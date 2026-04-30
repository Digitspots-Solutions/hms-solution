<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can manage all web packages. Remove non-applicable rooms, update price etc.
 	</span>
 	<span class="ln-display-box float-right">
		<h4 class="large">Web Packages &nbsp; +</h4>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>
	
	function packageLength() {
		
		var pkg = document.getElementById('fieldset4').value;

		if(pkg == 'No') {
			document.getElementById('package-duration').className = 'block-element box-border-thick pads15';
			document.getElementById('package-start').required = true;
			document.getElementById('package-end').required = true;
		} else if(pkg == 'Yes') {
			document.getElementById('package-duration').className = 'noshow';
			document.getElementById('package-start').required = false;
			document.getElementById('package-end').required = false;
			document.getElementById('package-start').value = '';
			document.getElementById('package-end').value = '';
		}
	}

	function dateschedule() {

		var dsc = document.getElementById('allow-more-schedule-date');

		if(dsc.lang == 'no') {
			document.getElementById('adsc').className = 'dark-black-theme white-font top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 rounded-button float-right';
			document.getElementById('schedule-date-box').className = 'block-element top-push-10 sml-rounded-button noscroll';
			dsc.lang = 'yes';
			dsc.value = 'Yes';
		} else if(dsc.lang == 'yes') {
			document.getElementById('adsc').className = 'noshow';
			document.getElementById('schedule-date-box').className = 'noshow';
			dsc.lang = 'no';
			dsc.value = 'No';
		}
	}

	function drawrr() {
		var rr = document.getElementById('fieldset5').value;
		if(eval(rr) >= 1) { window.location.href = '?logs=<?php echo $logs; ?>&edit=<?php echo $_GET['edit']; ?>&r=y&roomratediscount='+rr; }
	}


	function create_datepicker() {
		var contr,tr,td1,td2,txt1,txt2;

		contr = document.getElementById('schedule-date-datasheet');

		tr = document.createElement('tr');
		td1 = document.createElement('td');
		td2 = document.createElement('td');

		txt1 = document.createElement('input');
		txt2 = document.createElement('input');

		txt1.type = 'date';
		txt1.name = 'package-start[]';
		txt1.className = 'alignct';
		td1.appendChild(txt1);

		txt2.type = 'date';
		txt2.name = 'package-end[]';
		txt2.className = 'alignct';
		td2.appendChild(txt2);

		tr.appendChild(td1);
		tr.appendChild(td2);
		
		contr.appendChild(tr);
	}

</script>

