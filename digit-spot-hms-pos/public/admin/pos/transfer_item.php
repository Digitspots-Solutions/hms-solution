<?php
	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	#get all applcable workflows
	$itworkFlow = getjob_workflow(12);
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: you can transfer item by clicking <u>transfer</u> button. Follow on-screen instructions
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="objDisplay('transferbox')">Transfer</a>
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
	$pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');

	#-----------------------------------------------------------------------------------------------------------------

	createDatabasetable($var_tbl_304); //create a table for this post
	createDatabasetable($var_tbl_313); //create a table for this post
	
	$item_categories = select_dt_fetch('status','Active',$tbL115,'id','category');

	#-----------------------------------------------------------------------------------------------------------------

	$query_stores = array("status"=>"Active","deletedata"=>0);
	$for_stores = mysqli_data_fetch($tbL123,'id,store_name',$query_stores,'array');

	$virtual_stores = "";

	if(is_array($for_stores)) {
		foreach($for_stores as $key => $val) {
			$virtual_stores .= '<option value="'.$val['id'].'">'.$val['store_name'].'</option>';
		}
	} else {
		$virtual_stores .= '<option value="0">No stores</option>';
	}


	if(isset($_POST['submittransferbutton'])) {

		$pst_query = ""; $pst_field = "";

		$frompos = $_POST['frompos'];
		$topos = $_POST['topos'];

		$item = $_POST['item']; $qty = $_POST['qty'];
		$post = 0;

		for($i=0; $i < count($item); $i++) {
			if($qty[$i] >= 1) {
				$itemid = idget_data($tbL16,$item[$i],'itemcode');
				$qtybal = idget_data($tbL16,$item[$i],'balance');

				$pst_query = array("frompos"=>$frompos,"topos"=>$topos,"itemid"=>$itemid,"tr_status"=>"Pending");
				$pst_field = array("queryrow"=>$item[$i],"frompos"=>$frompos,"topos"=>$topos,"itemid"=>$itemid,"qty_available"=>$qtybal,"qty_required"=>$qty[$i],"requestby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

				$result = mysqli_data_insert($tbL166,$pst_field,$pst_query);
				if($result == 2) { $post += 1; }

				$itemid = "";
				$qtybal = "";
			}
		}

		if(isset($post) && $post >= 1) {
			$post_result = "Transfer request sent to source outlet";
		}
	}

	
	$sqlset = "COUNT(queryrow)";
	$queryset = "topos={$cur_pos_store_id} AND tr_status='Pending'";

	$noofpending = mysqli_arithmetic_data($tbL166,$sqlset,$queryset);


?>

<div class="cs-height-10"></div>

<h3 class="large nobold red-font alignlt"><?php echo $post_result; ?></h3>
<h3 class="large nobold black-font alignlt">Outlet to outlet pending transfer: <?php echo $noofpending; ?></h3>

<div id="transferbox" class="noshow pads20 sml-rounded-button bottom-push-30">
	<h3 class="large nobold default-text-font-bold alignct">Please note that only store-to-outlet requires approval. Use outlet-to-outlet for instant transfer</h3><br>
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
			<h4 class="large nobold dark-grey-font alignct">Search to list consumable items?</h4>
		</div>
 	</div>
 	<div class="ln-display-box float-right nc-width-70 pads20">
		<form id="trxform" action="materialcontrol/workspace.php" method="post" onsubmit="trx(event)" autocomplete="off">
			<input type="hidden" name="uri" value="apply-transfer-request">
			<div class="pads15 box-border-thick xsml-rounded-button bottom-push-10">
				<span class="ln-display-box float-left nc-width-35 right-pull-10">
					<h4 class="xlarge nobold black-font alignlt bottom-pull-3">Transfer Source</h4>
					<select name="frompos" id="frompos">
						<option value="<?php echo $cur_pos_storage; ?>"><?php echo $storage_name; ?></option>
						<?php echo $virtual_stores; ?>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-35 left-pull-10">
					<h4 class="xlarge nobold black-font alignlt bottom-pull-3">Transfer To</h4>
					<select name="topos" id="topos" onclick="getPoStores(this.id)">
						<option value="<?php echo $cur_pos_store_id; ?>" selected="selected"><?php echo $pos_name; ?></option>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-30 left-pull-10">
					<h4 class="xlarge nobold black-font alignlt bottom-pull-3">Transfer As</h4>
					<select name="transferas" id="transferas" onchange="changestore(this.value)">
						<option value="Store-to-outlet">Store-to-outlet</option>
						<option value="Outlet-to-outlet">Outlet-to-outlet</option>
						<!--<option value="BnD">Bad & Damage</option>-->
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div class="pads10 grey-1-theme">
				<div class="xsml-rounded-button noscroll">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th align="center">&nbsp;</th>
							<th align="center">Item</th>
							<th align="center">Qty Transferring</th>
						</tr>
						<tbody id="datalist"></tbody>
					</table>
				</div>
			</div>
			<div id="sendbutton" class="noshow top-push-20">
				<input type="hidden" name="workflow" id="workflow">
				<div id="continue" class="motion"><input type="button" name="submitbutton" value="Continue" class="submit pads10 black-white-state rounded-button nc-width-20" onclick="popwkf()"></div>
				<div id="submitbutton" class="noshow motion"><input type="submit" name="submittransferbutton" id="submittransferbutton" value="Submit Transfer" class="submit pads10 dark-black-white-state rounded-button nc-width-30"></div>
			</div>
		</form>
	</div>
	<div class="block-element new-line-space">
		<!-- clear line -->
	</div>
</div>

<div id="irbox" class="noshow fx-position-rel zind-2 motion top-pull-50" align="right">
	<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick obj-shadow" onclick="forsty('',0)">
		<h3 id="irheader" class="large nobold default-text-font-bold light-red-font">Confirm Transfer Type?</h3>
		<h3 id="irmessage" class="large nobold anchor"></h3>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<?php
	
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$additionalQuery = " AND (from_posid={$cur_pos_store_id} OR to_posid={$cur_pos_store_id}) GROUP BY transfer_number ORDER BY id DESC";
	$dataproperty = "transfer_number";
	$constrain = array("deletedata"=>0,"transfer_status"=>"Under Approval");
	$dataGroup = mysqli_data_fetch($tbL157,$dataproperty,$constrain,'array');

	if(is_array($dataGroup))
	{
		$ir_workflow = ""; $jpL = "";

		foreach($dataGroup as $theader => $tdata)
		{
			$additionalQuery = "";
			$fields = "job_level,user_one,approval_one,user_two,approval_two,user_three,approval_three,user_four,approval_four,user_five,approval_five";
			$query = array("subject"=>$tdata['transfer_number'],"approval_type"=>"TR");
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

			$tapx = $ir_workflow;

			$additionalQuery = " AND (from_posid={$cur_pos_store_id} OR to_posid={$cur_pos_store_id})";
			$dataproperty = "id,from_posid,to_posid,itemid,uom,qty_transfer,tagged_name,transfer_status,userid,datelogged,timelogged";
			$constrain = array("transfer_number"=>$tdata['transfer_number'],"deletedata"=>0,"transfer_status"=>"Under Approval");
			$dataCollect = mysqli_data_fetch($tbL157,$dataproperty,$constrain,'array');

			if(is_array($dataCollect))
			{
				$thproperty = array("enoth","to outlet","item","qty to transfer","type","logged by","status");
				$tcount = count($thproperty);

				$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
				
				$htmlresult .= '<div class="'.$tdata['transfer_number'].'">';
				$htmlresult .= '<h1 id="'.$tdata['transfer_number'].'-h" class="noshow motion">'._LONG_NAME.'</h1>';
				$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
				$htmlresult .= '<span class="float-right"><input type="button" value="Print" onclick="printhis(this)" lang="'.$tdata['transfer_number'].'"></span>';
				$htmlresult .= '<h3 class="large nobold">Transfer No: <b class="nobold default-text-font-bold right-push-5">'.$tdata['transfer_number'].'</b> <a href="javascript://" class="blue-font ft-xsml-size" title="'.$tapx.'">&hookrightarrow; See report status</a></h3>';
				$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
				$htmlresult .= '<table cellpadding="0" cellspacing="0" style="font-size: 13px !important">';
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
				
				$num=$pgstart; $g=""; $dataid=""; $get_item_name=""; $get_uom=""; $loggedby=0;
				$disabled=""; $isdisbursed=0; $outlet_name=""; $outlet_name2="";

				foreach($dataCollect as $theader => $tdata)
				{
					$num += 1;
					$g = $num / 2;

					$dataid = $tdata["id"];

					$additionalQuery = "";
					
					$outlet_name = idget_data($tbL14,$tdata['from_posid'],'posname');
					if($tdata['to_posid'] > 0) { $outlet_name2 = idget_data($tbL14,$tdata['to_posid'],'posname'); }
					else { $outlet_name2 = "Warehouse"; }

					$loggedby = idget_data($tbL7,$tdata['userid'],'staffname');
					$get_item_name = idget_data($tbL118,$tdata['itemid'],'item');
					$get_uom = arrayget_key($uoms,$tdata['uom']);
					
					$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

					if($tdata["transfer_status"] == 'Transfer Completed') { $isdisbursed += 1; $status_color="forest-green-font"; }
					elseif($tdata["transfer_status"] == 'Under Approval') { $status_color="light-red-font"; }
							
					$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
					$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$outlet_name2.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$get_item_name.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["qty_transfer"].' '.$get_uom.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["tagged_name"].'</td>';
					$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">'.$loggedby.'</td>';
					$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right default-text-font-bold '.$status_color.'">'.$tdata["transfer_status"].'</td>';
					$htmlresult .= '</tr>';

					#----------------------------------------------------------------------------------------------------------------------------------------------------
				}

				$htmlresult .= '</table>';
				$htmlresult .= '</div>';
				$htmlresult .= '</form>';
				$htmlresult .= '</div>';
				$htmlresult .= '<br><br>';
			}

			$tapx = "";
			$ir_workflow = "";

			$userone = "";
			$useone_roleid = "";
			$userone_rolename = "";
			$apr_stat_one = "";

			$usertwo = "";
			$usetwo_roleid = "";
			$usertwo_rolename = "";
			$apr_stat_two = "";

			$userthree = "";
			$usethree_roleid = "";
			$userthree_rolename = "";
			$apr_stat_three = "";

			$userfour = "";
			$usefour_roleid = "";
			$userfour_rolename = "";
			$apr_stat_four = "";

			$userfive = "";
			$usefive_roleid = "";
			$userfive_rolename = "";
			$apr_stat_five = "";
		}

	} else {
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

?>

<div id="fbox"></div>

<script>

	const tagnumbr = {"row":1};

	function wgtSublist() {
		var fs_selector = document.getElementById('category');
		var ss_selector = document.getElementById('subcategory');
		var thistore = document.getElementById('frompos').value;
		var tfr = document.getElementById('transferas').value;
		
		if(tfr == 'Store-to-outlet') {
			sqldatastring.sql = "SELECT t1.item,t2.itemid,t2.balance,t2.uom FROM stock_item_tbl t1, warehouse_stock_items_tbl t2 WHERE t1.id=t2.itemid AND t2.categoryid="+fs_selector.value+" AND t2.storageid="+thistore+" AND t2.deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

				for(i=0; i<data.length; i++) {
					vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].balance+'" title="'+data[i].item+'" align="'+js_uoms[Number(data[i].uom) - 1]+'" onclick="jsAdd('+data[i].itemid+',this.title,this.lang,this.align)">';
					vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
					vhtml += '<h3 class="large nobold default-text-font-bold nomargin">'+data[i].item+'</h3>';
					vhtml += '<h3 class="large nobold">Stock: '+data[i].balance+' '+js_uoms[Number(data[i].uom) - 1]+'</h3>';
					vhtml += '</div>';
				}

				document.getElementById('list-item').innerHTML = vhtml;
			}

		} else if(tfr == 'Outlet-to-outlet') {
			sqldatastring.sql = "SELECT * FROM pos_store_product_tbl WHERE item REGEXP '"+searchbyname.value+"' AND storagetype IN('consumable') AND status='Active' AND postoreid="+thistore+" AND categoryid="+fs_selector.value+" AND deletedata=0";
			 sqldataQuery(wgtpop,sqldatastring);
			
			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

				for(i=0; i<data.length; i++) {
					vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].balance+'" title="'+data[i].item+'" align="'+js_uoms[Number(data[i].uom) - 1]+'" onclick="jsAdd('+data[i].id+',this.title,this.lang,this.align)">';
					vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
					vhtml += '<h3 class="large nobold default-text-font-bold nomargin">'+data[i].item+'</h3>';
					vhtml += '<h3 class="large nobold">Stock: '+data[i].balance+' '+js_uoms[Number(data[i].uom) - 1]+'</h3>';
					vhtml += '</div>';
				}

				document.getElementById('list-item').innerHTML = vhtml;
			}
		}
	}

	function wgtSublist2() {

		var searchbyname = document.getElementById('byname');
		var thistore = document.getElementById('frompos').value;
		var tfr = document.getElementById('transferas').value;

		if(searchbyname.value !== null && searchbyname.value != '') {

			if(tfr == 'Store-to-outlet') {
				sqldatastring.sql = "SELECT t1.item,t2.itemid,t2.balance,t2.uom FROM stock_item_tbl t1, warehouse_stock_items_tbl t2 WHERE t1.item REGEXP '"+searchbyname.value+"' AND t1.id=t2.itemid AND t2.storageid="+thistore+" AND t2.deletedata=0";
				sqldataQuery(wgtpop,sqldatastring);

				function wgtpop(response) {
					var i, vhtml, data, ajaxresult = JSON.parse(response);
					data = ajaxresult.datastring;

					vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

					for(i=0; i<data.length; i++) {
						vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].balance+'" title="'+data[i].item+'" align="'+js_uoms[Number(data[i].uom) - 1]+'" onclick="jsAdd('+data[i].itemid+',this.title,this.lang,this.align)">';
						vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
						vhtml += '<h3 class="large nobold default-text-font-bold nomargin">'+data[i].item+'</h3>';
						vhtml += '<h3 class="large nobold">Stock: '+data[i].balance+' '+js_uoms[Number(data[i].uom) - 1]+'</h3>';
						vhtml += '</div>';
					}

					document.getElementById('list-item').innerHTML = vhtml;
				}

			} else if(tfr == 'Outlet-to-outlet') {
				sqldatastring.sql = "SELECT * FROM pos_store_product_tbl WHERE item REGEXP '"+searchbyname.value+"' AND storagetype IN('consumable') AND status='Active' AND postoreid="+thistore+" AND deletedata=0";
				sqldataQuery(wgtpop,sqldatastring);

				function wgtpop(response) {
					var i, vhtml, data, ajaxresult = JSON.parse(response);
					data = ajaxresult.datastring;

					vhtml = '<h4 class="xlarge nobold black-font">Select the item to add to list</h4><br>';

					for(i=0; i<data.length; i++) {
						vhtml += '<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10 anchor" lang="'+data[i].balance+'" title="'+data[i].item+'" align="'+js_uoms[Number(data[i].uom) - 1]+'" onclick="jsAdd('+data[i].id+',this.title,this.lang,this.align)">';
						vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
						vhtml += '<h3 class="large nobold default-text-font-bold nomargin">'+data[i].item+'</h3>';
						vhtml += '<h3 class="large nobold">Stock: '+data[i].balance+' '+js_uoms[Number(data[i].uom) - 1]+'</h3>';
						vhtml += '</div>';
					}

					document.getElementById('list-item').innerHTML = vhtml;
				}
			}
			
		}
	}

	function jsAdd(id,item,stock,uom) {
		var tr, vhtml, contr = document.getElementById('datalist');
		tr = document.createElement('tr');
		tr.className = "grey-white-state";

		var nrand = (Math.random() * 1000000000) + 1;

		vhtml = '<td align="left" class="pads7"><a href="javascript:void(0)" id="t'+nrand+'" class="black-font" title="Remove from list"><b class="fa-trash"></b></a></td>';
		vhtml += '<td align="left" class="pads7"><input type="hidden" name="item[]" value="'+id+'" required><h3 class="large nobold default-text-font-bold">'+item+'</h3></td>';
		vhtml += '<td align="left" class="cs-width-200 pads7"><span class="ln-display-box float-left nc-width-60 top-pull-5"><input type="number" step=".01" name="qty[]" id="qty'+tagnumbr.row+'" placeholder="Enter here" class="nopads no-back-black" onkeyup="fQy('+tagnumbr.row+')" required><input type="hidden" name="stockbal[]" id="stockbal'+tagnumbr.row+'" value="'+stock+'"></span><span class="ln-display-box float-left nc-width-40 top-pull-5 blue-font alignct">'+uom+'</span></td>';
	
		contr.appendChild(tr);

		setTimeout(() => {
			tr.innerHTML = vhtml;
			tagnumbr.row = eval(tagnumbr.row) + 1;
			var del = document.getElementById('t'+nrand);
			del.onclick = () => { contr.removeChild(tr); }
			objDisplay('sendbutton');
		},500);
	}


	function forsty(val,state) {
		var msg, topos = document.getElementById('topos');
		if(state == 1) {
			if(val == 'BnD') {
				topos.innerHTML = '<option value="0" selected>Warehouse</option>';
				msg = "Note that all bad and damage items go back to warehouse after approval is done. Click <u>here</u> to return";
			} else {
				topos.innerHTML = '<option value="" selected>Choose?</option>';
				topos.click();
				msg = "Note that inter-store is within pos stores. Ensure you choose the right store. Click <u>here</u> to return";
			}
			chgclass('irbox','fx-position-stick zind-2 motion fscr top-pull-50 txp8-white');
			writeObjheader('irmessage',msg);
			parent.document.getElementById('workspace').scrollTop = 0;
		} else if(state == 0) {
			chgclass('irbox','noshow fx-position-rel zind-2 motion top-pull-50');
			writeObjheader('irmessage','');
		}
	}


	function changestore(opt) {
		if(opt == 'Outlet-to-outlet') {
			var thistore = "<?php echo $cur_pos_store_id; ?>";
			writeObjheader('frompos','<option value="" selected>fetching..</option>');

			sqldatastring.sql = "SELECT * FROM pos_store_tbl WHERE id NOT IN("+thistore+") AND postype IN('Service') AND iscounter IN('Yes') AND status='Active' AND deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<option value="" selected>Choose?</option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].posname+'</option>';
				}

				writeObjheader('frompos',vhtml);
			}

		} else if(opt == 'Store-to-outlet') {

			writeObjheader('frompos','<option value="" selected>fetching..</option>');

			sqldatastring.sql = "SELECT * FROM stores_tbl WHERE deletedata=0 AND status='Active'";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<option value="" selected>Choose?</option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].store_name+'</option>';
				}

				writeObjheader('frompos',vhtml);
			}
		}
	}


	function getPoStores(id) {

		if(document.getElementById(id).value == '' || document.getElementById(id).value == null) {
			
			var thistore = "<?php echo $cur_pos_store_id; ?>";
			writeObjheader(id,'<option value="" selected>fetching..</option>');

			//sqldatastring.sql = "SELECT t1.store_name,t2.id FROM stores_tbl t1, pos_store_tbl t2 WHERE t2.id NOT IN("+thistore+") AND t2.deletedata=0 AND t1.id = t2.store";
			sqldatastring.sql = "SELECT t1.store_name,t2.id,t2.posname FROM stores_tbl t1, pos_store_tbl t2 WHERE t2.iscounter IN('Yes') AND t2.status IN('Active') AND t2.deletedata=0 AND t1.id = t2.store";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<option value="" selected>Choose?</option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].posname+'</option>';
				}

				writeObjheader(id,vhtml);
			}
		}
	}


	function fQy(row) {
		var msg, qtyavail, qtyinputted, transfertype;
		qtyavail = document.getElementById('stockbal'+row);
		qtyinputted = document.getElementById('qty'+row);
		transfertype = document.getElementById('transferas');

		if(transfertype.value == 'Outlet-to-outlet' || transfertype.value == 'Store-to-outlet') {
			if(eval(qtyinputted.value) > eval(qtyavail.value)) {
				qtyinputted.value = "";
				msg = "You cannot transfer value more than your stock. Click <u>here</u> to return";
				chgclass('irbox','fx-position-stick zind-2 motion fscr top-pull-50 txp8-white');
				writeObjheader('irmessage',msg);
				parent.document.getElementById('workspace').scrollTop = 0;
			}
		}
	}

	function trx(e) {
		e.preventDefault();
		var frm, vhtml, f = document.getElementById('trxform');
		f.setAttribute('target','tframe');

		var button = document.getElementById('submittransferbutton');
		button.value = "Sending request, wait..";
		button.setAttribute('type','button');

		vhtml = '';
		vhtml += '<div class="fx-position-fixed fscr zind-2 motion top-pull-50 txp8-white" align="center">';
		vhtml += '<h3 class="large nobold default-text-font-bold">Sending request, please wait..</h3>';
		vhtml += '<div id="xfr" class="noshow white-theme pads20 top-push-50">';
		vhtml += '</div>';
		vhtml += '</div>';

		frm = document.createElement('iframe');
		frm.width = '100%';
		frm.height = '100%';
		frm.name = 'tframe';
		frm.id = 'tframe';

		writeObjheader('fbox',vhtml);
		document.getElementById('xfr').appendChild(frm);
		setTimeout(() => { f.submit(); },1000);
		setTimeout(() => { writeObjheader('fbox',''); window.location.reload(); },3000);
	}


	function popwkf() {
		//window.location.href = window.location.href+"&curi=pr-approval-request";
		var transfertype = document.getElementById('transferas').value;
		var isworkflow = '<?php echo $itworkFlow; ?>';
		
		if(transfertype == 'Outlet-to-outlet') {
			
			var submitform = document.getElementById('trxform');
			submitform.setAttribute('action','');
			submitform.setAttribute('onsubmit','');

			chgclass('tktBox','xfadein noshow motion');
			chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt noscroll');
			writeObjheader('rBox','');

			chgclass('continue','noshow motion');
			chgclass('submitbutton','block-element motion');

		} else if(transfertype == 'Store-to-outlet') {
			
			chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
			chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt noscroll');

			vhtml = '';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
			vhtml += '<div class="pads10 alignlt">';
			vhtml += '<label class="block-element bottom-push-7">Select your approval workflow?</label>';
			vhtml += '<select name="workflowx" id="workflowx" class="nopads no-back-black">'+isworkflow+'</select>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
			vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
			vhtml += '</div>';
			vhtml += '</form>';
			
			writeObjheader('rBox',vhtml);
			parent.document.getElementById('workspace').scrollTop = 0;
		}
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt noscroll');
		writeObjheader('rBox','');
	}


	function jbtrigger(e) {
		e.preventDefault();
		document.getElementById('workflow').value = document.getElementById('workflowx').value;
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt noscroll');
		writeObjheader('rBox','');

		chgclass('continue','noshow motion');
		chgclass('submitbutton','block-element motion');	
	}


	function printhis(obj) {
		var name = obj.lang;
		var printsheet = document.getElementsByClassName(name)[0];
		printsheet.setAttribute('id','section-to-print');
		chgclass(name+'-h','motion');
		
		setTimeout(() => {
			window.print();
		},500);

		setTimeout(() => {
			printsheet.removeAttribute('id');
			chgclass(name+'-h','noshow motion');
		},2000)
	}

</script>