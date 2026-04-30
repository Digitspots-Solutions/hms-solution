<?php

	$html_query_result_1 = '';
	$html_query_result_2 = '';

	//Get housekeeping status
	$hrs_query = array("deletedata"=>0);
	$additionalQuery = " AND housekeeping_stateid > 0 GROUP BY housekeeping_stateid";
	$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid',$hrs_query,'array');

	if(is_array($hrs_data)) {
		$count_rooms = ""; $count_room_query = ""; $legend = "";
		foreach ($hrs_data as $hrs_key => $hrs_value) {
			$count_room_datasets = "COUNT(roomid)";
			$count_room_query = "housekeeping_stateid = ".$hrs_value['housekeeping_stateid']." AND deletedata = 0";
			$count_rooms = mysqli_arithmetic_data($tbL94,$count_room_datasets,$count_room_query);

			$legend = idget_data($tbL36,$hrs_value['housekeeping_stateid'],'legendname');
			
			$html_query_result_1 .= '<div class="block-element bottom-push-3">';
			$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
			$html_query_result_1 .= $legend;
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
			$html_query_result_1 .= $count_rooms;
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '<span class="block-element new-line-space">';
			$html_query_result_1 .= '</span>';
			$html_query_result_1 .= '</div>';
		}

	} else {

		$count_room_datasets = "COUNT(id)";
		$count_room_query = "deletedata = 0 AND roomstatus = 1";
		$count_rooms = mysqli_arithmetic_data($tbL56,$count_room_datasets,$count_room_query);

		$html_query_result_1 .= '<div class="block-element bottom-push-3">';
		$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
		$html_query_result_1 .= $default_housekeeping_legend;
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
		$html_query_result_1 .= $count_rooms;
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '<span class="block-element new-line-space">';
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '</div>';
	}


	//Get room status
	#total room
	$count_troom_datasets = "COUNT(id)";
	$count_troom_query = "deletedata = 0 AND roomstatus = 1";
	$count_trooms = mysqli_arithmetic_data($tbL56,$count_troom_datasets,$count_troom_query);

	#total not available
	$count_ntroom_datasets = "COUNT(id)";
	$count_ntroom_query = "deletedata = 0 AND room_status_id NOT IN(1)";
	$count_ntrooms = mysqli_arithmetic_data($tbL94,$count_ntroom_datasets,$count_ntroom_query);

	$total_rooms_avail = $count_trooms - $count_ntrooms;

	$rs_query = array("deletedata"=>0);
	$additionalQuery = " AND room_status_id > 0 GROUP BY room_status_id";
	$rs_data = mysqli_data_fetch($tbL94,'room_status_id',$rs_query,'array');

	if(is_array($rs_data)) {
		$count_rooms = ""; $count_room_query = ""; $legend = "";
		foreach ($rs_data as $rs_key => $rs_value) {
			$count_room_datasets = "COUNT(roomid)";
			$count_room_query = "room_status_id = ".$rs_value['room_status_id']." AND deletedata = 0";
			$count_rooms = mysqli_arithmetic_data($tbL94,$count_room_datasets,$count_room_query);

			if($rs_value['room_status_id'] == 1) { $count_rooms = $total_rooms_avail; }
			else { $count_rooms = $count_rooms; }
			
			$legend = idget_data($tbL38,$rs_value['room_status_id'],'legendname');

			$html_query_result_2 .= '<div class="block-element bottom-push-3">';
			$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
			$html_query_result_2 .= $legend;
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
			$html_query_result_2 .= $count_rooms;
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '<span class="block-element new-line-space">';
			$html_query_result_2 .= '</span>';
			$html_query_result_2 .= '</div>';
		}
	} else {

		$count_room_datasets = "COUNT(id)";
		$count_room_query = "deletedata = 0 AND roomstatus = 1";
		$count_rooms = mysqli_arithmetic_data($tbL56,$count_room_datasets,$count_room_query);

		$html_query_result_2 .= '<div class="block-element bottom-push-3">';
		$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
		$html_query_result_2 .= $default_room_status_legend;
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
		$html_query_result_2 .= $count_rooms;
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '<span class="block-element new-line-space">';
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '</div>';
	}

?>


<div class="block-element white-theme">
	<div class="block-element box-border-thick pads15 bottom-push-20">
		<h4 class="large">Housekeeping</h4>
		<div class="cs-height-5"></div>
		<?php echo $html_query_result_1; ?>
	</div>
	<div class="block-element box-border-thick pads15">
		<h4 class="large">Current Room Status</h4>
		<div class="cs-height-5"></div>
		<?php echo $html_query_result_2; ?>
	</div>
</div>