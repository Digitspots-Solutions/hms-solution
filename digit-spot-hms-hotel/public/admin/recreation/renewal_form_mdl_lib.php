<?php $smdl = "recreation"; $logs = escape_data($_GET['logs']); $amdl = 10; include "get_avail_workflow.php"; ?>

<div class="block-element">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can renew recreation membership account. Select account to continue
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	$recreation_selection_key = array("deletedata"=>0,"status"=>1);
	$get_recreation_data = mysqli_data_fetch($tbL105,'id,recreation_number,salutation,firstname,lastname',$recreation_selection_key,'array');

	if(is_array($get_recreation_data)) {
		$rcr_account = ''; $get_salutation = '';
		foreach ($get_recreation_data as $rcr_key => $rcr_value) {
			$get_salutation = idget_data($tbL42,$rcr_value['salutation'],'name');
			$rcr_account .= '<option value="'.$rcr_value['id'].'">'.$get_salutation.' '.$rcr_value['firstname'].' '.$rcr_value['lastname'].' ('.$rcr_value['recreation_number'].')</option>';
		}
	}

	$member_id = 0;

	if(isset($_GET['members']) && !empty($_GET['members'])) {
		$isSearched = 1;
		$getmemberby = escape_data($_GET['members']);
		$additionalQuery = " AND (recreation_number REGEXP '^{$getmemberby}' OR firstname REGEXP '^{$getmemberby}' OR lastname REGEXP '^{$getmemberby}')";
		$member_selection_key = array("deletedata"=>0);
		$dataproperty = "id,photo,salutation,firstname,lastname,membership_type,plan,startdate,enddate,recreation_number";
		$get_member_data = mysqli_data_fetch($tbL105,$dataproperty,$member_selection_key,'array');
	} else {
		$isSearched = 0;
		$get_member_data = "";
	}


	if(isset($_GET['member']) && is_numeric($_GET['member'])) {
		$member_id = 1; $isSearched = 0;
		$getmemberby = escape_data($_GET['member']);
		$member_selection_key = array("id"=>$getmemberby);
		$dataproperty = "id,photo,salutation,firstname,lastname,membership_type,plan,startdate,enddate,recreation_number,iscorporate,corporate_type,workflow,isapproved,status";
		$get_member_data = mysqli_data_fetch($tbL105,$dataproperty,$member_selection_key,'noarray');
	}


