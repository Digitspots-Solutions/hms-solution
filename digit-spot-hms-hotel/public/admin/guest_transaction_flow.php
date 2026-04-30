<?php

	createDatabasetable($var_tbl_140); //create a table for this post

	$insert_query_g = "";
	$insert_data_g = array("transaction_number"=>$transaction_flow_number,"transaction_type"=>$transaction_type,"guest"=>$guest_number,"biller"=>$biller,"sales_point"=>$sales_point,"sales_description"=>$sales_description,"amount"=>$transaction_amount,"balance_bfw"=>$balance_bfw,"payment_mode"=>$transaction_payment_mode,"cheque_number"=>$cheque_number,"userid"=>$createdby,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);

	mysqli_data_insert($tbL145,$insert_data_g,$insert_query_g);
?>