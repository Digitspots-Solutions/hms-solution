<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/hotel_profile.php";
?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script type="text/javascript" src="../../js/all.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	<div id="section-to-print" class="block-element" align="center">
		<div class="cs-width-800">
			<?php
				if(isset($_GET['receipt']) && $_GET['receipt'] >= 1) {
					
					$select_query = array("id"=>escape_data($_GET['receipt']));
					$select_property = "booking_number,invoice_number,receipt_number,userid,customerid,amount,detail,datelogged,timelogged";
					$select_data = mysqli_data_fetch($tbL131,$select_property,$select_query,'noarray');

					$customer_name = idget_data($tbL102,$select_data[4],'name');
					$billto = idget_data($tbL102,$select_data[4],'billto');
					$billtype = idget_data($tbL102,$select_data[4],'billtype');

					#to whom bill goes to
					if(isset($billto) && $billto >= 1) {
						if(isset($billtype) && $billtype == 3) {
							$_bill_on_account = " (Compl. ".idget_data($tbL33,$billto,'name').")";
							$_to_bill = 1;
						} elseif(isset($billtype) && $billtype == 4) {
							$_bill_on_account = " (Corpo/Spl. ".idget_data($tbL58,$billto,'name').")";
							$_to_bill = 1;
						} else {
							$_bill_on_account = "";
							$_to_bill = 0;
						}

					} else {
						$_bill_on_account = "";
						$_to_bill = 0;
					}
					#end

					$cashier = idget_data($tbL7,$select_data[3],'staffname');
					$amount_paid = write_amountF($gh_get_decimal_format,$select_data[5]);

					$logged_date = write_dateF($gh_get_date_format,$select_data[7]);
					$logged_time = write_timeF($gh_get_time_format,$select_data[8]);

					?>
						<img src="<?php echo _FC_LOGO_Mx; ?>">
						<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
						<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
						<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
						<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>
						<div class="block-element top-pull-30">
							<span class="ln-display-box float-left bottom-push-10 nc-width-40 alignlt">
								<small class="block-element bottom-push-5"><b>Receipt Number</b>: <?php echo $select_data[2]; ?></small>
								<small class="block-element bottom-push-5"><b>Booking Number</b>: <?php echo $select_data[0]; ?></small>
								<small class="block-element bottom-push-5"><b>Guest</b>: <?php echo $customer_name; ?> (<?php echo $_bill_on_account; ?>)</small>
							</span>
							<span class="ln-display-box float-right bottom-push-10 nc-width-30 alignrt">
								<small class="block-element bottom-push-5"><b>Cashier</b>: <?php echo $cashier; ?></small>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<h4 class="large">Payment Details</h4>
						<table cellpadding="0" cellspacing="0" class="ft-xxsml-size top-push-5">
							<tr>
								<th width="50px" align="center"></th>
								<th width="500px" align="center">Description</th>
								<th width="200px" align="center">Amount Paid</th>
							</tr>
							<tr>
								<td width="50px" align="center"></td>
								<td width="500px" align="center"><?php echo $select_data[6]; ?></td>
								<td width="200px" align="center">&#8358;<?php echo $amount_paid; ?></td>
							</tr>
						</table>

						<div class="pads20">
							<span class="ln-display-box float-left bottom-push-10 nc-width-40 alignlt">
								<small class="block-element bottom-push-5"><b>Cashier Signature</b></small>
								<small class="block-element bottom-push-5"><em><?php echo $logged_date.' '.$logged_time; ?></em></small>
								
							</span>
							<span class="ln-display-box float-right bottom-push-10 nc-width-30 alignrt">
								<small class="block-element bottom-push-5"><b>Guest Signature</b></small>
								<small class="block-element bottom-push-5"><em><?php echo $logged_date.' '.$logged_time; ?></em></small>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
					<?php
				}
			?>
		</div>
	</div>
	<p class="top-pull-20 alignrt">
		<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button anchor" onclick="window.print()"> 
	</p>
</div>


<script>
	
	window.addEventListener('load',function() {
		window.print();
	},false);

</script>