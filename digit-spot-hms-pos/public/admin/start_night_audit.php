<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._FUNC_;
include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_;

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/common_data_vars.php";

$_SESSION['return2work'] = 200;

$audit_sql = ""; $night_query = "";
$mode_msg = ""; $mode_button_value = ""; $mode_button_name = "";

$isguestAct = ""; $guestAct_msg = "";

$additionalQuery = " LIMIT 1";
$get_last_run_date = array("status"=>"Pending");
$lrdata = mysqli_data_fetch($tbL136,'audit_date',$get_last_run_date,'noarray');
if(isset($_SESSION['lastrundate'])) { $_SESSION['lastrundate'] = $_SESSION['lastrundate']; }
else { $_SESSION['lastrundate'] = $lrdata[0]; }

$last_run_date = $_SESSION['lastrundate'];
$bill_post_date = $_SESSION['lastrundate'];


$additionalQuery = "";

//update night audit status
$night_query = array("audit_date"=>$last_run_date);
$audit_sql = array("status"=>"Started","audit_time"=>$server_get_time);
mysqli_data_update($tbL136,$audit_sql,$night_query);

if(!isset($_POST['return2frontdesk'])) {
	if(isset($_SESSION['auditStage'])) { $_SESSION['auditStage'] = $_SESSION['kses']; }
	else { $_SESSION['auditStage'] = 1; }
	$audit_sql = array("audit_date"=>$last_run_date,"start_audit"=>"Running");
	if(isset($_GET['pg']) && is_numeric($_GET['pg'])) { unset($_GET['pg']); }
	$mode_msg = "Running.. Please wait, this may take a few seconds";
	$mode_button_value = "Stop";
	$mode_button_name = "return2frontdesk";
} else {
	$_SESSION['kses'] = $_SESSION['auditStage'];
	$_SESSION['auditStage'] = 0;
	$audit_sql = array("start_audit"=>"Pending");
	$mode_msg = "Not running.. Click on resume button to continue";
	$mode_button_value = "Resume";
	$mode_button_name = "return2nightaudit";
}

$night_query = array("deletedata"=>0);
mysqli_data_update('night_audit_ini_tbl',$audit_sql,$night_query);

#-------------------------------------------------------------------------------------------

#perform audit action
#step 1:
if(isset($_POST['saveguestnames'])) {

	$ids = $_POST['id']; $title = $_POST['salutation'];
	$firstname = $_POST['fname']; $lastname = $_POST['lname'];
	$phoneno = $_POST['phoneno']; $email = $_POST['email'];

	$pst_query = "";
	$pst_field = "";

	for($i=0; $i < count($ids); $i++) {
		$pst_query = array("id"=>$ids[$i]);
		$pst_field = array("salutation"=>$title[$i],"fname"=>$firstname[$i],"lname"=>$lastname[$i],"mobile"=>$phoneno[$i],"emailaddress"=>$email[$i]);

		mysqli_data_update($tbL102,$pst_field,$pst_query);
	}
}

#step 2:
if(isset($_POST['saveroomnumbers'])) {

	$ids = $_POST['id']; $room = $_POST['room'];
	
	$pst_query = "";
	$pst_field = "";

	for($i=0; $i < count($ids); $i++) {
		$pst_query = array("id"=>$ids[$i]);
		$pst_field = array("roomid"=>$room[$i]);

		mysqli_data_update($tbL127,$pst_field,$pst_query);
	}
}

