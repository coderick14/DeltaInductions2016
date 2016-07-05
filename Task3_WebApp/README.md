###Web App

This project involves making a simple web app where users can login or signup to share their posts. Thus it consists of an authentication and authorization system. Each user can edit his/her details at any point of time. They can also remove any of their posts, if required. Every user can view the profile of any other user using the search box which uses AJAX Live Search.

----

**Framework used : PHP on Apache**  
**Database 	 : MySQL**  
**Server	 : Apache2**  

Below are the links for downloading all the necessary software required to run the scripts :

####For Windows
+ Install Apache. [Click here](https://www.sitepoint.com/how-to-install-apache-on-windows/) to install. It contains all the links and a step by step guide about the installation.
+ Install php5. [This link](https://www.sitepoint.com/how-to-install-php-on-windows/) provides a step by step method on how to install and configure php5 on your system.
+ Install MySQL. [This link](https://www.sitepoint.com/how-to-install-mysql/) provides a step by step method for doing this

####For Linux
+ Install Apache. Open your terminal. Type **sudo apt-get install apache2**. Start your server with **sudo /etc/init.d/apache2 start**.
+ Install php5. Type **sudo apt-get install php5 libapache2-mod-php5** and **sudo apt-get install php5-mysql**. Restart your server with the command **sudo /etc/init.d/apache2 restart**.
+ Install MySQL. Type **sudo apt-get install mysql-server**. 
In case of any trouble, [click here](https://www.linux.com/learn/easy-lamp-server-installation) for a detailed instruction on how to set up a LAMP Server. 

----

The details about the database and the tables used are as follows :
+ Create an user with all grant privileges, say "MyUsername" or you may use any existing user with all grant privileges.
+ In case you created a new user, set up a password, say "MyPassword"
+ Create a database after logging in with the above username and password, say "MyDatabase". You may use any existing database as well(Not recommended).
+ The first table is 'users'. The CREATE TABLE command is given below.  
   CREATE TABLE `users` (  
  `user_id` int(5) NOT NULL AUTO_INCREMENT,  
  `user_name` varchar(20) NOT NULL,  
  `user_pass` varchar(60) NOT NULL,  
  `user_mail` varchar(30) NOT NULL,  
  `user_phone` bigint(20) unsigned NOT NULL,  
  `user_pic` varchar(100) NOT NULL,  
  `user_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (`user_id`),  
  UNIQUE KEY `user_name` (`user_name`),  
  UNIQUE KEY `user_mail` (`user_mail`),  
  UNIQUE KEY `user_phone` (`user_phone`)  
) 
+ The second table is 'posts'. The CREATE TABLE command is given below.  
   CREATE TABLE `posts` (  
  `post_id` int(5) NOT NULL AUTO_INCREMENT,  
  `post_topic` varchar(200) NOT NULL,  
  `post_content` text NOT NULL,  
  `post_by` varchar(30) NOT NULL DEFAULT 'Anonymous',  
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (`post_id`)  
) 

----

**Captcha System**

+ The signup page uses Google reCaptcha to prevent bot users.
+ Go to [this link](https://www.google.com/recaptcha/intro/index.html). Click on **get reCaptcha** button in top right corner.
+ Sign in through your Gmail account.(If you are already signed up, then ignore this step).
+ In the **Register a new site** box, type in a label(say localhost) and your domain name(say localhost). 
+ Click on **Register**.
+ You will get two keys, a public key and a private key.
+ Copy the private key. Open signup.php. In the line
```html
$privatekey = "Your-private-key";
```
replace the string "Your-private-key" with your own secret/private key.
+ Copy the public key. Open signup.php. You will see a line 
```html
<div class="g-recaptcha" data-sitekey="Your-public-key"></div>
```
Paste this public key in the 'data-sitekey' attribute,replacing "Your-public-key".

----

**After you are done with the above steps, make necessary changes to connect.php script**.

The **mysqli** library has been used for connecting to the database.

####How to run the scripts
+ Clone this repository into the folder you want. 
+ Start your apache server.
+ Copy all the files from Task3_WebApp to your localhost directory.(Usually C:/inetpub/wwwroot for Windows and /var/www/html for Linux).
+ Open a new empty directory there. Name it **profilepictures**. Make sure you have write permissions for this directory. This directory will store the profile pictures of all the users.
+ Open up your browser. Type http://localhost/ as the URL.
+ Click on signup.php
