<?php
	$booking_number = $ftoken;
	$ths_token = explode('-',$stoken);

	$wgt_cur_room_id = $ths_token[0];
	$wgt_cur_guest_id = $ths_token[1];

	$room_prefix = idget_data($tbL56,$wgt_cur_room_id,'roomprefix');
	$room_number = idget_data($tbL56,$wgt_cur_room_id,'roomnumber');
	$block_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'blockid');
	$block_name = idget_data($tbL49,$block_id,'name');

	//get guest in this room
	$wgt_bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');
	$wgt_booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
	$wgt_bill_type = idget_fdata($tbL130,'booking_number',$booking_number,'bill_type');
	
	if($wgt_booking_type == 'corporate' && $wgt_bill_to >= 1) {
		$cspg = 'Corp: '.idget_data($tbL58,$wgt_bill_to,'name');
	} elseif($wgt_booking_type == 'complimentary' && $wgt_bill_to >= 1) {
		$cspg = 'Compl: '.idget_data($tbL33,$wgt_bill_to,'name');
	} else {
		$cspg = "";
	}

	$additionalQuery=" ORDER BY id ASC LIMIT 1";
	$occ_data2 = "customerid,checkin_byuser,occupancy_type,noofdays,checkin_date,checkin_time,checkout_date,checkout_time";
	$cquery2 = array("booking_number"=>$booking_number,"customerid"=>$wgt_cur_guest_id,"deletedata"=>0);
	$cdata2 = mysqli_data_fetch($tbL127,$occ_data2,$cquery2,'noarray');
	
	$additionalQuery="";

	$occ_data = "customerid,checkin_byuser,occupancy_type,noofdays,checkin_date,checkin_time,checkout_date,checkout_time";
	$cquery = array("booking_number"=>$booking_number,"roomid"=>$wgt_cur_room_id,"customerid"=>$wgt_cur_guest_id,"deletedata"=>0);
	$cdata = mysqli_data_fetch($tbL127,$occ_data,$cquery,'noarray');

	$thsguest = $cdata[0];
	$thscheckinuser = $cdata[1];
	$thsoccupancy = $cdata[2];

	$psnoofdays = 0;

	$query_pra = "booking_number='{$booking_number}' AND customerid={$thsguest} AND status IN('Swapped','Downgraded','Upgraded')";
	$result = mysqli_data_checkr($tbL127,'(*)',$query_pra);
	
	if($result == true) {
		$count_ps = "COUNT(roomid)";
		$query_ps = "booking_number='{$booking_number}' AND customerid={$thsguest} AND roomid NOT IN({$wgt_cur_room_id}) AND ischarged=1 AND wkf=0 AND room_status='CheckedIn' AND deletedata=0";
		$psnoofdays = mysqli_arithmetic_data($tbL134,$count_ps,$query_ps);
	}

	$count_d = "COUNT(roomid)";
	$query_d = "booking_number='{$booking_number}' AND roomid={$wgt_cur_room_id} AND customerid={$thsguest} AND ischarged=1 AND wkf=0 AND room_status='CheckedIn' AND deletedata=0"; $thsnoofdays = mysqli_arithmetic_data($tbL134,$count_d,$query_d);

	$thsnoofdays = $thsnoofdays + $psnoofdays;

	$salutationid = idget_data($tbL102,$thsguest,'salutation');
	$thsSalutation = idget_data($tbL42,$salutationid,'name');
	$thsguest_account_name = $thsSalutation.' '.idget_data($tbL102,$thsguest,'fname').' '.idget_data($tbL102,$thsguest,'lname');
	$thsguest_address = idget_data($tbL102,$thsguest,'address');

	$checkin_staffname = idget_data($tbL7,$thscheckinuser,'staffname');
	$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
	$get_occupancy_type = idget_data($tbL51,$thsoccupancy,'name');

	$rrquery = array("booking_number"=>$booking_number,"roomid"=>$wgt_cur_room_id,"customerid"=>$thsguest,"day"=>1);
	$rrdata = mysqli_data_fetch($tbL134,'room_amount',$rrquery,'noarray');
	$rack_rate = $rrdata[0];
	$print_rack_rate = write_amountF(2,$rack_rate);

	$print_todaysdate = write_dateF($gh_get_date_format,$server_get_date);
	$print_todaystime = write_timeF($gh_get_time_format,$server_get_time);

	if(is_array($cdata2) && count($cdata2) > 0) {
		$xprint_wgt_checkin_date = write_dateF($gh_get_date_format,$cdata2[4]);
		$xprint_wgt_checkin_time = write_timeF($gh_get_time_format,$cdata2[5]);
	} else {
		$xprint_wgt_checkin_date = write_dateF($gh_get_date_format,$cdata[4]);
		$xprint_wgt_checkin_time = write_timeF($gh_get_time_format,$cdata[5]);
	}

	$xprint_wgt_checkout_date = write_dateF($gh_get_date_format,$cdata[6]);
	$xprint_wgt_checkout_time = write_timeF($gh_get_time_format,$cdata[7]);

	
	//include "booking_tokens.php";
