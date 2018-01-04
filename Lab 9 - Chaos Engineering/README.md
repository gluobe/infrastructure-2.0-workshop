# **LAB 9 - Chaos Engineering** #

## Visual Interpretation ##
The streaming giant Netflix is widely known to be an innovator in infrastructure, cloud and devops. They created the [Chaos Monkey](https://github.com/Netflix/chaosmonkey/) project. 

>Chaos Monkey randomly terminates virtual machine instances and containers that run inside of your production environment. Exposing engineers to failures more frequently incentivizes them to build resilient services.

Yes, you read that right: **production**. The only true way to know your infrastructure is fool proof is to regularly destroy key services to see if things still regenerate and run properly. 

![](../Images/ChaosEngineeringVisualised.png?raw=true)

### 1. Remove an EC2 Instance in an Autoscaling Group ###
If you have some time left, we can have some fun with the instances.

* Try deleting one of the EC2 Instance(s) created by the Cloudformation Stack and managed by the Autoscaling Group.
    * The only real services we're heavily invested in are the EC2 Instances, having them fail is no problem since they'll get recreated if they don't respond to the ASG.
    * The other services are almost completely managed by AWS, which means if they fail it's AWS' fault and they've made damn sure they don't fail as the market leader in Cloud Computing. 

### 2. Deleting the Cloudformation stack ###
Normally deleting a Cloudformation Stack is as simple as setting it up, but our bucket still needs to be emptied manually sadly.

1. Go to `Services -> S3`.
1. Click the record but do not open your bucket `Cloudformation-<your_ID>-bucket`.
1. Above the table click `Empty Bucket`.
    1. Type the bucket name.
    1. Press `Confirm`.
        * If you get an error, sometimes trying it again solves the issue.

Now for deleting the Cloudformation Stack.

1. Go to `Services -> Cloudformation`.
1. Select your stack `lab-app-<your_ID>`.
1. Press `Actions`, then `Delete Stack` and confirm with `Yes, Delete`.
    * If the stack deletion fails, try again but do not check any boxes that could appear on this step, as doing so will skip the deletion of services. We don't want any stragglers, only total deletion.
    
That's it! Hope you learned something :)