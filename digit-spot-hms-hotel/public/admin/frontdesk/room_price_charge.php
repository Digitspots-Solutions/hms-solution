<?php

	if(isset($pst_action) && $pst_action == 'cancelroombutton')
	{
		if(isset($pst_booking_type) &&  ($pst_booking_type == 1 || $pst_booking_type == 2 || $pst_booking_type == 3 || $pst_booking_type == 4)) {

			$defaultAmount = idget_data($tbL52,$roomtype,'defaultprice');

			if(isset($pst_booking_type) && $pst_booking_type == 2) {
				
				$guest_bill_to = $get_biller[0];
				$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0);
				$corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
				
				if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
					if($corporate_tariff_settings[1] == 'Amount') {
						$unitprice = $corporate_tariff_settings[2];
						$totalprice = ($unitprice * 1) * 1;
						$discount = 0;

						$consumption_tax = 0;
						$value_added_tax = 0;
						$service_charge = 0;
						$other_charges = 0;

					} elseif($corporate_tariff_settings[1] == 'Percentage') {
						$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
						$unitprice = $defaultAmount - $inPercent;
						$totalprice = ($unitprice * 1) * 1;
						$discount = $inPercent;

						$consumption_tax = 0;
						$value_added_tax = 0;
						$service_charge = 0;
						$other_charges = 0;
					}
				} else {
					$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
					$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
					$unitprice = $defaultAmount - $inPercent;
					$totalprice = ($unitprice * 1) * 1;
					$discount = $inPercent;

					$consumption_tax = 0;
					$value_added_tax = 0;
					$service_charge = 0;
					$other_charges = 0;
				}

			} else {
				$unitprice = $defaultAmount;
				$totalprice = $defaultAmount;
				$discount = 0;

				$consumption_tax = 0;
				$value_added_tax = 0;
				$service_charge = 0;
				$other_charges = 0;
			}

			$get_cancellation_policy = mysqli_data_fetch($tbL127,'cancel_policy',$check_status_room,'noarray');
			$cancellation_policy_id = $get_cancellation_policy[0];

			if(isset($cancellation_policy_id) && $cancellation_policy_id != 999) {
				$cancellation_discount_charge = idget_data($tbL31,$cancellation_policy_id,'discount');
				$bill_amount = $totalprice + $consumption_tax + $value_added_tax + $service_charge;
				$actual_charges = ($cancellation_discount_charge / 100) * $bill_amount;
				$actual_discount = $discount;
			} else {
				$actual_charges = 0;
				$actual_discount = 0;
			}
			

			#insert charges 
			$cr_invoice_dataproperty = array("booking_number"=>$pst_booking_number,"invoice_number"=>$get_invoice_number,"userid"=>$userSignedIn,"roomid"=>$roomnumber[$i],"customerid"=>$this_primary_guest_id,"booking_type"=>$pst_booking_type,"sub_total"=>$actual_charges,"discount_amount"=>$actual_discount,"tax_amount"=>$value_added_tax,"consumption_tax_amount"=>$consumption_tax,"service_charge"=>$service_charge,"other_charges"=>$other_charges,"bill_amount"=>$actual_charges,"bill_date"=>$server_get_date,"bill_time"=>$server_get_time,"status"=>"Cancellation Charges","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL134,$cr_invoice_dataproperty,'');
		}
	}
	

	#-----------------------------------------------------------------------------------------


	if(isset($pst_action) && $pst_action == 'checkoutbutton')
	{
		if(isset($pst_booking_type) &&  ($pst_booking_type == 1 || $pst_booking_type == 2 || $pst_booking_type == 3 || $pst_booking_type == 4)) {

			$defaultAmount = idget_data($tbL52,$roomtype,'defaultprice');

			if(isset($pst_booking_type) && $pst_booking_type == 2) {
				
				$guest_bill_to = $get_biller[0];
				$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0);
				$corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
				
				if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
					if($corporate_tariff_settings[1] == 'Amount') {
						$unitprice = $corporate_tariff_settings[2];
						$totalprice = ($unitprice * 1) * 1;
						$discount = 0;

						$consumption_tax = 0;
						$value_added_tax = 0;
						$service_charge = 0;
						$other_charges = 0;

					} elseif($corporate_tariff_settings[1] == 'Percentage') {
						$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
						$unitprice = $defaultAmount - $inPercent;
						$totalprice = ($unitprice * 1) * 1;
						$discount = $inPercent;

						$consumption_tax = 0;
						$value_added_tax = 0;
						$service_charge = 0;
						$other_charges = 0;
					}
				} else {
					$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
					$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
					$unitprice = $defaultAmount - $inPercent;
					$totalprice = ($unitprice * 1) * 1;
					$discount = $inPercent;

					$consumption_tax = 0;
					$value_added_tax = 0;
					$service_charge = 0;
					$other_charges = 0;
				}

			} else {
				$unitprice = $defaultAmount;
				$totalprice = $defaultAmount;
				$discount = 0;

				$consumption_tax = 0;
				$value_added_tax = 0;
				$service_charge = 0;
				$other_charges = 0;
			}

			$bill_amount = $totalprice + $consumption_tax + $value_added_tax + $service_charge;
			$actual_charges = ($late_checkout_charges / 100) * $bill_amount;
			$actual_discount = $discount;
			
			#insert charges 
			$cr_invoice_dataproperty = array("booking_number"=>$pst_booking_number,"invoice_number"=>$get_invoice_number,"userid"=>$userSignedIn,"roomid"=>$roomnumber[$i],"customerid"=>$this_primary_guest_id,"booking_type"=>$pst_booking_type,"sub_total"=>$actual_charges,"discount_amount"=>$actual_discount,"tax_amount"=>$value_added_tax,"consumption_tax_amount"=>$consumption_tax,"service_charge"=>$service_charge,"other_charges"=>$other_charges,"bill_amount"=>$actual_charges,"bill_date"=>$server_get_date,"bill_time"=>$server_get_time,"status"=>"Late Checkout Charges","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL134,$cr_invoice_dataproperty,'');
		}
	}

?>