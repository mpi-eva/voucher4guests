<?php
namespace Voucher\Scripts;

require_once 'Db.php';

/**
 * Class VoucherControl
 * @package Voucher\Scripts
 */
class VoucherControl
{
    /**
     * @var
     */
    private $config;
    /**
     * @var
     */
    private $dbConfig;

    /**
     * @return array An Array with config values
     */
    public function getConfig()
    {
        if (!isset($this->config)) {
            $this->config = include ('../config/config.php');
        }

        return $this->config;
    }

    /**
     * @return array An Array with the database connection settings
     */
    public function getDbConfig()
    {
        if (!isset($this->dbConfig)) {
            $this->dbConfig = include ('../config/database.config.php');
        }

        return $this->dbConfig;
    }

    /**
     * Mark expired vouchers as 'canceled' and remove the firewall rule
     *
     * @return void
     */
    public function deactivateVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE canceled='0' AND (expiration_time<=NOW() AND expiration_time!=NULL)");
        foreach($result as $row) {


            $result1 = $db->select("SELECT m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.vid='" . $row['vid'] . "'
                               AND m.active = 1
                               ");

            foreach ($result1 as $mac_row) {

                //recreate normal mac address schema
                $mac = self::format_mac($mac_row['mac_address'], ":", false);
                exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");

                print "Eintrag aus Firewall gelöscht: VID=".$row['vid'].", MAC=".$mac."\n";
                #mark this voucher as canceled in database
                $update = $db->query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row['vid'] . "'");
                print "This voucher is canceled (expired): VID=".$row['vid']."\n";
            }
        }
    }

    # mark vouchers which where never activated (by user) and which over "use_by_date" as canceled
    /**
     * Mark unused voucher on the expiry date as 'canceled'
     * @return void
     */
    public function deactivateUnusedVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE canceled='0' AND  (activation_time=NULL AND use_by_date<=NOW())");
        foreach($result as $row) {
            #mark this voucher as canceled in database
            $update = $db->query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row['vid'] . "'");
            print "This voucher is canceled (never used): VID=".$row['vid']."\n";
        }
    }

    /**
     * Activate voucher which start at an defined date
     *
     * @return void
     */
    public function activateVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE activation_time<=DATE_ADD(NOW(),INTERVAL 1 DAY) AND active='0' AND canceled='0'");
        foreach($result as $row) {
            $update = $db->query("UPDATE vouchers SET active = '1' WHERE vid='" . $row['vid']. "'");
            print "voucher activated: VID=".$row['vid']."\n";
        }
    }

    /**
     * Remove voucher form the database which are 'canceled' and older than a defined period of time.
     *
     * @return void
     */
    public function removeVoucher()
    {

        $config = $this->getConfig();
        $db = new Db($this->getDbConfig());

        $expiration_time = "DATE_SUB(NOW(), INTERVAL ".$config['voucher_retention_period'].")";

        $result = $db->select("SELECT vid, expiration_time FROM vouchers WHERE canceled='1' AND (expiration_time<= ".$expiration_time.")");
        foreach($result as $row) {

            $delete_mac = $db->query("DELETE FROM mac_addresses WHERE vid='" . $row['vid'] . "'");

            # remove entry
            $delete = $db->query("DELETE FROM vouchers WHERE vid='" . $row['vid'] . "'");
            print "voucher was deleted from database: VID=".$row['vid']." expiration time=".$row['expiration_time']."\n";
        }
    }

    /**
     * Reload the firewall rules for active mac addresses
     *
     * @return void
     */
    public function reloadVoucher()
    {
        $db = new Db($this->getDbConfig());

        $result = $db->query("SELECT m.mac_address FROM vouchers as v, mac_addresses as m WHERE v.vid=m.vid and v.active='1' and v.canceled='0' and m.active = '1';");
        foreach($result as $row) {
            $mac = self::format_mac($row['mac_address'], ":", false);
            exec("sudo /sbin/iptables -I GUEST 1 -t nat -m mac --mac-source $mac -j ACCEPT");
        }

    }

    /**
     * Formatting the MAC address
     *
     * @param $mac
     * @param string $separator
     * @param bool $toUppercase
     * @return string
     */
    static function format_mac($mac, $separator = "", $toUppercase = false)
    {
        //deletes special characters from mac address
        $mac = preg_replace('/[^0-9a-fA-F]/', '', $mac);

        if ($toUppercase) {
            $mac = strtoupper($mac);
        } else {
            $mac = strtolower($mac);
        }

        if ($separator != "") {
            $mac = wordwrap($mac, 2, $separator, true);
        }

        return $mac;
    }
}