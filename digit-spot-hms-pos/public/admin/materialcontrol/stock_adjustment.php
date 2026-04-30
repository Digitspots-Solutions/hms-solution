<div class="pads30">
	
	<div class="alignlt"><h3 class="xlarge nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> You can adjust your available stock based on the needs</h3></div>

	<br><br>

	<form action="" method="post" autocomplete="off" onsubmit="xframeenablescroll(); onsubmt('submitbutton','Submitting..')">

		<div class="sided-box alignlt bottom-push-20">
			<ul>
				<li class="nc-width-30 right-pull-10">
					<div class="xform grey-theme">
						<span class="right-pull-5 left-pull-3">
							<input onclick="empty_store()" type="radio" name="pushtype" value="Outlets" checked> Outlets &nbsp; <input onclick="empty_store()" type="radio" name="pushtype" value="Virtual Stores"> Virtual Stores 
							<div class="bottom-pull-3"></div>
							<label>Storage Location</label>
							<select name="store" id="store" class="nopads no-back-black" onclick="change_storage()" required>
								<option value="" selected>Choose</option>
							</select>
						</span>
					</div>
				</li>
				<li class="nc-width-10">
					&nbsp;
				</li>
				<li class="nc-width-25">
					<h3 class="large nobold default-text-font-bold">Look-up Item</h3>
					<input type="text" name="item2lookup" id="item2lookup" placeholder="Enter item to search?">
				</li>
				<li class="nc-width-10">
					&nbsp;
				</li>
				<li class="nc-width-15 alignrt">
					<input type="submit" name="searchbutton" value="Search" class="submit blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance">
				</li>
				<li></li>
			</ul>
		</div>
	</form>


	<?php
		
		$show = 0;

		if(isset($_POST['searchbutton']) && !empty($_POST['store'])) {
			$_SESSION['ccstore'] = $_POST['store'];
			$_SESSION['cctype'] = $_POST['pushtype'];
			$show = 1;
			if(isset($_POST['item2lookup']) && !empty($_POST['item2lookup'])) {
				$likeAlpha = " AND item LIKE '{$_POST['item2lookup']}%'";
				$likeAlpha2 = " AND t2.item LIKE '{$_POST['item2lookup']}%'";
			} else {
				$likeAlpha = "";
				$likeAlpha2 = "";
			}
		} else {
			if(isset($_SESSION['ccstore']) && isset($_SESSION['cctype'])) {
				$_SESSION['ccstore'] = $_SESSION['ccstore'];
				$_SESSION['cctype'] = $_SESSION['cctype'];
				$show = 1;
			}

			if(isset($_GET['alpha']) && !empty($_GET['alpha'])) {
				$likeAlpha = " AND item LIKE '{$_GET['alpha']}%'";
				$likeAlpha2 = " AND t2.item LIKE '{$_GET['alpha']}%'";
			} else {
				$likeAlpha = "";
				$likeAlpha2 = "";
			}
		}


		if($show == 1):

			if($_SESSION['cctype'] == 'Outlets'):
				$store_name = idget_name($_SESSION['ccstore'],'posname',$tbL14);
				$sql = "SELECT * FROM {$tbL16} WHERE postoreid={$_SESSION['ccstore']} AND storagetype='consumable'".$likeAlpha;
			elseif($_SESSION['cctype'] == 'Virtual Stores'):
				$store_name = idget_name($_SESSION['ccstore'],'store_name',$tbL123);
				$sql = "SELECT t1.id,t1.itemid,t1.stockin,t1.stockout,t1.balance FROM {$mtbL19} t1, {$mtbL5} t2 WHERE t1.itemid=t2.id AND t1.storageid={$_SESSION['ccstore']}".$likeAlpha2;
			endif;

			$datasets = idget_data($sql);
			
			?>

				<div class="alignct bottom-push-10">
					<u><?php echo $store_name; ?></u> : Look-up for &nbsp; 
					<?php
						$a2z = range('A','Z'); $jAlpha = "";
						foreach($a2z as $alpha) { $jAlpha .= '<a href="'.$_SERVER['PHP_SELF'].'?logs='.$_GET['logs'].'&tag='.$_GET['tag'].'&alpha='.$alpha.'" class="blue-font right-push-10">'.$alpha.'</a>'; }
						echo $jAlpha;
					?>
				</div>

				<form action="" method="post" autocomplete="off" onsubmit="">
					<input type="hidden" name="uri" value="adjust-stock">
					<input type="hidden" name="activestore" value="<?php echo $_SESSION['ccstore']; ?>">
					<input type="hidden" name="activestoretype" value="<?php echo $_SESSION['cctype']; ?>">
					<table cellpadding="3" cellspacing="1">
						<tr>
							<th class="default-text-font-bold"></th>
							<th class="default-text-font-bold">Item</th>
							<th class="default-text-font-bold">Expiring</th>
							<th class="default-text-font-bold">Qty</th>
							<th class="default-text-font-bold">Adjustment Qty</th>
							<th class="default-text-font-bold">Adjustment Type</th>
							<th class="default-text-font-bold">Action</th>
							<th class="default-text-font-bold">Remarks</th>
						</tr>
						<?php
							if(is_array($datasets)):
								foreach($datasets as $key => $val):

									$itemid = !empty($val['itemid']) ? $val['itemid'] : $val['itemcode'];
									$item_name = idget_name($itemid,'item',$mtbL5);
									$isexpire = idget_name($itemid,'isexpire',$mtbL5);
									$expire_date = idget_name($itemid,'expiry_date',$mtbL5);
									
									?>
										<tr>
											<td class="nobordercolor">
												<input type="hidden" name="ids[]" value="<?php echo $val['id']; ?>">
											</td>
											<td class="nobordercolor">
												<?php echo $item_name; ?>
												<input type="hidden" name="itemids[]" value="<?php echo $itemid; ?>">
											</td>
											<td class="nobordercolor">
												<?php echo $isexpire; ?>
											</td>
											<td class="nobordercolor">
												<?php echo $val['balance']; ?>
												<input type="hidden" name="availqty[]" value="<?php echo $val['balance']; ?>">
												<input type="hidden" name="availqtyin[]" value="<?php echo $val['stockin']; ?>">
												<input type="hidden" name="availqtyout[]" value="<?php echo $val['stockout']; ?>">
											</td>
											<td class="cs-width-120 nobordercolor">
												<input type="number" min="1" step=".01" name="adjustqty[]" value="1">
											</td>
											<td class="nobordercolor">
												<select name="adjusttype[]">
													<option value="">Choose?</option>
													<option value="Damage">Damage</option>
													<option value="Expired">Expired</option>
													<option value="Missing">Missing</option>
													<option value="Others">Others</option>
												</select>
											</td>
											<td class="nobordercolor">
												<select name="adjustcal[]">
													<option value="">Choose?</option>
													<option value="increment">INC</option>
													<option value="decrement">DEC</option>
												</select>
											</td>
											<td class="cs-width-200 nobordercolor">
												<textarea name="remarks[]"></textarea>
											</td>
										</tr>

									<?php

									$itemid = ""; $isexpire = ""; $expire_date = ""; $item_name = "";

								endforeach;
							endif;
						?>
					</table>

					<h4 class="large nobold top-pull-5 alignlt">Total: <?php echo count($datasets); ?></h4><br>

					<div class="top-push-50 alignct">
						<input type="submit" id="submitbutton" name="submitbutton" value="Apply Adjustment" class="dark-black-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance top-pull-15 right-pull-30 bottom-pull-15 left-pull-30">
					</div>
				</form>

			<?php

		endif;

		?>

