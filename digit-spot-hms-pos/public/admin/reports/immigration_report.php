<?php
$smdl = "reports"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the list of <u>immigration</u> report
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
			<!--<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" onfocus="textodate(this.id)" class="nopads no-back-black" value="<?php //if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" onfocus="textodate(this.id)" class="nopads no-back-black" value="<?php //if(isset($_POST['endate'])) { echo $_POST['endate']; } else { echo $server_get_date; } ?>">
			</span>-->
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Today's Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" class="nopads no-back-black">
				<input type="hidden" name="endate" id="endate" placeholder="End Date?" value="<?php echo $server_get_date; ?>" class="nopads no-back-black">
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
		<div id="section-to-print" class="nc-width-100">

			<?php
				
				$tbl = $tbL102;

				$startnumbr = 0;
				$shift_name = ""; $keywords = "";

				if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
					//$keywords .= " AND t1.datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
					$keywords = "";
					$startdate = date('d-m-Y',strtotime($_POST['startdate']));
					$endate = date('d-m-Y',strtotime($_POST['endate']));
				} else {
					//$keywords .= " AND t1.datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					$keywords .= "";
					$startdate = date('d-m-Y',strtotime($server_get_date));
					$endate = date('d-m-Y',strtotime($server_get_date));
				}

			?>

			<div class="bottom-push-15" align="center">
				<div class="cs-width-100 bottom-push-10 noscroll">
					<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
				</div>
				<h2 class="xlarge nobold default-text-font-bold nomargin"><?php echo _LONG_NAME; ?></h2>
				<h3 class="large nobold default-text-font-bold nomargin"><?php echo $hotel_address; ?></h3>
				<h3 class="large nobold default-text-font-bold">Immigration Report (between <?php echo $startdate; ?> and <?php echo $endate; ?>)</h3>
			</div>

			<?php

				$queryset = "t1.deletedata=0".$keywords." ORDER BY t1.id DESC";
				$dataset = "SELECT t1.salutation,t1.fname,t1.lname,t1.gender,t1.mobile,t1.address,t1.nationality,t1.occupation,t1.employer,t1.passport_no,t1.next_destination,t1.visa_validity,t2.roomid,t2.checkin_date,t2.checkout_date FROM {$tbL102} t1, {$tbL127} t2 WHERE t1.id=t2.customerid AND t2.status IN('CheckedIn') AND ((t1.passport_no != '' AND t1.visa_validity != '') OR (t1.nationality NOT IN('Nigeria','',NULL))) AND ".$queryset;

				$allow_gridlines = true;

				//t1.immi_status,t1.allien_regno,t1.country_date_checkin,
				/*"immi_status"=>"immi status",
					"allien_regno"=>"aliens reg no.",
					"country_date_checkin"=>"date of arrival",
					*/

				$keys = array(
					"salutation"=>"title",
					"fname"=>"firstname",
					"lname"=>"lastname",
					"gender"=>"sex",
					"mobile"=>"mobile no.",
					"address"=>"contact addr.",
					"nationality"=>"nationality",
					"occupation"=>"occupation",
					"employer"=>"work place",
					"passport_no"=>"passport no.",
					"next_destination"=>"next destination",
					"visa_validity"=>"visa validity",
					"roomid"=>"room no.",
					"checkin_date"=>"(df)arr. date",
					"checkout_date"=>"(df)dept. date"
				);

				$format = array(
					"grid"
				);

				//$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				$datasheet = data_row_dpl2($dataset,$keys,$format,$startnumbr,$extdata);
				echo $datasheet;

			?>
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

</script>