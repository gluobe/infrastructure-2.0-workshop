# **LAB 4 - ELB** #

## Start of Lab 4 ##
You should have one EC2 Instance with MemeGen installed on it which is reachable and works with the remote DynamoDB.

## Visual Interpretation ##
Right now, we only have one server and it's carrying the full load of a savage Meme Economy boom. We'll need multiple servers and an Elastic Load Balancer (ELB) to share the network load over the two instances.

![](../Images/Lab4.png?raw=true)

### 1. Create a Security Group ###
We'll need a firewall configuration for the Load Balancer. It simply forwards port 80 to port 80 equally on the instances.

1. Go to `Services -> EC2`.
1. Click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_SecGroup_ELB_<your_ID>`
    * Description: `Security Group for a Load Balancer`
    * VPC: `defaultVPC`
    * Add an **inbound** rule:
        * `HTTP` on port `80`, `source anywhere`
1. Click `Create`

### 2. Create a second EC2 Instance ###
To gain the most out of the load balancer we're about to set up, we'll need another EC2 Instance to link the load balancer with so it can do its job properly. You can also do this with just one instance but the load balancer wouldn't really be load balancing anything, it would just be a glorified proxy.

At this point we should still have one instance running. We can copy the settings of an instance and create another instance from it. This won't copy the original instance's hard drive, we'll have to install MemeGen on this new instance again. 

1. Go to `Services -> EC2 -> Instances`
1. Select your own instance named `lab_EC2_instance1_<your_ID>`.
1. Press the `Actions` dropdown button above the EC2 Instances list and in the dropdown that appears press `Launch More Like This`.
1. You immediately get the same launch configurations listed as your previous virtual machine.
    * Go back to the `5. Add Tags` section and change the `Name` tag to `lab_EC2_instance2_<your_ID>`.
    * Press `Review and Launch`.
1. Press `Launch`.
1. Choose your own key pair, acknowledge and press `Launch Instances`.

### 3. Configure your second EC2 Instance ###
Next we'll install the MemeGen app on the second instance via a bash script instead of doing it all manually like in Lab 2 again.

* **Log out of your first instance to the red prompt of your management instance**.  

1. `ssh -i ~/.ssh/id_rsa ubuntu@<IP-address-second-instance>`
    * Log in to your **second** instance.
1. `sudo su -`
    * Become the root user.
1. `git clone https://github.com/gluobe/infrastructure-2.0-workshop.git ~/infra-workshop`
    * Clone the repository to your home directory.
1. `chmod 755 ~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Change permissions on the script to make it executable.
1. `~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Execute it. It's done after it prints `Local MemeGen installation complete.` (this can take some time).
1. **Change the site's config.php.**
    1. Enter `/var/www/html/config.php` using your favorite editor.
    1. Change the `$yourId` variable to your own ID.
    1. Change the `$remoteData` variable to `true`.

* We should now have two separate Instances which are both linked to DynamoDB and will be reachable via the Load Balancer in a minute:

    ![](../Images/ELBTwoInstancesTwoApps1.png?raw=true)  

    ![](../Images/ELBTwoInstancesTwoApps2.png?raw=true)    

* You'll note however that no images are synchronized between the instances... When a meme is made it just saves it locally on its own instance filesystem. We'll fix that later.

    ![](../Images/ELBMissingImagesNoSync1.png?raw=true)

    ![](../Images/ELBMissingImagesNoSync2.png?raw=true)

### 4. Create a Load Balancer ###
Let's create a load balancer to share the network load between our two separate instances.

1. Go to `Services -> EC2 -> Load Balancers`.
1. Press the `Create Load Balancer` button.
    * Choose the `Classic Load Balancer (Previous Generation)` option.
    * Name it `lab-ELB-<your_ID>`.
    * Make sure `defaultVPC` is selected as a VPC.
1. Press `Next: Assign Security Groups`.
    * Choose `lab_SecGroup_ELB_<your_ID>` as an **existing** security group.
1. Press `Next: Configure Security Settings`.
1. Press `Next: Configure Health Check`.
    * Change `Ping Path` to `/index.php`.
    * Change `Healthy threshold` to `4`.
1. Press `Next: Add EC2 Instances`.
    * Select your instances named `lab_EC2_instance1_<your_ID>` and `lab_EC2_instance2_<your_ID>`.
1. Press `Next: Add Tags`.
    * Add a tag with key `Name` and value `lab-ELB-<your_ID>`.
1. Press `Review and Create`.
1. Press `Create`.

* Click on your load balancer `lab-ELB-<your_ID>` and click the `Instances` tab to view the status of your instances being linked to the ELB. It may take some time for it to go from `OutOfService` to `InService`.

    ![](../Images/ELBTwoInstancesLinked.png?raw=true)    

### 5. Connecting through the Load Balancer ###
Now that we've linked both the instances to one load balancer we can browse to the load balancer URL and be redirected to one of the instances.

1. Copy the DNS URL provided by the Load Balancer.

    ![](../Images/ELBCopyURL.png?raw=true) 

1. Paste it into your browser titlebar.
    * The Load Balancer is pretty quick, but linking the instances and getting the DNS changes to take effect could take up to 1-3 minutes.
    
    ![](../Images/ELBLinkIntoBrowser.png?raw=true) 

### 6. Differentiate the Instances ###
To show both instances are actually being used by the Load Balancer we'll change the site color of the second instance's app to differentiate the two instances from each other. 

1. **Change the second instance's config.php.**
    1. Enter `/var/www/html/config.php` using your favorite editor.
    1. Change the `$siteColorBlue` variable to `true`.
1. Try to hard refresh the Load Balancer site (`CTRL` + `SHIFT` + `R`) a few times. About 50% of the time the site color will visibly change meaning we've reached different instances.

    ![](../Images/ELBButtonChange1.png?raw=true)
    
    ![](../Images/ELBButtonChange2.png?raw=true)


## End of Lab 4 ##
Congratulations! You've successfully created a second instance and load balanced its network load using an AWS Elastic Load Balancer.

To update your score, `exit` to your management instance and run this command `/.checkScore.sh`, then log back in to your own instance `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.

Once you have two Instances, both connected to the same Load Balancer and DynamoDB, you may continue to the next lab. ([Next lab](../Lab%205%20-%20S3))

### More info ###

* What is ELB? (https://aws.amazon.com/elasticloadbalancing/).
* The ELB specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/elb/index.html).
