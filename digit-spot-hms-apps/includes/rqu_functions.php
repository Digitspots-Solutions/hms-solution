<?php
/* file to all required function library */

function data_pagenation($display,$start,$total)
{
	//global $ext;
	//global $pag_links;
	global $curpage;

	$pg_start = $start; $pg_limit = $display; $to = $display;
	$meanumber = $total / $display;

	if(($total - $display) == 1) { $meanumber = 2; }
	else { $meanumber = $meanumber; }

	if(is_int($meanumber)) { $loop = $meanumber; }
	else { $loop = (round($meanumber)) + 1; }
	
	
	if(isset($total) && isset($display) && $total > $display)
	{
		$selectopt = '';

		$selectopt .= '<select name="pagenumbr" id="pagenumbr" onchange="loadpage(this.value)" style="width: 150px; padding: 7px">';
		
		if(isset($curpage) && $curpage >= 1) { $selectopt .= '<option value="'.$curpage.'">Pg '.$curpage.'</option>'; $pg=$curpage; }
		else { $selectopt .= '<option value="">Page Numbering</option>'; $pg=1; }

		for($i=0; $i < $loop; $i++)
		{
			$link = $i + 1;
			$selectopt .= '<option value="'.$pg_start.'-'.$pg_limit.'-'.$link.'">'.$link.'</option>';
			$pg_start = $pg_start + $display; $to = $to + $display;
		}

		$selectopt .= '</select>';

		$pagelink = '<small class="dark-grey-font">Page: '.$pg.'</small> '.$selectopt.' &nbsp; <small class="blue-font">'.number_format($total).' found</small>';
	}
	else
	{
		$pagelink = '';
	}

	return $pagelink;
}

//end of function ------------------------------------------------/

function select_fetch($colkey,$key,$table,$optvalue,$optlabel)
{
	if(isset($key) && !empty($key)) { $keydata = array($colkey=>$key); }
	else { $keydata = ""; }

	$datasets = $optvalue.','.$optlabel;
	$additionalQuery = "";
	$fetch_data = mysqli_data_fetch($table,$datasets,$keydata,'array');

	$select = '';

	if(is_array($fetch_data))
	{
		foreach($fetch_data as $theader => $tdata)
		{
			$select .= '<option value="'.$tdata[$optvalue].'">'.$tdata[$optlabel].'</option>';
		}
	}
	else
	{
		$select .= '<option value="">No options</option>';
	}

	echo $select;
}

//end of function ------------------------------------------------/

function select_dt_fetch($colkey,$key,$table,$optvalue,$optlabel)
{
	global $extable;
	global $extcols;
	global $extkey;
	global $add2xQuery;

	global $additionalQuery;

	$duplicate_value = array();

	if(isset($key) && !empty($key)) { $keydata = array($colkey=>$key); }
	else { $keydata = ""; }

	if(!empty($optvalue)) { $datasets = $optvalue.','.$optlabel; }
	else { $datasets = $optlabel; }

	if(isset($additionalQuery) && !empty($additionalQuery)) { $additionalQuery = $additionalQuery; }
	$fetch_data = mysqli_data_fetch($table,$datasets,$keydata,'array');

	$select = '';

	if(is_array($fetch_data))
	{
		foreach($fetch_data as $theader => $tdata)
		{
			if(is_numeric($tdata[$optlabel])) {
				if(isset($add2xQuery) && !empty($add2xQuery)) { $additionalQuery = $add2xQuery; }
				$dk = array($extkey=>$tdata[$optlabel]);
				$fetch_data_key = mysqli_data_fetch($extable,$extcols,$dk,'noarray');
				if(!in_array($fetch_data_key[0], $duplicate_value)) {
					array_push($duplicate_value, $fetch_data_key[0]);
					$select .= '<option value="'.$tdata[$optlabel].'">'.$fetch_data_key[0].'</option>';
				}
			} else { 
				if(!in_array($tdata[$optlabel], $duplicate_value)) {
					array_push($duplicate_value, $tdata[$optlabel]);
					$select .= '<option value="'.$tdata[$optvalue].'">'.$tdata[$optlabel].'</option>';
				}
			}
		}
	}
	else
	{
		$select .= '<option value="">No options</option>';
	}

	return $select;
}

