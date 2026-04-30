<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

createDatabasetable($var_tbl_307); //for city ledger

$update_result = '';
$post_result = '';
$htmlresult = '';

$extable = $tbL7; $extcols = "staffname"; $extkey = "id";
$get_users = select_dt_fetch('deletedata',0,$tbL160,'','chargeto');

#-----------------------------------------------------------------------------------------------------

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can see user city ledger
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
			<span class="ln-display-box float-left cs-width-150 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By User</h3>
				<select name="user" id="user" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $get_users; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold">By Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold">By End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" value="<?php echo $server_get_date; ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>
<div class="top-pull-30" align="left">
	<!--<p class="bottom-pull-20">
		<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('datasheet')">Accept Fund</a>
	</p>-->
	<div class="x-scroll">
		<div class="nc-width-100">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL160;
					
					$startnumbr = 0;
					$keywords = "";

					if(isset($_GET['user']) && !empty($_GET['user'])) {
						$keywords .= " AND userid={$_GET['user']}";
					}

					if((isset($_GET['startdate']) && !empty($_GET['startdate'])) && (isset($_GET['endate']) && !empty($_GET['endate']))) {
						$keywords .= " AND datelogged BETWEEN '{$_GET['startdate']}' AND '{$_GET['endate']}'";
					} else {
						$keywords .= " AND datelogged BETWEEN '{$server_get_date}' AND '{$server_get_date}'";
					}

					$queryset = "deletedata=0".$keywords;

					$keys = array(
						"amount"=>"(nf)amount charge &#8358;",
						"chargeto"=>"charged to",
						"detail"=>"(nl)remark",
						"userid"=>"general cashier",
						"datelogged"=>"(df)date",
						"timelogged"=>"time"
					);

					$format = array(
						"grid",
						"use-base-data",
						"allow-check-for-isprocess"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;

				?>
			</div>
		</div>
	</div>
</div>


<script>

	function jsForm(fr) {
		if(fr == 'reportform') {
			var param = {
				"user" : document.getElementById('user').value,
				"startdate" : document.getElementById('startdate').value,
				"endate" : document.getElementById('enddate').value
			};

			sessionStorage.setItem('acctgparams',JSON.stringify(param));

			setTimeout(() => {
				if(sessionStorage.getItem('acctgparams') !== null && sessionStorage.getItem('acctgparams') != 'undefined') {
					var uri,params,wp;
					uri = sessionStorage.getItem('cityuri'); params = sessionStorage.getItem('acctgparams'); wp = JSON.parse(params);
					window.location.href = uri+'&user='+wp.user+'&startdate='+wp.startdate+'&endate='+wp.endate;
				}
			},1000);

		} else if(fr == 'datasheet') {
			document.getElementById(fr).submit();
		}
	}

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	window.onload = () => {
		if(sessionStorage.getItem('cityuri') == null || sessionStorage.getItem('cityuri') == 'undefined') {
			sessionStorage.setItem('cityuri',window.location.href);
		}
	}

</script>