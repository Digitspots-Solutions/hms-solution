<div id="prqx" class="fx-position-flow fscr zind-3 motion noscroll" align="center">
	<div class="cs-height-100"></div>
	<div class="fx-width-90 white-theme right-push-20 left-push-20 xsml-rounded-button obj-light-shadow box-border-thick motion noscroll" align="left">
		<div class="cs-height-50 box-border-thick-bottom pads15 obj-light-shadow">
			<span class="float-right"><a href="javascript://" class="black-font" onclick="chgclass('prqx','fx-position-rel cs-height-0 motion noscroll')"><b class="mbri-close"></b></a></span><span onclick=""><h3 class="large nobold default-text-font-bold nomargin">Unfinished Purchase Order</h3></span>
		</div>
		<div class="cs-height-600 pads20 y-scroll">
			<?php
				if(isset($_GET['po']) && !empty($_GET['po'])) {

					$tpo = escape_data($_GET['po']);

					$queryx = array("order_number"=>$tpo,"gstat"=>"Pending");
					$podata = "order_number,supplierid,store,datelogged,order_status,order_total_amount,order_tax_amount,order_net_amount,itemid,uom,delivery_date,delivery_note,unitprice,qty_ordered";
					$wgpo = mysqli_data_fetch($tbL121,$podata,$queryx,'array');

					$supplier = idget_fdata($tbL114,'order_number',$tpo,'supplierid');
					$store = idget_fdata($tbL114,'order_number',$tpo,'store');
					$datelogged = idget_fdata($tbL114,'order_number',$tpo,'datelogged');

					$wg_supplier = idget_data($tbL114,$supplier,'supplier_name');
					$wg_store = idget_data($tbL123,$store,'store_name');
					$order_date = write_dateF($gh_get_date_format,$datelogged);

					$wgtd = "";

					
					if(is_array($wgpo)) {
						$isNumbr = 0;
						foreach($wgpo as $key => $val) {
							$isNumbr += 1;
							$wguom = arrayget_key($uoms,$val['uom']);
							$price = $val['unitprice'] * $val['qty_ordered'];
							$wgtd .= '<tr>';
							$wgtd .= '<td width="30px" align="center">'.$isNumbr.'</td>';
							$wgtd .= '<td width="200px" align="center">'.idget_data($tbL118,$val['itemid'],'item').'</td>';
							$wgtd .= '<td width="100px" align="center">'.write_amountF($gh_get_decimal_format,$val['unitprice']).'</td>';
							$wgtd .= '<td width="200px" align="center">'.$val['qty_ordered'].' '.$wguom.'</td>';
							$wgtd .= '<td width="150px" align="center">'.write_amountF($gh_get_decimal_format,$price).'</td>';
							$wgtd .= '</tr>';

							$wguom = ""; $price = "";
						}

						$wgtd .= '<tr><td colspan="5">&nbsp;</td></tr>';
						$wgtd .= '<tr><td colspan="2" rowspan="4"><h4 class="xlarge nobold default-text-font-bold">Deliver Note:</h4>'.$wgpo[0]['delivery_note'].'</td><td>&nbsp;</td></tr>';
						//$wgtd .= '<tr>';
						$wgtd .= '<tr><td colspan="4" align="right">Sub Total: &#8358;'.write_amountF($gh_get_decimal_format,$wgpo[0]['order_total_amount']).'</td></tr>';
						$wgtd .= '<tr><td colspan="4" align="right">Taxes: &#8358;'.write_amountF($gh_get_decimal_format,$wgpo[0]['order_tax_amount']).'</td></tr>';
						$wgtd .= '<tr><td colspan="4" align="right">Grand Total: &#8358;'.write_amountF($gh_get_decimal_format,$wgpo[0]['order_net_amount']).'</td></tr>';
						//$wgtd .= '</tr>';
					}
					

					?>
						<div id="section-to-print" class="block-element" align="center">
							<div class="fx-width-50 bottom-push-50">
								<img src="<?php echo _FC_LOGO_Mx; ?>">
								<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
								<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
								<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
								<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>

								<div class="cs-height-30"></div>
								<h2 class="large nobold">Purchase Order Preview</h2>
							</div>
							<span class="ln-display-box float-left nc-width-5">
								&nbsp;
							</span>
							<span class="ln-display-box float-left nc-width-30 alignlt">
								<h3 class="large nobold default-text-font-bold">Supplier Details</h3>
								<h3 class="large nobold"><?php echo $wg_supplier; ?></h3>
							</span>
							<span class="ln-display-box float-left nc-width-30 alignlt">
								<h3 class="large nobold default-text-font-bold">Store Details</h3>
								<h3 class="large nobold"><?php echo $wg_store; ?></h3>
							</span>
							<span class="ln-display-box float-left nc-width-30 alignlt">
								<h3 class="large nobold">Date: <?php echo $order_date; ?></h3>
								<h3 class="large nobold">Order No#: <?php echo $order_no; ?></h3>
								<h3 class="large nobold">Status: <b id="wstat" class="light-red-font">Pending</b></h3>
								<h3 class="large nobold">Created By: <?php echo $admin_name; ?></h3>
							</span>
							<span class="ln-display-box float-left nc-width-5">
								&nbsp;
							</span>
							<span class="block-element new-line-space">
							</span>
						
							<div class="cs-height-100"></div>

							<div class="block-element sml-rounded-button noscroll">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<th width="30px" align="center"></th>
										<th width="200px" align="center">Item</th>
										<th width="100px" align="center">Unit Cost</th>
										<th width="200px" align="center">Quantity</th>
										<th width="150px" align="center">Amount</th>
									</tr>
									<?php
										echo $wgtd;
									?>
								</table>
							</div>
						</div>
					<?php

				} else {
					
					$additionalQuery = " GROUP BY order_number";
					$podata = "order_number,supplierid,store,datelogged,order_status";
					//order_total_amount,order_tax_amount,order_net_amount
					$wgpo = mysqli_data_fetch($tbL121,$podata,$query,'array');
					
					?>

					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<span class="float-right">
							<input type="submit" name="continuebutton" value="Continue" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor right-push-10">
							<input type="submit" name="deletebutton" value="Delete" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state sml-rounded-button anchor">
						</span>
						<span class="block-element new-line-space cs-height-20">
						</span>

						<table cellpadding="0" cellspacing="0">
							<tr>
								<th align="center">&nbsp;</th>
								<th align="center">Order No.</th>
								<th align="center">Supplier</th>
								<th align="center">Store</th>
								<th align="center">Logged Date</th>
								<th align="center">Order Status</th>
								<th align="center">Total Amount</th>
								<th align="center">Tax Amount</th>
								<th align="center">Grand Amount</th>
							</tr>
							<?php
								
								$wg_supplier = ""; $wg_store = ""; $order_date = "";

								foreach($wgpo as $key => $val) {
									
									$wg_supplier = idget_data($tbL114,$val['supplierid'],'supplier_name');
									$wg_store = idget_data($tbL123,$val['store'],'store_name');
									$order_date = write_dateF($gh_get_date_format,$val['datelogged']);

									$sqlx1 = "SUM(order_total_amount)";
									$queryx1 = "order_number='".$val['order_number']."' AND deletedata=0";
									$total_amt = mysqli_arithmetic_data($tbL121,$sqlx1,$queryx1);

									$sqlx2 = "SUM(order_tax_amount)";
									$queryx2 = "order_number='".$val['order_number']."' AND deletedata=0";
									$tax_amt = mysqli_arithmetic_data($tbL121,$sqlx2,$queryx2);

									$sqlx3 = "SUM(order_net_amount)";
									$queryx3 = "order_number='".$val['order_number']."' AND deletedata=0";
									$net_amt = mysqli_arithmetic_data($tbL121,$sqlx3,$queryx3);

									?>
										<tr>
											<td align="center"><input type="checkbox" name="checkers[]" value="<?php echo $val['order_number']; ?>"></td>
											<td align="center"><a href="?logs=<?php echo $logs; ?>&po=<?php echo $val['order_number']; ?>" class="blue-font"><?php echo $val['order_number']; ?></a></td>
											<td align="center"><?php echo $wg_supplier; ?></td>
											<td align="center"><?php echo $wg_store; ?></td>
											<td align="center"><?php echo $order_date; ?></td>
											<td align="center"><?php echo $val['order_status']; ?></td>
											<td align="center"><?php echo write_amountF($gh_get_decimal_format,$total_amt); ?></td>
											<td align="center"><?php echo write_amountF($gh_get_decimal_format,$tax_amt); ?></td>
											<td align="center"><?php echo write_amountF($gh_get_decimal_format,$net_amt); ?></td>
										</tr>
									<?php

									$sqlx1 = ""; $queryx1 = ""; $total_amt = "";
									$sqlx2 = ""; $queryx2 = ""; $tax_amt = "";
									$sqlx3 = ""; $queryx3 = ""; $net_amt = "";
								}

							?>
						</table>
					</form>
					<?php
				}
			?>
		</div>
	</div>
</div>