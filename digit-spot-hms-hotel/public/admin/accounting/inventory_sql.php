<?php

$inv_jan_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_jan_row = mysqli_data_array('assoc',$inv_jan_sql);

$inv_feb_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_feb_row = mysqli_data_array('assoc',$inv_feb_sql);

$inv_mar_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_mar_row = mysqli_data_array('assoc',$inv_mar_sql);

$inv_apr_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_apr_row = mysqli_data_array('assoc',$inv_apr_sql);

$inv_may_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_may_row = mysqli_data_array('assoc',$inv_may_sql);

$inv_jun_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_jun_row = mysqli_data_array('assoc',$inv_jun_sql);

$inv_jul_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_jul_row = mysqli_data_array('assoc',$inv_jul_sql);

$inv_aug_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_aug_row = mysqli_data_array('assoc',$inv_aug_sql);

$inv_sep_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_sep_row = mysqli_data_array('assoc',$inv_sep_sql);

$inv_oct_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_oct_row = mysqli_data_array('assoc',$inv_oct_sql);

$inv_nov_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_nov_row = mysqli_data_array('assoc',$inv_nov_sql);

$inv_dec_sql = "SELECT SUM(order_net_amount) AS AmountIncur FROM {$tbL121} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND order_status='Approved' AND receipt_status='Received' AND deletedata=0";
$inv_dec_row = mysqli_data_array('assoc',$inv_dec_sql);

?>