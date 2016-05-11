<?php
chdir(dirname(__FILE__));
require_once 'VoucherActions.php';

$va = new \Voucher\ManagementInterface\VoucherActions();

if ($_POST) {

    if (isset($_POST['option']) && isset($_POST['data'])) {
        $option = $_POST['option'];
        $data = $_POST['data'];
        if($option =='mac'){

            preg_match('/([0-9a-fA-F][0-9a-fA-F][\.:-]?){5}([0-9a-fA-F][0-9a-fA-F])/', $data, $matches);

            if (!empty($matches[0])) {
                $mac = $matches[0];
                $result = $va->deactivateMac($mac);
                if ($result){
                    echo "MAC-Adresse wurde erfolgreich entfernt.";
                } else {
                    echo "MAC-Adresse konnte nicht entfernt werden.";
                }
            } else {
                echo "Keine gültige MAC-Adresse";
            }


        }
        if ($option == 'vid'){
            preg_match('/[0-9]+/', $data, $matches);

            if (!empty($matches[0])) {

                $vid = preg_replace('/^0{*}/','',$matches[0]);
                $result = $va->deactivateVoucher($vid);
                if (!$result){
                    echo "\nVoucher konnte nicht entfernt werden.";
                }
            } else {
                echo "\nKeine gültige VID";
            }


        }
        print_r($_POST);

    }
}



