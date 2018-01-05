### Skip the lab ###
If something didn't work in this lab or you simply don't have enough time you can execute these commands to quickly go to the next lab.

If you created the following services already, please remove them first:
    * an instance security group.
    * an instance.
    * a key pair.

1. You should be logged in to the Management Instance.
1. `export MYID=<your_ID> && export MYREGION=eu-west-1`
    * Set environment variables.
1. `ssh-keygen -t rsa -f ~/.ssh/id_rsa -q -P ""`
    * Generate a key pair.
1. `aws ec2 import-key-pair --key-name lab_key_$MYID --public-key-material "$(cat ~/.ssh/id_rsa.pub)" --region $MYREGION`
    * Import the key to aws.
1. `aws ec2 create-security-group --group-name lab_SecGroup_EC2_$MYID --description "Security Group for EC2 Instances" --region $MYREGION`
    * Create a security group.
1. `aws ec2 authorize-security-group-ingress --group-name lab_SecGroup_EC2_$MYID --protocol tcp --port 22 --cidr 0.0.0.0/0 --region $MYREGION && aws ec2 authorize-security-group-ingress --group-name lab_SecGroup_EC2_$MYID --protocol tcp --port 80 --cidr 0.0.0.0/0 --region $MYREGION`
    * Add port 22 and 80 as ingress to the Security Group.
1. `aws ec2 run-instances --instance-type t2.micro --image-id ami-8fd760f6 --tag-specifications "ResourceType=instance,Tags=[{Key=Name,Value=lab_EC2_instance1_$MYID}]" --iam-instance-profile Name=lab_InstanceAccess --security-groups lab_SecGroup_EC2_$MYID --key-name lab_key_$MYID --region $MYREGION`
    * Create the instance.
1. `aws ec2 describe-instances --filters "Name=tag:Name,Values=lab_EC2_instance1_$MYID" --region $MYREGION | jq '.Reservations[0].Instances[0].PublicIpAddress'`
    * Get the IP-address of your instance.
1. `ssh -i ~/.ssh/id_rsa ubuntu@<ec2_public_ip_address>`
    * Log in to the instance with private key and IP-address.