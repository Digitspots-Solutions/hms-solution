<?php

	//hotel registration profile details

	$hotel_selection_key = array("deletedata"=>0);
	$hotel_data = mysqli_data_fetch($tbL34,'address,phonenumber1,contactemail,url,country,state,city,tnc,otherinfo,name',$hotel_selection_key,'noarray');

	if(isset($hotel_data[0]) && !empty($hotel_data[0])) { $hotel_address = $hotel_data[0]; }
	else { $hotel_address = ''; }

	if(isset($hotel_data[1]) && !empty($hotel_data[1])) { $hotel_fs_phonenumber = $hotel_data[1]; }
	else { $hotel_fs_phonenumber = ''; }

	if(isset($hotel_data[2]) && !empty($hotel_data[2])) { $hotel_email = $hotel_data[2]; }
	else { $hotel_email = ''; }

	if(isset($hotel_data[3]) && !empty($hotel_data[3])) { $hotel_url = $hotel_data[3]; }
	else { $hotel_url = ''; }

	if(isset($hotel_data[4]) && !empty($hotel_data[4])) { $hotel_country = $hotel_data[4]; }
	else { $hotel_country = ''; }

	if(isset($hotel_data[5]) && !empty($hotel_data[5])) { $hotel_state = $hotel_data[5]; }
	else { $hotel_state = ''; }

	if(isset($hotel_data[6]) && !empty($hotel_data[6])) { $hotel_city = $hotel_data[6]; }
	else { $hotel_city = ''; }

	if(isset($hotel_data[7]) && !empty($hotel_data[7])) { $hotel_terms = $hotel_data[7]; }
	else { $hotel_terms = ''; }

	if(isset($hotel_data[8]) && !empty($hotel_data[8])) { $hotel_otherinfo = $hotel_data[8]; }
	else { $hotel_otherinfo = ''; }

	if(isset($hotel_data[9]) && !empty($hotel_data[9])) { $hotel_name = $hotel_data[9]; }
	else { $hotel_name = 'Hospitality Management Solutions'; }

?>