<?php

	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	//get user approval list
	$get_orderno = isset($_GET['orderno']) ? $_GET['orderno'] : "";
	
	$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$get_orderno}' AND approval_type='VAR'";
	$jpL = idget_data($isjp);
?>

<div class="cs-height-30"></div>

<div class="alignlt left-pull-30"><h3 class="large nobold nomargin">Here are the list of awaiting stock to be received. Please acknowledge your consent</h3></div>

<div class="pads30">
	
	<?php
		if(is_array($jpL) && count($jpL)) {
			?>
				<div class="sided-box grey-theme xsml-rounded-button pads20 bottom-push-10 alignlt">
					<ul>
						<li class="right-pull-30">
							<?php
								if($jpL[0]['user_one']) {
									$userone = idget_name($jpL[0]['user_one'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_one'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af1_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_one']);
									$af1_status_color = wgtcolor($af1_approval_signed_status);
									?>
										<h3 class="large nobold nunito-semibold nomargin"><?php echo $userone; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
									<?php
									
									if($userSignedIn != $jpL[0]['user_one']) {
										?>
											<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
										<?php
									} elseif($userSignedIn == $jpL[0]['user_one']) {
										if($jpL[0]['approval_one'] == 0) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,1)">
													<option value="" selected>Choose to sign</option>
													<option value="1">Approve</option>
													<option value="2">On Hold</option>
													<option value="3">Reject</option>
												</select>
											<?php
										} else {
											if($jpL[0]['approval_one'] == 2) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,1)">
														<option value="" selected>Change approval state</option>
														<option value="1">Approve</option>
														<option value="3">Reject</option>
													</select>
												<?php
											}
											?>
												<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
												<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
											<?php
										}
									}
								}
							?>
						</li>
						<li class="right-pull-30">
							<?php
								if($jpL[0]['user_two']) {
									$usertwo = idget_name($jpL[0]['user_two'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_two'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af2_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_two']);
									$af2_status_color = wgtcolor($af2_approval_signed_status);
									?>
										<h3 class="large nobold nunito-semibold nomargin"><?php echo $usertwo; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
									<?php

									if($userSignedIn != $jpL[0]['user_two']) {
										?>
											<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
										<?php
									} elseif($userSignedIn == $jpL[0]['user_two']) {
										if($jpL[0]['approval_two'] == 0) {
											if($jpL[0]['approval_one'] == 1) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,2)">
														<option value="" selected>Choose to sign</option>
														<option value="1">Approve</option>
														<option value="2">On Hold</option>
														<option value="3">Reject</option>
													</select>
												<?php
											} else {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,2)" disabled="disabled">
														<option value="0" selected>Signatory Locked</option>
													</select>
												<?php
											}
										} else {
											if($jpL[0]['approval_two'] == 2) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,2)">
														<option value="" selected>Change approval state</option>
														<option value="1">Approve</option>
														<option value="3">Reject</option>
													</select>
												<?php
											}
											?>
											<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
											<?php
										}
									}
								}
							?>
						</li>
						<li class="right-pull-30">
							<?php
								if($jpL[0]['user_three']) {
									$userthree = idget_name($jpL[0]['user_three'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_three'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af3_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_three']);
									$af3_status_color = wgtcolor($af3_approval_signed_status);
									?>
										<h3 class="large nobold nunito-semibold nomargin"><?php echo $userthree; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
									<?php

									if($userSignedIn != $jpL[0]['user_three']) {
										?>
											<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
										<?php
									} elseif($userSignedIn == $jpL[0]['user_three']) {
										if($jpL[0]['approval_three'] == 0) {
											if($jpL[0]['approval_two'] == 1) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,3)">
														<option value="" selected>Choose to sign</option>
														<option value="1">Approve</option>
														<option value="2">On Hold</option>
														<option value="3">Reject</option>
													</select>
												<?php
											} else {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,3)" disabled="disabled">
														<option value="0" selected>Signatory Locked</option>
													</select>
												<?php
											}
										} else {
											if($jpL[0]['approval_three'] == 2) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,3)">
														<option value="" selected>Change approval state</option>
														<option value="1">Approve</option>
														<option value="3">Reject</option>
													</select>
												<?php
											}
											?>
											<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
											<?php
										}
									}
								}
							?>
						</li>
						<li class="right-pull-30">
							<?php
								if($jpL[0]['user_four']) {
									$userfour = idget_name($jpL[0]['user_four'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_four'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af4_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_four']);
									$af4_status_color = wgtcolor($af4_approval_signed_status);
									?>
										<h3 class="large nobold nunito-semibold nomargin"><?php echo $userfour; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
									<?php

									if($userSignedIn != $jpL[0]['user_four']) {
										?>
											<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
										<?php
									} elseif($userSignedIn == $jpL[0]['user_four']) {
										if($jpL[0]['approval_four'] == 0) {
											if($jpL[0]['approval_three'] == 1) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,4)">
														<option value="" selected>Choose to sign</option>
														<option value="1">Approve</option>
														<option value="2">On Hold</option>
														<option value="3">Reject</option>
													</select>
												<?php
											} else {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,4)" disabled="disabled">
														<option value="0" selected>Signatory Locked</option>
													</select>
												<?php
											}
										} else {
											if($jpL[0]['approval_four'] == 2) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,4)">
														<option value="" selected>Change approval state</option>
														<option value="1">Approve</option>
														<option value="3">Reject</option>
													</select>
												<?php
											}
											?>
											<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
											<?php
										}
									}
								}
							?>
						</li>
						<li class="right-pull-30">
							<?php
								if($jpL[0]['user_five']) {
									$userfive = idget_name($jpL[0]['user_five'],'staffname',$tbL7);
									$useroleid = idget_name($jpL[0]['user_five'],'role',$tbL7);
									$userole = idget_name($useroleid,'role',$tbL4);
									$af5_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_five']);
									$af5_status_color = wgtcolor($af5_approval_signed_status);
									?>
										<h3 class="large nobold nunito-semibold nomargin"><?php echo $userfive; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
									<?php

									if($userSignedIn != $jpL[0]['user_five']) {
										?>
											<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
										<?php
									} elseif($userSignedIn == $jpL[0]['user_five']) {
										if($jpL[0]['approval_five'] == 0) {
											if($jpL[0]['approval_four'] == 1) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,5)">
														<option value="" selected>Choose to sign</option>
														<option value="1">Approve</option>
														<option value="2">On Hold</option>
														<option value="3">Reject</option>
													</select>
												<?php
											} else {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,5)" disabled="disabled">
														<option value="0" selected>Signatory Locked</option>
													</select>
												<?php
											}
										} else {
											if($jpL[0]['approval_five'] == 2) {
												?>
													<select name="signpr" id="signpr" onchange="signPr('<?php echo $get_orderno; ?>',this.id,5)">
														<option value="" selected>Change approval state</option>
														<option value="1">Approve</option>
														<option value="3">Reject</option>
													</select>
												<?php
											}
											?>
											<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
											<?php
										}
									}
								}
							?>
						</li>
						<li></li>
					</ul>
				</div>
			<?php
		}
	?>

	<div class="x-scroll bottom-pull-10">
		<div class="nc-width-100">
			<?php
				$tbl = $mtbL20;
				
				$var_pr = "SELECT * FROM {$tbl} WHERE deletedata=0 AND order_number='{$get_orderno}'";
				$wgt_vpr = idget_data($var_pr);
			?>
							
			<h3 class="large nobold nunito-bold alignct">Stock Variation for PR: <?php echo $get_orderno; ?></h3><br>

			<form action="" method="post" autocomplete="off">
				<input type="hidden" name="uri" value="apply-stock-variance">
				<input type="hidden" name="pr" value="<?php echo $get_orderno; ?>">
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
									<td class="right-pull-10 left-pull-10"><input type="text" name="qtyrequired[]" id="qtyr<?php echo $numb; ?>" value="<?php echo $val['qty_required']; ?>" placeholder="Quantity Required" pattern="\d*" class="qtyrd" readonly></td>
									<td class="right-pull-10 left-pull-10"><input type="text" name="qtybought[]" id="qtyb<?php echo $numb; ?>" placeholder="Enter here?" value="<?php echo $val['qty_bought']; ?>" pattern="\d*" class="qtybt" onblur="wgetQvar(<?php echo $numb; ?>)" readonly></td>
									<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="text" name="qtydiff[]" id="qtyd<?php echo $numb; ?>" placeholder="Auto?" value="<?php echo $val['qty_diff']; ?>" pattern="\d*" class="qtydf" readonly></td>
									<td class="right-pull-10 left-pull-10"><input type="text" name="pricerequest[]" id="pricer<?php echo $numb; ?>" value="<?php echo $val['price_request']; ?>" placeholder="Price Request" class="pricert" readonly></td>
									<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="number" min="1" name="mktprice[]" id="mkt<?php echo $numb; ?>" placeholder="Enter here?" value="<?php echo $val['market_price']; ?>" class="mktprice" readonly onblur="wgetPvar(<?php echo $numb; ?>)"></td>
									<td class="top-pull-5 bottom-pull-5 right-pull-10 left-pull-10"><input type="number" min="1" name="pricediff[]" id="pricedf<?php echo $numb; ?>" placeholder="Auto?" value="<?php echo $val['price_diff']; ?>" class="pricedf" readonly></td>
								</tr>
							<?php

							$numb += 1;
						}
					?>
				</table>
			</form>			
		</div>
	</div>

