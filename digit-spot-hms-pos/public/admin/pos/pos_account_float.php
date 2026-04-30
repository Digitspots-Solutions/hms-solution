<?php

$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

$update_result = '';
$post_result = '';
$htmlresult = '';

#get user counter session id
$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;
$search_date = isset($_GET['isdate']) ? $_GET['isdate'] : "";

$counter_select = idget_data($tbL19,$counter_sesid,'countername');

if(!empty($search_date)) {
	$user_counter_query = array("deletedata"=>0,"counterid"=>$counter_sesid,"userid"=>$userSignedIn,"datelogged"=>$search_date);
	$queryset = "deletedata=0 AND counterid={$counter_sesid} AND userid={$userSignedIn} AND datelogged='{$search_date}'";
	$pst_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"datelogged"=>$search_date);
} else {
	$user_counter_query = array("deletedata"=>0,"counterid"=>$counter_sesid,"userid"=>$userSignedIn,"ispast"=>0);
	$queryset = "deletedata=0 AND counterid={$counter_sesid} AND userid={$userSignedIn} AND ispast=0";
	$pst_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn);
}

#-------------------------------------------------------------------------------------------------------------------

if(isset($_GET['wob']) && $_GET['wob'] == 'y') {
	$get_dataf = mysqli_data_fetch($tbL25,'id,openingbalance,withdrawal',$user_counter_query,'array');
	if(is_array($get_dataf)) {
		$iswdr = 0;
		foreach($get_dataf as $key => $val) {
			if($val['openingbalance'] > 0) {
				$new_withdraws = $val['openingbalance'] + $val['withdrawal'];
				
				$qy = array("id"=>$val['id']);
				$sy = array("openingbalance"=>0,"withdrawal"=>$new_withdraws);
				mysqli_data_update($tbL25,$sy,$qy);

				$new_withdraws = 0;
				$iswdr += 1;
			}
		}

		if(isset($iswdr) && $iswdr > 0) {
			
			$saynotify = 1;
			$notifytype = 2;
			
			$post_header = "Notification";
			$post_message = "Withdraw was successfully";
			
			$islogfile = 1;
			$logfile_msg = "Opening balance was withdrawn by this user";
		}
	}
}

#-------------------------------------------------------------------------------------------------------------------

#total opening balance
$obl_sql = "SUM(openingbalance)";
$wgt_obl = mysqli_arithmetic_data($tbL25,$obl_sql,$queryset);

#total credit
$crd_sql = "SUM(collection)";
$wgt_crd = mysqli_arithmetic_data($tbL25,$crd_sql,$queryset);

#total debit
$rf_sql = "SUM(refunds)";
$wgt_rf = mysqli_arithmetic_data($tbL25,$rf_sql,$queryset);

//$wgt_net = $wgt_crd - ($wgt_obl + $wgt_rf);
$wgt_net = $wgt_crd - $wgt_rf;
$wgt_gc = ($wgt_crd + $wgt_obl) - $wgt_rf;

#count no of active funds to enable transfer to gc
$af_sql = "COUNT(ispast)";
$wgt_af = mysqli_arithmetic_data($tbL25,$af_sql,$queryset);

#get shift details
$additionalQuery = " ORDER BY id DESC LIMIT 1";
$pst_field = "shiftid,resumptiontime,closetime,datelogged";
$get_data = mysqli_data_fetch($tbL23,$pst_field,$pst_query,'noarray');
$shift_name = idget_data($tbL20,$get_data[0],'shiftname');

#-------------------------------------------------------------------------------------------------------------------

