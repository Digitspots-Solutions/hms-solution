<?php
	
	$additionalQuery = "";

	//default checkout time
	$wgt_df_checkin_time = $the_hr_in[0].':'.$gh_checkin_time_min_name.':00';
	$wgt_df_checkout_time = $the_hr_out[0].':'.$gh_checkout_time_min_name.':00';

	$print_wgt_df_checkin_time = write_timeF($gh_get_time_format,$wgt_df_checkin_time);
	$print_wgt_df_checkout_time = write_timeF($gh_get_time_format,$wgt_df_checkout_time);

	#---------------------------------------------------------------------------------------------------------------

	//from booking invoice table
	$wgt_booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
	$wgt_bill_type = idget_fdata($tbL130,'booking_number',$booking_number,'bill_type');
	$wgt_bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');
	$wgt_bill_to_g = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to_g');
	$wgt_reservation = idget_fdata($tbL130,'booking_number',$booking_number,'reservation');
	$wgt_isbill_to_room = idget_fdata($tbL130,'booking_number',$booking_number,'isbill_to_room');
	$wgt_billing_services = idget_fdata($tbL130,'booking_number',$booking_number,'billing_services');
	$wgt_islate_checkout = idget_fdata($tbL130,'booking_number',$booking_number,'islate_checkout');
	$wgt_isweekend_fares = idget_fdata($tbL130,'booking_number',$booking_number,'isweekend_fares');
	$wgt_settled_booking = idget_fdata($tbL130,'booking_number',$booking_number,'settled_booking');

	if(empty($wgt_settled_booking)) { $wgt_settled_booking = 0; }
	else { $wgt_settled_booking = $wgt_settled_booking; }

	$wgt_checkin_date = idget_fdata($tbL130,'booking_number',$booking_number,'checkin_date');
	$wgt_checkin_time = idget_fdata($tbL130,'booking_number',$booking_number,'checkin_time');
	$wgt_checkout_date = idget_fdata($tbL130,'booking_number',$booking_number,'checkout_date');
	$wgt_checkout_time = idget_fdata($tbL130,'booking_number',$booking_number,'checkout_time');
	$wgt_bookedby = idget_fdata($tbL130,'booking_number',$booking_number,'userid');
	$wgt_bk_datelogged = idget_fdata($tbL130,'booking_number',$booking_number,'datelogged');
	$wgt_bk_timelogged = idget_fdata($tbL130,'booking_number',$booking_number,'timelogged');

	$wgtbkStatus = wgt_booking_status($wgt_reservation);

	$print_wgt_checkin_date = write_dateF($gh_get_date_format,$wgt_checkin_date);
	$print_wgt_checkout_date = write_dateF($gh_get_date_format,$wgt_checkout_date);
	$print_wgt_checkin_time = write_timeF($gh_get_time_format,$wgt_checkin_time);
	$print_wgt_checkout_time = write_timeF($gh_get_time_format,$wgt_checkout_time);

	$bill_charged_on = "";
	$cspg_address = ""; $cspg_city = ""; $cspg_phone = ""; $cspg_email = "";

	if(isset($wgt_booking_type) && ($wgt_booking_type == 'corporate' || $wgt_booking_type == 'Corporate')) {
		if(isset($wgt_bill_type) && ($wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Guest')) {
			$bill_charged_on = "<b class='nobold royal-blue-font'>Corporate/Spl Guest Name: ".idget_data($tbL58,$wgt_bill_to,'name')."</b><br>";
			if($wgt_bill_to_g > 0 && $wgt_bill_type == 'Guest') { $bill_charged_on .= "(".idget_data($tbL58,$wgt_bill_to_g,'code').")"; $billpaylabel = "Amount Paid"; }
			else { $bill_charged_on .= "(".idget_data($tbL58,$wgt_bill_to,'code').")"; $billpaylabel = "Bill to Corporate"; }
			$cspg_address = idget_fdata($tbL59,'cspgid',$wgt_bill_to,'address1');
			$cspg_city = idget_fdata($tbL59,'cspgid',$wgt_bill_to,'city');
			$cspg_phone = idget_data($tbL58,$wgt_bill_to,'mobile');
			$cspg_email = idget_data($tbL58,$wgt_bill_to,'email');
		} else {
			$bill_charged_on = "";
			$billpaylabel = "Amount Paid";
		}
		$guestfeatOpt = "noshow";
	} elseif(isset($wgt_booking_type) && ($wgt_booking_type == 'complimentary' || $wgt_booking_type == 'Complimentary')) {
		$bill_charged_on = "Compl. ".idget_data($tbL33,$wgt_bill_to,'name');
		$guestfeatOpt = "block-element";
		$billpaylabel = "Complimentary";
	} else {
		$bill_charged_on = "";
		$guestfeatOpt = "block-element";
		$billpaylabel = "Amount Paid";
	}

	if(isset($wgt_isbill_to_room) && $wgt_isbill_to_room == 'Yes') { $rsArry = explode(',',$wgt_billing_services); $allowAd = "yes"; $allowChg = "yes"; } else { $rsArry = ""; $allowAd = "no"; $allowChg = "yes"; }

	#check if guest needs stay extension
	$stayextension = strtotime($server_get_date) - strtotime($wgt_checkout_date);
	if(isset($stayextension) && $stayextension >= 0) { $showExnotification = "block-element"; }
	else { $showExnotification = "noshow"; }
	
	/*if(isset($wgt_checkout_date) && $wgt_checkout_date == $server_get_date) { $showExnotification = "block-element"; }
	else { $showExnotification = "noshow"; }*/

	$frontdesk_cashier = idget_data($tbL7,$wgt_bookedby,'staffname');
	$print_wgt_bk_datelogged = write_dateF($gh_get_date_format,$wgt_bk_datelogged);
	$print_wgt_bk_timelogged = write_timeF($gh_get_time_format,$wgt_bk_timelogged);

	#---------------------------------------------------------------------------------------------------------------

	//from guest table
	//fetch primary contact

	if($isguestid == 0) { $guest_query = array("booking_number"=>$booking_number,"primary_guest"=>1,"deletedata"=>0); }
	else { $guest_query = array("id"=>$isguestid,"deletedata"=>0); }

	$guest_dataproperty = "id,photo,guest_code,salutation,fname,lname,mobile,emailaddress,remarks,address,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,gender,age,dob,pob,nationality,immi_status,allien_regno,employer,phoneno,zip_code,country_date_checkin,next_destination,id_issue_date,id_issue_place,current_address,probable_destination,passport_no,issue_date,expiry_date,issue_place,visa_validity";
	$get_guest_detail = mysqli_data_fetch($tbL102,$guest_dataproperty,$guest_query,'noarray');
	
	#primary guest ID
	if(isset($get_guest_detail[0]) && $get_guest_detail[0] >= 1) { $wgt_pry_id = $get_guest_detail[0]; }
	else { $wgt_pry_id = 0; }

	#get salutation
	$salutation = idget_data($tbL42,$get_guest_detail[3],'name');
	$guest_account_name = $salutation.' '.$get_guest_detail[4].' '.$get_guest_detail[5];

	#check if room already charged
	$sql_query_1 = array("booking_number"=>$booking_number,"customerid"=>$wgt_pry_id,"day"=>1);
	$ischarged = mysqli_data_fetch($tbL134,'ischarged,charge',$sql_query_1,'noarray');
	$wgt_ischarged = $ischarged[0]; $wgt_charge = $ischarged[1];

	#check if guest has made payment
	$sql_query_ispaid = array("booking_number"=>$booking_number,"transaction_type"=>"credit","isreversed"=>0);
	$isbillPaid = mysqli_data_checkr($tbL131,'(*)',$sql_query_ispaid);
	
	#---------------------------------------------------------------------------------------------------------------


	#---------------------------------------------------------------------------------------------------------------

	//from room occupancy table
	
	#get summary of occupancy
	$queryset1 = "booking_number='{$booking_number}' AND status NOT IN('Swapped','Upgraded','Downgraded','Cancelled') AND deletedata=0";
	$queryset2 = "booking_number='{$booking_number}' AND status NOT IN('Swapped','Upgraded','Downgraded','Cancelled') AND isextrabed=1 AND deletedata=0";
	$sqlset1 = "COUNT(roomid)";
	$sqlset2 = "SUM(adult)";
	$sqlset3 = "SUM(child)";
	$sqlset4 = "COUNT(isextrabed)";

	$wgt_total_rooms = mysqli_arithmetic_data($tbL127,$sqlset1,$queryset1);
	$wgt_total_adults = mysqli_arithmetic_data($tbL127,$sqlset2,$queryset1);
	$wgt_total_childs = mysqli_arithmetic_data($tbL127,$sqlset3,$queryset1);
	$wgt_total_extrabeds = mysqli_arithmetic_data($tbL127,$sqlset4,$queryset2);

	if(isset($wgt_total_rooms) && $wgt_total_rooms == 1) { $wgt_lodged_as = "Single - ".$wgtbkStatus; }
	elseif(isset($wgt_total_rooms) && $wgt_total_rooms >= 2) { $wgt_lodged_as = "Group - ".$wgtbkStatus; }
	else { $wgt_lodged_as = ""; }

	#---------------------------------------------------------------------------------------------------------------

	//from room daily charges table, payment and balance

	#charges
	$queryset3 = "booking_number='{$booking_number}' AND deletedata=0";
	
	if($wgt_bill_type == 'Corporate') { $queryset3x = "booking_number='{$booking_number}' AND biller={$wgt_bill_to} AND status NOT IN('Pending') AND isreversed=0 AND deletedata=0"; }
	else { $queryset3x = "booking_number='{$booking_number}' AND status NOT IN('Pending') AND isreversed=0 AND deletedata=0"; }

	$sqlset5 = "SUM(room_amount)";
	$sqlset6 = "SUM(discount_amount)";
	$sqlset7 = "SUM(tax_amount)";
	$sqlset8 = "SUM(consumption_tax_amount)";
	$sqlset9 = "SUM(service_charge)";
	$sqlset9x = "SUM(bill_amount)";

	$wgt_other_charges = mysqli_arithmetic_data($tbL100,$sqlset9x,$queryset3x);
	$wgt_total_room_tariff = mysqli_arithmetic_data($tbL134,$sqlset5,$queryset3);
	$wgt_total_room_discount = mysqli_arithmetic_data($tbL134,$sqlset6,$queryset3);
	$wgt_total_room_tax = mysqli_arithmetic_data($tbL134,$sqlset7,$queryset3);
	$wgt_total_room_consumption = mysqli_arithmetic_data($tbL134,$sqlset8,$queryset3);
	$wgt_total_room_servicecharge = mysqli_arithmetic_data($tbL134,$sqlset9,$queryset3);

	$wgt_total_tariff = (($wgt_total_room_tariff + $wgt_total_room_tax + $wgt_total_room_consumption + $wgt_total_room_servicecharge) - $wgt_total_room_discount) + $wgt_other_charges;


	#payment
	$sqlset10 = "SUM(amount)";
	if(isset($wgt_bill_type) && ($wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Complimentary')) { $queryset4 = "booking_number='{$booking_number}' AND transaction_type='debit' AND deletedata=0"; } else { $queryset4 = "booking_number='{$booking_number}' AND transaction_type IN('credit','refund','coupon','rebate') AND deletedata=0"; }

	$wgt_total_payment = mysqli_arithmetic_data($tbL131,$sqlset10,$queryset4);
	

	#get balance as either credit or debit
	$wgt_balance = $wgt_total_tariff - $wgt_total_payment;

	$print_room_charges = write_amountF($gh_get_decimal_format,$wgt_total_room_tariff);
	$print_discount = write_amountF($gh_get_decimal_format,$wgt_total_room_discount);
	$print_service_charge = write_amountF($gh_get_decimal_format,$wgt_total_room_servicecharge);
	$print_tax_amount = write_amountF($gh_get_decimal_format,$wgt_total_room_tax);
	$print_consumption_tax_amount = write_amountF($gh_get_decimal_format,$wgt_total_room_consumption);
	$print_other_charges = write_amountF($gh_get_decimal_format,$wgt_other_charges);

	$print_grand_total = write_amountF($gh_get_decimal_format,$wgt_total_tariff);
	$print_amount_paid = write_amountF($gh_get_decimal_format,$wgt_total_payment);
	$print_balance = write_amountF($gh_get_decimal_format,$wgt_balance);

	#---------------------------------------------------------------------------------------------------------------

	//from guest arrival and departure mode table

	$gad_1 = idget_fdata($tbL128,'booking_number',$booking_number,'source_of_biz');
	$gad_2 = idget_fdata($tbL128,'booking_number',$booking_number,'arrival_mode');
	$gad_3 = idget_fdata($tbL128,'booking_number',$booking_number,'departure_mode');
	$gad_4 = idget_fdata($tbL128,'booking_number',$booking_number,'remarks');

	if(isset($gad_1) && $gad_1 >= 1) { $source_of_biz = idget_data($tbL43,$gad_1,'name'); }
	else { $source_of_biz = 'Choose'; }

	if(isset($gad_2) && $gad_2 >= 1) { $arrival_mode = idget_data($tbL29,$gad_2,'name'); }
	else { $arrival_mode = 'Choose'; }

	if(isset($gad_3) && $gad_3 >= 1) { $departure_mode = idget_data($tbL29,$gad_3,'name'); }
	else { $departure_mode = 'Choose'; }

	if(isset($gad_4) && !empty($gad_4)) { $gad_remarks = $gad_4; }
	else { $gad_remarks = 'No remarks'; }

	#---------------------------------------------------------------------------------------------------------------

	//get sales outlet as bill-to-room list for additional

	$disabled = ""; $outletpack = "";

	$outlet_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
	$get_outlet = mysqli_data_fetch($tbL14,'id,posname,isfoodtype',$outlet_query,'array');

	if(is_array($get_outlet)) {
		$outletpack = '';
		foreach($get_outlet as $olkey => $olvalue) {
			
			//value-to-find and source
			/*if(is_array($rsArry)) { if(in_array($olvalue['id'],$rsArry)) { $disabled=" disabled"; $color=" dark-grey-font"; } else { $disabled=""; $color=""; } } else { $disabled=""; $color=""; }*/

			$disabled=""; $color="";

			$outletpack .= '<div class="ln-display-box float-left nc-width-50 bottom-push-10 ft-xsml-size">';
			$outletpack .= '<span class="ln-display-box float-left nc-width-15">';
			$outletpack .= '<input type="checkbox" name="outlets[]" value="'.$olvalue['id'].'"'.$disabled.'>';
			$outletpack .= '</span>';
			$outletpack .= '<span class="ln-display-box float-left nc-width-85 alignlt'.$color.'">';
			$outletpack .= $olvalue['posname'];
			$outletpack .= '</span>';
			$outletpack .= '<span class="block-element new-line-space"></span>';

			if($olvalue['isfoodtype'] == 'Yes') {
				$outletpack .= '<input type="checkbox" name="outlets[]" value="'.$olvalue['id'].'-1"'.$disabled.'> Breakfast &nbsp;';
				$outletpack .= '<input type="checkbox" name="outlets[]" value="'.$olvalue['id'].'-2"'.$disabled.'> Lunch &nbsp;';
				$outletpack .= '<input type="checkbox" name="outlets[]" value="'.$olvalue['id'].'-3"'.$disabled.'> Dinner';
			}

			$outletpack .= '</div>';
		}
		$outletpack .= '<div class="block-element new-line-space"></div>';
	}

	#---------------------------------------------------------------------------------------------------------------

	//form data needed

	$salutations = select_dt_fetch('status','Active',$tbL42,'id','name');
	$identity_type = select_dt_fetch('status','Active',$tbL37,'id','name');
	$business_src = select_dt_fetch('status','Active',$tbL43,'id','name');
	$transit_mode = select_dt_fetch('status','Active',$tbL29,'id','name');
	$cancelling_rs = select_dt_fetch('status','Active',$tbL32,'id','name');

	$policy_query = array("isactive"=>"Yes","deletedata"=>0);
	$policy_datasets = "id,policyname,discount,detail";
	$policy_data = mysqli_data_fetch($tbL31,$policy_datasets,$policy_query,'array');
	
	$policy_select_opt = "";
	
	if(is_array($policy_data)) {
		foreach ($policy_data as $plkey => $plvalue) {
			$policy_select_opt .= '<option value="'.$plvalue['id'].'" title="'.$plvalue['detail'].'">'.$plvalue['policyname'].': '.$plvalue['discount'].'% Charges</option>';
		}
	} else {
		$policy_select_opt = '<option value="0">No charges</option>';
	}

?>