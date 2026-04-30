<?php
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$receipt_id = $ftoken;

	$iou_number = idget_data($tbL153,$receipt_id,'iou_no');
	$pr_number = idget_data($tbL153,$receipt_id,'pr_no');
	$wrdate = idget_data($tbL153,$receipt_id,'datelogged');

	$iou_amount = idget_data($tbL153,$receipt_id,'amount');
	$pr_amount = idget_data($tbL153,$receipt_id,'pr_amount');
	$vr_amount = idget_data($tbL153,$receipt_id,'variance_amount');

	if(!empty($pr_number)) {
		$iou_disburser = idget_fdata($tbL151,'subject',$pr_number,'user_one');
		$disburser_name = idget_data($tbL7,$iou_disburser,'staffname');
	} else {
		$iou_disburser = idget_data($tbL153,$receipt_id,'disbursedby');
		$disburser_name = idget_data($tbL7,$iou_disburser,'staffname');
	}

	$iou_retire = idget_data($tbL153,$receipt_id,'retiredby');
	$retire_name = idget_data($tbL7,$iou_retire,'staffname');

	$query_pr = "SELECT * FROM {$tbL121} WHERE order_number='{$pr_number}' AND deletedata=0";
	$wgt_pr = mysqli_data_array('assoc',$query_pr);

?>
<p class="bottom-pull-30 alignrt">
	<a href="javascript:void(0)" class="blue-font ft-sml-size default-text-font-bold" onclick="window.print()">Print <b class="fa-print nobold left-push-5"></b></a>
</p>
<div id="section-to-print" class="block-element">
	
	<div class="bottom-push-30">
		<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
		<h1 class="large nobold default-text-font-bold alignrt"><?php echo _LONG_NAME; ?></h1>
	</div>
	<div class="cs-height-50">
	</div>
	
	
	<h2 class="xlarge nobold default-text-font-bold alignct">IOU RETIREMENT RECEIPT</h2><br>

	<?php if(!empty($pr_number)): ?>

	<div class="top-push-30 bottom-push-30">
		<table cellspacing="0" cellpadding="3">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Quantity</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Unit Price</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Amount</td>
			</tr>

			<?php
				if(is_array($wgt_pr) && count($wgt_pr) > 0) {
					foreach($wgt_pr as $key => $val) {

						idget_global($val['itemid'],$var_item);
						idget_global($val['userid'],$var_user);
				
						$buyingUnit = arrayget_key($uoms,$val['uom']);

						?>
							<tr>
								<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
								<td class="right-pull-10 left-pull-10"><?php echo $val['qty_ordered'].' '.$buyingUnit; ?></td>
								<td class="right-pull-10 left-pull-10"><?php echo number_format($val['unitprice'],2); ?></td>
								<td class="right-pull-10 left-pull-10"><?php echo number_format($val['order_net_amount'],2); ?></td>
							</tr>
						<?php
					}
				}
			?>
			
		</table>
	</div>

	<?php
		else:
			$iou_type = idget_fdata($tbL161,'iou_no',$iou_number,'expense_type');
			$iou_type_detail = idget_fdata($tbL161,'iou_no',$iou_number,'detail');
	?>

	<div class="top-push-30 bottom-push-30">
		<table cellspacing="0" cellpadding="3">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Particular</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Amount</td>
			</tr>

			<tr>
				<td class="right-pull-10 left-pull-10"><?php echo $iou_type.': '.$iou_type_detail; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo number_format($iou_amount,2); ?></td>
			</tr>
			
		</table>
	</div>

	<?php endif; ?>


	<table cellspacing="5" cellpadding="5">
		<tr>
			<td class="right-pull-10 left-pull-10">
				<table cellspacing="0" cellpadding="0">
					<tr><td class="default-text-font-bold box-noborder">Amount Disbursed:</td><td class="box-noborder">&#8358;<?php echo number_format($iou_amount,2); ?></td></tr>
					<tr><td class="default-text-font-bold box-noborder">Amount Spent:</td><td class="box-noborder">&#8358;<?php echo number_format($pr_amount,2); ?></td></tr>
					<tr><td class="default-text-font-bold box-noborder">Balance Due:</td><td class="box-noborder">&#8358;<?php echo number_format($vr_amount,2); ?></td></tr>
				</table>
			</td>
			<td class="right-pull-10 left-pull-10">
				<table cellspacing="0" cellpadding="0">
					<tr><td class="default-text-font-bold box-noborder">Date:</td><td class="box-noborder"><?php echo date('d-m-Y',strtotime($wrdate)); ?></td></tr>
					<tr><td class="default-text-font-bold box-noborder">IOU:</td><td class="box-noborder"><?php echo $iou_number; ?></td></tr>
					<tr><td class="default-text-font-bold box-noborder">PR:</td><td class="box-noborder"><?php echo $pr_number; ?></td></tr>
				</table>
			</td>
		</tr>
	</table>

	<div class="cs-height-30">
	</div>

	<table cellspacing="5" cellpadding="5">
		<tr><td class="default-text-font-bold box-noborder">PURCHASING MANAGER</td><td class="default-text-font-bold box-noborder">PAY MASTER</td></tr>
		<tr>
			<td class="box-noborder"><?php //echo $disburser_name; ?><p class="top-pull-20">-------------------------------------------</p></td>
			<td class="box-noborder"><?php //echo $retire_name; ?><p class="top-pull-20">-------------------------------------------</p></td>
		</tr>
	</table>

</div>