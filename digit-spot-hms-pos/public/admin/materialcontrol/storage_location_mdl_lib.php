<?php $smdl = "material control"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Here is list of storage locations. To add more, go to <u>master data</u> - <u>cost center and stores</u>
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$get_dept = select_dt_fetch('status','Active',$tbL12,'id','department');
	$get_store_type = arrayset_form($store_type,'select');
	$get_parent_store = arrayset_form($parent_store,'select');

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL123,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change store outlet status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
		$fieldset4 = escape_data($_POST['fieldset4']);
		$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset7 = escape_data($_POST['fieldset7']);

		$insert_dataproperty = array("store_name"=>ucwords(strtolower($fieldset1)),"detail"=>$fieldset2,"store_type"=>$fieldset3,"parent_store"=>$fieldset4,"address"=>$fieldset5,"department"=>$fieldset6);
		$insert_constrain = array("id"=>$fieldset7);
		$data_inserted = mysqli_data_update($tbL123,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit store outlet details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
			$del = mysqli_data_update($tbL123,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently removed store outlet","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (store_name LIKE '".escape_data($_POST['search'])."%' OR store_number LIKE '".escape_data($_POST['search'])."%')";
	} else { 
		if(isset($_GET['getstatus']) && ($_GET['getstatus'] == 'Active' || $_GET['getstatus'] == 'InActive')) {
			$keywords=" AND status = '".$_GET['getstatus']."'";
		} else {
			$keywords="";
		}
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

	$dataproperty = "id,store_number,store_name,store_type,parent_store,department,detail,address,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL123,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","store code","store name","store type","address","status");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		//$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-20">';
		//$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		
		$htmlresult .= '<span class="ln-display-box float-right nc-width-20 top-pull-10 alignrt">';
		$htmlresult .= '<a href="?logs='.$logs.'&getstatus=All" class="steel-blue-font ft-xxsml-size"><u>All</u></a> &nbsp; <a href="?logs='.$logs.'&getstatus=Active" class="steel-blue-font ft-xxsml-size"><u>Active</u></a> &nbsp; <a href="?logs='.$logs.'&getstatus=InActive" class="steel-blue-font ft-xxsml-size"><u>InActive</u></a>';
		$htmlresult .= '</span>';

		$htmlresult .= '<span class="ln-display-box float-right nc-width-30">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $this_store_type=""; $this_parent_store=""; $this_department="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$this_store_type = arrayget_key($store_type,$tdata["store_type"]);
			$this_parent_store = arrayget_key($parent_store,$tdata["parent_store"]);
			$this_department = idget_data($tbL12,$tdata["department"],'department');

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["store_number"].'</td>';
			$htmlresult .= '<td width="250px" align="center" class="box-border-thick-right"><a href="javascript:void(0)" class="royal-blue-font" title="Store Details" onclick="get_item_detail('.$dataid.')"><b>'.$tdata["store_name"].'</b></a></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$this_store_type.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["address"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '</tr>';

			#-----------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Store: '.$tdata["store_name"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Name <b class="red-font">*</b></small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" value="'.$tdata["store_name"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Description</small>';
				$htmlresult .= '<textarea name="fieldset2" id="fieldset2" placeholder="Enter description">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Store Type <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required" onchange="pSt()">';
				$htmlresult .= '<option value="'.$tdata["store_type"].'" selected="selected">'.$this_store_type.'</option>';
				$htmlresult .= $get_store_type;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Parent Store <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset4" id="fieldset4" required="required">';
				$htmlresult .= '<option value="'.$tdata["parent_store"].'" selected="selected">'.$this_parent_store.'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Address</small>';
				$htmlresult .= '<textarea name="fieldset5" id="fieldset5" placeholder="Enter description">'.$tdata["address"].'</textarea>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-15">';
				$htmlresult .= '<small class="block-element bottom-push-5 left-pull-5 dark-grey-font alignlt">Department <b class="red-font">*</b></small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				$htmlresult .= '<option value="'.$tdata["department"].'" selected="selected">'.$this_department.'</option>';
				$htmlresult .= $get_dept;
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-20 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset7" id="fieldset7" value="'.$fieldset.'">';
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
	mysqli_data_check($tbL123,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<script type="text/javascript">
	
	function pSt(opt1,opt2) {
		var st = document.getElementById('fieldset3'), ps = document.getElementById('fieldset4'), fget_ps = '';

		fget_ps += '<option value="" selected="selected">Select Parent</option>';
		if(eval(st.value) == 1) { fget_ps += '<?php echo $get_fs_parent_store; ?>'; }
		else { fget_ps += '<?php echo $get_parent_store; ?>'; }

		ps.innerHTML = fget_ps;
	}


	function get_item_detail(id) {
		chgclass('item-mgt','fx-position-stick zind-2 motion fscr txp8-black noscroll');
		chgclass('item-mgt-box','block-element');

		if(eval(document.getElementById('isframeSet').value) == 1) {
			var newframe = document.querySelector('iframe');
			objDisplay('loading-msg');
			newframe.src = "store_mgt.php?thisItem="+id+"&a=overview";
			newframe.onload = function() { objHidden('loading-msg'); }
		} else {
			var newframe = document.createElement('iframe');
			newframe.id = 'frame1';
			newframe.name = 'frame1';
			newframe.frameBorder = 0;
			newframe.marginWidth = 0;
			newframe.marginHeight = 0;
			newframe.width = '100%';
			newframe.height = '90%';
			newframe.scrolling = 'auto';

			document.getElementById('itm-work-area').appendChild(newframe);
			htmlpassval(1,'isframeSet'); objDisplay('loading-msg');
			newframe.src = "store_mgt.php?thisItem="+id+"&a=overview";
			newframe.onload = function() { objHidden('loading-msg'); }
		}
		
	}

	function close_item_detail() {
		chgclass('item-mgt','fx-position-flow zind-2 motion btscr noscroll');
		chgclass('item-mgt-box','noshow');
	}

</script>

<div id="item-mgt" class="fx-position-flow zind-2 motion btscr noscroll" align="center">
	<div id="item-mgt-box" class="noshow">
		<div class="block-element top-push-10 bottom-push-10 right-pull-30 left-pull-50">
			<span class="ln-display-box float-left nc-width-50">
				<small id="loading-msg" class="white-font noshow">Loading item details..</small>
			</span>
			<span class="ln-display-box float-right">
				<a href="javascript:void(0)" class="light-grey-font ft-xsml-size" onclick="close_item_detail()">X Close</a>
			</span>
			<span class="block-element new-line-space"></span>
		</div>
		<div id="itm-work-area" class="cs-width-900 white-theme bottom-push-10 sml-rounded-button alignlt box-border-thick noscroll">
		</div>
	</div>
	<input type="hidden" id="isframeSet" value="0">
</div>