<?php
	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	#get only stores with no outlet link
	$query_stores = "SELECT * FROM {$tbL12} WHERE status IN('Active') AND deletedata=0";
	$for_stores = idget_data($query_stores);

	$packto = "";

	if(is_array($for_stores)) {
		foreach($for_stores as $key => $val) {
			//$query = "store={$val['id']} AND deletedata=0";
			//$outlet = mysqli_data_exist($tbL14,$query);

			//if($outlet['isdata'] == false) {
				$packto .= '<option value="'.$val['id'].'">'.$val['department'].'</option>';
			//}

			$query=""; $outlet="";
		}
	}

	if(isset($_GET['rrq']) && !empty($_GET['rrq'])) {
		$pst_query = "request_number='{$_GET['rrq']}'";
		trash_record($tbL152,$pst_query);
		unset($_GET['rrq']);
	}

	$remark_tbl = "CREATE TABLE IF NOT EXISTS ir_remark_tbl(id bigint(50) auto_increment, request_number varchar(50), remark text, datelogged date, timelogged time, primary key(id))";

	createDatabasetable($remark_tbl);

?>
<div class="pads30">
	<div class="block-element bottom-push-30">
	 	<span class="ln-display-box float-left">
			<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
			&nbsp; Note: you can only request for serviceable items here
	 	</span>
	 	<span class="ln-display-box float-right">
			<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="chgclass('requestbox','pads20 sml-rounded-button bottom-push-30 motion')">Make Request</a>
		</span>
		<span class="block-element new-line-space">
			<!-- break -->
		</span>
	</div>

	<div id="requestbox" class="noshow motion" align="left">
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
	 	<div class="ln-display-box float-right nc-width-65 pads20">
	 		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	 			<input type="hidden" name="uri" value="mc-make-item-request">
		 		<div class="box-border-thick xsml-rounded-button top-pull-7 right-pull-20 bottom-pull-10 left-pull-20 bottom-push-15">
		 			<h4 class="xlarge nobold black-font alignlt nunito-bold">Select store/department receiving the request</h4>
		 			<select name="stores" id="stores" class="nopads no-back-black">
		 				<option value="" selected>Choose?</option>
		 				<?php echo $packto; ?>
		 			</select>
		 		</div>
		 		<div class="grey-1-theme pads20">
					<h4 class="xlarge nobold black-font alignct">All selected items display here</h4><br>
					<div class="xsml-rounded-button noscroll">
						<table cellpadding="0" cellspacing="0" style="font-size: 14px !important;">
							<tr>
								<td align="center">&nbsp;</td>
								<td class="default-text-font-bold" align="center">Item</td>
								<td class="default-text-font-bold" align="center">Qty Requesting</td>
							</tr>
							<tbody id="datalist"></tbody>
							<tr>
								<td colspan="4">
									<div id="remark" class="noshow motion">
										<textarea name="remark" class="notextborder" placeholder="Enter remark for your request?"></textarea>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div id="sendbutton" class="noshow top-push-20">
						<input type="submit" name="submitbutton" value="Submit Request" class="pads10 blue-white-state rounded-button nc-width-30 anchor">
					</div>
				</div>
			</form>
		</div>
		<div class="block-element new-line-space">
			<!-- clear line -->
		</div>
	</div>
</div>


<?php

	//get all available stores for request
	$query_avs = "SELECT storeid FROM {$tbL152} WHERE status IN('Reviewing','Under Approval','Ready to Disburse','Disbursed') GROUP BY storeid";
	$xtbl = $tbL12; $xcol = "id"; $xopttext = "department";
	$pack_storage_name = html_db_select($query_avs,'storeid','storeid');

	if(isset($_GET['storeid']) && $_GET['storeid'] > 0) {
		if(isset($_GET['stat']) && !empty($_GET['stat'])) { $ustat = "'{$_GET['stat']}'"; }
		else { $ustat = "'Reviewing','Under Approval','Ready to Disburse','Disbursed'"; }
		$accept = "0,1";
		$keywords = "storeid={$_GET['storeid']} AND deletedata=0 AND acceptance IN({$accept}) AND status IN({$ustat}) GROUP BY storeid,request_number ORDER BY id DESC";
	} else {
		$ustat = "'Reviewing'"; $accept = 0;
		$keywords = "posid=0 AND stock_type='serviceable' AND deletedata=0 AND acceptance IN({$accept}) AND status IN('Reviewing') GROUP BY storeid,request_number ORDER BY id DESC";
	}

	$sqldata = "SELECT * FROM {$tbL152} WHERE ".$keywords;
	$wgt_ir = idget_data($sqldata);

