<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_departments = select_dt_fetch('status','Active',$tbL12,'id','department');

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can see employee payments report
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="rtask" id="rtask" value="employee-payment">
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Department</h3>
				<select name="departments" id="departments" class="nopads no-back-black" onchange="listEmployee(this.value)" required>
					<option value="" selected>Choose?</option>
					<?php echo $get_departments; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-30">
				<h3 class="large nobold default-text-font-bold">Employee</h3>
				<select name="employees" id="employees" class="nopads no-back-black">
					<option value="" selected>Choose?</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php if(isset($_POST['endate'])) { echo $_POST['endate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<!--<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>-->
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>
<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="cs-width-1500">
			<div id="section-to-print">
				<?php
					
					if(isset($_POST['rtask']) && $_POST['rtask'] == 'employee-payment') {

						$report_date = "BETWEEN ".date('d-m-Y',strtotime($_POST['startdate']))." AND ".date('d-m-Y',strtotime($_POST['endate']));

						?>
							<div class="bottom-push-30" align="center">
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">Employee Payments</h3>
								<h4 class="large nobold nomargin"><?php echo $report_date; ?></h4>
								<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
							</div>

						<?php
					
						$tbl = $tbL165;
						
						$startnumbr = 0;
						$keywords = "";

						if(isset($_POST['departments']) && !empty($_POST['departments'])) {
							$keywords .= " AND departmentid={$_POST['departments']}";
						}

						if(isset($_POST['employees']) && !empty($_POST['employees'])) {
							$keywords .= " AND staff={$_POST['employees']}";
						}

						if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
							$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						}

						$queryset = "status='Active' AND deletedata=0".$keywords;

						$keys = array(
							"receipt_number"=>"(fx)receipt no.",
							"transaction_date"=>"(df)transaction date",
							"staff"=>"employee",
							"departmentid"=>"department",
							"bill_type"=>"bill type",
							"amount"=>"(nf)amount (&#8358;)",
							"payment_mode"=>"pay type",
							"detail"=>"(nl)description",
							"userid"=>"collected by"
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


<script>

	function jsForm(fr) {
		document.getElementById(fr).submit();
	}

	function listEmployee(dept) {
	
		sqldatastring.sql = "SELECT * FROM user_admin_tbl WHERE department="+dept+" AND status='Active' AND deletedata=0";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '<option value="">All</option>';

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