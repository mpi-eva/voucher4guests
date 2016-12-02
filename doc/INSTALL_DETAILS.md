# Installation details

This document describes the installation and configuration in detail. All steps are done by the `install.sh`. 
Simply run  `./install.sh` with root privileges.  

## Installation steps

### Install packages

Install required packages with the package manager.
```bash 
sudo apt install apache2 php7.0 php7.0-cli php7.0-intl php7.0-xml php7.0-xmlrpc libapache2-mod-php7.0 php7.0-mysql apache2-utils
```

When installing the mysql server you will be asked to set an admin password. You will need it later to setup the 
database. Use the following command to install mysql:
```bash
sudo apt install mysql-server
```

### Copying the files

Copy all folders in the install path. We recommend `/usr/local/voucher4guests` 

```
voucher4guests/
  config/
  doc/
  management_interface/ 
  scripts/ 
  user_interface/ 
```

### Create the config files

Make a copy of the template configuration files and remove the file extension `.dist`.

```
config/
  config.php.dist
  config.php
  database.config.php.dist
  database.config.php    
```

### Setup sudoers config

The captive portal system must be able to manipulate the firewall rules to en/dissable devices for passing the gateway.
Therefore the scripts adds or removes a firewall rule for each device (mac address). 
This commands need root privileges for the executing user, in this case for the webserver user `www-data`.

This can be done by editing the `/etc/sudoers` file or adding a file to the include directory. 
- Create the include directory if it does not exist:

```bash
 mkdir /etc/sudoers.d
```
- Set the include folder in the ´/etc/sudoers´ file. 

```bash
 echo "#includedir /etc/sudoers.d" >> /etc/sudoers
```

- Create the new config file with the following content.  

```bash
echo "
 # activate and/or disable mac address in running iptables ruleset

 www-data ALL=(ALL) NOPASSWD: /sbin/iptables -I GUEST 1 -t nat -m mac --mac-source ??\:??\:??\:??\:??\:?? -j ACCEPT
 www-data ALL=(ALL) NOPASSWD: /sbin/iptables -D GUEST -t nat -m mac --mac-source ??\:??\:??\:??\:??\:?? -j ACCEPT
  " > /etc/sudoers.d/voucher4guests
```

- Adjust the permissions of the file.

```bash
 chmod 0440 /etc/sudoers.d/voucher4guests
```

### Setup the cronjobs

Configure the cronjobs for important scripts.

```bash

echo "
# service maintenance and backup scripts for voucher4guests installation
0 0    * * *   root    /usr/bin/php /usr/local/voucher4guests/scripts/voucher_control.php >> /var/log/voucher.log
0 1    * * *   root    /bin/bash /usr/local/voucher4guests/scripts/db_backup.sh > /dev/null
" >> /etc/crontab
```

Restart the service afterwards with: `service cron restart`
 
### Setup the Logrotate

Add the logrotate config file to the include dir with following command:
```bash
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
```

And adjust the permissions of the file.
```bash
 chmod 0440 /etc/logrotate.d/voucher4guests
```
 
 
### Creating the database

For the creation of the Database sipmly run the supplied sql script `scripts/voucher_database.sql`.

You can use the following command to apply the script. You have to enter the mysql root password.
```bash
 mysql -u root -p < /usr/local/voucher4guests/scripts/voucher_database.sql
```
 
Futhermore a datebase user is necessary. You can create a new database user or use an existing.
```sql
GRANT ALL PRIVILEGES ON voucher4guests.* TO 'v4g_user'@'localhost' IDENTIFIED BY '[PASSWORD HERE]'; 
FLUSH PRIVILEGES; 
```

Enter the the credentials in the config file `config/database.config.php`
```php
return array(
    'db_base' => 'voucher4guests',
    'db_user' => 'v4g_user',
    'db_password' => '[PASSWORD HERE]',
    'db_host' => 'localhost',
);
```

### Setup the apache webserver

The captive portal system use two virtual hosts. One for the user interface(HTTP) and the other one for the management
interface(HTTPS). You can find the sample configuration files within the folder `scripts/vhosts`.

Some configurations have to be adapted to your own requirements.

 - `ServerName` with the Full Qualified Domain Name (FQDN) of the host
 - `ServerAdmin` with the admin mail address
 - `DocumentRoot`  with the paths for HTTP: `/usr/local/voucher4guests/user_interface` ,
    and for HTTPS: `/usr/local/voucher4guests/mannagement_interface`
 - `Directory` with the corresponding DocumentRoot directories
 
The server name must also be set in the config file `/usr/local/voucher4guests/config/config.php`.
```
'domain_name' => 'servername.example.com'
```

The system needs to redirect user, so the `ssl` and the `rewrite` apache modules have to be enabled.
```
sudo a2enmod ssl rewrite
```


Enable the virtual host configurations:
```
sudo a2ensite voucher4guests.conf
sudo a2ensite voucher4guests_ssl.conf
``` 

Restart the apache webserver after the configuration: `sudo service apache2 restart`


### Access restriction for the management interface

The management interface is secured with an .htaccess authentication. 
The credential are stored in an .htpasswd file. You can create this file
with the following command:
```
htpasswd -c /usr/local/voucher4guests/management_interface/.htpasswd [login name here]
```
Use the parameter `-c` to create the file if it not exist. Enter a login name at the end of
of the command.
 
Edit the .htacces file and enter the path to the .htpasswd file (AuthUserFile).
`/usr/local/voucher4guests/management_interface/.htpasswd`
```
AuthName "Voucher4guests - Administration"
AuthType Basic
AuthUserFile /usr/local/voucher4guests/management_interface/admin/.htpasswd
require valid-user
```
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 