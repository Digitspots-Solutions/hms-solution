<?php
	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;
?>

<div class="pads30">

	<p class="bottom-pull-10 alignrt">
		<a href="javascript:void(0)" class="blue-font ft-sml-size default-text-font-bold right-push-15" onclick="window.print()">Print <b class="fa-print nobold left-push-5"></b></a> <a href="javascript:void(0)" class="dark-grey-font ft-sml-size" onclick="window.parent.location.reload(true)">Close <b class="mbri-close left-push-5"></b></a>
	</p>

	<div id="section-to-print" class="block-element">

		<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
			<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
		</div>
		<div class="cs-width-400 margin-auto-ct alignct">
			<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
		</div>


		<h1 class="large nobold nunito-semibold nomargin alignrt">Transfer Request</h1><br>
	
		<?php

			$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

			//get user approval list
			$get_requestno = isset($_GET['trqs']) ? $_GET['trqs'] : "";
			
			$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$get_requestno}' AND approval_type='TR'";
			$jpL = idget_data($isjp);

			$keywords = "";

			if(!empty($get_requestno) && $get_requestno != '') { $keywords = "transfer_number='{$get_requestno}' AND deletedata=0 AND transfer_status IN('Under Approval','Transfer Completed') GROUP BY transfer_number"; }
			
			$sqldata = "SELECT * FROM {$tbL156} WHERE ".$keywords;
			$wgt_ir = idget_data($sqldata);
			
			if(is_array($wgt_ir)) {
				
				$storage_name=""; $pack_storage_name="";
				
				foreach($wgt_ir as $key => $val) {
					
					$sqldata2 = "SELECT * FROM {$tbL156} WHERE transfer_number='{$val['transfer_number']}' AND deletedata=0 AND transfer_status IN('Under Approval','Transfer Completed')"; $wgt_ir2 = idget_data($sqldata2);

					
					if(is_array($jpL) && count($jpL)) {
						?>
							<div class="sided-box bottom-push-10 alignlt">
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
												<?php
												
												if($userSignedIn != $jpL[0]['user_one']) {
													?>
														<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
													<?php
												} elseif($userSignedIn == $jpL[0]['user_one']) {
													if($jpL[0]['approval_one'] == 0) {
														?>
															<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,1)">
																<option value="" selected>Choose to sign</option>
																<option value="1">Approve</option>
																<option value="2">On Hold</option>
																<option value="3">Reject</option>
															</select>
														<?php
													} else {
														if($jpL[0]['approval_one'] == 2) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,1)">
																	<option value="" selected>Change approval state</option>
																	<option value="1">Approve</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														}
														?>
															<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
															<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
														<?php
													}
												}
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
												<?php

												if($userSignedIn != $jpL[0]['user_two']) {
													?>
														<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
													<?php
												} elseif($userSignedIn == $jpL[0]['user_two']) {
													if($jpL[0]['approval_two'] == 0) {
														if($jpL[0]['approval_one'] == 1) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,2)">
																	<option value="" selected>Choose to sign</option>
																	<option value="1">Approve</option>
																	<option value="2">On Hold</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														} else {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,2)" disabled="disabled">
																	<option value="0" selected>Signatory Locked</option>
																</select>
															<?php
														}
													} else {
														if($jpL[0]['approval_two'] == 2) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,2)">
																	<option value="" selected>Change approval state</option>
																	<option value="1">Approve</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														}
														?>
														<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
														<?php
													}
												}
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
												<?php

												if($userSignedIn != $jpL[0]['user_three']) {
													?>
														<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
													<?php
												} elseif($userSignedIn == $jpL[0]['user_three']) {
													if($jpL[0]['approval_three'] == 0) {
														if($jpL[0]['approval_two'] == 1) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,3)">
																	<option value="" selected>Choose to sign</option>
																	<option value="1">Approve</option>
																	<option value="2">On Hold</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														} else {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,3)" disabled="disabled">
																	<option value="0" selected>Signatory Locked</option>
																</select>
															<?php
														}
													} else {
														if($jpL[0]['approval_three'] == 2) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,3)">
																	<option value="" selected>Change approval state</option>
																	<option value="1">Approve</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														}
														?>
														<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
														<?php
													}
												}
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
												<?php

												if($userSignedIn != $jpL[0]['user_four']) {
													?>
														<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
													<?php
												} elseif($userSignedIn == $jpL[0]['user_four']) {
													if($jpL[0]['approval_four'] == 0) {
														if($jpL[0]['approval_three'] == 1) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,4)">
																	<option value="" selected>Choose to sign</option>
																	<option value="1">Approve</option>
																	<option value="2">On Hold</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														} else {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,4)" disabled="disabled">
																	<option value="0" selected>Signatory Locked</option>
																</select>
															<?php
														}
													} else {
														if($jpL[0]['approval_four'] == 2) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,4)">
																	<option value="" selected>Change approval state</option>
																	<option value="1">Approve</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														}
														?>
														<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
														<?php
													}
												}
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
												<?php

												if($userSignedIn != $jpL[0]['user_five']) {
													?>
														<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
													<?php
												} elseif($userSignedIn == $jpL[0]['user_five']) {
													if($jpL[0]['approval_five'] == 0) {
														if($jpL[0]['approval_four'] == 1) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,5)">
																	<option value="" selected>Choose to sign</option>
																	<option value="1">Approve</option>
																	<option value="2">On Hold</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														} else {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,5)" disabled="disabled">
																	<option value="0" selected>Signatory Locked</option>
																</select>
															<?php
														}
													} else {
														if($jpL[0]['approval_five'] == 2) {
															?>
																<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_requestno; ?>',this.id,5)">
																	<option value="" selected>Change approval state</option>
																	<option value="1">Approve</option>
																	<option value="3">Reject</option>
																</select>
															<?php
														}
														?>
														<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
														<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
														<?php
													}
												}
											}
										?>
									</li>
									<li></li>
								</ul>
							</div>
						<?php
					}

					?>
					
						<h3 class="large nobold alignlt">Request No. #<?php echo $val['transfer_number']; ?></h3>
						
						<div class="x-scroll top-push-20 bottom-pull-10">
							<div class="nc-width-100">
								<table cellspacing="0" cellpadding="0">
									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">From Store</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">To Outlet</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Transferred</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Last Price</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Amount</td>
									</tr>
									<?php
										$numb = 0; $total_cost_amount = 0;
										
										foreach($wgt_ir2 as $key2 => $val2) {
											
											idget_global($val2['itemid'],$var_item);
											idget_global($val2['userid'],$var_user);

											//$frompos = idget_name($val2['from_posid'],'posname',$tbL14);
											$frompos = idget_name($val2['from_posid'],'store_name',$tbL123);
											if($val2['to_posid'] > 0) { $topos = idget_name($val2['to_posid'],'posname',$tbL14); }
											else { $topos = "Warehouse"; }

											$categoryid = idget_name($val2['itemid'],'categoryid',$mtbL5);
											$subcategoryid = idget_name($val2['itemid'],'subcategoryid',$mtbL5);
										
											$category_name = idget_name($categoryid,'category',$mtbL2);
											$subcategory_name = idget_name($subcategoryid,'subcategory',$mtbL3);
											
											$get_su = arrayget_key($uoms,$val2['uom']);
										
											$numb += 1;

											$lsp = "SELECT * FROM {$mtbL18} WHERE itemid={$val2['itemid']} AND deletedata=0 ORDER BY id DESC LIMIT 1"; $get_lsp = idget_data($lsp);

											$last_price = $get_lsp[0]['costprice'];
											$cost_amount = $val2['qty_transfer'] * $last_price;

											$total_cost_amount = $total_cost_amount + $cost_amount;

											?>
												<tr>
													<td class="right-pull-10 left-pull-10"><?php echo $numb; ?>.</td>
													<td class="right-pull-10 left-pull-10"><?php echo $frompos; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $topos; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
													<td class="right-pull-10 left-pull-10 alignlt"><?php echo $val2['qty_transfer'].' '.$get_su; ?></td>
													<td class="right-pull-10 left-pull-10">&#8358; <?php echo number_format($last_price,2); ?></td>
													<td class="right-pull-10 left-pull-10">&#8358; <?php echo number_format($cost_amount,2); ?></td>
												</tr>
											<?php

											$frompos=""; $topos=""; $cost_amount = ""; $last_price = "";
											$get_su=""; $get_bu=""; $categoryid=""; $subcategoryid=""; $buying_unit="";
											$category_name=""; $subcategory_name=""; $stock_balance="";
										}
									?>

										<tr>
											<td class="right-pull-10 left-pull-10 default-text-font-bold" colspan="6">Total</td>
											<td class="right-pull-10 left-pull-10 default-text-font-bold grey-1-theme">&#8358; <?php echo number_format($total_cost_amount,2); ?></td>
										</tr>

								</table>
							</div>
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