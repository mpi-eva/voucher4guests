<?php
namespace Voucher;

require_once 'Db.php';

class VoucherService
{
    /**
     * Message constants
     */
    const MESSAGE_SYSTEM_ERROR = 0;
    const MESSAGE_ACTIVATION_SUCCESSFUL = 1;
    const MESSAGE_INVALID_VOUCHER = 2;
    const MESSAGE_DEVICE_ALREADY_ACTIVATED = 3;
    const MESSAGE_EXPIRED_VOUCHER = 4;
    const MESSAGE_NOT_ACTIVATED_VOUCHER = 5;    
    const MESSAGE_MAX_DEVICES_ACTIVATED = 6;

    private $config;
    private $dbConfig;

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


    /**
     *  Activate a device with a given voucher code
     *
     * @param $voucher_code - voucher code without hyphens
     * @return bool|int - False on failure / Message code
     */
    public function login($voucher_code)
    {
        //prevent brute force attacks
        sleep(1);

        //load config
        $config = $this->getConfig();

        $db = new Db($this->getDbConfig());
        $mac = self::read_client_mac();
        $mac = self::format_mac($mac, "", false);
        $voucher_code = $db->connect()->real_escape_string($voucher_code);

        //checks if voucher code exists in database
        $result = $db->select("SELECT v.vid, v.voucher_code, v.active, v.canceled, v.validity
                               FROM vouchers AS v
                               WHERE v.voucher_code='" . $voucher_code . "'
                               LIMIT 0,1");
        if (empty($result)) {
            //ERROR - Voucher invalid
            $message = self::MESSAGE_INVALID_VOUCHER;
        } else {
            //is voucher already canceled
            if ($result[0]['canceled'] == 1) {
                //ERROR - Voucher expired
                $message = self::MESSAGE_EXPIRED_VOUCHER;
            } else {

                //is voucher activated
                if ($result[0]['active'] == 0) {
                    print "ERROR - Voucher not activated";
                    $message = self::MESSAGE_NOT_ACTIVATED_VOUCHER;
                } else {
                    $vid = $result[0]['vid'];
                    //Check if MAC can be activated
                    $result1 = $db->select("SELECT m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.vid='" . $vid . "'
                               AND m.active = 1
                               ");

                    $already_set = false;

                    if (!empty($result1)) {

                        //is there a mac address already set for this voucher?
                        foreach ($result1 as $mac_entry) {
                            if ($mac == $mac_entry['mac_address']) {
                                $already_set = true;
                                break;
                            }
                        }
                    }
                    if ($already_set) {
                        //ERROR - Device already activated
                        $message = self::MESSAGE_DEVICE_ALREADY_ACTIVATED;
                    } else {

                        if (count($result1) >= $config['max_devices']) {
                            //ERROR - Maximum number of devices registered
                            $message = self::MESSAGE_MAX_DEVICES_ACTIVATED;
                        } else {
                            //deactivate mac address on another voucher
                            $result2 = $db->select("SELECT m.mid, m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.mac_address='" . $mac . "'
                               AND m.active = 1
                               LIMIT 0,1");

                            if (!empty($result2)) {
                                foreach ($result2 as $mac_found) {
                                    print_r($mac_found);
                                    $update = $db->query("UPDATE mac_addresses SET active = 0 WHERE mid=" . $mac_found['mid']);

                                    if (!$update) {
                                        //can't update mac entry
                                        return false;
                                    }
                                }
                            }

                            //add mac to voucher
                            $insert = $db->query("INSERT INTO mac_addresses (vid, mac_address, active, activation_time)
                                                  VALUES (" . $vid . ",'" . $mac . "',1,NOW())");

                            if (!$insert) {
                                //can't insert mac entry
                                return false;
                            }

                            //get validity from voucher
                            $result3 = $db->select("SELECT validity
                                                    FROM validities
                                                    WHERE validity_id='" . $result[0]['validity'] . "'
                                                    ");

                            if (!empty($result3)) {
                                //set time
                                $validity = $result3[0]['validity'];

                                if ($validity > 0) {
                                    $update1 = $db->query("UPDATE vouchers
                                                           SET activation_time = NOW(),
                                                              expiration_time = DATE_ADD(NOW(),INTERVAL " . $validity . " DAY)
                                                           WHERE vid=" . $vid);

                                    if (!$update1) {
                                        //can't update voucher entry
                                        return false;
                                    }
                                }


                                //recreate normal mac address schema
                                $mac = self::format_mac($mac, ":", false);

                                //delete mac address from iptables ruleset
                                //exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");

                                //add mac address to iptables ruleset
                                //exec("sudo /sbin/iptables -I GUEST 1 -t nat -m mac --mac-source $mac -j ACCEPT");

                                $message = self::MESSAGE_ACTIVATION_SUCCESSFUL;
                                
                            } else {
                                //can't find validity
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return $message;
    }


    /**
     * Get information about the validity of a voucher
     *
     * @return array -  voucher information
     */
    public function get_validity()
    {
        //load config
        $config = $this->getConfig();

        $db = new Db($this->getDbConfig());
        $mac = self::read_client_mac();
        $mac = self::format_mac($mac, "", false);

        $result = $db->select("SELECT v.vid, v.voucher_code, v.expiration_time
                               FROM vouchers AS v, mac_addresses AS m
                               WHERE v.vid = m.vid
                               AND m.mac_address='" . $mac . "' AND m.active='1'
                               AND v.active='1' AND v.canceled='0'
                               LIMIT 0,1");

        if (!empty($result)) {

            $vid = $result[0]['vid'];
            $result1 = $db->select("SELECT m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.vid='" . $vid . "'
                               AND m.active = 1
                               ");

            $remaining = $config['max_devices'];
            if (!empty($result1)) {
                $remaining = $remaining - count($result1);
            }

            $voucher = wordwrap($result[0]['voucher_code'], 5, " - ", true);

            $time = strtotime($result[0]['expiration_time']);
            $exp_date = date("d.m.Y", $time);

            $mac = self::format_mac($mac, ":", true);

            $ret = array(
                "activated" => true,
                "voucher_code" => $voucher,
                "expiration_date" => $exp_date,
                "mac" => $mac,
                "remaining" => $remaining
            );
        } else {
            $ret = array(
                "activated" => false
            );
        }

        return ($ret);

    }

    /**
     * Logout a device
     *
     * @return bool - true if logout was successful
     */
    public function logout()
    {
        $db = new Db($this->getDbConfig());
        $mac = self::read_client_mac();
        $mac = self::format_mac($mac, "", false);

        $result = $db->select("SELECT v.vid, m.mid
                               FROM vouchers AS v, mac_addresses AS m
                               WHERE v.vid = m.vid
                               AND m.mac_address='" . $mac . "' AND m.active='1'
                               AND v.canceled != '1' LIMIT 0,1");

        if (!empty($result)) {

           // $update = $db->query("UPDATE vouchers SET canceled = '1', expiration_time=NOW() WHERE vid=" . $result[0]['vid']);
           // $update = $db->query("UPDATE mac_addresses SET active = '0' WHERE vid=" . $result[0]['vid']." AND mac_address='".$mac."'");
            $update = $db->query("UPDATE mac_addresses SET active = '0', deactivation_time = NOW() WHERE mid=" . $result[0]['mid']);

            if ($update) {
                // deletes mac address from iptables ruleset
                //exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac_host -j ACCEPT");

                $ret = true;
            } else {
                $ret = false;
            }
        } else {
            $ret = false;
        }

        return $ret;
    }


    /**
     * Read client MAC address from local arp cache
     *
     * @return null|string
     */
    static function read_client_mac()
    {
        $mac = null;
        //$mac = shell_exec("/usr/sbin/arp -an " . $_SERVER['REMOTE_ADDR']);
        $mac = shell_exec("/usr/sbin/arp -an 192.168.41.47");
        preg_match('/..:..:..:..:..:../', $mac, $matches);
        if (!empty($matches[0])) {
            $mac = $matches[0];
        }

        return $mac;
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

    /**
     * Formatting the voucher code
     *
     * @param $code
     * @param string $separator
     * @return string
     */
    public function format_voucher_code($code, $separator = "")
    {
        //load config
        $config = $this->getConfig();

        $allowed_characters = implode($config['allowed_characters']);
        $allowed_characters = preg_quote($allowed_characters,'/');

        //deletes special characters from mac address
        $code = preg_replace('/[^'.$allowed_characters.']/', '', $code);

        if ($separator != "") {
            $code = wordwrap($code, 5, $separator, true);
        }
        return $code;
    }

}