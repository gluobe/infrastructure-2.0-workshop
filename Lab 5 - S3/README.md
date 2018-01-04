# **LAB 5 - S3** #

## Start of Lab 5 ##
You should have two Instances, both connected to the same ELB and DynamoDB.

## Visual Interpretation ##
S3 is a file storing and sharing service a lot like Dropbox, Google Drive and OneDrive.
Now that our MemeGen data is being stored in DynamoDB, we might as well synchronize our memes to the cloud as well.

![](../Images/Lab5.png?raw=true)

### 1. Going to your S3 bucket ###
S3 is a service for storing files in a folder like structure. 

1. Go to `Services -> S3`.
1. Find your bucket. We've already created one for you, it should be named `lab-<your_ID>-bucket`.
1. There's (almost) nothing in it! That'll soon change, when we upload all our memes to it.
    
### 2. Synchronize files to the bucket ###
#### 2.1. Giving EC2 Instances access to S3 via a Role ####
We need to give both EC2 instances an access role. This role contains permissions to give read and write privileges to certain S3 buckets. This means the role will allow the EC2 Instances to read and write to your bucket in S3.

Do this for **both** instances.

1. Select one of your instances in `Services -> EC2 -> Instances`.
1. Press the `Actions` button above the instances list.
    * Hover over `Instance Settings`.
    * Press `Attach/Replace IAM Role`.
1. Select `lab_InstanceAccess`.
1. Press `Apply`.

#### 2.2. Installing AWSCLI ####
AWS has its AWS Console (which we've been working in) to access AWS via your browser, but it's also accessible via a commandline tool named AWSCLI. It's installed via a Python package called Pip.

Do this on **both** instances. (it might already be installed on the second one.)

1. `pip install awscli`
    *  Install AWSCLI with the python package manager pip.
1. `aws --version`
    *  Check if AWSCLI is installed correctly.

> root@ip-192-168-16-213:~# **aws --version**
>
> aws-cli/1.14.9 Python/2.7.12 Linux/4.4.0-1041-aws botocore/1.8.13

#### 2.3. Uploading images to S3 ####
We'll be using AWSCLI to upload our local memes to the S3 bucket.

Do this **once**, just to try it out.

1. Upload a meme of your choosing to the bucket `aws s3 cp /var/www/html/meme-generator/memes/successkid.jpg s3://lab-<your_ID>-bucket`.
1. Go back to your bucket, the image should have been added!

    ![](../Images/S3BucketContents.png?raw=true)

### 3. Switch to using S3 ###
#### 3.1. Change the site config ####
Next we'll change the application configuration file on both instances one more time to synchronize the memes to the bucket.

Do this on **both** instances.

1. `sed -i 's@^$remoteFiles.*@$remoteFiles = true; # S3 (Altered by sed)@g' /var/www/html/config.php`
    * Change the $remoteFiles variable in config.php to true.

#### 3.2. Create another meme ####
Your instances are now linked to each other not only by database but also by filesystem. Every time you create and display a meme, the memes folder will be synchronized in the php backend using awscli.

1. Create a meme on **both** instances. This will ensure all images from all instances are uploaded.
1. Refresh the load balancer page a couple of times. All images should load on both instances.
1. Go and look at your bucket's contents again. All memes will have been uploaded.

## End of Lab 5 ##
Once you have two Instances, both connected to a Load Balancer, DynamoDB and S3, you may continue to the next lab.

### More info ###

* What is S3? (https://aws.amazon.com/s3/).
* The S3 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/s3/index.html).
