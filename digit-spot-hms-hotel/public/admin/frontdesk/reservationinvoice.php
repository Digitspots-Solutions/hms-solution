<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$print_todaysdate = write_dateF($gh_get_date_format,$server_get_date);
	$print_todaystime = write_dateF($gh_get_time_format,$server_get_time);

	include "booking_tokens.php";

	if(isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner') {
		$ths_guest_query = array("booking_number"=>$booking_number,"invoice_number"=>$ths_token,"primary_guest"=>1,"deletedata"=>0);
	} else {
		$ths_guest_query = array("booking_number"=>$booking_number,"invoice_number"=>$ths_token,"deletedata"=>0);
	}

	$guest_dataproperty = "id,photo,guest_code,salutation,fname,lname";
	$get_thsguest_detail = mysqli_data_fetch($tbL102,$guest_dataproperty,$ths_guest_query,'noarray');

	#get salutation
	$salutation = idget_data($tbL42,$get_thsguest_detail[3],'name');
	$thsguest_account_name = $salutation.' '.$get_thsguest_detail[4].' '.$get_thsguest_detail[5];
?>

<div id="section-to-print" class="block-element" align="center">
	<div class="cs-width-900">
		<img src="<?php echo _FC_LOGO_Mx; ?>">
		<h1 class="large nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h1>
		<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
		<small class="block-element top-push-3 bottom-push-20">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></small>
		<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?> On <?php echo $print_todaysdate.' '.$print_todaystime; ?></h4><br>
		<h2 class="large nobold default-text-font-bold">Invoice</h2><br>
		<div class="block-element bottom-push-20">
			<span class="ln-display-box float-left nc-width-45 alignlt">
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Guest Name
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<b class="nobold default-text-font-bold"><?php echo $thsguest_account_name; ?></b>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Check-In Date & Time
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $print_wgt_checkin_date.' '.$print_wgt_checkin_time; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Check-Out Date & Time
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
					<?php echo $print_wgt_checkout_date.' '.$print_wgt_checkout_time; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="ln-display-box float-right nc-width-45 alignrt">
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Booking Number
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<b class="nobold default-text-font-bold"><?php echo $booking_number; ?></b>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Booking Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $wgt_booking_type; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
				<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
					Billing Type
				</div>
				<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
					<?php echo $wgt_bill_type; ?>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="block-element new-line-space">
			</span>
		</div>
		<div class="block-element top-push-3 box-border-thick sml-rounded-button noscroll">
			<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
				<tr>
					<th width="150px" align="center">Date</th>
					<th width="250px" align="center">Room</th>
					<th width="150px" align="center">Charge</th>
					<th width="100px" align="center">Day</th>
					<th width="150px" align="center">Total</th>
				</tr>

				<?php
					#get room daily charges listed
					$sql_query = array("booking_number"=>$booking_number,"invoice_number"=>$ths_token,"ischarged"=>1,"deletedata"=>0);
					$datasets = "roomid,day,weekday,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,bill_date,bill_time,datelogged,timelogged";
					$sql_data = mysqli_data_fetch($tbL134,$datasets,$sql_query,'array');

					if(is_array($sql_data)) {
						
						$noofcounts = 0;

						$logged_date=""; $room_discount=0; $bill_post=0; $total_discount=0; $total_billpost=0;
						$total_comsumption_tax=0; $total_vat=0; $total_service_charge=0; $total_other_charges=0;
						
						foreach($sql_data as $r_key => $r_value) {
							
							$noofcounts += 1;

							$total_vat = $total_vat + $r_value['tax_amount'];
							$total_comsumption_tax = $total_comsumption_tax + $r_value['consumption_tax_amount'];
							$total_service_charge = $total_service_charge + $r_value['service_charge'];
							$total_billpost = $total_billpost + $r_value['room_amount'];

							$print_bill_date = write_dateF($gh_get_date_format,$r_value['bill_date']);
							$print_bill_post = write_amountF($gh_get_decimal_format,$r_value['room_amount']);

							$room_prefix = idget_data($tbL56,$r_value['roomid'],'roomprefix');
							$room_number = idget_data($tbL56,$r_value['roomid'],'roomnumber');
							$block_id = idget_fdata($tbL56,'id',$r_value['roomid'],'blockid');
							$block_name = idget_data($tbL49,$block_id,'name');

							?>
								<tr>
									<td width="150px" align="center"><?php echo $print_bill_date; ?></td>
									<td width="250px" align="center"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</td>
									<td width="150px" align="center">&#8358; <?php echo $print_bill_post; ?></td>
									<td width="100px" align="center"><?php echo $r_value['day']; ?></td>
									<td width="150px" align="center">&#8358; <?php echo $print_bill_post; ?></td>
								</tr>
							<?php

							$room_prefix=""; $room_number=""; $block_id="";
						}

						$total_bill_amount = $total_billpost + $total_comsumption_tax + $total_vat + $total_service_charge;
						
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
		</div>
		<div class="block-element top-pull-30 ft-sml-size" align="left">
			<b class="nobold default-text-font-bold">Guest Signature & Date</b>
			<div class="cs-width-400 cs-height-40 box-border-thick-bottom"></div>
		</div>
	</div>
</div>

<div class="block-element top-pull-50 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 grey-theme sml-rounded-button anchor" onclick="window.print()">
</div>


<script>
	
	function removechild(obj) {
		chgclass(obj,'noshow');
	}

</script>