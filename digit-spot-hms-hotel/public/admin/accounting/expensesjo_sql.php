<?php

$exp_jan_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS janExpenses FROM {$tbL153} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_jan_row = mysqli_data_array('assoc',$exp_jan_sql);

$exp_feb_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS febExpenses FROM {$tbL153} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_feb_row = mysqli_data_array('assoc',$exp_feb_sql);

$exp_mar_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS marExpenses FROM {$tbL153} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_mar_row = mysqli_data_array('assoc',$exp_mar_sql);

$exp_apr_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS aprExpenses FROM {$tbL153} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_apr_row = mysqli_data_array('assoc',$exp_apr_sql);

$exp_may_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS mayExpenses FROM {$tbL153} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_may_row = mysqli_data_array('assoc',$exp_may_sql);

$exp_jun_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS junExpenses FROM {$tbL153} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_jun_row = mysqli_data_array('assoc',$exp_jun_sql);

$exp_jul_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS julExpenses FROM {$tbL153} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_jul_row = mysqli_data_array('assoc',$exp_jul_sql);

$exp_aug_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS augExpenses FROM {$tbL153} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_aug_row = mysqli_data_array('assoc',$exp_aug_sql);

$exp_sep_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS sepExpenses FROM {$tbL153} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_sep_row = mysqli_data_array('assoc',$exp_sep_sql);

$exp_oct_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS octExpenses FROM {$tbL153} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_oct_row = mysqli_data_array('assoc',$exp_oct_sql);

$exp_nov_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS novExpenses FROM {$tbL153} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_nov_row = mysqli_data_array('assoc',$exp_nov_sql);

$exp_dec_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS decExpenses FROM {$tbL153} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";
$exp_dec_row = mysqli_data_array('assoc',$exp_dec_sql);
?>