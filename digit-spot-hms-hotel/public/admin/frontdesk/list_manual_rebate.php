<?php
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	$approval_status = array(0=>"Not signed",1=>"Approved",2=>"On Hold",3=>"Rejected");

	$post_message = "";

	#-----------------------------------------------------------------------------------------------

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$request = isset($_POST['uri']) ? $_POST['uri'] : "";

		if($request === 'apply-rebate-approval') {

			$commentpr = escape_data($_POST['commentpr']);
			$rebateno = escape_data($_POST['rebateno']);
			$level = escape_data($_POST['level']);
			$signatory = escape_data($_POST['signatory']);
			
			$pst_query = array();
			$pst_field = array();

			$pst_query['subject'] = $rebateno;
			$pst_query['approval_type'] = 'RBT';

			if($level == 1) {
				$pst_field['approval_one'] = $signatory;
				$pst_field['comment_one'] = $commentpr;
			} elseif($level == 2) {
				$pst_field['approval_two'] = $signatory;
				$pst_field['comment_one'] = $commentpr;
			} elseif($level == 3) {
				$pst_field['approval_three'] = $signatory;
				$pst_field['comment_one'] = $commentpr;
			} elseif($level == 4) {
				$pst_field['approval_four'] = $signatory;
				$pst_field['comment_one'] = $commentpr;
			} elseif($level == 5) {
				$pst_field['approval_five'] = $signatory;
				$pst_field['comment_one'] = $commentpr;
			}

			$result = mysqli_data_update($tbL151,$pst_field,$pst_query);

			if($result == 2) {

				$rbt_level = "SELECT job_level FROM $tbL151 WHERE subject='{$rebateno}' AND approval_type='RBT'";
				$show_rbt_level = wgetSQL($rbt_level);

				$get_rbt_job_level = $show_rbt_level[0]['job_level'];
				
				if($get_rbt_job_level == $level && $signatory == 1) { 

					$pst_field = array();
					$pst_field['approval_status'] = 'Completed';

					mysqli_data_update($tbL151,$pst_field,$pst_query);

					#end

					$pst_query = array();
					$pst_field = array();

					$pst_query['rebate_no'] = $rebateno;
					$pst_field['status'] = 'Completed';
					$pst_field['approval_status'] = 'Approved';

					mysqli_data_update($tbL163,$pst_field,$pst_query);

				} else {

					$pst_query = array();
					$pst_field = array();

					$pst_query['rebate_no'] = $rebateno;
					$pst_field['status'] = 'Processing';
					$pst_field['approval_status'] = 'Approval process is in progress at stage '.$level;

					mysqli_data_update($tbL163,$pst_field,$pst_query);
				}

				$post_message = '<div class="grey-theme pads10 alignct top-push-5 bottom-push-5">Approval acknowledgment received</div>';

			} else {
				$post_message = '<div class="red-theme white-font pads10 alignct bottom-push-30">Error processing approval. Try again</div>';
			}
		}
	}

	#-----------------------------------------------------------------------------------------------

	$rebate_no = isset($_GET['rbt']) ? $_GET['rbt'] : null;

	if($rebate_no !== null):

		$sql = "SELECT * FROM {$tbL163} WHERE rebate_no='{$rebate_no}' AND deletedata=0";
		$data = wgetSQL($sql);

		$createdby = idget_data($tbL7,$data[0]['userid'],'staffname');

		echo $post_message;

