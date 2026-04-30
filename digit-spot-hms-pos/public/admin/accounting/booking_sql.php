<?php

$bk_jan_sql = "SELECT IFNULL(SUM(room_amount),0) AS janRevenue FROM {$tbL134} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND ischarged=1 AND deletedata=0";
$bk_jan_row = mysqli_data_array('assoc',$bk_jan_sql);

$bk_feb_sql = "SELECT IFNULL(SUM(room_amount),0) AS febRevenue FROM {$tbL134} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND ischarged=1 AND deletedata=0";
$bk_feb_row = mysqli_data_array('assoc',$bk_feb_sql);

$bk_mar_sql = "SELECT IFNULL(SUM(room_amount),0) AS marRevenue FROM {$tbL134} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND ischarged=1 AND deletedata=0";
$bk_mar_row = mysqli_data_array('assoc',$bk_mar_sql);

$bk_apr_sql = "SELECT IFNULL(SUM(room_amount),0) AS aprRevenue FROM {$tbL134} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND ischarged=1 AND deletedata=0";
$bk_apr_row = mysqli_data_array('assoc',$bk_apr_sql);

$bk_may_sql = "SELECT IFNULL(SUM(room_amount),0) AS mayRevenue FROM {$tbL134} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND ischarged=1 AND deletedata=0";
$bk_may_row = mysqli_data_array('assoc',$bk_may_sql);

$bk_jun_sql = "SELECT IFNULL(SUM(room_amount),0) AS junRevenue FROM {$tbL134} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND ischarged=1 AND deletedata=0";
$bk_jun_row = mysqli_data_array('assoc',$bk_jun_sql);

$bk_jul_sql = "SELECT IFNULL(SUM(room_amount),0) AS julRevenue FROM {$tbL134} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND ischarged=1 AND deletedata=0";
$bk_jul_row = mysqli_data_array('assoc',$bk_jul_sql);

$bk_aug_sql = "SELECT IFNULL(SUM(room_amount),0) AS augRevenue FROM {$tbL134} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND ischarged=1 AND deletedata=0";
$bk_aug_row = mysqli_data_array('assoc',$bk_aug_sql);

$bk_sep_sql = "SELECT IFNULL(SUM(room_amount),0) AS sepRevenue FROM {$tbL134} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND ischarged=1 AND deletedata=0";
$bk_sep_row = mysqli_data_array('assoc',$bk_sep_sql);

$bk_oct_sql = "SELECT IFNULL(SUM(room_amount),0) AS octRevenue FROM {$tbL134} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND ischarged=1 AND deletedata=0";
$bk_oct_row = mysqli_data_array('assoc',$bk_oct_sql);

$bk_nov_sql = "SELECT IFNULL(SUM(room_amount),0) AS novRevenue FROM {$tbL134} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND ischarged=1 AND deletedata=0";
$bk_nov_row = mysqli_data_array('assoc',$bk_nov_sql);

$bk_dec_sql = "SELECT IFNULL(SUM(room_amount),0) AS decRevenue FROM {$tbL134} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND ischarged=1 AND deletedata=0";
$bk_dec_row = mysqli_data_array('assoc',$bk_dec_sql);


#------------------end

$bk_sql = "SELECT IFNULL(SUM(room_amount),0) AS ttRevenue FROM {$tbL134} WHERE datelogged >= '{$jan_sdsx}' AND datelogged <= '{$dec_edsx}' AND ischarged=1 AND deletedata=0";
$bk_row = mysqli_data_array('assoc',$bk_sql);

#------------------summay-end

$bkt_jan_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS janTaxes FROM {$tbL134} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND ischarged=1 AND deletedata=0";
$bkt_jan_row = mysqli_data_array('assoc',$bkt_jan_sql);

$bkt_feb_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS febTaxes FROM {$tbL134} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND ischarged=1 AND deletedata=0";
$bkt_feb_row = mysqli_data_array('assoc',$bkt_feb_sql);

$bkt_mar_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS marTaxes FROM {$tbL134} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND ischarged=1 AND deletedata=0";
$bkt_mar_row = mysqli_data_array('assoc',$bkt_mar_sql);

$bkt_apr_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS aprTaxes FROM {$tbL134} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND ischarged=1 AND deletedata=0";
$bkt_apr_row = mysqli_data_array('assoc',$bkt_apr_sql);

$bkt_may_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS mayTaxes FROM {$tbL134} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND ischarged=1 AND deletedata=0";
$bkt_may_row = mysqli_data_array('assoc',$bkt_may_sql);

$bkt_jun_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS junTaxes FROM {$tbL134} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND ischarged=1 AND deletedata=0";
$bkt_jun_row = mysqli_data_array('assoc',$bkt_jun_sql);

$bkt_jul_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS julTaxes FROM {$tbL134} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND ischarged=1 AND deletedata=0";
$bkt_jul_row = mysqli_data_array('assoc',$bkt_jul_sql);

$bkt_aug_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS augTaxes FROM {$tbL134} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND ischarged=1 AND deletedata=0";
$bkt_aug_row = mysqli_data_array('assoc',$bkt_aug_sql);

$bkt_sep_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS sepTaxes FROM {$tbL134} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND ischarged=1 AND deletedata=0";
$bkt_sep_row = mysqli_data_array('assoc',$bkt_sep_sql);

$bkt_oct_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS octTaxes FROM {$tbL134} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND ischarged=1 AND deletedata=0";
$bkt_oct_row = mysqli_data_array('assoc',$bkt_oct_sql);

$bkt_nov_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS novTaxes FROM {$tbL134} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND ischarged=1 AND deletedata=0";
$bkt_nov_row = mysqli_data_array('assoc',$bkt_nov_sql);

$bkt_dec_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS decTaxes FROM {$tbL134} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND ischarged=1 AND deletedata=0";
$bkt_dec_row = mysqli_data_array('assoc',$bkt_dec_sql);


?>