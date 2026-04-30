<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');


$frc_today1 = $server_get_date; $fdy1 = date('d-m-y',strtotime($frc_today1));
$frc_today2 = date('Y-m-d',strtotime($server_get_date. '+1 days')); $fdy2 = date('d-m-y',strtotime($frc_today2));
$frc_today3 = date('Y-m-d',strtotime($server_get_date. '+2 days')); $fdy3 = date('d-m-y',strtotime($frc_today3));
$frc_today4 = date('Y-m-d',strtotime($server_get_date. '+3 days')); $fdy4 = date('d-m-y',strtotime($frc_today4));
$frc_today5 = date('Y-m-d',strtotime($server_get_date. '+4 days')); $fdy5 = date('d-m-y',strtotime($frc_today5));
$frc_today6 = date('Y-m-d',strtotime($server_get_date. '+5 days')); $fdy6 = date('d-m-y',strtotime($frc_today6));

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the daily business summary report
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
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-30">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php if(isset($_POST['endate'])) { echo $_POST['endate']; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
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
		<div class="nc-width-100">
			<div id="section-to-print">
			
				<?php
					
					$startnumbr = 0; $keywords = "";

					if(isset($_POST['startdate']) && !empty($_POST['startdate'])) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['startdate']}'";
						$startdate = date('d-m-y',strtotime($_POST['startdate'])); $sdt = $_POST['startdate'];
						$endate = date('d-m-y',strtotime($_POST['endate'])); $edt = $_POST['endate'];
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$startdate = date('d-m-y',strtotime($server_get_date)); $sdt = $server_get_date;
						$endate = date('d-m-y',strtotime($server_get_date)); $edt = $server_get_date;
					}

				?>

				<div class="bottom-push-15" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold">Daily Business Summary Report (between <?php echo $startdate; ?> and <?php echo $startdate; ?>)</h3>
				</div>

				<?php

					$queryset = "deletedata=0".$keywords;

					$hkr = 0;

					$query1 = "roomstatus=1 AND deletedata=0";
					$data1 = "COUNT(roomnumber) AS 'totalrooms'";
					$result1 = pfetch($data1,$tbL56,$query1);
					$totalrooms1 = $result1[0]['totalrooms'];

					$query2 = "roomstatus=0 AND deletedata=0";
					$data2 = "COUNT(roomnumber) AS 'totalblockrooms'";
					$result2 = pfetch($data2,$tbL56,$query2);
					$totalrooms2 = $result2[0]['totalblockrooms'];

					$percentage_oor = ($totalrooms2 / $totalrooms1) * 100;
					$percentage_oor_apprx = number_format($percentage_oor,2);

					$query3 = "room_status_id=3 AND deletedata=0 AND checkin_date='{$sdt}'";
					$data3 = "COUNT(roomid) AS 'totaloccupied'";
					$result3 = pfetch($data3,$tbL127,$query3);
					$totalrooms3 = $result3[0]['totaloccupied'];

					$percentage_rocc = ($totalrooms3 / $totalrooms1) * 100;
					$percentage_rocc_apprx = number_format($percentage_rocc,2);

					$query4 = "SELECT COUNT(roomid) AS 'totalcompl' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND room_status_id=3 AND t1.checkin_date='{$sdt}'";
					$result4 = mysqli_data_array('assoc',$query4);
					$totalrooms4 = $result4[0]['totalcompl'];

					$percentage_compl = ($totalrooms4 / $totalrooms1) * 100;
					$percentage_compl_apprx = number_format($percentage_compl,2);

					$transient_rooms = $totalrooms3 - $totalrooms4;
					$transient_perc = ($transient_rooms / $totalrooms1) * 100;
					$transient_perc_apprx = number_format($transient_perc,2);

					$vacant_rooms = $totalrooms1 - $totalrooms3;
					$vacant_perc = ($vacant_rooms / $totalrooms1) * 100;
					$vacant_perc_apprx = number_format($vacant_perc,2);

					#---------------------------------------------------

					$qSales1 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid LEFT JOIN {$tbL130} t3 ON t3.booking_number=t1.booking_number WHERE (discount_amount = 0 OR discount_amount IS NULL) AND t2.status IN('CheckedIn','CheckedOut') AND booking_type NOT IN('complimentary') AND t2.datelogged='{$sdt}'";
					$sales1 = mysqli_data_array('assoc',$qSales1);

					$transientSalesnoTaxes = $sales1[0]['totalroomAmt'] - $sales1[0]['totaldiscountAmt'];
					$transientSalesTaxes = $transientSalesnoTaxes + $sales1[0]['totaltaxAmt'] + $sales1[0]['totalconsumptionAmt'] + $sales1[0]['totalserviceAmt'];
					$diffinSales = $transientSalesTaxes - $transientSalesnoTaxes;

					$qNoSales = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid WHERE t1.deletedata=1 AND t2.status IN('Cancelled') AND t2.datelogged='{$sdt}'";
					$nosales = mysqli_data_array('assoc',$qNoSales);
					$cancellationBkgs = $nosales[0]['totalroomAmt'] - $nosales[0]['totaldiscountAmt'];

					#---------------------------------------------------

					#avg room rate
					$avg_room_rate = @ ($transientSalesnoTaxes / $totalrooms3);

					#actual 2 potential perc.
					$a2p = @ ($transientSalesnoTaxes / $transientSalesTaxes) * 100;

					#---------------------------------------------------

					$qdayUseSales = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid WHERE checkin_date=checkout_date AND t2.status IN('CheckedOut') AND t2.datelogged='{$sdt}'";
					$dayUseSales = mysqli_data_array('assoc',$qdayUseSales);

					$totaldayUseSales = $dayUseSales[0]['totalroomAmt'] - $dayUseSales[0]['totaldiscountAmt'];

					#---------------------------------------------------

					#market segment analysis

					#individual
					$qmkSA1x = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='individual' AND booking_src='InPerson' AND t2.datelogged='{$sdt}'";
					$mkSA1x = mysqli_data_array('assoc',$qmkSA1x);

					$mkSA_ind_rooms = $mkSA1x[0]['totalrooms'];
					$mkSA_ind_rooms_perc = @ ($mkSA_ind_rooms / $totalrooms3) * 100;
					$mkSA_ind_rooms_perc = number_format($mkSA_ind_rooms_perc,2);

					$qmkSA1 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='individual' AND booking_src='InPerson' AND t2.datelogged='{$sdt}'";

					$mkSA1 = mysqli_data_array('assoc',$qmkSA1);
					$mkSA_ind = ($mkSA1[0]['totalroomAmt'] + $mkSA1[0]['totaltaxAmt'] + $mkSA1[0]['totalconsumptionAmt'] + $mkSA1[0]['totalserviceAmt']) - $mkSA1[0]['totaldiscountAmt'];

					#corporate
					$qmkSA2x = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='corporate' AND booking_src='InPerson' AND t2.datelogged='{$sdt}'";
					$mkSA2x = mysqli_data_array('assoc',$qmkSA2x);

					$mkSA_corp_rooms = $mkSA2x[0]['totalrooms'];
					$mkSA_corp_rooms_perc = @ ($mkSA_corp_rooms / $totalrooms3) * 100;
					$mkSA_corp_rooms_perc = number_format($mkSA_corp_rooms_perc,2);

					$qmkSA2 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='corporate' AND booking_src='InPerson' AND t2.datelogged='{$sdt}'";

					$mkSA2 = mysqli_data_array('assoc',$qmkSA2);
					$mkSA_corp = ($mkSA2[0]['totalroomAmt'] + $mkSA2[0]['totaltaxAmt'] + $mkSA2[0]['totalconsumptionAmt'] + $mkSA2[0]['totalserviceAmt']) - $mkSA2[0]['totaldiscountAmt'];

					#agent
					$mkSA_agent_rooms = 0;
					$mkSA_agent_rooms_perc = 0;
					$mkSA_agent = 0;
					$mkSA_agent_commission = 0;

					#complimentary
					$qmkSA3x = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND t2.datelogged='{$sdt}'";
					$mkSA3x = mysqli_data_array('assoc',$qmkSA3x);

					$mkSA_compl_rooms = $mkSA3x[0]['totalrooms'];
					$mkSA_compl_rooms_perc = @ ($mkSA_compl_rooms / $totalrooms3) * 100;
					$mkSA_compl_rooms_perc = number_format($mkSA_compl_rooms_perc,2);

					$qmkSA3 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND t2.datelogged='{$sdt}'";

					$mkSA3 = mysqli_data_array('assoc',$qmkSA3);
					$mkSA_compl = ($mkSA3[0]['totalroomAmt'] + $mkSA3[0]['totaltaxAmt'] + $mkSA3[0]['totalconsumptionAmt'] + $mkSA3[0]['totalserviceAmt']) - $mkSA3[0]['totaldiscountAmt'];


					#online booking
					$qmkSA4x = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type IN('corporate','individual') AND booking_src='Online' AND t2.datelogged='{$sdt}'";
					$mkSA4x = mysqli_data_array('assoc',$qmkSA4x);

					$mkSA_online_rooms = $mkSA4x[0]['totalrooms'];
					$mkSA_online_rooms_perc = @ ($mkSA_online_rooms / $totalrooms3) * 100;
					$mkSA_online_rooms_perc = number_format($mkSA_online_rooms_perc,2);

					$qmkSA4 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type IN('corporate','individual') AND booking_src='Online' AND t2.datelogged='{$sdt}'";

					$mkSA4 = mysqli_data_array('assoc',$qmkSA4);
					$mkSA_online = ($mkSA4[0]['totalroomAmt'] + $mkSA4[0]['totaltaxAmt'] + $mkSA4[0]['totalconsumptionAmt'] + $mkSA4[0]['totalserviceAmt']) - $mkSA4[0]['totaldiscountAmt'];


					#cancellation booking
					/*$qmkSA6x = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE status IN('Cancelled') AND t2.datelogged='{$sdt}'";
					$mkSA6x = mysqli_data_array('assoc',$qmkSA6x);

					$mkSA_cancel_rooms = $mkSA6x['totalrooms'];
					$mkSA_cancel_rooms_perc = ($mkSA_cancel_rooms / $totalrooms3) * 100;
					$mkSA_cancel_rooms_perc = number_format($mkSA_cancel_rooms_perc,2);*/

					$mkSA_cancelled = $cancellationBkgs;

					#rebate amount
					///$qmkSA5 = "SELECT SUM(amount) AS 'totalrebate' FROM {$tbL131} WHERE transaction_type='rebate' AND datelogged='{$sdt}'";
					$qmkSA5 = "SELECT SUM(amount) AS 'totalrebate' FROM {$tbL163} WHERE approval_status IN('Approved','Completed') AND datelogged='{$sdt}'";

					$mkSA5 = mysqli_data_array('assoc',$qmkSA5);
					$mkSA_rebate = $mkSA5[0]['totalrebate'];

					$mkSA_total = $mkSA_ind + $mkSA_corp + $mkSA_agent + $mkSA_compl + $mkSA_online + $mkSA_cancelled + $mkSA_rebate + $mkSA_agent_commission;

					#---------------------------------------------------

					#forecast

					$qfRc1a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today1}' AND status IN('Reserved')"; $fRc1a = mysqli_data_array('assoc',$qfRc1a);

					$qfRc1b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today1}' AND status IN('CheckedIn')"; $fRc1b = mysqli_data_array('assoc',$qfRc1b);

					$qfRc1c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today1}'";
					$fRc1c = mysqli_data_array('assoc',$qfRc1c);


					$qfRc2a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today2}' AND status IN('Reserved')"; $fRc2a = mysqli_data_array('assoc',$qfRc2a);

					$qfRc2b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today2}' AND status IN('CheckedIn')"; $fRc2b = mysqli_data_array('assoc',$qfRc2b);

					$qfRc2c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today2}'";
					$fRc2c = mysqli_data_array('assoc',$qfRc2c);


					$qfRc3a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today3}' AND status IN('Reserved')"; $fRc3a = mysqli_data_array('assoc',$qfRc3a);

					$qfRc3b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today3}' AND status IN('CheckedIn')"; $fRc3b = mysqli_data_array('assoc',$qfRc3b);

					$qfRc3c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today3}'";
					$fRc3c = mysqli_data_array('assoc',$qfRc3c);


					$qfRc4a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today4}' AND status IN('Reserved')"; $fRc4a = mysqli_data_array('assoc',$qfRc4a);

					$qfRc4b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today4}' AND status IN('CheckedIn')"; $fRc4b = mysqli_data_array('assoc',$qfRc4b);

					$qfRc4c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today4}'";
					$fRc4c = mysqli_data_array('assoc',$qfRc4c);


					$qfRc5a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today5}' AND status IN('Reserved')"; $fRc5a = mysqli_data_array('assoc',$qfRc5a);

					$qfRc5b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today5}' AND status IN('CheckedIn')"; $fRc5b = mysqli_data_array('assoc',$qfRc5b);

					$qfRc5c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today5}'";
					$fRc5c = mysqli_data_array('assoc',$qfRc5c);


					$qfRc6a = "SELECT COUNT(roomid) AS 'totalroomsAr' FROM {$tbL127} WHERE checkin_date='{$frc_today6}' AND status IN('Reserved')"; $fRc6a = mysqli_data_array('assoc',$qfRc6a);

					$qfRc6b = "SELECT COUNT(roomid) AS 'totalroomsOr' FROM {$tbL127} WHERE checkin_date='{$frc_today6}' AND status IN('CheckedIn')"; $fRc6b = mysqli_data_array('assoc',$qfRc6b);

					$qfRc6c = "SELECT COUNT(roomid) AS 'totalroomsDr' FROM {$tbL127} WHERE checkout_date='{$frc_today6}'";
					$fRc6c = mysqli_data_array('assoc',$qfRc6c);

					#---------------------------------------------------

					#reservation

					$qRSv1 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE datelogged='{$sdt}' AND reservation='Reserving'"; $RSv1 = mysqli_data_array('assoc',$qRSv1);

					$qRSv2 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE datelogged='{$sdt}' AND status IN('No Show')"; $RSv2 = mysqli_data_array('assoc',$qRSv2);

					$qRSv3 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE datelogged='{$sdt}' AND status IN('Cancelled')"; $RSv3 = mysqli_data_array('assoc',$qRSv3);

					$qRSv4 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE checkin_date='{$sdt}' AND reservation='Reserving' AND status IN('CheckedIn')"; $RSv4 = mysqli_data_array('assoc',$qRSv4);

					$qRSv5 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_src='InPerson' AND t2.checkin_date='{$sdt}' AND status IN('CheckedIn')";
					$RSv5 = mysqli_data_array('assoc',$qRSv5);

					$qRSv6 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE checkin_date='{$sdt}' AND status IN('CheckedIn')"; $RSv6 = mysqli_data_array('assoc',$qRSv6);

					$qRSv7 = "SELECT COUNT(roomid) AS 'totalrooms' FROM {$tbL127} WHERE checkout_date='{$sdt}' AND status IN('CheckedOut')"; $RSv7 = mysqli_data_array('assoc',$qRSv7);

					$totalRMP = @ ($RSv1[0]['totalrooms'] / $totalrooms1) * 100; $totalRMP = number_format($totalRMP,2);
					$totalNSP = @ ($RSv2[0]['totalrooms'] / $totalrooms1) * 100; $totalNSP = number_format($totalNSP,2);
					$totalCLP = @ ($RSv3[0]['totalrooms'] / $totalrooms1) * 100; $totalCLP = number_format($totalCLP,2);
					$totalRUP = @ ($RSv4[0]['totalrooms'] / $totalrooms1) * 100; $totalRUP = number_format($totalRUP,2);
					$totalAWP = @ ($RSv5[0]['totalrooms'] / $totalrooms1) * 100; $totalAWP = number_format($totalAWP,2);

					//$totalRSs = $RSv1[0]['totalrooms'] + $RSv2[0]['totalrooms'] + $RSv3[0]['totalrooms'] + $RSv4[0]['totalrooms'] + $RSv5[0]['totalrooms'];
					
				?>

				<div class="cs-height-30"></div>

				<div class="">
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="alignct default-text-font-bold">Room Statistics</td>
							<td class="alignct default-text-font-bold">Room Revenue</td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick">
								<table cellpadding="3" cellspacing="0">
									<tr>
										<td class="alignct">Details</td>
										<td class="alignct"><?php echo $startdate; ?></td>
										<td class="alignct">%</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Transient</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $transient_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $transient_perc_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Complimentary</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms4; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $percentage_compl_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">House Use</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms3; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $percentage_rocc_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Rooms Occupied</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms3; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $percentage_rocc_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Out of Order Rooms</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms2; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $percentage_oor_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Vacant Rooms</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $vacant_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $vacant_perc_apprx; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Available Rooms</td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms1; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">100.00</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Occupancy %</td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $percentage_rocc_apprx; ?> %</td>
									</tr>
								</table>
							</td>
							<td class="right-pull-10 left-pull-10 box-border-thick">
								<table cellpadding="3" cellspacing="0">
									<tr>
										<td class="alignct">Details</td>
										<td class="alignct"><?php echo $startdate; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Booking Transient (Exclusive of taxes)<br>Cancellation Transient (Exclusive of taxes)</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($transientSalesnoTaxes,2); ?><br><?php echo number_format($cancellationBkgs,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Day Use</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($totaldayUseSales,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Actual Rooms Rev (Booking Transient + Cancellation Transient) (Exclusive of taxes)</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($transientSalesnoTaxes,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Potential Rooms Rev.(Inclusive of taxes)</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($transientSalesTaxes,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Difference</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($diffinSales,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">Average Room Rate</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($avg_room_rate,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">% of Actual to Pot Rev.</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo number_format($a2p,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right">No of Guests</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $totalrooms3; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<div class="cs-height-50"></div>

					<h3 class="large nobold default-text-font-bold alignct">Reservation Statistics</h3>
					
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="alignct">Details</td>
							<td class="alignct"><?php echo $startdate; ?></td>
							<td class="alignct">%</td>
							<td class="alignct">Details</td>
							<td class="alignct"><?php echo $startdate; ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Reservations Made</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $RSv1[0]['totalrooms']; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalRMP; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Room Arrivals</td>
							<td class="right-pull-10 left-pull-10 alignrt"><?php echo $RSv6[0]['totalrooms']; ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Less No Show</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $RSv2[0]['totalrooms']; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalNSP; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Guest Arrival</td>
							<td class="right-pull-10 left-pull-10 alignrt"><?php echo $RSv6[0]['totalrooms']; ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Less Cancellation</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $RSv3[0]['totalrooms']; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalCLP; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Room Departure</td>
							<td class="right-pull-10 left-pull-10 alignrt"><?php echo $RSv7[0]['totalrooms']; ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Actual Reservations Used</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $RSv4[0]['totalrooms']; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalRUP; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Guest Departure</td>
							<td class="right-pull-10 left-pull-10 alignrt"><?php echo $RSv7[0]['totalrooms']; ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Add Actual Walkins</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $RSv5[0]['totalrooms']; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalAWP; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right"></td>
							<td class="right-pull-10 left-pull-10 alignrt"></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Total Rooms Occupied</td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $totalrooms3; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $percentage_rocc_apprx; ?></td>
							<td class="right-pull-10 left-pull-10 box-border-thick-right"></td>
							<td class="right-pull-10 left-pull-10 alignrt"></td>
						</tr>
					</table>

					<div class="cs-height-50"></div>

					<h3 class="large nobold default-text-font-bold alignct">Forecast Date Reservation Statistics</h3>
					
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="alignct default-text-font-bold box-border-thick">Forecast Dates</td>
							<td class="alignct">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy1; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy2; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy3; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy4; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy5; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 alignct"><?php echo $fdy6; ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Arrival</td>
										<td class="right-pull-10 left-pull-10 alignct default-text-font-bold">Occupied</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Room Arrival/Occupied</td>
							<td class="right-pull-10 left-pull-10">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc1a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc1b[0]['totalroomsOr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc2a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc2b[0]['totalroomsOr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc3a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc3b[0]['totalroomsOr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc4a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc4b[0]['totalroomsOr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc5a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc5b[0]['totalroomsOr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc6a[0]['totalroomsAr']; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick-right alignrt"><?php echo $fRc6b[0]['totalroomsOr']; ?></td>
										
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Room Departure</td>
							<td class="right-pull-10 left-pull-10">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc1c[0]['totalroomsDr']; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc2c[0]['totalroomsDr']; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc3c[0]['totalroomsDr']; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc4c[0]['totalroomsDr']; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc5c[0]['totalroomsDr']; ?></td>
										<td colspan="2" class="right-pull-10 left-pull-10 box-border-thick-right alignct"><?php echo $fRc6c[0]['totalroomsDr']; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<div class="cs-height-50"></div>

					<h3 class="large nobold default-text-font-bold alignct">Business Mix/Market Segment Analaysis</h3>

					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="alignct default-text-font-bold">Segment BreakDown</td>
							<td class="alignct default-text-font-bold">Last Business Date</td>
						</tr>
						<tr>
							<td class="">
								<table cellpadding="3" cellspacing="0">
									<tr>
										<td class="alignct">&nbsp;</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Individual</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Corporate Org/branch</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Agent</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Complimentary</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Online</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Cancellation Amount</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Rebate Amount</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick">Agent Commission</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick default-text-font-bold">Total</td>
									</tr>
								</table>
							</td>
							<td class="">
								<table cellpadding="3" cellspacing="0">
									<tr>
										<td class="alignct">Unit</td>
										<td class="alignct">%</td>
										<td class="alignct">Revenue</td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_ind_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_ind_rooms_perc; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_ind,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_corp_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_corp_rooms_perc; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_corp,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_agent_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_agent_rooms_perc; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_agent,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_compl_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_compl_rooms_perc; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_compl,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_online_rooms; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $mkSA_online_rooms_perc; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_online,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_cancelled,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_rebate,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_agent_commission,2); ?></td>
									</tr>
									<tr>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt"><?php echo $totalrooms3; ?></td>
										<td class="right-pull-10 left-pull-10 box-border-thick alignrt">0</td>
										<td class="right-pull-10 left-pull-10 default-text-font-bold box-border-thick alignrt">&#8358; <?php echo number_format($mkSA_total,2); ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

				</div>

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

</script>