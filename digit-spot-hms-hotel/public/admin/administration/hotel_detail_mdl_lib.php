<?php $smdl = "administration"; $logs = $_GET['logs']; ?>

<div class="block-element box-border-thick-bottom bottom-pull-15 bottom-push-20">
	<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Make changes to company details below. New changes overwrite the present information. All asterik fields are compulsory
</div>

<?php
	
	//create a table for this post
	createDatabasetable($var_tbl_32);
	createDatabasetable($var_tbl_146);

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = addslashes($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);
		$fieldset13 = escape_data($_POST['fieldset13']);
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset15 = addslashes($_POST['fieldset15']);
		$fieldset16 = addslashes($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);
		$fieldset18 = escape_data($_POST['fieldset18']);

		$insert_dataproperty = array("detail"=>$fieldset6,"address"=>$fieldset1,"phonenumber1"=>$fieldset2,"phonenumber2"=>$fieldset3,"contactemail"=>$fieldset4,"url"=>$fieldset5,"businesstype"=>$fieldset7,"starcategory"=>$fieldset8,"country"=>$fieldset9,"state"=>$fieldset10,"city"=>$fieldset11,"areaname"=>$fieldset12,"zipcode"=>$fieldset13,"seokeywords"=>$fieldset14,"tnc"=>$fieldset15,"otherinfo"=>$fieldset16,"name"=>$fieldset18);
		
		if(isset($fieldset17) && $fieldset17 >= 1) {
			$insert_constrain = array("id"=>$fieldset17);
			$data_inserted = mysqli_data_update($tbL34,$insert_dataproperty,$insert_constrain);
		} else {
			$insert_constrain = "";
			$data_inserted = mysqli_data_insert($tbL34,$insert_dataproperty,$insert_constrain);
		}
		

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Update hotel details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//get hotel details
	$dataproperty = "id,detail,address,phonenumber1,phonenumber2,contactemail,url,businesstype,starcategory,country,state,city,areaname,zipcode,seokeywords,tnc,otherinfo,name";
	$get_hotel_data = mysqli_data_fetch($tbL34,$dataproperty,'','noarray');

	if(isset($get_hotel_data[0])) { $id=$get_hotel_data[0]; } else { $id=0; }

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['presetsmsbutton'])) {
		foreach($_POST['message'] as $msg) {
			if(!empty($msg)) {
				$insert_dataproperty = array("msg"=>$msg);
				mysqli_data_insert($tbL150,$insert_dataproperty,'');
			}
		}
	}

	if(isset($_POST['delbutton']) && isset($_POST['checker'])) {
		foreach($_POST['checker'] as $msgid) {
			$d_query = array("id"=>$msgid);
			trash_record($tbL150,$d_query);
		}
	}

?>

<span class="float-right"><a href="javascript:void(0)" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 dark-black-white-state xsml-rounded-button ft-sml-size" onclick="chgclass('for-preset-sms','box-border-thick pads20 sml-rounded-button motion bottom-push-20'); document.getElementById('wgtf1').focus()">Add Preset SMS</a></span>
<h2 class="large"><?php echo _LONG_NAME; ?></h2><br>

<div id="for-preset-sms" class="noshow box-border-thick pads20 sml-rounded-button motion bottom-push-20">
	<h4 class="xlarge nobold black-font bottom-pull-15">Note: to move to next preset-message, press <u>enter-key</u> at the end of each message you typed</h4>
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<div id="text-container">
			<div id="txtwgtf1" class="sml-rounded-button pads15 right-push-5 bottom-push-5 motion"><input type="text" name="message[]" id="wgtf1" placeholder="Enter message here?" class="nopads no-back-black" onkeypress="nextObj(this.id,event)" required></div>
		</div>

		<div id="sbtn" class="xfadein motion" align="center">
			<input type="submit" id="submitbutton" name="presetsmsbutton" value="Add to List" class="nc-width-30 blue-white-state top-pull-10 bottom-pull-10 nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance default-text-font-bold">
		</div>
	</form>
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<div class="top-push-30 box-border-thick-top top-pull-20">
			<?php
				//get preset sms list
				$get_prs = mysqli_data_fetch($tbL150,'id,msg','','array');
				if(is_array($get_prs)) {
					foreach ($get_prs as $key => $val) {
						?>
							<div class="bottom-push-10"><span class="float-right left-pull-10"><input type="checkbox" name="checker[]" value="<?php echo $val['id']; ?>"></span>+ <?php echo $val['msg']; ?></div>
						<?php
					}
				}
			?>
			<p class="top-pull-5">
				<input type="submit" id="delbutton" name="delbutton" value="Delete" class="nc-width-10 blue-white-state top-pull-10 bottom-pull-10 nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance default-text-font-bold">
			</p>
		</div>
	</form>
</div>

