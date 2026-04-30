<?php
	
	//include "../../../includes/uom.php";
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");
	
	$keywords = ""; $disableHeaders = 0;

	if(isset($_GET['orderno'])) { $keywords = " AND order_number='{$_GET['orderno']}' AND order_status='Approved' AND pr_status='IOU'"; $disableHeaders = 1; } else { $keywords = " AND order_status IN('Approved') AND pr_status='IOU'"; $disableHeaders = 0; }

	#create table for batch
	createDatabasetable($var_tbl_116);
	
	$tbl = $mtbL8;

	//check if data exist in the table
	$query_po = "deletedata=0".$keywords;
	$po = mysqli_data_exist($tbl,$query_po);

	$iou_no = idget_fname($_GET['orderno'],'pr_no','iou_no',$tbL153);
	$iou_date = idget_fname($_GET['orderno'],'pr_no','datelogged',$tbL153);
	$store_location = idget_fname($_GET['orderno'],'order_number','store',$tbL121);
	$store_name = idget_name($store_location,'store_name',$tbL123);

?>

<div class="cs-height-30"></div>

<div class="pads30">
	<form action="" method="post" autocomplete="off" id="datasheet">
		<input type="hidden" name="ftask" id="ftask">
		<input type="hidden" name="xtbl" id="xtbl" value="<?php echo $tbl; ?>">
		<?php

			if($disableHeaders == 1) {
				?>
					<h3 class="large nobold dark-grey-font alignct"><b class="fas fa-question-circle right-push-5"></b> You can approve the request by acknowledging your consent. Click the drop-box and select your approval state only if the previous signatories have signed</h3><br>
					<h3 class="xlarge nobold nunito-bold alignlt bottom-pull-5 left-pull-7">+ IOU Approval</h3>
				<?php
			}

			if($po['isdata'] == true) {

				$isSql = "SELECT * FROM {$tbl} WHERE receipt_status='Pending' AND deletedata=0".$keywords." GROUP BY order_number";
				$wgt_po = idget_data($isSql);

				$af1_approval_signed_status = ""; $af2_approval_signed_status = ""; $af3_approval_signed_status = "";
				$af4_approval_signed_status = ""; $af5_approval_signed_status = "";

				$af1_status_color = ""; $af2_status_color = ""; $af3_status_color = "";
				$af4_status_color = ""; $af5_status_color = "";

				$jpL = "";

				if(is_array($wgt_po) && count($wgt_po)) {
					foreach($wgt_po as $key => $val) {
						idget_global($val['supplierid'],$var_supplier);

						$query_supplier = "SELECT SUM(order_net_amount) AS 'totalpramt' FROM {$tbl} WHERE order_number='{$val['order_number']}' AND deletedata=0"; $wgt_supplier = idget_data($query_supplier);

						?>
							<div class="box-border-thick xsml-rounded-button pads20 bottom-push-20">
								<span class="float-left"><h3 class="xlarge nobold nunito-semibold"><?php echo $_gparams[$var_supplier]['returnval']; ?><b class="fa-arrow-right left-push-10"></b></h3><h3 class="large nobold">IOU No: <b class="nunito-bold"><?php echo $iou_no; ?></b></h3></span>
								<p class="alignrt bottom-pull-20">
									<?php
										if($val['gstat'] == 'Pending') {
											?>
												<h3 class="large nobold alignlt">Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b></h3>
											<?php
										} elseif($val['gstat'] == 'Confirm') {
											if($val['order_status'] == 'Approved') {
												?>
													Order No: <a href="javascript:void(0)" class="royal-blue-font" onclick="jsPr('<?php echo $val['order_number']; ?>')"><b class="nobold"><u class="default-text-font-bold"><?php echo $val['order_number']; ?></u></b></a><br>
												<?php
												if($val['pr_status'] == 'IOU') {
													?>
														* Set for IOU<br>
														<b class="light-red-font">Under Approval</b>
													<?php
												} elseif($val['pr_status'] == 'IOU Approved') {
													?>
														* Set for IOU<br>
														<b class="forest-green-font">Approved</b>
													<?php
												}
											}
										}
									?>
								</p>

								<?php
									$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$val['order_number']}' AND approval_type='IOU'";
									$jpL = idget_data($isjp);

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
																			<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,1)">
																				<option value="" selected>Choose to sign</option>
																				<option value="1">Approve</option>
																				<option value="2">On Hold</option>
																				<option value="3">Reject</option>
																			</select>
																		<?php
																	} else {
																		if($jpL[0]['approval_one'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,1)">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,2)">
																					<option value="" selected>Choose to sign</option>
																					<option value="1">Approve</option>
																					<option value="2">On Hold</option>
																					<option value="3">Reject</option>
																				</select>
																			<?php
																		} else {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,2)" disabled="disabled">
																					<option value="0" selected>Signatory Locked</option>
																				</select>
																			<?php
																		}
																	} else {
																		if($jpL[0]['approval_two'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,2)">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,3)">
																					<option value="" selected>Choose to sign</option>
																					<option value="1">Approve</option>
																					<option value="2">On Hold</option>
																					<option value="3">Reject</option>
																				</select>
																			<?php
																		} else {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,3)" disabled="disabled">
																					<option value="0" selected>Signatory Locked</option>
																				</select>
																			<?php
																		}
																	} else {
																		if($jpL[0]['approval_three'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,3)">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,4)">
																					<option value="" selected>Choose to sign</option>
																					<option value="1">Approve</option>
																					<option value="2">On Hold</option>
																					<option value="3">Reject</option>
																				</select>
																			<?php
																		} else {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,4)" disabled="disabled">
																					<option value="0" selected>Signatory Locked</option>
																				</select>
																			<?php
																		}
																	} else {
																		if($jpL[0]['approval_four'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,4)">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,5)">
																					<option value="" selected>Choose to sign</option>
																					<option value="1">Approve</option>
																					<option value="2">On Hold</option>
																					<option value="3">Reject</option>
																				</select>
																			<?php
																		} else {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,5)" disabled="disabled">
																					<option value="0" selected>Signatory Locked</option>
																				</select>
																			<?php
																		}
																	} else {
																		if($jpL[0]['approval_five'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,5)">
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

								<div class="x-scroll bottom-pull-10">
									<div class="nc-width-100">
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Particular</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Amount</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Date/Time</td>
											</tr>
											<tr>
												<td class="right-pull-10 left-pull-10">Purchase request raised for (<?php echo $store_name; ?>) with order number (<?php echo $val['order_number']; ?>)</td>
												<td class="right-pull-10 left-pull-10"><?php echo number_format($wgt_supplier[0]['totalpramt']); ?></td>
												<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($iou_date)); ?></td>
											</tr>
													
										</table>
									</div>
								</div>
							</div>
						<?php
					}
				}

			} else {
				
				?>
					<div class="cs-height-50"></div>
					<div class="block-element" align="center">
						<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
							<span class="block-element nc-height-35"></span>
							<b class="mbri-pages ft-Lsize nobold"></b>
						</div>
						<h3 class="xlarge nobold dark-grey-font">No records found</h3><br>
						<h3 class="xlarge nobold">You may need to go to the purchase request section to complete the transaction</h3>
					</div>
				<?php
			}
		?>
	</form>

