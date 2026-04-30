<?php
	$smdl = "reports"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$xtbl = $tbL129;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: Here you can see the report regarding coupon details
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporter" value="coupon-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Coupon Status</h3>
				<select name="coupon" id="coupon" class="nopads no-back-black">
					<option value="" selected>All</option>
					<option value="Active">Active</option>
					<option value="Inactive">Inactive</option>
					<option value="Used">Used</option>
					<option value="Unused">Unused</option>
					<option value="Expired">Expired</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold alignlt">End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
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
		<div class="nc-width-100 right-pull-20 left-pull-20">
			<div id="section-to-print">

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'coupon-reports') {

						?>
							<div class="bottom-push-20" align="center">
								<div class="cs-width-100 noscroll">
									<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
								</div>
								<div class="cs-width-700 margin-auto-ct alignct">
									<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
									<h3 class="large nobold nomargin">Coupon Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
									<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
								</div>
							</div>
						<?php

						$_SESSION['startdate'] = $_POST['startdate'];
						$_SESSION['enddate'] = $_POST['enddate'];

						$keywords = "";

						if(isset($_POST['coupon'])) {
							if($_POST['coupon'] == 'Active' || $_POST['coupon'] == 'Inactive') {
								$isstatus = ($_POST['coupon'] == 'Active') ? 1 : 0;
								$keywords .= " AND status={$isstatus}";
							} else {
								if(!empty($_POST['coupon'])) {
									$keywords .= " AND coupon_status='{$_POST['coupon']}'";
								} else {
									$keywords .= "";
								}
							}
						}

						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
					
						$sql = "SELECT * FROM {$xtbl} WHERE deletedata=0".$keywords;
						$datagroup = wgetSQL($sql);

						if(is_array($datagroup)) {
							
							?>
							
							<table cellpadding="3" cellspacing="0" border="1">
								<tr>
									<td class="alignct default-text-font-bold">Rf. Booking No.</td>
									<td class="alignct default-text-font-bold">Cr. Booking No.</td>
									<td class="alignct default-text-font-bold">Guest Detail</td>
									<td class="alignct default-text-font-bold">Date Created</td>
									<td class="alignct default-text-font-bold">Coupon Code</td>
									<td class="alignct default-text-font-bold">Coupon Type</td>
									<td class="alignct default-text-font-bold">Status</td>
									<td class="alignct default-text-font-bold">Expiry</td>
									<td class="alignct default-text-font-bold">Created By</td>
									<td class="alignct default-text-font-bold">Used By</td>
									<td class="alignct default-text-font-bold">Coupon Amount</td>
								</tr>

								<?php
									
									$coupon_type = ""; $total_coupon_amount = 0; $coupon_numbr = 0;
									$createdby = ""; $updatedby = "";

									foreach($datagroup as $key => $val) {
										
										$coupon_type = ($val['coupon_type'] == 1) ? 'Hotel Booking' : 'Generated';
										
										$createdby = idget_data($tbL7,$val['userid'],'staffname');
										$updatedby = idget_data($tbL7,$val['usedby'],'staffname');

										$sqlx = "SELECT booking_number FROM {$tbL131} WHERE transaction_type IN('credit') AND sales_description LIKE '%{$val['coupon_code']}%' LIMIT 1";
										$iscouponcredit = wgetSQL($sqlx);

										$total_coupon_amount = $total_coupon_amount + $val['coupon_amount'];
										$coupon_numbr += 1;

										?>
											<tr>
												<td class="right-pull-10 left-pull-10 alignct"><a title="Reference Booking" href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></a></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php if(!empty($iscouponcredit[0]['booking_number'])): ?><a title="Credited Booking" href="javascript:void(0)" class="blue-font" onclick="jsxView('<?php echo $iscouponcredit[0]['booking_number']; ?>')"><?php echo $iscouponcredit[0]['booking_number']; ?></a><?php else: ?>--<?php endif; ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo $val['guest_name']; ?><br><?php echo $val['guest_contact']; ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo date('d-m-Y',strtotime($val['datelogged'])); ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo strtoupper($val['coupon_code']); ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo $coupon_type; ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo $val['coupon_status']; ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo date('d-m-Y',strtotime($val['expires_on'])); ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo $createdby; ?></td>
												<td class="right-pull-10 left-pull-10 alignct"><?php echo $updatedby; ?></td>
												<td class="right-pull-10 left-pull-10 alignrt"><?php echo number_format($val['coupon_amount'],2); ?></td>
											</tr>
										<?php

									}

								?>

								<tr>
									<td class="right-pull-10 left-pull-10 alignlt">Total</td>
									<td class="right-pull-10 left-pull-10 alignlt" colspan="9"><?php echo $coupon_numbr; ?></td>
									<td class="right-pull-10 left-pull-10 default-text-font-bold alignrt"><?php echo number_format($total_coupon_amount,2); ?></td>
								</tr>

							</table>

							<?php
							
						}
					}
				?>
			</div>
		</div>
	</div>
</div>


<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function jsForm(fr) {
		document.getElementById(fr).submit();
	}

	
	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}

</script>