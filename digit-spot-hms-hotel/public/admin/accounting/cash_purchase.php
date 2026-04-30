<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_pays = datalist_fetch('deletedata',0,$tbL154,'pay_no','pay_no');

$get_account_wdr = idget_fdata($tbL159,'account_type','p2p-fund','withdraws');
$get_account_bal = idget_fdata($tbL159,'account_type','p2p-fund','balance');

#-----------------------------------------------------------------------------------------------------

if(isset($_POST['disbursebutton']) && isset($_POST['wgid'])) {
		
	$record = escape_data($_POST['wgid']);
	$get_pay_no = idget_data($tbL154,$record,'pay_no');
	$get_pr_no = idget_data($tbL154,$record,'pr_no');
	$pst_amount = $_POST['amount2disburse'];

	if($pst_amount > $get_account_bal) {

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Insufficient fund to complete transaction";

	} else {
		
		$new_wdr = $get_account_wdr + $pst_amount;
		$new_balance = $get_account_bal - $pst_amount;

		$f_p2p_query = array("account_type"=>"p2p-fund");
		$f_p2p_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
		mysqli_data_update($tbL159,$f_p2p_sql,$f_p2p_query);
		
		$payment_query = array("id"=>$record);
		$payment_sql = array("amount"=>$pst_amount,"status"=>"Disbursed","disbursedby"=>$userSignedIn);
		mysqli_data_update($tbL154,$payment_sql,$payment_query);

		$pr_query = array("order_number"=>$get_pr_no);
		$pr_sql = array("pr_status"=>"Payment Approved");
		mysqli_data_update($tbL121,$pr_sql,$pr_query);

		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "Amount is disbursed successfully";
		
		$islogfile = 1;
		$logfile_msg = "amount (".$pst_amount.") disbursed for purchase-to-pay (p2p) number (".$get_pay_no.") by this user";
	}
}

#-----------------------------------------------------------------------------------------------------

