<?php
	//$booking_number = $ftoken;
	//$ths_token = $stoken;

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
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Up/Downgrade Room</h2></legend>
			<div class="block-element cs-height-10"></div>
			<h3 class="large nobold alignct">Note: Only available rooms are applicable</h3><br>
			<h3 class="large nobold default-text-font-bold alignct"><?php echo $room_type_name; ?>: <?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</h3>
			<div class="block-element top-push-10">
				<p class="alignct bottom-pull-10"><input type="radio" name="roomstatus" value="Upgraded" required> &nbsp;Upgrade &nbsp;&nbsp; <input type="radio" name="roomstatus" value="Downgraded" required> &nbsp;Downgrade</p>
				<span class="ln-display-box float-left nc-width-40 right-pull-20 top-pull-10">
					<select name="wgtpstroomtype" id="wgtpstroomtype" onchange="getdata('wgtroom','eget-rooms','wgtpstroomtype','dropbox');" required>
						<option value="" selected="selected">From roomtype:</option>
						<?php echo $getroomtype; ?>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-20 right-pull-20 alignct top-pull-15">
					<h1 class="large nobold mbri-right"></h1>
				</span>
				<span class="ln-display-box float-left nc-width-40 top-pull-10">
					<select name="wgtroom" id="wgtroom" onchange="getJson_data('room-type-detail','nodetail','wgtpstroomtype')"  required>
						<option value="" selected="selected">Replace room with:</option>
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
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
			<input type="hidden" name="checkin" id="checkin" value="0000-00-00">
			<input type="hidden" name="checkout" id="checkout" value="0000-00-00">
			<input type="submit" name="updownroombutton" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button">
		</div>
	</div>
</form>