<?php
include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

$smdl = "pos";
$cur_pos_store_id = $_SESSION['postoreid'];

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../../../includes/common_data_vars.php";
include "../module_operation_privilege.php";

	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../../style/custom.css"/>
		<link rel="stylesheet" href="../admin/applystyle.css"/>
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

	<?php

	$posname = idget_data($tbL14,$cur_pos_store_id,'posname'); //get the name of the current pos in use


	//------------------------------------------------------------------------------------------------------------------------------

	if(isset($_GET['q']) && isset($_GET['a']))
	{
		if($_GET['a'] == 'cancel' || $_GET['a'] == 'delete') { $write_im = "Are you sure you want to cancel this order?"; }
		?>
			<script>
				window.addEventListener('load',function() {
					parent.document.getElementById('workspace').scrollTop = 0;
					writeObjheader('confirm-message-notification','<?php echo $write_im; ?>');
					objDisplay('confirmbox');
				},false);
			</script>
		<?php
	}

	if(isset($_GET['cfm']) && $_GET['cfm'] == 'y') {
		
		$cr = isset($_GET['cq']) ? $_GET['cq'] : "";
		$ca = isset($_GET['ca']) ? $_GET['ca'] : "";
		
		if($ca == 'cancel' || $ca == 'delete') {
			
			$del_query = array("order_number"=>$cr);
			
			#for consumable items
			$order_data = "itemid,qty";
			$get_order_data = mysqli_data_fetch($tbL99,$order_data,$del_query,'array');

			if(is_array($get_order_data)) {
				foreach($get_order_data as $key => $val) {
					$storagetype = idget_data($tbL16,$val['itemid'],'storagetype');
					$stockout = idget_data($tbL16,$val['itemid'],'stockout');
					$balance = idget_data($tbL16,$val['itemid'],'balance');

					if($storagetype == 'consumable') {
						$new_stockout = $stockout - $val['qty'];
						$new_balance = $balance + $val['qty'];

						$pst_query = array("id"=>$val['itemid']);
						$pst_field = array("stockout"=>$new_stockout,"balance"=>$new_balance);
						mysqli_data_update($tbL16,$pst_field,$pst_query);
					}
				}
			}

			trash_record($tbL99,$del_query);
			trash_record($tbL100,$del_query);

			//create a log file
			$message = "Recently cancelled pending pos order with order number: ".$cr;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);mysqli_data_insert($tbL8,$log_datasets,'');

			?>
			<script>
				window.addEventListener('load',function() {
					window.location.href = '../pos_counter.php?logs=<?php echo $posname; ?>';
				},false);
			</script>
			<?php
		}

	} elseif(isset($_GET['cfm']) && $_GET['cfm'] == 'n') {
		$cr = isset($_GET['cr']) ? $_GET['cr'] : "";
		$crq = isset($_GET['crq']) ? $_GET['crq'] : "";
		?>
			<script>
				window.addEventListener('focus',function() {
					window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $cr; ?>=<?php echo $crq; ?>';
				},false);
			</script>
		<?php
	}

	//------------------------------------------------------------------------------------------------------------------------------

	$new_order = isset($_GET['new_order']) ? escape_data($_GET['new_order']) : "";

	if(!empty($new_order)) {
		
		//get new order preview
		$additionalQuery = "";
		$order_selection_key = array("order_number"=>$new_order,"status"=>"Pending","payment"=>"Pending");
		$invoice_data_property = "order_number,invoice_number,posid,customerid,discount_amount,sub_total,tax_amount,bill_amount,foodtype,billtype,tableid,cover,detail,status,datelogged,timelogged,biller,roomid,isreversed,ispaid";
		$get_invoice_data = mysqli_data_fetch($tbL100,$invoice_data_property,$order_selection_key,'noarray');

		$order_selection_key_ = array("order_number"=>$new_order,"status"=>"Pending");
		$order_data_property = "order_number,itemid,qty,price";
		$get_order_data = mysqli_data_fetch($tbL99,$order_data_property,$order_selection_key_,'array');

		$get_food_type = get_pos_cmd($food_type,$get_invoice_data[8]);
		$get_bill_type = get_pos_cmd($bill_type,$get_invoice_data[9]);
		$get_table_name = idget_data($tbL17,$get_invoice_data[10],'tablename');
		$get_cover_name = $get_invoice_data[11];

		$billto = $get_invoice_data[16];
		$chargeroomid = $get_invoice_data[17];

		if($get_invoice_data[9] == 1) {
			$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname').' ';
			$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
			$get_bill_name = $get_guest_name;
			$wpay = 1;
		} elseif($get_invoice_data[9] == 2) {
			$get_guest_name = idget_data($tbL102,$get_invoice_data[3],'fname').' ';
			$get_guest_name .= idget_data($tbL102,$get_invoice_data[3],'lname');
			$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
			$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
			$r_suffix = idget_data($tbL56,$chargeroomid,'roomsuffix');
			$get_bill_name = $r_prefix.$r_number.$r_suffix;
			$wpay = 2;
		} elseif($get_invoice_data[9] == 3) {
			$get_bill_name = idget_data($tbL33,$billto,'name');

			if(!empty($get_invoice_data[3])) {
				$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname').' ';
				$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
			} else {
				$get_guest_name = "";
			}

			$get_bill_name = $get_bill_name.' ('.$get_guest_name.')';
			
			$wpay = 3;
		} elseif($get_invoice_data[9] == 4) {
			$get_bill_name = idget_data($tbL58,$billto,'name');
			if(!empty($get_invoice_data[3])) {
				$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname').' ';
				$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
			} else {
				$get_guest_name = "";
			}

			$get_bill_name = $get_bill_name.' ('.$get_guest_name.')';

			$wpay = 0;
		} elseif($get_invoice_data[9] == 5) {
			$get_bill_name = idget_data($tbL7,$billto,'staffname');
			$wpay = 4;
		}

		$reverseday = dayDiffs($server_get_date,$get_invoice_data[14]);
		
		?>
			<div class="cs-height-100"></div>
			<div class="block-element top-pull-10">
				<div class="block-element light-yellow-theme pads7 bottom-push-10 left-push-10">
					<small>Note: Use tab-refresh icon to return to default screen</small>
				</div>
				<div class="ln-display-box float-left nc-width-70 pads15">
					<span class="ln-display-box float-left">
						<h3 class="large nobold default-text-font-bold"><?php echo strtoupper($posname); ?></h3>
						<small>OUTLET<?php echo $cur_pos_store_id; ?></small>
					</span>
					<span class="ln-display-box float-right">
						<h3 class="large nobold default-text-font-bold">New Order</h3>
						<small class="block-element bottom-push-3">Date: <?php echo date("d/m/Y",strtotime($get_invoice_data[14])); ?>. <?php echo $get_invoice_data[15]; ?></small>
						<small class="block-element bottom-push-3">Order Number: #<?php echo $new_order; ?></small>
						<small class="block-element bottom-push-3">Order Status: <b class="red-font nobold"><?php echo $get_invoice_data[13]; ?></b></small>
					</span>
					<span class="block-element new-line-space">
					</span>
					<span class="block-element top-push-20">
						<h4 class="large nobold default-text-font-bold">Guest details</h4>
						<div class="block-element sml-rounded-button noscroll top-push-7">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<?php
										if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
											?>
												<th width="100px" align="center">Food Type</th>
											<?php
										}
									?>
									<th width="150px" align="center">Bill Type</th>
									<th width="150px" align="center">Bill To</th>
									<th width="150px" align="center">Guest Name</th>

									<?php 
										if(isset($checkIfistableset) && $checkIfistableset > 0) {
											?>
												<th width="100px" align="center">Table</th>
												<th width="100px" align="center">Cover</th>
											<?php
										}
									?>
							
								</tr>
								<tr>
									<?php
										if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
											?>
												<td width="100px" align="center"><?php echo $get_food_type; ?></td>
											<?php
										}
									?>
									<td width="150px" align="center"><?php echo $get_bill_type; ?></td>
									<td width="150px" align="center"><?php echo $get_bill_name; ?></td>
									<td width="150px" align="center"><?php echo $get_guest_name; ?></td>

									<?php 
										if(isset($checkIfistableset) && $checkIfistableset > 0) {
											?>
												<td width="100px" align="center"><?php echo $get_table_name; ?></td>
												<td width="100px" align="center"><?php echo $get_cover_name; ?></td>
											<?php
										}
									?>
								</tr>
							</table>
						</div>
					</span>
					<span class="block-element top-push-20">
						<h4 class="large">Order Items</h4>
						<div class="block-element sml-rounded-button noscroll top-push-7">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="200px" align="center">Product</th>
									<th width="100px" align="center">Code</th>
									<th width="150px" align="center">Unit Price &#8358;</th>
									<th width="100px" align="center">Units</th>
									<th width="100px" align="center">Tax  &#8358;</th>
									<th width="100px" align="center">Amount &#8358;</th>
								</tr>

								<?php
									if(is_array($get_order_data)) {
										$item_name = ""; $item_code = ""; $sub_amount = 0; $tax_amount = 0;
										foreach ($get_order_data as $order_key => $order_value) {
											
											$item_name = idget_data($tbL16,$order_value['itemid'],'item');
											$item_code = idget_data($tbL16,$order_value['itemid'],'itemcode');

											$sub_amount = $order_value['price'] * $order_value['qty'];
											$tax_amount = ($cur_pos_tax_charge / 100) * $sub_amount;

											?>
												<tr>
													<td width="200px" align="center"><?php echo $item_name;?></td>
													<td width="100px" align="center"><?php echo $item_code; ?></td>
													<td width="150px" align="center"><?php echo number_format($order_value['price'],2); ?></td>
													<td width="100px" align="center"><?php echo $order_value['qty']; ?></td>
													<td width="100px" align="center"><?php echo number_format($tax_amount,2); ?></td>
													<td width="100px" align="center"><?php echo number_format($sub_amount,2); ?></td>
												</tr>
											<?php
										}

										$get_paid_amount = 0;
										$get_balance_amount = $get_invoice_data[7] - $get_paid_amount;

										?>
											<tr>
												<td colspan="7">
													<div class="block-element top-push-15">
														<span class="ln-display-box float-left nc-width-40 pads15">
															<h4 class="large">Remarks</h4>
															<small class="block-element"><?php echo nl2br($get_invoice_data[12]) ?></small>
														</span>
														<span class="ln-display-box float-right nc-width-40 pads15">
															<div class="block-element box-border-thick sml-rounded-button alignrt">
																<div class="block-element box-border-thick-bottom pads10">
																	<span class="ln-display-box float-left">
																		<h4 class="large">Sub Total</h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php echo number_format($get_invoice_data[5],2); ?></h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>
																	<span class="ln-display-box float-left">
																		<h4 class="large">Discount</h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php echo number_format($get_invoice_data[4],2); ?></h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>
																	<!--<span class="ln-display-box float-left">
																		<h4 class="large"><?php //echo $cur_pos_tax_name; ?></h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php //echo number_format($get_invoice_data[6],2); ?>0</h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>-->
																</div>
																<div class="block-element box-border-thick-bottom pads10">
																	<span class="ln-display-box float-left">
																		<h4 class="large">Bill Amount</h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php echo number_format($get_invoice_data[7],2); ?></h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>
																	<span class="ln-display-box float-left">
																		<h4 class="large">Paid</h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php echo number_format($get_paid_amount,2); ?></h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>
																</div>
																<div class="block-element grey-theme pads10">
																	<span class="ln-display-box float-left">
																		<h4 class="large">Balance</h4>
																	</span>
																	<span class="ln-display-box float-right">
																		<h4 class="large nobold"><?php echo number_format($get_balance_amount,2); ?></h4>
																	</span>
																	<span class="block-element new-line-space">	
																	</span>
																</div>
															</div>
														</span>
														<span class="block-element new-line-space">	
														</span>
													</div>
												</td>
											</tr>
										<?php
									}
								?>
							</table>
						</div>
					</span>
				</div>
				<div class="ln-display-box float-right nc-width-25 box-border-thick sml-rounded-button pads20 obj-light-shadow right-push-10">
					<h4 class="large nobold">Make Payment <b class="float-right">+</b></h4><br>
					
					<?php $list_payment_modes = select_dt_fetch('iscounter','Yes',$tbL24,'id','name'); ?>

					<form action="process_payment.php" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<input type="hidden" name="order_number" value="<?php echo $new_order; ?>">
						<?php
							if(isset($wpay) && $wpay == 1) {
								?>
									<span class="block-element bottom-push-10">
										<select name="payment-mode" id="payment-mode" onchange="allowpay(this.value)">
											<option value="" selected="selected">Payment Mode</option>
											<?php echo $list_payment_modes; ?>
										</select>
									</span>
									<!--<span class="block-element bottom-push-10">
										<input type="text" name="cheque-number" id="cheque-number" placeholder="CC/Cheque Number" title="CC/Cheque Number">
									</span>-->
									<span class="block-element bottom-push-10">
										<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[7],2); ?>" title="Amount Billed" readonly>
										<input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[7]; ?>">
									</span>
									<span id="pay-box" class="noshow motion">
									</span>
									<span class="block-element bottom-push-10">
										<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
									</span>

									<p class="top-pull-30 alignct">
										<input type="submit" name="paybutton" id="paybutton" value="Pay Now" class="noshow submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button">
									</p>
								<?php
							} elseif(isset($wpay) && $wpay == 2) {
								?>
									<input type="hidden" name="payment-mode" id="payment-mode" value="0">
									<!--<span class="block-element bottom-push-10">
										<select name="payment-mode" id="payment-mode" onchange="">
											<option value="" selected="selected">Payment Mode</option>
											<?php //echo $list_payment_modes; ?>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cheque-number" id="cheque-number" placeholder="CC/Cheque Number" title="CC/Cheque Number">
									</span>-->
									<span class="block-element bottom-push-10">
										<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[7],2); ?>" title="Amount Billed" readonly>
										<input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[7]; ?>">
									</span>
									<span class="block-element bottom-push-10">
										<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
									</span>

									<p class="top-pull-30 alignct">
										<small class="block-element bottom-push-10 dark-grey-font">Either pay now or transfer to room to pend the payment</small>
										<input type="submit" name="transferbutton" value="Transfer to Room" class="submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 black-white-state rounded-button bottom-push-7"><br>
										<!--<input type="submit" name="paybutton" value="Pay Now" class="submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button">-->
									</p>
								<?php
							} elseif(isset($wpay) && $wpay == 3) {
								?>
									<span class="block-element bottom-push-10">
										<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[7],2); ?>" title="Amount Billed" readonly>
										<input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[7]; ?>">
									</span>
									<span class="block-element bottom-push-10">
										<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
									</span>

									<p class="top-pull-30 alignct">
										<small class="block-element bottom-push-10 dark-grey-font">Confirm transaction as complimentary, no payment applies</small>
										<input type="submit" name="complimentarybutton" value="Confirm As Complim.." class="submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button">
									</p>
								<?php
							} elseif(isset($wpay) && $wpay == 0) {
								?>
									<span class="block-element bottom-push-10">
										<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[7],2); ?>" title="Amount Billed" readonly>
										<input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[7]; ?>">
									</span>
									<span class="block-element bottom-push-10">
										<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
									</span>

									<p class="top-pull-30 alignct">
										<small class="block-element bottom-push-10 dark-grey-font">Payment is charged to spl. guest account</small>
										<input type="submit" name="creditbutton" value="Transfer to Group" class="submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 black-white-state rounded-button">
									</p>
								<?php
							} elseif(isset($wpay) && $wpay == 4) {
								?>
									<span class="block-element bottom-push-10">
										<select name="payment-mode" id="payment-mode" onchange="allowpay(this.value)">
											<option value="" selected="selected">Payment Mode</option>
											<?php echo $list_payment_modes; ?>
										</select>
									</span>
									<!--<span class="block-element bottom-push-10">
										<input type="text" name="cheque-number" id="cheque-number" placeholder="CC/Cheque Number" title="CC/Cheque Number">
									</span>-->
									<span class="block-element bottom-push-10">
										<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[7],2); ?>" title="Amount Billed" readonly><input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[7]; ?>">
									</span>
									<span id="pay-box" class="noshow motion">
									</span>
									<span class="block-element bottom-push-10">
										<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
									</span>

									<p class="top-pull-30 alignct">
										<small class="block-element bottom-push-10 dark-grey-font">Either pay now or transfer to staff to pend the payment</small>
										<input type="submit" name="transferbutton" id="transferbutton" value="Transfer to Staff" class="submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 black-white-state rounded-button bottom-push-7"><br>
										<input type="submit" name="paybutton" id="paybutton" value="Pay Now" class="noshow submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button">
									</p>
								<?php
							}

						?>

						<?php if($get_invoice_data[19] == 0 && isset($allowPosReverse) && $allowPosReverse == 200) { ?><p class="top-pull-15 alignct"><a href="?q=<?php echo $new_order; ?>&a=cancel&rq=<?php echo $new_order; ?>&r=new_order&new_order=<?php echo $new_order; ?>" class="blue-font ft-sml-size">Cancel Order</a></p><?php } ?>
						
						<?php if($get_invoice_data[19] == 1 && $get_invoice_data[18] == 0) { if((isset($reverseday) && $reverseday <= $gh_get_allow_past_reverse) && (isset($allowPosReverse) && $allowPosReverse == 200)) { ?><p class="top-pull-15 alignct"><a href="javascript:void(0)" class="blue-font top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 box-border-thick rounded-button obj-light-shadow ft-sml-size" onclick="popmodalframe('','posreversemode','<?php echo $new_order; ?>','reverse',400,400);">Reverse Order</a></p><?php } } ?>
						
					</form>
				</div>
				<div class="block-element new-line-space">
				</div>
			</div>
		<?php
	}
