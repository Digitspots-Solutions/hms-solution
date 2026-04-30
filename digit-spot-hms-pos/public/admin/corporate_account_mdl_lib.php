<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: manage corporate account for credit and debit transaction
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

	createDatabasetable($var_tbl_61); //create a table for this post

	$payment_mode = select_dt_fetch('deletedata',0,$tbL24,'id','name');
	$cspg = select_dt_fetch('status','Active',$tbL58,'id','name');
	
	$new_url = 200;
	
	if(isset($_GET['cspga'])) { $_SESSION['cspga'] = $_GET['cspga']; $new_url = 200; }
	else { if(isset($_SESSION['cspga'])) { $_SESSION['cspga'] = $_SESSION['cspga']; $new_url = 0; } else { $_SESSION['cspga'] = 0; $new_url = 200; } }
	
	$cspga = $_SESSION['cspga'];

	#keyword search
	if(isset($_GET['type']) && !empty($_GET['type'])) { $_SESSION['trtype'] = ucfirst($_GET['type']); }
	else { if(isset($_SESSION['trtype'])) { $_SESSION['trtype'] = $_SESSION['trtype']; } else { $_SESSION['trtype'] = 'All'; } }

	if($_SESSION['trtype'] == 'All') { $keywords = " ORDER BY id DESC"; }
	else { $keywords = " AND transaction_type IN('{$_SESSION['trtype']}') ORDER BY id DESC"; }

	if($cspga > 0) {
		$display = "block-element";
		$acctbutton = "";
		$corporate_name = idget_data($tbL58,$cspga,'name'); $corporate_id = $cspga;
		$credit_balance = idget_data($tbL58,$cspga,'creditlimit');
		$credit_limit = idget_data($tbL58,$cspga,'xcreditlimit');
		$notify_limit = idget_data($tbL58,$cspga,'notifylimit');
		$retainership = idget_data($tbL58,$cspga,'isretainership');
		$isfixed = idget_data($tbL58,$cspga,'isfixeddiscount');
		$discount = idget_data($tbL58,$cspga,'discount');

		$querycheck = "cspgid={$corporate_id} AND deletedata=0";
		$queryset = "cspgid={$corporate_id} AND deletedata=0".$keywords;

	} else {
		$display = "block-element";
		$acctbutton = " noshow";
		$corporate_name = ""; $corporate_id = 0;
		$credit_balance = 0;
		$credit_limit = 0;
		$notify_limit = 0;
		$retainership = "";
		$isfixed = "";
		$discount = 0;

		$querycheck = "deletedata=0";
		$queryset = "deletedata=0".$keywords;
	}

	#--------------------------------------------------------------------------------------------------------------

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	#--------------------------------------------------------------------------------------------------------------


	if(isset($_POST['transactionbutton'])) {

		$corporateid = escape_data($_POST['cspg']);
		$transaction_type = $_POST['transactiontype'];
		$transaction_date = $_POST['transactiondate'];
		$transaction_status = $_POST['transactionstatus'];
		$amount = escape_data($_POST['amount']);
		$pay_media = escape_data($_POST['paymode']);
		$chequeno = escape_data($_POST['chequenumber']);

		$pay_desc = "Payment received by ".idget_data($tbL24,$pay_media,'name');
		$detail = $pay_desc.' '.escape_data($_POST['remark']);

		$transaction_number = prgSequence($tbL155,'CPY');
		$iscredit = 0;

		if($transaction_type == 'Credit') { $new_credit_balance = $credit_balance + $amount; $iscredit = 1; $biller = "funding"; }
		elseif($transaction_type == 'Debit') { $new_credit_balance = $credit_balance - $amount; $iscredit = 0; $biller = "withdraw"; }

		$pst_query = array("cspgid"=>$corporateid,"status"=>"Pending");
		$pst_field = array("cspgid"=>$corporateid,"transaction_number"=>$transaction_number,"transaction_type"=>$transaction_type,"paymode"=>$pay_media,"cheque_number"=>$chequeno,"amount"=>$amount,"detail"=>$detail,"transaction_date"=>$transaction_date,"credit_balance"=>$new_credit_balance,"userid"=>$userSignedIn,"status"=>$transaction_status,"biller"=>$biller,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$result = mysqli_data_insert($tbL63,$pst_field,$pst_query);

		if(isset($result) && $result == 2) {

			#update sales for open-counter

			if($counter_sesid > 0) {
				
				if((isset($pay_media) && $pay_media > 0) && (isset($amount) && $amount > 0 && $transaction_type == 'Credit')) {
					
					$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$pay_media,"ispast"=>0);
					$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

					$new_collection = $sales_counter_data[0] + $amount;
					$cash2refund = 0;
					
					if($cash2refund > 0) { $sales_counter_sql = array("collection"=>$new_collection,"refunds"=>$cash2refund); }
					else { $sales_counter_sql = array("collection"=>$new_collection); }

					mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
					
				} elseif((isset($pay_media) && $pay_media == 1) && (isset($amount) && $amount > 0 && $transaction_type == 'Debit')) {

					$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$pay_media,"ispast"=>0);
					$sales_counter_data = mysqli_data_fetch($tbL25,'refunds',$sales_counter_query,'noarray');

					$new_refunds = $sales_counter_data[0] + $amount;

					$sales_counter_sql = array("refunds"=>$new_refunds);
					mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
				}
			}

			#update credit balance if transaction-type is posted as credit
			
			$pst_query = array("id"=>$corporateid);
			$pst_field = array("creditlimit"=>$new_credit_balance);
			mysqli_data_update($tbL58,$pst_field,$pst_query);
			

			//create a log file
			$message = "Recently updated corporate ".strtolower($transaction_type)." payment of ".$amount.": ".$detail;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="light-red-font ft-sml-size">Transaction submitted successfully</span>';
			$post_result .= '</div>';
		}
	}

	#-reverse payment-----------------------------------------------------------------------------------------------

	if((isset($_GET['reversepy']) && $_GET['reversepy'] == 'yes') && (isset($_GET['py']) && !empty($_GET['py'])) && (isset($_GET['rrs']) && !empty($_GET['rrs'])) ) {

		$wgt_reverseDesc = $_GET['rrs'];
		$wgt_reverseid = $_GET['py'];
		$sql_uquery = array("id"=>$wgt_reverseid);
		$sql_udata = array("isreversed"=>1,"deletedata"=>1);
		$isdata = mysqli_data_update($tbL63,$sql_udata,$sql_uquery);

		if(isset($isdata) && $isdata == 2) {

			$wgt_biller = idget_data($tbL63,$wgt_reverseid,'cspgid');
			$wgt_amount = idget_data($tbL63,$wgt_reverseid,'amount');
			$wgt_paymode = idget_data($tbL63,$wgt_reverseid,'paymode');
			$wgt_trnumber = idget_data($tbL63,$wgt_reverseid,'transaction_number');
			$wgt_userid = idget_data($tbL63,$wgt_reverseid,'userid');
			$wgt_counter = idget_data($tbL63,$wgt_reverseid,'counter_used');
			$wgt_shiftid = idget_data($tbL63,$wgt_reverseid,'shiftid');

			$get_cspg_name = idget_data($tbL58,$wgt_biller,'name');
			$get_user = idget_data($tbL7,$wgt_userid,'staffname');
		
			#update user counter

			if(!empty($counter_sesid) && $counter_sesid > 0) {
				
				$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$wgt_paymode,"ispast"=>0);
				$sales_counter_data = mysqli_data_fetch($tbL25,'refunds',$sales_counter_query,'noarray');
				$new_refunds = $sales_counter_data[0] + $wgt_amount;

				$sales_counter_sql = array("refunds"=>$new_refunds);		
				$isdata = mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
			}

			#-------------------------------------------------------------------------------------------------------------

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Payment was reversed successfully";
			
			$islogfile = 1;
			$logfile_msg = "Corporate guest ({$get_cspg_name}) payment with transaction number ({$wgt_trnumber}) was reversed by this user";
			
			$isguestAct = 1;
			$pst_booking_number = $wgt_trnumber;
			$remark_tag = "reverse"; $app_tag = "Payment"; $session_tag = "Corporate Account";
			$guestAct_msg = "Corporate guest ({$get_cspg_name}) payment of sum of {$wgt_amount} with transaction number ({$wgt_trnumber}) was reversed. Reason for reverse is stated as follow: {$wgt_reverseDesc} - {$get_user}";

			unset($_GET['py']);
			unset($_GET['rrs']);

		} else {

			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Reverse request could not be completed. Please try again";
		}
	}

	#--------------------------------------------------------------------------------------------------------------

	echo $post_result;

