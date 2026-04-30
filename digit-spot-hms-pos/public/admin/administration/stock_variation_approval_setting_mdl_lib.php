<?php $smdl = "administration"; $logs = escape_data($_GET['logs']);  $for_mdl = 13; ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b>Stock Variation Approval Configuration</b>
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_103); //create a table for this post
		createDatabasetable($var_tbl_137); //create a table for this post

		$inserted = 0;

		if(isset($_POST['isdefault']) && $_POST['isdefault'] == 1) {
			$isdefault = 1;
		} else {
			$isdefault = 0;
		}

		if(isset($_POST['defaultname']) && !empty($_POST['defaultname'])) {
			$defaultname = $_POST['defaultname'];
		} else {
			$defaultname = "unknown";
		}

		$constrain = "";
		$dataproperty = array("approval_setting"=>$for_mdl,"workflow_name"=>ucwords(strtolower($defaultname)),"isdefault"=>$isdefault); $isdata = mysqli_data_insert($tbL142,$dataproperty,$constrain);

		if(isset($isdata) && $isdata == 2) { $inserted += 1; $new_workflow_id = $mysqli_id; }

		$approvalLevel = $_POST['levelofapproval'];
		$labelkey = $_POST['label'];
		$role = $_POST['role'];

		$dataproperty = ""; $constrain = "";

		for($r=0; $r < $approvalLevel; $r++) {
			$constrain = array("qc"=>$new_workflow_id,"level"=>$labelkey[$r],"approve"=>$for_mdl);
			$dataproperty = array("qc"=>$new_workflow_id,"level"=>$labelkey[$r],"approve"=>$for_mdl,"role"=>$role[$r]);
			$isdata_r = mysqli_data_insert($tbL108,$dataproperty,$constrain);
			if(isset($isdata_r) && $isdata_r == 2) { $inserted += 1; }
		}

		if(isset($inserted) && $inserted >= 1) {
			
			//create a log file
			$log_message = "Recently configured approval levels for recreation workflow";
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
			$post_result .= '<span class="red-font">Approval settings entry was saved successfully</span>';
			$post_result .= '</div>';
		}

		echo $post_result;
	}

	#---------------------------------------------------------------------------------------------------------------------------------------------

	if(isset($_POST['updatebutton']))
	{
		$inserted = 0;

		$pst_qc = escape_data($_POST['postqc']);

		if(isset($pst_qc) && $pst_qc >= 1) {

			if(isset($_POST['isdefault']) && $_POST['isdefault'] == 1) {
				$isdefault = 1;
			} else {
				$isdefault = 0;
			}

			if(isset($_POST['defaultname']) && !empty($_POST['defaultname'])) {
				$defaultname = $_POST['defaultname'];
			} else {
				$defaultname = "unknown";
			}

			//if new update is set as default, then first change default to non-default

			if($isdefault == 1) {
				$dataproperty = array("isdefault"=>0);
				$constrain = array("approval_setting"=>$for_mdl,"isdefault"=>1);
				mysqli_data_update($tbL142,$dataproperty,$constrain);
			}

			$constrain = array("id"=>$pst_qc);
			$dataproperty = array("workflow_name"=>ucwords(strtolower($defaultname)),"isdefault"=>$isdefault);
			$isdata = mysqli_data_update($tbL142,$dataproperty,$constrain);

			if(isset($isdata) && $isdata == 2) { $inserted += 1; }

			$approvalLevel = $_POST['levelofapproval'];
			$labelkey = $_POST['label'];
			$role = $_POST['role'];

			$dataproperty = ""; $constrain = ""; $check_this_role = "";

			for($r=0; $r < $approvalLevel; $r++) {
				
				$check_this_role = array("qc"=>$pst_qc,"level"=>$labelkey[$r],"approve"=>$for_mdl);
				$ifrole = mysqli_data_fetch($tbL108,'role',$check_this_role,'noarray');

				if(isset($ifrole[0]) && $ifrole[0] >= 1) {
					$dataproperty = array("role"=>$role[$r]);
					$constrain = array("qc"=>$pst_qc,"level"=>$labelkey[$r],"approve"=>$for_mdl);
					$isdata_r = mysqli_data_update($tbL108,$dataproperty,$constrain);
				} else {
					$dataproperty = array("qc"=>$pst_qc,"level"=>$labelkey[$r],"approve"=>$for_mdl,"role"=>$role[$r]);
					$constrain = array("qc"=>$pst_qc,"level"=>$labelkey[$r],"approve"=>$for_mdl);
					$isdata_r = mysqli_data_insert($tbL108,$dataproperty,$constrain);
				}

				if(isset($isdata_r) && $isdata_r == 2) { $inserted += 1; }
			}

			if(isset($inserted) && $inserted >= 1) {
				
				//create a log file
				$log_message = "Recently updated approval configuration levels for accounting workflow";
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

				$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';
				$post_result .= '<span class="red-font">Approval settings update was successful</span>';
				$post_result .= '</div>';
			}

			echo $post_result;
		}
	}

	#---------------------------------------------------------------------------------------------------------------------------------------------

	//list all workflow names in this approval setting and identify the default

	if(isset($_GET['rre']) && is_numeric($_GET['rre'])) {
		
		$rwkf = $_GET['rre'];
		
		$queryset = array("id"=>$rwkf);
		trash_record($tbL142,$queryset);

		$queryset2 = array("qc"=>$rwkf);
		trash_record($tbL108,$queryset2);
	}
	
	$select_query = array("approval_setting"=>$for_mdl);
	$select_data = mysqli_data_fetch($tbL142,'id,workflow_name,isdefault',$select_query,'array');

	if(is_array($select_data)) {
		
		$get_df = ""; $df = "";

		$listworkflows = '';
		$listworkflows = '<h3 class="large">Workflow Setup Names:</h3>';
		$listworkflows .= '<ul class="nolist">';
	
		foreach ($select_data as $key => $value) {
			if($value['isdefault'] == 1) { $get_df = $value['id']; $df = $value['isdefault']; }
			else { $get_df = 0; $df = 0; }
			$listworkflows .= '<li class="ln-display-box float-left right-push-50 bottom-push-20"><a href="?logs='.$logs.'&qc='.$value['id'].'&dft='.$value['isdefault'].'" class="blue-font">'.$value['workflow_name'].'</a> <a href="?logs='.$logs.'&rre='.$value['id'].'" title="Trash"><b class="fa-trash nobold left-push-5"></b></a></li>';
		}

		$listworkflows .= '<li class="block-element new-line-space"></li>';
		$listworkflows .= '</ul>';
	}

	/*if(isset($_GET['qc']) && $_GET['qc'] >= 1) { $get_Qcl = $_GET['qc']; $dft = $_GET['dft']; }
	else { if(isset($get_df) && $get_df >= 1) { $get_Qcl = $get_df; $dft = $df; } else { $get_Qcl = 0; $dft = 0; } }*/

	if(isset($_GET['qc']) && $_GET['qc'] >= 1) { $get_Qcl = $_GET['qc']; $dft = $_GET['dft']; }
	else { $get_Qcl = 0; $dft = 0; }

	if($get_Qcl >= 1) { $get_workflow_name = idget_data($tbL142,$get_Qcl,'workflow_name'); }
	else { $get_workflow_name = ""; }

