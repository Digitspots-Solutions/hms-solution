<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_;

sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];


$htmlresult='';
$post_htmlresult='';

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
	<body class="grey-1-theme y-scroll">

		<div class="pads30" align="center">
			<div class="cs-height-100"></div>
			<div class="fx-width-50 pads30">
				<h2 class="large nobold default-text-font-bold">Please use account float menu in frontdesk to complete this request. Click the button to return</h2>
				<p class="top-pull-30">
					<input type="button" value="Return" class="submit pads10 dark-black-white-state rounded-button nc-width-30" onclick="window.location.href='<?php echo DOMAIN_URL.PUB_FLD.'admin/portal'.PHP_EXT; ?>'">
				</p>
			</div>
		</div>
	</body>
</html>