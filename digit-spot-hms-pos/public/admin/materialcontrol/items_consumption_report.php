<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$isItem = "SELECT id,item FROM {$tbL118} WHERE deletedata=0";
	$wgtItem = html_db_select($isItem,'id','item');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: This shows the item consumption details
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
			<input type="hidden" name="reporter" value="item-consumption-reports">
			<span class="ln-display-box float-left cs-width-300 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold alignlt">Items</h3>
				<select name="items[]" id="items" class="nopads no-back-black" size="2" multiple>
					<?php echo $wgtItem; ?>
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
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'item-consumption-reports') {

						?>
							<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
								<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
							</div>
							<div class="cs-width-500 margin-auto-ct alignct">
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold nomargin">Item Consumption Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
								<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
							</div>
						<?php

						$keywords = ""; $gr_items = "";

						if(isset($_POST['items']) && !empty($_POST['items'])) {
							foreach($_POST['items'] as $item) { $gr_items .= "'".$item."',"; }
							$gr_items = substr_replace($gr_items,'',-1,1);
							$keywords .= " AND itemcode IN({$gr_items})";
						}

						$sql = "SELECT itemcode FROM {$tbL16} WHERE storagetype='consumable' AND deletedata=0".$keywords." GROUP BY itemcode"; $datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$order_summary = array(); $index = 0;

							$item_name = "";

							foreach($datagroup as $key => $val) {
								
								$item_name = idget_fname($val['itemcode'],'item',$tbL16);

								$orders = array();

								?>
									<h3 class="large nobold"><?php echo $item_name; ?></h3>
									<table cellpadding="3" cellspacing="0" border="1">
										<tr>
											<td class="alignct default-text-font-bold">Store</td>
											<td class="alignct default-text-font-bold">Quantity</td>
											<td class="alignct default-text-font-bold">Unit Price</td>
											<td class="alignct default-text-font-bold">Amount</td>
										</tr>

										<?php
											$sql_2 = "SELECT * FROM {$tbL99} WHERE itemid={$val['itemcode']} AND isreversed=0 AND deletedata=0 AND status IN('Completed') AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
											$dataget = idget_data($sql_2);

											$total_quantity = 0; $total_amount = 0;
											
											foreach($dataget as $xkey => $xval) {
												
												idget_global($xval['posid'],$var_store);

												?>
													<tr>
														<td class="alignct"><?php echo $_gparams[$var_store]['returnval']; ?></td>
														<td class="alignrt"><?php echo $xval['qty']; ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($xval['price'],2); ?></td>
														<td class="alignrt">&#8358; <?php echo number_format($xval['amount'],2); ?></td>
													</tr>
												<?php

												$total_quantity = $total_quantity + $xval['qty'];
												$total_amount = $total_amount + $xval['amount'];

											}

											$orders['name'] = $item_name;
											$orders['qty'] = $total_quantity;
											$orders['amount'] = $total_amount;

										?>

										<tr>
											<td class="alignlt default-text-font-bold">Total</td>
											<td class="alignrt default-text-font-bold"><?php echo $total_quantity; ?></td>
											<td>&nbsp;</td>
											<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_amount,2); ?></td>
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

									<h3 class="large nobold alignct">Summary Report for Item Consumption</h3><br>
									
									<div align="center">
										<table style="width: 400px !important" cellpadding="1" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Item</td>
												<td class="alignct default-text-font-bold">Quantity</td>
												<td class="alignct default-text-font-bold">Amount</td>
											</tr>
											<?php

												$grand_quantity = 0; $grand_amount = 0;

												foreach($order_summary as $key => $val) {
														
													$grand_quantity = $grand_quantity + $val['qty'];
													$grand_amount = $grand_amount + $val['amount'];

													?>
														<tr>
															<td class="alignlt"><?php echo $val['name']; ?></td>
															<td class="alignrt"><?php echo $val['qty']; ?></td>
															<td class="alignrt"><?php echo number_format($val['amount'],2); ?></td>
														</tr>
													<?php
												}

											?>
											<tr>
												<td class="alignlt default-text-font-bold">Total</td>
												<td class="alignrt default-text-font-bold"><?php echo $grand_quantity; ?></td>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grand_amount,2); ?></td>
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
	}

</script>