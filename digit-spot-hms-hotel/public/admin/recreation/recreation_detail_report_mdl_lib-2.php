<?php

	$smdl = "recreation"; $logs = escape_data($_GET['logs']);

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$duration = arrayset_form($recreation_duration,'select');
	
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Here you can see the list of recreation list
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
		
		<span class="ln-display-box float-left cs-width-130 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Start date</h4>
			<input type="date" name="startdate" id="startdate" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" title="From date">
		</span>

		<span class="ln-display-box float-left cs-width-130 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">End date</h4>
			<input type="date" name="enddate" id="enddate" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>" title="To date">
		</span>

		<span class="ln-display-box float-left cs-width-150 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Recreation Type</h4>
			<select name="recreationtype" id="recreationtype">
				<option value="New Membership">New Membership</option>
				<option value="Renewal">Renewal</option>
			</select>
		</span>

		<span class="ln-display-box float-left cs-width-150 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Membership Plan</h4>
			<select name="memberplan" id="memberplan">
				<option value="" selected="selected">All</option>
				<option value="Single">Single</option>
				<option value="Couple">Couple</option>
				<option value="Family">Family</option>
			</select>
		</span>

		<span class="ln-display-box float-left cs-width-120 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Membership Type</h4>
			<select name="membertype" id="membertype">
				<option value="" selected="selected">All</option>
				<option value="Individual">Individual</option>
				<option value="Corporate">Corporate</option>
				<option value="Complimenetary">Complimenetary</option>
			</select>
		</span>

		<span class="ln-display-box float-left cs-width-80 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Status</h4>
			<select name="status" id="status">
				<option value="" selected="selected">All</option>
				<option value="Active">Active</option>
				<option value="InActive">InActive</option>
				<option value="Expires In">Expires In</option>
			</select>
		</span>

		<span class="ln-display-box float-left cs-width-120 right-push-15">
			<h4 class="large nobold default-text-font-bold bottom-pull-5">Duration</h4>
			<select name="duration" id="duration">
				<option value="" selected="selected">All</option>
				<?php echo $duration; ?>
			</select>
		</span>
		
		<span class="ln-display-box float-right top-pull-10">
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


			if(isset($_POST['recreationtype']) && $_POST['recreationtype'] == 'New Membership') {
				$rcr_type = " AND archivedata=0";
			} else {
				$rcr_type = " AND archivedata=1";
			}

			if(isset($_POST['memberplan']) && !empty($_POST['memberplan'])) {
				$rcr_plan = " AND membership_type='{$_POST['memberplan']}'";
			} else {
				$rcr_plan = "";
			}

			if(isset($_POST['membertype']) && $_POST['membertype'] == 'Individual') {
				$rcr_reg = " AND complimentary_src=0 AND corporate_type=0";
			} elseif(isset($_POST['membertype']) && $_POST['membertype'] == 'Complimentary') {
				$rcr_reg = " AND complimentary_src > 0 AND corporate_type=0";
			} elseif(isset($_POST['membertype']) && $_POST['membertype'] == 'Corporate') {
				$rcr_reg = " AND complimentary_src=0 AND corporate_type > 0";
			} else {
				$rcr_reg = "";
			}

			if(isset($_POST['status']) && $_POST['status'] == 'Active') {
				$rcr_status = " AND status=1";
				$rcr_date = " AND datelogged >= '{$_POST['startdate']}' AND datelogged <= '{$_POST['enddate']}'";
			} elseif(isset($_POST['status']) && $_POST['status'] == 'InActive') {
				$rcr_status = " AND status=0";
				$rcr_date = " AND datelogged >= '{$_POST['startdate']}' AND datelogged <= '{$_POST['enddate']}'";
			} elseif(isset($_POST['status']) && $_POST['status'] == 'Expires In') {
				$rcr_status = " AND enddate >= '{$_POST['startdate']}' AND enddate <= '{$_POST['enddate']}'";
				$rcr_date = "";
			} else {
				$rcr_status = "";
				$rcr_date = " AND datelogged >= '{$_POST['startdate']}' AND datelogged <= '{$_POST['enddate']}'";
			}

			if(isset($_POST['duration']) && !empty($_POST['duration'])) {
				$rcr_duration = " AND plan={$_POST['duration']}";
			} else {
				$rcr_duration = "";
			}

			
			$sql = "SELECT * FROM $tbL105 WHERE deletedata=0".$rcr_type.$rcr_plan.$rcr_reg.$rcr_status.$rcr_duration.$rcr_date;
			$dataset = wgetSQL($sql);


			?>
				<div class="bottom-push-20" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
					</div>
					<div class="cs-width-500 margin-auto-ct alignct">
						<h2 class="xlarge nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold nomargin">Recreation Details Report (from <?php echo $startdate; ?> to <?php echo $enddate; ?>)</h3><h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
					</div>
				</div>

				<table cellpadding="3" cellspacing="0">
					<tr>
						<td class="alignct default-text-font-bold">Recreation No.</td>
						<td class="alignct default-text-font-bold">Membership Period</td>
						<td class="alignct default-text-font-bold">Membership Type</td>
						<td class="alignct default-text-font-bold">Membership Plan</td>
						<td class="alignct default-text-font-bold">Head Details</td>
						<td class="alignct default-text-font-bold">Spouse Details</td>
						<td class="alignct default-text-font-bold">Amount Paid</td>
						<td class="alignct default-text-font-bold">Status</td>
					</tr>

					<?php
						if(is_array($dataset) && count($dataset) > 0) {
							
							$recreation_plan = "";
							$_rcr_reg = ""; $addLabel = ""; $rcr_title = "";
							$spouse_name = ""; $spouse_dob = "";

							$amt_query = ""; $amts = 0; $total_rcr_amount = 0;

							foreach($dataset as $key => $val) {
								
								if($val['complimentary_src'] == 0 && $val['corporate_type'] == 0) {
									$_rcr_reg = "Individual";
									$addLabel = "";
								} elseif($val['complimentary_src'] >= 1 && $val['corporate_type'] == 0) {
									$_rcr_reg = "Complimentary";
									$addLabel = " (".idget_data($tbL33,$val['complimentary_src'],'name').")";
								} elseif($val['complimentary_src'] == 0 && $val['corporate_type'] >= 1) {
									$_rcr_reg = "Corporate";
									$addLabel = " (".idget_data($tbL58,$val['corporate_type'],'name').")";
								}

								$recreation_plan = arrayget_key($recreation_duration,$val['plan']);

								$rcr_title = idget_data($tbL42,$val['salutation'],'name');
								$spouse_name = idget_fdata($tbL106,'memberid',$val['id'],'flname');
								$spouse_dob = idget_fdata($tbL106,'memberid',$val['id'],'dob');

								$amt_sql = "SUM(amount)"; $amt_query = "memberid={$val['id']} AND startdate='{$val['startdate']}' AND enddate='{$val['enddate']}' AND deletedata=0";
								$amts = mysqli_arithmetic_data($tbL107,$amt_sql,$amt_query);

								$status_name = arrayget_key($status_tag,$val['status']);
								$status_color = ($val['status'] == 1) ? "forest-green-font" : "light-red-font";

								$total_rcr_amount = $total_rcr_amount + $amts;

								?>
									<tr>
										<td class="alignct"><a href="javascript:void(0)" class="blue-font" onclick="openform(<?php echo $val['id']; ?>)"><?php echo $val['recreation_number']; ?></a></td>
										<td class="alignct"><?php echo date("d/m/Y",strtotime($val['startdate'])).' - '.date("d/m/Y",strtotime($val['enddate'])); ?><p class="top-pull-3">(<?php echo $recreation_plan; ?>)</p></td>
										<td class="alignct"><?php echo $_rcr_reg.$addLabel; ?></td>
										<td class="alignct"><?php echo $val['membership_type']; ?></td>
										<td class="alignlt">Name: <?php echo $rcr_title.' '.$val['firstname'].' '.$val['lastname']; ?><br>DOB: <?php if(!empty($val['dob'])) { echo date("d/m/Y",strtotime($val['dob'])); } ?><br>Mobile No: <?php echo $val['mobile']; ?><br>Email: <?php echo $val['emailaddress']; ?><br>Profession: <?php echo $val['profession']; ?></td>
										<td class="alignlt">Name: <?php echo $spouse_name; ?><br>DOB: <?php if(!empty($spouse_dob)) { echo date("d/m/Y",strtotime($spouse_dob)); } ?></td>
										<td class="alignct">&#8358; <?php echo number_format($amts,2); ?></td>
										<td class="alignct <?php echo $status_color; ?>"><?php echo $status_name; ?></td>
									</tr>
								<?php

								$_rcr_reg = "";
							}

							?>
								<tr>
									<td colspan="6" class="alignlt default-text-font-bold">Total</td>
									<td class="alignct default-text-font-bold">&#8358; <?php echo number_format($total_rcr_amount,2); ?></td>
									<td class="alignct">&nbsp;</td>
								</tr>
							<?php
						}
					?>

				</table>
				<p class="top-pull-10 ft-sml-size">
					<?php echo count($dataset); ?> found
				</p>
			<?php
		}
		
	?>

</div>

<script>

	function openform(id) {
		
		var hr = parent.document.getElementById('frame-header');
		var tabs = hr.getElementsByTagName('div');

		var wrk = parent.document.getElementById('frame-work');
		var wspace = wrk.getElementsByTagName('div');

		for(var i=0; i < wspace.length; i++) {
			var cls = wspace[i].getAttribute('style');
			if(cls !== null) {
				cls = cls.replace('display: block; ','');
				console.log(cls);
				if(cls.indexOf('display: none;') == -1) { wspace[i].setAttribute('style','display: none; '+cls); }
			}
		}

		parent.document.getElementById('curpage').value = 10000;
		parent.document.getElementById('th10000').click();
		parent.document.getElementById('td10000').style.display = 'block';
		parent.document.getElementById('frame10000').src = filePath+'public/admin/recreation.php?logs=Recreation&dtl='+id;
	}

</script>