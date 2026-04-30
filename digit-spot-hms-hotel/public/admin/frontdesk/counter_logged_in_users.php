<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';


if(isset($_POST['applycountershiftstate'])) {

	$ids = $_POST['id']; $users = $_POST['user'];
	$csshift = $_POST['csshift']; $cstate = $_POST['cstate'];
	
	$pst_query = "";
	$pst_field = "";

	$posted = 0;

	for($i=0; $i < count($ids); $i++) {
		
		if($cstate[$i] == 'close-counter') {
			
			$pst_field = array("logstatus"=>"Closed");
			$pst_query = array("id"=>$ids[$i]);
			mysqli_data_update($tbL22,$pst_field,$pst_query);

			$pst_field = array("status"=>"Closed");
			$pst_query = array("counterid"=>$csshift[$i]);
			mysqli_data_update($tbL21,$pst_field,$pst_query);
		
			$pst_field = array("dateclosed"=>$server_get_date,"closetime"=>$server_get_time,"status"=>"Closed");
			$pst_query = array("counterid"=>$csshift[$i],"userid"=>$users[$i],"status"=>"Open");
			mysqli_data_update($tbL23,$pst_field,$pst_query);

			$pst_field = array("ispast"=>1);
			$pst_query = array("counterid"=>$csshift[$i],"userid"=>$users[$i],"ispast"=>0);
			mysqli_data_update($tbL25,$pst_field,$pst_query);

			$posted += 1;
		}
	}


	if(isset($posted) && $posted >= 1) {
		$htmlresult = '<div class="grey-theme light-red-font alignct top-pull-10 bottom-pull-10 bottom-push-30">'.$posted.' counter(s) closed successfully</div>';
	}
}


$counter_sql = "SELECT * FROM user_counter_log_tbl WHERE logstatus='Open'";
$result = wgetSQL($counter_sql);

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Counter Logged-in Users</b>: here you can see list of counters currently running. You can make them active or close
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="cs-height-30">
</div>

<?php echo $htmlresult; ?>

<form action="" method="post" class="nomargin">
	<div class="bottom-pull-10 x-scroll" align="center">
		
		<table cellpadding="3" cellspacing="0" style="width: 1000px !important">
			<tr>
				<th align="center">&nbsp;</th>
				<th align="center">Counter</th>
				<th align="center">Open Date</th>
				<th align="center">App User</th>
				<th align="center">Pending Withdrawals</th>
				<th align="center">Action</th>
			</tr>

			<?php
				
				$startnumbr = 0;

				if(is_array($result) && count($result) > 0) {
					
					$obl_sql = "SUM(openingbalance)"; $crd_sql = "SUM(collection)"; $rf_sql = "SUM(refunds)";
					$wgt_obl = ""; $wgt_crd = ""; $wgt_rf = ""; $balance_amount = 0;

					$app_user = ""; $counter_name = ""; $query_counter = "";

					foreach($result as $key => $row) {
					
						$startnumbr += 1;

						$app_user = idget_data($tbL7,$row['userid'],'staffname');
						$counter_name = idget_data($tbL19,$row['counterid'],'countername');

						$query_counter = "deletedata=0 AND counterid={$row['counterid']} AND userid={$row['userid']} AND ispast=0";

						#total opening balance
						$wgt_obl = mysqli_arithmetic_data($tbL25,$obl_sql,$query_counter);

						#total credit
						$wgt_crd = mysqli_arithmetic_data($tbL25,$crd_sql,$query_counter);

						#total debit
						$wgt_rf = mysqli_arithmetic_data($tbL25,$rf_sql,$query_counter);

						$balance_amount = ($wgt_crd + $wgt_obl) - $wgt_rf;
						
						?>
							<tr>
								<td class="cs-width-40" align="center">
									<?php echo $startnumbr; ?>.
									<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
									<input type="hidden" name="user[]" value="<?php echo $row['userid']; ?>">
									<input type="hidden" name="csshift[]" value="<?php echo $row['counterid']; ?>">
								</td>
								<td align="center">
									<h3 class="large nobold"><?php echo $counter_name; ?></h3>
								</td>
								<td align="center">
									<h3 class="large nobold"><?php echo date('d-m-Y',strtotime($row['datelogged'])).' '.$row['timelogged']; ?></h3>
								</td>
								<td class="cs-width-200" align="center">
									<h3 class="large nobold"><?php echo $app_user; ?></h3>
								</td>
								<td align="center">
									<h3 class="large nobold"><?php echo number_format($balance_amount,2); ?></h3>
								</td>
								<td class="cs-width-150" align="center">
									<select name="cstate[]" class="no-back-black default-text-font-bold" required>
										<option value="" selected="">Choose</option>
										<option value="close-counter">Close</option>
										<option value="use-counter">Stay Active</option>
									</select>
								</td>
							</tr>
						<?php
					}
				}
			?>

		</table>
		
	</div>
	
	<?php if(isset($startnumbr) && $startnumbr > 0): ?>

	<div class="top-push-30" align="center">
		<input type="submit" name="applycountershiftstate" value="Apply" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-30">
	</div>

	<?php else: ?>

		<div class="light-red-font alignct top-pull-10 bottom-pull-10 bottom-push-30">No active counters found</div>

	<?php endif; ?>

</form>