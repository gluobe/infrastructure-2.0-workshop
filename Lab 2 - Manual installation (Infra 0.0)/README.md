# **LAB 2 - Manual installation** #

## Visual Interpretation ##
To really show the power of infrastructure 2.0 later on, we'll be starting very simply and traditionally by creating a simple apache webserver that's hosting a Wordpress site with MySQL as our local database on one instance.

![](../Images/Lab2.png?raw=true)

### 1. Install packages and start services ###

1. Enter the superuser's shell by typing `sudo su -`.
1. Install a bunch of packages to set up a LAMP stack `apt-get install -y apache2 mysql-server php7.0 libapache2-mod-php7.0 php7.0-mysql wget unzip`. 
    * If you encounter any password prompts, enter nothing and press enter.
1. Make sure the services start when the system reboots with `systemctl enable mysql apache2`.
1. Start the Apache and MySQL servers with `systemctl start mysql apache2`.
1. To view the status of both services, type `systemctl status mysql apache2`. They should both be `Running`.

It should look something like this:

![](../Images/PackagesStateRunning.png?raw=true)

### 2. Configure MySQL ###

1. Log in to your local MySQL server with `mysql -u root`.
1. Create a database with `create database wordpress;`.
1. Create a user with `create user wordpress@localhost identified by 'Cloud247';`.
1. Grant privileges to the user with `grant all privileges on wordpress.* to wordpress@localhost;`.
1. Apply the privileges with `flush privileges;`.
1. Exit MySQL with `quit`.
  
### 3. Download and install Wordpress ###

1. Change to the apache default web directory, where all wordpress web files will be stored `cd /var/www/html`.
1. Download the wordpress .zip file `wget https://wordpress.org/latest.zip`.
1. Unzip the downloaded file `unzip latest.zip`.
1. Change owner of the extracted wordpress folder and files to the apache user `chgrp -R www-data wordpress/`.
1. Change permissions of the directory `chmod 3770 wordpress/`.
    * If you're curious about the `3` in this command check out `sticky bit` and `setgid on directories`.
1. Give all files under the wordpress folder the proper group write permissions `chmod -R g+w /var/www/html/wordpress`.
1. View the contents of the wordpress folder `ls -la wordpress/`.

It should look something like this:

```bash
root@ip-192-168-0-84:/var/www/html# ls -la wordpress/
total 196
drwxrws--T  5 root www-data  4096 Nov 29 19:06 .
drwxr-xr-x  3 root root      4096 Dec 13 14:32 ..
-rw-rw-r--  1 root www-data   418 Sep 25  2013 index.php
-rw-rw-r--  1 root www-data 19935 Jan  2  2017 license.txt
-rw-rw-r--  1 root www-data  7413 Dec 12  2016 readme.html
-rw-rw-r--  1 root www-data  5434 Sep 23 12:21 wp-activate.php
drwxrwxr-x  9 root www-data  4096 Nov 29 19:06 wp-admin
-rw-rw-r--  1 root www-data   364 Dec 19  2015 wp-blog-header.php
-rw-rw-r--  1 root www-data  1627 Aug 29  2016 wp-comments-post.php
-rw-rw-r--  1 root www-data  2853 Dec 16  2015 wp-config-sample.php
drwxrwxr-x  4 root www-data  4096 Nov 29 19:06 wp-content
-rw-rw-r--  1 root www-data  3669 Aug 20 04:37 wp-cron.php
drwxrwxr-x 18 root www-data 12288 Nov 29 19:06 wp-includes
-rw-rw-r--  1 root www-data  2422 Nov 21  2016 wp-links-opml.php
-rw-rw-r--  1 root www-data  3306 Aug 22 11:52 wp-load.php
-rw-rw-r--  1 root www-data 36583 Oct 13 02:10 wp-login.php
-rw-rw-r--  1 root www-data  8048 Jan 11  2017 wp-mail.php
-rw-rw-r--  1 root www-data 16246 Oct  4 00:20 wp-settings.php
-rw-rw-r--  1 root www-data 30071 Oct 18 17:36 wp-signup.php
-rw-rw-r--  1 root www-data  4620 Oct 23 22:12 wp-trackback.php
-rw-rw-r--  1 root www-data  3065 Aug 31  2016 xmlrpc.php
```

### 4. Configure Apache ###
The default directory for apache to find its web pages is /var/www/html, since we created the wordpress folder in /var/www/html we want to redirect all traffic to that folder.

1. You can change the DocumentRoot of the default site config manually with editors like `vi` or `nano` or use the following command to replace lines in files from the commandline `sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/wordpress/g" /etc/apache2/sites-available/000-default.conf`.
    * This will simply replace "DocumentRoot /var/www/html" with "DocumentRoot /var/www/html/wordpress".
1. Restart the web server to make the changes take effect `systemctl restart apache2`.

### 5. Configure Wordpress ###
We can now go to the server's IP-address in your web browser. If everything works you should see the Wordpress installation page. Enter the following information:

* Page 1: Database configuration
    * Db name:      wordpress
    * Username:     wordpress
    * Password:     Cloud247
    * Db host:      localhost
    * Table_prefix: wp_
    * Press `Next`.
* Page 2: Wordpress configuration
    * Site title:   AWS
    * Username:     wordpress
    * Password:     Cloud247 (and confirm the weak password)
    * E-mail:       bla@bla.bla
    * Press `Install Wordpress`.
    
The installation is complete!

![](../Images/ManualWordpressInstallationComplete.png?raw=true)

* Log in to the Wordpress site you've just configured using:
    * Username:     wordpress
    * Password:     Cloud247
    * Press `Login`.
    * Click your site title `AWS` on the top left to visit your home page.
    
Your Wordpress site is now functional.

### 6. Look at the database's table data (Optional) ###
If you're curious if Wordpress has actually written any data to the database we can check this out.

1. Use the command `mysql -u root` to log in.
1. Enter the database we created with `use wordpress;`.
1. Show all the databases' tables with `show tables;`.
1. We can see the users' table's data by entering `select * from wp_users;`.
1. Logout the MySQL shell with `quit`.

It should look something like this:

![](../Images/ManualWordpressInstallationShowDatabaseInformation.png?raw=true)
    
### 7. Clean up what you've created ###
Now that we've shown everything can be installed and configured on one machine, we'll start over using more of AWS' services to create a more managed, safer and more scriptable version of our one-server setup. 

We will have to remove the created instance now and we'll create a fresh Instance in the next lab.

1. To remove an EC2 Instance simply select the instance you've created in `Services -> EC2 -> Instances`.
1. Click the `Actions` dropdown button, hover over `Instance State` and press `Terminate`.
    * Please make sure you've selected your own Instance instead of blindly continuing.
1. You can leave the Security Group you've created alone, we'll need it in the next labs.

