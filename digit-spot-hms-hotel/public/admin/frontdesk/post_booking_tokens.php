<?php

	//guest photo update
	if(isset($_POST['imagebutton']) && isset($_POST['dataurl']) && !empty($_POST['dataurl'])) {
		
		if(isset($_POST['wgtidx']) && $_POST['wgtidx'] >= 1) {
			
			$wgtf1 = $_POST['wgtidx'];
			$image_upload_link = "../../theme/images/general/guestphotos/";

			$encoded_data = str_replace(' ','+',$_POST['dataurl']);
			$binary_data = base64_decode($encoded_data);

			$img = "gp_".date('YmdHis');
			$newname = $image_upload_link.$img.".jpg";

			//for existing image, remove
			$isimage = idget_data($tbL102,$wgtf1,'photo');
			if(isset($isimage) && !empty($isimage)) { @ unlink($image_upload_link.$isimage); }
			
			file_put_contents($newname, $binary_data);
			$newimage = $img.".jpg";

			$photo_query = array("id"=>$_POST['wgtidx']);
			$image_arr = array("photo"=>$newimage);
			mysqli_data_update($tbL102,$image_arr,$photo_query);

			$islogfile = 1;
			$logfile_msg = "Guest passport photograph was added by this user";
			
			$isguestAct = 1;
			$pst_booking_number = idget_data($tbL102,$_POST['wgtidx'],'booking_number');
			$guestAct_msg = "Guest recently update his/her passport photograph";
		}
	}

	#---------------------------------------------------------------------------------------------------------------


	if(isset($_POST['submitbutton'])) {

		//for stay extension
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'extendstay') {
			
			$isday = dayDiffs($_POST['exstartdate'],$_POST['exendate']);
			
			if(strtotime($_POST['exendate']) > strtotime($_POST['exstartdate'])) {
				
				$charge_lib = array(
					"weekcharge"=>array(
						"charge_type"=>"","billto"=>"","booking_number"=>"","invoice_number"=>"","room_type_id"=>"","roomid"=>"","customerid"=>"","day"=>"","weekday"=>"","actual_room_amount"=>"","actual_tax_amount"=>"","actual_service_charge"=>"","actual_consumption_tax_amount"=>"","room_amount"=>"","discount_amount"=>"","tax_amount"=>"","consumption_tax_amount"=>"","service_charge"=>"","wkf"=>0
					),
					"weekendcharge"=>array(
						"charge_type"=>"","billto"=>"","booking_number"=>"","invoice_number"=>"","room_type_id"=>"","roomid"=>"","customerid"=>"","day"=>"","weekday"=>"","actual_room_amount"=>"","actual_tax_amount"=>"","actual_service_charge"=>"","actual_consumption_tax_amount"=>"","room_amount"=>"","discount_amount"=>"","tax_amount"=>"","consumption_tax_amount"=>"","service_charge"=>"","wkf"=>7
					),
					"wkf_days"=>array("Friday","Saturday","Sunday"),
					"nowkf_days"=>array("Monday","Tuesday","Wednesday","Thursday")
				);

				$wgt1 = $_POST['wgtfield1'];
				$weekdayBtwn = getWeekdays($_POST['exstartdate'],$_POST['exendate'],'nameweekday');
				$dateBtwn = getWeekdays($_POST['exstartdate'],$_POST['exendate'],'daterange');

				$actual_checkout_date = $_POST['exendate'];

				$sql_uquery = array("booking_number"=>$wgt1);
				$sql_udata = array("checkout_date"=>$actual_checkout_date);
				mysqli_data_update($tbL130,$sql_udata,$sql_uquery);
				
				$additionalQuery = " GROUP BY roomid,customerid";
				$sql_uquery = array("booking_number"=>$wgt1,"deletedata"=>0);

				if(isset($_POST['wgtagroom']) && !empty($_POST['wgtagroom']) && $_POST['wgtagroom'] >= 1) {
					$sql_uquery['roomid'] = $_POST['wgtagroom'];
				}

				$sql_data_select = mysqli_data_fetch($tbL134,'roomid,customerid',$sql_uquery,'array');

				$ext_rooms = "";

				if(is_array($sql_data_select)) {
					
					$additionalQuery = "";

					$maxday = ""; $rrid = ""; $ccid = ""; $pst_wkf = ""; $nwk = "";

					/*$maxday = ""; $rrid = ""; $ccid = ""; $getGst = ""; $getInv = ""; $wgt_room_type = ""; $wgt_room_rate = "";
					$wgt_tax = ""; $wgt_consumption = ""; $wgt_service_charge = ""; $chargeroom = "";
					$actual_room_amount = ""; $actual_tax_amount = ""; $actual_service_charge = "";
					$actual_consumption_tax_amount = ""; $nwk = ""; $iswkf = ""; $pst_wkf = "";*/

					$room_prefix = ""; $room_number = "";
					
					$wtotal_room_amount = 0;
					$wtotal_tax_amount = 0;
					$wtotal_consumption_tax_amount = 0;
					$wtotal_service_charge = 0;
					$wtotal_discount_amount = 0;
					
					foreach($sql_data_select as $key => $value) {
						
						$rrid = $value['roomid'];
						$ccid = $value['customerid'];

						$sql_rstatQr = array("booking_number"=>$wgt1,"roomid"=>$rrid,"customerid"=>$ccid,"status"=>"CheckedIn","deletedata"=>0);
						$isStatactive = mysqli_data_checkr($tbL127,'(*)',$sql_rstatQr);

						if($isStatactive == true) {

							$room_prefix = idget_data($tbL56,$rrid,'roomprefix');
							$room_number = idget_data($tbL56,$rrid,'roomnumber');

							$ext_rooms .= $room_prefix.$room_number.",";

							//check if weekend fare is applied
							$pst_wkf = idget_fdata($tbL130,'booking_number',$wgt1,'isweekend_fares');

							/*
							//get room last day information
							$additionalQuery = " ORDER BY id DESC LIMIT 1";
							$sql_uquery2x = array("booking_number"=>$wgt1,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0);
							$sql_data_select2x = mysqli_data_fetch($tbL134,'day,charge_type,billto,invoice_number,room_type_id',$sql_uquery2x,'noarray');
							$maxday = $sql_data_select2x[0];

							//get room data
							$datasets = "invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,wkf,id";
							$sql_uquery2 = array("booking_number"=>$wgt1,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0);

							$nwk = date('l',strtotime($server_get_date));
							if($pst_wkf == 'Yes' && ($nwk == 'Friday' || $nwk == 'Saturday' || $nwk == 'Sunday')) { $sql_uquery2['wkf'] = 7; }
							else { $sql_uquery2['wkf'] = 0; }

							$sql_data_select2 = mysqli_data_fetch($tbL134,$datasets,$sql_uquery2,'noarray');
							$additionalQuery = "";

							if($sql_data_select2[1] > 0) {
								$getInv = $sql_data_select2[0]; $wgt_room_type = $sql_data_select2[1];
								$getGst = $sql_data_select2[2]; $wgt_room_rate = $sql_data_select2[3];
								$wgt_discount = $sql_data_select2[4]; $wgt_tax = $sql_data_select2[5];
								$wgt_consumption = $sql_data_select2[6]; $wgt_service_charge = $sql_data_select2[7];
								$chargeroom = $sql_data_select2[8]; $charge_type = $sql_data_select2[9];
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
							}*/

							$additionalQuery = " ORDER BY id DESC LIMIT 1";
							
							$sql_query_x0 = array("booking_number"=>$wgt1,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0,"wkf"=>0);
							$sql_query_x7 = array("booking_number"=>$wgt1,"roomid"=>$rrid,"customerid"=>$ccid,"room_status"=>"CheckedIn","deletedata"=>0,"wkf"=>7);

							$datasets = "id,invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,day";

							$sql_data_select_x0 = mysqli_data_fetch($tbL134,$datasets,$sql_query_x0,'noarray');
							$sql_data_select_x7 = mysqli_data_fetch($tbL134,$datasets,$sql_query_x7,'noarray');
							
							if(!empty($sql_data_select_x0[0]) && $sql_data_select_x0[0] >= 1) {
								
								$charge_lib['weekcharge']['invoice_number'] = $sql_data_select_x0[1];
								$charge_lib['weekcharge']['room_type_id'] = $sql_data_select_x0[2];
								$charge_lib['weekcharge']['customerid'] = $sql_data_select_x0[3];
								$charge_lib['weekcharge']['room_amount'] = $sql_data_select_x0[4];
								$charge_lib['weekcharge']['discount_amount'] = $sql_data_select_x0[5];
								$charge_lib['weekcharge']['tax_amount'] = $sql_data_select_x0[6];
								$charge_lib['weekcharge']['consumption_tax_amount'] = $sql_data_select_x0[7];
								$charge_lib['weekcharge']['service_charge'] = $sql_data_select_x0[8];
								$charge_lib['weekcharge']['charge_type'] = $sql_data_select_x0[10];
								$charge_lib['weekcharge']['billto'] = $sql_data_select_x0[11];
								$charge_lib['weekcharge']['actual_room_amount'] = $sql_data_select_x0[12];
								$charge_lib['weekcharge']['actual_tax_amount'] = $sql_data_select_x0[13];
								$charge_lib['weekcharge']['actual_service_charge'] = $sql_data_select_x0[14];
								$charge_lib['weekcharge']['actual_consumption_tax_amount'] = $sql_data_select_x0[15];
								$charge_lib['weekcharge']['day'] = $sql_data_select_x0[16];
								
							} else {
								
								array_pop($sql_query_x0);

								$sql_data_select_x0 = mysqli_data_fetch($tbL134,$datasets,$sql_query_x0,'noarray');
								$wgt_room_rate = idget_data($tbL52,$sql_data_select_x0[2],'defaultprice');
								
								$wgt_tax = ($gh_get_vat / 100) * $wgt_room_rate;
								$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_room_rate;
								$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_room_rate;

								$charge_lib['weekcharge']['invoice_number'] = $sql_data_select_x0[1];
								$charge_lib['weekcharge']['room_type_id'] = $sql_data_select_x0[2];
								$charge_lib['weekcharge']['customerid'] = $sql_data_select_x0[3];
								$charge_lib['weekcharge']['room_amount'] = $wgt_room_rate;
								$charge_lib['weekcharge']['discount_amount'] = 0;
								$charge_lib['weekcharge']['tax_amount'] = $wgt_tax;
								$charge_lib['weekcharge']['consumption_tax_amount'] = $wgt_consumption;
								$charge_lib['weekcharge']['service_charge'] = $wgt_service_charge;
								$charge_lib['weekcharge']['charge_type'] = $sql_data_select_x0[10];
								$charge_lib['weekcharge']['billto'] = $sql_data_select_x0[11];
								$charge_lib['weekcharge']['actual_room_amount'] = $wgt_room_rate;
								$charge_lib['weekcharge']['actual_tax_amount'] = $wgt_tax;
								$charge_lib['weekcharge']['actual_service_charge'] = $wgt_service_charge;
								$charge_lib['weekcharge']['actual_consumption_tax_amount'] = $wgt_consumption;
								$charge_lib['weekcharge']['day'] = $sql_data_select_x0[16];
							}


							if(!empty($sql_data_select_x7[0]) && $sql_data_select_x7[0] >= 1) {
								
								$charge_lib['weekendcharge']['invoice_number'] = $sql_data_select_x7[1];
								$charge_lib['weekendcharge']['room_type_id'] = $sql_data_select_x7[2];
								$charge_lib['weekendcharge']['customerid'] = $sql_data_select_x7[3];
								$charge_lib['weekendcharge']['room_amount'] = $sql_data_select_x7[4];
								$charge_lib['weekendcharge']['discount_amount'] = $sql_data_select_x7[5];
								$charge_lib['weekendcharge']['tax_amount'] = $sql_data_select_x7[6];
								$charge_lib['weekendcharge']['consumption_tax_amount'] = $sql_data_select_x7[7];
								$charge_lib['weekendcharge']['service_charge'] = $sql_data_select_x7[8];
								$charge_lib['weekendcharge']['charge_type'] = $sql_data_select_x7[10];
								$charge_lib['weekendcharge']['billto'] = $sql_data_select_x7[11];
								$charge_lib['weekendcharge']['actual_room_amount'] = $sql_data_select_x7[12];
								$charge_lib['weekendcharge']['actual_tax_amount'] = $sql_data_select_x7[13];
								$charge_lib['weekendcharge']['actual_service_charge'] = $sql_data_select_x7[14];
								$charge_lib['weekendcharge']['actual_consumption_tax_amount'] = $sql_data_select_x7[15];
								$charge_lib['weekendcharge']['day'] = $sql_data_select_x7[16];

							} else {
								
								$charge_lib['weekendcharge']['invoice_number'] = $sql_data_select_x0[1];
								$charge_lib['weekendcharge']['room_type_id'] = $sql_data_select_x0[2];
								$charge_lib['weekendcharge']['customerid'] = $sql_data_select_x0[3];
								$charge_lib['weekendcharge']['room_amount'] = $sql_data_select_x0[4];
								$charge_lib['weekendcharge']['discount_amount'] = $sql_data_select_x0[5];
								$charge_lib['weekendcharge']['tax_amount'] = $sql_data_select_x0[6];
								$charge_lib['weekendcharge']['consumption_tax_amount'] = $sql_data_select_x0[7];
								$charge_lib['weekendcharge']['service_charge'] = $sql_data_select_x0[8];
								$charge_lib['weekendcharge']['charge_type'] = $sql_data_select_x0[10];
								$charge_lib['weekendcharge']['billto'] = $sql_data_select_x0[11];
								$charge_lib['weekendcharge']['actual_room_amount'] = $sql_data_select_x0[12];
								$charge_lib['weekendcharge']['actual_tax_amount'] = $sql_data_select_x0[13];
								$charge_lib['weekendcharge']['actual_service_charge'] = $sql_data_select_x0[14];
								$charge_lib['weekendcharge']['actual_consumption_tax_amount'] = $sql_data_select_x0[15];
								$charge_lib['weekendcharge']['day'] = $sql_data_select_x0[16];
								$charge_lib['weekendcharge']['wkf'] = 0;
							}

							$additionalQuery = "";

							$total_room_amount = 0;
							$total_tax_amount = 0;
							$total_consumption_tax_amount = 0;
							$total_service_charge = 0;
							$total_discount_amount = 0;

							//set max day for increment
							$maxday = (!empty($charge_lib['weekcharge']['day']) && $charge_lib['weekcharge']['day'] > $charge_lib['weekendcharge']['day']) ? $charge_lib['weekcharge']['day'] : $charge_lib['weekendcharge']['day'];

							$getGst = ""; $getInv = ""; $wgt_room_type = ""; $wgt_room_rate = "";
							$wgt_tax = ""; $wgt_consumption = ""; $wgt_service_charge = ""; $chargeroom = "";
							$actual_room_amount = ""; $actual_tax_amount = ""; $actual_service_charge = "";
							$actual_consumption_tax_amount = ""; $iswkf = "";

							$day_incr = $maxday; $noofoccurrence = 0;
							
							for($d=0; $d < $isday; $d++) {
								
								$day_incr += 1;
								
								$getWk = $weekdayBtwn[$d];
								$getDt = $dateBtwn[$d];

								if(in_array($getWk, $charge_lib['wkf_days'])) {
									
									$charge_type = $charge_lib['weekendcharge']['charge_type'];
									$billto = $charge_lib['weekendcharge']['billto'];
									$wgt_room_type = $charge_lib['weekendcharge']['room_type_id'];
									$getInv = $charge_lib['weekendcharge']['invoice_number'];
									$getGst = $charge_lib['weekendcharge']['customerid'];
									
									$wgt_room_rate = $charge_lib['weekendcharge']['room_amount'];
									$wgt_tax = $charge_lib['weekendcharge']['tax_amount'];
									$wgt_consumption = $charge_lib['weekendcharge']['consumption_tax_amount'];
									$wgt_service_charge = $charge_lib['weekendcharge']['service_charge'];
									$wgt_discount = $charge_lib['weekendcharge']['discount_amount'];
									
									$actual_room_amount = $charge_lib['weekendcharge']['actual_room_amount'];
									$actual_tax_amount = $charge_lib['weekendcharge']['actual_tax_amount'];
									$actual_consumption_tax_amount = $charge_lib['weekendcharge']['actual_consumption_tax_amount'];
									$actual_service_charge = $charge_lib['weekendcharge']['actual_service_charge'];

									$iswkf = $charge_lib['weekendcharge']['wkf'];

								} elseif(in_array($getWk, $charge_lib['nowkf_days'])) {

									$charge_type = $charge_lib['weekcharge']['charge_type'];
									$billto = $charge_lib['weekcharge']['billto'];
									$wgt_room_type = $charge_lib['weekcharge']['room_type_id'];
									$getInv = $charge_lib['weekcharge']['invoice_number'];
									$getGst = $charge_lib['weekcharge']['customerid'];
									
									$wgt_room_rate = $charge_lib['weekcharge']['room_amount'];
									$wgt_tax = $charge_lib['weekcharge']['tax_amount'];
									$wgt_consumption = $charge_lib['weekcharge']['consumption_tax_amount'];
									$wgt_service_charge = $charge_lib['weekcharge']['service_charge'];
									$wgt_discount = $charge_lib['weekcharge']['discount_amount'];
									
									$actual_room_amount = $charge_lib['weekcharge']['actual_room_amount'];
									$actual_tax_amount = $charge_lib['weekcharge']['actual_tax_amount'];
									$actual_consumption_tax_amount = $charge_lib['weekcharge']['actual_consumption_tax_amount'];
									$actual_service_charge = $charge_lib['weekcharge']['actual_service_charge'];

									$iswkf = $charge_lib['weekcharge']['wkf'];
								}

								$total_room_amount = $total_room_amount + $wgt_room_rate;
								$total_tax_amount = $total_tax_amount + $wgt_tax;
								$total_consumption_tax_amount = $total_consumption_tax_amount + $wgt_consumption;
								$total_service_charge = $total_service_charge + $wgt_service_charge;
								$total_discount_amount = $total_discount_amount + $wgt_discount;

								$daily_charge_query = array("booking_number"=>$wgt1,"roomid"=>$rrid,"bill_date"=>$getDt);
								$daily_sql = array("charge_type"=>$charge_type,"billto"=>$billto,"booking_number"=>$wgt1,"invoice_number"=>$getInv,"room_type_id"=>$wgt_room_type,"roomid"=>$rrid,"customerid"=>$getGst,"day"=>$day_incr,"weekday"=>strtolower($getWk),"actual_room_amount"=>$actual_room_amount,"actual_tax_amount"=>$actual_tax_amount,"actual_service_charge"=>$actual_service_charge,"actual_consumption_tax_amount"=>$actual_consumption_tax_amount,"room_amount"=>$wgt_room_rate,"discount_amount"=>$wgt_discount,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"charge"=>"yes","bill_date"=>$getDt,"status"=>"Pending","room_status"=>"CheckedIn","wkf"=>$iswkf,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
								mysqli_data_insert($tbL134,$daily_sql,$daily_charge_query);

								$noofoccurrence += 1;
							}

							$sql_uqueryx = array("booking_number"=>$wgt1,"roomid"=>$rrid,"deletedata"=>0);
							$gdt = mysqli_data_fetch($tbL127,'noofdays',$sql_uqueryx,'noarray');
							$new_noofdays = $gdt[0] + $noofoccurrence;

							$sql_udata = array("noofdays"=>$new_noofdays,"checkout_date"=>$actual_checkout_date);
							mysqli_data_update($tbL127,$sql_udata,$sql_uqueryx);

							$hk_qry = array("roomid"=>$rrid);
							$sql_hk = array("endate"=>$actual_checkout_date);
							mysqli_data_update($tbL94,$sql_hk,$hk_qry);

							$new_noofdays = "";

							$wtotal_room_amount = $wtotal_room_amount + $total_room_amount;
							$wtotal_tax_amount = $wtotal_tax_amount + $total_tax_amount;
							$wtotal_consumption_tax_amount = $wtotal_consumption_tax_amount + $total_consumption_tax_amount;
							$wtotal_service_charge = $wtotal_service_charge + $total_service_charge;
							$wtotal_discount_amount = $wtotal_discount_amount + $total_discount_amount;
						}
					}

					//check if this booking is for corporate or complimentary
					//then log billing transaction
					$wgt_bill_type = idget_fdata($tbL130,'booking_number',$wgt1,'bill_type');
					$wgt_bill_to = idget_fdata($tbL130,'booking_number',$wgt1,'bill_to');

					//if(isset($wgt_bill_type) && ($wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Complimentary')) {
					if(isset($wgt_bill_type) && $wgt_bill_type == 'Corporate') {
						$ths_amount = ($wtotal_room_amount + $wtotal_tax_amount + $wtotal_consumption_tax_amount + $wtotal_service_charge) - $wtotal_discount_amount;

						if(!empty($wgt_bill_to) && $wgt_bill_to > 0) {
							
							$credit_limit = idget_data($tbL58,$wgt_bill_to,'creditlimit');
							$credit_notification_limit = idget_data($tbL58,$wgt_bill_to,'notifylimit');
							$new_creditlimit = $credit_limit - $ths_amount;

							$blc_selection_key = array("id"=>$wgt_bill_to);
							$crl_datasets = array("creditlimit"=>$new_creditlimit);
							mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

							$transaction_desc = "Room charged for extension";
							$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$wgt_bill_to,"transaction_number"=>$wgt1,"transaction_type"=>"Debit","amount"=>$ths_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
							mysqli_data_insert($tbL63,$ledger_dataproperty,'');
						}
						
						/*$payment_sql = array("biller"=>$wgt_bill_to,"sales_point"=>"booking","sales_description"=>"payment made for lodging","booking_number"=>$wgt1,"invoice_number"=>$getInv,"customerid"=>$wgt_bill_to,"transaction_type"=>"debit","ispaid"=>0,"amount"=>$ths_amount,"payment_mode"=>0,"cheque_number"=>"","detail"=>"noremark","userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						mysqli_data_insert($tbL131,$payment_sql,'');

						#update receipt number
						$receipt_id = $mysqli_id; $receipt_number = $receipt_prefix.$receipt_id;
						$payment_sql = array("receipt_number"=>$receipt_number);
						$payment_query = array("id"=>$receipt_id);
						mysqli_data_update($tbL131,$payment_sql,$payment_query);*/
					}
				}

				$ext_rooms = substr_replace($ext_rooms,'',-1,1);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Guest stay has been extended successfully";
				
				$islogfile = 1;
				$logfile_msg = "Guest stay was extended by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $wgt1;
				$remark_tag = "extension"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
				$guestAct_msg = "Guest stay duration with booking number {$wgt1} was extended for {$ext_rooms} from ".$_POST['exstartdate']." till ".$_POST['exendate'];

			} else {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Extension could not be applied. Please check your date selection";
			}
		}

		//for guest detail
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'guestdetail') {
			
			$wgt1 = $_POST['wgtfield1'];
			$wgt2 = escape_data($_POST['wgtfield2']);
			$wgt3 = escape_data($_POST['wgtfield3']);
			$wgt4 = escape_data($_POST['wgtfield4']);
			$wgt5 = escape_data($_POST['wgtfield5']);
			$wgt6 = escape_data($_POST['wgtfield6']);
			$wgt7 = escape_data($_POST['wgtfield7']);
			$wgt8 = escape_data($_POST['wgtfield8']);
			$wgt9 = escape_data($_POST['wgtfield9']);
			$wgt10 = escape_data($_POST['wgtfield10']);
			$wgt11 = escape_data($_POST['wgtfield11']);
			$wgt12 = escape_data($_POST['wgtfield12']);
			$wgt13 = escape_data($_POST['wgtfield13']);
			$wgt14 = escape_data($_POST['wgtfield14']);

			$wgt2a = escape_data($_POST['wgtfield2a']);
			$wgt3a = escape_data($_POST['wgtfield3a']);
			$wgt4a = escape_data($_POST['wgtfield4a']);
			$wgt5a = escape_data($_POST['wgtfield5a']);
			$wgt6a = escape_data($_POST['wgtfield6a']);
			$wgt4b = escape_data($_POST['wgtfield4b']);
			$wgt7a = escape_data($_POST['wgtfield7a']);
			$wgt8a = escape_data($_POST['wgtfield8a']);
			$wgt9a = escape_data($_POST['wgtfield9a']);
			$wgt9b = escape_data($_POST['wgtfield9b']);
			$wgt10a = escape_data($_POST['wgtfield10a']);
			$wgt11a = escape_data($_POST['wgtfield11a']);
			$wgt11b = escape_data($_POST['wgtfield11b']);
			$wgt12a = escape_data($_POST['wgtfield12a']);
			$wgt13a = escape_data($_POST['wgtfield13a']);
			$wgt14a = escape_data($_POST['wgtfield14a']);
			$wgt15a = escape_data($_POST['wgtfield15a']);
			$wgt16a = escape_data($_POST['wgtfield16a']);
			$wgt17a = escape_data($_POST['wgtfield17a']);
			$wgt18a = escape_data($_POST['wgtfield18a']);
			
			$wgt15 = $_POST['wgtfield15'];
			$wgt16 = $_POST['wgtfield16'];

			$pst_guest_query = array("id"=>$wgt15);
			$pst_guest_dataproperty = array("salutation"=>$wgt1,"fname"=>ucwords(strtolower($wgt2)),"lname"=>ucwords(strtolower($wgt3)),"mobile"=>$wgt4,"emailaddress"=>$wgt5,"remarks"=>$wgt14,"address"=>$wgt10,"city"=>$wgt11,"state"=>$wgt12,"country"=>$wgt13,"means_of_identification"=>$wgt6,"identification_number"=>$wgt7,"occupation"=>$wgt8,"period_of_stay"=>$wgt9,"gender"=>$wgt2a,"dob"=>$wgt3a,"pob"=>$wgt5a,"age"=>$wgt4a,"nationality"=>$wgt5a,"immi_status"=>$wgt9b,"allien_regno"=>$wgt12a,"employer"=>$wgt6a,"phoneno"=>$wgt4b,"zip_code"=>$wgt11b,"country_date_checkin"=>$wgt9a,"next_destination"=>$wgt10a,"id_issue_date"=>$wgt7a,"id_issue_place"=>$wgt8a,"current_address"=>$wgt11a,"probable_destination"=>$wgt13a,"passport_no"=>$wgt14a,"issue_date"=>$wgt15a,"expiry_date"=>$wgt16a,"issue_place"=>$wgt17a,"visa_validity"=>$wgt18a);
			$isupdate = mysqli_data_update($tbL102,$pst_guest_dataproperty,$pst_guest_query);

			if(isset($isupdate) && $isupdate == 2) {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Guest detail has been updated successfully. Please use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest detail was updated by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $wgt16;
				$ths_guest_pry = $wgt15;
				$guestAct_msg = "Guest (".ucwords(strtolower($wgt2))." ".ucwords(strtolower($wgt3))." - ".$wgt4.") detail with code: P".$wgt15." is changed as at ".$server_get_date." ".$server_get_time;
			}
		}


		//for guest bill-to-room status
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'billtoroom') {
			
			$wgt1 = $_POST['wgtfield2'];
			
			$chklastchange = idget_fdata($tbL130,'booking_number',$wgt1,'isbill_to_room');
			if($chklastchange == 'Yes') { $newchange = "No"; }
			elseif($chklastchange == 'No') { $newchange = "Yes"; }

			$sql_uquery = array("booking_number"=>$wgt1);
			$sql_udata = array("isbill_to_room"=>$newchange);
			$isupdate = mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

			if(isset($isupdate) && $isupdate == 2) {

				$sql_uquery = array("booking_number"=>$wgt1,"primary_guest"=>1);
				$sql_udata = array("isbill_to_room"=>$newchange);
				$isupdate = mysqli_data_update($tbL102,$sql_udata,$sql_uquery);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Bill-to-room status was changed successfully";
				
				$islogfile = 1;
				$logfile_msg = "Bill-to-room status for booking number (".$wgt1.") was changed by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $wgt1;
				$guestAct_msg = "Bill-to-room status of this guest was changed as at ".$server_get_date." ".$server_get_time;
			}
		}


		//for guest bill-to-room add service
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'billtoroomservice') {
			
			$wgt1 = $_POST['wgtfield3'];
			$chkexistService = idget_fdata($tbL130,'booking_number',$wgt1,'billing_services');
			
			if(isset($_POST['outlets'])) {
				
				$new_bs=""; $otl=""; foreach($_POST['outlets'] as $bs) {
					$new_bs .= $bs.',';
					if(is_numeric($bs)) { $otl .= idget_data($tbL14,$bs,'posname').','; }
				}

				$ths_new_bs = substr_replace($new_bs,'',-1,1);
				$otls = substr_replace($otl,'',-1,1);
				
				if(!empty($chkexistService) && $chkexistService != '') { $guest_bs = $chkexistService.','.$ths_new_bs; }
				else { $guest_bs = $ths_new_bs; }

				$nomain = 0;
				
				//$sql_uquery = array("booking_number"=>$wgt1,"primary_guest"=>1);
				$sql_uquery = array("booking_number"=>$wgt1);
				if(isset($_POST['wgtagcustomer']) && $_POST['wgtagcustomer'] >= 1) { $sql_uquery['id'] = $_POST['wgtagcustomer']; }
				$sql_udata = array("isbill_to_room"=>"Yes","billing_services"=>$guest_bs);
				$isupdate = mysqli_data_update($tbL102,$sql_udata,$sql_uquery);

				if(isset($isupdate) && $isupdate == 2) {

					$sql_uquery = array("booking_number"=>$wgt1);
					$sql_udata = array("billing_services"=>$guest_bs);
					$isupdate = mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

					$saynotify = 1;
					$notifytype = 2;
					
					$post_header = "Notification";
					$post_message = "Bill-to-room service was added successfully";
					
					$islogfile = 1;
					$logfile_msg = "Bill-to-room service for booking number (".$wgt1.") was added by this user";
					
					$isguestAct = 1;
					$pst_booking_number = $wgt1;
					$guestAct_msg = "The following outlet(s) ({$otls}) is allowed to be charged to room for booking number ({$wgt1}) as at ".$server_get_date." ".$server_get_time;
				}
			}
		}


		//for guest arrival and departure
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'guestarrivaldeparture') {
			
			$wgt1 = $_POST['wgtfield1'];
			$wgt2 = $_POST['wgtfield2'];
			$wgt3 = $_POST['wgtfield3'];
			$wgt4 = escape_data($_POST['wgtfield4']);

			$wgt5 = $_POST['wgtfield5'];

			$pst_transit_query = array("booking_number"=>$wgt5);
			$pst_transit_dataproperty = array("source_of_biz"=>$wgt1,"arrival_mode"=>$wgt2,"departure_mode"=>$wgt3,"remarks"=>$wgt4);
			$isupdate = mysqli_data_update($tbL128,$pst_transit_dataproperty,$pst_transit_query);

			if(isset($isupdate) && $isupdate == 2) {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Changes was made to guest arrival and departure mode. Please use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest arrival and departure mode for booking number (".$wgt5.") was changed by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $wgt1;
				$guestAct_msg = "Guest supplied information for his/her arrival and departure mode";
			}
		}


		//for guest room remark
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'roomremark') {
			
			$wgt1 = escape_data($_POST['wgtfield1']);
			$wgt2 = $_POST['wgtfield2'];
			$wgt3 = $_POST['wgtfield3'];

			$pst_query = array("booking_number"=>$wgt3,"roomid"=>$wgt2);
			$pst_dataproperty = array("remarks"=>$wgt1);
			$isupdate = mysqli_data_update($tbL127,$pst_dataproperty,$pst_query);

			if(isset($isupdate) && $isupdate == 2) {

				$room_prefix = idget_data($tbL56,$wgt2,'roomprefix');
				$room_number = idget_data($tbL56,$wgt2,'roomnumber');

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Room remark was updated successfully. Please use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest room remark for room number (".$room_prefix.$room_number.") and the booking number (".$wgt3.") was updated by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $wgt1;
				$guestAct_msg = "Guest room remark for room number (".$room_prefix.$room_number.") and the booking number (".$wgt3.") updated";
			}
		}


		//for day booking transaction
		if(isset($_POST['wgtag']) && $_POST['wgtag'] == 'removedaybooking') {
			
			$days = $_POST['days'];
			$query = "";
			
			foreach($days as $eachDay) {
				$query = array("id"=>$eachDay);
				trash_record($tbL134,$query);
			}

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Selected day-booking transaction removed from bill";

			$islogfile = 1;
			$logfile_msg = "Room day-booking information was deleted by this user";
		}
	}


	#---------------------------------------------------------------------------------------------------------------

	#for room processes only
	
	if(isset($_POST['savechangesbutton']) && isset($_POST['checkers'])) {
		
		$checkers = $_POST['checkers'];

		$booking_number = idget_data($tbL127,$checkers,'booking_number');
		$booking_status = idget_data($tbL127,$checkers,'status');
		$get_cur_room_type = idget_data($tbL127,$checkers,'room_type_id');
		$get_customerid = idget_data($tbL127,$checkers,'customerid');

		if($booking_status == 'CheckedIn') {

			$wgt_pst_occupancytype = $_POST["occupancytype$checkers"];
			$get_roomid = idget_data($tbL127,$checkers,'roomid');

			if(!empty($wgt_pst_occupancytype) && $wgt_pst_occupancytype > 0) {
				
				$sql_uquery = array("id"=>$checkers);
				$sql_ur = array("occupancy_type"=>$wgt_pst_occupancytype);
				mysqli_data_update($tbL127,$sql_ur,$sql_uquery);

				$oyp_query = array("room_type_id"=>$get_cur_room_type,"occupancy_type_id"=>$wgt_pst_occupancytype,"deletedata"=>0);
				$oyp_data = mysqli_data_fetch($tbL54,'price',$oyp_query,'noarray');
				$occupancy_charges = $oyp_data[0];

				$datasets = "invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,room_status,roomid";

				$additionalQuery = " ORDER BY id DESC LIMIT 1";
				$in_query = array("booking_number"=>$booking_number,"roomid"=>$get_roomid,"customerid"=>$get_customerid,"ischarged"=>0,"wkf"=>0,"deletedata"=>0);
				$in_data = mysqli_data_fetch($tbL134,$datasets,$in_query,'noarray');

				if($wgt_pst_occupancytype == 1) { $nw_rack_rate = $occupancy_charges; }
				else { $nw_rack_rate = $in_data[3] + $occupancy_charges; }

				if(!empty($in_data[5]) && $in_data[5] > 0) { $nw_tax = ($gh_get_vat / 100) * $nw_rack_rate; }
				else { $nw_tax = $in_data[5]; }

				if(!empty($in_data[6]) && $in_data[6] > 0) { $nw_service_charge = ($gh_get_service_charge / 100) * $nw_rack_rate; }
				else { $nw_service_charge = $in_data[6]; }

				if(!empty($in_data[7]) && $in_data[7] > 0) { $nw_consumption = ($gh_get_consumption_tax / 100) * $nw_rack_rate; }
				else { $nw_consumption = $in_data[7]; }

				$sql_udata = array();

				$sql_udata['room_amount'] = $nw_rack_rate;
				$sql_udata['tax_amount'] = $nw_tax;
				$sql_udata['service_charge'] = $nw_service_charge;
				$sql_udata['consumption_tax_amount'] = $nw_consumption;
				$sql_udata['occupancy_charges'] = $occupancy_charges;

				$additionalQuery = "";
				mysqli_data_update($tbL134,$sql_udata,$in_query);

				$room_prefix = idget_data($tbL56,$get_roomid,'roomprefix');
				$room_number = idget_data($tbL56,$get_roomid,'roomnumber');
				$occupany_name = idget_data($tbL51,$wgt_pst_occupancytype,'name');

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Changes were made to selected room successfully";
				
				$islogfile = 1;
				$logfile_msg = "Guest room (".$room_prefix.$room_number.") occupancy type of booking number (".$booking_number.") changed to ".$occupany_name;
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$guestAct_msg = "Guest room (".$room_prefix.$room_number.") occupancy type of booking number (".$booking_number.") changed to ".$occupany_name;
			}

		} elseif($booking_status == 'Reserved') {

			$wgt_pst_roomtype = $_POST["roomtype$checkers"];
			$wgt_pst_adult = $_POST["adults$checkers"];
			$wgt_pst_child = $_POST["childs$checkers"];
			$wgt_pst_occupancytype = $_POST["occupancytype$checkers"];

			$checkin = $_POST["checkin$checkers"];
			$checkout = $_POST["checkout$checkers"];
			$weekoftheday = date('l',strtotime($checkin));
			$weekoftheday = strtolower($weekoftheday);

			$room_type_id = $wgt_pst_roomtype;
			
			#-- for occupancy charges

			if(!empty($wgt_pst_occupancytype) && $wgt_pst_occupancytype > 1) {
				$oyp_query = array("room_type_id"=>$room_type_id,"occupancy_type_id"=>$wgt_pst_occupancytype,"deletedata"=>0);
				$oyp_data = mysqli_data_fetch($tbL54,'price',$oyp_query,'noarray');
				$occupancy_charges = $oyp_data[0];
			} else {
				$occupancy_charges = 0;
			}

			#--change day, week and bill date of this customer

			/*$sqlset = "MAX(day)";
			$queryset = "booking_number='{$booking_number}' AND customerid={$get_customerid} AND deletedata=0";
			$maxday = mysqli_arithmetic_data($tbL134,$sqlset,$queryset);*/

			$isday = dayDiffs($checkout,$checkin);

			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$queryset = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"wkf"=>0,"deletedata"=>0);
			$sqlset = mysqli_data_fetch($tbL134,'day',$queryset,'noarray');
			$maxday = $sqlset[0];

			$additionalQuery = "";
			
			$datasets = "invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,room_status,roomid";

			$in_query = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$maxday,"wkf"=>0,"deletedata"=>0);
			$in_data = mysqli_data_fetch($tbL134,$datasets,$in_query,'noarray');

			if(isset($_POST["roomnumber$checkers"]) && !empty($_POST["roomnumber$checkers"]) && $_POST["roomnumber$checkers"] > 0) {
				$push_new_room = $_POST["roomnumber$checkers"];
				if(!empty($in_data[16]) && $in_data[16] > 0 && $in_data[16] != $push_new_room) {
					
					#change old room status
					$ohk_query = array("roomid"=>$in_data[16]);
					$ohk_sql = array("housekeeping_stateid"=>1,"room_status_id"=>1,"remarks"=>"room status changed upon swapping in reservation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_update($tbL94,$ohk_sql,$ohk_query);

					#change new room status
					$nhk_query = array("roomid"=>$push_new_room);
					$nhk_sql = array("housekeeping_stateid"=>4,"room_status_id"=>6,"remarks"=>"room status changed upon swapping in reservation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_update($tbL94,$nhk_sql,$nhk_query);
				} else {
					#change new room status
					$nhk_query = array("roomid"=>$push_new_room);
					$nhk_sql = array("housekeeping_stateid"=>4,"room_status_id"=>6,"remarks"=>"room status changed upon swapping in reservation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_update($tbL94,$nhk_sql,$nhk_query);
				}
			} else {
				if(!empty($in_data[16]) && $in_data[16] > 0) {
					
					#change old room status
					$ohk_query = array("roomid"=>$in_data[16]);
					$ohk_sql = array("housekeeping_stateid"=>1,"room_status_id"=>1,"remarks"=>"room status changed upon changes in reservation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_update($tbL94,$ohk_sql,$ohk_query);

					$push_new_room = 0;
				} else {
					$push_new_room = $in_data[16];
				}
			}


			$nw_rack_rate = ($in_data[3] - $in_data[4]) + $occupancy_charges;

			if(!empty($in_data[5]) && $in_data[5] > 0) { $nw_tax = ($gh_get_vat / 100) * $nw_rack_rate; }
			else { $nw_tax = $in_data[5]; }

			if(!empty($in_data[6]) && $in_data[6] > 0) { $nw_service_charge = ($gh_get_service_charge / 100) * $nw_rack_rate; }
			else { $nw_service_charge = $in_data[6]; }

			if(!empty($in_data[7]) && $in_data[7] > 0) { $nw_consumption = ($gh_get_consumption_tax / 100) * $nw_rack_rate; }
			else { $nw_consumption = $in_data[7]; }

			$weekdayBtwn = getWeekdays($checkin,$checkout,'nameweekday');
			$dateBtwn = getWeekdays($checkin,$checkout,'daterange');

			$day_incr = 0;

			for($d=0; $d < $isday; $d++) {
				$day_incr += 1;
				$getWk = $weekdayBtwn[$d];
				$getDt = $dateBtwn[$d];
				
				$pst_query = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$day_incr,"wkf"=>0,"deletedata"=>0);
				$chk_entry = mysqli_data_checkr($tbL134,'id',$pst_query);
				
				if($chk_entry == true) {
					$pst_query['ischarged'] = 0;
					$pst_field = array("roomid"=>$push_new_room,"bill_date"=>$getDt,"weekday"=>strtolower($getWk),"room_amount"=>$nw_rack_rate,"tax_amount"=>$nw_tax,"consumption_tax_amount"=>$nw_consumption,"service_charge"=>$nw_service_charge);
					mysqli_data_update($tbL134,$pst_field,$pst_query);
				} else {
					$pst_field = array("charge_type"=>$in_data[9],"billto"=>$in_data[10],"booking_number"=>$booking_number,"invoice_number"=>$in_data[0],"room_type_id"=>$wgt_pst_roomtype,"roomid"=>$push_new_room,"customerid"=>$get_customerid,"day"=>$day_incr,"weekday"=>strtolower($getWk),"actual_room_amount"=>$in_data[11],"actual_tax_amount"=>$in_data[12],"actual_service_charge"=>$in_data[13],"actual_consumption_tax_amount"=>$in_data[14],"room_amount"=>$nw_rack_rate,"tax_amount"=>$nw_tax,"consumption_tax_amount"=>$nw_consumption,"service_charge"=>$nw_service_charge,"discount_amount"=>$in_data[4],"charge"=>$in_data[8],"bill_date"=>$getDt,"status"=>"Pending","room_status"=>$in_data[15],"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL134,$pst_field,$pst_query);
				}
			}

			if($maxday > $isday) {
				for($d=$maxday; $d > $isday; $d--) {
					$constrain = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$d,"wkf"=>0,"deletedata"=>0);
					trash_record($tbL134,$constrain);
				}
			}

			#--end

			$sql_udata = array("room_type_id"=>$wgt_pst_roomtype,"adult"=>$wgt_pst_adult,"child"=>$wgt_pst_child,"occupancy_type"=>$wgt_pst_occupancytype,"checkin_date"=>$checkin,"checkin_time"=>$server_get_time,"checkout_date"=>$checkout,"checkout_time"=>$wgt_checkout_time,"noofdays"=>$isday);
			
			if(isset($_POST["roomnumber$checkers"]) && !empty($_POST["roomnumber$checkers"]) && $_POST["roomnumber$checkers"] > 0) {
				$roomnumber = $_POST["roomnumber$checkers"];
				$sql_udata['roomid'] = $roomnumber;
			} else {
				//$roomnumber = idget_data($tbL127,$checkers,'roomid');
				$roomnumber = $push_new_room;
				$sql_udata['roomid'] = $roomnumber;
			}
			
			$sql_uquery = array("id"=>$checkers);
			mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$booking_number);
			$sql_udata = array("checkin_date"=>$checkin,"checkin_time"=>$server_get_time,"checkout_date"=>$checkout,"checkout_time"=>$wgt_checkout_time);
			mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

			//get charges for occupancy type if available
			$ocquery = array("room_type_id"=>$room_type_id,"occupancy_type_id"=>$wgt_pst_occupancytype,"deletedata"=>0);
			$ocdata = mysqli_data_fetch($tbL54,'price',$ocquery,'noarray');
			
			$sql_uquery = array("booking_number"=>$booking_number,"roomid"=>$push_new_room,"customerid"=>$get_customerid);

			if(isset($ocdata[0]) && !empty($ocdata[0]) && $ocdata[0] > 1) {
				$wgt_occupancy_charges = $ocdata[0];
				$sql_udata = array("room_type_id"=>$wgt_pst_roomtype,"occupancy_charges"=>$wgt_occupancy_charges);
			} else {
				$wgt_occupancy_charges = 0;
				$sql_udata = array("room_type_id"=>$wgt_pst_roomtype);
			}

			
			if($get_cur_room_type != $wgt_pst_roomtype) {
				
				$pst_booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
				$pst_bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');
				$pst_bill_to2 = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to_g');
				$tobiller = !empty($pst_bill_to) ? $pst_bill_to : $pst_bill_to2;

				$rack_rate = 0; $cdiscount = 0; $discountval = "";

				if($pst_booking_type == 'corporate' && $tobiller > 0) {
					
					$ischargetype = idget_data($tbL58,$tobiller,'chargetype');
					$cspg_discount = idget_data($tbL58,$tobiller,'discount');

					if($ischargetype == 'Unknown') { $gpr = "yes"; $cdiscount = 0; }
					elseif($ischargetype == 'On Discount') { $gpr = "yes"; $cdiscount = $cspg_discount; }
					elseif($ischargetype == 'Corporate Tariff') { $gpr = "no"; $cdiscount = 0; }

					$weekday = date('l',strtotime($server_get_date));
					$weekday = strtolower($weekday);

					if($gpr == 'yes') {
					
						$queryset = array("id"=>$wgt_pst_roomtype);
						$rate = mysqli_data_fetch($tbL52,'defaultprice',$queryset,'noarray');
						$rack_rate = $rate[0];
						$discountval = ($cdiscount / 100) * $rack_rate;
						$discountval = round($discountval,2);
						$rack_rate = $rack_rate - $discountval;

						$tax = $gh_get_vat;
						$servicecharge = $gh_get_service_charge;
						$consumption = $gh_get_consumption_tax;

					} elseif($gpr == 'no') {

						$queryset = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"ratetype"=>"naira","day"=>$weekday,"status"=>"Active","deletedata"=>0);
						$rate = mysqli_data_fetch($tbL147,'price',$queryset,'noarray');

						$tax_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>3,"status"=>"Active","deletedata"=>0); $wtax = mysqli_data_fetch($tbL82,'taxid',$tax_query,'noarray');

						$service_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>2,"status"=>"Active","deletedata"=>0); $wservice = mysqli_data_fetch($tbL82,'taxid',$service_query,'noarray');

						$consumption_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>1,"status"=>"Active","deletedata"=>0); $wconsumption = mysqli_data_fetch($tbL82,'taxid',$consumption_query,'noarray');

						if($wtax[0] == 3) { $tax = $gh_get_vat; } else { $tax = $gh_get_vat; }
						if($wservice[0] == 2) { $servicecharge = $gh_get_service_charge; } else { $servicecharge = $gh_get_service_charge; }
						if($wconsumption[0] == 1) { $consumption = $gh_get_consumption_tax; } else { $consumption = $gh_get_consumption_tax; }

						if($wtax[0] == 3 && $wservice[0] == 2) {
							$accu_taxes = 100 / (100 + $tax + $servicecharge + $consumption);
							$rack_rate = $accu_taxes * $rate[0];
							$rack_rate = round($rack_rate,2);
							$discountval = 0;
						} else {
							$rack_rate = $rate[0];
							$discountval = 0;
						}
					}

				} else {

					$queryset = array("id"=>$wgt_pst_roomtype);
					$rate = mysqli_data_fetch($tbL52,'defaultprice',$queryset,'noarray');
					$rack_rate = $rate[0];
					$discountval = 0;

					$tax = $gh_get_vat;
					$servicecharge = $gh_get_service_charge;
					$consumption = $gh_get_consumption_tax;
				}

				$wgt_room_rate = $rack_rate + $wgt_occupancy_charges;
				$wgt_tax = ($tax / 100) * $wgt_room_rate;
				$wgt_consumption = ($consumption / 100) * $wgt_room_rate;
				$wgt_service_charge = ($servicecharge / 100) * $wgt_room_rate;

				$sql_udata['actual_room_amount'] = $wgt_room_rate;
				$sql_udata['actual_tax_amount'] = $wgt_tax;
				$sql_udata['actual_service_charge'] = $wgt_service_charge;
				$sql_udata['actual_consumption_tax_amount'] = $wgt_consumption;
				$sql_udata['room_amount'] = $wgt_room_rate;
				$sql_udata['discount_amount'] = $discountval;
				$sql_udata['tax_amount'] = $wgt_tax;
				$sql_udata['service_charge'] = $wgt_service_charge;
				$sql_udata['consumption_tax_amount'] = $wgt_consumption;
			}

			mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

			#--end

			if($roomnumber > 0) {
				$room_prefix = idget_data($tbL56,$roomnumber,'roomprefix');
				$room_number = idget_data($tbL56,$roomnumber,'roomnumber');
			} else {
				$room_prefix = "NO-";
				$roomnumber = "ROOMNUMBER";
			}

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Changes were made to selected room successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest room detail of room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Room lodge detail of room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed while on reservation";
		}

	}

	#end 1:

	if(isset($_POST['cancelroombutton']) && isset($_POST['wgtcroom'])) {
		$checkers = $_POST['wgtcroom'];
		if(isset($checkers) && $checkers >= 1) {
			
			$booking_number = idget_data($tbL127,$checkers,'booking_number');
			$room_id = idget_data($tbL127,$checkers,'roomid');
			$customer_id = idget_data($tbL127,$checkers,'customerid');

			#check for number of rooms in this booking not cancelled
			
			$additionalQuery = " AND status IN('CheckedIn','Reserved','Temp. Reserved')";
			$queryset = array("booking_number"=>$booking_number);
			mysqli_data_check($tbL127,'(*)',$queryset);
			
			$get_room_result = $numOfrows;

			if(isset($get_room_result) && $get_room_result == 1) {
				$additionalQuery = "";
				$sql_uquery = array("booking_number"=>$booking_number);
				$sql_udata = array("reservation"=>"Cancelling");
				mysqli_data_update($tbL130,$sql_udata,$sql_uquery);
			}

			#end

			$additionalQuery = "";

			$house_keeping_status = 6;
			$room_status = 1;

			$rrquery = array("booking_number"=>$booking_number,"roomid"=>$room_id,"customerid"=>$customer_id,"day"=>1);
			$rrdata = mysqli_data_fetch($tbL134,'room_amount',$rrquery,'noarray');
			if(isset($rrdata[0]) && $rrdata[0] > 0) { $wgt_room_rate = $rrdata[0]; }
			else { $wgt_room_rate = 0; }

			$wgtcreason = $_POST['wgtcreason'];
			$wgtcpolicy = $_POST['wgtcpolicy'];

			$wgt_cancellation_discount = idget_data($tbL31,$wgtcpolicy,'discount');
			if($wgt_cancellation_discount > 0) { $cancellation_charges = ($wgt_cancellation_discount / 100) * $wgt_room_rate; }
			else { $cancellation_charges = 0; }

			$sql_uquery = array("id"=>$checkers);
			$sql_udata = array("cancel_policy"=>$wgtcpolicy,"cancel_reason"=>$wgtcreason,"cancellation_charges"=>$cancellation_charges,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"status"=>"Cancelled","cancel_date"=>$server_get_date,"cancel_time"=>$server_get_time,"cancel_byuser"=>$userSignedIn);
			mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

			$reason_for_cancellation = idget_data($tbL32,$wgtcreason,'name');

			/*$room_amount = $cancellation_charges;
			$service_charge = ($gh_get_service_charge / 100) * $room_amount;
			$consumption_amount = ($gh_get_consumption_tax / 100) * $room_amount;
			$vat_amount = ($gh_get_vat / 100) * $room_amount;*/

			$sql_uquery = array("booking_number"=>$booking_number,"roomid"=>$room_id,"customerid"=>$customer_id,"deletedata"=>0);
			$sql_udata = array("charge"=>"yes","deletedata"=>1);
			mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

			$room_prefix = idget_data($tbL56,$room_id,'roomprefix');
			$room_number = idget_data($tbL56,$room_id,'roomnumber');

			#housekeeping
			$ths_room_id = $room_id;

			$wgt_room_type = idget_data($tbL56,$ths_room_id,'room_type_id');
			$wgt_start_date = idget_data($tbL127,$checkers,'checkin_date');
			$wgt_end_date = idget_data($tbL127,$checkers,'checkout_date');

			$hk_query = array("roomid"=>$ths_room_id);
				$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room cancellation","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_update($tbL94,$hk_sql,$hk_query);

			$hk_query = "";
			$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room cancellation","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL95,$hk_sql,$hk_query);

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Changes were made to selected room successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest room (".$room_prefix.$room_number.") with booking number (".$booking_number.") was cancelled by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Guest room (".$room_prefix.$room_number.") with booking number (".$booking_number.") was cancelled due to: ".$reason_for_cancellation;
		}
	}

	#end 2:

	if(isset($_POST['noshowbutton']) && isset($_POST['checkers'])) {
		$checkers = $_POST['checkers'];
		if(isset($checkers) && $checkers >= 1) {
			
			$booking_number = idget_data($tbL127,$checkers,'booking_number');
			$room_id = idget_data($tbL127,$checkers,'roomid');

			$room_prefix = idget_data($tbL56,$room_id,'roomprefix');
			$room_number = idget_data($tbL56,$room_id,'roomnumber');

			$sql_uquery = array("id"=>$checkers);
			$sql_udata = array("checkout_date"=>$server_get_date,"status"=>"No Show","housekeeping_stateid"=>4,"room_status_id"=>5);
			mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$booking_number);
			$sql_udata = array("reservation"=>"No Show");
			mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$booking_number,"roomid"=>$room_id,"deletedata"=>0);
			$sql_udata = array("deletedata"=>1);
			mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

			$sql_uquery = array("roomid"=>$room_id);
			$sql_udata = array("housekeeping_stateid"=>4,"room_status_id"=>1);
			mysqli_data_update($tbL94,$sql_udata,$sql_uquery);

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Changes were made to selected room successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest room status for room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to NoShow by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Guest room status for room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to NoShow and no longer available for checkin";
		}
	}

	#end 3:

	if(isset($_POST['checkinbutton']) && isset($_POST['checkers'])) {
		
		$checkers = $_POST['checkers'];
		$room_id = $_POST["roomnumber$checkers"];
		$ischeckindate = $_POST["checkin$checkers"];
		$ischeckoutdate = $_POST["checkout$checkers"];

		if((isset($checkers) && $checkers >= 1) && (isset($room_id) && $room_id >= 1) && (strtotime($server_get_date) >= strtotime($ischeckindate)) && (strtotime($ischeckoutdate) > strtotime($ischeckindate))) {

			$house_keeping_status = 6;
			$room_status = 3;

			$booking_number = idget_data($tbL127,$checkers,'booking_number');

			$wgt_pst_roomtype = $_POST["roomtype$checkers"];
			$wgt_pst_adult = $_POST["adults$checkers"];
			$wgt_pst_child = $_POST["childs$checkers"];
			$wgt_pst_occupancytype = $_POST["occupancytype$checkers"];

			$checkin = $_POST["checkin$checkers"];
			$checkout = $_POST["checkout$checkers"];
			$weekoftheday = date('l',strtotime($checkin));
			$weekoftheday = strtolower($weekoftheday);

			$room_type_id = $wgt_pst_roomtype;
			$get_cur_room_type = idget_data($tbL127,$checkers,'room_type_id');
			$get_customerid = idget_data($tbL127,$checkers,'customerid');

			$room_prefix = idget_data($tbL56,$room_id,'roomprefix');
			$room_number = idget_data($tbL56,$room_id,'roomnumber');

			#--change day, week and bill date of this customer

			$sqlset = "MAX(day)";
			$queryset = "booking_number='{$booking_number}' AND customerid={$get_customerid} AND wkf=0 AND deletedata=0";
			$maxday = mysqli_arithmetic_data($tbL134,$sqlset,$queryset);
			$isday = dayDiffs($checkout,$checkin);

			//$datasets = "invoice_number,room_type_id,customerid,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,room_status,roomid";
			//$in_query = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$maxday,"wkf"=>0,"deletedata"=>0);
			//$in_data = mysqli_data_fetch($tbL134,$datasets,$in_query,'noarray');

			$weekdayBtwn = getWeekdays($checkin,$checkout,'nameweekday');
			$dateBtwn = getWeekdays($checkin,$checkout,'daterange');

			$day_incr = 0;

			for($d=0; $d < $isday; $d++) {
				$day_incr += 1;
				$getWk = $weekdayBtwn[$d];
				$getDt = $dateBtwn[$d];
				
				$pst_query = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$day_incr,"wkf"=>0,"deletedata"=>0);
				$chk_entry = mysqli_data_checkr($tbL134,'id',$pst_query);
				
				if($chk_entry == true) {
					$pst_field = array("bill_date"=>$getDt,"weekday"=>strtolower($getWk),"charge"=>"yes","room_status"=>"CheckedIn");
					mysqli_data_update($tbL134,$pst_field,$pst_query);
				}/* else {
					$pst_field = array("charge_type"=>$in_data[9],"billto"=>$in_data[10],"booking_number"=>$booking_number,"invoice_number"=>$in_data[0],"room_type_id"=>$wgt_pst_roomtype,"roomid"=>$in_data[16],"customerid"=>$get_customerid,"day"=>$day_incr,"weekday"=>strtolower($getWk),"actual_room_amount"=>$in_data[11],"actual_tax_amount"=>$in_data[12],"actual_service_charge"=>$in_data[13],"actual_consumption_tax_amount"=>$in_data[14],"room_amount"=>$in_data[3],"tax_amount"=>$in_data[5],"consumption_tax_amount"=>$in_data[6],"service_charge"=>$in_data[7],"discount_amount"=>$in_data[4],"charge"=>$in_data[8],"bill_date"=>$getDt,"status"=>"Pending","room_status"=>$in_data[15],"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL134,$pst_field,$pst_query);
				}*/
			}

			if($maxday > $isday) {
				for($d=$maxday; $d > $isday; $d--) {
					$constrain = array("booking_number"=>$booking_number,"customerid"=>$get_customerid,"day"=>$d,"wkf"=>0,"deletedata"=>0);
					trash_record($tbL134,$constrain);
				}
			}

			#--end

			$sql_uquery = array("id"=>$checkers);
			$sql_udata = array("room_type_id"=>$wgt_pst_roomtype,"roomid"=>$room_id,"adult"=>$wgt_pst_adult,"child"=>$wgt_pst_child,"occupancy_type"=>$wgt_pst_occupancytype,"checkin_date"=>$checkin,"checkin_time"=>$server_get_time,"checkout_date"=>$checkout,"checkout_time"=>$wgt_checkout_time,"noofdays"=>$isday,"checkin_byuser"=>$userSignedIn,"status"=>"CheckedIn","housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status);
			mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

			$sql_uquery = array("booking_number"=>$booking_number);
			$sql_udata = array("reservation"=>"Checking In","checkin_date"=>$checkin,"checkin_time"=>$server_get_time,"checkout_date"=>$checkout,"checkout_time"=>$wgt_checkout_time);
			mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

			//get charges for occupancy type if available
			$ocquery = array("room_type_id"=>$wgt_pst_roomtype,"occupancy_type_id"=>$wgt_pst_occupancytype,"deletedata"=>0);
			$ocdata = mysqli_data_fetch($tbL54,'price',$ocquery,'noarray');
			
			if(isset($ocdata[0]) && !empty($ocdata[0]) && $ocdata[0] > 0) { $wgt_occupancy_charges = $ocdata[0]; }
			else { $wgt_occupancy_charges = 0; }

			$rrquery = array("booking_number"=>$booking_number,"customerid"=>$get_customerid);
			$sql_rudata = array("room_type_id"=>$wgt_pst_roomtype,"roomid"=>$room_id,"occupancy_charges"=>$wgt_occupancy_charges,"charge"=>"yes","room_status"=>"CheckedIn");

			if($get_cur_room_type != $wgt_pst_roomtype) {
			
				$pst_booking_type = idget_fdata($tbL130,'booking_number',$booking_number,'booking_type');
				$pst_bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');
				$pst_bill_to2 = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to_g');
				$tobiller = !empty($pst_bill_to) ? $pst_bill_to : $pst_bill_to2;

				$rack_rate = 0; $cdiscount = 0; $discountval = "";

				if($pst_booking_type == 'corporate' && $tobiller > 0) {
					
					$ischargetype = idget_data($tbL58,$tobiller,'chargetype');
					$cspg_discount = idget_data($tbL58,$tobiller,'discount');

					if($ischargetype == 'Unknown') { $gpr = "yes"; $cdiscount = 0; }
					elseif($ischargetype == 'On Discount') { $gpr = "yes"; $cdiscount = $cspg_discount; }
					elseif($ischargetype == 'Corporate Tariff') { $gpr = "no"; $cdiscount = 0; }

					$weekday = date('l',strtotime($server_get_date));
					$weekday = strtolower($weekday);

					if($gpr == 'yes') {
					
						$queryset = array("id"=>$wgt_pst_roomtype);
						$rate = mysqli_data_fetch($tbL52,'defaultprice',$queryset,'noarray');
						$rack_rate = $rate[0];
						$discountval = ($cdiscount / 100) * $rack_rate;
						$discountval = round($discountval,2);
						$rack_rate = $rack_rate - $discountval;

						$tax = $gh_get_vat;
						$servicecharge = $gh_get_service_charge;
						$consumption = $gh_get_consumption_tax;

					} elseif($gpr == 'no') {

						$queryset = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"ratetype"=>"naira","day"=>$weekday,"status"=>"Active","deletedata"=>0);
						$rate = mysqli_data_fetch($tbL147,'price',$queryset,'noarray');

						$tax_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>3,"status"=>"Active","deletedata"=>0); $wtax = mysqli_data_fetch($tbL82,'taxid',$tax_query,'noarray');

						$service_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>2,"status"=>"Active","deletedata"=>0); $wservice = mysqli_data_fetch($tbL82,'taxid',$service_query,'noarray');

						$consumption_query = array("corporateid"=>$tobiller,"room_type_id"=>$wgt_pst_roomtype,"taxid"=>1,"status"=>"Active","deletedata"=>0); $wconsumption = mysqli_data_fetch($tbL82,'taxid',$consumption_query,'noarray');

						if($wtax[0] == 3) { $tax = $gh_get_vat; } else { $tax = 0; }
						if($wservice[0] == 2) { $servicecharge = $gh_get_service_charge; } else { $servicecharge = 0; }
						if($wconsumption[0] == 1) { $consumption = $gh_get_consumption_tax; } else { $consumption = 0; }

						$accu_taxes = 100 / (100 + $tax + $servicecharge + $consumption);
						$rack_rate = $accu_taxes * $rate[0];
						$rack_rate = round($rack_rate,2);
						$discountval = 0;
					}

				} else {

					$queryset = array("id"=>$wgt_pst_roomtype);
					$rate = mysqli_data_fetch($tbL52,'defaultprice',$queryset,'noarray');
					$rack_rate = $rate[0];
					$discountval = 0;

					$tax = $gh_get_vat;
					$servicecharge = $gh_get_service_charge;
					$consumption = $gh_get_consumption_tax;
				}

				$wgt_room_rate = $rack_rate;
				$wgt_tax = ($tax / 100) * $wgt_room_rate;
				$wgt_consumption = ($consumption / 100) * $wgt_room_rate;
				$wgt_service_charge = ($servicecharge / 100) * $wgt_room_rate;

				$sql_rudata['actual_room_amount'] = $wgt_room_rate;
				$sql_rudata['actual_tax_amount'] = $wgt_tax;
				$sql_rudata['actual_service_charge'] = $wgt_service_charge;
				$sql_rudata['actual_consumption_tax_amount'] = $wgt_consumption;
				$sql_rudata['room_amount'] = $wgt_room_rate;
				$sql_rudata['discount_amount'] = $discountval;
				$sql_rudata['tax_amount'] = $wgt_tax;
				$sql_rudata['service_charge'] = $wgt_service_charge;
				$sql_rudata['consumption_tax_amount'] = $wgt_consumption;

			}

			mysqli_data_update($tbL134,$sql_rudata,$rrquery);

			#--end

			
			#housekeeping
			$ths_room_id = $room_id;
			
			$wgt_room_type = idget_data($tbL56,$ths_room_id,'room_type_id');
			$wgt_start_date = idget_data($tbL127,$checkers,'checkin_date');
			$wgt_end_date = idget_data($tbL127,$checkers,'checkout_date');
			
			/*$wgt_room_hk_status = idget_fdata($tbL94,'roomid',$ths_room_id,'roomid');

			if(isset($wgt_room_hk_status) && $wgt_room_hk_status == $ths_room_id) {
				$hk_query = array("roomid"=>$ths_room_id);
				$hk_sql = array("room_type"=>$wgt_room_type,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon guest checkin","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_update($tbL94,$hk_sql,$hk_query);
			} else {
				$hk_query = "";
				$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon guest checkin","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL94,$hk_sql,$hk_query);
			}*/

			$hk_query = array("roomid"=>$ths_room_id);
			$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon guest checkin","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_update($tbL94,$hk_sql,$hk_query);

			$hk_query = "";
			$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon guest checkin","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL95,$hk_sql,$hk_query);


			#check if booking is corporate paid by corporate
			
			$cpg_query = array("booking_number"=>$booking_number,"bill_type"=>"Corporate");
			$cpg_data = mysqli_data_fetch($tbL130,'bill_to,datelogged',$cpg_query,'noarray');

			if(is_array($cpg_data) && $cpg_data[0] > 0) {
				
				$cpgx_query = array("booking_number"=>$booking_number,"roomid"=>$ths_room_id);
				$cpgx_data = mysqli_data_fetch($tbL127,'customerid',$cpgx_query,'noarray');
				$get_guest_name = idget_data($tbL102,$cpgx_data[0],'fname').' ';
				$get_guest_name .= idget_data($tbL102,$cpgx_data[0],'lname');

				$queryset = "booking_number='{$booking_number}' AND roomid={$ths_room_id} AND deletedata=0";
				$_1pyx_sql = "SUM(room_amount)"; $_1pyx = mysqli_arithmetic_data($tbL134,$_1pyx_sql,$queryset);
				$_2pyx_sql = "SUM(discount_amount)"; $_2pyx = mysqli_arithmetic_data($tbL134,$_2pyx_sql,$queryset);
				$_3pyx_sql = "SUM(tax_amount)"; $_3pyx = mysqli_arithmetic_data($tbL134,$_3pyx_sql,$queryset);
				$_4pyx_sql = "SUM(consumption_tax_amount)"; $_4pyx = mysqli_arithmetic_data($tbL134,$_4pyx_sql,$queryset);
				$_5pyx_sql = "SUM(service_charge)"; $_5pyx = mysqli_arithmetic_data($tbL134,$_5pyx_sql,$queryset);
				$pay_amount = ($_1pyx + $_3pyx + $_4pyx + $_5pyx) - $_2pyx;

				$credit_limit = idget_data($tbL58,$cpg_data[0],'creditlimit');
				$credit_notification_limit = idget_data($tbL58,$cpg_data[0],'notifylimit');
				$new_creditlimit = $credit_limit - $pay_amount;

				$blc_selection_key = array("id"=>$cpg_data[0]);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

				$transaction_desc = "Room charge (".$room_prefix.$room_number.") - ".$get_guest_name;
				$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$cpg_data[0],"transaction_number"=>$booking_number,"transaction_type"=>"Debit","amount"=>$pay_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$cpg_data[1],"detail"=>$transaction_desc,"biller"=>"booking","counter_used"=>$ths_mycounter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL63,$ledger_dataproperty,'');

				#-end
			}

			$guest_flname = idget_data($tbL102,$get_customerid,'fname').' ';
			$guest_flname .= idget_data($tbL102,$get_customerid,'lname');

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Changes were made to selected room successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest room status of room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to CheckedIn by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Guest (".$guest_flname.") room status of room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to CheckedIn upon guest arrival on ".date('d-m-Y',strtotime($server_get_date))." ".$server_get_time;

		} else {
			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification Error";
			$post_message = "Please select necessary information for check-in properly and save it first";
		}
	}

	#end 4:

	if(isset($_POST['checkoutbutton']) && isset($_POST['checkers'])) {
		$checkers = $_POST['checkers'];
		if(isset($checkers) && $checkers >= 1) {

			$booking_number = idget_data($tbL127,$checkers,'booking_number');
			$myBilltype = idget_fdata($tbL130,'booking_number',$booking_number,'bill_type');
			$bill_to = idget_fdata($tbL130,'booking_number',$booking_number,'bill_to');
			$chargedLatecheckout = idget_fdata($tbL130,'booking_number',$booking_number,'islate_checkout');

			$room_type_id = idget_data($tbL127,$checkers,'room_type_id');
			$room_id = idget_data($tbL127,$checkers,'roomid');
			$noofdays = idget_data($tbL127,$checkers,'noofdays');
			$customer_id = idget_data($tbL127,$checkers,'customerid');
			$checkedin_date = idget_data($tbL127,$checkers,'checkin_date');
			$checkedout_date = idget_data($tbL127,$checkers,'checkout_date');

			$gpos_query = array("roomid"=>$room_id,"booking_number"=>$booking_number,"status"=>"Pending","isreversed"=>0,"deletedata"=>0);
			$gpos_data = mysqli_data_checkr($tbL99,'(*)',$gpos_query);
			
			if($gpos_data == false) {

				$house_keeping_status = 2;
				$room_status = 4;

				$room_prefix = idget_data($tbL56,$room_id,'roomprefix');
				$room_number = idget_data($tbL56,$room_id,'roomnumber');

				#charge room if not already charged. Get days that guest actually stayed
				if($checkedin_date == $server_get_date) {
					$additionalQuery = " AND checkin_date NOT IN('{$server_get_date}') AND status IN('Swapped','Downgraded','Upgraded') ORDER BY id DESC LIMIT 1";
					$chk4roomchg = array("booking_number"=>$booking_number,"customerid"=>$customer_id);
					$chkiftr = mysqli_data_checkr($tbL127,'(*)',$chk4roomchg);

					if($chkiftr == false) {
						$additionalQuery = " AND bill_date <= '{$server_get_date}'";
						$sql_cquery = array("booking_number"=>$booking_number,"roomid"=>$room_id,"charge"=>"yes","ischarged"=>0,"deletedata"=>0);
						$sql_cdata = array("ischarged"=>1,"bill_time"=>$server_get_time);
						mysqli_data_update($tbL134,$sql_cdata,$sql_cquery);
					}
				} else {
					$additionalQuery = " AND bill_date < '{$server_get_date}'";
					$sql_cquery = array("booking_number"=>$booking_number,"roomid"=>$room_id,"charge"=>"yes","ischarged"=>0,"deletedata"=>0);
					$sql_cdata = array("ischarged"=>1,"bill_time"=>$server_get_time);
					mysqli_data_update($tbL134,$sql_cdata,$sql_cquery);
				}

				$additionalQuery = "";

				if($myBilltype == 'Corporate' && $bill_to > 0) {

					//for days not charged
					$nocharge_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"charge_type"=>"corporate","billto"=>$bill_to,"charge"=>"yes","ischarged"=>0,"wkf"=>0,"deletedata"=>0);
					$chkiftr = mysqli_data_checkr($tbL134,'(*)',$nocharge_query);

					if($chkiftr == true) {
						$set_query = "booking_number='{$booking_number}' AND roomid={$room_id} AND charge_type='corporate' AND billto={$bill_to} AND charge='yes' AND ischarged=0 AND wkf=0 AND deletedata=0";
						$set_sql_1 = "SUM(room_amount)"; $set_sql_2 = "SUM(discount_amount)"; $set_sql_3 = "SUM(tax_amount)";
						$set_sql_4 = "SUM(consumption_tax_amount)"; $set_sql_5 = "SUM(service_charge)";
						$cspg_room_amount = mysqli_arithmetic_data($tbL134,$set_sql_1,$set_query);
						$cspg_discount_amount = mysqli_arithmetic_data($tbL134,$set_sql_2,$set_query);
						$cspg_tax_amount = mysqli_arithmetic_data($tbL134,$set_sql_3,$set_query);
						$cspg_consumption_amount = mysqli_arithmetic_data($tbL134,$set_sql_4,$set_query);
						$cspg_service_amount = mysqli_arithmetic_data($tbL134,$set_sql_5,$set_query);

						$cspg_nocharge = ($cspg_room_amount - $cspg_discount_amount) + ($cspg_tax_amount + $cspg_consumption_amount + $cspg_service_amount);

						$credit_limit = idget_data($tbL58,$bill_to,'creditlimit');
						$credit_notification_limit = idget_data($tbL58,$bill_to,'notifylimit');
						$new_creditlimit = $credit_limit + $cspg_nocharge;

						$blc_selection_key = array("id"=>$bill_to);
						$crl_datasets = array("creditlimit"=>$new_creditlimit);
						mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

						$details = "System reverse charges for unused days upon checkout room ".$room_prefix.$room_number;
						$cspg_upd = array("cspgid"=>$bill_to,"biller"=>"booking","transaction_number"=>$booking_number,"transaction_type"=>"Credit","amount"=>$cspg_nocharge,"credit_balance"=>$new_creditlimit,"paymode"=>0,"transaction_date"=>$server_get_date,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						mysqli_data_insert($tbL63,$cspg_upd,'');

					}
				}

				//get time difference from checkout time and default checkout time
				//if guest checkout time exceeded default time
				//then charge guest base on time different

				//remove non billed post
				$addQuery = " AND wkf IN(0,7) AND bill_date >= '{$server_get_date}'";
				$remove_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"ischarged"=>0);
				trash_record($tbL134,$remove_query);
				
				//$booking_checkouttime = $server_get_date.' '.$server_get_time;
				//$default_checkouttime = $server_get_date.' '.$wgt_checkout_time;

				if($checkedin_date != $server_get_date && strtotime($server_get_time) > strtotime($wgt_checkout_time)) {
					$wghr = getHrs($wgt_checkout_time,$server_get_time);
					$wgt_inout_per_charge = wgt_inout_charges($wghr,'checkout');
				} else {
					$wgt_inout_per_charge = 0;
				}

				#get room rate
				$additionalQuery = " AND wkf IN(0,7) ORDER BY id DESC LIMIT 1";
				$day_charge_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"ischarged"=>1,"deletedata"=>0,"room_status"=>"CheckedIn");
				$get_day_charge = mysqli_data_fetch($tbL134,'room_amount,tax_amount,consumption_tax_amount,service_charge',$day_charge_query,'noarray');

				$additionalQuery = "";
				
				if(!empty($get_day_charge[0]) && $get_day_charge[0] > 0) { $wgt_room_rate = $get_day_charge[0] + $get_day_charge[1] + $get_day_charge[2] + $get_day_charge[3]; } else { $wgt_room_rate = idget_data($tbL52,$room_type_id,'defaultprice'); }


				if($wgt_inout_per_charge > 0 && $chargedLatecheckout == 'Yes') {

					$invoice_number = "LATECHECKOUT";
					$details = "Late checkout charges for room no. ".$room_prefix.$room_number;

					$noofdays = $noofdays + 1;

					if($myBilltype == 'Corporate' && $bill_to > 0) {
						
						$late_checkout_charges = ($wgt_inout_per_charge / 100) * $wgt_room_rate;

						$sql_py_pst_data = array("cspgid"=>$bill_to,"biller"=>"booking","booking_number"=>$booking_number,"transaction_type"=>"Debit","amount"=>$late_checkout_charges,"paymode"=>0,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						$isdata = mysqli_data_insert($tbL63,$sql_py_pst_data,'');

						$pst_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"bill_date"=>$server_get_date);
						$pst_field = array("charge_type"=>"corporate","billto"=>$bill_to,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$room_type_id,"roomid"=>$room_id,"customerid"=>$customer_id,"day"=>$noofdays,"room_amount"=>$late_checkout_charges,"discount_amount"=>0,"tax_amount"=>0,"consumption_tax_amount"=>0,"service_charge"=>0,"occupancy_charges"=>0,"extrabed_charges"=>0,"charge"=>"yes","ischarged"=>1,"bill_date"=>$server_get_date,"wkf"=>1,"status"=>"Successful","room_status"=>"CheckedIn","datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

						mysqli_data_insert($tbL134,$pst_field,$pst_query);

						$chargeThisLate = 1;

					} else {
		
						if($myBilltype == 'Guest' || $myBilltype == 'Group Owner') {
							
							$late_checkout_charges = ($wgt_inout_per_charge / 100) * $wgt_room_rate;

							$pst_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"bill_date"=>$server_get_date);
							$pst_field = array("charge_type"=>"individual","billto"=>0,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$room_type_id,"roomid"=>$room_id,"customerid"=>$customer_id,"day"=>$noofdays,"room_amount"=>$late_checkout_charges,"discount_amount"=>0,"tax_amount"=>0,"consumption_tax_amount"=>0,"service_charge"=>0,"occupancy_charges"=>0,"extrabed_charges"=>0,"charge"=>"yes","ischarged"=>1,"bill_date"=>$server_get_date,"wkf"=>1,"status"=>"Successful","room_status"=>"CheckedIn","datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

							mysqli_data_insert($tbL134,$pst_field,$pst_query);

						} elseif($myBilltype == 'Complimentary') {

							$late_checkout_charges = ($wgt_inout_per_charge / 100) * $wgt_room_rate;

							$pst_query = array("booking_number"=>$booking_number,"roomid"=>$room_id,"bill_date"=>$server_get_date);
							$pst_field = array("charge_type"=>"complimentary","billto"=>0,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$room_type_id,"roomid"=>$room_id,"customerid"=>$customer_id,"day"=>$noofdays,"room_amount"=>$late_checkout_charges,"discount_amount"=>0,"tax_amount"=>0,"consumption_tax_amount"=>0,"service_charge"=>0,"occupancy_charges"=>0,"extrabed_charges"=>0,"charge"=>"yes","ischarged"=>1,"bill_date"=>$server_get_date,"wkf"=>1,"status"=>"Successful","room_status"=>"CheckedIn","datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"userid"=>$userSignedIn,"bizday"=>$server_get_bizedate);

							mysqli_data_insert($tbL134,$pst_field,$pst_query);
						}

						$chargeThisLate = 1;
					}
					
				} else {
					$chargeThisLate = 0;
				}

				$addQuery = "";


				#update checked out room status in the guest list table
				$sql_uquery = array("id"=>$checkers);
				$sql_udata = array("checkout_byuser"=>$userSignedIn,"status"=>"CheckedOut","housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"late_checkout_charges"=>$chargeThisLate,"checkout_date"=>$server_get_date,"checkout_time"=>$server_get_time);
				mysqli_data_update($tbL127,$sql_udata,$sql_uquery);


				#change booking status upon this effect
				$additionalQuery = " AND status IN('CheckedIn','Reserved')";
				//$queryif = array("booking_number"=>$booking_number,"status"=>"CheckedIn");
				$queryif = array("booking_number"=>$booking_number);
				$chkIfroomisLeftCheckedIn = mysqli_data_checkr($tbL127,'(*)',$queryif);
			
				if($chkIfroomisLeftCheckedIn == false) {
					$additionalQuery = "";
					$sql_uquery = array("booking_number"=>$booking_number);
					$sql_udata = array("reservation"=>"Checking Out","checkout_date"=>$server_get_date,"checkout_time"=>$server_get_time);
					mysqli_data_update($tbL130,$sql_udata,$sql_uquery);
				}

				$additionalQuery = "";
				

				#housekeeping
				$ths_room_id = $room_id;

				$wgt_room_type = idget_data($tbL56,$ths_room_id,'room_type_id');
				$wgt_start_date = idget_data($tbL127,$checkers,'checkin_date');
				$wgt_end_date = idget_data($tbL127,$checkers,'checkout_date');

				$hk_query = array("roomid"=>$ths_room_id);
				$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>8,"remarks"=>"room status changed upon guest checkout","endate"=>$server_get_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);

				$hk_query = "";
				$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon guest checkout","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);

				//send sms to guest as room is checked out
				$g_query = array("booking_number"=>$booking_number,"primary_guest"=>1);
				$for_guest_data = mysqli_data_fetch($tbL102,'mobile',$g_query,'noarray');

				//get sender & notification message to initiate sms
				$sender = _SHORT_NAME;
				$composemsg = $gh_get_notification_message;
				//$sms2phone = serializePhone($for_guest_data[0]);
				//sendSMS($sms2phone,$sender,$composemsg);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Changes were made to selected room successfully";
				
				$islogfile = 1;
				$logfile_msg = "Guest room status for room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to CheckedOut by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$guestAct_msg = "Guest room status for room number (".$room_prefix.$room_number.") and the booking number (".$booking_number.") changed to CheckedOut upon guest departure";

			} else {
				
				$gpos_query = array("roomid"=>$room_id,"booking_number"=>$booking_number,"status"=>"Pending","isreversed"=>0,"deletedata"=>0); $gpos_data = mysqli_data_fetch($tbL99,'order_number',$gpos_query,'array'); $orders = "";
				if(is_array($gpos_data)) { foreach($gpos_data as $key => $val) { $orders .= $val['order_number'].","; } }

				$x_orders = substr_replace($orders,'',-1,1);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Unable to complete request. Check for pending orders ({$x_orders}) from the outlet";
			}
		}
	}

	#end 5:

	#swaproom
	if(isset($_POST['swaproombutton']) && isset($_POST['wgtroom']) && $_POST['wgtroom'] >= 1) {
		
		$wgtroom = $_POST['wgtroom'];
		$wgtcuroom = $_POST['wgtcuroom'];
		$wgtcuroomid = $_POST['wgtcuroomid'];
		$wgtroomtype = $_POST['wgtroomtype'];
		$wgtpstbk = $_POST['wgtpstbk'];

		$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
		$room_ocp_datasets = "customerid,adult,child,isextrabed,occupancy_type,reservation,checkout_date,checkout_time,checkin_date";
		$room_ocp_data = mysqli_data_fetch($tbL127,$room_ocp_datasets,$sql_uquery,'noarray');
		
		//get days left for room to be checked out
		$daysLeft = dayDiffs($server_get_date,$room_ocp_data[6]);
		
		if(isset($daysLeft) && $daysLeft > 0) {

			$sql_uquery_x = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"roomid"=>$wgtroom);
			$isNewroomExist = mysqli_data_checkr($tbL127,'(*)',$sql_uquery_x);

			if($isNewroomExist == true) {
				
				$saynotify = 1;
				$notifytype = 2;
			
				$post_header = "Notification";
				$post_message = "Sorry, you cannot swap to this room as it is already exist in the guest bill";

			} else {
			
				//for current room
				$house_keeping_status = 4;
				$room_status = 8;

				$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
				$sql_udata = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"checkout_date"=>$server_get_date,"status"=>"Swapped");
				mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

				$hk_query = array("roomid"=>$wgtcuroomid);
				$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room swap by guest","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);

				$hk_query = "";
				$hk_sql = array("room_type"=>$wgtroomtype,"roomid"=>$wgtcuroomid,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room swap by guest","startdate"=>$room_ocp_data[8],"endate"=>$room_ocp_data[6],"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);


				//for new room swapped to
				$house_keeping_status = 6;
				$room_status = 3;

				$room_insert_query = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"roomid"=>$wgtroom,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$sql_insert_data = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"room_type_id"=>$wgtroomtype,"roomid"=>$wgtroom,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"adult"=>$room_ocp_data[1],"child"=>$room_ocp_data[2],"isextrabed"=>$room_ocp_data[3],"occupancy_type"=>$room_ocp_data[4],"noofdays"=>$daysLeft,"reservation"=>$room_ocp_data[5],"checkin_date"=>$server_get_date,"checkin_time"=>$server_get_time,"checkout_date"=>$room_ocp_data[6],"checkout_time"=>$room_ocp_data[7],"checkin_byuser"=>$userSignedIn,"userid"=>$userSignedIn,"status"=>"CheckedIn","datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL127,$sql_insert_data,$room_insert_query);

				$booking_number = $wgtpstbk;
				$ths_room_id = $wgtroom;
				$wgt_room_type = $wgtroomtype;
				$guest_number = $room_ocp_data[0];
				$wgt_start_date = $server_get_date;
				$wgt_end_date = $room_ocp_data[6];

				$wgt_no_of_days = $daysLeft;

				///for housekeeping room update
				$hk_query = array("roomid"=>$ths_room_id);
				$chkIfExist = mysqli_data_checkr($tbL94,'(*)',$hk_query);
				
				if($chkIfExist == true) {
					$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room swap by guest","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);
				} else {
					$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room swap by guest","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL94,$hk_sql,$hk_query);
				}
				
				$hk_query = "";
				$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room swap by guest","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);
				///end

				//get current invoice
				$additionalQuery = " ORDER BY id DESC LIMIT 1";
				$invquery = array("booking_number"=>$booking_number,"customerid"=>$guest_number,"roomid"=>$wgtcuroomid,"deletedata"=>0);
				$invdatasets = "invoice_number,room_amount,tax_amount,consumption_tax_amount,service_charge,charge,charge_type,billto,actual_room_amount,actual_tax_amount,actual_service_charge,actual_consumption_tax_amount,wkf,discount_amount";
				$invdata = mysqli_data_fetch($tbL134,$invdatasets,$invquery,'noarray');
				$additionalQuery = "";

				$sql_uquery = array("booking_number"=>$booking_number,"roomid"=>$wgtcuroomid,"ischarged"=>0);
				$sql_udata = array("deletedata"=>1);
				mysqli_data_update($tbL134,$sql_udata,$sql_uquery);
				
				$invoice_number = $invdata[0];
				$wgt_room_rate = $invdata[1];
				$xwgt_tax = $invdata[2];
				$xwgt_consumption = $invdata[3];
				$xwgt_service_charge = $invdata[4];
				$chargeroom = $invdata[5];
				$charge_type = $invdata[6];
				$billto = $invdata[7];
				$actual_room_amount = $invdata[8];
				$actual_tax_amount = $invdata[9];
				$actual_service_charge = $invdata[10];
				$actual_consumption_tax_amount = $invdata[11];
				$iswkf = $invdata[12];
				$xdiscount_amount = $invdata[13];

				$weekdayBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'nameweekday');
				$dateBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'daterange');

				$getWkn = "";
			
				for($i=1; $i <= $wgt_no_of_days; $i++) {
					
					$getWkn = $i - 1;
					$getWk = $weekdayBtwn[$getWkn];
					$getDt = $dateBtwn[$getWkn];

					$daily_sql = array("charge_type"=>$charge_type,"billto"=>$billto,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$wgt_room_type,"roomid"=>$ths_room_id,"customerid"=>$guest_number,"day"=>$i,"weekday"=>strtolower($getWk),"actual_room_amount"=>$actual_room_amount,"actual_tax_amount"=>$actual_tax_amount,"actual_service_charge"=>$actual_service_charge,"actual_consumption_tax_amount"=>$actual_consumption_tax_amount,"room_amount"=>$wgt_room_rate,"tax_amount"=>$xwgt_tax,"consumption_tax_amount"=>$xwgt_consumption,"service_charge"=>$xwgt_service_charge,"discount_amount"=>$xdiscount_amount,"charge"=>"yes","bill_date"=>$getDt,"status"=>"Pending","room_status"=>"CheckedIn","wkf"=>$iswkf,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL134,$daily_sql,'');

					$getWk = ""; $getDt = "";
				}

				$room_prefix = idget_data($tbL56,$ths_room_id,'roomprefix');
				$room_number = idget_data($tbL56,$ths_room_id,'roomnumber');

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Room swap was completed successfully. Use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest room (".$wgtcuroom.") was swapped to (".$room_prefix.$room_number.") by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$guestAct_msg = "Guest room (".$wgtcuroom.") was swapped to (".$room_prefix.$room_number.")";

			}

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Ensure guest have more days";
		}
		
	}

	#end 6:

	#updowngrade room
	if(isset($_POST['updownroombutton']) && isset($_POST['wgtroom']) && $_POST['wgtroom'] >= 1) {
		
		$wgtroom = $_POST['wgtroom'];
		$wgtcuroom = $_POST['wgtcuroom'];
		$wgtcuroomid = $_POST['wgtcuroomid'];
		$wgtroomtype = $_POST['wgtroomtype'];
		$wgtpstroomtype = $_POST['wgtpstroomtype'];
		$wgtpstbk = $_POST['wgtpstbk'];
		$wgtroomstatus = $_POST['roomstatus'];

		if($wgtroomstatus == 'Downgraded') { $thsStatus = "Downgraded"; $actionChanged = "downgrade"; }
		elseif($wgtroomstatus == 'Upgraded') { $thsStatus = "Upgraded"; $actionChanged = "upgrade"; }

		$wgtunitprice = $_POST['wgtunitprice'];
		$wgtdiscount = $_POST['wgtdiscount'];
		$wgttax = $_POST['wgttax'];
		$wgtservicecharge = $_POST['wgtservicecharge'];
		$wgtconsumption = $_POST['wgtconsumption'];

		$pst_booking_type = idget_fdata($tbL130,'booking_number',$wgtpstbk,'booking_type');
		$myBilltype = idget_fdata($tbL130,'booking_number',$wgtpstbk,'bill_type');
		$pst_bill_to = idget_fdata($tbL130,'booking_number',$wgtpstbk,'bill_to');
		$pst_wkf = idget_fdata($tbL130,'booking_number',$wgtpstbk,'isweekend_fares');

		$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
		$room_ocp_datasets = "customerid,adult,child,isextrabed,occupancy_type,reservation,checkout_date,checkout_time,checkin_date";
		$room_ocp_data = mysqli_data_fetch($tbL127,$room_ocp_datasets,$sql_uquery,'noarray');
		
		//get days left for room to be checked out
		$daysLeft = dayDiffs($server_get_date,$room_ocp_data[6]);
		
		if(isset($daysLeft) && $daysLeft > 0) {

			$sql_uquery_x = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"roomid"=>$wgtroom);
			$isNewroomExist = mysqli_data_checkr($tbL127,'(*)',$sql_uquery_x);

			if($isNewroomExist == true) {
				
				$saynotify = 1;
				$notifytype = 2;
			
				$post_header = "Notification";
				$post_message = "Sorry, you cannot upgrade or downgrade to this room as it is already exist in the guest bill";

			} else {

				//for current room
				$house_keeping_status = 4;
				$room_status = 8;

				///check for room charges per night
				$additionalQuery = " ORDER BY id DESC LIMIT 1";
				$rquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid,"deletedata"=>0);
				$rdata = mysqli_data_fetch($tbL134,'room_amount,invoice_number,charge',$rquery,'noarray');
				$additionalQuery = "";

				$rack_rate = 0;

				if((isset($rdata[0]) && isset($wgtunitprice)) && ($rdata[0] > $wgtunitprice)) {
					$rack_rate = $rdata[0];
				} else {
					$rack_rate = $wgtunitprice;
				}

				$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
				$sql_udata = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"checkout_date"=>$server_get_date,"status"=>$thsStatus);
				mysqli_data_update($tbL127,$sql_udata,$sql_uquery);

				$hk_query = array("roomid"=>$wgtcuroomid);
					$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"startdate"=>$room_ocp_data[8],"endate"=>$server_get_date,"remarks"=>"room status changed upon room up/downgrade by guest","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);

				$hk_query = "";
				$hk_sql = array("room_type"=>$wgtroomtype,"roomid"=>$wgtcuroomid,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room up/downgrade by guest","startdate"=>$room_ocp_data[8],"endate"=>$room_ocp_data[6],"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);

				$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid,"ischarged"=>0);
				$sql_udata = array("deletedata"=>1);
				mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

				//reverse any charges for this room logged for corporate
				if($myBilltype == 'Corporate' && $pst_bill_to > 0) {

					$nocharge_query = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid,"charge_type"=>"corporate","billto"=>$pst_bill_to,"charge"=>"yes","ischarged"=>0,"wkf"=>0,"deletedata"=>1);
					$chkiftr = mysqli_data_checkr($tbL134,'(*)',$nocharge_query);

					if($chkiftr == true) {
						$set_query = "booking_number='{$wgtpstbk}' AND roomid={$wgtcuroomid} AND charge_type='corporate' AND billto={$pst_bill_to} AND charge='yes' AND ischarged=0 AND wkf=0 AND deletedata=1";
						$set_sql_1 = "SUM(room_amount)"; $set_sql_2 = "SUM(discount_amount)"; $set_sql_3 = "SUM(tax_amount)";
						$set_sql_4 = "SUM(consumption_tax_amount)"; $set_sql_5 = "SUM(service_charge)";
						$cspg_room_amount = mysqli_arithmetic_data($tbL134,$set_sql_1,$set_query);
						$cspg_discount_amount = mysqli_arithmetic_data($tbL134,$set_sql_2,$set_query);
						$cspg_tax_amount = mysqli_arithmetic_data($tbL134,$set_sql_3,$set_query);
						$cspg_consumption_amount = mysqli_arithmetic_data($tbL134,$set_sql_4,$set_query);
						$cspg_service_amount = mysqli_arithmetic_data($tbL134,$set_sql_5,$set_query);

						$cspg_nocharge = ($cspg_room_amount - $cspg_discount_amount) + ($cspg_tax_amount + $cspg_consumption_amount + $cspg_service_amount);
						$c_room_prefix = idget_data($tbL56,$wgtcuroomid,'roomprefix');
						$c_room_number = idget_data($tbL56,$wgtcuroomid,'roomnumber');

						$credit_limit = idget_data($tbL58,$pst_bill_to,'creditlimit');
						$credit_notification_limit = idget_data($tbL58,$pst_bill_to,'notifylimit');
						$new_creditlimit = $credit_limit + $cspg_nocharge;

						$blc_selection_key = array("id"=>$pst_bill_to);
						$crl_datasets = array("creditlimit"=>$new_creditlimit);
						mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

						$details = "System reverse charges for unused days upon up/downgrade room ".$c_room_prefix.$c_room_number;
						$cspg_upd = array("cspgid"=>$pst_bill_to,"biller"=>"booking","transaction_number"=>$wgtpstbk,"transaction_type"=>"Credit","amount"=>$cspg_nocharge,"credit_balance"=>$new_creditlimit,"paymode"=>0,"transaction_date"=>$server_get_date,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						mysqli_data_insert($tbL63,$cspg_upd,'');
					}
				}

				//for new room up/downgraded to
				$house_keeping_status = 6;
				$room_status = 3;

				$room_insert_query = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"roomid"=>$wgtroom);
				$sql_insert_data = array("booking_number"=>$wgtpstbk,"customerid"=>$room_ocp_data[0],"room_type_id"=>$wgtpstroomtype,"roomid"=>$wgtroom,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"adult"=>$room_ocp_data[1],"child"=>$room_ocp_data[2],"isextrabed"=>$room_ocp_data[3],"occupancy_type"=>$room_ocp_data[4],"noofdays"=>$daysLeft,"reservation"=>$room_ocp_data[5],"checkin_date"=>$server_get_date,"checkin_time"=>$server_get_time,"checkout_date"=>$room_ocp_data[6],"checkout_time"=>$room_ocp_data[7],"checkin_byuser"=>$userSignedIn,"userid"=>$userSignedIn,"status"=>"CheckedIn","datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL127,$sql_insert_data,$room_insert_query);

				$booking_number = $wgtpstbk;
				$ths_room_id = $wgtroom;
				$wgt_room_type = $wgtpstroomtype;
				$guest_number = $room_ocp_data[0];
				$wgt_start_date = $server_get_date;
				$wgt_end_date = $room_ocp_data[6];

				$wgt_no_of_days = $daysLeft;

				///for housekeeping room update
				$hk_query = array("roomid"=>$ths_room_id);
				$chkIfExist = mysqli_data_checkr($tbL94,'(*)',$hk_query);
				
				if($chkIfExist == true) {
					$hk_sql = array("housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room up/downgrade by guest","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_update($tbL94,$hk_sql,$hk_query);
				} else {
					$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room up/downgrade by guest","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL94,$hk_sql,$hk_query);
				}
				
				$hk_query = "";
				$hk_sql = array("room_type"=>$wgt_room_type,"roomid"=>$ths_room_id,"housekeeping_stateid"=>$house_keeping_status,"room_status_id"=>$room_status,"remarks"=>"room status changed upon room up/downgrade by guest","startdate"=>$wgt_start_date,"endate"=>$wgt_end_date,"userid"=>0,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL95,$hk_sql,$hk_query);
				///end

				//new room charges
				$invoice_number = $rdata[1];

				$wgt_room_rate = $wgtunitprice;
				$xwgt_discount = ($wgtdiscount / 100) * $wgt_room_rate;
				$xwgt_actual_room_rate = $wgt_room_rate - $xwgt_discount;
				$xwgt_tax = ($wgttax / 100) * $xwgt_actual_room_rate;
				$xwgt_consumption = ($wgtconsumption / 100) * $xwgt_actual_room_rate;
				$xwgt_service_charge = ($wgtservicecharge / 100) * $xwgt_actual_room_rate;
				$chargeroom = $rdata[2];

				$wgt_room_rate_2 = $rack_rate;
				$xwgt_tax_2 = ($wgttax / 100) * $wgt_room_rate_2;
				$xwgt_consumption_2 = ($wgtconsumption / 100) * $wgt_room_rate_2;
				$xwgt_service_charge_2 = ($wgtservicecharge / 100) * $wgt_room_rate_2;

				$weekdayBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'nameweekday');
				$dateBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'daterange');

				$charged_days = array();
				$getWkn = "";

				$n_room_amount = 0; $n_tax_amount = 0; $n_service_amount = 0; $n_consumption_amount = 0;
			
				for($i=1; $i <= $wgt_no_of_days; $i++) {
					
					$getWkn = $i - 1;
					$getWk = $weekdayBtwn[$getWkn];
					$getDt = $dateBtwn[$getWkn];

					$n_room_amount = $n_room_amount + $wgt_room_rate;
					$n_tax_amount = $n_tax_amount + $xwgt_tax;
					$n_service_amount = $n_service_amount + $xwgt_service_charge;
					$n_consumption_amount = $n_consumption_amount + $xwgt_consumption;

					$daily_sql = array("charge_type"=>$pst_booking_type,"billto"=>$pst_bill_to,"booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"room_type_id"=>$wgt_room_type,"roomid"=>$ths_room_id,"customerid"=>$guest_number,"day"=>$i,"weekday"=>strtolower($getWk),"actual_room_amount"=>$wgt_room_rate_2,"actual_tax_amount"=>$xwgt_tax_2,"actual_service_charge"=>$xwgt_service_charge_2,"actual_consumption_tax_amount"=>$xwgt_consumption_2,"room_amount"=>$wgt_room_rate,"tax_amount"=>$xwgt_tax,"consumption_tax_amount"=>$xwgt_consumption,"service_charge"=>$xwgt_service_charge,"discount_amount"=>$xwgt_discount,"charge"=>"yes","bill_date"=>$getDt,"status"=>"Pending","room_status"=>"CheckedIn","userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL134,$daily_sql,'');

					$edl = array();

					if($getWk == 'friday' || $getWk == 'Friday' || $getWk == 'saturday' || $getWk == 'Saturday' || $getWk == 'sunday' || $getWk == 'Sunday') {
						
						$edl['bookingno'] = $booking_number;
						$edl['roomtype'] = $wgt_room_type;
						$edl['roomid'] = $ths_room_id;
						$edl['customerid'] = $guest_number;
						$edl['dayname'] = $getWk;

						array_push($charged_days,$edl);
					}

					$getWk = ""; $getDt = "";
				}

				#if weekend rate applies, update the record

				if((isset($pst_wkf) && $pst_wkf == 'Yes') && (is_array($charged_days) && count($charged_days) > 0)) {
					for($r=0; $r < count($charged_days); $r++) {

						$wkfprice_query = array("room_type_id"=>$charged_days[$r]['roomtype'],"day"=>$charged_days[$r]['dayname'],"status"=>"Active","deletedata"=>0); $wkfprice = mysqli_data_fetch($tbL146,'price',$wkfprice_query,'noarray');

						$price = $wkfprice[0];
						$wkftax = ($wgttax / 100) * $price;
						$wkfservicecharges = ($wgtservicecharge / 100) * $price;
						$wkfconsumption = ($wgtconsumption / 100) * $price;

						$day_query = array("booking_number"=>$charged_days[$r]['bookingno'],"roomid"=>$charged_days[$r]['roomid'],"customerid"=>$charged_days[$r]['customerid'],"weekday"=>$charged_days[$r]['dayname'],"ischarged"=>0);
						$day_sql = array("room_amount"=>$price,"discount_amount"=>0,"tax_amount"=>$wkftax,"consumption_tax_amount"=>$wkfconsumption,"service_charge"=>$wkfservicecharges,"wkf"=>7);
						
						mysqli_data_update($tbL134,$day_sql,$day_query);

						$price = ""; $wkftax = ""; $wkfservicecharges = ""; $wkfconsumption = "";
					}
				}

				#end

				$room_prefix = idget_data($tbL56,$ths_room_id,'roomprefix');
				$room_number = idget_data($tbL56,$ths_room_id,'roomnumber');

				//charge corporate for the new bill
				$cspg_charges = $n_room_amount + $n_tax_amount + $n_service_amount + $n_consumption_amount;

				if($myBilltype == 'Corporate' && $pst_bill_to > 0) {

					$credit_limit = idget_data($tbL58,$pst_bill_to,'creditlimit');
					$credit_notification_limit = idget_data($tbL58,$pst_bill_to,'notifylimit');
					$new_creditlimit = $credit_limit - $cspg_charges;

					$blc_selection_key = array("id"=>$pst_bill_to);
					$crl_datasets = array("creditlimit"=>$new_creditlimit);
					mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

					$details = "Room charge (".$room_prefix.$room_number.")";
					$cspg_upd = array("cspgid"=>$pst_bill_to,"biller"=>"booking","transaction_number"=>$booking_number,"transaction_type"=>"Debit","amount"=>$cspg_charges,"credit_balance"=>$new_creditlimit,"paymode"=>0,"transaction_date"=>$server_get_date,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					mysqli_data_insert($tbL63,$cspg_upd,'');
				}

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Room ".$actionChanged." was completed successfully. Use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest room (".$wgtcuroom.") was ".strtolower($thsStatus)." to (".$room_prefix.$room_number.") by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$remark_tag = "room tariff"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
				$guestAct_msg = "Guest room (".$wgtcuroom.") was ".strtolower($thsStatus)." to (".$room_prefix.$room_number."). New tariff: ".$wgt_room_rate;
			}

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Ensure guest have more days";
		}
		
	}

	#end 7:

	#roomtype tariffchange
	if(isset($_POST['roomtariffchangebutton']) && isset($_POST['wgtroom']) && $_POST['wgtroom'] >= 1) {
		
		$wgtroom = $_POST['wgtroom'];
		$wgtcuroom = $_POST['wgtcuroom'];
		$wgtcuroomid = $_POST['wgtcuroomid'];
		$wgtroomtype = $_POST['wgtroomtype'];
		$wgtpstroomtype = $_POST['wgtpstroomtype'];
		$wgtpstroomtype2 = $_POST['wgtpstroomtype2'];
		$wgtpstbk = $_POST['wgtpstbk'];

		$wgtunitprice = $_POST['wgtunitprice'];
		$wgtdiscount = $_POST['wgtdiscount'];
		$wgttax = $_POST['wgttax'];
		$wgtservicecharge = $_POST['wgtservicecharge'];
		$wgtconsumption = $_POST['wgtconsumption'];

		$pst_booking_type = idget_fdata($tbL130,'booking_number',$wgtpstbk,'booking_type');
		$pst_bill_to = idget_fdata($tbL130,'booking_number',$wgtpstbk,'bill_to');
		$pst_bill_to2 = idget_fdata($tbL130,'booking_number',$wgtpstbk,'bill_to_g');

		$rack_rate = 0;

		if($pst_booking_type == 'corporate' && ($pst_bill_to >= 1 || $pst_bill_to2 >= 1)) {
			
			$bill2cspg = (!empty($pst_bill_to) && $pst_bill_to > 0) ? $pst_bill_to : $pst_bill_to2;
			$cspg_charge_type = idget_data($tbL58,$bill2cspg,'chargetype');
			
			if($cspg_charge_type == 'Corporate Tariff') {
			
				$weekday = date('l',strtotime($server_get_date));
				$weekday = strtolower($weekday);

				$json_constrain = array("corporateid"=>$bill2cspg,"room_type_id"=>$wgtpstroomtype2,"ratetype"=>"naira","day"=>$weekday,"status"=>"Active","deletedata"=>0); $corporate_price = mysqli_data_fetch($tbL147,'price',$json_constrain,'noarray');
				$rack_rate = $corporate_price[0];
				$cspg_discount = 0;

			} elseif($cspg_charge_type == 'On Discount') {
				
				$rack_rate = idget_data($tbL52,$wgtpstroomtype2,'defaultprice');
				$cspg_discount = idget_data($tbL58,$bill2cspg,'discount');
			}

			if(!empty($pst_bill_to) && $pst_bill_to >= 1) {
			
				#return active days bill remain

				$sqlset1 = "SUM(room_amount)";
				$sqlset2 = "SUM(discount_amount)";
				$sqlset3 = "SUM(tax_amount)";
				$sqlset4 = "SUM(consumption_tax_amount)";
				$sqlset5 = "SUM(service_charge)";

				$queryset = "booking_number='{$wgtpstbk}' AND roomid={$wgtcuroomid} AND ischarged=0 AND deletedata=0";
				
				$wgt_total_room_tariff = mysqli_arithmetic_data($tbL134,$sqlset1,$queryset);
				$wgt_total_room_discount = mysqli_arithmetic_data($tbL134,$sqlset2,$queryset);
				$wgt_total_room_tax = mysqli_arithmetic_data($tbL134,$sqlset3,$queryset);
				$wgt_total_room_consumption = mysqli_arithmetic_data($tbL134,$sqlset4,$queryset);
				$wgt_total_room_servicecharge = mysqli_arithmetic_data($tbL134,$sqlset5,$queryset);

				$wgt_total_tariff = ($wgt_total_room_tariff - $wgt_total_room_discount) + ($wgt_total_room_tax + $wgt_total_room_consumption + $wgt_total_room_servicecharge);

				//$room_prefix = idget_data($tbL56,$wgtcuroomid,'roomprefix');
				//$room_number = idget_data($tbL56,$wgtcuroomid,'roomnumber');

				$credit_limit = idget_data($tbL58,$pst_bill_to,'creditlimit');
				$credit_notification_limit = idget_data($tbL58,$pst_bill_to,'notifylimit');
				$new_creditlimit = $credit_limit + $wgt_total_tariff;

				$blc_selection_key = array("id"=>$pst_bill_to);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

				$details = "Room-type tariff adjustment for room (".$wgtcuroom.")";
				$cspg_upd = array("cspgid"=>$pst_bill_to,"biller"=>"booking","transaction_number"=>$wgtpstbk,"transaction_type"=>"Credit","amount"=>$wgt_total_tariff,"credit_balance"=>$new_creditlimit,"paymode"=>0,"transaction_date"=>$server_get_date,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);

				mysqli_data_insert($tbL63,$cspg_upd,'');
			}

		} else {
			$rack_rate = idget_data($tbL52,$wgtpstroomtype2,'defaultprice');
			$cspg_discount = 0;
		}

		$additionalQuery = " AND status IN('CheckedIn','Reserved')";
		$query_r = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
		$isStatOn = mysqli_data_checkr($tbL127,'(*)',$query_r);
		
		if($isStatOn == true) {

			$additionalQuery = "";
			$wgt_room_rate = $rack_rate;

			$wgt_discount = ($cspg_discount / 100) * $wgt_room_rate;
			$wgt_actual_room_rate = $wgt_room_rate - $wgt_discount;
			$wgt_tax = ($gh_get_vat / 100) * $wgt_actual_room_rate;
			$wgt_consumption = ($gh_get_consumption_tax / 100) * $wgt_actual_room_rate;
			$wgt_service_charge = ($gh_get_service_charge / 100) * $wgt_actual_room_rate;

			$rtf_query = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid,"ischarged"=>0,"deletedata"=>0);
			$daily_sql = array("actual_room_amount"=>$wgt_room_rate,"actual_tax_amount"=>$wgt_tax,"actual_service_charge"=>$wgt_service_charge,"actual_consumption_tax_amount"=>$wgt_consumption,"room_amount"=>$wgt_room_rate,"tax_amount"=>$wgt_tax,"consumption_tax_amount"=>$wgt_consumption,"service_charge"=>$wgt_service_charge,"discount_amount"=>$wgt_discount);
			
			$result = mysqli_data_update($tbL134,$daily_sql,$rtf_query);

			sleep(5);

			if(!empty($pst_bill_to) && $pst_bill_to >= 1) {
			
				#return active days bill remain

				$sqlset1 = "SUM(room_amount)";
				$sqlset2 = "SUM(discount_amount)";
				$sqlset3 = "SUM(tax_amount)";
				$sqlset4 = "SUM(consumption_tax_amount)";
				$sqlset5 = "SUM(service_charge)";

				$queryset = "booking_number='{$wgtpstbk}' AND roomid={$wgtcuroomid} AND ischarged=0 AND deletedata=0";
				
				$wgt_total_room_tariff = mysqli_arithmetic_data($tbL134,$sqlset1,$queryset);
				$wgt_total_room_discount = mysqli_arithmetic_data($tbL134,$sqlset2,$queryset);
				$wgt_total_room_tax = mysqli_arithmetic_data($tbL134,$sqlset3,$queryset);
				$wgt_total_room_consumption = mysqli_arithmetic_data($tbL134,$sqlset4,$queryset);
				$wgt_total_room_servicecharge = mysqli_arithmetic_data($tbL134,$sqlset5,$queryset);

				$wgt_total_tariff = ($wgt_total_room_tariff - $wgt_total_room_discount) + ($wgt_total_room_tax + $wgt_total_room_consumption + $wgt_total_room_servicecharge);

				//$room_prefix = idget_data($tbL56,$wgtcuroomid,'roomprefix');
				//$room_number = idget_data($tbL56,$wgtcuroomid,'roomnumber');

				$credit_limit = idget_data($tbL58,$pst_bill_to,'creditlimit');
				$credit_notification_limit = idget_data($tbL58,$pst_bill_to,'notifylimit');
				$new_creditlimit = $credit_limit - $wgt_total_tariff;

				$blc_selection_key = array("id"=>$pst_bill_to);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

				$details = "New room-type tariff charge for room (".$wgtcuroom.")";
				$cspg_upd = array("cspgid"=>$pst_bill_to,"biller"=>"booking","transaction_number"=>$wgtpstbk,"transaction_type"=>"Debit","amount"=>$wgt_total_tariff,"credit_balance"=>$new_creditlimit,"paymode"=>0,"transaction_date"=>$server_get_date,"cheque_number"=>"","detail"=>$details,"userid"=>$userSignedIn,"counter_used"=>$current_counter,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				
				mysqli_data_insert($tbL63,$cspg_upd,'');
			}

			$saynotify = 1;
			$notifytype = 2;

			if(isset($result) && $result == 2) {
			
				$post_header = "Notification";
				$post_message = "Room tariff change completed successfully. Use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest room (".$wgtcuroom.") tariff was changed by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$remark_tag = "room tariff"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
				$guestAct_msg = "Guest room (".$wgtcuroom.") tariff changed to a new tariff: ".$wgt_room_rate." using room-type tariff change";

			} else {
				$post_header = "Notification";
				$post_message = "Your request may not be completed as some information not applicable";
			}

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Ensure room is still checked-in or reserved";
		}
	}

	#end 8:


	#manual tariff change
	if(isset($_POST['manualroomtariffchangebutton']) && isset($_POST['wgtamtcharging']) && is_numeric($_POST['wgtamtcharging'])) {
		
		$wgtamtcharging = str_replace(',','',$_POST['wgtamtcharging']);

		if(isset($wgtamtcharging) && $wgtamtcharging > 0) {
		
			$wgtcuroom = $_POST['wgtcuroom'];
			$wgtcuroomid = $_POST['wgtcuroomid'];
			$wgtroomtype = $_POST['wgtroomtype'];
			$wgtpstbk = $_POST['wgtpstbk'];

			$room_update_query = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);

			$for_disc_data = array("isdiscount"=>1);
			mysqli_data_update($tbL127,$for_disc_data,$room_update_query);

			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$datasets = "room_amount,tax_amount,consumption_tax_amount,service_charge";
			$daily_query = array("booking_number"=>$booking_number,"roomid"=>$ths_room_id,"deletedata"=>0);
			$curdata = mysqli_data_fetch($tbL134,$datasets,$daily_query,'noarray');
			$additionalQuery = "";

			//$tax_percent = ($curdata[1] / $curdata[0]) * 100;
			//$ctax_percent = ($curdata[2] / $curdata[0]) * 100;
			//$sc_percent = ($curdata[3] / $curdata[0]) * 100;

			$new_tax_amount = ($gh_get_vat / 100) * $wgtamtcharging;
			$new_ctax_amount = ($gh_get_consumption_tax / 100) * $wgtamtcharging;
			$new_sc_amount = ($gh_get_service_charge / 100) * $wgtamtcharging;

			$room_update_query2 = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid,"ischarged"=>0,"deletedata"=>0);
			$for_roomamt_data = array("room_amount"=>$wgtamtcharging,"tax_amount"=>$new_tax_amount,"consumption_tax_amount"=>$new_ctax_amount,"service_charge"=>$new_sc_amount,"discount_amount"=>0);
			mysqli_data_update($tbL134,$for_roomamt_data,$room_update_query2);

	
			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Manual tariff change was applied successfully. Use refresh icon in booking to see new update";
			
			$islogfile = 1;
			$logfile_msg = "Guest room (".$wgtcuroom.") was given manual tariff charge of (".$wgtamtcharging.") by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$remark_tag = "room tariff"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
			$guestAct_msg = "Guest room (".$wgtcuroom.") was given manual tariff charge of ".$wgtamtcharging;

			
			/*$sql_uquery = array("booking_number"=>$wgtpstbk,"roomid"=>$wgtcuroomid);
			$room_ocp_datasets = "customerid,adult,child,isextrabed,occupancy_type,reservation,checkout_date,checkout_time,checkin_date";
			$room_ocp_data = mysqli_data_fetch($tbL127,$room_ocp_datasets,$sql_uquery,'noarray');
			
			//get days left for room to be checked out
			$daysLeft = dayDiffs($room_ocp_data[6],$server_get_date);
			
			if(isset($daysLeft) && $daysLeft > 0) {

				$booking_number = $wgtpstbk;
				$ths_room_id = $wgtcuroomid;
				$wgt_room_type = $wgtroomtype;
				$guest_number = $room_ocp_data[0];
				$wgt_start_date = $server_get_date;
				$wgt_end_date = $room_ocp_data[6];

				$wgt_no_of_days = $daysLeft;

				$weekdayBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'nameweekday');
				$dateBtwn = getWeekdays($wgt_start_date,$wgt_end_date,'daterange');

				$getWkn = "";
			
				for($i=1; $i <= $wgt_no_of_days; $i++) {
					
					$getWkn = $i - 1;
					$getWk = $weekdayBtwn[$getWkn];
					$getDt = $dateBtwn[$getWkn];

					$datasets = "room_amount,tax_amount,consumption_tax_amount,service_charge";
					$daily_query = array("booking_number"=>$booking_number,"roomid"=>$ths_room_id,"weekday"=>strtolower($getWk));
					$curdata = mysqli_data_fetch($tbL134,$datasets,$daily_query,'noarray');

					$tax_percent = ($curdata[1] / $curdata[0]) * 100;
					$ctax_percent = ($curdata[2] / $curdata[0]) * 100;
					$sc_percent = ($curdata[3] / $curdata[0]) * 100;

					$new_tax_amount = ($tax_percent / 100) * $wgtamtcharging;
					$new_ctax_amount = ($ctax_percent / 100) * $wgtamtcharging;
					$new_sc_amount = ($sc_percent / 100) * $wgtamtcharging;

					$daily_sql = array("room_amount"=>$wgtamtcharging,"tax_amount"=>$new_tax_amount,"consumption_tax_amount"=>$new_ctax_amount,"service_charge"=>$new_sc_amount,"discount_amount"=>0);
					$isdata = mysqli_data_update($tbL134,$daily_sql,$daily_query);

					$tax_percent = ""; $ctax_percent = ""; $sc_percent = "";
					$getWk = ""; $getDt = "";
				}

				$sql_insert_data = array("isdiscount"=>1);
				$room_insert_query = array("booking_number"=>$booking_number,"roomid"=>$ths_room_id);
				mysqli_data_update($tbL127,$sql_insert_data,$room_insert_query);

				$room_prefix = idget_data($tbL56,$ths_room_id,'roomprefix');
				$room_number = idget_data($tbL56,$ths_room_id,'roomnumber');

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Tariff change was applied successfully. Use refresh icon to see new update";
				
				$islogfile = 1;
				$logfile_msg = "Guest room (".$wgtcuroom.") was given manual tariff charge of (".$wgtamtcharging.") by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$guestAct_msg = "Guest room (".$wgtcuroom.") was given manual tariff charge of ".$wgtamtcharging;

			} else {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Unable to complete request. Ensure guest have more days";
			}*/

		} else {
			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Ensure amount is specified correctly";
		}
	}

	#end 9:


	#reverse booking

	if(isset($_POST['reversebooking']) && isset($_POST['checkers']) && (isset($_POST['checkout']) && !empty($_POST['checkout']))) {
		$booking_number = $_POST['bookingnumber'];
		$new_checkout_date = $_POST['checkout'];

		$iseffected = 0; $effectedroom = "";

		foreach($_POST['checkers'] as $room) {
			$room_payload = explode('#',$room);
			if(strtotime($room_payload[4]) > strtotime($new_checkout_date)) {
				$addQuery = " AND bill_date >= '{$new_checkout_date}'";
				$constrain = array("booking_number"=>$booking_number,"room_type_id"=>$room_payload[2],"roomid"=>$room_payload[3],"customerid"=>$room_payload[1]);

				$result = trash_record($tbL134,$constrain);

				if($result == 2) {
					$pst_query = array("id"=>$room_payload[0]);
					$pst_field = array("checkout_date"=>$new_checkout_date);
					mysqli_data_update($tbL127,$pst_field,$pst_query);

					$iseffected += 1;
					$effectedroom .= $room_payload[5].',';
				}
			}

			$room_payload = ""; $addQuery = ""; $constrain = ""; $result = "";
		}

		if(isset($iseffected) && $iseffected > 0) {

			$effectedroom = substr_replace($effectedroom,'',-1);
			
			$pst_query = array("booking_number"=>$booking_number);
			$pst_field = array("checkout_date"=>$new_checkout_date);
			mysqli_data_update($tbL130,$pst_field,$pst_query);

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Guest booking duration was reversed successfully. Please refresh guest folio for the new update";
			
			$islogfile = 1;
			$logfile_msg = "Guest booking ({$booking_number}) duration was reversed by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "The following room(s) {$effectedroom} duration was reversed to checkout on ".date('d-m-Y',strtotime($new_checkout_date));

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Ensure you specify room and checkout date correctly";
		}
	}

	#---------------------------------------------------------------------------------------------------------------

	##end of post

	

	if($_SERVER["REQUEST_METHOD"] == "GET") {

		//for guest bill-to-room service
		if(isset($_GET['wgtag']) && $_GET['wgtag'] == 'removebilltoroom') {
			
			$new_bs = "";
			$wgt_bs_active = $_GET['r'];
			$wgt1 = $booking_number;
			
			$wgt_billing_services = idget_fdata($tbL130,'booking_number',$booking_number,'billing_services');
			$wgt_bs = explode(',', $wgt_billing_services);

			foreach($wgt_bs as $rs) { if($rs != $wgt_bs_active && $rs != '') { $new_bs .= $rs.','; } }
			$ths_new_bs = substr_replace($new_bs,'',-1,1);

			if(is_numeric($wgt_bs_active)) { $otls = idget_data($tbL14,$wgt_bs_active,'posname'); }

			$sql_uquery = array("booking_number"=>$wgt1);
			$sql_udata = array("billing_services"=>$ths_new_bs);
			if(isset($_GET['cid']) && $_GET['cid'] >= 1) { $isupdate = 2; }
			else { $isupdate = mysqli_data_update($tbL130,$sql_udata,$sql_uquery); }

			if(isset($isupdate) && $isupdate == 2) {

				//$sql_uquery = array("booking_number"=>$wgt1,"primary_guest"=>1);
				$sql_uquery = array("booking_number"=>$wgt1);
				if(isset($_GET['cid']) && $_GET['cid'] >= 1) { $sql_uquery['id'] = $_GET['cid']; }
				$sql_udata = array("billing_services"=>$ths_new_bs);
				$isupdate = mysqli_data_update($tbL102,$sql_udata,$sql_uquery);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "One bill-to-room service removed successfully";
				
				$islogfile = 1;
				$logfile_msg = "One of the bill-to-room service for booking number (".$wgt1.") removed by this user";

				$isguestAct = 1;
				$pst_booking_number = $wgt1;
				$guestAct_msg = "The following outlet(s) ({$otls}) bill-to-room is removed from the guest booking ({$wgt1}) as at ".$server_get_date." ".$server_get_time;
			}

		}
	}

?>