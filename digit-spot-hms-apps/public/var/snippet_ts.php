<span class="ln-display-box float-left bottom-pull-10 left-push-20 right-pull-20 left-pull-20 anchor <?php if(isset($mdlses) && $mdlses == 'super-admin') { echo $_flyclass_1_; } else { echo $_flyclass_2_; } ?>" onclick="window.location.href='ini.php?logs=<?php echo $_GET['logs']; ?>&mdlses=super-admin'">
	Admin Logins
</span>
<span class="block-element new-line-space">
	<!-- clear line -->
</span>

<span class="block-element white-theme sml-rounded-button top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 top-push-3 obj-light-shadow">
	<?php
		if(isset($_GET['mdlses'])) { $privilege = str_replace('-', ' ', $_GET['mdlses']); }
		else { $privilege = null; }

		switch($privilege)
		{
			case "super admin":
			include "super_admin.php";
			break;

			default:
			include "mdl.html";
			break;
		}
		
	?>
</span>