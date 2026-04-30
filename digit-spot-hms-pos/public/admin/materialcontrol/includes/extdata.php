<?php
	
	#generic select db query
	$sqlSelect = "SELECT gcol FROM gtbl WHERE gquery";

	#dateformat
	$nth_dfn = "d/m/Y";
	$nth_df = "j M Y";

	#unwanted characters
	$arry_unwanted = array("(nf)","(df)","(tc)","(fx)","(ctr)");
	//nf - number format, df - date format, tc - text case
	//ctr - control checkbox


	#array of all extension data link to db

	$extdata = array(
		"userid"=>array(
			"tbl"=>$mtbL16,
			"col"=>"staffname",
			"origin"=>"db"
		),
		"requestby"=>array(
			"tbl"=>$mtbL16,
			"col"=>"staffname",
			"origin"=>"db"
		),
		"acknowledgeby"=>array(
			"tbl"=>$mtbL16,
			"col"=>"staffname",
			"origin"=>"db"
		),
		"department"=>array(
			"tbl"=>$mtbL15,
			"col"=>"department",
			"origin"=>"db"
		),
		"role"=>array(
			"tbl"=>$mtbL17,
			"col"=>"role",
			"origin"=>"db"
		),
		"status"=>array(
			"tbl"=>"data_status",
			"col"=>"",
			"origin"=>"setarry"
		),
		"isprocess"=>array(
			"tbl"=>"process_status",
			"col"=>"",
			"origin"=>"setarry"
		),
		"currency"=>array(
			"tbl"=>"currency_types",
			"col"=>"",
			"origin"=>"setarry"
		),
		"buying_unit"=>array(
			"tbl"=>"uoms",
			"col"=>"",
			"origin"=>"setarry"
		),
		"selling_unit"=>array(
			"tbl"=>"uoms",
			"col"=>"",
			"origin"=>"setarry"
		),
		"uom"=>array(
			"tbl"=>"uoms",
			"col"=>"",
			"origin"=>"setarry"
		),
		"supplierid"=>array(
			"tbl"=>$mtbL1,
			"col"=>"supplier_name",
			"origin"=>"db"
		),
		"itemid"=>array(
			"tbl"=>$mtbL5,
			"col"=>"item",
			"origin"=>"db"
		),
		"categoryid"=>array(
			"tbl"=>$mtbL2,
			"col"=>"category",
			"origin"=>"db"
		),
		"subcategoryid"=>array(
			"tbl"=>$mtbL3,
			"col"=>"subcategory",
			"origin"=>"db"
		),
		"storageid"=>array(
			"tbl"=>$tbL123,
			"col"=>"store_name",
			"origin"=>"db"
		),
		"frompos"=>array(
			"tbl"=>$tbL14,
			"col"=>"posname",
			"origin"=>"db"
		),
		"topos"=>array(
			"tbl"=>$tbL14,
			"col"=>"posname",
			"origin"=>"db"
		)
	);


	#global params
	$var_user = "xf_user"; $var_role = "xf_role"; $var_supplier = "xf_supplier"; $var_store = "xf_store";
	$var_item = "xf_item"; $var_category = "xf_category"; $var_subcategory = "xf_subcategory";
	
	$_gparams = array(
		$var_user=>array("returnval"=>"","name"=>"staffname","col"=>"id","tbl"=>$mtbL16),
		$var_role=>array("returnval"=>"","name"=>"rolename","col"=>"id","tbl"=>$mtbL17),
		$var_supplier=>array("returnval"=>"","name"=>"supplier_name","col"=>"id","tbl"=>$mtbL1),
		$var_item=>array("returnval"=>"","name"=>"item","col"=>"id","tbl"=>$mtbL5),
		$var_category=>array("returnval"=>"","name"=>"category","col"=>"id","tbl"=>$mtbL2),
		$var_subcategory=>array("returnval"=>"","name"=>"subcategory","col"=>"id","tbl"=>$mtbL3),
		$var_store=>array("returnval"=>"","name"=>"store_name","col"=>"id","tbl"=>$tbL123)
	);


	//for email template

	/*$upage = '';
	$upage .= '<html lang="en">';
	$upage .= '<head>';
	$upage .= '<meta charset="utf-8">';
	$upage .= '<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1,minimum-scale=1,maximum-scale=3,user-scalable=yes">';
	$upage .= '<style>';
	$upage .= '* { box-sizing: border-box; font-family: century gothic,tahoma,verdana,arial,sans serif; }';
	$upage .= '.mainbox { display: block; background-color: #e8e8e8; padding: 50px 20px 50px 20px; font-size: 15px; }';
	$upage .= 'div.container { width: 65%; background-color: #ffffff; border-radius: 7px; -webkit-border-radius: 7px; padding: 30px; }';
	$upage .= 'h1 { margin: 7px 0px 15px 0px; font-size: 35px; }';
	$upage .= 'h2 { margin: 0px 0px 5px 0px; font-size: 18px; }';
	$upage .= 'h3 { margin: 0; font-size: 15px; font-weight: normal; }';
	$upage .= '@media screen and (max-width: 480px) { .mainbox { display: block; background-color: #e8e8e8; padding: 30px 10px 30px 10px; font-size: 14px; } div.container { width: 100%; background-color: #ffffff; border-radius: 7px; -webkit-border-radius: 7px; padding: 20px; } h1 { margin: 5px 0px 15px 0px; font-size: 25px; } }';
	$upage .= '@media only screen and (device-width: 480px) { .mainbox { display: block; background-color: #e8e8e8; padding: 30px 10px 30px 10px; font-size: 14px; } div.container { width: 100%; background-color: #ffffff; border-radius: 7px; -webkit-border-radius: 7px; padding: 20px; } h1 { margin: 5px 0px 15px 0px; font-size: 25px; } }';
	$upage .= '</style>';
	$upage .= '</head>';
	$upage .= '<body>';
	$upage .= '<div class="mainbox" align="center">';
	$upage .= '<div style="margin-bottom: 20px"><img src="'.$_site_defined_images_.'logo.png" style="margin-bottom: 10px"><h2>Global Digits Holding Corporation</h2><h3>Trusted for instant money exchange</h3></div>';
	$upage .= '<div class="container" align="left">';
	
	$dpage = '</div>';
	$dpage .= '<div style="display: block; margin-top: 15px; text-align: center">';
	$dpage .= '<small style="color: #555">Copyright &copy; '.date("Y").'. Rockview Hotels</small>';
	$dpage .= '<small style="display: block; margin-top: 5px">website: https://www.rockviewhotels.com</small>';
	$dpage .= '</div>';

	$dpage .= '</div>';
	$dpage .= '</body>';
	$dpage .= '</html>';*/



?>