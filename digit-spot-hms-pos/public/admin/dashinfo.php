<?php

$queryset1 = "status IN('Reserved','CheckedIn') AND datelogged='{$server_get_date}' AND deletedata=0";
$queryset2 = "status IN('CheckedIn') AND datelogged='{$server_get_date}' AND deletedata=0";
$queryset3 = "status IN('CheckedOut') AND datelogged='{$server_get_date}' AND deletedata=0";

$queryset4 = "housekeeping_stateid=1 AND room_status_id=1 AND deletedata=0";
$queryset5 = "housekeeping_stateid=2 AND deletedata=0";

$sqlset = "COUNT(roomid)";

$wgt_total_reservations = mysqli_arithmetic_data($tbL127,$sqlset,$queryset1);
$wgt_total_checkedin = mysqli_arithmetic_data($tbL127,$sqlset,$queryset2);
$wgt_total_checkedout = mysqli_arithmetic_data($tbL127,$sqlset,$queryset3);

$wgt_vacant_rooms = mysqli_arithmetic_data($tbL94,$sqlset,$queryset4);
$wgt_dirty_rooms = mysqli_arithmetic_data($tbL94,$sqlset,$queryset5);

?>