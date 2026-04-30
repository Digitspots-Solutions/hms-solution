<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;
include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_; include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

$toparentLog = true;
sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

//include "../../includes/uom.php";
include "../../includes/common_data_vars.php";

$tommorow = strtotime('1 day');
$indays_time = strtotime('2 days');
$logs = isset($_GET['logs']);

$payment_mode = select_dt_fetch('deletedata',0,$tbL24,'id','name');
$complimentary = select_dt_fetch('status','Active',$tbL33,'id','name');
$cspg = select_dt_fetch('status','Active',$tbL58,'id','name');

$posstore_constrain = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
mysqli_data_check($tbL14,'(*)',$posstore_constrain);
$total_pos_count = $numOfrows;

//include "frontdesk/process_booking.php";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js?ver=1.0"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element cs-height-70"></div>
<div class="block-element pads30">
	<div class="block-element">
		<span class="ln-display-box float-left nc-width-50 top-pull-7 bottom-pull-7">
			<div class="ft-xsml-size"><b class="mbri-user right-push-5"></b> Logged in as: <b class="nobold default-text-font-bold light-red-font"><?php echo $admin_name; ?></b></div>
			<h3 class="large nobold default-text-font-bold">Create New Booking</h3>
		</span>
		<span class="ln-display-box float-right nc-width-40 top-pull-7 alignrt">
			<a id="show-room-rate" href="javascript:void(0)" class="dark-black-white-state top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 right-push-7 ft-xsml-size xsml-rounded-button">Room Rate &nbsp; <b class="fa-home fa-color-strike-1 nobold"></b></a>
			<a href="javascript:void(0)" id="qsh" class="dark-black-white-state top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 ft-xsml-size xsml-rounded-button" onclick="objswitch('search-box','search-box','inn-search-box','fx-position-flow cs-width-600 cs-height-70 motion box-border-thick white-theme pads10','block-element','fx-position-flow csp-width-0 cs-height-0 motion','noshow')">Quick Search &nbsp; <b class="fa-search nobold"></b></a>
		</span>
		<span class="block-element new-line-space">
		</span>

		<div id="search-box" class="fx-position-flow csp-width-0 cs-height-0 motion" lang="uncollapsed">
			<div id="inn-search-box" class="noshow">
				<form action="" method="post" autocomplete="off" onsubmit="frontdkSearch(event)">
					<span class="ln-display-box float-left nc-width-40 right-push-30">
						<input type="text" name="search-field" id="search-field" placeholder="Type your keywords e.g booking number, name, phone number etc" required="required">
					</span>
					<span class="ln-display-box float-left nc-width-30 right-push-30">
						<select name="search-opt" id="search-opt" required="required">
							<option value="" selected="selected">Search What?</option>
							<option value="Bookings">Bookings</option>
							<option value="Rooms">Rooms</option>
							<option value="Guest">Guest</option>
						</select>
					</span>
					<span class="ln-display-box float-left nc-width-10">
						<input type="submit" name="searchbutton" value="Search" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button">
					</span>
					<span class="block-element new-line-space">
					</span>
				</form>
			</div>
		</div>
	</div>
	<div class="block-element top-pull-10">
		<form action="" method="post" onsubmit="" id="fbooking" autocomplete="off">
			<div class="ln-display-box float-left nc-width-60">
				<div class="block-element">
					<h4 class="large nobold steel-blue-font">Booking Type</h4><br>
					<span class="ln-display-box float-left">
						<div class="ln-display-box float-left right-push-10">
							<input type="radio" name="type-1" id="type-1" value="Individual" class="radio-option-default-custom" onclick="biller(1)">
						</div>
						<div class="ln-display-box float-left top-pull-5 right-push-20">
							<small>Individual</small>
						</div>
						<div class="ln-display-box float-left right-push-10">
							<input type="radio" name="type-2" id="type-2" value="Group" class="radio-option-custom" onclick="biller(2)">
							<input type="hidden" id="cspgcrlimit" value="0">
							<input type="hidden" id="cspgcrbal" value="0">
						</div>
						<div class="ln-display-box float-left top-pull-5 right-push-20">
							<small>Corporate/Spl. Guests</small>
						</div>
						<!--<div class="ln-display-box float-left right-push-10">
							<input type="radio" name="type-3" id="type-3" value="Agent" class="radio-option-custom" onclick="biller(3)">
						</div>
						<div class="ln-display-box float-left top-pull-5 right-push-20">
							<small>Agent</small>
						</div>-->
						<div class="ln-display-box float-left right-push-10">
							<input type="radio" name="type-4" id="type-4" value="Complimentary" class="radio-option-custom" onclick="biller(4)">
						</div>
						<div class="ln-display-box float-left top-pull-5 right-push-20">
							<small>Complimentary</small>
						</div>
						<div class="block-element new-line-space">
						</div>
					</span>
					<span class="block-element new-line-space">
					</span>
					<input type="hidden" name="customer-type" id="customer-type" value="individual">
				</div>
				<div id="biller" class="block-element">
					<div id="biller-type-1" class="noshow">
						<span class="ln-display-box float-left nc-width-30 right-push-20 top-pull-20">
							<small class="dark-grey-font">Complimentary <b class="mbri-right left-push-5"></b></small>
						</span>
						<span class="ln-display-box float-left nc-width-30 top-pull-15">
							<select name="complimentary" id="complimentary">
								<option value="" selected="selected">Choose</option>
								<?php echo $complimentary; ?>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div id="biller-type-2" class="noshow">
						<span class="ln-display-box float-left nc-width-30 right-push-20 top-pull-20">
							<small class="dark-grey-font">Corporate / Spl. Guests <b class="mbri-right left-push-5"></b></small>
						</span>
						<span class="ln-display-box float-left nc-width-40 top-pull-15">
							<div class="xform fx-position-rel">
								<span class="pads7"><input type="text" name="for-cspg" id="for-cspg" placeholder="Type to search.." class="nopads no-back-black" oninput="filtersearch(this.id)" onfocus="filtersearch(this.id)"></span>
								<span id="list-cspg" class="noshow"><select name="cspg" id="cspg" class="nopads no-back-black" onchange="filterselect(this.id); getcr(this.value)"><option value="" selected="selected" class="white-theme">&nbsp;</option><?php echo $cspg; ?></select></span>
							</div>			
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div id="biller-type-3" class="noshow">
						<span class="ln-display-box float-left nc-width-30 right-push-20 top-pull-20">
							<small class="dark-grey-font">Active Agents <b class="mbri-right left-push-5"></b></small>
						</span>
						<span class="ln-display-box float-left nc-width-30 top-pull-15">
							<select name="agent" id="agent">
								<option value="" selected="selected">Choose</option>
								<?php //echo $agent; ?>
							</select>			
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>
				<div class="block-element top-pull-20">
					<span class="ln-display-box float-left nc-width-45">
						<h4 class="large">Allow bill to room?</h4>
						<input type="radio" name="bill-to-room" value="Yes" onclick="showposShop('yes')"> Yes &nbsp; 
						<input type="radio" name="bill-to-room" value="No" checked="checked" onclick="showposShop('no')"> No

						<div id="show-pos-shop" class="noshow top-pull-15">
							<div class="black-theme cs-width-250 cs-height-40 sml-rounded-button noscroll">
								<span class="ln-display-box float-left right-push-10 top-pull-7 right-pull-10 bottom-pull-10 left-pull-10 box-border-dark-thick-right">
									<input type="checkbox" id="select-all" value="<?php echo $total_pos_count; ?>" class="checkbox-option-custom" onclick="checkallboxes('select-all','select-all','ps-','ss-label')" lang="c" checked="checked">
								</span>
								<span class="ln-display-box float-left top-pull-10 right-pull-10 bottom-pull-10 left-pull-3">
									<small id="ss-label" class="dark-grey-font">Pos Arenas: Deselect All</small>
								</span>
								<span class="block-element new-line-space"></span>
							</div>

							<br>

							<?php
								
								$get_posstores = mysqli_data_fetch($tbL14,'id,posname',$posstore_constrain,'array');

								if(is_array($get_posstores)) {
									$cnumbr = 0;
									foreach ($get_posstores as $poss_key => $poss_value) {
										$cnumbr += 1;
										?>
											<div class="ln-display-box float-left nc-width-50 bottom-push-10 ft-xsml-size">
												<span class="ln-display-box float-left nc-width-15">
													<input id="ps-<?php echo $cnumbr; ?>" type="checkbox" name="posstores" value="<?php echo $poss_value["id"]; ?>" checked="checked">
												</span>
												<span class="ln-display-box float-left nc-width-85 alignlt">
													<?php echo $poss_value["posname"]; ?>
												</span>
												<span class="block-element new-line-space"></span>
											</div>
										<?php
									}
								}

							?>
							<div class="block-element new-line-space">
							</div>
						</div>
					</span>
					<span class="ln-display-box float-right nc-width-45">
						<div id="not-group">
							<h4 class="large">Payment pay by?</h4>
							<input type="radio" name="payment-by" value="Group Owner" checked="checked"> Group Owner &nbsp; <input type="radio" name="payment-by" value="Guest"> Guests
						</div>
						<div id="for-group" class="noshow">
							<h4 class="large">Payment pay by?</h4>
							<input type="radio" name="payment-by2" value="Corporate" checked="checked"> Corporate &nbsp; <input type="radio" name="payment-by2" value="Guest"> Guests
						</div>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads15 top-push-20 bottom-push-20">
					<span class="ln-display-box float-left nc-width-25 right-push-20">
						<h4 class="large">Start from?</h4>
						<input type="text" name="checkin" id="checkin" value="<?php echo $server_get_date; ?>" class="top-push-5" required="required" readonly="readonly">
					</span>
					<span class="ln-display-box float-left nc-width-25 right-push-20">
						<h4 class="large">End to?</h4>
						<input type="text" name="checkout" id="checkout" value="<?php echo date('Y-m-d',strtotime($server_get_date . ' +1 days')); ?>" class="top-push-5" required="required" readonly="readonly" oninput="datechangeloadCustomerBill()" onblur="datechangeloadCustomerBill()">
					</span>
					<span class="ln-display-box float-left nc-width-15 right-push-20">
						<h4 class="large">Rooms?</h4>
						<select name="noofrooms" id="noofrooms" required="required" class="top-push-5" onchange="loadCustomerInfo('roomtype'); showformbutton()">
							<option value="" selected=""></option>
						</select>
					</span>
					<span class="ln-display-box float-left nc-width-20">
						<h4 class="large">Price?</h4>
						<small id="rm-price" class="block-element top-push-10">&#8358; 0.00</small>
						<small class="block-element ft-xxsml-size dark-grey-font">tax exclusive</small>
						<input type="hidden" name="unitprice" id="unitprice" value="0">
						<input type="hidden" name="totalprice" id="totalprice" value="0">
						<input type="hidden" name="noofdays" id="noofdays" value="0">
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
				<p class="alignrt right-pull-7"><small class="ft-xxsml-size">first information is tagged as primary guest:</small> &nbsp; <a href="javascript:void(0)" class="steel-blue-font ft-xxsml-size" id="rptr"><u>Repeat first info in all</u> &nbsp; <b class="fa-share nobold"></b></a></p>
				<div class="block-element sml-rounded-button noscroll top-push-7">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="100px" align="center">Room No#</th>
							<th width="70px" align="center">Title</th>
							<th width="100px" align="center">First Name</th>
							<th width="100px" align="center">Last Name</th>
							<th width="100px" align="center">Phone No#</th>
							<th width="100px" align="center">Email</th>
						</tr>
						<tbody id="datasheet"></tbody>
					</table>
					<input type="hidden" id="rwcounter" value="0">
					<div id="name-suggest" class="noshow motion"></div>
				</div>
					
				<div id="form-buttons" class="noshow top-pull-10">
					<span class="ln-display-box float-left nc-width-30">	
						<div class="ln-display-box float-left right-push-10">
							<input type="checkbox" name="temporary-reservation" id="temporary-reservation" value="no" lang="n" onclick="for_temprs()" class="">
						</div>
						<div class="ln-display-box float-left">
							<small class="red-font"> Temporary Reservation</small>
						</div>
						<div class="block-element new-line-space">
						</div>
						<div id="tr-box" class="noshow">
							<input type="text" name="tr-date" id="tr-date" placeholder="hold reservation till?" readonly="readonly">
						</div>
					</span>
					<span class="ln-display-box float-right nc-width-70 alignrt left-pull-50">
						<div class="noshow"><a id="cap" href="javascript:void(0)" class="blue-font ft-xsml-size" lang="n" onclick="for_advpay()"><b class="fa-money nobold"></b> Collect advance payment</a></div>
						<div id="cap-box" class="noshow top-push-3 top-pull-5 right-pull-5 bottom-pull-5 left-pull-5 box-border-thick sml-rounded-button">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="70px" align="center">Mode</td>
									<td width="60px" align="center">Amount</td>
									<td width="70px" align="center">Cheq. No</td>
									<!--<td width="70px" align="center">Receipt</td>-->
									<td width="90px" align="center">Detail</td>
								</tr>
								<tr>
									<td width="80px">
										<select name="payment-type" id="payment-type">
											<?php echo $payment_mode; ?>
										</select>
									</td>
									<td width="60px">
										<input type="text" name="amount-deposited" id="amount-deposited" pattern="\d*">
									</td>
									<td width="70px">
										<input type="text" name="cheque-number" id="cheque-number">
									</td>
									<!--<td width="70px">
										<input type="text" name="receipt" id="receipt">
									</td>-->
									<td width="90px">
										<input type="text" name="detail" id="detail">
									</td>
								</tr>
							</table>
						</div>
					</span>
					<span class="block-element new-line-space"></span>
					<span id="left-buttons" class="ln-display-box float-left nc-width-30 top-pull-20">
						<div id="show-left-buttons" class="noshow">
							<input type="button" name="tempreservationbutton" id="tempreservationbutton" value="Apply Temp. Reservation" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button" onclick="doBooking('Temp Reserve')">
						</div>
					</span>
					<span id="right-buttons" class="ln-display-box float-right nc-width-60 top-pull-20">
						<div id="show-right-buttons" class="alignrt">
							<input type="button" name="reservebutton" id="reservebutton" value="Reserve Booking" class="submit dark-black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button" onclick="doBooking('Reserving')"> &nbsp; <input type="button" name="checkinbutton" id="checkinbutton" value="CheckIn Booking" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button" onclick="doBooking('Checking In')">
						</div>
					</span>
					<span class="block-element new-line-space"></span>
				</div>
			</div>
			<div class="ln-display-box float-right nc-width-35">
				<div id="get-search-result"></div>
				<div id="addon-booking">
					<span id="owner-addon-booking"></span>
					<input type="hidden" name="onbH" id="onbH">
					<input type="hidden" name="vgc" id="vgc" value="0">
					<input type="hidden" name="fgc" id="fgc">
				</div>
				<div class="block-element box-border-thick pads20 sml-rounded-button obj-light-shadow">
					<div class="block-element bottom-pull-10 bottom-push-10">
						<small class="ft-xxsml-size dark-grey-font">Check-In: & Check-Out Time:</small>
						<small class="block-element top-push-5">
							<?php echo $the_hr_in[0].':'.$gh_checkin_time_min_name.' '.$the_hr_in[1]; ?> &mdash; <?php echo $the_hr_out[0].':'.$gh_checkout_time_min_name.' '.$the_hr_out[1]; ?>
						</small>
					</div>
					<div class="noshow bottom-push-5 alignrt">
						<span class="ln-display-box float-right">
							<small class="black-font">Apply Weekly Tariff</small>
						</span>
						<span class="ln-display-box float-right right-pull-5">
							<input type="checkbox" id="apply-wkly-tariff" value="No" onclick="towkt()">
							<input type="hidden" name="hotelseason" id="hotelseason" value="0">
							<input type="hidden" name="hotelseasonday" id="hotelseasonday" value="defaultday">
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>

					<select name="roomtype" id="roomtype" class="top-push-5 nopads no-back-black" onchange="getJson_data('room-type-detail','frontdesk-list-room-type-detail','roomtype'); setTimeout(() => { document.getElementById('type-1').disabled='disabled'; document.getElementById('type-2').disabled='disabled'; document.getElementById('type-4').disabled='disabled'; },1000)">
						<option value="" selected="selected">Choose Room Type</option>
						<?php select_fetch('status','Active',$tbL52,'id','name'); ?>
					</select>
					<div id="room-type-detail" class="block-element left-pull-5 right-pull-5 bottom-push-30">
					</div>
					
					<input type="hidden" name="wgt-discount" id="wgt-discount" value="0">
					<input type="hidden" name="rsvat" id="rsvat" value="0">
					<input type="hidden" name="rsschg" id="rsschg" value="0">
					<input type="hidden" name="rsctax" id="rsctax" value="0">
					
					<?php if($htx3 == 1): ?>
					<div id="w-vat">
						<span class="ln-display-box float-left nc-width-50 pads10 bottom-push-10">
							<h4 class="xlarge nobold">Vat</h4>
						</span>
						<span class="ln-display-box float-left nc-width-30 pads10 box-border-thick bottom-push-10">
							<input type="text" name="wgt-tax" id="wgt-tax" value="0" class="nopads no-back-black" readonly>
						</span>
						<span class="ln-display-box float-left nc-width-20 dark-black-white-state right-border-radius-7 pads10 alignct anchor bottom-push-10" onclick="removechild('w-vat','wgt-tax')" title="Remove this charge">
							<b class="ft-sml-size nobold default-text-font-bold">x</b>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<?php else: ?>
						<input type="hidden" name="wgt-tax" id="wgt-tax" value="0" class="nopads no-back-black" readonly>
					<?php endif; ?>

					<?php if($htx2 == 1): ?>
					<div id="w-service">
						<span class="ln-display-box float-left nc-width-50 pads10 bottom-push-10">
							<h4 class="xlarge nobold">Service Charge</h4>
						</span>
						<span class="ln-display-box float-left nc-width-30 pads10 box-border-thick bottom-push-10">
							<input type="text" name="wgt-service-charge" id="wgt-service-charge" value="0" class="nopads no-back-black" readonly>
						</span>
						<span class="ln-display-box float-left nc-width-20 dark-black-white-state right-border-radius-7 pads10 alignct anchor bottom-push-10" onclick="removechild('w-service','wgt-service-charge')" title="Remove this charge">
							<b class="ft-sml-size nobold default-text-font-bold">x</b>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<?php else: ?>
						<input type="hidden" name="wgt-service-charge" id="wgt-service-charge" value="0" class="nopads no-back-black" readonly>
					<?php endif; ?>
					
					<?php if($htx1 == 1): ?>
					<div id="w-consumption">
						<span class="ln-display-box float-left nc-width-50 pads10 bottom-push-10">
							<h4 class="xlarge nobold">Consumption</h4>
						</span>
						<span class="ln-display-box float-left nc-width-30 pads10 box-border-thick bottom-push-10">
							<input type="text" name="wgt-consumption" id="wgt-consumption" value="0" class="nopads no-back-black" readonly>
						</span>
						<span class="ln-display-box float-left nc-width-20 dark-black-white-state right-border-radius-7 pads10 alignct anchor bottom-push-10" onclick="removechild('w-consumption','wgt-consumption')" title="Remove this charge">
							<b class="ft-sml-size nobold default-text-font-bold">x</b>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<?php else: ?>
						<input type="hidden" name="wgt-consumption" id="wgt-consumption" value="0" class="nopads no-back-black" readonly>
					<?php endif; ?>

					<div class="block-element left-pull-5 right-pull-5 top-push-7 bottom-push-10">
						<h4 class="large nobold light-red-font"><u>Note</u>: Select the room-type then, you can remove the charges you do not want to apply</h4>
					</div>
					
				</div>
				<div class="block-element top-push-20 box-border-thick pads20 sml-rounded-button obj-light-shadow">
					<h4 class="large nobold right-pull-10 left-pull-10 default-text-font-bold">Daily Routine<b class="mbri-plus float-right"></b></h4><div class="cs-height-10"></div>
					<ul class="nolist">
						<li class="white-grey-state sml-rounded-button top-pull-5 right-pull-7 bottom-pull-5 left-pull-10 motion ft-xsml-size">
							<a id="show-house-status" href="javascript:void(0)" class="blue-font" onclick="">House Status</a><b class="mbri-right float-right"></b>
						</li>
						<li class="white-grey-state sml-rounded-button top-pull-5 right-pull-7 bottom-pull-5 left-pull-10 motion ft-xsml-size">
							<a id="show-weekly-tariff" href="javascript:void(0)" class="blue-font">Weekly Tariff</a><b class="mbri-right float-right"></b><!--onclick="showWkTr()"-->
						</li>
						<li class="white-grey-state sml-rounded-button top-pull-5 right-pull-7 bottom-pull-5 left-pull-10 motion ft-xsml-size">
							<a id="show-checkin-checkout" href="javascript:void(0)" class="blue-font" onclick="">Today's Checkin/Checkout</a><b class="mbri-right float-right"></b>
						</li>
					</ul>
				</div>
			</div>
			<div class="block-element new-line-space">
			</div>
		</form>
	</div>

	<br><br><br><br>
	<br><br><br><br>
	<br><br><br><br>
	<br><br><br><br>
	<br><br><br><br>
