# **LAB 1 - AWS Console & EC2** #
### 0. Login to AWS Console ###

1. Log in to the AWS console if you're not already, use [this link](https://gluo-workshop.signin.aws.amazon.com/console).
1. Make sure you're in the `EU-WEST-1` or `Ireland` region by clicking the country in the top right corner of the console and selection the right one. You'll have no permissions in any other region.
    * ![](../Images/RegionSelection.png?raw=true)
1. AWS provides many different Cloud services, of which we'll only see a small handful.
    * Look at `Services` dropdown menu. We'll explore EC2, RDS, S3, Route53 and Cloudformation.
1. Go to `Services -> EC2 -> Instances`. This is the location where you can see all your EC2 virtual machines of a specific region.
1. We'll begin by creating a simple EC2 instance or cloud virtual machine, which has some simple firewall rules assigned via the EC2 Security Group service and has your own private SSH key assigned to login with instead of the one provided.

### 1.1. Create your own key pair ###
Amazon Web Services does not allow default password access to new EC2 Instances. Instead, everything is done with a key pair, which is very safe (if used correctly) and not very difficult once you get the hang of it.
The key pair consists of a public and private key. The public key is put onto a remote server and the private key is used to log in to these servers.

1. Make sure you're logged in to the provided Management virtual machine. If not, follow the previous lab's instructions.
1. Switch to the superuser by typing `sudo su -`.
1. Generate a key pair with `ssh-keygen -b 2048 -t rsa -f key -P ""`.
1. The created files are visible in the current directory `ls -l`.
1. Change the private key's name to have an extension `mv key key.pem`.
1. Look at the files' permissions with `ls -l`.
    * Note the private key's permissions. Only the owner of the file can change these.
1. You'll want something that looks like this:

```bash
root@ip-192-168-0-134:~# ssh-keygen -b 2048 -t rsa -f key -P ""
Generating public/private rsa key pair.
Your identification has been saved in key.
Your public key has been saved in key.pub.
The key fingerprint is:
SHA256:reeEWe7ReVk3EwRO+CLgwqpx8kiAAG+U9q9+KgT+qkQ root@ip-192-168-0-134
The key\'s randomart image is:
+---[RSA 2048]----+
|o ..        .o.. |
|.oo    .   .o .  |
|o.o.. . .   .. . |
|+.  .o . o . .  .|
|oE  ... S + .  oo|
|.=.o  .  * . . o+|
|.oO  .  + = o o  |
|.o.o. .  = . .   |
|o..ooo    o      |
+----[SHA256]-----+
root@ip-192-168-0-134:~# mv key key.pem
root@ip-192-168-0-134:~# ls -l
total 8
-rw------- 1 root root 1679 Dec 13 13:59 key.pem
-rw-r--r-- 1 root root  403 Dec 13 13:59 key.pub
```

### 1.2. Import public key in AWS ###
We then need to import this public key into AWS so we can link Instances to this key and then log in to the Instances with the private key.

1. Go to `Services -> EC2 -> Key Pairs` under `Network & Security`.
1. Click the `Import Key Pair` button.
1. Change the `Key pair name` to `lab_key_<your_ID>`.
1. You can print a key's contents in the terminal with `cat key.pub`, paste the public key's contents in the multiline input box.
1. Click `Import`.

### 2. Create a Security Group ###
One more step before we can create our own instance. We need some firewall rules to link our instance with. In AWS, a group of security rules is called a Security Group and can be linked to multiple instances and even other AWS services. In AWS you can also create your own Network, also called VPC, which has its own Subnets. We'll be using one that's provided for you already.

1. Go to `Services -> EC2` in the AWS Console.
1. On the left pane, click `Security Groups` under `Network & Security`.
1. Click `Create Security Group (Button)`.
1. Fill in the Security Group information:
    * Name: `lab_SecGroup_EC2_<your_ID>`
    * Description: `Security Group for EC2 Instances`
    * VPC: `lab_ManagementVpc`
    * Add some **inbound** rules
        * `SSH` on port `22`, `source: anywhere`
        * `HTTP` on port `80`, `source: anywhere`
        * `All ICMP - IPv4` on port `0-65535`, `source: anywhere`