?>

<div id="processbar" class="fx-position-stick fscr zind-1 motion noshow txp2-white" align="center">
	<div class="block-element nc-height-20">&nbsp;</div>
	<div class="cs-width-250 white-theme obj-shadow pads20">Processing data..</div>
</div>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion btscr" align="left">
	<div class="cs-width-400 white-theme pads20 bottom-push-30 left-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="pos-header-notification" class="large red-font"></h4>
		<small id="pos-message-notification" class="block-element top-push-10"></small>
	</div>
</div>

<div id="confirmbox" class="noshow fx-position-stick zind-2 motion fscr" align="center">
	<div class="block-element cs-height-200"></div>
	<div class="cs-width-350 white-theme pads20 bottom-push-30 left-push-50 box-border-thick alignlt">
		<h4 class="large"><b class="fa-nointernet nobold"></b> &nbsp; Confirm</h4>
		<small id="confirm-message-notification" class="block-element top-push-10"></small>
		<p class="top-pull-15 alignct">
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?cfm=y&cq=<?php echo $_GET['q']; ?>&ca=<?php echo $_GET['a']; ?>" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button blue-white-state right-push-10">YES</a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cfm=n&crq=<?php echo $_GET['rq']; ?>&cr=<?php echo $_GET['r']; ?>" class="blue-font">NO</a>
		</p>
	</div>
