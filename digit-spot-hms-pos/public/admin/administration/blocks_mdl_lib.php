<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new block by clicking <u>new block</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Block
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
		createDatabasetable($var_tbl_47); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		
		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)));
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL49,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new building/block","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

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
							<h3 class="nomargin">Creating New Block</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
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
		
		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)));
		$insert_constrain = array("id"=>$fieldset2);
		$data_inserted = mysqli_data_update($tbL49,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit building/block","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['ineditbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = $_POST['floors'];
		$fieldset3 = $_POST['floorctrl'];

		$flr = 0;
		
		for($f=0; $f <= count($fieldset3); $f++) {
			$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset2[$f])));
			$insert_constrain = array("id"=>$fieldset3[$f]);
			$data_inserted = mysqli_data_update($tbL50,$insert_dataproperty,$insert_constrain);

			if(isset($data_inserted) && $data_inserted == 2) { $flr = $flr + 1; }
		}
		
		
		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($flr) && $flr >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit building/block floor","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['floorbutton']))
	{
		createDatabasetable($var_tbl_48); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		
		$insert_dataproperty = array("blockid"=>$fieldset2,"name"=>ucwords(strtolower($fieldset1)));
		$insert_constrain = array("blockid"=>$fieldset2,"name"=>ucwords(strtolower($fieldset1)));
		$data_inserted = mysqli_data_insert($tbL50,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Add new floor to created building/block","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">New floor was added successfully</span>';
		} else {
			$update_result .= '<span class="red-font">Floor is already created!</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL49,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from blocks list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND name LIKE '".escape_data($_POST['search'])."%'";
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

	$dataproperty = "id,name";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL49,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","block","floor","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		/*$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';*/
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $numoffloor=0; $disableCheck=""; $blockFloor=''; $editableFloor='';

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];
			$balance = $tdata["balance"];

			$block_key = array("blockid"=>$dataid);
			$block_lib = mysqli_data_fetch($tbL50,'id,name',$block_key,'array');
			if(is_array($block_lib)) {
				$myblockFloors=''; $myeditableFloors=''; $floorid='';
				foreach ($block_lib as $blk_key => $blk_value) {
					$floorid = $blk_value["id"];
					$myblockFloors .='<option value="">'.$blk_value["name"].'</option>';
					$myeditableFloors .='<span class="block-element bottom-push-10"><input type="text" name="floors[]" value="'.$blk_value["name"].'" required="required"><input type="hidden" name="floorctrl[]" value="'.$floorid.'"></span>';
				}
				$blockFloor=$myblockFloors;
				$editableFloor=$myeditableFloors;
				$numoffloor = 1;
			} else {
				$blockFloor='<option value="">No available floors</option>';
				$editableFloor=$myeditableFloors;
				$numoffloor = 0;
			}

			if(isset($numoffloor) && $numoffloor >= 1) {
				$disableCheck=' disabled="disabled"';
			} else {
				$disableCheck='';
			}

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["name"].'<small class="block-element top-push-3 bottom-push-5 ft-xxsml-size">(<a href="?logs='.$logs.'&addfloor='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="royal-blue-font">Add Floor</a>)</small></td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right"><span class="ln-display-box float-left nc-width-60 left-pull-20"><select>'.$blockFloor.'</select></span><span class="ln-display-box float-right nc-width-30 top-pull-10">';
			
			if(isset($numoffloor) && $numoffloor >= 1) {
				$htmlresult .= '<small class="block-element top-push-3 bottom-push-5 ft-xxsml-size">(<a href="?logs='.$logs.'&editfloor='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="royal-blue-font">Edit Floors</a>)</small>';
			} else {
				$htmlresult .= '&nbsp;';
			}

			$htmlresult .= '</span><span class="block-element new-line-space"></span></td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"'.$disableCheck.'></td>';
			$htmlresult .= '</tr>';

			#----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['addfloor']) && $_GET['addfloor'] >= 1) && ($_GET['addfloor'] == $dataid))
			{
				$fieldset = escape_data($_GET['addfloor']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Adding floor to <u>'.$tdata["name"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Floor</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset2" id="fieldset2" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="floorbutton" value="Add Floor" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['editfloor']) && $_GET['editfloor'] >= 1) && ($_GET['editfloor'] == $dataid))
			{
				$fieldset = escape_data($_GET['editfloor']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Editing floors at <u>'.$tdata["name"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= $editableFloor;
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset1" id="fieldset1" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="ineditbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Block</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Enter Block Name</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" value="'.$tdata["name"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset2" id="fieldset2" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL49,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>