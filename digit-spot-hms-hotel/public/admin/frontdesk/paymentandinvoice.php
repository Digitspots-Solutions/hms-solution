<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	#bill extractor
	if(isset($_POST['billExtractor'])) {
		
		$ex_totalamount = 0; $kps_bills = array();

		foreach($_POST['pos'] as $pvid) {
			
			$theBill = idget_data($tbL100,$pvid,'bill_amount'); //get bill
			array_push($kps_bills, $pvid);

			$ex_totalamount = $ex_totalamount + $theBill;
			
			$theBill = 0;
		}

		$_SESSION['kpbill'] = $kps_bills;

		$wget_payment_mode = select_dt_fetch('deletedata',0,$tbL24,'id','name');

		?>
			<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion nc-height-100 y-scroll" align="center">
				<div class="cs-height-150"></div>
				<div id="rBox" class="fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll">
					<form action="" method="post" autocomplete="off" onsubmit="">
						<div class="pads10 alignlt">
							<label class="block-element default-text-font-bold bottom-push-20">Outlet bill extraction & payment</label>
							<input type="text" name="outletamountx" id="outletamountx" placeholder="Enter value here" value="<?php echo number_format($ex_totalamount); ?>" class="default-text-font-bold" readonly><input type="hidden" name="outletamount" id="outletamount" value="<?php echo $ex_totalamount; ?>" readonly>
							<p class="top-pull-10">
								<select name="paymentmode4outlet" id="paymentmode4outlet">
									<option value="" selected>Payment Mode?</option>
									<?php echo $wget_payment_mode; ?>
								</select>
							</p>
						</div>
						<div class="top-pull-30 motion" align="center">
							<input type="submit" id="outletbillbutton" name="outletbillbutton" value="Confirm & Pay" class="nc-width-100 blue-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2 right-push-20">
							<p class="top-pull-15 alignct"><a href="" class="black-font default-text-font-bold" title="Close">Cancel x</a></p>
						</div>
					</form>
				</div>
			</div>

			
		<?php
	}

	if(isset($_POST['outletbillbutton']) && isset($_SESSION['kpbill'])) {

		$postBill = $_SESSION['kpbill'];

		$postAmount = $_POST['outletamount'];
		$postPaymode = $_POST['paymentmode4outlet'];

		foreach($postBill as $pvid) {
			
			$pst_query = array("id"=>$pvid);
			$pst_field = array("payment"=>"Paid","ispaid"=>1,"biller"=>0,"media"=>$postPaymode);
			mysqli_data_update($tbL100,$pst_field,$pst_query);

			$pst_query = ""; $pst_field = "";
		}


		if(isset($postPaymode) && $postPaymode > 0) {
						
			$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$postPaymode,"ispast"=>0);
			$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

			$new_collection = $sales_counter_data[0] + $postAmount;
			
			$sales_counter_sql = array("collection"=>$new_collection);
			mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
		}


		unset($_SESSION['kpbill']);

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Bill extraction was done successfully";
		
		$islogfile = 1;
		$logfile_msg = "Bill extraction was performed on a corporate guest booking (".$booking_number.") for separate payment";
		
		$isguestAct = 1;
		$pst_booking_number = $booking_number;
		$guestAct_msg = "Bill extraction was performed on this booking (".$booking_number.") for a separate payment";
	}

	#---end


	//include "post_booking_tokens.php";
	include "booking_tokens.php";

	#get user privilege to reverse guest payment, apply discount and others
	//include "module_operation_privilege.php";
	
	#get variables

	#do rebate/coupon
	if(isset($_POST['couponbutton']) && (isset($_POST['amount']) && $_POST['amount'] >= 0)) {

		$sql_uquery = array("booking_number"=>$booking_number);
		$sql_udata = array("settled_booking"=>1,"reservation"=>"Checking Out");
		$isdata = mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

		$rebate_amt = $_POST['amount'];
		$keep_coupon_amt = $rebate_amt - ($rebate_amt * 2);

		createDatabasetable($var_tbl_133); //create a table for this post
		createDatabasetable($var_tbl_124); //create a table for this post

		$new_coupon_code = substr(sha1(mt_rand(100,999999999999)),1,8);
		$sql_uquery = array("booking_number"=>$booking_number);
		$sql_udata = array("coupon_code"=>$new_coupon_code,"booking_number"=>$booking_number,"guest_name"=>$guest_account_name,"guest_contact"=>$get_guest_detail[6],"coupon_amount"=>$rebate_amt,"payment_mode"=>0,"coupon_type"=>1,"expires_on"=>$coupon_expiry_default_date,"customerid"=>0,"userid"=>$userSignedIn,"coupon_status"=>"Unused","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		/*$sql_uquery = array("booking_number"=>$booking_number);
		$sql_udata = array("booking_number"=>$booking_number,"balance_amount"=>$rebate_amt,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);*/
		$isdata = mysqli_data_insert($tbL129,$sql_udata,$sql_uquery);

		if(isset($isdata) && $isdata == 2) {

			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$lp_datasets = "biller,invoice_number,receipt_number,customerid";
			$lastpayment = mysqli_data_fetch($tbL131,$lp_datasets,$sql_uquery,'noarray');

			$sales_description = 'Balance set as coupon ('.$new_coupon_code.') at the time of settle booking';

			$sql_py_pst_data = array("biller"=>$lastpayment[0],"sales_point"=>"booking","booking_number"=>$booking_number,"receipt_number"=>$lastpayment[2],"customerid"=>$lastpayment[3],"transaction_type"=>"coupon","amount"=>$keep_coupon_amt,"payment_mode"=>0,"sales_description"=>$sales_description,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');
					
			#-----------------------------------------------------------------------------

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Booking is settled and a coupon is generated successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest booking (".$booking_number.") is settled and a refund is posted as coupon by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Guest booking is settled and a refund is posted as coupon";
		}
	}


	#do refund
	if(isset($_POST['refundbutton']) && ((isset($_POST['amount']) && $_POST['amount'] >= 0) || (isset($_POST['pstamount']) && $_POST['pstamount'] >= 0))) {

		$df_pty = idget_fdata($tbL24,'isdefault','Yes','id');
		$queryset = "deletedata=0 AND counterid={$counter_sesid} AND userid={$userSignedIn} AND ispast=0";
		if($df_pty > 0) { $queryset2x = "fundid='{$df_pty}' AND deletedata=0 AND counterid={$counter_sesid} AND userid={$userSignedIn} AND ispast=0"; } else { $queryset2x = "fundid=1 AND deletedata=0 AND counterid={$counter_sesid} AND userid={$userSignedIn} AND ispast=0"; }

		#total opening balance
		$obl_sql = "SUM(openingbalance)";
		$wgt_obl = mysqli_arithmetic_data($tbL25,$obl_sql,$queryset);

		#total credit
		$crd_sql = "SUM(collection)";
		$wgt_crd = mysqli_arithmetic_data($tbL25,$crd_sql,$queryset2x);

		#total refunds
		$ref_sql = "SUM(refunds)";
		$wgt_ref = mysqli_arithmetic_data($tbL25,$ref_sql,$queryset2x);

		#total withdrawal
		$wdr_sql = "SUM(withdrawal)";
		$wgt_wdr = mysqli_arithmetic_data($tbL25,$wdr_sql,$queryset);

		$total_credits = ($wgt_obl + $wgt_crd) - ($wgt_ref + $wgt_wdr);
		//$total_credits = $wgt_obl;

		$rebate_amt = !empty($_POST['amount']) ? $_POST['amount'] : $_POST['pstamount'];
		$rebate_amt = round($rebate_amt,2);
		
		$keeprefund = $rebate_amt - ($rebate_amt * 2);

		if($total_credits >= $rebate_amt) {
			
			$sql_uquery = array("booking_number"=>$booking_number);

			if(isset($_POST['issettled']) && $_POST['issettled'] === 'yes') {
				$sql_udata = array("settled_booking"=>1,"reservation"=>"Checking Out");
				$isdata = mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

				$sales_description = 'Balance refunded at the time of settle booking';
				$tosettle = 1;

			} else {
				$sales_description = 'Balance refunded as at the time of booking';
				$tosettle = 0;
			}

			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$sql_pydata = array("refund"=>$rebate_amt);
			mysqli_data_update($tbL131,$sql_pydata,$sql_uquery);

			$lp_datasets = "biller,invoice_number,receipt_number,customerid";
			$lastpayment = mysqli_data_fetch($tbL131,$lp_datasets,$sql_uquery,'noarray');

			$sql_py_pst_data = array("biller"=>$lastpayment[0],"sales_point"=>"booking","booking_number"=>$booking_number,"receipt_number"=>$lastpayment[2],"customerid"=>$lastpayment[3],"transaction_type"=>"refund","amount"=>$keeprefund,"payment_mode"=>0,"sales_description"=>$sales_description,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');
					
			#-----------------------------------------------------------------------------

			$additionalQuery = "";
			$fund_id = idget_fdata($tbL24,'isdefault','Yes','id'); //get default fund type

			$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$fund_id,"ispast"=>0);
			$sales_counter_data = mysqli_data_fetch($tbL25,'refunds',$sales_counter_query,'noarray');
			$new_refunds = $sales_counter_data[0] + $rebate_amt;

			$sales_counter_sql = array("refunds"=>$new_refunds);		
			$isdata = mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

			if(isset($isdata) && $isdata == 2) {

				$saynotify = 1;
				$notifytype = 2;
				
				if($tosettle == 1) {
					$post_header = "Notification";
					$post_message = "Guest booking is settled successfully";
					
					$islogfile = 1;
					$logfile_msg = "Guest booking (".$booking_number.") is settled and a refund was made by this user";
					
					$isguestAct = 1;
					$pst_booking_number = $booking_number;
					$guestAct_msg = "Guest booking is settled and a refund was made";
				} else {
					$post_header = "Notification";
					$post_message = "Refund applied successfully";
					
					$islogfile = 1;
					$logfile_msg = "Guest booking (".$booking_number.") processed refund as at the time of booking by this user";
					
					$isguestAct = 1;
					$pst_booking_number = $booking_number;
					$guestAct_msg = "A refund occured ({$rebate_amt}) in guest ledger";
				}
			}

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "No refund occured. Try and check if you have any credit";
		}
	}


	#do rebate
	if(isset($_POST['rebatebutton']) && (isset($_POST['pstamount']) && $_POST['pstamount'] > 0)) {

		$rebate_amt = $_POST['pstamount'];
		$keeprebate = $rebate_amt;

		$sales_description = "Rebate is applied to allow booking settle";

		$additionalQuery = " ORDER BY id DESC LIMIT 1";
		$sql_uquery = array("booking_number"=>$booking_number);
		$lp_datasets = "biller,invoice_number,receipt_number,customerid,datelogged";
		$lastpayment = mysqli_data_fetch($tbL131,$lp_datasets,$sql_uquery,'noarray');

		$sql_py_pst_data = array("biller"=>$lastpayment[0],"sales_point"=>"booking","booking_number"=>$booking_number,"receipt_number"=>$lastpayment[2],"customerid"=>$lastpayment[3],"transaction_type"=>"rebate","amount"=>$keeprebate,"payment_mode"=>0,"sales_description"=>$sales_description,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
		mysqli_data_insert($tbL131,$sql_py_pst_data,'');

		$booking_type = idget_fdata($tbL130,$booking_number,'booking_number','booking_type');
		$bill_to = idget_fdata($tbL130,$booking_number,'booking_number','bill_to');

		if($booking_type == 'corporate' && $bill_to > 0) {
			$guest_name = idget_data($tbL58,$bill_to,'name');
		} else {
			$guest_name = idget_data($tbL102,$lastpayment[3],'fname').' ';
			$guest_name .= idget_data($tbL102,$lastpayment[3],'lname');
		}

		$pst_query = "";
		$pst_field = array("rebate_no"=>$booking_number,"booking_number"=>$booking_number,"rebate_type"=>"Booking","guest_name"=>$guest_name,"amount"=>$keeprebate,"remark"=>"Rebate guest account to settle transaction","transaction_date"=>$lastpayment[4],"userid"=>$userSignedIn,"status"=>"Completed","approval_status"=>"Completed","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL163,$pst_field,$pst_query);

		$sql_rebate_data = array("booking_number"=>$booking_number,"balance_amount"=>$keeprebate,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
		mysqli_data_insert($tbL138,$sql_rebate_data,'');
				
		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Rebate is applied successfully";
		
		$islogfile = 1;
		$logfile_msg = "Guest booking (".$booking_number.") ledger balance rebate by this user";
		
		$isguestAct = 1;
		$pst_booking_number = $booking_number;
		$guestAct_msg = "Rebate of {$rebate_amt} is applied to guest ledger balance";	
			
	}


	#settle booking
	if(isset($_POST['settlebutton'])) {

		$sql_uquery = array("booking_number"=>$booking_number);
		$sql_udata = array("settled_booking"=>1,"reservation"=>"Checking Out");
		$isdata = mysqli_data_update($tbL130,$sql_udata,$sql_uquery);

		if(isset($isdata) && $isdata == 2) {

			/*$pst_query = array("booking_number"=>$booking_number);
			$pst_field = array("payment"=>"Paid");
			mysqli_data_update($tbL100,$pst_field,$pst_query);*/

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Guest booking is settled successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest booking with booking number(".$booking_number.") was settled by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$guestAct_msg = "Guest booking is cleared and settled successfully";
		}
	}

	#reverse payment
	if(isset($_GET['reverse']) && $_GET['reverse'] >= 1) {

		$wgt_reverseid = $_GET['reverse'];
		$sql_uquery = array("id"=>$wgt_reverseid);
		$sql_udata = array("isreversed"=>1,"deletedata"=>1);
		$isdata = mysqli_data_update($tbL131,$sql_udata,$sql_uquery);

		if(isset($isdata) && $isdata == 2) {

			$wgt_biller = idget_data($tbL131,$wgt_reverseid,'biller');
			$wgt_receipt = idget_data($tbL131,$wgt_reverseid,'receipt_number');
			$wgt_booking_no = idget_data($tbL131,$wgt_reverseid,'booking_number');
			$wgt_invoice_no = idget_data($tbL131,$wgt_reverseid,'invoice_number');
			$wgt_customerid = idget_data($tbL131,$wgt_reverseid,'customerid');
			$wgt_amount = idget_data($tbL131,$wgt_reverseid,'amount');
			$wgt_famount = idget_data($tbL131,$wgt_reverseid,'first_amount');
			$wgt_samount = idget_data($tbL131,$wgt_reverseid,'second_amount');
			$wgt_paymode = idget_data($tbL131,$wgt_reverseid,'payment_mode');
			$wgt_cheque = idget_data($tbL131,$wgt_reverseid,'cheque_number');
			$wgt_userid = idget_data($tbL131,$wgt_reverseid,'userid');
			$wgt_counter = idget_data($tbL131,$wgt_reverseid,'counter_used');
			$wgt_shiftid = idget_data($tbL131,$wgt_reverseid,'shiftid');

			$get_user = idget_data($tbL7,$wgt_userid,'staffname');
			$desc = "Replica of reversed payment for counter user {$get_user} and with the booking number: ".$wgt_booking_no;

			$sql_uquery = "";
			$sql_udata = array("biller"=>0,"sales_point"=>"booking","booking_number"=>"","receipt_number"=>"","customerid"=>"","transaction_type"=>"refund","amount"=>$wgt_amount,"payment_mode"=>$wgt_paymode,"sales_description"=>$desc,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			$isdata = mysqli_data_insert($tbL131,$sql_udata,$sql_uquery);

			#update user counter
			$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymode,"ispast"=>0);
			$sales_counter_data = mysqli_data_fetch($tbL25,'refunds',$sales_counter_query,'noarray');
			$new_refunds = $sales_counter_data[0] + $wgt_amount;

			$sales_counter_sql = array("refunds"=>$new_refunds);		
			$isdata = mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

			#-------------------------------------------------------------------------------------------------------------

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Payment was reversed successfully";
			
			$islogfile = 1;
			$logfile_msg = "Guest payment with booking number (".$booking_number.") and the receipt number (".$wgt_receipt.") was reversed by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $booking_number;
			$remark_tag = "reverse"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
			$guestAct_msg = "Guest payment of sum of ({$wgt_amount}) with receipt number ({$wgt_receipt}) was reversed";
		}
	}

	#apply discount to charges
	if(isset($_POST['discountedbutton'])) {
		if(isset($_POST['wgtappliediscount']) && $_POST['wgtappliediscount'] >= 0) {
			
			$usethediscount = str_replace(',','',$_POST['wgtappliediscount']);
			
			if($usethediscount <= 100) {
				$getdiscount = ($usethediscount / 100) * $_POST['wgtchargeamount'];
				$denote = " %";
			} else {
				$getdiscount = $usethediscount;
				$denote = " NGN";
			}

			$chargebal = $_POST['wgtchargeamount'] - $getdiscount;
			$wgtid = $_POST['wgtchargeid'];
			
			$get_room_type_id = idget_data($tbL134,$wgtid,'room_type_id');
			$get_room_id = idget_data($tbL134,$wgtid,'roomid');
			$guest_id = idget_data($tbL134,$wgtid,'customerid');

			$room_type_name = idget_data($tbL52,$get_room_type_id,'name');
			$room_name = idget_data($tbL56,$get_room_id,'roomprefix');
			$room_name .= idget_data($tbL56,$get_room_id,'roomnumber');

			$customer_name = idget_data($tbL102,$guest_id,'fname').' ';
			$customer_name .= idget_data($tbL102,$guest_id,'lname');

			$tax_amount = idget_data($tbL134,$wgtid,'tax_amount');
			$tax_consumption_amount = idget_data($tbL134,$wgtid,'consumption_tax_amount');
			$service_charge = idget_data($tbL134,$wgtid,'service_charge');

			if($tax_amount > 0 && isset($gh_get_vat)) { $get_tpg = $gh_get_vat; }
			else { $get_tpg = 0; }

			if($tax_consumption_amount > 0 && isset($gh_get_consumption_tax)) { $get_cpg = $gh_get_consumption_tax; }
			else { $get_cpg = 0; }

			if($service_charge > 0 && isset($gh_get_service_charge)) { $get_spg = $gh_get_service_charge; }
			else { $get_spg = 0; }

			/*if($tax_amount > 0) { $get_tpg = ($tax_amount / $_POST['wgtchargeamount']) * 100; }
			else { $get_tpg = 0; }

			if($tax_consumption_amount > 0) { $get_cpg = ($tax_consumption_amount / $_POST['wgtchargeamount']) * 100; }
			else { $get_cpg = 0; }

			if($service_charge > 0) { $get_spg = ($service_charge / $_POST['wgtchargeamount']) * 100; }
			else { $get_spg = 0; }*/

			$new_tax_amount = ($get_tpg / 100) * $chargebal;
			$new_consumption_amount = ($get_cpg / 100) * $chargebal;
			$new_service_charge_amount = ($get_spg / 100) * $chargebal;
			
			$sql_uquery = array("id"=>$wgtid);
			$sql_udata = array("discount_amount"=>$getdiscount,"tax_amount"=>$new_tax_amount,"consumption_tax_amount"=>$new_consumption_amount,"service_charge"=>$new_service_charge_amount);
			$isdata = mysqli_data_update($tbL134,$sql_udata,$sql_uquery);

			if(isset($isdata) && $isdata == 2) {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Discount was applied successfully";
				
				$islogfile = 1;
				$logfile_msg = "Guest (".$booking_number.") was given discounted amount of (".$getdiscount.") for room {$room_name} by this user";
				
				$isguestAct = 1;
				$pst_booking_number = $booking_number;
				$remark_tag = "discount"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
				$guestAct_msg = $_POST['wgtappliediscount']."{$denote} discount (".$getdiscount.") applied to room charged for ".$customer_name.". Room type: ".$room_type_name." | room number: ".$room_name;
			}
		}
	}


	#post payment
	if(isset($_POST['vastpaymentbutton'])) {
		
		$wgt_pstbooking = $_POST['pstbookingnumber'];
		$wgt_pstbiller = $_POST['pstbiller'];
		$wgt_paymentmode = $_POST['paymentmode'];
		$wgt_amountdeposited = $_POST['wgtf1'] + $_POST['wgtf2'];
		$first_amount = escape_data($_POST['wgtf1']);
		$second_amount = escape_data($_POST['wgtf2']);
		$wgt_chequenumber = $_POST['chequenumber'];
		$wgt_detail = escape_data($_POST['detail']);
		$wgt_crdinvoice = $_POST['crdinvoice'];
		$totalbal2pay = $_POST['totalbal2pay'];

		$sales_description = "";

		if((isset($wgt_crdinvoice) && !empty($wgt_crdinvoice)) && (isset($wgt_paymentmode) && $wgt_paymentmode >= 1)) {
			
			/*if((isset($wgt_balance) && $wgt_balance > 0) && $wgt_amountdeposited >= $wgt_balance) {
				$pst_query = array("booking_number"=>$wgt_pstbooking);
				$pst_field = array("payment"=>"Paid","ispaid"=>1,"media"=>$wgt_paymentmode);
				mysqli_data_update($tbL100,$pst_field,$pst_query);
			}*/

			//add for journal if exist
			$query_jr = array("pychannel"=>$wgt_paymentmode,"deletedata"=>0);
			$isJournal = mysqli_data_fetch('coa_setup_tbl','id',$query_jr,'noarray');

			if(is_array($isJournal) && count($isJournal) > 0 && !empty($isJournal[0])) {

				$total_paid_amount = $first_amount + $second_amount;
				
				$mmonth = date('F',strtotime($server_get_date));
				$yyear = date('Y',strtotime($server_get_date));

				$insert_constrain = "";
				$insert_dataproperty = array("coa_id"=>$isJournal[0],"amount"=>$total_paid_amount,"entry_type"=>"Credit","detail"=>$wgt_detail,"mmonth"=>$mmonth,"yyear"=>$yyear,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

				mysqli_data_insert('coa_entry_tbl',$insert_dataproperty,$insert_constrain);
			}


			if(isset($_POST['isgroupinvoice']) && $_POST['isgroupinvoice'] == 1) {
				
				//$invoiceentity1 = $_POST['invoiceentity1'];
				//$invoiceentity2 = $_POST['invoiceentity2'];
				$ths_customer = ""; $isposted = 0; $totalamount = 0;
				
				$cSquery = array("booking_number"=>$wgt_pstbooking,"primary_guest"=>1,"deletedata"=>0);
				$cSdata = mysqli_data_fetch($tbL102,'id',$cSquery,'noarray');
				
				$ths_customer = $cSdata[0];

				$sql_py_pst_data = array("biller"=>$wgt_pstbiller,"sales_point"=>"booking","booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"customerid"=>$ths_customer,"transaction_type"=>"credit","amount"=>$wgt_amountdeposited,"first_amount"=>$first_amount,"second_amount"=>$second_amount,"payment_mode"=>$wgt_paymentmode,"cheque_number"=>$wgt_chequenumber,"detail"=>$wgt_detail,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');

				if(isset($isdata) && $isdata == 2) {

					#update receipt number and sales description
					$receipt_id = $mysqli_id;
					$receipt_number = $receipt_prefix.$receipt_id;

					if(isset($wgt_detail) && !empty($wgt_detail)) {
						$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.' ('.$wgt_detail.')';
					} else {
						$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number;
					}
					
					$receipt_sql = array("receipt_number"=>$receipt_number,"sales_description"=>$sales_description);
					$receipt_query = array("id"=>$receipt_id);
					mysqli_data_update($tbL131,$receipt_sql,$receipt_query);

					
					#update sales for open-counter
					$totalamount = $wgt_amountdeposited;

					if((isset($wgt_paymentmode) && $wgt_paymentmode > 0) && (isset($totalamount) && $totalamount > 0)) {
						
						$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

						$new_collection = $sales_counter_data[0] + $totalamount;
						$cash2refund = $totalamount - $totalbal2pay;
						
						if($cash2refund > 0) { $sales_counter_sql = array("collection"=>$new_collection); }
						else { $sales_counter_sql = array("collection"=>$new_collection); }

						mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
					}

					$invoiceString = substr_replace($_POST['invoiceString'], '', -1,1);

					$saynotify = 1;
					$notifytype = 2;
					
					$post_header = "Notification";
					$post_message = "Payment was logged successfully. Please use refresh icon to see new update";
					
					$islogfile = 1;
					$logfile_msg = "Guest(".$wgt_pstbooking.") payment logged (".$totalamount.") in respect to this invoice number (".$invoiceString.") by this user";
					
					$isguestAct = 1;
					$pst_booking_number = $wgt_pstbooking;
					$remark_tag = "credit"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
					$guestAct_msg = "Guest payment of sum of (".$totalamount.") for invoice number (".$invoiceString.") and the booking number (".$wgt_pstbooking.") is updated successfully";

				}
				
			} else {

				if(isset($wgt_amountdeposited) && $wgt_amountdeposited > 0) {

					if(isset($wgt_pstbiller) && $wgt_pstbiller >= 1) {
						$ths_customer = $wgt_pstbiller;
					} else {
						$cSquery = array("booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"deletedata"=>0);
						$cSdata = mysqli_data_fetch($tbL102,'id',$cSquery,'noarray');
						$ths_customer = $cSdata[0];
					}

					$sql_py_pst_data = array("biller"=>$wgt_pstbiller,"sales_point"=>"booking","booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"customerid"=>$ths_customer,"transaction_type"=>"credit","amount"=>$wgt_amountdeposited,"first_amount"=>$first_amount,"second_amount"=>$second_amount,"payment_mode"=>$wgt_paymentmode,"cheque_number"=>$wgt_chequenumber,"detail"=>$wgt_detail,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');

					if(isset($isdata) && $isdata == 2) {
						
						#update receipt number and sales description
						$receipt_id = $mysqli_id;
						$receipt_number = $receipt_prefix.$receipt_id;

						if(isset($wgt_detail) && !empty($wgt_detail)) {
							$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.' ('.$wgt_detail.')';
						} else {
							$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number;
						}
						
						$receipt_sql = array("receipt_number"=>$receipt_number,"sales_description"=>$sales_description);
						$receipt_query = array("id"=>$receipt_id);
						mysqli_data_update($tbL131,$receipt_sql,$receipt_query);

						#update sales for open-counter
						if(isset($wgt_paymentmode) && $wgt_paymentmode > 0) {
							
							$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

							$new_collection = $sales_counter_data[0] + $wgt_amountdeposited;
							$cash2refund = $wgt_amountdeposited - $totalbal2pay;
							
							if($cash2refund > 0) { $sales_counter_sql = array("collection"=>$new_collection); }
							else { $sales_counter_sql = array("collection"=>$new_collection); }

							mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
						}

						$saynotify = 1;
						$notifytype = 2;
						
						$post_header = "Notification";
						$post_message = "Payment was logged successfully. Please use refresh icon to see new update";
						
						$islogfile = 1;
						$logfile_msg = "Guest(".$wgt_pstbooking.") payment logged (".$wgt_amountdeposited.") in respect to this invoice number (".$wgt_crdinvoice.") by this user";
						
						$isguestAct = 1;
						$pst_booking_number = $wgt_pstbooking;
						$remark_tag = "credit"; $app_tag = "Booking"; $session_tag = "Guest Ledger";
						$guestAct_msg = "Guest payment of sum of (".$wgt_amountdeposited.") for invoice number (".$wgt_crdinvoice.") and the booking number (".$wgt_pstbooking.") is updated successfully";
					}

				} else {
					$saynotify = 1;
					$notifytype = 2;
					
					$post_header = "Notification";
					$post_message = "Unable to complete request. Please select necessary payment details to continue";
				}
			}

		} else {
			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Unable to complete request. Please select necessary payment details to continue";
		}
	}


	#post payment using coupon
	if(isset($_POST['couponpaymentbutton'])) {
		
		$wgt_coupon = $_POST['couponcode'];
		$wgt_amountdeposited = idget_fdata($tbL129,'coupon_code',$wgt_coupon,'coupon_amount');
		$totalbal2pay = $_POST['totalbal2pay'];

		if(isset($wgt_amountdeposited) && $wgt_amountdeposited > 0) {
		
			$wgt_pstbooking = $_POST['pstbookingnumber'];
			$wgt_pstbiller = $_POST['pstbiller'];
			$wgt_paymentmode = $_POST['mode4coupon'];
			$wgt_detail = escape_data($_POST['detail2']);
			$wgt_crdinvoice = $_POST['crdinvoice2'];

			
			/*if((isset($wgt_balance) && $wgt_balance > 0) && $wgt_amountdeposited >= $wgt_balance) {
				$pst_query = array("booking_number"=>$wgt_pstbooking);
				$pst_field = array("payment"=>"Paid","ispaid"=>1,"media"=>$wgt_paymentmode);
				mysqli_data_update($tbL100,$pst_field,$pst_query);
			}*/
			

			$sales_description = "";

			if((isset($wgt_crdinvoice) && !empty($wgt_crdinvoice)) && (isset($wgt_paymentmode) && $wgt_paymentmode >= 1)) {
				
				if(isset($_POST['isgroupinvoice']) && $_POST['isgroupinvoice'] == 1) {
					
					//$invoiceentity1 = $_POST['invoiceentity1'];
					//$invoiceentity2 = $_POST['invoiceentity2'];
					$ths_customer = ""; $isposted = 0; $totalamount = 0;
					
					$cSquery = array("booking_number"=>$wgt_pstbooking,"primary_guest"=>1,"deletedata"=>0);
					$cSdata = mysqli_data_fetch($tbL102,'id',$cSquery,'noarray');
					$ths_customer = $cSdata[0];

					$sql_py_pst_data = array("biller"=>$wgt_pstbiller,"sales_point"=>"booking","booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"customerid"=>$ths_customer,"transaction_type"=>"credit","amount"=>$wgt_amountdeposited,"payment_mode"=>$wgt_paymentmode,"cheque_number"=>$wgt_chequenumber,"detail"=>$wgt_detail,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
					$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');


					if(isset($isdata) && $isdata == 2) {

						#update receipt number and sales description
						$receipt_id = $mysqli_id;
						$receipt_number = $receipt_prefix.$receipt_id;

						if(isset($wgt_detail) && !empty($wgt_detail)) {
							$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.'('.$wgt_detail.') with a coupon code '.$wgt_coupon;
						} else {
							$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.' with a coupon code '.$wgt_coupon;
						}
						
						$receipt_sql = array("receipt_number"=>$receipt_number,"sales_description"=>$sales_description);
						$receipt_query = array("id"=>$receipt_id);
						mysqli_data_update($tbL131,$receipt_sql,$receipt_query);

						$isposted += 1;
						$totalamount = $totalamount + $invoiceentity1[$i];
					}
					

					if(isset($isposted) && $isposted > 0) {
						
						#update sales for open-counter
						/*if((isset($wgt_paymentmode) && $wgt_paymentmode > 0) && (isset($totalamount) && $totalamount > 0)) {
							$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray'); $new_collection = $sales_counter_data[0] + $totalamount;
							
							$sales_counter_sql = array("withdrawal"=>$new_withdrawal);
							mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
						}*/

						$invoiceString = substr_replace($_POST['invoiceString'], '', -1,1);

						$saynotify = 1;
						$notifytype = 2;
						
						$post_header = "Notification";
						$post_message = "Payment was logged successfully. Please use refresh icon in ledger to see new update";
						
						$islogfile = 1;
						$logfile_msg = "Guest(".$wgt_pstbooking.") payment logged (".$totalamount.") using a coupon code (".$wgt_coupon.") in respect to this invoice number (".$invoiceString.") by this user";
						
						$isguestAct = 1;
						$pst_booking_number = $wgt_pstbooking;
						$guestAct_msg = "Guest payment (".$totalamount.") using a coupon code (".$wgt_coupon.") for invoice number (".$invoiceString.") and the booking number (".$wgt_pstbooking.") is updated successfully";

					}
					
				} else {

					if(isset($wgt_amountdeposited) && $wgt_amountdeposited > 0) {

						if(isset($wgt_pstbiller) && $wgt_pstbiller >= 1) {
							$ths_customer = $wgt_pstbiller;
						} else {
							$cSquery = array("booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"deletedata"=>0);
							$cSdata = mysqli_data_fetch($tbL102,'id',$cSquery,'noarray');
							$ths_customer = $cSdata[0];
						}

						$sql_py_pst_data = array("biller"=>$wgt_pstbiller,"sales_point"=>"booking","booking_number"=>$wgt_pstbooking,"invoice_number"=>$wgt_crdinvoice,"customerid"=>$ths_customer,"transaction_type"=>"credit","amount"=>$wgt_amountdeposited,"payment_mode"=>$wgt_paymentmode,"cheque_number"=>$wgt_chequenumber,"detail"=>$wgt_detail,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
						$isdata = mysqli_data_insert($tbL131,$sql_py_pst_data,'');

						if(isset($isdata) && $isdata == 2) {
							
							#update receipt number and sales description
							$receipt_id = $mysqli_id;
							$receipt_number = $receipt_prefix.$receipt_id;

							if(isset($wgt_detail) && !empty($wgt_detail)) {
								$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.' ('.$wgt_detail.') with a coupon code '.$wgt_coupon;
							} else {
								$sales_description = 'Paid using '.idget_data($tbL24,$wgt_paymentmode,'name').'. '.$receipt_number.' with a coupon code '.$wgt_coupon;;
							}
							
							$receipt_sql = array("receipt_number"=>$receipt_number,"sales_description"=>$sales_description);
							$receipt_query = array("id"=>$receipt_id);
							mysqli_data_update($tbL131,$receipt_sql,$receipt_query);

							#update sales for open-counter
							/*if(isset($wgt_paymentmode) && $wgt_paymentmode > 0) {
								$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymentmode,"ispast"=>0); $sales_counter_data = mysqli_data_fetch($tbL25,'withdrawal',$sales_counter_query,'noarray'); $new_withdrawal = $sales_counter_data[0] + $wgt_amountdeposited;
								
								$sales_counter_sql = array("withdrawal"=>$new_withdrawal);
								mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
							}*/

							$saynotify = 1;
							$notifytype = 2;
							
							$post_header = "Notification";
							$post_message = "Payment was logged successfully. Please use refresh icon to see new update";

							$islogfile = 1;
							$logfile_msg = "Guest(".$wgt_pstbooking.") payment logged (".$wgt_amountdeposited.") using a coupon code (".$wgt_coupon.") in respect to this invoice number (".$wgt_crdinvoice.") by this user";
							
							$isguestAct = 1;
							$pst_booking_number = $wgt_pstbooking;
							$guestAct_msg = "Guest payment (".$wgt_amountdeposited.") using a coupon code (".$wgt_coupon.") for invoice number (".$wgt_crdinvoice.") and the booking number (".$wgt_pstbooking.") is updated successfully";
						}

					} else {
						$saynotify = 1;
						$notifytype = 2;
						
						$post_header = "Notification";
						$post_message = "Unable to complete request. Please select necessary payment details to continue";
					}
				}

				#update the coupon detail
				$coupon_query = array("coupon_code"=>$wgt_coupon);
				$coupon_data = array("usedby"=>$userSignedIn,"coupon_status"=>"Used","status"=>0);
				mysqli_data_update($tbL129,$coupon_data,$coupon_query);

			} else {
				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Unable to complete request. Please select necessary payment details to continue";
			}
		}
	}


	//guest summary charges and payment
	$additionalQuery = " AND invoice_number IS NOT NULL AND TRIM(invoice_number) <> '' GROUP BY invoice_number";
	$sumr_invoice_query = array("booking_number"=>$booking_number,"deletedata"=>0);
	$sumr_invoice_sql = mysqli_data_fetch($tbL134,'invoice_number',$sumr_invoice_query,'array');
	$additionalQuery = "";

	$wgt_bal_2pay = 0;
	$total_billpost_amount = 0;
	$invoice_datapack = array();
	$taxes_arry = array();

	$total_base_amount = 0;


	$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<div id="section-to-print" class="block-element">

	<div id="rvh-h" class="noshow bottom-push-30" align="center">
		<div class="cs-width-100 bottom-push-10 noscroll">
			<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
		</div>
		<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
		<h3 class="large nobold default-text-font-bold nomargin"><?php echo $hotel_address; ?></h3>
		<h3 class="large nobold nomargin">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></h3>
		<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
	</div>

	<form action="" method="post" autocomplete="off">
		<div class="block-element">
			<fieldset>
				<legend><h2 class="large nobold default-text-font-bold nomargin">Payment & Invoice</h2></legend>
				<div class="block-element cs-height-10"></div>
				<div id="balAlert" class="noshow alignct" onclick="chgclass('balAlert','noshow alignct')" title="Click to close"></div>
				<div class="block-element pads7 steel-blue-theme white-font ft-sml-size bottom-push-5">
					<span class="ln-display-box float-left nc-width-40">
						<h4 class="large nobold bottom-pull-3"><?php echo $wgtbkStatus; ?></h4>
						<h4 class="large nobold">Booking No: &nbsp; <?php echo $booking_number; ?></h4>
					</span>
					<span class="ln-display-box float-right nc-width-50 alignrt">
						<h4 class="large nobold bottom-pull-3">Check-In: <?php echo $print_wgt_checkin_date.' '.$print_wgt_checkin_time; ?></h4>
						<h4 class="large nobold">Check-Out: <?php echo $print_wgt_checkout_date.' '.$print_wgt_checkout_time; ?></h4>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
				<div class="block-element pads7 ft-sml-size bottom-push-10">
					<span class="ln-display-box float-left nc-width-60">
						<?php
							if($bill_charged_on != '') {
								if($guestfeatOpt == 'noshow') {
									$addr = (!empty($cspg_address)) ? $cspg_address : $get_guest_detail[9];
									$phone = (!empty($cspg_phone)) ? $cspg_phone : $get_guest_detail[6];
									?>
										<h4 class="large nobold bottom-pull-3"><?php echo $bill_charged_on; ?></h4>
										<h4 class="large nobold bottom-pull-10">Billing Address: &nbsp; <?php echo $addr; ?></h4>
										<h4 class="large nobold bottom-pull-3">Phone No: &nbsp; <?php echo $phone; ?></h4>
										<h4 class="large nobold">Email: &nbsp; <?php echo $cspg_email; ?></h4>
									<?php
								} else {
									?>
										<h4 class="large nobold bottom-pull-3"><?php echo $bill_charged_on; ?></h4>
									<?php
								}
							} else {
								?>
									<h4 class="large nobold bottom-pull-3 dark-grey-font">Primary Contact</h4>
									<h4 class="large nobold"><?php echo $guest_account_name; ?></h4>
								<?php
							}
						?>
					</span>
					<span class="ln-display-box float-right nc-width-30 alignrt">
						<h4 class="large nobold bottom-pull-3">No of person: Adult(<?php echo $wgt_total_adults; ?>) - Child(<?php echo $wgt_total_childs; ?>)</h4>
						<h4 class="large nobold bottom-pull-3">No of rooms: <?php echo $wgt_total_rooms; ?></h4>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>

				<div id="bkL" class="block-element box-border-thick pads10" onclick="removechild('bkL')">
					<h4 class="large nobold default-text-font-bold alignct">Booking Invoice List</h4>
					<table cellpadding="0" cellspacing="0" class="ft-xxsml-size top-push-5">
						<tr>
							<th width="200px" align="center">Guest Name</th>
							<th width="100px" align="center">Invoice #</th>
							<th width="100px" align="center">Amount</th>
							<th width="100px" align="center">Discount</th>
							<th width="100px" align="center">Tax</th>
							<th width="150px" align="center">Outlet Charges</th>
							<th width="100px" align="center">Paid</th>
							<th width="100px" align="center">Refunds</th>
							<th width="100px" align="center">Balance</th>
						</tr>
						<?php
							if(is_array($sumr_invoice_sql)) {
								
								$datasets = "customerid,room_type_id,roomid";
								$ths_invoice = "";

								$wgt_pyi_payment = 0;

								$wgt_pyi_other_charges = 0;
								$wgt_pyi_refund = 0;

								$wgt_pyi_amount = 0;
								$wgt_pyi_discount = 0;
								$wgt_pyi_tax = 0;
								$wgt_pyi_servicecharge = 0;
								$wgt_pyi_consumption = 0;
								$wgt_pyi_total_tax = 0;
								$wgt_pyi_total_amount = 0;
								$wgt_pyi_balance = 0;
								$wgt_pyi_total_famount = 0;

								$pyi_queryset2x = ""; $pyi_queryset2 = "";
								$pyi_queryset3 = ""; $pyi_queryset4 = "";
								$pyi_queryset5 = ""; $pyi_queryset5 = "";
								$pyi_queryset6 = ""; $pyi_queryset7 = "";
								$pyi_queryset8 = ""; $pyi_queryset9 = "";
								$pyi_queryset10 = ""; $pyi_queryset11 = "";
								$pyi_queryset12 = ""; $pyi_queryset13 = "";

								foreach($sumr_invoice_sql as $key => $value) {
									
									$taxes_rw = array();
									$ths_invoice = $value['invoice_number'];

									$additionalQuery = " LIMIT 1";
									$gs_invoice_query = array("booking_number"=>$booking_number,"invoice_number"=>$ths_invoice,"deletedata"=>0);
									$gs_invoice_sql = mysqli_data_fetch($tbL134,$datasets,$gs_invoice_query,'noarray');

									$customer_fname = idget_data($tbL102,$gs_invoice_sql[0],'fname');

									/*$datapack_arr['invoice'] = $ths_invoice;
									$datapack_arr['customer'] = $gs_invoice_sql[0];
									$datapack_arr['roomtype'] = $gs_invoice_sql[1];
									$datapack_arr['room'] = $gs_invoice_sql[2];*/

									if($wgt_bill_type == 'Corporate') {
										$pyi_queryset2 = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND biller={$wgt_bill_to} AND isreversed=0 AND deletedata=0";
										$pyi_queryset2x = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND biller={$wgt_bill_to} AND isreversed=0 AND ispaid=0 AND deletedata=0";
									} else {
										$pyi_queryset2 = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND isreversed=0 AND deletedata=0";
										$pyi_queryset2x = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND isreversed=0 AND ispaid=1 AND deletedata=0";
									}


									$pyi_queryset3 = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND deletedata=0";
									$pyi_queryset4 = "booking_number='{$booking_number}' AND deletedata=0";

									$pyi_sqlset5 = "SUM(room_amount)";
									$pyi_sqlset6 = "SUM(discount_amount)";
									$pyi_sqlset7 = "SUM(tax_amount)";
									$pyi_sqlset8 = "SUM(consumption_tax_amount)";
									$pyi_sqlset9 = "SUM(service_charge)";
									$pyi_sqlset10 = "SUM(amount)";
									$pyi_sqlset11 = "SUM(bill_amount)";
									$pyi_sqlset12 = "SUM(refund)";

									$wgt_pyi_other_charges_paid = mysqli_arithmetic_data($tbL100,$pyi_sqlset11,$pyi_queryset2x);
									$wgt_pyi_other_charges = mysqli_arithmetic_data($tbL100,$pyi_sqlset11,$pyi_queryset2);
									$wgt_pyi_refund = mysqli_arithmetic_data($tbL131,$pyi_sqlset12,$pyi_queryset3);;
									$wgt_pyi_amount = mysqli_arithmetic_data($tbL134,$pyi_sqlset5,$pyi_queryset3);
									$wgt_pyi_discount = mysqli_arithmetic_data($tbL134,$pyi_sqlset6,$pyi_queryset3);
									$wgt_pyi_tax = mysqli_arithmetic_data($tbL134,$pyi_sqlset7,$pyi_queryset3);
									$wgt_pyi_consumption = mysqli_arithmetic_data($tbL134,$pyi_sqlset8,$pyi_queryset3);
									$wgt_pyi_servicecharge = mysqli_arithmetic_data($tbL134,$pyi_sqlset9,$pyi_queryset3);

									$taxes_rw['tax'] = $wgt_pyi_tax;
									$taxes_rw['consumptiontax'] = $wgt_pyi_consumption;
									$taxes_rw['servicecharge'] = $wgt_pyi_servicecharge;

									array_push($taxes_arry,$taxes_rw);
									
									#payment, if is one invoice, check for number of rooms to allocate each payment
									$wgt_pyi_payment = mysqli_arithmetic_data($tbL131,$pyi_sqlset10,$pyi_queryset3);
									$wgt_pyi_payment = $wgt_pyi_payment + $wgt_pyi_other_charges_paid;
									
									$wgt_pyi_total_tax = $wgt_pyi_tax + $wgt_pyi_consumption + $wgt_pyi_servicecharge;
									$wgt_pyi_total_amount = ($wgt_pyi_amount + $wgt_pyi_total_tax) - $wgt_pyi_discount;
									$wgt_pyi_total_famount = $wgt_pyi_total_amount + $wgt_pyi_other_charges;

									if($wgt_balance <= 0) {
										$wgt_pyi_balance = 0; $wgt_bal_2pay = 0;
										//$wgt_pyi_payment = $wgt_pyi_total_famount;
									} else {
										if($wgt_pyi_payment > 0) { $wgt_pyi_balance = $wgt_pyi_total_famount - $wgt_pyi_payment; }
										else { $wgt_pyi_balance = $wgt_pyi_total_famount; }

										if($wgt_pyi_balance > 0) { $wgt_bal_2pay = $wgt_bal_2pay + $wgt_pyi_balance; }
									}

									//$datapack_arr['roombill'] = $wgt_pyi_total_famount;
									//$datapack_arr['roombill'] = $wgt_pyi_amount;

									$print_wgt_amount = write_amountF($gh_get_decimal_format,$wgt_pyi_amount);
									$print_wgt_discount = write_amountF($gh_get_decimal_format,$wgt_pyi_discount);
									$print_wgt_taxes = write_amountF($gh_get_decimal_format,$wgt_pyi_total_tax);
									$print_wgt_other_charges = write_amountF($gh_get_decimal_format,$wgt_pyi_other_charges);
									$print_wgt_payment = write_amountF($gh_get_decimal_format,$wgt_pyi_payment);
									$print_wgt_balance = write_amountF($gh_get_decimal_format,$wgt_pyi_balance);
									$print_wgt_refund = write_amountF($gh_get_decimal_format,$wgt_pyi_refund);

									?>
										<tr>
											<td width="200px" align="center"><?php echo $customer_fname; ?></td>
											<td width="100px" align="center"><a href="javascript:void(0)" class="blue-font" onclick="openinvoice('<?php echo $ths_invoice; ?>','<?php echo $booking_number; ?>')"><?php echo $ths_invoice; ?></a></td>
											<td width="100px" align="center"><?php echo $print_wgt_amount; ?></td>
											<td width="100px" align="center"><?php echo $print_wgt_discount; ?></td>
											<td width="100px" align="center"><?php echo $print_wgt_taxes; ?></td>
											<td width="150px" align="center"><?php echo $print_wgt_other_charges; ?></td>
											<td width="100px" align="center"><?php echo $print_wgt_payment; ?></td>
											<td width="100px" align="center"><?php echo $print_wgt_refund; ?></td>
											<td width="100px" align="center"><?php echo $print_wgt_balance; ?></td>
										</tr>
									<?php

									#group room per invoice and push data as assoc array
									$additionalQuery = " GROUP BY roomid";
									$gs_invoice_queryx = array("booking_number"=>$booking_number,"invoice_number"=>$ths_invoice,"deletedata"=>0);
									$gs_invoice_sqlx = mysqli_data_fetch($tbL134,'customerid,room_type_id,roomid',$gs_invoice_queryx,'array');
									
									$ths_roomid = "";
									
									foreach($gs_invoice_sqlx as $keyx => $valuex) {
										
										$datapack_arr = array();

										$ths_roomid = $valuex['roomid'];
										$pyi_queryset3x = "booking_number='{$booking_number}' AND invoice_number='{$ths_invoice}' AND roomid='{$ths_roomid}' AND deletedata=0";
										$pyi_sqlset5x = "SUM(room_amount)";

										$wgt_pyi_amountx = mysqli_arithmetic_data($tbL134,$pyi_sqlset5x,$pyi_queryset3x);

										$datapack_arr['invoice'] = $ths_invoice;
										$datapack_arr['customer'] = $valuex['customerid'];
										$datapack_arr['roomtype'] = $valuex['room_type_id'];
										$datapack_arr['room'] = $ths_roomid;
										$datapack_arr['roombill'] = $wgt_pyi_amountx;

										array_push($invoice_datapack,$datapack_arr);
									}
									#end here
								}
							}
						?>

					</table>
				</div>

				<div class="block-element box-border-thick pads10 top-push-5">
					<h4 class="large nobold default-text-font-bold alignct">Account Statement</h4>
					<table cellpadding="0" cellspacing="0" class="ft-xxsml-size top-push-5">
						<tr>
							<th width="30px" align="center">&nbsp;</th>
							<th width="100px" align="center">Date</th>
							<th width="300px" align="center">Description</th>
							<th width="150px" align="center">Invoice#</th>
							<th width="100px" align="center">Discount</th>
							<th width="120px" align="center">Bill Posts</th>
							<th width="120px" align="center">Amount</th>
							<th width="120px" align="center">Payment</th>
						</tr>

						<?php
							$additionalQuery = "";
							if(is_array($invoice_datapack) && count($invoice_datapack) > 0) {
								
								$customer_fname = ""; $customer_lname = "";
								$customer_roomtype = ""; $customer_roomno = ""; $customer_roomprfx = "";
								$customer_arr = ""; $customer_dpt = ""; $print_roombill = "";
								
								for($i=0; $i<count($invoice_datapack); $i++) {
									
									$customer_fname = idget_data($tbL102,$invoice_datapack[$i]['customer'],'fname');
									$customer_lname = idget_data($tbL102,$invoice_datapack[$i]['customer'],'lname');
									$customer_roomtype = idget_data($tbL52,$invoice_datapack[$i]['roomtype'],'name');
									$customer_roomno = idget_data($tbL56,$invoice_datapack[$i]['room'],'roomnumber');
									$customer_roomprfx = idget_data($tbL56,$invoice_datapack[$i]['room'],'roomprefix');

									$sql_arr_dpt_query = array("booking_number"=>$booking_number,"roomid"=>$invoice_datapack[$i]['room']);
									$sql_arr_dpt_data = mysqli_data_fetch($tbL127,'checkin_date,checkout_date',$sql_arr_dpt_query,'noarray');

									$print_arr_dpt_checkin = write_dateF($gh_get_date_format,$sql_arr_dpt_data[0]);
									$print_arr_dpt_checkout = write_dateF($gh_get_date_format,$sql_arr_dpt_data[1]);

									$print_roombill = write_amountF($gh_get_decimal_format,$invoice_datapack[$i]['roombill']);
									

									?>
										<tr>
											<td colspan="8" class="grey-theme left-pull-10">
												Guest: <?php echo $customer_fname.' '.$customer_lname; ?> - <?php echo $customer_roomtype; ?> (<?php echo $customer_roomno; ?>)
											</td>
										</tr>
										<tr>
											<td width="30px" align="center">&nbsp;</td>
											<td colspan="2" align="left"><?php echo $customer_roomprfx.$customer_roomno; ?> - Arr: <?php echo $print_arr_dpt_checkin; ?> to Dept: <?php echo $print_arr_dpt_checkout; ?></td>
											<td width="150px" align="center">&nbsp;</td>
											<td width="100px" align="center">&nbsp;</td>
											<td width="120px" align="center">&nbsp;</td>
											<td width="120px" align="right">&#8358; <?php echo $print_roombill; ?></td>
											<td width="120px" align="center">&nbsp;</td>
										</tr>
									<?php
									$datasets = "id,invoice_number,discount_amount,room_amount,bill_date";
									$sql_billpost_query = array("booking_number"=>$booking_number,"invoice_number"=>$invoice_datapack[$i]['invoice'],"roomid"=>$invoice_datapack[$i]['room'],"ischarged"=>1,"deletedata"=>0);
									$sql_billpost_data = mysqli_data_fetch($tbL134,$datasets,$sql_billpost_query,'array');

									if(is_array($sql_billpost_data)) {
										$print_bill_date = ""; $print_bp_discount = ""; $print_bp_roomcharge = "";
										foreach($sql_billpost_data as $key => $value) {
											$print_bill_date = write_dateF($gh_get_date_format,$value['bill_date']);
											$print_bp_discount = write_amountF($gh_get_decimal_format,$value['discount_amount']);
											$print_bp_roomcharge = write_amountF($gh_get_decimal_format,$value['room_amount']);

											$total_billpost_amount = $total_billpost_amount + $value['room_amount'];

											?>
												<tr>
													<td width="30px" align="center"><input type="checkbox" name="billpost[]" value="<?php echo $value['id']; ?>"></td>
													<td width="100px" align="center"><?php echo $print_bill_date; ?></td>
													<td width="300px" align="center">Room Charge</td>
													<td width="150px" align="center"><?php echo $value['invoice_number']; ?></td>
													<td width="100px" align="right"><?php echo $print_bp_discount; if((!isset($wgt_settled_booking) || $wgt_settled_booking == 0) && $wgt_bill_to == 0) { if(isset($allowDiscount) && $allowDiscount == 200) { ?> <a href="javascript:void(0)" class="royal-blue-font ft-xxsml-size" title="Apply discount" onclick="startdiscount(<?php echo $value['id']; ?>,'<?php echo $value['room_amount']; ?>')">Discount</a><?php } } ?></td>
													<td width="120px" align="right">&#8358; <?php echo $print_bp_roomcharge; ?></td>
													<td width="120px" align="center">&nbsp;</td>
													<td width="120px" align="center">&nbsp;</td>
												</tr>
											<?php
										}
									}
								}
							}
						?>

						<tr><td colspan="8" class="grey-theme left-pull-10 default-text-font-bold dark-grey-font">Payment Details</td></tr>
						
						<?php
							
							$py_datasets = "id,invoice_number,receipt_number,amount,sales_description,datelogged,timelogged,transaction_type,deletedata";
							$sql_py_query = array("booking_number"=>$booking_number,"isreversed"=>0,"deletedata"=>0);
							$sql_py_data = mysqli_data_fetch($tbL131,$py_datasets,$sql_py_query,'array');

							if(is_array($sql_py_data)) {
								$print_py_date = ""; $print_py_amount = ""; $total_py_amount = 0; $reverseday = 0;
								foreach($sql_py_data as $key => $value) {
									
									$total_py_amount = $total_py_amount + $value['amount'];

									$print_py_date = write_dateF($gh_get_date_format,$value['datelogged']);
									$print_py_amount = write_amountF($gh_get_decimal_format,$value['amount']);
									$reverseday = dayDiffs($server_get_date,$value['datelogged']);

									?>
										<tr>
											<td width="30px" align="center"><input type="checkbox" name="payment[]" value="<?php echo $value['id']; ?>"></td>
											<td width="100px" align="center"><?php echo $print_py_date; ?></td>
											<td width="300px" align="center"><?php echo $value['sales_description']; ?> <span class="noprint"><a href="frontdesk/receipt.php?getreceipt=<?php echo $value['id']; ?>" title="Print Receipt"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/checkin_card_icon.png"></a></span> <?php if((isset($reverseday) && $reverseday <= $gh_get_allow_past_reverse) && (isset($allowReverse) && $allowReverse == 200) && (($wgt_reservation == 'Checking In' || $wgt_reservation == 'Reserving') && $value['transaction_type'] == 'credit' && !strstr($value['sales_description'],'Paid using Coupon'))) { ?><span class="noprint"><a href="javascript:void(0)" class="blue-font left-push-5" title="Reverse Payment" onclick="doReverse('?logs=modals&prefix=frontdesk&param=<?php echo $param; ?>&ftoken=<?php echo $booking_number; ?>&stoken=<?php echo $ths_token; ?>&reverse=<?php echo $value['id']; ?>')">Reverse</a></span><?php } ?></td>
											<td width="150px" align="center"><?php echo $value['invoice_number']; ?></td>
											<td width="100px" align="center">&nbsp;</td>
											<td width="120px" align="center">&nbsp;</td>
											<td width="120px" align="center">&nbsp;</td>
											<td width="120px" align="right">&#8358; <?php echo $print_py_amount; ?></td>
										</tr>
									<?php
								}
							}

						?>

						<tr>
							<td colspan="8" class="grey-theme left-pull-10 default-text-font-bold dark-grey-font">
								<?php
									if(isset($wgt_bill_type) && $wgt_bill_type == 'Corporate') {
										?><span id="ex-tract" class="noshow float-right"><input type="submit" name="billExtractor" value="Extract Bill" title="Extract bill from invoice and make payment"></span><?php
									}
								?>
								Outlet Charges
							</td>
						</tr>

						<?php
							
							$additionalQuery = "";
							
							if(is_array($invoice_datapack) && count($invoice_datapack) > 0) {
								
								$customer_fname = ""; $customer_lname = ""; $block_id = ""; $block_name = "";
								$customer_roomtype = ""; $customer_roomno = ""; $customer_roomprfx = "";

								$invoice_datapack = array_unique($invoice_datapack);

								for($i=0; $i<count($invoice_datapack); $i++) {
									
									/*$customer_fname = idget_data($tbL102,$invoice_datapack[$i]['customer'],'fname');
									$customer_lname = idget_data($tbL102,$invoice_datapack[$i]['customer'],'lname');
									$customer_roomtype = idget_data($tbL52,$invoice_datapack[$i]['roomtype'],'name');
									$customer_roomno = idget_data($tbL56,$invoice_datapack[$i]['room'],'roomnumber');
									$customer_roomprfx = idget_data($tbL56,$invoice_datapack[$i]['room'],'roomprefix');
									$block_id = idget_fdata($tbL56,'id',$invoice_datapack[$i]['room'],'blockid');
									$block_name = idget_data($tbL49,$block_id,'name');*/

									
									if($wgt_bill_type == 'Corporate') {
										//$sql_arr_pos_query = array("booking_number"=>$booking_number,"biller"=>$wgt_bill_to,"roomid"=>$invoice_datapack[$i]['room'],"isreversed"=>0,"deletedata"=>0);
										$sql_arr_pos_query = array("booking_number"=>$booking_number,"biller"=>$wgt_bill_to,"isreversed"=>0,"deletedata"=>0);
									} else {
										//$sql_arr_pos_query = array("booking_number"=>$booking_number,"roomid"=>$invoice_datapack[$i]['room'],"isreversed"=>0,"deletedata"=>0);
										$sql_arr_pos_query = array("booking_number"=>$booking_number,"isreversed"=>0,"deletedata"=>0);
									}

									$additionalQuery = " AND status NOT IN('Pending')";

									$sql_arr_pos_data = mysqli_data_fetch($tbL100,'id,posid,order_number,bill_amount,customerid,billtype,roomid,datelogged',$sql_arr_pos_query,'array');

									$additionalQuery = "";

									if(is_array($sql_arr_pos_data)) {

										$wgtoutlet = ""; $print_posbill = ""; $print_posdate = "";

										foreach($sql_arr_pos_data as $key => $value) {
											
											$print_posbill = write_amountF($gh_get_decimal_format,$value['bill_amount']);
											$print_posdate = write_dateF($gh_get_date_format,$value['datelogged']);
											$wgtoutlet = idget_data($tbL14,$value['posid'],'posname');

											if(!empty($value['customerid']) && $value['customerid'] > 0) {
												$customer_fname = idget_data($tbL102,$value['customerid'],'fname');
												$customer_lname = idget_data($tbL102,$value['customerid'],'lname');
											} else {
												$customer_fname = "";
												$customer_lname = "";
											}
											
											if((!empty($value['billtype']) && $value['billtype'] == 2) && (!empty($value['roomid']) && $value['roomid'] > 0)) {
												$customer_roomno = idget_data($tbL56,$value['roomid'],'roomnumber');
												$customer_roomprfx = idget_data($tbL56,$value['roomid'],'roomprefix');
												$block_id = idget_fdata($tbL56,'id',$value['roomid'],'blockid');
												$block_name = idget_data($tbL49,$block_id,'name');
											} else {
												$customer_roomno = "NA";
												$customer_roomprfx = "";
												$block_id = 0;
												$block_name = "";
											}
								
											?>
												<tr>
													<td width="30px" align="center"><input type="checkbox" name="pos[]" value="<?php echo $value['id']; ?>" onclick="pfchk(this)" lang="off" class="extr"></td>
													<td width="100px" align="center"><?php echo $print_posdate; ?></td>
													<td width="300px" align="center"><?php echo $wgtoutlet; ?> (<a href="<?php echo DOMAIN_URL.PUB_FLD.'admin/pos/preview_receipt'.PHP_EXT.'?order='.$value['order_number']; ?>&receipt=0" class="blue-font" target="_blank"><?php echo $value['order_number']; ?></a> order for <?php echo $customer_fname.' '.$customer_lname; ?> - <?php echo $customer_roomprfx.$customer_roomno.' ['.$block_name.']'; ?>)</td>
													<td width="150px" align="center"><?php echo $invoice_datapack[$i]['invoice']; ?></td>
													<td width="100px" align="center">&nbsp;</td>
													<td width="120px" align="center">&nbsp;</td>
													<td width="120px" align="right">&#8358; <?php echo $print_posbill; ?></td>
													<td width="120px" align="center">&nbsp;</td>
												</tr>
											<?php

											$customer_fname = "";
											$customer_lname = "";
											$customer_roomtype = "";
											$customer_roomno = "";
											$customer_roomprfx = "";
											$block_id = "";
											$block_name = "";
										}
									}

								}
							}

							$total_billed_amt = $wgt_other_charges + $wgt_total_room_tariff;
							$print_tot_billpst_amt = write_amountF($gh_get_decimal_format,$total_billpost_amount);
							$print_tot_billed_amt = write_amountF($gh_get_decimal_format,$total_billed_amt);
							$print_total_py_amount = write_amountF($gh_get_decimal_format,$total_py_amount);


							#add all taxes together
							if(is_array($taxes_arry) && count($taxes_arry) > 0) {
								
								$total_base_tax = 0;
								$total_base_consumption = 0;
								$total_base_servicecharge = 0;

								for($t=0; $t < count($taxes_arry); $t++) {
									if($taxes_arry[$t]['tax'] > 0) { $total_base_tax = $total_base_tax + $taxes_arry[$t]['tax']; }
									if($taxes_arry[$t]['consumptiontax'] > 0) { $total_base_consumption = $total_base_consumption + $taxes_arry[$t]['consumptiontax']; }
									if($taxes_arry[$t]['servicecharge'] > 0) { $total_base_servicecharge = $total_base_servicecharge + $taxes_arry[$t]['servicecharge']; }
								}
							}

							//$total_base_amount = $total_billed_amt + $total_base_tax + $total_base_consumption + $total_base_servicecharge;

							$total_base_amount = ($total_billed_amt + $total_base_tax + $total_base_consumption + $total_base_servicecharge) - $wgt_total_room_discount;

							$print_total_base_tax = write_amountF($gh_get_decimal_format,$total_base_tax);
							$print_total_base_consumption = write_amountF($gh_get_decimal_format,$total_base_consumption);
							$print_total_base_servicecharge = write_amountF($gh_get_decimal_format,$total_base_servicecharge);
							$print_total_base_amount = write_amountF($gh_get_decimal_format,$total_base_amount);

							$total_base_balance = $total_base_amount - $total_py_amount;
							$print_total_base_balance = write_amountF($gh_get_decimal_format,$total_base_balance);

							$amt2refund = 0;

							if($total_base_balance > 0 && ($wgt_bill_type == 'Guest' || $wgt_bill_type == 'Group Owner')) {
								$amt2refund = 0;
								$balance_label = "Amount to be received";
								$color_code = "light-red-font";
								$isrebate = 0;
								
								if(isset($allowRebate) && $allowRebate == 200) {
									$actionLink = '&#8618; <a href="javascript:void(0)" name="'.$total_base_balance.'" lang="'.$booking_number.'" class="royal-blue-font" title="Click to rebate outstanding" onclick="rff(0,this.name,this.lang)">Apply Rebate</a>';
								} else {
									$actionLink = '';
								}

							} elseif($total_base_balance < 0 && ($wgt_bill_type == 'Guest' || $wgt_bill_type == 'Group Owner')) {
								$amt2refund = $total_base_balance;
								$balance_label = "Amount to be refunded";
								$color_code = "blue-font";
								$isrebate = 1;

								$actionLink = '&#8618; <a href="javascript:void(0)" name="'.$total_base_balance.'" lang="'.$booking_number.'"class="royal-blue-font" title="Click to refund balance" onclick="rff(1,this.name,this.lang)">Apply Refund</a>';

							} else {
								$amt2refund = 0;
								if($wgt_booking_type == 'corporate' && $wgt_bill_type == 'Corporate') { $balance_label = "Bill to Corporate"; } else { $balance_label = ""; }
								
								$color_code = "dark-grey-font";
								$isrebate = 9;
								$actionLink = "";
							}

							if($isrebate == 1) {
								//check if balance is already pushed to coupon mode
								$isrebate_query = array("booking_number"=>$booking_number);
								$isrebateChk = mysqli_data_checkr($tbL129,'(*)',$isrebate_query);
							}
							
						?>

						<tr><td colspan="8">&nbsp;</td></tr>
						<tr>
							<td width="30px" align="center">&nbsp;</td>
							<td width="100px" align="center">&nbsp;</td>
							<td width="300px" align="center" class="default-text-font-bold">Total</td>
							<td width="150px" align="center">&nbsp;</td>
							<td width="100px" align="right" class="default-text-font-bold">&#8358; <?php echo $print_discount; ?></td>
							<td width="120px" align="right" class="default-text-font-bold">&#8358; <?php echo $print_tot_billpst_amt; ?></td>
							<td width="120px" align="right" class="default-text-font-bold">&#8358; <?php echo $print_tot_billed_amt; ?></td>
							<td width="120px" align="right" class="default-text-font-bold">&#8358; <?php echo $print_total_py_amount; ?></td>
						</tr>
						<tr><td colspan="8" class="box-noborder">&nbsp;</td></tr>
						<tr>
						<td colspan="7" align="right">
							<div class="block-element bottom-push-5">Total for the period (<?php echo $print_wgt_checkin_date; ?> - <?php echo $print_wgt_checkout_date; ?>) &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_tot_billed_amt; ?></b></div>
							<div class="block-element bottom-push-5">Total Discount &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_discount; ?></b></div>
							
							<?php if($htx2 == 1): ?>
							<div id="htx-service" class="block-element bottom-push-5" ondblclick="removechild('htx-service')">Service Charge &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_base_servicecharge; ?></b></div>
							<?php endif; ?>

							<?php if($htx3 == 1): ?>
							<div id="htx-vat" class="block-element bottom-push-5" ondblclick="removechild('htx-vat')">VAT &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_base_tax; ?></b></div>
							<?php endif; ?>

							<?php if($htx1 == 1): ?>
							<div id="htx-consumption" class="block-element bottom-push-5" ondblclick="removechild('htx-consumption')">Consumption Tax &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_base_consumption; ?></b></div>
							<?php endif; ?>

							<div class="block-element bottom-push-5">Total Amount &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_base_amount; ?></b></div>

							<?php if($wgt_booking_type == 'corporate' && $wgt_bill_type == 'Corporate'): ?>
								<div class="block-element bottom-push-5"></div>
							<?php else: ?>
								<div class="block-element bottom-push-5">Total Paid &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_py_amount; ?></b></div>
							<?php endif; ?>

							<?php
								
								if(isset($isrebate) && $isrebate == 0) {
									$color_code = "light-red-font"; $settle4Bal = 1;
									?>
										<span class="float-left left-push-50 ft-xsml-size dark-grey-font">
											Note: Apply rebate or receive funds
										</span>
									<?php
								} elseif((isset($isrebate) && $isrebate == 1) && ($isrebateChk == false || $total_base_balance < 0)) {
										$color_code = "blue-font"; $settle4Bal = 1;
									?>
										<span class="float-left left-push-50 ft-xsml-size dark-grey-font">
											Note: Refund or use as coupon
										</span>
									<?php
								} elseif((isset($isrebate) && $isrebate == 1) && ($isrebateChk == true || $total_base_balance == 0)) {
										$color_code = "dark-grey-font"; $settle4Bal = 0; $amount2settle = 0;
									?>
										<span class="float-left left-push-50 ft-xsml-size default-text-font-bold dark-blue-font">
											Note: Payment settled
										</span>
									<?php
								}
							?>

							<div class="block-element bottom-push-5 <?php echo $color_code; ?>"><?php echo $balance_label; ?> &nbsp; <b class="nobold default-text-font-bold">&#8358; <?php echo $print_total_base_balance; ?></b></div> <?php echo $actionLink; ?>
							<br><br><br>
						</td>
						<td width="120px">
							&nbsp;
						</td>
						</tr>
						<tr><td colspan="8" class="box-noborder">
							<br><div class="float-left ft-sml-size default-text-font-bold">Date:</div>
							<div class="float-right ft-sml-size default-text-font-bold">Guest Signature</div>
							<br><br><br>
						</td></tr>
					</table>
				</div>
			</fieldset>
		</div>
	</form>
</div>

<?php

	$avoid_dupl = array(); $invoice_option = ""; $invoice_string = "";
	for($inv=0; $inv<count($invoice_datapack); $inv++) { if(!in_array($invoice_datapack[$inv]['invoice'],$avoid_dupl)) { array_push($avoid_dupl, $invoice_datapack[$inv]['invoice']); if(!empty($invoice_datapack[$inv]['invoice']) && $invoice_datapack[$inv]['invoice'] != 'EARLYCHECKIN' && $invoice_datapack[$inv]['invoice'] != 'LATECHECKOUT') { $invoice_option .= '<option value="'.$invoice_datapack[$inv]['invoice'].'">'.$invoice_datapack[$inv]['invoice'].'</option>'; $invoice_string .= $invoice_datapack[$inv]['invoice'].','; } } }

	$payment_mode = select_dt_fetch('iscounter','Yes',$tbL24,'id','name');
	$payment_mode2 = select_dt_fetch('iscounter','No',$tbL24,'id','name');
	
	$get_coupon = array("coupon_status"=>"Unused","status"=>1,"deletedata"=>0);
	$get_coupon_data = mysqli_data_fetch($tbL129,'coupon_code',$get_coupon,'array');


	if(isset($wgt_settled_booking) && $wgt_settled_booking == 0 && ($wgt_booking_type == 'individual' || $wgt_bill_type == 'Guest')) {

		?>
			<div id="paymentbox" class="block-element grey-1-theme pads7 top-push-50 ft-xsml-size">
				<form action="" method="post" autocomplete="off">
					<input type="hidden" name="pstbookingnumber" id="pstbookingnumber" value="<?php echo $booking_number; ?>">
					<input type="hidden" name="pstbiller" id="pstbiller" value="<?php echo $wgt_bill_to; ?>">
					<fieldset>
						<legend><a href="javascript:void(0)" id="p1" class="dark-black-font" onclick="chgclass('vast-payment','pads7'); chgclass('coupon-payment','pads7 noshow'); chgclass('p1','dark-black-font'); chgclass('p2','blue-font')">Payments</a> or <a href="javascript:void(0)" id="p2" class="blue-font" onclick="chgclass('vast-payment','pads7 noshow'); chgclass('coupon-payment','pads7'); chgclass('p1','blue-font'); chgclass('p2','dark-black-font')">Coupon</a></legend>
						<div id="coupon-payment" class="pads7 noshow">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="100px" align="center">&nbsp;</td>
									<td width="150px" align="center">Coupon Code</td>
									<!--<td width="150px" align="center">Amount Paying</td>-->
									<td width="200px" align="center">Detail</td>
									<td width="100px" align="center">&nbsp;</td>
									<td width="150px" align="center">Credit to Invoice</td>
									<td width="150px" align="center" class="box-noborder">&nbsp;</td>
								</tr>
								<tr>
									<td width="100px">
										<select name="mode4coupon" id="mode4coupon">
											<?php echo $payment_mode2; ?>
										</select>
									</td>
									<td width="150px">
										<input list="couponcodes" name="couponcode" id="couponcode" autocomplete="off" onchange="getdata('coupon-balance','eget-coupon-balance','couponcode','div')">
										<datalist id="couponcodes">
											<?php
												if(is_array($get_coupon_data)) {
													foreach($get_coupon_data as $cpkey => $cpvalue) {
														?>
															<option value="<?php echo $cpvalue['coupon_code']; ?>">
														<?php
													}
												}
											?>
										</datalist>
									</td>
									<!--<td width="150px">
										<input type="text" name="coupon-amount" id="coupon-amount" pattern="\d*">
									</td>-->
									<td width="150px">
										<input type="text" name="detail2" id="detail2" onkeypress="jsinfo(this.value,1)" readonly="readonly">
										<div id="detail-suggest-f" class="noshow motion"></div>
									</td>
									<td width="150px" class="grey-theme">
										<div id="coupon-balance" class="ft-sml-size left-pull-30"></div>
									</td>
									<td width="100px">
										<select name="crdinvoice2" id="crdinvoice2">
											<?php if((is_array($avoid_dupl) && count($avoid_dupl) > 1) && (isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner')) { ?><option value="All">All</option><?php } else { ?><option value="">Choose?</option><?php } echo $invoice_option; ?>
										</select>
										<?php if((is_array($avoid_dupl) && count($avoid_dupl) > 1) && (isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner')) { ?><input type="hidden" name="isgroupinvoice" value="1"><?php } ?>
									</td>
									<td width="180px" class="alignrt box-noborder">
										<input type="submit" name="couponpaymentbutton" id="couponpaymentbutton" value="Make Payment" class="blue-white-state default-text-font-bold top-pull-10 bottom-pull-10 rounded-button nc-width-80 anchor" onclick="document.getElementById('couponpaymentbutton').value='Processing..'; setTimeout(() => { document.getElementById('couponpaymentbutton').setAttribute('type','button'); },200)">
									</td>
								</tr>
							</table>
						</div>
						<div id="vast-payment" class="pads7">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="100px" align="center">Mode</td>
									<!--<td width="100px" align="center">Amount</td>-->
									<td width="70px" align="center">Cheq. No</td>
									<!--<td width="70px" align="center">Receipt</td>-->
									<td width="150px" align="center">Description</td>
									<td width="150px" align="center">Credit to Invoice</td>
									<td width="150px" align="center" class=" box-noborder">&nbsp;</td>
								</tr>
								<tr>
									<td width="150px">
										<select name="paymentmode" id="paymentmode" onchange="allowpay(this.value)">
											<option value="" selected>Payment Mode?</option>
											<?php echo $payment_mode; ?>
										</select>
									</td>
									<!--<td width="100px">
										<input type="text" name="amountdeposited" id="amountdeposited" pattern="\d*">
									</td>-->
									<td width="150px">
										<input type="text" name="chequenumber" id="chequenumber">
									</td>
									<!--<td width="70px">
										<input type="text" name="receipt" id="receipt">
									</td>-->
									<td width="150px">
										<input type="text" name="detail" id="detail" onkeypress="jsinfo(this.value,2)">
										<div id="detail-suggest-x" class="noshow motion"></div>
									</td>
									<td width="100px">
										<!--  onchange="enterinvoicevalue(this.value)" -->
										<select name="crdinvoice" id="crdinvoice">
											<?php if((is_array($avoid_dupl) && count($avoid_dupl) > 1) && (isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner')) { ?><option value="All">All</option><?php } else { ?><option value="">Choose?</option><?php } echo $invoice_option; ?>
										</select>
										<?php if((is_array($avoid_dupl) && count($avoid_dupl) > 1) && (isset($wgt_bill_type) && $wgt_bill_type == 'Group Owner')) { ?><input type="hidden" name="isgroupinvoice" value="1"><?php } ?>
									</td>
									<td width="180px" class="alignrt box-noborder">
										<input type="submit" name="vastpaymentbutton" id="vastpaymentbutton" value="Make Payment" class="noshow motion" onclick="document.getElementById('vastpaymentbutton').value='Processing..'; setTimeout(() => { document.getElementById('vastpaymentbutton').setAttribute('type','button'); },200)">
									</td>
								</tr>
							</table>
						</div>
						<div id="pay-box"></div>
						<input type="hidden" name="amountdeposited" id="amountdeposited" value="<?php echo $wgt_bal_2pay; ?>">
						<div id="allinvoice" class="noshow top-push-10">
						</div>
					</fieldset>
					<input type="hidden" name="totalbal2pay" id="totalbal2pay" value="<?php echo $wgt_pyi_balance; ?>">
					<input type="hidden" id="invoiceString" value="<?php echo $invoice_string; ?>">
				</form>
			</div>
		<?php

	}
	 
		
	if((isset($wgt_reservation) && ($wgt_reservation == 'Checking Out' || $wgt_reservation == 'Cancelling' || $wgt_reservation == 'No Show')) && (round($wgt_balance,2) <= 0 && $wgt_settled_booking == 0)) {
		?>
			<div id="paymentbox" class="block-element pads20 top-push-50 ft-xsml-size alignct">
				<a href="javascript:void(0)" class="top-pull-15 right-pull-50 bottom-pull-15 left-pull-50 blue-white-state sml-rounded-button default-text-font-bold" onclick="settlebk('<?php echo $booking_number; ?>','<?php echo $amt2refund; ?>')">Settle Booking</a>
			</div>
		<?php
	} elseif(isset($wgt_reservation) && $wgt_reservation == 'Checking Out' && $wgt_settled_booking == 0 && ($wgt_bill_type == 'corporate' || $wgt_bill_type == 'Corporate' || $wgt_booking_type == 'complimentary' || $wgt_booking_type == 'Complimentary')) {
		?>
			<div id="paymentbox" class="block-element pads20 top-push-50 ft-xsml-size alignct">
				<a href="javascript:void(0)" class="top-pull-15 right-pull-50 bottom-pull-15 left-pull-50 blue-white-state sml-rounded-button default-text-font-bold" onclick="settlebk('<?php echo $booking_number; ?>','<?php echo $amt2refund; ?>')">Settle Booking</a>
			</div>
		<?php
	}

	$noshowBalance = "";

	if($wgt_bill_type == 'Corporate' || $wgt_bill_type == 'Complimentary') { $noshowBalance = 'yes'; }
	else { $noshowBalance = 'no'; }
	
?>

<p class="top-pull-30 alignct">
	<input type="button" value="Print" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 dark-black-white-state sml-rounded-button anchor ft-mini-size" onclick="printSheet()">
</p>

<div id="wgt-pos-receipt" class="noshow">
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<div id="wgt-apply-discount" class="xfadein motion" align="center">
	<div id="wgt-apply-discount-box" class="noshow motion">
		<div class="nc-height-10"></div>
		<div class="fx-width-40 white-theme xsml-rounded-button pads30 obj-dark-shadow alignlt noscroll">
			<form action="" method="post" autocomplete="off">
				<h3 class="large nobold default-text-font-bold nomargin">Enter discount value (N or %)</h3>
				<h4 class="large nobold light-red-font">Indicate your discount in percentage (e.g 12.5) or naira amount (e.g 5000)</h4>
				<div class="top-push-10 bottom-push-30 bottom-pull-10"><input type="number" step="any" name="wgtappliediscount" id="wgtappliediscount" placeholder="0" required></div>
				<input type="hidden" name="wgtchargeid" id="wgtchargeid">
				<input type="hidden" name="wgtchargeamount" id="wgtchargeamount">
				<p class="bottom-pull-10"><input type="submit" name="discountedbutton" value="Apply" class="nc-width-100 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button"></p>
				<p class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="returnwin('wgt-apply-discount')">Cancel</a></p>
			</form>
		</div>
	</div>
</div>

<div id="wgt-invoice" class="xfadein motion" onclick="returnwin('wgt-invoice')" align="center">
	<p class="bottom-pull-20 alignlt"><a href="javascript:void(0)" class="white-font" onclick="returnwin('wgt-invoice')"><b class="fa-arrow-left fa-sml-size float-left right-push-20"></b> Back</a></p>
	<div id="wgt-invoice-box" class="noshow motion white-theme xsml-rounded-button noscroll">
		<iframe id="for-invoice" src="" marginheight="0" marginwidth="0" frameborder="0" scrolling="auto" width="100%" height="97%"></iframe>
	</div>
</div>

<script>
	
	window.addEventListener('load',function() {
		var print_amtAlert, amtAlert = "<?php echo $wgt_balance; ?>", amtAlert2 = "<?php echo number_format($wgt_balance,2); ?>", nobal = "<?php echo $noshowBalance; ?>";
		if(amtAlert > 0 && nobal == 'no') {
			print_amtAlert = 'Balance to be paid &#8358; '+numberFormat(amtAlert2);
			chgclass('balAlert','top-pull-7 bottom-pull-7 bottom-push-3 light-red-theme white-font ft-sml-size default-text-font-bold alignct anchor');
			writeObjheader('balAlert',print_amtAlert);
		}
	},false);


	function allowpay(payid) {
		
		if(payid !== null && payid != '') {
			
			chgclass('vastpaymentbutton','noshow motion');

			sqldatastring.sql = "SELECT * FROM paytype_tbl WHERE id="+payid;
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				wrdata = ajaxresult.datastring;
				data = wrdata[0];
				
				if(data.paytype == 'Two-way Payment') {
					
					vhtml = '<h4 class="xlarge nobold default-text-font-bold">Two-Way Payment:</h4><h4 class="large nobold black-font">Indicate value according to name pattern. For example Cash & Card: field 1 cash, field 2 card</h4>';
					vhtml += '<ul class="nolist">';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 right-pull-5">';
					vhtml += '<input type="text" lang="wgtf1" name="xwgtf1" id="xwgtf1" placeholder="0.00" class="default-text-font-bold" onkeyup="numberinputFormat(this.value,this.id,this.lang)"><input type="hidden" name="wgtf1" id="wgtf1" class="default-text-font-bold">';
					vhtml += '</li>';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 left-pull-5">';
					vhtml += '<input type="text" lang="wgtf2" name="xwgtf2" id="xwgtf2" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,this.lang)" class="default-text-font-bold"><input type="hidden" name="wgtf2" id="wgtf2" class="default-text-font-bold">';
					vhtml += '</li>';
					vhtml += '<li class="block-element new-line-space">';
					vhtml += '</li>';
					vhtml += '</ul>';

				} else if(data.paytype == 'One-way Payment') {

					vhtml = '<h4 class="xlarge nobold default-text-font-bold">One-Way Payment:</h4><h4 class="large nobold black-font">Indicate value only in the first field</h4>';
					vhtml += '<ul class="nolist">';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 right-pull-5">';
					vhtml += '<input type="text" lang="wgtf1" name="xwgtf1" id="xwgtf1" placeholder="0.00" oninput="numberinputFormat(this.value,this.id,this.lang)" class="default-text-font-bold"><input type="hidden" name="wgtf1" id="wgtf1" class="default-text-font-bold">';
					vhtml += '</li>';
					vhtml += '<li class="ln-display-box float-left nc-width-50 top-pull-7 left-pull-5">';
					vhtml += '<input type="number" min="1" name="wgtf2" id="wgtf2" placeholder="0.00" disabled>';
					vhtml += '</li>';
					vhtml += '<li class="block-element new-line-space">';
					vhtml += '</li>';
					vhtml += '</ul>';
				}
				
				chgclass('pay-box','cs-width-400 light-yellow-theme pads10 motion top-push-10 bottom-push-10');
				writeObjheader('pay-box',vhtml);
			
				setTimeout(() => {
					if(data.paytype == 'One-way Payment') {
						document.getElementById('xwgtf1').focus();
						chgclass('vastpaymentbutton','blue-white-state default-text-font-bold top-pull-10 bottom-pull-10 rounded-button nc-width-80 anchor motion');
					} else {
						document.getElementById('xwgtf1').focus();
						chgclass('vastpaymentbutton','blue-white-state default-text-font-bold top-pull-10 bottom-pull-10 rounded-button nc-width-80 anchor motion');
					}
				},1000);
			}

		} else {
			if(document.getElementById('vastpaymentbutton')) {
				chgclass('vastpaymentbutton','noshow motion');
			}
		}
	}


	function startdiscount(record,amt) {
		chgclass('wgt-apply-discount','fx-position-stick fscr zind-3 xfadeout txp5-white nc-height-100 y-scroll motion');
		chgclass('wgt-apply-discount-box','motion');
		htmlpassval(record,'wgtchargeid');
		htmlpassval(amt,'wgtchargeamount');
		setTimeout(function() { document.getElementById('wgt-apply-discount-box').scrollIntoView(); },200);
		//window.scrollTo(0,0);
	}

	function returnwin(win) {
		var modal = win;
		var modalbox = win+'-box';
		chgclass(modal,'xfadein motion');
		chgclass(modalbox,'noshow motion');
		htmlpassval('','wgtchargeid');
		htmlpassval('','wgtchargeamount');
	}

	function enterinvoicevalue(str) {
		if(str == 'All') {
			chgclass('allinvoice','left-pull-10 top-push-10');
			var htmlresult,i,invoice = (document.getElementById('invoiceString').value).split(',');
			
			htmlresult = '<h3 class="large nobold">Enter paid amount to the following invoice accordingly</h3>';

			for(i=0; i < invoice.length; i++) {
				if(invoice[i] != '') {
					htmlresult += '<div class="ln-display-box float-left nc-width-30 right-pull-20 bottom-pull-20">';
					htmlresult += '<div class="xform">';
					htmlresult += '<h4 class="large nobold default-text-font-bold">Amount for '+invoice[i]+'</h4>';
					htmlresult += '<input type="number" name="invoiceentity1[]" placeholder="e.g 10000" class="nopads no-back-black" required>';
					htmlresult += '<input type="hidden" name="invoiceentity2[]" value="'+invoice[i]+'">';
					htmlresult += '</div>';
					htmlresult += '</div>';
				}
			}

			htmlresult += '<input type="hidden" name="isgroupinvoice" value="1">';
			writeObjheader('allinvoice',htmlresult);

		} else {
			chgclass('allinvoice','noshow top-push-10');
			writeObjheader('allinvoice','');
		}
	}


	function openinvoice(invoice,bookingnumber) {
		
		chgclass('wgt-invoice','fx-position-stick fscr zind-3 xfadeout txp5-black pads30 nc-height-100 noscroll motion');
		chgclass('wgt-invoice-box','motion white-theme xsml-rounded-button noscroll');
		document.getElementById('for-invoice').src = filePath+'public/admin/workspacex.php?logs=modals&prefix=frontdesk&param=reservationinvoice&ftoken='+bookingnumber+'&stoken='+invoice;
	}


	function removechild(obj) {
		chgclass(obj,'noshow');
	}


	function doReverse(pg) {
		var askBefore = confirm('Are you sure you want to reverse?');
		if(askBefore == true) {
			window.location = filePath+'public/admin/workspace.php'+pg;
		}
	}


	function settlebk(booking,amt) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion nc-height-100 y-scroll');
		chgclass('rBox','fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		var namt = amt.replace('-','');
		var vhtml;
		
		if(amt < 0) {
			vhtml = '';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<div class="pads10 alignlt">';
			vhtml += '<label class="block-element bottom-push-10">Settling the booking requires you clearing guest refund. Do you want to refund or move as coupon?</label>';
			vhtml += '<input type="hidden" name="bookingnumber" id="bookingnumber" value="'+booking+'">';
			vhtml += '<input type="text" name="amount" id="amount" value="'+Math.round(namt,2)+'" class="default-text-font-bold" readonly>';
			vhtml += '<input type="hidden" name="issettled" id="issettled" value="yes">';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="submit" id="refundbutton" name="refundbutton" value="Refund & Settle" class="nc-width-45 blue-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2 right-push-20"><input type="submit" id="couponbutton" name="couponbutton" value="Coupon & Settle" class="nc-width-45 dark-black-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2">';
			vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
			vhtml += '</div>';
			vhtml += '</form>';
		} else {
			vhtml = '';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<div class="pads10 alignlt">';
			vhtml += '<label class="block-element bottom-push-10">Are you sure you want to settle the booking?</label>';
			vhtml += '<input type="hidden" name="bookingnumber" id="bookingnumber" value="'+booking+'">';
			vhtml += '<input type="hidden" name="amount" id="amount" value="'+Math.round(amt,2)+'">';
			vhtml += '<input type="hidden" name="issettled" id="issettled" value="yes">';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="submit" id="settlebutton" name="settlebutton" value="Yes, Settle" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2 right-push-20">';
			vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
			vhtml += '</div>';
			vhtml += '</form>';
		}
		
		writeObjheader('rBox',vhtml);
		setTimeout(() => { document.getElementById('rBox').scrollIntoView(); },200);
	}


	function rff(action,amt,booking) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion nc-height-100 y-scroll');
		chgclass('rBox','fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		var namt = amt.replace('-','');
		var vhtml;
		
		if(action == 1) {
			vhtml = '';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<div class="pads10 alignlt">';
			vhtml += '<label class="block-element bottom-push-10">You are making a refund to guest?</label>';
			vhtml += '<input type="hidden" name="bookingnumber" id="bookingnumber" value="'+booking+'">';
			vhtml += '<input type="text" lang="pstamount" name="amountx" id="amountx" placeholder="Enter here" value="'+numberFormat(namt)+'" minlength="2" class="default-text-font-bold" oninput="numberinputFormat(this.value,this.id,this.lang)" onkeyup="cEntry(this)">';
			vhtml += '<input type="hidden" name="pstamount" id="pstamount" value="'+namt+'" minlength="2">';
			vhtml += '<input type="hidden" name="pstamount2" id="pstamount2" value="'+namt+'">';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion" align="center">';
			vhtml += '<input type="submit" id="refundbutton" name="refundbutton" value="Refund" class="nc-width-100 blue-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2 right-push-20">';
			vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
			vhtml += '</div>';
			vhtml += '</form>';
		} else {
			vhtml = '';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<div class="pads10 alignlt">';
			vhtml += '<label class="block-element bottom-push-10">You are making a rebate from guest outstanding?</label>';
			vhtml += '<input type="hidden" name="bookingnumber" id="bookingnumber" value="'+booking+'">';
			vhtml += '<input type="text" lang="pstamount" name="amountx" id="amountx" placeholder="Enter here" value="'+numberFormat(namt)+'" minlength="2" class="default-text-font-bold" oninput="numberinputFormat(this.value,this.id,this.lang)" onkeyup="cEntry(this)">';
			vhtml += '<input type="hidden" name="pstamount" id="pstamount" value="'+namt+'" minlength="2">';
			vhtml += '<input type="hidden" name="pstamount2" id="pstamount2" value="'+namt+'">';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="submit" id="rebatebutton" name="rebatebutton" value="Rebate" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 rounded-button anchor letter-spacing-2 right-push-20">';
			vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
			vhtml += '</div>';
			vhtml += '</form>';
		}
		
		writeObjheader('rBox',vhtml);
		setTimeout(() => { document.getElementById('rBox').scrollIntoView(); },200);
	}


	function cEntry(obj) {
		setTimeout(() => {
			obj.placeholder = 'Enter here';
			var f1 = obj.lang, f2 = obj.lang+'2';
			if(eval(document.getElementById(f1).value) > eval(document.getElementById(f2).value)) {
				obj.value = ''; obj.placeholder = 'Invalid entry. Retry';
				document.getElementById(f1).value = 0;
			}
		},100);
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}


	function pfchk(obj) {
		if(document.getElementById('ex-tract')) {
			if(obj.lang == 'on') {
				obj.lang = 'off';
				chgclass('ex-tract','noshow float-right');

				var ischecked = 0, chkObjs = document.getElementsByClassName('extr');

				for(var j=0; j < chkObjs.length; j++) {
					if(chkObjs[j].getAttribute('lang') == 'on') {
						ischecked += 1;
					}
				}

				if(ischecked > 0) {
					chgclass('ex-tract','float-right right-pull-10');
				}

			} else if(obj.lang == 'off') {
				obj.lang = 'on';
				chgclass('ex-tract','float-right right-pull-10');
			}
		}
	}


	function jsinfo(val,div) {

		var obj,txtfield;

		if(div == 1) { obj = 'detail-suggest-f'; txtfield = "'detail2'"; }
		else if(div == 2) { obj = 'detail-suggest-x'; txtfield = "'detail'"; }

		chgclass(obj,'fx-position-flow zind-3 cs-width-300 cs-height-150 white-theme obj-light-shadow sml-rounded-button pads20 top-push-10 y-scroll motion');
		sqldatastring.sql = "SELECT * FROM transaction_payment_tbl WHERE detail REGEXP '^"+val+"'";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '<span class="float-right"><a id="nxj" href="javascript:void(0)" class="dark-grey-font"><b class="mbri-close"></b></a></span>';
			vhtml += '<h4 class="xlarge nobold black-font">Suggestion</h4><br>';

			for(i=0; i<data.length; i++) {
				vhtml += '<div class="bottom-push-10 anchor" title="'+data[i].detail+'" onclick="jsFil('+txtfield+',this.title)">';
				vhtml += '<h3 class="large nobold default-text-font-bold">'+data[i].detail+'</h3>';
				vhtml += '</div>';
			}

			writeObjheader(obj,vhtml);

			if(document.getElementById('nxj')) {
				document.getElementById('nxj').addEventListener('click',() => {
					chgclass(obj,'noshow motion');
				});
			}
		}
	}

	
	function jsFil(ids,name) {
		htmlpassval(name,ids);
	}


	function printSheet() {
		chgclass('rvh-h','bottom-push-30');
		var noprint = document.getElementsByClassName('noprint');
		for(var n=0; n < noprint.length; n++) { noprint[n].setAttribute('style','display: none'); }
		setTimeout(() => { window.print(); },500);
		setTimeout(() => { chgclass('rvh-h','noshow bottom-push-30'); },2000);
	}

</script>