<?php
	$recreation_number = $ftoken;
	$ths_token = $stoken;

	$dataproperty = "id,recreation_number,photo,salutation,firstname,lastname,othernames,maritalstatus,gender,dob,nationality,emailaddress,mobile,membership_type,iscomplimentary,complimentary_src,profession,bodyheight,heightuom,bodyweight,weightuom,bloodgroup,genotype,officeaddress,officephone,homeaddress,plan,startdate,enddate,iscorporate,corporate_type,detail,workflow,isapproved,status";

	$recreation_selection_key = array("id"=>$ths_token,"deletedata"=>0);
	$get_recreation_data = mysqli_data_fetch($tbL105,$dataproperty,$recreation_selection_key,'noarray');

	#---------------------------------------------------------------------------------

	$show_form = "block-element";

	if(isset($_POST['modifybutton']) && isset($_POST['rowid']) && !empty($_POST['rowid'])) {

		$new_recreation_id = $_POST['rowid'];
		$recreation_number = $_POST['recreationumber'];

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


		if(isset($_POST['iscomplimentary']) && $_POST['iscomplimentary'] == 'Yes') {
			$complimentary_src = $_POST['complimentary'];
		} else {
			$complimentary_src = 0;
		}

		if(isset($_POST['iscorporate']) && $_POST['iscorporate'] == 'Yes') {
			$corporate_src = $_POST['corporate'];
		} else {
			$corporate_src = 0;
		}


		$recreation_plan = arrayget_key($recreation_duration,$_POST['duration']);
		$recreation_plan_due_date = date("Y-m-d",strtotime($_POST['effectivedate'].' +'.$recreation_plan));

		$pst_query = array("id"=>$new_recreation_id);
		$pst_field = array("iscomplimentary"=>$_POST['iscomplimentary'],"complimentary_src"=>$complimentary_src,"plan"=>$_POST['duration'],"startdate"=>$_POST['effectivedate'],"enddate"=>$recreation_plan_due_date,"iscorporate"=>$_POST['iscorporate'],"corporate_type"=>$corporate_src);

		$isdata = mysqli_data_update($tbL105,$pst_field,$pst_query);

		if($isdata == 2) {

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

			$smdl = "Recreation";

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Membership plan modified successfully. Check recreation dashboard";
			
			$islogfile = 1;
			$logfile_msg = "Recently modified recreation plan for recreation membership (".$recreation_number.")";
			

			$show_form = "noshow";
		}
	}


	$list_payment_modes = select_dt_fetch('',0,$tbL24,'id','name');


	if($get_recreation_data[29] == 'Yes' && $get_recreation_data[30] >= 1) {
		$cspg = idget_data($tbL58,$get_recreation_data[30],'name');
		$addLabel = " - Corp/Spl. Guest (".$cspg.")";
		$iscorp = $get_recreation_data[30];
	} else {
		$addLabel = "";
		$iscorp = 0;
	}

	$duration = arrayset_form($recreation_duration,'select');

	$complimentary = select_dt_fetch('status','Active',$tbL33,'id','name');
	$additionalQuery = " ORDER BY name ASC";
	$cspg = select_dt_fetch('',0,$tbL58,'id','name');
	$additionalQuery = "";

?>

<div class="pads20 <?php echo $show_form; ?>">
	<h3 class="large nobold default-text-font-bold">Modify Recreation Plan</h3><br>
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<input type="hidden" name="recreationumber" value="<?php echo $recreation_number; ?>">
		<input type="hidden" name="rowid" value="<?php echo $ths_token; ?>">
		<input type="hidden" name="forcspg" value="<?php echo $iscorp; ?>">
		<fieldset>
			<legend><?php echo $get_recreation_data[4].' '.$get_recreation_data[5].$addLabel; ?></legend>
			
			<p></p>

			<h3 class="large nobold light-red-font">* Please note that these changes will override the current plan. Check entry before submitting</h3><br>

			<ul class="nolist">
				<li class="ln-display-box float-left nc-width-45">
					
					<div class="block-element bottom-push-15">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Type of Membership <sup class="red-font">*</sup></small>
						<select name="membershiptype" id="membershiptype" required="required" onchange="addon_membership()">
							<option value="" selected="selected">Choose</option>
							<option value="Single">Single</option>
							<option value="Couple">Couple</option>
							<option value="Family">Family</option>
						</select>
					</div>

					<div class="block-element bottom-push-15">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">For Complimentary?</small>
						<select name="iscomplimentary" id="iscomplimentary" onchange="for_compl()">
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
						<select name="iscorporate" id="iscorporate" onchange="for_corp()">
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

				</li>
				<li class="ln-display-box float-left nc-width-10">
					&nbsp;
				</li>
				<li class="ln-display-box float-left nc-width-45">
					
					<div class="block-element bottom-push-15">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Start from (effective day)  <sup class="red-font">*</sup></small>
						<input type="date" name="effectivedate" id="effectivedate" required="required">
					</div>

					<div class="block-element bottom-push-15">
						<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Duration <sup class="red-font">*</sup></small>
						<select name="duration" id="duration" required="required">
							<option value="" selected="selected">Choose</option>
							<?php echo $duration; ?>
						</select>
					</div>

				</li>
				<li class="block-element new-line-space">
				</li>
			</ul>

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

			<div class="block-element top-pull-20 bottom-push-15 alignct">
				<input type="submit" name="modifybutton" value="Apply Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
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


	function for_compl() {
		var compl = document.getElementById('iscomplimentary');
		if(compl.value == 'Yes') {
			objDisplay('show-complimentary');
			document.getElementById('complimentary').required = true;
			document.getElementById('corporate').required = false;
			$('#corporate').prop('selectedIndex', 0);
			$('#iscorporate').prop('selectedIndex', 0);
			objHidden('show-corporate');
		} else if(compl.value == 'No') {
			objHidden('show-complimentary');
			$('#complimentary').prop('selectedIndex', 0);
			document.getElementById('complimentary').required = false;
		}
	}


	function for_corp() {
		var compl = document.getElementById('iscorporate');
		if(compl.value == 'Yes') {
			objDisplay('show-corporate');
			document.getElementById('corporate').required = true;
			document.getElementById('complimentary').required = false;
			$('#complimentary').prop('selectedIndex', 0);
			$('#iscomplimentary').prop('selectedIndex', 0);
			objHidden('show-complimentary');

			//alert("Notification\n\nPlease indicate amount charging corporate in the amount field only, no payment mode should be applied");
		} else if(compl.value == 'No') {
			objHidden('show-corporate');
			$('#corporate').prop('selectedIndex', 0);
			document.getElementById('corporate').required = false;
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