</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-80 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>


<div id="fbox"></div>

<script>

	function signPr(order,id,level) {

		var wgets = document.getElementById(id), vhtml;
		
		if(wgets.value != '' && wgets.value !== null) {
			
			chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
			chgclass('rBox','fx-width-30 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-100 noscroll');

			vhtml = '';
			vhtml += '<p class="bottom-pull-15 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()"><b class="mbri-close"></b></a></p>';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<input type="hidden" name="uri" value="apply-pr-iou-approval">';
			vhtml += '<div class="alignlt">';
			vhtml += '<label>Write a comment if applicable</label>';
			vhtml += '<textarea name="commentpr" id="commentpr" placeholder="Type here.." class="notextborder"></textarea>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="hidden" name="orderno" id="orderno" value="'+order+'">';
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


	function jsdStock(supplier,orderno,tbl) {
		var qp = "curi=qr-delete-record&tbl="+tbl+"&sql=supplierid={"+supplier+"} AND order_number='{"+orderno+"}'";
		qrdel(qp); //console.log(qp);
	}


	function jscStock() {
		window.location.href = window.location.href+"&curi=pr-approval-request";
	}

	function dopay(order) {
		window.location.href = window.location.href+"&curi=pr-pay-request&pr="+order;
	}

	function iou(order) {
		window.location.href = window.location.href+"&curi=pr-iou-approval&pr="+order;
	}

	function jsPr(key) {
		popmodalframe('materialcontrol','preview_pr',key,0,1200,800);
	}

</script>