<?php $smdl = "administration"; $logs = $_GET['logs']; ?>

<div class="block-element bottom-pull-15 bottom-push-20">
	<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Make changes to hotel settings below for your business operation
</div>

<?php

	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		$general_setting = array("default_currency"=>$_POST['fieldset1'],"financial_start_month"=>$_POST['fieldset2'],"financial_start_day"=>$_POST['fieldset3'],"financial_end_month"=>$_POST['fieldset4'],"financial_end_day"=>$_POST['fieldset5'],"late_checkout_charges_1hr"=>$_POST['fieldset7'],"late_checkout_charges_2hr"=>$_POST['fieldset9'],"late_checkout_charges_3hr"=>$_POST['fieldset11'],"late_checkout_charges_4hr"=>$_POST['fieldset13'],"late_checkout_charges_5hr"=>$_POST['fieldset15'],"late_checkout_charges_6hr"=>$_POST['fieldset17'],"early_checkin_charges_1hr"=>$_POST['fieldset19'],"early_checkin_charges_2hr"=>$_POST['fieldset21'],"early_checkin_charges_3hr"=>$_POST['fieldset23'],"early_checkin_charges_4hr"=>$_POST['fieldset25'],"early_checkin_charges_5hr"=>$_POST['fieldset27'],"early_checkin_charges_6hr"=>$_POST['fieldset29'],"country"=>$_POST['fieldset30'],"state"=>$_POST['fieldset31'],"city"=>$_POST['fieldset32'],"area_name"=>$_POST['fieldset33'],"countrycode"=>$_POST['fieldset34'],"allow_past_reverse"=>$_POST['fieldset35'],"time_zone"=>$_POST['fieldset36'],"geo_location"=>$_POST['fieldset37'],"currency_decimal"=>$_POST['fieldset38'],"date_format"=>$_POST['fieldset39'],"time_format"=>$_POST['fieldset40'],"mail_senderid"=>$_POST['fieldset41'],"mail_copyid"=>$_POST['fieldset42'],"guest_birthday_message"=>$_POST['fieldset43'],"checkin_time_hr"=>$_POST['fieldset44'],"checkin_time_min"=>$_POST['fieldset45'],"checkout_time_hr"=>$_POST['fieldset46'],"checkout_time_min"=>$_POST['fieldset47'],"agent_commission_period"=>$_POST['fieldset48'],"cancellation_commission"=>$_POST['fieldset49'],"global_corporateguest_discount"=>$_POST['fieldset50'],"online_minimum_deposit"=>$_POST['fieldset51'],"online_booking_discount"=>$_POST['fieldset52'],"allow_advance_booking"=>$_POST['fieldset53'],"rack_rate"=>$_POST['fieldset54'],"pre_printed_layout"=>$_POST['fieldset55'],"allow_daywise_season_rate"=>$_POST['fieldset56'],"notification_message"=>$_POST['fieldset57'],"istariff_include_tax"=>$_POST['fieldset58'],"allow_inclusion_discount"=>$_POST['fieldset59'],"allow_extrabed_tax"=>$_POST['fieldset60'],"service_charge"=>$_POST['fieldset61'],"vat"=>$_POST['fieldset62'],"allow_hima_quota"=>$_POST['fieldset63'],"night_audit_hr"=>$_POST['fieldset64'],"night_audit_min"=>$_POST['fieldset65'],"night_audit_calendar"=>$_POST['fieldset66'],"night_audit_disable_users"=>$_POST['fieldset67'],"wakeup_call_time"=>$_POST['fieldset68'],"consumption_tax"=>$_POST['fieldset90']);

		$gs_query = array("id"=>1);
		mysqli_data_update($tbL109,$general_setting,$gs_query);

		#----------------------------------------------------------------------------------------------------------------------------------------------

		$sms_sender_setting = array("sms_sender"=>$_POST['fieldset69'],"sms_appending_message"=>$_POST['fieldset70'],"discount_numbers"=>$_POST['fieldset71'],"night_audit_numbers"=>$_POST['fieldset72'],"occupancy_status_numbers"=>$_POST['fieldset73'],"complaint_numbers"=>$_POST['fieldset74'],"hk_touchup_number"=>$_POST['fieldset75'],"hk_touchup_timing"=>$_POST['fieldset76']);

		$ss_query = array("id"=>1);
		$chk_ss_exist = mysqli_data_fetch($tbL110,'id',$ss_query,'noarray');
		
		if(isset($chk_ss_exist[0]) && $chk_ss_exist[0] >= 1) {
			mysqli_data_update($tbL110,$sms_sender_setting,$ss_query);
		} else {
			mysqli_data_insert($tbL110,$sms_sender_setting,'');
		}

		
		#----------------------------------------------------------------------------------------------------------------------------------------------

		$breakfast_setting = array("foodtype"=>1,"allow_start_time"=>$_POST['fieldset77'],"allow_end_time"=>$_POST['fieldset78']);
		$bks_query = array("foodtype"=>1);
		
		$chk_bks_exist = mysqli_data_fetch($tbL111,'foodtype',$bks_query,'noarray');

		if(isset($chk_bks_exist[0]) && $chk_bks_exist[0] == 1) { mysqli_data_update($tbL111,$breakfast_setting,$bks_query); }
		else { mysqli_data_insert($tbL111,$breakfast_setting,''); }

		$lunch_setting = array("foodtype"=>2,"allow_start_time"=>$_POST['fieldset79'],"allow_end_time"=>$_POST['fieldset80']);
		$lhs_query = array("foodtype"=>2);
		
		$chk_lhs_exist = mysqli_data_fetch($tbL111,'foodtype',$lhs_query,'noarray');

		if(isset($chk_lhs_exist[0]) && $chk_lhs_exist[0] == 2) { mysqli_data_update($tbL111,$lunch_setting,$lhs_query); }
		else { mysqli_data_insert($tbL111,$lunch_setting,''); }

		$dinner_setting = array("foodtype"=>3,"allow_start_time"=>$_POST['fieldset81'],"allow_end_time"=>$_POST['fieldset82']);
		$dns_query = array("foodtype"=>3);
		
		$chk_dns_exist = mysqli_data_fetch($tbL111,'foodtype',$dns_query,'noarray');

		if(isset($chk_dns_exist[0]) && $chk_dns_exist[0] == 3) { mysqli_data_update($tbL111,$dinner_setting,$dns_query); }
		else { mysqli_data_insert($tbL111,$dinner_setting,''); }

		#----------------------------------------------------------------------------------------------------------------------------------------------

		$tds_setting = array("tds_payable_percentage"=>$_POST['fieldset83'],"tds_receivable_percentage"=>$_POST['fieldset84'],"coupon_expiry_days"=>$_POST['fieldset85']);
		$tds_query = array("id"=>1);
		
		$chk_tds_exist = mysqli_data_fetch($tbL112,'id',$tds_query,'noarray');
		
		if(isset($chk_tds_exist[0]) && $chk_tds_exist[0] >= 1) {
			mysqli_data_update($tbL112,$tds_setting,$tds_query);
		} else {
			mysqli_data_insert($tbL112,$tds_setting,'');
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------

		$housekeeping_setting = array("mark_all_checkin_rooms"=>$_POST['fieldset86'],"mark_all_vacant_rooms"=>$_POST['fieldset87'],"mark_all_checkedout_rooms"=>$_POST['fieldset88'],"night_audit_mark_temp_booking"=>$_POST['fieldset89']);
		$hks_query = array("id"=>1);
		
		$chk_hks_exist = mysqli_data_fetch($tbL113,'id',$hks_query,'noarray');
		
		if(isset($chk_hks_exist[0]) && $chk_hks_exist[0] >= 1) {
			mysqli_data_update($tbL113,$housekeeping_setting,$hks_query);
		} else {
			mysqli_data_insert($tbL113,$housekeeping_setting,'');
		}

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Hotel settings were updated successfully";

		$islogfile = 1;
		$logfile_msg = "Hotel settings recently updated by this user";

	}

	#-----------------------------------------------------------------------------------------------------------------

	//check for data
	mysqli_data_check($tbL109,'(*)','');
	$isdata = $numOfrows;

	if($isdata == 0) {
		createDatabasetable($var_tbl_104); //create a table for this post
		createDatabasetable($var_tbl_105); //create a table for this post
		createDatabasetable($var_tbl_106); //create a table for this post
		createDatabasetable($var_tbl_107); //create a table for this post
		createDatabasetable($var_tbl_108); //create a table for this post

		$dataproperty = array("country"=>"Nigeria","state"=>_STATE,"city"=>_CITY,"area_name"=>_CITY,"countrycode"=>234);
		mysqli_data_insert($tbL109,$dataproperty,'');
	}
	
	$get_default_currency = idget_data($tbL109,1,'default_currency');
	$default_currency_name = idget_data($tbL26,$get_default_currency,'currencyname');

	$get_financial_start_month = idget_data($tbL109,1,'financial_start_month');
	$financial_start_month_name = arrayget_key($full_months_name,$get_financial_start_month);
	$get_financial_start_day = idget_data($tbL109,1,'financial_start_day');

	$get_financial_end_month = idget_data($tbL109,1,'financial_end_month');
	$financial_end_month_name = arrayget_key($full_months_name,$get_financial_end_month);
	$get_financial_end_day = idget_data($tbL109,1,'financial_end_day');

	$get_late_checkout_charges_1hr = idget_data($tbL109,1,'late_checkout_charges_1hr');
	$get_late_checkout_charges_2hr = idget_data($tbL109,1,'late_checkout_charges_2hr');
	$get_late_checkout_charges_3hr = idget_data($tbL109,1,'late_checkout_charges_3hr');
	$get_late_checkout_charges_4hr = idget_data($tbL109,1,'late_checkout_charges_4hr');
	$get_late_checkout_charges_5hr = idget_data($tbL109,1,'late_checkout_charges_5hr');
	$get_late_checkout_charges_6hr = idget_data($tbL109,1,'late_checkout_charges_6hr');

	$get_early_checkin_charges_1hr = idget_data($tbL109,1,'early_checkin_charges_1hr');
	$get_early_checkin_charges_2hr = idget_data($tbL109,1,'early_checkin_charges_2hr');
	$get_early_checkin_charges_3hr = idget_data($tbL109,1,'early_checkin_charges_3hr');
	$get_early_checkin_charges_4hr = idget_data($tbL109,1,'early_checkin_charges_4hr');
	$get_early_checkin_charges_5hr = idget_data($tbL109,1,'early_checkin_charges_5hr');
	$get_early_checkin_charges_6hr = idget_data($tbL109,1,'early_checkin_charges_6hr');

	$get_country = idget_data($tbL109,1,'country');
	$get_state = idget_data($tbL109,1,'state');
	$get_city = idget_data($tbL109,1,'city');
	$get_region = idget_data($tbL109,1,'area_name');
	$get_countrycode = idget_data($tbL109,1,'countrycode');

	$get_past_reverse_day = idget_data($tbL109,1,'allow_past_reverse');
	$get_currency_decimal = idget_data($tbL109,1,'currency_decimal');

	$get_date_format = idget_data($tbL109,1,'date_format');
	$date_format_name = arrayget_key($date_format,$get_date_format);

	$get_time_format = idget_data($tbL109,1,'time_format');
	$time_format_name = arrayget_key($time_format,$get_time_format);

	$get_mail_senderid = idget_data($tbL109,1,'mail_senderid');
	$get_mail_copyid = idget_data($tbL109,1,'mail_copyid');
	$get_guest_birthday_message = idget_data($tbL109,1,'guest_birthday_message');

	$get_checkin_time_hr = idget_data($tbL109,1,'checkin_time_hr');
	$checkin_time_hr_name = arrayget_key($guest_checkinout_timing_hr,$get_checkin_time_hr);
	$get_checkin_time_min = idget_data($tbL109,1,'checkin_time_min');
	$checkin_time_min_name = arrayget_key($guest_checkinout_timing_min,$get_checkin_time_min);

	$get_checkout_time_hr = idget_data($tbL109,1,'checkout_time_hr');
	$checkout_time_hr_name = arrayget_key($guest_checkinout_timing_hr,$get_checkout_time_hr);
	$get_checkout_time_min = idget_data($tbL109,1,'checkout_time_min');
	$checkout_time_min_name = arrayget_key($guest_checkinout_timing_min,$get_checkout_time_min);

	$get_agent_commission_period = idget_data($tbL109,1,'agent_commission_period');
	$agent_commission_period_name = arrayget_key($agent_commission_period,$get_agent_commission_period);

	$get_cancellation_commission = idget_data($tbL109,1,'cancellation_commission');
	$get_global_corporateguest_discount = idget_data($tbL109,1,'global_corporateguest_discount');
	$get_online_minimum_deposit = idget_data($tbL109,1,'online_minimum_deposit');
	$get_online_booking_discount = idget_data($tbL109,1,'online_booking_discount');
	$get_allow_advance_booking = idget_data($tbL109,1,'allow_advance_booking');
	$get_rack_rate = idget_data($tbL109,1,'rack_rate');
	$get_pre_printed_layout = idget_data($tbL109,1,'pre_printed_layout');
	$get_allow_daywise_season_rate = idget_data($tbL109,1,'allow_daywise_season_rate');
	$get_notification_message = idget_data($tbL109,1,'notification_message');

	$get_istariff_include_tax = idget_data($tbL109,1,'istariff_include_tax');
	$get_allow_inclusion_discount = idget_data($tbL109,1,'allow_inclusion_discount');
	$get_allow_extrabed_tax = idget_data($tbL109,1,'allow_extrabed_tax');
	$get_service_charge = idget_data($tbL109,1,'service_charge');
	$get_vat = idget_data($tbL109,1,'vat');
	$get_consumption_t = idget_data($tbL109,1,'consumption_tax');
	$get_allow_hima_quota = idget_data($tbL109,1,'allow_hima_quota');

	$get_night_audit_hr = idget_data($tbL109,1,'night_audit_hr');
	$get_night_audit_min = idget_data($tbL109,1,'night_audit_min');
	$get_night_audit_calendar = idget_data($tbL109,1,'night_audit_calendar');
	$night_audit_calendar_type = arrayget_key($night_audit_calendar_date,$get_night_audit_calendar);

	$get_night_audit_disable_users = idget_data($tbL109,1,'night_audit_disable_users');
	$get_wakeup_call_time = idget_data($tbL109,1,'wakeup_call_time');

	$active_currency = select_dt_fetch('deletedata',0,$tbL26,'id','currencyname');
	$listofmonths = arrayset_form($full_months_name,'select');
	$listofdays = arrayset_single_form($number_of_days,'select');
	$listdateformat = arrayset_form($date_format,'select');
	$listtimeformat = arrayset_form($time_format,'select');
	$listguestcheckinouttiminghr = arrayset_form($guest_checkinout_timing_hr,'select');
	$listguestcheckinouttimingmin = arrayset_form($guest_checkinout_timing_min,'select');
	$listagentcommissionperiod = arrayset_form($agent_commission_period,'select');

	$listnightaudithr = arrayset_single_form($night_audit_timing_24hr,'select');
	$listnightauditmin = arrayset_single_form($night_audit_timing_min,'select');
	$listnightauditcalendardate = arrayset_form($night_audit_calendar_date,'select');
	$listnightauditalerttime = arrayset_single_form($night_audit_start_alert_time,'select');
	$listwakeupcalltime = arrayset_single_form($wakeup_call_time,'select');

	#------------------------------------------------------------------------------------------------------------

	$get_hotel_sms_sender = idget_data($tbL110,1,'sms_sender');
	$get_sms_appending_message = idget_data($tbL110,1,'sms_appending_message');
	$get_discount_numbers = idget_data($tbL110,1,'discount_numbers');
	$get_night_audit_numbers = idget_data($tbL110,1,'night_audit_numbers');
	$get_occupancy_status_numbers = idget_data($tbL110,1,'occupancy_status_numbers');
	$get_complaint_numbers = idget_data($tbL110,1,'complaint_numbers');

	$get_hk_touchup_number = idget_data($tbL110,1,'hk_touchup_number');
	$get_hk_touchup_timing = idget_data($tbL110,1,'hk_touchup_timing');
	$hk_touchup_timing_name = arrayget_key($timing_12hr,$get_hk_touchup_timing);

	$listtouchupnumber = arrayset_single_form($hk_touchup_number,'select');
	$listtiming12hr = arrayset_form($timing_12hr,'select');

	#------------------------------------------------------------------------------------------------------------

	$get_breakfast_start_time = idget_data($tbL111,1,'allow_start_time');
	$get_breakfast_end_time = idget_data($tbL111,1,'allow_end_time');
	$breakfast_start_time_name = arrayget_key($timing_12hr,$get_breakfast_start_time);
	$breakfast_end_time_name = arrayget_key($timing_12hr,$get_breakfast_end_time);

	$get_lunch_start_time = idget_data($tbL111,2,'allow_start_time');
	$get_lunch_end_time = idget_data($tbL111,2,'allow_end_time');
	$lunch_start_time_name = arrayget_key($timing_12hr,$get_lunch_start_time);
	$lunch_end_time_name = arrayget_key($timing_12hr,$get_lunch_end_time);

	$get_dinner_start_time = idget_data($tbL111,3,'allow_start_time');
	$get_dinner_end_time = idget_data($tbL111,3,'allow_end_time');
	$dinner_start_time_name = arrayget_key($timing_12hr,$get_dinner_start_time);
	$dinner_end_time_name = arrayget_key($timing_12hr,$get_dinner_end_time);

	#------------------------------------------------------------------------------------------------------------

	$get_payable_percentage = idget_data($tbL112,1,'tds_payable_percentage');
	$get_receivable_percentage = idget_data($tbL112,1,'tds_receivable_percentage');
	$get_coupon_expiry_days = idget_data($tbL112,1,'coupon_expiry_days');

	#------------------------------------------------------------------------------------------------------------

	$get_mark_all_checkin_rooms = idget_data($tbL113,1,'mark_all_checkin_rooms');
	$get_mark_all_vacant_rooms = idget_data($tbL113,1,'mark_all_vacant_rooms');
	$get_mark_all_checkedout_rooms = idget_data($tbL113,1,'mark_all_checkedout_rooms');
	$get_night_audit_mark_temp_booking = idget_data($tbL113,1,'night_audit_mark_temp_booking');

	$touchup = idget_data($tbL36,$get_mark_all_checkin_rooms,'legendname');
	$clean = idget_data($tbL36,$get_mark_all_vacant_rooms,'legendname');
	$dirty = idget_data($tbL36,$get_mark_all_checkedout_rooms,'legendname');

	$housekeepinglegends = select_dt_fetch('deletedata',0,$tbL36,'id','legendname');