//end of function ------------------------------------------------/

function datalist_fetch($colkey,$key,$table,$optvalue,$optlabel)
{
	global $extable;
	global $extcols;
	global $extkey;

	$duplicate_value = array();

	if(isset($key) && !empty($key)) { $keydata = array($colkey=>$key); }
	else { $keydata = ""; }

	if(!empty($optvalue)) { $datasets = $optvalue.','.$optlabel; }
	else { $datasets = $optlabel; }

	$additionalQuery = "";
	$fetch_data = mysqli_data_fetch($table,$datasets,$keydata,'array');

	$select = '';

	if(is_array($fetch_data))
	{
		foreach($fetch_data as $theader => $tdata)
		{
			if(is_numeric($tdata[$optlabel])) {
				$dk = array($extkey=>$tdata[$optlabel]);
				$fetch_data_key = mysqli_data_fetch($extable,$extcols,$dk,'noarray');
				if(!in_array($fetch_data_key[0], $duplicate_value)) {
					array_push($duplicate_value, $fetch_data_key[0]);
					$select .= '<option value="'.$fetch_data_key[0].'">';
				}
			} else { 
				if(!in_array($tdata[$optlabel], $duplicate_value)) {
					array_push($duplicate_value, $tdata[$optlabel]);
					$select .= '<option value="'.$tdata[$optlabel].'">';
				}
			}
		}
	}
	else
	{
		$select .= '<option value="">';
	}

	return $select;
}

//end of function ------------------------------------------------/

function idget_data($table,$strid,$strcol)
{
	global $additionalQuery;
	
	$additionalQuery = "";
	$keydata = array("id"=>$strid);
	$fetch_data = mysqli_data_fetch($table,$strcol,$keydata,'noarray');

	if(isset($fetch_data[0]) && !empty($fetch_data[0])) { $rdata = $fetch_data[0]; }
	else { $rdata = null; }

	return $rdata;
}

//end of function ------------------------------------------------/

function idget_fdata($table,$keycol,$strid,$strcol)
{
	global $additionalQuery;
	
	$additionalQuery = "";
	$keydata = array($keycol=>$strid);
	$fetch_data = mysqli_data_fetch($table,$strcol,$keydata,'noarray');

	if(isset($fetch_data[0]) && !empty($fetch_data[0])) { $rdata = $fetch_data[0]; }
	else { $rdata = null; }
	
	return $rdata;
}

//end of function ------------------------------------------------/

function sqlFetch($table,$queryset)
{
	global $mysqli;

	$sqli = "SELECT * FROM {$table} WHERE {$queryset}";
	$result = mysqli_query($mysqli,$sqli);

	$row = "";
		
	if(@ mysqli_num_rows($result) == true) { $row = @ mysqli_fetch_array($result,MYSQLI_ASSOC); }
	else { $row = array(); }
	
	return $row;
}

//end of function ------------------------------------------------/

function getjob_workflow($mdl) {

	global $additionalQuery, $tbL142, $tbL108, $tbL4;

	$additionalQuery = "";
	$keydata = array("approval_setting"=>$mdl);
	$strcol = "id,workflow_name,isdefault";
	$fetch_data = mysqli_data_fetch($tbL142,$strcol,$keydata,'array');

	if(is_array($fetch_data) && count($fetch_data) > 0) {
		$fordefault=""; $nondefault="";
		foreach($fetch_data as $key => $val) {
			$qc_query = array("approve"=>$mdl,"qc"=>$val['id']);
			$qc = mysqli_data_fetch($tbL108,'role',$qc_query,'array');
			if($val['isdefault'] == 1) {
				$fordefault .= '<option value="'.$val['id'].'">'.$val['workflow_name'].'</option>';
				$fordefault .= '<optgroup label="--Approval Directors--" class="light-yellow-theme ft-xsml-size">';
				foreach($qc as $key2 => $val2) { $fordefault .= '<option disabled>'.idget_data($tbL4,$val2['role'],'role').'</option>'; }
				$fordefault .= '</optgroup>';
			}
			if($val['isdefault'] == 0) {
				$nondefault .= '<option value="'.$val['id'].'">'.$val['workflow_name'].'</option>';
				$nondefault .= '<optgroup label="--Approval Directors--" class="light-yellow-theme ft-xsml-size">';
				foreach($qc as $key2 => $val2) { $nondefault .= '<option disabled>'.idget_data($tbL4,$val2['role'],'role').'</option>'; }
				$nondefault .= '</optgroup>';
			}
		}

		return $fordefault.$nondefault;

	} else {
		return '<option value="">No workflow</option>';
	}
}

