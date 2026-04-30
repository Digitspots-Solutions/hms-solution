<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_; ?>

<?php
sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page');
$userSignedIn = USER_AUTHEN_SID;


$htmlresult='';
$post_htmlresult='';


if(isset($_POST['submitbutton']))
{
	$fieldset1 = $_POST['amt_fieldset']; //array data
	$fieldset2 = $_POST['camt_fieldset']; //array data
	$fieldset3 = $_POST['name_fieldset']; //array data
	$fieldset4 = $_POST['counter_id'];

	$counter_datasets = ""; $counter_data_query = ""; $isdata = 0;

	for($p=0; $p <= count($fieldset3); $p++) {
		/*$counter_datasets = array("openingbalance"=>$fieldset1[$p],"closingbalance"=>$fieldset2[$p],"ispast"=>1);
		$counter_data_query = array("userid"=>USER_AUTHEN_ID,"counterid"=>$fieldset4,"fundid"=>$fieldset3[$p],"datelogged"=>$server_get_date);*/
		
		$counter_datasets = array("ispast"=>1);
		$counter_data_query = array("userid"=>USER_AUTHEN_ID,"counterid"=>$fieldset4,"fundid"=>$fieldset3[$p]);
		mysqli_data_update($tbL25,$counter_datasets,$counter_data_query);
		
		$isdata += 1;
	}

	if(isset($isdata) && $isdata >= 1) {
		
		//close counter log
		$update_counter_datasets = array("status"=>"Closed");
		$counter_qcheck = array("counterid"=>$fieldset4);
		mysqli_data_update($tbL21,$update_counter_datasets,$counter_qcheck);

		//close user counter log
		$update_user_counter_datasets = array("logstatus"=>"Closed");
		$counter_user_qcheck = array("counterid"=>$fieldset4,"userid"=>USER_AUTHEN_ID,"logstatus"=>"Open");
		mysqli_data_update($tbL22,$update_user_counter_datasets,$counter_user_qcheck);

		//close counter shift
		$update_counter_shift_datasets = array("closetime"=>$server_get_time);
		$shift_qcheck = array("userid"=>USER_AUTHEN_ID);
		$additionalQuery = " ORDER BY id DESC LIMIT 1";
		mysqli_data_update($tbL23,$update_counter_shift_datasets,$shift_qcheck);

		$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
		$post_result .= '<small class="red-font">Completing request, please wait..</small>';
		$post_result .= '</div>';

		$nextpg = $_POST['donext'];

		?>
			<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location = "<?php echo $nextpg; ?>"; }, false); </script>
		<?php
	}
}

#-----------------------------------------------------------------------------------------------------------------------------------------------------------

$additionalQuery = "";

/*$counter_query = array("id"=>$_SESSION['counter_id']);
$get_counter_data = mysqli_data_fetch($tbL19,'countername',$counter_query,'noarray');
$counter_select = $get_counter_data[0];*/

$counter_select = idget_data($tbL19,$_SESSION['counter_id'],'countername');

if(isset($_GET['sesid']) && $_GET['sesid'] == 'y') {
	$page_continue = DOMAIN_URL.'public/admin/logout'.PHP_EXT;
} elseif(isset($_GET['sesid']) && $_GET['sesid'] == 'ny') {
	$page_continue = DOMAIN_URL.'login/shift_counter'.PHP_EXT;
} else {
	$page_continue = "";
}

