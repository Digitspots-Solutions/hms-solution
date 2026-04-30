<?php
$smdl = "pos"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');
$get_pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Shiftwise Sales Report</b>: here you can see the transactions done in shift
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
				<h3 class="large nobold default-text-font-bold">By Shift</h3>
				<select name="shift" id="shift" class="nopads no-back-black" onchange="getdata('user','eget-shift-users','shift','dropbox');">
					<option value="" selected="selected">All</option>
					<?php echo $get_shifts; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By User</h3>
				<select name="user" id="user" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Day</h3>
				<select name="datetype" id="datetype" class="nopads no-back-black">
					<option value="Order Date" selected="selected">Order Date</option>
					<option value="Business Date">Business Date</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
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
		<div>
			<div id="section-to-print" class="cs-width-1200">
				
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$tbl = $tbL100;

						$startnumbr = 0;
						$shift_name = ""; $keywords = ""; $pick_option_date = 1;
						$shift_label = "All Shifts"; $app_user = "All Users";
						$startdate = ""; $endate = ""; $swsr_option = 0;
						$shift = 1; $user = 0; $w_startdate = ""; $w_endate = ""; $swsr_option = 1;

						$shift_label = idget_data($tbL20,1,'shiftname');

						if(isset($_POST['shift']) && !empty($_POST['shift'])) {
							$shift = $_POST['shift'];
							$keywords .= " AND shiftid={$shift}";
							$shift_label = idget_data($tbL20,$_POST['shift'],'shiftname');
							$swsr_option = 2;
						}

						if(isset($_POST['user']) && !empty($_POST['user'])) {
							$user = $_POST['user'];
							$keywords .= " AND cashier={$user}";
							$app_user = idget_data($tbL7,$_POST['user'],'staffname');
							$swsr_option = 2;
						}

						if(isset($_POST['datetype']) && !empty($_POST['datetype'])) {
						
							if($_POST['datetype'] === 'Business Date') {
								
								$for_bdt = "SELECT id FROM hotel_businessday_tbl WHERE (startdate='{$_POST['startdate']}' AND enddate='{$_POST['endate']}') OR (startdate='{$_POST['startdate']}' AND enddate='0000-00-00')";
									$startdate = date('d-m-Y',strtotime($_POST['startdate']));
									$endate = date('d-m-Y',strtotime($_POST['endate']));
									$w_startdate = $_POST['startdate']; $w_endate = $_POST['endate'];
								
								$biz = mysqli_data_array('assoc',$for_bdt);

								if(is_array($biz) && count($biz) > 0) {
									$get_biz = ""; foreach($biz as $key => $val) { $get_biz .= $val['id'].","; }
									$get_biz = substr_replace($get_biz,'',-1,1);
									$keywords .= " AND bizday IN({$get_biz})";
								} else {
									$keywords .= "";
								}

								/*$startdate = date('d-m-Y',strtotime($_POST['startdate']));
								$endate = date('d-m-Y',strtotime($_POST['endate']));

								$w_startdate = $_POST['startdate']; $w_endate = $_POST['endate'];
								$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";*/

							} elseif($_POST['datetype'] === 'Order Date') {
								
								$startdate = date('d-m-Y',strtotime($_POST['startdate']));
								$endate = date('d-m-Y',strtotime($_POST['endate']));

								$w_startdate = $_POST['startdate']; $w_endate = $_POST['endate'];
								$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
							}

						}

						?>

							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">POS Shift Wise Report (<?php echo $shift_label; ?>: <?php echo $app_user; ?>)</h3><h3 class="large nobold default-text-font-bold">Between <?php echo $startdate; ?> and <?php echo $endate; ?></h3>

								<h3 class="large nobold">&mdash; <?php echo $get_pos_name; ?> &mdash;</h3>
							</div>

						<?php

						if($pick_option_date == 1 && $swsr_option == 1) {

							$tbl_tds = ""; $tbl_tds_value = ""; $tbl_tds_base = ""; $tbl_tds_base_value = array();

							if(is_array($bill_type) && count($bill_type) > 0) {
								
								foreach($bill_type as $key => $val) {
									
									if($key == 2) {
										$sql = "SELECT SUM(bill_amount) AS totalsales FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND billtype=2 AND iscomplimentary=0 AND status IN('Completed') AND shiftid={$shift} AND datelogged BETWEEN '{$w_startdate}' AND '{$w_endate}'";
									} elseif($key == 3) {
										$sql = "SELECT SUM(bill_amount) AS totalsales FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND (billtype=3 OR iscomplimentary > 0) AND status IN('Completed') AND shiftid={$shift} AND datelogged BETWEEN '{$w_startdate}' AND '{$w_endate}'";
									} else {
										$sql = "SELECT SUM(bill_amount) AS totalsales FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND billtype={$key} AND status IN('Completed') AND shiftid={$shift} AND datelogged BETWEEN '{$w_startdate}' AND '{$w_endate}'";
									}

									$result = wgetSQL($sql);
									
									$tbl_tds .= '<td class="alignct default-text-font-bold">'.$val.'</td>';
									$tbl_tds_value .= '<td class="alignrt">'.number_format($result[0]['totalsales'],2).'</td>';

									array_push($tbl_tds_base_value, $result[0]['totalsales']);

									$sql = "";
								}

								foreach($tbl_tds_base_value as $key_b => $val_b) {
									$tbl_tds_base .= '<td class="alignrt default-text-font-bold">'.number_format($val_b,2).'</td>';
								}
							}

							?>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Shift</td>
										<?php echo $tbl_tds; ?>
									</tr>
									<tr>
										<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="adView('<?php echo $shift.'/'.$user.'/'.$w_startdate.'/'.$w_endate; ?>')"><?php echo $shift_label; ?></a></td>
										<?php echo $tbl_tds_value; ?>
									</tr>
									<tr>
										<td class="alignlt default-text-font-bold">Total</td>
										<?php echo $tbl_tds_base; ?>
									</tr>
								</table>
							<?php

						} elseif($pick_option_date == 1 && $swsr_option == 2) {

							$bill2type_arry = array();

							$sql = "SELECT * FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND status IN('Completed') AND deletedata=0".$keywords;
							$result = wgetSQL($sql);

							?>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Order No.</td>
										<td class="alignct default-text-font-bold">Bill Type</td>
										<td class="alignct default-text-font-bold">Bill To</td>
										<td class="alignct default-text-font-bold">Receipt No.</td>
										<td class="alignct default-text-font-bold">Cover</td>
										<td class="alignct default-text-font-bold">Ordered Date</td>
										<td class="alignct default-text-font-bold">Food Amount</td>
										<td class="alignct default-text-font-bold">Beverage Amount</td>
										<td class="alignct default-text-font-bold">Other Amount</td>
										<td class="alignct default-text-font-bold">Total</td>
										<td class="alignct default-text-font-bold">Created By</td>
										<td class="alignct default-text-font-bold">Confirmed BY</td>
									</tr>
									
									<?php
										if(is_array($result) && count($result) > 0) {
											
											$total_covers = 0; $total_food_amount = 0; $total_beverages_amount = 0;
											$total_others_amount = 0; $grand_total = 0; $get_damt = 0;

											foreach($result as $key => $val) {
												
												//for bill type
												$get_bill_type = arrayget_key($bill_type,$val['billtype']);
												
												if($val['iscomplimentary'] > 0) {
												
													$bt_sql = "SELECT SUM(amount) AS totalsales, SUM(discount) AS totaldiscount FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND ((billtype=2 AND iscomplimentary > 0) OR (billtype=3)) AND status IN('Completed')".$keywords;
													
													$bt_view = wgetSQL($bt_sql);
													
													$bill2type_arry['Complimentary'] = $bt_view[0]['totalsales'] - $bt_view[0]['totaldiscount'];

												} elseif($val['billtype'] == 2) {
												
													$bt_sql = "SELECT SUM(amount) AS totalsales, SUM(discount) AS totaldiscount FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND billtype=2 AND iscomplimentary=0 AND status IN('Completed')".$keywords;
													
													$bt_view = wgetSQL($bt_sql);
													
													$bill2type_arry['Charge Room'] = $bt_view[0]['totalsales'] - $bt_view[0]['totaldiscount'];

												} else {

													$bt_sql = "SELECT SUM(amount) AS totalsales, SUM(discount) AS totaldiscount FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND billtype={$val['billtype']} AND status IN('Completed')".$keywords;
													
													$bt_view = wgetSQL($bt_sql);
													
													$bill2type_arry[$get_bill_type] = $bt_view[0]['totalsales'] - $bt_view[0]['totaldiscount'];
												}

												#end
												

												$cashier = idget_name($val['cashier'],'staffname',$tbL7);
												//$customer = idget_name($val['customerid'],'fname',$tbL169);
												//$customer .= idget_name($val['customerid'],'lname',$tbL169);

												if($val['biller'] > 0 && $val['billtype'] == 4) {
													$customer = idget_name($val['biller'],'name',$tbL58);
													$biller = $customer." (Corporate)";
												} else  {
													if($val['iscomplimentary'] > 0) {
														$customer = idget_name($val['iscomplimentary'],'name',$tbL33);
														$biller = $customer." (Complimentary)";
														if($val['billtype'] == 2) {
															$biller .= '<br>';
															$biller .= idget_name($val['roomid'],'roomprefix',$tbL56);
															$biller .= idget_name($val['roomid'],'roomnumber',$tbL56);
														}
													} else {
														if($val['billtype'] == 1) {
															$customer = idget_name($val['media'],'name',$tbL24);
															$biller = $customer." (Individual)";
														} elseif($val['billtype'] == 2) {
															$customer .= idget_name($val['roomid'],'roomprefix',$tbL56);
															$customer .= idget_name($val['roomid'],'roomnumber',$tbL56);
															$biller = $customer." (Individual)";
														} elseif($val['billtype'] == 5) {
															$customer .= idget_name($val['biller'],'staffname',$tbL7);
															if($val['media'] > 0) { $biller = $customer." (".idget_name($val['media'],'name',$tbL24).")"; }
															else { $biller = $customer." (No payment)"; }
														}
													}
												}

												//get order no amount analysis
												$orderno_sql = "SELECT * FROM {$tbL99} WHERE order_number='{$val['order_number']}' AND isreversed=0 AND deletedata=0"; $orderno_view = wgetSQL($orderno_sql);
												
												$food_amount = 0; $food_discounted_amount = 0;
												$beverage_amount = 0; $beverage_discounted_amount = 0;
												$others_amount = 0; $others_discounted_amount = 0;

												foreach($orderno_view as $key_view => $val_view) {
													
													$category = $val_view['main_category'];
													$category_name = idget_name($val_view['sales_category'],'category',$tbL15);
													
													if($val_view['amount'] > 0) { $amount2b = $val_view['amount']; }
													else { $amount2b = $val_view['price']; }
													
													if($category == 1) {
														$food_amount = $food_amount + $amount2b;
														$food_discounted_amount = $food_discounted_amount + $val_view['discount'];
													} elseif($category == 2) {
														$beverage_amount = $beverage_amount + $amount2b;
														$beverage_discounted_amount = $beverage_discounted_amount + $val_view['discount'];
													} elseif($category == 3) {
														$others_amount = $others_amount + $amount2b;
														$others_discounted_amount = $others_discounted_amount + $val_view['discount'];
													}

													$amount2b = 0; $category = ""; $category_name = "";
												}

												#end

												$get_or_date = write_dateF($gh_get_date_format,$val['datelogged']);

												$food_amount = $food_amount - $food_discounted_amount;
												$beverage_amount = $beverage_amount - $beverage_discounted_amount;
												$others_amount = $others_amount - $others_discounted_amount;

												$total_amount = $food_amount + $beverage_amount + $others_amount;
												$total_covers = $total_covers + $val['cover'];
												
												$total_food_amount = $total_food_amount + $food_amount;
												$total_beverages_amount = $total_beverages_amount + $beverage_amount;
												$total_others_amount = $total_others_amount + $others_amount;

												$grand_total = $grand_total + $total_amount;
												
												?>
													<tr>
														<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['order_number']; ?>')"><?php echo $val['order_number']; ?></a></td>
														<td class="alignct"><?php echo $get_bill_type; ?></td>
														<td class="alignct"><?php echo $biller; ?></td>
														<td class="alignct"><?php echo $val['receipt_number']; ?></td>
														<td class="alignct"><?php echo $val['cover']; ?></td>
														<td class="alignct"><?php echo $get_or_date; ?></td>
														<td class="alignlt">&#8358; <?php echo number_format($food_amount,2); ?></td>
														<td class="alignlt">&#8358; <?php echo number_format($beverage_amount,2); ?></td>
														<td class="alignlt">&#8358; <?php echo number_format($others_amount,2); ?></td>
														<td class="alignlt">&#8358; <?php echo number_format($total_amount,2); ?></td>
														<td class="alignct"><?php echo $cashier; ?></td>
														<td class="alignct"><?php echo $cashier; ?></td>
													</tr>
												<?php

												$get_bill_type = ""; $biller = ""; $customer = ""; $get_or_date = ""; $cashier = "";
												$food_amount = 0; $beverage_amount = 0; $others_amount = 0;  $total_amount = 0;
												$food_discounted_amount = 0; $beverage_discounted_amount = 0;
												$others_discounted_amount = 0;
											}
										}
									?>

									<tr>
										<td class="alignlt default-text-font-bold">Total</td>
										<td class="alignct default-text-font-bold"></td>
										<td class="alignct default-text-font-bold"></td>
										<td class="alignct default-text-font-bold"></td>
										<td class="alignct default-text-font-bold"><?php echo $total_covers; ?></td>
										<td class="alignct default-text-font-bold"></td>
										<td class="alignlt default-text-font-bold">&#8358; <?php echo number_format($total_food_amount,2); ?></td>
										<td class="alignlt default-text-font-bold">&#8358; <?php echo number_format($total_beverages_amount,2); ?></td>
										<td class="alignlt default-text-font-bold">&#8358; <?php echo number_format($total_others_amount,2); ?></td>
										<td class="alignlt default-text-font-bold">&#8358; <?php echo number_format($grand_total,2); ?></td>
										<td class="alignct default-text-font-bold"></td>
										<td class="alignct default-text-font-bold"></td>
									</tr>
								</table>

								<div class="cs-height-50"></div>

								<div class="float-left cs-width-350">
									<?php
										//get payment analytics
										$pay_sql = "SELECT * FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND status IN('Completed') AND isreversed=0 AND deletedata=0".$keywords; $get_analytics = wgetSQL($pay_sql);

										if(is_array($get_analytics)) {
											
											$pay_ana_ = array();
											$mode=""; $pattern=""; $mode_name="";
											
											foreach($get_analytics as $key => $val) {
												$mode = idget_data($tbL24,$val['media'],'name');
												$pattern = idget_data($tbL24,$val['media'],'paytype');

												$amt_ = "";

												if($pattern == 'Two-way Payment') {
													$mode_name = explode(' & ',$mode);
													
													if($pay_ana_[$mode_name[0]]) {
														$amt_ = $pay_ana_[$mode_name[0]] + $val['first_amount'];
														$pay_ana_[$mode_name[0]] = $amt_;
													} else {
														$amt_ = $val['first_amount'];
														$pay_ana_[$mode_name[0]] = $amt_;
													}

													if($pay_ana_[$mode_name[1]]) {
														$amt_ = $pay_ana_[$mode_name[1]] + $val['second_amount'];
														$pay_ana_[$mode_name[1]] = $amt_;
													} else {
														$amt_ = $val['second_amount'];
														$pay_ana_[$mode_name[1]] = $amt_;
													}

												} elseif($pattern == 'One-way Payment') {
													if($pay_ana_[$mode]) {
														$amt_ = $pay_ana_[$mode] + $val['first_amount'];
														$pay_ana_[$mode] = $amt_;
													} else {
														$amt_ = $val['first_amount'];
														$pay_ana_[$mode] = $amt_;
													}
												}
											}

											?>
												<h3 class="large nobold alignct">Analysis of Actual Payment Modes</h3><br>
												<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
													<tr>
														<td class="alignct default-text-font-bold">Mode</td>
														<td class="alignct default-text-font-bold">Amount</td>
													</tr>
													<?php
														if(is_array($pay_ana_) && count($pay_ana_) > 0) {
															foreach($pay_ana_ as $k => $v) {
																?>
																	<tr>
																		<td class="alignct"><?php echo strtoupper($k); ?></td>
																		<td class="alignct"><?php echo number_format($v,2); ?></td>
																	</tr>
																<?php
															}
														}
													?>
												</table>
											<?php
										}
									?>
								</div>

								<h3 class="large nobold alignct">Summary Analysis of Shiftwise Report</h3><br>

								<div align="center">
									<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Type</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>
										<tr>
											<td class="alignlt">FOOD</td>
											<td class="alignrt">&#8358; <?php echo number_format($total_food_amount,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">BEVERAGES</td>
											<td class="alignrt">&#8358; <?php echo number_format($total_beverages_amount,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">OTHERS</td>
											<td class="alignrt">&#8358; <?php echo number_format($total_others_amount,2); ?></td>
										</tr>
										<tr>
											<td class="alignlt">TOTAL</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grand_total,2); ?></td>
										</tr>
									</table>
								</div>

								<div class="cs-height-50"></div>

								<div align="center">
									<h3 class="large nobold alignct">* Bill Type</h3>
									<table style="width: 300px !important" cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Type</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>
										<?php
											if(is_array($bill2type_arry) && count($bill2type_arry) > 0) {
												$total2bt = 0;
												foreach($bill2type_arry as $key => $val) {
													?>
														<tr>
															<td class="alignlt"><?php echo $key; ?></td>
															<td class="alignrt"><?php echo number_format($val,2); ?></td>
														</tr>
													<?php
													$total2bt = $total2bt + $val;
												}
											}
										?>
										<tr>
											<td class="alignlt">Total</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total2bt,2); ?></td>
										</tr>
									</table>
								</div>

								<div class="cs-height-50"></div>

								<div align="center">
									<h3 class="large nobold alignct">* Denominations</h3>
									<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Amount in (&#8358;)</td>
											<td class="alignct default-text-font-bold">Total</td>
										</tr>
										
										<?php
											$denomination_sql = "SELECT * FROM {$tbL28} WHERE deletedata=0";
											$denominations = wgetSQL($denomination_sql);

											if(is_array($denominations)) {
												foreach($denominations as $key => $val) {
													?>
														<tr>
															<td class="alignrt"><?php echo $val['name']; ?>*</td>
															<td class="alignct default-text-font-bold"></td>
														</tr>
													<?php
												}
											}
										?>
										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td class="alignrt"></td>
										</tr>
									</table>
								</div>

							<?php

						}
					}

				?>


				

				<div class="cs-height-50"></div>

			</div>
		</div>
	</div>
</div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function adView(key) {
		popmodalframe('pos','pos_shiftwise_details',key,0,1000,2500);
	}

	function jsxView(key) {
		popmodalframe('pos','pos_post_bill_review',key,0,1000,2500);
	}

</script>