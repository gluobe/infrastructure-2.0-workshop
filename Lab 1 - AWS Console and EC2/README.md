# **LAB 1 - AWS Console and EC2** #

## Start of Lab 1 ##
You should be logged in to your Management EC2 Instance.

### 0. AWS Console Introduction ###

AWS provides many different Cloud services, of which we'll only see a small handful.

* Click `Services` in the top left navbar in the AWS Console. We'll explore EC2, DynamoDB, S3, Route53 and Cloudformation.

    ![](../Images/AWSConsoleServicesOverview.png?raw=true)

* Go to `Services -> EC2 -> Instances`. This is the location where you can see all your EC2 virtual machines of a specific region.

    ![](../Images/AWSConsoleEC2Overview.png?raw=true)

* We'll begin by creating a simple EC2 instance or cloud virtual machine, which has some simple firewall rules assigned via the EC2 Security Group service and has your own private SSH key assigned to login with instead of the lab_ManagementKey one. We'll always use the management instance as a proxy to access instances we've created.
    * You are here:
    
    ![](../Images/ManagementProxyStep1.png?raw=true)
    
    * After this lab you'll be here:
    
    ![](../Images/ManagementProxyStep2.png?raw=true)

### 1. Create your own key pair ###
Amazon Web Services does not allow default password access to new EC2 Instances. Instead, everything is done with key pairs, which is very safe (if used correctly) and not very difficult once you get the hang of it.
The key pair consists of a public and private key. The public key is put onto a remote server and the private key is used to log in to these servers.

1. `sudo su -`
    * Switch to be superuser or root. Your prompt will turn red.
1. `ssh-keygen` (Press `Enter` a couple of times without filling in anything until your prompt returns.)
    * Generate a key pair.
