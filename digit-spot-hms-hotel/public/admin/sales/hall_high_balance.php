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
		<span class="ln-display-box float-left cs-width-150 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left cs-width-20">
			&nbsp;
		</span>
		<span class="ln-display-box float-left cs-width-150 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left cs-width-200 right-pull-10">
			<small class="block-element bottom-push-3 left-pull-3">Corporate Type</small>
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
				<option value="Retainership">Retainership</option>
				<option value="Non Retainership">Non Retainership</option>
			</select>
		</span>
		<span class="ln-display-box float-left cs-width-250 right-pull-10">
			<small class="block-element bottom-push-3 left-pull-3">Corporates</small>
			<select name="corporates" id="corporates">
				<?php
					if(isset($_POST['corporates']) && !empty($_POST['corporates']) && is_numeric($_POST['corporates'])) {
						$cspg = idget_data($tbL58,$_POST['corporates'],'name');
						?>
							<option value="<?php echo $_POST['corporates']; ?>" selected="selected"><?php echo $cspg; ?></option>
						<?php
					} else {
						?>
							<option value="All" selected>All</option>
						<?php
					}

					echo $cspg_opt;
				?>
			</select>
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

					//$sql = "SELECT id AS posId, (SELECT biller FROM {$tbL100} WHERE posid=posId AND billtype=4 AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' GROUP BY biller) AS billerId, (SELECT name FROM {$tbL58} WHERE id=billerId AND isretainership IN('No')) AS firm, (SELECT SUM(bill_amount) FROM {$tbL100} WHERE posid=posId AND billtype=4 AND biller=billerId AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' GROUP BY biller) AS consumption, (SELECT COUNT(biller) FROM {$tbL100} WHERE posid=posId AND billtype=4 AND biller=billerId AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' GROUP BY biller) AS duration, (SELECT datelogged FROM {$tbL100} WHERE posid=posId AND billtype=4 AND biller=billerId AND datelogged BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}'  GROUP BY biller ORDER BY id DESC LIMIT 1) AS kickOffdate, posname FROM {$tbL14} WHERE iscounter IN('Yes') AND isfoodflash IN('Yes') AND deletedata=0";

					if(($_POST['corporatetype'] == 'All' && $_POST['corporates'] == 'All') || ($_POST['corporates'] == 'All')) {
						$sql = "SELECT GROUP_CONCAT(posid) AS outlets, biller FROM {$tbL100} WHERE billtype=4 AND biller >= 1 AND datelogged >= '{$_POST['fieldset1']}' AND datelogged <= '{$_POST['fieldset2']}' AND status IN('Completed') AND isreversed=0 AND deletedata=0 GROUP BY biller";
					} else {
						$sql = "SELECT GROUP_CONCAT(posid) AS outlets, biller FROM {$tbL100} WHERE billtype=4 AND biller IN({$_POST['corporates']}) AND datelogged >= '{$_POST['fieldset1']}' AND datelogged <= '{$_POST['fieldset2']}' AND status IN('Completed') AND isreversed=0 AND deletedata=0 GROUP BY biller";
					}

					$result = mysqli_query($mysqli,$sql);

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

										if(@mysqli_num_rows($result) == true) {

											$ftH = ""; $ftHid = ""; $actualOutlets = "";
											
											$balance = 0; $num = 0; $sql2 = "";

											$total_consumption = 0; $total_pay = 0; $total_bal = 0;

											while($row = @ mysqli_fetch_array($result,MYSQLI_ASSOC)) {

												if($_POST['corporatetype'] == 'All') {
													$sql3 = "SELECT name AS firm FROM $tbL58 WHERE id={$row['biller']}";
												} else {
													if($_POST['corporatetype'] == 'Non Retainership') {
														$sql3 = "SELECT name AS firm FROM $tbL58 WHERE id={$row['biller']} AND isretainership IN('No')";
													} elseif($_POST['corporatetype'] == 'Retainership') {
														$sql3 = "SELECT name AS firm FROM $tbL58 WHERE id={$row['biller']} AND isretainership IN('Yes')";
													}
												}

												$result3 = mysqli_query($mysqli,$sql3);
												$row3 = @ mysqli_fetch_array($result3,MYSQLI_ASSOC);

												$sql4 = "SELECT GROUP_CONCAT(id) AS ftHallids, GROUP_CONCAT(posname) AS ftHall FROM $tbL14 WHERE id IN({$row['outlets']}) AND iscounter IN('Yes') AND isfoodtype IN('No')";

												$result4 = mysqli_query($mysqli,$sql4);
												$row4 = @ mysqli_fetch_array($result4,MYSQLI_ASSOC);

												$ftH = explode(',',$row4['ftHall']);
												$ftHid = explode(',',$row4['ftHallids']);

												$hName = ""; $hId = "";

												for($i=0; $i<count($ftH); $i++) {
													if(stristr($ftH[$i],'Hall') || stristr($ftH[$i],'Banquet')) {
														$hName .= $ftH[$i].',';
														$hId .= $ftHid[$i].',';
													}
												}

												$hName = substr_replace($hName,'',-1);
												$hId = substr_replace($hId,'',-1);


												if(is_array($row4) && !empty($hId) && is_array($row3) && !empty($row3['firm'])) {
												
													$sql1 = "SELECT SUM(bill_amount) AS consumption FROM $tbL100 WHERE posid IN({$hId}) AND biller={$row['biller']} AND billtype=4 AND datelogged >= '{$_POST['fieldset1']}' AND datelogged <= '{$_POST['fieldset2']}' AND status IN('Completed') AND isreversed=0 AND deletedata=0";

													$result1 = mysqli_query($mysqli,$sql1);
													$row1 = @ mysqli_fetch_array($result1,MYSQLI_ASSOC);

													$sql1b = "SELECT datelogged AS kickOffdate FROM $tbL100 WHERE biller={$row['biller']} AND billtype=4 AND datelogged >= '{$_POST['fieldset1']}' AND datelogged <= '{$_POST['fieldset2']}' AND status IN('Completed') AND isreversed=0 AND deletedata=0 ORDER BY id DESC LIMIT 1";

													$result1b = mysqli_query($mysqli,$sql1b);
													$row1b = @ mysqli_fetch_array($result1b,MYSQLI_ASSOC);

													$sql2 = "SELECT SUM(amount) AS payment FROM $tbL63 WHERE cspgid={$row['biller']} AND transaction_type IN('Credit') AND paymode > 0 AND transaction_date BETWEEN '{$_POST['fieldset1']}' AND '{$_POST['fieldset2']}' AND deletedata=0";

													$result2 = mysqli_query($mysqli,$sql2);
													$row2 = @ mysqli_fetch_array($result2,MYSQLI_ASSOC);

													$sql5 = "SELECT COUNT(biller) AS duration FROM $tbL100 WHERE posid IN({$hId}) AND biller={$row['biller']} AND billtype=4 AND datelogged >= '{$_POST['fieldset1']}' AND datelogged <= '{$_POST['fieldset2']}' AND status IN('Completed') AND isreversed=0 AND deletedata=0 GROUP BY datelogged";

													$result5 = mysqli_query($mysqli,$sql5);
													$row5 = @ mysqli_fetch_array($result5,MYSQLI_ASSOC);

												
													if($row1['consumption'] >= 1 || $row2['payment'] >= 1) {

														$balance = $row1['consumption'] - $row2['payment'];

														$num += 1;

														$total_consumption = $total_consumption + $row1['consumption'];
														$total_pay = $total_pay + $row2['payment'];
														$total_bal = $total_bal + $balance;

														?>
															<tr>
																<td width="50px" align="center"><?php echo $num; ?>.</td>
																<td width="150px" align="center"><?php echo date('d/m/y',strtotime($row1b['kickOffdate'])); ?></td>
																<td width="250px" align="center"><?php echo $row3['firm']; ?></td>
																<td width="200px" align="center"><?php echo $hName; ?></td>
																<td width="150px" align="center"><?php echo number_format($row2['payment'],2); ?></td>
																<td width="150px" align="center"><?php echo number_format($row1['consumption'],2); ?></td>
																<td width="150px" align="center"><?php echo $row5['duration']; ?> day(s)</td>
																<td width="150px" align="center"><?php echo number_format($balance,2); ?></td>
															</tr>
														<?php
													}
												}

												$sql = ""; $sql1 = ""; $sql2 = ""; $sql3 = ""; $sql4 = ""; $sql5 = "";
												$ftHid = ""; $ftH = ""; $hId = ""; $hName = "";
											}

											?>
												<tr>
													<td width="50px" align="center">&nbsp;</td>
													<td width="150px" align="center">Total</td>
													<td width="250px" align="center">&nbsp;</td>
													<td width="200px" align="center">&nbsp;</td>
													<td width="150px" align="center" class="default-text-font-bold"><?php echo number_format($total_pay,2); ?></td>
													<td width="150px" align="center" class="default-text-font-bold"><?php echo number_format($total_consumption,2); ?></td>
													<td width="150px" align="center">&nbsp;</td>
													<td width="150px" align="center" class="default-text-font-bold"><?php echo number_format($total_bal,2); ?></td>
												</tr>
											<?php
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
				vhtml = '<option value="All" selected="selected">All</option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
				}

				writeObjheader('corporates',vhtml);
			}
		}
	}

</script>