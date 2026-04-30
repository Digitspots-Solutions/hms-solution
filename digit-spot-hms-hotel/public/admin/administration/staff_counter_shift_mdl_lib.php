<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: you can check all staff counters and shifts history here. Use the search form to display log that you want to see
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$pageurl = 'workspace.php?logs='.$logs;

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	//$additionalQuery = " GROUP BY userid";
	//$extable=$tbL7; $extcols="staffname";
	//$users = groupSelect_fetch('deletedata',0,$tbL23,'userid');

	
	$additionalQuery = " ORDER BY staffname ASC";
	$keydata = array("deletedata"=>0,"uaccess"=>"limited");
	$fetch_data = mysqli_data_fetch($tbL7,'id,staffname',$keydata,'array');

	if(is_array($fetch_data) && count($fetch_data) > 0) {
		foreach($fetch_data as $theader => $tdata) {
			$select .= '<option value="'.$tdata['id'].'">'.$tdata['staffname'].'</option>';
		}
	} else {
		$select .= '<option value="">No options</option>';
	}

	$get_users = $select;

	$additionalQuery = "";

?>

<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
	<div class="block-element pads20 sml-rounded-button box-border-thick">
		<span class="ln-display-box float-left nc-width-20 right-push-10">
			<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">User</small>
			<select name="fieldset4" id="fieldset4" required="required">
				<option value="All">All</option>
				<?php echo $get_users; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-push-10">
			<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">Date from</small>
			<input type="date" name="fieldset1" id="fieldset1">
		</span>
		<span class="ln-display-box float-left nc-width-20 right-push-10">
			<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">Date to</small>
			<input type="date" name="fieldset2" id="fieldset2">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-push-10">
			<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">Status</small>
			<select name="fieldset3" id="fieldset3" required="required">
				<option value="All">All</option>
				<option value="Open">Open</option>
				<option value="Closed">Closed</option>
			</select>
		</span>
		<span class="ln-display-box float-right nc-width-20 alignrt">
			<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">&nbsp;</small>
			<input type="submit" name="searchbutton" value=" Search " class="submit pads10 black-white-state sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</form>

<?php

	if(isset($_POST['searchbutton'])) {
		
		if((isset($_POST['fieldset1']) && !empty($_POST['fieldset1'])) && (isset($_POST['fieldset2']) && !empty($_POST['fieldset2']))) {
			$date_query = " AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'";
		} else {
			$date_query = "";
		}
		
		if(isset($_POST['fieldset3']) && $_POST['fieldset3'] == 'All') {
			$logcategory = "";
		} else {
			$logcategory = " AND status = '".$_POST['fieldset3']."'";
		}

		if(isset($_POST['fieldset4']) && $_POST['fieldset4'] == 'All') {
			$loguser = "";
		} else {
			if(!empty($_POST['fieldset4']) && $_POST['fieldset4'] > 0) {
				$loguser = " AND userid = '".$_POST['fieldset4']."'";
			} else {
				$loguser = "";
			}
		}

		$keywords=$loguser.$logcategory.$date_query;

		$_SESSION['loguser'] = $_POST['fieldset4'];
		$_SESSION['logcategory'] = $_POST['fieldset3'];
		$_SESSION['startdate'] = $_POST['fieldset1'];
		$_SESSION['endate'] = $_POST['fieldset2'];
		$_SESSION['extendget'] = 1;

	} else {
		
		if(isset($_SESSION['extendget']) && $_SESSION['extendget'] == 1) {
			
			if((isset($_SESSION['startdate']) && isset($_SESSION['endate'])) && (!empty($_SESSION['startdate']) && !empty($_SESSION['endate']))) {
				$date_query = " AND datelogged BETWEEN '".$_SESSION['startdate']."' AND '".$_SESSION['endate']."'";
			} else {
				$date_query = "";
			}

			if(isset($_SESSION['logcategory']) && $_SESSION['logcategory'] == 'All') {
				$logcategory = "";
			} else {
				$logcategory = " AND status = '".$_SESSION['logcategory']."'";
			}

			if(isset($_SESSION['loguser']) && $_SESSION['loguser'] == 'All') {
				$loguser = "";
			} else {
				if(!empty($_SESSION['loguser']) && $_SESSION['loguser'] > 0) {
					$loguser = " AND userid = '".$_SESSION['loguser']."'";
				} else {
					$loguser = "";
				}
			}

			$keywords=$loguser.$logcategory.$date_query;

		} else {
			$keywords="";

			$_SESSION['loguser'] = 0;
			$_SESSION['logcategory'] = null;
			$_SESSION['startdate'] = null;
			$_SESSION['endate'] = null;
			$_SESSION['extendget'] = 0;
		}
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

	$dataproperty = "id,userid,shiftid,counterid,resumptiontime,closetime,datelogged,dateclosed,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL23,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("enoth","noth","user","shift","counter","resumption date","resumption time","closed date","closed time","status");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		//$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		//$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
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
		
		$num=$pgstart; $g=""; $dataid=""; $staffid=""; $staff_name=""; $shift_name=""; $counter_name=""; $staff_role=""; $role_name="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata['id'];

			$additionalQuery = "";
			
			$staff_name = idget_data($tbL7,$tdata['userid'],'staffname');
			$staff_role = idget_data($tbL7,$tdata['userid'],'role');
			$role_name = idget_data($tbL4,$staff_role,'role');

			$shift_name = idget_data($tbL20,$tdata['shiftid'],'shiftname');

			if(!empty($tdata['counterid']) && $tdata['counterid'] > 0) { $counter_name = idget_data($tbL19,$tdata['counterid'],'countername'); } else { $counter_name = "Non-counter User"; }
			

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$staff_name.' ('.$role_name.')</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$shift_name.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$counter_name.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.date("d/m/y",strtotime($tdata['datelogged'])).'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata['resumptiontime'].'</td>';
			
			if(!empty($tdata['dateclosed']) && $tdata['dateclosed'] !== NULL) {
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.date("d/m/y",strtotime($tdata['dateclosed'])).'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata['closetime'].'</td>';
			} else {
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">---</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">---</td>';
			}


			if($tdata['status'] == 'Open') {
				$htmlresult .= '<td width="80px" align="center" class="forest-green-font default-text-font-bold box-border-thick-right">'.$tdata['status'].'</td>';
			} elseif($tdata['status'] == 'Closed') {
				$htmlresult .= '<td width="80px" align="center" class="light-red-font default-text-font-bold box-border-thick-right">'.$tdata['status'].'</td>';
			}

			$htmlresult .= '</tr>';
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">Display record using the search form</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = $keywords;
	mysqli_data_check($tbL23,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>