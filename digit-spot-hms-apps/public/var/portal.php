<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; ?>
<?php sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); ?>

<?php 

	$dataproperty = "staffname,emailaddress";
	$queryproperty = array("id"=>USER_AUTHEN_ID);
	$admin_data = mysqli_get_schema_data($tbL1,$dataproperty,$queryproperty);
	
	$admin_name = ucwords($admin_data[0]);
	$user_login = $admin_data[1];
	
	//linking pages
	$pg_link1 = "logout".PHP_EXT;
	$pg_link2 = "ini.php";

	#----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['passwordbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);

		$insert_dataproperty = array("password"=>sha1($fieldset2));
		$insert_constrain = array("id"=>USER_AUTHEN_ID);
		$data_inserted = mysqli_data_update($tbL1,$insert_dataproperty,$insert_constrain);

		if(isset($data_inserted) && $data_inserted == 2)
		{
			?> <script> window.location.href = "<?php echo $pg_link1; ?>"; </script> <?php 
		}
	}

	#----------------------------------------------------------------------------------------------------------------

?>
<html>
	<head>
		<title><?php echo SOFTWARE_NAME; ?> | App Services</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<link rel="shortcut icon" href="../../theme/images/inc/fav-icon.png" type="images/x-icon"/>
		<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="applystyle.css"/>
		<script type="text/javascript" src="../../js/jspath.js"></script>
		<script type="text/javascript" src="../../js/jsutility.js"></script>

		<style>

		.nc-width-79 {
			width: 79%;
		}

		.stick-cs-left-margin {
			margin-left: 25% !important;
			margin-right: 10% !important;
		}

		#chatframe {
			height: 37px;
		}

		#cframeheader {
			border-top-right-radius: 5px;
			border-top-left-radius: 5px;
			-webkit-border-top-right-radius: 5px;
			-webkit-border-top-left-radius: 5px;
		}

		#messaging {
			height: 100% !important;
			padding: 35px 30px 10px 40px !important;
			outline: 0;
		}

		#messaging:focus {
			outline: 0;
		}

		.select-custom-state-1 {
			width: 150px !important;
			padding: 7px !important;
		}


		.menu-mini-photobox {
			width: 45px;
			height: 45px;
		}

		</style>
		
	</head>
	<body id="parent-node" class="grey-1-theme noscroll">
		<div id="container" class="block-element">
			<div id="sidebar" class="ln-display-box float-left nc-width-20 nc-height-100 white-theme obj-dark-shadow" lang="s">
				<span id="icons" class="ln-display-box float-left nc-width-15 nc-height-100 black-theme">
					<div class="nc-height-15 top-pull-30 alignct anchor fa-listing fa-sml-size fa-color-strike-2" onclick="slideInOut()" title="Slide In/Out">
					</div>
					<div class="nc-height-85 alignct top-pull-3 left-pull-10">
						<span class="block-element top-push-10 nc-width-60 nc-height-5 noscroll anchor" title="Dashboard">
							
						</span>
					</div>
				</span>
				<span id="text-link" class="ln-display-box float-right nc-width-85 nc-height-100 motion">
					<div id="text-link-tp" class="box-border-thick-bottom nc-height-15 top-pull-30 left-pull-20 noscroll">
						<b class="ft-tahoma"><?php echo SOFTWARE_NAME; ?></b>
					</div>
					<div id="text-link-bt" class="grey-2-theme nc-height-85 y-scroll ft-sml-size letter-spacing-2">
						<span id="tab1" class="block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor" onclick="module('tab1','<?php echo $pg_link2; ?>?logs=dashboard')">
							&nbsp; Dashboard
						</span>
						<span id="tab2" class="block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor" onclick="module('tab2','<?php echo $pg_link2; ?>?logs=module category')">
							&nbsp; Module Category
						</span>
						<span id="tab3" class="block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor" onclick="module('tab3','<?php echo $pg_link2; ?>?logs=category library')">
							&nbsp; Category Library
						</span>
						<span id="tab4" class="block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor" onclick="module('tab4','<?php echo $pg_link2; ?>?logs=super admin')">
							&nbsp; Super Admin
						</span>
						<span id="tab5" class="block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor" onclick="module('tab5','<?php echo $pg_link2; ?>?logs=app services')">
							&nbsp; App Services
						</span>
					</div>
				</span>
			</div>
			<div id="workspace" class="ln-display-box float-right nc-width-79 nc-height-100 grey-1-theme left-push-3 motion">
				<div class="fx-position-stick tpscr zind-4 bottom-push-50 pads15">
					<div class="block-element right-pull-50">
						<span class="ln-display-box float-left">
							&nbsp;
						</span>
						<span class="ln-display-box float-right left-push-10 ft-sml-size">
							<a href="<?php echo $pg_link1; ?>" class="overstate-font-two">Logout</a>
						</span>
						<span class="ln-display-box float-right left-push-50 ft-sml-size">
							<a href="javascript:void(0)" class="overstate-font-two" onclick="objDisplay('modal-box'); objDisplay('modal-box-1')">Profile</a>
						</span>
						<span class="ln-display-box float-right ft-sml-size">
							Logged in: <b><?php echo $admin_name; ?></b>
						</span>
						<span class="block-element new-line-space">
							<!-- clear line -->
						</span>
					</div>
				</div>
				
				<iframe src="<?php echo $pg_link2; ?>" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" width="100%" height="100%" name="frameworks" id="frameworks"></iframe>
				
			</div>

			<span class="block-element new-line-space">
				<!-- clear line -->
			</span>

		</div>

		<div id="modal-box" class="fx-position-flow fscr zind-1 motion txp5-white noshow" align="center">
			<div class="block-element nc-height-15">&nbsp;</div>
			<div id="modal-box-1" class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 sml-rounded-button obj-light-shadow motion">
				<form action="" method="post" autocomplete="off">
					<div class="form-block-label alignlt">
						<h3 class="nobold nomargin">Change Password</h3><br>
					</div>
					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">USERID</small><br>
						<input type="text" name="fieldset1" id="fieldset1" placeholder="<?php echo strtolower($user_login); ?>" required="required" class="no-back-black" readonly>
					</p>
					<p class="form-block-label">
						<input autocomplete='new-password' type="text" name="fieldset2" id="fieldset2" placeholder="New Password?" required="required" class="no-back-black" onkeyup="htmlFormField('fieldset2','password')">
					</p>
					
					
					<br>
					
					<input type="submit" name="passwordbutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('modal-box'); objHidden('modal-box-1')">Cancel</a>
				</form>
			</div>
		</div>
			
	</body>
</html>