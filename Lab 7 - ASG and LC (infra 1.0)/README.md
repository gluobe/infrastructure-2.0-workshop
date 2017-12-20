# **LAB 7 - ASG and LC (infra 1.0)** #

## Visual Interpretation ##
We've been slowly extracting every service used by what used to be one EC2 Instance and made them into AWS Managed Services. Our last move before full automation is to get rid of the manual creation of EC2 Instances by creating a Launch Configuration and Autoscaling Group.

![](../Images/Lab7.png?raw=true)

### 1.1. Deleting your EC2 Instances ###
Using an Autoscaling Group we can automatically scale the amount of EC2 Instances we're running according to how big of a load the website is enduring. It'll create and configure or destroy them accordingly. Now let's destroy our old EC2 Instances.

1. Go to `Services -> EC2 -> Instances`.
1. Select your 2 instances:
    * `lab_EC2_<your_ID>-1`
    * `lab_EC2_<your_ID>-2`
1. Click on `Actions -> Instance State -> Terminate -> Yes, Terminate`.

### 1.2. Reset RDS ###
We'll have to reset the Wordpress data since the new configuration won't work with the old.

1. On your managementserver, use `mysql -u wordpress -h <RDS_ENDPOINT_URL> -P 3306 -p`.
    * Use the `Cloud247` password.
1. Drop the wordpress database `drop database wordpress;`.
1. Create the wordpress database `create database wordpress;`.
1. Exit the RDS `quit`.

### 1.3. Reset S3 ###
We'll have to reset the Wordpress files since the new configuration won't work with the old.

1. Empty your S3 Bucket `Services -> S3`.
1. Click the record but do not open `lab-<your_ID>-bucket`.
1. Above the table click `Empty Bucket`.
1. Type the bucket name.
1. Click `Confirm`.

### 2. Configuring an LC ###
A Launch configuration will contain all the EC2 Instance details and can even be used to run scripts when the Instance is up and running for the first time.

1. Go to `Services -> EC2 -> Launch Configurations -> Create launch configuration`.
1. Select `Ubuntu Server 16.04 LTS`.
1. Select `t2.micro` and press `Next: Configure Details`.
1. Fill in:
    * Name it `lab-LC-<your_ID>`.
    * Assign it the role `lab_S3Access`.
    * Now press `Advanced`:
        * Go to "Scripts/InstallAllManual.sh" and add your ID and RDS Endpoint URL with port to the parameters ([Change InstallAllManual.sh values](../Images/LCChangeValuesManually.png?raw=true)).
        * Copy and paste the whole script into the `User Data` field `As text`.
        * Set `IP Address Type` to `Assign a public IP address to every instance.`.
    * It should all look like this: ([Launch Configuration config](../Images/LCConfigurationAddScript.png?raw=true))
1. Press `Next: Add Storage`.
1. Press `Next: Configure Security Group`.
1. Select an **existing** Security group: `lab_SecGroup_EC2_<your_ID>` and press `Review`.
1. Press `Create launch configuration`.
1. Choose an **existing** key pair: `lab_key_<your_ID>`, acknowledge and press `Create launch configuration`.
1. Click `Close`.
    * You might get automatically redirected to create an autoscaling group here. You can go back to `Services -> EC2 -> Launch Configurations` to view your LC.

### 3. Configuring an ASG ###
Next, we'll create the Autoscaling Group. It can scale the amount of EC2 Instances depending CPU or network load or just manually. If an instance goes down, the ASG will turn another back up again as well.

Some errors can pop up in this or future parts, they're most probably permission issues so you can ignore them. 

1. Go to `Services -> EC2 -> Auto Scaling Groups -> Create Auto Scaling Group`.
1. Select from an **existing** LC: `lab-LC-<your_ID>` and press `Next Step`.
1. Fill in:
    * Group name: `lab-ASG-<your_ID>`.
    * Group size: `1`.
    * Network: `lab_ManagementVpc`.
    * Subnet: (select all available)
        * `lab_Subnet1`
        * `lab_Subnet2`
        * `lab_Subnet3`
1. Press `Next: Configure scaling policies`.
    * Here you can select "Use scaling policies to adjust the capacity of this group", so you can see the ASG is able to scale dynamically depending on CPU utilization or network load to name a few. We won't use any of the advanced scaling settings, instead we'll choose `Keep this group at its initial size` and press `Next: Configure Notifications`.
