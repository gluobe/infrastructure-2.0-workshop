### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

If you played around with lab 2 already please execute the following command first:
    * `rm -rf ~/* && rm -rf /var/www/html/*`

1. You should be logged in to your own created instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `git clone https://github.com/gluobe/memegen-webapp.git ~/memegen-webapp`
    * Clone the repository to your home directory.
1. `chmod 755 ~/memegen-webapp/scripts/InstallMemeGen-php.sh`
    * Change permissions on the script to make it executable.
1. `~/memegen-webapp/scripts/InstallMemeGen-php.sh`
    * Execute the script.
1. Once `Local MemeGen installation complete.` is shown you've successfully installed MemeGen.
1. `sed -i "s@^\$yourId.*@\$yourId = \"$MYID\"; # (Altered by sed)@g" /var/www/html/config.php`
    * Change the **id** variable in config.php.
1. `sed -i "s@^\$awsRegion.*@\$awsRegion = \"$MYREGION\"; # (Altered by sed)@g" /var/www/html/config.php`
    * Change the **region** variable in config.php.
1. Create a meme.
1. `echo "db.images.find()" | mongo memegen -u student --password=Cloud247`
    * Verify a record was added to MongoDB's memegen database and images collection.
1. `ls -la /var/www/html/meme-generator/memes`
    * Verify the meme is saved locally.