function getuser4_notification($approves,$qc) {

	global $tbL108, $tbL7, $joblevel;

	$users4A = array();

	if(is_numeric($qc) && $qc > 0) {
		$qc_query = array("approve"=>$approves,"qc"=>$qc,"deletedata"=>0);
		$wgtjA = mysqli_data_fetch($tbL108,'role',$qc_query,'array');
	} else {
		$qc_query = array("approve"=>$approves,"deletedata"=>0);
		$wgtjA = mysqli_data_fetch($tbL108,'role',$qc_query,'array');
	}

	if(is_array($wgtjA) && count($wgtjA) > 0) {
		foreach($wgtjA as $key => $val) {
			$user_query = array("role"=>$val['role'],"deletedata"=>0);
			$wgtjA2 = mysqli_data_fetch($tbL7,'id',$user_query,'noarray');
			$usr = array();
			$usr['id'] = $wgtjA2[0];
			array_push($users4A,$usr);
			$user_query = "";
		}
	}

	return $users4A;
}


function inboxmsg($message_params) {
	
	global $tbL104;

	if(is_array($message_params)) {

		$users = $message_params['receiver'];
		
		if(is_array($users) && count($users) > 0) {
			
			foreach($users as $key => $val) {
				
				if(!empty($val['id']) && is_numeric($val['id'])) { $is_receiver = $val['id']; }
				else { $is_receiver = 0; }
				
				$pst_query = "";
				$pst_field = array("subject"=>$message_params['subject'],"sender"=>$message_params['sender'],"receiver"=>$is_receiver,"message"=>$message_params['message'],"priority"=>$message_params['priority'],"msgtype"=>$message_params['msgtype'],"datelogged"=>$message_params['datelogged'],"timelogged"=>$message_params['timelogged']);
				mysqli_data_insert($tbL104,$pst_field,$pst_query);

				$pst_query = ""; $pst_field = "";
				$is_receiver = 0;
			}
		}
	}
}

//end of function ------------------------------------------------/

function groupSelect_fetch($colkey,$key,$table,$optvalue)
{
	global $extable;
	global $extcols;
	global $extkey;
	
	if(isset($key) && !empty($key)) { $keydata = array($colkey=>$key); }
	else { $keydata = ""; }

	$datasets = $optvalue;
	$additionalQuery = " GROUP BY ".$optvalue;
	$fetch_data = mysqli_data_fetch($table,$datasets,$keydata,'array');

	$select = '';

	if(is_array($fetch_data))
	{
		foreach($fetch_data as $theader => $tdata)
		{
			if(is_numeric($tdata[$optvalue])) {
				$dk = array("id"=>$tdata[$optvalue]);
				$fetch_data_key = mysqli_data_fetch($extable,$extcols,$dk,'noarray');
				$select .= '<option value="'.$tdata[$optvalue].'">'.$fetch_data_key[0].'</option>';
			} else {
				$select .= '<option value="'.$tdata[$optvalue].'">'.$tdata[$optvalue].'</option>';
			}
		}
	}
	else
	{
		$select .= '<option value="">No options</option>';
	}

	return $select;
}

//end of function ------------------------------------------------/

function perform_role_check($table,$role,$privilege,$class)
{
	global $myaccess;

	if($myaccess == 'limited') {

		if(isset($class) && !empty($class)) {
			$additionalQuery="";
			$usr_pvl = array("roleid"=>$role,"name"=>$privilege,"classid"=>$class);
		} else {
			$additionalQuery=" AND classid NOT IN(0,888,999)";
			$usr_pvl = array("roleid"=>$role,"name"=>$privilege);
		}

		$admin_pvl = mysqli_data_fetch($table,'id',$usr_pvl,'noarray');

		if(isset($admin_pvl[0]) && $admin_pvl[0] >= 1) { return $admin_pvl[0]; }
		else { return 0; }

	} else {
		return 1;
	}
}

