<?php

$pos_jan_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS janRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND isreversed=0 AND deletedata=0";
$pos_jan_row = mysqli_data_array('assoc',$pos_jan_sql);

$pos_feb_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS febRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND isreversed=0 AND deletedata=0";
$pos_feb_row = mysqli_data_array('assoc',$pos_feb_sql);

$pos_mar_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS marRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND isreversed=0 AND deletedata=0";
$pos_mar_row = mysqli_data_array('assoc',$pos_mar_sql);

$pos_apr_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS aprRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND isreversed=0 AND deletedata=0";
$pos_apr_row = mysqli_data_array('assoc',$pos_apr_sql);

$pos_may_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS mayRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND isreversed=0 AND deletedata=0";
$pos_may_row = mysqli_data_array('assoc',$pos_may_sql);

$pos_jun_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS junRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND isreversed=0 AND deletedata=0";
$pos_jun_row = mysqli_data_array('assoc',$pos_jun_sql);

$pos_jul_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS julRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND isreversed=0 AND deletedata=0";
$pos_jul_row = mysqli_data_array('assoc',$pos_jul_sql);

$pos_aug_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS augRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND isreversed=0 AND deletedata=0";
$pos_aug_row = mysqli_data_array('assoc',$pos_aug_sql);

$pos_sep_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS sepRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND isreversed=0 AND deletedata=0";
$pos_sep_row = mysqli_data_array('assoc',$pos_sep_sql);

$pos_oct_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS octRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND isreversed=0 AND deletedata=0";
$pos_oct_row = mysqli_data_array('assoc',$pos_oct_sql);

$pos_nov_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS novRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND isreversed=0 AND deletedata=0";
$pos_nov_row = mysqli_data_array('assoc',$pos_nov_sql);

$pos_dec_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS decRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND isreversed=0 AND deletedata=0";
$pos_dec_row = mysqli_data_array('assoc',$pos_dec_sql);


#------------------end

$pos_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS ttRevenue FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jan_sdsx}' AND datelogged <= '{$dec_edsx}' AND isreversed=0 AND deletedata=0";
$pos_row = mysqli_data_array('assoc',$pos_sql);

#------------------summary-end


$post_jan_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS janTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND isreversed=0 AND deletedata=0";
$post_jan_row = mysqli_data_array('assoc',$post_jan_sql);

$post_feb_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS febTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND isreversed=0 AND deletedata=0";
$post_feb_row = mysqli_data_array('assoc',$post_feb_sql);

$post_mar_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS marTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND isreversed=0 AND deletedata=0";
$post_mar_row = mysqli_data_array('assoc',$post_mar_sql);

$post_apr_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS aprTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND isreversed=0 AND deletedata=0";
$post_apr_row = mysqli_data_array('assoc',$post_apr_sql);

$post_may_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS mayTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND isreversed=0 AND deletedata=0";
$post_may_row = mysqli_data_array('assoc',$post_may_sql);

$post_jun_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS junTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND isreversed=0 AND deletedata=0";
$post_jun_row = mysqli_data_array('assoc',$post_jun_sql);

$post_jul_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS julTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND isreversed=0 AND deletedata=0";
$post_jul_row = mysqli_data_array('assoc',$post_jul_sql);

$post_aug_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS augTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND isreversed=0 AND deletedata=0";
$post_aug_row = mysqli_data_array('assoc',$post_aug_sql);

$post_sep_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS sepTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND isreversed=0 AND deletedata=0";
$post_sep_row = mysqli_data_array('assoc',$post_sep_sql);

$post_oct_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS octTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND isreversed=0 AND deletedata=0";
$post_oct_row = mysqli_data_array('assoc',$post_oct_sql);

$post_nov_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS novTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND isreversed=0 AND deletedata=0";
$post_nov_row = mysqli_data_array('assoc',$post_nov_sql);

$post_dec_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS decTaxes FROM {$tbL100} WHERE posid='{$val['id']}' AND datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND isreversed=0 AND deletedata=0";
$post_dec_row = mysqli_data_array('assoc',$post_dec_sql);

?>