if(isset($_GET['cc']) && $_GET['cc'] == 'y') {

	//close counter log
	$update_counter_datasets = array("status"=>"Closed");
	$counter_qcheck = array("counterid"=>$counter_sesid);
	mysqli_data_update($tbL21,$update_counter_datasets,$counter_qcheck);

	//close user counter log
	$update_user_counter_datasets = array("logstatus"=>"Closed");
	$counter_user_qcheck = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"logstatus"=>"Open");
	mysqli_data_update($tbL22,$update_user_counter_datasets,$counter_user_qcheck);

	//close counter shift
	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$shift_qcheck = array("userid"=>$userSignedIn);
	
	$update_counter_shift_datasets = array("closetime"=>$server_get_time);
	mysqli_data_update($tbL23,$update_counter_shift_datasets,$shift_qcheck);

	?>
		<script>
			window.addEventListener('load', () => { window.parent.location = "<?php echo DOMAIN_URL; ?>login/"; }, false);
		</script>
	<?php
}

#-------------------------------------------------------------------------------------------------------------------

createDatabasetable($var_tbl_305); //for account receivable table

if(isset($_POST['uri']) && $_POST['uri'] == 'apply-transfer-to-gc') {

	$acct_src = escape_data($_POST['account2gc']); $cashier = escape_data($_POST['user2gc']);
	$counter = escape_data($_POST['counter2gc']); $shift = escape_data($_POST['shift2gc']);
	$start_end_date = escape_data($_POST['date2gc']); $start_time = escape_data($_POST['startime2gc']);
	$end_time = escape_data($_POST['endtime2gc']); $amount = escape_data($_POST['amt2gc']);

	$pst_query = "";
	$pst_field = array("account"=>$acct_src,"shift_start_date"=>$start_end_date,"shift_end_date"=>$start_end_date,"shift_start_time"=>$start_time,"shift_end_time"=>$server_get_time,"shift"=>$shift,"counterid"=>$counter,"userid"=>$cashier,"paid_amount"=>$amount,"account_src"=>"pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	$result = mysqli_data_insert($tbL158,$pst_field,$pst_query);

	if(isset($result) && $result == 2) {
		
		$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"ispast"=>0);
		$sales_counter_sql = array("ispast"=>1);
		$update_rs = mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

		//close counter log
		$update_counter_datasets = array("status"=>"Closed");
		$counter_qcheck = array("counterid"=>$counter_sesid);
		mysqli_data_update($tbL21,$update_counter_datasets,$counter_qcheck);

		//close user counter log
		$update_user_counter_datasets = array("logstatus"=>"Closed");
		$counter_user_qcheck = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"logstatus"=>"Open");
		mysqli_data_update($tbL22,$update_user_counter_datasets,$counter_user_qcheck);

		//close counter shift
		$update_counter_shift_datasets = array("closetime"=>$server_get_time);
		$shift_qcheck = array("userid"=>$userSignedIn);
		$additionalQuery = " ORDER BY id DESC LIMIT 1";
		mysqli_data_update($tbL23,$update_counter_shift_datasets,$shift_qcheck);

		//$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($update_rs) && $update_rs > 0) {
			
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently retired counter net sales of {$amount} to general cashier","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');


			?>
				<script>
					window.addEventListener('load', () => { window.parent.location = "<?php echo DOMAIN_URL; ?>login/"; }, false);
				</script>
			<?php

			//$post_result .= '<span class="light-red-font ft-sml-size">Amount was retired successfully</span>';
		}

		//$post_result .= '</div>';

		//echo $post_result;
	}
}

?>

<div class="noshow">
	<form id="for-tr-2gc" action="" method="post">
		<input type="hidden" name="uri" value="apply-transfer-to-gc">
		<input type="hidden" name="account2gc" id="account2gc" value="<?php echo $counter_select; ?>">
		<input type="hidden" name="user2gc" id="user2gc" value="<?php echo $userSignedIn; ?>">
		<input type="hidden" name="counter2gc" id="counter2gc" value="<?php echo $counter_sesid; ?>">
		<input type="hidden" name="shift2gc" id="shift2gc" value="<?php echo $shift_name; ?>">
		<input type="hidden" name="date2gc" id="date2gc" value="<?php echo $get_data[3]; ?>">
		<input type="hidden" name="startime2gc" id="startime2gc" value="<?php echo $get_data[1]; ?>">
		<input type="hidden" name="endtime2gc" id="endtime2gc" value="<?php echo $get_data[2]; ?>">
		<input type="hidden" name="amt2gc" id="amt2gc" value="<?php echo $wgt_gc; ?>">
		<input type="submit" name="submitbutton">
	</form>
