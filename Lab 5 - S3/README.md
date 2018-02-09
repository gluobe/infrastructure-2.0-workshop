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
1. Find your bucket. We've already created one for you, it should be named `lab-images-bucket-<your_ID>`.
1. There's (almost) nothing in it! That'll soon change, when we upload all our memes to it.
    
### 2. Installing AWSCLI ###
AWS has its AWS Console (which we've been working in) to access AWS via your browser, but it's also accessible via a commandline tool named AWSCLI. It's installed via a Python package manager called Pip.

Do this on **both** instances. (It might already be installed on your second instance.)

1. `pip install awscli`
    *  Install AWSCLI with the python package manager pip.
1. `aws --version`
    *  Check if AWSCLI is installed correctly.

> root@ip-192-168-16-213:~# **aws --version**
>
> aws-cli/1.14.9 Python/2.7.12 Linux/4.4.0-1041-aws botocore/1.8.13

### 3. Trying out AWSCLI ###
PHP will be using AWSCLI to upload and download our local memes to the S3 bucket. We'll do it manually once just to try it out. 

1. `aws s3 cp /var/www/html/meme-generator/memes/successkid.jpg s3://lab-images-bucket-<your_ID>`.
    * Upload a meme of your choosing to the bucket 
    
    > root@ip-172-31-16-165:~# aws s3 cp /var/www/html/meme-generator/memes/successkid.jpg s3://lab-images-bucket-2
    >
    > upload: ../var/www/html/meme-generator/memes/successkid.jpg to s3://lab-images-bucket-2/successkid.jpg

1. Go back to your bucket, the image should have been added!

    ![](../Images/S3BucketContents.png?raw=true)

### 4. Change the site config ###
Next we'll change the application configuration file on both instances one more time to switch it to using S3 instead of the local filesystem.

Do this on **both** instances.

1. **Change config.php.**
    1. Enter `/var/www/html/config.php` using your favorite editor.
    1. Change the `$remoteFiles` variable to `true`.

### 5. Create another meme ###
Your instances are now linked to each other not only by database but also by filesystem. Every time you create and display a meme, the memes folder will be synchronized in the php backend using awscli.

1. Create a meme on **both the blue and green instance**. This will ensure all images from all instances are uploaded.
1. Refresh the load balancer page a couple of times. All images should load on both instances.
1. Go and look at your bucket's contents again. All memes will have been uploaded.

* You might notice that your image is not loaded immediately after creating it now. This is because S3 is not very fast & our application is not very good :). Simply refresh the page to prevent this issue.

## End of Lab 5 ##
Congratulations! You've successfully switched to using S3 as your storage device.

To update your score, `exit` to your management instance and run this command `sudo checkscore`. You needn't log back into your own instances.

Once you have two Instances, both connected to a Load Balancer, DynamoDB and S3, you may continue to the [next lab](../Lab%206%20-%20Route%2053).

### More info ###

* What is S3? (https://aws.amazon.com/s3/).
* The AWS commandline interface (https://aws.amazon.com/cli/).
* AWSCLI available services (https://docs.aws.amazon.com/cli/latest/reference/index.html#available-services)
* The S3 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/s3/index.html).
