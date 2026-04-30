<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(isset($_POST['fieldset3']) && !empty($_POST['fieldset3'])) { $bookid = $_POST['fieldset3']; $bookname = ucfirst($_POST['fieldset3']); }  else { $bookid = "All"; $bookname = "All"; }

	if(isset($_POST['fieldset4']) && !empty($_POST['fieldset4'])) { $bookstatid = $_POST['fieldset4']; $bookstatname = $_POST['fieldset4']; } else { $bookstatid = "All"; $bookstatname = "All"; }

?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see all the early check-in and late check-out charges on guest ledger
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Early Checkin & Late Checkout Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php if(isset($_POST['fieldset1'])) { echo $_POST['fieldset1']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php if(isset($_POST['fieldset2'])) { echo $_POST['fieldset2']; } else { echo $server_get_date; } ?>">
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
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Charge Type</small>
			<select name="fieldset4" id="fieldset4" required="required">
				<option value="<?php echo $bookstatid; ?>" selected="selected"><?php echo $bookstatname; ?></option>
				<option value="All">All</option>
				<option value="Early Checkin">Early Checkin</option>
				<option value="Late Checkout">Late Checkout</option>
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
		$date_to = write_dateF($gh_get_date_format,$_POST['fieldset2']);

		$additionalQuery = " AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}'";
		$gs_query = array("deletedata"=>0);

		if(isset($_POST['fieldset3']) && $_POST['fieldset3'] != 'All') {
			$gs_query['charge_type'] = $_POST['fieldset3'];
		}

		if(isset($_POST['fieldset4']) && $_POST['fieldset4'] == 'Early Checkin') {
			$gs_query['invoice_number'] = "EARLYCHECKIN";
			$gs_query['wkf'] = 2;
		} elseif(isset($_POST['fieldset4']) && $_POST['fieldset4'] == 'Late Checkout') {
			$gs_query['invoice_number'] = "LATECHECKOUT";
			$gs_query['wkf'] = 1;
		} else {
			$additionalQuery .= " AND invoice_number IN('EARLYCHECKIN','LATECHECKOUT')";
		}

		?>
		<p class="bottom-pull-20">
			<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
		</p>

		<div id="section-to-print" class="block-element">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<small class="block-element alignct">Early Check-in and Late Check-out Report Between <?php echo $date_from.' And '.$date_to; ?></small>
			<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

			<?php

				#start report selection

				$guest_dataproperty = "charge_type,billto,booking_number,invoice_number,customerid,roomid,room_amount";
				$get_guest_data = mysqli_data_fetch($tbL134,$guest_dataproperty,$gs_query,'array');

				?>
					<div class="block-element top-push-20">
						<div class="block-element top-push-5 box-border-thick">
							<table cellpadding="0" cellspacing="0" border="1" class="ft-xxsml-size">
								<tr>
									<th width="50px" align="center">&nbsp;</th>
									<th width="150px" align="center">Booking Number</th>
									<th width="200px" align="center">Guest</th>
									<th width="100px" align="center">Room No.</th>
									<th width="150px" align="center">Booking Type</th>
									<th width="150px" align="center">Actual Arrival Date</th>
									<th width="150px" align="center">Actual Departure Date</th>
									<th width="150px" align="center">Charge Type</th>
									<th width="150px" align="center">Amount</th>
								</tr>

								<?php

									if(is_array($get_guest_data)) {
										
										$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
										$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
										$checkin_time = ""; $room_name = ""; $room_floor = ""; $bkg_type = "";

										$show_room_bookings = ""; $get_room_bookings = "";
										$total_4earlycheckins = 0; $total_4latecheckouts = 0;
										$total_4earlycounts = 0; $total_4latecounts = 0;

										$numbr = 0;

										foreach($get_guest_data as $scd_key => $scd_value) {

											$get_room_bookings = "SELECT * FROM {$tbL127} WHERE booking_number='{$scd_value['booking_number']}' AND roomid='{$scd_value['roomid']}'";
											$show_room_bookings = wgetSQL($get_room_bookings);

											$stay_f = write_dateF($gh_get_date_format,$show_room_bookings[0]['checkin_date']);
											$stay_t = write_dateF($gh_get_date_format,$show_room_bookings[0]['checkout_date']);

											
											$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
											$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

											if($scd_value['invoice_number'] == 'EARLYCHECKIN') {
												$bkt = "Early Checkin";
												$total_4earlycheckins = $total_4earlycheckins + $scd_value['room_amount'];
												$total_4earlycounts = $total_4earlycounts + 1;
											} elseif($scd_value['invoice_number'] == 'LATECHECKOUT') {
												$bkt = "Late Checkout";
												$total_4latecheckouts = $total_4latecheckouts + $scd_value['room_amount'];
												$total_4latecounts = $total_4latecounts + 1;
											}

											$salutation = idget_data($tbL102,$scd_value['customerid'],'salutation');
											$billto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to');
											$booking_type = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'booking_type');

											$customer_name = idget_data($tbL42,$salutation,'name').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'fname').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'lname');

											if($scd_value['charge_type'] == 'corporate' && $scd_value['billto'] > 0) { $bkg_type = "Corporate (".idget_data($tbL58,$billto,'name').")"; }
											elseif($scd_value['charge_type'] == 'complimentary' && $scd_value['billto'] > 0) { $bkg_type = "Complimentary (".idget_data($tbL33,$billto,'name').")"; }
											else { $bkg_type = "Individual"; }

											$numbr += 1;

											?>
												<tr>
													<td width="50px" align="center"><?php echo $numbr; ?>.</td>
													<td width="150px" align="center" class="blue-font anchor" onclick="jsxView('<?php echo $scd_value['booking_number']; ?>')"><?php echo $scd_value['booking_number']; ?></td>
													<td width="200px" align="center"><?php echo $customer_name; ?></td>
													<td width="100px" align="center"><?php echo $room_name; ?></td>
													<td width="150px" align="center"><?php echo $bkg_type; ?></td>
													<td width="150px" align="center"><?php echo $stay_f.'. '.$show_room_bookings[0]['checkin_time']; ?></td>
													<td width="150px" align="center"><?php echo $stay_t.'. '.$show_room_bookings[0]['checkout_time']; ?></td>
													<td width="120px" align="center"><?php echo $bkt; ?></td>
													<td width="120px" align="center"><?php echo number_format($scd_value['room_amount'],2); ?></td>
												</tr>
											<?php

										}
									}

								?>

							</table>
						</div>
						<p class="top-pull-3 ft-sml-size"><?php echo $numbr; ?> Found</p>

						<br><br>

						<?php
							$total_counts = $total_4earlycounts + $total_4latecounts;
							$total_amounts = $total_4earlycheckins + $total_4latecheckouts;
						?>

						<div align="center">
							<h3 class="large nobold">Summary of Early Checkin & Late Checkout</h3>
							<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
								<tr>
									<td class="default-font-text-bold">Particulars</td>
									<td class="default-font-text-bold">Total Rooms</td>
									<td class="default-font-text-bold">Amount</td>
								</tr>
								<tr>
									<td class="alignlt">Early Check-in</td>
									<td class="alignrt"><?php echo number_format($total_4earlycounts); ?></td>
									<td class="alignrt"><?php echo number_format($total_4earlycheckins,2); ?></td>
								</tr>
								<tr>
									<td class="alignlt">Late Check-out</td>
									<td class="alignrt"><?php echo number_format($total_4latecounts); ?></td>
									<td class="alignrt"><?php echo number_format($total_4latecheckouts,2); ?></td>
								</tr>
								<tr>
									<td class="default-font-text-bold alignlt">Total</td>
									<td class="default-font-text-bold alignrt"><?php echo number_format($total_counts); ?></td>
									<td class="default-font-text-bold alignrt">&#8358; <?php echo number_format($total_amounts,2); ?></td>
								</tr>
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

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}

</script>