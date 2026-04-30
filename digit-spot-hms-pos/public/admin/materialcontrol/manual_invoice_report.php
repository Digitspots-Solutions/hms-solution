<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$xtbl = $tbL123; $xcol = "id"; $xopttext = "store_name";
	$isOutlet = "SELECT id,store FROM {$tbL14} WHERE deletedata=0";
	$wgtOutlet = html_db_select($isOutlet,'id','store');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: This will show the summary of all the purchases between the selected date period
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
			<input type="hidden" name="reporter" value="pr-order-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Stores</h3>
				<select name="stores" id="stores" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $wgtOutlet; ?>
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
		<div class="nc-width-100">
			<div id="section-to-print">

				<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
					<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
				</div>
				<div class="cs-width-400 margin-auto-ct alignct">
					<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold nomargin">MC Manual Invoice (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
					<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
				</div>

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'pr-order-reports') {

						$keywords = "";

						if(isset($_POST['stores']) && !empty($_POST['stores'])) {
							$keywords .= " AND store={$_POST['stores']}";
						}

						$sql = "SELECT store,store_type FROM {$tbL121} WHERE ispr_to_manual IN('Yes') AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'".$keywords." GROUP BY store"; $datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$order_summary = array(); $total_amount_store_receive = 0; $index = 0;

							$store_id = 0; $store_name = "";

							foreach($datagroup as $key => $val) {
								
								if($val['store_type'] == 'Outlets') {
									$store_name = idget_name($val['store'],'posname',$tbL14);
								} elseif($val['store_type'] == 'Virtual Stores') {
									$store_name = idget_name($val['store'],'store_name',$tbL123);
								}

								$orders = array();

								?>
									<h3 class="large nobold"><?php echo $store_name; ?></h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Invoice Number</td>
											<td class="alignct default-text-font-bold">Supplier</td>
											<td class="alignct default-text-font-bold">Total Amount</td>
											<td class="alignct default-text-font-bold">Tax</td>
											<td class="alignct default-text-font-bold">Net Amount</td>
											<td class="alignct default-text-font-bold">Payment Status</td>
											<td class="alignct default-text-font-bold">Received On</td>
											<td class="alignct default-text-font-bold">Received By</td>
										</tr>

										<?php
											$sql_2 = "SELECT * FROM {$tbL121} WHERE store={$val['store']} AND ispr_to_manual IN('Yes') AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
											$dataget = idget_data($sql_2);

											$write_status = "";

											$order_total_amount = 0; $order_tax_amount = 0; $order_net_amount = 0;
											$order_total_r_amount = 0; $order_tax_r_amount = 0; $order_net_r_amount = 0;
											$received_amount = 0;

											foreach($dataget as $xkey => $xval) {
												
												idget_global($xval['supplierid'],$var_supplier);
												idget_global($xval['userid'],$var_user);

												?>
													<tr>
														<td class="alignct blue-font anchor default-text-font-bold" onclick="jsPrint('<?php echo $xval['order_number']; ?>')"><?php echo $xval['invoice_number']; ?></td>
														<td class="alignct"><?php echo $_gparams[$var_supplier]['returnval']; ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($xval['order_total_amount'],2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($xval['order_tax_amount'],2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($xval['order_net_amount'],2); ?></td>
														<td class="alignct">Paid</td>
														<td class="alignct"><?php echo date('d/m/Y',strtotime($xval['delivery_date'])); ?></td>
														<td class="alignct"><?php echo $_gparams[$var_user]['returnval']; ?></td>
													</tr>
												<?php

												$order_total_amount = $order_total_amount + $xval['order_total_amount'];
												$order_tax_amount = $order_tax_amount + $xval['order_tax_amount'];
												$order_net_amount = $order_net_amount + $xval['order_net_amount'];

												if($xval['receipt_status'] == 'Received') {
													$received_amount = $received_amount + $xval['order_net_amount'];
												}

											}

											$orders['name'] = $store_name;
											$orders['amount'] = $received_amount;

										?>

										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td>&nbsp;</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($order_total_amount,2); ?></td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($order_tax_amount,2); ?></td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($order_net_amount,2); ?></td>
											<td colspan="3">&nbsp;</td>
										</tr>

									</table>

									<div class="cs-height-10">
									</div>
								<?php

								$index += 1;

								array_push($order_summary,$orders);
							}


							if(is_array($order_summary) && count($order_summary) > 0) {
								
								?>
									<br><br>

									<h3 class="large nobold alignct">Summary Report for Manual Invoice</h3><br>
									<div align="center">
										<table style="width: 400px !important" cellpadding="1" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Store</td>
												<td class="alignct default-text-font-bold">Paid</td>
												<td class="alignct default-text-font-bold">Pending</td>
											</tr>
											<?php

												$grandtotal = 0; $grandpending = 0; $pending = 0;

												foreach($order_summary as $key => $val) {
														
													$grandtotal = $grandtotal + $val['amount'];
													$grandpending = $grandpending + $pending;

													?>
														<tr>
															<td class="alignlt"><?php echo $val['name']; ?></td>
															<td class="alignrt"><?php echo number_format($val['amount'],2); ?></td>
															<td class="alignrt"><?php echo number_format($pending,2); ?></td>
														</tr>
													<?php
												}

											?>
											<tr>
												<td class="alignlt default-text-font-bold">Total</td>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grandtotal,2); ?></td>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grandpending,2); ?></td>
											</tr>
										</table>
									</div>
								<?php
							}
						}
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


	function jsPrint(order) {

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
		inframe.src = curl+'public/admin/materialcontrol/printpr.php?pr='+order;
		parent.document.getElementById('workspace').scrollTop = 0;
	}

</script>