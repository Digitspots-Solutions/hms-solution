<?php

	$cs_param = "";
	mysqli_data_check($tbL7,'(*)',$cs_param);
	$total_super_user = $numOfrows;

?>

<span class="bottom-push-20 bottom-pull-10 box-3border-thick-bottom royal-blue-font left-push-20 right-pull-20 left-pull-20 ft-sml-size">
	Dashboard
</span>
<span class="block-element new-line-space">
		<!-- clear line -->
</span>
<span class="block-element white-theme sml-rounded-button top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 top-push-15 obj-light-shadow">
	<h3 class="left-push-20">&nbsp; Dashboard: <?php echo date("F Y"); ?></h3><br>
	<div class="ln-display-box float-left right-push-20">
		&nbsp;
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-umbrella fa-mini-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20">0</p>
			<h3 class="large">Modules</h3>
		</span>
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-favourite fa-mini-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20">0</p>
			<h3 class="large">Module Categories</h3>
		</span>
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-user fa-large-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20"><?php echo number_format($total_super_user); ?></p>
			<h3 class="large">Super Users</h3>
		</span>
	</div>
	<div class="block-element new-line-space bottom-push-20">
		<!-- clear line -->
	</div>
	<div class="ln-display-box float-left right-push-20">
		&nbsp;
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-share fa-mini-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20">0</p>
			<h3 class="large">Category Library</h3>
		</span>
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-thumbs-up fa-mini-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20">0.00</p>
			<h3 class="large">Total Sales</h3>
		</span>
	</div>
	<div class="grey-theme ln-display-box float-left nc-width-30 top-pull-20 right-pull-20 bottom-pull-20 left-pull-20 right-push-20 mini-rounded-button">
		<span class="ln-display-box float-left nc-width-30">
			<div class="steel-blue-theme rounded-element dashboard-cycle-xsize noscroll alignct vertical-align-50">
				<b class="nobold fa-database fa-mini-size white-font"></b>
			</div>
		</span>
		<span class="ln-display-box float-right nc-width-70 alignrt">
			<p class="bottom-push-20">0.00</p>
			<h3 class="large">Total Revenue</h3>
		</span>
	</div>
	<div class="block-element new-line-space bottom-push-50">
		<!-- clear line -->
	</div>
</span>