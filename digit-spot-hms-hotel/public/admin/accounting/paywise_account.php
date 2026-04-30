<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

createDatabasetable($var_tbl_306);
createDatabasetable($var_tbl_314);

if(isset($_POST['submitbutton']) && (isset($_POST['amount']) && $_POST['amount'] > 0)) {
		
	$record = escape_data($_POST['account']);
	$pst_amount = escape_data($_POST['amount']);

	$get_account_amt = idget_fdata($tbL159,'account_type',$record,'amount');
	$get_account_bal = idget_fdata($tbL159,'account_type',$record,'balance');
	
	if($get_account_bal > 0) {
		$new_amount = $get_account_amt + $pst_amount;
		$new_balance = $get_account_bal + $pst_amount;
		$isacct = 1;
	} else {
		$new_amount = $pst_amount;
		$new_balance = $pst_amount;
		$isacct = 0;
	}

	$payment_sql = array("account_type"=>$record,"amount"=>$new_amount,"balance"=>$new_balance);
	$payment_query = array("account_type"=>$record);

	if(isset($isacct) && $isacct == 1) {
		mysqli_data_update($tbL159,$payment_sql,$payment_query);
	} else {
		mysqli_data_insert($tbL159,$payment_sql,$payment_query);
	}

	$fund_query = "";
	$fund_sql = array("fund_type"=>$record,"amount"=>$pst_amount,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	mysqli_data_insert($tbL168,$fund_sql,$fund_query);

	$saynotify = 1;
	$notifytype = 2;
	
	$post_header = "Notification";
	$post_message = "Account is funded successfully";
	
	$islogfile = 1;
	$logfile_msg = $record." account is funded with (".$pst_amount.") NGN by this user";
}

#----------------------------------------------------------------------------------------------------------

$datasets = "amount,withdraws,balance";

$iou_queryset = array("account_type"=>"iou-fund");
$iou = mysqli_data_fetch($tbL159,$datasets,$iou_queryset,'noarray');

if(is_array($iou) && count($iou) > 0) { $iou_fx_added = $iou[0]; $iou_fx_withdraws = $iou[1]; $iou_fx_bal = $iou[2]; }
else { $iou_fx_added = 0; $iou_fx_withdraws = 0; $iou_fx_bal = 0; }

$p2p_queryset = array("account_type"=>"p2p-fund");
$p2p = mysqli_data_fetch($tbL159,$datasets,$p2p_queryset,'noarray');

if(is_array($p2p) && count($p2p) > 0) { $p2p_fx_added = $p2p[0]; $p2p_fx_withdraws = $p2p[1]; $p2p_fx_bal = $p2p[2]; }
else { $p2p_fx_added = 0; $p2p_fx_withdraws = 0; $p2p_fx_bal = 0; }

?>

<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can deposit into your treasury funds account. Click <u>add fund</u> button
	</span>
	<span class="ln-display-box float-right top-pull-5">
		<input type="button" value="Print" onclick="window.print()">
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div id="section-to-print" class="pads30" align="left">
	<ul class="nolist">
		<li class="ln-display-box float-left nc-width-50 right-pull-20">
			<fieldset>
				<legend class="default-text-font-bold ft-sml-size">IOU Fund</legend>
				<div class="pads10">
					<table cellpadding="2" cellspacing="5">
						<tr>
							<th>Deposit</th>
							<th>Withdraws</th>
							<th>Balance</th>
						</tr>
						<tr>
							<td class="alignct">&#8358; <?php echo number_format($iou_fx_added,2); ?></td>
							<td class="alignct">&#8358; <?php echo number_format($iou_fx_withdraws,2); ?></td>
							<td class="alignct">&#8358; <?php echo number_format($iou_fx_bal,2); ?></td>
						</tr>
					</table>
					<?php
						if(isset($allowAddTreasuryFund) && $allowAddTreasuryFund == 200) {
							?>
								<p class="top-pull-20 left-pull-10">
									<a href="javascript:void(0)" class="blue-font ft-sml-size" onclick="xjsForm('iou-fund')">Add Fund +</a>
								</p>
							<?php
						}
					?>
				</div>
			</fieldset>
		</li>
		<li class="ln-display-box float-left nc-width-50 left-pull-20">
			<fieldset>
				<legend class="default-text-font-bold ft-sml-size">Purchase-to-Pay Fund</legend>
				<div class="pads10">
					<table cellpadding="2" cellspacing="5">
						<tr>
							<th>Deposit</th>
							<th>Withdraws</th>
							<th>Balance</th>
						</tr>
						<tr>
							<td class="alignct">&#8358; <?php echo number_format($p2p_fx_added,2); ?></td>
							<td class="alignct">&#8358; <?php echo number_format($p2p_fx_withdraws,2); ?></td>
							<td class="alignct">&#8358; <?php echo number_format($p2p_fx_bal,2); ?></td>
						</tr>
					</table>
					<?php
						if(isset($allowAddTreasuryFund) && $allowAddTreasuryFund == 200) {
							?>
								<p class="top-pull-20 left-pull-10">
									<a href="javascript:void(0)" class="blue-font ft-sml-size" onclick="xjsForm('p2p-fund')">Add Fund +</a>
								</p>
							<?php
						}
					?>
				</div>
			</fieldset>
		</li>
		<li class="block-element new-line-space">
		</li>
	</ul>


	<?php
		
		$query_type = "deletedata=0";
		$chkF = mysqli_data_exist($tbL168,$query_type);
		$totalcount = $chkF['dbrows'];

	?>

	<div class="cs-height-50">
	</div>

	<h3 class="large nobold default-text-font-bold">Fund Transaction Sheet</h3><br>
	<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 x-scroll">
		<div class="nc-width-100">
			<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
				<span class="ln-display-box float-left cs-width-150 top-pull-10 right-push-20">
					<h4 class="large nobold"><?php echo $totalcount; ?> Record(s)</h4>
				</span>
				<span class="ln-display-box float-left cs-width-120 top-pull-10 right-push-20">
					<input type="text" name="startdate" id="startdate" placeholder="From Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
				</span>
				<span class="ln-display-box float-left cs-width-120 top-pull-10 right-push-50">
					<input type="text" name="enddate" id="enddate" placeholder="To Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
				</span>
				<span class="ln-display-box float-right top-pull-15">
					<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform',0)" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</div>
	</div>
	<div class="top-pull-30" align="left">
		<?php
					
			if((isset($_GET['startdate']) && !empty($_GET['startdate'])) && (isset($_GET['endate']) && !empty($_GET['endate']))) {
				$keywords .= " AND datelogged BETWEEN '{$_GET['startdate']}' AND '{$_GET['endate']}' ORDER BY id ASC";
			} else {
				$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}' ORDER BY id DESC";
			}

			$queryset = "deletedata=0".$keywords;

			$keys = array(
				"fund_type"=>"(uc)fund",
				"amount"=>"(nf)amount deposited &#8358;",
				"userid"=>"deposited by",
				"datelogged"=>"(df)date",
				"timelogged"=>"time"
			);

			$format = array(
				"grid",
				"use-base-data"
			);

			$datasheet = data_row_dpl($tbL168,$queryset,$keys,$format,$startnumbr,$extdata);
			echo $datasheet;

		?>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<script>

	function xjsForm(acct) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion nc-height-100 y-scroll');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		var vhtml,typeofAcct;
		var amt = "'amount'";

		if(acct == 'iou-fund') { typeofAcct = 'IOU'; }
		else if(acct == 'p2p-fund') { typeofAcct = 'P2P'; }

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label class="block-element bottom-push-7">Please enter the amount you want to fund into this account ('+typeofAcct+')</label>';
		vhtml += '<input type="hidden" name="account" id="account" value="'+acct+'">';
		vhtml += '<input type="text" name="wgtamount" id="wgtamount" placeholder="Enter here?" class="no-back-black default-text-font-bold" onkeyup="numberinputFormat(this.value,this.id,'+amt+')">';
		vhtml += '<input type="hidden" name="amount" id="amount">';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="submitbutton" name="submitbutton" value="Apply Fund" class="nc-width-100 dark-black-white-state top-pull-10 bottom-pull-10 rounded-button ft-mini-size anchor letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font default-text-font-bold" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';

		writeObjheader('rBox',vhtml);

	}

	function jsForm(fr,task) {
		if(fr == 'reportform') {
			var param = {
				"startdate" : document.getElementById('startdate').value,
				"endate" : document.getElementById('enddate').value
			};

			sessionStorage.setItem('trfunds',JSON.stringify(param));

			setTimeout(() => {
				if(sessionStorage.getItem('trfunds') !== null && sessionStorage.getItem('trfunds') != 'undefined') {
					var uri,params,wp;
					uri = sessionStorage.getItem('truri'); params = sessionStorage.getItem('trfunds'); wp = JSON.parse(params);
					window.location.href = uri+'&startdate='+wp.startdate+'&endate='+wp.endate;
				}
			},1000);

		} else if(fr == 'datasheet') {
			document.getElementById('ftask').value = task;
			setTimeout(() => { document.getElementById(fr).submit(); },500);
		}
	}

	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	window.onload = () => {
		if(sessionStorage.getItem('truri') == null || sessionStorage.getItem('truri') == 'undefined') {
			sessionStorage.setItem('truri',window.location.href);
		}
	}

</script>