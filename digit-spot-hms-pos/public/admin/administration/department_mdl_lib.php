<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create your department by clicking <u>new department</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Department
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
		createDatabasetable($var_tbl_6); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		//$fieldset6 = escape_data($_POST['fieldset6']);
		

		$insert_dataproperty = array("department"=>ucwords($fieldset1),"phonenumber"=>$fieldset2,"extn"=>$fieldset3,"hod"=>$fieldset4,"primarycontact"=>$fieldset5);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL12,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new department","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		$usr_key = array("uaccess"=>"limited");
		mysqli_data_check($tbL7,'(*)',$usr_key);
		$totaluser = $numOfrows;

		if(isset($totaluser) && $totaluser >= 1) {
			$usr = select_dt_fetch('uaccess','limited',$tbL7,'id','staffname');
		} else {
			$usr = '<option value="0">Inview</option>';
		}

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Department</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter department name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset2" id="fieldset2" placeholder="Enter department phonenumber" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset3" id="fieldset3" placeholder="Enter department extension" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset4" id="fieldset4" required="required">
								<option value="" selected="selected">Head of Department</option>
								<?php echo $usr; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset5" id="fieldset5" required="required">
								<option value="" selected="selected">Primary Contact</option>
								<?php echo $usr; ?>
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

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL12,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change user status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
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
		$fieldset5 = escape_data($_POST['fieldset5']);
		//$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);

		$insert_dataproperty = array("department"=>ucwords($fieldset1),"phonenumber"=>$fieldset2,"extn"=>$fieldset3,"hod"=>$fieldset4,"primarycontact"=>$fieldset5);
		$insert_constrain = array("id"=>$fieldset7);
		$data_inserted = mysqli_data_update($tbL12,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit department details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
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
			$del = mysqli_data_update($tbL12,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from department list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND department LIKE '".escape_data($_POST['search'])."%'";
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
		$pgstart = 0; $pglimit = 15;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,department,phonenumber,extn,hod,primarycontact,mdl,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL12,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","department","phonenumber","extension","head of dept.","primary contact","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by department.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $hod=""; $primarycontact=""; $h=""; $p=""; $the_mdl="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$the_mdl = appmodule($tdata["mdl"]);

			if($tdata["hod"] == 0) {
				$hod='Inview (<a href="?logs='.$logs.'&hod='.$dataid.'&start='.$pgstart.'&limit='.$pglimit.'" class="blue-font">Add</a>)';
				$h=0;
			} else {
				$hod_key = array("id"=>$tdata["hod"]);
				$hod_data = mysqli_get_schema_data($tbL7,'staffname,role',$hod_key);

				$role_key = array("id"=>$hod_data[1]);
				$role_data = mysqli_get_schema_data($tbL4,'role',$role_key);

				$hod=$hod_data[0].' ('.$role_data[0].')';
				$h=1;
			}

			if($tdata["primarycontact"] == 0) {
				$primarycontact='Inview (<a href="?logs='.$logs.'&prc='.$dataid.'&start='.$pgstart.'&limit='.$pglimit.'" class="blue-font">Add</a>)';
				$p=0;
			} else {
				$prc_key = array("id"=>$tdata["primarycontact"]);
				$prc_data = mysqli_get_schema_data($tbL7,'staffname,role',$prc_key);

				$role_key = array("id"=>$prc_data[1]);
				$role_data = mysqli_get_schema_data($tbL4,'role',$role_key);

				$primarycontact=$prc_data[0].' ('.$role_data[0].')';
				$p=1;
			}
			
			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["department"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["phonenumber"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["extn"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$hod.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$primarycontact.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$additionalQuery = "";
				$usr_key = array("uaccess"=>"limited");
				mysqli_data_check($tbL7,'(*)',$usr_key);
				$totaluser = $numOfrows;

				if(isset($totaluser) && $totaluser >= 1) {
					$usr = select_dt_fetch('uaccess','limited',$tbL7,'id','staffname');
				} else {
					$usr = '<option value="0">Inview</option>';
				}

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["department"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Department</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter department name" value="'.$tdata["department"].'">';
				$htmlresult .= '</span>';
				/*$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 left-pull-5 alignlt">Link to module?</small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				if(isset($tdata["mdl"]) && $tdata["mdl"] >= 1) { $htmlresult .= '<option value="'.$tdata["mdl"].'" selected>'.$the_mdl.'</option>'; }
				$htmlresult .= $apmOpt;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';*/
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Phone Number</small>';
				$htmlresult .= '<input type="number" name="fieldset2" id="fieldset2" placeholder="Enter department phonenumber" value="'.$tdata["phonenumber"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Extension</small>';
				$htmlresult .= '<input type="number" name="fieldset3" id="fieldset3" placeholder="Enter department phonenumber" value="'.$tdata["extn"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Head of Department</small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required">';
				if(isset($h) && $h == 1) { $htmlresult .= '<option value="'.$tdata["hod"].'">'.$hod.'</option>'; }
				else { $htmlresult .= $usr; }
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Primary Contact</small>';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required">';
				if(isset($p) && $p == 1) { $htmlresult .= '<option value="'.$tdata["primarycontact"].'">'.$primarycontact.'</option>'; }
				else { $htmlresult .= $usr; }
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset7" id="fieldset7" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL12,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(15,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>