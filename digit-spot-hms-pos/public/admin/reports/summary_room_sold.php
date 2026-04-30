<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

if(isset($_POST['startdate']) && !empty($_POST['startdate'])) { $startdate = $_POST['startdate']; }
else { $startdate = $server_get_date; }

if(isset($_POST['endate']) && !empty($_POST['endate'])) { $endate = $_POST['endate']; }
else { $endate = $server_get_date; }

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Summary of Rooms Sold Report</b>: Here you can see the summary of room sold for the date selected
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
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporting" value="post">
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100">
			<div id="section-to-print">
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$tbl = $tbL127;
					
						$keywords = "";
						
						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							$keywords .= " AND checkin_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						}


						$in_sql = "SELECT checkin_date FROM {$tbl} WHERE status IN('CheckedIn','Reserved') AND deletedata=0".$keywords." GROUP BY checkin_date"; $in_view = wgetSQL($in_sql);

						?>
							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Summary of Rooms Sold Report (From <?php echo $startdate; ?> To <?php echo $endate; ?>)</h3>
								<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
							</div>

							<div class="bottom-push-30">
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Date</td>
										<td class="alignct default-text-font-bold">Check In</td>
										<td class="alignct default-text-font-bold">Comp.</td>
										<td class="alignct default-text-font-bold">Full Rate</td>
										<td class="alignct default-text-font-bold">Discount Rate</td>
										<td class="alignct default-text-font-bold">Paying</td>
									</tr>

									<?php
										if(is_array($in_view)) {
											
											$checkedin = 0; $compl = 0; $fullrate = 0; $discounted = 0;

											$numbr = 0;

											foreach($in_view as $key => $val) {
												
												$sql = "SELECT COUNT(roomid) AS totalcheckedIn FROM {$tbL127} WHERE reservation IN('Checking In') AND checkin_date='{$val['checkin_date']}' AND deletedata=0";
												$queryset = wgetSQL($sql); $checkedin = $queryset[0]['totalcheckedIn'];

												$sql = "SELECT COUNT(roomid) AS totalComp FROM {$tbL127} t1 LEFT JOIN {$tbL130} t2 ON t1.booking_number=t2.booking_number WHERE booking_type='complimentary' AND t1.checkin_date='{$val['checkin_date']}' AND t1.deletedata=0";
												$queryset = wgetSQL($sql); $compl = $queryset[0]['totalComp'];

												$sql = "SELECT COUNT(roomid) AS totalFullr FROM {$tbL127} WHERE reservation IN('Checking In') AND checkin_date='{$val['checkin_date']}' AND roomid IN(SELECT DISTINCT t1.roomid FROM {$tbL134} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.roomid=t2.roomid WHERE (t1.actual_room_amount = t1.room_amount OR t1.discount_amount = 0) AND t1.deletedata=0)";
												$queryset = wgetSQL($sql); $fullrate = $queryset[0]['totalFullr'];

												$sql = "SELECT COUNT(roomid) AS totalDiscount FROM {$tbL127} WHERE reservation IN('Checking In') AND checkin_date='{$val['checkin_date']}' AND roomid IN(SELECT DISTINCT t1.roomid FROM {$tbL134} AS t1 LEFT JOIN {$tbL127} AS t2 ON t1.roomid=t2.roomid WHERE (t1.actual_room_amount > t1.room_amount OR t1.discount_amount > 0) AND t1.deletedata=0)";
												$queryset = wgetSQL($sql); $discounted = $queryset[0]['totalDiscount'];

												?>
													<tr>
														<td class="alignct"><?php echo date('d/m/y',strtotime($val['checkin_date'])); ?></td>
														<td class="alignrt"><?php echo $checkedin; ?></td>
														<td class="alignrt"><?php echo $compl; ?></td>
														<td class="alignrt"><?php echo $fullrate; ?></td>
														<td class="alignrt"><?php echo $discounted; ?></td>
														<td class="alignrt"><?php echo $checkedin; ?></td>
													</tr>
												<?php

												$checkedin = 0; $compl = 0; $fullrate = 0; $discounted = 0;

												$numbr += 1;
											}
										}
									?>
									
								</table>
								<h4 class="xlarge nobold top-pull-7"><?php echo $numbr; ?> Found</h4>
							</div>
						<?php
					}

				?>
			</div>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

</script>