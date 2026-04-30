<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_;

sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];

$_get_portal = app_service_portal($service_portal,$_SESSION['app_service']);

$htmlresult='';
$post_htmlresult='';

if(isset($_GET['counter']) && $_GET['counter'] == 'ncls')
{
	$htmlresult .= '<br><h3 class="large nobold default-text-font-bold">'.$print_shift.'</h3><br>';
	$htmlresult .='<h3 class="large nobold">Unable to log you out as your counter is still opened</h3><br>';
	$htmlresult .='<a href="close_counter'.PHP_EXT.'?sesid=y" class="submit pads10 blue-white-state rounded-button nc-width-40"> &nbsp; Close Counter &nbsp; </a>';
	$htmlresult .='&nbsp;&nbsp;';
	$htmlresult .='<a href="'.$_get_portal.'public/admin/portal'.PHP_EXT.'?aps='.$_SESSION['app_service'].'" class="submit pads10 black-white-state rounded-button nc-width-50"> &nbsp; Continue Working &nbsp; </a>';
	$htmlresult .='<br><br>';
}
elseif(isset($_GET['counter']) && $_GET['counter'] == 'forced-cls')
{
	$htmlresult .= '<br><h3 class="large nobold default-text-font-bold">'.$print_shift.'</h3><br>';
	$htmlresult .='<h3 class="large nobold">Your last counter was not closed, rendering it unreachable to others. You can either close this counter or continue with same counter</h3><br>';
	$htmlresult .='<a href="close_counter'.PHP_EXT.'?sesid=ny" class="submit pads10 blue-white-state rounded-button nc-width-40"> &nbsp; Close Counter &nbsp; </a>';
	$htmlresult .='&nbsp;&nbsp;';
	$htmlresult .='<a href="'.$_get_portal.'public/admin/portal'.PHP_EXT.'?aps='.$_SESSION['app_service'].'" class="submit pads10 black-white-state rounded-button nc-width-50"> &nbsp; Continue &nbsp; </a>';
	$htmlresult .='<br><br>';
}

$counter_query = array("id"=>$_SESSION['counter_id']);
$get_counter_data = mysqli_data_fetch($tbL19,'countername',$counter_query,'noarray');
$counter_select = $get_counter_data[0];

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
		<script type="text/javascript" src="../js/jsbk.js"></script>
	</head>
	<body class="grey-theme y-scroll">
		<div class="block-element top-pull-50" align="center">
			<img src="<?php echo _FC_LOGO; ?>" class="bottom-push-20">
			<div class="cs-width-700 white-theme pads30 sml-rounded-button obj-shadow bottom-push-30" align="left">
				<h3 class="nobold blue-font"><b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b> (<?php echo $counter_select; ?>)</h3><br>
				<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">
					<?php echo $htmlresult; ?>
				</div>
			</div>
		</div>
	</body>
</html>