# **LAB 1 - AWS Console and EC2** #

## Start of Lab 1 ##
You should be logged in to your Management EC2 Instance.

### 0. AWS Console Introduction ###

AWS provides many different Cloud services, of which we'll only see a small handful.

* Click `Services` in the top left navbar in the AWS Console. We'll explore EC2, DynamoDB, S3, Route53 and Cloudformation.

    ![](../Images/AWSConsoleServicesOverview.png?raw=true)

* Go to `Services -> EC2 -> Instances`. This is the location where you can see all your EC2 virtual machines of a specific region.

    ![](../Images/AWSConsoleEC2Overview.png?raw=true)

* You can list instances with your specific ID from the EC2 list by filtering on `<your_ID>`. (It's a bit finicky, you should be able to fill in `id:<your_ID>` and press `ENTER`. If it doesn't work just move on.)

    ![](../Images/EC2FilterOnTag.png?raw=true)

* We'll begin by creating an EC2 instance, which has some simple firewall rules assigned via an EC2 Security Group and has your own private SSH key assigned to login with instead of the `management_key` you were given to access the management instance. You'll always use the management instance as a proxy to access instances you've created.
    * You are here:

    ![](../Images/ManagementProxyStep1.png?raw=true)

    * After this lab you'll be here:

    ![](../Images/ManagementProxyStep2.png?raw=true)

### 1. Create your own key pair ###
While you can configure your instance to allow users to log in via a password, the default configuration requires you to use key pairs. These key pairs are a bit more complicated than a simple password but are a lot safer and not very difficult once you get the hang of it.
A key pair consists of a **public** and **private key**. The public key is put onto a remote server and the private key is used to log in to that server.

1. `ssh-keygen`
    * Press `Enter` a couple of times without filling in anything until your prompt returns to generate a key pair with default parameters.