#step 3:
if(isset($_POST['applyroomstate'])) {

	$ids = $_POST['id']; $room = $_POST['room']; $roomtype = $_POST['roomtype'];
	$bookingno = $_POST['bookingno']; $invoiceno = $_POST['invoiceno'];
	$roomstate = $_POST['roomstate'];
	
	$wkd_arry = array("Friday","Saturday","Sunday");
	$nwk = date('l',strtotime($server_get_date));

	$new_noofdays = ""; $gdy = 0;
	
	$pst_query = "";
	$pst_field = "";

	$isguestAct = 1;

	for($i=0; $i < count($ids); $i++) {
		
		$dataset = "SELECT * FROM daily_invoice_charges_tbl WHERE booking_number='{$bookingno[$i]}' AND roomid={$room[$i]} AND room_status IN('CheckedIn') AND deletedata=0 ORDER BY id DESC LIMIT 1";

		$query = mysqli_query($mysqli,$dataset);
		$data = @mysqli_fetch_array($query,MYSQLI_ASSOC);

		$actual_checkout_date = date('Y-m-d',strtotime($data['bill_date'] . '+2 day'));
		$dayPs = date('Y-m-d',strtotime($data['bill_date'] . '+1 day'));
		$wkPs = date('l',strtotime($dayPs)); $dayNx = $data['day'] + 1;

		if($roomstate[$i] == 'extension') {
			
			if($data['wkf'] == 7 && in_array($nwk,$wkd_arry)) {
				
				$pst_query = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"bill_date"=>$dayPs);
				$pst_field = array("charge_type"=>$data['charge_type'],"billto"=>$data['billto'],"booking_number"=>$bookingno[$i],"invoice_number"=>$invoiceno[$i],"room_type_id"=>$data['room_type_id'],"roomid"=>$room[$i],"customerid"=>$data['customerid'],"day"=>$dayNx,"weekday"=>$wkPs,"actual_room_amount"=>$data['actual_room_amount'],"actual_tax_amount"=>$data['actual_tax_amount'],"actual_service_charge"=>$data['actual_service_charge'],"actual_consumption_tax_amount"=>$data['actual_consumption_tax_amount'],"room_amount"=>$data['room_amount'],"discount_amount"=>$data['discount_amount'],"tax_amount"=>$data['tax_amount'],"consumption_tax_amount"=>$data['consumption_tax_amount'],"service_charge"=>$data['service_charge'],"occupancy_charges"=>$data['occupancy_charges'],"extrabed_charges"=>$data['extrabed_charges'],"charge"=>"yes","ischarged"=>0,"bill_date"=>$dayPs,"status"=>"Pending","wkf"=>$data['wkf'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

				mysqli_data_insert($tbL134,$pst_field,$pst_query);

			} elseif($data['wkf'] == 7 && !in_array($nwk,$wkd_arry)) {
				
				$chk_nowkf = "SELECT * FROM daily_invoice_charges_tbl WHERE booking_number='{$bookingno[$i]}' AND roomid={$room[$i]} AND room_status IN('CheckedIn') AND wkf=0 AND room_amount IS NOT NULL AND deletedata=0 ORDER BY id DESC LIMIT 1";
				
				$query_nowkf = mysqli_query($mysqli,$chk_nowkf);

				if(@mysqli_num_rows($query_nowkf) == true) {
				
					$data = @mysqli_fetch_array($query_nowkf,MYSQLI_ASSOC);

					$wgt_room_rate = $data['room_amount'];
					$actual_room_amount = $data['actual_room_amount'];

					$wgt_tax = ($gh_get_vat / 100) * $wgt_room_rate;
					$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_room_rate;
					$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_room_rate;
				
					$actual_tax_amount = $data['actual_tax_amount'];
					$actual_consumption_tax_amount = $data['actual_consumption_tax_amount'];
					$actual_service_charge = $data['actual_service_charge'];

					$pst_query = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"bill_date"=>$dayPs);
					$pst_field = array("charge_type"=>$data['charge_type'],"billto"=>$data['billto'],"booking_number"=>$bookingno[$i],"invoice_number"=>$invoiceno[$i],"room_type_id"=>$data['room_type_id'],"roomid"=>$room[$i],"customerid"=>$data['customerid'],"day"=>$dayNx,"weekday"=>$wkPs,"actual_room_amount"=>$actual_room_amount,"actual_tax_amount"=>$actual_tax_amount,"actual_service_charge"=>$actual_service_charge,"actual_consumption_tax_amount"=>$actual_consumption_tax_amount,"room_amount"=>$wgt_room_rate,"discount_amount"=>0,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"occupancy_charges"=>$data['occupancy_charges'],"extrabed_charges"=>$data['extrabed_charges'],"charge"=>"yes","ischarged"=>0,"bill_date"=>$dayPs,"status"=>"Pending","wkf"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

				} else {
					
					$wgt_room_rate = idget_data($tbL52,$data['room_type_id'],'defaultprice');
					$actual_room_amount = $wgt_room_rate;

					$wgt_tax = ($gh_get_vat / 100) * $wgt_room_rate;
					$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_room_rate;
					$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_room_rate;
				
					$actual_tax_amount = $wgt_tax;
					$actual_consumption_tax_amount = $wgt_consumption;
					$actual_service_charge = $wgt_service_charge;

					$pst_query = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"bill_date"=>$dayPs);
					$pst_field = array("charge_type"=>$data['charge_type'],"billto"=>$data['billto'],"booking_number"=>$bookingno[$i],"invoice_number"=>$invoiceno[$i],"room_type_id"=>$data['room_type_id'],"roomid"=>$room[$i],"customerid"=>$data['customerid'],"day"=>$dayNx,"weekday"=>$wkPs,"actual_room_amount"=>$actual_room_amount,"actual_tax_amount"=>$actual_tax_amount,"actual_service_charge"=>$actual_service_charge,"actual_consumption_tax_amount"=>$actual_consumption_tax_amount,"room_amount"=>$wgt_room_rate,"discount_amount"=>0,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"occupancy_charges"=>$data['occupancy_charges'],"extrabed_charges"=>$data['extrabed_charges'],"charge"=>"yes","ischarged"=>0,"bill_date"=>$dayPs,"status"=>"Pending","wkf"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);
				}

				mysqli_data_insert($tbL134,$pst_field,$pst_query);

			} else {
				$pst_query = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"bill_date"=>$dayPs);
				$pst_field = array("charge_type"=>$data['charge_type'],"billto"=>$data['billto'],"booking_number"=>$bookingno[$i],"invoice_number"=>$invoiceno[$i],"room_type_id"=>$data['room_type_id'],"roomid"=>$room[$i],"customerid"=>$data['customerid'],"day"=>$dayNx,"weekday"=>$wkPs,"actual_room_amount"=>$data['actual_room_amount'],"actual_tax_amount"=>$data['actual_tax_amount'],"actual_service_charge"=>$data['actual_service_charge'],"actual_consumption_tax_amount"=>$data['actual_consumption_tax_amount'],"room_amount"=>$data['room_amount'],"discount_amount"=>$data['discount_amount'],"tax_amount"=>$data['tax_amount'],"consumption_tax_amount"=>$data['consumption_tax_amount'],"service_charge"=>$data['service_charge'],"occupancy_charges"=>$data['occupancy_charges'],"extrabed_charges"=>$data['extrabed_charges'],"charge"=>"yes","ischarged"=>0,"bill_date"=>$dayPs,"status"=>"Pending","wkf"=>$data['wkf'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

				mysqli_data_insert($tbL134,$pst_field,$pst_query);
			}

			#-----------------------------end

			$gdy = idget_data($tbL127,$ids[$i],'noofdays');
			$new_noofdays = $gdy + 1;

			$pst_query = array("id"=>$ids[$i]);
			$pst_field = array("noofdays"=>$new_noofdays,"checkout_date"=>$actual_checkout_date);
			mysqli_data_update($tbL127,$pst_field,$pst_query);

			$pst_query = array("booking_number"=>$bookingno[$i]);
			$pst_field = array("checkout_date"=>$actual_checkout_date);
			mysqli_data_update($tbL130,$pst_field,$pst_query);

			#update date of checkout of this room in housekeeping
			$hk_qry = array("roomid"=>$room[$i]);
			$sql_hk = array("endate"=>$actual_checkout_date);
			mysqli_data_update($tbL94,$sql_hk,$hk_qry);	

			//for corporate
			$wgt_bill_type = idget_fdata($tbL130,'booking_number',$bookingno[$i],'bill_type');
			$wgt_bill_to = idget_fdata($tbL130,'booking_number',$bookingno[$i],'bill_to');

			if(isset($wgt_bill_type) && $wgt_bill_type == 'Corporate') {

				if(!empty($wgt_bill_to) && $wgt_bill_to > 0) {

					$ths_amount = ($data['room_amount'] + $data['tax_amount'] + $data['consumption_tax_amount'] + $data['service_charge']) - $data['discount_amount'];

					$credit_limit = idget_data($tbL58,$wgt_bill_to,'creditlimit');
					$credit_notification_limit = idget_data($tbL58,$wgt_bill_to,'notifylimit');
					$new_creditlimit = $credit_limit - $ths_amount;

					$blc_selection_key = array("id"=>$wgt_bill_to);
					$crl_datasets = array("creditlimit"=>$new_creditlimit);
					mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

					$transaction_desc = "Room charged for extension";
					$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$wgt_bill_to,"transaction_number"=>$bookingno[$i],"transaction_type"=>"Debit","amount"=>$ths_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL63,$ledger_dataproperty,'');
				}

				$receipt_id=""; $credit_limit=""; $credit_notification_limit="";
				$new_creditlimit="";
			}

			$guestAct_msg = "Guest room status was updated while doing night audit. The room usage was extended";

		} elseif($roomstate[$i] == 'checkout') {

			$pst_query = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"bill_date"=>$bill_post_date,"ischarged"=>0);
			$pst_field = array("ischarged"=>1,"bill_time"=>$server_get_time,"status"=>"Successful");
			mysqli_data_update($tbL134,$pst_field,$pst_query);

			$pst_query = array("id"=>$ids[$i]);
			$pst_field = array("checkout_date"=>$bill_post_date,"status"=>"CheckedOut","housekeeping_stateid"=>2,"room_status_id"=>4);
			mysqli_data_update($tbL127,$pst_field,$pst_query);

			$pst_query = array("booking_number"=>$bookingno[$i]);
			$pst_field = array("reservation"=>"Checking Out","checkout_date"=>$bill_post_date);
			mysqli_data_update($tbL130,$pst_field,$pst_query);

			$hk_query = array("roomid"=>$room[$i]);
			$hk_sql = array("housekeeping_stateid"=>2,"room_status_id"=>8,"remarks"=>"room status changed to checkout while doing night audit","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_update($tbL94,$hk_sql,$hk_query);

			$hk_query = "";
			$hk_sql = array("room_type"=>$data['room_type_id'],"roomid"=>$room[$i],"housekeeping_stateid"=>2,"room_status_id"=>4,"remarks"=>"room status changed upon room checkout","startdate"=>$dayPs,"endate"=>$dayPs,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL95,$hk_sql,$hk_query);

			$guestAct_msg = "Guest room status was updated while doing night audit. The room was checked-out and billed for the day";
		}

		#create activity for guest transaction
		$guest_activities_dataproperty = array("booking_number"=>$bookingno[$i],"customerid"=>$data['customerid'],"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
	}
}


#step 4:
if(isset($_POST['applyroomstate2'])) {

	$ids = $_POST['id']; $room = $_POST['room']; $roomtype = $_POST['roomtype'];
	$bookingno = $_POST['bookingno']; $invoiceno = $_POST['invoiceno'];
	$roomstate = $_POST['roomstate']; $checkin_date = $_POST['checkin']; $checkout_date = $_POST['checkout'];
	
	$pst_query = "";
	$pst_field = "";

	$isguestAct = 1;

	for($i=0; $i < count($ids); $i++) {
		
		if($roomstate[$i] == 'extension') {
			
			$new_checkin_date = date('Y-m-d',strtotime($checkin_date[$i] . ' +1 days'));
			$new_checkout_date = date('Y-m-d',strtotime($checkout_date[$i] . ' +1 days'));

			$pst_query = array("id"=>$ids[$i]);
			$pst_field = array("checkin_date"=>$new_checkin_date,"checkout_date"=>$new_checkout_date);
			mysqli_data_update($tbL127,$pst_field,$pst_query);

			$pst_query = array("booking_number"=>$bookingno[$i]);
			$pst_field = array("checkin_date"=>$new_checkin_date,"checkout_date"=>$new_checkout_date);
			mysqli_data_update($tbL130,$pst_field,$pst_query);

			$guestAct_msg = "Guest room status was updated while doing night audit. Extension for check-in and check-out date applied";

		} elseif($roomstate[$i] == 'noshow') {

			$sql_uquery = array("id"=>$ids[$i]);
			$sql_udata = array("checkout_date"=>$bill_post_date,"status"=>"No Show","housekeeping_stateid"=>4,"room_status_id"=>5);
			mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$bookingno[$i]);
			$sql_udata = array("reservation"=>"No Show");
			mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$bookingno[$i],"roomid"=>$room[$i],"deletedata"=>0);
			$sql_udata = array("deletedata"=>1);
			mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

			$sql_uquery = array("roomid"=>$room[$i]);
			$sql_udata = array("housekeeping_stateid"=>4,"room_status_id"=>1);
			mysqli_data_update($tbL94,$sql_udata,$sql_uquery);

			$guestAct_msg = "Guest room status was updated while doing night audit. No-show applied for reservation";
		}

		#create activity for guest transaction
		$guest_activities_dataproperty = array("booking_number"=>$bookingno[$i],"customerid"=>$data['customerid'],"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
	}
}


