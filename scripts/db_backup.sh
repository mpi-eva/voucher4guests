#!/bin/bash

datum=`/bin/date '+%d%m%y'`;
backupPath="/usr/local/voucher4guests";

# create backup file
/usr/bin/mysqldump -u db_user -ppassword --opt voucher4guests > $backupPath/mysql_backup/backup_voucher_db$datum.sql


# delete backup files older than 30 days
find $backupPath/mysql_backup -iname 'backup_voucher_db*' -type f -mtime +30 -exec rm {} \;

exit 0
