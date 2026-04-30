<?php

$logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can check activities logged by users
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30">
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<small class="block-element bottom-push-7"><b>Account Audit Trail</b></small>
		<span class="ln-display-box float-left right-push-30">
			<small class="block-element dark-grey-font bottom-push-7">Action Date</small>
			<select name="period" id="period" onchange="dateStat()">
				<option value="Today">Today</option>
				<option value="Custom Date">Custom Date</option>
			</select>
		</span>
		<span class="ln-display-box float-left right-push-30">
			<div id="custom-date" class="noshow">
				<div class="ln-display-box float-left right-push-10">
					<small class="block-element dark-grey-font bottom-push-7">From date</small>
					<input type="date" name="startdate" id="startdate" value="<?php echo $server_get_date; ?>">
				</div>
				<div class="ln-display-box float-left right-push-10">
					<small class="block-element dark-grey-font bottom-push-7">To date</small>
					<input type="date" name="endate" id="endate" value="<?php echo $server_get_date; ?>">
				</div>
			</div>
		</span>
		<span class="ln-display-box float-left right-push-30">
			<small class="block-element dark-grey-font bottom-push-7">Action Type</small>
			<select name="actype" id="actype">
				<option value="" selected="selected">All</option>
				<option value="credit">Credit</option>
				<option value="debit">Debit</option>
				<option value="reverse">Reverse</option>
				<option value="extension">Extension</option>
				<option value="discount">Discount</option>
				<option value="room tariff">Tariff Change</option>
			</select>
		</span>
		<span class="ln-display-box float-right">
			<p class="bottom-pull-15"></p>
			<input type="submit" name="checklogbutton" value="Run" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button"> &nbsp; <input type="button" value="Print" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 black-white-state sml-rounded-button" onclick="window.print()">
		</span>
		<span class="block-element new-line-space">
		</span>
	</form>
</div>

<?php
	
	$show_report = 0;

	if(isset($_POST['checklogbutton'])) {
		
		$show_report = 1; $keywords = "";

		if(isset($_POST['period']) && !empty($_POST['period'])) {
			
			$custom_date_start=""; $custom_date_end="";
			
			switch ($_POST['period']) {
				case 'Today':
					$keywords .= " AND datelogged BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($server_get_date))." AND ".date('d-m-Y',strtotime($server_get_date));
					break;

				case 'Tomorrow':
					$get_tomorrow_date = date('Y-m-d',strtotime($server_get_date . ' +2 days'));
					$keywords .= " AND datelogged BETWEEN '".$get_tomorrow_date."' AND '".$get_tomorrow_date."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($get_tomorrow_date))." AND ".date('d-m-Y',strtotime($get_tomorrow_date));
					break;
				
				case 'Custom Date':
					$custom_date_start=$_POST['startdate']; $custom_date_end=$_POST['endate'];
					$keywords .= " AND datelogged BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($_POST['startdate']))." AND ".date('d-m-Y',strtotime($_POST['endate']));
					break;

				default:
					$keywords .= "";
					break;
			}
		}

		if(isset($_POST['actype']) && !empty($_POST['actype'])) {
			$keywords .= " AND remark_tag='{$_POST['actype']}'";
		} else {
			$keywords .= " AND remark_tag IN('credit','debit','discount','extension','room tariff','reverse')";
		}

	} else {
		$query = "";
		$show_report = 0;
	}

	
	if($show_report == 1) {
		
		$sql = "SELECT * FROM {$tbL132} WHERE deletedata=0".$keywords;
		$getSQL = wgetSQL($sql);

		?>
			<div id="section-to-print">
				<div class="bottom-push-30" align="center">
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">Account Audit Trail Log</h3>
					<h4 class="large nobold nomargin"><?php echo $report_date; ?></h4>
					<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
				</div>

				<table cellpadding="3" cellspacing="0" border="1">
					<tr>
						<td class="alignct default-text-font-bold">Audit Type</td>
						<td class="alignct default-text-font-bold">Audit Action Type</td>
						<td class="alignct default-text-font-bold">Effected In</td>
						<td width="300px" class="alignct default-text-font-bold">Description</td>
						<td class="alignct default-text-font-bold">Reference</td>
						<td class="alignct default-text-font-bold">Datetime</td>
						<td class="alignct default-text-font-bold">Created By</td>
					</tr>

					<?php
						if(is_array($getSQL)) {
							
							$createdby = ""; $remark = "";

							foreach($getSQL as $key => $val) {
								
								$createdby = idget_data($tbL7,$val['userid'],'staffname');

								if(strlen($val['activities']) > 45 && strstr($val['activities'],',')) {
									$remark = str_replace(',',' ',$val['activities']);
								} else {
									$remark = $val['activities'];
								}

								?>
									<tr>
										<td class="alignct"><?php echo $val['app_tag']; ?></td>
										<td class="alignct"><?php echo ucwords($val['remark_tag']); ?></td>
										<td class="alignct"><?php echo $val['session_tag']; ?></td>
										<td width="300px" class="alignlt"><div class="noscroll" style="word-wrap: break-word !important"><?php echo wordwrap($remark,45,"<br>\n"); ?></div></td>
										<td class="alignct"><?php echo $val['booking_number']; ?></td>
										<td class="alignct"><?php echo date('d/m/Y',strtotime($val['datelogged'])).' '.$val['timelogged']; ?></td>
										<td class="alignct"><?php echo $createdby; ?></td>
									</tr>
								<?php
							}
						}
					?>

				</table>
			</div>
		<?php
	}


	$pos_stores = select_dt_fetch('iscounter','Yes',$tbL14,'id','posname');

