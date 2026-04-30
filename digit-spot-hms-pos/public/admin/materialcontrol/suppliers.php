<?php
	
	#create table
	createDatabasetable($var_tbl_109);

	$tbl = $mtbL1;
	
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
	$keywords = isset($_POST['search']) ? " AND (supplier_name REGEXP '{$_POST['search']}' OR city REGEXP '{$_POST['search']}' OR country REGEXP '{$_POST['search']}') " : "";

	$wscreen = mediaQuery();
	if(isset($wscreen) && $wscreen == 1) { $xwidth="cs-width-1000"; }
	elseif(isset($wscreen) && $wscreen == 2) { $xwidth="nc-width-100"; }
?>

<div class="white-theme top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 x-scroll">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left right-pull-30">
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
			<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="jsForm()" title="Click to add information">Add Supplier</a>
			<a href="javascript:void(0)" class="left-push-5 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 black-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="wgtfsubmit('datasheet','delete')" title="Remove record">Delete</a>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<div class="x-scroll">
		<div class="<?php echo $xwidth; ?>">
			<?php
				
				$queryset = "deletedata=0 ".$keywords."LIMIT {$pgstart},{$pglimit}";

				$keys = array(
					"supplier_name"=>"supplier",
					"mobile"=>"contact number",
					"emailaddress"=>"contact email",
					"city"=>"city",
					"country"=>"country",
					"sales_representative"=>"representative",
					"datelogged"=>"date modified"
				);

				$format = array(
					"grid",
					"form-ctrl",
					"allow-edit"
				);

				$result = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
				echo $result;
			?>
		</div>
	</div>
</div>

<input type="hidden" id="page-access" value="<?php echo $noaccess; ?>">
<div id="fbox"></div>

<script>
	
	function jsForm() {

		datastring.process = "insert";
		datastring.tip = "Creation of business supplier";
		datastring.element = "html/supplier.html";
		
		xform('htmlform');
	}

	function jsEdit(id) {
		
		datastring.process = "update";
		datastring.tip = "Changing information for supplier";
		datastring.element = "html/supplier.html";
		
		xform('htmlform');

		wparams.tbl = "<?php echo $tbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var ajaxresult = JSON.parse(response);

			var stopIf = setInterval(function() {
				if(document.getElementById('datau')) {
					clearInterval(stopIf);
					htmlpassval(id,'datau');
					htmlpassval(ajaxresult.datastring[0].supplier_name,'wgtf1');
					htmlpassval(ajaxresult.datastring[0].mobile,'wgtf2');
					htmlpassval(ajaxresult.datastring[0].emailaddress,'wgtf3');
					htmlpassval(ajaxresult.datastring[0].city,'wgtf4');
					htmlpassval(ajaxresult.datastring[0].country,'wgtf5');
					htmlpassval(ajaxresult.datastring[0].sales_representative,'wgtf6');
				}
			},500);
		}
	}


</script>