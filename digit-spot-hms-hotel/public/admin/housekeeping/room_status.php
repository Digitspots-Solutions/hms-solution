<?php $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: You can check the availability of all the rooms for the selected date period
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30">
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<small class="dark-grey-font block-element bottom-push-7"><b>Check room status</b></small>
		<span class="ln-display-box float-left right-push-30">
			<select name="period" id="period" onchange="dateStat()">
				<option value="" selected="selected">Select Period</option>
				<option value="Today">Today</option>
				<option value="Tomorrow">Tomorrow</option>
				<option value="Custom Date">Custom Date</option>
			</select>
		</span>
		<span class="ln-display-box float-left right-push-30">
			<div id="custom-date" class="noshow">
				<div class="ln-display-box float-left right-push-10 top-pull-10">
					<small class="block-element dark-grey-font">&nbsp; From date</small>
				</div>
				<div class="ln-display-box float-left right-push-10">
					<input type="date" name="startdate" id="startdate">
				</div>
				<div class="ln-display-box float-left right-push-10 top-pull-10">
					<small class="block-element dark-grey-font">&nbsp; To date</small>
				</div>
				<div class="ln-display-box float-left right-push-10">
					<input type="date" name="endate" id="endate">
				</div>
			</div>
		</span>
		<span class="ln-display-box float-left">
			<input type="submit" name="checkavailability" value="Check Availability" class="submit top-pull-10 right-pull-50 bottom-pull-10 left-pull-50 blue-white-state sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
		</span>
	</form>