?>

<div id="section-to-print" class="block-element" align="center">
	<div class="cs-width-900">
		<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
		<h1 class="large nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h1>
		<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
		<small class="block-element top-push-3 bottom-push-20">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></small>
		<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?> On <?php echo $print_todaysdate.' '.$print_todaystime; ?></h4><br>
		<h3 class="large nobold default-text-font-bold">Statement of Account</h3>
		<div class="block-element alignrt top-pull-7 bottom-pull-20">
			<input type="button" value="Bookings" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="allowPrint('booking','pos'); addup('booking-total')"> &nbsp; <input type="button" value="Outlet" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="allowPrint('pos','booking'); addup('pos-total')">
		</div>
		<div class="block-element">
			<span class="ln-display-box float-left nc-width-45 alignlt">
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Guest Name
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php if(!empty($cspg)) { ?><h3 class="large nobold default-text-font-bold"><?php echo $cspg; ?></h3><?php } ?>
					<b class="nobold default-text-font-bold"><?php echo $thsguest_account_name; ?></b>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Address
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<b class="nobold default-text-font-bold"><?php echo $thsguest_address; ?></b>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Room Number
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $room_prefix.$room_number; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Check-In Date & Time
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $xprint_wgt_checkin_date.' '.$xprint_wgt_checkin_time; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Check-Out Date & Time
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $xprint_wgt_checkout_date.' '.$xprint_wgt_checkout_time; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					CheckedIn Staff
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $checkin_staffname; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Tariff Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<!--&#8358; <?php //echo $print_rack_rate; ?>-->Rack Rate
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Occupancy Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $get_occupancy_type; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="ln-display-box float-right nc-width-45 alignrt">
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Booking Number
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<b class="nobold default-text-font-bold"><?php echo $booking_number; ?></b>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Booking Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $wgt_booking_type; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Billing Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $wgt_bill_type; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					No of Nights
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $thsnoofdays; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Date
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $print_todaysdate; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size dark-black-font">
					Time
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $print_todaystime; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="block-element new-line-space">
			</span>
		</div>
		<div id="booking" class="block-element top-push-15 alignlt">
			<h4 class="large nobold default-text-font-bold alignct">Booking</h4>
			<div class="block-element top-push-3 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
					<tr>
						<th width="100px" align="center">Date</th>
						<th width="100px" align="center">Room</th>
						<th width="300px" align="center">Charge</th>
						<th width="150px" align="center">Debit</th>
						<th width="150px" align="center">Credit</th>
						<th width="50px" align="center"></th>
					</tr>

					<?php

						if($result == true) {

							$noofcounts = 0;
								
							$logged_date=""; $room_discount=0; $bill_post=0; $total_discount=0; $total_billpost=0;
							$total_comsumption_tax=0; $total_vat=0; $total_service_charge=0; $total_other_charges=0;

							#get room daily charges listed
							$additionalQuery = " AND roomid NOT IN({$wgt_cur_room_id})";
							$sql_query = array("booking_number"=>$booking_number,"customerid"=>$thsguest,"ischarged"=>1,"deletedata"=>0);
							$datasets = "roomid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,bill_date,bill_time,ischarged";
							$sql_data = mysqli_data_fetch($tbL134,$datasets,$sql_query,'array');

							if(is_array($sql_data)) {
								
								$x_room_prefix=""; $x_room_number="";
								
								foreach($sql_data as $r_key => $r_value) {
									
									$noofcounts += 1;

									$total_vat = $total_vat + $r_value['tax_amount'];
									$total_comsumption_tax = $total_comsumption_tax + $r_value['consumption_tax_amount'];
									$total_service_charge = $total_service_charge + $r_value['service_charge'];
									$charged_room_tariff = $r_value['room_amount'] - $r_value['discount_amount'];
									$total_billpost = $total_billpost + $charged_room_tariff;

									$print_bill_date = write_dateF($gh_get_date_format,$r_value['bill_date']);
									$print_bill_post = write_amountF($gh_get_decimal_format,$charged_room_tariff);

									$x_room_prefix = idget_data($tbL56,$r_value['roomid'],'roomprefix');
									$x_room_number = idget_data($tbL56,$r_value['roomid'],'roomnumber');

									?>
										<tr>
											<td width="100px" align="center"><?php echo $print_bill_date; ?></td>
											<td width="100px" align="center"><?php echo $x_room_prefix.$x_room_number; ?></td>
											<td width="300px" align="center">Room Charge</td>
											<td width="150px" align="center">&#8358;<?php echo $print_bill_post; ?></td>
											<td width="150px" align="center">0</td>
											<td width="50px" align="center">&nbsp;</td>
										</tr>
									<?php

									$charged_room_tariff = 0;
								}

								?>
									<tr><td colspan="6"></td></tr>
								<?php
							}

						} else {

							$noofcounts = 0;

							$logged_date=""; $room_discount=0; $bill_post=0; $total_discount=0; $total_billpost=0;
							$total_comsumption_tax=0; $total_vat=0; $total_service_charge=0; $total_other_charges=0;
						}


						#get room daily charges listed
						$additionalQuery = "";
						$sql_query = array("booking_number"=>$booking_number,"roomid"=>$wgt_cur_room_id,"customerid"=>$thsguest,"ischarged"=>1,"deletedata"=>0);
						$datasets = "invoice_number,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,bill_date,bill_time";
						$sql_data = mysqli_data_fetch($tbL134,$datasets,$sql_query,'array');

						if(is_array($sql_data)) {
						
							foreach($sql_data as $r_key => $r_value) {
								
								$noofcounts += 1;

								$total_vat = $total_vat + $r_value['tax_amount'];
								$total_comsumption_tax = $total_comsumption_tax + $r_value['consumption_tax_amount'];
								$total_service_charge = $total_service_charge + $r_value['service_charge'];
								$charged_room_tariff = $r_value['room_amount'] - $r_value['discount_amount'];
								$total_billpost = $total_billpost + $charged_room_tariff;

								$print_bill_date = write_dateF($gh_get_date_format,$r_value['bill_date']);
								$print_bill_post = write_amountF($gh_get_decimal_format,$charged_room_tariff);

								?>
									<tr>
										<td width="100px" align="center"><?php echo $print_bill_date; ?></td>
										<td width="100px" align="center"><?php echo $room_prefix.$room_number; ?></td>
										<td width="300px" align="center"><?php if($r_value['invoice_number'] === 'LATECHECKOUT'): ?>LATECHECKOUT<?php else: ?>ROOM CHARGE<?php endif; ?></td>
										<td width="150px" align="center">&#8358;<?php echo $print_bill_post; ?></td>
										<td width="150px" align="center">0</td>
										<td width="50px" align="center">&nbsp;</td>
									</tr>
								<?php

								$charged_room_tariff = 0;
							}
						}


						$total_bill_amount = $total_billpost + $total_comsumption_tax + $total_vat + $total_service_charge;
							
						if($total_bill_amount > 0) {
							$print_total_billpost = write_amountF($gh_get_decimal_format,$total_billpost);
							$print_total_comsumption_tax = write_amountF($gh_get_decimal_format,$total_comsumption_tax);
							$print_total_vat = write_amountF($gh_get_decimal_format,$total_vat);
							$print_total_service_charge = write_amountF($gh_get_decimal_format,$total_service_charge);
							$print_total_bill_amount = write_amountF($gh_get_decimal_format,$total_bill_amount);
						} else {
							$print_total_billpost = "0.00";
							$print_total_comsumption_tax = "0.00";
							$print_total_vat = "0.00";
							$print_total_service_charge = "0.00";
							$print_total_bill_amount = "0.00";
						}

					?>
				</table>
			</div>
			<div class="block-element top-pull-30 right-pull-10 ft-xsml-size">
				<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_billpost; ?></b></span>
				<span class="ln-display-box float-right bottom-push-10">Room Tariff</span>
				<span class="block-element new-line-space"></span>
				
				<?php if($htx1 == 1): ?>
				<div id="htx-consumption" ondblclick="removechild('htx-consumption')">
					<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_comsumption_tax; ?></b></span>
					<span class="ln-display-box float-right bottom-push-10">Consumption Tax</span>
					<span class="block-element new-line-space"></span>
					
				</div>
				<?php endif; ?>
				
				<?php if($htx2 == 1): ?>
				<div id="htx-service" ondblclick="removechild('htx-service')">
					<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_service_charge; ?></b></span>
					<span class="ln-display-box float-right bottom-push-10">Service Charge</span>
					<span class="block-element new-line-space"></span>
				</div>
				<?php endif; ?>
				
				<?php if($htx3 == 1): ?>
				<div id="htx-vat" ondblclick="removechild('htx-vat')">
					<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_vat; ?></b></span>
					<span class="ln-display-box float-right bottom-push-10">VAT</span>
					<span class="block-element new-line-space"></span>
				</div>
				<?php endif; ?>
				
				<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_bill_amount; ?></b></span>
				<span class="ln-display-box float-right bottom-push-10">Total</span>
				<span class="block-element new-line-space"></span>
				<input type="hidden" name="booking-total" id="booking-total" value="<?php echo $total_bill_amount; ?>">
			</div>
		</div>

		<div id="pos" class="block-element top-push-15 alignlt">
			<h4 class="large nobold default-text-font-bold alignct">Sales Outlet</h4>
			<div class="block-element top-push-3 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
					<tr>
						<th width="100px" align="center">Date</th>
						<th width="200px" align="center">Occupancy / Tariff</th>
						<th width="150px" align="center">Order Number</th>
						<th width="150px" align="center">Store</th>
						<th width="100px" align="center">Debit</th>
						<th width="100px" align="center">Credit</th>
						<th width="50px" align="center"></th>
					</tr>

					<?php

						//get sales point records

						$select_property = "datelogged";
						$additionalQuery = " GROUP BY datelogged";
						$select_query = array("booking_number"=>$booking_number,"customerid"=>$thsguest,"status"=>"Completed","isreversed"=>0,"deletedata"=>0);
						$select_data = mysqli_data_fetch($tbL100,$select_property,$select_query,'array');

						if(is_array($select_data)) {
							
							$bydate=""; $total_pos_charges=0;

							foreach($select_data as $pskey => $psvalue) {
								
								$bydate = $psvalue['datelogged'];
								$ps_logged_date = write_dateF($gh_get_date_format,$bydate);

								$additionalQuery = "";
								$select_property_4 = "id,datelogged,order_number,posid,bill_amount";
								$select_query_4 = array("booking_number"=>$booking_number,"customerid"=>$thsguest,"status"=>"Completed","isreversed"=>0,"datelogged"=>$bydate);

								if(isset($wgt_booking_type) && $wgt_booking_type == 'corporate' && !empty($wgt_bill_to)) {
									$select_query_4['biller'] = $wgt_bill_to;
								}

								$select_data_4 = mysqli_data_fetch($tbL100,$select_property_4,$select_query_4,'array');

								if(is_array($select_data_4)) {
										
									$pos_store=""; $amount=""; $print_amount=""; $count_transaction=0;
									
									foreach($select_data_4 as $pos_key => $pos_value) {
										
										$count_transaction += 1;

										$pos_store = idget_data($tbL14,$pos_value['posid'],'posname');
										$amount = $pos_value['bill_amount'];
										$total_pos_charges = $total_pos_charges + $amount;
										
										$print_amount = write_amountF($gh_get_decimal_format,$amount);

										?>
											<tr>
												<?php if($count_transaction == 1) { ?><td width="100px" align="center" rowspan="<?php echo count($select_data_4); ?>" class="box-border-thick-right"><?php echo $ps_logged_date; ?></td><?php } ?>
												<td width="200px" align="center" class="box-border-thick-right"></td>
												<td width="150px" align="center" class="box-border-thick-right"><?php echo $pos_value['order_number']; ?></td>
												<td width="150px" align="center" class="box-border-thick-right"><?php echo $pos_store; ?></td>
												<td width="100px" align="center" class="box-border-thick-right">&#8358;<?php echo $print_amount; ?></td>
												<td width="100px" align="center" class="box-border-thick-right">0</td>
												<td width="50px" align="center"></td>
											</tr>
										<?php
									}
								}
							}

							$print_total_pos_charges = write_amountF($gh_get_decimal_format,$total_pos_charges);

							?>
								<tr class="grey-theme">
									<td width="100px" align="center">Total</td>
									<td width="200px" align="center"></td>
									<td width="150px" align="center"></td>
									<td width="150px" align="center"></td>
									<td width="100px" align="center"><b>&#8358;<?php echo $print_total_pos_charges; ?></b></td>
									<td width="100px" align="center"></td>
									<td width="50px" align="center"></td>
								</tr>
							<?php
						}
					?>
				</table>
				<input type="hidden" name="pos-total" id="pos-total" value="<?php echo $total_pos_charges; ?>">
			</div>
		</div>

		<?php
			$grand_total = $total_pos_charges + $total_bill_amount;
			$print_grand_total = write_amountF($gh_get_decimal_format,$grand_total);
		?>

		<div id="t-base" class="block-element top-push-50 alignlt">
			<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
				<tr>
					<td align="right" class="box-border-thick-top top-pull-10"><h4 class="xlarge nobold">Grand Total</h4><h3 id="grand-total" class="large nobold default-text-font-bold">&#8358;<?php echo $print_grand_total; ?></h3></td>
				</tr>
			</table>
		</div>


		<div class="alignlt top-pull-50">
			--------------------------------------------
			<p class="top-pull-7 left-pull-10">Signature & Date</p>
		</div>
				
	</div>
</div>
<div class="block-element top-pull-50 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="window.print()"> &nbsp; <input type="button" value="Send Mail" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="">
</div>

<script>

	function allowPrint(obj1,obj2) {
		chgclass(obj1,'block-element top-push-15 alignlt');
		chgclass(obj2,'noshow top-push-15 alignlt');

		var cssStyle, styleobj = document.createElement('style');
		styleobj.type = 'text/css';

		cssStyle = "@media print { #"+obj2+" { display: none; } }";

		if(styleobj.styleSheet) {
			styleobj.styleSheet.cssText = cssStyle;
		} else {
			styleobj.appendChild(document.createTextNode(cssStyle));
			document.getElementsByTagName("head")[0].appendChild(styleobj);
		}
	}

	function addup(sum) {
		var tsum = document.getElementById(sum).value;
		var print_sum = numberFormat(tsum);
		writeObjheader('grand-total','&#8358; '+print_sum);
	}

	function removechild(obj) {
		chgclass(obj,'noshow');
	}

</script>