<?php $smdl = "accounting"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can receive payment from employee
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<style> .autoct { margin: 0 auto; } </style>

<?php

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$pst_query = "";
	$pst_field = "";

	createDatabasetable($var_tbl_312); //create a table for this post
	$get_departments = select_dt_fetch('status','Active',$tbL12,'id','department');
	$payment_mode = select_dt_fetch('iscounter','Yes',$tbL24,'id','name');

	#get user counter session id
	$counter_sesid = isset($_SESSION['counter_id']) ? $_SESSION['counter_id'] : 0;

	#--------------------------------------------------------------------------------------------------------------


	if(isset($_POST['employeepaymentbutton'])) {

		$departments = $_POST['departments'];
		$employees = $_POST['employees'];
		$transaction_date = $_POST['transactiondate'];
		$billtype = $_POST['billtype'];
		$paymenttype = $_POST['paymenttype'];
		$amount = escape_data($_POST['amount']);
		$detail = escape_data($_POST['remark']);

		$receipt_number = prgSequence($tbL155,'EPR');
		
		$pst_query = array("receipt_number"=>$receipt_number);
		$pst_field = array("receipt_number"=>$receipt_number,"transaction_date"=>$transaction_date,"staff"=>$employees,"departmentid"=>$departments,"bill_type"=>$billtype,"amount"=>$amount,"payment_mode"=>$paymenttype,"detail"=>$detail,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$result = mysqli_data_insert($tbL165,$pst_field,$pst_query);

		if(isset($result) && $result == 2) {

			//add payment to user counter
			
			if(isset($counter_sesid) && $counter_sesid > 0) {
				
				$sales_counter_query = array("counterid"=>$counter_sesid,"userid"=>$userSignedIn,"fundid"=>$paymenttype,"ispast"=>0);
				$sales_counter_data = mysqli_data_fetch($tbL25,'collection',$sales_counter_query,'noarray');

				$new_collection = $sales_counter_data[0] + $amount;
				
				$sales_counter_sql = array("collection"=>$new_collection);
				mysqli_data_update($tbL25,$sales_counter_sql,$sales_counter_query);

				//create replica information of payment in general payment table
				$get_user = idget_data($tbL7,$employees,'staffname');
				$desc = "Employee {$get_user} payment received for {$billtype} : ".$detail;

				$sql_uquery = "";
				$sql_udata = array("biller"=>0,"sales_point"=>"employee-payment","booking_number"=>"","receipt_number"=>$receipt_number,"customerid"=>"","transaction_type"=>"credit","amount"=>$amount,"payment_mode"=>$paymenttype,"sales_description"=>$desc,"ispaid"=>1,"userid"=>$userSignedIn,"counter_used"=>$counter_sesid,"shiftid"=>$current_shift,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time,"bizday"=>$server_get_bizid);
				mysqli_data_insert($tbL131,$sql_udata,$sql_uquery);
			}

			//create a log file
			$message = "Recently received payment from an employee ({$get_user}) for ".$billtype." of sum of ".$amount.": ".$detail;
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<div class="block-element top-push-30 alignct">';
			$post_result .= '<span class="light-red-font ft-sml-size">Payment received and noted successfully</span>';
			$post_result .= '</div>';
		}
	}

	#--------------------------------------------------------------------------------------------------------------

	echo $post_result;

?>


<div class="cs-height-30"></div>

<form action="" method="post" autocomplete="off" onsubmit="">
	
	<div id="acct-box" class="fx-width-80 box-border-thick motion sml-rounded-button top-push-30 white-theme autoct">
		<div class="box-border-thick-bottom pads20">
			<span class="float-right">
				<input type="submit" name="employeepaymentbutton" id="employeepaymentbutton" value="Make Payment" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
			</span>
			<h3 class="large nobold">+ Employee Payments</h3><br>
		</div>
		<div class="pads20">
			<ul class="nolist">
				<li class="ln-display-box float-left fx-width-35 box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Department</h4>
					<select name="departments" id="departments" class="no-back-black" onchange="listEmployee(this.value)" required>
						<option value="" selected>Choose?</option>
						<?php echo $get_departments; ?>
					</select>
				</li>
				<li class="ln-display-box float-left fx-width-35 right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Employee Name</h4>
					<select name="employees" id="employees" class="no-back-black" required>
						<option value="" selected>Choose?</option>
					</select>
				</li>
				<li class="ln-display-box float-left fx-width-30 box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Transaction Date</h4>
					<input type="date" name="transactiondate" id="transactiondate" value="<?php echo $server_get_date; ?>" class="no-back-black" required>
				</li>
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="ln-display-box float-left fx-width-35 box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Bill Type</h4>
					<select name="billtype" id="billtype" class="no-back-black" required>
						<option value="" selected>Choose?</option>
						<option value="Excess in FD">Excess in FD</option>
						<option value="City Ledger Payment">City Ledger Payment</option>
						<option value="Excess in POS">Excess in POS</option>
						<option value="Miscellaneous">Miscellaneous</option>
						<option value="Special Allowance">Special Allowance</option>
					</select>
				</li>
				<li class="ln-display-box float-left fx-width-35 right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Payment Type</h4>
					<select name="paymenttype" id="paymenttype" class="no-back-black" required>
						<option value="" selected>Choose?</option>
						<?php echo $payment_mode; ?>
					</select>
				</li>
				<li class="ln-display-box float-left fx-width-30 right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
					<h4 class="xlarge nobold default-text-font-bold">Amount (&#8358;)</h4>
					<input type="text" name="wgtamount" id="wgtamount" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount')" class="no-back-black default-text-font-bold">
					<input type="hidden" name="amount" id="amount" required>
				</li>
				<li class="block-element new-line-space">
					&nbsp;
				</li>
				<li class="">
					<textarea name="remark" id="remark" placeholder="Write description here?" class="notextborder"></textarea>
				</li>
			</ul>
		</div>
	</div>
</form>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL165;
					
					$startnumbr = 0;
					$keywords = "";

					$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					$queryset = "status='Active' AND deletedata=0".$keywords;

					$keys = array(
						"receipt_number"=>"(fx)receipt no.",
						"transaction_date"=>"(df)transaction date",
						"staff"=>"employee",
						"departmentid"=>"department",
						"bill_type"=>"bill type",
						"amount"=>"(nf)amount (&#8358;)",
						"userid"=>"collected by"
					);

					$format = array(
						"grid",
						"form-ctrl",
						"use-base-data"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;

				?>
			</div>
		</div>
	</div>
</div>


<script>

	function listEmployee(dept) {
	
		sqldatastring.sql = "SELECT * FROM user_admin_tbl WHERE department="+dept+" AND status='Active' AND deletedata=0";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '';

			for(i=0; i<data.length; i++) {
				vhtml += '<option value="'+data[i].id+'">'+data[i].staffname+'</option>';
			}

			writeObjheader('employees',vhtml);
		}
	}


	function jsxView(key) {
		popmodalframe('accounting','employee_payment_receipt',key,0,1000,2500);
	}

</script>