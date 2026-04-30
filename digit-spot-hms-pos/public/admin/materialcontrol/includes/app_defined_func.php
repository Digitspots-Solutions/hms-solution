<?php
	
	function _appKeys($appkeydatas) {
	
		if($appkeydatas['user']['key'] > 0) {
			
			global $ukd_name;

			$tbl = $appkeydatas['user']['tbl'];
			$id = $appkeydatas['user']['key'];

			$sql = "SELECT * FROM {$tbl} WHERE id='{$id}' AND deletedata=0";
			$data = idget_data($sql);

			$ukd_name = $data[0]['staffname'];
		}
	}

	function getjob_workflow($mdl) {

		global $tbL142, $tbL108, $tbL4;

		$isSql = "SELECT * FROM {$tbL142} WHERE approval_setting={$mdl} AND deletedata=0";
		$wgtjA = idget_data($isSql);

		if(is_array($wgtjA) && count($wgtjA) > 0) {
			$fordefault=""; $nondefault="";
			foreach($wgtjA as $key => $val) {
				$isSql2 = "SELECT role FROM {$tbL108} WHERE approve={$mdl} AND qc={$val['id']}";
				$wgtjA2 = idget_data($isSql2);
				if($val['isdefault'] == 1) {
					$fordefault .= '<option value="'.$val['id'].'">'.$val['workflow_name'].'</option>';
					$fordefault .= '<optgroup label="--Approval Directors--" class="light-yellow-theme ft-xsml-size">';
					foreach($wgtjA2 as $key2 => $val2) { $fordefault .= '<option disabled>'.idget_name($val2['role'],'role',$tbL4).'</option>'; }
					$fordefault .= '</optgroup>';
				}
				if($val['isdefault'] == 0) {
					$nondefault .= '<option value="'.$val['id'].'">'.$val['workflow_name'].'</option>';
					$nondefault .= '<optgroup label="--Approval Directors--" class="light-yellow-theme ft-xsml-size">';
					foreach($wgtjA2 as $key2 => $val2) { $nondefault .= '<option disabled>'.idget_name($val2['role'],'role',$tbL4).'</option>'; }
					$nondefault .= '</optgroup>';
				}
			}

			return $fordefault.$nondefault;

		} else {
			return '<option value="">No workflow</option>';
		}
	}

	
	function getuser4_notification($approves,$qc) {

		global $tbL108, $tbL7, $joblevel;

		$users4A = array();

		if(is_numeric($qc) && $qc > 0) {
			$isSql = "SELECT role FROM {$tbL108} WHERE approve={$approves} AND qc={$qc} AND deletedata=0";
			$wgtjA = idget_data($isSql);
		} else {
			$isSql = "SELECT role FROM {$tbL108} WHERE approve={$approves} AND deletedata=0";
			$wgtjA = idget_data($isSql);
		}

		if(is_array($wgtjA) && count($wgtjA) > 0) {
			foreach($wgtjA as $key => $val) {
				$isSql2 = "SELECT id FROM {$tbL7} WHERE role = {$val['role']} AND deletedata=0";
				$wgtjA2 = idget_data($isSql2);
				$usr = array();
				$usr['id'] = $wgtjA2[0]['id'];
				array_push($users4A,$usr);
			}
		}

		return $users4A;
	}


	function inboxmsg($message_params) {
		
		global $tbL104;

		if(is_array($message_params)) {

			$users = $message_params['receiver'];
			
			if(is_array($users) && count($users) > 0) {
				
				foreach($users as $key => $val) {
					
					if(!empty($val['id']) && is_numeric($val['id'])) { $is_receiver = $val['id']; }
					else { $is_receiver = 0; }
					
					$pst_query = "subject='{$message_params['subject']}',sender={$message_params['sender']},receiver={$is_receiver}";
					$pst_field = "subject='{$message_params['subject']}',sender={$message_params['sender']},receiver={$is_receiver},message='{$message_params['message']}',priority={$message_params['priority']},msgtype={$message_params['msgtype']},datelogged='{$message_params['datelogged']}',timelogged='{$message_params['timelogged']}'";
					mysqli_data_insert($tbL104,$pst_field,$pst_query);
					$pst_query = ""; $pst_field = "";
					$is_receiver = 0;
				}
			}
		}
	}


	/*function getuser4_notification($approves,$qc) {

		global $tbL108, $tbL7, $joblevel;

		if(is_numeric($qc) && $qc > 0) {
			$isSql = "SELECT t2.id FROM {$tbL108} t1, {$tbL7} t2 WHERE t1.approve={$approves} AND t1.qc={$qc} AND t1.deletedata=0 AND t1.role=t2.role";
			$wgtjA = idget_data($isSql);
		} else {
			$isSql = "SELECT t2.id FROM {$tbL108} t1, {$tbL7} t2 WHERE t1.approve={$approves} AND t1.deletedata=0 AND t1.role=t2.role";
			$wgtjA = idget_data($isSql);
		}

		return $wgtjA;
	}


	function inboxmsg($message_params) {
		
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
					mysqli_data_insert($tbL104,$pst_field,$pst_query);
					$pst_query = ""; $pst_field = "";
				}
			}
		}
	}*/


	function prgSequence($table,$program) {
		
		$serialized_number = idget_fname($program,'app_name','start_number',$table);
		$to_number = $program.$serialized_number;
		$new_number = $serialized_number + 1;

		$pst_query = "app_name='{$program}'";
		$pst_field = "start_number={$new_number}";

		mysqli_data_update($table,$pst_field,$pst_query);

		return $to_number;
	}
	
?>