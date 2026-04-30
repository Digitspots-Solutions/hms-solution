<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	include "post_booking_tokens.php";
	include "booking_tokens.php";
	
	$room_query = array("booking_number"=>$booking_number,"roomid"=>$ths_token);
	$room_sql = mysqli_data_fetch($tbL127,'remarks',$room_query,'noarray');

	$room_type_id = idget_fdata($tbL56,'id',$ths_token,'room_type_id');
	$room_type = idget_data($tbL52,$room_type_id,'name');

	$block_id = idget_fdata($tbL56,'id',$ths_token,'blockid');
	$block_name = idget_data($tbL49,$block_id,'name');

	$room_prefix = idget_data($tbL56,$ths_token,'roomprefix');
	$room_number = idget_data($tbL56,$ths_token,'roomnumber');
?>

<h3 class="large nobold">Room: <?php echo $room_prefix.$room_number; ?></h3>
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Room Remark</h2></legend>
			<div class="block-element cs-height-10"></div>
			<textarea name="wgtfield1" id="wgtfield1" class="nopads no-back-black notextborder" placeholder="Write remark here"><?php echo $room_sql[0]; ?></textarea>
		</fieldset>

		<div class="block-element top-pull-50 alignct">
			<input type="hidden" name="wgtfield2" id="wgtfield2" value="<?php echo $ths_token; ?>">
			<input type="hidden" name="wgtfield3" id="wgtfield3" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="wgtag" id="wgtag" value="roomremark">
			<input type="submit" name="submitbutton" value="Save Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 dark-black-white-state rounded-button default-text-font-bold">
		</div>
	</div>
</form>