</div>
<?php

	$roomAlloted = 0; $raiseAlarm = 0;
	
	$_html_construct = '';

	if(isset($_GET['rrid']) && $_GET['rrid'] >= 1) {

		$ths_room_id = escape_data($_GET['rrid']);
		$hk_query = array("roomid"=>$ths_room_id);
		$hk_sql = array("room_status_id"=>1,"remarks"=>"Room released for re-booking upon error","startdate"=>$server_get_date,"endate"=>$server_get_date);
		mysqli_data_update($tbL94,$hk_sql,$hk_query);
		
		unset($_GET['rrid']);

		?>
			<script> window.parent.location.reload(true); </script>
		<?php
	}

	if(isset($_GET['lrrid']) && $_GET['lrrid'] >= 1) {

		$ths_room_id = escape_data($_GET['lrrid']);
		$hk_query = array("roomid"=>$ths_room_id);
		$hk_sql = array("housekeeping_stateid"=>1,"room_status_id"=>1,"remarks"=>"Room released for re-booking upon error","startdate"=>$server_get_date,"endate"=>$server_get_date);
		mysqli_data_update($tbL94,$hk_sql,$hk_query);

		unset($_GET['lrrid']);

		?>
			<script> window.parent.location.reload(true); </script>
		<?php
	}

	if(isset($_POST['checkavailability'])) {
		if(isset($_POST['period']) && !empty($_POST['period'])) {
			$custom_date_start=""; $custom_date_end="";
			switch ($_POST['period']) {
				case 'Today':
					$query = " AND endate BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
					break;

				case 'Tomorrow':
					$get_tomorrow_date = date('Y-m-d',strtotime($server_get_date . ' +2 days')); //date("Y-m-d",strtotime("2 days"));
					$query = " AND endate BETWEEN '".$get_tomorrow_date."' AND '".$get_tomorrow_date."'";
					break;
				
				case 'Custom Date':
					$custom_date_start=$_POST['startdate']; $custom_date_end=$_POST['endate'];
					$query = " AND endate BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";
					break;

				default:
					$query = "";
					break;
			}
		}
	} else {
		//$query = " AND endate BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
		$query = "";
	}


	//get all rooms out in group

	$additionalQuery = " GROUP BY room_type_id";
	$rm_query = array("deletedata"=>0,"roomstatus"=>1);
	$rm_data = mysqli_data_fetch($tbL56,'room_type_id',$rm_query,'array');

	if(is_array($rm_data)) {
		
		$rm_type_query = "";
		
		$_html_construct .= '<div id="section-to-print" class="block-element top-push-20 nc-width-80">';
		$_html_construct .= '<span class="float-right top-pull-3"><a title="Print Sheet" href="javascript:window.print()" class="blue-font"><b class="fas fa-print nobold"></b></a></span>';
		$_html_construct .= '<h3 class="large">Summary Rooms for Today</h3>';
		$_html_construct .= '<table cellpadding="0" cellspacing="0" class="box-border-thick">';
		$_html_construct .= '<tr>';
		$_html_construct .= '<th width="200px" align="center">Room Type</th>';
		$_html_construct .= '<th width="150px" align="center">Reserved</th>';
		$_html_construct .= '<th width="150px" align="center">Occupied</th>';
		$_html_construct .= '<th width="100px" align="center">Vacant</th>';
		$_html_construct .= '<th width="150px" align="center">Checkout</th>';
		$_html_construct .= '<th width="100px" align="center">Total</th>';
		$_html_construct .= '</tr>';

		$gtotal = 0; $totalroom = 0;
		$tl_reserved=0; $tl_checkedin=0; $tl_vacant=0; $tl_checkout=0; $eachroomtypetotal = 0;

		foreach ($rm_data as $rm_key => $rm_value) {
			
			$eachroomtypetotal = idget_data($tbL52,$rm_value['room_type_id'],'noofrooms');
			$cur_room = idget_data($tbL52,$rm_value['room_type_id'],'name');
			
			$_html_construct .= '<tr>';
			$_html_construct .= '<td width="200px" align="left" class="left-pull-10"><a href="?logs='.$logs.'&isroomtype='.$rm_value['room_type_id'].'" class="blue-font default-text-font-bold noselect">'.$cur_room.'</a></td>';

			?>
				<div class="block-element bottom-push-30">
					<h3 class="large royal-blue-font">&bull; <?php echo $cur_room; ?></h3>
						<?php

						$additionalQuery = " ORDER BY roomnumber ASC";
						$rm_type_query = array("room_type_id"=>$rm_value['room_type_id'],"deletedata"=>0,"roomstatus"=>1);
						$rm_type_data = mysqli_data_fetch($tbL56,'id,roomnumber,roomprefix,blockid',$rm_type_query,'array');
						$additionalQuery = "";

						if(is_array($rm_type_data)) {
						
							$housekeeping_color_tag = "";
							$room_color_tag = "";
							$housekeeping_label_tag = "";
							$room_label_tag = "";

							$cur_block_name = "";
							$room_house_kp = "";
							$status_arry = array();

							$wgt_customerid = "";
							$customer_booking_number = "";
							$customer_name = "";
							$customer_mobile = "";
							$releasebutton = "";

							foreach($rm_type_data as $rm_typ_key => $rm_typ_value) {
								
								$cur_block_name = idget_data($tbL49,$rm_typ_value['blockid'],'name');
								$room_house_kp = array("roomid"=>$rm_typ_value['id']);

								//get this room current status
								//$additionalQuery = $query;
								//$this_room_status_data = mysqli_data_fetch($tbL97,'stateid',$room_house_kp,'noarray');

								$additionalQuery = $query;
								$this_room_hsk_status_data = mysqli_data_fetch($tbL94,'housekeeping_stateid,room_status_id',$room_house_kp,'noarray');

								if(is_array($this_room_hsk_status_data)) {
									//housekeeping status
									if($this_room_hsk_status_data[0] > 0) {
										$housekeeping_color_tag = idget_data($tbL36,$this_room_hsk_status_data[0],'colorcode');
										$housekeeping_label_tag = idget_data($tbL36,$this_room_hsk_status_data[0],'legendname');

										$room_color_tag = idget_data($tbL38,$this_room_hsk_status_data[1],'colorcode');
										$room_label_tag = idget_data($tbL38,$this_room_hsk_status_data[1],'legendname');
										array_push($status_arry,$this_room_hsk_status_data[1]);

										if($this_room_hsk_status_data[1] == 3) {
											$room_wgt_customer = array("roomid"=>$rm_typ_value['id'],"status"=>"CheckedIn");
											$room_wgt_data = mysqli_data_fetch($tbL127,'booking_number,customerid,roomid',$room_wgt_customer,'noarray');

											if($room_wgt_data[2] >= 1) {
												$wgt_customerid = $room_wgt_data[1];
												$customer_booking_number = $room_wgt_data[0];
												if($room_wgt_data[1] > 0) {
													$customer_name = idget_data($tbL102,$room_wgt_data[1],'fname').' ';
													$customer_name .= idget_data($tbL102,$room_wgt_data[1],'lname');
													$customer_mobile = idget_data($tbL102,$room_wgt_data[1],'mobile');
												} else {
													$customer_name = 'Guest-name Unknown';
													$customer_mobile = 'No-number';
												}
												$releasebutton = "";
											} else {
												$raiseAlarm += 1;
												$wgt_customerid = 0;
												$customer_booking_number = "";
												$customer_name = "";
												$customer_mobile = "";
												$releasebutton = '<p class="top-pull-30"><input title="Error: Free this room for re-booking" type="button" value="RELEASE ROOM" class="sml-rounded-button anchor" onclick="rRl2(this)" lang="'.$rm_typ_value['id'].'"></p>';
											}

										} elseif($this_room_hsk_status_data[1] == 6) {
											$room_wgt_customer = array("roomid"=>$rm_typ_value['id'],"status"=>"Reserved");
											$room_wgt_data = mysqli_data_fetch($tbL127,'booking_number,customerid,roomid',$room_wgt_customer,'noarray');

											if($room_wgt_data[2] >= 1) {
												$wgt_customerid = $room_wgt_data[1];
												$customer_booking_number = $room_wgt_data[0];
												$customer_name = idget_data($tbL102,$room_wgt_data[1],'fname').' ';
												$customer_name .= idget_data($tbL102,$room_wgt_data[1],'lname');
												$customer_mobile = idget_data($tbL102,$room_wgt_data[1],'mobile');
												$releasebutton = "";
											} else {
												$raiseAlarm += 1;
												$wgt_customerid = 0;
												$customer_booking_number = "";
												$customer_name = "";
												$customer_mobile = "";
												$releasebutton = '<p class="top-pull-30"><input title="Error: Free this room for booking" type="button" value="RELEASE ROOM" class="sml-rounded-button anchor" onclick="rRl(this)" lang="'.$rm_typ_value['id'].'"></p>';
											}

										} else {
											$additionalQuery = " AND status IN('CheckedIn','Reserved')";
											$room_wgt_customer = array("roomid"=>$rm_typ_value['id'],"deletedata"=>0);
											$room_wgt_data = mysqli_data_fetch($tbL127,'booking_number,customerid,roomid,status,checkin_date,checkout_date',$room_wgt_customer,'noarray');

											$additionalQuery = "";

											if(is_array($room_wgt_data) && $room_wgt_data[3] == 'CheckedIn') {
												
												$roomAlloted = 1;
												
												$hk_query = array("roomid"=>$rm_typ_value['id']);
												$hk_sql = array("housekeeping_stateid"=>6,"room_status_id"=>3,"startdate"=>$room_wgt_data[4],"endate"=>$room_wgt_data[5],"datelogged"=>$server_get_date);
												mysqli_data_update($tbL94,$hk_sql,$hk_query);

												$hk_query = ""; $hk_sql = "";

											} elseif(is_array($room_wgt_data) && $room_wgt_data[3] == 'Reserved') {
												
												$roomAlloted = 1;
												
												$hk_query = array("roomid"=>$rm_typ_value['id'],"startdate"=>$room_wgt_data[4],"endate"=>$room_wgt_data[5],"datelogged"=>$server_get_date);
												$hk_sql = array("room_status_id"=>6);
												mysqli_data_update($tbL94,$hk_sql,$hk_query);

												$hk_query = ""; $hk_sql = "";
											}

											$wgt_customerid = 0;
											$customer_booking_number = "";
											$customer_name = "";
											$customer_mobile = "";
											$releasebutton = "";
										}
										
									} else {
										$housekeeping_color_tag = $default_housekeeping_legend_color;
										$housekeeping_label_tag = $default_housekeeping_legend;

										$room_color_tag = $default_room_status_legend_color;
										$room_label_tag = $default_room_status_legend;
										array_push($status_arry,1);

										$wgt_customerid = 0;
										$customer_booking_number = "";
										$customer_name = "";
										$customer_mobile = "";
										$releasebutton = "";
									}
								} else {
									$housekeeping_color_tag = $default_housekeeping_legend_color;
									$housekeeping_label_tag = $default_housekeeping_legend;

									$room_color_tag = $default_room_status_legend_color;
									$room_label_tag = $default_room_status_legend;
									array_push($status_arry,1);

									$wgt_customerid = 0;
									$customer_booking_number = "";
									$customer_name = "";
									$customer_mobile = "";
									$releasebutton = "";
								}

								/*if(is_array($this_room_status_data)) {
									
									//room availability status
									if($this_room_status_data[0] > 0) {
										$room_color_tag = idget_data($tbL38,$this_room_status_data[0],'colorcode');
										$room_label_tag = idget_data($tbL38,$this_room_status_data[0],'legendname');
										array_push($status_arry, $this_room_status_data[0]);
									} else {
										$room_color_tag = $default_room_status_legend_color;
										$room_label_tag = $default_room_status_legend;
										array_push($status_arry, 1);
									}

								} else {
									$room_color_tag = $default_room_status_legend_color;
									$room_label_tag = $default_room_status_legend;
									array_push($status_arry, 1);
								}*/

								?>
									<div class="ln-display-box float-left cs-width-200 cs-height-100 box-border-thick right-push-5 bottom-push-5 pads10" style="background: <?php echo $room_color_tag; ?>" title="ROOM <?php echo $rm_typ_value['roomprefix'].$rm_typ_value['roomnumber']; ?> : <?php echo $room_label_tag; ?>">
										<div class="ln-display-box float-left right-pull-7"><small><b><?php echo $rm_typ_value['roomprefix'].$rm_typ_value['roomnumber']; ?></b> [<?php echo $cur_block_name; ?>]</small></div>
										<div class="ln-display-box float-right cs-width-20 cs-height-20" style="background: <?php echo $housekeeping_color_tag; ?>"></div>
										<div class="block-element new-line-space"></div>
										<?php
											if(($this_room_hsk_status_data[1] == 3 || $this_room_hsk_status_data[1] == 6) && $wgt_customerid > 0) {
												?>
													<a href="javascript:void(0)" class="royal-blue-font" onclick="crframe('<?php echo $customer_booking_number; ?>','<?php echo $wgt_customerid; ?>','reservations')">
														<div class="ln-display-box float-left nc-width-90 right-pull-7 top-push-10">
															<h4 class="large nobold"><?php echo $customer_name; ?></h4><h4 class="large nobold default-text-font-bold dark-black-font"><?php echo $customer_booking_number; ?></h4>
															<h4 class="large nobold dark-black-font"><b class="fa-phone right-push-7"></b><?php echo $customer_mobile; ?></h4>
														</div>
														<div class="ln-display-box float-right nc-width-10 left-pull-7 alignrt top-push-10 top-pull-3">
															<h3 class="large nobold mbri-user"></h3>
														</div>
														<div class="block-element new-line-space">
														</div>
													</a>
												<?php
											}

											echo $releasebutton;
										?>
									</div>
								<?php
							}
						}

						?>
					<div class="block-element new-line-space">
					</div>
					<div class="block-element top-push-3">
						<small class="right-push-5"><b>Summary :</b></small>
						<?php
						
							//calculating summary for each room type
							if(is_array($status_arry) && count($status_arry) >= 1) {
								
								$rlg = mysqli_data_fetch($tbL38,'id,legendname','','array');

								if(is_array($rlg)) {
									foreach($rlg as $rlg_key => $rlg_value) {
										$count_f = 0;
										$reserved=0; $checkedin=0; $vacant=0; $checkout=0;
										foreach($status_arry as $f) {
											if($rlg_value['id'] == $f) {
												$count_f += 1; 
											}

											if($f == 6) { $reserved += 1; }
											if($f == 3) { $checkedin += 1; }
											if($f == 1) { $vacant += 1; }
											if($f == 4) { $checkout += 1; }
										}
										$vacant = $eachroomtypetotal - $checkedin;
										?>
											<small class="right-push-5"><?php echo $rlg_value['legendname']; ?> - <?php echo $count_f; ?>,</small>
										<?php
									}
								}
							}
						?>
					</div>
				</div>
			<?php

			//$totalroom = ($vacant + $checkout + $reserved + $checkedin) - ($reserved + $checkedin);
			$totalroom = $eachroomtypetotal;
			//$total = ($vacant + $checkout) - ($reserved + $checkedin);

			$_html_construct .= '<td width="150px" align="center">'.$reserved.'</td>';
			$_html_construct .= '<td width="150px" align="center">'.$checkedin.'</td>';
			$_html_construct .= '<td width="100px" align="center">'.$vacant.'</td>';
			$_html_construct .= '<td width="150px" align="center">'.$checkout.'</td>';
			$_html_construct .= '<td width="100px" align="center">'.$totalroom.'</td>';

			$_html_construct .= '</tr>';

			if(isset($_GET['isroomtype']) && $_GET['isroomtype'] == $rm_value['room_type_id']) {
				$listroomistype = array("room_type"=>$_GET['isroomtype']);
				$listroomintype = mysqli_data_fetch($tbL94,'roomid,housekeeping_stateid,room_status_id,remarks,startdate,endate',$listroomistype,'array');
				if(is_array($listroomintype)) {
					$roomLabel = ""; $pt1 = ""; $pt2 = ""; $pt3 = ""; $pt4 = ""; $pt5 = "";
					$_html_construct .= '<tr>';
					$_html_construct .= '<th width="200px" align="center">Room</th>';
					$_html_construct .= '<th width="150px" align="center">Status</th>';
					$_html_construct .= '<th width="150px" align="center">Housekeep</th>';
					$_html_construct .= '<th width="150px" align="center">Remark</th>';
					$_html_construct .= '<th width="100px" align="center">From</th>';
					$_html_construct .= '<th width="100px" align="center">To</th>';
					$_html_construct .= '</tr>';
					foreach($listroomintype as $key => $val) {
						$roomLabel = idget_data($tbL56,$val['roomid'],'roomprefix');
						$roomLabel .= idget_data($tbL56,$val['roomid'],'roomnumber');
						if($val['room_status_id'] == 1) {

							$pt1 .= '<tr>';
							$pt1 .= '<td width="150px" align="center">'.$roomLabel.'</td>';
							$pt1 .= '<td width="200px" align="center" class="left-pull-10">Available</td>';

							if($val['housekeeping_stateid'] == 1) {
								$pt1 .= '<td width="150px" align="center">Clean</td>';
							} elseif($val['housekeeping_stateid'] == 2) {
								$pt1 .= '<td width="150px" align="center">Dirty</td>';
							} elseif($val['housekeeping_stateid'] == 4) {
								$pt1 .= '<td width="150px" align="center">Inspect</td>';
							} elseif($val['housekeeping_stateid'] == 5) {
								$pt1 .= '<td width="150px" align="center">Repair</td>';
							} elseif($val['housekeeping_stateid'] == 6) {
								$pt1 .= '<td width="150px" align="center">Touchup</td>';
							}

							$pt1 .= '<td width="150px" align="center">'.$val['remarks'].'</td>';
							$pt1 .= '<td width="100px" align="center">--</td>';
							$pt1 .= '<td width="100px" align="center">--</td>';
							$pt1 .= '</tr>';
						}

						if($val['room_status_id'] == 2) {

							$pt2 .= '<tr>';
							$pt2 .= '<td width="150px" align="center">'.$roomLabel.'</td>';
							$pt2 .= '<td width="200px" align="center" class="left-pull-10">Cancelled</td>';

							if($val['housekeeping_stateid'] == 1) {
								$pt2 .= '<td width="150px" align="center">Clean</td>';
							} elseif($val['housekeeping_stateid'] == 2) {
								$pt2 .= '<td width="150px" align="center">Dirty</td>';
							} elseif($val['housekeeping_stateid'] == 4) {
								$pt2 .= '<td width="150px" align="center">Inspect</td>';
							} elseif($val['housekeeping_stateid'] == 5) {
								$pt2 .= '<td width="150px" align="center">Repair</td>';
							} elseif($val['housekeeping_stateid'] == 6) {
								$pt2 .= '<td width="150px" align="center">Touchup</td>';
							}

							$pt2 .= '<td width="150px" align="center">'.$val['remarks'].'</td>';
							$pt2 .= '<td width="100px" align="center">--</td>';
							$pt2 .= '<td width="100px" align="center">--</td>';
							$pt2 .= '</tr>';
						}

						if($val['room_status_id'] == 3) {

							$pt3 .= '<tr>';
							$pt3 .= '<td width="150px" align="center">'.$roomLabel.'</td>';
							$pt3 .= '<td width="200px" align="center" class="left-pull-10">Checked-In</td>';

							if($val['housekeeping_stateid'] == 1) {
								$pt3 .= '<td width="150px" align="center">Clean</td>';
							} elseif($val['housekeeping_stateid'] == 2) {
								$pt3 .= '<td width="150px" align="center">Dirty</td>';
							} elseif($val['housekeeping_stateid'] == 4) {
								$pt3 .= '<td width="150px" align="center">Inspect</td>';
							} elseif($val['housekeeping_stateid'] == 5) {
								$pt3 .= '<td width="150px" align="center">Repair</td>';
							} elseif($val['housekeeping_stateid'] == 6) {
								$pt3 .= '<td width="150px" align="center">Touchup</td>';
							}

							$pt3 .= '<td width="150px" align="center">'.$val['remarks'].'</td>';
							$pt3 .= '<td width="100px" align="center">'.date('d/m/y',strtotime($val['startdate'])).'</td>';
							$pt3 .= '<td width="100px" align="center">'.date('d/m/y',strtotime($val['endate'])).'</td>';
							$pt3 .= '</tr>';
						}

						if($val['room_status_id'] == 4) {

							$pt4 .= '<tr>';
							$pt4 .= '<td width="150px" align="center">'.$roomLabel.'</td>';
							$pt4 .= '<td width="200px" align="center" class="left-pull-10">Checked-Out</td>';

							if($val['housekeeping_stateid'] == 1) {
								$pt4 .= '<td width="150px" align="center">Clean</td>';
							} elseif($val['housekeeping_stateid'] == 2) {
								$pt4 .= '<td width="150px" align="center">Dirty</td>';
							} elseif($val['housekeeping_stateid'] == 4) {
								$pt4 .= '<td width="150px" align="center">Inspect</td>';
							} elseif($val['housekeeping_stateid'] == 5) {
								$pt4 .= '<td width="150px" align="center">Repair</td>';
							} elseif($val['housekeeping_stateid'] == 6) {
								$pt4 .= '<td width="150px" align="center">Touchup</td>';
							}

							$pt4 .= '<td width="150px" align="center">'.$val['remarks'].'</td>';
							$pt4 .= '<td width="100px" align="center">--</td>';
							$pt4 .= '<td width="100px" align="center">--</td>';
							$pt4 .= '</tr>';
						}

						if($val['room_status_id'] == 6) {

							$pt5 .= '<tr>';
							$pt5 .= '<td width="150px" align="center">'.$roomLabel.'</td>';
							$pt5 .= '<td width="200px" align="center" class="left-pull-10">Reserved</td>';

							if($val['housekeeping_stateid'] == 1) {
								$pt5 .= '<td width="150px" align="center">Clean</td>';
							} elseif($val['housekeeping_stateid'] == 2) {
								$pt5 .= '<td width="150px" align="center">Dirty</td>';
							} elseif($val['housekeeping_stateid'] == 4) {
								$pt5 .= '<td width="150px" align="center">Inspect</td>';
							} elseif($val['housekeeping_stateid'] == 5) {
								$pt5 .= '<td width="150px" align="center">Repair</td>';
							} elseif($val['housekeeping_stateid'] == 6) {
								$pt5 .= '<td width="150px" align="center">Touchup</td>';
							}

							$pt5 .= '<td width="150px" align="center">'.$val['remarks'].'</td>';
							$pt5 .= '<td width="100px" align="center">'.date('d/m/y',strtotime($val['startdate'])).'</td>';
							$pt5 .= '<td width="100px" align="center">'.date('d/m/y',strtotime($val['endate'])).'</td>';
							$pt5 .= '</tr>';
						}

					}

					$_html_construct .= $pt1;
					$_html_construct .= $pt2;
					$_html_construct .= $pt3;
					$_html_construct .= $pt4;
					$_html_construct .= $pt5;
				}
			}

			$tl_reserved = $tl_reserved + $reserved;
			$tl_checkedin = $tl_checkedin + $checkedin;
			$tl_vacant = $tl_vacant + $vacant;
			$tl_checkout = $tl_checkout + $checkout;

			$gtotal = $gtotal + $totalroom;

		}

		$_html_construct .= '<tr>';
		$_html_construct .= '<td width="200px" align="center"><b>Total</b></td>';
		$_html_construct .= '<td width="150px" align="center"><b>'.$tl_reserved.'</b></td>';
		$_html_construct .= '<td width="150px" align="center"><b>'.$tl_checkedin.'</b></td>';
		$_html_construct .= '<td width="100px" align="center"><b>'.$tl_vacant.'</b></td>';
		$_html_construct .= '<td width="150px" align="center"><b>'.$tl_checkout.'</b></td>';
		$_html_construct .= '<td width="100px" align="center"><b>'.$gtotal.'</b></td>';
		$_html_construct .= '</tr>';

		$_html_construct .= '</table>';
		$_html_construct .= '</div>';

		echo $_html_construct;
	}

	
	$rm_color_legend_query = array("deletedata"=>0);
	$rm_color_legend = mysqli_data_fetch($tbL38,'id,legendname,colorcode,status',$rm_color_legend_query,'array');

	if(is_array($rm_color_legend))
	{
		?>
			<br><h3 class="large">Room Status Legends</h3>
			<?php
				foreach($rm_color_legend as $theader => $tdata)
				{
					?>
						<span class="ln-display-box float-left right-push-50 bottom-push-20">
							<div class="ln-display-box float-left cs-width-20 cs-height-20" style="background:<?php echo $tdata["colorcode"]; ?>">&nbsp;</div>
							<div class="ln-display-box float-left left-push-15"><?php echo $tdata["legendname"]; ?></div>
							<div class="block-element new-line-space"></div>
						</span>
					<?php
				}
				?>
					<span class="block-element new-line-space">
					</span>
			<?php
	}


	$hsk_color_legend_query = array("deletedata"=>0);
	$hsk_color_legend = mysqli_data_fetch($tbL36,'id,legendname,colorcode,status',$hsk_color_legend_query,'array');

	if(is_array($hsk_color_legend))
	{
		?>
			<br><h3 class="large">Housekeeping Status Legends</h3>
			<?php
				foreach($hsk_color_legend as $theader => $tdata)
				{
					?>
						<span class="ln-display-box float-left right-push-50 bottom-push-20">
							<div class="ln-display-box float-left cs-width-20 cs-height-20" style="background:<?php echo $tdata["colorcode"]; ?>">&nbsp;</div>
							<div class="ln-display-box float-left left-push-15"><?php echo $tdata["legendname"]; ?></div>
							<div class="block-element new-line-space"></div>
						</span>
					<?php
				}
				?>
					<span class="block-element new-line-space">
					</span>
			<?php
	}

?>

<script>

	const roomAlloted = "<?php echo $roomAlloted; ?>";
	const raiseAlarm = "<?php echo $raiseAlarm; ?>";

	if(roomAlloted == 1) { window.location.reload(true); }
	if(parseInt(raiseAlarm) >= 1) { alert('Notification\nOne or two rooms are not presenting well in your room list.\n\nWHAT TO DO: Use the button there to release the room and run a fresh booking'); }

	function dateStat() {
		var d = document.getElementById('period').value;
		if(d == 'Custom Date') {
			objDisplay('custom-date');
			document.getElementById('startdate').required = true;
			document.getElementById('endate').required = true;
		} else {
			objHidden('custom-date');
			document.getElementById('startdate').required = false;
			document.getElementById('endate').required = false;
		}
	}

	function rRl(obj) {
		window.location.href = window.location.href+'&rrid='+obj.lang;
	}

	function rRl2(obj) {
		window.location.href = window.location.href+'&lrrid='+obj.lang;
	}

</script>

