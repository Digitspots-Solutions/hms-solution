<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	//$xtbl = $tbL123; $xcol = "id"; $xopttext = "store_name";
	$isOutlet = "SELECT * FROM {$tbL123} WHERE deletedata=0";
	$wgtOutlet = html_db_select($isOutlet,'id','store_name');

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
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">PR Status</h3>
				<select name="prstatus" id="prstatus" class="nopads no-back-black">
					<option value="" selected>All</option>
					<option value="Pending">Pending</option>
					<option value="Approved">Approved</option>
					<option value="Approval in Progress">In Progress</option>
					<option value="Rejected">Rejected</option>
				</select>
			</span>
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
		<div class="cs-width-1500">
			<div id="section-to-print">

				<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
					<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
				</div>
				<div class="cs-width-400 margin-auto-ct alignct">
					<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold nomargin">Purchase Requests Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
					<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
				</div>

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'pr-order-reports') {

						$keywords = ""; $key_1 = ""; $key_2 = "";

						if(isset($_POST['prstatus']) && $_POST['prstatus'] == 'Approved') {
							$key_1 .= " AND ispr_to_manual IN('No') AND order_status IN('Approved')";
						} elseif(isset($_POST['prstatus']) && $_POST['prstatus'] == 'Pending') {
							$key_1 .= " AND ispr_to_manual IN('No') AND gstat IN('Confirm') AND order_status IN('Pending')";
						} elseif(isset($_POST['prstatus']) && $_POST['prstatus'] == 'Rejected') {
							$key_1 .= " AND ispr_to_manual IN('No') AND gstat IN('Confirm') AND order_status IN('Rejected')";
						} elseif(isset($_POST['prstatus']) && $_POST['prstatus'] == 'Approval in Progress') {
							$key_1 .= " AND ispr_to_manual IN('No') AND order_status IN('Approved') AND pr_status IN('IOU','Payment Inview')";
						} else {
							$key_1 .= " AND ispr_to_manual IN('No')";
						}

						if(isset($_POST['stores']) && !empty($_POST['stores'])) {
							$key_2 = " AND store={$_POST['stores']}";
						}

						$keywords = $key_1.$key_2;

						$sql = "SELECT store FROM {$tbL121} WHERE datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'".$keywords." GROUP BY store"; $datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$order_summary = array(); $total_amount_store_receive = 0; $index = 0;

							$store_id = 0; $store_name = "";

							foreach($datagroup as $key => $val) {
								
								//$store_id = idget_name($val['store'],'store',$tbL14);
								$store_name = idget_name($val['store'],'store_name',$mtbL10);

								$orders = array();

								?>
									<h3 class="large nobold"><?php echo $store_name; ?></h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Order Number</td>
											<td class="alignct default-text-font-bold">Supplier</td>
											<td class="alignct default-text-font-bold">Ordered Date</td>
											<td class="alignct default-text-font-bold">Ordered By</td>
											<td class="alignct default-text-font-bold">Status</td>
											<td class="alignct default-text-font-bold">Receipt Status</td>
											<td class="cs-width-300 alignct default-text-font-bold">
												Ordered Price
												<table cellpadding="0" cellspacing="0" border="1">
													<tr>
														<td class="alignct">Total Amount</td>
														<td class="alignct">Tax</td>
														<td class="alignct">Net Amount</td>
													</tr>
												</table>
											</td>
											<td class="cs-width-300 alignct default-text-font-bold">
												Received Price
												<table cellpadding="0" cellspacing="0" border="1">
													<tr>
														<td class="alignct">Total Amount</td>
														<td class="alignct">Tax</td>
														<td class="alignct">Net Amount</td>
													</tr>
												</table>
											</td>
										</tr>

										<?php
											$sql_2 = "SELECT * FROM {$tbL121} WHERE store={$val['store']} AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'".$key_1." GROUP BY order_number";
											$dataget = idget_data($sql_2);

											$write_status = ""; $query_po_var = "";

											$order_total_amount = 0; $order_tax_amount = 0; $order_net_amount = 0;
											$order_total_r_amount = 0; $order_tax_r_amount = 0; $order_net_r_amount = 0;
											$received_amount = 0;

											foreach($dataget as $xkey => $xval) {
												
												idget_global($xval['supplierid'],$var_supplier);
												idget_global($xval['userid'],$var_user);

												if($xval['gstat'] == 'Confirm' && $xval['order_status'] == 'Pending') {
													$write_status = "Approval is under review";
												} elseif($xval['gstat'] == 'Confirm' && $xval['order_status'] == 'Rejected') {
													$write_status = "Approval is rejected";
												} elseif($xval['order_status'] == 'Approved' && ($xval['pr_status'] == 'IOU' || $xval['pr_status'] == 'Payment Inview')) {
													$write_status = "Stage 2 approval is ongoing";
												} else {
													$write_status = $xval['order_status'];
												}

												$sql_3 = "SELECT SUM(order_total_amount) AS ordertotalAmt, SUM(order_tax_amount) AS ordertaxAmt, SUM(order_net_amount) AS ordernetAmt, SUM(order_total_r_amount) AS ordertotalrAmt, SUM(order_tax_r_amount) AS ordertaxrAmt, SUM(order_net_r_amount) AS ordernetrAmt FROM {$tbL121} WHERE store={$val['store']} AND order_number='{$xval['order_number']}'"; $get_sql_3 = idget_data($sql_3);

												$query_po_var = "order_number='{$xval['order_number']}' AND deletedata=0";
												$po_var = mysqli_data_exist($mtbL20,$query_po_var);

												?>
													<tr>
														<td class="alignct blue-font anchor default-text-font-bold" onclick="jsPrint('<?php echo $xval['order_number']; ?>')"><?php echo $xval['order_number']; ?></td>
														<td class="alignct"><?php echo $_gparams[$var_supplier]['returnval']; ?></td>
														<td class="alignct"><?php echo date('d/m/Y',strtotime($xval['datelogged'])); ?></td>
														<td class="alignct"><?php echo $_gparams[$var_user]['returnval']; ?></td>
														<td class="alignct"><?php echo $write_status; ?></td>
														<td class="alignct"><?php echo $xval['receipt_status']; if($po_var == true) { ?><a href="javascript:void(0)" class="blue-font left-push-5" onclick="jsVar('<?php echo $xval['order_number']; ?>')">Check Variance</a><?php } ?></td>
														<td class="cs-width-300 alignrt">
															<table cellpadding="0" cellspacing="0" border="1">
																<tr>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordertotalAmt'],2); ?></td>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordertaxAmt'],2); ?></td>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordernetAmt'],2); ?></td>
																</tr>
															</table>
														</td>
														<td class="cs-width-300 alignrt">
															<table cellpadding="0" cellspacing="0" border="1">
																<tr>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordertotalrAmt'],2); ?></td>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordertaxrAmt'],2); ?></td>
																	<td class="alignrt">&#8358; <?php echo number_format($get_sql_3[0]['ordernetrAmt'],2); ?></td>
																</tr>
															</table>
														</td>
													</tr>
												<?php

												$order_total_amount = $order_total_amount + $get_sql_3[0]['ordertotalAmt'];
												$order_tax_amount = $order_tax_amount + $get_sql_3[0]['ordertaxAmt'];
												$order_net_amount = $order_net_amount + $get_sql_3[0]['ordernetAmt'];

												$order_total_r_amount = $order_total_r_amount + $get_sql_3[0]['ordertotalrAmt'];
												$order_tax_r_amount = $order_tax_r_amount + $get_sql_3[0]['ordertaxrAmt'];
												$order_net_r_amount = $order_net_r_amount + $get_sql_3[0]['ordernetrAmt'];

												if($xval['receipt_status'] == 'Received') {
													$received_amount = $received_amount + $get_sql_3[0]['ordernetrAmt'];
												}

											}

											$orders['name'] = $store_name;
											$orders['amount'] = $received_amount;

										?>

										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td colspan="5">&nbsp;</td>
											<td class="cs-width-300 alignrt">
												<table cellpadding="0" cellspacing="0" border="1">
													<tr>
														<td class="alignrt">&#8358; <?php echo number_format($order_total_amount,2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($order_tax_amount,2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($order_net_amount,2); ?></td>
													</tr>
												</table>
											</td>
											<td class="cs-width-300 alignrt">
												<table cellpadding="0" cellspacing="0" border="1">
													<tr>
														<td class="alignrt">&#8358; <?php echo number_format($order_total_r_amount,2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($order_tax_r_amount,2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($order_net_r_amount,2); ?></td>
													</tr>
												</table>
											</td>
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

									<h3 class="large nobold alignct">Summary Report for Purchase Requests</h3><br>
									<div align="center">
										<table style="width: 350px !important" cellpadding="1" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Particular</td>
												<td class="alignct default-text-font-bold">Received Amount</td>
											</tr>
											<?php

												$grandtotal = 0;

												foreach($order_summary as $key => $val) {
														
													$grandtotal = $grandtotal + $val['amount'];

													?>
														<tr>
															<td class="alignlt"><?php echo $val['name']; ?></td>
															<td class="alignrt"><?php echo number_format($val['amount'],2); ?></td>
														</tr>
													<?php
												}

											?>
											<tr>
												<td class="alignlt default-text-font-bold">Total</td>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grandtotal,2); ?></td>
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