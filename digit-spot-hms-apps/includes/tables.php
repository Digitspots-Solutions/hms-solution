<?php
/* file to database tables */

##table 0: Server Date/Time Table
$var_tbl_0 = "CREATE TABLE IF NOT EXISTS serverdate_tbl(
id int,
date date,
time time
)";

##table 1: Admin Login Table
$var_tbl_1 = "CREATE TABLE IF NOT EXISTS hotelsoft_admin_tbl(
id bigint(50) auto_increment,
staffname varchar(250),
username varchar(50),
emailaddress varchar(50),
password varchar(250),
uaccess varchar(50) default 'master',
status varchar(50) default 'Active',
primary key(id)
)";

##table 2: Log 1
$var_tbl_2 = "CREATE TABLE IF NOT EXISTS hotelsoft_log_tbl(
id bigint(50) auto_increment,
userid bigint,
message text,
datelogged date,
timelogged time,
archivedata int default 0,
deletedata int default 0,
primary key(id)
)";

##table 3: Log 2
$var_tbl_3 = "CREATE TABLE IF NOT EXISTS user_log_tbl(
id bigint(50) auto_increment,
userid bigint,
logcategory varchar(150),
subctg int default 0,
message text,
datelogged date,
timelogged time,
archivedata int default 0,
deletedata int default 0,
primary key(id)
)";

##table 4: Module Category Table
$var_tbl_4 = "CREATE TABLE IF NOT EXISTS module_category_tbl(
id bigint(50) auto_increment,
moduleid int,
category varchar(250),
status varchar(50) default 'Active',
primary key(id)
)";

##table 5: Category Library Table
$var_tbl_5 = "CREATE TABLE IF NOT EXISTS category_library_tbl(
id bigint(50) auto_increment,
categoryid int,
name varchar(250),
status varchar(50) default 'Active',
primary key(id)
)";

##table 6: Department Table
$var_tbl_6 = "CREATE TABLE IF NOT EXISTS department_tbl(
id bigint(50) auto_increment,
department varchar(250),
phonenumber varchar(50),
extn varchar(20),
address text,
hod int default 0,
primarycontact int default 0,
status varchar(50) default 'Active',
mdl int,
deletedata int default 0,
primary key(id)
)";

##table 7: Role Table
$var_tbl_7 = "CREATE TABLE IF NOT EXISTS role_tbl(
id bigint(50) auto_increment,
role varchar(150),
departmentid int,
mdl int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

$var_tbl_8 = "CREATE TABLE IF NOT EXISTS user_admin_tbl(
id bigint(50) auto_increment,
primarycontact int,
branchid int,
staffnumber varchar(150),
staffname varchar(250),
gender varchar(50),
mobile varchar(50),
department int,
role int,
homenumber varchar(50),
worknumber varchar(50),
passportnumber varchar(150),
qualification varchar(250),
extn varchar(50),
isforcedip varchar(5),
forceip varchar(500),
datehire date,
dateofbirth date,
salary varchar(50),
username varchar(50),
emailaddress varchar(50),
password varchar(250),
uaccess varchar(50) default 'limited',
status varchar(50) default 'Active',
deletedata int default 0,
datelogged date,
timelogged time,
unique(username),
primary key(id)
)";

##table 9: Privilege Table
$var_tbl_9 = "CREATE TABLE IF NOT EXISTS privilege_tbl(
id bigint(50) auto_increment,
roleid int,
classid int,
name int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 10: Amenities Table
$var_tbl_10 = "CREATE TABLE IF NOT EXISTS amenities_tbl(
id bigint(50) auto_increment,
name varchar(500),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 11: Pos Store Table
$var_tbl_11 = "CREATE TABLE IF NOT EXISTS pos_store_tbl(
id bigint(50) auto_increment,
store int default 0,
posname varchar(500),
postype varchar(50),
departmentid int,
isfoodtype varchar(50),
iscounter varchar(50),
isdiscount varchar(10) default 'No',
guest_discount varchar(50) default 0,
staff_discount varchar(50) default 0,
isfoodflash varchar(50) default 'No',
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 12: Pos Store Category Table
$var_tbl_12 = "CREATE TABLE IF NOT EXISTS pos_store_category_tbl(
id bigint(50) auto_increment,
postoreid int,
program_id int,
category varchar(500),
detail text,
status varchar(50) default 'Active',
isdefault varchar(5) default 'Yes',
deletedata int default 0,
primary key(id)
)";

##table 13: Pos Store Sub-Category Table
$var_tbl_13 = "CREATE TABLE IF NOT EXISTS pos_store_subcategory_tbl(
id bigint(50) auto_increment,
postoreid int,
categoryid int,
subcategory varchar(500),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 14: Pos Store Product Table
$var_tbl_14 = "CREATE TABLE IF NOT EXISTS pos_store_product_tbl(
id bigint(50) auto_increment,
storageid int default 0,
storagetype varchar(50),
postoreid int,
categoryid int,
subcategoryid int,
itemcode varchar(50),
item varchar(500),
stockin varchar(50) default 0,
uom varchar(50),
price varchar(50),
stockout varchar(50) default 0,
balance varchar(50) default 0,
detail text,
isfeature varchar(10),
isstaff varchar(10),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 15: Pos Store Serve-Tables Table
$var_tbl_15 = "CREATE TABLE IF NOT EXISTS pos_store_table_tbl(
id bigint(50) auto_increment,
postoreid int,
tablename varchar(150),
tabletype varchar(100),
noofperson int,
tableshape varchar(150),
tablesize varchar(150),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 16: Pos Store Tax Table
$var_tbl_16 = "CREATE TABLE IF NOT EXISTS pos_store_tax_tbl(
id bigint(50) auto_increment,
postoreid int,
taxname varchar(250),
taxcharge varchar(50),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 17: Counter Table
$var_tbl_17 = "CREATE TABLE IF NOT EXISTS counter_tbl(
id bigint(50) auto_increment,
countername varchar(250),
countertype varchar(150),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 18: Shift Table
$var_tbl_18 = "CREATE TABLE IF NOT EXISTS shift_tbl(
id bigint(50) auto_increment,
shiftname varchar(250),
startime time,
startimelabel varchar(50),
endtime time,
endtimelabel varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 19: Counter Log Table
$var_tbl_19 = "CREATE TABLE IF NOT EXISTS counter_log_tbl(
id bigint(50) auto_increment,
counterid int,
status varchar(50) default 'Open',
primary key(id)
)";

##table 20: User Counter Log Table
$var_tbl_20 = "CREATE TABLE IF NOT EXISTS user_counter_log_tbl(
id bigint(50) auto_increment,
counterid int,
userid int,
logstatus varchar(50),
message text,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 21: User Shift Log Table
$var_tbl_21 = "CREATE TABLE IF NOT EXISTS user_shift_log_tbl(
id bigint(50) auto_increment,
shiftid int,
counterid int,
userid int,
resumptiontime time,
closetime time default '00:00:00',
datelogged date,
dateclosed date,
status varchar(30) default 'Open',
deletedata int default 0,
primary key(id)
)";

