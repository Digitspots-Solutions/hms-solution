<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/hotel_profile.php";
?>
<head>
	<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
	<link rel="stylesheet" href="../../style/custom.css"/>
	<link rel="stylesheet" href="applystyle.css"/>
	<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="../../js/jspath.js"></script>
	<script type="text/javascript" src="../../js/jsbk.js"></script>
	<script type="text/javascript" src="../../js/all.js"></script>
	<script src="../ckeditor/ckeditor.js"></script>
</head>

<div class="block-element pads30">
	<div id="section-to-print" class="block-element" align="center">
		<div class="cs-width-900">
			<?php
				if(isset($_GET['booking']) && isset($_GET['room']) && isset($_GET['customer'])) {
					
					$booking_number = escape_data($_GET['booking']);
					$room = escape_data($_GET['room']);
					$customer = escape_data($_GET['customer']);

					//get guest taxes status
					$is_vat = idget_fdata($tbL139,'booking_number',$booking_number,'vat');
					$is_servicecharge = idget_fdata($tbL139,'booking_number',$booking_number,'service_charge');
					$is_consumption_tax = idget_fdata($tbL139,'booking_number',$booking_number,'consumption_tax');

					$room_type_id = idget_fdata($tbL56,'id',$room,'room_type_id');		
					$room_type = idget_data($tbL52,$room_type_id,'name');
					$room_prefix = idget_data($tbL56,$room,'roomprefix');
					$room_number = idget_data($tbL56,$room,'roomnumber');

					$billpayby = idget_fdata($tbL130,'booking_number',$booking_number,'bill_pay_by');

					$pr_select_property = "name";
					$pr_select_query = array("booking_number"=>$booking_number,"primary_guest"=>1);
					$pr_select_data = mysqli_data_fetch($tbL102,$pr_select_property,$pr_select_query,'noarray');

					$select_property = "name,billto,billtype";
					$select_query = array("booking_number"=>$booking_number,"id"=>$customer);
					$select_data = mysqli_data_fetch($tbL102,$select_property,$select_query,'noarray');

					if(isset($billpayby) && $billpayby == "Group Owner") {
						$customer_name = $pr_select_data[0];
					} else {
						$customer_name = $select_data[0];
					}

					$billto = $select_data[1];
					$billtype = $select_data[2];

					#to whom bill goes to
					if(isset($billto) && $billto >= 1) {
						if(isset($billtype) && $billtype == 3) {
							$_bill_on_account = " (Compl. ".idget_data($tbL33,$billto,'name').")";
							$_to_bill = 1;
						} elseif(isset($billtype) && $billtype == 4) {
							$_bill_on_account = " (Corpo/Spl. ".idget_data($tbL58,$billto,'name').")";
							$_to_bill = 1;
						} else {
							$_bill_on_account = "";
							$_to_bill = 0;
						}

					} else {
						$_bill_on_account = "";
						$_to_bill = 0;
					}
					#end


					$print_date = write_dateF($gh_get_date_format,$server_get_date);
					$print_time = write_timeF($gh_get_time_format,$server_get_time);

					$printedby = idget_data($tbL7,$userSignedIn,'staffname');

					//get room occupancy details
					$select_property_2 = "adult,child,isextrabed,occupancy_type,booking_type,checkin,checkout,status,remarks,checkin_date,checkin_time,checkout_date,checkout_time,datelogged,timelogged";
					$select_query_2 = array("booking_number"=>$booking_number,"roomid"=>$room);
					$select_data_2 = mysqli_data_fetch($tbL127,$select_property_2,$select_query_2,'noarray');

					if(str_replace('-','',$select_data_2[9]) > 1) { $f_guest_checkin_date = write_dateF($gh_get_date_format,$select_data_2[9]); }
					else { $f_guest_checkin_date = "--:--"; }
					if(str_replace('-','',$select_data_2[11]) > 1) { $f_guest_checkout_date = write_dateF($gh_get_date_format,$select_data_2[11]); }
					else { $f_guest_checkout_date = "--:--"; }

					if(str_replace(':','',$select_data_2[10]) > 1) { $f_guest_checkin_time = write_timeF($gh_get_time_format,$select_data_2[10]); }
					else { $f_guest_checkin_time = ""; }
					if(str_replace(':','',$select_data_2[12]) > 1) { $f_guest_checkout_time = write_timeF($gh_get_time_format,$select_data_2[12]); }
					else { $f_guest_checkout_time = ""; }

					$get_occupancy_type = idget_data($tbL51,$select_data_2[3],'name');
					$get_booking_Type = arrayget_key($booking_type,$select_data_2[4]);


					//get checkedin staff
					$select_property_3 = "userid";
					$select_query_3 = array("booking_number"=>$booking_number,"roomid"=>$room);
					$select_data_3 = mysqli_data_fetch($tbL97,$select_property_3,$select_query_3,'noarray');
					$checkedin_staff = idget_data($tbL7,$select_data_3[0],'staffname');

					//get number of night
					$ar_sql = "COUNT(roomid)";
					$ar_query = "booking_number='".$booking_number."' AND roomid='".$room."'";
					$ar_data_result = mysqli_arithmetic_data($tbL134,$ar_sql,$ar_query);

					?>
						<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
						<h1 class="large alignct"><b><?php echo _LONG_NAME; ?></b></h1>
						<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
						<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
						<small class="block-element top-push-3 bottom-push-10"><?php echo $hotel_email; ?></small>
						<h4 class="large">Printed By: <?php echo $printedby; ?> On <?php echo $print_date.' '.$print_time; ?></h4><br>
						<h1 class="large">Statement of Account</h1>
						<div class="block-element alignrt top-pull-7 bottom-pull-20">
							<input type="button" value="Bookings" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="allowPrint('booking','pos')"> &nbsp; <input type="button" value="Sales Point" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="allowPrint('pos','booking')">
						</div>
						<div class="block-element">
							<span class="ln-display-box float-left nc-width-45 alignlt">
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Guest Name
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<b><?php echo $customer_name; ?> (<?php echo $_bill_on_account; ?>)</b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Room Number
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<b><?php echo $room_prefix.$room_number; ?></b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Check-In Date & Time
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<?php echo $f_guest_checkin_date.' '.$f_guest_checkin_time; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Check-Out Date & Time
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<?php echo $f_guest_checkout_date.' '.$f_guest_checkout_time; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									CheckedIn Staff
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<b><?php echo $checkedin_staff; ?></b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Tariff Type
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<?php  ?>Rack Rate
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Occupancy Type
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size">
									<?php echo $get_occupancy_type; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
							</span>
							<span class="ln-display-box float-right nc-width-45 alignrt">
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Booking Number
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<b><?php echo $booking_number; ?></b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Booking Type
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<?php echo $get_booking_Type; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Balance Pay By
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<b><?php echo $billpayby; ?></b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									No of Nights
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<b><?php echo $ar_data_result; ?></b>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Date
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<?php echo $print_date; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
								<div class="ln-display-box float-left nc-width-40 bottom-push-10 ft-xsml-size black-font">
									Time
								</div>
								<div class="ln-display-box float-left nc-width-60 left-pull-20 bottom-push-10 ft-xsml-size alignlt">
									<?php echo $print_time; ?>
								</div>
								<div class="block-element new-line-space">
								</div>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div id="booking" class="block-element top-push-15 alignlt">
							<h4 class="large">Booking</h4>
							<div class="block-element top-push-3 box-border-thick sml-rounded-button noscroll">
								<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
									<tr>
										<th width="100px" align="center">Date</th>
										<th width="300px" align="center">Charge</th>
										<th width="150px" align="center">Debit</th>
										<th width="150px" align="center">Credit</th>
										<th width="50px" align="center"></th>
									</tr>

									<?php

										//for room inclusion
										include "frontdesk/guest_room_inclusions.php";

										//get room occupancy charges
										$occupancy_room_sql = "SUM(amount)";
										$occupancy_room_query = "booking_number='".$booking_number."' AND roomid='".$room."'";
										$occupancy_room_charges = mysqli_arithmetic_data($tbL140,$occupancy_room_sql,$occupancy_room_query);

										#get room daily charges listed
										$r_daily_c_query = array("booking_number"=>$booking_number,"roomid"=>$room,"deletedata"=>0);
										$select_data_4 = "sub_total,discount_amount,tax_amount,consumption_tax_amount,service_charge,other_charges,bill_amount,bill_date,bill_time,status,datelogged";
										$get_r_data = mysqli_data_fetch($tbL134,$select_data_4,$r_daily_c_query,'array');

										if(is_array($get_r_data)) {
											
											$noofcounts = 0;

											$logged_date=""; $room_discount=0; $billposts=0; $total_discount=0; $total_billposts=0;
											$total_comsumption_tax=0; $total_vat=0; $total_service_charge=0; $total_other_charges=0;
											
											foreach ($get_r_data as $r_key => $r_value) {
												
												$noofcounts += 1;

												$logged_date = write_dateF($gh_get_date_format,$r_value['bill_date']);
												$room_discount = write_amountF($gh_get_decimal_format,$r_value['discount_amount']);
												$billposts = write_amountF($gh_get_decimal_format,$r_value['sub_total']);

												$total_billposts = $total_billposts + $r_value['sub_total'];
												$total_discount = $total_discount + $r_value['discount_amount'];
												
												//$total_comsumption_tax = $total_comsumption_tax + $r_value['consumption_tax_amount'];
												//$total_vat = $total_vat + $r_value['tax_amount'];
												//$total_service_charge = $total_service_charge + $r_value['service_charge'];

												if(isset($is_servicecharge) && $is_servicecharge == 1) { $total_service_charge = $total_service_charge + $r_value['service_charge']; } else { $total_service_charge = 0; }
												
												if(isset($is_vat) && $is_vat == 1) { $total_vat = $total_vat + $r_value['tax_amount']; }
												else { $total_vat = 0; }

												if(isset($is_consumption_tax) && $is_consumption_tax == 1) { $total_comsumption_tax = $total_comsumption_tax + $r_value['consumption_tax_amount']; } else { $total_comsumption_tax = 0; }


												$total_other_charges = $total_other_charges + $r_value['other_charges'];

												?>
													<tr>
														<td width="100px" align="center"><?php echo $logged_date; ?></td>
														<td width="300px" align="center"><?php echo $r_value['status']; ?></td>
														<td width="150px" align="center">&#8358;<?php echo $billposts; ?></td>
														<td width="150px" align="center">&nbsp;</td>
														<td width="50px" align="center">&nbsp;</td>
													</tr>
												<?php
											}

											if(isset($noofcounts) && $noofcounts >= 1) {
												$total_other_charges = $total_other_charges + $actual_incl_bill + ($occupancy_room_charges * $noofcounts);
											} else {
												$total_other_charges = $total_other_charges + $actual_incl_bill;
											}

											$total_bill_amount = $total_billposts + $total_comsumption_tax + $total_vat + $total_service_charge + $total_other_charges;
											
											$print_total_billposts = write_amountF($gh_get_decimal_format,$total_billposts);
											$print_total_comsumption_tax = write_amountF($gh_get_decimal_format,$total_comsumption_tax);
											$print_total_vat = write_amountF($gh_get_decimal_format,$total_vat);
											$print_total_service_charge = write_amountF($gh_get_decimal_format,$total_service_charge);
											$print_total_bill_amount = write_amountF($gh_get_decimal_format,$total_bill_amount);
										}

									?>
								</table>
							</div>
							<div class="block-element top-pull-30 right-pull-10 ft-xsml-size">
								<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_billposts; ?></b></span>
								<span class="ln-display-box float-right bottom-push-10">Room Tariff</span>
								<span class="block-element new-line-space"></span>
								
								<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_comsumption_tax; ?></b></span>
								<span class="ln-display-box float-right bottom-push-10">Consumption Tax</span>
								<span class="block-element new-line-space"></span>
								
								<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_service_charge; ?></b></span>
								<span class="ln-display-box float-right bottom-push-10">Service Charge</span>
								<span class="block-element new-line-space"></span>
								
								<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_vat; ?></b></span>
								<span class="ln-display-box float-right bottom-push-10">VAT</span>
								<span class="block-element new-line-space"></span>
								
								<span class="ln-display-box float-right bottom-push-10 left-pull-10"><b>&#8358;<?php echo $print_total_bill_amount; ?></b></span>
								<span class="ln-display-box float-right bottom-push-10">Total</span>
								<span class="block-element new-line-space"></span>
							</div>
						</div>
						<div id="pos" class="block-element top-push-15 alignlt">
							<h4 class="large">Sales Point</h4>
							<div class="block-element top-push-3 box-border-thick sml-rounded-button noscroll">
								<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
									<tr>
										<th width="100px" align="center">Date</th>
										<th width="200px" align="center">Occupancy / Tariff</th>
										<th width="150px" align="center">Order Number</th>
										<th width="150px" align="center">Store</th>
										<th width="100px" align="center">Debit</th>
										<th width="100px" align="center">Credit</th>
										<th width="50px" align="center"></th>
									</tr>

									<?php

										//get sales point records
										$select_property_4 = "id,datelogged,order_number,posid,price,qty";
										$select_query_4 = array("customerid"=>$customer,"roomid"=>$room);
										$select_data_4 = mysqli_data_fetch($tbL99,$select_property_4,$select_query_4,'array');

										if(is_array($select_data_4)) {
											
											$ps_logged_date=""; $pos_store=""; $amount=""; $print_amount=""; $total_pos_charges=0;
											
											foreach ($select_data_4 as $pos_key => $pos_value) {
												
												$ps_logged_date = write_dateF($gh_get_date_format,$pos_value['datelogged']);
												$pos_store = idget_data($tbL14,$pos_value['posid'],'posname');
												$amount = $pos_value['price'] * $pos_value['qty'];
												$total_pos_charges = $total_pos_charges + $amount;
												
												$print_amount = write_amountF($gh_get_decimal_format,$amount);

												?>
													<tr>
														<td width="100px" align="center"><?php echo $ps_logged_date; ?></td>
														<td width="200px" align="center"></td>
														<td width="150px" align="center"><?php echo $pos_value['order_number']; ?></td>
														<td width="150px" align="center"><?php echo $pos_store; ?></td>
														<td width="100px" align="center">&#8358;<?php echo $print_amount; ?></td>
														<td width="100px" align="center"></td>
														<td width="50px" align="center"></td>
													</tr>
												<?php
											}

											$print_total_pos_charges = write_amountF($gh_get_decimal_format,$total_pos_charges);

											?>
												<tr class="grey-theme">
													<td width="100px" align="center">Total</td>
													<td width="200px" align="center"></td>
													<td width="150px" align="center"></td>
													<td width="150px" align="center"></td>
													<td width="100px" align="center"><b>&#8358;<?php echo $print_total_pos_charges; ?></b></td>
													<td width="100px" align="center"></td>
													<td width="50px" align="center"></td>
												</tr>
											<?php
										}
									?>

								</table>
							</div>
						</div>
					<?php
				}
			?>
		</div>
	</div>
	<div class="block-element top-pull-50 alignct">
		<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="window.print()"> &nbsp; <input type="button" value="Send Mail" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 grey-theme sml-rounded-button anchor" onclick="">
	</div>
</div>


<script>

	function allowPrint(obj1,obj2) {
		chgclass(obj1,'block-element top-push-15 alignlt');
		chgclass(obj2,'noshow top-push-15 alignlt');

		var cssStyle, styleobj = document.createElement('style');
		styleobj.type = 'text/css';

		cssStyle = "@media print { #"+obj2+" { display: none; } }";

		if(styleobj.styleSheet) {
			styleobj.styleSheet.cssText = cssStyle;
		} else {
			styleobj.appendChild(document.createTextNode(cssStyle));
			document.getElementsByTagName("head")[0].appendChild(styleobj);
		}
	}

</script>