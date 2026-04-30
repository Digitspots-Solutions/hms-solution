<?php

	$html_query_result_1 = '';
	$html_query_result_2 = '';


	#total todays room checkedin
	$datasets = "COUNT(roomid)";
	$checkin_room_query = "deletedata = 0 AND checkin_date = '{$server_get_date}'";
	$total_checkin = mysqli_arithmetic_data($tbL127,$datasets,$checkin_room_query);

	#total todays room checkedout
	$datasets = "COUNT(roomid)";
	$checkout_room_query = "deletedata = 0 AND checkout_date = '{$server_get_date}'";
	$total_checkout = mysqli_arithmetic_data($tbL127,$datasets,$checkout_room_query);

	#-------------------------------------------------------------------------------------------------------

	$rs_chkin_query = array("deletedata"=>0,"checkin_date"=>$server_get_date);
	$rs_chkin_data = mysqli_data_fetch($tbL127,'roomid,customerid,booking_number',$rs_chkin_query,'array');

	if(is_array($rs_chkin_data)) {
		foreach ($rs_chkin_data as $rs_key => $rs_value) {
			
			$room_prefix = idget_data($tbL56,$rs_value['roomid'],'roomprefix');
			$room_number = idget_data($tbL56,$rs_value['roomid'],'roomnumber');
			$block_id = idget_fdata($tbL56,'id',$rs_value['roomid'],'blockid');
			$block_name = idget_data($tbL49,$block_id,'name');

			$room_type_id = idget_fdata($tbL56,'id',$rs_value['roomid'],'room_type_id');
			$room_type_name = idget_data($tbL52,$room_type_id,'name');

			$guest_salutation = idget_data($tbL102,$rs_value['customerid'],'salutation');
			$salutation = idget_data($tbL42,$guest_salutation,'name');

			$guest_name = $salutation.' '.idget_data($tbL102,$rs_value['customerid'],'fname').' ';
			$guest_name .= idget_data($tbL102,$rs_value['customerid'],'lname');
			

			$html_query_result_1 .= '<div class="block-element bottom-push-5">';
			$html_query_result_1 .= '<span class="block-element bottom-push-3 ft-sml-size">';
			$html_query_result_1 .= $rs_value['booking_number'];
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-60 ft-sml-size">';
			$html_query_result_1 .= $room_prefix.$room_number.' ('.$block_name.')';
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-40 ft-sml-size alignrt">';
			$html_query_result_1 .= $guest_name;
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '<span class="block-element new-line-space">';
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '</div>';

			$room_prefix=""; $room_number=""; $block_id=""; $room_type_id=""; $guest_name="";
		} 

	} else {
		$html_query_result_1 .= '<div class="block-element ft-sml-size bottom-push-5">';
		$html_query_result_1 .= 'Zero checked-in rooms';
		$html_query_result_1 .= '</div>';
	}

	#-------------------------------------------------------------------------------------------------------
	
	$rs_chkout_query = array("deletedata"=>0,"checkout_date"=>$server_get_date);
	$rs_chkout_data = mysqli_data_fetch($tbL127,'roomid,customerid,booking_number',$rs_chkout_query,'array');

	if(is_array($rs_chkout_data)) {
		foreach ($rs_chkout_data as $rs_key => $rs_value) {
			
			$room_prefix = idget_data($tbL56,$rs_value['roomid'],'roomprefix');
			$room_number = idget_data($tbL56,$rs_value['roomid'],'roomnumber');
			$block_id = idget_fdata($tbL56,'id',$rs_value['roomid'],'blockid');
			$block_name = idget_data($tbL49,$block_id,'name');

			$room_type_id = idget_fdata($tbL56,'id',$rs_value['roomid'],'room_type_id');
			$room_type_name = idget_data($tbL52,$room_type_id,'name');

			$guest_salutation = idget_data($tbL102,$rs_value['customerid'],'salutation');
			$salutation = idget_data($tbL42,$guest_salutation,'name');

			$guest_name = $salutation.' '.idget_data($tbL102,$rs_value['customerid'],'fname').' ';
			$guest_name .= idget_data($tbL102,$rs_value['customerid'],'lname');
			

			$html_query_result_2 .= '<div class="block-element bottom-push-5">';
			$html_query_result_2 .= '<span class="block-element bottom-push-3 ft-sml-size">';
			$html_query_result_2 .= $rs_value['booking_number'];
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-60 ft-sml-size">';
			$html_query_result_2 .= $room_prefix.$room_number.' ('.$block_name.')';
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-40 ft-sml-size alignrt">';
			$html_query_result_2 .= $guest_name;
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '<span class="block-element new-line-space">';
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '</div>';

			$room_prefix=""; $room_number=""; $block_id=""; $room_type_id=""; $guest_name="";
		}

	} else {
		$html_query_result_2 .= '<div class="block-element ft-sml-size bottom-push-5">';
		$html_query_result_2 .= 'Zero checked-out rooms';
		$html_query_result_2 .= '</div>';
	}

?>


<div class="block-element white-theme">
	<div class="block-element box-border-thick pads15 bottom-push-20">
		<h4 class="large">Checked-In Rooms (<?php echo $total_checkin; ?>)</h4>
		<div class="cs-height-5"></div>
		<?php echo $html_query_result_1; ?>
	</div>
	<div class="block-element box-border-thick pads15">
		<h4 class="large">Rooms to Check-Out (<?php echo $total_checkout; ?>)</h4>
		<div class="cs-height-5"></div>
		<?php echo $html_query_result_2; ?>
	</div>
</div>