#!/bin/bash
# Install of packages, upload of Wordpress to bucket, sync with bucket to local fs and Configuration of Apache. 
# Make sure you've got privileges with the user you execute this as.

# Param values
BUCKET="lab-<your_ID>-bucket"
HOST="<your_RDS_Endpoint_URL>"
USER="wordpress"
PASS="Cloud247"

# Update the server
  apt-get update -y 
  # apt-get upgrade -y

##################### AWSCLI

  # Install Python
  apt install python wget -y
  # Download Pip
  wget https://bootstrap.pypa.io/get-pip.py
  # Install Pip
  python get-pip.py
  # Remove Pip file
  rm get-pip.py
  # Install AWSCLI with pip
  pip install awscli

  # Completed.
  echo -e "AWSCLI sucessfully installed."
  
  
##################### S3FS
  
  # Install packages
    apt-get install -y automake autotools-dev fuse g++ git libcurl4-gnutls-dev libfuse-dev libssl-dev libxml2-dev make pkg-config

  # Clone the s3fs git repo
    cd ~  
    # More info on the S3FS project here: https://github.com/s3fs-fuse/s3fs-fuse/wiki/Fuse-Over-Amazon
    git clone https://github.com/s3fs-fuse/s3fs-fuse.git

  # Install s3fs
    cd s3fs-fuse/
    ./autogen.sh && ./configure && make && make install

  # Go back to home dir
    cd ~

##################### Wordpress

# Install packages and start services
  # Make sure MySQL server does not ask for a password
  export DEBIAN_FRONTEND=noninteractive
  export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin
  # dpkg fix
  dpkg --configure -a
  # Install packages
  apt-get install -y apache2 mysql-server php7.0 libapache2-mod-php7.0 php7.0-mysql wget unzip
  # Enable
  systemctl enable mysql apache2
  # Start
  systemctl start mysql apache2
  
# Make sure the wordpress database exists
  mysql -u $USER -h $HOST -P 3306 -e "create database wordpress;" --password=$PASS || true

# Download and install wordpress
  # Change to httpd folder
  cd /var/www/html
  # Download wordpress
  wget https://wordpress.org/latest.zip
  # Unzip file
  unzip latest.zip
  # Change owner to httpd user
  chgrp www-data wordpress/
  # Give privileges and make sure it's undeletable by non-users (I think)
  chmod 3770 wordpress/
  # Set all files under wordpress/ to have group execution rights
  chmod -R g+w wordpress/
  
# Upload to S3
  aws s3 sync wordpress/ s3://$BUCKET --region eu-west-1
# Remove local copy
  rm -rf wordpress/*
  
# Synchronize with S3 (give permissions to uid 33, www-data)
  s3fs -o iam_role=auto -ouid=33,gid=33,allow_other $BUCKET wordpress/
  
# Go back to home dir 
  cd ~

# Configure httpd and restart
  # Change a line in httpd config
  sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/wordpress/g" /etc/apache2/sites-available/000-default.conf
  # Restart httpd
  systemctl restart apache2

# Complete!
  echo -e "Install of packages, upload of Wordpress to bucket, sync with bucket to local fs and Configuration of Apache complete."