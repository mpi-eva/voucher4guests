#!/bin/bash
###############################################################################
#NAME install_logging.sh  is part of the voucher4guests Project
#SYNOPSIS install logging script 
#DESCRIPTION automatic install script of voucher4guests project
#AUTHOR Lars Uhlemann, lars_uhlemann at eva dot mpg dot de
#AUTHOR Alexander Mueller, alexander_mueller at eva dot mpg dot de
#VERSION 0.4
#COPYRIGHT AND LICENSE 
#
#(c) Alexander Mueller Lars Uhlemann
#
#This software is released under GPLv2 license - see 
#http://www.gnu.org/licenses/gpl-2.0.html
##############################################################################

LOGANALYZER_FILE="loganalyzer-3.*.tar.gz"
LOG="install_logging_log.txt"
# Installationsverzeichnis
installPATH="/usr/local/voucher4guests"



if [ $USER != 'root' ]
then
echo "THIS SCRIPT REQUIRES ROOT PRIVILEGES"
exit 0
fi

if [  ! -d $installPATH/user_interface ] && [ ! -d $installPATH/management_interface ]; then
	echo -e "Please run install.sh before install logging\n"
	exit 0
fi

pakete=( rsyslog rsyslog-mysql )

anz_elemente=${#pakete[*]};

for((i=0;i<$anz_elemente;i++)); do
  inst=`dpkg -l ${pakete[$i]} 2>/dev/null | awk '/ii/ { print $1 }'`
  if [ "$inst" == "ii" ]; then
     unset pakete[$i];
  fi
done

if [ ${#pakete[*]} -gt 0 ]; then
  echo -e "\n The following packets are missing, please install them first and start install_logging.sh again: ${pakete[@]} \n"
  exit
fi

echo -e "\n- Install LogAnalyzer\n" | tee -a $LOG	

if [ -f $installPATH/management_interface/log/login.php ]; then
	echo -n "Found a Loganalyzer installation, continue (yes/no):? "
	read log_ans
	case "$log_ans" in
	Yes|yes|Y|y|Ja|ja|J|j|"")

	;;
	*)	echo -e "\nLogging installation was canceled!\n"
		exit
	;;
	esac
fi


if [ -e $LOGANALYZER_FILE ]; then
	LOGANALYZER_FILE=`find . -type f -name "$LOGANALYZER_FILE" -exec basename {} \;`
	/bin/tar xfz $LOGANALYZER_FILE | tee -a $LOG
	echo  -e "found LogAnalyzer Install Package unpacking and installing now: $LOGANALYZER_FILE \n"
	LOGANALYZER_FILE=`echo "$LOGANALYZER_FILE" | sed -e s/.tar.gz//`
	/bin/cp -r $LOGANALYZER_FILE/src/* $installPATH/management_interface/log/ | tee -a $LOG
else
	echo -e "\nPlease download newest stable release of Adiscon LogAnalyzer\nfrom here http://loganalyzer.adiscon.com/downloads"
	echo -e "and save it here, where this installscript is located"
	echo -e "Bye.."
	exit
fi


	echo -e "\n- Configure LogAnalyzer\n" | tee -a $LOG

	echo -e "configure rsyslog mysql destination to log iptables logging messages to database and"
	echo -e "setup LogAnalyzer to connect to Syslog Database\n"
	#better create a backup before 
	/bin/cp /etc/rsyslog.d/mysql.conf /etc/rsyslog.d/mysql.conf.bac | tee -a $LOG
	/bin/sed -i "s/*.* :ommysql:/\#changed by install script of voucher4guest project\nkern.warning :ommysql:/" /etc/rsyslog.d/mysql.conf | tee -a $LOG
	#extract database password 
	LOGDBPASSWORD=`/usr/bin/awk -F, '/kern.warning/ {print $NF}' /etc/rsyslog.d/mysql.conf`
	#set database password to LogAnalyzer config.php
	/bin/sed -i "/\['DBPassword'\]/s/\['DBPassword'\] = '.*'/\['DBPassword'\] = '$LOGDBPASSWORD'/" $installPATH/management_interface/log/config.php | tee -a $LOG 
	#enable logging for voucher_control.php
	/bin/sed -i "/'logging_activated'/s/=> false/=> true/g" $installPATH/config/config.php | tee -a $LOG

	# copy log_database.config.dist file for database connection
	/bin/cp $installPATH/config/log_database.config.php.dist $installPATH/config/log_database.config.php | tee -a $LOG
	#set logging database parameter file (for maintenance script check_voucher.php)
	/bin/sed -i "/$conf\['log_db_password'\]/s/= '.*'/= '$LOGDBPASSWORD'/g" $installPATH/config/log_database.config.php | tee -a $LOG
	#restart rsyslog to start logging
	/usr/bin/service rsyslog restart

	echo -e "Logging is installed!\n"
	echo -e "You can delete the install directory $LOGANALYZER_FILE here, we don't need it anymore\n"

