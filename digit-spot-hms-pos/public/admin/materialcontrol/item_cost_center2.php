<?php
	
	#create table for batch
	createDatabasetable($var_tbl_147);

	$tbl = $mtbL18;

	//check if data exist in expense category table
	$query_type = "deletedata=0";
	$istype = mysqli_data_exist($tbl,$query_type);

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

					<table cellpadding="0" cellspacing="0" class="">
						<tr>
							<td class="nunito-bold alignct">&nbsp;</td>
							<td class="nunito-bold alignct">Item</td>
							<td class="nunito-bold alignct">Cost Price</td>
							<td class="nunito-bold alignct">Cost Controller</td>
							<td class="nunito-bold alignct">Date Modified</td>
						</tr>
					<?php
						
						if($istype['isdata'] == true) {

							$isSql = "SELECT itemid FROM {$tbl} WHERE deletedata=0 GROUP BY itemid";
							$wgtGdata = idget_data($isSql);

							$numbr = 1;

							if(is_array($wgtGdata) && count($wgtGdata)) {
								foreach($wgtGdata as $key => $val) {
									
										

													$isSql2 = "SELECT * FROM {$tbl} WHERE itemid={$val['itemid']} AND deletedata=0 ORDER BY id DESC"; $wgt_items = idget_data($isSql2);

													$incre = 0;

													foreach($wgt_items as $key2 => $val2) {
														
														idget_global($val2['itemid'],$var_item);
														idget_global($val2['userid'],$var_user);

														$incre += 1;

														?>
															<tr class="white-grey-state motion">
																<td class="cs-width-30 pads7"><div class="grey-theme pads7 xsml-rounded-button"><input type="checkbox" name="checkers[]" value="<?php echo $val2['id']; ?>" <?php if($incre == 1) { ?>disabled<?php } ?>></div></td>
																<td class="alignct right-pull-7 left-pull-7"><?php echo $_gparams[$var_item]['returnval']; ?></td>
																<td class="alignct right-pull-7 left-pull-7"><?php echo number_format($val2['costprice'],2); if($incre == 1) { ?><sup class="fx-position-flow" style="margin-left: -5px;"><b class="nobold top-pull-3 right-pull-10 bottom-pull-3 left-pull-10 orange-white-state rounded-button" style="font-size: 9px">New</b></sup><?php } ?></td>
																<td class="alignct right-pull-7 left-pull-7"><?php echo $_gparams[$var_user]['returnval']; ?></td>
																<td class="alignct right-pull-7 left-pull-7"><?php echo date($nth_dfn,strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
															</tr>
														<?php
													}
												?>			
											</table>
										</div>
									<?php

									$numbr += 1;
								}
							}

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