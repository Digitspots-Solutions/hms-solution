<?php

$ch1_jan_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_jan_row = mysqli_data_array('assoc',$ch1_jan_sql);

$ch2_jan_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_jan_row = mysqli_data_array('assoc',$ch2_jan_sql);

$ch3_jan_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_jan_row = mysqli_data_array('assoc',$ch3_jan_sql);

$ch4_jan_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_jan_row = mysqli_data_array('assoc',$ch4_jan_sql);

$janCash = $ch1_jan_row[0]['CashAmount'] + $ch2_jan_row[0]['CashAmount'] + $ch3_jan_row[0]['CashAmount'] + $ch4_jan_row[0]['CashAmount'];

#----end

$ch1_feb_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_feb_row = mysqli_data_array('assoc',$ch1_feb_sql);

$ch2_feb_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_feb_row = mysqli_data_array('assoc',$ch2_feb_sql);

$ch3_feb_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_feb_row = mysqli_data_array('assoc',$ch3_feb_sql);

$ch4_feb_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_feb_row = mysqli_data_array('assoc',$ch4_feb_sql);

$febCash = $ch1_feb_row[0]['CashAmount'] + $ch2_feb_row[0]['CashAmount'] + $ch3_feb_row[0]['CashAmount'] + $ch4_feb_row[0]['CashAmount'];

#----end

$ch1_mar_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_mar_row = mysqli_data_array('assoc',$ch1_mar_sql);

$ch2_mar_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_mar_row = mysqli_data_array('assoc',$ch2_mar_sql);

$ch3_mar_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_mar_row = mysqli_data_array('assoc',$ch3_mar_sql);

$ch4_mar_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_mar_row = mysqli_data_array('assoc',$ch4_mar_sql);

$marCash = $ch1_mar_row[0]['CashAmount'] + $ch2_mar_row[0]['CashAmount'] + $ch3_mar_row[0]['CashAmount'] + $ch4_mar_row[0]['CashAmount'];

#----end

$ch1_apr_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_apr_row = mysqli_data_array('assoc',$ch1_apr_sql);

$ch2_apr_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_apr_row = mysqli_data_array('assoc',$ch2_apr_sql);

$ch3_apr_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_apr_row = mysqli_data_array('assoc',$ch3_apr_sql);

$ch4_apr_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_apr_row = mysqli_data_array('assoc',$ch4_apr_sql);

$aprCash = $ch1_apr_row[0]['CashAmount'] + $ch2_apr_row[0]['CashAmount'] + $ch3_apr_row[0]['CashAmount'] + $ch4_apr_row[0]['CashAmount'];

#----end

$ch1_may_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_may_row = mysqli_data_array('assoc',$ch1_may_sql);

$ch2_may_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_may_row = mysqli_data_array('assoc',$ch2_may_sql);

$ch3_may_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_may_row = mysqli_data_array('assoc',$ch3_may_sql);

$ch4_may_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_may_row = mysqli_data_array('assoc',$ch4_may_sql);

$mayCash = $ch1_may_row[0]['CashAmount'] + $ch2_may_row[0]['CashAmount'] + $ch3_may_row[0]['CashAmount'] + $ch4_may_row[0]['CashAmount'];

#----end

$ch1_jun_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_jun_row = mysqli_data_array('assoc',$ch1_jun_sql);

$ch2_jun_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_jun_row = mysqli_data_array('assoc',$ch2_jun_sql);

$ch3_jun_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_jun_row = mysqli_data_array('assoc',$ch3_jun_sql);

$ch4_jun_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_jun_row = mysqli_data_array('assoc',$ch4_jun_sql);

$junCash = $ch1_jun_row[0]['CashAmount'] + $ch2_jun_row[0]['CashAmount'] + $ch3_jun_row[0]['CashAmount'] + $ch4_jun_row[0]['CashAmount'];

#----end

$ch1_jul_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_jul_row = mysqli_data_array('assoc',$ch1_jul_sql);

$ch2_jul_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_jul_row = mysqli_data_array('assoc',$ch2_jul_sql);

$ch3_jul_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_jul_row = mysqli_data_array('assoc',$ch3_jul_sql);