?>

	<div class="block-element bottom-pull-30 bottom-push-30" align="center">
		<div class="nc-width-80" align="left">
			<div class="cs-height-50"></div>
			<h3 class="large nobold default-text-font-bold">Search by recreation number or membership name, then press <u>enter-key</u> button</h3><br>
			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">	
				<fieldset>
					<legend class="cs-width-350">
						<input type="text" name="memberlist" id="memberlist" placeholder="Search here.." onkeyup="show_member(event)">
							<!--<select name="member-list" id="member-list" required="required" onchange="show_member()">
								<option value="" selected="selected">Choose Member</option>
								<?php //echo $rcr_account; ?>
							</select>-->
						<input type="hidden" name="recreation-number" id="recreation-number" value="<?php echo $get_member_data[9]; ?>">
					</legend>
					
					<div class="block-element pads20">
						<?php

							if(isset($_POST['submitbutton'])) {
								
								if(isset($_POST['amount']) && !empty($_POST['amount'])) {
									
									$fieldset1 = escape_data($_POST['fieldset1']);
									$fieldset2 = escape_data($_POST['fieldset2']);

									$new_recreation_id = $_GET['member'];
									$recreation_numer = idget_data($tbL105,$new_recreation_id,'recreation_number');

									$recreation_plan = arrayget_key($recreation_duration,$fieldset2);
									$recreation_plan_due_date = date("Y-m-d",strtotime($fieldset1.' +'.$recreation_plan));

									$data_key = array("id"=>$new_recreation_id);
									$update_dataproperty = array("startdate"=>$fieldset1,"enddate"=>$recreation_plan_due_date,"plan"=>$fieldset2,"datelogged"=>$server_get_date,"archivedata"=>1,"status"=>1);
									$isdata = mysqli_data_update($tbL105,$update_dataproperty,$data_key);

								
									
									$wgt_paymentmode = $_POST['payment-mode'];
									$totalamount = $_POST['amount'];

									//for corporate
									$corporate_src = idget_data($tbL105,$new_recreation_id,'corporate_type');

									if(isset($totalamount) && !empty($totalamount) && $totalamount > 0 && $corporate_src >= 1) {

										#retrieve group creditlimt
										$credit_limit = idget_data($tbL58,$corporate_src,'creditlimit');
										$new_creditlimit = $credit_limit - $totalamount;

										#update group creditlimt
										$blc_selection_key = array("id"=>$corporate_src);
										$crl_datasets = array("creditlimit"=>$new_creditlimit);
										mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

										$transaction_desc = "Recreation renewal for recreation number ({$recreation_numer})";

										$ledger_dataquery = array("cspgid"=>$corporate_src,"transaction_number"=>$recreation_numer,"transaction_type"=>"Debit","datelogged"=>$server_get_date,"deletedata"=>0);
										
										$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$corporate_src,"transaction_number"=>$recreation_numer,"transaction_type"=>"Debit","amount"=>$totalamount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"recreation","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
										mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);

										//for payment / bill
										$payment_dataproperty = array("recreation_number"=>$recreation_numer,"memberid"=>$new_recreation_id,"mode"=>111,"amount"=>escape_data($totalamount),"chequenumber"=>escape_data($_POST['cheque-number']),"receipt"=>escape_data($_POST['receipt']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$server_get_date,"userid"=>$userSignedIn,"startdate"=>$fieldset1,"enddate"=>$recreation_plan_due_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
										mysqli_data_insert($tbL107,$payment_dataproperty,'');
										$new_payment_id = $mysqli_id;
										$invoice_number = $recreation_inv_prefix.$new_payment_id;

										$pay_data_key = array("id"=>$new_payment_id);
										$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
										mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);
									}

									//for counter user
									
									if((isset($_POST['payment-mode']) && $_POST['payment-mode'] > 0) && (isset($totalamount) && $totalamount > 0)) {
										
										$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

										$new_collection = $sales_counter_data[0] + $totalamount;

										$sales_counter_sql = array("collection"=>$new_collection);
										mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

										//for payment / bill
										$payment_dataproperty = array("recreation_number"=>$recreation_numer,"memberid"=>$new_recreation_id,"mode"=>$_POST['payment-mode'],"amount"=>escape_data($totalamount),"chequenumber"=>escape_data($_POST['cheque-number']),"receipt"=>escape_data($_POST['receipt']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$server_get_date,"userid"=>$userSignedIn,"startdate"=>$fieldset1,"enddate"=>$recreation_plan_due_date,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
										mysqli_data_insert($tbL107,$payment_dataproperty,'');
										$new_payment_id = $mysqli_id;
										$invoice_number = $recreation_inv_prefix.$new_payment_id;

										$pay_data_key = array("id"=>$new_payment_id);
										$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
										mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);
									}


									//generate information for guest transaction flow

									$biller = 1;

									$createdby = $userSignedIn;
									$transaction_flow_number = $invoice_number; $transaction_type = "Credit";
									$guest_number = $new_recreation_id; $sales_point = 6;
									$sales_description = "Recreation membership renewal";
									$transaction_amount = $_POST['amount']; $balance_bfw = 0; $transaction_payment_mode = $_POST['payment-mode'];
									if(isset($_POST['cheque-number']) && !empty($_POST['cheque-number'])) { $cheque_number = $_POST['cheque-number']; } else { $cheque_number = ""; }

									include "guest_transaction_flow.php";

									//create a log file
									$log_message = "Recently renewed recreation membership (".$_POST['recreation-number'].")";
									$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

									?>
										<script>
											alert('Renewal was done successfully! Go to recreation list to print');
										</script>
									<?php
								} else {
									?>
										<script>
											alert('Unable to process your request. Ensure payment is indicated');
										</script>
									<?php
								}
							}

							if(is_array($get_member_data) && count($get_member_data) > 0) {
								if(isset($isSearched) && $isSearched == 1) {
								
									$member_photo = "";
									
									?>
										<table cellpadding="10" cellspacing="5">
											<?php
												foreach($get_member_data as $key => $val) {
													
													if(isset($val['photo']) && !empty($val['photo'])) {
														$member_photo = DOMAIN_URL."theme/images/general/recreation-members/".$val['photo'];
													} else {
														$member_photo = DOMAIN_URL."theme/images/general/photo.png";
													}

													?>
														<tr>
															<td class="cs-width-80"><div class="cs-width-80 noscroll"><img src="<?php echo $member_photo; ?>" class="auto-wh"></div></td>
															<td class="top-pull-10 left-pull-20"><h3 class="large nobold default-text-font-bold"><?php echo $val['firstname'].' '.$val['lastname']; ?></h3><p class="top-pull-3"><a href="?logs=<?php echo $logs; ?>&member=<?php echo $val['id']; ?>&rcr=<?php echo $_GET['members']; ?>" class="blue-font ft-sml-size">View Membership +</a></p></td>
														</tr>
													<?php
												}
											?>
										</table>
									<?php
								}

							} else {
								if(isset($isSearched) && $isSearched == 1) {
									?>
										<h3 class="large nobold default-text-font-bold light-red-font">No match records. Try again</h3>
									<?php
								}
							}

							if(isset($member_id) && $member_id >= 1) {
								
								if(isset($get_member_data[1]) && !empty($get_member_data[1])) {
									$member_photo = DOMAIN_URL."theme/images/general/recreation-members/".$get_member_data[1];
								} else {
									$member_photo = DOMAIN_URL."theme/images/general/photo.png";
								}

								$get_salutation = idget_data($tbL42,$get_member_data[2],'name');
								$duration = arrayset_form($recreation_duration,'select');
								$list_payment_modes = select_dt_fetch('',0,$tbL24,'id','name');

								if($get_member_data[10] == 'Yes' && $get_member_data[11] >= 1) {
									$cspg = idget_data($tbL58,$get_member_data[11],'name');
									$addLabel = " - Corp/Spl. Guest (".$cspg.")";
									$iscorp = $get_member_data[11];
								} else {
									$addLabel = "";
									$iscorp = 0;
								}

								?>
								<span class="ln-display-box float-left nc-width-20 right-push-50">
									<!--<div class="block-element cs-height-100"></div>-->
									<div class="block-element cs-height-200 bottom-push-10 noscroll alignct">
										<img src="<?php echo $member_photo; ?>" class="auto-wh">
									</div>
								</span>
								<span class="ln-display-box float-left nc-width-50 alignlt">
									<h3 class="large nobold default-text-font-bold"><?php echo $get_salutation.' '.$get_member_data[3].' '.$get_member_data[4].$addLabel; ?></h3>
									<small class="block-element top-push-3"><b class="dark-grey-font nobold">Membership Type:</b> &nbsp;<?php echo $get_member_data[5]; ?></small><small class="block-element">(<?php echo date("d/m/Y",strtotime($get_member_data[7])).' &mdash; '.date("d/m/Y",strtotime($get_member_data[8])); ?>)</small>

									<div class="block-element top-push-50">
										<h4 class="xlarge nobold light-red-font">* For corporate, please do not choose payment mode. Enter only amount</h4><br>
										<h4 class="large nobold default-text-font-bold">Start new membership plan</h4><br>
										<span class="block-element bottom-push-10">
											<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Start from (effectiveness)</small>
											<input type="date" name="fieldset1" id="fieldset1" required="required">
										</span>
										<span class="block-element bottom-push-10">
											<small class="block-element bottom-push-5 left-pull-5 dark-grey-font">Duration</small>
											<select name="fieldset2" id="fieldset2" required="required">
												<option value="" selected="selected">Choose</option>
												<?php echo $duration; ?>
											</select>
										</span>
									</div>
								</span>
								<span class="block-element new-line-space">
								</span>

								
								<div class="block-element top-push-30 sml-rounded-button noscroll">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<th width="150px" align="center">Payment Mode</th>
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
												<input type="number" name="amount" id="amount" min="1" step="1" placeholder="0.00" required="required">
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

								<div class="block-element top-push-30 bottom-push-15">
									<span class="float-right">
										<input type="submit" name="submitbutton" value="Renew Membership" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
									</span>
									<!--<div class="cs-width-250">
										<select name="recreationworkflow" id="recreationworkflow" required>
											<option value="">Choose Approval Workflow</option>
											<?php //echo $ths_workflow_names; ?>
										</select>
									</div>-->
								</div>

								<?php
							} else {
								?>
									<small class="block-element dark-grey-font">Select the member's account to continue</small>
								<?php
							}

						?>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div id="notifybox" class="noshow fx-position-stick zind-2 motion btscr" align="left">
		<div class="cs-width-400 white-theme pads20 bottom-push-30 left-push-50 sml-rounded-button alignlt box-border-thick">
			<h4 id="rec-header-notification" class="large red-font"></h4>
			<small id="rec-message-notification" class="block-element top-push-10"></small>
		</div>
	</div>


<script>
	
	function show_member(e) {
		if(e.KeyCode == 13 || e.which == 13) {
			e.preventDefault();
			var mid = document.getElementById('memberlist').value;
			window.location.href = '?logs=<?php echo $logs; ?>&members='+mid;
		}
	}

</script>