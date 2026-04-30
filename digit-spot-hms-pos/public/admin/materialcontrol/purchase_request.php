<?php
	
	#get all applcable workflows
	$prworkFlow = getjob_workflow(6);
	$iouworkFlow = getjob_workflow(3);
	$payworkFlow = getjob_workflow(8);

	//include "../../../includes/uom.php";
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	/*if(isset($_SESSION['postoreid'])) { $toStore = " AND store={$_SESSION['postoreid']}"; }
	else { $toStore = ""; }*/

	$toStore = "";
	
	$keywords = ""; $disableHeaders = 0;

	if(isset($_GET['joborder']) && !empty($_GET['joborder'])) {
		$jo_query = "order_number='{$_GET['joborder']}'";
		$jo_post = "pr_status='Job Order'";
		$result = mysqli_data_update($mtbL8,$jo_post,$jo_query);
	}

	if(isset($_GET['instantpr']) && !empty($_GET['instantpr'])) {
		$jo_query = "order_number='{$_GET['instantpr']}'";
		$jo_post = "var_status=3,var_approval='Yes'";
		$result = mysqli_data_update($mtbL8,$jo_post,$jo_query);
	}

	if(isset($_GET['orderno'])) {
		$keywords = " AND order_number='{$_GET['orderno']}' AND ispr_to_manual IN('No') AND order_status IN('Approved','Pending')";
		$disableHeaders = 0;
		unset($_GET['orderno']);
	} else {
		$keywords = " AND order_status IN('Archiver')";
		$disableHeaders = 0;
	}

	#create table for batch
	createDatabasetable($var_tbl_116);

	#create table for job order control
	createDatabasetable($var_tbl_160);
	createDatabasetable($var_tbl_161);
	createDatabasetable($var_tbl_162);
	
	$tbl = $mtbL8;

	//check if data exist in the table
	$query_po = "deletedata=0".$keywords;
	$po = mysqli_data_exist($tbl,$query_po);

	//get only approved orders number
	$lAr = "SELECT * FROM {$tbl} WHERE store_type IN('Virtual Stores') AND order_status IN('Approved') AND ispr_to_manual IN('No') AND receipt_status IN('Pending') AND deletedata=0".$toStore." GROUP BY order_number";
	$listofApprovedOrders = html_db_datalist($lAr,'order_number');

?>

<div class="cs-height-30"></div>

