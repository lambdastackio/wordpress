### Modified httpd.conf
We use a yum based version of Linux similar to CentOS (Amazon AMI) so Apache is called httpd instead of apache as is the case with Ubuntu.

We have modified this file which you should override the existing one on your base instance. The modifications allow for larger uploads, and SEO friendly permalinks. Make sure you restart Apache after copying this file over the existing one:

>sudo service httpd restart
