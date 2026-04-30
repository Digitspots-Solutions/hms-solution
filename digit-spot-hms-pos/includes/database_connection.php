<?php
/* file connecting to database */

require_once ("connection_string.php");

if(DB_SERVER !='' && DB_USER !='' && DB_NAME !='')
{
	$fp_con = new mysqli(DB_SERVER, DB_USER, DB_PASS);
	$select_db = mysqli_select_db($fp_con,DB_NAME);
	
	if(!$select_db)
	{
		mysqli_query($fp_con,"CREATE DATABASE ".DB_NAME) or die(mysqli_error());
	}
	
	$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
}
else
{
	die('No connection string found..');
	exit;
}

?>