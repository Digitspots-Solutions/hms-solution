<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	$wgt_isweekend_fares = idget_fdata($tbL130,'booking_number',$booking_number,'isweekend_fares');
	$days = array(); //$roomtypes = array();

	if(isset($wgt_isweekend_fares) && $wgt_isweekend_fares == 'No') {
		$isweek_status = "Yes";
		$header = "Please select weekend days you would like to apply?";
		
		$wkday_query = array("booking_number"=>$booking_number,"ischarged"=>0,"deletedata"=>0);
		$wkday_data = mysqli_data_fetch($tbL134,'id,weekday,roomid',$wkday_query,'array');
		
		if(is_array($wkday_data)) {
			foreach($wkday_data as $key => $value) {
				if($value['weekday'] == 'friday' || $value['weekday'] == 'saturday' || $value['weekday'] == 'sunday') {
					array_push($days, $value['id']);
					//array_push($roomtypes, $value['room_type_id']);
				}
			}

			if(is_array($days) && count($days) >= 1) {
				$message = "";
				$status = 1;
				$noshow = " block-element";
				$noshow2 = "block-element";
				$noshow3 = "noshow";
			} else {
				$message = "There are no weekend days within this booking";
				$status = 0;
				$noshow = " noshow";
				$noshow2 = "noshow";
				$noshow3 = "noshow";
			}

		} else {
			$message = "Error processing request! Rooms already charged and posted";
			$status = 0;
			$noshow = " noshow";
			$noshow2 = "noshow";
			$noshow3 = "noshow";
		}

	} else {
		
		$wkday_query = array("booking_number"=>$booking_number,"wkf"=>7,"deletedata"=>0);
		$wkday_data = mysqli_data_fetch($tbL134,'id,weekday,roomid',$wkday_query,'array');
		
		if(is_array($wkday_data)) {
			foreach($wkday_data as $key => $value) {
				array_push($days, $value['id']);
			}
		}

		$isweek_status = "No";
		$header = "Confirm Request?";
		$message = "The following days are previously applied for weekend fares. Do you want to disable them?";
		$status = 0;
		$noshow = " block-element";
		$noshow2 = "noshow";
		$noshow3 = "block-element";
	}

?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Apply Weekend Fares')" id="authenform">
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
		<h3 class="large nobold default-text-font-bold"><?php echo $header; ?></h3>
		<div class="bottom-push-10 cs-height-150 grey-1-theme pads15 y-scroll">
			<small class="block-element dark-grey-font bottom-push-5"><?php echo $message; ?></small>

			<?php
				if(isset($status) && $status == 1) {

					$totalcheck = 0;
					
					for($i=0; $i<count($days); $i++) {
						
						$totalcheck += 1;

						$wkday = idget_data($tbL134,$days[$i],'weekday');
						$theroom = idget_data($tbL134,$days[$i],'roomid');
						$bill_date = idget_data($tbL134,$days[$i],'bill_date');
						$print_date = write_dateF(5,$bill_date);

						$room_prefix = idget_data($tbL56,$theroom,'roomprefix');
						$room_number = idget_data($tbL56,$theroom,'roomnumber');

						?>
							<div class="block-element bottom-push-7">
								<span class="ln-display-box float-left nc-width-10">
									<input type="checkbox" name="udays" value="<?php echo $days[$i]; ?>">
								</span>
								<span class="ln-display-box float-left nc-width-90 ft-sml-size">
									<?php echo $room_prefix.$room_number.": ".ucfirst($wkday).' - '.$print_date; ?>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
						<?php

						$room_prefix = ""; $room_number = "";
						$wkday = ""; $theroom = "";
						$bill_date = "";
					}
				} else {
					if(isset($isweek_status) && $isweek_status == 'No') {

						for($i=0; $i<count($days); $i++) {
						
							$wkday = idget_data($tbL134,$days[$i],'weekday');
							$theroom = idget_data($tbL134,$days[$i],'roomid');
							$bill_date = idget_data($tbL134,$days[$i],'bill_date');
							$print_date = write_dateF(5,$bill_date);

							$room_prefix = idget_data($tbL56,$theroom,'roomprefix');
							$room_number = idget_data($tbL56,$theroom,'roomnumber');

							?>
								<div class="block-element top-push-5 bottom-push-3">
									<span class="ln-display-box float-left nc-width-10">
										<input type="checkbox" name="udays" value="<?php echo $days[$i]; ?>" checked disabled>
									</span>
									<span class="ln-display-box float-left nc-width-90 ft-sml-size">
										<?php echo $room_prefix.$room_number.": ".ucfirst($wkday).' - '.$print_date; ?>
									</span>
									<span class="block-element new-line-space">
									</span>
								</div>
							<?php

							$room_prefix = ""; $room_number = "";
							$wkday = ""; $theroom = "";
							$bill_date = "";
						}
					}
				}
			?>
			<input type="hidden" id="totalcheck" value="<?php echo $totalcheck; ?>">
		</div>
		<input type="hidden" name="wgtweekstatus" id="wgtweekstatus" value="<?php echo $isweek_status; ?>">
		<input type="hidden" name="wgtxuserid" id="wgtxuserid">
		<div id="fbutton2" class="top-pull-30 alignct<?php echo $noshow; ?>">
			<div id="f1" class="<?php echo $noshow2; ?>">
				<small class="block-element bottom-push-20 dark-grey-font">Clicking this button will apply weekend fares accordingly</small>
				<input type="button" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button" onclick="bkmod()">
			</div>
			<div id="f2" class="<?php echo $noshow3; ?>">
				<small class="block-element bottom-push-20 dark-grey-font">Clicking this button will return the normal fare</small>
				<input type="button" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button" onclick="nbkmod()">
			</div>
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
		</div>
	</form>
