<?php
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$iou_number = $ftoken;

	$query_iou = "SELECT * FROM {$tbL153} WHERE iou_no='{$iou_number}' AND deletedata=0";
	$wgt_iou = mysqli_data_array('assoc',$query_iou);

	$order_number = $wgt_iou[0]['pr_no'];

	$store_location = idget_fdata($tbL121,'order_number',$order_number,'store');
	$store_name = idget_data($tbL123,$store_location,'store_name');

	$pr_jo = idget_fdata($tbL161,'iou_no',$iou_number,'expense_type');
	$pr_iou_type = idget_fdata($tbL161,'iou_no',$iou_number,'iou_type');
	$pr_iou_desc = idget_fdata($tbL161,'iou_no',$iou_number,'detail');

	if(!empty($order_number)) {
		
		$label = "For LPO: ".$order_number;

		$iou_disburser = $wgt_iou[0]['disbursedby'];
		$disbursedby = idget_data($tbL7,$iou_disburser,'staffname');

		if($pr_jo == 'Job Order') {
			if(!empty($iou_disburser) && $iou_disburser > 0) { $for_Type = $pr_iou_type.' - '.$disbursedby; }
			else { $for_Type = $pr_iou_type; }
			$remark = $pr_iou_desc;
			$departmentid = idget_fdata($tbL161,'iou_no',$iou_number,'departmentid');
			$department = idget_data($tbL12,$departmentid,'department');

			$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$iou_number}' AND approval_type='IOU'";
			$jpL = mysqli_data_array('assoc',$isjp);

		} else {
			if(!empty($iou_disburser) && $iou_disburser > 0) { $for_Type = "GC Payment - ".$disbursedby; }
			else { $for_Type = "GC Payment"; }
			$remark = "Purchase request for (".$store_name.") with order number (".$order_number.")";
			$department = "Material Control";

			$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$order_number}' AND approval_type='IOU'";
			$jpL = mysqli_data_array('assoc',$isjp);
		}
		
		$iou_status = $wgt_iou[0]['status'];
		$iou_date = $wgt_iou[0]['datelogged']; $iou_time = $wgt_iou[0]['timelogged'];
		
		$amount = $wgt_iou[0]['amount'];

	} else {

		$label = $iou_number;
		$for_Type = idget_fdata($tbL161,'iou_no',$iou_number,'iou_type');
		$remark = idget_fdata($tbL161,'iou_no',$iou_number,'detail');

		$iou_status = $wgt_iou[0]['status'];
		$iou_date = $wgt_iou[0]['datelogged']; $iou_time = $wgt_iou[0]['timelogged'];

		$iou_disburser = $wgt_iou[0]['disbursedby'];
		$disbursedby = idget_data($tbL7,$iou_disburser,'staffname');
		
		$departmentid = idget_fdata($tbL161,'iou_no',$iou_number,'departmentid');
		$department = idget_data($tbL12,$departmentid,'department');

		$amount = $wgt_iou[0]['amount'];

		$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$iou_number}' AND approval_type='IOU'";
		$jpL = mysqli_data_array('assoc',$isjp);
	}

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
	
	<span class="float-right"><h2 class="large nobold"><u class="default-text-font-bold"><?php echo $label; ?></u></h2></span>
	<h2 class="xlarge nobold default-text-font-bold">IOU - <?php echo $iou_number; ?></h2>

	<div class="top-push-30 bottom-push-30">
		<table cellspacing="5" cellpadding="5">
			<tr>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Department</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Type/User</td>
				<td class="default-text-font-bold right-pull-10 left-pull-10">Date</td>
			</tr>
			<tr>
				<td class="right-pull-10 left-pull-10"><?php echo $department; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo $for_Type; ?></td>
				<td class="right-pull-10 left-pull-10"><?php echo date($nth_dfn,strtotime($iou_date)).' '.$iou_time; ?></td>
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
			<td class="right-pull-10 left-pull-10"><?php echo $remark; ?></td>
			<td class="right-pull-10 left-pull-10"><?php echo number_format($amount); ?></td>
			<td class="right-pull-10 left-pull-10"><?php echo $iou_status; ?></td>
		</tr>
	</table>

	<div class="cs-height-100">
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