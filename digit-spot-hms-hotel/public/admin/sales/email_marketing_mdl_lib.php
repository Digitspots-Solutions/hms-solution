<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Choose the category email you would like to send and follow on-screen instructions.
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
			document.getElementById('general-box-email').className = 'block-element';
			document.getElementById('corporate-box-email').className = 'noshow';
			document.getElementById('inhouse-guest-box-email').className = 'noshow';

			document.getElementById('general-emails').required = true;
			document.getElementById('corporate-emails').required = false;
			document.getElementById('start-date').required = false;
			document.getElementById('end-date').required = false;
		} else if(recp == 'Corporate') {
			document.getElementById('general-box-email').className = 'noshow';
			document.getElementById('corporate-box-email').className = 'block-element';
			document.getElementById('inhouse-guest-box-email').className = 'noshow';

			document.getElementById('general-emails').required = false;
			document.getElementById('corporate-emails').required = true;
			document.getElementById('start-date').required = false;
			document.getElementById('end-date').required = false;
		} else if(recp == 'Inhouse Guest') {
			document.getElementById('general-box-email').className = 'noshow';
			document.getElementById('corporate-box-email').className = 'noshow';
			document.getElementById('inhouse-guest-box-email').className = 'block-element';

			document.getElementById('general-emails').required = false;
			document.getElementById('corporate-emails').required = false;
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
		//send pin to email address
		$logourl = _LOGO_URL;
		$footnote = _LONG_NAME;
		$senderlabel = _LONG_NAME;
		$senderemail = _NOREPLY_EMAIL;
		$mailsubject = ucwords(strtolower($_POST['fieldset2']));
		$mailmessage = nl2br($_POST['fieldset3']);

		if(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'General') {
			$email_to = explode(',',$_POST['general-emails']);
		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'Corporate') {
			$email_to = explode(',',$_POST['corporate-emails']);
		} elseif(isset($_POST['fieldset1']) && $_POST['fieldset1'] == 'Inhouse Guest') {
			
			$additionalQuery=" datelogged BETWEEN '".$_POST['start-date']."' AND '".$_POST['end-date']."'";
			$get_guest_emails = mysqli_data_fetch($tbL102,'emailaddress','','array');
			
			$guest_em_list = '';
			
			if(is_array($get_guest_emails)) {
				foreach ($get_guest_emails as $em_key => $em_value) {
					$guest_em_list .= trim($em_value['email']).',';
				}
			}

			$email_to = explode(',',$guest_em_list);
		}

		$issent = 0;

		foreach($email_to as $recipient) {
			if($recipient != '') {
				mailSender($logourl,$footnote,$senderlabel,$senderemail,$recipient,$mailsubject,$mailmessage);
				$issent += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($issent) && $issent >= 1) {
			$post_result .= '<span class="red-font">Email was sent to selected recipient successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}


	#-----------------------------------------------------------------------------------------------------------------


	//get list of corporate emails

	$additionalQuery="";
	$corp_query = array("deletedata"=>0,"status"=>"Active");
	$get_corp = mysqli_data_fetch($tbL58,'email',$corp_query,'array');
	
	$corp_list = '';
	
	if(is_array($get_corp)) {
		foreach ($get_corp as $corp_key => $corp_value) {
			$corp_list .= trim($corp_value['email']).',';
		}
	}

?>


	<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
		<div class="nc-width-70">
			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
				<div class="bottom-push-20 alignlt">
					<h3 class="nomargin">Sending Email</h3>
				</div>
				<span class="block-element bottom-push-30">
					<small class="block-element bottom-push-3 left-pull-5 alignlt">Recipient</small>
					<select name="fieldset1" id="fieldset1" onchange="switch_recipient()" required="required">
						<option>General</option>
						<option>Corporate</option>
						<option>Inhouse Guest</option>
					</select>
					<div id="general-box-email" class="block-element">
						<textarea name="general-emails" id="general-emails" placeholder="Type or paste emails here. Separate them with commas" required="required"></textarea><small class="block-element left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this mail to these email addresses</small>
					</div>
					<div id="corporate-box-email" class="noshow">
						<textarea name="corporate-emails" id="corporate-emails" placeholder="Type or paste emails here. Separate them with commas" required="required"><?php echo $corp_list; ?></textarea><small class="block-element left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this mail to all available corporates email address</small>
					</div>
					<div id="inhouse-guest-box-email" class="noshow">
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
						<small class="block-element top-push-5 left-pull-5 alignlt steel-blue-font ft-xxsml-size">send this mail to guests that appear within the selected period</small>
					</div>
				</span>
				<span class="block-element bottom-push-30">
					<small class="block-element bottom-push-3 left-pull-5 alignlt">Subject</small>
					<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter email subject" required="required">
				</span>
				<span class="block-element bottom-push-10">
					<small class="block-element bottom-push-3 left-pull-5 alignlt">Message</small>
					<textarea name="fieldset3" id="fieldset3" placeholder="Type message here"></textarea>
					<script> CKEDITOR.replace( 'fieldset3' ); </script>
				</span>
				

				<br><br>
				
				<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-30"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
			</form>
		</div>
	</div>