# **LAB 4 - ELB** #

## Visual Interpretation ##
Right now, we only have one server and it's carrying the full load of a savage Black Friday sale which we're blogging about. We'll need multiple servers and an (elastic) Load Balancer to share the network load over the two instances.

![](../Images/Lab4.png?raw=true)

### 1. Create a Security Group ###
We'll need a firewall configuration for the Load Balancer. It simply forwards port 80 to port 80 equally on the instances.

1. Go to `Services -> EC2`.
1. Click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_SecGroup_ELB_<your_ID>`
    * Description: `Security Group for a Load Balancer`
    * VPC: `lab_ManagementVpc`
    * Add an **inbound** rule:
        * `HTTP` on port `80`, `source anywhere`
1. Click `Create`

### 2. Create a second EC2 Instance ###
To gain the most out of the load balancer we're about to set up, we'll need another EC2 Instance to link the load balancer with so it can do its job properly. You can also do this with just one instance but the load balancer wouldn't really be load balancing anything, it would just be a glorified proxy.

At this point we should still have one instance running. You can also just copy its settings and create another EC2 instance from it.

1. Go to `Services -> EC2 -> Instances`
1. Select your own instance named `lab_EC2_instance1_<your_ID>`.
1. Press the `Actions` dropdown button above the EC2 Instances list and in the dropdown that appears press `Launch More Like This`.
1. You immediately get the same launch configurations listed as your previous virtual machine.
    * Go back to the `5. Add Tags` section and change the `Name` tag to `lab_EC2_instance2_<your_ID>`.
    * Press `Review and Launch`.
1. Press `Launch`.
1. Choose your own key pair, acknowledge and press `Launch Instances`.

### 3. Configure your second EC2 Instance ###

1. Make sure to execute the `Scripts/InstallWordpress.sh` script on the new EC2 Instance.
    * If you're stuck doing this go back to Lab 3, step 2.
1. Browse to your second Instance's IP-address and configure it to work with the RDS.
    * You won't have to configure Wordpress anymore since all of that data was stored in the RDS we're now sharing with another instance.
    * If you're stuck doing this go back to Lab 3, step 5.
    
* After configuring the second Instance to link to the RDS you should get this message, meaning the RDS already has some Wordpress data stored from the first Instance we configured:

![](../Images/LinkSecondInstanceToRDSMessage.png?raw=true)

* We should now have two separate Instances which we can login to with the same credentials:

![](../Images/TwoInstancesConnectedByRDS.png?raw=true)

### 4. Create a Load Balancer ###
Now that our instances are connected to the same database, we can create our load balancer to share the network load.

1. Go to `Services -> EC2 -> Load Balancers`.
1. Press the `Create Load Balancer` button.
    * Choose the `Classic Load Balancer (Previous Generation)` option.
    * Name it `lab-ELB-<your_ID>`.
    * Make sure `lab_ManagementVpc` is selected as a VPC.
    * Select all 3 subnets under `Select Subnets`.
1. Press `Next: Assign Security Groups`.
    * Choose `lab_SecGroup_ELB_<your_ID>` as an existing security group.
1. Press `Next: Configure Security Settings`.
1. Press `Next: Configure Health Check`.
    * Change `Ping Protocol` to `TCP`.
1. Press `Next: Add EC2 Instances`.
    * Select your instances named `lab_EC2_instance1_<your_ID>` and `lab_EC2_instance2_<your_ID>`.
1. Press `Next: Add Tags`.
    * Add a tag with key `Name` and value `lab-ELB-<your_ID>`.
1. Press `Review and Create`.
1. Press `Create`.

Eventually it should look something like this:

![](../Images/CreatedELBInstanceList.png?raw=true)

### 5. Connecting to the Website ###
Now that we've linked both the instances to one load balancer we can browse to the load balancer and be directed to one of the instances.

* Click on `lab-ELB-<your_ID>` and click the `Instances` tab to view the status of your instances being linked to the ELB.
* Fill in the DNS name of your created load balancer in your web browser and press enter. It should redirect you to your Wordpress site. (The Load Balancer is pretty quick, but linking the instances and getting the DNS changes to take effect could take up to 5 minutes.)


### 6. Differentiate the Instances ###
To show both instances are actually being used by the Load Balancer we'll add a line to the second instance's index.php to differentiate the two instances from each other. 

1. On the **second** instance do `echo 'echo "<script type=\"text/javascript\">alert(\"This is the second instance.\");</script>";' >> /var/www/html/wordpress/index.php`.
1. Once the Load Balancer is fully operational, refresh the page a few times. About 50% of the time you'll get an alert saying `This is the second instance.`.

It should look something like this:

![](../Images/ELBSecondInstanceAlert.png?raw=true)

Right now, you might note that only our database is synchronized between the two machines, but as you can see the files clearly aren't. Suppose you're a Wordpress developer. Changing one of Wordpress' .php files on ONE instance will also have to be changed on the other one. 

We're looking for a way to have both the instances use the same Wordpress files. That's where AWS' S3 service comes in handy.  

### More info ###

* What is ELB? (https://aws.amazon.com/elasticloadbalancing/).
* The ELB specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/elb/index.html).
