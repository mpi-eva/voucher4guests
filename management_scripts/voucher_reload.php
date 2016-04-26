<?php
chdir(dirname(__FILE__));
require_once 'includes/VoucherControl.php';

// load config
$config = include('../config/config.php');

$vc = new \Voucher\Scripts\VoucherControl();

# reload the firewall rules for the active mac addresses
$vc->reloadVoucher();




