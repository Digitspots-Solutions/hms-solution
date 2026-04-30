<?php
	
	//get charges from inclusion (as other charges)

	$guest_incl_bill_query = array("booking_number"=>$booking_number,"roomid"=>$room,"deletedata"=>0);
	$get_guest_incl_bill = mysqli_data_fetch($tbL135,'inclusion_id,roomid',$guest_incl_bill_query,'array');
	$incl_bill = 0; $incl_price = ""; $select_room = array();
	if(is_array($get_guest_incl_bill)) {
		foreach ($get_guest_incl_bill as $ggib_key => $ggib_value) {
			$incl_price = idget_data($tbL83,$ggib_value['inclusion_id'],'price');
			$incl_bill = $incl_bill + $incl_price;

			array_push($select_room, $ggib_value['roomid']);
		}
	} else {
		$incl_bill = 0;
	}


	//get total number of times room charged

	$total_occurence = 0;

	if(is_array($select_room)) {
		
		$select_room_unique = array_unique($select_room);
		$ar_query = "";
		
		foreach($select_room_unique as $r) {
			
			$ar_sql = "COUNT(roomid)";
			$ar_query = "booking_number='".$booking_number."' AND roomid='".$r."'";
			$ar_data_result = mysqli_arithmetic_data($tbL134,$ar_sql,$ar_query);

			$total_occurence = $total_occurence + $ar_data_result;
		}
	}

	$actual_incl_bill = $incl_bill * $total_occurence;

?>