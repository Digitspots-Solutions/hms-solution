<?php
	$payid = $ftoken;
	$paydate = $stoken;

	$paytype_name = idget_data($tbL24,$payid,'name');
	$paydate_format = date('d/m/Y',strtotime($paydate));

	$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
	$print_todaysdate = write_dateF($gh_get_date_format,$server_get_date);
	$print_todaystime = write_timeF($gh_get_time_format,$server_get_time);

	$sales_datasheet = array();

	$queryset_sales = array("payment_mode"=>$payid,"transaction_type"=>"Credit","datelogged"=>$paydate,"isreversed"=>0,"deletedata"=>0);
	$dataset_sales = "booking_number,invoice_number,biller,customerid,transaction_type,amount,userid";
	$wget_sales = mysqli_data_fetch($tbL131,$dataset_sales,$queryset_sales,'array');

	if(is_array($wget_sales) && count($wget_sales) > 0) {
		foreach($wget_sales as $key => $val) {
			
			if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
				
				$sales = array();
				
				$sales['bookingno'] = $val['booking_number'];
				$sales['invoiceno'] = $val['invoice_number'];
				$sales['transaction'] = "Credit";
				$sales['date'] = $paydate_format;
				$sales['amount'] = $val['amount'];
				$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

				array_push($sales_datasheet,$sales);
			}
		}
	}


	$queryset_sales2 = array("media"=>$payid,"datelogged"=>$paydate,"isreversed"=>0,"deletedata"=>0);
	$dataset_sales2 = "order_number,invoice_number,bill_amount,userid";
	$wget_sales2 = mysqli_data_fetch($tbL100,$dataset_sales2,$queryset_sales2,'array');

	if(is_array($wget_sales2) && count($wget_sales2) > 0) {
		foreach($wget_sales2 as $key => $val) {
		
			$sales = array();
			
			$sales['bookingno'] = $val['order_number'];
			$sales['invoiceno'] = $val['invoice_number'];
			$sales['transaction'] = "Credit";
			$sales['date'] = $paydate_format;
			$sales['amount'] = $val['bill_amount'];
			$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

			array_push($sales_datasheet,$sales);
		}
	}


	$queryset_sales3 = array("paymode"=>$payid,"transaction_type"=>"Credit","datelogged"=>$paydate,"deletedata"=>0);
	$dataset_sales3 = "id,transaction_number,cspgid,transaction_type,amount,userid";
	$wget_sales3 = mysqli_data_fetch($tbL63,$dataset_sales3,$queryset_sales3,'array');

	if(is_array($wget_sales3) && count($wget_sales3) > 0) {
		foreach($wget_sales3 as $key => $val) {
			
			if($val['transaction_type'] == 'credit' || $val['transaction_type'] == 'Credit') {
				
				$sales = array();

				$sales['bookingno'] = $val['transaction_number'];
				$sales['invoiceno'] = "CB".$val['id'];
				$sales['transaction'] = "Credit";
				$sales['date'] = $paydate_format;
				$sales['amount'] = $val['amount'];
				$sales['createdby'] = idget_data($tbL7,$val['userid'],'staffname');

				array_push($sales_datasheet,$sales);
			}
		}
	}


	$queryset_sales4 = array("mode"=>$payid,"datelogged"=>$paydate,"isreversed"=>0,"deletedata"=>0);
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
		<h3 class="large nobold nomargin">Pay Type Collection (<?php echo $paytype_name; ?> - <?php echo $paydate_format; ?>)</h3>
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