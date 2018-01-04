# **LAB 0 - Prerequisites** #

### 1. Downloads and installs ###

* We'll be using the SSH protocol to log in to Ubuntu Linux instances running on AWS. Windows users will need to download [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html).

### 2. AWS Console login and SSH key ###
AWS has a graphical user interface (GUI) that is accessible through a website.  To log in to the AWS Console you'll need credentials. Every student or student pair will get an `ID`. This number is part of a string which will be used as your username and every service you create in AWS.

1. Receive the following information from the Workshop Tutors:
    * `<your_ID>`
    * `<AWS_Password>`
1. [Login to the AWS Console](https://gluo-workshop.signin.aws.amazon.com/console).
    * Account: gluo-workshop
    * Username: lab-student-<your_ID>
    * Password: <AWS_Password>

        ![](../Images/AWSConsoleLogin.png?raw=true)

1. Once you're logged in, make sure you're in the `eu-west-1` or `Ireland` region by clicking the country in the top right corner of the console and selecting the right one. You'll have no permissions in any other region.

    ![](../Images/AWSRegionSelection.png?raw=true)
    
### 3. Verify SSH login ###
We'll be logging into a bunch of instances using the SSH protocol in the commandline with private keys. 
Please make sure you log in to the machine that corresponds with your ID!

1. First [download your ssh private key](http://studentinfo.gluo.cloud/index.html) which is used as authorization when logging into your Management Instance.
1. Then in the AWS Console, go to `Services -> EC2 -> Instances` and find your instance's `public IP-address`.
    * Follow the image below (top left, top right, bottom).
  
    ![](../Images/AWSGotoIntro.png?raw=true)

##### **On Windows**

1. Open Putty.
1. Under `Connection->SSH->Auth`
    1. Click `Browse`.
    1. Choose the .ppk file: `lab_ManagementKey.ppk`.
1. Under `Session`
    1. Fill in `ubuntu@<server ip>`.
    1. Click `Open`.
1. Accept the fingerprint.
  
    ![](../Images/AWSPuttyLoginWindows.png?raw=true)
    
##### **On Linux or MacOS**

1. Open your Terminal.
1. `chmod 400 lab_ManagementKey` 
    * Make sure the key file has no permissions on anyone but the owner.
1. `ssh -i lab_ManagementKey ubuntu@<server ip>` 
    * Log in to the server with the private key.
1. Accept the fingerprint.

    >**[root@gluo Downloads]# ssh -i lab_ManagementKey ubuntu@34.242.163.180**
    >
    >Welcome to Ubuntu 16.04.3 LTS (GNU/Linux 4.4.0-1041-aws x86_64)
    >
    > * Documentation:  https://help.ubuntu.com
    > * Management:     https://landscape.canonical.com
    > * Support:        https://ubuntu.com/advantage
    >
    >  Get cloud support with Ubuntu Advantage Cloud Guest:
    >
    >    http://www.ubuntu.com/business/services/cloud
    >
    >
    >0 packages can be updated.
    >
    >0 updates are security updates.
    >
    >
    >
    >The programs included with the Ubuntu system are free software;
    the exact distribution terms for each program are described in the
    individual files in /usr/share/doc/copyright.
    >
    >Ubuntu comes with ABSOLUTELY NO WARRANTY, to the extent permitted by
    applicable law.
    >
    >To run a command as administrator (user "root"), use "sudo <command>".
    >
    >See "man sudo_root" for details.
    >
    >**ubuntu@ip-192-168-0-134:~$**
  
# End of Lab 0
Once you're logged in to the Management Instance, you can continue to the next lab.    

