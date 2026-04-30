<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you enable weekend fares by selecting the day
 	</span>
 	<span class="ln-display-box float-right right-pull-5">
		<h3 class="large nobold default-text-font-bold">Enable Weekend Fares</h3>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$day_arry = array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");
	$post_days = array();

	#---------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton'])) {
		
		if(isset($_POST['checker'])) {

			$post_result = 0;
			
			foreach($_POST['checker'] as $checker) {
				array_push($post_days,$checker);
				$sql_query = array("day"=>$checker);
				$sql = array("status"=>"Active");
				mysqli_data_update($tbL146,$sql,$sql_query);
				$post_result += 1;
			}

			if(isset($post_result) && $post_result >= 1) {
				$saynotify = 1;
				$notifytype = 2;

				$post_header = "Notification";
				$post_message = "Selected weekly tariff are now active";
			}
		}

		foreach($day_arry as $day) {
			if(!in_array($day, $post_days)) {
				$sql_query = array("day"=>$day);
				$sql = array("status"=>"InActive");
				mysqli_data_update($tbL146,$sql,$sql_query);
			}
		}
	}

	#---------------------------------------------------------------------------------------------------------------
	
	$additionalQuery = " LIMIT 1";

	$mon = array("day"=>"monday");
	$mon_data = mysqli_data_fetch($tbL146,'status',$mon,'noarray');

	$tue = array("day"=>"tuesday");
	$tue_data = mysqli_data_fetch($tbL146,'status',$tue,'noarray');

	$wed = array("day"=>"wednesday");
	$wed_data = mysqli_data_fetch($tbL146,'status',$wed,'noarray');

	$thur = array("day"=>"thursday");
	$thur_data = mysqli_data_fetch($tbL146,'status',$thur,'noarray');

	$fri = array("day"=>"friday");
	$fri_data = mysqli_data_fetch($tbL146,'status',$fri,'noarray');

	$sat = array("day"=>"saturday");
	$sat_data = mysqli_data_fetch($tbL146,'status',$sat,'noarray');

	$sun = array("day"=>"sunday");
	$sun_data = mysqli_data_fetch($tbL146,'status',$sun,'noarray');

?>

<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
		
	<div class="block-element sml-rounded-button noscroll">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="100px" class="box-border-thick-left box-border-thick-right dark-black-theme" align="center">Select All</th>
				<th width="100px" class="box-border-thick-right" align="center">Mon</th>
				<th width="100px" class="box-border-thick-right" align="center">Tue</th>
				<th width="100px" class="box-border-thick-right" align="center">Wed</th>
				<th width="100px" class="box-border-thick-right" align="center">Thur</th>
				<th width="100px" class="box-border-thick-right" align="center">Fri</th>
				<th width="100px" class="box-border-thick-right" align="center">Sat</th>
				<th width="100px" class="box-border-thick-right" align="center">Sun</th>
			</tr>
			<tr>
				<td width="100px" class="box-border-thick-left box-border-thick-right alignct grey-theme">
					<input type="checkbox" id="checkall" onclick="applyCheck(this.lang,'checkall')" lang="off">
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d1" value="monday"<?php if(isset($mon_data[0]) && $mon_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d2" value="tuesday"<?php if(isset($tue_data[0]) && $tue_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d3" value="wednesday"<?php if(isset($wed_data[0]) && $wed_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d4" value="thursday"<?php if(isset($thur_data[0]) && $thur_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d5" value="friday"<?php if(isset($fri_data[0]) && $fri_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d6" value="saturday"<?php if(isset($sat_data[0]) && $sat_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
				<td width="100px" class="box-border-thick-right" align="center">
					<input type="checkbox" name="checker[]" id="d7" value="sunday"<?php if(isset($sun_data[0]) && $sun_data[0] == 'Active') { ?> checked<?php } ?>>
				</td>
			</tr>
		</table>
	</div>

	<div class="block-element alignct top-push-30">
		<input type="submit" name="submitbutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
	</div>
</form>

<script>
	
	function applyCheck(str,selector) {
		var i,picker=document.getElementById(selector);
		if(str == 'off') {
			picker.lang = 'on';
			for(i=1; i <= 7; i++) {
				document.getElementById('d'+i).checked = true;
			}
		} else if(str == 'on') {
			picker.lang = 'off';
			for(i=1; i <= 7; i++) {
				document.getElementById('d'+i).checked = false;
			}
		}
	}

</script>