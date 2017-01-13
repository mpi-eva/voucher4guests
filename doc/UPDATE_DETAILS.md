# Update voucher4gusts installation

Follow the instructions to update voucher4guests installations less than version 2.0. 

## Create a backup
If your system is running on as virtual machine we recommend to create a snapshot of the 
current system.

Save the files and configs from the install directory (default: `/usr/local/voucher4guests`).

Create a backup of your database, you can run this command to write a snapshot of the database in a file.
```bash
mysqldump -u db_user -p[password here] --opt voucher > backup.sql
```

### Database connection details

Save the database credentials of the previous installation. You can find them in the file `/config/database.php` 
in the install directory (default: `/usr/local/voucher4guests`).

## Update files

Unpack the Voucher4guests files and copy them in the install directory. 

Replicate your individual customisations, for example the logo, changes in 
the language files or the voucher settings.

How to do the adjustments can be found in the install manual `INSTALL.md`.

## Update database

To update the database and keep the current entries of the database 
you have to run the `update_schema.php` script. The script is located in the directory 'scripts/update/'.

Before executing, you have to enter the database login credentials within the script. Open the 
the script with an editor of your choice and enter the connection data.
```php
$config = array(
    'db_base' => 'voucher4guests',
    'db_user' => '',
    'db_password' => '',
    'db_host' => 'localhost'
);
```
Run the script with the following command: 
```bash
php update_schema.php
```

## Update scripts

To complete the update, check whether all configurations have been made 
(e.g. `config/config.php` or `scripts/voucher.fw`). To do that, 
check the installation steps in the `INSTALL.md` and `doc/INSTALL_DETAILS.md`.


