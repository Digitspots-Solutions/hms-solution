<?php
	
	#create table for batch
	createDatabasetable($var_tbl_147);
	createDatabasetable($var_tbl_148);

	$tbl = $mtbL18;

	//check if data exist in expense category table
	$query_type = "deletedata=0";
	$istype = mysqli_data_exist($tbl,$query_type);
	$totalcount = $istype['dbrows'];

	#keyword search
	$keywords = isset($_POST['search']) ? " AND (item REGEXP '{$_POST['search']}')" : "";

	if(!empty($keywords)) {
					
		$sqldata = "SELECT id FROM {$mtbL5} WHERE deletedata=0".$keywords;
		$search_result = idget_data($sqldata);

		$addtokey = "";
		
		if(is_array($search_result)) {
			foreach($search_result as $key => $val) { $addtokey .= $val['id'].','; }
			$addtokey = substr_replace($addtokey,'',-1,1);
		}

		$queryset = " AND itemid IN({$addtokey})";

	} else {
		$queryset = "";
	}

?>

<div class="pads30">
	<div class="box-border-thick xsml-rounded-button alignlt">
		<ul class="nolist">
			<li class="">
				<div class="pads30">

					<div class="block-element bottom-push-30">
					 	<span class="ln-display-box float-left">
							<a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
							&nbsp; Note: To add or update item cost of purchase, click <u>add</u> button
					 	</span>
					 	<span class="ln-display-box float-right">
							<a href="javascript:void(0)" class="blue-font box-border-thick rounded-button top-pull-10 right-pull-20 bottom-pull-10 left-pull-20" onclick="jsForm()">Add <b class="fa-arrow-right left-push-5"></b></a>
						</span>
						<span class="block-element new-line-space">
							<!-- clear line -->
						</span>
					</div>

					<div class="bottom-push-30">
						<span class="ln-display-box float-left cs-width-180 right-pull-30">
							<h4 class="large nobold nomargin top-pull-10">Total Record: <?php echo $totalcount; ?></h4>
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
						<span class="block-element new-line-space">
						</span>
					</div>

					<?php
						
						if($istype['isdata'] == true) {

							?>
								<table cellpadding="0" cellspacing="0" class="">
									<tr>
										<td class="nunito-bold alignct">&nbsp;</td>
										<td class="nunito-bold">Item</td>
										<td class="cs-width-200 nunito-bold">Cost Price List</td>
									</tr>
									
									<?php

										$isSql = "SELECT itemid FROM {$tbl} WHERE deletedata=0".$queryset." GROUP BY itemid";
										$wgtGdata = idget_data($isSql);

										if(is_array($wgtGdata) && count($wgtGdata)) {
											$num = 0;
											foreach($wgtGdata as $key => $val) {
												
												$isSql2 = "SELECT costprice FROM {$tbl} WHERE itemid={$val['itemid']} AND deletedata=0 ORDER BY id DESC"; $forCP = idget_data($isSql2);

												idget_global($val['itemid'],$var_item);
												//idget_global($val2['userid'],$var_user);

												$incre = 0; $costprice_option = "";
												
												foreach($forCP as $key2 => $val2) {
													$incre += 1;
													if($incre == 1) { $costprice_option .= '<option value="'.$val2['costprice'].'">'.$val2['costprice'].' &check;</option>'; } else { $costprice_option .= '<option value="'.$val2['costprice'].'">'.$val2['costprice'].' x</option>'; }
												}

												$num += 1;

												?>
													<tr class="white-grey-state motion">
														<td class="cs-width-100 pads7"><?php echo $num; ?>.</td>
														<td class="right-pull-7"><?php echo $_gparams[$var_item]['returnval']; ?></td>
														<td class="cs-width-200 alignct right-pull-7"><select class="nopads no-back-black"><?php echo $costprice_option; ?></select></td>
													</tr>
												<?php

												$incre = 0; $costprice_option = "";
											}
										}

									?>

								</table>
							<?php

						} else {
						
							?>
								<div class="cs-height-50"></div>
								<div class="block-element" align="center">
									<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
										<span class="block-element nc-height-35"></span>
										<b class="mbri-pages ft-Lsize nobold"></b>
									</div>
									<h3 class="xlarge nobold dark-grey-font">No records found</h3>
								</div>
							<?php
						}
					?>
					
				</div>
			</li>
		</ul>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsForm() {

		datastring.process = "insert";
		datastring.tip = "Creating new item cost of purchase";
		datastring.element = "html/itemcost.html";
		
		xform('htmlform');
	}

</script>