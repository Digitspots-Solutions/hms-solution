<?php

$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');

//$extable = $tbL7; $extcols = "staffname"; $extkey = "id";
//$get_users = select_dt_fetch('deletedata',0,$tbL22,'','userid');

$extable = $tbL19; $extcols = "countername"; $extkey = "id";
$get_users = select_dt_fetch('deletedata',0,$tbL25,'','counterid');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Counter Shift Report</b>
 	</span>
 	<span class="ln-display-box float-right">
		here you can see the counter summary transactions
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom bottom-push-50 x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporting" value="post">
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Counter Date</h3>
				<input type="text" name="counterdate" id="counterdate" placeholder="Date?" onfocus="textodate(this.id)" class="nopads no-back-black" required>
			</span>
			
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Counters</h3>
				<select name="user" id="user" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php echo $get_users; ?>
				</select>
			</span>

			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Shift</h3>
				<select name="shift" id="shift" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php echo $get_shifts; ?>
				</select>
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

<div id="section-to-print">
	<h2 class="xlarge nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h2>
	<h3 class="large nobold alignct">Counter Shift List</h3><br>
	<br>
	<?php

		if(isset($_POST['reporting']) || isset($_SESSION['reporting'])) {

			$tbl = $tbL25;

			$_SESSION['reporting'] = "on";

			$startnumbr = 0;
			$keywords = ""; $userid = ""; $shiftid = ""; $datelog = "";

			/*if(isset($_POST['user']) && !empty($_POST['user'])) {
				$userid = $_POST['user'];
				$keywords .= " AND userid={$userid}";
			}*/

			if(!isset($_POST['reporting']) && isset($_SESSION['reporting'])) {
				$keywords = $_SESSION['keywords'];
				$userid = $_SESSION['userid'];
				$shiftid = $_SESSION['shiftid'];
				$sql_date = $_SESSION['sql_date'];
				$datelog = $_SESSION['datelog'];
				$counter_date_format = $_SESSION['counter_date_format'];
			} else {

				if(isset($_POST['user']) && !empty($_POST['user'])) {
					$userid = " AND counterid={$_POST['user']}";
					$keywords .= " AND counterid={$_POST['user']}";

					$_SESSION['userid'] = $userid;
					$_SESSION['keywords'] = $keywords;
				}

				if(isset($_POST['shift']) && !empty($_POST['shift'])) {
					$shiftid = " AND shiftid={$_POST['shift']}";
					$keywords .= " AND shiftid={$_POST['shift']}";

					$_SESSION['shiftid'] = $shiftid;
					$_SESSION['keywords'] = $_SESSION['keywords'].$keywords;
				}

				if(isset($_POST['counterdate']) && !empty($_POST['counterdate'])) {
					$sql_date = $_POST['counterdate'];
					$datelog = " AND datelogged='{$sql_date}'";
					$keywords .= " AND datelogged='{$sql_date}'";
					$counter_date_format = date('d-m-Y',strtotime($sql_date));

					$_SESSION['sql_date'] = $sql_date;
					$_SESSION['datelog'] = $datelog;
					$_SESSION['counter_date_format'] = $counter_date_format;
					$_SESSION['keywords'] = $_SESSION['keywords'].$keywords;
				} else {
					$sql_date = $server_get_date;
					$datelog = " AND datelogged='{$sql_date}'";
					$keywords .= " AND datelogged='{$sql_date}'";
					$counter_date_format = date('d-m-Y',strtotime($sql_date));

					$_SESSION['sql_date'] = $sql_date;
					$_SESSION['datelog'] = $datelog;
					$_SESSION['counter_date_format'] = $counter_date_format;
					$_SESSION['keywords'] = $_SESSION['keywords'].$keywords;
				}
			}

			$sqlx = "SELECT counterid FROM {$tbl} WHERE ispast IN(0,1) AND deletedata=0".$keywords." GROUP BY counterid";
			$resultx = wgetSQL($sqlx);

			if(is_array($resultx)) {

				?>
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="default-text-font-bold alignct">Counter Date</td>
							<td class="default-text-font-bold alignct">Counter User</td>
							<td class="default-text-font-bold alignct">Counter Name</td>
							<td class="default-text-font-bold alignct">Opening Bal.</td>
							<td class="default-text-font-bold alignct">Collections</td>
							<td class="default-text-font-bold alignct">Refunds</td>
							<td class="default-text-font-bold alignct">Withdrawals</td>
							<td class="default-text-font-bold alignct">Closing Bal.</td>
							<td class="default-text-font-bold alignct">&nbsp;</td>
						</tr>

						<?php
							
							foreach($resultx as $keyx => $valx) {
								
								$counter_user = ""; $counter_name = "";
								$gt_totalob = 0; $gt_totalcl = 0; $gt_totalrf = 0; $gt_totalwd = 0; $gt_totalab = 0;

								$counter_name = idget_data($tbL19,$valx['counterid'],'countername');

								$sql = "SELECT * FROM {$tbl} WHERE ispast IN(0,1) AND counterid={$valx['counterid']}".$shiftid.$datelog." AND deletedata=0 GROUP BY userid";
								$result = wgetSQL($sql);

								foreach($result as $key => $val) {

									$counter_user = idget_data($tbL7,$val['userid'],'staffname');
									
									$sql_1 = "SELECT SUM(openingbalance) AS totalob FROM {$tbl} WHERE counterid={$valx['counterid']} AND userid={$val['userid']} AND ispast IN(0,1) AND deletedata=0".$shiftid.$datelog;
									$result_1 = wgetSQL($sql_1);

									$sql_2 = "SELECT SUM(collection) AS totalcl FROM {$tbl} WHERE counterid={$valx['counterid']} AND userid={$val['userid']} AND ispast IN(0,1) AND deletedata=0".$shiftid.$datelog;
									$result_2 = wgetSQL($sql_2);

									$sql_3 = "SELECT SUM(refunds) AS totalrf FROM {$tbl} WHERE counterid={$valx['counterid']} AND userid={$val['userid']} AND ispast IN(0,1) AND deletedata=0".$shiftid.$datelog;
									$result_3 = wgetSQL($sql_3);

									$sql_4 = "SELECT SUM(withdrawal) AS totalwd FROM {$tbl} WHERE counterid={$valx['counterid']} AND userid={$val['userid']} AND ispast IN(0,1) AND deletedata=0".$shiftid.$datelog;
									$result_4 = wgetSQL($sql_4);

									if($result_2[0]['totalcl'] > 0) {
										
										$available_bal = ($result_1[0]['totalob'] + $result_2[0]['totalcl']) - ($result_3[0]['totalrf'] + $result_4[0]['totalwd']);

										$gt_totalob = $gt_totalob + $result_1[0]['totalob'];
										$gt_totalcl = $gt_totalcl + $result_2[0]['totalcl'];
										$gt_totalrf = $gt_totalrf + $result_3[0]['totalrf'];
										$gt_totalwd = $gt_totalwd + $result_4[0]['totalwd'];
										$gt_totalab = $gt_totalab + $available_bal;

										?>
											<tr>
												<td class="alignct"><?php echo $counter_date_format; ?></td>
												<td class="alignct"><?php echo $counter_user; ?></td>
												<td class="alignct"><?php echo $counter_name; ?></td>
												<td class="alignct"><?php echo number_format($result_1[0]['totalob'],2); ?></td>
												<td class="alignct"><?php echo number_format($result_2[0]['totalcl'],2); ?></td>
												<td class="alignct"><?php echo number_format($result_3[0]['totalrf'],2); ?></td>
												<td class="alignct"><?php echo number_format($result_4[0]['totalwd'],2); ?></td>
												<td class="alignct"><?php echo number_format($available_bal,2); ?></td>
												<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="ucwrk('<?php echo $val['userid']; ?>','<?php echo $valx['counterid']; ?>','<?php echo $val['shiftid']; ?>','<?php echo $sql_date; ?>')">Print</a></td>
											</tr>
										<?php
									}

									$available_bal = 0;
								}

								if($gt_totalcl > 0) {

									?>

										<tr>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="default-text-font-bold alignct"><?php echo number_format($gt_totalob,2); ?></td>
											<td class="default-text-font-bold alignct"><?php echo number_format($gt_totalcl,2); ?></td>
											<td class="default-text-font-bold alignct"><?php echo number_format($gt_totalrf,2); ?></td>
											<td class="default-text-font-bold alignct"><?php echo number_format($gt_totalwd,2); ?></td>
											<td class="default-text-font-bold alignct"><?php echo number_format($gt_totalab,2); ?></td>
											<td class="alignct">&nbsp;</td>
										</tr>
										<tr>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
											<td class="alignct">&nbsp;</td>
										</tr>

									<?php
								}
							}
						?>

					</table>

				<?php
			}
		}


		if((isset($_GET['user']) && $_GET['user'] > 0) && (isset($_GET['counter']) && $_GET['counter'] > 0) && (isset($_GET['shift']) && $_GET['shift'] > 0)) {
			$user = escape_data($_GET['user']);
			$counter = escape_data($_GET['counter']);
			$shift = escape_data($_GET['shift']);
			$wdate = escape_data($_GET['date']);
			
			$_SESSION['counter'] = $counter;
			$_SESSION['user'] = $user;
			$_SESSION['shift'] = $shift;
			$_SESSION['from'] = $wdate;
			$_SESSION['to'] = $wdate;

			unset($_GET['user']);
			unset($_GET['counter']);
			unset($_GET['shift']);
			unset($_GET['date']);
		}
	?>

</div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function ucwrk(user,counter,shift,wdate) {
		sessionStorage.setItem('usercounterview',1);
		setTimeout(() => {
			window.location.href = window.location.href+'&user='+user+'&counter='+counter+'&shift='+shift+'&date='+wdate;
		},1000);
	}

	window.onload = () => {
		if(sessionStorage.getItem('usercounterview') !== null && sessionStorage.getItem('usercounterview') != undefined) {
			sessionStorage.removeItem('usercounterview');
			popmodalframe('frontdesk','user_counter_sheet',1,0,1000,2500);
		}
	}

</script>