<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$tbl = $tbL127;

?>

<div class="white-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 bottom-push-30">
	<span class="ln-display-box float-left right-pull-30">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can extend room occupancy. Select the rooms and click <u>extend</u> button
	</span>
	<span class="ln-display-box float-right top-pull-5">
		<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()">Extend</a> <a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 dark-black-white-state rounded-button ft-xsml-size default-text-font-bold left-push-10" onclick="window.print()">Print</a>
	</span>
	<span class="block-element new-line-space">
	</span>
</div>
<div class="pads30" align="left">

	<span class="block-element cs-width-350 bottom-push-20"><input type="text" name="searchbydate" id="searchbydate" placeholder="Search by checkout date?" onfocus="textodate(this.id)" oninput="jsd(this.value)"></span>

	<?php

		$new_ext = isset($_POST['ftask']) ? $_POST['ftask'] : "";
		$rows = isset($_POST['checkers']) ? $_POST['checkers'] : "";

		if(!empty($new_ext) && is_array($rows)) {

			$ext_rooms = ""; $room_prefix = ""; $room_number = "";
			$new_noofdays = ""; $cur_noofdays = ""; $isExt = 0;
			$actual_checkout_date = "";

			foreach($rows as $id) {
				
				$old_day  = idget_data($tbl,$id,'checkin_date');
				$cur_day  = idget_data($tbl,$id,'checkout_date');

				$isday = dayDiffs($cur_day,$new_ext);
				$total_days_length = dayDiffs($old_day,$new_ext);
			
				if(strtotime($new_ext) > strtotime($cur_day)) {
					
					#get booking number attach to this record
					$booking_number = idget_data($tbl,$id,'booking_number');
					
					#generate date in between the start and end date
					$weekdayBtwn = getWeekdays($cur_day,$new_ext,'nameweekday');
					$dateBtwn = getWeekdays($cur_day,$new_ext,'daterange');

					$actual_checkout_date = $new_ext;
					$ebill_date = date('Y-m-d',strtotime($new_ext.' -1 day'));

					#update check-out date in two tables
					$sql_uquery = array("booking_number"=>$booking_number);
					$sql_udata = array("checkout_date"=>$actual_checkout_date);
					mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

					#get recent no of days used
					$cur_noofdays = idget_data($tbL127,$id,'noofdays');
					//$new_noofdays = $cur_noofdays + $total_days_length;
					$new_noofdays = $total_days_length;

					$sql_uqueryx = array("id"=>$id);
					$sql_udatax = array("noofdays"=>$new_noofdays,"checkout_date"=>$actual_checkout_date);
					mysqli_data_update($tbL127,$sql_udatax,$sql_uqueryx);

					$additionalQuery = "";

					$maxday = ""; $queryset = ""; $getGst = ""; $getInv = ""; $wgt_room_type = ""; $wgt_room_rate = "";
					$wgt_tax = ""; $rrid = ""; $ccid = ""; $wgt_consumption = ""; $wgt_service_charge = ""; $chargeroom = "";
					$actual_room_amount = ""; $actual_tax_amount = ""; $actual_service_charge = "";
					$actual_consumption_tax_amount = ""; $iswkf = ""; $pst_wkf = ""; $nwk = "";

					#get roomid
					$rrid = idget_data($tbl,$id,'roomid');
					$ccid = idget_data($tbl,$id,'customerid');

					#update date of checkout of this room in housekeeping
					$hk_qry = array("roomid"=>$rrid);
					$sql_hk = array("endate"=>$actual_checkout_date);
					mysqli_data_update($tbL94,$sql_hk,$hk_qry);
					
					$room_prefix = idget_data($tbL56,$rrid,'roomprefix');
					$room_number = idget_data($tbL56,$rrid,'roomnumber');

					$ext_rooms .= $room_prefix.$room_number.",";

					#get room last valid day to replicate new days
					//$sqlset = "MAX(day)";
					//$queryset = "booking_number='{$booking_number}' AND roomid={$rrid} AND customerid={$ccid} AND wkf=0 AND room_status IN('CheckedIn') AND deletedata=0";
					//$maxday = mysqli_arithmetic_data($tbL134,$sqlset,$queryset);

					//check if weekend fare is applied
					$pst_wkf = idget_fdata($tbL130,'booking_number',$booking_number,'isweekend_fares');

					$additionalQuery = " ORDER BY id DESC LIMIT 1";
					$sql_uquery2x = array("booking_number"=>$booking_number,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0);
					$sql_data_select2x = mysqli_data_fetch($tbL134,'day,charge_type,billto,invoice_number,room_type_id',$sql_uquery2x,'noarray');
					$maxday = $sql_data_select2x[0];

					$datasets = "invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,wkf";

					$sql_uquery2 = array("booking_number"=>$booking_number,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0);

					$nwk = date('l',strtotime($ebill_date));
					
					if($pst_wkf == 'Yes' && ($nwk == 'Friday' || $nwk == 'Saturday' || $nwk == 'Sunday')) { $sql_uquery2['wkf'] = 7; }
					else { $sql_uquery2['wkf'] = 0; }

					$sql_data_select2 = mysqli_data_fetch($tbL134,$datasets,$sql_uquery2,'noarray');
					$additionalQuery = "";

					if($sql_data_select2[1] > 0) {
						$getInv = $sql_data_select2[0]; $wgt_room_type = $sql_data_select2[1];
						$getGst = $sql_data_select2[2]; $wgt_room_rate = $sql_data_select2[3];
						$wgt_discount = $sql_data_select2[4]; $wgt_tax = $sql_data_select2[5];
						$wgt_consumption = $sql_data_select2[6]; $wgt_service_charge = $sql_data_select2[7];
						$chargeroom = "yes"; $charge_type = $sql_data_select2[9];
						$billto = $sql_data_select2[10]; $actual_room_amount = $sql_data_select2[11];
						$actual_tax_amount = $sql_data_select2[12]; $actual_service_charge = $sql_data_select2[13];
						$actual_consumption_tax_amount = $sql_data_select2[14]; $iswkf = $sql_data_select2[15];
					} else {
						$getInv = $sql_data_select2x[3]; $wgt_room_type = $sql_data_select2x[4];
						$getGst = $ccid; $chargeroom = "yes"; $charge_type = $sql_data_select2x[1];
						$billto = $sql_data_select2x[2]; $iswkf = 0; $wgt_discount = 0;

						$wgt_room_rate = idget_data($tbL52,$wgt_room_type,'defaultprice');
						$actual_room_amount = $wgt_room_rate;

						$wgt_tax = ($gh_get_vat / 100) * $wgt_room_rate;
						$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_room_rate;
						$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_room_rate;
					
						$actual_tax_amount = $wgt_tax;
						$actual_consumption_tax_amount = $wgt_consumption;
						$actual_service_charge = $wgt_service_charge;
					}

					$total_room_amount = 0;
					$total_tax_amount = 0;
					$total_consumption_tax_amount = 0;
					$total_service_charge = 0;
					$total_discount_amount = 0;

					//set max day for increment
					$day_incr = $maxday;
					
					for($d=0; $d < $isday; $d++) {
						
						$day_incr += 1;
						
						$getWk = $weekdayBtwn[$d];
						$getDt = $dateBtwn[$d];

						$total_room_amount = $total_room_amount + $wgt_room_rate;
						$total_tax_amount = $total_tax_amount + $wgt_tax;
						$total_consumption_tax_amount = $total_consumption_tax_amount + $wgt_consumption;
						$total_service_charge = $total_service_charge + $wgt_service_charge;
						$total_discount_amount = $total_discount_amount + $wgt_discount;

						$daily_charge_query = array("booking_number"=>$booking_number,"roomid"=>$rrid,"bill_date"=>$getDt);
						$daily_sql = array("charge_type"=>$charge_type,"billto"=>$billto,"booking_number"=>$booking_number,"invoice_number"=>$getInv,"room_type_id"=>$wgt_room_type,"roomid"=>$rrid,"customerid"=>$getGst,"day"=>$day_incr,"weekday"=>strtolower($getWk),"actual_room_amount"=>$actual_room_amount,"actual_tax_amount"=>$actual_tax_amount,"actual_service_charge"=>$actual_service_charge,"actual_consumption_tax_amount"=>$actual_consumption_tax_amount,"room_amount"=>$wgt_room_rate,"discount_amount"=>$wgt_discount,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"charge"=>$chargeroom,"bill_date"=>$getDt,"status"=>"Pending","room_status"=>"CheckedIn","wkf"=>$iswkf,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);

						mysqli_data_insert($tbL134,$daily_sql,$daily_charge_query);
					}

					#check if this booking is for corporate or complimentary
					#then log billing transaction
					$wgt_bill_type = idget_fdata($tbL130,'booking_number',$booking_number,'bill_type');
					$wgt_bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');

					//if(isset($wgt_bill_type) && ($wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Complimentary')) {
					if(isset($wgt_bill_type) && $wgt_bill_type == 'Corporate') {
						$ths_amount = ($total_room_amount + $total_tax_amount + $total_consumption_tax_amount + $total_service_charge) - $total_discount_amount;
						
						/*$payment_sql = array("biller"=>$wgt_bill_to,"sales_point"=>"booking","sales_description"=>"payment made for lodging","booking_number"=>$booking_number,"invoice_number"=>$getInv,"customerid"=>$wgt_bill_to,"transaction_type"=>"debit","ispaid"=>0,"amount"=>$ths_amount,"payment_mode"=>0,"cheque_number"=>"","detail"=>"noremark","userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						mysqli_data_insert($tbL131,$payment_sql,'');

						#update receipt number
						$receipt_id = $mysqli_id; $receipt_number = $receipt_prefix.$receipt_id;
						$payment_sql = array("receipt_number"=>$receipt_number);
						$payment_query = array("id"=>$receipt_id);
						mysqli_data_update($tbL131,$payment_sql,$payment_query);*/

						if(!empty($wgt_bill_to) && $wgt_bill_to > 0) {

							$credit_limit = idget_data($tbL58,$wgt_bill_to,'creditlimit');
							$credit_notification_limit = idget_data($tbL58,$wgt_bill_to,'notifylimit');
							$new_creditlimit = $credit_limit - $ths_amount;

							$blc_selection_key = array("id"=>$wgt_bill_to);
							$crl_datasets = array("creditlimit"=>$new_creditlimit);
							mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

							$transaction_desc = "Room charged for extension";
							$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$wgt_bill_to,"transaction_number"=>$booking_number,"transaction_type"=>"Debit","amount"=>$ths_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
							mysqli_data_insert($tbL63,$ledger_dataproperty,'');
						}

						$receipt_id=""; $credit_limit=""; $credit_notification_limit="";
						$new_creditlimit="";
					}

					$wgt_bill_type=""; $wgt_bill_to="";
					
					$isExt += 1;
				}
			}

			if(isset($isExt) && $isExt >= 1) {

				$ext_rooms = substr_replace($ext_rooms,'',-1,1);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Selected roooms were extended successfully";
				
				$islogfile = 1;
				$logfile_msg = "Rooms were extended by this user";

				$isguestAct = 1;
				$pst_booking_number = "";
				$remark_tag = "extension"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
				$guestAct_msg = "Guest stay duration was extended for {$ext_rooms} till {$new_ext}";
			}
		}

	?>

	<div class="x-scroll">
		<div id="section-to-print" class="nc-width-100">
			<?php
				
				/*$query = "status='CheckedIn' AND deletedata=0";
				$data = pfetch('*',$tbl,$query);

				if(is_array($data) && count($data)) {
					foreach($data as $key => $val) {}
				}*/

				$search_date = isset($_GET['isdate']) ? $_GET['isdate'] : "";

				if(!empty($search_date)) { $queryset = "status='CheckedIn' AND checkout_date='{$search_date}' AND deletedata=0"; }
				else { $queryset = "status='CheckedIn' AND checkout_date='{$server_get_date}' AND deletedata=0"; }

				$force_tabs = array(
					"isfunc"=>array("tbl"=>"","key"=>"guestBillSummary","val"=>"booking_number","th"=>"balance amount")
				);

				//"noofdays"=>"duration (days)",
				//"checkin_time"=>"check-in time",
				//"checkout_time"=>"check-out time",
			
				$keys = array(
					"booking_number"=>"(fx)booking no.",
					"customerid"=>"guest name",
					"room_type_id"=>"room type",
					"roomid"=>"room no.",
					"checkin_date"=>"check-in",
					"checkout_date"=>"check-out",
					"checkin_byuser"=>"lodged by"
				);

				$format = array(
					"grid",
					"form-ctrl"
				);

				$datarow = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				echo $datarow;
			?>
		</div>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<script>

	function jsForm() {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		var vhtml;

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<h3 class="xlarge nobold default-text-font-bold">Choose the extension date to continue?</h3>';
		vhtml += '<input type="text" id="extensiondate" class="no-back-black" placeholder="Enter here" onfocus="textodate(this.id)">';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-15 motion">';
		vhtml += '<input type="submit" name="applybutton" value="Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}


	function jbtrigger(e) {
		e.preventDefault();
		if(document.getElementById('ftask')) {
			document.getElementById('ftask').value = document.getElementById('extensiondate').value;
			setTimeout(() => { document.getElementById('datasheet').submit(); },500);
		}
	}


	function jsd(date) {
		var ds;

		if(sessionStorage.getItem('uext') !== null && sessionStorage.getItem('uext') != 'undefined') {
			ds = sessionStorage.getItem('uext');
		} else {
			sessionStorage.setItem('uext',window.location.href);
			ds = window.location.href;
		}

		setTimeout(() => { window.location.href = ds+'&isdate='+date; },500);
	}


	function jsxView(key) {
		var numbr = Math.round((Math.random() * 10000000) - 1);
		crframe(key,numbr,'reservations');
	}

</script>