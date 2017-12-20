#!/bin/bash
# Installs Wordpress. Make sure you've got privileges with the user you execute this as.

# Make sure it's run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root! use 'sudo su -'." 
   exit 1
fi

# Update the server
  apt-get update -y 
  # apt-get upgrade -y

# Install packages and start services
  # Make sure MySQL server does not ask for a password
  export DEBIAN_FRONTEND=noninteractive
  # Install packages
  apt-get install -y apache2 mysql-server php7.0 libapache2-mod-php7.0 php7.0-mysql wget unzip
  # Enable
  systemctl enable mysql apache2
  # Start
  systemctl start mysql apache2

# Configure Wordpress
  # Create db
  mysql -u root -e "create database wordpress;"
  # Create user
  mysql -u root -e "create user wordpress@localhost identified by 'Cloud247';"
  # Give user privileges
  mysql -u root -e "grant all privileges on wordpress.* to wordpress@localhost;"
  # Flush
  mysql -u root -e "flush privileges;"
  
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
  chmod -R  g+w /var/www/html/wordpress
  # Go back to home dir 
  cd ~

# Configure httpd and restart
  # Change a line in httpd config
  sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/wordpress/g" /etc/apache2/sites-available/000-default.conf
  # Restart httpd
  systemctl restart apache2

# Please go to http://
  echo -e "Local Wordpress installation complete."

