<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_;

sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
$userSignedIn = $_SESSION['authenticate_id'];
$cur_pos_store_id = $_SESSION['postoreid'];
$wget_pos_name = idget_data($tbL14,$cur_pos_store_id,'posname');

$smdl = "pos";


$htmlresult='';
$post_htmlresult='';
$loadsheet = 0;

if(isset($_GET['cl']) && $_GET['cl'] === 'shift') {
	
	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$shift_qcheck = array("userid"=>$userSignedIn);
	
	$update_counter_shift_datasets = array("closetime"=>$server_get_time,"dateclosed"=>$server_get_date,"status"=>"Closed");
	mysqli_data_update($tbL23,$update_counter_shift_datasets,$shift_qcheck);

	#get shift details
	
	$pst_query = array("userid"=>$userSignedIn,"status"=>"Closed");
	$pst_field = "shiftid,resumptiontime,closetime,datelogged,dateclosed";
	$get_data = mysqli_data_fetch($tbL23,$pst_field,$pst_query,'noarray');

	$shift_name = idget_data($tbL20,$get_data[0],'shiftname');

	#--new line
	# payment modes received by general cashier

	$additionalQuery = "";

	$pst_field = "id,name";
	$pst_query = array("isreceivable"=>"Yes");
	$get_receivables = mysqli_data_fetch($tbL24,$pst_field,$pst_query,'array');

	$rs_modes = "";

	if(is_array($get_receivables)) {
		foreach($get_receivables as $key => $val) {
			$rs_modes .= $val['id'].',';
		}
	}

	$rs_modes = substr_replace($rs_modes,'',-1,1);

	$sqlset = "SUM(bill_amount)";
	$queryset = "posid={$cur_pos_store_id} AND shiftid={$get_data[0]} AND cashier={$userSignedIn} AND media IN({$rs_modes}) AND datelogged BETWEEN '{$get_data[3]}' AND '{$get_data[4]}' AND isreversed=0 AND deletedata=0";
	$wgt_gc_funds = mysqli_arithmetic_data($tbL100,$sqlset,$queryset);

	$acct_src = idget_data($tbL14,$cur_pos_store_id,'posname'); $cashier = $userSignedIn;
	$counter = $cur_pos_store_id; $shift = $shift_name; $shift_start_date = $get_data[3]; $shift_end_date = $get_data[4];
	$start_time = $get_data[1]; $end_time = $get_data[2];
	$shiftid = $get_data[0]; $amount = $wgt_gc_funds;

	$pst_query = array("counterid"=>$counter,"shiftid"=>$shiftid,"userid"=>$cashier,"datelogged"=>$server_get_date);
	$pst_field = array("account"=>$acct_src,"shift_start_date"=>$shift_start_date,"shift_end_date"=>$shift_end_date,"shift_start_time"=>$start_time,"shift_end_time"=>$server_get_time,"shift"=>$shift,"counterid"=>$counter,"shiftid"=>$shiftid,"userid"=>$cashier,"paid_amount"=>$amount,"account_src"=>"pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	$result = mysqli_data_insert($tbL158,$pst_field,$pst_query);

	if(isset($result) && $result == 2) {
		$loadsheet = 1;
		$post_htmlresult = '<p class="top-pull-10 light-red-font">Preparing shift sales sheet..</p>';

		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently retired cash sales of {$amount} to general cashier","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

	} elseif(isset($result) && $result == 1) {
		$loadsheet = 8;
		$post_htmlresult = '';
	} else {
		$loadsheet = 0;
		$post_htmlresult = '';
	}

	$return2work = "no";
	$_SESSION['return2work'] = "no";
	unset($_GET['cl']);
}


if(isset($_GET['ncl']) && $_GET['ncl'] === 'shift') {
	
	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$shift_qcheck = array("userid"=>$userSignedIn);
	
	$update_counter_shift_datasets = array("dateclosed"=>$server_get_date);
	mysqli_data_update($tbL23,$update_counter_shift_datasets,$shift_qcheck);

	#get shift details
	
	$pst_query = array("userid"=>$userSignedIn,"status"=>"Open");
	$pst_field = "shiftid,resumptiontime,closetime,datelogged,dateclosed";
	$get_data = mysqli_data_fetch($tbL23,$pst_field,$pst_query,'noarray');

	$shift_name = idget_data($tbL20,$get_data[0],'shiftname');

	#--new line
	# payment modes received by general cashier

	$additionalQuery = "";

	$pst_field = "id,name";
	$pst_query = array("isreceivable"=>"Yes");
	$get_receivables = mysqli_data_fetch($tbL24,$pst_field,$pst_query,'array');

	$rs_modes = "";

	if(is_array($get_receivables)) {
		foreach($get_receivables as $key => $val) {
			$rs_modes .= $val['id'].',';
		}
	}

	$rs_modes = substr_replace($rs_modes,'',-1,1);

	$sqlset = "SUM(bill_amount)";
	$queryset = "posid={$cur_pos_store_id} AND shiftid={$get_data[0]} AND cashier={$userSignedIn} AND media IN({$rs_modes}) AND datelogged BETWEEN '{$get_data[3]}' AND '{$get_data[4]}' AND isreversed=0 AND deletedata=0";
	$wgt_gc_funds = mysqli_arithmetic_data($tbL100,$sqlset,$queryset);

	$acct_src = idget_data($tbL14,$cur_pos_store_id,'posname'); $cashier = $userSignedIn;
	$counter = $cur_pos_store_id; $shift = $shift_name; $shift_start_date = $get_data[3]; $shift_end_date = $get_data[4];
	$start_time = $get_data[1]; $end_time = $get_data[2];
	$shiftid = $get_data[0]; $amount = $wgt_gc_funds;

	$pst_query = array("counterid"=>$counter,"shiftid"=>$shiftid,"userid"=>$cashier,"datelogged"=>$server_get_date);
	$pst_field = array("account"=>$acct_src,"shift_start_date"=>$shift_start_date,"shift_end_date"=>$shift_end_date,"shift_start_time"=>$start_time,"shift_end_time"=>$server_get_time,"shift"=>$shift,"counterid"=>$counter,"shiftid"=>$shiftid,"userid"=>$cashier,"paid_amount"=>$amount,"account_src"=>"pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

	$result = mysqli_data_insert($tbL158,$pst_field,$pst_query);

	if(isset($result) && $result == 2) {
		$loadsheet = 1;
		$post_htmlresult = '<p class="top-pull-10 light-red-font">Preparing shift sales sheet..</p>';

		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently retired cash sales of {$amount} to general cashier","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

	} elseif(isset($result) && $result == 1) {
		$loadsheet = 8;
		$post_htmlresult = '';
	} else {
		$loadsheet = 0;
		$post_htmlresult = '';
	}

	$return2work = "yes";
	$_SESSION['return2work'] = "yes";
	unset($_GET['ncl']);
}


/*if(isset($_GET['sesid']) && $_GET['sesid'] == 'end') {
	$loadsheet = 2;
	$post_htmlresult = '<p class="top-pull-10 light-red-font">Starting new shift..</p>';
	
	unset($_GET['sesid']);
	session_destroy();
}*/

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
	<body class="grey-1-theme y-scroll">

		<div class="pads30" align="center">
			<div class="cs-height-50"></div>
			<p class="ft-large-size alignct default-text-font-bold"><?php echo $wget_pos_name; ?> Shiftwise Sales</p>
			<p>&nbsp;</p>
			<div class="fx-width-40 white-theme pads30 sml-rounded-button">
				<div class="cs-width-120 cs-height-120 grey-theme rounded-element bottom-push-15 noscroll"><b class="fa-user nobold fa-xxlarge-size"></b></div>
				<h1 class="xlarge nobold default-text-font-bold"><?php echo $admin_name; ?></h1><br>
				<h3 class="xlarge nobold right-pull-20 left-pull-20">Submit Sales Report: Select the task you want to perform at the moment?</h3>

				<?php if($loadsheet == 1): echo $post_htmlresult; ?>
					
					<script>
						window.addEventListener('load', () => {
							window.location.href = "<?php echo DOMAIN_URL.PUB_FLD.'admin/pos/print_pos_shiftwise_details'.PHP_EXT; ?>?ses=pos&shift=<?php echo $shiftid; ?>&cashier=<?php echo $cashier; ?>&trd1=<?php echo $shift_start_date; ?>&trd2=<?php echo $shift_end_date; ?>&return=<?php echo $return2work; ?>";
						}, false);
					</script>

				<?php elseif($loadsheet == 2): echo $post_htmlresult; ?>
					
					<script>
						window.addEventListener('load', () => {
							window.location.href = "<?php echo DOMAIN_URL.'login/'; ?>";
						}, false);
					</script>

				<?php elseif($loadsheet == 8): ?>
					
					<p class="top-pull-30 alignct ft-sml-size light-red-font">
						* Please return to login screen to start a new shift or use new outlet
					</p>
			
				<?php else: ?>

					<p class="top-pull-30">
						<input type="button" value="Submit & End Shift" class="submit pads10 red-white-state rounded-button nc-width-40" onclick="sendsales(1)"> <input type="button" value="Submit & Continue Working" class="submit pads10 dark-black-white-state rounded-button nc-width-50 left-push-10" onclick="sendsales(2)">
					</p>
					<p class="top-pull-7 alignct">
						Not now, <a href="<?php echo DOMAIN_URL.PUB_FLD.'admin/portal'.PHP_EXT; ?>" class="blue-font"><u>Return</u></a>
					</p>

				<?php endif; ?>
			</div>
		</div>
	</body>
</html>

<script>

	function sendsales(option) {

		const link = "<?php echo $_SERVER['PHP_SELF']; ?>";

		var conf,lpath;

		if(option == 1) {
			lpath = link+'?cl=shift';
			conf = confirm('Are you sure you want to submit sales and end your shift?');
		} else if(option == 2) {
			lpath = link+'?ncl=shift';
			conf = confirm('Are you sure you want to submit sales and continue working?');
		}

		if(conf == true) {
			window.location.href = lpath;
		}
	}
</script>