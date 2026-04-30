<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include BWF_PATH.ROOT_FLD._USRP_; include BWF_PATH.ROOT_FLD._APPMODULES_;
	
	$_SESSION['app_service'] = (!empty($_GET['app_service'])) ? $_GET['app_service'] : null;
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
	<title><?php echo _CTITLE_; ?></title>
	<meta name="format-detection" content="telephone=no" />
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="robots" content="index, nofollow" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="touch-startup-image" href="">
	<meta name="theme-color" content="#000000">
	<meta name="description" content="HMS: Hotel Management Software">
	<meta name="application-name" content="HMS v1.0">
	<link rel="shortcut icon" href="../assets/theme/fav-icon.png?ver=1" type="images/x-icon"/>
	<link rel="stylesheet" href="../assets/css3.0/default.css?ver=1"/>

	<style>
	
		.hotel-mgt-theme {
		    background-image: url('img/hotel-booking.jpg');
		    background-position: center center;
		    background-repeat: no-repeat;
		    background-size: cover;
		}

		.pos-theme {
		    background-image: url('img/pos.jpg');
		    background-position: center center;
		    background-repeat: no-repeat;
		    background-size: cover;
		}

		.travels-theme {
		    background-image: url('img/travels.jpg');
		    background-position: center center;
		    background-repeat: no-repeat;
		    background-size: cover;
		}

	</style>

</head>
<body class="fx-position-rel white-theme motion noscroll">

	<?php include "authen_user.php"; ?>
	
	<div id="main" class="fx-sided-box">
		<ul>
			<?php if($_SESSION['app_service'] == 'hotel-management'): ?>
			<li class="fx-width-50 nc-height-100 hotel-mgt-theme noscroll">
			<?php elseif($_SESSION['app_service'] == 'point-of-sale'): ?>
			<li class="fx-width-50 nc-height-100 pos-theme noscroll">
			<?php elseif($_SESSION['app_service'] == 'travels-and-tours'): ?>
			<li class="fx-width-50 nc-height-100 travels-theme noscroll">
			<?php else: ?>
			<li class="fx-width-50 nc-height-100 startup-theme noscroll">
			<?php endif; ?>
				<div class="block-element nc-height-100 txp5-black pads30">
					<ul>
						<li class="fx-width-50">
							<a href="../" class="noselect"><img src="../assets/theme/fav-icon.png"></a>
						</li>
						<li class="fx-width-50 top-pull-10 alignrt">
							<?php $app = str_replace('-',' ',strtoupper($_GET['app_service'])); ?>
							<h2 class="xlarge nobold ft-tahoma white-font"><?php echo $app; ?></h2>
						</li>
						<li></li>
					</ul>
				</div>
			</li>
			<li class="fx-width-50 nc-height-100 pattern pads30 y-scroll">
				<div class="fx-width-85 margin-auto-ct top-pull-30">
					<div class="fx-width-90 nc-height-80 margin-auto-ct white-theme mini-rounded-button pads30 xhover-shadow-faded">
						<h1 class="large nobold dmsanbold-font letter-spacing-1 alignct">HMS USER</h1>
						<p>&nbsp;</p>

						<form action="" method="post" onsubmit="" autocomplete="off" id="lform">
							<div class="xform bottom-push-15">
								<p class="pads7">
									<input x-autocompletetype="given-name" type="text" name="fieldset1" id="fieldset1" placeholder="Username" class="nopads no-back-black">
								</p>
							</div>
							<div class="xform bottom-push-15">
								<p class="pads7">
									<input autocomplete="new-password" type="password" name="fieldset2" id="fieldset2" placeholder="Password" class="nopads no-back-black">
								</p>
							</div>

							<div id="messenger" class="<?php echo $noshow; ?>"><?php echo $pst_message; ?></div>

							<p id="form-submit-1" class="form-block-label alignct top-push-30">
								<?php if($_SESSION['app_service'] == 'travels-and-tours'): ?>
									<input type="button" name="log" value="Login" class="submit-alt black-white-state sml-rounded-button motion">
								<?php else: ?>
									<input type="submit" name="log" value="Login" class="submit-alt blue-white-state sml-rounded-button motion">
								<?php endif; ?>
							</p>
							<p class="top-pull-10 right-pull-30 left-pull-30 dmsan-font alignct text-line-spacing-1 dark-blue-font">
								For your login credentials, please contact HMS administrator
							</p>
						</form>
					</div>

					<p class="cs-width-180 margin-auto-ct top-pull-30 alignlt"><b class="block-element dmsan-font dark-grey-font ft-size left-pull-2 bottom-push-3 nobold">Powered By</b><b class="dmsanbold-font steel-blue-font ft-ssize nobold">DigitSpot Solutions Ltd.</b></p>
				</div>
			</li>
			<li></li>
		</ul>
	</div>

	<?php if(isset($message) && !empty($message)): ?>
				
	<script> window.onload = () => { window.location.href = "<?php echo $message; ?>"; } </script>
	
	<?php endif; ?>

</body>
</html>