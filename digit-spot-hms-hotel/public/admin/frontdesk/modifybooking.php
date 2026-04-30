<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$additionalQuery = " ORDER BY name ASC";
	$complimentary = select_dt_fetch('status','Active',$tbL33,'id','name');
	$cspg = select_dt_fetch('status','Active',$tbL58,'id','name');

	if(isset($ths_token) && $ths_token == 'corporate') {
		$typ1a = "ln-display-box float-left right-push-10";
		$typ1b = "ln-display-box float-left top-pull-5 right-push-20";
		$typ2a = "ln-display-box float-left right-push-10";
		$typ2b = "ln-display-box float-left top-pull-5 right-push-20";
		$typ3a = "ln-display-box float-left right-push-10";
		$typ3b = "ln-display-box float-left top-pull-5 right-push-20";
	} elseif(isset($ths_token) && $ths_token == 'individual') {
		$typ1a = "noshow";
		$typ1b = "noshow";
		$typ2a = "ln-display-box float-left right-push-10";
		$typ2b = "ln-display-box float-left top-pull-5 right-push-20";
		$typ3a = "ln-display-box float-left right-push-10";
		$typ3b = "ln-display-box float-left top-pull-5 right-push-20";
	} elseif(isset($ths_token) && $ths_token == 'complimentary') {
		$typ1a = "ln-display-box float-left right-push-10";
		$typ1b = "ln-display-box float-left top-pull-5 right-push-20";
		$typ2a = "ln-display-box float-left right-push-10";
		$typ2b = "ln-display-box float-left top-pull-5 right-push-20";
		$typ3a = "noshow";
		$typ3b = "noshow";
	}

?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Modify Booking Type')" id="authenform">
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
	<form action="" method="post" onsubmit="" id="fbooking">
		<div class="block-element">
			<h3 class="large nobold steel-blue-font default-text-font-bold">Select Booking Type</h3>
			<span class="ln-display-box float-left">
				<div class="<?php echo $typ1a; ?>">
					<input type="radio" name="type-1" id="type-1" value="Individual" class="radio-option-custom" onclick="biller(1)">
				</div>
				<div class="<?php echo $typ1b; ?>">
					<small>Individual</small>
				</div>
				<div class="<?php echo $typ2a; ?>">
					<input type="radio" name="type-2" id="type-2" value="Group" class="radio-option-custom" onclick="biller(2)">
				</div>
				<div class="<?php echo $typ2b; ?>">
					<small>Corporate/Spl. Guests</small>
				</div>
				<!--<div class="ln-display-box float-left right-push-10">
					<input type="radio" name="type-3" id="type-3" value="Agent" class="radio-option-custom" onclick="biller(3)">
				</div>
				<div class="ln-display-box float-left top-pull-5 right-push-20">
					<small>Agent</small>
				</div>-->
				<div class="<?php echo $typ3a; ?>">
					<input type="radio" name="type-4" id="type-4" value="Complimentary" class="radio-option-custom" onclick="biller(4)">
				</div>
				<div class="<?php echo $typ3b; ?>">
					<small>Complimentary</small>
				</div>
				<div class="block-element new-line-space">
				</div>
			</span>
			<span class="block-element new-line-space">
			</span>
			<input type="hidden" name="customer-type" id="customer-type">
		</div>
		<div id="biller" class="block-element top-push-10">
			<div id="biller-type-1" class="noshow">
				<span class="ln-display-box float-left nc-width-50 right-push-20 top-pull-20">
					<small class="dark-grey-font">Complimentary <b class="mbri-right left-push-5"></b></small>
				</span>
				<span class="ln-display-box float-left nc-width-40 top-pull-15">
					<select name="complimentary" id="complimentary">
						<option value="" selected="selected">Choose</option>
						<?php echo $complimentary; ?>
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div id="biller-type-2" class="noshow">
				<span class="ln-display-box float-left nc-width-50 right-push-20 top-pull-20">
					<small class="dark-grey-font">Corporate / Spl. Guests <b class="mbri-right left-push-5"></b></small>
				</span>
				<span class="ln-display-box float-left nc-width-40 top-pull-15">
					<select name="cspg" id="cspg">
						<option value="" selected="selected">Choose</option>
						<?php echo $cspg; ?>
					</select>			
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div id="biller-type-3" class="noshow">
				<span class="ln-display-box float-left nc-width-50 right-push-20 top-pull-20">
					<small class="dark-grey-font">Active Agents <b class="mbri-right left-push-5"></b></small>
				</span>
				<span class="ln-display-box float-left nc-width-30 top-pull-15">
					<select name="agent" id="agent">
						<option value="" selected="selected">Choose</option>
						<?php //echo $agent; ?>
					</select>			
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
		</div>

		<div id="not-group" class="noshow top-push-20">
			<h4 class="large">Payment pay by?</h4>
			<input type="radio" name="payment-by" value="Group Owner" checked="checked"> Group Owner &nbsp; <input type="radio" name="payment-by" value="Guest"> Guests
		</div>
		<div id="for-group" class="noshow top-push-20">
			<h4 class="large">Payment pay by?</h4>
			<input type="radio" name="payment-by2" value="Corporate" checked="checked"> Corporate &nbsp; <input type="radio" name="payment-by2" value="Guest"> Guests
		</div>

		<input type="hidden" name="wgtxuserid" id="wgtxuserid">

		<div id="fbutton2" class="top-pull-30 alignct noshow">
			<small class="block-element bottom-push-20 dark-grey-font">Clicking the button will change all information related to this booking</small>
			<input type="button" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button" onclick="bkmod()">
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
		</div>
	</form>
