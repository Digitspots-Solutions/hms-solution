<?php

$ar_jan_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND isreversed=0 AND deletedata=0";

$ar_jan_row = mysqli_data_array('assoc',$ar_jan_sql);
$jan_ar = $ar_jan_row[0]['totaldebit'] - $ar_jan_row[0]['totalcredit'];


$ar_feb_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND isreversed=0 AND deletedata=0";

$ar_feb_row = mysqli_data_array('assoc',$ar_feb_sql);
$feb_ar = $ar_feb_row[0]['totaldebit'] - $ar_feb_row[0]['totalcredit'];


$ar_mar_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND isreversed=0 AND deletedata=0";

$ar_mar_row = mysqli_data_array('assoc',$ar_mar_sql);
$mar_ar = $ar_mar_row[0]['totaldebit'] - $ar_mar_row[0]['totalcredit'];


$ar_apr_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND isreversed=0 AND deletedata=0";

$ar_apr_row = mysqli_data_array('assoc',$ar_apr_sql);
$apr_ar = $ar_apr_row[0]['totaldebit'] - $ar_apr_row[0]['totalcredit'];


$ar_may_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND isreversed=0 AND deletedata=0";

$ar_may_row = mysqli_data_array('assoc',$ar_may_sql);
$may_ar = $ar_may_row[0]['totaldebit'] - $ar_may_row[0]['totalcredit'];


$ar_jun_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND isreversed=0 AND deletedata=0";

$ar_jun_row = mysqli_data_array('assoc',$ar_jun_sql);
$jun_ar = $ar_jun_row[0]['totaldebit'] - $ar_jun_row[0]['totalcredit'];


$ar_jul_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND isreversed=0 AND deletedata=0";

$ar_jul_row = mysqli_data_array('assoc',$ar_jul_sql);
$jul_ar = $ar_jul_row[0]['totaldebit'] - $ar_jul_row[0]['totalcredit'];


$ar_aug_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND isreversed=0 AND deletedata=0";

$ar_aug_row = mysqli_data_array('assoc',$ar_aug_sql);
$aug_ar = $ar_aug_row[0]['totaldebit'] - $ar_aug_row[0]['totalcredit'];


$ar_sep_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND isreversed=0 AND deletedata=0";

$ar_sep_row = mysqli_data_array('assoc',$ar_sep_sql);
$sep_ar = $ar_sep_row[0]['totaldebit'] - $ar_sep_row[0]['totalcredit'];


$ar_oct_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND isreversed=0 AND deletedata=0";

$ar_oct_row = mysqli_data_array('assoc',$ar_oct_sql);
$oct_ar = $ar_oct_row[0]['totaldebit']  - $ar_oct_row[0]['totalcredit'];


$ar_nov_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND isreversed=0 AND deletedata=0";

$ar_nov_row = mysqli_data_array('assoc',$ar_nov_sql);
$nov_ar = $ar_nov_row[0]['totaldebit'] - $ar_nov_row[0]['totalcredit'];


$ar_dec_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND isreversed=0 AND deletedata=0";

$ar_dec_row = mysqli_data_array('assoc',$ar_dec_sql);
$dec_ar = $ar_dec_row[0]['totaldebit'] - $ar_dec_row[0]['totalcredit'];

?>