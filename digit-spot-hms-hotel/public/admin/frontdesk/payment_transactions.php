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
		&nbsp; Note: here you can get the list of payments or refunds done for guest-paying bookings
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
				<h3 class="large nobold default-text-font-bold">Transaction Type</h3>
				<select name="transactiontype" id="transactiontype" class="nopads no-back-black" onclick="getcspg(this.value)">
					<?php if(isset($_POST['transactiontype']) && !empty($_POST['transactiontype'])): ?>
						<option value="<?php echo $_POST['transactiontype']; ?>" selected="selected"><?php echo $_POST['transactiontype']; ?></option>
					<?php endif; ?>
					<option value="Payments">Payments</option>
					<option value="Refunds">Refunds</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php if(isset($_POST['endate'])) { echo $_POST['endate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="cs-width-1200">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL131;

					$startnumbr = 0; $iscspg=0;
					$shift_name = ""; $keywords = "";

					if(isset($_POST['transactiontype']) && !empty($_POST['transactiontype'])) {
					
						if($_POST['transactiontype'] == 'Payments') { $keywords .= " AND transaction_type IN('credit')"; $userlabel = "received by"; }
						elseif($_POST['transactiontype'] == 'Refunds') { $keywords .= " AND transaction_type IN('refund')"; $userlabel = "paid by";  }
						//else { $keywords .= " AND transaction_type IN('credit','refund')"; }
						
		
						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						} else {
							$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						}

						$queryset = "deletedata=0 AND isreversed=0 AND customerid > 0".$keywords." ORDER BY id DESC";

						$force_tabs = array(
							"booking_type"=>array("tbl"=>$tbL130,"key"=>"booking_number","val"=>"booking_number","th"=>"booking type")
						);

						$removedash = true;

						$keys = array(
							"booking_number"=>"(fx)booking no.",
							"receipt_number"=>"receipt no.",
							"customerid"=>"guest name",
							"amount"=>"(nf)amount",
							"payment_mode"=>"method",
							"datelogged"=>"(df)date",
							"timelogged"=>"time",
							"userid"=>$userlabel
						);

						$format = array(
							"grid",
							"use-base-data"
						);

						$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
						echo $datasheet;
					}

				?>
			</div>
		</div>
	</div>
</div>

<div class="noshow"><a id="newwin" href="" target="_blank">openwindow</a></div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function jsxView(key) {
		/*if(key.indexOf('REC') > -1) {
			document.getElementById('newwin').setAttribute('href','receipt?');
		} else {
			var numbr = Math.round((Math.random() * 10000000) - 1);
			crframe(key,numbr,'reservations');
		}*/

		var numbr = Math.round((Math.random() * 10000000) - 1);
		crframe(key,numbr,'reservations');
	}

</script>