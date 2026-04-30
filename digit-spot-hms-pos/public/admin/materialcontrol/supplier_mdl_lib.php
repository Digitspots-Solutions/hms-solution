<?php $smdl = "material control"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new supplier by clicking <u>new supplier</u> button and fill the necessary information
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Supplier
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
		createDatabasetable($var_tbl_109); //create a table for this post
		
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
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset15 = escape_data($_POST['fieldset15']);

		$insert_dataproperty = array("supplier_name"=>ucwords(strtolower($fieldset1)),"mobile"=>$fieldset2,"fax"=>$fieldset3,"emailaddress"=>$fieldset4,"website"=>$fieldset5,"order_method"=>$fieldset6,"address"=>$fieldset7,"country"=>ucwords(strtolower($fieldset8)),"pincode"=>$fieldset9,"person_incharge"=>ucwords(strtolower($fieldset10)),"sales_representative"=>ucwords(strtolower($fieldset11)),"city"=>ucwords(strtolower($fieldset12)),"pan"=>ucwords(strtolower($fieldset13)),"extn"=>$fieldset14,"paymenterm"=>$fieldset15,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL114,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new supplier","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
				<div class="nc-width-90">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Supplier</h3>
						</div>
						<div class="ln-display-box float-left nc-width-40 right-pull-10">
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Name <b class="red-font">*</b></small>
								<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Phone No. <b class="red-font">*</b></small>
								<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter phone number" required="required">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Fax</small>
								<input type="text" name="fieldset3" id="fieldset3" placeholder="Enter fax">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">E-mail <b class="red-font">*</b></small>
								<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter e-mail">
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Website</small>
								<input type="text" name="fieldset5" id="fieldset5" placeholder="Enter website">
							</span>
							<span class="block-element bottom-push-15 alignlt">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Placing Orders <b class="red-font">*</b></small>
								<input type="radio" name="fieldset6" id="fieldset6" value="Email" required="required"> Email &nbsp;&nbsp; <input type="radio" name="fieldset6" id="fieldset6" value="Fax" required="required"> Fax &nbsp;&nbsp; <input type="radio" name="fieldset6" id="fieldset6" value="Post" required="required"> Post
							</span>
							<span class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Address <b class="red-font">*</b></small>
								<textarea name="fieldset7" id="fieldset7" placeholder="Enter address" required="required"></textarea>
							</span>
						</div>
						<div class="ln-display-box float-left nc-width-60 left-pull-30">
							<div class="ln-display-box float-left nc-width-50 right-pull-10">
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Country</small>
									<input type="text" name="fieldset8" id="fieldset8" placeholder="Enter country">
								</span>
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Pincode <b class="red-font">*</b></small>
									<input type="text" name="fieldset9" id="fieldset9" placeholder="Enter pincode" required="required">
								</span>
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Person Incharge</small>
									<input type="text" name="fieldset10" id="fieldset10" placeholder="Enter person in-charge">
								</span>
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Sales Representative</small>
									<input type="text" name="fieldset11" id="fieldset11" placeholder="Enter sales representative">
								</span>
							</div>
							<div class="ln-display-box float-left nc-width-50 left-pull-10">
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">City <b class="red-font">*</b></small>
									<input type="text" name="fieldset12" id="fieldset12" placeholder="Enter city" required="required">
								</span>
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">PAN</small>
									<input type="text" name="fieldset13" id="fieldset13" placeholder="Enter pan">
								</span>
								<span class="block-element bottom-push-15">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Extension</small>
									<input type="text" name="fieldset14" id="fieldset14" placeholder="Enter extension">
								</span>
							</div>
							<div class="block-element new-line-space">
							</div>
							<div class="block-element bottom-push-15">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Terms of Payment <b class="red-font">*</b></small>
								<textarea name="fieldset15" id="fieldset15" placeholder="Enter terms of payment" required="required"></textarea>
							</div>
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
			$cstat = mysqli_data_update($tbL114,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change supplier status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset15 = escape_data($_POST['fieldset15']);
	

		$insert_dataproperty = array("supplier_name"=>ucwords(strtolower($fieldset1)),"mobile"=>$fieldset2,"fax"=>$fieldset3,"emailaddress"=>$fieldset4,"website"=>$fieldset5,"order_method"=>$fieldset6,"address"=>$fieldset7,"country"=>ucwords(strtolower($fieldset8)),"pincode"=>$fieldset9,"person_incharge"=>ucwords(strtolower($fieldset10)),"sales_representative"=>ucwords(strtolower($fieldset11)),"city"=>ucwords(strtolower($fieldset12)),"pan"=>ucwords(strtolower($fieldset13)),"extn"=>$fieldset14,"paymenterm"=>$fieldset15);
		$insert_constrain = array("id"=>$fieldset16);
		$data_inserted = mysqli_data_update($tbL114,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit supplier details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
			$del = mysqli_data_update($tbL114,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove supplier","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (supplier_name LIKE '".escape_data($_POST['search'])."%' OR pincode LIKE '".escape_data($_POST['search'])."%')";
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

	$dataproperty = "id,supplier_name,mobile,fax,emailaddress,website,order_method,address,country,pincode,person_incharge,sales_representative,city,pan,extn,paymenterm,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL114,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","name","address","city","phone no","email","website","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
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
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&supplier='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="royal-blue-font" title="See detail"><b>'.$tdata["supplier_name"].'</b></a></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["address"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["city"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["emailaddress"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["website"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				if($tdata["order_method"] == 'Email') { $order_m_1 = " checked"; } else { $order_m_1 = ""; }
				if($tdata["order_method"] == 'Fax') { $order_m_2 = " checked"; } else { $order_m_2 = ""; }
				if($tdata["order_method"] == 'Post') { $order_m_3 = " checked"; } else { $order_m_3 = ""; }

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Supplier: '.$tdata["supplier_name"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-90">';
				
				$htmlresult .= '<div class="ln-display-box float-left nc-width-40 right-pull-10">';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Name <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" value="'.$tdata["supplier_name"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Phone No. <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" value="'.$tdata["mobile"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Fax</small>';
				$htmlresult .= '<input type="text" name="fieldset3" id="fieldset3" value="'.$tdata["fax"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">E-mail <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset4" id="fieldset4" value="'.$tdata["emailaddress"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Website</small>';
				$htmlresult .= '<input type="text" name="fieldset5" id="fieldset5" value="'.$tdata["website"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15 alignlt">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Placing Orders <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="radio" name="fieldset6" id="fieldset6" value="Email" required="required"'.$order_m_1.'> Email &nbsp;&nbsp; <input type="radio" name="fieldset6" id="fieldset6" value="Fax" required="required"'.$order_m_2.'> Fax &nbsp;&nbsp; <input type="radio" name="fieldset6" id="fieldset6" value="Post" required="required"'.$order_m_3.'> Post';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Address <b class="red-font">*</b></small>';
				$htmlresult .= '<textarea name="fieldset7" id="fieldset7" required="required">'.$tdata["address"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-60 left-pull-30">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50 right-pull-10">';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Country</small>';
				$htmlresult .= '<input type="text" name="fieldset8" id="fieldset8" value="'.$tdata["country"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Pincode <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset9" id="fieldset9" value="'.$tdata["pincode"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Person Incharge</small>';
				$htmlresult .= '<input type="text" name="fieldset10" id="fieldset10" value="'.$tdata["person_incharge"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Sales Representative</small>';
				$htmlresult .= '<input type="text" name="fieldset11" id="fieldset11" value="'.$tdata["sales_representative"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50 left-pull-10">';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">City <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset12" id="fieldset12" value="'.$tdata["city"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">PAN</small>';
				$htmlresult .= '<input type="text" name="fieldset13" id="fieldset13" value="'.$tdata["pan"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Extension</small>';
				$htmlresult .= '<input type="text" name="fieldset14" id="fieldset14" value="'.$tdata["extn"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Terms of Payment <b class="red-font">*</b></small>';
				$htmlresult .= '<textarea name="fieldset15" id="fieldset15" required="required">'.$tdata["paymenterm"].'</textarea>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';

				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignct">';
				$htmlresult .= '<input type="hidden" name="fieldset16" id="fieldset16" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL114,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>