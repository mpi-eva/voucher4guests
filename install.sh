#!/bin/bash
###############################################################################
#NAME install.sh  is part of the voucher4guests Project
#SYNOPSIS voucher4guests install script
#DESCRIPTION automatic install script of voucher4guests project
#AUTHOR Alexander Mueller, alexander_mueller at eva dot mpg dot de
#VERSION 0.4a
#COPYRIGHT AND LICENSE
#
#(c) Alexander Mueller Lars Uhlemann
#
#This software is released under GPLv2 license - see
#http://www.gnu.org/licenses/gpl-2.0.html
##############################################################################
#

LOG="installlog.txt"
# installdirectory
installPATH="/usr/local/voucher4guests"


if [ $USER != 'root' ]
then
echo "THIS SCRIPT REQUIRES ROOT PRIVILEGES"
exit 0
fi


pakete=( apache2 php5 php5-cli libapache2-mod-php5 mysql-server php5-mysql )

anz_elemente=${#pakete[*]};

for((i=0;i<$anz_elemente;i++)); do
  inst=`/usr/bin/dpkg -l ${pakete[$i]} 2>/dev/null | awk '/ii/ { print $1 }'`
  if [ "$inst" == "ii" ]; then
     unset pakete[$i];
  fi 
done


if [ ${#pakete[*]} -gt 0 ]; then
  echo -e "\nThe following packets are missing, please install them first and start install.sh again: ${pakete[@]} \n"
  exit
fi

# save the mysql password from previous installations and create backup firewall config (voucher.fw)
if [ -f $installPATH/config/database.php ];  then
	echo -n "- Found a previous installation, continue (yes/no)?: "
	read prev_ans
	case "$prev_ans" in
	Yes|yes|Y|y|Ja|ja|J|j|"")
		echo -e "\n  OK, backup firewall voucher.fw to voucher.fw.bac"
		echo -e "  please change settings in new voucher.fw setup file!\n"
	;;
	*)	echo -e "\n Installation was canceled!\n"
		exit
	;;
	esac
	
	DB_PW=`grep "\$conf\['db_password'\]" $installPATH/config/database.php | sed "s/\\$conf\['db_password'\] = '\(.*\)';/\1/"`;
 
	# backup the firewall script
	/bin/cp $installPATH/scripts/voucher.fw $installPATH/scripts/voucher.fw.bac | tee -a $LOG
fi

  echo -e  "- Copying directories\n" | tee -a $LOG

  if [ ! -d $installPATH ];  then
    mkdir $installPATH | tee -a $LOG
  fi

  /bin/cp -r voucher/ voucher_ssl/ config/ scripts/ doc/ $installPATH/ | tee -a $LOG


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e "- Setup sudoers\n" | tee -a $LOG

  sudodir=`grep "#includedir /etc/sudoers.d" /etc/sudoers`

  if [ "$sudodir" != "#includedir /etc/sudoers.d" ]; then
     if [ ! -f /etc/sudoers_backup ];  then
       /bin/cp /etc/sudoers /etc/sudoers_backup
     fi
     echo "#includedir /etc/sudoers.d
     " >> /etc/sudoers
  fi

  if [ ! -d /etc/sudoers.d ];  then
    mkdir /etc/sudoers.d 
  fi
 
  echo "
# activate and/or disable mac address in running iptables ruleset

www-data ALL=(ALL) NOPASSWD: /sbin/iptables -I GUEST 1 -t nat -m mac --mac-source ??\:??\:??\:??\:??\:?? -j ACCEPT
www-data ALL=(ALL) NOPASSWD: /sbin/iptables -D GUEST -t nat -m mac --mac-source ??\:??\:??\:??\:??\:?? -j ACCEPT 
  " > /etc/sudoers.d/voucher4guests 
  
  /bin/chmod 0440 /etc/sudoers.d/voucher4guests


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e "- Setup cronjob for maintanance\n" | tee -a $LOG
  
  if [ ! -f /etc/crontab_backup ];  then
    cp /etc/crontab /etc/crontab_backup
  fi
  
  cron=`grep "# service maintenance and backup scripts for voucher4guests installation" /etc/crontab`

  if [ $? -gt 0 ]; then
	echo "

# service maintenance and backup scripts for voucher4guests installation 
0 0    * * *   root    /usr/bin/php5 $installPATH/scripts/check_voucher.php >> /var/log/voucher.log
0 1    * * *   root    /bin/bash $installPATH/scripts/db_backup.sh > /dev/null
  	" >> /etc/crontab
  fi

  /usr/sbin/service cron restart


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e "- Setup Logrotate\n" | tee -a $LOG

echo "/var/log/voucher.log
{
        rotate 8
        weekly
        missingok
        notifempty
        create 644 root adm
        compress
        delaycompress
}" > /etc/logrotate.d/voucher4guests

  /bin/chmod 0440 /etc/logrotate.d/voucher4guests


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e "\n- Setup mysql database\n" | tee -a $LOG

  echo "setting up tables for voucher database,"
  echo "old voucher database will be overwritten!"
  echo -n "Do you want to continue (yes/no)?: "
  read mysql_ans

  case "$mysql_ans" in

  Yes|yes|Y|y|Ja|ja|J|j|"")

    DB_PW=`tr -cd -- "-._?\!+a-zA-Z0-9" < /dev/urandom | head -c 10 && echo`

    MYSQL=`which mysql`

    # create user
    echo "GRANT ALL PRIVILEGES ON voucher.* TO 'db_user'@'localhost' IDENTIFIED BY '$DB_PW'; FLUSH PRIVILEGES;" >> $installPATH/scripts/voucher.sql

    # create database 
    echo -e "\nfor creation of database, the root password of mysql is needed! "
    $MYSQL -u root -p < $installPATH/scripts/voucher.sql

  ;;
  *) echo -e "\nMySQL setup was canceled!\n"
  ;;
