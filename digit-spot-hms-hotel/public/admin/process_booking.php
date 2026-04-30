<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

//include "../../includes/uom.php";
include "../../includes/common_data_vars.php";

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
	createDatabasetable($var_tbl_134); //create a table for this post
	createDatabasetable($var_tbl_138); //create a table for this post


	//create booking number
	$booking_dataproperty = array("datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
	$is_inserted = mysqli_data_insert($tbL133,$booking_dataproperty,'');
	if(isset($is_inserted) && $is_inserted == 2) { $new_booking_id = $mysqli_id; }
	else { $new_booking_id = 0; }

	$booking_prefix = idget_data($tbL76,1,'prefixtext');
	$invoice_prefix = idget_data($tbL77,1,'prefixtext');

	$booking_number = $booking_prefix.$new_booking_id;

	//log transit & source of biz
	$transit_dataproperty = array("booking_number"=>$booking_number);
	mysqli_data_insert($tbL128,$transit_dataproperty,'');

	//tag new booking as unsettled
	$unstl_query = array("booking_number"=>$booking_number);
	$unstl_dataproperty = array("booking_number"=>$booking_number,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL143,$unstl_dataproperty,$unstl_query);
				

	$current_time = $server_get_time;
	$previous_date = date("Y-m-d",strtotime("-1 day"));
	//$this_2day = $server_get_date;

	//create a log file
	$log_message = "Create new booking (booking number: ".$booking_number.")";
	$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

	if(isset($_POST['customer-type']) && $_POST['customer-type'] == 'individual') {
		$biller = 1; $guest_bill_to = 0;
	} elseif(isset($_POST['customer-type']) && $_POST['customer-type'] == 'corporate') {
		$biller = 2; $guest_bill_to = $_POST['cspg'];
	} elseif(isset($_POST['customer-type']) && $_POST['customer-type'] == 'agent') {
		$biller = 3; $guest_bill_to = 0;
	} elseif(isset($_POST['customer-type']) && $_POST['customer-type'] == 'e-booking') {
		$biller = 4; $guest_bill_to = 0;
	} elseif(isset($_POST['customer-type']) && $_POST['customer-type'] == 'complimentary') {
		$biller = 5; $guest_bill_to = $_POST['complimentary'];
	} elseif(isset($_POST['customer-type']) && $_POST['customer-type'] == 'packages') {
		$biller = 6; $guest_bill_to = 0;
	}

	//check if is bill to room
	if(isset($_POST['bill-to-room']) && !empty($_POST['bill-to-room'])) {
		$allow_bill_to_room = $_POST['bill-to-room'];
	} else {
		$allow_bill_to_room = null;
	}

	//indicate whose the bill is charged to e.g cash,charge room,complimentary,group,staff
	if(isset($allow_bill_to_room) && $allow_bill_to_room == 'Yes') {
		$this_bill_type = 2;
	} else {
		if(isset($biller) && $biller == 1) {
			$this_bill_type = 1;
		} else if(isset($biller) && $biller == 2) {
			$this_bill_type = 4;
		} else if(isset($biller) && $biller == 5) {
			$this_bill_type = 3;
		} else {
			$this_bill_type = 2;
		}
	}

	//indicate who will pay the bill
	if(isset($_POST['payment-by']) && !empty($_POST['payment-by'])) {
		$allow_payment_by = $_POST['payment-by'];
	} else {
		$allow_payment_by = null;
	}

	//indicate if this booking is temporary reservation
	if(isset($_POST['temporary-reservation']) && $_POST['temporary-reservation'] == 'Yes') {
		$temp_reserve = "Yes";
	} else {
		$temp_reserve = "No";
	}

	$start_date = $_POST['checkin']; $end_date = $_POST['checkout'];
	$temp_reserve_date = $_POST['tr_date'];

	$noofdays = $_POST['noofdays'];
	$noofrooms = $_POST['noofrooms'];
	$roomtype = $_POST['roomtype'];
	$defaultAmount = $_POST['unitprice'];


	//compute biller charging options
	
	if(isset($biller) && $biller == 2) {
		$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0);
		$corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
		
		$tx_query_fs = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>1,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_fs,'noarray');
		$tx_query_ss = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>2,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');
		$tx_query_ts = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>3,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');

		if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
			if($corporate_tariff_settings[1] == 'Amount') {
				$_a_unitprice = $corporate_tariff_settings[2];
				$_a_totalprice = ($_a_unitprice * $noofrooms) * $noofdays;
				$_a_discount = 0;

				if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $_a_consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $_a_consumption_tax = 0; }
				
				if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $_a_service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $_a_service_charge = 0; }
				
				if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $_a_value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $_a_value_added_tax = 0; }
				
				$_a_other_charges = 0;

			} elseif($corporate_tariff_settings[1] == 'Percentage') {
				$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
				$_a_unitprice = $defaultAmount - $inPercent;
				$_a_totalprice = ($_a_unitprice * $noofrooms) * $noofdays;
				$_a_discount = $inPercent;

				if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $_a_consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $_a_consumption_tax = 0; }
				
				if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $_a_service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $_a_service_charge = 0; }
				
				if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $_a_value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $_a_value_added_tax = 0; }
				
				$_a_other_charges = 0;
			}
		} else {
			$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
			$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
			$_a_unitprice = $defaultAmount - $inPercent;
			$_a_totalprice = ($_a_unitprice * $noofrooms) * $noofdays;
			$_a_discount = $inPercent;

			if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $_a_consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $_a_consumption_tax = 0; }
				
			if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $_a_service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $_a_service_charge = 0; }
				
			if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $_a_value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $_a_value_added_tax = 0; }
				
			$_a_other_charges = 0;
		}

	} else {
		$_a_unitprice = $_POST['unitprice'];
		$_a_totalprice = $_POST['totalprice'];
		$_a_discount = 0;

		$_a_consumption_tax = ($gh_get_consumption_tax / 100) * $_a_totalprice;
		$_a_value_added_tax = ($gh_get_vat / 100) * $_a_totalprice;
		$_a_service_charge = ($gh_get_service_charge / 100) * $_a_totalprice;
		$_a_other_charges = 0;
	}

	$_a_bill_amount = $_a_totalprice + $_a_consumption_tax + $_a_value_added_tax + $_a_service_charge;


	if(isset($biller) && $biller == 2) {
		$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0);
		$corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
		
		$tx_query_fs = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>1,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_fs,'noarray');
		$tx_query_ss = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>2,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');
		$tx_query_ts = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>3,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');

		if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
			
			if($corporate_tariff_settings[1] == 'Amount') {
				$unitprice = $corporate_tariff_settings[2];
				$totalprice = ($unitprice * 1) * 1; //$noofdays
				$discount = 0;

				if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }
				
				if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }
					
				if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $value_added_tax = 0; }
					
				$other_charges = 0;

			} elseif($corporate_tariff_settings[1] == 'Percentage') {
				$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
				$unitprice = $defaultAmount - $inPercent;
				$totalprice = ($unitprice * 1) * 1; //$noofdays
				$discount = $inPercent;

				if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }
				
				if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }
					
				if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $value_added_tax = 0; }
					
				$other_charges = 0;
			}
		} else {
			$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
			$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
			$unitprice = $defaultAmount - $inPercent;
			$totalprice = ($unitprice * 1) * 1; //$noofdays
			$discount = $inPercent;

			if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }
				
			if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }
					
			if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $value_added_tax = 0; }
					
			$other_charges = 0;
		}

	} else {
		$unitprice = $_POST['unitprice'];
		$totalprice = $_POST['totalprice'];
		$discount = 0;

		$consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice;
		$value_added_tax = ($gh_get_vat / 100) * $totalprice;
		$service_charge = ($gh_get_service_charge / 100) * $totalprice;
		$other_charges = 0;
	}

	$bill_amount = $totalprice + $consumption_tax + $value_added_tax + $service_charge;
	

	$hotelseason = $_POST['hotelseason'];
	$hotelseasonday = $_POST['hotelseasonday'];

	$room_number = $_POST['roomnumber'];
	$customer_title = $_POST['title'];
	$customer_firstname = $_POST['firstname'];
	$customer_lastname = $_POST['lastname'];
	$customer_phonenumber = $_POST['phonenumber'];
	$customer_emailaddress = $_POST['emailaddress'];

	
	//run guest detail

	$guest_dataproperty=""; $primary_guest=""; $customer_name=""; $this_customer_id=""; $guest_code="";

	$csc_arry=array();

	for($r=0; $r < $noofrooms; $r++) {
		if($r == 0) { $primary_guest = 1; } else { $primary_guest = 0; }
		$customer_name = ucwords(strtolower($customer_firstname[$r])).' '.ucwords(strtolower($customer_lastname[$r]));
		$guest_dataproperty = array("primary_guest"=>$primary_guest,"booking_number"=>$booking_number,"billtype"=>$this_bill_type,"billto"=>$guest_bill_to,"salutation"=>$customer_title[$r],"name"=>$customer_name,"mobile"=>$customer_phonenumber[$r],"emailaddress"=>$customer_emailaddress[$r],"remarks"=>"Inhouse Booking","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL102,$guest_dataproperty,'');
		$this_customer_id = $mysqli_id;

		if(isset($primary_guest) && $primary_guest == 1) { $_SESSION['for_pg'] = $this_customer_id; }
		else { $_SESSION['for_pg'] = $_SESSION['for_pg']; }

		array_push($csc_arry,$this_customer_id);

		$guest_code = $guest_number_prefix.$this_customer_id;

		$guest_cs_query = array("id"=>$this_customer_id);
		$guest_cs = array("guest_code"=>$guest_code);
		mysqli_data_update($tbL102,$guest_cs,$guest_cs_query);
	}

	#for booking taxes
	$insert_data = array("booking_number"=>$booking_number,"guest_id"=>$_SESSION['for_pg']);
	mysqli_data_insert($tbL139,$insert_data,'');

	//end of program

	
	$invoice2_dataproperty = array("booking_number"=>$booking_number,"userid"=>$userSignedIn,"customerid"=>$_SESSION['for_pg'],"sub_total"=>$_a_totalprice,"discount_amount"=>$_a_discount,"tax_amount"=>$_a_value_added_tax,"consumption_tax_amount"=>$_a_consumption_tax,"service_charge"=>$_a_service_charge,"other_charges"=>$_a_other_charges,"bill_amount"=>$_a_bill_amount,"balance"=>$_a_bill_amount,"hotel_season"=>$hotelseason,"hotel_season_day"=>$hotelseasonday,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	//$invoice2_dataproperty = array("booking_number"=>$booking_number,"userid"=>$userSignedIn,"customerid"=>$_SESSION['for_pg'],"sub_total"=>0,"discount_amount"=>0,"tax_amount"=>0,"consumption_tax_amount"=>0,"service_charge"=>0,"other_charges"=>0,"bill_amount"=>0,"balance"=>0,"hotel_season"=>$hotelseason,"hotel_season_day"=>$hotelseasonday,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	if(isset($_POST['checkinbutton'])) {
		
		$the_room_status = 3;
		$the_hk_room_status = idget_data($tbL113,1,'mark_all_checkin_rooms');

		//create invoice information
		$is_inserted2 = mysqli_data_insert($tbL130,$invoice2_dataproperty,'');
		$new_invoice_id = $mysqli_id;
		$invoice_number = $invoice_prefix.$new_invoice_id;

		$invoice_data1_query = array("id"=>$new_invoice_id);
		$invoice_sub1_dataproperty = array("invoice_number"=>$invoice_number);
		mysqli_data_update($tbL130,$invoice_sub1_dataproperty,$invoice_data1_query);
		
		
		//run this if guest checks in before default set checkin time
		$default_checkin_timeStamp = str_replace(':', '', $predefined_manual_checkin_time);
		$this_checkin_timeStamp = str_replace(':', '', $current_time);
		
		if($default_checkin_timeStamp > $this_checkin_timeStamp) {

			for($r=0; $r < $noofrooms; $r++) {
				
				#bill guest (each room) for previous stay
				$daily_invoice_dataproperty = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"roomid"=>$room_number[$r],"customerid"=>$csc_arry[$r],"booking_type"=>$biller,"sub_total"=>$totalprice,"discount_amount"=>$discount,"tax_amount"=>$value_added_tax,"consumption_tax_amount"=>$consumption_tax,"service_charge"=>$service_charge,"other_charges"=>$other_charges,"bill_amount"=>$bill_amount,"bill_date"=>$previous_date,"bill_time"=>$current_time,"status"=>"Early Checkin Charges","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL134,$daily_invoice_dataproperty,'');

				#room and occupaying details
				$room_d1_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"customerid"=>$csc_arry[$r],"userid"=>$userSignedIn,"startdate"=>$previous_date,"endate"=>$end_date,"noofdays"=>$noofdays,"checkin"=>1,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d1_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

				$room_d2_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"userid"=>$userSignedIn,"startdate"=>$previous_date,"endate"=>$end_date,"noofdays"=>$noofdays,"day"=>1,"checkin"=>1,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d2_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

			}
		}

		//end of program

		
		//current charges state

		/*$is_inserted3 = mysqli_data_insert($tbL130,$invoice2_dataproperty,'');

		if(isset($is_inserted3) && $is_inserted3 == 2) {
				
			$new_invoice_id = $mysqli_id;
			$invoice_number = $invoice_prefix.$new_invoice_id;

			$invoice_data2_query = array("id"=>$new_invoice_id);
			$invoice_sub2_dataproperty = array("invoice_number"=>$invoice_number);
			mysqli_data_update($tbL130,$invoice_sub2_dataproperty,$invoice_data2_query);*/

			#check for advanced payment
			if((isset($_POST['payment-type']) && $_POST['payment-type'] >= 1) && (isset($_POST['amount-deposited']) && $_POST['amount-deposited'] > 1)) {
				$pay_dataproperty = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"customerid"=>$_SESSION['for_pg'],"booking_type"=>$biller,"amount"=>escape_data($_POST['amount-deposited']),"payment_mode"=>$_POST['payment-type'],"cheque_number"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$is_inserted4 = mysqli_data_insert($tbL131,$pay_dataproperty,'');

				if(isset($is_inserted4) && $is_inserted4 == 2) {
					$new_receipt_id = $mysqli_id;
					$receipt_number = $receipt_prefix.$new_receipt_id;

					$receipt_data2_query = array("id"=>$new_receipt_id);
					$receipt_sub2_dataproperty = array("receipt_number"=>$receipt_number);
					mysqli_data_update($tbL131,$receipt_sub2_dataproperty,$receipt_data2_query);
				}
			}
		
			for($r=0; $r < $noofrooms; $r++) {
			
				#active guest
				$active_guest_dataproperty = array("roomid"=>$room_number[$r],"customerid"=>$csc_arry[$r],"userid"=>$userSignedIn,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d4_constrain = array("roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL98,$active_guest_dataproperty,$room_d4_constrain);

				#room and occupaying details
				$room_d1_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"customerid"=>$csc_arry[$r],"userid"=>$userSignedIn,"startdate"=>$start_date,"endate"=>$end_date,"noofdays"=>$noofdays,"checkin"=>1,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d1_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

				$room_d2_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"userid"=>$userSignedIn,"startdate"=>$start_date,"endate"=>$end_date,"noofdays"=>$noofdays,"checkin"=>1,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d2_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

				#guest occupancy detail
				$guest_occupancy_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"adult"=>1,"occupancy_type"=>0,"booking_type"=>$biller,"checkin"=>$start_date,"checkout"=>$end_date,"status"=>"Checked In","checkin_date"=>$server_get_date,"checkin_time"=>$server_get_time,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d3_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL127,$guest_occupancy_dataproperty,$room_d3_constrain);

				#update room housekeeping status
				$get_ifstatus = idget_fdata($tbL94,'roomid',$room_number[$r],'room_status_id');
				if(isset($get_ifstatus) && $get_ifstatus >= 1) {
					$room_hk_dataproperty = array("housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					$room_hk_query = array("roomid"=>$room_number[$r]);
					mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
				} else {
					$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
				}

				$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL95,$room_hk_dataproperty,'');
				
			}

			#guest activities
			$_activity_msg = "Currently checked into the room by ".$server_get_date." ".$server_get_time;
			$guest_activities_dataproperty = array("booking_number"=>$booking_number,"customerid"=>$_SESSION['for_pg'],"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
		//}

		//end of program

		?>
			<script>
				window.addEventListener('focus',function() {
					writeObjheader('g-header-notification','Processing Request');
					writeObjheader('g-message-notification','Please wait while system complete request..');
					objDisplay('notifybox');
					autohidePopupBox('notifybox',90000);
				},false);

				window.addEventListener('load',function() {
					window.location.href = "workspace.php?logs=new-booking&booking=<?php echo $booking_number; ?>";
				},false);
			</script>
		<?php
	}


	if(isset($_POST['reservebutton'])) {
		
		$the_room_status = 6;
		$the_hk_room_status = 4;

		$is_inserted3 = mysqli_data_insert($tbL130,$invoice2_dataproperty,'');

		if(isset($is_inserted3) && $is_inserted3 == 2) {
				
			$new_invoice_id = $mysqli_id;
			$invoice_number = $invoice_prefix.$new_invoice_id;

			$invoice_data2_query = array("id"=>$new_invoice_id);
			$invoice_sub2_dataproperty = array("invoice_number"=>$invoice_number);
			mysqli_data_update($tbL130,$invoice_sub2_dataproperty,$invoice_data2_query);

			#check for advanced payment
			if((isset($_POST['payment-type']) && $_POST['payment-type'] >= 1) && (isset($_POST['amount-deposited']) && $_POST['amount-deposited'] > 1)) {
				$pay_dataproperty = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"customerid"=>$_SESSION['for_pg'],"booking_type"=>$biller,"amount"=>escape_data($_POST['amount-deposited']),"payment_mode"=>$_POST['payment-type'],"cheque_number"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$is_inserted4 = mysqli_data_insert($tbL131,$pay_dataproperty,'');

				if(isset($is_inserted4) && $is_inserted4 == 2) {
					$new_receipt_id = $mysqli_id;
					$receipt_number = $receipt_prefix.$new_receipt_id;

					$receipt_data2_query = array("id"=>$new_receipt_id);
					$receipt_sub2_dataproperty = array("receipt_number"=>$receipt_number);
					mysqli_data_update($tbL131,$receipt_sub2_dataproperty,$receipt_data2_query);
				}
			}
		
			for($r=0; $r < $noofrooms; $r++) {
			
				#room and occupaying details
				$room_d1_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"customerid"=>$csc_arry[$r],"userid"=>$userSignedIn,"startdate"=>$start_date,"endate"=>$end_date,"noofdays"=>$noofdays,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d1_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

				$room_d2_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"userid"=>$userSignedIn,"startdate"=>$start_date,"endate"=>$end_date,"noofdays"=>$noofdays,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d2_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

				#guest occupancy detail
				$guest_occupancy_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"adult"=>1,"occupancy_type"=>0,"booking_type"=>$biller,"checkin"=>$start_date,"checkout"=>$end_date,"status"=>"Reserved","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); $room_d3_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL127,$guest_occupancy_dataproperty,$room_d3_constrain);

				#update room housekeeping status
				$get_ifstatus = idget_fdata($tbL94,'roomid',$room_number[$r],'room_status_id');
				if(isset($get_ifstatus) && $get_ifstatus >= 1) {
					$room_hk_dataproperty = array("housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					$room_hk_query = array("roomid"=>$room_number[$r]);
					mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
				} else {
					$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
				}

				$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL95,$room_hk_dataproperty,'');
				
			}

			#guest activities
			$_activity_msg = "Currently reserved room for later checkin";
			$guest_activities_dataproperty = array("booking_number"=>$booking_number,"customerid"=>$_SESSION['for_pg'],"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		}

		//end of program

		?>
			<script>
				window.addEventListener('focus',function() {
					writeObjheader('g-header-notification','Processing Request');
					writeObjheader('g-message-notification','Please wait while system complete request..');
					objDisplay('notifybox');
					autohidePopupBox('notifybox',90000);
				},false);

				window.addEventListener('load',function() {
					window.location.href = "workspace.php?logs=new-booking&booking=<?php echo $booking_number; ?>";
				},false);
			</script>
		<?php
	}


	if(isset($_POST['tempreservationbutton'])) {

		$the_room_status = 7;
		$the_hk_room_status = 4;

		$is_inserted3 = mysqli_data_insert($tbL130,$invoice2_dataproperty,'');

		if(isset($is_inserted3) && $is_inserted3 == 2) {
				
			$new_invoice_id = $mysqli_id;
			$invoice_number = $invoice_prefix.$new_invoice_id;

			$invoice_data2_query = array("id"=>$new_invoice_id);
			$invoice_sub2_dataproperty = array("invoice_number"=>$invoice_number);
			mysqli_data_update($tbL130,$invoice_sub2_dataproperty,$invoice_data2_query);

			#check for advanced payment
			if((isset($_POST['payment-type']) && $_POST['payment-type'] >= 1) && (isset($_POST['amount-deposited']) && $_POST['amount-deposited'] > 1)) {
				$pay_dataproperty = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"customerid"=>$_SESSION['for_pg'],"booking_type"=>$biller,"amount"=>escape_data($_POST['amount-deposited']),"payment_mode"=>$_POST['payment-type'],"cheque_number"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$is_inserted4 = mysqli_data_insert($tbL131,$pay_dataproperty,'');

				if(isset($is_inserted4) && $is_inserted4 == 2) {
					$new_receipt_id = $mysqli_id;
					$receipt_number = $receipt_prefix.$new_receipt_id;

					$receipt_data2_query = array("id"=>$new_receipt_id);
					$receipt_sub2_dataproperty = array("receipt_number"=>$receipt_number);
					mysqli_data_update($tbL131,$receipt_sub2_dataproperty,$receipt_data2_query);
				}
			}
		
			for($r=0; $r < $noofrooms; $r++) {
			
				#room and occupaying details
				$room_d1_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"tempreserved"=>$temp_reserve,"tempdatereserved"=>$temp_reserve_date,"customerid"=>$csc_arry[$r],"userid"=>$userSignedIn,"startdate"=>$start_date,"endate"=>$end_date,"noofdays"=>$noofdays,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d1_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

				$room_d2_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"stateid"=>$the_room_status,"userid"=>$userSignedIn,"startdate"=>$previous_date,"endate"=>$end_date,"noofdays"=>$noofdays,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_d2_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

				#guest occupancy detail
				$guest_occupancy_dataproperty = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r],"adult"=>1,"occupancy_type"=>0,"booking_type"=>$biller,"checkin"=>$start_date,"checkout"=>$end_date,"status"=>"Temp. Reserved","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); $room_d3_constrain = array("booking_number"=>$booking_number,"roomid"=>$room_number[$r]);
				mysqli_data_insert($tbL127,$guest_occupancy_dataproperty,$room_d3_constrain);

				
				#update room housekeeping status
				/*
				$get_ifstatus = idget_fdata($tbL94,'roomid',$room_number[$r],'room_status_id');
				if(isset($get_ifstatus) && $get_ifstatus >= 1) {
					$room_hk_dataproperty = array("housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					$room_hk_query = array("roomid"=>$room_number[$r]);
					mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
				} else {
					$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
				}

				$room_hk_dataproperty = array("roomid"=>$room_number[$r],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL95,$room_hk_dataproperty,'');
				*/
				
			}

		}

		//end of program

		?>
			<script>
				window.addEventListener('focus',function() {
					writeObjheader('g-header-notification','Processing Request');
					writeObjheader('g-message-notification','Please wait while system complete request..');
					objDisplay('notifybox');
					autohidePopupBox('notifybox',90000);
				},false);

				window.addEventListener('load',function() {
					window.location.href = "workspace.php?logs=new-booking&booking=<?php echo $booking_number; ?>";
				},false);
			</script>
		<?php
	}

?>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr" align="center">
	<div class="cs-width-400 white-theme pads20 top-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="g-header-notification" class="large red-font"></h4>
		<small id="g-message-notification" class="block-element top-push-10"></small>
	</div>
</div>