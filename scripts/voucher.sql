
-- NAME voucher.sql  is part of the voucher4guests Project
-- SYNOPSIS creates voucher database 
-- DESCRIPTION creates voucher database
-- AUTHOR Alexander Mueller, alexander_mueller at eva dot mpg dot de
-- AUTHOR Lars Uhlemann, lars_uhlemann at eva dot mpg dot de
-- VERSION 0.4
-- COPYRIGHT AND LICENSE 
--
-- (c) Alexander Mueller Lars Uhlemann
-- 
-- This software is released under GPLv2 license - see 
-- http://www.gnu.org/licenses/gpl-2.0.html


DROP DATABASE IF EXISTS `voucher`;
 
CREATE DATABASE `voucher`;
 
USE `voucher`;
 
 --
 -- Table structure for table `voucher`
 --
 DROP TABLE IF EXISTS `vouchers`;
 SET character_set_client = utf8;
 CREATE TABLE `vouchers` (
   `vid` integer unsigned NOT NULL auto_increment,
   `voucher_code` varchar(20) NOT NULL,
   `validity` smallint unsigned NOT NULL,
   `mac` varchar(12) NOT NULL,
   `canceled` tinyint unsigned NOT NULL,
   `printed` tinyint unsigned NOT NULL,
   `activ` tinyint unsigned NOT NULL,
   `activation_time` datetime NOT NULL,
   `expiration_time` datetime NOT NULL,
   `use_by_date` datetime NOT NULL,
 
   PRIMARY KEY  (`vid`)
 );

 --
 -- Table structure for table `validity`
 --
 DROP TABLE IF EXISTS `validities`;
 SET character_set_client = utf8;
 CREATE TABLE `validities` (
	`validity_id` smallint unsigned NOT NULL,
	`validity` smallint unsigned NOT NULL,
	`description` varchar(20) NOT NULL,	

	PRIMARY KEY  (`validity_id`)
 );
 INSERT INTO `validities` (`validity_id`,`validity`,`description`) VALUES ('0','0','from-to'),('1','7','7 days'),('2','30','1 month'),('3','180','6 months'),('4','365','1 year');