1. `ls -l ~/.ssh/`
    * The private key is `id_rsa`, the public key is `id_rsa.pub` and they're located under your current user's .ssh directory `/home/ubuntu/.ssh/` or `~/.ssh/`.
    * Note that the private key's permissions (-rw-------) are stricter than the public key's permissions (-rw-r--r--).

    > *ubuntu@management0:~$* **ssh-keygen**
    >
    > Generating public/private rsa key pair.
    >
    > Enter file in which to save the key (/home/ubuntu/.ssh/id_rsa):
    >
    > Enter passphrase (empty for no passphrase):
    >
    > Enter same passphrase again:
    >
    > Your **identification** has been saved in **/home/ubuntu/.ssh/id_rsa**.
    >
    > Your **public key** has been saved in **/home/ubuntu/.ssh/id_rsa.pub**.
    >
    >...
    >
    > *ubuntu@management0:~$* **ls -l ~/.ssh/**
    >
    > -rw------- 1 ubuntu ubuntu  554 Dec 22 12:21 authorized_keys
    >
    > **-rw-------** 1 ubuntu ubuntu 1679 Dec 22 13:12 **id_rsa**
    >
    > **-rw-r--r--** 1 ubuntu ubuntu  404 Dec 22 13:12 **id_rsa.pub**

### 2. Import public key in AWS ###
Now we need to import this public key into AWS so we can link Instances to this key and log in to the instances with the private key.

1. Go to `Services -> EC2 -> Key Pairs` under `Network & Security`.
1. Click `Actions`, then `Import Key Pair`.
    1. Change the `Key pair name` to `lab_key_student<your_ID>`.
    1. Print the public key's contents with `cat ~/.ssh/id_rsa.pub`
    1. Copy and paste the public key's contents in the multiline input box.
    1. Click `Import key pair`.

    ![](../Images/EC2PublicKeyUpload.png?raw=true)

### 3. Create a Security Group ###
One more step before we can create our own instance. We need some firewall rules to link our instance with. In AWS, a group of firewall rules is called a Security Group and can be linked to multiple instances and even other AWS services.

In AWS you can also create your own Virtual Network, also called VPC, which has its own Subnets but you can forget that for now. We'll be using one that's provided for you already (defaultVPC).

1. Go to `Services -> EC2` in the AWS Console.
1. On the left pane, click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_sg_ec2_student<your_ID>`
    * Description: `Security Group for EC2 Instances`
    * VPC: `defaultVPC`
    * Add some **inbound** rules
        * `SSH` on port `22`, `source: anywhere-ipv4`
        * `HTTP` on port `80`, `source: anywhere-ipv4`
        * `All ICMP - IPv4` on port `0-65535`, `source: anywhere-ipv4`
        * Since security groups are stateful, traffic that is allowed in (inbound) is automatically allowed back out (outbound).
1. Click `Create security group`.

    ![](../Images/EC2SecurityGroup.png?raw=true)

### 4. Launch an instance ###
Now we can finally spawn an instance and link it to our created security group and created key pair.

1. Go to: `Services -> EC2 -> Instances -> Launch Instances (Button)`.
1. Select `Ubuntu Server 20.04 LTS (ami-08ca3fed11864d6bb)`.
  * If it's not on the front page you may have to search for the AMI with the search box. It will be a `community AMI`. The AMI ID is unique so if you find an AMI with that ID you'll have found the correct one.
1. Instance type refers to how many virtual resources are allocated to the instance we're about to create. Memory and vCPUs are most often the deciding factor here.
    * Make sure to choose `t2.micro` and press `Next: Configure Instance Details`
1. Press `Next: Add Storage`.
1. Press `Next: Add Tags`
1. With Tags, you can reference the instance from many different AWS services. The tag "Name" will make sure it is properly named when looking at the EC2 Instances list.
    * Add a tag with key `Name` and value `lab_instance1_student<your_ID>`.
    * Add a tag with key `Id` and value `<your_ID>`.
1. Press `Next: Configure Security Group`
1. We opened port 22 for SSH access and port 80 for HTTP access via the browser. Let's link the Security Group to the instance.
    * Select `Select an existing security group`.
    * Check `lab_sg_ec2_student<your_ID>`.
1. Press `Review and Launch`
1. Press `Launch`
1. Choose the **existing** key `lab_key_student<your_ID>`, acknowledge it and press `Launch Instances`.
1. Press `View Instances`.

### 5. Log in the created Instance ###
Let's log in to our EC2 Instance from the management EC2 Instance using our own private key (~/.ssh/id_rsa).

  ![](../Images/EC2NewInstanceCreated.png?raw=true)

1. Copy the `IPv4 Public IP` of your new instance from the AWS console in `Services -> EC2 -> Instances`.
1. From your management instance use the command `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.
1. Accept the fingerprint by entering `yes`.
1. Once you see a green prompt you've logged in your new instance.

    >ubuntu@*management0*:~$ **ls ~/.ssh**
    >
    >authorized_keys  
    >
    >id_rsa  
    >
    >id_rsa.pub
    >
    >
    >ubuntu@*management0*:~$ **ssh -i ~/.ssh/id_rsa ubuntu@18.218.40.45**
    >
    >The authenticity of host '18.218.40.45 (18.218.40.45)' can't be established.
    >ECDSA key fingerprint is SHA256:XltK5+RljF2cuUGG4bnB4b6SQ/UM1CSN5QONehFhynE.
    >
    >Are you sure you want to continue connecting (yes/no)? **yes**
    >
    >Warning: Permanently added '18.218.40.45' (ECDSA) to the list of known hosts.
    >
    >Welcome to Ubuntu 20.04.1 LTS (GNU/Linux 5.4.0-1029-aws x86_64)
    >
    >...
    >
    >ubuntu@*ip-172-31-25-78*:~$

## End of Lab 1 ##
Congratulations! You're logged in to your own created EC2 Instance.

To update your score, `exit` to your management instance and run this command `sudo checkscore`, then log back in to your own instance `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.

![](../Images/EC2RunScoringScriptLab1.png?raw=true)

And then continue to the [next lab](../Lab%202%20-%20Manual%20installation%20(Infra%200.0)).

### More info ###

* What is AWS? (https://aws.amazon.com/what-is-aws/).
* Main competitors: Google Cloud Platform (https://cloud.google.com/) and Microsoft Azure (https://azure.microsoft.com/en-us/).
* The AWS commandline interface (https://aws.amazon.com/cli/).
* The EC2 specific AWS CLI documentation (http://docs.aws.amazon.com/cli/latest/reference/ec2/).
