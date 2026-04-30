<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_;

sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];

createDatabasetable($var_tbl_3); //create log table

$_get_portal = app_service_portal($service_portal,$_SESSION['app_service']);

if(isset($myaccess) && $myaccess == 'super admin') {
	//$_SESSION['uaccess'] = $admin_data[0];
	$loadpage = $_get_portal."public/admin/portal".PHP_EXT.'?aps='.$_SESSION['app_service'];

	//create a log file
	$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>"accessibility","message"=>"Log in to the application platform","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

} else {
	$prc_query = array("id"=>$myprimarycontact);
	$prc_data = mysqli_data_fetch($tbL7,'status',$prc_query,'noarray');

	if($prc_data[0] == 'InActive') {
		$loadpage = DOMAIN_URL."login/error-login".HTM_EXT;
	} else {
		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>"accessibility","message"=>"Log in to the application platform","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$loadpage = DOMAIN_URL."login/shift_counter".PHP_EXT;
	}
}


?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<title><?php echo _CTITLE_; ?></title>
		<meta name="description" content="<?php echo _CTITLE_; ?>">
		<link rel="shortcut icon" href="../theme/images/inc/favicon.png" type="images/x-icon"/>
		<script type="text/javascript" src="../style/csslibrary/flexcroll.js"></script>
		<link rel="stylesheet" href="../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../style/custom.css"/>
	</head>
	<body class="grey-theme noscroll">
		<div class="block-element top-pull-50" align="center">
			<div class="top-push-50">
				<h2 class="dark-grey-font">Initializing authentication, please wait..</h2>
			</div>
		</div>
	</body>
</html>

<script>

	window.addEventListener('load', function() {
		window.location.href = '<?php echo $loadpage; ?>';
}, false);

</script>