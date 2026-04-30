<?php
$smdl = "pos"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');
$get_items = select_dt_fetch('deletedata',0,$tbL16,'id','item');
$get_pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Store Consumption Report</b>: here you can see the transactions done in shift
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
				<select name="shift" id="shift" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php echo $get_shifts; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Category</h3>
				<!--<select name="item" id="item" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php //echo $get_items; ?>
				</select>-->
				<select name="category" id="category" class="nopads no-back-black">
					<option value="" selected>All</option>
					<option value="1">Food</option>
					<option value="2">Beverage</option>
					<option value="3">Others</option>
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
		<div class="nc-width-100">
			<div id="section-to-print">
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$tbl = $tbL99;
					
						$keywords = "";
						$shift_label = "All Shifts"; $item_label = "All Items";

						if(isset($_POST['shift']) && !empty($_POST['shift'])) {
							$keywords .= " AND shiftid={$_POST['shift']}";
							$shift_label = idget_data($tbL20,$_POST['shift'],'shiftname');
						}

						/*if(isset($_POST['item']) && !empty($_POST['item'])) {
							$keywords .= " AND itemid={$_POST['item']}";
							$item_label = idget_data($tbL16,$_POST['item'],'item');
						}*/

						if(isset($_POST['category']) && !empty($_POST['category'])) {
							$keywords .= " AND main_category={$_POST['category']}";
							$item_label = $outlet_category_type[$_POST['category']];
						}

						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							$startdate = date('Y-m-d',strtotime($_POST['startdate'])); $endate = date('Y-m-d',strtotime($_POST['endate']));
							$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						}


						$sc_sql = "SELECT itemid FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND deletedata=0".$keywords." GROUP BY itemid";
						$sc_view = wgetSQL($sc_sql);

						?>
							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Store Consumption Report (<?php echo $shift_label; ?>: <?php echo $item_label; ?>)</h3><h3 class="large nobold default-text-font-bold">Between <?php echo $startdate; ?> and <?php echo $endate; ?></h3>

								<h3 class="large nobold">&mdash; <?php echo $get_pos_name; ?> &mdash;</h3>
							</div>

							<div align="center">
								<table style="width: 600px !important" cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Category</td>
										<td class="alignct default-text-font-bold">Item</td>
										<td class="alignct default-text-font-bold">Qty</td>
										<td class="alignct default-text-font-bold">Amount</td>
									</tr>

									<?php

										if(is_array($sc_view) && count($sc_view) > 0) {
											
											$gr_totalqty = 0; $gr_totalsales = 0;

											foreach($sc_view as $key => $val) {
											
												$get_item_name = idget_data($tbL16,$val['itemid'],'item');
												
												$item_sql = "SELECT SUM(qty) AS totalqty, SUM(amount) AS totalsales FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND itemid={$val['itemid']} AND status IN('Completed')".$keywords;
												$item_view = wgetSQL($item_sql);

												$item_sql2 = "SELECT main_category FROM {$tbL99} WHERE posid={$cur_pos_store_id} AND isreversed=0 AND itemid={$val['itemid']} AND status IN('Completed')".$keywords." GROUP BY main_category";
												$item_view2 = wgetSQL($item_sql2);

												$gr_totalqty = $gr_totalqty + $item_view[0]['totalqty'];
												$gr_totalsales = $gr_totalsales + $item_view[0]['totalsales'];

												$get_category_name = $outlet_category_type[$item_view2[0]['main_category']];

												?>
													<tr>
														<td class="alignlt"><?php echo $get_category_name; ?></td>
														<td class="alignlt"><a href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['itemid']; ?>')"><?php echo $get_item_name; ?></a></td>
														<td class="alignrt"><?php echo $item_view[0]['totalqty']; ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($item_view[0]['totalsales'],2); ?></td>
													</tr>
												<?php

												$get_item_name = ""; $get_category_id = ""; $get_category_name = "";
											}
										}
									?>
								
									<tr>
										<td class="alignlt">TOTAL</td>
										<td class="alignrt default-text-font-bold">&nbsp;</td>
										<td class="alignrt default-text-font-bold"><?php echo $gr_totalqty; ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($gr_totalsales,2); ?></td>
									</tr>
								</table>
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

	function jsxView(key) {
		popmodalframe('pos','item_sales_details',key,0,1000,2500);
	}

</script>