# **LAB 8 - CloudFormation (infra 2.0)** #

## Visual Interpretation ##
For our last big leap, we're even foregoing using the AWS Console. Using code to start up AWS services is probably the most efficient way to build our highly available MemeGen site in the cloud. `Infrastructure as code` is one of the fundamental technical advantages brought about by the DevOps culture.

![](../Images/InfrastructureAsCodeVisualised.png?raw=true)

### 1. Remove everything ###
Just like some of us might have made mistakes when installing the MemeGen application on an EC2 instance, can we accidentally cause problems while manually clicking together infrastructure in the AWS Console.
AWS stumbled upon this problem as well and worked out a solution in the name of Cloudformation, but before we proceed with that, you can start by removing everything manually. :)

Do **not** remove your __private key__, though, we'll reuse it.

1. Remove the __Autoscaling Group__ (Services -> EC2 -> Auto Scaling Groups -> `lab-asg-<your_ID>` -> Actions -> Delete -> Yes, Delete)
1. Remove the __Launch Configuration__ (Services -> EC2 -> Launch Configurations -> `lab-lc-<your_ID>` -> Actions -> Delete Launch Configuration -> Yes, Delete)
1. Remove the __Load Balancer__ (Services -> EC2 -> Load Balancers -> `lab-elb-<your_ID>` -> Actions -> Delete -> Yes, Delete)
1. Remove the __DynamoDB table__ (Services -> DynamoDB -> Tables -> Select `lab-images-table-<your_ID>` -> Delete Table -> Delete)
1. Remove the __Route53 record__ (Services -> Route53 -> Hosted Zones -> gluo.cloud. -> `<your_ID>.gluo.cloud.` -> Delete Record Set)
1. Remove the __S3 Bucket__ (Services -> S3 -> Click the record but do not open `lab-images-bkt-<your_ID>` -> Above the table click "Delete Bucket" -> Type the bucket name -> Confirm)
    * You may have to empty the bucket first before deleting it.
1. Remove 2 __Security Groups__ (Services -> EC2 -> Security Groups)
    * `lab_sg_elb_student<your_ID>` -> Actions -> Delete Security Group -> Yes, Delete
    * `lab_sg_ec2_student<your_ID>` -> Actions -> Delete Security Group -> Yes, Delete

### 2. Execute a CloudFormation script ###
Using CloudFormation you can create AWS resources using code instead of clicking it together in the AWS Console.
Code can be automated, it makes the infrastructure more reliable and readable and makes the infrastructure a lot easier to clean up, among many other benefits.

1. Go to `Services -> CloudFormation -> Create Stack (-> With new resources)`
1. There are multiple ways to create a CloudFormation template or script, there's even a visual editor but we'll stick to code. Press `Template is ready`, then `Upload a template file`, then `Choose file` and find `cloudformation-application-create.yml` under `Lab 8 - Cloudformation (infra 2.0)` in this repository.
1. (Optional) If you want you can view the template in the designer by pressing `View in Designer`.
    ![](../Images/AWSCFDesigner.png?raw=true)
1. Press `Next`.
1. Fill in the form:
    * Stack name: `lab-cf-<your_ID>`. (Please make sure this is correct.)
    * StudentIdParam: `<your_ID>`. (Please make sure this is correct.)
    * KeyNameParam: `lab_key_student<your_ID>`.
    * AmiParam: `ami-08ca3fed11864d6bb`.
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

This stack consists of:

* A DynamoDB table functioning as our database.
* An S3 Bucket functioning as our fileserver.
* An Autoscaling Group that creates and maintains 2 EC2 Instances.
* A Launch Configuration that uses scripts on the ASG EC2 instances to install our application.
* A Load Balancer that points to any amount of EC2 Instances created by the Autoscaling Group.
* A Route53 DNS Record pointing to the Load Balancer.

You can pretty much script any of the tens or hundreds of services AWS offers. Some are more supported than others.

### 3. Verify installation ###
This stack will take up to 15 minutes but that's mostly because the stack has to wait for the meme generator to be installed on the EC2 instances.
In the meantime, look around the tabs of the Cloudformation stack, especially the `Events`, `Resources` and `Template` tabs are interesting. Feel free to scan through the code as well! You will recognize a lot of the resources that we configured manually in the previous labs.
Once your Cloudformation stack's status shows `CREATE_COMPLETE`, you can continue.

Let's see if our app works.

1. In `Services -> Cloudformation`
    1. Click your cloudformation stack `lab-cf-<your_ID>`.
    1. Click the `Outputs` tab.
        * This provides some handy links to your resources.
    1. Copy the Route53 record `lab-cf-<your_ID>.gluo.cloud`
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
