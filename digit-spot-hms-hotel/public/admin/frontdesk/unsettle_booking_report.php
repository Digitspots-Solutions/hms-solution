<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_shifts = select_dt_fetch('deletedata',0,$tbL20,'id','shiftname');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the list of unsettled bookings
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<span class="ln-display-box float-left cs-width-180 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Type</h3>
				<select name="bookingtype" id="bookingtype" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<option value="Individual">Individual</option>
					<option value="Corporate">Corporate</option>
					<option value="Complimentary">Complimentary</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-30">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100">
			<div id="section-to-print">
				
				<div class="bottom-push-30" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold nomargin">Unsettled Bookings Report</h3>
				
					<?php
						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							?><h3 class="large nobold"><?php echo 'From '.date('d-m-Y',strtotime($_POST['startdate'])).' to '.date('d-m-Y',strtotime($_POST['endate'])); ?></h3><?php
						} else {
							?><h3 class="large nobold"><?php echo 'From '.date('d-m-Y',strtotime($server_get_date)).' to '.date('d-m-Y',strtotime($server_get_date)); ?></h3><?php
						}
					?>

				</div>

				<?php
					
					$startnumbr = 0;
					$shift_name = ""; $keywords = "";

					if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])) {
						$keywords .= " AND booking_type='{$_POST['bookingtype']}'";
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND checkout_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
					} else {
						$keywords .= " AND checkout_date BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					}

					$sql = "SELECT * FROM {$tbL130} WHERE reservation='Checking Out' AND settled_booking=0 AND deletedata=0".$keywords;
					$dataset = wgetSQL($sql);

				?>

				<table cellpadding="3" cellspacing="0" border="1">
					<tr>
						<td class="alignct default-text-font-bold"></td>
						<td class="alignct default-text-font-bold">Booking No.</td>
						<td class="alignct default-text-font-bold">Primary Guest</td>
						<td class="alignct default-text-font-bold">Total Amount</td>
						<td class="alignct default-text-font-bold">Amount Paid</td>
						<td class="alignct default-text-font-bold">Total Balance</td>
					</tr>

					<?php
						if(is_array($dataset) && count($dataset)) {
							
							$g_total_balance = 0; $num = 0;

							foreach($dataset as $key => $val) {
								
								$num += 1;

								$sql_1 = "SELECT SUM(bill_amount) AS total FROM {$tbL100} WHERE booking_number='{$val['booking_number']}' AND isreversed=0 AND deletedata=0"; $dataset_1 = wgetSQL($sql_1);
								$pos_bill = $dataset_1[0]['total'];

								$sql_2 = "SELECT SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge, SUM(room_amount) AS total, SUM(discount_amount) AS discounts FROM {$tbL134} WHERE booking_number='{$val['booking_number']}' AND room_status IN('CheckedIn') AND deletedata=0";
								$dataset_2 = wgetSQL($sql_2);

								$total_amount = (($dataset_2[0]['total'] + $dataset_2[0]['vat'] + $dataset_2[0]['consumption'] + $dataset_2[0]['scharge']) - $dataset_2[0]['discounts']) + $pos_bill;

								$sql_3 = "SELECT * FROM {$tbL102} WHERE booking_number='{$val['booking_number']}' AND primary_guest=1";
								$dataset_3 = wgetSQL($sql_3);

								$salutation = idget_data($tbL42,$dataset_3[0]['salutation'],'name');

								$sql_4 = "SELECT SUM(amount) AS totalpaid FROM {$tbL131} WHERE booking_number='{$val['booking_number']}' AND transaction_type IN('credit') AND isreversed=0 AND deletedata=0";
								$dataset_4 = wgetSQL($sql_4); $total_paid = $dataset_4[0]['totalpaid'];

								$total_balance = $total_paid - $total_amount;
								$g_total_balance = $g_total_balance + $total_balance;

								if($val['bill_type'] == 'Complimentary' && $val['bill_to'] >= 1) { $taggy = ' <b class="light-red-font nobold">[Compl.]</b>'; }
								elseif($val['bill_type'] == 'Corporate' && $val['bill_to'] >= 1) { $taggy = ' <b class="forest-green-font nobold">[Corp.]</b>'; }
								else { $taggy = ''; }

								?>
									<tr>
										<td class="alignlt"><?php echo $num; ?>.</td>
										<td class="alignlt"><a href="javascript:void(0)" class="blue-font default-text-font-bold" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></a></td>
										<td class="alignlt"><?php echo $salutation.' '.$dataset_3[0]['fname'].' '.$dataset_3[0]['lname'].$taggy; ?></td>
										<td class="alignrt"><?php echo number_format($total_amount,2); ?></td>
										<td class="alignrt"><?php echo number_format($total_paid,2); ?></td>
										<td class="alignrt"><?php echo number_format($total_balance,2); ?></td>
									</tr>
								<?php

								$salutation = ""; $total_amount = ""; $total_paid = ""; $total_balance = ""; $pos_bill = "";
								$taggy = "";
							}

							?>
								<tr>
									<td colspan="5" class="alignlt">Total</td>
									<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_total_balance,2); ?></td>
								</tr>
							<?php
						}
					?>

				</table>
			</div>
		</div>
	</div>
</div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function jsxView(key) {
		var numbr = Math.round(Math.random() * 10000) + 1;
		crframe(key,numbr,'reservations');
	}

</script>