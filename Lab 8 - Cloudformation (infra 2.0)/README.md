# **LAB 8 - CloudFormation (infra 2.0)** #

## Visual Interpretation ##
For our last big leap, we're even foregoing using the AWS Console. Using code to start up AWS services is probably the most efficient way to build our highly available MemeGen site in the cloud. `Infrastructure as code` is one of the fundamental concepts of DevOps.

![](../Images/InfrastructureAsCodeVisualised.png?raw=true)

### 1. Remove everything ###
The big problem with manually making complex setups in AWS or any cloud is having to do that same thing for a different customer which results in small mistakes, lost time and having no real overview. We have a solution for that in this lab, but you can start by removing everything manually right now. :)

Do **not** remove your __private key__ (Services -> EC2 -> Key Pairs). 

1. Remove the __Autoscaling Group__ (Services -> EC2 -> Auto Scaling Groups -> `lab-ASG-<your_ID>` -> Actions -> Delete -> Yes, Delete)
1. Remove the __Launch Configuration__ (Services -> EC2 -> Launch Configurations -> `lab-LC-<your_ID>` -> Actions -> Delete Launch Configuration -> Yes, Delete)
1. Remove the __DynamoDB table__ (Services -> DynamoDB -> Tables -> Select `images-<your_ID>` -> Delete Table -> Delete)
1. Remove the __Load Balancer__ (Services -> EC2 -> Load Balancers -> `lab-ELB-<your_ID>` -> Actions -> Delete -> Yes, Delete)
1. Remove the __Route53 record__ (Services -> Route53 -> Hosted Zones -> gluo.cloud. -> `<your_ID>.gluo.cloud.` -> Delete Record Set)
1. Remove the __S3 Bucket__ (Services -> S3 -> Click the record but do not open `lab-images-bucket-<your_ID>` -> Above the table click "Delete Bucket" -> Type the bucket name -> Confirm)
1. Remove 2 __Security Groups__ (Services -> EC2 -> 
    * `lab_SecGroup_ELB_<your_ID>` -> Actions -> Delete Security Group -> Yes, Delete)
    * `lab_SecGroup_EC2_<your_ID>` -> Actions -> Delete Security Group -> Yes, Delete)

### 2. Execute a CloudFormation script ###
Using CloudFormation you can command AWS Services to start up and interconnect with each other using code instead of your normal browser interface or AWS Console.
This is a lot faster, makes the structure more intact and readable and is a lot easier to clean up, among many other benefits.

1. Go to `Services -> CloudFormation -> Create Stack`
1. There are multiple ways to create a CloudFormation template or script, there's even a visual editor but we'll stick to code. Press `Browse` and find `cloudformation-application-create.yml` under `Lab 8 - Cloudformation (infra 2.0)` in this repository.
1. Press `Next`.
1. Fill in the form:
    * Stack name: `lab-app-<your_ID>`. (Please make sure this is correct.)
    * StudentIdParam: `<your_ID>`. (Please make sure this is correct.)
    * KeyNameParam: `lab_key_<your_ID>`.
    * AmiParam: `ami-8fd760f6`.
    * Ec2InstanceTypeParam: `t2.micro`.
    * DomainNameParam: `gluo.cloud`.
    * VpcIdParam: `defaultVPC`.
    * PublicSubnetsParam: 
        * `localSubnet0`.
        * `localSubnet16`.
        * `localSubnet32`.
1. Press `Next`.
1. Press `Next`.
    * Check `I acknowledge ...`
1. Press `Create`.

Creating every service will take about 10-15 minutes.

This script will setup:

* An DynamoDB table functioning as our database.
* An S3 Bucket functioning as our fileserver.
* An Autoscaling Group that creates 2 EC2 Instances.
* A Launch Configuration that uses scripts on the ASG EC2 instances to install LAMP and our application...
* A Load Balancer that points to any amount of EC2 Instances linked to the Autoscaling Group.
* A Route53 DNS Record pointing to the Load Balancer.

### 3. Verify installation ###
Once your Cloudformation stack's status shows `CREATE_COMPLETE`, you can continue. In the meantime, look around the tabs of the Cloudformation stack, especially the `Events`, `Resources` and `Template` tabs are interesting.

Let's see if our app works.

1. In `Services -> Cloudformation`
    1. Click your cloudformation stack `lab-app-<your_ID>`. 
    1. Click the `Outputs` tab.
        * This provides some handy links to your resources.
    1. Copy the Route53 record `lab-app-<your_ID>.gluo.cloud`
    1. Browse to it.
        * This could give you a nice "The connection was reset" for up to 5-10 minutes up until the stack is created. 
1. Verify the EC2 instances from the Autoscaling Group are connected to the Load Balancer in `Services -> EC2 -> Load Balancers -> Click your Load Balancer -> Click the "Instances" tab`. 
    * Once their status is `InService`, you should be able to connect to the MemeGen web app. 

## End of Lab 8 ##
Et voilà! This method is much easier, more consistent, readable and less prone to errors than doing everything manually. Our whole application is up and running in minutes without much effort. This is infrastructure2.0.

Run this command on the management instance to update your score: `sudo checkscore`.

Now let's try to break things in the last lab. ([To last lab](../Lab%209%20-%20Chaos%20Engineering)) 

### More info ###

* What is Cloudformation? (https://aws.amazon.com/cloudformation/).
* The Cloudformation specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/cloudformation/).

