<?php

	#Get server current date and time
		
	$var_server_date_time_tbl = "CREATE TABLE IF NOT EXISTS xserverdate_tbl(id int,srvdate date,srvtime time)";
	createDatabasetable($var_server_date_time_tbl);

	$table = "xserverdate_tbl";
	$dataset = "id=1,srvdate=NOW(),srvtime=NOW()";
	$queryset = "id=1";

	$wgtf = mysqli_data_exist($table,$queryset);

	if(isset($wgtf['isdata']) && $wgtf['isdata'] == true) {
		mysqli_data_update($table,$dataset,$queryset);
	} else {
		mysqli_data_insert($table,$dataset,$queryset);
	}

	$sql = "SELECT * FROM {$table} WHERE id=1";
	$fetch = idget_data($sql);

	$server_get_date = $fetch[0]['srvdate'];
	$server_get_time = $fetch[0]['srvtime'];

	$server_get_day = date('d',strtotime($server_get_date));
	$server_get_month = date('m',strtotime($server_get_date));
	$server_get_year = date('Y',strtotime($server_get_date));

	$server_get_fday = date('Y-m-d',strtotime('-'.($server_get_day - 1).' days'));
	$fdayinmonth = date('M. jS',strtotime('-'.($server_get_day - 1).' days'));
	$cdayinmonth = date('M. jS',strtotime($server_get_date));

?>