<?php
	
	$authen_id = ""; $authen_page = ""; $username = ""; $loggedtime = "";

	if(isset($_SESSION['authen_id']) && isset($_SESSION['authen_page']) && isset($_SESSION['loggedin_time'])) {
		$authen_id = $_SESSION['authen_id'];
		$authen_page = $_SESSION['authen_page'];
		$loggedtime = $_SESSION['loggedin_time'];
	} else {
		$authen_id = 0;
		$authen_page = 0;
		$loggedtime = 0;
	}

	$userSignedIn = $authen_id;
	
	if($authen_id > 0) {
		$queryset = "SELECT * FROM {$tbL1} WHERE id={$userSignedIn}";
		$wgtpfl = idget_data($queryset);

		$account_name = $wgtpfl[0]['staffname'];
		$designation = $wgtpfl[0]['designation'];
		$status = $wgtpfl[0]['status'];
		$branch = $wgtpfl[0]['branch'];
		$role = $wgtpfl[0]['role'];
	} else {
		$designation = "";
		$access = "";
		$status = 0;
		$account_name = "No account name";
		$branch = 0;
		$role = 0;
	}

	if($branch > 0) {
		$user_branch_name = idget_name($branch,'name',$tbL6);
		$branch_query_selector = "branch={$branch} AND deletedata=0";
		$wbranch_query_selector = "id={$branch} AND deletedata=0";
		$branch_query_string = "id";
	} else {
		$user_branch_name = "Admin Office";
		$branch_query_selector = "deletedata=0";
		$wbranch_query_selector = "deletedata=0";
		$branch_query_string = "deletedata";
	}


	if(isset($_SESSION['centre']) && isset($_SESSION['centreid'])) {
		
		$centreid = $_SESSION['centreid'];
		
		if($_SESSION['centre'] == 'pos') { $centre = $var_pos; }
		elseif($_SESSION['centre'] == 'warehouse') { $centre = $var_whr; }
		else { $centre = ""; }

		idget_global($_SESSION['centreid'],$centre);
		$centre_name = $_gparams[$centre]['returnval'];

	} else { 
		$centreid = 0;
		$centre_name = "INVALID_NAME";
	}

?>

<script>
	
	var user_branch = {
		"branchname":"<?php echo $user_branch_name; ?>",
		"branchid":"<?php echo $branch; ?>",
		"selector":"<?php echo $branch_query_string; ?>",
		"ftbl":"<?php echo $tbL6; ?>"
	}

	if(sessionStorage.getItem('userbranch') == 'undefined' || sessionStorage.getItem('userbranch') == null) {
		sessionStorage.setItem('userbranch',JSON.stringify(user_branch));
	}

</script>