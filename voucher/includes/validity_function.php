<?php
/*
NAME validity_function.php is part of the voucher4guests Project
SYNOPSIS checks voucher validity
DESCRIPTION check and display the voucher validity  
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/




function validity() {
	
include($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');

#open connection to database
$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
mysql_select_db ($conf['db_base'], $conn) or die("cant find database!");

#read mac address from arp cache
$mac = shell_exec("/usr/sbin/arp -an ".$_SERVER['REMOTE_ADDR']);
preg_match('/..:..:..:..:..:../',$mac , $matches);
if ( isset($matches[0])) {
  $mac_host = $matches[0];        
} else {
  $mac_host="";
}

#deletes special characters from mac address
$mac = preg_replace('/[^0-9a-fA-F]/', '', $mac_host);

$check_query1 = mysql_query("SELECT `voucher_code` FROM `vouchers` WHERE `mac`='$mac' AND `activ`='1' AND `canceled`='0' LIMIT 0,1", $conn);
$result1 = mysql_fetch_row($check_query1);
$result2 = array();


if ($result1[0] != ""){
	
	$voucher = wordwrap($result1[0],5," - ",true);

	$check_query2 = mysql_query("SELECT `expiration_time` FROM `vouchers` WHERE `mac` ='$mac' AND `activ`='1' AND `canceled`='0'");

	$result2 = mysql_fetch_row($check_query2);

	ereg("([0-9]{4}).([0-9]{2}).([0-9]{2})", $result2[0], $alter);
	$bdate="$alter[3].$alter[2].$alter[1]";

	$mac = wordwrap($mac, 2, ":", true);

	$ret = array("true", $voucher, $bdate, $mac);
	
}else{

	$ret = array("false", 0, 0, 0);
	
}

return($ret);
	
}	

?>
