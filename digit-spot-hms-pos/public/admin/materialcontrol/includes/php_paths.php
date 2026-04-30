<?php
#This program is written and packaged by WebProx Technologies
#Copyright 2021. No license required
#Feel free to copy and re-use

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//error_reporting(0);

//for file paths
define ("B4WF_PATH","../../../../");
define ("B3WF_PATH","../../../");
define ("B2WF_PATH","../../");
define ("BWF_PATH","../");
define ("BF_PATH","./");
define ("CONFIG_FILES","includes/");
define ("PUB_FILES","public/");
define ("AJAX_FILES","cphp/");
define ("_IMAGE_", "theme/images/");

$sub_images_folder = array(
	"inc",
	"general"
);

$_files_ = array(
	"connection_string.php",
	"database_connection.php",
	"functions.php",
	"rqu_functions.php",
	"tables.php",
	"common_data.php",
	"gwt.php",
	"server_run_time.php"
);

//for apache run
define ("PUBLIC_HTML","public/"); //public/
define ("PHP_EXT",".php"); //.php
define ("HTM_EXT",".html"); //.html

//for application url
define ("MAIN_DOMAIN_URL","http://127.0.0.1/digit-spot-hms/");
define ("DOMAIN_URL","http://127.0.0.1/digit-spot-hms-pos/");

//sms gateway parameters
define ("_SHORT_NAME","HMS");
define ("_LOGO_URL","http://127.0.0.1/digit-spot-hms-pos/theme/images/inc/logo.png");
define ("_NOREPLY_EMAIL","noreply@digitspots.com");
define ("_STATE","Nigeria");
define ("_CITY","Lagos");

//cpanel login details
//userid: 
//password: 

//email account
//user: 
//pwd: 

//chat login
//user: 
//pwd:

?>