<?php
	
	$shift_htmlresult="";
	$additionalQuery="";

	$shift_dataproperty = "id,shiftname,startime,startimelabel,endtime,endtimelabel";
	$shift_query = array("deletedata"=>0,"status"=>"Active");
	$shift_data = mysqli_data_fetch($tbL20,$shift_dataproperty,$shift_query,'array');

	if(is_array($shift_data)) {
		foreach ($shift_data as $shift_key => $shift_value) {
			$shift_htmlresult .='<option value="'.$shift_value["id"].'">'.$shift_value["shiftname"].' ('.$shift_value["startimelabel"].' - '.$shift_value["endtimelabel"].')</option>';
		}
	} else {
		$shift_htmlresult ='<option value="">No available shift time</option>';
	}

?>