<?php
	$recreation_number = $ftoken;
	$ths_token = $stoken;

	$dataproperty = "id,recreation_number,photo,salutation,firstname,lastname,othernames,maritalstatus,gender,dob,nationality,emailaddress,mobile,membership_type,iscomplimentary,complimentary_src,profession,bodyheight,heightuom,bodyweight,weightuom,bloodgroup,genotype,officeaddress,officephone,homeaddress,plan,startdate,enddate,iscorporate,detail,workflow,isapproved,status";

	$recreation_selection_key = array("id"=>$ths_token,"deletedata"=>0);
	$get_recreation_data = mysqli_data_fetch($tbL105,$dataproperty,$recreation_selection_key,'noarray');

	#---------------------------------------------------------------------------------

	$show_form = "block-element";

	if(isset($_POST['submitbutton']) && isset($_POST['rowid']) && !empty($_POST['rowid'])) {

		$pst_query = array("id"=>$_POST['rowid']);
		$pst_field = array("membership_type"=>$_POST['membershiptype']);
		$result = mysqli_data_update($tbL105,$pst_field,$pst_query);

		if($result == 2) {

			$new_recreation_id = $_POST['rowid'];
			$recreation_number = $_POST['recreationumber'];

			#get user counter session id
			$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

			$totalamount = escape_data($_POST['amount']);
			$wgt_paymentmode = escape_data($_POST['payment-mode']);

			#update sales for open-counter
			if((isset($wgt_paymentmode) && $wgt_paymentmode > 0) && (isset($totalamount) && $totalamount > 0)) {
				
				$payment_dataproperty = array("recreation_number"=>$recreation_number,"memberid"=>$new_recreation_id,"mode"=>$wgt_paymentmode,"amount"=>$totalamount,"chequenumber"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$server_get_date,"userid"=>$userSignedIn,"startdate"=>$get_recreation_data[27],"enddate"=>$get_recreation_data[28],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL107,$payment_dataproperty,'');
				$new_payment_id = $mysqli_id;
				$invoice_number = $recreation_inv_prefix.$new_payment_id;

				$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
				$pay_data_key = array("id"=>$new_payment_id);
				mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);


				$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0);
				$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

				$new_collection = $sales_counter_data[0] + $totalamount;

				$sales_counter_sql = array("collection"=>$new_collection);
				mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
			}


			if(isset($_POST['membershiptype']) && $_POST['membershiptype'] == 'Couple') {
				$photo_data = $_POST['dataurl-cpl'];
				$spouse_name = escape_data($_POST['spousename']);
				$spouse_dob = $_POST['spousedob'];
			} elseif(isset($_POST['membershiptype']) && $_POST['membershiptype'] == 'Family') {
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
			$post_message = "Membership upgrade was successful. Check recreation dashboard";
			
			$islogfile = 1;
			$logfile_msg = "Recently upgraded recreation membership (".$recreation_number.")";
		}

		$show_form = "noshow";
	}


	$list_payment_modes = select_dt_fetch('',0,$tbL24,'id','name');
?>

<div class="pads20 <?php echo $show_form; ?>">
	<h3 class="large nobold default-text-font-bold">Recreation Upgrade</h3><br>
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<input type="hidden" name="recreationumber" value="<?php echo $recreation_number; ?>">
		<input type="hidden" name="rowid" value="<?php echo $ths_token; ?>">
		<fieldset>
			<legend><?php echo $get_recreation_data[4].' '.$get_recreation_data[5]; ?></legend>

			<h4 class="large nobold dark-grey-font bottom-pull-5">Type of Membership</h4>
			<select name="membershiptype" id="membershiptype" required="required" onchange="addon_membership()">
				<option value="" selected="selected">Choose</option>
				<option value="Couple">Couple</option>
				<option value="Family">Family</option>
			</select>

			<p>&nbsp;</p>

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

			<!--<div class="block-element nc-width-50 bottom-push-30">
				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
				<textarea name="remark" id="remark"></textarea>
			</div>-->

			<div class="block-element bottom-push-15">
				<h4 class="xlarge nobold left-pull-5 default-text-font-bold">Make Payment (Only if payment is applicable)</h4>
				<div class="block-element sml-rounded-button top-push-5 noscroll">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="150px" align="center">Mode</th>
							<th width="150px" align="center">Amount</th>
							<th width="150px" align="center">CC/Cheque No</th>
							<!--<th width="150px" align="center">Receipt</th>-->
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
							<!--<td width="150px" align="center">
								<input type="text" name="receipt" id="receipt">
							</td>-->
							<td width="200px" align="center">
								<input type="text" name="detail" id="detail">
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="block-element top-pull-20 bottom-push-15 alignct">
				<input type="submit" name="submitbutton" value="Apply Upgrade" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
			</div>

		</fieldset>
	</form>
</div>


<script>

	function addon_membership() {
		
		var membership = document.getElementById('membershiptype');
		
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