<?php $smdl = "material control"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new item by clicking <u>new item</u> button and fill the necessary information
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Item
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$get_active_categories = select_dt_fetch('status','Active',$tbL115,'id','category');
	$get_uom = arrayset_form($uoms,'select');

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_113); //create a table for this post
		
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);

		if(isset($fieldset10) && $fieldset10 == "Yes") {
			if(isset($_POST['indays']) && $_POST['indays'] >= 1) {
				$day = $_POST['indays']." days";
				$err = 0;
				$dateExpire = date("Y-m-d",strtotime($day));
			} elseif(isset($_POST['indate']) && !empty($_POST['indate'])) {
				$dateExpire = $_POST['indate'];
				$err = 0;
			} else {
				$dateExpire = "";
				$err = 1;
			}
		} else {
			$dateExpire = "";
			$err = 0;
		}

		if(isset($err) && !empty($err)) {
			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Unable to save! Please indicate expire day/date for expiring item</span>';
			$post_result .= '</div>';
		} else {
			$insert_dataproperty = array("categoryid"=>$fieldset3,"subcategoryid"=>$fieldset4,"itemgroupid"=>$fieldset5,"item"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2,"iscost_center"=>$fieldset6,"buying_unit"=>$fieldset7,"selling_unit"=>$fieldset8,"noofpiece_bu"=>$fieldset9,"isexpire"=>$fieldset10,"expiry_date"=>$dateExpire,"minimum_stock"=>$fieldset11,"maximum_stock"=>$fieldset12);
			$insert_constrain = "";
			$data_inserted = mysqli_data_insert($tbL118,$insert_dataproperty,$insert_constrain);

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

			if(isset($data_inserted) && $data_inserted == 2)
			{
				$item_code = "ITM".$mysqli_id;
				$item_datasets = array("itemcode"=>$item_code);
				$item_query = array("id"=>$mysqli_id);
				$data_updated = mysqli_data_update($tbL118,$item_datasets,$item_query);

				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new stock item","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$post_result .= '<span class="red-font">New entry was saved successfully</span>';
			}

			$post_result .= '</div>';
		}
		
		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{
		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-90">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Item</h3>
						</div>
						<div class="ln-display-box float-left nc-width-40 right-pull-10">
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Name <b class="red-font">*</b></small>
								<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Description</small>
								<textarea name="fieldset2" id="fieldset2" placeholder="Enter description"></textarea>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Category <b class="red-font">*</b></small>
								<select name="fieldset3" id="fieldset3" required="required" onchange="getdata('fieldset4','eget-item-subcategory-list','fieldset3','dropbox');">
									<option value="" selected="selected">Select Category</option>
									<?php echo $get_active_categories; ?>
								</select>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Sub-Category <b class="red-font">*</b></small>
								<select name="fieldset4" id="fieldset4" required="required" onchange="getdata('fieldset5','eget-item-group-list','fieldset4','dropbox');">
									<option value="" selected="selected">Select Sub-Category</option>
								</select>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Item Groups <b class="red-font">*</b></small>
								<select name="fieldset5" id="fieldset5" required="required">
									<option value="" selected="selected">Select Item Group</option>
								</select>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Is Cost Center Item</small>
								<select name="fieldset6" id="fieldset6" required="required">
									<option value="No" selected="selected">No</option>
									<option value="Yes" selected="selected">Yes</option>
								</select>
							</span>
						</div>
						<div class="ln-display-box float-left nc-width-60 left-pull-30">
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Buying Unit</small>
								<select name="fieldset7" id="fieldset7" required="required">
									<?php echo $get_uom; ?>
								</select>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Selling Unit</small>
								<select name="fieldset8" id="fieldset8" required="required">
									<?php echo $get_uom; ?>
								</select>
								<input type="number" name="fieldset9" id="fieldset9" step="any" min="1" placeholder="No of pieces for buying unit?">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Expiry Type</small>
								<div class="ft-xsml-size alignlt"><input type="radio" name="fieldset10" id="fieldset10" value="No" required="required"> Never Expire &nbsp;&nbsp; <input type="radio" name="fieldset10" id="fieldset10" value="Yes" required="required" onclick="chgclass('indays','noshow'); chgclass('indate','block-element'); formrequired('indate'); formnotrequired('indays')"> Expiry Date &nbsp;&nbsp; <input type="radio" name="fieldset10" id="fieldset10" value="Yes" required="required" checked="checked" onclick="chgclass('indays','block-element'); chgclass('indate','noshow'); formrequired('indays'); formnotrequired('indate')"> Expires in</div>
								<div class="block-element top-push-5 nc-width-30">
									<div id="indays"><input type="text" name="indays" pattern="\d*" placeholder="No of days?"></div>
									<div id="indate" class="noshow"><input type="date" name="indate"></div>
								</div>
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Minimum Stock</small>
								<input type="text" name="fieldset11" id="fieldset11" placeholder="Enter minimum" value="0">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Maximum Stock</small>
								<input type="text" name="fieldset12" id="fieldset12" placeholder="Enter maximum" value="0">
							</span>
						</div>
						<div class="block-element new-line-space">
						</div>

						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
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
			$cstat = mysqli_data_update($tbL118,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change stock item status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);
		$fieldset13 = escape_data($_POST['fieldset13']);
		
		if(isset($fieldset10) && $fieldset10 == "Yes") {
			if(isset($_POST['indays']) && $_POST['indays'] >= 1) {
				$day = $_POST['indays']." days";
				$err = 0;
				$dateExpire = date("Y-m-d",strtotime($day));
			} elseif(isset($_POST['indate']) && !empty($_POST['indate'])) {
				$dateExpire = $_POST['indate'];
				$err = 0;
			} else {
				$dateExpire = "";
				$err = 1;
			}
		} else {
			$dateExpire = "";
			$err = 0;
		}

		if(isset($err) && !empty($err)) {
			$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$update_result .= '<span class="red-font">Unable to save! Please indicate expire day/date for expiring item</span>';
			$update_result .= '</div>';
		} else {
			$insert_dataproperty = array("categoryid"=>$fieldset3,"subcategoryid"=>$fieldset4,"itemgroupid"=>$fieldset5,"item"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2,"iscost_center"=>$fieldset6,"buying_unit"=>$fieldset7,"selling_unit"=>$fieldset8,"noofpiece_bu"=>$fieldset9,"isexpire"=>$fieldset10,"expiry_date"=>$dateExpire,"minimum_stock"=>$fieldset11,"maximum_stock"=>$fieldset12);
			$insert_constrain = array("id"=>$fieldset13);
			$data_inserted = mysqli_data_update($tbL118,$insert_dataproperty,$insert_constrain);

			$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

			if(isset($data_inserted) && $data_inserted == 2)
			{
				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit stock item details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$update_result .= '<span class="red-font">Changes were added successfully</span>';
			}

			$update_result .= '</div>';
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL118,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove stock item","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (item LIKE '".escape_data($_POST['search'])."%' OR itemcode LIKE '".escape_data($_POST['search'])."%')";
	} else { 
		if(isset($_GET['getstatus']) && ($_GET['getstatus'] == 'Active' || $_GET['getstatus'] == 'InActive')) {
			$keywords=" AND status = '".$_GET['getstatus']."'";
		} else {
			$keywords="";
		}
	}

	

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 25;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,categoryid,subcategoryid,itemgroupid,item,itemcode,detail,buying_unit,selling_unit,noofpiece_bu,isexpire,expiry_date,expiry_status,minimum_stock,maximum_stock,iscost_center,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL118,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","item code","item name","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-20">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		
		$htmlresult .= '<span class="ln-display-box float-right nc-width-20 top-pull-10 alignrt">';
		$htmlresult .= '<a href="?logs='.$logs.'&getstatus=All" class="steel-blue-font ft-xxsml-size"><u>All</u></a> &nbsp; <a href="?logs='.$logs.'&getstatus=Active" class="steel-blue-font ft-xxsml-size"><u>Active</u></a> &nbsp; <a href="?logs='.$logs.'&getstatus=InActive" class="steel-blue-font ft-xxsml-size"><u>InActive</u></a>';
		$htmlresult .= '</span>';

		$htmlresult .= '<span class="ln-display-box float-right nc-width-30">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $hod=""; $primarycontact=""; $h=""; $p="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["itemcode"].'</td>';
			$htmlresult .= '<td width="300px" align="center" class="box-border-thick-right"><a href="javascript:void(0)" class="royal-blue-font" title="Item Details" onclick="get_item_detail('.$dataid.')"><b>'.$tdata["item"].'</b></a></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$category = idget_data($tbL115,$tdata["categoryid"],'category');
				$sub_category = idget_data($tbL116,$tdata["subcategoryid"],'subcategory');
				$item_group = idget_data($tbL117,$tdata["itemgroupid"],'groupname');

				$buying_unit = arrayget_key($uoms,$tdata["buying_unit"]);
				$selling_unit = arrayget_key($uoms,$tdata["selling_unit"]);

				if($tdata["isexpire"] == "Yes") { $checked_exp = " checked"; $xlabel = "Expired Item (".$tdata["expiry_date"].")"; }
				else { $checked_exp = ""; $xlabel = ""; }

				if($tdata["isexpire"] == "No") { $notchecked = " checked"; $xxlabel = "Never Expire Item"; }
				else { $notchecked = ""; $xxlabel = ""; }

				$writeDataIn1="'fieldset4'"; $selectOpt1="'eget-item-subcategory-list'"; $getDataKeyFrom1="'fieldset3'"; $formObj1="'dropbox'";
				$writeDataIn2="'fieldset5'"; $selectOpt2="'eget-item-group-list'"; $getDataKeyFrom2="'fieldset4'"; $formObj2="'dropbox'";

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Item: '.$tdata["item"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-90">';
				
				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-pull-10">';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Name <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" value="'.$tdata["item"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Description</small>';
				$htmlresult .= '<textarea name="fieldset2" id="fieldset2" placeholder="Enter description">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Category <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required" onchange="getdata('.$writeDataIn1.','.$selectOpt1.','.$getDataKeyFrom1.','.$formObj1.')">';
				$htmlresult .= '<option value="'.$tdata["categoryid"].'" selected="selected">'.$category.'</option>';
				$htmlresult .= $get_active_categories;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Sub Category <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required" onchange="getdata('.$writeDataIn2.','.$selectOpt2.','.$getDataKeyFrom2.','.$formObj2.')">';
				$htmlresult .= '<option value="'.$tdata["subcategoryid"].'" selected="selected">'.$sub_category.'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Item Group <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required">';
				$htmlresult .= '<option value="'.$tdata["itemgroupid"].'" selected="selected">'.$item_group.'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Is Cost Center Item</small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				$htmlresult .= '<option value="'.$tdata["iscost_center"].'" selected="selected">'.$tdata["iscost_center"].'</option>';
				$htmlresult .= '<option value="No" selected="selected">No</option>';
				$htmlresult .= '<option value="Yes" selected="selected">Yes</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50 left-pull-50">';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Buying Unit</small>';
				$htmlresult .= '<select name="fieldset7" id="fieldset7" required="required">';
				$htmlresult .= '<option value="'.$tdata["buying_unit"].'" selected="selected">'.$buying_unit.'</option>';
				$htmlresult .= $get_uom;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Selling Unit</small>';
				$htmlresult .= '<select name="fieldset8" id="fieldset8" required="required">';
				$htmlresult .= '<option value="'.$tdata["selling_unit"].'" selected="selected">'.$selling_unit.'</option>';
				$htmlresult .= $get_uom;
				$htmlresult .= '</select>';
				$htmlresult .= '<input type="text" name="fieldset9" id="fieldset9" placeholder="No of pieces for buying unit" value="'.$tdata["noofpiece_bu"].'">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt ft-xxsml-size">* for buying unit</small>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 black-font alignlt add-bold">'.$xlabel.$xxlabel.'</small>';
				$htmlresult .= '<input type="radio" name="fieldset10" id="fieldset10" value="No" required="required"'.$notchecked.'> Never Expire &nbsp;&nbsp; <input type="radio" name="fieldset10" id="fieldset10" value="Yes" required="required"'.$checked_exp.'> Expiry Date';
				$htmlresult .= '<div class="top-push-5"><input type="date" name="indate" id="indate" value="'.$tdata["expiry_date"].'"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Minimum Stock</small>';
				$htmlresult .= '<input type="text" name="fieldset11" id="fieldset11" value="'.$tdata["minimum_stock"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Maximum Stock</small>';
				$htmlresult .= '<input type="text" name="fieldset12" id="fieldset12" value="'.$tdata["maximum_stock"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignct">';
				$htmlresult .= '<input type="hidden" name="fieldset13" id="fieldset13" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL118,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<script type="text/javascript">
	
	function get_item_detail(id) {
		chgclass('item-mgt','fx-position-stick zind-2 motion fscr white-theme noscroll');
		chgclass('item-mgt-box','block-element');

		if(eval(document.getElementById('isframeSet').value) == 1) {
			var newframe = document.querySelector('iframe');
			objDisplay('loading-msg');
			newframe.src = "stock_item_mgt.php?thisItem="+id+"&a=overview";
			newframe.onload = function() { objHidden('loading-msg'); }
		} else {
			var newframe = document.createElement('iframe');
			newframe.id = 'frame1';
			newframe.name = 'frame1';
			newframe.frameBorder = 0;
			newframe.marginWidth = 0;
			newframe.marginHeight = 0;
			newframe.width = '100%';
			newframe.height = '90%';
			newframe.scrolling = 'auto';

			document.getElementById('itm-work-area').appendChild(newframe);
			htmlpassval(1,'isframeSet'); objDisplay('loading-msg');
			newframe.src = "stock_item_mgt.php?thisItem="+id+"&a=overview";
			newframe.onload = function() { objHidden('loading-msg'); }
		}
		
	}

	function close_item_detail() {
		chgclass('item-mgt','fx-position-flow zind-2 motion btscr noscroll');
		chgclass('item-mgt-box','noshow');
	}

</script>

<div id="item-mgt" class="fx-position-flow zind-2 motion btscr noscroll" align="center">
	<div class="cs-height-100"></div>
	<div id="item-mgt-box" class="noshow">
		<div class="block-element top-push-10 bottom-push-10 right-pull-30 left-pull-50">
			<span class="ln-display-box float-left nc-width-50">
				<small id="loading-msg" class="white-font noshow">Loading item details..</small>
			</span>
			<span class="ln-display-box float-right">
				<a href="javascript:void(0)" class="black-font ft-xsml-size" onclick="close_item_detail()">X Close</a>
			</span>
			<span class="block-element new-line-space"></span>
		</div>
		<div id="itm-work-area" class="cs-width-900 white-theme bottom-push-10 sml-rounded-button alignlt box-border-thick noscroll">
		</div>
	</div>
	<input type="hidden" id="isframeSet" value="0">
</div>