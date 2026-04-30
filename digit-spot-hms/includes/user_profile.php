<?php
	
	//user profile

	$user_dataproperty = "staffname,username,role,department,uaccess,primarycontact,emailaddress,gender,mobile";
	$user_queryproperty = array("id"=>$_SESSION['authenticate_id']);
	$user_admin_data = mysqli_data_fetch($tbL7,$user_dataproperty,$user_queryproperty,'noarray');

	$myrole = $user_admin_data[2];
	$admin_name = ucwords($user_admin_data[0]);
	$user_login = $user_admin_data[1];
	$myaccess = $user_admin_data[4];
	$myprimarycontact = $user_admin_data[5];
	$emailaddress = $user_admin_data[6];
	$gender = $user_admin_data[7];
	$mobile = $user_admin_data[8];

	#--------------------------------------------------------------------------------------------------

	//get shift time & counter

	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$usr_shift_query = array("userid"=>$_SESSION['authenticate_id'],"status"=>"Open");
	$get_usr_shift_data = mysqli_data_fetch($tbL23,'shiftid,counterid',$usr_shift_query,'noarray');
	
	if(isset($get_usr_shift_data[0]) && $get_usr_shift_data[0] >= 1) { $current_shift = $get_usr_shift_data[0]; }
	else { $current_shift = 0; }

	if(isset($get_usr_shift_data[1]) && $get_usr_shift_data[1] >= 1) { $current_counter = $get_usr_shift_data[1]; }
	else { $current_counter = 0; }

	$additionalQuery = "";
	$myshift = array("id"=>$current_shift,"status"=>"Active");
	$myshift_data = mysqli_data_fetch($tbL20,'shiftname,startimelabel,endtimelabel',$myshift,'noarray');
	$print_shift = $myshift_data[0].' ('.$myshift_data[1].' - '.$myshift_data[2].')';

	$mycounter = array("id"=>$current_counter,"status"=>"Active");
	$mycounter_data = mysqli_data_fetch($tbL19,'countername,countertype',$mycounter,'noarray');
	$print_counter = $mycounter_data[0];

	#--------------------------------------------------------------------------------------------------

	//get last business date

	$additionalQuery = " AND datelogged NOT IN('".$server_get_date."') ORDER BY id DESC LIMIT 1";
	$usr_lastlong_query = array("userid"=>$_SESSION['authenticate_id'],"logcategory"=>"accessibility");
	$get_usr_lastlong_data = mysqli_data_fetch($tbL8,'datelogged',$usr_lastlong_query,'noarray');
	$print_lastlong = date("d/m/Y",strtotime($get_usr_lastlong_data[0]));


	#--------------------------------------------------------------------------------------------------

	$additionalQuery = "";

	$mycounter_query = array("userid"=>$_SESSION['authenticate_id'],"counterid"=>$current_counter,"logstatus"=>"Open");
	$mycounter_data = mysqli_data_fetch($tbL22,'id',$mycounter_query,'noarray');
	if($mycounter_data[0] >= 1) { $ths_mycounter = $mycounter_data[0]; }
	else { $ths_mycounter = 0; }

?>