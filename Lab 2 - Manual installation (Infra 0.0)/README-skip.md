# **LAB 2 - Manual installation (SKIP WITH SCRIPT)** #

## Start of Lab 2 ##
You should be logged in to your own EC2 Instance.

## Visual Interpretation ##
To really show the power of infrastructure 2.0 later on, we'll be starting very simply and traditionally by creating one single instance with the following software:

* our MemeGen application
* an Apache webserver
* a MongoDB NoSQL database

You might call it a LAMP stack (Linux, Apache, MySQL, PHP), but with MongoDB instead of MySQL.

![](../Images/Lab2.png?raw=true)

## Skipping manual installation

Normally our workshop is 4 hours long. If you're reading this it probably means that the workshop is under 2.5 hours long. 

To save time we're going to be skipping the manual installation of the application to get to the juicy parts quicker.

You won't be missing out on much, the manual installation was meant to be an irritating and long process where students make a lot of mistakes, to later point out the importance of scripting and automation so manual mistakes cannot happen.

**You should be logged in to your own created instance.**

* `sudo su -`
  * Become root user

* `export MYID=<your_ID> && export MYREGION=eu-west-1`
  * Set environment variables so our next commands know what id we are and region we're in. **Replace** `<your_ID>` with your ID!

* `git clone --single-branch --branch 2020-version https://github.com/gluobe/memegen-webapp-aws.git ~/memegen-webapp`
  * Clone the memegen application repository.

* `chmod 755 ~/memegen-webapp/scripts/InstallMemeGen-php.sh`
  * Give the memegen installation script execution permissions.

* `~/memegen-webapp/scripts/InstallMemeGen-php.sh`
  * Execute the memegen installation script. This can take a while so go grab a drink if you don't have one ;)

* `sed -i "s@^\$yourId.*@\$yourId = \"$MYID\"; # (Altered by sed)@g" /var/www/html/config.php`
  * Alter a parameter in the application config file.

* `sed -i "s@^\$awsRegion.*@\$awsRegion = \"$MYREGION\"; # (Altered by sed)@g" /var/www/html/config.php`
  * Alter a parameter in the application config file.

## Testing script execution result

* Browse to the public IP of your created instance and create a meme.

* Once you've create a meme, you should be able to see the image was added to the database and as a file to our instance using the following commands:
  * `echo "db.images.find()" | mongo memegen -u student --password=Cloud247`

  * `ls -la /var/www/html/meme-generator/memes`
  
## End of Lab 2 ##
Congratulations! You've successfully installed the memegen application!

To update your score, `exit` (maybe multiple times) to your management instance and run this command `sudo checkscore`, then log back in to your own instance `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.

Once your MemeGen application works and your local MongoDB receives records, you can continue to the [next lab](../Lab%203%20-%20DynamoDB).

### More info ###

* What is MongoDB? (https://www.mongodb.com/what-is-mongodb)
* MongoDB vs MySQL (https://www.mongodb.com/compare/mongodb-mysql?jmp=docs)

