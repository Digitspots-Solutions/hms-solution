<?php
	
	//notification type

	$notify_arry = array(
		1=>"Online Booking",
		2=>"Offline Booking",
		3=>"Booking Cancellation",
		4=>"Payment",
		5=>"Discount Apply",
		6=>"Purchase Order",
		7=>"Withdrawal",
		8=>"Saving/Deposit",
		9=>"IOU Approval",
		10=>"Item Request",
		11=>"Stock Re-order",
		12=>"General Request",
		13=>"General Notification",
		14=>"Credit Limit",
		15=>"Payment Approval",
		16=>"Purchase Order Approval",
		17=>"Cost Control Unit",
		18=>"Fund Disburstment",
		19=>"Stock Variation Approval",
		20=>"Transfer Request",
		21=>"Manual Rebate Request"
	);

	
	$ntpOpt = '<option value="">Notification Type</option>';
	foreach ($notify_arry as $ntp_key => $ntp_value) {
		$ntpOpt .= '<option value="'.$ntp_value.'">'.$ntp_key.'</option>';
	}


	#-----------------------------------------------------------------------------------

	$notify_title = array(
		1=>"Corporate guest account credit limit alert",
		2=>"Corporate guest account recurring payment",
		3=>"Recreation: (Membership account renewal)",
		4=>"Recreation: (Membership account approval)",
		5=>"For Material Control / Procurement Section"
	);

	$mail_message_priority = array(
		1=>"High",
		2=>"Medium",
		3=>"Low"
	);

	$color_notification = array(
		2=>"#000000",
		1=>"#6b8e23",
		0=>"#ff0000"
	);

	$inb_message_status = array(
		1=>"Read",
		0=>"Unread"
	);

	$record_status = array(
		1=>"Active",
		0=>"InActive"
	);

	$record_approval = array(
		1=>"IsApproved",
		0=>"Not approved"
	);


	#-----------------------------------------------------------------------------------

	$approval_level = array(
		1=>"Sanctioned By",
		2=>"Authorized By",
		3=>"Verified By",
		4=>"Certified By",
		5=>"Approved By",
		6=>"Confirmed By"
	);

	$approval_setting = array(
		1=>"For Accounting",
		2=>"For City Ledger",
		3=>"For IOU",
		4=>"For Item Request",
		5=>"For Other Expenses",
		6=>"For PR",
		7=>"For Item Receiving",
		8=>"For PR Make Payment",
		9=>"For Rebate",
		10=>"For Recreation",
		11=>"For Item Cost Price Changes",
		12=>"For Transfer Request",
		13=>"For Stock Variation"
	);

	$quality_control = array(
		1 => array(
			"Default" => "Set as default"
		),
		2 => array(
			"Workflow" => "Set as workflow"
		)
	);

	$get_listed_levels = arrayset_form($approval_level,'select');

?>