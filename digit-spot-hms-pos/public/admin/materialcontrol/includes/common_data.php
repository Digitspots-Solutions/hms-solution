<?php
	
	$rootfile = $_SERVER['DOCUMENT_ROOT'].'/';
	//$rootfile = $_SERVER['DOCUMENT_ROOT'].'/warehousing/';
	$vars = json_decode(file_get_contents($rootfile.'includes/var.json'), true);

	$var_webtitle = $vars['webpacket'][0]['webtitle'];
	$var_webdesc = $vars['webpacket'][0]['description'];
	$var_backend_longname = $vars['webpacket'][0]['backendlongname'];
	$var_backend_shortname = $vars['webpacket'][0]['backendshortname'];
	$var_suffixname = $vars['webpacket'][0]['suffixname'];

	$var_backgroundcolor = $vars['startup'][0]['backgroundcolor'];
	$var_image = $vars['startup'][0]['image'];

	$_site_defined_images_ = DOMAIN_URL._IMAGE_.$sub_images_folder[0]."/";
	$_site_common_images_ = DOMAIN_URL._IMAGE_.$sub_images_folder[1]."/";


	#accept image upload
	$image_accept = array(
		"png",
		"jpeg",
		"jpg"
	);

	#accept video upload
	$video_accept = array(
		"mp4"
	);


	#accept document
	$doc_accept = array(
		"pdf",
		"xdocs",
		"doc",
		"xlsx",
		"xls",
		"csv"
	);

	#data status
	$data_status = array(
		1=>"Active",
		0=>"Not active"
	);

	$process_status = array(
		1=>"Successful",
		0=>"Pending"
	);

	$avail_status = array(
		1=>"Available",
		0=>"Not available"
	);

	$pay_status = array(
		1=>"Paid",
		0=>"Not paid"
	);

	#currency
	$currency_types = array(
		"NGN"=>"&#8358;",
		"USD"=>"&#36;",
		"GBP"=>"&#163;",
		"EUR"=>"&euro;"
	);

	#rating service
	$service_tags = array(
		"Product Quality Control",
		"Delivery Service",
		"Support Service",
		"Secured Payment"
	);

	#rating stars
	$rating = array(
		1=>40,
		2=>60,
		3=>80,
		4=>90,
		5=>100
	);

	#frontend component

	#email parameters
	define ("_SHORT_NAME","ISAP");
	define ("_LONG_NAME","CENTINO - ISAP");
	define ("_NOREPLY_EMAIL","noreply@domain.com");

	#sms api
	$apiUrl = "";
	

	#auto page title
	$curl_request = $_SERVER['REQUEST_URI'];

	if(isset($curl_request) && strstr($curl_request, '/')) {
		$curl_page = explode('/',$curl_request);
		$curl_pagex = explode('?',$curl_page[1]);
		
		if(isset($curl_pagex[0]) && !empty($curl_pagex[0])) {
			$flushext = str_replace('.php','',$curl_pagex[0]);
			$flushext = str_replace('.html','',$flushext);
			$flushext = str_replace('.htm','',$flushext);
			$flushext = str_replace('.asp','',$flushext);
			$flushext = str_replace('-',' ',$flushext);
			$ths_title = ucwords(strtolower($flushext)). " | ".$var_webtitle;
		} else {
			$ths_title = $var_webtitle;
		}
	} else {
		$ths_title = $var_webtitle;
	}

	$pgtitle = $ths_title;
	$description = $var_webdesc;
	


	#backend component
	$admin_pgtitle = $var_webtitle;
	$backend_name = $var_backend_longname;
	$backend_short_name = $var_backend_shortname;
	$suffix_name = $var_suffixname;

?>