<div class="pads30">

	<form action="" method="post" autocomplete="off" id="datasheet">
		<input type="hidden" name="ftask" id="ftask">
		<input type="hidden" name="xtbl" id="xtbl" value="<?php echo $tbl; ?>">
		<?php

			if($disableHeaders == 0) {
				?>
					<div class="float-right cs-width-250 box-border-thick sml-rounded-button pads15 alignlt">
						<h4 class="xlarge nobold default-text-font-bold">Search for PRs</h4>
						<input list="rqi" name="rqiList" id="rqiList" placeholder="Type to choose order no.?" class="nopads no-back-black" onblur="getrqi(this)" autocomplete="off">
						<datalist id="rqi">
							<?php echo $listofApprovedOrders; ?>
						</datalist>
					</div>

					<div class="alignlt"><h3 class="xlarge nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Click <a href="javascript:void(0)" class="blue-font ft-Xsize" onclick="jsForm(); fSp()"><u class="ft-Xsize">here</u></a> to create new purchase order</h3></div>

					<div class="block-element new-line-space"></div>

					<div class="cs-height-50"></div>
				<?php

			}

			if($disableHeaders == 1) {
				?>
					<h3 class="large nobold dark-grey-font alignct"><b class="fas fa-question-circle right-push-5"></b> You can approve the request by acknowledging your consent. Click the drop-box and select your approval state only if the previous signatories have signed</h3><br>
					<h3 class="xlarge nobold nunito-bold alignlt bottom-pull-5 left-pull-7">+ Purchase Request Approval</h3>
				<?php
			}

			if($po['isdata'] == true) {

				//$isSql = "SELECT * FROM {$tbl} WHERE receipt_status='Pending' AND deletedata=0".$keywords." GROUP BY supplierid,order_number";
				$isSql = "SELECT * FROM {$tbl} WHERE deletedata=0".$keywords." GROUP BY supplierid,order_number";
				$wgt_po = idget_data($isSql);

				$af1_approval_signed_status = ""; $af2_approval_signed_status = ""; $af3_approval_signed_status = "";
				$af4_approval_signed_status = ""; $af5_approval_signed_status = "";

				$af1_status_color = ""; $af2_status_color = ""; $af3_status_color = "";
				$af4_status_color = ""; $af5_status_color = "";

				$jpL = "";

				if(is_array($wgt_po) && count($wgt_po)) {
					
					$wget_store_name = "";

					foreach($wgt_po as $key => $val) {
						
						idget_global($val['supplierid'],$var_supplier);
						
						if($val['store'] == 0) {
							$wget_store_name = 'Warehouse';
						} else {
							idget_global($val['store'],$var_store);
							$wget_store_name = $_gparams[$var_store]['returnval'];
						}

						$query_supplier = "SELECT * FROM {$tbl} WHERE supplierid={$val['supplierid']} AND order_number='{$val['order_number']}' AND deletedata=0"; $wgt_supplier = idget_data($query_supplier);

						?>
							<div class="box-border-thick xsml-rounded-button pads20 bottom-push-20">
								<span class="float-left alignlt">
									<h3 class="xlarge nobold nunito-bold nomargin">For <?php echo $wget_store_name; ?></h3>
									<h3 class="large nobold">Supplier: <?php echo $_gparams[$var_supplier]['returnval']; ?><b class="fa-arrow-right left-push-10"></b></h3>
								</span>
								<p class="alignrt bottom-pull-20">
									<?php
										if($val['gstat'] == 'Pending') {
											?>
												<span class="block-element bottom-push-30"><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow" onclick="jscStock('<?php echo $val['order_number']; ?>')">Send for Approval  <b class="fab fa-codepen left-push-5"></b></a></span>

												<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsEdit(); callupPr('<?php echo $val['order_number']; ?>')">Edit <b class="fas fa-edit left-push-5"></b></a> <a href="javascript:void(0)" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button right-push-10" onclick="wgtfsubmit('datasheet','delete')">Delete  <b class="fas fa-trash-alt left-push-5"></b></a>

												<h3 class="large nobold alignlt">Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b></h3>
											<?php
											$jpL = "";
										} elseif($val['gstat'] == 'Confirm') {
											$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$val['order_number']}' AND approval_type='PR'";
											$jpL = idget_data($isjp);

											if($val['order_status'] == 'Approved') {
												?>
													Order No: <a href="javascript:void(0)" class="royal-blue-font" onclick="jsPrint('<?php echo $val['order_number']; ?>')"><b class="royal-blue-font"><?php echo $val['order_number']; ?></b></a><br>
													Status: <b class="forest-green-font">Approved</b><br><br>
												<?php
												if($val['pr_status'] == 'IOU') {
													?>
														<b class="nobold light-red-font">* Set for IOU Approval</b>
													<?php
												} elseif($val['pr_status'] == 'IOU Approved') {
													?>
														<b class="nobold forest-green-font">* IOU Approved</b><br><br>
														<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsPrint('<?php echo $val['order_number']; ?>')">Print LPO <b class="fas fa-print left-push-5"></b></a>
													<?php
												} elseif($val['pr_status'] == 'Payment Inview') {
													?>
														<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsPrint('<?php echo $val['order_number']; ?>')">Print LPO <b class="fas fa-print left-push-5"></b></a>

														Awaiting payment
													<?php
												} elseif($val['pr_status'] == 'Payment Approved') {
													?>
														<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsPrint('<?php echo $val['order_number']; ?>')">Print LPO <b class="fas fa-print left-push-5"></b></a>

														<b class="nobold dark-blue-font">Funds Disbursed</b>
													<?php
												} elseif($val['pr_status'] == 'Job Order') {
													
													$jostat = idget_fname($val['order_number'],'pr_no','status',$mtbL26);
													
													if($jostat == 'Completed') {
														?>
															<b class="nobold forest-green-font">* Job Order Completed</b><br><br>
															<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsPrint2('<?php echo $val['order_number']; ?>')">Print Job Order <b class="fas fa-print left-push-5"></b></a>
															<p>&nbsp;</p>
														<?php
													} elseif($jostat == 'Partial' || $jostat == 'Unknown') {
														?>
															<b class="nobold light-red-font">* Pending Job Order</b><br>
															<?php echo $val['order_number']; ?>
															<p class=""><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsPrint2('<?php echo $val['order_number']; ?>')">Print Job Order <b class="fas fa-print left-push-5"></b></a> <a href="javascript:void(0)" class="blue-font top-pull-7 right-pull-20 bottom-pull-7 left-pull-20" onclick="jsReceiveJoborder('<?php echo $val['order_number']; ?>')">Receive Job Order <b class="fas fa-handshake left-push-5"></b></a></p>
															<p>&nbsp;</p>
														<?php
													}
												} else {
													if($val['var_approval'] == 'No') {
														?>
															<!--<a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="dopay('<?php //echo $val['order_number']; ?>')">Process Payment</a>-->
															<a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="iou('<?php echo $val['order_number']; ?>')">Create IOU</a><?php if($val['var_status'] != 3) { ?> &nbsp; <a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="doJoborder('<?php echo $val['order_number']; ?>')">Job Order</a> &nbsp; 
															<a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="dopr('<?php echo $val['order_number']; ?>')">Receive PR</a><?php } elseif($val['var_status'] == 3) { ?><b class="nobold light-red-font left-push-5">* PR Received</b><?php } ?>

															<p>&nbsp;</p>
															
														<?php
													}
												}
											} else {
												?>
													Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b><br>
													Status: <b class="light-red-font">Under Approval</b><br>
													<!--<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsEdit(); callupPr('<?php //echo $val['order_number']; ?>')">Edit <b class="fas fa-edit left-push-5"></b></a> <a href="javascript:void(0)" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button right-push-10" onclick="wgtfsubmit('datasheet','delete')">Delete  <b class="fas fa-trash-alt left-push-5"></b></a>-->
													<p class="alignrt bottom-pull-3"><a href="javascript:void(0)" class="blue-font" onclick="jsPrint('<?php echo $val['order_number']; ?>')">Print LPO <b class="fas fa-print left-push-5"></b></a></p>
												<?php
											}
										}
									?>
								</p>

								<?php
									if(is_array($jpL) && count($jpL) > 0) {
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
																			<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,1)" disabled="disabled">
																				<option value="" selected>N/A</option>
																				<option value="1">Approve</option>
																				<option value="2">On Hold</option>
																				<option value="3">Reject</option>
																			</select>
																		<?php
																	} else {
																		if($jpL[0]['approval_one'] == 2) {
																			?>
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,1)" disabled="disabled">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,2)" disabled="disabled">
																					<option value="" selected>N/A</option>
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,2)" disabled="disabled">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,3)" disabled="disabled">
																					<option value="" selected>N/A</option>
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,3)" disabled="disabled">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,4)" disabled="disabled">
																					<option value="" selected>N/A</option>
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,4)" disabled="disabled">
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,5)" disabled="disabled">
																					<option value="" selected>N/A</option>
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
																				<select name="signpr" id="signpr" onchange="signPr('<?php echo $val['order_number']; ?>',this.id,5)" disabled="disabled">
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
									<div class="cs-width-2000">
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td class="default-text-font-bold right-pull-10 left-pull-10"></td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Sn.</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Quantity</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Unit Price</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Sub Total</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Total</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Order Status</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Prepared By</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">Date/Time</td>
											</tr>

											<?php
												
												$numbr = 0; $total_amount = 0;
												
												foreach($wgt_supplier as $key2 => $val2) {
													
													idget_global($val2['itemid'],$var_item);
													idget_global($val2['userid'],$var_user);
													
													$buyingUnit = arrayget_key($uoms,$val2['uom']);

													$order_stat = $val2['order_status'];
													$order_stat_color = wgtcolor($order_stat);

													$numbr += 1;
													$total_amount = $total_amount + $val2['order_net_amount'];
													
													?>
														<tr>
															<td class="right-pull-10 left-pull-10"><div class="cs-width-40 top-push-5 bottom-push-5 pads7 grey-theme sml-rounded-button alignct"><input type="checkbox" name="checkers[]" value="<?php echo $val2['id']; ?>"<?php if($order_stat == 'Approved') { ?>disabled="disabled"<?php } ?>></div></td>
															<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_ordered'].' '.$buyingUnit; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['unitprice'],2); ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['order_total_amount'],2); ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['order_net_amount'],2); ?></td>
															<td class="right-pull-10 left-pull-10" style="color: <?php echo $order_stat_color; ?>"><?php echo $order_stat; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
														</tr>
													<?php

													$buyingUnit = ""; $order_stat = ""; $order_stat_color = "";
												}
											?>

											<tr>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="yellow-theme nunito-bold right-pull-10 left-pull-10 alignrt">&#8358;</td>
												<td class="yellow-theme nunito-bold right-pull-10 left-pull-10"><?php echo number_format($total_amount,2); ?></td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
												<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
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
					<div class="cs-height-100"></div>
					<div class="block-element" align="center">
						<h3 class="large nobold dark-grey-font"></h3>
					</div>
				<?php
			}
		?>
	</form>

	<div class="top-push-30 sided-box box-border-dashed pads30 sml-rounded-button">
		<ul>
			<?php
				$get_ucf_pr = "SELECT * FROM {$tbl} WHERE gstat IN('Confirm','Pending') AND var_approval IN('No') AND ispr_to_manual IN('No') AND deletedata=0 GROUP BY order_number ORDER BY id DESC LIMIT 100";
				$fetch_ucf_pr = idget_data($get_ucf_pr);

				if(is_array($fetch_ucf_pr)) {
					
					$pr_creator=""; $pr_store=""; $pr_date="";
					
					?>
						<div class="cs-width-300 margin-auto-ct bottom-push-20">
							<input type="text" id="pr-store-search" placeholder="Type the store to list.." onkeyup="lookup_store(this)">
						</div>

						<h4 class="large nobold alignct red-font">Recent or Active Purchase Request Numbers (* Click to view)</h4><br>
					<?php
					foreach($fetch_ucf_pr as $key => $val) {
						$pr_creator = idget_name($val['userid'],'staffname',$tbL7);
						$pr_store = idget_name($val['store'],'store_name',$tbL123);
						$pr_date = date('M. jS Y',strtotime($val['datelogged']));
						?>
							<li class="alignlt right-pull-30 bottom-pull-10 filter" title="<?php echo $pr_store; ?>">
								<h4 class="large nobold nomargin">PR logged by <?php echo $pr_creator; ?></h4>
								<h4 class="large nobold black-font nomargin">On <b class="dark-blue-font nobold"><?php echo $pr_date; ?></b></h4>
								<p class="light-red-font">for <?php echo $pr_store; ?></p>
								<p><a href="javascript:void(0)" class="blue-font nunito-bold" onclick="getrqi('<?php echo $val['order_number']; ?>')"><?php echo $val['order_number']; ?> <b class="mbri-right left-push-5"></b></a></p>
							</li>
						<?php
					}
				} else {
					?>
						<li class="ft-xLsize grey-font">
							&bull;&bull;&bull;
						</li>
					<?php
				}
			?>
			<li></li>
		</ul>
	</div>

