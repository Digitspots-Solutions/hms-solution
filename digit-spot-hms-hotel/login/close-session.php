<?php include "../includes/php_paths.php"; if(isset($_GET['sesid']) && $_GET['sesid'] == 'end') { session_destroy(); } ?>
<title><?php echo _LONG_NAME; ?></title>
<h2 style="text-align: center">Starting new shift..</h2>


<script>
	window.addEventListener('load', () => {
		window.location.href = "<?php echo MAIN_DOMAIN_URL; ?>";
	}, false);
</script>