1. `ls -l ~/.ssh/`
    * Look at the key pair. The private key is `id_rsa`, the public key is `id_rsa.pub` and they're located under your current user home's .ssh directory `/root/.ssh/`.
    * Note that the private key's permissions (-rw-------) are stricter than the public key's permissions (-rw-r--r--). 
    
    > **ubuntu**@management-server:~$ **sudo su -**
    >
    >
    > **root**@management-server:~$ **ssh-keygen**
    >
    > Generating public/private rsa key pair.
    >
    > Enter file in which to save the key (/root/.ssh/id_rsa): 
    >
    > Enter passphrase (empty for no passphrase): 
    >
    > Enter same passphrase again:
    >
    > Your **identification** has been saved in **/root/.ssh/id_rsa**.
    >
    > Your **public key** has been saved in **/root/.ssh/id_rsa.pub**.
    >
    > The key fingerprint is:
    >
    > SHA256:E2C2C+Jho1+VdPLUqXLhKFD9e6PZGTe1ATpV6CNbOq8 root@management-server
    >
    > The key's randomart image is:
    >
    >...
    >
    > ((((RANDOM ART))))
    >
    >...
    >
    > root@management-server:~$ **ls -l ~/.ssh/**
    >
    > total 12
    >
    > -rw------- 1 root root  554 Dec 22 12:21 authorized_keys
    >
    > **-rw-------** 1 root root 1679 Dec 22 13:12 **id_rsa** 
    >
    > **-rw-r--r--** 1 root root  404 Dec 22 13:12 **id_rsa.pub**
    >
    > root@management-server:~$

### 2. Import public key in AWS ###
We then need to import this public key into AWS so we can link Instances to this key and then log in to the Instances with the private key.

1. Go to `Services -> EC2 -> Key Pairs` under `Network & Security`.
1. Click the `Import Key Pair` button.
    1. Change the `Key pair name` to `lab_key_<your_ID>`.
    1. Print the public key's contents with `cat ~/.ssh/id_rsa.pub`
    1. Copy and paste the public key's contents in the multiline input box.
    1. Click `Import`.

    ![](../Images/EC2PublicKeyUpload.png?raw=true)

### 3. Create a Security Group ###
One more step before we can create our own instance. We need some firewall rules to link our instance with. In AWS, a group of security rules is called a Security Group and can be linked to multiple instances and even other AWS services. 

In AWS you can also create your own Virtual Network, also called VPC, which has its own Subnets but you can forget that for now. We'll be using one that's provided for you already (defaultVPC).

1. Go to `Services -> EC2` in the AWS Console.
1. On the left pane, click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_SecGroup_EC2_<your_ID>`
    * Description: `Security Group for EC2 Instances`
    * VPC: `defaultVPC`
    * Add some **inbound** rules
        * `SSH` on port `22`, `source: anywhere`
        * `HTTP` on port `80`, `source: anywhere`
        * `All ICMP - IPv4` on port `0-65535`, `source: anywhere`
1. Click `Create`.

    ![](../Images/EC2SecurityGroup.png?raw=true)

### 4. Launch an instance ###
Now we can finally spawn an instance and link it to our created security group and created key pair.

1. Go to: `Services -> EC2 -> Instances -> Launch Instance (Button)`.
1. Select `Ubuntu Server 16.04 LTS`.
1. Instance type refers to how many virtual resources are allocated to the instance we're about to create. Memory and vCPUs are probably the deciding factor here.
    * Make sure to choose `t2.micro` and press `Next: Configure Instance Details`
1. Press `Next: Add Storage`.
1. Press `Next: Add Tags`
1. With Tags, you can reference the instance from many different AWS services. The tag "Name" will make sure it is properly named when looking at the EC2 Instances list.
    * Add a tag with key `Name` and value `lab_EC2_instance1_<your_ID>`.
1. Press `Next: Configure Security Group`
1. We opened port 22 for SSH access and port 80 for HTTP access via the browser. Let's link the Security Group to the instance.
    * Select `Select an existing security group`.
    * Check `lab_SecGroup_EC2_<your_ID>`.
1. Press `Review and Launch`
1. Press `Launch`
1. Choose the **existing** key `lab_key_<your_ID>`, acknowledge it and press `Launch Instances`.
1. Press `View Instances`.

    ![](../Images/EC2NewInstanceCreated.png?raw=true)

### 5. Log in the created Instance ###
Let's log in to our EC2 Instance from the management EC2 Instance using our own private key (~/.ssh/id_rsa). 

1. Copy the `IPv4 Public IP` of your new instance from the AWS console in `Services -> EC2 -> Instances`.
1. From your management instance use the command `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.
1. Accept the fingerprint by entering `yes`.
1. Once you see a green prompt you've logged in your new instance.

    >root@**management-server**:~$ **ls ~/.ssh**
    >
    >authorized_keys  
    >
    >id_rsa  
    >
    >id_rsa.pub
    >
    >
    >root@**management-server**:~$ **ssh -i ~/.ssh/id_rsa ubuntu@18.218.40.45**
    >
    >The authenticity of host '18.218.40.45 (18.218.40.45)' can't be established.
    >ECDSA key fingerprint is SHA256:XltK5+RljF2cuUGG4bnB4b6SQ/UM1CSN5QONehFhynE.
    >
    >Are you sure you want to continue connecting (yes/no)? **yes**
    >
    >Warning: Permanently added '18.218.40.45' (ECDSA) to the list of known hosts.
    >
    >Welcome to Ubuntu 16.04.3 LTS (GNU/Linux 4.4.0-1041-aws x86_64)
    >
    > * Documentation:  https://help.ubuntu.com
    > * Management:     https://landscape.canonical.com
    > * Support:        https://ubuntu.com/advantage
    >
    >  Get cloud support with Ubuntu Advantage Cloud Guest:
    >    http://www.ubuntu.com/business/services/cloud
    >
    >0 packages can be updated.
    >0 updates are security updates.
    >
    >
    >
    >The programs included with the Ubuntu system are free software;
    >the exact distribution terms for each program are described in the
    >individual files in /usr/share/doc/copyright.
    >
    >Ubuntu comes with ABSOLUTELY NO WARRANTY, to the extent permitted by
    >applicable law.
    >
    >To run a command as administrator (user "root"), use "sudo <command>".
    >See "man sudo_root" for details.
    >
    >
    >ubuntu@**ip-172-31-25-78:~$**

## End of Lab 1 ##
Congratulations! You're logged in to your own created EC2 Instance.

To update your score, `exit` to your management instance and run this command `/.checkScore.sh`, then log back in to your own instance `ssh -i ~/.ssh/id_rsa ubuntu@<public IP-address>`.

![](../Images/EC2RunScoringScript.png?raw=true)

And then continue to the [next lab](../Lab%202%20-%20Manual%20installation%20(Infra%200.0)). 

### More info ###

* What is AWS? (https://aws.amazon.com/what-is-aws/).
* Main competitors: Google Cloud Platform (https://cloud.google.com/) and Microsoft Azure (https://azure.microsoft.com/en-us/).
* The AWS commandline interface (https://aws.amazon.com/cli/).
* The EC2 specific AWS CLI documentation (http://docs.aws.amazon.com/cli/latest/reference/ec2/).
