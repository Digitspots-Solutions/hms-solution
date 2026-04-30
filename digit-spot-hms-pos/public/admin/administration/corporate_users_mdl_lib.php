<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>
<?php if(isset($_GET['corporateuser']) && is_numeric($_GET['corporateuser'])) { $myfirm = $_GET['corporateuser']; } else { $myfirm = 0; } ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>&corporateuser=<?php echo $myfirm; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new corporate user by clicking <u>new user</u> button. All asterik or marked fields are compulsory
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new&corporateuser=<?php echo $myfirm; ?>" class="submit pads12 sml-rounded-button blue-theme white-font">
		New User
		</a>
		<?php
			if(isset($myfirm) && $myfirm >= 1) {
				?>&nbsp; <a href="?logs=corporate/spl.-guests" class="submit pads12 sml-rounded-button blue-theme white-font">
					&lsaquo; Back
				</a><?php
			}
		?>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$get_role = select_dt_fetch('status','Active',$tbL4,'id','role');
	$get_corporate_name = select_dt_fetch('status','Active',$tbL58,'id','name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_44); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);
		
		$insert_dataproperty = array("branchid"=>SERVER_AUTHEN_ID,"flname"=>ucwords(strtolower($fieldset4)),"mobile"=>$fieldset5,"corporatename"=>$fieldset6,"role"=>$fieldset7,"username"=>$fieldset1,"emailaddress"=>$fieldset3,"password"=>sha1($fieldset2),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		$insert_constrain = array("username"=>$fieldset1);
		$data_inserted = mysqli_data_insert($tbL46,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new corporate user","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{
		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-50">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Corporate User</h3>
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
								<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter first & lastname" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset5" id="fieldset5" placeholder="Enter mobile number" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<select name="fieldset6" id="fieldset6" required="required">
									
									<?php
									if(isset($myfirm) && $myfirm >= 1) {
										$corporateid = escape_data($myfirm);
										$currentcorporate = idget_data($tbL58,$corporateid,'name');
										?>
											<option value="<?php echo $corporateid; ?>" selected="selected"><?php echo $currentcorporate; ?></option>
										<?php
									} else {
										?>
											<option value="" selected="selected">Corporate Name?</option>
										<?php
										echo $get_corporate_name;
									}
									?>

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
								<select name="fieldset7" id="fieldset7" required="required">
									<option value="" selected="selected">Role</option>
									<?php echo $get_role; ?>
								</select>
							</div>
							<div class="ln-display-box float-right nc-width-5 top-pull-10">
								<b class="fa-checker nobold ft-xsml-size"></b>
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
			$cstat = mysqli_data_update($tbL46,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change corporate user status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$data_inserted = mysqli_data_update($tbL46,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change corporate user password","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		
		
		$insert_dataproperty = array("branchid"=>SERVER_AUTHEN_ID,"flname"=>ucwords(strtolower($fieldset4)),"mobile"=>$fieldset5,"corporatename"=>$fieldset6,"role"=>$fieldset7,"emailaddress"=>$fieldset3,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		$insert_constrain = array("id"=>$fieldset8);
		$data_inserted = mysqli_data_update($tbL46,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit corporate user details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (corporatename LIKE '".escape_data($_POST['search'])."%' OR username LIKE '".escape_data($_POST['search'])."%' OR flname LIKE '".escape_data($_POST['search'])."%')";
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

	$dataproperty = "id,flname,username,emailaddress,role,mobile,status,corporatename";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL46,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","login id","flname","mobile","corporate","role","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $department=""; $role=""; $mylastlogin=""; $staffname=""; $corporate="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$role_key = array("id"=>$tdata["role"]);
			$role_data = mysqli_get_schema_data($tbL4,'role',$role_key);
			$role=$role_data[0];

			$corporate = idget_data($tbL58,$tdata["corporatename"],'name');

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["username"].'<small class="block-element top-push-5 bottom-push-5 ft-xsml-size"><a href="?logs='.$logs.'&resetpwd='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Reset Password</a></small></td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["flname"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["emailaddress"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$corporate.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$role.'</td>';
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

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">View/Update '.$tdata["flname"].' Information</h4><br>';
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
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" value="Password Chosen" readonly>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-30">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Email Address</small>';
				$htmlresult .= '<input type="text" name="fieldset3" id="fieldset3" value="'.$tdata["emailaddress"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">First & Lastname</small>';
				$htmlresult .= '<input type="text" name="fieldset4" id="fieldset4" value="'.$tdata["flname"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Mobile Number</small>';
				$htmlresult .= '<input type="number" name="fieldset5" id="fieldset5" value="'.$tdata["mobile"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Corporate Name</small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				$htmlresult .= '<option value="'.$tdata["corporatename"].'" selected="selected">'.$corporate.'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Role</small>';
				$htmlresult .= '<select name="fieldset7" id="fieldset7" required="required">';
				$htmlresult .= '<option value="'.$tdata["role"].'">'.$role.'</option>';
				$htmlresult .= $get_role;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset8" id="fieldset8" value="'.$fieldset.'">';
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
	$ukey = array("deletedata"=>0);
	mysqli_data_check($tbL46,'(*)',$ukey);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>