?>
<div class="pads30" align="left">
	<h3 class="large nobold alignct">&mdash;&mdash;&mdash; &nbsp; DEPARTMENTAL REQUEST LIST &nbsp; &mdash;&mdash;&mdash;</h3>
	<h4 class="large nobold alignct light-red-font">Choose department then select the IR status to see the list</h4><br>
	<div class="cs-width-600 margin-auto-ct bottom-push-50">
		<div class="xform obj-light-shadow">
			<div class="sided-box right-pull-5 left-pull-5">
				<ul>
					<li class="nc-width-20 nunito-semibold top-pull-2">Search by:</li>
					<li class="nc-width-50 top-pull-2"><select id="searchlist" class="nopads no-back-black"><option value="" selected>Available Departments</option><?php echo $pack_storage_name; ?></select></li>
					<li class="nc-width-30 top-pull-2 left-pull-10"><select id="searchstatus" class="nopads no-back-black" onchange="changeList()"><option value="" selected>Status</option><option value="">All</option><option value="Disbursed">Disbursed IR</option><option value="Ready to Disburse">Ready to Disburse</option><option value="Reviewing">Reviewing</option><option value="Under Approval">Under Approval</option></select></li>
					<li></li>
				</ul>
			</div>
		</div>
	</div>

	<?php
		
		if(is_array($wgt_ir)) {
			
			$storage_name=""; $pack_storage_name=""; $stat_color="";
			
			foreach($wgt_ir as $key => $val) {
				
				$storage_name = idget_name($val['storeid'],'department',$tbL12);
				//$pack_storage_name .= '<option value="'.$val['storeid'].'">'.$storage_name.'</option>';
				
				$sqldata2 = "SELECT * FROM {$tbL152} WHERE request_number='{$val['request_number']}' AND storeid={$val['storeid']} AND deletedata=0 AND acceptance IN({$accept}) AND posid=0 AND stock_type='serviceable' AND status IN({$ustat})";

				$wgt_ir2 = idget_data($sqldata2);

				$fsStat=0; $ssStat=0; $tsStat=0; $frStat=0; $ir_stat=0;

				foreach($wgt_ir2 as $ky => $vl) {
					if($vl['status'] == 'Reviewing') { $fsStat += 1; $stat_color = "light-red-font"; $ir_stat=0; }
					if($vl['status'] == 'Under Approval') { $ssStat += 1; $stat_color = "light-red-font"; $ir_stat=1; }
					if($vl['status'] == 'Ready to Disburse') { $tsStat += 1; $stat_color = "royal-blue-font"; $ir_stat=1; }
					if($vl['status'] == 'Disbursed') { $frStat += 1; $stat_color = "forest-green-font";  $ir_stat=1;}
				}


				//approval level users
				$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$val['request_number']}' AND approval_type='ITEM DISBURST'";
				$jpL = idget_data($isjp);

				if(is_array($jpL) && count($jpL)) {
					?>
						<div class="sided-box grey-theme xsml-rounded-button pads20 bottom-push-15 alignlt">
							<ul>
								<li class="right-pull-30">
									<?php
										if($jpL[0]['user_one']) {
											$userone = idget_name($jpL[0]['user_one'],'staffname',$tbL7);
											$useroleid = idget_name($jpL[0]['user_one'],'role',$tbL7);
											$userole = idget_name($useroleid,'role',$tbL4);
											$af1_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_one']);
											$af1_status_color = wgtcolor($af1_approval_signed_status);
											?>
												<h3 class="large nobold nunito-semibold nomargin"><?php echo $userone; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
												<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
											<?php
										}
									?>
								</li>
								<li class="right-pull-30">
									<?php
										if($jpL[0]['user_two']) {
											$usertwo = idget_name($jpL[0]['user_two'],'staffname',$tbL7);
											$useroleid = idget_name($jpL[0]['user_two'],'role',$tbL7);
											$userole = idget_name($useroleid,'role',$tbL4);
											$af2_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_two']);
											$af2_status_color = wgtcolor($af2_approval_signed_status);
											?>
												<h3 class="large nobold nunito-semibold nomargin"><?php echo $usertwo; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
												<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
											<?php
										}
									?>
								</li>
								<li class="right-pull-30">
									<?php
										if($jpL[0]['user_three']) {
											$userthree = idget_name($jpL[0]['user_three'],'staffname',$tbL7);
											$useroleid = idget_name($jpL[0]['user_three'],'role',$tbL7);
											$userole = idget_name($useroleid,'role',$tbL4);
											$af3_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_three']);
											$af3_status_color = wgtcolor($af3_approval_signed_status);
											?>
												<h3 class="large nobold nunito-semibold nomargin"><?php echo $userthree; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
												<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
											<?php
										}
									?>
								</li>
								<li class="right-pull-30">
									<?php
										if($jpL[0]['user_four']) {
											$userfour = idget_name($jpL[0]['user_four'],'staffname',$tbL7);
											$useroleid = idget_name($jpL[0]['user_four'],'role',$tbL7);
											$userole = idget_name($useroleid,'role',$tbL4);
											$af4_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_four']);
											$af4_status_color = wgtcolor($af4_approval_signed_status);
											?>
												<h3 class="large nobold nunito-semibold nomargin"><?php echo $userfour; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
												<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
											<?php
										}
									?>
								</li>
								<li class="right-pull-30">
									<?php
										if($jpL[0]['user_five']) {
											$userfive = idget_name($jpL[0]['user_five'],'staffname',$tbL7);
											$useroleid = idget_name($jpL[0]['user_five'],'role',$tbL7);
											$userole = idget_name($useroleid,'role',$tbL4);
											$af5_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_five']);
											$af5_status_color = wgtcolor($af5_approval_signed_status);
											?>
												<h3 class="large nobold nunito-semibold nomargin"><?php echo $userfive; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
												<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
											<?php
										}
									?>
								</li>
								<li></li>
							</ul>
						</div>
					<?php
				}

				?>
					<div<?php if($tsStat > 0 || $frStat > 0) { ?> id="section-to-print"<?php } ?> class="box-border-thick-bottom bottom-pull-10 bottom-push-20">
						<div id="<?php echo $val['request_number']; ?>-h" class="noshow motion"><h1 class="xxlarge nobold"><?php echo _LONG_NAME; ?></h1></div>
						<form action="" method="post" autocomplete="off">
							<span class="float-right">
								<?php
									if(empty($val['storeid']) || $val['storeid'] == '') {
										?>?<?php
									} else {
										if($fsStat > 0) {
											?><h4 class="xlarge nobold light-red-font nunito-semibold">Reviewing &nbsp; <input type="button" value="Remove Request" class="anchor" onclick="remove_ir('<?php echo $val['request_number']; ?>')"></h4><?php
										} elseif($ssStat > 0) {
											?><h4 class="xlarge nobold light-red-font nunito-semibold">Under Approval</h4><?php
										} elseif($tsStat > 0) {
											?><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="printhis(this)" lang="<?php echo $val['request_number']; ?>">Print <b class="fas fa-print left-push-5"></b></a> <b class="nobold forest-green-font nunito-semibold left-push-10">Ready to Disburse</b><?php
										} elseif($frStat > 0) {
											?><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="printhis(this)" lang="<?php echo $val['request_number']; ?>">Print <b class="fas fa-print left-push-5"></b></a> <b class="nobold forest-green-font nunito-semibold left-push-10">Disbursed</b><?php
										}
									}
								?>
							</span>
							
							<?php
								if($storage_name == 'Unknown') {
									?><h3 class="large nobold nunito-bold light-red-font">* No department identified</h3><?php
								} else {
									?><h3 class="large nobold nunito-bold">&bull; <b class="nobold">Item Request for <?php echo $storage_name; ?></b> (<?php echo $val['request_number']; ?> - <?php echo date('d/m/Y',strtotime($val['datelogged'])); ?>)</h3><?php
								}
							?>

							<br>
						
							<div class="x-scroll bottom-pull-10">
								<div class="nc-width-100">
									<table cellspacing="0" cellpadding="0">
										<tr>
											<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Category</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Sub Category</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Required</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Received</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Stock Type</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Request By</td>
										</tr>
										<?php
											$numb = 0; $disburse_state = 0;
											
											foreach($wgt_ir2 as $key2 => $val2) {
												
												$numb += 1;

												idget_global($val2['itemid'],$var_item);
												idget_global($val2['userid'],$var_user);

												$categoryid = idget_name($val2['itemid'],'categoryid',$mtbL5);
												$subcategoryid = idget_name($val2['itemid'],'subcategoryid',$mtbL5);
												$buying_unit = idget_name($val2['itemid'],'buying_unit',$mtbL5);

												$category_name = idget_name($categoryid,'category',$mtbL2);
												$subcategory_name = idget_name($subcategoryid,'subcategory',$mtbL3);
												$stock_balance = idget_fname($val2['itemid'],'itemid','balance',$mtbL19);
												$stock_balance = str_replace('Unknown','0',$stock_balance);

												$get_su = arrayget_key($uoms,$val2['uom']);
												$get_bu = arrayget_key($uoms,$buying_unit);

												//if($val2['status'] == 'Ready to Disburse') { $disburse_state += 1; }
												
												?>
													<tr>
														<td class="right-pull-10 left-pull-10"><?php echo $numb; ?>.</td>
														<td class="right-pull-10 left-pull-10"><?php echo $category_name; ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $subcategory_name; ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?><input type="hidden" name="id[]" value="<?php echo $val2['id']; ?>" required></td>
														<td class="right-pull-10 left-pull-10 alignlt"><?php echo $val2['qty_required'].' '.$get_su; ?></td>
														<td class="right-pull-10 left-pull-10 cs-width-150 pads7"><input type="hidden" name="qtyrequired[]" value="<?php echo $val2['qty_required']; ?>" required>
															<?php echo $val2['qty_received'].' '.$get_su; ?>
														</td>
														<td class="right-pull-10 left-pull-10"><?php echo ucfirst($val2['stock_type']); ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
													</tr>
												<?php

												$get_su=""; $get_bu=""; $categoryid=""; $subcategoryid=""; $buying_unit="";
												$category_name=""; $subcategory_name=""; $stock_balance="";
											}

											$get_remark = idget_fname($val['request_number'],'request_number','remark','ir_remark_tbl');
											
											if(!empty($get_remark) && $get_remark != 'Unknown' && $get_remark != '') {
												?>
													<tr>
														<td colspan="11" align="left" class="top-pull-5 left-pull-15 right-pull-15 grey-theme">
															<h4 class="xlarge nobold"><b class="nobold light-red-font default-text-font-bold">Remark:</b> <?php echo $get_remark; ?></h4>
														</td>
													</tr>
												<?php
											}
										?>
									</table>
								</div>
							</div>
						</form>
					</div>
				<?php
			}
		} else {
			?>
				<div class="cs-height-50"></div>
				<div class="block-element" align="center">
					<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
						<span class="block-element nc-height-35"></span>
						<b class="mbri-pages ft-Lsize nobold"></b>
					</div>
					<h3 class="xlarge nobold">No records found</h3>
				</div>
			<?php
		}
	?>
