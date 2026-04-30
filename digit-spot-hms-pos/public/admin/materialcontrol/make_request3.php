<?php
	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	#get only stores with no outlet link
	$query_stores = "SELECT t1.store_name,t2.store FROM {$tbL123} t1, {$tbL14} t2 WHERE t1.id=t2.store AND t2.iscounter IN('No') AND t2.status IN('Active')";
	$for_stores = idget_data($query_stores);

	$packto = "";

	if(is_array($for_stores)) {
		foreach($for_stores as $key => $val) {
			//$query = "store={$val['id']} AND deletedata=0";
			//$outlet = mysqli_data_exist($tbL14,$query);

			//if($outlet['isdata'] == false) {
				$packto .= '<option value="'.$val['store'].'">'.$val['store_name'].'</option>';
			//}

			$query=""; $outlet="";
		}
	}

?>
<div class="pads30">
	<div class="block-element bottom-push-30">
	 	<span class="ln-display-box float-left">
			<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
			&nbsp; Note: you can only request for serviceable items here. Only stores with no outlet will be listed
	 	</span>
	 	<span class="ln-display-box float-right">
			&nbsp;
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</div>

	<div id="requestbox" class="pads20 sml-rounded-button bottom-push-30" align="left">
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
						<table cellpadding="0" cellspacing="0">
							<tr>
								<th align="center">&nbsp;</th>
								<th align="center">Item</th>
								<th align="center">Qty Requesting</th>
							</tr>
							<tbody id="datalist"></tbody>
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
	
	$keywords = "posid=0 AND stock_type='serviceable' AND deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval','Ready to Disburse') GROUP BY storeid,request_number";

	$sqldata = "SELECT * FROM {$tbL152} WHERE ".$keywords;
	$wgt_ir = idget_data($sqldata);

?>
<div class="pads30" align="left">
	<h3 class="large nobold alignct">&mdash;&mdash;&mdash; &nbsp; REQUEST LIST &nbsp; &mdash;&mdash;&mdash;</h3><br>
	<?php
		
		if(is_array($wgt_ir)) {
			
			$storage_name=""; $pack_storage_name=""; $stat_color="";
			
			foreach($wgt_ir as $key => $val) {
				
				$storage_name = idget_name($val['storeid'],'store_name',$tbL123);
				//$pack_storage_name .= '<option value="'.$val['storeid'].'">'.$storage_name.'</option>';
				
				$sqldata2 = "SELECT * FROM {$tbL152} WHERE request_number='{$val['request_number']}' AND storeid={$val['storeid']} AND deletedata=0 AND acceptance=0 AND posid=0 AND stock_type='serviceable' AND status IN('Reviewing','Under Approval','Ready to Disburse')";

				$wgt_ir2 = idget_data($sqldata2);

				$fsStat=0; $ssStat=0; $tsStat=0; $frStat=0;
				foreach($wgt_ir2 as $ky => $vl) {
					if($vl['status'] == 'Reviewing') { $fsStat += 1; $stat_color = "light-red-font"; }
					if($vl['status'] == 'Under Approval') { $ssStat += 1; $stat_color = "light-red-font"; }
					if($vl['status'] == 'Ready to Disburse') { $tsStat += 1; $stat_color = "royal-blue-font"; }
					if($vl['status'] == 'Disbursed') { $frStat += 1; $stat_color = "forest-green-font"; }
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
					<div<?php if($tsStat > 0) { ?> id="section-to-print"<?php } ?> class="box-border-thick-bottom bottom-pull-10 bottom-push-20">
						<form action="" method="post" autocomplete="off">
							<span class="float-right">
								<?php
									if(empty($val['storeid']) || $val['storeid'] == '') {
										?>?<?php
									} else {
										if($fsStat > 0) {
											?><h4 class="xlarge nobold light-red-font nunito-semibold">Reviewing</h4><?php
										} elseif($ssStat > 0) {
											?><h4 class="xlarge nobold light-red-font nunito-semibold">Under Approval</h4><?php
										} elseif($tsStat > 0) {
											?><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="window.print()">Print <b class="fas fa-print left-push-5"></b></a> <b class="nobold forest-green-font nunito-semibold left-push-10">Ready to Disburse</b><?php
										} elseif($frStat > 0) {
											?><h4 class="xlarge nobold forest-green-font nunito-semibold">Disbursed</h4><?php
										}
									}
								?>
							</span>
							
							<?php
								if($storage_name == 'Unknown') {
									?><h3 class="large nobold nunito-bold light-red-font">* No physical store</h3><?php
								} else {
									?><h3 class="large nobold nunito-bold">&bull; <b class="nobold">Item Request for <?php echo $storage_name; ?></b> (<?php echo $val['request_number']; ?>)</h3><?php
								}
							?>

							<br>
						
							<div class="x-scroll bottom-pull-10">
								<div class="cs-width-1200">
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
										?>
									</table>
								</div>
							</div>
						</form>
					</div>
				<?php
			}
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
		//vhtml += '<td align="left" class="pads7 cs-width-180"><select name="stocktype[]" class="no-back-black" required><option value="serviceable">For Serviceable</option></select></td>';
		
		contr.appendChild(tr);

		setTimeout(() => {
			tr.innerHTML = vhtml;
			var del = document.getElementById('t'+id);
			del.onclick = () => { contr.removeChild(tr); }
			objDisplay('sendbutton');
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

</script>