# **LAB 7 - ASG and LC (infra 1.0)** #

## Start of Lab 7 ##
You should be able to reach both your instances via the Route53 record through the Load Balancer.

## Visual Interpretation ##
We've been slowly extracting every service used by what used to be one EC2 Instance and made them into AWS Managed Services. Our last move before full automation is to get rid of the manual creation of EC2 Instances by creating a Launch Configuration and Autoscaling Group.

![](../Images/Lab7.png?raw=true)

### 1. Deleting your EC2 Instances ###
Let's destroy our old EC2 Instances.

1. Go to `Services -> EC2 -> Instances`.
1. Select your 2 instances:
    * `lab_instance1_<your_ID>`
    * `lab_instance2_<your_ID>`
1. Click on `Actions -> Instance State -> Terminate -> Yes, Terminate`.

### 2. Configuring an LC ###
A Launch configuration (LC) will contain all the EC2 Instance details and can even be used to run scripts when the Instance starts running for the first time (which we will do to install our MemeGen application).

1. Go to `Services -> EC2 -> Launch Configurations -> Create launch configuration`.
1. Select `Ubuntu Server 16.04 LTS`.
1. Select `t2.micro` and press `Next: Configure Details`.
1. Fill in:
    * Name it `lab-LC-<your_ID>`.
    * Assign it the role `lab_InstanceAccess`.
    * Now press `Advanced Details`:
        * In this repository, go to "Scripts/InstallMemeGen-php-LC.sh" and add your ID to the parameters
        
        ![](../Images/LCChangeIDParameter.png?raw=true)     
            
        * Copy and paste the whole script into the `User Data` field `As text`.
        * Set `IP Address Type` to `Assign a public IP address to every instance.`.
        
        ![](../Images/LCFullConfigurationPage.png?raw=true)

1. Press `Next: Add Storage`.
1. Press `Next: Configure Security Group`.
    1. Select an **existing** Security group: `lab_SecGroup_EC2_<your_ID>` and press `Review`.
1. Press `Create launch configuration`.
    1. Choose an **existing** key pair: `lab_key_<your_ID>`, acknowledge and press `Create launch configuration`.
1. Click `Close`.

### 3. Configuring an ASG ###
Using an Autoscaling Group (ASG) we can automatically scale up or down the amount of EC2 Instances we're running according to CPU usage, network load or just manually. It'll create and configure or destroy them accordingly. If an instance goes down for any reason, the ASG will turn another back on again as well.

1. Go to `Services -> EC2 -> Auto Scaling Groups -> Create Auto Scaling Group`.
1. Select from an **existing** LC: `lab-LC-<your_ID>` and press `Next Step`.
1. Fill in:
    * Group name: `lab-ASG-<your_ID>`.
    * Group size: `1`.
    * Network: `defaultVPC`.
    * Subnet: (select all available)
        * `localSubnet0`
        * `localSubnet16`
        * `localSubnet32`
1. Click `Next: Configure scaling policies`.
1. Click `Next: Configure Tags`.
    * Add Key `Name` and Value `lab-ASG-<your_ID>`.
    * Add Key `Id` and Value `<your_ID>`.
1. Click `Review`.
1. Click `Create Auto Scaling group`.
1. Click `Close`.

    ![](../Images/ASGListInfo.png?raw=true)

### 4. Linking the Load Balancer to your Autoscaling Group ###
Previously, your instances were manually linked to the Load Balancer. We cannot manually link the ELB to each Instance every time one is destroyed and recreated. Instead we'll link the ELB with the Autoscaling Group, that way the ELB will automatically route to the instances in that ASG.

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-ASG-<your_ID>` and click the `Actions` dropdownmenu and click `Edit`.
    * In the `Details` tab, find `Load Balancers`.
    * Click the editable box and select `lab-ELB-<your_ID>`.
    * Click `Save` (to the right).

        ![](../Images/ASGChangeLinkedELB.png?raw=true)

1. Go to `<your_ID>.gluo.cloud`.

### 5. Scaling your application (Optional) ###
Feeling like the load on your servers is getting too high? We can easily add another EC2 Instance by scaling up (or down) our Autoscaling Group. It will do this automatically as well, if you configure it right.

1. Go to `Services -> EC2 -> Auto Scaling Groups`.
1. Select `lab-ASG-<your_ID>` and click the `Actions` dropdownmenu and click `Edit`.
    * Change `Desired` to `2`.
    * Change `Min` to `1`.
    * Change `Max` to `2`.
    * Click `Save (to the right)`.
1. An `i` should appear in the `Instances` column of your ASG. This means an Instance is being started or stopped. 
1. You can go to `Services -> EC2 -> Instances` to verify the creation of your new Instance.
1. You can also go to `Services -> EC2 -> Load Balancers` and look at your ELB's linked instances as they go into service (might take some time):

    ![](../Images/ASGInstancesInService.png?raw=true)  

## End of Lab 7 ##
If not everything's working you may continue anyway, but normally you should have 2 autoscaling instances with the MemeGen application, which are both reachable via the Load Balancer and have DynamoDB and S3 as a backend.  

Run this command on the management instance to update your score: `sudo checkscore`.

Let's unleash the true power of infrastructure2.0 in the ([next lab](../Lab%208%20-%20Cloudformation%20(infra%202.0))).

### More info ###

* What is an ASG & LC? (https://aws.amazon.com/autoscaling/).
* The ASG & LC specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/autoscaling/index.html).
    