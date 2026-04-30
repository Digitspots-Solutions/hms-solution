<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the hall functions, consumptions and payments
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 box-border-thick-bottom bottom-pull-5">
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left cs-width-20">
			&nbsp;
		</span>
		<span class="ln-display-box float-left nc-width-20 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php echo $server_get_date; ?>">
		</span>
		
		<span class="ln-display-box float-right nc-width-10 right-pull-5 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
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


	if(isset($_POST['submitbutton'])) {
		
		$date_from = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$date_to = write_dateF($gh_get_date_format,$_POST['fieldset2']);

		?>
			<p class="alignrt bottom-pull-20">
				<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
			</p>

			<div id="section-to-print" class="block-element">
				<div align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
					<small class="block-element alignct">Hall High Balance Report (<?php echo $date_from.' To '.$date_to; ?>)</small>
					<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>
				</div>

				<?php
					
					#start report selection

					
					$hb_query = array("isretainership"=>"No","deletedata"=>0);
					$get_hb_data = mysqli_data_fetch($tbL58,'id,name',$hb_query,'array');

					?>
						<div class="block-element top-push-20">
							<div class="block-element top-push-5 box-border-thick">
								<table cellpadding="0" cellspacing="0" class="ft-xsml-size">
									<tr>
										<th width="50px" align="center">&nbsp;</th>
										<th width="150px" align="center">DATE</th>
										<th width="250px" align="center">NAME OF ORGANIZATION</th>
										<th width="200px" align="center">NAME OF HALL USED</th>
										<th width="150px" align="center">DEPOSIT MADE</th>
										<th width="150px" align="center">CONSUMPTION</th>
										<th width="150px" align="center">DURATION</th>
										<th width="150px" align="center">BALANCE</th>
									</tr>

									<?php

										if(is_array($get_hb_data)) {

											foreach($get_hb_data as $key => $val) {
												
												$sql = "SELECT id AS posId, posname, (SELECT SUM(bill_amount) FROM $tbL100 WHERE posid=posId AND billtype=4 AND biller={$val['id']} AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}') AS consumption, (SELECT COUNT(biller) FROM $tbL100 WHERE posid=posId AND billtype=4 AND biller={$val['id']} AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}') AS duration, (SELECT datelogged FROM $tbL100 WHERE posid=posId AND billtype=4 AND biller={$val['id']} AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' ORDER BY id ASC LIMIT 1) AS kickOffdate FROM {$tbL14} WHERE postype IN('Establishment') AND isfoodflash IN('Yes') AND deletedata=0)";

												$result = mysqli_query($mysqli,$sql);

												if(@mysqli_num_rows($result) >= 1) {
													

													$sql2 = "SELECT SUM(amount) AS payment FROM $tbL63 WHERE cspgid={$val['id']} AND transaction_type IN('Credit') AND paymode > 0 AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' AND deletedata=0)";

													$result2 = mysqli_query($mysqli,$sql2);
													$row2 = @ mysqli_fetch_array($result2,MYSQLI_ASSOC);

													$balance = 0; $num = 0;

													$total_consumption = 0; $total_pay = 0; $total_bal = 0;

													while($row = @ mysqli_fetch_array($result,MYSQLI_ASSOC)) {

														$balance = $row['consumption'] - $row2['payment'];

														$num += 1;

														$total_consumption = $total_consumption + $row['consumption'];
														$total_pay = $total_pay + $row2['payment'];
														$total_bal = $total_bal + $balance;

														?>
															<tr>
																<td width="50px" align="center"><?php echo $num; ?>.</td>
																<td width="150px" align="center"><?php echo date('d/m/y',strtotime($row['kickOffdate'])); ?></td>
																<td width="250px" align="center"><?php echo $val['name']; ?></td>
																<td width="200px" align="center"><?php echo $row['posname']; ?></td>
																<td width="150px" align="center"><?php echo number_format($row2['payment'],2); ?></td>
																<td width="150px" align="center"><?php echo number_format($row['consumption']); ?></td>
																<td width="150px" align="center"><?php echo $row['duration']; ?> day(s)</td>
																<td width="150px" align="center"><?php echo number_format($balance); ?></td>
															</tr>
														<?php
													}
												}
											}
										}

									?>

								</table>
							</div>
						</div>
					<?php

				?>
			</div>
		<?php
	}

?>