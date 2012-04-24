<?php

/*
NAME agree.php is part of the voucher4guests Project
SYNOPSIS display the user agreement
DESCRIPTION display the user agreement
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/

$lang_array = array('de', 'en');
if(!isset($_GET['lang'])) $language = 'en';

if(isset($_GET['lang']) && in_array($_GET['lang'], $lang_array)){ 
  $language = $_GET['lang'];
}
require_once('language/'.$language.'.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title><?php echo $lang['PageTitle']; ?></title>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
<link rel="stylesheet" href="includes/css/layout.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
</head>
<body>
<div id="wrap">
<div id="top"><div class="time"><?php echo date("d.m.Y - H:i") ?></div></div>
<div id="header">
	<img src="images/logo_<?php echo $language; ?>.jpg" alt="Logo" class="logo" /><h1><?php echo $lang['HeaderTitle']; ?></h1>
    <ul class="language">
    	<li id="language_en"><a href="agree.php?lang=en">English</a></li>
    	<li id="language_de"><a href="agree.php?lang=de">Deutsch</a></li>
    </ul>
</div>

<div id="content">
		
		<div class="outer_content">

        <?php echo $lang["AgreeContent"]; ?>

        <p>Download: <a href="pdf/<?php echo $lang['AgreePDF']; ?>" target="_blank"><?php echo $lang['AgreePDF']; ?></a></p>

      </div>
</div>
</div>

<div id="footer"><p><?php echo $lang['Footer']; ?></p></div>
</body>
</html>
