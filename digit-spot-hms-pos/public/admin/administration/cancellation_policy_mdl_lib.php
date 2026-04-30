<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<script>

	function changetime() {
		if(document.getElementById('fieldset3').value == 'Default') {
			document.getElementById('frombx').style.display='none';
			document.getElementById('tobx').style.display='none';
			document.getElementById('fieldset4').innerHTML='<option></option>';
			document.getElementById('fieldset5').innerHTML='<option></option>';
		} else if(document.getElementById('fieldset3').value == 'In Hours') {
			document.getElementById('sftime').innerHTML='Hours';
			document.getElementById('eftime').innerHTML='Hours';

			var i,hr; hr='';

			for(i=0; i <= 24; i++) {
				hr += '<option value="'+i+'">'+i+'</option>';
			}

			document.getElementById('fieldset4').innerHTML=hr;
			document.getElementById('fieldset5').innerHTML=hr;
		} else if(document.getElementById('fieldset3').value == 'In Days') {
			document.getElementById('sftime').innerHTML='Days';
			document.getElementById('eftime').innerHTML='Days';

			var i,dy; dy='';

			for(i=1; i <= 90; i++) {
				dy += '<option value="'+i+'">'+i+'</option>';
			}

			document.getElementById('fieldset4').innerHTML=dy;
			document.getElementById('fieldset5').innerHTML=dy;
		}
	}