</div>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr top-push-50 top-pull-50" align="right">
	<div class="cs-width-400 light-yellow-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="fo-header-notification" class="large red-font"></h4>
		<small id="fo-message-notification" class="block-element top-push-10"></small>
	</div>
</div>

<div id="other-notifybox" class="fx-position-flow zind-2 motion btscr noscroll" align="left">
	<div id="frame-box" class="noshow">
		<span class="ln-display-box float-left nc-width-50">
			<small id="rlt-msg" class="white-font noshow">Loading..</small>
		</span>
		<span class="ln-display-box float-right">
			<a href="javascript:void(0)" class="ft-xsml-size black-font" onclick="closeWkTr()">X Close</a>
		</span>
		<span class="block-element new-line-space">
		</span>
		<div id="rlt-work-area" class="block-element white-theme top-push-5 bottom-push-5 noscroll">
		</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="../assets/dhxcalendar/fonts/font_roboto/roboto.css"/>
<link rel="stylesheet" type="text/css" href="../assets/dhxcalendar/dhtmlxcalendar.css"/>
<script src="../assets/dhxcalendar/dhtmlxcalendar.js"></script>

<style>
	#calendar,
	#calendar2,
	#calendar3 {
		border: 1px solid #dfdfdf;
		font-family: Roboto, Arial, Helvetica;
		font-size: 14px;
		color: #404040;
	}
