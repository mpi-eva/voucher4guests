#!/bin/bash
#
# This file is part of voucher4guests.
#
# voucher4guests Project - An open source captive portal system
# Copyright (C) 2016. Alexander Müller, Lars Uhlemann
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#


datum=`/bin/date '+%d%m%y'`;
backupPath="/usr/local/voucher4guests";

# create backup file
/usr/bin/mysqldump -u db_user -ppassword --opt voucher4guests > $backupPath/mysql_backup/backup_voucher_db$datum.sql


# delete backup files older than 30 days
find $backupPath/mysql_backup -iname 'backup_voucher_db*' -type f -mtime +30 -exec rm {} \;

exit 0
