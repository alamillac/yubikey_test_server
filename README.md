# yubikey test server

test of the Yubikey device

## Setup
### Create vagrant VM
```sh
cd vagrant
vagrant up
```

### Init python server (optional)
open ssh vagrant session
```sh
cd vagrant
vagrant ssh
```
inside ssh console
```sh
cd /var/www/html/python_server/
./app.py
```

### Open client
The client test could be open in:
http://localhost:8080/client/

## Bibliograpy
###Create api key:
* https://upgrade.yubico.com/getapikey/

###Demo:
* https://demo.yubico.com/

###Develop OTP server:
* https://developers.yubico.com/yubikey-val/
* https://developers.yubico.com/OTP/Libraries/Using_a_library.html

###Repo:
* https://github.com/Yubico/php-yubico

###Library:
* https://developers.yubico.com/OTP/Libraries/List_of_libraries.html

###Validation Protocol:
* https://developers.yubico.com/yubikey-val/Validation_Protocol_V2.0.html
