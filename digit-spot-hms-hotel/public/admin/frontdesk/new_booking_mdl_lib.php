<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	$booking_number = $_GET['booking'];

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	//get extra flow charges
	$late_checkout_status = idget_fdata($tbL130,'booking_number',$booking_number,'disable_late_checkout');
	$weekend_fares_status = idget_fdata($tbL130,'booking_number',$booking_number,'disable_weekend_fares');
	
	//post forms
	#post 1:
	if(isset($_POST['guestbutton'])) {
		
		$gfieldset1 = escape_data($_POST['gfieldset1']);
		$gfieldset2 = escape_data($_POST['gfieldset2']);
		$gfieldset3 = escape_data($_POST['gfieldset3']);
		$gfieldset4 = escape_data($_POST['gfieldset4']);
		$gfieldset5 = escape_data($_POST['gfieldset5']);
		$gfieldset6 = escape_data($_POST['gfieldset6']);
		$gfieldset7 = escape_data($_POST['gfieldset7']);
		$gfieldset8 = escape_data($_POST['gfieldset8']);
		$gfieldset9 = escape_data($_POST['gfieldset9']);
		$gfieldset10 = escape_data($_POST['gfieldset10']);
		$gfieldset11 = escape_data($_POST['gfieldset11']);
		$gfieldset12 = escape_data($_POST['gfieldset12']);
		$gfieldset13 = escape_data($_POST['gfieldset13']);
		$gfieldset14 = escape_data($_POST['gfieldset14']);

		$period_of_stay = $gfieldset13.' '.$gfieldset14;

		$gfieldset15 = $_POST['gfieldset15'];

		$pst_guest_dataproperty = array("salutation"=>$gfieldset1,"name"=>ucwords(strtolower($gfieldset2)),"mobile"=>$gfieldset3,"emailaddress"=>$gfieldset4,"remarks"=>$gfieldset9,"address"=>$gfieldset5,"city"=>$gfieldset6,"state"=>$gfieldset7,"country"=>$gfieldset8,"means_of_identification"=>$gfieldset10,"identification_number"=>$gfieldset11,"occupation"=>$gfieldset12,"period_of_stay"=>$period_of_stay);
		$pst_guest_query = array("id"=>$gfieldset15);
		$is_updated = mysqli_data_update($tbL102,$pst_guest_dataproperty,$pst_guest_query);

		if(isset($is_updated) && $is_updated == 2) {

			$pst_guest_code = idget_data($tbL102,$gfieldset15,'guest_code');

			//create a log file
			$log_message = "Recently changed guest information (guest code: ".$pst_guest_code.")";
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
			$post_result .= '</div>';
		}
	}

	#post 2:
	if(isset($_POST['allowbillbutton'])) {
		
		$gafieldset1 = escape_data($_POST['gafieldset1']);
		$gafieldset2 = $_POST['gafieldset2'];

		$pst_allowbill_dataproperty = array("allow_bill_to_room"=>$gafieldset1);
		$pst_allowbill_query = array("booking_number"=>$gafieldset2);
		mysqli_data_update($tbL130,$pst_allowbill_dataproperty,$pst_allowbill_query);
		mysqli_data_update($tbL96,$pst_allowbill_dataproperty,$pst_allowbill_query);

		$get_guest_id = mysqli_data_fetch($tbL102,'id,primary_guest',$pst_allowbill_query,'array');
		if(is_array($get_guest_id)) {
			$this_primary_guest_id=0;
			foreach ($get_guest_id as $gikey => $givalue) {
				if($givalue['primary_guest'] == 1) { $this_primary_guest_id = $givalue['id']; }
				$pst_allowbill_query = array("customerid"=>$givalue['id']);
				mysqli_data_update($tbL98,$pst_allowbill_dataproperty,$pst_allowbill_query);
			}
		}

		//create a log file
		$log_message = "Recently changed allow-bill-to-room status";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		#guest activities
		if(isset($gafieldset1) && $gafieldset1 == 'Yes') { $_activity_msg = "Guest granted permission to allow bill charged to room on the ".$server_get_date." ".$server_get_time; } elseif(isset($gafieldset1) && $gafieldset1 == 'No') { $_activity_msg = "Guest disallow bill to charge to room. Prefering instant payment. This was issued around ".$server_get_date." ".$server_get_time; }
		
			$guest_activities_dataproperty = array("booking_number"=>$gafieldset2,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}

	#post 3:
	if(isset($_POST['transitbutton'])) {
		
		$gtfieldset1 = escape_data($_POST['gtfieldset1']);
		$gtfieldset2 = escape_data($_POST['gtfieldset2']);
		$gtfieldset3 = escape_data($_POST['gtfieldset3']);
		$gtfieldset4 = escape_data($_POST['gtfieldset4']);

		$gtfieldset5 = $_POST['gtfieldset5'];

		$pst_transit_query = array("booking_number"=>$gtfieldset5);
		$pst_transit_dataproperty = array("source_of_biz"=>$gtfieldset1,"arrival_mode"=>$gtfieldset2,"departure_mode"=>$gtfieldset3,"remarks"=>$gtfieldset4);
		$is_update = mysqli_data_update($tbL128,$pst_transit_dataproperty,$pst_transit_query);

		if(isset($is_update) && $is_update == 2) {

			//create a log file
			$log_message = "Recently changed arrival and departure details";
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
			$post_result .= '</div>';
		}
	}

	#post 4:
	if(isset($_POST['savechangesbutton']) && isset($_POST['checkers'])) {
		
		$checkers = $_POST['checkers']; $roomtype = $_POST['roomtype']; $roomnumber = $_POST['roomnumber'];
		$adults = $_POST['adults']; $childs = $_POST['childs']; $occupancy_type = $_POST['occupancy_type'];
		$checkin = $_POST['checkin']; $checkout = $_POST['checkout']; $remarks = $_POST['remarks'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest']; $pst_allowbill = $_POST['allowbilltoroom']; $pst_billpayby = $_POST['billpayby'];

		$noofdays_stay = "";
		$the_room_status = 6;
		$the_hk_room_status = 4;

		$defaultroomAmount = "";
		$roomtotalAmount = "";

		$_activity_msg = "";
		
		$chkr = 0;
		
		foreach($checkers as $thischk) {
			
			$total_occupancy_charges = 0;
			
			for($i=0; $i < count($_POST['roomnumber']); $i++) {
				if($thischk == $checkers[$i]) {
					
					$this_occupancy = idget_data($tbL51,$occupancy_type[$i],'name');
					
					$data_query = array("id"=>$checkers[$i]);
					$data_exist = mysqli_data_fetch($tbL127,'roomid',$data_query,'noarray');
					
					if(isset($data_exist[0]) && $data_exist[0] >= 1) {
						$data_property = array("adult"=>$adults[$i],"child"=>$childs[$i],"occupancy_type"=>$occupancy_type[$i],"remarks"=>escape_data($remarks[$i])); mysqli_data_update($tbL127,$data_property,$data_query);

						$_activity_msg .= "Room occupancy status was changed as follows; adult: ".$adults[$i].", child: ".$childs[$i].", occupancy type: ".$this_occupancy."/";
					} else {
						$data_property = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"adult"=>$adults[$i],"child"=>$childs[$i],"occupancy_type"=>$occupancy_type[$i],"booking_type"=>$pst_booking_type,"checkin"=>$checkin[$i],"checkout"=>$checkout[$i],"status"=>"Reserved","remarks"=>escape_data($remarks[$i]),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL127,$data_property,'');

						$pst_room_type = idget_data($tbL52,$roomtype[$i],'name');
						$pst_room_prefix = idget_data($tbL56,$roomnumber[$i],'roomprefix');
						$pst_room_number = idget_data($tbL56,$roomnumber[$i],'roomnumber');

						$_activity_msg .= "Added new room(s) to current booking. Room details stated as follow; room: ".$pst_room_type." ".$pst_room_prefix.$pst_room_number.", adult: ".$adults[$i].", child: ".$childs[$i].", occupancy type: ".$this_occupancy."/";

						$noofdays_stay = dayDiffs($checkout[$i],$checkin[$i]);

						#active guest
						$active_guest_dataproperty = array("roomid"=>$roomnumber[$i],"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"allow_bill_to_room"=>$pst_allowbill,"bill_pay_by"=>$pst_billpayby,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$room_d4_constrain = array("roomid"=>$roomnumber[$i]);
						mysqli_data_insert($tbL98,$active_guest_dataproperty,$room_d4_constrain);

						#room and occupaying details
						$room_d1_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"stateid"=>$the_room_status,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"startdate"=>$checkin[$i],"endate"=>$checkout[$i],"noofdays"=>$noofdays_stay,"checkin"=>0,"booking_type"=>$pst_booking_type,"allow_bill_to_room"=>$pst_allowbill,"bill_pay_by"=>$pst_billpayby,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$room_d1_constrain = array("booking_number"=>$booking_number,"roomid"=>$roomnumber[$i]);
						mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

						$room_d2_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"stateid"=>$the_room_status,"userid"=>$userSignedIn,"startdate"=>$checkin[$i],"endate"=>$checkout[$i],"noofdays"=>$noofdays_stay,"checkin"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$room_d2_constrain = array("booking_number"=>$booking_number,"roomid"=>$roomnumber[$i]);
						mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

						#update room housekeeping status
						$get_ifstatus = idget_fdata($tbL94,'roomid',$roomnumber[$i],'room_status_id');
						if(isset($get_ifstatus) && $get_ifstatus >= 1) {
							$room_hk_dataproperty = array("housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
							$room_hk_query = array("roomid"=>$roomnumber[$i]);
							mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
						} else {
							$room_hk_dataproperty = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
							mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
						}

						$room_hk_dataproperty = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to recent booking","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL95,$room_hk_dataproperty,'');
					}


					if($occupancy_type[$i] >= 1) {
						
						createDatabasetable($var_tbl_135); //create a table for this post
						
						$select_query_1 = array("room_type_id"=>$roomtype[$i],"occupancy_type_id"=>$occupancy_type[$i]);
						$select_data_1 = mysqli_data_fetch($tbL54,'price',$select_query_1,'noarray');
						
						if(isset($select_data_1[0]) && $select_data_1[0] > 0) { $occ_price = $select_data_1[0]; }
						else { $occ_price = 0; }
						
						$select_query_2 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);
						$select_data_2 = mysqli_data_fetch($tbL140,'id',$select_query_2,'noarray');

						if(isset($select_data_2[0]) && $select_data_2[0] >= 1) {
							$insert_data_1 = array("amount"=>$occ_price,"occupancyid"=>$occupancy_type[$i],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_update($tbL140,$insert_data_1,$select_query_2);
						} else {
							$insert_data_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"amount"=>$occ_price,"occupancyid"=>$occupancy_type[$i],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
							mysqli_data_insert($tbL140,$insert_data_1,$select_query_2);
						}

						//$total_occupancy_charges = $total_occupancy_charges + $occ_price;
					}
				}
			}
		}

		#update occupancy charges to booking invoice table as other charges
		/*$insert_query_3 = array("booking_number"=>$pst_booking_number);
		$insert_data_3 = array("other_charges"=>$total_occupancy_charges);
		mysqli_data_update($tbL140,$insert_data_3,$insert_query_3);*/

		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}

	#post 5:
	if(isset($_POST['cancelroombutton'])) {

		$pst_action = 'cancelroombutton';

		$checkers = $_POST['checkers']; $roomnumber = $_POST['roomnumber']; $rwid = $_POST['rwid'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest']; $roomtype = "";

		$get_the_biller = array("booking_number"=>$pst_booking_number,"primary_guest"=>1);
		$get_biller = mysqli_data_fetch($tbL102,'billto',$get_the_biller,'noarray');
		$get_invoice_number = idget_fdata($tbL130,'booking_number',$pst_booking_number,'invoice_number');

		$chkr = 0; $to_start_date=""; $to_end_date=""; $timediffr=""; $tar=""; $ar = array();
		
		foreach($checkers as $thischk) {
			for($i=0; $i < count($_POST['roomnumber']); $i++) {
				if($rwid[$i] == $thischk) {
					$check_status_room = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);
					$get_room_stat = mysqli_data_fetch($tbL127,'status,checkin,checkout,timelogged,cancel_policy,id',$check_status_room,'noarray');

					if((isset($get_room_stat[0]) && $get_room_stat[0] != 'Checked In' && $get_room_stat[0] != 'Checked Out' && $get_room_stat[0] != 'Room Swapped' && $get_room_stat[0] != 'Room Upgrade' && $get_room_stat[0] != 'Room Downgrade' && $get_room_stat[0] != 'Cancelled') && ($get_room_stat[4] == 0)) {
						$to_start_date = $get_room_stat[1].' '.$get_room_stat[3];
						$to_end_date = $server_get_date.' '.$server_get_time;

						$timediffr = daytimeDiffs($to_start_date,$to_end_date);

						if(isset($timediffr[2]) && ($timediffr[2] >= 1 && $timediffr[2] <= 24)) {
							$get_dis_policy = array("cancellationtype"=>"In Hours");
							$gdp = mysqli_data_fetch($tbL31,'id',$get_dis_policy,'noarray');
							$tar .= $get_room_stat[5].','; array_push($ar,$get_room_stat[5]);
							$r = array("status"=>"Cancelled","cancel_policy"=>$gdp[0]);
							mysqli_data_update($tbL127,$r,$check_status_room);
						} elseif(isset($timediffr[1]) && ($timediffr[1] >= 1 && $timediffr[1] <= 14)) {
							$get_dis_policy = array("cancellationtype"=>"In Days");
							$gdp = mysqli_data_fetch($tbL31,'id',$get_dis_policy,'noarray');
							$tar .= $get_room_stat[5].','; array_push($ar,$get_room_stat[5]);
							$r = array("status"=>"Cancelled","cancel_policy"=>$gdp[0]);
							mysqli_data_update($tbL127,$r,$check_status_room);
						} else {
							$tar .= $get_room_stat[5].','; array_push($ar,$get_room_stat[5]);
							$r = array("status"=>"Cancelled","cancel_policy"=>999,"checkout_date"=>$server_get_date,"checkout_time"=>$server_get_time,"cancel_date"=>$server_get_date,"cancel_time"=>$server_get_time);
							mysqli_data_update($tbL127,$r,$check_status_room);
						}

						$rhk_query = array("roomid"=>$roomnumber[$i]);
						$rhk_sql = array("housekeeping_stateid"=>$dhl,"room_status_id"=>$drsl,"userid"=>0,"remarks"=>"room status changed due to room cancellation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_update($tbL94,$rhk_sql,$rhk_query);

						$rhk_sql_h = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$dhl,"room_status_id"=>$drsl,"userid"=>0,"remarks"=>"room status changed due to room cancellation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL95,$rhk_sql_h,'');

						$rbs_sql = array("stateid"=>2,"checkout"=>1);
						$rbs_query = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);
						mysqli_data_update($tbL97,$rbs_sql,$rbs_query);
						mysqli_data_update($tbL96,$rbs_sql,$rbs_query);

						$roomtype = idget_data($tbL56,$roomnumber[$i],'room_type_id');
						include "room_price_charge.php";
					}
				}
			}
		}

		if(is_array($ar)) {
			?>
				<script>
					window.addEventListener('load',function() {
						openWin('room-cancelling-reason');
					},false);
				</script>
			<?php
		}
	}

	
	#post 6:
	if(isset($_POST['crbutton'])) {

		$crfieldset1 = escape_data($_POST['crfieldset1']);
		$crfieldset2 = explode(',',$_POST['crfieldset2']);
		$crfieldset3 = escape_data($_POST['crfieldset3']);
		$crfieldset4 = escape_data($_POST['crfieldset4']);
		
		$pst_cr_dataproperty = array("cancel_reason"=>$crfieldset1);
		$isupt = 0; $theroom_id=""; $theroom=""; $this_primary_guest_id=$crfieldset3;

		foreach($crfieldset2 as $crRoom) {
			$theroom_id = idget_data($tbL127,$crRoom,'roomid');
			$theroom .= idget_data($tbL56,$theroom_id,'roomprefix').idget_data($tbL56,$theroom_id,'roomnumber').'/';
			$pst_cr_query = array("id"=>$crRoom);
			$is_update = mysqli_data_update($tbL127,$pst_cr_dataproperty,$pst_cr_query);
			if(isset($is_update) && $is_update == 2) { $isupt += 1; }
		}
		

		if(isset($isupt) && $isupt == 2) {

			//create a log file
			$log_message = "Recently cancelled room based on speculated reasons";
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$_activity_msg = "Guest make request to cancel booked room(s) stated as follow: ".$theroom;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
			$post_result .= '</div>';
		}
	}


	#post 7:
	if(isset($_POST['checkinbutton'])) {
		
		$pst_action = 'checkinbutton';

		$checkers = $_POST['checkers']; $roomnumber = $_POST['roomnumber']; $rwid = $_POST['rwid'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest']; $pst_allowbill = $_POST['allowbilltoroom']; $pst_billpayby = $_POST['billpayby'];

		$checkin_rooms = array("checkin"=>1);
		$oc_checkin_rooms = array("status"=>"Checked In","checkin_date"=>$server_get_date,"checkin_time"=>$server_get_time);
		$theroom = ""; $active_g_n = "";

		#get guest ids
		$in_guest_query = array("booking_number"=>$pst_booking_number,"deletedata"=>0);
		$get_guest_id_ = mysqli_data_fetch($tbL102,'id',$in_guest_query,'array');
		if(is_array($get_guest_id_)) { $in_guest_arry=array(); foreach ($get_guest_id_ as $inkey => $invalue) { array_push($in_guest_arry,$invalue['id']); } }
		
		$chkr = 0; 

		foreach($checkers as $thischk) {
			for($i=0; $i < count($_POST['rwid']); $i++) {
				if($rwid[$i] == $thischk) {
					
					if(isset($in_guest_arry[$i]) && $in_guest_arry[$i] >= 1) { $active_g_n = $in_guest_arry[$i]; }
					else { $active_g_n = $this_primary_guest_id; }

					$theroom .= idget_data($tbL56,$roomnumber[$i],'roomprefix').idget_data($tbL56,$roomnumber[$i],'roomnumber').'/';
					$checkin_rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"checkin"=>0);
					$checkin_rooms_query_2 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);

					mysqli_data_update($tbL97,$checkin_rooms,$checkin_rooms_query_1);
					mysqli_data_update($tbL96,$checkin_rooms,$checkin_rooms_query_1);
					mysqli_data_update($tbL127,$oc_checkin_rooms,$checkin_rooms_query_2);

					$rhk_query = array("roomid"=>$roomnumber[$i]);
					$rhk_sql = array("housekeeping_stateid"=>$the_hk_checkin_room_status,"room_status_id"=>3,"userid"=>0,"remarks"=>"room status changed due to room checkin","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$rhk_sql,$rhk_query);

					$rhk_sql_h = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_checkin_room_status,"room_status_id"=>3,"userid"=>0,"remarks"=>"room status changed due to room checkin","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL95,$rhk_sql_h,'');

					#active guest
					$active_guest_dataproperty = array("roomid"=>$roomnumber[$i],"customerid"=>$active_g_n,"userid"=>$userSignedIn,"allow_bill_to_room"=>$pst_allowbill,"bill_pay_by"=>$pst_billpayby,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					$room_d4_constrain = array("roomid"=>$roomnumber[$i]);
					mysqli_data_insert($tbL98,$active_guest_dataproperty,$room_d4_constrain);
				}
			}
		}

		//create a log file
		$log_message = "Recently checkedin guest room(s)";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$_activity_msg = "Guest checked into the following room(s) : ".$theroom." as at ".$server_get_date." ".$server_get_time;
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}


	#post 8:
	if(isset($_POST['checkoutbutton'])) {
		
		$pst_action = 'checkoutbutton';

		$checkers = $_POST['checkers']; $roomnumber = $_POST['roomnumber']; $rwid = $_POST['rwid'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest']; $roomtype = "";

		$get_the_biller = array("booking_number"=>$pst_booking_number,"primary_guest"=>1);
		$get_biller = mysqli_data_fetch($tbL102,'billto',$get_the_biller,'noarray');
		$get_invoice_number = idget_fdata($tbL130,'booking_number',$pst_booking_number,'invoice_number');

		$checkout_rooms = array("checkout"=>1,"stateid"=>4); $checkout_rooms_e = array("checkout"=>1);
		$oc_checkout_rooms = array("status"=>"Checked Out","checkout_date"=>$server_get_date,"checkout_time"=>$server_get_time);
		$theroom = "";
		
		$default_checkout_time = $server_get_date.' '.$the_hr_out[0].':'.$gh_checkout_time_min_name.':00';
		$now_checkout_time = $server_get_date.' '.$server_get_time;
		$timediffr = daytimeDiffs($default_checkout_time,$now_checkout_time);

		$get_lateCheckout_status = idget_fdata($tbL130,'booking_number',$pst_booking_number,'disable_late_checkout');

		if(isset($get_lateCheckout_status) && $get_lateCheckout_status == 'Yes') {
			$late_checkout_charges = 0;
		} else {
			if(isset($timediffr[2]) && $timediffr[2] == 1) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_1hr');
			} elseif(isset($timediffr[2]) && ($timediffr[2] > 1 && $timediffr[2] <= 2)) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_2hr');
			} elseif(isset($timediffr[2]) && ($timediffr[2] > 2 && $timediffr[2] <= 3)) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_3hr');
			} elseif(isset($timediffr[2]) && ($timediffr[2] > 3 && $timediffr[2] <= 4)) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_4hr');
			} elseif(isset($timediffr[2]) && ($timediffr[2] > 4 && $timediffr[2] <= 5)) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_5hr');
			} elseif(isset($timediffr[2]) && ($timediffr[2] > 5 && $timediffr[2] <= 6)) {
				$late_checkout_charges = idget_data($tbL113,1,'late_checkout_charges_6hr');
			} elseif(isset($timediffr[2]) && $timediffr[2] > 6) {
				$late_checkout_charges = 100;
			} else {
				$late_checkout_charges = 0;
			}
		}
		

		$chkr = 0;
		
		foreach($checkers as $thischk) {
			for($i=0; $i < count($_POST['roomnumber']); $i++) {
				if($rwid[$i] == $thischk) {

					$theroom .= idget_data($tbL56,$roomnumber[$i],'roomprefix').idget_data($tbL56,$roomnumber[$i],'roomnumber').'/';
					$checkout_rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i],"checkin"=>1);
					$checkout_rooms_query_2 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);

					mysqli_data_update($tbL97,$checkout_rooms,$checkout_rooms_query_1);
					mysqli_data_update($tbL96,$checkout_rooms,$checkout_rooms_query_1);
					mysqli_data_update($tbL127,$oc_checkout_rooms,$checkout_rooms_query_2);

					$rhk_query = array("roomid"=>$roomnumber[$i]);
					$rhk_sql = array("housekeeping_stateid"=>$the_hk_checkout_room_status,"room_status_id"=>4,"userid"=>0,"remarks"=>"room status changed due to room checkout","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$rhk_sql,$rhk_query);

					$rhk_sql_h = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_checkout_room_status,"room_status_id"=>4,"userid"=>0,"remarks"=>"room status changed due to room checkout","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL95,$rhk_sql_h,'');

					if(isset($late_checkout_charges) && $late_checkout_charges > 0) {
						$roomtype = idget_data($tbL56,$roomnumber[$i],'room_type_id');
						
						if(isset($late_checkout_status) && $late_checkout_status == 'No') {
							include "room_price_charge.php";
						}
					}
				}
			}
		}

		//create a log file
		$log_message = "Recently checkedout guest room(s)";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$_activity_msg = "Guest checked out from the following room(s) : ".$theroom." as at ".$server_get_date." ".$server_get_time;
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}


	#post 9:
	if(isset($_POST['stayadjbutton'])) {

		$gsfieldset1 = escape_data($_POST['gsfieldset1']);
		$gsfieldset2 = escape_data($_POST['gsfieldset2']);

		$this_primary_guest_id = escape_data($_POST['gsfieldset3']);
		$pst_booking_number = escape_data($_POST['gsfieldset4']);

		$noofdays = dayDiffs($gsfieldset2,$gsfieldset1);
		$data_query = array("booking_number"=>$pst_booking_number);
		$data_property_1 = array("startdate"=>$gsfieldset1,"endate"=>$gsfieldset2,"noofdays"=>$noofdays);
		$data_property_2 = array("checkin"=>$gsfieldset1,"checkout"=>$gsfieldset2);
		mysqli_data_update($tbL97,$data_property_1,$data_query);
		mysqli_data_update($tbL96,$data_property_1,$data_query);
		mysqli_data_update($tbL127,$data_property_2,$data_query);

		//create a log file
		$log_message = "Recently adjust guest stay";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$_activity_msg = "Guest changed duration from ".write_dateF($gh_get_date_format,$gsfieldset1)." to ".write_dateF($gh_get_date_format,$gsfieldset2);
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}

	#post 10:
	if(isset($_POST['confirmbutton'])) {

		$pst_action = 'confirmbutton';

		$the_room_status = 6;
		$the_hk_room_status = 4;

		$checkers = $_POST['checkers']; $roomnumber = $_POST['roomnumber']; $rwid = $_POST['rwid'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest'];

		$oc_reserved_rooms = array("status"=>"Reserved");
		$rm_reserved_status_1 = array("stateid"=>$the_room_status,"tempreserved"=>"No","tempdatereserved"=>"0000-00-00");
		$rm_reserved_status_2 = array("stateid"=>$the_room_status);

		$theroom = ""; $get_ifstatus = "";
		
		$chkr = 0;

		foreach($checkers as $thischk) {
			for($i=0; $i < count($_POST['rwid']); $i++) {
				if($rwid[$i] == $thischk) {
					
					$theroom .= idget_data($tbL56,$roomnumber[$i],'roomprefix').idget_data($tbL56,$roomnumber[$i],'roomnumber').'/';
					$checkin_rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);
					
					mysqli_data_update($tbL97,$rm_reserved_status_2,$checkin_rooms_query_1);
					mysqli_data_update($tbL96,$rm_reserved_status_1,$checkin_rooms_query_1);
					mysqli_data_update($tbL127,$oc_reserved_rooms,$checkin_rooms_query_1);

					#update room housekeeping status
					$get_ifstatus = idget_fdata($tbL94,'roomid',$roomnumber[$i],'room_status_id');
					if(isset($get_ifstatus) && $get_ifstatus >= 1) {
						$room_hk_dataproperty = array("housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to room reserved","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$room_hk_query = array("roomid"=>$roomnumber[$i]);
						mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
					} else {
						$room_hk_dataproperty = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to room reserved","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
					}

					$room_hk_dataproperty = array("roomid"=>$roomnumber[$i],"housekeeping_stateid"=>$the_hk_room_status,"room_status_id"=>$the_room_status,"remarks"=>"room status changed due to room reserved","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL95,$room_hk_dataproperty,'');
				}
			}
		}

		//create a log file
		$log_message = "Recently confirmed guest room(s) that is temporary booked";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$_activity_msg = "Guest confirmed the following room(s) : ".$theroom." as at ".$server_get_date." ".$server_get_time." to checkin";
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}

	#post 11:
	if(isset($_POST['releasebutton'])) {

		$pst_action = 'releasebutton';

		$checkers = $_POST['checkers']; $roomnumber = $_POST['roomnumber']; $rwid = $_POST['rwid'];
		$pst_booking_number = $_POST['bookingnumber']; $pst_booking_type = $_POST['bookingtype'];
		$this_primary_guest_id = $_POST['primaryguest'];

		$oc_reserved_rooms = array("status"=>"Cancelled","cancel_policy"=>999,"cancel_reason"=>7);
		$tmpr_sets = array("tempreserved"=>"No","tempdatereserved"=>"0000-00-00");

		$chkr = 0;

		foreach($checkers as $thischk) {
			for($i=0; $i < count($_POST['rwid']); $i++) {
				if($rwid[$i] == $thischk) {
					
					$checkin_rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber[$i]);
					mysqli_data_update($tbL96,$tmpr_sets,$checkin_rooms_query_1);
					mysqli_data_update($tbL127,$oc_reserved_rooms,$checkin_rooms_query_1);
				}
			}
		}

		//create a log file
		$log_message = "Recently released guest room(s) that is temporary booked";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';
	}


	#post 12:
	if(isset($_POST['inclusionbutton']) && isset($_POST['inclusions'])) {

		$pst_action = 'inclusionbutton';

		createDatabasetable($var_tbl_130); //create a table for this post

		$roomnumber = $_POST['infieldset2']; $pst_booking_number = $_POST['infieldset3'];
		$this_primary_guest_id = $_POST['infieldset1'];

		foreach($_POST['inclusions'] as $inclusions) {
			//$inclusion_constrain = array("booking_number"=>$pst_booking_number,"inclusion_id"=>$inclusions);
			$inclusion_constrain = "";
			$guest_inclusion_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"inclusion_id"=>$inclusions,"roomid"=>$roomnumber,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL135,$guest_inclusion_dataproperty,$inclusion_constrain);
		}

		//create a log file
		$log_message = "Recently added inclusion to guest";
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		$_activity_msg = "Guest added more inclusions";
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_primary_guest_id,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
		$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
		$post_result .= '</div>';

	}

	
	#post 13:
	if(isset($_POST['sudbutton'])) {
		$form = $_POST['rsudfieldset4'];
		$this_guest_number = $_POST['rsudfieldset1']; $roomnumber = $_POST['rsudfieldset2'];
		$pst_booking_number = $_POST['rsudfieldset3'];

		$allow_bill_to_room = idget_fdata($tbL130,'booking_number',$pst_booking_number,'allow_bill_to_room');
		$allow_payment_by = idget_fdata($tbL130,'booking_number',$pst_booking_number,'bill_pay_by');
		$biller = idget_fdata($tbL130,'booking_number',$pst_booking_number,'booking_type');

		$frm_hk_status = idget_fdata($tbL130,'roomid',$roomnumber,'housekeeping_stateid');
		$frm_room_status = idget_fdata($tbL130,'roomid',$roomnumber,'room_status_id');

		if(isset($form) && $form == 1) {
			$new_roomnumber = $_POST['swapsameroomtype'];
			$room_usage = array("checkout"=>1);
			$occupancy_rooms = array("status"=>"Room Swapped");
			$housekeeping_room_status = array("roomid"=>$roomnumber,"housekeeping_stateid"=>$the_hk_checkout_room_status,"room_status_id"=>4,"userid"=>0,"remarks"=>"room status changed due to room checkout","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

			$rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber);
			$rooms_query_2 = array("roomid"=>$roomnumber);

			mysqli_data_update($tbL96,$room_usage,$rooms_query_1);
			mysqli_data_update($tbL97,$room_usage,$rooms_query_1);
			mysqli_data_update($tbL127,$occupancy_rooms,$rooms_query_1);
			mysqli_data_update($tbL94,$housekeeping_room_status,$rooms_query_2);
			mysqli_data_insert($tbL95,$housekeeping_room_status,$rooms_query_2);

			$the_room_status = 3;
			$stay_data = mysqli_data_fetch($tbL97,'startdate,endate,noofdays',$rooms_query_1,'noarray');
			$occupancy_data = mysqli_data_fetch($tbL127,'occupancy_type',$rooms_query_1,'noarray');

			#active guest
			$active_guest_dataproperty = array("roomid"=>$new_roomnumber,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d4_constrain = array("roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL98,$active_guest_dataproperty,$room_d4_constrain);

			#room and occupaying details
			$room_d1_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"stateid"=>$frm_room_status,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"noofdays"=>$stay_data[2],"checkin"=>1,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d1_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

			$room_d2_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"stateid"=>$frm_room_status,"userid"=>$userSignedIn,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"noofdays"=>$stay_data[2],"checkin"=>1,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d2_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

			#guest occupancy detail
			$guest_occupancy_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"adult"=>1,"occupancy_type"=>$occupancy_data[0],"booking_type"=>$biller,"checkin"=>$stay_data[0],"checkout"=>$stay_data[1],"status"=>"Checked In","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); $room_d3_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL127,$guest_occupancy_dataproperty,$room_d3_constrain);

			#update room housekeeping status
			$get_ifstatus = idget_fdata($tbL94,'roomid',$new_roomnumber,'room_status_id');
			if(isset($get_ifstatus) && $get_ifstatus >= 1) {
				$room_hk_dataproperty = array("housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room swap","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_hk_query = array("roomid"=>$new_roomnumber);
				mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
			} else {
				$room_hk_dataproperty = array("roomid"=>$new_roomnumber,"housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room swap","userid"=>0,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
			}

			$room_hk_dataproperty = array("roomid"=>$new_roomnumber,"housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room swap","userid"=>0,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL95,$room_hk_dataproperty,'');

			#guest activities
			$from_room = idget_data($tbL56,$roomnumber,'roomprefix');
			$from_room .= idget_data($tbL56,$roomnumber,'roomnumber');
			$to_room = idget_data($tbL56,$new_roomnumber,'roomprefix');
			$to_room .= idget_data($tbL56,$newroomnumber,'roomnumber');
			$_activity_msg = "Guest swapped room from  ".$from_room." ".$to_room;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
			$post_result .= '</div>';

		} elseif(isset($form) && $form == 2) {

			$new_roomnumber = $_POST['updowngrade-room'];
			$new_room_type = $_POST['updowngrade-roomtype'];

			#get current room price
			$current_room_type = idget_data($tbL56,$roomnumber,'room_type_id');
			$current_room_type_price = idget_data($tbL52,$current_room_type,'defaultprice');
			$new_room_type_price = idget_data($tbL52,$new_room_type,'defaultprice');

			if(isset($new_room_type_price) && isset($current_room_type_price) && $new_room_type_price > $current_room_type_price) { $this_status = "Room Upgrade"; $a_msg = "upgraded room"; } else { $this_status = "Room Downgrade"; $a_msg = "downgraded room"; }

			$room_usage = array("checkout"=>1);
			$occupancy_rooms = array("status"=>$this_status);
			$housekeeping_room_status = array("roomid"=>$roomnumber,"housekeeping_stateid"=>$the_hk_checkout_room_status,"room_status_id"=>4,"userid"=>0,"remarks"=>"room status changed due to room checkout","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

			$rooms_query_1 = array("booking_number"=>$pst_booking_number,"roomid"=>$roomnumber);
			$rooms_query_2 = array("roomid"=>$roomnumber);

			mysqli_data_update($tbL96,$room_usage,$rooms_query_1);
			mysqli_data_update($tbL97,$room_usage,$rooms_query_1);
			mysqli_data_update($tbL127,$occupancy_rooms,$rooms_query_1);
			mysqli_data_update($tbL94,$housekeeping_room_status,$rooms_query_2);
			mysqli_data_insert($tbL95,$housekeeping_room_status,$rooms_query_2);

			$the_room_status = 3;
			$stay_data = mysqli_data_fetch($tbL97,'startdate,endate,noofdays',$rooms_query_1,'noarray');
			$occupancy_data = mysqli_data_fetch($tbL127,'occupancy_type',$rooms_query_1,'noarray');

			#active guest
			$active_guest_dataproperty = array("roomid"=>$new_roomnumber,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d4_constrain = array("roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL98,$active_guest_dataproperty,$room_d4_constrain);

			#room and occupaying details
			$room_d1_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"stateid"=>$frm_room_status,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"noofdays"=>$stay_data[2],"checkin"=>1,"booking_type"=>$biller,"allow_bill_to_room"=>$allow_bill_to_room,"bill_pay_by"=>$allow_payment_by,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d1_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL96,$room_d1_dataproperty,$room_d1_constrain);

			$room_d2_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"stateid"=>$frm_room_status,"userid"=>$userSignedIn,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"noofdays"=>$stay_data[2],"checkin"=>1,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			$room_d2_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL97,$room_d2_dataproperty,$room_d2_constrain);

			#guest occupancy detail
			$guest_occupancy_dataproperty = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber,"adult"=>1,"occupancy_type"=>$occupancy_data[0],"booking_type"=>$biller,"checkin"=>$stay_data[0],"checkout"=>$stay_data[1],"status"=>"Checked In","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); $room_d3_constrain = array("booking_number"=>$pst_booking_number,"roomid"=>$new_roomnumber);
			mysqli_data_insert($tbL127,$guest_occupancy_dataproperty,$room_d3_constrain);

			#update room housekeeping status
			$get_ifstatus = idget_fdata($tbL94,'roomid',$new_roomnumber,'room_status_id');
			if(isset($get_ifstatus) && $get_ifstatus >= 1) {
				$room_hk_dataproperty = array("housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room upgrade or downgrade","userid"=>0,"startdate"=>$start_date,"endate"=>$end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$room_hk_query = array("roomid"=>$new_roomnumber);
				mysqli_data_update($tbL94,$room_hk_dataproperty,$room_hk_query);
			} else {
				$room_hk_dataproperty = array("roomid"=>$new_roomnumber,"housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room upgrade or downgrade","userid"=>0,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL94,$room_hk_dataproperty,'');
			}

			$room_hk_dataproperty = array("roomid"=>$new_roomnumber,"housekeeping_stateid"=>$frm_hk_status,"room_status_id"=>$frm_room_status,"remarks"=>"room status changed due to room upgrade or downgrade","userid"=>0,"startdate"=>$stay_data[0],"endate"=>$stay_data[1],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL95,$room_hk_dataproperty,'');

			#guest activities
			$from_room = idget_data($tbL56,$roomnumber,'roomprefix');
			$from_room .= idget_data($tbL56,$roomnumber,'roomnumber');
			$to_room = idget_data($tbL56,$new_roomnumber,'roomprefix');
			$to_room .= idget_data($tbL56,$newroomnumber,'roomnumber');
			$_activity_msg = "Guest ".$a_msg." from  ".$from_room." ".$to_room;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have saved changes to guest information</span>';
			$post_result .= '</div>';

		}
	}


	#post 14:
	if(isset($_POST['removetaxbutton']) && isset($_POST['taxchecker'])) {
		
		$insert_data = array();

		$pst_booking_number = $_POST['etxfieldset2'];
		$this_guest_number = $_POST['etxfieldset1'];

		$insert_data['booking_number'] = $pst_booking_number;
		$insert_data['guest_id'] = $this_guest_number;

		$insert_query = array("booking_number"=>$pst_booking_number);

		$tx_to_remove = '';

		foreach($_POST['taxchecker'] as $ctx) {
			
			if($ctx == 1) {
				$insert_data['consumption_tax'] = 0; $tx_to_remove .= 'Consumption Tax,';
				$upt_data = array("consumption_tax_amount"=>0);
				mysqli_data_update($tbL130,$upt_data,$insert_query);
			}

			if($ctx == 2) {
				$insert_data['service_charge'] = 0; $tx_to_remove .= 'Service Charge,';
				$upt_data = array("service_charge"=>0);
				mysqli_data_update($tbL130,$upt_data,$insert_query);
			}

			if($ctx == 3) {
				$insert_data['vat'] = 0; $tx_to_remove .= 'Value Added Tax,';
				$upt_data = array("tax_amount"=>0);
				mysqli_data_update($tbL130,$upt_data,$insert_query);
			}
		}

		//print_r($insert_data); print_r($insert_query);

		$tx_to_remove_ = substr_replace($tx_to_remove, '', -1,1);

		$isdata = mysqli_data_update($tbL139,$insert_data,$insert_query);

		if(isset($isdata) && $isdata == 2) {
			$_activity_msg = "Guest ".$tx_to_remove_." were removed from booking charges as at ".$server_get_date." ".$server_get_time;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Selected tax charges are now excluded from booking</span>';
			$post_result .= '</div>';
		}
	}



	#post 15
	if(isset($_POST['latecheckoutbutton'])) {

		$pst_booking_number = $_POST['etxfieldset2'];
		$this_guest_number = $_POST['etxfieldset1'];

		$insert_data = array("disable_late_checkout"=>"Yes");
		$insert_query = array("booking_number"=>$pst_booking_number);
		$isdata = mysqli_data_update($tbL130,$insert_data,$insert_query);

		if(isset($isdata) && $isdata == 2) {
			$_activity_msg = "Guest late-checkout-charges was disabled as at ".$server_get_date." ".$server_get_time;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have disabled late checkout charges</span>';
			$post_result .= '</div>';
		}		
	}


	#post 16
	if(isset($_POST['weekendfarebutton'])) {

		$pst_booking_number = $_POST['etxfieldset2'];
		$this_guest_number = $_POST['etxfieldset1'];

		$insert_data = array("disable_weekend_fares"=>"Yes");
		$insert_query = array("booking_number"=>$pst_booking_number);
		$isdata = mysqli_data_update($tbL130,$insert_data,$insert_query);

		if(isset($isdata) && $isdata == 2) {
			$_activity_msg = "Guest weekend fares was disabled as at ".$server_get_date." ".$server_get_time;
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$this_guest_number,"userid"=>$userSignedIn,"activities"=>$_activity_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">You have disabled weekend fares</span>';
			$post_result .= '</div>';
		}		
	}

	#---------------------------------------------------------------------------------------------------------------------------------------------end

	//get guest taxes status
	$is_vat = idget_fdata($tbL139,'booking_number',$booking_number,'vat');
	$is_servicecharge = idget_fdata($tbL139,'booking_number',$booking_number,'service_charge');
	$is_consumption_tax = idget_fdata($tbL139,'booking_number',$booking_number,'consumption_tax');

	//get room stay status
	$room_stat_id = idget_fdata($tbL97,'booking_number',$booking_number,'stateid');
	$xcheckin_date = idget_fdata($tbL97,'booking_number',$booking_number,'startdate');
	$xcheckout_date = idget_fdata($tbL97,'booking_number',$booking_number,'endate');
	$room_stat_tag = idget_data($tbL38,$room_stat_id,'legendname');

	//get no of days allocated
	$noofdays_allocated = idget_fdata($tbL97,'booking_number',$booking_number,'noofdays');
	
	//get number of room
	$room_number_sql = "COUNT(roomid)";
	$room_number_query = "booking_number='".$booking_number."'";
	$get_numbr = mysqli_arithmetic_data($tbL96,$room_number_sql,$room_number_query);

	if(isset($get_numbr) && $get_numbr >= 2) { $number_of_room_booked_tag = "Group"; }
	else { $number_of_room_booked_tag = "Single"; }

	//get guest details
	$guest_query = array("booking_number"=>$booking_number,"primary_guest"=>1,"deletedata"=>0);
	$guest_dataproperty = "guest_code,salutation,name,mobile,emailaddress,remarks,address,id,city,state,country,means_of_identification,identification_number,occupation,period_of_stay,datelogged,billto,billtype";
	$get_guest_detail = mysqli_data_fetch($tbL102,$guest_dataproperty,$guest_query,'noarray');
	$salutation = idget_data($tbL42,$get_guest_detail[1],'name');

	#to whom bill goes to
	if(isset($get_guest_detail[16]) && $get_guest_detail[16] >= 1) {
		
		$_to_complimentary = "no";

		if(isset($get_guest_detail[17]) && $get_guest_detail[17] == 3) {
			$_bill_on_account = " (Compl. ".idget_data($tbL33,$get_guest_detail[16],'name').")";
			$_to_bill = 1;
		} elseif(isset($get_guest_detail[17]) && $get_guest_detail[17] == 4) {
			$_bill_on_account = " (Corpo/Spl. ".idget_data($tbL58,$get_guest_detail[16],'name').")";
			$_to_bill = 1;
		} else {
			$_bill_on_account = "";
			$_to_bill = 0;
		}

	} else {
		$_to_complimentary = "yes";
		$_bill_on_account = "";
		$_to_bill = 0;
	}
	#end

	$guest_query_2 = array("booking_number"=>$booking_number,"deletedata"=>0);
	$get_guest_detail_2 = mysqli_data_fetch($tbL102,'name,mobile,id',$guest_query_2,'array');
	
	if(is_array($get_guest_detail_2)) {
		$g2=""; $guest_array=array();
		foreach ($get_guest_detail_2 as $ggkey => $ggvalue) {
			$g2 = $ggvalue['name'].'/'.$ggvalue['mobile'].'/'.$ggvalue['id'];
			array_push($guest_array,$g2);
		}
	}

	$es_checkin_date = write_dateF($gh_get_date_format,$xcheckin_date);
	$es_checkout_date = write_dateF($gh_get_date_format,$xcheckout_date);

	$get_au_checkin_date = idget_fdata($tbL127,'booking_number',$booking_number,'checkin_date');
	$get_au_checkout_date = idget_fdata($tbL127,'booking_number',$booking_number,'checkout_date');
	$au_checkin_date = write_dateF($gh_get_date_format,$get_au_checkin_date);
	if(str_replace('-','',$get_au_checkout_date) > 1) { $au_checkout_date = write_dateF($gh_get_date_format,$get_au_checkout_date); }
	else { $au_checkout_date = write_dateF($gh_get_date_format,$xcheckout_date); }

	//get booking type
	$booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');

	//get allow bill to room
	$allow_bill_to_room = idget_fdata($tbL130,'booking_number',$booking_number,'allow_bill_to_room');

	//get bill paid by
	$bill_paid_by = idget_fdata($tbL130,'booking_number',$booking_number,'bill_pay_by');

	//get guest arrival & departure detail
	$gad_1 = idget_fdata($tbL128,'booking_number',$booking_number,'source_of_biz');
	$gad_2 = idget_fdata($tbL128,'booking_number',$booking_number,'arrival_mode');
	$gad_3 = idget_fdata($tbL128,'booking_number',$booking_number,'departure_mode');
	$gad_4 = idget_fdata($tbL128,'booking_number',$booking_number,'remarks');

	if(isset($gad_1) && $gad_1 >= 1) { $source_of_biz = idget_data($tbL43,$gad_1,'name'); } else { $source_of_biz = 'Nil'; }
	if(isset($gad_2) && $gad_2 >= 1) { $arrival_mode = idget_data($tbL29,$gad_2,'name'); } else { $arrival_mode = 'Nil'; }
	if(isset($gad_3) && $gad_3 >= 1) { $departure_mode = idget_data($tbL29,$gad_3,'name'); } else { $departure_mode = 'Nil'; }
	if(isset($gad_4) && !empty($gad_4)) { $gad_remarks = $gad_4; } else { $gad_remarks = 'Nil'; }

	//booking is issued by
	$booking_issued_by = idget_fdata($tbL130,'booking_number',$booking_number,'userid');
	$date_issued = idget_fdata($tbL130,'booking_number',$booking_number,'datelogged');
	$time_issued = idget_fdata($tbL130,'booking_number',$booking_number,'timelogged');
	$f_date_issued = write_dateF($gh_get_date_format,$date_issued);
	$staffname = idget_data($tbL7,$booking_issued_by,'staffname');

	//get number of adults
	$adult_number_sql = "SUM(adult)";
	$adult_number_query = "booking_number='".$booking_number."'";
	$adult = mysqli_arithmetic_data($tbL127,$adult_number_sql,$adult_number_query);

	//get number of child
	$child_number_sql = "SUM(child)";
	$child_number_query = "booking_number='".$booking_number."'";
	$child = mysqli_arithmetic_data($tbL127,$child_number_sql,$child_number_query);

	//get number of extrabeds
	$extrabed_number_sql = "SUM(isextrabed)";
	$extrabed_number_query = "booking_number='".$booking_number."'";
	$extrabed = mysqli_arithmetic_data($tbL127,$extrabed_number_sql,$extrabed_number_query);

	//get room occupancy charges
	$occupancy_room_sql = "SUM(amount)";
	$occupancy_room_query = "booking_number='".$booking_number."'";
	$occupancy_room_charges = mysqli_arithmetic_data($tbL140,$occupancy_room_sql,$occupancy_room_query);


	//get estimated bill and payment
	$guest_bill_query = array("booking_number"=>$booking_number,"deletedata"=>0);
	$guest_bill_dataproperty = "sub_total,discount_amount,service_charge,tax_amount,consumption_tax_amount,other_charges,bill_amount";
	$get_guest_bill = mysqli_data_fetch($tbL134,$guest_bill_dataproperty,$guest_bill_query,'array');

	if(is_array($get_guest_bill)) {

		$sub_total = ""; $discount_amount = ""; $service_charge = "";
		$tax_amount = ""; $consumption_tax_amount = ""; $other_charges = ""; $noofcounts = 0;

		foreach ($get_guest_bill as $gbl_key => $gbl_value) {
			
			$noofcounts += 1;

			$sub_total = $sub_total + $gbl_value['sub_total'];
			$discount_amount = $discount_amount + $gbl_value['discount_amount'];
			
			//$service_charge = $service_charge + $gbl_value['service_charge'];
			//$tax_amount = $tax_amount + $gbl_value['tax_amount'];
			//$consumption_tax_amount = $consumption_tax_amount + $gbl_value['consumption_tax_amount'];

			if(isset($is_servicecharge) && $is_servicecharge == 1) { $service_charge = $service_charge + $gbl_value['service_charge']; }
			else { $service_charge = 0; }
			
			if(isset($is_vat) && $is_vat == 1) { $tax_amount = $tax_amount + $gbl_value['tax_amount']; }
			else { $tax_amount = 0; }

			if(isset($is_consumption_tax) && $is_consumption_tax == 1) { $consumption_tax_amount = $consumption_tax_amount + $gbl_value['consumption_tax_amount']; } else { $consumption_tax_amount = 0; }
			
			$other_charges = $other_charges + $gbl_value['other_charges'];
			$bill_amount = $bill_amount + $gbl_value['bill_amount'];
		}

	} else {
		$noofcounts = 0;
		$sub_total = 0; $discount_amount = 0; $service_charge = 0;
		$tax_amount = 0; $consumption_tax_amount = 0; $other_charges = 0;
	}


	include "frontdesk/guest_inclusions.php";

	if(isset($noofcounts) && $noofcounts >= 1) {
		$other_charges = $other_charges + $actual_incl_bill + ($occupancy_room_charges * $noofcounts);
	} else {
		$other_charges = $other_charges + $actual_incl_bill;
	}

	//$sub_total = idget_fdata($tbL130,'booking_number',$booking_number,'sub_total');
	//$discount = idget_fdata($tbL130,'booking_number',$booking_number,'discount_amount');
	$room_charges = $sub_total + $discount_amount;

	//$service_charge = idget_fdata($tbL130,'booking_number',$booking_number,'service_charge');
	//$tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'tax_amount');
	//$consumption_tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'consumption_tax_amount');
	//$other_charges = idget_fdata($tbL130,'booking_number',$booking_number,'other_charges');

	$print_room_charges = write_amountF($gh_get_decimal_format,$room_charges);
	$print_discount = write_amountF($gh_get_decimal_format,$discount_amount);
	$print_service_charge = write_amountF($gh_get_decimal_format,$service_charge);
	$print_tax_amount = write_amountF($gh_get_decimal_format,$tax_amount);
	$print_consumption_tax_amount = write_amountF($gh_get_decimal_format,$consumption_tax_amount);
	$print_other_charges = write_amountF($gh_get_decimal_format,$other_charges);

	$grand_total = $sub_total + $service_charge + $tax_amount + $consumption_tax_amount + $other_charges;
	$print_grand_total = write_amountF($gh_get_decimal_format,$grand_total);

	#---------------------------------------------------------------------------------

	$es_sub_total = idget_fdata($tbL130,'booking_number',$booking_number,'sub_total');
	$es_discount_amount = idget_fdata($tbL130,'booking_number',$booking_number,'discount_amount');
	
	if(isset($is_servicecharge) && $is_servicecharge == 1) { $es_service_charge = idget_fdata($tbL130,'booking_number',$booking_number,'service_charge'); }
	else { $es_service_charge = 0; }
	
	if(isset($is_vat) && $is_vat == 1) { $es_tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'tax_amount'); }
	else { $es_tax_amount = 0; }

	if(isset($is_consumption_tax) && $is_consumption_tax == 1) { $es_consumption_tax_amount = idget_fdata($tbL130,'booking_number',$booking_number,'consumption_tax_amount'); }
	else { $es_consumption_tax_amount = 0; }

	$es_other_charges = idget_fdata($tbL130,'booking_number',$booking_number,'other_charges');
	$es_other_charges = $es_other_charges + $actual_incl_bill + ($occupancy_room_charges * $noofdays_allocated);
	$es_bill_amount = idget_fdata($tbL130,'booking_number',$booking_number,'bill_amount');

	#get extrabed cost
	$select_query_3 = array("booking_number"=>$booking_number);
	$select_data_3 = mysqli_data_fetch($tbL127,'roomid,isextrabed',$select_query_3,'array');

	if(is_array($select_data_3)) {
		
		$ex_roomtype = ""; $total_extrabed_cost = 0; $cost_of_extrabed = "";
		
		foreach ($select_data_3 as $key3 => $value3) {
			if(isset($value3['isextrabed']) && $value3['isextrabed'] >= 1) {
				$ex_roomtype = idget_data($tbL56,$value3['roomid'],'room_type_id');
				$get_extrabed_price = idget_data($tbL52,$ex_roomtype,'extrabedprice');
				$cost_of_extrabed = $get_extrabed_price * $value3['isextrabed'];
			} else {
				$cost_of_extrabed = 0;
			}

			$total_extrabed_cost = $total_extrabed_cost + $cost_of_extrabed;
		}
	}

	$total_extrabed_cost_ = $total_extrabed_cost * $noofdays_allocated;
	#end here

	$es_room_charges = $es_sub_total + $es_discount_amount + $total_extrabed_cost_;
	$es_grand_total = $es_room_charges + $es_service_charge + $es_tax_amount + $es_consumption_tax_amount + $es_other_charges;

	$print_es_room_charges = write_amountF($gh_get_decimal_format,$es_room_charges);
	$print_es_discount = write_amountF($gh_get_decimal_format,$es_discount_amount);
	$print_es_service_charge = write_amountF($gh_get_decimal_format,$es_service_charge);
	$print_es_tax_amount = write_amountF($gh_get_decimal_format,$es_tax_amount);
	$print_es_consumption_tax_amount = write_amountF($gh_get_decimal_format,$es_consumption_tax_amount);
	$print_es_other_charges = write_amountF($gh_get_decimal_format,$es_other_charges);
	$print_es_grand_total = write_amountF($gh_get_decimal_format,$es_grand_total);

	#---------------------------------------------------------------------------------

	//get total amount paid so far
	$amount_sql = "SUM(amount)";
	$amount_query = "booking_number='".$booking_number."'";
	$amount_paid = mysqli_arithmetic_data($tbL131,$amount_sql,$amount_query);
	$print_amount_paid = write_amountF($gh_get_decimal_format,$amount_paid);

	if((isset($amount_paid) && isset($grand_total)) && ($grand_total > 0 && $amount_paid >= $grand_total)) { $balance = $amount_paid - $grand_total; }
	elseif(isset($grand_total) && ($grand_total > 0 && $grand_total >= $amount_paid)) { $balance = $grand_total - $amount_paid; }
	else { $balance = 0; }
	
	$print_balance = write_amountF($gh_get_decimal_format,$balance);

	#---------------------------------------------------------------------------------

	if((isset($amount_paid) && isset($es_grand_total)) && ($es_grand_total > 0 && $amount_paid >= $es_grand_total)) { $es_balance = $amount_paid - $es_grand_total; } elseif(isset($es_grand_total) && ($es_grand_total > 0 && $es_grand_total >= $amount_paid)) { $es_balance = $es_grand_total - $amount_paid; } else { $es_balance = 0; }
	
	$print_es_balance = write_amountF($gh_get_decimal_format,$es_balance);


	#---------------------------------------------------------------------------------


	//get occupancy details
	$occupancy_query = array("booking_number"=>$booking_number,"deletedata"=>0);
	$occupancy_dataproperty = "id,roomid,adult,child,isextrabed,occupancy_type,booking_type,checkin,checkout,status,remarks,cancel_policy,datelogged,timelogged";
	$get_occupancy_data = mysqli_data_fetch($tbL127,$occupancy_dataproperty,$occupancy_query,'array');
	
	//get if estimated active
	$es_checkout_day = str_replace('-','',$xcheckout_date);
	$sx_server_day = str_replace('-','',$server_get_date);

	//print saved data variables
	echo $post_result;

?>

<div class="block-element">
	<span class="ln-display-box float-left">
		<div class="cs-width-300 pads10 black-theme white-font alignct ft-sml-size anchor">
			<a href="?logs=<?php echo $logs; ?>&booking=<?php echo $booking_number; ?>" class="red-font right-push-7"><b class="fa-refresh nobold"></b></a> Booking No: &nbsp; <b><?php echo $booking_number; ?></b>
		</div>
	</span>
	<span class="ln-display-box float-left">
		<div class="cs-width-200 pads10 red-theme white-font alignct">
			<h4 class="large nobold nomargin"><?php echo $room_stat_tag; ?> &nbsp; &mdash; &nbsp; <?php echo $number_of_room_booked_tag; ?></h4>
		</div>
	</span>
	<span class="ln-display-box float-right top-pull-10 right-pull-20">
		<a href="javascript:void(0)" class="blue-font ft-sml-size right-push-20" onclick="openWin('booking-activities-info')">History</a>
		<?php if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 6 || $room_stat_id == 7)) { ?><a href="javascript:void(0)" class="blue-font ft-sml-size right-push-20" onclick="openWin('allowModifyBooking')">Modify Booking Type</a><?php } ?>
		<a href="frontdesk.php" class="blue-white-state top-pull-5 right-pull-20 bottom-pull-5 left-pull-20 ft-xsml-size">&lsaquo; &nbsp; Back</a>
	</span>
	<span class="block-element new-line-space">
	</span>
</div>
<div class="block-element box-border-thick pads20">
	<div class="ln-display-box float-left nc-width-35 box-border-thick-right right-pull-20">
		<h4 class="large">Guest Details <a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="openWin('guest-info')">Edit</a></h4>
		<br>
		<span class="ln-display-box float-left nc-width-20 right-pull-10">
			<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
		</span>
		<span class="ln-display-box float-left nc-width-80 left-pull-10">
			<div class="ln-display-box float-left nc-width-50 right-pull-5">
				<small class="block-element bottom-push-3 dark-grey-font">Guest Code</small>
				<small class="block-element bottom-push-10"><?php echo $get_guest_detail[0]; ?></small>

				<small class="block-element bottom-push-3 dark-grey-font">Address</small>
				<small class="block-element bottom-push-10"><?php echo $get_guest_detail[6]; ?></small>
			</div>
			<div class="ln-display-box float-left nc-width-50 left-pull-5">
				<small class="block-element bottom-push-3 dark-grey-font">Name</small>
				<small class="block-element bottom-push-10"><?php echo $salutation.' '.$get_guest_detail[2].$_bill_on_account; ?></small>

				<small class="block-element bottom-push-3 dark-grey-font">Mobile</small>
				<small class="block-element bottom-push-10"><?php echo $get_guest_detail[3]; ?></small>

				<small class="block-element bottom-push-3 dark-grey-font">Email</small>
				<small class="block-element bottom-push-10"><?php echo $get_guest_detail[4]; ?></small>
			</div>
			<div class="block-element new-line-space">
			</div>
		</span>
		<span class="block-element new-line-space">
		</span>

		<br><br>
			
		<ul class="nolist">
			
			<?php
				
				if(isset($allow_bill_to_room) && $allow_bill_to_room == 'Yes') {
					?><li class="bottom-push-3"><a href="" class="blue-font ft-xsml-size">Room Services</a></li><?php
				}

				if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 6 || $room_stat_id == 7)) {
					?>
						<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('apply-discount')">Apply Discount</a></li>
						<?php if(isset($eht_isprivilege) && $eht_isprivilege >= 1) { ?><li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('excl-tax')">Exclude Hotel Tax Charges</a></li><?php } ?>
						<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('sms')">Send SMS</a></li>
						<li class="bottom-push-3"><a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('emailer')">Send Email</a></li>
					<?php
				}
				
				//if(isset($_to_bill) && $_to_bill == 1) {
					if(isset($late_checkout_status) && $late_checkout_status == 'No') {
						if(isset($dlc_isprivilege) && $dlc_isprivilege >= 1) {
							?>
								<li class="bottom-push-3">
									<a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('latecheckout')">Disable Late Check-Out Charges</a>
								</li>
							<?php
						}
					} elseif(isset($late_checkout_status) && $late_checkout_status == 'Yes') {
						?>
							<li class="bottom-push-3 red-font ft-xsml-size">* Late Check-Out Charges Disabled</li>
						<?php
					}

					if(isset($weekend_fares_status) && $weekend_fares_status == 'No') {
						if(isset($dwf_isprivilege) && $dwf_isprivilege >= 1) {
							?>
								<li class="bottom-push-3">
									<a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('weekendfares')">Disable Weekend Fares</a>
								</li>
							<?php
						}
					} elseif(isset($weekend_fares_status) && $weekend_fares_status == 'Yes') {
						?>
							<li class="bottom-push-3 red-font ft-xsml-size">* Weekend fares disabled</li>
						<?php
					}

				//}
			?>
		</ul>
	</div>
	<div class="ln-display-box float-left nc-width-35 box-border-thick-right right-pull-20 left-pull-20">
		<h4 class="large">Stay Details <?php if((isset($es_checkout_day) && isset($sx_server_day)) && ($es_checkout_day >= $sx_server_day) && ($room_stat_id == 3 || $room_stat_id == 6 || $room_stat_id == 7)) { ?><a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="openWin('guest-stay-adjustment')">Change</a><?php } else { ?><a href="javascript:void(0)" class="steel-blue-font ft-xsml-size left-push-20">Change</a><?php } ?></h4><br>
		
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="100px" align="center"></th>
				<th width="150px" align="center">Check-In</th>
				<th width="150px" align="center">Check-Out</th>
			</tr>
			<tr>
				<td width="100px" align="center"><small class="ft-xxsml-size">Estimated</small></td>
				<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $es_checkin_date; ?></b></small></td>
				<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $es_checkout_date; ?></b></small></td>
			</tr>
			<tr>
				<td width="100px" align="center"><small class="ft-xxsml-size">Actual</small></td>
				<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $au_checkin_date; ?></b></small></td>
				<td width="150px" align="center"><small class="ft-xxsml-size"><b><?php echo $au_checkout_date; ?></b></small></td>
			</tr>
		</table>

		<br>
		
		<h4 class="large nobold">&bull; Allow bill to room</h4>
		
		<table cellpadding="0" cellspacing="0" class="top-push-7">
			<tr>
				<td width="100px" align="center" class="dark-grey-theme"><b><?php echo $allow_bill_to_room; ?></b></td>
				<td width="70px" align="center"><a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="openWin('guest-allow-bill-to-room')">Edit</a></td>
			</tr>
		</table>

		<br>
		
		<h4 class="large nobold">&bull; Arrival / Departure Details <a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="openWin('guest-transit-mode')">Edit</a></h4>
		
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
		<h4 class="large">Booking Details</h4><br>

		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="150px" align="left"><small class="ft-xxsml-size">Booked By:</small></td>
				<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $staffname; ?> (<?php echo $f_date_issued.' '.$time_issued; ?>)</small></td>
			</tr>
			<tr>
				<td width="150px" align="left"><small class="ft-xxsml-size">No of Rooms:</small></td>
				<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $get_numbr; ?></small></td>
			</tr>
			<tr>
				<td width="150px" align="left"><small class="ft-xxsml-size">Adult:</small></td>
				<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $adult; ?></small></td>
			</tr>
			<tr>
				<td width="150px" align="left"><small class="ft-xxsml-size">Child:</small></td>
				<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $child; ?></small></td>
			</tr>
			<tr>
				<td width="150px" align="left"><small class="ft-xxsml-size">Extra Beds:</small></td>
				<td width="200px" align="center"><small class="ft-xxsml-size"><?php echo $extrabed; ?></small></td>
			</tr>
		</table>

		<div class="block-element top-push-10 bottom-push-20 pads10 grey-theme ft-xsml-size">Balance Paid By: &nbsp; <b><?php echo $bill_paid_by; ?></b></div>

		<h4 class="large nobold">&bull; Tariff Details <?php if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 4 || $room_stat_id == 6)) { ?><a href="javascript:void(0)" class="blue-font ft-xsml-size left-push-20" onclick="guestPay('<?php echo $booking_number; ?>')">Payments & Invoices</a><?php } else { ?><a href="javascript:void(0)" class="steel-blue-font ft-xsml-size left-push-20">Payments & Invoices</a><?php } ?></h4>
		
		<div class="block-element top-push-7">
			<div id="fsbox" class="ln-display-box float-left nc-width-50 pads7 box-border-thick black-theme white-font ft-xsml-size alignct anchor add-bold motion" onclick="changeCc('fsbox')">Estimated</div>
			<div id="ssbox" class="ln-display-box float-left nc-width-50 pads7 box-border-thick ft-xsml-size alignct anchor motion" onclick="changeCc('ssbox')">Actual</div>
		</div>
		<div id="estimated-cc" class="block-element top-push-3 motion">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Room Charges</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_room_charges; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Discount</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_discount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Service Charge</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_service_charge; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Value Added Tax</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_tax_amount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Consumption Tax</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_consumption_tax_amount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Other Charges</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_other_charges; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Total</small></td>
					<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_grand_total; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Amount Paid</small></td>
					<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php echo $print_amount_paid; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Balance</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_es_balance; ?></small></td>
				</tr>
			</table>
		</div>
		<div id="actual-cc" class="noshow top-push-3 motion">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Room Charges</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_room_charges; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Discount</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_discount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Service Charge</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_service_charge; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Value Added Tax</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_tax_amount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Consumption Tax</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_consumption_tax_amount; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Other Charges</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_other_charges; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Total</small></td>
					<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php echo $print_grand_total; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Amount Paid</small></td>
					<td width="200px" align="right" class="grey-2-theme right-pull-5 box-border-thick-white"><small class="ft-xxsml-size">&#8358; <?php echo $print_amount_paid; ?></small></td>
				</tr>
				<tr>
					<td width="150px" align="left"><small class="ft-xxsml-size">Balance</small></td>
					<td width="200px" align="right" class="right-pull-5"><small class="ft-xxsml-size">&#8358; <?php echo $print_balance; ?></small></td>
				</tr>
			</table>
		</div>

	</div>
	<div class="block-element new-line-space">
	</div>
	
	<div class="block-element box-border-thick pads10 top-push-20">
		<?php if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 6)) { ?><a href="javascript:void(0)" class="blue-font ft-xsml-size add-bold left-push-10" onclick="addMrm()">+ Add New Room</a><?php } ?>
		<form action="" method="post" autocomplete="off">
			<input type="hidden" id="rwcounter" value="<?php echo $get_numbr; ?>">
			<input type="hidden" name="bookingnumber" id="bookingnumber" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="bookingtype" id="bookingtype" value="<?php echo $booking_type; ?>">
			<input type="hidden" name="allowbilltoroom" id="allowbilltoroom" value="<?php echo $allow_bill_to_room; ?>">
			<input type="hidden" name="billpayby" id="billpayby" value="<?php echo $bill_paid_by; ?>">
			<input type="hidden" name="primaryguest" id="primaryguest" value="<?php echo $get_guest_detail[7]; ?>">
			<div class="block-element box-border-dark-thick-top box-border-dark-thick-right box-border-dark-thick-bottom box-border-dark-thick-left top-push-7 x-scroll">
				<div class="cs-width-1700">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="50px" align="center"><input type="checkbox" id="select-all-box" lang="u" value="<?php echo $get_numbr; ?>" onclick="checkallboxes('select-all-box','select-all-box','chk','')"></th>
							<th width="150px" align="left"><small class="ft-xxsml-size">Room Types</small></th>
							<th width="150px" align="left"><small class="ft-xxsml-size">Room Nos.</small></th>
							<th width="70px" align="left"><small class="ft-xxsml-size">Adults</small></th>
							<th width="70px" align="left"><small class="ft-xxsml-size">Child</small></th>
							<th width="120px" align="left"><small class="ft-xxsml-size">Occupancy Type</small></th>
							<th width="100px" align="left"><small class="ft-xxsml-size">Check-In</small></th>
							<th width="100px" align="left"><small class="ft-xxsml-size">Check-Out</small></th>
							<th width="100px" align="left"><small class="ft-xxsml-size">Status</small></th>
							<th width="150px" align="left"><small class="ft-xxsml-size">Allow Bill</small></th>
							<th width="100px" align="left"><small class="ft-xxsml-size">Billing Status</small></th>
							<th width="150px" align="center"><small class="ft-xxsml-size">Action</small></th>
							<th align="left"></th>
							<th width="400px" align="left" class="box-border-thick-left">
								<small class="block-element white-font left-pull-10 top-pull-3 bottom-pull-3 box-border-thick-bottom add-bold">Guest Detail</small>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<th width="100px" align="center"><small class="ft-xxsml-size">First Name</small></th>
										<th width="130px" align="center"><small class="ft-xxsml-size">Last Name</small></th>
										<th width="100px" align="center"><small class="ft-xxsml-size">Phone No.</small></th>
										<th width="70px" align="center"><small class="ft-xxsml-size">Action</small></th>
									</tr>
								</table>
							</th>
						</tr>
						
						<?php

							if(is_array($get_occupancy_data)) {
								
								/*$alp = range(1,5);*/ $gog = 0; $checker_id=0;
								$occupancy_type = select_dt_fetch('status','Active',$tbL51,'id','name');
								$room_type_id=""; $room_type=""; $block_id=""; $block_name=""; $room_prefix=""; $room_number="";
								$cur_occupancy_type=""; $gcheckin=""; $gcheckout=""; $this_guest=""; $gu_name="";
								$max_adults=""; $max_childs=""; $disabled_this_room="";
								
								foreach ($get_occupancy_data as $gokey => $govalue) {
									
									$room_type_id = idget_fdata($tbL56,'id',$govalue['roomid'],'room_type_id');
									
									$room_type = idget_data($tbL52,$room_type_id,'name');
									$max_adults = idget_data($tbL52,$room_type_id,'adult');
									$max_childs = idget_data($tbL52,$room_type_id,'child');

									$block_id = idget_fdata($tbL56,'id',$govalue['roomid'],'blockid');
									$block_name = idget_data($tbL49,$block_id,'name');
									$room_prefix = idget_data($tbL56,$govalue['roomid'],'roomprefix');
									$room_number = idget_data($tbL56,$govalue['roomid'],'roomnumber');
									$cur_occupancy_type = idget_data($tbL51,$govalue['occupancy_type'],'name');

									$gcheckin = write_dateF($gh_get_date_format,$govalue['checkin']);
									$gcheckout = write_dateF($gh_get_date_format,$govalue['checkout']);

									$this_guest = explode('/', $guest_array[$gog]);
									$gu_name = explode(' ', $this_guest[0]);

									$checker_id += 1;

									if(isset($govalue['cancel_policy']) && $govalue['cancel_policy'] >= 1) { $disabled_this_room=" disabled"; }
									else { if(isset($govalue['status']) && ($govalue['status'] == 'Checked Out' || $govalue['status'] == 'Room Swapped' || $govalue['status'] == 'Room Upgrade' || $govalue['status'] == 'Room Downgrade')) { $disabled_this_room=" disabled"; } else { $disabled_this_room=""; } }

									?>
										<tr>
											<td width="50px" align="center">
												<input type="checkbox" name="checkers[]" id="chk<?php echo $checker_id; ?>" value="<?php echo $govalue['id']; ?>"<?php echo $disabled_this_room; ?>>
												<input type="hidden" name="rwid[]" value="<?php echo $govalue['id']; ?>">
											</td>
											<td width="150px" align="left">
												<small class="ft-xxsml-size"><b class="fa-wallet steel-blue-font nobold right-push-5"></b> <?php echo $room_type; ?></small>
												<input type="hidden" name="roomtype[]" value="<?php echo $room_type_id; ?>">
											</td>
											<td width="150px" align="left">
												<small class="ft-xxsml-size"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</small>
												<input type="hidden" name="roomnumber[]" value="<?php echo $govalue['roomid']; ?>">
											</td>
											<td width="70px" align="left" class="right-pull-3">
												<select name="adults[]" required="required">
													<option value="<?php echo $govalue['adult']; ?>" selected><?php echo $govalue['adult']; ?></option>
													<?php
														for($a=1; $a<=count($max_adults); $a++) {
															?><option value="<?php echo $a; ?>"><?php echo $a; ?></option><?php
														}
													?>
												</select>
											</td>
											<td width="70px" align="left" class="right-pull-3">
												<select name="childs[]" required="required">
													<option value="<?php echo $govalue['child']; ?>" selected><?php echo $govalue['child']; ?></option>
													<?php
														for($c=1; $c<=count($max_childs); $c++) {
															?><option value="<?php echo $c; ?>"><?php echo $c; ?></option><?php
														}
													?>
												</select>
											</td>
											<td width="120px" align="left" class="right-pull-3">
												<select name="occupancy_type[]" required="required">
													<option value="<?php echo $govalue['occupancy_type']; ?>" selected><?php echo $cur_occupancy_type; ?></option><?php echo $occupancy_type; ?>
												</select>
											</td>
											<td width="100px" align="left" class="left-pull-5">
												<small class="ft-xxsml-size add-bold"><?php echo $gcheckin; ?></small>
												<input type="hidden" name="checkin[]" value="0">
											</td>
											<td width="100px" align="left" class="left-pull-5">
												<small class="ft-xxsml-size add-bold"><?php echo $gcheckout; ?></small>
												<input type="hidden" name="checkout[]" value="0">
											</td>
											<td width="100px" align="left">
												<small class="ft-xxsml-size"><?php echo $govalue['status']; ?></small>
												<?php if(isset($govalue['status']) && ($govalue['status'] == 'Checked In' || $govalue['status'] == 'Reserved')) { ?> <a href="javascript:void(0)" class="dark-black-font ft-sml-size" onclick="openWin('guest-room-change'); htmlpassval('<?php echo trim($this_guest[2]); ?>','rsudfieldset1'); htmlpassval('<?php echo $govalue['roomid']; ?>','rsudfieldset2');"><b class="fa-bookmark-fil blue-font nobold"></b></a><?php } if(isset($govalue['status']) && $govalue['status'] == 'Checked In') { ?> <a href="javascript:void(0)" class="dark-black-font ft-sml-size" onclick="openWin('guest-inclusion'); htmlpassval('<?php echo trim($this_guest[2]); ?>','infieldset1'); htmlpassval('<?php echo $govalue['roomid']; ?>','infieldset2'); getdata('list-inclusion','eget-inclusion',1,'div');"><b class="fa-database violet-font nobold"></b></a><?php } ?>
											</td>
											<td width="150px" align="left">
												<small class="ft-xxsml-size">Bill to</small>
											</td>
											<td width="100px" align="center">
												<?php if(isset($govalue['status']) && ($govalue['status'] == 'Checked In' || $govalue['status'] == 'Checked Out')) { ?><a href="javascript:void(0)" class="dark-black-font ft-sml-size" onclick="billStat('<?php echo $booking_number; ?>',<?php echo $govalue['roomid']; ?>,<?php echo $this_guest[2]; ?>)"><b class="fa-male light-slate-blue-font nobold right-push-5"></b></a><?php } ?>
											</td>
											<td width="150px" align="left">
												<textarea name="remarks[]" placeholder="Remarks (if any?)" class="cs-height-50 ft-xxsml-size"><?php echo $govalue['remarks']; ?></textarea>
											</td>
											<td align="left"></td>
											<td width="400px" align="left">
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td width="100px" align="center"><small class="ft-xxsml-size"><?php echo $gu_name[0]; ?></small></td>
														<td width="130px" align="center"><small class="ft-xxsml-size"><?php echo $gu_name[1]; ?></small></td>
														<td width="100px" align="center"><small class="ft-xxsml-size"><?php echo $this_guest[1]; ?></small></td>
														<td width="70px" align="center"><a href="javascript:void(0)" class="dark-blue-font ft-xsml-size" onclick="openWin('guest-info'); getGuestdata(<?php echo trim($this_guest[2]); ?>)" title="Edit Record"><b class="fa-settings steel-blue-font nobold right-push-5"></b></a></td>
													</tr>
												</table>
											</td>
										</tr>
									<?php

									$gog += 1;
								}
							}

						?>
							<tbody id="room-listing"></tbody>
					</table>
				</div>
			</div>
			<div class="block-element top-pull-15 bottom-pull-15" align="center">
				<?php
						if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 6)) {
							?>
								<input type="submit" name="savechangesbutton" value="Save Changes" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 blue-white-state">
								<input type="submit" name="cancelroombutton" value="Cancel Room" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 blue-white-state">
								<!--<input type="submit" name="noshowbutton" value="Apply No-Show" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 blue-white-state">-->
								<input type="submit" name="checkinbutton" value="Check-In" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 blue-white-state">
								<input type="submit" name="checkoutbutton" value="Check-Out" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state">
							<?php
						} elseif(isset($room_stat_id) && $room_stat_id == 4) {
							?>
								<input type="button" value="Save Changes" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5" disabled="disabled">
								<input type="button" value="Cancel Room" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5" disabled="disabled">
								<!--<input type="button" value="Apply No-Show" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5" disabled="disabled">-->
								<input type="button" value="Check-In" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5" disabled="disabled">
								<input type="button" value="Check-Out" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20" disabled="disabled">
							<?php
						} elseif(isset($room_stat_id) && $room_stat_id == 7) {
							?>
								<input type="submit" name="savechangesbutton" value="Save Changes" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 blue-white-state">
								<input type="submit" name="confirmbutton" value="Confirm Button" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 black-white-state">
								<input type="submit" name="releasebutton" value="Release Booking" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 right-push-5 black-white-state">
								<input type="button" name="extendbutton" value="Extend Booking" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 black-white-state" onclick="openWin('guest-temp-room-extension')">
							<?php
						}
				?>
			</div>
			<fieldset>
				<legend class="ft-xsml-size add-bold">Booking Legends</legend>
				<div class="block-element pads15">
					
					<span class="ln-display-box float-left nc-width-35">
						<ul class="nolist">
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-wallet steel-blue-font nobold right-push-5"></b> Rack Rates</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-heart blue-font nobold right-push-5"></b> Seasons</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-gift royal-blue-font nobold right-push-5"></b> Packages</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-money dark-black-font nobold right-push-5"></b> Tariff Change</a>
							</li>
						</ul>
					</span>
					<span class="ln-display-box float-left nc-width-35">
						<ul class="nolist">
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-umbrella red-font nobold right-push-5"></b> Not Billed for The Day</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-favourite forest-green-font nobold right-push-5"></b> Billed for The Day</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-credit-card steel-blue-font nobold right-push-5"></b> Credit Notification</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-male light-slate-blue-font nobold right-push-5"></b> Check-In Card</a>
							</li>
						</ul>
					</span>
					<span class="ln-display-box float-left nc-width-30">
						<ul class="nolist">
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-settings steel-blue-font nobold right-push-5"></b> Edit Record</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-database violet-font nobold right-push-5"></b> Inclusions</a>
							</li>
							<li class="bottom-push-7">
								<a class="dark-blue-font ft-xsml-size"><b class="fa-bookmark-fil blue-font nobold right-push-5"></b> Room Type Tariff Change</a>
							</li>
						</ul>
					</span>
					<span class="block-element new-line-space">
					</span>
							
				</div>
			</fieldset>
		</form>
	</div>

