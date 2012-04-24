<?php

/*
NAME validity.php is part of the voucher4guests Project
SYNOPSIS creates validity check site 
DESCRIPTION creates validity check site with validity check subroutine
validity_function.php and logout_function.php subroutine
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once('includes/redirect.php');

if(!isset($_GET['mobil']) || $_GET['mobil'] != "off") {
	$mobil = 'on';
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
    	<li id="language_en"><a href="validity.php?lang=en<?php echo '&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl; ?>">English</a></li>
    	<li id="language_de"><a href="validity.php?lang=de<?php echo '&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl; ?>">Deutsch</a></li>
    </ul>
</div>

<div id="content">
		
		<div class="outer_content">

       <?php 
          echo $lang['ValidityContent'];              
          
          require_once('includes/validity_function.php');
          
    		 list($set, $voucher, $date, $mac) = validity();
    		           
          if($set == "true") {
          	printf($lang['ShowValidity'], $date, $voucher, $mac);    	
          }else {				
				echo $lang['NoValidity'];
			 }
		 ?> 

       <p>&nbsp;</p>	

       <?php echo $lang['LogoutContent']; ?>
       <p>
       <?php
         if ( isset($_POST["logout"])) {
            $ilogout=$_POST["logout"];
         } else {
            $ilogout="";
         }
       
	    	if($ilogout == $lang["Logout"]) {
			   require_once('includes/logout_function.php');
			 
			   $logout = logout();
  			     
			   if($logout){
			     echo $lang['LogoutWorks'];	
			   }else{
			     echo $lang['NoLogout'];	
			   }
					
		  }else{	
		 ?>     
           <form name="Formular" action="validity.php<?php echo '?lang='.$language.'&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl; ?>" method="post" onsubmit="return chkFormular()">    
		        <input type="submit" class="submit" value='<?php echo $lang["Logout"]; ?>' name="logout" style="float:left; margin-left:10px;" />
		     </form> 
		 <?php } ?> 
		 </p>
		 <p>&nbsp;</p> 
		 <p>&nbsp;</p> 	      
   
        &bull;&nbsp;&nbsp;<a href="index.php<?php echo '?lang='.$language.'&add='.$add.'&mobil='.$mobil.'&ssl='.$ssl ?>"><?php echo $lang['GoToStart']; ?></a>
      
     </div>
     
</div>
</div>

<div id="footer"><p><?php echo $lang['Footer']; ?></p></div>
</body>
</html>