esac

    # fill config files for database connection
    /bin/sed -i "/\$conf\['db_user'\]/s/= '.*'/= 'db_user'/g" $installPATH/config/database.php  
    /bin/sed -i "/\$conf\['db_password'\]/s/= '.*'/= '$DB_PW'/g" $installPATH/config/database.php  
    /bin/sed -i "/\$conf\['db_host'\]/s/= '.*'/= 'localhost'/g" $installPATH/config/database.php  

    /bin/sed -i 's/--password=".*" -D/--password="'$DB_PW'" -D/g' $installPATH/scripts/voucher.fw 
    /bin/sed -i 's/--user=".*" --pass/--user="db_user" --pass/g' $installPATH/scripts/voucher.fw 

    /bin/sed -i "/mysqldump/s/-p.* --opt/-p'$DB_PW' --opt/g" $installPATH/scripts/db_backup.sh
    /bin/sed -i '/backupPath=/s|backupPath=".*"|backupPath="'$installPATH'"|g' $installPATH/scripts/db_backup.sh
    if [ ! -d $installPATH/mysql_backup ];  then
      /bin/mkdir $installPATH/mysql_backup
    fi


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e "\n- Setup apache2 webserver\n" | tee -a $LOG

  echo -n "In the next step, the webserver will be configured. Do you want to continue (yes/no)?: "
  read apa_ans

  case "$apa_ans" in

  Yes|yes|Y|y|Ja|ja|J|j|"")
    echo -e "\n"
    echo -n "Please insert the Full Qualified Domain Name (FQDN) of this host here: " | tee -a $LOG
    read fqdn
    echo -e "\n"
    echo -n "Please insert email address from webserver administrator:" | tee -a $LOG
    read mail

    #set server name
    /bin/sed -i "/ServerName/s|ServerName.*|ServerName $fqdn|g" $installPATH/scripts/vhosts/voucher
    /bin/sed -i "/ServerName/s|ServerName.*|ServerName $fqdn|g" $installPATH/scripts/vhosts/voucher_ssl

   #set admin email address
    /bin/sed -i "/ServerAdmin/s/ServerAdmin.*/ServerAdmin $mail/g" $installPATH/scripts/vhosts/voucher
    /bin/sed -i "/ServerAdmin/s/ServerAdmin.*/ServerAdmin $mail/g" $installPATH/scripts/vhosts/voucher_ssl

    #set document root
    /bin/sed -i "/DocumentRoot/s|DocumentRoot .*|DocumentRoot $installPATH\/voucher|g" $installPATH/scripts/vhosts/voucher
    /bin/sed -i "/DocumentRoot/s|DocumentRoot .*|DocumentRoot $installPATH\/voucher_ssl|g" $installPATH/scripts/vhosts/voucher_ssl

    #set directory directive
    /bin/sed -i "/Directory/s|Directory .*voucher\/|Directory $installPATH\/voucher\/|g" $installPATH/scripts/vhosts/voucher
    /bin/sed -i "/Directory/s|Directory .*voucher_ssl\/|Directory $installPATH\/voucher_ssl\/|g" $installPATH/scripts/vhosts/voucher_ssl

    #set servername for redirect, important!
    /bin/sed -i '/\$servername=/s|\$servername=".*";|\$servername="'$fqdn'";|g' $installPATH/voucher/includes/redirect.php 

    #create self-signet certificate
    /usr/sbin/make-ssl-cert generate-default-snakeoil --force-overwrite 
    
    /usr/sbin/make-ssl-cert generate-default-snakeoil --force-overwrite 
    
    #activate ssl and auth apache modules
    /usr/sbin/a2enmod ssl rewrite    

    vhost=`/usr/bin/find /etc/apache2/sites-enabled/ -type l -or -type f`
    
    if [ "$vhost" == "" ]; then
       /bin/cp $installPATH/scripts/vhosts/voucher /etc/apache2/sites-available/
       /bin/cp $installPATH/scripts/vhosts/voucher_ssl /etc/apache2/sites-available/

       /bin/ln -s /etc/apache2/sites-available/voucher /etc/apache2/sites-enabled/voucher
       /bin/ln -s /etc/apache2/sites-available/voucher_ssl /etc/apache2/sites-enabled/voucher_ssl
    else

      echo -e "\n There already exist webserver configurations files for virtual Hosts: \n" 
      echo "This files will be overwritten! : "
      /usr/bin/find /etc/apache2/sites-enabled/ -type l -or -type f
      echo -e "\n" 
      echo -n "Do you want to continue (yes/no)?: "
      read vhost_ans

      case "$vhost_ans" in

      Yes|yes|Y|y|Ja|ja|J|j|"")
        /bin/cp $installPATH/scripts/vhosts/voucher /etc/apache2/sites-available/
        /bin/cp $installPATH/scripts/vhosts/voucher_ssl /etc/apache2/sites-available/

	/bin/rm /etc/apache2/sites-enabled/*
        /bin/ln -s /etc/apache2/sites-available/voucher /etc/apache2/sites-enabled/voucher
        /bin/ln -s /etc/apache2/sites-available/voucher_ssl /etc/apache2/sites-enabled/voucher_ssl

      ;;
      *)
        echo -e "\nVhost setup was canceled!\n"
      ;;

      esac

    fi

    /usr/sbin/service apache2 restart

  ;;
  *) 
    echo -e "\nApache setup was canceled!\n"
    
    echo -n "Please insert the Full Qualified Domain Name (FQDN) of this host here: " | tee -a $LOG    
    read fqdn

    #set servername for redirect, important!
    /bin/sed -i '/\$servername=/s|\$servername=".*";|\$servername="'$fqdn'";|g' $installPATH/voucher/includes/redirect.php

  ;;

esac


#<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  echo -e  "\n- Setup .htaccess for administrator access (generate/manage vouchers and view logs)\n" | tee -a $LOG

  /bin/sed -i "/AuthUserFile/s|AuthUserFile .*|AuthUserFile $installPATH\/voucher_ssl\/.htpasswd|g" $installPATH/voucher_ssl/.htaccess

  echo -n "Please choose a username who should manage the administrators website: " 
  read name 

  if [ ! -f $installPATH/voucher_ssl/admin/.htpasswd ];  then
    /usr/bin/htpasswd -c $installPATH/voucher_ssl/.htpasswd $name
  else
    /usr/bin/htpasswd $installPATH/voucher_ssl/.htpasswd $name
  fi

echo -e "\n- voucher4guests project installation is finished!\n"
exit

