<?php
$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';


?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; <b class="default-text-font-bold nobold">Rebate Report</b>: here you can see rebate details
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
			<input type="hidden" name="reporting" value="post">
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="text" name="endate" id="endate" placeholder="End Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
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
		<div>
			<div id="section-to-print" class="cs-width-1200">
				
				<?php
					
					if(isset($_POST['reporting']) && $_POST['reporting'] === 'post') {
						
						$tbl = $tbL163;

						$startdate = date('d-m-Y',strtotime($_POST['startdate']));
						$endate = date('d-m-Y',strtotime($_POST['endate']));

						$keywords = " AND transaction_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";

						?>

							<div class="bottom-push-15" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold">Rebate Report Between <?php echo $startdate; ?> & <?php echo $endate; ?></h3>
							</div>

						<?php

						$queryset = "deletedata=0 ".$keywords." ORDER BY id DESC";

						$keys = array(
							"rebate_no"=>"(fx)sequence no.",
							"rebate_type"=>"rebate type",
							"guest_name"=>"guest name",
							"amount"=>"(nf)amount (&#8358;)",
							"transaction_date"=>"(df)transaction date",
							"status"=>"status",
							"approval_status"=>"approval status",
							"userid"=>"created by"
						);

						$format = array(
							"grid",
							"form-ctrl",
							"use-base-data"
						);

						$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
						echo $datasheet;
						
					}

				?>

				<div class="cs-height-50"></div>

			</div>
		</div>
	</div>
</div>

<script>

	function jsForm() {
		document.getElementById('reportform').submit();
	}

	function jsxView(key) {
		popmodalframe('frontdesk','rebate_detail',key,0,1000,2500);
	}

</script>