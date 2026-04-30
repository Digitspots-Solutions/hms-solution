<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_; ?>

<?php
sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];

include "../public/admin/module_operation_privilege.php";

$_get_portal = app_service_portal($service_portal,$_SESSION['app_service']);

$shift_htmlresult='';
$counter_htmlresult='';
$post_htmlresult='';
$post_result='';
$new_counter = 0;
$iscurshift = 0;
$isshiftxls = "";

//check if last counter was not closed

$additionalQuery=" ORDER BY id DESC LIMIT 1";
$user_counter_query = array("userid"=>$userSignedIn,"logstatus"=>"Open");
mysqli_data_check($tbL22,'(*)',$user_counter_query);
$counter_count_check = $numOfrows;

if(isset($counter_count_check) && $counter_count_check >= 1) {
	$get_last_counterid = mysqli_data_fetch($tbL22,'counterid',$user_counter_query,'noarray');
	$_SESSION['counter_id'] = $get_last_counterid[0];

	$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
	$post_result .= '<small class="red-font">Redirecting..</small>';
	$post_result .= '</div>';

	?>
		<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location = "counter_checker<?php echo PHP_EXT; ?>?counter=forced-cls"; }, false); </script>
	<?php
} else {
	
	if(!isset($_POST['submitbutton'])) {

		//check if user has no counter but has logged in with shift
		$additionalQuery=" ORDER BY id DESC LIMIT 1";
		$user_shift_query = array("userid"=>$userSignedIn,"datelogged"=>$server_get_date,"status"=>"Open");
		mysqli_data_check($tbL23,'(*)',$user_shift_query);
		$shift_count_check = $numOfrows;

		if(isset($shift_count_check) && $shift_count_check >= 1) {
			$time_error = 0; $error_message = ''; $shift_name = "";
			$wgtshift = mysqli_data_fetch($tbL23,'shiftid,counterid,closetime',$user_shift_query,'noarray');
			
			if(isset($wgtshift[0]) && $wgtshift[0] >= 1) {
			
				$iscurshift = $wgtshift[0];
				$isshiftxls = $wgtshift[2];

				$shift_name = idget_data($tbL20,$wgtshift[0],'shiftname');
				$shift_startime = idget_data($tbL20,$wgtshift[0],'startime');
				$shift_endtime = idget_data($tbL20,$wgtshift[0],'endtime');
				$server_current_time = str_replace(':', '', $server_get_time);

				$shift_startime = str_replace(':', '', $shift_startime);
				$shift_endtime = str_replace(':', '', $shift_endtime);

				$time_error = 0;
			}
			
			if(!isset($time_error) || $time_error == 0) {
				
				$user_counter_query = array("userid"=>$userSignedIn,"counterid"=>$wgtshift[1],"logstatus"=>"Closed","datelogged"=>$server_get_date); $iscounter = mysqli_data_checkr($tbL22,'(*)',$user_counter_query);

				if($iscounter == true) {
					$new_counter = 1;
				} else {
					$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
					$post_result .= '<small class="red-font">Active counter! Please wait while loading hotelmaster..</small>';
					$post_result .= '</div>';

					?>
						<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location.href = "<?php echo $_get_portal; ?>public/admin/portal<?php echo PHP_EXT; ?>"; }, false); </script>
					<?php
				}
			} else {
				$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
				$post_result .= '<small class="red-font">'.$error_message.'</small>';
				$post_result .= '</div>';

				$shift_sql = array("closetime"=>$server_get_time,"dateclosed"=>$server_get_date,"status"=>"Closed");
				mysqli_data_update($tbL23,$shift_sql,$user_shift_query);
			}
		} else {
			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$user_shift_query = array("userid"=>$userSignedIn,"status"=>"Open");
			$isactiveShift = mysqli_data_checkr($tbL23,'(*)',$user_shift_query);

			if($isactiveShift == true) {
				$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
				$post_result .= '<small class="red-font">Active shift! Please wait while loading hotelmaster..</small>';
				$post_result .= '</div>';

				?>
					<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location.href = "<?php echo $_get_portal; ?>public/admin/portal<?php echo PHP_EXT; ?>"; }, false); </script>
				<?php
			}
		}
	}
}

