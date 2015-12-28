<?php

require_once 'Db.php';


//load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

$db = new Db();

$voucher['data'] = array();
$result = $db->select("SELECT * FROM vouchers");
//print "<pre>";
if (!empty($result)) {
    foreach ($result as $entry) {

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