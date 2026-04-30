<div class="alignlt left-pull-30 bottom-push-30"><h3 class="large nobold nomargin">Here are the list of awaiting items request to be disbursed. Please acknowledge your consent</h3></div>
<div class="pads30">
	
	<?php

		$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

		//get user approval list
		$get_requestno = isset($_GET['irqs']) ? $_GET['irqs'] : "";
		
		$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$get_requestno}' AND approval_type='ITEM DISBURST'";
		$jpL = idget_data($isjp);

		if(!empty($get_requestno) && $get_requestno != '') { $keywords = "request_number='{$_GET['irqs']}' AND deletedata=0 AND acceptance=0 AND status IN('Under Approval') GROUP BY request_number"; } else { $keywords = "deletedata=0 AND acceptance=0 AND status IN('Under Approval') GROUP BY storeid,request_number"; }
		
		$sqldata = "SELECT * FROM {$tbL152} WHERE ".$keywords;
		$wgt_ir = idget_data($sqldata);
		
		if(is_array($wgt_ir)) {
			
			$storage_name=""; $pack_storage_name="";
			
			foreach($wgt_ir as $key => $val) {
				
				$storage_name = idget_name($val['storeid'],'store_name',$tbL123);
				
				$sqldata2 = "SELECT * FROM {$tbL152} WHERE request_number='{$val['request_number']}' AND storeid={$val['storeid']} AND deletedata=0 AND acceptance=0 AND status IN('Under Approval')"; $wgt_ir2 = idget_data($sqldata2);

				
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
					<form action="" method="post" autocomplete="off">
						<span class="float-right"><input type="submit" name="submitbutton" value="Edit" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button blue-white-state"></span>
						<h3 class="large nobold nunito-bold alignlt">+ Item Request for <?php echo $storage_name; ?></h3><br>
						<input type="hidden" name="uri" value="edit-item-request">
						<input type="hidden" name="requestno" value="<?php echo $val['request_number']; ?>">
						<div class="x-scroll top-push-20 bottom-pull-10">
							<div class="cs-width-1200">
								<table cellspacing="0" cellpadding="0">
									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Category</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Sub Category</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Required</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty in Stock</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Transferring</td>
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
													<td class="right-pull-10 left-pull-10 alignlt"><?php echo $val2['qty_required'].' '.$get_su; ?></td>
													<td class="right-pull-10 left-pull-10 alignlt"><span class="pads7 forest-green-theme  white-font sml-rounded-button"><?php echo $stock_balance.' '.$get_bu; ?></span></td>
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
							</div>
						</div>
					</form>
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
	<div id="rBox" class="fx-width-80 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function signPr(nos,id,level) {

		var wgets = document.getElementById(id), vhtml;
		
		if(wgets.value != '' && wgets.value !== null) {
			
			chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
			chgclass('rBox','fx-width-30 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-100 noscroll');

			vhtml = '';
			vhtml += '<p class="bottom-pull-15 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()"><b class="mbri-close"></b></a></p>';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<input type="hidden" name="uri" value="apply-item-request-approval">';
			vhtml += '<div class="alignlt">';
			vhtml += '<label>Write a comment if applicable</label>';
			vhtml += '<textarea name="commentpr" id="commentpr" placeholder="Type here.." class="notextborder"></textarea>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="hidden" name="requestno" id="requestno" value="'+nos+'">';
			vhtml += '<input type="hidden" name="signatory" id="signatory" value="'+wgets.value+'">';
			vhtml += '<input type="hidden" name="level" id="level" value="'+level+'">';
			vhtml += '<input type="submit" id="approvebutton" name="approvebutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
			vhtml += '</div>';
			vhtml += '</form>';
			
			writeObjheader('rBox',vhtml);
			parent.document.getElementById('workspace').scrollTop = 0;
		}
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

</script>