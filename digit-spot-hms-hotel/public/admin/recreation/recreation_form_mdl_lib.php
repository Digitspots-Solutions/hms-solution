<?php $smdl = "recreation"; $logs = escape_data($_GET['logs']); $salutations = select_dt_fetch('status','Active',$tbL42,'id','name');
$duration = arrayset_form($recreation_duration,'select'); $list_payment_modes = select_dt_fetch('',0,$tbL24,'id','name');

$complimentary = select_dt_fetch('status','Active',$tbL33,'id','name');
$additionalQuery = " ORDER BY name ASC";
$cspg = select_dt_fetch('',0,$tbL58,'id','name');
$additionalQuery = "";

$amdl = 10; include "get_avail_workflow.php";
?>

<div class="block-element pads10">
	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" class="black-font" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"> Refresh</a> 
 	</span>
	<p class="bottom-pull-10 right-pull-30 alignrt">
		<a href="javascript:void(0)" class="blue-font" onclick="window.print()"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print</a>
	</p>

	<div id="section-to-print" class="block-element">
		<h1 class="large alignct nomargin">Recreation Centre - <?php echo _LONG_NAME; ?></h1>
		<h1 class="large nobold alignct">Membership Application Form</h1>

		<small class="block-element top-push-5 bottom-push-30 dark-grey-font alignct">(Information provided in this form will be treated as confidential)</small>

		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<div class="block-element" align="center">
				<div class="block-element nc-width-80 alignlt">
					<span class="ln-display-box float-left nc-width-25 right-push-30">
						<div id="imagebox" class="block-element cs-height-200 grey-theme box-border-thick bottom-push-10 noscroll alignct">
							<div class="block-element nc-height-40"></div>
							<b class="fa-camera fa-mini-size" onclick="document.getElementById('f').click()"></b>
						</div>
						<small class="block-element">Attach Photograph</small>
						<input type="hidden" name="dataurl" id="dataurl">
						<small id="fmsg" class="block-element red-font top-push-5 alignlt"></small>
						<input onchange="resizeimage(event,250,250,'dataurl','notupload','cimg','imagebox'); writeObjheader('fmsg','attaching image..')" type="file" id="f" style="position: fixed; top: -100em">
					</span>
					<span class="ln-display-box float-left nc-width-30 right-push-50">
						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Title <sup class="red-font">*</sup></small>
							<select name="fieldset1" id="fieldset1" required="required">
								<option value="" selected="selected">Choose</option>
								<?php echo $salutations; ?>
							</select>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">First Name <sup class="red-font">*</sup></small>
							<input type="text" name="fieldset2" id="fieldset2" placeholder="firstname?" required="required">
						</div>
						
						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Last Name <sup class="red-font">*</sup></small>
							<input type="text" name="fieldset3" id="fieldset3" placeholder="lastname?" required="required">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Marital Status</small>
							<select name="fieldset4" id="fieldset4">
								<option value="" selected="selected">Choose</option>
								<option value="Single">Single</option>
								<option value="Married">Married</option>
							</select>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Sex <sup class="red-font">*</sup></small>
							<select name="fieldset5" id="fieldset5" required="required">
								<option value="" selected="selected">Choose</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
							<input type="date" name="fieldset6" id="fieldset6">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Nationality</small>
							<input type="text" name="fieldset7" id="fieldset7" placeholder="nationality?">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Email Address</small>
							<input type="text" name="fieldset8" id="fieldset8">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Mobile Number</small>
							<input type="number" name="fieldset9" id="fieldset9" placeholder="mobile number?">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Type of Membership <sup class="red-font">*</sup></small>
							<select name="fieldset10" id="fieldset10" required="required" onchange="addon_membership()">
								<option value="" selected="selected">Choose</option>
								<option value="Single">Single</option>
								<option value="Couple">Couple</option>
								<option value="Family">Family</option>
							</select>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">For Complimentary?</small>
							<select name="fieldset11" id="fieldset11" onchange="for_compl()">
								<option value="No">No</option>
								<option value="Yes">Yes</option>
							</select>
							<div id="show-complimentary" class="noshow top-push-3">
								<select name="complimentary" id="complimentary">
									<option value="" selected="selected">Choose Complimentary</option>
									<?php echo $complimentary; ?>
								</select>
							</div>
						</div>

						<div class="bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">For Corporate?</small>
							<select name="fieldset24" id="fieldset24" onchange="for_corp()">
								<option value="No">No</option>
								<option value="Yes">Yes</option>
							</select>
							<div id="show-corporate" class="noshow top-push-3">
								<select name="corporate" id="corporate">
									<option value="" selected="selected">Choose Corporate</option>
									<?php echo $cspg; ?>
								</select>
							</div>
						</div>

					</span>
					<span class="ln-display-box float-left nc-width-30">
						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Profession</small>
							<input type="text" name="fieldset12" id="fieldset12" placeholder="profession?">
						</div>
						
						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Height</small>
							<span class="ln-display-box float-left nc-width-30">
								<input type="text" name="fieldset13" id="fieldset13" placeholder="0">
							</span>
							<span class="ln-display-box float-left nc-width-70">
								<select name="fieldset13b" id="fieldset13b">
									<option value="Centimeters">Centimeters</option>
									<option value="Feets">Feets</option>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Weight</small>
							<span class="ln-display-box float-left nc-width-30">
								<input type="text" name="fieldset14" id="fieldset14" placeholder="0">
							</span>
							<span class="ln-display-box float-left nc-width-70">
								<select name="fieldset14b" id="fieldset14b">
									<option value="Kgs">Kgs</option>
									<option value="Lbs">Lbs</option>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Blood Group</small>
							<input type="text" name="fieldset15" id="fieldset15">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Genotype</small>
							<input type="text" name="fieldset16" id="fieldset16">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Company Address</small>
							<textarea name="fieldset17" id="fieldset17"></textarea>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Phone Number</small>
							<input type="number" name="fieldset18" id="fieldset18">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Residential Address</small>
							<textarea name="fieldset19" id="fieldset19"></textarea>
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Start from (effectiveness)  <sup class="red-font">*</sup></small>
							<input type="date" name="fieldset20" id="fieldset20" required="required">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Duration <sup class="red-font">*</sup></small>
							<select name="fieldset21" id="fieldset21" required="required">
								<option value="" selected="selected">Choose</option>
								<?php echo $duration; ?>
							</select>
						</div>

						<div class="noshow bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Other Names</small>
							<input type="text" name="fieldset22" id="fieldset22">
						</div>

						<div class="block-element bottom-push-15">
							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Workflow</small>
							<select name="fieldset23" id="fieldset23">
								<option value="0">N/A</option>
								<?php //echo $ths_workflow_names; ?>
							</select>
						</div>

					</span>
					<span class="block-element new-line-space">
					</span>
					
					<div id="addon-forms" class="block-element bottom-push-15">
						<div id="show-couple-membership" class="noshow box-border-thick sml-rounded-button pads15 noscroll">
							<h3 class="large alignct">For couple membership, please complete the below section</h3><br>
							<span class="ln-display-box float-left nc-width-25 right-push-50">
								<div id="imagebox-cpl" class="block-element cs-height-200 grey-theme box-border-thick bottom-push-10 noscroll alignct">
									<div class="block-element nc-height-40"></div>
									<b class="fa-camera fa-mini-size" onclick="document.getElementById('cpl').click()"></b>
								</div>
								<small class="block-element">Attach Photograph</small>
								<input type="hidden" name="dataurl-cpl" id="dataurl-cpl">
								<small id="fmsg-cpl" class="block-element red-font top-push-5 alignlt"></small>
								<input onchange="resizeimage(event,250,250,'dataurl-cpl','notupload','cimg','imagebox-cpl'); writeObjheader('fmsg-cpl','image attached..')" type="file" id="cpl" style="position: fixed; top: -100em">
							</span>
							<span class="ln-display-box float-left nc-width-30">
								<div class="block-element bottom-push-10">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Name of Spouse</small>
									<input type="text" name="spousename" id="spousename">
									<input type="hidden" name="member100" id="member100" value="Spouse">
								</div>
								<div class="block-element bottom-push-10">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
									<input type="date" name="spousedob" id="spousedob">
								</div>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div id="show-family-membership" class="noshow box-border-thick sml-rounded-button pads15 noscroll">
							<h3 class="large alignct">For family membership, please complete the below section</h3><br>
							<h4 class="large nobold">&bull; Spouse</h4><br>
							<span class="ln-display-box float-left nc-width-25 right-push-50">
								<div id="imagebox-cpl-2" class="block-element cs-height-200 grey-theme box-border-thick bottom-push-10 noscroll alignct">
									<div class="block-element nc-height-40"></div>
									<b class="fa-camera fa-mini-size" onclick="document.getElementById('cpl-2').click()"></b>
								</div>
								<small class="block-element">Attach Photograph</small>
								<input type="hidden" name="dataurl-cpl-2" id="dataurl-cpl-2">
								<small id="fmsg-cpl-2" class="block-element red-font top-push-5 alignlt"></small>
								<input onchange="resizeimage(event,250,250,'dataurl-cpl-2','notupload','cimg','imagebox-cpl-2'); writeObjheader('fmsg-cpl-2','image attached..')" type="file" id="cpl-2" style="position: fixed; top: -100em">
							</span>
							<span class="ln-display-box float-left nc-width-30">
								<div class="block-element bottom-push-10">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Name of Spouse</small>
									<input type="text" name="spousename-2" id="spousename-2">
									<input type="hidden" name="member200" id="member200" value="Spouse">
								</div>
								<div class="block-element bottom-push-10">
									<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
									<input type="date" name="spousedob-2" id="spousedob-2">
								</div>
							</span>
							<span class="block-element new-line-space">
							</span>

							<span class="block-element box-border-thick-top top-push-10 bottom-push-10">
							</span>

							<h4 class="large nobold">&bull; Children &nbsp; <a href="javascript:void(0)" class="black-font" title="Add more children" onclick="addchilds()"><b class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 black-theme white-font">+</b></a></h4><br>
							<span class="ln-display-box float-left nc-width-30 right-push-50 bottom-push-20">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Child Name</small>
								<input type="text" name="childname[]" placeholder="Name of child">
							</span>
							<span class="ln-display-box float-left nc-width-30 bottom-push-20">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
								<input type="date" name="childob[]" id="childob[]">
							</span>
							<span class="block-element new-line-space">
							</span>
							<span class="ln-display-box float-left nc-width-30 right-push-50 bottom-push-20">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Child Name</small>
								<input type="text" name="childname[]" placeholder="Name of child">
							</span>
							<span class="ln-display-box float-left nc-width-30 bottom-push-20">
								<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
								<input type="date" name="childob[]">
							</span>
							<span class="block-element new-line-space">
							</span>
							<div id="morechild" class="block-element">
							</div>
						</div>
					</div>

					<div class="block-element nc-width-50 bottom-push-30">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
						<textarea name="fieldset25" id="fieldset25"></textarea>
					</div>

					<div class="block-element bottom-push-15">
						<small class="block-element bottom-push-7 left-pull-5 dark-grey-font add-bold">Make Payment</small>
						<div class="block-element sml-rounded-button noscroll">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="150px" align="center">Mode</th>
									<th width="150px" align="center">Amount</th>
									<th width="150px" align="center">CC/Cheque No</th>
									<th width="150px" align="center">Receipt</th>
									<th width="200px" align="center">Description</th>
								</tr>
								<tr>
									<td width="150px" align="center">
										<select name="payment-mode" id="payment-mode">
											<option value="" selected>Choose?</option>
											<?php echo $list_payment_modes; ?>
										</select>
									</td>
									<td width="150px" align="center">
										<input type="number" name="amount" id="amount" step="any" placeholder="0.00">
									</td>
									<td width="150px" align="center">
										<input type="text" name="cheque-number" id="cheque-number">
									</td>
									<td width="150px" align="center">
										<input type="text" name="receipt" id="receipt">
									</td>
									<td width="200px" align="center">
										<input type="text" name="detail" id="detail">
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="block-element top-pull-20 bottom-push-15 alignct">
						<input type="submit" name="submitbutton" value="Create New Membership" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<?php
	
	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_100); //create a table for this post
		createDatabasetable($var_tbl_101); //create a table for this post
		createDatabasetable($var_tbl_102); //create a table for this post

		$fieldset1 = $_POST['fieldset1'];
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);
		$fieldset13 = escape_data($_POST['fieldset13']);
		$fieldset13b = escape_data($_POST['fieldset13b']);
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset14b = escape_data($_POST['fieldset14b']);
		$fieldset15 = escape_data($_POST['fieldset15']);
		$fieldset16 = escape_data($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);
		$fieldset18 = escape_data($_POST['fieldset18']);
		$fieldset19 = escape_data($_POST['fieldset19']);
		$fieldset20 = escape_data($_POST['fieldset20']);
		$fieldset21 = escape_data($_POST['fieldset21']);
		$fieldset22 = escape_data($_POST['fieldset22']);
		$fieldset23 = escape_data($_POST['fieldset23']);
		$fieldset24 = escape_data($_POST['fieldset24']);
		$fieldset25 = escape_data($_POST['fieldset25']);


		if(isset($_POST['dataurl']) && !empty($_POST['dataurl'])) {
			$encoded_data = str_replace(' ','+',$_POST['dataurl']);
			$binary_data = base64_decode($encoded_data);

			$fs_img = "fs_rcr_".date('YmdHis');
			$fs_rcr="../../theme/images/general/recreation-members/".$fs_img.".jpg";
			
			file_put_contents($fs_rcr, $binary_data);
			$fs_image = $fs_img.".jpg";
		} else {
			$fs_image = null;
		}

		if(isset($fieldset10) && $fieldset10 == 'Couple') {
			$photo_data = $_POST['dataurl-cpl'];
			$spouse_name = escape_data($_POST['spousename']);
			$spouse_dob = $_POST['spousedob'];
		} elseif(isset($fieldset10) && $fieldset10 == 'Family') {
			$photo_data = $_POST['dataurl-cpl-2'];
			$spouse_name = escape_data($_POST['spousename-2']);
			$spouse_dob = $_POST['spousedob-2'];
		} else {
			$photo_data = null;
			$spouse_name = null;
			$spouse_dob = null;
		}


		if(isset($photo_data) && !empty($photo_data)) {

			$encoded_data_2 = str_replace(' ','+',$photo_data);
			$binary_data_2 = base64_decode($encoded_data_2);

			$ss_img = "ss_rcr_".date('YmdHis');
			$ss_rcr="../../theme/images/general/recreation-members/".$ss_img.".jpg";
			
			file_put_contents($ss_rcr, $binary_data_2);
			$ss_image = $ss_img.".jpg";
		} else {
			$ss_image = null;
		}


		if(isset($fieldset11) && $fieldset11 == 'Yes') {
			$complimentary_src = $_POST['complimentary'];
		} else {
			$complimentary_src = 0;
		}

		if(isset($fieldset24) && $fieldset24 == 'Yes') {
			$corporate_src = $_POST['corporate'];
		} else {
			$corporate_src = 0;
		}


		$recreation_plan = arrayget_key($recreation_duration,$fieldset21);
		$recreation_plan_due_date = date("Y-m-d",strtotime($fieldset20.' +'.$recreation_plan));

		//$recreation_no = prgSequence($tbL155,'RECR');


		//save the registration
		$dataproperty = array("photo"=>$fs_image,"salutation"=>$fieldset1,"firstname"=>ucwords(strtolower($fieldset2)),"lastname"=>ucwords(strtolower($fieldset3)),"othernames"=>ucwords(strtolower($fieldset22)),"maritalstatus"=>$fieldset4,"gender"=>$fieldset5,"dob"=>$fieldset6,"nationality"=>ucwords(strtolower($fieldset7)),"emailaddress"=>$fieldset8,"mobile"=>$fieldset9,"membership_type"=>$fieldset10,"iscomplimentary"=>$fieldset11,"complimentary_src"=>$complimentary_src,"profession"=>ucwords(strtolower($fieldset12)),"bodyheight"=>$fieldset13,"heightuom"=>$fieldset13b,"bodyweight"=>$fieldset14,"weightuom"=>$fieldset14b,"bloodgroup"=>strtoupper($fieldset15),"genotype"=>strtoupper($fieldset16),"officeaddress"=>$fieldset17,"officephone"=>$fieldset18,"homeaddress"=>$fieldset19,"plan"=>$fieldset21,"startdate"=>$fieldset20,"enddate"=>$recreation_plan_due_date,"workflow"=>$fieldset23,"iscorporate"=>$fieldset24,"corporate_type"=>$corporate_src,"detail"=>$fieldset25,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		if(isset($_POST['amount']) && !empty($_POST['amount']) && $_POST['amount'] > 0) {
			$dataproperty['status'] = 1;
		}

		$isdata = mysqli_data_insert($tbL105,$dataproperty,'');

		if(isset($isdata) && $isdata == 2) {
			
			$new_recreation_id = $mysqli_id;
			
			//update member recreation number
			$recreation_number = $recreation_prefix.$new_recreation_id;
			$update_dataproperty = array("recreation_number"=>$recreation_number);
			$data_key = array("id"=>$new_recreation_id);
			mysqli_data_update($tbL105,$update_dataproperty,$data_key);

			//for payment / bill

			#get user counter session id
			$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

			$totalamount = escape_data($_POST['amount']);
			$wgt_paymentmode = escape_data($_POST['payment-mode']);

			#send charge to corporate if is registered as that
			if(isset($totalamount) && !empty($totalamount) && $totalamount > 0 && $corporate_src >= 1) {

				#retrieve group creditlimt
				$credit_limit = idget_data($tbL58,$corporate_src,'creditlimit');
				$new_creditlimit = $credit_limit - $totalamount;

				#update group creditlimt
				$blc_selection_key = array("id"=>$corporate_src);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

				$transaction_desc = "Recreation charges for ".ucwords(strtolower($fieldset2))." ".ucwords(strtolower($fieldset3))." with recreation number ({$recreation_number})";

				$ledger_dataquery = array("cspgid"=>$corporate_src,"transaction_number"=>$recreation_number,"transaction_type"=>"Debit");
				$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$corporate_src,"transaction_number"=>$recreation_number,"transaction_type"=>"Debit","amount"=>$totalamount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"recreation","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);

				#log payment for corporate
				$payment_dataproperty = array("recreation_number"=>$recreation_number,"memberid"=>$new_recreation_id,"mode"=>111,"amount"=>$totalamount,"chequenumber"=>escape_data($_POST['cheque-number']),"receipt"=>escape_data($_POST['receipt']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$server_get_date,"userid"=>$userSignedIn,"startdate"=>$fieldset20,"enddate"=>$recreation_plan_due_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL107,$payment_dataproperty,'');
				$new_payment_id = $mysqli_id;
				$invoice_number = $recreation_inv_prefix.$new_payment_id;

				$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
				$pay_data_key = array("id"=>$new_payment_id);
				mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);
			}

			#update sales for open-counter
			if((isset($wgt_paymentmode) && $wgt_paymentmode > 0) && (isset($totalamount) && $totalamount > 0)) {
				
				$payment_dataproperty = array("recreation_number"=>$recreation_number,"memberid"=>$new_recreation_id,"mode"=>$wgt_paymentmode,"amount"=>$totalamount,"chequenumber"=>escape_data($_POST['cheque-number']),"receipt"=>escape_data($_POST['receipt']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$server_get_date,"userid"=>$userSignedIn,"startdate"=>$fieldset20,"enddate"=>$recreation_plan_due_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL107,$payment_dataproperty,'');
				$new_payment_id = $mysqli_id;
				$invoice_number = $recreation_inv_prefix.$new_payment_id;

				$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
				$pay_data_key = array("id"=>$new_payment_id);
				mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);


				$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

				$new_collection = $sales_counter_data[0] + $totalamount;

				$sales_counter_sql = array("collection"=>$new_collection);
				mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

				//insert information for approval
				$ths_workflow = $fieldset23;
				
				if(isset($ths_workflow) && $ths_workflow > 0) {
					/*$document_ref_number = $recreation_number;
					$document_alias = "recreation_number";
					$subject = 4;
					$priority = 2;
					$message = "A new membership account has been created for RECREATION with recreation number (".$document_ref_number."). Kindly do the needful";*/
		
					include "document_approval.php";
				}
			}

			//for spouse data
			if(isset($spouse_name) && !empty($spouse_name)) {
				$spouse_dataproperty = array("memberid"=>$new_recreation_id,"listype"=>"spouse","photo"=>$ss_image,"flname"=>ucwords(strtolower($spouse_name)),"dob"=>$spouse_dob,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL106,$spouse_dataproperty,'');
			}
			
			//for children added
			$child_name = $_POST['childname']; $child_dob = $_POST['childob']; $child_dataproperty = "";

			for($ch=0; $ch <= count($child_name); $ch++) {
				if($child_name[$ch] != "" && $child_dob[$ch] != "") {
					$child_dataproperty = array("memberid"=>$new_recreation_id,"listype"=>"child","flname"=>ucwords(strtolower($child_name[$ch])),"dob"=>$child_dob[$ch],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL106,$child_dataproperty,'');
				}
			}

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Recreation membership is registered successfully";
			
			$islogfile = 1;
			$logfile_msg = "New recreation membership (".$recreation_number.") was added by this user";

			
			//generate information for guest transaction flow

			/*if($complimentary_src >= 1) { $biller = 5; }
			else { $biller = 1; }

			$createdby = $userSignedIn;
			$transaction_flow_number = $invoice_number; $transaction_type = "Pending";
			$guest_number = $new_recreation_id; $sales_point = 6;
			$sales_description = "Recreation membership";
			$transaction_amount = $_POST['amount']; $balance_bfw = 0; $transaction_payment_mode = $_POST['payment-mode'];
			if(isset($_POST['cheque-number']) && !empty($_POST['cheque-number'])) { $cheque_number = $_POST['cheque-number']; } else { $cheque_number = ""; }*/

			//include "guest_transaction_flow.php";
		

			/*
			//create a log file
			$log_message = "Create new recreation membership (".$recreation_number.")";
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');*/

			?>
				<script>
					const rcrno = "<?php echo $recreation_number; ?>";
					alert("Notification\n\nRecreation with membership number ("+rcrno+") was registered successfully. Go to recreation dashboard to see the new registration");
				</script>

			<?php
		}

	}

