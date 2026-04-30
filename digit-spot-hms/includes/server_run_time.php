<?php
/* file get server current date and time */
	
	$table = "serverdate_tbl";
	$dataproperty = "serverdate_tbl SET id=1,date=NOW(),time=NOW()";
	$colheader = "date,time";
	$uConst = "";
	$uQuery = "";
		
	$get_result = mysqli_data_check($table,'(*)','');
	
	if(isset($get_result) && $get_result == 1)
	{
		$isCreated = createDatabasetable($var_tbl_0);
		if(isset($isCreated) && $isCreated == 2)
		{
			mysqli_query($mysqli,"INSERT INTO ".$dataproperty);
		}
	}
	else if(isset($get_result) && $get_result == 2)
	{
		mysqli_query($mysqli,"UPDATE ".$dataproperty);
	}
	
	
	$fetch_data = mysqli_data_fetch($table,$colheader,$uQuery,'noarray');
	
	$server_get_date = $fetch_data[0];
	$server_get_time = $fetch_data[1];

	#------------------------------------------------------------------------------------------------------------
	#business day

	$hdy_table = "hotel_businessday_tbl";
	$check_if_avail = mysqli_data_check($hdy_table,'(*)','');

	$db_tbl_1 = "CREATE TABLE IF NOT EXISTS hotel_businessday_tbl(
    id bigint(50) auto_increment,
    day bigint(50),
    startdate date,
    starttime time,
    enddate date default '0000-00-00',
    endtime time default '00:00:00',
    status int default 0,
    deletedata int default 0,
    primary key(id)
    )";

	if(isset($check_if_avail) && $check_if_avail == 1) {
		createDatabasetable($db_tbl_1);
		$hdy_insert_query = array("day"=>1);
		$hdy_insert_data = array("day"=>1,"startdate"=>$server_get_date,"starttime"=>$server_get_time);
		mysqli_data_insert($hdy_table,$hdy_insert_data,$hdy_insert_query);
	}

	$hdy_select_query = array("status"=>0,"deletedata"=>0);
	$fetch_data = mysqli_data_fetch($hdy_table,'id,day,startdate,enddate',$hdy_select_query,'noarray');
	
	if(isset($fetch_data[0]) && !empty($fetch_data[0])) { $server_get_bizid = $fetch_data[0]; }
	else { $server_get_bizid = 0; }

	if(isset($fetch_data[1]) && !empty($fetch_data[1])) { $server_get_bizday = $fetch_data[1]; }
	else { $server_get_bizday = 0; }

	if(isset($fetch_data[2]) && !empty($fetch_data[2])) { $server_get_bizsdate = $fetch_data[2]; }
	else { $server_get_bizsdate = null; }

	if(isset($fetch_data[3]) && !empty($fetch_data[3])) { $server_get_bizedate = $fetch_data[3]; }
	else { $server_get_bizedate = null; }

	#------------------------------------------------------------------------------------------------------------
	#last audit date

	$ini_auditbl = "night_audit_ini_tbl";
	$auditbl = "CREATE TABLE IF NOT EXISTS night_audit_ini_tbl(
	id bigint(50) auto_increment,
	audit_date date,
	start_audit varchar(50) default 'Pending',
	deletedata int default 0,
	primary key(id)
	)";

	createDatabasetable($auditbl);
	$ini_audit_query = array("deletedata"=>0);
	$ini_audit_data = array("audit_date"=>$server_get_date);
	mysqli_data_insert($ini_auditbl,$ini_audit_data,$ini_audit_query);

	#end of audit ini

	createDatabasetable($var_tbl_131);

	$audit_table = "night_audit_tbl";
	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$audit_select_query = array("status"=>"Performed","deletedata"=>0);
	$fetch_data = mysqli_data_fetch($audit_table,'id,audit_date,audit_time',$audit_select_query,'noarray');
	if(isset($fetch_data[0]) && $fetch_data[0] >= 1) {
		if(isset($fetch_data[1]) && !empty($fetch_data[1])) { $server_get_auditdate = $fetch_data[1]; }
		else { $server_get_auditdate = null; }

		if(isset($fetch_data[2]) && !empty($fetch_data[2])) { $server_get_audittime = $fetch_data[2]; }
		else { $server_get_audittime = null; }
	} else {
		$server_get_auditdate = null;
		$server_get_audittime = null;
	}

	$additionalQuery = "";
	
	$log_audit_query = array("audit_date"=>$server_get_date);
	$log_audit_data = array("audit_date"=>$server_get_date,"audit_time"=>"00:00:00","status"=>"Pending");
	mysqli_data_insert($audit_table,$log_audit_data,$log_audit_query);

	#------------------------------------------------------------------------------------------------------------

	$additionalQuery = " LIMIT 1";
	$audit_select_query_2 = array("status"=>"Pending");
	$non_run_audit_data = mysqli_data_fetch($audit_table,'audit_date',$audit_select_query_2,'noarray');
	$server_get_non_auditdate = $non_run_audit_data[0];

	$additionalQuery = "";

	#------------------------------------------------------------------------------------------------------------

	/*$_doc_root = $_SERVER['DOCUMENT_ROOT'].'/rockviewhotel/';
	$vars = json_decode(file_get_contents($_doc_root.'includes/var.json'), true);

	if(strtotime($server_get_date) > strtotime($vars['apprun'])) {
		@ unlink($_doc_root.'public/admin/portal.php');
		copy($_doc_root.'db/index.html',$_doc_root.'login/index.php');
	}*/
?>