#step 5:
if(isset($_POST['applyroomstate3'])) {

	$ids = $_POST['id']; $room = $_POST['room']; $roomtype = $_POST['roomtype'];
	$bookingno = $_POST['bookingno']; $invoiceno = $_POST['invoiceno'];
	$roomstate = $_POST['roomstate']; $checkin_date = $_POST['checkin']; $checkout_date = $_POST['checkout'];
	
	$pst_query = "";
	$pst_field = "";

	$isguestAct = 1;

	for($i=0; $i < count($ids); $i++) {
		
		$pst_field = array("ischarged"=>1,"bill_time"=>$server_get_time,"status"=>"Successful");
		$pst_query = array("id"=>$ids[$i]);
		
		mysqli_data_update($tbL134,$pst_field,$pst_query);

		$guestAct_msg = "Guest room status was updated while doing night audit. Room charged for the day";

		#create activity for guest transaction
		$guest_activities_dataproperty = array("booking_number"=>$bookingno[$i],"customerid"=>$data['customerid'],"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
	}
}


if(isset($_POST['applycountershiftstate'])) {

	$ids = $_POST['id']; $users = $_POST['user'];
	$csshift = $_POST['csshift']; $cstate = $_POST['cstate'];
	
	$pst_query = "";
	$pst_field = "";

	$posted = 0;

	for($i=0; $i < count($ids); $i++) {
		
		if($cstate[$i] == 'close-counter') {
			
			$pst_field = array("logstatus"=>"Closed");
			$pst_query = array("id"=>$ids[$i]);
			mysqli_data_update($tbL22,$pst_field,$pst_query);

			$pst_field = array("status"=>"Closed");
			$pst_query = array("counterid"=>$csshift[$i]);
			mysqli_data_update($tbL21,$pst_field,$pst_query);

			$pst_field = array("ispast"=>1);
			$pst_query = array("counterid"=>$csshift[$i],"userid"=>$users[$i],"ispast"=>0);
			mysqli_data_update($tbL25,$pst_field,$pst_query);
		}

		if($cstate[$i] == 'close-shift') {
			$pst_field = array("dateclosed"=>$bill_post_date,"closetime"=>$server_get_time,"status"=>"Closed");
			$pst_query = array("id"=>$ids[$i]);
			mysqli_data_update($tbL23,$pst_field,$pst_query);
		}

		$posted += 1;

	}
	
	#Update night audit 
	$night_query = array("audit_date"=>$last_run_date);
	$audit_sql = array("status"=>"Performed","audit_time"=>$server_get_time);
	mysqli_data_update($tbL136,$audit_sql,$night_query);

	$night_query = array("deletedata"=>0);
	$audit_sql = array("start_audit"=>"Pending");
	mysqli_data_update('night_audit_ini_tbl',$audit_sql,$night_query);

	//update business day
	$bizdy_query = array("day"=>$server_get_bizid);
	$bizdy_sql = array("enddate"=>$server_get_date,"endtime"=>$server_get_time,"status"=>1);
	mysqli_data_update($hdy_table,$bizdy_sql,$bizdy_query);

	//create new business day
	$new_day = $server_get_bizid + 1;
	$bizdy_query2 = array("day"=>$new_day);
	$bizdy_sql2 = array("day"=>$new_day,"startdate"=>$server_get_date,"starttime"=>$server_get_time,"status"=>0);
	mysqli_data_insert($hdy_table,$bizdy_sql2,$bizdy_query2);

	unset($_SESSION['lastrundate']);
	header("location: nightAuditreport.php");

} else {
	
	if(isset($_POST['reportstage']) && strtotime($server_get_date) > strtotime($last_run_date)) {

		#Update night audit 
		$night_query = array("audit_date"=>$last_run_date);
		$audit_sql = array("status"=>"Performed","audit_time"=>$server_get_time);
		mysqli_data_update($tbL136,$audit_sql,$night_query);

		$night_query = array("deletedata"=>0);
		$audit_sql = array("start_audit"=>"Pending");
		mysqli_data_update('night_audit_ini_tbl',$audit_sql,$night_query);	

		//update business day
		$bizdy_query = array("day"=>$server_get_bizid);
		$bizdy_sql = array("enddate"=>$server_get_date,"endtime"=>$server_get_time,"status"=>1);
		mysqli_data_update($hdy_table,$bizdy_sql,$bizdy_query);

		//create new business day
		$new_day = $server_get_bizid + 1;
		$bizdy_query2 = array("day"=>$new_day);
		$bizdy_sql2 = array("day"=>$new_day,"startdate"=>$server_get_date,"starttime"=>$server_get_time,"status"=>0);
		mysqli_data_insert($hdy_table,$bizdy_sql2,$bizdy_query2);

		unset($_SESSION['lastrundate']);
		header("location: nightAuditreport");
	}
}


//audit stages
if(isset($_POST['pg']) && is_numeric($_POST['pg'])) {
	$_SESSION['auditStage'] = $_POST['pg'];
} else {
	if(isset($_SESSION['auditStage'])) {
		$_SESSION['auditStage'] = $_SESSION['auditStage'];
	} else {
		$_SESSION['auditStage'] = 1;
	}
}


//required token
$salutations = select_dt_fetch('status','Active',$tbL42,'id','name');

function getroomtypeList($roomtypenumber) {

	global $tbL56,$tbL94,$tbL36;
	global $default_housekeeping_legend;

	$nw_htmlresult = "";

	$cpn_constrain = array("room_type_id"=>$roomtypenumber,"deletedata"=>0,"roomstatus"=>1);
    $cpn_data = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$cpn_constrain,'array');

    if(is_array($cpn_data) && count($cpn_data) > 0) {
    	
    	$housekeeping_room_state = ''; $room_availability = '';
    	
    	$nw_htmlresult .= '<option value="" selected>Choose</option>';

    	foreach ($cpn_data as $key => $value) {
    		
    		$hrs_query = array("roomid"=>$value['id']);
    		$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid,room_status_id',$hrs_query,'noarray');
    		
    		if(isset($hrs_data[0]) && $hrs_data[0] >= 1) {
    			$housekeeping_room_state = '['.idget_data($tbL36,$hrs_data[0],'legendname').']';
    		} else {
    			$housekeeping_room_state = '['.$default_housekeeping_legend.']';
    		}

    		//do not list rooms that are either checkedin, reserved or temp. reserve
    		if($hrs_data[1] != 3 && $hrs_data[1] != 6 && $hrs_data[1] != 7) {
    			$nw_htmlresult .= '<option value="'.$value['id'].'">'.$value['roomprefix'].$value['roomnumber'].' '.$housekeeping_room_state.'</option>';
    		}
    	}

	} else {
		$nw_htmlresult .= '<option value="" selected>No Option!</option>';
	}

	return $nw_htmlresult;
}

