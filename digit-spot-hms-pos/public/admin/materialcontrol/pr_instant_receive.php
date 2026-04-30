<?php
	
	//include "../../../includes/uom.php";
	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");
	
	if(isset($_SESSION['postoreid'])) { $toStore = " AND store={$_SESSION['postoreid']}"; }
	else { $toStore = ""; }

	$keywords = " AND receipt_status IN('Pending') AND var_status IN(3) AND var_approval='Yes'";

	#create table for batch
	createDatabasetable($var_tbl_116);
	
	$tbl = $mtbL8;

	//check if data exist in the table
	$query_po = "deletedata=0".$keywords;
	$po = mysqli_data_exist($tbl,$query_po);

?>

<div class="cs-height-30"></div>

<div class="pads30">
	<form action="" method="post" autocomplete="off" id="for-stock">
		<input type="hidden" name="uri" value="apply-pr-instant-receive">
		<input type="hidden" name="orderno" id="orderno">
		
		<div class="alignlt"><h3 class="large nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Here are list of approved PR to be received. Confirm and receive to add-up to stock</h3></div>

		<br><br>

		<?php

			if($po['isdata'] == true) {

				$isSql = "SELECT * FROM {$tbl} WHERE deletedata=0".$keywords." GROUP BY order_number ORDER BY id DESC";
				$wgt_po = idget_data($isSql);

				if(is_array($wgt_po) && count($wgt_po)) {
					foreach($wgt_po as $key => $val) {
						
						$query_stock = "SELECT * FROM {$tbl} WHERE order_number='{$val['order_number']}' AND deletedata=0";
						$wgt_stock = idget_data($query_stock);

						?>
						<span class="float-left top-pull-5">
							<h3 class="large nobold">for PR: <b class="nunito-bold blue-font"><?php echo $val['order_number']; ?></b></h3>
						</span>
						<span class="float-right">
							<input type="button" name="submitbutton" value="Receive Stock" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance" lang="<?php echo $val['order_number']; ?>" onclick="jsStock(this.lang)">
						</span>
						<span class="block-element new-line-space bottom-push-7">
						</span>

						<div class="x-scroll box-border-thick top-pull-5 right-pull-7 bottom-pull-5 left-pull-7 xsml-rounded-button bottom-push-20">
							<div class="nc-width-100">
								<table cellspacing="0" cellpadding="0">
									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Sn.</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Supplier</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Store</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Actual Qty</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Cost Price</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Total Amount</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Date/Time</td>
									</tr>

									<?php
										
										$numbr = 0; $total_amount = 0; $wget_store_name = "";
										
										foreach($wgt_stock as $key2 => $val2) {
											
											idget_global($val2['itemid'],$var_item);
											idget_global($val2['userid'],$var_user);
											idget_global($val2['supplierid'],$var_supplier);
											//idget_global($val2['store'],$var_store);

											if($val2['store'] == 0) {
												$wget_store_name = 'Warehouse';
											} else {
												idget_global($val2['store'],$var_store);
												$wget_store_name = $_gparams[$var_store]['returnval'];
											}

											if($val2['qty_received'] == 0) { $actual_qty = $val2['qty_ordered']; }
											elseif($val2['qty_received'] > 0) { $actual_qty = $val2['qty_received']; }
											
											$buyingUnit = arrayget_key($uoms,$val2['uom']);

											$numbr += 1;
						
											?>
												<tr>
													<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_supplier]['returnval']; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $wget_store_name; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $actual_qty.' '.$buyingUnit; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['unitprice']); ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo number_format($val2['order_net_amount']); ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
												</tr>
											<?php

											$buyingUnit = ""; $actual_qty = "";
										}
									?>

								</table>
							</div>
						</div>
							
						<?php
					}
				}

			} else {
				
				?>
					<div class="cs-height-50"></div>
					<div class="block-element" align="center">
						<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
							<span class="block-element nc-height-35"></span>
							<b class="mbri-pages ft-Lsize nobold"></b>
						</div>
						<h3 class="xlarge nobold dark-grey-font">No records found</h3>
						<!--<h3 class="xlarge nobold">If you're in inbox mode, please go to purchase request section to complete the transaction</h3>-->
					</div>
				<?php
			}
		?>
	</form>

</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function jsStock(order) {
		document.getElementById('orderno').value = order;
		setTimeout(() => { document.getElementById('for-stock').submit(); },1000);
	}

</script>