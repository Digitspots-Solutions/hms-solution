<?php

	#generic select db query
	$sqlSelect = "SELECT gcol FROM gtbl WHERE gquery";

	#dateformat
	$nth_dfn = "d/m/Y";
	$nth_df = "j M Y";

	#unwanted characters
	$arry_unwanted = array("(nf)","(df)","(tc)","(uc)","(fx)","(ctr)","(pr)","(bx)","(nl)");
	//nf - number format, df - date format, tc - text case
	//ctr - control checkbox


	#datasheet global params
	$extdata = array(
		"userid"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"staff"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"receivedby"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"disbursedby"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"retiredby"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"checkin_byuser"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"checkout_byuser"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"cancel_byuser"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"cashier"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"waiter"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"chargeto"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"assignedby"=>array("tbl"=>$tbL7,"col"=>"staffname","origin"=>"db"),
		"department"=>array("tbl"=>$tbL12,"col"=>"department","origin"=>"db"),
		"departmentid"=>array("tbl"=>$tbL12,"col"=>"department","origin"=>"db"),
		"role"=>array("tbl"=>$tbL4,"col"=>"role","origin"=>"db"),
		"isprocess"=>array("tbl"=>"process_status","col"=>"","origin"=>"setarry"),
		"foodtype"=>array("tbl"=>"food_type","col"=>"","origin"=>"setarry"),
		"billtype"=>array("tbl"=>"bill_type","col"=>"","origin"=>"setarry"),
		"supplierid"=>array("tbl"=>$tbL114,"col"=>"supplier_name","origin"=>"db"),
		"itemid"=>array("tbl"=>$tbL16,"col"=>"item","origin"=>"db"),
		"categoryid"=>array("tbl"=>$tbL15,"col"=>"category","origin"=>"db"),
		"subcategoryid"=>array("tbl"=>$tbL92,"col"=>"subcategory","origin"=>"db"),
		"cspgid"=>array("tbl"=>$tbL58,"col"=>"name","origin"=>"db"),
		"biller"=>array("tbl"=>$tbL58,"col"=>"name","origin"=>"db"),
		"paymode"=>array("tbl"=>$tbL24,"col"=>"name","origin"=>"db"),
		"payment_mode"=>array("tbl"=>$tbL24,"col"=>"name","origin"=>"db"),
		"media"=>array("tbl"=>$tbL24,"col"=>"name","origin"=>"db"),
		"room_type_id"=>array("tbl"=>$tbL52,"col"=>"name","origin"=>"db"),
		"roomid"=>array("tbl"=>$tbL56,"col"=>"roomprefix/roomnumber","origin"=>"db"),
		"tableid"=>array("tbl"=>$tbL17,"col"=>"tablename","origin"=>"db"),
		"customerid"=>array("tbl"=>$tbL102,"col"=>"fname-lname","origin"=>"db"),
		"salutation"=>array("tbl"=>$tbL42,"col"=>"name","origin"=>"db"),
		"country"=>array("tbl"=>"countries","col"=>"name","origin"=>"db"),
		"housekeeping_stateid"=>array("tbl"=>$tbL36,"col"=>"legendname","origin"=>"db"),
		"new_housekeeping_stateid"=>array("tbl"=>$tbL36,"col"=>"legendname","origin"=>"db"),
		"room_status_id"=>array("tbl"=>$tbL38,"col"=>"legendname","origin"=>"db")
	);


	#global params
	$var_user = "xf_user"; $var_role = "xf_role"; $var_supplier = "xf_supplier";
	$var_item = "xf_item"; $var_category = "xf_category"; $var_subcategory = "xf_subcategory";
	$var_corporate = "xf_corporate"; $var_paymode = "xf_paymode"; $var_store = "xf_store"; $var_mstore = "xf_mstore";
	
	$_gparams = array(
		$var_user=>array("returnval"=>"","name"=>"staffname","col"=>"id","tbl"=>$tbL7),
		$var_role=>array("returnval"=>"","name"=>"rolename","col"=>"id","tbl"=>$tbL4),
		$var_supplier=>array("returnval"=>"","name"=>"supplier_name","col"=>"id","tbl"=>$tbL114),
		$var_item=>array("returnval"=>"","name"=>"item","col"=>"id","tbl"=>$tbL118),
		$var_category=>array("returnval"=>"","name"=>"category","col"=>"id","tbl"=>$tbL115),
		$var_subcategory=>array("returnval"=>"","name"=>"subcategory","col"=>"id","tbl"=>$tbL116),
		$var_corporate=>array("returnval"=>"","name"=>"name","col"=>"id","tbl"=>$tbL58),
		$var_paymode=>array("returnval"=>"","name"=>"name","col"=>"id","tbl"=>$tbL24),
		$var_store=>array("returnval"=>"","name"=>"posname","col"=>"id","tbl"=>$tbL14),
		$var_mstore=>array("returnval"=>"","name"=>"store_name","col"=>"id","tbl"=>$tbL123)
	);


?>