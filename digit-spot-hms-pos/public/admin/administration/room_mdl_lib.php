<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: all created rooms are listed below. To create more rooms, use <u>bulk room create</u> link
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		
		$insert_dataproperty = array("roomprefix"=>$fieldset1,"roomnumber"=>$fieldset2,"roomsuffix"=>$fieldset3,"room_type_id"=>$fieldset4,"blockid"=>$fieldset5,"floorid"=>$fieldset6,"detail"=>$fieldset7,"extn"=>$fieldset8);
		$insert_constrain = array("id"=>$fieldset9);
		$data_inserted = mysqli_data_update($tbL56,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit room details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if(isset($_POST['searchbutton'])) {
		
		if(isset($_POST['blocklist']) && !empty($_POST['blocklist'])) { $block_query = " AND blockid = ".$_POST['blocklist']; }
		else { $block_query = ""; }
		
		if(isset($_POST['floorlist']) && !empty($_POST['floorlist'])) { $floor_query = " AND floorid = ".$_POST['floorlist']; }
		else { $floor_query = ""; }

		if(isset($_POST['roomtypelist']) && !empty($_POST['roomtypelist'])) { $roomtype_query = " AND room_type_id = ".$_POST['roomtypelist']; }
		else { $roomtype_query = ""; }

		$keywords=$block_query.$floor_query.$roomtype_query;

	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 30;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,blockid,floorid,room_type_id,roomprefix,roomnumber,roomsuffix,extn,detail";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL56,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","room number","room type","description","hotel block","hotel floor","extension","noth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		
		$additionalQuery = "";
		$blocks = select_dt_fetch('',0,$tbL49,'id','name');
		$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');

		$selectsrc="'blocklist'"; $selectdest="'floorlist'"; $selectqry="'eget-block-floors-list'"; $selecttype="'dropbox'";

		$htmlresult .= '<span class="ln-display-box float-left nc-width-10 top-pull-10">';
		$htmlresult .= '<b>Search By:</b> ';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="ln-display-box float-left nc-width-20 right-push-10">';
		$htmlresult .= '<select name="blocklist" id="blocklist" onchange="getdata('.$selectdest.','.$selectqry.','.$selectsrc.','.$selecttype.');"><option value="" selected>Blocks</option>'.$blocks.'</select>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="ln-display-box float-left nc-width-20 right-push-10">';
		$htmlresult .= '<select name="floorlist" id="floorlist"><option value="" selected>Floors</option></select>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="ln-display-box float-left nc-width-20 right-push-10">';
		$htmlresult .= '<select name="roomtypelist" id="roomtypelist"><option value="" selected>Room Types</option>'.$roomtypes.'</select>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-20">';
		$htmlresult .= '<input type="submit" name="searchbutton" value=" Search " class="submit pads10 black-white-state sml-rounded-button">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $hotelroomtype=""; $hotelblock=""; $hotelfloor="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$hotelroomtype = idget_data($tbL52,$tdata["room_type_id"],'name');
			$hotelblock = idget_data($tbL49,$tdata["blockid"],'name');
			$hotelfloor = idget_data($tbL50,$tdata["floorid"],'name');

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["roomprefix"].$tdata["roomnumber"].$tdata["roomsuffix"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$hotelroomtype.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["detail"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$hotelblock.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$hotelfloor.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["extn"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$selectsrc="'fieldset5'"; $selectdest="'fieldset6'"; $selectqry="'eget-block-floors-list'"; $selecttype="'dropbox'";

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Room</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-7 dark-grey-font left-pull-5">Room Number</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20">';
				$htmlresult .= '<h4 class="large">&nbsp; Prefix</h4>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" value="'.$tdata["roomprefix"].'" required>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20">';
				$htmlresult .= '<h4 class="large">&nbsp; No.</h4>';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" value="'.$tdata["roomnumber"].'" required>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20">';
				$htmlresult .= '<h4 class="large">&nbsp; Suffix</h4>';
				$htmlresult .= '<input type="text" name="fieldset3" id="fieldset3" value="'.$tdata["roomsuffix"].'">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Type</small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required">';
				$htmlresult .= '<option value="'.$tdata["room_type_id"].'" selected="selected">'.$hotelroomtype.'</option>';
				$htmlresult .= $roomtypes;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Block</small>';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required" onchange="getdata('.$selectdest.','.$selectqry.','.$selectsrc.','.$selecttype.');">';
				$htmlresult .= '<option value="'.$tdata["blockid"].'" selected="selected">'.$hotelblock.'</option>';
				$htmlresult .= $blocks;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Floor Number</small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				$htmlresult .= '<option value="'.$tdata["floorid"].'" selected="selected">'.$hotelfloor.'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Description</small>';
				$htmlresult .= '<textarea name="fieldset7" id="fieldset7" placeholder="Enter description">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Extension</small>';
				$htmlresult .= '<input type="text" name="fieldset8" id="fieldset8" placeholder="Enter extension" value="'.$tdata["extn"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset9" id="fieldset9" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = $keywords;
	mysqli_data_check($tbL56,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>