?>

<p class="top-pull-5">&nbsp;</p>
<p class="box-border-thick-top top-pull-5 bottom-pull-5"></p>
<p>&nbsp;</p>

<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	<small class="block-element bottom-push-7"><b>Unfinished Outlet Transactions</b></small>
	<span class="ln-display-box float-left right-push-30">
		<small class="block-element dark-grey-font bottom-push-7">Action Date</small>
		<select name="period" id="period-2" onchange="dateStat2()">
			<option value="Today">Today</option>
			<option value="Custom Date">Custom Date</option>
		</select>
	</span>
	<span class="ln-display-box float-left right-push-30">
		<div id="custom-date-2" class="noshow">
			<div class="ln-display-box float-left right-push-10">
				<small class="block-element dark-grey-font bottom-push-7">From date</small>
				<input type="date" name="startdate" id="startdate" value="<?php echo $server_get_date; ?>">
			</div>
			<div class="ln-display-box float-left right-push-10">
				<small class="block-element dark-grey-font bottom-push-7">To date</small>
				<input type="date" name="endate" id="endate" value="<?php echo $server_get_date; ?>">
			</div>
		</div>
	</span>
	<span class="ln-display-box float-left right-push-30">
		<small class="block-element dark-grey-font bottom-push-7">Outlets</small>
		<select name="outlet" id="outlet">
			<option value="0">All</option>
			<?php echo $pos_stores; ?>
		</select>
	</span>
	<span class="ln-display-box float-left right-push-30">
		<small class="block-element dark-grey-font bottom-push-7">Action Type</small>
		<select name="actype" id="actype">
			<option value="" selected="selected">Open Order</option>
		</select>
	</span>
	<span class="ln-display-box float-right">
		<p class="bottom-pull-15"></p>
		<input type="submit" name="checkvrbutton" value="Run" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button"> &nbsp; <input type="button" value="Print" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 black-white-state sml-rounded-button" onclick="window.print()">
	</span>
	<span class="block-element new-line-space">
	</span>
</form>