#-----------------------------------------------------------------------------------------------------------------------------------------------------------

$additionalQuery="";

$shift_dataproperty = "id,shiftname,startime,startimelabel,endtime,endtimelabel";
/*if(isset($iscurshift) && $iscurshift > 0) { $shift_query = array("id"=>$iscurshift); }
else { $shift_query = array("deletedata"=>0,"status"=>"Active"); }*/
$shift_query = array("deletedata"=>0,"status"=>"Active");
$shift_data = mysqli_data_fetch($tbL20,$shift_dataproperty,$shift_query,'array');

if(is_array($shift_data)) {
	foreach ($shift_data as $shift_key => $shift_value) {
		$shift_htmlresult .='<option value="'.$shift_value["id"].'/'.$shift_value["startime"].'/'.$shift_value["endtime"].'">'.$shift_value["shiftname"].' ('.$shift_value["startimelabel"].' - '.$shift_value["endtimelabel"].')</option>';
	}
} else {
	$shift_htmlresult ='<option value="">No available shift time</option>';
}


#-----------------------------------------------------------------------------------------------------------------------------------------------------------

$counter_dataproperty = "id,countername,countertype";
$counter_query = array("deletedata"=>0);
$counter_data = mysqli_data_fetch($tbL19,$counter_dataproperty,$counter_query,'array');

if(is_array($counter_data)) {
	$counter_statusquery = ""; $counterstatus = "";
	foreach ($counter_data as $counter_key => $counter_value) {
		$counter_statusquery = array("counterid"=>$counter_value["id"]);
		$counter_status = mysqli_data_fetch($tbL21,'id,status',$counter_statusquery,'noarray');
		if(isset($counter_status[0]) && $counter_status[0] >= 1) {
			$counterstatus = $counter_status[1];
		} else {
			$counterstatus = 'Closed';
		}

		$counter_htmlresult .='<option value="'.$counter_value["id"].'/'.$counterstatus.'">'.$counter_value["countername"].' ('.$counterstatus.')</option>';
	}
} else {
	$counter_htmlresult ='<option value="">No available counter</option>';
}

#-----------------------------------------------------------------------------------------------------------------------------------------------------------