<?php

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
	
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = $_POST['fieldset7'];
		$fieldset8 = $_POST['packagetype-mdl'];

		$insert_dataproperty = array("packagename"=>ucwords(strtolower($fieldset1)),"shortname"=>str_replace(' ','',strtoupper($fieldset2)),"displayname"=>ucwords(strtolower($fieldset6)),"packageforeverstatus"=>$fieldset4,"detail"=>$fieldset7,"userid"=>$userSignedIn);
		$insert_constrain = array("id"=>$fieldset8);
		$data_inserted = mysqli_data_update($tbL84,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_package_id = $fieldset8; //new package-room-type created

			##for date schedule
			/*if(isset($fieldset4) && $fieldset4 == 'No') {
				$package_start = $_POST['package-start'];
				$package_end = $_POST['package-end'];

				for($d=0; $d < count($package_start); $d++) {
					if($package_start[$d] != '' && $package_end[$d] != '') {
						$pckg_arr = array("packageid"=>$new_package_id,"packagestart"=>$package_start[$d],"packageend"=>$package_end[$d]);
						mysqli_data_insert($tbL85,$pckg_arr,'');
					}
				}
			}*/
			
			##for extra bed rate
			$extra_bedrate_arr = $_POST['extrabed'];
			$extra_bedrate_room_id = $_POST['extrabed-roomid'];

			for($e=0; $e < count($extra_bedrate_room_id); $e++) {
				$extrabed_select_arr = array("packageid"=>$new_package_id,"room_type_id"=>$extra_bedrate_room_id[$e],"ratetype"=>"extrabed rate","amount"=>$extra_bedrate_arr[$e],"night"=>0);
				$extrabed_select_query = array("packageid"=>$new_package_id,"room_type_id"=>$extra_bedrate_room_id[$e],"ratetype"=>"extrabed rate","night"=>0);
				mysqli_data_update($tbL87,$extrabed_select_arr,$extrabed_select_query);
			}

			##for room rate
			$room_rate_arr = $_POST['drpn'];
			$room_rate_id = $_POST['roomrate-roomid'];
			$room_rate_night = $_POST['roomrate-night'];

			for($r=0; $r < count($room_rate_id); $r++) {
				$room_select_arr = array("packageid"=>$new_package_id,"room_type_id"=>$room_rate_id[$r],"ratetype"=>"room rate","amount"=>$room_rate_arr[$r],"night"=>$room_rate_night[$r]);
				$room_select_query = array("packageid"=>$new_package_id,"room_type_id"=>$room_rate_id[$r],"ratetype"=>"room rate","night"=>$room_rate_night[$r]);
				mysqli_data_update($tbL87,$room_select_arr,$room_select_query);
			}

			##for inclusions selected
			$incl_arr = $_POST['incl'];
			if(isset($incl_arr)) {
				foreach($incl_arr as $pckg_incl) {
					$incl_select_arr = array("packageid"=>$new_package_id,"inclusion_id"=>$pckg_incl);
					mysqli_data_insert($tbL88,$incl_select_arr,'');
				}
			}
			

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change package settings for room booking","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['edit']) && is_numeric($_GET['edit']))
	{
		$fieldset = escape_data($_GET['edit']);
		
		$get_dataproperty = "id,packagename,shortname,displayname,packagetype,detail,packageforeverstatus,numberofnight,status";
		$package_query = array("id"=>$fieldset);
		
		$get_package_data = mysqli_data_fetch($tbL84,$get_dataproperty,$package_query,'noarray');

		$room_query = array("deletedata"=>0);

		if(isset($_GET['roomratediscount']) && $_GET['roomratediscount'] >= 1) { $drawratetable = $_GET['roomratediscount']; }
		else { $drawratetable = $get_package_data[7]; }

		if(isset($get_package_data[6]) && $get_package_data[6] == 'No') {
			$classobj = "block-element";
			$pckg_arr = array("packageid"=>$fieldset);
			$get_package_schl_date = mysqli_data_fetch($tbL85,'packagestart,packageend',$pckg_arr,'noarray');
			$pkgstart = $get_package_schl_date[0];
			$pkgend = $get_package_schl_date[1];
		} else {
			$classobj = "noshow";
			$pkgstart = '';
			$pkgend = '';
		}

		$pckg = get_pt($get_package_data[4]);

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-90">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin"><?php echo $get_package_data[1]; ?> Details</h3>
						</div>
						<div class="bottom-push-20 alignlt box-border-thick light-yellow-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size">
							Please indicate number of night before filling the other information or go with the default
						</div>

						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter package name" value="<?php echo $get_package_data[1]; ?>" required="required">
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="ln-display-box float-right nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<small class="block-element bottom-push-5 alignlt left-pull-7">Short Name</small>
									<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter short name" value="<?php echo $get_package_data[2]; ?>" required="required">
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>

						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<select name="fieldset3" id="fieldset3" required="required">
										<option value="<?php echo $get_package_data[4]; ?>" selected="selected"><?php echo $pckg; ?></option>
									</select>
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="ln-display-box float-right nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<small class="block-element bottom-push-5 alignlt left-pull-7">Active Forever</small>
									<select name="fieldset4" id="fieldset4" required="required" onchange="packageLength()">
										<option value="<?php echo $get_package_data[6]; ?>" selected="selected"><?php echo $get_package_data[6]; ?></option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<div id="package-duration" class="<?php echo $classobj; ?>">
										<span class="block-element top-push-10 bottom-push-5">
											<small class="block-element bottom-push-5 dark-grey-font alignlt left-pull-5">Package Start:</small>
											<input type="date" name="package-start[]" id="package-start" value="<?php echo $pkgstart; ?>">
										</span>
										<span class="block-element bottom-push-5">
											<small class="block-element bottom-push-5 dark-grey-font alignlt left-pull-5">Package End:</small>
											<input type="date" name="package-end[]" id="package-end" value="<?php echo $pkgend; ?>">
										</span>
									</div>
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<small class="block-element bottom-push-5 alignlt left-pull-7">Number of Night</small>
									<input type="text" name="fieldset5" id="fieldset5" placeholder="Enter number of night" value="<?php echo $drawratetable; ?>" required="required" onkeyup="drawrr()" onblur="drawrr()" pattern="\d*">
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-20 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="ln-display-box float-right nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<small class="block-element bottom-push-5 alignlt left-pull-7">Display Name</small>
									<input type="text" name="fieldset6" id="fieldset6" placeholder="Enter display name" value="<?php echo $get_package_data[3]; ?>">
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>

						<span class="block-element top-push-20 bottom-push-20 box-border-thick pads15 sml-rounded-button">
							<small class="block-element bottom-push-15 blue-font alignlt"><b>Package Component Details</b> <a href="javascript:void(0)" class="dark-black-theme white-font top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 rounded-button float-right" onclick="">Add +</a></small>
							<table cellpadding="0" cellspacing="0">
								<tbody id="component-datasheet"></tbody>
							</table>
						</span>

						<span class="block-element bottom-push-20 box-border-thick pads15 sml-rounded-button">
							<small class="block-element bottom-push-15 blue-font alignlt"><b>Allow Multiple Dates</b> &nbsp; <input type="checkbox" name="allow-more-schedule-date" id="allow-more-schedule-date" value="Yes" onclick="dateschedule()" lang="no"> &nbsp; <a href="javascript:void(0)" id="adsc" class="noshow" onclick="create_datepicker()">Add +</a></small>
							<p class="block-element new-line-space"></p>
							<div id="schedule-date-box" class="noshow">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<th width="100px" class="box-border-thick-right" align="center">Package Start</th>
										<th width="100px" align="center">Package End</th>
									</tr>
									<tbody id="schedule-date-datasheet"></tbody>
								</table>
							</div>
						</span>

						<span class="block-element top-push-20 bottom-push-20">
							<div class="block-element sml-rounded-button noscroll">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<th width="150px" class="box-border-thick-right box-border-thick-left">Room Type</th>
										<?php
											$room_name = mysqli_data_fetch($tbL52,'name',$room_query,'array');
											foreach($room_name as $rn_key => $rn_value) {
												?>
													<th width="160px" class="box-border-thick-right" title="<?php echo $rn_value['name']; ?>"><?php echo substr($rn_value['name'],0,15); ?></th>
												<?php
											}
										?>
									</tr>
									<tr>
										<td width="150px" class="box-border-thick-right box-border-thick-left" align="center">Adult</td>
										<?php
											$get_adult = '';
											$room_adult = mysqli_data_fetch($tbL52,'id',$room_query,'array');
											foreach($room_adult as $ra_key => $ra_value) {
												
													$get_adult = idget_data($tbL52,$ra_value['id'],'adult');
												?>
													<td width="160px" class="box-border-thick-right" align="center"><?php echo $get_adult; ?></td>
												<?php
											}
										?>
									</tr>
									<tr>
										<td width="150px" class="box-border-thick-right box-border-thick-left" align="center">Child</td>
										<?php
											$get_child = '';
											$room_child = mysqli_data_fetch($tbL52,'id',$room_query,'array');
											foreach($room_child as $rc_key => $rc_value) {
												
													$get_child = idget_data($tbL52,$rc_value['id'],'child');
												?>
													<td width="160px" class="box-border-thick-right" align="center"><?php echo $get_child; ?></td>
												<?php
											}
										?>
									</tr>
									<tr>
										<td width="150px" class="box-border-thick-right box-border-thick-left" align="center">Extra Bed</td>
										<?php
											$get_extrabed = ''; $query_apply_extrabed = '';
											$room_extrabed = mysqli_data_fetch($tbL52,'id',$room_query,'array');
											foreach($room_extrabed as $reb_key => $reb_value) {
												
												$get_extrabed = idget_data($tbL52,$reb_value['id'],'extrabedprice');
												$query_apply_extrabed = array("packageid"=>$fieldset,"room_type_id"=>$reb_value['id'],"ratetype"=>"extrabed rate");
												$get_apply_room_extrabed = mysqli_data_fetch($tbL87,'amount',$query_apply_extrabed,'noarray');

												?>
													<td width="160px" class="box-border-thick-right" align="center"><input type="number" name="extrabed[]" value="<?php if(isset($get_apply_room_extrabed[0]) && $get_apply_room_extrabed[0] > 0) { echo $get_apply_room_extrabed[0]; } else { echo $get_extrabed; } ?>"><input type="hidden" name="extrabed-roomid[]" value="<?php echo $reb_value['id']; ?>"></td>
												<?php
											}
										?>
									</tr>
									<tr>
										<td width="150px" class="box-border-thick-right box-border-thick-left" align="center">Rate Per Night</td>
										<?php
											$get_room_rate = '';
											$room_room_rate = mysqli_data_fetch($tbL52,'id',$room_query,'array');
											foreach($room_room_rate as $rr_key => $rr_value) {
												
													$get_room_rate = idget_data($tbL52,$rr_value['id'],'defaultprice');
												?>
													<td width="160px" class="box-border-thick-right" align="center">&#8358; <?php echo number_format($get_room_rate,2); ?></td>
												<?php
											}
										?>
									</tr>

									<?php

										for($tr=1; $tr <= $drawratetable; $tr++) {
											?>
												<tr>
													<td width="150px" class="box-border-thick-right box-border-thick-left" align="center">Discounted Rate Per Night <?php echo $tr; ?></td>
													<?php
														$get_drpn = ''; $query_apply_room = '';
														$room_drpn = mysqli_data_fetch($tbL52,'id',$room_query,'array');
														foreach($room_drpn as $drpn_key => $drpn_value) {
															
															$get_drpn = idget_data($tbL52,$drpn_value['id'],'defaultprice');
															$query_apply_room = array("packageid"=>$fieldset,"room_type_id"=>$drpn_value['id'],"ratetype"=>"room rate","night"=>$tr); $get_apply_room = mysqli_data_fetch($tbL87,'amount',$query_apply_room,'noarray');

															?>
																<td width="160px" class="box-border-thick-right" align="center"><input type="number" name="drpn[]" value="<?php if(isset($get_apply_room[0]) && $get_apply_room[0] > 0) { echo $get_apply_room[0]; } else { echo $get_drpn; } ?>"><input type="hidden" name="roomrate-roomid[]" value="<?php echo $drpn_value['id']; ?>"><input type="hidden" name="roomrate-night[]" value="<?php echo $tr; ?>"></td>
															<?php
														}
													?>
												</tr>
											<?php
										}
									
									?>

								</table>
							</div>
							<div class="block-element pads15">
								<small class="block-element bottom-push-15 blue-font alignlt"><b>Inclusions</b></small>
								<?php
								
									$incl_constrain = array("deletedata"=>0,"status"=>"Active");
									$get_incl = mysqli_data_fetch($tbL83,'id,name',$incl_constrain,'array');

									if(is_array($get_incl)) {
										$inc_clr = "";
										foreach ($get_incl as $incl_key => $incl_value) {
											$inc_key = array("inclusion_id"=>$incl_value["id"]);
											$chk_incl = mysqli_data_fetch($tbL88,'id',$inc_key,'noarray');
											?><div class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-10 ft-xsml-size">
												<span class="ln-display-box float-left nc-width-15">
													<?php if(isset($chk_incl[0]) && $chk_incl[0] >= 1) { $inc_clr = "dark-grey-font"; ?><input type="checkbox" disabled="disabled"><?php } else { $inc_clr = ""; ?><input type="checkbox" name="incl[]" value="<?php echo $incl_value["id"]; ?>"><?php } ?>
												</span>
												<span class="ln-display-box float-left nc-width-85 alignlt <?php echo $inc_clr; ?>">
													<?php echo $incl_value["name"]; ?>
												</span>
												<span class="block-element new-line-space"></span>
											</div><?php
										}
									}

								?>
								<div class="block-element new-line-space">
								</div>
							</div>
						</span>

						<span class="block-element top-push-30 bottom-push-20">
							<small class="block-element bottom-push-5 alignlt"><b>Package Description</b></small>
							<textarea name="fieldset7" id="fieldset7" placeholder="Enter description"><?php echo $get_package_data[5]; ?></textarea>
							<script> CKEDITOR.replace( 'fieldset7' ); </script>
						</span>

						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL84,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change frontdesk package status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['del']) && is_numeric($_GET['del']))
	{
		$tbl = $_GET['tbl']; $rowid = $_GET['del'];
		$del_query = array("id"=>$rowid);
		$del = trash_record($tbl,$del_query);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($del) && $del == 2) {

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from package options","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Package option was removed successfully</span>';
		} else {
			$post_result .= '<span class="red-font">Unable to remove selected option!</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (packagename LIKE '".escape_data($_POST['search'])."%' OR shortname LIKE '".escape_data($_POST['search'])."%')";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 5;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,packagename,shortname,displayname,packagetype,detail,packageforeverstatus,numberofnight,status";
	$constrain = array("deletedata"=>0,"packagetype"=>2);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL84,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","name","length of stay","price list","activation period","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$htmlresult .= '</tr>';
		
		$num=$pgstart; $g=""; $dataid=""; $activation_period=""; $pkg_incl=""; $pricelist="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$additionalQuery = "";

			#----------------------------------------------------------------------------------------------------------------------------------------------------

			$get_room_rate = array("packageid"=>$dataid,"ratetype"=>"room rate","night"=>1);
			$room_rate_data = mysqli_data_fetch($tbL87,'id,room_type_id,amount',$get_room_rate,'array');

				if(is_array($room_rate_data)) {
					
					$rrd=''; $rrd_numbr = 0; $room_name="";

					foreach ($room_rate_data as $rrd_key => $rrd_value) {
						$room_name = idget_data($tbL52,$rrd_value['room_type_id'],'name');
						$rrd .= '<div class="block-element ft-xxsml-size bottom-push-3"><a href="?logs='.$logs.'&del='.$rrd_value['id'].'&tbl='.$tbL87.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'"><b class="fa-trash nobold" title="Remove"></b></a> &nbsp; '.$room_name.' : &#8358; '.number_format($rrd_value['amount'],2).'</div>';
					}

					$pricelist = $rrd;

				} else {
					$pricelist = 'Unknown';
				}

			#----------------------------------------------------------------------------------------------------------------------------------------------------

			if($tdata["packageforeverstatus"] == 'Yes') {
				$activation_period="Unlimited";
			} elseif($tdata["packageforeverstatus"] == 'No') {
				
				$get_schedule_date = array("packageid"=>$dataid);
				$schedule_date = mysqli_data_fetch($tbL85,'id,packagestart,packageend',$get_schedule_date,'array');

				if(is_array($schedule_date)) {
					
					$sdt=''; $sdt_numbr = 0;

					foreach ($schedule_date as $sdt_key => $sdt_value) {
						$sdt_numbr += 1;
						if($sdt_numbr == 1) {
							$sdt .= '<div class="block-element ft-xxsml-size bottom-push-3 left-pull-15">&nbsp;'.date("d/m/Y",strtotime($sdt_value['packagestart'])).' &mdash; '.date("d/m/Y",strtotime($sdt_value['packageend'])).'</div>';
						} else {
							$sdt .= '<div class="block-element ft-xxsml-size bottom-push-3"><a href="?logs='.$logs.'&del='.$sdt_value['id'].'&tbl='.$tbL85.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'"><b class="fa-trash nobold" title="Remove"></b></a> &nbsp; '.date("d/m/Y",strtotime($sdt_value['packagestart'])).' &mdash; '.date("d/m/Y",strtotime($sdt_value['packageend'])).'</div>';
						}
					}

					$activation_period = $sdt;

				} else {
					$activation_period = 'Unknown';
				}

				
			}

			#----------------------------------------------------------------------------------------------------------------------------------------------------

			$get_incl = array("packageid"=>$dataid);
			$incl_data = mysqli_data_fetch($tbL88,'id,inclusion_id',$get_incl,'array');

			if(is_array($incl_data)) {
				
				$incl=''; $incl_name='';
				$incl .= '<small class="block-element dark-grey-font top-push-5 bottom-push-3">Inclusions:</small>';

				foreach ($incl_data as $incl_key => $incl_value) {
					$incl_name = idget_data($tbL83,$incl_value['inclusion_id'],'name');
					$incl .= '<div class="block-element ft-xxsml-size bottom-push-3"><a href="?logs='.$logs.'&del='.$incl_value['id'].'&tbl='.$tbL83.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'"><b class="fa-trash nobold" title="Remove"></b></a> &nbsp; '.$incl_name.'</div>';
				}

				$incl .= '<div class="block-element ft-xxsml-size bottom-push-3">&nbsp;</div>';

				$pkg_incl = $incl;
			} else {
				$pkg_incl = '';
			}

			#----------------------------------------------------------------------------------------------------------------------------------------------------


			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="left" class="box-border-thick-right left-pull-7"><b class="blue-font">'.$tdata["packagename"].'</b>'.$pkg_incl.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["numberofnight"].' Night(s)</td>';
			$htmlresult .= '<td width="200px" align="left" class="box-border-thick-right left-pull-7">'.$pricelist.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$activation_period.'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="blue-font">View/Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	mysqli_data_check($tbL84,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(5,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>