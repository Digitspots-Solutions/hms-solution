<?php
include "includes/initialize_session.php";
include "includes/config.php";

include "../../../includes/uom.php";
include "../../../includes/hotel_profile_alt.php";

define ("_LONG_NAME",$hotel_name);

$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

$order_number = isset($_GET['pr']) ? $_GET['pr'] : 0;

$get_store = idget_fname($order_number,'order_number','store',$tbL121);
$get_store_type = idget_fname($order_number,'order_number','store_type',$tbL121);
$get_invoice_number = idget_fname($order_number,'order_number','invoice_number',$tbL121);
$get_invoice_number = ($get_invoice_number == 'Unknown') ? $order_number : $get_invoice_number;

if($get_store_type == 'Outlets') {
	$store_name = idget_name($get_store,'posname',$tbL14);
} elseif($get_store_type == 'Virtual Stores') {
	$store_name = idget_name($get_store,'store_name',$tbL123);
}

$order_date = idget_fname($order_number,'order_number','order_date',$tbL121);
$delivery_date = idget_fname($order_number,'order_number','delivery_date',$tbL121);
$delivery_note = idget_fname($order_number,'order_number','delivery_note',$tbL121);

$query_po = "SELECT SUM(order_net_amount) AS 'totalpramt' FROM {$tbL121} WHERE order_number='{$order_number}' AND deletedata=0";
$wgt_po = idget_data($query_po);

?>

<script type="text/javascript" src="css3.0/flexcroll.js"></script>
<link rel="stylesheet" href="css3.0/default.css"/>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jspath.js"></script>
<script src="js/jsfx.js"></script>
<script src="js/index.js"></script>
<script src="js/all.js"></script>

<p class="bottom-pull-30 alignrt">
	<a href="javascript:void(0)" class="blue-font ft-sml-size default-text-font-bold right-push-15" onclick="window.print()">Print <b class="fa-print nobold left-push-5"></b></a> <a href="javascript:void(0)" class="dark-grey-font ft-sml-size" onclick="window.parent.location.reload(true)">Close <b class="mbri-close left-push-5"></b></a>
</p>
<div id="section-to-print" class="block-element">

	<div class="bottom-push-30">
		<span class="float-left"><img src="<?php echo _LOGO_URL; ?>"></span>
		<h1 class="large nobold default-text-font-bold alignrt"><?php echo _LONG_NAME; ?></h1>
	</div>
	<div class="cs-height-50">
	</div>
	
	<span class="float-right"><h2 class="large nobold default-text-font-bold">LPO: <?php echo $get_invoice_number; ?></h2></span>
	<h2 class="large nobold default-text-font-bold">PURCHASE ORDER - <?php echo $order_number.' ('.$store_name.')'; ?></h2>

	<div class="top-push-30 bottom-push-30">
		<table cellspacing="5" cellpadding="5">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Date</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Note</td>
			</tr>
			<tr>
				<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($delivery_date)); ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo $delivery_note; ?></td>
			</tr>
		</table>
	</div>

	<?php

		$query_pox = "SELECT * FROM {$tbL121} WHERE order_number='{$order_number}' AND deletedata=0 GROUP BY supplierid,order_number";
		$wgt_pox = idget_data($query_pox);

		if(is_array($wgt_pox) && count($wgt_pox)) {
			foreach($wgt_pox as $key => $val) {
				
				idget_global($val['supplierid'],$var_supplier);
				idget_global($val['store'],$var_store);

				$query_supplier = "SELECT * FROM {$tbL121} WHERE supplierid={$val['supplierid']} AND order_number='{$val['order_number']}' AND deletedata=0"; $wgt_supplier = idget_data($query_supplier);

				?>
					<div class="box-border-thick xsml-rounded-button pads20 bottom-push-20">
						
						<h3 class="large nobold">Supplier: <?php echo $_gparams[$var_supplier]['returnval']; ?><b class="fa-arrow-right left-push-10"></b></h3>
						
						<div class="bottom-pull-10">
							<div class="nc-width-100">
								<table cellspacing="0" cellpadding="0">
									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Sn.</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Quantity</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Unit Price</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Amount</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Prepared By</td>
									</tr>

									<?php
										
										$numbr = 0; $total_amount = 0;
										
										foreach($wgt_supplier as $key2 => $val2) {
											
											$numbr += 1;

											idget_global($val2['itemid'],$var_item);
											idget_global($val2['userid'],$var_user);
											
											$buyingUnit = arrayget_key($uoms,$val2['uom']);
											$total_amount = $total_amount + $val2['order_net_amount'];
											
											?>
												<tr>
													
													<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_ordered'].' '.$buyingUnit; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['unitprice'],2); ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['order_net_amount'],2); ?></td>
													
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_user]['returnval']; ?></td>
													
												</tr>
											<?php

											$buyingUnit = ""; $order_stat = ""; $order_stat_color = "";
										}
									?>

									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
										<td class="yellow-theme nunito-bold right-pull-10 left-pull-10 alignrt">&#8358;</td>
										<td class="yellow-theme nunito-bold right-pull-10 left-pull-10"><?php echo number_format($total_amount,2); ?></td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">&nbsp;</td>
									</tr>

								</table>
							</div>
						</div>
					</div>
				<?php
			}
		}

	?>

	<div class="cs-height-50">
	</div>

	<h3 class="large nobold default-text-font-bold">Approvals</h3><br>

	<?php
		
		$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$order_number}' AND approval_type='PR'";
		$jpL = idget_data($isjp);

		if(is_array($jpL) && count($jpL)) {
			
			?>
				<div class="bottom-push-10 alignlt">
					<ul class="nolist">
						<li class="ln-display-box float-left nc-width-20 right-pull-10">
							<?php
								if($jpL[0]['user_one']) {
									$userone = idget_name($jpL[0]['user_one'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_one'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af1_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_one']);
									$af1_status_color = wgtcolor($af1_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userone; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left nc-width-20 right-pull-10">
							<?php
								if($jpL[0]['user_two']) {
									$usertwo = idget_name($jpL[0]['user_two'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_two'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af2_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_two']);
									$af2_status_color = wgtcolor($af2_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $usertwo; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left nc-width-20 right-pull-10">
							<?php
								if($jpL[0]['user_three']) {
									$userthree = idget_name($jpL[0]['user_three'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_three'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af3_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_three']);
									$af3_status_color = wgtcolor($af3_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userthree; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left nc-width-20 right-pull-10">
							<?php
								if($jpL[0]['user_four']) {
									$userfour = idget_name($jpL[0]['user_four'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_four'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af4_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_four']);
									$af4_status_color = wgtcolor($af4_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfour; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left nc-width-20 right-pull-10">
							<?php
								if($jpL[0]['user_five']) {
									$userfive = idget_name($jpL[0]['user_five'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_five'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af5_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_five']);
									$af5_status_color = wgtcolor($af5_approval_signed_status);
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfive; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
									<?php
								}
							?>
						</li>
						<li class="block-element new-line-space">
						</li>
					</ul>
				</div>
			<?php
		}

		unset($_GET['pr']);

	?>
</div>