//end of function ------------------------------------------------/

function workflow_privilege($mdlid,$ses)
{
	global $additionalQuery;
	global $tbL10;
	global $tbL11;

	global $this_class_id;

	$additionalQuery = "";
	$select_query = array("moduleid"=>$mdlid); //$appid
	$select_data = mysqli_data_fetch($tbL10,'id',$select_query,'array');

	$select_query_2 = ""; $select_data_2 = ""; $prl_id = 0; $this_class_id = 0;

	if(is_array($select_data)) {
		foreach ($select_data as $key => $value) {
			$select_query_2 = array("categoryid"=>$value['id'],"name"=>$ses);
			$select_data_2 = mysqli_data_fetch($tbL11,'id',$select_query_2,'noarray');
			if(isset($select_data_2[0]) && $select_data_2[0] >= 1) {
				$this_class_id = $value['id']; $prl_id = $select_data_2[0];
				break;
			}
		}
	}

	return $prl_id;
}

//end of function ------------------------------------------------/

function weekOfMonth($date) {
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01", $date));
    //Apply above formula.
    return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
}

//end of function ------------------------------------------------/

function month_intostring($m) {
	
	global $mvl;

	switch ($m) {
		case 1:
			$mvl = "01";
			return 'January';
			break;
		case 2:
			$mvl = "02";
			return 'Febuary';
			break;
		case 3:
			$mvl = "03";
			return 'March';
			break;
		case 4:
			$mvl = "04";
			return 'April';
			break;
		case 5:
			$mvl = "05";
			return 'May';
			break;
		case 6:
			$mvl = "06";
			return 'June';
			break;
		case 7:
			$mvl = "07";
			return 'July';
			break;
		case 8:
			$mvl = "08";
			return 'August';
			break;
		case 9:
			$mvl = "09";
			return 'September';
			break;
		case 10:
			$mvl = "10";
			return 'October';
			break;
		case 11:
			$mvl = "11";
			return 'November';
			break;
		case 12:
			$mvl = "12";
			return 'December';
			break;
		
		default:
			$mvl = 0;
			return 'Undefined';
			break;
	}
}

//end of function ------------------------------------------------//

function mt_select_fetch($colkey,$key,$table,$optvalue,$arraylabels,$extbls,$extcols)
{	
	global $global_addon_query;

	if(isset($key) && !empty($key)) {
		$keydata = array($colkey=>$key);
		if(is_array($global_addon_query) && count($global_addon_query) > 0) {
			$keydata = $keydata + $global_addon_query;
		}
	} else {
		$keydata = "";
	}

	$datasets = $optvalue.','.$arraylabels;
	$additionalQuery = "";
	$fetch_data = mysqli_data_fetch($table,$datasets,$keydata,'array');

	$select = '';

	if(is_array($fetch_data))
	{
		$opts = explode(',',$arraylabels);
		$xtbls = explode(',',$extbls);
		$xcols = explode(',',$extcols);

		//$primary_col = $opts[0];

		foreach($fetch_data as $theader => $tdata)
		{
			$select .= '<option value="'.$tdata[$optvalue].'">';
			if(isset($tdata[$opts[0]]) && is_numeric($tdata[$opts[0]])) {
				$pri_col = idget_data($xtbls[0],$tdata[$opts[0]],$xcols[0]);
				$select .= $pri_col;
			} else {
				$select .= $tdata[$opts[0]];
			}
			
			$ato = " (";
			
			for($i=1; $i<=count($opts); $i++) {
				if(isset($tdata[$opts[$i]]) && is_numeric($tdata[$opts[$i]])) {
					$ext_col = idget_data($xtbls[$i],$tdata[$opts[$i]],$xcols[$i]);
					$ato .= $ext_col;
				} else {
					$ato .= $tdata[$opts[$i]];
				}
				
				if($i < (count($opts) - 1)) { $ato .= ' - '; }
			}

			$ato .= ")";
			$select .= $ato;
			$select .= '</option>';
		}
	}
	else
	{
		$select .= '<option value="">No options</option>';
	}

	return $select;
}