<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Update contact information</small>

		<span class="block-element nc-width-100 bottom-push-30">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Name:</small>
			<textarea name="fieldset18" id="fieldset18" placeholder="e.g Maitama Hotels" required="required"><?php if(isset($get_hotel_data[17])) { echo $get_hotel_data[17]; } ?></textarea>
		</span>

		<span class="ln-display-box float-left nc-width-40">
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Address:</small>
				<textarea name="fieldset1" id="fieldset1" required="required"><?php if(isset($get_hotel_data[2])) { echo $get_hotel_data[2]; } ?></textarea>
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Phone Number 1:</small>
				<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter phone number" value="<?php if(isset($get_hotel_data[3])) { echo $get_hotel_data[3]; } ?>" required="required">
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-50">
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5">Phone Number 2:</small>
				<input type="text" name="fieldset3" id="fieldset3" placeholder="Enter phone number" value="<?php if(isset($get_hotel_data[4])) { echo $get_hotel_data[4]; } ?>">
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5">Official Email:</small>
				<input type="email" name="fieldset4" id="fieldset4" placeholder="Enter email address" value="<?php if(isset($get_hotel_data[5])) { echo $get_hotel_data[5]; } ?>">
			</div>
			<div class="block-element bottom-push-10">
				<small class="block-element bottom-push-5 left-pull-5">Website Address:</small>
				<input type="text" name="fieldset5" id="fieldset5" placeholder="Enter website address" value="<?php if(isset($get_hotel_data[6])) { echo $get_hotel_data[6]; } ?>">
			</div>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>

	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Update description</small>
		<small class="block-element bottom-push-5 left-pull-5">(Optional) About the company:</small>
		<textarea name="fieldset6" id="fieldset6"><?php if(isset($get_hotel_data[1])) { echo $get_hotel_data[1]; } ?></textarea>
		<script> CKEDITOR.replace( 'fieldset6' ); </script>
	</div>

	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Update Region Information</small>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5">Company Type:</small>
			<select name="fieldset7" id="fieldset7">
				<?php if(isset($get_hotel_data[7])) { ?><option value="<?php echo $get_hotel_data[7]; ?>"><?php echo $get_hotel_data[7]; ?></option><?php } else { ?><option value="">Choose</option><option value="Hospitality Business">Hospitality Business</option><?php } ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5">Star Category:</small>
			<select name="fieldset8" id="fieldset8">
				<?php if(isset($get_hotel_data[8])) { ?><option value="<?php echo $get_hotel_data[8]; ?>"><?php echo $get_hotel_data[8]; ?></option><?php } else { ?><option value="">Choose</option><?php } ?>
				<option value="1">1 Star</option>
				<option value="2">2 Star</option>
				<option value="3">3 Star</option>
				<option value="4">4 Star</option>
				<option value="5">5 Star</option>
				<option value="100">5 Star and above</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Country:</small>
			<select name="fieldset9" id="fieldset9" required="required">
				<?php if(isset($get_hotel_data[9])) { ?><option value="<?php echo $get_hotel_data[9]; ?>"><?php echo $get_hotel_data[9]; ?></option><?php } else { ?><option value="">Choose</option><option value="Nigeria">Nigeria</option><?php } ?>
			</select>
		</span>
		<span class="block-element new-line-space">
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; State:</small>
			<select name="fieldset10" id="fieldset10" required="required">
				<?php if(isset($get_hotel_data[10])): ?>
					<option value="<?php echo $get_hotel_data[10]; ?>"><?php echo $get_hotel_data[10]; ?></option>
				<?php endif; ?>
				<option value="Abia">Abia</option>
				<option value="Adamawa">Adamawa</option>
				<option value="Akwa Ibom">Akwa Ibom</option>
				<option value="Anambra">Anambra</option>
				<option value="Bauchi">Bauchi</option>
				<option value="Bayelsa">Bayelsa</option>
				<option value="Benue">Benue</option>
				<option value="Borno">Borno</option>
				<option value="Cross River">Cross River</option>
				<option value="Delta">Delta</option>
				<option value="Ebonyi">Ebonyi</option>
				<option value="Edo">Edo</option>
				<option value="Ekiti">Ekiti</option>
				<option value="Enugu">Enugu</option>
				<option value="FCT">FCT</option>
				<option value="Gombe">Gombe</option>
				<option value="Imo">Imo</option>
				<option value="Jigawa">Jigawa</option>
				<option value="Kaduna">Kaduna</option>
				<option value="Kano">Kano</option>
				<option value="Katsina">Katsina</option>
				<option value="Kebbi">Kebbi</option>
				<option value="Kogi">Kogi</option>
				<option value="Kwara">Kwara</option>
				<option value="Lagos">Lagos</option>
				<option value="Nasarawa">Nasarawa</option>
				<option value="Niger">Niger</option>
				<option value="Ogun">Ogun</option>
				<option value="Ondo">Ondo</option>
				<option value="Osun">Osun</option>
				<option value="Oyo">Oyo</option>
				<option value="Plateau">Plateau</option>
				<option value="Rivers">Rivers</option>
				<option value="Yobe">Yobe</option>
				<option value="Zamfara">Zamfara</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; City:</small>
			<input type="text" name="fieldset11" id="fieldset11" placeholder="Enter City e.g Kosofe" value="<?php if(isset($get_hotel_data[11])) { echo $get_hotel_data[11]; } else { echo _CITY; } ?>" required="required">
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Area Name:</small>
			<input type="text" name="fieldset12" id="fieldset12" placeholder="Enter Area Name e.g Alapere" value="<?php if(isset($get_hotel_data[12])) { echo $get_hotel_data[12]; } else { echo _CITY; } ?>" required="required">
		</span>
		<span class="block-element new-line-space">
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5"><b class="red-font">*</b>&nbsp; Zipcode:</small>
			<input type="text" name="fieldset13" id="fieldset13" placeholder="Enter zipcode" value="<?php if(isset($get_hotel_data[13])) { echo $get_hotel_data[13]; } ?>" required="required">
		</span>
		<span class="ln-display-box float-left nc-width-30 right-push-10 bottom-push-20">
			<small class="block-element bottom-push-5 left-pull-5">Seo Keywords:</small>
			<textarea name="fieldset14" id="fieldset14"><?php if(isset($get_hotel_data[14])) { echo $get_hotel_data[14]; } ?></textarea>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>

	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Amenities</small>
		<?php
			$dataproperty = "id,name";
			$constrain = array("deletedata"=>0,"status"=>"Active");
			$row = "array";

			$get_amenities = mysqli_data_fetch($tbL13,$dataproperty,$constrain,$row);

			if(is_array($get_amenities)) {
				foreach ($get_amenities as $amn_key => $amn_value) {
					?><span class="ln-display-box float-left nc-width-30 right-push-20 bottom-push-10 ft-xsml-size">
						<div class="ln-display-box float-left nc-width-10 top-pull-3"><b class="fa-checker nobold"></b></div>
						<div class="ln-display-box float-left nc-width-90"><?php echo $amn_value["name"]; ?></div>
						<div class="block-element new-line-space"></div>
					</span><?php
				}
			}
		?>
		<span class="block-element new-line-space">
		</span>
	</div>

	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Update terms and condition</small>
		<small class="block-element bottom-push-5 left-pull-5">(Optional) Company T&C:</small>
		<textarea name="fieldset15" id="fieldset15"><?php if(isset($get_hotel_data[15])) { echo $get_hotel_data[15]; } ?></textarea>
		<script> CKEDITOR.replace( 'fieldset15' ); </script>
	</div>

	<div class="block-element box-border-thick pads20 sml-rounded-button bottom-push-20">
		<small class="block-element bottom-push-15 add-bold">Update other information</small>
		<small class="block-element bottom-push-5 left-pull-5">(Optional) Others:</small>
		<textarea name="fieldset16" id="fieldset16"><?php if(isset($get_hotel_data[16])) { echo $get_hotel_data[16]; } ?></textarea>
		<script> CKEDITOR.replace( 'fieldset16' ); </script>
	</div>

	<br><br>

	<p class="alignct">
		<input type="hidden" name="fieldset17" id="fieldset17" value="<?php echo $id; ?>">
		<input type="submit" name="submitbutton" value="Update Info." class="submit pads10 blue-white-state rounded-button nc-width-40">
	</p>
