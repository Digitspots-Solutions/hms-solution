<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_room_types = select_dt_fetch('status','Active',$tbL52,'id','name');

if(isset($_POST['startdate']) && !empty($_POST['startdate'])) { $startdate = $_POST['startdate']; }
else { $startdate = $server_get_date; }

if(isset($_POST['endate']) && !empty($_POST['endate'])) { $endate = $_POST['endate']; }
else { $endate = $server_get_date; }

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see food and beverages datewise report
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
			<input type="hidden" name="rtask" id="rtask" value="fbr">
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="date" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100 bottom-push-30">
			<div id="section-to-print">

				<div class="bottom-push-15" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold nomargin">Food and Beverages Revenue (<?php echo date('d-m-Y',strtotime($startdate)); ?> to <?php echo date('d-m-Y',strtotime($endate)); ?>)</h3>
					<h4 class="large nobold">Printed By: <?php echo $printed_by.' on '.$printed_date; ?></h4>
				</div>

				<table cellpadding="3" cellspacing="0">
					<tr class="dark-grey-theme">
						<td class="alignct default-text-font-bold">Date</td>
						<td class="alignct default-text-font-bold">Breakfast</td>
						<td class="alignct default-text-font-bold">Lunch</td>
						<td class="alignct default-text-font-bold">Dinner</td>
						<td class="alignct default-text-font-bold">Beverages</td>
						<td class="alignct default-text-font-bold">Gross Revenue</td>
						<td class="alignct default-text-font-bold">Net Revenue</td>
					</tr>

					<?php
						if(isset($_POST['rtask']) && $_POST['rtask'] == 'fbr') {

							$fbr_sql = "SELECT datelogged FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}' GROUP BY datelogged"; $datagroup = wgetSQL($fbr_sql);

							if(is_array($datagroup) && count($datagroup) > 0) {
								
								$total_breakfast = 0; $total_lunch = 0; $total_dinner = 0;
								$total_bvg = 0; $total_gross = 0; $total_net = 0;

								foreach($datagroup as $key => $val) {
									
									$bf_sql = "SELECT SUM(amount) as totalamt, SUM(discount) as totaldisc FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0 AND foodtype IN(1) AND main_category IN(1) AND billtype NOT IN(3) AND datelogged='{$val['datelogged']}'";
									$bf_dataset = wgetSQL($bf_sql);

									$lu_sql = "SELECT SUM(amount) as totalamt, SUM(discount) as totaldisc FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0 AND foodtype IN(2) AND main_category IN(1) AND billtype NOT IN(3) AND datelogged='{$val['datelogged']}'";
									$lu_dataset = wgetSQL($lu_sql);

									$dn_sql = "SELECT SUM(amount) as totalamt, SUM(discount) as totaldisc FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0 AND foodtype IN(3) AND main_category IN(1) AND billtype NOT IN(3) AND datelogged='{$val['datelogged']}'";
									$dn_dataset = wgetSQL($dn_sql);

									$bvg_sql = "SELECT SUM(amount) as totalamt, SUM(discount) as totaldisc FROM {$tbL99} WHERE isreversed=0 AND status IN('Completed') AND deletedata=0 AND foodtype IN(1,2,3) AND main_category IN(2) AND billtype NOT IN(3) AND datelogged='{$val['datelogged']}'";
									$bvg_dataset = wgetSQL($bvg_sql);

									$totalBf = $bf_dataset[0]['totalamt'] - $bf_dataset[0]['totaldisc'];
									$totalLu = $lu_dataset[0]['totalamt'] - $lu_dataset[0]['totaldisc'];
									$totalDn = $dn_dataset[0]['totalamt'] - $dn_dataset[0]['totaldisc'];
									$totalBvg = $bvg_dataset[0]['totalamt'] - $bvg_dataset[0]['totaldisc'];
									
									$gross = $totalBf + $totalLu + $totalDn + $totalBvg;
									
									$vat = ($gh_get_vat / 100) * $gross;
									$service = ($gh_get_service_charge / 100) * $gross;
									$consumption = ($gh_get_consumption_tax / 100) * $gross;
									$net = $gross - ($vat + $service + $consumption);

									$total_breakfast = $total_breakfast + $totalBf;
									$total_lunch = $total_lunch + $totalLu;
									$total_dinner = $total_dinner + $totalDn;
									$total_bvg = $total_bvg + $totalBvg;
									$total_gross = $total_gross + $gross;
									$total_net = $total_net + $net;

									?>
										<tr>
											<td class="alignct"><?php echo date('y-m-d',strtotime($val['datelogged'])); ?></td>
											<td class="alignct"><?php echo number_format($totalBf,2); ?></td>
											<td class="alignct"><?php echo number_format($totalLu,2); ?></td>
											<td class="alignct"><?php echo number_format($totalDn,2); ?></td>
											<td class="alignct"><?php echo number_format($totalBvg,2); ?></td>
											<td class="alignct"><?php echo number_format($gross,2); ?></td>
											<td class="alignct"><?php echo number_format($net,2); ?></td>
										</tr>
									<?php

									$totalBf = 0; $totalLu = 0; $totalDn = 0; $totalBvg = 0; $gross = 0; $net = 0;
									$vat = 0; $service = 0; $consumption = 0;
								}

								?>
									<tr class="grey-theme">
										<td class="alignct">Total</td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_breakfast,2); ?></td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_lunch,2); ?></td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_dinner,2); ?></td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_bvg,2); ?></td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_gross,2); ?></td>
										<td class="alignct default-text-font-bold"><?php echo number_format($total_net,2); ?></td>
									</tr>
								<?php
							}
						}
					?>

				</table>

			</div>
		</div>
	</div>
</div>


<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

</script>