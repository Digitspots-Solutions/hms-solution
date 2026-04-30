<?php $smdl = "frontdesk"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you create your manual rebate
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<style> .autoct { margin: 0 auto; } </style>


<?php

	#get all applcable workflows
	$mrworkFlow = getjob_workflow(9);
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$pst_query = "";
	$pst_field = "";

	createDatabasetable($var_tbl_310); //create a table for this post

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	#--------------------------------------------------------------------------------------------------------------


	if(isset($_POST['rebatebutton'])) {

		$transaction_type = $_POST['transactiontype'];
		$transaction_date = $_POST['transactiondate'];
		$guest_name = $_POST['guestname'];
		$amount = escape_data($_POST['amount']);
		$detail = escape_data($_POST['remark']);

		$rebate_number = prgSequence($tbL155,'RBT');
		
		$pst_query = array("rebate_no"=>$rebate_number);
		$pst_field = array("rebate_no"=>$rebate_number,"rebate_type"=>$transaction_type,"guest_name"=>$guest_name,"amount"=>$amount,"remark"=>$detail,"transaction_date"=>$transaction_date,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$result = mysqli_data_insert($tbL163,$pst_field,$pst_query);

		if(isset($result) && $result == 2) {

			#send inbox message and others

			$message_title = "Rebate Approval for (".$rebate_number.")";
			$sendmessage = 'The following rebate request number ('.$rebate_number.') is up for approval. Please click <a href="javascript:void(0)" class="blue-font" name="'.$rebate_number.'" onclick="jsmr(this.name)"><u>here</u></a> to acknowledge';

			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(9,$workflow);
			
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>2,
				"msgtype"=>21,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
			
				$joblevel = count($users);
				$pst_query = array("subject"=>$rebate_number,"approval_type"=>"RBT");
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = array("job_level"=>1,"subject"=>$rebate_number,"user_one"=>$users[0]['id'],"approval_type"=>"RBT");
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = array("job_level"=>2,"subject"=>$rebate_number,"user_one"=>$users[0]['id'],"user_two"=>$users[1]['id'],"approval_type"=>"RBT");
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = array("job_level"=>3,"subject"=>$rebate_number,"user_one"=>$users[0]['id'],"user_two"=>$users[1]['id'],"user_three"=>$users[2]['id'],"approval_type"=>"RBT");
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = array("job_level"=>4,"subject"=>$rebate_number,"user_one"=>$users[0]['id'],"user_two"=>$users[1]['id'],"user_three"=>$users[2]['id'],"user_four"=>$users[3]['id'],"approval_type"=>"RBT");
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = array("job_level"=>5,"subject"=>$rebate_number,"user_one"=>$users[0]['id'],"user_two"=>$users[1]['id'],"user_three"=>$users[2]['id'],"user_four"=>$users[3]['id'],"user_five"=>$users[4]['id'],"approval_type"=>"RBT");
				}


				mysqli_data_insert($tbL151,$pst_field,$pst_query);
			}
			

			//create a log file
			$message = "Recently created a manual rebate for ".$transaction_type." of sum of ".$amount.": ".$detail;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element top-push-30 alignct">';
			$post_result .= '<span class="light-red-font ft-sml-size">Manual rebate created successfully</span>';
			$post_result .= '</div>';
		}
	}

	#--------------------------------------------------------------------------------------------------------------

	echo $post_result;

?>


<div class="cs-height-30"></div>

<form action="" method="post" autocomplete="off" onsubmit="">
	
	<div id="acct-box" class="fx-width-80 box-border-thick motion sml-rounded-button top-push-30 white-theme autoct">
		<div class="box-border-thick-bottom pads20">
			<h3 class="large nobold">+ Manual Rebate Form</h3><br>
			<label class="block-element bottom-push-7">Select your approval workflow?</label>
			<select name="workflow" id="workflow" class="nopads no-back-black default-text-font-bold"><?php echo $mrworkFlow; ?></select>
		</div>
		<div class="pads20">
			
			
			<ul class="nolist">
				<li class="ln-display-box float-left fx-width-45 box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Rebate Type</h4>
					<select name="transactiontype" id="transactiontype" class="no-back-black" required>
						<option value="" selected>Choose?</option>
						<option value="Pos">Pos</option>
						<option value="Booking">Booking</option>
						<option value="Others">Others</option>
					</select>
				</li>
				<li class="ln-display-box float-left fx-width-45 box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 left-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Date</h4>
					<input type="date" name="transactiondate" id="transactiondate" value="<?php echo $server_get_date; ?>" class="no-back-black">
				</li>
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="ln-display-box float-left fx-width-45 right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Guest Name</h4>
					<input type="text" name="guestname" id="guestname" placeholder="Enter here" class="no-back-black" onkeyup="titleCase(this.value,this.id)">
				</li>
				<li class="ln-display-box float-left fx-width-45 right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 left-push-15">
					<h4 class="xlarge nobold default-text-font-bold">Amount</h4>
					<input type="text" name="wgtamount" id="wgtamount" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount')" class="no-back-black default-text-font-bold">
					<input type="hidden" name="amount" id="amount" required>
				</li>
				
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="">
					<textarea name="remark" id="remark" placeholder="Write remark here if applicable?" class="notextborder"></textarea>
				</li>
			</ul>
		</div>
	</div>

	<div class="fx-width-40 autoct top-pull-30 alignct">
		<input type="submit" name="rebatebutton" id="rebatebutton" value="Submit for approval" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
	</div>
</form>