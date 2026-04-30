<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_ious = datalist_fetch('deletedata',0,$tbL153,'iou_no','iou_no');

$get_account_wdr = idget_fdata($tbL159,'account_type','iou-fund','withdraws');
$get_account_bal = idget_fdata($tbL159,'account_type','iou-fund','balance');

#-----------------------------------------------------------------------------------------------------

if(isset($_POST['disbursebutton']) && isset($_POST['wgid'])) {
		
	$record = escape_data($_POST['wgid']);
	$get_iou_no = idget_data($tbL153,$record,'iou_no');
	$pst_amount = $_POST['amount2disburse'];

	if($pst_amount > $get_account_bal) {

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Insufficient fund to complete transaction";

	} else {
		
		$new_wdr = $get_account_wdr + $pst_amount;
		$new_balance = $get_account_bal - $pst_amount;

		$f_iou_query = array("account_type"=>"iou-fund");
		$f_iou_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
		mysqli_data_update($tbL159,$f_iou_sql,$f_iou_query);
		
		
		$payment_query = array("id"=>$record);
		$payment_sql = array("amount"=>$pst_amount,"status"=>"Disbursed","disbursedby"=>$userSignedIn);
		mysqli_data_update($tbL153,$payment_sql,$payment_query);

		$get_iou_no = idget_data($tbL153,$record,'iou_no');
		$get_pr_no = idget_data($tbL153,$record,'pr_no');

		if(!empty($get_pr_no)) {
			$f_iou_query = array("iou_no"=>$get_iou_no);
			$f_iou_sql = array("amount"=>$pst_amount,"status"=>"Approved");
			mysqli_data_update($tbL161,$f_iou_sql,$f_iou_query);
		}

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "amount is disbursed successfully";
		
		$islogfile = 1;
		$logfile_msg = "amount (".$pst_amount.") disbursed for iou number (".$get_iou_no.") by this user";
	}
}

#-----------------------------------------------------------------------------------------------------


if(isset($_POST['amount2retire']) && !empty($_POST['amount2retire'])) {

	$record = $_POST['wgid'];

	$get_iou_no = idget_data($tbL153,$record,'iou_no');
	$get_amount = idget_data($tbL153,$record,'amount');
	$get_pr_amount = $_POST['amount2retire'];

	if($get_pr_amount > 0) {

		#reconciliation fund when there is variance
		$pst_amount = $get_amount - $get_pr_amount;
		$variance = $get_pr_amount;

		$new_wdr = $get_account_wdr - $get_pr_amount;
		$new_balance = $get_account_bal + $get_pr_amount;

		$f_iou_query = array("account_type"=>"iou-fund");
		$f_iou_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
		mysqli_data_update($tbL159,$f_iou_sql,$f_iou_query);

		$f_iou_query = array("iou_no"=>$get_iou_no);
		$f_iou_sql = array("amount"=>$pst_amount);
		mysqli_data_update($tbL161,$f_iou_sql,$f_iou_query);

	} else {
		$pst_amount = $get_amount;
		$variance = 0;
	}

	$rt_query = array("id"=>$record);
	$rt_sql = array("pr_amount"=>$pst_amount,"variance_amount"=>$variance,"retiredby"=>$userSignedIn,"isprocess"=>1);
	mysqli_data_update($tbL153,$rt_sql,$rt_query);	
	
	$isposted += 1;

	if(isset($isposted) && $isposted > 0) {
		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "selected iou are retired successfully";
		
		$islogfile = 1;
		$logfile_msg = "iou retired as expenditure by this user";
	}
}