?>

	<h3 class="large nobold default-text-font-bold">Rebate Approval Request</h3>
	<h4 class="large nobold">Status: <b class="nobold light-red-font"><?php echo $data[0]['status']; ?></b></h4><br>

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
			<div class="cs-height-20">
			</div>

			<h4 class="large nobold alignct">-- Approval Section --</h4><br>

			<table cellpadding="5" cellspacing="5" border="0">
				<tr>
					<td class="box-noborder cs-width-200">
						<?php
							if($jpL[0]['user_one']) {
								$userone = idget_data($tbL7,$jpL[0]['user_one'],'staffname');
								$useroleid = idget_data($tbL7,$jpL[0]['user_one'],'role');
								$userole = idget_data($tbL4,$useroleid,'role');
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
											<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,1)">
												<option value="" selected>Choose to sign</option>
												<option value="1">Approve</option>
												<option value="2">On Hold</option>
												<option value="3">Reject</option>
											</select>
										<?php
									} else {
										if($jpL[0]['approval_one'] == 2) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,1)">
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
					</td>
					<td class="box-noborder cs-width-200">
						<?php
							if($jpL[0]['user_two']) {
								$usertwo = idget_data($tbL7,$jpL[0]['user_two'],'staffname');
								$useroleid = idget_data($tbL7,$jpL[0]['user_two'],'role');
								$userole = idget_data($tbL4,$useroleid,'role');
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
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,2)">
													<option value="" selected>Choose to sign</option>
													<option value="1">Approve</option>
													<option value="2">On Hold</option>
													<option value="3">Reject</option>
												</select>
											<?php
										} else {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,2)" disabled="disabled">
													<option value="0" selected>Signatory Locked</option>
												</select>
											<?php
										}
									} else {
										if($jpL[0]['approval_two'] == 2) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,2)">
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
					</td>
					<td class="box-noborder cs-width-200">
						<?php
							if($jpL[0]['user_three']) {
								$userthree = idget_data($tbL7,$jpL[0]['user_three'],'staffname');
								$useroleid = idget_data($tbL7,$jpL[0]['user_three'],'role');
								$userole = idget_data($tbL4,$useroleid,'role');
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
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,3)">
													<option value="" selected>Choose to sign</option>
													<option value="1">Approve</option>
													<option value="2">On Hold</option>
													<option value="3">Reject</option>
												</select>
											<?php
										} else {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,3)" disabled="disabled">
													<option value="0" selected>Signatory Locked</option>
												</select>
											<?php
										}
									} else {
										if($jpL[0]['approval_three'] == 2) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,3)">
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
					</td>
					<td class="box-noborder cs-width-200">
						<?php
							if($jpL[0]['user_four']) {
								$userfour = idget_data($tbL7,$jpL[0]['user_four'],'staffname');
								$useroleid = idget_data($tbL7,$jpL[0]['user_four'],'role');
								$userole = idget_data($tbL4,$useroleid,'role');
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
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,4)">
													<option value="" selected>Choose to sign</option>
													<option value="1">Approve</option>
													<option value="2">On Hold</option>
													<option value="3">Reject</option>
												</select>
											<?php
										} else {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,4)" disabled="disabled">
													<option value="0" selected>Signatory Locked</option>
												</select>
											<?php
										}
									} else {
										if($jpL[0]['approval_four'] == 2) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,4)">
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
					</td>
					<td class="box-noborder cs-width-200">
						<?php
							if($jpL[0]['user_five']) {
								$userfive = idget_data($tbL7,$jpL[0]['user_five'],'staffname');
								$useroleid = idget_data($tbL7,$jpL[0]['user_five'],'role');
								$userole = idget_data($tbL4,$useroleid,'role');
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
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,5)">
													<option value="" selected>Choose to sign</option>
													<option value="1">Approve</option>
													<option value="2">On Hold</option>
													<option value="3">Reject</option>
												</select>
											<?php
										} else {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,5)" disabled="disabled">
													<option value="0" selected>Signatory Locked</option>
												</select>
											<?php
										}
									} else {
										if($jpL[0]['approval_five'] == 2) {
											?>
												<select name="signpr" id="signpr" onchange="signPr('<?php echo $rebate_no; ?>',this.id,5)">
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
					</td>
				</tr>
			</table>
		<?php
	}

?>

<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
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
			vhtml += '<input type="hidden" name="uri" value="apply-rebate-approval">';
			vhtml += '<div class="alignlt">';
			vhtml += '<label>Write a comment if applicable</label>';
			vhtml += '<textarea name="commentpr" id="commentpr" placeholder="Type here.." class="notextborder"></textarea>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="hidden" name="rebateno" id="rebateno" value="'+order+'">';
			vhtml += '<input type="hidden" name="signatory" id="signatory" value="'+wgets.value+'">';
			vhtml += '<input type="hidden" name="level" id="level" value="'+level+'">';
			vhtml += '<input type="submit" id="approvebutton" name="approvebutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
			vhtml += '</div>';
			vhtml += '</form>';
			
			writeObjheader('rBox',vhtml);
			parent.document.getElementById('workspace').scrollTop = 0;
		}
	}


	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

</script>