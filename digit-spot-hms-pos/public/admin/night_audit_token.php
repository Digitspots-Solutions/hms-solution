<?php
	$night_audit_time = $server_get_date.' '.$gh_get_night_audit_hr.':'.$gh_get_night_audit_min.':00';
	$systime = $server_get_date.' '.$server_get_time;
	$timediffr = daytimeDiffs($systime,$night_audit_time);
	$timeArr = json_encode($timediffr);
?>