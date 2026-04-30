<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_;  include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../module_operation_privilege.php";

?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
		<title>Docket Activities</title>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<link rel="touch-startup-image" href="">
		<meta name="theme-color" content="#000000">

		<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../../style/custom.css"/>
		<link rel="stylesheet" href="../applystyle.css"/>
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>
		<script src="../../ckeditor/ckeditor.js"></script>
	</head>
	<body>
		<div class="block-element pads30">

			<span class="float-right top-pull-3 left-pull-20"><a href="" class="box-border-thick top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 default-text-font-bold rounded-button blue-font ft-xsml-size">Refresh</a></span>
			<span class="float-right top-pull-3"><?php echo date('D. F jS, Y'); ?></span>
			<h3 class="xlarge ft-tahoma nomargin">Waiter Pending Transactions</h3>
			<p>&nbsp;</p>

			<div class="sml-rounded-button noscroll">
			<table cellpadding="5" cellspacing="0">
				<tr>
					<th width="200px" align="center" class="default-text-font-bold ft-xxsml-size">Waiter</th>
					<th width="100px" align="center" class="default-text-font-bold ft-xxsml-size">Table</th>
					<th width="70px" align="center" class="default-text-font-bold ft-xxsml-size">Order No.</th>
					<th width="100px" align="center" class="default-text-font-bold ft-xxsml-size">Amount (&#8358;)</th>
				</tr>
				
				<?php
					//get all pending transaction and list them here
					
					$additionalQuery = " AND cashier=waiter";
					$query_pendings = array("posid"=>$cur_pos_store_id,"status"=>"Pending","payment"=>"Pending","isreversed"=>0,"datelogged"=>$server_get_date);
					$get_pending_tr = mysqli_data_fetch($tbL100,'waiter,order_number,bill_amount,tableid',$query_pendings,'array');

					$additionalQuery = "";

					if(is_array($get_pending_tr)) {
						
						$table_name = ""; $waiter = "";
						
						foreach($get_pending_tr as $key => $val) {

							$table_name = idget_data($tbL17,$val['tableid'],'tablename');
							$waiter = idget_data($tbL7,$val['waiter'],'staffname');

							?>

								<tr>
									<td align="center" class="default-text-font-bold ft-xsml-size"><?php echo $waiter; ?></td>
									<td align="center" class="default-text-font-bold ft-xsml-size"><?php echo $table_name; ?></td>
									<td align="center" class="default-text-font-bold"><a href="?getorder=<?php echo $val['order_number']; ?>" class="default-text-font-bold steel-blue-font ft-xsml-size"><?php echo $val['order_number']; ?></a></td>
									<td align="center" class="default-text-font-bold ft-xsml-size"><?php echo number_format($val['bill_amount'],2); ?></td>
								</tr>
								
							<?php

							if(isset($_GET['getorder']) && $_GET['getorder'] == $val['order_number']) {

								$query_order = array("order_number"=>$_GET['getorder']);
								$get_order_tr = mysqli_data_fetch($tbL99,'itemid,qty,price,amount',$query_order,'array');

								?>
									<tr>
										<td colspan="4" class="sky-blue-theme sml-rounded-button pads30">
						
											<div class="sml-rounded-button noscroll">
												<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
													<tr>
														<td width="50px" align="center" class=""></td>
														<td width="200px" align="center" class="default-text-font-bold ft-xxsml-size">Item</td>
														<td width="100px" align="center" class="default-text-font-bold ft-xxsml-size">Price</td>
														<td width="70px" align="center" class="default-text-font-bold ft-xxsml-size">Unit(s)</td>
														<td width="100px" align="center" class="default-text-font-bold ft-xxsml-size">Amount</td>
													</tr>

													<?php
														$numbr = 0; $item = "";

														foreach($get_order_tr as $key => $val) {
															
															$numbr += 1;

															$item = idget_data($tbL16,$val['itemid'],'item');

															?>
																<tr>
																	<td align="center" class="ft-xsml-size"><?php echo $numbr; ?>.</td>
																	<td align="center" class="ft-xsml-size"><?php echo $item; ?></td>
																	<td align="center" class="ft-xsml-size"><?php echo number_format($val['price'],2); ?></td>
																	<td align="center" class=" ft-xsml-size"><?php echo $val['qty']; ?></td>
																	<td align="center" class=" ft-xsml-size"><?php echo number_format($val['amount'],2); ?></td>
																</tr>
															<?php
														}
													?>

												</table>
											</div>
											
										</td>
									</tr>

								<?php
							}
						}

					}

				?>

			</table>
			</div>

		</div>
	</body>
</html>