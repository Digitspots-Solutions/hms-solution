<?php
$smdl = "pos"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the list of reservations
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
					<option value="" selected="selected">Choose?</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Day</h3>
				<select name="datetype" id="datetype" class="nopads no-back-black">
					<option value="Business Date" selected="selected">Business Date</option>
					<option value="Order Date">Order Date</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
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
					
					$tbl = $tbL100;

					$startnumbr = 0;
					$shift_name = ""; $keywords = ""; $pick_option_date = 0;
					$shift_label = "All Shifts"; $app_user = "All Users";
					$startdate = ""; $endate = "";

					if(isset($_POST['shift']) && !empty($_POST['shift'])) {
						$keywords .= " AND shiftid={$_POST['shift']}";
						$shift_label = idget_data($tbL20,$_POST['shift'],'shiftname');
					}

					if(isset($_POST['user']) && !empty($_POST['user'])) {
						$keywords .= " AND cashier={$_POST['user']}";
						$app_user = idget_data($tbL7,$_POST['user'],'staffname');
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$pick_option_date = 1;
					}

					if(isset($_POST['datetype']) && !empty($_POST['datetype'])) {
					
						if($_POST['datetype'] === 'Business Date') {
							if(isset($pick_option_date) && $pick_option_date == 1) {
								$for_bdt = "SELECT id FROM hotel_businessday_tbl WHERE (startdate='{$_POST['startdate']}' AND enddate='{$_POST['endate']}') OR (startdate='{$_POST['startdate']}' AND enddate='0000-00-00')";
								$startdate = date('d-m-Y',strtotime($_POST['startdate']));
								$endate = date('d-m-Y',strtotime($_POST['endate']));
							} else {
								$for_bdt = "SELECT id FROM hotel_businessday_tbl WHERE (startdate='{$server_get_date}' AND enddate='{$server_get_date}') OR (startdate='{$server_get_date}' AND enddate='0000-00-00')";
								$startdate = date('d-m-Y',strtotime($server_get_date));
								$endate = date('d-m-Y',strtotime($server_get_date));
							}
							
							$biz = mysqli_data_array('assoc',$for_bdt);
							if(is_array($biz) && count($biz) > 0) {
								$get_biz = ""; foreach($biz as $key => $val) { $get_biz .= $val['id'].","; }
								$get_biz = substr_replace($get_biz,'',-1,1);
								$keywords .= " AND bizday IN({$get_biz})";
							} else {
								$keywords .= "";
							}

						} elseif($_POST['datetype'] === 'Order Date') {
							if($pick_option_date == 1) {
								$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
								$startdate = $_POST['startdate']; $endate = $_POST['endate'];
							} else {
								$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
								$startdate = date('d-m-Y',strtotime($server_get_date));
								$endate = date('d-m-Y',strtotime($server_get_date));
							}
						}

					} else {

						$for_bdt = "SELECT id FROM hotel_businessday_tbl WHERE (startdate='{$server_get_date}' AND enddate='{$server_get_date}') OR (startdate='{$server_get_date}' AND enddate='0000-00-00')";
						
						$biz = mysqli_data_array('assoc',$for_bdt);
						
						if(is_array($biz) && count($biz) > 0) {
							$get_biz = ""; foreach($biz as $key => $val) { $get_biz .= $val['id'].","; }
							$get_biz = substr_replace($get_biz,'',-1,1);
							$keywords .= " AND bizday IN({$get_biz})";
						} else {
							$keywords .= "";
						}

						$startdate = date('d-m-Y',strtotime($server_get_date));
						$endate = date('d-m-Y',strtotime($server_get_date));
					}

					?>

						<div class="bottom-push-15" align="center">
							<div class="cs-width-100 bottom-push-10 noscroll">
								<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
							</div>
							<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
							<h3 class="large nobold default-text-font-bold nomargin">POS Shift Wise Report (<?php echo $shift_label; ?>: <?php echo $app_user; ?>)</h3><h3 class="large nobold default-text-font-bold">Between <?php echo $startdate; ?> and <?php echo $endate; ?></h3>
						</div>

					<?php

					$queryset = "posid={$cur_pos_store_id} AND deletedata=0 AND isreversed=0".$keywords;

					$keys = array(
						"order_number"=>"(fx)order no.",
						"receipt_number"=>"receipt no.",
						"bill_amount"=>"(nf)bill amount",
						"billtype"=>"bill type",
						"cover"=>"(nf)cover",
						"cashier"=>"cashier",
						"waiter"=>"waiter",
						"datelogged"=>"order date"
					);

					$format = array(
						"grid",
						"use-base-data"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;

				?>


				<div class="cs-height-50"></div>
				<h3 class="large nobold default-text-font-bold alignct">Class & Transaction Type Base Summary</h3>

				<div class="cs-height-10"></div>

				<?php

					$posaSql = "SELECT SUM(bill_amount) AS 'totalSalesAmt' FROM pos_payment_tbl";

					$ctgSql = "SELECT categoryid FROM pos_store_product_tbl WHERE postoreid={$cur_pos_store_id} AND deletedata=0 GROUP BY categoryid";
					$query_ctg = mysqli_query($mysqli,$ctgSql);
					$row_ctg = @mysqli_num_rows($query_ctg);

					$total4PoSummary = 0; $total4PoSViseSummary = 0;
				?>

				<div class="ln-display-box float-left cs-width-300">
					
					<div class="cs-height-10"></div>

					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="default-text-font-bold" align="left">Transaction Type</td>
							<td class="default-text-font-bold" align="left">Amount</td>
						</tr>

						<?php
							if($row_ctg == true) {
								
								$category_name = ""; $eachSum = "";

								while($ctg = @mysqli_fetch_array($query_ctg,MYSQLI_ASSOC)) {
									
									$category_name = idget_data($tbL115,$ctg['categoryid'],'category');

									$ctgval = "SELECT id FROM pos_store_product_tbl WHERE deletedata=0 AND categoryid={$ctg['categoryid']} AND postoreid={$cur_pos_store_id}"; $query_ctgval = mysqli_query($mysqli,$ctgval);

									$getSum = 0; $sumfx = "";

									while($order = @mysqli_fetch_array($query_ctgval,MYSQLI_ASSOC)) {
										$sumfx = "SELECT SUM(price * qty) AS 'tsales' FROM pos_orders_tbl WHERE posid={$cur_pos_store_id} AND itemid={$order['id']} AND status='Completed' AND isreversed=0".$keywords;
										$queryfx = mysqli_query($mysqli,$sumfx);
										$gsx = @mysqli_fetch_array($queryfx,MYSQLI_ASSOC);
										$getSum = $getSum + $gsx['tsales'];
									}

									$eachSum = $getSum;
									$total4PoSummary = $total4PoSummary + $eachSum;

									?>
										<tr>
											<td align="left"><?php echo $category_name; ?></td>
											<td align="left">&#8358; <?php echo number_format($eachSum,2); ?></td>
										</tr>
									<?php
								}
							}
						?>

						<tr>
							<td align="left">Total</td>
							<td class="default-text-font-bold" align="left">&#8358; <?php echo number_format($total4PoSummary,2); ?></td>
						</tr>
					</table>
				</div>

				<div class="ln-display-box float-right cs-width-300">
					
					<div class="cs-height-10"></div>

					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="default-text-font-bold" align="left">Transaction Type</td>
							<td class="default-text-font-bold" align="left">Amount</td>
						</tr>

						<?php
							if(isset($bill_type) && is_array($bill_type)) {
								
								$totalBillTypeSales = 0;
								$btp = "";

								foreach($bill_type as $key => $val) {
								
									$typ = $posaSql." WHERE posid={$cur_pos_store_id} AND billtype={$key} AND isreversed=0".$keywords;
									
									$query_btp = mysqli_query($mysqli,$typ);
									$btp = @mysqli_fetch_array($query_btp,MYSQLI_ASSOC);

									$totalBillTypeSales = $btp['totalSalesAmt'];

									?>
										<tr>
											<td align="left"><?php echo $val; ?></td>
											<td align="left">&#8358; <?php echo number_format($totalBillTypeSales,2); ?></td>
										</tr>
									<?php

									$typ = "";

									$total4PoSViseSummary = $total4PoSViseSummary + $totalBillTypeSales;
								}
							}
						?>

						<tr>
							<td align="left">Total</td>
							<td class="default-text-font-bold" align="left">&#8358; <?php echo number_format($total4PoSViseSummary,2); ?></td>
						</tr>
					</table>
				</div>

				<div class="block-element new-line-space">
				</div>

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

	function jsxView(key) {
		popmodalframe('pos','preview_xreceipt',key,0,700,1500);
	}

</script>