##table 22: Pay Type Table
$var_tbl_22 = "CREATE TABLE IF NOT EXISTS paytype_tbl(
id bigint(50) auto_increment,
name varchar(150),
paytype varchar(50),
isreceivable varchar(10),
iscounter varchar(10) default 'Yes',
isdefault int default 'No',
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 23: User Counter Fund Table
$var_tbl_23 = "CREATE TABLE IF NOT EXISTS user_counter_fund_tbl(
id bigint(50) auto_increment,
counterid int,
shiftid int,
userid int,
fundid int,
openingbalance varchar(50),
closingbalance varchar(50) default 0,
fundadded varchar(50) default 0,
withdrawal varchar(50) default 0,
collection varchar(50) default 0,
refunds varchar(50) default 0,
moneyathand varchar(50) default 0,
err varchar(50) default 0,
ispast int default 0,
datelogged date,
timelogged time,
bizday int,
deletedata int default 0,
primary key(id)
)";

##table 24: Currency Table
$var_tbl_24 = "CREATE TABLE IF NOT EXISTS currency_tbl(
id bigint(50) auto_increment,
currencyname varchar(150),
shortname varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 25: Counter Discrepancy Table
$var_tbl_25 = "CREATE TABLE IF NOT EXISTS discrepancy_counter_tbl(
id bigint(50) auto_increment,
userid int,
counterid int,
message text,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 26: Denominations Table
$var_tbl_26 = "CREATE TABLE IF NOT EXISTS denomination_tbl(
id bigint(50) auto_increment,
name varchar(50),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 27: Arrival / Departure Mode Table
$var_tbl_27 = "CREATE TABLE IF NOT EXISTS arrival_departure_mode_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 28: Booking Discount Matrix Table
$var_tbl_28 = "CREATE TABLE IF NOT EXISTS booking_discount_tbl(
id bigint(50) auto_increment,
roleid int,
discount varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 29: Cancellation Policy Table
$var_tbl_29 = "CREATE TABLE IF NOT EXISTS cancellation_policy_tbl(
id bigint(50) auto_increment,
policyname varchar(250),
discount varchar(50),
cancellationtype varchar(50),
ftime int,
ttime int,
detail varchar(250),
isactive varchar(10),
deletedata int default 0,
primary key(id)
)";

##table 30: Cancellation Reason Table
$var_tbl_30 = "CREATE TABLE IF NOT EXISTS cancellation_reason_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 31: Complimentary Table
$var_tbl_31 = "CREATE TABLE IF NOT EXISTS complimentary_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 32: Hotel Detail Table
$var_tbl_32 = "CREATE TABLE IF NOT EXISTS hotel_detail_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
address text,
phonenumber1 varchar(250),
phonenumber2 varchar(250),
contactemail varchar(250),
url varchar(250),
businesstype varchar(250),
starcategory varchar(5),
country varchar(250),
state varchar(250),
city varchar(250),
areaname varchar(250),
zipcode varchar(50),
seokeywords text,
tnc text,
otherinfo text,
deletedata int default 0,
primary key(id)
)";

##table 33: Hotel Tax Table
$var_tbl_33 = "CREATE TABLE IF NOT EXISTS hotel_tax_tbl(
id bigint(50) auto_increment,
taxname varchar(250),
taxcharge varchar(50),
detail text,
isonlinebooking varchar(10),
isactive varchar(10),
deletedata int default 0,
primary key(id)
)";

##table 34: Housekeeping Legend Table
$var_tbl_34 = "CREATE TABLE IF NOT EXISTS housekeeping_legend_tbl(
id bigint(50) auto_increment,
legendname varchar(250),
colorcode varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 35: Identity Type Table
$var_tbl_35 = "CREATE TABLE IF NOT EXISTS identity_types_tbl(
id bigint(50) auto_increment,
name varchar(50),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 36: Room Status Legend Table
$var_tbl_36 = "CREATE TABLE IF NOT EXISTS room_status_legend_tbl(
id bigint(50) auto_increment,
legendname varchar(250),
colorcode varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 37: Laundry Type Table
$var_tbl_37 = "CREATE TABLE IF NOT EXISTS laundry_type_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 38: Notification Types Table
$var_tbl_38 = "CREATE TABLE IF NOT EXISTS notification_type_tbl(
id bigint(50) auto_increment,
name int,
detail text,
ismail varchar(10),
issms varchar(10),
isinbox varchar(10),
isbackground varchar(10),
deliveron date,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 39: Payment Terms Table
$var_tbl_39 = "CREATE TABLE IF NOT EXISTS payment_terms_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 40: Salutation Table
$var_tbl_40 = "CREATE TABLE IF NOT EXISTS salutation_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 41: Source of Biz Table
$var_tbl_41 = "CREATE TABLE IF NOT EXISTS src_of_biz_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 42: Testimonial Table
$var_tbl_42 = "CREATE TABLE IF NOT EXISTS testimonial_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
email varchar(50),
url varchar(50),
ispublished varchar(50),
datelogged date,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 43: Gallery Table
$var_tbl_43 = "CREATE TABLE IF NOT EXISTS gallery_tbl(
id bigint(50) auto_increment,
thumbnail_image_url varchar(250),
big_image_url varchar(250),
detail text,
gallery_type varchar(50),
deletedata int default 0,
primary key(id)
)";

##table 44: Corporate User Table
$var_tbl_44 = "CREATE TABLE IF NOT EXISTS corporate_user_tbl(
id bigint(50) auto_increment,
branchid int,
corporatenumber varchar(150),
corporatename int,
flname varchar(250),
username varchar(50),
password varchar(250),
emailaddress varchar(50),
mobile varchar(50),
role int,
status varchar(50) default 'Active',
deletedata int default 0,
datelogged date,
timelogged time,
primary key(id)
)";


##table 45: Account Categories Table
$var_tbl_45 = "CREATE TABLE IF NOT EXISTS account_category_tbl(
id bigint(50) auto_increment,
name varchar(500),
detail text,
balance varchar(50) default 0,
deletedata int default 0,
primary key(id)
)";

##table 46: Sub Account Categories Table
$var_tbl_46 = "CREATE TABLE IF NOT EXISTS sub_account_category_tbl(
id bigint(50) auto_increment,
account_category_id int,
name varchar(500),
detail text,
balance varchar(50) default 0,
deletedata int default 0,
primary key(id)
)";


