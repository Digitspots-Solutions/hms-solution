<?php

	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(isset($_SESSION['postoreid'])) { $cur_pos_store_id = $_SESSION['postoreid']; }
	else { $cur_pos_store_id = 0; }

	//$posname = idget_data($tbL14,$cur_pos_store_id,'posname');

	#get pos tax
	/*$pos_tax_selection_key = array("postoreid"=>$cur_pos_store_id);
	$get_tax_data = mysqli_data_fetch($tbL18,'id,taxcharge',$pos_tax_selection_key,'noarray');
	if(isset($get_tax_data[0]) && $get_tax_data[0] >= 1) { $pos_tax = $get_tax_data[1]; } else { $pos_tax = 0; }*/

	$pos_tax = 0;

	$pos_stores = select_dt_fetch('iscounter','Yes',$tbL14,'id','posname');
	$shift_list = mt_select_fetch('status','Active',$tbL20,'id','shiftname','','');
	
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Select necessary information to generate revenue report
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="nc-width-100 x-scroll bottom-push-20">
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<span class="ln-display-box float-left right-push-10">
			<select name="period" id="period" onchange="dateStat()" required="required">
				<option value="" selected="selected">Select Period</option>
				<option value="Today">Today</option>
				<option value="Yesterday">Yesterday</option>
				<option value="Custom Date">Custom Date</option>
			</select>
		</span>
		<span class="ln-display-box float-left right-push-10">
			<div id="custom-date" class="noshow">
				<div class="ln-display-box float-left right-push-10">
					<input type="date" name="startdate" id="startdate" title="From date">
				</div>
				<div class="ln-display-box float-left right-push-10">
					<input type="date" name="endate" id="endate" title="To date">
				</div>
			</div>
		</span>
		
		<?php if(isset($_SESSION['sesid']) && $_SESSION['sesid'] == 'report'): ?>

		<span class="ln-display-box float-left cs-width-200 right-push-10">
			<select name="stores[]" id="stores" size="2" multiple>
				<option value="" selected="selected">Outlets</option>
				<option value="0">All</option>
				<?php echo $pos_stores; ?>
			</select>
		</span>

		<?php endif;?>

		<span class="ln-display-box float-left right-push-10">
			<select name="category" id="category">
				<option value="" selected="selected">Category</option>
				<option value="0">All</option>
				<?php echo $list_outlet_category; ?>
			</select>
		</span>
		<span class="ln-display-box float-left right-push-10">
			<select name="shift" id="shift">
				<option value="" selected="selected">Shift</option>
				<option value="0">All</option>
				<?php echo $shift_list; ?>
			</select>
		</span>
		<span class="ln-display-box float-right">
			<input type="submit" name="searchbutton" value="Run" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
		</span>
	</form>
</div>

