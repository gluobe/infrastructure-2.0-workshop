### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

1. You should be logged in to your Management Instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `export instance1=$(aws ec2 describe-instances --region $MYREGION | jq '.Reservations[]' | jq ".Instances[] | select(.Tags[].Value==\"lab_EC2_instance1_$MYID\") | .InstanceId" | tr -d '"') && export instance2=$(aws ec2 describe-instances --region $MYREGION | jq '.Reservations[]' | jq ".Instances[] | select(.Tags[].Value==\"lab_EC2_instance2_$MYID\") | .InstanceId" | tr -d '"')`
    * Get instance1 and instance2 ID.
1. `aws ec2 associate-iam-instance-profile --iam-instance-profile=Name=lab_InstanceAccess --instance-id=$instance1 --region $MYREGION && aws ec2 associate-iam-instance-profile --iam-instance-profile=Name=lab_InstanceAccess --instance-id=$instance2 --region $MYREGION`
    * Give instance1 and instance2 an instance profile.
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    # on both instances
1. Install awscli if not already
1. sed -i 's@^$remoteFiles.*@$remoteFiles = true; # S3 (Altered by sed)@g' /var/www/html/config.php