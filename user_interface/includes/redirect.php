<?php

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