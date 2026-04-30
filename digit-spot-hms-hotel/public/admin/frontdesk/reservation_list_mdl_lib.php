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
		&nbsp; Note: here you can see the list of reservations
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
				<h3 class="large nobold default-text-font-bold">Booking Type</h3>
				<select name="bookingtype" id="bookingtype" class="nopads no-back-black" onclick="getcspg(this.value)">
					
					<?php if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])): ?>
						<option value="<?php echo $_POST['bookingtype']; ?>" selected="selected"><?php echo $_POST['bookingtype']; ?></option>
					<?php else: ?>
						<option value="" selected="selected">All</option>
					<?php endif; ?>

					<option value="Individual">Individual</option>
					<option value="Corporate">Corporate</option>
					<option value="Complimentary">Complimentary</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Corporate</h3>
				<select name="cspg" id="cspg" class="nopads no-back-black">
					<?php if(isset($_POST['cspg']) && !empty($_POST['cspg'])): $cspg = idget_data($tbL58,$_POST['cspg'],'name'); ?>
						<option value="<?php echo $_POST['cspg']; ?>" selected="selected"><?php echo $cspg; ?></option>
					<?php else: ?>
						<option value="" selected="selected">N/A</option>
					<?php endif; ?>
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
			<?php if(isset($_POST['startdate']) && isset($_POST['endate'])): ?>
				<div class="cs-width-350 bottom-push-30">
					<input type="text" id="wfinder" placeholder="Type word to look-up reservation?" onkeyup="wfinder(this.value)">
				</div>
			<?php endif; ?>

			<div id="section-to-print" class="motion">
				<?php
					
					if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
						
						$tbl = $tbL127;

						$startnumbr = 0;
						$shift_name = ""; $keywords = "";

						if(isset($_POST['bookingtype']) && !empty($_POST['bookingtype'])) {
						
							if(isset($_POST['cspg']) && !empty($_POST['cspg'])) {
								$for_bkg = "SELECT booking_number FROM {$tbL130} WHERE booking_type='{$_POST['bookingtype']}' AND bill_to_g='{$_POST['cspg']}' AND reservation IN('Reserving')";
							} else {
								$for_bkg = "SELECT booking_number FROM {$tbL130} WHERE booking_type='{$_POST['bookingtype']}' AND reservation IN('Reserving')";
							}
							
							$bkg = mysqli_data_array('assoc',$for_bkg);
							
							if(is_array($bkg)) { foreach($bkg as $key => $val) {
								$get_bkgnos .= "'".$val['booking_number']."',"; }
								
								$get_bkgno = substr_replace($get_bkgnos,'',-1,1);
								$get_bkgnos = "";

								$keywords .= " AND booking_number IN({$get_bkgno})";
							}
						}

						$keywords .= " AND checkin_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
						$queryset = "deletedata=0 AND status='Reserved'".$keywords." ORDER BY id DESC";

						$force_tabs = array(
							"mobile"=>array("tbl"=>$tbL102,"key"=>"id","val"=>"customerid","th"=>"guest mobile"),
							"booking_type"=>array("tbl"=>$tbL130,"key"=>"booking_number","val"=>"booking_number","th"=>"booking type"),
							"bill_to"=>array("tbl"=>$tbL130,"key"=>"booking_number","val"=>"booking_number","th"=>"corporate")
						);

						$keys = array(
							"booking_number"=>"(fx)booking no.",
							"customerid"=>"guest name",
							"room_type_id"=>"room type",
							"roomid"=>"room no.",
							"checkin_date"=>"(df)from date",
							"checkout_date"=>"(df)to date",
							"datelogged"=>"(df)booked on",
							"userid"=>"created by"
						);

						$format = array(
							"grid"
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

	function wfinder(value) {
		if(value && value !== null && value.length >= 3) {
			
			var table = document.getElementsByTagName('table')[0];
			var trs = table.getElementsByTagName('tr');
			
			var th = '<tr>'+trs[0].innerHTML+'</tr>';
			var trFound = '', trNfound = '';

			for(var i=1; i<trs.length; i++) {
				var tds = trs[i].innerHTML;
				if(tds.indexOf(value) > -1) {
					trFound += '<tr class="yellow-theme">'+trs[i].innerHTML+'</tr>';
				}

				if(tds.indexOf(value) == -1) {
					trNfound += '<tr>'+trs[i].innerHTML+'</tr>';
				}
			}

			document.getElementById('section-to-print').innerHTML = '<form action="" method="post" id="datasheet"><input type="hidden" name="ftask" id="ftask"><table cellpadding="0" cellspacing="0">'+th+trFound+trNfound+'</table></form>';
		}
	}


	function getcspg(val) {

		var vhtml;

		if(val == 'Corporate') {
			sqldatastring.sql = "SELECT a.bill_to_g, b.name FROM booking_invoice_tbl a, cspg_tbl b WHERE a.reservation IN('Reserving') AND a.bill_to_g=b.id AND a.deletedata=0 GROUP BY a.bill_to_g ORDER BY b.name ASC";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<option value="" selected>All</option>';
				
				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].bill_to_g+'">'+data[i].name+'</option>';
				}

				writeObjheader('cspg',vhtml);
			}

		} else {
			vhtml = '<option value="" selected>N/A</option>';
		}
	}


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