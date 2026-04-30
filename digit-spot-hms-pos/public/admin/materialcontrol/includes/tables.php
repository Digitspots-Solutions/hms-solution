<?php
//table creation queries
//Copyright 2022

#database table names
#default tables

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
$tbL156 = "stock_transfer_tbl";
$tbL157 = "bad_damage_stock_tbl";
$tbL158 = "iou_expense_tbl";
$tbL159 = "modify_booking_tbl";
$tbL160 = "manual_rebate_tbl";
$tbL161 = "departmental_items_tbl";

##---------------------------------------------------------------------------------------

$mtbL1 = "supplier_tbl";
$mtbL2 = "stock_category_tbl";
$mtbL3 = "stock_subcategory_tbl";
$mtbL4 = "stock_itemgroup_tbl";
$mtbL5 = "stock_item_tbl";
$mtbL6 = "stock_item_stores_tbl";
$mtbL7 = "stock_item_movement_tbl";
$mtbL8 = "stock_item_purchase_order_tbl";
$mtbL9 = "stock_item_request_tbl";
$mtbL10 = "stores_tbl";
$mtbL11 = "store_outlet_linkeditems_tbl";
$mtbL12 = "store_outlet_stock_tbl";
$mtbL13 = "store_outlet_purchase_tbl";
$mtbL14 = "mc_tax_tbl";
$mtbL15 = "department_tbl";
$mtbL16 = "user_admin_tbl";
$mtbL17 = "role_tbl";
$mtbL18 = "item_cost_centre_tbl";
$mtbL19 = "warehouse_stock_items_tbl";
$mtbL20 = "stock_variance_tbl";
$mtbL21 = "bad_damage_stock_tbl";
$mtbL22 = "departmental_items_tbl";
$mtbL23 = "outlet_transfer_tbl";
$mtbL24 = "stock_adjustment_tbl";
$mtbL25 = "job_order_tbl";
$mtbL26 = "job_order_state_tbl";
$mtbL27 = "job_order_batch_tbl";

##---------------------------------------------------------------------------------------

#database table structure setup


##table 109: Supplier Table
$var_tbl_109 = "CREATE TABLE IF NOT EXISTS supplier_tbl(
id bigint(50) auto_increment,
supplier_name varchar(250),
mobile varchar(50),
fax varchar(50),
emailaddress varchar(50),
website varchar(50),
order_method varchar(50),
address varchar(500),
city varchar(50),
country varchar(250),
pincode varchar(50),
person_incharge varchar(150),
sales_representative varchar(250),
pan varchar(250),
extn varchar(50),
paymenterm varchar(500),
datelogged date,
timelogged time,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 110: Stock Category Table
$var_tbl_110 = "CREATE TABLE IF NOT EXISTS stock_category_tbl(
id bigint(50) auto_increment,
postoreid int default 0,
category varchar(500),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 111: Stock Sub-Category Table
$var_tbl_111 = "CREATE TABLE IF NOT EXISTS stock_subcategory_tbl(
id bigint(50) auto_increment,
postoreid int default 0,
categoryid int,
subcategory varchar(500),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 112 Stock Item Group Table
$var_tbl_112 = "CREATE TABLE IF NOT EXISTS stock_itemgroup_tbl(
id bigint(50) auto_increment,
categoryid int,
subcategoryid int,
groupname varchar(500),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 113: Stock Item Table
$var_tbl_113 = "CREATE TABLE IF NOT EXISTS stock_item_tbl(
id bigint(50) auto_increment,
postoreid int default 0,
categoryid int,
subcategoryid int,
itemgroupid int,
itemcode varchar(50),
item varchar(500),
detail text,
buying_unit int,
selling_unit int,
noofpiece_bu varchar(50),
noofpiece_su varchar(50),
calc_formular varchar(250),
isexpire varchar(10),
expiry_date date,
expiry_status varchar(50) default 'Valid',
minimum_stock varchar(50) default 0,
maximum_stock varchar(50) default 0,
iscost_center varchar(10),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 114: Stock Item Stores Table
$var_tbl_114 = "CREATE TABLE IF NOT EXISTS stock_item_stores_tbl(
id bigint(50) auto_increment,
itemid int,
stockin varchar(50),
stockout varchar(50),
balance varchar(50),
deletedata int default 0,
primary key(id)
)";

