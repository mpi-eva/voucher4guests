<?php
/*
NAME logout_function.php is part of the voucher4guests Project
SYNOPSIS user initiated deactivation of voucher
DESCRIPTION user can deativate his active voucher by himself  
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/

function logout() {
	include($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');
	
	#open connection to mysql database
	$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
	mysql_select_db ($conf['db_base'], $conn) or die("cant find database!");
	
	# reads mac address from arp cache
	$mac = shell_exec("/usr/sbin/arp -an ".$_SERVER['REMOTE_ADDR']);
	preg_match('/..:..:..:..:..:../',$mac , $matches);
	if ( isset($matches[0])) {
      $mac_host = $matches[0];        
   } else {
      $mac_host="";
   }
	
	#deletes special characters from mac address
	$mac = preg_replace('/[^0-9a-fA-F]/', '', $mac_host);
	
	$check_query1 = mysql_query("SELECT `voucher_code` FROM `vouchers` WHERE `mac` ='$mac' AND `canceled`!='1' LIMIT 0,1", $conn);
	$result1 = mysql_fetch_row($check_query1);
	
	if ($result1[0] != ""){
		$update = mysql_query("UPDATE vouchers SET canceled = '1', expiration_time=NOW() WHERE mac='$mac'") or die(mysql_error());
		# deletes mac address from iptables ruleset
		exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac_host -j ACCEPT");
	
		$ret = true;
	}else{
		$ret = false;	
	}
	return $ret;
}	

?>
