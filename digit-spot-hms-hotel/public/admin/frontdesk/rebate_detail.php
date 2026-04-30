<?php
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$rebate_no = !empty($ftoken) ? $ftoken : null;

	if($rebate_no !== null):

		$sql = "SELECT * FROM {$tbL163} WHERE rebate_no='{$rebate_no}' AND deletedata=0";
		$data = wgetSQL($sql);

		$createdby = idget_data($tbL7,$data[0]['userid'],'staffname');

		echo $post_message;

?>

	
	<p class="alignrt bottom-pull-10">
		<input type="button" value="Print" onclick="window.print()">
	</p>

	<div id="section-to-print">

		<h3 class="large nobold default-text-font-bold alignct">-- Rebate Details --</h3><br>
	
		<table cellpadding="5" cellspacing="1" border="0">
			<tr>
				<td class="box-noborder cs-width-150">Status:<br><b class="nobold light-red-font"><?php echo $data[0]['status']; ?></b></td>
				<td class="box-noborder">Approval Status:<br><b class="nobold default-text-font-bold"><?php echo $data[0]['approval_status']; ?></b></td>
			</tr>
		</table>

		<div class="cs-height-10">
		</div>

		<table cellpadding="5" cellspacing="5" border="1">
			<tr>
				<td class="box-noborder">Sequence No.<br><b class="nobold"><?php echo $rebate_no; ?></b></td>
				<td class="box-noborder">&nbsp;</td>
				<td class="box-noborder">Rebate Type<br><b class="nobold"><?php echo $data[0]['rebate_type']; ?></b></td>
				<td class="box-noborder">&nbsp;</td>
				<td class="box-noborder">&nbsp;</td>
				<td class="box-noborder">Transaction Date:<br><b class="nobold"><?php echo date('d-m-Y',strtotime($data[0]['transaction_date'])); ?></b></td>
			</tr>
			
			<tr>
				<td class="box-noborder">Guest Name<br><b class="nobold"><?php echo $data[0]['guest_name']; ?></b></td>
				<td class="box-noborder">&nbsp;</td>
				<td class="box-noborder">Amount<br><b class="nobold">&#8358; <?php echo number_format($data[0]['amount'],2); ?></b></td>
				<td class="box-noborder">&nbsp;</td>
				<td class="box-noborder">Created By:<br><b class="nobold"><?php echo $createdby; ?></b></td>
			</tr>

			<tr>
				<td colspan="8" class="box-noborder">Remark<br><b class="nobold"><?php echo nl2br($data[0]['remark']); ?></b></td>
			</tr>
		</table>

		<?php
			
			$isjp = "SELECT * FROM {$tbL151} WHERE subject='{$rebate_no}' AND approval_type='RBT'";
			$jpL = wgetSQL($isjp);

			endif;


			if(is_array($jpL) && count($jpL) > 0) {
				?>
					<div class="cs-height-10">
					</div>

					<table cellpadding="5" cellspacing="5" border="0">
						<tr>
							<td class="box-noborder">
								<?php
									if($jpL[0]['user_one']) {
										$userone = idget_data($tbL7,$jpL[0]['user_one'],'staffname');
										$useroleid = idget_data($tbL7,$jpL[0]['user_one'],'role');
										$userole = idget_data($tbL4,$useroleid,'role');
										$af1_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_one']);
										$af1_status_color = wgtcolor($af1_approval_signed_status);
										?>
											<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userone; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
											<h4 class="large nobold" style="color: <?php echo $af1_status_color; ?>"><i><?php echo $af1_approval_signed_status; ?></i></h4>
													<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_one']; ?></h4>
										<?php
									}
								?>
							</td>
							<td class="box-noborder">
								<?php
									if($jpL[0]['user_two']) {
										$usertwo = idget_data($tbL7,$jpL[0]['user_two'],'staffname');
										$useroleid = idget_data($tbL7,$jpL[0]['user_two'],'role');
										$userole = idget_data($tbL4,$useroleid,'role');
										$af2_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_two']);
										$af2_status_color = wgtcolor($af2_approval_signed_status);
										?>
											<h3 class="large nobold default-text-font-bold nomargin"><?php echo $usertwo; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
											<h4 class="large nobold" style="color: <?php echo $af2_status_color; ?>"><i><?php echo $af2_approval_signed_status; ?></i></h4>
												<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_two']; ?></h4>
										<?php
									}
								?>
							</td>
							<td class="box-noborder">
								<?php
									if($jpL[0]['user_three']) {
										$userthree = idget_data($tbL7,$jpL[0]['user_three'],'staffname');
										$useroleid = idget_data($tbL7,$jpL[0]['user_three'],'role');
										$userole = idget_data($tbL4,$useroleid,'role');
										$af3_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_three']);
										$af3_status_color = wgtcolor($af3_approval_signed_status);
										?>
											<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userthree; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
											<h4 class="large nobold" style="color: <?php echo $af3_status_color; ?>"><i><?php echo $af3_approval_signed_status; ?></i></h4>
												<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_three']; ?></h4>
										<?php
									}
								?>
							</td>
							<td class="box-noborder">
								<?php
									if($jpL[0]['user_four']) {
										$userfour = idget_data($tbL7,$jpL[0]['user_four'],'staffname');
										$useroleid = idget_data($tbL7,$jpL[0]['user_four'],'role');
										$userole = idget_data($tbL4,$useroleid,'role');
										$af4_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_four']);
										$af4_status_color = wgtcolor($af4_approval_signed_status);
										?>
											<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfour; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>
											<h4 class="large nobold" style="color: <?php echo $af4_status_color; ?>"><i><?php echo $af4_approval_signed_status; ?></i></h4>
												<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_four']; ?></h4>
										<?php
									}
								?>
							</td>
							<td class="box-noborder">
								<?php
									if($jpL[0]['user_five']) {
										$userfive = idget_data($tbL7,$jpL[0]['user_five'],'staffname');
										$useroleid = idget_data($tbL7,$jpL[0]['user_five'],'role');
										$userole = idget_data($tbL4,$useroleid,'role');
										$af5_approval_signed_status = arrayget_key($approval_status,$jpL[0]['approval_five']);
										$af5_status_color = wgtcolor($af5_approval_signed_status);
										?>
											<h3 class="large nobold default-text-font-bold nomargin"><?php echo $userfive; ?></h3><h4 class="large nobold dark-grey-font"><?php echo $userole; ?></h4>

											<h4 class="large nobold" style="color: <?php echo $af5_status_color; ?>"><i><?php echo $af5_approval_signed_status; ?></i></h4>
												<h4 class="large nobold black-font"><?php echo $jpL[0]['comment_five']; ?></h4>
										<?php
									}
								?>
							</td>
						</tr>
					</table>
				<?php
			}

		?>

	</div>