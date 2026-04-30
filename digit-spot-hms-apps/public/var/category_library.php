<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		Note: create your category library by selecting the module category
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="objDisplay('modal-box'); objDisplay('modal-box-1')">
		Create
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$userSignedIn = USER_AUTHEN_ID;

	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'ini.php?logs='.$_GET['logs'].'/mdlses='.$_GET['mdlses'];

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_5); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset2']);
		$fieldset2 = escape_data($_POST['fieldset3']);
		

		$insert_dataproperty = array("categoryid"=>$fieldset1,"name"=>ucwords(strtolower($fieldset2)));
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL11,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"message"=>"Create new category library","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Data was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['efieldset1']);
		$fieldset2 = escape_data($_POST['efieldset2']);
		$fieldset3 = escape_data($_POST['efieldset3']);

		$insert_dataproperty = array("categoryid"=>$fieldset1,"name"=>$fieldset2);
		$insert_constrain = array("id"=>$fieldset3);
		$data_inserted = mysqli_data_update($tbL11,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"message"=>"Edit one of the category library","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Data was edited successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;

		?>
			<script> window.addEventListener('load',function() { objHidden('e-modal-box'); },false); </script>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		foreach ($_POST['checkers'] as $fkey) {
			
			$ukey = array("id"=>$fkey);
			$del = trash_record($tbL11,$ukey);

			if(isset($del) && $del == 2) {
				$data_deleted += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_deleted) && $data_deleted == 0)
		{
			$post_result .= '<span class="red-font">Unable to remove data. Try again</span>';
		}
		elseif(isset($data_deleted) && $data_deleted >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"message"=>"Remove one, two or more of the category libraries","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Data was removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['statusbutton']) && isset($_POST['checkers']))
	{
		$data_updated=0;

		foreach ($_POST['checkers'] as $fkey) {
			
			$ukey = array("id"=>$fkey);
			$curStatus = idget_data($tbL11,$fkey,'status');
			
			if($curStatus == 'Active') { $newStatus = "InActive"; }
			elseif($curStatus == 'InActive') { $newStatus = "Active"; }

			$coldatarry = array("status"=>$newStatus);
			$result = mysqli_data_update($tbL11,$coldatarry,$ukey);

			if(isset($result) && $result == 2) {
				$data_updated += 1;
			}

			$curstatus = ""; $newStatus = "";
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to remove data. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"message"=>"Disable one, two or more of the category libraries","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Record status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = " LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 30;
		$additionalQuery = " LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,categoryid,name,status";
	$constrain = "";
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL11,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","module","category","library","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'process-bar'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 blue-white-state rounded-button nc-width-20 right-push-10"> <input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-20">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $category_select=""; $module_select="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$additionalQuery = "";
			$categorykey = array("id"=>$tdata["categoryid"]);
			$category_select = mysqli_data_fetch($tbL10,'category,moduleid',$categorykey,'noarray');
			$module_select=appmodule($category_select[1]);

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$module_select.'</td>';
			$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$category_select[0].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["name"].'</td>';
			if($tdata["status"] == 'Active') { $htmlresult .= '<td width="70px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>'; } else { $htmlresult .= '<td width="70px" align="center" class="box-border-thick-right red-font">'.$tdata["status"].'</td>'; }
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$_GET['logs'].'&mdlses='.$_GET['mdlses'].'&edit='.$dataid.'&start='.$pgstart.'&limit='.$pglimit.'" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	mysqli_data_check($tbL11,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="modal-box" class="fx-position-flow fscr zind-1 top-pull-50 txp5-white motion" align="center">
	<div class="block-element nc-height-15">&nbsp;</div>
	<div id="modal-box-1" class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 sml-rounded-button obj-light-shadow motion">
		<form action="" method="post" autocomplete="off">
			<div class="form-block-label alignlt">
				<h3 class="nobold nomargin">New Category Library</h3><br>
			</div>
			<span class="block-element bottom-push-10">
				<select name="fieldset1" id="fieldset1" required="required" onchange="getdata('fieldset2','get-module-category','fieldset1','selectbox')">
					<?php echo $apmOpt; ?>
				</select>
			</span>
			<span class="block-element bottom-push-10">
				<select name="fieldset2" id="fieldset2" required="required">
					<option value="" selected="selected">Choose Category</option>
				</select>
			</span>
			<span class="block-element bottom-push-10">
				<input type="text" name="fieldset3" id="fieldset3" placeholder="Enter library name" required="required">
			</span>
			<br>
			
			<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('modal-box'); objHidden('modal-box-1')">Cancel</a>
		</form>
	</div>
</div>

<?php

	if(isset($_GET['edit']) && $_GET['edit'] >= 1)
	{
		$fieldset1 = escape_data($_GET['edit']);

		$additionalQuery = "";

		$edatasets = "categoryid,name";
		$edataid = array("id"=>$fieldset1);
		$eget_data = mysqli_data_fetch($tbL11,$edatasets,$edataid,'noarray');

		//get category
		$categorykey = array("id"=>$eget_data[0]);
		$category_select = mysqli_data_fetch($tbL10,'category,moduleid',$categorykey,'noarray');

		//get related category
		$modulekey = array("moduleid"=>$category_select[1]);
		$module_select = mysqli_data_fetch($tbL10,'id,category',$modulekey,'array');

		if(is_array($module_select))
	    {
	    	$nw_htmlresult='';

	    	foreach ($module_select as $mkey => $mvalue) {
	    		
	    		$nw_htmlresult .='<option value="'.$mvalue['id'].'">'.$mvalue['category'].'</option>';
	    	}
		}

		?>
			<div id="e-modal-box" class="fx-position-flow fscr zind-1 motion txp5-white" align="center">	
				<div class="block-element nc-height-15">&nbsp;</div>
				<div class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 sml-rounded-button obj-light-shadow motion">
					<form action="" method="post" autocomplete="off">
						<div class="form-block-label alignlt">
							<h3 class="nobold nomargin">Edit Category Library</h3><br>
						</div>
						<span class="block-element bottom-push-10">
							<select name="efieldset1" id="efieldset1" required="required">
								<option value="<?php echo $eget_data[0]; ?>"><?php echo $category_select[0]; ?></option>
								<?php echo $nw_htmlresult; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<input type="text" name="efieldset2" id="efieldset2" value="<?php echo $eget_data[1]; ?>" required="required">
							<input type="hidden" name="efieldset3" id="efieldset3" value="<?php echo $fieldset1; ?>" required="required">
						</span>
						<br>
						
						<input type="submit" name="editbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('e-modal-box')">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}
?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>