?>


<form action="" method="post" autocomplete="off" onsubmit="">
	<div class="xform fx-position-rel">
		<div class="top-pull-5 right-pull-7 bottom-pull-5 left-pull-7">
			<span class="pads7"><input type="text" name="for-cspg" id="for-cspg" placeholder="Type to search corporate.." value="<?php echo $corporate_name; ?>" class="nopads no-back-black" oninput="filtersearch(this.id)" onfocus="filtersearch(this.id)"></span>
			<span id="list-cspg" class="noshow"><select name="cspg" id="cspg" class="nopads no-back-black" onchange="filterselect(this.id); cspg_acct(this.value)" required="required"><option value="<?php echo $corporate_id; ?>" selected="selected" class="white-theme"><?php echo $corporate_name; ?></option><?php echo $cspg; ?></select></span>
		</div>
	</div>

	<div id="acct-box" class="box-border-thick motion sml-rounded-button top-push-20 white-theme obj-light-shadow noshow">
		<div class="box-border-thick-bottom pads20">
			<h3 class="large nobold">+ Account Details</h3><br>
			<ul class="nolist">
				<li class="ln-display-box float-left top-pull-7 right-pull-50"><h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo number_format($credit_limit,2); ?></h3><h4 class="large nobold">Credit Limit</h4></li>
				<li class="ln-display-box float-left top-pull-7 right-pull-50"><h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo number_format($notify_limit,2); ?></h3><h4 class="large nobold">Notify Limit</h4></li>
				<li class="ln-display-box float-left top-pull-7 right-pull-50"><h3 class="xlarge nobold default-text-font-bold nomargin <?php if($credit_balance > 0): ?>forest-green-font<?php elseif($credit_balance == 0): ?>black-font<?php elseif($credit_balance < 0): ?>light-red-font<?php endif; ?>"><?php echo number_format($credit_balance,2); ?></h3><h4 class="large nobold">Credit Bal.</h4></li>
				<li class="ln-display-box float-left top-pull-7"><h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo $retainership; ?></h3><h4 class="large nobold">Retainership</h4></li>
				<li class="ln-display-box float-right grey-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button"><h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo number_format($discount,1); ?>%</h3><h4 class="large nobold light-red-font">Fixed Global Discount</h4></li>
				<li class="block-element new-line-space"></li>
			</ul>
		</div>
		<div class="pads20">
			<span class="float-right"><input type="submit" name="transactionbutton" id="transactionbutton" value="Submit Entry" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button"></span>
			<h3 class="large nobold">+ Account Entry</h3><br>
			<ul class="nolist">
				<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Transaction Type</h4>
					<select name="transactiontype" id="transactiontype" class="no-back-black" required>
						<option value="" selected>Choose?</option>
						<option value="Credit">Credit</option>
						<option value="Debit">Debit</option>
					</select>
				</li>
				<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Transaction Date</h4>
					<input type="date" name="transactiondate" id="transactiondate" value="<?php echo $server_get_date; ?>" class="no-back-black">
				</li>
				<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Transaction Status</h4>
					<select name="transactionstatus" id="transactionstatus" class="no-back-black" required>
						<option value="Completed">Completed</option>
						<option value="Pending">Pending</option>
					</select>
				</li>
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Amount</h4>
					<input type="text" name="wgtamount" id="wgtamount" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount')" class="no-back-black default-text-font-bold">
					<input type="hidden" name="amount" id="amount" required>
				</li>
				<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Payment Mode</h4>
					<select name="paymode" id="paymode" class="no-back-black" required>
						<option value="" selected>Choose?</option>
						<?php echo $payment_mode; ?>
					</select>
				</li>
				<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Cheque No.</h4>
					<input type="text" name="chequenumber" id="chequenumber" placeholder="if applicable?" class="no-back-black">
				</li>
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="grey-theme pads20 sml-rounded-button">
					<textarea name="remark" id="remark" placeholder="Write remark here if applicable?" class="nopads no-back-black notextborder" required></textarea>
				</li>
			</ul>
		</div>
	</div>
