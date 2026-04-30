<?php
	$additionalQuery = " ORDER BY roomnumber ASC";
	$cpn_constrain = array("room_type_id"=>$room_type_id,"deletedata"=>0,"roomstatus"=>1);
    $cpn_data = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$cpn_constrain,'array');
    $additionalQuery = "";

    if(is_array($cpn_data)) {
    	$housekeeping_room_state = ''; $room_availability = '';
    	$getroomlist .='<option value="">Choose?</option>';
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
    			$getroomlist .='<option value="'.$value['id'].'">'.$value['roomprefix'].$value['roomnumber'].' '.$housekeeping_room_state.'</option>';
    		}
    	}
	} else {
		$getroomlist .='<option value="" selected>No Option!</option>';
	}
?>