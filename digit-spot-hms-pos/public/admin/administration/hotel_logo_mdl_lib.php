<?php $smdl = "administration"; $logs = $_GET['logs']; ?>

<div class="block-element bottom-push-30">
	<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Make changes to company logo below
</div>

<?php

	//guest photo update
	if(isset($_POST['logobutton']) && isset($_POST['dataurl']) && !empty($_POST['dataurl'])) {
		
		$image_upload_link = "../../theme/images/inc/";

		$encoded_data = str_replace(' ','+',$_POST['dataurl']);
		$binary_data = base64_decode($encoded_data);

		$actual_logo = $image_upload_link."logo.png";
		$alt_logo = $image_upload_link."mini-logo.png";

		@ unlink($actual_logo);
		@ unlink($alt_logo);

		file_put_contents($actual_logo, $binary_data);
		file_put_contents($alt_logo, $binary_data);
		
	}

	#---------------------------------------------------------------------------------------------------------------
?>

<div class="motion">
	<ul class="nolist">
		<li class="float-left nc-width-50">
			<h2 class="large nobold default-text-font-bold">Company Logo (PNG FORMAT)</h2><br>
			<div class="pads50 noscroll">
				<?php if(file_exists('../../theme/images/inc/logo.png')): ?>
					<img src="<?php echo DOMAIN_URL; ?>theme/images/inc/logo.png">
				<?php else: ?>
					<h1 class="xlarge nobold">No logo added. Upload a new logo</h1>
				<?php endif; ?>
			</div>
		</li>
		<li class="float-left nc-width-50 left-pull-50">
			<div class="white-theme xsml-rounded-button obj-shadow pads30 noscroll alignlt">
				<form action="" method="post" autocomplete="off" id="imgform" enctype="multipart/form-data">
					<div id="image-box" class="cs-height-350 box-border-dashed xsml-rounded-button pads20 noscroll">
						<div class="nc-height-20"></div>
						<div id="image-tip" class="alignct ft-mini-size dark-grey-font" onclick="document.getElementById('f').click()">
							<h2 class="xlarge nobold ft-tahoma royal-blue-font anchor">Click here to attach logo</h2>
						</div>
					</div>
					<input onchange="resizeimage(event,200,80,'dataurl','notupload','cimg','image-box'); writeObjheader('fmsg','attaching image..'); chgclass('imbutton','top-push-30')" type="file" id="f" style="position: fixed; top: -100em" accept=".png">
					<input type="hidden" name="dataurl" id="dataurl">
					<small id="fmsg" class="block-element red-font bottom-push-10 alignct"></small>
					<input type="hidden" name="wgtidx" id="wgtidx" value="<?php echo $wgt_pry_id; ?>" required="required">
					<div id="snap" class="noshow" align="center">
					</div>
					<div id="imbutton" class="noshow top-push-30" align="center">
						<input type="submit" name="logobutton" value="Apply Changes" class="nc-width-60 submit anchor top-pull-7 bottom-pull-7 dark-black-white-state sml-rounded-button default-text-font-bold right-push-10">
					</div>
					<div id="fmessage">
					</div>
				</form>
			</div>
		</li>
		<li>
		</li>
	</ul>
</div>