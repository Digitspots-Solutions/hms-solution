<?php
	
	$membership = isset($_GET['membership']) ? $_GET['membership'] : "";
	$recreation_selection_key = array("recreation_number"=>$membership,"deletedata"=>0);

	$dataproperty = "id,recreation_number,photo,salutation,firstname,lastname,othernames,maritalstatus,gender,dob,nationality,emailaddress,mobile,membership_type,iscomplimentary,complimentary_src,profession,bodyheight,heightuom,bodyweight,weightuom,bloodgroup,genotype,officeaddress,officephone,homeaddress,plan,startdate,enddate,iscorporate,detail,workflow,isapproved,status";

	$get_recreation_data = mysqli_data_fetch($tbL105,$dataproperty,$recreation_selection_key,'noarray');
	$dataid = $get_recreation_data[0];

	//get total payment
	$payment_sql = "SUM(amount)";
	$payment_query = "memberid=".$dataid." ORDER BY id DESC LIMIT 1";
	$amountpaid = mysqli_arithmetic_data($tbL107,$payment_sql,$payment_query);

	$additionalQuery = "";

	$fields = "job_level,user_one,approval_one,comment_one,user_two,approval_two,comment_two,user_three,approval_three,comment_three,user_four,approval_four,user_five,approval_five,comment_five";
	$query = array("subject"=>$membership,"approval_type"=>"RECR");
	$jpL = mysqli_data_fetch($tbL151,$fields,$query,'noarray');

	
	$ir_workflow = "";

	if($jpL[0] == 1) {
				
		$userone = idget_data($tbL7,$jpL[1],'staffname');
		$useone_roleid = idget_data($tbL7,$jpL[1],'role');
		$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
		$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
		
		$ir_workflow .= "First Level Approval: <br>";
		$ir_workflow .= $userone." (".$userone_rolename.") <br>";
		$ir_workflow .= $apr_stat_one;

		if($jpL[2] == 0) {
			$ir_workflow .= '<p class="top-pull-10"><input type="button" value="Approve Membership" data-user="'.$jpL[1].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 ft-sml-size">'.$jpL[3].'</p>';
		}

	} elseif($jpL[0] == 2) {

		$userone = idget_data($tbL7,$jpL[1],'staffname');
		$useone_roleid = idget_data($tbL7,$jpL[1],'role');
		$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
		$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
		
		$ir_workflow .= "First Level Approval: <br>";
		$ir_workflow .= $userone." (".$userone_rolename.") <br>";
		$ir_workflow .= $apr_stat_one;

		if($jpL[2] == 0) {
			$ir_workflow .= '<p class="top-pull-10 bottom-pull-15"><input type="button" value="Approve Membership" data-user="'.$jpL[1].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 bottom-pull-15 ft-sml-size">'.$jpL[3].'</p>';
		}

		$usertwo = idget_data($tbL7,$jpL[4],'staffname');
		$usetwo_roleid = idget_data($tbL7,$jpL[4],'role');
		$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
		$apr_stat_two = arrayget_key($approval_status,$jpL[5]);
		
		$ir_workflow .= "Second Level Approval: <br>";
		$ir_workflow .= $usertwo." (".$usertwo_rolename.") <br>";
		$ir_workflow .= $apr_stat_two;

		if($jpL[5] == 0) {
			$ir_workflow .= '<p class="top-pull-10 bottom-pull-15"><input type="button" value="Approve Membership" data-user="'.$jpL[4].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 bottom-pull-15 ft-sml-size">'.$jpL[6].'</p>';
		}

	} elseif($jpL[0] == 3) {

		$userone = idget_data($tbL7,$jpL[1],'staffname');
		$useone_roleid = idget_data($tbL7,$jpL[1],'role');
		$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
		$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
		
		$ir_workflow .= "First Level Approval: <br>";
		$ir_workflow .= $userone." (".$userone_rolename.") <br>";
		$ir_workflow .= $apr_stat_one;

		if($jpL[2] == 0) {
			$ir_workflow .= '<p class="top-pull-10 bottom-pull-15"><input type="button" value="Approve Membership" data-user="'.$jpL[1].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 bottom-pull-15 ft-sml-size">'.$jpL[3].'</p>';
		}

		$usertwo = idget_data($tbL7,$jpL[4],'staffname');
		$usetwo_roleid = idget_data($tbL7,$jpL[4],'role');
		$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
		$apr_stat_two = arrayget_key($approval_status,$jpL[5]);
		
		$ir_workflow .= "Second Level Approval: <br>";
		$ir_workflow .= $usertwo." (".$usertwo_rolename.") <br>";
		$ir_workflow .= $apr_stat_two;

		if($jpL[5] == 0) {
			$ir_workflow .= '<p class="top-pull-10 bottom-pull-15"><input type="button" value="Approve Membership" data-user="'.$jpL[4].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 bottom-pull-15 ft-sml-size">'.$jpL[6].'</p>';
		}

		$userthree = idget_data($tbL7,$jpL[5],'staffname');
		$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
		$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
		$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
		
		$ir_workflow .= "Third Level Approval: <br>";
		$ir_workflow .= $userthree." (".$userthree_rolename.") <br>";
		$ir_workflow .= $apr_stat_three;

		if($jpL[6] == 0) {
			$ir_workflow .= '<p class="top-pull-10 bottom-pull-15"><input type="button" value="Approve Membership" data-user="'.$jpL[4].'" data-key="'.$membership.'" onclick="recrApprove(this)"></p>';
		} else {
			$ir_workflow .= '<p class="top-pull-5 bottom-pull-15 ft-sml-size">'.$jpL[6].'</p>';
		}

	} elseif($jpL[0] == 4) {

		$userone = idget_data($tbL7,$jpL[1],'staffname');
		$useone_roleid = idget_data($tbL7,$jpL[1],'role');
		$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
		$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
		
		$ir_workflow .= "First Level Approval: <br>";
		$ir_workflow .= $userone." (".$userone_rolename.") <br>";
		$ir_workflow .= $apr_stat_one." <br><br>";

		$usertwo = idget_data($tbL7,$jpL[3],'staffname');
		$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
		$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
		$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
		
		$ir_workflow .= "Second Level Approval: <br>";
		$ir_workflow .= $usertwo." (".$usertwo_rolename.") <br>";
		$ir_workflow .= $apr_stat_two." <br><br>";

		$userthree = idget_data($tbL7,$jpL[5],'staffname');
		$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
		$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
		$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
		
		$ir_workflow .= "Third Level Approval: <br>";
		$ir_workflow .= $userthree." (".$userthree_rolename.") <br>";
		$ir_workflow .= $apr_stat_three." <br><br>";

		$userfour = idget_data($tbL7,$jpL[7],'staffname');
		$usefour_roleid = idget_data($tbL7,$jpL[7],'role');
		$userfour_rolename = idget_data($tbL4,$usefour_roleid,'role');
		$apr_stat_four = arrayget_key($approval_status,$jpL[8]);
		
		$ir_workflow .= "Fourth Level Approval: <br>";
		$ir_workflow .= $userfour." (".$userfour_rolename.") <br>";
		$ir_workflow .= $apr_stat_four." <br><br>";

	} elseif($jpL[0] == 5) {

		$userone = idget_data($tbL7,$jpL[1],'staffname');
		$useone_roleid = idget_data($tbL7,$jpL[1],'role');
		$userone_rolename = idget_data($tbL4,$useone_roleid,'role');
		$apr_stat_one = arrayget_key($approval_status,$jpL[2]);
		
		$ir_workflow .= "First Level Approval: <br>";
		$ir_workflow .= $userone." (".$userone_rolename.") <br>";
		$ir_workflow .= $apr_stat_one." <br><br>";

		$usertwo = idget_data($tbL7,$jpL[3],'staffname');
		$usetwo_roleid = idget_data($tbL7,$jpL[3],'role');
		$usertwo_rolename = idget_data($tbL4,$usetwo_roleid,'role');
		$apr_stat_two = arrayget_key($approval_status,$jpL[4]);
		
		$ir_workflow .= "Second Level Approval: <br>";
		$ir_workflow .= $usertwo." (".$usertwo_rolename.") <br>";
		$ir_workflow .= $apr_stat_two." <br><br>";

		$userthree = idget_data($tbL7,$jpL[5],'staffname');
		$usethree_roleid = idget_data($tbL7,$jpL[5],'role');
		$userthree_rolename = idget_data($tbL4,$usethree_roleid,'role');
		$apr_stat_three = arrayget_key($approval_status,$jpL[6]);
		
		$ir_workflow .= "Third Level Approval: <br>";
		$ir_workflow .= $userthree." (".$userthree_rolename.") <br>";
		$ir_workflow .= $apr_stat_three." <br><br>";

		$userfour = idget_data($tbL7,$jpL[7],'staffname');
		$usefour_roleid = idget_data($tbL7,$jpL[7],'role');
		$userfour_rolename = idget_data($tbL4,$usefour_roleid,'role');
		$apr_stat_four = arrayget_key($approval_status,$jpL[8]);
		
		$ir_workflow .= "Fourth Level Approval: <br>";
		$ir_workflow .= $userfour." (".$userfour_rolename.") <br>";
		$ir_workflow .= $apr_stat_four." <br><br>";

		$userfive = idget_data($tbL7,$jpL[9],'staffname');
		$usefive_roleid = idget_data($tbL7,$jpL[9],'role');
		$userfive_rolename = idget_data($tbL4,$usefive_roleid,'role');
		$apr_stat_five = arrayget_key($approval_status,$jpL[10]);
		
		$ir_workflow .= "Fifth Level Approval: <br>";
		$ir_workflow .= $userfive." (".$userfive_rolename.") <br>";
		$ir_workflow .= $apr_stat_five;

	} else {
		$ir_workflow .= "No approval workflow: <br>";
		$ir_workflow .= "You may contact warehouse supply staff";
	}

