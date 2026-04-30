<?php
	$receipt_id = $ftoken;
	$ths_token = $stoken;

	$printedby_staffname = idget_data($tbL7,$userSignedIn,'staffname');
	$print_todaysdate = write_dateF($gh_get_date_format,$server_get_date);
	$print_todaystime = write_timeF($gh_get_time_format,$server_get_time);

	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	$query_pu = array("userid"=>$userSignedIn,"counterid"=>$current_counter,"logstatus"=>"Open");
	$getlogdate = mysqli_data_fetch($tbL22,'datelogged',$query_pu,'noarray');

	$additionalQuery = "";

	$counter = isset($_SESSION['counter']) ? $_SESSION['counter'] : $current_counter;
	$user = isset($_SESSION['user']) ? $_SESSION['user'] : $userSignedIn;
	$shiftid = isset($_SESSION['shift']) ? $_SESSION['shift'] : $current_shift;
	$start_date = isset($_SESSION['from']) ? $_SESSION['from'] : $getlogdate[0];
	$end_date = isset($_SESSION['to']) ? $_SESSION['to'] : $server_get_date;

	$xcounter_user = idget_data($tbL7,$user,'staffname');
	$xcounter_name = idget_data($tbL19,$counter,'countername');

	$sales_datasheet = array();
	
	//$additionalQuery = " AND transaction_type IN('credit')";
	//$queryset_sales = array("userid"=>$user,"counter_used"=>$counter,"shiftid"=>$shiftid);

	$additionalQuery = " AND datelogged BETWEEN '{$start_date}' AND '{$end_date}'";
	$queryset_sales = array("userid"=>$user,"isreversed"=>0,"deletedata"=>0);
	$dataset_sales = "recreation_number,memberid,amount,invoice_number,mode,detail,datelogged,timelogged";
	$wget_sales = mysqli_data_fetch($tbL107,$dataset_sales,$queryset_sales,'array');

	if(is_array($wget_sales) && count($wget_sales) > 0) {
		foreach($wget_sales as $key => $val) {
			
			$sales = array();

			$customer = idget_data($tbL105,$val['memberid'],'firstname').' ';
			$customer .= idget_data($tbL105,$val['memberid'],'lastname');
			
			$sales['date'] = date('d/m/Y',strtotime($val['datelogged']));
			$sales['time'] = $val['timelogged'];
			$sales['receipt'] = $val['invoice_number'];
			$sales['bookingno'] = $val['recreation_number'];
			$sales['guestname'] = $customer;
			$sales['desc'] = $val['detail'];
			$sales['credit'] = $val['amount'];
			$sales['debit'] = 0;

			array_push($sales_datasheet,$sales);

			$customer = ""; $credit_amount = 0; $debit_amount = 0;
		}
	}


	$additionalQuery = " AND media NOT IN(0) AND datelogged BETWEEN '{$start_date}' AND '{$end_date}'";
	$queryset_sales2x = array("userid"=>$user,"shiftid"=>$shiftid,"isreversed"=>0,"deletedata"=>0);
	$dataset_sales2x = "posid,billtype,paydetail,order_number,receipt_number,bill_amount,media,datelogged,timelogged";
	$wget_sales2x = mysqli_data_fetch($tbL100,$dataset_sales2x,$queryset_sales2x,'array');

	if(is_array($wget_sales2x) && count($wget_sales2x) > 0) {

		include "../../includes/pos_common_data.php";
		
		foreach($wget_sales2x as $key => $val) {
			
			$customer = $bill_type[$val['billtype']];
			$credit_amount = $val['bill_amount'];
			$detail = idget_data($tbL14,$val['posid'],'posname')." (Paid with ".idget_data($tbL24,$val['media'],'name').": ".$val['paydetail'].")";

			$sales = array();
			
			$sales['date'] = date('d/m/Y',strtotime($val['datelogged']));
			$sales['time'] = $val['timelogged'];
			$sales['receipt'] = $val['receipt_number'];
			$sales['bookingno'] = $val['order_number'];
			$sales['guestname'] = $customer;
			$sales['desc'] = $detail;
			$sales['credit'] = $credit_amount;
			$sales['debit'] = 0;

			array_push($sales_datasheet,$sales);

			$customer = ""; $credit_amount = 0; $debit_amount = 0; $detail = "";
		}
	}


	$additionalQuery = " AND datelogged BETWEEN '".$start_date."' AND '".$end_date."'";
	//$queryset_pty = array("userid"=>$user,"counterid"=>$counter,"shiftid"=>$shiftid,"ispast"=>0);
	$queryset_pty = array("userid"=>$user,"counterid"=>$counter,"shiftid"=>$shiftid);
	$dataset_pty = "id,fundid,openingbalance,fundadded,withdrawal,collection,refunds,err";
	$wget_counter = mysqli_data_fetch($tbL25,$dataset_pty,$queryset_pty,'array');

	$additionalQuery = "";
	
