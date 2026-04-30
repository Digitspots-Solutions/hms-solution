<?php

	/*createDatabasetable($var_tbl_139); //create a table for this post

	$qc_query = array("qc"=>$ths_workflow);
	$qc_data = mysqli_data_fetch($tbL108,'level,approve,role',$qc_query,'array');

	if(is_array($qc_data)) {
		
		foreach ($qc_data as $qc_key => $qc_value) {
			
			$insert_query = array("document_ref_number"=>$document_ref_number,"approve"=>$qc_value['approve'],"role"=>$qc_value['role']);
			$insert_data = array("document_ref_number"=>$document_ref_number,"document_alias"=>$document_alias,"sender"=>$userSignedIn,"subject"=>$subject,"message"=>$message,"priority"=>$priority,"qc"=>$ths_workflow,"level"=>$qc_value['level'],"approve"=>$qc_value['approve'],"role"=>$qc_value['role'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			
			mysqli_data_insert($tbL144,$insert_data,$insert_query);
		}
	}*/

	function mysqli_data_dexist($table,$constrain)
	{
		global $mysqli;

		$response = array();
		
		$wgt = "SELECT * FROM {$table} WHERE ".$constrain;
		$result = $mysqli->query($wgt);

		if($result->num_rows > 0) {
			$response['isdata'] = true;
			$response['dbrows'] = $result->num_rows;
		} else {
			$response['isdata'] = false;
			$response['dbrows'] = 0;
		}

		return $response;
	}


	function mysqli_data_dfetch($xfunc,$sql)
	{
		global $mysqli;
		
		$response = array();

		$result = $mysqli->query($sql);
		
		if($xfunc == 'arithmetic-fx') {
			$data = $result->fetch_assoc();
			$response['mathr'] = $data['field'];
		} else if($xfunc == 'assoc') {
			while($data = $result->fetch_assoc()) { $response[] = $data; }
			$result->free();
		}

		return $response;
	}


	function mysqli_data_insertf($table,$dataset,$constrain)
	{
		global $mysqli;
		
		$noinsert = 0;
		$response = array();

		if(isset($constrain) && !empty($constrain)) {
			$result = mysqli_data_dexist($table,$constrain);
			if($result['isdata'] == true) { $noinsert = 1; }
			else { $noinsert = 0; }
		} else {
			$noinsert = 0;
		}

		if($noinsert == 0) {
			$sql = "INSERT INTO {$table} SET ".$dataset;
			$mysqli->query($sql);
			if($mysqli->affected_rows >= 1) {
				$response['isaffected'] = true;
				$response['rowid'] = $mysqli->insert_id;
			} else {
				$response['isaffected'] = false;
				$response['rowid'] = 0;
			}
		}

		return $response;
	}

	function getuser4_notification_rc($approves,$qc) {

		global $tbL108, $tbL7, $joblevel;

		if(is_numeric($qc) && $qc > 0) {
			$isSql = "SELECT role FROM {$tbL108} WHERE approve={$approves} AND qc={$qc} AND deletedata=0";
			$wgtjA = mysqli_data_dfetch('assoc',$isSql);
		} else {
			$isSql = "SELECT role FROM {$tbL108} WHERE approve={$approves} AND deletedata=0";
			$wgtjA = mysqli_data_dfetch('assoc',$isSql);
		}

		if(is_array($wgtjA) && count($wgtjA) > 0) {
			$insql=""; foreach($wgtjA as $key => $val) { $insql .= $val['role'].','; }
			$insqlr = substr_replace($insql,'',-1,1);

			$isSql2 = "SELECT id FROM {$tbL7} WHERE role IN({$insqlr}) AND deletedata=0";
			$wgtjA2 = mysqli_data_dfetch('assoc',$isSql2);

			return $wgtjA2;

		} else {
			return 0;
		}
	}

	function inboxmsg_rc($message_params) {
		
		global $tbL104;

		if(is_array($message_params)) {

			$users = $message_params['receiver'];
			$is_receiver = 0;

			if(is_array($users) && count($users) > 0) {
				
				foreach($users as $key => $val) {
					
					if(!empty($val['id']) && is_numeric($val['id'])) { $is_receiver = $val['id']; }
					elseif(!empty($val['user_two']) && is_numeric($val['user_two'])) { $is_receiver = $val['user_two']; }
					elseif(!empty($val['user_three']) && is_numeric($val['user_three'])) { $is_receiver = $val['user_three']; }
					elseif(!empty($val['user_four']) && is_numeric($val['user_four'])) { $is_receiver = $val['user_four']; }
					else { $is_receiver = 0; }
					
					$pst_query = "";
					$pst_field = "subject='{$message_params['subject']}',sender={$message_params['sender']},receiver={$is_receiver},message='{$message_params['message']}',priority={$message_params['priority']},msgtype={$message_params['msgtype']},datelogged='{$message_params['datelogged']}',timelogged='{$message_params['timelogged']}'";
					mysqli_data_insertf($tbL104,$pst_field,$pst_query);
					$pst_query = ""; $pst_field = "";
				}
			}
		}
	}



	$message_title = "Approval for Recreation Membership (".$recreation_number.")";
	$sendmessage = 'The following recreation membership with number ('.$recreation_number.') requires approval. Please click <a href="javascript:void(0)" class="blue-font" name="'.$recreation_number.'" onclick="jpson_rec(this.name)"><u>here</u></a> to acknowledge';

	//$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
	$users = getuser4_notification_rc(10,$ths_workflow);
	$message_params = array(
		"subject"=>$message_title,
		"sender"=>2,
		"receiver"=>$users,
		"message"=>$sendmessage,
		"priority"=>1,
		"msgtype"=>4,
		"datelogged"=>$server_get_date,
		"timelogged"=>$server_get_time
	);

	inboxmsg_rc($message_params);


	if(is_array($users) && count($users) > 0) {
					
		$joblevel = count($users);
		$pst_query = "subject='{$recreation_number}' AND approval_type='RECR'";
		
		if(isset($joblevel) && $joblevel == 1) {
			$pst_field = "job_level=1,subject='{$recreation_number}',user_one={$users[0]['id']},approval_type='RECR'";
		} elseif(isset($joblevel) && $joblevel == 2) {
			$pst_field = "job_level=2,subject='{$recreation_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='RECR'";
		} elseif(isset($joblevel) && $joblevel == 3) {
			$pst_field = "job_level=3,subject='{$recreation_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='RECR'";
		} elseif(isset($joblevel) && $joblevel == 4) {
			$pst_field = "job_level=4,subject='{$recreation_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='RECR'";
		} elseif(isset($joblevel) && $joblevel == 5) {
			$pst_field = "job_level=5,subject='{$recreation_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_five={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='RECR'";
		}

		mysqli_data_insertf($tbL151,$pst_field,$pst_query);
	}
	

?>