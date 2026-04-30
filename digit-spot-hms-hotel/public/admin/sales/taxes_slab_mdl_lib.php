<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new tax slab by clicking <u>new tax slab</u> button.
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Tax Slab
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
		createDatabasetable($var_tbl_86); //create a table for this post
		createDatabasetable($var_tbl_87); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		

		$insert_dataproperty = array("bill_from"=>$fieldset1,"bill_to"=>$fieldset2,"detail"=>$fieldset3,"sequencenumber"=>$fieldset4);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL90,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$bill_tax_slab_id = $mysqli_id;

			##for tax slab list
			$tax_slab_percent = $_POST['tx'];
			$tax_id = $_POST['txid'];

			for($d=0; $d < count($tax_id); $d++) {
				
				$tx_arr = array("taxslab"=>$bill_tax_slab_id,"taxid"=>$tax_id[$d],"taxcharges"=>$tax_slab_percent[$d]);
				$tx_arr_query = array("taxslab"=>$bill_tax_slab_id,"taxid"=>$tax_id[$d]);
				mysqli_data_insert($tbL91,$tx_arr,$tx_arr_query);
				
			}
			

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new taxes slab","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{
		$additionalQuery = " ORDER BY id DESC LIMIT 1";
		$constrain = array("deletedata"=>0);
		$get_last_record = mysqli_data_fetch($tbL90,'id,bill_to,sequencenumber',$constrain,'noarray');

		if(isset($get_last_record[0]) && $get_last_record[0] >= 1) {
			$bill_from = $get_last_record[1] + 1;
			$sequence_number = $get_last_record[2] + 1;
		} else {
			$bill_from = 1;
			$sequence_number = 1;
		}

		
		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Tax Slab</h3>
						</div>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-3 left-pull-5 alignlt">Bill from (default)</small>
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter start from" value="<?php echo $bill_from; ?>" required="required" readonly="readonly">
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-3 left-pull-5 alignlt">Bill to</small>
							<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter end to e.g 100000" pattern="\d*" maxlength="5" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-3 left-pull-5 alignlt">Description</small>
							<textarea name="fieldset3" id="fieldset3" placeholder="Enter description" required="required"></textarea>
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-5 left-pull-5 alignlt">Taxes (%)</small>

							<?php
								
								$additionalQuery = "";
								$tax_constrain = array("deletedata"=>0);
								$get_tx = mysqli_data_fetch($tbL35,'id,taxname',$tax_constrain,'array');

								if(is_array($get_tx)) {
									foreach ($get_tx as $tx_key => $tx_value) {
										?>
											<div class="ln-display-box float-left nc-width-30 right-push-5 bottom-push-5">
												<small class="block-element bottom-push-3 left-pull-5 alignlt dark-grey-font ft-xxsml-size">
													<?php echo $tx_value['taxname']; ?>
												</small>
												<input type="number" name="tx[]" step="any" placeholder="0.0" required="required">
												<input type="hidden" name="txid[]" value="<?php echo $tx_value['id']; ?>">
											</div>
										<?php
									}
								}

							?>

							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-3 left-pull-5 alignlt">Sequence number</small>
							<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter sequence number" value="<?php echo $sequence_number; ?>" required="required" readonly="readonly">
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
			$cstat = mysqli_data_update($tbL90,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change tax slab status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		
		$bill_tax_slab_id = $fieldset1;

		##for tax slab list
		$tax_slab_percent = $_POST['tx'];
		$tax_id = $_POST['txid'];

		for($d=0; $d < count($tax_id); $d++) {	
			
			$tx_arr = array("taxslab"=>$bill_tax_slab_id,"taxid"=>$tax_id[$d],"taxcharges"=>$tax_slab_percent[$d]);
			$tx_arr_query = array("taxslab"=>$bill_tax_slab_id,"taxid"=>$tax_id[$d]);
			mysqli_data_update($tbL91,$tx_arr,$tx_arr_query);
		}

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit taxes slab","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
		
		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$update_result .= '<span class="red-font">Changes were added successfully</span>';
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
			$del = mysqli_data_update($tbL90,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from tax slab list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND detail LIKE '".escape_data($_POST['search'])."%'";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 30;
		$additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,bill_from,bill_to,detail,sequencenumber,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL90,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","sequence","from","to","description","taxes","total tax","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
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
		
		$num=$pgstart; $g=""; $dataid="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

			$additionalQuery="";
			$taxslab_query = array("taxslab"=>$dataid);
			$get_taxslabs = mysqli_data_fetch($tbL91,'taxid,taxcharges',$taxslab_query,'array');
			$tx_list = '<select>';
			
			if(is_array($get_taxslabs)) {
				$total_tax_slabs = 0; $tax_name = ''; $htmlresult_in = '';
				foreach ($get_taxslabs as $txs_key => $txs_value) {
					$total_tax_slabs = $total_tax_slabs + $txs_value['taxcharges'];
					$tax_name = idget_data($tbL35,$txs_value['taxid'],'taxname');
					$tx_list .= '<option>'.$tax_name.'</option>';

					$htmlresult_in .= '<div class="ln-display-box float-left nc-width-30 right-push-5 bottom-push-5">';
					$htmlresult_in .= '<small class="block-element bottom-push-3 left-pull-5 alignlt dark-grey-font ft-xxsml-size">';
					$htmlresult_in .= $tax_name;
					$htmlresult_in .= '</small>';
					$htmlresult_in .= '<input type="number" name="tx[]" step="any" placeholder="0.0" value="'.$txs_value['taxcharges'].'" required="required">';
					$htmlresult_in .= '<input type="hidden" name="txid[]" value="'.$txs_value['taxid'].'">';
					$htmlresult_in .= '</div>';
				}
			}

			$tx_list .= '</select>';
		
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["sequencenumber"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["bill_from"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.number_format($tdata["bill_to"],2).'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["detail"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tx_list.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$total_tax_slabs.' %</td>';
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
				$htmlresult .= '<h4 class="large blue-font">Updating Taxes Slab</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 left-pull-5">Taxes (%)</small>';
				$htmlresult .= $htmlresult_in;
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset1" id="fieldset1" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL90,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>