if((isset($_POST['ftask']) && $_POST['ftask'] == 'retire') && isset($_POST['checkers'])) {
		
	$rows = $_POST['checkers']; $isposted = 0; $get_amount = 0; $get_pr_amount = 0;

	foreach($rows as $id) {
		$record = $id;
		$get_status = idget_data($tbL154,$record,'status');
		$get_pay_no = idget_data($tbL154,$record,'pay_no');
		$get_amount = idget_data($tbL153,$record,'amount');
		$get_pr_amount = idget_data($tbL154,$record,'pr_amount');

		if(!empty($get_status) && $get_status == 'Disbursed') {

			if($get_pr_amount > $get_amount) {
				
				#reconciliation fund when there is variance
				$pst_amount = $get_pr_amount - $get_amount;

				$new_wdr = $get_account_wdr + $pst_amount;
				$new_balance = $get_account_bal - $pst_amount;

				$f_p2p_query = array("account_type"=>"p2p-fund");
				$f_p2p_sql = array("withdraws"=>$new_wdr,"balance"=>$new_balance);
				mysqli_data_update($tbL159,$f_p2p_sql,$f_p2p_query);
			}

			$rt_sql = array("retiredby"=>$userSignedIn,"isprocess"=>1);
			$rt_query = array("id"=>$record);
			mysqli_data_update($tbL154,$rt_sql,$rt_query);

			$pst_query = "";
			$pst_field = array("account"=>$get_pay_no,"counterid"=>0,"userid"=>$userSignedIn,"paid_amount"=>$get_pr_amount,"actual_amount"=>$get_pr_amount,"diff_amount"=>0,"account_src"=>"p2p","account_type"=>"Debit","status"=>"Received","receivedby"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
			mysqli_data_insert($tbL158,$pst_field,$pst_query);

			$pst_amount = 0; $new_wdr = ""; $new_balance = "";

			$isposted += 1;
		}
	}

	if(isset($isposted) && $isposted > 0) {
		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "selected p2p are retired successfully";
		
		$islogfile = 1;
		$logfile_msg = "p2p retired as expenditure by this user";
	}
}

#-----------------------------------------------------------------------------------------------------

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can disburse or retire funds from purchase-request. After the search, select the record and click either <u>disburse fund</u> or <u>retire fund</u> button
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
				<h3 class="large nobold default-text-font-bold">By P2P</h3>
				<input list="d-pay" name="payno" id="payno" placeholder="Type or select" class="nopads no-back-black">
				<datalist id="d-pay">
					<?php echo $get_pays; ?>
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
					
					$tbl = $tbL154;
					
					$startnumbr = 0;
					$keywords = "";

					if(isset($_GET['payno']) && !empty($_GET['payno'])) {
						$keywords .= " AND pay_no='{$_GET['payno']}'";
					}

					if(isset($_GET['status']) && !empty($_GET['status'])) {
						$keywords .= " AND status={$_GET['status']}";
					}

					if((isset($_GET['startdate']) && !empty($_GET['startdate'])) && (isset($_GET['endate']) && !empty($_GET['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_GET['startdate']}' AND '{$_GET['endate']}'";
					}

					$queryset = "deletedata=0".$keywords;

					$keys = array(
						"pay_no"=>"pay no.",
						"pr_no"=>"(fx)pr no.",
						"amount"=>"(nf)amount disbursed &#8358;",
						"pr_amount"=>"(nf)amount procured &#8358;",
						"variance_amount"=>"(nf)amount variance &#8358;",
						"disbursedby"=>"disbursed by",
						"retiredby"=>"retired by",
						"status"=>"status",
						"datelogged"=>"(df)date modified"
					);

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

		$get_status = idget_data($tbL154,$record,'status');
		$get_pay_no = idget_data($tbL154,$record,'pay_no');
		$get_pr_no = idget_data($tbL154,$record,'pr_no');

		$sum = "SUM(order_net_amount)";
		$queryset = "order_number='{$get_pr_no}' AND pr_status='Payment Inview'";
		$pr_amount = mysqli_arithmetic_data($tbL121,$sum,$queryset);

		if($get_status == 'Disbursed') {
			?>
				<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox" class="fx-width-35 pads30 dark-black-theme white-font xsml-rounded-button alignlt">
						<form action="" method="post" autocomplete="off">
							<div class="pads10 alignlt">
								<h3 class="xlarge nobold">Selected P2P already disbursed. Use retire-fund button instead if not done</h3>
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
					<div id="rBox" class="fx-width-35 pads30 white-theme obj-shadow xsml-rounded-button alignlt">
						<form action="" method="post" autocomplete="off">
							<div class="pads10 alignlt">
								<h3 class="large nobold">Indicate amount for p2p number <u><?php echo $get_pay_no; ?></u></h3><h3 class="large nobold default-text-font-bold"><?php echo $get_pr_no; ?> (&#8358;<?php echo number_format($pr_amount); ?>)</h3><br>
								<input type="text" name="wgtamount2disburse" id="wgtamount2disburse" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount2disburse')" class="no-back-black default-text-font-bold">
								<input type="hidden" name="amount2disburse" id="amount2disburse" required>
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
											<h3 class="large nobold light-red-font">Error collecting paymaster status. check approval</h3>
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

	function jsxView(key) {
		if(key.indexOf('PR') > -1) { popmodalframe('accounting','preview_pr',key,0,900,800); }
		else if(key.indexOf('IOU') > -1) { popmodalframe('accounting','preview_pr_iou',key,0,900,800); }
	}


	function jsForm(fr,task) {
		if(fr == 'reportform') {
			var param = {
				"payno" : document.getElementById('payno').value,
				"status" : document.getElementById('status').value,
				"startdate" : document.getElementById('startdate').value,
				"endate" : document.getElementById('enddate').value
			};

			sessionStorage.setItem('acctgparams',JSON.stringify(param));

			setTimeout(() => {
				if(sessionStorage.getItem('acctgparams') !== null && sessionStorage.getItem('acctgparams') != 'undefined') {
					var uri,params,wp;
					uri = sessionStorage.getItem('payuri'); params = sessionStorage.getItem('acctgparams'); wp = JSON.parse(params);
					window.location.href = uri+'&payno='+wp.payno+'&status='+wp.status+'&startdate='+wp.startdate+'&endate='+wp.endate;
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

	window.onload = () => {
		if(sessionStorage.getItem('payuri') == null || sessionStorage.getItem('payuri') == 'undefined') {
			sessionStorage.setItem('payuri',window.location.href);
		}
	}

</script>