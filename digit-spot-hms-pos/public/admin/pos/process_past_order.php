<?php
include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";


	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../../style/custom.css"/>
		<link rel="stylesheet" href="applystyle.css"/>
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

		<script>
			window.addEventListener('focus',function() {

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

	//start pos order process

	if(isset($_POST['submitbutton']))
	{
		$this_selection_shift = $_POST['shift'];
		$order_date = $_POST['orderdate'];
		$cashier = $_POST['cashier'];
		$waiter = $_POST['waiters'];

		$foodtype = $_POST['foodtype'];
		$billtype = $_POST['billtype'];

		if((isset($_POST['billacct']) && !empty($_POST['billacct'])) && ($_POST['billacct'] == 'Instant Payment')) {
			$billsrc = $billtype;
			$billto = 0;
			$customer_src = 0;
			$roomid = 0;
			$iscomplimentary = 0;
		} else {
			if(isset($billtype) && $billtype == 2) {
				$cs_selection_key = array("roomid"=>$_POST['billacct']);
				$customer_data = mysqli_data_fetch($tbL98,'customerid',$cs_selection_key,'noarray');
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$customer_src = $customer_data[0];
				$roomid = $_POST['billacct'];
				$iscomplimentary = 0;
			} else if(isset($billtype) && $billtype == 3) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = $_POST['billacct'];
			} else if(isset($billtype) && $billtype == 4) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = 0;
			} else if(isset($billtype) && $billtype == 5) {
				$billsrc = $billtype;
				$billto = $_POST['billacct'];
				$customer_src = 0;
				$roomid = 0;
				$iscomplimentary = 0;
			}
		}

		//for individual non-account/account guest

		createDatabasetable($var_tbl_97); //create a table for this post

		$new_guest = escape_data($_POST['guestname']);
		if(isset($customer_src) && $customer_src >= 1) {
			$update_query = array("id"=>$customer_src);
			$datasets = array("billtype"=>$billsrc,"billto"=>$billto);
			$isdata = mysqli_data_update($tbL102,$datasets,$update_query);
			
			if(isset($isdata) && $isdata == 2) { $customer = $customer_src; } else { $customer = 0; }
		} else {
			$datasets = array("billtype"=>$billsrc,"billto"=>$billto,"name"=>ucwords($new_guest));
			$isdata = mysqli_data_insert($tbL102,$datasets,'');

			if(isset($isdata) && $isdata == 2) { $customer = $mysqli_id; } else { $customer = 0; }
		}
		
		
		$table = $_POST['tabletype'];
		$cover = $_POST['cover'];


		$itemid = $_POST['itemid']; $price = $_POST['price']; $qty = $_POST['qty'];
		$subtotal = $_POST['sub-total']; $tax_amount = $_POST['consumption-tax']; $bill_amount = $_POST['grand-total'];
		$remarks = $_POST['remarks'];

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



		//create invoice information
		createDatabasetable($var_tbl_95); //create a table for this post
		$invoice_number_key = array("invoice_number"=>$invoice_number);
		$invoice_datasets = array("order_number"=>$order_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"posid"=>$cur_pos_store_id,"customerid"=>$customer,"discount_amount"=>0,"sub_total"=>$subtotal,"tax_amount"=>$tax_amount,"bill_amount"=>$bill_amount,"foodtype"=>$foodtype,"billtype"=>$billtype,"tableid"=>$table,"cover"=>$cover,"detail"=>$remarks,"cashier"=>$cashier,"waiter"=>$waiter,"roomid"=>$roomid,"iscomplimentary"=>$iscomplimentary,"shiftid"=>$this_selection_shift,"datelogged"=>$order_date,"timelogged"=>"00:00:00"); $is_invoice = mysqli_data_insert($tbL100,$invoice_datasets,$invoice_number_key);

		//create order information
		createDatabasetable($var_tbl_94); //create a table for this post
		$order_datasets = "";

		$log_order = 0;

		for($i=0; $i < count($itemid); $i++) {
			$order_datasets = array("order_number"=>$order_number,"userid"=>$userSignedIn,"posid"=>$cur_pos_store_id,"customerid"=>$customer,"itemid"=>$itemid[$i],"qty"=>$qty[$i],"price"=>$price[$i],"foodtype"=>$foodtype,"billtype"=>$billtype,"tableid"=>$table,"cover"=>$cover,"cashier"=>$cashier,"waiter"=>$waiter,"roomid"=>$roomid,"shiftid"=>$this_selection_shift,"datelogged"=>$order_date,"timelogged"=>"00:00:00");
			$is_order = mysqli_data_insert($tbL99,$order_datasets,'');
			if(isset($is_order) && $is_order == 2) { $log_order += 1; }
		}


		if((isset($is_invoice) && $is_invoice == 2) && (isset($log_order) && $log_order >= 1)) {

			//create a log file
			$message = "Recently create pos order with order number: ".$order_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');

			$_SESSION['order_date'] = $order_date;

			?>
				<script>
					window.location.href = "preview_pos_order.php?new_order=<?php echo $order_number; ?>";
				</script>
			<?php
		}

	}

?>