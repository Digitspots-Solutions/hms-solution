<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new package by clicking <u>new package</u> button. All asterik fields are compulsory
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Package
		</a>
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
		if(eval(rr) >= 1) { window.location.href = '?logs=<?php echo $logs; ?>&c=<?php echo $_GET['c']; ?>&r=y&roomratediscount='+rr; }
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
		createDatabasetable($var_tbl_80); //create a table for this post
		createDatabasetable($var_tbl_81); //create a table for this post
		createDatabasetable($var_tbl_82); //create a table for this post
		createDatabasetable($var_tbl_83); //create a table for this post
		createDatabasetable($var_tbl_84); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = $_POST['fieldset7'];

		$insert_dataproperty = array("packagename"=>ucwords(strtolower($fieldset1)),"shortname"=>str_replace(' ','',strtoupper($fieldset2)),"displayname"=>ucwords(strtolower($fieldset6)),"packagetype"=>$fieldset3,"packageforeverstatus"=>$fieldset4,"numberofnight"=>$fieldset5,"detail"=>$fieldset7,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL84,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_package_id = $mysqli_id; //new package-room-type created

			##for date schedule
			if(isset($fieldset4) && $fieldset4 == 'No') {
				$package_start = $_POST['package-start'];
				$package_end = $_POST['package-end'];

				for($d=0; $d < count($package_start); $d++) {
					if($package_start[$d] != '' && $package_end[$d] != '') {
						$pckg_arr = array("packageid"=>$new_package_id,"packagestart"=>$package_start[$d],"packageend"=>$package_end[$d]);
						mysqli_data_insert($tbL85,$pckg_arr,'');
					}
				}
			}
			
			##for extra bed rate
			$extra_bedrate_arr = $_POST['extrabed'];
			$extra_bedrate_room_id = $_POST['extrabed-roomid'];

			for($e=0; $e < count($extra_bedrate_room_id); $e++) {
				$extrabed_select_arr = array("packageid"=>$new_package_id,"room_type_id"=>$extra_bedrate_room_id[$e],"ratetype"=>"extrabed rate","amount"=>$extra_bedrate_arr[$e],"night"=>0);
				mysqli_data_insert($tbL87,$extrabed_select_arr,'');
			}

			##for room rate
			$room_rate_arr = $_POST['drpn'];
			$room_rate_id = $_POST['roomrate-roomid'];
			$room_rate_night = $_POST['roomrate-night'];

			for($r=0; $r < count($room_rate_id); $r++) {
				$room_select_arr = array("packageid"=>$new_package_id,"room_type_id"=>$room_rate_id[$r],"ratetype"=>"room rate","amount"=>$room_rate_arr[$r],"night"=>$room_rate_night[$r]);
				mysqli_data_insert($tbL87,$room_select_arr,'');
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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new package for room booking","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		$room_query = array("deletedata"=>0);

		if(isset($_GET['roomratediscount']) && $_GET['roomratediscount'] >= 1) { $drawratetable = $_GET['roomratediscount']; }
		else { $drawratetable = 1; }

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-90">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-10 alignlt">
							<h3 class="nomargin">Creating New Package</h3>
						</div>
						<div class="bottom-push-20 alignlt box-border-thick light-yellow-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size">
							Please indicate number of night before filling the other information or go with the default
						</div>

						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter package name" required="required">
								</span>
								<span class="ln-display-box float-left nc-width-5 top-pull-10 red-font">
									*
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="ln-display-box float-right nc-width-45">
								<span class="ln-display-box float-left nc-width-90 right-push-10">
									<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter short name" required="required">
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
										<option value="" selected="selected">Package Type</option>
										<?php echo $ptOpt; ?>
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
									<select name="fieldset4" id="fieldset4" required="required" onchange="packageLength()">
										<option value="" selected="selected">Active Forever</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<div id="package-duration" class="noshow">
										<span class="block-element top-push-10 bottom-push-5">
											<small class="block-element bottom-push-5 dark-grey-font alignlt">Package Start:</small>
											<input type="date" name="package-start[]" id="package-start">
										</span>
										<span class="block-element bottom-push-5">
											<small class="block-element bottom-push-5 dark-grey-font alignlt">Package End:</small>
											<input type="date" name="package-end[]" id="package-end">
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
									<input type="text" name="fieldset6" id="fieldset6" placeholder="Enter display name">
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
											$get_extrabed = '';
											$room_extrabed = mysqli_data_fetch($tbL52,'id',$room_query,'array');
											foreach($room_extrabed as $reb_key => $reb_value) {
												
													$get_extrabed = idget_data($tbL52,$reb_value['id'],'extrabedprice');
												?>
													<td width="160px" class="box-border-thick-right" align="center"><input type="number" name="extrabed[]" value="<?php echo $get_extrabed; ?>"><input type="hidden" name="extrabed-roomid[]" value="<?php echo $reb_value['id']; ?>"></td>
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
														$get_drpn = '';
														$room_drpn = mysqli_data_fetch($tbL52,'id',$room_query,'array');
														foreach($room_drpn as $drpn_key => $drpn_value) {
															
																$get_drpn = idget_data($tbL52,$drpn_value['id'],'defaultprice');
															?>
																<td width="160px" class="box-border-thick-right" align="center"><input type="number" name="drpn[]" value="<?php echo $get_drpn; ?>"><input type="hidden" name="roomrate-roomid[]" value="<?php echo $drpn_value['id']; ?>"><input type="hidden" name="roomrate-night[]" value="<?php echo $tr; ?>"></td>
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
										foreach ($get_incl as $incl_key => $incl_value) {
											?><div class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-10 ft-xsml-size">
												<span class="ln-display-box float-left nc-width-15">
													<input type="checkbox" name="incl[]" value="<?php echo $incl_value["id"]; ?>">
												</span>
												<span class="ln-display-box float-left nc-width-85 alignlt">
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
							<textarea name="fieldset7" id="fieldset7" placeholder="Enter description"></textarea>
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

?>