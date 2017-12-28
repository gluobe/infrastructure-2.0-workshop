# **LAB 5 - S3** #

## Visual Interpretation ##
S3 is a file storing and sharing service a lot like Dropbox, Google Drive and OneDrive.
Now that our Wordpress data is being stored in the RDS, we might as well synchronize our Wordpress files to the cloud as well.

![](../Images/Lab5.png?raw=true)

### 1. Going to your S3 bucket ###
S3 is a service for storing files in a folder like structure. 

1. Go to `Services -> S3`.
1. Find your bucket. We've already created one for you, it should be named `lab-<your_ID>-bucket`.
1. There's (almost) nothing in it! That'll soon change, when we upload all Wordpress files to it.
    
### 2. Upload Wordpress to the bucket ###
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

Do this **once** on your **first** instance.

1. Install Python `apt-get install python wget -y`.
1. Download Pip `wget https://bootstrap.pypa.io/get-pip.py`.
1. Install Pip `python get-pip.py`.
1. Remove Pip install file `rm get-pip.py`.
1. Install AWSCLI `pip install awscli`.
1. Check if AWSCLI is installed correctly with `aws --version`:

```bash
root@ip-192-168-16-213:~# aws --version
aws-cli/1.14.9 Python/2.7.12 Linux/4.4.0-1041-aws botocore/1.8.13
```

#### 2.3. Uploading Wordpress to S3 ####
We'll be using AWSCLI to upload our local Wordpress files to the S3 bucket.

Do this **once** on your **first** instance.

1. Move to apache web directory `cd /var/www/html`.
1. Upload the contents of your working Wordpress folder `aws s3 sync wordpress/ s3://lab-<your_ID>-bucket --region eu-west-1`.
    * If something fails here you might not have linked the S3 role to your EC2 Instances, go back to Lab 5, step 2.
1. Go back to your bucket, the Wordpress files should have been added!

Your bucket should look something like this:

![](../Images/S3BucketContents.png?raw=true)

### 3. Synchronize the bucket to the FS ###
#### 3.1. Install S3FS ####
S3, being an AWS service, offers many ways to interconnect different AWS services with it. Mounting an S3 bucket as a filesystem on Linux however is still not natively possible, but naturally there exists a Github project doing the exact thing.

Do this for **both** instances.

1. Remove your local copy `rm -rf /var/www/html/wordpress`. We'll be syncing it with the S3 bucket soon.
1. Install some packages with `apt-get install automake autotools-dev fuse g++ git libcurl4-gnutls-dev libfuse-dev libssl-dev libxml2-dev make pkg-config -y`.
1. Change to home directory `cd ~`.
1. Clone the git repository with `git clone https://github.com/s3fs-fuse/s3fs-fuse.git`.
1. Go into the project `cd s3fs-fuse/`.
1. Compile the project from source `./autogen.sh && ./configure && make && make install`.
1. Verify installation with `s3fs --version`.

The S3FS version command output should look something like this:

```bash
root@ip-192-168-16-213:~/s3fs-fuse# s3fs --version
Amazon Simple Storage Service File System V1.82(commit:566961c) with OpenSSL
Copyright (C) 2010 Randy Rizun <rrizun@gmail.com>
License GPL2: GNU GPL version 2 <http://gnu.org/licenses/gpl.html>
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.
```

#### 3.2. Synchronize Bucket to Apache webfolder ####

Do this for **both** instances.

1. Go to your apache web folder `cd /var/www/html`.
1. Create the wordpress folder again `mkdir wordpress`.
1. Synchronize your bucket with the wordpress folder `s3fs -o iam_role=auto -ouid=33,gid=33,allow_other lab-<your_ID>-bucket wordpress/`.

### 4. Log in to Wordpress ###

1. Use your Load Balancer's DNS name to browse to your Wordpress site again. The site isn't as quick to load, since we're using S3 as a web- and fileserver and it's not really supposed to be used for that.
1. Once you log in, go to your bucket and open the wp_config.php file. We were able to log in without configuring since our earlier configuration via the Wordpress web interface was generated into a .php file!

### More info ###

* What is S3? (https://aws.amazon.com/s3/).
* The S3 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/s3/index.html).
