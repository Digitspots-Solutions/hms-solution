<?php
//dynamic array variable queryselector
//Copyright 2022

include "../includes/config.php";
include "../../../../includes/uom.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if(isset($_POST['qpst']) && isset($_POST['kywdata'])) {
		
		$qps = remove_data_injection($_POST['qpst']);

    	if($qps === 'array-get-list') {
	    	
	    	$request_data = stripslashes($_POST['kywdata']);
			$wgt_json = json_decode($request_data);

			$arryname = $wgt_json->arryname;
			$type = $wgt_json->type;

			$real_arryname = $$arryname;

			if($type == 1) {
				$response_data = $real_arryname;
			} elseif($type == 2) {
				$response_data = array();
				if(is_array($real_arryname)) {
					foreach($real_arryname as $key => $val) {
						$eachinarry = array();
						$eachinarry['ky'] = $key;
						$eachinarry['vl'] = $val;
						array_push($response_data,$eachinarry);
					}
				}
			}

		} elseif($qps === 'array-get-only-key-exist') {

			$request_data = stripslashes($_POST['kywdata']);
			$wgt_json = json_decode($request_data);

			$arryname = $wgt_json->arryname;
			$keys = $wgt_json->keys;

			$real_arryname = $$arryname;
			$response_data = array();

			if(is_array($real_arryname)) {
				
				//$arkeys = explode('-',$keys);
				//$index = 0;

				$response_data['success'] = 200;

				foreach($real_arryname as $key => $val) {
					if($keys == $key) { $response_data['dataval'] = $val; }
				}
			}
		}

		$doJSON = json_encode($response_data);
		echo $doJSON;
	}
}

?>