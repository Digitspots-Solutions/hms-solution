<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../../../includes/common_data_vars.php";

include "../../../includes/class.vars.php";
include "../../../includes/class.function.php";

$shiftid = isset($_GET['shift']) ? $_GET['shift'] : $current_shift;
$cashierid = isset($_GET['cashier']) ? $_GET['cashier'] : $userSignedIn;
$shift_start_date = isset($_GET['trd1']) ? $_GET['trd1'] : $server_get_date;
$shift_end_date = isset($_GET['trd2']) ? $_GET['trd2'] : $server_get_date;

if(!isset($cur_pos_store_id) && empty($cur_pos_store_id)) { $cur_pos_store_id = $_GET['store']; }
else { $cur_pos_store_id = $cur_pos_store_id; }

$get_pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');

$shift_label = idget_data($tbL20,$shiftid,'shiftname');
$app_user = idget_name($cashierid,'staffname',$tbL7);

$keywords = " AND shiftid={$shiftid} AND cashier={$cashierid} AND datelogged BETWEEN '{$shift_start_date}' AND '{$shift_end_date}'";

$bill2type_arry = array();

$sql = "SELECT * FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND status IN('Completed') AND deletedata=0".$keywords;
$result = wgetSQL($sql);

?>
	
<link rel="stylesheet" href="../../../style/csslibrary/default.css" media="all" />
<link rel="stylesheet" href="../../../style/custom.css" media="all" />
<link rel="stylesheet" href="applystyle.css" media="all" />
<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../../js/jspath.js"></script>
<script type="text/javascript" src="../../../js/jsbk.js"></script>

<title>POS Shift Wise Report (<?php echo $shift_label; ?>: <?php echo $app_user; ?>)</title>

<p class="top-pull-10 right-pull-30 alignrt">
	<input type="button" value="Print" onclick="window.print()" class="anchor"> <?php if(isset($_SESSION['return2work']) && $_SESSION['return2work'] == 'yes') { ?><input type="button" value="Return to Work" onclick="window.location.href='<?php echo DOMAIN_URL.PUB_FLD.'admin/portal'.PHP_EXT; ?>'" class="anchor left-push-10"><?php } elseif(isset($_SESSION['return2work']) && $_SESSION['return2work'] == 'no') { ?><input type="button" value="Start New Shift" onclick="window.location.href='<?php echo DOMAIN_URL.'login/close-session'.PHP_EXT; ?>?sesid=end'" class="anchor left-push-10"><?php } ?>
</p>

<div id="section-to-print" class="pads30">
	<div class="bottom-push-15" align="center">
		<div class="cs-width-100 bottom-push-10 noscroll">
			<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
		</div>
		<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
		<h3 class="large nobold nomargin">POS Shift Wise Report (<?php echo $shift_label; ?>: <?php echo $app_user; ?>)</h3><h3 class="large nobold nomargin">Between <?php echo date('d-m-y',strtotime($shift_start_date)); ?> and <?php echo date('d-m-y',strtotime($shift_end_date)); ?></h3>

		<h3 class="large nobold default-text-font-bold">&mdash; <?php echo $get_pos_name; ?> &mdash;</h3>
	</div>

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
				$total_others_amount = 0; $grand_total = 0;

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
					//$customer = idget_name($val['customerid'],'fname',$tbL102);
					//$customer .= idget_name($val['customerid'],'lname',$tbL102);

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
					$orderno_sql = "SELECT * FROM {$tbL99} WHERE order_number='{$val['order_number']}' AND isreversed=0 AND status IN('Completed') AND deletedata=0";
					$orderno_view = wgetSQL($orderno_sql);
					
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

					$get_bill_type = ""; $biller = ""; $customer = ""; $get_or_date = "";
					$food_amount = 0; $beverage_amount = 0; $others_amount = 0;  $total_amount = 0;
					$cashier = "";
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
		<table style="width: 450px !important" cellpadding="3" cellspacing="0" border="1">
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
</div>

<?php
	
	if(isset($_SESSION['counter'])) { unset($_SESSION['counter']); }
	if(isset($_SESSION['shift'])) { unset($_SESSION['shift']); }
	if(isset($_SESSION['user'])) { unset($_SESSION['user']); }
	if(isset($_SESSION['from'])) { unset($_SESSION['from']); }
	if(isset($_SESSION['to'])) { unset($_SESSION['to']); }

?>


<script>

	function jsxView(key) {
		popmodalframe('pos','pos_post_bill_review',key,0,1000,2500);
	}

</script>