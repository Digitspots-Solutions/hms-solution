<?php
	$smdl = "reports"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$sql_cspg = "SELECT * FROM $tbL58 WHERE status IN('Active') AND deletedata=0 ORDER BY name ASC";
	$dataset_cspg = wgetSQL($sql_cspg);

	$cspg_opt = "";

	foreach($dataset_cspg as $cspg_key => $cspg_val) {
		$cspg_opt .= '<option value="'.$cspg_val['id'].'">'.$cspg_val['name'].'</option>';
	}

?>

<div class="block-element bottom-push-30">
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


<div class="block-element box-border-thick-bottom bottom-pull-5 bottom-push-30">
	<form action="" method="post">
		<span class="ln-display-box float-left right-pull-10">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Start Date</h4>
			<input type="date" name="startdate" id="startdate" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left right-pull-10">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">End Date</h4>
			<input type="date" name="enddate" id="enddate" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left cs-width-150 right-pull-10">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Corporate Type</h4>
			<select name="corporatetype" id="corporatetype" onchange="change_cspg(this.value)">
				<?php
					if(isset($_POST['corporatetype'])) {
						?>
							<option value="<?php echo $_POST['corporatetype']; ?>" selected="selected"><?php echo $_POST['corporatetype']; ?></option>
						<?php
					} else {
						?>
							<option value="All" selected="selected">All</option>
						<?php
					}
				?>
				<option value="">All</option>
				<option value="Retainership">Retainership</option>
				<option value="Non Retainership">Non Retainership</option>
			</select>
		</span>
		<span class="ln-display-box float-left cs-width-150 right-pull-10">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Corporates</h4>
			<select name="corporates" id="corporates">
				<?php
					if(isset($_POST['corporates']) && !empty($_POST['corporates'])) {
						$cspg = idget_data($tbL58,$_POST['corporates'],'name');
						?>
							<option value="<?php echo $_POST['corporates']; ?>" selected="selected"><?php echo $cspg; ?></option>
						<?php
					}

					echo $cspg_opt;
				?>
			</select>
		</span>
		<span class="ln-display-box float-left cs-width-150 right-pull-10">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Transaction Type</h4>
			<select name="transactiontype" id="transactiontype">
				<?php
					if(isset($_POST['transactiontype']) && !empty($_POST['transactiontype'])) {
						?>
							<option value="<?php echo $_POST['transactiontype']; ?>" selected="selected"><?php echo $_POST['transactiontype']; ?></option>
						<?php
					}
				?>
				<option value="Bookings">Bookings</option>
				<option value="Bookings and POS">Bookings and POS</option>
				<option value="POS">POS</option>
				<option value="Deposit">Deposit</option>
				<option value="Reservations">Reservations</option>
			</select>
		</span>
		<span class="ln-display-box float-left left-pull-20 ft-xsml-size">
			<h4 class="large nobold light-red-font bottom-pull-5">* Check if applicable</h4>
			<input type="checkbox" name="inclcheckin" id="inclcheckin" value="yes"> &nbsp; Include In-house?
		</span>
		<span class="ln-display-box float-left left-pull-20">
			<h4 class="large nobold bottom-pull-5">&nbsp;</h4>
			<input type="submit" name="submitbutton" value="Run" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20">
		</span>
		<span class="ln-display-box float-left left-pull-20">
			<h4 class="large nobold bottom-pull-5">&nbsp;</h4>
			<input type="button" value="Print" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20" onclick="window.print()">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>


