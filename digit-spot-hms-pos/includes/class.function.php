<?php

function mysqli_data_exist($table,$constrain) {
	
	global $mysqli;

	$response = array();
	
	$wgt = "SELECT * FROM {$table} WHERE ".$constrain;
	$result = $mysqli->query($wgt);

	if($result->num_rows > 0) {
		$response['isdata'] = true;
		$response['dbrows'] = $result->num_rows;
	} else {
		$response['isdata'] = false;
		$response['dbrows'] = 0;
	}

	return $response;
}


function mysqli_data_array($xfunc,$sql) {
	
	global $mysqli;

	$response = array();

	$result = $mysqli->query($sql);
	
	if($xfunc == 'arithmetic-fx') {
		$data = $result->fetch_assoc();
		$response['mathr'] = $data['field'];
	} else if($xfunc == 'assoc') {
		while($data = $result->fetch_assoc()) { $response[] = $data; }
		$result->free();
	}

	return $response;
}


function wgetSQL($queryset) {
	
	$result = mysqli_data_array('assoc',$queryset);
	
	$response = "";

	if(is_array($result) && count($result) > 0) {
		$response = $result;
	} else {
		$response = null;
	}
	
	return $response;
}


function class_data_pagenation($display,$start,$total) {
	
	global $curpage;

	/*$pg_start = $start; $pg_limit = $display; $to = $display;
	$meanumber = $total / $display; $loop = number_format($meanumber,1);*/

	$pg_start = $start; $pg_limit = $display; $to = $display;
	$meanumber = $total / $display;

	if(($total - $display) == 1) { $meanumber = 2; }
	else { $meanumber = $meanumber; }

	if(is_int($meanumber)) { $loop = $meanumber; }
	else { $loop = (round($meanumber)) + 1; }
	
	if(isset($total) && isset($display) && $total > $display)
	{
		$selectopt = '';

		$selectopt .= '<select name="pagenumbr" id="pagenumbr" onchange="nextpage(this.value)" class="nopads no-back-black">';
		
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


function idget_name($id,$name,$tbl) {
	$queryset = "SELECT {$name} FROM {$tbl} WHERE id = {$id}";
	$isSql = wgetSQL($queryset);
	if(!empty($isSql[0][$name])) { return $isSql[0][$name]; }
	else { return "Unknown"; }
}


function idget_fname($id,$col,$name,$tbl) {
	$queryset = "SELECT {$name} FROM {$tbl} WHERE {$col} = '{$id}'";
	$isSql = wgetSQL($queryset);
	if(!empty($isSql[0][$name])) { return $isSql[0][$name]; }
	else { return "Unknown"; }
}


function idget_global($key,$arr) {
	
	if(!empty($arr) && count($arr) > 0) {
		
		global $_gparams;

		$tbl = $_gparams[$arr]['tbl'];
		$col = $_gparams[$arr]['col'];
		$name = $_gparams[$arr]['name'];

		$queryset = "SELECT {$name} FROM {$tbl} WHERE {$col} = '{$key}'";
		$isSql = wgetSQL($queryset);
		
		if(!empty($isSql[0][$name])) { $_gparams[$arr]['returnval'] = $isSql[0][$name]; }
		else { $_gparams[$arr]['returnval'] = "Null value"; }
	}
}


function idget_foreign_data($fparams) {
	
	$tbl = $fparams['tbl'];
	$col = $fparams['col'];
	$key = $fparams['datakey'];

	if(strstr($col,',')) { $sql = "SELECT {$col} FROM {$tbl} WHERE id = '{$key}'"; }
	else { $sql = "SELECT * FROM {$tbl} WHERE id = '{$key}'"; }
	
	$result = mysqli_data_array('assoc',$sql);
	
	$response = ""; $doubledata = "";

	if(is_array($result) && count($result) > 0) {
		if(strstr($col,',')) {
			$explode_data = explode(',',$col);
			foreach($explode_data as $coldt) { $doubledata .= $result[0][$coldt].' - '; }
			$response = substr_replace($doubledata,'',-3,3);
		} elseif(strstr($col,'-')) {
			$explode_data = explode('-',$col);
			foreach($explode_data as $coldt) { $doubledata .= $result[0][$coldt].' '; }
			$response = $doubledata;
		} elseif(strstr($col,'/')) {
			$explode_data = explode('/',$col);
			foreach($explode_data as $coldt) { $doubledata .= $result[0][$coldt]; }
			$response = $doubledata;
		} elseif(strstr($col,'@')) {
			$doubledata .= $result[0][$col];
			$response = $doubledata;
		} else {
			$response = $result[0][$col];
		}
	} else {
		$response = "N/A";
	}
	
	return $response;
}


function data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata) {
	
	global $data_status, $process_status, $currency, $avail_status, $nth_df, $arry_unwanted, $uoms, $nth_dfn;
	global $bill_type, $food_type, $force_tabs, $allow_iou_receipt_print, $removedash;

	global $value_compare;
	global $allow_gridlines;

	if(is_array($value_compare) && count($value_compare)) {
		$cckey = array_shift(array_keys($value_compare));
		$ccval = array_shift(array_values($value_compare));
	} else {
		$cckey = null;
		$ccval = null;
	}

	$sql = "SELECT * FROM {$tbl} WHERE ".$queryset;
	$result = mysqli_data_array('assoc',$sql);

	$response = "";
	$xtable = "";

	if(is_array($result) && count($result) > 0) {
		if(in_array('grid', $format)) {
			
			//extract table header from included array

			$cols = array(); $csvheader = array(); $hAdf = array(); $ncols = array();
			$allow_form_check = "";

			$xheader = '<td class="">&nbsp;</td>';
			
			if(in_array('form-ctrl', $format)) {
				$xheader .= '<td class="alignct"><input type="checkbox" id="cchk" value="off" class="nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button motion" onclick="chkAll(this.id)" title="Auto-check"></td>';
			}

			$eheader = "";

			foreach($keys as $col => $header) {
				$eheader = ucwords(str_replace($arry_unwanted,'',$header));
				$xheader .= '<td class="default-text-font-bold alignct">'.$eheader.'</td>';
				array_push($cols,$col);
				array_push($csvheader,$eheader);
				array_push($hAdf,$header);
			}

			if(is_array($force_tabs)) {
				foreach($force_tabs as $key => $obj) {
					$xheader .= '<td class="default-text-font-bold alignct">'.ucwords($obj['th']).'</td>';
				}
			}

			if(in_array('allow-edit', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			if(in_array('allow-view', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			//extract table data from from database array
			$num=$startnumbr; $arryStart = 0; $xdata = ""; $xdatax = ""; $basedata = "";

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

					if(trim($chkctr) == 'disabled' && (isset($allow_iou_receipt_print) && $allow_iou_receipt_print == true)) {
						$xdata .= '<td class="alignct cs-width-30 pads7"><a href="javascript://" class="blue-font" onclick="jsReceipt('.$dbrowid.')" title="Click to print receipt form"><b class="fa-print nobold"></b></a></td>';
					} else {
						$xdata .= '<td class="alignct cs-width-30 pads7"><div class="grey-theme pads7 xsml-rounded-button"><input type="checkbox" name="checkers[]" class="checkers" value="'.$dbrowid.'"'.$chkctr.'></div></td>';
					}
				}

				$wgtval = ""; $keyparam = ""; $passval = ""; $passkey = ""; $color = "";

				$data_row = array(); $indexing = 0; $setindex = ""; $pass_param_link = ""; $forcekey = "";

				foreach($val as $xkey => $xval) {
					
					$forcekey = $cols[$indexing];
					
					if(in_array($xkey, $cols)) {
						
						$setindex = array_search($xkey, $cols);
						$totval = array();

						$passkey = $xkey;

						if(array_key_exists($xkey, $extdata)) {
							
							if(stristr($hAdf[$setindex],'(bx)')) {
								if($xkey == 'biller') {
									if($result[$arryStart]['billtype'] == 1) {
										global $tbL102,$tbL169;
										$extdata[$xkey]['tbl'] = $tbL169;
										$extdata[$xkey]['col'] = 'fname-lname';
										$keyparam = $extdata[$xkey];
										$keyparam['datakey'] = $result[$arryStart]['customerid'];
									} elseif($result[$arryStart]['billtype'] == 2) {
										global $tbL56;
										$extdata[$xkey]['tbl'] = $tbL56;
										$extdata[$xkey]['col'] = 'roomprefix/roomnumber';
										$keyparam = $extdata[$xkey];
										$keyparam['datakey'] = $result[$arryStart]['roomid'];
									} elseif($result[$arryStart]['billtype'] == 3) {
										global $tbL33;
										$extdata[$xkey]['tbl'] = $tbL33;
										$extdata[$xkey]['col'] = 'name';
										$keyparam = $extdata[$xkey];
										$keyparam['datakey'] = $xval;
									} elseif($result[$arryStart]['billtype'] == 4) {
										global $tbL58;
										$extdata[$xkey]['tbl'] = $tbL58;
										$extdata[$xkey]['col'] = 'name';
										$keyparam = $extdata[$xkey];
										$keyparam['datakey'] = $xval;
									} elseif($result[$arryStart]['billtype'] == 5) {
										global $tbL7;
										$extdata[$xkey]['tbl'] = $tbL7;
										$extdata[$xkey]['col'] = 'staffname';
										$keyparam = $extdata[$xkey];
										$keyparam['datakey'] = $xval;
									}
								}
							} else {
								$keyparam = $extdata[$xkey];
								$keyparam['datakey'] = $xval;
							}

							if($keyparam['origin'] == 'db') { $wgtval = idget_foreign_data($keyparam); }
							elseif($keyparam['origin'] == 'setarry') { $invar = $keyparam['tbl']; $wgtval = arrayget_key($$invar,trim($xval)); }
							$passval = $wgtval; $color = wgtcolor($passval);
							if(stristr($hAdf[$setindex],'(nf)')) {
								if(isset($removedash) && $removedash == true) {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + str_replace('-','',$passval); } else { $base_arry[$passkey] = str_replace('-','',$passval); }
								 	$passval = number_format(str_replace('-','',$passval),2);
								} else {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
									$passval = number_format($passval,2);
								}
							} elseif(stristr($hAdf[$setindex],'(df)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = date($nth_dfn,strtotime($passval));
							} elseif(stristr($hAdf[$setindex],'(tc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = ucwords($passval);
							} elseif(stristr($hAdf[$setindex],'(uc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = strtoupper($passval);
							} else {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = $passval;
							}

							if(stristr($hAdf[$setindex],'(fx)')) {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$xval.')" title="Click for more details">'.$passval.'</td>';
							} elseif(stristr($hAdf[$setindex],'(pr)')) {
								if(!empty($cckey) && $result[$arryStart][$cckey] === $ccval) { $xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.' <a href="javascript:void(0)" class="black-font left-push-5" title="Click to print receipt" onclick="jsxPrint(this)" lang="'.$result[$arryStart]['id'].'"><b class="fa-print nobold"></b></a><p class="top-pull-3">[<a href="javascript:void(0)" class="blue-font" title="Click to reverse payment" onclick="reversePy(this)" lang="'.$result[$arryStart]['id'].'">Reverse</a>]</p></td>'; } else { $xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>'; }
							} elseif(stristr($hAdf[$setindex],'(nl)')) {
								$xdata .= '<td class="cs-width-250 alignlt right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							} else {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							}

						} else {
							$passval = $xval; $color = wgtcolor($passval);
							if(stristr($hAdf[$setindex],'(nf)')) {
								if(isset($removedash) && $removedash == true) {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + str_replace('-','',$passval); } else { $base_arry[$passkey] = str_replace('-','',$passval); }
								 	$passval = number_format(str_replace('-','',$passval),2);
								} else {
									if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
									$passval = number_format($passval,2);
								}
							} elseif(stristr($hAdf[$setindex],'(df)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = date($nth_dfn,strtotime($passval));
							} elseif(stristr($hAdf[$setindex],'(tc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = ucwords($passval);
							} elseif(stristr($hAdf[$setindex],'(uc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = strtoupper($passval);
							} else {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = $passval;
							}

							if(stristr($hAdf[$setindex],'(fx)')) {
								$pass_param_link = "'".$passval."'";
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$pass_param_link.')" title="Click for more details">'.$passval.'</td>';
							} elseif(stristr($hAdf[$setindex],'(pr)')) {
								if(!empty($cckey) && $result[$arryStart][$cckey] === $ccval) { $xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.' <a href="javascript:void(0)" class="black-font left-push-5" title="Click to print receipt" onclick="jsxPrint(this)" lang="'.$result[$arryStart]['id'].'"><b class="fa-print nobold"></b></a><p class="top-pull-3">[<a href="javascript:void(0)" class="blue-font" title="Click to reverse payment" onclick="reversePy(this)" lang="'.$result[$arryStart]['id'].'">Reverse</a>]</p></td>'; } else { $xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>'; }
							} elseif(stristr($hAdf[$setindex],'(nl)')) {
								$xdata .= '<td class="cs-width-250 alignlt right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							} else {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							}
						}

					} else {
						$passkey = "";
						$passval = "";
					}
					
					$indexing += 1;

					if($passkey != "" && $passval != "") { $data_row[$passkey] = $passval; }
				}

				if(is_array($force_tabs)) {
					$incl_bal = "";
					foreach($force_tabs as $tdkey => $tdobj) {
					
						if($tdkey == 'isfunc') {
							if($tdobj['key'] == 'guestBillSummary') {
								$incl_bal = guestBillSummary($result[$arryStart][$tdobj['val']]);
								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">&#8358; '.number_format($incl_bal,2).'</td>';
							}
						} else {
							if($tdobj['th'] == 'corporate' && $tdkey == 'bill_to') {
								global $tbL58;
								$cbc = idget_fdata($tdobj['tbl'],$tdobj['key'],$val[$tdobj['val']],'bill_to');
								$cbg = idget_fdata($tdobj['tbl'],$tdobj['key'],$val[$tdobj['val']],'bill_to_g');
								$tobill = (!empty($cbc) && $cbc > 0) ? $cbc : $cbg;

								$passkey = $tdkey;
								
								if($tobill > 0) { $passval = idget_data($tbL58,$tobill,'name'); }
								else { $passval = "N/A"; }

								$color = wgtcolor($passval);
							} else {
								$wgtval = idget_fdata($tdobj['tbl'],$tdobj['key'],$val[$tdobj['val']],$tdkey);
										
								$passkey = $tdkey;
								$passval = ucwords($wgtval); $color = wgtcolor($passval);
							}
						}

						$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
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
						$basedata .= '<td class="grey-theme alignct right-pull-7 left-pull-7 default-text-font-bold">'.number_format($xbval,2).'</td>';
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

			if($allow_gridlines && $allow_gridlines == true) { $xtable .= '<table cellpadding="3" cellspacing="0" border="1">'; }
			else { $xtable .= '<table cellpadding="0" cellspacing="0">'; }

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
								<div class="box-border-thick cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
									<span class="block-element nc-height-35"></span>
									<b class="mbri-pages ft-xlarge-size nobold"></b>
								</div>
								<h3 class="xlarge nobold dark-grey-font">No records found</h3>
							</div>';
		
	}

	$response = $xtable;
	return $response;
}

#end func

function data_row_dpl2($queryset,$keys,$format,$startnumbr,$extdata) {
	
	global $data_status, $process_status, $currency, $avail_status, $nth_df, $arry_unwanted, $uoms, $nth_dfn;
	global $bill_type, $food_type, $force_tabs;

	global $allow_gridlines;
	
	//$sql = "SELECT * FROM {$tbl} WHERE ".$queryset;
	$result = mysqli_data_array('assoc',$queryset);

	$response = "";
	$xtable = "";

	if(is_array($result) && count($result) > 0) {
		if(in_array('grid', $format)) {
			
			//extract table header from included array

			$cols = array(); $csvheader = array(); $hAdf = array(); $ncols = array();
			$allow_form_check = "";

			$xheader = '<td class="">&nbsp;</td>';
			
			if(in_array('form-ctrl', $format)) {
				$xheader .= '<td class="alignct"><input type="checkbox" id="cchk" value="off" class="nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button motion" onclick="chkAll(this.id)" title="Auto-check"></td>';
			}

			$eheader = "";

			foreach($keys as $col => $header) {
				$eheader = ucwords(str_replace($arry_unwanted,'',$header));
				$xheader .= '<td class="default-text-font-bold alignct">'.$eheader.'</td>';
				array_push($cols,$col);
				array_push($csvheader,$eheader);
				array_push($hAdf,$header);
			}

			if(is_array($force_tabs)) {
				foreach($force_tabs as $key => $obj) {
					$xheader .= '<td class="default-text-font-bold alignct">'.ucwords($obj['th']).'</td>';
				}
			}

			if(in_array('allow-edit', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			if(in_array('allow-view', $format)) {
				$xheader .= '<td class="">&nbsp;</td>';
			}

			//extract table data from from database array
			$num=$startnumbr; $arryStart = 0; $xdata = ""; $xdatax = ""; $basedata = "";

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

					$xdata .= '<td class="alignct cs-width-30 pads7"><div class="grey-theme pads7 xsml-rounded-button"><input type="checkbox" name="checkers[]" class="checkers" value="'.$dbrowid.'"'.$chkctr.'></div></td>';
				}

				$wgtval = ""; $keyparam = ""; $passval = ""; $passkey = ""; $color = "";

				$data_row = array(); $indexing = 0; $setindex = ""; $pass_param_link = ""; $forcekey = "";

				foreach($val as $xkey => $xval) {
					
					$forcekey = $cols[$indexing];
					
					if(in_array($xkey, $cols)) {
						
						$setindex = array_search($xkey, $cols);
						$totval = array();

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
							} elseif(stristr($hAdf[$setindex],'(dc)')) {
								if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
								$passval = number_format($passval,2);
							} elseif(stristr($hAdf[$setindex],'(df)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = date($nth_dfn,strtotime($passval));
							} elseif(stristr($hAdf[$setindex],'(tc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = ucwords($passval);
							} else {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = $passval;
							}

							if(stristr($hAdf[$setindex],'(fx)')) {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$xval.')" title="Click for more details">'.$passval.'</td>';
							} elseif(stristr($hAdf[$setindex],'(xfx)')) {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$dbrowid.')" title="Click for more details">'.$passval.'</td>';
							} else {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							}

						} else {
							$passval = $xval; $color = wgtcolor($passval);
							if(stristr($hAdf[$setindex],'(nf)')) {
								if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
								$passval = number_format($passval);
							} elseif(stristr($hAdf[$setindex],'(dc)')) {
								if(array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = $base_arry[$passkey] + $passval; } else { $base_arry[$passkey] = $passval; }
								$passval = number_format($passval,2);
							} elseif(stristr($hAdf[$setindex],'(df)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = date($nth_dfn,strtotime($passval));
							} elseif(stristr($hAdf[$setindex],'(tc)')) {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = ucwords($passval);
							} else {
								if(!array_key_exists($passkey,$base_arry)) { $base_arry[$passkey] = 'na'; }
								$passval = $passval;
							}

							if(stristr($hAdf[$setindex],'(fx)')) {
								$pass_param_link = "'".$passval."'";
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$pass_param_link.')" title="Click for more details">'.$passval.'</td>';
							} elseif(stristr($hAdf[$setindex],'(xfx)')) {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7 anchor blue-font default-text-font-bold" onclick="jsxView('.$dbrowid.')" title="Click for more details">'.$passval.'</td>';
							} else {
								$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
							}
						}

					} else {
						$passkey = "";
						$passval = "";
					}
					
					$indexing += 1;

					if($passkey != "" && $passval != "") { $data_row[$passkey] = $passval; }
				}

				if(is_array($force_tabs)) {
					foreach($force_tabs as $tdkey => $tdobj) {
					
						$wgtval = idget_fdata($tdobj['tbl'],$tdobj['key'],$val[$tdobj['val']],$tdkey);
								
						$passkey = $tdkey;
						$passval = ucwords($wgtval); $color = wgtcolor($passval);

						$xdata .= '<td class="alignct right-pull-7 left-pull-7" style="color:'.$color.'">'.$passval.'</td>';
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
						$basedata .= '<td class="grey-theme alignct right-pull-7 left-pull-7 default-text-font-bold">'.number_format($xbval).'</td>';
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
			
			if($allow_gridlines && $allow_gridlines == true) { $xtable .= '<table cellpadding="3" cellspacing="0" border="1">'; }
			else { $xtable .= '<table cellpadding="0" cellspacing="0">'; }
			
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
								<div class="box-border-dark-thick cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
									<span class="block-element nc-height-35"></span>
									<b class="mbri-pages ft-xxLsize nobold"></b>
								</div>
								<h3 class="xlarge nobold dark-grey-font">No records found. Try create one</h3>
							</div>';
		
	}

	$response = $xtable;
	return $response;
}

#end func
	

function wgtcolor($val) {

	$textcolor = ""; //set color to none

	$ptextnames = array('Active','Paid','Credited','Credit','Successful','Available','Ready','Approved','Received','Disbursed');
	$ntextnames = array('Not active','Unpaid','Not credited','Debit','Pending','Expired','Not available','Awaiting','Rejected','Not signed','Under Approval','Processing');
	$xntextnames = array('Overdue','Archived','Blacklist','Printed','On Hold','Completed');
	
	if(in_array($val, $ptextnames)) { $textcolor = "#228b22"; }
	elseif(in_array($val, $ntextnames)) { $textcolor = "#ff0000"; }
	elseif(in_array($val, $xntextnames)) { $textcolor = "#999999"; }
	else { $textcolor = "#000000"; }
	
	return $textcolor;
}


function pfetch($data,$tbl,$query) {
	
	if(!empty($query)) { $sql = "SELECT {$data} FROM {$tbl} WHERE {$query}"; }
	else { $sql = "SELECT {$data} FROM {$tbl}"; }

	$result = mysqli_data_array('assoc',$sql);
	
	$response = "";

	if(is_array($result) && count($result) > 0) {
		$response = $result;
	} else {
		$response = null;
	}
	
	return $response;
}


function fetch_array_record($result,$header,$jsf) {

	global $sum_group_amt;

	if(is_array($result)) {
		
		$arry_values = array_values($header);
		$arry_keys = array_keys($header);

		$xdata = '';

		$base_arry = array();
		$startnumbr = 0;

		foreach($result as $key => $val) {
			
			$isPrk = ""; $isTxt = ""; $tar = ""; $col = ""; $ref = ""; $fr = ""; $lnkval = "";
			$startnumbr += 1;  $row = 0;

			$xdata .= '<tr class="white-grey-state motion">';
			$xdata .= '<td class="right-pull-7 left-pull-7 alignlt">'.$startnumbr.'</td>';
			
			foreach($val as $rkey => $rval) {
				$tar = explode('-',$arry_values[$row]);
				$col = $tar[0]; $ref = $tar[1]; $fr = $tar[2];

				if($ref === 'pkey') { $isPrk = $rval; }
				$iswgt = "";

				if($ref === 'pkey') {
					$isTxt = $rval;
					$lnkval = "'".$isTxt."'";
					$xdata .= '<td class="right-pull-7 left-pull-7 alignct"><a href="javascript:void(0)" class="blue-font" onclick="'.$jsf.'('.$lnkval.')">'.$isTxt.'</a></td>';
				} elseif($ref === 'nokey') {
					if($fr == 'nf') {
						$isTxt = number_format($rval,2);
						if(array_key_exists($col,$base_arry)) { $base_arry[$col] = $base_arry[$col] + $isTxt; } else { $base_arry[$col] = $isTxt; }
					} elseif($fr == 'df') {
						$isTxt = date('d-m-Y',strtotime($rval));
					} elseif($fr == 'tc') {
						$isTxt = ucwords($rval);
					} else {
						$isTxt = $rval;
					}
					$xdata .= '<td class="right-pull-7 left-pull-7 alignct">'.$isTxt.'</td>';
				} else {
					
					if($fr == 'sef' || $fr == 'sefx') { $isPrk = $rval; }
					else { $isPrk = $isPrk; }

					if($ref === 'guestfx') { $iswgt = guestfx($isPrk); }
					elseif($ref === 'billfx') { $iswgt = billfx($isPrk); }
					elseif($ref === 'payfx') { $iswgt = payfx($isPrk); }
					elseif($ref === 'balfx') { $iswgt = balfx(); }
					elseif($ref === 'stayfx') { $iswgt = stayfx($isPrk); }
					elseif($ref === 'nroomfx') { $iswgt = nroomfx($isPrk); }
					elseif($ref === 'bkgstatfx') { $iswgt = bkgstatfx($isPrk); }
					elseif($ref === 'compfx') { $iswgt = compfx($isPrk); }
					else { $iswgt = "novalue"; }

					if($fr == 'nf' || $fr == 'sefx') {
						$isTxt = number_format($iswgt,2);
						if(array_key_exists($col,$base_arry)) { $base_arry[$col] = $base_arry[$col] + $iswgt; } else { $base_arry[$col] = $iswgt; }
					} elseif($fr == 'df') {
						$isTxt = date('d-m-Y',strtotime($iswgt));
					} elseif($fr == 'tc') {
						$isTxt = ucwords($iswgt);
					} else {
						$isTxt = $iswgt;
					}
					$xdata .= '<td class="right-pull-7 left-pull-7 alignct">'.$isTxt.'</td>';
				}

				$row += 1;
			}

			$xdata .= '</tr>';
		}

		if(is_array($base_arry) && count($base_arry)) {
			//print_r($base_arry);
			$btar = ""; $bcol = "";
			$xdata .= '<tr>';
			$xdata .= '<td class="right-pull-7 left-pull-7 alignlt">&nbsp;</td>';
			foreach($header as $key => $val) {
				$btar = explode('-',$val);
				
				if($btar[2] === 'nf') {
					$bcol = $btar[0];
					$xdata .= '<td class="right-pull-7 left-pull-7 alignct default-text-font-bold grey-theme">'.number_format($base_arry[$bcol],2).'</td>';
				} else {
					$xdata .= '<td class="right-pull-7 left-pull-7 alignct">&nbsp;</td>';
				}
			}
			$xdata .= '</tr>';
		}

		return $xdata;
	}
}


function rowHeader($theader) {
	
	global $nth_df, $arry_unwanted;

	if(is_array($theader)) {
		
		$xheader = ""; $eheader = "";
		
		foreach($theader as $th => $fx) {
			$eheader = ucwords(str_replace($arry_unwanted,'',$th));
			$xheader .= '<td class="default-text-font-bold alignct">'.$eheader.'</td>';
		}

		return '<tr><td>&nbsp;</td>'.$xheader.'</tr>';
	}
}


function rowHeader2($theader) {
	
	global $nth_df, $arry_unwanted, $isnum;

	if(is_array($theader)) {
		
		$xheader = ""; $eheader = "";
		
		foreach($theader as $th) {
			$eheader = ucwords(str_replace($arry_unwanted,'',$th));
			$xheader .= '<td class="default-text-font-bold alignct">'.$eheader.'</td>';
		}

		if($isnum == false) { return '<tr>'.$xheader.'</tr>'; }
		else { return '<tr><td>&nbsp;</td>'.$xheader.'</tr>'; }
	}
}


function guestfx($key) {
	global $tbL102; $guest_name = "";
	$query = "(booking_number='{$key}' AND primary_guest=1) OR (id='{$key}')";
	$wget = pfetch('*',$tbL102,$query);
	if(!empty($wget)) { $guest_name = $wget[0]['fname'].' '.$wget[0]['lname']; }
	return $guest_name;
}


function billfx($key) {
	
	global $tbL134, $tbL100, $var_amt;
	
	$room_billed_amount = 0; $pos_billed_amount = 0;

	$query = "booking_number='{$key}' OR roomid='{$key}'";
	$dataset = "SUM(room_amount) AS 'amt1', SUM(tax_amount) AS 'amt2', SUM(consumption_tax_amount) AS 'amt3', SUM(service_charge) AS 'amt4', SUM(discount_amount) AS 'amt5'";
	$wget = pfetch($dataset,$tbL134,$query);

	$query2 = "(booking_number='{$key}' OR roomid='{$key}') AND payment='Pending'";
	$dataset2 = "SUM(bill_amount) AS 'amt6'";
	$wget2 = pfetch($dataset2,$tbL100,$query2);

	if(!empty($wget)) { $room_billed_amount = ($wget[0]['amt1'] + $wget[0]['amt2'] + $wget[0]['amt3'] + $wget[0]['amt4']) - $wget[0]['amt5']; }

	if(!empty($wget2)) { $pos_billed_amount = $wget2[0]['amt6']; }

	$total_bill = $room_billed_amount + $pos_billed_amount;
	$var_amt['bill'] = $total_bill;

	return $total_bill;
}


function cbillfx($key) {
	
	global $tbL134, $tbL100, $var_amt;
	
	$room_billed_amount = 0; $pos_billed_amount = 0;

	$query = "booking_number='{$key}' AND deletedata=0";
	$dataset = "SUM(room_amount) AS 'amt1', SUM(tax_amount) AS 'amt2', SUM(consumption_tax_amount) AS 'amt3', SUM(service_charge) AS 'amt4', SUM(discount_amount) AS 'amt5'";
	$wget = pfetch($dataset,$tbL134,$query);

	$query2 = "booking_number='{$key}' AND isreversed=0";
	$dataset2 = "SUM(bill_amount) AS 'amt6'";
	$wget2 = pfetch($dataset2,$tbL100,$query2);

	if(!empty($wget)) { $room_billed_amount = ($wget[0]['amt1'] + $wget[0]['amt2'] + $wget[0]['amt3'] + $wget[0]['amt4']) - $wget[0]['amt5']; }

	if(!empty($wget2)) { $pos_billed_amount = $wget2[0]['amt6']; }

	$total_bill = $room_billed_amount + $pos_billed_amount;
	$var_amt['bill'] = $total_bill;

	return $total_bill;
}


function billrfx($key,$sql,$tbl) {
	
	global $addon;
	
	if(isset($addon) && !empty($addon)) { $query = $addon."booking_number='{$key}'"; }
	else { $query = "booking_number='{$key}'"; }

	$dataset = "SUM({$sql}) AS 'amt'";
	$wget = pfetch($dataset,$tbl,$query);

	return $wget[0]['amt'];
}


function payfx($key) {
	global $tbL131, $var_amt; $pay_amount = 0;
	$query = "(booking_number='{$key}' OR customerid='{$key}') AND transaction_type='credit'";
	$dataset = "SUM(amount) AS 'amt1'";
	$wget = pfetch($dataset,$tbL131,$query);

	if(!empty($wget)) { $pay_amount = $wget[0]['amt1']; }
	$var_amt['pay'] = $pay_amount;

	return $pay_amount;
}


function balfx() {
	global $var_amt; $bal = 0;
	if(is_array($var_amt) && count($var_amt) > 0) { $bal = $var_amt['bill'] - $var_amt['pay']; $var_amt = array(); }
	else { $bal = $bal; }
	
	return $bal;
}

function stayfx($key) {
	global $tbL130; $stay_dur = "";
	$query = "booking_number='{$key}'";
	$dataset = "checkin_date,checkout_date";
	$wget = pfetch($dataset,$tbL130,$query);

	if(!empty($wget)) { $stay_dur = date('d-m-Y',strtotime($wget[0]['checkin_date'])).' - '.date('d-m-Y',strtotime($wget[0]['checkout_date'])); }
	
	return $stay_dur;
}

function nroomfx($key) {
	global $tbL127; $noofrooms = "";
	$query = "booking_number='{$key}'";
	$dataset = "COUNT(roomid) AS 'noofrooms'";
	$wget = pfetch($dataset,$tbL127,$query);

	if(!empty($wget)) { $noofrooms = $wget[0]['noofrooms']; }
	
	return $noofrooms;
}

function bkgstatfx($key) {
	global $tbL127; $booking_status = "";
	$query = "booking_number='{$key}' LIMIT 1";
	$dataset = "status";
	$wget = pfetch($dataset,$tbL127,$query);

	if(!empty($wget)) { $booking_status = $wget[0]['status']; }
	
	return $booking_status;
}

function compfx($key) {
	global $tbL33; $compl = "";
	$query = "id={$key}";
	$dataset = "name";
	$wget = pfetch($dataset,$tbL33,$query);

	if(!empty($wget)) { $compl = $wget[0]['name']; }
	
	return $compl;
}

?>