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
</head>
<body class="fx-position-rel nc-height-100 white-theme pattern motion noscroll">

	<div class="fx-position-flow fscr zind-4 txp6-black top-pull-50 right-pull-15 bottom-pull-10 left-pull-15 y-scroll">

		<?php include "authen_user.php"; ?>
		
		<div class="fx-width-35 nc-height-80 margin-auto-ct white-theme mini-rounded-button pads30 xhover-shadow-faded">
			<h1 class="large nobold dmsanbold-font letter-spacing-1 alignct">HMS APP-SERVICE</h1>
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
				<p class="top-pull-30 right-pull-30 left-pull-30 dmsan-font alignct text-line-spacing-1 dark-blue-font alignct">
					DigitSpot Solutions Ltd.
				</p>
			</form>
		</div>
	
	</div>

	<?php if(isset($message) && !empty($message)): ?>
				
	<script> window.onload = () => { window.location.href = "<?php echo $message; ?>"; } </script>
	
	<?php endif; ?>

</body>
</html>