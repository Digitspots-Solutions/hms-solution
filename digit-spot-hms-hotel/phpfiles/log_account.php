<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; ?>
<?php
	
	if(isset($_POST['dataSend']) && $_POST['dataSend'] == 400)
	{
		//password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
		//password_verify('rasmuslerdorf', $hash);
		
		$datapropertyadmin = array("staffname"=>'administrator',"username"=>"hotelmaster","emailaddress"=>"hotelmaster","password"=>sha1('master001'));
		$constrainadmin = array("emailaddress"=>"hotelmaster");
		
		$get_result = mysqli_data_check($tbL1,'(*)','');
		
		if(isset($get_result) && $get_result == 1)
		{
			$isCreated = createDatabasetable($var_tbl_1);
			if(isset($isCreated) && $isCreated == 2)
			{
				createDatabasetable($var_tbl_2); //create log table

				$dataInserted = mysqli_data_insert($tbL1,$datapropertyadmin,$constrainadmin);
			}
		}
		
		$formfield1 = escape_data($_POST['formfield1']);
		$formfield2 = escape_data($_POST['formfield2']);
		
		$allowpage = '';

		if(isset($formfield1) && $formfield1 == 'hotelmaster')
		{
			$table = $tbL1;
			$dataproperty = array("emailaddress"=>$formfield1,"password"=>sha1($formfield2),"status"=>"Active");
			$allowpage = "yes";
		}
		else
		{
			$table = $tbL7;
			$dataproperty = array("username"=>$formfield1,"password"=>sha1($formfield2),"status"=>"Active");
			$allowpage = "verify";
		}
	
		$isdata = mysqli_log_authen($table,$dataproperty);		
		
		if(isset($isdata) && $isdata == 2) {
			if($allowpage == 'yes') {
				
				$userSignedIn = $userSigned;

				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"message"=>"Log in to the application platform","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$message = DOMAIN_URL.'public/var/portal'.PHP_EXT;
			} elseif($allowpage == 'verify') {
				$message = DOMAIN_URL.'login/verify'.PHP_EXT;
			}
		} else {
			$message = 'undefined';
		}
		
		echo $message;
	}
	
?>