?>

<p class="top-pull-10 alignrt">
	<input type="button" value="Print" class="anchor" onclick="window.print()">
</p>

<div id="section-to-print" class="block-element" align="center">
	<div class="bottom-push-3">
		<div class="cs-width-100 bottom-push-10 noscroll">
			<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
		</div>
		<h3 class="xlarge nobold default-text-font-bold nomargin"><?php echo _LONG_NAME; ?></h3>
		<h4 class="large nobold nomargin"><?php echo $hotel_address; ?></h4>
		<h4 class="large nobold nomargin bottom-pull-5">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></h4>
		<h4 class="large nobold">Printed By: <?php echo $printedby_staffname; ?> On <?php echo $print_todaysdate.' '.$print_todaystime; ?></h4>
	</div>
	<h4 class="large nobold left-pull-30 alignlt"><b class="nobold default-text-font-bold"><?php echo $xcounter_name; ?></b> (<?php echo $xcounter_user; ?>)</h4>
	<div class="right-pull-5 left-pull-5" align="center">
		
		<table style="width: 900px !important; font-size: 11px !important;" cellpadding="2" cellspacing="0" border="1">
			<tr>
				<td class="alignct default-text-font-bold">Date</td>
				<td class="alignct default-text-font-bold">Time</td>
				<td class="alignct default-text-font-bold">Receipt</td>
				<td class="alignct default-text-font-bold">Recreation No.</td>
				<td class="alignct default-text-font-bold">Guest Name</td>
				<td class="alignct default-text-font-bold">Description</td>
				<td class="alignct default-text-font-bold">Credit In (&#8358;)</td>
				<td class="alignct default-text-font-bold">Debit In (&#8358;)</td>
			</tr>
			
			<?php
				
				$totalcredit = 0; $totaldebit = 0;

				if(is_array($sales_datasheet) && count($sales_datasheet) > 0):
					for($i=0; $i < count($sales_datasheet); $i++):

						$totalcredit = $totalcredit + $sales_datasheet[$i]['credit'];
						$totaldebit = $totaldebit + $sales_datasheet[$i]['debit'];
			?>

				<tr>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['date']; ?></td>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['time']; ?></td>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['receipt']; ?></td>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['bookingno']; ?></td>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['guestname']; ?></td>
					<td class="alignct default-text-font-bold"><?php echo $sales_datasheet[$i]['desc']; ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($sales_datasheet[$i]['credit'],2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($sales_datasheet[$i]['debit'],2); ?></td>
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
					<td class="alignct">&nbsp;</td>
					<td class="alignct">&nbsp;</td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($totalcredit,2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($totaldebit,2); ?></td>
				</tr>

		</table>

		<br>

		<h4 class="large nobold alignlt left-pull-30">Pay Type Details</h4>
		<table style="width: 900px !important; font-size: 11px !important;" cellpadding="2" cellspacing="0" border="1">
			<tr>
				<td class="alignct default-text-font-bold">Pay Types</td>
				<td class="alignct default-text-font-bold">Opening Balance</td>
				<td class="alignct default-text-font-bold">Withdrawals</td>
				<td class="alignct default-text-font-bold">Collections</td>
				<td class="alignct default-text-font-bold">Refunds</td>
				<td class="alignct default-text-font-bold">Balance On-Hand</td>
			</tr>

			<?php
				
				$gcr_amount = 0;
				$total_openingbal = 0; $total_fundadded = 0; $total_withdrawal = 0;
				$total_collection = 0; $total_refunds = 0; $total_moneyathand = 0;

				$pay_type_name = ""; $pay_type_gcr = ""; $moneyathand = ""; $actual_refund = 0;

				$df_pty = idget_fdata($tbL24,'isdefault','Yes','id');

				if(is_array($wget_counter) && count($wget_counter) > 0):
					foreach($wget_counter as $key => $val):
						$pay_type_name = idget_data($tbL24,$val['fundid'],'name');
						$pay_type_gcr = idget_data($tbL24,$val['fundid'],'isreceivable');

						if($pay_type_gcr === 'Yes'):
							$gcr_amount = $gcr_amount + $val['collection'];
						endif;

						if($val['fundid'] == $df_pty):
							$actual_refund = $actual_refund + $val['refunds'];
						endif;

						$moneyathand = ($val['openingbalance'] + $val['fundadded'] + $val['collection']) - ($val['refunds'] + $val['withdrawal']);

						$total_openingbal = $total_openingbal + $val['openingbalance'];
						$total_fundadded = $total_fundadded + $val['fundadded'];
						$total_withdrawal = $total_withdrawal + $val['withdrawal'];
						$total_collection = $total_collection + $val['collection'];
						$total_refunds = $total_refunds + $val['refunds'];

						$total_moneyathand = $total_moneyathand + $moneyathand;
			?>

				<tr>
					<td class="alignlt default-text-font-bold"><?php echo $pay_type_name; ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($val['openingbalance'],2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($val['withdrawal'],2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($val['collection'],2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($val['refunds'],2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($moneyathand,2); ?></td>
				</tr>

			<?php
				endforeach;
				endif;
			?>

				<tr>
					<td class="alignlt default-text-font-bold">Total</td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($total_openingbal,2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($total_withdrawal,2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($total_collection,2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($total_refunds,2); ?></td>
					<td class="alignrt default-text-font-bold"><?php echo number_format($total_moneyathand,2); ?></td>
				</tr>

		</table>

		<br>

		<?php $g_balance = $totalcredit - $totaldebit; ?>

		<table style="width: 700px !important; font-size: 11px !important;" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="400px" valign="top" class="box-noborder">
					<h4 class="large nobold alignlt">* Current Hotel Summary</h4>
					<table cellpadding="2" cellspacing="0" border="1">
						<tr>
							<td class="alignct default-text-font-bold">Particulars</td>
							<td class="alignct default-text-font-bold">Amount (&#8358;)</td>
						</tr>
						<tr>
							<td class="alignlt">Opening Balance</td>
							<td class="alignrt default-text-font-bold"><?php echo number_format($total_openingbal,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Credit</td>
							<td class="alignrt default-text-font-bold"><?php echo number_format($totalcredit,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt">Debit</td>
							<td class="alignrt default-text-font-bold"><?php echo number_format($totaldebit,2); ?></td>
						</tr>
						<tr>
							<td class="alignlt default-text-font-bold">Total</td>
							<td class="alignrt default-text-font-bold"><?php echo number_format($g_balance,2); ?></td>
						</tr>
					</table>
				</td>
				<td width="50px" valign="top" class="box-noborder">
				</td>
				<td width="350px" valign="top" class="box-noborder">
					<h4 class="large nobold alignlt">* Denominations</h4>
					<table cellpadding="2" cellspacing="0" border="1" style="font-size: 11px !important;">
						<tr>
							<td class="alignct default-text-font-bold">Amount in (&#8358;)</td>
							<td class="alignct default-text-font-bold">Total</td>
						</tr>
						
						<?php
							$queryset = array("deletedata"=>0);
							$denominations = mysqli_data_fetch($tbL28,'name',$queryset,'array');
						
							if(is_array($denominations)) {
								foreach($denominations as $key => $val) {
									?>
										<tr>
											<td class="alignrt"><?php echo $val['name']; ?>*</td>
											<td class="alignct default-text-font-bold"></td>
										</tr>
									<?php
								}
							}
						?>
						<tr>
							<td class="alignlt default-text-font-bold">Total</td>
							<td class="alignrt"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<br><br>

		<?php $actual_gcr_amount = $gcr_amount - $actual_refund; ?>

		<p class="alignlt default-text-font-bold">Total Amount Payable to GC: &#8358; <?php echo number_format($actual_gcr_amount,2); ?></p>

	</div>
</div>