# **LAB 6 - Route 53** #

## Start of Lab 6 ##
You should have two Instances, both connected to a Load Balancer, DynamoDB and S3.

## Visual Interpretation ##
Using the randomly generated ELB URL isn't too user friendly. That's why the AWS Route53 service exists.

![](../Images/Lab6.png?raw=true)

### 1. Creating a new DNS record via Route53 ###

1. Copy your Load Balancer's DNS record (in `Services -> EC2 -> Load Balancers`).
1. Go to `Services -> Route 53` under `Networking and Content Delivery`.
1. Under `DNS Management`, click `Hosted zones`.
1. Click on `gluo.cloud.`.
1. Click `Create Record Set`
    * Set `Name:` to `<your_ID>`.
    * Set `Type:` to `CNAME - Canonical name`.
    * Set `Value:` to `<Load Balancer DNS url>`.
        * This cannot contain `http://` or trailing slashes like `/` at the end.
1. Click `Create`.

The record can take up to 2-5 minutes to be registered by the top DNS servers. You can go to `<your_ID>.gluo.io` to view your Load Balanced MemeGen site.

![](../Images/Route53BrowseToLoadBalancer.png?raw=true)

## End of Lab 6 ##
Once you can reach both your instances via the Route53 records through the Load Balancer, you may continue to the next lab.

### More info ###

* What is Route53? (https://aws.amazon.com/route53/).
* The Route53 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/route53/index.html).
