<?php
#This program is written and packaged by Ibat Informatics
#Copyright 2021. No license required
#Feel free to copy and re-use
#custom functions

function data_pagenation($display,$start,$total) {
	
	global $curpage;

	$pg_start = $start; $pg_limit = $display; $to = $display;
	$meanumber = $total / $display; $loop = number_format($meanumber,1);
	
	if(isset($total) && isset($display) && $total > $display)
	{
		$selectopt = '';

		$selectopt .= '<select name="pagenumbr" id="pagenumbr" onchange="loadpage(this.value)" class="nopads no-back-black">';
		
		if(isset($curpage) && $curpage >= 1) { $selectopt .= '<option value="'.$curpage.'">Pg '.$curpage.' - '.$display.' of '.$total.'</option>'; $pg=$curpage; } else { $selectopt .= '<option value="">Page Numbering</option>'; $pg=1; }

		for($i=0; $i < $loop; $i++)
		{
			$link = $i + 1;
			$selectopt .= '<option value="'.$pg_start.'-'.$pg_limit.'-'.$link.'">'.$link.'</option>';
			$pg_start = $pg_start + $display; $to = $to + $display;
		}

		$selectopt .= '</select>';

		//$pagelink = '<small class="dark-grey-font">Page: '.$pg.'</small> '.$selectopt.' &nbsp; <small class="blue-font">'.number_format($total).' found</small>';
		$pagelink = $selectopt;
	}
	else
	{
		$pagelink = '';
	}

	return $pagelink;
}

//end of function ------------------------------------------------/

function html_db_select($queryset,$optval,$opttext) {
	
	$select = ""; $textId = ""; $textName = "";

	$result = mysqli_data_fetch('assoc',$queryset);
	$duplicate_value = array();

	if(is_array($result) && count($result) > 0) {
		foreach($result as $key => $val) {
			
			$fchar = 0; $textId = 0; $textName = "";
			
			foreach($val as $xkey => $xval) {
				if($optval == $xkey) { $textId = $xval; }
				else { if(is_numeric($optval)) { $textId = $optval; } }
				
				if(is_array($opttext)) {
					$fchar = 1;
					if(in_array($xkey, $opttext)) { $textName .= $xval.' - '; }
				} else {
					$fchar = 0;
					if($opttext == $xkey) {
						if(is_numeric($xval)) {
							global $xtbl; global $xcol; global $xopttext;
							$xsql = "SELECT {$xopttext} FROM {$xtbl} WHERE {$xcol} = {$xval}";
							$params = idget_data($xsql);
							if($params !== null) { $textName = $params[0][$xopttext]; }
							else { if($xval > 0) { $textName = $xval; } else { $textName = "N/A"; } }
						} else {
							$textName = $xval;
						}
					}
				}
			}

			if($fchar == 1) { $textName = substr_replace($textName,'',-1,3); }
			else { $textName = $textName; }

			if(!in_array($textName, $duplicate_value)) {
				array_push($duplicate_value, $textName);
				$select .= '<option value="'.$textId.'">'.$textName.'</option>';
			}
		}
	} else {
		$select = '<option value="">No options</option>';
	}

	return $select;
}

//end of function ------------------------------------------------/

function html_db_datalist($queryset,$optval) {
	
	$select = "";

	$result = mysqli_data_fetch('assoc',$queryset);
	
	if(is_array($result) && count($result) > 0) {
		foreach($result as $key => $val) {
			$select .= '<option value="'.$val[$optval].'">';
		}
	} else {
		$select = '<option value="No options">';
	}

	return $select;
}

//end of function ------------------------------------------------/

function idget_data($queryset) {
	
	$result = mysqli_data_fetch('assoc',$queryset);
	
	$response = "";

	if(is_array($result) && count($result) > 0) {
		$response = $result;
	} else {
		$response = null;
	}
	
	return $response;
}

//end of function ------------------------------------------------/

function idget_name($id,$name,$tbl) {
	$queryset = "SELECT {$name} FROM {$tbl} WHERE id = '{$id}'";
	$isSql = idget_data($queryset);
	if(!empty($isSql[0][$name])) { return $isSql[0][$name]; }
	else { return "Unknown"; }
}

