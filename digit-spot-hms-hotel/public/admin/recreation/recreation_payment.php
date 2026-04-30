<?php
	$recreation_number = $ftoken;
	$ths_token = $stoken;

	$dataproperty = "id,recreation_number,photo,salutation,firstname,lastname,othernames,maritalstatus,gender,dob,nationality,emailaddress,mobile,membership_type,iscomplimentary,complimentary_src,profession,bodyheight,heightuom,bodyweight,weightuom,bloodgroup,genotype,officeaddress,officephone,homeaddress,plan,startdate,enddate,iscorporate,corporate_type,detail,workflow,isapproved,status";

	$recreation_selection_key = array("id"=>$ths_token,"deletedata"=>0);
	$get_recreation_data = mysqli_data_fetch($tbL105,$dataproperty,$recreation_selection_key,'noarray');

	#---------------------------------------------------------------------------------

	$show_form = "block-element";

	if(isset($_POST['submitbutton']) && isset($_POST['rowid']) && !empty($_POST['rowid'])) {

		$new_recreation_id = $_POST['rowid'];
		$recreation_number = $_POST['recreationumber'];

		#get user counter session id
		$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

		$totalamount = escape_data($_POST['amount']);
		$wgt_paymentmode = escape_data($_POST['payment-mode']);
		$wgt_paymentdate = escape_data($_POST['transactiondate']);

		$corporate_src = $_POST['forcspg'];

		$pst_query = array("id"=>$_POST['rowid']);
		$pst_field = array("status"=>1);
		$result = mysqli_data_update($tbL105,$pst_field,$pst_query);

		#send charge to corporate if is registered as that
		if(isset($totalamount) && !empty($totalamount) && $totalamount > 0 && $corporate_src >= 1) {

			#retrieve group creditlimt
			$credit_limit = idget_data($tbL58,$corporate_src,'creditlimit');
			$new_creditlimit = $credit_limit - $totalamount;

			#update group creditlimt
			$blc_selection_key = array("id"=>$corporate_src);
			$crl_datasets = array("creditlimit"=>$new_creditlimit);
			mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

			$transaction_desc = "Recreation charges for ".ucwords(strtolower($get_recreation_data[4]))." ".ucwords(strtolower($get_recreation_data[5]))." with recreation number ({$recreation_number})";

			$ledger_dataquery = array("cspgid"=>$corporate_src,"transaction_number"=>$recreation_number,"transaction_type"=>"Debit","datelogged"=>$server_get_date,"deletedata"=>0);
			$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$corporate_src,"transaction_number"=>$recreation_number,"transaction_type"=>"Debit","amount"=>$totalamount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$wgt_paymentdate,"detail"=>$transaction_desc,"biller"=>"recreation","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);

			#log payment for corporate
			$payment_dataproperty = array("recreation_number"=>$recreation_number,"memberid"=>$new_recreation_id,"mode"=>111,"amount"=>$totalamount,"chequenumber"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$wgt_paymentdate,"userid"=>$userSignedIn,"startdate"=>$get_recreation_data[27],"enddate"=>$get_recreation_data[28],"datelogged"=>$wgt_paymentdate,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL107,$payment_dataproperty,'');
			$new_payment_id = $mysqli_id;
			$invoice_number = $recreation_inv_prefix.$new_payment_id;

			$update_payment_dataproperty = array("invoice_number"=>$invoice_number);
			$pay_data_key = array("id"=>$new_payment_id);
			mysqli_data_update($tbL107,$update_payment_dataproperty,$pay_data_key);
		}

		#update sales for open-counter
		if((isset($wgt_paymentmode) && $wgt_paymentmode > 0) && (isset($totalamount) && $totalamount > 0)) {
			
			$payment_dataproperty = array("recreation_number"=>$recreation_number,"memberid"=>$new_recreation_id,"mode"=>$wgt_paymentmode,"amount"=>$totalamount,"chequenumber"=>escape_data($_POST['cheque-number']),"detail"=>escape_data($_POST['detail']),"paymentdate"=>$wgt_paymentdate,"userid"=>$userSignedIn,"startdate"=>$get_recreation_data[27],"enddate"=>$get_recreation_data[28],"datelogged"=>$wgt_paymentdate,"timelogged"=>$server_get_time);
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
		}

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Membership payment was added successful. Check recreation dashboard";
		
		$islogfile = 1;
		$logfile_msg = "Recently added payment for recreation membership (".$recreation_number.")";
		

		$show_form = "noshow";
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

?>

<div class="pads20 <?php echo $show_form; ?>">
	<h3 class="large nobold default-text-font-bold">Recreation Bill / Payment</h3><br>
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<input type="hidden" name="recreationumber" value="<?php echo $recreation_number; ?>">
		<input type="hidden" name="rowid" value="<?php echo $ths_token; ?>">
		<input type="hidden" name="forcspg" value="<?php echo $iscorp; ?>">
		<fieldset>
			<legend><?php echo $get_recreation_data[4].' '.$get_recreation_data[5].$addLabel; ?></legend>
			<h4 class="xlarge nobold light-red-font">* For corporate, please do not choose payment mode. Enter only amount</h4><br>
			<div class="block-element bottom-push-15">
				<h4 class="xlarge nobold left-pull-5 default-text-font-bold">Make Payment</h4>
				<div class="block-element sml-rounded-button top-push-5 noscroll">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="150px" align="center">Mode</th>
							<th width="150px" align="center">Amount</th>
							<th width="150px" align="center">CC/Cheque No</th>
							<th width="150px" align="center">Transaction Date</th>
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
								<input type="date" name="transactiondate" id="transactiondate" value="<?php echo $server_get_date; ?>">
							</td>
							<td width="200px" align="center">
								<input type="text" name="detail" id="detail">
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="block-element top-pull-20 bottom-push-15 alignct">
				<input type="submit" name="submitbutton" value="Apply Payment" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button">
			</div>

		</fieldset>
	</form>
</div>


<script>

</script>