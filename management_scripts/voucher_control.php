<?php

require_once 'includes/VoucherControl.php';

// load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

$vc = new \Voucher\Scripts\VoucherControl();

# mark vouchers which where activated (by user) and now outdated in database as canceled
$vc->deactivateVoucher();

# mark vouchers which where never activated (by user) and which over "use_by_date" as canceled
$vc->deactivateUnusedVoucher();

# activate voucher when they reach their activation date
$vc->activateVoucher();

# delete vouchers which are canceled and older than 60 days
$vc->removeVoucher();


if($config['logging_activated'] == true){
    # checking log entries and delete them if there are older than 60 days
    $vc->removeLogEntries();
}


