<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_room_types = select_dt_fetch('status','Active',$tbL52,'id','name');

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
		&nbsp; Note: here you can see mod-analysis
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
			<input type="hidden" name="rtask" id="rtask" value="mod-analysis">
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-pull-10 left-pull-10">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="date" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" class="nopads no-back-black">
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
		<div class="cs-width-1500 bottom-push-30">
			<div id="section-to-print">

				<?php

					if(isset($_POST['rtask']) && $_POST['rtask'] == 'mod-analysis'):

						$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
						$get_posstores = mysqli_data_fetch($tbL14,'id,posname',$pst_query,'array');

					?>
						<div class="bottom-push-15" align="center">
							<div class="cs-width-100 bottom-push-10 noscroll">
								<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
							</div>
							<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
							<h3 class="large nobold default-text-font-bold nomargin">MOD Analysis</h3>
							<h4 class="large nobold">Between <?php echo date('d-m-Y',strtotime($startdate)); ?> And <?php echo date('d-m-Y',strtotime($endate)); ?></h4>
							<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
						</div>
				
					<?php

					$keywords = "";

					$keywords .= " AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";

					$sql = "SELECT datelogged FROM {$tbL100} WHERE deletedata=0 AND isreversed=0 AND status IN('Completed')".$keywords." GROUP BY datelogged"; $datagroup = wgetSQL($sql);

					?>
						<table cellpadding="3" cellspacing="0" border="1">
							<tr>
								<td class="alignct default-text-font-bold">Date</td>

								<?php
									if(is_array($get_posstores)) {
										foreach($get_posstores as $key => $val) {
											?>
												<td class="alignct default-text-font-bold"><?php echo $val['posname']; ?></td>
											<?php
										}
									}
								?>
								
							</tr>
							
							<?php
								if(is_array($datagroup)) {
									
									$total_revenue = 0;
									
									$base_total = array();
									
									foreach($datagroup as $key => $val) {
										
										?>
											<tr>
												<td class="alignlt"><?php echo date('d-m-Y',strtotime($val['datelogged'])); ?></td>
													<?php

													$revenue = ""; $get_key_val = "";

													foreach($get_posstores as $key2 => $val2) {
														
														$sql = "SELECT SUM(bill_amount) AS totalamount FROM {$tbL100} WHERE posid={$val2['id']} AND  isreversed=0 AND status IN('Completed') AND datelogged='{$val['datelogged']}'";

														$in_dataset = wgetSQL($sql);

														$revenue = $in_dataset[0]['totalamount'];

														?>
															<td class="alignrt"><?php echo number_format($revenue,2); ?></td>
														<?php

														if(array_key_exists($val2['id'],$base_total)) {
															$get_key_val = $base_total[$val2['id']];
															$base_total[$val2['id']] = $get_key_val + $revenue;
														} else {
															$base_total[$val2['id']] = $revenue;
														}
													}

													?>
											</tr>
										<?php
									}
								}
							?>
							<tr>
								<td class="alignlt default-text-font-bold">Total</td>
								
								<?php
									if(is_array($base_total) && count($base_total) > 0) {
										foreach($base_total as $key3 => $val3) {
											?>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($val3,2); ?></td>
											<?php
										}
									}
								?>
							</tr>

						</table>

					<?php



					endif;

				?>

			</div>
		</div>
	</div>
</div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

</script>