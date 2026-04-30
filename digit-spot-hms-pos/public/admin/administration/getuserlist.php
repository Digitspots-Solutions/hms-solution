<?php
	
	$inner_htmlresult='';

	$additionalQuery = "";
	$dataproperty = "id,staffnumber,staffname,username,emailaddress,mobile,status,worknumber";
	$constrain = array("role"=>$fieldset,"deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL7,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","login id","staffnumber","staffname","mobile","work number","status");
		$tcount = count($thproperty);

		$inner_htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$inner_htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$inner_htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $inner_htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $inner_htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $inner_htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$inner_htmlresult .= '</tr>';
		
		$unum=0; $ug="";

		foreach($dataCollect as $theader => $tdata)
		{
			$unum += 1;
			$ug = $unum / 2;

			$trcolor = is_int($ug) ? '#F9F9F9' : '#D1E0ED';
					
			$inner_htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$inner_htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$unum.'</td>';
			$inner_htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["username"].'</td>';
			$inner_htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$tdata["staffnumber"].'</td>';
			$inner_htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["staffname"].'</td>';
			$inner_htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["mobile"].'</td>';
			$inner_htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["worknumber"].'</td>';
			$inner_htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$inner_htmlresult .= '</tr>';
		}
		
		$inner_htmlresult .= '</table>';
		$inner_htmlresult .= '</div>';
	}
	else
	{
		$inner_htmlresult .= '<div class="top-pull-10 bottom-pull-10 alignct"><small class="dark-grey-font">There are no users added!</small></div>';
	}
?>