<h1 align="center"> 📊 Tech Infrastructure </h1> 

<h3> 🔓 Deployment Guide </h3> 
<br> This guide can be used for a fresh DPGA web app server in the production stage.

<br><b>Step 1 </b> - Create an account AWS / Google cloud / Digital ocean. 
<br><b>Step 2</b> - Create an Ubuntu 22.04 instance.
<br><b>Step 3 </b>- Now access the ubuntu terminal through SSH keys OR the web terminal.
<br><br><b>Step 4</b> - Now we need to install Apache, PHP, MySql, and PHPMyAdmin. Run the below commands in the ubuntu terminal.

<br>sudo apt-get update
<br>sudo apt-get install zip unzip
<br>sudo apt-get install apache2
<br>sudo apt install php libapache2-mod-php
<br>sudo systemctl restart apache2
<br>sudo apt install mysql-server
<br>sudo apt install php-mysql php-gd
<br>sudo apt-get install phpmyadmin

<br><b>Step 5 </b>- Now we create a database user. Go to MySQL section and run the below command

<br>CREATE USER '<DPGAUSER>'@'localhost' IDENTIFIED BY '<DPGADBUSERPASSWORD>';
<br>GRANT ALL PRIVILEGES ON * . * TO '<DPGAUSER>'@'localhost';
<br>FLUSH PRIVILEGES;
<br>
<br><b>Step 6</b> - As of now PHPMyAdmin is installed on the server but we are unable to access it through the browser.
<br>  <br>Use the below commands to access PHPMyAdmin in the browser:
<br>sudo ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf
<br>sudo a2enconf phpmyadmin.conf
<br>sudo systemctl restart apache2

<br>Now try to access PHPMyAdmin in browser via following <a href="http://<SERVERIP>/phpmyadmin">URL</a>


<br><b>Step 7 </b>- Now PHPMyAdmin is accessible through the browser. Login with mysql user details which you created in step 5.

<br><b>Step 8 </b>- Now create a database in PHPMyAdmin dashboard and import the sample database file(webapp_database.sql) which is available in the dpga_webapp.zip folder(this folder is available in the source code). 

<br><b>Step 9 </b>- Now we move our files to webdirectory, unzip our code files to html folder.
sudo unzip dpga_webapp.zip /var/www/html/

<br><b>Step 10 </b>- Now we need to edit a few details(host details, database details, email details) in our code files so the web app runs smoothly.

  <br><b>Step 11 </b>- Now all things are completed. Webapp should be running and accessible through server IP. Now we need to map a domain name to this server. So this webapp is also accessible through a domain name. 
Open DNS settings and add an A record that points out to our server IP. Now wait for 20-30 minutes and after you see that domain hitting our IP address.

<br><b>Step 12 </b>- Now we install the SSL certificate. Here we are installing Let’s Encrypt free certificate which needs to renew every 90 days (renewal can be automated through a cron).
<br> <br>For SSL installation, follow <a href="https://certbot.eff.org/instructions?ws=apache&os=ubuntufocal"> this guide </a>



<br><b>Step 13 </b>- For the smooth working of all edge cases in the web app we need to run several cron at a specific time. Login as admin and in the menu click on the cron details tab. Here you get an all crons list with time. Set all these crons so web app works as expected.
<br><br>For cron setup we can follow <a href="https://www.digitalocean.com/community/tutorials/how-to-use-cron-to-automate-tasks-ubuntu-1804"> this guide </a>
<br>
<br>
  <h3> 💻  Technical Infrastruture (Production)</h3>
<br>

Currently, we use AWS EC2 instance as production server to host the webapp.

Webapp runs on an Ubuntu 22.04 machine with aaPanel for simplifying the hosting on Ubuntu and running commands via a GUI.

Through this GUI Panel, you can easily manage all files, databases, crons, SSL certificates, server details, PHP versions, etc.

Other utilities installed are Apache, PHP, MySql, and PHPMyAdmin

Backend is written via in Codeigniter 3 framework in PHP language.

MySQL used for database

Frontend is developed using the Bootstrap 5 framework.

REST APIs are used to pull data from the database.

Domain and IP Address: app.digitalpublicgoods.net pointing to 15.222.197.93 Using A record on Google Domains

SSL using Let’s Encrypt

Refer comments in the code for explanation of different functions, etc. Variable names are made simple and to the point.