<div id="section-to-print">
	
	<?php
		if(isset($_POST['submitbutton'])) {

			$cspg_name = idget_data($tbL58,$_POST['corporates'],'name');
			
			$server_year = date('Y',strtotime($server_get_date));
			$bizdate = $server_year.'-01-01';

			$startdate = date('d-m-Y',strtotime($_POST['startdate']));
			$enddate = date('d-m-Y',strtotime($_POST['enddate']));
			
			?>
				<div class="bottom-push-20" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
					</div>
					<div class="cs-width-500 margin-auto-ct alignct">
						<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold nomargin">Corporate Consumption Report (for <?php echo $cspg_name; ?>)</h3>
						<h3 class="large nobold nomargin">Date Period Between <?php echo $startdate; ?> And <?php echo $enddate; ?></h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
					</div>
				</div>
			<?php

			$trx_type = isset($_POST['transactiontype']) ? $_POST['transactiontype'] : "";
			$bkg_inclusion = (isset($_POST['inclcheckin']) && !empty($_POST['inclcheckin'])) ? "'Checking Out','Checking In'" : "'Checking Out'";
			$rm_inclusion = (isset($_POST['inclcheckin']) && !empty($_POST['inclcheckin'])) ? "'CheckedOut','CheckedIn'" : "'CheckedOut'";

			if($trx_type === 'Bookings') {

				$sql_bkg = "SELECT t1.booking_number,t2.customerid,t2.roomid,t2.checkin_date,t2.checkin_time,t2.checkout_date,t2.checkout_time,t2.noofdays FROM $tbL130 t1, $tbL127 t2 WHERE t1.booking_number=t2.booking_number AND t1.reservation IN({$bkg_inclusion}) AND t1.bill_to={$_POST['corporates']} AND t1.datelogged >= '{$_POST['startdate']}' AND t1.datelogged <= '{$_POST['enddate']}' ORDER BY t1.checkout_date ASC";
				$dataset_bkg = wgetSQL($sql_bkg);

				?>
					<div class="bottom-push-30">
						<h3 class="large nobold default-text-font-bold bottom-pull-7">Bookings</h3>
						<table cellpadding="3" cellspacing="0" border="1">
							<tr>
								<td class="alignct default-text-font-bold">Booking No.</td>
								<td class="alignct default-text-font-bold">Guest Name</td>
								<td class="alignct default-text-font-bold">Room No</td>
								<td class="alignct default-text-font-bold">Actual Arival Datetime</td>
								<td class="alignct default-text-font-bold">Actual Departure Datetime</td>
								<td class="alignct default-text-font-bold">No. of Nights</td>
								<td class="alignct default-text-font-bold">Consumed Amount</td>
							</tr>

							<?php
								if(is_array($dataset_bkg)) {
									
									$guest_name = ""; $room_name = "";
									$stay_f = ""; $stay_t = ""; $consumed = "";

									$total_consumption = 0;

									foreach($dataset_bkg as $key => $val) {
										
										$guest_name = idget_data($tbL102,$val['customerid'],'fname').' ';
										$guest_name .= idget_data($tbL102,$val['customerid'],'lname');

										$room_name = idget_data($tbL56,$val['roomid'],'roomprefix');
										$room_name .= idget_data($tbL56,$val['roomid'],'roomnumber');

										$stay_f = write_dateF($gh_get_date_format,$val['checkin_date']);
										$stay_t = write_dateF($gh_get_date_format,$val['checkout_date']);

										$sql_1 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE booking_number='{$val['booking_number']}' AND roomid='{$val['roomid']}' AND room_status IN('CheckedIn') AND room_amount > 0 AND deletedata=0";
										$total_consumed = wgetSQL($sql_1);

										$consumed = ($total_consumed[0]['total'] - $total_consumed[0]['discounts']) + $total_consumed[0]['vat'] + $total_consumed[0]['consumption'] + $total_consumed[0]['scharge'];

										$total_consumption = $total_consumption + $consumed;

										?>
											<tr>
												<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></a></td>
												<td class="alignct"><?php echo $guest_name; ?></td>
												<td class="alignct"><?php echo $room_name; ?></td>
												<td class="alignct"><?php echo $stay_f.' '.$val['checkin_time']; ?></td>
												<td class="alignct"><?php echo $stay_t.' '.$val['checkout_time']; ?></td>
												<td class="alignct"><?php echo $val['noofdays']; ?></td>
												<td class="alignct"><?php echo number_format($consumed,2); ?></td>
											</tr>
										<?php
									}

									?>
										<tr>
											<td class="alignct default-text-font-bold">Total</td>
											<td class="alignct" colspan="5"></td>
											<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_consumption,2); ?></td>
										</tr>
									<?php
								}
							?>
						</table>
					</div>

				<?php

			} elseif($trx_type === 'Bookings and POS') {

				$sql_bkg = "SELECT t1.booking_number,t2.customerid,t2.roomid,t2.checkin_date,t2.checkin_time,t2.checkout_date,t2.checkout_time,t2.noofdays FROM $tbL130 t1, $tbL127 t2 WHERE t1.booking_number=t2.booking_number AND t1.reservation IN({$bkg_inclusion}) AND t1.bill_to={$_POST['corporates']} AND t2.status IN({$rm_inclusion}) AND t1.datelogged >= '{$_POST['startdate']}' AND t1.datelogged <= '{$_POST['enddate']}' ORDER BY t1.checkout_date ASC";
				$dataset_bkg = wgetSQL($sql_bkg);

				$sql_gr = "SELECT posid FROM $tbL100 WHERE billtype=4 AND biller={$_POST['corporates']} AND status='Completed' AND isreversed=0 AND deletedata=0 AND datelogged >= '{$_POST['startdate']}' AND datelogged <= '{$_POST['enddate']}' GROUP BY posid";
				$datagroup = wgetSQL($sql_gr);

				?>
					
					<div class="bottom-push-30">
						<h3 class="large nobold default-text-font-bold bottom-pull-7">Bookings</h3>
						<table cellpadding="3" cellspacing="0" border="1">
							<tr>
								<td class="alignct default-text-font-bold">Booking No.</td>
								<td class="alignct default-text-font-bold">Guest Name</td>
								<td class="alignct default-text-font-bold">Room No</td>
								<td class="alignct default-text-font-bold">Actual Arival Datetime</td>
								<td class="alignct default-text-font-bold">Actual Departure Datetime</td>
								<td class="alignct default-text-font-bold">No. of Nights</td>
								<td class="alignct default-text-font-bold">Consumed Amount</td>
							</tr>

							<?php
								if(is_array($dataset_bkg)) {
									
									$guest_name = ""; $room_name = "";
									$stay_f = ""; $stay_t = ""; $consumed = ""; $consumed_base = "";

									$total_consumption = 0;
									$total_consumption_base = 0;

									foreach($dataset_bkg as $key => $val) {
										
										$guest_name = idget_data($tbL102,$val['customerid'],'fname').' ';
										$guest_name .= idget_data($tbL102,$val['customerid'],'lname');

										$room_name = idget_data($tbL56,$val['roomid'],'roomprefix');
										$room_name .= idget_data($tbL56,$val['roomid'],'roomnumber');

										$stay_f = write_dateF($gh_get_date_format,$val['checkin_date']);
										$stay_t = write_dateF($gh_get_date_format,$val['checkout_date']);

										$sql_1 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE booking_number='{$val['booking_number']}' AND roomid='{$val['roomid']}' AND room_status IN('CheckedIn') AND room_amount > 0 AND deletedata=0";
										$total_consumed = wgetSQL($sql_1);

										$sql_2 = "SELECT SUM(bill_amount) AS totaloutletcharge FROM $tbL100 WHERE billtype IN(2) AND booking_number='{$val['booking_number']}' AND biller={$_POST['corporates']} AND status='Completed' AND isreversed=0 AND deletedata=0 AND datelogged >= '{$_POST['startdate']}' AND datelogged <= '{$_POST['enddate']}'";
										$total_outlet_consumed = wgetSQL($sql_2);

										$consumed = ($total_consumed[0]['total'] - $total_consumed[0]['discounts']) + $total_consumed[0]['vat'] + $total_consumed[0]['consumption'] + $total_consumed[0]['scharge'] + $total_outlet_consumed[0]['totaloutletcharge'];

										$consumed_base = ($total_consumed[0]['total'] - $total_consumed[0]['discounts']) + $total_consumed[0]['vat'] + $total_consumed[0]['consumption'] + $total_consumed[0]['scharge'];

										$total_consumption = $total_consumption + $consumed;
										$total_consumption_base = $total_consumption_base + $consumed_base;

										?>
											<tr>
												<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></a></td>
												<td class="alignct"><?php echo $guest_name; ?></td>
												<td class="alignct"><?php echo $room_name; ?></td>
												<td class="alignct"><?php echo $stay_f.' '.$val['checkin_time']; ?></td>
												<td class="alignct"><?php echo $stay_t.' '.$val['checkout_time']; ?></td>
												<td class="alignct"><?php echo $val['noofdays']; ?></td>
												<td class="alignct"><?php echo number_format($consumed,2); ?></td>
											</tr>
										<?php
									}

									?>
										<tr>
											<td class="alignct default-text-font-bold">Total</td>
											<td class="alignct" colspan="5"></td>
											<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_consumption,2); ?></td>
										</tr>
									<?php
								}
							?>
						</table>
					</div>

					<div class="bottom-push-30">

						<?php
							if(is_array($datagroup)) {
								
								$outlet_name = ""; $outlet_total_order_amount = 0;

								foreach($datagroup as $key => $val) {

									$outlet_name = idget_data($tbL14,$val['posid'],'posname');

									$sql_orders = "SELECT * FROM $tbL100 WHERE posid={$val['posid']} AND billtype IN(4) AND biller={$_POST['corporates']} AND status='Completed' AND isreversed=0 AND deletedata=0 AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
									$dataset_orders = wgetSQL($sql_orders);

									?>
										<h3 class="large nobold default-text-font-bold bottom-pull-7"><?php echo $outlet_name; ?></h3>

										<table cellpadding="3" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Order No.</td>
												<td class="alignct default-text-font-bold">Guest Name</td>
												<td class="alignct default-text-font-bold">Description</td>
												<td class="alignct default-text-font-bold">Order Date</td>
												<td class="alignct default-text-font-bold">Amount</td>
											</tr>

											<?php
												$order_date = ""; $total_order_amount = 0;

												foreach($dataset_orders as $rkey => $rval) {
													
													$order_date = write_dateF($gh_get_date_format,$rval['datelogged']);

													$total_order_amount = $total_order_amount + $rval['bill_amount'];

													?>
														<tr>
															<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="jsxView2('<?php echo $rval['order_number']; ?>')"><?php echo $rval['order_number']; ?></a></td>
															<td class="alignct"><?php echo $cspg_name; ?></td>
															<td class="alignct">Outlet order for <?php echo strtolower($outlet_name); ?> items</td>
															<td class="alignct"><?php echo $order_date; ?></td>
															<td class="alignct"><?php echo number_format($rval['bill_amount'],2); ?></td>
														</tr>
													<?php
												}
											?>

											<tr>
												<td class="alignct default-text-font-bold">Total</td>
												<td class="alignct" colspan="3"></td>
												<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_order_amount,2); ?></td>
											</tr>

										</table>

										<div class="cs-height-20">
										</div>
									<?php

									$outlet_total_order_amount = $outlet_total_order_amount + $total_order_amount;
								}
							}
						?>

					</div>

					<div class="bottom-push-30" align="center">

						<?php
							$g_total_consumption = $total_consumption + $outlet_total_order_amount;
						?>

						<div class="cs-width-400">
							<h3 class="large nobold bottom-pull-7">Short Summary for Corporate Consumption Report</h3>
							<table cellpadding="3" cellspacing="0" border="1">
								<tr>
									<td class="alignct default-text-font-bold">Particular</td>
									<td class="alignct default-text-font-bold">Amount</td>
								</tr>
								<tr>
									<td class="alignlt">Total Bookings</td>
									<td class="alignrt"><?php echo number_format($total_consumption,2); ?></td>
								</tr>
								<tr>
									<td class="alignlt">Total POS</td>
									<td class="alignrt"><?php echo number_format($outlet_total_order_amount,2); ?></td>
								</tr>
								<tr>
									<td class="alignlt default-text-font-bold">Total Consumption</td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_total_consumption,2); ?></td>
								</tr>
							</table>
						</div>
					</div>


					<div class="bottom-push-30" align="center">

						<?php
							
							$dataid = $_POST['corporates'];

							$sqlset = "SUM(amount)";

							$a_queryset = "cspgid={$dataid} AND transaction_type='Debit' AND datelogged >= '{$bizdate}' AND datelogged <= '{$_POST['enddate']}'"; $g_total_consumed = mysqli_arithmetic_data($tbL63,$sqlset,$a_queryset);

							$a2_queryset = "cspgid={$dataid} AND transaction_type='Credit' AND datelogged >= '{$bizdate}' AND datelogged <= '{$_POST['enddate']}'"; $g_total_pay = mysqli_arithmetic_data($tbL63,$sqlset,$a2_queryset);

							/*$queryset = "cspgid={$dataid} AND transaction_type='Debit' AND datelogged >= '{$startdate}' AND datelogged <= '{$enddate}'"; $total_consumed = mysqli_arithmetic_data($tbL63,$sqlset,$queryset);

							$queryset2 = "cspgid={$dataid} AND transaction_type='Credit' AND datelogged >= '{$startdate}' AND datelogged <= '{$enddate}'"; $total_pay = mysqli_arithmetic_data($tbL63,$sqlset,$queryset2);*/

							$closing_bal = $g_total_pay - $g_total_consumed;

						?>

						<div class="cs-width-500">
							<h3 class="large nobold bottom-pull-7">Corporate Account Balance Overview</h3>
							<table cellpadding="3" cellspacing="0" border="1">
								<tr>
									<td class="alignct default-text-font-bold">Particular</td>
									<td class="alignct default-text-font-bold">Amount</td>
								</tr>
								<!--<tr>
									<td class="alignlt">Opening Balance</td>
									<td class="alignrt default-text-font-bold">0</td>
								</tr>-->
								<tr>
									<td class="alignlt">Total Deposit</td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_total_pay,2); ?></td>
								</tr>
								<!--<tr>
									<td class="alignlt">Selected Periodic Consumption</td>
									<td class="alignrt default-text-font-bold">0</td>
								</tr>-->
								<tr>
									<td class="alignlt">Closing Balance</td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($closing_bal,2); ?></td>
								</tr>
							</table>
						</div>
					</div>
				<?php
			}
		}
	?>

</div>


<script>
	
	function change_cspg(cspg) {

		if(cspg != '') {

			const ret = (cspg == 'Retainership') ? 'Yes' : 'No';

			sqldatastring.sql = "SELECT * FROM cspg_tbl WHERE isretainership='"+ret+"' AND status='Active' AND deletedata=0 ORDER BY name ASC";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				//vhtml = '<option value="" selected="selected">Choose?</option>';
				vhtml = '';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
				}

				writeObjheader('corporates',vhtml);
			}
		}
	}


	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}


	function wgetorder(key) {
		var order = key;
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(order,uId,'posorder');
	}


	function jsxView2(key) {
		popmodalframe('pos','pos_post_bill_review',key,0,1000,2500);
	}

</script>