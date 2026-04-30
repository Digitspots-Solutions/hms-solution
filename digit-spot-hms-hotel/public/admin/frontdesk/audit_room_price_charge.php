<?php

	if(isset($pst_booking_type) &&  ($pst_booking_type == 1 || $pst_booking_type == 2 || $pst_booking_type == 3 || $pst_booking_type == 4)) {

		if(isset($pst_booking_type) && $pst_booking_type == 2) {
			
			$guest_bill_to = $get_biller[0];
			$cts_query = array("corporateid"=>$guest_bill_to,"room_type_id"=>$roomtype,"status"=>"Active","deletedata"=>0);
			$corporate_tariff_settings = mysqli_data_fetch($tbL81,'id,tarifftype,tariffamount,discount',$cts_query,'noarray');
			
			if(isset($corporate_tariff_settings[0]) && $corporate_tariff_settings[0] >= 1) {
				if($corporate_tariff_settings[1] == 'Amount') {
					$unitprice = $corporate_tariff_settings[2];
					$totalprice = ($unitprice * 1) * $noofdays;
					$discount = 0;

					$consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice;
					$value_added_tax = ($gh_get_vat / 100) * $totalprice;
					$service_charge = ($gh_get_service_charge / 100) * $totalprice;
					$other_charges = 0;

				} elseif($corporate_tariff_settings[1] == 'Percentage') {
					$inPercent = ($corporate_tariff_settings[3] / 100) * $defaultAmount;
					$unitprice = $defaultAmount - $inPercent;
					$totalprice = ($unitprice * 1) * $noofdays;
					$discount = $inPercent;

					$consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice;
					$value_added_tax = ($gh_get_vat / 100) * $totalprice;
					$service_charge = ($gh_get_service_charge / 100) * $totalprice;
					$other_charges = 0;
				}
			} else {
				$get_corporate_discount = idget_data($tbL58,$guest_bill_to,'discount');
				$inPercent = ($get_corporate_discount / 100) * $defaultAmount;
				$unitprice = $defaultAmount - $inPercent;
				$totalprice = ($unitprice * 1) * $noofdays;
				$discount = $inPercent;

				$consumption_tax = ($gh_get_consumption_tax / 100) * $totalprice;
				$value_added_tax = ($gh_get_vat / 100) * $totalprice;
				$service_charge = ($gh_get_service_charge / 100) * $totalprice;
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
	}

?>