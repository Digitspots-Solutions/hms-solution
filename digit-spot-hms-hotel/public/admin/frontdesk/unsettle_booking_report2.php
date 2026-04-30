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
		&nbsp; Note: here you can see the list of unsettled bookings
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
				<h3 class="large nobold default-text-font-bold">By Type</h3>
				<select name="bookingtype" id="bookingtype" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<option value="Individual">Individual</option>
					<option value="Corporate">Corporate</option>
					<option value="Complimentary">Complimentary</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-30">
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
				
				<div class="bottom-push-15" align="center">
					<div class="cs-width-100 bottom-push-10 noscroll">
						<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
					</div>
					<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
					<h3 class="large nobold default-text-font-bold">Unsettled Bookings Report</h3>
				</div>

				<?php
					
					$var_amt = array();

					$tbLOt = '<table cellpadding="3" cellspacing="0">';
					$tbLCt = '</table>';

					$ths = array(
						"booking number"=>"booking_number-pkey-ign",
						"primary guest name"=>"guestname-guestfx-ign",
						"total amount"=>"bill_amount-billfx-nf",
						"amount paid"=>"amount-payfx-nf",
						"balance amount"=>"bal-balfx-nf"
					);

					$setH = rowHeader($ths);

					$tbl = $tbL130;

					$startnumbr = 0;
					$shift_name = ""; $keywords = "";

					if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])) {
						$keywords .= " AND booking_type='{$_POST['bookingtype']}'";
					}

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
					}

					//$queryset = "deletedata=0 AND booking_type IN('individual') AND reservation='Checking Out' AND settled_booking=0".$keywords;
					$queryset = "deletedata=0 AND reservation='Checking Out' AND settled_booking=0".$keywords;

					$dataset = "booking_number,booking_type,bill_to,reservation,userid";
					$result = pfetch($dataset,$tbl,$queryset);
					$setD = fetch_array_record($result,$ths,$jsf='jsxView');

					$datasheet = $tbLOt;
					$datasheet .= $setH;
					$datasheet .= $setD;
					$datasheet .= $tbLCt;

					echo $datasheet;
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