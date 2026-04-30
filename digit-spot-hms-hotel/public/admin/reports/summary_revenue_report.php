<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

if(isset($_POST['orderdate']) && !empty($_POST['orderdate'])) { $endate = $_POST['orderdate']; }
else { $endate = $server_get_date; }

$dateSplited = explode('-',$endate);
$startdate = $dateSplited[0].'-'.$dateSplited[1].'-01';


$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Summary Revenue Report</b>: Here you can see the summary revenue for the date selected
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporting" value="post">
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Choose Date</h3>
				<input type="text" name="orderdate" id="orderdate" placeholder="Start Date?" value="<?php echo $endate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
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
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
						$get_posstores = mysqli_data_fetch($tbL14,'id,posname,postype',$pst_query,'array');
						
						?>
							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Summary Revenue Report (For <?php echo $endate; ?>)</h3>
								<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
							</div>

							<div class="bottom-push-30">
								<h3 class="large nobold bottom-pull-7">Pos Stores Income</h3>
								<table cellpadding="3" cellspacing="0" border="1" style="font-size: 10px !important;">
									<tr>
										<td class="alignct default-text-font-bold">Revenue Stores</td>
										<td class="alignct default-text-font-bold">Cost Purchases</td>
										<td class="alignct default-text-font-bold">Gross Revenue</td>
										<td class="alignct default-text-font-bold">Complimentary (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">Tax1</td>
										<td class="alignct default-text-font-bold">Tax2</td>
										<td class="alignct default-text-font-bold">Tax3</td>
										<td class="alignct default-text-font-bold">Net Revenue (Excl. of taxes)</td>
										<td class="alignct default-text-font-bold">To-Date Revenue</td>
									</tr>
									
									<?php
										if(is_array($get_posstores)) {
											
											$costofpurchase = 0; $gross_revenue = 0;
											$complimentary = 0; $tax_1 = 0; $tax_2 = 0; $tax_3 = 0;
											$ctax_1 = 0; $ctax_2 = 0; $ctax_3 = 0; $crevenue = 0;
											$net_revenue = 0; $accumulated_revenue = 0; 

											$g_costofpurchase = 0; $g_gross_revenue = 0;
											$g_complimentary = 0; $g_net_revenue = 0; $g_accumulated_revenue = 0;
											$g_taxes = 0; $g_consumption = 0; $g_service_charge = 0;

											$posid = ""; $posname = ""; $postype = ""; $taxes = ""; $service_charge = "";
											$sql_1 = ""; $sql_2 = ""; $sql_3 = "";
											
											foreach($get_posstores as $key => $val) {
												
												$posid = $val['id'];
												$posname = $val['posname'];
												$postype = $val['postype'];

												$sql_2 = "SELECT SUM(amount) AS total FROM {$tbL99} WHERE posid={$posid} AND (billtype=3 OR iscomplimentary > 0) AND status IN('Completed') AND isreversed=0 AND deletedata=0 AND datelogged='{$endate}'";
												$dataset_2 = wgetSQL($sql_2);

												$complimentary = $dataset_2[0]['total'];
												$costofpurchase = 0;

												if($postype == 'Establishment') {
													
													$sql_1 = "SELECT * FROM {$tbL99} WHERE posid={$posid} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0 AND datelogged='{$endate}'"; $dataset_1 = wgetSQL($sql_1);

													$sql_3 = "SELECT SUM(amount) AS total, SUM(discount) AS discount FROM {$tbL99} WHERE posid={$posid} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";
													$dataset_3 = wgetSQL($sql_3);

													if(is_array($dataset_1) && count($dataset_1) > 0) {
														
														$t_amount = 0; $t_vat = 0; $t_service = 0; $t_consumption = 0;
														
														foreach($dataset_1 as $key => $val) {
															
															$amount = $val['amount'] - $val['discount'];
															
															$sql_1x = "SELECT * FROM {$tbL100} WHERE order_number='{$val['order_number']}'"; $dataset_1x = wgetSQL($sql_1x);

															if($dataset_1x[0]['tax_amount'] > 0) { $vat_amount = ($gh_get_vat / 100) * $amount; } else { $vat_amount = 0; }

															if($dataset_1x[0]['service_charge_amount'] > 0) { $service_amount = ($gh_get_service_charge / 100) * $amount; } else { $service_amount = 0; }

															if($dataset_1x[0]['consumption_amount'] > 0) { $consumption_amount = ($gh_get_consumption_tax / 100) * $amount; } else { $consumption_amount = 0; }

															$t_amount = $t_amount + $amount;
															$t_vat = $t_vat + $vat_amount;
															$t_service = $t_service + $service_amount;
															$t_consumption = $t_consumption + $consumption_amount;
														}

														$tax_1 = $t_service;
														$tax_2 = $t_vat;
														$tax_3 = $t_consumption;

														if(($tax_1 + $tax_2 + $tax_3) == 0) { $net_revenue = $t_amount - ($tax_1 + $tax_2 + $tax_3); } else { $net_revenue = $t_amount; }
														$gross_revenue = $t_amount + $tax_1 + $tax_2 + $tax_3;

														$t_amount = 0; $t_vat = 0; $t_service = 0; $t_consumption = 0;

													} else {
														
														$tax_1 = 0;
														$tax_2 = 0;
														$tax_3 = 0;

														$net_revenue = 0;
														$gross_revenue = 0;
													}

											
													$crevenue = $dataset_3[0]['total'] - $dataset_3[0]['discount'];
													$accumulated_revenue = $crevenue;

												} else {
													
													$sql_1 = "SELECT SUM(amount) AS total, SUM(discount) AS discount FROM {$tbL99} WHERE posid={$posid} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0 AND datelogged='{$endate}'"; $dataset_1 = wgetSQL($sql_1);

													$sql_3 = "SELECT SUM(amount) AS total, SUM(discount) AS discount FROM {$tbL99} WHERE posid={$posid} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";
													$dataset_3 = wgetSQL($sql_3);
													
													$gross_revenue = $dataset_1[0]['total'] - $dataset_1[0]['discount'];
													
													$tax_1 = ($gh_get_vat / 100) * $gross_revenue;
													$tax_2 = ($gh_get_consumption_tax / 100) * $gross_revenue;
													$tax_3 = ($gh_get_service_charge / 100) * $gross_revenue;

													$crevenue = $dataset_3[0]['total'] - $dataset_3[0]['discount'];

													$ctax_1 = ($gh_get_vat / 100) * $crevenue;
													$ctax_2 = ($gh_get_consumption_tax / 100) * $crevenue;
													$ctax_3 = ($gh_get_service_charge / 100) * $crevenue;

													$net_revenue = $gross_revenue - ($tax_1 + $tax_2 + $tax_3);
													$gross_revenue = $gross_revenue;
													
													$accumulated_revenue = $crevenue - ($ctax_1 + $ctax_2 + $ctax_3);
												}

												?>
													<tr>
														<td class="alignct"><?php echo $posname; ?></td>
														<td class="alignrt"><?php echo number_format($costofpurchase,2); ?></td>
														<td class="alignrt"><?php echo number_format($gross_revenue,2); ?></td>
														<td class="alignrt"><?php echo number_format($complimentary,2); ?></td>
														<td class="alignrt"><?php echo number_format($tax_3,2); ?></td>
														<td class="alignrt"><?php echo number_format($tax_1,2); ?></td>
														<td class="alignrt"><?php echo number_format($tax_2,2); ?></td>
														<td class="alignrt"><?php echo number_format($net_revenue,2); ?></td>
														<td class="alignrt"><?php echo number_format($accumulated_revenue,2); ?></td>
													</tr>
												<?php

												$g_costofpurchase = $g_costofpurchase + $costofpurchase;
												$g_gross_revenue = $g_gross_revenue + $gross_revenue;
												$g_complimentary = $g_complimentary + $complimentary;
												$g_net_revenue = $g_net_revenue + $net_revenue;
												$g_accumulated_revenue = $g_accumulated_revenue + $accumulated_revenue;
												$g_taxes = $g_taxes + $tax_1;
												$g_consumption = $g_consumption + $tax_2;
												$g_service_charge = $g_service_charge + $tax_3;
											}
										}
									?>

									<tr>
										<td class="alignrt default-text-font-bold">Total</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_costofpurchase,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_gross_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_complimentary,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_service_charge,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_taxes,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_consumption,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_net_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_accumulated_revenue,2); ?></td>
									</tr>

								</table>
							</div>

							<?php
								
								$irr_net_revenue_1 = 0; $irr_commission_1 = 0; $irr_service_charge_1 = 0;
								$irr_taxes_1 = 0; $irr_taxes_1x = 0; $irr_complimentary_1 = 0; $irr_rebate_1 = 0;
								$irr_gross_revenue_1 = 0; $irr_accumulated_revenue_1 = 0;

								$irr_net_revenue_2 = 0; $irr_commission_2 = 0; $irr_service_charge_2 = 0;
								$irr_taxes_2 = 0; $irr_taxes_2x = 0; $irr_complimentary_2 = 0; $irr_rebate_2 = 0;
								$irr_gross_revenue_2 = 0; $irr_accumulated_revenue_2 = 0;

								$irr_net_revenue_3 = 0; $irr_commission_3 = 0; $irr_service_charge_3 = 0;
								$irr_taxes_3 = 0; $irr_taxes_3x = 0; $irr_complimentary_3 = 0; $irr_rebate_3 = 0;
								$irr_gross_revenue_3 = 0; $irr_accumulated_revenue_3 = 0;

								$irr_net_revenue_4 = 0; $irr_commission_4 = 0; $irr_service_charge_4 = 0;
								$irr_taxes_4 = 0; $irr_taxes_4x = 0; $irr_complimentary_4 = 0; $irr_rebate_4 = 0;
								$irr_gross_revenue_4 = 0; $irr_accumulated_revenue_4 = 0;

								$irr_net_revenue_5 = 0; $irr_commission_5 = 0; $irr_service_charge_5 = 0;
								$irr_taxes_5 = 0; $irr_taxes_5x = 0;$irr_complimentary_5 = 0; $irr_rebate_5 = 0;
								$irr_gross_revenue_5 = 0; $irr_accumulated_revenue_5 = 0;

								$irr_net_revenue_6 = 0; $irr_commission_6 = 0; $irr_service_charge_6 = 0;
								$irr_taxes_6 = 0; $irr_taxes_6x = 0; $irr_taxes_6x = 0; $irr_complimentary_6 = 0; $irr_rebate_6 = 0;
								$irr_gross_revenue_6 = 0; $irr_accumulated_revenue_6 = 0;

								$irr_net_revenue_7 = 0; $irr_commission_7 = 0; $irr_service_charge_7 = 0;
								$irr_taxes_7 = 0; $irr_taxes_7x = 0; $irr_complimentary_7 = 0; $irr_rebate_7 = 0;
								$irr_gross_revenue_7 = 0; $irr_accumulated_revenue_7 = 0;

								#---start

								$sql_dayuse = "SELECT booking_number FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND deletedata=0 AND datelogged='{$endate}'";
								$dataset_dayuse = wgetSQL($sql_dayuse);

								if(is_array($dataset_dayuse) && count($dataset_dayuse) > 0) {
									$dayuse_rooms = ""; $rooms_string = "";
									foreach($dataset_dayuse as $key => $val) { $rooms_string .= "'".$val['booking_number']."',"; }
									$dayuse_rooms = substr_replace($rooms_string,'',-1,1);
									$dayuse_uquery = " AND booking_number IN({$dayuse_rooms})";
									$dayuse_nquery = " AND booking_number NOT IN({$dayuse_rooms})";
								} else {
									$dayuse_rooms = "";
									$dayuse_uquery = "";
									$dayuse_nquery = "";
								}

								//echo $dayuse_uquery;
								//echo $dayuse_nquery;

								#end of day-use rooms pack

								$sql_4 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(8,2)) > CAST(room_amount as DECIMAL(8,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_4 = wgetSQL($sql_4);

								$irr_taxes_1 = $dataset_4[0]['vat'];
								$irr_taxes_1x = $dataset_4[0]['consumption'];
								$irr_service_charge_1 = $dataset_4[0]['scharge'];
								$irr_net_revenue_1 = $dataset_4[0]['total'] - $dataset_4[0]['discounts'];

								$irr_gross_revenue_1 = $irr_net_revenue_1 + $irr_taxes_1 + $irr_taxes_1x + $irr_service_charge_1;

								$sql_5 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(8,2)) > CAST(room_amount as DECIMAL(8,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_5 = wgetSQL($sql_5);

								$irr_complimentary_1 = ($dataset_5[0]['total'] - $dataset_5[0]['discounts']) + $dataset_5[0]['vat'] + $dataset_5[0]['consumption'] + $dataset_5[0]['scharge'];

								/*$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount > t1.room_amount OR t1.discount_amount > 0) AND t1.deletedata=0)";
								$dataset_6 = wgetSQL($sql_6);*/
								$irr_rebate_1 = 0;

								/*$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND datelogged='{$endate}' AND rebate_type IN('Booking')"; $dataset_6 = wgetSQL($sql_6);
								$irr_rebate_1 = $dataset_6[0]['total'];*/

								$sql_7 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(8,2)) > CAST(room_amount as DECIMAL(8,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_7 = wgetSQL($sql_7);

								$irr_accumulated_revenue_1 = $dataset_7[0]['total'] - $dataset_7[0]['discounts'];

								#---end


								/*$sql_8 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=1 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf IN(0,7) AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
								$dataset_8 = wgetSQL($sql_8);

								$irr_taxes_2 = $dataset_8[0]['vat'];
								$irr_taxes_2x = $dataset_8[0]['consumption'];
								$irr_service_charge_2 = $dataset_8[0]['scharge'];
								$irr_net_revenue_2 = $dataset_8[0]['total']  - $dataset_8[0]['discounts'];

								$irr_gross_revenue_2 = $irr_net_revenue_2 + $irr_taxes_2 + $irr_taxes_2x + $irr_service_charge_2;

								$sql_9 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
								$dataset_9 = wgetSQL($sql_9);

								$irr_accumulated_revenue_2 = ($dataset_9[0]['total'] - $dataset_9[0]['discounts']) + $dataset_9[0]['vat'] + $dataset_9[0]['consumption'] + $dataset_9[0]['scharge'];*/

								$irr_taxes_2 = 0;
								$irr_taxes_2x = 0;
								$irr_service_charge_2 = 0;
								$irr_net_revenue_2 = 0;

								$irr_gross_revenue_2 = $irr_net_revenue_2 + $irr_taxes_2 + $irr_taxes_2x + $irr_service_charge_2;
								$irr_accumulated_revenue_2 = 0;

								#---end

								if(!empty($dayuse_uquery)) {
									$sql_10 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7)".$dayuse_uquery; $dataset_10 = wgetSQL($sql_10);

									$irr_taxes_3 = $dataset_10[0]['vat'];
									$irr_taxes_3x = $dataset_10[0]['consumption'];
									$irr_service_charge_3 = $dataset_10[0]['scharge'];
									$irr_net_revenue_3 = $dataset_10[0]['total'] - $dataset_10[0]['discounts'];

									$irr_gross_revenue_3 = $irr_net_revenue_3 + $irr_taxes_3 + $irr_taxes_3x + $irr_service_charge_3;

									$sql_11 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7)".$dayuse_uquery;
									$dataset_11 = wgetSQL($sql_11);

									$irr_complimentary_3 = ($dataset_11[0]['total'] - $dataset_11[0]['discounts']) + $dataset_11[0]['vat'] + $dataset_11[0]['consumption'] + $dataset_11[0]['scharge'];
								} else {
									$irr_taxes_3 = 0;
									$irr_taxes_3x = 0;
									$irr_service_charge_3 = 0;
									$irr_net_revenue_3 = 0;

									$irr_gross_revenue_3 = $irr_net_revenue_3 + $irr_taxes_3 + $irr_taxes_3x + $irr_service_charge_3;
									$irr_complimentary_3 = 0;
								}

								/*$sql_12 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL127} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.checkin_date='{$endate}' AND t1.checkout_date='{$endate}' AND t1.status='CheckedOut')"; $dataset_12 = wgetSQL($sql_12);*/
								$irr_rebate_3 = 0;

								$sql_13a = "SELECT * FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_13a = wgetSQL($sql_13a);

								if(is_array($dataset_13a) && count($dataset_13a) > 0) {
									
									$xtotal_room_amount = 0; $xtotal_discount_amount = 0;
									$sql_13 = "";
									
									foreach($dataset_13a as $kya => $vla) {
									
										$sql_13 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$vla['checkout_date']}' AND wkf IN(0,7) AND booking_number='{$vla['booking_number']}'";
										$dataset_13 = wgetSQL($sql_13);

										$xtotal_room_amount = $xtotal_room_amount + $dataset_13[0]['total'];
										$xtotal_discount_amount = $xtotal_discount_amount + $dataset_13[0]['discounts'];
									}

									//$irr_accumulated_revenue_3 = ($dataset_13[0]['total'] - $dataset_13[0]['discounts']) + $dataset_13[0]['vat'] + $dataset_13[0]['consumption'] + $dataset_13[0]['scharge'];
									$irr_accumulated_revenue_3 = $xtotal_room_amount - $xtotal_discount_amount;
								} else {
									$irr_accumulated_revenue_3 = 0;
								}

								#---end

								$sql_14 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'";
								$dataset_14 = wgetSQL($sql_14);

								$irr_taxes_4 = ($gh_get_vat / 100) * $dataset_14[0]['total'];
								$irr_taxes_4x = ($gh_get_consumption_tax / 100) * $dataset_14[0]['total'];
								$irr_service_charge_4 = ($gh_get_service_charge / 100) * $dataset_14[0]['total'];
								$irr_net_revenue_4 = $dataset_14[0]['total'] - ($irr_taxes_4 + $irr_taxes_4x + $irr_service_charge_4);

								$irr_gross_revenue_4 = $irr_net_revenue_4 + $irr_taxes_4 + $irr_taxes_4x + $irr_service_charge_4;

								$sql_15 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'"; $dataset_15 = wgetSQL($sql_15);

								$irr_taxes_a4 = ($gh_get_vat / 100) * $dataset_15[0]['total'];
								$irr_taxes_a4x = ($gh_get_consumption_tax / 100) * $dataset_15[0]['total'];
								$irr_service_charge_a4 = ($gh_get_service_charge / 100) * $dataset_15[0]['total'];
								$irr_accumulated_revenue_4 = $dataset_15[0]['total'] - ($irr_taxes_a4 + $irr_taxes_a4x + $irr_service_charge_a4);

								#---end

								$sql_16 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
								$dataset_16 = wgetSQL($sql_16);

								$irr_taxes_5 = ($gh_get_vat / 100) * $dataset_16[0]['total'];
								$irr_taxes_5x = ($gh_get_consumption_tax / 100) * $dataset_16[0]['total'];
								$irr_service_charge_5 = ($gh_get_service_charge / 100) * $dataset_16[0]['total'];
								$irr_net_revenue_5 = $dataset_16[0]['total'] - ($irr_taxes_5 + $irr_taxes_5x + $irr_service_charge_5);

								$irr_gross_revenue_5 = $irr_net_revenue_5 + $irr_taxes_5 + $irr_taxes_5x + $irr_service_charge_5;

								$sql_17 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
								$dataset_17 = wgetSQL($sql_17);

								$irr_taxes_a5 = ($gh_get_vat / 100) * $dataset_17[0]['total'];
								$irr_taxes_a5x = ($gh_get_consumption_tax / 100) * $dataset_17[0]['total'];
								$irr_service_charge_a5 = ($gh_get_service_charge / 100) * $dataset_17[0]['total'];
								$irr_accumulated_revenue_5 = $dataset_17[0]['total'] - ($irr_taxes_a5 + $irr_taxes_a5x + $irr_service_charge_a5);

								#---end

								$sql_18 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$endate}' AND '{$endate}'";
								$dataset_18 = wgetSQL($sql_18);

								$irr_net_revenue_6 = $dataset_18[0]['total'];
								$irr_gross_revenue_6 = $dataset_18[0]['total'];

								$sql_19 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_19 = wgetSQL($sql_19);
								
								$irr_accumulated_revenue_6 = $dataset_19[0]['total'];

								#---end

								/*$sql_20 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (actual_room_amount = room_amount OR discount_amount = 0) AND room_status IN('CheckedIn')"; $dataset_20 = wgetSQL($sql_20);*/

								$sql_20 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(8,2)) >= CAST(actual_room_amount as DECIMAL(8,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_20 = wgetSQL($sql_20);

								$irr_taxes_7 = $dataset_20[0]['vat'];
								$irr_taxes_7x = $dataset_20[0]['consumption'];
								$irr_service_charge_7 = $dataset_20[0]['scharge'];
								$irr_net_revenue_7 = $dataset_20[0]['total'];

								$irr_gross_revenue_7 = $irr_net_revenue_7 + $irr_taxes_7 + $irr_taxes_7x + $irr_service_charge_7;

								$sql_21 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(8,2)) >= CAST(actual_room_amount as DECIMAL(8,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_21 = wgetSQL($sql_21);

								$irr_complimentary_7 = $dataset_21[0]['total'] + $dataset_21[0]['vat'] + $dataset_21[0]['consumption'] + $dataset_21[0]['scharge'];

								/*$sql_22 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount = t1.room_amount OR t1.discount_amount = 0) AND t1.room_status='CheckedIn' AND t1.deletedata=0)";*/

								$sql_22 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND rebate_type IN('Booking') AND deletedata=0 AND transaction_date='{$endate}'";
								$dataset_22 = wgetSQL($sql_22);
								$irr_rebate_7 = $dataset_22[0]['total'];
								$irr_gross_revenue_7 = $irr_gross_revenue_7 - $irr_rebate_7;

								$sql_22x = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND rebate_type IN('Booking') AND deletedata=0 AND transaction_date BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_22x = wgetSQL($sql_22x);
								$irr_rebate_7x = $dataset_22x[0]['total'];
								
								$sql_23 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(8,2)) >= CAST(actual_room_amount as DECIMAL(8,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
								$dataset_23 = wgetSQL($sql_23);

								$irr_accumulated_revenue_7 = $dataset_23[0]['total'] - $irr_rebate_7x;

								#---end


								#---base total

								$irr_g_net_revenue = $irr_net_revenue_1 + $irr_net_revenue_2 + $irr_net_revenue_3 + $irr_net_revenue_4 + $irr_net_revenue_5 + $irr_net_revenue_6 + $irr_net_revenue_7;

								$irr_g_commission = $irr_taxes_1x + $irr_taxes_2x + $irr_taxes_3x + $irr_taxes_4x + $irr_taxes_5x + $irr_taxes_6x + $irr_taxes_7x;

								$irr_g_service_charge = $irr_service_charge_1 + $irr_service_charge_2 + $irr_service_charge_3 + $irr_service_charge_4 + $irr_service_charge_5 + $irr_service_charge_6 + $irr_service_charge_7;

								$irr_g_taxes = $irr_taxes_1 + $irr_taxes_2 + $irr_taxes_3 + $irr_taxes_4 + $irr_taxes_5 + $irr_taxes_6 + $irr_taxes_7;

								$irr_g_complimentary = $irr_complimentary_1 + $irr_complimentary_2 + $irr_complimentary_3 + $irr_complimentary_4 + $irr_complimentary_5 + $irr_complimentary_6 + $irr_complimentary_7;

								$irr_g_rebate = $irr_rebate_1 + $irr_rebate_2 + $irr_rebate_3 + $irr_rebate_4 + $irr_rebate_5 + $irr_rebate_6 + $irr_rebate_7;

								$irr_g_gross_revenue = $irr_gross_revenue_1 + $irr_gross_revenue_2 + $irr_gross_revenue_3 + $irr_gross_revenue_4 + $irr_gross_revenue_5 + $irr_gross_revenue_6 + $irr_gross_revenue_7;

								$irr_g_accumulated_revenue = $irr_accumulated_revenue_1 + $irr_accumulated_revenue_2 + $irr_accumulated_revenue_3 + $irr_accumulated_revenue_4 + $irr_accumulated_revenue_5 + $irr_accumulated_revenue_6 + $irr_accumulated_revenue_7;

							?>

							<div class="bottom-push-30">
								<h3 class="large nobold bottom-pull-7">Income Room Revenue</h3>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct"></td>
										<td class="alignct default-text-font-bold">Net Revenue (Excl. of taxes)</td>
										<td class="alignct default-text-font-bold">Service Charge</td>
										<td class="alignct default-text-font-bold">VAT</td>
										<td class="alignct default-text-font-bold">Consumption</td>
										<td class="alignct default-text-font-bold">Complimentary (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">Rebate</td>
										<td class="alignct default-text-font-bold">Gross Revenue (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">To-Date Revenue</td>
									</tr>
									<tr>
										<td class="alignlt">Rooms Sold (at discounted rate)</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_1x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_1,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_1,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">No Show</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_2x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_2,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_2,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Day Use</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_3x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_3,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_3,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Early Check-in Charges</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_4x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_4,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_4,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Late Check-out Charges</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_5x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_5,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_5,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Cancelled Revenue</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_6x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_6,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_6,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Rooms Sold (at full rate)</td>
										<td class="alignrt"><?php echo number_format($irr_net_revenue_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_service_charge_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_taxes_7x,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_complimentary_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_rebate_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_gross_revenue_7,2); ?></td>
										<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_7,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt default-text-font-bold">Total Room Revenue</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_net_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_service_charge,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_taxes,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_commission,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_complimentary,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_rebate,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_gross_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_accumulated_revenue,2); ?></td>
									</tr>
								</table>
							</div>

							<?php
								
								$sql_24 = "SELECT SUM(amount) AS total, COUNT(plan) AS member FROM {$tbL105} t1 LEFT JOIN {$tbL107} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.archivedata=0 AND t1.deletedata=0 AND t2.datelogged BETWEEN '{$endate}' AND '{$endate}' AND t2.isreversed=0 AND t2.deletedata=0"; $dataset_24 = wgetSQL($sql_24);

								$sql_25 = "SELECT SUM(amount) AS total, COUNT(plan) AS member FROM {$tbL105} t1 LEFT JOIN {$tbL107} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.archivedata=0 AND t1.deletedata=0 AND t2.datelogged BETWEEN '{$startdate}' AND '{$endate}' AND t2.isreversed=0 AND t2.deletedata=0"; $dataset_25 = wgetSQL($sql_25);

								$recreation_count = $dataset_24[0]['member'];
								$recreation_vat = ($gh_get_vat / 100) * $dataset_24[0]['total'];
								$recreation_service_charge = ($gh_get_service_charge / 100) * $dataset_24[0]['total'];
								$recreation_taxess = $recreation_vat + $recreation_service_charge;
								$recreation_amount = $dataset_24[0]['total'] - $recreation_taxess;

								$recreation_accuml_count = $dataset_25[0]['member'];
								$recreation_accu_vat = ($gh_get_vat / 100) * $dataset_25[0]['total'];
								$recreation_accu_service_charge = ($gh_get_service_charge / 100) * $dataset_25[0]['total'];
								$recreation_accuml_revenue = $dataset_25[0]['total'] - ($recreation_accu_vat + $recreation_accu_service_charge);

								$sql_26 = "SELECT SUM(amount) AS total, COUNT(plan) AS member FROM {$tbL105} t1 LEFT JOIN {$tbL107} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.archivedata=1 AND t1.deletedata=0 AND t2.datelogged BETWEEN '{$endate}' AND '{$endate}' AND t2.isreversed=0 AND t2.deletedata=0"; $dataset_26 = wgetSQL($sql_26);

								$sql_27 = "SELECT SUM(amount) AS total, COUNT(plan) AS member FROM {$tbL105} t1 LEFT JOIN {$tbL107} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.archivedata=1 AND t1.deletedata=0 AND t2.datelogged BETWEEN '{$startdate}' AND '{$endate}' AND t2.isreversed=0 AND t2.deletedata=0"; $dataset_27 = wgetSQL($sql_27);

								$renewal_count = $dataset_26[0]['member'];
								$renewal_amount = $dataset_26[0]['total'];
								$renewal_accuml_count = $dataset_27[0]['member'];
								
								$recreation_r_accu_vat = ($gh_get_vat / 100) * $dataset_27[0]['total'];
								$recreation_r_accu_service_charge = ($gh_get_service_charge / 100) * $dataset_27[0]['total'];
								$renewal_accuml_revenue = $dataset_27[0]['total'] - ($recreation_r_accu_vat + $recreation_r_accu_service_charge);

								$g_count = $recreation_count + $renewal_count;
								$g_amount = $recreation_amount + $renewal_amount;
								$accuml_g_count = $recreation_accuml_count + $renewal_accuml_count;
								$accuml_g_revenue = $recreation_accuml_revenue + $renewal_accuml_revenue;
							?>

							<div class="bottom-push-30" align="center">
								<div class="cs-width-700">
									<h3 class="large nobold alignlt bottom-pull-7">Recreation Revenue</h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Type</td>
											<td class="alignct default-text-font-bold">Count</td>
											<td class="alignct default-text-font-bold">Amount</td>
											<td class="alignct default-text-font-bold">To-Date Count</td>
											<td class="alignct default-text-font-bold">To-Date Revenue</td>
										</tr>
										<tr>
											<td class="alignlt">Recreation</td>
											<td class="alignrt"><?php echo number_format($recreation_count,2); ?></td>
											<td class="alignrt"><?php echo number_format($recreation_amount,2); ?></td>
											<td class="alignrt"><?php echo number_format($recreation_accuml_count,2); ?></td>
											<td class="alignrt"><?php echo number_format($recreation_accuml_revenue,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">Renewals</td>
											<td class="alignrt"><?php echo number_format($renewal_count,2); ?></td>
											<td class="alignrt"><?php echo number_format($renewal_amount,2); ?></td>
											<td class="alignrt"><?php echo number_format($renewal_accuml_count,2); ?></td>
											<td class="alignrt"><?php echo number_format($renewal_accuml_revenue,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td class="alignrt default-text-font-bold"><?php echo number_format($g_count,2); ?></td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_amount,2); ?></td>
											<td class="alignrt default-text-font-bold"><?php echo number_format($accuml_g_count,2); ?></td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($accuml_g_revenue,2); ?></td>
										</tr>
									</table>
								</div>
							</div>

							<?php
								$consumption = 0; $deposit = 0;
								$guest_total_consumption_deposit = $consumption + $deposit;
							?>

							<!--<div class="bottom-push-30" align="center">
								<div class="cs-width-700">
									<h3 class="large nobold alignlt bottom-pull-7">Total Guest Balance</h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Particular</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>
										<tr>
											<td class="alignlt">Consumption</td>
											<td class="alignrt"><?php //echo number_format($consumption,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">Deposit</td>
											<td class="alignrt"><?php //echo number_format($deposit,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">Total</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php //echo number_format($guest_total_consumption_deposit,2); ?></td>
										</tr>
									</table>
								</div>
							</div>-->

							<?php
								$paytype = "SELECT * FROM {$tbL24} WHERE deletedata=0";
								$show_paytype = wgetSQL($paytype);
							?>

							<div class="bottom-push-30" align="center">
								<div class="cs-width-700">
									<h3 class="large nobold alignlt bottom-pull-7">Pay Type Details</h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Pay Type</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>
										
										<?php
											if(is_array($show_paytype)) {
												
												$paytype_sales = 0; $total_paytype_sales = 0;
												
												$booking_general_payments = ""; $booking_corp_payments = "";
												$pos_payments = ""; $recreation_payments = "";

												foreach($show_paytype as $key => $val) {
													
													$sql_28 = "SELECT SUM(amount) AS total FROM {$tbL131} WHERE payment_mode={$val['id']} AND deletedata=0 AND isreversed=0 AND transaction_type='Credit' AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_28 = wgetSQL($sql_28);

													$booking_general_payments = $dataset_28[0]['total'];

													$sql_28 = "SELECT SUM(amount) AS total FROM {$tbL63} WHERE paymode={$val['id']} AND deletedata=0 AND transaction_type='Credit' AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_28 = wgetSQL($sql_28);

													$booking_corp_payments = $dataset_28[0]['total'];

													$sql_28 = "SELECT SUM(bill_amount) AS total FROM {$tbL100} WHERE media={$val['id']} AND deletedata=0 AND isreversed=0 AND ispaid=1 AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_28 = wgetSQL($sql_28);

													$pos_payments = $dataset_28[0]['total'];

													$sql_28x = "SELECT SUM(amount) AS total FROM {$tbL107} WHERE mode={$val['id']} AND deletedata=0 AND isreversed=0 AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_28x = wgetSQL($sql_28x);

													$recreation_payments = $dataset_28x[0]['total'];

													$paytype_sales = $booking_general_payments + $booking_corp_payments + $pos_payments + $recreation_payments;

													?>
														<tr>
															<td class="alignlt"><a href="javascript:void(0)" class="blue-font" onclick="openpayt('<?php echo $val['id']; ?>','<?php echo $endate; ?>')"><?php echo $val['name']; ?></a></td><td class="alignrt"><?php echo number_format($paytype_sales,2) ?></td>
														</tr>
													<?php

													$total_paytype_sales = $total_paytype_sales + $paytype_sales;
												}

												?>
													<tr>
														<td class="alignlt">Total</td><td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_paytype_sales,2) ?></td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>

							
							<?php
								
								$cpaytype = "SELECT * FROM {$tbL24} WHERE isdefault='Yes' AND deletedata=0 LIMIT 1";
								$show_cpaytype = wgetSQL($cpaytype);

								$sql_29 = "SELECT SUM(amount) AS total FROM {$tbL131} WHERE payment_mode={$show_cpaytype[0]['id']} AND deletedata=0 AND isreversed=0 AND transaction_type='Credit' AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_29 = wgetSQL($sql_29);

								$booking_general_payments = $dataset_29[0]['total'];

								$sql_29 = "SELECT SUM(amount) AS total FROM {$tbL63} WHERE paymode={$show_cpaytype[0]['id']} AND deletedata=0 AND transaction_type='Credit' AND datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_29 = wgetSQL($sql_29);

								$booking_corp_payments = $dataset_29[0]['total'];

								$sql_29 = "SELECT SUM(bill_amount) AS total FROM {$tbL100} WHERE media={$show_cpaytype[0]['id']} AND deletedata=0 AND isreversed=0 AND ispaid=1 AND datelogged BETWEEN '{$endate}' AND '{$endate}'";
								$dataset_29 = wgetSQL($sql_29);

								$pos_payments = $dataset_29[0]['total'];

								$booking_cash = $booking_general_payments + $booking_corp_payments;
								$pos_cash = $pos_payments;

								$total_actual_cash = $booking_cash + $pos_cash;
							?>

							<div class="bottom-push-30" align="center">
								<div class="cs-width-700">
									<h3 class="large nobold alignlt bottom-pull-7">Summary for Actual Cash</h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Particular</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>
										<tr>
											<td class="alignlt">Booking</td>
											<td class="alignrt"><?php echo number_format($booking_cash,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">POS</td>
											<td class="alignrt"><?php echo number_format($pos_cash,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt default-text-font-bold">Total Actual Cash</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_actual_cash,2); ?></td>
										</tr>
									</table>
								</div>
							</div>

							<?php
								
								$sql_2x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}'"; $dataset_2x = wgetSQL($sql_2x);

								$sql_3x = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE rebate_type IN('Booking') AND approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}'";
								$dataset_3x = wgetSQL($sql_3x);

								$sql_3x2 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE rebate_type IN('Booking') AND approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_3x2 = wgetSQL($sql_3x2);

								$sql_4x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}'"; $dataset_4x = wgetSQL($sql_4x);

								$sql_5x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}'"; $dataset_5x = wgetSQL($sql_5x);
								

								//$booking_revenue_notax = $dataset_2x[0]['total'];
								$booking_revenue_notax = $irr_g_net_revenue;
								$booking_rebate = $dataset_3x[0]['total'];
								
								//$booking_revenue_norebate = $dataset_2x[0]['total'] - $dataset_3x[0]['total'];
								$booking_revenue_norebate = $irr_g_net_revenue - $dataset_3x[0]['total'];
								$booking_commission = 0;
								
								//$booking_taxes = $dataset_2x[0]['vat'] + $dataset_2x[0]['consumption'] + $dataset_2x[0]['scharge'];
								$booking_taxes = $irr_g_service_charge + $irr_g_taxes + $irr_g_commission;
								
								//$booking_complimentary = $dataset_4x[0]['total'] + $dataset_4x[0]['vat'] + $dataset_4x[0]['consumption'] + $dataset_4x[0]['scharge'];
								$booking_complimentary = $irr_g_complimentary;
								
								//$booking_revenue_addtaxes = $dataset_2x[0]['total'] + $dataset_2x[0]['vat'] + $dataset_2x[0]['consumption'] + $dataset_2x[0]['scharge'];
								//$booking_revenue_addtaxes = $irr_g_gross_revenue;
								$booking_revenue_addtaxes = $booking_revenue_norebate + $booking_taxes;
								
								//$booking_revenue_accuml = $dataset_5x[0]['total'] - $dataset_3x2[0]['total'];
								$booking_revenue_accuml = $irr_g_accumulated_revenue;

								$sql_6x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge, SUM(bill_amount) AS total FROM {$tbL100} WHERE billtype NOT IN(3) AND isreversed=0 AND status IN('Completed') AND deletedata=0 AND datelogged='{$endate}'";
								$dataset_6x = wgetSQL($sql_6x);

								$sql_7x = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE rebate_type IN('Pos') AND approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}'";
								$dataset_7x = wgetSQL($sql_7x);

								$sql_7x2 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE rebate_type IN('Pos') AND approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_7x2 = wgetSQL($sql_7x2);

								$sql_8x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge, SUM(bill_amount) AS total FROM {$tbL100} WHERE billtype IN(3) AND isreversed=0 AND status IN('Completed') AND deletedata=0 AND datelogged='{$endate}'";
								$dataset_8x = wgetSQL($sql_8x);

								$sql_9x = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge, SUM(bill_amount) AS total, SUM(discount_amount) AS discount FROM {$tbL100} WHERE billtype NOT IN(3) AND isreversed=0 AND status IN('Completed') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";
								$dataset_9x = wgetSQL($sql_9x);

								//$pos_revenue_notax = $dataset_6x[0]['total'];
								$pos_revenue_notax = $g_net_revenue;
								$pos_rebate = $dataset_7x[0]['total'];

								//$pos_revenue_norebate = $dataset_6x[0]['total'] - $dataset_7x[0]['total'];
								$pos_revenue_norebate = $pos_revenue_notax - $pos_rebate;

								$pos_commission = 0;

								//$pos_taxes = $dataset_6x[0]['vat'] + $dataset_6x[0]['consumption'] + $dataset_6x[0]['scharge'];
								$pos_taxes = $g_service_charge + $g_taxes + $g_consumption;
								
								//$pos_complimentary = $dataset_8x[0]['total'] + $dataset_8x[0]['vat'] + $dataset_8x[0]['consumption'] + $dataset_8x[0]['scharge'];
								$pos_complimentary = $g_complimentary;

								//$pos_revenue_addtaxes = $dataset_6x[0]['total'] + $dataset_6x[0]['vat'] + $dataset_6x[0]['consumption'] + $dataset_6x[0]['scharge'];
								//$pos_revenue_addtaxes = $g_net_revenue;
								$pos_revenue_addtaxes = $pos_revenue_norebate + $pos_taxes;

								//$pos_revenue_accuml = $dataset_9x[0]['total'] - $dataset_7x2[0]['total'];
								$pos_revenue_accuml = $g_accumulated_revenue - $dataset_7x2[0]['total'];

								
								$sql_10x = "SELECT SUM(amount) AS total FROM {$tbL107} t1 LEFT JOIN {$tbL105} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.deletedata=0 AND t2.archivedata=0 AND t2.deletedata=0 AND t2.datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_10x = wgetSQL($sql_10x);

								$sql_11x = "SELECT SUM(amount) AS total FROM {$tbL107} t1 LEFT JOIN {$tbL105} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.deletedata=0 AND t2.archivedata=0 AND t2.deletedata=0 AND t2.datelogged BETWEEN '{$startdate}' AND '{$endate}'"; $dataset_11x = wgetSQL($sql_11x);

								$sql_12x = "SELECT SUM(amount) AS total FROM {$tbL107} t1 LEFT JOIN {$tbL105} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.deletedata=0 AND t2.archivedata=1 AND t2.deletedata=0 AND t2.datelogged BETWEEN '{$endate}' AND '{$endate}'"; $dataset_12x = wgetSQL($sql_12x);

								$sql_13x = "SELECT SUM(amount) AS total FROM {$tbL107} t1 LEFT JOIN {$tbL105} t2 ON t1.recreation_number=t2.recreation_number WHERE t1.deletedata=0 AND t2.archivedata=1 AND t2.deletedata=0 AND t2.datelogged BETWEEN '{$startdate}' AND '{$endate}'"; $dataset_13x = wgetSQL($sql_13x);

								//$recreation_revenue_notax = $dataset_10x[0]['total'];
								$recreation_revenue_notax = $recreation_amount;
								$recreation_rebate = 0;
								$recreation_revenue_norebate = $recreation_amount;
								$recreation_commission = 0;
								$recreation_taxes = $recreation_taxess;
								$recreation_complimentary = 0;
								//$recreation_revenue_addtaxes = $dataset_10x[0]['total'];
								$recreation_revenue_addtaxes = $recreation_amount + $recreation_taxes;
								//$recreation_revenue_accuml = $dataset_11x[0]['total'];
								$recreation_revenue_accuml = $recreation_accuml_revenue;

								//$renewal_revenue_notax = $dataset_12x[0]['total'];
								$recreation_r_vat = ($gh_get_vat / 100) * $renewal_amount;
								$recreation_r_service_charge = ($gh_get_service_charge / 100) * $renewal_amount;

								$renewal_revenue_notax = $renewal_amount - ($recreation_r_vat + $recreation_r_service_charge);
								$renewal_rebate = 0; 
								$renewal_revenue_norebate = $renewal_amount - ($recreation_r_vat + $recreation_r_service_charge);
								$renewal_commission = 0;
								$renewal_taxes = $recreation_r_vat + $recreation_r_service_charge;
								$renewal_complimentary = 0;
								//$renewal_revenue_addtaxes = $dataset_12x[0]['total'];
								$renewal_revenue_addtaxes = $renewal_amount;
								//$renewal_revenue_accuml = $dataset_13x[0]['total'];
								$renewal_revenue_accuml = $renewal_accuml_revenue;
								

								$sr_g_revenue_notax = $booking_revenue_notax + $pos_revenue_notax + $recreation_revenue_notax + $renewal_revenue_notax;

								$sr_g_rebate = $booking_rebate + $pos_rebate + $recreation_rebate + $renewal_rebate;

								$sr_g_revenue_norebate = $booking_revenue_norebate + $pos_revenue_norebate + $recreation_revenue_norebate + $renewal_revenue_norebate;

								$sr_commission = $booking_commission + $pos_commission + $recreation_commission + $renewal_commission;
								
								$sr_g_taxes = $booking_taxes + $pos_taxes + $recreation_taxes + $renewal_taxes;

								$sr_g_complimentary = $booking_complimentary + $pos_complimentary + $recreation_complimentary + $renewal_complimentary;

								$sr_g_revenue_addtaxes = $booking_revenue_addtaxes + $pos_revenue_addtaxes + $recreation_revenue_addtaxes + $renewal_revenue_addtaxes;

								$sr_g_revenue_accuml = $booking_revenue_accuml + $pos_revenue_accuml + $recreation_revenue_accuml + $renewal_revenue_accuml;
								//$sr_g_revenue_accuml = $booking_revenue_norebate + $pos_revenue_norebate + $recreation_revenue_accuml + $renewal_revenue_accuml;
							?>

							<div class="bottom-push-30">
								<h3 class="large nobold bottom-pull-7">Summary Revenue</h3>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Particular</td>
										<td class="alignct default-text-font-bold">Revenue (Excl. of taxes)</td>
										<td class="alignct default-text-font-bold">Rebate (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">Revenue (Excl. of Rebate)</td>
										<!--<td class="alignct default-text-font-bold">Commission</td>-->
										<td class="alignct default-text-font-bold">Taxes</td>
										<td class="alignct default-text-font-bold">Complimentary (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">Revenue (Incl. of taxes)</td>
										<td class="alignct default-text-font-bold">Grand To-Date Revenue</td>
									</tr>
									<tr>
										<td class="alignlt">Booking</td>
										<td class="alignrt"><?php echo number_format($booking_revenue_notax,2); ?></td>
										<td class="alignrt"><?php echo number_format($booking_rebate,2); ?></td>
										<td class="alignrt"><?php echo number_format($booking_revenue_norebate,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($booking_commission,2); ?></td>-->
										<td class="alignrt"><?php echo number_format($booking_taxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($booking_complimentary,2); ?></td>
										<td class="alignrt"><?php echo number_format($booking_revenue_addtaxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($booking_revenue_accuml,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($booking_revenue_norebate,2); ?></td>-->
									</tr>
									<tr>
										<td class="alignlt">POS</td>
										<td class="alignrt"><?php echo number_format($pos_revenue_notax,2); ?></td>
										<td class="alignrt"><?php echo number_format($pos_rebate,2); ?></td>
										<td class="alignrt"><?php echo number_format($pos_revenue_norebate,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($pos_commission,2); ?></td>-->
										<td class="alignrt"><?php echo number_format($pos_taxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($pos_complimentary,2); ?></td>
										<td class="alignrt"><?php echo number_format($pos_revenue_addtaxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($pos_revenue_accuml,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($pos_revenue_norebate,2); ?></td>-->
									</tr>
									<tr>
										<td class="alignlt">Recreation</td>
										<td class="alignrt"><?php echo number_format($recreation_revenue_notax,2); ?></td>
										<td class="alignrt"><?php echo number_format($recreation_rebate,2); ?></td>
										<td class="alignrt"><?php echo number_format($recreation_revenue_norebate,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($recreation_commission,2); ?></td>-->
										<td class="alignrt"><?php echo number_format($recreation_taxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($recreation_complimentary,2); ?></td>
										<td class="alignrt"><?php echo number_format($recreation_revenue_addtaxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($recreation_revenue_accuml,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Renewals</td>
										<td class="alignrt"><?php echo number_format($renewal_revenue_notax,2); ?></td>
										<td class="alignrt"><?php echo number_format($renewal_rebate,2); ?></td>
										<td class="alignrt"><?php echo number_format($renewal_revenue_norebate,2); ?></td>
										<!--<td class="alignrt"><?php //echo number_format($renewal_commission,2); ?></td>-->
										<td class="alignrt"><?php echo number_format($renewal_taxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($renewal_complimentary,2); ?></td>
										<td class="alignrt"><?php echo number_format($renewal_revenue_addtaxes,2); ?></td>
										<td class="alignrt"><?php echo number_format($renewal_revenue_accuml,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt default-text-font-bold">Total</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_revenue_notax,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_rebate,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_revenue_norebate,2); ?></td>
										
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_taxes,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_complimentary,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_revenue_addtaxes,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($sr_g_revenue_accuml,2); ?></td>
									</tr>
								</table>
							</div>

							<?php
								
								$roomsCheckedin = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0"; $gt_roomCheckedin = wgetSQL($roomsCheckedin);

								$sql_30 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=2 AND room_type_id > 0 AND roomid > 0"; $dataset_30 = wgetSQL($sql_30);

								$sql_31 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=1 AND room_type_id > 0 AND roomid > 0"; $dataset_31 = wgetSQL($sql_31);

								$sql_32 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged='{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='individual')";
								$dataset_32 = wgetSQL($sql_32);

								$sql_33 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND actual_room_amount = room_amount AND discount_amount = 0"; $dataset_33 = wgetSQL($sql_33);

								$sql_34 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_34 = wgetSQL($sql_34);

								$individual_early_checkin_total = $dataset_30[0]['total'];
								$individual_late_checkout_total = $dataset_31[0]['total'];
								$individual_dayuse_total = $dataset_32[0]['total'];
								$individual_fullrate_total = $dataset_33[0]['total'];
								$individual_discountrate_total = $dataset_34[0]['total'];
								$individual_g_total = $individual_early_checkin_total + $individual_late_checkout_total + $individual_dayuse_total + $individual_fullrate_total + $individual_discountrate_total;


								$sql_35 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=2 AND room_type_id > 0 AND roomid > 0"; $dataset_35 = wgetSQL($sql_35);

								$sql_36 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=1 AND room_type_id > 0 AND roomid > 0"; $dataset_36 = wgetSQL($sql_36);

								$sql_37 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged='{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='corporate')";
								/*$sql_37 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND roomid IN(SELECT roomid FROM {$tbL127} WHERE checkin_date=checkout_date)";*/
								$dataset_37 = wgetSQL($sql_37);

								$sql_38 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND actual_room_amount = room_amount AND discount_amount = 0"; $dataset_38 = wgetSQL($sql_38);

								$sql_39 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_39 = wgetSQL($sql_39);

								$corporate_early_checkin_total = $dataset_35[0]['total'];
								$corporate_late_checkout_total = $dataset_36[0]['total'];
								$corporate_dayuse_total = $dataset_37[0]['total'];
								$corporate_fullrate_total = $dataset_38[0]['total'];
								$corporate_discountrate_total = $dataset_39[0]['total'];
								$corporate_g_total = $corporate_early_checkin_total + $corporate_late_checkout_total + $corporate_dayuse_total + $corporate_fullrate_total + $corporate_discountrate_total;

								$agent_early_checkin_total = 0; $agent_late_checkout_total = 0;
								$agent_dayuse_total = 0; $agent_fullrate_total = 0;
								$agent_discountrate_total = 0; $agent_g_total = 0;


								$sql_40 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=2 AND room_type_id > 0 AND roomid > 0"; $dataset_40 = wgetSQL($sql_40);

								$sql_41 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=1 AND room_type_id > 0 AND roomid > 0"; $dataset_41 = wgetSQL($sql_41);

								$sql_42 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged='{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='complimentary')";

								/*$sql_42 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND roomid IN(SELECT roomid FROM {$tbL127} WHERE checkin_date=checkout_date)";*/
								$dataset_42 = wgetSQL($sql_42);

								$sql_43 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND actual_room_amount = room_amount AND discount_amount = 0"; $dataset_43 = wgetSQL($sql_43);

								$sql_44 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf IN(0,7) AND room_type_id > 0 AND roomid > 0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_44 = wgetSQL($sql_44);

								$complimentary_early_checkin_total = $dataset_40[0]['total'];
								$complimentary_late_checkout_total = $dataset_41[0]['total'];
								$complimentary_dayuse_total = $dataset_42[0]['total'];
								$complimentary_fullrate_total = $dataset_43[0]['total'];
								$complimentary_discountrate_total = $dataset_44[0]['total'];
								$complimentary_g_total = $complimentary_early_checkin_total + $complimentary_late_checkout_total + $complimentary_dayuse_total + $complimentary_fullrate_total + $complimentary_discountrate_total;


								$sql_45 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=2 AND room_type_id > 0 AND roomid > 0"; $dataset_45 = wgetSQL($sql_45);

								$sql_46 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND deletedata=0 AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf=1 AND room_type_id > 0 AND roomid > 0"; $dataset_46 = wgetSQL($sql_46);

								$sql_47 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged='{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='online')";
								$dataset_47 = wgetSQL($sql_47);

								$sql_48 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2) AND room_type_id > 0 AND roomid > 0 AND actual_room_amount = room_amount AND discount_amount = 0"; $dataset_48 = wgetSQL($sql_48);

								$sql_49 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND bill_date='{$endate}' AND room_status IN('CheckedIn') AND (actual_room_amount > room_amount OR discount_amount > 0) AND room_type_id > 0 AND roomid > 0"; $dataset_49 = wgetSQL($sql_49);

								$online_early_checkin_total = $dataset_45[0]['total'];
								$online_late_checkout_total = $dataset_46[0]['total'];
								$online_dayuse_total = $dataset_47[0]['total'];
								$online_fullrate_total = $dataset_48[0]['total'];
								$online_discountrate_total = $dataset_49[0]['total'];
								$online_g_total = $online_early_checkin_total + $online_late_checkout_total + $online_dayuse_total + $online_fullrate_total + $online_discountrate_total;

								$early_checkin_total = $individual_early_checkin_total + $corporate_early_checkin_total + $agent_early_checkin_total + $complimentary_early_checkin_total + $online_early_checkin_total;

								$late_checkout_total = $individual_late_checkout_total + $corporate_late_checkout_total + $agent_late_checkout_total + $agent_late_checkout_total + $complimentary_late_checkout_total + $online_late_checkout_total;

								$dayuse_total = $individual_dayuse_total + $corporate_dayuse_total + $agent_dayuse_total + $complimentary_dayuse_total + $online_dayuse_total;
								
								$fullrate_total = $individual_fullrate_total + $corporate_fullrate_total + $agent_fullrate_total + $complimentary_fullrate_total + $online_fullrate_total;

								$discountrate_total = $individual_discountrate_total + $corporate_discountrate_total + $agent_discountrate_total + $complimentary_discountrate_total + $online_discountrate_total;
								
								$g_total = $individual_g_total + $corporate_g_total + $agent_g_total + $complimentary_g_total + $online_g_total;
							?>

							<div class="bottom-push-30" align="center">
								<div class="cs-width-700">
									<h3 class="large nobold alignlt bottom-pull-7">Total Occupied Rooms: <?php echo $gt_roomCheckedin[0]['total']; ?></h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Booking Type</td>
											<td class="alignct default-text-font-bold">Early Check-in</td>
											<td class="alignct default-text-font-bold">Late Check-out</td>
											<td class="alignct default-text-font-bold">Day Use</td>
											<td class="alignct default-text-font-bold">Full Rate</td>
											<td class="alignct default-text-font-bold">Discount Rate</td>
											<td class="alignct default-text-font-bold">Total</td>
										</tr>
										<tr>
											<td class="alignlt">Individual</td>
											<td class="alignrt"><?php echo $individual_early_checkin_total; ?></td>
											<td class="alignrt"><?php echo $individual_late_checkout_total; ?></td>
											<td class="alignrt"><?php echo $individual_dayuse_total; ?></td>
											<td class="alignrt"><?php echo $individual_fullrate_total; ?></td>
											<td class="alignrt"><?php echo $individual_discountrate_total; ?></td>
											<td class="alignrt"><?php echo $individual_g_total; ?></td>
										</tr>
										<tr>
											<td class="alignlt">Corporate</td>
											<td class="alignrt"><?php echo $corporate_early_checkin_total; ?></td>
											<td class="alignrt"><?php echo $corporate_late_checkout_total; ?></td>
											<td class="alignrt"><?php echo $corporate_dayuse_total; ?></td>
											<td class="alignrt"><?php echo $corporate_fullrate_total; ?></td>
											<td class="alignrt"><?php echo $corporate_discountrate_total; ?></td>
											<td class="alignrt"><?php echo $corporate_g_total; ?></td>
										</tr>
										<tr>
											<td class="alignlt">Agent</td>
											<td class="alignrt"><?php echo $agent_early_checkin_total; ?></td>
											<td class="alignrt"><?php echo $agent_late_checkout_total; ?></td>
											<td class="alignrt"><?php echo $agent_dayuse_total; ?></td>
											<td class="alignrt"><?php echo $agent_fullrate_total; ?></td>
											<td class="alignrt"><?php echo $agent_discountrate_total; ?></td>
											<td class="alignrt"><?php echo $agent_g_total; ?></td>
										</tr>
										<tr>
											<td class="alignlt">Complimentary</td>
											<td class="alignrt"><?php echo $complimentary_early_checkin_total; ?></td>
											<td class="alignrt"><?php echo $complimentary_late_checkout_total; ?></td>
											<td class="alignrt"><?php echo $complimentary_dayuse_total; ?></td>
											<td class="alignrt"><?php echo $complimentary_fullrate_total; ?></td>
											<td class="alignrt"><?php echo $complimentary_discountrate_total; ?></td>
											<td class="alignrt"><?php echo $complimentary_g_total; ?></td>
										</tr>
										<tr>
											<td class="alignlt">Online</td>
											<td class="alignrt"><?php echo $online_early_checkin_total; ?></td>
											<td class="alignrt"><?php echo $online_late_checkout_total; ?></td>
											<td class="alignrt"><?php echo $online_dayuse_total; ?></td>
											<td class="alignrt"><?php echo $online_fullrate_total; ?></td>
											<td class="alignrt"><?php echo $online_discountrate_total; ?></td>
											<td class="alignrt"><?php echo $online_g_total; ?></td>
										</tr>
										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td class="alignrt default-text-font-bold"><?php echo $early_checkin_total; ?></td>
											<td class="alignrt default-text-font-bold"><?php echo $late_checkout_total; ?></td>
											<td class="alignrt default-text-font-bold"><?php echo $dayuse_total; ?></td>
											<td class="alignrt default-text-font-bold"><?php echo $fullrate_total; ?></td>
											<td class="alignrt default-text-font-bold"><?php echo $discountrate_total; ?></td>
											<td class="alignrt default-text-font-bold"><?php echo $g_total; ?></td>
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

<div id="fbox"></div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function openpayt(paytype,datelog) {
		popmodalframe('reports','paytype_day_report',paytype,datelog,1000,1500);
	}

</script>