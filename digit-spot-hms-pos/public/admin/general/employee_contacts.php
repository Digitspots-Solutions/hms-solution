<?php 
	$smdl = ""; $logs = escape_data($_GET['logs']);
	$usr = select_dt_fetch('status','Active',$tbL12,'id','department');
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left nc-width-55">
		<div class="ln-display-box float-left nc-width-5 top-pull-10">
			<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		</div>
		<div class="ln-display-box float-left nc-width-95 left-pull-10 right-pull-30">
			Note: select the department that you want to see their employee details and click on <u>search</u> button.
		</div>
		<div class="block-element new-line-space">
		</div>
 	</span>
 	<span class="ln-display-box float-right nc-width-45">
		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<div class="ln-display-box float-left nc-width-60">
				<select name="depts" id="depts" required="required">
					<option value="" selected="selected">Choose Department</option>
					<?php echo $usr; ?>
				</select>
			</div>
			<div class="ln-display-box float-left nc-width-40 alignrt">
				<input type="submit" name="searchbutton" value="Search" class="submit pads10 black-white-state rounded-button nc-width-90">
			</div>
			<div class="block-element new-line-space">
			</div>
		</form>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>


<?php

	if(isset($_POST['searchbutton']))
	{
		$selected_department=idget_data($tbL12,$_POST['depts'],"department");

		$additionalQuery = " ORDER BY staffname ASC";
		$dataproperty = "id,staffnumber,staffname,username,emailaddress,department,role,mobile,primarycontact,isforcedip,forceip,datehire,dateofbirth,qualification,salary,status,worknumber,homenumber";
		$constrain = array("department"=>$_POST['depts'],"deletedata"=>0,"uaccess"=>"limited");
		$row = "array";

		$dataCollect = mysqli_data_fetch($tbL7,$dataproperty,$constrain,$row);

		if(is_array($dataCollect))
		{
			$thproperty = array("employee name","department","role","extn","mobile number","work number","home number");
			$tcount = count($thproperty);

			$htmlresult .= '<div class="block-element bottom-push-20 alignrt right-pull-20"><a href="javascript:window.print()" class="blue-font"><b class="fa-print nobold black-font"></b> Print</a></div>';
			$htmlresult .= '<div class="block-element bottom-push-5 left-pull-10"><h3 class="large">+ '.$selected_department.'</h3></div>';
			$htmlresult .= '<div id="print-section" class="block-element sml-rounded-button noscroll">';
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
			
			$num=$pgstart; $g=""; $dataid=""; $department=""; $extn=""; $role=""; $mylastlogin=""; $staffname="";

			foreach($dataCollect as $theader => $tdata)
			{
				$num += 1;
				$g = $num / 2;

				$dataid = $tdata["id"];

				$department=idget_data($tbL12,$tdata["department"],"department");
				$extn=idget_data($tbL12,$tdata["department"],"extn");

				$role=idget_data($tbL4,$tdata["role"],"role");

				$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
						
				$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
				$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["staffname"].'</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$department.'</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$role.'</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$extn.'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["worknumber"].'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["homenumber"].'</td>';
				$htmlresult .= '</tr>';
			}
			
			$htmlresult .= '</table>';
			$htmlresult .= '</div>';
			
		}
		else
		{
			$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no related records available for your search..</small></div>';
		}

		echo $htmlresult;
	}

?>