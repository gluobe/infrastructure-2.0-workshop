### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

1. You should be logged in to your Management Instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
    
#### Create and Configure EC2 Instance2
1. `aws ec2 run-instances --instance-type t2.micro --image-id ami-8fd760f6 --tag-specifications "ResourceType=instance,Tags=[{Key=Name,Value=lab_EC2_instance2_$MYID}]" --iam-instance-profile Name=lab_InstanceAccess --security-groups lab_SecGroup_EC2_$MYID --key-name lab-key-$MYID --region $MYREGION`
    * Create a second instance.
1. `aws ec2 describe-instances --filters "Name=tag:Name,Values=lab_EC2_instance2_$MYID" --region $MYREGION | jq '.Reservations[0].Instances[0].PublicIpAddress'`
    * Get the IP-address of your instance.
1. `ssh -i ~/.ssh/id_rsa ubuntu@<ec2_public_ip_address>`
    * Log in to the second instance with private key and IP-address.
1. `git clone https://github.com/gluobe/infrastructure-2.0-workshop.git ~/infra-workshop`
    * Clone the repository to your home directory.
1. `chmod 755 ~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Change permissions on the script to make it executable.
1. `~/infra-workshop/Scripts/InstallMemeGen-php.sh`
    * Execute the script.
1. Once `Local MemeGen installation complete.` is shown you've successfully installed MemeGen.
1. `sed -i "s@^\$yourId.*@\$yourId = \"$MYID\"; # (Altered by sed)@g" /var/www/html/config.php`
    * Change the config file to have **your ID**.
1. `sed -i 's@^$remoteData.*@$remoteData = true; # DynamoDB (Altered by sed)@g' /var/www/html/config.php`
    * Change the $remoteData variable to true to enable DynamoDB for MemeGen.
1. `sed -i 's@^$siteColorBlue.*@$siteColorBlue = true; # Blue (Altered by sed)@g' /var/www/html/config.php` 
    * Alter one of the instance's app to use another site color by changing `config.php`.
    
#### Create a Load Balancer    
1. `aws ec2 create-security-group --group-name lab_SecGroup_ELB_$MYID --description "Security Group for a Load Balancer" --region $MYREGION`
    * Create a security group for the load balancer.
1. `aws ec2 authorize-security-group-ingress --group-name lab_SecGroup_ELB_$MYID --protocol tcp --port 80 --cidr 0.0.0.0/0 --region $MYREGION`
    * Add port 22 and 80 as ingress to the Security Group.
1. `export secGroupId=$(aws ec2 describe-security-groups --region us-east-2 | jq ".SecurityGroups[] | select(.GroupName==\"lab_SecGroup_ELB_$MYID\") | .GroupId" | head -n 1 | tr -d '"')`
    * Save the ID of the Security Group.
1. `export subnetIds=$(aws ec2 describe-subnets --region us-east-2 | jq '.Subnets[] | select(.DefaultForAz==true) | .SubnetId' | tr -d '"')`
    * Save the ID's of the default VPC's subnets.
1. `aws elb create-load-balancer --load-balancer-name lab-ELB-$MYID --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80" --subnets $subnetIds --security-groups $secGroupId --region $MYREGION`
    * Create a Load Balancer.
1. `export instance1=$(aws ec2 describe-instances --region $MYREGION | jq '.Reservations[]' | jq ".Instances[] | select(.Tags[].Value==\"lab_EC2_instance1_$MYID\") | .InstanceId" | tr -d '"')`
    * Get instance1 ID.
1. `export instance2=$(aws ec2 describe-instances --region $MYREGION | jq '.Reservations[]' | jq ".Instances[] | select(.Tags[].Value==\"lab_EC2_instance2_$MYID\") | .InstanceId" | tr -d '"')`
    * Get instance2 ID.
1. `aws elb register-instances-with-load-balancer --load-balancer-name lab-ELB-$MYID --instances $instance1 $instance2 --region $MYREGION`
    * Add the instances to the load balancer.