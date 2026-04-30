<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_complimentary = select_dt_fetch('deletedata',0,$tbL33,'id','name');

if(isset($_POST['cgroup']) && !empty($_POST['cgroup'])) { $groupid = $_POST['cgroup']; $groupname = idget_data($tbL33,$_POST['cgroup'],'name'); } else { $groupid = ""; $groupname = "All"; }

if(isset($_POST['startdate']) && !empty($_POST['startdate'])) { $startdate = $_POST['startdate']; }
else { $startdate = $server_get_date; }

if(isset($_POST['endate']) && !empty($_POST['endate'])) { $endate = $_POST['endate']; }
else { $endate = $server_get_date; }

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the list of complimentary bookings report
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
			<span class="ln-display-box float-left cs-width-180 top-pull-7 right-push-30">
				<h3 class="large nobold default-text-font-bold">By Group</h3>
				<select name="cgroup" id="cgroup" class="nopads no-back-black">
					<option value="<?php echo $groupid; ?>" selected="selected"><?php echo $groupname; ?></option>
					<?php echo $get_complimentary; ?>
				</select>
			</span>
		
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $startdate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $endate; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
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
		<div>
			<div id="section-to-print" class="nc-width-100">
				
				<?php
					
					$var_amt = array();
					$sum_group_amt = array("group"=>array());

					$tbLOt = '<table cellpadding="3" cellspacing="0">';
					$tbLCt = '</table>';

					$ths = array(
						"booking number"=>"booking_number-pkey-ign",
						"group"=>"bill_to-compfx-sef",
						"check-in"=>"checkin_date-nokey-df",
						"check-out"=>"checkout_date-nokey-df",
						"total amount"=>"bill-billfx-nf",
					);

					$setH = rowHeader($ths);
					$tbl = $tbL130;

					$startnumbr = 0; $keywords = "";
					$startdate = ""; $endate = "";

					if(isset($_POST['cgroup']) && !empty($_POST['cgroup'])) {
						$keywords .= " AND bill_to={$_POST['cgroup']}";
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$startdate = date('d-m-Y',strtotime($server_get_date));
						$endate = date('d-m-Y',strtotime($server_get_date));
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$startdate = date('d-m-Y',strtotime($server_get_date));
						$endate = date('d-m-Y',strtotime($server_get_date));
					}

					?>

						<div class="bottom-push-15" align="center">
							<div class="cs-width-100 bottom-push-10 noscroll">
								<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
							</div>
							<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
							<h3 class="large nobold default-text-font-bold nomargin">Complimentary Bookings Report (between <?php echo $startdate; ?> and <?php echo $endate; ?>)</h3>
						</div>

					<?php

					$queryset = "booking_type='complimentary' AND deletedata=0".$keywords;

					$dataset = "booking_number,bill_to,checkin_date,checkout_date,datelogged";
					$result = pfetch($dataset,$tbl,$queryset);
					$setD = fetch_array_record($result,$ths,$jsf='jsxView');

					$datasheet = $tbLOt;
					$datasheet .= $setH;
					$datasheet .= $setD;
					$datasheet .= $tbLCt;

					echo $datasheet;

					/*$cbl = array("tbl"=>$tbL33,"col"=>"name","origin"=>"db");
					$extdata['bill_to'] = $cbl;

					$queryset = "t1.booking_type='complimentary' AND t1.deletedata=0".$keywords;
					$dataset = "SELECT t1.booking_number, t1.checkin_date, t1.checkout_date, t1.bill_to, SUM(t2.room_amount + t2.tax_amount + t2.consumption_tax_amount + t2.service_charge) AS 'totalamount' FROM {$tbL130} t1, {$tbL134} t2 WHERE t1.booking_number=t2.booking_number AND ".$queryset;

					$keys = array(
						"booking_number"=>"(fx)booking no.",
						"checkin_date"=>"(df)check-in",
						"checkout_date"=>"(df)check-out",
						"bill_to"=>"group",
						"totalamount"=>"(nf)total amount &#8358;"
					);

					$format = array(
						"grid",
						"use-base-data"
					);

					$datasheet = data_row_dpl2($dataset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;*/

				?>

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
		var numbr = Math.round((Math.random() * 10000000) - 1);
		crframe(key,numbr,'reservations');
	}

</script>