<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see manager flash report
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
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-30">
				<h3 class="large nobold default-text-font-bold">Choose Date</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Choose Date?" value="<?php echo $server_get_date; ?>" class="nopads no-back-black">
			</span>
			<!--<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>-->
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
					
					$var_amt = array();

					$tbLOt = '<table cellpadding="3" cellspacing="0">';
					$tbLCt = '</table>';

					$startnumbr = 0; $keywords = ""; $rkeywords = "";

					if(isset($_POST['startdate']) && !empty($_POST['startdate'])) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['startdate']}'";
						$rkeywords .= " AND transaction_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['startdate']}'";
						$startdate = date('d-m-Y',strtotime($_POST['startdate'])); $sdt = $_POST['startdate'];
						$endate = date('d-m-Y',strtotime($_POST['startdate'])); $edt = $_POST['startdate'];
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$rkeywords .= " AND transaction_date BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$startdate = date('d-m-Y',strtotime($server_get_date)); $sdt = $server_get_date;
						$endate = date('d-m-Y',strtotime($server_get_date)); $edt = $server_get_date;
					}

					$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
					$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

				?>

				<div class="bottom-push-15" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold">Manager Flash Report (between <?php echo $startdate; ?> and <?php echo $endate; ?>)</h3>
					<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
				</div>

				<?php

					$queryset = "deletedata=0".$keywords;
					$r_queryset = "deletedata=0".$rkeywords;

					$hkr = 0;

					$query1 = "roomstatus=1 AND deletedata=0";
					$data1 = "COUNT(roomnumber) AS 'totalrooms'";
					$result1 = pfetch($data1,$tbL56,$query1);

					$query2 = "roomstatus=0 AND deletedata=0";
					$data2 = "COUNT(roomnumber) AS 'totalblockrooms'";
					$result2 = pfetch($data2,$tbL56,$query2);

					$totalrooms2 = $result1[0]['totalrooms'] - $result2[0]['totalblockrooms'];

					$query3 = "room_status_id=3 AND deletedata=0";
					$data3 = "COUNT(roomid) AS 'totaloccupied'";
					$result3 = pfetch($data3,$tbL127,$query3);

					$totalrooms3 = $result1[0]['totalrooms'] - $result3[0]['totaloccupied'];
					$totalrooms4 = $totalrooms3 - $result2[0]['totalblockrooms'];

					$query4 = "SELECT COUNT(roomid) AS 'totalcompl' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND room_status_id=3";
					$result4 = mysqli_data_array('assoc',$query4);
					
					$totalrooms5 = $result3[0]['totaloccupied'] - ($result4[0]['totalcompl'] + $hkr);
					$totalrooms6 = $result3[0]['totaloccupied'] - $hkr;
					$totalrooms7 = $result3[0]['totaloccupied'] - $result4[0]['totalcompl'];

					$query5 = "SELECT SUM(adult) AS 'totaladults', SUM(child) AS 'totalchilds' FROM {$tbL127} WHERE room_status_id=3 AND deletedata=0";
					$result5 = mysqli_data_array('assoc',$query5);

					$totalinperson = $result5[0]['totaladults'] + $result5[0]['totalchilds'];

					$query6 = "SELECT COUNT(roomid) AS 'totalindrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='individual' AND room_status_id=3";
					$result6 = mysqli_data_array('assoc',$query6);

					$query7 = "SELECT COUNT(roomid) AS 'totalcorprooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='corporate' AND room_status_id=3";
					$result7 = mysqli_data_array('assoc',$query7);

					$query8 = "SELECT COUNT(roomid) AS 'totalagentrooms' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='agent' AND room_status_id=3";
					$result8 = mysqli_data_array('assoc',$query8);

					$percentage_rocc = ($result3[0]['totaloccupied'] / $result1[0]['totalrooms']) * 100;
					$percentage_compl = ($result4[0]['totalcompl'] / $result1[0]['totalrooms']) * 100;
					$percentage_hkr = ($hkr / $result1[0]['totalrooms']) * 100;
					$percentage_oor = ($result2[0]['totalblockrooms'] / $result1[0]['totalrooms']) * 100;

					$base_perc1 = $percentage_rocc - ($percentage_compl + $percentage_hkr);
					$base_perc2 = $percentage_rocc - ($percentage_compl + $percentage_hkr + $percentage_oor);
					$base_perc3 = $percentage_rocc - $percentage_compl;
					$base_perc4 = $percentage_rocc - $percentage_hkr;
					$base_perc5 = $percentage_rocc - ($percentage_compl + $percentage_oor);
					$base_perc6 = $percentage_rocc - ($percentage_hkr + $percentage_oor);
					$base_perc7 = $percentage_rocc - $percentage_oor;

					$query9 = "SELECT COUNT(roomid) AS 'totalEchkin' FROM {$tbL127} WHERE early_checkin_charges > 0 AND ".$queryset;
					$result9 = mysqli_data_array('assoc',$query9);

					$query10 = "SELECT COUNT(roomid) AS 'totalLchkout' FROM {$tbL127} WHERE late_checkout_charges > 0 AND ".$queryset;
					$result10 = mysqli_data_array('assoc',$query10);

					$query11 = "SELECT COUNT(roomid) AS 'totaldayUse' FROM {$tbL127} WHERE checkin_date = checkout_date AND ".$queryset;
					$result11 = mysqli_data_array('assoc',$query11);

					$query12 = "SELECT COUNT(roomid) AS 'totalNoshow' FROM {$tbL127} WHERE status IN('No Show') AND ".$queryset;
					$result12 = mysqli_data_array('assoc',$query12);

					/*$query13 = "SELECT SUM(adult) AS 'totalNadults', SUM(child) AS 'totalNchilds' FROM {$tbL127} WHERE status IN('No Show') AND ".$queryset; $result13 = mysqli_data_array('assoc',$query13);
					$totalNoshowPerson = $result13['totalNadults'] + $result13['totalNchilds'];*/

					$query14 = "SELECT COUNT(roomid) AS 'totalCancelled' FROM {$tbL127} WHERE reservation='Reserving' AND status IN('Cancelled') AND datelogged='{$server_get_date}'";
					$result14 = mysqli_data_array('assoc',$query14);

					$query15 = "SELECT COUNT(roomid) AS 'totalDirty' FROM {$tbL94} WHERE housekeeping_stateid=2";
					$result15 = mysqli_data_array('assoc',$query15);

					$query16 = "SELECT COUNT(roomid) AS 'totalTouchup' FROM {$tbL94} WHERE housekeeping_stateid=6";
					$result16 = mysqli_data_array('assoc',$query16);

					$query17 = "SELECT COUNT(roomid) AS 'totalClean' FROM {$tbL94} WHERE housekeeping_stateid=1";
					$result17 = mysqli_data_array('assoc',$query17);

					$query18 = "SELECT COUNT(roomid) AS 'totalRepair' FROM {$tbL94} WHERE housekeeping_stateid=5";
					$result18 = mysqli_data_array('assoc',$query18);

					$query19 = "SELECT COUNT(roomid) AS 'totalArrivalr' FROM {$tbL127} WHERE reservation='Reserving' AND checkin_date='{$server_get_date}'";
					$result19 = mysqli_data_array('assoc',$query19);

					$query20 = "SELECT COUNT(booking_number) AS 'totalWalkin' FROM {$tbL130} WHERE booking_src='InPerson' AND ".$queryset; $result20 = mysqli_data_array('assoc',$query20);

					$query21 = "SELECT COUNT(roomid) AS 'totalWalkinr' FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_src='InPerson' AND t1.datelogged BETWEEN '{$sdt}' AND '{$edt}'"; $result21 = mysqli_data_array('assoc',$query21);

					$query22 = "SELECT COUNT(roomid) AS 'totalDeptr' FROM {$tbL127} WHERE checkout_date='{$server_get_date}'";
					$result22 = mysqli_data_array('assoc',$query22);

					$chkOutomorrow = date('Y-m-d',strtotime($server_get_date. '+1 days'));
					$query23 = "SELECT COUNT(roomid) AS 'totalDeptr2' FROM {$tbL127} WHERE checkout_date='{$chkOutomorrow}'";
					$result23 = mysqli_data_array('assoc',$query23);

					$query24 = "SELECT COUNT(roomid) AS 'totalArrivalr2' FROM {$tbL127} WHERE checkin_date='{$chkOutomorrow}'";
					$result24 = mysqli_data_array('assoc',$query24);

					$percentage_rchk2m = ($result24[0]['totalArrivalr2'] / $result1[0]['totalrooms']) * 100;

					$query25 = "SELECT COUNT(roomid) AS 'totalReserve2d' FROM {$tbL127} WHERE status IN('Reserving') AND holdtill='{$server_get_date}'"; $result25 = mysqli_data_array('assoc',$query25);
					
					$datasheet = $tbLOt;
					$datasheet .= '<tr><td>Total Rooms in Hotel</td><td>'.$result1[0]['totalrooms'].'</td></tr>';
					$datasheet .= '<tr><td>Rooms Occupied</td><td>'.$result3[0]['totaloccupied'].'</td></tr>';
					$datasheet .= '<tr><td>Total Rooms minus Out-of-order Rooms</td><td>'.$totalrooms2.'</td></tr>';
					$datasheet .= '<tr><td>Available Rooms</td><td>'.$totalrooms3.'</td></tr>';
					$datasheet .= '<tr><td>Available Rooms minus Out-of-order Rooms</td><td>'.$totalrooms4.'</td></tr>';
					$datasheet .= '<tr><td>Complimentary Rooms</td><td>'.$result4[0]['totalcompl'].'</td></tr>';
					$datasheet .= '<tr><td>Rooms Occupied minus Comp minus house rooms</td><td>'.$totalrooms5.'</td></tr>';
					$datasheet .= '<tr><td>Rooms Occupied minus house rooms</td><td>'.$totalrooms6.'</td></tr>';
					$datasheet .= '<tr><td>Rooms Occupied minus Comp</td><td>'.$totalrooms7.'</td></tr>';
					$datasheet .= '<tr><td>Out Of Order Rooms</td><td>'.$result2[0]['totalblockrooms'].'</td></tr>';
					$datasheet .= '<tr><td>In House Adults</td><td>'.$result5[0]['totaladults'].'</td></tr>';
					$datasheet .= '<tr><td>In House Child</td><td>'.$result5[0]['totalchilds'].'</td></tr>';
					$datasheet .= '<tr><td>In House Persons</td><td>'.$totalinperson.'</td></tr>';
					$datasheet .= '<tr><td>Individual Rooms In House</td><td>'.$result6[0]['totalindrooms'].'</td></tr>';
					$datasheet .= '<tr><td>Hotel Agent Rooms In House</td><td>'.$result8[0]['totalagentrooms'].'</td></tr>';
					$datasheet .= '<tr><td>Corporate Rooms In House</td><td>'.$result7[0]['totalcorprooms'].'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied	</td><td>'.number_format($percentage_rocc,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus Comp minus house rooms</td><td>'.number_format($base_perc1,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus Comp minus house rooms minus Out-of-order Rooms</td><td>'.number_format($base_perc2,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus Comp</td><td>'.number_format($base_perc3,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus house rooms</td><td>'.number_format($base_perc4,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus Comp minus Out-of-order Rooms</td><td>'.number_format($base_perc5,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus house rooms minus Out-of-order Rooms</td><td>'.number_format($base_perc6,2).'</td></tr>';
					$datasheet .= '<tr><td>% Rooms Occupied minus ooo Rooms</td><td>'.number_format($base_perc7,2).'</td></tr>';
					$datasheet .= '<tr><td>Early Check-in Counts</td><td>'.$result9[0]['totalEchkin'].'</td></tr>';
					$datasheet .= '<tr><td>Late Check-out Counts</td><td>'.$result10[0]['totalLchkout'].'</td></tr>';
					$datasheet .= '<tr><td>Day Use Count</td><td>'.$result11[0]['totaldayUse'].'</td></tr>';
					$datasheet .= '<tr><td>No Show Rooms</td><td>'.$result12[0]['totalNoshow'].'</td></tr>';
					$datasheet .= '<tr><td>No Show Persons</td><td>'.$result12[0]['totalNoshow'].'</td></tr>';
					$datasheet .= '<tr><td>Cancelled Reservation For Today</td><td>'.$result14[0]['totalCancelled'].'</td></tr>';
					$datasheet .= '<tr><td>Cancel Persons</td><td>'.$result14[0]['totalCancelled'].'</td></tr>';
					$datasheet .= '<tr><td>Dirty</td><td>'.$result15[0]['totalDirty'].'</td></tr>';
					$datasheet .= '<tr><td>Touchup</td><td>'.$result16[0]['totalTouchup'].'</td></tr>';
					$datasheet .= '<tr><td>Clean</td><td>'.$result17[0]['totalClean'].'</td></tr>';
					$datasheet .= '<tr><td>Repair</td><td>'.$result18[0]['totalRepair'].'</td></tr>';
					$datasheet .= '<tr><td>Arrival Rooms</td><td>'.$result19[0]['totalArrivalr'].'</td></tr>';
					$datasheet .= '<tr><td>Arrival Persons</td><td>'.$result19[0]['totalArrivalr'].'</td></tr>';
					$datasheet .= '<tr><td>Walkin Rooms</td><td>'.$result21[0]['totalWalkinr'].'</td></tr>';
					$datasheet .= '<tr><td>Walkin Persons</td><td>'.$result20[0]['totalWalkin'].'</td></tr>';
					$datasheet .= '<tr><td>Departure Rooms</td><td>'.$result22[0]['totalDeptr'].'</td></tr>';
					$datasheet .= '<tr><td>Departure Persons</td><td>'.$result22[0]['totalDeptr'].'</td></tr>';
					$datasheet .= '<tr><td>Arrival Rooms For Tomorrow</td><td>'.$result24[0]['totalArrivalr2'].'</td></tr>';
					$datasheet .= '<tr><td>Departure Rooms For Tomorrow</td><td>'.$result23[0]['totalDeptr2'].'</td></tr>';
					$datasheet .= '<tr><td>Departure persons For Tomorrow</td><td>'.$result23[0]['totalDeptr2'].'</td></tr>';
					$datasheet .= '<tr><td>% Room Occupied for Tomorrows</td><td>'.number_format($percentage_rchk2m,2).'</td></tr>';
					$datasheet .= '<tr><td>Room Nights Reservation Today</td><td>'.$result25[0]['totalReserve2d'].'</td></tr>';
					$datasheet .= '<tr><td>Doubles as Singles</td><td>0</td></tr>';
					$datasheet .= '<tr><td>Todays Demand</td><td>'.$result3[0]['totaloccupied'].'</td></tr>';
					$datasheet .= $tbLCt;

					echo $datasheet;


					#----------------------------------------------------------------------------------------

					/*$bSquery2 = "SELECT SUM(amount) AS 'totalrebate' FROM {$tbL131} WHERE transaction_type='rebate' AND ".$queryset; $bSresult2 = mysqli_data_array('assoc',$bSquery2);
					$totalrebate = $bSresult2[0]['totalrebate'];*/

					$bSquery2 = "SELECT SUM(amount) AS 'totalrebate' FROM {$tbL163} WHERE approval_status IN('Approved','Completed') AND ".$r_queryset; $bSresult2 = mysqli_data_array('assoc',$bSquery2);
					$totalrebate = $bSresult2[0]['totalrebate'];

					#----------------------------------------------------------------------------------------

					$bSquery1 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid WHERE discount_amount > 0 AND t2.status IN('CheckedIn','CheckedOut') AND t2.datelogged BETWEEN '{$sdt}' AND '{$edt}'";
					$bSresult1 = mysqli_data_array('assoc',$bSquery1);

					$roomSoldonDiscount = $bSresult1[0]['totalroomAmt'] - $bSresult1[0]['totaldiscountAmt'];

					$totaltaxAmt = $bSresult1[0]['totaltaxAmt'];
					$totalconsumptionAmt = $bSresult1[0]['totalconsumptionAmt'];
					$totaldiscountAmt = $bSresult1[0]['totaldiscountAmt'];
					$totalserviceAmt = $bSresult1[0]['totalserviceAmt'];

					$compute1 = ($roomSoldonDiscount + $totaltaxAmt + $totalconsumptionAmt + $totalserviceAmt) - $totalrebate;

					#----------------------------------------------------------------------------------------

					//$bSquery3 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND t2.datelogged BETWEEN '{$sdt}' AND '{$edt}'";

					$bSquery3 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} WHERE charge_type='complimentary' AND datelogged BETWEEN '{$sdt}' AND '{$edt}'";
					$bSresult3 = mysqli_data_array('assoc',$bSquery3);

					$roomSoldonCompl = ($bSresult3[0]['totalroomAmt'] + $bSresult3[0]['totaltaxAmt'] + $bSresult3[0]['totalconsumptionAmt'] + $bSresult3[0]['totalserviceAmt']) - $bSresult3[0]['totaldiscountAmt'];

					#----------------------------------------------------------------------------------------

					$bSquery4 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid WHERE t2.status IN('No Show') AND t2.datelogged BETWEEN '{$sdt}' AND '{$edt}'";
					$bSresult4 = mysqli_data_array('assoc',$bSquery4);

					$actualCal1 = $bSresult4[0]['totalroomAmt'] - $bSresult4[0]['totaldiscountAmt'];
					$compute2 = $actualCal1 + $bSresult4[0]['totaltaxAmt'] + $bSresult4[0]['totalconsumptionAmt'] + $bSresult4[0]['totalserviceAmt'];

					#----------------------------------------------------------------------------------------

					$bSquery5 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid WHERE checkin_date=checkout_date AND t2.status IN('CheckedOut') AND t2.datelogged BETWEEN '{$sdt}' AND '{$edt}'";
					$bSresult5 = mysqli_data_array('assoc',$bSquery5);

					$actualCal2 = $bSresult5[0]['totalroomAmt'] - $bSresult5[0]['totaldiscountAmt'];
					$compute3 = $actualCal2 + $bSresult5[0]['totaltaxAmt'] + $bSresult5[0]['totalconsumptionAmt'] + $bSresult5[0]['totalserviceAmt'];
					
					#----------------------------------------------------------------------------------------

					$bSquery6x = "SELECT SUM(room_amount) AS 'totalSubCharge1' FROM {$tbL134} WHERE wkf=2 AND ".$queryset;
					$bSresult6x = mysqli_data_array('assoc',$bSquery6x);

					$bSquery6xr = "SELECT SUM(room_amount) AS 'totalSubCharge2' FROM {$tbL134} WHERE wkf=1 AND ".$queryset;
					$bSresult6xr = mysqli_data_array('assoc',$bSquery6xr);

					/*$bSquery6 = "SELECT SUM(early_checkin_charges) AS 'totalSubCharge1', SUM(late_checkout_charges) AS 'totalSubCharge2', SUM(cancellation_charges) AS 'totalSubCharge3' FROM {$tbL127} WHERE ".$queryset;
					$bSresult6 = mysqli_data_array('assoc',$bSquery6);*/

					$bSquery6 = "SELECT SUM(cancellation_charges) AS 'totalSubCharge3' FROM {$tbL127} WHERE ".$queryset;
					$bSresult6 = mysqli_data_array('assoc',$bSquery6);

					$totalEarlyCharges = $bSresult6x[0]['totalSubCharge1'];
					$totalLateCharges = $bSresult6xr[0]['totalSubCharge2'];
					$totalCancelCharges = $bSresult6[0]['totalSubCharge3'];

					#----------------------------------------------------------------------------------------

					$bSquery7 = "SELECT SUM(room_amount) AS 'totalroomAmt', SUM(discount_amount) AS 'totaldiscountAmt', SUM(tax_amount) AS 'totaltaxAmt', SUM(consumption_tax_amount) AS 'totalconsumptionAmt', SUM(service_charge) AS 'totalserviceAmt' FROM {$tbL134} t1 LEFT JOIN {$tbL127} t2 ON t1.roomid=t2.roomid LEFT JOIN {$tbL130} t3 ON t3.booking_number=t1.booking_number WHERE (discount_amount = 0 OR discount_amount IS NULL) AND t2.status IN('CheckedIn','CheckedOut') AND booking_type NOT IN('complimentary') AND t2.datelogged BETWEEN '{$sdt}' AND '{$edt}'";

					$bSresult7 = mysqli_data_array('assoc',$bSquery7);

					$roomSoldFullRate = $bSresult7[0]['totalroomAmt'];
					$compute4 = $roomSoldFullRate + $bSresult7[0]['totaltaxAmt'] + $bSresult7[0]['totalconsumptionAmt'] + $bSresult7[0]['totalserviceAmt'];

					#----------------------------------------------------------------------------------------

					$baseCal1 = $roomSoldFullRate + $roomSoldonDiscount + $actualCal1 + $actualCal2 + $totalEarlyCharges + $totalLateCharges + $totalCancelCharges;

					$baseCal2 = $bSresult1[0]['totalconsumptionAmt'] + $bSresult4[0]['totalconsumptionAmt'] + $bSresult5[0]['totalconsumptionAmt'] + $bSresult7[0]['totalconsumptionAmt'];

					$baseCal3 = $bSresult1[0]['totalserviceAmt'] + $bSresult4[0]['totalserviceAmt'] + $bSresult5[0]['totalserviceAmt'] + $bSresult7[0]['totalserviceAmt'];

					$baseCal4 = $bSresult1[0]['totaltaxAmt'] + $bSresult4[0]['totaltaxAmt'] + $bSresult5[0]['totaltaxAmt'] + $bSresult7[0]['totaltaxAmt'];

					$baseCal5 = $totalrebate;

					$baseCal6 = $roomSoldonCompl;

					$baseCal7 = $compute1 + $compute2 + $compute3 + $compute4 + $totalEarlyCharges + $totalLateCharges + $totalCancelCharges;

				?>

				<div class="cs-height-50"></div>

				<h3 class="large nobold default-text-font-bold">Income Room Revenue</h3>

				<div class="">
					<table cellpadding="3" cellspacing="0">
						<tr>
							<th></th>
							<th class="alignct">Net Revenue<br>(Excl. of taxes)</th>
							<th class="alignct">Comission<br>Amount</th>
							<th class="alignct">Service Charge<br>Amount</th>
							<th class="alignct">VAT<br>Amount</th>
							<th class="alignct">Complimentary<br>(Incl. of taxes)</th>
							<th class="alignct">Rebate<br>Applied</th>
							<th class="alignct">Gross Revenue<br>(Incl. of taxes)</th>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Rooms Sold (at discounted rate)</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($roomSoldonDiscount,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalconsumptionAmt,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalserviceAmt,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totaltaxAmt,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($roomSoldonCompl,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalrebate,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($compute1,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">No Show</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($actualCal1,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult4[0]['totalconsumptionAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult4[0]['totalserviceAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult4[0]['totaltaxAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($compute2,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Day Use</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($actualCal2,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult5[0]['totalconsumptionAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult5[0]['totalserviceAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult5[0]['totaltaxAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($compute3,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Early Check-in Charges</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalEarlyCharges,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalEarlyCharges,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Late Check-out Charges</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalLateCharges,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalLateCharges,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Cancelled Revenue</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalCancelCharges,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($totalCancelCharges,2); ?></td>
						</tr>
						<tr>
							<td class="right-pull-10 left-pull-10 box-border-thick-right">Rooms Sold (at full rate)</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($roomSoldFullRate,2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult7[0]['totalconsumptionAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult7[0]['totalserviceAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($bSresult7[0]['totaltaxAmt'],2); ?></td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; 0.00</td>
							<td class="right-pull-10 left-pull-10 alignrt box-border-thick-right">&#8358; <?php echo number_format($compute4,2); ?></td>
						</tr>
						<tr>
							<td class="grey-theme right-pull-10 left-pull-10">Total Room Revenue</td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal1,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal2,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal3,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal4,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal6,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal5,2); ?></td>
							<td class="default-text-font-bold right-pull-10 left-pull-10 alignrt box-border-thick">&#8358; <?php echo number_format($baseCal7,2); ?></td>
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