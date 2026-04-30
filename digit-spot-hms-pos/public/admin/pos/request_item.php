<?php
	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	#get all applcable workflows
	$irworkFlow = getjob_workflow(4);
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: you can request for item by clicking <u>make request</u> button. Follow on-screen instructions
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="objDisplay('requestbox')">Make Request</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$cur_pos_store_id = $_SESSION['postoreid'];
	$cur_pos_storage = idget_data($tbL14,$cur_pos_store_id,'store');
	$storage_name = idget_data($tbL123,$cur_pos_storage,'store_name');

	#-----------------------------------------------------------------------------------------------------------------

	createDatabasetable($var_tbl_300); //create a table for this post

	$item_categories = select_dt_fetch('status','Active',$tbL115,'id','category');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['uri']) && $_POST['uri'] == 'submit-item-request')
	{
		//createDatabasetable($var_tbl_10);

		$item = $_POST['item'];
		$qty = $_POST['qty'];
		$stock_type = $_POST['stocktype'];

		$request_number = prgSequence($tbL155,'IR');

		$insert_data = 0;

		for($i=0; $i < count($item); $i++) {
			$get_selling_uom = idget_data($tbL118,$item[$i],'selling_unit');
			$insert_dataproperty = array();
			$insert_constrain = array();

			$insert_dataproperty['request_number'] = $request_number;
			$insert_dataproperty['posid'] = $cur_pos_store_id;
			$insert_dataproperty['storeid'] = $cur_pos_storage;
			$insert_dataproperty['itemid'] = escape_data($item[$i]);
			$insert_dataproperty['uom'] = $get_selling_uom;
			$insert_dataproperty['qty_required'] = escape_data($qty[$i]);
			$insert_dataproperty['stock_type'] = $stock_type[$i];
			$insert_dataproperty['userid'] = $userSignedIn;
			$insert_dataproperty['datelogged'] = $server_get_date;
			$insert_dataproperty['timelogged'] = $server_get_time;
			
			$insert_dataproperty['posid'] = $cur_pos_store_id;
			$insert_dataproperty['itemid'] = $item[$i];
			$insert_dataproperty['stock_type'] = $stock_type[$i];
			$insert_dataproperty['status'] = 'Reviewing';

			$data_inserted = mysqli_data_insert($tbL152,$insert_dataproperty,$insert_constrain);
			if($data_inserted == 2) { $insert_data += 1; }

			$get_selling_uom = 0;
		}
		

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($insert_data) && $insert_data > 0)
		{
			//create a log file
			$pos_counter_name = idget_data($tbL14,$cur_pos_store_id,'posname');
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently create item request for {$pos_counter_name} pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="light-red-font ft-sml-size">Item request submitted successfully</span>';
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
			$del = mysqli_data_update($tbL152,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Removed pending item request list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['acceptbutton']) && !empty($_POST['requestno'])) {

		$dataproperty = "itemid,uom,qty_received,stock_type";
		$constrain = array("posid"=>$cur_pos_store_id,"request_number"=>escape_data($_POST['requestno']),"deletedata"=>0);
		$for_ir_data = mysqli_data_fetch($tbL152,$dataproperty,$constrain,'array');

		if(is_array($for_ir_data)) {
			$pst_query = ""; $pst_field = "";
			foreach($for_ir_data as $key => $val) {
				
				$chkIfitem = idget_fdata($tbL16,'itemcode',$val['itemid'],'id');
				
				$get_item_name = idget_data($tbL118,$val['itemid'],'item');
				$get_item_ctgid = idget_data($tbL118,$val['itemid'],'categoryid');
				$get_item_subctgid = idget_data($tbL118,$val['itemid'],'subcategoryid');
				
				$get_item_cost = idget_fdata($tbL156,'itemid',$val['itemid'],'unitprice');
				$get_item_stockout = idget_fdata($tbL156,'itemid',$val['itemid'],'stockout');
				$get_item_stockbal = idget_fdata($tbL156,'itemid',$val['itemid'],'balance');
				

				if(is_numeric($chkIfitem) && $chkIfitem > 0) {
					if($val['stock_type'] == 'consumable') {
						
						$stockin = idget_fdata($tbL16,'itemcode',$val['itemid'],'stockin');
						$balance = idget_fdata($tbL16,'itemcode',$val['itemid'],'balance');
						$new_stock = $stockin + $val['qty_received'];
						$new_balance = $balance + $val['qty_received'];

						$pst_query = array("itemcode"=>$val['itemid']);
						$pst_field = array("item"=>$get_item_name,"stockin"=>$new_stock,"cost"=>$get_item_cost,"balance"=>$new_balance);
						mysqli_data_update($tbL16,$pst_field,$pst_query);

					} elseif($val['stock_type'] == 'serviceable') {
						$pst_query = array("itemcode"=>$val['itemid']);
						$pst_field = array("storageid"=>$cur_pos_storage,"storagetype"=>$val['stock_type'],"postoreid"=>$cur_pos_store_id,"categoryid"=>$get_item_ctgid,"subcategoryid"=>$get_item_subctgid,"itemcode"=>$val['itemid'],"item"=>$get_item_name,"stockin"=>$val['qty_received'],"uom"=>$val['uom'],"cost"=>$get_item_cost,"price"=>0,"stockout"=>0,"balance"=>$val['qty_received'],"isfeature"=>"No","isstaff"=>"No");
						mysqli_data_insert($tbL16,$pst_field,$pst_query);
					}
				} else {
					$pst_query = array("itemcode"=>$val['itemid']);
					$pst_field = array("storageid"=>$cur_pos_storage,"storagetype"=>$val['stock_type'],"postoreid"=>$cur_pos_store_id,"categoryid"=>$get_item_ctgid,"subcategoryid"=>$get_item_subctgid,"itemcode"=>$val['itemid'],"item"=>$get_item_name,"stockin"=>$val['qty_received'],"uom"=>$val['uom'],"cost"=>$get_item_cost,"price"=>0,"stockout"=>0,"balance"=>$val['qty_received'],"isfeature"=>"No","isstaff"=>"No");
					mysqli_data_insert($tbL16,$pst_field,$pst_query);
				}

				$new_stockout = $get_item_stockout + $val['qty_received'];
				$new_stockbal = $get_item_stockbal - $val['qty_received'];

				$pst_query = array("itemid"=>$val['itemid']);
				$pst_field = array("stockout"=>$new_stockout,"balance"=>$new_stockbal);
				mysqli_data_update($tbL156,$pst_field,$pst_query);

				$new_stockout = "";
				$new_stockbal = "";
			}

			$pst_query = array("posid"=>$cur_pos_store_id,"request_number"=>escape_data($_POST['requestno']));
			$pst_field = array("acceptance"=>1); mysqli_data_update($tbL152,$pst_field,$pst_query);

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Stock was updated successfully</span>';
			$post_result .= '</div>';
		}

		echo $post_result;
	}