</div>









<script>

	function bkmod() {
		
		var js_authen,authen,bookingString,th_booking_type,th_biller,th_payby,th_user,th_remark,th_booking;

		objHidden('fbutton2');
		writeObjheader('fmessage2','<div class="loading"></div>');

		th_booking_type = document.getElementById('customer-type').value;
		th_user = document.getElementById('wgtxuserid').value;
		
		if(th_booking_type == 'individual') {
			th_biller = 0;
			var wgtradio = document.getElementById('fbooking').elements['payment-by'];
			var wgtradio_numbr = wgtradio.length;
			for(var i=0; i < wgtradio_numbr; i++) { if(wgtradio[i].checked) { th_payby = wgtradio[i].value; break; } }
		} else if(th_booking_type == 'corporate') {
			th_biller = document.getElementById('cspg').value;
			var wgtradio = document.getElementById('fbooking').elements['payment-by2'];
			var wgtradio_numbr = wgtradio.length;
			for(var i=0; i < wgtradio_numbr; i++) { if(wgtradio[i].checked) { th_payby = wgtradio[i].value; break; } }
		} else if(th_booking_type == 'agent') {
			th_biller = document.getElementById('agent').value;
			th_payby = "Guest";
		} else if(th_booking_type == 'complimentary') {
			th_biller = document.getElementById('complimentary').value;
			th_payby = "na";
		}

		authen = sessionStorage.getItem('authen');
		js_authen = JSON.parse(authen);
		th_booking = js_authen.wgtkey;
		th_remark = js_authen.wgtremark;

		bookingString = {
			"bookingnumber": th_booking,
			"remarks": th_remark,
			"userid": th_user,
			"bookingtype": [th_booking_type,th_biller,th_payby]
		};

		var postdatarequest = JSON.stringify(bookingString);
		var xhr,params,url,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		params = "kyw=modifybooking&postdatarequest="+postdatarequest;
		url = filePath+"public/admin/frontdesk/postbooking.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					console.log(xhr.responseText);
					ajaxresult = JSON.parse(xhr.responseText);
					if(ajaxresult.success && ajaxresult.success == 200) {
						var rparam = ajaxresult.param, rframe = ajaxresult.frame;
						parent.document.getElementById('workspace').scrollTop = 0;
						writeObjheader('utask','<h3 class="large nobold alignct">Booking has been modified successfully. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
						parent.document.getElementById('fmodalwin').className = 'white-theme xsml-rounded-button cs-width-500 cs-height-150 motion noscroll';
						
						setTimeout(function() {
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
	


	function biller(b) {
		if(b == 1) {
			htmlpassval('individual','customer-type');
			objHidden('biller-type-1');
			objHidden('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-default-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('not-group');
			objHidden('for-group');
		} else if(b == 2) {
			htmlpassval('corporate','customer-type');
			objHidden('biller-type-1');
			objDisplay('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-default-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('for-group');
			objHidden('not-group');
		} else if(b == 3) {
			htmlpassval('agent','customer-type');
			objHidden('biller-type-1');
			objHidden('biller-type-2');
			objDisplay('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-default-custom');
			chgclass('type-4','radio-option-custom');
			objDisplay('not-group');
			objHidden('for-group');
		} else if(b == 4) {
			htmlpassval('complimentary','customer-type');
			objDisplay('biller-type-1');
			objHidden('biller-type-2');
			objHidden('biller-type-3');
			chgclass('type-1','radio-option-custom');
			chgclass('type-2','radio-option-custom');
			chgclass('type-3','radio-option-custom');
			chgclass('type-4','radio-option-default-custom');
			objHidden('not-group');
			objHidden('for-group');
		}

		objDisplay('fbutton2');
	}

</script>