<?php
	
	$booking_number = $ftoken;
	$isguestid = $stoken;

	switch($isguestid) {
		case 0:
			$guest_as = 'Primary Contact';
			break;
		
		default:
			$guest_as = 'Group Guest';
			break;
	}

	include "post_booking_tokens.php";
	include "booking_tokens.php";

	if(isset($get_guest_detail[13]) && $get_guest_detail[13] >= 1) { $idtype = idget_data($tbL37,$get_guest_detail[13],'name'); } else { $idtype = "Choose"; }

	$get_country = select_dt_fetch('',0,$tbL64,'id','name');

	if($get_guest_detail[12] > 0) { $country_name = idget_data($tbL64,$get_guest_detail[12],'name'); }
	else { $country_name = ""; }

	if($get_guest_detail[11] > 0) { $state_name = idget_data($tbL65,$get_guest_detail[11],'name'); }
	else { $state_name = ""; }

?>
<form action="" method="post">
	<p class="alignrt bottom-pull-7">
		<input type="button" value="Print" onclick="window.print()">
	</p>
	<div id="section-to-print" class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Guest Details</h2></legend>
			<div class="block-element cs-height-10"></div>
			<h3 class="large nobold default-text-font-bold alignct">Complete the guest detail by filling the necessary information</h3><br>
			<span class="ln-display-box float-left nc-width-30">
				<div class="block-element noscroll">
					<?php if(isset($get_guest_detail[1]) && ($get_guest_detail[1] != NULL && $get_guest_detail[1] != '')) { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/guestphotos/<?php echo $get_guest_detail[1]; ?>" class="auto-wh"><?php } else { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png"><?php } ?>
				</div>
				<h4 class="large nobold light-red-font top-pull-10"><?php echo $guest_as; ?></h4>
				<p class="top-pull-20">
					<a href="javascript:void(0)" class="blue-font ft-xxsml-size box-border-thick-blue rounded-button top-pull-7 right-pull-30 bottom-pull-7 left-pull-30" onclick="xModal(1,'fx-position-stick fscr zind-2 motion pads30 y-scroll',1)">Change Photo</a>
				</p>

				<br><br>

				<fieldset>
					<legend>Basic Information</legend>

					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Title <sup class="red-font">*</sup></small>
						<span class="ln-display-box float-left nc-width-40 right-pull-5">
							<select name="wgtfield1" id="wgtfield1" required="required">
								<option value="<?php echo $get_guest_detail[3]; ?>" selected="selected"><?php echo $salutation; ?></option>
								<?php echo $salutations; ?>
							</select>
						</span>
						<span class="ln-display-box float-left nc-width-50">
							&nbsp;
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Guest Name <sup class="red-font">*</sup></small>
						<span class="ln-display-box float-left nc-width-50 right-pull-5"><input type="text" name="wgtfield2" id="wgtfield2" value="<?php echo $get_guest_detail[4]; ?>" required></span>
						<span class="ln-display-box float-left nc-width-50 left-pull-5"><input type="text" name="wgtfield3" id="wgtfield3" value="<?php echo $get_guest_detail[5]; ?>" required></span>
						<span class="block-element new-line-space"></span>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Gender</small>
						<select name="wgtfield2a" id="wgtfield2a">
							<?php if(!empty($get_guest_detail[17])) { ?><option value="<?php echo $get_guest_detail[17]; ?>" selected="selected"><?php echo $get_guest_detail[17]; ?></option><?php } else { ?><option value="" selected="selected">Gender</option><?php } ?>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
						<input type="date" name="wgtfield3a" id="wgtfield3a" value="<?php if(!empty($get_guest_detail[19])) { echo $get_guest_detail[19]; } ?>" oninput="getAge(this.value,'wgtfield4a')">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Age</small>
						<input type="text" name="wgtfield4a" id="wgtfield4a" value="<?php if(!empty($get_guest_detail[18])) { echo $get_guest_detail[18]; } ?>" readonly>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Nationality <sup class="red-font">*</sup></small>
						<input type="text" name="wgtfield5a" id="wgtfield5a" value="<?php if(!empty($get_guest_detail[21])) { echo $get_guest_detail[21]; } ?>" required>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Occupation</small>
						<input type="text" name="wgtfield8" id="wgtfield8" value="<?php echo $get_guest_detail[15]; ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Employer/Host</small>
						<input type="text" name="wgtfield6a" id="wgtfield6a" value="<?php if(!empty($get_guest_detail[24])) { echo $get_guest_detail[24]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
						<textarea name="wgtfield14" id="wgtfield14"><?php echo $get_guest_detail[8]; ?></textarea>
					</div>
				</fieldset>
			</span>
			<span class="ln-display-box float-left nc-width-30 left-pull-30">
				<fieldset>
					<legend>Contact Details</legend>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Contact Address</small>
						<textarea name="wgtfield10" id="wgtfield10"><?php echo $get_guest_detail[9]; ?></textarea>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Country</small>
						<select name="wgtfield13" id="wgtfield13" onchange="getdata('wgtfield12','eget-country-states-list','wgtfield13','dropbox');">
							<?php if(!empty($country_name)) { ?><option value="<?php echo $get_guest_detail[12]; ?>" selected="selected"><?php echo $country_name; ?></option><?php } else { ?><option value="" selected="selected">Country</option><?php } echo $get_country; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">State</small>
						<select name="wgtfield12" id="wgtfield12">
							<?php if(!empty($state_name)) { ?><option value="<?php echo $get_guest_detail[11]; ?>" selected="selected"><?php echo $state_name; } else { ?><option value="" selected="selected">State</option><?php } ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">City</small>
						<input type="text" name="wgtfield11" id="wgtfield11" value="<?php if(!empty($get_guest_detail[10])) { echo $get_guest_detail[10]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Zip Code</small>
						<input type="text" name="wgtfield11b" id="wgtfield11b" value="<?php if(!empty($get_guest_detail[26])) { echo $get_guest_detail[26]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Phone No.</small>
						<input type="text" name="wgtfield4b" id="wgtfield4b" value="<?php if(!empty($get_guest_detail[25])) { echo $get_guest_detail[25]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Mobile No. <sup class="red-font">*</sup></small>
						<input type="text" name="wgtfield4" id="wgtfield4" value="<?php if(!empty($get_guest_detail[6])) { echo $get_guest_detail[6]; } ?>" required>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Email Address</small>
						<input type="text" name="wgtfield5" id="wgtfield5" value="<?php if(!empty($get_guest_detail[7])) { echo $get_guest_detail[7]; } ?>">
					</div>
				</fieldset>

				<br><br>

				<fieldset>
					<legend>Identification Details</legend>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Means of Identification</small>
						<select name="wgtfield6" id="wgtfield6">
							<option value="<?php echo $get_guest_detail[13]; ?>" selected><?php echo $idtype; ?></option>
							<?php echo $identity_type; ?>
						</select>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Identification Number</small>
						<input type="text" name="wgtfield7" id="wgtfield7" value="<?php if(!empty($get_guest_detail[14])) { echo $get_guest_detail[14]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Issue date of Reg.</small>
						<input type="date" name="wgtfield7a" id="wgtfield7a" value="<?php if(!empty($get_guest_detail[29])) { echo $get_guest_detail[29]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Issue place of Reg.</small>
						<input type="text" name="wgtfield8a" id="wgtfield8a" value="<?php if(!empty($get_guest_detail[30])) { echo $get_guest_detail[30]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Present Address</small>
						<textarea name="wgtfield11a" id="wgtfield11a"><?php if(!empty($get_guest_detail[31])) { echo $get_guest_detail[31]; } ?></textarea>
					</div>
				</fieldset>
			</span>
			<span class="ln-display-box float-left nc-width-40 left-pull-30">
				<fieldset>
					<legend>Immigration Details</legend>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of arrival in country</small>
						<input type="date" name="wgtfield9a" id="wgtfield9a" value="<?php if(!empty($get_guest_detail[27])) { echo $get_guest_detail[27]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Next Destination</small>
						<input type="text" name="wgtfield10a" id="wgtfield10a" value="<?php if(!empty($get_guest_detail[28])) { echo $get_guest_detail[28]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Immi. Status</small>
						<input type="text" name="wgtfield9b" id="wgtfield9b" value="<?php if(!empty($get_guest_detail[22])) { echo $get_guest_detail[22]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Allien Reg. No</small>
						<input type="text" name="wgtfield12a" id="wgtfield12a" value="<?php if(!empty($get_guest_detail[23])) { echo $get_guest_detail[23]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Duration of Stay</small>
						<span class="ln-display-box float-left nc-width-50 right-pull-5">
							<input type="text" name="wgtfield9" id="wgtfield9" pattern="\d*" placeholder="Enter e.g 2" value="<?php echo $get_guest_detail[16]; ?>">
						</span>
						<span class="ln-display-box float-left nc-width-40">
							<select>
								<option value="days">Day(s)</option>
								<option value="months">Month(s)</option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Probable Destination</small>
						<input type="text" name="wgtfield13a" id="wgtfield13a" value="<?php if(!empty($get_guest_detail[32])) { echo $get_guest_detail[32]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Passport No.</small>
						<input type="text" name="wgtfield14a" id="wgtfield14a" value="<?php if(!empty($get_guest_detail[33])) { echo $get_guest_detail[33]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Issue Date</small>
						<input type="date" name="wgtfield15a" id="wgtfield15a" value="<?php if(!empty($get_guest_detail[34])) { echo $get_guest_detail[34]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Expiry Date</small>
						<input type="date" name="wgtfield16a" id="wgtfield16a" value="<?php if(!empty($get_guest_detail[35])) { echo $get_guest_detail[35]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Issue Place</small>
						<input type="text" name="wgtfield17a" id="wgtfield17a" value="<?php if(!empty($get_guest_detail[36])) { echo $get_guest_detail[36]; } ?>">
					</div>
					<div class="block-element bottom-push-10">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Visa Validity</small>
						<input type="text" name="wgtfield18a" id="wgtfield18a" value="<?php if(!empty($get_guest_detail[37])) { echo $get_guest_detail[37]; } ?>">
					</div>
				</fieldset>
			</span>
			<span class="block-element new-line-space">
			</span>

		</fieldset>	

		<div class="block-element top-pull-30 alignct">
			<input type="hidden" name="wgtfield15" id="wgtfield15" value="<?php echo $get_guest_detail[0]; ?>">
			<input type="hidden" name="wgtfield16" id="wgtfield16" value="<?php echo $booking_number; ?>">
			<input type="hidden" name="wgtag" id="wgtag" value="guestdetail">
			<input type="submit" name="submitbutton" value="Save Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 dark-black-white-state rounded-button default-text-font-bold">
		</div>
	</div>