</div>
	



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

			sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE item REGEXP '"+searchbyname.value+"' AND status='Active' AND deletedata=0";
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

		var nrand = (Math.random() * 1000000000) + 1;

		vhtml = '<td align="left" class="pads7"><a href="javascript:void(0)" id="t'+nrand+'" class="black-font" title="Remove from list"><b class="fa-trash"></b></a></td>';
		vhtml += '<td align="left" class="pads7"><input type="hidden" name="item[]" value="'+id+'" required><h3 class="large nobold default-text-font-bold">'+item+'</h3></td>';
		vhtml += '<td align="left" class="cs-width-200 pads7"><span id="uom'+id+'" class="ln-display-box float-right nc-width-40"></span><span class="ln-display-box float-right nc-width-30"><input type="text" name="qty[]" placeholder="0.0" class="nopads no-back-black default-text-font-bold" required></span></td>';
		//vhtml += '<td align="left" class="pads7 cs-width-180"><select name="stocktype[]" class="no-back-black" required><option value="serviceable">For Serviceable</option></select></td>';
		
		contr.appendChild(tr);

		setTimeout(() => {
			tr.innerHTML = vhtml;
			var del = document.getElementById('t'+nrand);
			del.onclick = () => { contr.removeChild(tr); }
			objDisplay('sendbutton');
			chgclass('remark','motion');
			arrygets.splice(0,arrygets.length);
			var fsu = {"arryname":"uoms","keys":uom}; wgtarrykey(fsu);
		},500);

		setTimeout(() => {
			writeObjheader('uom'+id,arrygets[0]);
		},1000);

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


	function remove_ir(request) {
		var con = confirm('Are you sure want to remove this request?');
		if(con == true) { window.location.href = window.location.href+'&rrq='+request; }
	}


	function changeList() {
		var id = document.getElementById('searchlist').value;
		var stat = document.getElementById('searchstatus').value;
		window.location.href = window.location.href+"&storeid="+id+"&stat="+stat;
	}


	function printhis(obj) {
		var name = obj.lang;
		//var printsheet = document.getElementsByClassName(name)[0];
		obj.setAttribute('class','noshow motion');
		chgclass(name+'-h','dark-black-font block-dkt-show motion');
		
		setTimeout(() => {
			window.print();
		},2000);

		setTimeout(() => {
			obj.setAttribute('class','blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10');
			chgclass(name+'-h','noshow motion');
		},3000)
	}

</script>