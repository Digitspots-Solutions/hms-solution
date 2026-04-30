<?php

	$smdl = "pos"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$sql_cspg = "SELECT * FROM $tbL58 WHERE status IN('Active') AND deletedata=0 ORDER BY name ASC";
	$dataset_cspg = wgetSQL($sql_cspg);

	$cspg_opt = ""; $arry = array(); $r_arry = array(); $nr_arry = array();

	foreach($dataset_cspg as $cspg_key => $cspg_val) {
		$cspg_opt .= '<option value="'.$cspg_val['id'].'">'.$cspg_val['name'].'</option>';
		array_push($arry,$cspg_val['id']);
		if($cspg_val['isretainership'] == 'Yes') { array_push($r_arry,$cspg_val['id']); }
		if($cspg_val['isretainership'] == 'No') { array_push($nr_arry,$cspg_val['id']); }
	}
	
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Here you see the corporate credit and debit transaction report
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="nc-width-100 x-scroll bottom-push-20">
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		
		<span class="ln-display-box float-left cs-width-150 right-push-10">
			<h4 class="large nobold bottom-pull-5">Start date</h4>
			<input type="date" name="startdate" id="startdate" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } ?>" title="From date">
		</span>

		<span class="ln-display-box float-left cs-width-150 right-push-10">
			<h4 class="large nobold bottom-pull-5">End date</h4>
			<input type="date" name="enddate" id="enddate" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } ?>" title="To date">
		</span>

		<span class="ln-display-box float-left cs-width-150 right-push-10">
			<h4 class="large nobold bottom-pull-5">Corporate</h4>
			<select name="cspgtype" id="cspgtype" onchange="getcspg(this.value)">
				<option value="" selected="selected">All</option>
				<option value="Retainership">Retainership</option>
				<option value="Non Retainership">Non Retainership</option>
			</select>
		</span>

		<span class="ln-display-box float-left cs-width-250 right-push-10">
			<select name="cspg[]" id="cspg" size="2" multiple>
				<option value="" selected="selected">All</option>
				<?php echo $cspg_opt; ?>
			</select>
		</span>
		
		<span class="ln-display-box float-right">
			<input type="submit" name="searchbutton" value="Run" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button"> &nbsp;
			<input type="button" value="Print" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 black-white-state sml-rounded-button" onclick="window.print()">
		</span>
		<span class="block-element new-line-space">
		</span>
	</form>
</div>

<div id="section-to-print" class="block-element pads15">

	<?php

		if(isset($_POST['searchbutton'])) {
			
			$startdate = date('d-m-Y',strtotime($_POST['startdate']));
			$enddate = date('d-m-Y',strtotime($_POST['enddate']));

			?>
				<div class="bottom-push-20" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
					</div>
					<div class="cs-width-500 margin-auto-ct alignct">
						<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold nomargin">Corporate/Group Transaction Report</h3>
						<h3 class="large nobold nomargin">Date Period Between <?php echo $startdate; ?> And <?php echo $enddate; ?></h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
					</div>
				</div>

				<table cellpadding="3" cellspacing="0" border="1">
					<tr>
						<td class="alignct default-text-font-bold">Corporate Name</td>
						<td class="alignct default-text-font-bold">Credit</td>
						<td class="alignct default-text-font-bold">Debit</td>
						<td class="alignct default-text-font-bold">Balance</td>
					</tr>

					<?php

						$cspgtype = $_POST['cspgtype'];
						$cspg = $_POST['cspg'];

						if(empty($cspg[0]) && empty($cspgtype)) { $r_cspg = $arry; }
						elseif(empty($cspg[0]) && $cspgtype == 'Retainership') { $r_cspg = $r_arry; }
						elseif(empty($cspg[0]) && $cspgtype == 'Non Retainership') { $r_cspg = $nr_arry; }
						else { $r_cspg = $cspg; }

						$cspg_name = ""; $cspg_bal = "";
						$totalcredit = 0; $totaldebit = 0;

						foreach($r_cspg as $group) {

							$cspg_name = idget_data($tbL58,$group,'name');
							$cspg_bal = idget_data($tbL58,$group,'creditlimit');

							$sql_cr = "SELECT SUM(amount) AS totalcredit FROM $tbL63 WHERE cspgid={$group} AND transaction_type='Credit' AND transaction_date >= '{$_POST['startdate']}' AND transaction_date <= '{$_POST['enddate']}' AND deletedata=0"; $dataset_cr = wgetSQL($sql_cr);

							$sql_dbt = "SELECT SUM(amount) AS totaldebit FROM $tbL63 WHERE cspgid={$group} AND transaction_type='Debit' AND transaction_date >= '{$_POST['startdate']}' AND transaction_date <= '{$_POST['enddate']}' AND deletedata=0"; $dataset_dbt = wgetSQL($sql_dbt);

							if((!empty($dataset_cr[0]['totalcredit']) && $dataset_cr[0]['totalcredit'] > 0) || (!empty($dataset_dbt[0]['totaldebit']) && $dataset_dbt[0]['totaldebit'] > 0)) {

								$totalcredit = $totalcredit + $dataset_cr[0]['totalcredit'];
								$totaldebit = $totaldebit + $dataset_dbt[0]['totaldebit'];

								?>
									<tr>
										<td class="cs-width-400 alignlt"><?php echo $cspg_name; ?></td>
										<td class="alignrt"><?php echo number_format($dataset_cr[0]['totalcredit'],2); ?></td>
										<td class="alignrt"><?php echo number_format($dataset_dbt[0]['totaldebit'],2); ?></td>
										<td class="alignrt"><?php echo number_format($cspg_bal,2); ?></td>
									</tr>
								<?php
							}
						}
					?>

					<tr>
						<td class="alignlt default-text-font-bold">Total</td>
						<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($totalcredit,2); ?></td>
						<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($totaldebit,2); ?></td>
						<td class="alignrt">&nbsp;</td>
					</tr>

				</table>
			<?php
		}

	?>

</div>


<script>

	function getcspg(cspg) {

		if(cspg != '') {

			const ret = (cspg == 'Retainership') ? 'Yes' : 'No';

			sqldatastring.sql = "SELECT * FROM cspg_tbl WHERE isretainership='"+ret+"' AND status='Active' AND deletedata=0 ORDER BY name ASC"; sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '';
				vhtml += '<option value="" selected="selected">All</option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
				}

				writeObjheader('cspg',vhtml);
			}
		}
	}

</script>