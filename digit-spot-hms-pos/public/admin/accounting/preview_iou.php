<?php
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$iou_number = $ftoken;

	$query_iou = "SELECT * FROM {$tbL161} WHERE iou_no='{$iou_number}' AND deletedata=0";
	$wgt_iou = mysqli_data_array('assoc',$query_iou);

	$iou_status = $wgt_iou[0]['status']; $iou_amount = $wgt_iou[0]['amount'];
	$iou_date = $wgt_iou[0]['iou_date']; $iou_detail = $wgt_iou[0]['detail'];
	$iou_type = $wgt_iou[0]['iou_type']; $iou_expense = $wgt_iou[0]['expense_type'];

	$iou_department = $wgt_iou[0]['departmentid'];
	$department = idget_data($tbL12,$iou_department,'department');

	$iou_receiver = $wgt_iou[0]['receivedby'];
	$receivedby = idget_data($tbL7,$iou_receiver,'staffname');

	$iou_user = $wgt_iou[0]['userid'];
	$createdby = idget_data($tbL7,$iou_user,'staffname');
	
	
	$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$iou_number}' AND approval_type='IOU'";
	$jpL = mysqli_data_array('assoc',$isjp);

?>
<p class="bottom-pull-30 alignrt">
	<a href="javascript:void(0)" class="blue-font ft-sml-size default-text-font-bold" onclick="window.print()">Print <b class="fa-print nobold left-push-5"></b></a>
</p>
<div id="section-to-print" class="block-element">
	
	<div class="bottom-push-30">
		<span class="float-left"><img src="<?php echo _FC_LOGO; ?>"></span>
		<h1 class="large nobold default-text-font-bold alignrt"><?php echo _LONG_NAME; ?></h1>
	</div>
	<div class="cs-height-50">
	</div>
	
	<span class="float-right">
		<h2 class="xlarge nobold default-text-font-bold">IOU - <u><?php echo $iou_number; ?></u></h2>
	</span>
	

	<div class="top-push-30 bottom-push-10">
		<table cellspacing="5" cellpadding="5">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">IOU Type</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Expense Type</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Date</td>
			</tr>
			<tr>
				<td class="right-pull-10 left-pull-10"><?php echo $iou_type; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo $iou_expense; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($iou_date)); ?></td>
			</tr>
		</table>
	</div>

	<div class="bottom-push-10">
		<table cellspacing="5" cellpadding="5">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Department</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Beneficiary</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Created By</td>
			</tr>
			<tr>
				<td class="right-pull-10 left-pull-10"><?php echo $department; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo $receivedby; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo $createdby; ?></td>
			</tr>
		</table>
	</div>


	<table cellspacing="5" cellpadding="5">
		<tr>
			<td class="default-text-font-bold right-pull-10 left-pull-10">Particular</td>
			<td class="default-text-font-bold right-pull-10 left-pull-10">Amount (&#8358;)</td>
			<td class="default-text-font-bold right-pull-10 left-pull-10">Payment Status</td>
		</tr>
		<tr>
			<td class="right-pull-10 left-pull-10"><?php echo $iou_detail; ?></td>
			<td class="right-pull-10 left-pull-10"><?php echo number_format($iou_amount); ?></td>
			<td class="right-pull-10 left-pull-10"><?php echo $iou_status; ?></td>
		</tr>
	</table>

	<div class="cs-height-50">
	</div>

	<h3 class="large nobold default-text-font-bold">Authorization Detail</h3><br>

	<?php
		
		if(is_array($jpL) && count($jpL)) {
			
			?>
				<div class="pads20 bottom-push-10 alignlt">
					<ul class="nolist">
						<li class="ln-display-box float-left right-pull-30">
							<?php
								if($jpL[0]['user_one']) {
									$userone = idget_data($tbL7,$jpL[0]['user_one'],'staffname');
									$useroleid = idget_data($tbL7,$jpL[0]['user_one'],'role');
									$userole = idget_data($tbL4,$useroleid,'role');
									$af1_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_one']);
									$af1_status_color = wgtcolor($af1_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userone; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left right-pull-30">
							<?php
								if($jpL[0]['user_two']) {
									$usertwo = idget_data($tbL7,$jpL[0]['user_two'],'staffname');
									$useroleid = idget_data($tbL7,$jpL[0]['user_two'],'role');
									$userole = idget_data($tbL4,$useroleid,'role');
									$af2_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_two']);
									$af2_status_color = wgtcolor($af2_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $usertwo; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left right-pull-30">
							<?php
								if($jpL[0]['user_three']) {
									$userthree = idget_data($tbL7,$jpL[0]['user_three'],'staffname');
									$useroleid = idget_data($tbL7,$jpL[0]['user_three'],'role');
									$userole = idget_data($tbL4,$useroleid,'role');
									$af3_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_three']);
									$af3_status_color = wgtcolor($af3_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userthree; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left right-pull-30">
							<?php
								if($jpL[0]['user_four']) {
									$userfour = idget_data($tbL7,$jpL[0]['user_four'],'staffname');
									$useroleid = idget_data($tbL7,$jpL[0]['user_four'],'role');
									$userole = idget_data($tbL4,$useroleid,'role');
									$af4_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_four']);
									$af4_status_color = wgtcolor($af4_approval_signed_status);
									
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfour; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
									<?php
								}
							?>
						</li>
						<li class="ln-display-box float-left right-pull-30">
							<?php
								if($jpL[0]['user_five']) {
									$userfive = idget_data($tbL7,$jpL[0]['user_five'],'staffname');
									$useroleid = idget_data($tbL7,$jpL[0]['user_five'],'role');
									$userole = idget_data($tbL4,$useroleid,'role');
									$af5_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_five']);
									$af5_status_color = wgtcolor($af5_approval_signed_status);
									?>
										<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfive; ?></h3><h4 class="large nobold"><?php echo $userole; ?></h4>

										<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
											<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
									<?php
								}
							?>
						</li>
						<li class="block-element new-line-space">
						</li>
					</ul>
				</div>
			<?php
		}

	?>
</div>