<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_;
include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B3WF_PATH.ROOT_FLD._USRP_; 

sessionIsChecked(PAGE_AUTHEN_SID,'../','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/common_data_vars.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	createDatabasetable($var_tbl_89); //create a table for this post
	createDatabasetable($var_tbl_90); //create a table for this post
	createDatabasetable($var_tbl_91); //create a table for this post
	createDatabasetable($var_tbl_92); //create a table for this post
	createDatabasetable($var_tbl_93); //create a table for this post
	createDatabasetable($var_tbl_97); //create a table for this post
	createDatabasetable($var_tbl_122); //create a table for this post
	createDatabasetable($var_tbl_123); //create a table for this post
	createDatabasetable($var_tbl_124); //create a table for this post
	createDatabasetable($var_tbl_125); //create a table for this post
	createDatabasetable($var_tbl_126); //create a table for this post
	createDatabasetable($var_tbl_127); //create a table for this post
	createDatabasetable($var_tbl_128); //create a table for this post
	createDatabasetable($var_tbl_129); //create a table for this post
	createDatabasetable($var_tbl_130); //create a table for this post
	createDatabasetable($var_tbl_133); //create a table for this post
	createDatabasetable($var_tbl_144); //create a table for this post


	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'newbooking')) {
		
		$smdl = "frontdesk";
		
		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		#check if this guest id exist and get virtual copy of guest information
		$fgc = $request_data_json['fqguest'];
		$vgc = $request_data_json['virtualguest'];
		
		if(isset($vgc) && $vgc > 0) {
			$virtual_guest_code = escape_data($vgc);
			$guest_query = array("id"=>$virtual_guest_code);
			$chk_guest = mysqli_data_checkr($tbL102,'id',$guest_query);

			if($chk_guest == true) {
				$gvd = array();
				$coldataset = "photo,address,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,gender,age,dob,pob,nationality,immi_status,allien_regno,employer,phoneno,zip_code,country_date_checkin,next_destination,id_issue_date,id_issue_place,current_address,probable_destination,passport_no,issue_date,expiry_date,issue_place,visa_validity,virtual_guest_code,primary_guest";
				$get_guest = mysqli_data_fetch($tbL102,$coldataset,$guest_query,'noarray');
				array_push($gvd,$get_guest[0]); array_push($gvd,$get_guest[1]); array_push($gvd,$get_guest[2]);
				array_push($gvd,$get_guest[3]); array_push($gvd,$get_guest[4]); array_push($gvd,$get_guest[5]);
				array_push($gvd,$get_guest[6]); array_push($gvd,$get_guest[7]); array_push($gvd,$get_guest[8]);
				array_push($gvd,$get_guest[9]); array_push($gvd,$get_guest[10]); array_push($gvd,$get_guest[11]);
				array_push($gvd,$get_guest[12]); array_push($gvd,$get_guest[13]); array_push($gvd,$get_guest[14]);
				array_push($gvd,$get_guest[15]); array_push($gvd,$get_guest[16]); array_push($gvd,$get_guest[17]);
				array_push($gvd,$get_guest[18]); array_push($gvd,$get_guest[19]); array_push($gvd,$get_guest[20]);
				array_push($gvd,$get_guest[21]); array_push($gvd,$get_guest[22]); array_push($gvd,$get_guest[23]);
				array_push($gvd,$get_guest[24]); array_push($gvd,$get_guest[25]); array_push($gvd,$get_guest[26]);
				array_push($gvd,$get_guest[27]); array_push($gvd,$get_guest[28]); array_push($gvd,$get_guest[29]);
				array_push($gvd,$get_guest[30]);
			} else {
				$gvd = "";
			}
		} else {
			$gvd = "";
		}

		$ampm = date('A',strtotime($server_get_time));

		$onbH = $request_data_json['addonbooking'];
		$wgt_booking_class = $request_data_json['bookingclass'];

		$wgt_booking_type = $request_data_json['bookingtype'][0];
		$wgt_bill_to = $request_data_json['bookingtype'][1];
		$wgt_bill_type = $request_data_json['bookingtype'][2];

		$wgt_bill_room = $request_data_json['billroom'][0]['isbill'];
		$wgt_bill_room_services = $request_data_json['billroom'][0]['roomservices'];

		$wgt_room_type = $request_data_json['lodging'][0]['roomtype'];

		if($ampm == 'AM' && $wgt_booking_class == 'Checking In') {
			$numeric_time = intval($server_get_time);
			if($numeric_time < 6) {
				$df_start = $request_data_json['lodging'][0]['startdate'];
				$df_end = $request_data_json['lodging'][0]['endate'];

				$wgt_start_date = date('Y-m-d',strtotime($df_start.'-1 day'));
				$wgt_end_date = date('Y-m-d',strtotime($df_end.'-1 day'));
			} else {
				$wgt_start_date = $request_data_json['lodging'][0]['startdate'];
				$wgt_end_date = $request_data_json['lodging'][0]['endate'];
			}

		} elseif($ampm == 'AM' && $wgt_booking_class == 'Reserving' && $request_data_json['lodging'][0]['startdate'] == $server_get_date) {
			$numeric_time = intval($server_get_time);
			if($numeric_time < 6) {
				$df_start = $request_data_json['lodging'][0]['startdate'];
				$df_end = $request_data_json['lodging'][0]['endate'];

				$wgt_start_date = date('Y-m-d',strtotime($df_start.'-1 day'));
				$wgt_end_date = date('Y-m-d',strtotime($df_end.'-1 day'));
			} else {
				$wgt_start_date = $request_data_json['lodging'][0]['startdate'];
				$wgt_end_date = $request_data_json['lodging'][0]['endate'];
			}
		} else {
			$numeric_time = "";
			$wgt_start_date = $request_data_json['lodging'][0]['startdate'];
			$wgt_end_date = $request_data_json['lodging'][0]['endate'];
		}

		$wgt_no_of_rooms = $request_data_json['lodging'][0]['noofrooms'];
		$wgt_room_rate = $request_data_json['lodging'][0]['rate'];
		$wgt_room_total = $request_data_json['lodging'][0]['total'];
		$wgt_no_of_days = $request_data_json['lodging'][0]['stay'];
		$wgt_temp_date = $request_data_json['lodging'][0]['tempreservedate'];

		$wgt_payment_mode = $request_data_json['payment'][0]['paymentmode'];
		$wgt_amount_paid = $request_data_json['payment'][0]['amountpaid'];
		$wgt_cheque_no = $request_data_json['payment'][0]['chequeno'];
		$wgt_remark = $request_data_json['payment'][0]['detail'];

		$wgt_guests = $request_data_json['guest'];

		$wgt_tax = $request_data_json['billsummary'][0]['tax'];
		$wgt_consumption = $request_data_json['billsummary'][0]['consumption'];
		$wgt_service_charge = $request_data_json['billsummary'][0]['servicecharge'];
		$wgt_discount = $request_data_json['billsummary'][0]['discount'];
		$wgt_total_sum = $request_data_json['billsummary'][0]['totalsumup'];

		/*if($wgt_tax > 0) { $xwgt_tax = $wgt_tax / $wgt_no_of_days; } else { $xwgt_tax = $wgt_tax; }
		if($wgt_consumption > 0) { $xwgt_consumption = $wgt_consumption / $wgt_no_of_days; } else { $xwgt_consumption = $wgt_consumption; }
		if($wgt_service_charge > 0) { $xwgt_service_charge = $wgt_service_charge / $wgt_no_of_days; } else { $xwgt_service_charge = $wgt_service_charge; }
		if($wgt_discount > 0) { $xwgt_discount = $wgt_discount / $wgt_no_of_days; } else { $xwgt_discount = $wgt_discount; }*/

		$wgt_no_of_guests = count($wgt_guests);

		if($wgt_tax > 0) { $xwgt_tax = ($wgt_tax / $wgt_no_of_days) / $wgt_no_of_guests; } else { $xwgt_tax = $wgt_tax; }
		if($wgt_consumption > 0) { $xwgt_consumption = ($wgt_consumption / $wgt_no_of_days) / $wgt_no_of_guests; } else { $xwgt_consumption = $wgt_consumption; }
		if($wgt_service_charge > 0) { $xwgt_service_charge = ($wgt_service_charge / $wgt_no_of_days) / $wgt_no_of_guests; } else { $xwgt_service_charge = $wgt_service_charge; }
		if($wgt_discount > 0) { $xwgt_discount = ($wgt_discount / $wgt_no_of_days) / $wgt_no_of_guests; } else { $xwgt_discount = $wgt_discount; }

		#------------------------------------------------------------------------------------------------------

		//get time difference from checkin time and default checkin time
		$booking_checkintime = $wgt_start_date.' '.$server_get_time;
		$default_checkintime = $wgt_start_date.' '.$wgt_checkin_time;

		if(strtotime($wgt_checkin_time) > strtotime($server_get_time)) {
			$wghr = getHrs($booking_checkintime,$default_checkintime);
			$wgt_inout_per_charge = wgt_inout_charges($wghr,'checkin');
		} else {
			$wgt_inout_per_charge = 0;
		}
		
		/*$day_time_sets = daytimeDiffs($booking_checkintime,$default_checkintime);

		$wgday = trim($day_time_sets[1]); $wghr = trim($day_time_sets[2]);
		$wgday = intval($wgday); $wghr = intval($wghr);

		if((isset($wgday) && $wgday > 0) && (isset($wghr) && $wghr > 0)) {
			$wgt_inout_per_charge = wgt_inout_charges($wghr,'checkin');
		} else {
			$wgt_inout_per_charge = 0;
		}*/

		#------------------------------------------------------------------------------------------------------

		//create new booking number
		if(isset($wgt_booking_type) && $wgt_booking_type == 'corporate') { $isdiscount = 1; }
		else { $isdiscount = 0; }
		
		$new_booking_id = ""; $booking_number = "";
		$isAddon = 0;

		$booking_prefix = idget_data($tbL76,1,'prefixtext');
		$invoice_prefix = idget_data($tbL77,1,'prefixtext');

		if(isset($onbH) && !empty($onbH)) {
			$new_booking_id = str_replace($booking_prefix,'',$onbH);
			$isAddon = 1;
		} else {
			$booking_sql = array("datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$isbooking = mysqli_data_insert($tbL133,$booking_sql,'');
			if(isset($isbooking) && $isbooking == 2) { $new_booking_id = $mysqli_id; }
			$isAddon = 0;
		}
		
		$booking_number = $booking_prefix.$new_booking_id;
		$invoice_number = ""; $is_invoice_not_separate = 0;

		if(isset($wgt_bill_type) && ($wgt_bill_type == 'Group Owner' || $wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Complimentary')) {
			$invoice_sql = array("status"=>1);
			mysqli_data_insert($tbL148,$invoice_sql,'');
			$invoice_number = $invoice_prefix.$mysqli_id;
			$is_invoice_not_separate = 1;
		}

		if((isset($isAddon) && $isAddon == 1) && (isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner'))  {
			$invoice_number = idget_fdata($tbL102,'booking_number',$onbH,'invoice_number');
			$is_invoice_not_separate = 1;
		}

		#------------------------------------------------------------------------------------------------------

		//log guest transit mode & source of contact
		$transit_sql = array("booking_number"=>$booking_number);
		mysqli_data_insert($tbL128,$transit_sql,'');

		#------------------------------------------------------------------------------------------------------

		$cg = $wgt_bill_to; //for corporate paid by guest

		if(isset($wgt_booking_type) && $wgt_booking_type == 'corporate' && $wgt_bill_type == 'Guest') { $wgt_bill_to = 0; }
		else { $wgt_bill_to = $wgt_bill_to; }


		//log booking status
		$bkg_class_query = array("booking_number"=>$booking_number);
		$bkg_class_sql = array("booking_number"=>$booking_number,"booking_type"=>$wgt_booking_type,"bill_type"=>$wgt_bill_type,"bill_to"=>$wgt_bill_to,"bill_to_g"=>$cg,"reservation"=>$wgt_booking_class,"isbill_to_room"=>$wgt_bill_room,"billing_services"=>$wgt_bill_room_services,"checkin_date"=>$wgt_start_date,"checkin_time"=>$server_get_time,"checkout_date"=>$wgt_end_date,"checkout_time"=>$wgt_checkout_time,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizid);

		mysqli_data_insert($tbL130,$bkg_class_sql,$bkg_class_query);

		#------------------------------------------------------------------------------------------------------

		//log guest detail and room occupancy status

		$usePryguest = ucwords(strtolower($wgt_guests[0]['firstname'])).' '.ucwords(strtolower($wgt_guests[0]['lastname'])).' | Mobile: '.$wgt_guests[0]['phone'];

		switch ($wgt_booking_class) {
			case 'Checking In':
				$room_status = 3;
				$house_keeping_status = 6;
				$checkin_by_user = $userSignedIn;
				$early_checkin_charges = ($wgt_inout_per_charge / 100) * $wgt_room_rate;
				$change_housekeeping = "yes";
				$chargeroom = "yes";
				$room_occupancy_status = "CheckedIn";
				$guestLogMessage = "Guest: {$usePryguest} was checkedin into the room as at ".date('d/m/Y',strtotime($server_get_date))." ".$server_get_date." by ".$admin_name;
				break;
			
			case 'Reserving':
				$room_status = 6;
				$house_keeping_status = 4;
				$checkin_by_user = 0;
				$early_checkin_charges = 0;
				$change_housekeeping = "yes";
				$chargeroom = "no";
				$room_occupancy_status = "Reserved";
				$guestLogMessage = "Room(s) was reserved (with booking number: ".$booking_number.") for guest ({$usePryguest}) as at ".date('d/m/Y',strtotime($server_get_date))." ".$server_get_time." by ".$admin_name;
				break;

			case 'Temp Reserve':
				$room_status = 7;
				$house_keeping_status = 4;
				$checkin_by_user = 0;
				$early_checkin_charges = 0;
				$change_housekeeping = "no";
				$chargeroom = "no";
				$room_occupancy_status = "Temp. Reserved";
				$guestLogMessage = "";
				break;

			default:
				$room_status = 0;
				$house_keeping_status = 0;
				$checkin_by_user = 0;
				$early_checkin_charges = 0;
				$change_housekeeping = "no";
				$chargeroom = "no";
				$room_occupancy_status = "Unknown";
				break;
		}

		$room_packs_arry = array();
		$invoice_packs_arry = array();
		$guest_packs_arry = array();

		$count_guest = 0; $primary_guest = ""; $guest_number = ""; $wgt_room_hk_status = "";
		$ths_room_id = ""; $sms2phone = "";

		//get sender & notification message to initiate sms
		$sender = _SHORT_NAME;
		$composemsg = $gh_get_notification_message;

		$room_prefix = ""; $room_number = ""; $chargeThisLate = 0;

		for($g=0; $g < count($wgt_guests); $g++) {
			
			$count_guest += 1;

			$ths_room_id = $wgt_guests[$g]['room'];
			array_push($room_packs_arry,$ths_room_id);
			
			if($count_guest == 1) {
				if(isset($isAddon) && $isAddon == 1) { $primary_guest = 0; } else { $primary_guest = 1; }
				//$sms2phone = serializePhone($wgt_guests[$g]['phone']);
				//sendSMS($sms2phone,$sender,$composemsg);
			} else {
				$primary_guest = 0;
			}

			if(!isset($is_invoice_not_separate) || $is_invoice_not_separate == 0) {
				$invoice_sql = array("status"=>1);
				mysqli_data_insert($tbL148,$invoice_sql,'');
				$invoice_number = $invoice_prefix.$mysqli_id;
			}

			array_push($invoice_packs_arry,$invoice_number);

			#guest
			if(is_array($gvd)) {
				if($primary_guest == 1) {
					$guest_sql = array("primary_guest"=>$primary_guest,"booking_type"=>$wgt_booking_type,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"isbill_to_room"=>$wgt_bill_room,"billing_services"=>$wgt_bill_room_services,"guest_code"=>"P","salutation"=>$wgt_guests[$g]['title'],"fname"=>ucwords(strtolower($wgt_guests[$g]['firstname'])),"lname"=>ucwords(strtolower($wgt_guests[$g]['lastname'])),"mobile"=>$wgt_guests[$g]['phone'],"emailaddress"=>$wgt_guests[$g]['email'],"photo"=>$gvd[0],"gender"=>$gvd[9],"dob"=>$gvd[11],"pob"=>$gvd[12],"age"=>$gvd[10],"nationality"=>$gvd[13],"immi_status"=>$gvd[14],"allien_regno"=>$gvd[15],"employer"=>$gvd[16],"phoneno"=>$gvd[17],"zip_code"=>$gvd[18],"country_date_checkin"=>$gvd[19],"next_destination"=>$gvd[20],"id_issue_date"=>$gvd[21],"id_issue_place"=>$gvd[22],"current_address"=>$gvd[23],"probable_destination"=>$gvd[24],"passport_no"=>$gvd[25],"issue_date"=>$gvd[26],"expiry_date"=>$gvd[27],"issue_place"=>$gvd[28],"visa_validity"=>$gvd[29],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL102,$guest_sql,'');
					$guest_number = $mysqli_id;
				} else {
					$guest_sql = array("primary_guest"=>$primary_guest,"booking_type"=>$wgt_booking_type,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"isbill_to_room"=>$wgt_bill_room,"billing_services"=>$wgt_bill_room_services,"guest_code"=>"P","salutation"=>$wgt_guests[$g]['title'],"fname"=>ucwords(strtolower($wgt_guests[$g]['firstname'])),"lname"=>ucwords(strtolower($wgt_guests[$g]['lastname'])),"mobile"=>$wgt_guests[$g]['phone'],"emailaddress"=>$wgt_guests[$g]['email'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

					if(strlen($wgt_guests[$g]['phone']) == 11) {
						
						$guest_query = array("mobile"=>$wgt_guests[$g]['phone']);
						$chk_guest_exist = mysqli_data_checkr($tbL102,'(*)',$guest_query);

						if($chk_guest_exist == true) {
							
							$coldataset = "photo,address,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,gender,age,dob,pob,nationality,immi_status,allien_regno,employer,phoneno,zip_code,country_date_checkin,next_destination,id_issue_date,id_issue_place,current_address,probable_destination,passport_no,issue_date,expiry_date,issue_place,visa_validity,virtual_guest_code,primary_guest";
							$get_guest = mysqli_data_fetch($tbL102,$coldataset,$guest_query,'noarray');

							$guest_sql['photo'] = $get_guest[0]; $guest_sql['address'] = $get_guest[1];
							$guest_sql['city'] = $get_guest[2]; $guest_sql['state'] = $get_guest[3];
							$guest_sql['country'] = $get_guest[4]; $guest_sql['means_of_identification'] = $get_guest[5];
							$guest_sql['identification_number'] = $get_guest[6]; $guest_sql['occupation'] = $get_guest[7];
							$guest_sql['period_of_stay'] = $get_guest[8]; $guest_sql['gender'] = $get_guest[9];
							$guest_sql['age'] = $get_guest[10]; $guest_sql['dob'] = $get_guest[11]; $guest_sql['pob'] = $get_guest[12];
							$guest_sql['nationality'] = $get_guest[13]; $guest_sql['immi_status'] = $get_guest[14];
							$guest_sql['allien_regno'] = $get_guest[15]; $guest_sql['employer'] = $get_guest[16];
							$guest_sql['phoneno'] = $get_guest[17]; $guest_sql['zip_code'] = $get_guest[18];
							$guest_sql['country_date_checkin'] = $get_guest[19]; $guest_sql['next_destination'] = $get_guest[20];
							$guest_sql['id_issue_date'] = $get_guest[21]; $guest_sql['id_issue_place'] = $get_guest[22];
							$guest_sql['current_address'] = $get_guest[23]; $guest_sql['probable_destination'] = $get_guest[24];
							$guest_sql['passport_no'] = $get_guest[25]; $guest_sql['issue_date'] = $get_guest[26];
							$guest_sql['expiry_date'] = $get_guest[27]; $guest_sql['issue_place'] = $get_guest[28];
							$guest_sql['visa_validity'] = $get_guest[29];
							
						}
					}

					mysqli_data_insert($tbL102,$guest_sql,'');

					$guest_number = $mysqli_id;
					$g_queryset = array("id"=>$guest_number);
					$coldatarry = array("virtual_guest_code"=>$guest_number);
					mysqli_data_update($tbL102,$coldatarry,$g_queryset);
				}
			} else {
				$guest_sql = array("primary_guest"=>$primary_guest,"booking_type"=>$wgt_booking_type,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"isbill_to_room"=>$wgt_bill_room,"billing_services"=>$wgt_bill_room_services,"guest_code"=>"P","salutation"=>$wgt_guests[$g]['title'],"fname"=>ucwords(strtolower($wgt_guests[$g]['firstname'])),"lname"=>ucwords(strtolower($wgt_guests[$g]['lastname'])),"mobile"=>$wgt_guests[$g]['phone'],"emailaddress"=>$wgt_guests[$g]['email'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				
				if(strlen($wgt_guests[$g]['phone']) == 11) {
						
					$guest_query = array("mobile"=>$wgt_guests[$g]['phone']);
					$chk_guest_exist = mysqli_data_checkr($tbL102,'(*)',$guest_query);

					if($chk_guest_exist == true) {
						
						$coldataset = "photo,address,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,gender,age,dob,pob,nationality,immi_status,allien_regno,employer,phoneno,zip_code,country_date_checkin,next_destination,id_issue_date,id_issue_place,current_address,probable_destination,passport_no,issue_date,expiry_date,issue_place,visa_validity,virtual_guest_code,primary_guest";
						$get_guest = mysqli_data_fetch($tbL102,$coldataset,$guest_query,'noarray');

						$guest_sql['photo'] = $get_guest[0]; $guest_sql['address'] = $get_guest[1];
						$guest_sql['city'] = $get_guest[2]; $guest_sql['state'] = $get_guest[3];
						$guest_sql['country'] = $get_guest[4]; $guest_sql['means_of_identification'] = $get_guest[5];
						$guest_sql['identification_number'] = $get_guest[6]; $guest_sql['occupation'] = $get_guest[7];
						$guest_sql['period_of_stay'] = $get_guest[8]; $guest_sql['gender'] = $get_guest[9];
						$guest_sql['age'] = $get_guest[10]; $guest_sql['dob'] = $get_guest[11]; $guest_sql['pob'] = $get_guest[12];
						$guest_sql['nationality'] = $get_guest[13]; $guest_sql['immi_status'] = $get_guest[14];
						$guest_sql['allien_regno'] = $get_guest[15]; $guest_sql['employer'] = $get_guest[16];
						$guest_sql['phoneno'] = $get_guest[17]; $guest_sql['zip_code'] = $get_guest[18];
						$guest_sql['country_date_checkin'] = $get_guest[19]; $guest_sql['next_destination'] = $get_guest[20];
						$guest_sql['id_issue_date'] = $get_guest[21]; $guest_sql['id_issue_place'] = $get_guest[22];
						$guest_sql['current_address'] = $get_guest[23]; $guest_sql['probable_destination'] = $get_guest[24];
						$guest_sql['passport_no'] = $get_guest[25]; $guest_sql['issue_date'] = $get_guest[26];
						$guest_sql['expiry_date'] = $get_guest[27]; $guest_sql['issue_place'] = $get_guest[28];
						$guest_sql['visa_validity'] = $get_guest[29];
						
					}
				}
				
				mysqli_data_insert($tbL102,$guest_sql,'');
				
				$guest_number = $mysqli_id;
				$g_queryset = array("id"=>$guest_number);
				if(isset($fgc) && is_numeric($fgc)) { $coldatarry = array("virtual_guest_code"=>$fgc); }
				else { $coldatarry = array("virtual_guest_code"=>$guest_number); }
				mysqli_data_update($tbL102,$coldatarry,$g_queryset);
			}

			array_push($guest_packs_arry,$guest_number);


			#if there is early checkin

			$room_prefix = idget_data($tbL56,$ths_room_id,'roomprefix');
			$room_number = idget_data($tbL56,$ths_room_id,'roomnumber');

			if($early_checkin_charges > 0 && $room_status == 3 && ($wgt_bill_type == 'Group Owner' || $wgt_bill_type == 'Guest')) {

				//$early_checkin_charges = '-'.$early_checkin_charges;
				$invoice_number = "EARLYCHECKIN";
				$details = "Early checkin charges for room no. ".$room_prefix.$room_number;

				/*$sql_py_pst_data = array("biller"=>0,"sales_point"=>"booking","booking_number"=>$booking_number,"invoice_number"=>strtoupper($invoice_number),"customerid"=>$guest_number,"transaction_type"=>"early checkin","amount"=>$early_checkin_charges,"payment_mode"=>0,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');*/

				$pst_query = array("booking_number"=>$booking_number,"roomid"=>$ths_room_id,"bill_date"=>$server_get_date);
				$pst_field = array("charge_type"=>"individual","billto"=>0,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$wgt_room_type,"roomid"=>$ths_room_id,"customerid"=>$guest_number,"day"=>0,"room_amount"=>$early_checkin_charges,"discount_amount"=>0,"tax_amount"=>0,"consumption_tax_amount"=>0,"service_charge"=>0,"occupancy_charges"=>0,"extrabed_charges"=>0,"charge"=>"yes","ischarged"=>1,"bill_date"=>$server_get_date,"wkf"=>2,"status"=>"Successful","room_status"=>$room_occupancy_status,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

				mysqli_data_insert($tbL134,$pst_field,$pst_query);

				$chargeThisLate = 1;

			} else {
				$chargeThisLate = 0;
			}

			#room
			$room_query = array("booking_number"=>$booking_number,"customerid"=>$guest_number,"roomid"=>$ths_room_id);
			$room_sql = array("booking_number"=>$booking_number,"customerid"=>$guest_number,"room_type_id"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"adult"=>1,"noofdays"=>$wgt_no_of_days,"reservation"=>$wgt_booking_class,"holdtill"=>$wgt_temp_date,"checkin_date"=>$wgt_start_date,"checkin_time"=>$server_get_time,"checkout_date"=>$wgt_end_date,"checkout_time"=>$wgt_checkout_time,"checkin_byuser"=>$checkin_by_user,"early_checkin_charges"=>$chargeThisLate,"userid"=>$userSignedIn,"status"=>$room_occupancy_status,"isdiscount"=>$isdiscount,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL127,$room_sql,$room_query);

			#housekeeping
			if(isset($change_housekeeping) && $change_housekeeping == 'yes' && $ths_room_id > 0) {
				
				/*$wgt_room_hk_status = idget_fdata($tbL94,'roomid',$ths_room_id,'roomid');
				if(isset($wgt_room_hk_status) && $wgt_room_hk_status == $ths_room_id) {
					$hk_query = array("roomid"=>$ths_room_id);
					$hk_sql = array("room_type"=>$wgt_room_type,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed due to recent booking","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);
				} else {
					$hk_query = "";
					$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed due to recent booking","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL94,$hk_sql,$hk_query);
				}*/

				$hk_query = array("roomid"=>$ths_room_id);
				$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed due to recent booking","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);

				$hk_query = "";
				$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed due to recent booking","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);
			}
		}


		#------------------------------------------------------------------------------------------------------

		//daily inovice charges
		
		//$wgt_tax = ($gh_get_vat / 100) * $wgt_room_rate;
		//$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_room_rate;
		//$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_room_rate;

		$weekdayBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'nameweekday');
		$dateBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'daterange');

		if(is_array($room_packs_arry) && count($room_packs_arry) >= 1) {
			
			if(isset($wgt_room_type) && $wgt_room_type > 0) {
				if($wgt_bill_to == 0) {
					$actual_room_price = idget_data($tbL52,$wgt_room_type,'defaultprice');
					$actual_tax = ($gh_get_vat / 100) * $actual_room_price;
					$actual_service_charges = ($gh_get_service_charge / 100) * $actual_room_price;
					$actual_consumption = ($gh_get_consumption_tax / 100) * $actual_room_price;
				} else {
					$actual_room_price = $wgt_room_rate;
					$actual_tax = $xwgt_tax;
					$actual_service_charges = $xwgt_service_charge;
					$actual_consumption = $xwgt_consumption;
				}
			}

			$charged_days = array();
			$lp = 0; $apply_charge_room = 0; $charge_status = ""; $charge_time = ""; $charge_room_status = "";
			
			foreach($room_packs_arry as $rrid) {
				
				$getGst = $guest_packs_arry[$lp];
				$getInv = $invoice_packs_arry[$lp];
				
				$getWkn = "";
				
				for($i=1; $i <= $wgt_no_of_days; $i++) {
					$getWkn = $i - 1;
					$getWk = $weekdayBtwn[$getWkn];
					$getDt = $dateBtwn[$getWkn];

					// && $wgt_booking_class == 'Checking In'

					if(strtotime($getDt) < strtotime($server_get_date) && $getDt == $server_get_auditdate) {
						$apply_charge_room = 1;
						$charge_status = "Successful";
						$charge_time = $server_get_time;
						$charge_room_status = "CheckedIn";
					} else {
						$apply_charge_room = 0;
						$charge_status = "Pending";
						$charge_time = "00:00:00";
						$charge_room_status = $room_occupancy_status;
					}

					$daily_charge_query = "";
					$daily_sql = array("charge_type"=>$wgt_booking_type,"billto"=>$wgt_bill_to,"booking_number"=>$booking_number,"invoice_number"=>$getInv,"room_type_id"=>$wgt_room_type,"roomid"=>$rrid,"customerid"=>$getGst,"day"=>$i,"weekday"=>strtolower($getWk),"actual_room_amount"=>$actual_room_price,"actual_tax_amount"=>$actual_tax,"actual_service_charge"=>$actual_service_charges,"actual_consumption_tax_amount"=>$actual_consumption,"room_amount"=>$wgt_room_rate,"tax_amount"=>$xwgt_tax,"consumption_tax_amount"=>$xwgt_consumption,"service_charge"=>$xwgt_service_charge,"discount_amount"=>$xwgt_discount,"charge"=>$chargeroom,"bill_date"=>$getDt,"bill_time"=>$charge_time,"ischarged"=>$apply_charge_room,"status"=>$charge_status,"room_status"=>$charge_room_status,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					
					mysqli_data_insert($tbL134,$daily_sql,$daily_charge_query);

					$edl = array();

					if($getWk == 'friday' || $getWk == 'Friday' || $getWk == 'saturday' || $getWk == 'Saturday' || $getWk == 'sunday' || $getWk == 'Sunday') {
						
						$edl['bookingno'] = $booking_number;
						$edl['roomtype'] = $wgt_room_type;
						$edl['roomid'] = $rrid;
						$edl['customerid'] = $getGst;
						$edl['dayname'] = $getWk;

						array_push($charged_days,$edl);
					}
				}

				$lp += 1;
			}

			if(isset($isAddon) && $isAddon == 1) {
				
				$pst_wkf = idget_fdata($tbL130,'booking_number',$booking_number,'isweekend_fares');

				#if weekend rate applies, update the record

				if((isset($pst_wkf) && $pst_wkf == 'Yes') && (is_array($charged_days) && count($charged_days) > 0)) {
					for($r=0; $r < count($charged_days); $r++) {

						$wkfprice_query = array("room_type_id"=>$charged_days[$r]['roomtype'],"day"=>$charged_days[$r]['dayname'],"status"=>"Active","deletedata"=>0); $wkfprice = mysqli_data_fetch($tbL146,'price',$wkfprice_query,'noarray');

						$price = $wkfprice[0];
						$wkftax = ($gh_get_vat / 100) * $price;
						$wkfservicecharges = ($gh_get_service_charge / 100) * $price;
						$wkfconsumption = ($gh_get_consumption_tax / 100) * $price;

						$day_query = array("booking_number"=>$charged_days[$r]['bookingno'],"roomid"=>$charged_days[$r]['roomid'],"customerid"=>$charged_days[$r]['customerid'],"weekday"=>$charged_days[$r]['dayname'],"ischarged"=>0);
						$day_sql = array("room_amount"=>$price,"discount_amount"=>0,"tax_amount"=>$wkftax,"consumption_tax_amount"=>$wkfconsumption,"service_charge"=>$wkfservicecharges,"wkf"=>7);

						mysqli_data_update($tbL134,$day_sql,$day_query);

						$price = ""; $wkftax = ""; $wkfservicecharges = ""; $wkfconsumption = "";
					}
				}

				#end
			}
		}

		#------------------------------------------------------------------------------------------------------

		//check for payment
		//log payment if corporate but state as debit
		//or log payment for others if payment is submitted

		$inv_group = array_unique($invoice_packs_arry);
		$inv_count = count($inv_group);
		
		/*if($wgt_bill_type == "Corporate" || $wgt_bill_type == "Complimentary") {
			$ths_invoice_number = $invoice_packs_arry[0];
			//$ths_customer = $guest_packs_arry[0];
			$payment_sql = array("biller"=>$wgt_bill_to,"sales_point"=>"booking","sales_description"=>"payment made for lodging","booking_number"=>$booking_number,"invoice_number"=>$ths_invoice_number,"customerid"=>$wgt_bill_to,"transaction_type"=>"debit","amount"=>$wgt_total_sum,"payment_mode"=>0,"cheque_number"=>0,"detail"=>"noremark","userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL131,$payment_sql,'');
			
			#update receipt number
			$receipt_id = $mysqli_id; $receipt_number = $receipt_prefix.$receipt_id;
			$payment_sql = array("receipt_number"=>$receipt_number);
			$payment_query = array("id"=>$receipt_id);
			mysqli_data_update($tbL131,$payment_sql,$payment_query);

		} else {
			if(isset($wgt_amount_paid) && $wgt_amount_paid > 0) {
				if($inv_count > 1) { $ths_amount = $wgt_amount_paid / $inv_count; }
				else { $ths_amount = $wgt_amount_paid; }
				$ths_invoice_number = ""; $ths_customer = "";
				for($py=0; $py < $inv_count; $py++) {
					$ths_invoice_number = $inv_group[$py];
					$ths_customer = $guest_packs_arry[$py];
					$payment_sql = array("biller"=>0,"sales_point"=>"booking","sales_description"=>"payment made for lodging","booking_number"=>$booking_number,"invoice_number"=>$ths_invoice_number,"customerid"=>$ths_customer,"transaction_type"=>"credit","ispaid"=>1,"amount"=>$ths_amount,"payment_mode"=>$wgt_payment_mode,"cheque_number"=>$wgt_cheque_no,"detail"=>$wgt_remark,"userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL131,$payment_sql,'');

					#update receipt number
					$receipt_id = $mysqli_id; $receipt_number = $receipt_prefix.$receipt_id;
					$payment_sql = array("receipt_number"=>$receipt_number);
					$payment_query = array("id"=>$receipt_id);
					mysqli_data_update($tbL131,$payment_sql,$payment_query);
				}
			}
		}*/

		#------------------------------------------------------------------------------------------------------

		$response_token = array();

		if(isset($request_data)) {
			
			$islogfile = 1;
			$logfile_msg = "Create new booking (booking number: ".$booking_number.")";

			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = $guestLogMessage;

			$response_token['success'] = 200;
			$response_token['param'] = $booking_number;
		} else {
			$islogfile = 0;
			$response_token['success'] = 0;
		}

		$wgt_userid = $userSignedIn;

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'modifybooking')) {

		$smdl = "frontdesk";

		createDatabasetable($var_tbl_309);
		
		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$wgt_booking_number = $request_data_json['bookingnumber'];
		$wgt_userid = $request_data_json['userid'];
		$wgt_remarks = $request_data_json['remarks'];

		$wgt_booking_type = $request_data_json['bookingtype'][0];
		$wgt_bill_to = $request_data_json['bookingtype'][1];
		$wgt_bill_type = $request_data_json['bookingtype'][2];

		$bkgtypeBf = idget_fdata($tbL130,'booking_number',$wgt_booking_number,'booking_type');
		$bkgbillBf = idget_fdata($tbL130,'booking_number',$wgt_booking_number,'bill_type');
		$bkgRsBf = idget_fdata($tbL130,'booking_number',$wgt_booking_number,'reservation');
		$bkguserBf = idget_fdata($tbL130,'booking_number',$wgt_booking_number,'userid');
		$bf_wgt_bill_to = idget_fdata($tbL130,'booking_number',$wgt_booking_number,'bill_to');

		$sqlset1 = "SUM(room_amount)";
		$sqlset2 = "SUM(tax_amount)";
		$sqlset3 = "SUM(consumption_tax_amount)";
		$sqlset4 = "SUM(service_charge)";
		$sqlset5 = "SUM(discount_amount)";

		$queryset = "booking_number='{$wgt_booking_number}'";
		$wgt_total_room_amount = mysqli_arithmetic_data($tbL134,$sqlset1,$queryset);
		$wgt_total_tax_amount = mysqli_arithmetic_data($tbL134,$sqlset2,$queryset);
		$wgt_total_consumption_tax_amount = mysqli_arithmetic_data($tbL134,$sqlset3,$queryset);
		$wgt_total_service_charge = mysqli_arithmetic_data($tbL134,$sqlset4,$queryset);
		$wgt_total_discount_amount = mysqli_arithmetic_data($tbL134,$sqlset5,$queryset);

		//get total bill to credit
		$bf_ths_amount = ($wgt_total_room_amount + $wgt_total_tax_amount + $wgt_total_consumption_tax_amount + $wgt_total_service_charge) - $wgt_total_discount_amount;

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		//update booking status
		if($wgt_booking_type == 'corporate' && $wgt_bill_type == 'Guest') { $charge_biller = 0; }
		else { $charge_biller = $wgt_bill_to; }
		
		$bkg_class_query = array("booking_number"=>$wgt_booking_number);
		$bkg_class_sql = array("booking_type"=>$wgt_booking_type,"bill_type"=>$wgt_bill_type,"bill_to"=>$charge_biller,"bill_to_g"=>$wgt_bill_to,"remarks"=>$wgt_remarks,"ismodified"=>"Yes","booking_type_bf"=>$bkgtypeBf);
		mysqli_data_update($tbL130,$bkg_class_sql,$bkg_class_query);

		//get room type id from daily charges table
		$select_query = array("booking_number"=>$wgt_booking_number,"customerid"=>$th_guest_id,"day"=>1);
		$toroomtypeid = mysqli_data_fetch($tbL134,'room_type_id,roomid',$select_query,'noarray');
		if(isset($toroomtypeid[0])) { $th_room_type_id = $toroomtypeid[0]; $th_room_id = $toroomtypeid[1]; }
		else { $th_room_type_id = 0; $th_room_id = 0; }

		//generate modify info
		$mbkg_class_sql = array("booking_number"=>$wgt_booking_number,"customerid"=>$th_guest_id,"room_type_id"=>$th_room_type_id,"roomid"=>$th_room_id,"current_type"=>$bkgtypeBf,"new_type"=>$wgt_booking_type,"remark"=>$wgt_remarks,"bookedby"=>$bkguserBf,"changedby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL162,$mbkg_class_sql,'');

		
		//get price, discount, tax, servicecharge and consumption base on guest type

		$noformula = 0;

		if(isset($wgt_booking_type) && $wgt_booking_type == 'corporate') {
			if(isset($wgt_bill_to) && $wgt_bill_to >= 1) {
				
				$ischargetype = idget_data($tbL58,$wgt_bill_to,'chargetype');
				$cspg_discount = idget_data($tbL58,$wgt_bill_to,'discount');

				if($ischargetype == 'Unknown') { $gpr = "yes"; $cdiscount = 0; }
				elseif($ischargetype == 'On Discount') { $gpr = "yes"; $cdiscount = $cspg_discount; }
				elseif($ischargetype == 'Corporate Tariff') { $gpr = "no"; $cdiscount = $cspg_discount; }

				if($gpr == 'yes') {

					$dataproperty = "defaultprice,baseprice";
					$json_constrain = array("id"=>$th_room_type_id);
					$room_types = mysqli_data_fetch($tbL52,$dataproperty,$json_constrain,'noarray');

					$discount = $cdiscount;
					$price = $room_types[0];
					$tax = $gh_get_vat;
					$servicecharge = $gh_get_service_charge;
					$consumption = $gh_get_consumption_tax;

					$noformula = 0;

				} else {
			
					$weekday = date('l',strtotime($server_get_date));
					$weekday = strtolower($weekday);

					$cspg = $wgt_bill_to;
					
					$json_constrain = array("corporateid"=>$cspg,"room_type_id"=>$th_room_type_id,"ratetype"=>"naira","day"=>$weekday,"status"=>"Active","deletedata"=>0);
					$corporate_price = mysqli_data_fetch($tbL147,'price',$json_constrain,'noarray');

					$discount = 0;
					$price = $corporate_price[0];
				
					$tax_query3 = array("corporateid"=>$cspg,"room_type_id"=>$th_room_type_id,"taxid"=>3,"status"=>"Active","deletedata"=>0);
					$tax_data3 = mysqli_data_fetch($tbL82,'taxid',$tax_query3,'noarray');
					
					if(isset($tax_data3[0]) && $tax_data3[0] == 3) { $tax = $gh_get_vat; }
					else { $tax = $gh_get_vat; }

					$tax_query2 = array("corporateid"=>$cspg,"room_type_id"=>$th_room_type_id,"taxid"=>2,"status"=>"Active","deletedata"=>0);
					$tax_data2 = mysqli_data_fetch($tbL82,'taxid',$tax_query2,'noarray');

					if(isset($tax_data2[0]) && $tax_data2[0] == 2) { $servicecharge = $gh_get_service_charge; }
					else { $servicecharge = $gh_get_service_charge; }

					$tax_query1 = array("corporateid"=>$cspg,"room_type_id"=>$th_room_type_id,"taxid"=>1,"status"=>"Active","deletedata"=>0);
					$tax_data1 = mysqli_data_fetch($tbL82,'taxid',$tax_query1,'noarray');

					if(isset($tax_data1[0]) && $tax_data1[0] == 1) { $consumption = $gh_get_consumption_tax; }
					else { $consumption = $gh_get_consumption_tax; }

					if((isset($tax_data3[0]) && $tax_data3[0] == 3) && (isset($tax_data2[0]) && $tax_data2[0] == 2)) { $noformula = 1; }
					else { $noformula = 0; }
				}
			}

		} else {

			$noformula = 0;

			$dataproperty = "defaultprice,baseprice";
			$json_constrain = array("id"=>$th_room_type_id);
			$room_types = mysqli_data_fetch($tbL52,$dataproperty,$json_constrain,'noarray');

			if(is_array($room_types)) {

				$discount = 0;
				$price = $room_types[0];
				$tax = $gh_get_vat;
				$servicecharge = $gh_get_service_charge;
				$consumption = $gh_get_consumption_tax;
			}
		}

		//--end

		if($noformula == 0) {
			$accu_taxes = "";
			$cpsg_flat_rate = "";
			$wgt_room_rate = $price;
			$wgt_discount = ($discount / 100) * $wgt_room_rate;
			$wgt_actual_room_rate = $wgt_room_rate - $wgt_discount;
			$wgt_tax = ($tax / 100) * $wgt_actual_room_rate;
			$wgt_consumption = ($consumption / 100) * $wgt_actual_room_rate;
			$wgt_service_charge = ($servicecharge / 100) * $wgt_actual_room_rate;
		} elseif($noformula == 1) {
			$accu_taxes = 100 / (100 + $tax + $servicecharge + $consumption);
			$cpsg_flat_rate = $accu_taxes * $price;
			$cpsg_flat_rate = round($cpsg_flat_rate,2);
			$wgt_room_rate = $cpsg_flat_rate;
			$wgt_discount = ($discount / 100) * $wgt_room_rate;
			$wgt_tax = ($tax / 100) * $wgt_room_rate;
			$wgt_consumption = ($consumption / 100) * $wgt_room_rate;
			$wgt_service_charge = ($servicecharge / 100) * $wgt_room_rate;
		}

		$daily_charge_query = array("booking_number"=>$wgt_booking_number);
		$daily_sql = array("charge_type"=>$wgt_booking_type,"billto"=>$charge_biller,"actual_room_amount"=>$wgt_room_rate,"actual_tax_amount"=>$wgt_tax,"actual_service_charge"=>$wgt_service_charge,"actual_consumption_tax_amount"=>$wgt_consumption,"room_amount"=>$wgt_room_rate,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"discount_amount"=>$wgt_discount,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$isupdated = mysqli_data_update($tbL134,$daily_sql,$daily_charge_query);
		
		//if(isset($isupdated) && $isupdated == 2) {

			if($wgt_bill_type == "Corporate") {
				$ths_customer = $wgt_bill_to;
				$th_biller = $wgt_bill_to;
				$outlet_sql = array("billtype"=>4,"biller"=>$wgt_bill_to);
			} elseif($wgt_bill_type == "Complimentary") {
				$ths_customer = $wgt_bill_to;
				$th_biller = $wgt_bill_to;
				$outlet_sql = array("billtype"=>3,"biller"=>$wgt_bill_to);
			} else {
				$ths_customer = $th_guest_id;
				$th_biller = 0;
			}

			
			if(isset($th_biller) && $th_biller > 0) {
				
				$outlet_charge_query = array("booking_number"=>$wgt_booking_number);
				$upt1 = mysqli_data_update($tbL99,$outlet_sql,$outlet_charge_query);
				$upt2 = mysqli_data_update($tbL100,$outlet_sql,$outlet_charge_query);

				if($wgt_bill_type == "Corporate") {
					
					#retrieve group creditlimt
					$credit_limit = idget_data($tbL58,$wgt_bill_to,'creditlimit');
					$credit_notification_limit = idget_data($tbL58,$wgt_bill_to,'notifylimit');

					#outlet bill
					$sqlset = "SUM(bill_amount)";
					$queryset = "booking_number='{$wgt_booking_number}' AND isreversed=0 AND deletedata=0";
					$get_outlet_charges = mysqli_arithmetic_data($tbL100,$sqlset,$queryset);

					if(!empty($get_outlet_charges) && $get_outlet_charges > 0) {
						
						$new_creditlimit = $credit_limit - $get_outlet_charges;

						#update group creditlimt
						$blc_selection_key = array("id"=>$wgt_bill_to);
						$crl_datasets = array("creditlimit"=>$new_creditlimit);
						mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

						$transaction_desc = "Migrated outlet bills (via modify booking) for booking number: ".$wgt_booking_number;

						$ledger_query = array("cspgid"=>$wgt_bill_to,"transaction_number"=>$wgt_booking_number,"transaction_type"=>"Debit");
						$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$wgt_bill_to,"transaction_number"=>$wgt_booking_number,"transaction_type"=>"Debit","amount"=>$get_outlet_charges,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"pos","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_query);
					}

					#booking bill

					$sqlset = "COUNT(roomid)";

					$queryset = "booking_number='{$wgt_booking_number}' AND deletedata=0";
					$total_nights = mysqli_arithmetic_data($tbL134,$sqlset,$queryset);
					
					$ths_amount = ($wgt_room_rate - $wgt_discount) + ($wgt_tax + $wgt_consumption + $wgt_service_charge);
					$ths_amount = $ths_amount * $total_nights;

					$new_creditlimit = $credit_limit - $ths_amount;

					#update group creditlimt
					$blc_selection_key = array("id"=>$wgt_bill_to);
					$crl_datasets = array("creditlimit"=>$new_creditlimit);
					mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

					$transaction_desc = "Migrated booking bills (via modify booking) for booking number: ".$wgt_booking_number;

					//$ledger_query = array("cspgid"=>$wgt_bill_to,"transaction_number"=>$wgt_booking_number,"transaction_type"=>"Debit");
					$ledger_query = "";
					$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$wgt_bill_to,"transaction_number"=>$wgt_booking_number,"transaction_type"=>"Debit","amount"=>$ths_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_query);
				}
			}


			#if the previous booking is corporate paid corporate that is checkedin

			if($bkgtypeBf == 'corporate' && $bkgbillBf == 'Corporate' && $bkgRsBf == 'Checking In') {

				#retrieve group creditlimt
				$credit_limit = idget_data($tbL58,$bf_wgt_bill_to,'creditlimit');
				$credit_notification_limit = idget_data($tbL58,$bf_wgt_bill_to,'notifylimit');

				$new_creditlimit = $credit_limit + $bf_ths_amount;

				#update group creditlimt
				$blc_selection_key = array("id"=>$bf_wgt_bill_to);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

				$transaction_desc = "Reversed debit booking bills (via modify booking) for booking number: ".$wgt_booking_number;

				$ledger_query = "";
				$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$bf_wgt_bill_to,"transaction_number"=>$wgt_booking_number,"transaction_type"=>"Credit","amount"=>$bf_ths_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_query);
			}


			//remove previous payment transaction

			//$remove_query = array("booking_number"=>$wgt_booking_number);
			//trash_record($tbL131,$remove_query);

		
			//process debit transaction if biller is true

			if(isset($th_biller) && $th_biller >= 1) {
				
				/*$invoice_prefix = idget_data($tbL77,1,'prefixtext');

				$invoice_sql = array("status"=>1);
				mysqli_data_insert($tbL148,$invoice_sql,'');
				$invoice_number = $invoice_prefix.$mysqli_id;

				$sqlset1 = "SUM(room_amount)";
				$sqlset2 = "SUM(tax_amount)";
				$sqlset3 = "SUM(consumption_tax_amount)";
				$sqlset4 = "SUM(service_charge)";
				$sqlset5 = "SUM(discount_amount)";

				$queryset = "booking_number='{$wgt_booking_number}'";
				$wgt_total_room_amount = mysqli_arithmetic_data($tbL134,$sqlset1,$queryset);
				$wgt_total_tax_amount = mysqli_arithmetic_data($tbL134,$sqlset2,$queryset);
				$wgt_total_consumption_tax_amount = mysqli_arithmetic_data($tbL134,$sqlset3,$queryset);
				$wgt_total_service_charge = mysqli_arithmetic_data($tbL134,$sqlset4,$queryset);
				$wgt_total_discount_amount = mysqli_arithmetic_data($tbL134,$sqlset5,$queryset);

				//get total bill to pay
				$ths_amount = ($wgt_total_room_amount + $wgt_total_tax_amount + $wgt_total_consumption_tax_amount + $wgt_total_service_charge) - $wgt_total_discount_amount;

				$ths_invoice_number = $invoice_number;
				$payment_sql = array("biller"=>$th_biller,"sales_point"=>"booking","sales_description"=>"payment made for lodging","booking_number"=>$wgt_booking_number,"invoice_number"=>$ths_invoice_number,"customerid"=>$ths_customer,"transaction_type"=>"debit","ispaid"=>0,"amount"=>$ths_amount,"payment_mode"=>0,"cheque_number"=>"","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"detail"=>"noremark","userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL131,$payment_sql,'');

				#update receipt number
				$receipt_id = $mysqli_id; $receipt_number = $receipt_prefix.$receipt_id;
				$payment_sql = array("receipt_number"=>$receipt_number);
				$payment_query = array("id"=>$receipt_id);
				mysqli_data_update($tbL131,$payment_sql,$payment_query);

				//update new invoice to guest table
				$guest_query = array("booking_number"=>$wgt_booking_number);
				$guest_sql = array("invoice_number"=>$ths_invoice_number);
				mysqli_data_update($tbL102,$guest_sql,$guest_query);*/

				//remove discount option from rooms
				$room_query = array("booking_number"=>$wgt_booking_number);
				$room_sql = array("isdiscount"=>1);
				mysqli_data_update($tbL127,$room_sql,$room_query);

			} else {
				//apply discount option to rooms
				$room_query = array("booking_number"=>$wgt_booking_number);
				$room_sql = array("isdiscount"=>0);
				mysqli_data_update($tbL127,$room_sql,$room_query);
			}
			

			$islogfile = 1;
			$logfile_msg = "Recently modified this booking (booking number: ".$wgt_booking_number.") to ".$wgt_booking_type."-".$wgt_bill_type.". ".$wgt_remarks;

			$isguestAct = 1;
			$pst_booking_number = $wgt_booking_number;
			$guestAct_msg = "Guest booking recently modified to ".$wgt_booking_type." paid by ".$wgt_bill_type.": ".$wgt_remarks;

			$response_token = array();

			$response_token['success'] = 200;
			$response_token['param'] = $wgt_booking_number;
			$response_token['frame'] = 'frame'.$th_guest_id;

			$wgt_json_token = json_encode($response_token);
			echo $wgt_json_token;
		//}
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'applydiscount')) {

		$smdl = "frontdesk";
		
		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$wgt_booking_number = $request_data_json['bookingnumber'];
		$wgt_userid = $request_data_json['userid'];
		$wgt_rooms = $request_data_json['rooms'];
		$wgt_discount = $request_data_json['discount'];

		$uaccess = idget_data($tbL7,$wgt_userid,'uaccess');
		$roleid = idget_data($tbL7,$wgt_userid,'role');

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id,fname,lname',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		$customer_name = $get_guest[1].' '.$get_guest[2];

		$response_token = array();

		if(isset($uaccess) && $uaccess == 'super admin') { $discount_max = 100; }
		else { $discount_max = idget_fdata($tbL30,'roleid',$roleid,'discount'); }

		if((isset($discount_max) && $discount_max > 0) && ($wgt_discount <= $discount_max)) {
		//if((isset($wgt_discount) && isset($discount_max) && $discount_max > 0) && ($wgt_discount > 0 && $wgt_discount <= $discount_max)) {
			
			$room_arry = explode(',',$wgt_rooms);

			if(is_array($room_arry) && count($room_arry) > 0) {
			
				$room_and_type = "";
				
				foreach($room_arry as $room) {
					
					$room_and_type = explode('-',$room);

					$room_query = array("booking_number"=>$wgt_booking_number,"room_type_id"=>$room_and_type[0],"roomid"=>$room_and_type[1],"ischarged"=>0,"deletedata"=>0);
					$wdatasets = "room_amount,tax_amount,consumption_tax_amount,service_charge,room_type_id,roomid,customerid";
					$room_rate = mysqli_data_fetch($tbL134,$wdatasets,$room_query,'noarray');

					//use rack rate set for room type
					$default_room_rate = $room_rate[0];
					//$default_room_rate = idget_data($tbL52,$room_rate[4],'defaultprice');
					
					$discount_amount = ($wgt_discount / 100) * $default_room_rate;
					$new_room_amount = $default_room_rate - $discount_amount;

					if($room_rate[1] > 0 && isset($gh_get_vat)) { $get_tpg = $gh_get_vat; }
					else { $get_tpg = 0; }

					if($room_rate[2] > 0 && isset($gh_get_consumption_tax)) { $get_cpg = $gh_get_consumption_tax; }
					else { $get_cpg = 0; }

					if($room_rate[3] > 0 && isset($gh_get_service_charge)) { $get_spg = $gh_get_service_charge; }
					else { $get_spg = 0; }

					$new_tax_amount = ($get_tpg / 100) * $new_room_amount;
					$new_consumption_amount = ($get_cpg / 100) * $new_room_amount;
					$new_service_charge_amount = ($get_spg / 100) * $new_room_amount;
					
					$fdatasets = array("discount_amount"=>$discount_amount,"tax_amount"=>$new_tax_amount,"consumption_tax_amount"=>$new_consumption_amount,"service_charge"=>$new_service_charge_amount);
					mysqli_data_update($tbL134,$fdatasets,$room_query);

					#-------------------------------------------------------

					$room_query2 = array("booking_number"=>$wgt_booking_number,"roomid"=>$room);
					$room_occupy = mysqli_data_fetch($tbL127,'remarks',$room_query2,'noarray');
					
					if($room_occupy[0] != NULL && $room_occupy[0] != '') {
						$new_remark = $room_occupy[0]." | discount applied: ".$wgt_discount."%";
					} else {
						$new_remark = "discount applied: ".$wgt_discount."%";
					}

					
					$sdatasets = array("remarks"=>$new_remark,"isdiscount"=>1);
					mysqli_data_update($tbL127,$sdatasets,$room_query2);

					$room_query = ""; $fdatasets = ""; $sdatasets = ""; $wdatasets = "";
					$discount_amount = ""; $new_room_amount = ""; $get_tpg = ""; $get_cpg = "";
					$get_spg = ""; $new_tax_amount = ""; $new_consumption_amount = "";
					$new_service_charge_amount = ""; $default_room_rate = "";
				}

				$islogfile = 1;
				$logfile_msg = "Discount was applied to this booking (booking number: ".$wgt_booking_number.")";

				$isguestAct = 1;
				$pst_booking_number = $wgt_booking_number;
				$remark_tag = "discount";
				$guestAct_msg = "{$wgt_discount} percent discount was applied to guest rooms bill on booking number {$wgt_booking_number} for {$customer_name} (primary guest)";

				$response_token['success'] = 200;
				$response_token['param'] = $wgt_booking_number;
				$response_token['frame'] = 'frame'.$th_guest_id;
				$response_token['status'] = 'Ok';

			} else {
				$response_token['success'] = 0;
				$response_token['status'] = 'Error processing data. Please try again';
			}

		} else {
			$response_token['success'] = 0;
			$response_token['status'] = 'Invalid discount value. Please try again';
		}

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'checkoutcharges')) {

		$smdl = "frontdesk";

		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$wgt_booking_number = $request_data_json['bookingnumber'];
		$wgt_userid = $request_data_json['userid'];
		$wgt_status = $request_data_json['checkoutstatus'];

		if(isset($wgt_status) && $wgt_status == 'enable') { $wstatus = 'Yes'; $wlb = "enabled"; }
		elseif(isset($wgt_status) && $wgt_status == 'disable') { $wstatus = 'No'; $wlb = "disabled"; }

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		$sql_query = array("booking_number"=>$wgt_booking_number);
		$sql_data = array("islate_checkout"=>$wstatus);
		$isupdated = mysqli_data_update($tbL130,$sql_data,$sql_query);

		$response_token = array();
		
		if(isset($isupdated) && $isupdated == 2) {
			
			$islogfile = 1;
			$logfile_msg = "Checkout charges status for this booking (booking number: ".$wgt_booking_number.") changed by this user";

			$isguestAct = 1;
			$pst_booking_number = $wgt_booking_number;
			$guestAct_msg = "Guest checkout-charges status was ".$wlb;

			$response_token['success'] = 200;
			$response_token['param'] = $wgt_booking_number;
			$response_token['frame'] = 'frame'.$th_guest_id;
			$response_token['status'] = 'Ok';

		} else {
			$response_token['success'] = 0;
			$response_token['status'] = 'Error processing data. Please try again';
		}

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'excludetaxes')) {

		$smdl = "frontdesk";

		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$sql_data = array();
		$response = 0;

		$wgt_userid = $request_data_json['userid'];
		$wgt_taxes = $request_data_json['taxes'];

		$tx_arry = explode(',',$wgt_taxes);
		
		$wgt_booking_number = $request_data_json['bookingnumber'];

		$additionalQuery = " AND wkf IN(0,7)";
		$queryset = array("booking_number"=>$wgt_booking_number);
		$dataset = "id,room_amount,tax_amount,service_charge,consumption_tax_amount";
		$datasheet = mysqli_data_fetch($tbL134,$dataset,$queryset,'array');
		$additionalQuery = "";

		if(is_array($datasheet)) {
			
			$pst_query = "";
			$pst_field = "";
			
			foreach($datasheet as $key => $val) {

				$pst_query = array("id"=>$val['id']);
				$pst_field = array();

				for($t=0; $t < count($tx_arry); $t++) {
					if($tx_arry[$t] == 1) {
						if($val['consumption_tax_amount'] >= 1) { $pst_field['consumption_tax_amount'] = 0; }
						else { $pst_field['consumption_tax_amount'] = ($gh_get_consumption_tax / 100) * $val['room_amount']; }
					}

					if($tx_arry[$t] == 2) {
						if($val['service_charge'] >= 1) { $pst_field['service_charge'] = 0; }
						else { $pst_field['service_charge'] = ($gh_get_service_charge / 100) * $val['room_amount']; }
					}

					if($tx_arry[$t] == 3) {
						if($val['tax_amount'] >= 1) { $pst_field['tax_amount'] = 0; }
						else { $pst_field['tax_amount'] = ($gh_get_vat / 100) * $val['room_amount']; }
					}
				}

				$isupdated = mysqli_data_update($tbL134,$pst_field,$pst_query);
				if(isset($isupdated) && $isupdated == 2) { $response += 1; }
			}
		}
		
		
		$excl = "";

		if(is_array($tx_arry) && count($tx_arry) >= 1) {
			foreach($tx_arry as $tx) {
				if($tx == 1) { $excl .= "consumption tax,"; }
				if($tx == 2) { $excl .= "service charge,"; }
				if($tx == 3) { $excl .= "value added tax,"; }
			}

			$excls = substr_replace($excl,'',-1,1);
		}

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		$response_token = array();
		
		if(isset($response) && $response >= 1) {
			
			$islogfile = 1;
			$logfile_msg = "Changes were made to the following charges (".$excls.") for booking number (".$wgt_booking_number.") by this user";

			$isguestAct = 1;
			$pst_booking_number = $wgt_booking_number;
			$guestAct_msg = "Inclusive or exclusive changes were made to the following charges (".$excls.")";

			$response_token['success'] = 200;
			$response_token['param'] = $wgt_booking_number;
			$response_token['frame'] = 'frame'.$th_guest_id;
			$response_token['status'] = 'Ok';

		} else {
			$response_token['success'] = 0;
			$response_token['status'] = 'Error processing data. Please try again';
		}

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'weekendfares')) {

		$smdl = "frontdesk";
		
		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$wgt_booking_number = $request_data_json['bookingnumber'];
		$wgt_userid = $request_data_json['userid'];
		$wgt_days = $request_data_json['days'];
		$wgt_wkfstatus = $request_data_json['wkfstatus'];

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		$response_token = array();

		$day_arry = explode(',',$wgt_days);

		if(is_array($day_arry) && count($day_arry) > 0) {
			
			$room_type_id = "";
			$weekday = "";
			$price = 0;

			$wkds = "";
			$iswkf = 0;
			
			foreach($day_arry as $day) {
				$room_type_id = idget_data($tbL134,$day,'room_type_id');
				$weekday = idget_data($tbL134,$day,'weekday');
				$wkds .= $weekday.',';
				
				$wkfprice_query = array("room_type_id"=>$room_type_id,"day"=>$weekday,"status"=>"Active","deletedata"=>0);
				$wkfprice = mysqli_data_fetch($tbL146,'price',$wkfprice_query,'noarray');

				if(isset($wkfprice[0]) && $wkfprice[0] > 0) { $price = $wkfprice[0]; }
				else { $price = 0; }

				if(isset($price) && $price > 0) {
					$wkftax = ($gh_get_vat / 100) * $price;
					$wkfservicecharges = ($gh_get_service_charge / 100) * $price;
					$wkfconsumption = ($gh_get_consumption_tax / 100) * $price;
					
					$day_query = array("id"=>$day,"ischarged"=>0,"wkf"=>0);
					$day_sql = array("room_amount"=>$price,"discount_amount"=>0,"tax_amount"=>$wkftax,"consumption_tax_amount"=>$wkfconsumption,"service_charge"=>$wkfservicecharges,"wkf"=>7);
					$isdata = mysqli_data_update($tbL134,$day_sql,$day_query);

					if(isset($isdata) && $isdata == 2) { $iswkf += 1; }
				}
			}

			if(isset($iswkf) && $iswkf >= 1) {

				$wkds = substr_replace($wkds,'',-1,1);

				$sql_query = array("booking_number"=>$wgt_booking_number);
				$sql_data = array("isweekend_fares"=>$wgt_wkfstatus);
				mysqli_data_update($tbL130,$sql_data,$sql_query);
				
				$islogfile = 1;
				$logfile_msg = "Weekend fares (".$wkds.") was applied to this booking (booking number: ".$wgt_booking_number.")";

				$isguestAct = 1;
				$pst_booking_number = $wgt_booking_number;
				$guestAct_msg = "Guest booking has taken weekend fares charge for (".$wkds."). And it has been effective";

				$response_token['success'] = 200;
				$response_token['param'] = $wgt_booking_number;
				$response_token['frame'] = 'frame'.$th_guest_id;
				$response_token['status'] = 'Ok';

			} else {
				$response_token['success'] = 0;
				$response_token['status'] = 'Error completing request. Please try again';
			}

		} else {
			$response_token['success'] = 0;
			$response_token['status'] = 'Error processing data. Please try again';
		}

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	if((isset($_POST['postdatarequest']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'disableweekendfares')) {

		$smdl = "frontdesk";
		
		$request_data = stripslashes($_POST['postdatarequest']);
		$request_data_json = json_decode($request_data,true);

		$wgt_booking_number = $request_data_json['bookingnumber'];
		$wgt_userid = $request_data_json['userid'];
		$wgt_wkfstatus = $request_data_json['wkfstatus'];

		//get primary guest
		$guest_query = array("booking_number"=>$wgt_booking_number,"primary_guest"=>1,"deletedata"=>0);
		$get_guest = mysqli_data_fetch($tbL102,'id',$guest_query,'noarray');
		if(isset($get_guest[0])) { $th_guest_id = $get_guest[0]; } else { $th_guest_id = 0; }

		$response_token = array();
			
		$price = 0;
		$iswkf = 0;
		
		$wday_query = array("booking_number"=>$wgt_booking_number,"wkf"=>7,"deletedata"=>0);
		$wday_sql = mysqli_data_fetch($tbL134,'id,room_type_id',$wday_query,'array');

		if(is_array($wday_sql)) {
			foreach($wday_sql as $key => $value) {
				
				$price = idget_data($tbL52,$value['room_type_id'],'defaultprice');
				
				if(isset($price) && $price > 0) {
					$wkftax = ($gh_get_vat / 100) * $price;
					$wkfservicecharges = ($gh_get_service_charge / 100) * $price;
					$wkfconsumption = ($gh_get_consumption_tax / 100) * $price;
					
					$day_query = array("id"=>$value['id'],"ischarged"=>0);
					$day_sql = array("room_amount"=>$price,"tax_amount"=>$wkftax,"consumption_tax_amount"=>$wkfconsumption,"service_charge"=>$wkfservicecharges,"wkf"=>0);
					$isdata = mysqli_data_update($tbL134,$day_sql,$day_query);

					if(isset($isdata) && $isdata == 2) { $iswkf += 1; }
				}
			}

			if(isset($iswkf) && $iswkf >= 1) {

				$sql_query = array("booking_number"=>$wgt_booking_number);
				$sql_data = array("isweekend_fares"=>$wgt_wkfstatus);
				mysqli_data_update($tbL130,$sql_data,$sql_query);
				
				$islogfile = 1;
				$logfile_msg = "Weekend fares for this booking (booking number: ".$wgt_booking_number.") was disabled by this user. Normal charges have been applied";

				$isguestAct = 1;
				$pst_booking_number = $wgt_booking_number;
				$guestAct_msg = "Guest booking for weekend fares charges are now disabled. Normal charges have been applied";

				$response_token['success'] = 200;
				$response_token['param'] = $wgt_booking_number;
				$response_token['frame'] = 'frame'.$th_guest_id;
				$response_token['status'] = 'Ok';

			} else {
				$response_token['success'] = 0;
				$response_token['status'] = 'Error completing request. Please try again';
			}

		} else {
			$response_token['success'] = 0;
			$response_token['status'] = 'Error processing data. Please try again';
		}

		$wgt_json_token = json_encode($response_token);
		echo $wgt_json_token;
	}

	#-------------------------------------------------------------------------------------------------------------

	##create a log file
	if(isset($islogfile) && $islogfile == 1) {
		$log_datasets = array("userid"=>$wgt_userid,"logcategory"=>$smdl,"message"=>$logfile_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL8,$log_datasets,'');
	}

	##log guest activities
	if(isset($isguestAct) && $isguestAct == 1) {
		$ths_guest_pry = $ths_customer;
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$ths_guest_pry,"userid"=>$wgt_userid,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
	}
}

?>