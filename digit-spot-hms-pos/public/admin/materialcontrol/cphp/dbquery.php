<?php
include "../includes/initialize_session.php";
include "../includes/config.php";
//header("Access-Control-Allow-Origin: *");

//include "frval.php";


if($_SERVER["REQUEST_METHOD"] == "GET") {
	
	if(isset($_GET['kyw']) && $_GET['kyw'] === 'register-session') {
		
		$_SESSION['centre'] = $_GET['fses']; $_SESSION['centreid'] = $_GET['sses'];
		
		$response = array();
		$response['success'] = 200;

		echo json_encode($response);
	}

	#----------------------------------------------------------------------------------------------------------------------------------------------end
}

?>