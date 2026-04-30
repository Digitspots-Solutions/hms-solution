<?php
	
	#create table
	createDatabasetable($var_tbl_151);

	$tbl = $mtbL19;
	$xtbl = $mtbL5;
	
	$querycheck = "deletedata=0";
	$ischecked = mysqli_data_exist($tbl,$querycheck);
	$totalcount = $ischecked['dbrows'];

	#keyword search
	$keywords = !empty($_POST['search']) ? " AND (itemcode REGEXP '{$_POST['search']}' OR item REGEXP '{$_POST['search']}')" : "";

	if((isset($_POST['storage']) && !empty($_POST['storage'])) && (empty($_POST['category']))) {
		$keyw = " AND storageid={$_POST['storage']}";
	} elseif((isset($_POST['storage']) && !empty($_POST['storage'])) && (isset($_POST['category']) && !empty($_POST['category']))) {
		$keyw = " AND storageid={$_POST['storage']} AND categoryid={$_POST['category']}";
	} else {
		$keyw = "";
	}

	$wscreen = mediaQuery();
	if(isset($wscreen) && $wscreen == 1) { $xwidth="cs-width-1000"; }
	elseif(isset($wscreen) && $wscreen == 2) { $xwidth="nc-width-100"; }

	#get all stores
	$wget_store_sql = "SELECT * FROM {$tbL123} WHERE deletedata=0";
	$wget_store = html_db_select($wget_store_sql,'id','store_name');

	#get all category
	$wget_ctg_sql = "SELECT * FROM {$tbL115} WHERE deletedata=0";
	$wget_ctg = html_db_select($wget_ctg_sql,'id','category');


	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>
<p>&nbsp;</p>
<h3 class="large nobold">* All available stock summary</h3>

<div class="white-theme top-pull-10 right-pull-20 bottom-pull-7 left-pull-20 x-scroll">
	<div class="fx-scroll-width">
		<span class="ln-display-box float-left cs-width-180 right-pull-30">
			<div class="float-left top-pull-7 right-push-5"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>
			<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
		</span>
		<span class="ln-display-box float-left cs-width-600 cs-height-35 left-pull-10 noscroll">
			<div class="nc-height-100 white-grey-state box-border-thick xsml-rounded-button top-pull-7 right-pull-10 left-pull-10 motion">
				<form action="" method="post" autocomplete="off" id="sform" class="nomargin nopads">
					<div class="ln-display-box float-left nc-width-25 right-pull-10">
						<select name="storage" id="storage" class="nopads no-back-black">
							<option value="" selected>All Stores</option>
							<?php echo $wget_store; ?>
						</select>
					</div>
					<div class="ln-display-box float-left nc-width-25 right-pull-10">
						<select name="category" id="category" class="nopads no-back-black">
							<option value="" selected>All Categories</option>
							<?php echo $wget_ctg; ?>
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
			<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
