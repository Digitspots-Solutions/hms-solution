<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new user by clicking <u>new user</u> button. All asterik or marked fields are compulsory
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New User
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$get_department = select_dt_fetch('status','Active',$tbL12,'id','department');
	$get_roles = select_dt_fetch('status','Active',$tbL4,'id','role');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);
		$fieldset13 = escape_data($_POST['fieldset13']);
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset15 = escape_data($_POST['fieldset15']);
		$fieldset16 = escape_data($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);
		$fieldset18 = escape_data($_POST['fieldset18']);
		
		$staffname = ucwords(strtolower($fieldset4)).' '.ucwords(strtolower($fieldset5));

		$insert_dataproperty = array("primarycontact"=>$userSignedIn,"branchid"=>SERVER_AUTHEN_ID,"staffnumber"=>strtoupper($fieldset18),"staffname"=>$staffname,"mobile"=>$fieldset6,"department"=>$fieldset7,"role"=>$fieldset8,"homenumber"=>$fieldset9,"worknumber"=>$fieldset10,"passportnumber"=>$fieldset11,"qualification"=>$fieldset12,"isforcedip"=>$fieldset13,"forceip"=>$fieldset14,"datehire"=>$fieldset15,"dateofbirth"=>$fieldset16,"salary"=>$fieldset17,"username"=>$fieldset1,"emailaddress"=>$fieldset3,"password"=>sha1($fieldset2),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		$insert_constrain = array("username"=>$fieldset1);
		$data_inserted = mysqli_data_insert($tbL7,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new user","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{
		//$get_role = select_dt_fetch('status','Active',$tbL4,'id','role');

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-50">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New User/Staff</h3>
						</div>
						<span class="block-element bottom-push-20 alignlt">
							<small class="blue-font">Login Detail</small>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter username" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="password" name="fieldset2" id="fieldset2" placeholder="Enter password" required="required" lang="h">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-lock-pwd nobold ft-xsml-size anchor" title="Show/Hide Password" onclick="revealpwd('fieldset2')"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-30">
							<div class="ln-display-box float-left nc-width-90">
								<input type="email" name="fieldset3" id="fieldset3" placeholder="Enter email address" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-20 alignlt">
							<small class="blue-font">Basic Info.</small>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset18" id="fieldset18" placeholder="Enter employee number" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter firstname" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="text" name="fieldset5" id="fieldset5" placeholder="Enter lastname" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset6" id="fieldset6" placeholder="Enter mobile number">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset7" id="fieldset7" required="required" onchange="getdata('fieldset8','get-department-role','fieldset7','dropbox')">
									<option value="" selected="selected">Department</option>
									<?php echo $get_department; ?>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset8" id="fieldset8" required="required">
									<option value="" selected="selected">Role</option>
									<?php //echo $get_role; ?>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset9" id="fieldset9" placeholder="Enter home number">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset10" id="fieldset10" placeholder="Enter work number">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset11" id="fieldset11" placeholder="Enter passport number">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset12" id="fieldset12" placeholder="Enter qualification">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset13" id="fieldset13">
									<option value="" selected="">Is Force IP</option>
									<option value="No">No</option>
									<option value="Yes">Yes</option>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<textarea name="fieldset14" id="fieldset14" placeholder="Enter force IPs (if yes)"></textarea>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<small class="block-element dark-grey-font ft-xxsml-size alignlt left-pull-5">Date Hired</small>
								<input type="date" name="fieldset15" id="fieldset15">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<small class="block-element dark-grey-font ft-xxsml-size alignlt left-pull-5">Date of Birth</small>
								<input type="date" name="fieldset16" id="fieldset16">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset17" id="fieldset17" placeholder="Enter salary">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								&nbsp;
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
	
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL7,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change user status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['resetbutton']))
	{
		$fieldset1 = escape_data($_POST['rfieldset1']);
		$fieldset2 = escape_data($_POST['rfieldset2']);
		
		$insert_dataproperty = array("password"=>sha1($fieldset1));
		$insert_constrain = array("id"=>$fieldset2);
		$data_inserted = mysqli_data_update($tbL7,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change user password","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Password was changed successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);
		$fieldset11 = escape_data($_POST['fieldset11']);
		$fieldset12 = escape_data($_POST['fieldset12']);
		$fieldset13 = escape_data($_POST['fieldset13']);
		$fieldset14 = escape_data($_POST['fieldset14']);
		$fieldset15 = escape_data($_POST['fieldset15']);
		$fieldset16 = escape_data($_POST['fieldset16']);
		$fieldset17 = escape_data($_POST['fieldset17']);
		$fieldset18 = escape_data($_POST['fieldset18']);
		$fieldset19 = escape_data($_POST['fieldset19']);
		
		$staffname = ucwords(strtolower($fieldset5)).' '.ucwords(strtolower($fieldset6));

		$insert_dataproperty = array("staffnumber"=>strtoupper($fieldset4),"staffname"=>$staffname,"mobile"=>$fieldset7,"department"=>$fieldset8,"role"=>$fieldset9,"homenumber"=>$fieldset10,"worknumber"=>$fieldset11,"passportnumber"=>$fieldset12,"qualification"=>$fieldset13,"isforcedip"=>$fieldset14,"forceip"=>$fieldset15,"datehire"=>$fieldset16,"dateofbirth"=>$fieldset17,"salary"=>$fieldset18,"emailaddress"=>$fieldset3);

		$insert_constrain = array("id"=>$fieldset19);
		$data_inserted = mysqli_data_update($tbL7,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit user details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (username LIKE '".escape_data($_POST['search'])."%' OR staffnumber LIKE '".escape_data($_POST['search'])."%' OR staffname LIKE '".escape_data($_POST['search'])."%')";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 25;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,staffnumber,staffname,username,emailaddress,department,role,mobile,primarycontact,isforcedip,forceip,datehire,dateofbirth,qualification,salary,status,worknumber,homenumber";
	$constrain = array("deletedata"=>0,"uaccess"=>"limited");
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL7,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","login id","staffnumber","staffname","mobile","department","role","last login","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by keywords.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$htmlresult .= '</tr>';
		
		$num=$pgstart; $g=""; $dataid=""; $department=""; $role=""; $mylastlogin=""; $staffname="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$dpt_key = array("id"=>$tdata["department"]);
			$dpt_data = mysqli_get_schema_data($tbL12,'department',$dpt_key);
			$department=$dpt_data[0];

			$role_key = array("id"=>$tdata["role"]);
			$role_data = mysqli_get_schema_data($tbL4,'role',$role_key);
			$role=$role_data[0];

			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$ull_key = array("userid"=>$dataid,"logcategory"=>"accessibility");
			$user_last_login = mysqli_data_fetch($tbL8,'id,datelogged,timelogged',$ull_key,'noarray');

			if(isset($user_last_login[0]) && $user_last_login[0] >= 1) {
				$mylastlogin = date("d/m/Y",strtotime($user_last_login[1])).' '.$user_last_login[2];
			} else {
				$mylastlogin = "Not available";
			}

			$staffname = explode(' ', $tdata["staffname"]);
			
			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["username"].'<small class="block-element top-push-5 bottom-push-5 ft-xsml-size"><a href="?logs='.$logs.'&resetpwd='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Reset Password</a></small></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["staffnumber"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["staffname"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$department.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$role.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$mylastlogin.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">View/Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['resetpwd']) && $_GET['resetpwd'] >= 1) && ($_GET['resetpwd'] == $dataid))
			{
				$fieldset = escape_data($_GET['resetpwd']);
				$rfieldset1 = "'rfieldset1'";

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Reset Password for '.$tdata["username"].'</h4><br>';
				$htmlresult .= '<span class="ln-display-box float-left nc-width-30">';
				$htmlresult .= '<div class="cs-width-100 cs-height-100 rounded-element noscroll"><img src="'.DOMAIN_URL.'theme/images/general/photo.png" class="auto-wh"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="ln-display-box float-left nc-width-60">';
				$htmlresult .= '<input type="password" name="rfieldset1" id="rfieldset1" placeholder="Enter new password" required="required" lang="h">';
				$htmlresult .= '<small class="block-element ft-xxsml-size top-push-5 anchor" onclick="revealpwd('.$rfieldset1.')">&nbsp; SHOW/HIDE <b class="fa-lock-pwd nobold"></b></small>';

				$htmlresult .= '<div class="block-element top-push-20 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="rfieldset2" id="rfieldset2" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="resetbutton" value="Change Password" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element new-line-space"></span>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';

			}

			#------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$fieldset8="'fieldsetx8'"; $getrole="'get-department-role'"; $fieldset9="'fieldsetx9'"; $dropbox="'dropbox'";
				$fieldset16="'fieldset16'"; $fieldset17="'fieldset17'";

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">View/Update '.$tdata["staffname"].' Information</h4><br>';
				$htmlresult .= '<div class="nc-width-80">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-30">';
				$htmlresult .= '<div class="cs-width-100 cs-height-100 rounded-element noscroll"><img src="'.DOMAIN_URL.'theme/images/general/photo.png" class="auto-wh"></div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Login ID</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" value="'.$tdata["username"].'" required="required" readonly>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Password</small>';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" value="Password Chosen" required="required" readonly>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-30">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Email Address</small>';
				$htmlresult .= '<input type="text" name="fieldset3" id="fieldset3" value="'.$tdata["emailaddress"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Employee Number</small>';
				$htmlresult .= '<input type="text" name="fieldset4" id="fieldset4" value="'.$tdata["staffnumber"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Firstname</small>';
				$htmlresult .= '<input type="text" name="fieldset5" id="fieldset5" value="'.$staffname[0].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Lastname</small>';
				$htmlresult .= '<input type="text" name="fieldset6" id="fieldset6" value="'.$staffname[1].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Mobile Number</small>';
				$htmlresult .= '<input type="number" name="fieldset7" id="fieldsetx7" value="'.$tdata["mobile"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Department</small>';
				$htmlresult .= '<select name="fieldset8" id="fieldsetx8" required="required" onchange="getdata('.$fieldset9.','.$getrole.','.$fieldset8.','.$dropdox.')">';
				$htmlresult .= '<option value="'.$tdata["department"].'">'.$department.'</option>';
				$htmlresult .= $get_department;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Role</small>';
				$htmlresult .= '<select name="fieldset9" id="fieldsetx9" required="required">';
				$htmlresult .= '<option value="'.$tdata["role"].'">'.$role.'</option>';
				$htmlresult .= $get_roles;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Home Number</small>';
				$htmlresult .= '<input type="number" name="fieldset10" id="fieldsetx10" value="'.$tdata["homenumber"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Work Number</small>';
				$htmlresult .= '<input type="number" name="fieldset11" id="fieldset11" value="'.$tdata["worknumber"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Passport Number</small>';
				$htmlresult .= '<input type="text" name="fieldset12" id="fieldset12" value="'.$tdata["passportnumber"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Qualification</small>';
				$htmlresult .= '<input type="text" name="fieldset13" id="fieldset13" value="'.$tdata["qualification"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Force IP</small>';
				$htmlresult .= '<select name="fieldset14" id="fieldset14" required="required">';
				$htmlresult .= '<option value="'.$tdata["isforcedip"].'">'.$tdata["isforcedip"].'</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Force IP</small>';
				$htmlresult .= '<textarea name="fieldset15" id="fieldset15">'.$tdata["forceip"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Date Hired</small>';
				$htmlresult .= '<input type="text" name="fieldset16" id="fieldset16" value="'.$tdata["datehire"].'" onclick="textodate('.$fieldset16.')">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Date of Birth</small>';
				$htmlresult .= '<input type="text" name="fieldset17" id="fieldset17" value="'.$tdata["dateofbirth"].'" onclick="textodate('.$fieldset17.')">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Salary</small>';
				$htmlresult .= '<input type="text" name="fieldset18" id="fieldset18" value="'.$tdata["salary"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset19" id="fieldset19" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	$ukey = array("uaccess"=>"limited","deletedata"=>0);
	mysqli_data_check($tbL7,'(*)',$ukey);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>