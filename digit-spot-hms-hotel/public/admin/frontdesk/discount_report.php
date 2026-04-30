<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(isset($_POST['fieldset3']) && !empty($_POST['fieldset3'])) { $bookid = $_POST['fieldset3']; $bookname = ucfirst($_POST['fieldset3']); }  else { $bookid = "All"; $bookname = "All"; }

?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see all the discounts given within the date period
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Discount Report</h3>
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

		$additionalQuery = " AND bill_date BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' AND discount_amount > 0 GROUP BY booking_number";
		$gs_query = array("room_status"=>"CheckedIn","deletedata"=>0);

		if(isset($_POST['fieldset3']) && $_POST['fieldset3'] != 'All') {
			$gs_query['charge_type'] = $_POST['fieldset3'];
		}

		?>
		<p class="bottom-pull-20">
			<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
		</p>

		<div id="section-to-print" class="block-element">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<small class="block-element alignct">Discount Report Between <?php echo $date_from.' And '.$date_to; ?></small>
			<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

			<?php

				#start report selection

				//$guest_dataproperty = "charge_type,billto,booking_number,invoice_number,customerid,roomid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge";
				$guest_dataproperty = "booking_number";
				$get_guest_data = mysqli_data_fetch($tbL134,$guest_dataproperty,$gs_query,'array');

				?>
					<div class="block-element top-push-20">
						<div class="block-element top-push-5 box-border-thick">
							<table cellpadding="0" cellspacing="0" border="1" class="ft-xxsml-size">
								<tr>
									<th width="40px" align="center">&nbsp;</th>
									<th align="center">Booking Number</th>
									<th align="center">Guest</th>
									<th align="center">Billed-To</th>
									<th align="center">Booked On</th>
									<th align="center">Early Checkin</th>
									<th align="center">Late Checkout</th>
									<th align="center">Room Tariff</th>
									<th align="center">Total Amount</th>
									<th align="center">Discount (%)</th>
									<th align="center">Discount (N)</th>
									<th align="center">Remarks</th>
									<th align="center">Tax</th>
									<th align="center">Net Amount</th>
								</tr>

								<?php

									if(is_array($get_guest_data)) {
										
										$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
										$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
										$checkin_time = ""; $room_name = ""; $room_floor = ""; $bkg_type = "";

										$show_guest = ""; $show_bills = ""; $show_ec = ""; $show_lc = ""; $show_rmk = "";
										$get_guest = ""; $get_bill = ""; $get_bill2 = ""; $get_bill3 = ""; $get_rmk = "";
										
										$total_4earlycheckins = 0; $total_4latecheckouts = 0; $total_4earlycounts = 0;
										$total_4latecounts = 0; $discount_perc = 0; $total_amount = 0; $total_taxes = 0; $net_amount = 0;
										$total_grand_amount = 0; $total_grand_net = 0; $total_room_amount = 0; $total_discount_amount = 0;
										$total_grand_taxes = 0;

										$numbr = 0;

										foreach($get_guest_data as $scd_key => $scd_value) {

											$get_bill = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} WHERE booking_number='{$scd_value['booking_number']}' AND deletedata=0 AND room_status IN('CheckedIn') AND invoice_number NOT IN('EARLYCHECKIN','LATECHECKOUT') AND bill_date BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' AND discount_amount > 0";
											$show_bills = wgetSQL($get_bill);

											$total_taxes = $show_bills[0]['totaltaxAmt'] + $show_bills[0]['totalconsumptionAmt'] + $show_bills[0]['totalserviceAmt'];

											$discount_perc = ($show_bills[0]['totaldiscountAmt'] / $show_bills[0]['totalroomAmt']) * 100;
											$discount_perc = number_format($discount_perc,1);

											$get_bill2 = "SELECT SUM(room_amount) AS 'totalEchkin' FROM {$tbL134} WHERE booking_number='{$scd_value['booking_number']}' AND deletedata=0 AND room_status IN('CheckedIn') AND invoice_number IN('EARLYCHECKIN') AND wkf=2 AND bill_date BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}'";
											$show_ec = wgetSQL($get_bill2);

											$get_bill3 = "SELECT SUM(room_amount) AS 'totalLchkout' FROM {$tbL134} WHERE booking_number='{$scd_value['booking_number']}' AND deletedata=0 AND room_status IN('CheckedIn') AND invoice_number IN('LATECHECKOUT') AND wkf=1 AND bill_date BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}'";
											$show_lc = wgetSQL($get_bill3);

											$total_amount = $show_bills[0]['totalroomAmt'] + $show_ec[0]['totalEchkin'] + $show_lc[0]['totalLchkout'];

											$get_guest = "SELECT * FROM {$tbL102} WHERE booking_number='{$scd_value['booking_number']}' AND primary_guest=1 AND deletedata=0"; $show_guest = wgetSQL($get_guest);

											
											$billto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to');
											$booking_type = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'booking_type');
											$booked_on = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'checkin_date');

											$customer_name = idget_data($tbL42,$show_guest[0]['salutation'],'name').' ';
											$customer_name .= $show_guest[0]['fname'].' ';
											$customer_name .= $show_guest[0]['lname'];

											if($scd_value['charge_type'] == 'corporate' && $scd_value['billto'] > 0) { $bkg_type = "Corporate (".idget_data($tbL58,$billto,'name').")"; }
											elseif($scd_value['charge_type'] == 'complimentary' && $scd_value['billto'] > 0) { $bkg_type = "Complimentary (".idget_data($tbL33,$billto,'name').")"; }
											else { $bkg_type = "Individual (".$customer_name.")"; }

											$net_amount = ($show_bills[0]['totalroomAmt'] + $total_taxes) - $show_bills[0]['totaldiscountAmt'];

											$get_rmk = "SELECT activities FROM {$tbL132} WHERE booking_number='{$scd_value['booking_number']}' AND (datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' OR datelogged BETWEEN '{$_POST['fieldset2']}' AND '{$_POST['fieldset2']}') AND remark_tag IN('discount') AND deletedata=0"; $show_rmk = wgetSQL($get_rmk);
											if(is_array($show_rmk)) { $rmks = ""; foreach($show_rmk as $key => $val) { $rmks .= nl2br($val['activities']).'<p>&nbsp;</p>'; } }

											$numbr += 1;

											?>
												<tr>
													<td width="40px" align="center"><?php echo $numbr; ?>.</td>
													<td align="center" class="blue-font anchor" onclick="jsxView('<?php echo $scd_value['booking_number']; ?>')"><?php echo $scd_value['booking_number']; ?></td>
													<td align="center"><?php echo $customer_name; ?></td>
													<td align="center"><?php echo $bkg_type; ?></td>
													<td align="center"><?php echo date('d/m/y',strtotime($booked_on)); ?></td>
													<td align="center"><?php echo number_format($show_ec[0]['totalEchkin'],2); ?></td>
													<td align="center"><?php echo number_format($show_lc[0]['totalLchkout'],2); ?></td>
													<td align="center"><?php echo number_format($show_bills[0]['totalroomAmt'],2); ?></td>
													<td align="center"><?php echo number_format($total_amount,2); ?></td>
													<td align="center"><?php echo $discount_perc; ?></td>
													<td align="center"><?php echo number_format($show_bills[0]['totaldiscountAmt'],2); ?></td>
													<td width="250px" align="left"><?php echo $rmks; ?></td>
													<td align="center"><?php echo number_format($total_taxes,2); ?></td>
													<td align="center"><?php echo number_format($net_amount,2); ?></td>
												</tr>
											<?php

											$total_4earlycheckins = $total_4earlycheckins + $show_ec[0]['totalEchkin'];
											$total_4latecheckouts = $total_4latecheckouts + $show_lc[0]['totalLchkout'];
											$total_room_amount = $total_room_amount + $show_bills[0]['totalroomAmt'];
											$total_grand_amount = $total_grand_amount + $total_amount;
											$total_discount_amount = $total_discount_amount + $show_bills[0]['totaldiscountAmt'];
											$total_grand_taxes = $total_grand_taxes + $total_taxes;
											$total_grand_net = $total_grand_net + $net_amount;

										}
									}

								?>

								<tr>
									<td width="40px" align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="center">&nbsp;</td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_4earlycheckins,2); ?></td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_4latecheckouts,2); ?></td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_room_amount,2); ?></td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_grand_amount,2); ?></td>
									<td align="center">&nbsp;</td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_discount_amount,2); ?></td>
									<td align="left">&nbsp;</td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_grand_taxes,2); ?></td>
									<td align="center" class="default-text-font-bold"><?php echo number_format($total_grand_net,2); ?></td>
								</tr>

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