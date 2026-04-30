<?php

	#get all applcable workflows
	$varworkFlow = getjob_workflow(13);

	#create table for batch
	createDatabasetable($var_tbl_116);
	createDatabasetable($var_tbl_151);

	$tbl = $mtbL8;

	//check if data exist in the table
	$query_pr = "deletedata=0 AND receipt_status='Pending' AND var_status = 0 AND order_status='Approved' AND (pr_status='IOU Approved' OR pr_status='Payment Approved' OR pr_status='Job Order') GROUP BY order_number";
	$pr_state = mysqli_data_exist($tbl,$query_pr);

?>

<div class="cs-height-30"></div>

<div class="alignlt left-pull-30"><h3 class="large nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Here are the list of awaiting stock to be received. Please indicate whether the following purchase requests require variation or not</h3></div>

<div class="pads30">
	
	<div class="x-scroll bottom-pull-10">
		<div class="cs-width-1000">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Sn.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Supplier</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Store</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Order No.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Date</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Note</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Date/Time</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10"></td>
				</tr>
	
				<?php
					if($pr_state['isdata'] == true) {
						
						$isSql = "SELECT * FROM {$tbl} WHERE receipt_status='Pending' AND var_status = 0 AND order_status='Approved' AND (pr_status='IOU Approved' OR pr_status='Payment Approved' OR pr_status='Job Order') AND deletedata=0 GROUP BY order_number";
						$wgt_pr = idget_data($isSql);

						$numbr = 0; $wget_store_name = "";
						
						foreach($wgt_pr as $key => $val) {
							
							idget_global($val['supplierid'],$var_supplier);
							
							if($val['store'] == 0) {
								$wget_store_name = 'Warehouse';
							} else {
								idget_global($val['store'],$var_store);
								$wget_store_name = $_gparams[$var_store]['returnval'];
							}
							
							$numbr += 1;
							
							?>
								<tr>
									<td class="right-pull-10 left-pull-10"><?php echo $numbr; ?>.</td>
									<td class="right-pull-10 left-pull-10"><?php echo $_gparams[$var_supplier]['returnval']; ?></td>
									<td class="right-pull-10 left-pull-10"><?php echo $wget_store_name; ?></td>
									<td class="right-pull-10 left-pull-10 blue-font"><?php echo $val['order_number']; ?></td>
									<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val['delivery_date'])); ?></td>
									<td class="right-pull-10 left-pull-10"><?php echo $val['delivery_note']; ?></td>
									<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val['datelogged'])).' '.$val2['timelogged']; ?></td>
									<td class="cs-width-150 right-pull-10 left-pull-10 pads7"><div class="pads7 grey-theme xsml-rounded-button"><select name="vars[]" class="nopads no-back-black" onchange="openvarform(this.value)"><option value="" selected>Apply Variation?</option><option value="<?php echo $val['order_number']; ?>/y">Yes, Apply</option><option value="<?php echo $val['order_number']; ?>/n">No, Ignore</option></select></div></td>
								</tr>
							<?php
						}
						
					} else {
						?>
							<tr><td colspan="7" class="pads30 alignct dark-grey-font">There are no available records at the moment</td></tr>
						<?php
					}
				?>

			</table>
		</div>
	</div>

</div>

<?php

	if(isset($_GET['nopops'])) { if(isset($_GET['var'])) { unset($_GET['var']); } }

	if(isset($_GET['var']) && !empty($_GET['var'])) {
		$this_var = explode('/', $_GET['var']);
		?>
			<div class="fx-position-stick fscr zind-2 txp5-white y-scroll xfadeout motion" align="center">
				<div class="fx-width-90 cs-height-500 white-theme xsml-rounded-button pads30 cs-margin-top-100">
					<?php
						if(!empty($this_var[1]) && $this_var[1] === 'n') {
							?>
								<div class="box-border-thick xsml-rounded-button pads30">
									<h3 class="large nobold">You are indicating no variation for the selected (<?php echo $this_var[0]; ?>). Click apply button to complete</h3>
									<p class="top-pull-20"><a href="javascript:void()" onclick="novar('<?php echo $this_var[0]; ?>')" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button rounded-button right-push-10">Apply</a> <a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="jsCancel()">Close x</a></p>
								</div>
							<?php
						} elseif(!empty($this_var[1]) && $this_var[1] === 'y') {
							$var_pr = "SELECT * FROM {$tbl} WHERE deletedata=0 AND order_number='{$this_var[0]}'";
							$wgt_vpr = idget_data($var_pr);

							?>
								<form id="stock-var" action="" method="post" autocomplete="off">
									<input type="hidden" name="uri" value="apply-stock-variance">
									<input type="hidden" name="pr" value="<?php echo $this_var[0]; ?>">
									<input type="hidden" name="workflow" id="workflow" value="0">
									<h4 class="xlarge nobold black-font alignct"><b class="fas fa-question-circle right-push-5"></b>Use tab-key to move around the fields while you change the information accordingly. Click apply button to confirm and send for approval</h4><br>
									<h3 class="large nobold nunito-bold">Stock Variation for PR: <?php echo $this_var[0]; ?></h3><br>
									<table cellspacing="0" cellpadding="0">
										<tr>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Item</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Required</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Bought</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Qty Variance</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Price Request</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Market Price</td>
											<td class="default-text-font-bold right-pull-10 left-pull-10">Price Variance</td>
										</tr>
										<?php
											$numb = 0;
											foreach($wgt_vpr as $key => $val) {
												
												idget_global($val['itemid'],$var_item);

												?>
													<tr>
														<td class="right-pull-10 left-pull-10 cs-width-150"><?php echo $_gparams[$var_item]['returnval']; ?><input type="hidden" name="item[]" value="<?php echo $val['itemid']; ?>" readonly></td>
														<td class="right-pull-10 left-pull-10"><input type="number" min="0" step=".01" name="qtyrequired[]" id="qtyr<?php echo $numb; ?>" value="<?php echo $val['qty_ordered']; ?>" placeholder="Quantity Required" class="nopads no-back-black qtyrd" readonly></td>
														<td class="right-pull-10 left-pull-10"><input type="number" min="0" step=".01" name="qtybought[]" id="qtyb<?php echo $numb; ?>" placeholder="Enter here?" class="qtybt" onblur="wgetQvar(<?php echo $numb; ?>)" required></td>
														<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="text" name="qtydiff[]" id="qtyd<?php echo $numb; ?>" placeholder="Auto?" class="qtydf" readonly></td>
														<td class="right-pull-10 left-pull-10"><input type="text" name="pricerequest[]" id="pricer<?php echo $numb; ?>" value="<?php echo $val['unitprice']; ?>" placeholder="Price Request" class="nopads no-back-black pricert" required readonly></td>
														<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="number" step=".01" min="1" name="mktprice[]" id="mkt<?php echo $numb; ?>" placeholder="Enter here?" class="mktprice" onblur="wgetPvar(<?php echo $numb; ?>)" required></td>
														<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="number" step=".01" min="1" name="pricediff[]" id="pricedf<?php echo $numb; ?>" placeholder="Auto?" class="pricedf" readonly></td>
													</tr>
												<?php

												$numb += 1;
											}
										?>
									</table>
									<p class="top-pull-20">
										<input type="button" id="submitbutton" name="submitbutton" value="Apply" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance" onclick="popwkf()">
										<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow left-push-15 right-push-10" onclick="jsCancel()">Close x</a>
									</p>
								</form>
							<?php
						}
					?>
				</div>
			</div>
		<?php
	}
