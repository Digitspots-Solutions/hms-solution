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

	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css" media="all" />
		<link rel="stylesheet" href="../../../style/custom.css" media="all" />
		<link rel="stylesheet" href="../admin/applystyle.css" media="all" />
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

	<?php

	//--------------------------------------------------------------------------------------------------------------------


	if((isset($_GET['order']) && isset($_GET['receipt'])) || (isset($ftoken) && !empty($ftoken)))
	{
		$new_order = !empty($_GET['order']) ? escape_data($_GET['order']) : $ftoken;
		$receipt_number = escape_data($_GET['receipt']);

		//get the name of the current pos in use
		if(isset($cur_pos_store_id) && !empty($cur_pos_store_id)) { $cur_pos_store_id = $cur_pos_store_id; }
		else { $cur_pos_store_id = idget_fdata($tbL100,'order_number',$new_order,'posid'); }
		$posname = idget_data($tbL14,$cur_pos_store_id,'posname');

		//update this receipt as printed
		$get_query = array("order_number"=>$new_order);
		$get_field = array("isprinted"=>1);
		mysqli_data_update($tbL100,$get_field,$get_query);

		
		//get new order preview
		$additionalQuery = " AND payment IN('Completed','Paid','Credit','Debit','Complimentary')";
		$order_selection_key = array("order_number"=>$new_order,"status"=>"Completed");
		$invoice_data_property = "customerid,billtype,ispaid,biller,roomid,bill_amount,receipt_number,media,cheque_number,paydetail,paydate,paytime,cashier,datelogged,timelogged,payment,waiter,tableid,cover,discount_amount,isreversed";
		$get_invoice_data = mysqli_data_fetch($tbL100,$invoice_data_property,$order_selection_key,'noarray');

		$additionalQuery = "";
		$order_selection_xkey_ = array("order_number"=>$new_order,"status"=>"Completed");
		$order_data_property = "itemid,qty,price,cover";
		$get_order_data = mysqli_data_fetch($tbL99,$order_data_property,$order_selection_xkey_,'array');

		//get receipt detail
		/*$additionalQuery = "";
		$receipt_selection_key = array("receipt_number"=>$receipt_number);
		$receipt_data_property = "amount,receipt_number,media,cheque_number,detail,cashier,datelogged,timelogged";
		$get_receipt_data = mysqli_data_fetch($tbL101,$receipt_data_property,$receipt_selection_key,'noarray');*/


		$billto = $get_invoice_data[3];
		$chargeroomid = $get_invoice_data[4];

		if($get_invoice_data[1] == 1) {
			$get_guest_name = idget_data($tbL169,$get_invoice_data[0],'fname').' ';
			$get_guest_name .= idget_data($tbL169,$get_invoice_data[0],'lname');
			$get_bill_name = $get_guest_name;
		} elseif($get_invoice_data[1] == 2) {
			$get_guest_name = idget_data($tbL102,$get_invoice_data[0],'fname').' ';
			$get_guest_name .= idget_data($tbL102,$get_invoice_data[0],'lname');
			$get_guest_bkt = idget_data($tbL102,$get_invoice_data[0],'booking_type');
			$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
			$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
			$r_suffix = idget_data($tbL56,$chargeroomid,'roomsuffix');
			$get_bill_name = strtoupper($get_guest_bkt).'<br>';
			$get_bill_name .= $get_guest_name.' ('.$r_prefix.$r_number.$r_suffix.')';
		} elseif($get_invoice_data[1] == 3) {
			$get_guest_name = idget_data($tbL169,$get_invoice_data[0],'fname').' ';
			$get_guest_name .= idget_data($tbL169,$get_invoice_data[0],'lname');
			$get_bill_name = idget_data($tbL33,$billto,'name');
			$get_bill_name .= '<br>';
			$get_bill_name .= $get_guest_name;
		} elseif($get_invoice_data[1] == 4) {
			$get_bill_name = idget_data($tbL58,$billto,'name');

			if(!empty($get_invoice_data[0])) {
				$get_guest_name = idget_data($tbL169,$get_invoice_data[0],'fname').' ';
				$get_guest_name .= idget_data($tbL169,$get_invoice_data[0],'lname');
			} else {
				$get_guest_name = "";
			}

			$get_bill_name .= '<br>';
			$get_bill_name .= $get_guest_name;

		} elseif($get_invoice_data[1] == 5) {
			$get_guest_name = "";
			$get_bill_name = idget_data($tbL7,$billto,'staffname');
		}
		
		$tablename = idget_data($tbL17,$get_invoice_data[17],'tablename');
		$tablename = str_replace('Table','',$tablename);

		if($get_invoice_data[15] == 'Paid') {
			$date_label = date("d/m/Y",strtotime($get_invoice_data[13])).' '.$get_invoice_data[14];
		} elseif($get_invoice_data[15] == 'Completed' || $get_invoice_data[15] == 'Credit') {
			$date_label = date("d/m/Y",strtotime($get_invoice_data[13])).' '.$get_invoice_data[14];
		} else {
			$date_label = date("d/m/Y",strtotime($get_invoice_data[13])).' '.$get_invoice_data[14];
		}

		?>
			
			<div class="cs-height-100"></div>

			<div class="block-element pads10 bottom-push-10 left-push-10">
				<span class="float-right"><input type="button" value="Print" onclick="window.print()"></span>
				<small>Note: Use tab-refresh icon to return to default screen</small>
			</div>

			<div id="section-to-print" class="block-element pads7">
				<div class="block-element cs-width-350">
					<span class="block-element bottom-push-5">
						<p class="alignct"><img src="<?php echo _FC_LOGO; ?>"></p>
						<h1 class="large nobold default-text-font-bold alignct nomargin"><?php echo $hotel_name; ?></h1>
						<h3 class="large nobold alignct"><?php echo $hotel_address.' '.$hotel_state; ?></h3>

						<h3 class="large nobold default-text-font-bold nomargin"><?php echo strtoupper($posname); ?></h3>
					</span>

					<span class="ln-display-box float-left">
						<small class="block-element default-text-font-bold bottom-push-3">Order No.</small>
						<small class="block-element default-text-font-bold bottom-push-15">#<?php echo $new_order; ?><?php if($get_invoice_data[20] == 1) { ?> - <b class="light-red-font nobold default-text-font-bold">Cancelled</b><?php } ?></small>
					</span>
					<span class="ln-display-box float-right">
						<small class="block-element default-text-font-bold bottom-push-3">Receipt No.</small>
						<small class="block-element default-text-font-bold bottom-push-15">#<?php echo $get_invoice_data[6]; ?></small>
					</span>
					<span class="block-element new-line-space">
					</span>
				
					<small class="block-element default-text-font-bold bottom-push-5">Date/time: <?php echo $date_label; ?></small>
					<?php if(!empty($get_invoice_data[17]) && $get_invoice_data[17] > 0) { ?><small class="block-element bottom-push-10 default-text-font-bold">Table #/Cover # <?php echo $tablename; ?>/<?php echo $get_invoice_data[18]; ?></small><?php } ?>

					<span class="block-element box-border-dark-thick-top box-border-dark-thick-bottom top-pull-5 bottom-pull-10 bottom-push-10">
						<table cellpadding="0" cellspacing="0" style="font-size: 14px !important; border: none !important; border-color: none !important;">
							<tr>
								<td align="left" class="box-noborder ft-tahoma add-bold">Item</td>
								<td align="left" class="box-noborder ft-tahoma add-bold">Qty</td>
								<td align="left" class="box-noborder ft-tahoma add-bold">Amount (&#8358;)</td>
							</tr>
							<?php
								if(is_array($get_order_data)) {
									
									$item_name = ""; $item_code = ""; $sub_amount = 0; $tax_amount = 0;
									$total_amount = 0;
									
									foreach ($get_order_data as $order_key => $order_value) {
										
										$item_name = idget_data($tbL16,$order_value['itemid'],'item');
										$item_code = idget_data($tbL16,$order_value['itemid'],'itemcode');

										$sub_amount = $order_value['price'] * $order_value['qty'];
										$total_amount = $total_amount + $sub_amount;
										//$tax_amount = ($cur_pos_tax_charge / 100) * $sub_amount;

										?>
											<tr>
												<td align="left" class="box-noborder cs-width-150 ft-tahoma add-bold"><?php echo $item_name;?></td>
												<td align="left" class="box-noborder right-pull-5 left-pull-5 ft-tahoma add-bold"><?php echo $order_value['qty']; ?></td>
												<td align="left" class="box-noborder ft-tahoma add-bold"><?php echo number_format($sub_amount,2); ?></td>
											</tr>
										<?php

										$item_name = ""; $item_code = ""; $sub_amount = "";
									}
								}
							?>
						</table>
					</span>

					<span class="block-element box-border-dark-thick-bottom bottom-pull-10 bottom-push-10">
						<?php
							if($get_invoice_data[15] == 'Paid') {
								?><small class="block-element default-text-font-bold bottom-push-3">Guest Name</small><?php 
							} elseif($get_invoice_data[15] == 'Completed') {
								if($get_invoice_data[1] == 2) { ?><small class="block-element default-text-font-bold bottom-push-3">Billed to room</small><?php  } elseif($get_invoice_data[1] == 5) { ?><small class="block-element default-text-font-bold bottom-push-3">Billed to staff</small><?php  }
							} elseif($get_invoice_data[15] == 'Complimentary') {
								?><small class="block-element default-text-font-bold bottom-push-3">Complimentary</small><?php 
							} elseif($get_invoice_data[15] == 'Credit') {
								?><small class="block-element default-text-font-bold bottom-push-3">Credit</small><?php 
							} elseif($get_invoice_data[15] == 'Debit') {
								?><small class="block-element default-text-font-bold bottom-push-3">Corporate/Spl Guest.</small><?php 
							}
						?>
						<h4 class="xlarge nobold ft-tahoma add-bold"><?php echo $get_bill_name; ?></h4>
					</span>

					<?php
						if($get_invoice_data[15] == 'Paid') {
							?>
								<span class="block-element bottom-push-10">
									<ul class="nolist">
										<li class="ln-display-box float-left">
											<small class="block-element default-text-font-bold bottom-push-3">Payment Mode</small>
											<h4 class="xlarge nobold ft-tahoma add-bold">
												<?php
											
													if(!empty($get_invoice_data[7]) && $get_invoice_data[7] > 0) {
														$paymode = idget_data($tbL24,$get_invoice_data[7],'name');
													} else {
														$paymode = "Nil";
													}

													echo $paymode;
												?>
											</h4>

											<br>

											<small class="block-element default-text-font-bold top-push-5 bottom-push-3">Remark</small>
											<h4 class="xlarge nobold ft-tahoma add-bold"><?php if(isset($get_invoice_data[9]) && !empty($get_invoice_data[9])) { echo $get_invoice_data[9]; } else { echo "Nil"; } ?></h4>
										</li>
										<li class="ln-display-box float-right">
											<small class="block-element default-text-font-bold bottom-push-3">Discount</small>
											<h4 class="xlarge nobold ft-tahoma add-bold">&#8358; <?php echo number_format($get_invoice_data[19],2); ?></h4><br>

											<small class="block-element default-text-font-bold top-push-5 bottom-push-3">Amount Paid</small>
											<h4 class="xlarge nobold ft-tahoma add-bold">&#8358; <?php echo number_format($get_invoice_data[5],2); ?></h4>
										</li>
										<li class="block-element new-line-space">
										</li>
									</ul>
								</span>
								<!--<small class="block-element dark-grey-font bottom-push-3">CC/Cheque Number</small>
								<small class="block-element bottom-push-15"><?php /*if(isset($get_invoice_data[8]) && !empty($get_invoice_data[8])) { echo $get_invoice_data[8]; } else { echo "Nil"; }*/ ?></small>-->
							<?php
						} elseif($get_invoice_data[15] == 'Completed' || $get_invoice_data[15] == 'Credit' || $get_invoice_data[15] == 'Debit') {
							?>
								<small class="block-element default-text-font-bold bottom-push-3">Bill Amount</small>
								<h4 class="xlarge nobold ft-tahoma add-bold bottom-pull-10">&#8358; <?php echo number_format($get_invoice_data[5],2); ?></h4>
								<small class="block-element default-text-font-bold bottom-push-3">Remark</small>
								<h4 class="xlarge nobold ft-tahoma add-bold"><?php if(isset($get_invoice_data[9]) && !empty($get_invoice_data[9])) { echo $get_invoice_data[9]; } else { echo "Nil"; } ?></h4>
							<?php
						} elseif($get_invoice_data[15] == 'Complimentary') {
							?>
								<small class="block-element default-text-font-bold bottom-push-3">Total Amount</small>
								<h4 class="xlarge nobold ft-tahoma add-bold bottom-pull-10">&#8358; <?php echo number_format($get_invoice_data[5],2); ?></h4>
								<small class="block-element default-text-font-bold bottom-push-3">Remark</small>
								<h4 class="xlarge nobold ft-tahoma add-bold"><?php if(isset($get_invoice_data[9]) && !empty($get_invoice_data[9])) { echo $get_invoice_data[9]; } else { echo "Nil"; } ?></h4>
							<?php
						}
					?>

					<span class="block-element box-border-dark-thick-top top-pull-15 bottom-pull-10 bottom-push-10">
						<ul class="nolist">
							<li class="ln-display-box float-left">
								<small class="block-element default-text-font-bold bottom-push-3">Cashier</small>
								<h4 class="xlarge nobold ft-tahoma add-bold"><?php if(isset($get_invoice_data[12]) && $get_invoice_data[12] >= 1) { $ccashier = idget_data($tbL7,$get_invoice_data[12],'staffname'); } else { $ccashier = "Unknown"; } echo $ccashier; ?></h4>
							</li>
							<li class="ln-display-box float-right">
								<small class="block-element default-text-font-bold bottom-push-3">Waiter</small>
								<h4 class="xlarge nobold ft-tahoma add-bold"><?php if(isset($get_invoice_data[16]) && $get_invoice_data[16] >= 1) { $cwaiter = idget_data($tbL7,$get_invoice_data[16],'staffname'); } else { $cwaiter = ""; } echo $cwaiter; ?></h4>
							</li>
							<li class="block-element new-line-space">
							</li>
						</ul>

						<br><br><br><br>
						<p>&nbsp;</p>
						<small class="block-element default-text-font-bold bottom-push-3">Guest Signature</small>
						<br><br>
					</span>
				</div>
			</div>

		<?php
	}

?>

<script>
	
	window.addEventListener('load',function() {
		window.print();

		document.addEventListener("keydown", function (event) {
		    if((event.ctrlKey || event.metaKey) && (event.key == "p" || event.charCode == 16 || event.charCode == 112 || event.keyCode == 80)) {
		        event.preventDefault();
		        event.cancelBubble = true;
		        event.stopImmediatePropagation();
		    }   
		});

	},false);

	
</script>
		