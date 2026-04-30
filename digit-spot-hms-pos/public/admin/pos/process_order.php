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

	if(isset($_POST['submitbutton']))
	{
	
		createDatabasetable($var_tbl_94); //create a table for this post
		createDatabasetable($var_tbl_95); //create a table for this post
		createDatabasetable($var_tbl_315); //create a table for this post

		$cashier = $_POST['cashier'];
		
		$itemid = $_POST['itemid']; $price = $_POST['price']; $qty = $_POST['qty'];
		$subtotal = $_POST['sub-total']; $bill_amount = $_POST['grand-total']; $item_discount = $_POST['discount'];
		$remarks = $_POST['remarks']; $tax_amount = 0; $discount_amount = $_POST['discount-amount'];
		//$tax_amount = $_POST['consumption-tax'];

		if(isset($_POST['pending-orders']) && !empty($_POST['pending-orders'])) {
			
			$order_number = escape_data($_POST['pending-orders']);
			$update_invoice_key = array("order_number"=>$order_number);
			$invoice_now = "sub_total,tax_amount,bill_amount,waiter,foodtype,billtype,tableid,cover,roomid,iscomplimentary,customerid";
			$get_exist_invoice = mysqli_data_fetch($tbL100,$invoice_now,$update_invoice_key,'noarray');
			
			$waiter =  $get_exist_invoice[3];
			$foodtype = $get_exist_invoice[4];
			$billtype = $get_exist_invoice[5];
			$table = $get_exist_invoice[6];
			$cover = $get_exist_invoice[7];
			$roomid = $get_exist_invoice[8];
			$iscomplimentary = $get_exist_invoice[9];
			$customer = $get_exist_invoice[10];

			$add_subtotal = $subtotal + $get_exist_invoice[0];
			$add_tax_amount = $tax_amount + $get_exist_invoice[1];
			$add_bill_amount = $bill_amount + $get_exist_invoice[2];

			$invoice_datasets = array("sub_total"=>$add_subtotal,"tax_amount"=>$add_tax_amount,"bill_amount"=>$add_bill_amount);
			$is_invoice = mysqli_data_update($tbL100,$invoice_datasets,$update_invoice_key);

		} else {

			$is_invoice = 0;

			$waiter = $_POST['waiters'];
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
					if($wgt_booking_type == 'corporate') { $billto = idget_fdata($tbL130,'booking_number',$customer_data[1],'bill_to'); } else { $billto = 0; if($wgt_booking_type == 'complimentary') { $iscomplimentary = idget_fdata($tbL130,'booking_number',$customer_data[1],'bill_to'); } else { $iscomplimentary = 0; } }
					$booking_number = $customer_data[1];
					$customer_src = $customer_data[0];
					$roomid = $_POST['billacct'];
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

			createDatabasetable($var_tbl_97); //create a table for this post

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
			
			
			$table = $_POST['tabletype'];
			$cover = $_POST['cover'];

			if(isset($_POST['tobill']) && !empty($_POST['tobill'])) {
				
				$isExist = true;
				
				$order_number = $_POST['tobill'];

				$invoice_number = idget_fdata($tbL100,'order_number',$order_number,'invoice_number');
				$get_bill_amount = idget_fdata($tbL100,'order_number',$order_number,'bill_amount');
				$get_subtotal = idget_fdata($tbL100,'order_number',$order_number,'sub_total');
				$get_discount = idget_fdata($tbL100,'order_number',$order_number,'discount_amount');
				
				$new_subtotal = $subtotal + $get_subtotal;
				$new_discount = $discount_amount + $get_discount;
				$new_bill_amount = $bill_amount + $get_bill_amount;

				$tax_amount = ($gh_get_vat / 100) * $new_bill_amount;
				$service_charge_amount = ($gh_get_service_charge / 100) * $new_bill_amount;
				$consumption_amount = ($gh_get_consumption_tax / 100) * $new_bill_amount;

				//create invoice information
				$invoice_number_key = array("order_number"=>$order_number);
				$invoice_datasets = array("discount_amount"=>$new_discount,"sub_total"=>$new_subtotal,"tax_amount"=>$tax_amount,"consumption_amount"=>$consumption_amount,"service_charge_amount"=>$service_charge_amount,"bill_amount"=>$new_bill_amount); $is_invoice = mysqli_data_update($tbL100,$invoice_datasets,$invoice_number_key);

			} else {
			
				$isExist = false;

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
			

				$tax_amount = ($gh_get_vat / 100) * $bill_amount;
				$service_charge_amount = ($gh_get_service_charge / 100) * $bill_amount;
				$consumption_amount = ($gh_get_consumption_tax / 100) * $bill_amount;

				//create invoice information
				$invoice_number_key = array("invoice_number"=>$invoice_number);
				$invoice_datasets = array("order_number"=>$order_number,"invoice_number"=>$invoice_number,"userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"posid"=>$cur_pos_store_id,"booking_number"=>$booking_number,"customerid"=>$customer,"discount_amount"=>$discount_amount,"sub_total"=>$subtotal,"tax_amount"=>$tax_amount,"consumption_amount"=>$consumption_amount,"service_charge_amount"=>$service_charge_amount,"bill_amount"=>$bill_amount,"foodtype"=>$foodtype,"billtype"=>$billtype,"biller"=>$billto,"tableid"=>$table,"cover"=>$cover,"detail"=>$remarks,"cashier"=>$cashier,"waiter"=>$waiter,"roomid"=>$roomid,"iscomplimentary"=>$iscomplimentary,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				$is_invoice = mysqli_data_insert($tbL100,$invoice_datasets,'');
			}
		}
		

		//create order information
		$order_datasets = "";

		$log_order = 0; $category = ""; $subcategory = ""; $main_category = ""; $costprice = ""; $discountAmt = 0;

		for($i=0; $i < count($itemid); $i++) {
			
			$category = idget_data($tbL16,$itemid[$i],'categoryid');
			$subcategory = idget_data($tbL16,$itemid[$i],'subcategoryid');
			$costprice = idget_data($tbL16,$itemid[$i],'cost');
			
			if($costprice == null || empty($costprice)) { $costprice = 0; }
			else { $costprice = $costprice; }

			if($item_discount[$i] >= 1) { $discountAmt = $item_discount[$i]; }
			else { $discountAmt = 0; }
			
			$main_category = idget_data($tbL15,$category,'program_id');
			$tr_amount = $price[$i] * $qty[$i];
			
			$order_datasets = array("main_category"=>$main_category,"sales_category"=>$category,"sales_subcategory"=>$subcategory,"order_number"=>$order_number,"userid"=>$userSignedIn,"counter_used"=>$ths_mycounter,"posid"=>$cur_pos_store_id,"booking_number"=>$booking_number,"customerid"=>$customer,"itemid"=>$itemid[$i],"qty"=>$qty[$i],"price"=>$price[$i],"cost"=>$costprice,"amount"=>$tr_amount,"discount"=>$discountAmt,"foodtype"=>$foodtype,"billtype"=>$billtype,"biller"=>$billto,"tableid"=>$table,"cover"=>$cover,"cashier"=>$cashier,"waiter"=>$waiter,"roomid"=>$roomid,"iscomplimentary"=>$iscomplimentary,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);

			$is_order = mysqli_data_insert($tbL99,$order_datasets,'');

			if(isset($is_order) && $is_order == 2) {
				
				$tr_amount = 0;
				
				$for_item_stockout = idget_data($tbL16,$itemid[$i],'stockout');
				$for_item_stockbal = idget_data($tbL16,$itemid[$i],'balance');
				$for_item_storagetype = idget_data($tbL16,$itemid[$i],'storagetype');
				
				if($for_item_storagetype == 'consumable' && $for_item_stockbal > 0) {
					$new_stockout = $for_item_stockout + $qty[$i];
					$new_stock_balance = $for_item_stockbal - $qty[$i];
					$pst_field = array("stockout"=>$new_stockout,"balance"=>$new_stock_balance);
					$pst_query = array("id"=>$itemid[$i]);
					mysqli_data_update($tbL16,$pst_field,$pst_query);

					$new_stockout=""; $new_stock_balance="";
				}

				$for_item_stockout = 0; $for_item_stockbal = 0; $for_item_storagetype="";

				$log_order += 1;
			}
		}


		if((isset($is_invoice) && $is_invoice == 2) && (isset($log_order) && $log_order >= 1)) {

			//create a log file
			$message = "Recently create pos order with order number: ".$order_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');

			?>
				<script>
					window.location.href = "preview_pos_order.php?new_order=<?php echo $order_number; ?>";
				</script>
			<?php
		}

	}

?>