?>


<div id="notifybox" class="noshow fx-position-stick zind-2 motion btscr" align="left">
	<div class="cs-width-400 white-theme pads20 bottom-push-30 left-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="rec-header-notification" class="large red-font"></h4>
		<small id="rec-message-notification" class="block-element top-push-10"></small>
	</div>
</div>


<script>

	function for_compl() {
		var compl = document.getElementById('fieldset11');
		if(compl.value == 'Yes') {
			objDisplay('show-complimentary');
			document.getElementById('complimentary').required = true;
			document.getElementById('corporate').required = false;
			$('#corporate').prop('selectedIndex', 0);
			$('#fieldset24').prop('selectedIndex', 0);
			objHidden('show-corporate');
		} else if(compl.value == 'No') {
			objHidden('show-complimentary');
			$('#complimentary').prop('selectedIndex', 0);
			document.getElementById('complimentary').required = false;
		}
	}


	function for_corp() {
		var compl = document.getElementById('fieldset24');
		if(compl.value == 'Yes') {
			objDisplay('show-corporate');
			document.getElementById('corporate').required = true;
			document.getElementById('complimentary').required = false;
			$('#complimentary').prop('selectedIndex', 0);
			$('#fieldset11').prop('selectedIndex', 0);
			objHidden('show-complimentary');

			alert("Notification\n\nPlease indicate amount charging corporate in the amount field only, no payment mode should be applied");
		} else if(compl.value == 'No') {
			objHidden('show-corporate');
			$('#corporate').prop('selectedIndex', 0);
			document.getElementById('corporate').required = false;
		}
	}


	function addon_membership() {
		var membership = document.getElementById('fieldset10');
		if(membership.value == 'Single') {
			objHidden('show-couple-membership');
			objHidden('show-family-membership');
		} else if(membership.value == 'Couple') {
			objDisplay('show-couple-membership');
			objHidden('show-family-membership');
		} else if(membership.value == 'Family') {
			objHidden('show-couple-membership');
			objDisplay('show-family-membership');
		}
	}


	function addchilds() {
		var child_container = document.getElementById('morechild'),
		span1 = document.createElement('span'),
		span2 = document.createElement('span'),
		span3 = document.createElement('span'),
		field1 = document.createElement('input'),
		field2 = document.createElement('input');
		label1 = document.createElement('small');
		label2 = document.createElement('small');

		span1.className = 'ln-display-box float-left nc-width-30 right-push-50 bottom-push-20';
		span2.className = 'ln-display-box float-left nc-width-30 right-push-50 bottom-push-20';
		span3.className = 'block-element new-line-space';

		label1.className = 'block-element bottom-push-5 left-pull-5 dark-grey-font';
		label2.className = 'block-element bottom-push-5 left-pull-5 dark-grey-font';

		field1.type = 'text';
		field1.placeholder = 'Name of child';
		field1.name = 'childname[]';

		field2.type = 'date';
		field2.name = 'childob[]';

		label1.innerHTML='Child Name';
		label2.innerHTML='Date of Birth';

		span1.appendChild(label1);
		span1.appendChild(field1);

		span2.appendChild(label2);
		span2.appendChild(field2);
		

		child_container.appendChild(span1);
		child_container.appendChild(span2);
		child_container.appendChild(span3);
	}

</script>