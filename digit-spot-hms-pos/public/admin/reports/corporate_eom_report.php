<?php $smdl = "reports"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-10">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: You can see the report regarding corporate debtors details
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	$server_month = date('F',strtotime($server_get_date));
	$server_year = date('Y',strtotime($server_get_date));

	$mm = range(1,12); $ext_year = $server_year + 1;
?>

<div class="block-element box-border-thick-bottom bottom-pull-5 bottom-push-30">
	<form action="" method="post">
		<span class="ln-display-box float-left right-pull-20">
			<h4 class="large nobold bottom-pull-5">Month</h4>
			<select name="month" id="month">
				
				<?php
					for($m=1; $m <= 12; $m++):
						$get_fMonth = month_intostring($m);
						if($get_fMonth == $server_month):
							?>
								<option value="<?php echo $m; ?>" selected="selected"><?php echo $get_fMonth; ?></option>
							<?php
						else:
							?>
								<option value="<?php echo $m; ?>"><?php echo $get_fMonth; ?></option>
							<?php
						endif;

						$get_fMonth = "";

					endfor;
				?>

			</select>
		</span>
		<span class="ln-display-box float-left right-pull-20">
			<h4 class="large nobold bottom-pull-5">Year</h4>
			<select name="year" id="year">
				
				<?php
					for($y=2022; $y <= $ext_year; $y++):
						if($y == $server_year):
							?>
								<option value="<?php echo $y; ?>" selected="selected"><?php echo $y; ?></option>
							<?php
						else:
							?>
								<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
							<?php
						endif;
					endfor;
				?>

			</select>
		</span>
		<span class="ln-display-box float-left left-pull-50">
			<h4 class="large nobold bottom-pull-5">&nbsp;</h4>
			<input type="submit" name="submitbutton" value="Run" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30">
		</span>
		<span class="ln-display-box float-left left-pull-20">
			<h4 class="large nobold bottom-pull-5">&nbsp;</h4>
			<input type="button" value="Print" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30" onclick="window.print()">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>



<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	//pagination controller

	/*if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = " ORDER BY name ASC LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 25;
		$additionalQuery = " ORDER BY name ASC LIMIT ".$pgstart.",".$pglimit;
	}*/

	$additionalQuery = " ORDER BY name ASC";

	$show = false;

	if(isset($_POST['submitbutton'])) {
		
		$show = true;

		$_SESSION['month'] = $_POST['month'];
		$_SESSION['year'] = $_POST['year'];

	} else {
		if(isset($_SESSION['month']) && isset($_SESSION['year'])) {
			
			$show = true;

			$_SESSION['month'] = $_SESSION['month'];
			$_SESSION['year'] = $_SESSION['year'];
		}
	}


	if($show == true) {

		$lastday = 30;

		switch($_SESSION['month']) {
			case 1:
				$lastday = 31;
				break;
			case 2:
				$lastday = 29;
				break;
			case 3:
				$lastday = 31;
				break;
			case 5:
				$lastday = 31;
				break;
			case 7:
				$lastday = 31;
				break;
			case 8:
				$lastday = 31;
				break;
			case 10:
				$lastday = 31;
				break;
			default:
				$lastday = 30;
				break;
		}

		$string_month = month_intostring($_SESSION['month']);

		$bizdate = $_SESSION['year'].'-01-01';
		$startdate = $_SESSION['year'].'-'.$mvl.'-01';
		$enddate = $_SESSION['year'].'-'.$mvl.'-'.$lastday;

		$dataproperty = "id,name,xcreditlimit,creditlimit,notifylimit";
		$constrain = array("deletedata"=>0); $row = "array";

		$dataCollect = mysqli_data_fetch($tbL58,$dataproperty,$constrain,$row);

		if(is_array($dataCollect)) {
		
			$thproperty = array("company name","opening balance","consumption","recovery","closing balance");
			$tcount = count($thproperty);

			$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
			$htmlresult .= '<table cellpadding="0" cellspacing="0">';
			$htmlresult .= '<tr>';
			
			$thu=0; $uclass="";
			
			foreach($thproperty as $th) {
				
				$thu += 1;
				
				if($tcount == $thu) { $uclass=''; }
				else { $uclass='class="box-border-thick-right"'; }
				
				if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
				elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
				else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
			}
			
			$htmlresult .= '</tr>';
			
			//$num=$pgstart; $g=""; $dataid="";
			$num=0; $g=""; $dataid="";

			$total_consumed = ""; $opening_bal = ""; $closing_bal = ""; $total_paid = ""; $balance_now = "";
			$g_total_consumed = ""; $g_total_pay = ""; $previous_consumption = ""; $previous_payment = "";

			foreach($dataCollect as $theader => $tdata) {
				
				$num += 1;
				$g = $num / 2;

				$dataid = $tdata['id'];

				$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

				$sqlset = "SUM(amount)";

				$a_queryset = "cspgid={$dataid} AND transaction_type='Debit' AND datelogged >= '{$bizdate}' AND datelogged <= '{$enddate}' AND isreversed=0 AND deletedata=0"; $g_total_consumed = mysqli_arithmetic_data($tbL63,$sqlset,$a_queryset);

				$a2_queryset = "cspgid={$dataid} AND transaction_type='Credit' AND datelogged >= '{$bizdate}' AND datelogged <= '{$enddate}' AND paymode > 0 AND isreversed=0 AND deletedata=0"; $g_total_pay = mysqli_arithmetic_data($tbL63,$sqlset,$a2_queryset);

				$queryset = "cspgid={$dataid} AND transaction_type='Debit' AND datelogged >= '{$startdate}' AND datelogged <= '{$enddate}' AND isreversed=0 AND deletedata=0"; $total_consumed = mysqli_arithmetic_data($tbL63,$sqlset,$queryset);

				$queryset2 = "cspgid={$dataid} AND transaction_type='Credit' AND datelogged >= '{$startdate}' AND datelogged <= '{$enddate}' AND paymode > 0 AND isreversed=0 AND deletedata=0"; $total_pay = mysqli_arithmetic_data($tbL63,$sqlset,$queryset2);

				$previous_consumption = $g_total_consumed - $total_consumed;
				$previous_payment = $g_total_pay - $total_pay;

				$opening_bal = $previous_payment - $previous_consumption;
				$closing_bal = $g_total_pay - $g_total_consumed;
				 
				$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata['name'].'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">&#8358; '.number_format($opening_bal,2).'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">&#8358; '.number_format($total_consumed,2).'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">&#8358; '.number_format($total_pay,2).'</td>';
				$htmlresult .= '<td width="100px" align="center" class="">&#8358; '.number_format($closing_bal,2).'</td>';
				$htmlresult .= '</tr>';
			}

			$htmlresult .= '</table>';
			$htmlresult .= '</div>';
		}

		?>

			<div id="section-to-print">

				<div class="bottom-push-20" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
					</div>
					<div class="cs-width-400 margin-auto-ct alignct">
						<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold nomargin">Corporate End of Month Report - <?php echo $string_month.' '.$_SESSION['year']; ?></h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
					</div>
				</div>

				<?php echo $htmlresult; ?>

			</div>

			<br><br>

		<?php


		//paginate this page

		/*$additionalQuery = "";
		$ukey = array("deletedata"=>0);
		mysqli_data_check($tbL58,'(*)',$ukey);
		$totalcount = $numOfrows;

		$paginate = data_pagenation(25,0,$totalcount);
		if(isset($paginate) && !empty($paginate)) {
			echo $paginate;
		}*/

		//end of pagination
	}

?>

<!--<div id="pageurl" class="noshow"><?php //echo $pageurl; ?></div>-->