if((isset($_POST['ftask']) && $_POST['ftask'] == 'retire') && isset($_POST['checkers'])) {
		
	$rows = $_POST['checkers']; $isposted = 0; $get_amount = 0; $get_pr_amount = 0;

	//foreach($rows as $id) {
		
		$record = $rows[0];
		
		$get_status = idget_data($tbL153,$record,'status');
		$get_iou_no = idget_data($tbL153,$record,'iou_no');
		$get_amount = idget_data($tbL153,$record,'amount');
		$get_pr_amount = idget_data($tbL153,$record,'pr_amount');

		if(!empty($get_status) && $get_status == 'Disbursed') {

			if(empty($get_pr_no) || $get_pr_no == '') {

				?>

				<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox" class="fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt">
						<form id="retireform" action="" method="post" autocomplete="off" onsubmit="doretire(event)">
							<div class="pads10 alignlt">
								<h2 class="large nobold default-text-font-bold">Indicate amount you would like to retire for <u><?php echo $get_iou_no; ?></u></h2><br>
								<fieldset>
									<legend class="ft-sml-size">Disbursed Amount (&#8358;<?php echo number_format($get_amount,2); ?>)</legend>
									<p class="pads10">
										<input type="text" name="wgtamount2retire" id="wgtamount2retire" placeholder="Enter amount retiring" onkeyup="numberinputFormat(this.value,this.id,'amount2retire')" class="no-back-black default-text-font-bold" required>
									</p>
								</fieldset>
								
								<input type="hidden" name="amount2retire" id="amount2retire" required>
								<input type="hidden" name="wgid" id="wgid" value="<?php echo $record; ?>">
							</div>
							<div class="top-pull-15 motion">
								<input type="submit" name="retirebutton" id="retirebutton" value="Retire" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">
								<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>
							</div>
						</form>
					</div>
				</div>

				<?php

			} else {

				if($get_pr_amount > $get_amount) {
					
					#reconciliation fund when there is variance
					$pst_amount = $get_pr_amount - $get_amount;

					$new_wdr = $get_account_wdr + $pst_amount;
					$new_balance = $get_account_bal - $pst_amount;

					$f_iou_query = array("account_type"=>"iou-fund");
					$f_iou_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
					mysqli_data_update($tbL159,$f_iou_sql,$f_iou_query);

					$f_iou_query = array("iou_no"=>$get_iou_no);
					$f_iou_sql = array("amount"=>$get_pr_amount);
					mysqli_data_update($tbL161,$f_iou_sql,$f_iou_query);

				} elseif($get_amount > $get_pr_amount) {

					#reconciliation fund when there is variance
					$pst_amount = $get_amount - $get_pr_amount;

					$new_wdr = $get_account_wdr - $pst_amount;
					$new_balance = $get_account_bal + $pst_amount;

					$f_iou_query = array("account_type"=>"iou-fund");
					$f_iou_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
					mysqli_data_update($tbL159,$f_iou_sql,$f_iou_query);

					$f_iou_query = array("iou_no"=>$get_iou_no);
					$f_iou_sql = array("amount"=>$get_pr_amount);
					mysqli_data_update($tbL161,$f_iou_sql,$f_iou_query);
				}

				$rt_query = array("id"=>$record);
				$rt_sql = array("retiredby"=>$userSignedIn,"isprocess"=>1);
				if($get_pr_amount == 0) { $rt_sql['pr_amount'] = $get_amount; }
				mysqli_data_update($tbL153,$rt_sql,$rt_query);

				$pst_query = "";
				$pst_field = array("account"=>$get_iou_no,"counterid"=>0,"userid"=>$userSignedIn,"paid_amount"=>$get_pr_amount,"actual_amount"=>$get_pr_amount,"diff_amount"=>0,"account_src"=>"iou","account_type"=>"Debit","status"=>"Received","receivedby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL158,$pst_field,$pst_query);

				$pst_amount = 0; $new_wdr = ""; $new_balance = "";
				
				$isposted += 1;
			}
		}
	//}

	if(isset($isposted) && $isposted > 0) {
		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "selected iou are retired successfully";
		
		$islogfile = 1;
		$logfile_msg = "iou retired as expenditure by this user";
	}
}

#-----------------------------------------------------------------------------------------------------

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can disburse or retire funds from purchase-request and iou. After the search, select the record and click either <u>disburse fund</u> or <u>retire fund</u> button
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
			<span class="ln-display-box float-left cs-width-100 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By IOU</h3>
				<input list="d-iou" name="iou" id="iou" placeholder="Type or select" class="nopads no-back-black">
				<datalist id="d-iou">
					<?php echo $get_ious; ?>
				</datalist>
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Status</h3>
				<select name="status" id="status" class="nopads no-back-black">
					<option value="" selected>All</option>
					<option value="Disbursed">Disbursed</option>
					<option value="Pending">Pending</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform',0)" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>
<div class="top-pull-30" align="left">
	<p class="bottom-pull-20">
		<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold right-push-15" onclick="jsForm('datasheet','disburse')">Disburse Fund</a> <a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 dark-black-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('datasheet','retire')">Retire Fund</a>
	</p>
	<div class="x-scroll">
		<div class="cs-width-1500">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL153;
					
					$startnumbr = 0;
					$keywords = "";

					if(isset($_GET['iou']) && !empty($_GET['iou'])) {
						$keywords .= " AND iou_no='{$_GET['iou']}'";
					}

					if(isset($_GET['status']) && !empty($_GET['status'])) {
						$keywords .= " AND status='{$_GET['status']}'";
					}

					if((isset($_GET['startdate']) && !empty($_GET['startdate'])) && (isset($_GET['endate']) && !empty($_GET['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_GET['startdate']}' AND '{$_GET['endate']}' ORDER BY id ASC";
					} else {
						if(!isset($_GET['iou']) || empty($_GET['iou'])) {
							$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}' ORDER BY id DESC";
						}
					}

					$queryset = "deletedata=0".$keywords;

					$keys = array(
						"iou_no"=>"(fx)iou no.",
						"pr_no"=>"(fx)pr no.",
						"pr_type"=>"pr type",
						"amount"=>"(nf)amount disbursed &#8358;",
						"pr_amount"=>"(nf)amount procured &#8358;",
						"variance_amount"=>"(nf)amount variance &#8358;",
						"disbursedby"=>"disbursed by",
						"retiredby"=>"retired by",
						"status"=>"status",
						"datelogged"=>"(df)date modified"
					);

					$allow_iou_receipt_print = true;

					$format = array(
						"grid",
						"form-ctrl",
						"use-base-data",
						"allow-check-for-isprocess"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;

				?>
			</div>
		</div>
	</div>
</div>

<?php

	if((isset($_POST['ftask']) && $_POST['ftask'] == 'disburse') && isset($_POST['checkers'])) {
		
		$rows = $_POST['checkers']; $record = $rows[0];

		$get_status = idget_data($tbL153,$record,'status');
		$get_iou_no = idget_data($tbL153,$record,'iou_no');
		$get_pr_no = idget_data($tbL153,$record,'pr_no');

		if(!empty($get_pr_no) && $get_pr_no != '') {
			$sum = "SUM(order_net_amount)";
			$queryset = "order_number='{$get_pr_no}' AND pr_status='IOU Approved'";
			$pr_amount = mysqli_arithmetic_data($tbL121,$sum,$queryset);
		} else {
			$get_pr_no = idget_data($tbL153,$record,'iou_no');
			$pr_amount = idget_data($tbL153,$record,'amount');
		}

		if($get_status == 'Disbursed') {
			?>
				<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox" class="fx-width-35 pads30 dark-black-theme white-font xsml-rounded-button alignlt">
						<form action="" method="post" autocomplete="off">
							<div class="pads10 alignlt">
								<h3 class="xlarge nobold">Selected IOU already disbursed. Use retire-fund button instead if not done</h3>
							</div>
							<div class="top-pull-15 motion alignct">
								<a href="javascript://" class="blue-font default-text-font-bold" title="Close" onclick="cancelPrSign()">GOT IT</a>
							</div>
						</form>
					</div>
				</div>
			<?php
		} else {
			?>
				<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox" class="fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt">
						<form action="" method="post" autocomplete="off">
							<div class="pads10 alignlt">
								<h3 class="large nobold">Indicate amount or click apply if no change in amount for iou number <u><?php echo $get_iou_no; ?></u></h3><h3 class="large nobold default-text-font-bold"><?php echo $get_pr_no; ?> (&#8358;<?php echo number_format($pr_amount); ?>)</h3><br>
								<span class="float-left nc-width-10">NGN</span>
								<span class="float-left nc-width-90 left-pull-10"><input type="text" name="wgtamount2disburse" id="wgtamount2disburse" placeholder="Enter here" onkeyup="numberinputFormat(this.value,this.id,'amount2disburse')" class="nopads no-back-black default-text-font-bold" value="<?php echo number_format($pr_amount); ?>" required></span><span class="block-element new-line-space"></span>
								<input type="hidden" name="amount2disburse" id="amount2disburse" value="<?php echo $pr_amount; ?>" required>
								<input type="hidden" name="wgid" id="wgid" value="<?php echo $record; ?>">
							</div>
							<div class="top-pull-15 motion">
								<?php
									if(isset($pr_amount) && $pr_amount > 0) {
										?>
											<input type="submit" name="disbursebutton" value="Apply" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">
										<?php
									} else {
										?>
											<h3 class="large nobold light-red-font">Error collecting iou status. check approval</h3>
										<?php
									}
								?>
								<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>
							</div>
						</form>
					</div>
				</div>
			<?php
		}
	}

?>


<script>

	function jsReceipt(key) {
		popmodalframe('accounting','iou_payment_receipt',key,0,900,800);
	}

	function jsxView(key) {
		if(key.indexOf('PR') > -1) { popmodalframe('accounting','preview_pr',key,0,1200,800); }
		else if(key.indexOf('IOU') > -1) { popmodalframe('accounting','preview_pr_iou',key,0,900,800); }
	}

	function jsForm(fr,task) {
		if(fr == 'reportform') {
			var param = {
				"iou" : document.getElementById('iou').value,
				"status" : document.getElementById('status').value,
				"startdate" : document.getElementById('startdate').value,
				"endate" : document.getElementById('enddate').value
			};

			sessionStorage.setItem('acctgparams',JSON.stringify(param));

			setTimeout(() => {
				if(sessionStorage.getItem('acctgparams') !== null && sessionStorage.getItem('acctgparams') != 'undefined') {
					var uri,params,wp;
					uri = sessionStorage.getItem('iouri'); params = sessionStorage.getItem('acctgparams'); wp = JSON.parse(params);
					window.location.href = uri+'&iou='+wp.iou+'&status='+wp.status+'&startdate='+wp.startdate+'&endate='+wp.endate;
				}
			},1000);

		} else if(fr == 'datasheet') {
			document.getElementById('ftask').value = task;
			setTimeout(() => { document.getElementById(fr).submit(); },500);
		}
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	function doretire(e) {
		e.preventDefault();
		var conf = confirm('Notification\nAre you sure you want to retire this fund?');
		if(conf == true) {
			//document.getElementById('retirebutton').setAttribute('type','button');
			document.getElementById('retirebutton').value='Sending..';
			document.getElementById('retireform').setAttribute('onsubmit','');
			setTimeout(() => { document.getElementById('retireform').submit(); },500);
		}
	}

	window.onload = () => {
		if(sessionStorage.getItem('iouri') == null || sessionStorage.getItem('iouri') == 'undefined') {
			sessionStorage.setItem('iouri',window.location.href);
		}
	}

</script>