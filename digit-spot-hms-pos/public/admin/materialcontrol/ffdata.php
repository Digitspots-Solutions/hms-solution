<?php
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$tr_date = $ftoken;

	$queryset = "transaction_date='{$tr_date}' AND deletedata=0";
	$iresult = mysqli_data_exist('fd_analysis_tbl',$queryset);

	if($iresult['isdata'] == true) {
		
		$line1 = "SELECT * FROM fd_analysis_tbl WHERE name='Mini bar' AND ".$queryset;
		$data1 = wgetSQL($line1); $mini_bar = $data1[0]['amount'];

		$line2 = "SELECT * FROM fd_analysis_tbl WHERE name='Elimination' AND ".$queryset;
		$data2 = wgetSQL($line2); $elimination = $data2[0]['amount'];

		$line3 = "SELECT * FROM fd_analysis_tbl WHERE name='Food to Bar' AND ".$queryset;
		$data3 = wgetSQL($line3); $food2bar = $data3[0]['amount'];

		$line4 = "SELECT * FROM fd_analysis_tbl WHERE name='VIP' AND ".$queryset;
		$data4 = wgetSQL($line4); $vip = $data4[0]['amount'];

		$line5 = "SELECT * FROM fd_analysis_tbl WHERE name='Employee Meal' AND ".$queryset;
		$data5 = wgetSQL($line5); $employee_meal = $data5[0]['amount'];

		$line6 = "SELECT * FROM fd_analysis_tbl WHERE name='Rebates' AND ".$queryset;
		$data6 = wgetSQL($line6); $rebates = $data6[0]['amount'];

		$line7 = "SELECT * FROM fd_analysis_tbl WHERE name='Total Breakfast Staff Cover' AND ".$queryset;
		$data7 = wgetSQL($line7); $tbsc = $data7[0]['amount'];

		$line8 = "SELECT * FROM fd_analysis_tbl WHERE name='Total Lunch Staff Cover' AND ".$queryset;
		$data8 = wgetSQL($line8); $tlsc = $data8[0]['amount'];

		$line9 = "SELECT * FROM fd_analysis_tbl WHERE name='Bar to Food' AND ".$queryset;
		$data9 = wgetSQL($line9); $bar2food = $data9[0]['amount'];

		$line10 = "SELECT * FROM fd_analysis_tbl WHERE name='Staff Canteen Only For Breakfast' AND ".$queryset;
		$data10 = wgetSQL($line10); $sco4b = $data10[0]['amount'];

		$line11 = "SELECT * FROM fd_analysis_tbl WHERE name='Staff Canteen Only for Lunch' AND ".$queryset;
		$data11 = wgetSQL($line11); $sco4l = $data11[0]['amount'];

		$line12 = "SELECT * FROM fd_analysis_tbl WHERE name='Meat' AND ".$queryset;
		$data12 = wgetSQL($line12); $meat = $data12[0]['amount'];

		$line13 = "SELECT * FROM fd_analysis_tbl WHERE name='Poultry' AND ".$queryset;
		$data13 = wgetSQL($line13); $poultry = $data13[0]['amount'];

		$line14 = "SELECT * FROM fd_analysis_tbl WHERE name='Sea Food' AND ".$queryset;
		$data14 = wgetSQL($line14); $sea_food = $data14[0]['amount'];

		$line15 = "SELECT * FROM fd_analysis_tbl WHERE name='Transfer' AND ".$queryset;
		$data15 = wgetSQL($line15); $transfer = $data15[0]['amount'];

		$line16 = "SELECT * FROM fd_analysis_tbl WHERE name='POS Store Issues' AND ".$queryset;
		$data16 = wgetSQL($line16); $psi = $data16[0]['amount'];

	} else {

		$mini_bar = 0;
		$elimination = 0;
		$food2bar = 0;
		$vip = 0;
		$employee_meal = 0;
		$rebates = 0;
		$tbsc = 0;
		$tlsc = 0;
		$bar2food = 0;
		$sco4b = 0;
		$sco4l = 0;
		$meat = 0;
		$poultry = 0;
		$sea_food = 0;
		$transfer = 0;
		$psi = 0;
	}

?>

