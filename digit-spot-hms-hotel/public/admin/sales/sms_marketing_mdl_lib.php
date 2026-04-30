<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Choose the category sms you would like to send and follow on-screen instructions.
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>
	
	function setCharAt(str,index,chr) {
	    if(index > str.length-1) return str;
	    return str.substr(0,index) + chr + str.substr(index+1);
	}

	function counter_xter(obj,str,max) {
		var text_box = document.getElementById(obj);
		var total_xter = document.getElementById(str);

		var typed_xters = (text_box.value).length;
		var left_xter = max - eval(typed_xters);
		total_xter.value = left_xter;

		if(left_xter <= 0) {
			
			var fix_text = (text_box.value).substr(0,max);
			text_box.value = fix_text;

			typed_xters = (text_box.value).length;
			left_xter = max - eval(typed_xters);
			total_xter.value = left_xter;
		}
	}


	function switch_recipient() {
		var recp = document.getElementById('fieldset1').value;
		if(recp == 'General') {
			document.getElementById('general-box-number').className = 'block-element';
			document.getElementById('department-box-number').className = 'noshow';
			document.getElementById('inhouse-guest-box-number').className = 'noshow';

			document.getElementById('general-numbers').required = true;
			document.getElementById('departmental-numbers').required = false;
			document.getElementById('start-date').required = false;
			document.getElementById('end-date').required = false;
		} else if(recp == 'Department') {
			document.getElementById('general-box-number').className = 'noshow';
			document.getElementById('department-box-number').className = 'block-element';
			document.getElementById('inhouse-guest-box-number').className = 'noshow';

			document.getElementById('general-numbers').required = false;
			document.getElementById('departmental-numbers').required = true;
			document.getElementById('start-date').required = false;
			document.getElementById('end-date').required = false;
		} else if(recp == 'Inhouse Guest') {
			document.getElementById('general-box-number').className = 'noshow';
			document.getElementById('department-box-number').className = 'noshow';
			document.getElementById('inhouse-guest-box-number').className = 'block-element';

			document.getElementById('general-numbers').required = false;
			document.getElementById('departmental-numbers').required = false;
			document.getElementById('start-date').required = true;
			document.getElementById('end-date').required = true;
		}
	}

</script>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------


	if(isset($_POST['submitbutton']))
	{
		//send sms to sender
		$sms_sender = _SHORT_NAME;
		$sms_message = $_POST['fieldset2']." - "._LONG_NAME;

		if(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'General') {
			$phone_numbers = explode(',',$_POST['general-numbers']);
		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'Department') {
			
			$additionalQuery="";
			$dept_query = array("department"=>$_POST['departmental-numbers']);
			$get_user_numbers = mysqli_data_fetch($tbL7,'mobile',$dept_query,'array');
			
			$user_numbr_list = '';
			
			if(is_array($get_user_numbers)) {
				foreach ($get_user_numbers as $numbr_key => $numbr_value) {
					$user_numbr_list .= trim($numbr_value['mobile']).',';
				}
			}

			$phone_numbers = explode(',',$user_numbr_list);

		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'Inhouse Guest') {
			
			$additionalQuery=" datelogged BETWEEN '".$_POST['start-date']."' AND '".$_POST['end-date']."'";
			$get_guest_numbers = mysqli_data_fetch($tbL102,'mobile','','array');
			
			$guest_numbr_list = '';
			
			if(is_array($get_guest_numbers)) {
				foreach ($get_guest_numbers as $numbr_key => $numbr_value) {
					$guest_numbr_list .= trim($numbr_value['mobile']).',';
				}
			}

			$phone_numbers = explode(',',$guest_numbr_list);
		}

		$issent = 0;

		foreach($phone_numbers as $recipient) {
			if($recipient != '') {
				$receiver_mobile_number = serializePhone($recipient);
				$rmsg = sendSMS($receiver_mobile_number,$sms_sender,$sms_message);
				$issent += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($issent) && $issent >= 1) {
			$post_result .= '<span class="red-font">Sms was sent to selected recipient successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$usr = select_dt_fetch('status','Active',$tbL12,'id','department');

?>


	<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
		<div class="nc-width-50">
			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
				<div class="bottom-push-20 alignlt">
					<h3 class="nomargin">Sending Sms</h3>
				</div>
				<span class="block-element bottom-push-10">
					<small class="block-element bottom-push-3 left-pull-5 alignlt">Recipient</small>
					<select name="fieldset1" id="fieldset1" onchange="switch_recipient()" required="required">
						<option>General</option>
						<option>Department</option>
						<option>Inhouse Guest</option>
					</select>
					<div id="general-box-number" class="block-element">
						<textarea name="general-numbers" id="general-numbers" placeholder="Type or paste numbers here. Separate them with commas" required="required"></textarea><small class="block-element left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this sms to these numbers</small>
					</div>
					<div id="department-box-number" class="noshow">
						<select name="departmental-numbers" id="departmental-numbers">
							<option value="" selected="selected">Choose Department</option>
							<?php echo $usr; ?>
						</select>
						<small class="block-element top-push-5 left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this sms to people in this department</small>
					</div>
					<div id="inhouse-guest-box-number" class="noshow">
						<span class="ln-display-box float-left nc-width-45 top-push-5">
							<small class="block-element bottom-push-5 left-pull-5 alignlt dark-grey-font ft-xxsml-size">Start Date</small>
							<input type="date" name="start-date" id="start-date">
						</span>
						<span class="ln-display-box float-right nc-width-45 top-push-5">
							<small class="block-element bottom-push-5 left-pull-5 alignlt dark-grey-font ft-xxsml-size">End Date</small>
							<input type="date" name="end-date" id="end-date">
						</span>
						<span class="block-element new-line-space">
						</span>
						<small class="block-element top-push-5 left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this sms to guests that appear within the selected period</small>
					</div>
				</span>
				<span class="block-element bottom-push-10">
					<small class="block-element bottom-push-3 left-pull-5 alignlt">Message</small>
					<textarea name="fieldset2" id="fieldset2" placeholder="Type message here" onkeyup="counter_xter('fieldset2','max-xter',350)" onblur="counter_xter('fieldset2','max-xter',350)"></textarea>
					<small class="block-element bottom-push-20 left-pull-5 alignlt dark-grey-font ft-xxsml-size">maximum of 350 charaters allowed</small>
					<div class="ln-display-box float-left nc-width-10 right-push-20 white-theme obj-light-shadow">
						<input type="text" id="max-xter" value="350" readonly="readonly">
					</div>
					<div class="ln-display-box float-left top-pull-10">
						<small class="red-font">Charaters remaining</small>
					</div>
					<div class="block-element new-line-space">
					</div>
				</span>
				

				<br><br>
				
				<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
			</form>
		</div>
	</div>