</form>

<?php
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$tbl = $tbL63;
	
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#pagination buttons
	$paginate = class_data_pagenation(25,0,$totalcount);

	$curpage = isset($_GET['pg']) ? $_GET['pg'] : 0;
	$pgstart = isset($_GET['start']) ? $_GET['start'] : 0;
	$pglimit = isset($_GET['limit']) ? $_GET['limit'] : 25;
	
	$startnumbr = $pgstart;

?>
<div class="white-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 x-scroll <?php echo $display; ?>">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left cs-width-150 right-pull-30">
			<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
		</span>
		<span class="ln-display-box float-left cs-width-180 cs-height-35 box-border-thick sml-rounded-button top-pull-7 left-pull-10 right-pull-10 noscroll">
			<?php if(isset($paginate) && !empty($paginate)) { echo $paginate; } else { ?><select class="nopads no-back-black"></select><?php } ?>
		</span>
		<span class="ln-display-box float-left cs-width-200 top-pull-7 left-pull-30">
			[<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&type=credit" class="royal-blue-font">Credit</a>] &nbsp; [<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&type=debit" class="royal-blue-font">Debit</a>] &nbsp; [<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&type=all" class="royal-blue-font">All</a>]
		</span>
		<span class="ln-display-box float-right top-pull-5">
			<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold<?php echo $acctbutton; ?>" onclick="jsForm()">Account +</a> <a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a> <a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state rounded-button ft-xsml-size default-text-font-bold left-push-10" onclick="window.print()">Print</a>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>

<div class="pads10" align="left">
	<div class="x-scroll">
		<div id="section-to-print" class="cs-width-2000">

			<?php

				if(!empty($corporate_name)) {
					?>
						<h3 class="xlarge nobold default-text-font-bold"><?php echo $corporate_name; ?></h3><br>
					<?php
				}
				
				$queryset = $queryset." LIMIT {$pgstart},{$pglimit}";

				$value_compare = array("biller"=>"funding");

				$keys = array(
					"transaction_number"=>"(fx)tr. number",
					"transaction_type"=>"(pr)tr. type",
					"paymode"=>"payment type",
					"cheque_number"=>"cheque no.",
					"amount"=>"(nf)amount (&#8358;)",
					"detail"=>"(nl)description",
					"transaction_date"=>"(df)tr. date",
					"credit_balance"=>"(nf)balance (&#8358;)",
					"status"=>"status",
					"datelogged"=>"(df)date modified"
				);

				if($corporate_id == 0) {
					$keys = array("cspgid"=>"(nl)corporate") + $keys;
				}

				$format = array(
					"grid"
				);

				$datarow = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				echo $datarow;
			?>
		</div>
	</div>
</div>

<?php
	$booking_prefix = idget_data($tbL76,1,'prefixtext');
?>

<script>

	const bookingPrf = "<?php echo $booking_prefix; ?>";

	function cspg_acct(id) {
		var account_uri;
		sessionStorage.setItem('sku',id);

		if(sessionStorage.getItem('cspga') !== null && sessionStorage.getItem('cspga') != 'undefined') {
			account_uri = sessionStorage.getItem('cspga');
		} else {
			sessionStorage.setItem('cspga',window.location.href);
			account_uri = window.location.href;
		}

		setTimeout(() => { window.location.href = account_uri+'&cspga='+id; },500);
	}

	function jsForm() {
		chgclass('acct-box','box-border-thick motion sml-rounded-button top-push-20 white-theme obj-light-shadow');
		parent.document.getElementById('workspace').scrollTop = 0;
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		if(key.indexOf('CPY') == -1) {
			if(key.indexOf(bookingPrf) > -1) {
				crframe(key,uId,'reservations');
				setTimeout(() => {
					popmodalframe('frontdesk','paymentandinvoice',key,0,1000,2500);
				},5000);
			} else {
				popmodalframe('pos','pos_post_bill_review',key,0,1000,2500);
			}
		}
	}

	function jsxPrint(key) {
		var dataid = key.lang;
		popmodalframe('frontdesk','corpaymentreceipt',dataid,0,800,1000);
	}

	function reversePy(key) {
		var dataid = key.lang;
		var sayprompt = prompt('Enter reason for reverse?','');
		if(sayprompt) { window.location.href = window.location.href+'&reversepy=yes&py='+dataid+'&rrs='+sayprompt; }
	}


	const new_url = "<?php echo $new_url; ?>";
	if(new_url == 200) { sessionStorage.removeItem('weburi'); }

</script>