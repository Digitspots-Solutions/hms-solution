<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	if(isset($ths_token) && $ths_token >= 1) { $room_query = array("booking_number"=>$booking_number,"roomid"=>$ths_token); }
	else { $room_query = array("booking_number"=>$booking_number); }

	$room_sql = mysqli_data_fetch($tbL127,'id,room_type_id,roomid,isdiscount',$room_query,'array');
?>

<div id="ufm">
	<form action="" method="post" autocomplete="off" onsubmit="getUserAuthen(event,'Frontdesk','Apply Discount')" id="authenform">
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
		<h3 class="large nobold default-text-font-bold">Select room for discount</h3>
		<div class="bottom-push-10 cs-height-80 grey-1-theme pads15 y-scroll">
			<?php
				if(is_array($room_sql)) {
					
					$room_type_id=""; $room_type=""; $block_id=""; $block_name=""; $room_prefix=""; $room_number="";
					$disabled=""; $color="";

					$totalcheck = 0; $totaldisabled = 0;

					foreach($room_sql as $key => $value) {

						$totalcheck += 1;

						//$room_type_id = idget_fdata($tbL56,'id',$value['roomid'],'room_type_id');
						$room_type_id = $value['room_type_id'];
						$room_type = idget_data($tbL52,$room_type_id,'name');

						$block_id = idget_fdata($tbL56,'id',$value['roomid'],'blockid');
						$block_name = idget_data($tbL49,$block_id,'name');

						$room_prefix = idget_data($tbL56,$value['roomid'],'roomprefix');
						$room_number = idget_data($tbL56,$value['roomid'],'roomnumber');

						/*if($value['isdiscount'] == 1) { $disabled=" disabled"; $color=" dark-grey-font"; $totaldisabled += 1; }
						else { $disabled=""; $color=""; $totaldisabled = 0; }*/

						$disabled=""; $color=""; $totaldisabled = 0;

						?>
							<div class="block-element bottom-push-7">
								<span class="ln-display-box float-left nc-width-10">
									<input type="checkbox" name="rooms" value="<?php echo $value['room_type_id'].'-'.$value['roomid']; ?>"<?php echo $disabled; ?>>
								</span>
								<span class="ln-display-box float-left nc-width-90 ft-sml-size<?php echo $color; ?>">
									<?php echo $room_type.' - '.$room_prefix.$room_number; ?>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
						<?php
					}

					if($totalcheck == $totaldisabled) { $noshow=" noshow"; $readonly=" readonly"; $msg="There are no more rooms to process"; } else { $noshow=""; $readonly=""; $msg=""; }
				}

			?>
			<input type="hidden" id="totalcheck" value="<?php echo $totalcheck; ?>">
		</div>
		<div class="xform bottom-push-10">
			<small class="block-element bottom-push-3 dark-grey-font default-text-font-bold">Apply Discount?</small>
			<small class="block-element bottom-push-7 light-red-font">* Only in percentage value e.g 10.5</small>
			<input type="number" name="wgtdiscount" id="wgtdiscount" step="any" placeholder="Click here to enter discount" class="nopads no-back-black"<?php echo $readonly; ?>>
		</div>

		<input type="hidden" name="wgtxuserid" id="wgtxuserid">

		<div id="fbutton2" class="top-pull-30 alignct<?php echo $noshow; ?>">
			<small class="block-element bottom-push-20 dark-grey-font">Clicking the button will apply discount to selected rooms. Only discount within your range is applicable</small>
			<input type="button" value="Apply" class="nc-width-80 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button" onclick="bkmod()">
		</div>
		<div id="fmessage2" class="top-pull-30" align="center">
			<small class="light-red-font"><?php echo $msg; ?></small>
		</div>
	</form>
</div>







<script>
	
	function bkmod() {
		
		var js_authen,authen,bookingString,th_user,th_booking,th_rooms,th_discount,th_checker;

		th_user = document.getElementById('wgtxuserid').value;
		th_discount = document.getElementById('wgtdiscount').value;
		th_checker = document.getElementById('totalcheck').value;

		if(th_discount >= 0) {

			objHidden('fbutton2');
			writeObjheader('fmessage2','<div class="loading"></div>');
			
			var fbk = document.getElementById('fbooking');
			var wgtcheckbox = fbk.elements['rooms'];
			
			if(th_checker == 1) {
				th_rooms = wgtcheckbox.value;
			} else {
				var numbr = wgtcheckbox.length; th_rooms = '';
				for(var i=0; i < numbr; i++) { if(wgtcheckbox[i].checked) { if((eval(i) + 1) < numbr) { th_rooms +=wgtcheckbox[i].value+','; } else { th_rooms +=wgtcheckbox[i].value; } } }
			}
			
			authen = sessionStorage.getItem('authen');
			js_authen = JSON.parse(authen);
			th_booking = js_authen.wgtkey;
			
			bookingString = {
				"bookingnumber": th_booking,
				"userid": th_user,
				"rooms": th_rooms,
				"discount": th_discount
			};
			
			var postdatarequest = JSON.stringify(bookingString);
			var xhr,params,url,ajaxresult;

			if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
			else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

			params = "kyw=applydiscount&postdatarequest="+postdatarequest;
			url = filePath+"public/admin/frontdesk/postbooking.php";
			
			xhr.onreadystatechange=function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						//console.log(xhr.responseText);
						ajaxresult = JSON.parse(xhr.responseText);
						if(ajaxresult.success && ajaxresult.success == 200) {
							var rparam = ajaxresult.param, rframe = ajaxresult.frame;
							parent.document.getElementById('workspace').scrollTop = 0;
							writeObjheader('utask','<h3 class="large nobold alignct">Discount was applied successfully. Please use booking refresh icon to see new effect</h3><h2 class="large nobold default-text-font-bold alignct">Returning screen, wait..</h2>');
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
	}

</script>