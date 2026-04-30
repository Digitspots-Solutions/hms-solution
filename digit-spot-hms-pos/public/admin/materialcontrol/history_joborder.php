<?php
include "includes/initialize_session.php";
include "includes/config.php";
include "../../../includes/uom.php";

$userSignedIn = $_SESSION['authenticate_id'];

$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");
$order_number = isset($_GET['pr']) ? $_GET['pr'] : 0;

$supplier = idget_fname($order_number,'order_number','supplierid',$tbL121);
idget_global($supplier,$var_supplier);

?>

<script type="text/javascript" src="css3.0/flexcroll.js"></script>
<link rel="stylesheet" href="css3.0/default.css"/>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jspath.js"></script>
<script src="js/jsfx.js"></script>
<script src="js/index.js"></script>
<script src="js/all.js"></script>


<div class="pads10">
	<span class="float-right"><a href="javascript:void(0)" class="blue-font ft-sml-size right-push-10" onclick="window.print()">Print <b class="mbri-print left-push-5"></b></a> <a href="javascript:void(0)" class="dark-grey-font ft-sml-size" onclick="window.parent.location.reload(true)">Close <b class="mbri-close left-push-5"></b></a></span>

	<div id="section-to-print">
		<h2 class="xlarge nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
		<h3 class="large nobold default-text-font-bold">SUPPLIER: <?php echo $_gparams[$var_supplier]['returnval']; ?> &nbsp;&nbsp; JOB ORDER NO.: <?php echo $order_number; ?></h3>
		
		<div class="cs-height-10">
		</div>

		<h4 class="large nobold alignct red-font">ORDER DELIVERY BATCHES</h4><br>
		<div class="nc-width-100">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td class="default-text-font-bold right-pull-10 left-pull-10">SN.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Batch No.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Received</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Total Cost</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Remaining</td>
					<!--<td class="default-text-font-bold right-pull-10 left-pull-10">Estimated Delivery Date</td>-->
					<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Date</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Received By</td>
				</tr>

				<?php

					$query_batch = "SELECT batch_no FROM {$mtbL25} WHERE pr_no='{$order_number}' AND deletedata=0 GROUP BY batch_no";
					$wgt_batch = idget_data($query_batch);
					
					if(is_array($wgt_batch) && count($wgt_batch) > 0) {
						
						foreach($wgt_batch as $key => $val) {	
							
							$query_jo = "SELECT * FROM {$mtbL25} WHERE pr_no='{$order_number}' AND batch_no={$val['batch_no']}";
							$wgt_jo = idget_data($query_jo);

							$numbr = 0; $total_cost = 0;

							foreach($wgt_jo as $key2 => $val2) {

								$numbr += 1;

								idget_global($val2['itemid'],$var_item);
								$get_uoms = idget_name($val2['itemid'],'buying_unit',$mtbL5);
								$buyingUnit = arrayget_key($uoms,$get_uoms);

								idget_global($val2['receivedby'],$var_user);

								$total_cost = $total_cost + $val2['total_amount'];

								?>
									<tr>
										<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?>.</td>
										<td class="right-pull-10 left-pull-10"><?php echo $val['batch_no']; ?>.</td>
										<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_received'].' '.$buyingUnit; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['total_amount'],2); ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_left'].' '.$buyingUnit; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo date('d/m/Y',strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
									</tr>
								<?php
							}

							?>
									<tr>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
										<td class="right-pull-10 left-pull-10">Total:</td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold"><?php echo number_format($total_cost,2); ?></td>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
										<td class="right-pull-10 left-pull-10">&nbsp;</td>
									</tr>

								<tr><td colspan="9" class="grey-theme box-noborder">&nbsp;</td></tr>
							<?php
						}

					} else {
						?>
							<tr><td colspan="9" class="alignct box-noborder"><p class="pads15">No history found</p></td></tr>
						<?php
					}
				?>

			</table>
		</div>
	</div>

</div>