</div>

<script>

	function wgetstore() {

		//var datalist = document.getElementById('store');
		
		if(document.getElementById('store').value == '' || document.getElementById('store').value == null) {
			
			writeObjheader('store','<option value="">fetching</option>');

			//sqldatastring.sql = "SELECT t1.id,t1.posname,t2.store_name FROM pos_store_tbl t1, stores_tbl t2 WHERE t1.store=t2.id";
			sqldatastring.sql = "SELECT * FROM pos_store_tbl WHERE status='Active' AND iscounter='Yes'";
			sqldataQuery(wgtstore,sqldatastring);

			function wgtstore(response) {
				var data = JSON.parse(response);
				var i, xhtml, dl = data.datastring;

				xhtml = '<option value="" selected>Choose?</option>';
				//xhtml = '<option value="0">Warehouse</option>';

				for(i=0; i<dl.length; i++) {
					xhtml += '<option value="'+dl[i].id+'">'+dl[i].posname+'</option>';
				}

				writeObjheader('store',xhtml);
			}
		}
	}

	function change_storage() {
		
		var tag = document.getElementsByName('pushtype');
		var loc = tag[0].checked == true ? tag[0].getAttribute('value') : tag[1].getAttribute('value');
		
		if(loc == 'Outlets') {

			if(document.getElementById('store').value == '' || document.getElementById('store').value == null) {
				
				writeObjheader('store','<option value="">fetching</option>');

				sqldatastring.sql = "SELECT * FROM pos_store_tbl WHERE status='Active' AND iscounter='Yes'";
				sqldataQuery(wgtstore,sqldatastring);

				function wgtstore(response) {
					var data = JSON.parse(response);
					var i, xhtml, dl = data.datastring;

					xhtml = '<option value="" selected>Choose?</option>';
					//xhtml = '<option value="0">Warehouse</option>';

					for(i=0; i<dl.length; i++) {
						xhtml += '<option value="'+dl[i].id+'">'+dl[i].posname+'</option>';
					}

					writeObjheader('store',xhtml);
				}
			}

		} else if(loc == 'Virtual Stores') {

			if(document.getElementById('store').value == '' || document.getElementById('store').value == null) {

				writeObjheader('store','<option value="">fetching</option>');

				sqldatastring.sql = "SELECT * FROM stores_tbl WHERE status='Active' AND deletedata=0";
				sqldataQuery(wgtstore,sqldatastring);

				function wgtstore(response) {
					var data = JSON.parse(response);
					var i, xhtml, dl = data.datastring;

					xhtml = '<option value="" selected>Choose?</option>';
					//xhtml = '<option value="0">Warehouse</option>';

					for(i=0; i<dl.length; i++) {
						xhtml += '<option value="'+dl[i].id+'">'+dl[i].store_name+'</option>';
					}

					writeObjheader('store',xhtml);
				}
			}
		}
	}


	function empty_store() {
		var xhtml = '<option value="" selected>Choose?</option>';
		writeObjheader('store',xhtml);
	}


	function xgetitem(tag) {

		if(tag.value !== null && tag.value != '') {
			
			var sstring = tag.value;
			var boxid = tag.id, sshow = boxid.replace('-tbox','-dlist'), dbx = boxid.replace('-tbox','');

			sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE item REGEXP '^"+sstring+"' AND status='Active' AND deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<ul class="nolist">';

				for(i=0; i<data.length; i++) {
					vhtml += '<li class="top-pull-5 bottom-pull-5 box-border-thick-bottom">';
					vhtml += '<span class="float-right"><input type="button" value="+" class="top-pull-3 right-pull-7 bottom-pull-3 left-pull-7 rounded-button anchor" name="'+data[i].item+'/?'+data[i].id+'" lang="'+dbx+'" onclick="xpk(this)"></span>';
					vhtml += '<h3 class="large nobold">'+data[i].item+'</h3>';
					vhtml += '</li>';
				}

				vhtml += '</ul>';

				writeObjheader(sshow,vhtml);
			}
		}
	}


</script>