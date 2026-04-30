<?php
	
	#create table
	createDatabasetable($var_tbl_151);

	$tbl = $mtbL19;
	$xtbl = $mtbL5;
	
	$querycheck = "deletedata=0 AND balance > 0";
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#pagination buttons
	$paginate = data_pagenation(25,0,$totalcount);

	$curpage = isset($_GET['pg']) ? $_GET['pg'] : 0;
	$pgstart = isset($_GET['start']) ? $_GET['start'] : 0;
	$pglimit = isset($_GET['limit']) ? $_GET['limit'] : 25;
	
	$startnumbr = $pgstart;

	#keyword search
	$keywords = !empty($_POST['search']) ? " AND (itemcode REGEXP '{$_POST['search']}' OR item REGEXP '{$_POST['search']}')" : "";

	if(isset($_POST['storage']) && !empty($_POST['storage'])) {
		$keyw = " AND storageid={$_POST['storage']}";
	} else {
		$keyw = "";
	}

	$wscreen = mediaQuery();
	if(isset($wscreen) && $wscreen == 1) { $xwidth="cs-width-1000"; }
	elseif(isset($wscreen) && $wscreen == 2) { $xwidth="cs-width-1500"; }

	#get all stores
	$wget_store_sql = "SELECT * FROM {$tbL123} WHERE deletedata=0";
	$wget_store = html_db_select($wget_store_sql,'id','store_name');

?>

<div class="white-theme top-pull-20 right-pull-20 bottom-pull-7 left-pull-20 x-scroll">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left cs-width-180 right-pull-30">
			<div class="float-left top-pull-7 right-push-5"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>
			<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
		</span>
		<span class="ln-display-box float-left cs-width-250 cs-height-35 grey-1-theme xsml-rounded-button top-pull-7 left-pull-10 right-pull-10 noscroll">
			<?php if(isset($paginate) && !empty($paginate)) { echo $paginate; } else { ?><select class="nopads no-back-black"></select><?php } ?>
		</span>
		<span class="ln-display-box float-left cs-width-400 cs-height-35 left-pull-10 noscroll">
			<div class="nc-height-100 white-grey-state box-border-thick xsml-rounded-button top-pull-7 right-pull-10 left-pull-10 motion">
				<form action="" method="post" autocomplete="off" id="sform" class="nomargin nopads">
					<div class="ln-display-box float-left nc-width-50 right-pull-10">
						<select name="storage" id="storage" class="nopads no-back-black">
							<option value="" selected>All Stores</option>
							<?php echo $wget_store; ?>
						</select>
					</div>
					<div class="ln-display-box float-left nc-width-40">
						<input type="text" name="search" id="search" placeholder="Search by keywords.." class="nopads no-back-black">
					</div>
					<div class="ln-display-box float-right nc-width-10 alignrt" title="Click to search">
						<a href="javascript: void(0)" class="dark-black-font" onclick="wgtfsubmit('sform','')"><b class="mbri-right"></b></a>
					</div>
					<div class="block-element new-line-space">
					</div>
				</form>
			</div>
		</span>
		<span class="ln-display-box float-right top-pull-7">
			<?php 
				/*if(isset($allowStartupStock) && $allowStartupStock == 200) {
					?>
						<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 rounded-button blue-white-state" onclick="jsStock()">Start-up Stock</a>
					<?php
				}*/
			?>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<div class="x-scroll">
		<div class="<?php echo $xwidth; ?>">
			<?php
				
				if(!empty($keywords)) {
					
					$sqldata = "SELECT id FROM {$mtbL5} WHERE deletedata=0".$keywords;
					$search_result = idget_data($sqldata);

					$addtokey = "";
					
					if(is_array($search_result)) {
						foreach($search_result as $key => $val) { $addtokey .= $val['id'].','; }
						$addtokey = substr_replace($addtokey,'',-1,1);
					}

					$queryset = "deletedata=0 AND itemid IN({$addtokey})".$keyw;

				} else {
					$queryset = "deletedata=0 AND balance > 0".$keyw." LIMIT {$pgstart},{$pglimit}";
				}

				$keys = array(
					"storageid"=>"store",
					"categoryid"=>"category",
					"subcategoryid"=>"subcategory",
					"itemid"=>"(fx)item",
					"uom"=>"uom",
					"delivery_date"=>"(df)last modified",
					"delivery_note"=>"note",
					"stockin"=>"received stock",
					"stockout"=>"transferred stock",
					"balance"=>"available stock",
					"userid"=>"by user",
					"datelogged"=>"(df)stock date"
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

	function jsxView(id) {

		datastring.process = "view";
		datastring.tip = "Product / item stock general analysis";
		
		xform('nohtmlform');

		wparams.tbl = "<?php echo $xtbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		suggestions.splice(0,suggestions.length);
		arrygets.splice(0,arrygets.length);

		idget_val('<?php echo $xtbl; ?>',id,'id','item','scalar');
		
		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var stopAfter = setInterval(() => {
				if(document.getElementById('fbox-content')) {
					writeObjheader('fbox-content','<h3 class="large nobold alignct">Previewing Content</h3>');
					clearInterval(stopAfter);

					var htmlresult, ajaxresult = JSON.parse(response);
					var arry = ajaxresult.datastring, data = arry[0];

					//var item_category='', item_sub_category='', item_group='';

					setTimeout(() => { idget_val('<?php echo $mtbL2; ?>',data.categoryid,'id','category','scalar'); },1000);
					setTimeout(() => { idget_val('<?php echo $mtbL3; ?>',data.subcategoryid,'id','subcategory','scalar'); },2000);
					setTimeout(() => { idget_val('<?php echo $mtbL4; ?>',data.itemgroupid,'id','groupname','scalar'); },3000);

					
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

					},4000);
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


	function jsStock() {
		popmodalframe('materialcontrol','startup_stock',200,0,1000,2000);
	}

</script>