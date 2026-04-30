<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";

	
	$run_module = 0;


	//get audit date
	$audit_query = array("status"=>"Pending");
	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$get_audit_data = mysqli_data_fetch($tbL136,'audit_date',$audit_query,'noarray');

	$charge_date = $get_audit_data[0];
	$charge_time = $server_get_time;

	//Charge the rooms that are currently checkedin
	$additionalQuery = "";
	$room_query = array("status"=>"Checked In","deletedata"=>0);
	$get_dataproperty = "booking_number,roomid,isextrabed,occupancy_type,booking_type";
	$get_data = mysqli_data_fetch($tbL127,$get_dataproperty,$room_query,'array');

	if(is_array($get_data)) {
		
		$biller = ""; $guest_bill_to = ""; $unitprice = ""; $totalprice = ""; $discount = ""; $consumption_tax = "";
		$value_added_tax = ""; $service_charge = ""; $other_charges = ""; $bill_amount = ""; $customerid = ""; $roomtype = "";
		$booking_number = ""; $invoice_number = ""; $roomid = ""; $cost_of_extrabed="";

		$noofdays = 1; $perform = 0;

		foreach ($get_data as $gkey => $gvalue) {
			
			$booking_number = $gvalue['booking_number'];
			$invoice_number = idget_fdata($tbL130,'booking_number',$booking_number,'invoice_number');
			$roomid = $gvalue['roomid'];

			$biller = $gvalue['booking_type'];

			//get taxes and charges set for guest
			$tx_charge_1 = idget_fdata($tbL139,'booking_number',$booking_number,'consumption_tax');
			$tx_charge_2 = idget_fdata($tbL139,'booking_number',$booking_number,'service_charge');
			$tx_charge_3 = idget_fdata($tbL139,'booking_number',$booking_number,'vat');

			#if is corporate guest, get corporate id
			$for_cpsg_query = array("booking_number"=>$gvalue['booking_number'],"primary_guest"=>1);
			$cpsg_data = mysqli_data_fetch($tbL102,'id,billto',$for_cpsg_query,'noarray');
			$guest_bill_to = $cpsg_data[1];

			$customerid = $cpsg_data[0];
			$roomtype = idget_data($tbL56,$roomid,'room_type_id');

			#get extrabed cost
			if(isset($gvalue['isextrabed']) && $gvalue['isextrabed'] >= 1) {
				$get_extrabed_price = idget_data($tbL52,$roomtype,'extrabedprice');
				$cost_of_extrabed = $get_extrabed_price * $gvalue['isextrabed'];
			} else {
				$cost_of_extrabed = 0;
			}

			if(isset($biller) && $biller == 2) {
				$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0); $corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');

				$tx_query_fs = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>1,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_fs = mysqli_data_fetch($tbL82,'id',$tx_query_fs,'noarray');
				$tx_query_ss = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>2,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_ss = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');
				$tx_query_ts = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"taxid"=>3,"status"=>"Active","deletedata"=>0); $corporate_tariff_tx_ts = mysqli_data_fetch($tbL82,'id',$tx_query_ss,'noarray');
				
				if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
					if($corporate_tariff_settings[1] == 'Amount') {
						$unitprice = $corporate_tariff_settings[2];
						$totalprice = ($unitprice + $cost_of_extrabed) * $noofdays;
						$discount = 0;

						if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }
				
						if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }
						
						if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $value_added_tax = 0; }
						
						$other_charges = 0;

					} elseif($corporate_tariff_settings[1] == 'Percentage') {
						$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
						$unitprice = $defaultAmount - $inPercent;
						$totalprice = ($unitprice + $cost_of_extrabed) * $noofdays;
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
					$totalprice = ($unitprice + $cost_of_extrabed) * $noofdays;
					$discount = $inPercent;

					if(isset($corporate_tariff_tx_fs[0]) && $corporate_tariff_tx_fs[0] >= 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }
				
					if(isset($corporate_tariff_tx_ss[0]) && $corporate_tariff_tx_ss[0] >= 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }
					
					if(isset($corporate_tariff_tx_ts[0]) && $corporate_tariff_tx_ts[0] >= 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; } else { $value_added_tax = 0; }
					
					$other_charges = 0;
				}

			} else {
				
				$isweektariff = idget_fdata($tbL130,'booking_number',$booking_number,'hotel_season');
				$weektariff_day = idget_fdata($tbL130,'booking_number',$booking_number,'hotel_season_day');
				$isweektariff_disabled = idget_fdata($tbL130,'booking_number',$booking_number,'disable_weekend_fares');

				if($isweektariff_disabled == 'No' && (isset($isweektariff) && $isweektariff >= 1)) {
					//get room charge price
					$tariff_query_1 = array("modeid"=>$isweektariff,"room_type_id"=>$roomtype,"ratetype"=>"adult rate","day"=>$weektariff_day,"status"=>"Active"); $get_cur_tariff1 = mysqli_data_fetch($tbL80,'price',$tariff_query_1,'noarray');
					
					//get extrabed charge
					$tariff_query_2 = array("modeid"=>$isweektariff,"room_type_id"=>$roomtype,"ratetype"=>"extrabed rate","day"=>$weektariff_day,"status"=>"Active"); $get_cur_tariff2 = mysqli_data_fetch($tbL80,'price',$tariff_query_2,'noarray');

					$unitprice = $get_cur_tariff1[0];

					if($cost_of_extrabed > 0) { $cost_of_extrabed = $get_cur_tariff2[0]; }
					else { $cost_of_extrabed = 0; }
					
					$totalprice = ($unitprice + $cost_of_extrabed) * $noofdays;
					$discount = 0;

				} else {
					$unitprice = idget_data($tbL52,$roomtype,'defaultprice');
					$totalprice = ($unitprice + $cost_of_extrabed) * $noofdays;
					$discount = 0;
				}

				if(isset($tx_charge_1) && $tx_charge_1 == 1) { $consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice; } else { $consumption_tax = 0; }

				if(isset($tx_charge_3) && $tx_charge_3 == 1) { $value_added_tax = ($gh_get_vat / 100) * $totalprice; }
				else { $value_added_tax = 0; }

				if(isset($tx_charge_2) && $tx_charge_2 == 1) { $service_charge = ($gh_get_service_charge / 100) * $totalprice; } else { $service_charge = 0; }

				$other_charges = 0;
			}

			$bill_amount = $totalprice + $consumption_tax + $value_added_tax + $service_charge + $other_charges;

			$daily_invoice_dataproperty = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"roomid"=>$roomid,"customerid"=>$customerid,"booking_type"=>$biller,"sub_total"=>$totalprice,"discount_amount"=>$discount,"tax_amount"=>$value_added_tax,"consumption_tax_amount"=>$consumption_tax,"service_charge"=>$service_charge,"other_charges"=>$other_charges,"bill_amount"=>$bill_amount,"bill_date"=>$charge_date,"bill_time"=>$charge_time,"status"=>"Room Occupancy Charges","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); $daily_invoice_constrain = array("booking_number"=>$booking_number,"roomid"=>$roomid,"datelogged"=>$server_get_date);
			$isdata = mysqli_data_insert($tbL134,$daily_invoice_dataproperty,$daily_invoice_constrain);
			if(isset($isdata) && $isdata == 2) { $perform += 1; }
		}

		if(isset($perform) && $perform >= 1) {
			$log_audit_query = array("audit_date"=>$charge_date,"audit_time"=>$server_get_time,"module"=>3);
			$log_audit_data = array("status"=>"Performed");
			mysqli_data_update($tbL137,$log_audit_data,$log_audit_query);
		}

		$run_module = 1;
	}


	if(isset($run_module) && $run_module == 1) {
		$log_audit_query_x = array("audit_date"=>$charge_date,"audit_time"=>$server_get_time);
		$log_audit_data_x = array("status"=>"Performed");
		mysqli_data_update($tbL136,$log_audit_data_x,$log_audit_query_x);

		sessionCloseSid($userSignedIn);
		sessionCloseSid(PAGE_AUTHEN_SID);
		
		?>
			<script>
				window.addEventListener('load',function() {
					window.parent.location.href = "<?php echo DOMAIN_URL.'login/'; ?>";
				},false);
			</script>
		<?php
	}











	#-------------------------------------------

	if(isset($_POST['token'])) {
	//update night audit status
	$night_query = array("audit_date"=>$server_get_date);
	$audit_sql = array("audit_time"=>$server_get_time,"status"=>"Successful");
	mysqli_data_update($tbL136,$audit_sql,$night_query);

	//update rooms charges
	//this will be later removed
	//select rooms that are not cancelled and ischargeable
	$charge_query = array("bill_date"=>$server_get_date,"deletedata"=>0);
	$charge_sql = array("ischarged"=>1,"bill_time"=>$server_get_time,"status"=>"Active");
	mysqli_data_update($tbL134,$charge_sql,$charge_query);

	//update business day
	$bizdy_query = array("day"=>$server_get_bizid);
	$bizdy_sql = array("enddate"=>$server_get_date,"endtime"=>$server_get_time,"status"=>1);
	mysqli_data_update($hdy_table,$bizdy_sql,$bizdy_query);

	//create new business day
	$new_day = $server_get_bizid + 1;
	$bizdy_query2 = array("day"=>$new_day);
	$bizdy_sql2 = array("day"=>$new_day,"startdate"=>$server_get_date,"starttime"=>$server_get_time,"status"=>0);
	mysqli_data_insert($hdy_table,$bizdy_sql2,$bizdy_query2);

	?>
		<script>
			window.addEventListener('load',function() {
				parent.document.getElementById('night-audit-alert').className = 'fx-position-flow fscr zind-1 txp8-black motion noshow';
				window.parent.location.href = filePath+"public/admin/portal.php";
			},false);
		</script>
	<?php
}
	
?>