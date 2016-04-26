#!/bin/bash
###############################################################################
#NAME db_backup.sh  is part of the voucher4guests Project
#SYNOPSIS voucher database daily backup
#DESCRIPTION create vocher database dumps and deletes dumps older than 30 days  
#AUTHOR Alexander Mueller, alexander_mueller at eva dot mpg dot de
#VERSION 0.4
#COPYRIGHT AND LICENSE
#
#(c) Alexander Mueller Lars Uhlemann
#
#This software is released under GPLv2 license - see
#http://www.gnu.org/licenses/gpl-2.0.html
##############################################################################

datum=`/bin/date '+%d%m%y'`;
backupPath="/usr/local/voucher4guests";

# create backup file
/usr/bin/mysqldump -u db_user -ppassword --opt voucher > $backupPath/mysql_backup/backup_voucher_db$datum.sql


# delete backup files older than 30 days
find $backupPath/mysql_backup -iname 'backup_voucher_db*' -type f -mtime +30 -exec rm {} \;

exit 0
