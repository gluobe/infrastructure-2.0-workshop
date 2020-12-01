# **LAB 0 - Prerequisites** #

### 1. Downloads and installs ###

* We'll be using the SSH protocol to log in to Ubuntu Linux instances running on AWS. Windows users will need to download [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html).

### 2. AWS Console login and SSH key ###
AWS has a browser interface called the AWS Console. To log in to the AWS Console you'll need credentials. Every student or student pair will get an `ID`. This number is part of a string which will be used as your username and will be appended to every service you create in AWS.

1. Receive the following information from the Workshop Tutors:
    * `<your_ID>`
    * `<AWS_Password>`
1. [Login to the AWS Console](https://gluo-workshop.signin.aws.amazon.com/console).
    * Account: gluo-workshop
    * Username: lab-student`<your_ID>`
    * Password: `<AWS_Password>`

        ![](../Images/AWSConsoleLogin.png?raw=true)

1. Once you're logged in, make sure you're in the `eu-west-1` or `Ireland` region by clicking the country in the top right corner of the console and selecting the right one. You have no permissions in any other region.

    ![](../Images/AWSRegionSelection.png?raw=true)
    
### 3. Verify SSH login ###
We'll be logging into a bunch of instances using the SSH protocol in the commandline with private keys. 
Please make sure you log in to the machine that corresponds with your ID!

1. First [download your ssh private key](http://studentinfo.gluo.cloud/index.html) which is used as authorization when logging into your Management Instance.
1. In the AWS Console, go to `Services -> EC2 -> Instances`.
1. Find **the instance with your ID**'s `public IP-address`.
    * Follow the image below (top left, top right, bottom).
  
    ![](../Images/AWSGotoIntro.png?raw=true)

##### **On Windows**

1. Open Putty.
1. Under `Connection->SSH->Auth`
    1. Click `Browse`.
    1. Choose the .ppk file: `management_key.ppk`.
1. Under `Session`
    1. Fill in `ubuntu@<server_public_ip>`.
    1. Click `Open`.
1. Accept the fingerprint (if needed).
1. You're now logged in as the `ubuntu` user!
1. **Please verify that the prompt's hostname number reflects your own ID**! `ubuntu@management<your_ID>:~$`
      
##### **On Linux or MacOS**

1. Open your Terminal.
1. `chmod 400 management_key` 
    * Make sure the key file has no permissions on anyone but the owner.
1. `ssh -i management_key ubuntu@<server_public_ip>` 
    * Log in to the server with the private key.
1. Accept the fingerprint (if needed).
1. You're now logged in as the `ubuntu` user!
1. **Please verify that the prompt's hostname number reflects your own ID**! (`ubuntu@management<your_ID>:~$`)

![](../Images/SSHManagementLogin.png?raw=true)
    
# End of Lab 0
Congratulations! You successfully logged in to an AWS EC2 instance using a private key.

To update your score, run this command `sudo checkscore`.

![](../Images/EC2RunScoringScriptLab0.png?raw=true)

You can go to https://scoring.gluo.cloud to see your workshop progress.

Then continue to the next lab, where we'll explore the AWS console and create an EC2 instance. ([Next lab](../Lab%201%20-%20AWS%20Console%20and%20EC2))

