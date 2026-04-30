<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_; ?>

<?php

include "../includes/hotel_profile.php";

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page');
$userSignedIn = $_GET['ctr'];

$get_counter_id = idget_fdata($tbL22,'userid',$userSignedIn,'counterid');
$counter_select = idget_data($tbL19,$get_counter_id,'countername');
$cur_user_name = idget_data($tbL7,$userSignedIn,'staffname');

$htmlresult='';
$post_htmlresult='';

$user_counter_dataproperty = "id,fundid,openingbalance,fundadded,withdrawal,collection,refunds,err";
$user_counter_query = array("deletedata"=>0,"counterid"=>$get_counter_id,"userid"=>$userSignedIn);
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

			$moneyathand = ($usd_value["openingbalance"] + $usd_value["fundadded"] + $usd_value["collection"]) - ($usd_value["withdrawal"] + $usd_value["refunds"]);

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
			$htmlresult .= '&#8358; '.number_format($usd_value["fundadded"]);
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
	$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_fundadded).'</td>';
	$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_withdrawal).'</td>';
	$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_collection).'</td>';
	$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_refunds).'</td>';
	$htmlresult .= '<td align="center" class="royal-blue-theme white-font">&#8358; '.number_format($total_moneyathand).'</td>';
	$htmlresult .= '</tr>';

} else {
	$counter_avail = 0;
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<title><?php echo _CTITLE_; ?> | Print User Shift Counter</title>
		<meta name="description" content="<?php echo _CTITLE_; ?>">
		<link rel="shortcut icon" href="../theme/images/inc/favicon.png" type="images/x-icon"/>
		<script type="text/javascript" src="../style/csslibrary/flexcroll.js"></script>
		<link rel="stylesheet" href="../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../style/custom.css"/>
		<script type="text/javascript" src="../js/jsbk.js"></script>
	</head>
	<body class="grey-theme y-scroll">
		<p class="alignrt pads30">
			<input type="button" value="Print" class="submit pads10 dark-black-white-state rounded-button nc-width-10" onclick="window.print()">
		</p>
		<div id="section-to-print" class="pads10">
			<div class="block-element" align="center">
				<img src="<?php echo _FC_LOGO; ?>" class="bottom-push-20">
				<h1 class="large"><?php echo _LONG_NAME; ?></h1>
				<h3 class="large nobold"><?php echo $hotel_address; ?><br>Phone: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></h3>
				<div class="cs-width-1000 white-theme top-push-30 pads30 sml-rounded-button obj-shadow bottom-push-30" align="left">
					<h2 class="large nobold">for: <b><?php echo $cur_user_name; ?></b></h2><br>

					<h4 class="large">Transaction as at today</h4><br>
					<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">

					</div>

					<br><br>

					<h4 class="large">Pay-type details</h4><br>
					<div class="block-element box-border-thick pads20 sml-rounded-button" align="center">
						<form action="" method="post">
							<div class="block-element sml-rounded-button noscroll">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<th width="250px" class="box-border-thick-right" align="center">Funds Type</th>
										<th width="100px" class="box-border-thick-right" align="center">Last Opening Bal.</th>
										<th width="150px" class="box-border-thick-right" align="center">Added</th>
										<th width="150px" class="box-border-thick-right" align="center">Withdrawals</th>
										<th width="150px" class="box-border-thick-right" align="center">Collections</th>
										<th width="150px" class="box-border-thick-right" align="center">Refunds</th>
										<th width="150px" class="box-border-thick-right" align="center">Bal. at Hand</th>
									</tr>
									<?php echo $htmlresult; ?>
									<tr>
										<td colspan="5" class="box-noborder">
											&nbsp;
										</td>
									</tr>
									<tr>
										<td colspan="5">
											<small class="block-element bottom-push-7"><b>remarks / reason for discrepancy</b></small>
											<textarea name="discrepancy_fieldset" placeholder="Indicate ok if none" required="required"></textarea>
										</td>
									</tr>
								</table>
							</div>
						</form>
					</div>

					<p class="top-pull-30 alignct">
						<small>--- Printed by: <?php echo $admin_name; ?> ---</small>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>