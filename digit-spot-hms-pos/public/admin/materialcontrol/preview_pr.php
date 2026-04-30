<?php
include "../../includes/class.vars.php";
include "../../includes/class.function.php";
	
$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

$keywords = ""; $disableHeaders = 0;

$order_no = $ftoken;

if(isset($order_no) && !empty($order_no)) {
	$keywords = " AND order_number='{$order_no}' AND order_status IN('Approved')";
	$disableHeaders = 0;
} else {
	$keywords = " AND order_status IN('Archiver')".$toStore;
	$disableHeaders = 0;
}

$tbl = $tbL121;

//check if data exist in the table
$query_po = "deletedata=0".$keywords;
$po = mysqli_data_exist($tbl,$query_po);

?>

<link rel="stylesheet" href="materialcontrol/css3.0/default.css"/>

<p class="right-pull-30 bottom-pull-7 alignrt"><input type="button" value="Print" onclick="window.print()"></p>

<div id="section-to-print" class="pads30">

	<form action="" method="post" autocomplete="off" id="datasheet">
		
		<?php

			if($po['isdata'] == true) {

				$isSql = "SELECT * FROM {$tbl} WHERE receipt_status='Pending' AND deletedata=0".$keywords." GROUP BY supplierid,order_number";
				$wgt_po = wgetSQL($isSql);

				$af1_approval_signed_status = ""; $af2_approval_signed_status = ""; $af3_approval_signed_status = "";
				$af4_approval_signed_status = ""; $af5_approval_signed_status = "";

				$af1_status_color = ""; $af2_status_color = ""; $af3_status_color = "";
				$af4_status_color = ""; $af5_status_color = "";

				$jpL = "";

				if(is_array($wgt_po) && count($wgt_po)) {
					foreach($wgt_po as $key => $val) {
						idget_global($val['supplierid'],$var_supplier);
						idget_global($val['store'],$var_mstore);

						$query_supplier = "SELECT * FROM {$tbl} WHERE supplierid={$val['supplierid']} AND order_number='{$val['order_number']}' AND deletedata=0"; $wgt_supplier = wgetSQL($query_supplier);

						?>
							<div class="box-border-thick xsml-rounded-button pads20 bottom-push-20">
								<span class="float-left alignlt">
									<h3 class="xlarge nobold nunito-bold nomargin">For <?php echo $_gparams[$var_mstore]['returnval']; ?></h3>
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
											$jpL = wgetSQL($isjp);

											if($val['order_status'] == 'Approved') {
												?>
													Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b><br>
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
												} else {
													?>
														<!--<a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="dopay('<?php //echo $val['order_number']; ?>')">Process Payment</a> <a href="javascript:void(0)" class="blue-font box-border-thick-green top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="iou('<?php //echo $val['order_number']; ?>')">IOU</a>-->
														Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b><br>
														Status: <b class="light-red-font">Go to dashboard search for detail about this PR</b><br>

													<?php
												}
											} else {
												?>
													Order No: <b class="royal-blue-font"><?php echo $val['order_number']; ?></b><br>
													Status: <b class="light-red-font">Under Approval</b><br>
												<?php
											}
										}
									?>
								</p>

								<?php
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

								<div class="x-scroll bottom-pull-10">
									<div id="section-to-print" class="cs-width-1500">
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

			}
		?>
	</form>

</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>