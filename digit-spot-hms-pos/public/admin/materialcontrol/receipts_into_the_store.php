<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$query_stores = "SELECT * FROM {$tbL123} WHERE status IN('Active') AND deletedata=0";
	$for_stores = html_db_select($query_stores,'id','store_name');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$xtbl = $mtbL8;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: Here you see the selected store items received
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporter" value="stock-issue-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Stores</h3>
				<select name="stores" id="stores" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $for_stores; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold alignlt">End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<!--<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>-->
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>
<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100 right-pull-20 left-pull-20">
			<div id="section-to-print">

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'stock-issue-reports') {

						?>
							<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
								<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
							</div>
							<div class="cs-width-700 margin-auto-ct alignct">
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold nomargin">MC Items Received Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
								<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
							</div>
						<?php

						$_SESSION['startdate'] = $_POST['startdate'];
						$_SESSION['enddate'] = $_POST['enddate'];

						$keywords = "";

						if(isset($_POST['stores']) && !empty($_POST['stores'])) {
							$keywords .= " AND store={$_POST['stores']}";
						}

						$ddOpt = " AND delivery_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
						$keywords .= $ddOpt;

					
						$sql = "SELECT store FROM {$xtbl} WHERE store_type IN('Virtual Stores') AND receipt_status='Received' AND ((qty_received > 0 AND order_net_amount > 0) OR (order_net_amount > 0 AND var_approval='Yes')) AND deletedata=0".$keywords." GROUP BY store";
						$datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$store_base_info = array();

							$sql2 = ""; $datagroup2 = ""; $store_name = ""; $stat_name = "";

							foreach($datagroup as $key => $val) {
				
								$store_name = idget_name($val['store'],'store_name',$tbL123);
								
								$sql2 = "SELECT order_number,first_approval,second_approval FROM {$xtbl} WHERE store={$val['store']} AND store_type IN('Virtual Stores') AND receipt_status='Received' AND ((qty_received > 0 AND order_net_amount > 0) OR (order_net_amount > 0 AND var_approval='Yes')) AND deletedata=0".$ddOpt." GROUP BY order_number";
								$datagroup2 = idget_data($sql2);
							
								?>
								<h3 class="large nobold default-text-font-bold bottom-pull-5"><?php echo $store_name; ?></h3>
								
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Supplier</td>
										<td class="alignct default-text-font-bold">Item</td>
										<td class="alignct default-text-font-bold">Expiry Status</td>
										<td class="alignct default-text-font-bold">Delivery Date</td>
										<td class="alignct default-text-font-bold">Quantity Received</td>
										<td class="alignct default-text-font-bold">Unit Price</td>
										<td class="alignct default-text-font-bold">Total Amount</td>
										<td class="alignct default-text-font-bold">Tax Amount</td>
										<td class="alignct default-text-font-bold">Net Amount</td>
									</tr>

									<?php

										$order_total_amount = 0; $order_tax_amount = 0; $order_net_amount = 0;
										$g_order_total_amount = 0; $g_order_tax_amount = 0; $g_order_net_amount = 0;

										$receiver = "";

										foreach($datagroup2 as $key2x => $val2x) {
										
											$sql3 = "SELECT * FROM {$xtbl} WHERE store={$val['store']} AND store_type IN('Virtual Stores') AND receipt_status='Received' AND order_number='{$val2x['order_number']}' AND ((qty_received > 0 AND order_net_amount > 0) OR (order_net_amount > 0 AND var_approval='Yes')) AND deletedata=0".$ddOpt; $datagroup3 = idget_data($sql3);

											if(!empty($val2x['first_approval']) && $val2x['first_approval'] > 0) {
												$receiver = idget_name($val2x['first_approval'],'staffname',$tbL7);
											} elseif(!empty($val2x['second_approval']) && $val2x['second_approval'] > 0) {
												$receiver = idget_name($val2x['second_approval'],'staffname',$tbL7);
											} else {
												$receiver = "Unknown";
											}

											?>
												<tr><td colspan="10" class="alignlt default-text-font-bold"><?php echo $val2x['order_number']; ?> - Received By <?php echo $receiver; ?></td></tr>
											<?php

											$num = 0; $get_qty = "";

											foreach($datagroup3 as $key2 => $val2) {
												
												$num += 1;

												idget_global($val2['supplierid'],$var_supplier);
												idget_global($val2['itemid'],$var_item);

												$get_su = arrayget_key($uoms,$val2['uom']);
												$expiry_state = idget_name($val2['itemid'],'isexpire',$mtbL5);
												$expiry_date = idget_name($val2['itemid'],'expiry_date',$mtbL5);

												$expiry_date_fr = date('d-m-y',strtotime($expiry_date));
												$get_expiry_stat = ($expiry_state == 'Yes') ? "Expiring - {$expiry_date_fr}" : "Never Expire";

												if(!empty($val2['order_total_r_amount']) && $val2['order_total_r_amount'] > 0) {

													$order_total_amount = $val2['order_total_r_amount'];
													$order_tax_amount = $val2['order_tax_r_amount'];
													$order_net_amount = $val2['order_net_r_amount'];

													$g_order_total_amount = $g_order_total_amount + $order_total_amount;
													$g_order_tax_amount = $g_order_tax_amount + $order_tax_amount;
													$g_order_net_amount = $g_order_net_amount + $order_net_amount;

												} else {
													
													$order_total_amount = $val2['order_total_amount'];
													$order_tax_amount = $val2['order_tax_amount'];
													$order_net_amount = $val2['order_net_amount'];

													$g_order_total_amount = $g_order_total_amount + $order_total_amount;
													$g_order_tax_amount = $g_order_tax_amount + $order_tax_amount;
													$g_order_net_amount = $g_order_net_amount + $order_net_amount;
												}

												if(!empty($val2['qty_received']) && $val2['qty_received'] > 0) { $get_qty = $val2['qty_received']; } else { $get_qty = $val2['qty_ordered']; }

												?>
													<tr>
														<td class="right-pull-10 left-pull-10 alignct"><?php echo $_gparams[$var_supplier]['returnval']; ?></td>
														<td class="right-pull-10 left-pull-10 alignct"><?php echo $_gparams[$var_item]['returnval']; ?></td>
														<td class="right-pull-10 left-pull-10 alignct"><?php echo $get_expiry_stat; ?></td>
														<td class="alignct"><?php echo date('d-m-Y',strtotime($val2['delivery_date'])); ?></td>
														
														<td class="right-pull-10 left-pull-10 alignct"><?php echo $get_qty.' '.$get_su; ?></td>
														<td class="right-pull-10 left-pull-10 alignrt"><?php echo number_format($val2['unitprice'],2); ?></td>
														<td class="right-pull-10 left-pull-10 alignrt"><?php echo number_format($order_total_amount,2); ?></td>
														<td class="right-pull-10 left-pull-10 alignrt"><?php echo number_format($order_tax_amount,2); ?></td>
														<td class="right-pull-10 left-pull-10 alignrt"><?php echo number_format($order_net_amount,2); ?></td>
													</tr>
												<?php

												$frompos=""; $topos=""; $cost_amount = ""; $last_price = "";
												$get_su=""; $get_bu=""; $categoryid=""; $subcategoryid=""; $buying_unit="";
												$expiry_state = ""; $expiry_date = ""; $expiry_date_fr = ""; $get_expiry_stat = "";
												$get_pos_name = "";
											}
										}

										$store_string = array();	
										$store_string['store'] = $store_name;
										$store_string['total'] = $g_order_net_amount;

										array_push($store_base_info, $store_string);
									?>

									<tr>
										<td class="right-pull-10 left-pull-10 default-text-font-bold" colspan="6">Total</td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold grey-1-theme alignrt">&#8358; <?php echo number_format($g_order_total_amount,2); ?></td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold grey-1-theme alignrt">&#8358; <?php echo number_format($g_order_tax_amount,2); ?></td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold grey-1-theme alignrt">&#8358; <?php echo number_format($g_order_net_amount,2); ?></td>
									</tr>

								</table>

								<div class="cs-height-30">
								</div>

								<?php
							}
						}

						?>
							<div align="center">
								<div class="cs-width-400">
									<h3 class="large nobold bottom-pull-7">Summary Overview</h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Stores</td>
											<td class="alignct default-text-font-bold">Cost Amount</td>
										</tr>

										<?php

											if(is_array($store_base_info) && count($store_base_info) > 0) {
												
												$gr_total = 0;

												foreach($store_base_info as $key => $val) {
													
													$gr_total = $gr_total + $val['total'];
													
													?>
														<tr>
															<td class="alignlt"><?php echo $val['store']; ?></td>
															<td class="alignrt">&#8358; <?php echo number_format($val['total'],2); ?></td>
														</tr>
													<?php
												}
											}

										?>
										
										<tr>
											<td class="alignlt">Total</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($gr_total,2); ?></td>
										</tr>
									</table>
								</div>
							</div>
						<?php
					}
				?>
			</div>
		</div>
	</div>
</div>


<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function jsForm(fr) {
		document.getElementById(fr).submit();
	}

	/*function jsxView(key) {
		popmodalframe('materialcontrol','transfer_item_list',key,0,1000,2500);
	}*/

	function jsxView(requestno) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/workspace.php?logs=Item Transfer No&tag=&trqs='+requestno;
		parent.document.getElementById('workspace').scrollTop = 0;
	}

</script>