##table 115: Stock Item (Movement) Table
$var_tbl_115 = "CREATE TABLE IF NOT EXISTS stock_item_movement_tbl(
id bigint(50) auto_increment,
itemid int,
ref_number varchar(50),
qty varchar(50),
item_receiver int,
typeof_movement varchar(150),
statusof_movement text,
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 116: Stock Item (Purchasing) Table
$var_tbl_116 = "CREATE TABLE IF NOT EXISTS stock_item_purchase_order_tbl(
id bigint(50) auto_increment,
itemid int,
uom int,
supplierid int,
store int,
order_number varchar(150),
lpo varchar(50),
order_date date,
delivery_date date default '0000-00-00',
delivery_note text,
unitprice varchar(50),
qty_ordered varchar(50),
qty_received varchar(50) default 0,
qty_diff varchar(50) default 0,
order_total_amount varchar(50),
order_tax_amount varchar(50),
order_net_amount varchar(50),
order_total_r_amount varchar(50) default 0,
order_tax_r_amount varchar(50) default 0,
order_net_r_amount varchar(50) default 0,
order_status varchar(150) default 'Pending',
receipt_status varchar(150) default 'Pending',
first_approval int default 0,
second_approval int default 0,
third_approval int default 0,
fourth_approval int default 0,
fifth_approval int default 0,
numberof_approvals int default 0,
qc int,
gstat varchar(30) default 'Pending',
pr_status varchar(50) default 'Pending',
ispr_to_manual varchar(50) default 'No',
var_status int default 0,
var_approval varchar(10) default 'No',
invoice_number varchar(50),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 117: Stock Item (Request) Table
$var_tbl_117 = "CREATE TABLE IF NOT EXISTS stock_item_request_tbl(
id bigint(50) auto_increment,
itemid int,
request_number varchar(50),
request_src int,
request_to int,
qty varchar(50),
remarks text,
status text,
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 118: Stores Table
$var_tbl_118 = "CREATE TABLE IF NOT EXISTS stores_tbl(
id bigint(50) auto_increment,
store_number varchar(50),
store_name varchar(150),
store_type varchar(150),
parent_store int,
detail text,
address text,
department int,
status varchar(50) default 'Active',
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 119: Store (Link Stock Item) Table
$var_tbl_119 = "CREATE TABLE IF NOT EXISTS store_outlet_linkeditems_tbl(
id bigint(50) auto_increment,
storeid int,
itemid int,
deletedata int default 0,
primary key(id)
)";

##table 120: Store (Outlet Stock) Table
$var_tbl_120 = "CREATE TABLE IF NOT EXISTS store_outlet_stock_tbl(
id bigint(50) auto_increment,
storeid int,
itemid int,
stockin varchar(50),
stockout varchar(50),
balance varchar(50),
deletedata int default 0,
primary key(id)
)";

##table 121: Store (Purchase Statistics) Table
$var_tbl_121 = "CREATE TABLE IF NOT EXISTS store_outlet_purchase_tbl(
id bigint(50) auto_increment,
storeid int,
itemid int,
order_number varchar(150),
datelogged date,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 147: Warehouse Item Cost Centre Table
$var_tbl_147 = "CREATE TABLE IF NOT EXISTS item_cost_centre_tbl(
id bigint(50) auto_increment,
itemid int,
costprice varchar(50),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 148: Warehouse Stock Items Table
$var_tbl_148 = "CREATE TABLE IF NOT EXISTS warehouse_stock_items_tbl(
id bigint(50) auto_increment,
storageid int,
itemgroupid int,
categoryid int,
subcategoryid int,
itemid int,
item varchar(250),
uom int,
supplierid int,
store int default 0,
order_number varchar(50),
invoice_number varchar(50),
delivery_date date default '0000-00-00',
delivery_note text,
unitprice varchar(50),
stockin varchar(50),
stockout varchar(50) default 0,
balance varchar(50),
total_cost varchar(50),
total_sales varchar(50),
total_profit varchar(50),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 149: Warehouse Stock Adjustment Table
$var_tbl_149 = "CREATE TABLE IF NOT EXISTS warehouse_stock_items_adjustment_tbl(
id bigint(50) auto_increment,
stockid int,
itemid int,
qty varchar(50),
detail text,
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 150: Job Approval Table
$var_tbl_150 = "CREATE TABLE IF NOT EXISTS job_approval_tbl(
id bigint(50) auto_increment,
job_level int,
subject varchar(250),
user_one int default 0,
approval_one int default 0,
comment_one varchar(500),
user_two int default 0,
approval_two int default 0,
comment_two varchar(500),
user_three int default 0,
approval_three int default 0,
comment_three varchar(500),
user_four int default 0,
approval_four int default 0,
comment_four varchar(500),
user_five int default 0,
approval_five int default 0,
comment_five varchar(500),
approval_type varchar(50),
approval_status varchar(50) default 'Pending',
deletedata int default 0,
primary key(id)
)";

