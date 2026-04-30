<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_blocks = select_dt_fetch('deletedata',0,$tbL49,'id','name');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the daily hight balance report
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads" onsubmit="chgclass('processbar','fx-position-stick fscr zind-1 motion')">
			<input type="hidden" name="requri" id="requri" value="run-daily-high-balance">
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Block</h3>
				<select name="blocks" id="blocks" class="nopads no-back-black" onchange="getdata('floors','eget-block-floors-list','blocks','dropbox');">
					<option value="" selected="selected">All</option>
					<?php echo $get_blocks; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Floor</h3>
				<select name="floors" id="floors" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-180 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Booking Type</h3>
				<select name="bookingtype" id="bookingtype" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<option value="individual">Individual</option>
					<!--<option value="corporate">Corporate</option>-->
					<option value="corporate-paid-by-guest">Corporate Paid By Guest</option>
					<!--<option value="online">Online</option>-->
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-180 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Date Selection</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm(this)" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<!--<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>-->
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div>
			<div id="section-to-print" class="cs-width-1500">
				
				<?php
					
					if(isset($_POST['requri']) && $_POST['requri'] == 'run-daily-high-balance') {
					
						$tbl = $tbL100;

						$startnumbr = 0;
						$keywords = ""; $pick_option_date = 0;
						$dateSelected = ""; $pick_option_booking = "";

						if(isset($_POST['blocks']) && !empty($_POST['blocks'])) {
							$keywords .= " AND blockid={$_POST['blocks']}";
						}

						if(isset($_POST['floors']) && !empty($_POST['floors'])) {
							$keywords .= " AND floorid={$_POST['floors']}";
						}

						
						//$pick_option_booking = " AND t1.booking_src='Online' AND t1.reservation IN('Checking in')";

						if($_POST['bookingtype'] == 'corporate-paid-by-guest') {
							$pick_option_booking = " AND t1.booking_type='corporate' AND t1.bill_type='Guest' AND t1.reservation IN('Checking in') AND t1.deletedata=0";
						} elseif($_POST['bookingtype'] == 'individual') {
							$pick_option_booking = " AND t1.booking_type='individual' AND t1.reservation IN('Checking in') AND t1.deletedata=0";
						} else {
							$pick_option_booking = " AND t1.booking_type IN('individual','corporate') AND t1.bill_type IN('Guest','Group Owner') AND t1.reservation IN('Checking in') AND t1.deletedata=0";
						}
						

						if(isset($_POST['startdate'])) {
							$qSdate = " AND (checkout_date >= '{$_POST['startdate']}' AND checkin_date <= '{$_POST['startdate']}')";
							$dateSelected = date('d-m-Y',strtotime($_POST['startdate']));
						} else {
							$qSdate = " AND (checkout_date >= '{$server_get_date}' AND checkin_date <= '{$server_get_date}')";
							$dateSelected = date('d-m-Y',strtotime($server_get_date));
						}

						?>

						<div class="bottom-push-15" align="center">
							<div class="cs-width-100 bottom-push-10 noscroll">
								<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
							</div>
							<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
							<h3 class="large nobold default-text-font-bold">Daily High Balance Report (<?php echo $dateSelected; ?>)</h3>
						</div>


						<div class="cs-height-30"></div>

						<?php

							$theader = array(
								"booking no.",
								"booking type",
								"guest name",
								"room nos.",
								"arrival d.",
								"departure d.",
								"consumption",
								"deposit",
								"balance",
								"credit limit",
								"Notify"
							);

							$isnum = false;
							$ths = rowHeader2($theader);


							$sql = "SELECT t1.booking_number AS 'bookingno', t1.booking_type, bill_type, bill_to, bill_to_g, checkin_date, checkin_time, checkout_date, checkout_time, CONCAT(fname,' ',lname) AS 'guestname', mobile, (SELECT SUM(room_amount + tax_amount + consumption_tax_amount + service_charge) - SUM(discount_amount) FROM {$tbL134} WHERE booking_number=bookingno AND room_status IN('CheckedIn') AND deletedata=0) AS 'totalconsumption', (SELECT IFNULL(SUM(bill_amount),0) FROM {$tbL100} WHERE booking_number=bookingno AND status IN('completed') AND ispaid=0 AND isreversed=0 AND deletedata=0) AS 'totalposconsumption', (SELECT IFNULL(SUM(amount),0) FROM {$tbL131} WHERE booking_number=bookingno AND isreversed=0 AND deletedata=0) AS 'totaldeposited', (SELECT roomid FROM $tbL127 WHERE status IN('CheckedIn') AND booking_number=bookingno LIMIT 1) AS roomno FROM {$tbL130} t1 RIGHT JOIN {$tbL102} t2 ON t1.booking_number=t2.booking_number WHERE t2.id=(SELECT customerid FROM $tbL127 WHERE status IN('CheckedIn') AND booking_number=bookingno LIMIT 1)".$pick_option_booking.$qSdate;

							$rows = mysqli_data_array('assoc',$sql);

						?>

						<table cellpadding="3" cellspacing="0" border="1">
							
							<?php
								echo $ths;

								if(is_array($rows) && count($rows) > 0) {
									
									$totalconsumed = ""; $totaldeposited = ""; $balance = "";
									$credit_limit = ""; $roomnos = ""; $corporate_name = "";
									$iscorps_name = "";

									$totalAmtconsumed = 0; $totalAmtdeposited = 0; $totalAmtBal = 0;

									foreach($rows as $key => $val) {
										
										if(!empty($val['bill_to']) && $val['bill_to'] > 0 && $val['bill_type'] == 'Corporate') {
											$corporate_name = idget_data($tbL58,$val['bill_to'],'name');
											$iscorps_name = " (".$corporate_name.")";
											$credit_limit = idget_data($tbL58,$val['bill_to'],'xcreditlimit');
										} elseif(!empty($val['bill_to_g']) && $val['bill_to_g'] > 0 && $val['bill_type'] == 'Guest') {
											$corporate_name = idget_data($tbL58,$val['bill_to_g'],'name');
											$iscorps_name = " (".$corporate_name.")";
											$credit_limit = 0;
										} else {
											$corporate_name = "";
											$iscorps_name = "";
											$credit_limit = 0;
										}

										#get sub rooms with guest names
										/*$get_rooms = "SELECT roomid, CONCAT(roomprefix,roomnumber) AS 'roomno', CONCAT(fname,' ',lname) AS 'secondaryguests' FROM {$tbL127} t1 LEFT JOIN {$tbL56} t2 ON roomid=t2.id LEFT JOIN {$tbL102} t3 ON t1.booking_number=t3.booking_number WHERE t1.booking_number='{$val['booking_number']}' AND t1.status IN('CheckedIn')";
										$roomnos = mysqli_data_array('assoc',$get_rooms);*/

										$get_rooms = "SELECT CONCAT(roomprefix,roomnumber) AS 'room' FROM {$tbL56} WHERE id={$val['roomno']}";
										$roomnos = mysqli_data_array('assoc',$get_rooms);


										$totalconsumed = $val['totalconsumption'] + $val['totalposconsumption'];
										$totaldeposited = $val['totaldeposited'];
										$balance = $totalconsumed - $totaldeposited;

										$totalAmtconsumed = $totalAmtconsumed + $totalconsumed;
										$totalAmtdeposited = $totalAmtdeposited + $totaldeposited;
										$totalAmtBal = $totalAmtBal + $balance;

										//$rcc = "dark-black-font";

										?>
											<tr class="white-grey-state">
												<td class="right-pull-10 left-pull-10 alignct"><a href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['bookingno']; ?>')"><?php echo $val['bookingno']; ?></a></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo ucwords($val['booking_type']); ?></td>
												<td class="cs-width-250 right-pull-10 left-pull-10 alignct"><?php echo $val['guestname'].'<b class="nobold red-font">'.$iscorps_name.'</b>'; ?></td>
												<td class="right-pull-10 left-pull-10 anchor">

													<div class="blue-font cs-height-20 motion noscroll" title="Show more" onclick="zipup(this)" lang="on"><?php echo $roomnos[0]['room']; ?></div>
													
													<?php 
														/*$rk = array();
														
														if(is_array($roomnos) && count($roomno) > 1) {
															?>
																<div class="blue-font cs-height-20 motion noscroll" title="Show more" onclick="zipup(this)" lang="on">
																	<?php
																		$lsn = 0; $packr = "";
																		foreach($roomnos as $rkey => $rval) {
																			if($lsn > 0) {
																				if(!in_array($rval['roomno'],$rk)) {
																					echo $rval['roomno'].' - '.$rval['secondaryguests'].'<br>';
																					array_push($rk,$rval['roomno']);
																				}
																			}

																			$lsn += 1;
																		}

																	?>
																</div>
															<?php
														} else {
															?>
																<div class="motion">
																	<?php echo $roomnos[0]['roomno']; ?>
																</div>
															<?php
														}*/

													?>
													
												</td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo date('d-m-y',strtotime($val['checkin_date'])); ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo date('d-m-y',strtotime($val['checkout_date'])); ?></td>
												<td class="right-pull-10 left-pull-10 alignct">&#8358; <?php echo number_format($totalconsumed,2); ?></td>
												<td class="right-pull-10 left-pull-10 alignct">&#8358; <?php echo number_format($totaldeposited,2); ?></td>
												<td class="right-pull-10 left-pull-10 alignct">&#8358; <?php echo number_format($balance,2); ?></td>
												<td class="right-pull-10 left-pull-10 alignct">&#8358; <?php echo number_format($credit_limit,2); ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><a href="javascript:void(0)" class="steel-blue-font" onclick="doSMS('<?php echo number_format($balance,2); ?>','<?php echo $val['mobile']; ?>')">Send SMS</a></td>
											</tr>
										<?php
									}

									?>

										<tr>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10 grey-theme default-text-font-bold alignct">&#8358; <?php echo number_format($totalAmtconsumed,2); ?></td>
											<td class="right-pull-10 left-pull-10 grey-theme default-text-font-bold alignct">&#8358; <?php echo number_format($totalAmtdeposited,2); ?></td>
											<td class="right-pull-10 left-pull-10 grey-theme default-text-font-bold alignct">&#8358; <?php echo number_format($totalAmtBal,2); ?></td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
											<td class="right-pull-10 left-pull-10">&nbsp;</td>
										</tr>

									<?php
								}

							?>

						</table>
						<span class="block-element ft-sml-size top-push-5"><?php echo count($rows); ?> Found</span>

						<?php
					}

				?>

			</div>
			<br><br>
		</div>
	</div>
