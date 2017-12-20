# **LAB 8 - CloudFormation (infra 2.0)** #

## Visual Interpretation ##
For our last big leap, we're even forgoing using the AWS Console. Using code to start up AWS services is probably the most efficient way to build our highly available Wordpress site in the cloud. `Infrastructure as code` is one of the fundamental concepts of DevOps.

![](../Images/InfrastructureAsCodeVisualised.png?raw=true)

### 1. Execute a CloudFormation script ###
Using CloudFormation you can command AWS Services to startup and interconnect with each other using scripts instead of your normal browser interface or AWS Console.
This is a lot faster, makes the structure more intact and readable and is a lot easier to clean up, among many other benefits.

1. Go to `Services -> CloudFormation -> Create Stack`
1. There are multiple ways to create a CloudFormation template or script, there's even a visual editor but we'll stick to code. Press `Browse` and find `cloudformation-application-create.yml` under `Lab 8 - Cloudformation (infra 2.0)` in this repository.
1. Press `Next`.
1. Fill in the form:
    * Stack name: `lab-app-<your_ID>`.
    * StudentIdParam: `<your_ID>`.
    * KeyNameParam: `lab_key_<your_ID>`.
    * AmiParam: `ami-8fd760f6`.
    * Ec2InstanceTypeParam: `t2.micro`.
    * DomainNameParam: `gluo.cloud`.
    * VpcIdParam: `lab-cloudformation-NetworkingVPC`.
    * PublicSubnetsParam: 
        * `lab-cloudformation-PublicSubnetA`.
        * `lab-cloudformation-PublicSubnetB`.
        * `lab-cloudformation-PublicSubnetC`.
    * RDSUsernameParam: `wordpress`.
    * RDSPasswordParam: `Cloud247`.
    * RDSInstanceClassParam: `db.t2.micro`.
    * RDSAllocatedStorageParam: `20`.
1. Press `Next`.
1. Press `Next`.
1. Check `I acknowledge ...`
1. Press `Create`.

Creating every service will take about 10 to 15 minutes.

This script will setup:

* An RDS functioning as our Wordpress database.
* An S3 Bucket functioning as our fileserver.
* An Autoscaling Group that creates 2 EC2 Instances.
* A Launch Configuration that uses scripts on the ASG instances to 
    * install AWSCLI and S3FS.
    * push a fresh Wordpress installation to the new S3 Bucket.
    * sync the S3 bucket to the wordpress/ webmap on both instances.
* A Load Balancer that points to any amount of EC2 Instances linked to the Autoscaling Group.
* A Route53 DNS Record pointing to the Load Balancer.

The only thing you have to do still is configure Wordpress(, which can honestly also be easily automated).

### 2. Verify installation ###
**Once the installation is complete, we can navigate to the "Outputs" tab** when having your cloudformation stack `lab-app-<your_ID>` selected. It provides some handy links to our created services.

Connecting to `lab-app-<your_ID>.gluo.cloud` (the load balancer) could give you a nice "The connection was reset" for up to 5-10 minutes up until it's created. Verify the EC2 instances from the Autoscaling Group are connected to the Load Balancer (Services -> EC2 -> Load Balancers -> Click a Load Balancer -> Click the "Instances" tab). Once their status is "InService", you should be able to connect. 

Once you can reach the site, fill in the RDS Endpoint and general site info. Et voilà!

### More info ###

* What is Cloudformation? (https://aws.amazon.com/cloudformation/).
* The Cloudformation specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/cloudformation/).

