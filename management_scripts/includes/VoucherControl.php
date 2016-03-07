<?php
namespace Voucher\Scripts;

require_once 'Db.php';

class VoucherControl
{
    private $config;
    private $dbConfig;
    private $logDbConfig;


    public function getConfig()
    {
        if (!isset($this->config)) {
            $this->config = include ($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
        }

        return $this->config;
    }

    public function getDbConfig()
    {
        if (!isset($this->dbConfig)) {
            $this->dbConfig = include ($_SERVER['DOCUMENT_ROOT'].'/../config/database.config.php');
        }

        return $this->dbConfig;
    }

    public function getLogDbConfig()
    {
        if (!isset($this->logDbConfig)) {
            $this->logDbConfig = include ($_SERVER['DOCUMENT_ROOT'].'/../config/database.config.php');
        }

        return $this->LogDbConfig;
    }


    # mark vouchers which where activated (by user) and now outdated in database as canceled
    public function deactivateVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE canceled='0' AND (expiration_time<=NOW() AND expiration_time!='0000-00-00 00:00:00')");
        foreach($result as $row) {


            $result1 = $db->select("SELECT m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.vid='" . $row['vid'] . "'
                               AND m.active = 1
                               ");

            foreach ($result1 as $mac_row) {

                //recreate normal mac address schema
                $mac = self::format_mac($mac_row['mac_address'], ":", false);
                // exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");

                print "Eintrag aus Firewall gelöscht: VID=".$row['vid'].", MAC=".$mac."\n";
                #mark this voucher as canceled in database
                $update = $db->query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row['vid'] . "'");
                print "This voucher is canceled (expired): VID=".$row['vid']."\n";
            }
        }
    }

    # mark vouchers which where never activated (by user) and which over "use_by_date" as canceled
    public function deactivateUnusedVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE canceled='0' AND  (activation_time='0000-00-00 00:00:00' AND use_by_date<=NOW())");
        foreach($result as $row) {
            #mark this voucher as canceled in database
            $update = $db->query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row['vid'] . "'");
            print "This voucher is canceled (never used): VID=".$row['vid']."\n";
        }
    }

    # activate voucher when they reach their activation date
    public function activateVoucher()
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE activation_time<=DATE_ADD(NOW(),INTERVAL 1 DAY) AND active='0' AND canceled='0'");
        foreach($result as $row) {
            $update = $db->query("UPDATE vouchers SET activ = '1' WHERE vid='" . $row['vid']. "'");
            print "voucher activated: VID=".$row['vid']."\n";
        }
    }

    # delete vouchers which are canceled and older than 60 days
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

    # checking log entries and delete them if there are older than 60 days
    public function removeLogEntries()
    {
        print "check Syslog tables and delete entry older than 60 days\n";

        $db = new Db($this->getLogDbConfig());
        $result = $db->query("SELECT ID FROM SystemEvents WHERE ReceivedAt<=DATE_SUB(NOW(),INTERVAL 60 DAY)");
        foreach($result as $row) {
            #delete entries
            $delete = $db->query("DELETE FROM SystemEvents WHERE ID='".$row['ID']."'");
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