?>
	
	<form action="" method="post" autocomplete="off">
		<?php echo $ir_workflow; ?>
		<input type="hidden" name="uri" value="approve-recreation">
	</form>

	<table cellpadding="0" cellspacing="0">
		<tr>
			<th width="180px" align="center" class="box-border-thick-right">Recreation No.</th>
			<th width="180px" align="center" class="box-border-thick-right">Name</th>
			<th width="180px" align="center" class="box-border-thick-right">Membership Type</th>
			<th width="200px" align="center" class="box-border-thick-right">Membership Period</th>
			<th width="150px" align="center" class="box-border-thick-right">Amount Paid</th>
			<th width="80px" align="center" class="box-border-thick-right">Status</th>
		</tr>
		<tr>
			<td width="180px" align="center" class="box-border-thick-right"><?php echo $get_recreation_data[1]; ?></td>
			<td width="180px" align="center" class="box-border-thick-right"><?php echo $get_recreation_data[4].' '.$get_recreation_data[5]; ?></td>
			<td width="180px" align="center" class="box-border-thick-right"><?php echo $get_recreation_data[26]; ?></td>
			<td width="200px" align="center" class="box-border-thick-right"><?php echo date('d-m-y',strtotime($get_recreation_data[27])).' - '.date('d-m-y',strtotime($get_recreation_data[28])); ?></td>
			<td width="150px" align="center" class="box-border-thick-right">&#8358; <?php echo number_format($amountpaid,2); ?></td>
			<td width="80px" align="center" class="box-border-thick-right light-red-font">InActive</td>
		</tr>
	</table>
	