</form>


<script>

	function nextObj(txtholder,e) {
	
		if(e.KeyCode == 13 || e.which == 13) {
			
			e.preventDefault();

			var contr,elems,noofElems,aElem;
			
			contr = document.getElementById('text-container');
			elems = contr.getElementsByTagName('div');
			noofElems = (elems.length) + 1;
			
			aElem = document.getElementById(txtholder);
			aElem.blur();
			
			chgclass('sbtn','xfadeout top-pull-50 motion');
			chgclass(txtholder,'nopads no-back-black alignlt ft-sml-size');
			chgclass('txt'+txtholder,'box-border-thick sml-rounded-button pads15 right-push-5 bottom-push-5 motion obj-light-shadow');

			var newTxtHolder = document.createElement('div');
			newTxtHolder.id = 'txtwgtf'+noofElems;
			newTxtHolder.className = 'box-border-thick sml-rounded-button pads15 right-push-5 bottom-push-5 motion obj-light-shadow';
			contr.appendChild(newTxtHolder);

			setTimeout(() => {
				newTxtHolder.innerHTML = '<input type="text" name="message[]" id="wgtf'+noofElems+'" placeholder="Enter message here?" class="nopads no-back-black" onkeypress="nextObj(this.id,event)">';
				document.getElementById('wgtf'+noofElems).focus();
			},200);
		}
	}

</script>