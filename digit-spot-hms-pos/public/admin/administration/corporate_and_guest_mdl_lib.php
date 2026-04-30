<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create corporate or guest by clicking <u>new client</u> button. All asterik or marked fields are compulsory
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Client
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>
	
	function isdiscountFixed() {
		var isdf = document.getElementById('fieldset10');
		var disc = document.getElementById('fieldset11');
		var obj = document.getElementById('isfixediscount');

		if(isdf.value == 'Yes') {
			obj.className = 'block-element top-push-7';
			disc.required = true;
		} else if(isdf.value == 'No') {
			obj.className = 'noshow top-push-7';
			disc.required = false;
			disc.value = '';
		}
	}

	function getother()
	{
		if(document.getElementById('others').lang == 'h')
		{
			document.getElementById('others').lang = 'o';
			document.getElementById('others').className = 'box-border-thick pads20 sml-rounded-button cs-height-2000 motion';
			document.getElementById('others-inner').className = 'block-element';
		}
		else if(document.getElementById('others').lang == 'o')
		{
			document.getElementById('others').lang = 'h';
			document.getElementById('others').className = 'box-border-thick sml-rounded-button cs-height-0 motion';
			document.getElementById('others-inner').className = 'noshow';
		}
	}

</script>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$get_payterm = select_dt_fetch('status','Active',$tbL41,'id','name');
	$get_country = select_dt_fetch('',0,$tbL64,'id','name');
	$get_salutation = select_dt_fetch('status','Active',$tbL42,'id','name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_56); //create a table for this post
		createDatabasetable($var_tbl_57); //create a table for this post
		createDatabasetable($var_tbl_58); //create a table for this post
		createDatabasetable($var_tbl_59); //create a table for this post
		createDatabasetable($var_tbl_60); //create a table for this post
		createDatabasetable($var_tbl_61); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		
		if($fieldset10 == 'Yes') { $fieldset11 = escape_data($_POST['fieldset11']); } else { $fieldset11 = 0; }
		
		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)),"email"=>$fieldset2,"mobile"=>$fieldset3,"code"=>strtoupper($fieldset4),"payterm"=>$fieldset5,"xcreditlimit"=>$fieldset6,"creditlimit"=>0,"notifylimit"=>$fieldset7,"isretainership"=>$fieldset8,"isdiscount"=>$fieldset9,"isfixeddiscount"=>$fieldset10,"discount"=>$fieldset11,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		$insert_constrain = array("name"=>ucwords(strtolower($fieldset1)),"code"=>strtoupper($fieldset4));
		$data_inserted = mysqli_data_insert($tbL58,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$new_corporate_id = $mysqli_id;

			//add packages selected
			if(isset($_POST['posstores'])) { 
				foreach($_POST['posstores'] as $pospackage) {
					$pospckg_arr = array("cspgid"=>$new_corporate_id,"posid"=>$pospackage);
					mysqli_data_insert($tbL61,$pospckg_arr,$pospckg_arr);
				}
			}

			//other info
			if(isset($_POST['moa_fieldset1']) && !empty($_POST['moa_fieldset1'])) {
				$infoid = 1;
				$moa_fieldset1 = escape_data($_POST['moa_fieldset1']);
			} else {
				$moa_fieldset1 = '';
			}

			if(isset($_POST['moa_fieldset2']) && !empty($_POST['moa_fieldset2'])) {
				$infoid = 1;
				$moa_fieldset2 = escape_data($_POST['moa_fieldset2']);
			} else {
				$moa_fieldset2 = '';
			}

			if(isset($_POST['moa_fieldset3']) && !empty($_POST['moa_fieldset3'])) {
				$infoid = 1;
				$moa_fieldset3 = escape_data($_POST['moa_fieldset3']);
			} else {
				$moa_fieldset3 = '';
			}

			if(isset($_POST['moa_fieldset4']) && !empty($_POST['moa_fieldset4'])) {
				$infoid = 1;
				$moa_fieldset4 = escape_data($_POST['moa_fieldset4']);
			} else {
				$moa_fieldset4 = '';
			}

			if(isset($_POST['moa_fieldset5']) && !empty($_POST['moa_fieldset5'])) {
				$infoid = 1;
				$moa_fieldset5 = escape_data($_POST['moa_fieldset5']);
			} else {
				$moa_fieldset5 = '';
			}

			if(isset($_POST['moa_fieldset6']) && !empty($_POST['moa_fieldset6'])) {
				$infoid = 1;
				$moa_fieldset6 = escape_data($_POST['moa_fieldset6']);
			} else {
				$moa_fieldset6 = '';
			}

			if(isset($_POST['check_moa']) && $_POST['check_moa'] == 'moa') {
				$infoid2 = 2;
				$sameas = 'yes';
			} else {
				$infoid2 = 2;
				$sameas = 'no';
				$mba_fieldset1 = escape_data($_POST['mba_fieldset1']);
				$mba_fieldset2 = escape_data($_POST['mba_fieldset2']);
				$mba_fieldset3 = escape_data($_POST['mba_fieldset3']);
				$mba_fieldset4 = escape_data($_POST['mba_fieldset4']);
				$mba_fieldset5 = escape_data($_POST['mba_fieldset5']);
				$mba_fieldset6 = escape_data($_POST['mba_fieldset6']);
				$mba_fieldset7 = escape_data($_POST['mba_fieldset7']);
			}

			if(isset($infoid) && $infoid == 1) {
				$moa_arr = array("cspgid"=>$new_corporate_id,"infoid"=>$infoid,"address1"=>$moa_fieldset1,"address2"=>$moa_fieldset2,"country"=>$moa_fieldset3,"state"=>$moa_fieldset4,"city"=>$moa_fieldset5,"pincode"=>$moa_fieldset6,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL59,$moa_arr,'');
			}

			if(isset($sameas) && $sameas == 'no') {
				$mba_arr = array("cspgid"=>$new_corporate_id,"infoid"=>$infoid2,"bcn"=>ucwords(strtolower($mba_fieldset1)),"address1"=>$mba_fieldset2,"address2"=>$mba_fieldset3,"country"=>$mba_fieldset4,"state"=>$mba_fieldset5,"city"=>$mba_fieldset6,"pincode"=>$mba_fieldset7,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL59,$moa_arr,'');
			}

			//end

			//other info 2
			if(isset($_POST['cpd_fieldset1']) && !empty($_POST['cpd_fieldset1'])) {
				$infoid3 = 1;
				$cpd_fieldset1 = escape_data($_POST['cpd_fieldset1']);
			} else {
				$cpd_fieldset1 = '';
			}

			if(isset($_POST['cpd_fieldset2']) && !empty($_POST['cpd_fieldset2'])) {
				$infoid3 = 1;
				$cpd_fieldset2 = escape_data($_POST['cpd_fieldset2']);
			} else {
				$cpd_fieldset2 = '';
			}

			if(isset($_POST['cpd_fieldset3']) && !empty($_POST['cpd_fieldset3'])) {
				$infoid3 = 1;
				$cpd_fieldset3 = escape_data($_POST['cpd_fieldset3']);
			} else {
				$cpd_fieldset3 = '';
			}

			if(isset($_POST['cpd_fieldset4']) && !empty($_POST['cpd_fieldset4'])) {
				$infoid3 = 1;
				$cpd_fieldset4 = escape_data($_POST['cpd_fieldset4']);
			} else {
				$cpd_fieldset4 = '';
			}

			if(isset($_POST['cpd_fieldset5']) && !empty($_POST['cpd_fieldset5'])) {
				$infoid3 = 1;
				$cpd_fieldset5 = escape_data($_POST['cpd_fieldset5']);
			} else {
				$cpd_fieldset5 = '';
			}

			if(isset($_POST['cpd_fieldset6']) && !empty($_POST['cpd_fieldset6'])) {
				$infoid3 = 1;
				$cpd_fieldset6 = escape_data($_POST['cpd_fieldset6']);
			} else {
				$cpd_fieldset6 = '';
			}

			if(isset($_POST['cpd_fieldset7']) && !empty($_POST['cpd_fieldset7'])) {
				$infoid3 = 1;
				$cpd_fieldset7 = escape_data($_POST['cpd_fieldset7']);
			} else {
				$cpd_fieldset7 = '';
			}

			if(isset($_POST['cpd_fieldset8']) && !empty($_POST['cpd_fieldset8'])) {
				$infoid3 = 1;
				$cpd_fieldset8 = escape_data($_POST['cpd_fieldset8']);
			} else {
				$cpd_fieldset8 = '';
			}


			if(isset($_POST['check_cpd']) && $_POST['check_cpd'] == 'cpd') {
				$infoid4 = 2;
				$sameas2 = 'yes';
			} else {
				$infoid4= 2;
				$sameas2 = 'no';
				$mbc_fieldset1 = escape_data($_POST['mbc_fieldset1']);
				$mbc_fieldset2 = escape_data($_POST['mbc_fieldset2']);
				$mbc_fieldset3 = escape_data($_POST['mbc_fieldset3']);
				$mbc_fieldset4 = escape_data($_POST['mbc_fieldset4']);
				$mbc_fieldset5 = escape_data($_POST['mbc_fieldset5']);
				$mbc_fieldset6 = escape_data($_POST['mbc_fieldset6']);
				$mbc_fieldset7 = escape_data($_POST['mbc_fieldset7']);
				$mbc_fieldset8 = escape_data($_POST['mbc_fieldset8']);
				$mbc_fieldset9 = escape_data($_POST['mbc_fieldset9']);
			}

			if(isset($infoid3) && $infoid3 == 1) {
				$cpd_arr = array("cspgid"=>$new_corporate_id,"infoid"=>$infoid3,"salutation"=>$cpd_fieldset1,"firstname"=>ucwords(strtolower($cpd_fieldset2)),"lastname"=>ucwords(strtolower($cpd_fieldset3)),"phone"=>$cpd_fieldset4,"fax"=>$cpd_fieldset5,"gender"=>$cpd_fieldset6,"dob"=>$cpd_fieldset7,"website"=>$cpd_fieldset8,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL60,$cpd_arr,'');
			}

			if(isset($sameas2) && $sameas2 == 'no') {
				$mbc_arr = array("cspgid"=>$new_corporate_id,"infoid"=>$infoid4,"salutation"=>$mbc_fieldset1,"firstname"=>ucwords(strtolower($mbc_fieldset2)),"lastname"=>ucwords(strtolower($mbc_fieldset3)),"phone"=>$mbc_fieldset4,"fax"=>$mbc_fieldset5,"mobile"=>$mbc_fieldset6,"gender"=>$mbc_fieldset7,"dob"=>$mbc_fieldset8,"website"=>$mbc_fieldset9,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL60,$mbc_arr,'');
			}

			//end

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new corporate/special guest","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{
		//$get_role = select_dt_fetch('status','Active',$tbL4,'id','role');

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-50">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Corporate / Special Guest</h3>
						</div>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="email" name="fieldset2" id="fieldset2" placeholder="Enter email address" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset3" id="fieldset3" placeholder="Enter mobile number" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter corporate/guest code" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset5" id="fieldset5" required="required">
									<option value="" selected="selected">Payment Term</option>
									<?php echo $get_payterm; ?>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset6" id="fieldset6" placeholder="Enter credit limit">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset7" id="fieldset7" placeholder="Enter notify limit">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-20">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset8" id="fieldset8" required="required">
									<option value="" selected="selected">Is Retainership?</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						
						<span class="block-element bottom-push-20 alignlt">
							<small class="blue-font"><b>Allow POS & Store</b></small>
						</span>
						<span class="block-element bottom-push-20">
							<?php
									
								$posstore_constrain = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
								$get_posstores = mysqli_data_fetch($tbL14,'id,posname,isfoodtype',$posstore_constrain,'array');

								if(is_array($get_posstores)) {
									foreach ($get_posstores as $poss_key => $poss_value) {
										?>
											<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">
												<span class="ln-display-box float-left nc-width-15">
													<input type="checkbox" name="posstores[]" value="<?php echo $poss_value["id"]; ?>">
												</span>
												<span class="ln-display-box float-left nc-width-85 alignlt">
													<?php echo $poss_value["posname"]; ?>
												</span>
												<span class="block-element new-line-space"></span> 
												<?php
													if($poss_value['isfoodtype'] == 'Yes') {
														?>
														<input type="checkbox" name="posstores[]" value="<?php echo $poss_value["id"]; ?>-1"> Breakfast &nbsp;<input type="checkbox" name="posstores[]" value="<?php echo $poss_value["id"]; ?>-2"> Lunch &nbsp;<input type="checkbox" name="posstores[]" value="<?php echo $poss_value["id"]; ?>-3"> Dinner
														<?php
													}
												?>
											</div>
										<?php
									}
								}

							?>
							<div class="block-element new-line-space">
							</div>
						</span>

						<span class="block-element bottom-push-20 alignlt">
							<small class="blue-font"><b>Discount Details</b></small>
						</span>
						<span class="block-element bottom-push-20">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset9" id="fieldset9" required="required">
									<option value="" selected="selected">Is Eligible for Discount?</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-20">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset10" id="fieldset10" required="required" onchange="isdiscountFixed()">
									<option value="" selected="selected">Is Fixed Discount?</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
								<span id="isfixediscount" class="noshow top-push-7">
									<div class="ln-display-box float-left nc-width-90">
										<input type="number" name="fieldset11" id="fieldset11" step="any" placeholder="Enter discount">
									</div>
									<div class="ln-display-box float-left nc-width-10 top-pull-5">
										&nbsp; %
									</div>
									<div class="block-element new-line-space">
									</div>
								</span>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>

						<span class="block-element bottom-push-20 alignlt left-pull-10">
							<a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="getother()"><u>Include more details</u></a>
						</span>
						<span class="block-element alignlt">
							<div id="others" class="box-border-thick sml-rounded-button cs-height-0 motion" lang="h">
								<div id="others-inner" class="noshow">
									<h4 class="large">+ Main Office Address</h4><br>
									<span class="block-element bottom-push-10">
										<input type="text" name="moa_fieldset1" id="moa_fieldset1" placeholder="Address Line 1">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="moa_fieldset2" id="moa_fieldset2" placeholder="Address Line 2">
									</span>
									<span class="block-element bottom-push-10">
										<select name="moa_fieldset3" id="moa_fieldset3" onchange="getdata('moa_fieldset4','eget-country-states-list','moa_fieldset3','dropbox');">
											<option value="" selected="selected">Country</option>
											<?php echo $get_country; ?>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<select name="moa_fieldset4" id="moa_fieldset4">
											<option value="" selected="selected">State</option>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="moa_fieldset5" id="moa_fieldset5" placeholder="City">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="moa_fieldset6" id="moa_fieldset6" placeholder="Pin Code">
									</span>
									
									<br>

									<h4 class="large">+ Main Billing Address</h4><br>
									<span class="block-element bottom-push-10">
										<small class="steel-blue-font"><input type="checkbox" name="check_moa" value="moa"> Same as main office address</small>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mba_fieldset1" id="mba_fieldset1" placeholder="Billing Company Name">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mba_fieldset2" id="mba_fieldset2" placeholder="Address Line 1">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mba_fieldset3" id="mba_fieldset3" placeholder="Address Line 2">
									</span>
									<span class="block-element bottom-push-10">
										<select name="mba_fieldset4" id="mba_fieldset4" onchange="getdata('mba_fieldset5','eget-country-states-list','mba_fieldset4','dropbox');">
											<option value="" selected="selected">Country</option>
											<?php echo $get_country; ?>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<select name="mba_fieldset5" id="mba_fieldset5">
											<option value="" selected="selected">State</option>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mba_fieldset6" id="mba_fieldset6" placeholder="City">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mba_fieldset7" id="mba_fieldset7" placeholder="Pin Code">
									</span>

									<span class="block-element top-push-10 bottom-push-20 box-border-thick-bottom">&nbsp;</span>

									<h4 class="large">+ Contact Person Details</h4><br>
									<span class="block-element bottom-push-10">
										<select name="cpd_fieldset1" id="cpd_fieldset1">
											<option value="" selected="selected">Salutation</option>
											<?php echo $get_salutation; ?>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset2" id="cpd_fieldset2" placeholder="First Name">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset3" id="cpd_fieldset3" placeholder="Last Name">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset4" id="cpd_fieldset4" placeholder="Office Phone">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset5" id="cpd_fieldset5" placeholder="Office Fax">
									</span>
									<span class="block-element bottom-push-10">
										<select name="cpd_fieldset6" id="cpd_fieldset6">
											<option value="" selected="selected">Gender</option>
											<option value="Male">Male</option>
											<option value="Female">Female</option>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset7" id="cpd_fieldset7" placeholder="Date of Birth" onclick="textodate('cpd_fieldset7')">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="cpd_fieldset8" id="cpd_fieldset8" placeholder="Website:">
									</span>

									<br>
		
									<h4 class="large">+ Main Billing Contact</h4><br>
									<span class="block-element bottom-push-10">
										<small class="steel-blue-font"><input type="checkbox" name="check_cpd" value="cpd"> Same as contact person</small>
									</span>
									<span class="block-element bottom-push-10">
										<select name="mbc_fieldset1" id="mbc_fieldset1">
											<option value="" selected="selected">Salutation</option>
											<?php echo $get_salutation; ?>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset2" id="mbc_fieldset2" placeholder="First Name">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset3" id="mbc_fieldset3" placeholder="Last Name">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset4" id="mbc_fieldset4" placeholder="Office Phone">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset5" id="mbc_fieldset5" placeholder="Office Fax">
									</span>
									<span class="block-element bottom-push-10">
										<input type="number" name="mbc_fieldset6" id="mbc_fieldset6" placeholder="Mobile">
									</span>
									<span class="block-element bottom-push-10">
										<select name="mbc_fieldset7" id="mbc_fieldset7">
											<option value="" selected="selected">Gender</option>
											<option value="Male">Male</option>
											<option value="Female">Female</option>
										</select>
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset8" id="mbc_fieldset8" placeholder="Date of Birth" onclick="textodate('mbc_fieldset7')">
									</span>
									<span class="block-element bottom-push-10">
										<input type="text" name="mbc_fieldset9" id="mbc_fieldset9" placeholder="Website:">
									</span>
								</div>
							</div>
						</span>
	
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL58,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change corporate/special guest status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		
		if($fieldset10 == 'Yes') { $fieldset11 = escape_data($_POST['fieldset11']); } else { $fieldset11 = 0; }
		$fieldset12 = escape_data($_POST['fieldset12']);
		
		$insert_dataproperty = array("name"=>ucwords(strtolower($fieldset1)),"email"=>$fieldset2,"mobile"=>$fieldset3,"code"=>strtoupper($fieldset4),"payterm"=>$fieldset5,"xcreditlimit"=>$fieldset6,"notifylimit"=>$fieldset7,"isretainership"=>$fieldset8,"isdiscount"=>$fieldset9,"isfixeddiscount"=>$fieldset10,"discount"=>$fieldset11);

		$insert_constrain = array("id"=>$fieldset12);
		$data_inserted = mysqli_data_update($tbL58,$insert_dataproperty,$insert_constrain);

		$new_corporate_id = $fieldset12;

		//add packages selected
		if(isset($_POST['posstores'])) { 
			foreach($_POST['posstores'] as $pospackage) {
				$pospckg_arr = array("cspgid"=>$new_corporate_id,"posid"=>$pospackage);
				$data_inserted2 = mysqli_data_insert($tbL61,$pospckg_arr,$pospckg_arr);
			}
		}

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if((isset($data_inserted) && $data_inserted == 2) || (isset($data_inserted2) && $data_inserted2 == 2))
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit corporate/special guest details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['chargetypebutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);

		$sql_query = array("id"=>$fieldset2);
		$sql_data = array("chargetype"=>$fieldset1);

		if(isset($fieldset1) && $fieldset1 == 'Corporate Tariff') {
			$isCorporatetariff = idget_fdata($tbL147,'corporateid',$fieldset2,'corporateid');
			if(isset($isCorporatetariff) && $isCorporatetariff >= 1) {
				mysqli_data_update($tbL58,$sql_data,$sql_query);
				$update_result .= '<span class="red-font">Charge type is updated successfully</span>';
			} else {
				$update_result .= '<span class="red-font">Operation is terminated! Corporate tariff has not been setup</span>';
			}
		} else {
			$isCorporatediscount = idget_data($tbL58,$fieldset2,'isdiscount');
			$isCorporatediscountfixed = idget_data($tbL58,$fieldset2,'isfixeddiscount');

			if((isset($isCorporatediscount) && isset($isCorporatediscountfixed)) && ($isCorporatediscount == 'No' && $isCorporatediscountfixed == 'No')) {
				$update_result .= '<span class="red-font">Operation is terminated! Corporate tariff has not been setup</span>';
			} else {
				mysqli_data_update($tbL58,$sql_data,$sql_query);
				$update_result .= '<span class="red-font">Charge type is updated successfully</span>';
			}
		}
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (name LIKE '%".escape_data($_POST['search'])."%' OR code LIKE '".escape_data($_POST['search'])."%')";
		$_SESSION['keywords'] = $keywords;
	} else { 
		if(isset($_SESSION['keywords'])) { $keywords = $_SESSION['keywords']; }
		else { $keywords = ""; }
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		if(isset($_SESSION['keywords'])) { unset($_SESSION['keywords']); }
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." ORDER BY name ASC LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 25;
		$additionalQuery = $keywords." ORDER BY name ASC LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,name,mobile,email,code,payterm,xcreditlimit,creditlimit,notifylimit,isretainership,isdiscount,isfixeddiscount,discount,chargetype,status,datelogged,timelogged";
	
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL58,$dataproperty,$constrain,$row);
	

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","code","name","email","credit balance","is retainership","charge type","status","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name or code" onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$htmlresult .= '</tr>';
		
		$num=$pgstart; $g=""; $dataid=""; $payterm="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["code"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["name"].'<small class="block-element top-push-5 bottom-push-5 ft-xsml-size"><a href="?logs=corporates&corporateuser='.$dataid.'&c=new" class="blue-font">Add User Login</a></small><small class="block-element bottom-push-5 ft-xsml-size"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">View/Edit</a></small></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["email"].'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">&#8358;'.number_format($tdata["creditlimit"],2).'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["isretainership"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right royal-blue-font">'.$tdata["chargetype"].'<p><a href="?logs='.$logs.'&chargetype='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="red-font ft-xsml-size"><u>Update</u></a></p></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			//$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			
			#--------------------------------------------------------------------------------------------------------
			#change charge type
			
			if((isset($_GET['chargetype']) && $_GET['chargetype'] >= 1) && ($_GET['chargetype'] == $dataid))
			{
				$fieldset = escape_data($_GET['chargetype']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Change Charge Type</h4><br>';
				$htmlresult .= '<div class="nc-width-80">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-40">';
				$htmlresult .= '<select name="fieldset1" id="fieldset1" required="required">';
				$htmlresult .= '<option value="" selected="selected">Choose Type</option>';
				$htmlresult .= '<option value="Corporate Tariff">Corporate Tariff</option><option value="On Discount">On Discount</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-40">';
				$htmlresult .= '<input type="hidden" name="fieldset2" id="fieldset2" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="chargetypebutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-60"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#--------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);
				$selected_payterm = idget_data($tbL41,$tdata["payterm"],'name');
				
				if($tdata["isfixeddiscount"] == 'Yes') { $showObj = ' block-element'; } else { $showObj = ' noshow'; }

				if(isset($_GET['trashpos']) && is_numeric($_GET['trashpos'])) {
					$trashpos = escape_data($_GET['trashpos']);
					$tps = array("id"=>$trashpos);
					trash_record($tbL61,$tps);
				}

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">View/Update '.$tdata["name"].' Information</h4><br>';
				$htmlresult .= '<div class="nc-width-80">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-30">';
				$htmlresult .= '<div class="cs-width-100 cs-height-100 rounded-element noscroll"><img src="'.DOMAIN_URL.'theme/images/general/photo.png" class="auto-wh"></div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Corporate/Special Guest</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" value="'.$tdata["name"].'" required="required">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Email Address</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="email" name="fieldset2" id="fieldset2" placeholder="Enter email address" value="'.$tdata["email"].'" required="required">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Mobile</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="number" name="fieldset3" id="fieldset3" placeholder="Enter mobile number" value="'.$tdata["mobile"].'" required="required">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Corporate/Special Guest Code</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter corporate/guest code" value="'.$tdata["code"].'" required="required">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Payment Term</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required">';
				$htmlresult .= '<option value="'.$tdata["payterm"].'" selected="selected">'.$selected_payterm.'</option>';
				$htmlresult .= $get_payterm;
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Credit Limit</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="number" name="fieldset6" id="fieldset6" placeholder="Enter credit limit" value="'.$tdata["xcreditlimit"].'">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '&nbsp;';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Notify Limit</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="number" name="fieldset7" id="fieldset7" placeholder="Enter notify limit" value="'.$tdata["notifylimit"].'">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '&nbsp;';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-20">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Retainership?</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<select name="fieldset8" id="fieldset8" required="required">';
				$htmlresult .= '<option value="'.$tdata["isretainership"].'" selected="selected">'.$tdata["isretainership"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-20 alignlt">';
				$htmlresult .= '<small class="blue-font"><b>Allow/Revoke POS & Store</b></small>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-20">';
				
				$posstore_constrain = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
				$get_posstores = mysqli_data_fetch($tbL14,'id,posname,isfoodtype',$posstore_constrain,'array');

				if(is_array($get_posstores)) {
					foreach ($get_posstores as $poss_key => $poss_value) {
						
						$ps_query = array("cspgid"=>$fieldset,"posid"=>$poss_value["id"]);
						$ps_exist = mysqli_data_fetch($tbL61,'id',$ps_query,'noarray');

						if(isset($ps_exist[0]) && $ps_exist[0] >= 1) {

							$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-15 left-pull-5">';
							$htmlresult .= '<a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&trashpos='.$ps_exist[0].'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="dark-black-font" title="Remove"><b class="fa-trash nobold"></b></a>';
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-85 alignlt">';
							$htmlresult .= $poss_value["posname"];
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="block-element new-line-space"></span>';

							if($poss_value['isfoodtype'] == 'Yes') {
								$xhtml = "";
								for($x=1; $x <= 3; $x++) {
									$rsx = $poss_value["id"].'-'.$x;
									$ps_queryx = array("cspgid"=>$fieldset,"posid"=>$rsx);
									$ps_existx = mysqli_data_checkr($tbL61,'(*)',$ps_queryx);
									if($ps_existx == true) { $xhtml .= $food_type[$x].' '; }
								}

								$htmlresult .= ' &nbsp;['.$xhtml.']';
							}

							$htmlresult .= '</div>';

						} else {
							$htmlresult .= '<div class="ln-display-box float-left nc-width-45 right-push-10 bottom-push-10 ft-xsml-size">';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-15">';
							$htmlresult .= '<input type="checkbox" name="posstores[]" value="'.$poss_value["id"].'">';
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="ln-display-box float-left nc-width-85 alignlt">';
							$htmlresult .= $poss_value["posname"];
							$htmlresult .= '</span>';
							$htmlresult .= '<span class="block-element new-line-space"></span>';

							if($poss_value['isfoodtype'] == 'Yes') {
								$htmlresult .= '<input type="checkbox" name="posstores[]" value="'.$poss_value['id'].'-1"> Breakfast &nbsp;';
								$htmlresult .= '<input type="checkbox" name="posstores[]" value="'.$poss_value['id'].'-2"> Lunch &nbsp;';
								$htmlresult .= '<input type="checkbox" name="posstores[]" value="'.$poss_value['id'].'-3"> Dinner';
							}

							$htmlresult .= '</div>';
						}
					}
				}

				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-20 alignlt">';
				$htmlresult .= '<small class="blue-font"><b>Discount Details</b></small>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-20">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Discount Eligible?</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<select name="fieldset9" id="fieldset9" required="required">';
				$htmlresult .= '<option value="'.$tdata["isdiscount"].'" selected="selected">'.$tdata["isdiscount"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-20">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Fixed Discount?</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<select name="fieldset10" id="fieldset10" required="required" onchange="isdiscountFixed()">';
				$htmlresult .= '<option value="'.$tdata["isfixeddiscount"].'" selected="selected">'.$tdata["isfixeddiscount"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '<span id="isfixediscount" class="top-push-7'.$showObj.'">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-90">';
				$htmlresult .= '<input type="number" name="fieldset11" id="fieldset11" step="any" placeholder="Enter discount" value="'.$tdata["discount"].'">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-10 top-pull-5">';
				$htmlresult .= '&nbsp; %';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-5 top-pull-10">';
				$htmlresult .= '<b class="fa-checker nobold ft-xsml-size"></b>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';

				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignct">';
				$htmlresult .= '<input type="hidden" name="fieldset12" id="fieldset12" value="'.$dataid.'">';
				$htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	$ukey = array("deletedata"=>0);
	mysqli_data_check($tbL58,'(*)',$ukey);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>