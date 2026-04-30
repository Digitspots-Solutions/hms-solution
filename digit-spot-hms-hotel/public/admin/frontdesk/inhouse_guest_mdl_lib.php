<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	$blocks = select_dt_fetch('',0,$tbL49,'id','name');

	if(isset($_POST['blocklist']) && !empty($_POST['blocklist']) && $_POST['blocklist'] > 0) { $blockid = $_POST['blocklist']; $blockname = idget_data($tbL49,$_POST['blocklist'],'name'); }
	else { $blockid = 0; $blockname = 'All'; }

	if(isset($_POST['floorlist']) && !empty($_POST['floorlist']) && $_POST['floorlist'] > 0) { $floorid = $_POST['floorlist']; $floorname = idget_data($tbL50,$_POST['floorlist'],'name'); }
	else { $floorid = 0; $floorname = 'Floors'; }

	if(isset($_POST['fieldset3']) && !empty($_POST['fieldset3'])) { $bookid = $_POST['fieldset3']; $bookname = ucfirst($_POST['fieldset3']); }
	else { $bookid = 0; $bookname = "All"; }


	if(isset($_POST['fieldset4']) && !empty($_POST['fieldset4'])) { $bookstatid = $_POST['fieldset4']; $bookstatname = $_POST['fieldset4']; }
	else { $bookstatid = 0; $bookstatname = "All"; }