<div class="block-element box-border-thick pads15 sml-rounded-button">

	<?php

		if(isset($_POST['searchbutton'])) {
		
			if(is_array($_POST['stores']) && count($_POST['stores']) > 0) {
				$selectedOutlets = ""; foreach($_POST['stores'] as $wrk_outlets) { $selectedOutlets .= $wrk_outlets.","; }
				$selectedOutlets_fx = substr_replace($selectedOutlets,'',-1,1);
				if(!empty($selectedOutlets_fx)) { $addtheoutlets = " AND posid IN({$selectedOutlets_fx})"; }
				else { $addtheoutlets = ""; }
			} else {
				if(isset($cur_pos_store_id) && $cur_pos_store_id > 0) {
					$addtheoutlets = " AND posid IN({$cur_pos_store_id})";
				} else {
					$addtheoutlets = "";
				}
			}

			$custom_date_start=""; $custom_date_end="";

			switch ($_POST['period']) {
				case 'Today':
					$custom_date_start=$server_get_date; $custom_date_end=$server_get_date;
					$date_query = "(".date("d/m/Y",strtotime($server_get_date)).")";
					$date_aQuery = " AND datelogged BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
					break;

				case 'Yesterday':
					$get_past_date = date("Y-m-d",strtotime("-1 day"));
					$custom_date_start=$get_past_date; $custom_date_end=$get_past_date;
					$date_query = "(".date("d/m/Y",strtotime("-1 day")).")";
					$date_aQuery = " AND datelogged BETWEEN '".$get_past_date."' AND '".$get_past_date."'";
					break;
				
				case 'Custom Date':
					$custom_date_start=$_POST['startdate']; $custom_date_end=$_POST['endate'];
					$date_query = "(Period Between ".date("d/m/Y",strtotime($_POST['startdate']))." And ".date("d/m/Y",strtotime($_POST['endate'])).")";;
					$date_aQuery = " AND datelogged BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";
					break;

				default:
					$query = "";
					$date_query = "";
					$date_aQuery = "";
					$custom_date_start=$server_get_date; $custom_date_end=$server_get_date;
					break;
			}


			if(isset($_POST['category']) && $_POST['category'] > 0) {
				$get_product_name = arrayget_key($outlet_category_type,$_POST['category']);
				$product_query = "(".$get_product_name.")";
				$product_category = " AND main_category='{$_POST['category']}'";
			} else {
				$product_query = "All Categories";
				$product_category = "";
			}


			if(isset($_POST['shift']) && $_POST['shift'] > 0) {
				$wget_shift = $_POST['shift'];
				$get_shift_name = idget_data($tbL20,$wget_shift,'shiftname');
				$get_shift_name .= " [";
				$get_shift_name .= idget_data($tbL20,$wget_shift,'startimelabel');
				$get_shift_name .= " &mdash; ";
				$get_shift_name .= idget_data($tbL20,$wget_shift,'endtimelabel');
				$get_shift_name .= "]";
				$shift_query = "(".$get_shift_name." Shift)";
				$shift = " AND shiftid={$wget_shift}";
				$nshift = " AND t1.shiftid={$wget_shift}";
			} else {
				$wget_shift = "";
				$shift_query = "All Shifts";
				$shift = "";
				$nshift = "";
			}


			?>
				<p class="top-pull-10 bottom-pull-10 right-pull-30 alignrt">
					<a href="javascript:void(0)" class="blue-font" onclick="window.print()"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print</a>
				</p>
				<div id="section-to-print" class="block-element">
					<div class="block-element alignct bottom-push-20">
						<h1 class="large"><?php echo _LONG_NAME; ?></h1>
						<small class="block-element add-bold bottom-push-3">Pos Revenue Report for <?php echo $date_query; ?> for <?php echo $product_query; ?> for <?php echo $shift_query; ?></small><small class="block-element">Printed By: <u><?php echo $admin_name; ?></u> as at <?php echo date("d/m/Y",strtotime($server_get_date)).' '.$server_get_time; ?></small>
					</div>

					<?php
						$sql_1 = "SELECT main_category FROM {$tbL99} WHERE isreversed=0 AND deletedata=0".$product_category." GROUP BY main_category"; $groupdata1 = wgetSQL($sql_1);

						if(is_array($groupdata1)) {
							
							$ctg_base_arry = array();

							$grandtotaltaxes = 0; $base_actual_cost_per = 0;

							foreach($groupdata1 as $ctg_ky => $ctg_vl) {

								$category_name = $outlet_category_type[$ctg_vl['main_category']];

								$base_arry = array();
								$base_arry['categoryname'] = $category_name;

								?>
									<div class="block-element bottom-push-20">
										<h4 class="large blue-font bottom-pull-5"><?php echo $category_name; ?></h4>
										<table cellpadding="0" cellspacing="0">
											<tr>
												<td class="default-text-font-bold" align="center">Outlet</td>
												<td class="default-text-font-bold" align="center">Charge Room</td>
												<td class="default-text-font-bold" align="center">Discount</td>
												<td class="default-text-font-bold" align="center">Staff</td>
												<td class="default-text-font-bold" align="center">Cash</td>
												<td class="default-text-font-bold" align="center">Compl.</td>
												<td class="default-text-font-bold" align="center">Group</td>
												<td class="default-text-font-bold" align="center">F. Covers</td>
												<td class="default-text-font-bold" align="center">Total Rev. (Incl. T)</td>
												<td class="default-text-font-bold" align="center">S.C</td>
												<td class="default-text-font-bold" align="center">VAT</td>
												<td class="default-text-font-bold" align="center">C.S</td>
												<td class="default-text-font-bold" align="center">Total Rev. (Excl. T)</td>
												<td class="default-text-font-bold" align="center">Actual Cost</td>
												<td class="default-text-font-bold" align="center">Actual Cost (%)</td>
											</tr>

											<?php
												$sql_2 = "SELECT posid FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND isreversed=0 AND deletedata=0".$shift.$addtheoutlets.$date_aQuery." GROUP BY posid";
												$groupdata2 = wgetSQL($sql_2);

												if(is_array($groupdata2)) {
													
													$get_outlet_name = ""; $get_outlet_type = "";
													$totalchargeroom = 0; $totaldiscount = 0; $totalchargestaff = 0;
													$totalchargecash = 0; $totalchargecompl = 0; $totalincl = 0;
													$totalchargegroup = 0; $totalfoodcovers = 0; $totalrevenueincltax = 0;
													$totaltax1 = 0; $totaltax2 = 0; $totaltax3 = 0; $totalrevenueexcltax = 0;
													$totalactualcost = 0; $totalcostper = 0; $revenueexcltax = 0;
													$actual_cost_per = 0; $actual_cost = 0; $revenueincltax = 0;

													$tax_1 = 0; $tax_2 = 0; $tax_3 = 0;

													$sql_in1 = ""; $sql_in2 = ""; $sql_in3 = ""; $sql_in4 = ""; $sql_in5 = "";
													$sql_in6 = ""; $sql_in7 = ""; $sql_in8 = ""; $sql_in9 = ""; $sql_in10 = "";
													$sql_in11 = ""; $sql_in12 = ""; $sql_in13 = ""; $sql_in14 = ""; $sql_in15 = "";

													foreach($groupdata2 as $pos_ky => $pos_vl) {
														
														$get_outlet_name = idget_data($tbL14,$pos_vl['posid'],'posname');
														$get_outlet_type = idget_data($tbL14,$pos_vl['posid'],'postype');

														$sql_in1 = "SELECT SUM(amount) AS totalchargeroom, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(2) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

														$dataset_in1 = wgetSQL($sql_in1);

														$sql_in2 = "SELECT SUM(discount) AS totaldiscount FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND isreversed=0 AND status IN('Completed') AND deletedata=0".$shift.$date_aQuery;

														$dataset_in2 = wgetSQL($sql_in2);

														$sql_in3 = "SELECT SUM(amount) AS totalchargestaff, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(5) AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

														$dataset_in3 = wgetSQL($sql_in3);

														$sql_in4 = "SELECT SUM(amount) AS totalchargecash, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(1) AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

														$dataset_in4 = wgetSQL($sql_in4);

														$sql_in5 = "SELECT SUM(amount) AS totalchargecompl, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND (billtype=3 OR iscomplimentary > 0) AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

														$dataset_in5 = wgetSQL($sql_in5);

														$sql_in6 = "SELECT SUM(amount) AS totalchargegroup, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(4) AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

														$dataset_in6 = wgetSQL($sql_in6);

														if($ctg_vl['main_category'] == 1) {
															$sql_in7 = "SELECT SUM(cover) AS totalcovers FROM {$tbL100} WHERE posid={$pos_vl['posid']} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

															$dataset_in7 = wgetSQL($sql_in7);

														} else {
															$dataset_in7 = array(array("totalcovers"=>0));
														}

														
														if($get_outlet_type == 'Establishment') {
															
															$sql_in8 = "SELECT * FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

															$dataset_in8 = wgetSQL($sql_in8);

															if(is_array($dataset_in8) && count($dataset_in8) > 0) {
																
																$t_amount = 0; $t_vat = 0; $t_service = 0; $t_consumption = 0;
																
																foreach($dataset_in8 as $key => $val) {
																	$amount = $val['amount'] - $val['discount'];
																	
																	$sql_in8x = "SELECT * FROM {$tbL100} WHERE order_number='{$val['order_number']}'"; $dataset_in8x = wgetSQL($sql_in8x);

																	if($dataset_in8x[0]['tax_amount'] > 0) { $vat_amount = ($gh_get_vat / 100) * $amount; } else { $vat_amount = 0; }

																	if($dataset_in8x[0]['service_charge_amount'] > 0) { $service_amount = ($gh_get_service_charge / 100) * $amount; } else { $service_amount = 0; }

																	if($dataset_in8x[0]['consumption_amount'] > 0) { $consumption_amount = ($gh_get_consumption_tax / 100) * $amount; } else { $consumption_amount = 0; }

																	$t_amount = $t_amount + $amount;
																	$t_vat = $t_vat + $vat_amount;
																	$t_service = $t_service + $service_amount;
																	$t_consumption = $t_consumption + $consumption_amount;
																}

																$tax_1 = $t_service;
																$tax_2 = $t_vat;
																$tax_3 = $t_consumption;

																$revenueexcltax = $t_amount;
																$revenueincltax = $t_amount + $tax_1 + $tax_2 + $tax_3;

																$t_amount = 0; $t_vat = 0; $t_service = 0; $t_consumption = 0;

															} else {
																$tax_1 = 0;
																$tax_2 = 0;
																$tax_3 = 0;

																$revenueexcltax = 0;
																$revenueincltax = 0;
															}

															
														} else {
															
															$sql_in8 = "SELECT SUM(amount) AS totalrevenueincltax, SUM(discount) AS tdisc FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;

															$dataset_in8 = wgetSQL($sql_in8);
															$revenueincltax = $dataset_in8[0]['totalrevenueincltax'] - $dataset_in8[0]['tdisc'];

															if($htx2 == 1) { $tax_1 = ($gh_get_service_charge / 100) * $revenueincltax; }
															else { $tax_1 = 0; }

															if($htx3 == 1) { $tax_2 = ($gh_get_vat / 100) * $revenueincltax; }
															else { $tax_2 = 0; }

															if($htx1 == 1) { $tax_3 = ($gh_get_consumption_tax / 100) * $revenueincltax; }
															else { $tax_3 = 0; }

															$revenueexcltax = $revenueincltax - ($tax_1 + $tax_2 + $tax_3);
															$revenueincltax = $revenueincltax;
														}


														$sql_in10 = "SELECT qty,cost FROM {$tbL99} WHERE main_category={$ctg_vl['main_category']} AND posid={$pos_vl['posid']} AND billtype IN(1,2,4,5) AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$shift.$date_aQuery;
														$dataset_in10 = wgetSQL($sql_in10);
														if(is_array($dataset_in10) && count($dataset_in10) > 0) {
															$cost = 0;
															foreach($dataset_in10 as $cqkey => $cqval) {
																$cost = $cost + ($cqval['qty'] * $cqval['cost']);
															}
															$actual_cost = $cost;
														} else {
															$actual_cost = 0;
														}

														//$actual_cost = $dataset_in10[0]['totalqty'] * $dataset_in10[0]['totalcost'];
														//$actual_cost = $dataset_in10[0]['totalcost'];
														$actual_cost_per = ($actual_cost > 0) ? (($actual_cost * 100) / $revenueexcltax) : 0;

														$totalchargeroom = $totalchargeroom + $dataset_in1[0]['totalchargeroom'];
														$totaldiscount = $totaldiscount + $dataset_in2[0]['totaldiscount'];
														$totalchargestaff = $totalchargestaff + $dataset_in3[0]['totalchargestaff'];
														$totalchargecash = $totalchargecash + $dataset_in4[0]['totalchargecash'];
														$totalchargecompl = $totalchargecompl + $dataset_in5[0]['totalchargecompl'];
														$totalchargegroup = $totalchargegroup + $dataset_in6[0]['totalchargegroup'];
														$totalfoodcovers = $totalfoodcovers + $dataset_in7[0]['totalcovers'];
														$totalrevenueincltax = $totalrevenueincltax + $revenueincltax;

														$totaltax1 = $totaltax1 + $tax_1;
														$totaltax2 = $totaltax2 + $tax_2;
														$totaltax3 = $totaltax3 + $tax_3;

														$totalrevenueexcltax = $totalrevenueexcltax + $revenueexcltax;
														$totalactualcost = $totalactualcost + $actual_cost;

														?>
															<tr>
																<td align="center"><?php echo $get_outlet_name; ?></td>
																<td align="center"><?php echo number_format($dataset_in1[0]['totalchargeroom'],2); ?></td>
																<td align="center"><?php echo number_format($dataset_in2[0]['totaldiscount'],2); ?></td>
																<td align="center"><?php echo number_format($dataset_in3[0]['totalchargestaff'],2); ?></td>
																<td align="center"><?php echo number_format($dataset_in4[0]['totalchargecash'],2); ?></td>
																<td align="center"><?php echo number_format($dataset_in5[0]['totalchargecompl'],2); ?></td>
																<td align="center"><?php echo number_format($dataset_in6[0]['totalchargegroup'],2); ?></td>
																<td align="center"><?php echo $dataset_in7[0]['totalcovers']; ?></td>
																<td align="center"><?php echo number_format($revenueincltax,2); ?></td>
																<td align="center"><?php echo number_format($tax_1,2); ?></td>
																<td align="center"><?php echo number_format($tax_2,2); ?></td>
																<td align="center"><?php echo number_format($tax_3,2); ?></td>
																<td align="center"><?php echo number_format($revenueexcltax,2); ?></td>
																<td align="center"><?php echo number_format($actual_cost,2); ?></td>
																<td align="center"><?php echo number_format($actual_cost_per,2); ?></td>
															</tr>
														<?php
													}

													$grandtotaltaxes = $totaltax1 + $totaltax2 + $totaltax3;
													$base_actual_cost_per = ($totalactualcost > 0) ? (($totalactualcost * 100) / $totalrevenueexcltax) : 0;

													$base_arry['revenueexcl'] = $totalrevenueexcltax;
													$base_arry['taxes'] = $grandtotaltaxes;
													$base_arry['revenueincl'] = $totalrevenueincltax;
													$base_arry['actualcost'] = $totalactualcost;
													$base_arry['actualcostper'] = $base_actual_cost_per;

													array_push($ctg_base_arry,$base_arry);

													?>
														<tr>
															<td class="default-text-font-bold" align="center">Total</td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalchargeroom,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totaldiscount,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalchargestaff,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalchargecash,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalchargecompl,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalchargegroup,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo $totalfoodcovers; ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalrevenueincltax,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totaltax1,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totaltax2,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totaltax3,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalrevenueexcltax,2); ?></td>
															<td class="default-text-font-bold" align="center"><?php echo number_format($totalactualcost,2); ?></td>
															<td align="center">&nbsp;</td>
														</tr>
													<?php
												}
											?>

										</table>
									</div>
								<?php
							}


							#base report

							if(is_array($ctg_base_arry)) {
												
								?>
									<div class="block-element top-push-50 bottom-push-20" align="center">
										<div class="cs-width-600">
											<h4 class="large nobold">Report summary for pos revenue report for <?php echo $date_query; ?> for <?php echo $product_query; ?> for <?php echo $shift_query; ?></h4><br>
											
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td class="default-text-font-bold" align="center">Category</td>
													<td class="default-text-font-bold" align="center">Revenue (Excl. T)</td>
													<td class="default-text-font-bold" align="center">Taxes</td>
													<td class="default-text-font-bold" align="center">Revenue (Incl. T)</td>
													<td class="default-text-font-bold" align="center">Actual Cost</td>
													<td class="default-text-font-bold" align="center">Cost %</td>
												</tr>

												<?php

													$total_sum_with_no_tax = 0; $total_sum_with_tax = 0; $total_tax_sum = 0;
													$total_sum_actual_cost = 0;$total_sum_actual_cost_ = 0;

													foreach($ctg_base_arry as $base => $val) {
														
														$total_sum_with_no_tax = $total_sum_with_no_tax + $val['revenueexcl'];
														$total_tax_sum = $total_tax_sum + $val['taxes'];
														$total_sum_with_tax = $total_sum_with_tax + $val['revenueincl'];
														$total_sum_actual_cost = $total_sum_actual_cost + $val['actualcost'];
														$total_sum_actual_cost_ = $total_sum_actual_cost_ + $val['actualcostper'];

														?>
															<tr>
																<td align="center"><?php echo $val['categoryname']; ?></td>
																<td align="center">&#8358;<?php echo number_format($val['revenueexcl'],2); ?></td>
																<td align="center">&#8358;<?php echo number_format($val['taxes'],2); ?></td>
																<td align="center">&#8358;<?php echo number_format($val['revenueincl'],2); ?></td>
																<td align="center">&#8358;<?php echo number_format($val['actualcost'],2); ?></td>
																<td align="center"><?php echo number_format($val['actualcostper'],2); ?></td>
															</tr>
														<?php
													}

												?>

												<tr>
													<td class="default-text-font-bold" align="center"><b>Total</b></td>
													<td class="default-text-font-bold" align="center">&#8358;<?php echo number_format($total_sum_with_no_tax,2); ?></td>
													<td class="default-text-font-bold" align="center">&#8358;<?php echo number_format($total_tax_sum,2); ?></td>
													<td class="default-text-font-bold" align="center">&#8358;<?php echo number_format($total_sum_with_tax,2); ?></td>
													<td class="default-text-font-bold" align="center">&#8358;<?php echo number_format($total_sum_actual_cost,2); ?></td>
													<td class="default-text-font-bold" align="center">&nbsp;</td>
												</tr>

											</table>
										</div>
									</div>

								<?php
							}

							#end here
						}

					?>

				</div>

			    <h4 class="large nobold default-text-font-bold">Note:</h4>
			    <ul>
			    	<li class="ft-sml-size">Total revenue amount is calculated with out complimentary and discount amount</li>
			    	<li class="ft-sml-size">Cost % = Actual Cost * 100 / Revenue(Exclusive of taxes)</li>
			    </ul>

			<?php
		}

	?>

</div>


<script>

	function dateStat() {
		var d = document.getElementById('period').value;
		if(d == 'Custom Date') {
			objDisplay('custom-date');
			document.getElementById('startdate').required = true;
			document.getElementById('endate').required = true;
		} else {
			objHidden('custom-date');
			document.getElementById('startdate').required = false;
			document.getElementById('endate').required = false;
		}
	}

</script>