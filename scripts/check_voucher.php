<?php

/*
NAME check_voucher.php is part of the voucher4guests Project
SYNOPSIS lifecycle vouchers and logdatabase 
DESCRIPTION checks daily over cron validity of voucher and 
de/activates vouchers and delete old log database entrys (if activated)
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpg dot de
AUTHORS Lars Uhlemann, lars_uhlemann at eva dot mpg dot de
VERSION 0.4a
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
#
*/

print "----------------------------------\n";
print date('d.m.Y - G:i')."\n";
print "----------------------------------\n";

$logging = "0";

chdir(dirname(__FILE__));
include('../config/database.php');

#open mysql database connection
$conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
mysql_select_db ($conf['db_base'], $conn) or die("cant find database");
#
#
# mark vouchers which where activated (by user) and now outdated in database as canceled
$result = mysql_query("SELECT vid, mac, expiration_time FROM vouchers WHERE canceled='0' AND (expiration_time<=NOW() AND expiration_time!='0000-00-00 00:00:00')");
while ($row = mysql_fetch_object($result)) {
    if ($row->mac != "") {
        $mac = wordwrap($row->mac, 2, ":", true);
        exec("sudo /sbin/iptables -D GUEST -t nat -m mac --mac-source $mac -j ACCEPT");
        print "Eintrag aus Firewall gelöscht: VID=".$row->vid."  MAC-Adresse=".$row->mac."\n";
	#mark this voucher as canceled in database
        $update = mysql_query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row->vid . "'") or die(mysql_error());
	print "This voucher is canceled (expired): VID=".$row->vid."  MAC-Adresse=".$row->mac."\n";
    }
}
mysql_free_result($result);
#
#
# mark vouchers which where never activated (by user) and wich over "use_by_date" as canceled
$result1a = mysql_query("SELECT vid, mac, activation_time FROM vouchers WHERE canceled='0' AND  (activation_time='0000-00-00 00:00:00' AND use_by_date<=NOW())");
while ($row = mysql_fetch_object($result1a)) {
	#mark this voucher as canceled in database
        $update1a = mysql_query("UPDATE vouchers SET canceled = '1' WHERE vid='" . $row->vid . "'") or die(mysql_error());
	print "This voucher is canceled (never used): VID=".$row->vid."  MAC-Adresse=".$row->mac."\n";
}
mysql_free_result($result1a);
#
#
# activate voucher when they reach their activation date
$result1 = mysql_query("SELECT vid FROM vouchers WHERE activation_time<=DATE_ADD(NOW(),INTERVAL 1 DAY) AND activ='0' AND mac=''");
while ($row = mysql_fetch_object($result1)) {
    $update1 = mysql_query("UPDATE vouchers SET activ = '1' WHERE vid='" . $row->vid . "'") or die(mysql_error());
    print "voucher activated: VID=".$row->vid."\n";
}
#
#
# delete vouchers which are canceled and older than 60 days
$result2 = mysql_query("SELECT vid, mac, expiration_time FROM vouchers WHERE canceled='1' AND (expiration_time<=DATE_SUB(NOW(), INTERVAL 60 DAY))");
while ($row = mysql_fetch_object($result2)) {
    # Eintrag loeschen
    $delete = mysql_query("DELETE FROM vouchers WHERE vid='" . $row->vid . "'") or die(mysql_error());
    print "voucher was deleted from database: VID=".$row->vid."  mac-address=".$row->mac." expiration time=".$row->expiration_time."\n";
}
mysql_close($conn);
#
#
# checking log entrys and delete them if there are older than 60 days
if ($logging != "0") {

	print "check Syslog tables and delete entry older than 60 days\n";
	
	include('../config/log_database.php');

	#open mysql database connection
	$conn = mysql_connect ($conf['log_db_host'],$conf['log_db_user'],$conf['log_db_password'])  or die ("database connection failed");
	mysql_select_db ($conf['log_db_base'], $conn) or die("cant find database");
	$result3 = mysql_query("SELECT ID FROM SystemEvents WHERE ReceivedAt<=DATE_SUB(NOW(),INTERVAL 60 DAY)");
	while ($row2 = mysql_fetch_object($result3)) {
	#delete entrys
		$delete2 = mysql_query("DELETE FROM SystemEvents WHERE ID='".$row2->ID."'") or die (mysql_error());
	}
	mysql_close($conn);
}

print "\n";

exit;
?>
