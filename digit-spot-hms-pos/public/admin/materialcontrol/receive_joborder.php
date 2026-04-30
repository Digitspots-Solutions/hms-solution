<?php
include "includes/initialize_session.php";
include "includes/config.php";
include "../../../includes/uom.php";

$userSignedIn = $_SESSION['authenticate_id'];

#create table for job order control
createDatabasetable($var_tbl_160);
createDatabasetable($var_tbl_161);
createDatabasetable($var_tbl_162);

$receive_limit = 3;
$post_message = 0;

$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

$order_number = isset($_GET['pr']) ? $_GET['pr'] : 0;

$get_store = idget_fname($order_number,'order_number','store',$tbL121);
$get_store_type = idget_fname($order_number,'order_number','store_type',$tbL121);

$order_date = idget_fname($order_number,'order_number','order_date',$tbL121);
$delivery_date = idget_fname($order_number,'order_number','delivery_date',$tbL121);
$delivery_note = idget_fname($order_number,'order_number','delivery_note',$tbL121);

$supplier = idget_fname($order_number,'order_number','supplierid',$tbL121);
idget_global($supplier,$var_supplier);

if(isset($_POST['submitbutton'])) {

	$post_pr = $_POST['prno']; $post_store = $_POST['store']; $supplierid = $_POST['supplierid'];
	$rows = $_POST['rows']; $itemid = $_POST['itemid']; $qty_ordered = $_POST['qtyordered'];
	$qty_received = $_POST['qtyreceived']; $unitprice = $_POST['unitprice']; $newprice = $_POST['newprice'];
	$qty_receiving = $_POST['qty2receive']; $fa = $_POST['fa']; $sa = $_POST['sa']; $ta = $_POST['ta'];

	$chk_pr = "pr_no='{$post_pr}' AND deletedata=0";
	$ispr_exist = mysqli_data_exist($mtbL27,$chk_pr);

	if($ispr_exist == true) {
		$sql = "SELECT * FROM $mtbL27 WHERE pr_no='{$post_pr}' AND deletedata=0 ORDER BY id DESC LIMIT 1";
		$fetchBt = idget_data($sql);
		$batch = $fetchBt[0]['batch_no'] + 1;
	} else {
		$batch = 1;
	}

	$pst_query = "";
	$pst_field = "batch_no={$batch},pr_no='{$post_pr}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
	mysqli_data_insert($mtbL27,$pst_field,$pst_query);

	$grand_total = 0; $cpo = 0; $inserted = 0; $rate = 0;

	for($i=0; $i < count($rows); $i++) {
		
		//$to_receive = trim($qty_receiving[$i]);

		if($qty_receiving[$i] !='' && $qty_receiving[$i] > 0) {
			
			$groupid = idget_name($itemid[$i],'itemgroupid',$mtbL5);
			$category = idget_name($itemid[$i],'categoryid',$mtbL5);
			$subcategory = idget_name($itemid[$i],'subcategoryid',$mtbL5);
			$item_uom = idget_name($itemid[$i],'buying_unit',$mtbL5);

			$total_received = $qty_received[$i] + $qty_receiving[$i];
			$total_left = $qty_ordered[$i] - $total_received;

			if(!empty($newprice[$i]) && $newprice[$i] > 0) { $rate = $newprice[$i]; } 
			else { $rate = $unitprice[$i]; }

			$total_amount = $total_received * $rate;
			$new_total_order_amount = $qty_ordered[$i] * $rate;
			$received_total_amount = $qty_receiving[$i] * $rate;
			
			$grand_total = $grand_total + $total_amount;

			$pst_query = "id={$rows[$i]}";
			$pst_field = "receipt_status='Received',var_approval='Yes',qty_received='{$total_received}',unitprice='{$rate}',order_total_amount='{$new_total_order_amount}',order_net_amount='{$new_total_order_amount}',order_total_r_amount='{$total_amount}',order_net_r_amount='{$total_amount}',delivery_date='{$server_get_date}'";

			if($fa[$i] == 0) { $pst_field .= ",first_approval={$userSignedIn}"; }
			elseif($sa[$i] == 0) { $pst_field .= ",second_approval={$userSignedIn}"; }
			elseif($ta[$i] == 0) { $pst_field .= ",third_approval={$userSignedIn}"; }

			mysqli_data_update($tbL121,$pst_field,$pst_query);

			$pst_query = ""; $pst_field = "";

			
			#update designated store

			$chk_item = "itemid={$itemid[$i]} AND storageid={$post_store} AND deletedata=0";
			$is_item_exist = mysqli_data_exist($mtbL19,$chk_item);

			if($is_item_exist['isdata'] == true) {
			
				$sql_item = "SELECT * FROM {$mtbL19} WHERE ".$chk_item;
				$whr_item = idget_data($sql_item);

				$new_balance = $whr_item[0]['balance'] + $qty_receiving[$i];
				$new_stockin = $whr_item[0]['stockin'] + $qty_receiving[$i];
				$item_total_cost = $new_balance * $unitprice[$i];
				
				$entry = "update";
				$pst_query = "itemid={$itemid[$i]} AND storageid={$post_store} AND deletedata=0";
				$pst_field = "unitprice='{$unitprice[$i]}',stockin='{$new_stockin}',balance='{$new_balance}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with Job Order - {$qty_receiving[$i]} Qty',userid={$userSignedIn}";

			} else {
				
				$item_total_cost = $qty_receiving[$i] * $unitprice[$i];

				$entry = "insert";
				$pst_query = "itemid={$itemid[$i]} AND storageid={$post_store} AND deletedata=0";
				$pst_field = "storageid={$post_store},itemgroupid={$groupid},categoryid={$category},subcategoryid={$subcategory},itemid={$itemid[$i]},uom={$item_uom},supplierid={$supplierid},unitprice='{$unitprice[$i]}',stockin='{$qty_receiving[$i]}',balance='{$qty_receiving[$i]}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with Job Order',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
			}

			if($entry == 'insert') { mysqli_data_insert($mtbL19,$pst_field,$pst_query); }
			elseif($entry == 'update') { mysqli_data_update($mtbL19,$pst_field,$pst_query); }

			//error_log($pst_query.'/'.$pst_field,3,'w.txt');

			
			#delivery history

			$pst_query = "";
			$pst_field = "batch_no={$batch},pr_no='{$post_pr}',supplierid={$supplierid},itemid={$itemid[$i]},qty_received='{$qty_receiving[$i]}',qty_left='{$total_left}',unit_price='{$rate}',total_amount='{$received_total_amount}',delivery_date='{$server_get_date}',receivedby={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			mysqli_data_insert($mtbL25,$pst_field,$pst_query);

			//error_log('///'.$pst_query.'/'.$pst_field,3,'w.txt');


			if($total_received == $qty_ordered[$i]) { $cpo += 1; }

			$to_receive=""; $groupid=""; $category=""; $subcategory=""; $item_uom="";
			$total_received=""; $total_left=""; $total_amount=0; $entry=""; $chk_item="";

			$inserted += 1;
		}
	}

	$jo_iou_pay = idget_fname($post_pr,'pr_no','iou_no',$tbL153);

	if(!empty($jo_iou_pay) && $jo_iou_pay != 'Unknown') {
		$pst_query = "pr_no='{$post_pr}'";
		$pst_field = "pr_amount='{$grand_total}'";
		mysqli_data_update($tbL153,$pst_field,$pst_query);
	}

	$pst_query = "pr_no='{$post_pr}'";
	$pst_field = "delivery_no='{$post_pr}',pr_no='{$post_pr}',status='Partial',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
	mysqli_data_insert($mtbL26,$pst_field,$pst_query);

	if($cpo == count($rows)) {
		$pst_query = "pr_no='{$post_pr}'";
		$pst_field = "status='Completed',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
		mysqli_data_update($mtbL26,$pst_field,$pst_query);
	}

	if(isset($inserted) && $inserted) {$post_message = 200; }
}

