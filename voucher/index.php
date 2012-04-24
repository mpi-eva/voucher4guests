<?php 

/*
NAME index.php is part of the voucher4guests Project
SYNOPSIS root user php of project 
DESCRIPTION root user php of project
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/


require_once('includes/redirect.php');

$match = strpos($_SERVER["REQUEST_URI"],$_SERVER["PHP_SELF"]);

if($match !== 0) {
        header("location:http://".$servername."/index.php");
        exit;
}

if(!isset($_GET['mobil']) || $_GET['mobil'] != "off") {
	$mobil = 'on';
	
	require_once('includes/mobile_detect.php');
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		header("Location: http://" .$_SERVER['HTTP_HOST']. "/mobil/index.php");	
	}
} else {
	$mobil = 'off';	
}

if(!isset($_GET['add'])) {
	$add = '';
} else {
	$add = $_GET['add'];	
}

if(!isset($_GET['ssl']) || $_GET['ssl'] != '1') {
	$ssl = "0";
} else {
	$ssl = "1";
}

$err_array = array('0', '1', '2', '3', '4', '5', '10', '11');
if(!isset($_GET['err'])) $err = '0';

if(isset($_GET['err']) && in_array($_GET['err'], $err_array)){ 
  $err = $_GET['err'];
}

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
<script src="includes/script.js" type="text/javascript"></script>
</head>
<body>
<div id="wrap">
<div id="top"><div class="time"><?php echo date("d.m.Y - H:i") ?></div></div>
<div id="header">
<img src="images/logo_<?php echo $language; ?>.jpg" alt="Logo" class="logo" /><h1><?php echo $lang['HeaderTitle']; ?></h1>
    <ul class="language">
       <li id="language_en"><a href="index.php?lang=en<?php echo '&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl.'&err='.$err; ?>">English</a></li>
       <li id="language_de"><a href="index.php?lang=de<?php echo '&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl.'&err='.$err; ?>">Deutsch</a></li>
    </ul>
</div>

<div id="content">
   <div class="voucher">
      <div class="voucher_content">
         <?php
         if ($err == 1) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['InvalidVoucher']."</span><br /><br />";
         }
         if ($err == 2) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['ActivatedVoucher']."</span><br /><br />";
         }
         if ($err == 3) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['ExpiredVoucher']."</span><br /><br />";
         }
         if ($err == 4) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['AcceptAgreement']."</span><br /><br />";
         }
         if ($err == 5) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['MacError']."</span><br /><br />";
         }
         if ($err == 10) {
            echo "<img id='errpic' src='images/icons/error.png' alt='ERROR' />&nbsp;
                  <span class='error_msg'>".$lang['NotActivatedVoucher']."</span><br /><br />";
         }
         if ($err == 11) {
            echo "<span class='error_msg' style='color: green;'>".$lang['ActivationCompleted']."</span>
                  &nbsp;<img id='errpic' src='images/icons/tick.png' alt='ready' /><br /><br />";
         }
         ?>

         <form name="Formular" id="form_voucher" action="login.php<?php echo '?lang='.$language.'&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl.'&site=normal'; ?>" method="post" onsubmit="return chkFormular()">
            <span class="help"><a href="help_<?php echo $language; ?>.html" target="_blank" onclick="return popup(this.href);"><?php echo $lang['Help']; ?></a></span>
            <label><?php echo $lang['LableVoucherCode']; ?></label><br/>
               <input tabindex="1" type="text" class="input_txt" name="IX1"  maxlength="5" value="" onchange="javascript:checkName(this);" onkeyup="AutomatischesWeiterspringen(this, 'IX2');" /> - 
               <input tabindex="2" type="text" class="input_txt" name="IX2"  maxlength="5" value="" onchange="javascript:checkName(this);" onkeyup="AutomatischesWeiterspringen(this, 'IX3');" /> - 
               <input tabindex="3" type="text" class="input_txt" name="IX3"  maxlength="5" value="" onchange="javascript:checkName(this);" onkeyup="AutomatischesWeiterspringen(this, 'IX4');" /> - 
               <input tabindex="4" type="text" class="input_txt" name="IX4"  maxlength="5" value="" onchange="javascript:checkName(this);" onkeyup="AutomatischesWeiterspringen(this, 'policy');" /><br/><br/>
               <input type="checkbox" value="accept" name="policy"/> <small><?php echo $lang['AcceptPolicy']; ?></small><br/><br/>
                    <input class="submit" type="submit" name="submit" value='<?php echo $lang["Submit"]; ?>' />
         </form>

      </div>
   </div>
   <div class="inner_content">
      <?php echo $lang["IndexContent"]; ?>
      <ul><li><a href="validity.php<?php echo '?lang='.$language.'&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl ?>"><?php echo $lang['LinkValidity']; ?></a></li></ul>
      <br/>
      <?php echo $lang["IndexAddContent"]; ?>
   </div>
</div>
</div>

<div id="footer"><p><?php echo $lang['Footer']; ?></p></div>
</body>
</html>
