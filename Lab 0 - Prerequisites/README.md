# **LAB 0 - Prerequisites** #

### 1. Downloads and installs ###

* We'll be using the SSH protocol to log in to Ubuntu Linux instances running on AWS. Windows users will need to download [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html), Linux and Mac users can sit back, relax and do nothing!

### 2. AWS Console login and SSH key ###

AWS has a graphical user interface (GUI) that is accessible through a website.  To log in to the AWS Console you'll need credentials.

* Please go to [this link](http://studentinfo.gluo.cloud/index.html) to download your SSH key and view your AWS Console account information and initial virtual machine, also called EC2 Instance.
* Login to AWS Console using [this link](https://gluo-workshop.signin.aws.amazon.com/console).
* Once you are logged in, make sure you're in the `eu-west-1` or `Ireland` region by clicking the country in the top right corner of the console and selecting the right one. You'll have no permissions in any other region.
    * ![](../Images/RegionSelection.png?raw=true)
    
### 3. Verify SSH login ###

We'll be logging into a bunch of instances using the SSH protocol in the commandline with private keys. 
Please make sure you can log in to the machine that corresponds with your ID!

* In the AWS Console, go to `Services -> EC2 -> Instances` and find your instance's public IP-address.

* To login from Windows: 
    * Open Putty.
    * Under "Connection->SSH->Auth & Browse", choose the .ppk file: `<key.ppk>`.
    * Under "Session", fill in `ubuntu@<server ip>`.
    * You can save the settings in the "Session" screen if you want.
    * Click "Open".
    * Accept the fingerprint.
  
On Windows it should look something like this:

![](../Images/PuttyLoginWindows.png?raw=true)
    
* To login from Linux or MacOS: 
    * Open your `Terminal`.
    * Make sure the .pem file has no permissions on anyone but the owner `chmod 400 <key.pem>`.
    * Use the following command in your terminal to log in: `ssh -i <key.pem> ubuntu@<server ip>`.
    * Accept the fingerprint.
    
On Linux or Mac it should look something like this:

```bash
[root@gluo Downloads]# ssh -i key.pem ubuntu@34.242.163.180
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

ubuntu@ip-192-168-0-134:~$
```