//end of function ------------------------------------------------/

function drawcalendar($str) {
    	
	global $server_get_date;

	if(isset($str) && $str == 'dd') {
		$opt = ''; $ndy = '';
		for($d=1; $d <= 31; $d++) {
			if($d <= 9) { $ndy = '0'.$d; }
			else { $ndy = $d; }
			$opt .= '<option value="'.$ndy.'">'.$ndy.'</option>';
		}
	} elseif(isset($str) && $str == 'mm') {
		$opt = ''; $ndy = '';
		for($d=1; $d <= 12; $d++) {
			if($d <= 9) { $ndy = '0'.$d; }
			else { $ndy = $d; }
			$opt .= '<option value="'.$ndy.'">'.$ndy.'</option>';
		}
	} elseif(isset($str) && $str == 'yy') {
		$opt = ''; $yr = date("Y",strtotime($server_get_date));
		$yr_fw = $yr + 1;
		for($d=$yr; $d <= $yr_fw; $d++) {
			$opt .= '<option value="'.$d.'">'.$d.'</option>';
		}
	}

	return $opt;
}

//end of function ------------------------------------------------/

function arrayget_key($arry,$key) {
	
	$th_cmd = '';

	if(is_array($arry)) {
		
		foreach ($arry as $cmd_key => $cmd_value) {
			if($key == $cmd_key) {
				$th_cmd = $cmd_value;
				break;
			}
		}

	}
	
	return $th_cmd;
}

//end of function ------------------------------------------------/

function arrayset_form($arry,$field) {
	
	$th_cmd = '';

	if(is_array($arry)) {
		
		if($field == 'select') {
			foreach ($arry as $cmd_key => $cmd_value) {
				$th_cmd .= '<option value="'.$cmd_key.'">'.$cmd_value.'</option>';
			}
		} elseif($field == 'checkbox') {
			foreach ($arry as $cmd_key => $cmd_value) {
				$th_cmd .= '<input type="checkbox" name="checkers[]" value="'.$cmd_key.'"> '.$cmd_value.' &nbsp; ';
			}
		} elseif($field == 'radio') {
			foreach ($arry as $cmd_key => $cmd_value) {
				$th_cmd .= '<input type="radio" name="single-opt" value="'.$cmd_key.'"> '.$cmd_value.' &nbsp; ';
			}
		}

	}
	
	return $th_cmd;
}


//end of function ------------------------------------------------/

function arrayset_single_form($arry,$field) {
	
	$th_cmd = '';

	if(is_array($arry)) {
		
		if($field == 'select') {
			foreach ($arry as $scalar) {
				$th_cmd .= '<option value="'.$scalar.'">'.$scalar.'</option>';
			}
		} elseif($field == 'checkbox') {
			foreach ($arry as $scalar) {
				$th_cmd .= '<input type="checkbox" name="checkers[]" value="'.$scalar.'"> '.$scalar.' &nbsp; ';
			}
		} elseif($field == 'radio') {
			foreach ($arry as $scalar) {
				$th_cmd .= '<input type="radio" name="single-opt" value="'.$scalar.'"> '.$scalar.' &nbsp; ';
			}
		}

	}
	
	return $th_cmd;
}

//end of function ------------------------------------------------/

function write_dateF($format,$this_date) {
	
	switch ($format) {
		case 1:
			$get_this_date = date("m-d-y",strtotime($this_date));
			break;
		case 2:
			$get_this_date = date("m-d-Y",strtotime($this_date));
			break;
		case 3:
			$get_this_date = date("m d Y",strtotime($this_date));
			break;
		case 4:
			$get_this_date = date("m/d/y",strtotime($this_date));
			break;
		case 5:
			$get_this_date = date("d-m-y",strtotime($this_date));
			break;
		case 6:
			$get_this_date = date("d/m/y",strtotime($this_date));
			break;
		
		default:
			$get_this_date = date("d/m/Y",strtotime($this_date));
			break;
	}

	return $get_this_date;
}

