# **LAB 2 - Manual installation** #

## Start of Lab 2 ##
You should be logged in to your own EC2 Instance.

## Visual Interpretation ##
To really show the power of infrastructure 2.0 later on, we'll be starting very simply and traditionally by creating one single instance with the following software:

* our MemeGen application
* an Apache webserver
* a MongoDB NoSQL database

You might call it a LAMP stack (Linux, Apache, MySQL, PHP), but with MongoDB instead of MySQL.

![](../Images/Lab2.png?raw=true)

### 1. Install Gluo MemeGen ###
Our application is a meme generator, but this could be any application. It will create memes, store the files on the local filesystem and store the image data in the local database.

1. `sudo su -`
    * Enter the superuser's shell. Your prompt should turn from green to white.
1. `mkdir -p /var/www/html`
    * Create the folder structure to house our application.
1. `git clone --single-branch --branch 2020-version https://github.com/gluobe/memegen-webapp-aws.git ~/memegen-webapp`
    * Git clone the repository to the server in a specific directory.
1. `ls ~/memegen-webapp`
    * Show local repository contents.
1. `cp -r ~/memegen-webapp/* /var/www/html/`
    * Move our application to the Apache web directory /var/www/html/.
1. `ls /var/www/html/`
    * Show the meme generator application contents.
1. **Change the site to have your ID.**
    1. Enter `/var/www/html/config.php` using your favorite editor. `nano` is a good one for beginners.
    1. Change the `$yourId` variable to your own ID.

### 2. Install & configure MongoDB ###
Our database is called MongoDB. It stores data in a NoSQL, document oriented manner. This means that it's not a relational SQL-like database and avoids joins. Instead it is more object oriented and groups the data together, without spreading it over a number of tables.

There's advantages and disadvantages to this type of database. For the purposes of this tutorial there isn't a real benefit to using SQL or NoSQL, we just like to switch it up.

1. `apt-get update -y`
    * Update the package manager's repositories.
1. `apt-get install -y mongodb mongodb-server`
    * Install MongoDB.
1. `systemctl start mongodb`
    * Start MongoDB.
1. `mongo`
    * When you first install MongoDB, anyone can log in without credentials. Enter the MongoDB CLI without credentials. Your prompt will change.
1. `use memegen`
    * Switch to the "memegen" database.
1. `db.createUser({ user: "student", pwd: "Cloud247", roles: [ { role: "root", db: "admin" } ] })` 
    * Create a student user with root privileges to any db. It should say `Successfully added user`.
1. `exit`
    * Exit the shell.
1. `echo "security:" >> /etc/mongodb.conf && echo "  authorization: enabled" >> /etc/mongodb.conf`
    * Enable MongoDB's access control.
1. `systemctl restart mongodb`
    * Restart MongoDB so access control is enabled.
1. `mongo memegen -u student --password=Cloud247`
    * Test access control by logging in with the right credentials...
1. `exit`
1. `mongo memegen -u student --password=wrongcredentials`
    * ...and the wrong credentials.

Our database with the student user is now up and running and access control is enabled.

### 3. Install & configure Apache ###
Apache is used to host web files and show web pages on the internet.

1. `apt-get install -y apache2`
    * Install Apache.
1. `chown -R www-data:www-data /var/www/html/`
    * Change the owner of the web directory so memes can be saved by the Apache user.
1. `ls -la /var/www/html`
    * The files now have a new group and owner.

The application is now available from the web via the public IP-address but won't work yet since PHP hasn't been installed yet.

### 4. Install & configure PHP ###
PHP is a server side language that will interact with the filesystem and database to make our application work.

1. `apt-get install -y composer php7.4 php7.4-dev libapache2-mod-php7.4 php-pear pkg-config libssl-dev libssl-dev python3-pip imagemagick wget unzip`
    * Install PHP 7.4 & other application packages.
1. `pip3 install wand`
    * Install a Python picture editor package.
1. `pecl install mongodb`    
    * Install the MongoDB PHP driver.
