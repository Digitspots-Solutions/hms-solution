<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create all point of sales available in hotel by clicking <u>new outlet</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Outlet
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

	$usr = select_dt_fetch('status','Active',$tbL12,'id','department');
	$stores = select_dt_fetch('status','Active',$tbL123,'id','store_name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_11); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);

		$storage = escape_data($_POST['storage']);

		if(isset($fieldset6) && $fieldset6 == 'Yes') {
			$guest_discount = $_POST['guest'];
			$staff_discount = $_POST['staff'];
		} elseif(isset($fieldset6) && $fieldset6 == 'No') {
			$guest_discount = 0;
			$staff_discount = 0;
		}

		$isdocket = escape_data($_POST['docket']);
		
		$insert_dataproperty = array("store"=>$storage,"posname"=>ucwords(strtolower($fieldset1)),"postype"=>ucwords(strtolower($fieldset2)),"departmentid"=>$fieldset3,"isfoodtype"=>$fieldset4,"iscounter"=>$fieldset5,"isdiscount"=>$fieldset6,"guest_discount"=>$guest_discount,"staff_discount"=>$staff_discount,"isdocket"=>$isdocket);
		$insert_constrain = array("posname"=>ucwords(strtolower($fieldset1)));
		$data_inserted = mysqli_data_insert($tbL14,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new pos store","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
							<h3 class="nomargin">Creating New POS Outlet</h3>
						</div>
						<span class="block-element bottom-push-10">
							<select name="storage" id="storage" required="required">
								<option value="" selected="selected">Storage Location?</option>
								<option value="0">N/A</option>
								<?php echo $stores; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter outlet name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset2" id="fieldset2" required="required">
								<option value="" selected="selected">Choose outlet type</option>
								<option value="Service">Service</option>
								<option value="Sales">Sales</option>
								<option value="Establishment">Establishment</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset3" id="fieldset3" required="required">
								<option value="" selected="selected">Department</option>
								<?php echo $usr; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset4" id="fieldset4" required="required">
								<option value="" selected="selected">Is Food Type Required</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset5" id="fieldset5" required="required">
								<option value="" selected="selected">Is Counter Receivable</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="docket" id="docket" required="required">
								<option value="" selected="selected">Allow Docket</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset6" id="fieldset6" required="required" onchange="allowdisc(this.value)">
								<option value="" selected="selected">Is Discount Allowed</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>

							<p></p>

							<div class="ln-display-box float-left cs-width-150 right-pull-10 left-pull-10">
								<h4 class="large nobold alignlt left-pull-5 bottom-pull-5 default-text-font-bold">Guest (%)</h4>
								<input type="number" name="guest" id="guest" step=".01" placeholder="0" readonly>
							</div>
							<div class="ln-display-box float-left cs-width-150 right-pull-10 left-pull-10">
								<h4 class="large nobold alignlt left-pull-5 bottom-pull-5 default-text-font-bold">Staff (%)</h4>
								<input type="number" name="staff" id="staff" step=".01" placeholder="0" readonly>
							</div>
							<div class="block-element new-line-space">
							</div>
							
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
			$cstat = mysqli_data_update($tbL14,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change pos store status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);

		$storage = escape_data($_POST['storage']);

		if(isset($fieldset7) && $fieldset7 == 'Yes') {
			$guest_discount = $_POST['guest'];
			$staff_discount = $_POST['staff'];
		} elseif(isset($fieldset7) && $fieldset7 == 'No') {
			$guest_discount = 0;
			$staff_discount = 0;
		}

		$isdocket = escape_data($_POST['docket']);

		$insert_dataproperty = array("store"=>$storage,"posname"=>ucwords(strtolower($fieldset1)),"postype"=>ucwords(strtolower($fieldset2)),"departmentid"=>$fieldset3,"isfoodtype"=>$fieldset4,"iscounter"=>$fieldset5,"isdiscount"=>$fieldset7,"guest_discount"=>$guest_discount,"staff_discount"=>$staff_discount,"isfoodflash"=>$fieldset8,"isdocket"=>$isdocket);
		$insert_constrain = array("id"=>$fieldset6);
		$data_inserted = mysqli_data_update($tbL14,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit pos store details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
			$del = mysqli_data_update($tbL14,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove pos store from list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND posname LIKE '".escape_data($_POST['search'])."%' OR postype LIKE '".escape_data($_POST['search'])."%'";
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

	$dataproperty = "id,store,posname,postype,departmentid,isfoodtype,iscounter,isfoodflash,isdocket,isdiscount,guest_discount,staff_discount,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL14,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","store","outlet","type","categories","products","tables","department","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by pos store.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $inline_category=""; $inline_product=""; $inline_table=""; $department=""; $store_name="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$dpt_key = array("id"=>$tdata["departmentid"]);
			$dpt_data = mysqli_get_schema_data($tbL12,'department',$dpt_key);
			$department=$dpt_data[0];

			$additionalQuery = "";
			$pos_store_key = array("postoreid"=>$dataid);
			
			mysqli_data_check($tbL15,'(*)',$pos_store_key);
			$inline_category = $numOfrows;

			mysqli_data_check($tbL16,'(*)',$pos_store_key);
			$inline_product = $numOfrows;

			mysqli_data_check($tbL17,'(*)',$pos_store_key);
			$inline_table = $numOfrows;

			$store_name = idget_data($tbL123,$tdata["store"],'store_name');
			
			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$store_name.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["posname"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["postype"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="" class="blue-font">'.$inline_category.' Category(s)</a></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="" class="blue-font">'.$inline_product.' Product(s)</a></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="" class="blue-font">'.$inline_table.' Table(s)</a></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$department.'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["isfoodtype"].'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["iscounter"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit/View</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["posname"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Storage Location</small>';
				$htmlresult .= '<select name="storage" id="storage" required="required"><option value="'.$tdata["store"].'" selected="selected">'.$store_name.'</option><option value="0">N/A</option>'.$stores.'</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Outlet Name</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter pos outlet name" value="'.$tdata["posname"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Outlet Type</small>';
				$htmlresult .= '<select name="fieldset2" id="fieldset2" required="required">';
				$htmlresult .= '<option value="'.$tdata["postype"].'" selected="selected">'.$tdata["postype"].'</option>';
				$htmlresult .= '<option value="Service">Service</option>';
				$htmlresult .= '<option value="Sales">Sales</option>';
				$htmlresult .= '<option value="Establishment">Establishment</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Department</small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required">';
				$htmlresult .= '<option value="'.$tdata["departmentid"].'">'.$department.'</option>';
				$htmlresult .= $usr;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Food Type Required</small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required">';
				$htmlresult .= '<option value="'.$tdata["isfoodtype"].'">'.$tdata["isfoodtype"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Counter Receivable</small>';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required">';
				$htmlresult .= '<option value="'.$tdata["iscounter"].'">'.$tdata["iscounter"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Allow Docket</small>';
				$htmlresult .= '<select name="docket" id="docket" required="required">';
				$htmlresult .= '<option value="'.$tdata["isdocket"].'">'.$tdata["isdocket"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Allow Food Flash</small>';
				$htmlresult .= '<select name="fieldset8" id="fieldset8" required="required">';
				$htmlresult .= '<option value="'.$tdata["isfoodflash"].'">'.$tdata["isfoodflash"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Discount Allowed</small>';
				$htmlresult .= '<select name="fieldset7" id="fieldset7" required="required" onchange="allowdisc(this.value)">';
				$htmlresult .= '<option value="'.$tdata["isdiscount"].'">'.$tdata["isdiscount"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '<p></p>';
				$htmlresult .= '<div class="ln-display-box float-left cs-width-150 right-pull-10 left-pull-10">';
				$htmlresult .= '<h4 class="large nobold alignlt left-pull-5 bottom-pull-5 default-text-font-bold">Guest (%)</h4>';
				$htmlresult .= '<input type="number" name="guest" id="guest" step=".01" placeholder="0" value="'.$tdata["guest_discount"].'" readonly>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left cs-width-150 right-pull-10 left-pull-10">';
				$htmlresult .= '<h4 class="large nobold alignlt left-pull-5 bottom-pull-5 default-text-font-bold">Staff (%)</h4>';
				$htmlresult .= '<input type="number" name="staff" id="staff" step=".01" placeholder="0" value="'.$tdata["staff_discount"].'" readonly>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset6" id="fieldset6" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL14,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(15,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<script>

	function allowdisc(opt) {
		if(opt == 'Yes') {
			document.getElementById('guest').removeAttribute('readonly');
			document.getElementById('staff').removeAttribute('readonly');
		} else if(opt == 'No') {
			document.getElementById('guest').value = "";
			document.getElementById('staff').value = "";
			document.getElementById('guest').setAttribute('readonly','readonly');
			document.getElementById('staff').setAttribute('readonly','readonly');
		}
	}

</script>