//end of function ------------------------------------------------/

function write_timeF($format,$this_time) {
	
	switch ($format) {
		case 1:
			$get_this_time = date("g:i A",strtotime($this_time));
			break;
		case 2:
			$get_this_time = date("h:i",strtotime($this_time));
			break;
		
		default:
			$get_this_time = date("d/m/Y",strtotime($this_date));
			break;
	}

	return $get_this_time;
}

//end of function ------------------------------------------------/

function write_amountF($format,$this_amount) {

	switch ($format) {
		case 1:
			$get_this_amount = number_format($this_amount,1);
			break;
		case 2:
			$get_this_amount = number_format($this_amount,2);
			break;
		case 3:
			$get_this_amount = number_format($this_amount,3);
			break;
		
		default:
			$get_this_amount = number_format($this_amount);
			break;
	}

	return $get_this_amount;
}

//end of function ------------------------------------------------/

function dayDiffs($dateto,$datefrom) {

	global $leftyears;
	global $leftmonths;

	$diff = strtotime($dateto) - strtotime($datefrom);
	$days = abs(round($diff / (24 * 60 * 60)));

	/*$diff = abs(strtotime($dateto) - strtotime($datefrom));
				
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));*/
				
	$leftyears = "";
	$leftmonths = "";
	$leftdays = $days;

	return $days;
}

//end of function ------------------------------------------------/

function timeDiff($firstTime,$lastTime)
{
   // convert to unix timestamps
   $firstTime=strtotime($firstTime);
   $lastTime=strtotime($lastTime);

   // perform subtraction to get the difference (in seconds) between times
   $timeDiff=$lastTime-$firstTime;

   // return the difference
   return $timeDiff;
}
	
function daytimeDiffs($datefrom,$dateto) {

	global $thisyears;
	global $thisdays;
	global $thishours;
	global $thismins;

	$from = $datefrom;
	$to = $dateto;
	
	//calculate time difference
	$difference = timeDiff($from,$to);

	$years = abs(floor($difference / 31536000));
	$days = abs(floor(($difference-($years * 31536000))/86400));
	$hours = abs(floor(($difference-($years * 31536000)-($days * 86400))/3600));
	$mins = abs(floor(($difference-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
	#floor($difference / 60);

	$thisyears = $years; $thisdays = $days; $thishours = $hours; $thismins = $mins;
	$daytime_c = array($thisyears,$thisdays,$thishours,$thismins);

	return $daytime_c;
}

//end of function ------------------------------------------------/

function getWeekdays($startdate,$enddate,$getparam) {

	$start_date = strtotime($startdate);
	$last_date = strtotime($enddate) - 1;

	$weekday_arry = array();
	$nweekday_arry = array();
	$date_arry = array();
	
	if($getparam == 'nameweekday') {
		
		while($start_date <= $last_date) {
		    array_push($weekday_arry,date('l',$start_date));
		    $start_date = strtotime('+1 day', $start_date);
		}

		return $weekday_arry;

	} elseif($getparam == 'numberweekday') {
		
		while($start_date <= $last_date) {
		    array_push($nweekday_arry,date('N',$start_date));
		    $start_date = strtotime('+1 day', $start_date);
		}

		return $nweekday_arry;

	} elseif($getparam == 'daterange') {
		
		while($start_date <= $last_date) {
		    array_push($date_arry,date('Y-m-d',$start_date));
		    $start_date = strtotime('+1 day', $start_date);
		}

		return $date_arry;
	}
}

//end of function ------------------------------------------------/

function prgSequence($table,$program) {
		
	$serialized_number = idget_fdata($table,'app_name',$program,'start_number');
	$to_number = $program.$serialized_number;
	$new_number = $serialized_number + 1;

	$pst_query = array("app_name"=>$program);
	$pst_field = array("start_number"=>$new_number);
	mysqli_data_update($table,$pst_field,$pst_query);

	return $to_number;
}

//end of function ------------------------------------------------/

function app_service_portal($service_portal,$service) {
	$portal_url = $service_portal[$service];
	return $portal_url;
}
    
?>