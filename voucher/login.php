<?php


/*
NAME login.php is part of the voucher4guests Project
SYNOPSIS open db connection and other misc things 
DESCRIPTION open db connetcion, reads client mac address, checks voucher for
this mac, add iptables entry when voucher is valid  or deletes iptables entry 
if voucher is invalid
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/

#prevent brute force attacks
sleep(1);

include($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');

#open MySQL - database connection
$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
mysql_select_db ($conf['db_base'], $conn) or die("cant find database!");

$lang_array = array('de', 'en');
if(!isset($_GET['lang'])) $language = 'en';

if(isset($_GET['lang']) && in_array($_GET['lang'], $lang_array)){ 
  $language = $_GET['lang'];
}

if(!isset($_GET['mobil']) || $_GET['mobil'] != "off") {
	$mobil = 'on';
} else {
	$mobil = 'off';	
}

$site="";
if($_GET['site'] == "mobil") $site = "mobil/"; 

$ssl="";
if($_GET['ssl'] == "1") $ssl = "&ssl=1";

$add=$_GET['add'];

$attach = "&lang=".$language."&add=".$add."&mobil=".$mobil."".$ssl;


if ( isset($_POST['IX1']) && isset($_POST['IX2']) && isset($_POST['IX3']) && isset($_POST['IX4']) ) {
   $voucher_code = "".$_POST['IX1']."".$_POST['IX2']."".$_POST['IX3']."".$_POST['IX4'];
} else {
   $voucher_code = "";
}

#checks if policy was accepted
if ($_POST['policy'] != "accept"){
	header ("Location: ".$site."index.php?err=4".$attach);
	exit;	
}


# reads MAC address from arp cache
$mac = shell_exec("/usr/sbin/arp -an ".$_SERVER['REMOTE_ADDR']);
preg_match('/..:..:..:..:..:../',$mac , $matches);
if ( isset($matches[0])) {
  $mac_host = $matches[0];        
} else {
  $mac_host="";
}

# checks mac address is a valid one
if (preg_match("/^([0-9a-f]{2}([:]|$)){6}$|[0-9a-f]{12}/i", $mac_host)) {
}else{
   header ("Location: ".$site."index.php?err=5".$attach);
   exit;
}

#deletes special characters from mac address
$mac = preg_replace('/[^0-9a-fA-F]/', '', $mac_host);

#checks if mac address is in database
$check_query1 = mysql_query("SELECT `voucher_code` FROM `vouchers` WHERE `voucher_code` ='$voucher_code' LIMIT 0,1", $conn);
$result1 = mysql_num_rows($check_query1);

if ($result1 < 1){
	print $result1."test";
	header ("Location: ".$site."index.php?err=1".$attach);
	exit;
}else{
	#checks if voucher code exists in database
        $check_query2 = mysql_query("SELECT `canceled` FROM `vouchers` WHERE `voucher_code` ='$voucher_code' LIMIT 0,1", $conn);
        $result2 = mysql_fetch_row($check_query2);
        if ($result2[0] == 1){
                header ("Location: ".$site."index.php?err=3".$attach);
                exit;
        }else{
		#is voucher already active?
    	    	$check_query3 = mysql_query("SELECT `activ` FROM `vouchers` WHERE `voucher_code` ='$voucher_code' LIMIT 0,1", $conn);
    		$result3 = mysql_fetch_row($check_query3);
      	 	if ($result3[0] == 0){
        		      header ("Location: ".$site."index.php?err=10".$attach);
        	       	exit;
     	  	}else{

			#is there a mac address already set for this voucher?
			$check_query4 = mysql_query("SELECT `mac` FROM `vouchers` WHERE `voucher_code` ='$voucher_code'", $conn);
    			$result4 = mysql_fetch_row($check_query4);
		
	    		if ($result4[0] != ""){
       				header ("Location: ".$site."index.php?err=2".$attach);
				      exit;
			}else{
	
				#deactivates another voucher with the same mac address
				$sql2="UPDATE vouchers SET activ = '0' WHERE mac='$mac'";
        			$erg3 = mysql_db_query($db_user,$sql2,$conn);

      		  		if (!mysql_query($sql2,$conn)){
        				die('Error: ' . mysql_error());
        			}

				#checks validity timeframe of this voucher	
				$check_query5 = mysql_query("SELECT validities.validity FROM `vouchers` LEFT JOIN `validities` ON vouchers.validity = validities.validity_id WHERE `voucher_code` ='$voucher_code'");
			#	$check_query5 = mysql_query("SELECT `validity` FROM `voucher` WHERE `voucher` ='$voucher_code'");
       		 		$result5 = mysql_fetch_row($check_query5);
				
				if ($result5[0] == 0){   # bei from-to Voucher

					$sql1="UPDATE vouchers SET mac='$mac' WHERE voucher_code='$voucher_code'";
				}else{

					#insert hostdata like mac,activationtime etc in database
					$sql1="UPDATE vouchers SET mac='$mac', activation_time=NOW(), expiration_time=DATE_ADD(NOW(),INTERVAL $result5[0] DAY) 
					WHERE voucher_code='$voucher_code'";
				}

				$erg2 = mysql_db_query($db_user,$sql1,$conn);

				if (!mysql_query($sql1,$conn)){
 					die('Error: ' . mysql_error());
 				}
	

				#recreate normal mac address schema
				$n_mac = wordwrap($mac,2,":",true);
			
				#delete mac address from iptables ruleset
				exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $n_mac -j ACCEPT");

				#add mac address to iptables ruleset				
				exec("sudo /sbin/iptables -I GUEST 1 -t nat -m mac --mac-source $n_mac -j ACCEPT");

				#forward to website 
				##SSL hier noch notwendig, da eh sinnfrei (Zertifikatsproblematik)?
				if ($_GET['ssl'] == "1"){
					header("location:https://".$_GET['add']);
					exit;
				}elseif ($_GET['add'] == ""){
					header("location:http://".$_SERVER['SERVER_NAME']."".$site."/index.php?err=11".$attach);
					exit;
				}else{
					header("location:http://".$_GET['add']);
					exit;
				}


			}
		}
	}
}

mysql_close($conn);
exit;
?>
