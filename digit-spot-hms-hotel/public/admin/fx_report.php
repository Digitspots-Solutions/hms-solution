<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; 

$print_business_date = write_dateF(6,$server_get_bizsdate);
if(isset($server_get_auditdate) && !empty($server_get_auditdate)) { $print_last_audit_date = write_dateF(6,$server_get_auditdate); } else { $print_last_audit_date = "N/A"; }

$row = "array";
$constrain = array("moduleid"=>9,"status"=>"Active");
$dataCollect = mysqli_data_fetch($tbL10,'id,category',$constrain,$row);

$_SESSION['sesid'] = "report";
if(isset($_SESSION['postoreid'])) { unset($_SESSION['postoreid']); }

if(isset($myaccess) && $myaccess == 'super admin') {
	
	if(is_array($dataCollect))
	{
		?>

		<div class="block-element cs-height-100 sky-blue-theme top-pull-15 right-pull-10 left-pull-10">
			<small class="block-element"><b>Business Status</b></small>
			<div class="block-element top-pull-5 bottom-pull-15 bottom-push-5 ft-xsml-size">
				<small class="block-element bottom-push-3">Audit Done till: <b><?php echo $print_last_audit_date; ?></b></small><small class="block-element bottom-push-3">Business Date: <b><?php echo $print_business_date; ?></b></small>
				<small class="block-element">Shift: <b>N/A</b></small>
			</div>
		</div>

		<?php
		$submdls=''; $submdl_key=''; $onclick='';

		//menu with submenus

		foreach ($dataCollect as $ckey => $cvalue) {
			
			if($cvalue['category'] != 'Non-module') {
				
				$submdl_key = array("categoryid"=>$cvalue['id'],"status"=>"Active");
				mysqli_data_check($tbL11,'(*)',$submdl_key);
				$submdls = $numOfrows;

				if(isset($submdls) && $submdls >= 1) {
					
					$submdl_data = mysqli_data_fetch($tbL11,'id,name',$submdl_key,'array');

					?>
					<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
						<?php if($cvalue['category'] != 'Revenue Report' && $_SESSION['app_service'] == 'hotel-management'): ?>
						<div id="cmdl1<?php echo $cvalue['id']; ?>" lang="uncollapsed" class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 sky-dark-deep-blue-theme dark-black-font anchor box-border-thick-bottom" onclick="forsubmenu('cmdl1<?php echo $cvalue['id']; ?>','lmdl1<?php echo $cvalue['id']; ?>','xlmdl1<?php echo $cvalue['id']; ?>')">
							<span class="ln-display-box float-left nc-width-90">
								<small class=""><?php echo $cvalue['category']; ?></small>
							</span>
							<span class="ln-display-box float-left nc-width-10 alignct">
								<b class="fa-arrow-down nobold ft-xsml-size"></b>
							</span>
							<span class="block-element new-line-space">
								<!-- clear line -->
							</span>
						</div>
						<?php endif; ?>
						<div id="lmdl1<?php echo $cvalue['id']; ?>" class="csp-height-0 motion">
							<div id="xlmdl1<?php echo $cvalue['id']; ?>" class="noshow motion">
								<?php
									foreach ($submdl_data as $subkey => $subvalue) {
										if($_SESSION['app_service'] == 'hotel-management' && $subvalue['name'] != 'Pos Revenue Report') {
											?>
												<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom  sub-menu-class motion" onclick="loadframe('<?php echo $subvalue['name']; ?>',<?php echo $subvalue['id']; ?>)">
													<small><?php echo $subvalue['name']; ?></small>
												</div>
											<?php
										}
									}
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}

		//menu with no submenus

		foreach ($dataCollect as $xckey => $xcvalue) {
			
			$submdl_key = array("categoryid"=>$xcvalue['id'],"status"=>"Active");
			mysqli_data_check($tbL11,'(*)',$submdl_key);
			$submdls = $numOfrows;

			if($submdls == 0 && $xcvalue['category'] != 'Pos Revenue Report') {
				
				?>
				<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
					<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom motion nosub-menu-class" onclick="loadframe('<?php echo $xcvalue['category']; ?>',<?php echo $xcvalue['id']; ?>)">
						<small><?php echo $xcvalue['category']; ?></small>
					</div>
				</div>
				<?php
			}
		}
	}
	else
	{
		?>
			<div class="block-element"> No menu list </div>
		<?php
	}

}
else
{
	$isprivilege = perform_role_check($tbL5,$myrole,9,0);
	if(isset($isprivilege) && $isprivilege >= 1) {
		
		if(is_array($dataCollect))
		{
			?>

			<div class="block-element cs-height-120 sky-blue-theme top-pull-15 right-pull-10 left-pull-10">
				<small class="block-element"><b>Business Status</b></small>
				<div class="block-element top-pull-5 bottom-pull-15 bottom-push-5 ft-xsml-size">
					<small class="block-element bottom-push-3">Audit Done till: <b><?php echo $print_last_audit_date; ?></b></small><small class="block-element bottom-push-3">Business Date: <b><?php echo $print_business_date; ?></b></small>
					<small class="block-element bottom-push-3">Shift: <b><?php echo $print_shift; ?></b></small>
					<?php if(isset($_SESSION['counter_id']) && $_SESSION['counter_id'] >= 1) { ?><a href="<?php echo DOMAIN_URL; ?>login/close_counter<?php echo PHP_EXT; ?>?sesid=ny" class="blue-font"><u>Close Counter: <?php echo $print_counter; ?></u></a><?php } ?>
				</div>
			</div>

			<?php
			$submdls=''; $submdl_key=''; $onclick='';

			//menu with submenus

			$xinner_isprivilege = "";

			foreach ($dataCollect as $ckey => $cvalue) {
				
				if($cvalue['category'] != 'Non-module') {
					
					$submdl_key = array("categoryid"=>$cvalue['id'],"status"=>"Active");
					mysqli_data_check($tbL11,'(*)',$submdl_key);
					$submdls = $numOfrows;

					if(isset($submdls) && $submdls >= 1) {
						
						$submdl_data = mysqli_data_fetch($tbL11,'id,name',$submdl_key,'array');

						$xinner_isprivilege = perform_role_check($tbL5,$myrole,$cvalue['id'],888);
						if(isset($xinner_isprivilege) && $xinner_isprivilege >= 1)
						{
							?>
							<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
								<div id="cmdl1<?php echo $cvalue['id']; ?>" lang="uncollapsed" class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 sky-dark-deep-blue-theme dark-black-font anchor box-border-thick-bottom" onclick="forsubmenu('cmdl1<?php echo $cvalue['id']; ?>','lmdl1<?php echo $cvalue['id']; ?>','xlmdl1<?php echo $cvalue['id']; ?>')">
									<span class="ln-display-box float-left nc-width-90">
										<small class=""><?php echo $cvalue['category']; ?></small>
									</span>
									<span class="ln-display-box float-left nc-width-10 alignct">
										<b class="fa-arrow-down nobold ft-xsml-size"></b>
									</span>
									<span class="block-element new-line-space">
										<!-- clear line -->
									</span>
								</div>
								<div id="lmdl1<?php echo $cvalue['id']; ?>" class="csp-height-0 motion">
									<div id="xlmdl1<?php echo $cvalue['id']; ?>" class="noshow motion">
										<?php
											$inner_isprivilege = "";
											foreach ($submdl_data as $subkey => $subvalue) {
												$inner_isprivilege = perform_role_check($tbL5,$myrole,$subvalue['id'],'');
												if(isset($inner_isprivilege) && $inner_isprivilege >= 1) {
													?>
														<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom  sub-menu-class motion" onclick="loadframe('<?php echo $subvalue['name']; ?>',<?php echo $subvalue['id']; ?>)">
															<small><?php echo $subvalue['name']; ?></small>
														</div>
													<?php
												}
											}
										?>
									</div>
								</div>
							</div>
							<?php
						}
					}
				}
			}

			//menu with no submenus

			$xxinner_isprivilege = "";

			foreach ($dataCollect as $xckey => $xcvalue) {
				
				$submdl_key = array("categoryid"=>$xcvalue['id'],"status"=>"Active");
				mysqli_data_check($tbL11,'(*)',$submdl_key);
				$submdls = $numOfrows;

				if($submdls == 0 && $xcvalue['category'] != 'Pos Revenue Report') {
					$xxinner_isprivilege = perform_role_check($tbL5,$myrole,$xcvalue['id'],888);
					if(isset($xxinner_isprivilege) && $xxinner_isprivilege >= 1) {
						?>
						<div id="mdl1<?php echo $cvalue['id']; ?>" class="block-element">
							<div class="block-element top-pull-10 right-pull-10 bottom-pull-10 left-pull-10 anchor box-border-thick-bottom motion nosub-menu-class" onclick="loadframe('<?php echo $xcvalue['category']; ?>',<?php echo $xcvalue['id']; ?>)">
								<small><?php echo $xcvalue['category']; ?></small>
							</div>
						</div>
						<?php
					}
				}
			}
		}
		else
		{
			?>
				<div class="block-element"> No menu list </div>
			<?php
		}

	} else {
		?>
			<div class="block-element red-font pads10 top-push-50 add-bold" align="center"> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/warning.png"> <br><br> You are not authorized to use this module! </div>
		<?php
	}
}

?>

<input type="hidden" id="access-mode" value="1">