<?php $smdl = "frontdesk"; $logs = escape_data($_GET['logs']); $blocks = select_dt_fetch('',0,$tbL49,'id','name'); ?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the list of cancellation reports
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Cancellation Reservation Report</h3>
	<form action="" method="post">
		<div class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Select Date</small>
			<select name="fieldset1" id="fieldset1" onchange="dateStat()">
				<option value="today" selected="selected">Today</option>
				<option value="yesterday">Yesterday</option>
				<option value="custom">Custom</option>
			</select>
		</div>
		<div id="for-custom-date" class="noshow">
			<span class="ln-display-box float-left nc-width-50 right-pull-5">
				<small class="block-element bottom-push-3 left-pull-3">Date From</small>
				<input type="date" name="fieldset2" id="fieldset2" value="<?php echo $server_get_date; ?>">
			</span>
			<span class="ln-display-box float-left nc-width-50 right-pull-5">
				<small class="block-element bottom-push-3 left-pull-3">Date To</small>
				<input type="date" name="fieldset3" id="fieldset3" value="<?php echo $server_get_date; ?>">
			</span>
		</div>
		<div class="ln-display-box float-left nc-width-20 right-pull-5 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
		</div>
		<div class="block-element new-line-space">
			<!-- clear line -->
		</div>
	</form>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton']))
	{
		if(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'today') {
			$mysqli_date_from = $server_get_date;
			$mysqli_date_to = $server_get_date;
		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'yesterday') {
			$mysqli_date_from = date('Y-m-d',strtotime('-1 day'));
			$mysqli_date_to = date('Y-m-d',strtotime('-1 day'));
		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'custom') {
			$mysqli_date_from = $_POST['fieldset2'];
			$mysqli_date_to = $_POST['fieldset3'];
		}

		$date_from = write_dateF($gh_get_date_format,$mysqli_date_from);
		$date_to = write_dateF($gh_get_date_format,$mysqli_date_to);
		
		?>
			<p class="bottom-pull-20">
				<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
			</p>

			<div id="section-to-print" class="block-element">
				<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
				<small class="block-element alignct">Cancellation Details Report Between <?php echo $date_from.' And '.$date_to; ?></small>
				<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

				<?php
					
					#start report selection
					$additionalQuery = " AND datelogged BETWEEN '".$mysqli_date_from."' AND '".$mysqli_date_to."'";
					$ccl_query = array("status"=>"Cancelled","deletedata"=>0);

					$guest_dataproperty = "booking_number,roomid,booking_type,checkin_date,checkout_date,cancel_date,cancel_reason,status";
					$get_guest_data = mysqli_data_fetch($tbL127,$guest_dataproperty,$ccl_query,'array');

					?>
						<div class="block-element top-push-20">
							<div class="block-element top-push-5 box-border-thick">
								<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
									<tr>
										<th width="150px" align="center">Booking Number</th>
										<th width="150px" align="center">Guest</th>
										<th width="100px" align="center">Room</th>
										<th width="100px" align="center">Check In</th>
										<th width="100px" align="center">Check Out</th>
										<th width="150px" align="center">Cancelled Date</th>
										<th width="170px" align="center">Cancelled Reason</th>
										<th width="150px" align="center">Booking Amount</th>
										<th width="70px" align="center">Tax</th>
										<th width="100px" align="center">Commission</th>
										<th width="80px" align="center">Discount</th>
										<th width="150px" align="center">Cancel Fare</th>
									</tr>

									<?php

										if(is_array($get_guest_data)) {
											$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $room_type = "";
											$room_name = ""; $bkt = ""; $checkin_date = ""; $checkin_time = ""; $cancel_date = ""; $reason_for_cancel = "";
											$guest_bill_to = ""; $noofdays = ""; $unitprice = ""; $totalprice = ""; $discount = ""; $value_added_tax = "";
											$booking_amount = ""; $tax = ""; $disc = ""; $commission = ""; $cancel_fare = ""; $commission_f = "";
											$cancel_fare_f = "";


											foreach ($get_guest_data as $scd_key => $scd_value) {
												
												$biller = $scd_value['booking_type'];
												$stay_f = write_dateF($gh_get_date_format,$scd_value['checkin_date']);
												$stay_t = write_dateF($gh_get_date_format,$scd_value['checkout_date']);
												$cancel_date = write_dateF($gh_get_date_format,$scd_value['cancel_date']);
												$reason_for_cancel = idget_data($tbL32,$scd_value['cancel_reason'],'name');

												$roomtype = idget_data($tbL56,$scd_value['roomid'],'room_type_id');
												$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
												$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

												if(isset($t_bkt) && $t_bkt >= 1) { $bkt = arrayget_key($booking_type,$t_bkt); }
												else { $bkt = "All"; }

												#get guest details
												$additionalQuery = "";
												$checker = array("booking_number"=>$scd_value['booking_number'],"roomid"=>$scd_value['roomid']);
												$get_checker_data = mysqli_data_fetch($tbL96,'customerid,noofdays',$checker,'noarray');

												$salutation = idget_data($tbL102,$get_checker_data[0],'salutation');
												$billto = idget_data($tbL102,$get_checker_data[0],'billto');

												$customer_name = idget_data($tbL42,$salutation,'name').' ';
												$customer_name .= idget_data($tbL102,$get_checker_data[0],'name');

												if(isset($billto) && $billto >= 1) { $customer_name .= " (".idget_data($tbL58,$billto,'name').")"; $guest_bill_to = $billto; } else { $guest_bill_to = 0; }



												#booking rate and charges

												$noofdays = $get_checker_data[1];

												if(isset($biller) && $biller == 2) {
													$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0); $corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
													
													if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
														if($corporate_tariff_settings[1] == 'Amount') {
															$unitprice = $corporate_tariff_settings[2];
															$totalprice = ($unitprice * 1) * $noofdays;
															$discount = 0;

															$value_added_tax = ($gh_get_vat / 100) * $totalprice;

														} elseif($corporate_tariff_settings[1] == 'Percentage') {
															$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
															$unitprice = $defaultAmount - $inPercent;
															$totalprice = ($unitprice * 1) * $noofdays;
															$discount = $inPercent;

															$value_added_tax = ($gh_get_vat / 100) * $totalprice;
														}
													} else {
														$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
														$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
														$unitprice = $defaultAmount - $inPercent;
														$totalprice = ($unitprice * 1) * $noofdays;
														$discount = $inPercent;

														$value_added_tax = ($gh_get_vat / 100) * $totalprice;
													}

												} else {
													$unitprice = idget_data($tbL52,$roomtype,'defaultprice');
													$totalprice = $unitprice * $noofdays;
													$discount = 0;

													$value_added_tax = ($gh_get_vat / 100) * $totalprice;
												}

												$booking_amount = write_amountF($gh_get_decimal_format,$totalprice);
												$tax = write_amountF($gh_get_decimal_format,$value_added_tax);
												$disc = write_amountF($gh_get_decimal_format,$discount);
												
												$commission = $totalprice + $value_added_tax;
												$commission_f = write_amountF($gh_get_decimal_format,$commission);

												//get cancellation fare
												$amount_sql = "SUM(bill_amount)";
												$amount_query = "booking_number='".$scd_value['booking_number']."' AND roomid=".$scd_value['roomid'];
												$cancel_fare = mysqli_arithmetic_data($tbL134,$amount_sql,$amount_query);
												$cancel_fare_f = write_amountF($gh_get_decimal_format,$cancel_fare);

												?>
													<tr>
														<td width="150px" align="center"><?php echo $scd_value['booking_number']; ?></td>
														<td width="150px" align="center"><?php echo $customer_name; ?></td>
														<td width="100px" align="center"><?php echo $room_name; ?></td>
														<td width="100px" align="center"><?php echo $stay_f; ?></td>
														<td width="100px" align="center"><?php echo $stay_t; ?></td>
														<td width="150px" align="center"><?php echo $cancel_date; ?></td>
														<td width="170px" align="center"><?php echo $reason_for_cancel; ?></td>
														<td width="150px" align="center"><?php echo $booking_amount; ?></td>
														<td width="70px" align="center"><?php echo $tax; ?></td>
														<td width="100px" align="center"><?php echo $commission_f; ?></td>
														<td width="80px" align="center"><?php echo $disc; ?></td>
														<td width="150px" align="center"><?php echo $cancel_fare_f; ?></td>
													</tr>
												<?php
											}
										}

									?>

								</table>
							</div>
						</div>
					<?php
					
				?>
			</div>
		<?php
	}

?>

<script>

	function dateStat() {
		var d = document.getElementById('fieldset1').value;
		if(d == 'custom') {
			chgclass('for-custom-date','ln-display-box float-left nc-width-40 right-pull-5');
			document.getElementById('fieldset2').required = true;
			document.getElementById('fieldset3').required = true;
		} else {
			chgclass('for-custom-date','noshow');
			document.getElementById('fieldset2').required = false;
			document.getElementById('fieldset3').required = false;
		}
	}

</script>