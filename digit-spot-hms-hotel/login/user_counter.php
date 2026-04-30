<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_; ?>

<?php
sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];


$htmlresult='';
$post_htmlresult='';
$post_result= '';

if(isset($_POST['submitbutton']))
{
	createDatabasetable($var_tbl_23); //create a table for this post

	$sales_counter_sql = array("ispast"=>1);
	$sales_counter_query = array("counterid"=>$_POST['counter_id'],"userid"=>$userSignedIn,"ispast"=>0);
	mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

	$fieldset1 = $_POST['name_fieldset']; //array data
	$fieldset2 = $_POST['amt_fieldset']; //array data
	$fieldset3 = $_POST['message'];
	$fieldset4 = $_POST['counter_id'];
	$fieldset5 = $_POST['newcounter'];

	$counter_datasets = ""; $counter_data_query = ""; $isdata = 0;

	for($p=0; $p < count($fieldset1); $p++) {
		
		$query_fund = array("userid"=>$userSignedIn,"counterid"=>$fieldset4,"fundid"=>$fieldset1[$p],"ispast"=>0);
		$fund_data = mysqli_data_fetch($tbL25,'id,openingbalance',$query_fund,'noarray');
		
		if(!empty($fund_data[0]) && $fund_data[0] > 0) {
			if(!empty($fieldset2[$p]) && $fieldset2[$p] > 0) { $new_opening_bal = $fund_data[1] + $fieldset2[$p]; }
			else { $new_opening_bal = $fund_data[1]; }
			
			$counter_datasets = array("openingbalance"=>$new_opening_bal);
			mysqli_data_update($tbL25,$counter_datasets,$query_fund);
			
			$isdata += 1;

		} else {
			$counter_datasets = array("userid"=>$userSignedIn,"counterid"=>$fieldset4,"shiftid"=>$current_shift,"fundid"=>$fieldset1[$p],"openingbalance"=>$fieldset2[$p],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
			mysqli_data_insert($tbL25,$counter_datasets,'');
			
			$isdata += 1;
		}
	}

	if(isset($isdata) && $isdata >= 1) {
		
		createDatabasetable($var_tbl_25); //create a table for this post

		$err_datasets = array("userid"=>$userSignedIn,"counterid"=>$fieldset4,"message"=>escape_data($fieldset3),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		mysqli_data_insert($tbL27,$err_datasets,'');

		$post_result .= '<div class="block-element top-push-5 bottom-push-5">';
		$post_result .= '<small class="red-font">Please wait while loading hotelmaster..</small>';
		$post_result .= '</div>';

		?>
			<script> window.addEventListener('load', function() { objHidden('submit-box'); window.location.href = "<?php echo DOMAIN_URL; ?>public/admin/portal<?php echo PHP_EXT; ?>"; }, false); </script>
		<?php
	}

	$disableGET = 'yes';
}

if((!isset($disableGET)) && (isset($_GET['cid']) && $_GET['cid'] >= 1)) {

	$_SESSION['counter_id'] = $_GET['cid'];
	$counter_select = idget_data($tbL19,$_SESSION['counter_id'],'countername');

	$user_counter_dataproperty = "id,fundid,openingbalance,closingbalance,err";
	$user_counter_query = array("deletedata"=>0,"counterid"=>$_SESSION['counter_id'],"userid"=>$userSignedIn,"ispast"=>0);
	$user_counter_data = mysqli_data_fetch($tbL25,$user_counter_dataproperty,$user_counter_query,'array');

	if(is_array($user_counter_data)) {

		$counter_avail = 1; $newcounter = 0; $paytype_query = "";

		foreach ($user_counter_data as $usd_key => $usd_value) {
			
			$paytype_query = array("id"=>$usd_value["fundid"]);
			$paytype_data = mysqli_data_fetch($tbL24,'name',$paytype_query,'noarray');

			$htmlresult .= '<tr>';
			$htmlresult .= '<td width="250px" align="center" class="box-border-thick-left box-border-thick-right">';
			$htmlresult .= $paytype_data[0];
			$htmlresult .= '<input type="hidden" name="name_fieldset[]" value="'.$usd_value["fundid"].'" required>';
			$htmlresult .= '</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">';
			$htmlresult .= '<input type="number" name="amt_fieldset[]" value="'.$usd_value["closingbalance"].'" required>';
			$htmlresult .= '</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">';
			$htmlresult .= number_format($usd_value["err"]);
			$htmlresult .= '</td>';
			$htmlresult .= '</tr>';
		}

	} else {
		//$user_counter_query = array("deletedata"=>0,"iscounter"=>"Yes");
		$user_counter_query = array("deletedata"=>0);
		$user_counter_data = mysqli_data_fetch($tbL24,'id,name,paytype,isreceivable',$user_counter_query,'array');
		if(is_array($user_counter_data)) {
			
			$counter_avail = 1; $newcounter = 1;

			foreach ($user_counter_data as $usd_key => $usd_value) {
				$htmlresult .= '<tr>';
				$htmlresult .= '<td width="250px" align="center" class="box-border-thick-left box-border-thick-right">';
				$htmlresult .= $usd_value["name"];
				$htmlresult .= '<input type="hidden" name="name_fieldset[]" value="'.$usd_value["id"].'">';
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">';
				$htmlresult .= '<input type="number" name="amt_fieldset[]" value="0" required>';
				$htmlresult .= '</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">';
				$htmlresult .= '0.0';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

		} else {
			$counter_avail = 0;
			$htmlresult .= '<tr><td colspan="5"><small class="dark-grey-font">Error: No counter fund type</small></td></tr>';
		}
	}

} else {
	if(isset($_SESSION['counter_id'])) { $_SESSION['counter_id'] = $_SESSION['counter_id']; }
	else { $_SESSION['counter_id'] = 0; }
	$counter_select = 'Unknown';
	$counter_avail = 0;
	$htmlresult .= '<tr><td colspan="5"><small class="dark-grey-font">Error: Unknown counter!</small></td></tr>';
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
	<body class="grey-theme fx-position-rel nc-height-100 y-scroll">
		<div class="block-element top-pull-50" align="center">
			<img src="<?php echo _FC_LOGO; ?>" class="bottom-push-20">
			<div class="cs-width-700 white-theme pads30 sml-rounded-button obj-shadow bottom-push-30" align="left">
				<h3 class="nobold blue-font"><b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b> (<?php echo $counter_select; ?>)</h3><br>
				<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">
					<?php echo $post_result; ?>
					<br><h4 class="large nobold default-text-font-bold">Opening balances from last transaction. This will become your new opening for the counter, make changes and indicate remark to continue</h4><br>
					<form action="" method="post">
						<div class="block-element sml-rounded-button noscroll">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="250px" class="box-border-thick-right" align="center">Opening Balance</th>
									<th width="100px" class="box-border-thick-right" align="center">&nbsp;</th>
									<th width="150px" class="box-border-thick-right" align="center">Discrepancies</th>
								</tr>
								<?php echo $htmlresult; ?>
								<tr>
									<td colspan="5" class="box-noborder">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<h3 class="large nobold default-text-font-bold">Remarks</h3>
										<textarea name="message" placeholder="Indicate ok if none" required="required"></textarea>
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
										<input type="hidden" name="newcounter" value="<?php echo $newcounter; ?>">
										<input type="submit" name="submitbutton" value="Save & Open Counter" class="submit pads10 blue-white-state rounded-button nc-width-40">
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