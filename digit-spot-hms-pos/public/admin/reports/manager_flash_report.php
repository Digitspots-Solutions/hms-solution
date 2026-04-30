<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');

if(isset($_POST['startdate']) && !empty($_POST['startdate'])) { $startdate = $_POST['startdate']; }
else { $startdate = $server_get_date; }

if(isset($_POST['endate']) && !empty($_POST['endate'])) { $endate = $_POST['endate']; }
else { $endate = $server_get_date; }

$dateSplited = explode('-',$endateLog);
$startdateLog = $dateSplited[0].'-'.$dateSplited[1].'-01';

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
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
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
		<div class="nc-width-100">
			<div id="section-to-print">
			
				<?php
					
					$var_amt = array();

					$tbLOt = '<table cellpadding="3" cellspacing="0">';
					$tbLCt = '</table>';

					$startnumbr = 0; $keywords = ""; $rkeywords = "";

					if(isset($_POST['startdate']) && !empty($_POST['startdate'])) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$rkeywords .= " AND transaction_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$dykeywords = "AND checkin_date='{$_POST['startdate']}' AND checkout_date='{$_POST['endate']}' AND deletedata=0";
						$xrkeywords .= " AND bill_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$startdate = date('d-m-Y',strtotime($_POST['startdate'])); $sdt = $_POST['startdate'];
						$endate = date('d-m-Y',strtotime($_POST['endate'])); $edt = $_POST['endate'];
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$rkeywords .= " AND transaction_date BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$dykeywords = "AND checkin_date='{$server_get_date}' AND checkout_date='{$server_get_date}' AND deletedata=0";
						$xrkeywords .= " AND bill_date BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
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
					$xr_queryset = "deletedata=0".$xrkeywords;
					$xr_queryset2 = "t1.deletedata=0".$xrkeywords;
					$xr_queryset3 = "t1.deletedata=1".$xrkeywords;

					$hkr = 0;

					$query1 = "roomstatus=1 AND deletedata=0";
					$data1 = "COUNT(roomnumber) AS 'totalrooms'";
					$result1 = pfetch($data1,$tbL56,$query1);

					$query2 = "roomstatus=0 AND deletedata=0";
					$data2 = "COUNT(roomnumber) AS 'totalblockrooms'";
					$result2 = pfetch($data2,$tbL56,$query2);

					$totalrooms2 = $result1[0]['totalrooms'] - $result2[0]['totalblockrooms'];

					$query3 = "TRIM(booking_number) <> '' AND TRIM(room_type_id) <> '' AND TRIM(roomid) <> '' AND room_status IN('CheckedIn') AND room_type_id > 0 AND roomid > 0 AND ".$xr_queryset;
					$data3 = "COUNT(roomid) AS 'totaloccupied'";
					$result3 = pfetch($data3,$tbL134,$query3);

					$query3x = "status IN('CheckedOut') ".$dykeywords;
					$data3x = "COUNT(roomid) AS 'totaloccupied'";
					$result3x = pfetch($data3x,$tbL127,$query3x);

					//$totalrooms3 = $result1[0]['totalrooms'] - ($result3[0]['totaloccupied'] - $result3x[0]['totaloccupied']);
					$totalrooms3 = $result1[0]['totalrooms'] - $result3[0]['totaloccupied'];
					$totalrooms4 = $totalrooms3 - $result2[0]['totalblockrooms'];

					$query4 = "SELECT COUNT(roomid) AS 'totalcompl' FROM {$tbL134} WHERE charge_type='complimentary' AND room_status IN('CheckedIn') AND ".$xr_queryset;
					$result4 = mysqli_data_array('assoc',$query4);
					
					$totalrooms5 = $result3[0]['totaloccupied'] - ($result4[0]['totalcompl'] + $hkr);
					$totalrooms6 = $result3[0]['totaloccupied'] - $hkr;
					$totalrooms7 = $result3[0]['totaloccupied'] - $result4[0]['totalcompl'];

					$query5 = "SELECT SUM(adult) AS 'totaladults', SUM(child) AS 'totalchilds' FROM {$tbL127} t1 LEFT JOIN {$tbL134} t2 ON t1.roomid=t2.roomid WHERE room_status IN('CheckedIn') AND ".$xr_queryset2;
					$result5 = mysqli_data_array('assoc',$query5);

					$totalinperson = $result5[0]['totaladults'] + $result5[0]['totalchilds'];

					$query6 = "SELECT COUNT(roomid) AS 'totalindrooms' FROM {$tbL134} WHERE charge_type='individual' AND room_status IN('CheckedIn') AND ".$xr_queryset;
					$result6 = mysqli_data_array('assoc',$query6);

					$query7 = "SELECT COUNT(roomid) AS 'totalcorprooms' FROM {$tbL134} WHERE charge_type='corporate' AND room_status IN('CheckedIn') AND ".$xr_queryset;
					$result7 = mysqli_data_array('assoc',$query7);

					$query8 = "SELECT COUNT(roomid) AS 'totalagentrooms' FROM {$tbL134} WHERE charge_type='agent' AND room_status IN('CheckedIn') AND ".$xr_queryset;
					$result8 = mysqli_data_array('assoc',$query8);

					$percentage_rocc = ($result3[0]['totaloccupied'] / $result1[0]['totalrooms']) * 100;
					$percentage_compl = ($result4[0]['totalcompl'] / $result1[0]['totalrooms']) * 100;
					$percentage_hkr = @ ($hkr / $result1[0]['totalrooms']) * 100;
					$percentage_oor = ($result2[0]['totalblockrooms'] / $result1[0]['totalrooms']) * 100;

					$base_perc1 = $percentage_rocc - ($percentage_compl + $percentage_hkr);
					$base_perc2 = $percentage_rocc - ($percentage_compl + $percentage_hkr + $percentage_oor);
					$base_perc3 = $percentage_rocc - $percentage_compl;
					$base_perc4 = $percentage_rocc - $percentage_hkr;
					$base_perc5 = $percentage_rocc - ($percentage_compl + $percentage_oor);
					$base_perc6 = $percentage_rocc - ($percentage_hkr + $percentage_oor);
					$base_perc7 = $percentage_rocc - $percentage_oor;

					$query9 = "SELECT COUNT(roomid) AS 'totalEchkin' FROM {$tbL134} WHERE invoice_number='EARLYCHECKIN' AND wkf=2 AND ".$xr_queryset;
					$result9 = mysqli_data_array('assoc',$query9);

					$query10 = "SELECT COUNT(roomid) AS 'totalLchkout' FROM {$tbL134} WHERE invoice_number='LATECHECKOUT' AND wkf=1 AND ".$xr_queryset;
					$result10 = mysqli_data_array('assoc',$query10);

					$query11 = "SELECT COUNT(roomid) AS 'totaldayUse' FROM {$tbL127} WHERE checkin_date = checkout_date AND ".$queryset;
					$result11 = mysqli_data_array('assoc',$query11);

					$query12 = "SELECT COUNT(roomid) AS 'totalNoshow' FROM {$tbL127} WHERE status IN('No Show') AND ".$queryset;
					$result12 = mysqli_data_array('assoc',$query12);

					/*$query13 = "SELECT SUM(adult) AS 'totalNadults', SUM(child) AS 'totalNchilds' FROM {$tbL127} WHERE status IN('No Show') AND ".$queryset; $result13 = mysqli_data_array('assoc',$query13);
					$totalNoshowPerson = $result13['totalNadults'] + $result13['totalNchilds'];*/

					$query14 = "SELECT COUNT(roomid) AS 'totalCancelled' FROM {$tbL127} WHERE reservation='Reserving' AND status IN('Cancelled') AND cancel_date='{$sdt}'";
					$result14 = mysqli_data_array('assoc',$query14);

					$query15 = "SELECT COUNT(roomid) AS 'totalDirty' FROM {$tbL127} WHERE housekeeping_stateid=2 AND ".$queryset;
					$result15 = mysqli_data_array('assoc',$query15);

					$query16 = "SELECT COUNT(roomid) AS 'totalTouchup' FROM {$tbL127} WHERE housekeeping_stateid=6 AND ".$queryset;
					$result16 = mysqli_data_array('assoc',$query16);

					$query17 = "SELECT COUNT(roomid) AS 'totalClean' FROM {$tbL127} WHERE housekeeping_stateid=1 AND ".$queryset;
					$result17 = mysqli_data_array('assoc',$query17);

					$query18 = "SELECT COUNT(roomid) AS 'totalRepair' FROM {$tbL127} WHERE housekeeping_stateid=5 AND ".$queryset;
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

					$startdate = $sdt;
					$endate = $edt;

					$irr_net_revenue_1 = 0; $irr_commission_1 = 0; $irr_service_charge_1 = 0;
					$irr_taxes_1 = 0; $irr_taxes_1x = 0; $irr_complimentary_1 = 0; $irr_rebate_1 = 0;
					$irr_gross_revenue_1 = 0; $irr_accumulated_revenue_1 = 0;

					$irr_net_revenue_2 = 0; $irr_commission_2 = 0; $irr_service_charge_2 = 0;
					$irr_taxes_2 = 0; $irr_taxes_2x = 0; $irr_complimentary_2 = 0; $irr_rebate_2 = 0;
					$irr_gross_revenue_2 = 0; $irr_accumulated_revenue_2 = 0;

					$irr_net_revenue_3 = 0; $irr_commission_3 = 0; $irr_service_charge_3 = 0;
					$irr_taxes_3 = 0; $irr_taxes_3x = 0; $irr_complimentary_3 = 0; $irr_rebate_3 = 0;
					$irr_gross_revenue_3 = 0; $irr_accumulated_revenue_3 = 0;

					$irr_net_revenue_4 = 0; $irr_commission_4 = 0; $irr_service_charge_4 = 0;
					$irr_taxes_4 = 0; $irr_taxes_4x = 0; $irr_complimentary_4 = 0; $irr_rebate_4 = 0;
					$irr_gross_revenue_4 = 0; $irr_accumulated_revenue_4 = 0;

					$irr_net_revenue_5 = 0; $irr_commission_5 = 0; $irr_service_charge_5 = 0;
					$irr_taxes_5 = 0; $irr_taxes_5x = 0;$irr_complimentary_5 = 0; $irr_rebate_5 = 0;
					$irr_gross_revenue_5 = 0; $irr_accumulated_revenue_5 = 0;

					$irr_net_revenue_6 = 0; $irr_commission_6 = 0; $irr_service_charge_6 = 0;
					$irr_taxes_6 = 0; $irr_taxes_6x = 0; $irr_taxes_6x = 0; $irr_complimentary_6 = 0; $irr_rebate_6 = 0;
					$irr_gross_revenue_6 = 0; $irr_accumulated_revenue_6 = 0;

					$irr_net_revenue_7 = 0; $irr_commission_7 = 0; $irr_service_charge_7 = 0;
					$irr_taxes_7 = 0; $irr_taxes_7x = 0; $irr_complimentary_7 = 0; $irr_rebate_7 = 0;
					$irr_gross_revenue_7 = 0; $irr_accumulated_revenue_7 = 0;

					#---start

					$sql_dayuse = "SELECT booking_number FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND deletedata=0 AND datelogged='{$endate}'"; $dataset_dayuse = wgetSQL($sql_dayuse);

					if(is_array($dataset_dayuse) && count($dataset_dayuse) > 0) {
						$dayuse_rooms = ""; $rooms_string = "";
						foreach($dataset_dayuse as $key => $val) { $rooms_string .= "'".$val['booking_number']."',"; }
						$dayuse_rooms = substr_replace($rooms_string,'',-1,1);
						$dayuse_uquery = " AND booking_number IN({$dayuse_rooms})";
						$dayuse_nquery = " AND booking_number NOT IN({$dayuse_rooms})";
					} else {
						$dayuse_rooms = "";
						$dayuse_uquery = "";
						$dayuse_nquery = "";
					}

					//echo $dayuse_uquery;
					//echo $dayuse_nquery;

					#end of day-use rooms pack

					$sql_4 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(6,2)) > CAST(room_amount as DECIMAL(6,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_4 = wgetSQL($sql_4);

					$irr_taxes_1 = $dataset_4[0]['vat'];
					$irr_taxes_1x = $dataset_4[0]['consumption'];
					$irr_service_charge_1 = $dataset_4[0]['scharge'];
					$irr_net_revenue_1 = $dataset_4[0]['total'] - $dataset_4[0]['discounts'];

					$irr_gross_revenue_1 = $irr_net_revenue_1 + $irr_taxes_1 + $irr_taxes_1x + $irr_service_charge_1;

					$sql_5 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(6,2)) > CAST(room_amount as DECIMAL(6,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_5 = wgetSQL($sql_5);

					$irr_complimentary_1 = ($dataset_5[0]['total'] - $dataset_5[0]['discounts']) + $dataset_5[0]['vat'] + $dataset_5[0]['consumption'] + $dataset_5[0]['scharge'];

					/*$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount > t1.room_amount OR t1.discount_amount > 0) AND t1.deletedata=0)";
					$dataset_6 = wgetSQL($sql_6);*/
					$irr_rebate_1 = 0;

					/*$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND datelogged='{$endate}' AND rebate_type IN('Booking')"; $dataset_6 = wgetSQL($sql_6);
					$irr_rebate_1 = $dataset_6[0]['total'];*/

					$sql_7 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND (CAST(actual_room_amount as DECIMAL(6,2)) > CAST(room_amount as DECIMAL(6,2)) OR discount_amount > 0) AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_7 = wgetSQL($sql_7);

					$irr_accumulated_revenue_1 = $dataset_7[0]['total'] - $dataset_7[0]['discounts'];

					#---end


					/*$sql_8 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=1 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf IN(0,7) AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
					$dataset_8 = wgetSQL($sql_8);

					$irr_taxes_2 = $dataset_8[0]['vat'];
					$irr_taxes_2x = $dataset_8[0]['consumption'];
					$irr_service_charge_2 = $dataset_8[0]['scharge'];
					$irr_net_revenue_2 = $dataset_8[0]['total']  - $dataset_8[0]['discounts'];

					$irr_gross_revenue_2 = $irr_net_revenue_2 + $irr_taxes_2 + $irr_taxes_2x + $irr_service_charge_2;

					$sql_9 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
					$dataset_9 = wgetSQL($sql_9);

					$irr_accumulated_revenue_2 = ($dataset_9[0]['total'] - $dataset_9[0]['discounts']) + $dataset_9[0]['vat'] + $dataset_9[0]['consumption'] + $dataset_9[0]['scharge'];*/

					$irr_taxes_2 = 0;
					$irr_taxes_2x = 0;
					$irr_service_charge_2 = 0;
					$irr_net_revenue_2 = 0;

					$irr_gross_revenue_2 = $irr_net_revenue_2 + $irr_taxes_2 + $irr_taxes_2x + $irr_service_charge_2;
					$irr_accumulated_revenue_2 = 0;

					#---end

					if(!empty($dayuse_uquery)) {
						$sql_10 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7)".$dayuse_uquery; $dataset_10 = wgetSQL($sql_10);

						$irr_taxes_3 = $dataset_10[0]['vat'];
						$irr_taxes_3x = $dataset_10[0]['consumption'];
						$irr_service_charge_3 = $dataset_10[0]['scharge'];
						$irr_net_revenue_3 = $dataset_10[0]['total'] - $dataset_10[0]['discounts'];

						$irr_gross_revenue_3 = $irr_net_revenue_3 + $irr_taxes_3 + $irr_taxes_3x + $irr_service_charge_3;

						$sql_11 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7)".$dayuse_uquery;
						$dataset_11 = wgetSQL($sql_11);

						$irr_complimentary_3 = ($dataset_11[0]['total'] - $dataset_11[0]['discounts']) + $dataset_11[0]['vat'] + $dataset_11[0]['consumption'] + $dataset_11[0]['scharge'];
					} else {
						$irr_taxes_3 = 0;
						$irr_taxes_3x = 0;
						$irr_service_charge_3 = 0;
						$irr_net_revenue_3 = 0;

						$irr_gross_revenue_3 = $irr_net_revenue_3 + $irr_taxes_3 + $irr_taxes_3x + $irr_service_charge_3;
						$irr_complimentary_3 = 0;
					}

					/*$sql_12 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL127} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.checkin_date='{$endate}' AND t1.checkout_date='{$endate}' AND t1.status='CheckedOut')"; $dataset_12 = wgetSQL($sql_12);*/
					$irr_rebate_3 = 0;

					$sql_13a = "SELECT * FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";
					$dataset_13a = wgetSQL($sql_13a);

					if(is_array($dataset_13a) && count($dataset_13a) > 0) {
						
						$xtotal_room_amount = 0; $xtotal_discount_amount = 0;
						$sql_13 = "";
						
						foreach($dataset_13a as $kya => $vla) {
						
							$sql_13 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$vla['checkout_date']}' AND wkf IN(0,7) AND booking_number='{$vla['booking_number']}'";
							$dataset_13 = wgetSQL($sql_13);

							$xtotal_room_amount = $xtotal_room_amount + $dataset_13[0]['total'];
							$xtotal_discount_amount = $xtotal_discount_amount + $dataset_13[0]['discounts'];
						}

						//$irr_accumulated_revenue_3 = ($dataset_13[0]['total'] - $dataset_13[0]['discounts']) + $dataset_13[0]['vat'] + $dataset_13[0]['consumption'] + $dataset_13[0]['scharge'];
						$irr_accumulated_revenue_3 = $xtotal_room_amount - $xtotal_discount_amount;
					} else {
						$irr_accumulated_revenue_3 = 0;
					}

					#---end

					$sql_14 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'";
					$dataset_14 = wgetSQL($sql_14);

					$irr_taxes_4 = ($gh_get_vat / 100) * $dataset_14[0]['total'];
					$irr_taxes_4x = ($gh_get_consumption_tax / 100) * $dataset_14[0]['total'];
					$irr_service_charge_4 = ($gh_get_service_charge / 100) * $dataset_14[0]['total'];
					$irr_net_revenue_4 = $dataset_14[0]['total'] - ($irr_taxes_4 + $irr_taxes_4x + $irr_service_charge_4);

					$irr_gross_revenue_4 = $irr_net_revenue_4 + $irr_taxes_4 + $irr_taxes_4x + $irr_service_charge_4;

					$sql_15 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'"; $dataset_15 = wgetSQL($sql_15);

					$irr_taxes_a4 = ($gh_get_vat / 100) * $dataset_15[0]['total'];
					$irr_taxes_a4x = ($gh_get_consumption_tax / 100) * $dataset_15[0]['total'];
					$irr_service_charge_a4 = ($gh_get_service_charge / 100) * $dataset_15[0]['total'];
					$irr_accumulated_revenue_4 = $dataset_15[0]['total'] - ($irr_taxes_a4 + $irr_taxes_a4x + $irr_service_charge_a4);

					#---end

					$sql_16 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
					$dataset_16 = wgetSQL($sql_16);

					$irr_taxes_5 = ($gh_get_vat / 100) * $dataset_16[0]['total'];
					$irr_taxes_5x = ($gh_get_consumption_tax / 100) * $dataset_16[0]['total'];
					$irr_service_charge_5 = ($gh_get_service_charge / 100) * $dataset_16[0]['total'];
					$irr_net_revenue_5 = $dataset_16[0]['total'] - ($irr_taxes_5 + $irr_taxes_5x + $irr_service_charge_5);

					$irr_gross_revenue_5 = $irr_net_revenue_5 + $irr_taxes_5 + $irr_taxes_5x + $irr_service_charge_5;

					$sql_17 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
					$dataset_17 = wgetSQL($sql_17);

					$irr_taxes_a5 = ($gh_get_vat / 100) * $dataset_17[0]['total'];
					$irr_taxes_a5x = ($gh_get_consumption_tax / 100) * $dataset_17[0]['total'];
					$irr_service_charge_a5 = ($gh_get_service_charge / 100) * $dataset_17[0]['total'];
					$irr_accumulated_revenue_5 = $dataset_17[0]['total'] - ($irr_taxes_a5 + $irr_taxes_a5x + $irr_service_charge_a5);

					#---end

					$sql_18 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$endate}' AND '{$endate}'";
					$dataset_18 = wgetSQL($sql_18);

					$irr_net_revenue_6 = $dataset_18[0]['total'];
					$irr_gross_revenue_6 = $dataset_18[0]['total'];

					$sql_19 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$startdate}' AND '{$endate}'";
					$dataset_19 = wgetSQL($sql_19);
					
					$irr_accumulated_revenue_6 = $dataset_19[0]['total'];

					#---end

					/*$sql_20 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND (actual_room_amount = room_amount OR discount_amount = 0) AND room_status IN('CheckedIn')"; $dataset_20 = wgetSQL($sql_20);*/

					$sql_20 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(6,2)) >= CAST(actual_room_amount as DECIMAL(6,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_20 = wgetSQL($sql_20);

					$irr_taxes_7 = $dataset_20[0]['vat'];
					$irr_taxes_7x = $dataset_20[0]['consumption'];
					$irr_service_charge_7 = $dataset_20[0]['scharge'];
					$irr_net_revenue_7 = $dataset_20[0]['total'];

					$irr_gross_revenue_7 = $irr_net_revenue_7 + $irr_taxes_7 + $irr_taxes_7x + $irr_service_charge_7;

					$sql_21 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(6,2)) >= CAST(actual_room_amount as DECIMAL(6,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_21 = wgetSQL($sql_21);

					$irr_complimentary_7 = $dataset_21[0]['total'] + $dataset_21[0]['vat'] + $dataset_21[0]['consumption'] + $dataset_21[0]['scharge'];

					/*$sql_22 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND transaction_date='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount = t1.room_amount OR t1.discount_amount = 0) AND t1.room_status='CheckedIn' AND t1.deletedata=0)";*/

					$sql_22 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND rebate_type IN('Booking') AND deletedata=0 AND transaction_date='{$endate}'";
					$dataset_22 = wgetSQL($sql_22);
					$irr_rebate_7 = $dataset_22[0]['total'];
					$irr_gross_revenue_7 = $irr_gross_revenue_7 - $irr_rebate_7;

					$sql_22x = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND rebate_type IN('Booking') AND deletedata=0 AND transaction_date BETWEEN '{$startdate}' AND '{$endate}'";
					$dataset_22x = wgetSQL($sql_22x);
					$irr_rebate_7x = $dataset_22x[0]['total'];
					
					$sql_23 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf IN(0,7) AND CAST(room_amount as DECIMAL(6,2)) >= CAST(actual_room_amount as DECIMAL(6,2)) AND discount_amount = 0 AND room_status IN('CheckedIn')".$dayuse_nquery;
					$dataset_23 = wgetSQL($sql_23);

					$irr_accumulated_revenue_7 = $dataset_23[0]['total'] - $irr_rebate_7x;

					#---end


					#---base total

					$irr_g_net_revenue = $irr_net_revenue_1 + $irr_net_revenue_2 + $irr_net_revenue_3 + $irr_net_revenue_4 + $irr_net_revenue_5 + $irr_net_revenue_6 + $irr_net_revenue_7;

					$irr_g_commission = $irr_taxes_1x + $irr_taxes_2x + $irr_taxes_3x + $irr_taxes_4x + $irr_taxes_5x + $irr_taxes_6x + $irr_taxes_7x;

					$irr_g_service_charge = $irr_service_charge_1 + $irr_service_charge_2 + $irr_service_charge_3 + $irr_service_charge_4 + $irr_service_charge_5 + $irr_service_charge_6 + $irr_service_charge_7;

					$irr_g_taxes = $irr_taxes_1 + $irr_taxes_2 + $irr_taxes_3 + $irr_taxes_4 + $irr_taxes_5 + $irr_taxes_6 + $irr_taxes_7;

					$irr_g_complimentary = $irr_complimentary_1 + $irr_complimentary_2 + $irr_complimentary_3 + $irr_complimentary_4 + $irr_complimentary_5 + $irr_complimentary_6 + $irr_complimentary_7;

					$irr_g_rebate = $irr_rebate_1 + $irr_rebate_2 + $irr_rebate_3 + $irr_rebate_4 + $irr_rebate_5 + $irr_rebate_6 + $irr_rebate_7;

					$irr_g_gross_revenue = $irr_gross_revenue_1 + $irr_gross_revenue_2 + $irr_gross_revenue_3 + $irr_gross_revenue_4 + $irr_gross_revenue_5 + $irr_gross_revenue_6 + $irr_gross_revenue_7;

					$irr_g_accumulated_revenue = $irr_accumulated_revenue_1 + $irr_accumulated_revenue_2 + $irr_accumulated_revenue_3 + $irr_accumulated_revenue_4 + $irr_accumulated_revenue_5 + $irr_accumulated_revenue_6 + $irr_accumulated_revenue_7;

				?>

				<div class="cs-height-50"></div>

				<h3 class="large nobold default-text-font-bold">Income Room Revenue</h3>

				<div class="">
					<table cellpadding="3" cellspacing="0">
						<tr class="dark-grey-theme">
							<td class="alignct"></td>
							<td class="alignct default-text-font-bold">Net Revenue (Excl. of taxes)</td>
							<td class="alignct default-text-font-bold">Service Charge</td>
							<td class="alignct default-text-font-bold">VAT</td>
							<td class="alignct default-text-font-bold">Consumption</td>
							<td class="alignct default-text-font-bold">Complimentary (Incl. of taxes)</td>
							<td class="alignct default-text-font-bold">Rebate</td>
							<td class="alignct default-text-font-bold">Gross Revenue (Incl. of taxes)</td>
							<td class="alignct default-text-font-bold">To-Date Revenue</td>
						</tr>
						<tr>
							<td class="alignlt">Rooms Sold (at discounted rate)</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_1x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_1,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_1,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">No Show</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_2x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_2,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_2,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Day Use</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_3x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_3,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_3,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Early Check-in Charges</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_4x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_4,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_4,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Late Check-out Charges</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_5x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_5,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_5,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Cancelled Revenue</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_6x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_6,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_6,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Rooms Sold (at full rate)</td>
							<td class="alignrt"><?php echo number_format($irr_net_revenue_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_service_charge_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_taxes_7x,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_complimentary_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_rebate_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_gross_revenue_7,2); ?></td>
							<td class="alignrt"><?php echo number_format($irr_accumulated_revenue_7,2); ?></td>
						</tr>
						<tr class="grey-theme">
							<td class="alignlt default-text-font-bold">Total Room Revenue</td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_net_revenue,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_service_charge,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_taxes,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_commission,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_complimentary,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_rebate,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_gross_revenue,2); ?></td>
							<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($irr_g_accumulated_revenue,2); ?></td>
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