?>
<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>

<title><?php echo SOFTWARE_NAME; ?> | Back Office</title>

<div class="bottom-pull-30">
	<div class="fx-position-stick tpscr royal-blue-theme white-font top-pull-10 right-pull-20 bottom-pull-10 left-pull-20">
		<form action="" method="post" class="nomargin">
			<span class="float-right"><input type="submit" name="<?php echo $mode_button_name; ?>" value="<?php echo $mode_button_value; ?>" class="anchor"></span>
			<h3 class="large nobold default-text-font-bold nomargin">Night Audit Mode</h3>
		</form>
	</div>
	<div class="pads20">
		<div class="cs-height-40"></div>
		<h3 id="mode-msg" class="xlarge nobold alignct"><?php echo $mode_msg; ?></h3><br>
		
		<?php
			
			$roomtype = ""; $room = ""; $salutation = ""; $roomlist = "";
			$amount = 0; $paid_amount = 0; $balance_amount = 0;

			$app_user = ""; $counter_name = ""; $shift_name = "";

			$gbil = "SELECT SUM(room_amount) AS 'totalroomAmt',SUM(discount_amount) AS 'totaldiscountAmt',SUM(tax_amount) AS 'totaltaxAmt',SUM(consumption_tax_amount) AS 'totalconsumptionAmt',SUM(service_charge) AS 'totalserviceAmt' FROM daily_invoice_charges_tbl WHERE queryname";

			$gpay = "SELECT SUM(amount) AS 'totalpayAmt' FROM transaction_payment_tbl WHERE queryname";

			if(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 1) {
				
				$dataset = "SELECT * FROM booking_invoice_tbl t1, guest_tbl t2 WHERE t1.booking_number=t2.booking_number AND t1.reservation='Reserving' AND (t2.fname='' OR t2.lname='') AND t1.checkin_date='{$bill_post_date}'";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);
				
				if($rows == true) {

					$dataset_rx = "SELECT t1.booking_number,t1.checkin_date,t1.checkout_date,t2.room_type_id,t2.roomid,t3.id,t3.salutation,t3.fname,t3.lname,t3.mobile,t3.emailaddress FROM booking_invoice_tbl t1, guest_occupancy_detail_tbl t2, guest_tbl t3 WHERE t1.booking_number=t2.booking_number AND t1.booking_number=t3.booking_number AND t1.reservation='Reserving' AND (t3.fname='' OR t3.lname='') AND t1.checkin_date='{$bill_post_date}'";
					
					$query_rx = mysqli_query($mysqli,$dataset_rx);

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="cs-width-1500 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Booking No.</th>
											<th align="center">Room Type</th>
											<th align="center">Room No.</th>
											<th align="center">Check-In</th>
											<th align="center">Check-Out</th>
											<th align="center">Title</th>
											<th align="center">Firstname</th>
											<th align="center">Lastname</th>
											<th align="center">Phone No.</th>
											<th align="center">Email Address</th>
											<th align="center">Amount</th>
										</tr>
										
										<?php
											
											$startnumbr = 0;

											while($row = @mysqli_fetch_array($query_rx,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$roomtype = idget_data($tbL52,$row['room_type_id'],'name');
												$room = idget_data($tbL56,$row['roomid'],'roomprefix');
												$room .= idget_data($tbL56,$row['roomid'],'roomnumber');

												$salutation = idget_data($tbL42,$row['salutation'],'name');
												
												$rbl = "roomid='{$row['roomid']}'";
												$ths_bill = str_replace('queryname',$rbl,$gbil);

												$query_rbl = mysqli_query($mysqli,$ths_bill);
												$ths_amt = @mysqli_fetch_array($query_rbl,MYSQLI_ASSOC);
												$amount = ($ths_amt['totalroomAmt'] + $ths_amt['totaltaxAmt'] + $ths_amt['totalconsumptionAmt'] + $ths_amt['totalserviceAmt']) -  + $ths_amt['totaldiscountAmt'];
												
												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
														</td>
														<td align="center">
															<input class="anchor" onclick="jsxView('<?php echo $row['booking_number']; ?>')" type="text" name="bookingno[]" placeholder="Enter here" value="<?php echo $row['booking_number']; ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="roomtype[]" placeholder="Enter here" value="<?php echo $roomtype; ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="room[]" placeholder="Enter here" value="<?php echo $room; ?>" readonly>
														</td>
														<td align="center">
															<input type="text" name="checkin[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkin_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="checkout[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkout_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<select name="salutation[]" class="no-back-black" required>
																<option value="<?php echo $row['salutation']; ?>"><?php echo $salutation; ?></option>
																<?php echo $salutations; ?>
															</select>
														</td>
														<td align="center">
															<input type="text" name="fname[]" placeholder="Enter here" value="<?php if(!empty($row['fname'])) { echo $row['fname']; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="lname[]" placeholder="Enter here" value="<?php if(!empty($row['lname'])) { echo $row['lname']; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="phoneno[]" placeholder="Enter here" value="<?php if(!empty($row['mobile'])) { echo $row['mobile']; } ?>">
														</td>
														<td align="center">
															<input type="email" name="email[]" placeholder="Enter here" value="<?php if(!empty($row['emailaddress'])) { echo $row['emailaddress']; } ?>">
														</td>
														<td align="center">
															<input type="text" name="amount[]" placeholder="Enter here" value="<?php echo number_format($amount); ?>" readonly required>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="2">
								<input type="submit" name="saveguestnames" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of reservations without guest names : <?php echo $bill_post_date; ?>');
							},false);
						</script>
					<?php

				} else {
					
					?>
						<form id="pg2" action="" method="post">
							<input type="hidden" name="pg" value="2">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','No issue with reservation guest names. Checking reservation rooms, please wait..');
								setTimeout(() => { document.getElementById('pg2').submit(); },5000);
							},false);
						</script>
					<?php
				}

			} elseif(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 2) {

				//$dataset = "SELECT * FROM guest_occupancy_detail_tbl t1, guest_tbl t2 WHERE t1.booking_number=t2.booking_number AND (t1.roomid=0 OR t1.roomid='' OR t1.roomid is null) AND t1.status NOT IN('Cancelled','No Show','Swapped','Upgraded','Downgraded') AND t1.datelogged='{$bill_post_date}'";

				$dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE status IN('Reserved') AND (roomid=0 OR roomid='' OR roomid is null) AND checkin_date='{$bill_post_date}'";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);
				
				if($rows == true) {

					//$dataset_rx = "SELECT t1.booking_number,t1.checkin_date,t1.checkout_date,t1.room_type_id,t1.roomid,t1.id,t2.salutation,t2.fname,t2.lname,t2.mobile,t2.emailaddress FROM guest_occupancy_detail_tbl t1, guest_tbl t2 WHERE t1.booking_number=t2.booking_number AND (t1.roomid=0 OR t1.roomid='' OR t1.roomid is null) AND t1.status NOT IN('Cancelled','No Show') AND t1.datelogged='{$bill_post_date}'";
					
					//$query_rx = mysqli_query($mysqli,$dataset_rx);

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="cs-width-1700 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Booking No.</th>
											<th align="center">Room Type</th>
											<th align="center">Room No.</th>
											<th align="center">Check-In</th>
											<th align="center">Check-Out</th>
											<th align="center">Title</th>
											<th align="center">Firstname</th>
											<th align="center">Lastname</th>
											<th align="center">Phone No.</th>
											<th align="center">Email Address</th>
											<th align="center">Amount</th>
										</tr>
										
										<?php
											
											$startnumbr = 0; $roomtype = ""; $room = ""; $fsalutation = "";
											$fname = ""; $lname = ""; $invoice_no = ""; $salutation = ""; $amount = 0;
											$mobile = ""; $email = "";

											while($row = @mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$roomtype = idget_data($tbL52,$row['room_type_id'],'name');
												$room = idget_data($tbL56,$row['roomid'],'roomprefix');
												$room .= idget_data($tbL56,$row['roomid'],'roomnumber');

												$fsalutation = idget_data($tbL102,$row['customerid'],'salutation');
												$salutation = idget_data($tbL42,$fsalutation,'name');
												$fname = idget_data($tbL102,$row['customerid'],'fname');
												$lname = idget_data($tbL102,$row['customerid'],'lname');
												$mobile = idget_data($tbL102,$row['customerid'],'mobile');
												$email = idget_data($tbL102,$row['customerid'],'emailaddress');

												$invoice_no = idget_data($tbL102,$row['customerid'],'invoice_number');

												if(is_numeric($row['roomid'])) {
													$rbl = "booking_number='{$row['booking_number']}' AND roomid='{$row['roomid']}'";
													$ths_bill = str_replace('queryname',$rbl,$gbil);

													$query_rbl = mysqli_query($mysqli,$ths_bill);
													$ths_amt = @mysqli_fetch_array($query_rbl,MYSQLI_ASSOC);
													$amount = ($ths_amt['totalroomAmt'] + $ths_amt['totaltaxAmt'] + $ths_amt['totalconsumptionAmt'] + $ths_amt['totalserviceAmt']) - $ths_amt['totaldiscountAmt'];
												} else {
													$amount = 0;
												}

												$roomlist = getroomtypeList($row['room_type_id']);
												
												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
														</td>
														<td align="center">
															<input class="anchor" onclick="jsxView('<?php echo $row['booking_number']; ?>')" type="text" name="bookingno[]" placeholder="Enter here" value="<?php echo $row['booking_number']; ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="roomtype[]" placeholder="Enter here" value="<?php echo $roomtype; ?>" readonly required>
														</td>
														<td align="center">
															<select name="room[]" class="no-back-black" required>
																<option value="<?php echo $row['roomid']; ?>"><?php echo $room; ?></option>
																<?php echo $roomlist; ?>
															</select>
														</td>
														<td align="center">
															<input type="text" name="checkin[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkin_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="checkout[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkout_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<select name="salutation[]" class="no-back-black">
																<option value="<?php echo $fsalutation; ?>"><?php echo $salutation; ?></option>
																<?php echo $salutations; ?>
															</select>
														</td>
														<td align="center">
															<input type="text" name="fname[]" placeholder="Enter here" value="<?php if(!empty($fname)) { echo $fname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="lname[]" placeholder="Enter here" value="<?php if(!empty($lname)) { echo $lname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="phoneno[]" placeholder="Enter here" value="<?php if(!empty($mobile)) { echo $mobile; } ?>">
														</td>
														<td align="center">
															<input type="email" name="email[]" placeholder="Enter here" value="<?php if(!empty($email)) { echo $email; } ?>">
														</td>
														<td align="center">
															<input type="text" name="amount[]" placeholder="Enter here" value="<?php echo number_format($amount); ?>" readonly>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="3">
								<input type="submit" name="saveroomnumbers" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of reservations without rooms allocation : <?php echo $bill_post_date; ?>');
								//getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
							},false);
						</script>
					<?php

				} else {
					?>
						<form id="pg3" action="" method="post">
							<input type="hidden" name="pg" value="3">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','No issue with reservation guest rooms. Checking rooms to check-out, please wait..');
								setTimeout(() => { document.getElementById('pg3').submit(); },5000);
							},false);
						</script>
					<?php
				}

			} elseif(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 3) {

				//$dataset = "SELECT * FROM guest_occupancy_detail_tbl t1, guest_tbl t2 WHERE t1.booking_number=t2.booking_number AND t1.checkout_date='{$bill_post_date}' AND t1.status NOT IN('CheckedOut','Cancelled','No Show')";

				$dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE checkout_date='{$bill_post_date}' AND status NOT IN('CheckedOut','Cancelled','No Show','Downgraded','Upgraded','Swapped')";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);
				
				if($rows == true) {

					//$dataset_rx = "SELECT t1.booking_number,t1.checkin_date,t1.checkout_date,t1.room_type_id,t1.roomid,t1.id,t2.invoice_number,t2.salutation,t2.fname,t2.lname,t2.mobile,t2.emailaddress FROM guest_occupancy_detail_tbl t1, guest_tbl t2 WHERE t1.booking_number=t2.booking_number AND t1.checkout_date='{$bill_post_date}' AND t1.status NOT IN('CheckedOut','Cancelled','No Show')";
					
					//$query_rx = mysqli_query($mysqli,$dataset_rx);

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="nc-width-100 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Booking No.</th>
											<th align="center">Room Type</th>
											<th align="center">Room No.</th>
											<th align="center">Check-In</th>
											<th align="center">Check-Out</th>
											<th align="center">Title</th>
											<th align="center">Firstname</th>
											<th align="center">Lastname</th>
											<th align="center">Bal. Amount</th>
											<th align="center">-To do-</th>
										</tr>
										
										<?php
											
											$startnumbr = 0; $roomtype = ""; $room = ""; $fsalutation = "";
											$fname = ""; $lname = ""; $invoice_no = ""; $salutation = ""; $amount = 0;

											while($row = @mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$roomtype = idget_data($tbL52,$row['room_type_id'],'name');
												$room = idget_data($tbL56,$row['roomid'],'roomprefix');
												$room .= idget_data($tbL56,$row['roomid'],'roomnumber');

												$fsalutation = idget_data($tbL102,$row['customerid'],'salutation');
												$fname = idget_data($tbL102,$row['customerid'],'fname');
												$lname = idget_data($tbL102,$row['customerid'],'lname');
												$invoice_no = idget_data($tbL102,$row['customerid'],'invoice_number');

												$salutation = idget_data($tbL42,$fsalutation,'name');
												
												if(!empty($row['roomid']) && is_numeric($row['roomid'])) {
													$rbl = "booking_number='{$row['booking_number']}' AND roomid='{$row['roomid']}'";
													$ths_bill = str_replace('queryname',$rbl,$gbil);

													$query_rbl = mysqli_query($mysqli,$ths_bill);
													$ths_amt = @mysqli_fetch_array($query_rbl,MYSQLI_ASSOC);
													$amount = ($ths_amt['totalroomAmt'] + $ths_amt['totaltaxAmt'] + $ths_amt['totalconsumptionAmt'] + $ths_amt['totalserviceAmt']) - $ths_amt['totaldiscountAmt'];
												} else {
													$amount = 0;
												}

												$pbl = "sales_point='booking' AND booking_number='{$row['booking_number']}' AND invoice_number='{$invoice_no}' AND transaction_type='credit'";
												$ths_pay = str_replace('queryname',$pbl,$gpay);

												$query_pbl = mysqli_query($mysqli,$ths_pay);
												$ths_payamt = @mysqli_fetch_array($query_pbl,MYSQLI_ASSOC);
												$paid_amount = $ths_payamt['totalpayAmt'];
												$balance_amount = $amount - $paid_amount;

												$roomlist = getroomtypeList($row['room_type_id']);
												
												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
														</td>
														<td align="center">
															<input type="hidden" name="invoiceno[]" value="<?php echo $invoice_no; ?>">
															<input class="anchor" onclick="jsxView('<?php echo $row['booking_number']; ?>')" type="text" name="bookingno[]" placeholder="Enter here" value="<?php echo $row['booking_number']; ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="roomtype[]" placeholder="Enter here" value="<?php echo $roomtype; ?>" readonly required>
														</td>
														<td class="cs-width-100" align="center">
															<input type="hidden" name="room[]" value="<?php echo $row['roomid']; ?>">
															<select name="noroom" class="no-back-black" required>
																<option value="<?php echo $row['roomid']; ?>"><?php echo $room; ?></option>
															</select>
														</td>
														<td align="center">
															<input type="text" name="checkin[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkin_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="checkout[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkout_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<select name="salutation[]" class="no-back-black">
																<option value="<?php echo $fsalutation; ?>"><?php echo $salutation; ?></option>
																<?php echo $salutations; ?>
															</select>
														</td>
														<td align="center">
															<input type="text" name="fname[]" placeholder="Enter here" value="<?php if(!empty($fname)) { echo $fname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="lname[]" placeholder="Enter here" value="<?php if(!empty($lname)) { echo $lname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="amount[]" placeholder="Enter here" value="<?php echo number_format($balance_amount); ?>" readonly>
														</td>
														<td class="cs-width-150 yellow-theme" align="center">
															<select name="roomstate[]" class="no-back-black default-text-font-bold">
																<option value="extension">Extend</option>
																<option value="checkout">Check-Out</option>
															</select>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="4">
								<input type="submit" name="applyroomstate" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of rooms due to check-out on this day : <?php echo $bill_post_date; ?>. Either extend or check-out the room');
								//getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
							},false);
						</script>
					<?php

				} else {
					
					?>
						<form id="pg4" action="" method="post">
							<input type="hidden" name="pg" value="4">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','No issue with check-out rooms. Checking for due check-in rooms, please wait..');
								setTimeout(() => { document.getElementById('pg4').submit(); },5000);
							},false);
						</script>
					<?php
				}

			} elseif(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 4) {

				$dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE status='Reserved' AND checkin_date='{$bill_post_date}'";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);
				
				if($rows == true) {

					//$dataset_rx = "SELECT t1.booking_number,t1.checkin_date,t1.checkout_date,t1.room_type_id,t1.roomid,t1.id,t2.invoice_number,t2.salutation,t2.fname,t2.lname,t2.mobile,t2.emailaddress,t3.room_amount FROM guest_occupancy_detail_tbl t1, guest_tbl t2, daily_invoice_charges_tbl t3 WHERE t1.booking_number=t2.booking_number AND t1.booking_number=t3.booking_number AND t1.status='Reserved' AND t1.checkin_date='{$bill_post_date}' AND t1.roomid=t3.roomid AND t3.day=1";
					
					//$query_rx = mysqli_query($mysqli,$dataset_rx);

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="cs-width-1700 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Booking No.</th>
											<th align="center">Room Type</th>
											<th align="center">Room No.</th>
											<th align="center">Check-In</th>
											<th align="center">Check-Out</th>
											<th align="center">Title</th>
											<th align="center">Firstname</th>
											<th align="center">Lastname</th>
											<th align="center">Phone No.</th>
											<th align="center">Email Address</th>
											<th align="center">Room Tariff</th>
											<th align="center">-To do-</th>
										</tr>
										
										<?php
											
											$startnumbr = 0; $roomtype = ""; $room = ""; $fsalutation = "";
											$fname = ""; $lname = ""; $invoice_no = ""; $salutation = "";
											$mobile = ""; $email = ""; $amount = 0;

											while($row = @mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$roomtype = idget_data($tbL52,$row['room_type_id'],'name');
												$room = idget_data($tbL56,$row['roomid'],'roomprefix');
												$room .= idget_data($tbL56,$row['roomid'],'roomnumber');

												$fsalutation = idget_data($tbL102,$row['customerid'],'salutation');
												$fname = idget_data($tbL102,$row['customerid'],'fname');
												$lname = idget_data($tbL102,$row['customerid'],'lname');
												$mobile = idget_data($tbL102,$row['customerid'],'mobile');
												$email = idget_data($tbL102,$row['customerid'],'emailaddress');

												$invoice_no = idget_data($tbL102,$row['customerid'],'invoice_number');

												$salutation = idget_data($tbL42,$row['salutation'],'name');
												
												if(is_numeric($row['roomid'])) {
													$rbl = "booking_number='{$row['booking_number']}' AND roomid='{$row['roomid']}'";
													$ths_bill = str_replace('queryname',$rbl,$gbil);

													$query_rbl = mysqli_query($mysqli,$ths_bill);
													$ths_amt = @mysqli_fetch_array($query_rbl,MYSQLI_ASSOC);
													//$amount = ($ths_amt['totalroomAmt'] + $ths_amt['totaltaxAmt'] + $ths_amt['totalconsumptionAmt'] + $ths_amt['totalserviceAmt']) - $ths_amt['totaldiscountAmt'];
													$amount = $ths_amt['totalroomAmt'];
												} else {
													$amount = 0;
												}

						
												$roomlist = getroomtypeList($row['room_type_id']);
												
												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
														</td>
														<td align="center">
															<input type="hidden" name="invoiceno[]" value="<?php echo $invoice_no; ?>">
															<input class="anchor" onclick="jsxView('<?php echo $row['booking_number']; ?>')" type="text" name="bookingno[]" placeholder="Enter here" value="<?php echo $row['booking_number']; ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="roomtype[]" placeholder="Enter here" value="<?php echo $roomtype; ?>" readonly required>
														</td>
														<td class="cs-width-100" align="center">
															<select name="room[]" class="no-back-black" required>
																<option value="<?php echo $row['roomid']; ?>"><?php echo $room; ?></option>
															</select>
														</td>
														<td align="center">
															<input type="text" name="checkin[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkin_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<input type="text" name="checkout[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($row['checkout_date'])); ?>" readonly required>
														</td>
														<td align="center">
															<select name="salutation[]" class="no-back-black">
																<option value="<?php echo $fsalutation; ?>"><?php echo $salutation; ?></option>
																<?php echo $salutations; ?>
															</select>
														</td>
														<td align="center">
															<input type="text" name="fname[]" placeholder="Enter here" value="<?php if(!empty($fname)) { echo $fname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="lname[]" placeholder="Enter here" value="<?php if(!empty($lname)) { echo $lname; } ?>" required>
														</td>
														<td align="center">
															<input type="text" name="phoneno[]" placeholder="Enter here" value="<?php if(!empty($mobile)) { echo $mobile; } ?>">
														</td>
														<td align="center">
															<input type="email" name="email[]" placeholder="Enter here" value="<?php if(!empty($email)) { echo $email; } ?>">
														</td>
														<td align="center">
															<input type="text" name="amount[]" placeholder="Enter here" value="<?php echo number_format($amount); ?>" readonly>
														</td>
														<td class="cs-width-150 yellow-theme" align="center">
															<select name="roomstate[]" class="no-back-black default-text-font-bold">
																<option value="extension">Extend</option>
																<option value="noshow">No Show</option>
															</select>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="5">
								<input type="submit" name="applyroomstate2" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of reservation rooms due for check-in on this day : <?php echo $bill_post_date; ?>. Either extend or apply no-show');
								//getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
							},false);
						</script>
					<?php

				} else {
					
					?>
						<form id="pg5" action="" method="post">
							<input type="hidden" name="pg" value="5">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','No issue with reservation due for check-ins. Checking for in-house guests rooms, please wait..');
								setTimeout(() => { document.getElementById('pg5').submit(); },5000);
							},false);
						</script>
					<?php
				}

			} elseif(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 5) {

				$dataset = "SELECT * FROM daily_invoice_charges_tbl WHERE bill_date='{$bill_post_date}' AND ischarged=0 AND deletedata=0 AND charge IN('yes') AND room_status IN('CheckedIn') AND room_type_id NOT IN(0) AND roomid NOT IN(0)";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);
				
				if($rows == true) {

					/*$dataset_rx = "SELECT t1.checkin_date,t1.checkout_date,t2.salutation,t2.fname,t2.lname,t2.mobile,t2.emailaddress,t3.id,t3.booking_number,t3.room_type_id,t3.roomid,t3.room_amount FROM guest_occupancy_detail_tbl t1, guest_tbl t2, daily_invoice_charges_tbl t3 WHERE t1.booking_number=t2.booking_number AND t1.booking_number=t3.booking_number AND t1.status='CheckedIn' AND t1.roomid=t3.roomid AND t3.charge IN('yes') AND t3.bill_date='{$bill_post_date}' AND t3.deletedata=0";

					$query_rx = mysqli_query($mysqli,$dataset_rx);*/

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="cs-width-1500 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Booking No.</th>
											<th align="center">Room Type</th>
											<th align="center">Room No.</th>
											<th align="center">Check-In</th>
											<th align="center">Check-Out</th>
											<th align="center">Title</th>
											<th align="center">Firstname</th>
											<th align="center">Lastname</th>
											<th align="center">Room Tariff</th>
											<th align="center">-To do-</th>
										</tr>
										
										<?php
											
											$startnumbr = 0; $salutation = ""; $fsalutation = "";
											$fname = ""; $lname = "";

											while($row = @mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												
												if(!empty($row['booking_number']) && !empty($row['room_type_id']) && !empty($row['roomid']) && !empty($row['room_amount'])) {

													$startnumbr += 1;

													$roomtype = idget_data($tbL52,$row['room_type_id'],'name');
													$room = idget_data($tbL56,$row['roomid'],'roomprefix');
													$room .= idget_data($tbL56,$row['roomid'],'roomnumber');

													$fsalutation = idget_data($tbL102,$row['customerid'],'salutation');
													$fname = idget_data($tbL102,$row['customerid'],'fname');
													$lname = idget_data($tbL102,$row['customerid'],'lname');
													$salutation = idget_data($tbL42,$fsalutation,'name');
													//$roomlist = getroomtypeList($row['room_type_id']);

													$gra = "SELECT * FROM {$tbL127} WHERE booking_number='{$row['booking_number']}' AND roomid='{$row['roomid']}' AND status IN('CheckedIn')";
													$query_gra = mysqli_query($mysqli,$gra);
													$show_gra = @mysqli_fetch_array($query_gra,MYSQLI_ASSOC);
													
													?>
														<tr>
															<td class="cs-width-40" align="center">
																<?php echo $startnumbr; ?>.
																<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
															</td>
															<td align="center" class="anchor" onclick="jsxView('<?php echo $row['booking_number']; ?>')">
																<input type="text" name="bookingno[]" placeholder="Enter here" value="<?php echo $row['booking_number']; ?>" readonly required>
															</td>
															<td align="center">
																<input type="text" name="roomtype[]" placeholder="Enter here" value="<?php echo $roomtype; ?>" readonly required>
															</td>
															<td class="cs-width-100" align="center">
																<select name="room[]" class="no-back-black" required>
																	<option value="<?php echo $row['roomid']; ?>"><?php echo $room; ?></option>
																</select>
															</td>
															<td align="center">
																<input type="text" name="checkin[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($show_gra['checkin_date'])); ?>" readonly required>
															</td>
															<td align="center">
																<input type="text" name="checkout[]" placeholder="Enter here" value="<?php echo date('d-m-Y',strtotime($show_gra['checkout_date'])); ?>" readonly required>
															</td>
															<td align="center">
																<?php echo $salutation; ?>
															</td>
															<td align="center">
																<?php if(!empty($fname)) { echo $fname; } ?>
															</td>
															<td align="center">
																<?php if(!empty($lname)) { echo $lname; } ?>
															</td>
															<td align="center">
																<?php echo number_format($row['room_amount']); ?>
															</td>
															<td class="cs-width-150 yellow-theme" align="center">
																<select name="roomstate[]" class="no-back-black default-text-font-bold">
																	<option value="charge-room">Charge Room</option>
																	<!--<option value="continue-stay">Continue Stay</option>-->
																</select>
															</td>
														</tr>
													<?php
												}
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="6">
								<input type="submit" name="applyroomstate3" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of in-house guests on this day : <?php echo $bill_post_date; ?>. Apply charge room');
								//getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
							},false);
						</script>
					<?php

				} else {
					
					?>
						<form id="pg6" action="" method="post">
							<input type="hidden" name="pg" value="6">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','No issue with in-house guests. Checking for unclosed counters, please wait..');
								setTimeout(() => { document.getElementById('pg6').submit(); },5000);
							},false);
						</script>
					<?php
				}

			} elseif(isset($_SESSION['auditStage']) && $_SESSION['auditStage'] == 6) {

				$dataset = "SELECT * FROM user_counter_log_tbl WHERE logstatus='Open' AND datelogged='{$bill_post_date}'";
				$dataset2 = "SELECT * FROM user_shift_log_tbl WHERE status='Open' AND datelogged='{$bill_post_date}'";

				$query = mysqli_query($mysqli,$dataset);
				$rows = @mysqli_num_rows($query);

				$query2 = mysqli_query($mysqli,$dataset2);
				$rows2 = @mysqli_num_rows($query2);
				
				if($rows == true || $rows2 == true) {

					//$dataset_rx = "SELECT * FROM user_counter_log_tbl WHERE logstatus='Open' AND datelogged='{$bill_post_date}'";
					$dataset_rx = "SELECT * FROM user_counter_log_tbl WHERE logstatus='Open'";
					$query_rx = mysqli_query($mysqli,$dataset_rx);

					//$dataset_rx2 = "SELECT * FROM user_shift_log_tbl WHERE status='Open' AND datelogged='{$bill_post_date}'";
					$dataset_rx2 = "SELECT * FROM user_shift_log_tbl WHERE status='Open'";
					$query_rx2 = mysqli_query($mysqli,$dataset_rx2);

					$obl_sql = "SUM(openingbalance)"; $crd_sql = "SUM(collection)"; $rf_sql = "SUM(refunds)";
					$wgt_obl = ""; $wgt_crd = ""; $wgt_rf = ""; $balance_amount = 0;

					?>
						<form action="" method="post" class="nomargin">
							<div class="bottom-pull-10 x-scroll">
								<div class="nc-width-100 sml-rounded-button noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Counter</th>
											<th align="center">Open Date</th>
											<th align="center">App User</th>
											<th align="center">Pending Withdrawals</th>
											<th align="center">-To do-</th>
										</tr>

										<?php
											
											$startnumbr = 0;

											while($row = @mysqli_fetch_array($query_rx,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$app_user = idget_data($tbL7,$row['userid'],'staffname');
												$counter_name = idget_data($tbL19,$row['counterid'],'countername');

												$query_counter = "deletedata=0 AND counterid={$row['counterid']} AND userid={$row['userid']} AND ispast=0";

												#total opening balance
												$wgt_obl = mysqli_arithmetic_data($tbL25,$obl_sql,$query_counter);

												#total credit
												$wgt_crd = mysqli_arithmetic_data($tbL25,$crd_sql,$query_counter);

												#total debit
												$wgt_rf = mysqli_arithmetic_data($tbL25,$rf_sql,$query_counter);

												$balance_amount = ($wgt_crd + $wgt_obl) - $wgt_rf;
												
												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
															<input type="hidden" name="user[]" value="<?php echo $row['userid']; ?>">
															<input type="hidden" name="csshift[]" value="<?php echo $row['counterid']; ?>">
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo $counter_name; ?></h3>
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo date('d-m-Y',strtotime($row['datelogged'])).' '.$row['timelogged']; ?></h3>
														</td>
														<td class="cs-width-100" align="center">
															<h3 class="large nobold"><?php echo $app_user; ?></h3>
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo number_format($balance_amount,2); ?></h3>
														</td>
														<td class="cs-width-150 yellow-theme" align="center">
															<select name="cstate[]" class="no-back-black default-text-font-bold" required>
																<option value="close-counter">Close</option>
																<option value="use-counter">Stay Active</option>
															</select>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>

								<div class="nc-width-100 sml-rounded-button top-push-30 noscroll">
									<table cellpadding="3" cellspacing="0">
										<tr>
											<th align="center">&nbsp;</th>
											<th align="center">Shift</th>
											<th align="center">Open Date</th>
											<th align="center">App User</th>
											<th align="center">Pending Outlet Sales</th>
											<th align="center">-To do-</th>
										</tr>

										<?php
											
											$startnumbr = 0;
											$wgt_gc_funds = 0; $shF = ""; $queryset = "";

											while($row = @mysqli_fetch_array($query_rx2,MYSQLI_ASSOC)) {
												
												$startnumbr += 1;

												$app_user = idget_data($tbL7,$row['userid'],'staffname');
												$shift_name = idget_data($tbL20,$row['shiftid'],'shiftname');

												$pst_query = array("cashier"=>$row['userid'],"shiftid"=>$row['shiftid'],"datelogged"=>$bill_post_date,"isreversed"=>0,"deletedata"=>0);
												$shF = mysqli_data_checkr($tbL100,'(*)',$pst_query);
						
												if($shF == true) {

													$sqlset = "SUM(bill_amount)";
													$queryset = "shiftid={$row['shiftid']} AND cashier={$row['userid']} AND datelogged='{$bill_post_date}' AND isreversed=0 AND deletedata=0";
													$wgt_gc_funds = mysqli_arithmetic_data($tbL100,$sqlset,$queryset);

												} else {
													$wgt_gc_funds = 0;
												}

												?>
													<tr>
														<td class="cs-width-40" align="center">
															<?php echo $startnumbr; ?>.
															<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
															<input type="hidden" name="user[]" value="<?php echo $row['userid']; ?>">
															<input type="hidden" name="csshift[]" value="<?php echo $row['shiftid']; ?>">
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo $shift_name; ?></h3>
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo date('d-m-Y',strtotime($row['datelogged'])).' '.$row['resumptiontime']; ?></h3>
														</td>
														<td class="cs-width-100" align="center">
															<h3 class="large nobold"><?php echo $app_user; ?></h3>
														</td>
														<td align="center">
															<h3 class="large nobold"><?php echo number_format($wgt_gc_funds,2); ?></h3>
														</td>
														<td class="cs-width-150 yellow-theme" align="center">
															<select name="cstate[]" class="no-back-black default-text-font-bold" required>
																<option value="close-shift">Close</option>
																<option value="use-shift">Stay Active</option>
															</select>
														</td>
													</tr>
												<?php
											}
										?>

									</table>
								</div>
							</div>
							<div class="top-push-30" align="center">
								<input type="hidden" name="pg" value="0">
								<input type="submit" name="applycountershiftstate" value="Save & Continue" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
							</div>
						</form>

						<script>
							window.addEventListener('load',() => {
								chgclass('mode-msg','xlarge nobold alignct default-text-font-bold');
								writeObjheader('mode-msg','List of counters not closed on this day : <?php echo $bill_post_date; ?>. And list of user shifts used');
								//getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
							},false);
						</script>
					<?php

				} else {
					
					?>
						<form id="pg7" action="" method="post">
							<input type="hidden" name="pg" value="0">
							<input type="hidden" name="reportstage" value="yes">
						</form>

						<script>
							window.addEventListener('load',() => {
								writeObjheader('mode-msg','Generating report, please wait..');
								//setTimeout(() => { window.location.href = "nightAuditreport"+exts; },5000);
								setTimeout(() => { document.getElementById('pg7').submit(); },5000);
							},false);
						</script>
					<?php
				}
			}

		?>

	</div>
</div>

<script>

	function jsxView(key) {
		popmodalframe('frontdesk','booking.php',key,0,1200,3000);
	}

</script>