##table 47: Building/Blocks Table
$var_tbl_47 = "CREATE TABLE IF NOT EXISTS blocks_tbl(
id bigint(50) auto_increment,
name varchar(500),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 48: Block Floors Table
$var_tbl_48 = "CREATE TABLE IF NOT EXISTS floors_tbl(
id bigint(50) auto_increment,
blockid int,
name varchar(500),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 49: Occupancy Types Table
$var_tbl_49 = "CREATE TABLE IF NOT EXISTS occupancy_type_tbl(
id bigint(50) auto_increment,
name varchar(500),
shortname varchar(250),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 50: Room Types Table
$var_tbl_50 = "CREATE TABLE IF NOT EXISTS room_type_tbl(
id bigint(50) auto_increment,
name varchar(500),
shortname varchar(250),
adult int,
child int,
defaultprice varchar(50),
baseprice varchar(50),
extrabedprice varchar(50),
childfare varchar(50),
minimumdeposit varchar(50),
noofrooms int,
hima_allocation int,
maxallow int,
grade int,
detail text,
ismandatory_minimum_deposit varchar(10),
isextrabed varchar(10),
issmoking varchar(10),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 51: Room Types Photos Table
$var_tbl_51 = "CREATE TABLE IF NOT EXISTS photo_room_type_tbl(
id bigint(50) auto_increment,
room_type_id int,
image varchar(500),
deletedata int default 0,
primary key(id)
)";

##table 52: Room Types Occupancy Type Rate Table
$var_tbl_52 = "CREATE TABLE IF NOT EXISTS occupancy_rate_room_type_tbl(
id bigint(50) auto_increment,
room_type_id int,
occupancy_type_id int,
price varchar(50),
deletedata int default 0,
primary key(id)
)";

##table 53: Room Types Amenities Table
$var_tbl_53 = "CREATE TABLE IF NOT EXISTS amenities_room_type_tbl(
id bigint(50) auto_increment,
room_type_id int,
amenityid int,
deletedata int default 0,
primary key(id)
)";

##table 54: Room Table
$var_tbl_54 = "CREATE TABLE IF NOT EXISTS room_tbl(
id bigint(50) auto_increment,
blockid int,
floorid int,
room_type_id int,
roomprefix varchar(50),
roomnumber varchar(50),
roomsuffix varchar(50),
extn varchar(50) default 0,
detail text,
roomstatus int default 1,
deletedata int default 0,
primary key(id)
)";

##table 55: Room Control Table
$var_tbl_55 = "CREATE TABLE IF NOT EXISTS room_control_tbl(
id bigint(50) auto_increment,
roomid int,
fromdate date,
todate date,
blockstatus varchar(50),
detail text,
roomstatus int default 1,
datelogged date,
deletedata int default 0,
primary key(id)
)";

##table 56: Corporate / Special Guest Table
$var_tbl_56 = "CREATE TABLE IF NOT EXISTS cspg_tbl(
id bigint(50) auto_increment,
name varchar(500),
email varchar(150),
mobile varchar(150),
code varchar(50),
payterm int,
xcreditlimit varchar(50),
creditlimit varchar(50),
notifylimit varchar(50),
isretainership varchar(10),
isdiscount varchar(10),
isfixeddiscount varchar(10),
discount varchar(50),
chargetype varchar(50) default 'Unknown',
datelogged date,
timelogged time,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 57: Corporate / Special Guest Main Office Table
$var_tbl_57 = "CREATE TABLE IF NOT EXISTS cspg_office_tbl(
id bigint(50) auto_increment,
cspgid int,
infoid int,
bcn varchar(500),
address1 varchar(500),
address2 varchar(500),
country varchar(150),
state varchar(150),
city varchar(150),
pincode varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 58: Corporate / Special Guest Contact Person Table
$var_tbl_58 = "CREATE TABLE IF NOT EXISTS cspg_contact_person_tbl(
id bigint(50) auto_increment,
cspgid int,
infoid int,
salutation varchar(50),
firstname varchar(150),
lastname varchar(150),
phone varchar(50),
mobile varchar(50),
fax varchar(50),
gender varchar(10),
dob varchar(50),
website varchar(150),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 59: Corporate / Special Guest Pos Package Table
$var_tbl_59 = "CREATE TABLE IF NOT EXISTS cspg_pos_package_tbl(
id bigint(50) auto_increment,
cspgid int,
posid varchar(30),
deletedata int default 0,
primary key(id)
)";

##table 60: Corporate / Special Guest Pos Expenses Table
$var_tbl_60 = "CREATE TABLE IF NOT EXISTS cspg_pos_ledger_tbl(
id bigint(50) auto_increment,
userid int,
cspgid int,
posid int,
invoiceid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 61: Corporate / Special Guest Pos Payment Table
$var_tbl_61 = "CREATE TABLE IF NOT EXISTS cspg_pos_ledger_payment_tbl(
id bigint(50) auto_increment,
cspgid int,
transaction_number varchar(50),
transaction_type varchar(50),
paymode int default 0,
cheque_number varchar(50) default 'N/A',
amount varchar(50),
update_history varchar(50),
detail text,
transaction_date date,
credit_balance varchar(50),
status varchar(50) default 'Completed',
userid int,
biller varchar(50),
counter_used int default 0,
shiftid int default 0,
datelogged date,
timelogged time,
bizday int default 0,
biller varchar(50),
isreversed int default 0,
deletedata int default 0,
primary key(id)
)";

##table 62: Agent Table
$var_tbl_62 = "CREATE TABLE IF NOT EXISTS agent_tbl(
id bigint(50) auto_increment,
name varchar(500),
email varchar(150),
mobile varchar(150),
code varchar(50),
payterm int,
creditlimit varchar(50),
notifylimit varchar(50),
pancardnumber varchar(100),
commission varchar(50),
ccommission varchar(50),
servicetax varchar(50),
datelogged date,
timelogged time,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 63: Agent Main Office Table
$var_tbl_63 = "CREATE TABLE IF NOT EXISTS agent_office_tbl(
id bigint(50) auto_increment,
cspgid int,
infoid int,
bcn varchar(500),
address1 varchar(500),
address2 varchar(500),
country varchar(150),
state varchar(150),
city varchar(150),
pincode varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 64: Agent Contact Person Table
$var_tbl_64 = "CREATE TABLE IF NOT EXISTS agent_contact_person_tbl(
id bigint(50) auto_increment,
cspgid int,
infoid int,
salutation varchar(50),
firstname varchar(150),
lastname varchar(150),
designation varchar(150),
phone varchar(50),
mobile varchar(50),
fax varchar(50),
gender varchar(10),
dob varchar(50),
website varchar(150),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 65: Agent Ledger Table
$var_tbl_65 = "CREATE TABLE IF NOT EXISTS agent_ledger_tbl(
id bigint(50) auto_increment,
userid int,
cspgid int,
posid int,
amount varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 66: Agent Payment Table
$var_tbl_66 = "CREATE TABLE IF NOT EXISTS agent_ledger_payment_tbl(
id bigint(50) auto_increment,
userid int,
cspgid int,
posid int,
ledgerid int,
amount varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 67: Agent User Table
$var_tbl_67 = "CREATE TABLE IF NOT EXISTS agent_user_tbl(
id bigint(50) auto_increment,
branchid int,
corporatenumber varchar(150),
corporatename int,
flname varchar(250),
username varchar(50),
password varchar(250),
emailaddress varchar(50),
mobile varchar(50),
role int,
status varchar(50) default 'Active',
deletedata int default 0,
datelogged date,
timelogged time,
primary key(id)
)";