?>
	<div class="block-element bottom-push-30" align="center">
		<div class="nc-width-80">
			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">General Settings <small class="float-right">+</small></h3><br>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Default Currency</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset1" id="fieldset1" required="required">
								<?php if(isset($get_default_currency) && !empty($get_default_currency)) { ?><option value="<?php echo $get_default_currency; ?>" selected="selected"><?php echo $default_currency_name; ?></option><?php }
								else { ?><option value="" selected="selected"></option><?php } ?>
								<?php echo $active_currency; ?>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Financial Year Start Date</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-80 right-pull-7">
								<select name="fieldset2" id="fieldset2" required="required">
									<?php if(isset($get_financial_start_month) && !empty($get_financial_start_month)) { ?><option value="<?php echo $get_financial_start_month; ?>" selected="selected"><?php echo $financial_start_month_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listofmonths; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-20">
								<select name="fieldset3" id="fieldset3" required="required">
									<?php if(isset($get_financial_start_day) && !empty($get_financial_start_day)) { ?><option value="<?php echo $get_financial_start_day; ?>" selected="selected"><?php echo $get_financial_start_day; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listofdays; ?>
								</select>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-20">
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt">
							<h4 class="large alignlt">Financial Year End Date</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-80 right-pull-7">
								<select name="fieldset4" id="fieldset4" required="required">
									<?php if(isset($get_financial_end_month) && !empty($get_financial_end_month)) { ?><option value="<?php echo $get_financial_end_month; ?>" selected="selected"><?php echo $financial_end_month_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listofmonths; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-20">
								<select name="fieldset5" id="fieldset5" required="required">
									<?php if(isset($get_financial_end_day) && !empty($get_financial_end_day)) { ?><option value="<?php echo $get_financial_end_day; ?>" selected="selected"><?php echo $get_financial_end_day; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listofdays; ?>
								</select>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<h4 class="large alignlt">Apply Late Check-out Charges</h4>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset6" id="fieldset6" required="required">
										<option value="1" selected="selected">1</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hr</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset7" id="fieldset7" value="<?php echo $get_late_checkout_charges_1hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset8" id="fieldset8" required="required">
										<option value="2" selected="selected">2</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset9" id="fieldset9" value="<?php echo $get_late_checkout_charges_2hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset10" id="fieldset10" required="required">
										<option value="3" selected="selected">3</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset11" id="fieldset11" value="<?php echo $get_late_checkout_charges_3hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset12" id="fieldset12" required="required">
										<option value="4" selected="selected">4</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset13" id="fieldset13" value="<?php echo $get_late_checkout_charges_4hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset14" id="fieldset14" required="required">
										<option value="5" selected="selected">5</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset15" id="fieldset15" value="<?php echo $get_late_checkout_charges_5hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset16" id="fieldset16" required="required">
										<option value="6" selected="selected">6</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset17" id="fieldset17" value="<?php echo $get_late_checkout_charges_6hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<h4 class="large alignlt">Apply Early Check-in Charges</h4>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset18" id="fieldset18" required="required">
										<option value="1" selected="selected">1</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hr</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset19" id="fieldset19" value="<?php echo $get_early_checkin_charges_1hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset20" id="fieldset20" required="required">
										<option value="2" selected="selected">2</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset21" id="fieldset21" value="<?php echo $get_early_checkin_charges_2hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset22" id="fieldset22" required="required">
										<option value="3" selected="selected">3</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset23" id="fieldset23" value="<?php echo $get_early_checkin_charges_3hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset24" id="fieldset24" required="required">
										<option value="4" selected="selected">4</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset25" id="fieldset25" value="<?php echo $get_early_checkin_charges_4hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset26" id="fieldset26" required="required">
										<option value="5" selected="selected">5</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset27" id="fieldset27" value="<?php echo $get_early_checkin_charges_5hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-40 ft-sml-size top-pull-7 alignlt bottom-push-7">
							&nbsp;
						</span>
						<span class="ln-display-box float-right nc-width-55 ft-sml-size bottom-push-7">
							<div class="ln-display-box float-left nc-width-50 right-pull-7">
								<span class="ln-display-box float-left top-pull-10 right-pull-5">Upto</span>
								<span class="ln-display-box float-left nc-width-50">
									<select name="fieldset28" id="fieldset28" required="required">
										<option value="6" selected="selected">6</option>
									</select>
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">Hrs</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<span class="ln-display-box float-left nc-width-50">
									<input type="text" name="fieldset29" id="fieldset29" value="<?php echo $get_early_checkin_charges_6hr; ?>">
								</span>
								<span class="ln-display-box float-left top-pull-10 left-pull-5">%</span>
								<span class="block-element new-line-space"></span>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Country</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset30" id="fieldset30" required="required">
								<option value="<?php echo $get_country; ?>" selected="selected"><?php echo $get_country; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">State</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset31" id="fieldset31" required="required">
								<option value="<?php echo $get_state; ?>" selected="selected"><?php echo $get_state; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">City</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset32" id="fieldset32" required="required">
								<option value="<?php echo $get_city; ?>" selected="selected"><?php echo $get_city; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Area Name</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset33" id="fieldset33" required="required">
								<option value="<?php echo $get_region; ?>" selected="selected"><?php echo $get_region; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Country Code</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset34" id="fieldset34" required="required">
								<option value="<?php echo $get_countrycode; ?>" selected="selected">+<?php echo $get_countrycode; ?></option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Past Reverse for</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-60 right-push-15">
								<input type="text" name="fieldset35" id="fieldset35" value="<?php echo $get_past_reverse_day; ?>">
							</div>
							<div class="ln-display-box float-left nc-width-30 top-pull-10 alignlt ft-xsml-size">days</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Time Zone</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset36" id="fieldset36">
								<option value="Africa/Algier">Africa/Algier</option>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-30 alignlt">
							<h4 class="large alignlt">Geo Location</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset37" id="fieldset37"></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Currency Decimal</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-15">
								<input type="text" name="fieldset38" id="fieldset38" value="<?php if(isset($get_currency_decimal) && !empty($get_currency_decimal)) { echo $get_currency_decimal; } ?>" pattern="\d*" maxlength="2" minlength="0" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">Decimal Places</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Date Format</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset39" id="fieldset39" required="required">
								<?php if(isset($date_format_name) && !empty($date_format_name)) { ?><option value="<?php echo $get_date_format; ?>" selected="selected"><?php echo $date_format_name; ?></option><?php }
								else { ?><option value="" selected="selected"></option><?php } ?>

								<?php echo $listdateformat; ?>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Time Format</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset40" id="fieldset40" required="required">
								<?php if(isset($time_format_name) && !empty($time_format_name)) { ?><option value="<?php echo $get_time_format; ?>" selected="selected"><?php echo $time_format_name; ?></option><?php }
								else { ?><option value="" selected="selected"></option><?php } ?>

								<?php echo $listtimeformat; ?>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Sent by Mail</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<input type="text" name="fieldset41" id="fieldset41" value="<?php if(isset($get_mail_senderid) && !empty($get_mail_senderid)) { echo $get_mail_senderid; } ?>" required="required">
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">Sent CC Mail</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<input type="text" name="fieldset42" id="fieldset42" value="<?php if(isset($get_mail_copyid) && !empty($get_mail_copyid)) { echo $get_mail_copyid; } ?>" required="required">
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-30 alignlt">
							<h4 class="large alignlt">Guest Birthday Message</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset43" id="fieldset43"><?php if(isset($get_guest_birthday_message) && !empty($get_guest_birthday_message)) { echo $get_guest_birthday_message; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="top-push-10 bottom-push-10">
						<div class="ln-display-box float-left nc-width-40">
							&nbsp;
						</div>
						<div class="ln-display-box float-right nc-width-55">
							<span class="ln-display-box float-left nc-width-50 alignlt right-pull-10">
								<h4 class="large alignlt">Check in Time</h4>
								<div class="ln-display-box float-left nc-width-70 right-pull-7 top-push-10">
									<select name="fieldset44" id="fieldset44" required="required">
										<?php if(isset($checkin_time_hr_name) && !empty($checkin_time_hr_name)) { ?><option value="<?php echo $get_checkin_time_hr; ?>" selected="selected"><?php echo $checkin_time_hr_name; ?></option><?php }
										else { ?><option value="" selected="selected"></option><?php } ?>

										<?php echo $listguestcheckinouttiminghr; ?>
									</select>
								</div>
								<div class="ln-display-box float-left nc-width-30 top-push-10">
									<select name="fieldset45" id="fieldset45" required="required">
										<?php if(isset($checkin_time_min_name) && !empty($checkin_time_min_name)) { ?><option value="<?php echo $get_checkin_time_min; ?>" selected="selected"><?php echo $checkin_time_min_name; ?></option><?php }
										else { ?><option value="" selected="selected"></option><?php } ?>

										<?php echo $listguestcheckinouttimingmin; ?>
									</select>
								</div>
								<div class="block-element new-line-space">
								</div>
							</span>
							<span class="ln-display-box float-left nc-width-50 alignlt left-pull-10">
								<h4 class="large alignlt">Check out Time</h4>
								<div class="ln-display-box float-left nc-width-70 right-pull-7 top-push-10">
									<select name="fieldset46" id="fieldset46" required="required">
										<?php if(isset($checkout_time_hr_name) && !empty($checkout_time_hr_name)) { ?><option value="<?php echo $get_checkout_time_hr; ?>" selected="selected"><?php echo $checkout_time_hr_name; ?></option><?php }
										else { ?><option value="" selected="selected"></option><?php } ?>

										<?php echo $listguestcheckinouttiminghr; ?>
									</select>
								</div>
								<div class="ln-display-box float-left nc-width-30 top-push-10">
									<select name="fieldset47" id="fieldset47" required="required">
										<?php if(isset($checkout_time_min_name) && !empty($checkout_time_min_name)) { ?><option value="<?php echo $get_checkout_time_min; ?>" selected="selected"><?php echo $checkout_time_min_name; ?></option><?php }
										else { ?><option value="" selected="selected"></option><?php } ?>

										<?php echo $listguestcheckinouttimingmin; ?>
									</select>
								</div>
								<div class="block-element new-line-space">
								</div>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Agent Commission Period</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<select name="fieldset48" id="fieldset48" required="required">
								<?php if(isset($agent_commission_period_name) && !empty($agent_commission_period_name)) { ?><option value="<?php echo $get_agent_commission_period; ?>" selected="selected"><?php echo $agent_commission_period_name; ?></option><?php }
								else { ?><option value="" selected="selected"></option><?php } ?>

								<?php echo $listagentcommissionperiod; ?>
							</select>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Cancellation Commission</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset49" id="fieldset49" value="<?php echo $get_cancellation_commission; ?>" step="any" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Global Corporate/Spl. Guests Discount</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset50" id="fieldset50" value="<?php echo $get_global_corporateguest_discount; ?>" step="any" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Minimum Deposit for Online Booking</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset51" id="fieldset51" value="<?php echo $get_online_minimum_deposit; ?>" step="any" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Online Booking Discount Percentage</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset52" id="fieldset52" value="<?php echo $get_online_booking_discount; ?>" step="any" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Allow Advance Booking Before</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="text" name="fieldset53" id="fieldset53" value="<?php echo $get_allow_advance_booking; ?>" pattern="\d*" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">days</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Show Rack Rate (even if there is a seasonal rate for that period)</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset54" id="fieldset54" required="required">
									<option value="<?php echo $get_rack_rate; ?>" selected="selected"><?php echo $get_rack_rate; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Is Pre Printed Layout</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset55" id="fieldset55" required="required">
									<option value="<?php echo $get_pre_printed_layout; ?>" selected="selected"><?php echo $get_pre_printed_layout; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Apply Daywise Seasonal Rate</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset56" id="fieldset56" required="required">
									<option value="<?php echo $get_allow_daywise_season_rate; ?>" selected="selected"><?php echo $get_allow_daywise_season_rate; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-30 alignlt">
							<h4 class="large alignlt">Notification Message</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset57" id="fieldset57"><?php if(isset($get_notification_message) && !empty($get_notification_message)) { echo $get_notification_message; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Is Tariff Include Taxes?</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset58" id="fieldset58" required="required">
									<option value="<?php echo $get_istariff_include_tax; ?>" selected="selected"><?php echo $get_istariff_include_tax; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Calculate Discount on Inclusion?</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset59" id="fieldset59" required="required">
									<option value="<?php echo $get_allow_inclusion_discount; ?>" selected="selected"><?php echo $get_allow_inclusion_discount; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Allow Tax on Extra Bed?</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset60" id="fieldset60" required="required">
									<option value="<?php echo $get_allow_extrabed_tax; ?>" selected="selected"><?php echo $get_allow_extrabed_tax; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Service Charge</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset61" id="fieldset61" step="any" value="<?php echo $get_service_charge; ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">VAT</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset62" id="fieldset62" step="any" value="<?php echo $get_vat; ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Consumption Tax</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset90" id="fieldset90" step="any" value="<?php echo $get_consumption_t; ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Inclusive of Service Tax</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7 alignlt">
								<input type="checkbox" value="Yes" checked="checked">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Allow HIMA to define Quotas</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset63" id="fieldset63" required="required">
									<option value="<?php echo $get_allow_hima_quota; ?>" selected="selected"><?php echo $get_allow_hima_quota; ?></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">Night Audit <small class="float-right">+</small></h3><br>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">The night audit or roll over will not happen before</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-20 right-push-5">
								<select name="fieldset64" id="fieldset64" required="required">
									<?php if(isset($get_night_audit_hr) && !empty($get_night_audit_hr)) { ?><option value="<?php echo $get_night_audit_hr; ?>" selected="selected"><?php echo $get_night_audit_hr; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listnightaudithr; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-20 right-push-7">
								<select name="fieldset65" id="fieldset65" required="required">
									<?php if(isset($get_night_audit_min) && !empty($get_night_audit_min)) { ?><option value="<?php echo $get_night_audit_min; ?>" selected="selected"><?php echo $get_night_audit_min; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listnightauditmin; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<select name="fieldset66" id="fieldset66" required="required">
									<?php if(isset($night_audit_calendar_type) && !empty($night_audit_calendar_type)) { ?><option value="<?php echo $get_night_audit_calendar; ?>" selected="selected"><?php echo $night_audit_calendar_type; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listnightauditcalendardate; ?>
								</select>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Night audit force user logout time in</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset67" id="fieldset67" required="required">
									<option value="<?php echo $get_night_audit_disable_users; ?>" selected="selected"><?php echo $get_night_audit_disable_users; ?></option>
									<?php echo $listnightauditalerttime; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">min(s)</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Wake-Up Call Time in</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset68" id="fieldset68" required="required">
									<option value="<?php echo $get_wakeup_call_time; ?>" selected="selected"><?php echo $get_wakeup_call_time; ?></option>
									<?php echo $listwakeupcalltime; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">min(s)</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">Hotel Management SMS Numbers <small class="float-right">+</small></h3><br>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-5 alignlt">
							<h4 class="large alignlt">SMS Sender Number</h4>
							<small class="block-element dark-grey-font top-push-3">Type the name of the hotel/group, which will sent in the sms</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<input type="text" name="fieldset69" id="fieldset69" value="<?php if(isset($get_hotel_sms_sender) && !empty($get_hotel_sms_sender)) { echo $get_hotel_sms_sender; } ?>" placeholder="e.g RockviewHtl" maxlength="11" required="required">
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">SMS Appending Message</h4>
							<small class="block-element dark-grey-font top-push-3">This message is attached with the text</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset70" id="fieldset70" required="required" placeholder="e.g Rockview Hotel"><?php if(isset($get_sms_appending_message) && !empty($get_sms_appending_message)) { echo $get_sms_appending_message; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Discount Mobile Numbers</h4>
							<small class="block-element dark-grey-font top-push-3">Type numbers for whom you want to send SMS by separating them with commas</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset71" id="fieldset71" required="required" placeholder="e.g 08099005500,08022339988"><?php if(isset($get_discount_numbers) && !empty($get_discount_numbers)) { echo $get_discount_numbers; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Night Audit Mobile Numbers</h4>
							<small class="block-element dark-grey-font top-push-3">Type numbers for whom you want to send SMS by separating them with commas</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset72" id="fieldset72" required="required" placeholder="e.g 08099005500,08022339988"><?php if(isset($get_night_audit_numbers) && !empty($get_night_audit_numbers)) { echo $get_night_audit_numbers; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Occupancy Status Mobile Numbers</h4>
							<small class="block-element dark-grey-font top-push-3">Type numbers for whom you want to send SMS by separating them with commas</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset73" id="fieldset73" required="required" placeholder="e.g 08099005500,08022339988"><?php if(isset($get_occupancy_status_numbers) && !empty($get_occupancy_status_numbers)) { echo $get_occupancy_status_numbers; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Complaint Mobile Numbers</h4>
							<small class="block-element dark-grey-font top-push-3">Type numbers for whom you want to send SMS by separating them with commas</small>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<textarea name="fieldset74" id="fieldset74" required="required" placeholder="e.g 08099005500,08022339988"><?php if(isset($get_complaint_numbers) && !empty($get_complaint_numbers)) { echo $get_complaint_numbers; } ?></textarea>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-7 alignlt">
							<h4 class="large alignlt">HK Touch-Up Timing</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-20 right-push-7">
								<select name="fieldset75" id="fieldset75" required="required">
									<?php if(isset($get_hk_touchup_number) && !empty($get_hk_touchup_number)) { ?><option value="<?php echo $get_hk_touchup_number; ?>" selected="selected"><?php echo $get_hk_touchup_number; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listtouchupnumber; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<select name="fieldset76" id="fieldset76" required="required">
									<?php if(isset($get_hk_touchup_timing) && !empty($get_hk_touchup_timing)) { ?><option value="<?php echo $get_hk_touchup_timing; ?>" selected="selected"><?php echo $hk_touchup_timing_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>
									<?php echo $listtiming12hr; ?>
								</select>
							</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">POS Food Type Timing <small class="float-right">+</small></h3><br>
					</div>
					<div class="top-push-10 bottom-push-10">
						<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Breakfast</h4>
						</div>
						<div class="ln-display-box float-right nc-width-55">
							<span class="ln-display-box float-left nc-width-50 alignlt right-pull-10">
								<h4 class="large nobold alignlt"> From</h4>
								<select name="fieldset77" id="fieldset77" required="required" class="top-push-5">
									<?php if(isset($get_breakfast_start_time) && !empty($get_breakfast_start_time)) { ?><option value="<?php echo $get_breakfast_start_time; ?>" selected="selected"><?php echo $breakfast_start_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="ln-display-box float-left nc-width-50 alignlt left-pull-10">
								<h4 class="large nobold alignlt"> To</h4>
								<select name="fieldset78" id="fieldset78" required="required" class="top-push-5">
									<?php if(isset($get_breakfast_end_time) && !empty($get_breakfast_end_time)) { ?><option value="<?php echo $get_breakfast_end_time; ?>" selected="selected"><?php echo $breakfast_end_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
					<div class="top-push-10 bottom-push-10">
						<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Lunch</h4>
						</div>
						<div class="ln-display-box float-right nc-width-55">
							<span class="ln-display-box float-left nc-width-50 alignlt right-pull-10">
								<h4 class="large nobold alignlt"> From</h4>
								<select name="fieldset79" id="fieldset79" required="required" class="top-push-5">
									<?php if(isset($get_lunch_start_time) && !empty($get_lunch_start_time)) { ?><option value="<?php echo $get_lunch_start_time; ?>" selected="selected"><?php echo $lunch_start_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="ln-display-box float-left nc-width-50 alignlt left-pull-10">
								<h4 class="large nobold alignlt"> To</h4>
								<select name="fieldset80" id="fieldset80" required="required" class="top-push-5">
									<?php if(isset($get_lunch_end_time) && !empty($get_lunch_end_time)) { ?><option value="<?php echo $get_lunch_end_time; ?>" selected="selected"><?php echo $lunch_end_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
					<div class="top-push-10 bottom-push-10">
						<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Dinner</h4>
						</div>
						<div class="ln-display-box float-right nc-width-55">
							<span class="ln-display-box float-left nc-width-50 alignlt right-pull-10">
								<h4 class="large nobold alignlt"> From</h4>
								<select name="fieldset81" id="fieldset81" required="required" class="top-push-5">
									<?php if(isset($get_dinner_start_time) && !empty($get_dinner_start_time)) { ?><option value="<?php echo $get_dinner_start_time; ?>" selected="selected"><?php echo $dinner_start_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="ln-display-box float-left nc-width-50 alignlt left-pull-10">
								<h4 class="large nobold alignlt"> To</h4>
								<select name="fieldset82" id="fieldset82" required="required" class="top-push-5">
									<?php if(isset($get_dinner_end_time) && !empty($get_dinner_end_time)) { ?><option value="<?php echo $get_dinner_end_time; ?>" selected="selected"><?php echo $dinner_end_time_name; ?></option><?php }
									else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $listtiming12hr; ?>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">TDS Percentages <small class="float-right">+</small></h3><br>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">TDS Payable Percentage</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset83" id="fieldset83" step="any" value="<?php if(isset($get_payable_percentage) && !empty($get_payable_percentage)) { echo $get_payable_percentage; } ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">TDS Receivable Percentage</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset84" id="fieldset84" step="any" value="<?php if(isset($get_receivable_percentage) && !empty($get_receivable_percentage)) { echo $get_receivable_percentage; } ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">%</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Coupon Expiry in Days</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<input type="number" name="fieldset85" id="fieldset85" step="any" value="<?php if(isset($get_coupon_expiry_days) && !empty($get_coupon_expiry_days)) { echo $get_coupon_expiry_days; } ?>" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>
				<div class="block-element box-border-thick sml-rounded-button pads30 bottom-push-10">
					<div class="bottom-push-20 alignlt">
						<h3 class="large nobold nomargin blue-font">Housekeeping Settings <small class="float-right">+</small></h3><br>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Mark all check-in rooms to</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset86" id="fieldset86" required="required">
									<?php if(isset($get_mark_all_checkin_rooms) && !empty($get_mark_all_checkin_rooms)) { ?><option value="<?php echo $get_mark_all_checkin_rooms; ?>" selected="selected"><?php echo $touchup; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $housekeepinglegends; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Mark all vacant rooms to</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset87" id="fieldset87" required="required">
									<?php if(isset($get_mark_all_vacant_rooms) && !empty($get_mark_all_vacant_rooms)) { ?><option value="<?php echo $get_mark_all_vacant_rooms; ?>" selected="selected"><?php echo $clean; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $housekeepinglegends; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">Mark all checked-out rooms to</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset88" id="fieldset88" required="required">
									<?php if(isset($get_mark_all_checkedout_rooms) && !empty($get_mark_all_checkedout_rooms)) { ?><option value="<?php echo $get_mark_all_checkedout_rooms; ?>" selected="selected"><?php echo $dirty; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>

									<?php echo $housekeepinglegends; ?>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="bottom-push-10">
						<span class="ln-display-box float-left nc-width-40 top-pull-10 alignlt">
							<h4 class="large alignlt">On night audit, mark all temporary bookings as</h4>
						</span>
						<span class="ln-display-box float-right nc-width-55">
							<div class="ln-display-box float-left nc-width-50 right-push-7">
								<select name="fieldset89" id="fieldset89" required="required">
									<?php if(isset($get_night_audit_mark_temp_booking) && !empty($get_night_audit_mark_temp_booking)) { ?><option value="<?php echo $get_night_audit_mark_temp_booking; ?>" selected="selected"><?php echo $get_night_audit_mark_temp_booking; ?></option><?php } else { ?><option value="" selected="selected"></option><?php } ?>
									<option value="Allow Booking">Allow Booking</option>
									<option value="No Show">No Show</option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-40 top-pull-10 alignlt ft-xsml-size">&nbsp;</div>
							<div class="block-element new-line-space"></div>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				</div>

				<br><br><br>

				<div class="fx-position-rel">
					<div class="white-theme block-element alignct">
						<input type="submit" name="submitbutton" value="Save or Update Settings" class="submit dark-black-white-state top-pull-10 right-pull-50 bottom-pull-10 left-pull-50 xsml-rounded-button">
					</div>
				</div>
			</form>
		</div>
	</div>