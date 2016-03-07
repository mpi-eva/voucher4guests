<?php

/*
NAME func.php is part of the voucher4guests Project
SYNOPSIS generates randomly voucher codes
DESCRIPTION generates randomly voucher codes and define
a function to generate vouchers and insert them in to db
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see
http://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * generates random string.
 *
 * @param length -> length of string
 * @return String
 */
function GenerateVouchercode($length){
     $randstr = '';
     $key = 0;
     $lastKey = -1;
     $chars = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
      
      shuffle($chars);
      for ($i = 1; $i <= $length;) {
          $key = array_rand($chars, 1);
 
          if ($key == $lastKey) {       
            continue;
          }
 	 /*
          if (0 == ($key % 2)) {          -> upper and lowercase characters
            $randstr .= $chars[$key];
          } else {
            $randstr .= strtoupper($chars[$key]);
          }*/
 			 $randstr .= $chars[$key];
 			 
          $lastKey = $key;
          $i++;
      }
       return $randstr;
}

/**
 * insert new vouchers to database.
 *
 * @param quantity validity act_time* exp_time*
 * (*optional)  
 */
function AddVoucher($quantity, $validity, $act_time='0000-00-00 00:00:00', $exp_time='0000-00-00 00:00:00' ) {

	for ($i=1; $i<=$quantity; $i++) {

		$voucherstr = GenerateVouchercode(20);
	
		if ($validity == "0") {
			if ($act_time <= date('Y-m-d')) {
			   $insert = mysql_query("Insert Into vouchers(voucher_code, validity, printed, activ, activation_time, expiration_time, use_by_date) 
			   Values('".$voucherstr."', '0', '0', '1', '".$act_time." 00:00:00', '".$exp_time." 23:59:59', '".$exp_time." 23:59:59')") or die(mysql_error());
                        }else{
			   $insert = mysql_query("Insert Into vouchers(voucher_code, validity, printed, activ, activation_time, expiration_time, use_by_date) 
			   Values('".$voucherstr."', '0', '0', '0', '".$act_time." 00:00:00', '".$exp_time." 23:59:59', '".$exp_time." 23:59:59')") or die(mysql_error());
                        }
		}else{
			$insert = mysql_query("Insert Into vouchers(voucher_code, validity, printed, activ, use_by_date) 
			Values('".$voucherstr."', '".$validity."', '0', '1', DATE_ADD(NOW(),INTERVAL 365 DAY))") or die(mysql_error());
		}
	}
}

?> 
