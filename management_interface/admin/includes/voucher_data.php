<?php

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