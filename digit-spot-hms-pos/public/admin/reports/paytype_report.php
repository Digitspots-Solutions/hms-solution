<?php
	$smdl = "reports"; $logs = escape_data($_GET['logs']);
	$paytypes = select_dt_fetch('iscounter','Yes',$tbL24,'id','name');

	if(isset($_POST['paytype']) && !empty($_POST['paytype']) && $_POST['paytype'] > 0) { $payid = $_POST['paytype']; $payname = idget_data($tbL24,$_POST['paytype'],'name'); }
	else { $payid = ""; $payname = 'Choose?'; }

	if(isset($_POST['portal']) && !empty($_POST['portal'])) { $trval = $_POST['portal']; $trtext = $_POST['portal']; $portal = $_POST['portal']; }
	else { $trval = "All"; $trtext = "All"; $portal = "All"; }
?>

<div class="block-element bottom-push-5">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get pay-type reports within specific period
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 light-yellow-theme pads10">
	<h3 class="large">Pay Type Report</h3>
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-10">
			<small class="block-element bottom-push-3 left-pull-3">Start Date</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php if(isset($_POST['fieldset1'])) { echo $_POST['fieldset1']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-10">
			<small class="block-element bottom-push-3 left-pull-3">End Date</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php if(isset($_POST['fieldset2'])) { echo $_POST['fieldset2']; } else { echo $server_get_date; } ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-10">
			<small class="block-element bottom-push-3 left-pull-3">Payment Type</small>
			<select name="paytype" id="paytype" required>
				<option value="<?php echo $payid; ?>" selected="selected"><?php echo $payname; ?></option>
				<?php echo $paytypes; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Transaction Portal</small>
			<select name="portal" id="portal">
				<option value="<?php echo $trval; ?>" selected="selected"><?php echo $trtext; ?></option>
				<option value="">All</option>
				<option value="Booking">Booking</option>
				<option value="POS">POS</option>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-10 left-pull-20 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>

<?php
	
	if(isset($_POST['submitbutton'])) {
	
		$paydate_f = $_POST['fieldset1'];
		$paydate_t = $_POST['fieldset2'];

		$paytype_name = $payname;
		$paydate_format = date('d/m/y',strtotime($paydate_f)).' to '.date('d/m/y',strtotime($paydate_t));

		$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
		$print_todaysdate = write_dateF($gh_get_date_format,$server_get_date);
		$print_todaystime = write_timeF($gh_get_time_format,$server_get_time);

		$sales_datasheet = array();


		if($trval == 'Booking') {
			
			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales = array("payment_mode"=>$payid,"transaction_type"=>"Credit","isreversed"=>0,"deletedata"=>0);
			$dataset_sales = "booking_number,invoice_number,biller,customerid,transaction_type,amount,userid,datelogged,timelogged";
			$wget_sales = mysqli_data_fetch($tbL131,$dataset_sales,$queryset_sales,'array');
			$additionalQuery = "";

			if(is_array($wget_sales) && count($wget_sales) > 0) {
				foreach($wget_sales as $key => $val) {
					
					if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
						
						$sales = array();
						
						$sales['bookingno'] = $val['booking_number'];
						$sales['invoiceno'] = $val['invoice_number'];
						$sales['transaction'] = "Credit";
						$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
						$sales['amount'] = $val['amount'];
						$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

						array_push($sales_datasheet,$sales);
					}
				}
			}

			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales3 = array("paymode"=>$payid,"transaction_type"=>"Credit","deletedata"=>0);
			$dataset_sales3 = "id,transaction_number,cspgid,transaction_type,amount,userid,datelogged,timelogged";
			$wget_sales3 = mysqli_data_fetch($tbL63,$dataset_sales3,$queryset_sales3,'array');
			$additionalQuery = "";

			if(is_array($wget_sales3) && count($wget_sales3) > 0) {
				foreach($wget_sales3 as $key => $val) {
					
					if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
						
						$sales = array();

						$sales['bookingno'] = $val['transaction_number'];
						$sales['invoiceno'] = "CB".$val['id'];
						$sales['transaction'] = "Credit";
						$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
						$sales['amount'] = $val['amount'];
						$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

						array_push($sales_datasheet,$sales);
					}
				}
			}

		} elseif($trval == 'POS') {
			
			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales2 = array("media"=>$payid,"isreversed"=>0,"deletedata"=>0);
			$dataset_sales2 = "order_number,invoice_number,bill_amount,userid,datelogged,timelogged";
			$wget_sales2 = mysqli_data_fetch($tbL100,$dataset_sales2,$queryset_sales2,'array');
			$additionalQuery = "";

			if(is_array($wget_sales2) && count($wget_sales2) > 0) {
				foreach($wget_sales2 as $key => $val) {
				
					$sales = array();
					
					$sales['bookingno'] = $val['order_number'];
					$sales['invoiceno'] = $val['invoice_number'];
					$sales['transaction'] = "Credit";
					$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
					$sales['amount'] = $val['bill_amount'];
					$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

					array_push($sales_datasheet,$sales);
				}
			}

		} elseif($trval == 'All') {

			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales = array("payment_mode"=>$payid,"transaction_type"=>"Credit","isreversed"=>0,"deletedata"=>0);
			$dataset_sales = "booking_number,invoice_number,biller,customerid,transaction_type,amount,userid,datelogged,timelogged";
			$wget_sales = mysqli_data_fetch($tbL131,$dataset_sales,$queryset_sales,'array');
			$additionalQuery = "";

			if(is_array($wget_sales) && count($wget_sales) > 0) {
				foreach($wget_sales as $key => $val) {
					
					if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
						
						$sales = array();
						
						$sales['bookingno'] = $val['booking_number'];
						$sales['invoiceno'] = $val['invoice_number'];
						$sales['transaction'] = "Credit";
						$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
						$sales['amount'] = $val['amount'];
						$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

						array_push($sales_datasheet,$sales);
					}
				}
			}

			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales3 = array("paymode"=>$payid,"transaction_type"=>"Credit","deletedata"=>0);
			$dataset_sales3 = "id,transaction_number,cspgid,transaction_type,amount,userid,datelogged,timelogged";
			$wget_sales3 = mysqli_data_fetch($tbL63,$dataset_sales3,$queryset_sales3,'array');
			$additionalQuery = "";

			if(is_array($wget_sales3) && count($wget_sales3) > 0) {
				foreach($wget_sales3 as $key => $val) {
					
					if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
						
						$sales = array();

						$sales['bookingno'] = $val['transaction_number'];
						$sales['invoiceno'] = "CB".$val['id'];
						$sales['transaction'] = "Credit";
						$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
						$sales['amount'] = $val['amount'];
						$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

						array_push($sales_datasheet,$sales);
					}
				}
			}

			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales2 = array("media"=>$payid,"isreversed"=>0,"deletedata"=>0);
			$dataset_sales2 = "order_number,invoice_number,bill_amount,userid,datelogged,timelogged";
			$wget_sales2 = mysqli_data_fetch($tbL100,$dataset_sales2,$queryset_sales2,'array');
			$additionalQuery = "";

			if(is_array($wget_sales2) && count($wget_sales2) > 0) {
				foreach($wget_sales2 as $key => $val) {
				
					$sales = array();
					
					$sales['bookingno'] = $val['order_number'];
					$sales['invoiceno'] = $val['invoice_number'];
					$sales['transaction'] = "Credit";
					$sales['date'] = date('d/m/y',strtotime($val['datelogged']))." ".$val['timelogged'];
					$sales['amount'] = $val['bill_amount'];
					$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

					array_push($sales_datasheet,$sales);
				}
			}


			$additionalQuery = " AND datelogged >= '{$paydate_f}' AND datelogged <= '{$paydate_t}'";
			$queryset_sales4 = array("mode"=>$payid,"isreversed"=>0,"deletedata"=>0);
			$dataset_sales4 = "id,recreation_number,invoice_number,amount,userid";
			$wget_sales4 = mysqli_data_fetch($tbL107,$dataset_sales4,$queryset_sales4,'array');

			if(is_array($wget_sales4) && count($wget_sales4) > 0) {
				foreach($wget_sales4 as $key => $val) {
					
					$sales = array();

					$sales['bookingno'] = $val['recreation_number'];
					$sales['invoiceno'] = $val['invoice_number'];
					$sales['transaction'] = "Credit";
					$sales['date'] = $paydate_format;
					$sales['amount'] = $val['amount'];
					$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

					array_push($sales_datasheet,$sales);
				}
			}
		}

		?>

			<p class="top-pull-10 alignrt">
				<input type="button" value="Print" class="anchor" onclick="window.print()">
			</p>

			<div id="section-to-print" class="block-element" align="center">
				<div class="bottom-push-10">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo _LONG_NAME; ?></h3>
					<h3 class="large nobold nomargin"><?php echo $portal; ?> Pay Type Collection (<?php echo $paytype_name; ?> - <?php echo $paydate_format; ?>)</h3>
					<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?> On <?php echo $print_todaysdate.' '.$print_todaystime; ?></h4>
				</div>

				<table style="font-size: 11px !important;" cellpadding="2" cellspacing="0" border="1">
					<tr style="background-color: #c1c1c1;">
						<td class="alignct default-text-font-bold">Transaction No.</td>
						<td class="alignct default-text-font-bold">Invoice No.</td>
						<td class="alignct default-text-font-bold">Transaction Type</td>
						<td class="alignct default-text-font-bold">Date</td>
						<td class="alignct default-text-font-bold">Amount (&#8358;)</td>
						<td class="alignct default-text-font-bold">Logged By</td>
					</tr>
					
					<?php
						
						$totalcredit = 0; $totaldebit = 0;

						if(is_array($sales_datasheet) && count($sales_datasheet) > 0):
							for($i=0; $i < count($sales_datasheet); $i++):

								$totalcredit = $totalcredit + $sales_datasheet[$i]['amount'];
					?>

						<tr>
							<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['bookingno']; ?></td>
							<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['invoiceno']; ?></td>
							<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['transaction']; ?></td>
							<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['date']; ?></td>
							<td class="alignct default-text-font-bold"><?php echo number_format($sales_datasheet[$i]['amount'],2); ?></td>
							<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['createdby']; ?></td>
						</tr>

					<?php 
						endfor;
						endif;
					?>

						<tr>
							<td class="alignct">&nbsp;</td>
							<td class="alignct">&nbsp;</td>
							<td class="alignct">&nbsp;</td>
							<td class="alignct">&nbsp;</td>
							<td class="alignct default-text-font-bold"><?php echo number_format($totalcredit,2); ?></td>
							<td class="alignct">&nbsp;</td>
						</tr>

				</table>

			</div>
		<?php
	}