<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_; include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

$toparentLog = true;
sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/notificationlist.php";
include "../../includes/hotel_profile.php";
include "module_operation_privilege.php";

define ("_LONG_NAME",$hotel_name);

$post_header = "";
$post_message = "";

$smdl = "";
$islogfile = 0;
$logfile_msg = "";

$isguestAct = 0;
$guestAct_msg = "";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script type="text/javascript" src="../../js/all.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	<?php

		if(isset($_GET['logs']) && $_GET['logs'] == 'modals')
		{
			$pfx = $_GET['prefix'];
			if(!empty($pfx)) { include $pfx."/modals.php"; }
			else { include "modals.php"; }
		}

		#----------------------------------------------------------------------

		
		##create a log file
		if(isset($islogfile) && $islogfile == 1) {
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$logfile_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');
		}

		##log guest activities
		if(isset($isguestAct) && $isguestAct == 1) {
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$ths_guest_pry,"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

			if(isset($remark_tag) && !empty($remark_tag)) { $guest_activities_dataproperty['remark_tag'] = $remark_tag; }
			if(isset($app_tag) && !empty($app_tag)) { $guest_activities_dataproperty['app_tag'] = $app_tag; }
			if(isset($session_tag) && !empty($session_tag)) { $guest_activities_dataproperty['session_tag'] = $session_tag; }
			
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
		}
		

		##pop notifications

		if(isset($saynotify) && $saynotify >= 1) {
			switch ($notifytype) {
				case 1:
					$header = 'Room Block Notification!';
					$message = 'You have rooms that are due to unblocking. Please refer to whom it concerns';
					break;
				
				case 2:
					$header = $post_header;
					$message = $post_message;
					break;

				default:
					$header = 'Error Notification!';
					$message = 'No message found';
					break;
			}

			?>
				
				<div id="notifybox" class="noshow fx-position-stick zind-1 motion fscr" align="right">
					<div class="block-element cs-height-120"></div>
					<div class="cs-width-400 white-theme pads20 obj-shadow right-push-50 sml-rounded-button alignlt">
						<h4 class="large red-font"><?php echo $header; ?></h4>
						<small class="block-element top-push-10"><?php echo $message; ?></small>
					</div>
				</div>

				<script>
					window.addEventListener('load',function() { 
						parent.document.getElementById('workspace').scrollTop = 0;
						parent.document.getElementById('for-pop-wins').scrollTop = 0;
						objDisplay('notifybox'); autohidePopupBox('notifybox',2000);
					}, false);
				</script>

			<?php
		}

	?>
</div>

<div id="processbar" class="fx-position-stick fscr zind-1 motion noshow" align="center">
	<div class="block-element nc-height-10">&nbsp;</div>
	<div class="cs-width-250 white-theme obj-shadow pads20">Processing request..</div>
</div>