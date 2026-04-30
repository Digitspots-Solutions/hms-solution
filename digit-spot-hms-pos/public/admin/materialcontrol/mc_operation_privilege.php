<?php

	#get useful privileges

	function isPrivileged($operation,$privilege) {

		global $userSignedIn;
		global $tbL10;
		global $tbL11;
		global $tbL7;
		global $tbL5;

		$intf_query = "SELECT id FROM {$tbL10} WHERE category='{$operation}' AND moduleid=200";
		$wgt_operation = idget_data($intf_query); $operationid = $wgt_operation[0]['id'];
		
		$prl_query = "SELECT id FROM {$tbL11} WHERE name='{$privilege}' AND categoryid={$operationid}";
		$wgt_privilege = idget_data($prl_query); $privilegeid = $wgt_privilege[0]['id'];
		
		$wgt_role = idget_name($userSignedIn,'role',$tbL7);
		$wgt_access = idget_name($userSignedIn,'uaccess',$tbL7);
		
		$response = 0;
		
		if($wgt_access == 'super admin') {
			$response = 200;
		} else {
			$accs_query = "roleid={$wgt_role} AND classid={$operationid} AND name={$privilegeid} AND deletedata=0";
			$wgt_allow = mysqli_data_exist($tbL5,$accs_query);
			
			if($wgt_allow['isdata'] == true) { $response = 200; }
			else { $response = 0; }
		}

		return $response;
	}
	

	#list all operational privilege
	$allowMcDelete = isPrivileged('Material Control','Allow Record Archiving');
	$allowMcChangeStatus = isPrivileged('Material Control','Allow Change Record Status');
	$allowStartupStock = isPrivileged('Point Of Sales','Allow Create Start-up Stock');
	$allowRemoveStock = isPrivileged('Material Control','Allow Remove Stock Item');

?>