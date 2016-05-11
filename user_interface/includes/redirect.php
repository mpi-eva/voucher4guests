<?php

/*
NAME redirect.php is part of the voucher4guests Project
SYNOPSIS redirect voucher site
DESCRIPTION redirect to voucher site and saves site which was aimed before 
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see
http://www.gnu.org/licenses/gpl-2.0.html
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