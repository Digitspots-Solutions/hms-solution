<?php

$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

createDatabasetable($var_tbl_308); //for city ledger

$update_result = '';
$post_result = '';
$htmlresult = '';

$joList = "";

$sqlJo = "SELECT order_number FROM {$tbL121} WHERE pr_status IN('Job Order') AND qty_received < qty_ordered AND first_approval=0 AND deletedata=0 GROUP BY order_number ORDER BY id DESC"; $showJo = wgetSQL($sqlJo);

if(is_array($showJo) && count($showJo)) {
	foreach($showJo as $key => $val) {
		$joList .= '<option value="'.$val['order_number'].'">'.$val['order_number'].'</option>';
	}
} else {
	$joList .= '<option value="">No Order Numbers</option>';
}

$get_departments = select_dt_fetch('deletedata',0,$tbL12,'id','department');
$amdl = 3; include "get_avail_workflow.php";

?>

<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: here you can create iou for expenses. Click <u>create iou</u> button
	</span>
	<span class="ln-display-box float-right top-pull-5">
		<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm()">Create IOU</a>
	</span>
	<span class="block-element new-line-space">
	</span>
</div>


<?php

	if(isset($_GET['iouform']) && $_GET['iouform'] == 'y') {
	
		?>
			<div id="tktBox" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
				<div class="cs-height-150"></div>
				<div id="rBox" class="fx-width-70 pads30 white-theme obj-dark-shadow xsml-rounded-button alignlt">
					<form id="iou-form" action="materialcontrol/workspace.php" method="post" autocomplete="off" onsubmit="fr_iou(event)">
						<input type="hidden" name="uri" value="apply-iou-expense-request">
						<span class="float-right"><input type="submit" name="transactionbutton" id="transactionbutton" value="Submit Entry" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button default-text-font-bold anchor"> <a href="javascript://" class="black-font default-text-font-bold left-push-10" title="Close" onclick="cancelPrSign()">Cancel x</a></span>
						<span class="float-right cs-width-150 dark-grey-theme top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 xsml-rounded-button right-push-30">
							<select name="workflow" id="workflow" class="nopads no-back-black">
								<?php echo $ths_workflow_names; ?>
							</select>
						</span>
						<h3 class="large nobold top-pull-5">IOU Form Entry</h3><br>
						
						<br><br>

						<ul class="nolist">
							<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
								<h4 class="xlarge nobold default-text-font-bold">IOU Type</h4>
								<select name="transactiontype" id="transactiontype" class="no-back-black" required>
									<option value="" selected>Choose?</option>
									<option value="Petty Cash">Petty Cash</option>
									<option value="GC Payment">GC Payment</option>
									<option value="Paymaster Payment">Paymaster Payment</option>
								</select>
							</li>
							<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
								<h4 class="xlarge nobold default-text-font-bold">Raise For</h4>
								<select name="expensetype" id="expensetype" class="no-back-black" onchange="chktype(this.value)" required>
									<option value="" selected>Choose?</option>
									<option value="Travel Expenses">Travel Expenses</option>
									<option value="Maintenance Expenses">Maintenance Expenses</option>
									<option value="Miscellaneous Expenses">Miscellaneous Expenses</option>
									<option value="Job Order">Job Order</option>
								</select>
								<div id="forjoborder" class="xform noshow">
									<select name="joborders" id="joborders" class="nopads no-back-black" onchange="getjo(this.value)">
										<option value="">Choose Job Order No.?</option>
										<?php echo $joList; ?>
									</select>
								</div>
							</li>
							<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
								<h4 class="xlarge nobold default-text-font-bold">Transaction Date</h4>
								<input type="date" name="transactiondate" id="transactiondate" class="no-back-black">
							</li>
							<li class="block-element new-line-space">
								&nbsp;
							</li>
							<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
								<h4 class="xlarge nobold default-text-font-bold">Amount</h4>
								<input type="text" name="wgtamount" id="wgtamount" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount')" class="no-back-black default-text-font-bold" required>
								<input type="hidden" name="amount" id="amount" required>
							</li>
							<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
								<h4 class="xlarge nobold default-text-font-bold">Department</h4>
								<select name="department" id="department" class="no-back-black" onchange="getdata('user','eget-department-user-list','department','dropbox')">
									<option value="" selected>Choose?</option>
									<?php echo $get_departments; ?>
								</select>
							</li>
							<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
								<h4 class="xlarge nobold default-text-font-bold">Beneficiary</h4>
								<select name="user" id="user" class="no-back-black">
									<option value="" selected>Choose?</option>
								</select>
							</li>
							<li class="block-element new-line-space">
								&nbsp;
							</li>
							<li class="grey-theme pads20 sml-rounded-button">
								<textarea name="remark" id="remark" placeholder="Write particulars here?" class="nopads no-back-black notextborder"></textarea>
							</li>
						</ul>
					</form>
				</div>
			</div>
		<?php
	}

	#update iou

	if(isset($_POST['etransactionbutton'])) {

		$constrain = array("id"=>$_POST['iouid']);
		$dataset = array("iou_type"=>$_POST['transactiontype'],"expense_type"=>$_POST['expensetype'],"departmentid"=>$_POST['department'],"receivedby"=>$_POST['user'],"detail"=>$_POST['remark'],"amount"=>$_POST['amount'],"iou_date"=>$_POST['transactiondate'],"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		mysqli_data_update($tbL161,$dataset,$constrain);


		$saynotify = 1;
		$notifytype = 2;
		
		$post_header = "Notification";
		$post_message = "IOU was edited successfully";
		
		$islogfile = 1;
		$logfile_msg = "IOU: ".$_POST['iouno']." was edited by this user";

		unset($_GET['editiou']);
	}


	if(isset($_GET['editiou']) && !empty($_GET['editiou'])) {
		
		$iou_id = escape_data($_GET['editiou']);

		$iou_no = idget_data($tbL161,$iou_id,'iou_no');
		$transaction_type = idget_data($tbL161,$iou_id,'iou_type');
		$expense_type = idget_data($tbL161,$iou_id,'expense_type');
		$iou_date = idget_data($tbL161,$iou_id,'iou_date');
		$iou_amount = idget_data($tbL161,$iou_id,'amount');
		$iou_desc = idget_data($tbL161,$iou_id,'detail');

		$department_id = idget_data($tbL161,$iou_id,'departmentid');
		$department_name = idget_data($tbL12,$department_id,'department');

		$receiver_id = idget_data($tbL161,$iou_id,'receivedby');
		$receiver = idget_data($tbL7,$receiver_id,'staffname');

		$entry_status = idget_data($tbL161,$iou_id,'status');

		if($entry_status === 'Under Approval') {

			?>
				<div id="tktBox-edit" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox-edit" class="fx-width-70 pads30 white-theme obj-dark-shadow xsml-rounded-button alignlt">
						<form action="" method="post" autocomplete="off" onsubmit="onsubmt('submitbutton','Submitting..')">
							<input type="hidden" name="uri" value="apply-iou-expense-edit">
							<input type="hidden" name="iouno" value="<?php echo $iou_no; ?>">
							<input type="hidden" name="iouid" value="<?php echo $iou_id; ?>">
							<span class="float-right"><input type="submit" name="etransactionbutton" id="etransactionbutton" value="Save Changes" class="blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button default-text-font-bold anchor"> <a href="javascript://" class="black-font default-text-font-bold left-push-10" title="Close" onclick="cancelIOU()">Cancel x</a></span>
							
							<h3 class="large nobold top-pull-5">Editing IOU</h3><br>
							
							<br><br>

							<ul class="nolist">
								<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
									<h4 class="xlarge nobold default-text-font-bold">IOU Type</h4>
									<select name="transactiontype" id="transactiontype" class="no-back-black" required>
										<?php if(isset($transaction_type) && !empty($transaction_type)) { ?><option value="<?php echo $transaction_type; ?>" selected><?php echo $transaction_type; ?></option><?php } else { ?><option value="" selected>Choose?</option><?php } ?>
										<option value="Petty Cash">Petty Cash</option>
										<option value="GC Payment">GC Payment</option>
										<option value="Paymaster Payment">Paymaster Payment</option>
									</select>
								</li>
								<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3 right-push-15">
									<h4 class="xlarge nobold default-text-font-bold">Raise For</h4>
									<select name="expensetype" id="expensetype" class="no-back-black" onchange="chktype(this.value)" required>
										<?php if(isset($expense_type) && !empty($expense_type)) { ?><option value="<?php echo $expense_type; ?>" selected><?php echo $expense_type; ?></option><?php } else { ?><option value="" selected>Choose?</option><?php } ?>
										<option value="Travel Expenses">Travel Expenses</option>
										<option value="Maintenance Expenses">Maintenance Expenses</option>
										<option value="Miscellaneous Expenses">Miscellaneous Expenses</option>
										<option value="Job Order">Job Order</option>
									</select>
									<div id="forjoborder" class="xform noshow">
										<select name="joborders" id="joborders" class="nopads no-back-black" onchange="getjo(this.value)">
											<option value="">Choose Job Order No.?</option>
											<?php echo $joList; ?>
										</select>
									</div>
								</li>
								<li class="ln-display-box float-left box-border-thick-bottom right-pull-20 left-pull-20 bottom-push-3">
									<h4 class="xlarge nobold default-text-font-bold">Transaction Date</h4>
									<input type="date" name="transactiondate" id="transactiondate" value="<?php if(isset($iou_date) && !empty($iou_date)) { echo $iou_date; } ?>" class="no-back-black">
								</li>
								<li class="block-element new-line-space">
									&nbsp;
								</li>
								<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
									<h4 class="xlarge nobold default-text-font-bold">Amount</h4>
									<input type="text" name="wgtamount" id="wgtamount" placeholder="0.00" onkeyup="numberinputFormat(this.value,this.id,'amount')" value="<?php if(isset($iou_amount) && !empty($iou_amount)) { echo number_format($iou_amount); } ?>" class="no-back-black default-text-font-bold">
									<input type="hidden" name="amount" id="amount" value="<?php if(isset($iou_amount) && !empty($iou_amount)) { echo $iou_amount; } ?>" required>
								</li>
								<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3 right-push-15">
									<h4 class="xlarge nobold default-text-font-bold">Department</h4>
									<select name="department" id="department" class="no-back-black" onchange="getdata('user','eget-department-user-list','department','dropbox')">
										<?php if(isset($department_id) && !empty($department_id)) { ?><option value="<?php echo $department_id; ?>" selected><?php echo $department_name; ?></option><?php } else { ?><option value="" selected>Choose?</option><?php } ?>
										<?php echo $get_departments; ?>
									</select>
								</li>
								<li class="ln-display-box float-left right-pull-20 left-pull-20 box-border-thick-bottom bottom-push-3">
									<h4 class="xlarge nobold default-text-font-bold">Beneficiary</h4>
									<select name="user" id="user" class="no-back-black">
										<?php if(isset($receiver_id) && !empty($receiver_id)) { ?><option value="<?php echo $receiver_id; ?>" selected><?php echo $receiver; ?></option><?php } else { ?><option value="" selected>Choose?</option><?php } ?>
									</select>
								</li>
								<li class="block-element new-line-space">
									&nbsp;
								</li>
								<li class="grey-theme pads20 sml-rounded-button">
									<textarea name="remark" id="remark" placeholder="Write particulars here?" class="nopads no-back-black notextborder"><?php if(isset($iou_desc) && !empty($iou_desc)) { echo $iou_desc; } ?></textarea>
								</li>
							</ul>
						</form>
					</div>
				</div>
			<?php

		} else {
			?>
				<div id="tktBox-edit" class="fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion" align="center">
					<div class="cs-height-150"></div>
					<div id="rBox-edit" class="fx-width-40 pads30 white-theme obj-dark-shadow xsml-rounded-button alignct">
						<h3 class="xlarge nobold default-text-font-bold">IOU can only be edited when is under approval. Check and try again</h3>
						<p class="top-pull-20 alignct">
							<a href="javascript://" class="blue-font left-push-10" title="Close" onclick="cancelIOU()">Cancel x</a>
						</p>
					</div>
				</div>
			<?php
		}
	}

?>

<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="cs-width-1500">
			<div id="section-to-print">
				<?php
					
					$tbl = $tbL161;
					
					$startnumbr = 0;
					$keywords = "";

					$in5days = date('Y-m-d',strtotime('7 days'));

					$queryset = "deletedata=0 AND datelogged BETWEEN '{$server_get_date}' AND '{$in5days}'".$keywords;

					$keys = array(
						"iou_no"=>"(fx)iou no.",
						"iou_type"=>"iou type",
						"expense_type"=>"raised for",
						"departmentid"=>"department",
						"receivedby"=>"beneficiary",
						"amount"=>"(nf)amount &#8358;",
						"iou_date"=>"(df)transaction date",
						"status"=>"status"
					);

					$format = array(
						"grid",
						"allow-edit"
					);

					$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $datasheet;

				?>
			</div>
		</div>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsxView(key) {
		popmodalframe('accounting','preview_iou',key,0,900,800);
	}


	function fr_iou(e) {
		e.preventDefault();
		var frm, vhtml, f = document.getElementById('iou-form');
		f.setAttribute('target','tframe');

		var button = document.getElementById('transactionbutton');
		button.value = "Sending..";
		button.setAttribute('type','button');

		vhtml = '';
		vhtml += '<div class="fx-position-fixed fscr zind-2 motion top-pull-50 txp8-white" align="center">';
		vhtml += '<h3 class="large nobold default-text-font-bold">Sending request, please wait..</h3>';
		vhtml += '<div id="xfr" class="noshow white-theme pads20 top-push-50">';
		vhtml += '</div>';
		vhtml += '</div>';

		frm = document.createElement('iframe');
		frm.width = '100%';
		frm.height = '100%';
		frm.name = 'tframe';
		frm.id = 'tframe';

		writeObjheader('fbox',vhtml);
		document.getElementById('xfr').appendChild(frm);
		setTimeout(() => { f.submit(); },1000);
		setTimeout(() => { writeObjheader('fbox',''); window.location.href = sessionStorage.getItem('iouuri'); },3000);
	}

	function jsForm(fr) {
		if(sessionStorage.getItem('iouuri') !== null && sessionStorage.getItem('iouuri') != 'undefined') {
			var uri,params,wp;
			uri = sessionStorage.getItem('iouuri');
			window.location.href = uri+'&iouform=y';
		}
	}

	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	function cancelIOU() {
		chgclass('tktBox-edit','xfadein noshow motion');
		chgclass('rBox-edit','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	window.onload = () => {
		if(sessionStorage.getItem('iouuri') == null || sessionStorage.getItem('iouuri') == 'undefined') {
			sessionStorage.setItem('iouuri',window.location.href);
		}
	}


	function jsEdit(id) {
		window.location.href = window.location.href+'&editiou='+id;
	}


	function chktype(val) {
		if(val == 'Job Order') {
			chgclass('forjoborder','xform');
			document.getElementById('joborders').setAttribute('required','required');
			if(document.getElementById('remark')) { document.getElementById('remark').value = "Payment raised for job order"; }
		} else {
			chgclass('forjoborder','xform noshow');
			document.getElementById('joborders').removeAttribute('required');
			if(document.getElementById('remark')) { document.getElementById('remark').value = ""; }
		}
	}


	function getjo(val) {
		if(val !== null && val !='') {
			if(document.getElementById('remark')) { document.getElementById('remark').value = "Payment raised for job order ("+val+")"; }
		}
	}

</script>