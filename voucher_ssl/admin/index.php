<?php
/*
NAME index.php is part of the voucher4guests Project
SYNOPSIS create and print voucher
DESCRIPTION create and print normal voucher of given attributes 
like quantity duration 
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see
http://www.gnu.org/licenses/gpl-2.0.html
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>Administration</title>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
<link rel="stylesheet" href="includes/css/layout.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<script src="includes/script.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/calendarDateInput.js">
/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/
</script>

</head>
<body>
<div id="wrap">
<div id="top"><div class="time"><?php echo date("d.m.Y - H:i") ?></div></div>
<div id="header">
	<!--<img src="images/logo_.jpg" alt="Logo" class="logo" />--><h1>Administration</h1>
   <!-- <ul class="language">
    	<li id="language_en"><a href="index.php?lang=en">English</a></li>
    	<li id="language_de"><a href="index.php?lang=de">Deutsch</a></li>
    </ul>-->
</div>

<div id="menue">
   <ul>
      <li><a href="index.php">Voucher erzeugen</a></li>
      <li><a href="list.php">Datenbank</a></li>
      <li><a href="../log/index.php">Logging</a></li>
   </ul>
</div>

<div id="content">
		<div class="voucher">
        	<div class="voucher_content">
        	 <form name="Formular" action="<?php print $_SERVER['PHP_SELF'];?>" method="post" onsubmit="return chkFormular()" id="Formular">
                  <div>
                    <label>Anzahl der Voucher:</label><br />
                    <input type="text" name="number" class="input_txt" value="<?php if (isset($_POST['number'])) echo htmlspecialchars($_POST['number']); ?>" />
                    <br />
                    <br />
                    <input type="radio" name="select_validity" value="0" onclick="chkRadio()" /><small>G&uuml;ltigkeit in Tagen</small><br />
                    <input type="radio" name="select_validity" value="1" onclick="chkRadio()" /><small>G&uuml;ltigkeit von/bis</small><br />

                    <div id="days" style="display:none;">
                      <br />
                      <label><small>G&uuml;ltigkeit der Voucher :</label></small><br />
                       <select name="validity" size="1" class="input_txt">
                        <?php
	                       #open mysql database connection
		                    require_once($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');
		
		                    $conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
	                       mysql_select_db ($conf['db_base'], $conn) or die("cant find database");
	                       
	                       $options= mysql_query('SELECT * FROM validities WHERE validity_id != "0"');
								  while ($option = mysql_fetch_object($options)) {
								  		print '<option value="'.$option->validity_id.'">'.$option->description.'</option>';
								  	}
   							 ?>
                        </select>
                      <br />
                    </div>

                    <div id="fromto" style="display:none;">
                      <br />
                      <small>von: </small>
                      <script type="text/javascript"> DateInput('date1', true, 'YYYY-MM-DD') </script> 
                      <small>bis: </small>
                      <script type="text/javascript"> DateInput('date2', true, 'YYYY-MM-DD') </script>
                      
                    </div>
                    <br />
                    <input type="submit" class="submit" name="login" value="Submit" />                     
                  </div>
                </form>

         </div>	
     </div>
     <div class="inner_content">
        <h2>Neue Voucher erstellen</h2>
        <p>Hier k&ouml;nnen Sie neue Voucher <strong>erzeugen</strong> und <strong>ausdrucken</strong>.</p>
        <?php
              if (isset($_POST['select_validity']) && isset($_POST['number'])) {
                  if (preg_match("/^[0-9]+$/", $_POST['number'])) {
                      if ($_POST['select_validity'] == 0) {                          
                          print "<p><a href='pdf/create_voucher.php?quantity=".$_POST['number']."&validity=".$_POST['validity']."&act_time=&exp_time=' target='_blank'>&rArr; Your new Voucher PDF file!</a></p>";
                          print "<p><b> PDF sofort speichern oder ausdrucken.</b><br /> (PDF wird beim n&auml;chsten Erstellen von Vouchern &uuml;berschrieben!)";
                          print "<br />&bull;  randlos drucken! <br />&bull;  min. 160g Papier (wei&szlig;)</p>";
                      } else {
                          $date = isset($_REQUEST["date1"]) ? $_REQUEST["date1"] : "";
                          $date1 = isset($_REQUEST["date2"]) ? $_REQUEST["date2"] : "";
                          $date = str_replace("-", "", $date);
                          $date1 = str_replace("-", "", $date1);
                          if ($date >= $date1) {
                              print "<font color=red>ERROR: Startdatum befindet sich nach dem Enddatum!</font>";
                          } else {
                              print "<p><a href='pdf/create_voucher.php?quantity=".$_POST['number']."&validity=0&act_time=".$_REQUEST["date1"]."&exp_time=".$_REQUEST["date2"]."' target='_blank'>&rArr; Your new Voucher PDF file!</a></p>";
                              print "<p><b> PDF sofort speichern oder ausdrucken.</b></b><br /> (PDF wird beim n&auml;chsten Erstellen von Vouchern &uuml;berschrieben!)";
                              print "<br />&bull;  randlos drucken! <br />&bull;  min. 160g Papier (wei&szlig;)</p>";
                          }
                      }
                                         
                  } else {
                      print "<font color=red>ERROR: Bitte nur Zahlen als Anzahl eingeben!</font>";
                  }
              } ?>
     </div>
     
</div>
</div>

<div id="footer"><p>&copy; 2011, voucher4guests Project</p></div>
</body>
</html>