<?php
	
	$show_reportx = 0;

	if(isset($_POST['checkvrbutton'])) {
		
		$show_reportx = 1; $keywords = "";

		if(isset($_POST['period']) && !empty($_POST['period'])) {
			
			$custom_date_start=""; $custom_date_end="";
			
			switch ($_POST['period']) {
				case 'Today':
					$keywords .= " AND datelogged BETWEEN '".$server_get_date."' AND '".$server_get_date."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($server_get_date))." AND ".date('d-m-Y',strtotime($server_get_date));
					break;

				case 'Tomorrow':
					$get_tomorrow_date = date('Y-m-d',strtotime($server_get_date . ' +2 days'));
					$keywords .= " AND datelogged BETWEEN '".$get_tomorrow_date."' AND '".$get_tomorrow_date."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($get_tomorrow_date))." AND ".date('d-m-Y',strtotime($get_tomorrow_date));
					break;
				
				case 'Custom Date':
					$custom_date_start=$_POST['startdate']; $custom_date_end=$_POST['endate'];
					$keywords .= " AND datelogged BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";
					$report_date = "BETWEEN ".date('d-m-Y',strtotime($_POST['startdate']))." AND ".date('d-m-Y',strtotime($_POST['endate']));
					break;

				default:
					$keywords .= "";
					break;
			}
		}

		if(isset($_POST['outlet']) && !empty($_POST['outlet'])) {
			$keywords .= " AND posid={$_POST['outlet']}";
		}

		$keywords .= " AND (status IN('Pending') OR payment IN('Pending'))";

	} else {
		$query = "";
		$show_reportx = 0;
	}

	
	if($show_reportx == 1) {
		
		$sql = "SELECT * FROM {$tbL100} WHERE deletedata=0".$keywords;
		$getSQL = wgetSQL($sql);

		?>
			<div id="section-to-print">
				<div class="bottom-push-30" align="center">
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">Unfinished Transaction Log</h3>
					<h4 class="large nobold nomargin"><?php echo $report_date; ?></h4>
					<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
				</div>

				<table cellpadding="3" cellspacing="0" border="1">
					<tr>
						<td class="alignct default-text-font-bold">&nbsp;</td>
						<td class="alignct default-text-font-bold">Outlet</td>
						<td class="alignct default-text-font-bold">Order Number</td>
						<td class="alignct default-text-font-bold">Reference Number</td>
						<td class="alignct default-text-font-bold">Bill Amount</td>
						<td class="alignct default-text-font-bold">Food Type</td>
						<td class="alignct default-text-font-bold">Billed As</td>
						<td class="alignct default-text-font-bold">Billed To</td>
						<td class="alignct default-text-font-bold">Cashier</td>
						<td class="alignct default-text-font-bold">Date-time</td>
					</tr>

					<?php
						if(is_array($getSQL)) {
							
							$createdby = ""; $remark = ""; $outlet = ""; $foodtype = ""; $billedAs = ""; $get_bill_name = "";
							$biller = 0; $customerid = 0; $chargeroomid = 0;

							$counter = 0;

							foreach($getSQL as $key => $val) {
								
								$outlet = idget_data($tbL14,$val['posid'],'posname');
								$createdby = idget_data($tbL7,$val['cashier'],'staffname');
								$foodtype = $food_type[$val['foodtype']];
								$billedAs = $bill_type[$val['billtype']];

								$biller = $val['biller'];
								$customerid = $val['customerid'];
								$chargeroomid = $val['roomid'];

								$get_guest_name = ""; $r_prefix = ""; $r_number = ""; $r_suffix = "";

								if($val['billtype'] == 1) {
									$get_guest_name = idget_data($tbL169,$customerid,'fname').' ';
									$get_guest_name .= idget_data($tbL169,$customerid,'lname');
									$get_bill_name = $get_guest_name;
								} elseif($val['billtype'] == 2) {
									$get_guest_name = idget_data($tbL102,$customerid,'fname').' ';
									$get_guest_name .= idget_data($tbL102,$customerid,'lname');
									$get_guest_bkt = idget_data($tbL102,$customerid,'booking_type');
									$r_prefix = idget_data($tbL56,$chargeroomid,'roomprefix');
									$r_number = idget_data($tbL56,$chargeroomid,'roomnumber');
									$r_suffix = idget_data($tbL56,$chargeroomid,'roomsuffix');
									$get_bill_name = strtoupper($get_guest_bkt).'<br>';
									$get_bill_name .= $get_guest_name.' ('.$r_prefix.$r_number.$r_suffix.')';
								} elseif($val['billtype'] == 3) {
									$get_guest_name = idget_data($tbL169,$customerid,'fname').' ';
									$get_guest_name .= idget_data($tbL169,$customerid,'lname');
									$get_bill_name = idget_data($tbL33,$biller,'name');
									$get_bill_name .= '<br>';
									$get_bill_name .= $get_guest_name;
								} elseif($val['billtype'] == 4) {
									$get_bill_name = idget_data($tbL58,$biller,'name');

									if(!empty($get_invoice_data[0])) {
										$get_guest_name = idget_data($tbL169,$customerid,'fname').' ';
										$get_guest_name .= idget_data($tbL169,$customerid,'lname');
									} else {
										$get_guest_name = "";
									}

									$get_bill_name .= '<br>';
									$get_bill_name .= $get_guest_name;

								} elseif($val['billtype'] == 5) {
									$get_guest_name = "";
									$get_bill_name = idget_data($tbL7,$biller,'staffname');
								}

								$counter += 1;

								?>
									<tr>
										<td class="alignct"><?php echo $counter; ?>.</td>
										<td class="alignct"><?php echo $outlet; ?></td>
										<td class="alignct"><?php echo $val['order_number']; ?></td>
										<td class="alignct"><?php echo $val['booking_number']; ?></td>
										<td class="alignct"><?php echo number_format($val['bill_amount'],2); ?></td>
										<td class="alignct"><?php echo $foodtype; ?></td>
										<td class="alignct"><?php echo $billedAs; ?></td>
										<td class="alignct"><?php echo $get_bill_name; ?></td>
										<td class="alignct"><?php echo $createdby; ?></td>
										<td class="alignct"><?php echo date('d/m/Y',strtotime($val['datelogged'])).' '.$val['timelogged']; ?></td>
									</tr>
								<?php
							}
						}
					?>

				</table>
			</div>
		<?php
	}

?>


<script>

	function dateStat() {
		var d = document.getElementById('period').value;
		if(d == 'Custom Date') {
			objDisplay('custom-date');
			document.getElementById('startdate').required = true;
			document.getElementById('endate').required = true;
		} else {
			objHidden('custom-date');
			document.getElementById('startdate').required = false;
			document.getElementById('endate').required = false;
		}
	}

	function dateStat2() {
		var d = document.getElementById('period-2').value;
		if(d == 'Custom Date') {
			objDisplay('custom-date-2');
			document.getElementById('startdate').required = true;
			document.getElementById('endate').required = true;
		} else {
			objHidden('custom-date-2');
			document.getElementById('startdate').required = false;
			document.getElementById('endate').required = false;
		}
	}

</script>