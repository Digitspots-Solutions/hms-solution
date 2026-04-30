<?php $smdl = "housekeeping"; $logs = escape_data($_GET['logs']); ?>
<?php
	$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can put rooms in maintenance status. Select room type to continue
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['blockbutton']))
	{
		createDatabasetable($var_tbl_55); //create a table for this post

		$fieldset1 = escape_data($_POST['startdate']);
		$fieldset2 = escape_data($_POST['endate']);
		$fieldset3 = escape_data($_POST['blockstatus']);
		$fieldset4 = escape_data($_POST['remarks']);

		$fieldset5 = explode('-', $_POST['block-rooms-in']);


		$isdata = 0;

		foreach($fieldset5 as $roomid) {
			if($roomid != '') {
				$insert_dataproperty = array("roomid"=>$roomid,"fromdate"=>$fieldset1,"todate"=>$fieldset2,"blockstatus"=>$fieldset3,"detail"=>$fieldset4,"datelogged"=>$server_get_date);
				$insert_constrain = array("roomid"=>$roomid,"deletedata"=>0);
				$data_inserted = mysqli_data_insert($tbL57,$insert_dataproperty,$insert_constrain);

				if(isset($data_inserted) && $data_inserted == 2) {
					$isdata += 1;
				}
			}
		}

		if(isset($isdata) && $isdata >= 1) {
			
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently put rooms under maintenance","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Room maintenance setup was completed successfully</span>';
			$post_result .= '</div>';
		}

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$html_query_result = '';
	$html_query_result_f = '';
	$html_query_result_s = '';
	$to_block_rooms = '';
	$cur_room = ''; $r = ''; $blk = '';

	if(isset($_GET['ls']) && $_GET['ls'] >= 1) {
		
		$r = escape_data($_GET['ls']);
		$cur_room = idget_data($tbL52,$r,'name');

		$rm_query = array("room_type_id"=>$r,"deletedata"=>0,"roomstatus"=>1);
		$rm_data = mysqli_data_fetch($tbL56,'id,roomnumber,roomprefix',$rm_query,'array');

		
		foreach ($rm_data as $rm_key => $rm_value) {
			$html_query_result .= '<option value="'.$rm_value['id'].'">'.$rm_value['roomprefix'].$rm_value['roomnumber'].'</option>';
			$html_query_result_s .= '<option value="'.$rm_value['id'].'">'.$rm_value['roomprefix'].$rm_value['roomnumber'].'</option>';
			$blk .= $rm_value['id'].'-';
		}

		if(isset($_GET['t']) && $_GET['t'] == 'mmoveforward') {
			$html_query_result_f = $html_query_result_s;
			$to_block_rooms = $blk;
		}

		if(isset($_GET['ar']) && !empty($_GET['ar'])) {
			$to_block_rooms = $_GET['ar'];
			$selected_rooms = explode('-',$_GET['ar']);
			$room_1_dtl = ''; $room_2_dtl = '';

			foreach ($selected_rooms as $rm_id) {
				if($rm_id != '') {
					$room_1_dtl = idget_data($tbL56,$rm_id,'roomnumber');
					$room_2_dtl = idget_data($tbL56,$rm_id,'roomprefix');
					$html_query_result_f .= '<option value="'.$rm_id.'">'.$room_2_dtl.$room_1_dtl.'</option>';
				}
			}
		}
	}

?>

<div class="block-element" align="center">
	<div class="nc-width-40">
		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<div class="bottom-push-20 left-pull-10 alignlt">
				<h3 class="nomargin">Room Maintenance Status</h3>
			</div>
			<span class="block-element bottom-push-10">
				<select name="roomtypelist" id="roomtypelist" onchange="listrooms()">
					<?php 
						if(isset($cur_room) && !empty($cur_room)) {
							?><option value="<?php echo $r; ?>" selected="selected"><?php echo $cur_room; ?></option><?php
						} else {
							?><option value="" selected="selected">Select room type</option><?php
						}
						
						echo $roomtypes;
					?>
				</select>
			</span>
			<span class="block-element bottom-push-10">
				<div class="ln-display-box float-left nc-width-40">
					<small class="block-element dark-grey-font bottom-push-3">Available Rooms</small>
					<span class="block-element box-border-thick cs-height-200 sml-rounded-button noscroll">
						<select name="available-rooms[]" id="available-rooms" multiple="multiple" size="200" class="nc-height-100">
							<?php echo $html_query_result; ?>
						</select>
					</span>
				</div>
				<div class="ln-display-box float-left nc-width-20 top-pull-20 right-pull-5 left-pull-5 alignct">
					<small class="block-element bottom-push-20">move</small>
					<a href="javascript:void(0)" class="grey-theme sml-rounded-button box-border-thick top-pull-5 right-pull-20 bottom-pull-5 left-pull-20" onclick="moveselect('moveforward')" title="forward">
						<b class="fa-arrow-right nobold fa-sml-size"></b>
					</a>
					<small class="block-element bottom-push-15">
					</small>
					<a href="javascript:void(0)" class="grey-theme sml-rounded-button box-border-thick top-pull-5 right-pull-20 bottom-pull-5 left-pull-20" onclick="moveselect('movebackward')" title="backward">
						<b class="fa-arrow-left nobold fa-sml-size"></b>
					</a>
					<small class="block-element bottom-push-15">
					</small>
					<a href="javascript:void(0)" class="grey-theme sml-rounded-button box-border-thick top-pull-5 right-pull-20 bottom-pull-5 left-pull-20" onclick="mt_moveselect('mmoveforward')" title="multiple forward">
						<b class="fa-arrow-right nobold fa-sml-size"></b><b class="fa-arrow-right nobold fa-sml-size"></b>
					</a>
					<small class="block-element bottom-push-15">
					</small>
					<a href="javascript:void(0)" class="grey-theme sml-rounded-button box-border-thick top-pull-5 right-pull-20 bottom-pull-5 left-pull-20" onclick="mt_moveselect('mmovebackward')" title="multiple backward">
						<b class="fa-arrow-left nobold fa-sml-size"></b><b class="fa-arrow-left nobold fa-sml-size"></b>
					</a>
				</div>
				<div class="ln-display-box float-left nc-width-40">
					<small class="block-element dark-grey-font bottom-push-3">Block Rooms</small>
					<span class="block-element box-border-thick cs-height-200 sml-rounded-button noscroll">
						<select name="block-rooms" id="block-rooms" multiple="multiple" size="200" class="nc-height-100">
							<?php echo $html_query_result_f; ?>
						</select>
						<input type="hidden" name="block-rooms-in" id="blokc-rooms-in" value="<?php echo $to_block_rooms; ?>" required="required">
					</span>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="block-element bottom-push-10">
				<div class="ln-display-box float-left nc-width-45">
					<small class="block-element dark-grey-font bottom-push-3 alignlt">&nbsp; Start date</small>
					<input type="date" name="startdate" id="startdate" required="required">
				</div>
				<div class="ln-display-box float-right nc-width-45">
					<small class="block-element dark-grey-font bottom-push-3 alignlt">&nbsp; End date</small>
					<input type="date" name="endate" id="endate" required="required">
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="block-element bottom-push-10">
				<textarea name="remarks" id="remarks" placeholder="reason for maintenance work" required="required"></textarea>
			</span>
			<span class="block-element bottom-push-10">
				<select name="blockstatus" id="blockstatus" required="required">
					<option value="" selected="selected">Select Status</option>
					<option value="Under Maintenance">Under Maintenance</option>
					<option value="Hima Agent Quota">Hima Agent Quota</option>
				</select>
			</span>

			<br><br>

			<p align="center">
				<input type="submit" name="blockbutton" value="Apply Block" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 dark-black-white-state sml-rounded-button">
			</p>
		</form>
	</div>
</div>



<div class="block-element top-push-50 bottom-push-30 box-border-thick-top top-pull-30">
 	<span class="ln-display-box float-left">
		Note: here you can unblock all rooms under maintenance or thereabout
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=unblock" class="submit pads12 sml-rounded-button blue-theme white-font">
			UnBlock Room
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['blockbutton']) && isset($_POST['checkers']))
	{
		createDatabasetable($var_tbl_55); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);

		$isdata = 0;

		foreach($_POST['checkers'] as $roomid) {

			$insert_dataproperty = array("roomid"=>$roomid,"fromdate"=>$fieldset1,"todate"=>$fieldset2,"blockstatus"=>$fieldset3,"detail"=>$fieldset4,"datelogged"=>$server_get_date);
			$insert_constrain = array("roomid"=>$roomid,"deletedata"=>0);
			$data_inserted = mysqli_data_insert($tbL57,$insert_dataproperty,$insert_constrain);

			if(isset($data_inserted) && $data_inserted == 2) {
				$isdata += 1;
			}
		}

		if(isset($isdata) && $isdata >= 1) {
			
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Initialize room blocking request","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Request was sent successfully</span>';
			$post_result .= '</div>';
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'block')
	{
		$additionalQuery = "";
		$blocks = select_dt_fetch('',0,$tbL49,'id','name');
		$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');

		?>
			<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
				<div class="block-element pads20 sml-rounded-button box-border-thick">
					<span class="ln-display-box float-left nc-width-10 top-pull-10">
						<b>Search By:</b> 
					</span>
					<span class="ln-display-box float-left nc-width-20 right-push-10">
						<select name="blocklist" id="blocklist" onchange="getdata('floorlist','eget-block-floors-list','blocklist','dropbox');">
							<option value="" selected="selected">Blocks</option>
							<?php echo $blocks; ?>
						</select>
					</span>
					<span class="ln-display-box float-left nc-width-20 right-push-10">
						<select name="floorlist" id="floorlist">
							<option value="" selected="selected">Floors</option>
						</select>
					</span>
					<span class="ln-display-box float-left nc-width-20 right-push-10">
						<select name="roomtypelist" id="roomtypelist">
							<option value="" selected="selected">Room Types</option>
							<?php echo $roomtypes; ?>
						</select>
					</span>
					<span class="ln-display-box float-right nc-width-20">
						<input type="submit" name="searchbutton" value=" Search " class="submit pads10 black-white-state sml-rounded-button">
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
			</form>
		<?php

		echo $post_result;

		if(isset($_POST['searchbutton']))
		{
			$block_query = " AND blockid = ".$_POST['blocklist'];
			
			if(isset($_POST['floorlist']) && !empty($_POST['floorlist'])) { $floor_query = " AND floorid = ".$_POST['floorlist']; }
			else { $floor_query = ""; }

			if(isset($_POST['roomtypelist']) && !empty($_POST['roomtypelist'])) { $roomtype_query = " AND room_type_id = ".$_POST['roomtypelist']; }
			else { $roomtype_query = ""; }

			$keywords=$block_query.$floor_query.$roomtype_query;
		

			$additionalQuery = $keywords;

			$dataproperty = "id,blockid,floorid,room_type_id,roomprefix,roomnumber,roomsuffix,roomstatus";
			$constrain = array("deletedata"=>0);
			$row = "array";

			$dataCollect = mysqli_data_fetch($tbL56,$dataproperty,$constrain,$row);

			if(is_array($dataCollect))
			{
				$thproperty = array("noth","room number","room type","hotel block","hotel floor","enoth");
				$tcount = count($thproperty);

				$processbar="'processbar'"; $hobj="'rblock'"; $form="'buform'";
				
				$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')" id="buform">';
				$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
				$htmlresult .= '<span class="ln-display-box float-left right-push-10 top-push-5">';
				$htmlresult .= '<div class="cs-width-20 cs-height-20" style="background: #99bbff">&nbsp;</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-left right-push-30 top-push-5">';
				$htmlresult .= '<small>Blocked Rooms</small>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-left right-push-5 top-push-5">';
				$htmlresult .= '<div class="cs-width-20 cs-height-20" style="background: #F9F9F9">&nbsp;</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-left right-push-10 top-push-5">';
				$htmlresult .= '<div class="cs-width-20 cs-height-20" style="background: #D1E0ED">&nbsp;</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-left right-push-10 top-push-5">';
				$htmlresult .= '<small>Available Rooms</small>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-60">';
				
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-30">';
				$htmlresult .= '<input type="button" value=" Apply Block " class="submit pads10 black-white-state sml-rounded-button" onclick="objDisplay('.$hobj.')">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element new-line-space"></span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
				$htmlresult .= '<table cellpadding="0" cellspacing="0">';
				$htmlresult .= '<tr>';
				
				$thu=0; $uclass="";
				
				foreach($thproperty as $th)
				{
					$thu += 1;
					
					if($tcount == $thu) { $uclass=''; }
					else { $uclass='class="box-border-thick-right"'; }
					
					if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
					elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
					else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
				}
				
				$htmlresult .= '</tr>';
				
				$num=$pgstart; $g=""; $dataid=""; $hotelroomtype=""; $hotelblock=""; $hotelfloor=""; $trcolor=""; $checker="";

				foreach($dataCollect as $theader => $tdata)
				{
					$num += 1;
					$g = $num / 2;

					$dataid = $tdata["id"];

					$additionalQuery = "";

					$hotelroomtype = idget_data($tbL52,$tdata["room_type_id"],'name');
					$hotelblock = idget_data($tbL49,$tdata["blockid"],'name');
					$hotelfloor = idget_data($tbL50,$tdata["floorid"],'name');

					if($tdata["roomstatus"] == 0) {
						$trcolor="#99bbff";
						$checker=' disabled="disabled"';
					} else {
						$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
						$checker='';
					}

					$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
					$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["roomprefix"].$tdata["roomnumber"].$tdata["roomsuffix"].'</td>';
					$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$hotelroomtype.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$hotelblock.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$hotelfloor.'</td>';
					$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"'.$checker.'></td>';
					$htmlresult .= '</tr>';
				}
				
				$htmlresult .= '</table>';
				$htmlresult .= '</div>';

				$field1="'fieldset1'"; $field2="'fieldset2'"; $type1="'date'";

				$htmlresult .= '<div id="rblock" class="noshow cs-width-400 fx-position-stick zind-3 white-theme obj-shadow sml-rounded-button pads20" style="margin-top: -70%; margin-left: 30%">';
				$htmlresult .= '<h4 class="large">Provide information to complete block process. You will be reminded when due</h4><br>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Start block from" required="required" onclick="htmlFormField('.$field1.','.$type1.')">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" placeholder="End block by" required="required" onclick="htmlFormField('.$field2.','.$type1.')">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required">';
				$htmlresult .= '<option value="" selected="selected">Block Status</option>';
				$htmlresult .= '<option value="Under Maintenance">Under Maintenance</option>';
				$htmlresult .= '<option value="Hima Agent Quota">Hima Agent Quota</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<textarea name="fieldset4" id="fieldset4" placeholder="Write comment (if any?)"></textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element top-push-10 alignct">';
				$htmlresult .= '<input type="submit" name="blockbutton" value="Apply" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('.$hobj.'); htmlFormReset('.$form.')">Cancel</a>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '</form>';
			}
			else
			{
				$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">No record found</small></div>';
			}
		}
		else
		{
			$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">Get list of rooms available using search form</small></div>';
		}

		echo $htmlresult;
	}


	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['unblockbutton']))
	{
		$additionalQuery = "";

		foreach($_POST['checkers'] as $unblockid) {

			$insert_constrain = array("id"=>$unblockid);
			$insert_dataproperty = array("deletedata"=>1);
			mysqli_data_update($tbL57,$insert_dataproperty,$insert_constrain); //update the block status

			$ctrl_data = mysqli_data_fetch($tbL57,'roomid',$insert_constrain,'noarray'); //get room id

			$insert_constrain_2 = array("id"=>$ctrl_data[0]);
			$insert_dataproperty_2 = array("roomstatus"=>1);
			mysqli_data_update($tbL56,$insert_dataproperty_2,$insert_constrain_2);
		}

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Unblock rooms","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">Room unblocking request was completed successfully</span>';
		$post_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'unblock')
	{
		?>
			<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
				<div class="block-element pads20 sml-rounded-button box-border-thick">
					<small class="block-element bottom-push-15 dark-grey-font">Use select criteria to start unblock process or view blocked rooms</small>
					
					<span class="ln-display-box float-left nc-width-20">
						<div class="ln-display-box float-left nc-width-30 top-pull-10 alignrt">From &nbsp; </div>
						<div class="ln-display-box float-left nc-width-70"><input type="date" name="fromdatelog" value="<?php echo $server_get_date; ?>"></div>
						<div class="block-element new-line-space"></div>
					</span>
					<span class="ln-display-box float-left nc-width-20 right-push-20">
						<div class="ln-display-box float-left nc-width-30 top-pull-10 alignrt">To &nbsp; </div>
						<div class="ln-display-box float-left nc-width-70"><input type="date" name="todatelog" value="<?php echo $server_get_date; ?>"></div>
						<div class="block-element new-line-space"></div>
					</span>
					<span class="ln-display-box float-left nc-width-20 right-push-20">
						<select name="blockstatus" id="blockstatus" required="required">
							<option value="" selected="selected">Status</option>
							<option value="All">All</option>
							<option value="Under Maintenance">Under Maintenance</option>
							<option value="Hima Agent Quota">Hima Agent Quota</option>
						</select>
					</span>
					<span class="ln-display-box float-right nc-width-20">
						<input type="submit" name="listbutton" value=" Search " class="submit pads10 black-white-state sml-rounded-button">
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
			</form>
		<?php

		echo $post_result;

		if(isset($_POST['listbutton']))
		{
			if(isset($_POST['blockstatus']) && $_POST['blockstatus'] == 'All') { $block_status_query = ""; } else { $block_status_query = " AND blockstatus = ".$_POST['blockstatus']; }
			
			if((isset($_POST['fromdatelog']) && isset($_POST['todatelog'])) && (!empty($_POST['fromdatelog']) && !empty($_POST['todatelog']))) { $date_query = " AND (datelogged BETWEEN '".$_POST['fromdatelog']."' AND '".$_POST['todatelog']."')"; } else { $date_query = ""; }

			$keywords=$block_status_query.$date_query;
		
			$additionalQuery = $keywords;

			$dataproperty = "id,roomid,fromdate,todate,blockstatus,detail";
			$constrain = array("deletedata"=>0);
			$row = "array";

			$dataCollect = mysqli_data_fetch($tbL57,$dataproperty,$constrain,$row);

			if(is_array($dataCollect))
			{
				$thproperty = array("noth","hotel","room number","date from","date to","status","comment","enoth");
				$tcount = count($thproperty);

				$processbar="'processbar'"; $hobj="'rblock'"; $form="'buform'";
				
				$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')" id="buform">';
				$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
				$htmlresult .= '<span class="ln-display-box float-left">';
				$htmlresult .= '<input type="submit" name="unblockbutton" value=" Apply UnBlock " class="submit pads10 black-white-state sml-rounded-button">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element new-line-space"></span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
				$htmlresult .= '<table cellpadding="0" cellspacing="0">';
				$htmlresult .= '<tr>';
				
				$thu=0; $uclass="";
				
				foreach($thproperty as $th)
				{
					$thu += 1;
					
					if($tcount == $thu) { $uclass=''; }
					else { $uclass='class="box-border-thick-right"'; }
					
					if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
					elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
					else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
				}
				
				$htmlresult .= '</tr>';
				
				$num=$pgstart; $g=""; $dataid=""; $hotelroomtype=""; $hotelblock=""; $hotelfloor=""; $roomid=""; $rprefix=""; $rnumb=""; $rsuffix="";

				foreach($dataCollect as $theader => $tdata)
				{
					$num += 1;
					$g = $num / 2;

					$dataid = $tdata["id"];
					$roomid = $tdata["roomid"];

					$additionalQuery = "";

					$from_room_tbl = array("id"=>$roomid);
					$room_data_clt = mysqli_data_fetch($tbL56,'room_type_id,blockid,floorid,roomprefix,roomnumber,roomsuffix',$from_room_tbl,'noarray');

					$hotelroomtype = idget_data($tbL52,$room_data_clt[0],'name');
					$hotelblock = idget_data($tbL49,$room_data_clt[1],'name');
					$hotelfloor = idget_data($tbL50,$room_data_clt[2],'name');
					$rprefix = $room_data_clt[3];
					$rnumb = $room_data_clt[4];
					$rsuffix = $room_data_clt[5];

				
					$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
					$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
					$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
					$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$hotelblock.' - '.$hotelfloor.' - '.$hotelroomtype.'</td>';
					$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$rprefix.$rnumb.$rsuffix.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.date("d/m/Y",strtotime($tdata["fromdate"])).'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.date("d/m/Y",strtotime($tdata["todate"])).'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["blockstatus"].'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["detail"].'</td>';
					$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
					$htmlresult .= '</tr>';
				}
				
				$htmlresult .= '</table>';
				$htmlresult .= '</div>';
				$htmlresult .= '</form>';
			}
			else
			{
				$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">No record found</small></div>';
			}
		}
		
		echo $htmlresult;
	}

