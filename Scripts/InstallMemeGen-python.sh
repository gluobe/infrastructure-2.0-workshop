#!/bin/bash

if [[ $EUID -ne 0 ]]; then 
   echo "This script must be run as root! use 'sudo su -'." 
   exit 1
fi

sudo apt-get update
sudo apt-get install -y apache2 python-dev build-essential libsqlite3-dev sqlite3 libjpeg8-dev
sudo apt-get install -y libapache2-mod-wsgi

sudo apt-get install -y python-pip
sudo pip install flask

# Git clone the repository in your home directory
git clone https://github.com/gluobe/infrastructure-2.0-workshop.git ~/infra-workshop
# Clone the application out of the repo to the web folder.
cp -r ~/infra-workshop/Application/memegen-python/ /home/ubuntu/

sudo ln -sT /home/ubuntu/memegen-python /var/www/html/memegen-python

mv /home/ubuntu/memegen-python/000-default.conf /etc/apache2/sites-enabled/000-default.conf

sudo usermod -a -G ubuntu www-data

sudo apachectl restart

sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 2930ADAE8CAF5059EE73BB4B58712A2291FA4AD5
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.6.list
sudo apt-get update
sudo apt-get install -y mongodb-org
sudo service mongod start
sudo pip install Flask-PyMongo

sudo apachectl restart
sudo pip install boto3
sudo pip install wand awscli
sudo pip install Pillow
sudo pip install pysqlite

chown -R www-data:www-data /home/ubuntu/memegen-python
sudo apachectl restart
