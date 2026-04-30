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

?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Tariff Change')" id="authenform">
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
	<form action="" method="post" autocomplete="off">
		<div class="block-element">
			<fieldset>
				<legend><h2 class="large nobold default-text-font-bold nomargin">Tariff Change</h2></legend>
				<div class="block-element cs-height-10"></div>
				<h3 class="large nobold alignct">Manual price entry for charging a room: Note that discount will no longer be applicable</h3><br>
				<h3 class="large nobold default-text-font-bold alignct"><?php echo $room_type_name; ?>: <?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</h3>
				<div class="block-element top-push-20 bottom-push-30">
					<span class="block-element box-border-thick-bottom top-pull-10 bottom-pull-15">
						<h4 class="large nobold default-text-font-bold">Amount charging?</h4>
						<div class="top-push-10">
							<input type="number" step="0.01" name="wgtamtcharging" id="wgtamtcharging" placeholder="Enter amount e.g 10000" class="nopads no-back-black">
						</div>
					</span>
				</div>
			</fieldset>

			<div class="block-element top-pull-50 alignct">
				<input type="hidden" name="wgtcuroom" id="wgtcuroom" value="<?php echo $room_prefix.$room_number; ?>">
				<input type="hidden" name="wgtcuroomid" id="wgtcuroomid" value="<?php echo $wgt_cur_room_id; ?>">
				<input type="hidden" name="wgtroomtype" id="wgtroomtype" value="<?php echo $room_type_id; ?>">
				<input type="hidden" name="wgtpstbk" id="wgtpstbk" value="<?php echo $booking_number; ?>">
				<input type="submit" name="manualroomtariffchangebutton" value="Apply" class="nc-width-80 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button">
			</div>
		</div>
	</form>
</div>