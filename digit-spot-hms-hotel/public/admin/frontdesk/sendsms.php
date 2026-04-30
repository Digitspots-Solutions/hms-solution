<?php
	
	$booking_number = $ftoken;
	$ths_token = $stoken;


	include "post_booking_tokens.php";
	include "booking_tokens.php";

?>
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<h3 class="large nobold default-text-font-bold nomargin"><?php echo $guest_account_name; ?></h3>
		<h3 class="large nobold">Primary Guest</h3><br>

		<?php
			//get preset sms list
			$get_prs = mysqli_data_fetch($tbL150,'id,msg','','array');
			if(is_array($get_prs)) {
				foreach ($get_prs as $key => $val) {
					?>
						<div class="ln-display-box float-left box-border-thick xsml-rounded-button pads10 ft-sml-size right-push-10 bottom-push-10 anchor" onclick="dropmsg('<?php echo $val['msg']; ?>')"><?php echo $val['msg']; ?></div>
					<?php
				}
			}
		?>
		<div class="block-element new-line-space">
		</div>
		<div class="top-push-20 box-border-thick-top top-pull-10">
			<textarea name="message" id="message" class="no-back-black notextborder" placeholder="Select message to drop here?" readonly required></textarea>
		</div>
		<div class="block-element top-pull-50 alignct">
			<input type="hidden" name="wgtfield5" id="wgtfield5" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="wgtag" id="wgtag" value="sendsmstoguest">
			<input type="submit" name="submitbutton" value="Send Message" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 dark-black-white-state rounded-button default-text-font-bold">
		</div>
	</div>
</form>

<script>

	function dropmsg(msg) {
		document.getElementById('message').value = msg;
	}

</script>