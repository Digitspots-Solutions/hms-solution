<?php

//package types

$package_arry = array(
"Front Office Package"=>1,
"Web Package"=>2,
"Common Package"=>3
);

$ptOpt = '';
//$ptOpt .= '<option value="">Package Type</option>';

foreach ($package_arry as $pt_key => $pt_value) {
	$ptOpt .= '<option value="'.$pt_value.'">'.$pt_key.'</option>';
}

#-----------------------------------------------------------------------------------

	function get_pt($ptid) {
		
		switch ($ptid) {
			case 1:
				$pt_name = "Front Office Package";
				break;
			case 2:
				$pt_name = "Web Package";
				break;
			case 3:
				$pt_name = "Common Package";
				break;
			default:
				$pt_name = "Undefined";
				break;
		}

		return $pt_name;
	}


?>