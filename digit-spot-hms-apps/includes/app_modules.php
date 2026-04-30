<?php

	/* rockview hotels application modules */

	$application_module = array(
		"Administration" => 1,
		"Accounting" => 2,
		"Booking" => 3,
		"Housekeeping" => 4,
		"Sales" => 5,
		"Recreation" => 6,
		"Material Control" => 7,
		"Point of Sales" => 8,
		"Reports" => 9,
		"Module Operations" => 200
	);

	$modular_operations = array(
		"Module Operations" => 200
	);


	$apmOpt = '';
	$apmOpt .= '<option value="">Choose Module</option>';

	foreach ($application_module as $apm_key => $apm_value) {
		//if($apm_value < 200) { $apmOpt .= '<option value="'.$apm_value.'">'.$apm_key.'</option>'; }
		$apmOpt .= '<option value="'.$apm_value.'">'.$apm_key.'</option>';
	}

	#-----------------------------------------------------------------------------------

	$mmAlloteds = array();

	if(isset($_SESSION['app_service']) && $_SESSION['app_service'] == 'hotel-management') {
		array_push($mmAlloteds,'Booking');
		array_push($mmAlloteds,'Recreation');
		array_push($mmAlloteds,'Housekeeping');
		array_push($mmAlloteds,'Sales');
		array_push($mmAlloteds,'Administration');
	} elseif(isset($_SESSION['app_service']) && $_SESSION['app_service'] == 'point-of-sale') {
		array_push($mmAlloteds,'Point of Sales');
		array_push($mmAlloteds,'Material Control');
		array_push($mmAlloteds,'Administration');
	}

	$moduleAlloted = mysqli_data_fetch('app_service_tbl','services','','array');
	
	if(is_array($moduleAlloted)) {
		foreach($moduleAlloted as $ky => $vl) {
			if(!in_array($vl['services'], $mmAlloteds)) {
				array_push($mmAlloteds,$vl['services']);
			}
		}
	}

	$totalmmAlloted = count($mmAlloteds); $startAllotment = 0;

	$moduleMenu = ''; $loader = "'pause-state'"; $loaderMsg = "'pause-state-msg'"; $loaderMsgLbl = "'Processing request, wait..'";
	$accessMode = "'access-mode'"; $appLibrary = "'text-link-bt'"; $accessPage = '';

	$htmlheader = "'frame-header'"; $htmlheaderpg = "'public/admin/clear_header.html'";
	$htmlbody = "'frame-work'"; $htmlbodypg = "'public/admin/general_dasboard.html'";

	$usedropbox = ''; $dropboxPropr = ''; $arrowdown = ''; $tipvalue = "'curpage'";

	

	$moduleMenu .= '<span class="ln-display-box float-left cs-width-30 cs-height-30 rounded-element blue-white-state top-pull-7 alignct right-push-10 motion"><a href="" class="white-font" title="Return to Dashboard"><b class="mbri-home"></b></a></span>';

	foreach($application_module as $apm_key_menu => $apm_value_menu) {
		
		if($apm_value_menu < 200 && in_array(strtoupper($apm_key_menu),$mmAlloteds)) {

			$startAllotment += 1;

			$accessPage = "'public/admin/".appaccess_pages($apm_value_menu)."'";

			if($apm_value_menu == 8) {
				
				$postorekey = array("iscounter"=>"Yes","deletedata"=>0,"status"=>"Active");
				$postores = mysqli_data_fetch($tbL14,'id,posname',$postorekey,'array');

				$shop = '';
				$shopname = "'pos-shop-selected'";
				$isprivilege = "";

				$usedropbox=' drop-box';
				$arrowdown='';
				//$arrowdown='&nbsp; <b class="fa-arrow-down nobold ft-xxsml-size"></b>';
				$dropboxPropr .= '<div class="nc-width-90 top-pull-20 minuleft15 th-menu right-layout">';
				$dropboxPropr .= '<span class="block-element noscroll grey-1-theme xsml-rounded-button pads30">';
				$dropboxPropr .= '<ul class="nolist">';

				if(is_array($postores)) {
					foreach ($postores as $pskey => $psvalue) {
						
						$shop = $psvalue["id"];
						$isprivilege = perform_role_check($tbL5,$myrole,$shop,999);

						if(isset($isprivilege) && $isprivilege >= 1) { $dropboxPropr .= '<li onclick="changemenuclass(this,'.$startAllotment.','.$totalmmAlloted.'); objDisplay('.$loader.'); writeObjheader('.$loaderMsg.','.$loaderMsgLbl.'); csautohidePopupBox('.$accessMode.','.$loader.'); pageLoader('.$appLibrary.','.$accessPage.'); pageLoader('.$htmlheader.','.$htmlheaderpg.'); pageLoader('.$htmlbody.','.$htmlbodypg.'); get_pos_shop('.$shop.','.$shopname.'); htmlpassval(0,'.$tipvalue.'); removeViewedPages()" class="ln-display-box float-left nc-width-25 right-pull-5 left-pull-5 bottom-push-15" data-tab="8"><small class="overstate-font-seven default-text-font-bold">'.$psvalue["posname"].' <b class="fa-arrow-right nobold left-push-3"></b></small></li>'; }
					}

				} else {
					$dropboxPropr .= '<li><small>&nbsp;</small></li>';
				}
				
				$dropboxPropr .= '</ul>';
				$dropboxPropr .= '</span>';
				$dropboxPropr .= '</div>';
				
			} else {
				$usedropbox='';
				$dropboxPropr='';
				$arrowdown='';
			}

			
			if(isset($usedropbox) && !empty($usedropbox)) {

				$moduleMenu .= '<span id="mdl'.$startAllotment.'" class="ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-state motion anchor'.$usedropbox.'" data-tab="8">';
				$moduleMenu .= '<ul class="nolist">';
				$moduleMenu .= '<li>';
				$moduleMenu .= '<h4 class="nobold large default-text-font-bold">'.$apm_key_menu.$arrowdown.'</h4>';
				$moduleMenu .= $dropboxPropr;
				$moduleMenu .= '</li>';
				$moduleMenu .= '</ul>';
				$moduleMenu .= '</span>';

			} else {
				$moduleMenu .= '<span id="mdl'.$startAllotment.'" class="ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-state motion anchor'.$usedropbox.'" onclick="changemenuclass(this,'.$startAllotment.','.$totalmmAlloted.'); objDisplay('.$loader.'); writeObjheader('.$loaderMsg.','.$loaderMsgLbl.'); csautohidePopupBox('.$accessMode.','.$loader.'); pageLoader('.$appLibrary.','.$accessPage.'); pageLoader('.$htmlheader.','.$htmlheaderpg.'); pageLoader('.$htmlbody.','.$htmlbodypg.'); htmlpassval(0,'.$tipvalue.'); removeViewedPages()" data-tab="0">';
				$moduleMenu .= '<ul class="nolist">';
				$moduleMenu .= '<li>';
				$moduleMenu .= '<h4 class="nobold large default-text-font-bold">'.$apm_key_menu.$arrowdown.'</h4>';
				$moduleMenu .= $dropboxPropr;
				$moduleMenu .= '</li>';
				$moduleMenu .= '</ul>';
				$moduleMenu .= '</span>';
			}
		}
	}

	$moduleMenu .= '<span class="block-element new-line-space">';
	$moduleMenu .= '</span>';

	#-----------------------------------------------------------------------------------

	function appmodule($appmdlid) {
		switch ($appmdlid) {
			case 1:
				$module = "Administration";
				break;
			case 2:
				$module = "Accounting";
				break;
			case 3:
				$module = "Front Office";
				break;
			case 4:
				$module = "Housekeeping";
				break;
			case 5:
				$module = "Sales";
				break;
			case 6:
				$module = "Recreation";
				break;
			case 7:
				$module = "Material Control";
				break;
			case 8:
				$module = "Point of Sales";
				break;
			case 9:
				$module = "Reports";
				break;
			case 200:
				$module = "Module Operations";
				break;
			default:
				$module = "Undefined";
				break;
		}

		return $module;
	}

	#-----------------------------------------------------------------------------------

	function appaccess_pages($pg) {
		switch ($pg) {
			case 1:
				$accesspg = "fx_administration.php";
				break;
			case 2:
				$accesspg = "fx_accounting.php";
				break;
			case 3:
				$accesspg = "fx_front_office.php";
				break;
			case 4:
				$accesspg = "fx_housekeeping.php";
				break;
			case 5:
				$accesspg = "fx_sales.php";
				break;
			case 6:
				$accesspg = "fx_recreation.php";
				break;
			case 7:
				$accesspg = "fx_material_control.php";
				break;
			case 8:
				$accesspg = "fx_pos.php";
				break;
			case 9:
				$accesspg = "fx_report.php";
				break;
			default:
				$accesspg = "Undefined";
				break;
		}

		return $accesspg;
	}

?>