<?php
	$order_number = $ftoken;
	$token = $stoken;
?>
<div class="pads30">
	<h1 class="xlarge nobold default-text-font-bold">Reverse Transaction?</h1>
	<h3 class="xlarge nobold">You're about to reverse a transaction. This will have effect on your sales and stock record</h3>
	<div class="cs-height-30"></div>
	<div class="top-pull-50">
		<form action="" method="post">
			<input type="hidden" name="ordernumber" value="<?php echo $order_number; ?>">
			<input type="submit" name="submitbutton" value="Apply Reverse" class="submit pads10 dark-black-white-state rounded-button nc-width-70">
		</form>
	</div>

	<?php 
		if(isset($_POST['submitbutton'])) {
			
			$ordernumber = $_POST['ordernumber'];
			
			$pst_query = array("order_number"=>$ordernumber);
			$pst_field = array("isreversed"=>1,"deletedata"=>1);

			mysqli_data_update($tbL100,$pst_field,$pst_query);
			mysqli_data_update($tbL99,$pst_field,$pst_query);

			#check if bill is for corporate then credit the corporate
			$bill_data = "billtype,biller,bill_amount";
			$get_bill_data = mysqli_data_fetch($tbL100,$bill_data,$pst_query,'noarray');

			if(isset($_SESSION['postoreid'])) { $cur_pos_store_id = $_SESSION['postoreid']; }
			else { $cur_pos_store_id = 0; }

			$posname = idget_data($tbL14,$cur_pos_store_id,'posname');

			$guestAct_msg = "{$posname} : POS reversed with total amount of {$get_bill_data[2]}";

			$guest_activities_dataproperty = array("booking_number"=>$ordernumber,"customerid"=>0,"userid"=>$userSignedIn,"activities"=>$guestAct_msg,"remark_tag"=>"reverse","app_tag"=>"POS","session_tag"=>"POS Order","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL132,$guest_activities_dataproperty,'');

			if((!empty($get_bill_data[0]) && $get_bill_data[0] == 4) && (!empty($get_bill_data[1]) && $get_bill_data[1] > 0)) {

				$pay_amount = $get_bill_data[2];
				$transaction_desc = "POS reversed bill";

				#retrieve group creditlimt
				$credit_limit = idget_data($tbL58,$get_bill_data[1],'creditlimit');
				$new_creditlimit = $credit_limit + $pay_amount;

				#update group creditlimt
				$blc_selection_key = array("id"=>$get_bill_data[1]);
				$crl_datasets = array("creditlimit"=>$new_creditlimit);
				mysqli_data_update($tbL58,$crl_datasets,$blc_selection_key);
				
				$ledger_dataquery = "";
				$ledger_dataproperty = array("userid"=>$userSignedIn,"cspgid"=>$get_bill_data[1],"transaction_number"=>$ordernumber,"transaction_type"=>"Credit","amount"=>$pay_amount,"credit_balance"=>$new_creditlimit,"transaction_date"=>$server_get_date,"detail"=>$transaction_desc,"biller"=>"pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				
				mysqli_data_insert($tbL63,$ledger_dataproperty,$ledger_dataquery);
				
			}


			#for consumable items
			$order_data = "itemid,qty";
			$get_order_data = mysqli_data_fetch($tbL99,$order_data,$pst_query,'array');

			if(is_array($get_order_data)) {
				foreach($get_order_data as $key => $val) {
					$storagetype = idget_data($tbL16,$val['itemid'],'storagetype');
					$stockout = idget_data($tbL16,$val['itemid'],'stockout');
					$balance = idget_data($tbL16,$val['itemid'],'balance');

					if($storagetype == 'consumable') {
						$new_stockout = $stockout - $val['qty'];
						$new_balance = $balance + $val['qty'];

						$pst_query = array("id"=>$val['itemid']);
						$pst_field = array("stockout"=>$new_stockout,"balance"=>$new_balance);
						mysqli_data_update($tbL16,$pst_field,$pst_query);
					}
				}
			}

			?>
				<br><br>
				<h3 class="large nobold black-font alignct">Reverse was applied successfully. Please wait while system reset..</h3>
				<script>
					window.onload = () => {
						setTimeout(() => { window.parent.location.reload(); },3000);
					}
				</script>
			<?php
		}
	?>
</div>

