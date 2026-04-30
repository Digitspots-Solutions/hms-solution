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
		&nbsp; Note: here you can see all the room-types from bookings for the selected date period
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
			<input type="hidden" name="rtask" id="rtask" value="room-type-revenue-report">
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="date" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">Room Type</h3>
				<select name="roomtypes" id="roomtypes" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_room_types; ?>
				</select>
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

					if(isset($_POST['rtask']) && $_POST['rtask'] == 'room-type-revenue-report'):

					?>
						<div class="bottom-push-15" align="center">
							<div class="cs-width-100 bottom-push-10 noscroll">
								<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
							</div>
							<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
							<h3 class="large nobold default-text-font-bold nomargin">Room-type Revenues On Check-ins</h3>
							<h4 class="large nobold">Between <?php echo date('d-m-Y',strtotime($startdate)); ?> And <?php echo date('d-m-Y',strtotime($endate)); ?></h4>
							<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
						</div>
				
					<?php

					$keywords = ""; $roomtype = "";

					if(isset($_POST['roomtypes']) && !empty($_POST['roomtypes'])) {
						$keywords .= " AND room_type_id={$_POST['roomtypes']}";
						$roomtype = " AND room_type_id={$_POST['roomtypes']}";
					}

					$keywords .= " AND bill_date BETWEEN '{$startdate}' AND '{$endate}'";

					$sql = "SELECT bill_date FROM {$tbL134} WHERE charge_type NOT IN('complimentary') AND wkf IN(0,1,2,7) AND deletedata=0 AND room_status='CheckedIn'".$keywords." GROUP BY bill_date";
					$datagroup = wgetSQL($sql);

					if(is_array($datagroup)) {
						
						$base_data_arry = array();

						$total_occupancy_rate = 0; $set_base_revenue = 0;

						foreach($datagroup as $key => $val) {
							
							$date_arry = array();
							$date_arry['date'] = date('d-m-Y',strtotime($val['bill_date']));

							$in_sql = "SELECT room_type_id FROM {$tbL134} WHERE wkf IN(0,1,2,7) AND deletedata=0 AND room_status='CheckedIn' AND charge_type NOT IN('complimentary') AND bill_date='{$val['bill_date']}'".$roomtype." GROUP BY room_type_id"; $dataset = wgetSQL($in_sql);

							?>
								<h3 class="large nobold default-text-font-bold"><?php echo date('d-m-Y',strtotime($val['bill_date'])); ?></h3>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Room Type</td>
										<td class="alignct default-text-font-bold">Occupancy</td>
										<td class="alignct default-text-font-bold">Revenue</td>
										<td class="alignct default-text-font-bold">Cancel Revenue</td>
										<td class="alignct default-text-font-bold">Tax</td>
										<td class="alignct default-text-font-bold">Gross Revenue</td>
										<td class="alignct default-text-font-bold">Net Revenue</td>
									</tr>

									<?php
										if(is_array($dataset)) {
											
											$room_type_name = ""; $nos_rooms = ""; $room_occupancy = 0; $occupancy_rate = 0;
											$revenue = 0; $cancel_revenue = 0; $taxes = 0; $gross_revenue = 0;

											$total_occupancy = 0; $total_rooms_avail = 0; $total_revenue = 0;
											$total_cancel_revenue = 0; $total_taxes = 0; $total_gross_revenue = 0;

											$rt_sql = ""; $in_dataset = "";
											
											foreach($dataset as $key2 => $val2) {
												
												if(!empty($val2['room_type_id'])) {
												
													$room_type_name = idget_data($tbL52,$val2['room_type_id'],'name');
													$nos_rooms = idget_data($tbL52,$val2['room_type_id'],'noofrooms');

													$rt_sql = "SELECT COUNT(roomid) AS totalrooms FROM {$tbL134} WHERE wkf IN(0,1,2,7) AND deletedata=0 AND charge_type NOT IN('complimentary') AND room_status='CheckedIn' AND bill_date='{$val['bill_date']}' AND room_type_id={$val2['room_type_id']}"; $in_dataset = wgetSQL($rt_sql);

													$room_occupancy = $in_dataset[0]['totalrooms'];
													$occupancy_rate = @ ($room_occupancy / $nos_rooms) * 100;

													#end 1:

													$rn_sql = "SELECT SUM(room_amount) AS totalrevenue, SUM(discount_amount) AS totaldiscount FROM {$tbL134} WHERE wkf IN(0,1,2,7) AND deletedata=0 AND charge_type NOT IN('complimentary') AND room_status='CheckedIn' AND bill_date='{$val['bill_date']}' AND room_type_id={$val2['room_type_id']}"; $in_dataset2 = wgetSQL($rn_sql);

													$revenue = $in_dataset2[0]['totalrevenue'] - $in_dataset2[0]['totaldiscount'];

													#end 2:

													$cr_sql = "SELECT SUM(cancellation_charges) AS totalcancelrevenue FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date='{$val['bill_date']}' AND room_type_id={$val2['room_type_id']}"; $in_dataset3 = wgetSQL($cr_sql);

													$cancel_revenue = $in_dataset3[0]['totalcancelrevenue'];

													#end 3:

													$tx_sql = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge FROM {$tbL134} WHERE wkf IN(0,1,2,7) AND deletedata=0 AND charge_type NOT IN('complimentary') AND room_status='CheckedIn' AND bill_date='{$val['bill_date']}' AND room_type_id={$val2['room_type_id']}"; $in_dataset4 = wgetSQL($tx_sql);

													$taxes = $in_dataset4[0]['vat'] + $in_dataset4[0]['consumption'] + $in_dataset4[0]['scharge'];

													#end 4:

													$gross_revenue = $revenue + $taxes;

													#base total
													$total_occupancy = $total_occupancy + $room_occupancy;
													$total_rooms_avail = $total_rooms_avail + $nos_rooms;
													$total_revenue = $total_revenue + $revenue;
													$total_cancel_revenue = $total_cancel_revenue + $cancel_revenue;
													$total_taxes = $total_taxes + $taxes;
													$total_gross_revenue = $total_gross_revenue + $gross_revenue;

													?>
														<tr>
															<td class="alignlt"><?php echo $room_type_name; ?></td>
															<td class="alignlt">(<?php echo $room_occupancy.'/'.$nos_rooms; ?>) <?php echo number_format($occupancy_rate,1); ?>%</td>
															<td class="alignrt"><?php echo number_format($revenue,2); ?></td>
															<td class="alignrt"><?php echo number_format($cancel_revenue,2); ?></td>
															<td class="alignrt"><?php echo number_format($taxes,2); ?></td>
															<td class="alignrt"><?php echo number_format($gross_revenue,2); ?></td>
															<td class="alignrt"><?php echo number_format($revenue,2); ?></td>
														</tr>
													<?php
												}
											}
										}

										$set_base_revenue = $set_base_revenue + $total_revenue;

										/*$rn_sqlx = "SELECT SUM(room_amount) AS totalrevenue FROM {$tbL134} WHERE wkf IN(0,1,2) AND deletedata=0 AND room_status='CheckedIn' AND bill_date BETWEEN '{$startdate}' AND '{$val['bill_date']}'"; $in_dataset2x = wgetSQL($rn_sqlx);*/

										$total_occupancy_rate = ($total_occupancy / $total_rooms_avail) * 100;

										$date_arry['roomoccupancy'] = $total_occupancy;
										$date_arry['noofrooms'] = $total_rooms_avail;
										$date_arry['occupancydetail'] = "(".$total_occupancy."/".$total_rooms_avail.") ".number_format($total_occupancy_rate,1)."%";
										$date_arry['revenue'] = $total_revenue;
										$date_arry['grossrevenue'] = $total_gross_revenue;
										$date_arry['revenue2date'] = $set_base_revenue;
									?>

									<tr>
										<td class="alignlt default-text-font-bold">Total</td>
										<td class="alignlt default-text-font-bold">(<?php echo $total_occupancy.'/'.$total_rooms_avail; ?>) <?php echo number_format($total_occupancy_rate,1); ?>%</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_cancel_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_taxes,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_gross_revenue,2); ?></td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_revenue,2); ?></td>
									</tr>

								</table>

								<br><br>
							<?php

							array_push($base_data_arry, $date_arry);
						}

						?>

							<h3 class="large nobold alignct">Report Summary for Room-type Revenues Between <?php echo date('d-m-Y',strtotime($startdate)); ?> And <?php echo date('d-m-Y',strtotime($endate)); ?></h3>

							<table cellpadding="3" cellspacing="0" border="1">
								<tr class="grey-theme">
									<td class="alignct default-text-font-bold">Date</td>
									<td class="alignct default-text-font-bold">Total Occupancy</td>
									<td class="alignct default-text-font-bold">Gross Revenue</td>
									<td class="alignct default-text-font-bold">Net Revenue</td>
									<td class="alignct default-text-font-bold">Revenue to Date</td>
								</tr>

								<?php
									if(is_array($base_data_arry)) {
										
										$grand_total_occupancy = 0; $grand_total_room = 0;
										$grand_total_revenue = 0; $grand_total_gross_revenue = 0;
										
										foreach($base_data_arry as $key => $val) {
											
											$grand_total_occupancy = $grand_total_occupancy + $val['roomoccupancy'];
											$grand_total_room = $grand_total_room + $val['noofrooms'];
											$grand_total_revenue = $grand_total_revenue + $val['revenue'];
											$grand_total_gross_revenue = $grand_total_gross_revenue + $val['grossrevenue'];

											?>
												<tr>
													<td class="alignlt"><?php echo $val['date']; ?></td>
													<td class="alignlt"><?php echo $val['occupancydetail']; ?></td>
													<td class="alignrt">&#8358; <?php echo number_format($val['grossrevenue'],2); ?></td>
													<td class="alignrt">&#8358; <?php echo number_format($val['revenue'],2); ?></td>
													<td class="alignrt">&#8358; <?php echo number_format($val['revenue2date'],2); ?></td>
												</tr>
											<?php
										}

										$base_occupancy_rate = ($grand_total_occupancy / $grand_total_room) * 100;
									}
								?>

								<tr>
									<td class="alignlt default-text-font-bold">Total</td>
									<td class="alignlt default-text-font-bold">(<?php echo $grand_total_occupancy.'/'.$grand_total_room; ?>) <?php echo number_format($base_occupancy_rate,1); ?>%</td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grand_total_gross_revenue,2); ?></td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grand_total_revenue,2); ?></td>
									<td class="alignrt default-text-font-bold"></td>
								</tr>

							</table>
						<?php
					}


					endif;

				?>

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