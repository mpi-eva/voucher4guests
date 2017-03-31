# Installation

## requirements on your voucher gateway

### OS ###
* reference platform ubuntu 16.04 LTS (Lucid Lynx)
* -> default install with NO additional software packages

### network ###
* guestnetinferface (where the guestclients are located)
* uplinkinterface (connection to internet)
* optional: managementinterface

### software: (ubuntu packages) ###
* php7.0 php7.0-cli php7.0-intl php7.0-xml php7.0-xmlrpc libapache2-mod-php7.0 mysql-server php7.0-mysql apache2-utils

### guestnetwork ###
* with working dhcp service, dhcp relay service or static ip's
* with working domain name service (dns)
* INSTALL.md-> both can be optional installed on this voucher gateway

---

## base install steps

* untar installation package

* install following software packages with apt-get:
  apache2 php7.0 php7.0-cli php7.0-intl libapache2-mod-php7.0 mysql-server php7.0-mysql apache2-utils

* run install.sh for copying project files to /usr/local/voucher4guests (default dir)
  and setup configuration

* if you have already an voucher4guests installation follow the steps in the `doc\UPDATE_DETAILS.md`.

----

## setup firewall

* edit scripts/voucher.fw and change the networkinterface identifier (eg.: eth0 or
  eth1 for interface to guest network/or interface to internet uplink network)

* uncomment other features like NATing and/or management access over the
  optional managementinterface

* IMPORTANT: ACTIVATE ssh ACCESS over internet uplink interface or guestnet interface
  before you activate firewall. See voucher.fw for predefined entry's. PLEASE NOTE THAT
  unrestricted ssh ACCESS over the guestnet and/or internet interface is not a good idea.

* run voucher.fw (bash script) to activate the firewall

* Please configure an firewall init script to start voucher.fw each time the voucher
  system is restarted

---

voucher4guests is running now

---

## first steps

* create vouchers: go to https:/VOUCHERGATEWAY/admin and create over the web
  frontend some vouchers

* download and print this vouchers

* try to reach an outside web site with an client which is located inside the
  client/guestnetwork you should be automaticly forwarded to the voucher login
  website, insert your voucher code, accept the policy and now click on login.
  Now you should be forwarded to your original website destination

* on the admin page your can select "Database" an you get a list of all generated
  vouchers

---
## customize the system

* replace the placeholder logo with your own institute logo
  voucher/images/logo_en.jpg  |  voucher/images/logo_de.jpg    [use the same names]

* customize the written content of the web pages e.g. the footer with the institute name
  voucher/language/en.php  |  voucher/language/de.php

* customize or insert the wifi credentials on the vouchers
  voucher_ssl/admin/pdf/create_voucher.php

* create voucher with custom validities
  - add a new validity with a sql-command in the database
    INSERT INTO validities (validity, description) VALUES ('2','2 days');
    [validity -> in days, description -> printed on the voucher]
  - add a background for the new voucher
    voucher_ssl/admin/pdf/vorlagen/v[days].jpg     -- e.g. v2.jpg -> 2 days
    [width:1240px; height:877px resolution:300px/inch]

* create you own voucher design
  - download and install free gimp software
  - open "vorlagen_v4_1g.xcf" under /doc/misc
  - now you get an mulitlayered Image

  - to include for example a new logo select the right layer
    and replace the default one with your own institutes logo
  - now select the right voucher-(layer) validity and save it as jpg,
    and again for all other wished validities

* back up the database
  - use the backup script in the `/scripts` folder
  - configure the backup path and set the database password in the script
  - to run the script periodically, the following line must be in the `/etc/crontab`

  ```0 1    * * *   root    /bin/bash /usr/local/voucher4guests/scripts/db_backup.sh > /dev/null```
