<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;
?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Disable Late Checkout Charges')" id="authenform">
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
		<input type="hidden" name="wgtstatus" id="wgtstatus" value="<?php echo $ths_token; ?>">
		<input type="hidden" name="wgtxuserid" id="wgtxuserid">

		<div id="fbutton2" class="top-pull-30 alignct<?php echo $noshow; ?>">
			<h3 class="large nobold dark-grey-font"><?php if(isset($ths_token) && $ths_token == 'enable') { ?>Enabling late checkout charges. This will change checkout charges status, click <u>apply</u> button to continue<?php } else { ?>Disabling late checkout charges. This will change checkout charges status, click <u>apply</u> button to continue<?php } ?></h3><br>
			<input type="button" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button" onclick="bkmod()">
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
		</div>
	</form>
</div>



<script>
	
	function bkmod() {
		
		var js_authen,authen,bookingString,th_user,th_checkout_status;

		th_user = document.getElementById('wgtxuserid').value;
		th_checkout_status = document.getElementById('wgtstatus').value;
		
		objHidden('fbutton2');
		writeObjheader('fmessage2','<div class="loading"></div>');
		
		authen = sessionStorage.getItem('authen');
		js_authen = JSON.parse(authen);
		th_booking = js_authen.wgtkey;
		
		bookingString = {
			"bookingnumber": th_booking,
			"userid": th_user,
			"checkoutstatus": th_checkout_status
		};
		
		var postdatarequest = JSON.stringify(bookingString);
		var xhr,params,url,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		params = "kyw=checkoutcharges&postdatarequest="+postdatarequest;
		url = filePath+"public/admin/frontdesk/postbooking.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					console.log(xhr.responseText);
					ajaxresult = JSON.parse(xhr.responseText);
					if(ajaxresult.success && ajaxresult.success == 200) {
						var rparam = ajaxresult.param, rframe = ajaxresult.frame;
						parent.document.getElementById('workspace').scrollTop = 0;
						writeObjheader('utask','<h3 class="large nobold alignct">Late checkout charges status changed successfully. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
						parent.document.getElementById('fmodalwin').className = 'white-theme xsml-rounded-button cs-width-500 cs-height-150 motion noscroll';
						
						setTimeout(function() {
							//'parent.'+rframe+'.location.reload()';
							'parent.'+rframe+'.location.href = '+filePath+'public/admin/workspace.php?logs=reservations&token='+rparam;
							parent.document.getElementById('clsframe').click();
						},3000);
					}
				}
			}
		};

		xhr.open('POST', url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}

</script>