#!/bin/bash

set -e

# install PHP dependencies
apt-get update
apt-get install libapache2-mod-php5 php5-curl php-pear -y
cd /tmp && wget https://developers.yubico.com/php-yubico/Releases/Auth_Yubico-2.5.tgz
pear install Auth_Yubico-2.5.tgz
a2enmod rewrite
service apache2 restart

# install python dependencies
apt-get install python-pip -y
pip install yubico-client flask-restful flask flask-cors
