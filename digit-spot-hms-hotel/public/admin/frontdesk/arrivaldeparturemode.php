<?php
	
	$booking_number = $ftoken;
	$ths_token = $stoken;


	include "post_booking_tokens.php";
	include "booking_tokens.php";

?>
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Guest Arrival & Departure Mode</h2></legend>
			<div class="block-element cs-height-10"></div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Source of Link <sup class="red-font">*</sup></small>
				<select name="wgtfield1" id="wgtfield1" required="required">
					<option value="<?php echo $gad_1; ?>" selected="selected"><?php echo $source_of_biz; ?></option>
					<?php echo $business_src; ?>
				</select>
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Arrival Mode <sup class="red-font">*</sup></small>
				<select name="wgtfield2" id="wgtfield2" required="required">
					<option value="<?php echo $gad_2; ?>" selected="selected"><?php echo $arrival_mode; ?></option>
					<?php echo $transit_mode; ?>
				</select>
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Departure Mode <sup class="red-font">*</sup></small>
				<select name="wgtfield3" id="wgtfield3">
					<option value="<?php echo $gad_3; ?>" selected="selected"><?php echo $departure_mode; ?></option>
					<?php echo $transit_mode; ?>
				</select>
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
				<textarea name="wgtfield4" id="wgtfield4"><?php echo $gad_remarks; ?></textarea>
			</div>
		</fieldset>

		<div class="block-element top-pull-50 alignct">
			<input type="hidden" name="wgtfield5" id="wgtfield5" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="wgtag" id="wgtag" value="guestarrivaldeparture">
			<input type="submit" name="submitbutton" value="Save Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 dark-black-white-state rounded-button default-text-font-bold">
		</div>
	</div>
</form>