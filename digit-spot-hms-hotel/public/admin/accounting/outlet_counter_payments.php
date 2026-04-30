<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

createDatabasetable($var_tbl_307); //for city ledger

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('status','Active',$tbL20,'shiftname','shiftname');

$extable = $tbL7; $extcols = "staffname"; $extkey = "id";
$get_users = select_dt_fetch('account_src','pos',$tbL158,'','userid');

#-----------------------------------------------------------------------------------------------------

if(isset($_GET['counter']) && $_GET['counter'] > 0) {
	$record = escape_data($_GET['counter']);
	$_SESSION['counter'] = idget_data($tbL158,$record,'counterid');
	$_SESSION['user'] = idget_data($tbL158,$record,'userid');
	$_SESSION['shift'] = idget_data($tbL158,$record,'shiftid');
	$_SESSION['from'] = idget_data($tbL158,$record,'shift_start_date');
	$_SESSION['to'] = idget_data($tbL158,$record,'shift_end_date');
}

if(isset($_GET['counter'])) { unset($_GET['counter']); }

#-----------------------------------------------------------------------------------------------------

if(isset($_POST['applybutton']) && isset($_POST['wgid'])) {
		
	$record = escape_data($_POST['wgid']);
	$paid_amount = idget_data($tbL158,$record,'paid_amount');
	
	if(!isset($_POST['amountreceived']) || empty($_POST['amountreceived'])) { $pst_amount = $paid_amount; }
	else { $pst_amount = $_POST['amountreceived']; }

	if(isset($_POST['cashremark']) && !empty($_POST['cashremark'])) { $remark = escape_data($_POST['cashremark']); }
	else { $remark = 'N/A'; }

	$diff_amount = $paid_amount - $pst_amount;

	$payment_sql = array("remark"=>$remark,"actual_amount"=>$pst_amount,"diff_amount"=>$diff_amount,"receivedby"=>$userSignedIn,"status"=>"Received","isprocess"=>1); $payment_query = array("id"=>$record);
	mysqli_data_update($tbL158,$payment_sql,$payment_query);

	#for user city ledger
	if((isset($_POST['charge4diff']) && !empty($_POST['charge4diff'])) && ($diff_amount > 0)) {
		$chargeto = idget_data($tbL158,$record,'userid');
		$message = "System auto-charge for difference in payment tendered to general cashier";

		$cityledger_sql = array("forcounter"=>"outlet","amount"=>$diff_amount,"chargeto"=>$chargeto,"detail"=>$message,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL160,$cityledger_sql,'');
	}

	$saynotify = 1;
	$notifytype = 2;
	
	$post_header = "Notification";
	$post_message = "payment is confirmed and received successfully";
	
	$islogfile = 1;
	$logfile_msg = "counter payment received by this user";
}