//end of function ------------------------------------------------/

function idget_fname($id,$col,$name,$tbl) {
	$queryset = "SELECT {$name} FROM {$tbl} WHERE {$col} = '{$id}'";
	$isSql = idget_data($queryset);
	if(!empty($isSql[0][$name])) { return $isSql[0][$name]; }
	else { return "Unknown"; }
}

//end of function ------------------------------------------------/

function idget_global($key,$arr) {
	
	if(!empty($arr) && count($arr) > 0) {
		
		global $_gparams;

		$tbl = $_gparams[$arr]['tbl'];
		$col = $_gparams[$arr]['col'];
		$name = $_gparams[$arr]['name'];

		$queryset = "SELECT {$name} FROM {$tbl} WHERE {$col} = '{$key}'";
		$isSql = idget_data($queryset);
		
		if(!empty($isSql[0][$name])) { $_gparams[$arr]['returnval'] = $isSql[0][$name]; }
		else { $_gparams[$arr]['returnval'] = "Null value"; }
	}
}

//end of function ------------------------------------------------/

function idget_foreign_data($fparams) {
	
	$tbl = $fparams['tbl'];
	$col = $fparams['col'];
	$key = $fparams['datakey'];

	if(strstr($col,',')) { $sql = "SELECT {$col} FROM {$tbl} WHERE id = {$key}"; }
	else { $sql = "SELECT * FROM {$tbl} WHERE id = {$key}"; }
	
	$result = mysqli_data_fetch('assoc',$sql);
	
	$response = ""; $doubledata = "";

	if(is_array($result) && count($result) > 0) {
		if(strstr($col,',')) {
			$explode_data = explode(',',$col);
			foreach($explode_data as $coldt) { $doubledata .= $result[0][$coldt].' - '; }
			$response = substr_replace($doubledata,'',-3,3);
		} elseif(strstr($col,'-')) {
			$explode_data = explode('-',$col);
			foreach($explode_data as $coldt) { $doubledata .= $result[0][$coldt]; }
			$response = $doubledata;
		} else {
			$response = $result[0][$col];
		}
	} else {
		$response = "N/A";
	}
	
	return $response;
}

//end of function ------------------------------------------------/

function rowHeader($theader) {
	
	global $nth_df, $arry_unwanted;

	if(is_array($theader)) {
		
		$xheader = ""; $eheader = "";
		
		foreach($theader as $th) {
			if($th === 'na') {
				$eheader = "";
				$xheader .= '<td class="">&nbsp;</td>';
			} else {
				$eheader = ucwords(str_replace($arry_unwanted,'',$th));
				$xheader .= '<td class="nunito-bold alignct">'.$eheader.'</td>';
			}
		}

		return '<tr>'.$xheader.'</tr>';
	}
}

//end of function ------------------------------------------------/

