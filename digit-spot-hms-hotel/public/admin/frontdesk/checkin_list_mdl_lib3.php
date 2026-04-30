<?php $smdl = "frontdesk"; $logs = escape_data($_GET['logs']); include "./shift_selection.php"; ?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the list of check-in guest reports
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">CheckIn Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Booking Type</small>
			<select name="fieldset3" id="fieldset3" required="required">
				<option value="0" selected="selected">All</option>
				<option value="1">Individual</option>
				<option value="2">Corporate</option>
				<option value="3">Agent</option>
				<option value="4">E-Booking</option>
				<option value="5">Complimentary</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Shift</small>
			<select name="fieldset4" id="fieldset4" required="required">
				<option value="0" selected="selected">All</option>
				<?php echo $shift_htmlresult; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-pull-5 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton']))
	{
		$checkin_date_from = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$checkout_date_to = write_dateF($gh_get_date_format,$_POST['fieldset2']);

		?>
			<p class="bottom-pull-20">
				<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
			</p>

			<div id="section-to-print" class="block-element">
				<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
				<small class="block-element alignct">CheckIn Report Between <?php echo $checkin_date_from.' And '.$checkout_date_to; ?></small>
				<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

				<?php
					if($_POST['fieldset4'] == 0) {
						$ushift_query = array("deletedata"=>0,"status"=>"Active");
						$ushift_data = mysqli_data_fetch($tbL20,'id,shiftname',$ushift_query,'array');
						
						if(is_array($ushift_data)) {
							$this_user_shift_list = "";
							foreach ($ushift_data as $ushf_key => $ushf_value) {
								
								$get_user_shift_log = array("shiftid"=>$ushf_value['id'],"deletedata"=>0);
								$get_user_shift_data = mysqli_data_fetch($tbL23,'userid',$get_user_shift_log,'array');
								if(is_array($get_user_shift_data)) { $user_shift_list = ""; foreach($get_user_shift_data as $usd_key => $usd_data) { $user_shift_list .= $usd_data['userid'].","; } } else { $user_shift_list = 0; }
								
								$this_user_shift_list = substr_replace($user_shift_list,'',-1,1);

								#start report selection

								if(isset($_POST['fieldset3']) && $_POST['fieldset3'] > 0) { $shift_checkin_query = array("status"=>"CheckedIn","booking_type"=>$_POST['fieldset3'],"deletedata"=>0); $t_bkt = $_POST['fieldset3']; } else { $shift_checkin_query = array("status"=>"CheckedIn","deletedata"=>0); $t_bkt = 0; }

								$additionalQuery = " AND userid IN(".$this_user_shift_list.") AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'"; $shift_checkin_dataproperty = "booking_number,roomid,customerid,userid,noofdays,checkin_date,checkout_date,datelogged";
								$get_shift_checkin_data = mysqli_data_fetch($tbL127,$shift_checkin_dataproperty,$shift_checkin_query,'array');

								?>
									<div class="block-element top-push-10">
										<h4 class="large nobold default-text-font-bold"><?php echo $ushf_value['shiftname']; ?></h4>
										<div class="block-element top-push-5 box-border-thick">
											<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
												<tr>
													<th width="150px" align="center">Booking Number</th>
													<th width="150px" align="center">Stay Duration</th>
													<th width="150px" align="center">Guest</th>
													<th width="100px" align="center">Contact</th>
													<th width="120px" align="center">Booking Date</th>
													<th width="100px" align="center">Room</th>
													<th width="100px" align="center">CheckIn By</th>
													<th width="120px" align="center">CheckIn Date</th>
													<th width="150px" align="center">Booking Type</th>
													<th width="70px" align="center">Status</th>
												</tr>

												<?php

													if(is_array($get_shift_checkin_data)) {
														$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $mobile = "";
														$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
														$checkin_time = "";

														foreach ($get_shift_checkin_data as $scd_key => $scd_value) {
															
															$stay_f = write_dateF($gh_get_date_format,$scd_value['startdate']);
															$stay_t = write_dateF($gh_get_date_format,$scd_value['endate']);

															$salutation = idget_data($tbL102,$scd_value['customerid'],'salutation');
															$billto = idget_data($tbL102,$scd_value['customerid'],'billto');

															$customer_name = idget_data($tbL42,$salutation,'name').' ';
															$customer_name .= idget_data($tbL102,$scd_value['customerid'],'name');

															if(isset($billto) && $billto >= 1) { $customer_name .= " (".idget_data($tbL58,$billto,'name').")"; }

															$mobile = idget_data($tbL102,$scd_value['customerid'],'mobile');
															$dateofbooking = write_dateF($gh_get_date_format,$scd_value['datelogged']);
															$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
															$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

															$g_username = idget_data($tbL7,$scd_value['userid'],'staffname');
															
															if(isset($t_bkt) && $t_bkt >= 1) { $bkt = arrayget_key($booking_type,$t_bkt); }
															else { $bkt = "All"; }

															#get guest checkin status
															//$additionalQuery = "";
															//$checker = array("booking_number"=>$scd_value['booking_number'],"roomid"=>$scd_value['roomid']);
															//$get_checker_data = mysqli_data_fetch($tbL127,'status,checkin_date,checkin_time',$checker,'noarray');

															//$checkin_date = write_dateF($gh_get_date_format,$get_checker_data[1]);
															//$checkin_time = write_timeF($gh_get_time_format,$get_checker_data[2]);

															?>
																<tr>
																	<td width="150px" align="center"><?php echo $scd_value['booking_number']; ?></td>
																	<td width="150px" align="center"><?php echo $stay_f.' - '.$stay_t; ?></td>
																	<td width="150px" align="center"><?php echo $customer_name; ?></td>
																	<td width="100px" align="center"><?php echo $mobile; ?></td>
																	<td width="120px" align="center"><?php echo $dateofbooking; ?></td>
																	<td width="100px" align="center"><?php echo $room_name; ?></td>
																	<td width="100px" align="center"><?php echo $g_username; ?></td>
																	<td width="120px" align="center"><?php echo $checkin_date.' '.$checkin_time; ?></td>
																	<td width="150px" align="center"><?php echo $bkt; ?></td>
																	<td width="70px" align="center"><?php echo $get_checker_data[0]; ?></td>
																</tr>
															<?php
														}
													}

												?>

											</table>
										</div>
									</div>
								<?php
							}
						}

					} else {
						
						$ushift_query = array("id"=>$_POST['fieldset4'],"deletedata"=>0,"status"=>"Active");
						$ushift_data = mysqli_data_fetch($tbL20,'id,shiftname',$ushift_query,'noarray');
						
						if(is_array($ushift_data)) {
							
							$get_user_shift_log = array("shiftid"=>$ushift_data[0],"deletedata"=>0);
							$get_user_shift_data = mysqli_data_fetch($tbL23,'userid',$get_user_shift_log,'array');
							if(is_array($get_user_shift_data)) { $user_shift_list = ""; foreach($get_user_shift_data as $usd_key => $usd_data) { $user_shift_list .= $usd_data['userid'].","; } } else { $user_shift_list = 0; }
							
							$this_user_shift_list = substr_replace($user_shift_list,'',-1,1);

							#start report selection

							if(isset($_POST['fieldset3']) && $_POST['fieldset3'] > 0) { $shift_checkin_query = array("status"=>"CheckedIn","booking_type"=>$_POST['fieldset3'],"deletedata"=>0); $t_bkt = $_POST['fieldset3']; } else { $shift_checkin_query = array("status"=>"CheckedIn","deletedata"=>0); $t_bkt = 0; }

							$additionalQuery = " AND userid IN(".$this_user_shift_list.") AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'"; $shift_checkin_dataproperty = "booking_number,roomid,customerid,userid,startdate,endate,datelogged";
							$get_shift_checkin_data = mysqli_data_fetch($tbL96,$shift_checkin_dataproperty,$shift_checkin_query,'array');

							?>
								<div class="block-element top-push-20">
									<h4 class="large nobold default-text-font-bold"><?php echo $ushift_data[1]; ?></h4>
									<div class="block-element top-push-5 box-border-thick">
										<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
											<tr>
												<th width="150px" align="center">Booking Number</th>
												<th width="150px" align="center">Stay Duration</th>
												<th width="150px" align="center">Guest</th>
												<th width="100px" align="center">Contact</th>
												<th width="120px" align="center">Booking Date</th>
												<th width="100px" align="center">Room</th>
												<th width="100px" align="center">Checkin By</th>
												<th width="120px" align="center">CheckIn Date</th>
												<th width="150px" align="center">Booking Type</th>
												<th width="70px" align="center">Status</th>
											</tr>

											<?php

												if(is_array($get_shift_checkin_data)) {
													$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $mobile = "";
													$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
													$checkin_time = "";

													foreach ($get_shift_checkin_data as $scd_key => $scd_value) {
														
														$stay_f = write_dateF($gh_get_date_format,$scd_value['startdate']);
														$stay_t = write_dateF($gh_get_date_format,$scd_value['endate']);

														$salutation = idget_data($tbL102,$scd_value['customerid'],'salutation');
														$billto = idget_data($tbL102,$scd_value['customerid'],'billto');

														$customer_name = idget_data($tbL42,$salutation,'name').' ';
														$customer_name .= idget_data($tbL102,$scd_value['customerid'],'name');

														if(isset($billto) && $billto >= 1) { $customer_name .= " (".idget_data($tbL58,$billto,'name').")"; }

														$mobile = idget_data($tbL102,$scd_value['customerid'],'mobile');
														$dateofbooking = write_dateF($gh_get_date_format,$scd_value['datelogged']);
														$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
														$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

														$g_username = idget_data($tbL7,$scd_value['userid'],'staffname');
														
														if(isset($t_bkt) && $t_bkt >= 1) { $bkt = arrayget_key($booking_type,$t_bkt); }
														else { $bkt = "All"; }

														#get guest checkin status
														$additionalQuery = "";
														$checker = array("booking_number"=>$scd_value['booking_number'],"roomid"=>$scd_value['roomid']);
														$get_checker_data = mysqli_data_fetch($tbL127,'status,checkin_date,checkin_time',$checker,'noarray');

														$checkin_date = write_dateF($gh_get_date_format,$get_checker_data[1]);
														$checkin_time = write_timeF($gh_get_time_format,$get_checker_data[2]);

														?>
															<tr>
																<td width="150px" align="center"><?php echo $scd_value['booking_number']; ?></td>
																<td width="150px" align="center"><?php echo $stay_f.' - '.$stay_t; ?></td>
																<td width="150px" align="center"><?php echo $customer_name; ?></td>
																<td width="100px" align="center"><?php echo $mobile; ?></td>
																<td width="120px" align="center"><?php echo $dateofbooking; ?></td>
																<td width="100px" align="center"><?php echo $room_name; ?></td>
																<td width="100px" align="center"><?php echo $g_username; ?></td>
																<td width="120px" align="center"><?php echo $checkin_date.' '.$checkin_time; ?></td>
																<td width="150px" align="center"><?php echo $bkt; ?></td>
																<td width="70px" align="center"><?php echo $get_checker_data[0]; ?></td>
															</tr>
														<?php
													}
												}

											?>

										</table>
									</div>
								</div>
							<?php
						}
					}
				?>
			</div>
		<?php
	}

?>