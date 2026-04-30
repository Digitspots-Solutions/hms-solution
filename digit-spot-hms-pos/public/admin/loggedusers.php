<?php

	$additionalQuery = " GROUP BY userid";
	$logged_query = array("datelogged"=>$server_get_date);
	$select_logged_user = mysqli_data_fetch('user_log_tbl','userid',$logged_query,'array');

	$staff_name = ""; $roleid = ""; $role_name = "";

	if(is_array($select_logged_user)):
		foreach($select_logged_user as $key => $val):

			$staff_name = idget_data($tbL7,$val['userid'],'staffname');
			$roleid = idget_data($tbL7,$val['userid'],'role');
			
			$role_name = (!empty($roleid)) ? idget_data($tbL4,$roleid,'role') : 'System Admin';

			?>

				<div class="bottom-push-10">
					<ul class="nolist">
						<li class="float-left cs-width-100">
							<div class="box-border-thick rounded-element cs-width-60 cs-height-60 noscroll">
								<img src="<?php echo DOMAIN_URL; ?>theme/images/general/photo.png">
							</div>
						</li>
						<li class="float-left cs-width-250">
							<h3 class="xlarge nobold ft-tahoma"><?php echo $staff_name; ?></h3>
							<h3 class="large nobold default-text-font"><?php echo $role_name; ?></h3>
						</li>
						<li class="block-element new-line-space">
						</li>
					</ul>
				</div>

			<?php

		endforeach;
	endif;
	
?>