1. Click `Next: Configure Tags`.
    * Add Key `Name` and Value `lab-ASG-<your_ID>`.
1. Click `Review`.
1. Click `Create Auto Scaling group`.
1. Click `Close`.

It should look something like this:

![](../Images/CreatedASGInstanceList.png?raw=true)

### 4. Linking the Load Balancer to your Autoscaling Group ###
Previously, your instances were manually linked to the Load Balancer. We cannot manually link the ELB to each Instance every time one is destroyed and recreated. Instead we'll link the ELB with the Autoscaling Group and it will automatically route to the instances.

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-ASG-<your_ID>` and click the `Actions` dropdownmenu and click `Edit`.
    * In the `Details` tab, find `Load Balancers`.
    * Click the editable box and select `lab-ELB-<your_ID>`.
    * Click `Save (to the right)`.
        * You might have to refresh the page if it's stuck due to permission issues.

### 6. Configuring the application ###
We'll have to configure Wordpress again after having created a blank slate.

1. Go to `<your_ID>.gluo.cloud`.
    * **Make sure you do it through this link** and not on one of the instance's ip's. Wordpress' installation will go corrupt if you don't and then delete an instance.
1. Configure the Wordpress site again using the RDS Endpoint.
    * If you're stuck doing this look back at `lab 2, step 5` and `lap 3, step 5`.


### 5. Scaling your application (Optional) ###
Feeling like the load on your servers is getting too high? We can easily add another EC2 Instance by scaling up (or down) our Autoscaling Group. It will do this automatically as well, if you configure it right.

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-ASG-<your_ID>` and click the `Actions` dropdownmenu and click `Edit`.
    * In the `Details` tab, find `Desired`.
    * Fill in `2` in the editable box.
    * Make sure `Min` and `Max` are `1` and `2` respectively to allow the ASG to scale from 1 to 2 if it wants to under certain conditions.
    * Click `Save (to the right)`.
        * You might have to refresh the page if it's stuck due to permission issues.
1. An `i` should appear in the `Instances` column of your ASG. This means an Instance is being started or stopped. 
1. You can go to `Services -> EC2 -> Instances` to verify the creation of your new Instance.
1. You can also go to `Services -> EC2 -> Load Balancers` and look at your ELB's linked instances as they go into service (might take some time):

![](../Images/ASGAndELBInstancesGoingUp.png?raw=true)

### 6. Remove everything ###
Having everything configured properly feels good. Making a new stack for a different customer will still require you to set everything up manually again though. We have a solution for that in the next lab, but you can start by removing everything manually right now. :)

Do **not** remove your private key yet (Services -> EC2 -> Key Pairs). We could use it in the next lab.

1. Remove the RDS (Services -> RDS -> Instances -> lab-RDS-<your_ID> -> Instance Actions -> Delete -> Create final snapshot: NO -> check "I acknowledge" -> Delete)
1. Remove the Autoscaling Group (Services -> EC2 -> Auto Scaling Groups -> lab-ASG-<your_ID> -> Actions -> Delete -> Yes, Delete)
1. Remove Load Balancer (Services -> EC2 -> Load Balancers -> lab-ELB-<your_ID> -> Actions -> Delete -> Yes, Delete)
1. Remove Route53 record (Services -> Route53 -> Hosted Zones -> gluo.cloud. -> <your_ID>.gluo.cloud. -> Delete Record Set)
1. Remove your S3 Bucket (Services -> S3 -> Click the record but do not open lab-<your_ID>-bucket -> Above the table click "Delete Bucket" -> Type the bucket name -> Confirm)

Once Load Balancer and Autoscaling Groups' instances are gone:

1. Remove the Launch Configuration (Services -> EC2 -> Launch Configurations -> lab-LC-<your_ID> -> Actions -> Delete Launch Configuration -> Yes, Delete)
1. Remove 3 Security Groups (Services -> EC2 -> 
    * lab_SecGroup_ELB_<your_ID> -> Actions -> Delete Security Group -> Yes, Delete)
    * lab_SecGroup_RDS_<your_ID> -> Actions -> Delete Security Group -> Yes, Delete)
    * lab_SecGroup_EC2_<your_ID> -> Actions -> Delete Security Group -> Yes, Delete)
    
We obviously don't want to do this every time. 

### More info ###

* What is an ASG & LC? (https://aws.amazon.com/autoscaling/).
* The ASG & LC specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/autoscaling/index.html).
    