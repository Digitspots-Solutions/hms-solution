<?php
session_start();

define ("B3WF_PATH","../../../");
define ("B2WF_PATH","../../");
define ("BWF_PATH","../");
define ("ROOT_FLD","includes/");
define ("PUB_FLD","public/");

define ("_DB_SERVER_","database_connection.php");
define ("_FUNC_","functions.php");
define ("_RQ_FUNC_","rqu_functions.php");
define ("_SERVER_CUR_DATE_","server_run_time.php");
define ("_DB_TABLES_","tables.php");
define ("_COUNTY_DB_TABLE_","country_codelist.php");
define ("_LOC_TABLE_","ng_states_sql.php");
define ("_ROLE_","admin_roles.php");
define ("_APPMODULES_","app_modules.php");
define ("_USRP_","user_profile.php");
define ("_COUNTRY_STATE_DB_TABLE_","countries.php");
define ("_NOTIFICATION_TYPES_","notificationlist.php");
define ("_PACKAGE_TYPES_","packages.php");

//common utilities
define("SERVER_AUTHEN_ID", 1);
define("SOFTWARE_NAME", "HMS");
define("MANUFACTURER_NAME", "DigitSpot Solutions Ltd.");

define('USER_AUTHEN_ID',$_SESSION['authenticate_id']);
define('PAGE_AUTHEN_SID',$_SESSION['page_sid']);
define('DATA_AUTHEN_SID',200);

define ("PHP_EXT",".php"); //.php
define ("HTM_EXT",".html"); //.html

define ("CONTENT_HEADER","page_header.php");
define ("CONTENT_FOOTER","page_footer.php");
define ("DOMAIN_URL","http://127.0.0.1/digit-spot-hms/");


//sms gateway parameters
define ("_SHORT_NAME","HMS");
define ("_LOGO_URL","http://127.0.0.1/digit-spot-hms/theme/images/inc/logo.png");
define ("_NOREPLY_EMAIL","noreply@digitspots.com");
define ("_STATE","Nigeria");
define ("_CITY","Lagos");

$apiUrl = "http://smsapi.com/";
$apiUsername = "smsuseraccount";
$apiPassword = "***";

//web objs
define("_MF_LOGO_", "http://127.0.0.1/digit-spot-hms/theme/images/inc/mf-logo.png");
define("_FC_LOGO", "http://127.0.0.1/digit-spot-hms/theme/images/inc/logo.png");
define("_FC_LOGO_Mx", "http://127.0.0.1/digit-spot-hms/theme/images/inc/mini-logo.png");
define("_CTITLE_", "HMS: Hospitality Management Solutions | Enterprise Application for Hospitality Business");

$service_portal = array(
   "hotel-management"=>"http://127.0.0.1/digit-spot-hms-hotel/",
   "point-of-sale"=>"http://127.0.0.1/digit-spot-hms-pos/",
   "travels-and-tours"=>"http://127.0.0.1/digit-spot-hms-travel-master/"
);

