<?php
	
	#create table
	createDatabasetable($var_tbl_113);

	$tbl = $mtbL5;
	
	$querycheck = "deletedata=0 AND status='Active'";
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#pagination buttons
	$paginate = data_pagenation(25,0,$totalcount);

	$curpage = isset($_GET['pg']) ? $_GET['pg'] : 0;
	$pgstart = isset($_GET['start']) ? $_GET['start'] : 0;
	$pglimit = isset($_GET['limit']) ? $_GET['limit'] : 25;
	
	$startnumbr = $pgstart;

	#keyword search
	$keywords = isset($_POST['search']) ? " AND (itemcode LIKE '%{$_POST['search']}%' OR item LIKE '%{$_POST['search']}%') " : " ORDER BY item ASC";

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
					<div class="ln-display-box float-right nc-width-30 alignrt" title="Click to search">
						<a href="javascript: void(0)" class="dark-black-font" onclick="wgtfsubmit('sform','')"><b class="mbri-right"></b></a>
					</div>
					<div class="block-element new-line-space">
					</div>
				</form>
			</div>
		</span>
		<span class="ln-display-box float-right top-pull-7">
			<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="jsForm()" title="Click to add information">Add Item</a>
			<?php if(isset($allowMcChangeStatus) && $allowMcChangeStatus == 200) { ?><a href="javascript:void(0)" class="left-push-5 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="wgtfsubmit('datasheet','item-status')" title="Change status to unlist item">Status</a><?php } if(isset($allowMcDelete) && $allowMcDelete == 200) { ?><a href="javascript:void(0)" class="left-push-5 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 black-white-state xsml-rounded-button ft-xsml-size nunito-semibold" onclick="wgtfsubmit('datasheet','archive')" title="Remove record">Delete</a><?php } ?>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<div class="x-scroll">
		<div class="<?php echo $xwidth; ?>">
			<?php
				
				$queryset = "deletedata=0 AND status='Active' ".$keywords." LIMIT {$pgstart},{$pglimit}";

				$keys = array(
					"itemcode"=>"code",
					"item"=>"item",
					"buying_unit"=>"stock-in method",
					"selling_unit"=>"selling method",
					"minimum_stock"=>"re-order level",
					"maximum_stock"=>"maximum stock"
				);

				$format = array(
					"grid",
					"form-ctrl",
					"allow-view",
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
		datastring.tip = "Creation of new stock item";
		datastring.element = "html/item.html";
		
		xform('htmlform');
	}

	
	function jsEdit(id) {
		
		datastring.process = "update";
		datastring.tip = "Changing information for master data item";
		datastring.element = "html/item.html";
		
		xform('htmlform');

		wparams.tbl = "<?php echo $tbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var ajaxresult = JSON.parse(response);

			var stopIf = setInterval(function() {
				if(document.getElementById('datau')) {
					
					htmlpassval(id,'datau');
					htmlpassval(ajaxresult.datastring[0].item,'wgtf1');
					htmlpassval(ajaxresult.datastring[0].minimum_stock,'wgtf9');
					htmlpassval(ajaxresult.datastring[0].maximum_stock,'wgtf10');
					htmlpassval('Apply Changes','submitbutton');

					document.getElementById('wgtf6').removeAttribute('required');
					document.getElementById('wgtf7').removeAttribute('required');
					document.getElementById('wgtf8').removeAttribute('required');
					document.getElementById('wgtf8a').removeAttribute('required');
					document.getElementById('wgtf9').removeAttribute('required');
					document.getElementById('wgtf10').removeAttribute('required');
					document.getElementById('wgtf11a').removeAttribute('required');
					document.getElementById('wgtf11b').removeAttribute('required');
					document.getElementById('wgtf12').removeAttribute('required');
					chgclass('item-cost-price','noshow');

					clearInterval(stopIf);
				}
			},500);
		}
	}


	function jsView(id) {

		datastring.process = "view";
		datastring.tip = "Product / item stock general analysis";
		
		xform('nohtmlform');

		wparams.tbl = "<?php echo $tbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		suggestions.splice(0,suggestions.length);
		arrygets.splice(0,arrygets.length);

		idget_val('<?php echo $tbl; ?>',id,'id','item','scalar');
		
		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var stopAfter = setInterval(() => {
				if(document.getElementById('fbox-content')) {
					writeObjheader('fbox-content','<h3 class="large nobold alignct">Previewing Content</h3>');
					clearInterval(stopAfter);

					var htmlresult, ajaxresult = JSON.parse(response);
					var arry = ajaxresult.datastring, data = arry[0];

					//var item_category='', item_sub_category='', item_group='';

					setTimeout(() => { idget_val('<?php echo $mtbL2; ?>',data.categoryid,'id','category','scalar'); },1500);
					setTimeout(() => { idget_val('<?php echo $mtbL3; ?>',data.subcategoryid,'id','subcategory','scalar'); },3000);
					setTimeout(() => { idget_val('<?php echo $mtbL4; ?>',data.itemgroupid,'id','groupname','scalar'); },4000);

					
					setTimeout(() => { var fbu = {"arryname":"uoms","keys":data.buying_unit}; wgtarrykey(fbu); },500);
					if(data.selling_unit && data.selling_unit > 0) { setTimeout(() => { var fsu = {"arryname":"uoms","keys":data.selling_unit}; wgtarrykey(fsu); },1500); }

					var expiry;

					if(data.isexpire == 'No') { expiry = "Never Expire"; }
					else if(data.isexpire == 'Yes') { expiry = data.expiry_date; }

					setTimeout(() => {
					
						htmlresult = '';
						htmlresult += '<h3 class="large nobold default-text-font-bold bottom-pull-7">'+suggestions[0]+'</h3>';
						htmlresult += '<div class="sided-box bottom-push-30">';
						htmlresult += '<ul>';
						htmlresult += '<li id="tab-1" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor" onclick="changetab(this.id)">Overview</li>';
						htmlresult += '<li id="tab-2" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Supplier</li>';
						htmlresult += '<li id="tab-3" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Purchase Statistics</li>';
						htmlresult += '<li id="tab-4" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Variation</li>';
						htmlresult += '<li id="tab-5" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Movement</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-1" class="sided-box xfadeout motion-x">';
						htmlresult += '<ul>';
						htmlresult += '<li class="nc-width-35 right-pull-30 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[1]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Sub Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[2]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Group</h4>';
						htmlresult += '<h4 class="xlarge nobold">N/A</h4>';
						//htmlresult += '<h4 class="xlarge nobold">'+suggestions[3]+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-35 right-pull-30 left-pull-20 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Buying Unit</h4>';
						htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_bu+' Pieces in 1 '+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Selling Unit</h4>';
						if(arrygets[1] != 'undefined') {
							htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[1]+'</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_su+' '+arrygets[1]+' in 1 piece</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom">Formula: '+data.calc_formular+'</h4>';
						} else {
							htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">N/A</h4>';
						}
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-30 left-pull-20">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Expiry Date</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+expiry+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Minimum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.minimum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Maximum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.maximum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Cost Center Item</h4>';
						htmlresult += '<h4 class="xlarge nobold">'+data.iscost_center+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-2" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-3" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-4" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-5" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						writeObjheader('fbox-content',htmlresult);

					},5000);
				}
			},1000);
		}
	}

	function changetab(id) {
		var i;
		for(i=1; i <= 5; i++) {
			if('tab-'+i == id) {
				chgclass(id,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor');
				chgclass('in-'+id,'sided-box xfadeout motion-x');
			} else {
				chgclass('tab-'+i,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor');
				chgclass('in-tab-'+i,'noshow xfadein motion-x');
			}
		}

		if(id == 'tab-2' || id == 'tab-3' || id == 'tab-4' || id == 'tab-5') {
			writeObjheader('in-'+id,'<h4 class="large nobold dark-grey-font alignct">Looking for record..</h4>');
		}

		if(id == 'tab-2') {

		}
	}

</script>