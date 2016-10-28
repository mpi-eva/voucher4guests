<?php

require_once '../includes/Db.php';
require_once 'fpdf.php';


if ($_POST) {

    if (isset($_GET['quantity'])&& isset($_GET['validity'])) {
        $quantity = $_GET['quantity'];
        $validity = $_GET['validity'];
        if(isset($_GET['act_time'])&&isset($_GET['exp_time'])){
            $act_time = $_GET['act_time'];
            $exp_time = $_GET['exp_time'];

            createVoucher($quantity, $validity, $act_time, $exp_time);
        } else {
            createVoucher($quantity, $validity);
        }

        createPDF();
    }
}




/**
 * Create PDF with not printed vouchers
 */
function createPDF()
{
    //load config
    $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetCreator('voucher4guests');
    $pdf->SetAuthor('voucher4guests');
    $pdf->SetTitle('Voucher PDF');
    $pdf->SetAutoPageBreak(5);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $a = 0;
    $imgy = 0;
    $page = 0;

    $dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/../config/database.config.php');
    $db = new \Voucher\ManagementInterface\Db($dbConfig);

    $result = $db->select("SELECT vid, voucher_code, validities.validity, validities.description, activation_time, expiration_time, use_by_date FROM vouchers LEFT JOIN validities ON vouchers.validity = validities.validity_id WHERE printed = '0' ORDER BY vouchers.vid");

    if (!empty($result)) {
        foreach ($result as $row) {
            if ($page == 8) {
                $a = 0;
                $imgy = 0;
                $page = 0;
                $pdf->AddPage();
            }
            if ($a == 0) {
                $imgx = "0";
                $a = 1;
            } else {
                $imgx = "105";
                $a = 0;
            }

            $voucher_code = wordwrap($row['voucher_code'], 5, " - ", 1);

            $vid = sprintf("%05d", $row['vid']); # auf 5 Stellen mit 00000 auffuellen

            $use_by_date = formatDate($row['use_by_date']);

            if ($row['validity'] == 0) {
                $activation_time = formatDate($row['activation_time']);
                $expiration_time = formatDate($row['expiration_time']);
                $description = $activation_time . " - " . $expiration_time;
            } else {
                $description = $row['description'];
            }

            $pdf->Image('vorlagen/v' . $row['validity'] . '.jpg', $imgx, $imgy, -300);

            $pdf->SetFontSize(14);
            $pdf->SetXY($imgx + 7, $imgy + 31.2);
            $pdf->Cell(0, 10, $voucher_code);

            $pdf->SetFontSize(10);
            $pdf->SetXY($imgx + 42.4, $imgy + 40.7);
            $pdf->Cell(0, 10, $description);
# SSID >>>>>>>>>>>>>>		 
            $pdf->SetXY($imgx + 35.4, $imgy + 52);
            $pdf->Cell(0, 10, $config['voucher_network_name']);
# passphrase >>>>>>>>		 
            $pdf->SetXY($imgx + 37.2, $imgy + 57);
            $pdf->Cell(0, 10, $config['voucher_wlan_password']);

            $pdf->SetFontSize(8);
            $pdf->SetXY($imgx + 12.2, $imgy + 63.6);
            $pdf->Cell(0, 10, $vid);

            $pdf->SetXY($imgx + 84.7, $imgy + 63.6);
            $pdf->Cell(0, 10, $use_by_date);

            if ($a == 0) {
                $imgy = $imgy + 73.5;
            }
            $page++;

            $update = $db->query("UPDATE vouchers SET printed = '1' WHERE vid='" . $row['vid'] . "'");

        }

        $pdfName = "voucher_" . date('ymdHis') . ".pdf";
        $pdf->Output($pdfName, 'I');
    }
}

/**
 * Convert a date retrieved from MySQL
 *
 * @param $datetime
 * @return bool|string
 */
function formatDate($datetime)
{
    $time = strtotime($datetime);
    $formatted_date = date("d.m.Y", $time);

    return $formatted_date;
}


/**
 * generates voucher code.
 *
 * @return String
 */
function generateVoucherCode(){
    //load config
    $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

    $dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/../config/database.config.php');
    $db = new \Voucher\ManagementInterface\Db($dbConfig);

    $voucher_code = "";

    while(true) {

        $voucher_code = generateCode($config['voucher_code_length'], $config['voucher_allowed_characters']);

        //test if voucher code is already in use
        $result = $db->select("SELECT voucher_code FROM vouchers WHERE voucher_code = '" . $voucher_code . "'");

        if (empty($result)) {
            break;
        }
    }
    return $voucher_code;
}


/**
 *  insert new vouchers into theq database.
 *
 * @param $quantity
 * @param $validity
 * @param string $act_time
 * @param string $exp_time
 * @return bool
 */
function createVoucher($quantity, $validity, $act_time = 'NULL', $exp_time = 'NULL')
{
    //load config
    $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

    $dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/../config/database.config.php');
    $db = new \Voucher\ManagementInterface\Db($dbConfig);

    //a from-to voucher (validity 0) needs necessarily start and end dates.
    if ($validity == '0' AND ($act_time == 'NULL' OR $exp_time == 'NULL')) {
        return false;
    }

    $act_time_full = $act_time . ' 00:00:00';
    $exp_time_full = $exp_time . ' 23:59:59';
    $use_by_date = $exp_time_full;

    for ($i = 1; $i <= $quantity; $i++) {

        $voucher_code = generateVoucherCode();
        //default values
        $canceled = '0';
        $printed = '0';

        if ($validity == '0') {
            //from-to voucher
            $active = '0';
            if ($act_time <= date('Y-m-d')) {
                $active = '1';
            }

            $insert = $db->query("INSERT INTO vouchers(voucher_code, validity, canceled, printed, active, activation_time, expiration_time, use_by_date)
			   VALUES('"
                . $voucher_code .  "', '"
                . $validity . "', '"
                . $canceled . "', '"
                . $printed . "', '"
                . $active . "', '"
                . $act_time_full . "', '"
                . $exp_time_full . "', '"
                . $use_by_date . "')");

            if (!$insert) {
                print $db->error();
                //can't insert entry
                return false;
            }
        } else {
            $use_by_date = "DATE_ADD(NOW(), INTERVAL " .$config['voucher_lifetime'].")";
            $active = '1';

            $insert = $db->query("INSERT INTO vouchers(voucher_code, validity, canceled, printed, active, use_by_date)
			VALUES('"
                . $voucher_code . "', '"
                . $validity . "', '"
                . $canceled . "', '"
                . $printed . "', '"
                . $active . "', "
                . $use_by_date . ")");

            if (!$insert) {
                print $db->error();
                //can't insert entry
                return false;
            }
        }
    }
}


/**
 * generates random string.
 *
 * @param length - length of string
 * @return String
 */
function generateCode($length, $chars)
{
    $randstr = '';
    $lastKey = -1;

    shuffle($chars);
    $i = 1;
    while ($i <= $length) {
        $key = array_rand($chars, 1);

        if ($key == $lastKey) {
            continue;
        }

        /*  create upper and lowercase characters
        if (0 == ($key % 2)) {
          $randstr .= $chars[$key];
        } else {
          $randstr .= strtoupper($chars[$key]);
        }
        */

        $randstr .= $chars[$key];

        $lastKey = $key;
        $i++;
    }

    return $randstr;
}