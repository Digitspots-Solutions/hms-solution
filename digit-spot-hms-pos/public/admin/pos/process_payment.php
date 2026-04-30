<?php
include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../../../includes/common_data_vars.php";


	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../../style/custom.css"/>
		<link rel="stylesheet" href="../admin/applystyle.css"/>
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

		<script>
			window.addEventListener('focus',function() {

				parent.document.getElementById('workspace').scrollTop = 0;
				writeObjheader('pos-header-notification','Processing Request');
				writeObjheader('pos-message-notification','Please wait while system complete request..');
				objDisplay('notifybox');
				autohidePopupBox('notifybox',90000);

			},false);
		</script>

		<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr" align="center">
			<div class="cs-width-400 white-theme pads20 top-push-50 sml-rounded-button alignlt box-border-thick">
				<h4 id="pos-header-notification" class="large red-font"></h4>
				<small id="pos-message-notification" class="block-element top-push-10"></small>
			</div>
		</div>

	<?php

	$smdl = "pos";
	$cur_pos_store_id = $_SESSION['postoreid'];
	$posname = idget_data($tbL14,$cur_pos_store_id,'posname'); //get the name of the current pos in use

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	#start pos order process
	createDatabasetable($var_tbl_96); //create a table for this post

	if(isset($_SESSION['order_date']) && !empty($_SESSION['order_date'])) {
		$server_get_date = $_SESSION['order_date'];
		$server_get_time = "00:00:00";
	} else {
		$server_get_date = $server_get_date;
		$server_get_time = $server_get_time;
	}

	if(isset($_POST['paybutton']))
	{
		$order_number = escape_data($_POST['order_number']);
		
		$oss_selection_key = array("order_number"=>$order_number,"status"=>"Pending");
		$oss_datasets = array("status"=>"Completed");
		mysqli_data_update($tbL100,$oss_datasets,$oss_selection_key);
		mysqli_data_update($tbL99,$oss_datasets,$oss_selection_key);

		//get new order details
		$order_selection_key = array("order_number"=>$order_number,"status"=>"Completed","payment"=>"Pending");
		$get_invoice_data = mysqli_data_fetch($tbL100,'id,posid,bill_amount,cashier',$order_selection_key,'noarray');

		$pay_invoiceid = $get_invoice_data[0];
		$pay_posid = $get_invoice_data[1];
		$pay_amount = $get_invoice_data[2];
		$pay_cashier = $get_invoice_data[3];
		$pay_media = $_POST['payment-mode'];
		$first_amount = escape_data($_POST['wgtf1']);
		$second_amount = isset($_POST['wgtf2']) ? escape_data($_POST['wgtf2']) : 0;
		$pay_cheque = escape_data($_POST['cheque-number']);
		$pay_detail = escape_data($_POST['detail']);

		$total_paid_amount = $first_amount + $second_amount;

		
		$new_receipt = $pay_invoiceid;
		$receipt_number = $receipt_prefix.$new_receipt;

		$oss_query = array("order_number"=>$order_number,"status"=>"Completed");
		$oss_datasets = array("receipt_number"=>$receipt_number,"payment"=>"Paid","ispaid"=>1,"media"=>$pay_media,"cheque_number"=>$pay_cheque,"paydetail"=>$pay_detail,"paydate"=>$server_get_date,"paytime"=>$server_get_time,"first_amount"=>$first_amount,"second_amount"=>$second_amount);
		mysqli_data_update($tbL100,$oss_datasets,$oss_query);


		#update sales for open-counter
		if((isset($pay_media) && $pay_media > 0) && (isset($total_paid_amount) && $total_paid_amount > 0) && (isset($counter_sesid) && $counter_sesid > 0)) {
			
			$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$pay_media,"ispast"=>0);
			$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

			$new_collection = $sales_counter_data[0] + $total_paid_amount;
			//$cash2refund = $total_paid_amount - $pay_amount;
			
			/*if($cash2refund > 0) { $sales_counter_sql = array("collection"=>$new_collection,"refunds"=>$cash2refund); }
			else { $sales_counter_sql = array("collection"=>$new_collection); }*/

			$sales_counter_sql = array("collection"=>$new_collection);
			mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);
		}

		/*$receipt_number = $receipt_prefix.$new_receipt;
		$rcp_query = array("id"=>$new_receipt);
		$rcp_datasets = array("receipt_number"=>$receipt_number);
		mysqli_data_update($tbL101,$rcp_datasets,$rcp_query);*/

		//create a log file
		$message = "Recently accept payment for pos order with receipt number: ".$receipt_number;
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL8,$log_datasets,'');

		//add for journal if exist
		$query_jr = array("pychannel"=>$pay_media,"deletedata"=>0);
		$isJournal = mysqli_data_fetch('coa_setup_tbl','id',$query_jr,'noarray');

		if(is_array($isJournal) && count($isJournal) > 0 && !empty($isJournal[0])) {
			
			$mmonth = date('F',strtotime($server_get_date));
			$yyear = date('Y',strtotime($server_get_date));

			$insert_constrain = "";
			$insert_dataproperty = array("coa_id"=>$isJournal[0],"amount"=>$total_paid_amount,"entry_type"=>"Credit","detail"=>$pay_detail,"mmonth"=>$mmonth,"yyear"=>$yyear,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

			mysqli_data_insert('coa_entry_tbl',$insert_dataproperty,$insert_constrain);
		}

		?>
			<script>
				window.location.href = "preview_pos_invoice.php?order=<?php echo $order_number; ?>&receipt=<?php echo $receipt_number; ?>";
			</script>
		<?php
		
	}

	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	
	if(isset($_POST['transferbutton']))
	{
		$order_number = escape_data($_POST['order_number']);
		$pay_detail = escape_data($_POST['detail']);

		$new_receipt = idget_fdata($tbL100,'order_number',$order_number,'id');
		$receipt_number = $receipt_prefix.$new_receipt;

		$oss_selection_key = array("order_number"=>$order_number,"status"=>"Pending");
		$oss_datasets = array("status"=>"Completed");
		$oss_datasets_py = array("receipt_number"=>$receipt_number,"status"=>"Completed","paydetail"=>$pay_detail,"payment"=>"Completed");
		mysqli_data_update($tbL100,$oss_datasets_py,$oss_selection_key);
		mysqli_data_update($tbL99,$oss_datasets,$oss_selection_key);

		//check if bill is attach to corporate
		$billtype = idget_fdata($tbL100,'order_number',$order_number,'billtype');
		$biller = idget_fdata($tbL100,'order_number',$order_number,'biller');

		//check if bill is attach to corporate
		$billtype = idget_fdata($tbL100,'order_number',$order_number,'billtype');
		$biller = idget_fdata($tbL100,'order_number',$order_number,'biller');
		$iscomplimentary = idget_fdata($tbL100,'order_number',$order_number,'iscomplimentary');

		if($billtype == 2 && $iscomplimentary == 0 && $biller >= 1) {

			//get new order details
			$order_selection_key = array("order_number"=>$order_number,"status"=>"Completed","payment"=>"Completed");
			$get_invoice_data = mysqli_data_fetch($tbL100,'id,posid,bill_amount,cashier,customerid,biller,datelogged,roomid',$order_selection_key,'noarray');

			$pay_invoiceid = $get_invoice_data[0];
			$pay_posid = $get_invoice_data[1];
			$pay_amount = $get_invoice_data[2];
			$pay_cashier = $get_invoice_data[3];
			$pay_media = 'Nil';
			$pay_cheque = 'Nil';
			$pay_detail = escape_data($_POST['detail']);

			#compose transaction for group
			$transaction_desc = "POS Outlet (".$posname.") - ".$order_number;

			if(!empty($get_invoice_data[4]) && $get_invoice_data[4] > 0) {
				$get_guest_name = idget_data($tbL102,$get_invoice_data[4],'fname').' ';
				$get_guest_name .= idget_data($tbL102,$get_invoice_data[4],'lname');
				$transaction_desc .= " to ".$get_guest_name;
			}

			if(!empty($get_invoice_data[7]) && $get_invoice_data[7] > 0) {
				$chargeroomid = $get_invoice_data[7];
				$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
				$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
				$transaction_desc .= " @ room ".$r_prefix.$r_number;
			}


			$new_receipt = $pay_invoiceid;
			$receipt_number = $receipt_prefix.$new_receipt;

			$oss_query = array("order_number"=>$order_number,"status"=>"Completed");
			$oss_datasets = array("paydetail"=>$pay_detail,"receipt_number"=>$receipt_number);
			mysqli_data_update($tbL100,$oss_datasets,$oss_query);

			/*$receipt_number = $receipt_prefix.$new_receipt;
			$rcp_query = array("id"=>$new_receipt);
			$rcp_datasets = array("receipt_number"=>$receipt_number);
			mysqli_data_update($tbL101,$rcp_datasets,$rcp_query);*/

			#retrieve group creditlimt
			$credit_limit = idget_data($tbL58,$get_invoice_data[5],'creditlimit');
			$credit_notification_limit = idget_data($tbL58,$get_invoice_data[5],'notifylimit');
			$new_creditlimit = $credit_limit - $pay_amount;


			#update group creditlimt
			$blc_selection_key = array("id"=>$get_invoice_data[5]);
			$crl_datasets = array("creditlimit"=>$new_creditlimit);
			mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

			
			#lodge pos transaction
			$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_invoice_data[5],"posid"=>$pay_posid,"invoiceid"=>$pay_invoiceid,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL62,$ledger_dataproperty,'');

			
			$ledger_dataquery = array("cspgid"=>$get_invoice_data[5],"transaction_number"=>$order_number,"transaction_type"=>"Debit");
			$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_invoice_data[5],"transaction_number"=>$order_number,"transaction_type"=>"Debit","amount"=>$pay_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$get_invoice_data[6],"detail"=>$transaction_desc,"biller"=>"pos","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);
			//--end here


			//create a log file
			$message = "Recently accept payment for pos order with receipt number: ".$receipt_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
		}

		?>
			<script>	
				//window.location.href = '../pos_counter.php?logs=<?php //echo $posname; ?>';
				window.location.href = "preview_pos_invoice.php?order=<?php echo $order_number; ?>&receipt=<?php echo $receipt_number; ?>";
			</script>
		<?php
	}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	
	if(isset($_POST['creditbutton']))
	{
		$order_number = escape_data($_POST['order_number']);

		$oss_selection_key = array("order_number"=>$order_number,"status"=>"Pending");
		$oss_datasets = array("status"=>"Completed");
		mysqli_data_update($tbL100,$oss_datasets,$oss_selection_key);
		mysqli_data_update($tbL99,$oss_datasets,$oss_selection_key);

		//get new order details
		$order_selection_key = array("order_number"=>$order_number,"status"=>"Completed","payment"=>"Pending");
		$get_invoice_data = mysqli_data_fetch($tbL100,'id,posid,bill_amount,cashier,customerid,biller,datelogged,roomid',$order_selection_key,'noarray');

		$pay_invoiceid = $get_invoice_data[0];
		$pay_posid = $get_invoice_data[1];
		$pay_amount = $get_invoice_data[2];
		$pay_cashier = $get_invoice_data[3];
		$pay_media = 'Nil';
		$pay_cheque = 'Nil';
		$pay_detail = escape_data($_POST['detail']);

		#compose transaction for group
		$transaction_desc = "POS Outlet (".$posname.") - ".$order_number;

		if(!empty($get_invoice_data[4]) && $get_invoice_data[4] > 0) {
			$get_guest_name = idget_data($tbL102,$get_invoice_data[4],'fname').' ';
			$get_guest_name .= idget_data($tbL102,$get_invoice_data[4],'lname');
			$transaction_desc .= " to ".$get_guest_name;
		}

		if(!empty($get_invoice_data[7]) && $get_invoice_data[7] > 0) {
			$chargeroomid = $get_invoice_data[7];
			$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
			$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
			$transaction_desc .= " @ room ".$r_prefix.$r_number;
		}


		$new_receipt = $pay_invoiceid;
		$receipt_number = $receipt_prefix.$new_receipt;

		$oss_query = array("order_number"=>$order_number,"status"=>"Completed");
		$oss_datasets = array("paydetail"=>$pay_detail,"receipt_number"=>$receipt_number,"payment"=>"Debit","paydate"=>$server_get_date,"paytime"=>$server_get_time);
		mysqli_data_update($tbL100,$oss_datasets,$oss_query);

		/*$receipt_number = $receipt_prefix.$new_receipt;
		$rcp_query = array("id"=>$new_receipt);
		$rcp_datasets = array("receipt_number"=>$receipt_number);
		mysqli_data_update($tbL101,$rcp_datasets,$rcp_query);*/

		#retrieve group creditlimt
		$credit_limit = idget_data($tbL58,$get_invoice_data[5],'creditlimit');
		$credit_notification_limit = idget_data($tbL58,$get_invoice_data[5],'notifylimit');
		$new_creditlimit = $credit_limit - $pay_amount;


		#update group creditlimt
		$blc_selection_key = array("id"=>$get_invoice_data[5]);
		$crl_datasets = array("creditlimit"=>$new_creditlimit);
		mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);

		
		#lodge pos transaction
		$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_invoice_data[5],"posid"=>$pay_posid,"invoiceid"=>$pay_invoiceid,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL62,$ledger_dataproperty,'');

		
		$ledger_dataquery = array("cspgid"=>$get_invoice_data[5],"transaction_number"=>$order_number,"transaction_type"=>"Debit");
		$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_invoice_data[5],"transaction_number"=>$order_number,"transaction_type"=>"Debit","amount"=>$pay_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$get_invoice_data[6],"detail"=>$transaction_desc,"biller"=>"pos","counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);
		//--end here


		//create a log file
		$message = "Recently accept payment for pos order with receipt number: ".$receipt_number;
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

		?>
			<script>
				window.location.href = "preview_pos_invoice.php?order=<?php echo $order_number; ?>&receipt=<?php echo $receipt_number; ?>";
			</script>
		<?php
		
	}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


	if(isset($_POST['complimentarybutton']))
	{
		$order_number = escape_data($_POST['order_number']);

		$oss_selection_key = array("order_number"=>$order_number,"status"=>"Pending");
		$oss_datasets = array("status"=>"Completed");
		mysqli_data_update($tbL100,$oss_datasets,$oss_selection_key);
		mysqli_data_update($tbL99,$oss_datasets,$oss_selection_key);

		//get new order details
		$order_selection_key = array("order_number"=>$order_number,"status"=>"Completed","payment"=>"Pending");
		$get_invoice_data = mysqli_data_fetch($tbL100,'id,posid,bill_amount,cashier',$order_selection_key,'noarray');

		$pay_invoiceid = $get_invoice_data[0];
		$pay_posid = $get_invoice_data[1];
		$pay_amount = $get_invoice_data[2];
		$pay_cashier = $get_invoice_data[3];
		$pay_media = 'Nil';
		$pay_cheque = 'Nil';
		$pay_detail = escape_data($_POST['detail']);

		//insert payment
		//$pay_dataproperty = array("posid"=>$pay_posid,"invoiceid"=>$pay_invoiceid,"amount"=>$pay_amount,"media"=>$pay_media,"cheque_number"=>$pay_cheque,"detail"=>$pay_detail,"userid"=>$userSignedIn,"cashier"=>$pay_cashier,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		//$isdata = mysqli_data_insert($tbL101,$pay_dataproperty,'');

		//if(isset($isdata) && $isdata == 2) {
			
			$new_receipt = $pay_invoiceid;
			$receipt_number = $receipt_prefix.$new_receipt;

			$oss_query = array("order_number"=>$order_number,"status"=>"Completed");
			$oss_datasets = array("paydetail"=>$pay_detail,"receipt_number"=>$receipt_number,"payment"=>"Complimentary","paydate"=>$server_get_date,"paytime"=>$server_get_time);
			mysqli_data_update($tbL100,$oss_datasets,$oss_query);

			/*$receipt_number = $receipt_prefix.$new_receipt;
			$rcp_query = array("id"=>$new_receipt);
			$rcp_datasets = array("receipt_number"=>$receipt_number);
			mysqli_data_update($tbL101,$rcp_datasets,$rcp_query);*/

			//create a log file
			$message = "Recently accept payment for pos order with receipt number: ".$receipt_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			?>
				<script>
					window.location.href = "preview_pos_invoice.php?order=<?php echo $order_number; ?>&receipt=<?php echo $receipt_number; ?>";
				</script>
			<?php
		//}
	}



?>