1. Click `Create`.

### 3. Launch an instance ###
Now we can finally spawn an instance and link it to our created security group and created key pair.

1. Go to: `Services -> EC2 -> Instances -> Launch Instance (Button)`.
1. Select `Ubuntu Server 16.04 LTS`.
1. Instance type refers to how many virtual resources are allocated to the instance we're about to create. Memory and vCPUs are probably the deciding factor here.
    * Make sure to choose `t2.micro` and press `Next: Configure Instance Details`
1. In the following form, choose:
    * Network: `lab_ManagementVpc`
    * Auto-assign Public IP: `Enable`
1. Press `Next: Add Storage`.
1. Press `Next: Add Tags`
1. With Tags, you can reference the instance from many different AWS services. The tag "Name" will make sure its properly named when looking at the EC2 Instances list.
    * Add a tag with key `Name` and value `lab_EC2_instance1_<your_ID>`.
    * Press `Next: Configure Security Group`
1. We opened port 22 for SSH access and port 80 for HTTP access via the browser.
    * Select `Select an existing security group` and check `lab_SecGroup_EC2_<your_ID>`.
    * Press `Review and Launch`
1. Press `Launch`
1. Choose the **existing** key `lab_key_<your_ID>`, acknowledge it and press `Launch Instances`.
1. Press `View Instances`.

A new Instance is being created and should be ready very soon:

![](../Images/EC2InstanceRecord.png?raw=true)

### 4. Log in the created Instance ###
We'll be logging into our created EC2 Instance from the management EC2 Instance. 
The Management server's hostname or prompt isn't particularly helpful with knowing on which server we're on since it's just an IP-address. We can counter this by changing our Management server's hostname to something else `hostnamectl set-hostname management-server-<your_ID> && exec bash`.

```bash
root@ip-192-168-32-9:~# hostnamectl set-hostname management-server-6 && exec bash
root@management-server-6:~#
```

Now to log in to the created instance...

1. Copy the public IP-address of your new instance from the AWS console in `Services -> EC2 -> Instances`.
1. Login:
    * Make sure the private key has correct permissions `chmod 400 key.pem`.
    * Use the command `ssh -i key.pem ubuntu@<public IP-address>`.
1. Accept the fingerprint by entering `yes`.
1. Once you see a (different) prompt you've logged in your new instance.

It should look something like this:

```
root@management-server-6:~# ls
key.pem  key.pub
root@management-server-6:~# ssh -i key.pem ubuntu@34.250.254.23
The authenticity of host '34.250.254.23 (34.250.254.23)' can\'t be established.
ECDSA key fingerprint is SHA256:75+xiFDgeLOll70V+s/svq4Qg6kD2wTUQNxPi6mVgTo.
Are you sure you want to continue connecting (yes/no)? yes
Warning: Permanently added '34.250.254.23' (ECDSA) to the list of known hosts.
Welcome to Ubuntu 16.04.3 LTS (GNU/Linux 4.4.0-1041-aws x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

  Get cloud support with Ubuntu Advantage Cloud Guest:
    http://www.ubuntu.com/business/services/cloud

0 packages can be updated.
0 updates are security updates.



The programs included with the Ubuntu system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/copyright.

Ubuntu comes with ABSOLUTELY NO WARRANTY, to the extent permitted by
applicable law.

To run a command as administrator (user "root"), use "sudo <command>".
See "man sudo_root" for details.

ubuntu@ip-192-168-0-84:~$
```

* Right now we're using the Management instance as a "bastion host", a gateway to an instance that could be locked from the outside world. This is very safe since the our created instance could house valuable information which we don't want to risk opening to the internet.

### More info ###

* What is AWS? (https://aws.amazon.com/what-is-aws/).
* Main competitors: Google Cloud Platform (https://cloud.google.com/) and Microsoft Azure (https://azure.microsoft.com/en-us/).
* The AWS commandline interface (https://aws.amazon.com/cli/).
* The EC2 specific AWS CLI documentation (http://docs.aws.amazon.com/cli/latest/reference/ec2/).