##table 151: Stock Variance Table
$var_tbl_151 = "CREATE TABLE IF NOT EXISTS stock_variance_tbl(
id bigint(50) auto_increment,
order_number varchar(150),
itemid int,
qty_required varchar(50),
qty_bought varchar(50),
qty_diff varchar(50) default 0,
price_request varchar(50),
market_price varchar(50),
price_diff varchar(50),
total_amount varchar(50),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 152: Bad & Damage Stock Items Table
$var_tbl_152 = "CREATE TABLE IF NOT EXISTS bad_damage_stock_tbl(
id bigint(50) auto_increment,
bnd_number varchar(50),
itemid int,
store int default 0,
unitprice varchar(50),
stock varchar(50),
uom int,
total_cost varchar(50),
userid int,
bnd_status varchar(50) default 'Pending',
isdisposed varchar(50) default 'No',
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Item Request Table
$var_tbl_153 = "CREATE TABLE IF NOT EXISTS item_request_tbl(
id bigint(50) auto_increment,
request_number varchar(50),
posid int,
storeid int,
itemid int,
uom int,
qty_required varchar(50),
qty_received varchar(50) default 0,
qty_diff varchar(50) default 0,
stock_type varchar(50),
userid int,
whr_user int default 0,
status varchar(50) default 'Reviewing',
acceptance int default 0,
for_pr varchar(50) default 'No',
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: IOU fund Table
$var_tbl_154 = "CREATE TABLE IF NOT EXISTS iou_fund_tbl(
id bigint(50) auto_increment,
iou_no varchar(50),
pr_no varchar(50),
amount varchar(50) default 0,
pr_amount varchar(50) default 0,
variance_amount varchar(50) default 0,
disbursedby int default 0,
retiredby int default 0,
status varchar(50) default 'Pending',
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Sequencial Table
$var_tbl_155 = "CREATE TABLE IF NOT EXISTS sequencial_tbl(
id bigint(50) auto_increment,
app_name varchar(50),
start_number varchar(50),
deletedata int default 0,
unique(app_name),
primary key(id)
)";

##table: pay master Table
$var_tbl_156 = "CREATE TABLE IF NOT EXISTS paymaster_tbl(
id bigint(50) auto_increment,
pay_no varchar(50),
pr_no varchar(50),
amount varchar(50) default 0,
pr_amount varchar(50) default 0,
variance_amount varchar(50) default 0,
disbursedby int default 0,
retiredby int default 0,
status varchar(50) default 'Pending',
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: transfer Table
$var_tbl_157 = "CREATE TABLE IF NOT EXISTS stock_transfer_tbl(
id bigint(50) auto_increment,
transfer_number varchar(50),
from_posid int,
to_posid int,
itemid int,
qty_transfer varchar(50),
stock_type varchar(50) default 'consumable',
uom int,
userid int,
tagged_name varchar(50),
transfer_status varchar(50) default 'Pending',
isbad int default 0,
bnd int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Departmental Items Table
$var_tbl_158 = "CREATE TABLE IF NOT EXISTS departmental_items_tbl(
id bigint(50) auto_increment,
departmentid int,
storageid int,
categoryid int,
subcategoryid int,
itemcode varchar(50),
item varchar(500),
stockin varchar(50) default 0,
uom varchar(50),
cost varchar(50),
stockout varchar(50) default 0,
balance varchar(50) default 0,
detail text,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Stock Adjustment Table
$var_tbl_159 = "CREATE TABLE IF NOT EXISTS stock_adjustment_tbl(
id bigint(50) auto_increment,
store int,
store_type varchar(50),
stockid int,
itemid int,
current_stock varchar(50),
adjusted_stock varchar(50),
new_stock varchar(50),
adjustment_type varchar(50),
adjustment_process varchar(50),
remarks varchar(500),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Job Order table
$var_tbl_160 = "CREATE TABLE IF NOT EXISTS job_order_tbl(
id bigint(50) auto_increment,
batch_no int,
pr_no varchar(50),
supplierid int,
itemid int,
qty_received varchar(50),
unit_price varchar(50) default 0,
total_amount varchar(50) default 0,
qty_left varchar(50),
delivery_date date,
receivedby int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Job Order State table
$var_tbl_161 = "CREATE TABLE IF NOT EXISTS job_order_state_tbl(
id bigint(50) auto_increment,
delivery_no varchar(50),
pr_no varchar(50),
status varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
unique(pr_no),
primary key(id)
)";

##table: Job Order State table
$var_tbl_162 = "CREATE TABLE IF NOT EXISTS job_order_batch_tbl(
id bigint(50) auto_increment,
batch_no int,
pr_no varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";


?>