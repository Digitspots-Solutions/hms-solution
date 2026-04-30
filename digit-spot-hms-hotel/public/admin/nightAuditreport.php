<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
include "../../includes/pos_common_data.php";
include "../../includes/hotel_profile.php";

if(isset($_POST['billPostdate'])) {
	$bill_post_date = $_POST['billPostdate'];
	$audit_done_date = date('Y-m-d',strtotime($_POST['billPostdate'] . '+1 days'));
} else {
	//$bill_post_date = date('Y-m-d',strtotime($server_get_auditdate . '+1 days'));
	$bill_post_date = $server_get_auditdate;
	$audit_done_date = $server_get_auditdate;
}

?>
<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>

<title><?php echo SOFTWARE_NAME; ?> | Back Office</title>

<div class="top-pull-5 right-pull-15 bottom-pull-10 left-pull-15" id="section-to-print">

	<div align="center">
		<div class="cs-width-150 bottom-push-10 noscroll">
			<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
		</div>
	</div>

	<h3 class="large nobold default-text-font-bold alignct">Night Audit Summary Report for <?php echo date('d/m/Y',strtotime($bill_post_date)); ?></h3>
	<h4 class="large nobold alignct">Current Time: <b class="nobold default-text-font-bold"><?php echo date('d/m/Y',strtotime($server_get_date)).' '.$server_get_time; ?></b></h4>
	<h4 class="large nobold alignct">Audit Done Till: <b class="nobold default-text-font-bold"><?php echo date('d/m/Y',strtotime($audit_done_date)); ?></b></h4>

	<div class="cs-height-10"></div>

	<?php

		$tbSql = "SELECT SUM(room_amount) AS 'rmAmt', SUM(tax_amount) AS 'taxAmt', SUM(consumption_tax_amount) AS 'ctaxAmt', SUM(service_charge) AS 'scAmt', SUM(discount_amount) AS 'dsAmt' FROM daily_invoice_charges_tbl WHERE queryname";

		$posSql = "SELECT SUM(bill_amount) AS 'totalPosAmt' FROM pos_payment_tbl WHERE queryname";
		$pySql = "SELECT SUM(amount) AS 'cPayAmt' FROM transaction_payment_tbl WHERE queryname";
		$rbtSql = "SELECT SUM(amount) AS 'cRebateAmt' FROM transaction_payment_tbl WHERE queryname";
		

		$inhouse_dayuse_guest_dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE checkin_date='{$bill_post_date}' AND checkout_date='{$bill_post_date}' AND status IN('CheckedOut')";
		$inhouse_dayuse_guest_query = mysqli_query($mysqli,$inhouse_dayuse_guest_dataset);
		
		if(@mysqli_num_rows($inhouse_dayuse_guest_query) == true) {
			
			$dayuse_bookings = "";
			
			while($dayuse_data = @mysqli_fetch_array($inhouse_dayuse_guest_query,MYSQLI_ASSOC)) {
				$dayuse_bookings .= "'".$dayuse_data['customerid']."',";
			}

			$dayuse_bookings = substr_replace($dayuse_bookings,'',-1);

			$inhouse_guest_dataset = "SELECT * FROM daily_invoice_charges_tbl WHERE bill_date='{$bill_post_date}' AND room_amount > 0 AND ischarged=1 AND charge='yes' AND deletedata=0 AND customerid NOT IN({$dayuse_bookings}) AND invoice_number NOT IN('EARLYCHECKIN','LATECHECKOUT')";

		} else {
			$inhouse_guest_dataset = "SELECT * FROM daily_invoice_charges_tbl WHERE bill_date='{$bill_post_date}' AND room_amount > 0 AND ischarged=1 AND charge='yes' AND deletedata=0 AND invoice_number NOT IN('EARLYCHECKIN','LATECHECKOUT')";
		}

		$inhouse_guest_query = mysqli_query($mysqli,$inhouse_guest_dataset);
		$inhouse_guest_rows = @mysqli_num_rows($inhouse_guest_query);

	?>

	<h4 class="large nobold default-text-font-bold">In House Guests (<?php echo $inhouse_guest_rows; ?>)</h4>
	<div class="cs-height-10"></div>

	<table cellpadding="3" cellspacing="0" border="1">
		<tr>
			<td class="default-text-font-bold" align="center">Booking No.</td>
			<td class="default-text-font-bold" align="center">Room No.</td>
			<td class="default-text-font-bold" align="center">Guest Name</td>
			<td class="default-text-font-bold" align="center">Opening Bal.</td>
			<td class="default-text-font-bold" align="center">Room Charged</td>
			<td class="default-text-font-bold" align="center">Discount Amount</td>
			<td class="default-text-font-bold" align="center">Tax Charge</td>
			<td class="default-text-font-bold" align="center">Pos Stores</td>
			<td class="default-text-font-bold" align="center">Tele phone</td>
			<td class="default-text-font-bold" align="center">Misc. Charge</td>
			<td class="default-text-font-bold" align="center">Paid Out</td>
			<td class="default-text-font-bold" align="center">Sub-Total</td>
			<td class="default-text-font-bold" align="center">F.I.O Cash</td>
			<td class="default-text-font-bold" align="center">F&B Cash</td>
			<td class="default-text-font-bold" align="center">C/L Payment</td>
			<td class="default-text-font-bold" align="center">C/L Transfer</td>
			<td class="default-text-font-bold" align="center">Rebate Amount</td>
			<td class="default-text-font-bold" align="center">Transfer Amount</td>
			<td class="default-text-font-bold" align="center">Closing Bal.</td>
		</tr>

		<?php
			
			if($inhouse_guest_rows == true) {

				$roomtype = ""; $room = ""; $salutation = ""; $roomlist = ""; $booking_type = ""; $taggy = "";
				$amount = 0; $paid_amount = 0; $balance_amount = 0;
				$guest_name = ""; $room_tariff = ""; $rebate_amount = 0;
				$transfer_amount = 0; $tax_charge = 0; $opening_bal = ""; $closing_bal = 0;
				$pos_store_charge = 0; $subtotal = 0; $fbcash = 0; $fiocash = 0;
				$cl_payment = 0; $cl_transfer = 0; $telephone = 0; $paidout = 0;
				$misc = 0; $discount = 0; $rebate_amount = 0;

				$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
				$total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0; $total10 = 0; $total11 = 0;

				while($data = @mysqli_fetch_array($inhouse_guest_query,MYSQLI_ASSOC)) {
					
					$room = idget_data($tbL56,$data['roomid'],'roomprefix');
					$room .= idget_data($tbL56,$data['roomid'],'roomnumber');

					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					$bill_to = idget_fdata($tbL130,'booking_number',$data['booking_number'],'bill_to');

					$guest_name = idget_data($tbL102,$data['customerid'],'fname').' ';
					$guest_name .= idget_data($tbL102,$data['customerid'],'lname');

					if($booking_type == 'corporate' && $bill_to > 0) {
						$guest_name .= ' ('.idget_data($tbL58,$bill_to,'code').')';
					}
					
					$select_fx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date NOT IN('{$bill_post_date}')";
					$sql_fx = str_replace('queryname',$select_fx,$tbSql);
					$query_fx = mysqli_query($mysqli,$sql_fx);
					$amt_fx = @mysqli_fetch_array($query_fx,MYSQLI_ASSOC);

					$opening_bal = ($amt_fx['rmAmt'] + $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt']) - $amt_fx['dsAmt'];

					/*$select_sx = "roomid='{$data['roomid']}' AND charge='Yes' AND ischarged=1 AND bill_date='{$bill_post_date}'";
					$sql_sx = str_replace('queryname',$select_sx,$tbSql);
					$query_sx = mysqli_query($mysqli,$sql_sx);
					$amt_sx = @mysqli_fetch_array($query_sx,MYSQLI_ASSOC);*/

					$room_tariff = $data['room_amount']; $discount = $data['discount_amount'];
					$tax_charge = $data['tax_amount'] + $data['service_charge'] + $data['consumption_tax_amount'];

					$select_tx = "roomid='{$data['roomid']}' AND payment IN('Pending','Completed') AND datelogged='{$bill_post_date}'";
					$sql_tx = str_replace('queryname',$select_tx,$posSql);
					$query_tx = mysqli_query($mysqli,$sql_tx);
					$amt_tx = @mysqli_fetch_array($query_tx,MYSQLI_ASSOC);

					$pos_store_charge = $amt_tx['totalPosAmt'];
					$subtotal = (($room_tariff + $tax_charge) - $discount) + $pos_store_charge;

					$select_ftx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='credit' AND datelogged='{$bill_post_date}'";
					$sql_ftx = str_replace('queryname',$select_ftx,$pySql);
					$query_ftx = mysqli_query($mysqli,$sql_ftx);
					$amt_ftx = @mysqli_fetch_array($query_ftx,MYSQLI_ASSOC);

					$select_ffx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='rebate' AND datelogged='{$bill_post_date}'";
					$sql_ffx = str_replace('queryname',$select_ffx,$rbtSql);
					$query_ffx = mysqli_query($mysqli,$sql_ffx);
					$amt_ffx = @mysqli_fetch_array($query_ffx,MYSQLI_ASSOC);

					$fiocash = $amt_ftx['cPayAmt'];
					$rebate_amount = $amt_ffx['cRebateAmt'];
					$closing_bal = $subtotal + $opening_bal;

					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					
					if($booking_type == 'complimentary') {
						$color_code = "light-red-font";
						$taggy = " CP";
					} else {
						$color_code = "dark-black-font";
						$total1 = $total1 + $opening_bal; $total2 = $total2 + $room_tariff;
						$total3 = $total3 + $discount; $total4 = $total4 + $tax_charge;
						$total5 = $total5 + $pos_store_charge; $total6 = $total6 + $telephone;
						$total7 = $total7 + $misc; $total8 = $total8 + $paidout;
						$total9 = $total9 + $subtotal; $total10 = $total10 + $fiocash;
						$total11 = $total11 + $closing_bal;
						$taggy = "";
					}

					?>
						<tr>
							<td align="center"><a href="javascript:void(0)" onclick="jsxView('<?php echo $data['booking_number']; ?>')" class="blue-font"><?php echo $data['booking_number']; ?></a></td>
							<td align="center"><?php echo $room; ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo $guest_name.$taggy; ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($opening_bal,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($room_tariff,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($discount,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($tax_charge,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($pos_store_charge,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($telephone,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($misc,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($paidout,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($subtotal,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($fiocash,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($fbcash,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($cl_payment,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($cl_transfer,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($rebate_amount,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($transfer_amount,2); ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo number_format($closing_bal,2); ?></td>
						</tr>
					<?php
				}

				?>
					<tr>
						<td align="center">&nbsp;</td>
						<td align="center">Total</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total1,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total2,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total3,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total4,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total5,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total6,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total7,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total8,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total9,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total10,2); ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total11,2); ?></td>
					</tr>
				<?php
			}
		?>

	</table>

	<?php

		$checkout_guest_dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE status='CheckedOut' AND checkout_date='{$bill_post_date}'";

		$checkout_guest_query = mysqli_query($mysqli,$checkout_guest_dataset);
		$checkout_guest_rows = @mysqli_num_rows($checkout_guest_query);

	?>

	<div class="cs-height-50"></div>

	<h4 class="large nobold default-text-font-bold">Checkout Guests (<?php echo $checkout_guest_rows; ?>)</h4>
	<div class="cs-height-10"></div>

	<table cellpadding="3" cellspacing="0" border="1">
		<tr>
			<td class="default-text-font-bold" align="center">Booking No.</td>
			<td class="default-text-font-bold" align="center">Room No.</td>
			<td class="default-text-font-bold" align="center">Guest Name</td>
			<td class="default-text-font-bold" align="center">Opening Bal.</td>
			<td class="default-text-font-bold" align="center">Room Charged</td>
			<td class="default-text-font-bold" align="center">Discount Amount</td>
			<td class="default-text-font-bold" align="center">Tax Charge</td>
			<td class="default-text-font-bold" align="center">Pos Stores</td>
			<td class="default-text-font-bold" align="center">Tele phone</td>
			<td class="default-text-font-bold" align="center">Misc. Charge</td>
			<td class="default-text-font-bold" align="center">Paid Out</td>
			<td class="default-text-font-bold" align="center">Sub-Total</td>
			<td class="default-text-font-bold" align="center">F.I.O Cash</td>
			<td class="default-text-font-bold" align="center">F&B Cash</td>
			<td class="default-text-font-bold" align="center">C/L Payment</td>
			<td class="default-text-font-bold" align="center">C/L Transfer</td>
			<td class="default-text-font-bold" align="center">Rebate Amount</td>
			<td class="default-text-font-bold" align="center">Transfer Amount</td>
			<td class="default-text-font-bold" align="center">Closing Bal.</td>
		</tr>

		<?php

			if($checkout_guest_rows == true) {

				$roomtype = ""; $room = ""; $salutation = ""; $roomlist = ""; $booking_type = ""; $taggy = "";
				$amount = 0; $paid_amount = 0; $balance_amount = 0;
				$guest_name = ""; $room_tariff = ""; $rebate_amount = 0;
				$transfer_amount = 0; $tax_charge = 0; $opening_bal = 0; $closing_bal = 0;
				$pos_store_charge = 0; $subtotal = 0; $fbcash = 0; $fiocash = 0;
				$cl_payment = 0; $cl_transfer = 0; $telephone = 0; $paidout = 0;
				$misc = 0; $discount = 0; $rebate_amount = 0;

				$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
				$total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0; $total10 = 0; $total11 = 0;

				while($data = @mysqli_fetch_array($checkout_guest_query,MYSQLI_ASSOC)) {
					
					$room = idget_data($tbL56,$data['roomid'],'roomprefix');
					$room .= idget_data($tbL56,$data['roomid'],'roomnumber');

					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					$bill_to = idget_fdata($tbL130,'booking_number',$data['booking_number'],'bill_to');

					$guest_name = idget_data($tbL102,$data['customerid'],'fname').' ';
					$guest_name .= idget_data($tbL102,$data['customerid'],'lname');

					if($booking_type == 'corporate' && $bill_to > 0) {
						$guest_name .= ' ('.idget_data($tbL58,$bill_to,'code').')';
					}
					
					$select_fx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date NOT IN('{$bill_post_date}')";
					$sql_fx = str_replace('queryname',$select_fx,$tbSql);
					$query_fx = mysqli_query($mysqli,$sql_fx);
					$amt_fx = @mysqli_fetch_array($query_fx,MYSQLI_ASSOC);

					$opening_bal = ($amt_fx['rmAmt'] + $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt']) - $amt_fx['dsAmt'];

					$select_sx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date='{$bill_post_date}'";
					$sql_sx = str_replace('queryname',$select_sx,$tbSql);
					$query_sx = mysqli_query($mysqli,$sql_sx);
					$amt_sx = @mysqli_fetch_array($query_sx,MYSQLI_ASSOC);

					$room_tariff = $amt_sx['rmAmt']; $discount = $amt_sx['dsAmt'];
					$tax_charge = $amt_sx['taxAmt'] + $amt_sx['scAmt'] + $amt_sx['ctaxAmt'];

					$select_tx = "roomid='{$data['roomid']}' AND payment IN('Pending','Completed') AND datelogged='{$bill_post_date}'";
					$sql_tx = str_replace('queryname',$select_tx,$posSql);
					$query_tx = mysqli_query($mysqli,$sql_tx);
					$amt_tx = @mysqli_fetch_array($query_tx,MYSQLI_ASSOC);

					$pos_store_charge = $amt_tx['totalPosAmt'];
					$subtotal = (($room_tariff + $tax_charge) - $discount) + $pos_store_charge;

					$select_ftx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='credit' AND datelogged='{$bill_post_date}'";
					$sql_ftx = str_replace('queryname',$select_ftx,$pySql);
					$query_ftx = mysqli_query($mysqli,$sql_ftx);
					$amt_ftx = @mysqli_fetch_array($query_ftx,MYSQLI_ASSOC);

					$select_ffx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='rebate' AND datelogged='{$bill_post_date}'";
					$sql_ffx = str_replace('queryname',$select_ffx,$rbtSql);
					$query_ffx = mysqli_query($mysqli,$sql_ffx);
					$amt_ffx = @mysqli_fetch_array($query_ffx,MYSQLI_ASSOC);

					$fiocash = $amt_ftx['cPayAmt'];
					$rebate_amount = $amt_ffx['cRebateAmt'];
					$closing_bal = $subtotal + $opening_bal;


					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					
					if($booking_type == 'complimentary') {
						$color_code = "light-red-font";
						$taggy = " CP";
					} else {
						$color_code = "dark-black-font";
						$total1 = $total1 + $opening_bal; $total2 = $total2 + $room_tariff;
						$total3 = $total3 + $discount; $total4 = $total4 + $tax_charge;
						$total5 = $total5 + $pos_store_charge; $total6 = $total6 + $telephone;
						$total7 = $total7 + $misc; $total8 = $total8 + $paidout;
						$total9 = $total9 + $subtotal; $total10 = $total10 + $fiocash;
						$total11 = $total11 + $closing_bal;
						$taggy = "";
					}

					?>
						<tr>
							<td align="center"><a href="javascript:void(0)" onclick="jsxView('<?php echo $data['booking_number']; ?>')" class="blue-font"><?php echo $data['booking_number']; ?></a></td>
							<td align="center"><?php echo $room; ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo $guest_name.$taggy; ?></td>
							<td align="center"><?php echo number_format($opening_bal,2); ?></td>
							<td align="center"><?php echo number_format($room_tariff,2); ?></td>
							<td align="center"><?php echo number_format($discount,2); ?></td>
							<td align="center"><?php echo number_format($tax_charge,2); ?></td>
							<td align="center"><?php echo number_format($pos_store_charge,2); ?></td>
							<td align="center"><?php echo number_format($telephone,2); ?></td>
							<td align="center"><?php echo number_format($misc,2); ?></td>
							<td align="center"><?php echo number_format($paidout,2); ?></td>
							<td align="center"><?php echo number_format($subtotal,2); ?></td>
							<td align="center"><?php echo number_format($fiocash,2); ?></td>
							<td align="center"><?php echo number_format($fbcash,2); ?></td>
							<td align="center"><?php echo number_format($cl_payment,2); ?></td>
							<td align="center"><?php echo number_format($cl_transfer,2); ?></td>
							<td align="center"><?php echo number_format($rebate_amount,2); ?></td>
							<td align="center"><?php echo number_format($transfer_amount,2); ?></td>
							<td align="center"><?php echo number_format($closing_bal,2); ?></td>
						</tr>
					<?php
				}

				?>
					<tr>
						<td align="center">&nbsp;</td>
						<td align="center">Total</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total1,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total2,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total3,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total4,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total5,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total6,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total7,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total8,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total9,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total10,2); ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total11,2); ?></td>
					</tr>
				<?php
			}

		?>

	</table>

	<?php

		$noshow_guest_dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE status='No Show' AND (checkin_date='{$bill_post_date}' OR checkout_date='{$bill_post_date}')";

		$noshow_guest_query = mysqli_query($mysqli,$noshow_guest_dataset);
		$noshow_guest_rows = @mysqli_num_rows($noshow_guest_query);

	?>

	<div class="cs-height-50"></div>

	<h4 class="large nobold default-text-font-bold">Noshow Guests (<?php echo $noshow_guest_rows; ?>)</h4>
	<div class="cs-height-10"></div>

	<table cellpadding="3" cellspacing="0" border="1">
		<tr>
			<td class="default-text-font-bold" align="center">Booking No.</td>
			<td class="default-text-font-bold" align="center">Room No.</td>
			<td class="default-text-font-bold" align="center">Guest Name</td>
			<td class="default-text-font-bold" align="center">Opening Bal.</td>
			<td class="default-text-font-bold" align="center">Room Charged</td>
			<td class="default-text-font-bold" align="center">Discount Amount</td>
			<td class="default-text-font-bold" align="center">Tax Charge</td>
			<td class="default-text-font-bold" align="center">Pos Stores</td>
			<td class="default-text-font-bold" align="center">Tele phone</td>
			<td class="default-text-font-bold" align="center">Misc. Charge</td>
			<td class="default-text-font-bold" align="center">Paid Out</td>
			<td class="default-text-font-bold" align="center">Sub-Total</td>
			<td class="default-text-font-bold" align="center">F.I.O Cash</td>
			<td class="default-text-font-bold" align="center">F&B Cash</td>
			<td class="default-text-font-bold" align="center">C/L Payment</td>
			<td class="default-text-font-bold" align="center">C/L Transfer</td>
			<td class="default-text-font-bold" align="center">Rebate Amount</td>
			<td class="default-text-font-bold" align="center">Transfer Amount</td>
			<td class="default-text-font-bold" align="center">Closing Bal.</td>
		</tr>
		
		<?php
			
			if($noshow_guest_rows == true) {

				$roomtype = ""; $room = ""; $salutation = ""; $roomlist = ""; $booking_type = ""; $taggy = "";
				$amount = 0; $paid_amount = 0; $balance_amount = 0;
				$guest_name = ""; $room_tariff = ""; $rebate_amount = 0;
				$transfer_amount = 0; $tax_charge = 0; $opening_bal = 0; $closing_bal = 0;
				$pos_store_charge = 0; $subtotal = 0; $fbcash = 0; $fiocash = 0;
				$cl_payment = 0; $cl_transfer = 0; $telephone = 0; $paidout = 0;
				$misc = 0; $discount = 0; $rebate_amount = 0;

				$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
				$total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0; $total10 = 0; $total11 = 0;

				while($data = @mysqli_fetch_array($noshow_guest_query,MYSQLI_ASSOC)) {
					
					$room = idget_data($tbL56,$data['roomid'],'roomprefix');
					$room .= idget_data($tbL56,$data['roomid'],'roomnumber');

					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					$bill_to = idget_fdata($tbL130,'booking_number',$data['booking_number'],'bill_to');

					$guest_name = idget_data($tbL102,$data['customerid'],'fname').' ';
					$guest_name .= idget_data($tbL102,$data['customerid'],'lname');

					if($booking_type == 'corporate' && $bill_to > 0) {
						$guest_name .= ' ('.idget_data($tbL58,$bill_to,'code').')';
					}
					
					$select_fx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date NOT IN('{$bill_post_date}')";
					$sql_fx = str_replace('queryname',$select_fx,$tbSql);
					$query_fx = mysqli_query($mysqli,$sql_fx);
					$amt_fx = @mysqli_fetch_array($query_fx,MYSQLI_ASSOC);

					$opening_bal = ($amt_fx['rmAmt'] + $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt']) - $amt_fx['dsAmt'];

					$select_sx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date='{$bill_post_date}'";
					$sql_sx = str_replace('queryname',$select_sx,$tbSql);
					$query_sx = mysqli_query($mysqli,$sql_sx);
					$amt_sx = @mysqli_fetch_array($query_sx,MYSQLI_ASSOC);

					$room_tariff = $amt_sx['rmAmt']; $discount = $amt_sx['dsAmt'];
					$tax_charge = $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt'];

					$select_tx = "roomid='{$data['roomid']}' AND payment IN('Pending','Completed') AND datelogged='{$bill_post_date}'";
					$sql_tx = str_replace('queryname',$select_tx,$posSql);
					$query_tx = mysqli_query($mysqli,$sql_tx);
					$amt_tx = @mysqli_fetch_array($query_tx,MYSQLI_ASSOC);

					$pos_store_charge = $amt_tx['totalPosAmt'];
					$subtotal = (($room_tariff + $tax_charge) - $discount) + $pos_store_charge;

					$select_ftx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='credit' AND datelogged='{$bill_post_date}'";
					$sql_ftx = str_replace('queryname',$select_ftx,$pySql);
					$query_ftx = mysqli_query($mysqli,$sql_ftx);
					$amt_ftx = @mysqli_fetch_array($query_ftx,MYSQLI_ASSOC);

					$select_ffx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='rebate' AND datelogged='{$bill_post_date}'";
					$sql_ffx = str_replace('queryname',$select_ffx,$rbtSql);
					$query_ffx = mysqli_query($mysqli,$sql_ffx);
					$amt_ffx = @mysqli_fetch_array($query_ffx,MYSQLI_ASSOC);

					$fiocash = $amt_ftx['cPayAmt'];
					$rebate_amount = $amt_ffx['cRebateAmt'];
					$closing_bal = $subtotal + $opening_bal;


					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					
					if($booking_type == 'complimentary') {
						$color_code = "light-red-font";
						$taggy = " CP";
					} else {
						$color_code = "dark-black-font";
						$total1 = $total1 + $opening_bal; $total2 = $total2 + $room_tariff;
						$total3 = $total3 + $discount; $total4 = $total4 + $tax_charge;
						$total5 = $total5 + $pos_store_charge; $total6 = $total6 + $telephone;
						$total7 = $total7 + $misc; $total8 = $total8 + $paidout;
						$total9 = $total9 + $subtotal; $total10 = $total10 + $fiocash;
						$total11 = $total11 + $closing_bal;
						$taggy = "";
					}

					?>
						<tr>
							<td align="center"><a href="javascript:void(0)" onclick="jsxView('<?php echo $data['booking_number']; ?>')" class="blue-font"><?php echo $data['booking_number']; ?></a></td>
							<td align="center"><?php echo $room; ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo $guest_name.$taggy; ?></td>
							<td align="center"><?php echo number_format($opening_bal,2); ?></td>
							<td align="center"><?php echo number_format($room_tariff,2); ?></td>
							<td align="center"><?php echo number_format($discount,2); ?></td>
							<td align="center"><?php echo number_format($tax_charge,2); ?></td>
							<td align="center"><?php echo number_format($pos_store_charge,2); ?></td>
							<td align="center"><?php echo number_format($telephone,2); ?></td>
							<td align="center"><?php echo number_format($misc,2); ?></td>
							<td align="center"><?php echo number_format($paidout,2); ?></td>
							<td align="center"><?php echo number_format($subtotal,2); ?></td>
							<td align="center"><?php echo number_format($fiocash,2); ?></td>
							<td align="center"><?php echo number_format($fbcash,2); ?></td>
							<td align="center"><?php echo number_format($cl_payment,2); ?></td>
							<td align="center"><?php echo number_format($cl_transfer,2); ?></td>
							<td align="center"><?php echo number_format($rebate_amount,2); ?></td>
							<td align="center"><?php echo number_format($transfer_amount,2); ?></td>
							<td align="center"><?php echo number_format($closing_bal,2); ?></td>
						</tr>
					<?php
				}

				?>
					<tr>
						<td align="center">&nbsp;</td>
						<td align="center">Total</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total1,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total2,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total3,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total4,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total5,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total6,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total7,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total8,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total9,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total10,2); ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total11,2); ?></td>
					</tr>
				<?php
			}

		?>

	</table>
		
	<div class="cs-height-50"></div>

	<?php

		$ccSql = "SELECT SUM(cancellation_charges) AS 'ccAmt' FROM guest_occupancy_detail_tbl WHERE cancel_date='{$bill_post_date}'";
		$query_cc = mysqli_query($mysqli,$ccSql);
		$ccamt = @mysqli_fetch_array($query_cc,MYSQLI_ASSOC);

		$cancel_guest_dataset = "SELECT * FROM guest_occupancy_detail_tbl WHERE status='Cancelled' AND cancel_date='{$bill_post_date}' AND reservation='Reserving'";

		$cancel_guest_query = mysqli_query($mysqli,$cancel_guest_dataset);
		$cancel_guest_rows = @mysqli_num_rows($cancel_guest_query);

	?>

	<div class="cs-height-30"></div>

	<h4 class="large nobold default-text-font-bold">Cancelled Reservations (<?php echo $cancel_guest_rows; ?>)</h4>
	<div class="cs-height-10"></div>

	<table cellpadding="3" cellspacing="0" border="1">
		<tr>
			<td class="default-text-font-bold" align="center">Booking No.</td>
			<td class="default-text-font-bold" align="center">Room No.</td>
			<td class="default-text-font-bold" align="center">Guest Name</td>
			<td class="default-text-font-bold" align="center">Opening Bal.</td>
			<td class="default-text-font-bold" align="center">Room Charged</td>
			<td class="default-text-font-bold" align="center">Discount Amount</td>
			<td class="default-text-font-bold" align="center">Tax Charge</td>
			<td class="default-text-font-bold" align="center">Pos Stores</td>
			<td class="default-text-font-bold" align="center">Tele phone</td>
			<td class="default-text-font-bold" align="center">Misc. Charge</td>
			<td class="default-text-font-bold" align="center">Paid Out</td>
			<td class="default-text-font-bold" align="center">Sub-Total</td>
			<td class="default-text-font-bold" align="center">F.I.O Cash</td>
			<td class="default-text-font-bold" align="center">F&B Cash</td>
			<td class="default-text-font-bold" align="center">C/L Payment</td>
			<td class="default-text-font-bold" align="center">C/L Transfer</td>
			<td class="default-text-font-bold" align="center">Rebate Amount</td>
			<td class="default-text-font-bold" align="center">Transfer Amount</td>
			<td class="default-text-font-bold" align="center">Closing Bal.</td>
		</tr>
		
		<?php
			
			if($cancel_guest_rows == true) {

				$roomtype = ""; $room = ""; $salutation = ""; $roomlist = ""; $booking_type = ""; $taggy = "";
				$amount = 0; $paid_amount = 0; $balance_amount = 0;
				$guest_name = ""; $room_tariff = ""; $rebate_amount = 0;
				$transfer_amount = 0; $tax_charge = 0; $opening_bal = 0; $closing_bal = 0;
				$pos_store_charge = 0; $subtotal = 0; $fbcash = 0; $fiocash = 0;
				$cl_payment = 0; $cl_transfer = 0; $telephone = 0; $paidout = 0;
				$misc = 0; $discount = 0; $rebate_amount = 0;

				$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
				$total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0; $total10 = 0; $total11 = 0;

				while($data = @mysqli_fetch_array($cancel_guest_query,MYSQLI_ASSOC)) {
					
					$room = idget_data($tbL56,$data['roomid'],'roomprefix');
					$room .= idget_data($tbL56,$data['roomid'],'roomnumber');

					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					$bill_to = idget_fdata($tbL130,'booking_number',$data['booking_number'],'bill_to');

					$guest_name = idget_data($tbL102,$data['customerid'],'fname').' ';
					$guest_name .= idget_data($tbL102,$data['customerid'],'lname');

					if($booking_type == 'corporate' && $bill_to > 0) {
						$guest_name .= ' ('.idget_data($tbL58,$bill_to,'code').')';
					}
					
					$select_fx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date NOT IN('{$bill_post_date}')";
					$sql_fx = str_replace('queryname',$select_fx,$tbSql);
					$query_fx = mysqli_query($mysqli,$sql_fx);
					$amt_fx = @mysqli_fetch_array($query_fx,MYSQLI_ASSOC);

					$opening_bal = ($amt_fx['rmAmt'] + $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt']) - $amt_fx['dsAmt'];

					$select_sx = "booking_number='{$data['booking_number']}' AND roomid='{$data['roomid']}' AND charge='yes' AND ischarged=1 AND bill_date='{$bill_post_date}'";
					$sql_sx = str_replace('queryname',$select_sx,$tbSql);
					$query_sx = mysqli_query($mysqli,$sql_sx);
					$amt_sx = @mysqli_fetch_array($query_sx,MYSQLI_ASSOC);

					$room_tariff = $amt_sx['rmAmt']; $discount = $amt_sx['dsAmt'];
					$tax_charge = $amt_fx['taxAmt'] + $amt_fx['scAmt'] + $amt_fx['ctaxAmt'];

					$select_tx = "roomid='{$data['roomid']}' AND payment IN('Pending','Completed') AND datelogged='{$bill_post_date}'";
					$sql_tx = str_replace('queryname',$select_tx,$posSql);
					$query_tx = mysqli_query($mysqli,$sql_tx);
					$amt_tx = @mysqli_fetch_array($query_tx,MYSQLI_ASSOC);

					$pos_store_charge = $amt_tx['totalPosAmt'];
					$subtotal = (($room_tariff + $tax_charge) - $discount) + $pos_store_charge;

					$select_ftx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='credit' AND datelogged='{$bill_post_date}'";
					$sql_ftx = str_replace('queryname',$select_ftx,$pySql);
					$query_ftx = mysqli_query($mysqli,$sql_ftx);
					$amt_ftx = @mysqli_fetch_array($query_ftx,MYSQLI_ASSOC);

					$select_ffx = "booking_number='{$data['booking_number']}' AND customerid='{$data['customerid']}' AND transaction_type='rebate' AND datelogged='{$bill_post_date}'";
					$sql_ffx = str_replace('queryname',$select_ffx,$rbtSql);
					$query_ffx = mysqli_query($mysqli,$sql_ffx);
					$amt_ffx = @mysqli_fetch_array($query_ffx,MYSQLI_ASSOC);

					$fiocash = $amt_ftx['cPayAmt'];
					$rebate_amount = $amt_ffx['cRebateAmt'];
					$closing_bal = $subtotal + $opening_bal;


					$booking_type = idget_fdata($tbL130,'booking_number',$data['booking_number'],'booking_type');
					
					if($booking_type == 'complimentary') {
						$color_code = "light-red-font";
						$taggy = " CP";
					} else {
						$color_code = "dark-black-font";
						$total1 = $total1 + $opening_bal; $total2 = $total2 + $room_tariff;
						$total3 = $total3 + $discount; $total4 = $total4 + $tax_charge;
						$total5 = $total5 + $pos_store_charge; $total6 = $total6 + $telephone;
						$total7 = $total7 + $misc; $total8 = $total8 + $paidout;
						$total9 = $total9 + $subtotal; $total10 = $total10 + $fiocash;
						$total11 = $total11 + $closing_bal;
						$taggy = "";
					}

					?>
						<tr>
							<td align="center"><a href="javascript:void(0)" onclick="jsxView('<?php echo $data['booking_number']; ?>')" class="blue-font"><?php echo $data['booking_number']; ?></a></td>
							<td align="center"><?php echo $room; ?></td>
							<td align="center" class="<?php echo $color_code; ?>"><?php echo $guest_name.$taggy; ?></td>
							<td align="center"><?php echo number_format($opening_bal,2); ?></td>
							<td align="center"><?php echo number_format($room_tariff,2); ?></td>
							<td align="center"><?php echo number_format($discount,2); ?></td>
							<td align="center"><?php echo number_format($tax_charge,2); ?></td>
							<td align="center"><?php echo number_format($pos_store_charge,2); ?></td>
							<td align="center"><?php echo number_format($telephone,2); ?></td>
							<td align="center"><?php echo number_format($misc,2); ?></td>
							<td align="center"><?php echo number_format($paidout,2); ?></td>
							<td align="center"><?php echo number_format($subtotal,2); ?></td>
							<td align="center"><?php echo number_format($fiocash,2); ?></td>
							<td align="center"><?php echo number_format($fbcash,2); ?></td>
							<td align="center"><?php echo number_format($cl_payment,2); ?></td>
							<td align="center"><?php echo number_format($cl_transfer,2); ?></td>
							<td align="center"><?php echo number_format($rebate_amount,2); ?></td>
							<td align="center"><?php echo number_format($transfer_amount,2); ?></td>
							<td align="center"><?php echo number_format($closing_bal,2); ?></td>
						</tr>
					<?php
				}

				?>
					<tr>
						<td align="center">&nbsp;</td>
						<td align="center">Total</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total1,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total2,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total3,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total4,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total5,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total6,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total7,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total8,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total9,2); ?></td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total10,2); ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center" class="default-text-font-bold"><?php echo number_format($total11,2); ?></td>
					</tr>
				<?php
			}

		?>

	</table>
		
	<div class="cs-height-50"></div>

	<div class="cs-width-300">
		<h4 class="large nobold default-text-font-bold">Cancellation Revenue</h4>
		<div class="cs-height-10"></div>

		<table cellpadding="3" cellspacing="0" border="1">
			<tr>
				<td align="left">&#8358; <?php echo number_format($ccamt['ccAmt'],2); ?></td>
			</tr>
		</table>
	</div>

	<div class="cs-height-50"></div>

	<?php

		$posaSql = "SELECT SUM(bill_amount) AS 'totalSalesAmt' FROM pos_payment_tbl";

		$ctgSql = "SELECT program_id FROM pos_store_category_tbl WHERE deletedata=0 GROUP BY program_id";
		$query_ctg = mysqli_query($mysqli,$ctgSql);
		$row_ctg = @mysqli_num_rows($query_ctg);

		$total4PoSummary = 0; $total4PoSViseSummary = 0;
	?>

	<div class="ln-display-box float-left cs-width-300">
		<h4 class="large nobold default-text-font-bold">POS Amount Summary</h4>
		<div class="cs-height-10"></div>

		<table cellpadding="3" cellspacing="0" border="1">
			<tr>
				<td class="default-text-font-bold" align="left">Transaction Type</td>
				<td class="default-text-font-bold" align="left">Amount</td>
			</tr>

			<?php
				if($row_ctg == true) {
					
					$category_name = ""; $eachSum = "";
					$getSum = 0; $sumfx = "";

					while($ctg = @mysqli_fetch_array($query_ctg,MYSQLI_ASSOC)) {
						
						$category_name = arrayget_key($outlet_category_type,$ctg['program_id']);

						$sumfx = "SELECT SUM(amount) AS 'tsales' FROM pos_orders_tbl WHERE main_category={$ctg['program_id']} AND status='Completed' AND datelogged='{$bill_post_date}' AND isreversed=0 AND billtype NOT IN(3)";
						$queryfx = mysqli_query($mysqli,$sumfx);
						
						$gsx = @mysqli_fetch_array($queryfx,MYSQLI_ASSOC);
						
						$total4PoSummary = $total4PoSummary + $gsx['tsales'];

						?>
							<tr>
								<td align="left"><?php echo $category_name; ?></td>
								<td align="left">&#8358; <?php echo number_format($gsx['tsales'],2); ?></td>
							</tr>
						<?php
					}
				}
			?>

			<tr>
				<td align="left">Total</td>
				<td class="default-text-font-bold" align="left">&#8358; <?php echo number_format($total4PoSummary,2); ?></td>
			</tr>
		</table>
	</div>

	<div class="ln-display-box float-right cs-width-300">
		<h4 class="large nobold default-text-font-bold">POS Transaction Wise Summary</h4>
		<div class="cs-height-10"></div>

		<table cellpadding="3" cellspacing="0" border="1">
			<tr>
				<td class="default-text-font-bold" align="left">Transaction Type</td>
				<td class="default-text-font-bold" align="left">Amount</td>
			</tr>

			<?php
				if(isset($bill_type) && is_array($bill_type)) {
					
					$totalBillTypeSales = 0;
					$btp = "";

					foreach($bill_type as $key => $val) {
						
						$typ = $posaSql." WHERE billtype={$key} AND datelogged='{$bill_post_date}' AND isreversed=0";
						$query_btp = mysqli_query($mysqli,$typ);
						$btp = @mysqli_fetch_array($query_btp,MYSQLI_ASSOC);

						$totalBillTypeSales = $btp['totalSalesAmt'];

						?>
							<tr>
								<td align="left"><?php echo $val; ?></td>
								<td align="left">&#8358; <?php echo number_format($totalBillTypeSales,2); ?></td>
							</tr>
						<?php

						$typ = "";

						$total4PoSViseSummary = $total4PoSViseSummary + $totalBillTypeSales;
					}
				}
			?>

			<tr>
				<td align="left">Total</td>
				<td class="default-text-font-bold" align="left">&#8358; <?php echo number_format($total4PoSViseSummary,2); ?></td>
			</tr>
		</table>
	</div>

	<div class="block-element new-line-space">
	</div>

</div>

<p class="pads20 alignct">
	<a href="javascript:void(0)" class="blue-font right-push-30" onclick="window.print()">Print</a>
	<?php if(isset($_SESSION['return2work']) && $_SESSION['return2work'] == 200) { ?><a href="<?php echo DOMAIN_URL.PUB_FLD; ?>admin/portal" class="dark-blue-font">Return to workspace</a><?php } ?>
</p>


<script>

	function jsxView(key) {
		popmodalframe('frontdesk','booking.php',key,0,1200,3000);
	}

</script>