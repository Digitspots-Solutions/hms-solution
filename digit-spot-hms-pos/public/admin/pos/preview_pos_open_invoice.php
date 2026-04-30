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
include "../../../includes/hotel_profile.php";
include "../../../includes/common_data_vars.php";
include "../module_operation_privilege.php";

	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css" media="all" />
		<link rel="stylesheet" href="../../../style/custom.css" media="all" />
		<link rel="stylesheet" href="../admin/applystyle.css" media="all" />
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

	<?php

	$posname = idget_data($tbL14,$cur_pos_store_id,'posname'); //get the name of the current pos in use


	//------------------------------------------------------------------------------------------------------------------------------

	if(isset($_SESSION['order_date']) && !empty($_SESSION['order_date'])) {
		unset($_SESSION['order_date']);
	}

	//------------------------------------------------------------------------------------------------------------------------------

	if(isset($_GET['order']) && isset($_GET['receipt']))
	{
		$new_order = escape_data($_GET['order']);
		$receipt_number = escape_data($_GET['receipt']);

		//get new order preview
		$additionalQuery = " AND payment IN('Completed','Paid','Credit','Debit','Complimentary')";
		$order_selection_key = array("order_number"=>$new_order,"status"=>"Completed");
		$invoice_data_property = "order_number,invoice_number,posid,customerid,discount_amount,sub_total,tax_amount,bill_amount,foodtype,billtype,tableid,cover,detail,status,datelogged,timelogged,payment,id,biller,roomid,receipt_number,media,cheque_number,paydetail,paydate,paytime,isprinted,isreversed,cashier,consumption_amount,service_charge_amount";
		$get_invoice_data = mysqli_data_fetch($tbL100,$invoice_data_property,$order_selection_key,'noarray');

		$additionalQuery = "";
		$order_selection_key_ = array("order_number"=>$new_order,"status"=>"Completed");
		$order_data_property = "order_number,itemid,qty,price";
		$get_order_data = mysqli_data_fetch($tbL99,$order_data_property,$order_selection_key_,'array');

		//get receipt detail
		/*$receipt_selection_key = array("receipt_number"=>$receipt_number);
		$receipt_data_property = "amount,receipt_number,media,cheque_number,detail,cashier,datelogged,timelogged";
		$get_receipt_data = mysqli_data_fetch($tbL101,$receipt_data_property,$receipt_selection_key,'noarray');*/
		
		#total payment
		//$gp_sql = "SUM(bill_amount)";
		//$gp_query = "order_number='{$new_order}' AND status='Completed'";
		//$get_gp = mysqli_arithmetic_data($tbL100,$gp_sql,$gp_query);
		$get_gp = $get_invoice_data[7];


		$get_food_type = get_pos_cmd($food_type,$get_invoice_data[8]);
		$get_bill_type = get_pos_cmd($bill_type,$get_invoice_data[9]);
		$get_table_name = idget_data($tbL17,$get_invoice_data[10],'tablename');
		$get_cover_name = $get_invoice_data[11];

		$billto = $get_invoice_data[18];
		$chargeroomid = $get_invoice_data[19];

		if($get_invoice_data[9] == 1) {
			$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname');
			$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
			$get_bill_name = $get_guest_name;
			$wpay = 1;
		} elseif($get_invoice_data[9] == 2) {
			$get_guest_name = idget_data($tbL102,$get_invoice_data[3],'fname');
			$get_guest_name .= idget_data($tbL102,$get_invoice_data[3],'lname');
			$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
			$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
			$r_suffix = idget_data($tbL56,$chargeroomid,'roomsuffix');
			$get_bill_name = $get_guest_name.' ('.$r_prefix.$r_number.$r_suffix.')';
			$wpay = 2;
		} elseif($get_invoice_data[9] == 3) {
			$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname').' ';
			$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
			$get_bill_name = idget_data($tbL33,$billto,'name');
			$wpay = 3;
		} elseif($get_invoice_data[9] == 4) {
			$get_bill_name = idget_data($tbL58,$billto,'name');
			$wpay = 0;
		} elseif($get_invoice_data[9] == 5) {
			$get_bill_name = idget_data($tbL7,$billto,'staffname');
			$wpay = 4;
		}
		
		$reverseday = dayDiffs($server_get_date,$get_invoice_data[14]);

		?>
			<div class="cs-height-100"></div>
			<div class="block-element top-pull-10">
				<div class="block-element light-yellow-theme pads10 bottom-push-10 left-push-10">
					<small>Note: Use tab-refresh icon to return to default screen</small>
				</div>
				
				<p class="top-pull-10 bottom-pull-10 right-pull-30 alignrt">
					<a href="javascript:void(0)" class="blue-font ft-sml-size" onclick="window.print()"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print A4</a>
				</p>
						
				<div id="section-to-print" class="block-element">
					<div class="ln-display-box float-left nc-width-70 pads15">
						<span class="ln-display-box float-left">
							<h1 class="large nobold default-text-font-bold"><?php echo $hotel_name; ?></h1>
							<small class="block-element top-push-3"><?php echo $hotel_address.' '.$hotel_state; ?></small>
							<br><h3 class="large nobold default-text-font-bold royal-blue-font"><?php echo strtoupper($posname); ?></h3>
							<small>OUTLET<?php echo $cur_pos_store_id; ?></small>
						</span>
						<span class="ln-display-box float-right">
							<h3 class="large nobold default-text-font-bold">Invoice</h3>
							<small class="block-element bottom-push-3">Date: <?php echo date("d/m/Y",strtotime($get_invoice_data[14])); ?>. <?php echo $get_invoice_data[15]; ?></small>
							<small class="block-element bottom-push-3">Invoice Number: #<?php echo $get_invoice_data[1]; ?></small>
							<small class="block-element bottom-push-3">Order Number: #<?php echo $get_invoice_data[0]; ?></small>
							<small class="block-element bottom-push-3">Status: <b class="red-font nobold"><?php echo $get_invoice_data[16]; ?></b></small>
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
										<!--<th width="100px" align="center">Tax  &#8358;</th>-->
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
														<!--<td width="100px" align="center"><?php //echo number_format($tax_amount,2); ?></td>-->
														<td width="100px" align="center"><?php echo number_format($sub_amount,2); ?></td>
													</tr>
												<?php
											}

											$get_paid_amount = $get_gp;
											$get_balance_amount = 0;

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

																		<?php

																			if(!empty($get_invoice_data[6]) && $get_invoice_data[6] > 0) {
																				?>
																					<span class="ln-display-box float-left">
																						<h4 class="large">VAT</h4>
																					</span>
																					<span class="ln-display-box float-right">
																						<h4 class="large nobold"><?php echo number_format($get_invoice_data[6],2); ?></h4>
																					</span>
																					<span class="block-element new-line-space">	
																					</span>
																				<?php
																			}

																			if(!empty($get_invoice_data[29]) && $get_invoice_data[29] > 0) {
																				?>
																					<span class="ln-display-box float-left">
																						<h4 class="large">Consumption Tax</h4>
																					</span>
																					<span class="ln-display-box float-right">
																						<h4 class="large nobold"><?php echo number_format($get_invoice_data[29],2); ?></h4>
																					</span>
																					<span class="block-element new-line-space">	
																					</span>
																				<?php
																			}

																			if(!empty($get_invoice_data[30]) && $get_invoice_data[30] > 0) {
																				?>
																					<span class="ln-display-box float-left">
																						<h4 class="large">Service Charge</h4>
																					</span>
																					<span class="ln-display-box float-right">
																						<h4 class="large nobold"><?php echo number_format($get_invoice_data[30],2); ?></h4>
																					</span>
																					<span class="block-element new-line-space">	
																					</span>
																				<?php
																			}

																		?>

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

																		<?php if($get_invoice_data[9] == 1 || $get_invoice_data[9] == 5): ?>
																		<span class="ln-display-box float-left">
																			<h4 class="large">Paid</h4>
																		</span>
																		<span class="ln-display-box float-right">
																			<h4 class="large nobold"><?php echo number_format($get_paid_amount,2); ?></h4>
																		</span>
																		<span class="block-element new-line-space">	
																		</span>
																		<?php endif; ?>
																	</div>
																	<div class="block-element box-border-thick-bottom pads10">
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
						<h4 class="large">Payment History</h4><br>
						<small class="block-element dark-grey-font bottom-push-3">Receipt Number</small>
						
						<?php if($get_invoice_data[26] == 0) { ?><small class="block-element bottom-push-15"><?php echo $get_invoice_data[20]; ?><a href="preview_receipt.php?order=<?php echo $new_order; ?>&receipt=<?php echo $receipt_number; ?>" class="black-font float-right"><b class="fa-print nobold"></b>&nbsp; Print POS</a></small><?php } ?>

						<?php
							if($get_invoice_data[16] == 'Paid') {
								?>
									<small class="block-element dark-grey-font bottom-push-3">Payment Mode</small>
									<small class="block-element bottom-push-15"><?php if(isset($get_invoice_data[21]) && $get_invoice_data[21] >= 1) { $paymode = idget_data($tbL24,$get_invoice_data[21],'name'); } else { $paymode = "Nil"; } echo $paymode; ?></small>
									<small class="block-element dark-grey-font bottom-push-3">CC/Cheque Number</small>
									<small class="block-element bottom-push-15"><?php if(isset($get_invoice_data[22]) && !empty($get_invoice_data[22])) { echo $get_invoice_data[22]; } else { echo "Nil"; } ?></small>
									<small class="block-element dark-grey-font bottom-push-3">Amount Paid</small>
									<small class="block-element bottom-push-15">&#8358; <?php echo number_format($get_invoice_data[7],2); ?></small>
									<small class="block-element dark-grey-font bottom-push-3">Remark</small>
									<small class="block-element bottom-push-15"><?php if(isset($get_invoice_data[23]) && !empty($get_invoice_data[23])) { echo $get_invoice_data[23]; } else { echo "Nil"; } ?></small>
									<small class="block-element dark-grey-font bottom-push-3">Transaction Date</small>
									<small class="block-element bottom-push-30"><?php echo date("d/m/Y",strtotime($get_invoice_data[24])).' '.$get_invoice_data[25]; ?></small>
								<?php
							}
						?>

						<small class="block-element dark-grey-font bottom-push-3">Cashier</small>
						<small class="block-element bottom-push-15"><?php if(isset($get_invoice_data[28]) && $get_invoice_data[28] >= 1) { $ccashier = idget_data($tbL7,$get_invoice_data[28],'staffname'); } else { $ccashier = "Unknown"; } echo $ccashier; ?></small>
					</div>
					<div class="block-element new-line-space">
					</div>

					<br><br>

					<small class="block-element default-text-font-bold top-push-20 left-push-20 bottom-push-15">Guest Sign</small>
				</div>
			</div>
		<?php
	}
?>

<div id="confirmbox" class="noshow fx-position-stick zind-2 motion fscr" align="center">
	<div class="block-element cs-height-200"></div>
	<div class="cs-width-350 white-theme pads20 bottom-push-30 left-push-50 alignlt box-border-thick">
		<h4 class="large"><b class="fa-nointernet nobold"></b> &nbsp; Confirm</h4>
		<small id="confirm-message-notification" class="block-element top-push-10"></small>
		<p class="top-pull-15 alignct">
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?cfm=y&cq=<?php echo $_GET['q']; ?>&ca=<?php echo $_GET['a']; ?>" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button blue-white-state right-push-10">YES</a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?cfm=n&crq=<?php echo $_GET['rq']; ?>&cr=<?php echo $_GET['r']; ?>" class="blue-font">NO</a>
		</p>
	</div>
</div>

<script>
	
	window.addEventListener('load',function() {
		
		document.addEventListener("keydown", function (event) {
		    if((event.ctrlKey || event.metaKey) && (event.key == "p" || event.charCode == 16 || event.charCode == 112 || event.keyCode == 80)) {
		        event.preventDefault();
		        event.cancelBubble = true;
		        event.stopImmediatePropagation();
		    }   
		});

	},false);

	
</script>