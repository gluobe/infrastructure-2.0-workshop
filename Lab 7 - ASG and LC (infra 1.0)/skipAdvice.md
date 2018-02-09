### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

If you created the following services already, please remove them first:
    * an autoscaling group.
    * a launch configuration. 
    
1. Delete your 2 EC2 instances.
1. You should be logged in to your Management Instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `git clone https://github.com/gluobe/memegen-webapp.git ~/memegen-webapp`
    * Git clone the repository for the launch configuration InstallMemeGen-php-LC.sh script.
1. `sed -i "s@^YOURID=.*@YOURID=\"$MYID\"; (Altered by sed)@g" ~/memegen-webapp/scripts/InstallMemeGen-php-LC.sh`
    * Change the **id** of the script.
1. `export secGroupId=$(aws ec2 describe-security-groups --region $MYREGION | jq ".SecurityGroups[] | select(.GroupName==\"lab_SecGroup_EC2_$MYID\") | .GroupId" | head -n 1 | tr -d '"')`
    * Get the EC2 security group.
1. `aws autoscaling create-launch-configuration --launch-configuration-name lab-LC-$MYID --instance-type t2.micro --image-id ami-8fd760f6 --iam-instance-profile lab_InstanceAccess --security-groups $secGroupId --key-name lab_key_$MYID --region $MYREGION --associate-public-ip-address --user-data file:///root/memegen-webapp/scripts/InstallMemeGen-php-LC.sh`
    * Create a launch configuration with the script.
1. `export subnetIds=$(aws ec2 describe-subnets --region $MYREGION | jq '.Subnets[] | select(.DefaultForAz==true) | .SubnetId' | tr -d '"')`
    * Get subnet id's of default VPC.
1. `export subnetIdsCommas=$(echo $subnetIds |  sed 's/ /,/g')`
    * Seperate the ids variable with comma's instead of spaces.
1. `aws autoscaling create-auto-scaling-group --auto-scaling-group-name lab-ASG-$MYID --min-size 1 --max-size 2 --desired-capacity 2 --launch-configuration-name lab-LC-$MYID --load-balancer-names lab-ELB-$MYID --vpc-zone-identifier=$subnetIdsCommas --region $MYREGION --tags "ResourceType=auto-scaling-group,Key=Name,Value=lab-ASG-$MYID,PropagateAtLaunch=true"`  
    * Create an autoscaling group.
1. Wait for the instances to start up. 
1. Go to `<your_ID>.gluo.cloud`.