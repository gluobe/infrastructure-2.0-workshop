# **LAB 9 - Chaos Engineering** #

## Visual Interpretation ##
The streaming giant Netflix is widely known to be an implementor of public cloud infrastructure and innovator in infrastructure reliability software. They created the [Chaos Monkey](https://github.com/Netflix/chaosmonkey/) project for example. 

>Chaos Monkey randomly terminates virtual machine instances and containers that run inside of your production environment. Exposing engineers to failures more frequently incentivizes them to build resilient services.

Yes, you read that right: **production**. One of the ways to know your infrastructure is reliable is to regularly destroy key services in a controlled and monitored environment to see if things regenerate and run properly. 

![](../Images/ChaosEngineeringVisualised.png?raw=true)

### 1. Remove an EC2 Instance in an Autoscaling Group ###
If you have some time left, we can have some fun with the instances.

* Try deleting one of the EC2 Instance(s) created by the Cloudformation Stack and managed by the Autoscaling Group.
    * The only real services we're heavily invested in are the EC2 Instances, having them fail is no problem since they'll get recreated if the ASG notices they're gone.
    * The other services (DynamoDB, ELB, S3, Route53...) are almost completely managed by AWS, which means they'll probably never fail. The chance of a service failing is defined in [AWS SLA (service level agreements)](https://aws.amazon.com/legal/service-level-agreements/) and on each service info page. AWS S3 for example [is designed for 99.999999999% durability and 99.99% availability of objects in its buckets](https://aws.amazon.com/s3/storage-classes/?nc=sn&loc=3).

### 2. Deleting the Cloudformation stack ###
Normally deleting a Cloudformation Stack is as simple as setting it up, but our bucket still needs to be emptied manually sadly.

1. Go to `Services -> S3`.
1. Click the list item but do not open your bucket `lab-cf-images-bkt-<your_ID>`.
1. Above the table click `Empty Bucket`.
    1. Type `permanently delete`.
    1. Press `Confirm`.

Now for deleting the Cloudformation Stack.

1. Go to `Services -> Cloudformation`.
1. Select your stack `lab-cf-<your_ID>`.
1. Press `Actions`, then `Delete Stack` and confirm with `Yes, Delete`.
    * If the stack deletion fails, try again but do not check any boxes that could appear on this step, as doing so will skip the deletion of services. We don't want any stragglers, only total deletion.
    

Run this command on the management instance to update your score: `sudo checkscore`.  

![](https://tinyurl.com/y78fzwla)

...And that's it! We really hope you enjoyed this workshop and learned something new!