if(isset($page_continue) && !empty($page_continue)) {

	//$_SESSION['pageloadnext'] = $page_continue;

	$user_counter_dataproperty = "id,fundid,openingbalance,fundadded,withdrawal,collection,refunds,err";
	$user_counter_query = array("deletedata"=>0,"counterid"=>$_SESSION['counter_id'],"userid"=>USER_AUTHEN_ID,"ispast"=>0);
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
				//$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				//$htmlresult .= '&#8358; '.number_format($usd_value["fundadded"]);
				//$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["withdrawal"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["collection"]);
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($usd_value["refunds"]);
				$htmlresult .= '</td>';
				//$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				//$htmlresult .= '&#8358; '.number_format($moneyathand);
				//$htmlresult .= '</td>';
				$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				$htmlresult .= '&#8358; '.number_format($moneyathand);
				//$htmlresult .= '<input type="number" name="amt_fieldset[]" placeholder="0" required>';
				$htmlresult .= '<input type="hidden" name="amt_fieldset[]" placeholder="0" value="0" required>';
				$htmlresult .= '<input type="hidden" name="camt_fieldset[]" value="'.$usd_value["openingbalance"].'" required>';
				//$htmlresult .= '<input type="hidden" name="name_fieldset[]" value="'.$usd_value["fundid"].'" required>';
				$htmlresult .= '</td>';
				//$htmlresult .= '<td width="120px" align="center" class="box-border-thick-right">';
				//$htmlresult .= number_format($usd_value["err"]);
				//$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
		}

		$htmlresult .= '<tr>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&nbsp;</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_openingbal).'</td>';
		//$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_fundadded).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_withdrawal).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_collection).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_refunds).'</td>';
		$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_moneyathand).'</td>';
		//$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&nbsp;</td>';
		//$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_err).'</td>';
		$htmlresult .= '</tr>';

	} else {
		$counter_avail = 0;
	}

} else {
	$counter_avail = 0;
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<title><?php echo _CTITLE_; ?></title>
		<meta name="description" content="<?php echo _CTITLE_; ?>">
		<link rel="shortcut icon" href="../theme/images/inc/favicon.png" type="images/x-icon"/>
		<script type="text/javascript" src="../style/csslibrary/flexcroll.js"></script>
		<link rel="stylesheet" href="../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../style/custom.css"/>
		<script type="text/javascript" src="../js/jsbk.js"></script>
	</head>
	<body class="grey-theme y-scroll">
		<div class="block-element top-pull-50" align="center">
			<img src="<?php echo _FC_LOGO; ?>" class="bottom-push-20">
			<div class="cs-width-1200 white-theme pads30 sml-rounded-button obj-shadow bottom-push-30" align="left">
				<h3 class="nobold blue-font"><b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b> (<?php echo $counter_select; ?>)</h3><br>
				<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">
					<?php echo $post_result; ?>
					<br><h4 class="large nobold default-text-font-bold">Closing transactions for this counter shift. Ensure all accounts are settled then indicate the closing balance to becoming the next counter shift</h4><br>
					<form action="" method="post">
						<div class="block-element sml-rounded-button noscroll">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="250px" class="box-border-thick-right" align="center">Funds Type</th>
									<th width="100px" class="box-border-thick-right" align="center">Last Opening Bal.</th>
									<!--<th width="150px" class="box-border-thick-right" align="center">Added</th>-->
									<th width="150px" class="box-border-thick-right" align="center">Withdrawals</th>
									<th width="150px" class="box-border-thick-right" align="center">Collections</th>
									<th width="150px" class="box-border-thick-right" align="center">Refunds</th>
									<!--<th width="150px" class="box-border-thick-right" align="center">Bal. at Hand</th>-->
									<th width="150px" class="box-border-thick-right" align="center">Closing Bal.</th>
									<!--<th width="150px" class="box-border-thick-right" align="center">Discrepancies</th>-->
								</tr>
								<?php echo $htmlresult; ?>
								<tr>
									<td colspan="5" class="box-noborder">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<h3 class="large nobold default-text-font-bold">Remarks for counter</h3>
										<textarea name="discrepancy_fieldset" placeholder="Indicate ok if none" required="required"></textarea>
									</td>
								</tr>
							</table>
						</div>
						
						<?php
							if(isset($counter_avail) && $counter_avail == 1) {
								?>
									<br><br>

									<p id="submit-box">
										<input type="hidden" name="counter_id" value="<?php echo $_SESSION['counter_id']; ?>">
										<input type="hidden" name="donext" value="<?php echo $page_continue; ?>">
										<input type="submit" name="submitbutton" value="Close Counter" class="submit pads10 blue-white-state rounded-button nc-width-20"> &nbsp;&nbsp;&nbsp; <input type="button" value="Continue Working" class="submit pads10 dark-black-white-state rounded-button nc-width-20" onclick="window.location.href='<?php echo DOMAIN_URL.PUB_FLD.'admin/portal'.PHP_EXT; ?>'">
									</p>

									<p class="alignct top-pull-20">
										<a href="print_shift_counter<?php echo PHP_EXT; ?>?ctr=<?php echo USER_AUTHEN_ID; ?>" class="blue-font">Print Shift Counter</a>
									</p>

								<?php
							}
						?>	
					</form>
				</div>
			</div>
		</div>
	</body>
</html>