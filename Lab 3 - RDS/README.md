# **LAB 3 - RDS** #

## Visual Interpretation ##
Next we'll create an RDS (Amazon Relational Database Service). This is an Amazon Managed Service, meaning Amazon takes more responsibility for the database being available. Previously on our EC2 instance we had pretty much no safeguards in place for making sure the database was kept running. Amazon's Service Level Agreement gives us an uptime of somewhere around 99.9%. Quite a bit better than our locally installed unscalable and volatile MySQL server.

![](../Images/Lab3.png?raw=true)

### 1. Create a new EC2 Instance ###
After successfully cleaning up our one instance we'll need another EC2 Instance to link the external database called RDS with.

* Make a new EC2 Instance but don't configure anything on it, if you need a refresh go back to Lab 1, step 3.

### 2. Configure the new EC2 Instance ###
Instead of doing the whole configuration of Wordpress manually again, we could simply use a bash-script to execute a bunch of commands for us.

1. Log in to the EC2 Instance from your management server `ssh -i key.pem ubuntu@<public IP-address>`.
1. You can git clone the repository into the EC2 Instance or just copy the Scripts/InstallWordpress.sh file's contents manually from your host to a file on the remote machine.
1. Change the permissions of the file to make it executable `chmod 755 InstallWordpress.sh`.
1. Execute it `./InstallWordpress.sh`.
1. Lots of text scrolls past. That's the script doing the exact steps you did to install Wordpress in the previous lab! Once you see `Local Wordpress installation complete.`, you can continue.

### 3. Create a Security Group ###
Next we'll create a Security Group for the RDS. It uses port 3306 to communicate with other servers.

1. Go to `Services -> EC2`.
1. Click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_SecGroup_RDS_<your_ID>`
    * Description: `Security Group for a Relational Database`
    * VPC: `lab_ManagementVpc`
    * Add some **inbound** rules
        * `MYSQL/Aurora` on port `3306`, `source anywhere`
1. Click `Create`

### 4. Create RDS Instance ###
Next we'll create the RDS Instance itself, configure it and link it to its Security group.

**Note**: It's possible this interface is different from the explanation since AWS is testing new interfaces on new user accounts in RDS.

1. Go to `Services -> RDS -> Instances -> Launch DB Instance (button)`.
    1. Choose the `MySQL` Relational Database.
    1. Check the `Only show options that are eligible for RDS Free Tier`.
1. Click `Next`.
1. Now we're filling in the database information:
    * Fill in `DB Instance Identifier*` with `lab-RDS-<your_ID>`.
    * Fill in `Master Username` with `wordpress`.
    * Fill in `Master Password` and `Confirm Password*` with `Cloud247`.
1. Press `Next Step`.
1. Some more specific information is required:
    * Choose `lab_ManagementVpc` in the `Virtual Private Cloud (VPC)` listbox.
    * Choose `Yes` for `Public accessibility`.
    * Choose an **existing** Security Group and choose `lab_SecGroup_RDS_<your_ID>` in the `Select VPC Security Groups` listbox.
    * Fill in `Database Name` with `wordpress`.
1. Press `Launch DB Instance`.
1. The creation of the RDS will take some time (about 3 to 5 minutes).

Going back to the Instances list, your instance should look something like this:

![](../Images/CreatedRDSInstanceList.png?raw=true)

### 5. Link Wordpress to RDS ###

1. Go to `Services -> RDS -> Instances` and click on your instance.
1. Wait for your RDS to have an `Endpoint` in the `Connect` tile.
1. Configure Wordpress again using the RDS Endpoint URL instead of "localhost", this way Wordpress connects to the external RDS. More info on configuring Wordpress is found in lab 2, step 5. 
    * You might have to append the port to the URL `<RDS_ENDPOINT_URL>:3306`.
    
It should look something like this:

![](../Images/DifferentDatabaseHost.png?raw=true)

### 6. Enter the RDS (Optional) ###

1. Use the following command to log in to your RDS instance once its created. It will prompt you for your password:
    * Login to the server with `mysql -h <rds endpoint url> -P 3306 -u wordpress -p` and password `Cloud247`.
    * Show databases with `show databases;`
    * Switch databases with `use wordpress;`
    * Show tables of a selected database with `show tables;`
    * Select a table's data with `select * from wp_users;`
    
### More info ###

* What is RDS? (https://aws.amazon.com/rds/).
* The RDS service level agreement (https://aws.amazon.com/rds/sla/).
* The RDS specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/rds/index.html).