</form>

<!-- for photo upload -->
<div id="modal-win-1" class="fx-position-flow btscr motion" align="center">
	<div id="modal-box-1" class="noshow white-theme cs-margin-top-100 xsml-rounded-button obj-shadow pads30 fx-width-30 noscroll alignlt">
		<form action="" method="post" autocomplete="off" id="imgform" enctype="multipart/form-data">
			 <a href="javascript:void(0)" class="royal-blue-font ft-mini-size float-right" onclick="xModal(1,'fx-position-flow right-layout motion',0); htmlFormReset('imgform')"><b class="mbri-close"></b></a>
			<h2 class="large nobold default-text-font-bold">Guest Photograph</h2><br>
			<div id="image-box" class="cs-height-350 box-border-dashed xsml-rounded-button pads20 noscroll">
				<div class="nc-height-20"></div>
				<div id="image-tip" class="alignct ft-mini-size dark-grey-font" onclick="document.getElementById('f').click()">
					<h1 class="nobold fa-camera fa-color-strike-3" style="font-size: 5em"></h1>
					<small class="block-element royal-blue-font anchor">Click to attach photo</small>
				</div>
				<h2 class="large nobold alignct top-pull-5">-OR-</h2>
				<p class="alignct">
					<a href="javascript:void(0)" onclick="startCamera()" class="royal-blue-font ft-sml-size">Click to use camera</a>
				</p>
			</div>
			<input onchange="resizeimage(event,350,300,'dataurl','notupload','cimg','image-box'); writeObjheader('fmsg','attaching image..'); chgclass('imbutton','top-push-30')" type="file" id="f" style="position: fixed; top: -100em" accept=".png, .jpg, .jpeg">
			<input type="hidden" name="dataurl" id="dataurl">
			<small id="fmsg" class="block-element red-font bottom-push-10 alignlt"></small>
			<input type="hidden" name="wgtidx" id="wgtidx" value="<?php echo $wgt_pry_id; ?>" required="required">
			<div id="snap" class="noshow" align="center">
			</div>
			<div id="imbutton" class="noshow top-push-30" align="center">
				<input type="submit" name="imagebutton" value="Apply" class="nc-width-60 submit anchor top-pull-7 bottom-pull-7 dark-black-white-state rounded-button default-text-font-bold right-push-10">
			</div>
			<div id="fmessage">
			</div>
		</form>
	</div>
</div>

<script src="../../js/webcam.js"></script>

<script>

	function getAge(dateString,elem) {
	   var ageInMilliseconds = new Date() - new Date(dateString);
	   var age = Math.floor(ageInMilliseconds/1000/60/60/24/365); //convert to years
	   document.getElementById(elem).value = age;
	}

	
	function startCamera() {
		Webcam.set({
			width: 350,
			height: 300,
			image_format: 'jpeg',
			jpeg_quality: 100,
			force_flash: false
		});
		
		Webcam.attach('#image-box');
		setTimeout(function() { chgclass('snap','block-element top-pull-15'); writeObjheader('snap','<a href="javascript:void(0)" onclick="capturePhoto()" class="ft-sml-size top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button">Capture Photo</a>'); },1000);
	}

	function capturePhoto() {
		 Webcam.snap( function(data_uri) {
        	var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById('image-box').innerHTML = '<img src="'+data_uri+'"/>';
        	document.getElementById('dataurl').value = raw_image_data;
        	chgclass('imbutton','top-push-20');
        	chgclass('snap','noshow');
    	} );
	}

</script>