##table 68: Pos Sequence Setting Table
$var_tbl_68 = "CREATE TABLE IF NOT EXISTS pos_sequence_tbl(
id bigint(50) auto_increment,
branchid int,
posid int,
prefixtext varchar(150),
startnumber int,
primary key(id)
)";

##table 69: Pos Invoice Sequence Table
$var_tbl_69 = "CREATE TABLE IF NOT EXISTS pos_invoice_sequence_tbl(
id bigint(50) auto_increment,
branchid int,
prefixtext varchar(150),
primary key(id)
)";

##table 70: Pos Order Table
$var_tbl_70 = "CREATE TABLE IF NOT EXISTS pos_order_tbl(
id bigint(50) auto_increment,
branchid int,
posid int,
primary key(id)
)";

##table 71: Notification Types Role Table
$var_tbl_71 = "CREATE TABLE IF NOT EXISTS role_notification_type_tbl(
id bigint(50) auto_increment,
notification_type_id int,
roleid int,
deletedata int default 0,
primary key(id)
)";

##table 72: Hotel Booking Sequence Table
$var_tbl_72 = "CREATE TABLE IF NOT EXISTS hotel_booking_sequence_tbl(
id bigint(50) auto_increment,
branchid int,
prefixtext varchar(150),
primary key(id)
)";

##table 73: Hotel Invoice Sequence Table
$var_tbl_73 = "CREATE TABLE IF NOT EXISTS hotel_invoice_sequence_tbl(
id bigint(50) auto_increment,
branchid int,
prefixtext varchar(150),
primary key(id)
)";

##table 74: Season Modes Legend Table
$var_tbl_74 = "CREATE TABLE IF NOT EXISTS season_mode_legend_tbl(
id bigint(50) auto_increment,
legendname varchar(250),
colorcode varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 75: Hotel Season Setup Table
$var_tbl_75 = "CREATE TABLE IF NOT EXISTS hotel_season_tbl(
id bigint(50) auto_increment,
modeid int,
startseason date,
endseason date,
status varchar(50) default 'InActive',
deletedata int default 0,
primary key(id)
)";

##table 76: Hotel Season Tariff Setup Table
$var_tbl_76 = "CREATE TABLE IF NOT EXISTS hotel_season_tariff_tbl(
id bigint(50) auto_increment,
modeid int,
room_type_id int,
ratetype varchar(50),
day varchar(50),
price varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 77: Corporate Tariff Setup Table
$var_tbl_77 = "CREATE TABLE IF NOT EXISTS corporate_tariff_tbl(
id bigint(50) auto_increment,
corporateid int,
room_type_id int,
tarifftype varchar(50),
tariffamount varchar(50),
discount varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 78: Corporate Tariff Applicable Taxes Table
$var_tbl_78 = "CREATE TABLE IF NOT EXISTS corporate_tariff_tax_tbl(
id bigint(50) auto_increment,
corporateid int,
room_type_id int,
taxid int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 79: Inclusion Table
$var_tbl_79 = "CREATE TABLE IF NOT EXISTS inclusion_tbl(
id bigint(50) auto_increment,
name varchar(250),
detail text,
price varchar(50),
posstore int,
posproduct int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 80: Package Manager Table
$var_tbl_80 = "CREATE TABLE IF NOT EXISTS package_tbl(
id bigint(50) auto_increment,
packagename varchar(500),
shortname varchar(150),
displayname varchar(250),
packagetype int,
packageforeverstatus varchar(10),
numberofnight int,
detail text,
datelogged date,
timelogged time,
userid int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 81: Package Manager Schedule Date Table
$var_tbl_81 = "CREATE TABLE IF NOT EXISTS package_schedule_date_tbl(
id bigint(50) auto_increment,
packageid int,
packagestart date,
packageend date,
status varchar(50) default 'InActive',
deletedata int default 0,
primary key(id)
)";

##table 82: Package Manager Component Table
$var_tbl_82 = "CREATE TABLE IF NOT EXISTS package_component_tbl(
id bigint(50) auto_increment,
packageid int,
packagecomponent varchar(250),
packagevendor varchar(250),
amount varchar(50),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 83: Package Manager Rooms Table
$var_tbl_83 = "CREATE TABLE IF NOT EXISTS package_room_type_tbl(
id bigint(50) auto_increment,
packageid int,
room_type_id int,
ratetype varchar(50),
amount varchar(50),
night int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 84: Package Manager Room Inclusions Table
$var_tbl_84 = "CREATE TABLE IF NOT EXISTS package_room_inclusion_tbl(
id bigint(50) auto_increment,
packageid int,
inclusion_id int,
deletedata int default 0,
primary key(id)
)";

##table 85: Other Hotel Commission Slab Table
##commission to be given base on number of rooms acquired per night
$var_tbl_85 = "CREATE TABLE IF NOT EXISTS other_hotel_cs_tbl(
id bigint(50) auto_increment,
name varchar(500),
room_night_from int,
room_night_to int,
commission varchar(50),
sequencenumber int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 86: Other Hotel Taxes Slab Table
##setup taxes for third party hotel
$var_tbl_86 = "CREATE TABLE IF NOT EXISTS other_hotel_ts_tbl(
id bigint(50) auto_increment,
bill_from int,
bill_to int,
detail text,
totaltax varchar(50),
sequencenumber int,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

$var_tbl_87 = "CREATE TABLE IF NOT EXISTS other_hotel_ts_list_tbl(
id bigint(50) auto_increment,
taxslab int,
taxid int,
taxcharges varchar(50),
deletedata int default 0,
primary key(id)
)";


