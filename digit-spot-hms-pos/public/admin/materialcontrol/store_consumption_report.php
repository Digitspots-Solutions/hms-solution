<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$xtbl = $tbL123; $xcol = "id"; $xopttext = "store_name";
	$isOutlet = "SELECT id,posname FROM {$tbL14} WHERE status IN('Active') AND iscounter IN('Yes') AND deletedata=0";
	$wgtOutlet = html_db_select($isOutlet,'id','posname');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: This shows the store consumption details
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
			<input type="hidden" name="reporter" value="store-consumption-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Stores</h3>
				<select name="stores" id="stores" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $wgtOutlet; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Category</h3>
				<select name="category" id="category" class="nopads no-back-black">
					
					<?php
						if(isset($_POST['category']) && !empty($_POST['category'])) {
							if($_POST['category'] == 1) { $ctg = "Food"; }
							elseif($_POST['category'] == 2) { $ctg = "Beverage"; }
							elseif($_POST['category'] == 3) { $ctg = "Others"; }

							?>
								<option value="<?php echo $_POST['category']; ?>" selected><?php echo $ctg; ?></option>
							<?php
						} else {
							?>
								<option value="" selected>All</option>
							<?php
						}
					?>
					
					<option value="1">Food</option>
					<option value="2">Beverage</option>
					<option value="3">Others</option>
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

				<?php
				
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'store-consumption-reports') {

						?>
							<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
								<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
							</div>
							<div class="cs-width-600 margin-auto-ct alignct">
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold nomargin">Store Consumption Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
								<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
							</div>

						<?php

							$_SESSION['startdate'] = $_POST['startdate'];
							$_SESSION['enddate'] = $_POST['enddate'];

							$keywords = ""; $main_ctg = ""; $store_name = ""; $datelog = "";

							if(isset($_POST['stores']) && !empty($_POST['stores'])) {
								$keywords .= " AND posid={$_POST['stores']}";
							}

							if(isset($_POST['category']) && !empty($_POST['category'])) {
								$main_ctg = " AND main_category='{$_POST['category']}'";
								$keywords .= " AND main_category='{$_POST['category']}'";
							}

							if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['enddate']) && !empty($_POST['enddate']))) {
								$datelog = " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
								$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
							}

							$sc_sql = "SELECT posid FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0".$keywords." GROUP BY posid";
							$g_sql = idget_data($sc_sql);

							if(is_array($g_sql)) {

								foreach($g_sql as $xkey => $xval) {
									
									$store_name = idget_name($xval['posid'],'posname',$tbL14);

									?>
										<div class="bottom-push-30" align="center">
											<div class="cs-width-600">
												<h3 class="large nobold alignlt"><?php echo $store_name; ?></h3>
												<table cellpadding="3" cellspacing="0" border="1">
													<tr>
														<td class="alignct default-text-font-bold">Category</td>
														<td class="alignct default-text-font-bold">Item</td>
														<td class="alignct default-text-font-bold">Qty</td>
														<td class="alignct default-text-font-bold">Amount</td>
													</tr>

													<?php

														$sc_sql = "SELECT itemid FROM {$tbL99} WHERE posid={$xval['posid']} AND isreversed=0 AND status IN('Completed') AND deletedata=0".$main_ctg.$datelog." GROUP BY itemid";

														$sc_view = idget_data($sc_sql);



														if(is_array($sc_view) && count($sc_view) > 0) {
															
															$gr_totalqty = 0; $gr_totalsales = 0;

															foreach($sc_view as $key => $val) {
															
																$get_item_name = idget_name($val['itemid'],'item',$tbL16);
																
																$item_sql = "SELECT SUM(qty) AS totalqty, SUM(amount) AS totalsales FROM {$tbL99} WHERE posid={$xval['posid']} AND isreversed=0 AND itemid={$val['itemid']} AND status IN('Completed')".$main_ctg.$datelog;

																$item_view = idget_data($item_sql);

																$item_sql2 = "SELECT main_category FROM {$tbL99} WHERE posid={$xval['posid']} AND isreversed=0 AND itemid={$val['itemid']} AND status IN('Completed') GROUP BY main_category";

																$item_view2 = idget_data($item_sql2);

																$gr_totalqty = $gr_totalqty + $item_view[0]['totalqty'];
																$gr_totalsales = $gr_totalsales + $item_view[0]['totalsales'];

																if($item_view2[0]['main_category'] == 1) { $get_category_name = "Food"; }
																elseif($item_view2[0]['main_category'] == 2) { $get_category_name = "Beverage"; }
																elseif($item_view2[0]['main_category'] == 3) { $get_category_name = "Others"; }

																?>
																	<tr>
																		<td class="alignlt"><?php echo $get_category_name; ?></td>
																		<td class="alignlt"><a href="javascript:void(0)" class="dark-black-font" onclick="jsxView('<?php echo $val['itemid']; ?>')"><?php echo $get_item_name; ?></a></td>
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

	function jsxView(key) {
		popmodalframe('pos','pos_item_sold_review',key,0,1000,2500);
	}

</script>