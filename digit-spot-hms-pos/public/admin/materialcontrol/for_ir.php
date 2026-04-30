<?php
	
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	if(isset($_POST['submitbutton'])) {

		$storage = remove_data_injection($_POST['storage']);
		$storage_name = idget_name($storage,'store_name',$tbL123);
		$request_number = remove_data_injection($_POST['requestnumber']);

		$id = $_POST['id'];
		$qtyrequired = $_POST['qtyrequired'];
		$qtytransfer = $_POST['qtytransfer'];
		
		$insert_data = 0; $balance = 0;

		for($i=0; $i < count($id); $i++) {
			
			if(!empty($qtytransfer[$i]) && $qtytransfer[$i] >= 0) {
				$balance = $qtyrequired[$i] - $qtytransfer[$i];

				$pst_field = "qty_received='{$qtytransfer[$i]}',qty_diff='{$balance}',status='Under Approval',whr_user={$userSignedIn}";
				$pst_query = "id={$id[$i]}";
			
				mysqli_data_update($tbL152,$pst_field,$pst_query);
				$pst_field=""; $pst_query="";
			}
		}

		$message_title = "Item Request Approval (".$request_number.")";
		$sendmessage = 'The following item request number ('.$request_number.') for '.$storage_name.' needs approval to complete disburstment. Please click <a href="javascript:void(0)" class="blue-font" name="'.$request_number.'" onclick="jpdbst(this.name)"><u>here</u></a> to acknowledge';

		$users = getuser4_notification(7);
		$message_params = array(
			"subject"=>$message_title,
			"sender"=>2,
			"receiver"=>$users,
			"message"=>$sendmessage,
			"priority"=>1,
			"msgtype"=>10,
			"datelogged"=>$server_get_date,
			"timelogged"=>$server_get_time
		);

		inboxmsg($message_params);

		if(is_array($users) && count($users)) {
				
			$joblevel = count($users);
			$pst_query = "subject='{$request_number}' AND approval_type='ITEM DISBURST'";
			
			if(isset($joblevel) && $joblevel == 1) {
				$pst_field = "job_level=1,subject='{$request_number}',user_one={$users[0]['id']},approval_type='ITEM DISBURST'";
			} elseif(isset($joblevel) && $joblevel == 2) {
				$pst_field = "job_level=2,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='ITEM DISBURST'";
			} elseif(isset($joblevel) && $joblevel == 3) {
				$pst_field = "job_level=3,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='ITEM DISBURST'";
			} elseif(isset($joblevel) && $joblevel == 4) {
				$pst_field = "job_level=4,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='ITEM DISBURST'";
			} elseif(isset($joblevel) && $joblevel == 5) {
				$pst_field = "job_level=5,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_five={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='ITEM DISBURST'";
			}

			mysqli_data_insert($tbL151,$pst_field,$pst_query);
		}

		unset($_GET['ir']);
	}


	if(isset($_GET['ir']) && $_GET['ir'] === 'y') {
		
		//get all available stores for request
		$query_avs = "SELECT storeid FROM {$tbL152} GROUP BY storeid";
		$xtbl = $tbL123; $xcol = "id"; $xopttext = "store_name";
		$pack_storage_name = html_db_select($query_avs,'storeid','storeid');

		if(isset($_GET['storeid']) && $_GET['storeid'] > 0) { $keywords = "storeid={$_GET['storeid']} AND deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval') GROUP BY storeid,request_number"; } else { $keywords = "deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval') GROUP BY storeid,request_number"; }
		
		$sqldata = "SELECT * FROM {$tbL152} WHERE ".$keywords;
		$wgt_ir = idget_data($sqldata);

		?>
			<div id="tktBox" class="fx-position-stick fscr zind-2 txp5-white noscroll xfadeout motion" align="center">
				<div class="cs-margin-top-100"></div>
				<span class="float-right right-push-20"><a href="javascript:jsCancel()" class="black-font"><b class="mbri-close"></b></a></span>
				<div id="rBox" class="fx-width-95 cs-height-500 white-theme xsml-rounded-button pads30 top-push-30 alignlt y-scroll">
					<h4 class="xlarge nobold black-font alignct"><b class="fas fa-question-circle right-push-5"></b>Here you can disburse items to outlet stores. Clicking the <u>apply</u> button will send your disburstment for approval before the actual deduction occur. Request with no physical store is not applicable</h4><br>

					<div class="cs-width-400 bottom-push-20">
						<div class="xform obj-light-shadow">
							<div class="sided-box right-pull-5 left-pull-5">
								<ul>
									<li class="nc-width-20 nunito-semibold top-pull-2">Search by:</li>
									<li class="nc-width-80 top-pull-2"><select id="searchlist" class="nopads no-back-black" onchange="changeList(this.value)"><option value="" selected>Available Store</option><?php echo $pack_storage_name; ?></select></li>
									<li></li>
								</ul>
							</div>
						</div>
					</div>

					<?php
						
						if(is_array($wgt_ir)) {
							
							$storage_name=""; $pack_storage_name="";
							
							foreach($wgt_ir as $key => $val) {
								
								$storage_name = idget_name($val['storeid'],'store_name',$tbL123);
								//$pack_storage_name .= '<option value="'.$val['storeid'].'">'.$storage_name.'</option>';
								
								$sqldata2 = "SELECT * FROM {$tbL152} WHERE request_number='{$val['request_number']}' AND storeid={$val['storeid']} AND deletedata=0 AND acceptance=0 AND status IN('Reviewing','Under Approval')";
								$wgt_ir2 = idget_data($sqldata2);

								$fsStat=0; $ssStat=0;
								foreach($wgt_ir2 as $ky => $vl) {
									if($vl['status'] == 'Reviewing') { $fsStat += 1; }
									if($vl['status'] == 'Under Approval') { $ssStat += 1; }
								}


								//approval status
								$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$val['request_number']}' AND approval_type='ITEM DISBURST'"; $jpL = idget_data($isjp);

								if(is_array($jpL) && count($jpL)) {
									?>
										<div class="sided-box grey-theme xsml-rounded-button pads20 bottom-push-10 alignlt">
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
									<div class="box-border-thick-bottom bottom-pull-10 bottom-push-20">
										<form action="" method="post" autocomplete="off">
											<span class="float-right"><?php if(empty($val['storeid']) || $val['storeid'] == '') { ?>?<?php } else { if($fsStat > 0 && $ssStat == 0) { ?><input type="submit" id="submitbutton" name="submitbutton" value="Apply" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance"><?php } else { if($ssStat > 0) { ?><h4 class="xlarge nobold light-red-font nunito-semibold">Under Approval</h4><?php } } } ?></span>
											<?php if($storage_name == 'Unknown') { ?><h3 class="large nobold nunito-bold light-red-font">* No physical store</h3><?php } else { ?><h3 class="large nobold nunito-bold">&bull; <b class="nobold">Item Request for <?php echo $storage_name; ?></b> (<?php echo $val['request_number']; ?>)</h3><h4 class="xlarge nobold "></h4><?php } ?><br>
											<input type="hidden" name="uri" value="apply-item-request">
											<input type="hidden" name="storage" value="<?php echo $val['storeid']; ?>">
											<input type="hidden" name="requestnumber" value="<?php echo $val['request_number']; ?>">
											<table cellspacing="0" cellpadding="0">
												<tr>
													<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Category</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Sub Category</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Required</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Qty in Stock</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Qty to Transfer</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Stock Type</td>
													<td class="default-text-font-bold right-pull-10 left-pull-10">Request By</td>
												</tr>
												<?php
													$numb = 0;
													
													foreach($wgt_ir2 as $key2 => $val2) {
														
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

														$numb += 1;

														?>
															<tr>
																<td class="right-pull-10 left-pull-10"><?php echo $numb; ?>.</td>
																<td class="right-pull-10 left-pull-10"><?php echo $category_name; ?></td>
																<td class="right-pull-10 left-pull-10"><?php echo $subcategory_name; ?></td>
																<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?><input type="hidden" name="id[]" value="<?php echo $val2['id']; ?>" required></td>
																<td class="right-pull-10 left-pull-10 alignct"><?php echo $val2['qty_required'].' '.$get_su; ?></td>
																<td class="right-pull-10 left-pull-10 alignct"><?php echo $stock_balance.' '.$get_bu; ?></td>
																<td class="right-pull-10 left-pull-10 cs-width-150 pads7"><input type="hidden" name="qtyrequired[]" value="<?php echo $val2['qty_required']; ?>" required><?php if($stock_balance > 0) { ?><input type="number" min="1" name="qtytransfer[]" placeholder="Enter here?" value="<?php if($val2['qty_received'] > 0) { echo $val2['qty_received']; } ?>" required><?php } else { ?><input type="number" min="1" name="qtytransfer[]" placeholder="N/A" class="no-back-black" readonly><?php } ?></td>
																<td class="right-pull-10 left-pull-10"><?php echo ucfirst($val2['stock_type']); ?></td>
																<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
															</tr>
														<?php

														$get_su=""; $get_bu=""; $categoryid=""; $subcategoryid=""; $buying_unit="";
														$category_name=""; $subcategory_name=""; $stock_balance="";
													}
												?>
											</table>
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
			</div>
		<?php
	}

?>

<script>

	function pendingrqt() {
		window.location.href = window.location.href+"&ir=y";
	}

	function jsCancel() {
		var uri, hrf = window.location.href;
		uri = hrf.replace('&ir=y','');
		window.location.href = uri;
	}

	function changeList(id) {
		window.location.href = window.location.href+"&storeid="+id;
	}

	/*window.onload = () => {

	}*/

</script>