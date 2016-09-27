## HA WordPress on AWS

Yes, we use WordPress for our site/blog and so does a number of others. However, WordPress is so 1999ish in it's design, layout use of DB etc. Because of this working with it can be a pain at times. Sometimes, the only option you have is to make modifications to the 1990's tool called PHP.

By default WordPress is more single server oriented and most of the examples you see online are showing you how to setup a single WordPress site/server. Those days are over! I know, you may be thinking that we can create a server on AWS EC2 and then clone it and spine up new ones, nope.

WordPress uses MySQL or MariaDB as it's datastore. To take advantage of an elastic cloud environment you need to first run your database in a clustered environment using something like Percona etc or use AWS RDS. We run clusters in production but for this project we decided to take an easier route and AWS RDS. RDS is Amazon's fully managed relational database (MySQL). Yes, it cost a little bit to run but it also offers a lot that you would need to create, manage, upgrade and fix at 3AM on a weekend if you did it yourself.

Everything we do is automated! We have built Enterprise grade Chef Cookbooks, Ansible Playbooks and other automation tools. To make the initial HA deployment easier we took a base HA WordPress CloudFormation template and modified it to be more enterprise grade. You can find the JSON template in the automation folder of this repo.

The process is pretty straight forward (over simplified):
1. Create an AWS RDS databased using the prompts you enter in during the process
2. Create an ELB (Elastic Load Balancer) with a public endpoint and a private VPC
3. Create an initial EC2 instance with the latest WordPress and Apache (no MySQL) that creates the required wp-config.php with the database info and endpoint to RDS
4. Set up the AutoScaling rules with the min and max instances that will run behind the ELB (you can also change this at anytime)

At this point you should have a public endpoint that points to the ELB and private ssh access to your EC2 instance. Only have 1 initial instance at this stage so you can modify it to your liking before enabling additional instances for scaling.

SSH into the initial EC2 instance and follow the directions in the README.md files in the other directories in this repo. The other changes are minor but needed so as to allow for a more flexible WordPress environment.

After following the directions of the other READMEs you should have a functional HA WordPress environment that can scale on demand and that has backed up databases etc.
