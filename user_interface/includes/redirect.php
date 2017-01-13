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

$config = require($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

$serverName = $config['domain_name']; #FQDN

if ($_SERVER['SERVER_NAME'] != $serverName) {
    header("location:http://" . $serverName . "/index.php?origin_url=" . urlencode($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']));
    exit;
}

$match = strpos($_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"]);

if($match !== 0) {
    header("location:http://".$serverName."/index.php");
    exit;
}