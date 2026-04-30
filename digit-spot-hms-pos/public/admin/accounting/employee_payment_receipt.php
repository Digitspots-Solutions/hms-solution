<?php
	$receipt_number = $ftoken;

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$sql = "SELECT * FROM {$tbL165} WHERE receipt_number='{$receipt_number}' AND deletedata=0";
	$dataset = wgetSQL($sql);

	$employee_name = idget_data($tbL7,$dataset[0]['staff'],'staffname');
	$collector = idget_data($tbL7,$dataset[0]['userid'],'staffname');
	$pay_type = idget_data($tbL24,$dataset[0]['payment_mode'],'name');

?>

<p class="bottom-pull-30 alignrt">
	<input type="button" value="Print" onclick="window.print()">
</p>

<div id="section-to-print" class="block-element" align="center">
	
	<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
	<h1 class="xlarge nobold default-text-font-bold alignrt"><?php echo _LONG_NAME; ?></h1>

	<div class="cs-height-30"></div>

	<h2 class="large nobold default-text-font-bold alignct"><u class="default-text-font-bold">Employee Payment Receipt</u></h2><br>

	<table cellpadding="3" cellspacing="1">
		<tr>
			<td class="default-text-font-bold">Transaction Date:</td>
			<td><?php echo date('d-m-y',strtotime($dataset[0]['transaction_date'])); ?></td>
			<td class="default-text-font-bold">Payment Receipt:</td>
			<td><?php echo $dataset[0]['receipt_number']; ?></td>
		</tr>
		<tr>
			<td class="default-text-font-bold">Employee Name:</td>
			<td><?php echo $employee_name; ?></td>
			<td class="default-text-font-bold">Purpose:</td>
			<td><?php echo $dataset[0]['bill_type']; ?></td>
		</tr>
		<tr>
			<td class="default-text-font-bold">Amount:</td>
			<td>&#8358; <?php echo number_format($dataset[0]['amount'],2); ?></td>
			<td class="default-text-font-bold">Fund Type:</td>
			<td><?php echo $pay_type; ?></td>
		</tr>
		<tr>
			<td class="default-text-font-bold">Description:</td>
			<td colspan="3"><?php echo $dataset[0]['detail']; ?></td>
		</tr>

		<tr>
			<td colspan="4" class="nobordercolor box-noborder">&nbsp;</td>
		</tr>

		<tr>
			<td class="default-text-font-bold nobordercolor box-noborder">Paid By <p>&nbsp;</p>-----------------------------<p class=" top-pull-3 alignlt"><?php echo $collector; ?></p></td>
			<td class="nobordercolor box-noborder"></td>
			<td class="nobordercolor box-noborder"></td>
			<td class="default-text-font-bold nobordercolor box-noborder">Cashier <p>&nbsp;</p>-----------------------------<p class=" top-pull-3 alignlt">Guest Service Agent (GSA)</p></td>
		</tr>
	</table>
	

</div>