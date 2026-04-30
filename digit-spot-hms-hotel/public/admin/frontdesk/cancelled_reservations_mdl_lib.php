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
		&nbsp; Note: here you can get the list of cancelled reservations or bookings
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
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Type</h3>
				<select name="bookingtype" id="bookingtype" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<option value="Individual">Individual</option>
					<option value="Corporate">Corporate</option>
					<option value="Complimentary">Complimentary</option>
				</select>
			</span>
			<!--<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Shift</h3>
				<select name="shift" id="shift" class="nopads no-back-black">
					<option value="" selected="selected">All</option>
					<?php //echo $get_shifts; ?>
				</select>
			</span>-->
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
		<div class="cs-width-1200">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL127;

					$startnumbr = 0;
					$shift_name = ""; $keywords = "";

					if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])) {
					
						$for_bkg = "SELECT booking_number FROM {$tbL130} WHERE booking_type='{$_POST['bookingtype']}'";
						$bkg = mysqli_data_array('assoc',$for_bkg);
						
						if(is_array($bkg)) { foreach($bkg as $key => $val) {
							$get_bkgnos .= "'".$val['booking_number']."',"; }
							
							$get_bkgno = substr_replace($get_bkgnos,'',-1,1);
							$get_bkgnos = "";

							$keywords .= " AND booking_number IN({$get_bkgno})";
						}
					}

					/*if(isset($_POST['shift']) && !empty($_POST['shift'])) {
						
						$for_shift = "SELECT userid FROM {$tbL23} WHERE shiftid='{$_POST['shift']}'";
						$shift = mysqli_data_array('assoc',$for_shift);

						if(is_array($shift)) { foreach($shift as $key => $val) {
							$get_users .= $val['userid'].","; }
							
							$get_user = substr_replace($get_users,'',-1,1);
							$get_users = "";

							$keywords .= " AND checkin_byuser IN({$get_user})";
						}

						$shift_name = idget_data($tbL20,$_POST['shift'],'shiftname');
					}*/

					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						$keywords .= " AND cancel_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
					} else {
						$keywords .= " AND cancel_date BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					}

					/*if(!empty($shift_name)) { echo '<h3 class="large nobold default-text-font-bold">'.$shift_name.'</h3>'; }
					else { echo '<h3 class="large nobold default-text-font-bold">All Shifts</h3>'; }*/

					$queryset = "deletedata=0 AND status='Cancelled'".$keywords." ORDER BY id DESC";

					$force_tabs = array(
						"booking_type"=>array("tbl"=>$tbL130,"key"=>"booking_number","val"=>"booking_number","th"=>"booking type")
					);

					$keys = array(
						"booking_number"=>"(fx)booking no.",
						"customerid"=>"guest name",
						"room_type_id"=>"room type",
						"roomid"=>"room no.",
						"checkin_date"=>"(df)date booked",
						"cancel_date"=>"(df)date cancelled",
						"cancel_byuser"=>"cancelled by"
					);

					$format = array(
						"grid"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
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