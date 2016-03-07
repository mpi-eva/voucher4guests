<?php
/*NAME create_voucher.php is part of the voucher4guests Project
SYNOPSIS create voucher pdf's
DESCRIPTION create pdf's of new generated vouchers 
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see
http://www.gnu.org/licenses/gpl-2.0.html
*/




$quantity= $_GET['quantity'];
$validity= $_GET['validity'];
$act_time= $_GET['act_time'];
$exp_time= $_GET['exp_time'];


#open mysql database connection
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');
	
$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
mysql_select_db ($conf['db_base'], $conn) or die("cant find database.");

include('func.php');
AddVoucher($quantity, $validity, $act_time, $exp_time);


### create PDF 
require('fpdf.php');
$pdf = new FPDF('P','mm','A4');
$pdf->SetCreator('voucher4guests');
$pdf->SetAuthor('voucher4guests');
$pdf->SetTitle('Voucher PDF');
$pdf->SetAutoPageBreak(5);
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$a=0;
$imgy = 0;
$page= 0;

 function convertDatum($datetime) {
   	$newdate = date("d.m.Y", mktime(0,0,0, (int)substr($datetime,5,2), (int)substr($datetime,8,2), (int)substr($datetime,0,4) ) );
   	return ($newdate);	 
 }

$result = mysql_query("SELECT vid, voucher_code, validities.validity, validities.description, activation_time, expiration_time, use_by_date FROM vouchers LEFT JOIN validities ON vouchers.validity = validities.validity_id WHERE printed = '0' ORDER BY vouchers.vid");
$num_rows = mysql_num_rows($result); 

if($num_rows >= "1") {

	while ($row = mysql_fetch_object($result)) {
		     
	   if($page == 8) {
		$a = 0;
		$imgy = 0;
		$page = 0;
		$pdf->AddPage();
	   }
	   if($a==0) {
			$imgx="0";
			$a=1;
		} else {
			$imgx="105";
			$a=0;
		}
		
		$voucher_code = wordwrap($row->voucher_code, 5, " - ", 1);
		
		$vid = sprintf("%05d", $row->vid); # auf 5 Stellen mit 00000 auffuellen
		
		$use_by_date = convertDatum($row->use_by_date);
		
		if($row->validity == 0) {
			$activation_time = convertDatum($row->activation_time);
			$expiration_time = convertDatum($row->expiration_time);
			$description = $activation_time." - ".$expiration_time;
		} else {
			$description = $row->description;
		}
		
		$pdf->Image('vorlagen/v'.$row->validity.'.jpg',$imgx,$imgy,-300);
		
		$pdf->SetFontSize(14);
		$pdf->SetXY($imgx+7,$imgy+31.2);	
		$pdf->Cell(0,10,$voucher_code);
		
		 $pdf->SetFontSize(10);
		 $pdf->SetXY($imgx+42.4,$imgy+40.7);
		 $pdf->Cell(0,10,$description);
# SSID >>>>>>>>>>>>>>		 
		 $pdf->SetXY($imgx+35.4,$imgy+52);
		 $pdf->Cell(0,10,'WLAN SSID');
# passphrase >>>>>>>>		 
		 $pdf->SetXY($imgx+37.2,$imgy+57);
		 $pdf->Cell(0,10,'xxxxxxxxxxxxx');
		
		$pdf->SetFontSize(8);
	 	 $pdf->SetXY($imgx+12.2,$imgy+63.6);
		 $pdf->Cell(0,10,$vid);
		 
		 $pdf->SetXY($imgx+84.7,$imgy+63.6);
		 $pdf->Cell(0,10,$use_by_date);
	    
	    if($a == 0) {
			$imgy= $imgy + 73.5;
		}
		$page++;
		
		$update = mysql_query("UPDATE vouchers SET printed = '1' WHERE vid='" . $row->vid . "'") or die(mysql_error());	   
	}
	
	$pdfname="voucher_".date('ymdHis').".pdf";
	$pdf->Output($pdfname, 'I');

} else {
	print "no voucher created"	;
}

mysql_close($conn);

?>
