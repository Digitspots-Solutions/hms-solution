<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	//$query_stores = "SELECT * FROM {$tbL14} WHERE status IN('Active') AND iscounter IN('Yes') AND deletedata=0";
	$query_stores = "SELECT * FROM {$tbL14} WHERE status IN('Active') AND iscounter IN('Yes','No') AND deletedata=0";
	$for_stores = html_db_select($query_stores,'id','posname');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$xtbl = $tbL156;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: Here you can see all the stock-issue request by outlets
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
			<input type="hidden" name="reporter" value="stock-issue-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Outlets</h3>
				<select name="stores" id="stores" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $for_stores; ?>
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
		<div class="nc-width-100 right-pull-20 left-pull-20">
			<div id="section-to-print">

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'stock-issue-reports') {

						?>
							<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
								<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
							</div>
							<div class="cs-width-700 margin-auto-ct alignct">
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold nomargin">Issue Request Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
								<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
							</div>
						<?php

						$_SESSION['startdate'] = $_POST['startdate'];
						$_SESSION['enddate'] = $_POST['enddate'];

						$keywords = "";

						if(isset($_POST['stores']) && !empty($_POST['stores'])) {
							$keywords .= " AND to_posid={$_POST['stores']}";
						}

						$ddOpt = " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
						$keywords .= $ddOpt;

					
						$sql = "SELECT to_posid, transfer_status FROM {$xtbl} WHERE transfer_status IN('Transfer Completed') AND deletedata=0".$keywords." GROUP BY to_posid";
						$datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$sql2 = ""; $datagroup2 = ""; $store_name = ""; $stat_name = "";

							foreach($datagroup as $key => $val) {
				
								$store_name = idget_name($val['to_posid'],'posname',$tbL14);
								$stat_name = ($val['transfer_status'] == 'Under Approval') ? "<b class='light-red-font nobold left-push-10'>{$val['transfer_status']}</b>" : "<b class='forest-green-font nobold left-push-10'>Issued</b>";

								$sql2 = "SELECT * FROM {$xtbl} WHERE to_posid={$val['to_posid']} AND transfer_status IN('Transfer Completed') AND deletedata=0".$ddOpt;
								$datagroup2 = idget_data($sql2);
							
								?>
								<h3 class="large nobold default-text-font-bold bottom-pull-5"><?php echo $store_name.$stat_name; ?></h3>
								
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">&nbsp;</td>
										<td class="alignct default-text-font-bold">Store</td>
										<td class="alignct default-text-font-bold">Request No.</td>
										<td class="alignct default-text-font-bold">Datetime</td>
										<td class="alignct default-text-font-bold">Item</td>
										<td class="alignct default-text-font-bold">Expiry Status</td>
										<td class="alignct default-text-font-bold">Quantity</td>
										<td class="alignct default-text-font-bold">Last Price</td>
										<td class="alignct default-text-font-bold">Amount</td>
									</tr>

									<?php

										$num = 0;  $total_cost_amount = 0;

										foreach($datagroup2 as $key2 => $val2) {
											
											$num += 1;

											idget_global($val2['itemid'],$var_item);
											$get_su = arrayget_key($uoms,$val2['uom']);
											$expiry_state = idget_name($val2['itemid'],'isexpire',$mtbL5);
											$expiry_date = idget_name($val2['itemid'],'expiry_date',$mtbL5);

											$expiry_date_fr = date('d-m-y',strtotime($expiry_date));
											$get_expiry_stat = ($expiry_state == 'Yes') ? "Expiring - {$expiry_date_fr}" : "Never Expire";

											$get_pos_name = idget_name($val2['from_posid'],'store_name',$tbL123);

											$lsp = "SELECT * FROM {$mtbL18} WHERE itemid={$val2['itemid']} AND deletedata=0 ORDER BY id DESC LIMIT 1"; $get_lsp = idget_data($lsp);

											$last_price = $get_lsp[0]['costprice'];
											$cost_amount = $val2['qty_transfer'] * $last_price;

											$total_cost_amount = $total_cost_amount + $cost_amount;

											?>
												<tr>
													<td class="alignct"><?php echo $num; ?>.</td>
													<td class="alignct"><?php echo $get_pos_name; ?></td>
													<td class="alignct"><?php echo $val2['transfer_number']; ?></td>
													<td class="alignct"><?php echo date('d-m-Y',strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
													<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
													<td class="right-pull-10 left-pull-10 alignlt"><?php echo $get_expiry_stat; ?></td>
													<td class="right-pull-10 left-pull-10 alignlt"><?php echo $val2['qty_transfer'].' '.$get_su; ?></td>
													<td class="right-pull-10 left-pull-10">&#8358; <?php echo number_format($last_price,2); ?></td>
													<td class="right-pull-10 left-pull-10">&#8358; <?php echo number_format($cost_amount,2); ?></td>
												</tr>
											<?php

											$frompos=""; $topos=""; $cost_amount = ""; $last_price = "";
											$get_su=""; $get_bu=""; $categoryid=""; $subcategoryid=""; $buying_unit="";
											$expiry_state = ""; $expiry_date = ""; $expiry_date_fr = ""; $get_expiry_stat = "";
											$get_pos_name = "";
										}	

									?>

									<tr>
										<td class="right-pull-10 left-pull-10 default-text-font-bold" colspan="8">Total</td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold grey-1-theme">&#8358; <?php echo number_format($total_cost_amount,2); ?></td>
									</tr>

								</table>

								<div class="cs-height-30">
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

	/*function jsxView(key) {
		popmodalframe('materialcontrol','transfer_item_list',key,0,1000,2500);
	}*/

	function jsxView(requestno) {

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
		inframe.src = curl+'public/admin/materialcontrol/workspace.php?logs=Item Transfer No&tag=&trqs='+requestno;
		parent.document.getElementById('workspace').scrollTop = 0;
	}

</script>