# Open the index file to see the short directions to all the listed application repos

+ Development

#HTML, #CSS, #JS, #PHP and #MYSQL

The application is divided into three(3) different applications, though the hotel-management and point-of-sale were developed together initially. The solutions are now in three forms;

1. Hotel Management: This is for internal booking/reservation and recreation. The repo for this is #digit-spot-hms-hotel
2. Point of Sale: This is for hotel outlets, supermarts, event centers, and lounges. The repo for this is #digit-spot-hms-pos
3. Travel Master: This is still in-view. The repo for this is #digit-spot-hms-travel-master

However, there is a repo that interfaces these three applications: #digit-spot-hms. The index file in this repo creates room for you to enable the application you want the client to have access to. So this repo serves as a landing page for the three applications

Hotel Management & Point of Sale share the same database, but their files are independent.


+ Deployment

# On-Cloud
AWS

# On-Prem (Local Web Server)
LOCAL AREA NETWORK
This is a server-client system whereby you have a dedicated system that serves as a server and the client systems are connected on the same network. So the application is deployed on the server, and communication is done using network IPs.

The physical server runs with Apache Server (wamp). Check the repos in the list for apache_wamp_server

The client systems use the Windows Host-file. This could be configured for the server network IP to run the application

Note: The mentioned application repos are deployed on the apache server. So you need to install the attached wamp file

Steps to install the application on the server

1. Look for a repo in the list named as apache_wamp_server
2. Open it and download the wamp file
3. Install it on the server by following the prompt. Ensure you enable automatic start
4. After installation is completed, go to your C: drive and open wamp folder
5. Open www folder
4. Copy/download all the repos here except apache_wamp_server and mysql-information-tables
5. Paste these repos in the www folder
6. To access the software after all processes, open your browser and type http://127.0.0.1/digit-spot-hms/

Import the database
1. Copy/download mysql-information-tables
2. Open your browser and type localhost/phpmyadmin/
3. create a database name hmsdb
4. Click Import button then attach the sql file inside mysql-information-tables
5. Click on submit/go button to initiate import