function data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata) {
	
	global $data_status, $process_status, $currency, $avail_status, $nth_df, $arry_unwanted, $uoms;

	$sql = "SELECT * FROM {$tbl} WHERE ".$queryset;
	$result = mysqli_data_fetch('assoc',$sql);

	$response = "";
	$xtable = "";

	if(is_array($result) && count($result) > 0) {
		if(in_array('grid', $format)) {
			
			//extract table header from included array

			$cols = array(); $csvheader = array(); $hAdf = array();
			$allow_form_check = "";

			$xheader = '<td class="">&nbsp;</td>';
			
			if(in_array('form-ctrl', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			$eheader = "";

			foreach($keys as $col => $header) {
				$eheader = ucwords(str_replace($arry_unwanted,'',$header));
				$xheader .= '<td class="nunito-bold alignct">'.$eheader.'</td>';
				array_push($cols,$col);
				array_push($csvheader,$eheader);
				array_push($hAdf,$header);
			}

			if(in_array('allow-edit', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			if(in_array('allow-view', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			//extract table data from from database array
			$num=$startnumbr; $arryStart = 0; $xdata = ""; $basedata = "";

			$data_library = array(); $base_arry = array();
			
			foreach($result as $key => $val) {
				
				$num += 1;
				$dbrowid = $result[$arryStart]['id'];

				$xdata .= '<tr class="white-grey-state motion">';
				$xdata .= '<td class="alignct right-pull-5 left-pull-5">'.$num.'.</td>';
				
				if(in_array('form-ctrl', $format)) {
					$lastindexdata = count($format) - 1;
					if(stristr($format[$lastindexdata],'allow-check-for')) {
						$chkfor = str_replace('allow-check-for-','',$format[$lastindexdata]);
						if($result[$arryStart][$chkfor] == 0) { $chkctr = ""; }
						elseif($result[$arryStart][$chkfor] == 1) { $chkctr = " disabled"; }
					} else {
						$chkctr = "";
					}

					$xdata .= '<td class="alignct cs-width-30 pads7"><div class="grey-theme pads7 xsml-rounded-button"><input type="checkbox" name="checkers[]" value="'.$dbrowid.'"'.$chkctr.'></div></td>';
				}

				$wgtval = ""; $keyparam = ""; $passval = ""; $passkey = ""; $color = "";

				$data_row = array(); $indexing = 0; $setindex = "";

				foreach($val as $xkey => $xval) {
					if(in_array($xkey, $cols)) {
						$setindex = array_search($xkey, $cols);
						$totval = array();
						//if($xkey === $cols[$indexing]) {
							$passkey = $xkey;
							if(array_key_exists($xkey, $extdata)) {
								$keyparam = $extdata[$xkey];
								$keyparam['datakey'] = $xval;
								if($keyparam['origin'] == 'db') { $wgtval = idget_foreign_data($keyparam); }
								elseif($keyparam['origin'] == 'setarry') { $invar = $keyparam['tbl']; $wgtval = arrayget_key($$invar,trim($xval)); }
								$passval = $wgtval; $color = wgtcolor($passval);
								if(stristr($hAdf[$setindex],'(nf)')) {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
									$passval = number_format($passval);
								} elseif(stristr($hAdf[$setindex],'(df)')) {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = date($nth_df,strtotime($passval));
								} elseif(stristr($hAdf[$setindex],'(tc)')) {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = ucwords($passval);
								} else {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = $passval;
								}

								if(stristr($hAdf[$setindex],'(fx)')) {
									$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font" style="color:'.$color.'" onclick="jsxView('.$xval.')" title="Click for more details">'.$passval.'</td>';
								} else {
									$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
								}

							} else {
								$passval = $xval; $color = wgtcolor($passval);
								if(stristr($hAdf[$setindex],'(nf)')) {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
									$passval = number_format($passval);
								} elseif(stristr($hAdf[$setindex],'(df)')) {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = date($nth_df,strtotime($passval));
								} elseif(stristr($hAdf[$setindex],'(tc)')) {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = ucwords($passval);
								} else {
									if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
									$passval = $passval;
								}

								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							}
						//}

						$indexing += 1;

					} else {
						$passkey = "";
						$passval = "";
					}

					if($passkey != "" && $passval != "") { $data_row[$passkey] = $passval; }
				}

				if(in_array('allow-edit', $format)) {
					$xdata .= '<td class="alignct"><a href="javascript://" class="black-font" onclick="jsEdit('.$dbrowid.')" title="Edit"><div class="cs-width-20 cs-height-20 top-pull-5 rounded-element slate-blue-theme white-font alignct noscroll"><b class="fas fa-edit"></b></div></a></td>';
				}

				if(in_array('allow-view', $format)) {
					$xdata .= '<td class="alignct"><a href="javascript://" class="black-font" onclick="jsView('.$dbrowid.')" title="See More"><div class="cs-width-20 cs-height-20 top-pull-5 rounded-element royal-blue-theme white-font alignct noscroll"><b class="fas fa-book-open"></b></div></a></td>';
				}

				$xdata .= '</tr>';

				array_push($data_library, $data_row);
				$arryStart += 1;
			}

			#for base val
			if(is_array($base_arry) && count($base_arry)) {
				
				$basedata .= '<tr>';
				$basedata .= '<td class="">&nbsp;</td>';

				if(in_array('form-ctrl', $format)) {
					$basedata .= '<td class="">&nbsp;</td>';
				}
				
				foreach($base_arry as $xbkey => $xbval) {
					if($xbval === 'na') {
						$basedata .= '<td class="alignct right-pull-7 left-pull-7">&nbsp;</td>';
					} else {
						$basedata .= '<td class="grey-theme alignct right-pull-7 left-pull-7 nunito-semibold">'.number_format($xbval).'</td>';
					}
				}

				if(in_array('allow-edit', $format)) {
					$basedata .= '<td class="">&nbsp;</td>';
				}

				if(in_array('allow-view', $format)) {
					$basedata .= '<td class="">&nbsp;</td>';
				}

				$basedata .= '</tr>';

			} else {
				$basedata = '';
			}

			$xtable .= '<form action="" method="post" id="datasheet">';
			$xtable .= '<input type="hidden" name="ftask" id="ftask">';
			$xtable .= '<input type="hidden" name="xtbl" id="xtbl" value="'.$tbl.'">';
			$xtable .= '<table cellpadding="0" cellspacing="0">';
			$xtable .= '<tr>'.$xheader.'</tr>';
			$xtable .= $xdata;
			
			if(in_array('use-base-data', $format)) { $xtable .= $basedata; }
			
			$xtable .= '</table>';
			$xtable .= '</form>';

			if(isset($_SESSION['csvExcel']) || isset($_SESSION['csvHeaders'])) {
				unset($_SESSION['csvExcel']);
				unset($_SESSION['csvHeaders']);
			}

			$_SESSION['csvExcel'] = $data_library;
			$_SESSION['csvHeaders'] = $csvheader;

		} elseif(in_array('form', $format)) {
			$xtable = $result;
		}
	} else {
		$xtable .= '<form action="" method="post" id="datasheet">';
		$xtable .= '<input type="hidden" name="ftask" id="ftask">';
		$xtable .= '</form>';
		$xtable .= '<div class="cs-height-100"></div>
							<div class="block-element" align="center">
								<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
									<span class="block-element nc-height-35"></span>
									<b class="mbri-pages ft-Lsize nobold"></b>
								</div>
								<h3 class="xlarge nobold dark-grey-font">No records found</h3>
							</div>';
		
	}

	$response = $xtable;
	return $response;
}

//end of function ------------------------------------------------/

function mysqlpost($entry,$params) {
	
	$table = $params['tbls'];
	$dataset = $params['datasets'];
	$constrain = $params['constrains'];
	$isloop = $params['loop'];
	$pst_header_msg = $params['header'];
	$pst_body_msg = $params['body'];

	$ispop = "";

	if($entry == 'update') {
		
		if($isloop == 1) {
			$ispop = mysqli_data_update($table,$dataset,$constrain);
		} elseif($isloop == 2) {
			$pass = explode(';',$dataset);
			$query = explode(';',$constrain);

			for($i=0; $i <= count($pass); $i++) {
				if($pass[$i] != '') { $ispop = mysqli_data_update($table,$pass[$i],$query[$i]); }
			}
		}
		
	} elseif($entry == 'insert') {
		if($isloop == 1) {
			$ispop = mysqli_data_insert($table,$dataset,$constrain);
		} elseif($isloop == 2) {
			$pass = explode(';',$dataset);
			$query = explode(';',$constrain);

			for($i=0; $i <= count($pass); $i++) {
				if($pass[$i] != '') { $ispop = mysqli_data_insert($table,$pass[$i],$query[$i]); }
			}
		}
	} elseif($entry == 'delete') {
		if($isloop == 1) {
			$ispop = trash_record($table,$constrain);
		} elseif($isloop == 2) {
			$query = explode(';',$constrain);

			for($i=0; $i <= count($query); $i++) {
				if($query[$i] != '') { $ispop = trash_record($table,$query[$i]); }
			}
		}
	}

	global $saynotify, $post_header, $post_message, $mysql_rowid;
	
	if($ispop['isaffected'] == true) {
		$mysql_rowid = $ispop['rowid'];
		if(isset($pst_header_msg) && !empty($pst_header_msg)) { $post_header = $pst_header_msg; }
		//else { $post_header = ""; }
		if(isset($pst_body_msg) && !empty($pst_body_msg)) { $saynotify = 1; $post_message = $pst_body_msg; }
		//else { $post_message = ""; }
	} else {
		$mysql_rowid = 0;
		$saynotify = 1;
		$post_header = "Post Notification";
		$post_message = "Unable to complete request. Retry";
	}
}

//end of function ------------------------------------------------/

function wgtcolor($val) {
	
	$textcolor = ""; //set color to none

	$ptextnames = array('Active','Paid','Credited','Successful','Available','Ready','Approved');
	$ntextnames = array('Not active','Unpaid','Not credited','Pending','Expired','Not available','Awaiting','Rejected','Not signed');
	$xntextnames = array('Overdue','Archived','Blacklist','Printed','On Hold');
	
	if(in_array($val, $ptextnames)) { $textcolor = "#228b22"; }
	elseif(in_array($val, $ntextnames)) { $textcolor = "#ff0000"; }
	elseif(in_array($val, $xntextnames)) { $textcolor = "#999999"; }
	else { $textcolor = "#000000"; }
	
	return $textcolor;
}

//end of function ------------------------------------------------/

function getWeekdays($startdate,$enddate,$getparam) {

	$start_date = strtotime($startdate) - 1;
	$last_date = strtotime($enddate);

	$weekday_arry = array();
	$nweekday_arry = array();
	$date_arry = array();
	
	if($getparam == 'nameweekday') {
		
		while($start_date < $last_date) {
		    $start_date = strtotime('+1 day', $start_date);
		    array_push($weekday_arry,date('l',$start_date));
		}

		return $weekday_arry;

	} elseif($getparam == 'numberweekday') {
		
		while($start_date < $last_date) {
		    $start_date = strtotime('+1 day', $start_date);
		    array_push($nweekday_arry,date('N',$start_date));
		}

		return $nweekday_arry;

	} elseif($getparam == 'daterange') {
		
		while($start_date < $last_date) {
		    $start_date = strtotime('+1 day', $start_date);
		    array_push($date_arry,date('Y-m-d',$start_date));
		}

		return $date_arry;
	}
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
	switch ($m) {
		case 1:
			return 'January';
			break;
		case 2:
			return 'Febuary';
			break;
		case 3:
			return 'March';
			break;
		case 4:
			return 'April';
			break;
		case 5:
			return 'May';
			break;
		case 6:
			return 'June';
			break;
		case 7:
			return 'July';
			break;
		case 8:
			return 'August';
			break;
		case 9:
			return 'September';
			break;
		case 10:
			return 'October';
			break;
		case 11:
			return 'November';
			break;
		case 12:
			return 'December';
			break;
		
		default:
			return 'Undefined';
			break;
	}
}

//end of function ------------------------------------------------//



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
				$th_cmd .= '<div class="block-element bottom-push-10"><span class="ln-display-box float-left right-push-7"><input type="checkbox" name="checkers[]" value="'.$cmd_key.'"></span><span class="ln-display-box float-left ft-sml-size"> '.$cmd_value.'</span><span class="block-element new-line-space"></span></div>';
			}
		} elseif($field == 'radio') {
			foreach ($arry as $cmd_key => $cmd_value) {
				$th_cmd .= '<div class="block-element bottom-push-10"><span class="ln-display-box float-left right-push-7"><input type="radio" name="single-opt" value="'.$cmd_key.'"></span><span class="ln-display-box float-left ft-sml-size"> '.$cmd_value.'</span><span class="block-element new-line-space"></span></div>';
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
				$th_cmd .= '<option value="'.ucwords(strtolower($scalar)).'">'.ucwords(strtolower($scalar)).'</option>';
			}

		} elseif($field == 'checkbox') {

			foreach ($arry as $scalar) {
				$th_cmd .= '<input type="checkbox" name="checkers[]" value="'.ucwords(strtolower($scalar)).'"> '.ucwords(strtolower($scalar));
			}

		} elseif($field == 'radio') {

			foreach ($arry as $scalar) {
				$th_cmd .= '<input type="radio" name="single-opt" value="'.ucwords(strtolower($scalar)).'"> '.ucwords(strtolower($scalar));
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
		case 7:
			$get_this_date = date("D. F j, Y",strtotime($this_date));
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
			$get_this_time = date("h:i",strtotime($this_time));
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

	$diff = abs(strtotime($dateto) - strtotime($datefrom));
				
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
				
	$leftyears = $years;
	$leftmonths = $months;
	$leftdays = $days;

	return $leftdays;
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

function arrayget_keydata($arraystring,$key,$keyval) {

	foreach($arraystring as $mainkey => $datakey) {
		if($key == $mainkey) {
			$data_result = $datakey[$keyval];
			break;
		}
	}

	return $data_result;
}

//end of function ------------------------------------------------/

function getImageUploadSize($uplimage,$rw,$rh)
{
	global $gWidth;
	global $gHeight;
	
	list($width, $height) = getimagesize($uplimage);
	
	if(isset($rw) && isset($rh) && is_numeric($rw) && is_numeric($rh))
	{
		if($width <= $rw && $height <= $rh) 
		{
			$gWidth = $width;
			$gHeight = $gHeight;
			
			return 2;
		}
		else
		{
			$gWidth = 0;
			$gHeight = 0;
			
			return 1;
		}
	}
}

//end of function ------------------------------------------------/

function var_dump_out($var) {
	
	if(isset($var) && !empty($var)) { return true; }
	else { return false; }
}

//end of function ------------------------------------------------/

function var_type_dump_out($var,$type) {
	
	if($type == 'EMAIL') {
		if(!filter_var($var, FILTER_VALIDATE_EMAIL)) { return false; }
		else { return true; }
	} elseif($type == 'NUMBER') {
		if(is_numeric($var)) { return true; }
		else { return false; }
	} elseif($type == 'INT') {
		if(is_int($var)) { return true; }
		else { return false; }
	} elseif($type == 'URL') {
		if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {return false; }
		else { return true; }
	} elseif($type == 'DATE') {
		$gdate = str_replace('-','',$var);
		if(is_int($gdate) && strlen($gdate) == 8) { return true; }
		else { return false; }
	}
}

//end of function ------------------------------------------------/

function remove_data_injection($var) {
  
	$var = strip_tags($var);
	$var = trim($var);
	$var = stripslashes($var);
	$var = htmlspecialchars($var);
	$var = str_replace("'","",$var);

	return $var;
}

//end of function ------------------------------------------------/

function fileuploader($tmpfile,$rawfile,$accept,$path) {
  
	global $newimage;

	$ext=end(explode(".",$rawfile));
	
	if(in_array($ext, $accept)) {
		$replaceimage = 'im'.date("Ymdhis").'.'.$ext;
		$response = move_uploaded_file($tmpfile,$path.$replaceimage);

		$newimage = $replaceimage;
		
		if($response) { return 1; }
		else { return 0; }
	}	   		
}

//end of function ------------------------------------------------/

function custom_copy($src, $dst) {

	// open the source directory
	$dir = opendir($src);

	// Make the destination directory if not exist
	@mkdir($dst);

	// Loop through the files in source directory
	while($file = readdir($dir)) {
		if(($file != '.' ) && ($file != '..')) {
			if(is_dir($src . '/' . $file)) {

				// Recursively calling custom copy function
				// for sub directory
				custom_copy($src . '/' . $file, $dst . '/' . $file);
			} else {
				copy($src . '/' . $file, $dst . '/' . $file);
			}
		}
	}

	closedir($dir);
}

//end of function ------------------------------------------------/

function verifyToken($postoken) {
	
	global $rootfile;
	
	$fpath = $rootfile;
	$json = json_decode(file_get_contents($fpath.'cphp/packet.json'), true);
	$pkt = $json['jwt'];

	$isverified = false;
	$arr_hash = array();

	if(is_array($pkt)) {
		for($t=0; $t < count($pkt); $t++) {
			$hashtkn = hashToken($pkt[$t]);
			array_push($arr_hash,$hashtkn);
		}

		if(in_array($postoken, $arr_hash)) { $response = 200; }
		else { $response = 0; }
	}

	return $response;
}

//end of function ------------------------------------------------/
    
?>