$ch4_jul_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_jul_row = mysqli_data_array('assoc',$ch4_jul_sql);

$julCash = $ch1_jul_row[0]['CashAmount'] + $ch2_jul_row[0]['CashAmount'] + $ch3_jul_row[0]['CashAmount'] + $ch4_jul_row[0]['CashAmount'];

#----end

$ch1_aug_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_aug_row = mysqli_data_array('assoc',$ch1_aug_sql);

$ch2_aug_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_aug_row = mysqli_data_array('assoc',$ch2_aug_sql);

$ch3_aug_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_aug_row = mysqli_data_array('assoc',$ch3_aug_sql);

$ch4_aug_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_aug_row = mysqli_data_array('assoc',$ch4_aug_sql);

$augCash = $ch1_aug_row[0]['CashAmount'] + $ch2_aug_row[0]['CashAmount'] + $ch3_aug_row[0]['CashAmount'] + $ch4_aug_row[0]['CashAmount'];

#----end

$ch1_sep_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_sep_row = mysqli_data_array('assoc',$ch1_sep_sql);

$ch2_sep_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_sep_row = mysqli_data_array('assoc',$ch2_sep_sql);

$ch3_sep_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_sep_row = mysqli_data_array('assoc',$ch3_sep_sql);

$ch4_sep_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_sep_row = mysqli_data_array('assoc',$ch4_sep_sql);

$sepCash = $ch1_sep_row[0]['CashAmount'] + $ch2_sep_row[0]['CashAmount'] + $ch3_sep_row[0]['CashAmount'] + $ch4_sep_row[0]['CashAmount'];

#----end

$ch1_oct_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_oct_row = mysqli_data_array('assoc',$ch1_oct_sql);

$ch2_oct_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_oct_row = mysqli_data_array('assoc',$ch2_oct_sql);

$ch3_oct_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_oct_row = mysqli_data_array('assoc',$ch3_oct_sql);

$ch4_oct_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_oct_row = mysqli_data_array('assoc',$ch4_oct_sql);

$octCash = $ch1_oct_row[0]['CashAmount'] + $ch2_oct_row[0]['CashAmount'] + $ch3_oct_row[0]['CashAmount'] + $ch4_oct_row[0]['CashAmount'];

#----end

$ch1_nov_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_nov_row = mysqli_data_array('assoc',$ch1_nov_sql);

$ch2_nov_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_nov_row = mysqli_data_array('assoc',$ch2_nov_sql);

$ch3_nov_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_nov_row = mysqli_data_array('assoc',$ch3_nov_sql);

$ch4_nov_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_nov_row = mysqli_data_array('assoc',$ch4_nov_sql);

$novCash = $ch1_nov_row[0]['CashAmount'] + $ch2_nov_row[0]['CashAmount'] + $ch3_nov_row[0]['CashAmount'] + $ch4_nov_row[0]['CashAmount'];

#----end

$ch1_dec_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL131} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND payment_mode=1 AND isreversed=0 AND deletedata=0";
$ch1_dec_row = mysqli_data_array('assoc',$ch1_dec_sql);

$ch2_dec_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL107} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND mode=1 AND isreversed=0 AND deletedata=0";
$ch2_dec_row = mysqli_data_array('assoc',$ch2_dec_sql);

$ch3_dec_sql = "SELECT IFNULL(SUM(amount),0) AS CashAmount FROM {$tbL63} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND paymode=1 AND isreversed=0 AND deletedata=0";
$ch3_dec_row = mysqli_data_array('assoc',$ch3_dec_sql);

$ch4_dec_sql = "SELECT IFNULL(SUM(bill_amount),0) AS CashAmount FROM {$tbL100} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND media=1 AND isreversed=0 AND deletedata=0";
$ch4_dec_row = mysqli_data_array('assoc',$ch4_dec_sql);

$decCash = $ch1_dec_row[0]['CashAmount'] + $ch2_dec_row[0]['CashAmount'] + $ch3_dec_row[0]['CashAmount'] + $ch4_dec_row[0]['CashAmount'];

?>