</style>

<div id="for-roomtype-selector" class="noshow fx-position-flow fscr zind-3 pads30 motion" align="center">
	<div class="cs-height-200"></div>
	<div class="cs-width-300 white-theme mini-rounded-button box-border-thick obj-light-shadow">
		<div class="box-border-thick-bottom pads15">
			<span class="float-left nc-width-90">
				<input type="text" name="wishselector" id="wishselector" placeholder="Type to search or choose?" class="nopads no-back-black" onkeyup="rlookup(this.value)">
			</span>
			<span class="float-right nc-width-10 alignct top-pull-3">
				<a title="Click to close room list" href="javascript://" class="blue-font" onclick="chgclass('for-roomtype-selector','noshow fx-position-flow fscr zind-3 pads30 motion'); writeObjheader('roomtype-stack',''); htmlpassval('','wishselector')"><b class="mbri-close"></b></a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</div>
		<div id="roomtype-stack" class="cs-height-200 motion y-scroll">
			<p class="alignct pads15 dark-grey-font">Showing room list..</p>
		</div>
	</div>
</div>

<script>
	
	//Ask to reload page after 3 mins

	sessionStorage.setItem('workaround',0);

	const screen_timer = {"timing":0}
	const screen_stop = 300;

	var ifidle = setInterval(() => {
		if(screen_timer.timing < screen_stop) {
			//console.log(screen_timer.timing);
			screen_timer.timing = Number(screen_timer.timing) + 1;
		} else {
			screen_timer.timing = 0;
			var conf = confirm('System detects your dashboard has been idle. It is recommended that you reload your dashboard at this moment');

			if(conf === true) { window.location.reload(true); }
			else { screen_timer.timing = 0; }
		}
	},1000);

	setInterval(() => {
		var wka;
		wka = sessionStorage.getItem('workaround');
		wka = Number(wka) + 1;
		sessionStorage.setItem('workaround',wka);
	},1000);


	document.addEventListener("mousemove", (event) => {
		var e = event || window.event;
		if(e.clientX || e.clientY) { screen_timer.timing = 0; sessionStorage.setItem('workaround',0); }
	});

	document.addEventListener("keypress", (event) => {
		var e = event || window.event;
		if(e.code || e.which) { screen_timer.timing = 0; sessionStorage.setItem('workaround',0); }
	});


	//-----------------------------------------------------------------------------------------

	function rlookup(val) {

		if(val !== null && val !== undefined) {
			
			var rr = document.getElementsByClassName('rrl');
			var parentObj = document.getElementById('roomtype-stack').offsetTop;

			for(var j=0; j < rr.length; j++) {
				var haystack = rr[j].getAttribute('data-val');
				var needle = val;

				if(haystack.indexOf(needle) > -1) {
					var riD = rr[j].getAttribute('id');
					var pos = document.getElementById(riD).offsetTop; pos = Number(pos) - Number(parentObj);
					document.getElementById('roomtype-stack').scrollTop = pos;
					haystack = ""; needle = "";
					break;
				}
			}
		}
	}

	//-----------------------------------------------------------------------------------------

	const rrString = [];
	const checkedrooms = [];

	function rrPop(obj) {

		var roomid = obj.getAttribute('data-id');
		var roomno = obj.getAttribute('data-val');
		var field1 = rrString[0], field2 = rrString[1];

		var index = checkedrooms.map(obj => obj.room).indexOf(roomid);

		if(index > -1) {
			alert('Room already in the selection. Select a different room');
		} else {
			checkedrooms.push({"room":roomid});
			document.getElementById(field1).value = roomid;
			document.getElementById(field2).value = roomno;
		}

		chgclass('for-roomtype-selector','noshow fx-position-flow fscr zind-3 pads30 motion');
		writeObjheader('roomtype-stack','<p class="alignct pads15 dark-grey-font">Showing room list..</p>');
		htmlpassval('','wishselector');
	}

	//-----------------------------------------------------------------------------------------

	function roomlocker(obj) {
		
		var clickobj = obj.getAttribute('data-rno');
		var idobj = obj.getAttribute('data-rid');

		rrString.splice(0,rrString.length);
		rrString.push(idobj);
		rrString.push(clickobj);

		var get_roomtype = obj.getAttribute('data-rty');
		
		sqldatastring.sql = "SELECT a.roomprefix, a.roomnumber, a.id, b.housekeeping_stateid, c.legendname FROM room_tbl a, housekeeping_room_state_tbl b, housekeeping_legend_tbl c WHERE a.room_type_id="+get_roomtype+" AND a.roomstatus=1 AND a.deletedata=0 AND a.id=b.roomid AND b.room_status_id NOT IN(3,6) AND b.housekeeping_stateid=c.id ORDER BY roomnumber ASC";
		
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;
			
			vhtml = '<ul class="nolist">';
			
			for(i=0; i < data.length; i++) {
				if(data[i].housekeeping_stateid == 1) {
					vhtml += '<li id="r-'+data[i].roomnumber+'" data-val="'+data[i].roomprefix+data[i].roomnumber+'" data-id="'+data[i].id+'" class="box-border-thick-bottom anchor top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 white-grey-state ft-sml-size default-text-font-bold alignlt rrl" onclick="rrPop(this)"><span class="float-right"><input type="button" value="'+data[i].legendname.toUpperCase()+'"></span><b class="mbri-success right-push-10"></b>'+data[i].roomprefix+data[i].roomnumber+'</li>';
				} else {
					vhtml += '<li id="r-'+data[i].roomnumber+'" data-val="'+data[i].roomprefix+data[i].roomnumber+'" data-id="'+data[i].id+'" class="box-border-thick-bottom anchor top-pull-7 right-pull-15 bottom-pull-7 left-pull-15 white-grey-state ft-sml-size default-text-font-bold dark-grey-font alignlt rrl"><span class="float-right red-font default-text-font">'+data[i].legendname.toUpperCase()+'</span><b class="mbri-close right-push-10"></b>'+data[i].roomprefix+data[i].roomnumber+'</li>';
				}
			}
			
			vhtml += '</ul>';

			writeObjheader('roomtype-stack',vhtml);
		}
		

		chgclass('for-roomtype-selector','fx-position-flow fscr zind-3 pads30 txp5-white motion');
		document.getElementById('wishselector').focus();
	}

	//-----------------------------------------------------------------------------------------

	function removechild(obj,objx) {
		chgclass(obj,'noshow');
		document.getElementById(objx).value = 0;
	}

	function addGuest2Bk(str) {
		var ag = document.getElementById('addguest');
		ag.lang = str;

		setTimeout(function() {
			ag.click();
		},1000);
	}

	//-----------------------------------------------------------------------------------------

	function showformbutton() {
		var price_per_room = document.getElementById('unitprice').value;
		if(price_per_room != '' && price_per_room > 0) { objDisplay('form-buttons'); }
	}

	//-----------------------------------------------------------------------------------------

	function frontdkSearch(e) {
		
		e.preventDefault();

		var xhr,file,random_numbr,ajaxson,result;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		var string_1 = document.getElementById('search-field').value;
		var string_2 = document.getElementById('search-opt').value;
		
		chgclass('get-search-result','top-bottom-15 ft-xsml-size');
		writeObjheader('get-search-result','Searching, please wait..');

		file = phpfile+"dbquery.php?r=frontdesk-search-data&keywords="+string_1+"&src="+string_2+"&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					writeObjheader('get-search-result',xhr.responseText);
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}

	//-----------------------------------------------------------------------------------------

	function towkt() {
		var wktr = document.getElementById('apply-wkly-tariff');
		if(wktr.value == 'No') { wktr.value = 'Yes'; wktr.checked = true; }
		else if(wktr.value == 'Yes') { wktr.value = 'No'; wktr.checked = false; }

		//console.log(wktr.value);
	}

	function showWkTr() {
		chgclass('other-notifybox','fx-position-stick zind-2 motion fscr txp5-white noscroll');
		chgclass('frame-box','block-element pads20');
		
		var newframe = document.createElement('iframe');
		newframe.id = 'frame1';
		newframe.name = 'frame1';
		newframe.frameBorder = 0;
		newframe.marginWidth = 0;
		newframe.marginHeight = 0;
		newframe.width = '100%';
		newframe.height = '90%';
		newframe.scrolling = 'auto';

		document.getElementById('rlt-work-area').innerHTML = '';
		document.getElementById('rlt-work-area').appendChild(newframe);
		objDisplay('rlt-msg'); newframe.src = "weekly_tariff.php";
		newframe.onload = function() { objHidden('rlt-msg'); }
	}

	function closeWkTr() {
		chgclass('other-notifybox','fx-position-flow zind-2 motion btscr noscroll');
		chgclass('frame-box','noshow');
	}

	//-----------------------------------------------------------------------------------------

	function biller(b) {
		if(b == 1) {
			htmlpassval('individual','customer-type');
			objHidden('biller-type-1');
			objHidden('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-default-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('not-group');
			objHidden('for-group');
			chgclass('checkinbutton','submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button');
			document.getElementById('temporary-reservation').disabled = false;
		} else if(b == 2) {
			htmlpassval('corporate','customer-type');
			objHidden('biller-type-1');
			objDisplay('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-default-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('for-group');
			objHidden('not-group');
			chgclass('checkinbutton','noshow');
			document.getElementById('temporary-reservation').disabled = true;
		} else if(b == 3) {
			htmlpassval('agent','customer-type');
			objHidden('biller-type-1');
			objHidden('biller-type-2');
			objDisplay('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-default-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('not-group');
			objHidden('for-group');
			chgclass('checkinbutton','noshow');
			document.getElementById('temporary-reservation').disabled = true;
		} else if(b == 4) {
			htmlpassval('complimentary','customer-type');
			objDisplay('biller-type-1');
			objHidden('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-default-custom');
			objHidden('not-group');
			objHidden('for-group');
			chgclass('checkinbutton','submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button');
			document.getElementById('temporary-reservation').disabled = true;
		}
	}

	function getcr(cspgid) {

		sqldatastring.sql = "SELECT * FROM cspg_tbl WHERE id="+cspgid;
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;
			htmlpassval(data[0]['xcreditlimit'],'cspgcrlimit');
			htmlpassval(data[0]['notifylimit'],'cspgcrbal');
		}
	}

	//-----------------------------------------------------------------------------------------

	function showposShop(str) {
		var csp = document.getElementById('customer-type').value;
		if(str == 'yes') {
			if(csp == 'individual' || csp == 'Individual' || csp == 'Complimentary' || csp == 'complimentary') {
				objDisplay('show-pos-shop');
			} else {
				objDisplay('notifybox');
				writeObjheader('fo-header-notification','Notification!');
				writeObjheader('fo-message-notification','Biller already have setup pos package');
				autohidePopupBox('notifybox',4000);
			}
		} else if(str == 'no') {
			objHidden('show-pos-shop');
		}
	}

	//-----------------------------------------------------------------------------------------

	function for_temprs() {
		temprs = document.getElementById('temporary-reservation');
		if(temprs.lang == 'n') {
			temprs.lang = 'y';
			temprs.value = 'yes';
			objDisplay('tr-box');
			document.getElementById('tr-date').required = true;
			objDisplay('show-left-buttons');
			objHidden('show-right-buttons');
		} else if(temprs.lang == 'y') {
			temprs.lang = 'n';
			temprs.value = 'no';
			objHidden('tr-box');
			document.getElementById('tr-date').required = false;
			objHidden('show-left-buttons');
			objDisplay('show-right-buttons');
		}
	}

	//-----------------------------------------------------------------------------------------

	function for_advpay() {
		var cap = document.getElementById('cap');
		if(cap.lang == 'n') {
			cap.lang = 'y';
			objDisplay('cap-box');
			document.getElementById('payment-type').required = true;
			document.getElementById('amount-deposited').required = true;
			document.getElementById('receipt').required = true;
		} else if(cap.lang == 'y') {
			cap.lang = 'n';
			objHidden('cap-box');
			document.getElementById('payment-type').required = false;
			document.getElementById('amount-deposited').required = false;
			document.getElementById('receipt').required = false;
		}
	}

	//-----------------------------------------------------------------------------------------

	/*function for_compl() {
		var compl = document.getElementById('compl');
		if(compl.lang == 'n') {
			compl.lang = 'y';
			objDisplay('show-complimentary');
			objHidden('cap');
			objHidden('cap-box');
			document.getElementById('complimentary').required = true;
			document.getElementById('payment-type').required = false;
			document.getElementById('amount-deposited').required = false;
			document.getElementById('receipt').required = false;
			htmlpassval('complimentary','customer-type');
		} else if(compl.lang == 'y') {
			compl.lang = 'n';
			objHidden('show-complimentary');
			objDisplay('cap');
			objDisplay('cap-box');
			document.getElementById('show-complimentary').required = false;
			htmlpassval('individual','customer-type');
		}
	}*/

	//-----------------------------------------------------------------------------------------

	function jsName(ids,name) {
		var jns = name.split(' @');
		var jn = jns[0].split(' ');
		var obj = ids.split('/');
		document.getElementById(obj[0]).value = jn[0];
		document.getElementById(obj[1]).value = jn[1];
		document.getElementById(obj[2]).value = jns[1];
	}

	function jsGi(obj) {
		document.getElementById('fgc').value = obj.getAttribute('data-gi');
	}

	function nJs() {
		chgclass('name-suggest','noshow motion');
	}

	function loadCustomerInfo(roomtype) {
		
		var no_of_rooms = document.getElementById('noofrooms').value;
		var price_per_room = document.getElementById('unitprice').value;

		if(price_per_room != '' && price_per_room > 0) {

			var show_room_totalprice = document.getElementById('rm-price');
			var totalprice = document.getElementById('totalprice');
			var the_no_of_days = document.getElementById('noofdays');

			var checkin = document.getElementById('checkin').value;
			var checkout = document.getElementById('checkout').value;

			var date1 = checkin.replace(/-/g,'/');
			var date2 = checkout.replace(/-/g,'/');
			
			var day_from = new Date(date1);
			var day_to = new Date(date2);
			
			var timeDiff = Math.abs(day_to.getTime() - day_from.getTime());
			var no_of_days = Math.ceil(timeDiff / (1000 * 3600 * 24));

			var bill = (eval(no_of_rooms) * eval(price_per_room)) * no_of_days;
			show_room_totalprice.innerHTML = '&#8358; '+numberFormat(bill);
			totalprice.value = bill;
			the_no_of_days.value = no_of_days;

			var ph_rsvat, ph_rsctax, ph_rsschg;

			if(sessionStorage.getItem('phrsvat') !== null && sessionStorage.getItem('phrsvat') !== undefined) {
				ph_rsvat = sessionStorage.getItem('phrsvat'); } else { ph_rsvat = 0; }
		
			if(sessionStorage.getItem('phrsschg') !== null && sessionStorage.getItem('phrsschg') !== undefined) { ph_rsschg = sessionStorage.getItem('phrsschg'); } else { ph_rsschg = 0; }

			if(sessionStorage.getItem('phrsctax') !== null && sessionStorage.getItem('phrsctax') !== undefined) { ph_rsctax = sessionStorage.getItem('phrsctax'); } else { ph_rsctax = 0; }

			if(ph_rsvat && ph_rsvat > 0) {
				var new_ct1 = (eval(no_of_rooms) * eval(ph_rsvat)) * no_of_days;
				document.getElementById('rsvat').value = new_ct1;
			}

			if(ph_rsctax && ph_rsctax > 0) {
				var new_ct2 = (eval(no_of_rooms) * eval(ph_rsctax)) * no_of_days;
				document.getElementById('rsctax').value = new_ct2;
			}

			if(ph_rsschg && ph_rsschg > 0) {
				var new_ct3 = (eval(no_of_rooms) * eval(ph_rsschg)) * no_of_days;
				document.getElementById('rsschg').value = new_ct3;
			}

			//start table row creation

			var r,curnumbr,contr;

			curnumbr = document.getElementById('rwcounter');
			contr = document.getElementById('datasheet');
			contr.innerHTML = '';

			for(r=1; r <= no_of_rooms; r++)
			{
				var uni_id = eval(curnumbr.value) + 1; //generate new id this row

				var tr = document.createElement('tr');
				var td1 = document.createElement('td');
				var td2 = document.createElement('td');
				var td3 = document.createElement('td');
				var td4 = document.createElement('td');
				var td5 = document.createElement('td');
				var td6 = document.createElement('td');

				var txt1 = document.createElement('input');
				var txt2 = document.createElement('input');
				var txt3 = document.createElement('input');
				var txt4 = document.createElement('input');
				var txt5 = document.createElement('input');

				var dropbox1 = document.createElement('input');
				var dropbox1x = document.createElement('input');
				var dropbox2 = document.createElement('select');
				var opt1 = document.createElement('option');
				var opt2 = document.createElement('option');

				dropbox1.id = "select-col-1-"+uni_id;
				dropbox1x.id = "select-col-1x-"+uni_id;
				dropbox1.name = "roomnumber[]";
				dropbox1x.name = "roomselect[]";
				dropbox1.type = "hidden";
				dropbox1.value = 0;
				dropbox1x.type = "text";
				dropbox1x.placeholder = "Pick Room?";
				dropbox1x.setAttribute('data-rty',document.getElementById('roomtype').value);
				dropbox1x.setAttribute('data-rid','select-col-1-'+uni_id);
				dropbox1x.setAttribute('data-rno','select-col-1x-'+uni_id);
				dropbox1x.setAttribute('readonly','readonly');
				dropbox1x.setAttribute('title','Click to select a room');
				dropbox1x.setAttribute('onclick','roomlocker(this)');
				//dropbox1.required = "required";
				//opt1.value = "";
				//opt1.text = "";
				//dropbox1.appendChild(opt1);
				td1.appendChild(dropbox1);
				td1.appendChild(dropbox1x);
				

				/*dropbox1.onchange = function() {
					check_room_enabled(this.value,roomtype);
					sessionStorage.setItem('thisrow','select-col-1-'+uni_id);

					var index = checkedrooms.map(obj => obj.room).indexOf(this.value);

					if(index > -1) {
						alert('Room already in the selection. Select different room');
						var decr = this.id; $('#'+decr).prop('selectedIndex', 0);
					} else {
						checkedrooms.push({"room":this.value});
					}
				}*/


				dropbox2.id = "select-col-2-"+uni_id;
				dropbox2.name = "title[]";
				dropbox2.required = "required";
				opt2.value = "";
				opt2.text = "";
				dropbox2.appendChild(opt2);
				td2.appendChild(dropbox2);

				txt1.id = "input-col-"+uni_id;
				txt1.type = 'hidden';
				txt1.name = 'customerid[]';

				txt2.id = "input-col-3-"+uni_id;
				txt2.type = 'text';
				txt2.name = 'firstname[]';
				txt2.required = "required";
				txt2.onkeyup = function() {
					var guest = document.getElementById('input-col-3-1').value;
					chgclass('name-suggest','fx-position-flow zind-3 cs-width-300 cs-height-350 white-theme obj-light-shadow sml-rounded-button pads20 top-push-20 y-scroll motion');
					sqldatastring.sql = "SELECT * FROM guest_tbl WHERE fname REGEXP '^"+guest+"' OR lname REGEXP '^"+guest+"'";
					sqldataQuery(wgtpop,sqldatastring);

					function wgtpop(response) {
						var i, vhtml, data, ajaxresult = JSON.parse(response);
						data = ajaxresult.datastring;

						vhtml = '<span class="float-right"><a href="javascript:nJs()" class="dark-grey-font"><b class="mbri-close"></b></a></span>';
						vhtml += '<h4 class="xlarge nobold black-font">Go by related names</h4><br>';

						for(i=0; i<data.length; i++) {
							vhtml += '<div class="bottom-push-10 anchor" lang="input-col-3-1/input-col-4-1/input-col-5-1" title="'+data[i].fname+' '+data[i].lname+' @'+data[i].mobile+'" data-gi="'+data[i].id+'" onclick="jsName(this.lang,this.title); jsGi(this)">';
							vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
							vhtml += '<h3 class="large nobold default-text-font-bold">'+data[i].fname+' '+data[i].lname+' ('+data[i].guest_code+data[i].id+')</h3>';
							vhtml += '</div>';
						}

						document.getElementById('name-suggest').innerHTML = vhtml;
					}
				}
				td3.appendChild(txt1);
				td3.appendChild(txt2);

				txt3.id = "input-col-4-"+uni_id;
				txt3.type = 'text';
				txt3.name = 'lastname[]';
				txt3.required = "required";
				td4.appendChild(txt3);

				txt4.id = "input-col-5-"+uni_id;
				txt4.type = 'text';
				txt4.name = 'phonenumber[]';
				txt4.required = "required";
				td5.appendChild(txt4);

				txt5.id = "input-col-6-"+uni_id;
				txt5.type = 'email';
				txt5.name = 'emailaddress[]';
				td6.appendChild(txt5);

				tr.appendChild(td1);
				tr.appendChild(td2);
				tr.appendChild(td3);
				tr.appendChild(td4);
				tr.appendChild(td5);
				tr.appendChild(td6);

				contr.appendChild(tr);
				curnumbr.value = uni_id;

				//setTimeout(function() {
					//dodata('select-col-1-'+uni_id,'eget-rooms','roomtype','dropbox');
					dodata('select-col-2-'+uni_id,'eget-salutations',1,'dropbox');
				//},1000);

			}

			var infor_repeater = document.getElementById('rptr');
			infor_repeater.addEventListener('click',function() {
				var r, numbr = curnumbr.value;
				for(r=1; r <= numbr; r++) {
					if(r >= 2) {
						document.getElementById('select-col-2-'+r).value = document.getElementById('select-col-2-1').value;
						document.getElementById('input-col-3-'+r).value = document.getElementById('input-col-3-1').value;
						document.getElementById('input-col-4-'+r).value = document.getElementById('input-col-4-1').value;
						document.getElementById('input-col-5-'+r).value = document.getElementById('input-col-5-1').value;
						document.getElementById('input-col-6-'+r).value = document.getElementById('input-col-6-1').value;
					}
				}
			},false);

			if(document.getElementById('addguest')) {
				var ag = document.getElementById('addguest');
				ag.addEventListener('click',function() {
					var lng = ag.lang;
					var gdt = (document.getElementById('p'+lng).value).split('/');
					//var fln = new String(gdt[0]), gfln = fln.split(' ');
					document.getElementById('vgc').value = gdt[0];
					document.getElementById('input-col-3-1').value = gdt[3];
					document.getElementById('input-col-4-1').value = gdt[4];
					document.getElementById('input-col-5-1').value = gdt[5];
					document.getElementById('input-col-6-1').value = gdt[6];
					document.getElementById('select-col-2-1').getElementsByTagName('option')[0].value = gdt[1];
					document.getElementById('select-col-2-1').getElementsByTagName('option')[0].text = gdt[2];

					document.getElementById('input-col-3-1').setAttribute('readonly','readonly');
					document.getElementById('input-col-4-1').setAttribute('readonly','readonly');
					document.getElementById('input-col-5-1').setAttribute('readonly','readonly');

				},false);
			}
		}
	}

	//-----------------------------------------------------------------------------------------

	function datechangeloadCustomerBill() {
		
		var no_of_rooms = document.getElementById('noofrooms').value;
		var price_per_room = document.getElementById('unitprice').value;

		if(price_per_room != '' && price_per_room > 0) {
			
			var show_room_totalprice = document.getElementById('rm-price');
			var totalprice = document.getElementById('totalprice');
			var the_no_of_days = document.getElementById('noofdays');

			var checkin = document.getElementById('checkin').value;
			var checkout = document.getElementById('checkout').value;

			var date1 = checkin.replace(/-/g,'/');
			var date2 = checkout.replace(/-/g,'/');
			
			var day_from = new Date(date1);
			var day_to = new Date(date2);
			
			var timeDiff = Math.abs(day_to.getTime() - day_from.getTime());
			var no_of_days = Math.ceil(timeDiff / (1000 * 3600 * 24));

			var bill = (eval(no_of_rooms) * eval(price_per_room)) * no_of_days;
			show_room_totalprice.innerHTML = '&#8358; '+numberFormat(bill);
			totalprice.value = bill;
			the_no_of_days.value = no_of_days;

			var ph_rsvat, ph_rsctax, ph_rsschg;

			if(sessionStorage.getItem('phrsvat') !== null && sessionStorage.getItem('phrsvat') !== undefined) {
				ph_rsvat = sessionStorage.getItem('phrsvat'); } else { ph_rsvat = 0; }
		
			if(sessionStorage.getItem('phrsschg') !== null && sessionStorage.getItem('phrsschg') !== undefined) { ph_rsschg = sessionStorage.getItem('phrsschg'); } else { ph_rsschg = 0; }

			if(sessionStorage.getItem('phrsctax') !== null && sessionStorage.getItem('phrsctax') !== undefined) { ph_rsctax = sessionStorage.getItem('phrsctax'); } else { ph_rsctax = 0; }

			if(ph_rsvat && ph_rsvat > 0) {
				var new_ct1 = (eval(no_of_rooms) * eval(ph_rsvat)) * no_of_days;
				document.getElementById('rsvat').value = new_ct1;
			}

			if(ph_rsctax && ph_rsctax > 0) {
				var new_ct2 = (eval(no_of_rooms) * eval(ph_rsctax)) * no_of_days;
				document.getElementById('rsctax').value = new_ct2;
			}

			if(ph_rsschg && ph_rsschg > 0) {
				var new_ct3 = (eval(no_of_rooms) * eval(ph_rsschg)) * no_of_days;
				document.getElementById('rsschg').value = new_ct3;
			}

			var checkout = document.getElementById('checkout').blur();
		}
	}

	//-----------------------------------------------------------------------------------------

	var myCalendarIn,myCalendarOut;
	
	function initializeCalendar() {
		myCalendarIn = new dhtmlXCalendarObject(["checkin"]);
		myCalendarOut = new dhtmlXCalendarObject(["checkout"]);
		myCalendarRs = new dhtmlXCalendarObject(["tr-date"]);
	}

	
	function setFrom() {
		myCalendarIn.setSensitiveRange("<?php echo date("Y-m-d"); ?>",null);
		myCalendarOut.setSensitiveRange("<?php echo date("Y-m-d",$tommorow); ?>",null);
		myCalendarRs.setSensitiveRange("<?php echo date("Y-m-d",$tommorow); ?>",null);
		//myCalendar.setInsensitiveRange(null,"2011-07-07"); // the same
	}


	function doBooking(thebooking) {
		
		const htx1 = "<?php echo $htx1; ?>";
		const htx2 = "<?php echo $htx2; ?>";
		const htx3 = "<?php echo $htx3; ?>";

		var php_val_1 = document.getElementById('wgt-service-charge').value;
		var php_val_2 = document.getElementById('wgt-tax').value;
		var php_val_3 = document.getElementById('wgt-consumption').value;
		var php_val_4 = document.getElementById('wgt-discount').value;

		parent.document.getElementById('for-booking').className = 'fx-position-stick fscr zind-2 motion txp8-black';
		parent.document.getElementById('inn-for-booking').className = 'block-element';
		parent.document.getElementById('th-nbk').innerHTML='<h2 class="large nobold default-text-font-bold alignct black-font">Wait while system prepares booking..</h2>';

		setTimeout(function() {
			
			var bookingString,th_booking_type,th_biller,th_payby,th_billroom,th_roomservice,th_checkin,th_checkout,th_noofrooms,th_rate,th_total,th_stay,th_roomtype,th_temp_reserve_date,th_paymode,th_amount,th_chq,th_remark,foraddonbkg,forfgc,forvgc;
			
			foraddonbkg = document.getElementById('onbH').value;
			forvgc = document.getElementById('vgc').value;
			forfgc = document.getElementById('fgc').value;

			th_booking_type = document.getElementById('customer-type').value;
			th_checkin = document.getElementById('checkin').value;
			th_checkout = document.getElementById('checkout').value;
			th_noofrooms = document.getElementById('noofrooms').value;
			th_rate = document.getElementById('unitprice').value;
			th_total = document.getElementById('totalprice').value;
			th_stay = document.getElementById('noofdays').value;
			th_roomtype = document.getElementById('roomtype').value;
			th_paymode = document.getElementById('payment-type').value;
			th_amount = document.getElementById('amount-deposited').value;
			th_chq = document.getElementById('cheque-number').value;
			th_remark = document.getElementById('detail').value;

			if(document.getElementById('tr-date').value !== null && document.getElementById('tr-date').value != '' && document.getElementById('tr-date').value != 'undefined') { th_temp_reserve_date = document.getElementById('tr-date').value; } else { th_temp_reserve_date = "nil"; }
			
			if(th_booking_type == 'individual') {
				th_biller = 0;
				var wgtradio = document.getElementById('fbooking').elements['payment-by'];
				var wgtradio_numbr = wgtradio.length;
				for(var i=0; i < wgtradio_numbr; i++) { if(wgtradio[i].checked) { th_payby = wgtradio[i].value; break; } }
			} else if(th_booking_type == 'corporate') {
				th_biller = document.getElementById('cspg').value;
				var wgtradio = document.getElementById('fbooking').elements['payment-by2'];
				var wgtradio_numbr = wgtradio.length;
				for(var i=0; i < wgtradio_numbr; i++) { if(wgtradio[i].checked) { th_payby = wgtradio[i].value; break; } }
			} else if(th_booking_type == 'agent') {
				th_biller = document.getElementById('agent').value;
				th_payby = "Guest";
			} else if(th_booking_type == 'complimentary') {
				th_biller = document.getElementById('complimentary').value;
				th_payby = "Complimentary";
			}

			var wgtradio = document.getElementById('fbooking').elements['bill-to-room'];
			var wgtradio_numbr = wgtradio.length;
			for(var i=0; i < wgtradio_numbr; i++) { if(wgtradio[i].checked) { th_billroom = wgtradio[i].value; break; } }

			if(th_billroom == 'yes' || th_billroom == 'Yes') {
				var wgtcheckbox = document.getElementById('fbooking').elements['posstores'];
				var wgtcheckbox_numbr = wgtcheckbox.length;
				for(var i=0; i < wgtcheckbox_numbr; i++) { if(wgtcheckbox[i].checked) { if((eval(i) + 1) < wgtcheckbox_numbr) { th_roomservice +=wgtcheckbox[i].value+','; } else { th_roomservice +=wgtcheckbox[i].value; } } }
			} else {
				th_roomservice = 0;
			}

			if(th_roomservice != 0) { th_roomservice = th_roomservice.replace('undefined',''); }

			var roomno = document.getElementsByName('roomnumber[]');
			var title = document.getElementsByName('title[]');
			var firstname = document.getElementsByName('firstname[]');
			var lastname = document.getElementsByName('lastname[]');
			var phone = document.getElementsByName('phonenumber[]');
			var email = document.getElementsByName('emailaddress[]');

			bookingString = {
				"addonbooking": foraddonbkg,
				"virtualguest": forvgc,
				"fqguest": forfgc,
				"bookingclass": thebooking,
				"bookingtype": [th_booking_type,th_biller,th_payby],
				"billroom": [{"isbill":th_billroom,"roomservices":th_roomservice}],
				"lodging": [{"roomtype":th_roomtype,"startdate":th_checkin,"endate":th_checkout,"noofrooms":th_noofrooms,"rate":th_rate,"total":th_total,"stay":th_stay,"tempreservedate":th_temp_reserve_date}],
				"payment": [{"paymentmode":th_paymode,"amountpaid":th_amount,"chequeno":th_chq,"detail":th_remark}],
				"guest": [],
				"billsummary": []
			};

			for(var i=0; i < th_noofrooms; i++) {
				
				var element = {};
				
				var fname = (firstname[i].value).replace(/[^a-zA-Z ]/g, ""); fname = fname.replace(/ /g,"-");
				var lname = (lastname[i].value).replace(/[^a-zA-Z ]/g, ""); lname = lname.replace(/ /g,"-");
				
				element['room'] = roomno[i].value;
				element['title'] = title[i].value;
				element['firstname'] = fname;
				element['lastname'] = lname;
				element['phone'] = phone[i].value;
				element['email'] = email[i].value;
				
				bookingString.guest.push(element);
			}

			var th_title = bookingString.guest[0].title;
			var th_roomtype = bookingString.lodging[0].roomtype;
			var th_room = bookingString.guest[0].room;

			var wgt_guest_name = bookingString.guest[0].firstname+' '+bookingString.guest[0].lastname+' [Pr.]';
			var wgt_guest_phone = bookingString.guest[0].phone;
			var wgt_guest_email = bookingString.guest[0].email;
			var wgt_guest_checkin = bookingString.lodging[0].startdate;
			var wgt_guest_checkout = bookingString.lodging[0].endate;
			var wgt_guest_duration = bookingString.lodging[0].stay;
			var wgt_booking_type = bookingString.bookingtype[0]+' - '+bookingString.bookingtype[2];
			var wgt_adult = bookingString.lodging[0].noofrooms;
			var wgt_room_rate = bookingString.lodging[0].rate;
			var wgt_room_total_sum = bookingString.lodging[0].total;
			var wgt_amount = bookingString.payment[0].amountpaid;

			//var room_tariff = eval(wgt_room_rate) * eval(wgt_adult);
			var room_tariff = wgt_room_total_sum;
			var discount = (php_val_4 / 100) * room_tariff; room_tariff = room_tariff - discount;
			
			if(document.getElementById('rsschg').value > 0) { var service_charge = document.getElementById('rsschg').value; }
			else { var service_charge = (php_val_1 / 100) * eval(room_tariff); }

			if(document.getElementById('rsvat').value > 0) { var vat = document.getElementById('rsvat').value; }
			else { var vat = (php_val_2 / 100) * eval(room_tariff); }

			if(document.getElementById('rsctax').value > 0) { var consumption_tax = document.getElementById('rsctax').value; }
			else { var consumption_tax = (php_val_3 / 100) * eval(room_tariff); }
			
			var total_charges = eval(room_tariff) + eval(service_charge) + eval(vat) + eval(consumption_tax);

			var bsm = {};
			bsm['discount'] = discount;
			bsm['tax'] = vat;
			bsm['servicecharge'] = service_charge;
			bsm['consumption'] = consumption_tax;
			bsm['totalsumup'] = total_charges;

			bookingString.billsummary.push(bsm);
			
			var printRtr = numberFormat(room_tariff), printDsc = numberFormat(discount), printSc = numberFormat(service_charge), printVat = numberFormat(vat), printCtx = numberFormat(consumption_tax), printTc = tofixe(total_charges,2), printTc = numberFormat(printTc), printAmt;

			if(wgt_amount && eval(wgt_amount) > 0) { printAmt = numberFormat(wgt_amount); }
			else { printAmt = '0.00'; }
			
			json_getdata(wgtitle,'salutation_tbl',th_title,'id','name');
			function wgtitle(response) { sessionStorage.setItem('gstitle',response); }

			json_getdata(wgtroomtype,'room_type_tbl',th_roomtype,'id','name');
			function wgtroomtype(response) { sessionStorage.setItem('gsroomtype',response); }

			json_getdata(wgtroomfs,'room_tbl',th_room,'id','roomprefix');
			function wgtroomfs(response) { sessionStorage.setItem('gsroompfx',response); }

			json_getdata(wgtroomss,'room_tbl',th_room,'id','roomnumber');
			function wgtroomss(response) { sessionStorage.setItem('gsroomno',response); }
			
			var th_jsonObj = JSON.stringify(bookingString);
			sessionStorage.setItem('bookingdetails',th_jsonObj);

			var forcspgcrlimit = document.getElementById('cspgcrlimit').value;
			var forcspgcrbal = document.getElementById('cspgcrbal').value;

			var bkg_err;

			if(th_booking_type == 'corporate' && th_payby == 'Corporate' && forcspgcrlimit == 0 && forcspgcrbal == 0) { bkg_err = 1; }
			else { bkg_err = 0; }

			if(bkg_err == 1) {
				parent.document.getElementById('for-booking').className = 'noshow motion';
				parent.document.getElementById('inn-for-booking').className = 'noshow';
				alert("Notification\n\nUnable to process reservation for the selected corporate. Contact your manager for information");
				window.location.reload(true);
			} else {
			
				var htmlresult = '';

				htmlresult += '<small class="float-right top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 light-red-theme white-font">'+thebooking+'</small>';
				htmlresult += '<h3 class="large nobold font-big-bold alignlt top-pull-5">New Booking</h3><br>';
				htmlresult += '<div class="box-border-thick xxsml-rounded-button pads20 bottom-push-10 noscroll">';
				htmlresult += '<span class="ln-display-box float-left nc-width-50 alignlt">';
				htmlresult += '<h4 class="large nobold default-text-font-bold">Guest Details</h4><br>';
				htmlresult += '<div class="block-element ft-xsml-size">';
				htmlresult += '<ul class="nolist">';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Guest Name:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55"><span id="gstitle"></span> '+wgt_guest_name+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Phone No:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55">'+wgt_guest_phone+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Email:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55">'+wgt_guest_email+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '</ul>';
				htmlresult += '</div>';
				htmlresult += '</span>';
				htmlresult += '<span class="ln-display-box float-left nc-width-50 left-pull-20 alignlt">';
				htmlresult += '<h4 class="large nobold default-text-font-bold">Stay Details</h4><br>';
				htmlresult += '<div class="block-element ft-xsml-size">';
				htmlresult += '<ul class="nolist">';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Checkin Date:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">'+wgt_guest_checkin+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Checkout Date:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">'+wgt_guest_checkout+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">No of Days:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">'+wgt_guest_duration+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '</ul>';
				htmlresult += '</div>';
				htmlresult += '</span>';
				htmlresult += '<span class="block-element new-line-space">';
				htmlresult += '</span>';
				htmlresult += '</div>';
				htmlresult += '<div class="box-border-thick xxsml-rounded-button pads20 bottom-push-20 noscroll">';
				htmlresult += '<span class="ln-display-box float-left nc-width-50 alignlt">';
				htmlresult += '<h4 class="large nobold default-text-font-bold">Booking Details</h4><br>';
				htmlresult += '<div class="block-element ft-xsml-size">';
				htmlresult += '<ul class="nolist">';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Booking Type:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55">'+wgt_booking_type+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Room Type:</li>';
				htmlresult += '<li id="gsroomtype" class="ln-display-box float-left nc-width-55"></li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Room No:</li>';
				htmlresult += '<li id="gsroom" class="ln-display-box float-left nc-width-55"></li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Adults:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55">'+wgt_adult+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-45 dark-grey-font">Children:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-55">0</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '</ul>';
				htmlresult += '</div>';
				htmlresult += '</span>';
				htmlresult += '<span class="ln-display-box float-left nc-width-50 left-pull-20 alignlt">';
				htmlresult += '<h4 class="large nobold default-text-font-bold">Estimated Bill</h4><br>';
				htmlresult += '<div class="block-element ft-xsml-size">';
				htmlresult += '<ul class="nolist">';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Room Tariff:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printRtr+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Discount:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printDsc+'</li><li class="block-element new-line-space bottom-push-7"></li>';

				if(htx2 == 1) {
					htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Service Charge:</li>';
					htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printSc+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				}

				if(htx3 == 1) {
					htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Vat:</li>';
					htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printVat+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				}

				if(htx1 == 1) {
					htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Consumption Tax:</li>';
					htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printCtx+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				}

				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Amount Paid:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printAmt+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50 dark-grey-font">Total:</li>';
				htmlresult += '<li class="ln-display-box float-left nc-width-50">&#8358; '+printTc+'</li><li class="block-element new-line-space bottom-push-7"></li>';
				htmlresult += '</ul>';
				htmlresult += '</div>';
				htmlresult += '</span>';
				htmlresult += '<span class="block-element new-line-space">';
				htmlresult += '</span>';
				htmlresult += '</div>';
				htmlresult += '<p id="fbutton" class="alignct"><input id="confirmbooking" type="button" value="Confirm & Continue" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 xsml-rounded-button right-push-20 default-text-font-bold anchor" onclick="confirmbooking()"><input type="button" value="Redo Booking" class="red-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 xsml-rounded-button default-text-font-bold anchor" onclick="redobooking()"></p>';
				htmlresult += '<div id="fmsg" class="block-element"></div>';

				var feedin = setInterval(function() {
					if(sessionStorage.getItem('gsroomtype') !== null && sessionStorage.getItem('gsroomtype') !== undefined && sessionStorage.getItem('gsroompfx') !== null && sessionStorage.getItem('gsroompfx') !== undefined) {
						
						parent.document.getElementById('th-nbk').innerHTML = htmlresult;
						
						if(wgt_adult > 1) { var abbr = '..'; } else { var abbr = ''; }
						
						setTimeout(() => {
							parent.document.getElementById('gstitle').innerHTML = sessionStorage.getItem('gstitle');
							parent.document.getElementById('gsroomtype').innerHTML = sessionStorage.getItem('gsroomtype');
							parent.document.getElementById('gsroom').innerHTML = sessionStorage.getItem('gsroompfx')+sessionStorage.getItem('gsroomno')+abbr;

							sessionStorage.removeItem('gstitle');
							sessionStorage.removeItem('gsroomtype');
							sessionStorage.removeItem('gsroompfx');

						},1000);

						clearInterval(feedin);
					}
				},1000);
			}

		},3000);
	}



	window.onload = initializeCalendar(); setFrom();

</script>