# **Infrastructure 2.0 with AWS** #


### Introduction ###

Amazon Web Services (AWS) is a secure cloud services platform, offering compute power, database storage, content delivery and other functionality to help businesses scale and grow.

This **VERY** hands-on workshop demonstrates the usefulness of Cloud Computing in AWS. We'll start by hosting a simple application manually and in each lab we'll integrate more and more AWS services until eventually we've built a virtually indestructible cloud infrastructure layer on which our application rests comfortably. Everything will be automated, self-healing, stable, reliable and ready for real world testing and use. In the last lab we'll also demonstrate this indestructibility with Chaos Engineering, a term invented and used by the streaming giant Netflix, used to make their infrastructure both reliable and disposable.

In this workshop we'll discover the following AWS services:

* Cloud instances:      EC2 (Elastic Compute Cloud) instances and Security Groups
* Cloud databases:      DynamoDB 
* Cloud load balancers: ELB (Elastic Load balancers)
* Cloud storage:        S3 (Simple Storage Service)
* Cloud DNS:            Route53
* Cloud scaling:        ASG (autoscaling group) and LC (Launch Configuration)
* Cloud scripting:      Cloudformation


### Table of contents ###

* [Lab 0 - Prerequisites](Lab%200%20-%20Prerequisites)
* [Lab 1 - AWS Console & EC2](Lab%201%20-%20AWS%20Console%20and%20EC2)
* [Lab 2 - Manual installation (Infra 0.0)](Lab%202%20-%20Manual%20installation%20(Infra%200.0))
* [Lab 3 - DynamoDB](Lab%203%20-%20DynamoDB)
* [Lab 4 - ELB](Lab%204%20-%20ELB)
* [Lab 5 - S3](Lab%205%20-%20S3)
* [Lab 6 - Route 53](Lab%206%20-%20Route%2053)
* [Lab 7 - ASG and LC (infra 1.0)](Lab%207%20-%20ASG%20and%20LC%20(infra%201.0))
* [Lab 8 - Cloudformation (infra 2.0)](Lab%208%20-%20Cloudformation%20(infra%202.0))
* [Lab 9 - Chaos Engineering](Lab%209%20-%20Chaos%20Engineering)


### Target group ###

This workshop is very much an introduction to Cloud Computing and AWS, meant for beginners and students. If you're already familiar with other Cloud vendors like Microsoft's Azure or Google Cloud, this will also be up your ally but might be too slow and "holding hands" a bit too much.

We do list every command you need to execute, but knowledge and some basic experience with the Linux commandline is required.


### Some notes ###

* You will not be able to start this workshop on your own without a Gluo tutor. We set up an environment in which you can work (creating accounts and initial instances...) using scripting which is not integrated into the public Github repository.

* Permissions are set up in a very strict way. You'll probably get errors when venturing out "too far" with our provided accounts. We do very much encourage making your own cloud account to any Cloud vendor (be it AWS, Azure, GCP...) and trying out their free trials to discover more!

* We're using Amazon's AWS as our cloud and Ubuntu Linux as our operating system in this workshop. We have no real preference and we're actively working on creating more workshops for different vendors and technologies. 


### Contact ###

If you liked what did or want to like what we do, please do contact us! Revelant links can always be found at http://www.gluo.be.


