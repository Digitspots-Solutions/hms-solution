<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$query_stores = "SELECT * FROM {$tbL123} WHERE status IN('Active') AND deletedata=0";
	$for_stores = html_db_select($query_stores,'id','store_name');

	$fstore = ""; $tstore = ""; $fstore_name = ""; $itemstack = "";
	$err = 0; $err_message = "";

	if(isset($_GET['liststoreitems'])) {
		$fstore = remove_data_injection($_GET['liststoreitems']);
		$fstore_name = idget_name($fstore,'store_name',$tbL123);
	}


	if(isset($_POST['movebutton']) && isset($_POST['checkers'])) {

		$fstore = remove_data_injection($_POST['fstore']);
		$tstore = remove_data_injection($_POST['tstore']);
		$tstore_name = idget_name($tstore,'store_name',$tbL123);

		if($fstore == $tstore) {
			$err = 1;
			$err_message = "Error: You have selected the same store name. Try again";
		} else {
			$err = 0;
			$err_message = "";
		}

		if($err == 0) {
			
			$checkers = $_POST['checkers'];
			
			foreach($checkers as $rowid) {
				$pst_query = "id={$rowid}";
				$pst_field = "storageid={$tstore}";
				mysqli_data_update($mtbL19,$pst_field,$pst_query);
			}

			$err_message = "Item(s) store location changed successfully";
		}
	}
?>

<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: Here you can change item store location from one place to another
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="sided-box top-push-20 top-pull-20 right-pull-50 left-pull-50">
	
	<?php if(!empty($err_message)): ?>
		<h3 class="large nobold alignct light-red-font"><?php echo $err_message; ?></h3><br>
	<?php endif; ?>

	<form action="" method="post" autocomplete="off" class="nomargin nopads">
		<ul>
			<li class="nc-width-40 box-border-thick sml-rounded-button">
				<div class="pads20 box-border-thick-bottom">
					<h3 class="large nobold default-text-font-bold">Move From</h3><br>
					<select name="fstore" id="fstore" class="nopads no-back-black" onchange="loaditems(this.value)" required>
						<?php
							if(!empty($fstore) && is_numeric($fstore)) { ?><option value="<?php echo $fstore; ?>" selected><?php echo $fstore_name; ?></option><?php } else { ?><option value="" selected>Choose Store?</option><?php } ?>
						<?php echo $for_stores; ?>
					</select>
				</div>
				<div class="pads20 cs-height-400 y-scroll">
					<h4 class="xlarge nobold">Note: Check the box for the items you want to move, select the destination store and click on move-arrow button</h4><br>
					<table cellpadding="3" cellspacing="0">
						
						<?php
							if(!empty($fstore) && is_numeric($fstore)) {
								$sql = "SELECT * FROM {$mtbL19} WHERE storageid={$fstore} AND deletedata=0";
								$get_item = idget_data($sql);

								if(is_array($get_item) && count($get_item)) {
									
									$item_name = ""; $item_uom = ""; $num = 0;

									?>
										<tr class="grey-theme">
											<td class="cs-width-30">&nbsp;</td>
											<td class="cs-width-30 alignct"><input type="checkbox" name="checker" lang="off" onclick="chkrAll(this)"></td>
											<td class="light-red-font">Check for all</td>
										</tr>
									<?php

									foreach($get_item as $key => $val) {
										
										$item_name = idget_name($val['itemid'],'item',$mtbL5);
										$item_uom = arrayget_key($uoms,$val['uom']);

										$num += 1;

										?>
											<tr>
												<td class="cs-width-30 grey-theme"><h4 class="large nobold nomargin alignct"><?php echo $num; ?>.</h4></td>
												<td class="cs-width-30 box-border-thick-right alignct"><input type="checkbox" name="checkers[]" value="<?php echo $val['id']; ?>" class="item"></td>
												<td class="left-pull-10"><h4 class="large nobold nomargin"><?php echo $item_name.' - '.$val['balance'].' '.$item_uom; ?></h4></td>
											</tr>
										<?php
									}
								}
							}

						?>

					</table>
				</div>
			</li>
			<li class="nc-width-20">
				<br><br><br><br>
				<br><br><br><br>
				<input type="submit" name="movebutton" value="&rsaquo;&rsaquo;" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 ft-Msize" title="Apply Move">
			</li>
			<li class="nc-width-40 box-border-thick sml-rounded-button">
				<div class="pads20 box-border-thick-bottom">
					<h3 class="large nobold default-text-font-bold">Move To</h3><br>
					<select name="tstore" id="tstore" class="nopads no-back-black" required>
						<?php
							if(!empty($tstore) && is_numeric($tstore)) { ?><option value="<?php echo $tstore; ?>" selected><?php echo $tstore_name; ?></option><?php } else { ?><option value="" selected>Choose Store?</option><?php } ?>
						<?php echo $for_stores; ?>
					</select>
				</div>
				<div class="pads20 cs-height-400 y-scroll">
					<table cellpadding="3" cellspacing="0">
						
						<?php
							if(!empty($tstore) && is_numeric($tstore)) {
								$sql = "SELECT * FROM {$mtbL19} WHERE storageid={$tstore} AND deletedata=0";
								$get_item = idget_data($sql);

								if(is_array($get_item) && count($get_item)) {
									
									$item_name = ""; $item_uom = ""; $num = 0;

									?>
										<tr class="grey-theme">
											<td class="cs-width-30">&nbsp;</td>
											<td class="light-red-font">&nbsp;</td>
										</tr>
									<?php

									foreach($get_item as $key => $val) {
										
										$item_name = idget_name($val['itemid'],'item',$mtbL5);
										$item_uom = arrayget_key($uoms,$val['uom']);

										$num += 1;

										?>
											<tr>
												<td class="cs-width-30 grey-theme"><h4 class="large nobold nomargin alignct"><?php echo $num; ?>.</h4></td>
												<td class="left-pull-10"><h4 class="large nobold nomargin"><?php echo $item_name.' - '.$val['balance'].' '.$item_uom; ?></h4></td>
											</tr>
										<?php
									}
								}
							}

						?>

					</table>
				</div>
			</li>
			<li>
			</li>
		</ul>
	</form>

</div>


<script>

	function loaditems(val) {
		window.location.href = window.location.href+'&liststoreitems='+val;
	}

	function chkrAll(obj) {

		var items = document.getElementsByClassName('item');

		if(obj.lang == 'off') {
			obj.lang = 'on';
			for(var i=0; i<items.length; i++) {
				items[i].setAttribute('checked','checked');
			}
		} else if(obj.lang == 'on') {
			obj.lang = 'off';
			for(var i=0; i<items.length; i++) {
				items[i].removeAttribute('checked');
			}
		}
	}

</script>