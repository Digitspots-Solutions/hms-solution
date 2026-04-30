<?php
$smdl = "pos"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$extable = $tbL7; $extcols = "staffname"; $extkey = "id";
$get_users = select_dt_fetch('deletedata',0,$tbL100,'','cashier');
$get_pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');
$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the list of pos transactions. Click on the <u>order number</u> to print receipt
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
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Cashier</h3>
				<select name="user" id="user" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_users; ?>
				</select>
			</span>
			<!--<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Status</h3>
				<select name="status" id="status" class="nopads no-back-black">
					<option value="" selected>All</option>
					<option value="Paid">Paid</option>
					<option value="Completed">Completed</option>
					<option value="Complimentary">Complimentary</option>
				</select>
			</span>-->
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="cs-width-1200">
			<div id="section-to-print">

				<?php
					
					$tbl = $tbL100;

					$startnumbr = 0;
					$keywords = "";

					if(isset($_POST['user']) && !empty($_POST['user'])) {
						$keywords .= " AND cashier={$_POST['user']}";
					}

					if(isset($_POST['status']) && !empty($_POST['status'])) {
						$keywords .= " AND payment={$_POST['status']}";
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$report_date = "BETWEEN ".date('d-m-Y',strtotime($_POST['startdate']))." AND ".date('d-m-Y',strtotime($_POST['endate']));
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$report_date = "BETWEEN ".date('d-m-Y',strtotime($server_get_date))." AND ".date('d-m-Y',strtotime($server_get_date));
					}

				?>
					<div class="bottom-push-30" align="center">
						<div class="cs-width-100 bottom-push-10 noscroll">
							<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
						</div>
						<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">POS Transaction Report</h3>
						<h3 class="large nobold nomargin"><?php echo $get_pos_name; ?> (<?php echo $report_date; ?>)</h3>
						<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
					</div>

				<?php

					$queryset = "status IN('Completed') AND isreversed=0 AND deletedata=0 AND posid={$cur_pos_store_id}".$keywords." ORDER BY id DESC";

					$keys = array(
						"order_number"=>"(fx)order no.",
						"receipt_number"=>"receipt no.",
						"bill_amount"=>"(nf)bill amount (&#8358;)",
						"foodtype"=>"food type",
						"billtype"=>"bill type",
						"biller"=>"(bx)bill to",
						"cover"=>"(nf)cover",
						"cashier"=>"cashier",
						"waiter"=>"waiter",
						"media"=>"mode",
						"datelogged"=>"date",
						"timelogged"=>"time"
					);

					$format = array(
						"grid",
						"use-base-data"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;


					if($_SERVER['REQUEST_METHOD'] == 'POST'):

				?>

					<div class="cs-height-50"></div>

					<div align="center">
						<h3 class="large nobold alignct">Bill Type Summary</h3>
						<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
							<tr>
								<td class="alignct default-text-font-bold">Type</td>
								<td class="alignct default-text-font-bold">Cover</td>
								<td class="alignct default-text-font-bold">Amount</td>
							</tr>
							<?php
								if(is_array($bill_type)) {
									
									$total2bt = 0; $total2ct = 0;

									foreach($bill_type as $key => $val) {
										
										if($key == 2) {
											$sql = "SELECT SUM(bill_amount) AS total4bill, SUM(cover) AS total4cover FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND billtype=2 AND iscomplimentary=0 AND status IN('Completed') AND isreversed=0 AND deletedata=0".$keywords;
										} elseif($key == 3) {
											$sql = "SELECT SUM(bill_amount) AS total4bill, SUM(cover) AS total4cover FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND (billtype=3 OR iscomplimentary > 0) AND status IN('Completed') AND isreversed=0 AND deletedata=0".$keywords;
										} else {
											$sql = "SELECT SUM(bill_amount) AS total4bill, SUM(cover) AS total4cover FROM {$tbL100} WHERE posid={$cur_pos_store_id} AND billtype={$key} AND status IN('Completed') AND isreversed=0 AND deletedata=0".$keywords;
										}

										$result = wgetSQL($sql);

										?>
											<tr>
												<td class="alignlt"><?php echo $val; ?></td>
												<td class="alignrt"><?php echo number_format($result[0]['total4cover']); ?></td>
												<td class="alignrt"><?php echo number_format($result[0]['total4bill'],2); ?></td>
											</tr>
										<?php

										$total2ct = $total2ct + $result[0]['total4cover'];
										$total2bt = $total2bt + $result[0]['total4bill'];

										$result = "";
									}
								}
							?>
							<tr>
								<td class="alignlt">Total</td>
								<td class="alignrt default-text-font-bold"><?php echo number_format($total2ct); ?></td>
								<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total2bt,2); ?></td>
							</tr>
						</table>
					</div>

					<div class="cs-height-50"></div>

				<?php
					endif;
				?>

			</div>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function jsxView(key) {
		popmodalframe('pos','pos_post_bill_review',key,0,1000,2500);
	}

</script>