<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(isset($_POST['fieldset3']) && !empty($_POST['fieldset3'])) { $bookid = $_POST['fieldset3']; $bookname = ucfirst($_POST['fieldset3']); }  else { $bookid = "All"; $bookname = "All"; }

?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see all the short-stay data which are less than 1 day stay 
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Day Use Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Choose Date?</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php if(isset($_POST['fieldset1'])) { echo $_POST['fieldset1']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Booking Type</small>
			<select name="fieldset3" id="fieldset3" required="required">
				<option value="<?php echo $bookid; ?>" selected="selected"><?php echo $bookname; ?></option>
				<option value="All">All</option>
				<option value="individual">Individual</option>
				<option value="corporate">Corporate</option>
				<option value="agent">Agent</option>
				<option value="e-booking">E-Booking</option>
				<option value="complimentary">Complimentary</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-10 right-pull-5 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton'])) {
		
		$date_from = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$date_to = write_dateF($gh_get_date_format,$_POST['fieldset1']);

		if(isset($_POST['fieldset3']) && $_POST['fieldset3'] == 'All') {
			$sql = "SELECT booking_number,roomid,customerid,checkin_date,checkin_time,checkout_date,checkout_time FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset1']}' AND deletedata=0";
		} else {
			$sql = "SELECT t1.booking_number,t1.roomid,t1.customerid,t1.checkin_date,t1.checkin_time,t1.checkout_date,t1.checkout_tme FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE t2.booking_type='{$_POST['fieldset3']}' AND t1.checkin_date=t1.checkout_date AND t1.status IN('CheckedOut') AND t1.datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset1']}' AND t1.deletedata=0";
		}

		?>
		<p class="bottom-pull-20">
			<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
		</p>

		<div id="section-to-print" class="block-element">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<small class="block-element alignct">Day Use Report Between <?php echo $date_from.' And '.$date_to; ?></small>
			<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

			<?php

				#start report selection
				$get_guest_data = wgetSQL($sql);

				?>
					<div class="block-element top-push-20">
						<div class="block-element top-push-5 box-border-thick">
							<table cellpadding="0" cellspacing="0" border="1" class="ft-xxsml-size">
								<tr>
									<th align="center">Booking Number</th>
									<th align="center">Guest</th>
									<th align="center">Booking Type</th>
									<th align="center">Booked On</th>
									<th align="center">Actual Arrival Date</th>
									<th align="center">Actual Departure Date</th>
									<th align="center">Tax</th>
									<th align="center">Discount</th>
									<th align="center">Other Charges</th>
									<th align="center">Room Tariff</th>
									<th align="center">Total Amount</th>
								</tr>

								<?php

									if(is_array($get_guest_data)) {
										
										$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
										$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
										$checkin_time = ""; $room_name = ""; $room_floor = ""; $bkg_type = "";

										$show_guest = ""; $show_bills = ""; $show_oc = ""; $show_lc = ""; $show_rmk = "";
										$get_guest = ""; $get_bill = ""; $get_bill2 = ""; $get_bill3 = ""; $get_rmk = "";

										$show_room_bookings = ""; $get_room_bookings = "";
										
										$total_4earlycheckins = 0; $total_4latecheckouts = 0; $total_4earlycounts = 0;
										$total_4latecounts = 0; $discount_perc = 0; $total_amount = 0; $total_taxes = 0; $net_amount = 0;

										$g_taxes = 0; $g_discounts = 0; $g_others = 0; $g_tariff = 0; $g_net = 0;

										$numbr = 0;

										foreach($get_guest_data as $scd_key => $scd_value) {

											$get_bill = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} WHERE booking_number='{$scd_value['booking_number']}' AND roomid={$scd_value['roomid']} AND customerid={$scd_value['customerid']} AND deletedata=0 AND room_status IN('CheckedIn')";
											$show_bills = wgetSQL($get_bill);

											$total_taxes = $show_bills[0]['totaltaxAmt'] + $show_bills[0]['totalconsumptionAmt'] + $show_bills[0]['totalserviceAmt'];

											$get_bill2 = "SELECT SUM(bill_amount) AS 'othercharges' FROM {$tbL100} WHERE booking_number='{$scd_value['booking_number']}' AND roomid={$scd_value['roomid']} AND isreversed=0 AND deletedata=0";
											$show_oc = wgetSQL($get_bill2);


											$get_guest = "SELECT * FROM {$tbL102} WHERE id={$scd_value['customerid']} AND deletedata=0";
											$show_guest = wgetSQL($get_guest);

											/*$get_guest = "SELECT * FROM {$tbL102} WHERE booking_number='{$scd_value['booking_number']}' AND primary_guest=1 AND deletedata=0"; $show_guest = wgetSQL($get_guest);*/

											//$get_room_bookings = "SELECT * FROM {$tbL127} WHERE booking_number='{$scd_value['booking_number']}' LIMIT 1"; $show_room_bookings = wgetSQL($get_room_bookings);

											$stay_f = write_dateF($gh_get_date_format,$scd_value['checkin_date']);
											$stay_t = write_dateF($gh_get_date_format,$scd_value['checkout_date']);

											
											$billto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to');
											$booking_type = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'booking_type');
											$booked_on = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'checkin_date');

											$customer_name = idget_data($tbL42,$show_guest[0]['salutation'],'name').' ';
											$customer_name .= $show_guest[0]['fname'].' ';
											$customer_name .= $show_guest[0]['lname'];

											if($booking_type == 'corporate' && $billto > 0) { $bkg_type = "Corporate (".idget_data($tbL58,$billto,'code').")"; }
											elseif($booking_type == 'complimentary' && $billto > 0) { $bkg_type = "Complimentary (".idget_data($tbL33,$billto,'name').")"; }
											else { $bkg_type = "Individual"; }

											$net_amount = ($show_bills[0]['totalroomAmt'] + $total_taxes) - $show_bills[0]['totaldiscountAmt'];
											$net_amount = $net_amount + $show_oc[0]['othercharges'];

											$g_taxes = $g_taxes + $total_taxes;
											$g_discounts = $g_discounts + $show_bills[0]['totaldiscountAmt'];
											$g_others = $g_others + $show_oc[0]['othercharges'];
											$g_tariff = $g_tariff + $show_bills[0]['totalroomAmt'];
											$g_net = $g_net + $net_amount;

											$numbr += 1;

											?>
												<tr>
													<td align="center" class="blue-font anchor" onclick="jsxView('<?php echo $scd_value['booking_number']; ?>')"><?php echo $scd_value['booking_number']; ?></td>
													<td align="center"><?php echo $customer_name; ?></td>
													<td align="center"><?php echo $bkg_type; ?></td>
													<td align="center"><?php echo date('d/m/y',strtotime($booked_on)); ?></td>
													<td width="150px" align="center"><?php echo $stay_f.'. '.$scd_value['checkin_time']; ?></td>
													<td width="150px" align="center"><?php echo $stay_t.'. '.$scd_value['checkout_time']; ?></td>
													<td align="center"><?php echo number_format($total_taxes,2); ?></td>
													<td align="center"><?php echo number_format($show_bills[0]['totaldiscountAmt'],2); ?></td>
													<td align="center"><?php echo number_format($show_oc[0]['othercharges'],2); ?></td>
													<td align="center"><?php echo number_format($show_bills[0]['totalroomAmt'],2); ?></td>
													<td align="center"><?php echo number_format($net_amount,2); ?></td>
												</tr>
											<?php

										}

										?>
											<tr>
												<td align="center" class="default-text-font-bold">Total</td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center"></td>
												<td align="center" class="default-text-font-bold center"><?php echo number_format($g_taxes,2); ?></td>
												<td align="center" class="default-text-font-bold center"><?php echo number_format($g_discounts,2); ?></td>
												<td align="center" class="default-text-font-bold center"><?php echo number_format($g_others,2); ?></td>
												<td align="center" class="default-text-font-bold center"><?php echo number_format($g_tariff,2); ?></td>
												<td align="center" class="default-text-font-bold center"><?php echo number_format($g_net,2); ?></td>
											</tr>
									<?php
								}

							?>

							</table>
						</div>
						<p class="top-pull-3 ft-sml-size"><?php echo $numbr; ?> Found</p>
					</div>
				<?php
				
			?>
		</div>
		<?php
	}

?>

<script>

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}

</script>