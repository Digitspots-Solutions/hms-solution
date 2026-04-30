<?php

#for booking
$bk_sql = "SELECT IFNULL(SUM(room_amount),0) AS bookingRevenue FROM {$tbL134} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND ischarged=1 AND deletedata=0";
$bk_row = mysqli_data_array('assoc',$bk_sql);

$bk_tax_sql = "SELECT (SUM(tax_amount) + SUM(consumption_tax_amount)) AS taxCharge FROM {$tbL134} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND ischarged=1 AND deletedata=0";
$bk_tax_row = mysqli_data_array('assoc',$bk_tax_sql);


#for recreation
$rcr_sql = "SELECT IFNULL(SUM(amount),0) AS recreationRevenue FROM {$tbL107} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND isreversed=0 AND deletedata=0";
$rcr_row = mysqli_data_array('assoc',$rcr_sql);

$rcr_taxes = (($gh_get_vat / 100) * $rcr_row[0]['recreationRevenue']) + (($gh_get_consumption_tax / 100) * $rcr_row[0]['recreationRevenue']);

$actual_rcr_revenue = $rcr_row[0]['recreationRevenue'] - $rcr_taxes;


#for pos
$pos_sql = "SELECT (SUM(bill_amount) - (SUM(tax_amount) + SUM(consumption_amount) + SUM(service_charge_amount))) AS posRevenue FROM {$tbL100} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND isreversed=0 AND deletedata=0";
$pos_row = mysqli_data_array('assoc',$pos_sql);

$pos_tax_sql = "SELECT (SUM(tax_amount) + SUM(consumption_amount)) AS posRevenue FROM {$tbL100} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND isreversed=0 AND deletedata=0";
$pos_tax_row = mysqli_data_array('assoc',$pos_tax_sql);


$sales_revenue = $bk_row[0]['bookingRevenue'] + $pos_row[0]['posRevenue'] + $actual_rcr_revenue;
$taxes_charge = $bk_tax_row[0]['taxCharge'] + $pos_tax_row[0]['posRevenue'] + $rcr_taxes;


#receivable
$ar_sql = "SELECT cspgid, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND paymode > 0 AND transaction_type='Credit') AS totalcredit, (SELECT SUM(amount) FROM {$tbL63} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND transaction_type='Debit') AS totaldebit FROM {$tbL63} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND isreversed=0 AND deletedata=0";

$ar_row = mysqli_data_array('assoc',$ar_sql);
$acct_receivable = $ar_row[0]['totaldebit'] - $ar_row[0]['totalcredit'];


#payable
$py_sql = "SELECT (SUM(amount) - SUM(variance_amount)) AS accountPayable FROM {$tbL153} WHERE datelogged >= '{$mm_sds}' AND datelogged <= '{$mm_eds}' AND status IN('Pending') AND pr_type IN('Job Order') AND deletedata=0";

$py_row = mysqli_data_array('assoc',$py_sql);
$acct_payable = $py_row[0]['accountPayable'];

?>