<?php

	//hotel registration profile details

	$queryset = "SELECT address,phonenumber1,contactemail,url,country,state,city,tnc,otherinfo,name FROM {$tbL34} WHERE deletedata=0";
	$chkSql = mysqli_query($mysqli,$queryset);
	$hotel_data = mysqli_fetch_array($chkSql,MYSQLI_ASSOC);

	if(isset($hotel_data['address']) && !empty($hotel_data['address'])) { $hotel_address = $hotel_data['address']; }
	else { $hotel_address = ''; }

	if(isset($hotel_data['phonenumber1']) && !empty($hotel_data['phonenumber1'])) { $hotel_fs_phonenumber = $hotel_data['phonenumber1']; }
	else { $hotel_fs_phonenumber = ''; }

	if(isset($hotel_data['contactemail']) && !empty($hotel_data['contactemail'])) { $hotel_email = $hotel_data['contactemail']; }
	else { $hotel_email = ''; }

	if(isset($hotel_data['url']) && !empty($hotel_data['url'])) { $hotel_url = $hotel_data['url']; }
	else { $hotel_url = ''; }

	if(isset($hotel_data['country']) && !empty($hotel_data['country'])) { $hotel_country = $hotel_data['country']; }
	else { $hotel_country = ''; }

	if(isset($hotel_data['state']) && !empty($hotel_data['state'])) { $hotel_state = $hotel_data['state']; }
	else { $hotel_state = ''; }

	if(isset($hotel_data['city']) && !empty($hotel_data['city'])) { $hotel_city = $hotel_data['city']; }
	else { $hotel_city = ''; }

	if(isset($hotel_data['tnc']) && !empty($hotel_data['tnc'])) { $hotel_terms = $hotel_data['tnc']; }
	else { $hotel_terms = ''; }

	if(isset($hotel_data['otherinfo']) && !empty($hotel_data['otherinfo'])) { $hotel_otherinfo = $hotel_data['otherinfo']; }
	else { $hotel_otherinfo = ''; }

	if(isset($hotel_data['name']) && !empty($hotel_data['name'])) { $hotel_name = $hotel_data['name']; }
	else { $hotel_name = 'Hospitality Management Solutions'; }

?>