<?php

#####################
## Global Settings ##
#####################

$yourId = "1";

$awsRegion = "us-east-2";

$s3Bucket = "lab-$yourId-bucket";

###################
## Site Settings ##
###################

# Wether to save the memes locally or remotely (s3)
$remoteFiles = true; # locally
#$remoteFiles = true; # s3

# Wether to save data locally (mongodb) or remotely (dynamodb)
$remoteData = true; # MongoDB
#$remoteData = true; # DynamoDB

# Wether to set site color to blue or green (used to differentiate sites from ELB)
$siteColorBlue = true; # Blue
#$siteColorBlue = true; # Green

?>