<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	include "booking_tokens.php";

	$tx1 = $wgt_total_room_consumption;
	$tx2 = $wgt_total_room_servicecharge;
	$tx3 = $wgt_total_room_tax;
?>

<!--<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Exclude Hotel Tax Charges')" id="authenform">
		<h3 class="large nobold default-text-font-bold">User Authentication</h3><br>
		<div id="fmessage" class="bottom-push-7" align="center">
		</div>
		<span class="block-element">
			<input type="hidden" name="wgtkey" id="wgtkey" value="<?php //echo $booking_number; ?>">
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
</div>-->
<div id="utask" class="top-push-50">
	<form action="" method="post" onsubmit="" id="fbooking">
		<h3 class="large nobold default-text-font-bold">Select the tax to exclude</h3>
		<div class="bottom-push-10 cs-height-120 grey-1-theme pads15 y-scroll">
			<?php
				
				$disabled=""; $color=""; $ptc="";
				$totalcheck = 0; $totaldisabled = 0;

				foreach($tax_charges as $key => $value) {

					$totalcheck += 1;

					if($key == 1 && $tx1 == 0) { $disabled=" disabled"; $color=" dark-grey-font"; $totaldisabled += 1; }
					elseif($key == 2 && $tx2 == 0) { $disabled=" disabled"; $color=" dark-grey-font"; $totaldisabled += 1; }
					elseif($key == 3 && $tx3 == 0) { $disabled=" disabled"; $color=" dark-grey-font"; $totaldisabled += 1; }

					if($key == 1) { $ptc = $gh_get_consumption_tax.'%'; }
					elseif($key == 2) { $ptc = $gh_get_service_charge.'%'; }
					elseif($key == 3) { $ptc = $gh_get_vat.'%'; }
				
					?>
						<div class="block-element bottom-push-7">
							<span class="ln-display-box float-left nc-width-10">
								<input type="checkbox" name="taxes" value="<?php echo $key; ?>"<?php echo $disabled; ?>>
							</span>
							<span class="ln-display-box float-left nc-width-90 ft-sml-size<?php echo $color; ?>">
								<?php echo $value; ?> (<?php echo $ptc; ?>)
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
					<?php

					$disabled=""; $color="";
				}

				if($totalcheck == $totaldisabled) { $noshow=" noshow"; $readonly=" readonly"; $msg="There are no option for taxes"; }
				else { $noshow=""; $readonly=""; $msg=""; }
				
			?>
			<input type="hidden" id="totalcheck" value="<?php echo $totalcheck; ?>">
		</div>
		
		<input type="hidden" name="wgtxuserid" id="wgtxuserid">

		<div id="fbutton2" class="top-pull-30 alignct<?php echo $noshow; ?>">
			<small class="block-element bottom-push-20 dark-grey-font">Clicking the button will remove selected charges from guest bill</small>
			<input type="button" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button" onclick="bkmod()">
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
			<small class="light-red-font"><?php echo $msg; ?></small>
		</div>
	</form>
</div>







<script>
	
	function bkmod() {
		
		var js_authen,authen,bookingString,th_user,th_booking,th_taxes,th_discount,th_checker;

		th_user = document.getElementById('wgtxuserid').value;
		th_checker = document.getElementById('totalcheck').value;

		objHidden('fbutton2');
		writeObjheader('fmessage2','<div class="loading"></div>');
		
		var fbk = document.getElementById('fbooking');
		var wgtcheckbox = fbk.elements['taxes'];
		
		if(th_checker == 1) {
			th_taxes = wgtcheckbox.value;
		} else {
			var numbr = wgtcheckbox.length; th_taxes = '';
			for(var i=0; i < numbr; i++) { if(wgtcheckbox[i].checked) { if((eval(i) + 1) < numbr) { th_taxes +=wgtcheckbox[i].value+','; } else { th_taxes +=wgtcheckbox[i].value; } } }
		}
		
		authen = sessionStorage.getItem('authen');
		js_authen = JSON.parse(authen);
		th_booking = js_authen.wgtkey;
		
		bookingString = {
			"bookingnumber": th_booking,
			"userid": th_user,
			"taxes": th_taxes
		};
		
		var postdatarequest = JSON.stringify(bookingString);
		var xhr,params,url,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		params = "kyw=excludetaxes&postdatarequest="+postdatarequest;
		url = filePath+"public/admin/frontdesk/postbooking.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					//console.log(xhr.responseText);
					ajaxresult = JSON.parse(xhr.responseText);
					if(ajaxresult.success && ajaxresult.success == 200) {
						var rparam = ajaxresult.param, rframe = ajaxresult.frame;
						parent.document.getElementById('workspace').scrollTop = 0;
						writeObjheader('utask','<h3 class="large nobold alignct">Selected charges are now excluded from bill. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
						parent.document.getElementById('fmodalwin').className = 'white-theme xsml-rounded-button cs-width-500 cs-height-150 motion noscroll';
						
						setTimeout(function() {
							//'parent.'+rframe+'.location.reload()';
							'parent.'+rframe+'.location.href = '+filePath+'public/admin/workspace.php?logs=reservations&token='+rparam;
							parent.document.getElementById('clsframe').click();
						},3000);
					} else {
						objDisplay('fbutton2');
						writeObjheader('fmessage2','<small class="block-element bottom-push-20 light-red-font">'+ajaxresult.status+'</small>');
					}
				}
			}
		};

		xhr.open('POST', url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
		
	}

</script>