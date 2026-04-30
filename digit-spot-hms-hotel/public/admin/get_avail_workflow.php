<?php

	$ths_workflow_names = '';

	$is_amdl_avail = 0;

	$qc_query = array("approval_setting"=>$amdl,"isdefault"=>1);
	$qc_label = mysqli_data_fetch($tbL142,'id,workflow_name',$qc_query,'noarray');

	if(isset($qc_label[0]) && $qc_label[0] >= 1) {
		
		$is_amdl_avail = 1;

		$qc_query_r = array("approve"=>$amdl,"qc"=>$qc_label[0]);
		$qc_r = mysqli_data_fetch($tbL108,'role',$qc_query_r,'array');

		$department_id = ''; $department_name = ''; $role_name = '';

		$grl = '';
		$grl .= '<optgroup label="--Workflow Chain--" class="light-yellow-theme ft-xsml-size">';

		foreach($qc_r as $grkey => $grvalue) {
			
			$department_id = idget_data($tbL4,$grvalue['role'],'departmentid');
			$department_name = idget_data($tbL12,$department_id,'department');
			$role_name = idget_data($tbL4,$grvalue['role'],'role').' ('.$department_name.')';

			$grl .= '<option disabled>'.$role_name.'</option>';
		}
		
		//$grl .= '<option disabled>----------------------------------------------------------</option>';
		$grl .= '</optgroup>';

		$ths_workflow_names .= '<option value="'.$qc_label[0].'">'.$qc_label[1].'</option>';
		$ths_workflow_names .= $grl;
	}
	

	$qc_query = array("approval_setting"=>$amdl,"isdefault"=>0);
	$qc_label = mysqli_data_fetch($tbL142,'id,workflow_name',$qc_query,'array');

	if(is_array($qc_label)) {
		
		$is_amdl_avail = 1;

		foreach ($qc_label as $qckey => $qcvalue) {
			
			$qc_query_r = array("approve"=>$amdl,"qc"=>$qcvalue['id']);
			$qc_r = mysqli_data_fetch($tbL108,'role',$qc_query_r,'array');

			$department_id = ''; $department_name = ''; $role_name = '';

			$grl2 = '';
			$grl2 .= '<optgroup label="--workflow Chain--" class="grey-1-theme ft-xsml-size">';

			foreach($qc_r as $grkey => $grvalue) {
				
				$department_id = idget_data($tbL4,$grvalue['role'],'departmentid');
				$department_name = idget_data($tbL12,$department_id,'department');
				$role_name = idget_data($tbL4,$grvalue['role'],'role').' ('.$department_name.')';

				$grl2 .= '<option disabled>'.$role_name.'</option>';
			}

			//$grl2 .= '<option disabled>--------------------------------------------</option>';
			$grl2 .= '</optgroup>';

			$ths_workflow_names .= '<option value="'.$qcvalue['id'].'">'.$qcvalue['workflow_name'].'</option>';
			$ths_workflow_names .= $grl2;
		}
	}


	if($is_amdl_avail == 0) {
		$ths_workflow_names .= '<option value="" class="red-font">No workflow</option>';
	}

?>