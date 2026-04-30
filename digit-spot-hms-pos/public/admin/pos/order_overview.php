<?php
	$smdl = "pos";
	if(isset($_GET['logs'])) { $logs = escape_data($_GET['logs']); }

	if(function_exists('get_pos_cmd')):
	else: include "../../includes/pos_common_data.php";
	endif;

	$pst_order_number = "";
	$isguestAct = 0;
	$guestAct_msg = "";
	$ths_guest_pry = 0;

	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;


	#reverse order
	if(isset($_GET['reverse']) && !empty($_GET['reverse'])) {

		$wgt_reverseid = $_GET['reverse'];
		$wgt_remark = $_GET['remark'];

		$sql_uquery = array("order_number"=>$wgt_reverseid);
		$sql_udata = array("detail"=>$wgt_remark,"isreversed"=>1,"deletedata"=>1);
		$isdata = mysqli_data_update($tbL100,$sql_udata,$sql_uquery);

		if(isset($isdata) && $isdata == 2) {

			$sql_udata2 = array("isreversed"=>1,"deletedata"=>1);
			mysqli_data_update($tbL99,$sql_udata2,$sql_uquery);

			$bill_data = "billtype,biller,bill_amount,media";
			$get_bill_data = mysqli_data_fetch($tbL100,$bill_data,$sql_uquery,'noarray');

			if($get_bill_data[3] >= 1 && $counter_sesid > 0) {

				$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$get_bill_data[3],"ispast"=>0);
				$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

				if($sales_counter_data[0] >= $get_bill_data[2]) {
					$new_collection = $sales_counter_data[0] - $get_bill_data[2];
					$sales_counter_sql = array("collection"=>$new_collection);
					mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
				}
			}

			if((!empty($get_bill_data[0]) && $get_bill_data[0] == 4) && (!empty($get_bill_data[1]) && $get_bill_data[1] > 0)) {

				$pay_amount = $get_bill_data[2];
				$transaction_desc = "POS reversed bill";

				#retrieve group creditlimt
				$credit_limit = idget_data($tbL58,$get_bill_data[1],'creditlimit');
				$new_creditlimit = $credit_limit + $pay_amount;

				#update group creditlimt
				$blc_selection_key = array("id"=>$get_bill_data[1]);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);
				
				$ledger_dataquery = "";
				$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_bill_data[1],"transaction_number"=>$ordernumber,"transaction_type"=>"Credit","amount"=>$pay_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				
				mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);
				
			}

			#for consumable items
			$order_data = "itemid,qty";
			$get_order_data = mysqli_data_fetch($tbL99,$order_data,$sql_uquery,'array');

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

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Order was reversed successfully";
			
			$islogfile = 1;
			$logfile_msg = "Outlet transaction with order number (".$wgt_reverseid.") was reversed by this user";

			$isguestAct = 1;
			$pst_booking_number = $wgt_reverseid;
			$remark_tag = "reverse"; $app_tag = "POS"; $session_tag = "POS Order";
			$guestAct_msg = "{$posname} : POS reversed with total amount of {$get_bill_data[2]}";
		}
	}


	if((isset($_GET['token']) && !empty($_GET['token'])) && (isset($_SESSION['postoreid']) && $_SESSION['postoreid'] > 0)) {
		
		$order_number = $_GET['token'];
		$cur_pos_store_id = $_SESSION['postoreid'];
		$postype = idget_data($tbL14,$cur_pos_store_id,'postype');

		$constrain = array("order_number"=>$order_number);
		$isfound = mysqli_data_checkr($tbL100,'(*)',$constrain);

		?>
			<div class="block-element box-border-thick pads20 xsml-rounded-button noscroll">
				
				<?php

					if(isset($order_number) && !empty($order_number) && $isfound == true) {
		
						$new_order = escape_data($order_number);
						$receipt_number = idget_fdata($tbL100,$new_order,'order_number','receipt_number');

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
							$get_guest_name = idget_data($tbL169,$get_invoice_data[3],'fname').' ';
							$get_guest_name .= idget_data($tbL169,$get_invoice_data[3],'lname');
							$get_bill_name = idget_data($tbL58,$billto,'name');
							$wpay = 0;
						} elseif($get_invoice_data[9] == 5) {
							$get_guest_name = "";
							$get_bill_name = idget_data($tbL7,$billto,'staffname');
							$wpay = 4;
						}
						
						$reverseday = dayDiffs($server_get_date,$get_invoice_data[14]);

						?>
							<div class="block-element top-pull-10">
								
								<p class="top-pull-10 bottom-pull-10 right-pull-30 alignrt">
									
									<?php

										if($get_invoice_data[27] == 0) { if((isset($reverseday) && $reverseday <= $gh_get_allow_past_reverse) && (isset($allowReverse) && $allowReverse == 200)) { ?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logs=<?php echo $logs; ?>&token=<?php echo $order_number; ?>&reverse=<?php echo $order_number; ?>" class="blue-font left-push-5" title="Reverse Order"><b class="fa-share nobold right-push-5"></b> Cancel & Reverse</a><?php } } else { ?><b class="light-red-font nobold default-text-font-bold">Cancelled</b><?php }
									?>
								</p>

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
											<small class="block-element bottom-push-3">Status: <b class="red-font nobold"><?php echo $get_invoice_data[16]; ?></b><?php if($get_invoice_data[27] == 1) { ?> - <b class="light-red-font nobold default-text-font-bold">Cancelled</b><?php } ?></small>
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
																						<span class="ln-display-box float-left">
																							<h4 class="large">Discount</h4>
																						</span>
																						<span class="ln-display-box float-right">
																							<h4 class="large nobold"><?php echo number_format($get_invoice_data[4],2); ?></h4>
																						</span>
																						<span class="block-element new-line-space">	
																						</span>
																						
																						<?php

																							if(!empty($postype) && $postype == 'Establishment') {
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
										
										<small class="block-element bottom-push-15"><?php echo $get_invoice_data[20]; ?><a href="pos/preview_receipt.php?order=<?php echo $new_order; ?>&receipt=<?php echo $receipt_number; ?>" class="blue-font float-right">Print POS <b class="fa-print black-font nobold"></b></a></small>

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

					} else {
						echo '<br><h3 class="large nobold default-text-font-bold alignct">There are no related order information</h3><br>';
					}
				?>

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
	
	function doReverse(pg) {
		var askBefore = confirm('Are you sure you want to reverse?');
		if(askBefore == true) {
			var remark = prompt('Enter reason for reverse?','');
			if(remark) { window.location = filePath+'public/admin/workspace.php'+pg+'&remark='+remark; }
		}
	}

	/*window.onload = () => {
		
		document.addEventListener("keydown", function (event) {
		    if((event.ctrlKey || event.metaKey) && (event.key == "p" || event.charCode == 16 || event.charCode == 112 || event.keyCode == 80)) {
		        event.preventDefault();
		        event.cancelBubble = true;
		        event.stopImmediatePropagation();
		    }   
		});

	};*/

	
</script>