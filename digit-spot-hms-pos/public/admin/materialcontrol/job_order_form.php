<?php
	
	$wg_supplier = idget_data($tbL114,$supplier,'supplier_name');
	$wg_store = idget_data($tbL123,$store,'store_name');
	$order_date = date($gh_get_date_format,strtotime($server_get_date));

?>

<div id="pr" class="fx-position-stick fscr zind-2 white-theme pads20 motion y-scroll" align="center">
	<div class="cs-height-50"></div>
	<div id="section-to-print" class="block-element">
		<div class="fx-width-50 bottom-push-50">
			<img src="<?php echo _FC_LOGO_Mx; ?>">
			<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
			<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
			<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
			<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>

			<div class="cs-height-30"></div>
			<h2 class="large nobold">Job Order Form</h2>
		</div>
		<span class="ln-display-box float-left nc-width-5">
			&nbsp;
		</span>
		<span class="ln-display-box float-left nc-width-30 alignlt">
			<h3 class="large nobold default-text-font-bold">Supplier Details</h3>
			<h3 class="large nobold"><?php echo $wg_supplier; ?></h3>
		</span>
		<span class="ln-display-box float-left nc-width-30 alignlt">
			<h3 class="large nobold default-text-font-bold">Store Details</h3>
			<h3 class="large nobold"><?php echo $wg_store; ?></h3>
		</span>
		<span class="ln-display-box float-left nc-width-30 alignlt">
			<h3 class="large nobold">Date: <?php echo $order_date; ?></h3>
			<h3 class="large nobold">Order No#: <?php echo $order_no; ?></h3>
			<h3 class="large nobold">Status: <b class="light-red-font">Pending</b></h3>
			<h3 class="large nobold">Created By: <?php echo $admin_name; ?></h3>
		</span>
		<span class="ln-display-box float-left nc-width-5">
			&nbsp;
		</span>
	</div>

	<div class="cs-height-100"></div>
	
	<input type="button" value="Print" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button anchor" onclick="window.print()"> <input type="button" value="Job Order Form" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state rounded-button anchor left-push-10" onclick=""> <input type="button" value="Close x" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 black-white-state rounded-button anchor left-push-10" onclick="chgclass('pr','noshow motion')">

</div>