</div>


<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-80 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function signPr(order,id,level) {

		var wgets = document.getElementById(id), vhtml;
		
		if(wgets.value != '' && wgets.value !== null) {
			
			chgclass('tktBox','fx-position-stick fscr zind-2 txp3-white noscroll xfadeout motion');
			chgclass('rBox','fx-width-30 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-100 noscroll');

			vhtml = '';
			vhtml += '<p class="bottom-pull-15 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()"><b class="mbri-close"></b></a></p>';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="">';
			vhtml += '<input type="hidden" name="uri" value="apply-pr-var-approval">';
			vhtml += '<div class="alignlt">';
			vhtml += '<label>Write a comment if applicable</label>';
			vhtml += '<textarea name="commentpr" id="commentpr" placeholder="Type here.." class="notextborder"></textarea>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="hidden" name="orderno" id="orderno" value="'+order+'">';
			vhtml += '<input type="hidden" name="signatory" id="signatory" value="'+wgets.value+'">';
			vhtml += '<input type="hidden" name="level" id="level" value="'+level+'">';
			vhtml += '<input type="submit" id="approvebutton" name="approvebutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
			vhtml += '</div>';
			vhtml += '</form>';
			
			writeObjheader('rBox',vhtml);
		}
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
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
					if(eval(document.getElementById(bt).value) >= eval(document.getElementById(rd).value)) { document.getElementById(df).value = eval(document.getElementById(bt).value) - eval(document.getElementById(rd).value); }
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

</script>