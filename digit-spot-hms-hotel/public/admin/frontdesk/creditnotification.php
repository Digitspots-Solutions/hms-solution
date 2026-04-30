<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$wgt_cur_room_id = $ths_token;
	$room_prefix = idget_data($tbL56,$wgt_cur_room_id,'roomprefix');
	$room_number = idget_data($tbL56,$wgt_cur_room_id,'roomnumber');
	$block_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'blockid');
	$block_name = idget_data($tbL49,$block_id,'name');
	
	include "booking_tokens.php";
	//$customer_credit = str_replace('-', '', $wgt_balance);

	$credit_sql = "SUM(balance_amount)";
	$credit_query = "booking_number='{$booking_number}' AND status=0";
	$guest_credit = mysqli_arithmetic_data($tbL138,$credit_sql,$credit_query);
?>
<div id="section-to-print" class="block-element">
	<div class="block-element pads20 cs-width-350 alignct">
		<span class="block-element box-border-thick-bottom bottom-pull-15 bottom-push-30">
			<h1 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h1>
			<h3 class="large nobold nomargin"><?php echo $hotel_address; ?></h3>
			<h3 class="large nobold nomargin"><?php echo _CITY.', '._STATE; ?></h3>
			<h3 class="large nobold">Tel: <?php echo $hotel_fs_phonenumber; ?>. Email: <?php echo $hotel_email; ?></h3>
		</span>
		<h2 class="large nobold default-text-font-bold">Credit Notification</h2>
		<span class="block-element box-border-thick-bottom bottom-pull-15 top-push-10 bottom-push-30">
			<ul class="nolist">
				<li class="bottom-pull-10 ft-sml-size">
					<span class="ln-display-box float-left">Booking No</span>
					<span class="ln-display-box float-right"><?php echo $booking_number; ?></span>
					<span class="block-element new-line-space"></span>
				</li>
				<li class="bottom-pull-10 ft-sml-size">
					<span class="ln-display-box float-left">Checkout Date/Time</span>
					<span class="ln-display-box float-right"><?php echo $print_wgt_checkout_date.' '.$print_wgt_checkout_time; ?></span>
					<span class="block-element new-line-space"></span>
				</li>
				<li class="bottom-pull-10 ft-sml-size">
					<span class="ln-display-box float-left">Guest Name</span>
					<span class="ln-display-box float-right"><?php echo $guest_account_name; ?></span>
					<span class="block-element new-line-space"></span>
				</li>
				<li class="bottom-pull-10 ft-sml-size">
					<span class="ln-display-box float-left">Room No</span>
					<span class="ln-display-box float-right"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</span>
					<span class="block-element new-line-space"></span>
				</li>
				<li class="bottom-pull-20 ft-sml-size">
					<span class="ln-display-box float-left">Booking Type</span>
					<span class="ln-display-box float-right"><?php echo ucfirst($wgt_booking_type); ?></span>
					<span class="block-element new-line-space"></span>
				</li>
			</ul>
		</span>
		<span class="block-element">
			<ul class="nolist">
				<li class="ft-sml-size">
					<b class="nobold default-text-font-bold">Payment done completely.</b><br>
					Thank you for choosing to stay in our hotel<br>
					Your amount (<b class="nobold default-text-font-bold">&#8358;<?php echo write_amountF(2,$guest_credit); ?></b>) is with us. You can still extend your stay if you want
				</li>
			</ul>
		</span>
	</div>
</div>

<p class="top-pull-30 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 dark-black-white-state rounded-button anchor" onclick="window.print()">
</p>