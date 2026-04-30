<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

//include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "module_operation_privilege.php";

$logs = $_GET['logs']; $smdl = "recreation";

$salutations = select_dt_fetch('status','Active',$tbL42,'id','name');
$duration = arrayset_form($recreation_duration,'select');
$list_payment_modes = select_dt_fetch('',0,$tbL24,'id','name');
$complimentary = select_dt_fetch('status','Active',$tbL33,'id','name');

#get user counter session id
$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="cs-height-100"></div>

<div class="white-theme top-pull-7 right-pull-30 left-pull-30 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		Note: here you can see list of recreation members
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="block-element pads30">

	<?php

		$isguestAct = 0;
		$saynotify = 0;
		$notifytype = 0;
		$islogfile = 0;
				
		$post_header = "";
		$post_message = "";
		$rrv_message = "";

		if(isset($_GET['id']) && isset($_GET['amt']) && isset($_GET['mode'])) {

			$rrv_id = escape_data($_GET['id']);
			$rrv_amount = escape_data($_GET['amt']);
			$rrv_mode = escape_data($_GET['mode']);
			$datelog = escape_data($_GET['datelog']);
			$userlog = escape_data($_GET['user']);

			$additionalQuery = " AND collection NOT IN(0)";
			$sales_counter_query = array("userid"=>$userlog,"fundid"=>$rrv_mode,"datelogged"=>$datelog);
			$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

			$additionalQuery = "";

			if($sales_counter_data[0] >= $rrv_amount) {
				
				$pst_query = array("id"=>$rrv_id);
				$pst_field = array("isreversed"=>1,"deletedata"=>1);
				mysqli_data_update($tbL107,$pst_field,$pst_query);

				$new_collection = $sales_counter_data[0] - $rrv_amount;
				$sales_counter_sql = array("collection"=>$new_collection);
				mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Payment reversal was performed successfully";

				$isguestAct = 1;
				$wgt_receipt = idget_data($tbL107,$rrv_id,'invoice_number');
				$pst_booking_number = idget_data($tbL107,$rrv_id,'recreation_number');
				$ths_guest_pry = idget_fdata($tbL105,'recreation_number',$pst_booking_number,'id');
				$remark_tag = "reverse"; $app_tag = "Recreation"; $session_tag = "Membership Account";
				$guestAct_msg = "Recreation membership ({$pst_booking_number}) payment of sum of ({$rrv_amount}) with receipt number ({$wgt_receipt}) was reversed";

			} else {
		
				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Payment reversal was cancelled due to insufficient funds";
			}

			unset($_GET['id']); unset($_GET['user']); unset($_GET['mode']);
		}
		
		if(isset($_POST['submitbutton'])) {

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

			$post_dataid = escape_data($_POST['datau']);
			

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
				$photo_data = $_POST['dataurl-cpl'];
				$spouse_name = escape_data($_POST['spousename']);
				$spouse_dob = $_POST['spousedob'];
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


			//$recreation_plan = arrayget_key($recreation_duration,$fieldset21);
			//$recreation_plan_due_date = date("Y-m-d",strtotime($recreation_plan));


			//save the registration
			$queryset = array("id"=>$post_dataid);
			$dataproperty = array("salutation"=>$fieldset1,"firstname"=>ucwords(strtolower($fieldset2)),"lastname"=>ucwords(strtolower($fieldset3)),"othernames"=>ucwords(strtolower($fieldset22)),"maritalstatus"=>$fieldset4,"gender"=>$fieldset5,"dob"=>$fieldset6,"nationality"=>ucwords(strtolower($fieldset7)),"emailaddress"=>$fieldset8,"mobile"=>$fieldset9,"membership_type"=>$fieldset10,"iscomplimentary"=>$fieldset11,"complimentary_src"=>$complimentary_src,"profession"=>ucwords(strtolower($fieldset12)),"bodyheight"=>$fieldset13,"heightuom"=>$fieldset13b,"bodyweight"=>$fieldset14,"weightuom"=>$fieldset14b,"bloodgroup"=>strtoupper($fieldset15),"genotype"=>strtoupper($fieldset16),"officeaddress"=>$fieldset17,"officephone"=>$fieldset18,"homeaddress"=>$fieldset19,"iscorporate"=>$fieldset24,"detail"=>$fieldset25,"isapproved"=>1,"status"=>1);

			if($fs_image !== null) {
				$dataproperty['photo'] = $fs_image;
			}

			$isdata = mysqli_data_update($tbL105,$dataproperty,$queryset);

			//if(isset($isdata) && $isdata == 2) {
				
				$new_recreation_id = $post_dataid;
				$member_queryset = array("memberid"=>$new_recreation_id);
				
				//for spouse data
				if(isset($spouse_name) && !empty($spouse_name)) {
					$spouse_dataproperty = array("listype"=>"spouse","flname"=>ucwords(strtolower($spouse_name)),"dob"=>$spouse_dob);
					if($ss_image !== null) {
						$spouse_dataproperty['photo'] = $ss_image;
						$member_queryset['listype'] = "spouse";
					}
					$wifey = mysqli_data_update($tbL106,$spouse_dataproperty,$member_queryset);

					if($wifey == 1) {
						$member_queryset2x = array("memberid"=>$new_recreation_id,"listype"=>"spouse");
						$spouse_dataproperty = array("memberid"=>$new_recreation_id,"photo"=>$ss_image,"listype"=>"spouse","flname"=>ucwords(strtolower($spouse_name)),"dob"=>$spouse_dob,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL106,$spouse_dataproperty,$member_queryset2x);
					}
				}
				
				//for children added
				$child_name = $_POST['childname']; $child_dob = $_POST['childob']; $child_dataproperty = "";

				for($ch=0; $ch <= count($child_name); $ch++) {
					if($child_name[$ch] != "" && $child_dob[$ch] != "") {
						$child_dataproperty = array("listype"=>"child","flname"=>ucwords(strtolower($child_name[$ch])),"dob"=>$child_dob[$ch],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL106,$child_dataproperty,$member_queryset);
					}
				}

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Recreation membership is updated successfully";
				
				$islogfile = 1;
				$logfile_msg = "Recreation membership was updated by this user";

			//}

		}

	

	
	?>
		
	<form action="" method="post" autocomplete="off">
		<span class="ln-display-box float-left">
			<h3 class="large">Recreation Member List</h3>
		</span>
		<span class="ln-display-box float-right nc-width-30">
			<div class="ln-display-box float-left nc-width-70">
				<input type="text" name="search" id="search" placeholder="Search by keywords" onkeyup="chgclass('sbtn','submit pads10 black-white-state sml-rounded-button motion')">
			</div>
			<div class="ln-display-box float-left nc-width-30 alignrt">
				<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">
			</div>
			<div class="block-element new-line-space">
			</div>
		</span>
		<span class="block-element new-line-space">
		</span>

		<?php
			$dataproperty = "id,recreation_number,photo,salutation,firstname,lastname,othernames,maritalstatus,gender,dob,nationality,emailaddress,mobile,membership_type,iscomplimentary,complimentary_src,profession,bodyheight,heightuom,bodyweight,weightuom,bloodgroup,genotype,officeaddress,officephone,homeaddress,plan,startdate,enddate,iscorporate,corporate_type,detail,workflow,isapproved,status,datelogged,timelogged";

			//for search keywords
			if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
				$keywords=" AND (firstname LIKE '".escape_data($_POST['search'])."%' OR lastname LIKE '".escape_data($_POST['search'])."%' OR recreation_number LIKE '".escape_data($_POST['search'])."' OR membership_type LIKE '".escape_data($_POST['search'])."')";
				//$_SESSION['keywords'] = $keywords;
			} else { 
				/*if(!isset($_GET['pg']) && isset($_SESSION['keywords'])) { $keywords = $_SESSION['keywords']; }
				else { $keywords=""; }*/
				$keywords="";
			}

			//pagination controller
			if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
				$curpage = $_GET['pg'];
				$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
				$additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
			} else {
				$curpage = 0;
				$pgstart = 0; $pglimit = 25;
				if(empty($keywords)) { $additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit; }
				else { $additionalQuery = $keywords." ORDER BY id DESC"; }
			}


			$recreation_selection_key = array("deletedata"=>0);
			$get_recreation_data = mysqli_data_fetch($tbL105,$dataproperty,$recreation_selection_key,'array');

			if(is_array($get_recreation_data)) {

				?>
					<div class="block-element sml-rounded-button noscroll top-push-20 bottom-push-20">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<th width="180px" align="center" class="box-border-thick-right">Recreation No.</th>
								<th width="180px" align="center" class="box-border-thick-right">Name</th>
								<th width="180px" align="center" class="box-border-thick-right">Membership Billing</th>
								<th width="180px" align="center" class="box-border-thick-right">Membership Type</th>
								<th width="200px" align="center" class="box-border-thick-right">Membership Period</th>
								<th width="150px" align="center" class="box-border-thick-right">Recent Payment</th>
								<th width="100px" align="center" class="box-border-thick-right">No of Kids</th>
								<th width="80px" align="center" class="box-border-thick-right">Status</th>
								
								<th width="30px" align="center" class="dark-black-theme">&nbsp;</th>
							</tr>

							<?php

								$num=0; $g=""; $dataid=""; $noofkids=0; $amountpaid=0; $status=""; $status_color=""; $approval_status=""; $billing = "";

								foreach ($get_recreation_data as $rcr_key => $rcr_value) {
									
									$num += 1;
									$g = $num / 2;

									$dataid = $rcr_value["id"];

									//get number of kids
									$kid_sql = "COUNT(id)";
									$kid_query = "memberid=".$dataid." AND listype IN('child')";
									$noofkids = mysqli_arithmetic_data($tbL106,$kid_sql,$kid_query);

									//get total payment
									$additionalQuery = " ORDER BY id DESC LIMIT 1";
									$payment_query = array("memberid"=>$dataid,"isreversed"=>0,"deletedata"=>0);
									$datapay = mysqli_data_fetch($tbL107,'amount',$payment_query,'noarray');
									$amountpaid = $datapay[0];

									$additionalQuery = "";


									$status = arrayget_key($record_status,$rcr_value['status']);
									$status_color = arrayget_key($color_notification,$rcr_value['status']);
									$approval_status = arrayget_key($record_approval,$rcr_value['isapproved']);

									$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

									if($rcr_value['iscorporate'] == 'Yes' || $rcr_value['corporate_type'] >= 1) {
										$billing = "Corporate";
										$billing .= " (".idget_data($tbL58,$rcr_value['corporate_type'],'name').")";
									} elseif($rcr_value['iscomplimentary'] == 'Yes' || $rcr_value['complimentary_src'] >= 1) {
										$billing = "Complimentary";
										$billing .= " (".idget_data($tbL33,$rcr_value['complimentary_src'],'name').")";
									} else {
										$billing = "Individual (Self)";
									}

									?>
										<tr bgcolor="<?php echo $trcolor; ?>">
											<td width="180px" align="center" class="box-border-thick-right">
												<a href="?logs=<?php echo $logs; ?>&dtl=<?php echo $dataid; ?>&pg=<?php echo $curpage; ?>&start=<?php echo $pgstart; ?>&limit=<?php echo $pglimit; ?>&#tr" class="blue-font add-bold"><?php echo $rcr_value['recreation_number']; ?></a></td>
											<td width="180px" align="center" class="box-border-thick-right"><?php echo $rcr_value['firstname'].' '.$rcr_value['lastname']; ?></td>
											<td width="180px" align="center" class="box-border-thick-right"><?php echo $billing; ?></td>
											<td width="180px" align="center" class="box-border-thick-right"><?php echo $rcr_value['membership_type']; ?></td>
											<td width="200px" align="center" class="box-border-thick-right"><?php echo date("d/m/Y",strtotime($rcr_value['startdate'])).' - '.date("d/m/Y",strtotime($rcr_value['enddate'])); ?></td>
											<td width="150px" align="center" class="box-border-thick-right"><?php echo number_format($amountpaid,2); ?></td>
											<td width="100px" align="center" class="box-border-thick-right"><?php echo $noofkids; ?></td>
											<td width="80px" align="center" class="box-border-thick-right" style="color: <?php echo $status_color; ?>"><?php echo $status; ?></td>
										
											<td width="30px" class="grey-theme" align="center"><input type="checkbox" name="checkers[]" value="<?php echo $dataid; ?>"></td>
										</tr>
									<?php

									if((isset($_GET['dtl']) && $_GET['dtl'] >= 1) && ($_GET['dtl'] == $dataid)) {
										
										if(!empty($rrv_message)) { ?><tr><td colspan="30" class="light-red-font" align="center"><?php echo $rrv_message; ?></td></tr><?php }

										$fieldset = escape_data($_GET['dtl']);

										if(isset($rcr_value['photo']) && !empty($rcr_value['photo'])) {
											$member_photo = DOMAIN_URL."theme/images/general/recreation-members/".$rcr_value['photo'];
										} else {
											$member_photo = DOMAIN_URL."theme/images/general/photo.png";
										}

										$sp_membr_selection_key = array("memberid"=>$fieldset,"listype"=>"spouse","deletedata"=>0);
										$sp_membr_data = mysqli_data_fetch($tbL106,'photo,flname,dob,id',$sp_membr_selection_key,'noarray');

										$chl_membr_selection_key = array("memberid"=>$fieldset,"listype"=>"child","deletedata"=>0);
										$chl_membr_data = mysqli_data_fetch($tbL106,'id,photo,flname,dob',$chl_membr_selection_key,'array');

										$pay_selection_key = array("memberid"=>$fieldset,"isreversed"=>0,"deletedata"=>0);
										//$additionalQuery = " ORDER BY id DESC LIMIT 1";
										$additionalQuery = " ORDER BY id DESC";
										$pay_data = mysqli_data_fetch($tbL107,'id,invoice_number,mode,amount,receipt,datelogged,paymentdate,userid,startdate,enddate,detail,deletedata',$pay_selection_key,'array');

										$get_salutation = idget_data($tbL42,$rcr_value['salutation'],'name');
										$get_plan = arrayget_key($recreation_duration,$rcr_value['plan']);
										

										switch($rcr_value['workflow']) {
											case 1:
												$work_flow = "Default";
												break;

											case 2:
												$work_flow = "Workflow";
												break;
											
											default:
												$work_flow = "N/A";
												break;
										}

										if(isset($sp_membr_data[0]) && !empty($sp_membr_data[0])) {
											$spouse_photo = DOMAIN_URL."theme/images/general/recreation-members/".$sp_membr_data[0];
										} else {
											$spouse_photo = DOMAIN_URL."theme/images/general/photo.png";
										}

										?>
											<tr>
												<td colspan="30">

													<?php
														if(!empty($post_message)) {
															?>
																<div class="pads15 light-red-theme white-font bottom-push-10">
																	<?php echo $post_message; ?>
																</div>
															<?php
														}
													?>

													<div id="tr" class="block-element grey-1-theme pads30">
														<span class="float-left">
															<?php if(isset($allowRRupgrade) && $allowRRupgrade == 200): ?>
															<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 green-white-state rounded-button default-text-font-bold" onclick="popmodalframe('recreation','recreation_upgrade','<?php echo $rcr_value['recreation_number']; ?>','<?php echo $dataid; ?>',900,1200)">Upgrade</a>
															<?php endif; ?>
															&nbsp;&nbsp;
															<?php if(isset($allowRRpayment) && $allowRRpayment == 200): ?>
															<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 black-white-state rounded-button default-text-font-bold" onclick="popmodalframe('recreation','recreation_payment','<?php echo $rcr_value['recreation_number']; ?>','<?php echo $dataid; ?>',800,700)">Add Payment</a>
															<?php endif; ?>
															&nbsp;&nbsp;
															<?php if(isset($allowRRplan) && $allowRRplan == 200): ?>
															<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 orange-white-state rounded-button default-text-font-bold" onclick="popmodalframe('recreation','recreation_plan','<?php echo $rcr_value['recreation_number']; ?>','<?php echo $dataid; ?>',900,1200)">Modify Plan</a>
															<?php endif; ?>
														</span>
														<p class="bottom-pull-10 right-pull-30 alignrt">
															<a href="javascript:void(0)" class="blue-font" onclick="window.print()"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print Datasheet</a><a href="recreation/receipt.php?member=<?php echo $fieldset; ?>" class="blue-font left-push-30"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print Receipt</a>
														</p>

														<div id="section-to-print" class="block-element">
															<p class="alignct"><img src="<?php echo _FC_LOGO; ?>"></p>
															<h1 class="large alignct nomargin">Recreation Centre - <?php echo _LONG_NAME; ?></h1>
															<h1 class="large nobold alignct">Membership Datasheet (<?php echo $rcr_value['recreation_number']; ?>)</h1>

															<small class="block-element top-push-5 bottom-push-30 dark-grey-font alignct">(Information provided in this form will be treated as confidential)</small>

															<div class="block-element" align="center">
															<div class="block-element nc-width-80 alignlt">
																<span class="ln-display-box float-left nc-width-25 right-push-30">
																	<div id="imagebox" class="block-element cs-height-200 grey-theme box-border-thick bottom-push-10 noscroll alignct">
																		<img src="<?php echo $member_photo; ?>" class="auto-wh">
																	</div>
																	<small class="block-element anchor blue-font" onclick="document.getElementById('f').click()">Change Photograph</small>
																	<input type="hidden" name="dataurl" id="dataurl">
																	<small id="fmsg" class="block-element red-font top-push-5 alignlt"></small>
																	<input onchange="resizeimage(event,250,250,'dataurl','notupload','cimg','imagebox'); writeObjheader('fmsg','attaching image..')" type="file" id="f" style="position: fixed; top: -100em">
																</span>
																<span class="ln-display-box float-left nc-width-30 right-push-50">
																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Title</small>
																		<select name="fieldset1" id="fieldset1" required="required">
																			<option value="<?php echo $rcr_value['salutation']; ?>" selected="selected"><?php echo $get_salutation; ?></option>
																			<?php echo $salutations; ?>
																		</select>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">First Name</small>
																		<input type="text" name="fieldset2" id="fieldset2" value="<?php echo $rcr_value['firstname']; ?>" placeholder="firstname?" required="required">
																	</div>
																	
																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Last Name</small>
																		<input type="text" name="fieldset3" id="fieldset3" value="<?php echo $rcr_value['lastname']; ?>" placeholder="lastname?" required="required">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Marital Status</small>
																		<select name="fieldset4" id="fieldset4" required="required">
																			<option value="<?php echo $rcr_value['maritalstatus']; ?>" selected="selected"><?php echo $rcr_value['maritalstatus']; ?></option>
																			<option value="Single">Single</option>
																			<option value="Married">Married</option>
																		</select>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Sex</small>
																		<select name="fieldset5" id="fieldset5" required="required">
																			<option value="<?php echo $rcr_value['gender']; ?>" selected="selected"><?php echo $rcr_value['gender']; ?></option>
																			<option value="Male">Male</option>
																			<option value="Female">Female</option>
																		</select>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
																		<input type="text" name="fieldset6" id="fieldset6" value="<?php echo $rcr_value['dob']; ?>" onclick="textodate('fieldset6')">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Nationality</small>
																		<input type="text" name="fieldset7" id="fieldset7" value="<?php echo $rcr_value['nationality']; ?>" placeholder="nationality?" required="required">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Email Address</small>
																		<input type="text" name="fieldset8" id="fieldset8" value="<?php echo $rcr_value['emailaddress']; ?>">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Mobile Number</small>
																		<input type="number" name="fieldset9" id="fieldset9" value="<?php echo $rcr_value['mobile']; ?>" placeholder="mobile number?" required="required">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Type of Membership</small>
																		<select name="fieldset10" id="fieldset10" required="required">
																			<option value="<?php echo $rcr_value['membership_type']; ?>" selected="selected"><?php echo $rcr_value['membership_type']; ?></option>
																		</select>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Complimentary</small>
																		<select name="fieldset11" id="fieldset11">
																			<option value="<?php echo $rcr_value['iscomplimentary']; ?>"><?php echo $rcr_value['iscomplimentary']; ?></option>
																		</select>
																	</div>

																</span>
																<span class="ln-display-box float-left nc-width-30">
																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Profession</small>
																		<input type="text" name="fieldset12" id="fieldset12" value="<?php echo $rcr_value['profession']; ?>" placeholder="profession?" required="required">
																	</div>
																	
																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Height</small>
																		<span class="ln-display-box float-left nc-width-30">
																			<input type="text" name="fieldset13" id="fieldset13" value="<?php echo $rcr_value['bodyheight']; ?>" placeholder="0">
																		</span>
																		<span class="ln-display-box float-left nc-width-70">
																			<select name="fieldset13b" id="fieldset13b">
																				<option value="<?php echo $rcr_value['heightuom']; ?>"><?php echo $rcr_value['heightuom']; ?></option>
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
																			<input type="text" name="fieldset14" id="fieldset14" value="<?php echo $rcr_value['bodyweight']; ?>" placeholder="0">
																		</span>
																		<span class="ln-display-box float-left nc-width-70">
																			<select name="fieldset14b" id="fieldset14b">
																				<option value="<?php echo $rcr_value['weightuom']; ?>"><?php echo $rcr_value['weightuom']; ?></option>
																				<option value="Kgs">Kgs</option>
																				<option value="Lbs">Lbs</option>
																			</select>
																		</span>
																		<span class="block-element new-line-space">
																		</span>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Blood Group</small>
																		<input type="text" name="fieldset15" id="fieldset15" value="<?php echo $rcr_value['bloodgroup']; ?>">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Genotype</small>
																		<input type="text" name="fieldset16" id="fieldset16" value="<?php echo $rcr_value['genotype']; ?>">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Company Address </small>
																		<textarea name="fieldset17" id="fieldset17" required="required"><?php echo $rcr_value['officeaddress']; ?></textarea>
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Phone Number</small>
																		<input type="number" name="fieldset18" id="fieldset18" value="<?php echo $rcr_value['officephone']; ?>">
																	</div>

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Residential Address </small>
																		<textarea name="fieldset19" id="fieldset19" required="required"><?php echo $rcr_value['homeaddress']; ?></textarea>
																	</div>

																	<!--<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Start from (effectiveness) </small>
																		<input type="text" name="fieldset20" id="fieldset20" value="<?php //echo $rcr_value['startdate']; ?>" required="required" onclick="textodate('fieldset20')">
																	</div>-->

																	<!--<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Duration</small>
																		<select name="fieldset21" id="fieldset21" required="required">
																			<option value="<?php //echo $rcr_value['plan']; ?>" selected="selected"><?php //echo $get_plan; ?></option>
																			<?php //echo $duration; ?>
																		</select>
																	</div>-->

																	<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Other Names</small>
																		<input type="text" name="fieldset22" id="fieldset22" value="<?php echo $rcr_value['othernames']; ?>">
																	</div>

																	<!--<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Workflow</small>
																		<select name="fieldset23" id="fieldset23">
																			<option value="<?php //echo $rcr_value['workflow']; ?>"><?php //echo $work_flow; ?></option>
																		</select>
																	</div>-->

																	<!--<div class="block-element bottom-push-15">
																		<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Corporate Type</small>
																		<select name="fieldset24" id="fieldset24">
																			<option value="<?php //echo $rcr_value['iscorporate']; ?>"><?php //echo $rcr_value['iscorporate']; ?></option>
																		</select>
																	</div>-->
																</span>
																<span class="block-element new-line-space">
																</span>
																
																<div id="addon-forms" class="block-element bottom-push-15">
																	<div id="show-couple-membership" class="block-element box-border-thick sml-rounded-button pads15 noscroll">
																		<h3 class="large">Couple/Family Membership Details</h3><br>
																		<h4 class="large nobold">&bull; Spouse</h4><br>
																		<span class="ln-display-box float-left nc-width-25 right-push-50">
																			<div id="imagebox-cpl" class="block-element cs-height-200 grey-theme box-border-thick bottom-push-10 noscroll alignct">
																				<img src="<?php echo $spouse_photo; ?>" class="auto-wh">
																			</div>
																			<small class="block-element anchor blue-font" onclick="document.getElementById('cpl').click()">Change Photograph</small>
																			<input type="hidden" name="dataurl-cpl" id="dataurl-cpl">
																			<small id="fmsg-cpl" class="block-element red-font top-push-5 alignlt"></small>
																			<input onchange="resizeimage(event,250,250,'dataurl-cpl','notupload','cimg','imagebox-cpl'); writeObjheader('fmsg-cpl','image attached..')" type="file" id="cpl" style="position: fixed; top: -100em">
																		</span>
																		<span class="ln-display-box float-left nc-width-30">
																			<div class="block-element bottom-push-10">
																				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Name of Spouse</small>
																				<input type="text" name="spousename" id="spousename" value="<?php echo $sp_membr_data[1]; ?>">
																			</div>
																			<div class="block-element bottom-push-10">
																				<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
																				<input type="text" name="spousedob" id="spousedob" value="<?php echo $sp_membr_data[2]; ?>" onclick="textodate('spousedob')">
																			</div>
																		</span>
																		<span class="block-element new-line-space">
																		</span>

																		<?php
																			if(is_array($chl_membr_data)) {
																				?>
																					<br><h4 class="large nobold">&bull; Children</h4><br>
																				<?php
																				foreach ($chl_membr_data as $chl_key => $chl_value) {
																					?>
																						<span class="ln-display-box float-left nc-width-30 right-push-50 bottom-push-20">
																							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Child Name</small>
																							<input type="text" name="childname[]" value="<?php echo $chl_value['flname']; ?>" placeholder="Name of child">
																						</span>
																						<span class="ln-display-box float-left nc-width-30 bottom-push-20">
																							<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Date of Birth</small>
																							<input type="text" name="childob[]" value="<?php echo $chl_value['dob']; ?>" id="childob<?php echo $chl_value['id']; ?>" onclick="textodate('childob<?php echo $chl_value['id']; ?>')">
																						</span>
																						<span class="block-element new-line-space">
																						</span>
																					<?php
																				}
																			}
																		?>

																	</div>
																</div>

																<div class="block-element nc-width-50 bottom-push-30">
																	<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Remarks</small>
																	<textarea name="fieldset25" id="fieldset25"><?php echo $rcr_value['detail']; ?></textarea>
																</div>

																<div class="block-element bottom-push-30">
																	<small class="block-element bottom-push-7 left-pull-5 dark-grey-font add-bold">Payment Details</small>
																	<div class="block-element sml-rounded-button noscroll">
																		<table cellpadding="0" cellspacing="0">
																			<tr>
																				<th width="150px" align="center">Invoice No.</th>
																				<th width="150px" align="center">Amount (&#8358;)</th>
																				<th width="180px" align="center">Description</th>
																				<th width="150px" align="center">Mode</th>
																				<th width="180px" align="center">Received By</th>
																				<!--<th width="180px" align="center">Created On</th>-->
																				<th width="180px" align="center">Payment Date</th>
																				<th width="100px" align="center">&nbsp;</th>
																			</tr>
																			<?php
																			if(is_array($pay_data)):
																				foreach($pay_data as $key => $val):
																					
																					if($val['mode'] == 111) {
																						$get_payment_mode = "Charged to Corporate";
																					} else {
																						$get_payment_mode = idget_data($tbL24,$val['mode'],'name');
																					}
																					
																					$get_issuer = idget_data($tbL7,$val['userid'],'staffname');
																			?>
																					
																			<tr>
																				<td width="150px" align="center">
																					<a href="recreation/receipt.php?member=<?php echo $fieldset; ?>&receipt=<?php echo $val['invoice_number']; ?>"><?php echo $val['invoice_number']; ?></a>
																				</td>
																				<td width="150px" align="center">
																					<?php echo number_format($val['amount'],2); ?>
																				</td>
																				<td width="180px" align="center">
																					<?php echo $val['detail']; ?>
																				</td>
																				<td width="150px" align="center">
																					<?php echo $get_payment_mode; ?>
																				</td>
																				<td width="180px" align="center">
																					<?php echo $get_issuer; ?>
																				</td>
																				<!--<td width="180px" align="center">
																					<?php //echo date("d/m/Y",strtotime($val['datelogged'])); ?>
																				</td>-->
																				<td width="180px" align="center">
																					<?php echo date("d/m/Y",strtotime($val['paymentdate'])); ?>
																				</td>
																				
																				<td width="100px" align="center">
																					<?php if(isset($allowRRv) && $allowRRv == 200): ?>
																						<a href="javascript:void(0)" class="blue-font" title="Reverse Payment" onclick="doReverse(<?php echo $val['id']; ?>,'<?php echo $val['amount']; ?>',<?php echo $val['mode']; ?>,'<?php echo $val['datelogged']; ?>',<?php echo $val['userid']; ?>)">Reverse</a>
																					<?php else: ?>
																						<b class="nobold dark-grey-font">****</b>
																					<?php endif; ?>
																				</td>
																			</tr>

																			<?php

																				$get_payment_mode = "";
																				$get_issuer = "";

																				endforeach;
																			endif;
																			?>
																		</table>
																	</div>
																</div>
																<div class="block-element bottom-push-30">
																	<h2 class="large">Approval</h2>
																	*******
																</div>
															</div>
															</div>
														</div>
														<div class="block-element top-pull-20 bottom-push-15 alignct pads20 dark-grey-theme">
															<input type="hidden" name="datau" id="datau" value="<?php echo $fieldset; ?>">
															<input type="submit" name="submitbutton" value="Save Changes" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
														</div>
													</div>
												</td>
											</tr>
										<?php
									}
								}
							?>

						</table>
					</div>
				<?php

				//paginate this page

				$additionalQuery = "";
				mysqli_data_check($tbL105,'(*)',$recreation_selection_key);
				$totalcount = $numOfrows;

				$paginate = data_pagenation(25,0,$totalcount);
				if(isset($paginate) && !empty($paginate)) {
					echo $paginate;
				}

				//end of pagination

			} else {
				?>
					<small class="block-element top-push-50 alignct dark-grey-font">There are no memberships at the moment</small>
				<?php
			}

		?>
	</form>
</div>

<?php $pageurl = '?logs='.$logs; ?>
<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<?php

	##create a log file
	if(isset($islogfile) && $islogfile == 1) {
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$logfile_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL8,$log_datasets,'');
	}


	##log guest activities
	if(isset($isguestAct) && $isguestAct == 1) {
		$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$ths_guest_pry,"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		if(isset($remark_tag) && !empty($remark_tag)) { $guest_activities_dataproperty['remark_tag'] = $remark_tag; }
		if(isset($app_tag) && !empty($app_tag)) { $guest_activities_dataproperty['app_tag'] = $app_tag; }
		if(isset($session_tag) && !empty($session_tag)) { $guest_activities_dataproperty['session_tag'] = $session_tag; }
		
		mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
	}
?>

<script>
	
	function doReverse(id,amount,mode,datelog,user) {
		var conf = confirm('Are you sure you want to reverse this payment?');
		if(conf == true) {
			var uri = (window.location.href).replace('&#tr','');
			window.location.href = uri+'&id='+id+'&amt='+amount+'&mode='+mode+'&datelog='+datelog+'&user='+user+'&#tr';
		}
	}


	const _Alertmsg = "<?php echo $saynotify; ?>";

	window.onload = () => {
		if(_Alertmsg == 1) {
			alert("<?php echo $post_header; ?>\n<?php echo $post_message; ?>");
		}
	}
	
</script>