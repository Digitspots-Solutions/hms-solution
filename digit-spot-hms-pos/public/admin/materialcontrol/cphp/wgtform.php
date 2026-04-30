<?php
//form security token
//Copyright 2022

include "../includes/config.php";

if($_SERVER["REQUEST_METHOD"] == "GET") {

	if(isset($_GET['form']) && $_GET['form'] == 'xform-security-token') {
		
		$response_data = array();

		$fpath = $rootfile;
		$json = json_decode(file_get_contents($fpath.'cphp/packet.json'), true);
		$pkt = $json['jwt'];

		shuffle($pkt);
		$token = hashToken($pkt[0]);

		$response_data['success'] = 200;
		$response_data['token'] = $token;
		
		$wbjson = json_encode($response_data);
		echo $wbjson;
	}
}

?>