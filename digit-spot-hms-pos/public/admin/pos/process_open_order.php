<?php
include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/common_data_vars.php";
include "../../../includes/pos_common_data.php";


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

		<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr top-push-50 top-pull-50" align="center">
			<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick">
				<h4 id="pos-header-notification" class="large red-font"></h4>
				<small id="pos-message-notification" class="block-element top-push-10"></small>
			</div>
		</div>

	<?php

	$smdl = "pos";
	$cur_pos_store_id = $_SESSION['postoreid'];

	//start pos order process

	if(isset($_POST['submitbutton']) && (is_numeric($_POST['grandtotal']) && $_POST['grandtotal'] > 0) && (is_numeric($_POST['actualgrandtotal']) && $_POST['actualgrandtotal'] > 0)) {
	
		createDatabasetable($var_tbl_94); //create a table for this post
		createDatabasetable($var_tbl_95); //create a table for this post
		createDatabasetable($var_tbl_315); //create a table for this post

		$cashier = 0;
		
		$item = $_POST['item']; $category = $_POST['category']; $price = $_POST['rate']; $qty = $_POST['qty'];
		$amount = $_POST['amount']; $subtotal = $_POST['actualgrandtotal']; $bill_amount = $_POST['grandtotal'];
		$remarks = "For open sales";

		$tax_names = $_POST['taxnames'];
		$tax_value = $_POST['taxes'];

		$revoketax1 = $_POST['revoketax1'];
		$revoketax2 = $_POST['revoketax2'];
		$revoketax3 = $_POST['revoketax3'];

		$forvatCol = "tax_amount"; $forvatVal = 0;
		$forconsumptionCol = "consumption_amount"; $forconsumptionVal = 0;
		$forserviceChargeCol = "service_charge_amount"; $forserviceChargeVal = 0;

		for($t=0; $t < count($tax_names); $t++) {
			
			if($tax_names[$t] == 'Vat' || $tax_names[$t] == 'VAT' || $tax_names[$t] == 'Value Added Tax') {
				$forvatCol = "tax_amount"; $forvatVal = $tax_value[$t];
			}

			if($tax_names[$t] == 'Consumption' || $tax_names[$t] == 'Consumption Tax') {
				$forconsumptionCol = "consumption_amount"; $forconsumptionVal = $tax_value[$t];
			}

			if($tax_names[$t] == 'Service' || $tax_names[$t] == 'Service Charge') {
				$forserviceChargeCol = "service_charge_amount"; $forserviceChargeVal = $tax_value[$t];
			}
		}

		$actual_bill_ammount = $bill_amount;

		if($forvatVal == 0 && (!isset($revoketax1) || empty($revoketax1))) {
			$tax_amount = ($gh_get_vat / 100) * $actual_bill_ammount;
			$forvatVal = $tax_amount;
			$forvatCol = "tax_amount";
			$subtotal = $subtotal - $tax_amount;
		}

		if($forconsumptionVal == 0 && (!isset($revoketax3) || empty($revoketax3))) {
			$consumption_amount = ($gh_get_consumption_tax / 100) * $actual_bill_ammount;
			$forconsumptionVal = $consumption_amount;
			$forconsumptionCol = "consumption_amount";
			$subtotal = $subtotal - $consumption_amount;
		}

		if($forserviceChargeVal == 0 && (!isset($revoketax2) || empty($revoketax2))) {
			$service_charge_amount = ($gh_get_service_charge / 100) * $actual_bill_ammount;
			$forserviceChargeVal = $service_charge_amount;
			$forserviceChargeCol = "service_charge_amount";
			$subtotal = $subtotal - $service_charge_amount;
		}



		##end

		$is_invoice = 0;

		$waiter = 0; //$_POST['waiters'];
		$foodtype = $_POST['foodtype'];
		$billtype = $_POST['billtype'];

		if((isset($_POST['billacct']) && !empty($_POST['billacct'])) && ($_POST['billacct'] == 'Instant Payment')) {
			$billsrc = $billtype;
			$billto = 0;
			$booking_number = 0;
			$customer_src = 0;
			$roomid = 0;
			$iscomplimentary = 0;
		} else {
			if(isset($billtype) && $billtype == 2) {
				$cs_selection_key = array("roomid"=>$_POST['billacct'],"status"=>"CheckedIn","deletedata"=>0);
				$customer_data = mysqli_data_fetch($tbL127,'customerid,booking_number',$cs_selection_key,'noarray');
				$wgt_booking_type = idget_fdata($tbL130,'booking_number',$customer_data[1],'booking_type');
				$billsrc = $billtype;
				if($wgt_booking_type == 'corporate') { $billto = idget_fdata($tbL130,'booking_number',$customer_data[1],'bill_to'); }
				else { $billto = 0; }
				$booking_number = $customer_data[1];
				$customer_src = $customer_data[0];
				$roomid = $_POST['billacct'];
				$iscomplimentary = 0;
			} else if(isset($billtype) && $billtype == 3) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$booking_number = 0;
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = $_POST['billacct'];
			} else if(isset($billtype) && $billtype == 4) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$booking_number = 0;
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = 0;
			} else if(isset($billtype) && $billtype == 5) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$booking_number = 0;
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = 0;
			}
		}

		//for individual non-account/account guest

		//createDatabasetable($var_tbl_97); //create a table for this post

		$new_guest = escape_data($_POST['guestname']);
		$wgt_guestname = explode(' ',$new_guest);

		if(!isset($customer_src) || $customer_src == 0) {
			$datasets = array("fname"=>ucwords($wgt_guestname[0]),"lname"=>ucwords($wgt_guestname[1]));
			$isdata = mysqli_data_insert($tbL169,$datasets,'');
			if(isset($isdata) && $isdata == 2) { $customer = $mysqli_id; } else { $customer = 0; }
		} else {
			$customer = $customer_src;
		}

		/*if(isset($customer_src) && $customer_src >= 1) {
			$update_query = array("id"=>$customer_src);
			$datasets = array("billtype"=>$billsrc,"billto"=>$billto);
			$isdata = mysqli_data_update($tbL102,$datasets,$update_query);
			
			if(isset($isdata) && $isdata == 2) { $customer = $customer_src; } else { $customer = 0; }

		} else {
			$datasets = array("billtype"=>$billsrc,"billto"=>$billto,"name"=>ucwords($new_guest));
			$isdata = mysqli_data_insert($tbL102,$datasets,'');

			if(isset($isdata) && $isdata == 2) { $customer = $mysqli_id; } else { $customer = 0; }
		}*/
		
		
		$table = 0; //$_POST['tabletype'];
		$cover = $_POST['cover'];

		//get last pos sequence
		$pss_selection_key = array("posid"=>$cur_pos_store_id);
		$get_pos_sequence_data = mysqli_data_fetch($tbL72,'id,prefixtext,startnumber',$pss_selection_key,'noarray');

		$order_number = $get_pos_sequence_data[1].$get_pos_sequence_data[2];
		$new_sequence = $get_pos_sequence_data[2] + 1;
		$sequence_id = $get_pos_sequence_data[0];

		//update pos sequence
		$pss_query = array("id"=>$sequence_id);
		$pss_update_datasets = array("startnumber"=>$new_sequence);
		mysqli_data_update($tbL72,$pss_update_datasets,$pss_query);

		//get pos invoice prefix
		$get_pos_invoice_prefix_data = mysqli_data_fetch($tbL73,'prefixtext','','noarray');
		$invoice_number = $get_pos_invoice_prefix_data[0].$get_pos_sequence_data[2];

		if(isset($roomid) && $roomid >= 1) { $invoice_number = idget_data($tbL102,$customer,'invoice_number'); }
		else { $invoice_number = $invoice_number; }

		//create invoice information
		$invoice_number_key = array("invoice_number"=>$invoice_number);
		$invoice_datasets = array("order_number"=>$order_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"posid"=>$cur_pos_store_id,"booking_number"=>$booking_number,"customerid"=>$customer,"discount_amount"=>0,"sub_total"=>$subtotal,$forvatCol=>$forvatVal,$forconsumptionCol=>$forconsumptionVal,$forserviceChargeCol=>$forserviceChargeVal,"bill_amount"=>$bill_amount,"foodtype"=>$foodtype,"billtype"=>$billtype,"biller"=>$billto,"tableid"=>$table,"cover"=>$cover,"detail"=>$remarks,"cashier"=>$userSignedIn,"waiter"=>$waiter,"roomid"=>$roomid,"iscomplimentary"=>$iscomplimentary,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
		$is_invoice = mysqli_data_insert($tbL100,$invoice_datasets,'');
		
		

		//create order information
		$order_datasets = "";


		$log_order = 0; $total_cover = 0; $main_category = "";

		for($i=0; $i < count($item); $i++) {
			
			//$constrain = array("item"=>$item[$i]);
			$constrain = "";
			$product_arr = array("storageid"=>0,"storagetype"=>"opensales","postoreid"=>$cur_pos_store_id,"categoryid"=>$category[$i],"subcategoryid"=>0,"itemcode"=>0,"item"=>$item[$i],"stockin"=>0,"uom"=>0,"price"=>0,"balance"=>0,"isfeature"=>"No","isstaff"=>"No");
			$is_item = mysqli_data_insert($tbL16,$product_arr,$constrain);
			
			if(!empty($mysqli_id) && $mysqli_id > 0) { $itemid = $mysqli_id; }
			else { $itemid = idget_fdata($tbL16,'item',$item[$i],'id'); }

			$main_category = idget_data($tbL15,$category[$i],'program_id');

			$order_datasets = array("main_category"=>$main_category,"order_number"=>$order_number,"userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"posid"=>$cur_pos_store_id,"booking_number"=>$booking_number,"customerid"=>$customer,"itemid"=>$itemid,"qty"=>$qty[$i],"price"=>$price[$i],"amount"=>$amount[$i],"foodtype"=>$foodtype,"billtype"=>$billtype,"biller"=>$billto,"tableid"=>$table,"cover"=>$qty[$i],"cashier"=>$userSignedIn,"waiter"=>$waiter,"roomid"=>$roomid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);

			$is_order = mysqli_data_insert($tbL99,$order_datasets,'');

			if(isset($is_order) && $is_order == 2) {
				$log_order += 1;
				$total_cover = $total_cover + $qty[$i];
			}
		}


		if((isset($is_invoice) && $is_invoice == 2) && (isset($log_order) && $log_order >= 1)) {

			$pst_val = array("cover"=>$total_cover);
			$pst_key = array("order_number"=>$order_number);
			mysqli_data_update($tbL100,$pst_val,$pst_key);

			//create a log file
			$message = "Recently created pos open order with order number: ".$order_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');

			?>
				<script>
					window.location.href = "preview_pos_open_order.php?new_order=<?php echo $order_number; ?>";
				</script>
			<?php
		}

	}

?>