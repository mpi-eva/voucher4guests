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

require_once '../includes/Db.php';

//load config
//$config = include('../../config/database.config.php');

$config = array(
    'db_base' => 'voucher4guests',
    'db_user' => '',
    'db_password' => '',
    'db_host' => 'localhost'
);

$db = new \Voucher\Scripts\Db($config);

//test database connection
print "\nTest database connection: ";
if(!$db->connect()){
    print "ERROR\n";
    print " The database connection could not be established\n";
    print "\nUpdate aborted\n";
    exit(0);
} else {
    print "READY\n";
}

/*$sql="DROP TABLE IF EXISTS `mac_addresses`;";
$db->query($sql);*/

$message = "";
$databaseIsReady = true;

//test if database is ready for update
print "\nTest if database is ready for update: ";

$sql="SHOW TABLES FROM ".$config['db_base'];
$tables = $db->query($sql);

$tables_arr = array();
while($row = $tables->fetch_array()){
    $tables_arr[] = $row[0];
}
if (array_search('vouchers', $tables_arr) === false){
    $databaseIsReady = false;
    $message = " Table 'vouchers' does not exist\n";
}
if (array_search('mac_addresses', $tables_arr) !== false){
    $databaseIsReady = false;
    $message = " New table 'mac_addresses' already exists\n";
}

if(!$databaseIsReady){
    print "ERROR\n";
    print " The database is not ready for the update\n";
    print $message;
    print "\nUpdate aborted\n";
    exit(0);
} else {
    print "READY\n";
}




//dump database
print "\nBackup current database:\n";

$sql_file = dirname( __FILE__ )."/dump_" . $config['db_base'] . "_" . date('Ymd_Hi') . ".sql";

print "Location: ".$sql_file."\n";
exec("mysqldump -u ".$config['db_user']." -p'".$config['db_password']."' --allow-keywords --add-drop-table --complete-insert --quote-names ".$config['db_base']." > ".$sql_file);

if (file_exists($sql_file)) {
    echo "Backup successful\n";
} else {
    echo "ERROR: Backup failed";
    print "\nUpdate aborted\n";
    exit(0);
}




//create new table
print "\nCreate new table 'mac_addresses': ";


$sql = "
CREATE TABLE `mac_addresses` (
  `mid`               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `vid`               INT UNSIGNED      NOT NULL,
  `mac_address`       VARCHAR(12)       NOT NULL,
  `active`            TINYINT UNSIGNED  NOT NULL,
  `activation_time`   DATETIME          NULL DEFAULT NULL,
  `deactivation_time` DATETIME          NULL DEFAULT NULL,
  PRIMARY KEY (`mid`)
);
";

if ($db->query($sql) === true) {
    print "OK\n";
    print " Table created successfully\n";
} else {
    print "ERROR\n";
    print " Error creating table: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}


//fill new table

print "Migrate data\n";

$result = $db->select("SELECT * FROM vouchers");

if ($result) {
    $i = 0;
    foreach ($result as $row) {
        $i++;
        if($row['mac']!='') {
            //print $row['mac']."\n";
            $insert = "INSERT INTO mac_addresses(vid, mac_address, active) VALUES(" . $row['vid'] . ", '" . $row['mac'] . "', " . $row['activ'] . ")";
            $result = $db->query($insert);
            if ($result) {
            } else {
                print "ERROR\n";
                print " Error insert entry in table mac_addresses: " . $db->error() . "\n";
                abortUpdate($sql_file, $config, $db);
            }
        }
    }

    print " ".$i." entries migrated\n";
} else {

}



//update other tables
print "Update voucher table\n";


//rename columns
$sql="SET SESSION sql_mode = '';";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error setting sql_mode: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//rename columns
$sql="ALTER TABLE `vouchers` CHANGE `activ` `active` TINYINT UNSIGNED NOT NULL;";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error renaming column: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//change column definition
$sql="ALTER TABLE `vouchers` CHANGE `canceled` `canceled` TINYINT UNSIGNED NOT NULL DEFAULT '0';";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error changing column definition: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//change column definition
$sql="ALTER TABLE `vouchers` CHANGE `activation_time` `activation_time` DATETIME NULL DEFAULT NULL;";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error changing column definition: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//change column definition
$sql="ALTER TABLE `vouchers` CHANGE `expiration_time` `expiration_time` DATETIME NULL DEFAULT NULL;";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error changing column definition: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//change column definition
$sql="ALTER TABLE `vouchers` CHANGE `use_by_date` `use_by_date` DATETIME NULL DEFAULT NULL;";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error changing column definition: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//replace 0-dates with real NULL-values
$sql="UPDATE `vouchers` SET `activation_time` = NULL WHERE `activation_time` = '0000-00-00 00:00:00';";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error replacing unknown date values: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//replace 0-dates with real NULL-values
$sql="UPDATE `vouchers` SET `expiration_time` = NULL WHERE `expiration_time` = '0000-00-00 00:00:00';";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error replacing unknown date values: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//replace 0-dates with real NULL-values
$sql="UPDATE `vouchers` SET `use_by_date` = NULL WHERE `use_by_date` = '0000-00-00 00:00:00';";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error replacing unknown date values: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

//remove not needed column
$sql="ALTER TABLE `vouchers` DROP `mac`;";
$result = $db->query($sql);
if ($result) {
} else {
    print "ERROR\n";
    print " Error removing column: " . $db->error() . "\n";
    abortUpdate($sql_file, $config, $db);
}

print "\nUpdate successful\n";



function abortUpdate($sql_file, $config, \Voucher\Scripts\Db $db) {
    print "\nUpdate aborted\n";
    print "\nRestore database\n";
    $sql="DROP TABLE IF EXISTS `mac_addresses`;";
    $db->query($sql);
    exec("mysql -u ".$config['db_user']." -p'".$config['db_password']."' ".$config['db_base']." < ".$sql_file);
    exit(0);
}

