<?php
	
	#create table
	createDatabasetable($var_tbl_118);

	$tbl = $mtbL10;
	
	$querycheck = "deletedata=0";
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#pagination buttons
	$paginate = data_pagenation(25,0,$totalcount);

	$curpage = isset($_GET['pg']) ? $_GET['pg'] : 0;
	$pgstart = isset($_GET['start']) ? $_GET['start'] : 0;
	$pglimit = isset($_GET['limit']) ? $_GET['limit'] : 25;
	
	$startnumbr = $pgstart;

	#keyword search
	$keywords = isset($_POST['search']) ? " AND (store_name REGEXP '{$_POST['search']}' OR store_type REGEXP '{$_POST['search']}' OR store_number REGEXP '{$_POST['search']}') " : "";

	$wscreen = mediaQuery();
	if(isset($wscreen) && $wscreen == 1) { $xwidth="cs-width-1000"; }
	elseif(isset($wscreen) && $wscreen == 2) { $xwidth="nc-width-100"; }
?>

<div class="white-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 x-scroll">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left cs-width-180 right-pull-30">
			<div class="float-left top-pull-7 right-push-5"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>
			<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
		</span>
		<span class="ln-display-box float-left cs-width-250 cs-height-35 grey-1-theme xsml-rounded-button top-pull-7 left-pull-10 right-pull-10 noscroll">
			<?php if(isset($paginate) && !empty($paginate)) { echo $paginate; } else { ?><select class="nopads no-back-black"></select><?php } ?>
		</span>
		<span class="ln-display-box float-left cs-width-300 cs-height-35 left-pull-10 noscroll">
			<div class="nc-height-100 white-grey-state box-border-thick xsml-rounded-button top-pull-7 right-pull-10 left-pull-10 motion">
				<form action="" method="post" autocomplete="off" id="sform" class="nomargin nopads">
					<div class="ln-display-box float-left nc-width-70">
						<input type="text" name="search" id="search" placeholder="Search by keywords.." class="nopads no-back-black">
					</div>
					<div class="ln-display-box float-right nc-width-30 alignrt">
						<a href="javascript: void(0)" class="dark-black-font" onclick="wgtfsubmit('sform','')"><b class="mbri-right"></b></a>
					</div>
					<div class="block-element new-line-space">
					</div>
				</form>
			</div>
		</span>
		<span class="ln-display-box float-right top-pull-7">
			<a href="javascript:void(0)" class="left-push-5 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="wgtfsubmit('datasheet','pos-stock')" title="Click to view stock">Check Stock</a>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<h4 class="xlarge nobold black-font alignct"><b class="fas fa-question-circle right-push-5"></b> To view pos stock, select the checkbox and click on <u>check-stock</u> button</h4><br>
	<div class="x-scroll">
		<div class="<?php echo $xwidth; ?>">
			<?php
				
				$queryset = "deletedata=0 ".$keywords."LIMIT {$pgstart},{$pglimit}";

				$keys = array(
					"store_name"=>"pos store name",
					"store_type"=>"stock type",
					"department"=>"department"
				);

				$format = array(
					"grid",
					"form-ctrl"
				);

				$result = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				echo $result;
			?>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>
	
</script>