?>


<script>
	
	function listrooms() {
		var r = document.getElementById('roomtypelist').value;
		objDisplay('processbar');
		window.location.href = '?logs=<?php echo $logs; ?>&ls='+r;
	}

	function moveselect(str) {
		var r = document.getElementById('roomtypelist').value;
		var ar = document.getElementById('available-rooms').value;
		var rr = document.getElementById('block-rooms').value;
		
		if(str == 'moveforward') {
			if(sessionStorage.getItem('srs') !== null) {
				var i,err=0,ls=sessionStorage.getItem('srs').split('-');
				for(i=0; i < ls.length; i++) { if(eval(ar) == eval(ls[i])) { err=1; } }
				if(err == 0) { var ad = sessionStorage.getItem('srs')+'-'+ar; }
				else { var ad = sessionStorage.getItem('srs'); }
			} else {
				var ad = ar;
			}
		} else if(str == 'movebackward') {
			if(rr !== null && rr != 'undefined') {
				var i,nsrs='',ls=sessionStorage.getItem('srs').split('-'),tls=ls.length,cma=1;
				for(i=0; i < tls; i++) { if(eval(rr) != eval(ls[i])) { cma += 1; nsrs += ls[i]; if(cma<tls) { nsrs +='-'; } } }
				var ad = nsrs;
			}
		}

		//console.log(ad);
		
		if(ad != 'undefined') { sessionStorage.setItem('srs',ad); }

		//console.log(r);
		objDisplay('processbar');
		window.location.href = '?logs=<?php echo $logs; ?>&ls='+r+'&ar='+ad;
	}

	function mt_moveselect(str) {
		var r = document.getElementById('roomtypelist').value;
		objDisplay('processbar');
		if(sessionStorage.getItem('srs') !== null) { sessionStorage.removeItem('srs'); }
		window.location.href = '?logs=<?php echo $logs; ?>&ls='+r+'&t='+str;
	}

</script>