</div>


<script>
	
	function bkmod() {
		
		var js_authen,authen,bookingString,th_user,th_booking,th_days,th_wkfstatus,th_checker;

		th_user = document.getElementById('wgtxuserid').value;
		th_wkfstatus = document.getElementById('wgtweekstatus').value;
		th_checker = document.getElementById('totalcheck').value;

		//objHidden('fbutton2');
		chgclass('fbutton2','top-pull-30 alignct noshow');
		writeObjheader('fmessage2','<div class="loading"></div>');
		
		var fbk = document.getElementById('fbooking');
		var wgtcheckbox = fbk.elements['udays'];
		
		if(th_checker == 1) {
			th_days = wgtcheckbox.value;
		} else {
			var numbr = wgtcheckbox.length; th_days = '';
			for(var i=0; i < numbr; i++) { if(wgtcheckbox[i].checked) { if((eval(i) + 1) < numbr) { th_days +=wgtcheckbox[i].value+','; } else { th_days +=wgtcheckbox[i].value; } } }
		}
		
		authen = sessionStorage.getItem('authen');
		js_authen = JSON.parse(authen);
		th_booking = js_authen.wgtkey;
		
		bookingString = {
			"bookingnumber": th_booking,
			"userid": th_user,
			"days": th_days,
			"wkfstatus": th_wkfstatus
		};

		
		var postdatarequest = JSON.stringify(bookingString);
		var xhr,params,url,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		params = "kyw=weekendfares&postdatarequest="+postdatarequest;
		url = filePath+"public/admin/frontdesk/postbooking.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					console.log(xhr.responseText);
					ajaxresult = JSON.parse(xhr.responseText);
					if(ajaxresult.success && ajaxresult.success == 200) {
						var rparam = ajaxresult.param, rframe = ajaxresult.frame;
						parent.document.getElementById('workspace').scrollTop = 0;
						writeObjheader('utask','<h3 class="large nobold alignct">Weekend fares has been applied successfully. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
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



	function nbkmod() {
		
		var js_authen,authen,bookingString,th_user,th_booking,th_days,th_wkfstatus,th_checker;

		th_user = document.getElementById('wgtxuserid').value;
		th_wkfstatus = document.getElementById('wgtweekstatus').value;
	
		authen = sessionStorage.getItem('authen');
		js_authen = JSON.parse(authen);
		th_booking = js_authen.wgtkey;
		
		bookingString = {
			"bookingnumber": th_booking,
			"userid": th_user,
			"wkfstatus": th_wkfstatus
		};

		var postdatarequest = JSON.stringify(bookingString);
		var xhr,params,url,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		params = "kyw=disableweekendfares&postdatarequest="+postdatarequest;
		url = filePath+"public/admin/frontdesk/postbooking.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					console.log(xhr.responseText);
					ajaxresult = JSON.parse(xhr.responseText);
					if(ajaxresult.success && ajaxresult.success == 200) {
						var rparam = ajaxresult.param, rframe = ajaxresult.frame;
						parent.document.getElementById('workspace').scrollTop = 0;
						writeObjheader('utask','<h3 class="large nobold alignct">Weekend fares has been disabled successfully. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
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