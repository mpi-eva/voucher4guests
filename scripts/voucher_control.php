<?php
chdir(dirname(__FILE__));
require_once 'includes/VoucherControl.php';

// load config
$config = include('../config/config.php');

$vc = new \Voucher\Scripts\VoucherControl();

# mark vouchers which where activated (by user) and now outdated in database as canceled
$vc->deactivateVoucher();

# mark vouchers which where never activated (by user) and which over "use_by_date" as canceled
$vc->deactivateUnusedVoucher();

# activate voucher when they reach their activation date
$vc->activateVoucher();

# delete vouchers which are canceled and older than 60 days
$vc->removeVoucher();

