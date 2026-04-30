<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

$sql = "SELECT t1.staffname, t1.id, t1.role AS roleID, t2.approve, (SELECT role FROM {$tbL4} WHERE id=roleID) AS rolename FROM {$tbL7} t1, {$tbL108} t2 WHERE t1.role=t2.role AND t1.deletedata=0 AND t1.status='Active' GROUP BY t2.role";

$fetch_data = mysqli_data_array('assoc',$sql);

if(is_array($fetch_data) && count($fetch_data) > 0) {
	foreach($fetch_data as $theader => $tdata) {
		$select .= '<option value="'.$tdata['id'].'">'.$tdata['staffname'].' ('.$tdata['rolename'].')</option>';
	}
} else {
	$select .= '<option value="">No options</option>';
}

$get_users = $select;

$additionalQuery = "";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>

<div class="pads20">
	
	<h3 class="large nobold black-font alignct">Check the list of approved request by approval officers</h3>
	<p>&nbsp;</p>
				
	<div class="box-border-thick-bottom bottom-pull-15 bottom-push-15">
		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<span class="ln-display-box float-left cs-width-150 right-push-30">
				<h3 class="large nobold default-text-font-bold">Approval Officers</h3>
				<select name="officer" id="officer" class="nopads no-back-black" required>
					<option value="">Choose</option>
					<?php echo $get_users; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-150 right-push-30">
				<h3 class="large nobold default-text-font-bold">Approved Request</h3>
				<select name="request" id="request" class="nopads no-back-black" required>
					<option value="">Choose</option>
					<option value="IOU">IOU</option>
					<option value="PR">PR</option>
					<option value="TR">TR</option>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-180 right-push-30">
				<h3 class="large nobold default-text-font-bold">Date Signed (from)</h3>
				<input type="text" name="signdate" id="signdate" placeholder="Date From" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-180 right-push-30">
				<h3 class="large nobold default-text-font-bold">Date Signed (to)</h3>
				<input type="text" name="signdate2" id="signdate2" placeholder="Date To" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right">
				<input type="submit" name="submitbutton" value="Run" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold anchor right-push-15" onclick="this.value='Loading..'">
				<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button default-text-font-bold anchor" onclick="window.print()">
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>

	<div class="block-element pads10">
		<div id="section-to-print">

			<?php

				if(isset($_POST['submitbutton']) && !empty($_POST['signdate'])) {

					$officer = $_POST['officer']; $request = $_POST['request'];
					$from = $_POST['signdate']; $to = $_POST['signdate2'];
					//$from = strtotime($_POST['signdate']); $to = strtotime($_POST['signdate2']);

					$date_range = "";

					//while($from <= $to) { $date_range .= date('d-m-Y',$from).".*|"; $from = strtotime('+1 day', $from); }
					//while($from <= $to) { $date_range .= date('d-m-Y',$from).","; $from = strtotime('+1 day', $from); }
					//$date_range = substr_replace($date_range,'',-1);
					//$date_range = '{'.$date_range.'}';

					//$rAddQ = ($request == 'ALL') ? " AND approval_type IN('PR','IOU')" : " AND approval_type IN('{$request}')";
					$rAddQ = " AND approval_type IN('{$request}')";

					/*$sql = "SELECT * FROM {$tbL151} WHERE (user_one={$officer} OR user_two={$officer} OR user_three={$officer} OR user_four={$officer} OR user_five={$officer}) AND (approval_one=1 OR approval_two=1 OR approval_three=1 OR approval_four=1 OR approval_five=1) AND (comment_one LIKE '%{$from}%' OR comment_two LIKE '%{$from}%' OR comment_three LIKE '%{$from}%' OR comment_four LIKE '%{$from}%' OR comment_five LIKE '%{$from}%') AND deletedata=0".$rAddQ;*/

					/*$sql = "SELECT * FROM {$tbL151} WHERE (user_one={$officer} OR user_two={$officer} OR user_three={$officer} OR user_four={$officer} OR user_five={$officer}) AND (approval_one=1 OR approval_two=1 OR approval_three=1 OR approval_four=1 OR approval_five=1) AND (comment_one REGEXP '{$date_range}' OR comment_two REGEXP '{$date_range}' OR comment_three REGEXP '{$date_range}' OR comment_four REGEXP '{$date_range}' OR comment_five REGEXP '{$date_range}') AND deletedata=0".$rAddQ;*/

					//$sql = "SELECT * FROM {$tbL151} WHERE (user_one={$officer} OR user_two={$officer} OR user_three={$officer} OR user_four={$officer} OR user_five={$officer}) AND (approval_one=1 OR approval_two=1 OR approval_three=1 OR approval_four=1 OR approval_five=1) AND ((signdate_one >= '{$from}' AND signdate_one <= '{$to}') OR (signdate_two >= '{$from}' AND signdate_two <= '{$to}') OR (signdate_three >= '{$from}' AND signdate_three <= '{$to}') OR (signdate_four >= '{$from}' AND signdate_four <= '{$to}') OR (signdate_five >= '{$from}' AND signdate_five <= '{$to}')) AND deletedata=0".$rAddQ;

					$sql = "SELECT * FROM {$tbL151} WHERE (user_one={$officer} OR user_two={$officer} OR user_three={$officer} OR user_four={$officer} OR user_five={$officer}) AND (approval_one=1 OR approval_two=1 OR approval_three=1 OR approval_four=1 OR approval_five=1) AND ((signdate_one BETWEEN '{$from}' AND '{$to}') OR (signdate_two BETWEEN '{$from}' AND '{$to}') OR (signdate_three BETWEEN '{$from}' AND '{$to}') OR (signdate_four BETWEEN '{$from}' AND '{$to}') OR (signdate_five BETWEEN '{$from}' AND '{$to}')) AND deletedata=0".$rAddQ;

					$fetch_data = mysqli_data_array('assoc',$sql);

					$subj = ""; $approval_subj = ""; $approved_amt = 0; $approved_src = ""; $storeID = "";
					$signatory = ""; $link = ""; $signatory_note = ""; $link_alt = ""; $numbr = 0;

					if(is_array($fetch_data) && count($fetch_data) > 0) {
						
						?>
							<div class="bottom-push-30" align="center">
								<div class="cs-width-100 bottom-push-10 noscroll">
									<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
								</div>
								<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold default-text-font-bold nomargin">Approved Requests List (Date: <?php echo $from; ?>)</h3>
								<h3 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h3>
							</div>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="50px" align="center">&nbsp;</th>
									<th width="150px" align="center">Sent Date</th>
									<th width="200px" align="center">Signatory</th>
									<th width="150px" align="center">Sign/Date</th>
									<th width="100px" align="center">Particular</th>
									<th width="150px" align="center">Amount</th>
									<th width="300px" align="center">Source/Detail</th>
								</tr>
								<?php
									
									foreach ($fetch_data as $key => $val) {

										if(($officer == $val['user_one'] && $val['signdate_one'] != '0000-00-00') || ($officer == $val['user_two'] && $val['signdate_two'] != '0000-00-00') || ($officer == $val['user_three'] && $val['signdate_three'] != '0000-00-00') || ($officer == $val['user_four'] && $val['signdate_four'] != '0000-00-00') || ($officer == $val['user_five'] && $val['signdate_five'] != '0000-00-00')) {

											if(($officer == $val['user_one'] && strtotime($val['signdate_one']) >= strtotime($from) && strtotime($val['signdate_one']) <= strtotime($to)) || ($officer == $val['user_two'] && strtotime($val['signdate_two']) >= strtotime($from) && strtotime($val['signdate_two']) <= strtotime($to)) || ($officer == $val['user_three'] && strtotime($val['signdate_three']) >= strtotime($from) && strtotime($val['signdate_three']) <= strtotime($to)) || ($officer == $val['user_four'] && strtotime($val['signdate_four']) >= strtotime($from) && strtotime($val['signdate_four']) <= strtotime($to)) || ($officer == $val['user_five'] && strtotime($val['signdate_five']) >= strtotime($from) && strtotime($val['signdate_five']) <= strtotime($to))) {

												$approval_subj = trim($val['subject']);

												$sqlx = "SELECT * FROM {$tbL104} WHERE receiver={$officer} AND archivedata=1 AND subject LIKE '%{$approval_subj}%' AND deletedata=0";
												$fetch_jo = mysqli_data_array('assoc',$sqlx);

												
												if(is_array($fetch_jo) && count($fetch_jo) > 0) {

													$numbr += 1;
													
													$signatory = idget_data($tbL7,$officer,'staffname');

													if($officer == $val['user_one']) {
														//$signatory_note = $val['comment_one'];
														$signatory_note = date('d-m-Y',strtotime($val['signdate_one']));
													} elseif($officer == $val['user_two']) {
														//$signatory_note = $val['comment_two'];
														$signatory_note = date('d-m-Y',strtotime($val['signdate_two']));
													} elseif($officer == $val['user_three']) {
														//$signatory_note = $val['comment_three'];
														$signatory_note = date('d-m-Y',strtotime($val['signdate_three']));
													} elseif($officer == $val['user_four']) {
														//$signatory_note = $val['comment_four'];
														$signatory_note = date('d-m-Y',strtotime($val['signdate_four']));
													} elseif($officer == $val['user_five']) {
														//$signatory_note = $val['comment_five'];
														$signatory_note = date('d-m-Y',strtotime($val['signdate_five']));
													}


													if($val['approval_type'] == 'IOU') {
														$iou_sql = "SELECT * FROM {$tbL153} WHERE iou_no='{$val['subject']}'";
														$fetch_iou = mysqli_data_array('assoc',$iou_sql);
														if($fetch_iou[0]['pr_type'] == 'Manual') {
															$iou_sql_e = "SELECT * FROM {$tbL161} WHERE iou_no='{$val['subject']}'";
															$fetch_iou_e = mysqli_data_array('assoc',$iou_sql_e);

															$particular = $val['subject'];
															$approved_amt = $fetch_iou[0]['amount'];
															$approved_src = $fetch_iou_e[0]['iou_type'].' ('.$fetch_iou_e[0]['expense_type'].') - '.$fetch_iou_e[0]['detail'];
															$link = 'IOU';
															$link_alt = 'NIL';
														} else {
															$particular = $val['subject'];
															$approved_amt = $fetch_iou[0]['amount'];
															$approved_src = $fetch_iou[0]['pr_no'];
															$link = 'IOU';
															$link_alt = 'PR';
														}
													} elseif($val['approval_type'] == 'PR') {
														$pr_sql = "SELECT SUM(order_net_amount) AS amount FROM {$tbL121} WHERE order_number='{$val['subject']}'";
														$fetch_pr = mysqli_data_array('assoc',$pr_sql);

														$particular = $val['subject'];
														$approved_amt = $fetch_pr[0]['amount'];
													
														$storeID = idget_fdata($tbL121,'order_number',$val['subject'],'store');
														$approved_src = idget_data($tbL123,$storeID,'store_name');
														$link = 'PR';
														$link_alt = 'NIL';
													} else {
														$particular = $val['subject'];
														$approved_amt = 0;
														$approved_src = 'N/A';
														$link = 'TR';
														$link_alt = 'NIL';
													}

													?>

													<tr id="tr-<?php echo $numbr; ?>" ondblclick="document.getElementById('tr-<?php echo $numbr; ?>').remove()">
														<td width="50px" class="alignct"><?php echo $numbr; ?>.</td>
														<td width="150px" class="alignct"><?php echo date('d-m-Y',strtotime($fetch_jo[0]['datelogged'])).' '.$fetch_jo[0]['timelogged']; ?></td>
														<td width="200px" class="alignct"><?php echo $signatory; ?></td>
														<td width="150px" class="alignct"><?php echo $signatory_note; ?></td>
														<?php if($link == 'IOU'): ?>
														<td width="100px" class="alignct blue-font anchor" onclick="jsxView('<?php echo $val['subject']; ?>')"><?php echo $val['subject']; ?></td>
														<?php elseif($link == 'PR'): ?>
														<td width="100px" class="alignct blue-font anchor" onclick="jsxView('<?php echo $val['subject']; ?>')"><?php echo $val['subject']; ?></td>
														<?php elseif($link == 'TR'): ?>
														<td width="100px" class="alignct blue-font anchor" onclick="jsxView('<?php echo $val['subject']; ?>')"><?php echo $val['subject']; ?></td>
														<?php else: ?>
														<td width="100px" class="alignct blue-font anchor"><?php echo $particular; ?></td>
														<?php endif; ?>
														<td width="150px" class="alignct"><?php echo number_format($approved_amt,2); ?></td>
														<?php if($link_alt == 'PR'): ?>
															<td width="300px" class="alignct blue-font anchor" onclick="jsxView('<?php echo $approved_src; ?>')"><?php echo $approved_src; ?></td>
														<?php else: ?>
															<td width="300px" class="alignct"><?php echo $approved_src; ?></td>
														<?php endif; ?>
													</tr>

													<?php
													
												}
											}
										}
									}

								?>

							</table>

						<?php
					}
				}

			?>

		</div>
	</div>
</div>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>


<script>

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

function jsxView(key) {
	if(key.indexOf('PR') > -1) { popmodalframe('accounting','preview_pr',key,0,1200,800); }
	else if(key.indexOf('IOU') > -1) { popmodalframe('accounting','preview_pr_iou',key,0,900,800); }
}

</script>