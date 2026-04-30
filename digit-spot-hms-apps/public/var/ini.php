<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._APPMODULES_; ?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/animates.css">
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsutility.js"></script>

<br><br>

<div class="block-element top-push-50 right-pull-30 left-pull-30">
	
	<?php

	$usr_rl = array("id"=>USER_AUTHEN_ID);
	$admin_role = mysqli_get_schema_data($tbL1,'uaccess',$usr_rl);
	$uaccess = strtolower($admin_role[0]);

	#------------------------------------------------------------------------------------------------------------------------------------

	$prfx = "/";

	if(isset($_GET['logs'])) { $mdl = escape_data($_GET['logs']); }
	else { $mdl = null; }

	$default_module = null;
	$mdlses = null;
	$_flyclass_1_ = null;

	if(isset($mdl) && $mdl == 'module category')
	{
		$default_module = $mdl;
		$do_page = "snippet_fs.php";
	}
	elseif(isset($mdl) && $mdl == 'category library')
	{
		$default_module = $mdl;
		$do_page = "snippet_ss.php";
	}
	elseif(isset($mdl) && $mdl == 'super admin')
	{
		$default_module = $mdl;
		$do_page = "snippet_ts.php";
	}
	elseif(isset($mdl) && $mdl == 'app services')
	{
		$default_module = $mdl;
		$do_page = "snippet_ap.php";
	}
	

	if(!isset($default_module))
	{
		$do_page = "dashboard.php";
	}

	if(isset($_GET['mdlses']) && !empty($_GET['mdlses']))
	{
		$mdlses = escape_data($_GET['mdlses']);

		$get_uri = $_SERVER['REQUEST_URI'];
		$get_active_uri = $prfx."/public/var/ini.php?logs=".$mdl."&mdlses=".$mdlses;

		if(isset($get_uri) && $get_uri == $get_active_uri) { $_flyclass_1_ = "box-3border-thick-bottom royal-blue-font ft-sml-size motion"; } else { $_flyclass_1_ = "black-font ft-sml-size motion"; }
	}
	

	$_flyclass_2_ = "black-font ft-sml-size motion";

	include $do_page;

	?>

</div>

<div id="process-bar" class="fx-position-stick fscr txp5-white alignct noshow">
	<div class="nc-height-40"></div>
	<b class="nobold letter-spacing-1 pads15 grey-theme red-font"><small>PROCESSING REQUEST</small></b>
</div>

<br><br>