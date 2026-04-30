<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_;  include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../module_operation_privilege.php";



$smdl = "pos";
$cur_pos_store_id = $_SESSION['postoreid'];

?>

<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>

<div class="pads20">

	<?php

		if(isset($_POST['paybutton'])) {

			$order_number = escape_data($_POST['ordernumber']);
			$new_receipt_id = escape_data($_POST['orderid']);
			$receipt_number = $receipt_prefix.$new_receipt;

			$bill_amount = escape_data($_POST['amount-billed']);
			$pay_detail = escape_data($_POST['detail']);
			$pay_media = escape_data($_POST['payment-mode']);
			$pay_cheque = "N/A";

			$oss_query = array("order_number"=>$order_number,"status"=>"Pending","payment"=>"Pending");
			$oss_dataset = array("receipt_number"=>$receipt_number,"status"=>"Completed","payment"=>"Paid","ispaid"=>1,"media"=>$pay_media,"cheque_number"=>$pay_cheque,"paydetail"=>$pay_detail,"paydate"=>$server_get_date,"paytime"=>$server_get_time,"first_amount"=>$bill_amount,"second_amount"=>0);
			mysqli_data_update($tbL100,$oss_dataset,$oss_query);

			$oss_query = array("order_number"=>$order_number,"status"=>"Pending");
			$oss_dataset = array("status"=>"Completed");
			mysqli_data_update($tbL99,$oss_dataset,$oss_query);

			//create a log file
			$message = "Recently accept payment for pos order with receipt number: ".$receipt_number;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>"Point of Sale","message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');

			//add for journal if exist
			$query_jr = array("pychannel"=>$pay_media,"deletedata"=>0);
			$isJournal = mysqli_data_fetch('coa_setup_tbl','id',$query_jr,'noarray');

			if(is_array($isJournal) && count($isJournal) > 0 && !empty($isJournal[0])) {
				
				$mmonth = date('F',strtotime($server_get_date));
				$yyear = date('Y',strtotime($server_get_date));

				$insert_constrain = "";
				$insert_dataproperty = array("coa_id"=>$isJournal[0],"amount"=>$bill_amount,"entry_type"=>"Credit","detail"=>$pay_detail,"mmonth"=>$mmonth,"yyear"=>$yyear,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

				mysqli_data_insert('coa_entry_tbl',$insert_dataproperty,$insert_constrain);
			}

			?>
				<div class="grey-theme pads20 mini-rounded-button bottom-push-20">
					<h1 class="large ft-tahoma alignct nomargin">&#128712; &nbsp; Payment submission received</h1>
				</div>
			<?php
		}

	?>

	<div class="white-theme box-border-thick mini-rounded-button pads20 obj-light-shadow">
		<h3 class="large ft-tahoma"><b class="nobold right-push-10">+</b> Make Payment</h3>

		<p>&nbsp;</p>

		<p class="ft-tahoma ft-xsnl-size vertical-align-15 red-font alignct">Please ensure you have received payment into your bank account before completing the payment form</p>

		<p>&nbsp;</p>
						
		<?php

			$list_payment_modes = select_dt_fetch('iscounter','Yes',$tbL24,'id','name');
			
			if(isset($_GET['orderno']) && !empty($_GET['orderno'])) {

				$new_order = escape_data($_GET['orderno']);

				$invoice_data = "bill_amount,tableid,id";
				$order_key = array("order_number"=>$new_order,"status"=>"Pending","payment"=>"Pending");
				$get_invoice_data = mysqli_data_fetch($tbL100,$invoice_data,$order_key,'noarray');

				?>
					<h4 class="large nobold ft-tahoma">FOR ORDER NO</h4>
					<h3 class="large ft-tahoma">&#128279 <?php echo $new_order; ?></h3>

					<p>&nbsp;</p>

					<form action="" method="post" autocomplete="off">
						<input type="hidden" name="ordernumber" value="<?php echo $new_order; ?>">
						<input type="hidden" name="orderid" value="<?php echo $get_invoice_data[2]; ?>">
						<span class="block-element obj-dark-shadow bottom-push-20">
							<select name="payment-mode" id="payment-mode" required>
								<option value="" selected="selected">Payment Mode</option>
								<?php echo $list_payment_modes; ?>
							</select>
						</span>
						<span class="block-element bottom-push-20">
							<input type="text" name="amount" value="<?php echo '&#8358;'.number_format($get_invoice_data[0],2); ?>" title="Amount Billed" readonly>
							<input type="hidden" name="amount-billed" id="amount-billed" value="<?php echo $get_invoice_data[0]; ?>">
						</span>
						<span id="pay-box" class="noshow motion">
						</span>
						<span class="block-element bottom-push-30">
							<textarea name="detail" id="detail" placeholder="Description (if any?)"></textarea>
						</span>

						<p class="top-pull-30 alignct">
							<input type="submit" name="paybutton" id="paybutton" value="Pay Now" class="nc-width-90 submit top-pull-20 bottom-pull-20 blue-white-state rounded-button">
						</p>
					</form>
				<?php
			}

		?>

	</div>
</div>