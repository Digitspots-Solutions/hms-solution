<?php
	
	#get all applcable workflows
	$irworkFlow = getjob_workflow(4);

	$isremove = 0; $ismsg = null;

	#remove item from request list
	if(isset($_GET['rqsremove']) && !empty($_GET['rqsremove'])) {
		$set_query = "id={$_GET['rqsremove']}";
		trash_record($tbL152,$set_query);
		$isremove = 1; $ismsg = 0;
	}
	
	//get all available stores for request
	$query_avs = "SELECT storeid FROM {$tbL152} WHERE status IN('Reviewing','Under Approval','Ready to Disburse') GROUP BY storeid";
	$xtbl = $tbL12; $xcol = "id"; $xopttext = "department";
	$pack_storage_name = html_db_select($query_avs,'storeid','storeid');

	if(isset($_GET['storeid']) && $_GET['storeid'] > 0) {
		if(isset($_GET['stat']) && !empty($_GET['stat'])) { $ustat = "'{$_GET['stat']}'"; }
		else { $ustat = "'Reviewing','Under Approval','Ready to Disburse'"; }
		$keywords = "storeid={$_GET['storeid']} AND deletedata=0 AND acceptance=0 AND status IN({$ustat}) GROUP BY storeid,request_number ORDER BY id DESC";
	} else {
		$keywords = "deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval','Ready to Disburse') GROUP BY storeid,request_number ORDER BY id DESC";
	}

	$sqldata = "SELECT * FROM {$tbL152} WHERE ".$keywords;
	$wgt_ir = idget_data($sqldata);

	#-------------------------------------------------------------------------

	#get only stores with no outlet link
	$query_stores = "SELECT t1.store_name,t2.storageid FROM {$tbL123} t1, {$mtbL19} t2 WHERE t1.id=t2.storageid";
	$for_stores = idget_data($query_stores);

	$packto = "";
	$fr_stores = array();

	if(is_array($for_stores)) {
		foreach($for_stores as $key => $val) {
			if(!in_array($val['store_name'], $fr_stores)) {
				$packto .= '<option value="'.$val['storageid'].'">'.$val['store_name'].'</option>';
				array_push($fr_stores, $val['store_name']);
			}

			$query=""; $outlet="";
		}
	}