##table 88: Pos Store Product History Table
$var_tbl_88 = "CREATE TABLE IF NOT EXISTS pos_store_product_history_tbl(
id bigint(50) auto_increment,
storageid int default 0,
storagetype varchar(50),
postoreid int,
categoryid int,
subcategoryid int,
itemcode varchar(50),
item varchar(500),
stockin varchar(50) default 0,
uom varchar(50),
price varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 89: House Keeping Room State Table
$var_tbl_89 = "CREATE TABLE IF NOT EXISTS housekeeping_room_state_tbl(
id bigint(50) auto_increment,
room_type int,
roomid int,
housekeeping_stateid int,
room_status_id int,
remarks text,
userid int,
startdate date default '0000-00-00',
endate date default '0000-00-00',
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 90: House Keeping Room State History Table
$var_tbl_90 = "CREATE TABLE IF NOT EXISTS housekeeping_room_state_history_tbl(
id bigint(50) auto_increment,
room_type int,
roomid int,
housekeeping_stateid int,
new_housekeeping_stateid int default 1,
room_status_id int,
remarks text,
userid int,
assignedby int default 0,
startdate date default '0000-00-00',
endate date default '0000-00-00',
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 91: Room Usage Status History Table
$var_tbl_91 = "CREATE TABLE IF NOT EXISTS room_usage_status_history_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
roomid int,
stateid int,
tempreserved varchar(10) default 'No',
tempdatereserved date default '0000-00-00',
customerid int,
userid int,
startdate date default '0000-00-00',
endate date default '0000-00-00',
noofdays int,
checkin int default 0,
checkout int default 0,
booking_type int default 0,
allow_bill_to_room varchar(10),
bill_pay_by varchar(50),
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 92: Room Usage Status Table
$var_tbl_92 = "CREATE TABLE IF NOT EXISTS room_usage_status_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
room_type_id int,
roomid int,
stateid int,
startdate date,
endate date,
noofdays int,
status varchar(50),
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 93: Guest Active Rooms Table
$var_tbl_93 = "CREATE TABLE IF NOT EXISTS guest_active_rooms_tbl(
id bigint(50) auto_increment,
roomid int,
customerid int,
allow_bill_to_room varchar(10),
bill_pay_by varchar(50),
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 94: Pos Orders Table
$var_tbl_94 = "CREATE TABLE IF NOT EXISTS pos_orders_tbl(
id bigint(50) auto_increment,
main_category int default 0,
sales_category int default 0,
sales_subcategory int default 0,
order_number varchar(30),
userid int,
counter_used int default 0,
posid int,
booking_number varchar(50),
customerid int,
itemid int,
qty int,
price varchar(30),
cost varchar(30) default 0,
amount varchar(30) default 0,
discount varchar(30) default 0,
foodtype int,
billtype int,
biller int,
tableid int,
cover varchar(30),
status varchar(30) default 'Pending',
cashier int,
waiter int,
roomid int default 0,
iscomplimentary int default 0,
shiftid int default 0,
isreserved int default 0,
reserve_date_fr date,
reserve_time_fr time,
reserve_date_to date,
reserve_time_to time,
isprinted int default 0,
isreversed int default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 95: Pos Orders - Payment Table
$var_tbl_95 = "CREATE TABLE IF NOT EXISTS pos_payment_tbl(
id bigint(50) auto_increment,
order_number varchar(30),
invoice_number varchar(30),
receipt_number varchar(30),
userid int,
counter_used int default 0,
posid int,
booking_number varchar(50),
customerid int,
discount_amount varchar(30) default 0,
sub_total varchar(30),
tax_amount varchar(30) default 0,
consumption_amount varchar(30) default 0,
service_charge_amount varchar(30) default 0,
bill_amount varchar(30),
foodtype int,
billtype int,
biller int,
tableid int,
cover varchar(30),
detail text,
status varchar(30) default 'Pending',
payment varchar(30) default 'Pending',
cashier int,
waiter int,
roomid int default 0,
iscomplimentary int default 0,
shiftid int default 0,
ispaid int default 0,
amount varchar(30),
first_amount varchar(30) default 0,
second_amount varchar(30) default 0,
media int default 0,
cheque_number varchar(30),
paydetail text,
paydate date,
paytime time,
isprinted int default 0,
isreversed int default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 96: Pos Orders - Payment History Table
$var_tbl_96 = "CREATE TABLE IF NOT EXISTS pos_payment_history_tbl(
id bigint(50) auto_increment,
posid int,
invoiceid int,
receipt_number varchar(30),
amount varchar(30),
media int default 0,
cheque_number varchar(30),
detail text,
userid int,
cashier int,
shiftid int default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 97: Guest Table
$var_tbl_97 = "CREATE TABLE IF NOT EXISTS guest_tbl(
id bigint(50) auto_increment,
primary_guest int default 0,
booking_type varchar(50),
booking_number varchar(50) default 0,
invoice_number varchar(50) default 0,
isbill_to_room varchar(10),
billing_services text,
photo varchar(100),
guest_code varchar(50) default 'P',
virtual_guest_code int default 0,
salutation int default 0,
fname varchar(50),
lname varchar(50),
gender varchar(10),
age int,
dob varchar(50),
pob varchar(50),
nationality varchar(50),
immi_status varchar(50),
allien_regno varchar(50),
employer varchar(50),
phoneno varchar(50),
mobile varchar(11),
remarks text,
emailaddress varchar(50),
address varchar(250),
city varchar(50),
state varchar(50),
zip_code varchar(50),
country varchar(50),
means_of_identification int default 0,
identification_number varchar(50),
occupation varchar(250),
period_of_stay varchar(50),
country_date_checkin varchar(20),
next_destination varchar(50),
id_issue_date varchar(20),
id_issue_place varchar(50),
current_address varchar(250),
probable_destination varchar(50),
passport_no varchar(50),
issue_date varchar(20),
expiry_date varchar(20),
issue_place varchar(50),
visa_validity varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=100";

##table 98: Automated Notification Table
$var_tbl_98 = "CREATE TABLE IF NOT EXISTS auto_notify_tbl(
id bigint(50) auto_increment,
module int,
title int,
lastrun date,
nextrun date,
status int default 0,
primary key(id)
)";

##table 99: Inbox Table
$var_tbl_99 = "CREATE TABLE IF NOT EXISTS inbox_tbl(
id bigint(50) auto_increment,
subject varchar(500),
sender int,
receiver int,
message text,
priority int,
msgtype int,
datelogged date,
timelogged time,
read_status int default 0,
bizday int default 0,
deletedata int default 0,
archivedata int default 0,
primary key(id)
)";

##table 100: Recreation Table
$var_tbl_100 = "CREATE TABLE IF NOT EXISTS recreation_member_tbl(
id bigint(50) auto_increment,
recreation_number varchar(50),
photo varchar(50),
salutation int,
firstname varchar(250),
lastname varchar(250),
othernames varchar(500),
maritalstatus varchar(50),
gender varchar(10),
dob date,
nationality varchar(250),
emailaddress varchar(50),
mobile varchar(50),
membership_type varchar(50),
iscomplimentary varchar(5),
complimentary_src int default 0,
profession varchar(250),
bodyheight varchar(20),
heightuom varchar(50),
bodyweight varchar(20),
weightuom varchar(50),
bloodgroup varchar(150),
genotype varchar(150),
officeaddress text,
officephone varchar(50),
homeaddress text,
plan int,
startdate varchar(20),
enddate varchar(20),
iscorporate varchar(5),
corporate_type varchar(500) default 0,
detail text,
workflow text,
isapproved int default 0,
datelogged date,
timelogged time,
status int default 1,
bizday int default 0,
deletedata int default 0,
archivedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 101: Recreation Sub Table
$var_tbl_101 = "CREATE TABLE IF NOT EXISTS recreation_sub_member_tbl(
id bigint(50) auto_increment,
memberid int,
listype varchar(50),
photo varchar(50),
flname varchar(250),
dob date,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table 102: Recreation Payment Table
$var_tbl_102 = "CREATE TABLE IF NOT EXISTS recreation_member_payment_tbl(
id bigint(50) auto_increment,
recreation_number varchar(50),
memberid int,
invoice_number varchar(50),
mode int,
amount varchar(50),
chequenumber varchar(250),
receipt varchar(250),
detail text,
paymentdate date,
userid int,
startdate date,
enddate date,
datelogged date,
timelogged time,
approval_status varchar(50) default 'Under Approval',
isprocess int default 0,
isreversed int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";


