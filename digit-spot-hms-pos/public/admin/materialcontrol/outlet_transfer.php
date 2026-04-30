<?php
	
	$tbl = $mtbL23;
	
	$querycheck = "deletedata=0";
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#pagination buttons
	$paginate = data_pagenation(50,0,$totalcount);

	$curpage = isset($_GET['pg']) ? $_GET['pg'] : 0;
	$pgstart = isset($_GET['start']) ? $_GET['start'] : 0;
	$pglimit = isset($_GET['limit']) ? $_GET['limit'] : 50;
	
	$startnumbr = $pgstart;

	#keyword search
	$keywords = "";

	if(isset($_POST['items']) && !empty($_POST['items'])) {
		//$keywords .= " AND itemid={$_POST['items']}";
		$keywords .= " AND frompos={$_POST['items']}";
	}

	if((isset($_POST['startdate']) && !empty($_POST['startdate'])) && (isset($_POST['endate']) && !empty($_POST['endate']))) {
		$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['endate']}'";
	}

	$wscreen = mediaQuery();
	if(isset($wscreen) && $wscreen == 1) { $xwidth="cs-width-1000"; }
	elseif(isset($wscreen) && $wscreen == 2) { $xwidth="nc-width-100"; }

	#get all stores
	/*$xtbl = $tbL16; $xcol = "itemcode"; $xopttext = "item";
	$wget_item_sql = "SELECT * FROM {$mtbL23} WHERE deletedata=0";
	$wget_item = html_db_select($wget_item_sql,'itemid','itemid');*/
	$xtbl = $tbL14; $xcol = "id"; $xopttext = "posname";
	$wget_item_sql = "SELECT * FROM {$mtbL23} WHERE deletedata=0";
	$wget_item = html_db_select($wget_item_sql,'frompos','frompos');

?>
<span class="float-right right-pull-30"><input type="button" value="Print History" onclick="printh()"></span>
<h3 class="large nobold steel-blue-font alignlt left-pull-20">Outlet Stock Transfer History</h3>

<div class="white-theme top-pull-20 right-pull-20 bottom-pull-7 left-pull-20 x-scroll">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left cs-width-180 right-pull-30">
			<div class="float-left top-pull-7 right-push-5"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>
			<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
		</span>
		<span class="ln-display-box float-left cs-width-250 cs-height-35 grey-1-theme xsml-rounded-button top-pull-7 left-pull-10 right-pull-10 noscroll">
			<?php if(isset($paginate) && !empty($paginate)) { echo $paginate; } else { ?><select class="nopads no-back-black"></select><?php } ?>
		</span>
		<span class="ln-display-box float-left cs-width-500 cs-height-35 left-pull-10 noscroll">
			<div class="nc-height-100 white-grey-state box-border-thick xsml-rounded-button top-pull-7 right-pull-10 left-pull-10 motion">
				<form action="" method="post" autocomplete="off" id="sform" class="nomargin nopads">
					<div class="ln-display-box float-left nc-width-40 right-pull-10">
						<select name="items" id="items" class="nopads no-back-black">
							<option value="" selected>Search by outlet?</option>
							<?php echo $wget_item; ?>
						</select>
					</div>
					<div class="ln-display-box float-left nc-width-25">
						<input type="date" name="startdate" id="startdate" value="<?php echo $server_get_date; ?>" class="nopads no-back-black" title="Date from">
					</div>
					<div class="ln-display-box float-left nc-width-25">
						<input type="date" name="endate" id="endate" value="<?php echo $server_get_date; ?>" class="nopads no-back-black" title="Date to">
					</div>
					<div class="ln-display-box float-right nc-width-10 alignrt" title="Click to search..">
						<a href="javascript: void(0)" class="dark-black-font" onclick="wgtfsubmit('sform','')"><b class="mbri-right"></b></a>
					</div>
					<div class="block-element new-line-space">
					</div>
				</form>
			</div>
		</span>
		<span class="ln-display-box float-right top-pull-7">
			&nbsp;
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<div class="x-scroll">
		<div id="section-to-print" class="<?php echo $xwidth; ?>">
			<span id="rvh-h" class="noshow motion">
				<h1 class="large nobold"><?php echo _LONG_NAME; ?></h1>
				<h3 class="large nobold">Outlet-to-outlet Stock Transfer History</h3>
			</span>
			<?php
				
				$queryset = "deletedata=0".$keywords." LIMIT {$pgstart},{$pglimit}";

				$keys = array(
					"frompos"=>"from outlet",
					"topos"=>"to outlet",
					"itemid"=>"item",
					"qty_required"=>"qty request",
					"requestby"=>"initiator",
					"acknowledgeby"=>"acknowledge by",
					"tr_status"=>"status",
					"datelogged"=>"(df)date"
				);

				$format = array(
					"grid"
				);

				$result = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				echo $result;
			?>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>
	
	function printh() {
		chgclass('rvh-h','motion');
		setTimeout(() => { window.print(); },1000);
		setTimeout(() => { chgclass('rvh-h','noshow motion'); },2000);
	}

</script>