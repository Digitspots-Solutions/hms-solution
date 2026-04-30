<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$wgt_cur_room_id = $ths_token;
	
	$room_prefix = idget_data($tbL56,$wgt_cur_room_id,'roomprefix');
	$room_number = idget_data($tbL56,$wgt_cur_room_id,'roomnumber');
	$block_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'blockid');
	$block_name = idget_data($tbL49,$block_id,'name');

	$room_type_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'room_type_id');
	$room_type_name = idget_data($tbL52,$room_type_id,'name');

	$getroomtype = select_dt_fetch('status','Active',$tbL52,'id','name');

	include "post_booking_tokens.php";
	include "booking_tokens.php";

	$get_cspg = (!empty($wgt_bill_to) && $wgt_bill_to >= 1) ? $wgt_bill_to : $wgt_bill_to_g;

?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Room Type Tariff Change')" id="authenform">
		<h3 class="large nobold default-text-font-bold">User Authentication</h3><br>
		<div id="fmessage" align="center">
		</div>
		<span class="block-element">
			<input type="hidden" name="wgtkey" id="wgtkey" value="<?php echo $booking_number; ?>">
		</span>
		<span class="block-element bottom-push-10">
			<input type="text" name="wgtuserid" id="wgtuserid" placeholder="User Login" required="required">
		</span>
		<span class="block-element bottom-push-20">
			<input type="password" name="wgtpwd" id="wgtpwd" placeholder="User Password" required="required">
		</span>
		<span class="block-element bottom-push-30">
			<textarea name="wgtremark" id="wgtremark" placeholder="Enter remark?" required="required"></textarea>
		</span>

		<div id="fbutton" class="alignct">
			<input type="submit" name="logbutton" value="Continue" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
		</div>
	</form>
</div>
<div id="utask" class="top-push-50 noshow">
	<form action="" method="post" autocomplete="off" onsubmit="document.getElementById('tip').innerHTML='Applying, please wait..'; setTimeout(chgclass('roomtariffchangebutton','xfadein nc-width-80 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button motion'),1000)">
		<div class="block-element">
			<fieldset>
				<legend><h2 class="large nobold default-text-font-bold nomargin">Room Type Tariff Change</h2></legend>
				<div class="block-element cs-height-10"></div>
				<h3 class="large nobold alignct">Note: System will override the price of this room with the new room-type rack rate</h3><br>
				<h3 class="large nobold default-text-font-bold alignct"><?php echo $room_type_name; ?>: <?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</h3>
				<div class="block-element top-push-10">
					<div class="noshow">
						<span class="ln-display-box float-left nc-width-40 right-pull-20 top-pull-10">
							<!-- onchange="getdata('wgtroom','eget-rooms','wgtpstroomtype','dropbox');"-->
							<select name="wgtpstroomtype" id="wgtpstroomtype" required>
								<option value="<?php echo $room_type_id; ?>" selected="selected"><?php echo $room_type_name; ?></option>
							</select>
						</span>
						<span class="ln-display-box float-left nc-width-20 right-pull-20 alignct top-pull-15">
							<h1 class="large nobold mbri-right"></h1>
						</span>
						<span class="ln-display-box float-left nc-width-40 top-pull-10">
							<select name="wgtroom" id="wgtroom" required>
								<option value="<?php echo $wgt_cur_room_id; ?>" selected="selected"><?php echo $room_prefix.$room_number; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<span class="ln-display-box float-left nc-width-50 right-pull-20 top-pull-10">
						<select name="wgtpstroomtype2" id="wgtpstroomtype2" onchange="getJson_data('room-type-detail','nodetail','wgtpstroomtype2')" required>
							<option value="" selected="selected">Choose Room Type:</option>
							<?php echo $getroomtype; ?>
						</select>
					</span>
					<span class="ln-display-box float-left nc-width-20 right-pull-20 alignct top-pull-15">
						<h1 class="large nobold mbri-left"></h1>
					</span>
					<span class="ln-display-box float-left nc-width-30 top-pull-15">
						<h3 class="large nobold">Using the price of?</h3>
					</span>
					<span class="block-element new-line-space">
					</span>
					<p>&nbsp;</p>
					<input type="hidden" name="customer-type" id="customer-type" value="<?php echo $wgt_booking_type; ?>">
					<input type="hidden" name="apply-wkly-tariff" id="apply-wkly-tariff" value="No">
					<input type="hidden" name="cspg" id="cspg" value="<?php echo $get_cspg; ?>">
					<div id="room-type-detail" class="noshow"></div>
				</div>
			</fieldset>

			<div class="block-element top-pull-50 alignct">
				<input type="hidden" name="wgtunitprice" id="unitprice" value="0">
				<input type="hidden" name="wgtdiscount" id="wgt-discount" value="0">
				<input type="hidden" name="wgttax" id="wgt-tax" value="0">
				<input type="hidden" name="wgtservicecharge" id="wgt-service-charge" value="0">
				<input type="hidden" name="wgtconsumption" id="wgt-consumption" value="0">
				<input type="hidden" name="wgtcuroom" id="wgtcuroom" value="<?php echo $room_prefix.$room_number; ?>">
				<input type="hidden" name="wgtcuroomid" id="wgtcuroomid" value="<?php echo $wgt_cur_room_id; ?>">
				<input type="hidden" name="wgtroomtype" id="wgtroomtype" value="<?php echo $room_type_id; ?>">
				<input type="hidden" name="wgtpstbk" id="wgtpstbk" value="<?php echo $booking_number; ?>">
				<p id="tip" class="alignct pads7 blue-font"></p>
				<input type="submit" name="roomtariffchangebutton" id="roomtariffchangebutton" value="Apply" class="nc-width-80 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button motion">
			</div>
		</div>
	</form>
</div>