</script>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new cancellation policy by clicking <u>new cancellation policy</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Cancellation Policy
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
		createDatabasetable($var_tbl_29); //create a table for this post
		
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);

		if($fieldset3 == 'Default') {
			$fieldset4 = 0;
			$fieldset5 = 0;
			$fieldset7 = 'No time limit';
		} else {
			$fieldset4 = $_POST['fieldset4'];
			$fieldset5 = $_POST['fieldset5'];

			if($fieldset3 == 'In Hours') {
				$fieldset7 = 'Between '.$fieldset4.' to '.$fieldset5.' hours';
			} elseif($fieldset3 == 'In Days') {
				$fieldset7 = 'Between '.$fieldset4.' to '.$fieldset5.' days before check-in time';
			}
		}

		$fieldset6 = escape_data($_POST['fieldset6']);

		$insert_dataproperty = array("policyname"=>ucwords(strtolower($fieldset1)),"discount"=>$fieldset2,"cancellationtype"=>$fieldset3,"ftime"=>$fieldset4,"ttime"=>$fieldset5,"isactive"=>$fieldset6,"detail"=>$fieldset7);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL31,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new cancellation policy","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
							<h3 class="nomargin">Creating New Cancellation Policy</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter policy name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<div class="ln-display-box float-left nc-width-90">
								<input type="number" name="fieldset2" id="fieldset2" step="any" placeholder="Enter discount" required="required">
							</div>
							<div class="ln-display-box float-left nc-width-10 left-pull-5 top-pull-10">
								%
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset3" id="fieldset3" required="required" onchange="changetime()">
								<option value="" selected="selected">Cancellation Type</option>
								<option value="In Hours">In Hours</option>
								<option value="In Days">In Days</option>
								<option value="Default">Default</option>
							</select>
						</span>
						<span class="block-element bottom-push-10" id="frombx">
							<div class="ln-display-box float-left nc-width-20 top-pull-10">
								From
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<select name="fieldset4" id="fieldset4">
									<option></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-20 left-pull-5 top-pull-10">
								<small id="sftime"><b>Time</b></small>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10" id="tobx">
							<div class="ln-display-box float-left nc-width-20 top-pull-10">
								To
							</div>
							<div class="ln-display-box float-left nc-width-50">
								<select name="fieldset5" id="fieldset5">
									<option></option>
								</select>
							</div>
							<div class="ln-display-box float-left nc-width-20 left-pull-5 top-pull-10">
								<small id="eftime"><b>Time</b></small>
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset6" id="fieldset6" required="required">
								<option value="" selected="selected">Isactive</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
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

		if($fieldset3 == 'Default') {
			$fieldset4 = 0;
			$fieldset5 = 0;
			$fieldset7 = 'No time limit';
		} else {
			$fieldset4 = $_POST['fieldset4'];
			$fieldset5 = $_POST['fieldset5'];

			if($fieldset3 == 'In Hours') {
				$fieldset7 = 'Between '.$fieldset4.' to '.$fieldset5.' hours';
			} elseif($fieldset3 == 'In Days') {
				$fieldset7 = 'Between '.$fieldset4.' to '.$fieldset5.' days before check-in time';
			}
		}

		$fieldset6 = escape_data($_POST['fieldset6']);
		$fieldset8 = escape_data($_POST['fieldset8']);

		$insert_dataproperty = array("policyname"=>ucwords(strtolower($fieldset1)),"discount"=>$fieldset2,"cancellationtype"=>$fieldset3,"ftime"=>$fieldset4,"ttime"=>$fieldset5,"isactive"=>$fieldset6,"detail"=>$fieldset7);
		$insert_constrain = array("id"=>$fieldset8);
		$data_inserted = mysqli_data_update($tbL31,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit cancellation policy details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND policyname LIKE '".escape_data($_POST['search'])."%'";
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

	$dataproperty = "id,policyname,discount,cancellationtype,detail,isactive,ftime,ttime";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL31,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","policy","cancellation percent","cancellation type","time limit","is active","noth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
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
		
		$num=$pgstart; $g=""; $dataid="";
		
		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["policyname"].'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["discount"].' %</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["cancellationtype"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["detail"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["isactive"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit</a></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);
				
				if($tdata["cancellationtype"] == 'Default') {
					$limt = '';
				} elseif($tdata["cancellationtype"] == 'In Hours') {
					$limt = 'Hours';
				} elseif($tdata["cancellationtype"] == 'In Days') {
					$limt = 'Days';
				}


				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["policyname"].'</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Policy Name</small>';
				$htmlresult .= '<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter policy name" value="'.$tdata["policyname"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Cancellation Percentage (%)</small>';
				$htmlresult .= '<input type="number" name="fieldset2" id="fieldset2" step="any" placeholder="Enter discount" value="'.$tdata["discount"].'">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Cancellation Type</small>';
				$htmlresult .= '<select name="fieldset3" id="fieldset3" required="required" onchange="changetime()">';
				$htmlresult .= '<option value="'.$tdata["cancellationtype"].'" selected="selected">'.$tdata["cancellationtype"].'</option>';
				$htmlresult .= '<option value="In Hours">In Hours</option>';
				$htmlresult .= '<option value="In Days">In Days</option>';
				$htmlresult .= '<option value="Default">Default</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10" id="frombx">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20 top-pull-10 alignct">';
				$htmlresult .= 'From';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<select name="fieldset4" id="fieldset4">';
				$htmlresult .= '<option>'.$tdata["ftime"].'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20 left-pull-5 top-pull-10">';
				$htmlresult .= '<small id="sftime"><b>'.$limt.'</b></small>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10" id="tobx">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20 top-pull-10 alignct">';
				$htmlresult .= 'To';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<select name="fieldset5" id="fieldset5">';
				$htmlresult .= '<option>'.$tdata["ttime"].'</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-20 left-pull-5 top-pull-10">';
				$htmlresult .= '<small id="eftime"><b>'.$limt.'</b></small>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space">';
				$htmlresult .= '</div>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">IsActive</small>';
				$htmlresult .= '<select name="fieldset6" id="fieldset6" required="required">';
				$htmlresult .= '<option value="'.$tdata["isactive"].'" selected="selected">'.$tdata["isactive"].'</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset8" id="fieldset8" value="'.$dataid.'">';
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
	mysqli_data_check($tbL31,'(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(25,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>