##table 103: Approval Settings Table
$var_tbl_103 = "CREATE TABLE IF NOT EXISTS approval_settings_tbl(
id bigint(50) auto_increment,
qc int,
level int,
approve int,
role int,
mode varchar(50) default 'nil',
extra text,
isdefault int,
defaultname varchar(150),
deletedata int default 0,
primary key(id)
)";


##table 104: Hotel Settings Table
$var_tbl_104 = "CREATE TABLE IF NOT EXISTS hotel_settings_tbl(
id bigint(50) auto_increment,
default_currency int,
financial_start_month int,
financial_start_day int,
financial_end_month int,
financial_end_day int,
late_checkout_charges_1hr varchar(10) default 0,
late_checkout_charges_2hr varchar(10) default 0,
late_checkout_charges_3hr varchar(10) default 0,
late_checkout_charges_4hr varchar(10) default 0,
late_checkout_charges_5hr varchar(10) default 0,
late_checkout_charges_6hr varchar(10) default 0,
early_checkin_charges_1hr varchar(10) default 0,
early_checkin_charges_2hr varchar(10) default 0,
early_checkin_charges_3hr varchar(10) default 0,
early_checkin_charges_4hr varchar(10) default 0,
early_checkin_charges_5hr varchar(10) default 0,
early_checkin_charges_6hr varchar(10) default 0,
country varchar(150),
state varchar(150),
city varchar(150),
area_name varchar(150),
countrycode varchar(10),
allow_past_reverse int default 0,
time_zone varchar(50),
geo_location text,
currency_decimal int,
date_format int,
time_format int,
mail_senderid varchar(50),
mail_copyid varchar(50),
guest_birthday_message text,
checkin_time_hr varchar(10),
checkin_time_min varchar(10),
checkout_time_hr varchar(10),
checkout_time_min varchar(10),
agent_commission_period int,
cancellation_commission varchar(10) default 0,
global_corporateguest_discount varchar(10) default 0,
online_minimum_deposit varchar(10) default 100,
online_booking_discount varchar(10) default 0,
allow_advance_booking varchar(10) default 0,
rack_rate varchar(10) default 'Yes',
pre_printed_layout varchar(10) default 'Yes',
allow_daywise_season_rate  varchar(10) default 'Yes',
notification_message text,
istariff_include_tax varchar(10) default 'No',
allow_inclusion_discount varchar(10) default 'Yes',
allow_extrabed_tax varchar(10) default 'Yes',
service_charge varchar(10) default 0,
vat varchar(10) default 0,
consumption_tax varchar(10) default 0,
allow_hima_quota varchar(10) default 'No',
night_audit_hr varchar(10),
night_audit_min varchar(10),
night_audit_calendar varchar(50),
night_audit_disable_users int default 1,
wakeup_call_time int default 5,
deletedata int default 0,
primary key(id)
)";

##table 105: Hotel Management SMS Table
$var_tbl_105 = "CREATE TABLE IF NOT EXISTS sms_sender_detail_tbl(
id bigint(50) auto_increment,
sms_sender varchar(250),
sms_appending_message text,
discount_numbers text,
night_audit_numbers text,
occupancy_status_numbers text,
complaint_numbers text,
hk_touchup_number int,
hk_touchup_timing int,
deletedata int default 0,
primary key(id)
)";

##table 106: Pos Food Type Timing Table
$var_tbl_106 = "CREATE TABLE IF NOT EXISTS pos_foodtype_timing_tbl(
id bigint(50) auto_increment,
foodtype int,
allow_start_time int,
allow_end_time int,
deletedata int default 0,
primary key(id)
)";

##table 107: Tds Percentage Table
$var_tbl_107 = "CREATE TABLE IF NOT EXISTS tds_percentage_tbl(
id bigint(50) auto_increment,
tds_payable_percentage varchar(10) default 0,
tds_receivable_percentage varchar(10) default 0,
coupon_expiry_days int default 0,
deletedata int default 0,
primary key(id)
)";

##table 108: Housekeeping Tag Settings Table
$var_tbl_108 = "CREATE TABLE IF NOT EXISTS housekeeping_tag_setting_tbl(
id bigint(50) auto_increment,
mark_all_checkin_rooms int default 0,
mark_all_vacant_rooms int default 0,
mark_all_checkedout_rooms int default 0,
night_audit_mark_temp_booking varchar(50) default 'No Show',
deletedata int default 0,
primary key(id)
)";

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
program_id int,
category varchar(500),
detail text,
status varchar(50) default 'Active',
isdefault varchar(10) default 'Yes',
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
itemgroupid int default 0,
categoryid int default 0,
subcategoryid int default 0,
uom int,
supplierid int,
store int,
store_type varchar(50) default 'Virtual Stores',
order_number varchar(150),
lpo varchar(50),
order_date date,
delivery_date date default '0000-00-00',
delivery_note text,
unitprice varchar(50),
qty_ordered varchar(50),
qty_received varchar(50) default 0,
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

##table 122: Guest Occupancy Detail Table
$var_tbl_122 = "CREATE TABLE IF NOT EXISTS guest_occupancy_detail_tbl(
id bigint(50) auto_increment,
booking_number varchar(50) default 0,
customerid int,
room_type_id int default 0,
roomid int default 0,
housekeeping_stateid int,
room_status_id int,
adult int default 0,
child int default 0,
isextrabed int default 0,
occupancy_type int default 0,
noofdays int,
reservation varchar(50),
holdtill date,
checkin_date date default '0000-00-00',
checkin_time time default '00:00:00',
checkout_date date default '0000-00-00',
checkout_time time default '00:00:00',
cancel_date date default '0000-00-00',
cancel_time time default '00:00:00',
checkin_byuser int default 0,
checkout_byuser int default 0,
cancel_byuser int default 0,
remarks text,
cancel_policy int default 0,
cancel_reason int default 0,
early_checkin_charges varchar(50) default 0,
late_checkout_charges varchar(50) default 0,
cancellation_charges varchar(50) default 0,
isdiscount int default 0,
datelogged date,
timelogged time,
status varchar(50),
userid int,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 123: Guest Arrival & Departure Detail Table
$var_tbl_123 = "CREATE TABLE IF NOT EXISTS guest_arrival_departure_detail_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
source_of_biz int default 0,
arrival_mode int default 0,
departure_mode int default 0,
remarks text,
deletedata int default 0,
primary key(id)
)";

