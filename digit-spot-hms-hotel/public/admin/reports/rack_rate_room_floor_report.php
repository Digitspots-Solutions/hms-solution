<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	$blocks = select_dt_fetch('',0,$tbL49,'id','name');

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(isset($_POST['blocklist']) && !empty($_POST['blocklist']) && $_POST['blocklist'] > 0) { $blockid = $_POST['blocklist']; $blockname = idget_data($tbL49,$_POST['blocklist'],'name'); }
	else { $blockid = 0; $blockname = 'All'; }

	if(isset($_POST['floorlist']) && !empty($_POST['floorlist']) && $_POST['floorlist'] > 0) { $floorid = $_POST['floorlist']; $floorname = idget_data($tbL50,$_POST['floorlist'],'name'); }
	else { $floorid = 0; $floorname = 'Floors'; }

?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the rack rate reports
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Rack Rate Room Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php if(isset($_POST['fieldset1'])) { echo $_POST['fieldset1']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Filter By Blocks</small>
			<select name="blocklist" id="blocklist" onchange="getdata('floorlist','eget-block-floors-list','blocklist','dropbox');">
				<option value="<?php echo $blockid; ?>" selected="selected"><?php echo $blockname; ?></option>
				<?php echo $blocks; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Filter By Floors</small>
			<select name="floorlist" id="floorlist">
				<option value="<?php echo $floorid; ?>" selected="selected"><?php echo $floorname; ?></option>
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
		
		$date_selected = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$gs_query = array("deletedata"=>0);

		if($blockid > 0) { $gs_query['blockid'] = $blockid; }
		if($floorid > 0) { $gs_query['floorid'] = $floorid; }

		?>
		<p class="alignrt bottom-pull-20">
			<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
		</p>

		<div id="section-to-print" class="block-element">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<small class="block-element alignct">Rack Rate Room Report (<?php echo $date_selected; ?>)</small>
			<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

			<?php

				#start report selection

				
				$allrooms = array();
				$theroomIDs = "";

				$get_guest_rooms = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$gs_query,'array');
				
				if(is_array($get_guest_rooms)) {
					foreach($get_guest_rooms as $kr => $kv) {
						
						$theroomIDs .= $kv['id'].',';

						$room = $kv['roomprefix'].$kv['roomnumber'];
						array_push($allrooms,$room);

						$room = "";
					}

					sort($allrooms);
					$theroomIDs = substr_replace($theroomIDs,'',-1,1);
				}

				$additionalQuery = " AND roomid IN({$theroomIDs}) AND bill_date='{$_POST['fieldset1']}'";
				$gsx_query = array("room_status"=>"CheckedIn","ischarged"=>1,"deletedata"=>0);

				$guest_dataproperty = "booking_number,customerid,roomid,invoice_number";
				$get_guest_data = mysqli_data_fetch($tbL134,$guest_dataproperty,$gsx_query,'array');
				$additionalQuery = "";

				?>
					<div class="block-element top-push-20">
						<div class="block-element top-push-5 box-border-thick">
							<table cellpadding="0" cellspacing="0" border="1">
								<tr>
									<td class="default-text-font-bold" width="100px" align="center">Room No.</td>
									<td class="default-text-font-bold" width="200px" align="center">Guest Name</td>
									<td class="default-text-font-bold" width="100px" align="center">Status</td>
									<td class="default-text-font-bold" width="100px" align="center">Rack Rate</td>
									<td class="default-text-font-bold" width="100px" align="center">Discount</td>
									<td class="default-text-font-bold" width="150px" align="center">Different Price</td>
									<td class="default-text-font-bold" width="100px" align="center">Inclusion</td>
									<td class="default-text-font-bold" width="150px" align="center">Extra Bed Fare</td>
									<td class="default-text-font-bold" width="150px" align="center">Service Charge</td>
									<td class="default-text-font-bold" width="80px" align="center">Vat</td>
									<td class="default-text-font-bold" width="80px" align="center">Consumption</td>
									<td class="default-text-font-bold" width="150px" align="center">Total Amount</td>
								</tr>

								<?php

									if(is_array($get_guest_data)) {
										
										$stay_f = ""; $stay_t = ""; $customer_name = ""; $salutation = ""; $billto = ""; $country = "";
										$dateofbooking = ""; $room_name = ""; $g_username = ""; $bkt = ""; $checkin_date = "";
										$checkin_time = ""; $room_name = ""; $room_floor = ""; $country_name = ""; $room_status = ""; $datakey = "";

										$diff_price = 0; $inclusion = 0; $total_billed = 0;
										$wr_data = array(); $wr_data2 = array();

										foreach($get_guest_data as $scd_key => $scd_value) {

											$goc_sql = "SELECT * FROM {$tbL127} WHERE booking_number='{$scd_value['booking_number']}' AND roomid='{$scd_value['roomid']}' AND customerid='{$scd_value['customerid']}' AND deletedata=0 AND status IN('CheckedIn','CheckedOut')"; $goc_data = wgetSQL($goc_sql);

											if($scd_value['invoice_number'] == 'LATECHECKOUT' || $scd_value['invoice_number'] == 'EARLYCHECKIN') { $room_status = $scd_value['invoice_number']; }
											else { $room_status = $goc_data[0]['status']; }

											$room_name = idget_data($tbL56,$scd_value['roomid'],'roomprefix');
											$room_name .= idget_data($tbL56,$scd_value['roomid'],'roomnumber');

											$salutation = idget_data($tbL102,$scd_value['customerid'],'salutation');
											$billto = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'bill_to');
											$booking_type = idget_fdata($tbL130,'booking_number',$scd_value['booking_number'],'booking_type');

											$customer_name = idget_data($tbL42,$salutation,'name').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'fname').' ';
											$customer_name .= idget_data($tbL102,$scd_value['customerid'],'lname').' ';

											if($booking_type == 'corporate' && isset($billto) && $billto >= 1) { $customer_name .= " (".idget_data($tbL58,$billto,'name').")"; }

											$rack_sql = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts, SUM(extrabed_charges) AS extracharge FROM {$tbL134} WHERE booking_number='{$scd_value['booking_number']}' AND roomid='{$scd_value['roomid']}' AND customerid='{$scd_value['customerid']}' AND bill_date='{$_POST['fieldset1']}' AND deletedata=0";
											$rack_data = wgetSQL($rack_sql);

											$inclusion = 0;
											$diff_price = $rack_data[0]['total'] - $rack_data[0]['discounts'];
											$total_billed = $diff_price + $inclusion + $rack_data[0]['extracharge'] + $rack_data[0]['scharge'] + $rack_data[0]['vat'] + $rack_data[0]['consumption'];
											
											$wr_data[$room_name][] = array($customer_name,$room_status,$rack_data[0]['total'],$rack_data[0]['discounts'],$diff_price,$inclusion,$rack_data[0]['extracharge'],$rack_data[0]['scharge'],$rack_data[0]['vat'],$rack_data[0]['consumption'],$total_billed);
										}
										

										if(is_array($wr_data)) {
											
											$total_rack_rate = 0; $total_discount = 0; $total_diff = 0; $total_incl = 0;
											$total_extrabed = 0; $total_service = 0; $total_vat = 0; $total_consumption = 0;
											$total_actual_amount = 0; $guestsesID = "";

											$numbr = 0;

											foreach($allrooms as $room) {
												
												if(is_array($wr_data[$room]) && count($wr_data[$room]) > 0) {
													
													for($r=0; $r < count($wr_data[$room]); $r++) {
													
														$numbr += 1;

														$total_rack_rate = $total_rack_rate + $wr_data[$room][$r][2];
														$total_discount = $total_discount + $wr_data[$room][$r][3];
														$total_diff = $total_diff + $wr_data[$room][$r][4];
														$total_incl = $total_incl + $wr_data[$room][$r][5];
														$total_extrabed = $total_extrabed + $wr_data[$room][$r][6];
														$total_service = $total_service + $wr_data[$room][$r][7];
														$total_vat = $total_vat + $wr_data[$room][$r][8];
														$total_consumption = $total_vat + $wr_data[$room][$r][9];
														$total_actual_amount = $total_actual_amount + $wr_data[$room][$r][10];
														
														?>
															<tr>
																<td width="100px" align="center"><?php echo $room; ?></td>
																<td width="200px" align="center"><?php echo $wr_data[$room][$r][0]; ?></td>
																<td width="100px" align="center"><?php echo $wr_data[$room][$r][1]; ?></td>
																<td width="100px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][2],2); ?></td>
																<td width="100px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][3],2); ?></td>
																<td width="150px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][4],2); ?></td>
																<td width="100px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][5],2); ?></td>
																<td width="150px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][6],2); ?></td>
																<td width="150px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][7],2); ?></td>
																<td width="80px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][8],2); ?></td>
																<td width="80px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][9],2); ?></td>
																<td width="150px" align="center">&#8358; <?php echo number_format($wr_data[$room][$r][10],2); ?></td>
															</tr>
														<?php
													}
												}
											}

											?>
												<tr>
													<td width="80px" align="center">&nbsp;</td>
													<td width="200px" align="center">&nbsp;</td>
													<td width="150px" align="center">&nbsp;</td>
													<td width="100px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_rack_rate,2); ?></td>
													<td width="100px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_discount,2); ?></td>
													<td width="150px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_diff,2); ?></td>
													<td width="100px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_incl,2); ?></td>
													<td width="150px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_extrabed,2); ?></td>
													<td width="150px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_service,2); ?></td>
													<td width="80px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_vat,2); ?></td>
													<td width="80px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_consumption,2); ?></td>
													<td width="150px" align="center" class="default-text-font-bold">&#8358; <?php echo number_format($total_actual_amount,2); ?></td>
												</tr>
											<?php
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