?>

<div id="requestbox" class="noshow pads20 sml-rounded-button bottom-push-30">
	<div class="ln-display-box float-left nc-width-30 box-border-thick xsml-rounded-button white-theme obj-light-shadow">
		<div class="pads20">
			<span class="block-element bottom-push-5">
				<select name="category" id="category" onchange="getdata('subcategory','eget-sub-category-list','category','dropbox');">
					<option value="" selected="selected">By Category</option>
					<?php echo $item_categories; ?>
				</select>
			</span>
			<span class="block-element bottom-push-15">
				<select name="subcategory" id="subcategory" onchange="wgtSublist()">
					<option value="" selected="selected">By Sub-category</option>
				</select>
			</span>

			<span class="block-element alignct bottom-push-15">&mdash;&mdash;&mdash; OR &mdash;&mdash;&mdash;</span>

			<span class="block-element">
				<input type="text" name="byname" id="byname" placeholder="Search by item name?" onkeyup="wgtSublist2(this.value)" autocomplete="off">
			</span>
		</div>
		<div id="list-item" class="pads20 box-border-thick-top cs-height-250 y-scroll">
			<h4 class="large nobold dark-grey-font alignct">Search to list item?</h4>
		</div>
 	</div>
 	<div class="ln-display-box float-right nc-width-65 grey-1-theme pads20">
		<h4 class="xlarge nobold black-font alignct">All selected items display here</h4><br>
		<form id="for-ir" action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<input type="hidden" name="uri" value="submit-item-request">
			<div class="xsml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<th align="center">&nbsp;</th>
						<th align="center">Item</th>
						<th align="center">Qty Requesting</th>
						<th align="center">Stock Type</th>
					</tr>
					<tbody id="datalist"></tbody>
				</table>
			</div>
			<div id="sendbutton" class="noshow top-push-20">
				<input type="hidden" name="workflow" id="workflow">
				<input type="submit" name="submitbutton" value="Submit Request" class="submit pads10 black-white-state rounded-button nc-width-30 anchor">
			</div>
		</form>
	</div>
	<div class="block-element new-line-space">
		<!-- clear line -->
	</div>
