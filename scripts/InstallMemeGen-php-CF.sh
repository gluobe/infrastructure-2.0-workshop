#!/bin/bash
# Installs MemeGen, modified to be used for cloudformation. 

YOURID=$1
REGION=$2

# Set a settings for non interactive mode
  export DEBIAN_FRONTEND=noninteractive
  export PATH=$PATH:/usr/local/sbin/
  export PATH=$PATH:/usr/sbin/
  export PATH=$PATH:/sbin

# Install packages (apache, mongo, php, python and other useful packages)
  # Mongodb repo commands + apt refresh
  apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 2930ADAE8CAF5059EE73BB4B58712A2291FA4AD5 && echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse" >> /etc/apt/sources.list.d/mongodb-org-3.6.list;
  apt-get update -y;
  # Install all
  apt-get install -y apache2 mongodb-org mongodb-org-server php7.0 php7.0-dev libapache2-mod-php7.0 php-pear pkg-config libssl-dev libsslcommon2-dev python-minimal python-pip imagemagick composer wget unzip;
  # Mongodb config
  pecl install mongodb
  echo "extension=mongodb.so" >> /etc/php/7.0/apache2/php.ini && echo "extension=mongodb.so" >> /etc/php/7.0/cli/php.ini

# Pip install meme creation packages and awscli for syncing s3 to local fs
  pip install --upgrade pip
  pip install wand awscli
  
# Enable and start services  
  # Enable
  systemctl enable mongod apache2
  # Start
  systemctl start mongod apache2
  # Wait for mongod start
  until nc -z localhost 27017
  do
      echo 'Sleep till MongoDB is running.' | systemd-cat
      sleep 2
  done

# Configure Mongodb
  # Create mongo student root user for memegen db
  echo '
    use memegen
    db.createUser(
       {
         user: "student",
         pwd: "Cloud247",
         roles: [ { role: "root", db: "admin" } ]
       }
    )
  ' | mongo
  # Enable user credentials security
  echo "security:" >> /etc/mongod.conf && echo "  authorization: enabled" >> /etc/mongod.conf
  # Restart the mongodb service
  systemctl restart mongod
    
# Download and install MemeGen
  # Git clone the repository in your home directory
  git clone https://github.com/gluobe/memegen-webapp-aws.git ~/memegen-webapp
  # Clone the application out of the repo to the web folder.
  cp -r ~/memegen-webapp/* /var/www/html/
  # Set permissions for apache
  chown -R www-data:www-data /var/www/html/meme-generator/

# Install aws sdk for DynamoDB
  until [ -f /var/www/html/vendor/autoload.php ]
  do
      echo 'Sleep till Composer has been run.' | systemd-cat
      export HOME=/root
      export COMPOSER_HOME=/var/www/html
      composer -d="/var/www/html" require aws/aws-sdk-php
      sleep 2
  done
  
# Configure httpd and restart
  # Remove index.html
  rm -f /var/www/html/index.html
  # Restart httpd
  systemctl restart apache2
  
# Edit site's config.php file
  # Put remote on.
  sed -i 's@^$remoteData.*@$remoteData = true; # DynamoDB (Altered by sed)@g' /var/www/html/config.php
  sed -i 's@^$remoteFiles.*@$remoteFiles = true; # S3 (Altered by sed)@g' /var/www/html/config.php
  
  # Alter user id
  sed -i "s@^\$yourId.*@\$yourId = \"$YOURID\"; # (Altered by sed)@g" /var/www/html/config.php
  sed -i "s@^\$awsRegion.*@\$awsRegion = \"$REGION\"; # (Altered by sed)@g" /var/www/html/config.php
  sed -i "s@^\$dynamoDBTable.*@\$dynamoDBTable = \"cloudformation-images-\$yourId\"; # (Altered by sed)@g" /var/www/html/config.php
  sed -i "s@^\$s3Bucket.*@\$s3Bucket = \"cloudformation-images-bucket-\$yourId\"; # (Altered by sed)@g" /var/www/html/config.php

# Please go to http://
  echo -e "Automatic MemeGen installation complete."
  echo 'Automatic MemeGen installation complete.' | systemd-cat