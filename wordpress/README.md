# IMPORTANT
Included in this repo are several themes. However, some of those themes require a license to use such as repute-wp. So, to use that theme you will need to go purchase the license and full source at https://www.themeineed.com/.

We have customized the theme to our needs and it will not work properly for yours so please purchase the license from them if you decide to use it (it's cheap :)).

Also, the wp-config.php is not present! This file contains passwords etc from the given WordPress install. When you run the AWS CloudFormation automation template located in the automation subdirectory it will create it for you by default based on your answers to critical questions.

After your HA version of WordPress is up and running (takes a bit for the automation to complete) then you can copy the content of this wordpress directory over the default one created by CloudFormation. Since the wp-config.php is not present here it will not override your newly created version.

Also, one thing the automation does not do is change the owner:group of the wordpress directory. This should be changed to apache:apache: (assumes you're in /var/www/html directory)

>sudo chown -R apache:apache wordpress  

Restart apache after that with: sudo service httpd restart

We have also included the plugins we use as well. We use MailChimp for newsletter since it's free for Open Source projects so you will need to create an account at mailchimp.com and then create an API key. The plugin will guide you through the process.

So, required keys for this version of our wordpress with our plugins:

1. MailChimp API key (create account there)
2. Google Analytics (create google analytics account/property and get ID)
3. Google AdSense (create google adsense account, link it to google analytics and get ID)
4. Twitter account (you will need to create an integration link which give you keys and tokens. Plugin guides you)
5. Slack account (create an integration webhook. Plugin guides you)

The items above are actually simple and don't take long but if you have never done it before then you will want to read each plugin's instructions. You can always elect not to enable a given plugin.

#### WP-ADMIN
DO NOT change your WordPress Address (URL) nor your Site Address (URL) in Settings/General *until* after you have your DNS configured to point to the right endpoint via CNAME. If you do then you may not be able to get back into your admin screen and you will have to modify the DB directly.

#### Database
WordPress is very 1999ish. Yes, a lot of people, including us, use it but it's still not very HA oriented and requires a good bit of knowledge of distributed computing to make it work correctly. One of the main reasons for this is the MySQL or MariaDB that it uses. After running MySQL in clustered environments in production we have found it easier to allow AWS to manage that via Amazon RDS (Managed MySQL). The CloudFormation template sets this up automatically for during the install process. Yes, it cost a little bit but the good thing is you have really good visibility into your database, guaranteed backups and uptime which you would have to manage yourself if ran WP without it. For example, to do the same thing you would need to run a Percona cluster that manages leader election etc and then have each EC2 instance connected to the common DB endpoint. This DB cluster would also be running on EC2 instances. Why? great question. Under NO circumstance would you want a local MySQL DB for WordPress because there are too many things that can go wrong so you need a cluster so that you can dynamically scale up and down based on demand. AWS RDS allows you to check that burden off you're list unless you want to be in that business.

#### DNS
By default the AWS CloudFormation template will build out an ELB (Elastic Load Balancer) and assign a public side and a private side via VPC. The VPC will protect your instances from being accessed from the outside world except via ssh. You also be assigned a DNS endpoint to the ELB and NOT an IP. You can also not associate an Elastic IP to an ELB.

Once you have your WP site working the way you want it then you can modify your DNS. If you use AWS Route 53 to manage your DNS then follow their instructions. If you use something like Google Domains then set up a CNAME for www to the DNS name of the ELB. To map a naked domain (i.e., lambdastack.io) to your newly created CNAME then follow the instructions of your DNS provider.

You will then need to modify the two entries mentioned above in the WP-ADMIN section to point to your domain name instead of the funky looking endpoint of the ELB.
