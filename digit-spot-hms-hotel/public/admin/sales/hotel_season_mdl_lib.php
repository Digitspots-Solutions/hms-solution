<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create season for the hotel then set your tariff to initiate the season. Click <u>add season</u> button to start
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="create_newseason(); setTimeout(dodata('select-col-1-','eget-season-mode-list',1,'dropbox'),1000); objDisplay('ctrlbx')">
		Add Season
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


	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_75); //create a table for this post

		$fieldset1 = $_POST['modes'];
		$fieldset2 = $_POST['startdates'];
		$fieldset3 = $_POST['enddates'];
		
		$isdata = 0;

		for($r=0; $r < count($fieldset1); $r++)
		{
			if($fieldset1[$r] != '') {
				$room_arr = array("modeid"=>$fieldset1[$r],"startseason"=>$fieldset2[$r],"endseason"=>$fieldset3[$r]);
				$constrain = array("modeid"=>$fieldset1[$r],"startseason"=>$fieldset2[$r],"endseason"=>$fieldset3[$r]);
				$data_inserted = mysqli_data_insert($tbL79,$room_arr,$constrain);

				if(isset($data_inserted) && $data_inserted == 2) { $isdata += 1; }
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($isdata) && $isdata >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new season","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

?>

<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
	<div class="block-element sml-rounded-button box-border-thick pads15">
		<div class="block-element sml-rounded-button noscroll">
			<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="50px" class="box-border-thick-right" align="center">&nbsp;</th>
				<th width="200px" class="box-border-thick-right" align="center">Season Mode</th>
				<th width="100px" class="box-border-thick-right" align="center">Start Season From</th>
				<th width="100px" class="box-border-thick-right" align="center">End Season At</th>
			</tr>
			<tbody id="datasheet"></tbody>
			</table>
		</div>
		<input type="hidden" id="rwcounter" value="0">

		<br><br>
		<div id="ctrlbx" class="noshow alignct">
			<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
		</div>
	</div>
</form>

<br><br>

<script>

function create_newseason()
{
	var contr,tr,td1,td2,td3,td4,select1,opt1,txt1,txt2,obj,numbr,curnumbr;
	var select1Opt,select2Opt,select3Opt;

	curnumbr = document.getElementById('rwcounter');
	contr = document.getElementById('datasheet');

	tr = document.createElement('tr');
	td1 = document.createElement('td');
	td2 = document.createElement('td');
	td3 = document.createElement('td');
	td4 = document.createElement('td');
	
	select1 = document.createElement('select');
	opt1 = document.createElement('option');

	txt1 = document.createElement('input');
	txt2 = document.createElement('input');
	
	obj = document.createElement('span');

	numbr = eval(curnumbr.value) + 1; //generate new row number

	obj.id = 'span'+numbr;
	obj.className = 'block-element alignct';
	obj.innerHTML = numbr;
	td1.appendChild(obj);

	select1.id = 'select-col-1-'+numbr;
	select1.name = 'modes[]';
	opt1.value = '';
	opt1.text = 'Modes';
	select1.appendChild(opt1);
	td2.appendChild(select1);

	txt1.type = 'date';
	txt1.name = 'startdates[]';
	td3.appendChild(txt1);

	txt2.type = 'date';
	txt2.name = 'enddates[]';
	td4.appendChild(txt2);

	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);
	
	contr.appendChild(tr);
	curnumbr.value = numbr;
}

function dodata(str,sses,id,sopt) {
	var curnumbr = document.getElementById('rwcounter').value;
	var select_id = str+curnumbr;

	getdata(select_id,sses,id,sopt);
}

</script>


<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = $_POST['fieldset1'];
		$fieldset2 = $_POST['fieldset2'];
		$fieldset3 = $_POST['fieldset3'];
		
		$data_query = array("id"=>$fieldset3);
		$datasets = array("startseason"=>$fieldset1,"endseason"=>$fieldset2);
		$data_inserted = mysqli_data_update($tbL79,$datasets,$data_query);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently to save changes to hotel season time","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes made successfully</span>';
		}

		$update_result .= '</div>';

		echo $update_result;
	}
	
	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = trash_record($tbL79,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove inactive season","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	$keywords="";

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

	$dataproperty = "id,modeid,startseason,endseason,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL79,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","season mode","start season","end season","tariff","enoth","noth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $hotelseason="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$hotelseason = idget_data($tbL78,$tdata["modeid"],'legendname');
			
			if($tdata["status"] == 'InActive') {
				$disabled = '';
				$tariff_status = 'Not Set';
			} else {
				$disabled = 'disabled="disabled"';
				$tariff_status = 'On';
			}
			
			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$hotelseason.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.date("d/m/Y",strtotime($tdata["startseason"])).'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.date("d/m/Y",strtotime($tdata["endseason"])).'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right blue-font">'.$tariff_status.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right blue-font"><a href="?logs='.$logs.'&cdate='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Change Date</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'" '.$disabled.'></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['cdate']) && $_GET['cdate'] >= 1) && ($_GET['cdate'] == $dataid))
			{
				$fieldset = escape_data($_GET['cdate']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Changing Season Time</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Start Season</small>';
				$htmlresult .= '<input type="date" name="fieldset1" id="fieldset1" value="'.$tdata["startseason"].'" required="required">';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">End Season</small>';
				$htmlresult .= '<input type="date" name="fieldset2" id="fieldset2" value="'.$tdata["endseason"].'" required="required">';
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

	$additionalQuery = $keywords;
	mysqli_data_check($tbL79,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

	include "../../includes/general_routine.php";

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>