1. `echo "extension=mongodb.so" >> /etc/php/7.4/apache2/php.ini && echo "extension=mongodb.so" >> /etc/php/7.4/cli/php.ini`
    * Enable the MongoDB extention for PHP.
1. `composer -d "/var/www/html" require aws/aws-sdk-php`
    * Download the PHP SDK for AWS into /var/www/html, so our site can interact with AWS.

### 5. (Re)Start & enable all services ###
Everything has been installed and may or may not be running. Restart and check the status of the Apache and MongoDB services with the following commands to make sure it's stable.

1. `systemctl enable mongodb apache2`
    * Make sure the services start when the system reboots.
1. `systemctl restart mongodb apache2`
    * Start the Apache and MongoDB servers if they weren't already.
1. `systemctl status mongodb apache2`
    * View the status of both services. They should both be `Running`.

    ![](../Images/ManualInstallStateRunning.png?raw=true)

### 6. Use Gluo MemeGen ###
We can now go to the server's public IP-address in your web browser. If everything works you should see the MemeGen application. Let's create a meme.

1. Select a meme from the dropdown box.
1. Fill in both fields.
1. Click `Generate!`.

    ![](../Images/ManualInstallCreatedMeme.png?raw=true)

### 7. Verify MemeGen is working (Optional) ###
If you're curious if MemeGen has actually written any data to the database or saved files to the filesystem we can check this out.

1. `ls /var/www/html/meme-generator/memes/`
    * Look at your created meme. Images are saved locally.
1. `mongo memegen -u student --password=Cloud247`
    * Enter the MongoDB Shell in the memegen database. The database will save the id (id), image name (name) and date of creation (date).
1. `show databases`
    * Show all databases.
1. `db`
    * Show current database.
1. `use memegen`
    * Enter the memegen database if you aren't already in it.
1. `show collections`
    * Show all collections from your current database.
1. `db.images.find()`
    * Get all data from the images collection. You should see your created meme in here.
1. `db.getUsers()`
    * Get the users from your current database.
1. `exit`
    * Exit the shell.

    >root@ip-172-31-42-6:/var/www/html# **mongo memegen -u student --password=Cloud247**
    >
    >MongoDB shell version v3.6.1
    >
    >connecting to: mongodb://127.0.0.1:27017/memegen
    >
    >MongoDB server version: 3.6.1
    >
    >Welcome to the MongoDB shell.
    >
    > **...**
    >
    > \> **show databases**
    >
    >admin    0.000GB
    >
    >config   0.000GB
    >
    >local    0.000GB
    >
    >memegen  0.000GB
    >
    > \> **use memegen**
    >
    >switched to db memegen
    >
    > \> **db**
    >
    >memegen
    >
    > \> **show collections**
    >
    >images
    >
    > \> **db.images.find()**
    >
    >{ "\_id" : ObjectId("5a44f3cd67716674d11d2db2"), "id" : { "N" : 25835481 }, "name" : { "S" : "badluckbrian-firstdayonthejob-cryptolocker-397" }, "date" : { "S" : "1514468301" } }
    >
    >{ "\_id" : ObjectId("5a44f72f67716674b0174182"), "id" : { "N" : 41757927 }, "name" : { "S" : "buzzlightyear-memes-memeseverywhere-951" }, "date" : { "S" : "1514469167" } }
    >
    > \> **db.getUsers()**
    >
    >[{
    >
    >	"\_id" : "memegen.student",
    >
    >	"user" : "student",
    >
    >	"db" : "memegen",
    >
    >	"roles" : [{"role" : "root", "db" : "admin"}]
    >
    >	}]
    >
    > \> **exit**

## End of Lab 2 ##
Congratulations! You've successfully manually installed an application!

To update your score, `exit` to your management instance and run this command `sudo checkscore`, then log back in to your own instance `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.

Once your MemeGen application works and your local MongoDB receives records, you can continue to the [next lab](../Lab%203%20-%20DynamoDB).

### More info ###

* What is MongoDB? (https://www.mongodb.com/what-is-mongodb)
* MongoDB vs MySQL (https://www.mongodb.com/compare/mongodb-mysql?jmp=docs)
