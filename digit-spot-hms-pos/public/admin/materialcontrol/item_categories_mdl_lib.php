<?php $smdl = "material control"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new item category by clicking <u>new category</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Category
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

	//$cur_pos_store_id = $_SESSION['postoreid'];

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_110); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);

		$insert_dataproperty = array("category"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL115,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new stock category","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Category</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<textarea name="fieldset2" id="fieldset2" placeholder="Enter description" required="required"></textarea>
						</span>
						<span class="block-element">
							<input type="hidden" name="fieldset3" id="fieldset3" value="0" required="required">
						</span>
						
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['subcategorybutton']))
	{
		createDatabasetable($var_tbl_111); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
	
		$insert_dataproperty = array("categoryid"=>$fieldset3,"subcategory"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL116,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new stock subcategory","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Sub-category was created successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['itemgroupbutton']))
	{
		createDatabasetable($var_tbl_112); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);
	
		$insert_dataproperty = array("categoryid"=>$fieldset4,"subcategoryid"=>$fieldset3,"groupname"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL117,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new stock item group","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Item group was created successfully</span>';
		}

		$update_result .= '</div>';
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
			$cstat = mysqli_data_update($tbL115,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change stock category status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
	
		$insert_dataproperty = array("category"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2);
		$insert_constrain = array("id"=>$fieldset3);
		$data_inserted = mysqli_data_update($tbL115,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit stock category details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL115,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from stock category list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND category LIKE '".escape_data($_POST['search'])."%'";
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
		$pgstart = 0; $pglimit = 30;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,category,detail,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL115,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","category","sub category","item group","item","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		//$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		//$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
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
		
		$num=$pgstart; $g=""; $dataid=""; $subcategories=""; $noofproducts=""; $count_subcategory=""; $list_subcategory=""; $list_itemgroup="";
		$count_itemgroup=""; $list_item=""; $count_item="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			#-----------------------------------------------------------------------------------------------------------------
			//get number of sub categories

			$additionalQuery = "";
			$query_subcategory = array("categoryid"=>$dataid,"deletedata"=>0);
			$get_subcategory = mysqli_data_fetch($tbL116,'id,subcategory,detail',$query_subcategory,'array');

			if(is_array($get_subcategory)) {
				$noofsubcategories = 0; $htmlresult_in = ''; $sub_query_pos_product = '';
				foreach ($get_subcategory as $sub_catg_key => $sub_catg_value) {
					
					$sub_pos_product_datasets = "COUNT(id)";
					$sub_query_pos_product = "categoryid = '".$dataid."' AND subcategoryid = '".$sub_catg_value['id']."' AND deletedata = 0";
					$sub_noofproducts = mysqli_arithmetic_data($tbL118,$sub_pos_product_datasets,$sub_query_pos_product);

					$htmlresult_in .= '<div class="ln-display-box float-left nc-width-30 right-push-20 bottom-push-30">';
					$htmlresult_in .= '<h4 class="large">&bull; '.$sub_catg_value['subcategory'].'</h4>';
					$htmlresult_in .= '<small class="block-element bottom-push-3">'.$sub_catg_value['detail'].'</small>';
					$htmlresult_in .= '<small class="block-element alignlt dark-grey-font ft-xxsml-size"><u>'.$sub_noofproducts.' items</u></small>';
					$htmlresult_in .= '</div>';

					$noofsubcategories += 1;
				}

				$htmlresult_in .= '<div class="block-element new-line-space"></div>';
			} else {
				$noofsubcategories = 0;
				$htmlresult_in = '';
			}

			$count_subcategory = $noofsubcategories;
			$list_subcategory = $htmlresult_in;

			#-----------------------------------------------------------------------------------------------------------------
			//get number of item group

			$additionalQuery = "";
			$query_item_group = array("categoryid"=>$dataid,"deletedata"=>0);
			$get_item_group = mysqli_data_fetch($tbL117,'id,subcategoryid,groupname,detail',$query_item_group,'array');

			if(is_array($get_item_group)) {
				$noofitemgroup = 0; $htmlresult_in2 = ''; $subcategory_name = "";
				foreach ($get_item_group as $itg_key => $itg_value) {
					
					$subcategory_name = idget_data($tbL116,$itg_value['subcategoryid'],'subcategory');
					
					$htmlresult_in2 .= '<div class="ln-display-box float-left nc-width-30 right-push-20 bottom-push-30">';
					$htmlresult_in2 .= '<small class="block-element bottom-push-3 dark-grey-font">(In '.$subcategory_name.')</small>';
					$htmlresult_in2 .= '<h4 class="large">&bull; '.$itg_value['groupname'].'</h4>';
					$htmlresult_in2 .= '<small class="block-element bottom-push-3">'.$itg_value['detail'].'</small>';
					$htmlresult_in2 .= '</div>';

					$noofitemgroup += 1;
				}

				$htmlresult_in2 .= '<div class="block-element new-line-space"></div>';
			} else {
				$noofitemgroup = 0;
				$htmlresult_in2 = '';
			}

			$count_itemgroup = $noofitemgroup;
			$list_itemgroup = $htmlresult_in2;

			#-----------------------------------------------------------------------------------------------------------------
			//get number of items

			$additionalQuery = "";
			$query_item = array("categoryid"=>$dataid,"deletedata"=>0);
			$get_item = mysqli_data_fetch($tbL118,'id,item,status',$query_item,'array');

			if(is_array($get_item)) {
				$noofitems = 0; $htmlresult_in3 = '';
				foreach ($get_item as $itm_key => $itm_value) {
					
					$htmlresult_in3 .= '<div class="ln-display-box float-left nc-width-30 right-push-20 bottom-push-30">';
					$htmlresult_in3 .= '<h4 class="large">+ '.$itm_value['item'].'</h4>';
					$htmlresult_in3 .= '<small class="block-element bottom-push-3">'.$itm_value['status'].'</small>';
					$htmlresult_in3 .= '</div>';

					$noofitems += 1;
				}

				$htmlresult_in3 .= '<div class="block-element new-line-space"></div>';
			} else {
				$noofitems = 0;
				$htmlresult_in3 = '';
			}

			$count_item = $noofitems;
			$list_item = $htmlresult_in3;

			#-----------------------------------------------------------------------------------------------------------------
			


			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["category"].'</td>';
			
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">';
			
			if(isset($count_subcategory) && $count_subcategory >= 1) {
				$htmlresult .= '<small><a href="?logs='.$logs.'&sublist='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">'.$count_subcategory.' sub categories</a></small>';
			} else {
				$htmlresult .= '<small class="dark-grey-font">'.$count_subcategory.' sub categories</small>';
			}

			$htmlresult .= ' &nbsp; <small class="ft-xxsml-size"><a href="?logs='.$logs.'&addSc='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-white-state top-pull-3 right-pull-10 bottom-pull-3 left-pull-10 rounded-button">Add +</a></small>';

			$htmlresult .= '</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">';

			if(isset($count_itemgroup) && $count_itemgroup >= 1) {
				$htmlresult .= '<small><a href="?logs='.$logs.'&itemgroup='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">'.$count_itemgroup.' item groups</a></small>';
			} else {
				$htmlresult .= '<small class="dark-grey-font">'.$count_itemgroup.' item groups</small>';
			}

			$htmlresult .= ' &nbsp; <small class="ft-xxsml-size"><a href="?logs='.$logs.'&addItg='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-white-state top-pull-3 right-pull-10 bottom-pull-3 left-pull-10 rounded-button">Add +</a></small>';

			$htmlresult .= '</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">';

			if(isset($count_item) && $count_item >= 1) {
				$htmlresult .= '<small><a href="?logs='.$logs.'&items='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">'.$count_item.' items</a></small>';
			} else {
				$htmlresult .= '<small class="dark-grey-font">'.$count_item.' items</small>';
			}

			//$htmlresult .= ' &nbsp; <small class="ft-xxsml-size"><a href="?logs='.$logs.'&addItm='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-white-state top-pull-3 right-pull-10 bottom-pull-3 left-pull-10 rounded-button">Add +</a></small>';

			$htmlresult .= '</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["category"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Category</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" value="'.$tdata["category"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Description</small>';
				$htmlresult .= '<textarea name="fieldset2" id="fieldset2" placeholder="Enter description" required="required">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset3" id="fieldset3" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#------------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['addSc']) && $_GET['addSc'] >= 1) && ($_GET['addSc'] == $dataid))
			{
				$fieldset = escape_data($_GET['addSc']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Creating sub-category for <u>'.$tdata["category"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Sub Category</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Description</small>';
				$htmlresult .= '<textarea name="fieldset2" id="fieldset2" placeholder="Enter description" required="required"></textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset3" id="fieldset3" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="subcategorybutton" value="Create" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['sublist']) && $_GET['sublist'] >= 1) && ($_GET['sublist'] == $dataid))
			{
				//$fieldset = escape_data($_GET['sublist']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Sub-categories for <u>'.$tdata["category"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-90">';
				$htmlresult .= $list_subcategory;
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#--------------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['addItg']) && $_GET['addItg'] >= 1) && ($_GET['addItg'] == $dataid))
			{
				$fieldset = escape_data($_GET['addItg']);
				$get_active_subcategories = select_dt_fetch('categoryid',$fieldset,$tbL116,'id','subcategory');

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Creating item group under <u>'.$tdata["category"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Group Name</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter name" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Description</small>';
				$htmlresult .= '<textarea name="fieldset2" id="fieldset2" placeholder="Enter description" required="required"></textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Sub Category</small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required">';
				$htmlresult .= '<option value="">Select Sub-category</option>';
				$htmlresult .= $get_active_subcategories;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset4" id="fieldset4" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="itemgroupbutton" value="Create" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['itemgroup']) && $_GET['itemgroup'] >= 1) && ($_GET['itemgroup'] == $dataid))
			{
				//$fieldset = escape_data($_GET['sublist']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Item groups under <u>'.$tdata["category"].'</u></h4><br>';
				$htmlresult .= '<div class="nc-width-90">';
				$htmlresult .= $list_itemgroup;
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#--------------------------------------------------------------------------------------------------------------------------------------------------
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
	mysqli_data_check($tbL115,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>