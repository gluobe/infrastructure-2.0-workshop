<?php

#####################
## Global Settings ##
#####################

$yourId = "<your_ID>";

$awsRegion = "eu-west-1"; # Ireland
#$awsRegion = "us-east-2"; # Ohio

$dynamoDBTable = "images-$yourId"; # not using cloudformation
#$dynamoDBTable = "cloudformation-images-$yourId"; # using cloudformation

$s3Bucket = "lab-$yourId-bucket"; # not using cloudformation
#$s3Bucket = "cloudformation-$yourId-bucket"; # using cloudformation 

###################
## Site Settings ##
###################

# Wether to save data locally (mongodb) or remotely (dynamodb)
$remoteData = false; # MongoDB
#$remoteData = true; # DynamoDB

# Wether to save the memes locally or remotely (s3)
$remoteFiles = false; # locally
#$remoteFiles = true; # s3

# Wether to set site color to blue or green (used to differentiate sites from ELB)
$siteColorBlue = false; # Green
#$siteColorBlue = true; # Blue

?>