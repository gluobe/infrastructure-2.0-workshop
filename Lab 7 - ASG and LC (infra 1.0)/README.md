# **LAB 7 - ASG and LC (infra 1.0)** #

## Start of Lab 7 ##
You should be able to reach both your instances via the Route53 record through the Load Balancer.

## Visual Interpretation ##
We've been slowly extracting every service used by what used to be one EC2 Instance and migrated them into AWS Managed Services. Our last move before full automation is to get rid of the manual creation of EC2 Instances by creating a Launch Configuration (LC) and Autoscaling Group (ASG).

![](../Images/Lab7.png?raw=true)

### 1. Configuring an LC ###
First we'll need the MemeGen installation script from the corresponding repository on github. Execute the following commands from your management instance.

1. `git clone --single-branch --branch 2020-version https://github.com/gluobe/memegen-webapp-aws.git ~/memegen-webapp`
    * Clone the git repo.
1. `cat ~/memegen-webapp/scripts/InstallMemeGen-php-LC.sh`
    * Print out the memegen installation script for the launch configuration using cat, and copy the script to your clipboard.

A Launch configuration (LC) will contain all the EC2 Instance details and can even be used to run scripts when the Instance starts running for the first time (which we will do to install our MemeGen application).    

1. Go to `Services -> EC2 -> Launch Configurations -> Create launch configuration`.
1. Name it `lab-lc-<your_ID>`.
1. In the **AMI** box search for `ami-0aef57767f5404a3c`.
1. Under **Instance type**, select `t2.micro`.

    ![](../Images/LCFullConfigurationPage.png?raw=true)

1. Under **Additional configuration**:
    * Assign **IAM instance profile** the role `lab-management-ip`.
    * Press **Advanced details**:
        * Under **User data**, paste the script that we printed out in our terminal before as text and **make sure you add your own ID!**
        * Set **IP Address Type** to `Assign a public IP address to every instance.`.

            ![](../Images/LCChangeIDParameter.png?raw=true)     

1. Under **Security groups**, select an **existing** Security group: `lab_sg_ec2_student<your_ID>` and press `Review`.
1. Under **Key pair**, select an **existing** key pair: `lab_key_student<your_ID>`
    * Check the acknowledge checkbox.
    ![](../Images/LCSecurityGroupAndKeyPair.png?raw=true)     

1. Press `Create launch configuration`.

### 2. Configuring an ASG ###
Using an Autoscaling Group (ASG) we can automatically scale up or down the amount of EC2 Instances we're running according to CPU usage, network load or just manually. It'll create and configure or destroy them accordingly. If an instance goes down unexpectedly, the ASG will turn another back on to match the desired count.

1. Go to `Services -> EC2 -> Auto Scaling Groups -> Create Auto Scaling Group`.
1. Name: `lab-asg-<your_ID>`.
1. Under **Launch template**, click `Switch to launch configuration`.
1. Click the dropdown box and choose `lab-lc-<your_ID>`, press `Next`.
    ![](../Images/ASGNameAndLC.png?raw=true)     
1. Fill in Network settings:
    * Network: `defaultVPC`.
    * Subnet: (select all available)
        * `localSubnet0`
        * `localSubnet16`
        * `localSubnet32`
    * Press `Next`.
    ![](../Images/ASGVPCSelection.png?raw=true)     
1. For Advanced options, press `Next`.
1. For Group size and scaling policies, press `Next`.
1. For Notifications, press `Next`.
1. For Tags:
    * Add Key `Name` and Value `lab-asg-<your_ID>`.
    * Add Key `Id` and Value `<your_ID>`.
    * Press `Next`.
    ![](../Images/ASGTagging.png?raw=true)     
1. Click `Create Auto Scaling group`.
1. Select your ASG to view the details.

    ![](../Images/ASGListInfo.png?raw=true)

### 3. Linking the Load Balancer to your Autoscaling Group ###
Previously, we manually linked our instances to the Load Balancer. We cannot manually link the ELB to each Instance every time one is destroyed and recreated by the Autoscaling Group. Instead we'll link the ELB with the Autoscaling Group, that way the ELB will automatically route to the instances in that ASG.

While we could have linked the ELB to the ASG when we were creating the ASG, you can just as easily edit a resource and its parameters. Let's do it.

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-asg-<your_ID>` and click the `Edit` button.
1. In the `Load Balancing` box:
    * Check `Classic load balancers`
    * Click dropdown box and select `lab-elb-<your_ID>`.
1. Scroll down and click `Update`.

    ![](../Images/ASGChangeLinkedELB.png?raw=true)
    ![](../Images/ASGViewLinkedELB.png?raw=true)
    

### 4. Deleting your old EC2 Instances ###
Let's destroy our old EC2 Instances. **Make sure you're deleting your own instances.**

1. Go to `Services -> EC2 -> Instances`.
1. Select your first instance:
    * `lab_instance1_student<your_ID>`
1. Click on `Actions -> Instance State -> Terminate -> Yes, Terminate`.
1. Select your second instance:
    * `lab_instance2_student<your_ID>`
1. Click on `Actions -> Instance State -> Terminate -> Yes, Terminate`.

It could a minute but the load balancer should automatically recognize some instances have terminated and won't route traffic to them anymore. Instead it will route traffic to the `lab-asg-<your_ID>` instance, created by the ASG, which we linked to the ELB.
    ![](../Images/EC2ViewTerminatedInstances.png?raw=true)

1. Go to `<your_ID>.gluo.cloud`.
    * It might take a couple of minutes for the ASG to spin up a new instance and for the instance to configure itself. Once the ELB displays instances as `InService`, the site can be reached.
    * Once you reach the site, you will notice your memes have not disappeared. This is because the files were stored on S3 and the data on DynamoDB, amazing!


### 5. Scaling your application (Optional) ###
Feeling like the load on your servers is getting too high? We can easily add another EC2 Instance by scaling up our Autoscaling Group. The ASG can scale automatically as well. If you configure it right it can react to all sorts of metrics like average CPU usage, average network requests, average network bytes in...

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-asg-<your_ID>` and click `Edit`.
    * Change `Desired` to `2`.
    * Change `Max` to `2`.
1. Scroll down and click `Update`.
1. The status of your autoscaling group should change to `Updating capacity`. This means an Instance is being started or stopped.
    ![](../Images/ASGPendingNewInstance.png?raw=true)  
1. You can go to `Services -> EC2 -> Instances` to verify the creation of your new Instance.
1. You can also go to `Services -> EC2 -> Load Balancers` and look at your ELB's linked instances as they go into service (might take some time):
    ![](../Images/ELBInstancesOutOfService.png?raw=true)  
    
Once the application finishes installing the memegen application, it will become available and `InService`. 


## End of Lab 7 ##
If not everything's working you may continue anyway, but normally you should have 1 or 2 autoscaling instances with the MemeGen application, which are both reachable via the Load Balancer and have DynamoDB and S3 as a backend.  

Run this command on the management instance to update your score: `sudo checkscore`.

Let's unleash the true power of infrastructure2.0 in the ([next lab](../Lab%208%20-%20Cloudformation%20(infra%202.0))).

### More info ###

* What is an ASG & LC? (https://aws.amazon.com/autoscaling/).
* The ASG & LC specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/autoscaling/index.html).
