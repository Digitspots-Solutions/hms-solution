<?php

	#get all applcable workflows
	$varworkFlow = getjob_workflow(13);

	#create table for batch
	createDatabasetable($var_tbl_116);
	createDatabasetable($var_tbl_151);

	$tbl = $mtbL8;

	//check if data exist in the table
	$query_pr = "deletedata=0 AND pr_status IN('Job Order') GROUP BY order_number";
	$pr_state = mysqli_data_exist($tbl,$query_pr);

?>

<div class="cs-height-30"></div>

<div class="alignlt left-pull-30"><h3 class="large nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Here are the list of job orders. Click receive link or history link</h3></div>

<div class="pads30">

	<div class="cs-width-350 float-right">
		<form action="" method="post">
			<span class="cs-width-100 float-right">
				<input type="submit" name="varbutton" id="varbutton" value=" Search " class="submit pads10 black-white-state sml-rounded-button">
			</span>
			<span class="cs-width-200 float-left">
				<input type="text" name="prvar" id="prvar" placeholder="Search for JOB ORDER">
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
	<div class="block-element new-line-space bottom-push-20">
	</div>
	
	<div class="x-scroll bottom-pull-10">
		<div class="nc-width-100">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Sn.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Supplier</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Store</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Job Order No.</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Date</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Delivery Note</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10">Date/Time</td>
					<td class="default-text-font-bold right-pull-10 left-pull-10"></td>
				</tr>
	
				<?php
					if($pr_state['isdata'] == true) {
						
						if(isset($_POST['varbutton']) && !empty($_POST['prvar'])) {
							//$_SESSION['prvar'] = $_POST['prvar'];
							$isSql = "SELECT * FROM {$tbl} WHERE order_number='{$_POST['prvar']}' AND pr_status='Job Order' AND deletedata=0 GROUP BY order_number ORDER BY id DESC";
						} else {
							/*if(isset($_SESSION['prvar'])) {
								$isSql = "SELECT * FROM {$tbl} WHERE order_number='{$_SESSION['prvar']}' AND pr_status='Job Order' AND deletedata=0 GROUP BY order_number ORDER BY id DESC";
							} else {
								$isSql = "SELECT * FROM {$tbl} WHERE pr_status='Job Order' AND deletedata=0 GROUP BY order_number ORDER BY id DESC LIMIT 100";
							}*/

							$isSql = "SELECT * FROM {$tbl} WHERE pr_status='Job Order' AND deletedata=0 GROUP BY order_number ORDER BY id DESC LIMIT 100";
						}

						$wgt_pr = idget_data($isSql);

						$numbr = 0; $wget_store_name = "";
						
						if(is_array($wgt_pr) && count($wgt_pr) > 0) {
							
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
										<td class="right-pull-10 left-pull-10 blue-font anchor" onclick="jsPrint2('<?php echo $val['order_number']; ?>')"><?php echo $val['order_number']; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val['delivery_date'])); ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo $val['delivery_note']; ?></td>
										<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($val['datelogged'])).' '.$val2['timelogged']; ?></td>
										<td class="cs-width-250 right-pull-10 left-pull-10 pads7"><a href="javascript:void(0)" class="blue-font" onclick="jsReceiveJoborder('<?php echo $val['order_number']; ?>')">Receive Job Order</a> | <a href="javascript:void(0)" class="blue-font" onclick="jsHistoryJoborder('<?php echo $val['order_number']; ?>')">History</a></td>
									</tr>
								<?php
							}
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
					else if(eval(document.getElementById(bt).value) > eval(document.getElementById(rd).value)) { document.getElementById(df).value = eval(document.getElementById(bt).value) - eval(document.getElementById(rd).value); }
					else if(eval(document.getElementById(rd).value) >= eval(document.getElementById(bt).value)) { document.getElementById(df).value = eval(document.getElementById(rd).value) - eval(document.getElementById(bt).value); }
					else { document.getElementById(df).value = 0; document.getElementById(bt).value = 0; }
				} else {
					document.getElementById(df).value = 0; document.getElementById(bt).value = 0;
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
					else { document.getElementById(df).value = 0; document.getElementById(mkt).value = 0; }
				} else {
					document.getElementById(df).value = 0; document.getElementById(mkt).value = 0;
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


	function jsPrint(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/printpr.php?pr='+order;
	}


	function jsPrint2(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/printjo.php?pr='+order;
	}
	

	function jsReceiveJoborder(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-black noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/receive_joborder.php?pr='+order;
	}

	function jsHistoryJoborder(order) {

		chgclass('tktBox','fx-position-stick fscr zind-2 txp3-black noscroll xfadeout motion');
		
		var wgets, inframe;
	
		inframe = document.createElement('iframe');
		
		inframe.width = '100%';
		inframe.height = '100%';
		inframe.frameBorder = 0;
		inframe.marginWidth = 0;
		inframe.marginHeight = 0;
		inframe.scrolling = 'auto';

		//wgets = order+'-'+batch;

		document.getElementById('rBox').appendChild(inframe);
		inframe.src = curl+'public/admin/materialcontrol/history_joborder.php?pr='+order;
	}

</script>