# Voucher4guests

#### Introduction

The aim of voucher4guests project is to develop and distribute a voucher 
solution (Captive Portal) for the internal guest networks of .

The project were developed regarding to the following aspects:

- full compliant to the requirements of guest networks within the MPG
- covers the complete organizational work flow
- reducing the organizational demand to a minimum
- multi vendor-capability (network hardware)
- completely based on open source

#### Status of project

- technical part is complete
- installation script is complete
- documentation is finished  

#### License

This software is released under GPLv3 license

## Technical approach

The project is based on a host which acts as a layer 3 gateway between two subnets.
The first subnet connect's the gateway to internet, second one contains the guest
clients. Each client that want to connect to internet must pass the gateway. On the 
gateway itself traffic coming from client net (guestnet) will be firewalled.
That means that each client which is not known or not activated by a valid voucher 
will be forwarded automatically to the registration website located on the
gateway itself. Each client that is/was activated and his voucher is still valid
can pass the gateway and connect to the internet. The central token to identify and
handle the traffic of each client is their worldwide unique mac address.

## Contact
Mail: voucher4guests@eva.mpg.de
Web: http://www.eva.mpg.de/german/service/it/voucher4guests.html

#### Credits

Contributors:
 Alexander Mueller, alexander_mueller@eva.mpg.de
 Lars Uhlemann, lars_uhlemann@eva.mpg.de
