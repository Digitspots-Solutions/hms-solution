<?php
	$receipt_id = $ftoken;
	$ths_token = $stoken;

	$collectorid = idget_data($tbL63,$receipt_id,'userid');
	$collector = idget_data($tbL7,$collectorid,'staffname');

	$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
	$receipt_number = idget_data($tbL63,$receipt_id,'transaction_number');
	$transaction_date = idget_data($tbL63,$receipt_id,'transaction_date');
	$transaction_time = idget_data($tbL63,$receipt_id,'timelogged');
	$transaction_date = date('d-m-Y',strtotime($transaction_date));
	$transaction_amount = idget_data($tbL63,$receipt_id,'amount');
	$transaction_detail = idget_data($tbL63,$receipt_id,'detail');
	$paymode = idget_data($tbL63,$receipt_id,'paymode');
	$paymode_name = idget_data($tbL24,$paymode,'name');
	$receipt_title = "PAYMENT RECEIPT";

	$cspg = idget_data($tbL63,$receipt_id,'cspgid');
	$thebiller = "Corporate/Spl Guest Name: ".idget_data($tbL58,$cspg,'name');
?>

<p class="top-pull-10 alignrt">
	<input type="button" value="Print" class="anchor" onclick="window.print()">
</p>

<div id="section-to-print" class="block-element" align="center">
	<div class="top-pull-30">
		<img src="<?php echo _FC_LOGO_Mx; ?>">
		<h1 class="large nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h1>
		<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
		<small class="block-element top-push-3 bottom-push-10">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></small>
		<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?></h4><br>
		<h2 class="xlarge nobold default-text-font-bold alignct"><?php echo $receipt_title; ?></h2>
		
		<div class="block-element top-push-15 alignlt">
			
			<div class="cs-height-20"></div>
			<span class="float-right"><h3 class="large nobold alignlt">Pay Type:<br><b class="nobold default-text-font-bold"><?php echo $paymode_name; ?></b></h3></span>
			<h3 class="large nobold alignlt">Transaction Number: <?php echo $receipt_number; ?></h3>
			<h3 class="large nobold default-text-font-bold alignlt"><?php echo $thebiller; ?></h3>
			
			<div class="block-element top-push-10 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
					<tr>
						<th width="50px" align="center">S/No.</th>
						<th width="350px" align="center">Description</th>
						<th width="150px" align="center">Amount</th>
						<th width="150px" align="center">Transaction Type</th>
					</tr>

					<tr>
						<td width="50px" align="center">1.</td>
						<td width="350px" align="center"><?php echo $transaction_detail; ?></td>
						<td width="150px" align="right"><?php echo number_format($transaction_amount,2); ?></td>
						<td width="150px" align="center">Credit</td>
					</tr>
				</table>
			</div>
			
			<br><br><br>
			
			<div class="float-left alignlt">
				<h4 class="large nobold">Cashier Signature</h4>
				<h4 class="large nobold default-text-font-bold top-pull-3"><?php echo $collector; ?></h4>
				<h4 class="large nobold"><?php echo $transaction_date.'. '.$transaction_time; ?></h4>
			</div>

			<div class="float-right alignct">
				<h4 class="large nobold">--------------------------------------</h4>
				<h4 class="large nobold default-text-font-bold top-pull-3">Guest Signature</h4>
			</div>

			<div class="block-element new-line-space">
			</div>

		</div>
	</div>
</div>