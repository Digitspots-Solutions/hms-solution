<?php

$pst_message = ""; $message = "";
$formfield1 = ""; $formfield2 = "";

$noshow = "pads5 light-red-font ft-sml-size";

if(isset($_POST['log']) && !empty($_POST['fieldset1']) && !empty($_POST['fieldset2'])) {
	
	$noshow = "pads5 bottom-push-3 light-red-font ft-sml-size";

	$formfield1 = escape_data($_POST['fieldset1']);
	$formfield2 = escape_data($_POST['fieldset2']);
	
	$allowpage = '';

	if(isset($formfield1) && $formfield1 != 'hms') {
		$table = "user_admin_tbl";
		$passHash = sha1($formfield2);
		$dataproperty = " WHERE username='{$formfield1}' AND password='{$passHash}' AND status='Active'";
		$allowpage = "verify";
	}

	$sqli = "SELECT * FROM ".$table.$dataproperty;
	$result = mysqli_query($mysqli,$sqli);
	
	if(@ mysqli_num_rows($result) == 1) {
		
		$display = @ mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		$_SESSION['data_sid'] = 200;
		$_SESSION['page_sid'] = "session_active_page";
		$_SESSION['authenticate_id'] = $display['id'];
		
		$isdata = 2;
	} else {
		$isdata = 1;
	}	

	if(isset($isdata) && $isdata == 2) {
		
		$noshow = "pads5 bottom-push-5 black-font ft-sml-size nunito-regular alignct";
		$pst_message = "Loading platform, please wait..";
		
		if($allowpage == 'yes') {
			
			$userSignedIn = $_SESSION['authenticate_id'];

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"message"=>"Log in to the application platform","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert("user_log_tbl",$log_datasets,'');

			$message = DOMAIN_URL.'public/var/portal'.PHP_EXT;

		} elseif($allowpage == 'verify') {
			$message = DOMAIN_URL.'login/verify'.PHP_EXT;
		} else {
			$message = "";
		}

	} else {
		$noshow = "pads5 bottom-push-3 light-red-font ft-sml-size nunito-regular alignct";
		$pst_message = "Invalid username or password";
		$message = "";
	}
}

?>