?>

<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	<div class="ln-display-box float-left nc-width-40 right-push-50 bottom-push-30">
		<small class="block-element bottom-push-3">Workflow Name</small>
		<input type="text" name="defaultname" id="defaultname" placeholder="Enter workflow name" value="<?php echo $get_workflow_name; ?>" required="required">
	</div>
	<div class="ln-display-box float-left bottom-push-30 top-pull-30">
		<span class="ln-display-box float-left ft-xsml-size right-push-10 red-font">Isdefault</span>
		<span class="ln-display-box float-left"><input type="checkbox" name="isdefault" id="isdefault" value="1"<?php if(isset($dft) && $dft == 1) { ?> checked="checked"<?php } ?>></span>
		<span class="block-element new-line-space"></span>
	</div>
	<div class="block-element new-line-space">
	</div>

	<?php


		//check if approval settings exist by getting the highest level

		$mdl_sql = "COUNT(level)";
		$mdl_key = "approve=".$for_mdl." AND qc=".$get_Qcl;
		$max_level = mysqli_arithmetic_data($tbL108,$mdl_sql,$mdl_key);

		$this_level = 0;

		if(isset($_GET['level']) && !empty($_GET['level'])) {
			if(isset($max_level) && $max_level > $_GET['level']) {
				$this_level = $max_level;
			} else {
				$this_level = $_GET['level'];
			}
		} else {
			if(isset($max_level) && $max_level >= 1) {
				$this_level = $max_level;
			} else {
				$this_level = 1;
			}
		}

		$ext_tbls = "0,".$tbL12;
		$role_list = mt_select_fetch('status','Active',$tbL4,'id','role,departmentid',$ext_tbls,'0,department');

	?>

	<div class="ln-display-box float-left nc-width-40 right-push-50 bottom-push-30">
		<small class="block-element bottom-push-3">Level of Approval</small>
		<select name="levelofapproval" id="levelofapproval" required="required" onchange="load_level()">
			<option value="<?php echo $this_level; ?>" selected="selected"><?php echo $this_level; ?></option>
			<option value="1">1</option><option value="2">2</option>
			<option value="3">3</option><option value="4">4</option>
			<option value="5">5</option>
		</select>
	</div>
	<div class="ln-display-box float-left bottom-push-30 top-pull-30">
		<small class="dark-grey-font">Min Level: 1, Max Level: 5</small>
	</div>
	<div class="block-element new-line-space">
	</div>

	<?php echo $listworkflows; ?>
	
	<div class="block-element nc-width-70 sml-rounded-button noscroll">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="150px" align="center">Level</th>
				<th width="300px" align="center">Role</th>
				<th width="150px" align="center">Label</th>
			</tr>
			
			<?php

				//if(isset($_GET['level']) && $_GET['level']) {}

				$select_query = array("qc"=>$get_Qcl,"approve"=>$for_mdl);
				$select_data = mysqli_data_fetch($tbL108,'level,role',$select_query,'array');

				$count_numbr_appr_level_set = 0;

				if(is_array($select_data)) {

					$label_name = ''; $role_name = ''; $role_id = ''; $department_name = ''; $department_id = '';
					$numbr = 0;

					foreach($select_data as $key => $value) {
						
						$numbr += 1;

						$label_name = arrayget_key($approval_level,$value['level']);
						
						if(isset($value['role']) && $value['role'] >= 1) {
							$role_id = $value['role'];
							$department_id = idget_data($tbL4,$role_id,'departmentid');
							$department_name = idget_data($tbL12,$department_id,'department');
							$role_name = idget_data($tbL4,$role_id,'role').' ('.$department_name.')';
						} else {
							$role_id = '';
							$role_name = 'Choose';
						}

						?>
							<tr>
								<td width="150px" align="center">
									Approval Level <?php echo $numbr; ?>
								</td>
								<td width="300px" align="center">
									<select name="role[]" required="required">
										<option value="<?php echo $role_id; ?>" selected="selected"><?php echo $role_name; ?></option>
										<?php echo $role_list; ?>
									</select>
								</td>
								<td width="150px" align="center">
									<select name="label[]" required="required">
										<option value="<?php echo $value['level'] ?>" selected="selected"><?php echo $label_name; ?></option>
										<?php echo $get_listed_levels; ?>
									</select>
								</td>
							</tr>
						<?php

						$count_numbr_appr_level_set += 1;
					}
				}

				$round_level = "";

				if(isset($this_level) && ($this_level > $count_numbr_appr_level_set)) {
					for($i=$count_numbr_appr_level_set; $i < $this_level; $i++) {
						
						$round_level = $i + 1;
						
						?>
							<tr>
								<td width="150px" align="center">
									Approval Level <?php echo $round_level; ?>
								</td>
								<td width="300px" align="center">
									<select name="role[]" required="required">
										<option value="" selected="selected">Choose</option>
										<?php echo $role_list; ?>
									</select>
								</td>
								<td width="150px" align="center">
									<select name="label[]" required="required">
										<option value="" selected="selected">Choose</option>
										<?php echo $get_listed_levels; ?>
									</select>
								</td>
							</tr>
						<?php
					}
				}

			?>
			
		</table>
	</div>

	<div class="block-element top-pull-50 bottom-push-15 alignlt">
		<input type="hidden" name="postqc" id="postqc" value="<?php echo $get_Qcl; ?>">
		<input type="submit" name="submitbutton" value="Save Approval Settings" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <input type="submit" name="updatebutton" value="Update Approval Settings" class="submit top-pull-7 right-pull-50 bottom-pull-7 left-pull-50 blue-white-state sml-rounded-button"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="blue-font">Cancel</a>
	</div>
</form>

<script>
	
	function load_level() {
		var lv = document.getElementById('levelofapproval').value;
		window.location.href = '?logs=<?php echo $logs; ?>&qc=<?php echo $get_Qcl; ?>&level='+lv;
	}

</script>