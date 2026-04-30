<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$get_counters = select_dt_fetch('deletedata',0,$tbL19,'id','countername');

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can see the list of counter transactions
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
				<h3 class="large nobold default-text-font-bold">By Counter Name</h3>
				<select name="counter" id="counter" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php echo $get_counters; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
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
				<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>
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
				<?php
					
					$tbl = $tbL25;

					$startnumbr = 0;
					$shift_name = ""; $keywords = ""; $date_key = "";

					if(isset($_POST['counter']) && !empty($_POST['counter'])) {
						$keywords .= " AND counterid='{$_POST['counter']}'";
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$date_key = " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
						$date_key = " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					}

					$queryset = "deletedata=0".$keywords." GROUP BY userid,counterid";
					$group_dataset = "counterid,userid";
					$result = pfetch($group_dataset,$tbl,$queryset);

					$th = array(
						"date",
						"logged (in user)",
						"counter",
						"opened at",
						"opening bal.",
						"collections",
						"refunds",
						"added amount",
						"withdrawal",
						"available bal.",
						"closing bal."
					);

					if(is_array($result)) {
						foreach($variable as $key => $val) {
							//code...
						}
					}

					//$queryset = "deletedata=0 AND userid={}";

					/*$keys = array(
						"booking_number"=>"(fx)booking no.",
						"booking_type"=>"(tc)booking type",
						"bill_type"=>"bill paid by",
						"checkin_date"=>"(df)checkin date",
						"checkout_date"=>"(df)checkout date",
						"userid"=>"created by"
					);

					$format = array(
						"grid"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
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