</div>
<div class="pads30" align="left">
	<div class="x-scroll">
		<div id="section-to-print" class="<?php echo $xwidth; ?>">

			<div class="bottom-push-30" align="center">
				<div class="cs-width-100 bottom-push-10 noscroll">
					<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
				</div>
				<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
				<h3 class="large nobold default-text-font-bold nomargin">Available Stock Summary Report</h3>
				<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
			</div>

			<?php
				
				if(!empty($keywords)) {
					
					$sqldata = "SELECT id FROM {$mtbL5} WHERE deletedata=0".$keywords;
					$search_result = idget_data($sqldata);

					$addtokey = "";
					
					if(is_array($search_result)) {
						foreach($search_result as $key => $val) { $addtokey .= $val['id'].','; }
						$addtokey = substr_replace($addtokey,'',-1,1);
					}

					$queryset = "balance > 0 AND deletedata=0 AND itemid IN({$addtokey})".$keyw;

				} else {
					
					$queryset = "balance > 0 AND deletedata=0".$keyw;
				}

				$sql = "SELECT * FROM {$tbl} WHERE ".$queryset;
				$data = idget_data($sql);

				$base_sumr = array();

			?>

			<table cellpadding="3" cellspacing="0">
				<tr>
					<td class="default-text-font-bold">Store</td>
					<td class="default-text-font-bold">Item</td>
					<td class="default-text-font-bold">Expiry Status</td>
					<td class="default-text-font-bold">Stock Bal.</td>
					<td class="default-text-font-bold">Last Price</td>
					<td class="default-text-font-bold">Total</td>
				</tr>

				<?php
					
					$grand_total = 0;

					if(is_array($data) && count($data) > 0) {
						
						$inventory = array(); $inventoryStack = array(); $itemstack = array();

						$storename = ""; $itemname = ""; $uomname = ""; $expiry = ""; $expiry_status = ""; $totalamount = "";

						foreach($data as $key => $val) {
							
							$storename = idget_name($val['storageid'],'store_name',$tbL123);
							$itemname = idget_name($val['itemid'],'item',$mtbL5);
							$expiry = idget_name($val['itemid'],'isexpire',$mtbL5);
							$expiry_status = ($expiry == 'Yes') ? "Expiring - ".date('d/m/Y',strtotime($val['expiry_date'])) : "Never Expired";
							$uomname = arrayget_key($uoms,$val['uom']);
							$totalamount = $val['unitprice'] * $val['balance'];

							/*$inventoryStack['storename'] = $storename;
							$inventoryStack['itemname'] = $itemname;
							$inventoryStack['expiry'] = $expiry_status;
							$inventoryStack['balance'] = number_format($val['balance'],2).' '.$uomname;
							$inventoryStack['price'] = number_format($val['unitprice'],2);
							$inventoryStack['total'] = $totalamount;*/

							if(!in_array($itemname, $itemstack)) {
								array_push($itemstack,$itemname);
								$inventoryStack[$itemname] = array($storename,$itemname,$expiry_status,number_format($val['balance'],2).' '.$uomname,number_format($val['unitprice'],2),$totalamount);
							}

							array_push($base_sumr,$val['subcategoryid']);
						}

					
						$itemstack = array_unique($itemstack);
						sort($itemstack);
						
						if(is_array($itemstack) && count($itemstack) > 0) {
							foreach($itemstack as $key) {

								$grand_total = $grand_total + $inventoryStack[$key][5];

								?>
									<tr>
										<td class="alignlt"><?php echo $inventoryStack[$key][0]; ?></td>
										<td class="alignlt"><?php echo $inventoryStack[$key][1]; ?></td>
										<td class="alignlt"><?php echo $inventoryStack[$key][2]; ?></td>
										<td class="alignlt"><?php echo $inventoryStack[$key][3]; ?></td>
										<td class="alignlt"><?php echo $inventoryStack[$key][4]; ?></td>
										<td class="alignlt"><?php echo number_format($inventoryStack[$key][5],2); ?></td>
									</tr>
								<?php
							}
						}

					}
				?>

				<tr>
					<td class="alignlt">Total</td>
					<td class="alignlt">&nbsp;</td>
					<td class="alignlt">&nbsp;</td>
					<td class="alignlt">&nbsp;</td>
					<td class="alignlt">&nbsp;</td>
					<td class="alignlt default-text-font-bold"><?php echo number_format($grand_total,2); ?></td>
				</tr>

			</table>

			<div class="cs-height-50">
			</div>

			<div align="center">
				<div class="cs-width-400">
					<h3 class="large nobold">Stock Summary for Item Subcategories</h3>
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="default-text-font-bold alignct">Particular</td>
							<td class="default-text-font-bold alignct">Total</td>
						</tr>

						<?php

							//print_r($base_sumr);

							if(is_array($base_sumr) && count($base_sumr) > 0) {

								$ex_base_sumr = array_unique($base_sumr);
								$subcategory = ""; $sub_grand_total = 0;

								foreach($ex_base_sumr as $ky) {

									$subcategory = idget_name($ky,'subcategory',$tbL116);

									$sqlx = "SELECT SUM(unitprice * balance) AS totalamount FROM {$tbl} WHERE subcategoryid={$ky}".$keyw;
									$datax = idget_data($sqlx);

									$sub_grand_total = $sub_grand_total + $datax[0]['totalamount'];

									?>
										<tr>
											<td class="alignlt"><?php echo $subcategory; ?></td>
											<td class="alignrt"><?php echo number_format($datax[0]['totalamount'],2); ?></td>
										</tr>
									<?php
								}
							}
						?>

						<tr class="grey-theme">
							<td class="alignlt"> &nbsp;Total</td>
							<td class="default-text-font-bold alignrt"><?php echo number_format($sub_grand_total,2); ?>&nbsp; </td>
						</tr>

					</table>
				</div>
			</div>

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