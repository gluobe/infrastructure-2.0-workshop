# **LAB 6 - Route 53** #

## Start of Lab 6 ##
You should have two Instances, both connected to a Load Balancer, DynamoDB and S3.

## Visual Interpretation ##
Using the randomly generated ELB URL isn't too user friendly. That's why the AWS Route53 service exists.

![](../Images/Lab6.png?raw=true)

### 1. Creating a new DNS record via Route53 ###

1. Copy your Load Balancer's DNS record (in `Services -> EC2 -> Load Balancers`).
1. Go to `Services -> Route 53` under `Networking and Content Delivery`.
    * You might see some permission errors here. Ignore them.
1. Under `DNS Management`, click `Hosted zones`.
1. Click on `gluo.cloud`.
1. Click `Create Record`
1. We'll use `Simple routing`, press `Next`. If you want you can read what the different options are.
1. Click `Define simple record`
    * Set `Record name:` to `<your_ID>`. (Example: 1.gluo.cloud)
    * Set the `Value/Route traffic to` dropdown to `IP address or another value...`
        * Then fill in the text box with the `<Load Balancer URL>` you copied above.
        * This cannot contain `http://` or trailing slashes like `/` at the end.
    * Set `Type:` to `CNAME - Canonical name`.
    * Click `Define simple record`.
1. Click `Create records`.

* Go to `<your_ID>.gluo.cloud` to view your Load Balanced MemeGen site.
    * The record can take up to 5 minutes to be registered by the top DNS servers. 
    * If you want you can view the dns record value via the commandline using `watch dig <your_ID>.gluo.cloud`, `ctrl+c` to cancel.
    
![](../Images/Route53BrowseToLoadBalancer.png?raw=true)

## End of Lab 6 ##
Congratulations! You've successfully added a DNS record via Route53 to reach your website.

Run this command on the management instance to update your score: `sudo checkscore`.

Once you can reach both your instances via the Route53 records through the Load Balancer, you may continue to the [next lab](../Lab%207%20-%20ASG%20and%20LC%20(infra%201.0)).

### More info ###

* What is Route53? (https://aws.amazon.com/route53/).
* The Route53 specific AWSCLI documentation (http://docs.aws.amazon.com/cli/latest/reference/route53/index.html).
