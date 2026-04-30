<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._FUNC_;
include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_;

$userSignedIn = USER_AUTHEN_ID;

if(isset($myaccess) && $myaccess == 'limited') {
	$counter_close = 1;
} else {
	$counter_close = 1;
}

if(isset($counter_close) && $counter_close == 1) {

	//All clear
	//create a log file and logout user
	$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>"endsession","message"=>"Log out from the application platform","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

	$message = "Closing all sessions..";

	sessionCloseSid(USER_AUTHEN_ID);
	$page = sessionCloseSid(PAGE_AUTHEN_SID);
	sessionIsChecked($page,MAIN_DOMAIN_URL,'session_active_page');
}

?>

<html>
	<head>
		<title><?php echo SOFTWARE_NAME; ?> | Back Office</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<link rel="stylesheet" href="../../style/csslibrary/default.css">
		<link rel="shortcut icon" href="../../theme/images/inc/fav-icon.png" type="images/x-icon"/>
		
	</head>
	<body>
		<div id="container" align="center">
			
			<br><br>
			
			<h2 class="nobold"><?php echo $message; ?></h2>
			
		</div>
	</body>
</html>