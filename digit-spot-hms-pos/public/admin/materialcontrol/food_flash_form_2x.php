<?php
	//$wget_outlet_sql = "SELECT * FROM {$tbL14} WHERE postype IN('Establishment','Service') AND iscounter IN('Yes')";
	//$wget_outlet =  idget_data($wget_outlet_sql);
	
	if(isset($_GET['trdate']) && !empty($_GET['trdate'])) { $tr_date = $_GET['trdate']; }
	else { $tr_date = date('Y-m-d',strtotime($server_get_date . ' -1 days'));  }

	$wget_outlet_sql = "SELECT posid, SUM(amount) AS totalbillamt4outlet, SUM(cover) AS totalcover4outlet FROM {$tbL99} WHERE main_category IN(1) AND billtype IN(1,2,4,5) AND datelogged='{$tr_date}' AND isreversed=0 AND deletedata=0 GROUP BY posid";
	$wget_outlet =  idget_data($wget_outlet_sql);

	$queryset = "transaction_date='{$tr_date}' AND deletedata=0";
	$iresult = mysqli_data_exist('fd_analysis_tbl',$queryset);

	if($iresult['isdata'] == true) {
		
		$line1 = "SELECT * FROM fd_analysis_tbl WHERE name='Mini bar' AND ".$queryset;
		$data1 = idget_data($line1); $mini_bar = $data1[0]['amount'];

		$line2 = "SELECT * FROM fd_analysis_tbl WHERE name='Elimination' AND ".$queryset;
		$data2 = idget_data($line2); $elimination = $data2[0]['amount'];

		$line3 = "SELECT * FROM fd_analysis_tbl WHERE name='Food to Bar' AND ".$queryset;
		$data3 = idget_data($line3); $food2bar = $data3[0]['amount'];

		$line4 = "SELECT * FROM fd_analysis_tbl WHERE name='VIP' AND ".$queryset;
		$data4 = idget_data($line4); $vip = $data4[0]['amount'];

		$line5 = "SELECT * FROM fd_analysis_tbl WHERE name='Employee Meal' AND ".$queryset;
		$data5 = idget_data($line5); $employee_meal = $data5[0]['amount'];

		$line6 = "SELECT * FROM fd_analysis_tbl WHERE name='Rebates' AND ".$queryset;
		$data6 = idget_data($line6); $rebates = $data6[0]['amount'];

		$line7 = "SELECT * FROM fd_analysis_tbl WHERE name='Total Breakfast Staff Cover' AND ".$queryset;
		$data7 = idget_data($line7); $tbsc = $data7[0]['amount'];

		$line8 = "SELECT * FROM fd_analysis_tbl WHERE name='Total Lunch Staff Cover' AND ".$queryset;
		$data8 = idget_data($line8); $tlsc = $data8[0]['amount'];

		$line9 = "SELECT * FROM fd_analysis_tbl WHERE name='Bar to Food' AND ".$queryset;
		$data9 = idget_data($line9); $bar2food = $data9[0]['amount'];

		$line10 = "SELECT * FROM fd_analysis_tbl WHERE name='Staff Canteen Only For Breakfast' AND ".$queryset;
		$data10 = idget_data($line10); $sco4b = $data10[0]['amount'];

		$line11 = "SELECT * FROM fd_analysis_tbl WHERE name='Staff Canteen Only for Lunch' AND ".$queryset;
		$data11 = idget_data($line11); $sco4l = $data11[0]['amount'];

		$line12 = "SELECT * FROM fd_analysis_tbl WHERE name='Meat' AND ".$queryset;
		$data12 = idget_data($line12); $meat = $data12[0]['amount'];

		$line13 = "SELECT * FROM fd_analysis_tbl WHERE name='Poultry' AND ".$queryset;
		$data13 = idget_data($line13); $poultry = $data13[0]['amount'];

		$line14 = "SELECT * FROM fd_analysis_tbl WHERE name='Sea Food' AND ".$queryset;
		$data14 = idget_data($line14); $sea_food = $data14[0]['amount'];

		$line15 = "SELECT * FROM fd_analysis_tbl WHERE name='Transfer' AND ".$queryset;
		$data15 = idget_data($line15); $transfer = $data15[0]['amount'];

		$line16 = "SELECT * FROM fd_analysis_tbl WHERE name='POS Store Issues' AND ".$queryset;
		$data16 = idget_data($line16); $psi = $data16[0]['amount'];

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
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can update the food flash form data
	</span>
	<span class="ln-display-box float-right top-pull-5">
		<!--<input type="button" value="Print" onclick="window.print()">-->
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<form id="ffh" action="" method="post" autocomplete="off" class="nomargin nopads" onsubmit="check_submit(event)">
	<input type="hidden" name="uri" value="submit-food-flash-form">
	<div class="pads20">
		<div id="section-to-print" class="box-border-thick sml-rounded-button">
			<div class="sided-box pads15 box-border-thick-bottom alignlt">
				<ul>
					<li class="nc-width-20">Transaction Date: </li>
					<li><input type="date" name="transactiondate" id="transactiondate" value="<?php echo $tr_date; ?>" class="nopads no-back-black" oninput="foodSa(this.value)"></li>
					<li class="left-pull-30"><?php if($iresult['isdata'] == true): ?><a href="javascript:void(0)" class="blue-font" onclick="popmodalframe('materialcontrol','ffdata','<?php echo $tr_date; ?>',0,1000,1500);">Preview Form</a><?php endif; ?></li>
					<li></li>
				</ul>
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
										<input type="hidden" name="nbsb[]" value="Malt Drinks">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Mineral Drinks</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Mineral Drinks">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Bottle Water</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Bottle Water">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Beer</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Beer">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Spirits</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Spirits">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Wines</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Wines">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="1">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Cocktail Adj.</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Cocktail Adj">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="2">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Credits</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Credits">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="3">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Rebates</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nbsb[]" value="Rebates">
										<input type="text" name="bsb[]" value="0">
										<input type="hidden" name="gbsb[]" value="4">
									</td>
								</tr>
							</table>
						</td>
						<td class="nc-width-10 box-noborder nobordercolor">
							&nbsp;
						</td>
						<td valign="top" class="nc-width-45 box-noborder nobordercolor">
							<h3 class="large nobold default-text-font-bold blue-font">FOOD BREAKDOWN</h3><br>
							<table cellpadding="0" cellspacing="2">
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Mini Bar</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Mini Bar">
										<input type="text" name="fb[]" value="<?php echo $mini_bar; ?>">
										<input type="hidden" name="gfb[]" value="5">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Elimination</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Elimination">
										<input type="text" name="fb[]" value="<?php echo $elimination; ?>">
										<input type="hidden" name="gfb[]" value="5">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Food to Bar</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Food to Bar">
										<input type="text" name="fb[]" value="<?php echo $food2bar; ?>">
										<input type="hidden" name="gfb[]" value="5">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">VIP</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="VIP">
										<input type="text" name="fb[]" value="<?php echo $vip; ?>">
										<input type="hidden" name="gfb[]" value="5">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Employee Meal</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Employee Meal">
										<input type="text" name="fb[]" value="<?php echo $employee_meal; ?>">
										<input type="hidden" name="gfb[]" value="3">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Rebates</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Rebates">
										<input type="text" name="fb[]" value="<?php echo $rebates; ?>">
										<input type="hidden" name="gfb[]" value="9">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Total Breakfast Staff Cover</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Total Breakfast Staff Cover">
										<input type="text" name="fb[]" value="<?php echo $tbsc; ?>">
										<input type="hidden" name="gfb[]" value="14">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Total Lunch Staff Cover</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Total Lunch Staff Cover">
										<input type="text" name="fb[]" value="<?php echo $tlsc; ?>">
										<input type="hidden" name="gfb[]" value="15">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Bar to Food</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Bar to Food">
										<input type="text" name="fb[]" value="<?php echo $bar2food; ?>">
										<input type="hidden" name="gfb[]" value="6">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Staff Canteen Only For Breakfast</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Staff Canteen Only For Breakfast">
										<input type="text" name="fb[]" value="<?php echo $sco4b; ?>">
										<input type="hidden" name="gfb[]" value="7">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Staff Canteen Only for Lunch</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Staff Canteen Only for Lunch">
										<input type="text" name="fb[]" value="<?php echo $sco4l; ?>">
										<input type="hidden" name="gfb[]" value="8">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Meat</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Meat">
										<input type="text" name="fb[]" value="<?php echo $meat; ?>">
										<input type="hidden" name="gfb[]" value="11">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Poultry</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Poultry">
										<input type="text" name="fb[]" value="<?php echo $poultry; ?>">
										<input type="hidden" name="gfb[]" value="11">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Sea Food</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Sea Food">
										<input type="text" name="fb[]" value="<?php echo $sea_food; ?>">
										<input type="hidden" name="gfb[]" value="11">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">Transfer</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="Transfer">
										<input type="text" name="fb[]" value="<?php echo $transfer; ?>">
										<input type="hidden" name="gfb[]" value="12">
									</td>
								</tr>
								<tr>
									<td class="nc-width-50 box-noborder nobordercolor">POS Store Issues</td>
									<td class="nc-width-50 box-noborder nobordercolor">
										<input type="hidden" name="nfb[]" value="POS Store Issues">
										<input type="text" name="fb[]" value="<?php echo $psi; ?>">
										<input type="hidden" name="gfb[]" value="10">
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
						<td class="default-text-font-bold alignct">OUTLETS</td>
						<td class="default-text-font-bold alignct">COVERS</td>
						<td class="default-text-font-bold alignct">FOOD</td>
					</tr>

					<?php
						
						$posname = ""; $fflash = "";
						$grand_total_covers = 0; $grand_total_foods = 0;

						if(is_array($wget_outlet)):
							foreach($wget_outlet as $key => $val):

								$posname = idget_name($val['posid'],'posname',$tbL14);
								$fflash = idget_name($val['posid'],'isfoodflash',$tbL14);

								if($fflash == 'Yes'):
									
									//$wget_outlet_sales_sql = "SELECT SUM(cover) AS 'totalcover4outlet', SUM(bill_amount) AS 'totalbillamt4outlet' FROM {$tbL100} WHERE posid={$val['id']} AND datelogged='{$tr_date}'";
									//$wget_outlet_sales =  idget_data($wget_outlet_sales_sql);

									//$grand_total_covers = $grand_total_covers + $wget_outlet_sales[0]['totalcover4outlet'];
									//$grand_total_foods = $grand_total_foods + $wget_outlet_sales[0]['totalbillamt4outlet'];

									$pos_qff = "SELECT * FROM outlet_food_analysis_tbl WHERE pos={$val['posid']} AND ".$queryset;
									$pos_ff = idget_data($pos_qff);

									if($pos_ff[0]['food'] > 0) { $pos_amount = $pos_ff[0]['food']; }
									else { $pos_amount = $val['totalbillamt4outlet']; }

									if($pos_ff[0]['cover'] > 0) { $pos_covers = $pos_ff[0]['cover']; }
									else { $pos_covers = $val['totalcover4outlet']; }

									$grand_total_covers = $grand_total_covers + $pos_covers;
									$grand_total_foods = $grand_total_foods + $pos_amount;

									?>
										
										<tr>
											<td class="left-pull-5"><?php echo $posname; ?><input type="hidden" name="outlets[]" value="<?php echo $val['posid']; ?>"></td>
											<td class="right-pull-20 left-pull-20"><input type="text" name="covers[]" value="<?php echo $pos_covers; ?>" placeholder="0" onkeyup="sumup('covers','covertotal')"></td>
											<td class="right-pull-20 left-pull-20"><input type="text" name="foods[]" placeholder="0" value="<?php echo $pos_amount; ?>" onkeyup="sumup('foods','foodtotal')"></td>
										</tr>

									<?php

									$wget_outlet_sales_sql = "";
									$wget_outlet_sales = "";

									$pos_covers = ""; $pos_amount = "";

								endif;

							endforeach;
						endif;

						if($grand_total_foods > 0):
						
							?>

								<tr class="">
									<td class="default-text-font-bold left-pull-5">GRAND TOTAL</td>
									<td class="right-pull-20 left-pull-20"><input type="text" name="covertotal" id="covertotal" value="<?php echo $grand_total_covers; ?>" class="default-text-font-bold" readonly></td>
									<td class="right-pull-20 left-pull-20 grey-theme"><input type="text" name="foodtotal" id="foodtotal" value="<?php echo $grand_total_foods; ?>" class="default-text-font-bold" readonly></td>
								</tr>
							<?php

						else:
							?>
								<tr class="grey-theme"><td colspan="3" align="center">** No transactions found **</td></tr>
							<?php
						endif;
						?>
				</table>
			</div>
		</div>

		<?php if($grand_total_foods > 0): ?>

		<div class="sided-box pads30 alignct">
			<input type="submit" name="submitbutton" id="submitbutton" value="Save Food Flash">
		</div>

		<?php endif; ?>

	</div>
</form>


<script>

	function sumup(fields,sumto) {
		
		var amts = document.getElementsByName(fields+'[]');
		var total = document.getElementById(sumto);

		var i, sumfx = 0;

		for(var i=0; i < amts.length; i++) {
			
			var val;
			
			if(Number(amts[i].value) >= 0) { val = amts[i].value; }
			else { val = 0; }

			sumfx = sumfx + Number(val);
		}

		total.value = sumfx;
	}


	function foodSa(trdate) {
		window.location.href = window.location.href+'&trdate='+trdate;
	}


	function check_submit(e) {
		e.preventDefault();
		var conf = confirm("Are you sure you are ready to process this food flash form?");
		if(conf == true) { document.getElementById('ffh').submit(); }
	}

</script>