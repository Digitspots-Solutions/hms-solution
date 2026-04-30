<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new shift by clicking <u>new shift</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Shift
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

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_18); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);

		
		if($fieldset2 != $fieldset3) {

			$startime = explode("/",$fieldset2);
			$endtime = explode("/",$fieldset3);

			$insert_dataproperty = array("shiftname"=>ucwords(strtolower($fieldset1)),"startime"=>$startime[0],"startimelabel"=>$startime[1],"endtime"=>$endtime[0],"endtimelabel"=>$endtime[1]);
			$insert_constrain = "";
			$data_inserted = mysqli_data_insert($tbL20,$insert_dataproperty,$insert_constrain);

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

			if(isset($data_inserted) && $data_inserted == 2)
			{
				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new shift time","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$post_result .= '<span class="red-font">New entry was saved successfully</span>';
			}

			$post_result .= '</div>';
		} else {
			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Error: Invalid selection of same time!</span>';
			$post_result .= '</div>';
		}

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Shift</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter shift name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset2" id="fieldset2" required="required">
								<option value="" selected="selected">Start Time</option>
								<option value="00:00:00/12:00 AM">12:00 AM</option>
								<option value="01:00:00/01:00 AM">01:00 AM</option>
								<option value="02:00:00/02:00 AM">02:00 AM</option>
								<option value="03:00:00/03:00 AM">03:00 AM</option>
								<option value="04:00:00/04:00 AM">04:00 AM</option>
								<option value="05:00:00/05:00 AM">05:00 AM</option>
								<option value="06:00:00/06:00 AM">06:00 AM</option>
								<option value="07:00:00/07:00 AM">07:00 AM</option>
								<option value="08:00:00/08:00 AM">08:00 AM</option>
								<option value="09:00:00/09:00 AM">09:00 AM</option>
								<option value="10:00:00/10:00 AM">10:00 AM</option>
								<option value="11:00:00/11:00 AM">11:00 AM</option>
								<option value="12:00:00/12:00 PM">12:00 PM</option>
								<option value="13:00:00/01:00 PM">01:00 PM</option>
								<option value="14:00:00/02:00 PM">02:00 PM</option>
								<option value="15:00:00/03:00 PM">03:00 PM</option>
								<option value="16:00:00/04:00 PM">04:00 PM</option>
								<option value="17:00:0005:00 PM">05:00 PM</option>
								<option value="18:00:00/06:00 PM">06:00 PM</option>
								<option value="19:00:00/07:00 PM">07:00 PM</option>
								<option value="20:00:00/08:00 PM">08:00 PM</option>
								<option value="21:00:00/09:00 PM">09:00 PM</option>
								<option value="22:00:00/10:00 PM">10:00 PM</option>
								<option value="23:00:00/11:00 PM">11:00 PM</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset3" id="fieldset3" required="required">
								<option value="" selected="selected">End Time</option>
								<option value="00:00:00/12:00 AM">12:00 AM</option>
								<option value="01:00:00/01:00 AM">01:00 AM</option>
								<option value="02:00:00/02:00 AM">02:00 AM</option>
								<option value="03:00:00/03:00 AM">03:00 AM</option>
								<option value="04:00:00/04:00 AM">04:00 AM</option>
								<option value="05:00:00/05:00 AM">05:00 AM</option>
								<option value="06:00:00/06:00 AM">06:00 AM</option>
								<option value="07:00:00/07:00 AM">07:00 AM</option>
								<option value="08:00:00/08:00 AM">08:00 AM</option>
								<option value="09:00:00/09:00 AM">09:00 AM</option>
								<option value="10:00:00/10:00 AM">10:00 AM</option>
								<option value="11:00:00/11:00 AM">11:00 AM</option>
								<option value="12:00:00/12:00 PM">12:00 PM</option>
								<option value="13:00:00/01:00 PM">01:00 PM</option>
								<option value="14:00:00/02:00 PM">02:00 PM</option>
								<option value="15:00:00/03:00 PM">03:00 PM</option>
								<option value="16:00:00/04:00 PM">04:00 PM</option>
								<option value="17:00:0005:00 PM">05:00 PM</option>
								<option value="18:00:00/06:00 PM">06:00 PM</option>
								<option value="19:00:00/07:00 PM">07:00 PM</option>
								<option value="20:00:00/08:00 PM">08:00 PM</option>
								<option value="21:00:00/09:00 PM">09:00 PM</option>
								<option value="22:00:00/10:00 PM">10:00 PM</option>
								<option value="23:00:00/11:00 PM">11:00 PM</option>
							</select>
						</span>
						
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);

		if($fieldset2 != $fieldset3) {
			
			$startime = explode("/",$fieldset2);
			$endtime = explode("/",$fieldset3);

			$insert_dataproperty = array("shiftname"=>ucwords(strtolower($fieldset1)),"startime"=>$startime[0],"startimelabel"=>$startime[1],"endtime"=>$endtime[0],"endtimelabel"=>$endtime[1]);
			$insert_constrain = array("id"=>$fieldset4);
			$data_inserted = mysqli_data_update($tbL20,$insert_dataproperty,$insert_constrain);

			$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

			if(isset($data_inserted) && $data_inserted == 2)
			{
				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit shift time","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$update_result .= '<span class="red-font">Changes were added successfully</span>';
			}

			$update_result .= '</div>';
		} else {
			$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$update_result .= '<span class="red-font">Error: Invalid selection of same time!</span>';
			$update_result .= '</div>';
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL20,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change shift status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL20,$usr_datasets,$usr_key);

			if(isset($del) && $del == 2) {
				$data_deleted += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_deleted) && $data_deleted == 0)
		{
			$post_result .= '<span class="red-font">Unable to remove data. Try again</span>';
		}
		elseif(isset($data_deleted) && $data_deleted >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove shift time","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND shiftname LIKE '".escape_data($_POST['search'])."%'";
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

	$dataproperty = "id,shiftname,startime,startimelabel,endtime,endtimelabel,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL20,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","shift name","start time","end time","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by shift name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
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
		
		$num=$pgstart; $g=""; $dataid="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["shiftname"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["startimelabel"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["endtimelabel"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["shiftname"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Shift Name</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter shift name" value="'.$tdata["shiftname"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Start Time</small>';
				$htmlresult .= '<select name="fieldset2" id="fieldset2" required="required">';
				$htmlresult .= '<option value="'.$tdata["startime"].'/'.$tdata["startimelabel"].'" selected="selected">'.$tdata["startimelabel"].'</option>';
				$htmlresult .= '<option value="00:00:00/12:00 AM">12:00 AM</option>';
				$htmlresult .= '<option value="01:00:00/01:00 AM">01:00 AM</option>';
				$htmlresult .= '<option value="02:00:00/02:00 AM">02:00 AM</option>';
				$htmlresult .= '<option value="03:00:00/03:00 AM">03:00 AM</option>';
				$htmlresult .= '<option value="04:00:00/04:00 AM">04:00 AM</option>';
				$htmlresult .= '<option value="05:00:00/05:00 AM">05:00 AM</option>';
				$htmlresult .= '<option value="06:00:00/06:00 AM">06:00 AM</option>';
				$htmlresult .= '<option value="07:00:00/07:00 AM">07:00 AM</option>';
				$htmlresult .= '<option value="08:00:00/08:00 AM">08:00 AM</option>';
				$htmlresult .= '<option value="09:00:00/09:00 AM">09:00 AM</option>';
				$htmlresult .= '<option value="10:00:00/10:00 AM">10:00 AM</option>';
				$htmlresult .= '<option value="11:00:00/11:00 AM">11:00 AM</option>';
				$htmlresult .= '<option value="12:00:00/12:00 PM">12:00 PM</option>';
				$htmlresult .= '<option value="13:00:00/01:00 PM">01:00 PM</option>';
				$htmlresult .= '<option value="14:00:00/02:00 PM">02:00 PM</option>';
				$htmlresult .= '<option value="15:00:00/03:00 PM">03:00 PM</option>';
				$htmlresult .= '<option value="16:00:00/04:00 PM">04:00 PM</option>';
				$htmlresult .= '<option value="17:00:00/05:00 PM">05:00 PM</option>';
				$htmlresult .= '<option value="18:00:00/06:00 PM">06:00 PM</option>';
				$htmlresult .= '<option value="19:00:00/07:00 PM">07:00 PM</option>';
				$htmlresult .= '<option value="20:00:00/08:00 PM">08:00 PM</option>';
				$htmlresult .= '<option value="21:00:00/09:00 PM">09:00 PM</option>';
				$htmlresult .= '<option value="22:00:00/10:00 PM">10:00 PM</option>';
				$htmlresult .= '<option value="23:00:00/11:00 PM">11:00 PM</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">End Time</small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required">';
				$htmlresult .= '<option value="'.$tdata["endtime"].'/'.$tdata["endtimelabel"].'" selected="selected">'.$tdata["endtimelabel"].'</option>';
				$htmlresult .= '<option value="00:00:00/12:00 AM">12:00 AM</option>';
				$htmlresult .= '<option value="01:00:00/01:00 AM">01:00 AM</option>';
				$htmlresult .= '<option value="02:00:00/02:00 AM">02:00 AM</option>';
				$htmlresult .= '<option value="03:00:00/03:00 AM">03:00 AM</option>';
				$htmlresult .= '<option value="04:00:00/04:00 AM">04:00 AM</option>';
				$htmlresult .= '<option value="05:00:00/05:00 AM">05:00 AM</option>';
				$htmlresult .= '<option value="06:00:00/06:00 AM">06:00 AM</option>';
				$htmlresult .= '<option value="07:00:00/07:00 AM">07:00 AM</option>';
				$htmlresult .= '<option value="08:00:00/08:00 AM">08:00 AM</option>';
				$htmlresult .= '<option value="09:00:00/09:00 AM">09:00 AM</option>';
				$htmlresult .= '<option value="10:00:00/10:00 AM">10:00 AM</option>';
				$htmlresult .= '<option value="11:00:00/11:00 AM">11:00 AM</option>';
				$htmlresult .= '<option value="12:00:00/12:00 PM">12:00 PM</option>';
				$htmlresult .= '<option value="13:00:00/01:00 PM">01:00 PM</option>';
				$htmlresult .= '<option value="14:00:00/02:00 PM">02:00 PM</option>';
				$htmlresult .= '<option value="15:00:00/03:00 PM">03:00 PM</option>';
				$htmlresult .= '<option value="16:00:00/04:00 PM">04:00 PM</option>';
				$htmlresult .= '<option value="17:00:00/05:00 PM">05:00 PM</option>';
				$htmlresult .= '<option value="18:00:00/06:00 PM">06:00 PM</option>';
				$htmlresult .= '<option value="19:00:00/07:00 PM">07:00 PM</option>';
				$htmlresult .= '<option value="20:00:00/08:00 PM">08:00 PM</option>';
				$htmlresult .= '<option value="21:00:00/09:00 PM">09:00 PM</option>';
				$htmlresult .= '<option value="22:00:00/10:00 PM">10:00 PM</option>';
				$htmlresult .= '<option value="23:00:00/11:00 PM">11:00 PM</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset4" id="fieldset4" value="'.$fieldset.'">';
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

	$additionalQuery = "";
	mysqli_data_check($tbL20,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>