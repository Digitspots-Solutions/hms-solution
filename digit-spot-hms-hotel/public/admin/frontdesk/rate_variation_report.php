<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])) { $bookid = $_POST['bookingtype']; $bookname = ucfirst($_POST['bookingtype']); } else { $bookid = "all"; $bookname = "All"; }

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
		&nbsp; <b class="default-text-font-bold nobold">Rate Variation Report</b>: here you can see the variances happened in the booking
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
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Booking Type</h3>
				<select name="bookingtype" id="bookingtype" onchange="bktp(this.value)" class="nopads no-back-black">
					<option value="<?php echo $bookid; ?>" selected="selected"><?php echo $bookname; ?></option>
					<option value="all">All</option>
					<option value="individual">Individual</option>
					<option value="corporate">Corporate</option>
					<option value="complimentary">Complimentary</option>
					<!--<option value="ebooking">E-Booking</option>-->
				</select>
			</span>
			<span id="cspg-list" class="noshow cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Corporate List</h3>
				<?php
					$extable = $tbL58; $extcols = "name"; $extkey = "id"; $add2xQuery = " ORDER BY name ASC";
					$get_cspg = select_dt_fetch('booking_type','corporate',$tbL130,'bill_to_g','bill_to_g');
				?>
				<select name="cspg" id="cspg" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_cspg; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm(this)" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
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
		<div class="cs-width-1700">
			<div id="section-to-print">
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$tbl = $tbL134;
					
						$keywords = "";
						
						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							$keywords .= " AND bill_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						}

						if(isset($_POST['bookingtype']) && $_POST['bookingtype'] != 'all') {
							$keywords .= " AND charge_type='{$_POST['bookingtype']}'";
						}

						if(isset($_POST['cspg']) && !empty($_POST['cspg'])) {
							$keywords .= " AND billto='{$_POST['cspg']}'";
						}

						$rvr_sql = "SELECT * FROM {$tbl} WHERE room_status IN('CheckedIn') AND deletedata=0".$keywords;
						$rvr_view = wgetSQL($rvr_sql);

						//print_r($rvr_view);

						?>
							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Rate Variation Report<?php if($bookname != 'All') { echo ' - '.$bookname; } ?> (From <?php echo $startdate; ?> To <?php echo $endate; ?>)</h3>
								<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
							</div>

							<div class="bottom-push-30">
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Room Number</td>
										<td class="alignct default-text-font-bold">Booking Number</td>
										<td class="alignct default-text-font-bold">Room Type</td>
										<td class="alignct default-text-font-bold">Guest Name</td>
										<td class="alignct default-text-font-bold">Arrival Time</td>
										<td class="alignct default-text-font-bold">Departure Time</td>
										<td class="alignct default-text-font-bold">Booking Status</td>
										<td class="alignct default-text-font-bold">Rack Rate Pricing</td>
										<td class="alignct default-text-font-bold">Service Charge</td>
										<td class="alignct default-text-font-bold">Consumption</td>
										<td class="alignct default-text-font-bold">VAT</td>
										<td class="alignct default-text-font-bold">Tariff Amount</td>
										<td class="alignct default-text-font-bold">Extra Bed</td>
										<td class="alignct default-text-font-bold">Service Charge (on Tr.)</td>
										<td class="alignct default-text-font-bold">Consumption (on Tr.)</td>
										<td class="alignct default-text-font-bold">VAT (on Tr.)</td>
										<td class="alignct default-text-font-bold">Variation Amount</td>
									</tr>

									<?php
										if(is_array($rvr_view) && count($rvr_view) > 0) {
											
											$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
											$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
											$checkin_time = ""; $room_name = ""; $room_floor = ""; $booking_type = ""; $bkg_type = "";
											$room_type_name = ""; $block_name = ""; $floor_name = ""; $bill_type = "";

											$numbr = 0; $total_rack_rate_price = 0; $total_rack_rate_service_charge = 0;
											$total_rack_rate_vat = 0; $total_rack_rate_consumption = 0;
											$total_tariff_amt = 0; $total_bed_amt = 0;
											$total_tariff_service_charge = 0; $total_tariff_vat = 0;
											$total_tariff_consumption = 0; $total_variation = 0;
											$total_complimentary = 0; $total_rebate = 0; $total_cancel = 0;
											$disc_1 = 0; $disc_2 = 0;

											$checkin_date = ""; $checkout_date = ""; $checkin_time = ""; $checkout_time = "";

											$addon = ""; $gfl = ""; $setq = "";

											foreach($rvr_view as $key => $val) {
												
												if(!empty($val['booking_number']) && !empty($val['room_type_id']) && !empty($val['roomid']) && !empty($val['charge_type'])) {
												
													$bkg_type = idget_fdata($tbL130,'booking_number',$val['booking_number'],'booking_type');
													$bill_type = idget_fdata($tbL130,'booking_number',$val['booking_number'],'bill_type');

													$room_block = idget_data($tbL56,$val['roomid'],'blockid');
													$block_name = idget_data($tbL49,$room_block,'name');

													$room_floor = idget_data($tbL56,$val['roomid'],'floorid');
													$floor_name = idget_data($tbL50,$room_floor,'name');

													$room_type_name = idget_data($tbL52,$val['room_type_id'],'name');

													$room_name = idget_data($tbL56,$val['roomid'],'roomprefix');
													$room_name .= idget_data($tbL56,$val['roomid'],'roomnumber');

													/*$checkin_date = idget_fdata($tbL127,'roomid',$val['roomid'],'checkin_date');
													$checkin_time = idget_fdata($tbL127,'roomid',$val['roomid'],'checkin_time');
													$checkout_date = idget_fdata($tbL127,'roomid',$val['roomid'],'checkout_date');
													$checkout_time = idget_fdata($tbL127,'roomid',$val['roomid'],'checkout_time');
													$room_status = idget_fdata($tbL127,'roomid',$val['roomid'],'status');*/

													$setq = "booking_number='{$val['booking_number']}' AND roomid='{$val['roomid']}'";
													$gfl = sqlFetch($tbL127,$setq);

													$checkin_date = $gfl['checkin_date'];
													$checkin_time = $gfl['checkin_time'];
													$checkout_date = $gfl['checkout_date'];
													$checkout_time = $gfl['checkout_time'];
													$room_status = $gfl['status'];

													$salutation = idget_data($tbL102,$val['customerid'],'salutation');
													$billto = idget_fdata($tbL130,'booking_number',$val['booking_number'],'bill_to');
													

													$customer_name = idget_data($tbL42,$salutation,'name').' ';
													$customer_name .= idget_data($tbL102,$val['customerid'],'fname').' ';
													$customer_name .= idget_data($tbL102,$val['customerid'],'lname').' ';

													if($booking_type == 'corporate' && isset($billto) && $billto >= 1) { $customer_name .= " (".idget_data($tbL58,$billto,'name').")"; }

											
													if($bill_type == 'Guest' || $bill_type == 'Group Owner') {
														$addon = "transaction_type='rebate' AND ";
														$rebate = billrfx($val['booking_number'],'amount',$tbL131);
														$total_rebate = $total_rebate + $rebate;
													}

													$addon = "status='Cancelled' AND ";
													$cancel = billrfx($val['booking_number'],'cancellation_charges',$tbL127);
													$total_cancel = $total_cancel + $cancel;


													if($bkg_type == 'complimentary') {
														
														$addon = "charge_type='complimentary' AND roomid={$val['roomid']}".$keywords." AND ";
														
														$cy_tariff_amt = billrfx($val['booking_number'],'room_amount',$tbL134);
														$cy_tariff_service_charge = billrfx($val['booking_number'],'service_charge',$tbL134);
													
														$cy_vat_1 = billrfx($val['booking_number'],'tax_amount',$tbL134);
														$cy_vat_2 = billrfx($val['booking_number'],'consumption_tax_amount',$tbL134);
														$cy_tariff_vat = $cy_vat_1 + $cy_vat_2;

														$total_complimentary = $total_complimentary + ($cy_tariff_amt + $cy_tariff_service_charge + $cy_tariff_vat);
													}

													$addon = "roomid={$val['roomid']}".$keywords." AND ";

													if(isset($bookid) && !empty($bookid) && $bookid == $bkg_type) {

														$numbr += 1;

														$rack_rate_price = billrfx($val['booking_number'],'actual_room_amount',$tbL134);
														$rack_rate_service_charge = billrfx($val['booking_number'],'actual_service_charge',$tbL134);
														$vat_1 = billrfx($val['booking_number'],'actual_tax_amount',$tbL134);
														$vat_2 = billrfx($val['booking_number'],'actual_consumption_tax_amount',$tbL134);
														
														$rack_rate_vat = $vat_1;
														$rack_rate_consumption = $vat_2;

														$disc_2 = billrfx($val['booking_number'],'discount_amount',$tbL134);
														$tariff_amt = billrfx($val['booking_number'],'room_amount',$tbL134);
														$tariff_amt = $tariff_amt - $disc_2;
														$tariff_service_charge = billrfx($val['booking_number'],'service_charge',$tbL134);
														
														$vat_1x = billrfx($val['booking_number'],'tax_amount',$tbL134);
														$vat_2x = billrfx($val['booking_number'],'consumption_tax_amount',$tbL134);
														$tariff_vat = $vat_1x;
														$tariff_consumption = $vat_2x;

														$extrabed = billrfx($val['booking_number'],'extrabed_charges',$tbL134);

														$variation = ($rack_rate_price + $rack_rate_service_charge + $rack_rate_vat + $rack_rate_consumption) - ($tariff_amt + $tariff_service_charge + $tariff_vat + $tariff_consumption);

														$total_rack_rate_price = $total_rack_rate_price + $rack_rate_price;
														$total_rack_rate_service_charge = $total_rack_rate_service_charge + $rack_rate_service_charge;
														$total_rack_rate_vat = $total_rack_rate_vat + $rack_rate_vat;
														$total_rack_rate_consumption = $total_rack_rate_consumption + $rack_rate_consumption;
														
														$total_tariff_amt = $total_tariff_amt + $tariff_amt;
														$total_bed_amt = $total_bed_amt + $extrabed;
														$total_tariff_service_charge = $total_tariff_service_charge + $tariff_service_charge;
														$total_tariff_consumption = $total_tariff_consumption + $tariff_consumption;
														$total_tariff_vat = $total_tariff_vat + $tariff_vat;
														$total_variation = $total_variation + $variation;

														?>
															<tr>
																<td class="alignct"><?php echo $room_name; ?> (<?php echo $block_name.' '.$floor_name; ?>)</td>
																<td class="alignct blue-font anchor" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></td>
																<td class="alignct"><?php echo $room_type_name; ?></td>
																<td class="alignct"><?php echo $customer_name; ?></td>
																<td class="alignct"><?php echo date('d-m-y',strtotime($checkin_date)).' '.$checkin_time; ?></td>
																<td class="alignct"><?php echo date('d-m-y',strtotime($checkout_date)).' '.$checkout_time; ?></td>
																<td class="alignct"><?php echo $room_status; ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_price,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_service_charge,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_consumption,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_vat,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_amt,2); ?></td>
																<td class="alignct"><?php echo number_format($extrabed,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_service_charge,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_consumption,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_vat,2); ?></td>
																<td class="alignct"><?php echo number_format($variation,2); ?></td>
															</tr>
														<?php

														$rack_rate_price = 0;
														$rack_rate_service_charge = 0;
														
														$vat_1 = 0;
														$vat_2 = 0;
														$rack_rate_vat = 0;

														$tariff_amt = 0;
														$tariff_service_charge = 0;
														
														$vat_1x = 0;
														$vat_2x = 0;
														$tariff_vat = 0;

														$extrabed = 0;

													} elseif(isset($bookid) && !empty($bookid) && $bookid == 'all') {

														$numbr += 1;


														$rack_rate_price = billrfx($val['booking_number'],'actual_room_amount',$tbL134);
														$rack_rate_service_charge = billrfx($val['booking_number'],'actual_service_charge',$tbL134);
														$vat_1 = billrfx($val['booking_number'],'actual_tax_amount',$tbL134);
														$vat_2 = billrfx($val['booking_number'],'actual_consumption_tax_amount',$tbL134);
														
														$rack_rate_vat = $vat_1;
														$rack_rate_consumption = $vat_2;

														$tariff_amt = billrfx($val['booking_number'],'room_amount',$tbL134);
														$tariff_service_charge = billrfx($val['booking_number'],'service_charge',$tbL134);
														$disc_2 = billrfx($val['booking_number'],'discount_amount',$tbL134);
														$vat_1x = billrfx($val['booking_number'],'tax_amount',$tbL134);
														$vat_2x = billrfx($val['booking_number'],'consumption_tax_amount',$tbL134);
														
														$tariff_amt = $tariff_amt - $disc_2;
														$tariff_vat = $vat_1x;
														$tariff_consumption = $vat_2x;

														$extrabed = billrfx($val['booking_number'],'extrabed_charges',$tbL134);

														$variation = ($rack_rate_price + $rack_rate_service_charge + $rack_rate_vat + $rack_rate_consumption) - ($tariff_amt + $tariff_service_charge + $tariff_vat + $tariff_consumption);

														$total_rack_rate_price = $total_rack_rate_price + $rack_rate_price;
														$total_rack_rate_service_charge = $total_rack_rate_service_charge + $rack_rate_service_charge;
														$total_rack_rate_consumption = $total_rack_rate_consumption + $rack_rate_consumption;
														$total_rack_rate_vat = $total_rack_rate_vat + $rack_rate_vat;
														
														$total_tariff_amt = $total_tariff_amt + $tariff_amt;
														$total_bed_amt = $total_bed_amt + $extrabed;
														$total_tariff_service_charge = $total_tariff_service_charge + $tariff_service_charge;
														$total_tariff_consumption = $total_tariff_consumption + $tariff_consumption;
														$total_tariff_vat = $total_tariff_vat + $tariff_vat;
														$total_variation = $total_variation + $variation;

														?>
															<tr>
																<td class="alignct"><?php echo $room_name; ?> (<?php echo $block_name.' '.$floor_name; ?>)</td>
																<td class="alignct blue-font anchor" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></td>
																<td class="alignct"><?php echo $room_type_name; ?></td>
																<td class="alignct"><?php echo $customer_name; ?></td>
																<td class="alignct"><?php echo date('d-m-y',strtotime($checkin_date)).' '.$checkin_time; ?></td>
																<td class="alignct"><?php echo date('d-m-y',strtotime($checkout_date)).' '.$checkout_time; ?></td>
																<td class="alignct"><?php echo $room_status; ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_price,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_service_charge,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_consumption,2); ?></td>
																<td class="alignct"><?php echo number_format($rack_rate_vat,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_amt,2); ?></td>
																<td class="alignct"><?php echo number_format($extrabed,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_service_charge,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_consumption,2); ?></td>
																<td class="alignct"><?php echo number_format($tariff_vat,2); ?></td>
																<td class="alignct"><?php echo number_format($variation,2); ?></td>
															</tr>
														<?php

														$rack_rate_price = 0;
														$rack_rate_service_charge = 0;
														
														$vat_1 = 0;
														$vat_2 = 0;
														$rack_rate_vat = 0;
														$rack_rate_consumption = 0;

														$tariff_amt = 0;
														$tariff_service_charge = 0;
														
														$vat_1x = 0;
														$vat_2x = 0;
														$tariff_vat = 0;
														$tariff_consumption = 0;

														$extrabed = 0;
													}

													$cy_tariff_amt = 0;
													$cy_tariff_service_charge = 0;
													
													$cy_vat_1 = 0;
													$cy_vat_2 = 0;
													$cy_tariff_vat = 0;

													$rebate = 0; $cancel = 0;
												}
											}
										}
									?>
									<tr>
										<td class="alignlt">TOTAL</td>
										<td class="alignct"></td>
										<td class="alignct"></td>
										<td class="alignct"></td>
										<td class="alignct"></td>
										<td class="alignct"></td>
										<td class="alignct"></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_rack_rate_price,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_rack_rate_service_charge,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_rack_rate_consumption,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_rack_rate_vat,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_tariff_amt,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_bed_amt,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_tariff_service_charge,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_tariff_consumption,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_tariff_vat,2); ?></td>
										<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_variation,2); ?></td>
									</tr>
								</table>
								<h4 class="xlarge nobold top-pull-7"><?php echo $numbr; ?> Found</h4>
							</div>

							<div class="cs-height-30">
							</div>

							<div align="center">
								<h3 class="large nobold alignct">Variation Summary Report</h3>
								<table style="width: 400px !important" cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Particular</td>
										<td class="alignct default-text-font-bold">Amount</td>
									</tr>
									<tr>
										<td class="alignlt">Rack Rate Amount</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_rack_rate_price,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Rack Rate Service Charge</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_rack_rate_service_charge,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Rack Rate VAT</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_rack_rate_vat,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Tariff Amount (-)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_tariff_amt,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Tariff Service Charge (-)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_tariff_service_charge,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Tariff VAT (-)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_tariff_vat,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Variation Amount</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_variation,2); ?></td>
									</tr>
								</table>
							</div>

							<div class="cs-height-30">
							</div>

							<?php
								$tariff_taxes = $total_tariff_service_charge + $total_tariff_vat;
								$total_revenue = ($total_tariff_amt + $tariff_taxes + $total_bed_amt + $total_cancel) - $total_rebate;
							?>

							<div align="center">
								<h3 class="large nobold alignct">Room Tariff Summary Report</h3>
								<table style="width: 500px !important" cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Particular</td>
										<td class="alignct default-text-font-bold">Amount</td>
									</tr>
									<tr>
										<td class="alignlt">Tariff Amount (Excl. of taxes)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_tariff_amt,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Taxes</td>
										<td class="alignrt">&#8358; <?php echo number_format($tariff_taxes,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Total Inclusion Amount</td>
										<td class="alignrt">&#8358; 0.00</td>
									</tr>
									<tr>
										<td class="alignlt">Total Extra Bed Amount</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_bed_amt,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Cancellation Revenue (Inclusive of taxes)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_cancel,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Complimentary Amount (Inclusive of taxes) (-)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_complimentary,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Rebate (-)</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_rebate,2); ?></td>
									</tr>
									<tr>
										<td class="alignlt">Total Revenue (Inclusive of taxes)</td>
										<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_revenue,2); ?></td>
									</tr>
								</table>
							</div>

							<div class="cs-height-50">
							</div>

						<?php
					}

				?>
			</div>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsForm(obj) {
		obj.innerText = 'Loading..';
		document.getElementById('reportform').submit();
	}

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}

	function bktp(val) {
		if(val == 'corporate') {
			chgclass('cspg-list','ln-display-box float-left cs-width-150 top-pull-7 right-push-20');
		} else {
			chgclass('cspg-list','noshow cs-width-150 top-pull-7 right-push-20');
			$('#cspg').prop('selectedIndex', 0);
		}
	}

</script>