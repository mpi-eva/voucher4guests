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