#-----------------------------------------------------------------------------------------------------

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can accept funds from various outlets/counters. After the search, select the record and click <u>accept fund</u> button
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
				<h3 class="large nobold default-text-font-bold">By Shift</h3>
				<select name="shift" id="shift" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_shifts; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By User</h3>
				<select name="user" id="user" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_users; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_GET['startdate'])) { echo $_GET['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" value="<?php if(isset($_GET['enddate'])) { echo $_GET['enddate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
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
		<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('datasheet')">Accept Fund</a>
	</p>
	<div class="x-scroll">
		<div class="cs-width-1200">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL158;
					
					$startnumbr = 0;
					$keywords = "";

					if(isset($_GET['shift']) && !empty($_GET['shift'])) {
						$keywords .= " AND shift='{$_GET['shift']}'";
					}

					if(isset($_GET['user']) && !empty($_GET['user'])) {
						$keywords .= " AND userid={$_GET['user']}";
					}

					if((isset($_GET['startdate']) && !empty($_GET['startdate'])) && (isset($_GET['endate']) && !empty($_GET['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_GET['startdate']}' AND '{$_GET['endate']}'";
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					}

					$queryset = "deletedata=0 AND account_src IN('pos','recreation')".$keywords;

					$keys = array(
						"account"=>"outlet",
						"shift_start_date"=>"open date",
						"shift_end_date"=>"end date",
						"shift_start_time"=>"open time",
						"shift_end_time"=>"end time",
						"shift"=>"shift",
						"userid"=>"cashier",
						"paid_amount"=>"(nf)amt. receivable",
						"actual_amount"=>"(nf)amt. received",
						"diff_amount"=>"(nf)amt. diff.",
						"remark"=>"remark",
						"status"=>"status",
						"receivedby"=>"receiver"
					);

					$format = array(
						"grid",
						"form-ctrl",
						"allow-view",
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

<div class="noshow">
	<a id="popwin" href="" target="_blank">forceopenwindow</a>
</div>

<?php

	if(isset($_POST['checkers'])) {
		
		$rows = $_POST['checkers']; $record = $rows[0];
		$amount = idget_data($tbL158,$record,'paid_amount');
		
		?>
			<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
				<div class="cs-height-150"></div>
				<div id="rBox" class="fx-width-40 pads30 white-theme obj-shadow xsml-rounded-button alignlt">
					<form action="" method="post" autocomplete="off">
						<div class="pads10 alignlt">
							<h3 class="large nobold">If amount received is not the same, enter received amount otherwise click apply?</h3><h2 class="xlarge nobold default-text-font-bold">Payable: &#8358;<?php echo number_format($amount); ?></h2><br>
							<input type="text" name="wgtamountreceived" id="wgtamountreceived" placeholder="Enter here" onkeyup="numberinputFormat(this.value,this.id,'amountreceived')" class="no-back-black default-text-font-bold">
							<input type="hidden" name="amountreceived" id="amountreceived">
							<input type="hidden" name="wgid" id="wgid" value="<?php echo $record; ?>">
							<div class="xform top-push-7 bottom-push-7">
								<span class="block-element pads7">
									<h3 class="large ft-tahoma">(Optional) Give description about the payment/cash receivable</h3>
									<p>&nbsp;</p>
									<textarea name="cashremark" id="cashremark" placeholder="Write remark here.." class="nopads no-back-black notextborder"></textarea>
								</span>
							</div>
							<p class="top-pull-3 ft-xsml-size"><input type="checkbox" name="charge4diff" value="Yes"> Charge for difference</p>
						</div>
						<div class="top-pull-15 motion">
							<input type="submit" name="applybutton" value="Apply" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">
							<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>
						</div>
					</form>
				</div>
			</div>
		<?php
	}
?>


<script>

	function jsForm(fr) {
		if(fr == 'reportform') {
			var param = {
				"shift" : document.getElementById('shift').value,
				"user" : document.getElementById('user').value,
				"startdate" : document.getElementById('startdate').value,
				"endate" : document.getElementById('enddate').value
			};

			sessionStorage.setItem('xacctgparams',JSON.stringify(param));

			setTimeout(() => {
				if(sessionStorage.getItem('xacctgparams') !== null && sessionStorage.getItem('xacctgparams') != 'undefined') {
					var uri,params,wp;
					uri = sessionStorage.getItem('xaccturi'); params = sessionStorage.getItem('xacctgparams'); wp = JSON.parse(params);
					window.location.href = uri+'&shift='+wp.shift+'&user='+wp.user+'&startdate='+wp.startdate+'&endate='+wp.endate;
				}
			},1000);

		} else if(fr == 'datasheet') {
			document.getElementById(fr).submit();
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

	function jsView(data) {
		sessionStorage.setItem('counterview',data);
		setTimeout(() => {
			//window.location.reload(true);
			window.location.href = window.location.href+'&counter='+data;
		},1000);
	}

	window.onload = () => {
		if(sessionStorage.getItem('xaccturi') == null || sessionStorage.getItem('xaccturi') == 'undefined') {
			sessionStorage.setItem('xaccturi',window.location.href);
		}

		if(sessionStorage.getItem('counterview') !== null && sessionStorage.getItem('counterview') != undefined) {
			
			const domain = "<?php echo DOMAIN_URL.PUB_FLD; ?>";
			const counter = "<?php if(isset($_SESSION['counter'])) { echo $_SESSION['counter']; } ?>";
			const shift = "<?php if(isset($_SESSION['shift'])) { echo $_SESSION['shift']; } ?>";
			const user = "<?php if(isset($_SESSION['user'])) { echo $_SESSION['user']; } ?>";
			const from = "<?php if(isset($_SESSION['from'])) { echo $_SESSION['from']; } ?>";
			const to = "<?php if(isset($_SESSION['to'])) { echo $_SESSION['to']; } ?>";

			sessionStorage.removeItem('counterview');
			document.getElementById('popwin').setAttribute('href',domain+'admin/pos/print_pos_shiftwise_details?ses=pos&store='+counter+'&shift='+shift+'&cashier='+user+'&trd1='+from+'&trd2='+to);
			setTimeout(() => { document.getElementById('popwin').click(); },1000);
		}
	}

</script>