</div>

<?php
	$salutations = select_dt_fetch('status','Active',$tbL42,'id','name');
	$identity_type = select_dt_fetch('status','Active',$tbL37,'id','name');
	$business_src = select_dt_fetch('status','Active',$tbL43,'id','name');
	$transit_mode = select_dt_fetch('status','Active',$tbL29,'id','name');
	$cancelling_rs = select_dt_fetch('status','Active',$tbL32,'id','name');

	$tostay = explode(' ',$get_guest_detail[14]);
	$fpr = str_replace('s', '(s)', $tostay[1]);

	$temp_rs_query = array("booking_number"=>$booking_number,"tempreserved"=>"Yes");
	$temp_rs_data = mysqli_data_fetch($tbL96,'tempdatereserved',$temp_rs_query,'noarray');
?>

<div id="popup-win" class="fx-position-flow zind-2 motion btscr noscroll" align="left">
	<div id="frame-box" class="noshow">
		<div class="block-element alignrt">
			<input type="hidden" name="mdl" id="mdl">
			<a href="javascript:void(0)" class="ft-xsml-size black-font" onclick="closeWin()">X Close</a>
		</div>
		<div id="apply-discount" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Apply Discount</h4><br>
			
			<span class="ln-display-box float-left nc-width-20 top-pull-15">
				<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
			</span>
			<span class="ln-display-box float-left nc-width-50 right-pull-50">
				<div class="block-element bottom-push-10 ft-sml-size">
					<?php
						$mbt_isnum = workflow_privilege('module_category_tbl','category_library_tbl',3,'apply discount');
						$mbt_isprivilege = perform_role_check($tbL5,$myrole,$mbt_isnum,'');

						if(!isset($mbt_isprivilege) || $mbt_isprivilege == 0) { $verify1 = "block-element"; $verify1b = "noshow"; } else { $verify1 = "noshow"; $verify1b = "block-element"; }

					?>
						<small id="notify-2" class="noshow bottom-push-10 add-bold"></small>
						<div id="login-authen-2" class="<?php echo $verify1; ?> box-border-thick xsml-rounded-button pads20 noscroll">
							<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen1(event)" id="mbk-authen">
								<h4 class="large nobold red-font">Approve Authentication</h4><br>
								<span class="block-element bottom-push-10">
									<input type="text" name="ulogin" id="ulogin" placeholder="User Login" required="required">
								</span>
								<span class="block-element bottom-push-20">
									<input type="text" name="upwd" id="upwd" placeholder="User Password" required="required">
								</span>
								<span class="block-element bottom-push-10">
									<input type="number" name="discountpc" id="discountpc" placeholder="Enter discount" required="required">
								</span>

								<p class="alignct top-pull-20">
									<input type="hidden" name="ubk" value="<?php echo $booking_number; ?>">
									<input type="submit" name="applydiscbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
								</p>
							</form>
						</div>
					
						<div id="mbt2" class="<?php echo $verify1b; ?>">
							<form action="" method="post" autocomplete="off">
								<h4 class="large nobold red-font">Enter discount (0 - max)</h4><br>
								<span class="block-element bottom-push-10">
									<input type="number" name="discountpc" id="discountpc" placeholder="Enter discount" required="required">
								</span>

								<p class="alignct top-pull-20">
									<input type="hidden" name="ubk" value="<?php echo $booking_number; ?>">
									<input type="submit" name="applydiscbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
								</p>
							</form>
						</div>
							
				</div>
			</span>
			<span class="block-element new-line-space">
			</span>
			
		</div>
		<div id="allowModifyBooking" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Modify Booking Type</h4><br>
			
			<span class="ln-display-box float-left nc-width-20 top-pull-15">
				<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
			</span>
			<span class="ln-display-box float-left nc-width-50 right-pull-50">
				<div class="block-element bottom-push-10 ft-sml-size">
					<?php
						if(!isset($mbt_isprivilege) || $mbt_isprivilege == 0) { $verify1 = "block-element"; $verify1b = "noshow"; } else { $verify1 = "noshow"; $verify1b = "block-element"; }
					?>
						<small id="notify-1" class="noshow bottom-push-10 add-bold"></small>
						<div id="login-authen-1" class="<?php echo $verify1; ?> box-border-thick xsml-rounded-button pads20 noscroll">
							<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen1(event)" id="mbk-authen">
								<h4 class="large nobold red-font">Approve Authentication</h4><br>
								<span class="block-element bottom-push-10">
									<input type="text" name="ulogin" id="ulogin" placeholder="User Login" required="required">
								</span>
								<span class="block-element bottom-push-20">
									<input type="text" name="upwd" id="upwd" placeholder="User Password" required="required">
								</span>
								<span class="block-element bottom-push-10">
									<textarea name="uremark" id="uremark" placeholder="Enter remark?" required="required"></textarea>
								</span>

								<p class="alignct top-pull-20">
									<input type="hidden" name="ubk" id="ubk" value="<?php echo $booking_number; ?>">
									<input type="submit" name="modifybkbutton" value="Approve" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
								</p>
							</form>
						</div>
					
						<div id="mbt" class="<?php echo $verify1b; ?>">
							<form action="" method="post" autocomplete="off">
								<div class="block-element top-pull-15 alignlt">
									<input type="hidden" name="mbtfieldset1" id="mbtfieldset1" value="<?php echo $get_guest_detail[7]; ?>">
									<input type="hidden" name="mbtfieldset2" id="mbtfieldset2" value="<?php echo $booking_number; ?>">
									<input type="button" value="Send" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button" onclick=""> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
								</div>
							</form>
						</div>
							
				</div>
			</span>
			<span class="block-element new-line-space">
			</span>
			
		</div>
		<div id="sms" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Sending SMS</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-50 right-pull-50">
					<div class="block-element bottom-push-10 ft-sml-size">
						<small id="sms-notifier" class="noshow bottom-push-10 add-bold"></small>
						<span class="block-element bottom-push-10">
							<small class="block-element bottom-push-3 dark-grey-font left-pull-3">Predefined Sender</small>
							<input type="text" name="smsSender" id="smsSender" placeholder="Predefined Sender" value="<?php echo _SHORT_NAME; ?>" readonly>
						</span>
						<span class="block-element bottom-push-10">
							<textarea name="smsMessage" id="smsMessage" placeholder="Message" onkeyup="counter_xter('smsMessage','max-xter',150)" onblur="counter_xter('smsMessage','max-xter',150)"></textarea>
							<small class="block-element bottom-push-20 left-pull-5 alignlt dark-grey-font ft-xxsml-size">maximum of 150 charaters allowed</small>
							<div class="ln-display-box float-left nc-width-20 right-push-20 white-theme obj-light-shadow">
								<input type="text" id="max-xter" value="150" readonly="readonly">
							</div>
							<div class="ln-display-box float-left top-pull-15">
								<small class="red-font">Charaters Remaining</small>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="msfieldset1" id="msfieldset1" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="msfieldset2" id="msfieldset2" value="<?php echo $booking_number; ?>">
						<input type="button" value="Send" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button" onclick="sendsms()"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="emailer" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Sending Mail</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-50 right-pull-50">
					<div class="block-element bottom-push-10 ft-sml-size">
						<small id="mail-notifier" class="noshow bottom-push-10 add-bold"></small>
						<span class="block-element bottom-push-10">
							<input type="text" name="mailSubject" id="mailSubject" placeholder="Subject">
						</span>
						<span class="block-element bottom-push-10">
							<textarea name="mailMessage" id="mailMessage" placeholder="Message"></textarea>
						</span>
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="xmfieldset1" id="xmfieldset1" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="xmfieldset2" id="xmfieldset2" value="<?php echo $booking_number; ?>">
						<input type="button" value="Send" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button" onclick="sendmail()"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="latecheckout" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Disable Late Checkout Charges</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-70 right-pull-50">
					<div class="block-element bottom-push-10 ft-sml-size">
						Do you really want to continue with this request?
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="etxfieldset1" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="etxfieldset2" value="<?php echo $booking_number; ?>">
						<input type="submit" name="latecheckoutbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="weekendfares" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Disable Weekend Fares</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-70 right-pull-50">
					<div class="block-element bottom-push-10 ft-sml-size">
						Do you really want to continue with this request?
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="etxfieldset1" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="etxfieldset2" value="<?php echo $booking_number; ?>">
						<input type="submit" name="weekendfarebutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="excl-tax" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Exclude Hotel Taxes</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-70 right-pull-50">
					<div class="block-element bottom-push-10 ft-sml-size">
						<span class="ln-display-box float-left right-push-5 bottom-push-10">Select All</span>
						<span class="ln-display-box float-left bottom-push-10"><input type="checkbox" id="tax-checker" value="3" lang="c" checked="checked" onclick="checkallboxes('tax-checker','tax-checker','tax-checker-','')"></span>
						<span class="block-element new-line-space"></span>

						<?php if(isset($is_consumption_tax) && $is_consumption_tax == 1) { ?><span class="ln-display-box float-left right-push-5"><input type="checkbox" name="taxchecker[]" id="tax-checker-1" value="1" checked="checked"></span><span class="ln-display-box float-left right-push-20">Consumption Tax (<?php echo $gh_get_consumption_tax; ?>%)</span><?php $apl = 1; } ?>
						
						<?php if(isset($is_servicecharge) && $is_servicecharge == 1) { ?><span class="ln-display-box float-left right-push-5"><input type="checkbox" name="taxchecker[]" id="tax-checker-2" value="2" checked="checked"></span><span class="ln-display-box float-left right-push-20">Service Charge (<?php echo $gh_get_service_charge; ?>%)</span><?php $apl = 1; } ?>

						<?php if(isset($is_vat) && $is_vat == 1) { ?><span class="ln-display-box float-left right-push-5"><input type="checkbox" name="taxchecker[]" id="tax-checker-3" value="3" checked="checked"></span><span class="ln-display-box float-left">Vat (<?php echo $gh_get_vat; ?>%)</span><?php $apl = 1; } ?>
						
						<span class="block-element new-line-space"></span>
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<?php if(isset($apl) && $apl == 1) { ?><input type="hidden" name="etxfieldset1" value="<?php echo $get_guest_detail[7]; ?>"><input type="hidden" name="etxfieldset2" value="<?php echo $booking_number; ?>"><input type="submit" name="removetaxbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a><?php } else { ?>All taxes already applied!<?php } ?>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-room-change" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Guest Room Swap, Upgrade & Downgrade</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-50 right-pull-50">
					<div class="block-element bottom-push-10">
						<a href="javascript:void(0)" class="blue-font ft-xsml-size right-push-20" onclick="getdata('swap-room','eget-swap-room-type','rsudfieldset2','div'); objDisplay('the-apply-button'); writeObjheader('up-down-grade-room',''); htmlpassval(1,'rsudfieldset4')"><u>Swap Room</u></a>
						<a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="getdata('up-down-grade-room','eget-up-downgrade-room-type',1,'div'); objDisplay('the-apply-button'); writeObjheader('swap-room',''); htmlpassval(2,'rsudfieldset4')"><u>Upgrade, Downgrade Room</u></a>

						<div class="block-element top-push-30">
							<div id="swap-room"></div>
							<div id="up-down-grade-room"></div>
						</div>
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="rsudfieldset1" id="rsudfieldset1" value="">
						<input type="hidden" name="rsudfieldset2" id="rsudfieldset2" value="">
						<input type="hidden" name="rsudfieldset3" id="rsudfieldset3" value="<?php echo $booking_number; ?>">
						<input type="hidden" name="rsudfieldset4" id="rsudfieldset4" value="0">
						<div id="the-apply-button" class="noshow">
							<input type="submit" name="sudbutton" value="Update" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
						</div>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-inclusion" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Inclusions</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<div id="list-inclusion" class="block-element bottom-push-10">
						<small class="block-element alignct">Loading..</small>
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="infieldset1" id="infieldset1" value="">
						<input type="hidden" name="infieldset2" id="infieldset2" value="">
						<input type="hidden" name="infieldset3" id="infieldset3" value="<?php echo $booking_number; ?>">
						<input type="submit" name="inclusionbutton" value="Update Inclusions" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-temp-room-extension" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Guest Temporary Reserve Extension</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Hold Reservation Till <sup class="red-font">*</sup></small>
						<input type="date" name="gtrfieldset1" id="gtrfieldset1" value="<?php echo $temp_rs_data[0]; ?>">
					</div>
					
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="gtrfieldset2" id="gtrfieldset2" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="gtrfieldset3" id="gtrfieldset3" value="<?php echo $booking_number; ?>">
						<input type="submit" name="tempreservebutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-stay-adjustment" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Guest Lodging Adjustment?</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Check In <sup class="red-font">*</sup></small>
						<input type="date" name="gsfieldset1" id="gsfieldset1" value="<?php echo $xcheckin_date; ?>" <?php if(isset($room_stat_id) && $room_stat_id != 7) { ?>readonly="readonly"<?php } ?>>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Check Out <sup class="red-font">*</sup></small>
						<input type="date" name="gsfieldset2" id="gsfieldset2" value="<?php echo $xcheckout_date; ?>">
					</div>
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="gsfieldset3" id="gsfieldset3" value="<?php echo $get_guest_detail[7]; ?>">
						<input type="hidden" name="gsfieldset4" id="gsfieldset4" value="<?php echo $booking_number; ?>">
						<input type="submit" name="stayadjbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="room-cancelling-reason" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Reason for Cancelling?</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<select name="crfieldset1" id="crfieldset1" required="required">
						<option value="" selected="selected">Choose</option>
						<?php echo $cancelling_rs; ?>
					</select>
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="crfieldset2" id="crfieldset2" value="<?php echo $tar; ?>">
						<input type="hidden" name="crfieldset3" id="crfieldset3" value="<?php echo $this_primary_guest_id; ?>">
						<input type="hidden" name="crfieldset4" id="crfieldset4" value="<?php echo $pst_booking_number; ?>">
						<input type="submit" name="crbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-transit-mode" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Guest Arrival & Departure Mode</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-50 right-pull-50">
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Source of Business <sup class="red-font">*</sup></small>
						<select name="gtfieldset1" id="gtfieldset1" required="required">
							<?php if(isset($gad_1) && $gad_1 >= 1) { ?><option value="<?php echo $gad_1; ?>" selected="selected"><?php echo $source_of_biz; ?></option><?php } else { ?><option value="" selected="selected">Choose</option><?php } ?>
							<?php echo $business_src; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Arrival Mode <sup class="red-font">*</sup></small>
						<select name="gtfieldset2" id="gtfieldset2" required="required">
							<?php if(isset($gad_2) && $gad_2 >= 1) { ?><option value="<?php echo $gad_2; ?>" selected="selected"><?php echo $arrival_mode; ?></option><?php } else { ?><option value="" selected="selected">Choose</option><?php } ?>
							<?php echo $transit_mode; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Departure Mode <sup class="red-font">*</sup></small>
						<select name="gtfieldset3" id="gtfieldset3">
							<?php if(isset($gad_3) && $gad_3 >= 1) { ?><option value="<?php echo $gad_3; ?>" selected="selected"><?php echo $departure_mode; ?></option><?php } else { ?><option value="" selected="selected">Choose</option><?php } ?>
							<?php echo $transit_mode; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
						<textarea name="gtfieldset4" id="gtfieldset4"><?php echo $gad_remarks; ?></textarea>
					</div>
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="gtfieldset5" id="gtfieldset5" value="<?php echo $booking_number; ?>">
						<input type="submit" name="transitbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="guest-allow-bill-to-room" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Allow Bill to Room</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<select name="gafieldset1" id="gafieldset1" required="required">
						<option value="" selected="selected">Choose</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
					<div class="block-element top-pull-15 alignlt">
						<input type="hidden" name="gafieldset2" id="gafieldset2" value="<?php echo $booking_number; ?>">
						<input type="submit" name="allowbillbutton" value="Apply" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
		<div id="booking-activities-info" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Booking Activities</h4><br>
			<span class="ln-display-box float-left nc-width-20 top-pull-15">
				<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
			</span>
			<span class="ln-display-box float-left nc-width-70 right-pull-50">
				<?php

					$additionalQuery = " ORDER BY id DESC";
					$bh_query = array("booking_number"=>$booking_number,"deletedata"=>0);
					$get_bh_data = mysqli_data_fetch($tbL132,'activities,datelogged,timelogged',$bh_query,'array');
					
					if(is_array($get_bh_data)) {
						
						$wh = array("nc-width-80","nc-width-70","nc-width-60");

						foreach ($get_bh_data as $bhkey => $bhvalue) {
							
							shuffle($wh);

							?>
								<div class="block-element bottom-push-20">
									<span class="ln-display-box float-left cs-width-50 cs-height-70 top-pull-15 left-pull-7 noscroll">
										<div class="cs-width-40 cs-height-40 box-border-thick rotate-left-30deg top-push-3 left-push-30 white-theme"></div>
									</span>
									<span class="float-left box-border-thick pads20 rounded-button zind-4 neg-left-margin-2 white-theme <?php echo $wh[0]; ?>">
										<h4 class="large nobold"><b class="steel-blue-font"><?php echo $booking_number; ?></b>: <?php echo $bhvalue['activities']; ?></h4>
									</span>
									<span class="block-element new-line-space">
									</span>
								</div>
							<?php
						}
					}

				?>
			</span>
			<span class="block-element new-line-space">
			</span>
		</div>
		<div id="guest-info" class="noshow white-theme top-push-5 bottom-push-5">
			<h4 class="large">Guest Details</h4><br>
			<form action="" method="post" autocomplete="off">
				<span class="ln-display-box float-left nc-width-20 top-pull-15">
					<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
				</span>
				<span class="ln-display-box float-left nc-width-40 right-pull-50">
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Name <sup class="red-font">*</sup></small>
						<span class="ln-display-box float-left nc-width-25 right-pull-5">
							<select name="gfieldset1" id="gfieldset1" required="required">
								<option value="<?php echo $get_guest_detail[1]; ?>" selected="selected"><?php echo $salutation; ?></option>
								<?php echo $salutations; ?>
							</select>
						</span>
						<span class="ln-display-box float-left nc-width-75">
							<input type="text" name="gfieldset2" id="gfieldset2" value="<?php echo $get_guest_detail[2]; ?>" required>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Mobile No. <sup class="red-font">*</sup></small>
						<input type="text" name="gfieldset3" id="gfieldset3" value="<?php echo $get_guest_detail[3]; ?>" required>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Email Address</small>
						<input type="text" name="gfieldset4" id="gfieldset4" value="<?php echo $get_guest_detail[4]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Means of Identification</small>
						<select name="gfieldset10" id="gfieldset10">
							<?php if(isset($get_guest_detail[11]) && $get_guest_detail[11] >= 1) { $idtype = idget_data($tbL37,$get_guest_detail[11],'name'); ?><option value="<?php echo $get_guest_detail[11]; ?>" selected="selected"><?php echo $idtype; ?></option><?php } else { ?><option value="" selected="selected">Choose</option><?php } ?>
							<?php echo $identity_type; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Identification Number</small>
						<input type="text" name="gfieldset11" id="gfieldset11" value="<?php echo $get_guest_detail[12]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Occupation</small>
						<input type="text" name="gfieldset12" id="gfieldset12" value="<?php echo $get_guest_detail[13]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Period of Stay</small>
						<span class="ln-display-box float-left nc-width-70 right-pull-5">
							<input type="text" name="gfieldset13" id="gfieldset13" pattern="\d*" placeholder="Number e.g 2" value="<?php echo $tostay[0]; ?>">
						</span>
						<span class="ln-display-box float-left nc-width-30">
							<select name="gfieldset14" id="gfieldset14" required="required">
								<?php if(isset($tostay[1]) && !empty($tostay[1])) { ?><option value="<?php echo $tostay[1]; ?>"><?php echo ucfirst($fpr); ?></option><?php } ?>
								<option value="days">Day(s)</option>
								<option value="months">Month(s)</option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</span>
				<span class="ln-display-box float-left nc-width-40 left-pull-50">
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Contact Address</small>
						<textarea name="gfieldset5" id="gfieldset5"><?php echo $get_guest_detail[6]; ?></textarea>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">City</small>
						<input type="text" name="gfieldset6" id="gfieldset6" value="<?php echo $get_guest_detail[8]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">State</small>
						<input type="text" name="gfieldset7" id="gfieldset7" value="<?php echo $get_guest_detail[9]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Country</small>
						<input type="text" name="gfieldset8" id="gfieldset8" value="<?php echo $get_guest_detail[10]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
						<textarea name="gfieldset9" id="gfieldset9"><?php echo $get_guest_detail[5]; ?></textarea>
					</div>
				</span>
				<span class="block-element new-line-space">
				</span>

				<div class="block-element top-pull-30 alignct">
					<input type="hidden" name="gfieldset15" id="gfieldset15" value="<?php echo $get_guest_detail[7]; ?>">
					<input type="submit" name="guestbutton" value="Save Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>&booking=<?php echo $_GET['booking']; ?>" class="blue-font">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="popup-win2" class="fx-position-flow zind-2 motion btscr noscroll" align="left">
	<div id="clx" class="noshow alignrt pads10">
		<a href="javascript:void(0)" class="ft-xsml-size white-font" onclick="closeWin2()">X Close</a>
	</div>
	<div id="frame-box2" class="noshow top-push-20 right-push-50 bottom-push-20 left-push-50 white-theme sml-rounded-button nc-height-80 noscroll">
		<iframe src="invoice_and_payment.php" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" width="100%" height="100%" name="invoiceandpayment" id="invoiceandpayment"></iframe>
	</div>
</div>


<div id="popup-win3" class="fx-position-flow zind-2 motion btscr noscroll" align="left">
	<div id="clx2" class="noshow alignrt pads10">
		<a href="javascript:void(0)" class="ft-xsml-size white-font" onclick="closeWin3()">X Close</a>
	</div>
	<div id="frame-box3" class="noshow top-push-20 right-push-50 bottom-push-20 left-push-50 white-theme sml-rounded-button nc-height-80 noscroll">
		<iframe src="bill_status.php" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" width="100%" height="100%" name="billstatus" id="billstatus"></iframe>
	</div>
</div>

<script>

	function openWin(box) {
		chgclass('popup-win','fx-position-stick zind-2 motion fscr txp5-white y-scroll');
		chgclass('frame-box','block-element pads20');
		chgclass(box,'block-element white-theme top-push-10 right-push-50 bottom-push-10 left-push-50 pads30 obj-light-shadow');
		htmlpassval(box,'mdl');
	}

	function closeWin() {
		chgclass('popup-win','fx-position-flow zind-2 motion btscr noscroll');
		chgclass('frame-box','noshow');

		var xbox = document.getElementById('mdl').value;
		chgclass(xbox,'noshow white-theme top-push-5 bottom-push-5');
	}

	function closeWin2() {
		chgclass('popup-win2','fx-position-flow zind-2 motion btscr noscroll');
		objHidden('frame-box2'); objHidden('clx');
	}

	function closeWin3() {
		chgclass('popup-win3','fx-position-flow zind-2 motion btscr noscroll');
		objHidden('frame-box3'); objHidden('clx2');
	}


	function dodata(str,sses,id,sopt) {
		var select_id = str;
		getdata(select_id,sses,id,sopt);
	}


	function maxAc(adult,child,room) {
		var xhr,file,string,random_numbr,ajaxson;

		string = document.getElementById(room).value;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		file = phpfile+"dbquery.php?data="+string+"&r=formaxadultandchild&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					ajaxson = JSON.parse(xhr.responseText);

					var a,c,opt_1='',opt_2='';
					
					for(a=1; a <= ajaxson.mxadult; a++) { opt_1 += '<option value="'+a+'">'+a+'</option>'; }
					for(c=0; c <= ajaxson.mxchild; c++) { opt_2 += '<option value="'+c+'">'+c+'</option>'; }

					document.getElementById(adult).innerHTML = opt_1;
					document.getElementById(child).innerHTML = opt_2;
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}


	function addMrm() {

		var r,curnumbr,contr;

		curnumbr = document.getElementById('rwcounter');
		contr = document.getElementById('room-listing');
		
		var uni_id = eval(curnumbr.value) + 1; //generate new id this row

		var tr = document.createElement('tr');
		tr.id = 'tr'+uni_id;

		var td1 = document.createElement('td');
		var td2 = document.createElement('td');
		var td3 = document.createElement('td');
		var td4 = document.createElement('td');
		var td5 = document.createElement('td');
		var td6 = document.createElement('td');
		var td7 = document.createElement('td');
		var td8 = document.createElement('td');
		var td9 = document.createElement('td');
		var td10 = document.createElement('td');
		var td11 = document.createElement('td');
		var td12 = document.createElement('td');
		var td13 = document.createElement('td');
		var td14 = document.createElement('td');
		var td15 = document.createElement('td');
		var td16 = document.createElement('td');

		var txt1 = document.createElement('input');
		var txt2 = document.createElement('input');
		var txt3 = document.createElement('input');
		var txt4 = document.createElement('input');
		var txt5 = document.createElement('input');

		var dropbox1 = document.createElement('select');
		var dropbox2 = document.createElement('select');
		var dropbox3 = document.createElement('select');
		var dropbox4 = document.createElement('select');
		var dropbox5 = document.createElement('select');
		var opt1 = document.createElement('option');
		var opt2 = document.createElement('option');
		var opt3 = document.createElement('option');
		var opt4 = document.createElement('option');
		var opt5 = document.createElement('option');
		var opt6 = document.createElement('option');
		var opt7 = document.createElement('option');
		var opt8 = document.createElement('option');
		var opt9 = document.createElement('option');
		var opt10 = document.createElement('option');
		var opt11 = document.createElement('option');
		var opt12 = document.createElement('option');

		var comment1 = document.createElement('textarea');

		var trashicon = document.createElement('b');
		trashicon.id = 'b'+uni_id;
		trashicon.className = 'fa-trash nobold anchor right-push-5';
		trashicon.title = 'Remove Row '+uni_id+':';
		trashicon.onclick = function() { 
			contr.removeChild(tr);
		}

		txt1.id = "chk"+uni_id;
		txt1.name = "checkers[]";
		txt1.type = "checkbox";
		txt1.value = 0;
		td1.align = "center";
		td1.appendChild(trashicon);
		td1.appendChild(txt1);

		dropbox1.id = "select-col-1-"+uni_id;
		dropbox1.name = "roomtype[]";
		dropbox1.required = "required";
		dropbox1.onchange = function() { getdata('select-col-2-'+uni_id,'eget-rooms','select-col-1-'+uni_id,'dropbox'); maxAc('select-col-3-'+uni_id,'select-col-4-'+uni_id,'select-col-1-'+uni_id); };
		td2.appendChild(dropbox1);

		dropbox2.id = "select-col-2-"+uni_id;
		dropbox2.name = "roomnumber[]";
		dropbox2.required = "required";
		opt1.value = "";
		opt1.text = "Choose";
		dropbox2.appendChild(opt1);
		td3.appendChild(dropbox2);

		dropbox3.id = "select-col-3-"+uni_id;
		dropbox3.name = "adults[]";
		dropbox3.required = "required";
		/*opt2.value = 1;
		opt2.text = 1;
		opt3.value = 2;
		opt3.text = 2;
		opt4.value = 3;
		opt4.text = 3;
		opt5.value = 4;
		opt5.text = 4;
		opt6.value = 5;
		opt6.text = 5;
		dropbox3.appendChild(opt2);
		dropbox3.appendChild(opt3);
		dropbox3.appendChild(opt4);
		dropbox3.appendChild(opt5);
		dropbox3.appendChild(opt6);*/
		td4.appendChild(dropbox3);

		dropbox4.id = "select-col-4-"+uni_id;
		dropbox4.name = "childs[]";
		dropbox4.required = "required";
		/*opt7.value = 0;
		opt7.text = 0;
		opt8.value = 1;
		opt8.text = 1;
		opt9.value = 2;
		opt9.text = 2;
		opt10.value = 3;
		opt10.text = 3;
		opt11.value = 4;
		opt11.text = 4;
		opt12.value = 5;
		opt12.text = 5;
		dropbox4.appendChild(opt7);
		dropbox4.appendChild(opt8);
		dropbox4.appendChild(opt9);
		dropbox4.appendChild(opt10);
		dropbox4.appendChild(opt11);
		dropbox4.appendChild(opt12);*/
		td5.appendChild(dropbox4);

		dropbox5.id = "select-col-5-"+uni_id;
		dropbox5.name = "occupancy_type[]";
		dropbox5.required = "required";
		td6.appendChild(dropbox5);

		txt2.id = "input-col-1-"+uni_id;
		txt2.name = 'checkin[]';
		txt2.type = 'date';
		txt2.required = "required";
		td7.appendChild(txt2);

		txt3.id = "input-col-2-"+uni_id;
		txt3.name = 'checkout[]';
		txt3.type = 'date';
		txt3.required = "required";
		td8.appendChild(txt3);

		comment1.id = "text-col-1-"+uni_id;
		comment1.name = "remarks[]";
		comment1.placeholder = "Remarks (if any?)";
		comment1.className = "cs-height-50 ft-xxsml-size";
		td12.appendChild(comment1);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);
		tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);
		tr.appendChild(td10);
		tr.appendChild(td11);
		tr.appendChild(td12);

		contr.appendChild(tr);
		curnumbr.value = uni_id;

		
		dodata('select-col-1-'+uni_id,'eget-roomtype-list',1,'dropbox');
		dodata('select-col-5-'+uni_id,'eget-occupancy-type',1,'dropbox');
		
		/*var checker = document.getElementById('select-all-box');
		checker.addEventListener('click',function() {
			if(checker.lang == 'u') {
				for (i = 1; i<=curnumbr.value; i++) {
					document.getElementById('chk'+i).checked = true;
				}
				checker.lang = 'c';
			} else {
				for (i = 1; i<=curnumbr.value; i++) {
					document.getElementById('chk'+i).checked = false;
				}
				checker.lang = 'u';
			}
		},false);*/
	}


	function getGuestdata(guest) {
		var xhr,file,string,random_numbr,ajaxson;

		string = guest;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		file = phpfile+"dbquery.php?data="+string+"&r=foractiveguest&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					ajaxson = JSON.parse(xhr.responseText);
					
					htmlpassval(ajaxson.guestId,'gfieldset15');
					htmlpassval(ajaxson.guestName,'gfieldset2');
					htmlpassval(ajaxson.guestMobile,'gfieldset3');
					htmlpassval(ajaxson.guestEmail,'gfieldset4');
					htmlpassval(ajaxson.guestAddress,'gfieldset5');
					htmlpassval(ajaxson.guestCity,'gfieldset6');
					htmlpassval(ajaxson.guestState,'gfieldset7');
					htmlpassval(ajaxson.guestCountry,'gfieldset8');
					htmlpassval(ajaxson.guestRemark,'gfieldset9');
					htmlpassval(ajaxson.guestIdentificationNumber,'gfieldset11');
					htmlpassval(ajaxson.guestOccupation,'gfieldset12');

					var tostay = (ajaxson.guestStay).split(' ');
					var tostay_0 = tostay[0], tostay_1 = tostay[1].replace('s','(s)');

					htmlpassval(tostay_0,'gfieldset13');

					var titl = '<option value="'+ajaxson.guestSalutationId+'" selected="selected">'+ajaxson.guestSalutationName+'</option>';
					var idtype = '<option value="'+ajaxson.guestIdentityTypeId+'" selected="selected">'+ajaxson.guestIdentityTypeName+'</option>';
					var daystay = '<option value="'+tostay[1]+'" selected="selected">'+tostay_1+'</option>';
					writeObjheader('gfieldset1',titl);
					writeObjheader('gfieldset10',idtype);
					writeObjheader('gfieldset14',daystay);
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}


	function guestPay(bookingnumber) {
		chgclass('popup-win2','fx-position-stick zind-2 motion fscr txp5-black');
		objDisplay('frame-box2'); objDisplay('clx');

		window.invoiceandpayment.location.href = "invoice_and_payment.php?booking="+bookingnumber;
	}

	function billStat(bookingnumber,roomid,customerid) {
		chgclass('popup-win3','fx-position-stick zind-2 motion fscr txp5-black');
		objDisplay('frame-box3'); objDisplay('clx2');

		window.billstatus.location.href = "room_bill_status.php?booking="+bookingnumber+"&room="+roomid+"&customer="+customerid;
	}

	function changeCc(chgr) {
		if(chgr == 'fsbox') {
			chgclass('fsbox','ln-display-box float-left nc-width-50 pads7 box-border-thick black-theme white-font ft-xsml-size alignct anchor add-bold motion');
			chgclass('ssbox','ln-display-box float-left nc-width-50 pads7 box-border-thick ft-xsml-size alignct anchor motion');
			chgclass('estimated-cc','block-element top-push-3 motion');
			chgclass('actual-cc','noshow top-push-3 motion');
		} else if(chgr == 'ssbox') {
			chgclass('ssbox','ln-display-box float-left nc-width-50 pads7 box-border-thick black-theme white-font ft-xsml-size alignct anchor add-bold motion');
			chgclass('fsbox','ln-display-box float-left nc-width-50 pads7 box-border-thick ft-xsml-size alignct anchor motion');
			chgclass('estimated-cc','noshow top-push-3 motion');
			chgclass('actual-cc','block-element top-push-3 motion');
		}
	}

	
	function sendmail() {

		var guest = document.getElementById('xmfieldset1').value,
		subject = document.getElementById('mailSubject').value,
		message = document.getElementById('mailMessage').value;

		if(guest != "" && subject != "" && message != "") {

			var xhr,file,params;

			if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
			else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

			objDisplay('mail-notifier');
			writeObjheader('mail-notifier','Sending mail, please wait..');
			
			params = "postmail=y&pguest="+guest+"&psubject="+subject+"&pmessage="+message;
			file = phpfile+"phpmailer.php";
			
			xhr.onreadystatechange=function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						//console.log(xhr.responseText);
						var json_data = xhr.responseText, json_data = json_data.replace(/ /g,'');

						if(json_data == 1) {
							writeObjheader('mail-notifier','Mail has been sent to this guest emailaddress');
						} else if(json_data == 0) {
							writeObjheader('mail-notifier','Error sending mail! Check if guest has an email');
						}
					}
				}
			};

			xhr.open('POST', file, true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(params);
		}
	}

	function sendsms() {

		var guest = document.getElementById('xmfieldset1').value,
		subject = document.getElementById('smsSender').value,
		message = document.getElementById('smsMessage').value;

		if(guest != "" && subject != "" && message != "") {

			var xhr,file,params;

			if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
			else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

			objDisplay('sms-notifier');
			writeObjheader('sms-notifier','Sending sms, please wait..');
			
			params = "postsms=y&pguest="+guest+"&psubject="+subject+"&pmessage="+message;
			file = phpfile+"phpsms.php";
			
			xhr.onreadystatechange=function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						//console.log(xhr.responseText);
						var json_data = xhr.responseText, json_data = json_data.replace(/ /g,'');

						if(json_data == 1) {
							writeObjheader('sms-notifier','Sms has been sent to this guest phone number');
						} else if(json_data == 0) {
							writeObjheader('sms-notifier','Error sending sms! Check if guest has phone number');
						}
					}
				}
			};

			xhr.open('POST', file, true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(params);
		}
	}


	function counter_xter(obj,str,max) {
		var text_box = document.getElementById(obj);
		var total_xter = document.getElementById(str);

		var typed_xters = (text_box.value).length;
		var left_xter = max - eval(typed_xters);
		total_xter.value = left_xter;

		if(left_xter <= 0) {

			var fix_text = (text_box.value).substr(0,max);
			text_box.value = fix_text;

			typed_xters = (text_box.value).length;
			left_xter = max - eval(typed_xters);
			total_xter.value = left_xter;
		}
	}


	function getUserAuthen1() {

		e.preventDefault();

		var xhr,file,params,j,json_result,data;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		writeObjheader('notify-1','Authenticating, please wait..');
		chgclass('notify-1','block-element bottom-push-10 add-bold');

		data = json_get_formdata('mbk-authen');
		params = "f=mbk-authen&fdata="+data+"&dataSend=200";

		file = phpfile+"post_form_data.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					
					j = xhr.responseText; json_result = j.replace(/ /g,'');
					
					if(json_result == 1) {
						writeObjheader('notify-1',''); chgclass('notify-1','noshow bottom-push-10 add-bold');
						chgclass('login-authen-1','noshow'); chgclass('mbt','block-element');
					} else {
						writeObjheader('notify-1','Invalid username or password');
					}
				}
			}
		}

		xhr.open('POST', file, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}

</script>