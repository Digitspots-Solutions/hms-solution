<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script type="text/javascript" src="../../js/all.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	
	<?php
		$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

		if(isset($_GET['booking'])) {
			
			if(isset($_POST['paywith']) && $_POST['paywith'] == 1)
			{
				if(isset($_POST['paysubmitbutton']) && (isset($_POST['amount-deposited']) && $_POST['amount-deposited'] > 1)) {

					$pay_dataproperty = array("booking_number"=>escape_data($_POST['bookingnumber']),"invoice_number"=>escape_data($_POST['invoicenumber']),"userid"=>$userSignedIn,"customerid"=>escape_data($_POST['customerid']),"booking_type"=>escape_data($_POST['bookingtype']),"amount"=>escape_data($_POST['amount-deposited']),"payment_mode"=>$_POST['payment-type'],"cheque_number"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					$is_inserted = mysqli_data_insert($tbL131,$pay_dataproperty,'');

					if(isset($is_inserted) && $is_inserted == 2) {
						$new_receipt_id = $mysqli_id;
						$receipt_number = $receipt_prefix.$new_receipt_id;

						$receipt_data_query = array("id"=>$new_receipt_id);
						$receipt_sub_dataproperty = array("receipt_number"=>$receipt_number);
						mysqli_data_update($tbL131,$receipt_sub_dataproperty,$receipt_data_query);

						//create a log file
						$log_message = "Recently initiated payment(".$_POST['amount-deposited'].") for booking settlement";
						$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

						?>
							<div class="block-element top-pull-10 bottom-pull-10 ft-xsml-size alignct add-bold">
								You have successfully logged payment for this invoice
							</div>
						<?php
					}
				}
			}
			elseif(isset($_POST['paywith']) && $_POST['paywith'] == 2)
			{
				if(isset($_POST['paysubmitbutton']) && (isset($_POST['couponbal']) && $_POST['couponbal'] > 0)) {
					if(isset($_POST['coupon-amount']) && $_POST['coupon-amount'] <= $_POST['couponbal']) {
						
						$pay_dataproperty = array("booking_number"=>escape_data($_POST['bookingnumber']),"invoice_number"=>escape_data($_POST['invoicenumber']),"userid"=>$userSignedIn,"customerid"=>escape_data($_POST['customerid']),"booking_type"=>escape_data($_POST['bookingtype']),"amount"=>escape_data($_POST['coupon-amount']),"payment_mode"=>8,"cheque_number"=>"","detail"=>escape_data($_POST['detail']),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$is_inserted = mysqli_data_insert($tbL131,$pay_dataproperty,'');

						if(isset($is_inserted) && $is_inserted == 2) {
							$new_receipt_id = $mysqli_id;
							$receipt_number = $receipt_prefix.$new_receipt_id;

							$receipt_data_query = array("id"=>$new_receipt_id);
							$receipt_sub_dataproperty = array("receipt_number"=>$receipt_number);
							mysqli_data_update($tbL131,$receipt_sub_dataproperty,$receipt_data_query);

							$new_coupon_balance = $_POST['couponbal'] - $_POST['coupon-amount'];
							
							if(isset($new_coupon_balance) && $new_coupon_balance > 0) { $new_status = "Unused"; }
							else { $new_status = "Used"; }

							$coupon_update_query = array("coupon_code"=>$_POST['coupon-code']);
							$coupon_sql = array("coupon_amount"=>$new_coupon_balance,"coupon_status"=>$new_status);
							mysqli_data_update($tbL129,$coupon_sql,$coupon_update_query);

							//create a log file
							$log_message = "Recently initiated coupon payment(".$_POST['amount-deposited'].") for booking settlement";
							$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

							?>
								<div class="block-element top-pull-10 bottom-pull-10 ft-xsml-size alignct add-bold">
									You have successfully logged payment for this invoice
								</div>
							<?php
						}
					}
				}
			}
			

			#-----------------------------------------------------------------------------------------------------------------------------------end of post
			

			$booking_number = escape_data($_GET['booking']);

			//get no of days allocated
			$noofdays_allocated = idget_fdata($tbL97,'booking_number',$booking_number,'noofdays');

			//get guest taxes status
			$is_vat = idget_fdata($tbL139,'booking_number',$booking_number,'vat');
			$is_servicecharge = idget_fdata($tbL139,'booking_number',$booking_number,'service_charge');
			$is_consumption_tax = idget_fdata($tbL139,'booking_number',$booking_number,'consumption_tax');

			//invoice number
			$invoice_number = idget_fdata($tbL130,'booking_number',$booking_number,'invoice_number');
			$booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
			$customer_id = idget_fdata($tbL130,'booking_number',$booking_number,'customerid');

			//get room stay status
			$room_stat_id = idget_fdata($tbL97,'booking_number',$booking_number,'stateid');
			$xcheckin_date = idget_fdata($tbL97,'booking_number',$booking_number,'startdate');
			$xcheckout_date = idget_fdata($tbL97,'booking_number',$booking_number,'endate');
			$room_stat_tag = idget_data($tbL38,$room_stat_id,'legendname');
			
			//get number of room
			$room_number_sql = "COUNT(roomid)";
			$room_number_query = "booking_number='".$booking_number."'";
			$get_numbr = mysqli_arithmetic_data($tbL96,$room_number_sql,$room_number_query);

			//get room occupancy charges
			$occupancy_room_sql = "SUM(amount)";
			$occupancy_room_query = "booking_number='".$booking_number."'";
			$occupancy_room_charges = mysqli_arithmetic_data($tbL140,$occupancy_room_sql,$occupancy_room_query);

			//get lodge details
			$guest_checkin_date = idget_fdata($tbL127,'booking_number',$booking_number,'checkin_date');
			$guest_checkin_time = idget_fdata($tbL127,'booking_number',$booking_number,'checkin_time');
			$guest_checkout_date = idget_fdata($tbL127,'booking_number',$booking_number,'checkout_date');
			$guest_checkout_time = idget_fdata($tbL127,'booking_number',$booking_number,'checkout_time');

			if(str_replace('-','',$guest_checkin_date) > 1) { $f_guest_checkin_date = write_dateF($gh_get_date_format,$guest_checkin_date); }
			else { $f_guest_checkin_date = "--:--"; }
			if(str_replace('-','',$guest_checkout_date) > 1) { $f_guest_checkout_date = write_dateF($gh_get_date_format,$guest_checkout_date); }
			else { $f_guest_checkout_date = "--:--"; }

			if(str_replace(':','',$guest_checkin_time) > 1) { $f_guest_checkin_time = write_timeF($gh_get_time_format,$guest_checkin_time); }
			else { $f_guest_checkin_time = ""; }
			if(str_replace(':','',$guest_checkout_time) > 1) { $f_guest_checkout_time = write_timeF($gh_get_time_format,$guest_checkout_time); }
			else { $f_guest_checkout_time = ""; }

			//get guest details
			$guest_query = array("booking_number"=>$booking_number,"primary_guest"=>1,"deletedata"=>0);
			$guest_dataproperty = "guest_code,salutation,name,mobile,emailaddress,remarks,address,id,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,datelogged,billto,billtype";
			$get_guest_detail = mysqli_data_fetch($tbL102,$guest_dataproperty,$guest_query,'noarray');
			$salutation = idget_data($tbL42,$get_guest_detail[1],'name');

			#to whom bill goes to
			if(isset($get_guest_detail[16]) && $get_guest_detail[16] >= 1) {
				
				$_to_complimentary = "no";

				if(isset($get_guest_detail[17]) && $get_guest_detail[17] == 3) {
					$_bill_on_account = " (Compl. ".idget_data($tbL33,$get_guest_detail[16],'name').")";
					$_to_bill = 1;
				} elseif(isset($get_guest_detail[17]) && $get_guest_detail[17] == 4) {
					$_bill_on_account = " (Corpo/Spl. ".idget_data($tbL58,$get_guest_detail[16],'name').")";
					$_to_bill = 1;
				} else {
					$_bill_on_account = "";
					$_to_bill = 0;
				}

			} else {
				$_to_complimentary = "yes";
				$_bill_on_account = "";
				$_to_bill = 0;
			}
			#end


			#get list of pos orders
			$pos_transaction_post = '';
			$guest_pos_dataproperty = "id,billto,billtype";
			$guest_pos_query = array("booking_number"=>$booking_number,"deletedata"=>0);
			$get_guest_pos_detail = mysqli_data_fetch($tbL102,$guest_pos_dataproperty,$guest_pos_query,'array');

			$gtotal_amount_pos_charges = 0;

			if(is_array($get_guest_pos_detail)) {
				foreach ($get_guest_pos_detail as $gpkey => $gpvalue) {
					if($gpvalue['billtype'] == 2 && $gpvalue['billto'] >= 1) {
						
						#get each room pos transactions
						$guest_pos_dataproperty_r = "id,order_number,posid,itemid,qty,price,datelogged";
						$guest_pos_query_r = array("customerid"=>$gpvalue['id'],"roomid"=>$gpvalue['billto'],"deletedata"=>0);
						$get_guest_pos_detail_r = mysqli_data_fetch($tbL99,$guest_pos_dataproperty_r,$guest_pos_query_r,'array');

						if(is_array($get_guest_pos_detail_r)) {
							
							$pos_logged_date = ""; $pos_name = ""; $this_guest_name = ""; $g_roomnumber = ""; $g_roomprefix = "";
							$g_room_type = ""; $g_room_type_name = ""; $g_block_id = ""; $g_block_name = "";
							$g_amount = ""; $print_g_amount = "";

							$total_amount_pos_charges = 0;
							
							foreach ($get_guest_pos_detail_r as $gpr_key => $gpr_value) {
								
								$pos_logged_date = write_dateF($gh_get_date_format,$gpr_value['datelogged']);
								$pos_name = idget_data($tbL14,$gpr_value['posid'],'posname');
								$this_guest_name = idget_data($tbL102,$gpvalue['id'],'name');

								$g_roomnumber = idget_data($tbL56,$gpvalue['billto'],'roomnumber');
								$g_roomprefix = idget_data($tbL56,$gpvalue['billto'],'roomprefix');
								$g_room_type = idget_data($tbL56,$gpvalue['billto'],'room_type_id');
								$g_block_id = idget_data($tbL56,$gpvalue['billto'],'blockid');
								$g_room_type_name = idget_data($tbL52,$gpvalue['billto'],'name');
								$g_block_name = idget_data($tbL49,$g_block_id,'name');

								$g_amount = $gpr_value['price'] * $gpr_value['qty'];
								$print_g_amount = write_amountF($gh_get_decimal_format,$g_amount);

								$total_amount_pos_charges = $total_amount_pos_charges + $g_amount;

								$pos_transaction_post .= '<tr>';
								$pos_transaction_post .= '<td width="150px" align="center"><input type="checkbox" name="paydate[]" value="'.$gpr_value['id'].'"> &nbsp; '.$pos_logged_date.'</td>';
								$pos_transaction_post .= '<td width="300px" align="center"><b>'.$pos_name.'</b> (<a href="pos_print.php?order='.$gpr_value['id'].'" class="blue-font">'.$gpr_value['order_number'].'</a> Order for ROOM '.$g_roomnumber.' - '.$this_guest_name.'. <b>'.$g_roomprefix.$g_roomnumber.' '.$g_block_name.'</b>)</td>';
								$pos_transaction_post .= '<td width="100px" align="center">'.$invoice_number.'</td>';
								$pos_transaction_post .= '<td width="100px" align="center"></td>';
								$pos_transaction_post .= '<td width="100px" align="center"></td>';
								$pos_transaction_post .= '<td width="100px" align="center">&#8358;'.$print_g_amount.'</td>';
								$pos_transaction_post .= '<td width="100px" align="center"></td>';
								$pos_transaction_post .= '</tr>';
							}
						}

						$gtotal_amount_pos_charges = $gtotal_amount_pos_charges + $total_amount_pos_charges;
					}
				}
			}


			//get estimated bill and payment
			$guest_bill_query = array("booking_number"=>$booking_number,"deletedata"=>0);
			$guest_bill_dataproperty = "sub_total,discount_amount,service_charge,tax_amount,consumption_tax_amount,other_charges,bill_amount";
			$get_guest_bill = mysqli_data_fetch($tbL134,$guest_bill_dataproperty,$guest_bill_query,'array');

			if(is_array($get_guest_bill)) {

				$sub_total = ""; $discount_amount = ""; $service_charge = "";
				$tax_amount = ""; $consumption_tax_amount = ""; $other_charges = ""; $noofcounts = 0;

				foreach ($get_guest_bill as $gbl_key => $gbl_value) {
					
					$noofcounts += 1;

					$sub_total = $sub_total + $gbl_value['sub_total'];
					$discount_amount = $discount_amount + $gbl_value['discount_amount'];

					//$service_charge = $service_charge + $gbl_value['service_charge'];
					//$tax_amount = $tax_amount + $gbl_value['tax_amount'];
					//$consumption_tax_amount = $consumption_tax_amount + $gbl_value['consumption_tax_amount'];

					if(isset($is_servicecharge) && $is_servicecharge == 1) { $service_charge = $service_charge + $gbl_value['service_charge']; }
					else { $service_charge = 0; }
					
					if(isset($is_vat) && $is_vat == 1) { $tax_amount = $tax_amount + $gbl_value['tax_amount']; }
					else { $tax_amount = 0; }

					if(isset($is_consumption_tax) && $is_consumption_tax == 1) { $consumption_tax_amount = $consumption_tax_amount + $gbl_value['consumption_tax_amount']; }
					else { $consumption_tax_amount = 0; }

					$other_charges = $other_charges + $gbl_value['other_charges'];
					$bill_amount = $bill_amount + $gbl_value['bill_amount'];
				}

			} else {
				$sub_total = 0; $discount_amount = 0; $service_charge = 0;
				$tax_amount = 0; $consumption_tax_amount = 0; $other_charges = 0;
			}


			//get charges from inclusion (as other charges)
			/*$guest_incl_bill_query = array("booking_number"=>$booking_number,"deletedata"=>0);
			$get_guest_incl_bill = mysqli_data_fetch($tbL135,'inclusion_id',$guest_incl_bill_query,'array');
			$incl_bill = 0; $incl_price = "";
			if(is_array($get_guest_incl_bill)) {
				foreach ($get_guest_incl_bill as $ggib_key => $ggib_value) {
					$incl_price = idget_data($tbL83,$ggib_value['inclusion_id'],'price');
					$incl_bill = $incl_bill + $incl_price;
				}
			} else {
				$incl_bill = 0;
			}*/

			include "frontdesk/guest_inclusions.php";

			if(isset($noofcounts) && $noofcounts >= 1) {
				$other_charges = $other_charges + $actual_incl_bill + $gtotal_amount_pos_charges + ($occupancy_room_charges * $noofcounts);
			} else {
				$other_charges = $other_charges + $actual_incl_bill + $gtotal_amount_pos_charges;
			}

			$room_charges = $sub_total + $discount_amount;

			$print_room_charges = write_amountF($gh_get_decimal_format,$room_charges);
			$print_discount = write_amountF($gh_get_decimal_format,$discount_amount);
			$print_service_charge = write_amountF($gh_get_decimal_format,$service_charge);
			$print_tax_amount = write_amountF($gh_get_decimal_format,$tax_amount);
			$print_consumption_tax_amount = write_amountF($gh_get_decimal_format,$consumption_tax_amount);
			$print_other_charges = write_amountF($gh_get_decimal_format,$other_charges);

			$total_tax = $service_charge + $tax_amount + $consumption_tax_amount;
			$grand_total = $sub_total + $service_charge + $tax_amount + $consumption_tax_amount + $other_charges;
			
			$print_total_tax_amount = write_amountF($gh_get_decimal_format,$total_tax);
			$print_grand_total = write_amountF($gh_get_decimal_format,$grand_total);

			$refunds = 0;
			$print_refunds = write_amountF($gh_get_decimal_format,$refunds);

			#---------------------------------------------------------------------------------

			$es_sub_total = idget_fdata($tbL130,'booking_number',$booking_number,'sub_total');
			$es_discount_amount = idget_fdata($tbL130,'booking_number',$booking_number,'discount_amount');

			if(isset($is_servicecharge) && $is_servicecharge == 1) { $es_service_charge = idget_fdata($tbL130,'booking_number',$booking_number,'service_charge'); } else { $es_service_charge = 0; }
			
			if(isset($is_vat) && $is_vat == 1) { $es_tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'tax_amount'); }
			else { $es_tax_amount = 0; }

			if(isset($is_consumption_tax) && $is_consumption_tax == 1) { $es_consumption_tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'consumption_tax_amount'); }
			else { $es_consumption_tax_amount = 0; }
			
			$es_other_charges = idget_fdata($tbL130,'booking_number',$booking_number,'other_charges');
			$es_other_charges = $es_other_charges + $actual_incl_bill + ($occupancy_room_charges * $noofdays_allocated);
			$es_bill_amount = idget_fdata($tbL130,'booking_number',$booking_number,'bill_amount');

			$es_total_tax = $es_service_charge + $es_tax_amount + $es_consumption_tax_amount;
			$print_es_total_tax_amount = write_amountF($gh_get_decimal_format,$es_total_tax);
			$es_room_charges = $es_sub_total + $es_discount_amount;
			$es_grand_total = $es_room_charges + $es_service_charge + $es_tax_amount + $es_consumption_tax_amount + $es_other_charges;

			$print_es_room_charges = write_amountF($gh_get_decimal_format,$es_room_charges);
			$print_es_discount = write_amountF($gh_get_decimal_format,$es_discount_amount);
			$print_es_service_charge = write_amountF($gh_get_decimal_format,$es_service_charge);
			$print_es_tax_amount = write_amountF($gh_get_decimal_format,$es_tax_amount);
			$print_es_consumption_tax_amount = write_amountF($gh_get_decimal_format,$es_consumption_tax_amount);
			$print_es_other_charges = write_amountF($gh_get_decimal_format,$es_other_charges);
			$print_es_grand_total = write_amountF($gh_get_decimal_format,$es_grand_total);

			#---------------------------------------------------------------------------------

			//get total amount paid so far
			$amount_sql = "SUM(amount)";
			$amount_query = "booking_number='".$booking_number."'";
			$amount_paid = mysqli_arithmetic_data($tbL131,$amount_sql,$amount_query);
			$print_amount_paid = write_amountF($gh_get_decimal_format,$amount_paid);

			#---------------------------------------------------------------------------------

			if((isset($amount_paid) && isset($es_grand_total)) && ($es_grand_total > 0 && $amount_paid >= $es_grand_total)) { $es_balance = $amount_paid - $es_grand_total; $es_c_pay = 0; } elseif(isset($es_grand_total) && ($es_grand_total > 0 && $es_grand_total >= $amount_paid)) { $es_balance = $es_grand_total - $amount_paid; $es_c_pay = 1; } else { $es_balance = 0; $es_c_pay = 0; }
	
			$es_r_balance = $refunds + $es_balance;

			#---------------------------------------------------------------------------------

			if((isset($amount_paid) && isset($grand_total)) && ($grand_total > 0 && $amount_paid >= $grand_total)) { $balance = $amount_paid - $grand_total; 
				$c_pay = 0; } elseif(isset($grand_total) && ($grand_total > 0 && $grand_total >= $amount_paid)) { $balance = $grand_total - $amount_paid; $c_pay = 1; } else { $balance = 0; $c_pay = 0; }

			$r_balance = $refunds + $balance;

			#---------------------------------------------------------------------------------

			#check if there is any log rebate for check and balance
			$rebate_query = array("booking_number"=>$booking_number,"status"=>1);
			$get_rebate_data = mysqli_data_fetch($tbL138,'balance_amount',$rebate_query,'noarray');

			if(isset($get_rebate_data[0]) && $get_rebate_data[0] >= $r_balance) {
				$actual_balance = $get_rebate_data[0] - $r_balance;
				$es_actual_balance = $get_rebate_data[0] - $es_r_balance;
			} elseif(isset($get_rebate_data[0]) && $r_balance >= $get_rebate_data[0]) {
				$actual_balance = $r_balance - $get_rebate_data[0];
				$es_actual_balance = $es_r_balance - $get_rebate_data[0];
			} else {
				$actual_balance = $r_balance;
				$es_actual_balance = $es_r_balance;
			}

			if((isset($actual_balance) && $actual_balance > 0) || (isset($es_actual_balance) && $es_actual_balance > 0)) {
				if($c_pay == 0 || $es_c_pay == 0) {
					$label = "<a href='coupon.php?booking=".$booking_number."&invoice=".$invoice_number."&customerid=".$customer_id."&bal=".$balance."' class='blue-font'>Rebate</a> (to customer)";
				} elseif($c_pay == 1 || $es_c_pay == 1) {
					$label = "Debts (to be paid)";
				}
			} else {
				$label = "";
			}
			
			
			$print_balance = write_amountF($gh_get_decimal_format,$actual_balance);
			$print_es_balance = write_amountF($gh_get_decimal_format,$es_actual_balance);

			#---------------------------------------------------------------------------------
			
			?>
				<div id="section-to-print" class="block-element">
					<h3 class="large">Invoice & Payment</h3><br>
					<div class="block-element">
						<span class="ln-display-box float-left">
							<div class="cs-width-300 pads10 dark-black-font alignct ft-sml-size anchor">
								Booking No: &nbsp; <b><?php echo $booking_number; ?></b>
							</div>
						</span>
						<span class="ln-display-box float-left">
							<div class="cs-width-200 pads10 red-theme white-font alignct">
								<h4 class="large nobold nomargin"><?php echo $room_stat_tag; ?></h4>
							</div>
						</span>
						<span class="ln-display-box float-right top-pull-10 right-pull-20">
							<small>
								<b>Check In</b>: <?php echo $f_guest_checkin_date.' '.$f_guest_checkin_time; ?> &nbsp;&nbsp; 
								<b>Check Out</b>: <?php echo $f_guest_checkout_date.' '.$f_guest_checkout_time; ?>
							</small>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="block-element box-border-thick pads20">
						<h4 class="large">&bull; Booking Summary Invoice</h4>
						<table cellpadding="0" cellspacing="0" class="ft-xxsml-size top-push-5">
							<tr>
								<th width="200px" align="center">Guest Name</th>
								<th width="100px" align="center">Invoice #</th>
								<th width="100px" align="center">Amount</th>
								<th width="100px" align="center">Discount</th>
								<th width="100px" align="center">Tax</th>
								<th width="150px" align="center">Other Charges</th>
								<th width="100px" align="center">Paid</th>
								<th width="100px" align="center">Refunds</th>
								<th width="100px" align="center">Balance</th>
							</tr>
							<tr>
								<td width="200px" align="center"><?php echo $get_guest_detail[2].$_bill_on_account; ?></td>
								<td width="100px" align="center"><?php echo $invoice_number; ?></td>
								<td width="100px" align="center"><?php echo $print_es_room_charges; ?></td>
								<td width="100px" align="center"><?php echo $print_es_discount; ?></td>
								<td width="100px" align="center"><?php echo $print_es_total_tax_amount; ?></td>
								<td width="150px" align="center"><?php echo $print_es_other_charges; ?></td>
								<td width="100px" align="center"><?php echo $print_amount_paid; ?></td>
								<td width="100px" align="center"><?php echo $print_refunds; ?></td>
								<td width="100px" align="center"><?php echo $print_es_balance; ?></td>
							</tr>
						</table>
						
						<br><br>

						<h4 class="large">&bull; Account Statement</h4>
						<form action="" method="post" autocomplete="off">
							<table cellpadding="0" cellspacing="0" class="ft-xxsml-size top-push-5">
								<tr>
									<th width="150px" align="center">Date</th>
									<th width="300px" align="center">Description</th>
									<th width="100px" align="center">Invoice #</th>
									<th width="100px" align="center">Discount</th>
									<th width="100px" align="center">Bill Posts</th>
									<th width="100px" align="center">Amount</th>
									<th width="100px" align="center">Payment</th>
								</tr>

								<?php
									
									$guest_query_2 = array("booking_number"=>$booking_number,"deletedata"=>0);
									$get_guest_detail_2 = mysqli_data_fetch($tbL102,'name,mobile,id',$guest_query_2,'array');
									
									if(is_array($get_guest_detail_2)) {
										$g2=""; $guest_array=array();
										foreach ($get_guest_detail_2 as $ggkey => $ggvalue) {
											$g2 = $ggvalue['name'].'/'.$ggvalue['mobile'].'/'.$ggvalue['id'];
											array_push($guest_array,$g2);
										}
									}

									//get occupancy details
									$occupancy_query = array("booking_number"=>$booking_number,"deletedata"=>0);
									$occupancy_dataproperty = "id,roomid,checkin,checkout,status,remarks,datelogged";
									$get_occupancy_data = mysqli_data_fetch($tbL127,$occupancy_dataproperty,$occupancy_query,'array');

									if(is_array($get_occupancy_data)) {

										$gog = 0; $checker_id=0;

										$room_type_id=""; $room_type=""; $block_id=""; $block_name=""; $room_prefix=""; $room_number="";
										$gcheckin=""; $gcheckout=""; $gu_name="";

										$r_amount_sql=""; $r_amount_query=""; $r_total_amount=""; $print_room_amount=""; $grand_r_amount=0;
										$grand_discount_amount=0; $grand_billposts_amount=0;

										foreach ($get_occupancy_data as $gokey => $govalue) {
											
											
											$room_type_id = idget_fdata($tbL56,'id',$govalue['roomid'],'room_type_id');
											
											$room_type = idget_data($tbL52,$room_type_id,'name');
											$room_prefix = idget_data($tbL56,$govalue['roomid'],'roomprefix');
											$room_number = idget_data($tbL56,$govalue['roomid'],'roomnumber');
											
											$gcheckin = write_dateF($gh_get_date_format,$govalue['checkin']);
											$gcheckout = write_dateF($gh_get_date_format,$govalue['checkout']);
											
											$this_guest = explode('/', $guest_array[$gog]);
											$gu_name = $this_guest[0];

											$checker_id += 1;

											//get total amount charged per room so far
											$r_amount_sql = "SUM(sub_total)";
											$r_amount_query = "booking_number='".$booking_number."' AND roomid='".$govalue['roomid']."'";
											$r_total_amount = mysqli_arithmetic_data($tbL134,$r_amount_sql,$r_amount_query);
											$print_room_amount = write_amountF($gh_get_decimal_format,$r_total_amount);

											$grand_r_amount = $grand_r_amount + $r_total_amount;

											//if(isset($r_total_amount) && $r_total_amount > 0) {
												
												?>
													<tr class="grey-theme">
														<!--<td width="150px" align="center"></td>-->
														<td colspan="2" align="center">Guest: <b><?php echo $gu_name.$_bill_on_account; ?></b> &mdash; <?php echo $room_type; ?>(<?php echo $room_number; ?>)<small class="block-element top-push-3"><?php echo $room_prefix.$room_number; ?> - Arrival: <?php echo $gcheckin; ?> to Departure: <?php echo $gcheckout; ?></small></td>
														<td width="100px" align="center"></td>
														<td width="100px" align="center"></td>
														<td width="100px" align="center"></td>
														<td width="100px" align="center" class="white-theme"><?php echo $print_room_amount; ?></td>
														<td width="100px" align="center" class="white-theme"></td>
													</tr>
												<?php

												#get room daily charges listed
												$r_daily_c_query = array("booking_number"=>$booking_number,"roomid"=>$govalue['roomid'],"deletedata"=>0);
												$get_r_data = mysqli_data_fetch($tbL134,'invoice_number,discount_amount,sub_total,datelogged',$r_daily_c_query,'array');

												if(is_array($get_r_data)) {
													
													$logged_date=""; $room_discount=0; $billposts=0; $total_discount=0; $total_billposts=0;
													
													foreach ($get_r_data as $r_key => $r_value) {
														
														$logged_date = write_dateF($gh_get_date_format,$r_value['datelogged']);
														$room_discount = write_amountF($gh_get_decimal_format,$r_value['discount_amount']);
														$billposts = write_amountF($gh_get_decimal_format,$r_value['sub_total']);

														$total_discount = $total_discount + $r_value['discount_amount'];
														$total_billposts = $total_billposts + $r_value['sub_total'];

														?>
															<tr>
																<td width="150px" align="center"><input type="checkbox" name="logdate[]" value="<?php echo $booking_number.'/'.$govalue['roomid'].'/'.$r_value['id']; ?>"> &nbsp; <?php echo $logged_date; ?></td>
																<td width="300px" align="center">Room Charge (+ Inclusions)</td>
																<td width="100px" align="center"><?php echo $r_value['invoice_number']; ?></td>
																<td width="100px" align="center">&#8358;<?php echo $room_discount; ?></td>
																<td width="100px" align="center">&#8358;<?php echo $billposts; ?></td>
																<td width="100px" align="center"></td>
																<td width="100px" align="center"></td>
															</tr>
														<?php
													}

													$grand_discount_amount = $grand_discount_amount + $total_discount;
													$grand_billposts_amount = $grand_billposts_amount + $total_billposts;
												}
											//}

											$gog += 1;
										}
									}


									#get list of payment
									$payment_query = array("booking_number"=>$booking_number,"deletedata"=>0);
									$get_payment_data = mysqli_data_fetch($tbL131,'id,invoice_number,receipt_number,amount,payment_mode,detail,datelogged',$payment_query,'array');

								?>

								<tr>
									<td colspan="8" class="nobordercolor">&nbsp;</td>
								</tr>
								<tr class="grey-theme">
									<td colspan="8" class="nobordercolor left-pull-20"><b>Payment Details</b></td>
								</tr>

								<?php

									$total_payment = 0;

									if(is_array($get_payment_data)) {
										
										$pay_logged_date=""; $payment_mode=""; $sr_amount_paid="";

										foreach ($get_payment_data as $pay_key => $pay_value) {
											
											$pay_logged_date = write_dateF($gh_get_date_format,$pay_value['datelogged']);
											$payment_mode = idget_data($tbL24,$pay_value['payment_mode'],'name');
											$sr_amount_paid = write_amountF($gh_get_decimal_format,$pay_value['amount']);

											$total_payment = $total_payment + $pay_value['amount'];

											?>
												<tr>
													<td width="150px" align="center"><input type="checkbox" name="paydate[]" value="<?php echo $pay_value['id']; ?>"> &nbsp; <?php echo $pay_logged_date; ?></td>
													<td width="300px" align="center"><?php echo $pay_value['detail'].' <b>'.$payment_mode.'</b> '.$pay_value['receipt_number']; ?> <a href="print_lodging_payment_receipt.php?receipt=<?php echo $pay_value['id']; ?>" class="black-font"><b class="fa-print nobold"></b></a></td>
													<td width="100px" align="center"><?php echo $pay_value['invoice_number']; ?></td>
													<td width="100px" align="center"></td>
													<td width="100px" align="center"></td>
													<td width="100px" align="center"></td>
													<td width="100px" align="center">&#8358;<?php echo $sr_amount_paid; ?></td>
												</tr>
											<?php
										}
									}

								?>

								<tr>
									<td colspan="8" class="nobordercolor">&nbsp;</td>
								</tr>
								<tr class="grey-theme">
									<td colspan="8" class="nobordercolor left-pull-20"><b>Other Charges</b></td>
								</tr>

								<?php

									echo $pos_transaction_post;

									$grand_r_amount = $grand_r_amount + $gtotal_amount_pos_charges;
									
									$print_total_payment = write_amountF($gh_get_decimal_format,$total_payment);
									$print_total_discount = write_amountF($gh_get_decimal_format,$grand_discount_amount);
									$print_total_billposts = write_amountF($gh_get_decimal_format,$grand_billposts_amount);
									$print_grand_r_amount = write_amountF($gh_get_decimal_format,$grand_r_amount);

									$au_checkout_date = write_dateF($gh_get_date_format,$server_get_date);

									$payment_mode = select_dt_fetch('deletedata',0,$tbL24,'id','name');
								?>

								<tr>
									<td align="center" colspan="3"><b>Total:</b></td>
									<td width="100px" align="center">&#8358;<b><?php echo $print_total_discount; ?></b></td>
									<td width="100px" align="center">&#8358;<b><?php echo $print_total_billposts; ?></b></td>
									<td width="100px" align="center">&#8358;<b><?php echo $print_grand_r_amount; ?></b></td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_total_payment; ?></b></td>
								</tr>

								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Charges for period: <?php if($f_guest_checkin_date != '--:--') { echo $f_guest_checkin_date; } ?> - <?php if($f_guest_checkout_date != '--:--') { echo $f_guest_checkout_date; } else { echo $au_checkout_date; } ?></td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_grand_r_amount; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Total Discount</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_discount; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Consumption Tax</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_consumption_tax_amount; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Service Charge</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_service_charge; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">VAT</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_tax_amount; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Total Amount</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_grand_total; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3">Total Paid</td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_total_payment; ?></b></td>
								</tr>
								<tr>
									<td align="center" colspan="3"></td>
									<td align="right" colspan="3" class="red-font"><?php echo $label; ?></td>
									<td width="100px" align="right">&#8358;<b><?php echo $print_balance; ?></b></td>
								</tr>
							</table>
							<div class="block-element pads20 top-push-20 ft-xsml-size">
								<span class="ln-display-box float-left">
									<b>Date:</b>
								</span>
								<span class="ln-display-box float-right">
									<b>Guest Signature</b>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							
							<?php

								if((isset($c_pay) && $c_pay == 1) || (isset($es_c_pay) && $es_c_pay == 1)) {
									
									$get_coupon = array("coupon_status"=>"Unused","deletedata"=>0);
									$get_coupon_data = mysqli_data_fetch($tbL129,'coupon_code',$get_coupon,'array');

									?>
										<div class="block-element top-push-50">
											<input type="submit" name="invoicebutton" value="Route to invoice" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor">
										</div>
										<div class="block-element grey-1-theme pads20 top-push-10 ft-xsml-size">
											<fieldset>
												<legend><a href="javascript:void(0)" id="p1" class="dark-black-font" onclick="selPay(1)">Payments</a> or <a href="javascript:void(0)" id="p2" class="blue-font" onclick="selPay(2)">Coupon</a></legend>
												<div id="coupon-payment" class="pads10 noshow">
													<table cellpadding="0" cellspacing="0">
														<tr>
															<td width="150px" align="center">Coupon Code</td>
															<td width="150px" align="center">Amount Paying</td>
															<td width="200px" align="center">Detail</td>
															<td width="200px" align="center"></td>
														</tr>
														<tr>
															<td width="150px">
																<input list="coupon-codes" name="coupon-code" id="coupon-code" onblur="getdata('coupon-balance','eget-coupon-balance','coupon-code','nodropbox')">
																<datalist id="coupon-codes">
																	<?php
																		if(is_array($get_coupon_data)) {
																			foreach($get_coupon_data as $cpkey => $cpvalue) {
																				?>
																					<option value="<?php echo $cpvalue['coupon_code']; ?>">
																				<?php
																			}
																		}
																	?>
																</datalist>
															</td>
															<td width="150px">
																<input type="text" name="coupon-amount" id="coupon-amount" pattern="\d*">
															</td>
															<td width="200px">
																<input type="text" name="detail" id="detail">
															</td>
															<td width="200px">
																<div id="coupon-balance" class="ft-sml-size left-pull-30"></div>
															</td>
														</tr>
													</table>
												</div>
												<div id="vast-payment" class="pads10">
													<table cellpadding="0" cellspacing="0">
														<tr>
															<td width="70px" align="center">Mode</td>
															<td width="60px" align="center">Amount</td>
															<td width="70px" align="center">Cheq. No</td>
															<!--<td width="70px" align="center">Receipt</td>-->
															<td width="90px" align="center">Detail</td>
														</tr>
														<tr>
															<td width="80px">
																<select name="payment-type" id="payment-type">
																	<?php echo $payment_mode; ?>
																</select>
															</td>
															<td width="60px">
																<input type="text" name="amount-deposited" id="amount-deposited" pattern="\d*">
															</td>
															<td width="70px">
																<input type="text" name="cheque-number" id="cheque-number">
															</td>
															<!--<td width="70px">
																<input type="text" name="receipt" id="receipt">
															</td>-->
															<td width="90px">
																<input type="text" name="detail" id="detail">
															</td>
														</tr>
													</table>
												</div>
											</fieldset>
										</div>
										<div class="pads10 alignct">
											<input type="hidden" name="paywith" id="paywith" value="1">
											<input type="hidden" name="bookingnumber" value="<?php echo $booking_number; ?>">
											<input type="hidden" name="invoicenumber" value="<?php echo $invoice_number; ?>">
											<input type="hidden" name="bookingtype" value="<?php echo $booking_type; ?>">
											<input type="hidden" name="customerid" value="<?php echo $customer_id; ?>">
											<input type="submit" name="paysubmitbutton" value="Settle Booking" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor"> <input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor" onclick="window.print()">
										</div>
									<?php
								} else {
									?>
										<div class="pads30 alignct">
											<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor" onclick="window.print()">
										</div>
									<?php
								}

							?>
						</form>
					</div>
				</div>
			<?php
		}
	?>

</div>


<script>
	
	function selPay(pay) {
		htmlpassval(pay,'paywith');
		if(pay == 1) {
			objDisplay('vast-payment');
			objHidden('coupon-payment');
			chgclass('p1','dark-black-font');
			chgclass('p2','blue-font');
		} else if(pay == 2) {
			objDisplay('coupon-payment');
			objHidden('vast-payment');
			chgclass('p1','blue-font');
			chgclass('p2','dark-black-font');
		}
	}

</script>