##table 124: Guest Coupons Table
$var_tbl_124 = "CREATE TABLE IF NOT EXISTS guest_coupons_tbl(
id bigint(50) auto_increment,
guest_name varchar(150),
guest_contact varchar(11),
booking_number varchar(50),
invoice_number varchar(50),
coupon_code varchar(50),
coupon_type varchar(50),
coupon_amount varchar(50),
payment_mode int default 0,
expires_on date,
coupon_status varchar(50),
status int default 1,
refunds int default 0,
customerid int,
userid int,
usedby int default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 125: Booking Invoice & Payment Table
$var_tbl_125 = "CREATE TABLE IF NOT EXISTS booking_invoice_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
booking_type varchar(50),
booking_src varchar(50) default 'InPerson',
bill_type varchar(50),
bill_to int,
bill_to_g int default 0,
reservation varchar(50),
isbill_to_room varchar(10),
billing_services text,
islate_checkout varchar(10) default 'Yes',
isweekend_fares varchar(10) default 'No',
remarks text,
settled_booking int default 0,
checkin_date date,
checkin_time time,
checkout_date date,
checkout_time time,
datelogged date,
timelogged time,
ismodified varchar(10) default 'No',
booking_type_bf varchar(50) default 'Normal',
userid int default 0,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 126: Booking Invoice & Payment Table
$var_tbl_126 = "CREATE TABLE IF NOT EXISTS transaction_payment_tbl(
id bigint(50) auto_increment,
biller int default 0,
sales_point varchar(50),
sales_description text,
booking_number varchar(50),
invoice_number varchar(50),
receipt_number varchar(50),
customerid int,
transaction_type varchar(50),
update_history varchar(500),
amount varchar(50),
first_amount varchar(50),
second_amount varchar(50),
refund varchar(50) default 0,
payment_mode int,
cheque_number varchar(50),
detail text,
ispaid int default 0,
isreversed int default 0,
datelogged date,
timelogged time,
userid int,
counter_used int default 0,
shiftid int default 0,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";

##table 127: Guest Activities Table
$var_tbl_127 = "CREATE TABLE IF NOT EXISTS guest_activities_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
activities text,
app_tag varchar(50) default 'Booking',
session_tag varchar(100) default 'Guest Ledger',
remark_tag varchar(50),
customerid int,
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 128: Booking Number Sequence Table
$var_tbl_128 = "CREATE TABLE IF NOT EXISTS booking_numbers_tbl(
id bigint(50) auto_increment,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";


##table 129: Daily Invoice Charges Table
$var_tbl_129 = "CREATE TABLE IF NOT EXISTS daily_invoice_charges_tbl(
id bigint(50) auto_increment,
charge_type varchar(50),
billto int default 0,
booking_number varchar(50),
invoice_number varchar(50),
room_type_id int,
roomid int,
customerid int,
day int,
weekday varchar(20),
daydate date,
actual_room_amount varchar(50) default 0,
actual_tax_amount varchar(50) default 0,
actual_service_charge varchar(50) default 0,
actual_consumption_tax_amount varchar(50) default 0,
room_amount varchar(50),
discount_amount varchar(50) default 0,
tax_amount varchar(50) default 0,
consumption_tax_amount varchar(50) default 0,
service_charge varchar(50) default 0,
occupancy_charges varchar(50) default 0,
extrabed_charges varchar(50) default 0,
charge varchar(5),
ischarged int default 0,
bill_date date default '0000-00-00',
bill_time time default '00:00:00',
wkf int default 0,
status varchar(50),
room_status varchar(50) default 'CheckedIn',
datelogged date,
timelogged time,
userid int,
bizday int default 0,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1000";


##table 130: Guest Inclusion Table
$var_tbl_130 = "CREATE TABLE IF NOT EXISTS guest_inclusion_list_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
userid int,
roomid int,
customerid int,
inclusion_id int,
status varchar(50),
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";


##table 131: Night Audit Table
$var_tbl_131 = "CREATE TABLE IF NOT EXISTS night_audit_tbl(
id bigint(50) auto_increment,
audit_date date,
audit_time time default '00:00:00',
status varchar(50) default 'Pending',
deletedata int default 0,
unique(audit_date),
primary key(id)
)";


##table 132: Night Audit (on modules) Table
$var_tbl_132 = "CREATE TABLE IF NOT EXISTS night_audit_module_tbl(
id bigint(50) auto_increment,
audit_date date,
start_audit varchar(50) default 'Pending',
deletedata int default 0,
primary key(id)
)";


##table 133: Customer Rebate Table
$var_tbl_133 = "CREATE TABLE IF NOT EXISTS customer_rebate_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
invoice_number varchar(50),
roomid int,
balance_amount varchar(50),
status int default 0,
status_label varchar(50),
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";


##table 134: Guest Exclude Tax Table
/*id bigint(50) auto_increment,
booking_number varchar(50),
guest_id int,
vat int default 1,
service_charge int default 1,
consumption_tax int default 1,
deletedata int default 0,
primary key(id)
)";*/


##table 135: Guest Exclude Tax Table
/*$var_tbl_135 = "CREATE TABLE IF NOT EXISTS guest_room_occupancy_charges_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
roomid int,
occupancyid int,
amount varchar(50),
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";*/


##table 136: Modify Booking Remarks Table
/*$var_tbl_136 = "CREATE TABLE IF NOT EXISTS modify_booking_remarks_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
roomid int,
remarks text,
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";*/


##table 137: Workflow Names Table
$var_tbl_137 = "CREATE TABLE IF NOT EXISTS workflow_names_tbl(
id bigint(50) auto_increment,
approval_setting int,
workflow_name varchar(500),
isdefault int default 0,
userid int,
deletedata int default 0,
primary key(id)
)";

##table 138: Booking Settlement Table
/*$var_tbl_138 = "CREATE TABLE IF NOT EXISTS booking_settlement_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
booking_settlement varchar(50) default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";*/

##table 139: Approval Doc Stages Table
$var_tbl_139 = "CREATE TABLE IF NOT EXISTS doc_request_approval_stages_tbl(
id bigint(50) auto_increment,
document_ref_number varchar(50),
document_alias varchar(150),
sender int,
subject int,
message text,
priority int,
msgtype int default 0,
message_status int default 0,
qc int,
level int,
approve int,
role int,
signed int default 0,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

##table 140: Guest Ledger Table
/*$var_tbl_140 = "CREATE TABLE IF NOT EXISTS guest_ledger_tbl(
id bigint(50) auto_increment,
transaction_number varchar(50),
transaction_type varchar(50),
guest int,
biller int,
sales_point int,
sales_description text,
amount varchar(50),
balance_bfw varchar(50),
payment_mode int,
cheque_number varchar(50),
update_history varchar(500),
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";*/

##table 141: Hotel Business Day Table
$var_tbl_141 = "CREATE TABLE IF NOT EXISTS hotel_businessday_tbl(
id bigint(50) auto_increment,
day bigint(50),
startdate date,
starttime time,
enddate date default '0000-00-00',
endtime time default '00:00:00',
status int default 0,
deletedata int default 0,
unique(startdate),
primary key(id)
)";

##table 142: Hotel Weekly Tariff Setup Table
$var_tbl_142 = "CREATE TABLE IF NOT EXISTS hotel_wkly_tariff_tbl(
id bigint(50) auto_increment,
room_type_id int,
day varchar(50),
price varchar(50),
status varchar(50) default 'InActive',
deletedata int default 0,
primary key(id)
)";

