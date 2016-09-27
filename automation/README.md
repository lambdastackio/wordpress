### AWS CloudFormation

Automation is the key to everything today. If you like to build out one machine at a time and hand tune then stop it today! AWS CloudFormation is one of AWS' automation tools that allow for simple initial builds. OpsWork is another which is really Chef and is also good. Of course, you can use Ansible playbooks to orchestrate build outs too.

The initial buildout will build 1 instance. DO NOT specify more than 1 on initial server prompt. It's important to get the initial configs and instance running the way you want it to run before enabling additional instances. You can change these settings in the EC2 area of the AWS Console later.

Also, after you get the initial instance running the way you want it then create a snapshot image and store it in S3 for same keeping just to feel warm and fuzzy.
