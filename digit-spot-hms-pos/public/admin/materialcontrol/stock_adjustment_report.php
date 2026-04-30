<?php
	
	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div class="pads30">
	
	<div class="alignlt"><h3 class="xlarge nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Here you see all the stock adjustments for stores and outlets</h3></div>

	<br><br>

	<form action="" method="post" autocomplete="off" onsubmit="xframeenablescroll(); onsubmt('submitbutton','Submitting..')">
		<input type="hidden" name="reporter" value="stock-adjustment-reports">
		<div class="sided-box alignlt bottom-push-20">

			<input onclick="empty_store()" type="radio" name="pushtype" value="Outlets" checked> Outlets &nbsp; <input onclick="empty_store()" type="radio" name="pushtype" value="Virtual Stores"> Virtual Stores 

			<p></p>

			<ul>
				<li class="nc-width-20 right-pull-20 right-push-10">
					<label>Storage Location</label>
					<select name="store" id="store" class="nopads no-back-black" onclick="change_storage()">
						<option value="" selected>All</option>
					</select>
				</li>
				<li class="nc-width-20 right-pull-20 right-push-10">
					<label>Adjustment Type</label>
					<select name="adjusttype" id="adjusttype" class="nopads no-back-black">
						<option value="" selected>All</option>
						<option value="Damage">Damage</option>
						<option value="Expired">Expired</option>
						<option value="Missing">Missing</option>
						<option value="Others">Others</option>
					</select>
				</li>
				<li class="nc-width-15 right-pull-10">
					<label>Start Date</label>
					<input type="date" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
				</li>
				<li class="nc-width-15 right-pull-10">
					<label>End Date</label>
					<input type="date" name="enddate" id="enddate" placeholder="End Date?" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
				</li>
				<li class="nc-width-10 alignrt">
					<input type="submit" name="searchbutton" value="Run" class="blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance top-pull-10 right-pull-20 bottom-pull-10 left-pull-20">
				</li>
				<li class="nc-width-10 alignrt">
					<input type="button" value="Print" class="dark-black-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance top-pull-10 right-pull-20 bottom-pull-10 left-pull-20" onclick="window.print()">
				</li>
				<li></li>
			</ul>
		</div>
	</form>


	<div id="section-to-print">

		<?php
						
			if(isset($_POST['reporter']) && $_POST['reporter'] == 'stock-adjustment-reports') {

				$keywords = ""; $adj = "";

				if(isset($_POST['store']) && !empty($_POST['store'])) {
					$keywords .= " AND store={$_POST['store']}";
				}

				if(isset($_POST['adjusttype']) && !empty($_POST['adjusttype'])) {
					$keywords .= " AND adjustment_type='{$_POST['adjusttype']}'";
					$adj = " AND adjustment_type='{$_POST['adjusttype']}'";
				}

				$keywords .= " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
				$dated = " AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";


				?>
					<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
						<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
					</div>
					<div class="cs-width-500 margin-auto-ct bottom-push-20 alignct">
						<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold nomargin">Stock Adjustment Report (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
					</div>

				<?php

				$sql = "SELECT store,store_type FROM {$mtbL24} WHERE deletedata=0".$keywords." GROUP BY store";
				$datagroup = idget_data($sql);

				if(is_array($datagroup)) {
								
					$store_name = "";

					foreach($datagroup as $key => $val) {
						
						if($val['store_type'] == 'Outlets') { $store_name = idget_name($val['store'],'posname',$tbL14); }
						elseif($val['store_type'] == 'Virtual Stores') { $store_name = idget_name($val['store'],'store_name',$tbL123); }

						$sql2 = "SELECT * FROM {$mtbL24} WHERE store={$val['store']} AND deletedata=0".$adj.$dated;
						$datasets = idget_data($sql2);

						?>
							<h3 class="large nobold alignlt default-text-font-bold"><?php echo $store_name; ?></h3><br>

							<table cellpadding="3" cellspacing="0" border="1">
								<tr>
									<td class="alignct default-text-font-bold">Item</td>
									<td class="alignct default-text-font-bold">Old Qty</td>
									<td class="alignct default-text-font-bold">Adj. Qty</td>
									<td class="alignct default-text-font-bold">New Qty</td>
									<td class="alignct default-text-font-bold">Adj. Type</td>
									<td class="alignct default-text-font-bold">Adj. Action</td>
									<td class="alignct default-text-font-bold">Remark</td>
									<td class="alignct default-text-font-bold">Modified By</td>
									<td class="alignct default-text-font-bold">Date Modified</td>
								</tr>

								<?php
									if(is_array($datasets)) {
										foreach($datasets as $key2 => $val2) {

											$item_name = idget_name($val2['itemid'],'item',$mtbL5);
											$initiator = idget_name($val2['userid'],'staffname',$tbL7);

											?>
												<tr>
													<td class="alignct"><?php echo $item_name; ?></td>
													<td class="alignct"><?php echo $val2['current_stock']; ?></td>
													<td class="alignct"><?php echo $val2['adjusted_stock']; ?></td>
													<td class="alignct"><?php echo $val2['new_stock']; ?></td>
													<td class="alignct"><?php echo $val2['adjustment_type']; ?></td>
													<td class="alignct"><?php echo ucwords($val2['adjustment_process']); ?></td>
													<td class="alignct"><?php echo $val2['remarks']; ?></td>
													<td class="alignct"><?php echo $initiator; ?></td>
													<td class="alignct"><?php echo date('d/m/Y',strtotime($val2['datelogged'])).' '.$val2['timelogged']; ?></td>
												</tr>
											<?php

											$item_name=""; $initiator="";
										}
									}
								?>

							</table>
							<p>&nbsp;</p>
						<?php

					}
				}
			}

		?>

	</div>

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

				xhtml = '<option value="" selected>All</option>';

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

					xhtml = '<option value="" selected>All</option>';

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

					xhtml = '<option value="" selected>All</option>';
				
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