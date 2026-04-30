<?php
	if(isset($_GET['param']) && !empty($_GET['param'])) { 
		
		if(isset($_GET['ftoken']) && !empty($_GET['ftoken'])) { $ftoken = escape_data($_GET['ftoken']); }
		else { $ftoken = null; }

		$stoken = $_GET['stoken'];

		$param = $_GET['param'];
		$wgt_file = str_replace(' ','_',strtolower($param)).'.php';
		include $wgt_file;
	}
?>
