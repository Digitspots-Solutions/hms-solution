<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		Note: Use <u>Add+</u> to enable app services for HMS client
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="objDisplay('modal-box'); objDisplay('modal-box-1')">
		Add+
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

	$appSr = "CREATE TABLE IF NOT EXISTS app_service_tbl(
		id bigint(50) auto_increment,
		services varchar(250),
		status varchar(50),
		primary key(id)
	)";

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'ini.php?logs='.$_GET['logs'].'/mdlses='.$_GET['mdlses'];

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($appSr); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		

		$insert_dataproperty = array("services"=>$fieldset1,"status"=>$fieldset2);
		$insert_constrain = array("services"=>$fieldset1);
		$data_inserted = mysqli_data_insert('app_service_tbl',$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$post_result .= '<span class="red-font">Service was added successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		foreach ($_POST['checkers'] as $fkey) {
			
			$ukey = array("id"=>$fkey);
			$del = trash_record('app_service_tbl',$ukey);

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
			$post_result .= '<span class="red-font">App service was removed successfully</span>';
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

	$dataproperty = "id,services,status";
	$constrain = "";
	$row = "array";

	$dataCollect = mysqli_data_fetch('app_service_tbl',$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","services","status","enoth");
		$tcount = count($thproperty);

		$processbar="'process-bar'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-20">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $module_select="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["services"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["status"].'</td>';
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
	mysqli_data_check('app_service_tbl','(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

	$services = array(
		"Booking",
		"Point of Sales",
		"Recreation",
		"Housekeeping",
		"Accounting",
		"Sales",
		"Material Control",
		"Administration",
		"Reports"
	);

?>

<div id="modal-box" class="fx-position-flow fscr zind-1 top-pull-50 txp5-white motion" align="center">
	<div class="block-element nc-height-15">&nbsp;</div>
	<div id="modal-box-1" class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 sml-rounded-button obj-light-shadow motion">
		<form action="" method="post" autocomplete="off">
			<div class="form-block-label alignlt">
				<h3 class="nobold nomargin">App Services</h3><br>
			</div>
			<span class="block-element bottom-push-10">
				<select name="fieldset1" id="fieldset1" required="required">
					<option value="">CHOOSE?</option>
					<?php foreach($services as $addSr): ?>
						<option value="<?php echo strtoupper($addSr); ?>"><?php echo strtoupper($addSr); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<span class="block-element bottom-push-10">
				<input type="text" name="fieldset2" id="fieldset2" placeholder="ALLOW APP SERVICES" value="ALLOW APP SERVICES" required="required" readonly>
			</span>
			<br>
			
			<input type="submit" name="submitbutton" value="APPLY" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('modal-box'); objHidden('modal-box-1')">Cancel</a>
		</form>
	</div>
</div>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>