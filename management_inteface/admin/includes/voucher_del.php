<?php

/*NAME voucher_del.php is part of the voucher4guests Project
SYNOPSIS delete vouchers
DESCRIPTION mark vouchers as "canceled" and removes firewall entry
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpg dot de
AUTHORS Lars Uhlemann, lars_uhlemann at eva dot mpg dot de
VERSION 0.4
COPYRIGHT AND LICENSE

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see
http://www.gnu.org/licenses/gpl-2.0.html
*/

#open mysql database connection 
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');
	
$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
mysql_select_db ($conf['db_base'], $conn) or die("cant find database");


$v = $_GET['vid'];

$update = "UPDATE `vouchers` SET `canceled`='1' WHERE `vid`=$v";
$request ="SELECT mac FROM vouchers WHERE `vid`=$v";

$result = mysql_query($update);
if (!$result) {
    die('undefined result: ' . mysql_error());
}else{
  $deleted=mysql_affected_rows();
  if ($deleted == "1"){
      
       $mac = mysql_fetch_object(mysql_query($request));

       if ($mac->mac !="") {
          $mac = wordwrap($mac->mac, 2, ":", true);
          # delete mac address from iptables ruleset
          exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");
       }
  }
  echo $deleted;
}
?>

