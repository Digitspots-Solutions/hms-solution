<?php

	#get useful privileges

	/*$modify_booking_prvl = workflow_privilege(200,'Modify Booking Type');
	$mbt_isprivilege = perform_role_check($tbL5,$myrole,$modify_booking_prvl,$this_class_id);

	$apply_discount_prvl = workflow_privilege(200,'Apply Discount');
	$ads_isprivilege = perform_role_check($tbL5,$myrole,$apply_discount_prvl,$this_class_id);

	$disable_latecheckout_prvl = workflow_privilege(200,'Disable Late Check-out Charges');
	$dlc_isprivilege = perform_role_check($tbL5,$myrole,$disable_latecheckout_prvl,$this_class_id);

	$disable_weekfare_prvl = workflow_privilege(200,'Disable Weekend Fares');
	$dwf_isprivilege = perform_role_check($tbL5,$myrole,$disable_weekfare_prvl,$this_class_id);

	$exclude_hoteltax_prvl = workflow_privilege(200,'Exclude Hotel Tax Charges');
	$eht_isprivilege = perform_role_check($tbL5,$myrole,$exclude_hoteltax_prvl,$this_class_id);

	$change_corptariff_prvl = workflow_privilege(200,'Allow Edit Corporate Tariff');
	$cct_isprivilege = perform_role_check($tbL5,$myrole,$change_corptariff_prvl,$this_class_id);

	$counter_prvl = workflow_privilege(200,'Allow Counter Use');
	$ctr_isprivilege = perform_role_check($tbL5,$myrole,$counter_prvl,$this_class_id);*/

	function isPrivileged($operation,$privilege) {

		global $userSignedIn;
		global $tbL10;
		global $tbL11;
		global $tbL7;
		global $tbL5;

		$intf_query = array("category"=>$operation,"moduleid"=>200);
		$wgt_operation = mysqli_data_fetch($tbL10,'id',$intf_query,'noarray');
		
		$prl_query = array("name"=>$privilege,"categoryid"=>$wgt_operation[0]);
		$wgt_privilege = mysqli_data_fetch($tbL11,'id',$prl_query,'noarray');
		
		$wgt_role = idget_data($tbL7,$userSignedIn,'role');
		$wgt_access = idget_data($tbL7,$userSignedIn,'uaccess');
		
		$response = 0;
		
		if($wgt_access == 'super admin') {
			$response = 200;
		} else {
			$accs_query = array("roleid"=>$wgt_role,"classid"=>$wgt_operation[0],"name"=>$wgt_privilege[0],"deletedata"=>0);
			$wgt_allow = mysqli_data_checkr($tbL5,'(*)',$accs_query);
			if($wgt_allow == true) { $response = 200; }
			else { $response = 0; }
		}

		return $response;
	}
	

	#end

	//$operation = "frontdesk";
	//$privilege = "Allow Past Reverse";
	$allowReverse = isPrivileged('Frontdesk','Allow Past Reverse');
	$allowDiscount = isPrivileged('Frontdesk','Apply Discount');
	$allowCounterUse = isPrivileged('Frontdesk','Use Counter');
	$allowPosCounterUse = isPrivileged('Point Of Sales','Use Counter');
	$allowMcDelete = isPrivileged('Material Control','Allow Record Archiving');
	$allowMcChangeStatus = isPrivileged('Material Control','Allow Change Record Status');
	$allowPosDelete = isPrivileged('Point Of Sales','Allow Record Archiving');
	$allowPosReverse = isPrivileged('Point Of Sales','Allow Past Reverse');
	$allowPriceUpdate = isPrivileged('Point Of Sales','Allow Price Update');
	$allowStartupStock = isPrivileged('Point Of Sales','Allow Create Start-up Stock');
	$allowAddTreasuryFund = isPrivileged('Accounting','Add Treasury Funds');
	$allowRebate = isPrivileged('Frontdesk','Allow Rebate');
	$allowNightAudit = isPrivileged('Frontdesk','Allow Night Audit');
	$allowOutletTransfer = isPrivileged('Point Of Sales','Allow Outlet-to-outlet Transfer');
	$allowRRv = isPrivileged('Recreation','Allow Recreation Payment Reverse');
	$allowRRupgrade = isPrivileged('Recreation','Allow Recreation Membership Upgrade');
	$allowRRpayment = isPrivileged('Recreation','Allow Recreation Membership Payment');
	$allowRRplan = isPrivileged('Recreation','Allow Recreation Membership Plan Modification');
	$allowMgnCoupon = isPrivileged('Frontdesk','Allow Manage Guest Coupon');
	$allowManualtariff = isPrivileged('Frontdesk','Allow Manual Tariff');
	$allowRemoveStock = isPrivileged('Material Control','Allow Remove Stock Item');
	$allowSeeInbox = isPrivileged('Material Control','Allow See All Inbox');
	$allowSeeReview = isPrivileged('Material Control','Allow See Inbox Review');
	$allowGuestdetailUpdate = isPrivileged('Frontdesk','Allow Guest-detail Update');
	$allowDocket = isPrivileged('Point Of Sales','Allow Docket');
	$allowOverseeDockets = isPrivileged('Point Of Sales','Allow Oversee Dockets');
	$allowUseDesktopPOS = isPrivileged('Point Of Sales','Use Desktop Pos');

?>