</div>

<script>

	function jsForm(obj) {
		obj.innerText = 'Loading..';
		document.getElementById('reportform').submit();
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function jsxView(key) {
		var numbr = Math.round((Math.random() * 10000000) - 1);
		crframe(key,numbr,'reservations');
	}

	function doSMS(debt,phone) {

		var vhtml, div = document.createElement('div');
		document.body.appendChild(div);

		vhtml = '';
		vhtml += '<div class="cs-height-200"></div>';
		vhtml += '<div class="cs-width-400 sml-rounded-button white-theme pads30 box-border-thick alignlt">';
		vhtml += '<h3 class="large nobold bottom-pull-5">Sending to: <b class="nobold default-text-font-bold">'+phone+'</b></h3>';
		vhtml += 'Dear Sir/Ma, this is a gentle reminder of your outstanding bill of &#8358;'+debt+" that require payment. Contact the hotel for settlement";
		vhtml += '<p class="alignct top-pull-20"><a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button">Send</a></p>';
		vhtml += '</div>';

		div.className = 'fx-position-stick fscr zind-2 txp8-white top-pull-30';
		div.align = 'center';
		div.innerHTML = vhtml;

		div.onclick = () => { div.innerHTML = ""; document.body.removeChild(div); }
	}


	function zipup(obj) {
		if(obj.lang == 'on') {
			obj.className = 'motion';
			obj.lang = 'off';
			obj.title = 'Less more';
		} else if(obj.lang == 'off') {
			obj.className = 'motion cs-height-20 blue-font noscroll';
			obj.lang = 'on';
			obj.title = 'Show more';
		}
	}

</script>