<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		Food Flash Form
	</span>
	<span class="ln-display-box float-right top-pull-5">
		<input type="button" value="Print" onclick="window.print()">
	</span>
	<span class="block-element new-line-space">
	</span>
</div>


<div class="pads20">
	<div id="section-to-print" class="box-border-thick sml-rounded-button">
		<div class="sided-box pads15 box-border-thick-bottom alignct">
			<h1 class="large nobold nomargin"><?php echo _LONG_NAME; ?></h1>
			<h3 class="large nobold">Food Flash Form (for <?php echo date('d/m/Y',strtotime($tr_date)); ?>)</h3>
		</div>
		<div class="pads15 box-border-thick-bottom alignlt">
			<table cellpadding="0" cellspacing="5">
				<tr>
					<td valign="top" class="nc-width-35 box-noborder nobordercolor">
						<h3 class="large nobold default-text-font-bold blue-font">BEVERAGE SALES BREAKDOWN</h3><br>
						<table cellpadding="0" cellspacing="2">
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Malt Drinks</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Mineral Drinks</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Bottle Water</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Beer</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Spirits</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Wines</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Cocktail Adj.</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Credits</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Rebates</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									0
								</td>
							</tr>
						</table>
					</td>
					<td class="nc-width-5 box-noborder nobordercolor">
						&nbsp;
					</td>
					<td valign="top" class="nc-width-55 box-noborder nobordercolor">
						<h3 class="large nobold default-text-font-bold blue-font">FOOD BREAKDOWN</h3><br>
						<table cellpadding="0" cellspacing="2">
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Mini Bar</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $mini_bar; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Elimination</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $elimination; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Food to Bar</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $food2bar; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">VIP</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $vip; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Employee Meal</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $employee_meal; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Rebates</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $rebates; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Total Breakfast Staff Cover</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $tbsc; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Total Lunch Staff Cover</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $tlsc; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Bar to Food</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $bar2food; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Staff Canteen Only For Breakfast</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $sco4b; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Staff Canteen Only for Lunch</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $sco4l; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Meat</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $meat; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Poultry</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $poultry; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Sea Food</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $sea_food; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">Transfer</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $transfer; ?>
								</td>
							</tr>
							<tr>
								<td class="nc-width-50 box-noborder nobordercolor">POS Store Issues</td>
								<td class="nc-width-50 box-noborder nobordercolor">
									<?php echo $psi; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="sided-box pads15 box-border-thick-bottom alignlt">
			<table cellpadding="0" cellspacing="5">
				<tr>
					<td class="default-text-font-bold alignlt">OUTLETS</td>
					<td class="default-text-font-bold alignlt">COVERS</td>
					<td class="default-text-font-bold alignlt">FOOD</td>
				</tr>

				<?php
					
					$pos_qff = "SELECT * FROM outlet_food_analysis_tbl WHERE ".$queryset;
					$wget_outlet = wgetSQL($pos_qff);

					$posname = ""; $fflash = "";
					$grand_total_covers = 0; $grand_total_foods = 0;

					if(is_array($wget_outlet)):
						foreach($wget_outlet as $key => $val):

							$posname = idget_data($tbL14,$val['pos'],'posname');
							
							$pos_amount = $val['food'];
							$pos_covers = $val['cover'];

							$grand_total_covers = $grand_total_covers + $pos_covers;
							$grand_total_foods = $grand_total_foods + $pos_amount;

							?>
								
								<tr>
									<td class="left-pull-5"><?php echo $posname; ?></td>
									<td class="right-pull-20 left-pull-20"><?php echo number_format($pos_covers,2); ?></td>
									<td class="right-pull-20 left-pull-20"><?php echo number_format($pos_amount,2); ?></td>
								</tr>

							<?php

							$pos_covers = ""; $pos_amount = "";

						endforeach;

						?>
							<tr class="">
								<td class="default-text-font-bold left-pull-5">GRAND TOTAL</td>
								<td class="default-text-font-bold right-pull-20 left-pull-20"><?php echo number_format($grand_total_covers,2); ?></td><td class="default-text-font-bold right-pull-20 left-pull-20 grey-theme"><?php echo number_format($grand_total_foods,2); ?></td>
							</tr>
						<?php

					endif;
					
					?>
			</table>
		</div>
	</div>
</div>

