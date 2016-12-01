<?php
/**
 * This file is part of voucher4guests.
 *
 * voucher4guests Project - An open source captive portal system
 * Copyright (C) 2016. Alexander Müller, Lars Uhlemann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Voucher\ManagementInterface;

require_once 'Db.php';

class VoucherActions
{
    /**
     * @var
     */
    private $dbConfig;

    /**
     * @return array An Array with the database connection settings
     */
    public function getDbConfig()
    {
        if (!isset($this->dbConfig)) {
            $this->dbConfig = include ('../../../config/database.config.php');
        }

        return $this->dbConfig;
    }


    /**
     * @param $mac
     * @return bool
     */
    public function deactivateMac($mac)
    {
        $db = new Db($this->getDbConfig());
        $mac = self::format_mac($mac, "", false);

        $result = $db->select("SELECT v.vid, m.mid
                               FROM vouchers AS v, mac_addresses AS m
                               WHERE v.vid = m.vid
                               AND m.mac_address='" . $mac . "' AND m.active='1'
                               AND v.canceled != '1' LIMIT 0,1");

        if (!empty($result)) {
            $update = $db->query("UPDATE mac_addresses SET active = '0', deactivation_time = NOW() WHERE mid=" . $result[0]['mid']);

            if ($update) {
                $mac_host = self::format_mac($mac, ":", false);
                // deletes mac address from iptables ruleset
                exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac_host -j ACCEPT");

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
     * Mark expired vouchers as 'canceled' and remove the firewall rule
     *
     * @param $vid
     * @return bool
     */
    public function deactivateVoucher($vid)
    {
        $db = new Db($this->getDbConfig());
        $result = $db->select("SELECT vid FROM vouchers WHERE vid=".$vid." ");
        if (!empty($result)) {
            foreach ($result as $row) {


                $result1 = $db->select("SELECT m.vid, m.mac_address
                               FROM mac_addresses AS m
                               WHERE m.vid='" . $row['vid'] . "'
                               AND m.active = 1
                               ");

                foreach ($result1 as $mac_row) {

                    //recreate normal mac address schema
                    $mac = self::format_mac($mac_row['mac_address'], ":", false);
                    exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");

                    print "Eintrag aus Firewall gelöscht: VID=" . $row['vid'] . ", MAC=" . $mac . "\n";
                    #mark this voucher as canceled in database
                    $update = $db->query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row['vid'] . "'");
                    print "Voucher wurde deaktiviert VID=" . $row['vid'] . "\n";
                }
            }
            return true;
        } else {
            return false;
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