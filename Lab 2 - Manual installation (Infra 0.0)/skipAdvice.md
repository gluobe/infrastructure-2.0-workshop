### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

1. You should be logged in to your own created instance.
1. `export MYID=<your_ID>`
    * Set environment variables.
1. `git clone https://github.com/gluobe/infrastructure-2.0-workshop.git ~/infra-workshop`
    * Clone the repository to your home directory.
1. `chmod 755 ~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Change permissions on the script to make it executable.
1. `~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Execute the script.
1. Once `Local MemeGen installation complete.` is shown you've successfully installed MemeGen.
1. `sed -i "s@^\$yourId.*@\$yourId = \"$MYID\"; # (Altered by sed)@g" /var/www/html/config.php`
    * Change the config file to have **your ID**.
1. `echo "db.images.find()" | mongo memegen -u student --password=Cloud247`
    * Verify a record was added to MongoDB's memegen database and images collection.