</div>

<script>

	function allowpay(payid) {
		
		if(payid !== null && payid != '') {
			
			sqldatastring.sql = "SELECT * FROM paytype_tbl WHERE id="+payid;
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				wrdata = ajaxresult.datastring;
				data = wrdata[0];
				
				if(data.paytype == 'Two-way Payment') {
					
					vhtml = '<h4 class="xlarge nobold default-text-font-bold">Two-Way Payment:</h4><h4 class="large nobold black-font">Indicate value according to name pattern. For example Cash & Card: field 1 cash, field 2 card</h4>';
					vhtml += '<ul class="nolist">';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 right-pull-5">';
					vhtml += '<input type="number" min="1" name="wgtf1" id="wgtf1" placeholder="0.00" onkeyup="tally()" required>';
					vhtml += '</li>';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 left-pull-5">';
					vhtml += '<input type="number" min="1" name="wgtf2" id="wgtf2" placeholder="0.00" onkeyup="tally()" required>';
					vhtml += '</li>';
					vhtml += '<li class="block-element new-line-space">';
					vhtml += '</li>';
					vhtml += '</ul>';

				} else if(data.paytype == 'One-way Payment') {

					var onecash = document.getElementById('amount-billed').value;

					vhtml = '<h4 class="xlarge nobold default-text-font-bold">One-Way Payment:</h4><h4 class="large nobold black-font">Indicate value only in the first field</h4>';
					vhtml += '<ul class="nolist">';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 right-pull-5">';
					vhtml += '<input type="number" min="1" name="wgtf1" id="wgtf1" placeholder="0.00" value="'+onecash+'">';
					vhtml += '</li>';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 left-pull-5">';
					vhtml += '<input type="number" min="1" name="wgtf2" id="wgtf2" placeholder="0.00" disabled>';
					vhtml += '</li>';
					vhtml += '<li class="block-element new-line-space">';
					vhtml += '</li>';
					vhtml += '</ul>';
				}
				
				chgclass('pay-box','block-element light-yellow-theme pads10 motion bottom-push-10');
				writeObjheader('pay-box',vhtml);
			
				setTimeout(() => {
					if(data.paytype == 'One-way Payment') {
						//document.getElementById('wgtf1').value = document.getElementById('amount-billed').value;
						chgclass('paybutton','submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button');
					} else {
						chgclass('paybutton','noshow motion');
					}
				},2000);
			}

			if(document.getElementById('transferbutton')) {
				chgclass('transferbutton','noshow');
			}

		} else {
			if(document.getElementById('transferbutton')) {
				chgclass('transferbutton','submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 black-white-state rounded-button bottom-push-7');
			}
		}
	}

	function tally() {
		var bl = document.getElementById('amount-billed');
		var fpy = document.getElementById('wgtf1');
		var spy = document.getElementById('wgtf2');

		if((eval(fpy.value) + eval(spy.value)) == eval(bl.value)) {
			fpy.blur(); spy.blur();
			chgclass('paybutton','submit top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button');
		} else if((eval(fpy.value) + eval(spy.value)) < eval(bl.value)) {
			chgclass('paybutton','noshow motion');
		} else if((eval(fpy.value) + eval(spy.value)) > eval(bl.value)) {
			fpy.value=0; spy.value=0;
			chgclass('paybutton','noshow motion');
		}
	}

	window.onload = () => { parent.document.getElementById('workspace').scrollTop = 0; }

</script>