//database tables
$tbL1 = "hotelsoft_admin_tbl";
$tbL2 = "states";
$tbL3 = "local_governments";
$tbL4 = "role_tbl";
$tbL5 = "privilege_tbl";
$tbL6 = "user_admin_role_tbl";
$tbL7 = "user_admin_tbl";
$tbL8 = "user_log_tbl";
$tbL9 = "user_log_tbl";
$tbL10 = "module_category_tbl";
$tbL11 = "category_library_tbl";
$tbL12 = "department_tbl";
$tbL13 = "amenities_tbl";
$tbL14 = "pos_store_tbl";
$tbL15 = "pos_store_category_tbl";
$tbL16 = "pos_store_product_tbl";
$tbL17 = "pos_store_table_tbl";
$tbL18 = "pos_store_tax_tbl";
$tbL19 = "counter_tbl";
$tbL20 = "shift_tbl";
$tbL21 = "counter_log_tbl";
$tbL22 = "user_counter_log_tbl";
$tbL23 = "user_shift_log_tbl";
$tbL24 = "paytype_tbl";
$tbL25 = "user_counter_fund_tbl";
$tbL26 = "currency_tbl";
$tbL27 = "discrepancy_counter_tbl";
$tbL28 = "denomination_tbl";
$tbL29 = "arrival_departure_mode_tbl";
$tbL30 = "booking_discount_tbl";
$tbL31 = "cancellation_policy_tbl";
$tbL32 = "cancellation_reason_tbl";
$tbL33 = "complimentary_tbl";
$tbL34 = "hotel_detail_tbl";
$tbL35 = "hotel_tax_tbl";
$tbL36 = "housekeeping_legend_tbl";
$tbL37 = "identity_types_tbl";
$tbL38 = "room_status_legend_tbl";
$tbL39 = "laundry_type_tbl";
$tbL40 = "notification_type_tbl";
$tbL41 = "payment_terms_tbl";
$tbL42 = "salutation_tbl";
$tbL43 = "src_of_biz_tbl";
$tbL44 = "testimonial_tbl";
$tbL45 = "gallery_tbl";
$tbL46 = "corporate_user_tbl";
$tbL47 = "account_category_tbl";
$tbL48 = "sub_account_category_tbl";
$tbL49 = "blocks_tbl";
$tbL50 = "floors_tbl";
$tbL51 = "occupancy_type_tbl";
$tbL52 = "room_type_tbl";
$tbL53 = "photo_room_type_tbl";
$tbL54 = "occupancy_rate_room_type_tbl";
$tbL55 = "amenities_room_type_tbl";
$tbL56 = "room_tbl";
$tbL57 = "room_control_tbl";
$tbL58 = "cspg_tbl";
$tbL59 = "cspg_office_tbl";
$tbL60 = "cspg_contact_person_tbl";
$tbL61 = "cspg_pos_package_tbl";
$tbL62 = "cspg_pos_ledger_tbl";
$tbL63 = "cspg_pos_ledger_payment_tbl";
$tbL64 = "countries";
$tbL65 = "wstates";
$tbL66 = "agent_tbl";
$tbL67 = "agent_office_tbl";
$tbL68 = "agent_contact_person_tbl";
$tbL69 = "agent_ledger_tbl";
$tbL70 = "agent_ledger_payment_tbl";
$tbL71 = "agent_user_tbl";
$tbL72 = "pos_sequence_tbl";
$tbL73 = "pos_invoice_sequence_tbl";
$tbL74 = "pos_order_tbl";
$tbL75 = "role_notification_type_tbl";
$tbL76 = "hotel_booking_sequence_tbl";
$tbL77 = "hotel_invoice_sequence_tbl";
$tbL78 = "season_mode_legend_tbl";
$tbL79 = "hotel_season_tbl";
$tbL80 = "hotel_season_tariff_tbl";
$tbL81 = "corporate_tariff_tbl";
$tbL82 = "corporate_tariff_tax_tbl";
$tbL83 = "inclusion_tbl";
$tbL84 = "package_tbl";
$tbL85 = "package_schedule_date_tbl";
$tbL86 = "package_component_tbl";
$tbL87 = "package_room_type_tbl";
$tbL88 = "package_room_inclusion_tbl";
$tbL89 = "other_hotel_cs_tbl";
$tbL90 = "other_hotel_ts_tbl";
$tbL91 = "other_hotel_ts_list_tbl";
$tbL92 = "pos_store_subcategory_tbl";
$tbL93 = "pos_store_product_history_tbl";
$tbL94 = "housekeeping_room_state_tbl";
$tbL95 = "housekeeping_room_state_history_tbl";
$tbL96 = "room_usage_status_history_tbl";
$tbL97 = "room_usage_status_tbl";
$tbL98 = "guest_active_rooms_tbl";
$tbL99 = "pos_orders_tbl";
$tbL100 = "pos_payment_tbl";
$tbL101 = "pos_payment_history_tbl";
$tbL102 = "guest_tbl";
$tbL103 = "auto_notify_tbl";
$tbL104 = "inbox_tbl";
$tbL105 = "recreation_member_tbl";
$tbL106 = "recreation_sub_member_tbl";
$tbL107 = "recreation_member_payment_tbl";
$tbL108 = "approval_settings_tbl";
$tbL109 = "hotel_settings_tbl";
$tbL110 = "sms_sender_detail_tbl";
$tbL111 = "pos_foodtype_timing_tbl";
$tbL112 = "tds_percentage_tbl";
$tbL113 = "housekeeping_tag_setting_tbl";
$tbL114 = "supplier_tbl";
$tbL115 = "stock_category_tbl";
$tbL116 = "stock_subcategory_tbl";
$tbL117 = "stock_itemgroup_tbl";
$tbL118 = "stock_item_tbl";
$tbL119 = "stock_item_stores_tbl";
$tbL120 = "stock_item_movement_tbl";
$tbL121 = "stock_item_purchase_order_tbl";
$tbL122 = "stock_item_request_tbl";
$tbL123 = "stores_tbl";
$tbL124 = "store_outlet_linkeditems_tbl";
$tbL125 = "store_outlet_stock_tbl";
$tbL126 = "store_outlet_purchase_tbl";
$tbL127 = "guest_occupancy_detail_tbl";
$tbL128 = "guest_arrival_departure_detail_tbl";
$tbL129 = "guest_coupons_tbl";
$tbL130 = "booking_invoice_tbl";
$tbL131 = "transaction_payment_tbl";
$tbL132 = "guest_activities_tbl";
$tbL133 = "booking_numbers_tbl";
$tbL134 = "daily_invoice_charges_tbl";
$tbL135 = "guest_inclusion_list_tbl";
$tbL136 = "night_audit_tbl";
$tbL137 = "night_audit_module_tbl";
$tbL138 = "customer_rebate_tbl";
$tbL139 = "guest_exclude_tax_tbl";
$tbL140 = "guest_room_occupancy_charges_tbl";
$tbL141 = "modify_booking_remarks_tbl";
$tbL142 = "workflow_names_tbl";
$tbL143 = "booking_settlement_tbl";
$tbL144 = "doc_request_approval_stages_tbl";
$tbL145 = "guest_ledger_tbl";
$tbL146 = "hotel_wkly_tariff_tbl";
$tbL147 = "nw_corporate_tariff_tbl";
$tbL148 = "invoice_numbers_tbl";
$tbL149 = "mc_tax_tbl";
$tbL150 = "presetsms_tbl";
$tbL151 = "job_approval_tbl";
$tbL152 = "item_request_tbl";
$tbL153 = "iou_fund_tbl";
$tbL154 = "paymaster_tbl";
$tbL155 = "sequencial_tbl";
$tbL156 = "warehouse_stock_items_tbl";
$tbL157 = "stock_transfer_tbl";
$tbL158 = "account_receivable_tbl";
$tbL159 = "account_fund_tbl";
$tbL160 = "city_ledger_tbl";
$tbL161 = "iou_expense_tbl";
$tbL162 = "modify_booking_tbl";
$tbL163 = "manual_rebate_tbl";
$tbL164 = "departmental_items_tbl";
$tbL165 = "employee_payment_tbl";
$tbL166 = "outlet_transfer_tbl";
$tbL168 = "treasury_fund_history_tbl";
$tbL169 = "walkin_guest_tbl";

/* php file by Webprox Technologies 
   Copyright 2023.
   
   All snippets licensed by this company.
*/

?>