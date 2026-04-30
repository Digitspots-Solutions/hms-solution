<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/hotel_profile.php";

define ("_LONG_NAME",$hotel_name);

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script type="text/javascript" src="../../js/all.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30" align="center">
	<?php
		if(isset($_GET['booking']) && isset($_GET['customerid']) && isset($_GET['bal'])) {
			
			$rebate_amount = write_amountF($gh_get_decimal_format,$_GET['bal']);

			?>
				<div class="cs-width-500 white-theme box-border-thick top-push-30 sml-rounded-button pads30 alignct">
					<h4 class="large nobold">REBATE</h4>
					<h1 class="large">&#8358;<?php echo $rebate_amount; ?></h1>
					<small class="block-element top-push-20 bottom-push-20">Do you want to pay out or transfer to coupon?</small>
					<a href="?booking=<?php echo $_GET['booking']; ?>&invoice=<?php echo $_GET['invoice']; ?>&customerid=<?php echo $_GET['customerid']; ?>&amount=<?php echo $_GET['bal']; ?>&coupon=n" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor right-push-10">Pay Out</a>
					<a href="?booking=<?php echo $_GET['booking']; ?>&invoice=<?php echo $_GET['invoice']; ?>&customerid=<?php echo $_GET['customerid']; ?>&amount=<?php echo $_GET['bal']; ?>&coupon=y" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor right-push-10">Route to Coupon</a>
				</div>
			<?php
		}

		if(isset($_GET['coupon']) && isset($_GET['amount']) && isset($_GET['booking'])) {
			
			createDatabasetable($var_tbl_133);

			$booking = escape_data($_GET['booking']); $invoice = escape_data($_GET['invoice']); $amount = escape_data($_GET['amount']);
			$customerid = escape_data($_GET['customerid']);
			
			if($_GET['coupon'] == 'n') { $status_label = "Pay Out"; }
			elseif($_GET['coupon'] == 'y') { $status_label = "Coupon"; }
			
			$chk_rebate = idget_fdata($tbL138,'booking_number',$booking,'id');
			if(isset($chk_rebate[0]) && $chk_rebate[0] >= 1) {
				$rebate_query = array("booking_number"=>$booking);
				$rebate_data = array("balance_amount"=>$amount,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_update($tbL138,$rebate_data,$rebate_query);
			} else {
				$rebate_query = array("booking_number"=>$booking);
				$rebate_data = array("booking_number"=>$booking,"invoice_number"=>$invoice,"balance_amount"=>$amount,"status"=>1,"status_label"=>$status_label,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL138,$rebate_data,$rebate_query);
			}


			if(isset($status_label) && $status_label == "Pay Out") {
				?>
					<h3 class="large alignct">Rebate is now processed and settled</h3>
				<?php
			}

			if(isset($status_label) && $status_label == "Coupon") {
				
				createDatabasetable($var_tbl_124);

				?>
					<div class="cs-width-500 white-theme box-border-thick top-push-30 sml-rounded-button pads30 alignlt">
						<form action="" method="post" autocomplete="off">
							<div class="block-element bottom-push-10">
								<small class="block-element bottom-push-5 left-pull-3">Coupon Expires On</small>
								<input type="date" name="coupon_date" id="coupon_date" value="<?php echo $coupon_expiry_default_date; ?>" required="required" readonly>
							</div>
							<div class="block-element bottom-push-10">
								<small class="block-element bottom-push-3 left-pull-3">Type Coupon Code (if available)</small>
								<small class="block-element bottom-push-5 left-pull-3 red-font ft-xxsml-size">* Note that only unused coupon can be updated</small>
								<input type="text" name="coupon_code" id="coupon_code">
							</div>
							<div class="block-element top-pull-20">
								<input type="hidden" name="bookingnumber" value="<?php echo $booking; ?>">
								<input type="hidden" name="couponamount" value="<?php echo $amount; ?>">
								<input type="hidden" name="customer" value="<?php echo $customerid; ?>">
								<input type="submit" name="couponbutton" value="Apply" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state sml-rounded-button anchor">
							</div>
						</form>
					</div>
				<?php

				if(isset($_POST['couponbutton'])) {
					
					$check_coupon_id = idget_fdata($tbL129,'coupon_code',$_POST['coupon_code'],'id');
					
					if(isset($check_coupon_id) && $check_coupon_id >= 1) {
						$coupon_query = array("id"=>$check_coupon_id);
						$coupon_data = array("coupon_amount"=>$amount,"coupon_status"=>"Unused","datelogged"=>$server_get_date);
						$is_insert_1 = mysqli_data_update($tbL129,$coupon_data,$coupon_query);
					} else {
						$coupon_data = array("booking_number"=>$booking,"coupon_type"=>1,"coupon_amount"=>$amount,"expires_on"=>$_POST['coupon_date'],"customerid"=>$customerid,"userid"=>$userSignedIn,"coupon_status"=>"Unused","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						$is_insert = mysqli_data_insert($tbL129,$coupon_data,'');

						if(isset($is_insert) && $is_insert == 2) {
							$new_coupon_id = $mysqli_id;
							$new_coupon_code = "GCP".$new_coupon_id;

							$coupon_query_2 = array("id"=>$new_coupon_id);
							$coupon_data_2 = array("coupon_code"=>$new_coupon_code);
							$is_insert_2 = mysqli_data_update($tbL129,$coupon_data_2,$coupon_query_2);
						}
					}

					
					if(isset($is_insert_1) && $is_insert_1 == 2) {
						?>
							<h3 class="large alignct">Coupon has been updated successfully</h3>
						<?php
					}

					if(isset($is_insert_2) && $is_insert_2 == 2) {
						
						$print_amount = write_amountF($gh_get_decimal_format,$amount);

						$guest_id = idget_data($tbL129,$new_coupon_id,'customerid');
						if(isset($guest_id) && $guest_id >= 1) {
							$guest_name = idget_data($tbL102,$guest_id,'name');
							$guest_contact = idget_data($tbL102,$guest_id,'mobile');
						} else {
							$guest_name = idget_data($tbL129,$new_coupon_id,'guest_name');
							$guest_contact = idget_data($tbL129,$new_coupon_id,'guest_contact');
						}

						$created_date = idget_data($tbL129,$new_coupon_id,'datelogged');
						$created_time = idget_data($tbL129,$new_coupon_id,'timelogged');
						$expire_date = idget_data($tbL129,$new_coupon_id,'expires_on');
						$userid = idget_data($tbL129,$new_coupon_id,'userid');
						$status = idget_data($tbL129,$new_coupon_id,'status');

						$print_created_date = write_dateF($gh_get_date_format,$created_date);
						$print_created_time = write_timeF($gh_get_time_format,$created_time);
						$print_expire_date = write_dateF($gh_get_date_format,$expire_date);

						$issuer_name = idget_data($tbL7,$userid,'staffname');
						$status_name = arrayget_key($status_tag,$status);
						
						?>
							<div id="section-to-print" class="block-element" align="center">
								<div class="cs-width-500 white-theme box-border-thick top-push-30 sml-rounded-button pads30" align="center">
									<img src="<?php echo _FC_LOGO_Mx; ?>">
									<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
									<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
									<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
									<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>

									<br><h3 class="large">Coupon Details</h3>

									<div class="block-element top-pull-30 alignlt">
										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Coupon Code</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $new_coupon_code; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Guest Name</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $guest_name; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Guest Number</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $guest_contact; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Coupon Amount</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7">&#8358;<?php echo $print_amount; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Created On</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $print_created_date; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Expires On</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $print_expire_date; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Cashier</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $issuer_name; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-20">Status</span>
										<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-20"><?php echo $status_name; ?></span>
										<span class="block-element new-line-space"></span>

										<span class="ln-display-box float-left nc-width-50 ft-xsml-size">
											<small class="block-element bottom-push-3 dark-grey-font">Cashier Signature</small>
											<small class="block-element"><?php echo $print_created_date.' '.$print_created_time; ?></small>
										</span>
										<span class="ln-display-box float-left ft-xsml-size">
											<small class="block-element bottom-push-3 dark-grey-font">Guest Signature</small>
											<small class="block-element"><?php echo $print_created_date.' '.$print_created_time; ?></small>
										</span>
										
										<span class="block-element new-line-space"></span>	
									</div>
								</div>
							</div>

							<p class="top-pull-20 alignct">
								<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor right-push-10" onclick="window.print()">Print</a>
							</p>
						<?php
					}
				}
				
			}
		}


		if(isset($_GET['r']) && $_GET['r'] >= 1) {

			$new_coupon_id = escape_data($_GET['r']);
			$new_coupon_code = idget_data($tbL129,$new_coupon_id,'coupon_code');

			$amount = idget_data($tbL129,$new_coupon_id,'coupon_amount');
			$print_amount = write_amountF($gh_get_decimal_format,$amount);

			$guest_id = idget_data($tbL129,$new_coupon_id,'customerid');
			if(isset($guest_id) && $guest_id >= 1) {
				$guest_name = idget_data($tbL102,$guest_id,'name');
				$guest_contact = idget_data($tbL102,$guest_id,'mobile');
			} else {
				$guest_name = idget_data($tbL129,$new_coupon_id,'guest_name');
				$guest_contact = idget_data($tbL129,$new_coupon_id,'guest_contact');
			}

			$created_date = idget_data($tbL129,$new_coupon_id,'datelogged');
			$created_time = idget_data($tbL129,$new_coupon_id,'timelogged');
			$expire_date = idget_data($tbL129,$new_coupon_id,'expires_on');
			$userid = idget_data($tbL129,$new_coupon_id,'userid');
			$status = idget_data($tbL129,$new_coupon_id,'status');

			$print_created_date = write_dateF($gh_get_date_format,$created_date);
			$print_created_time = write_timeF($gh_get_time_format,$created_time);
			$print_expire_date = write_dateF($gh_get_date_format,$expire_date);

			$issuer_name = idget_data($tbL7,$userid,'staffname');
			$status_name = arrayget_key($status_tag,$status);

			?>
				<div id="section-to-print" class="block-element" align="center">
					<div class="cs-width-500 white-theme box-border-thick top-push-30 sml-rounded-button pads30" align="center">
						<img src="<?php echo _FC_LOGO_Mx; ?>">
						<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
						<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
						<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
						<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>

						<br><h3 class="large">Coupon Details</h3>

						<div class="block-element top-pull-30 alignlt">
							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Coupon Code</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $new_coupon_code; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Guest Name</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $guest_name; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Guest Number</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $guest_contact; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Coupon Amount</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7">&#8358;<?php echo $print_amount; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Created On</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $print_created_date; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Expires On</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $print_expire_date; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-7">Cashier</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-7"><?php echo $issuer_name; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size bottom-push-20">Status</span>
							<span class="ln-display-box float-left ft-xsml-size add-bold bottom-push-20"><?php echo $status_name; ?></span>
							<span class="block-element new-line-space"></span>

							<span class="ln-display-box float-left nc-width-50 ft-xsml-size">
								<small class="block-element bottom-push-3 dark-grey-font">Cashier Signature</small>
								<small class="block-element"><?php echo $print_created_date.' '.$print_created_time; ?></small>
							</span>
							<span class="ln-display-box float-left ft-xsml-size">
								<small class="block-element bottom-push-3 dark-grey-font">Guest Signature</small>
								<small class="block-element"><?php echo $print_created_date.' '.$print_created_time; ?></small>
							</span>
							
							<span class="block-element new-line-space"></span>	
						</div>
					</div>
				</div>

				<p class="top-pull-20 alignct">
					<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor right-push-10" onclick="window.print()">Print</a>
				</p>
			<?php
		}
	?>
</div>