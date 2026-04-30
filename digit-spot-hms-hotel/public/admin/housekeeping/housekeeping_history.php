<?php
$smdl = "pos"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the history of housekeeping on rooms
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
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Select Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
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

				<?php
					
					$tbl = $tbL95;

					$startnumbr = 0;
					$keywords = "";

					if(isset($_POST['startdate']) && !empty($_POST['startdate'])) {
						$dated = $_POST['startdate'];
						$keywords .= " AND datelogged='{$_POST['startdate']}'";
					} else {
						$dated = $server_get_date;
						$keywords .= " AND datelogged='{$server_get_date}'";
					}

				?>
					<div class="bottom-push-30" align="center">
						<div class="cs-width-100 bottom-push-10 noscroll">
							<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
						</div>
						<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold default-text-font-bold nomargin bottom-pull-5">Housekeeping History (<?php echo date('d-m-Y',strtotime($dated)); ?>)</h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
					</div>

				<?php

					$queryset = "roomid > 0 AND assignedby > 0 AND deletedata=0".$keywords." ORDER BY id DESC";

					$keys = array(
						"roomid"=>"room no.",
						"housekeeping_stateid"=>"old status",
						"new_housekeeping_stateid"=>"new status",
						"remarks"=>"remark",
						"userid"=>"cleaner",
						"assignedby"=>"initiate by",
						"datelogged"=>"date",
						"timelogged"=>"time"
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

<div id="fbox"></div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

</script>