?>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="tktBox2" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox2" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<div id="fbox"></div>


<script>

	function openvarform(order) {
		window.location.href = window.location.href+"&var="+order;
	}

	function jsCancel() {
		window.location.href = window.location.href+"&nopops=1";
	}

	function novar(order) {
		window.location.href = window.location.href+"&curi=apply-no-variance&pr="+order;
	}

	function wgetQvar(row) {

		var tr, j, r = row;
		
		var qtyrd = document.getElementsByClassName('qtyrd');
		var qtybt = document.getElementsByClassName('qtybt');
		var qtydf = document.getElementsByClassName('qtydf');

		for(j=0; j < qtyrd.length; j++) {
			if(j == r) {
				var rd = qtyrd[j].id, bt = qtybt[j].id, df = qtydf[j].id;
				if(document.getElementById(bt).value !='' && eval(document.getElementById(bt).value) > 0) {
					if(eval(document.getElementById(bt).value) == eval(document.getElementById(rd).value)) { document.getElementById(df).value = eval(document.getElementById(bt).value) - eval(document.getElementById(rd).value); }
					else if(eval(document.getElementById(bt).value) > eval(document.getElementById(rd).value)) { document.getElementById(bt).value = document.getElementById(rd).value; document.getElementById(df).value = 0; }
					else if(eval(document.getElementById(rd).value) >= eval(document.getElementById(bt).value)) { document.getElementById(df).value = eval(document.getElementById(rd).value) - eval(document.getElementById(bt).value); }
					else { document.getElementById(df).value = ""; document.getElementById(bt).value = ""; }
				} else {
					document.getElementById(df).value = ""; document.getElementById(bt).value = "";
				}

				break;
			}
		}
	}

	function wgetPvar(row) {

		var tr, j, r = row;
		
		var pricert = document.getElementsByClassName('pricert');
		var mktprice = document.getElementsByClassName('mktprice');
		var pricedf = document.getElementsByClassName('pricedf');

		for(j=0; j < pricert.length; j++) {
			if(j == r) {
				var rt = pricert[j].id, mkt = mktprice[j].id, df = pricedf[j].id;
				
				if(document.getElementById(mkt).value !='' && eval(document.getElementById(mkt).value) > 0) {
					if(eval(document.getElementById(mkt).value) >= eval(document.getElementById(rt).value)) { document.getElementById(df).value = eval(document.getElementById(mkt).value) - eval(document.getElementById(rt).value); }
					else if(eval(document.getElementById(rt).value) >= eval(document.getElementById(mkt).value)) { document.getElementById(df).value = eval(document.getElementById(rt).value) - eval(document.getElementById(mkt).value); }
					else { document.getElementById(df).value = ""; document.getElementById(mkt).value = ""; }
				} else {
					document.getElementById(df).value = ""; document.getElementById(mkt).value = "";
				}
				
				break;
			}
		}
	}


	function popwkf() {
		//window.location.href = window.location.href+"&curi=pr-approval-request";
		var isworkflow = '<?php echo $varworkFlow; ?>';
		
		chgclass('tktBox2','fx-position-stick fscr zind-1 txp2-white noscroll xfadeout motion');
		chgclass('rBox2','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-100 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label class="block-element bottom-push-7">Select your approval workflow?</label>';
		vhtml += '<select name="workflowx" id="workflowx" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox2',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt noscroll');
		writeObjheader('rBox','');
	}


	function jbtrigger(e) {
		e.preventDefault();
		document.getElementById('workflow').value = document.getElementById('workflowx').value;
		setTimeout(() => { document.getElementById('stock-var').submit(); },1000);
	}

</script>