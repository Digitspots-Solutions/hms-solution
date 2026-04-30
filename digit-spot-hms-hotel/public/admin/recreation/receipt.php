<?php
include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_; include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

$smdl = "recreation";

	?>
		<link rel="stylesheet" href="../../../style/csslibrary/default.css" media="all" />
		<link rel="stylesheet" href="../../../style/custom.css" media="all" />
		<link rel="stylesheet" href="../admin/applystyle.css" media="all" />
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>

	<?php

	include "../../../includes/hotel_profile.php";
	include "../../../includes/common_data_vars.php";

	//------------------------------------------------------------------------------------------------------------------------------


	if(isset($_GET['member']) && is_numeric($_GET['member']))
	{
		
		$fieldset = escape_data($_GET['member']);

		$membr_selection_key = array("id"=>$fieldset,"deletedata"=>0);
		$membr_data = mysqli_data_fetch($tbL105,'recreation_number,firstname,lastname,membership_type,startdate,enddate,plan,emailaddress,mobile',$membr_selection_key,'noarray');

		$sp_membr_selection_key = array("memberid"=>$fieldset,"listype"=>"spouse","deletedata"=>0);
		$sp_membr_data = mysqli_data_fetch($tbL106,'flname,dob',$sp_membr_selection_key,'noarray');

		$chl_membr_selection_key = array("memberid"=>$fieldset,"listype"=>"child","deletedata"=>0);
		$chl_membr_data = mysqli_data_fetch($tbL106,'flname,dob',$chl_membr_selection_key,'array');

		$pay_selection_key = array("memberid"=>$fieldset);

		if(isset($_GET['receipt']) && !empty($_GET['receipt'])) {
			$pay_selection_key['invoice_number'] = $_GET['receipt'];
			$additionalQuery = "";
		} else {
			$additionalQuery = " AND isreversed=0 AND deletedata=0 ORDER BY id DESC LIMIT 1";
		}
		
		$pay_data = mysqli_data_fetch($tbL107,'invoice_number,mode,amount,receipt,datelogged,paymentdate,userid,detail,timelogged',$pay_selection_key,'noarray');

		$get_plan = arrayget_key($recreation_duration,$membr_data[6]);
		$mode = idget_data($tbL24,$pay_data[1],'name');

		?>
			<div class="cs-height-100">
			</div>
			
			<div class="block-element light-yellow-theme pads10 bottom-push-10 left-push-10">
				<span class="float-right"><a href="javascript:window.print()" class="blue-font ft-xsml-size"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print Receipt</a></span>
				<small>Note: Use tab-refresh icon to return to default screen</small>
			</div>

			<div id="section-to-print" class="block-element">
				<div class="block-element cs-width-350">
					<span class="block-element box-border-thick-bottom bottom-pull-15 bottom-push-15">
						<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
						<h3 class="large alignct"><?php echo $hotel_address; ?></h3>
						<small class="block-element alignct"><?php echo $hotel_fs_phonenumber.', '.$hotel_email; ?></small>
					</span>

					<h4 class="large alignct">Recreation Membership Payment Receipt</h4>
					
					<p>&nbsp;</p>
					<h4 class="large nobold alignct">Date/time: <?php echo date("d/m/Y",strtotime($pay_data[4])).' '.$pay_data[8]; ?></h4><br>
				
					<div class="block-element pads10 box-border-thick-bottom bottom-push-10">
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Recreation Number</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $membr_data[0]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Bill To</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $membr_data[1].' '.$membr_data[2]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Membership Type</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $membr_data[3]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Membership Period</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo date("d/m/Y",strtotime($membr_data[4])).' &mdash; '.date("d/m/Y",strtotime($membr_data[5])); ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Duration</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $get_plan; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Invoice Number</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $pay_data[0]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Amount Paid</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element">&#8358; <?php echo number_format($pay_data[2],2); ?></small>
							<small class="block-element top-push-10">(<?php echo $mode; ?>)</small>
							<small class="block-element top-push-10"><?php echo $pay_data[7]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Contact Email</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $membr_data[7]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
							<small class="black-font add-bold">Contact Mobile Number</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 bottom-push-20">
							<small class="block-element"><?php echo $membr_data[8]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
					<div class="block-element pads10 box-border-thick-bottom bottom-push-30">
						<h3 class="large alignct">Couple/Family Details</h3>
						<span class="ln-display-box float-left nc-width-50 right-pull-30 top-push-7 bottom-push-20">
							<small class="black-font add-bold">Spouse Name</small>
						</span>
						<span class="ln-display-box float-left nc-width-50 top-push-7 bottom-push-20">
							<small class="block-element"><?php echo $sp_membr_data[0]; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
						
						<?php

							if(is_array($chl_membr_data)) {
								foreach ($chl_membr_data as $chl_key => $chl_value) {
									?>
										<span class="ln-display-box float-left nc-width-50 right-pull-30 bottom-push-20">
											<small class="dark-grey-font add-bold">Child Name</small>
										</span>
										<span class="ln-display-box float-left nc-width-50 bottom-push-20">
											<small class="block-element"><?php echo $chl_value['flname']; ?></small>
										</span>
										<span class="block-element new-line-space">
										</span>
									<?php
								}
							}

						?>

					</div>
				</div>
			</div>

		<?php
	}

?>

<script>
	
	window.addEventListener('load',function() {
		window.print();
	},false);

</script>
		