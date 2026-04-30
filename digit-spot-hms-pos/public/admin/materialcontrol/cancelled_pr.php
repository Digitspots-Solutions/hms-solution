<?php
	
	//include "../../../includes/uom.php";
	
	#create table for batch
	createDatabasetable($var_tbl_116);
	
	$tbl = $mtbL8;

	//check if data exist in the table
	$query_po = "order_status='Pending' AND deletedata=0";
	$po = mysqli_data_exist($tbl,$query_po);

?>

<div class="cs-height-30"></div>

<div class="pads30">
	<form action="" method="post" autocomplete="off" onsubmit="" id="datasheet">
		<?php if(isset($allowMcDelete) && $allowMcDelete == 200) { ?><span class="float-right left-push-20"><input type="submit" id="deletebutton" name="deletebutton" value="Cancel PR" class="cs-width-150 blue-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2"></span><?php } ?>

		<input type="hidden" name="ftask" id="ftask" value="noidelete">
		<input type="hidden" name="xtbl" id="xtbl" value="<?php echo $tbl; ?>">
		<input type="hidden" name="xcol" id="xcol" value="order_number">

		<div class="alignlt"><h3 class="large nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>Highlight the purchase request you want remove by checking the box</h3></div>

		<div class="cs-height-30"></div>
	
		<?php

			if($po['isdata'] == true) {

				$isSql = "SELECT * FROM {$tbl} WHERE order_status='Pending' AND deletedata=0 GROUP BY order_number";
				$wgt_po = idget_data($isSql);

				if(is_array($wgt_po) && count($wgt_po)) {
					foreach($wgt_po as $key => $val) {
						
						$query_sum = "SELECT SUM(order_net_amount) AS 'totalprcost' FROM {$tbl} WHERE order_number='{$val['order_number']}' AND deletedata=0"; $wgt_sum = idget_data($query_sum);

						?>
							<div class="ln-display-box float-left cs-width-250 box-border-thick xsml-rounded-button pads20 right-push-20 bottom-push-20 alignlt">
								<h2 class="large nobold nunito-semibold">Purchase Request Card</h2>
								<span class="float-right left-push-20"><input type="checkbox" name="checkers[]" value="<?php echo $val['order_number']; ?>"></span>
								<b class="royal-blue-font"><?php echo $val['order_number']; ?> - <?php echo date($nth_dfn,strtotime($val['order_date'])); ?></b><br>
								Status: <b class="light-red-font">Pending</b>
								<div class="box-border-thick-top top-pull-7 left-pull-5 top-push-20">
									<h2 class="large nobold nunito-bold nomargin">&#8358; <?php echo number_format($wgt_sum[0]['totalprcost'],2); ?></h2>
								</div>
							</div>
						<?php
					}

					?>
						<div class="block-element new-line-space">
						</div>
					<?php
				}

			} else {
				
				?>
					<div class="cs-height-100"></div>
					<div class="block-element" align="center">
						<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
							<span class="block-element nc-height-35"></span>
							<b class="mbri-pages ft-Lsize nobold"></b>
						</div>
						<h3 class="xlarge nobold dark-grey-font">No records found</h3>
					</div>
				<?php
			}
		?>

	</form>
</div>