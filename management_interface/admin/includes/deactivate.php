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