?>

<script type="text/javascript" src="css3.0/flexcroll.js"></script>
<link rel="stylesheet" href="css3.0/default.css"/>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jspath.js"></script>
<script src="js/jsfx.js"></script>
<script src="js/index.js"></script>
<script src="js/all.js"></script>


<div class="pads10">
	<span class="float-right"><a href="javascript:void(0)" class="dark-grey-font ft-sml-size" onclick="window.parent.location.reload(true)">Close <b class="mbri-close left-push-5"></b></a></span>

	<h2 class="large nobold default-text-font-bold">JOB ORDER: <?php echo $_gparams[$var_supplier]['returnval']; ?></h2>
	<h3 class="large nobold"><u>LPO No.</u>: <?php echo $order_number; ?> &nbsp;|&nbsp; <u>Delivery Date</u>: <?php echo date('d-m-Y',strtotime($delivery_date)); ?> &nbsp;|&nbsp; <u>Delivery Note</u>: <?php echo $delivery_note; ?></h3>

	<div class="cs-height-10">
	</div>

	<form action="" method="post" autocomplete="off">
		<input type="hidden" name="prno" value="<?php echo $order_number; ?>">
		<input type="hidden" name="store" value="<?php echo $get_store; ?>">
		<input type="hidden" name="supplierid" value="<?php echo $supplier; ?>">

		<div class="box-border-thick sml-rounded-button pads15 cs-height-350 scroll">
			
			<?php
				
				$numbr = 0;

				$query_po_iou = "SELECT * FROM {$tbL153} WHERE pr_no='{$order_number}' AND deletedata=0";
				$wgt_po_iou = idget_data($query_po_iou);

				if(!empty($wgt_po_iou[0]['disbursedby']) && $wgt_po_iou[0]['disbursedby'] > 0) {
					
					?>
						<h3 class="large nobold light-red-font">Sorry, you can no longer receive this order as payment was completed for it</h3>
					<?php

					$showbutton = false;

				} else {

					$query_po_iou = "SELECT COUNT(batch_no) AS totalrows FROM {$mtbL27} WHERE pr_no='{$order_number}' AND deletedata=0";
					$wgt_po_iou = idget_data($query_po_iou);

					if($wgt_po_iou[0]['totalrows'] >= $receive_limit) {
						
						?>
							<h3 class="large nobold light-red-font">Sorry, you have reached receiving limit for this order. Contact <u>Paymaster</u> for fund disburstment</h3>
						<?php

						$showbutton = false;

					} else {

						$query_po = "SELECT * FROM {$tbL121} WHERE order_number='{$order_number}' AND deletedata=0";
						$wgt_po = idget_data($query_po);

						?>
							<h4 class="large nobold alignct red-font">Full/Partial Receive: Please indicate only quantity to receive</h4><br>
							<div class="cs-width-1200">
								<table cellspacing="0" cellpadding="0">
									<tr>
										<td class="default-text-font-bold right-pull-10 left-pull-10">SN.</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Ordered</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Received</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Receiving</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Unit Price</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">First Receiver</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Second Receiver</td>
										<td class="default-text-font-bold right-pull-10 left-pull-10">Third Receiver</td>
									</tr>

									<?php

										$total_amount = 0;
										$first_receiver = ""; $second_receiver = ""; $third_receiver = ""; $max_input = 0;
										
										if(is_array($wgt_po) && count($wgt_po) > 0) {
											
											foreach($wgt_po as $key2 => $val2) {
												
												if($val2['qty_ordered'] > $val2['qty_received']) {
												
													$numbr += 1;

													idget_global($val2['itemid'],$var_item);
													
													if(!empty($val2['first_approval']) && $val2['first_approval'] > 0) {
														idget_global($val2['first_approval'],$var_user);
														$first_receiver = $_gparams[$var_user]['returnval'];
														$fa_user = $val2['first_approval'];
													} else {
														$first_receiver = "---";
														$fa_user = 0;
													}

													if(!empty($val2['second_approval']) && $val2['second_approval'] > 0) {
														idget_global($val2['second_approval'],$var_user);
														$second_receiver = $_gparams[$var_user]['returnval'];
														$sa_user = $val2['second_approval'];
													} else {
														$second_receiver = "---";
														$sa_user = 0;
													}


													if(!empty($val2['third_approval']) && $val2['third_approval'] > 0) {
														idget_global($val2['third_approval'],$var_user);
														$third_receiver = $_gparams[$var_user]['returnval'];
														$ta_user = $val2['third_approval'];
													} else {
														$third_receiver = "---";
														$ta_user = 0;
													}
													
													$buyingUnit = arrayget_key($uoms,$val2['uom']);
													$max_input = $val2['qty_ordered'] - $val2['qty_received'];
													
													?>
														<tr>
															<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?>.</td>
															<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_item]['returnval']; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_ordered'].' '.$buyingUnit; ?></td>
															<td class="right-pull-10 left-pull-10"><?php echo $val2['qty_received'].' '.$buyingUnit; ?></td>
															<td class="right-pull-10 left-pull-10"><span class="float-left cs-width-100"><input type="hidden" name="rows[]" value="<?php echo $val2['id']; ?>" required><input type="hidden" name="itemid[]" value="<?php echo $val2['itemid']; ?>" required><input type="hidden" name="qtyordered[]" value="<?php echo $val2['qty_ordered']; ?>" required><input type="hidden" name="qtyreceived[]" value="<?php echo $val2['qty_received']; ?>" required><input type="hidden" name="unitprice[]" value="<?php echo $val2['unitprice']; ?>" required><input step=".01" min="0" max="<?php echo $max_input; ?>" type="number" name="qty2receive[]" placeholder="<?php echo $max_input; ?>" required></span><span class="float-left top-pull-10 left-push-5"><?php echo $buyingUnit; ?></span></td>
															<td class="right-pull-10 left-pull-10"><span class="float-left cs-width-100"><input step=".01" min="0" type="number" name="newprice[]" placeholder="0.00" required></span></td>
															<td class="right-pull-10 left-pull-10"><?php echo $first_receiver; ?><input type="hidden" name="fa[]" value="<?php echo $fa_user; ?>"></td>
															<td class="right-pull-10 left-pull-10"><?php echo $second_receiver; ?><input type="hidden" name="sa[]" value="<?php echo $sa_user; ?>"></td>
															<td class="right-pull-10 left-pull-10"><?php echo $third_receiver; ?><input type="hidden" name="ta[]" value="<?php echo $ta_user; ?>"></td>
														</tr>
													<?php

													$buyingUnit = "";
												}
											}
										}
									?>

								</table>
							</div>
						<?php

						$showbutton = true;
					}
				}
			?>

		</div>

		<?php
			
			if($showbutton == true) {
				
				$jo_iou_raised = idget_fname($order_number,'pr_no','iou_no',$tbL153);
				
				if(!empty($jo_iou_raised) && $jo_iou_raised != 'Unknown') {
					if($numbr > 0) {
						?>

							<p class="top-pull-10 alignrt">
								<input type="submit" name="submitbutton" id="submitbutton" value="Receive Order" class="top-pull-10 right-pull-50 bottom-pull-10 left-pull-50 blue-white-state xsml-rounded-button anchor">
							</p>

						<?php
					} else {
						?>

							<p class="top-pull-10 alignct default-text-font-bold black-font">
								- No action needed -
							</p>

						<?php
					}

				} else {
					?>

						<p class="top-pull-10 alignct default-text-font-bold black-font">
							- You need to raise MANUAL IOU and attach this PR NUMBER to continue receiving JOB ORDER -
						</p>

					<?php
				}
			}

		?>

	</form>

</div>


<script>

	const msg = "<?php echo $post_message; ?>";

	window.onload = () =>{
		if(msg == 200) {
			alert('Notification\nOrder was received into designated store successfully');
			window.parent.location.reload(true);
		}
	}

</script>