</div>


<div id="irbox" class="noshow fx-position-rel zind-2 motion top-pull-50" align="right">
	<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick obj-shadow" onclick="forsty('',0)">
		<h3 id="irheader" class="large nobold default-text-font-bold light-red-font">Confirm Stock Type?</h3>
		<h3 id="irmessage" class="large nobold anchor"></h3>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<?php
	
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$additionalQuery = " GROUP BY request_number ORDER BY id DESC";
	$dataproperty = "request_number";
	$constrain = array("deletedata"=>0,"posid"=>$cur_pos_store_id,"acceptance"=>0);
	$dataGroup = mysqli_data_fetch($tbL152,$dataproperty,$constrain,'array');

	if(is_array($dataGroup))
	{
		$ir_workflow = "";

		foreach($dataGroup as $theader => $tdata)
		{
			$additionalQuery = "";
			$fields = "job_level,user_one,approval_one,user_two,approval_two,user_three,approval_three,user_four,approval_four,user_five,approval_five";
			$query = array("subject"=>$tdata['request_number'],"approval_type"=>"ITEM DISBURST");
			$jpL = mysqli_data_fetch($tbL151,$fields,$query,'noarray');

			if($jpL[0] == 1) {
				
				$userone = idget_data($tbL7,$jpL[1],'staffname');
				$useone_roleid = idget_data($tbL7,$jpL[1],'role');
				$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
				$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
				
				$ir_workflow .= "First Level Approval: \n";
				$ir_workflow .= $userone." (".$userone_rolename.") \n";
				$ir_workflow .= $apr_stat_one;

			} elseif($jpL[0] == 2) {

				$userone = idget_data($tbL7,$jpL[1],'staffname');
				$useone_roleid = idget_data($tbL7,$jpL[1],'role');
				$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
				$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
				
				$ir_workflow .= "First Level Approval: \n";
				$ir_workflow .= $userone." (".$userone_rolename.") \n";
				$ir_workflow .= $apr_stat_one." \n\n";

				$usertwo = idget_data($tbL7,$jpL[3],'staffname');
				$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
				$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
				$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
				
				$ir_workflow .= "Second Level Approval: \n";
				$ir_workflow .= $usertwo." (".$usertwo_rolename.") \n";
				$ir_workflow .= $apr_stat_two." \n\n";

			} elseif($jpL[0] == 3) {

				$userone = idget_data($tbL7,$jpL[1],'staffname');
				$useone_roleid = idget_data($tbL7,$jpL[1],'role');
				$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
				$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
				
				$ir_workflow .= "First Level Approval: \n";
				$ir_workflow .= $userone." (".$userone_rolename.") \n";
				$ir_workflow .= $apr_stat_one." \n\n";

				$usertwo = idget_data($tbL7,$jpL[3],'staffname');
				$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
				$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
				$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
				
				$ir_workflow .= "Second Level Approval: \n";
				$ir_workflow .= $usertwo." (".$usertwo_rolename.") \n";
				$ir_workflow .= $apr_stat_two." \n\n";

				$userthree = idget_data($tbL7,$jpL[5],'staffname');
				$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
				$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
				$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
				
				$ir_workflow .= "Third Level Approval: \n";
				$ir_workflow .= $userthree." (".$userthree_rolename.") \n";
				$ir_workflow .= $apr_stat_three." \n\n";

			} elseif($jpL[0] == 4) {

				$userone = idget_data($tbL7,$jpL[1],'staffname');
				$useone_roleid = idget_data($tbL7,$jpL[1],'role');
				$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
				$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
				
				$ir_workflow .= "First Level Approval: \n";
				$ir_workflow .= $userone." (".$userone_rolename.") \n";
				$ir_workflow .= $apr_stat_one." \n\n";

				$usertwo = idget_data($tbL7,$jpL[3],'staffname');
				$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
				$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
				$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
				
				$ir_workflow .= "Second Level Approval: \n";
				$ir_workflow .= $usertwo." (".$usertwo_rolename.") \n";
				$ir_workflow .= $apr_stat_two." \n\n";

				$userthree = idget_data($tbL7,$jpL[5],'staffname');
				$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
				$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
				$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
				
				$ir_workflow .= "Third Level Approval: \n";
				$ir_workflow .= $userthree." (".$userthree_rolename.") \n";
				$ir_workflow .= $apr_stat_three." \n\n";

				$userfour = idget_data($tbL7,$jpL[7],'staffname');
				$usefour_roleid = idget_data($tbL7,$jpL[7],'role');
				$userfour_rolename = idget_data($tbL4,$usefour_roleid,'role');
				$apr_stat_four = arrayget_key($approval_status,$jpL[8]);
				
				$ir_workflow .= "Fourth Level Approval: \n";
				$ir_workflow .= $userfour." (".$userfour_rolename.") \n";
				$ir_workflow .= $apr_stat_four." \n\n";

			} elseif($jpL[0] == 5) {

				$userone = idget_data($tbL7,$jpL[1],'staffname');
				$useone_roleid = idget_data($tbL7,$jpL[1],'role');
				$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
				$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
				
				$ir_workflow .= "First Level Approval: \n";
				$ir_workflow .= $userone." (".$userone_rolename.") \n";
				$ir_workflow .= $apr_stat_one." \n\n";

				$usertwo = idget_data($tbL7,$jpL[3],'staffname');
				$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
				$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
				$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
				
				$ir_workflow .= "Second Level Approval: \n";
				$ir_workflow .= $usertwo." (".$usertwo_rolename.") \n";
				$ir_workflow .= $apr_stat_two." \n\n";

				$userthree = idget_data($tbL7,$jpL[5],'staffname');
				$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
				$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
				$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
				
				$ir_workflow .= "Third Level Approval: \n";
				$ir_workflow .= $userthree." (".$userthree_rolename.") \n";
				$ir_workflow .= $apr_stat_three." \n\n";

				$userfour = idget_data($tbL7,$jpL[7],'staffname');
				$usefour_roleid = idget_data($tbL7,$jpL[7],'role');
				$userfour_rolename = idget_data($tbL4,$usefour_roleid,'role');
				$apr_stat_four = arrayget_key($approval_status,$jpL[8]);
				
				$ir_workflow .= "Fourth Level Approval: \n";
				$ir_workflow .= $userfour." (".$userfour_rolename.") \n";
				$ir_workflow .= $apr_stat_four." \n\n";

				$userfive = idget_data($tbL7,$jpL[9],'staffname');
				$usefive_roleid = idget_data($tbL7,$jpL[9],'role');
				$userfive_rolename = idget_data($tbL4,$usefive_roleid,'role');
				$apr_stat_five = arrayget_key($approval_status,$jpL[10]);
				
				$ir_workflow .= "Fifth Level Approval: \n";
				$ir_workflow .= $userfive." (".$userfive_rolename.") \n";
				$ir_workflow .= $apr_stat_five;

			} else {
				$ir_workflow .= "No approval workflow: \n";
				$ir_workflow .= "You may contact warehouse supply staff";
			}


			$additionalQuery = "";
			$dataproperty = "id,storeid,itemid,uom,qty_required,qty_received,stock_type,userid,whr_user,status,datelogged,timelogged";
			$constrain = array("request_number"=>$tdata['request_number'],"deletedata"=>0,"posid"=>$cur_pos_store_id,"acceptance"=>0);
			$dataCollect = mysqli_data_fetch($tbL152,$dataproperty,$constrain,'array');

			if(is_array($dataCollect))
			{
				$thproperty = array("enoth","noth","item","qty request","qty received","stock type","logged by","status");
				$tcount = count($thproperty);

				$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
				
				$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
				$htmlresult .= '<input type="hidden" name="requestno" value="'.$tdata['request_number'].'">';
				$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
				$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15">';
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
				$htmlresult .= '<span class="float-right"><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="window.print()">Print <b class="fa-print left-push-5"></b></a></span>';
				$htmlresult .= '<h3 class="large nobold">Request No: <b class="nobold default-text-font-bold right-push-5">'.$tdata['request_number'].'</b> <a href="javascript://" class="blue-font ft-xsml-size" title="'.$ir_workflow.'">&hookrightarrow; See report status</a></h3>';
				$htmlresult .= '<div id="section-to-print" class="block-element sml-rounded-button noscroll">';
				$htmlresult .= '<table cellpadding="0" cellspacing="0">';
				$htmlresult .= '<tr>';
				
				$thu=0; $uclass="";
				
				foreach($thproperty as $th)
				{
					$thu += 1;
					
					if($tcount == $thu) { $uclass=''; }
					else { $uclass='class="box-border-thick-right"'; }
					
					if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
					elseif($th == 'enoth') { $htmlresult .= '<th width="40px" '.$uclass.' align="center">&nbsp;</th>'; }
					else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
				}
				
				$htmlresult .= '</tr>';
				
				$num=$pgstart; $g=""; $dataid=""; $get_item_name=""; $get_uom=""; $loggedby=0; $disabled=""; $isdisbursed=0;

				foreach($dataCollect as $theader => $tdata)
				{
					$num += 1;
					$g = $num / 2;

					$dataid = $tdata["id"];

					$additionalQuery = "";
					
					$loggedby = idget_data($tbL7,$tdata['userid'],'staffname');
					$get_item_name = idget_data($tbL118,$tdata['itemid'],'item');
					$get_uom = arrayget_key($uoms,$tdata['uom']);
					
					$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

					if($tdata["qty_received"] > 0) { $disabled=' disabled="disabled"'; } else { $disabled=''; }
					if($tdata["status"] == 'Ready to Disburse') { $isdisbursed += 1; $status_color="forest-green-font"; }
					elseif($tdata["status"] == 'Reviewing' || $tdata["status"] == 'Under Approval') { $status_color="light-red-font"; }
							
					$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
					$htmlresult .= '<td width="40px" class="box-border-thick-right" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"'.$disabled.'></td>';
					$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
					//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$storage_name.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$get_item_name.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["qty_required"].' '.$get_uom.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["qty_received"].'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.ucfirst($tdata["stock_type"]).'</td>';
					$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">'.$loggedby.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right default-text-font-bold '.$status_color.'">'.$tdata["status"].'</td>';
					$htmlresult .= '</tr>';

					#----------------------------------------------------------------------------------------------------------------------------------------------------
				}

				/*if($isdisbursed > 0) {
					$htmlresult .= '<tr class="grey-theme">';
					$htmlresult .= '<td colspan="10" align="right" class="pads10"><input type="submit" name="acceptbutton" value=" Accept to stock " class="pads10 blue-white-state rounded-button nc-width-20" title="Click to update your stock"></td>';
					$htmlresult .= '</tr>';
				}*/
				
				$htmlresult .= '</table>';
				$htmlresult .= '</div>';
				$htmlresult .= '</form>';
				$htmlresult .= '<br><br>';
			}
		}

	} else {
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

?>

<script>

	function wgtSublist() {
		var fs_selector = document.getElementById('category');
		var ss_selector = document.getElementById('subcategory');
		
		sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE categoryid="+fs_selector.value+" AND subcategoryid="+ss_selector.value+" AND status='Active' AND deletedata=0";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

			for(i=0; i<data.length; i++) {
				vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].selling_unit+'" title="'+data[i].item+'" onclick="jsAdd('+data[i].id+',this.title,this.lang)">';
				vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
				vhtml += '<h3 class="large nobold default-text-font-bold">'+data[i].item+'</h3>';
				vhtml += '</div>';
			}

			document.getElementById('list-item').innerHTML = vhtml;
			
		}
	}

	function wgtSublist2() {

		var searchbyname = document.getElementById('byname');

		if(searchbyname.value !== null && searchbyname.value != '') {

			sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE item REGEXP '^"+searchbyname.value+"' AND status='Active' AND deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

				for(i=0; i<data.length; i++) {
					vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].selling_unit+'" title="'+data[i].item+'" onclick="jsAdd('+data[i].id+',this.title,this.lang)">';
					vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
					vhtml += '<h3 class="large nobold default-text-font-bold">'+data[i].item+'</h3>';
					vhtml += '</div>';
				}

				document.getElementById('list-item').innerHTML = vhtml;
				
			}
		}
	}

	function jsAdd(id,item,uom) {
		var tr, vhtml, contr = document.getElementById('datalist');
		tr = document.createElement('tr');
		tr.className = "grey-white-state";

		vhtml = '<td align="left" class="pads7"><a href="javascript:void(0)" id="t'+id+'" class="black-font" title="Remove from list"><b class="fa-trash"></b></a></td>';
		vhtml += '<td align="left" class="pads7"><input type="hidden" name="item[]" value="'+id+'" required><h3 class="large nobold default-text-font-bold">'+item+'</h3></td>';
		vhtml += '<td align="left" class="cs-width-200 pads7"><span class="ln-display-box float-left nc-width-60 top-pull-5"><input type="text" name="qty[]" placeholder="Enter here" class="nopads no-back-black" required></span><span id="uom'+id+'" class="ln-display-box float-left nc-width-40 top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 dark-black-white-state rounded-button alignct"></span></td>';
		vhtml += '<td align="left" class="pads7 cs-width-180"><select name="stocktype[]" class="no-back-black" onchange="forsty(this.value,1)" required><option value="" selected>Choose?</option><option value="consumable">For Consumable</option></select></td>';
		/*<option value="serviceable">For Serviceable</option>*/
		
		contr.appendChild(tr);

		setTimeout(() => {
			tr.innerHTML = vhtml;
			var del = document.getElementById('t'+id);
			del.onclick = () => { contr.removeChild(tr); }
			objDisplay('sendbutton');

			jsUom(uom,'uom'+id);

		},500);

		//container.innerHTML = container.innerHTML+tr;
	}


	function forsty(val,state) {
		if(state == 1) {
			chgclass('irbox','fx-position-stick zind-2 motion fscr top-pull-50 txp8-white');
			writeObjheader('irmessage','You have selected '+val+' as your stock type for this item. Click <u>here</u> to return');
			parent.document.getElementById('workspace').scrollTop = 0;
		} else if(state == 0) {
			chgclass('irbox','noshow fx-position-rel zind-2 motion top-pull-50');
			writeObjheader('irmessage','');
		}
	}


	function popwkf() {
		//window.location.href = window.location.href+"&curi=pr-approval-request";
		var isworkflow = '<?php echo $irworkFlow; ?>';
		var vhtml;
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label class="block-element bottom-push-7">Select your approval workflow?</label>';
		vhtml += '<select name="workflowx" id="workflowx" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}


	function jbtrigger(e) {
		e.preventDefault();
		document.getElementById('workflow').value = document.getElementById('workflowx').value;
		setTimeout(() => { document.getElementById('for-ir').submit(); },1000);
	}

</script>