<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_; include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

	$userSignedIn = USER_AUTHEN_ID;
	include "../../../includes/hotel_profile.php";
	include "../../../includes/common_data_vars.php";

	$receipt = $_GET['getreceipt'];
	
	$attr = idget_data($tbL131,$receipt,'userid');
	$booking_number = idget_data($tbL131,$receipt,'booking_number');
	$wgt_booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
	$invoice_number = idget_data($tbL131,$receipt,'invoice_number');
	$receipt_number = idget_data($tbL131,$receipt,'receipt_number');
	$biller = idget_data($tbL131,$receipt,'biller');
	$sales_point = idget_data($tbL131,$receipt,'sales_point');
	$sales_description = idget_data($tbL131,$receipt,'sales_description');
	$customer = idget_data($tbL131,$receipt,'customerid');
	$amount = idget_data($tbL131,$receipt,'amount');
	$datelogged = idget_data($tbL131,$receipt,'datelogged');
	$timelogged = idget_data($tbL131,$receipt,'timelogged');

	$ttyp = idget_data($tbL131,$receipt,'transaction_type');
	
	$print_bill_date = write_dateF($gh_get_date_format,$datelogged).' ';
	$print_bill_date .= $timelogged;

	$amount = str_replace('-','',$amount);
	$print_bill_post = write_amountF($gh_get_decimal_format,$amount);

	if($wgt_booking_type == 'corporate' && isset($biller) && $biller > 0) {
		$thebiller = "Corporate/Spl Guest Name: ".idget_data($tbL58,$biller,'name');
	} else {
		$thebiller = "";
	}

	$guest_salutation = idget_data($tbL102,$customer,'salutation');

	$guest_name = idget_data($tbL42,$guest_salutation,'name').'. ';
	$guest_name .= idget_data($tbL102,$customer,'fname').' ';
	$guest_name .= idget_data($tbL102,$customer,'lname');

	$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
	$cashier = idget_data($tbL7,$attr,'staffname');

	if($ttyp == 'refund') { $receipt_title = "REFUND DETAIL"; }
	elseif($ttyp == 'coupon') { $receipt_title = "COUPON DETAIL"; }
	elseif($ttyp == 'rebate') { $receipt_title = "REBATE DETAIL"; }
	else { $receipt_title = "PAYMENT RECEIPT"; }
?>

<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>

<div id="section-to-print" class="block-element" align="center">
	<div class="cs-width-900 top-pull-30">
		<img src="<?php echo _FC_LOGO_Mx; ?>">
		<h1 class="large nobold default-text-font-bold alignct nomargin"><?php echo $hotel_name; ?></h1>
		<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
		<small class="block-element top-push-3 bottom-push-20">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></small>
		<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?></h4><br>
		<h2 class="xlarge nobold default-text-font-bold alignct"><?php echo $receipt_title; ?></h2>
		
		<div class="block-element top-push-15 alignlt">
			
			<span class="float-right">
				<h4 class="large nobold alignlt nomargin">Check-in / Check-out Time:</h4>
				<h4 class="large nobold alignlt default-text-font-bold"><?php echo $gh_checkin_time_hr_name; ?> / <?php echo $gh_checkout_time_hr_name; ?></h4>
			</span>
			<h4 class="large nobold alignlt nomargin">Booking Number: <b class="nobold default-text-font-bold"><?php echo $booking_number; ?></b></h4>
			<h4 class="large nobold alignlt">Receipt Number: <b class="nobold default-text-font-bold"><?php echo $receipt_number; ?></b></h4>
			<div class="cs-height-20"></div>
			<h3 class="large nobold default-text-font-bold alignlt"><?php echo $thebiller; ?></h3>
			<h3 class="large nobold nomargin alignlt"><?php echo $guest_name; ?></h3>

			<div class="block-element top-push-10 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size default-text-font-bold">
					<tr>
						<th width="150px" align="center">Date</th>
						<th width="100px" align="center">Receipt</th>
						<th width="100px" align="center">Transaction</th>
						<th width="300px" align="center">Description</th>
						<th width="150px" align="center">Amount</th>
					</tr>

					<tr>
						<td width="150px" align="center"><?php echo $print_bill_date; ?></td>
						<td width="100px" align="center"><?php echo $receipt_number; ?></td>
						<td width="100px" align="center"><?php echo ucfirst($sales_point); ?></td>
						<td width="300px" align="center"><?php echo $sales_description; ?></td>
						<td width="150px" align="center">&#8358;<?php echo $print_bill_post; ?></td>
					</tr>
				</table>
			</div>
			
			<br><br><br>
			
			<div class="float-left alignlt">
				<h4 class="xlarge nobold default-text-font-bold">Cashier Sign & Date</h4>
				<h4 class="large nobold"><?php echo $cashier.' '.$print_bill_date; ?></h4>
			</div>

			<div class="float-right alignrt">
				<h4 class="xlarge nobold default-text-font-bold">Guest Sign & Date</h4>
				<h4 class="large nobold"><?php echo $guest_name.' '.$print_bill_date; ?></h4>
			</div>

			<div class="block-element new-line-space">
			</div>

		</div>
	</div>
</div>

<div class="block-element top-pull-50 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="window.print()">
</div>