if(isset($_POST['submitbutton'])) {
	
	createDatabasetable($var_tbl_19); //create a table for this post
	createDatabasetable($var_tbl_20); //create a table for this post
	createDatabasetable($var_tbl_21); //create a table for this post

	$time_error = 0;
	
	if(isset($_POST['fieldset1']) && !empty($_POST['fieldset1'])) {
		$shiftiming = explode('/', $_POST['fieldset1']);
		$server_current_time = str_replace(':', '', $server_get_time);
		$work_startime = str_replace(':', '', $shiftiming[1]);
		$work_endtime = str_replace(':', '', $shiftiming[2]);

		$time_error = 0;
		$error_message = '';
	} else {
		$time_error = 1;
	}
	
	
	if(!isset($time_error) || $time_error == 0) {
		
		$shiftid = $shiftiming[0];

		if(isset($_POST['fieldset2']) && !empty($_POST['fieldset2'])) {
			
			$counterstat = explode('/', $_POST['fieldset2']);
		
			if(isset($counterstat[1]) && $counterstat[1] == 'Closed') {
				$counter_error = 0;
				$error_message = '';
			} else {
				$counter_error = 1;
				$error_message = 'Error: Invalid counter selection as already in use!';
			}

			if($counter_error == 0) {
				$counterid = $counterstat[0];
				//$_SESSION['iscounteruse'] = 1;
				
				//do counter log
				$counter_check = array("counterid"=>$counterid);
				$get_counter_data = mysqli_data_fetch($tbL21,'id',$counter_check,'noarray');
				if(isset($get_counter_data[0]) && $get_counter_data[0] >= 1) { 
					$update_counter_datasets = array("status"=>"Open");
					mysqli_data_update($tbL21,$update_counter_datasets,$counter_check);
				} else {
					$update_counter_datasets = array("counterid"=>$counterid,"status"=>"Open");
					mysqli_data_insert($tbL21,$update_counter_datasets,'');
				}

				//do user counter log
				$user_counter_log = array("counterid"=>$counterid,"userid"=>$userSignedIn,"logstatus"=>"Open","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL22,$user_counter_log,'');

				//do user shift log
				//$user_shift_qy = array("counterid"=>$counterid,"shiftid"=>$shiftid,"userid"=>$userSignedIn,"datelogged"=>$server_get_date);
				$user_shift_qy = array("counterid"=>$counterid,"shiftid"=>$shiftid,"userid"=>$userSignedIn,"status"=>"Open");
				$user_shift_log = array("counterid"=>$counterid,"shiftid"=>$shiftid,"userid"=>$userSignedIn,"resumptiontime"=>$server_get_time,"datelogged"=>$server_get_date);
				mysqli_data_insert($tbL23,$user_shift_log,$user_shift_qy);

				$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
				$post_result .= '<small class="red-font">Please wait while loading your counter..</small>';
				$post_result .= '</div>';

				?>
					<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location = "user_counter<?php echo PHP_EXT; ?>?cid=<?php echo $counterid; ?>"; }, false); </script>
				<?php

				$loadpage=1;

			} else {
				$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
				$post_result .= '<small class="red-font">'.$error_message.'</small>';
				$post_result .= '</div>';

				$loadpage=0;
			}
		} else {

			//do user shift log
			$user_shift_qy = array("shiftid"=>$shiftid,"userid"=>$userSignedIn,"status"=>"Open");
			$user_shift_log = array("counterid"=>0,"shiftid"=>$shiftid,"userid"=>$userSignedIn,"resumptiontime"=>$server_get_time,"datelogged"=>$server_get_date);
			mysqli_data_insert($tbL23,$user_shift_log,$user_shift_qy);

			$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
			$post_result .= '<small class="red-font">Please wait while loading hotelmaster..</small>';
			$post_result .= '</div>';

			?>
				<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location.href = "<?php echo $_get_portal; ?>public/admin/portal<?php echo PHP_EXT; ?>"; }, false); </script>
			<?php

			$loadpage=1;
		}

	} else {
		$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
		$post_result .= '<small class="red-font">'.$error_message.'</small>';
		$post_result .= '</div>';

		$loadpage=0;
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
		<script type="text/javascript" src="../js/jsbk.js"></script>
	</head>
	<body class="grey-theme noscroll">
		<div class="block-element top-pull-50" align="center">
			<img src="<?php echo _FC_LOGO; ?>" class="bottom-push-20">
			<div class="cs-width-500 white-theme pads30 sml-rounded-button obj-shadow" align="left">
				<h3 class="nobold blue-font">Welcome <b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b></h3><br>
				<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">
					<?php echo $post_result; ?>
					<br><h4 class="large nobold default-text-font-bold">Select the shift and counter that you are starting now</h4><br>
					<form action="" method="post">
						<span class="block-element bottom-push-10">
							<select name="fieldset1" id="fieldset1" required="required">
								<?php if(!isset($iscurshift) || $iscurshift == 0) { ?><option value="" selected="selected">Select Shift</option><?php } echo $shift_htmlresult; ?>
							</select>
						</span>
						<?php
	
							if((isset($allowCounterUse) && $allowCounterUse == 200) || (isset($allowPosCounterUse) && $allowPosCounterUse == 200)) {
								?>
									<span class="block-element bottom-push-10">
										<select name="fieldset2" id="fieldset2" required="required">
											<option value="" selected="selected">Select Counter</option>
											<?php echo $counter_htmlresult; ?>
										</select>
									</span>
								<?php
							}

						?>
						<p id="submit-box" class="top-pull-20"><input type="submit" name="submitbutton" value="Continue" class="submit pads10 blue-white-state rounded-button nc-width-60"></p>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>