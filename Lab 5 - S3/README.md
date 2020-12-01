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
1. Find your bucket. We've already created one for you, it should be named `lab-images-bkt-<your_ID>`.
1. There's (almost) nothing in it! That'll soon change, when we upload all our memes to it.
    
### 2. Installing AWSCLI ###
AWS has its AWS Console (which we've been working in) to access AWS via your browser, but it's also accessible via a commandline tool named AWSCLI. It can be installed via a Python package manager called Pip.

Do this on **ONE** of your instances.

1. `pip3 install awscli`
    *  Install AWSCLI with the python package manager pip.
1. `aws --version`
    *  Check if AWSCLI is installed correctly.

> root@ip-192-168-16-213:~# **aws --version**
>
> aws-cli/1.14.9 Python/2.7.12 Linux/4.4.0-1041-aws botocore/1.8.13

### 3. Trying out AWSCLI ###
Let's upload a file to a bucket to see how simple the AWSCLI is.

1. `aws s3 cp /var/www/html/meme-generator/memes/successkid.jpg s3://lab-images-bkt-<your_ID>`
    * Upload a meme of your choosing to the bucket 
    
    > root@ip-172-31-16-165:~# **aws s3 cp /var/www/html/meme-generator/memes/successkid.jpg s3://lab-images-bkt-0**
    >
    > upload: ../var/www/html/meme-generator/memes/successkid.jpg to s3://lab-images-bkt-0/successkid.jpg

1. Go back to your bucket, the image should have been added!

    ![](../Images/S3BucketContents.png?raw=true)
    
The AWSCLI is a great tool for automating regular tasks. I've used it, in my daily life for example, to upload encrypted files to an encrypted s3 bucket as a backup solution. All I had to do was create a shell script, add some AWSCLI commands and add the script as a cronjob to execute on my laptop every day at 3PM.

    > [ben@work ~]$ aws s3 ls s3://backup-bucket
    > 2020-02-29 12:59:50  678516945 backup-2020-02-29-1582977561.zip
    > 2020-03-31 15:00:27  678774775 backup-2020-03-31-1585659601.zip
    > 2020-04-30 15:00:41  678764146 backup-2020-04-30-1588251601.zip
    > ...

### 4. Change the site config ###
Our bucket is ready and we know how it works, so let's enable our applications on both instances to use the S3 bucket as a file storage solution instead of using the local filesystem.

Do this on **both** instances.

1. **Change config.php.**
    1. Enter `/var/www/html/config.php` using your favorite editor.
    1. Change the `$remoteFiles` variable to `true`.

### 5. Create another meme ###
Data storage AND file storage is now centralized. The meme generator site uses PHP and PHP uses the AWS SDK to interact with the cloud. Every time you create a meme, data is uploaded to DynamoDB and the meme file is uploaded and put public via S3.

1. Create a meme on **both the blue and green instance**.
1. Refresh the load balancer page a couple of times. All images should load on both instances.
1. Go and look at your bucket's contents again. All memes will have been uploaded.

* You might notice that your image is not loaded immediately after creating it now. This is because S3 is not very fast & our application is not very good :). 

## End of Lab 5 ##
Congratulations! You've successfully switched to using S3 as your storage device.

To update your score, `exit` to your management instance and run this command `sudo checkscore`. You needn't log back into your own instances.

Once you have two Instances, both connected to a Load Balancer, DynamoDB and S3, you may continue to the [next lab](../Lab%206%20-%20Route%2053).

### More info ###

* What is S3? (https://aws.amazon.com/s3/).
* The AWS commandline interface (https://aws.amazon.com/cli/).
* AWSCLI available services (https://docs.aws.amazon.com/cli/latest/reference/index.html#available-services)
* The S3 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/s3/index.html).