</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	const updateNumbr = {'row':1,'idLabel':20};

	function jsForm() {
		datastring.process = "view";
		datastring.tip = "Creating purchase order for products/items";
		
		xform('nohtmlform');

		var stopAfter = setInterval(function() {
			if(document.getElementById('fbox-content')) {
				writeObjheader('fbox-content','<h3 class="large nobold alignct">Loading..</h3>');
				$('#fbox-content').load('html/order.html');
				clearInterval(stopAfter);
			}
		},1000);
	}

	
	function jsEdit() {
		datastring.process = "view";
		datastring.tip = "Editing purchase order for products/items";
		
		xform('nohtmlform');

		var stopAfter = setInterval(function() {
			if(document.getElementById('fbox-content')) {
				writeObjheader('fbox-content','<h3 class="large nobold alignct">Loading..</h3>');
				$('#fbox-content').load('html/update_order.html');
				clearInterval(stopAfter);
			}
		},1000);
	}

	
	function callupPr(order) {

		var endfetch = setInterval(() => {
			if(document.getElementById('table-form')) {
				
				sqldatastring.sql = "SELECT t1.id,t1.order_number,t1.supplierid,t1.itemid,t1.unitprice,t1.qty_ordered,t1.order_total_amount,t1.delivery_date,t1.delivery_note,t2.supplier_name,t3.item FROM stock_item_purchase_order_tbl t1, supplier_tbl t2, stock_item_tbl t3 WHERE t1.order_number='"+order+"' AND t1.supplierid = t2.id AND t1.itemid = t3.id";
				sqldataQuery(wgtpop,sqldatastring);

				function wgtpop(response) {
					var grandcost = 0, grandtotal = 0;
					var i, nrow, vhtml, data, ajaxresult = JSON.parse(response);
					data = ajaxresult.datastring;

					vhtml = ''; nrow = 1;

					for(i=0; i<data.length; i++) {
						 
						vhtml += '<tr>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-30"><a href="javascript:void(0)" title="Change supplier" lang="supplier'+nrow+'" class="light-red-font" onclick="changeSS(this)"><b class="mbri-close"></b></a></td>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200"><select name="supplier[]" id="supplier'+nrow+'" onclick="wgets4(3,this.id,0)" class="nopads no-back-black"><option value="'+data[i].supplierid+'">'+data[i].supplier_name+'</option></select><input type="hidden" name="rwid[]" value="'+data[i].id+'"></td>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200"><select name="item[]" id="item'+nrow+'" class="nopads no-back-black"><option value="'+data[i].itemid+'">'+data[i].item+'</option></select></td>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input list="thecostprice'+nrow+'" name="unitcost[]" id="unitcost'+nrow+'" placeholder="Type to choose?" class="nopads no-back-black unitcost" oninput="nextTp('+nrow+')" value="'+data[i].unitprice+'" required><datalist id="thecostprice'+nrow+'"></datalist></td>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input type="number" min="1" step=".01" name="quantity[]" id="quantity'+nrow+'" placeholder="Enter quantity?" value="'+data[i].qty_ordered+'" class="nopads no-back-black quantity" onkeypress="nextRow('+nrow+',this.id,event)" onblur="lastRow('+nrow+',this.id)" required></td>';
						vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input type="text" name="totalcost[]" id="totalcost'+nrow+'" placeholder="Auto?" value="'+data[i].order_total_amount+'" class="nopads no-back-black totalcost" readonly required></td>';
						vhtml += '</tr>';

						nrow += 1;

						grandcost = eval(grandcost) + eval(data[i].unitprice);
						grandtotal = eval(grandtotal) + eval(data[i].order_total_amount);
					}

					htmlpassval(order,'datau');
					htmlpassval(grandcost,'grandcost');
					htmlpassval(grandtotal,'grandtotal');
					htmlpassval(data[0]['delivery_date'],'deliverydate');
					htmlpassval(data[0]['delivery_note'],'deliverynote');
					writeObjheader('table-form',vhtml);
					updateNumbr.row = nrow - 1;
				}

				clearInterval(endfetch);
			}
		},1000);
	}


	function fSp() {
		var stopfSp = setInterval(() => {
			if(document.getElementById('supplier1')) {
				document.getElementById('supplier1').click();
				clearInterval(stopfSp);
			}
		},500);
	}
	

	function jsReceiveJoborder(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-black noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/receive_joborder.php?pr='+order;
	}

	function jsPrint(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/printpr.php?pr='+order;
	}


	function jsPrint2(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/printjo.php?pr='+order;
	}


	/*function jsPdf(order,batch) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'workspace/wks_ini.php?wget='+wgets+'&param=pdf-po';
	}*/


	function signPr(order,id,level) {

		var wgets = document.getElementById(id), vhtml;
		
		if(wgets.value != '' && wgets.value !== null) {
			
			chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
			chgclass('rBox','fx-width-30 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-100 noscroll');

			vhtml = '';
			vhtml += '<p class="bottom-pull-15 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()"><b class="mbri-close"></b></a></p>';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<input type="hidden" name="uri" value="apply-pr-approval">';
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


	function jscStock(orderno) {
		//window.location.href = window.location.href+"&curi=pr-approval-request";
		var isworkflow = '<?php echo $prworkFlow; ?>';
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
		vhtml += '<input type="hidden" name="uri" value="pr-approval-request">';
		vhtml += '<input type="hidden" name="pr" value="'+orderno+'">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label>Select your approval workflow?</label>';
		vhtml += '<select name="workflow" id="workflow" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}

	function dopay(order) {
		//window.location.href = window.location.href+"&curi=pr-pay-request&pr="+order;
		var isworkflow = '<?php echo $payworkFlow; ?>';
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
		vhtml += '<input type="hidden" name="uri" value="pr-pay-request">';
		vhtml += '<input type="hidden" name="pr" value="'+order+'">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label>Select your approval workflow?</label>';
		vhtml += '<select name="workflow" id="workflow" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}

	function iou(order) {
		//window.location.href = window.location.href+"&curi=pr-iou-approval&pr="+order;
		var isworkflow = '<?php echo $iouworkFlow; ?>';
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
		vhtml += '<input type="hidden" name="uri" value="pr-iou-approval">';
		vhtml += '<input type="hidden" name="pr" value="'+order+'">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label>Select your approval workflow?</label>';
		vhtml += '<select name="workflow" id="workflow" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function getrqi(obj) {
		if(obj.value && obj.value !== null && obj.value != '') { window.location.href = window.location.href+"&orderno="+obj.value; }
		else { window.location.href = window.location.href+"&orderno="+obj; }
	}


	function doJoborder(order) {
		
		var cfj = confirm('Are you sure you want to send this purchase for job-order?\nClick Ok to process or Cancel to retun');
		
		if(cfj == true) {
			window.location.href = window.location.href+"&joborder="+order;
		}
	}


	function dopr(order) {
		
		var cfj = confirm('Are you sure you want to receive this purchase?\nClick Ok to process or Cancel to retun');
		
		if(cfj == true) {
			window.location.href = window.location.href+"&instantpr="+order;
		}
	}


	function changeSS(obj) {
		var id = obj.lang;
		var dhtml = document.getElementById(id).innerHTML;
		document.getElementById(id).innerHTML = '<option value="" selected>Choose?</option>'+dhtml;
	}


	function lookup_store(obj) {
		var li = document.getElementsByClassName('filter');
		var search = obj.value;

		for(var j=0; j < li.length; j++) {
			var lname = li[j].getAttribute('title');
			if(lname.indexOf(search) > -1) {
				li[j].setAttribute('class','alignlt right-pull-20 left-pull-20 bottom-pull-10 motion filter');
			} else {
				li[j].setAttribute('class','noshow motion filter');
			}
		}
	}

	
</script>