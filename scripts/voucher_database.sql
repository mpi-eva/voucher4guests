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
CREATE DATABASE IF NOT EXISTS `voucher`;
USE `voucher`;


-- Table structure for table `validity`

DROP TABLE IF EXISTS `validities`;
SET character_set_client = utf8;
CREATE TABLE `validities` (
  `validity_id` SMALLINT UNSIGNED NOT NULL,
  `validity`    SMALLINT UNSIGNED NOT NULL,
  `description` VARCHAR(20)       NOT NULL,
  PRIMARY KEY (`validity_id`)
);


-- Table structure for table `voucher`

DROP TABLE IF EXISTS `vouchers`;
SET character_set_client = utf8;
CREATE TABLE `vouchers` (
  `vid`             INTEGER UNSIGNED  NOT NULL AUTO_INCREMENT,
  `voucher_code`    VARCHAR(20)       NOT NULL,
  `validity`        SMALLINT UNSIGNED NOT NULL,
  `canceled`        TINYINT UNSIGNED  NOT NULL,
  `printed`         TINYINT UNSIGNED  NOT NULL,
  `activ`           TINYINT UNSIGNED  NOT NULL,
  `activation_time` DATETIME          NOT NULL,
  `expiration_time` DATETIME          NOT NULL,
  `use_by_date`     DATETIME          NOT NULL,
  PRIMARY KEY (`vid`),
  INDEX `fk_vouchers_validities_idx` (`validity` ASC),
  CONSTRAINT `fk_vouchers_validities`
  FOREIGN KEY (`validity`)
  REFERENCES `voucher`.`validities` (`validity_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
);


-- Table structure for table `mac_addresses`

DROP TABLE IF EXISTS `mac_addresses`;
SET character_set_client = utf8;
CREATE TABLE `mac_addresses` (
  `mid`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vid`         INT UNSIGNED NOT NULL,
  `mac_address` VARCHAR(12)  NOT NULL,
  PRIMARY KEY (`mid`),
  INDEX `fk_mac_addresses_vouchers_idx` (`vid` ASC),
  CONSTRAINT `fk_mac_addresses_vouchers`
  FOREIGN KEY (`vid`)
  REFERENCES `voucher`.`vouchers` (`vid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


INSERT INTO `validities` (`validity_id`, `validity`, `description`)
VALUES ('0', '0', 'from-to'), ('1', '7', '7 days'), ('2', '30', '1 month'), ('3', '180', '6 months'),
  ('4', '365', '1 year');
