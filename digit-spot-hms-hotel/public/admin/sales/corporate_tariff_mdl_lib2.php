<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new corporate tariff by clicking <u>new tariff</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Tariff
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>

function changefield() {
	if(document.getElementById('fieldset3').value == 'Percentage') {
		document.getElementById('cff').innerHTML = '<input type="number" name="fieldset2" id="fieldset2" step="any" value="0.5">';
	} else if(document.getElementById('fieldset3').value == 'Amount') {
		document.getElementById('cff').innerHTML = '<input type="number" name="fieldset2" id="fieldset2" value="0">';
	}
}

</script>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');
	$corporatelist = select_dt_fetch('',0,$tbL58,'id','name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_77); //create a table for this post
		createDatabasetable($var_tbl_78); //create a table for this post
		

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset3']);
		$fieldset3 = escape_data($_POST['fieldset4']);

		if(isset($fieldset2) && $fieldset2 == 'Percentage') {
			$fieldset4 = escape_data($_POST['fieldset2']);
			$fieldset5 = 0;
		} else if(isset($fieldset2) && $fieldset2 == 'Amount') {
			$fieldset4 = 0;
			$fieldset5 = escape_data($_POST['fieldset2']);
		}
		
		$insert_dataproperty = array("corporateid"=>$fieldset3,"room_type_id"=>$fieldset1,"tarifftype"=>$fieldset2,"tariffamount"=>$fieldset5,"discount"=>$fieldset4);
		$insert_constrain = array("corporateid"=>$fieldset3,"room_type_id"=>$fieldset1);
		$data_inserted = mysqli_data_insert($tbL81,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_corp_tarrif_id = $mysqli_id; //new room-type created

			##for taxes selected
			$tax_arr = $_POST['taxes'];
			if(isset($tax_arr)) {
				foreach($tax_arr as $tax) {
					$tax_select_arr = array("corporateid"=>$fieldset3,"room_type_id"=>$fieldset1,"taxid"=>$tax);
					mysqli_data_insert($tbL82,$tax_select_arr,'');
				}
			}

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new corporate tariff on rooms","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
							<h3 class="nomargin">Creating New Corporate Tariff</h3>
						</div>
						<span class="block-element bottom-push-15">
							<select name="fieldset1" id="fieldset1" required="required">
								<option value="" selected="selected">Room Type</option>
								<?php echo $roomtypes; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-10 blue-font left-pull-5 alignlt">Tariff Type</small>
							<div class="ln-display-box float-left nc-width-60" id="cff">
								<input type="number" name="fieldset2" id="fieldset2" step="any" value="0.5">
							</div>
							<div class="ln-display-box float-right nc-width-30">
								<select name="fieldset3" id="fieldset3" required="required" onchange="changefield()">
									<option value="Percentage">Percentage</option>
									<option value="Amount">Amount</option>
								</select>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-10 ft-xsml-size alignlt left-pull-5 blue-font">Tax Details</small>
							<?php
								
								$tax_constrain = array("deletedata"=>0);
								$get_tax = mysqli_data_fetch($tbL35,'id,taxname',$tax_constrain,'array');

								if(is_array($get_tax)) {
									$tax_name = "";
									foreach ($get_tax as $tax_key => $tax_value) {
										$tax_name = arrayget_key($tax_charges,$tax_value['taxname']);
										?><div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">
											<span class="ln-display-box float-left nc-width-15">
												<input type="checkbox" name="taxes[]" value="<?php echo $tax_value['taxname']; ?>">
											</span>
											<span class="ln-display-box float-left nc-width-85 alignlt">
												<?php echo $tax_name; ?>
											</span>
											<span class="block-element new-line-space"></span>
										</div><?php
									}
								}

							?>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset4" id="fieldset4" required="required">
								<option value="" selected="selected">Corporate</option>
								<?php echo $corporatelist; ?>
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
			$cstat = mysqli_data_update($tbL81,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change corporate tariff status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$fieldset2 = escape_data($_POST['fieldset3']);
		$fieldset3 = escape_data($_POST['fieldset4']);

		if(isset($fieldset2) && $fieldset2 == 'Percentage') {
			$fieldset4 = escape_data($_POST['fieldset2']);
			$fieldset5 = 0;
		} else if(isset($fieldset2) && $fieldset2 == 'Amount') {
			$fieldset4 = 0;
			$fieldset5 = escape_data($_POST['fieldset2']);
		}
		
		$insert_dataproperty = array("corporateid"=>$fieldset3,"room_type_id"=>$fieldset1,"tarifftype"=>$fieldset2,"tariffamount"=>$fieldset5,"discount"=>$fieldset4);
		$insert_constrain = array("id"=>$fieldset6);
		$data_inserted = mysqli_data_update($tbL81,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			
			##for taxes selected
			$tax_arr = $_POST['taxes'];
			if(isset($tax_arr)) {
				foreach($tax_arr as $tax) {
					$tax_select_arr = array("corporateid"=>$fieldset3,"room_type_id"=>$fieldset1,"taxid"=>$tax);
					$chk_exist_data = mysqli_data_fetch($tbL82,'id',$tax_select_arr,'noarray');
					if(!isset($chk_exist_data[0])) { mysqli_data_insert($tbL82,$tax_select_arr,''); }
				}
			}

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit corporate tariff details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
			$del = mysqli_data_update($tbL81,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove corporate tariff rooms","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if(isset($_POST['searchbutton'])) {
		
		if(isset($_POST['search1']) && !empty($_POST['search1'])) { $corporate_query = " AND corporateid = ".$_POST['search1']; }
		else { $corporate_query = ""; }

		if(isset($_POST['search2']) && !empty($_POST['search2'])) { $roomtype_query = " AND room_type_id = ".$_POST['search2']; }
		else { $roomtype_query = ""; }

		$keywords=$corporate_query.$roomtype_query;

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

	$dataproperty = "id,corporateid,room_type_id,tarifftype,tariffamount,discount,status";
	$constrain = array("deletedata"=>0,"status"=>"Active");
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL81,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","room type","corporate","tariff type","tariff amount","discount apply","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-60">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-10 right-push-10 alignlt top-pull-15">';
		$htmlresult .= '<small class="">Search by: </small>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-35 right-push-10">';
		$htmlresult .= '<select name="search1" id="search1" onchange="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '<option value="" selected="selected">Corporate</option>';
		$htmlresult .= '<option value="">All</option>';
		$htmlresult .= $corporatelist;
		$htmlresult .= '</select>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30">';
		$htmlresult .= '<select name="search2" id="search2" onchange="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '<option value="" selected="selected">Room Type</option>';
		$htmlresult .= '<option value="">All</option>';
		$htmlresult .= $roomtypes;
		$htmlresult .= '</select>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-20 alignrt">';
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
		
		$num=$pgstart; $g=""; $dataid="";  $hotelroomtype=""; $hotelcorporate="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$additionalQuery = "";
			$hotelroomtype = idget_data($tbL52,$tdata["room_type_id"],'name');
			$hotelcorporate = idget_data($tbL58,$tdata["corporateid"],'name');

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$hotelroomtype.'</td>';
			$htmlresult .= '<td width="250px" align="center" class="box-border-thick-right">'.$hotelcorporate.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["tarifftype"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">&#8358 '.number_format($tdata["tariffamount"],2).'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["discount"].' %</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Corporate Tariff</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Room Type</small>';
				$htmlresult .= '<select name="fieldset1" id="fieldset1" required="required">';
				$htmlresult .= '<option value="'.$tdata["room_type_id"].'" selected="selected">'.$hotelroomtype.'</option>';
				$htmlresult .= $roomtypes;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Tariff Type</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-60" id="cff">';
				
				if($tdata["tarifftype"] == 'Percentage') {
					$htmlresult .= '<input type="number" name="fieldset2" id="fieldset2" step="any" value="'.$tdata["discount"].'">';
				} elseif($tdata["tarifftype"] == 'Amount') {
					$htmlresult .= '<input type="number" name="fieldset2" id="fieldset2" value="'.$tdata["tariffamount"].'">';
				}

				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-30">';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required" onchange="changefield()">';
				$htmlresult .= '<option value="'.$tdata["tarifftype"].'" selected="selected">'.$tdata["tarifftype"].'</option>';
				$htmlresult .= '<option value="Percentage">Percentage</option>';
				$htmlresult .= '<option value="Amount">Amount</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Tax Detail</small>';

				$tax_constrain = array("deletedata"=>0);
				$get_tax = mysqli_data_fetch($tbL35,'id,taxname',$tax_constrain,'array');

				if(is_array($get_tax)) {
					foreach ($get_tax as $tax_key => $tax_value) {
						$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">';
						$htmlresult .= '<span class="ln-display-box float-left nc-width-15">';
						$htmlresult .= '<input type="checkbox" name="taxes[]" value="'.$tax_value["id"].'">';
						$htmlresult .= '</span>';
						$htmlresult .= '<span class="ln-display-box float-left nc-width-85 alignlt">';
						$htmlresult .= $tax_value["taxname"];
						$htmlresult .= '</span>';
						$htmlresult .= '<span class="block-element new-line-space"></span>';
						$htmlresult .= '</div>';
					}
				}

				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Corporate</small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required">';
				$htmlresult .= '<option value="'.$tdata["corporateid"].'" selected="selected">'.$hotelcorporate.'</option>';
				$htmlresult .= $roomtypes;
				$htmlresult .= '</select>';
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
	mysqli_data_check($tbL81,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>