</div>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; User Account Float
 	</span>
 	<span class="ln-display-box float-right">
		<input type="button" value="Print" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 blue-white-state rounded-button anchor" onclick="window.print()"> <?php if((!empty($wgt_af) && $wgt_af > 0) && ((!empty($wgt_obl) && $wgt_obl > 0) || (!empty($wgt_crd) && $wgt_crd > 0))) { ?><input type="button" value="Transfer to GC" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 dark-black-white-state rounded-button anchor left-push-10" onclick="ctrf()"><?php } else { ?><input type="button" value="Close Counter" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 dark-black-white-state rounded-button anchor left-push-10" onclick="cct()"><?php } ?>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div id="section-to-print" class="block-element">
	
	<span class="float-right"><input type="text" name="searchbydate" id="searchbydate" placeholder="Search by date?" onfocus="textodate(this.id)" oninput="jsd(this.value)"></span>
	<h2 class="large nobold default-text-font-bold">Today's Shift - <?php echo $counter_select; ?></h2><br>

	<ul class="nolist">
		<li class="ln-display-box float-left default-text-font-bold right-pull-30 left-pull-30">Opening Balance<h2 class="large nobold top-pull-5"><?php echo number_format($wgt_obl,2); ?><span class="float-right top-pull-3 left-pull-10"><a class="top-pull-5 right-pull-10 bottom-pull-5 left-pull-10 orange-white-state rounded-button ft-xxsml-size default-text-font-bold anchor" onclick="wdr()">Withdraw<b class="fa-arrow-right left-push-5"></b></a></span></h2></li>
		<li class="ln-display-box float-left default-text-font-bold right-pull-30 left-pull-30">Total Credits<h2 class="large nobold top-pull-5"><?php echo number_format($wgt_crd,2); ?></h2></li>
		<li class="ln-display-box float-left default-text-font-bold right-pull-30 left-pull-30">Total Debits<h2 class="large nobold top-pull-5"><?php echo number_format($wgt_rf,2); ?></h2></li>
		<li class="block-element new-line-space"></li>
		<li class="ln-display-box float-left top-pull-20 default-text-font-bold right-pull-30 left-pull-30 bottom-pull-5 box-3border-thick-bottom">Total Net Sales<h2 class="large nobold top-pull-5"><?php echo number_format($wgt_net,2); ?></h2></li>
		<li class="ln-display-box float-right top-pull-20 default-text-font-bold right-pull-30 left-pull-30 bottom-pull-5 box-3border-thick-bottom">Amount (to GC)<h2 class="large nobold top-pull-5 light-red-font"><?php echo number_format($wgt_gc,2); ?></h2></li><li class="block-element new-line-space"></li>
	</ul>

	<?php

	$additionalQuery = "";

	$user_counter_dataproperty = "id,fundid,openingbalance,fundadded,withdrawal,collection,refunds,err";
	$user_counter_data = mysqli_data_fetch($tbL25,$user_counter_dataproperty,$user_counter_query,'array');

	if(is_array($user_counter_data)) {

		$counter_avail = 1; $paytype_query = ""; $moneyathand = "";
		$total_openingbal=0; $total_fundadded=0; $total_withdrawal=0; $total_collection=0; $total_refunds=0; $total_moneyathand=0;
		$total_err=0;

		foreach ($user_counter_data as $usd_key => $usd_value) {
			
			if($usd_value["fundid"] >= 1)
			{
				$paytype_query = array("id"=>$usd_value["fundid"]);
				$paytype_data = mysqli_data_fetch($tbL24,'name',$paytype_query,'noarray');

				//$moneyathand = ($usd_value["openingbalance"] + $usd_value["fundadded"] + $usd_value["collection"]) - ($usd_value["withdrawal"] + $usd_value["refunds"]);

				$moneyathand = ($usd_value["openingbalance"] + $usd_value["fundadded"] + $usd_value["collection"]) - $usd_value["refunds"];

				$total_openingbal = $total_openingbal + $usd_value["openingbalance"];
				$total_fundadded = $total_fundadded + $usd_value["fundadded"];
				$total_withdrawal = $total_withdrawal + $usd_value["withdrawal"];
				$total_collection = $total_collection + $usd_value["collection"];
				$total_refunds = $total_refunds + $usd_value["refunds"];
				$total_moneyathand = $total_moneyathand + $moneyathand;
				$total_err = $total_err + $usd_value["err"];

				$htmlresult .= '<tr>';
				$htmlresult .= '<td width="200px" align="center" class="box-border-thick-left box-border-thick-right">';
				$htmlresult .= $paytype_data[0];
				$htmlresult .= '<input type="hidden" name="name_fieldset[]" value="'.$usd_value["fundid"].'">';
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["openingbalance"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["withdrawal"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["collection"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["refunds"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($moneyathand);
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
		}

		$htmlresult .= '<tr>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&nbsp;</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_openingbal).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_withdrawal).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_collection).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_refunds).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_moneyathand).'</td>';
		$htmlresult .= '</tr>';

	} else {
		$counter_avail = 0;
	}


	?>

	<div class="block-element sml-rounded-button top-push-30 noscroll">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="250px" class="box-border-thick-right" align="center">Funds Type</th>
				<th width="100px" class="box-border-thick-right" align="center">Opening Bal.</th>
				<th width="150px" class="box-border-thick-right" align="center">Withdrawals</th>
				<th width="150px" class="box-border-thick-right" align="center">Collections</th>
				<th width="150px" class="box-border-thick-right" align="center">Refunds</th>
				<th width="150px" class="box-border-thick-right" align="center">Current Bal.</th>
			</tr>
			<?php echo $htmlresult; ?>
			<tr>
				<td colspan="5" class="box-noborder">
					&nbsp;
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<script>
	
	function jsd(date) {
		var ds;
		if(sessionStorage.getItem('jbd') !== null && sessionStorage.getItem('jbd') != 'undefined') {
			ds = sessionStorage.getItem('jbd');
		} else {
			sessionStorage.setItem('jbd',window.location.href);
			ds = window.location.href;
		}

		setTimeout(() => { window.location.href = ds+'&isdate='+date; },500);
	}


	function wdr() {
		var ds;
		if(sessionStorage.getItem('jbd') !== null && sessionStorage.getItem('jbd') != 'undefined') {
			ds = sessionStorage.getItem('jbd');
		} else {
			sessionStorage.setItem('jbd',window.location.href);
			ds = window.location.href;
		}

		setTimeout(() => { window.location.href = ds+'&wob=y'; },500);
	}
	

	function ctrf() {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-150 noscroll');

		var amt2gc = document.getElementById('amt2gc');
		var vhtml;

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<h3 class="large nobold">Note: We recommend that you do this when you are about closing your business day</h3><br><h3 class="large nobold default-text-font-bold">Confirm transfer of &#8358; '+numberFormat(amt2gc.value)+' to general cashier</h3>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-10 motion">';
		vhtml += '<input type="submit" name="applybutton" value="Apply Transfer" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}


	function jbtrigger(e) {
		e.preventDefault();
		setTimeout(() => { document.getElementById('for-tr-2gc').submit(); },500);
	}


	function cct() {
		window.location.href = window.location.href+'&cc=y';
	}

</script>