?>
<div class="pads30" align="left">

	<div class="float-left top-pull-3 right-push-30"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>

	<h4 class="xlarge nobold black-font"><b class="fas fa-question-circle right-push-5"></b>Here you can disburse items to department. Clicking the <u>apply</u> button will initial approval process</h4><br>

	<div class="cs-width-600 bottom-push-20">
		<div class="xform obj-light-shadow">
			<div class="sided-box right-pull-5 left-pull-5">
				<ul>
					<li class="nc-width-20 nunito-semibold top-pull-2">Search by:</li>
					<li class="nc-width-50 top-pull-2"><select id="searchlist" class="nopads no-back-black"><option value="" selected>Available Departments</option><?php echo $pack_storage_name; ?></select></li>
					<li class="nc-width-30 top-pull-2 left-pull-10"><select id="searchstatus" class="nopads no-back-black" onchange="changeList()"><option value="" selected>Status</option><option value="">All</option><option value="Ready to Disburse">Ready to Disburse</option><option value="Reviewing">Reviewing</option><option value="Under Approval">Under Approval</option></select></li>
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
				
				$sqldata2 = "SELECT * FROM {$tbL152} WHERE request_number='{$val['request_number']}' AND storeid={$val['storeid']} AND deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval','Ready to Disburse')";
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
					<div class="box-border-thick-bottom bottom-pull-10 bottom-push-20 print-<?php echo $val['request_number']; ?>">
						<form id="fr-<?php echo $val['request_number']; ?>" action="" method="post" autocomplete="off">
							<input type="hidden" name="workflow" id="workflow-<?php echo $val['request_number']; ?>">
							<span class="float-right">
								<?php
									if(empty($val['storeid']) || $val['storeid'] == '') {
										?>?<?php
									} else {
										if($fsStat > 0) {
											?><input type="button" id="submitbutton" name="submitbutton" value="Apply" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance" onclick="popwkf('fr-<?php echo $val['request_number']; ?>','workflow-<?php echo $val['request_number']; ?>')"><?php
										} elseif($ssStat > 0) {
											?><h4 class="xlarge nobold light-red-font nunito-semibold">Under Approval</h4><?php
										} elseif($tsStat > 0) {
											?><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="irPrint('<?php echo $val['request_number']; ?>')">Print <b class="fas fa-print left-push-5"></b></a> <b class="nobold forest-green-font nunito-semibold left-push-10">Ready to Disburse</b><?php
										} elseif($frStat > 0) {
											?><h4 class="xlarge nobold forest-green-font nunito-semibold">Disbursed</h4><?php
										}
									}
								?>
							</span>
							
							<?php
								if($storage_name == 'Unknown') {
									?><h3 class="large nobold nunito-bold light-red-font">* No Departments Identified</h3><?php
								} else {
									?><h3 class="large nobold nunito-bold">&bull; <b class="nobold">Item Request for <?php echo $storage_name; ?></b> (<?php echo $val['request_number']; ?>)</h3><?php
								}
							?>

							<br>
							
							<input type="hidden" name="uri" id="<?php echo $val['request_number']; ?>" value="apply-item-request">
							<input type="hidden" name="storage" value="<?php echo $val['storeid']; ?>">
							<input type="hidden" name="pos" value="<?php echo $val['posid']; ?>">
							<input type="hidden" name="requestnumber" value="<?php echo $val['request_number']; ?>">

							<div class="x-scroll bottom-pull-10">
								<div class="nc-width-100">
									<table cellspacing="0" cellpadding="0">
										<tr>
											<td class="default-text-font-bold right-pull-10 left-pull-10">
												<?php if($tsStat > 0): ?>
													<input type="checkbox" id="chk-<?php echo $val['request_number']; ?>" value="off" onclick="chkAll(this)" lang="<?php echo $val['request_number']; ?>" class="nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button motion" title="Check All">
												<?php else: echo '&nbsp;'; endif; ?>
											</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Category</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Sub Category</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Required</td>
											<!--<td class="default-text-font-bold right-pull-10 left-pull-10">Qty in Stock</td>-->
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty to Transfer</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Stock Type</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Request By</td>
											<?php
												if($fsStat > 0) {
													?>
														<td class="">&nbsp;</td>
													<?php
												}
											?>
										</tr>

										<?php
											$numb = 0; $disburse_state = 0; $balSql = ""; $wget_bal = "";
											
											foreach($wgt_ir2 as $key2 => $val2) {
												
												$numb += 1;

												idget_global($val2['itemid'],$var_item);
												idget_global($val2['userid'],$var_user);

												$categoryid = idget_name($val2['itemid'],'categoryid',$mtbL5);
												$subcategoryid = idget_name($val2['itemid'],'subcategoryid',$mtbL5);
												$buying_unit = idget_name($val2['itemid'],'buying_unit',$mtbL5);

												$category_name = idget_name($categoryid,'category',$mtbL2);
												$subcategory_name = idget_name($subcategoryid,'subcategory',$mtbL3);
												
												/*$balSql = "SELECT * FROM {$mtbL19} WHERE itemid={$val2['itemid']} AND storageid={$val2['storeid']}"; $wget_bal = idget_data($balSql);
												if(!empty($wget_bal[0]['balance']) && $wget_bal[0]['balance'] > 0) {
													$stock_balance = $wget_bal[0]['balance'];
												} else {
													$stock_balance = 0;
												}*/

												$stock_balance = $val2['qty_received'];

												//$stock_balance = idget_fname($val2['itemid'],'itemid','balance',$mtbL19);
												//$stock_balance = str_replace('Unknown','0',$stock_balance);

												$get_su = arrayget_key($uoms,$val2['uom']);
												$get_bu = arrayget_key($uoms,$buying_unit);

												//if($val2['status'] == 'Ready to Disburse') { $disburse_state += 1; }
												
												?>
													<tr>
														<td class="right-pull-10 left-pull-10">
														<?php if($tsStat > 0): ?>
															<input type="checkbox" name="checkers[]" value="<?php echo $val2['id']; ?>" class="<?php echo $val['request_number']; ?>">
														<?php else: echo $numb.'.'; endif; ?>
														</td>
														<td class="right-pull-10 left-pull-10"><?php echo $category_name; ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $subcategory_name; ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?><input type="hidden" name="id[]" value="<?php echo $val2['id']; ?>" required></td>
														<td class="right-pull-10 left-pull-10 alignlt"><?php echo $val2['qty_required'].' '.$get_su; ?></td>
														<!--<td class="right-pull-10 left-pull-10 alignlt"><span class="pads7 forest-green-theme  white-font sml-rounded-button"><?php //echo $stock_balance.' '.$get_bu; ?></span></td>-->
														<td class="right-pull-10 left-pull-10 cs-width-150 pads7"><input type="hidden" name="qtyrequired[]" value="<?php echo $val2['qty_required']; ?>" required>
															<?php
																if($stock_balance > 0) {
																	?>
																	<input type="number" min="1" name="qtytransfer[]" placeholder="Enter here?" value="<?php if($val2['qty_received'] > 0) { echo $val2['qty_received']; } ?>"<?php if($tsStat > 0) { ?>readonly<?php } ?> required><?php
																} else { 
																	?><input type="number" min="1" name="qtytransfer[]" placeholder="0" value="<?php if($val2['qty_received'] > 0) { echo $val2['qty_received']; } ?>" class="no-back-black" required>
																	<?php
																}
															?>
														</td>
														<td class="right-pull-10 left-pull-10"><?php echo ucfirst($val2['stock_type']); ?></td>
														<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
														<?php
															if($fsStat > 0) {
																?>
																	<td class="default-text-font-bold">
																		<a href="javascript:void(0)" class="blue-font" onclick="removeitem(<?php echo $val2['id']; ?>)">Remove</a>
																	</td>
																<?php
															}
														?>
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

											if($tsStat > 0) {
												?>
													<tr class="">
														<td colspan="11" align="left" class="top-pull-5">
															<h4 class="large nobold steel-blue-font">Note: Check the boxes for the items to disburse then select the store to disburse from. Ensure quantity is available at the store</h4>
															<span class="float-left right-push-30">
																<div class="xform cs-width-250 bottom-push-7">
																	
																		<select name="stores" id="stores-<?php echo $val['request_number']; ?>" class="nopads no-back-black">
															 				<option value="" selected>Pick from store?</option>
															 				<?php echo $packto; ?>
															 			</select>
																	
																</div>
															</span>

															<input type="button" value=" Disburse Stock " class="pads7 blue-white-state rounded-button nc-width-15 left-push-20 anchor" onclick="disburseStock(this,'fr-<?php echo $val['request_number']; ?>','<?php echo $val['request_number']; ?>')" title="Click here to disburse stock to this store">
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

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>
	

<script>

	function irPrint(ir) {
		
		var sheet;
		
		sheet = document.getElementsByClassName('print-'+ir)[0];
		sheet.setAttribute('id','section-to-print');

		window.print();

		setTimeout(() => { sheet.removeAttribute('id'); },2000);
	}
	
	function pendingrqt() {
		window.location.href = window.location.href+"&ir=y";
	}

	function jsCancel() {
		var uri, hrf = window.location.href;
		uri = hrf.replace('&ir=y','');
		window.location.href = uri;
	}

	function changeList() {
		var id = document.getElementById('searchlist').value;
		var stat = document.getElementById('searchstatus').value;
		window.location.href = window.location.href+"&storeid="+id+"&stat="+stat;
	}

	function disburseStock(obj,frm,requestno) {
		if(document.getElementById('stores-'+requestno).value !== null && document.getElementById('stores-'+requestno).value > 0) {
			obj.value = "Processing..";
			obj.setAttribute('onclick','');
			htmlpassval('disburse-stock-to-store',requestno);
			setTimeout(() => { document.getElementById(frm).submit(); },500);
		}
	}

	function popwkf(frm,wkf) {
		//window.location.href = window.location.href+"&curi=pr-approval-request";
		var isworkflow = '<?php echo $irworkFlow; ?>';
		var iwkf = "'"+wkf+"'";
		var ifrm = "'"+frm+"'";
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-60 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger('+ifrm+','+iwkf+',event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label>Select your approval workflow?</label>';
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

	function jbtrigger(htmlform,obj,e) {
		e.preventDefault();
		document.getElementById(obj).value = document.getElementById('workflowx').value;
		setTimeout(() => { document.getElementById(htmlform).submit(); },1000);
	}


	function chkAll(obj) {
		var cc = document.getElementById(obj.id);
		var i, aa = document.getElementsByClassName(obj.lang);
		
		if(obj.value == 'off') {
			obj.value = 'on';
			chgclass(cc,'nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button dark-grey-theme motion');
			for(i=0; i<aa.length; i++) {
				aa[i].setAttribute('checked','checked');
			}
		} else if(obj.value == 'on') {
			obj.value = 'off';
			chgclass(cc,'nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button motion');
			for(i=0; i<aa.length; i++) {
				aa[i].removeAttribute('checked');
			}
		}
	}

	function removeitem(id) {
		var conf = confirm("Notification\n\nAre you sure you want to remove this item from the request list?");
		if(conf == true) { window.location.href = window.location.href+'&rqsremove='+id; }
	}

	var loadCurlFromPHP = function(data,msg) {
	    if(data == 1) {
	    	if(msg == 0) {
	    		alert('Item removed from list successfully..');
	    	}
	    }
	}


	loadCurlFromPHP(<?php echo $isremove; ?>,<?php echo $ismsg; ?>);

</script>