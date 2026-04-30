<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_; include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

$toparentLog = true;
sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/pos_common_data.php";
include "../../includes/notificationlist.php";
include "../../includes/hotel_profile.php";
include "module_operation_privilege.php";

include "program_sequence_numbers.php";

define ("_LONG_NAME",$hotel_name);

#create table
createDatabasetable($var_tbl_303);

#load new program numbers
foreach($app_sequencials as $name => $no) {
	$pst_query = array("app_name"=>$name);
	$pst_field = array("app_name"=>$name,"start_number"=>$no);
	mysqli_data_insert($tbL155,$pst_field,$pst_query);
}


$post_header = "";
$post_message = "";

$smdl = "";
$islogfile = 0;
$logfile_msg = "";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script type="text/javascript" src="../../js/all.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element cs-height-100"></div>
<div class="block-element pads30">
	<?php

		##check for room blocking request
		$date_query = array("deletedata"=>0,"fromdate"=>$server_get_date);
		$get_rooms = mysqli_data_fetch($tbL57,'id,roomid',$date_query,'array');

		if(is_array($get_rooms)) {
			foreach ($get_rooms as $gr_key => $gr_value) {
				$gr = array("id"=>$gr_value["roomid"],"roomstatus"=>1);
				$gr_datasets = array("roomstatus"=>0);
				mysqli_data_update($tbL56,$gr_datasets,$gr);
			}
		}

		#----------------------------------------------------------------------

		##check for room unblocking notification
		$room_query = array("deletedata"=>0,"roomstatus"=>0);
		$get_query_rooms = mysqli_data_fetch($tbL56,'id',$room_query,'array');

		if(is_array($get_query_rooms)) {
			$serverdate = str_replace('-','',$server_get_date); $todate=''; $saynotify=0;
			foreach ($get_query_rooms as $grr_key => $grr_value) {
				$each_room_query = array("deletedata"=>0,"roomid"=>$grr_value["id"]);
				$get_room_status = mysqli_data_fetch($tbL57,'fromdate,todate',$each_room_query,'noarray');
				$todate = str_replace('-','',$get_room_status[1]);
				if($serverdate >= $todate) {
					$saynotify += 1;
					$notifytype = 1;
				}
			}
		}

		#----------------------------------------------------------------------

		#accounting module

		if(isset($_GET['logs']) && $_GET['logs'] == 'chart-of-accounts')
		{
			include "accounting/chart_of_accounts.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'journal-entry')
		{
			include "accounting/journal_entry.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'frontdesk-counter-payments')
		{
			include "accounting/frontdesk_counter_payments.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'outlet-counter-payments')
		{
			include "accounting/outlet_counter_payments.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'iou-receipt-report')
		{
			include "accounting/iou_receipt_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'iou-form')
		{
			include "accounting/iou_form.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'treasury-funds')
		{
			include "accounting/paywise_account.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'cash-purchase')
		{
			include "accounting/cash_purchase.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'city-ledger-report')
		{
			include "accounting/city_ledger_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'audit-trail')
		{
			include "accounting/audit_trail.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'employee-payments')
		{
			include "accounting/employee_payments.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'employee-payments-report')
		{
			include "accounting/employee_payments_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'profit-and-loss-statement')
		{
			include "accounting/profit_and_loss_statement.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'trial-balance')
		{
			include "accounting/trial_balance.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'balance-sheet')
		{
			include "accounting/balance_sheet.php";
		}

		#----------------------------------------------------------------------

		

		#administration module

		if(isset($_GET['logs']) && $_GET['logs'] == 'staff-counters-and-shifts')
		{
			include "administration/staff_counter_shift_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'hotel-settings' || $_GET['logs'] == 'company-settings'))
		{
			include "administration/hotel_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------


		if(isset($_GET['logs']) && $_GET['logs'] == 'arrival/departure-modes')
		{
			include "administration/arrival_departure_mode_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'departments')
		{
			include "administration/department_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'amenities')
		{
			include "administration/amenities_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'counters')
		{
			include "administration/counters_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'shifts')
		{
			include "administration/shifts_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pay-types')
		{
			include "administration/paytype_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'currencies')
		{
			include "administration/currency_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'denominations')
		{
			include "administration/denomination_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'booking-discount-matrices')
		{
			include "administration/booking_discount_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'cancellation-policy')
		{
			include "administration/cancellation_policy_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'cancellation-reasons')
		{
			include "administration/cancellation_reason_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'complimentary')
		{
			include "administration/complimentary_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'hotel-logo' || $_GET['logs'] == 'company-logo'))
		{
			include "administration/hotel_logo_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'hotel-details' || $_GET['logs'] == 'company-details'))
		{
			include "administration/hotel_detail_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		
		if(isset($_GET['logs']) && ($_GET['logs'] == 'hotel-tax' || $_GET['logs'] == 'company-tax'))
		{
			include "administration/hotel_tax_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'housekeeping-legend')
		{
			include "administration/housekeeping_legend_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-status-legend')
		{
			include "administration/room_status_legend_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'identity-types')
		{
			include "administration/identity_types_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'laundry-types')
		{
			include "administration/laundry_types_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'notification-types')
		{
			include "administration/notification_types_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'payment-terms')
		{
			include "administration/payment_terms_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'salutations')
		{
			include "administration/salutation_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'source-of-business')
		{
			include "administration/sourceofbiz_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'testimonials')
		{
			include "administration/testimonials_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'gallery')
		{
			include "administration/gallery_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of general settings


		if(isset($_GET['logs']) && $_GET['logs'] == 'account-categories')
		{
			include "administration/account_category_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of accounts


		if(isset($_GET['logs']) && $_GET['logs'] == 'roles-and-privileges')
		{
			include "administration/role_mdl_lib.php";
		}

		if(isset($_GET['logs']) && $_GET['logs'] == 'add-privilege')
		{
			include "administration/privilege_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'users')
		{
			include "administration/users_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporates')
		{
			include "administration/corporate_users_mdl_lib.php";
		}

		#----------------------------------------------------------------------


		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-agents')
		{
			include "administration/agent_users_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of user and security


		if(isset($_GET['logs']) && $_GET['logs'] == 'blocks')
		{
			include "administration/blocks_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'occupancy-types')
		{
			include "administration/occupancy_type_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-types')
		{
			include "administration/room_type_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'bulk-room-create')
		{
			include "administration/bulk_room_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'rooms')
		{
			include "administration/room_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-control')
		{
			include "administration/room_control_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of rooms manager


		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate/spl.-guests')
		{
			include "administration/corporate_and_guest_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate/spl.-guests-account')
		{
			include "corporate_account_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-agent-account')
		{
			include "administration/hotel_agent_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of rooms manager


		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-booking-sequence')
		{
			include "administration/hotel_booking_sequence_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-invoice-sequence')
		{
			include "administration/hotel_invoice_sequence_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of hotel booking sequence settings


		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-sequence')
		{
			include "administration/pos_sequence_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-invoice-sequence')
		{
			include "administration/pos_invoice_sequence_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of pos sequence settings

		if(isset($_GET['logs']) && $_GET['logs'] == 'user-activity-log')
		{
			include "administration/user_log_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of user log activity

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-recreation')
		{
			include "administration/recreation_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-accounting')
		{
			include "administration/accounting_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-city-ledger')
		{
			include "administration/cityledger_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-iou')
		{
			include "administration/iou_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-item-request')
		{
			include "administration/itemrequest_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-other-expenses')
		{
			include "administration/otherexpenses_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-pr')
		{
			include "administration/pr_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'for-pr-item-receiving' || $_GET['logs'] == 'for-item-receiving'))
		{
			include "administration/pr_item_receiving_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-pr-make-payment')
		{
			include "administration/pr_payment_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-rebate')
		{
			include "administration/rebate_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-supplier-item-price-changes')
		{
			include "administration/supplier_ipc_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-transfer-request')
		{
			include "administration/transfer_request_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		/*if(isset($_GET['logs']) && $_GET['logs'] == 'for-withdraw')
		{
			include "administration/withdraw_approval_setting_mdl_lib.php";
		}*/

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'for-stock-variation')
		{
			include "administration/stock_variation_approval_setting_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of approval settings


		#pos store module

		if(isset($_GET['logs']) && $_GET['logs'] == 'outlet-transfer-report')
		{
			include "pos/outlet_transfer_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-transaction-report')
		{
			include "pos/pos_transaction_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'shift-wise-sales-report')
		{
			include "pos/shift_wise_sales_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'store-consumption-report')
		{
			include "pos/store_consumption_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-stores')
		{
			include "administration/pos_store_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'categories')
		{
			include "pos/pos_store_category_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'pos-products' || $_GET['logs'] == 'pos-items'))
		{
			include "pos/pos_products_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-tax')
		{
			include "pos/pos_tax_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-tables')
		{
			include "pos/pos_table_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-revenue-report')
		{
			include "pos/pos_revenue_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'past-pos-order')
		{
			include "pos/past_pos_order_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-history')
		{
			include "pos/pos_history_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'item-request' || $_GET['logs'] == 'items-request'))
		{
			//include "pos/request_item.php";
			include "pos/request_item_mc.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'transfer-item')
		{
			include "pos/transfer_item.php";
		}

		if(isset($_GET['logs']) && $_GET['logs'] == 'transfer-item-mc')
		{
			include "pos/transfer_item_mc.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'consumable-stock')
		{
			include "pos/consumable_stock.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'serviceable-stock')
		{
			include "pos/serviceable_stock.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pos-account-float')
		{
			include "pos/pos_account_float.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'posorder')
		{
			include "pos/order_overview.php";
		}

		#----------------------------------------------------------------------
		#end of pos store


		#sales module

		if(isset($_GET['logs']) && $_GET['logs'] == 'hall-high-balance')
		{
			include "sales/hall_high_balance.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'frequent-customer-report')
		{
			include "sales/frequent_customer_report_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'season-modes')
		{
			include "sales/season_mode_legend_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'seasons')
		{
			include "sales/hotel_season_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'tariffs')
		{
			include "sales/hotel_season_tariff_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'weekly-tariff')
		{
			include "sales/hotel_weekly_tariff_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'enable-weekend-fares')
		{
			include "sales/enable_weekend_fares_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate-tariffs')
		{
			include "sales/corporate_tariff_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'inclusions')
		{
			include "sales/inclusion_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of price manager

		
		if(isset($_GET['logs']) && $_GET['logs'] == 'package-manager')
		{
			include "sales/package_manager_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'frontdesk-(packages)')
		{
			include "sales/frontdesk_package_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'web-(packages)')
		{
			include "sales/web_package_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of package master


		if(isset($_GET['logs']) && $_GET['logs'] == 'other-hotel-commission-slabs')
		{
			include "sales/thirdparty_hotel_commission_slab_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of commission slab

		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-tax-heads')
		{
			include "administration/hotel_tax_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'taxes-slab')
		{
			include "sales/taxes_slab_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		
		if(isset($_GET['logs']) && $_GET['logs'] == 'sms-marketing')
		{
			include "sales/sms_marketing_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'email-marketing')
		{
			include "sales/email_marketing_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'tax-calculator')
		{
			include "sales/tax_calculator_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of taxes slab


		#housekeeping module

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-legends')
		{
			include "housekeeping/room_legend.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'rooms-status')
		{
			include "housekeeping/room_status.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-maintenance')
		{
			include "housekeeping/room_maintenance.php";
		}

		#----------------------------------------------------------------------
		#end of housekeeping


		#general(cut-across) module

		if(isset($_GET['logs']) && $_GET['logs'] == 'employee-contacts')
		{
			include "general/employee_contacts.php";
		}

		#----------------------------------------------------------------------
		#end of general module



		#recreation module

		if(isset($_GET['logs']) && $_GET['logs'] == 'recreation-form')
		{
			include "recreation/recreation_form_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'renewal-form')
		{
			include "recreation/renewal_form_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'new-recreation-member')
		{
			include "recreation/new_recreation_member.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'recreation-detail-report')
		{
			include "recreation/recreation_detail_report_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'transfer-fund')
		{
			include "recreation/account_float.php";
		}

		#----------------------------------------------------------------------
		#end of recreation



		#material control module

		#Master Data
		if(isset($_GET['logs']) && $_GET['logs'] == 'suppliers')
		{
			include "materialcontrol/supplier_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'item-categories')
		{
			include "materialcontrol/item_categories_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'sub-categories')
		{
			include "materialcontrol/item_sub_categories_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'item-groups')
		{
			include "materialcontrol/item_groups_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'items')
		{
			include "materialcontrol/stock_items_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'cost-centers-/-stores')
		{
			include "materialcontrol/cost_centers_and_stores_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && ($_GET['logs'] == 'new-pr' || $_GET['logs'] == 'purchase-request'))
		{
			include "materialcontrol/new_pr_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of master data

		#Store
		if(isset($_GET['logs']) && $_GET['logs'] == 'storage-locations')
		{
			include "materialcontrol/storage_location_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of store

		#Mc taxes
		if(isset($_GET['logs']) && $_GET['logs'] == 'mc-taxes')
		{
			include "materialcontrol/mc_taxes_mdl_lib.php";
		}

		#----------------------------------------------------------------------
		#end of store



		#frontdesk module
		#new booking

		if(isset($_GET['logs']) && $_GET['logs'] == 'payment-transactions')
		{
			include "frontdesk/payment_transactions.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'new-booking')
		{
			include "frontdesk/new_booking_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		#booking operation
		if(isset($_GET['logs']) && $_GET['logs'] == 'rate-variation-report')
		{
			include "frontdesk/rate_variation_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'check-in-list')
		{
			include "frontdesk/checkin_list_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'check-out-list')
		{
			include "frontdesk/checkout_list_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'no-show-list')
		{
			include "frontdesk/noshow_list_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'reservation-list')
		{
			include "frontdesk/reservation_list_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'temp-room-list')
		{
			include "frontdesk/temp_room_list_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'in-house-guests')
		{
			include "frontdesk/inhouse_guest_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'cancelled-reservations')
		{
			include "frontdesk/cancelled_reservations_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'unsettled-booking-report')
		{
			include "frontdesk/unsettle_booking_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'complimentary-report')
		{
			include "frontdesk/complimentary_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'counter-transactions-report')
		{
			include "frontdesk/counter_transaction_report.php";
		}

		#----------------------------------------------------------------------
		#end of booking operation

		#Cashering

		if(isset($_GET['logs']) && $_GET['logs'] == 'counter-shift-report')
		{
			include "frontdesk/counter_shift_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'counter-logged-in-users')
		{
			include "frontdesk/counter_logged_in_users.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'guest-coupons')
		{
			include "frontdesk/guest_coupon_mdl_lib.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'reservations')
		{
			include "frontdesk/booking.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'account-float')
		{
			include "frontdesk/account_float.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'extension')
		{
			include "frontdesk/extension.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'manual-rebate')
		{
			include "frontdesk/manual_rebate.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'rebate-report')
		{
			include "frontdesk/rebate_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'list-manual-rebate')
		{
			include "frontdesk/list_manual_rebate.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'early-check-in-and-late-check-out-report')
		{
			include "frontdesk/earlycheckin_latecheckout_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'day-use-report')
		{
			include "frontdesk/dayuse_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'booking-history')
		{
			include "frontdesk/booking_history.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'modify-booking-report')
		{
			include "frontdesk/modify_booking_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'discounts-/-allowance-given')
		{
			include "frontdesk/discount_report.php";
		}

		#----------------------------------------------------------------------


		#Report Module

		if(isset($_GET['logs']) && $_GET['logs'] == 'sss-report')
		{
			include "reports/sss_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'police-report')
		{
			include "reports/police_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'immigration-report')
		{
			include "reports/immigration_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'guest-history-report')
		{
			include "reports/guest_history_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'manager-flash-report')
		{
			include "reports/manager_flash_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'daily-business-summary-report')
		{
			include "reports/daily_business_summary_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'daily-high-balance-report')
		{
			include "reports/daily_high_balance_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'night-audit-summary-report')
		{
			include "reports/night_audit_summary_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'summary-rooms-sold-report')
		{
			include "reports/summary_room_sold.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'summary-revenue-report')
		{
			include "reports/summary_revenue_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'room-type-revenue-report')
		{
			include "reports/room_type_revenue_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'tentative-collations-report')
		{
			include "reports/tentative_collations_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'mod-analysis')
		{
			include "reports/mod_analysis.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'hotel-operation-report')
		{
			include "reports/hotel_operation_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'outlet-transfer')
		{
			include "reports/outlet_transfer.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'housekeeping-history')
		{
			include "housekeeping/housekeeping_history.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate-end-of-the-month-report')
		{
			include "reports/corporate_eom_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate-consumption-report')
		{
			include "reports/corporate_consumption_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'corporate-/-group-transactions-report')
		{
			include "reports/corporate_cdb.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'coupon-report')
		{
			include "reports/coupon_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'rack-rate-room-floor-report')
		{
			include "reports/rack_rate_room_floor_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'pay-type-report')
		{
			include "reports/paytype_report.php";
		}

		#----------------------------------------------------------------------

		if(isset($_GET['logs']) && $_GET['logs'] == 'food-and-beverages-revenue-report')
		{
			include "reports/food_and_beverages_revenue_report.php";
		}

		#----------------------------------------------------------------------



		if(isset($_GET['logs']) && $_GET['logs'] == 'modals')
		{
			$pfx = $_GET['prefix'];
			include $pfx."/modals.php";
		}

		#----------------------------------------------------------------------

		
		##create a log file
		if(isset($islogfile) && $islogfile == 1) {
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$logfile_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL8,$log_datasets,'');
		}

		##log guest activities
		if(isset($isguestAct) && $isguestAct == 1) {
			$guest_activities_dataproperty = array("booking_number"=>$pst_booking_number,"customerid"=>$ths_guest_pry,"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

			if(isset($remark_tag) && !empty($remark_tag)) { $guest_activities_dataproperty['remark_tag'] = $remark_tag; }
			if(isset($app_tag) && !empty($app_tag)) { $guest_activities_dataproperty['app_tag'] = $app_tag; }
			if(isset($session_tag) && !empty($session_tag)) { $guest_activities_dataproperty['session_tag'] = $session_tag; }
			
			mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');
		}
		

		##pop notifications

		if(isset($saynotify) && $saynotify >= 1) {
			switch ($notifytype) {
				case 1:
					$header = 'Room Block Notification!';
					$message = 'You have rooms that are due to unblocking. Please refer to whom it concerns';
					break;
				
				case 2:
					$header = $post_header;
					$message = $post_message;
					break;

				default:
					$header = 'Error Notification!';
					$message = 'No message found';
					break;
			}

			?>
				
				<div id="notifybox" class="noshow fx-position-stick zind-1 motion fscr" align="right">
					<div class="block-element cs-height-120"></div>
					<div class="cs-width-400 white-theme pads20 obj-shadow right-push-50 sml-rounded-button alignlt">
						<h4 class="large red-font"><?php echo $header; ?></h4>
						<small class="block-element top-push-10"><?php echo $message; ?></small>
					</div>
				</div>

				<script>
					window.addEventListener('load',function() { 
						parent.document.getElementById('workspace').scrollTop = 0;
						objDisplay('notifybox'); autohidePopupBox('notifybox',2000);
					}, false);
				</script>

			<?php
		}

	?>
</div>

<div id="processbar" class="fx-position-stick fscr zind-1 motion noshow" align="center">
	<div class="block-element nc-height-10">&nbsp;</div>
	<div class="cs-width-250 white-theme obj-shadow pads20">Processing request..</div>
</div>

<script>

sessionStorage.setItem('workaround',0);

setInterval(() => {
	var wka;
	wka = sessionStorage.getItem('workaround');
	wka = Number(wka) + 1;
	sessionStorage.setItem('workaround',wka);
},1000);

window.addEventListener("mousemove", (event) => {
	var e = event || window.event;
	if(e.clientX || e.clientY) { sessionStorage.setItem('workaround',0); }
});

window.addEventListener("keypress", (event) => {
	var e = event || window.event;
	if(e.code || e.which) { sessionStorage.setItem('workaround',0); }
});

</script>