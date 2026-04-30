<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new room type by clicking <u>new room type</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Room Type
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
		createDatabasetable($var_tbl_50); //create a table for this post
		createDatabasetable($var_tbl_51); //create a table for this post
		createDatabasetable($var_tbl_52); //create a table for this post
		createDatabasetable($var_tbl_53); //create a table for this post

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
		$fieldset16 = escape_data($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);

		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)),"shortname"=>str_replace(' ','',strtoupper($fieldset2)),"detail"=>$fieldset3,"grade"=>$fieldset4,"adult"=>$fieldset5,"child"=>$fieldset6,"maxallow"=>$fieldset7,"isextrabed"=>$fieldset8,"noofrooms"=>$fieldset9,"hima_allocation"=>$fieldset10,"defaultprice"=>$fieldset11,"baseprice"=>$fieldset12,"extrabedprice"=>$fieldset13,"childfare"=>$fieldset14,"minimumdeposit"=>$fieldset15,"ismandatory_minimum_deposit"=>$fieldset16,"issmoking"=>$fieldset17);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL52,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_room_type_id = $mysqli_id; //new room-type created

			##for occupancy rate indicated
			$occupancy_type_rate = $_POST['occupancy_types'];
			$occupancy_type_rate_id = $_POST['occupancy_types_id'];

			for($cty=0; $cty < count($occupancy_type_rate_id); $cty++) {
				if($occupancy_type_rate[$cty] != '') {
					$cty_arr = array("room_type_id"=>$new_room_type_id,"occupancy_type_id"=>$occupancy_type_rate_id[$cty],"price"=>$occupancy_type_rate[$cty]);
					mysqli_data_insert($tbL54,$cty_arr,'');
				}
			}

			##for amenities selected
			$amenities_arr = $_POST['amenities'];
			if(isset($amenities_arr)) {
				foreach($amenities_arr as $room_amenity) {
					$amn_select_arr = array("room_type_id"=>$new_room_type_id,"amenityid"=>$room_amenity);
					mysqli_data_insert($tbL55,$amn_select_arr,'');
				}
			}

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new room type","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
				<div class="nc-width-50">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Room Type</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter room type" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter room-type short name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<textarea name="fieldset3" id="fieldset3" placeholder="Enter description"></textarea>
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset4" id="fieldset4" placeholder="Grade">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset5" id="fieldset5" placeholder="Adult" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset6" id="fieldset6" placeholder="Child" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset7" id="fieldset7" placeholder="Max Members Allowed?">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset8" id="fieldset8" required="required">
								<option value="" selected="selected">Extra Beds Allowed?</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset9" id="fieldset9" placeholder="No of Rooms" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset10" id="fieldset10" placeholder="Hima Allocation">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset11" id="fieldset11" step="any" placeholder="Default Fare" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset12" id="fieldset12" step="any" placeholder="Base Price (this will effect on bookings)" required="required">
							<!--<small class="block-element top-push-3 ft-xxsml-size red-font alignlt left-pull-5">* Base price will 
							effect on bookings</small>-->
						</span>
						<?php
							
							$oct_constrain = array("deletedata"=>0,"status"=>"Active");
							$get_occupancy_type = mysqli_data_fetch($tbL51,'id,name,shortname',$oct_constrain,'array');

							if(is_array($get_occupancy_type)) {
								foreach ($get_occupancy_type as $oct_key => $oct_value) {
									?>
										<span class="block-element bottom-push-10">
											<input type="number" name="occupancy_types[]" step="any" placeholder="<?php echo $oct_value["name"]; ?> Occupancy Rate"><input type="hidden" name="occupancy_types_id[]" value="<?php echo $oct_value["id"]; ?>">
										</span>
									<?php
								}
							}

						?>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset13" id="fieldset13" step="any" placeholder="Extra Bed Price" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset14" id="fieldset14" step="any" placeholder="Child Fare" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" name="fieldset15" id="fieldset15" step="any" placeholder="Minimum Deposit">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset16" id="fieldset16" required="required">
								<option value="" selected="selected">Is Minimum Deposit Mandatory?</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset17" id="fieldset17" required="required">
								<option value="" selected="selected">Is Smoking Allowed?</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-15 ft-xsml-size alignlt left-pull-5 add-bold">Select Facilities</small>
							<?php
								
								$amn_constrain = array("deletedata"=>0,"status"=>"Active");
								$get_amenities = mysqli_data_fetch($tbL13,'id,name',$amn_constrain,'array');

								if(is_array($get_amenities)) {
									foreach ($get_amenities as $amn_key => $amn_value) {
										?><div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">
											<span class="ln-display-box float-left nc-width-15">
												<input type="checkbox" name="amenities[]" value="<?php echo $amn_value["id"]; ?>">
											</span>
											<span class="ln-display-box float-left nc-width-85 alignlt">
												<?php echo $amn_value["name"]; ?>
											</span>
											<span class="block-element new-line-space"></span>
										</div><?php
									}
								}

							?>
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
			$cstat = mysqli_data_update($tbL52,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change room type status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['imagebutton']) && isset($_POST['dataurl']) && !empty($_POST['dataurl']))
	{
		$encoded_data = str_replace(' ','+',$_POST['dataurl']);
		$binary_data = base64_decode($encoded_data);

		$img = "room_".date('YmdHis');
		$newname="../../theme/images/general/roomtype/".$img.".jpg";
		
		file_put_contents($newname, $binary_data);
		$newimage = $img.".jpg";

		$new_room_type_id = escape_data($_POST['fieldset1']);
		$image_arr = array("room_type_id"=>$new_room_type_id,"image"=>$newimage);
		mysqli_data_insert($tbL53,$image_arr,'');

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Add image to room type","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$update_result .= '<span class="red-font">Image was uploaded successfully</span>';
		$update_result .= '</div>';
	}

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
		$fieldset16 = escape_data($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);
		$fieldset18 = escape_data($_POST['fieldset18']);

		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)),"shortname"=>str_replace(' ','',strtoupper($fieldset2)),"detail"=>$fieldset3,"grade"=>$fieldset4,"adult"=>$fieldset5,"child"=>$fieldset6,"maxallow"=>$fieldset7,"isextrabed"=>$fieldset8,"noofrooms"=>$fieldset9,"hima_allocation"=>$fieldset10,"defaultprice"=>$fieldset11,"baseprice"=>$fieldset12,"extrabedprice"=>$fieldset13,"childfare"=>$fieldset14,"minimumdeposit"=>$fieldset15,"ismandatory_minimum_deposit"=>$fieldset16,"issmoking"=>$fieldset17);
		$insert_constrain = array("id"=>$fieldset18);
		$data_inserted = mysqli_data_update($tbL52,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_room_type_id = $fieldset18; //new room-type created

			##for occupancy rate indicated
			$occupancy_type_rate = $_POST['occupancy_types'];
			$occupancy_type_rate_id = $_POST['occupancy_types_id'];

			for($cty=0; $cty < count($occupancy_type_rate_id); $cty++) {
				if($occupancy_type_rate[$cty] != '') {
					$cty_arr = array("price"=>$occupancy_type_rate[$cty]);
					$cty_qry = array("room_type_id"=>$new_room_type_id,"occupancy_type_id"=>$occupancy_type_rate_id[$cty]);
					mysqli_data_update($tbL54,$cty_arr,$cty_qry);
				}
			}

			##for amenities selected
			$amenities_arr = $_POST['amenities'];
			if(isset($amenities_arr)) {
				foreach($amenities_arr as $room_amenity) {
					$amn_select_arr = array("room_type_id"=>$new_room_type_id,"amenityid"=>$room_amenity);
					mysqli_data_insert($tbL55,$amn_select_arr,'');
				}
			}

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit roomtype details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
			$del = mysqli_data_update($tbL52,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove room type","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (name LIKE '".escape_data($_POST['search'])."%' OR shortname LIKE '".escape_data($_POST['search'])."%')";
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

	$dataproperty = "id,name,shortname,detail,adult,child,defaultprice,baseprice,extrabedprice,childfare,minimumdeposit,noofrooms,hima_allocation,maxallow,grade,ismandatory_minimum_deposit,isextrabed,issmoking,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL52,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","room type","adult","child","extra bed","no of rooms","base price","status","noth","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
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
		
		$num=$pgstart; $g=""; $dataid=""; $hod=""; $primarycontact=""; $h=""; $p="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&roomtypeid='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="royal-blue-font" title="See detail"><b><u>'.$tdata["name"].'</u></b></a></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["adult"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["child"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["isextrabed"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["noofrooms"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">&#8358 '.number_format($tdata["baseprice"],2).'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&image='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Add Image</a></td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['roomtypeid']) && $_GET['roomtypeid'] >= 1) && ($_GET['roomtypeid'] == $dataid))
			{
				$fieldset = escape_data($_GET['roomtypeid']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= '<h3 class="large blue-font">'.$tdata["name"].' ('.$tdata["shortname"].')</h3><br>';
				$htmlresult .= '<div class="nc-width-100">';
				$htmlresult .= '<span class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<h4 class="large">Room Facilities</h4><br>';

				##get all amenities attached to this roomtype
				$room_amenity_query = array("room_type_id"=>$fieldset,"deletedata"=>0);
				$get_room_amenities = mysqli_data_fetch($tbL55,'amenityid',$room_amenity_query,'array');

				if(is_array($get_room_amenities)) {
					$show_room_amenity='';
					foreach ($get_room_amenities as $room_amn_key => $room_amn_value) {
						$show_room_amenity = idget_data($tbL13,$room_amn_value['amenityid'],'name');
						$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10 ft-xxsml-size">';
						$htmlresult .= '<span class="ln-display-box float-left nc-width-10"><b class="fa-checker nobold"></b></span>';
						$htmlresult .= '<span class="ln-display-box float-left nc-width-90">'.$show_room_amenity.'</span>';
						$htmlresult .= '<span class="block-element new-line-space"></span>';
						$htmlresult .= '</div>';
					}
				}

				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '<div class="block-element top-push-15"></div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">About the room</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["detail"].'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Is Smoking Allowed</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["issmoking"].'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Maximum Members Allowed</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["maxallow"].'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Is Minimum Deposit Mandatory</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["ismandatory_minimum_deposit"].'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Minimum Deposit</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">&#8358 '.number_format($tdata["minimumdeposit"],2).'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Default Price</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">&#8358 '.number_format($tdata["defaultprice"],2).'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Extra Bed Price</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">&#8358 '.number_format($tdata["extrabedprice"],2).'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Child Fare</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">&#8358 '.number_format($tdata["childfare"],2).'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Hima Allocation</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["hima_allocation"].'</span>';
				$htmlresult .= '</div>';

				$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
				$htmlresult .= '<h4 class="large">Grade</h4>';
				$htmlresult .= '<span class="ft-xxsml-size">'.$tdata["grade"].'</span>';
				$htmlresult .= '</div>';

				##get all occupancy rate attached to this roomtype
				$room_occupancy_query = array("room_type_id"=>$fieldset,"deletedata"=>0);
				$get_room_occupancy_rate = mysqli_data_fetch($tbL54,'occupancy_type_id,price',$room_occupancy_query,'array');

				if(is_array($get_room_occupancy_rate)) {
					$show_room_ocr='';
					foreach ($get_room_occupancy_rate as $room_ocr_key => $room_ocr_value) {
						$show_room_ocr = idget_data($tbL51,$room_ocr_value['occupancy_type_id'],'name');
						$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-20 bottom-push-10">';
						$htmlresult .= '<h4 class="large">'.$show_room_ocr.' Occupancy Rate</h4>';
						$htmlresult .= '<span class="ft-xxsml-size">&#8358 '.number_format($room_ocr_value['price'],2).'</span>';
						$htmlresult .= '</div>';
					}
				}

				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-right nc-width-45">';

				##get all images attached to this roomtype
				$image_query = array("room_type_id"=>$fieldset,"deletedata"=>0);
				$get_image = mysqli_data_fetch($tbL53,'id,image',$image_query,'array');

				if(is_array($get_image)) {
					foreach ($get_image as $image_key => $image_value) {
						$htmlresult .= '<div class="ln-display-box float-left nc-width-45 nc-height-30 right-push-10 bottom-push-10 noscroll">';
						$htmlresult .= '<img src="'.DOMAIN_URL.'theme/images/general/roomtype/'.$image_value["image"].'" class="auto-wh">';
						$htmlresult .= '</div>';
					}
				}

				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element new-line-space">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';

			}

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['image']) && $_GET['image'] >= 1) && ($_GET['image'] == $dataid))
			{
				$fieldset = escape_data($_GET['image']);
				$dataurl="'dataurl'"; $upload="'notupload'"; $cimg="'cimg'"; $imagebox="'imagebox'";
				$fmsg="'fmsg'"; $msg="'attaching image..'"; $ffl="'f'";

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Add Room Image</h4><br>';
				$htmlresult .= '<div class="nc-width-80 alignct">';
				$htmlresult .= '<input onchange="resizeimage(event,350,350,'.$dataurl.','.$upload.','.$cimg.','.$imagebox.'); writeObjheader('.$fmsg.','.$msg.')" type="file" id="f" style="position: fixed; top: -100em">';
				$htmlresult .= '<input type="hidden" name="dataurl" id="dataurl">';
				$htmlresult .= '<small id="fmsg" class="block-element red-font bottom-push-10 alignlt"></small>';
				$htmlresult .= '<div id="imagebox" class="box-border-thick cs-width-400 cs-height-350 noscroll alignct">';
				$htmlresult .= '<div class="block-element nc-height-40"></div>';
				$htmlresult .= '<b class="fa-camera fa-mini-size" onclick="document.getElementById('.$ffl.').click()"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset1" id="fieldset1" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="imagebutton" value="Upload Image" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';

			}

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Room Type: '.$tdata["name"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Type</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter room type" value="'.$tdata["name"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Short Name</small>';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter room-type shortname" value="'.$tdata["shortname"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Description</small>';
				$htmlresult .= '<textarea name="fieldset3" id="fieldset3" placeholder="Enter description">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Grade</small>';
				$htmlresult .= '<input type="number" name="fieldset4" id="fieldset4" placeholder="Grade" value="'.$tdata["grade"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Adult</small>';
				$htmlresult .= '<input type="number" name="fieldset5" id="fieldset5" placeholder="Adult" value="'.$tdata["adult"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Child</small>';
				$htmlresult .= '<input type="number" name="fieldset6" id="fieldset6" placeholder="Child" value="'.$tdata["child"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Max Members Allowed</small>';
				$htmlresult .= '<input type="number" name="fieldset7" id="fieldset7" placeholder="Max Members Allowed?" value="'.$tdata["maxallow"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Extra Beds Allowed</small>';
				$htmlresult .= '<select name="fieldset8" id="fieldset8" required="required">';
				$htmlresult .= '<option value="'.$tdata["isextrabed"].'" selected="selected">'.$tdata["isextrabed"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">No of Rooms</small>';
				$htmlresult .= '<input type="number" name="fieldset9" id="fieldset9" placeholder="No of Rooms" required="required" value="'.$tdata["noofrooms"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Hima Allocation</small>';
				$htmlresult .= '<input type="number" name="fieldset10" id="fieldset10" placeholder="Hima Allocation" value="'.$tdata["hima_allocation"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Default Price</small>';
				$htmlresult .= '<input type="number" name="fieldset11" id="fieldset11" step="any" placeholder="Default Fare" value="'.$tdata["defaultprice"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Base Price (this will effect on bookings)</small>';
				$htmlresult .= '<input type="number" name="fieldset12" id="fieldset12" step="any" placeholder="Base Price" value="'.$tdata["baseprice"].'" required="required">';
				$htmlresult .= '</span>';

				$oct_constrain = array("deletedata"=>0,"status"=>"Active");
				$get_occupancy_type = mysqli_data_fetch($tbL51,'id,name,shortname',$oct_constrain,'array');

				if(is_array($get_occupancy_type)) {
					foreach ($get_occupancy_type as $oct_key => $oct_value) {
						
						$oct_query = array("room_type_id"=>$fieldset,"occupancy_type_id"=>$oct_value["id"]);
						$oct_exist = mysqli_data_fetch($tbL54,'id,price',$oct_query,'noarray');

						if($oct_exist[0] >= 1) {
							$htmlresult .= '<span class="block-element bottom-push-10">';
							$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">'.$oct_value["name"].' Occupancy Rate</small>';
							$htmlresult .= '<input type="number" name="occupancy_types[]" step="any" value="'.$oct_exist[1].'"><input type="hidden" name="occupancy_types_id[]" value="'.$oct_value["id"].'">';
							$htmlresult .= '</span>';
						}
						
					}
				}

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Extra Bed Price</small>';
				$htmlresult .= '<input type="number" name="fieldset13" id="fieldset13" step="any" placeholder="Extra Bed Price" value="'.$tdata["extrabedprice"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Child Fare</small>';
				$htmlresult .= '<input type="number" name="fieldset14" id="fieldset14" step="any" placeholder="Child Fare" value="'.$tdata["childfare"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Minimum Deposit</small>';
				$htmlresult .= '<input type="number" name="fieldset15" id="fieldset15" step="any" placeholder="Minimum Deposit" value="'.$tdata["minimumdeposit"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Minimum Deposit Mandatory</small>';
				$htmlresult .= '<select name="fieldset16" id="fieldset16" required="required">';
				$htmlresult .= '<option value="'.$tdata["ismandatory_minimum_deposit"].'" selected="selected">'.$tdata["ismandatory_minimum_deposit"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Smoking Allowed</small>';
				$htmlresult .= '<select name="fieldset17" id="fieldset17" required="required">';
				$htmlresult .= '<option value="'.$tdata["issmoking"].'" selected="selected">'.$tdata["issmoking"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Facilities</small>';

				$amn_constrain = array("deletedata"=>0,"status"=>"Active");
				$get_amenities = mysqli_data_fetch($tbL13,'id,name',$amn_constrain,'array');

				if(is_array($get_amenities)) {
					foreach ($get_amenities as $amn_key => $amn_value) {
						
						$amn_query = array("room_type_id"=>$fieldset,"amenityid"=>$amn_value["id"]);
						$amn_exist = mysqli_data_fetch($tbL55,'id',$amn_query,'noarray');

						if(isset($amn_exist[0]) && $amn_exist[0] >= 1) {
							$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-15">';
							$htmlresult .= '<b class="fa-checker nobold"></b>';
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-85 alignlt">';
							$htmlresult .= $amn_value["name"];
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="block-element new-line-space"></span>';
							$htmlresult .= '</div>';
						} else {
							$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-15">';
							$htmlresult .= '<input type="checkbox" name="amenities[]" value="'.$amn_value["id"].'">';
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-85 alignlt">';
							$htmlresult .= $amn_value["name"];
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="block-element new-line-space"></span>';
							$htmlresult .= '</div>';
						}
					}
				}

				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset18" id="fieldset18" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL52,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>