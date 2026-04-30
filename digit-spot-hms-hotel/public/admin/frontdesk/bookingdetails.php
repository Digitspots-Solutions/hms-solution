<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;
	
	$pay_query = array("booking_number"=>$booking_number,"deletedata"=>0);
	$chkPay = mysqli_data_checkr($tbL131,'(*)',$pay_query);
	if($chkPay == true) { $payStatus = "Confirmed"; }
	else { $payStatus = "Unconfirmed"; }

	include "booking_tokens.php";


	$room_lodge_query = array("booking_number"=>$booking_number,"status"=>"Reserved");
	$table_cols = "customerid,room_type_id,roomid,noofdays,adult,child,checkin_date,checkin_time,checkout_date,checkout_time,datelogged";
	$get_room_lodge_data = mysqli_data_fetch($tbL127,$table_cols,$room_lodge_query,'array');

	$queryset = "booking_number='{$booking_number}'";
	$room_amount = "SUM(room_amount)"; $discount = "SUM(discount)"; $tax = "SUM(tax_amount)";
	$consumption = "SUM(consumption_tax_amount)"; $service = "SUM(service_charge)";

	$get_room_amount = mysqli_arithmetic_data($tbL134,$room_amount,$queryset);
	$get_discount = mysqli_arithmetic_data($tbL134,$discount,$queryset);
	$get_tax = mysqli_arithmetic_data($tbL134,$tax,$queryset);
	$get_consumption = mysqli_arithmetic_data($tbL134,$consumption,$queryset);
	$get_service = mysqli_arithmetic_data($tbL134,$service,$queryset);

	$taxes = $get_tax + $get_consumption + $get_service;

	$amount_paid = "SUM(amount)";
	$py_queryset = "booking_number='{$booking_number}' AND ispaid=1";
	$get_amount_paid = mysqli_arithmetic_data($tbL131,$amount_paid,$py_queryset);

	$get_balance = $get_amount_paid - (($get_room_amount + $taxes) - $get_discount);
	
?>

<div id="section-to-print" class="block-element" align="center">
	<div class="cs-width-900">
		<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
		<h1 class="xlarge nobold default-text-font-bold alignrt"><?php echo _LONG_NAME; ?></h1>
		<h4 class="large nobold alignrt"><?php echo $hotel_address; ?></h4>
		<small class="block-element top-push-3 bottom-push-20 alignrt">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></small>
		
		<h3 class="xlarge nobold default-text-font-bold">&mdash; Booking Details &mdash;</h3>
		<div class="block-element top-push-15 alignlt">
			<h3 class="large nobold default-text-font-bold">Booking Number: <b class="nobold"><?php echo $booking_number; ?></b></h3>
			<h3 class="large nobold default-text-font-bold">Booking Status: <b class="nobold">Reserved (<?php echo $payStatus; ?>)</b></h3><h3 class="large nobold default-text-font-bold">Rate Type: <b class="nobold">Rack Rate</b></h3>
		</div>
		<div class="block-element top-push-30 alignlt">
			<h4 class="large nobold default-text-font-bold alignlt">Customer</h4>
			<div class="block-element top-push-3 bottom-push-15 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size default-text-font-bold">
					<tr>
						<th width="300px" align="center">Name</th>
						<th align="center">Phone Number</th>
						<th align="center">Email</th>
					</tr>
					<tr>
						<td width="300px" align="center"><?php echo $guest_account_name; ?></td>
						<td align="center"><?php echo $get_guest_detail[6]; ?></td>
						<td align="center"><?php echo $get_guest_detail[7]; ?></td>
					</tr>
				</table>
			</div>
			<h4 class="large nobold default-text-font-bold alignlt">Booking</h4>
			<div class="block-element top-push-3 bottom-push-15 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size default-text-font-bold">
					<tr>
						<th align="center">Guest Name</th>
						<th align="center">Stay Period</th>
						<th align="center">No of Days</th>
						<th align="center">Room Type</th>
						<th align="center">Room Nos</th>
						<th align="center">Adult/Child</th>
						<th align="center">Booked Date</th>
					</tr>
					<?php
						if(is_array($get_room_lodge_data)) {
							
							$customer_name = ""; $print_chkindate = "";  $print_chkintime = "";
							$print_chkoutdate = "";  $print_chkoutdate = ""; $print_date = "";
							$room_type = "";
							
							foreach ($get_room_lodge_data as $key => $val) {
								
								$customer_name = idget_data($tbL102,$val['customerid'],'fname').' ';
								$customer_name .= idget_data($tbL102,$val['customerid'],'lname');

								$print_chkindate = write_dateF($gh_get_date_format,$val['checkin_date']);
								//$print_chkintime = write_dateF($gh_get_time_format,$val['checkin_time']);

								$print_chkoutdate = write_dateF($gh_get_date_format,$val['checkout_date']);
								//$print_chkouttime = write_dateF($gh_get_time_format,$val['checkout_time']);

								$print_date = write_dateF($gh_get_date_format,$val['datelogged']);
								$room_type = idget_data($tbL52,$val['room_type_id'],'name');
								
								?>
									<tr>
										<td align="center"><?php echo $customer_name; ?></td>
										<td align="center"><?php echo $print_chkindate.' '.$val['checkin_time'].' to '.$print_chkoutdate.' '.$val['checkout_time']; ?></td>
										<td align="center"><?php echo $val['noofdays']; ?></td>
										<td align="center"><?php echo $room_type; ?></td>
										<td align="center">****</td>
										<td align="center"><?php echo $val['adult'].'/'.$val['child']; ?></td>
										<td align="center"><?php echo $print_date; ?></td>
									</tr>
								<?php
							}
						}
					?>
				</table>
			</div>
			<h4 class="large nobold default-text-font-bold alignlt">Bill & Payment</h4>
			<div class="block-element top-push-3 bottom-push-30 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size default-text-font-bold">
					<tr>
						<th align="center">Room Rate</th>
						<th align="center">Discount</th>
						<th align="center">Commission</th>
						<th align="center">Tax</th>
						<th align="center">Amount Paid</th>
						<th align="center">Balance Amount</th>
					</tr>
					<tr>
						<td align="center">&#8358; <?php echo write_amountF($gh_get_decimal_format,$get_room_amount); ?></td>
						<td align="center">&#8358; <?php echo write_amountF($gh_get_decimal_format,$get_discount); ?></td>
						<td align="center">&#8358; 0.00</td>
						<td align="center">&#8358; <?php echo write_amountF($gh_get_decimal_format,$taxes); ?></td>
						<td align="center">&#8358; <?php echo write_amountF($gh_get_decimal_format,$get_amount_paid); ?></td>
						<td align="center">&#8358; <?php echo write_amountF($gh_get_decimal_format,$get_balance); ?></td>
					</tr>
				</table>
			</div>

			<h3 class="large nobold default-text-font-bold">Terms & Conditions</h3>
			<h4 class="large nobold"><?php echo $hotel_terms; ?></h4>

			<br><br>

			<h3 class="large nobold default-text-font-bold">Other Information</h3>
			<h4 class="large nobold"><?php echo $hotel_otherinfo; ?></h4>

			<div class="block-element top-push-30 alignrt">
				<h4 class="large nobold">Customer Sign & Date</h4>
			</div>

		</div>
	</div>
</div>
<div class="block-element top-pull-50 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="window.print()">
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

</script>