<div id="tojobf" class="fx-position-flow fscr zind-3 motion noscroll" align="center">
	<div class="cs-height-100"></div>
	<div class="fx-width-90 white-theme right-push-20 left-push-20 xsml-rounded-button obj-light-shadow box-border-thick motion noscroll" align="left">
		<div class="cs-height-50 box-border-thick-bottom pads15 obj-light-shadow">
			<span class="float-right"><a href="javascript://" class="black-font" onclick="chgclass('tojobf','fx-position-rel cs-height-0 motion noscroll')"><b class="mbri-close"></b></a></span><span onclick=""><h3 class="large nobold default-text-font-bold nomargin">Purchase Order: Supplier & Price Update</h3></span>
		</div>
		<div class="cs-height-600 pads20 y-scroll">
			<?php
				if(isset($adj_order_no) && !empty($adj_order_no)) {

					$tpo = escape_data($adj_order_no);

					$queryx = array("order_number"=>$tpo,"gstat"=>"Confirm");
					$podata = "order_number,supplierid,store,datelogged,order_status,order_total_amount,order_tax_amount,order_net_amount,itemid,uom,delivery_date,delivery_note,unitprice,qty_ordered";
					$wgpo = mysqli_data_fetch($tbL121,$podata,$queryx,'array');

					$supplier = idget_fdata($tbL114,'order_number',$tpo,'supplierid');
					$store = idget_fdata($tbL114,'order_number',$tpo,'store');
					$datelogged = idget_fdata($tbL114,'order_number',$tpo,'datelogged');

					$wg_supplier = idget_data($tbL114,$supplier,'supplier_name');
					$wg_store = idget_data($tbL123,$store,'store_name');
					$order_date = write_dateF($gh_get_date_format,$datelogged);

					$wgtd = "";

					?>
						<div id="section-to-print" class="block-element" align="center">
							<div class="fx-width-50 bottom-push-50">
								<img src="<?php echo _FC_LOGO_Mx; ?>">
								<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
								<h4 class="large nobold"><?php echo $hotel_address; ?></h4>
								<small class="block-element top-push-3"><?php echo $hotel_fs_phonenumber; ?></small>
								<small class="block-element top-push-3"><?php echo $hotel_email; ?></small>

								<div class="cs-height-30"></div>
								<h2 class="large nobold">Purchase Order: Job Order Form</h2>
							</div>
							<span class="ln-display-box float-left nc-width-5">
								&nbsp;
							</span>
							<span class="ln-display-box float-left nc-width-40 alignlt">
								<h3 class="large nobold default-text-font-bold">Store Details</h3>
								<h3 class="large nobold"><?php echo $wg_store; ?></h3>
							</span>
							<span class="ln-display-box float-left nc-width-40 alignlt">
								<h3 class="large nobold">Date: <?php echo $order_date; ?></h3>
								<h3 class="large nobold">Order No#: <?php echo $order_no; ?></h3>
								<h3 class="large nobold">Status: <b id="wstat" class="forest-green-font">Confirm</b></h3>
								<h3 class="large nobold">Created By: <?php echo $admin_name; ?></h3>
							</span>
							<span class="ln-display-box float-left nc-width-5">
								&nbsp;
							</span>
							<span class="block-element new-line-space">
							</span>
						
							<div class="cs-height-100"></div>

							<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
								<div class="ln-display-box float-left nc-width-20">
									<h4 class="xlarge nobold bottom-pull-7">Supplier</h4>
									<select name="jfsupplier" id="jfsupplier">
										
									</select>
								</div>
								<div class="ln-display-box float-right nc-width-75">
									<div class="box-border-thick-bottom bottom-pull-7 bottom-push-10">
										<span class="ln-display-box float-left nc-width-10">&nbsp;</span>
										<span class="ln-display-box float-left nc-width-30"><h4 class="xlarge nobold">Item</h4></span>
										<span class="ln-display-box float-left nc-width-20"><h4 class="xlarge nobold">Price</h4></span>
										<span class="ln-display-box float-left nc-width-20"><h4 class="xlarge nobold">Qty</h4></span>
										<span class="ln-display-box float-left nc-width-20"><h4 class="xlarge nobold">Amount</h4></span>
										<span class="block-element new-line-space"></span>
									</div>
								</div>
								<div class="block-element new-line-space">
								</div>
							</form>
						</div>
					<?php

				} else {
					
					?>
						<div class="cs-height-50"></div>
						<h3 class="xlarge nobold dark-grey-font">Could not display purchase order! Please choose order number</h3>
					<?php
				}
			?>
		</div>
	</div>
</div>