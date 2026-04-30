<?php

$rcr_jan_sql = "SELECT IFNULL(SUM(amount),0) AS janRevenue FROM {$tbL107} WHERE datelogged >= '{$jan_sds}' AND datelogged <= '{$jan_eds}' AND isreversed=0 AND deletedata=0";
$rcr_jan_row = mysqli_data_array('assoc',$rcr_jan_sql);

$rcr_feb_sql = "SELECT IFNULL(SUM(amount),0) AS febRevenue FROM {$tbL107} WHERE datelogged >= '{$feb_sds}' AND datelogged <= '{$feb_eds}' AND isreversed=0 AND deletedata=0";
$rcr_feb_row = mysqli_data_array('assoc',$rcr_feb_sql);

$rcr_mar_sql = "SELECT IFNULL(SUM(amount),0) AS marRevenue FROM {$tbL107} WHERE datelogged >= '{$mar_sds}' AND datelogged <= '{$mar_eds}' AND isreversed=0 AND deletedata=0";
$rcr_mar_row = mysqli_data_array('assoc',$rcr_mar_sql);

$rcr_apr_sql = "SELECT IFNULL(SUM(amount),0) AS aprRevenue FROM {$tbL107} WHERE datelogged >= '{$apr_sds}' AND datelogged <= '{$apr_eds}' AND isreversed=0 AND deletedata=0";
$rcr_apr_row = mysqli_data_array('assoc',$rcr_apr_sql);

$rcr_may_sql = "SELECT IFNULL(SUM(amount),0) AS mayRevenue FROM {$tbL107} WHERE datelogged >= '{$may_sds}' AND datelogged <= '{$may_eds}' AND isreversed=0 AND deletedata=0";
$rcr_may_row = mysqli_data_array('assoc',$rcr_may_sql);

$rcr_jun_sql = "SELECT IFNULL(SUM(amount),0) AS junRevenue FROM {$tbL107} WHERE datelogged >= '{$jun_sds}' AND datelogged <= '{$jun_eds}' AND isreversed=0 AND deletedata=0";
$rcr_jun_row = mysqli_data_array('assoc',$rcr_jun_sql);

$rcr_jul_sql = "SELECT IFNULL(SUM(amount),0) AS julRevenue FROM {$tbL107} WHERE datelogged >= '{$jul_sds}' AND datelogged <= '{$jul_eds}' AND isreversed=0 AND deletedata=0";
$rcr_jul_row = mysqli_data_array('assoc',$rcr_jul_sql);

$rcr_aug_sql = "SELECT IFNULL(SUM(amount),0) AS augRevenue FROM {$tbL107} WHERE datelogged >= '{$aug_sds}' AND datelogged <= '{$aug_eds}' AND isreversed=0 AND deletedata=0";
$rcr_aug_row = mysqli_data_array('assoc',$rcr_aug_sql);

$rcr_sep_sql = "SELECT IFNULL(SUM(amount),0) AS sepRevenue FROM {$tbL107} WHERE datelogged >= '{$sep_sds}' AND datelogged <= '{$sep_eds}' AND isreversed=0 AND deletedata=0";
$rcr_sep_row = mysqli_data_array('assoc',$rcr_sep_sql);

$rcr_oct_sql = "SELECT IFNULL(SUM(amount),0) AS octRevenue FROM {$tbL107} WHERE datelogged >= '{$oct_sds}' AND datelogged <= '{$oct_eds}' AND isreversed=0 AND deletedata=0";
$rcr_oct_row = mysqli_data_array('assoc',$rcr_oct_sql);

$rcr_nov_sql = "SELECT IFNULL(SUM(amount),0) AS novRevenue FROM {$tbL107} WHERE datelogged >= '{$nov_sds}' AND datelogged <= '{$nov_eds}' AND isreversed=0 AND deletedata=0";
$rcr_nov_row = mysqli_data_array('assoc',$rcr_nov_sql);

$rcr_dec_sql = "SELECT IFNULL(SUM(amount),0) AS decRevenue FROM {$tbL107} WHERE datelogged >= '{$dec_sds}' AND datelogged <= '{$dec_eds}' AND isreversed=0 AND deletedata=0";
$rcr_dec_row = mysqli_data_array('assoc',$rcr_dec_sql);


#-------------------end

$rcr_sql = "SELECT IFNULL(SUM(amount),0) AS ttRevenue FROM {$tbL107} WHERE datelogged >= '{$jan_sdsx}' AND datelogged <= '{$dec_edsx}' AND isreversed=0 AND deletedata=0";
$rcr_row = mysqli_data_array('assoc',$rcr_sql);

#------------------summay-end


$rcrt_jan_taxes = (($gh_get_vat / 100) * $rcr_jan_row[0]['janRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_jan_row[0]['janRevenue']);
$rcrt_feb_taxes = (($gh_get_vat / 100) * $rcr_feb_row[0]['febRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_feb_row[0]['febRevenue']);
$rcrt_mar_taxes = (($gh_get_vat / 100) * $rcr_mar_row[0]['marRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_mar_row[0]['marRevenue']);
$rcrt_apr_taxes = (($gh_get_vat / 100) * $rcr_apr_row[0]['aprRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_apr_row[0]['aprRevenue']);
$rcrt_may_taxes = (($gh_get_vat / 100) * $rcr_may_row[0]['mayRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_may_row[0]['mayRevenue']);
$rcrt_jun_taxes = (($gh_get_vat / 100) * $rcr_jun_row[0]['junRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_jun_row[0]['junRevenue']);
$rcrt_jul_taxes = (($gh_get_vat / 100) * $rcr_jul_row[0]['julRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_jul_row[0]['julRevenue']);
$rcrt_aug_taxes = (($gh_get_vat / 100) * $rcr_aug_row[0]['augRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_aug_row[0]['augRevenue']);
$rcrt_sep_taxes = (($gh_get_vat / 100) * $rcr_sep_row[0]['sepRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_sep_row[0]['sepRevenue']);
$rcrt_oct_taxes = (($gh_get_vat / 100) * $rcr_oct_row[0]['octRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_oct_row[0]['octRevenue']);
$rcrt_nov_taxes = (($gh_get_vat / 100) * $rcr_nov_row[0]['novRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_nov_row[0]['novRevenue']);
$rcrt_dec_taxes = (($gh_get_vat / 100) * $rcr_dec_row[0]['decRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_dec_row[0]['decRevenue']);

?>