##table 143: New Corporate Tariff Setup Table
$var_tbl_143 = "CREATE TABLE IF NOT EXISTS nw_corporate_tariff_tbl(
id bigint(50) auto_increment,
corporateid int,
modeid int,
room_type_id int,
ratetype varchar(50),
day varchar(50),
price varchar(50),
status varchar(50) default 'InActive',
deletedata int default 0,
primary key(id)
)";

##table 144: Booking Invoice Numbers Table
$var_tbl_144 = "CREATE TABLE IF NOT EXISTS invoice_numbers_tbl(
id bigint(50) auto_increment,
status int,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";

##table 145: Material Control Tax Table
$var_tbl_145 = "CREATE TABLE IF NOT EXISTS mc_tax_tbl(
id bigint(50) auto_increment,
taxname varchar(250),
taxcharge varchar(50),
detail text,
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
)";

##table 146: Preset SMS Table
$var_tbl_146 = "CREATE TABLE IF NOT EXISTS presetsms_tbl(
id bigint(50) auto_increment,
msg text,
deletedata int default 0,
primary key(id)
)";

##table 150: Job Approval Table
$var_tbl_150 = "CREATE TABLE IF NOT EXISTS job_approval_tbl(
id bigint(50) auto_increment,
job_level int,
subject varchar(250),
user_one int,
approval_one int default 0,
comment_one varchar(500),
user_two int,
approval_two int default 0,
comment_two varchar(500),
user_three int,
approval_three int default 0,
comment_three varchar(500),
user_four int,
approval_four int default 0,
comment_four varchar(500),
user_five int,
approval_five int default 0,
comment_five varchar(500),
approval_type varchar(50),
approval_status varchar(50) default 'Pending',
deletedata int default 0,
primary key(id)
)";

##table: Item Request Table
$var_tbl_300 = "CREATE TABLE IF NOT EXISTS item_request_tbl(
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
$var_tbl_301 = "CREATE TABLE IF NOT EXISTS iou_fund_tbl(
id bigint(50) auto_increment,
iou_no varchar(50),
pr_no varchar(50),
pr_type varchar(50),
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
unique(iou_no),
unique(pr_no),
primary key(id)
)";

##table: pay master Table
$var_tbl_302 = "CREATE TABLE IF NOT EXISTS paymaster_tbl(
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

##table: Sequencial Table
$var_tbl_303 = "CREATE TABLE IF NOT EXISTS sequencial_tbl(
id bigint(50) auto_increment,
app_name varchar(50),
start_number varchar(50),
deletedata int default 0,
unique(app_name),
primary key(id)
)";

##table: transfer Table
$var_tbl_304 = "CREATE TABLE IF NOT EXISTS stock_transfer_tbl(
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

##table: account receivable Table
$var_tbl_305 = "CREATE TABLE IF NOT EXISTS account_receivable_tbl(
id bigint(50) auto_increment,
account varchar(250),
shift_start_date date,
shift_end_date date,
shift_start_time time,
shift_end_time time,
shift varchar(50),
counterid int,
shiftid int,
userid int,
paid_amount varchar(50),
actual_amount varchar(50) default 0,
diff_amount varchar(50) default 0,
remark text,
status varchar(50) default 'Pending',
receivedby int default 0,
account_type varchar(50) default 'Credit',
account_src varchar(50),
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: account fund Table
$var_tbl_306 = "CREATE TABLE IF NOT EXISTS account_fund_tbl(
id bigint(50) auto_increment,
account_type varchar(50),
amount varchar(50),
withdraws varchar(50) default 0,
balance varchar(50),
status varchar(50) default 'Active',
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: city ledger Table
$var_tbl_307 = "CREATE TABLE IF NOT EXISTS city_ledger_tbl(
id bigint(50) auto_increment,
forcounter varchar(50),
amount varchar(50),
paid_amount varchar(50) default 0,
chargeto int,
detail varchar(500),
userid int,
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: iou expense Table
$var_tbl_308 = "CREATE TABLE IF NOT EXISTS iou_expense_tbl(
id bigint(50) auto_increment,
iou_no varchar(50),
iou_type varchar(50),
expense_type varchar(50),
departmentid int,
receivedby int,
detail varchar(500),
amount varchar(50),
iou_date varchar(50),
userid int,
status varchar(50) default 'Pending',
isprocess int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: modify booking Table
$var_tbl_309 = "CREATE TABLE IF NOT EXISTS modify_booking_tbl(
id bigint(50) auto_increment,
booking_number varchar(50),
customerid int,
room_type_id int,
roomid int,
current_type varchar(50),
new_type varchar(50),
remark varchar(500),
bookedby int,
changedby int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

##table: Manual Rebate Table
$var_tbl_310 = "CREATE TABLE IF NOT EXISTS manual_rebate_tbl(
id bigint(50) auto_increment,
rebate_no varchar(50),
rebate_type varchar(50),
booking_number varchar(50),
guest_name varchar(150),
amount varchar(50),
remark text,
transaction_date date,
status varchar(30) default 'Pending',
approval_status varchar(50) default 'Reviewing',
userid int,
datelogged date,
timelogged time,
bizday int default 0,
deletedata int default 0,
primary key(id)
)";

/*##table: Departmental Item Table
$var_tbl_311 = "CREATE TABLE IF NOT EXISTS pos_store_product_tbl(
id bigint(50) auto_increment,
storageid int default 0,
storagetype varchar(50),
postoreid int,
categoryid int,
subcategoryid int,
itemcode varchar(50),
item varchar(500),
stockin varchar(50) default 0,
uom varchar(50),
price varchar(50),
stockout varchar(50) default 0,
balance varchar(50) default 0,
detail text,
isfeature varchar(10),
isstaff varchar(10),
status varchar(50) default 'Active',
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";*/

##table: Employee Payment Table
$var_tbl_312 = "CREATE TABLE IF NOT EXISTS employee_payment_tbl(
id bigint(50) auto_increment,
receipt_number varchar(50),
transaction_date date,
staff int,
departmentid int,
bill_type varchar(150),
amount varchar(50),
payment_mode int,
detail text,
userid int,
status varchar(50) default 'Active',
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
) AUTO_INCREMENT=1001";


##table: Outlet-to-outlet transfer table
$var_tbl_313 = "CREATE TABLE IF NOT EXISTS outlet_transfer_tbl(
id bigint(50) auto_increment,
queryrow int,
frompos int,
topos int,
itemid int,
qty_available varchar(50),
qty_required varchar(50),
requestby int,
acknowledgeby int default 0,
tr_status varchar(50) default 'Pending',
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";


##table: Treasury Fund Transaction History table
$var_tbl_314 = "CREATE TABLE IF NOT EXISTS treasury_fund_history_tbl(
id bigint(50) auto_increment,
fund_type varchar(50),
amount varchar(50),
userid int,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";


##table: Walk-in Guest table
$var_tbl_315 = "CREATE TABLE IF NOT EXISTS walkin_guest_tbl(
id bigint(50) auto_increment,
fname varchar(50),
lname varchar(50),
userid int default 0,
datelogged date,
timelogged time,
deletedata int default 0,
primary key(id)
)";

?>