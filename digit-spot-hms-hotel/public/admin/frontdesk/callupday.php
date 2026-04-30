<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$additionalQuery = " GROUP BY roomid";
	$cday_query = array("booking_number"=>$booking_number,"charge"=>"yes","deletedata"=>0);
	$cday_data = mysqli_data_fetch($tbL134,'roomid',$cday_query,'array');

	include "post_booking_tokens.php";
?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Call-up Day Booking')" id="authenform">
		<h3 class="large nobold default-text-font-bold">User Authentication</h3><br>
		<div id="fmessage" class="bottom-push-7" align="center">
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
		<div id="fbutton" class="alignct">
			<input type="submit" name="logbutton" value="Continue" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
		</div>
	</form>
</div>
<div id="utask" class="top-push-50 noshow">
	<form action="" method="post" onsubmit="" id="fbooking">
		<div class="left-pull-20">
			<h3 class="large nobold default-text-font-bold">Call-up Day Booking</h3>
			<h3 class="large nobold dark-grey-font">The following are day-booking transaction for rooms</h3>
		</div>
		<div class="bottom-push-10 pads15">
			<?php
				if(is_array($cday_data)) {
					$room_prefix = ""; $room_number = "";
					foreach($cday_data as $key => $val) {
						$room_prefix = idget_data($tbL56,$val['roomid'],'roomprefix');
						$room_number = idget_data($tbL56,$val['roomid'],'roomnumber');
						?>
							<div class="box-border-thick xsml-rounded-button pads15 bottom-push-15">
								<h3 class="large nobold default-text-font-bold">ROOM <?php echo $room_prefix.$room_number; ?></h3>
								<?php
									$additionalQuery = "";
									$gday_query = array("roomid"=>$val['roomid'],"booking_number"=>$booking_number,"charge"=>"yes","deletedata"=>0);
									$gday_data = mysqli_data_fetch($tbL134,'id,day,weekday,room_amount,discount_amount,tax_amount,consumption_tax_amount,service_charge,occupancy_charges,extrabed_charges',$gday_query,'array');

									if(is_array($gday_data)) {
										foreach($gday_data as $xkey => $xval) {
											?>
												<span class="float-right"><input type="checkbox" name="days[]" value="<?php echo $xval['id']; ?>"></span>
												<h4 class="xlarge nobold royal-blue-font bottom-pull-5">Day <?php echo $xval['day'].': '.ucfirst($xval['weekday']); ?></h4>
												<h4 class="xlarge nobold black-font bottom-pull-7">Room Price: <?php echo number_format($xval['room_amount']); ?>, Discount: <?php echo number_format($xval['discount_amount']); ?>, VAT: <?php echo number_format($xval['tax_amount']); ?>, Consumption Tax: <?php echo number_format($xval['consumption_tax_amount']); ?>, Service Charge: <?php echo number_format($xval['service_charge']); ?>..</h4>
											<?php
										}
									}
								?>
							</div>
						<?php
					}
				}
			?>
		</div>
		<input type="hidden" name="wgtxuserid" id="wgtxuserid">
		<div id="fbutton2" class="top-pull-30 alignct">
			<input type="hidden" name="pstbooking" id="pstbooking" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="wgtag" id="wgtag" value="removedaybooking">
			<small class="block-element bottom-push-20 dark-grey-font">Clicking this button will completely remove the selected day transactions from customer bill</small>
			<input type="submit" name="submitbutton" value="Apply Delete" class="nc-width-80 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button">
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
		</div>
	</form>
</div>


<script>
	
</script>