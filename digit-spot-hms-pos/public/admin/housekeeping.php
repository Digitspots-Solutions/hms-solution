<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

//include "../../includes/uom.php";
include "../../includes/common_data_vars.php";

$logs = $_GET['logs']; $smdl = "housekeeping";

$additionalQuery = "";
$blocks = select_dt_fetch('',0,$tbL49,'id','name');
//$floors = select_dt_fetch('',0,$tbL50,'id','name');
$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');
$statuslists = select_dt_fetch('',0,$tbL36,'id','legendname');

$dp_key = array("mdl"=>4);
$dp_data = mysqli_data_fetch($tbL4,'id',$dp_key,'array');
if(is_array($dp_data)) {
	$housekeepers = '';
	foreach ($dp_data as $dpt_key => $dpt_value) {
		$ext_tbls = "0,".$tbL12.",".$tbL4;
		$housekeepers .= mt_select_fetch('role',$dpt_value['id'],$tbL7,'id','staffname,department,role',$ext_tbls,'0,department,role');
	}
} else {
	$housekeepers = '<option value="">No user</option>';
}


#--------------------------------------------------------------------------------------------------

$post_result = '';

//change houskeeping status
if((isset($_POST['applystatusbutton']) && isset($_POST['rooms'])) && (!empty($_POST['rooms']))) {
	
	createDatabasetable($var_tbl_89); //create a table for this post
	createDatabasetable($var_tbl_90); //create a table for this post

	$rooms = explode(',',$_POST['rooms']);
	$numofrun = 0;

	foreach($rooms as $rm) {
		if($rm != '') {
			$room_type_id = idget_data($tbL56,$rm,'room_type_id');
			$r_key = array("roomid"=>$rm);
			$r_data = mysqli_data_fetch($tbL94,'id,room_status_id,housekeeping_stateid',$r_key,'noarray');
			if(isset($r_data[0]) && $r_data[0] >= 1) {
				$datasets_1 = array("housekeeping_stateid"=>$_POST['hskstatus'],"remarks"=>escape_data($_POST['remarks']));
				if($r_data[1] == 2 || $r_data[1] == 4 || $r_data[1] == 5 || $r_data[1] == 8) { $datasets_1['room_status_id'] = 1; }
				$isdata = mysqli_data_update($tbL94,$datasets_1,$r_key);
				$old_housekeeping_state = $r_data[2];
			} else {
				$datasets_1 = array("room_type"=>$room_type_id,"roomid"=>$rm,"housekeeping_stateid"=>$_POST['hskstatus'],"room_status_id"=>1,"remarks"=>escape_data($_POST['remarks']),"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$isdata = mysqli_data_insert($tbL94,$datasets_1,$r_key);
				$old_housekeeping_state = 6;
			}

			if(isset($isdata) && $isdata == 2) { $numofrun += 1; }

			$datasets_2 = array("room_type"=>$room_type_id,"roomid"=>$rm,"housekeeping_stateid"=>$old_housekeeping_state,"new_housekeeping_stateid"=>$_POST['hskstatus'],"room_status_id"=>1,"remarks"=>escape_data($_POST['remarks']),"userid"=>0,"assignedby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL95,$datasets_2,'');
		}
	}

	if(isset($numofrun) && $numofrun >= 1) {
		
		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have successfully updated house-keeping status</span>';
		$post_result .= '</div>';

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently updated housekeeping status (for rooms)","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
	}
}


//change houskeeping room keeper
if((isset($_POST['applyassignbutton']) && isset($_POST['rooms'])) && (!empty($_POST['rooms']))) {
	
	createDatabasetable($var_tbl_89); //create a table for this post
	createDatabasetable($var_tbl_90); //create a table for this post

	$rooms = explode(',',$_POST['rooms']);
	$numofrun = 0;

	foreach($rooms as $rm) {
		if($rm != '') {
			$room_type_id = idget_data($tbL56,$rm,'room_type_id');
			$r_key = array("roomid"=>$rm);
			$r_data = mysqli_data_fetch($tbL94,'id,housekeeping_stateid',$r_key,'noarray');
			if(isset($r_data[0]) && $r_data[0] >= 1) {
				$datasets_1 = array("userid"=>$_POST['assignuser']);
				$isdata = mysqli_data_update($tbL94,$datasets_1,$r_key);
				$old_housekeeping_state = $r_data[1];
			} else {
				$datasets_1 = array("room_type"=>$room_type_id,"roomid"=>$rm,"housekeeping_stateid"=>1,"room_status_id"=>1,"remarks"=>"No remarks","userid"=>$_POST['assignuser'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$isdata = mysqli_data_insert($tbL94,$datasets_1,$r_key);
				$old_housekeeping_state = 2;
			}

			if(isset($isdata) && $isdata == 2) { $numofrun += 1; }

			$datasets_2 = array("room_type"=>$room_type_id,"roomid"=>$rm,"housekeeping_stateid"=>$old_housekeeping_state,"new_housekeeping_stateid"=>1,"room_status_id"=>1,"remarks"=>"Assigned for cleaning or touch-up","userid"=>$_POST['assignuser'],"assignedby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL95,$datasets_2,'');
		}
	}

	if(isset($numofrun) && $numofrun >= 1) {
		
		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have successfully assigned user to selected room(s)</span>';
		$post_result .= '</div>';

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently assigned user to rooms (for task)","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
	}
}


#--------------------------------------------------------------------------------------------------

if(isset($_GET['opt1']) && $_GET['opt1'] >= 1) {
	
	$room_type_id = escape_data($_GET['opt1']);
	$room_type_name = idget_data($tbL52,$room_type_id,'name');

	if(isset($_GET['opt2']) && $_GET['opt2'] >= 1) {
		$block_id = escape_data($_GET['opt2']);
		$block_name = idget_data($tbL49,$block_id,'name');
		$opt2 = " AND blockid = ".$block_id;

		$nw_htmlresult='';

    	$dataid_query = escape_data($_GET['opt2']);
    	$cpn_constrain = array("blockid"=>$dataid_query,"deletedata"=>0);
	    $cpn_data = mysqli_data_fetch($tbL50,'id,name',$cpn_constrain,'array');

	    if(is_array($cpn_data)) {
	    	$nw_htmlresult .='<option value="0">All</option>';
	    	foreach ($cpn_data as $key => $value) {
	    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
	    	}
		} else {
			$nw_htmlresult .='<option value="0">All</option>';
		}
	} else {
		$block_id = "";
		$block_name = "";
		$opt2 = "";

		$nw_htmlresult .='<option value="0">All</option>';
	}

	if(isset($_GET['opt3']) && $_GET['opt3'] >= 1) {
		$floor_id = escape_data($_GET['opt3']);
		$floor_name = idget_data($tbL50,$floor_id,'name');
		$opt3 = " AND floorid = ".$floor_id;
	} else {
		$floor_id = "";
		$floor_name = "";
		$opt3 = "";
	}

	if(isset($_GET['opt4']) && $_GET['opt4'] >= 1) {
		$status_id = escape_data($_GET['opt4']);
		$status_name = idget_data($tbL36,$status_id,'legendname');
	} else {
		$status_id = 0;
	}

	$_SESSION['ext_query'] = " WHERE room_type_id=".$room_type_id.$opt2.$opt3;
	
} else {
	$_SESSION['ext_query'] = null;
	$nw_htmlresult .='<option value="0">All</option>';
}

if(isset($_SESSION['ext_query']) && !empty($_SESSION['ext_query'])) {
	$_SESSION['ext_query'] = $_SESSION['ext_query'];
} else {
	$_SESSION['ext_query'] = null;
}

$keywords = $_SESSION['ext_query'];
$_SESSION['status_id'] = $status_id;

$keywords = $keywords." ORDER BY roomprefix ASC, roomnumber ASC";

//echo $keywords;

//pagination controller
if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
	$curpage = $_GET['pg'];
	$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
	$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
} else {
	$curpage = 0;
	$pgstart = 0; $pglimit = 50;
	$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
}

$rm_type_data = mysqli_data_fetch($tbL56,'id,roomnumber,roomprefix,blockid,floorid,room_type_id','','array');

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	<div class="block-element light-yellow-theme pads10 bottom-push-30">
		Here you see the list of rooms and current status. You can manage the rooms status by following the on-screen instruction 
	</div>
	<div class="block-element left-pull-10 right-pull-10">
		<form action="" method="post" autocomplete="off">
			<span class="ln-display-box float-left nc-width-70 box-border-thick pads10">
				<div class="ln-display-box float-left nc-width-25 right-push-20">
					<small class="block-element bottom-push-3">Filter By: <b>Room Type</b></small>
					<select name="roomtypelist" id="roomtypelist" onchange="loadrooms('roomtypelist','roomtype')">
						<?php if(isset($room_type_id) && $room_type_id >= 1) { ?><option value="<?php echo $room_type_id; ?>"><?php echo $room_type_name; ?></option><?php } else { ?><option value="0" selected="selected">All</option><?php } ?>
						<?php echo $roomtypes; ?>
					</select>
				</div>
				<div class="ln-display-box float-left nc-width-20 right-push-20">
					<small class="block-element bottom-push-3">Filter By: <b>Blocks</b></small>
					<select name="blocklist" id="blocklist" onchange="loadrooms('blocklist','roomblock')">
						<?php if(isset($block_id) && $block_id >= 1) { ?><option value="<?php echo $block_id; ?>"><?php echo $block_name; ?></option><?php } else { ?><option value="0" selected="selected">All</option><?php } ?>
						<?php echo $blocks; ?>
					</select>
				</div>
				<div class="ln-display-box float-left nc-width-20 right-push-20">
					<small class="block-element bottom-push-3">Filter By: <b>Floors</b></small>
					<select name="floorlist" id="floorlist" onchange="loadrooms('floorlist','roomfloor')">
						<?php if(isset($floor_id) && $floor_id >= 1) { ?><option value="<?php echo $floor_id; ?>"><?php echo $floor_name; ?></option><?php } ?>
						<?php echo $nw_htmlresult; ?>
					</select>
				</div>
				<div class="ln-display-box float-left nc-width-20">
					<small class="block-element bottom-push-3">Filter By: <b>Status</b></small>
					<select name="statuslist" id="statuslist" onchange="loadrooms('statuslist','roomstatus')">
						<?php if(isset($status_id) && $status_id >= 1) { ?><option value="<?php echo $status_id; ?>"><?php echo $status_name; ?></option><?php } else { ?><option value="0" selected="selected">All</option><?php } ?>
						<?php echo $statuslists; ?>
					</select>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="ln-display-box float-left nc-width-30 left-pull-20 alignrt">
				<input type="submit" name="assignbutton" value="Assign rooms to" class="submit top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 dark-black-white-state bottom-push-5 sml-rounded-button"><br><input type="submit" name="statusbutton" value="Change room status" class="submit top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 dark-black-white-state sml-rounded-button">
			</span>
			<span class="block-element new-line-space">
			</span>

			<?php echo $post_result; ?>

			<p class="top-pull-7 bottom-pull-3"><a href="javascript:printHSK()" class="blue-font"><b class="fa-print right-push-5"></b> Print</a></p>

			<div id="section-to-print" class="block-element sml-rounded-button noscroll top-push-20 bottom-push-20">
				<div id="cc-header" class="noshow bottom-push-30" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">Housekeeping Room State</h3>
				</div>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<th width="30px" align="center" class="grey-theme"><input type="checkbox" name="checker" id="checker" value="<?php echo $pgstart.'-'.$pglimit; ?>" class="checkbox-option-custom" title="Select All" onclick="checkboxes()" lang="u"></th>
						<th width="50px" align="center" class="grey-theme">&nbsp;</th>
						<th width="180px" align="center" class="box-border-thick-right">Room Number</th>
						<th width="150px" align="center" class="box-border-thick-right">Room Type</th>
						<th width="80px" align="center" class="box-border-thick-right">Status</th>
						<th width="150px" align="center" class="box-border-thick-right">Availability</th>
						<th width="250px" align="center" class="box-border-thick-right">Remarks</th>
						<th width="150px" align="center">HouseKeeper</th>
					</tr>
					<?php
						if(is_array($rm_type_data)) {
							
							$num=$pgstart; $g=""; $dataid=""; $hotelroomtype=""; $hotelblock=""; $hotelfloor="";
							$hsk_status=""; $avail_status=""; $this_remarks=""; $this_user=""; $this_hsk_status_color="";

							foreach ($rm_type_data as $rhsk_key => $rhsk_value) {
								
								$num += 1;
								$g = $num / 2;

								$dataid = $rhsk_value["id"];

								$additionalQuery="";

								$hotelroomtype = idget_data($tbL52,$rhsk_value["room_type_id"],'name');
								$hotelblock = idget_data($tbL49,$rhsk_value["blockid"],'name');
								$hotelfloor = idget_data($tbL50,$rhsk_value["floorid"],'name');

								$rs_query = array("roomid"=>$dataid);
								$rs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid,room_status_id,remarks,userid',$rs_query,'noarray');

								if(is_array($rs_data)) {
									
									if(isset($rs_data[0]) && $rs_data[0] >= 1) { $hsk_status=idget_data($tbL36,$rs_data[0],'legendname'); $this_hsk_status_color=idget_data($tbL36,$rs_data[0],'colorcode'); }
									else { $hsk_status=$default_housekeeping_legend; $this_hsk_status_color=$default_housekeeping_legend_color; }

									if(isset($rs_data[1]) && $rs_data[1] >= 1) { $avail_status=idget_data($tbL38,$rs_data[1],'legendname'); }
									else { $avail_status=$default_room_status_legend; }

									$this_remarks=$rs_data[2];

									if(isset($rs_data[3]) && $rs_data[3] >= 1) { $this_user=idget_data($tbL7,$rs_data[3],'staffname'); }
									else { $this_user="Not assigned"; }
								} else {
									$hsk_status=$default_housekeeping_legend;
									$this_hsk_status_color=$default_housekeeping_legend_color;
									$avail_status=$default_room_status_legend;
									$this_remarks="No remarks";
									$this_user="Not assigned";
								}


								$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

								if(isset($_SESSION['status_id']) && $_SESSION['status_id'] > 0) {
									if(isset($rs_data[0]) && ($rs_data[0] == $_SESSION['status_id'])) {
										?>
											<tr bgcolor="<?php echo $trcolor; ?>">
												<td width="30px" class="box-border-thick-right" align="center"><input type="checkbox" name="checkers[]" id="ck-<?php echo $num; ?>" value="<?php echo $rhsk_value['id']; ?>"></td>
												<td width="50px" class="box-border-thick-right" align="center"><?php echo $num; ?>.</td>
												<td width="180px" align="center" class="box-border-thick-right"><?php echo $rhsk_value["roomprefix"].$rhsk_value["roomnumber"].$rhsk_value["roomsuffix"]; ?> (<?php echo $hotelblock.','.$hotelfloor; ?>)</td>
												<td width="150px" align="center" class="box-border-thick-right"><?php echo $hotelroomtype; ?></td>
												<td width="80px" align="center" class="box-border-thick-right" style="color: <?php echo $this_hsk_status_color; ?>"><?php echo $hsk_status; ?></td>
												<td width="100px" align="center" class="box-border-thick-right"><?php echo $avail_status; ?></td>
												<td width="100px" align="center" class="box-border-thick-right dark-blue-font"><?php echo $this_remarks; ?></td>
												<td width="150px" align="center" class="box-border-thick-right royal-blue-font"><?php echo $this_user; ?></td>
											</tr>
										<?php
									}
								} else {
									?>
										<tr bgcolor="<?php echo $trcolor; ?>">
											<td width="30px" class="box-border-thick-right" align="center"><input type="checkbox" name="checkers[]" id="ck-<?php echo $num; ?>" value="<?php echo $rhsk_value['id']; ?>"></td>
											<td width="50px" class="box-border-thick-right" align="center"><?php echo $num; ?>.</td>
											<td width="180px" align="center" class="box-border-thick-right"><?php echo $rhsk_value["roomprefix"].$rhsk_value["roomnumber"].$rhsk_value["roomsuffix"]; ?> (<?php echo $hotelblock.','.$hotelfloor; ?>)</td>
											<td width="150px" align="center" class="box-border-thick-right"><?php echo $hotelroomtype; ?></td>
											<td width="80px" align="center" class="box-border-thick-right" style="color: <?php echo $this_hsk_status_color; ?>"><?php echo $hsk_status; ?></td>
											<td width="100px" align="center" class="box-border-thick-right"><?php echo $avail_status; ?></td>
											<td width="100px" align="center" class="box-border-thick-right dark-blue-font"><?php echo $this_remarks; ?></td>
											<td width="150px" align="center" class="box-border-thick-right royal-blue-font"><?php echo $this_user; ?></td>
										</tr>
									<?php
								}
							}
						}
					?>
				</table>
			</div>
			<?php

				//paginate this page

				$additionalQuery = $keywords;
				mysqli_data_check($tbL56,'(*)','');
				$totalcount = $numOfrows;

				$paginate = data_pagenation(50,0,$totalcount);
				if(isset($paginate) && !empty($paginate)) {
					echo $paginate;
				}

				//end of pagination

				$pageurl = 'housekeeping.php?logs='.$logs;

			?>
			<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>
		</form>
	</div>
</div>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion btscr" align="left">
	<div class="cs-width-400 white-theme pads20 bottom-push-30 left-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="hsk-header-notification" class="large red-font"></h4>
		<small id="hsk-message-notification" class="block-element top-push-10"></small>
	</div>
</div>

<?php
	if(isset($_POST['statusbutton']) && isset($_POST['checkers'])) {
		$rS=""; foreach($_POST['checkers'] as $r) { $rS .= $r.','; }
		?>
			<div class="fx-position-stick zind-2 motion fscr txp5-white" align="center">
				<div class="cs-height-100"></div>
				<div class="cs-width-350 pads20 box-border-thick xsml-rounded-button white-theme top-push-50 alignlt">
					<form action="" method="post" autocomplete="off">
						<h3 class="large">Housekeeping Status</h3><br>
						<div class="block-element bottom-push-10">
							<select name="hskstatus" id="hskstatus" required="required">
								<option value="" selected="selected">Choose to change housekeeping status</option>
								<?php echo $statuslists; ?>
							</select>
						</div>
						<div class="block-element bottom-push-10">
							<textarea name="remarks" id="remarks" placeholder="Enter remark for change of room status?"></textarea>
						</div>
						<input type="hidden" name="rooms" value="<?php echo $rS; ?>">
						<p class="top-pull-30">
							<input type="submit" name="applystatusbutton" value="Apply Changes" class="submit top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 dark-black-white-state sml-rounded-button"> &nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
						</p>
					</form>
				</div>
			</div>
		<?php
	}

	if(isset($_POST['assignbutton']) && isset($_POST['checkers'])) {
		$rS=""; foreach($_POST['checkers'] as $r) { $rS .= $r.','; }
		?>
			<div class="fx-position-stick zind-2 motion fscr txp5-white" align="center">
				<div class="cs-height-100"></div>
				<div class="cs-width-350 pads20 box-border-thick xsml-rounded-button white-theme top-push-50 alignlt">
					<form action="" method="post" autocomplete="off">
						<h3 class="large">Housekeeping Users</h3><br>
						<select name="assignuser" id="assignuser" required="required">
							<option value="" selected="selected">Choose to assign user</option>
							<?php echo $housekeepers; ?>
						</select>
						<input type="hidden" name="rooms" value="<?php echo $rS; ?>">
						<p class="top-pull-30">
							<input type="submit" name="applyassignbutton" value="Apply Changes" class="submit top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 dark-black-white-state sml-rounded-button"> &nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
						</p>
					</form>
				</div>
			</div>
		<?php
	}
?>


<script>

	function loadrooms(obj,ses) {
		
		var opt1,opt2,opt3,opt4;
		
		if(document.getElementById('roomtypelist').value != 'undefined') { opt1=document.getElementById('roomtypelist').value; } else { opt1=0; }
		if(document.getElementById('blocklist').value != 'undefined') { opt2=document.getElementById('blocklist').value; } else { opt2=0; }
		if(document.getElementById('floorlist').value != 'undefined') { opt3=document.getElementById('floorlist').value; } else { opt3=0; }
		if(document.getElementById('statuslist').value != 'undefined') { opt4=document.getElementById('statuslist').value; } else { opt4=0; }

		if(opt1 == 0) {

			objDisplay('notifybox');
			writeObjheader('hsk-header-notification','Notification!');
			writeObjheader('hsk-message-notification','Room type must be chosen for filtering');
			autohidePopupBox('notifybox',4000);

		} else {
			window.location.href = '?opt1='+opt1+'&opt2='+opt2+'&opt3='+opt3+'&opt4='+opt4;
		}
	}


	function checkboxes() {
		var chk = document.getElementById('checker');
		var checknow = chk.value;
		var i,r,slm = checknow.split('-');
		var totalcount = eval(slm[0]) + eval(slm[1]);
		
		if(chk.lang == 'u') {
			
			for (i=slm[0]; i<totalcount; i++) {
				r = eval(i) + 1;
				document.getElementById('ck-'+r).checked = true;
			}
			
			chk.lang = 'c';
		}
		else if(chk.lang == 'c') {
			
			for (i=slm[0]; i<totalcount; i++) {
				r = eval(i) + 1;
				document.getElementById('ck-'+r).checked = false;
			}
			
			chk.lang = 'u';
		}
	}

	function printHSK() {
		document.getElementById('cc-header').classList.remove('noshow');
		setTimeout(window.print(),1000);
		setTimeout(() => { document.getElementById('cc-header').classList.add('noshow'); },3000);
	}

</script>