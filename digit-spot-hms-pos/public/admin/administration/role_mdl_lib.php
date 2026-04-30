<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create role by choosing department. Then assign privilege: Click <u>new role</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Role
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

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_7); //create a table for this post
		createDatabasetable($var_tbl_9); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);

		$insert_dataproperty = array("role"=>ucwords(strtolower($fieldset1)),"departmentid"=>$fieldset2,"mdl"=>$fieldset3);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL4,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new role","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		$usr = select_dt_fetch('status','Active',$tbL12,'id','department');

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Role</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<!--<small class="block-element bottom-push-3 left-pull-7 alignlt">Link to module?</small>-->
							<select name="fieldset3" id="fieldset3">
								<option value="0" selected="selected">Link to Module?</option>
								<?php echo $apmOpt; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset2" id="fieldset2" required="required">
								<option value="" selected="selected">Choose Department</option>
								<?php echo $usr; ?>
							</select>
						</span>
						
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
	
		$insert_dataproperty = array("role"=>ucwords(strtolower($fieldset1)),"departmentid"=>$fieldset2,"mdl"=>$fieldset3);
		$insert_constrain = array("id"=>$fieldset4);
		$data_inserted = mysqli_data_update($tbL4,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit role details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND role LIKE '".escape_data($_POST['search'])."%'";
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

	$dataproperty = "id,role,departmentid,mdl,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL4,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","role","user library","privilege","department","status","noth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<span class="ln-display-box float-left nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by role name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $department=""; $totalusers=""; $urole_key=""; $privilege_lib=""; $privilege_opts=""; $the_mdl="";
		
		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$the_mdl = appmodule($tdata["mdl"]);

			$dpt_key = array("id"=>$tdata["departmentid"]);
			$dpt_data = mysqli_get_schema_data($tbL12,'department',$dpt_key);
			$department=$dpt_data[0];

			$additionalQuery = "";
			$role_key = array("role"=>$dataid);
			mysqli_data_check($tbL7,'(*)',$role_key);
			$totalusers = $numOfrows;

			$urole_key = array("roleid"=>$dataid);
			$privilege_lib = mysqli_data_fetch($tbL5,'id,classid,name',$urole_key,'array');
			if(is_array($privilege_lib)) {
				
				$gmn=''; $glib1=''; $glib2=''; $glib3=''; $extquery='';

				foreach ($privilege_lib as $prlg_key => $prlg_value) {
					if($prlg_value["classid"] == 0) {
						$gmn .= '<option value="">'.appmodule($prlg_value["name"]).' Menus</option>';
					}
					
					if($prlg_value["classid"] == 999) {
						$extquery = array("id"=>$prlg_value["name"]);
						$lib1 = mysqli_data_fetch($tbL14,'posname',$extquery,'noarray');
						$glib1 .= '<option value="">'.$lib1[0].'</option>';
					}

					if($prlg_value["classid"] == 888) {
						$extquery = array("id"=>$prlg_value["name"]);
						$lib3 = mysqli_data_fetch($tbL10,'category',$extquery,'noarray');
						$glib3 .= '<option value="">'.$lib3[0].'</option>';
					}

					if($prlg_value["classid"] != 999 && $prlg_value["classid"] != 888 && $prlg_value["classid"] != 0) {
						$extquery = array("id"=>$prlg_value["name"]);
						$lib2 = mysqli_data_fetch($tbL11,'name',$extquery,'noarray');
						$glib2 .= '<option value="">'.$lib2[0].'</option>';
					}
				}

				$privilege_opts = $gmn.$glib1.$glib3.$glib2;

			} else {
				$privilege_opts ='<option value="">No Option!</option>';
			}

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["role"].'<small class="block-element top-push-3 bottom-push-5 ft-xxsml-size">(<a href="javascript:void(0)" class="blue-font" onclick="wgtprivilege('.$dataid.')">Add/View Privilege</a>)</small></td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&getuserlist='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="steel-blue-font">'.$totalusers.' User(s)</a></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right"><select>'.$privilege_opts.'</select></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$department.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['getuserlist']) && $_GET['getuserlist'] >= 1) && ($_GET['getuserlist'] == $dataid))
			{
				$fieldset = escape_data($_GET['getuserlist']);
				include "getuserlist.php";
				
				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= '<h4 class="large blue-font">User List</h4><br>';
				$htmlresult .= $inner_htmlresult;
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);
				$usr = select_dt_fetch('status','Active',$tbL12,'id','department');

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["role"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Role</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter role name" value="'.$tdata["role"].'">';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Linked Module</small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required">';
				if(isset($tdata["mdl"]) && $tdata["mdl"] >= 1) { $htmlresult .= '<option value="'.$tdata["mdl"].'" selected>'.$the_mdl.'</option>'; }
				else { $htmlresult .= '<option value="" selected>Link to Module</option>'; }
				$htmlresult .= $apmOpt;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';

				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Department</small>';
				$htmlresult .= '<select name="fieldset2" id="fieldset2" required="required">';
				$htmlresult .= '<option value="'.$tdata["departmentid"].'">'.$department.'</option>';
				$htmlresult .= $usr;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset4" id="fieldset4" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL4,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<script>

function wgtprivilege(str) {
	parent.document.getElementById('role-privilege').className = 'fx-position-stick fscr zind-2 motion txp5-black';
	parent.document.getElementById('inn-role-privilege').className = 'block-element';
	var newframe = document.createElement('iframe');
	newframe.setAttribute('id', 'wgtprivilege'); //assign an id
	newframe.name = 'wgtprivilege';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '100%';
	newframe.scrolling = 'auto';
	parent.document.getElementById('th-usr-pr').appendChild(newframe);
	parent.wgtprivilege.location.href = filePath+'public/admin/workspace.php?logs=add-privilege&role='+str;
}

</script>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>