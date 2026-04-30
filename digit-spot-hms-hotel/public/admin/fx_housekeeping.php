<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; 

$print_business_date = write_dateF(6,$server_get_bizsdate);
if(isset($server_get_auditdate) && !empty($server_get_auditdate)) { $print_last_audit_date = write_dateF(6,$server_get_auditdate); } else { $print_last_audit_date = "N/A"; }

include "../../includes/common_data_vars.php";

$row = "array";
$constrain = array("moduleid"=>4,"status"=>"Active");
$dataCollect = mysqli_data_fetch($tbL10,'id,category',$constrain,$row);

$html_query_result_1 = '';
$html_query_result_2 = '';

//Get housekeeping status
$hrs_query = array("deletedata"=>0);
$additionalQuery = " AND housekeeping_stateid > 0 GROUP BY housekeeping_stateid";
$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid',$hrs_query,'array');

if(is_array($hrs_data)) {
	$count_rooms = ""; $count_room_query = ""; $legend = "";
	foreach ($hrs_data as $hrs_key => $hrs_value) {
		$count_room_datasets = "COUNT(roomid)";
		$count_room_query = "housekeeping_stateid = ".$hrs_value['housekeeping_stateid']." AND deletedata = 0";
		$count_rooms = mysqli_arithmetic_data($tbL94,$count_room_datasets,$count_room_query);

		$legend = idget_data($tbL36,$hrs_value['housekeeping_stateid'],'legendname');
		
		$html_query_result_1 .= '<div class="block-element bottom-push-3">';
		$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
		$html_query_result_1 .= $legend;
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
		$html_query_result_1 .= $count_rooms;
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '<span class="block-element new-line-space">';
		$html_query_result_1 .= '</span>';
		$html_query_result_1 .= '</div>';
	}
} else {

	$count_room_datasets = "COUNT(id)";
	$count_room_query = "deletedata = 0 AND roomstatus = 1";
	$count_rooms = mysqli_arithmetic_data($tbL56,$count_room_datasets,$count_room_query);

	$html_query_result_1 .= '<div class="block-element bottom-push-3">';
	$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
	$html_query_result_1 .= $default_housekeeping_legend;
	$html_query_result_1 .= '</span>';
	$html_query_result_1 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
	$html_query_result_1 .= $count_rooms;
	$html_query_result_1 .= '</span>';
	$html_query_result_1 .= '<span class="block-element new-line-space">';
	$html_query_result_1 .= '</span>';
	$html_query_result_1 .= '</div>';
}


//Get room status
#total room
$count_troom_datasets = "COUNT(id)";
$count_troom_query = "deletedata = 0 AND roomstatus = 1";
$count_trooms = mysqli_arithmetic_data($tbL56,$count_troom_datasets,$count_troom_query);

#total not available
$count_ntroom_datasets = "COUNT(id)";
$count_ntroom_query = "deletedata = 0 AND room_status_id NOT IN(1)";
$count_ntrooms = mysqli_arithmetic_data($tbL94,$count_ntroom_datasets,$count_ntroom_query);

$total_rooms_avail = $count_trooms - $count_ntrooms;

$rs_query = array("deletedata"=>0);
$additionalQuery = " AND room_status_id > 0 GROUP BY room_status_id";
$rs_data = mysqli_data_fetch($tbL94,'room_status_id',$rs_query,'array');

if(is_array($rs_data)) {
	$count_rooms = ""; $count_room_query = ""; $legend = ""; $left_rooms = 0; $addup_used_rooms = 0;
	foreach ($rs_data as $rs_key => $rs_value) {
		$count_room_datasets = "COUNT(roomid)";
		$count_room_query = "room_status_id = ".$rs_value['room_status_id']." AND deletedata = 0";
		$count_rooms = mysqli_arithmetic_data($tbL94,$count_room_datasets,$count_room_query);

		if($rs_value['room_status_id'] == 1) { $count_rooms = $total_rooms_avail; }
		else { $count_rooms = $count_rooms; }
		
		$legend = idget_data($tbL38,$rs_value['room_status_id'],'legendname');

		if($legend == 'Checkedin' || $legend == 'Reserved'):
			$addup_used_rooms = $addup_used_rooms + $count_rooms;
		endif;

		if($legend !== 'Available'):

		$html_query_result_2 .= '<div class="block-element bottom-push-3">';
		$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
		$html_query_result_2 .= $legend;
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
		$html_query_result_2 .= $count_rooms;
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '<span class="block-element new-line-space">';
		$html_query_result_2 .= '</span>';
		$html_query_result_2 .= '</div>';

		endif;
	}

	$left_rooms = $count_trooms - $addup_used_rooms;

	$html_query_result_2 .= '<div class="block-element bottom-push-3">';
	$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
	$html_query_result_2 .= 'Available';
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
	$html_query_result_2 .= $left_rooms;
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '<span class="block-element new-line-space">';
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '</div>';
		
} else {

	$count_room_datasets = "COUNT(id)";
	$count_room_query = "deletedata = 0 AND roomstatus = 1";
	$count_rooms = mysqli_arithmetic_data($tbL56,$count_room_datasets,$count_room_query);

	$html_query_result_2 .= '<div class="block-element bottom-push-3">';
	$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-80 ft-sml-size">';
	$html_query_result_2 .= $default_room_status_legend;
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '<span class="ln-display-box float-left nc-width-20 ft-sml-size alignrt">';
	$html_query_result_2 .= $count_rooms;
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '<span class="block-element new-line-space">';
	$html_query_result_2 .= '</span>';
	$html_query_result_2 .= '</div>';
}


