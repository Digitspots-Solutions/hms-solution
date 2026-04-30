<?php
	$smdl = "frontdesk";
	if(isset($_GET['logs'])) { $logs = escape_data($_GET['logs']); }

	$pst_booking_number = "";
	$isguestAct = 0;
	$guestAct_msg = "";
	$ths_guest_pry = 0;

	if((isset($_GET['cf']) && $_GET['cf'] == 'yes') && (isset($_GET['cfbooking']) && !empty($_GET['cfbooking']))) {
		$pst_query = array("booking_number"=>$_GET['cfbooking'],"room_status"=>"Reserved");
		$pst_field = array("charge"=>"yes");
		mysqli_data_update($tbL134,$pst_field,$pst_query);

		unset($_GET['cf']); unset($_GET['cfbooking']);
	}

	if(isset($_GET['token']) && !empty($_GET['token'])) {
		
		$booking_number = $_GET['token'];

		include "post_booking_tokens.php";
		include "booking_tokens.php";

		?>
			<div class="block-element box-border-thick pads10 xsml-rounded-button noscroll">
				<div class="right-pull-15 bottom-pull-7 left-pull-15 box-border-thick-bottom ft-xsml-size">
					<span class="ln-display-box float-right">
						<?php if($wgt_reservation == 'Reserving') { ?><a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state right-push-20" onclick="popmodalframe('frontdesk','bookingdetails','<?php echo $booking_number; ?>','<?php echo $wgt_booking_type; ?>',1000,1500)">Print Booking</a><?php } ?>
						<a href="javascript:void(0)" class="blue-font right-push-20" onclick="popmodalframe('frontdesk','history','<?php echo $booking_number; ?>',0,800,1500)">History</a>
						<?php if((($wgt_bill_type == 'Group Owner' || $wgt_bill_type == 'Guest') && $isbillPaid == false) || ($wgt_bill_type == 'Corporate' && $wgt_settled_booking == 0)) { ?><a href="javascript:void(0)" class="blue-font right-push-20" onclick="popmodalframe('frontdesk','modifybooking','<?php echo $booking_number; ?>','<?php echo $wgt_booking_type; ?>',600,400)">Modify Booking</a><?php } ?>
					</span>
					<a href="?logs=<?php echo $logs; ?>&token=<?php echo $booking_number; ?>" class="black-font right-push-7" title="Refresh guest booking ledger for update"><b class="fa-refresh nobold"></b></a> Booking No: &nbsp; <b class="nobold default-text-font-bold"><?php echo $booking_number; ?> (<?php echo $wgt_lodged_as; ?>)</b>
				</div>
				<div class="block-element top-pull-20 ft-sml-size">
					<div class="ln-display-box float-left nc-width-35 box-border-thick-right right-pull-20">
						<h4 class="large nobold default-text-font-bold">Guest Details 
							<?php if($wgt_reservation == 'Checking In' || $wgt_reservation == 'Reserving'): ?>
								<a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="popmodalframe('frontdesk','guestdetail','<?php echo $booking_number; ?>',0,1000,1500)">Edit</a>
							<?php elseif(isset($allowGuestdetailUpdate) && $allowGuestdetailUpdate == 200): ?>
								<a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="popmodalframe('frontdesk','guestdetail','<?php echo $booking_number; ?>',0,1000,1500)">Edit</a>
							<?php else: ?>
								<b class="light-red-font nobold ft-xsml-size left-push-20">CLOSED</b>
							<?php endif; ?></h4><br>

						<?php
							#if guest account is corporate
							if($bill_charged_on != '') {
								$replica = $bill_charged_on;
								?>
									<div class="block-element bottom-push-20 dark-blue-font">
										<?php echo $bill_charged_on; ?>
									</div>
								<?php
							} else {
								$replica = $guest_account_name;
							}
						?>

						<span class="ln-display-box float-left nc-width-30 right-pull-10 noscroll">
							<div class="block-element anchor" onclick="xModal(1,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1)">
								<?php if(isset($get_guest_detail[1]) && !empty($get_guest_detail[1]) && file_exists('../../theme/images/general/guestphotos/'.$get_guest_detail[1])) { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/guestphotos/<?php echo $get_guest_detail[1]; ?>" class="auto-wh"><?php } else { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png"><?php } ?>
							</div>
							<p class="top-pull-5">
								<?php if($wgt_reservation == 'Checking In' || $wgt_reservation == 'Reserving'): ?>
									<a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="xModal(1,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1)">Change Photo</a>
								<?php endif; ?>
							</p>
						</span>
						<span class="ln-display-box float-left nc-width-70 left-pull-10">
							<div class="ln-display-box float-left nc-width-50 right-pull-5">
								<small class="block-element bottom-push-3 dark-grey-font">Guest Code</small>
								<small class="block-element bottom-push-10"><?php echo $get_guest_detail[2].$wgt_pry_id; ?></small>

								<small class="block-element bottom-push-3 dark-grey-font">Address</small>
								<small class="block-element bottom-push-10"><?php echo $get_guest_detail[9]; ?></small>
							</div>
							<div class="ln-display-box float-left nc-width-50 left-pull-5">
								<small class="block-element bottom-push-3 dark-grey-font">Name</small>
								<small class="block-element bottom-push-10"><?php echo $guest_account_name; ?></small>

								<small class="block-element bottom-push-3 dark-grey-font">Mobile</small>
								<small class="block-element bottom-push-10"><?php echo $get_guest_detail[6]; ?></small>

								<small class="block-element bottom-push-3 dark-grey-font">Email</small>
								<small class="block-element bottom-push-10"><?php echo $get_guest_detail[7]; ?></small>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>

						<br><br>
							
						<ul class="nolist">
							
							<?php
								
								if(isset($wgt_reservation) && $wgt_reservation == 'Checking In') {
									
									if($wgt_booking_type != "corporate") {
										
										?>
											<li class="bottom-push-3 <?php echo $guestfeatOpt; ?>"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','applydiscount','<?php echo $booking_number; ?>',0,500,400)">+ Apply Discount</a></li>
										
											<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','sendsms','<?php echo $booking_number; ?>',0,600,800)">+ Send SMS</a></li>
											<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','sendemail','<?php echo $booking_number; ?>',0,400,400)">+ Send Email</a></li>
										<?php

										if(isset($wgt_isweekend_fares) && $wgt_isweekend_fares == 'No') {
											?>
												<li class="bottom-push-3">
													<a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','weekendfares','<?php echo $booking_number; ?>','applyfares',500,400)">+ Apply Weekend Fares</a>
												</li>
											<?php
										} else {
											?>
												<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','weekendfares','<?php echo $booking_number; ?>','disablefares',500,400)">+ Disable Weekend Fares</a></li>
											<?php
										}
									}
								
									if(isset($wgt_islate_checkout) && $wgt_islate_checkout == 'Yes') {
										?>
											<li class="bottom-push-3">
												<a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','checkoutcharges','<?php echo $booking_number; ?>','disable',500,400)">+ Disable Late Checkout Charges</a>
											</li>
										<?php
									} else {
										?>
											<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','checkoutcharges','<?php echo $booking_number; ?>','enable',500,400)">+ Apply Late Checkout Charges</a></li>
										<?php
									}

									?>
										<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','callupday','<?php echo $booking_number; ?>','disablefares',500,900)">+ Call-up Day (Booking)</a></li>
									<?php
								}

							?>
						</ul>
					</div>
					<div class="ln-display-box float-left nc-width-35 box-border-thick-right right-pull-20 left-pull-20">
						<h4 class="large nobold default-text-font-bold">Stay Details <?php if($wgt_reservation == 'Checking In') { ?><a id="extButton" href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="xModal(2,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1); htmlpassval('<?php echo $booking_number; ?>','wgtfield1')" title="Add to guest stay duration">Extend</a><a id="extButton" href="javascript:void(0)" class="light-red-font ft-xsml-size left-push-10" onclick="popmodalframe('frontdesk','reversebooking','<?php echo $booking_number; ?>','guestbooking',600,900)" title="Reduce guest stay duration">Reverse</a><?php } ?></h4><br>
						
						<table cellpadding="0" cellspacing="0">
							<tr>
								<th width="100px" align="center"></th>
								<th width="150px" align="center">Check-In</th>
								<th width="150px" align="center">Check-Out</th>
							</tr>
							<tr>
								<td width="100px" align="center"><small class="ft-xxsml-size">Estimated</small></td>
								<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $print_wgt_checkin_date.' '.$print_wgt_df_checkin_time; ?></b></small></td>
								<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $print_wgt_checkout_date.' '.$print_wgt_df_checkout_time; ?></b></small></td>
							</tr>
							<tr>
								<td width="100px" align="center"><small class="ft-xxsml-size">Actual</small></td>
								<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $print_wgt_checkin_date.' '.$print_wgt_checkin_time; ?></b></small></td>
								<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $print_wgt_checkout_date.' '.$print_wgt_checkout_time; ?></b></small></td>
							</tr>
						</table>

						<br>
						
						<h4 class="large nobold">&bull; Allow bill to room: <b><?php echo $wgt_isbill_to_room; ?></b><span class="float-right"><?php if(isset($allowAd) && $allowAd == 'yes') { ?><a id="b2r" href="javascript:void(0)" class="blue-font ft-xxsml-size right-push-10" onclick="xModal(4,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1); htmlpassval('<?php echo $booking_number; ?>','wgtfield3')" title="Allow more bills">+ Add</a><?php } if(isset($allowChg) && $allowChg == 'yes') { ?><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="xModal(3,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1); htmlpassval('<?php echo $booking_number; ?>','wgtfield2')" title="Change bill room status">Change</a><?php } ?></span></h4>
						
						<table cellpadding="0" cellspacing="0" class="top-push-7">
							<tr>
								<td>
									<?php 
										$ths_service_name = ""; $issub = "";
										if(is_array($rsArry)) {
											foreach($rsArry as $rs) {
												if(is_numeric($rs)) {
													
													$ths_service_name = idget_data($tbL14,$rs,'posname');
													$issub = idget_data($tbL14,$rs,'isfoodtype');
													
													?>
														<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-10">
															<div class="ln-display-box float-left nc-width-90">
																<small class="steel-blue-font"><?php echo $ths_service_name; ?></small>
															</div>
															<div class="ln-display-box float-right nc-width-10 alignct">
																<a href="?logs=<?php echo $logs; ?>&token=<?php echo $booking_number; ?>&r=<?php echo $rs; ?>&wgtag=removebilltoroom" class="ft-xxsml-size black-font" title="Remove > <?php echo $ths_service_name; ?>">x</a>
															</div>
															<div class="block-element new-line-space">
															</div>
															<?php
																if($issub == 'Yes') {
																	for($x=1; $x <= 3; $x++) {
																		$rsx = $rs.'-'.$x;
																		if(in_array($rsx,$rsArry)) {
																			?>
																				<a href="?logs=<?php echo $logs; ?>&token=<?php echo $booking_number; ?>&r=<?php echo $rsx; ?>&wgtag=removebilltoroom" class="ft-xxsml-size sea-green-font right-push-5" title="Remove > <?php echo $food_type[$x]; ?>"><?php echo $food_type[$x]; ?> x</a>
																			<?php
																		}
																	}
																}
															?>
														</span>
													<?php
												}
											}
										}
									?>
									<span class="block-element new-line-space">
									</span>
								</td>
							</tr>
						</table>

						<br>
						
						<h4 class="large nobold">&bull; Arrival / Departure Details <a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="popmodalframe('frontdesk','arrivaldeparturemode','<?php echo $booking_number; ?>',0,500,500)">Edit</a></h4>
						
						<table cellpadding="0" cellspacing="0" class="top-push-7">
							<tr>
								<td width="150px" align="left" class="dark-grey-theme"><small class="ft-xxsml-size">&nbsp; Source of Biz</small></td>
								<td width="200px" align="center" class="white-theme"><small class="ft-xxsml-size"><?php echo $source_of_biz; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left" class="dark-grey-theme"><small class="ft-xxsml-size">&nbsp; Arrival Mode</small></td>
								<td width="200px" align="center" class="white-theme"><small class="ft-xxsml-size"><?php echo $arrival_mode; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left" class="dark-grey-theme"><small class="ft-xxsml-size">&nbsp; Departure Mode</small></td>
								<td width="200px" align="center" class="white-theme"><small class="ft-xxsml-size"><?php echo $departure_mode; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left" class="dark-grey-theme"><small class="ft-xxsml-size">&nbsp; Remarks</small></td>
								<td width="200px" align="center" class="white-theme"><small class="ft-xxsml-size"><?php echo $gad_remarks; ?></small></td>
							</tr>
						</table>

					</div>
					<div class="ln-display-box float-left nc-width-30 left-pull-20">
						<h4 class="large nobold default-text-font-bold">Booking Details</h4><br>

						<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Booked By:</small></td>
								<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $frontdesk_cashier; ?> (<?php echo $print_wgt_bk_datelogged.' '.$print_wgt_bk_timelogged; ?>)</small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">No of Rooms:</small></td>
								<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $wgt_total_rooms; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Adult:</small></td>
								<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $wgt_total_adults; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Child:</small></td>
								<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $wgt_total_childs; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Extra Beds:</small></td>
								<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $wgt_total_extrabeds; ?></small></td>
							</tr>
						</table>

						<div class="block-element top-push-10 bottom-push-20 pads10 grey-theme ft-xsml-size">Bill Paid By: &nbsp; <b><?php echo $wgt_bill_type; ?></b></div>
						<?php 
							if(isset($wgt_charge) && $wgt_charge == 'yes') {
								if(!isset($wgt_settled_booking) || $wgt_settled_booking == 0) {
									?>
										<a href="javascript:void(0)" class="dark-blue-font ft-xxsml-size float-right" onclick="popmodalframe('frontdesk','applytaxes','<?php echo $booking_number; ?>',0,500,400)">Apply Taxes</a><?php
								}
								//if($wgt_bill_type == 'Group Owner' || $wgt_bill_type == 'Guest') {
									?>
									<h4 class="large nobold default-text-font-bold">Tariff Details<a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-7" onclick="popmodalframe('frontdesk','paymentandinvoice','<?php echo $booking_number; ?>',0,1000,2500)">Payment & Invoice</a></h4><br>
									<?php
								//}
							} else { 
								?>
									<h4 class="large nobold default-text-font-bold">Tariff Details<a href="javascript:void(0)" class="blue-font ft-xxsml-size left-push-15" onclick="confirmReservation('<?php echo $booking_number; ?>')">Confirm Reservation?</a></h4><br>
								<?php
							}
						?>

						<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Room Charges</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_room_charges; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Discount</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_discount; ?></small></td>
							</tr>

							<?php if($htx2 == 1): ?>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Service Charge</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_service_charge; ?></small></td>
							</tr>
							<?php endif; ?>

							<?php if($htx3 == 1): ?>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Value Added Tax</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_tax_amount; ?></small></td>
							</tr>
							<?php endif; ?>

							<?php if($htx1 == 1): ?>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Consumption Tax</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_consumption_tax_amount; ?></small></td>
							</tr>
							<?php endif; ?>

							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Other Charges</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_other_charges; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Total</small></td>
								<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php echo $print_grand_total; ?></small></td>
							</tr>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size"><?php echo $billpaylabel; ?></small></td>
								<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php if($wgt_booking_type == 'corporate' && $wgt_bill_type == 'Corporate') { echo $print_balance; } else { echo $print_amount_paid; } ?></small></td>
							</tr>
							<?php if($wgt_booking_type == 'corporate' && $wgt_bill_type == 'Corporate'): ?>
							<tr>
								<td colspan="2"></td>
							</tr>
							<?php else: ?>
							<tr>
								<td width="150px" align="left"><small class="ft-xxsml-size">Balance</small></td>
								<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_balance; ?></small></td>
							</tr>
							<?php endif; ?>
						</table>


					</div>
					<div class="block-element new-line-space">
					</div>
				</div>
				<div class="block-element top-push-30 box-border-thick xsml-rounded-button ft-xsml-size noscroll">
					<!--<a href="javascript:void(0)" class="blue-font" onclick="addMrm()">+ Add New Room</a>-->
					<form action="" method="post" autocomplete="off">
						<p class="top-pull-5 bottom-pull-3 left-pull-10">
							<?php if(isset($wgt_reservation) && ($wgt_reservation == 'Checking In' || $wgt_reservation == 'Reserving')) { ?><a href="javascript:void(0)" class="blue-font" onclick="addnewbkg()">+ Add New Booking</a><?php } ?>
						</p>
						<div class="nc-width-100 cs-height-450 grey-1-theme box-border-thick-top iscroll">
							<div class="cs-width-1500">
								<?php include "guest_occupancy_room_list.php"; ?>
							</div>
						</div>
						<p class="top-pull-10 alignct">
							<input type="submit" name="savechangesbutton" id="btn1" value="Save Changes" class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 right-push-5 anchor" disabled="disabled">
							<input type="button" id="btn2" value="Cancel Room" class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 right-push-5 anchor" onclick="xModal(5,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1); wgthsroom()" disabled="disabled">
							<input type="submit" name="noshowbutton" id="btn3" value="Apply No-Show" class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 right-push-5 anchor" disabled="disabled">
							<input type="submit" name="checkinbutton" id="btn4" value="Check-In" class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 right-push-5 anchor" disabled="disabled">
							<input type="submit" name="checkoutbutton" id="btn5" value="Check-Out" class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 anchor" disabled="disabled">
						</p>
					</form>
				</div>
				<div class="block-element top-push-5 box-border-thick xsml-rounded-button pads20 noscroll ft-xsml-size">
					<h4 class="large nobold default-text-font-bold">Booking Legends</h4><br>
					<span class="ln-display-box float-left nc-width-35">
						<ul class="nolist">
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/rack_rate_icon.png"></span><span class="ln-display-box float-left left-pull-7">Rack Rates</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/seasons_icon.png"></span><span class="ln-display-box float-left left-pull-7">Seasons</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/packages_icon.png"></span><span class="ln-display-box float-left left-pull-7">Packages</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/tariff_change_icon.png"></span><span class="ln-display-box float-left left-pull-7">Tariff Change</span><span class="block-element new-line-space"></span>
							</li>
						</ul>
					</span>
					<span class="ln-display-box float-left nc-width-35">
						<ul class="nolist">
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/not_bill_icon.png"></span><span class="ln-display-box float-left left-pull-7">Not Billed for The Day</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/bill_icon.png"></span><span class="ln-display-box float-left left-pull-7">Billed for The Day</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/credit_notification_icon.png"></span><span class="ln-display-box float-left left-pull-7">Credit Notification</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/checkin_card_icon.png"></span><span class="ln-display-box float-left left-pull-7">Check-In Card</span><span class="block-element new-line-space"></span>
							</li>
						</ul>
					</span>
					<span class="ln-display-box float-left nc-width-30">
						<ul class="nolist">
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/edit_record_icon.png"></span><span class="ln-display-box float-left left-pull-7">Edit Record</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/inclusions_icon.png"></span><span class="ln-display-box float-left left-pull-7">Inclusions</span><span class="block-element new-line-space"></span>
							</li>
							<li class="bottom-push-7">
								<span class="ln-display-box float-left"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/room_type_tariff_change_icon.png"></span><span class="ln-display-box float-left left-pull-7">Room Type Tariff Change</span><span class="block-element new-line-space"></span>
							</li>
						</ul>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
			</div>
		<?php


		##log guest activities
		if(isset($isguestAct) && $isguestAct == 1) {
			$ths_guest_pry = $wgt_pry_id;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$ths_guest_pry,"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
		}
		
	}

?>

<!-- for photo upload -->
<div id="modal-win-1" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-1" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-40 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="imgform" enctype="multipart/form-data">
			 <a href="javascript:void(0)" class="royal-blue-font ft-mini-size float-right" onclick="xModal(1,'fx-position-flow right-layout motion',0); htmlFormReset('imgform')"><b class="mbri-close"></b></a>
			<h2 class="large nobold default-text-font-bold">Guest Photograph</h2><br>
			<div id="image-box" class="cs-height-350 box-border-dashed xsml-rounded-button pads20 noscroll">
				<div class="nc-height-20"></div>
				<div id="image-tip" class="alignct ft-mini-size dark-grey-font" onclick="document.getElementById('f').click()">
					<h1 class="nobold fa-camera fa-color-strike-3" style="font-size: 5em"></h1>
					<small class="block-element royal-blue-font anchor">Click to attach photo</small>
				</div>
				<h2 class="large nobold alignct top-pull-5">-OR-</h2>
				<p class="alignct">
					<a href="javascript:void(0)" onclick="ocf()" class="royal-blue-font ft-sml-size">Click to use camera</a>
				</p>
			</div>
			<input onchange="resizeimage(event,350,300,'dataurl','notupload','cimg','image-box'); writeObjheader('fmsg','attaching image..'); chgclass('imbutton','top-push-30')" type="file" id="f" style="position: fixed; top: -100em" accept=".png, .jpg, .jpeg">
			<input type="hidden" name="dataurl" id="dataurl">
			<small id="fmsg" class="block-element red-font bottom-push-10 alignlt"></small>
			<input type="hidden" name="wgtidx" id="wgtidx" value="<?php echo $wgt_pry_id; ?>" required="required">
			<div id="snap" class="noshow" align="center">
			</div>
			<div id="imbutton" class="noshow top-push-30" align="center">
				<input type="submit" name="imagebutton" value="Apply" class="nc-width-60 submit anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10">
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>

<div id="websnap" class="noshow motion">
<p class="noshow"><a id="x-snap" href="javascript://" onclick="chgclass('websnap','noshow motion')">x</a></p>
<iframe id="snap-frame" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" width="400px" height="500px"></iframe>
</div>


<!-- for stay extension -->
<div id="modal-win-2" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-2" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-40 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="exform" onsubmit="document.getElementById('extendstaybutton').value='Applying..'; setTimeout(() => { document.getElementById('extendstaybutton').setAttribute('type','button'); },1000)">
			<h2 id="ext-title" class="large nobold default-text-font-bold">Extend Guest Stay</h2><br>
			
			<div class="xform bottom-push-30">
				<span class="ln-display-box float-left nc-width-50 right-pull-10">
					<small class="block-element dark-grey-font bottom-push-3">Start from (default)</small>
					<input type="date" name="exstartdate" id="exstartdate" value="<?php echo $wgt_checkout_date; ?>" class="nopads no-back-black" required="required" readonly="readonly">
				</span>
				<span class="ln-display-box float-left nc-width-50 left-pull-10">
					<small class="block-element dark-grey-font bottom-push-3">End to?</small>
					<input type="date" name="exendate" id="exendate" value="<?php echo date('Y-m-d',strtotime($wgt_checkout_date . ' +1 days')); ?>" class="nopads no-back-black" required="required">
				</span>
				<span class="block-element new-line-space">
				</span>
				<input type="hidden" name="wgtfield1" id="wgtfield1">
			</div>

			<div id="fbutton" class="top-push-30" align="center">
				<input type="hidden" name="wgtag" id="wgtag" value="extendstay">
				<input type="hidden" name="wgtagroom" id="wgtagroom" value="0">
				<input type="submit" name="submitbutton" id="extendstaybutton" value="Apply" class="nc-width-50 anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10"> <a href="javascript:void(0)" class="ft-xsml-size blue-font" onclick="xModal(2,'fx-position-flow right-layout motion',0)">Cancel</a>
				<p id="ext-note" class="top-pull-10 ft-xsml-size">
					Note that this extension will affect all rooms in this booking
				</p>
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>


<!-- for bill-to-room status -->
<div id="modal-win-3" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-3" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-35 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="exform">
			<h2 class="large nobold default-text-font-bold">Confirm Request</h2><br>
			
			<div class="bottom-push-30">
				Are you sure you want to change bill-to-room status? Click <u>apply</u> to continue
				<input type="hidden" name="wgtfield2" id="wgtfield2">
			</div>

			<div id="fbutton" class="top-push-30" align="center">
				<input type="hidden" name="wgtag" id="wgtag" value="billtoroom">
				<input type="submit" name="submitbutton" value="Apply" class="nc-width-50 anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10"> <a href="javascript:void(0)" class="ft-xsml-size blue-font" onclick="xModal(3,'fx-position-flow right-layout motion',0)">Cancel</a>
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>


<!-- for bill-to-room service -->
<div id="modal-win-4" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-4" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-40 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="exform">
			<h2 class="large nobold default-text-font-bold">Apply bill-to-room service</h2><br>
			
			<div class="bottom-push-30">
				<?php echo $outletpack; ?>
				<input type="hidden" name="wgtfield3" id="wgtfield3">
			</div>

			<div id="fbutton" class="top-push-30" align="center">
				<input type="hidden" name="wgtagcustomer" id="wgtagcustomer" value="0">
				<input type="hidden" name="wgtag" id="wgtag" value="billtoroomservice">
				<input type="submit" name="submitbutton" value="Apply" class="nc-width-50 anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10"> <a href="javascript:void(0)" class="ft-xsml-size blue-font" onclick="xModal(4,'fx-position-flow right-layout motion',0)">Cancel</a>
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>


<!-- for room cancelling -->
<div id="modal-win-5" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-5" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-35 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="clform">
			
			<h2 id="roomlabel" class="large nobold default-text-font-bold alignct"></h2>
			<h4 class="large nobold">You are about to cancel this room. Select cancellation reason and charges</h4><br>
			
			<div class="bottom-push-30">
				<span class="ln-display-box float-left nc-width-50 right-pull-10">
					<select name="wgtcreason" id="wgtcreason" required>
						<option value="" selected="selected">Why?</option>
						<?php echo $cancelling_rs; ?>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-50 left-pull-10">
					<select name="wgtcpolicy" id="wgtcpolicy" required>
						<option value="" selected="selected">Charges Apply?</option>
						<?php echo $policy_select_opt; ?>
					</select>
				</span>
				<span class="block-element new-line-space"></span>
				<input type="hidden" name="wgtcroom" id="wgtcroom">
			</div>

			<div id="fbutton" class="top-push-30" align="center">
				<input type="hidden" name="wgtag" id="wgtag" value="cancelroom">
				<input type="submit" name="cancelroombutton" value="Apply" class="nc-width-50 anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10"> <a href="javascript:void(0)" class="ft-xsml-size blue-font" onclick="xModal(5,'fx-position-flow right-layout motion',0)">Cancel</a>
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>

<script src="../../js/webcam.js"></script>

<script>
	
	function ocf() {
		var box = document.getElementById('websnap');
		var frm = document.getElementById('snap-frame');

		chgclass('websnap','fx-position-stick zind-1 top-layout right-layout box-border-dark-thick motion');
		box.setAttribute('style','margin-top: 100px');
		frm.src = 'http://localhost/websnap/';
	}

	function startCamera() {
		Webcam.set({
			width: 350,
			height: 300,
			image_format: 'jpeg',
			jpeg_quality: 100,
			force_flash: false,
			constraints: { facingMode: 'user' }
		});
		
		Webcam.attach('#image-box');
		setTimeout(function() { chgclass('snap','block-element top-pull-15'); writeObjheader('snap','<a href="javascript:void(0)" onclick="capturePhoto()" class="ft-sml-size top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button">Capture Photo</a>'); },1000);
	}

	function capturePhoto() {
		 Webcam.snap( function(data_uri) {
        	var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById('image-box').innerHTML = '<img src="'+data_uri+'"/>';
        	document.getElementById('dataurl').value = raw_image_data;
        	chgclass('imbutton','top-push-20');
        	chgclass('snap','noshow');
    	} );
	}

	function confirmReservation(bookingno) {
		var con = confirm('Do you want to confirm this reservation to allow payment?');
		if(con == true) { window.location.href = window.location.href+'&cf=yes&cfbooking='+bookingno; }
	}

</script>