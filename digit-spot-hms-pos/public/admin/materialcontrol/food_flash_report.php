<?php
	$printedby_staffname = idget_name($userSignedIn,'staffname',$tbL7);
?>

<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can see the food flash report
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<form action="" method="post" autocomplete="off" class="nomargin nopads">
	<div class="pads20">
		<div class="box-border-thick sml-rounded-button">
			<div class="sided-box pads15 box-border-thick-bottom alignlt">
				<ul>
					<li class="nc-width-15">Choose Date: </li>
					<li><input type="date" name="transactiondate" id="transactiondate" value="<?php if(isset($_POST['transactiondate'])) { echo $_POST['transactiondate']; } else { echo $server_get_date; } ?>" class="nopads no-back-black"></li>
					<li class="left-pull-50"><input type="submit" name="reportbutton" id="reportbutton" value="Run"></li>
					<li></li>
				</ul>
			</div>
			<div class="pads15 alignlt">
				<?php
					if(isset($_POST['reportbutton'])):

						$tr_date = remove_data_injection($_POST['transactiondate']);
						$get_fd = explode('-', $_POST['transactiondate']);
						$tr_date_fx = $get_fd[0].'-'.$get_fd[1].'-01';

						#sales & cover records
						$dl_sales_sql = "SELECT SUM(food) AS totalsales, SUM(cover) AS totalcovers FROM outlet_food_analysis_tbl WHERE transaction_date='{$tr_date}'";
						$wget_dl_sales = idget_data($dl_sales_sql);

						$td_sales_sql = "SELECT SUM(food) AS sFtotalsales, SUM(cover) AS sFtotalcovers FROM outlet_food_analysis_tbl WHERE transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_td_sales = idget_data($td_sales_sql);

						
						#group 3 records
						$gr3_sql = "SELECT SUM(amount) AS totalGr3 FROM fd_analysis_tbl WHERE ngroup IN(3) AND transaction_date='{$tr_date}'";
						$wget_gr3 = idget_data($gr3_sql);

						$sf_gr3_sql = "SELECT SUM(amount) AS sFtotalGr3 FROM fd_analysis_tbl WHERE ngroup IN(3) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr3 = idget_data($sf_gr3_sql);

						
						#group 5 records
						$gr5_sql = "SELECT SUM(amount) AS totalGr5 FROM fd_analysis_tbl WHERE ngroup IN(5) AND transaction_date='{$tr_date}'";
						$wget_gr5 = idget_data($gr5_sql);

						$sf_gr5_sql = "SELECT SUM(amount) AS sFtotalGr5 FROM fd_analysis_tbl WHERE ngroup IN(5) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr5 = idget_data($sf_gr5_sql);

						
						#group 6 records
						$gr6_sql = "SELECT SUM(amount) AS totalGr6 FROM fd_analysis_tbl WHERE ngroup IN(6) AND transaction_date='{$tr_date}'";
						$wget_gr6 = idget_data($gr6_sql);

						$sf_gr6_sql = "SELECT SUM(amount) AS sFtotalGr6 FROM fd_analysis_tbl WHERE ngroup IN(6) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr6 = idget_data($sf_gr6_sql);

						
						#group 7 records
						$gr7_sql = "SELECT SUM(amount) AS totalGr7 FROM fd_analysis_tbl WHERE ngroup IN(7) AND transaction_date='{$tr_date}'";
						$wget_gr7 = idget_data($gr7_sql);

						$sf_gr7_sql = "SELECT SUM(amount) AS sFtotalGr7 FROM fd_analysis_tbl WHERE ngroup IN(7) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr7 = idget_data($sf_gr7_sql);

						
						#group 8 records
						$gr8_sql = "SELECT SUM(amount) AS totalGr8 FROM fd_analysis_tbl WHERE ngroup IN(8) AND transaction_date='{$tr_date}'";
						$wget_gr8 = idget_data($gr8_sql);

						$sf_gr8_sql = "SELECT SUM(amount) AS sFtotalGr8 FROM fd_analysis_tbl WHERE ngroup IN(8) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr8 = idget_data($sf_gr8_sql);

						
						#group 9 records
						$gr9_sql = "SELECT SUM(amount) AS totalGr9 FROM fd_analysis_tbl WHERE ngroup IN(9) AND transaction_date='{$tr_date}'";
						$wget_gr9 = idget_data($gr9_sql);

						$sf_gr9_sql = "SELECT SUM(amount) AS sFtotalGr9 FROM fd_analysis_tbl WHERE ngroup IN(9) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr9 = idget_data($sf_gr9_sql);

						
						#group 10 records
						$gr10_sql = "SELECT SUM(amount) AS totalGr10 FROM fd_analysis_tbl WHERE ngroup IN(10) AND transaction_date='{$tr_date}'";
						$wget_gr10 = idget_data($gr10_sql);

						$sf_gr10_sql = "SELECT SUM(amount) AS sFtotalGr10 FROM fd_analysis_tbl WHERE ngroup IN(10) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr10 = idget_data($sf_gr10_sql);

						
						#group 11 records
						$gr11_sql = "SELECT SUM(amount) AS totalGr11 FROM fd_analysis_tbl WHERE ngroup IN(11) AND transaction_date='{$tr_date}'";
						$wget_gr11 = idget_data($gr11_sql);

						$sf_gr11_sql = "SELECT SUM(amount) AS sFtotalGr11 FROM fd_analysis_tbl WHERE ngroup IN(11) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr11 = idget_data($sf_gr11_sql);

					
						#group 12 records
						$gr12_sql = "SELECT SUM(amount) AS totalGr12 FROM fd_analysis_tbl WHERE ngroup IN(12) AND transaction_date='{$tr_date}'";
						$wget_gr12 = idget_data($gr12_sql);

						$sf_gr12_sql = "SELECT SUM(amount) AS sFtotalGr12 FROM fd_analysis_tbl WHERE ngroup IN(12) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr12 = idget_data($sf_gr12_sql);


						#group 14 records
						$gr14_sql = "SELECT SUM(amount) AS totalGr14 FROM fd_analysis_tbl WHERE ngroup IN(14) AND transaction_date='{$tr_date}'";
						$wget_gr14 = idget_data($gr14_sql);

						$sf_gr14_sql = "SELECT SUM(amount) AS sFtotalGr14 FROM fd_analysis_tbl WHERE ngroup IN(14) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr14 = idget_data($sf_gr14_sql);


						#group 15 records
						$gr15_sql = "SELECT SUM(amount) AS totalGr15 FROM fd_analysis_tbl WHERE ngroup IN(15) AND transaction_date='{$tr_date}'";
						$wget_gr15 = idget_data($gr15_sql);

						$sf_gr15_sql = "SELECT SUM(amount) AS sFtotalGr15 FROM fd_analysis_tbl WHERE ngroup IN(15) AND transaction_date BETWEEN '{$tr_date_fx}' AND '{$tr_date}'";
						$wget_sf_gr15 = idget_data($sf_gr15_sql);

						#---end

						$sales = $wget_dl_sales[0]['totalsales'];
						$sf_sales = $wget_td_sales[0]['sFtotalsales'];
						$sales_perc = 100;

						$covers = $wget_dl_sales[0]['totalcovers'];
						$sf_covers = $wget_td_sales[0]['sFtotalcovers'];

						$avg_rev_per_cover = $sf_sales / $sf_covers;

						$gross_cost = ($wget_gr10[0]['totalGr10'] + $wget_gr11[0]['totalGr11']) - $wget_gr12[0]['totalGr12'];
						$sf_gross_cost = ($wget_sf_gr10[0]['sFtotalGr10'] + $wget_sf_gr11[0]['sFtotalGr11']) - $wget_sf_gr12[0]['sFtotalGr12'];
						$gross_perc = ($gross_cost / $sales) * 100;
						$sf_gross_perc = ($sf_gross_cost / $sf_sales) * 100;

						$lem = $wget_gr3[0]['totalGr3'];
						$sf_lem = $wget_sf_gr3[0]['sFtotalGr3'];
						$lem_perc = ($lem / $sales) * 100;
						$sf_lem_perc = ($sf_lem / $sf_sales) * 100;

						$ncf = $gross_cost - $lem;
						$sf_ncf = $sf_gross_cost - $sf_lem;
						$ncf_perc = ($ncf / $sales) * 100;
						$sf_ncf_perc = ($sf_ncf / $sf_sales) * 100;

						if(isset($ncf) && $ncf > 0) {
							$nm2d_avg_chk = $ncf / $covers;
							$sf_nm2d_avg_chk = $sf_ncf / $sf_covers;
						} else {
							$nm2d_avg_chk = 0;
							$sf_nm2d_avg_chk = 0;
						}

						if(isset($wget_gr7[0]['totalGr7']) && $wget_gr7[0]['totalGr7'] > 0) {
							$csb_avg_chk = $wget_gr7[0]['totalGr7'] / $wget_gr14[0]['totalGr14'];
							$sf_csb_avg_chk = $wget_sf_gr7[0]['sFtotalGr7'] / $wget_sf_gr14[0]['sFtotalGr14'];
						} else {
							$csb_avg_chk = 0;
							$sf_csb_avg_chk = 0;
						}

						if(isset($wget_gr8[0]['totalGr8']) && $wget_gr8[0]['totalGr8'] > 0) {
							$csm_avg_chk = $wget_gr8[0]['totalGr8'] / $wget_gr15[0]['totalGr15'];
							$sf_csm_avg_chk = $wget_sf_gr8[0]['sFtotalGr8'] / $wget_sf_gr15[0]['sFtotalGr15'];
						} else {
							$csm_avg_chk = 0;
							$sf_csm_avg_chk = 0;
						}


						#VIP, MINI BAR, FOOD2BAR, ELIMINATION
						$vip_sql = "SELECT SUM(amount) AS totalvip FROM fd_analysis_tbl WHERE name IN('VIP') AND transaction_date='{$tr_date}'"; $wget_vip = idget_data($vip_sql);

						$minibar_sql = "SELECT SUM(amount) AS totalminibar FROM fd_analysis_tbl WHERE name IN('Mini Bar') AND transaction_date='{$tr_date}'"; $wget_minibar = idget_data($minibar_sql);

						$food2bar_sql = "SELECT SUM(amount) AS totalfood2bar FROM fd_analysis_tbl WHERE name IN('Food to Bar') AND transaction_date='{$tr_date}'"; $wget_food2bar = idget_data($food2bar_sql);

						$elmn_sql = "SELECT SUM(amount) AS totalelmn FROM fd_analysis_tbl WHERE name IN('Elimination') AND transaction_date='{$tr_date}'"; $wget_elmn = idget_data($elmn_sql);

				?>
					
					<p class="alignrt bottom-pull-30"><input type="button" value="Print" onclick="window.print()"></p>
					
					<div id="section-to-print" class="block-element" align="center">
						<h1 class="xlarge nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h1>
						<h3 class="large nobold default-text-font-bold nomargin">Daily Food Flash Report - <?php echo date('d-m-Y',strtotime($_POST['transactiondate'])); ?></h3>
						<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?> On <?php echo $server_get_date.' '.$server_get_time; ?></h4>
					
						<p>&nbsp;</p>

						<table cellpadding="3" cellspacing="0" border="1" style="font-size: 0.8em !important;">
							<tr>
								<td></td>
								<td class="default-text-font-bold alignct">Day</td>
								<td></td>
								<td class="default-text-font-bold alignct">Todate</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td class="alignct">Amount (&#8358;)</td>
								<td class="alignct">%</td>
								<td class="alignct">Amount (&#8358;)</td>
								<td class="alignct">%</td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Sales</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($sales,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo $sales_perc; ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($sf_sales,2); ?></td>
								<td class="alignrt"><?php echo $sales_perc; ?></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Gross Cost</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($gross_cost,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($gross_perc,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($sf_gross_cost,2); ?></td>
								<td class="alignrt"><?php echo number_format($sf_gross_perc,2); ?></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Less Employee Meal</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($lem,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($lem_perc,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($sf_lem,2); ?></td>
								<td class="alignrt"><?php echo number_format($sf_lem_perc,2); ?></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Net Cost of Food</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($ncf,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($ncf_perc,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($sf_ncf,2); ?></td>
								<td class="alignrt"><?php echo number_format($sf_ncf_perc,2); ?></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Mini Bar, Elimination, Food to Bar, VIP</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr5[0]['totalGr5'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr5[0]['sFtotalGr5'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Bar to Food</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr6[0]['totalGr6'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr6[0]['sFtotalGr6'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Staff Canteen Only for Breakfast</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr7[0]['totalGr7'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr7[0]['sFtotalGr7'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Staff Canteen Only for Lunch</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr8[0]['totalGr8'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr8[0]['sFtotalGr8'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Cost of Rebate</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr9[0]['totalGr9'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr9[0]['sFtotalGr9'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">POS Store Issues</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr10[0]['totalGr10'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr10[0]['sFtotalGr10'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Meat, Poultry and Sea Food</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr11[0]['totalGr11'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr11[0]['sFtotalGr11'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Transfer</td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_gr12[0]['totalGr12'],2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt"><?php echo number_format($wget_sf_gr12[0]['sFtotalGr12'],2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Number of Meals Todate</td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $covers; ?><br>AVG CHK: &#8358; <?php echo number_format($nm2d_avg_chk,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $sf_covers; ?><br>AVG CHK: &#8358; <?php echo number_format($sf_nm2d_avg_chk,2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Cost of Staff Breakfast</td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $wget_gr14[0]['totalGr14']; ?><br>AVG CHK: &#8358; <?php echo number_format($csb_avg_chk,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $wget_sf_gr14[0]['sFtotalGr14']; ?><br>AVG CHK: &#8358; <?php echo number_format($sf_csb_avg_chk,2); ?></td>
								<td class="alignrt"></td>
							</tr>
							<tr>
								<td class="nc-width-35 box-border-thick-right default-text-font-bold alignlt">Cost of Staff Meals</td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $wget_gr15[0]['totalGr15']; ?><br>AVG CHK: &#8358; <?php echo number_format($csm_avg_chk,2); ?></td>
								<td class="box-border-thick-right right-pull-10 alignrt"></td>
								<td class="box-border-thick-right right-pull-10 alignrt">COVER: <?php echo $wget_sf_gr15[0]['sFtotalGr15']; ?><br>AVG CHK: &#8358; <?php echo number_format($sf_csm_avg_chk,2); ?></td>
								<td class="alignrt"></td>
							</tr>
						</table>

						<br><br>

						<div class="" align="center">
							<table cellpadding="3" cellspacing="0" style="width: 70% !important; font-size: 0.8em !important;" border="1">
								<tr>
									<td class="alignlt box-noborder nobordercolor default-text-font-bold">VIP</td>
									<td class="box-noborder nobordercolor alignrt">&#8358; <?php echo number_format($wget_vip[0]['totalvip'],2); ?></td>
									<td class="box-noborder nobordercolor nc-width-10">&nbsp;</td>
									<td class="alignlt box-noborder nobordercolor default-text-font-bold">Mini Bar</td>
									<td class="box-noborder nobordercolor alignrt">&#8358; <?php echo number_format($wget_minibar[0]['totalminibar'],2); ?></td>
								</tr>
								<tr>
									<td class="alignlt box-noborder nobordercolor default-text-font-bold">Food to Bar</td>
									<td class="box-noborder nobordercolor alignrt">&#8358; <?php echo number_format($wget_food2bar[0]['totalfood2bar'],2); ?></td>
									<td class="box-noborder nobordercolor nc-width-10">&nbsp;</td>
									<td class="alignlt box-noborder nobordercolor default-text-font-bold">Elimination</td>
									<td class="box-noborder nobordercolor alignrt">&#8358; <?php echo number_format($wget_elmn[0]['totalelmn'],2); ?></td>
								</tr>
								<tr>
									<td class="alignlt box-noborder nobordercolor default-text-font-bold">AVERAGE<br>REV/COVER</td>
									<td class="default-text-font-bold box-noborder nobordercolor alignrt">&#8358; <?php echo number_format($avg_rev_per_cover,2); ?></td>
									<td class="box-noborder nobordercolor nc-width-10">&nbsp;</td>
									<td class="box-noborder nobordercolor">&nbsp;</td>
									<td class="box-noborder nobordercolor">&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</div>
</form>


<script>

</script>