//----------------------------------------------------------------------------------------------------------

$additionalQuery = "";		

if(isset($myaccess) && $myaccess == 'super admin') {
	
	if(is_array($dataCollect))
	{
		?>

		<div class="block-element cs-height-100 sky-blue-theme top-pull-15 right-pull-10 left-pull-10">
			<small class="block-element"><b>Business Status</b></small>
			<div class="block-element top-pull-5 bottom-pull-15 bottom-push-5 ft-xsml-size">
				<small class="block-element bottom-push-3">Audit Done till: <b><?php echo $print_last_audit_date; ?></b></small><small class="block-element bottom-push-3">Business Date: <b><?php echo $print_business_date; ?></b></small>
				<small class="block-element">Shift: <b>N/A</b></small>
			</div>
		</div>
		<div class="block-element white-theme top-pull-15 right-pull-10 bottom-pull-10 left-pull-10">
			<div class="block-element bottom-push-20">
				<h4 class="large">Housekeeping</h4>
				<?php echo $html_query_result_1; ?>
			</div>
			<div class="block-element">
				<h4 class="large">Current Room Status</h4>
				<?php echo $html_query_result_2; ?>
			</div>
		</div>

		<?php
		$submdls=''; $submdl_key=''; $onclick='';

		//menu with submenus

		foreach ($dataCollect as $ckey => $cvalue) {
			
			if($cvalue['category'] != 'Non-module') {
				
				$submdl_key = array("categoryid"=>$cvalue['id'],"status"=>"Active");
				mysqli_data_check($tbL11,'(*)',$submdl_key);
				$submdls = $numOfrows;

				if(isset($submdls) && $submdls >= 1) {
					
					$submdl_data = mysqli_data_fetch($tbL11,'id,name',$submdl_key,'array');

					?>
					<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
						<div id="cmdl1<?php echo $cvalue['id']; ?>" lang="uncollapsed" class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 sky-dark-deep-blue-theme dark-black-font anchor box-border-thick-bottom" onclick="forsubmenu('cmdl1<?php echo $cvalue['id']; ?>','lmdl1<?php echo $cvalue['id']; ?>','xlmdl1<?php echo $cvalue['id']; ?>')">
							<span class="ln-display-box float-left nc-width-90">
								<small class=""><?php echo $cvalue['category']; ?></small>
							</span>
							<span class="ln-display-box float-left nc-width-10 alignct">
								<b class="fa-arrow-down nobold ft-xsml-size"></b>
							</span>
							<span class="block-element new-line-space">
								<!-- clear line -->
							</span>
						</div>
						<div id="lmdl1<?php echo $cvalue['id']; ?>" class="csp-height-0 motion">
							<div id="xlmdl1<?php echo $cvalue['id']; ?>" class="noshow motion">
								<?php
									foreach ($submdl_data as $subkey => $subvalue) {
										?>
											<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom  sub-menu-class motion" onclick="loadframe('<?php echo $subvalue['name']; ?>',<?php echo $subvalue['id']; ?>)">
												<small><?php echo $subvalue['name']; ?></small>
											</div>
										<?php
									}
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}

		//menu with no submenus

		foreach ($dataCollect as $xckey => $xcvalue) {
			
			$submdl_key = array("categoryid"=>$xcvalue['id']);
			mysqli_data_check($tbL11,'(*)',$submdl_key);
			$submdls = $numOfrows;

			if($submdls == 0) {
				
				?>
				<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
					<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom motion nosub-menu-class" onclick="loadframe('<?php echo $xcvalue['category']; ?>',<?php echo $xcvalue['id']; ?>)">
						<small><?php echo $xcvalue['category']; ?></small>
					</div>
				</div>
				<?php
			}
		}
	}
	else
	{
		?>
			<div class="block-element"> No menu list </div>
		<?php
	}

	?>
		<input type="hidden" id="hsk-mode" value="1">
	<?php

}
else
{
	$isprivilege = perform_role_check($tbL5,$myrole,4,0);
	if(isset($isprivilege) && $isprivilege >= 1) {
		
		if(is_array($dataCollect))
		{
			?>

			<div class="block-element cs-height-120 sky-blue-theme top-pull-15 right-pull-10 left-pull-10">
				<small class="block-element"><b>Business Status</b></small>
				<div class="block-element top-pull-5 bottom-pull-15 bottom-push-5 ft-xsml-size">
					<small class="block-element bottom-push-3">Audit Done till: <b><?php echo $print_last_audit_date; ?></b></small><small class="block-element bottom-push-3">Business Date: <b><?php echo $print_business_date; ?></b></small>
					<small class="block-element bottom-push-3">Shift: <b><?php echo $print_shift; ?></b></small>
					<?php if(isset($_SESSION['counter_id']) && $_SESSION['counter_id'] >= 1) { ?><a href="<?php echo DOMAIN_URL; ?>login/close_counter<?php echo PHP_EXT; ?>?sesid=ny" class="blue-font"><u>Close Counter: <?php echo $print_counter; ?></u></a><?php } ?>
				</div>
			</div>
			<div class="block-element white-theme top-pull-15 right-pull-10 bottom-pull-10 left-pull-10">
				<div class="block-element bottom-push-20">
					<h4 class="large">Housekeeping</h4>
					<?php echo $html_query_result_1; ?>
				</div>
				<div class="block-element">
					<h4 class="large">Today's Room Status</h4>
					<?php echo $html_query_result_2; ?>
				</div>
			</div>

			<?php
			$submdls=''; $submdl_key=''; $onclick='';

			//menu with submenus

			$xinner_isprivilege = "";

			foreach ($dataCollect as $ckey => $cvalue) {
				
				if($cvalue['category'] != 'Non-module') {
					
					$submdl_key = array("categoryid"=>$cvalue['id'],"status"=>"Active");
					mysqli_data_check($tbL11,'(*)',$submdl_key);
					$submdls = $numOfrows;

					if(isset($submdls) && $submdls >= 1) {
						
						$submdl_data = mysqli_data_fetch($tbL11,'id,name',$submdl_key,'array');

						$xinner_isprivilege = perform_role_check($tbL5,$myrole,$cvalue['id'],888);
						if(isset($xinner_isprivilege) && $xinner_isprivilege >= 1)
						{
							?>
							<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
								<div id="cmdl1<?php echo $cvalue['id']; ?>" lang="uncollapsed" class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 sky-dark-deep-blue-theme dark-black-font anchor box-border-thick-bottom" onclick="forsubmenu('cmdl1<?php echo $cvalue['id']; ?>','lmdl1<?php echo $cvalue['id']; ?>','xlmdl1<?php echo $cvalue['id']; ?>')">
									<span class="ln-display-box float-left nc-width-90">
										<small class=""><?php echo $cvalue['category']; ?></small>
									</span>
									<span class="ln-display-box float-left nc-width-10 alignct">
										<b class="fa-arrow-down nobold ft-xsml-size"></b>
									</span>
									<span class="block-element new-line-space">
										<!-- clear line -->
									</span>
								</div>
								<div id="lmdl1<?php echo $cvalue['id']; ?>" class="csp-height-0 motion">
									<div id="xlmdl1<?php echo $cvalue['id']; ?>" class="noshow motion">
										<?php
											$inner_isprivilege = "";
											foreach ($submdl_data as $subkey => $subvalue) {
												$inner_isprivilege = perform_role_check($tbL5,$myrole,$subvalue['id'],'');
												if(isset($inner_isprivilege) && $inner_isprivilege >= 1) {
													?>
														<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom  sub-menu-class motion" onclick="loadframe('<?php echo $subvalue['name']; ?>',<?php echo $subvalue['id']; ?>)">
															<small><?php echo $subvalue['name']; ?></small>
														</div>
													<?php
												}
											}
										?>
									</div>
								</div>
							</div>
							<?php
						}
					}
				}
			}

			//menu with no submenus

			$xxinner_isprivilege = "";

			foreach ($dataCollect as $xckey => $xcvalue) {
				
				$submdl_key = array("categoryid"=>$xcvalue['id'],"status"=>"Active");
				mysqli_data_check($tbL11,'(*)',$submdl_key);
				$submdls = $numOfrows;

				if($submdls == 0) {
					$xxinner_isprivilege = perform_role_check($tbL5,$myrole,$xcvalue['id'],888);
					if(isset($xxinner_isprivilege) && $xxinner_isprivilege >= 1) {
						?>
						<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
							<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom motion nosub-menu-class" onclick="loadframe('<?php echo $xcvalue['category']; ?>',<?php echo $xcvalue['id']; ?>)">
								<small><?php echo $xcvalue['category']; ?></small>
							</div>
						</div>
						<?php
					}
				}
			}
		}
		else
		{
			?>
				<div class="block-element"> No menu list </div>
			<?php
		}

		?>
			<input type="hidden" id="hsk-mode" value="1">
		<?php

	} else {
		?>
			<div class="block-element red-font pads10 top-push-50 add-bold" align="center"> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/warning.png"> <br><br> You are not authorized to use this module! </div>
		<?php
	}
}

?>

<input type="hidden" id="access-mode" value="1">