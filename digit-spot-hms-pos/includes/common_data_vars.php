<?php
	
	//single common data vars
	
	$dhl = idget_data($tbL113,1,'mark_all_vacant_rooms');
	$dhl_legendname = idget_data($tbL36,$dhl,'legendname');
	$dhl_colorcode = idget_data($tbL36,$dhl,'colorcode');

	$drsl = 1;
	$drsl_legendname = idget_data($tbL38,$drsl,'legendname');
	$drsl_colorcode = idget_data($tbL38,$drsl,'colorcode');
	$drsl_legendname = str_replace($drsl_legendname,'Vacant',$drsl_legendname);
	
	$default_housekeeping_legend = $dhl_legendname;
	$default_room_status_legend = $drsl_legendname;

	$default_housekeeping_legend_color = $dhl_colorcode;
	$default_room_status_legend_color = $drsl_colorcode;

	//transaction & record purpose
	$guest_number_prefix = "P";
	$receipt_prefix = "REC";

	//recreation & invoice prefix
	$recreation_inv_prefix = "RINV";
	$recreation_prefix = "RCR";

	//for tax charges
	$tax_charges = array(
		1=>"Consumption Tax",
		2=>"Service Charge",
		3=>"Value Added Tax"
	);

	//for booking type
	$booking_type = array(
		1=>"Individual",
		2=>"Corporate",
		3=>"Agent",
		4=>"E-Booking",
		5=>"Complimentary",
		6=>"Packages"
	);


	//for recreation service period
	$recreation_duration = array(
		1=>"1 Month",
		2=>"2 Months",
		3=>"3 Months",
		4=>"4 Months",
		5=>"5 Months",
		6=>"6 Months",
		7=>"7 Months",
		8=>"8 Months",
		9=>"9 Months",
		10=>"10 Months",
		11=>"11 Months",
		12=>"1 Year",
		13=>"1 Week",
		14=>"2 Weeks"
	);


	//for date format
	$date_format = array(
		1=>"mm-dd-yy",
		2=>"mm-dd-yyyy",
		3=>"mm dd yyyy",
		4=>"mm/dd/yy",
		5=>"dd-mm-yy",
		6=>"dd/mm/yy"
	);


	//for time format
	$time_format = array(
		1=>"12 Hours",
		2=>"24 Hours"
	);


	//for agent commission period
	$agent_commission_period = array(
		1=>"Weekly",
		2=>"Monthly",
		3=>"Quarterly",
		4=>"Half Yearly",
		5=>"Yearly"
	);

	$night_audit_start_alert_time = range(1,10);
	$wakeup_call_time = array(5,10,15,20,25,30,35,40,45,50,55,60);

	$hk_touchup_number = range(1,5);
	$number_of_days = range(1,31);

	$timing_12hr = array(
		1=>"12:00 AM",
		2=>"12:30 AM",
		3=>"01:00 AM",
		4=>"01:30 AM",
		5=>"02:00 AM",
		6=>"02:30 AM",
		7=>"03:00 AM",
		8=>"03:30 AM",
		9=>"04:00 AM",
		10=>"04:30 AM",
		11=>"05:00 AM",
		12=>"05:30 AM",
		13=>"06:00 AM",
		14=>"06:30 AM",
		15=>"07:00 AM",
		16=>"07:30 AM",
		17=>"08:00 AM",
		18=>"08:30 AM",
		19=>"09:00 AM",
		20=>"09:30 AM",
		21=>"10:00 AM",
		22=>"10:30 AM",
		23=>"11:00 AM",
		24=>"11:30 AM",
		25=>"12:00 PM",
		26=>"12:30 PM",
		27=>"01:00 PM",
		28=>"01:30 PM",
		29=>"02:00 PM",
		30=>"02:30 PM",
		31=>"03:00 PM",
		32=>"03:30 PM",
		33=>"04:00 PM",
		34=>"04:30 PM",
		35=>"05:00 PM",
		36=>"05:30 PM",
		37=>"06:00 PM",
		38=>"06:30 PM",
		39=>"07:00 PM",
		40=>"07:30 PM",
		41=>"08:00 PM",
		42=>"08:30 PM",
		43=>"09:00 PM",
		44=>"09:30 PM",
		45=>"10:00 PM",
		46=>"10:30 PM",
		47=>"11:00 PM",
		48=>"11:30 PM"
	);

	$guest_checkinout_timing_hr = array(
		1=>"12 AM",
		2=>"01 AM",
		3=>"02 AM",
		4=>"04 AM",
		5=>"05 AM",
		6=>"06 AM",
		7=>"07 AM",
		8=>"08 AM",
		9=>"09 AM",
		10=>"10 AM",
		11=>"11 AM",
		12=>"12 PM",
		13=>"01 PM",
		14=>"02 PM",
		15=>"03 PM",
		16=>"04 PM",
		17=>"05 PM",
		18=>"06 PM",
		19=>"07 PM",
		20=>"08 PM",
		21=>"09 PM",
		22=>"10 PM",
		23=>"11 PM"
	);

	$guest_checkinout_timing_min = array(
		1=>"00",
		2=>"15",
		3=>"30",
		4=>"45"
	);


	//for months of the year
	$full_months_name = array(
		1=>"January",
		2=>"February",
		3=>"March",
		4=>"April",
		5=>"May",
		6=>"June",
		7=>"July",
		8=>"August",
		9=>"September",
		10=>"October",
		11=>"November",
		12=>"December"
	);

	$night_audit_timing_24hr = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");

	$night_audit_timing_min = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59");

	$night_audit_calendar_date = array(
		1=>"Same Calendar Date",
		2=>"Next Calendar Date"
	);

	$time_zone = array(
		"zone"=>array("name"=>"Africa/Algier","transaction_time"=>"-2147483648","transaction_id"=>1),
		"zone"=>array("name"=>"Africa/Accra","transaction_time"=>"-1640995148","transaction_id"=>2)
	);


	$store_type = array(
		1=>"Statistical Store",
		2=>"Cost Center",
		3=>"Inventory Type",
		4=>"Pos Store"
	); //5=>"Main Store"

	$parent_store = array(
		0=>"N/A",
		1=>"Main Store",
		2=>"Restaurant & Bar",
		3=>"Pos Account",
		4=>"Pos IT",
		5=>"Gym",
		6=>"House Keeping"
	);

	$status_color_tag = array(
		"Active"=>"forest-green-font",
		"InActive"=>"red-font"
	);

	$status_tag = array(
		1=>"Active",
		0=>"InActive"
	);

	$coupon_type = array(
		1=>"Hotel Booking",
		2=>"Credit Note"
	);

	$gh_get_vat = idget_data($tbL109,1,'vat');
	$gh_get_service_charge = idget_data($tbL109,1,'service_charge');
	$gh_get_consumption_tax = idget_data($tbL109,1,'consumption_tax');

	$gh_get_checkin_time_hr = idget_data($tbL109,1,'checkin_time_hr');
	$gh_checkin_time_hr_name = arrayget_key($guest_checkinout_timing_hr,$gh_get_checkin_time_hr);
	$the_hr_in = explode(' ', $gh_checkin_time_hr_name);
	$gh_get_checkin_time_min = idget_data($tbL109,1,'checkin_time_min');
	$gh_checkin_time_min_name = arrayget_key($guest_checkinout_timing_min,$gh_get_checkin_time_min);

	$gh_get_checkout_time_hr = idget_data($tbL109,1,'checkout_time_hr');
	$gh_checkout_time_hr_name = arrayget_key($guest_checkinout_timing_hr,$gh_get_checkout_time_hr);
	$the_hr_out = explode(' ', $gh_checkout_time_hr_name);
	$gh_get_checkout_time_min = idget_data($tbL109,1,'checkout_time_min');
	$gh_checkout_time_min_name = arrayget_key($guest_checkinout_timing_min,$gh_get_checkout_time_min);

	$gh_get_date_format = idget_data($tbL109,1,'date_format');
	$gh_get_time_format = idget_data($tbL109,1,'time_format');
	$gh_get_decimal_format = idget_data($tbL109,1,'currency_decimal');

	$predefined_manual_checkin_time = "6:00:00";
	
	$the_hk_checkin_room_status = idget_data($tbL113,1,'mark_all_checkin_rooms');
	$the_hk_checkout_room_status = idget_data($tbL113,1,'mark_all_checkedout_rooms');

	$gh_get_notification_message = idget_data($tbL109,1,'notification_message');

	$gh_get_night_audit_hr = idget_data($tbL109,1,'night_audit_hr');
	$gh_get_night_audit_min = idget_data($tbL109,1,'night_audit_min');
	$gh_get_night_audit_logout_user = idget_data($tbL109,1,'night_audit_disable_users');
	$gh_get_night_audit_notification = idget_data($tbL109,1,'wakeup_call_time');
	$gh_get_night_audit_calendar = idget_data($tbL109,1,'night_audit_calendar');

	$gh_get_coupon_expiry_days = idget_data($tbL112,1,'coupon_expiry_days')." days";
	$coupon_expiry_default_date = date("Y-m-d",strtotime($gh_get_coupon_expiry_days));

	$gh_get_allow_past_reverse = idget_data($tbL109,1,'allow_past_reverse');

	//default checkin and checkout time
	$wgt_checkin_time = $the_hr_in[0].':'.$gh_checkin_time_min_name.':00';
	$wgt_checkout_time = $the_hr_out[0].':'.$gh_checkout_time_min_name.':00';

	//quality control user schemas
	$tqc = array('first_approval','second_approval','third_approval','fourth_approval','fifth_approval');


	function guestBillSummary($refnumber) {

		global $tbL100, $tbL134, $tbL131, $tbL130;

		$booking_number = $refnumber;

		$booking_type_fx = idget_fdata($refnumber,'booking_number','booking_type',$tbL130);
		$bill_type_fx = idget_fdata($refnumber,'booking_number','bill_type',$tbL130);
		$bill_to_fx = idget_fdata($refnumber,'booking_number','bill_to',$tbL130);

		#charges
		$queryset3 = "booking_number='{$booking_number}' AND deletedata=0";
		
		if($bill_type_fx == 'Corporate') { $queryset3x = "booking_number='{$booking_number}' AND biller={$bill_to_fx} AND isreversed=0 AND deletedata=0"; }
		else { $queryset3x = "booking_number='{$booking_number}' AND isreversed=0 AND deletedata=0"; }

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
		if(isset($bill_type_fx) && ($bill_type_fx == 'Corporate' || $bill_type_fx == 'Complimentary')) { $queryset4 = "booking_number='{$booking_number}' AND transaction_type='debit' AND deletedata=0"; } else { $queryset4 = "booking_number='{$booking_number}' AND transaction_type IN('credit','refund','coupon','rebate') AND deletedata=0"; }

		$wgt_total_payment = mysqli_arithmetic_data($tbL131,$sqlset10,$queryset4);

		#get balance as either credit or debit
		if($wgt_total_payment > 0) {
			/*if($wgt_total_tariff > $wgt_total_payment) { $wgt_balance = 0; }
			else { $wgt_balance = $wgt_total_tariff - $wgt_total_payment; }*/
			$wgt_balance = $wgt_total_tariff - $wgt_total_payment;
		} else {
			$wgt_balance = $wgt_total_tariff;
		}

		return $wgt_balance;
	}


	function getHrs($datefrom,$dateto) {

		$timestamp_1 = strtotime($datefrom);
		$timestamp_2 = strtotime($dateto);

		$hrs = abs($timestamp_2 - $timestamp_1)/(60*60);
		$hrs = round($hrs,1);

		return $hrs;
	}


	function wgt_inout_charges($timeset,$charges) {
		
		global $tbL109;

		if($charges == 'checkin') {
			if($timeset == 1) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_1hr'); }
			elseif($timeset > 1 && $timeset <= 2) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_2hr'); }
			elseif($timeset > 2 && $timeset <= 3) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_3hr'); }
			elseif($timeset > 3 && $timeset <= 4) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_4hr'); }
			elseif($timeset > 4 && $timeset <= 5) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_5hr'); }
			elseif($timeset > 5 && $timeset <= 6) { $setChargeOn = idget_data($tbL109,1,'early_checkin_charges_6hr'); }
			elseif($timeset > 6) { $setChargeOn = 0; }
		} elseif($charges == 'checkout') {
			if($timeset == 1) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_1hr'); }
			elseif($timeset > 1 && $timeset <= 2) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_2hr'); }
			elseif($timeset > 2 && $timeset <= 3) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_3hr'); }
			elseif($timeset > 3 && $timeset <= 4) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_4hr'); }
			elseif($timeset > 4 && $timeset <= 5) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_5hr'); }
			elseif($timeset > 5 && $timeset <= 6) { $setChargeOn = idget_data($tbL109,1,'late_checkout_charges_6hr'); }
			elseif($timeset > 6) { $setChargeOn = 100; }
		}

		return $setChargeOn;
	}
	

	function wgt_booking_status($status) {
		switch ($status) {
			case 'Checking In':
			$bkStatus = "CheckedIn";
			break;

			case 'Reserving':
			$bkStatus = "Reserved";
			break;

			case 'Temp Reserve':
			$bkStatus = "Temporary Reserved";
			break;

			case 'Checking Out':
			$bkStatus = "CheckedOut";
			break;

			case 'No Show':
			$bkStatus = "NoShow";
			break;

			case 'Cancelling':
			$bkStatus = "Cancelled";
			break;

			default:
			$bkStatus = "Unknown";
			break;
		}

		return $bkStatus;
	}


	function chkHoteltaxes($charge) {
		
		global $tbL35;
		
		$query = array("taxname"=>$charge,"isactive"=>"Yes");
		$result = mysqli_data_checkr($tbL35,'(*)',$query);

		if($result == true) { $avail = 1; }
		else { $avail = 0; }

		return $avail;
	}

	$htx1 = chkHoteltaxes(1);
	$htx2 = chkHoteltaxes(2);
	$htx3 = chkHoteltaxes(3);


	$additionalQuery = "";
	$xpst_query = array("isreceivable"=>"Yes");
	$get_receivables = mysqli_data_fetch($tbL24,'id,name',$xpst_query,'array');

	$gh_gc_modes = "";

	if(is_array($get_receivables) && count($get_receivables) > 0) {
		foreach($get_receivables as $key => $val) {
			$gh_gc_modes .= $val['id'].',';
		}
	}

	$gh_gc_modes = substr_replace($gh_gc_modes,'',-1,1);

?>