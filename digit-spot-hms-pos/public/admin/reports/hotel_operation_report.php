<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

if(isset($_POST['orderdate']) && !empty($_POST['orderdate'])) { $endate = $_POST['orderdate']; }
else { $endate = $server_get_date; }

$dateSplited = explode('-',$endate);
$startdate = $dateSplited[0].'-'.$dateSplited[1].'-01';

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;


$pos_tax = 0;

//$pos_stores = select_dt_fetch('iscounter','Yes',$tbL14,'id','posname');
//$shift_list = mt_select_fetch('status','Active',$tbL20,'id','shiftname,startimelabel,endtimelabel','','0,0,0');

//$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Hotel Operation Report</b>: Here you can see the hotel operations
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
			<input type="hidden" name="reporting" value="post">
			<span class="ln-display-box float-left right-push-10">
				<select name="period" id="period" onchange="dateStat()" required="required">
					<option value="" selected="selected">Select Period</option>
					<option value="Today">Today</option>
					<option value="Yesterday">Yesterday</option>
					<option value="Custom Date">Custom Date</option>
				</select>
			</span>
			<span class="ln-display-box float-left right-push-10">
				<div id="custom-date" class="noshow">
					<div class="ln-display-box float-left right-push-10">
						<input type="date" name="startdate" id="startdate" title="From date">
					</div>
					<div class="ln-display-box float-left right-push-10">
						<input type="date" name="endate" id="endate" title="To date">
					</div>
				</div>
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
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
						$get_posstores = mysqli_data_fetch($tbL14,'id,posname',$pst_query,'array');

						$startdate = ""; $endate = "";
						$custom_date_start = ""; $custom_date_end = "";

						switch ($_POST['period']) {
							case 'Today':
								$date_query = "(".date("d/m/Y",strtotime($server_get_date)).")";
								$date_aQuery = " AND datelogged BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
								$startdate = $server_get_date; $endate = $server_get_date;
								$custom_date_start = $server_get_date; $custom_date_end = $server_get_date;
								break;

							case 'Yesterday':
								$get_past_date = date("Y-m-d",strtotime("-1 day"));
								$date_query = "(".date("d/m/Y",strtotime("-1 day")).")";
								$date_aQuery = " AND datelogged BETWEEN '".$get_past_date."' AND '".$get_past_date."'";
								$startdate = $get_past_date; $endate = $get_past_date;
								$custom_date_start = $get_past_date; $custom_date_end = $get_past_date;
								break;
							
							case 'Custom Date':
								$date_query = "(Between ".date("d/m/Y",strtotime($_POST['startdate']))." And ".date("d/m/Y",strtotime($_POST['endate'])).")";;
								$date_aQuery = " AND datelogged BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";
								$startdate = $_POST['startdate']; $endate = $_POST['endate'];
								$custom_date_start = $_POST['startdate']; $custom_date_end = $_POST['endate'];
								break;

							default:
								$query = "";
								$date_query = "";
								break;
						}
						
						?>
							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Hotel Operation Report (For <?php echo $endate; ?>)</h3>
								<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
							</div>

						<?php
							
							$roomsCheckedin = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2)";
							$gt_roomCheckedin = wgetSQL($roomsCheckedin);

							$sql_30 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=2";
							$dataset_30 = wgetSQL($sql_30);

							$sql_31 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=1";
							$dataset_31 = wgetSQL($sql_31);

							$sql_32 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='individual')";
							$dataset_32 = wgetSQL($sql_32);

							$sql_33 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2) AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_33 = wgetSQL($sql_33);

							$sql_34 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='individual' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_34 = wgetSQL($sql_34);

							$individual_early_checkin_total = $dataset_30[0]['total'];
							$individual_late_checkout_total = $dataset_31[0]['total'];
							$individual_dayuse_total = $dataset_32[0]['total'];
							$individual_fullrate_total = $dataset_33[0]['total'];
							$individual_discountrate_total = $dataset_34[0]['total'];
							$individual_g_total = $individual_early_checkin_total + $individual_late_checkout_total + $individual_dayuse_total + $individual_fullrate_total + $individual_discountrate_total;


							$sql_35 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=2";
							$dataset_35 = wgetSQL($sql_35);

							$sql_36 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=1";
							$dataset_36 = wgetSQL($sql_36);

							$sql_37 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='corporate')";
							$dataset_37 = wgetSQL($sql_37);

							$sql_38 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2) AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_38 = wgetSQL($sql_38);

							$sql_39 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='corporate' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_39 = wgetSQL($sql_39);

							$corporate_early_checkin_total = $dataset_35[0]['total'];
							$corporate_late_checkout_total = $dataset_36[0]['total'];
							$corporate_dayuse_total = $dataset_37[0]['total'];
							$corporate_fullrate_total = $dataset_38[0]['total'];
							$corporate_discountrate_total = $dataset_39[0]['total'];
							$corporate_g_total = $corporate_early_checkin_total + $corporate_late_checkout_total + $corporate_dayuse_total + $corporate_fullrate_total + $corporate_discountrate_total;

							$agent_early_checkin_total = 0; $agent_late_checkout_total = 0;
							$agent_dayuse_total = 0; $agent_fullrate_total = 0;
							$agent_discountrate_total = 0; $agent_g_total = 0;


							$sql_40 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=2";
							$dataset_40 = wgetSQL($sql_40);

							$sql_41 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=1";
							$dataset_41 = wgetSQL($sql_41);

							$sql_42 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='complimentary')";
							$dataset_42 = wgetSQL($sql_42);

							$sql_43 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2) AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_43 = wgetSQL($sql_43);

							$sql_44 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='complimentary' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_44 = wgetSQL($sql_44);

							$complimentary_early_checkin_total = $dataset_40[0]['total'];
							$complimentary_late_checkout_total = $dataset_41[0]['total'];
							$complimentary_dayuse_total = $dataset_42[0]['total'];
							$complimentary_fullrate_total = $dataset_43[0]['total'];
							$complimentary_discountrate_total = $dataset_44[0]['total'];
							$complimentary_g_total = $complimentary_early_checkin_total + $complimentary_late_checkout_total + $complimentary_dayuse_total + $complimentary_fullrate_total + $complimentary_discountrate_total;


							$sql_45 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=2"; $dataset_45 = wgetSQL($sql_45);

							$sql_46 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf=1"; $dataset_46 = wgetSQL($sql_46);

							$sql_47 = "SELECT COUNT(roomid) AS total FROM {$tbL127} WHERE checkin_date=checkout_date AND status IN('CheckedOut') AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT booking_type FROM {$tbL130} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.booking_type='online')";
							$dataset_47 = wgetSQL($sql_47);

							$sql_48 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND wkf NOT IN(1,2) AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_48 = wgetSQL($sql_48);

							$sql_49 = "SELECT COUNT(roomid) AS total FROM {$tbL134} WHERE charge_type='online' AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND room_status IN('CheckedIn') AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_49 = wgetSQL($sql_49);

							$online_early_checkin_total = $dataset_45[0]['total'];
							$online_late_checkout_total = $dataset_46[0]['total'];
							$online_dayuse_total = $dataset_47[0]['total'];
							$online_fullrate_total = $dataset_48[0]['total'];
							$online_discountrate_total = $dataset_49[0]['total'];
							$online_g_total = $online_early_checkin_total + $online_late_checkout_total + $online_dayuse_total + $online_fullrate_total + $online_discountrate_total;

							$early_checkin_total = $individual_early_checkin_total + $corporate_early_checkin_total + $agent_early_checkin_total + $complimentary_early_checkin_total + $online_early_checkin_total;

							$late_checkout_total = $individual_late_checkout_total + $corporate_late_checkout_total + $agent_late_checkout_total + $agent_late_checkout_total + $complimentary_late_checkout_total + $online_late_checkout_total;

							$dayuse_total = $individual_dayuse_total + $corporate_dayuse_total + $agent_dayuse_total + $complimentary_dayuse_total + $online_dayuse_total;
							
							$fullrate_total = $individual_fullrate_total + $corporate_fullrate_total + $agent_fullrate_total + $complimentary_fullrate_total + $online_fullrate_total;

							$discountrate_total = $individual_discountrate_total + $corporate_discountrate_total + $agent_discountrate_total + $complimentary_discountrate_total + $online_discountrate_total;
							
							$g_total = $individual_g_total + $corporate_g_total + $agent_g_total + $complimentary_g_total + $online_g_total;
						?>

						<div class="bottom-push-30" align="center">
							<div class="cs-width-700">
								<h3 class="large nobold alignlt bottom-pull-7">Total Occupied Rooms: <?php echo $gt_roomCheckedin[0]['total']; ?></h3>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Booking Type</td>
										<td class="alignct default-text-font-bold">Early Check-in</td>
										<td class="alignct default-text-font-bold">Late Check-out</td>
										<td class="alignct default-text-font-bold">Day Use</td>
										<td class="alignct default-text-font-bold">Full Rate</td>
										<td class="alignct default-text-font-bold">Discount Rate</td>
										<td class="alignct default-text-font-bold">Total</td>
									</tr>
									<tr>
										<td class="alignlt">Individual</td>
										<td class="alignrt"><?php echo $individual_early_checkin_total; ?></td>
										<td class="alignrt"><?php echo $individual_late_checkout_total; ?></td>
										<td class="alignrt"><?php echo $individual_dayuse_total; ?></td>
										<td class="alignrt"><?php echo $individual_fullrate_total; ?></td>
										<td class="alignrt"><?php echo $individual_discountrate_total; ?></td>
										<td class="alignrt"><?php echo $individual_g_total; ?></td>
									</tr>
									<tr>
										<td class="alignlt">Corporate</td>
										<td class="alignrt"><?php echo $corporate_early_checkin_total; ?></td>
										<td class="alignrt"><?php echo $corporate_late_checkout_total; ?></td>
										<td class="alignrt"><?php echo $corporate_dayuse_total; ?></td>
										<td class="alignrt"><?php echo $corporate_fullrate_total; ?></td>
										<td class="alignrt"><?php echo $corporate_discountrate_total; ?></td>
										<td class="alignrt"><?php echo $corporate_g_total; ?></td>
									</tr>
									<tr>
										<td class="alignlt">Agent</td>
										<td class="alignrt"><?php echo $agent_early_checkin_total; ?></td>
										<td class="alignrt"><?php echo $agent_late_checkout_total; ?></td>
										<td class="alignrt"><?php echo $agent_dayuse_total; ?></td>
										<td class="alignrt"><?php echo $agent_fullrate_total; ?></td>
										<td class="alignrt"><?php echo $agent_discountrate_total; ?></td>
										<td class="alignrt"><?php echo $agent_g_total; ?></td>
									</tr>
									<tr>
										<td class="alignlt">Complimentary</td>
										<td class="alignrt"><?php echo $complimentary_early_checkin_total; ?></td>
										<td class="alignrt"><?php echo $complimentary_late_checkout_total; ?></td>
										<td class="alignrt"><?php echo $complimentary_dayuse_total; ?></td>
										<td class="alignrt"><?php echo $complimentary_fullrate_total; ?></td>
										<td class="alignrt"><?php echo $complimentary_discountrate_total; ?></td>
										<td class="alignrt"><?php echo $complimentary_g_total; ?></td>
									</tr>
									<tr>
										<td class="alignlt">Online</td>
										<td class="alignrt"><?php echo $online_early_checkin_total; ?></td>
										<td class="alignrt"><?php echo $online_late_checkout_total; ?></td>
										<td class="alignrt"><?php echo $online_dayuse_total; ?></td>
										<td class="alignrt"><?php echo $online_fullrate_total; ?></td>
										<td class="alignrt"><?php echo $online_discountrate_total; ?></td>
										<td class="alignrt"><?php echo $online_g_total; ?></td>
									</tr>
									<tr>
										<td class="alignlt default-text-font-bold">Total</td>
										<td class="alignrt default-text-font-bold"><?php echo $early_checkin_total; ?></td>
										<td class="alignrt default-text-font-bold"><?php echo $late_checkout_total; ?></td>
										<td class="alignrt default-text-font-bold"><?php echo $dayuse_total; ?></td>
										<td class="alignrt default-text-font-bold"><?php echo $fullrate_total; ?></td>
										<td class="alignrt default-text-font-bold"><?php echo $discountrate_total; ?></td>
										<td class="alignrt default-text-font-bold"><?php echo $g_total; ?></td>
									</tr>
								</table>
							</div>
						</div>

						<?php
								
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

							$sql_4 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_4 = wgetSQL($sql_4);

							$irr_taxes_1 = $dataset_4[0]['vat'];
							$irr_taxes_1x = $dataset_4[0]['consumption'];
							$irr_service_charge_1 = $dataset_4[0]['scharge'];
							$irr_net_revenue_1 = $dataset_4[0]['total'] - $dataset_4[0]['discounts'];

							$irr_gross_revenue_1 = $irr_net_revenue_1 + $irr_taxes_1 + $irr_taxes_1x + $irr_service_charge_1;

							$sql_5 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_5 = wgetSQL($sql_5);

							$irr_complimentary_1 = ($dataset_5[0]['total'] - $dataset_5[0]['discounts']) + $dataset_5[0]['vat'] + $dataset_5[0]['consumption'] + $dataset_5[0]['scharge'];

							$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount > t1.room_amount OR t1.discount_amount > 0) AND t1.deletedata=0)"; $dataset_6 = wgetSQL($sql_6);
							$irr_rebate_1 = $dataset_6[0]['total'];

							/*$sql_6 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed','Approved') AND deletedata=0 AND datelogged='{$endate}' AND rebate_type IN('Booking')"; $dataset_6 = wgetSQL($sql_6);
							$irr_rebate_1 = $dataset_6[0]['total'];*/

							$sql_7 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=0 AND (actual_room_amount > room_amount OR discount_amount > 0)"; $dataset_7 = wgetSQL($sql_7);

							$irr_accumulated_revenue_1 = $dataset_7[0]['vat'] + $dataset_7[0]['consumption'] + $dataset_7[0]['scharge'] + ($dataset_7[0]['total'] - $dataset_7[0]['discounts']);

							#---end


							$sql_8 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=1 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=0 AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
							$dataset_8 = wgetSQL($sql_8);

							$irr_taxes_2 = $dataset_8[0]['vat'];
							$irr_taxes_2x = $dataset_8[0]['consumption'];
							$irr_service_charge_2 = $dataset_8[0]['scharge'];
							$irr_net_revenue_2 = $dataset_8[0]['total']  - $dataset_8[0]['discounts'];

							$irr_gross_revenue_2 = $irr_net_revenue_2 + $irr_taxes_2 + $irr_taxes_2x + $irr_service_charge_2;

							$sql_9 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=0 AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.status='No Show')";
							$dataset_9 = wgetSQL($sql_9);

							$irr_accumulated_revenue_2 = $dataset_9[0]['vat'] + $dataset_9[0]['consumption'] + $dataset_9[0]['scharge'] + ($dataset_9[0]['total'] - $dataset_9[0]['discounts']);

							#---end


							$sql_10 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=0 AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.checkin_date=t1.checkout_date AND t1.status='CheckedOut')";
							$dataset_10 = wgetSQL($sql_10);

							$irr_taxes_3 = $dataset_10[0]['vat'];
							$irr_taxes_3x = $dataset_10[0]['consumption'];
							$irr_service_charge_3 = $dataset_10[0]['scharge'];
							$irr_net_revenue_3 = $dataset_10[0]['total'] - $dataset_10[0]['discounts'];

							$irr_gross_revenue_3 = $irr_net_revenue_3 + $irr_taxes_3 + $irr_taxes_3x + $irr_service_charge_3;

							$sql_11 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=0 AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.checkin_date=t1.checkout_date AND t1.status='CheckedOut')";
							$dataset_11 = wgetSQL($sql_11);

							$irr_complimentary_3 = $dataset_11[0]['vat'] + $dataset_11[0]['consumption'] + $dataset_11[0]['scharge'] + ($dataset_11[0]['total'] - $dataset_11[0]['discounts']);

							$sql_12 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed') AND deletedata=0 AND datelogged BETWEEN '{$startdate}' AND '{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL127} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE t1.checkin_date=t1.checkout_date AND t1.status='CheckedOut')"; $dataset_12 = wgetSQL($sql_12);
							$irr_rebate_3 = $dataset_12[0]['total'];

							$sql_13 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=0 AND roomid IN(SELECT t1.roomid FROM {$tbL127} AS t1 LEFT JOIN {$tbL134} AS t2 ON t1.roomid=t2.roomid WHERE t1.checkin_date=t1.checkout_date AND t1.status='CheckedOut')";
							$dataset_13 = wgetSQL($sql_13);

							$irr_accumulated_revenue_3 = $dataset_13[0]['vat'] + $dataset_13[0]['consumption'] + $dataset_13[0]['scharge'] + ($dataset_13[0]['total'] - $dataset_13[0]['discounts']);

							#---end

							$sql_14 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'";
							$dataset_14 = wgetSQL($sql_14);

							$irr_taxes_4 = $dataset_14[0]['vat'];
							$irr_taxes_4x = $dataset_14[0]['consumption'];
							$irr_service_charge_4 = $dataset_14[0]['scharge'];
							$irr_net_revenue_4 = $dataset_14[0]['total'];

							$irr_gross_revenue_4 = $irr_net_revenue_4 + $irr_taxes_4 + $irr_taxes_4x + $irr_service_charge_4;

							$sql_15 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=2 AND invoice_number='EARLYCHECKIN'";
							$dataset_15 = wgetSQL($sql_15);

							$irr_accumulated_revenue_4 =  $dataset_15[0]['vat'] + $dataset_15[0]['consumption'] + $dataset_15[0]['scharge'] + $dataset_15[0]['total'];

							#---end

							$sql_16 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$endate}' AND '{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
							$dataset_16 = wgetSQL($sql_16);

							$irr_taxes_5 = $dataset_16[0]['vat'];
							$irr_taxes_5x = $dataset_16[0]['consumption'];
							$irr_service_charge_5 = $dataset_16[0]['scharge'];
							$irr_net_revenue_5 = $dataset_16[0]['total'];

							$irr_gross_revenue_5 = $irr_net_revenue_5 + $irr_taxes_5 + $irr_taxes_5x + $irr_service_charge_5;

							$sql_17 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=1 AND invoice_number='LATECHECKOUT'";
							$dataset_17 = wgetSQL($sql_17);

							$irr_accumulated_revenue_5 =  $dataset_17[0]['vat'] + $dataset_17[0]['consumption'] + $dataset_17[0]['scharge'] + $dataset_17[0]['total'];

							#---end

							$sql_18 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$endate}' AND '{$endate}'";
							$dataset_18 = wgetSQL($sql_18);

							$irr_net_revenue_6 = $dataset_18[0]['total'];
							$irr_gross_revenue_6 = $dataset_18[0]['total'];

							$sql_19 = "SELECT SUM(cancellation_charges) AS total FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$startdate}' AND '{$endate}'";
							$dataset_19 = wgetSQL($sql_19);
							
							$irr_accumulated_revenue_6 = $dataset_19[0]['total'];

							#---end

							$sql_20 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=0 AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_20 = wgetSQL($sql_20);

							$irr_taxes_7 = $dataset_20[0]['vat'];
							$irr_taxes_7x = $dataset_20[0]['consumption'];
							$irr_service_charge_7 = $dataset_20[0]['scharge'];
							$irr_net_revenue_7 = $dataset_20[0]['total'];

							$irr_gross_revenue_7 = $irr_net_revenue_7 + $irr_taxes_7 + $irr_taxes_7x + $irr_service_charge_7;

							$sql_21 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type IN('complimentary') AND deletedata=0 AND bill_date='{$endate}' AND wkf=0 AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_21 = wgetSQL($sql_21);

							$irr_complimentary_7 = $dataset_21[0]['total'] + $dataset_21[0]['vat'] + $dataset_21[0]['consumption'] + $dataset_21[0]['scharge'];

							$sql_22 = "SELECT SUM(amount) AS total FROM {$tbL163} WHERE approval_status IN('Completed') AND deletedata=0 AND datelogged='{$endate}' AND booking_number IN(SELECT t1.booking_number FROM {$tbL134} AS t1 LEFT JOIN {$tbL163} AS t2 ON t1.booking_number=t2.booking_number WHERE (t1.actual_room_amount = t1.room_amount OR t1.discount_amount = 0) AND t1.deletedata=0)"; $dataset_22 = wgetSQL($sql_22);
							$irr_rebate_7 = $dataset_22[0]['total'];
							
							$sql_23 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND deletedata=0 AND bill_date BETWEEN '{$startdate}' AND '{$endate}' AND wkf=0 AND (actual_room_amount = room_amount OR discount_amount = 0)"; $dataset_23 = wgetSQL($sql_23);

							$irr_accumulated_revenue_7 = $dataset_23[0]['vat'] + $dataset_23[0]['consumption'] + $dataset_23[0]['scharge'] + $dataset_23[0]['total'];

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

						<div class="bottom-push-30">
							<h3 class="large nobold bottom-pull-7">Income Room Revenue</h3>
							<table cellpadding="3" cellspacing="0" border="1">
								<tr>
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
								<tr>
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

						<br><br>

						<?php

						$product_category = 0;
						$shift = 0;

						//get all pos product category from selection

						if($product_category > 0 && $product_category == 1) {
							unset($outlet_category_type[2]);
							unset($outlet_category_type[3]);
						} elseif($product_category > 0 && $product_category == 2) {
							unset($outlet_category_type[1]);
							unset($outlet_category_type[3]);
						} elseif($product_category > 0 && $product_category == 3) {
							unset($outlet_category_type[1]);
							unset($outlet_category_type[2]);
						}

						if(is_array($outlet_category_type)) {

							$add_category_to_array = array();
							
							$category_name = "";
							$category_item_selection_key = "";

							$counter_up = 0;

							foreach($outlet_category_type as $psckey => $pscvalue) {
							
								$add_category_component = array();

								$category_name = $pscvalue;

								$counter_up += 1;

								?>

								<div class="block-element bottom-push-20">
									<h4 class="large blue-font"><?php echo $category_name; ?></h4>
									<div class="block-element top-push-10 sml-rounded-button noscroll">
										<table cellpadding="0" cellspacing="0">
											<tr>
												<th width="150px" align="center">Pos Store</th>
												<th width="100px" align="center">C. Room</th>
												<th width="100px" align="center">Discount</th>
												<th width="70px" align="center">Staff</th>
												<th width="70px" align="center">Cash</th>
												<th width="70px" align="center">Compl.</th>
												<th width="80px" align="center">Group</th>
												<th width="100px" align="center">Food Covers</th>
												<th width="150px" align="center">Total Revenue (Incl. Taxes)</th>
												<th width="70px" align="center">Tax 1</th>
												<th width="70px" align="center">Tax 2</th>
												<th width="70px" align="center">Tax 3</th>
												<th width="150px" align="center">Total Revenue (Excl. Taxes)</th>
												<th width="100px" align="center">Actual Cost</th>
												<th width="100px" align="center">Actual Cost (In %)</th>
											</tr>
											
											<?php

											if(is_array($get_posstores)) {
												
												$g_total_sales = 0; $g_add_taxes = 0; $g_total_sales_tax = 0;
												$g_actual_cost = 0; $g_actual_cost_in_percentage = 0;

												$item_selection_key_1 = "";
												$item_selection_key_2 = "";
												$item_selection_key_3 = "";
												$item_selection_key_4 = "";
												$item_selection_key_5 = "";

												$get_pos_sales_item_data_1 = ""; $get_pos_sales_item_data_2 = "";
												$get_pos_sales_item_data_3 = ""; $get_pos_sales_item_data_4 = "";
												$get_pos_sales_item_data_5 = "";

												$room_charged_total = ""; $room_charged_total_tax = "";
												$staff_total = ""; $staff_total_tax = "";
												$cash_total = ""; $cash_total_tax = "";
												$compl_total = ""; $compl_total_tax = "";
												$group_total = ""; $group_total_tax = "";

												foreach($get_posstores as $key => $val) {

													$additionalQuery = $date_aQuery;

													$total_sum_fu_1 = 0; $total_tax_fu_1 = 0; $total_cover_1 = 0;
													$total_sum_fu_2 = 0; $total_tax_fu_2 = 0; $total_cover_2 = 0;
													$total_sum_fu_3 = 0; $total_tax_fu_3 = 0; $total_cover_3 = 0;
													$total_sum_fu_4 = 0; $total_tax_fu_4 = 0; $total_cover_4 = 0;
													$total_sum_fu_5 = 0; $total_tax_fu_5 = 0; $total_cover_5 = 0;

													$for_vat_1 = 0;
													$for_consumption_1 = 0;
													$for_service_1 = 0;

													$for_vat_2 = 0;
													$for_consumption_2 = 0;
													$for_service_2 = 0;

													$for_vat_3 = 0;
													$for_consumption_3 = 0;
													$for_service_3 = 0;

													$for_vat_4 = 0;
													$for_consumption_4 = 0;
													$for_service_4 = 0;

													$for_vat_5 = 0;
													$for_consumption_5 = 0;
													$for_service_5 = 0;

													$total_cost_fu_1 = 0;
													$total_cost_fu_2 = 0;
													$total_cost_fu_3 = 0;
													$total_cost_fu_4 = 0;
													$total_cost_fu_5 = 0;
													
													##for room charge

													$item_selection_key_2 = array(
														"main_category"=>$psckey,
														"status"=>"Completed",
														"billtype"=>2
													);

													if($val['id'] > 0) { $item_selection_key_2['posid'] = $val['id']; }
													if($shift && $shift > 0) { $item_selection_key_2['shiftid'] = $shift; }

													$get_pos_sales_item_data_2 = mysqli_data_fetch($tbL99,'qty,cost,amount,cover,order_number',$item_selection_key_2,'array');

													if(is_array($get_pos_sales_item_data_2)) {
														$tor_arry_2 = array(); $tor_arry_uni_2 = "";
														$total_sum_2 = ""; $total_tax_2 = ""; $total_cost_2 = "";
														
														foreach($get_pos_sales_item_data_2 as $frc_key => $frc_value) {
															$total_sum_2 = $frc_value['amount'];
															$total_cost_2 = $frc_value['qty'] * $frc_value['cost'];
															$total_tax_2 = ($pos_tax / 100) * $total_sum_2;
															$total_sum_fu_2 = $total_sum_fu_2 + $total_sum_2;
															$total_cover_2 = $total_cover_2 + $frc_value['cover'];
															$total_cost_fu_2 = $total_cost_fu_2 + $total_cost_2;

															if(!in_array($frc_value['order_number'], $tor_arry_2)) {
																$tor_arry_uni_2 .= "'".$frc_value['order_number']."',";
																array_push($tor_arry_2, $frc_value['order_number']);
															}
														}

														$tor_arry_uni_2 = substr_replace($tor_arry_uni_2,'',-1,1);

														$sql_2 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE order_number IN({$tor_arry_uni_2})"; $dataset_2 = wgetSQL($sql_2);

														$total_tax_fu_2 = $dataset_2[0]['vat'] + $dataset_2[0]['consumption'] + $dataset_2[0]['scharge'];

														$for_vat_2 = $dataset_2[0]['vat'];
														$for_consumption_2 = $dataset_2[0]['consumption'];
														$for_service_2 = $dataset_2[0]['scharge'];
													}

													$room_charged_total_tax = $total_sum_fu_2;
													$room_charged_total = $total_sum_fu_2 - $total_tax_fu_2;

													##end

													##for staff charge

													$item_selection_key_5 = array(
														"main_category"=>$psckey,
														"status"=>"Completed",
														"billtype"=>5
													);

													if($val['id'] > 0) { $item_selection_key_5['posid'] = $val['id']; }
													if($shift && $shift > 0) { $item_selection_key_5['shiftid'] = $shift; }

													$get_pos_sales_item_data_5 = mysqli_data_fetch($tbL99,'qty,cost,amount,cover,order_number',$item_selection_key_5,'array');
													
													if(is_array($get_pos_sales_item_data_5)) {
														
														$tor_arry_5 = array(); $tor_arry_uni_5 = "";
														$total_sum_5 = ""; $total_tax_5 = ""; $total_cost_5 = "";
														
														foreach($get_pos_sales_item_data_5 as $stf_key => $stf_value) {
															$total_sum_5 = $stf_value['amount'];
															$total_cost_5 = $stf_value['qty'] * $stf_value['cost'];
															$total_tax_5 = ($pos_tax / 100) * $total_sum_5;
															$total_sum_fu_5 = $total_sum_fu_5 + $total_sum_5;
															//$total_tax_fu_5 = $total_tax_fu_5 + $total_tax_5;
															$total_cover_5 = $total_cover_5 + $stf_value['cover'];
															$total_cost_fu_5 = $total_cost_fu_5 + $total_cost_5;

															if(!in_array($stf_value['order_number'], $tor_arry_5)) {
																$tor_arry_uni_5 .= "'".$stf_value['order_number']."',";
																array_push($tor_arry_5, $stf_value['order_number']);
															}
														}

														$tor_arry_uni_5 = substr_replace($tor_arry_uni_5,'',-1,1);

														$sql_5 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE order_number IN({$tor_arry_uni_5})"; $dataset_5 = wgetSQL($sql_5);

														$total_tax_fu_5 = $dataset_5[0]['vat'] + $dataset_5[0]['consumption'] + $dataset_5[0]['scharge'];

														$for_vat_5 = $dataset_5[0]['vat'];
														$for_consumption_5 = $dataset_5[0]['consumption'];
														$for_service_5 = $dataset_5[0]['scharge'];
													}

													$staff_total_tax = $total_sum_fu_5;
													$staff_total = $total_sum_fu_5 - $total_tax_fu_5;

													##end

													##for cash charge

													$item_selection_key_1 = array(
														"main_category"=>$psckey,
														"status"=>"Completed",
														"billtype"=>1
													);

													if($val['id'] > 0) { $item_selection_key_1['posid'] = $val['id']; }
													if($shift && $shift > 0) { $item_selection_key_1['shiftid'] = $shift; }

													$get_pos_sales_item_data_1 = mysqli_data_fetch($tbL99,'qty,cost,amount,cover,order_number',$item_selection_key_1,'array');

													if(is_array($get_pos_sales_item_data_1)) {
														
														$tor_arry_1 = array(); $tor_arry_uni_1 = "";
														$total_sum_1 = ""; $total_tax_1 = ""; $total_cost_1 = "";
														
														foreach($get_pos_sales_item_data_1 as $csh_key => $csh_value) {
															$total_sum_1 = $csh_value['amount'];
															$total_cost_1 = $csh_value['qty'] * $csh_value['cost'];
															$total_tax_1 = ($pos_tax / 100) * $total_sum_1;
															$total_sum_fu_1 = $total_sum_fu_1 + $total_sum_1;
															//$total_tax_fu_1 = $total_tax_fu_1 + $total_tax_1;
															$total_cover_1 = $total_cover_1 + $csh_value['cover'];
															$total_cost_fu_1 = $total_cost_fu_1 + $total_cost_1;

															if(!in_array($csh_value['order_number'], $tor_arry_1)) {
																$tor_arry_uni_1 .= "'".$csh_value['order_number']."',";
																array_push($tor_arry_1, $csh_value['order_number']);
															}
														}

														$tor_arry_uni_1 = substr_replace($tor_arry_uni_1,'',-1,1);

														$sql_1 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE order_number IN({$tor_arry_uni_1})"; $dataset_1 = wgetSQL($sql_1);

														$total_tax_fu_1 = $dataset_1[0]['vat'] + $dataset_1[0]['consumption'] + $dataset_1[0]['scharge'];

														$for_vat_1 = $dataset_1[0]['vat'];
														$for_consumption_1 = $dataset_1[0]['consumption'];
														$for_service_1 = $dataset_1[0]['scharge'];
													}

													$cash_total_tax = $total_sum_fu_1;
													$cash_total = $total_sum_fu_1 - $total_tax_fu_1;

													##end

													##for compl charge

													$item_selection_key_3 = array(
														"main_category"=>$psckey,
														"status"=>"Completed",
														"billtype"=>3
													);

													if($val['id'] > 0) { $item_selection_key_3['posid'] = $val['id']; }
													if($shift && $shift > 0) { $item_selection_key_3['shiftid'] = $shift; }

													$get_pos_sales_item_data_3 = mysqli_data_fetch($tbL99,'qty,cost,amount,cover,order_number',$item_selection_key_3,'array');
													
													if(is_array($get_pos_sales_item_data_3)) {
														
														$tor_arry_3 = array(); $tor_arry_uni_3 = "";
														$total_sum_3 = ""; $total_tax_3 = ""; $total_cost_3 = "";
														
														foreach($get_pos_sales_item_data_3 as $cmp_key => $cmp_value) {
															$total_sum_3 = $cmp_value['amount'];
															$total_cost_3 = $cmp_value['qty'] * $cmp_value['cost'];
															$total_tax_3 = ($pos_tax / 100) * $total_sum_3;
															$total_sum_fu_3 = $total_sum_fu_3 + $total_sum_3;
															//$total_tax_fu_3 = $total_tax_fu_3 + $total_tax_3;
															$total_cover_3 = $total_cover_3 + $cmp_value['cover'];
															$total_cost_fu_3 = $total_cost_fu_3 + $total_cost_3;

															if(!in_array($cmp_value['order_number'], $tor_arry_3)) {
																$tor_arry_uni_3 .= "'".$cmp_value['order_number']."',";
																array_push($tor_arry_3, $cmp_value['order_number']);
															}
														}

														$tor_arry_uni_3 = substr_replace($tor_arry_uni_3,'',-1,1);

														$sql_3 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE order_number IN({$tor_arry_uni_3})"; $dataset_3 = wgetSQL($sql_3);

														$total_tax_fu_3 = $dataset_3[0]['vat'] + $dataset_3[0]['consumption'] + $dataset_3[0]['scharge'];

														$for_vat_3 = $dataset_3[0]['vat'];
														$for_consumption_3 = $dataset_3[0]['consumption'];
														$for_service_3 = $dataset_3[0]['scharge'];
													}

													$compl_total_tax = $total_sum_fu_3;
													$compl_total = $total_sum_fu_3 - $total_tax_fu_3;

													##end

													##for group charge

													$item_selection_key_4 = array(
														"main_category"=>$psckey,
														"status"=>"Completed",
														"billtype"=>4
													);

													if($val['id'] > 0) { $item_selection_key_4['posid'] = $val['id']; }
													if($shift && $shift > 0) { $item_selection_key_4['shiftid'] = $shift; }

													$get_pos_sales_item_data_4 = mysqli_data_fetch($tbL99,'qty,cost,amount,cover,order_number',$item_selection_key_4,'array');
													
													if(is_array($get_pos_sales_item_data_4)) {
														
														$tor_arry_4 = array(); $tor_arry_uni_4 = "";
														$total_sum_4 = ""; $total_tax_4 = ""; $total_cost_4 = "";
														
														foreach($get_pos_sales_item_data_4 as $grp_key => $grp_value) {
															$total_sum_4 = $grp_value['amount'];
															$total_cost_4 = $grp_value['qty'] * $grp_value['cost'];
															$total_tax_4 = ($pos_tax / 100) * $total_sum_4;
															$total_sum_fu_4 = $total_sum_fu_4 + $total_sum_4;
															//$total_tax_fu_4 = $total_tax_fu_4 + $total_tax_4;
															$total_cover_4 = $total_cover_4 + $grp_value['cover'];
															$total_cost_fu_4 = $total_cost_fu_4 + $total_cost_4;

															if(!in_array($grp_value['order_number'], $tor_arry_4)) {
																$tor_arry_uni_4 .= "'".$grp_value['order_number']."',";
																array_push($tor_arry_4, $grp_value['order_number']);
															}
														}

														$tor_arry_uni_4 = substr_replace($tor_arry_uni_4,'',-1,1);

														$sql_4 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE order_number IN({$tor_arry_uni_4})"; $dataset_4 = wgetSQL($sql_4);

														$total_tax_fu_4 = $dataset_4[0]['vat'] + $dataset_4[0]['consumption'] + $dataset_4[0]['scharge'];

														$for_vat_4 = $dataset_4[0]['vat'];
														$for_consumption_4 = $dataset_4[0]['consumption'];
														$for_service_4 = $dataset_4[0]['scharge'];
													}

													$group_total_tax = $total_sum_fu_4;
													$group_total = $total_sum_fu_4 - $total_tax_fu_4;

													##end

													##for discount charge
													$discount_total_tax = 0; $discount_total = 0;
													##end

													##for inclusion charge
													$inclusion_total_tax = 0; $inclusion_total = 0;
													##end

													##total cover
													$sales_cover = $total_cover_1 + $total_cover_2 + $total_cover_3 + $total_cover_4 + $total_cover_5;
													##end

													##total sales with tax inclusive
													$total_sales_tax = $room_charged_total_tax + $staff_total_tax + $cash_total_tax + $compl_total_tax + $group_total_tax;
													##end

													##total sales without tax inclusive
													$total_sales = $room_charged_total + $staff_total + $cash_total + $compl_total + $group_total;
													##end

													##total tax 1
													//$tax_1 = $total_tax_fu_1 + $total_tax_fu_2 + $total_tax_fu_3 + $total_tax_fu_4 + $total_tax_fu_5;
													$tax_1 = $for_vat_1 + $for_vat_2 + $for_vat_3 + $for_vat_4 + $for_vat_5;
													##end

													##total tax 2
													$tax_2 = $for_consumption_1 + $for_consumption_2 + $for_consumption_3 + $for_consumption_4 + $for_consumption_5;
													##end

													##total tax 3
													$tax_3 = $for_service_1 + $for_service_2 + $for_service_3 + $for_service_4 + $for_service_5;
													##end

													#actual cost
													$actual_cost = $total_cost_fu_1 + $total_cost_fu_2 + $total_cost_fu_3 + $total_cost_fu_4 + $total_cost_fu_5;
													##end

													#actual cost
													if($actual_cost > 0 && $total_sales > 0) { $actual_cost_in_percentage = ($actual_cost * 100) / $total_sales; }
													else { $actual_cost_in_percentage = 0; }
													##end
														
													$add_taxes = $total_tax_fu_1 + $total_tax_fu_2 + $total_tax_fu_3 + $total_tax_fu_4 + $total_tax_fu_5;

													?>
												
													<tr>
														<td width="150px" align="center"><?php echo $val['posname']; ?></td>
														<td width="100px" align="center">&#8358;<?php echo number_format($room_charged_total_tax,2); ?></td>
														<td width="100px" align="center">&#8358;<?php echo number_format($discount_total_tax,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($staff_total_tax,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($cash_total_tax,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($compl_total_tax,2); ?></td>
														<td width="80px" align="center">&#8358;<?php echo number_format($group_total_tax,2); ?></td>
														<td width="100px" align="center"><?php echo $sales_cover; ?></td>
														<td width="150px" align="center">&#8358;<?php echo number_format($total_sales_tax,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($tax_3,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($tax_1,2); ?></td>
														<td width="70px" align="center">&#8358;<?php echo number_format($tax_2,2); ?></td>
														<td width="150px" align="center">&#8358;<?php echo number_format($total_sales,2); ?></td>
														<td width="100px" align="center">&#8358;<?php echo number_format($actual_cost,2); ?></td>
														<td width="100px" align="center"><?php echo number_format($actual_cost_in_percentage,2); ?></td>
													</tr>

													<?php

													$g_total_sales = $g_total_sales + $total_sales;
													$g_add_taxes = $g_add_taxes + $add_taxes;
													$g_total_sales_tax = $g_total_sales_tax + $total_sales_tax;
													$g_actual_cost = $g_actual_cost + $actual_cost;
													$g_actual_cost_in_percentage = $g_actual_cost_in_percentage + $actual_cost_in_percentage;

													$discount_total_tax = 0; $discount_total = 0;
													$inclusion_total_tax = 0; $inclusion_total = 0;										
													$total_sales_tax = 0; $total_sales = 0;
													$tax_1 = 0; $tax_2 = 0; $tax_3 = 0;
													$actual_cost = 0; $actual_cost_in_percentage = 0;
													$sales_cover = 0;
													$add_taxes = 0;

												}

											}

											$add_category_component['revenuenotax'] = $g_total_sales;
											$add_category_component['taxes'] = $g_add_taxes;
											$add_category_component['revenuetax'] = $g_total_sales_tax;
											$add_category_component['actualcost'] = $g_actual_cost;
											$add_category_component['actualpercentagecost'] = $g_actual_cost_in_percentage;

											?>

										</table>
									</div>
								</div>
											
								<?php

								$this_array = $counter_up - 1;
								$add_category_to_array[$psckey] = $add_category_component;

							}

							//print_r($add_category_component);
							//print_r($add_category_to_array);

							?>
								<div class="block-element top-push-50 bottom-push-20" align="center">
									<h4 class="large nobold">Report summary for pos revenue report for <?php echo $date_query; ?> for <?php echo $product_query; ?> for <?php echo $shift_query; ?></h4>
									<div class="block-element nc-width-70 top-push-10 sml-rounded-button noscroll">
										<table cellpadding="0" cellspacing="0">
											<tr>
												<th width="150px" align="center">Category</th>
												<th width="200px" align="center">Revenue (Excl. Taxes)</th>
												<th width="100px" align="center">Taxes</th>
												<th width="200px" align="center">Revenue (Incl. Taxes)</th>
												<th width="150px" align="center">Actual Cost</th>
												<th width="150px" align="center">Cost %</th>
											</tr>

											<?php
											
											if(is_array($add_category_to_array)) {
												
												$total_sum_with_no_tax = 0; $total_sum_with_tax = 0; $total_tax_sum = 0; $total_sum_actual_cost = 0;
												$total_sum_actual_cost_ = 0;

												$category_name = "";
												
												foreach($add_category_to_array as $category => $component) {
													
													$category_name = arrayget_key($outlet_category_type,$category);

													$total_sum_with_no_tax = $total_sum_with_no_tax + $component['revenuenotax'];
													$total_tax_sum = $total_tax_sum + $component['taxes'];
													$total_sum_with_tax = $total_sum_with_tax + $component['revenuetax'];
													$total_sum_actual_cost = $total_sum_actual_cost + $component['actualcost'];
													$total_sum_actual_cost_ = $total_sum_actual_cost_ + $component['actualpercentagecost'];

													?>
														<tr>
															<td width="150px" align="center"><?php echo $category_name; ?></td>
															<td width="200px" align="center">&#8358;<?php echo number_format($component['revenuenotax'],2); ?></td>
															<td width="100px" align="center">&#8358;<?php echo number_format($component['taxes'],2); ?></td>
															<td width="200px" align="center">&#8358;<?php echo number_format($component['revenuetax'],2); ?></td>
															<td width="150px" align="center">&#8358;<?php echo number_format($component['actualcost'],2); ?></td>
															<td width="150px" align="center"><?php echo number_format($component['actualpercentagecost'],2); ?></td>
														</tr>
													<?php
												}

												?>
													<tr bgcolor="#e6e6e6">
														<td width="150px" align="center"><b>Total</b></td>
														<td width="200px" align="center">&#8358;<?php echo number_format($total_sum_with_no_tax,2); ?></td>
														<td width="100px" align="center">&#8358;<?php echo number_format($total_tax_sum,2); ?></td>
														<td width="200px" align="center">&#8358;<?php echo number_format($total_sum_with_tax,2); ?></td>
														<td width="150px" align="center">&#8358;<?php echo number_format($total_sum_actual_cost,2); ?></td>
														<td width="150px" align="center"><?php echo number_format($total_sum_actual_cost_,2); ?></td>
													</tr>
												<?php
											}
											
											?>
										</table>
									</div>
								</div>
		
							<?php
						}
					}

				?>
			</div>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}


	function dateStat() {
		var d = document.getElementById('period').value;
		if(d == 'Custom Date') {
			objDisplay('custom-date');
			document.getElementById('startdate').required = true;
			document.getElementById('endate').required = true;
		} else {
			objHidden('custom-date');
			document.getElementById('startdate').required = false;
			document.getElementById('endate').required = false;
		}
	}

</script>