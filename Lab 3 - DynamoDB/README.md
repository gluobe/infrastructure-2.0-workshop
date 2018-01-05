# **LAB 3 - DynamoDB** #

## Start of Lab 3 ##
You should have one EC2 Instance with MemeGen installed on it which is reachable and works with its local MongoDB.

## Visual Interpretation ##
Now that we've shown everything can be installed and configured on one machine, we'll slowly extract every local service we're running and use more of AWS' services to create a more managed, safer and more scriptable version of our all-in-one server setup. 

Let's create a DynamoDB table (Amazon's NoSQL Solution) to replace MongoDB. This is an Amazon Managed Service, meaning Amazon takes more responsibility for the database being available. Previously on our EC2 instance we had pretty much no safeguards in place for making sure the Mongo database was kept running. 

![](../Images/Lab3.png?raw=true)

### 1. Create a DynamoDB table ###
Unlike in MongoDB, there aren't multiple databases, there are just multiple tables or collections in one giant database.

Let's create a table for our image data to be stored in.

1. Go to `Services -> DynamoDB`.
1. Click `Create table`.
    1. Table name: `images-<your_ID>`
    1. Primary key: `id`
    1. Press the dropdown box next to `id` and select `Number`.
1. Press `Create`.

### 2. Create DynamoDB records ###
Next let's add some records that will pull some pre-fabricated memes from the repository and show them on the site.

1. Once your new table is created, click it `images-<your_ID>`.
1. Click the `Items` tab.
1. Click `Create Item`.
    1. `id`, Number: `1`
    1. `name`, String: `successkid`
    1. `date`, String: `1`
1. Click `Save`.

    ![](../Images/DynamoDBAddRecord.png?raw=true)

* You can do the same for `badluckbrian`, `willywonka`, `yodawg` and `archer` if you want. 

    ![](../Images/DynamoDBAddedRecords.png?raw=true)

### 3. Link MemeGen to DynamoDB ###
In lab 2 we already installed the necessary PHP SDK that AWS provides so our code can interact with AWS' DynamoDB. The only thing we need to do now is change the configuration variable to switch the application over to using DynamoDB instead of our local MongoDB.

1. `vim /var/www/html/config.php`.
1. Press `i` (insert).
1. Change `$remoteData` to `true`.
1. Press `ESC`, then type `:wq` (write and quit) and press `ENTER`.

* If you're stuck in `vim`, press `ESC`, `:q!`, `ENTER`.

### 4. Give your instance permissions ###
Your account has restricted permissions. We're telling PHP to go and change data in DynamoDB, but nowhere have we specified any of our account credentials in the EC2 instance. This is why we have to give our EC2 instance a role so it has permissions to change things in other AWS services like adding a record in DynamoDB.

1. Go to `Services -> EC2 -> Instances`.
1. Select your instance `lab_EC2_instance1_<your_ID>`.
1. Click `Actions`.
    1. Hover over `Instance Settings`.
    1. Click `Attach/Replace IAM Role`.
1. Click the dropdown box displaying `No Role`.
1. Select the `lab_InstanceAccess` role.
1. Click `Apply`.

### 5. Use site again ###

1. Refresh the webpage. New memes will show up!
1. Create another awesome meme through the website.

### 6. Show DynamoDB Data ###
After you created a new meme we can go to DynamoDB and see the added record. 

1. Go to `Services -> DynamoDB`.
1. Click `Tables` in the sidebar.
1. Click your table `images-<your_ID>`.
1. Click the `Items` tab to view your table data. A record should have been added.

    ![](../Images/DynamoDBAddedRecordsOwnMeme.png?raw=true)

## End of Lab 3 ##
Once your MemeGen application works and your remote DynamoDB receives records, you can continue to the next lab. ([Next lab](../Lab%204%20-%20ELB))

### More info ###

* What is DynamoDB? (https://aws.amazon.com/dynamodb/).
* The DynamoDB specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/dynamodb/index.html).
