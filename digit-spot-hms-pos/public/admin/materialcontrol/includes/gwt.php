<?php

	$atZ = range('A','Z');
	$zt9 = range(0, 9);

	shuffle($atZ);
	shuffle($zt9);

	$new_token = ""; $gwt = "";

	for($i=0; $i < count($atZ); $i++) { $gwt .= $atZ[$i]; }
	for($j=0; $j < count($zt9); $j++) { $gwt .= $zt9[$j]; }

	$new_token = sha1($gwt);
?>