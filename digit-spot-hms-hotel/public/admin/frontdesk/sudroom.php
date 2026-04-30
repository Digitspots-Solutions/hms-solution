<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;
?>

<h3 class="large nobold default-text-font-bold">Select room deal option to continue</h3>

<span class="block-element bottom-push-10 ft-sml-size">
	<input type="radio" name="roomdealoption" value="swap" class="float-left right-push-20" onclick="roomdeal(this.value)"> Swap Room
</span>
<span class="block-element bottom-push-30 ft-sml-size">
	<input type="radio" name="roomdealoption" value="upgrade-and-downgrade" class="float-left right-push-20" onclick="roomdeal(this.value)"> Upgrade / Downgrade Room
</span>



<?php

	if(isset($_GET['dealopt']) && $_GET['dealopt'] == 'swap') {
		include "swaproom.php";
	} else if(isset($_GET['dealopt']) && $_GET['dealopt'] == 'upgrade-and-downgrade') {
		include "upgradeandowngraderoom.php";
	}

?>

<script>
	
	function roomdeal(str) {
		var curl = window.location.href;
		window.location = curl+"&dealopt="+str;
	}

</script>