?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the list of in-house guest reports
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">In-House Guest Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php if(isset($_POST['fieldset1'])) { echo $_POST['fieldset1']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php if(isset($_POST['fieldset2'])) { echo $_POST['fieldset2']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Filter By Blocks</small>
			<select name="blocklist" id="blocklist" onchange="getdata('floorlist','eget-block-floors-list','blocklist','dropbox');">
				<option value="<?php echo $blockid; ?>" selected="selected"><?php echo $blockname; ?></option>
				<?php echo $blocks; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Floor</small>
			<select name="floorlist" id="floorlist">
				<option value="<?php echo $floorid; ?>" selected="selected"><?php echo $floorname; ?></option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Booking Type</small>
			<select name="fieldset3" id="fieldset3" onchange="bktp(this.value)" required="required">
				<option value="<?php echo $bookid; ?>" selected="selected"><?php echo $bookname; ?></option>
				<option value="individual">Individual</option>
				<option value="corporate">Corporate</option>
				<option value="agent">Agent</option>
				<option value="e-booking">E-Booking</option>
				<option value="complimentary">Complimentary</option>
			</select>
			<div id="cspg-list" class="noshow fx-position-flow cs-width-250 white-theme pads15 sml-rounded-button obj-light-shadow">
				<h4 class="large nobold">Corporate List</h4>
				<div class="top-pull-10">
					<?php
						$extable = $tbL58; $extcols = "name"; $extkey = "id";
						$get_cspg = select_dt_fetch('booking_type','corporate',$tbL130,'bill_to_g','bill_to_g');
					?>
					<select name="cspg" id="cspg" class="nopads no-back-black">
						<option value="" selected>All</option>
						<?php echo $get_cspg; ?>
					</select>
				</div>
			</div>
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Booking Status</small>
			<select name="fieldset4" id="fieldset4" required="required">
				<option value="<?php echo $bookstatid; ?>" selected="selected"><?php echo $bookstatname; ?></option>
				<option value="Checked In">Checked In</option>
				<option value="Checked Out">Checked Out</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-10 right-pull-5 alignct">
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


	if(isset($_POST['submitbutton'])) {
		
		$date_from = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$date_to = write_dateF($gh_get_date_format,$_POST['fieldset2']);

		if(isset($_POST['fieldset4']) && $_POST['fieldset4'] == 'Checked In') {
			$additionalQuery = "";
			$gs_query = array("status"=>"CheckedIn","deletedata"=>0);
		} elseif(isset($_POST['fieldset4']) && $_POST['fieldset4'] == 'Checked Out') {
			$additionalQuery = " AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}'";
			$gs_query = array("status"=>"CheckedOut","deletedata"=>0);
		} else {
			$additionalQuery = "";
			$gs_query = array("status"=>"CheckedIn","deletedata"=>0);
		}

		?>
		<p class="bottom-pull-20">
			<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
		</p>

		<div id="section-to-print" class="block-element">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<small class="block-element alignct">In-House Guest Details Report Between <?php echo $date_from.' And '.$date_to; ?></small>
			<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

			<?php

				#start report selection

				
				$allrooms = array();
				$get_guest_rooms = mysqli_data_fetch($tbL127,'roomid',$gs_query,'array');
				
				if(is_array($get_guest_rooms)) {
					foreach($get_guest_rooms as $kr => $kv) {
						$room .= idget_data($tbL56,$kv['roomid'],'roomprefix');
						$room .= idget_data($tbL56,$kv['roomid'],'roomnumber');
						
						array_push($allrooms,$room);
						$room = "";
					}

					sort($allrooms);
				}

				$guest_dataproperty = "booking_number,customerid,roomid,checkin_date,checkout_date,status";
				$get_guest_data = mysqli_data_fetch($tbL127,$guest_dataproperty,$gs_query,'array');

				?>
					<div class="block-element top-push-20">
						<div class="block-element top-push-5 box-border-thick">
							<table cellpadding="0" cellspacing="0" border="1" class="ft-xxsml-size">
								<tr>
									<th width="50px" align="center">&nbsp;</th>
									<th width="150px" align="center">Booking Number</th>
									<th width="200px" align="center">Guest</th>
									<th width="100px" align="center">Country</th>
									<th width="100px" align="center">Room</th>
									<th width="150px" align="center">Booking Type</th>
									<th width="150px" align="center">Arrival Date</th>
									<th width="150px" align="center">Departure Date</th>
									<th width="150px" align="center">Booking Status</th>
								</tr>

								<?php

									if(is_array($get_guest_data)) {
										
										$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
										$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
										$checkin_time = ""; $room_name = ""; $room_floor = ""; $country_name = ""; $billgto = "";
										$cspg = ""; $get_cspg_name = ""; $booking_type_str = "";

										$wr_data = array();

										foreach($get_guest_data as $scd_key => $scd_value) {

											$stay_f = write_dateF($gh_get_date_format,$scd_value['checkin_date']);
											$stay_t = write_dateF($gh_get_date_format,$scd_value['checkout_date']);

											$room_block = idget_data($tbL56,$scd_value['roomid'],'blockid');
											$room_floor = idget_data($tbL56,$scd_value['roomid'],'floorid');

											$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
											$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

											if(isset($t_bkt) && $t_bkt >= 1) { $bkt = arrayget_key($booking_type,$t_bkt); }
											else { $bkt = "All"; }

											$salutation = idget_data($tbL102,$scd_value['customerid'],'salutation');
											$billto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to');
											$billgto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to_g');
											$booking_type = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'booking_type');

											$customer_name = idget_data($tbL42,$salutation,'name').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'fname').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'lname').' ';

											$cspgid = !empty($billto) ? $billto : $billgto;

											if($booking_type == 'corporate' && isset($cspgid) && $cspgid >= 1) {
												$get_cspg_name = " (".idget_data($tbL58,$cspgid,'name').")";
												$booking_type_str = ucfirst($booking_type).$get_cspg_name;
											} else {
												$booking_type_str = ucfirst($booking_type);
											}

											$country = idget_data($tbL102,$scd_value['customerid'],'country');
											$country_name = idget_data('countries',$country,'name');

											if(isset($_POST['blocklist']) && $_POST['blocklist'] > 0 && isset($_POST['floorlist']) && $_POST['floorlist'] > 0 && isset($bookid) && !empty($bookid) && empty($_POST['cspg'])) {
												if($bookid == $booking_type && $_POST['blocklist'] == $room_block && $_POST['floorlist'] == $room_floor) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif(isset($_POST['blocklist']) && $_POST['blocklist'] > 0 && isset($_POST['floorlist']) && $_POST['floorlist'] > 0 && isset($bookid) && !empty($bookid) && !empty($_POST['cspg'])) {
												if($bookid == $booking_type && $_POST['blocklist'] == $room_block && $_POST['floorlist'] == $room_floor && $_POST['cspg'] == $cspgid) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif(isset($_POST['blocklist']) && $_POST['blocklist'] > 0 && $_POST['floorlist'] == 0 && isset($bookid) && !empty($bookid) && empty($_POST['cspg'])) {
												if($bookid == $booking_type && $_POST['blocklist'] == $room_block) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif(isset($_POST['blocklist']) && $_POST['blocklist'] > 0 && $_POST['floorlist'] == 0 && isset($bookid) && !empty($bookid) && !empty($_POST['cspg'])) {
												if($bookid == $booking_type && $_POST['blocklist'] == $room_block && $_POST['cspg'] == $cspgid) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif($_POST['blocklist'] == 0 && $_POST['floorlist'] == 0 && isset($bookid) && !empty($bookid) && empty($_POST['cspg'])) {
												if($bookid == $booking_type) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif($_POST['blocklist'] == 0 && $_POST['floorlist'] == 0 && isset($bookid) && !empty($bookid) && !empty($_POST['cspg'])) {
												if($bookid == $booking_type && $_POST['cspg'] == $cspgid) {

													$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
												}

											} elseif($_POST['blocklist'] == 0 && $_POST['floorlist'] == 0 && $bookid == 0) {
											
												$wr_data[$room_name] = array($scd_value['booking_number'],$customer_name,$country_name,$booking_type_str,$stay_f,$stay_t,$scd_value['status']);
											}
										}

										//print_r($wr_data);
										//$wr_data = array_unique($wr_data);
										$printed_rooms = array();

										if(is_array($wr_data)) {
											
											$numbr = 0;

											foreach($allrooms as $room) {
												
												if(!in_array($room,$printed_rooms) && is_array($wr_data[$room]) && count($wr_data[$room]) > 0) {
													
													$numbr += 1;

													array_push($printed_rooms, $room);

													?>
														<tr>
															<td width="50px" align="center"><?php echo $numbr; ?>.</td>
															<td width="150px" align="center" class="blue-font anchor" onclick="jsxView('<?php echo $wr_data[$room][0]; ?>')"><?php echo $wr_data[$room][0]; ?></td>
															<td width="200px" align="center"><?php echo $wr_data[$room][1]; ?></td>
															<td width="100px" align="center"><?php echo $wr_data[$room][2]; ?></td>
															<td width="100px" align="center"><?php echo $room; ?></td>
															<td width="150px" align="center"><?php echo $wr_data[$room][3]; ?></td>
															<td width="150px" align="center"><?php echo $wr_data[$room][4]; ?></td>
															<td width="150px" align="center"><?php echo $wr_data[$room][5]; ?></td>
															<td width="120px" align="center"><?php echo $wr_data[$room][6]; ?></td>
														</tr>
													<?php
												}
											}
										}
									}

								?>

							</table>
						</div>
						<p class="top-pull-3 ft-sml-size"><?php echo $numbr; ?> Found</p>
					</div>
				<?php
				
			?>
		</div>
		<?php
	}

?>

<script>

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}


	function bktp(val) {
		if(val == 'corporate') {
			chgclass('cspg-list','fx-position-flow cs-width-250 white-theme pads15 sml-rounded-button obj-light-shadow');
		} else {
			chgclass('cspg-list','noshow fx-position-flow cs-width-250 white-theme pads15 sml-rounded-button obj-light-shadow');
			$('#cspg').prop('selectedIndex', 0);
		}
	}

</script>