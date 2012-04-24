<?php
/*
NAME list.php is part of the voucher4guests Project
SYNOPSIS create a list 
DESCRIPTION creates a list of all available vouchers and the 
ability to delete vouchers
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
<link rel="stylesheet" href="includes/css/list.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<script language="javascript" src="includes/sorttable.js" type="text/javascript"></script>
<script language="javascript" src="includes/remove.js" type="text/javascript"></script>
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
     <div class="outer_content">
           <table class="sortable" border="1">
              <!--<caption>Voucher</caption>-->

              <thead>
                <tr>
                  <th>VID</th>
                  <th>Voucher-Code</th>
                  <th>G&uuml;ltigkeit</th>
                  <th>MAC</th>
                  <th>&#10006; inaktiv</th>
                  <th>&#10004; aktiv</th>
                  <th>Aktivierung</th>
                  <th>Ablaufzeit</th>
                  <th>Verfalls-<br>datum</th>
                  <th>Del</th>
                </tr>
              </thead>
              <?php
                #open mysql database connection
                
	             require_once($_SERVER['DOCUMENT_ROOT'].'/../config/database.php');
	
	             $conn = mysql_connect ($conf['db_host'],$conf['db_user'],$conf['db_password'])  or die ("database connection failed");
                mysql_select_db ($conf['db_base'], $conn) or die("cant find database.");
                
                $result = mysql_query("SELECT vid, voucher_code, validities.validity as val, mac, canceled, printed, activ, activation_time, expiration_time, use_by_date 
                                       FROM vouchers, validities 
                                       WHERE vouchers.validity=validities.validity_id 
                                       ORDER BY vid");
                $num_rows = mysql_num_rows($result);
                echo '<tfoot>';
                echo '<tr>';
                echo '<th>Total</th>';
                echo '<td>' . $num_rows . ' Eintr&auml;ge</td>';
                echo '<td></td> <td></td> <td></td> <td></td>
                      <td></td> <td></td> <td></td> <td></td>';
                echo '</tr>';
                echo '</tfoot>';
                $odd = 0;
                echo '<tbody>';
                               
                while ($row = mysql_fetch_object($result)) {
                    if ($row->mac == "") $row->mac = "-";
                    if ($odd == 0) {
                        echo '<tr class="even">';
                        $odd = 1;
                    } else {
                        echo '<tr class="odd">';
                        $odd = 0;
                    }
                    $row->voucher_code = wordwrap($row->voucher_code, 5, " - ", 1);
                    $row->activation_time = substr($row->activation_time, 0, 10);
			           $row->expiration_time = substr($row->expiration_time, 0, 10);
			           $row->use_by_date = substr($row->use_by_date, 0, 10);
                    
                    echo '<th id=v' . $row->vid . '>' . $row->vid . '</th>';
                    echo '<td align="center">' . $row->voucher_code . '</td>';
                    echo '<td align="center">' . $row->val . '</td>';
                    echo '<td align="center">' . $row->mac . ' </td>';
                    echo '<td align="center">' . $row->canceled . '</td>';
                    echo '<td align="center">' . $row->activ . '</td>';
                    echo '<td align="center">' . $row->activation_time . '</td>';
                    echo '<td align="center">' . $row->expiration_time . '</td>';
                    echo '<td align="center">' . $row->use_by_date . '</td>';
                    echo '<td align="center"><img id="'.$row->vid.'" src="images/delete.png" alt="delete" title="Eintrag deaktivieren" onClick="Deletethis(this, \''.$_SERVER['SERVER_NAME'].'\');" /></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
            ?>
            </table>
     </div>       

</div>
</div>

<div id="footer"><p>&copy; 2011, voucher4guests Project</p></div>
</body>
</html>
