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

require_once 'Db.php';


//load config
$dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/../config/database.config.php');

$db = new \Voucher\ManagementInterface\Db($dbConfig);

$voucher['data'] = array();
$result = $db->select("SELECT v.vid, v.voucher_code, va.description as validity, v.active, v.canceled, v.activation_time, v.expiration_time, v.use_by_date  FROM vouchers as v, validities as va WHERE v.validity = va.validity_id");
//print "<pre>";
if (!empty($result)) {
    foreach ($result as $entry) {

        $status = 'inactive';
        //get status
        if($entry['active'] == '1'){
            $status = 'active';
        }
        if($entry['canceled'] == '1'){
            $status = 'expired';
        }
        $entry['status'] = $status;


        //get mac addresses
        $result1 = $db->select("SELECT * FROM mac_addresses WHERE vid=" . $entry['vid']);
        if (!empty($result1)) {
            $macs = array();
            foreach ($result1 as $entry1) {
                array_push($macs, $entry1);
            }
            $entry['macs'] = $macs;
        }

        //print_r($entry);
        array_push($voucher['data'], $entry);
    }
}
//print_r($voucher);
echo json_encode($voucher);