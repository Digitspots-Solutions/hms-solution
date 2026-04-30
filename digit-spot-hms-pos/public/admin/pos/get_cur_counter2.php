<?php

//get waiter list
$global_addon_query = array("status"=>"Active");

$dp_key = array("mdl"=>8);
$dp_data = mysqli_data_fetch($tbL4,'id',$dp_key,'array');
if(is_array($dp_data)) {
	$housekeepers = '';
	foreach ($dp_data as $dpt_key => $dpt_value) {
		$ext_tbls = "0,".$tbL4;
		//$waiters .= mt_select_fetch('role',$dpt_value['id'],$tbL7,'id','staffname,department,role',$ext_tbls,'0,department,role');
		$waiters .= mt_select_fetch('role',$dpt_value['id'],$tbL7,'id','staffname,role',$ext_tbls,'0,role');
	}
} else {
	$waiters = '<option value="">No user</option>';
}


#--------------------------------------------------------------------------------------------------

//get consumption tax for the active pos counter

$additionalQuery = " ORDER BY id DESC LIMIT 1";
$ctax_key = array("deletedata"=>0,"status"=>"Active","postoreid"=>$cur_pos_store_id);
$ctax = mysqli_data_fetch($tbL18,'taxcharge',$ctax_key,'noarray');

if(isset($ctax[0]) && $ctax[0] > 0) {
	$cur_pos_ctax = $ctax[0];
} else {
	$cur_pos_ctax = 0;
}

$additionalQuery = "";

$all_pending_orders = select_dt_fetch('status','Pending',$tbL99,'order_number','order_number');

?>

<div class="ln-display-box float-left nc-width-75">
	<div class="block-element box-border-thick cs-height-250 noscroll sml-rounded-button">
		<div class="block-element nc-height-15 grey-theme top-pull-10 left-pull-20"><small id="pcl-header">Featured Products</small></div>
		<div id="pos-show-products" class="block-element nc-height-85 y-scroll top-pull-15 left-pull-15">
			<?php

				$posproductkey = array("deletedata"=>0,"isfeature"=>"Yes","postoreid"=>$cur_pos_store_id);
				$dataproperty = "id,categoryid,subcategoryid,itemcode,item,stockin,uom,price,isstaff,storagetype,balance";
				$posproducts = mysqli_data_fetch($tbL16,$dataproperty,$posproductkey,'array');

				if(is_array($posproducts)) {
					
					$select_uom = ''; $pushItem = '';

					foreach($posproducts as $pskey => $psvalue) {
						
						$select_uom = !empty($psvalue['uom']) ? get_uom($psvalue['uom']) : "one";
						$pushItem = $psvalue['id'].'=='.$psvalue['item'].'=='.$psvalue['itemcode'].'=='.$psvalue['price'].'=='.$psvalue['isstaff'].'=='.$psvalue['storagetype'].'=='.$psvalue['balance'];
					
						?>
							<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-150 cs-height-120 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; <?php echo number_format($psvalue['price'],2); ?>" onclick="pushItem('billtype','billacct','<?php echo $pushItem; ?>')">
								<h4 class="large nobold default-text-font-bold"><?php echo $psvalue['item']; ?></h4>
								<small class="block-element top-push-3">&#8358; <?php echo number_format($psvalue['price'],2); ?></small>
								<small class="block-element top-push-3 ft-xxsml-size light-grey-font">per <?php echo $select_uom; ?></small>
							</span>
						<?php
					}

					?>
						<span class="block-element new-line-space">
						</span>
					<?php
				} else {
					?>
						<small class="block-element top-push-50 dark-grey-font alignct">No products list</small>
					<?php
				}

				#---------------------------------------------------------------------------------------------------------------------------------------------

				#get all product categories
				$ctgkey = array("deletedata"=>0,"postoreid"=>$cur_pos_store_id);
				$ctgs = mysqli_data_fetch($tbL16,'categoryid',$ctgkey,'array');
				
				$product_categories = array();
				
				if(is_array($ctgs)) {
					foreach ($ctgs as $ctgkey => $ctgvalue) {
						array_push($product_categories,$ctgvalue['categoryid']);
					}
				}
			
				if(is_array($product_categories) && count($product_categories) > 0) {
					$arry_unq_category = array_unique($product_categories);
					$category_count = 1;
				} else {
					$arry_unq_category = null;
					$category_count = 0;
				}
				
			?>
		</div>
	</div>

	<!--
		<a href="javascript:void(0)" class="blue-font ft-xxsml-size float-right left-push-20" onclick="chgclass('order-form','block-element top-push-20 box-border-thick pads15 sml-rounded-button'); chgclass('for-new-order','noshow'); chgclass('pending-order','ln-display-box float-left nc-width-30'); chgclass('pos-header','noshow'); formrequired('pending-orders'); formnotrequired('foodtype'); formnotrequired('billtype'); formnotrequired('tabletype'); formnotrequired('cover'); formnotrequired('waiters'); writeObjheader('waiters','<option>Active Waiter</option>')"><u>Update Order</u></a>
	-->

	<p id="for-new-order" class="top-pull-10">
		<?php if(isset($category_count) && $category_count >= 1) { ?><small>Click on <u>start new order</u> button to open order form</small> <a href="javascript:void(0)" class="blue-font ft-tahoma ft-xsml-size float-right" onclick="chgclass('order-form','block-element top-push-20 box-border-thick pads15 sml-rounded-button'); chgclass('for-new-order','noshow')">Start New Order <b class="fa-arrow-right"></b></a><?php } ?>
	</p>

	<p class="new-line-space"></p>

	<form action="pos/process_order.php" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<div id="order-form" class="noshow">
			<div class="ln-display-box float-left nc-width-20 top-pull-10 bottom-push-7">
				<a href="javascript:void(0)" class="ft-xsml-size" onclick="chgclass('order-form','noshow'); chgclass('for-new-order','block-element top-pull-10')">Hide x</a>
			</div>
			<div class="ln-display-box float-right nc-width-80 bottom-push-7">
				<span id="pending-order" class="noshow">
					<select name="pending-orders" id="pending-orders" onchange="getbiller()">
						<option value="" selected="selected">Pending Orders</option>
						<?php echo $all_pending_orders; ?>
					</select>
				</span>
				<span class="ln-display-box float-left">
					&nbsp;
				</span>
				
				<?php
					if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
						?>
							<span class="ln-display-box float-right nc-width-30 left-push-20">
								<select name="waiters" id="waiters" required="required">
									<option value="" selected="selected">Waiter</option>
									<?php echo $waiters; ?>
								</select>
							</span>
						<?php
					} else {
						?>
							<span class="noshow">
								<select name="waiters" id="waiters" required="required">
									<option value="0">No Waiter</option>
								</select>
							</span>
						<?php
					}
				?>
				
				<span class="ln-display-box float-right nc-width-30">
					<select name="cashier" id="cashier" required="required" title="Cashier in charge">
						<!--<option value="" selected="selected">Cashier</option>-->
						<option value="<?php echo $userSignedIn; ?>"><?php echo $admin_name; ?></option>
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div class="block-element new-line-space">
			</div>

			<h3 class="large alignct">&mdash; &nbsp; New Pos Order &nbsp; &mdash;</h3>
		
			<div id="pos-header" class="block-element sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<?php
							if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
								?>
									<th width="100px" align="center">Food Type</th>
								<?php
							}
						?>
						
						<th width="130px" align="center">Bill Type</th>
						<th width="150px" align="center">Bill To</th>
						<th width="150px" align="center">Guest Name</th>
						
						<?php 
							if(isset($checkIfistableset) && $checkIfistableset > 0) {
								?>
									<th width="100px" align="center">Table</th>
									<th width="100px" align="center">Cover</th>
								<?php
							}
						?>
						
					</tr>
					<tr>
						
						<?php
							if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
								?>
									<td width="100px" align="center">
										<select name="foodtype" id="foodtype">
											<option value="" selected="selected">Choose</option>
											<?php echo $list_food_type; ?>
										</select>
									</td>
								<?php
							}
						?>
						
						<td width="130px" align="center">
							<select name="billtype" id="billtype" required="required" onchange="getdata('bill-to','get-biller-account','billtype','div')">
								<option value="" selected="selected">Choose</option>
								<?php echo $list_bill_type; ?>
							</select>
						</td>
						<td width="150px" align="center">
							<div id="bill-to">xxxxx</div>
						</td>
						<td width="150px" align="center">
							<input type="text" name="guestname" id="guestname">
							<input type="hidden" name="guestid" id="guestid">
						</td>

						<?php 
							if(isset($checkIfistableset) && $checkIfistableset > 0) {
								?>
									<td width="100px" align="center">
										<select name="tabletype" id="tabletype">
											<option value="" selected="selected">Choose</option>
											<option value="0">Not Apply</option>
											<?php echo $list_tables; ?>
										</select>
									</td>
									<td width="100px" align="center">
										<input type="text" name="cover" id="cover" pattern="\d*">
									</td>
								<?php
							}
						?>
						
					</tr>
				</table>
			</div>

			<div class="block-element sml-rounded-button noscroll top-push-20 bottom-push-20">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
					<tr>
						<th width="50px" align="center" class="dark-black-theme"></th>
						<th width="200px" align="center" class="ft-xxsml-size">Item</th>
						<th width="60px" align="center" class="ft-xxsml-size">Code</th>
						<th width="100px" align="center" class="ft-xxsml-size">Price &#8358;</th>
						<th width="70px" align="center" class="ft-xxsml-size">Unit(s)</th>
						<th width="100px" align="center" class="ft-xxsml-size">Discount (%)</th>
						<th width="100px" align="center" class="ft-xxsml-size">Discount (in &#8358;)</th>
						<th width="100px" align="center" class="ft-xxsml-size">Amount &#8358;</th>
					</tr>
					<tbody id="datasheet"></tbody>
				</table>
			</div>
			
			<div class="ln-display-box float-left nc-width-40">
				<textarea name="remarks" id="remarks" placeholder="enter remarks"></textarea>
			</div>
			<div class="ln-display-box float-right nc-width-40">
				<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
					<small class="dark-grey-font right-push-20">Sub Total:</small> <small id="sub-total-label" class="add-bold">&#8358; 0.00</small>
					<input type="hidden" name="sub-total" id="sub-total" value="0">
				</span>
				<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
					<small class="dark-grey-font right-push-20">Discount:</small> <small id="discount-amount-label" class="add-bold">&#8358; 0.00</small>
					<input type="hidden" name="discount-amount" id="discount-amount" value="0">
				</span>
				<span class="noshow box-border-thick-bottom bottom-pull-10 bottom-push-7">
					<small class="dark-grey-font right-push-20">Tax:</small> <small id="consumption-tax-label" class="add-bold">&#8358; 0.00</small>
					<input type="hidden" id="for-ctax-charge" value="<?php echo $cur_pos_ctax; ?>">
					<input type="hidden" name="consumption-tax" id="consumption-tax" value="0">
				</span>
				<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
					<small class="dark-grey-font right-push-20">Grand Total:</small> <small id="grand-total-label" class="add-bold">&#8358; 0.00</small>
					<input type="hidden" name="grand-total" id="grand-total" value="0">
				</span>
			</div>
			<div class="block-element new-line-space">
			</div>
		</div>
	
		<br><br>

		<div id="ctrlbx" class="noshow alignct">
			<h4 class="large nobold black-font alignct right-pull-20 left-pull-20">Please check your order before clicking on <u>create-order</u> button. Contact your supervisor for reversal of any transaction</h4><br>
			<input type="submit" name="submitbutton" value="Create Order" class="submit pads10 dark-black-white-state rounded-button nc-width-30"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
		</div>
	</form>
</div>
<div class="ln-display-box float-right nc-width-20 box-border-thick sml-rounded-button pads10 obj-light-shadow">
	<h4 class="large">Quick Search</h4>
	<span class="ln-display-box float-left nc-width-80 top-push-7">
		<input type="text" name="search" id="search" placeholder="Search for item.." autocomplete="off">
	</span>
	<span class="ln-display-box float-left nc-width-20 alignct top-push-7 pads10 black-theme white-font anchor" onclick="writeObjheader('pcl-header','Searched Products'); keywords_search('pos-show-products','search-pos-products-button','search')">
		<b class="nobold fa-search"></b>
	</span>
	<span class="block-element new-line-space">
	</span>
	
	<br>

	<?php
		
		$featured_product_key = "isfeature='Yes' AND postoreid='".$cur_pos_store_id."'";
		$featured_datasets = "COUNT(item)";
		$get_featured_pdt = mysqli_arithmetic_data($tbL16,$featured_datasets,$featured_product_key);

		$staff_product_key = "isstaff='Yes' AND postoreid='".$cur_pos_store_id."'";
		$staff_datasets = "COUNT(item)";
		$get_staff_pdt = mysqli_arithmetic_data($tbL16,$staff_datasets,$staff_product_key);

	?>
	<span class="block-element">
		<ul class="nolist ft-xxsml-size">
			<li class="bottom-push-5 anchor" onclick="writeObjheader('pcl-header','Featured Products'); getdata('pos-show-products','get-pos-products-button',999,'div')">Featured Products <b class="float-right blue-font"><?php echo $get_featured_pdt; ?></b></li>
			<li class="bottom-push-5 anchor" onclick="writeObjheader('pcl-header','Available Products for Staff'); getdata('pos-show-products','get-pos-products-button',888,'div')">Staff Products <b class="float-right blue-font"><?php echo $get_staff_pdt; ?></b></li>
			<?php
				
				$select_ctg = '';

				if(is_array($arry_unq_category)) {
					foreach($arry_unq_category as $ctg) {
						if($ctg != '') {
							$select_ctg = idget_data($tbL15,$ctg,'category');
							?>
								<li class="bottom-push-5 anchor" onclick="chgclass('li-<?php echo $ctg; ?>','block-element')">
									<?php echo $select_ctg; ?> <b class="float-right nobold">+</b>
									<div id="li-<?php echo $ctg; ?>" class="noshow">
										<ul class="nolist">
											<?php
												$additionalQuery = " GROUP BY subcategoryid";
												$ctgkey_2 = array("deletedata"=>0,"categoryid"=>$ctg,"postoreid"=>$cur_pos_store_id);
												$ctgs_2 = mysqli_data_fetch($tbL16,'subcategoryid',$ctgkey_2,'array');

												if(is_array($ctgs_2)) {
													$select_sub_ctg = ''; $count_product_key = '';
													foreach($ctgs_2 as $sctgkey => $sctgvalue) {
														
														$additionalQuery = "";
														$select_sub_ctg = idget_data($tbL92,$sctgvalue['subcategoryid'],'subcategory');
														
														$count_product_key = "postoreid={$cur_pos_store_id} AND subcategoryid={$sctgvalue['subcategoryid']}"; $count_datasets = "COUNT(item)";
														$get_count_pdt = mysqli_arithmetic_data($tbL16,$count_datasets,$count_product_key);

														?>
															<li class="bottom-push-5 anchor black-font ft-xxsml-size" onclick="writeObjheader('pcl-header','All <?php echo $select_sub_ctg; ?>'); getdata('pos-show-products','get-pos-products-button',<?php echo $sctgvalue['subcategoryid']; ?>,'div')">&bull; <?php echo $select_sub_ctg; ?> <b class="float-right blue-font"><?php echo $get_count_pdt; ?></b></li>
														<?php
													}
												}
											?>
										</ul>
									</div>
								</li>
							<?php
						}
					}
				}
			?>
		</ul>
	</span>

	<br><br>
	
	<h4 class="large">Pending Orders <b class="float-right nobold fa-arrow-down"></b></h4><br>
	<?php
		//get all pending transaction and list theme here
		$additionalQuery = " GROUP BY order_number";
		$pts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Pending","isreversed"=>0);
		$get_pos_pt_data = mysqli_data_fetch($tbL99,'id,order_number,tableid',$pts_selection_key,'array');

		if(is_array($get_pos_pt_data)) {
			
			$table_name = "";
			
			foreach ($get_pos_pt_data as $pts_key => $pts_value) {
				
				//$table_name = idget_data($tbL17,$pts_value['tableid'],'tablename');

				?>
					<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" onclick="window.location.href='pos/preview_pos_order.php?new_order=<?php echo $pts_value['order_number']; ?>'" title="Click here to complete transaction">
						<span class="ln-display-box float-left right-push-7">
							<h4 class="large nobold default-text-font-bold"><?php echo $pts_value['order_number']; ?></h4>
							<small class="block-element">&#8358;0.00</small>
						</span>
						<span class="ln-display-box float-right right-push-7">
							<small class="block-element bottom-push-3"><b class="fa-share nobold"></b></small>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				<?php
			}
		}
	?>
	
	<br>

	<h4 class="large">Pending Payments <b class="float-right nobold fa-arrow-down"></b></h4><br>

	<?php
		//get all pending transaction and list theme here
		$additionalQuery = " GROUP BY order_number";
		$pyts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Completed","payment"=>"Pending","isreversed"=>0);
		$get_pos_pyt_data = mysqli_data_fetch($tbL100,'id,order_number,invoice_number,bill_amount,tableid,customerid,billtype,roomid',$pyts_selection_key,'array');

		if(is_array($get_pos_pyt_data)) {
			
			$table_name = ""; $get_bill_name = ""; $billto = "";
			
			foreach ($get_pos_pyt_data as $pyts_key => $pyts_value) {
				
				$billto = idget_data($tbL102,$pyts_value['customerid'],'booking_type');
				//$get_bill_name = idget_data($tbL102,$pyts_value['customerid'],'fname').' '.idget_data($tbL102,$pyts_value['customerid'],'lname');

				if($pyts_value['billtype'] == 1) { $get_bill_name = idget_data($tbL102,$pyts_value['customerid'],'fname').' '.idget_data($tbL102,$pyts_value['customerid'],'lname'); }
				elseif($pyts_value['billtype'] == 2) { $r_prefix = idget_data($tbL56,$pyts_value['roomid'],'roomprefix'); $r_number = idget_data($tbL56,$pyts_value['roomid'],'roomnumber'); $r_suffix = idget_data($tbL56,$pyts_value['roomid'],'roomsuffix'); $get_bill_name = $r_prefix.$r_number.$r_suffix; }
				elseif($pyts_value['billtype'] == 3) { $get_bill_name = idget_data($tbL33,$billto,'name'); }
				elseif($pyts_value['billtype'] == 4) { $get_bill_name = idget_data($tbL58,$billto,'name'); }
				elseif($pyts_value['billtype'] == 5) { $get_bill_name = idget_data($tbL7,$billto,'staffname'); }

				?>
					<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" title="Click here to complete payment" onclick="window.location.href='pos/preview_pos_order_pay.php?new_order=<?php echo $pyts_value['order_number']; ?>&invoice=<?php echo $pyts_value['invoice_number']; ?>'">
						<span class="ln-display-box float-left right-push-7">
							<h4 class="large nobold default-text-font-bold"><?php echo $pyts_value['invoice_number']; ?></h4>
							<small class="block-element">&#8358; <?php echo number_format($pyts_value['bill_amount'],2); ?></small>
						</span>
						<span class="ln-display-box float-right right-push-7">
							<small class="block-element add-bold">Guest</small>
							<small><?php echo $get_bill_name; ?></small>
						</span>
						<span class="block-element new-line-space">
						</span>
					</div>
				<?php
			}
		}
	?>

</div>
<div class="block-element new-line-space">
</div>





<script>

	const addedCarts = [];
	const errs = {"err":0};
	const allowdiscount = "<?php echo $checkIfisdiscountallowed; ?>";
	
	function getbiller() {
	
		if(document.getElementById('pending-orders') && document.getElementById('pending-orders').value != '')
		{
			var xhr,file,strings,randomNum,myObj;
			strings = document.getElementById('pending-orders').value;

			if(window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}

			file = phpfile+"dbquery.php?jstring="+strings+"&jmsg=get-cur-biller&dataSend=200";
			randomNum = Math.random() * 1000000000;
			
			xhr.onreadystatechange=stateChanged;
			xhr.open('GET', file+"&rand=" + randomNum, true);
			
			function stateChanged()
			{
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						myObj = JSON.parse(xhr.responseText);
						document.getElementById('billtype').innerHTML = '<option value="'+myObj.biller+'">biller</option>';
						document.getElementById('bill-to').innerHTML = '<input type="hidden" name="billacct" id="billacct" value="'+myObj.account+'">';
					}
				}
			}

			xhr.send();
		}
	}


	function pushItem(billtyp,billacct,itemstrings) {
		
		var biller,biller,acct,pushs,err;

		if(document.getElementById(billtyp)) { biller = document.getElementById(billtyp).value; }
		else { biller = ''; }

		if(document.getElementById(billacct)) { acct = document.getElementById(billacct).value; }
		else { acct = ''; }

		pushs = itemstrings.split('==');
		err = 0;

		if(biller == '' || biller == 'undefined' || biller == null) {
			err = 1;
			writeObjheader('pos-header-notification','Error Notification!');
			writeObjheader('pos-message-notification','Please select bill-type and try again');
			objDisplay('notifybox');
			autohidePopupBox('notifybox',3000);
		} else if(acct == '' || acct == 'undefined' || acct == null) {
			err = 1;
			writeObjheader('pos-header-notification','Error Notification!');
			writeObjheader('pos-message-notification','Please select bill-account and try again');
			objDisplay('notifybox');
			autohidePopupBox('notifybox',3000);
		} else {
			
			if(biller == 5 && pushs[4] == 'No') {
				err = 1;
				writeObjheader('pos-header-notification','Error Notification!');
				writeObjheader('pos-message-notification','Selected biller is not eligible to this item');
				objDisplay('notifybox');
				autohidePopupBox('notifybox',5000);
			} else if(biller == 4) {
				err = 1;
				json_data(acct,'get-corporate-pos-package');
				var stopwhile = setInterval(function () { 
					if(localStorage.getItem('json') !== null && localStorage.getItem('json') != 'undefined') {
						
						var result = localStorage.getItem('json');
						
						if(result == 0) {
							errs.err = 1;
							err = 1;
							writeObjheader('pos-header-notification','Error Notification!');
							writeObjheader('pos-message-notification','Selected biller is not eligible to this pos package');
							objDisplay('notifybox');
							autohidePopupBox('notifybox',5000);
						} else {
							errs.err = 0;
							err = 0;
						}

						//localStorage.removeItem('json');
						clearInterval(stopwhile);
					}
				}, 500);
			} else if(biller == 2) {
				err = 1;
				acct = document.getElementById('guestid').value;
				json_data(acct,'get-room-access-service');
				var stopwhile = setInterval(function () { 
					if(localStorage.getItem('json') !== null && localStorage.getItem('json') != 'undefined') {
						
						var result = localStorage.getItem('json');
						
						if(result == 0) {
							errs.err = 1;
							err = 1;
							writeObjheader('pos-header-notification','Error Notification!');
							writeObjheader('pos-message-notification','Selected biller is not eligible to this pos package');
							objDisplay('notifybox');
							autohidePopupBox('notifybox',5000);
						} else {
							errs.err = 0;
							err = 0;
						}

						//localStorage.removeItem('json');
						clearInterval(stopwhile);
					}
				}, 500);
			} else {
				err = 0;
			}

		
			//var cart =
			setTimeout(() => {
				if(err == 0 || errs.err == 0) {
					
					var index = addedCarts.indexOf(pushs[0]);
				
					if(index > -1) {
						writeObjheader('pos-header-notification','Cart Notification!');
						writeObjheader('pos-message-notification','Duplicate item not allowed! Use quantity field');
						objDisplay('notifybox');
						autohidePopupBox('notifybox',2000);
					} else {
						addedCarts.push(pushs[0]);
						
						var uni_id = Math.random() * 10000;

						var subtotal = document.getElementById('sub-total');
						var consumptiontax = document.getElementById('consumption-tax');
						var grandtotal = document.getElementById('grand-total');
						var ctax = document.getElementById('for-ctax-charge');
						var discountamt = document.getElementById('discount-amount');

						contr = document.getElementById('datasheet');
						var tr = document.createElement('tr');
						tr.id = 'tr'+uni_id;

						var td1 = document.createElement('td');
						var td2 = document.createElement('td');
						var td3 = document.createElement('td');
						var td4 = document.createElement('td');
						var td5 = document.createElement('td');
						var td6 = document.createElement('td');
						var td7 = document.createElement('td');
						var td8 = document.createElement('td');

						var txt1 = document.createElement('input');
						var txt2 = document.createElement('input');
						var txt3 = document.createElement('input');
						var txt4 = document.createElement('input');
						var txt5 = document.createElement('input');
						var txt6 = document.createElement('input');

						var div1 = document.createElement('div');
						var div2 = document.createElement('div');
						var div3 = document.createElement('div');
						var div4 = document.createElement('div');

						var amount;
						amount = eval(pushs[3]) * 1;

						var trashicon = document.createElement('b');
						trashicon.id = 'b'+uni_id;
						trashicon.className = 'fa-trash nobold';
						trashicon.title = 'Remove item';
						trashicon.onclick = function() { 
							contr.removeChild(tr);
							
							subtotal.value = eval(subtotal.value) - eval(amount);
							consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
							grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);
							
							var sbt_label = numberFormat(subtotal.value);
							var ctax_label = numberFormat(consumptiontax.value);
							var gt_label = numberFormat(grandtotal.value);

							document.getElementById('sub-total-label').innerHTML = "&#8358; "+sbt_label;
							document.getElementById('consumption-tax-label').innerHTML = "&#8358; "+ctax_label;
							document.getElementById('grand-total-label').innerHTML = "&#8358; "+gt_label;
						}

						td1.align='center';
						td1.width='50px';
						td1.appendChild(trashicon);

						txt1.type = 'hidden';
						txt1.name = 'itemid[]';
						txt1.value = pushs[0];

						div1.className = 'ft-xsml-size';
						div1.innerHTML = pushs[1];

						td2.align='center';
						td2.width='200px';
						td2.appendChild(txt1);
						td2.appendChild(div1);

						div2.className = 'ft-xsml-size';
						div2.innerHTML = pushs[2];

						td3.align='center';
						td3.width='80px';
						td3.appendChild(div2);

						div3.className = 'ft-xsml-size';
						div3.innerHTML = numberFormat(pushs[3]);

						txt2.id = 'price'+uni_id;
						txt2.type = 'hidden';
						txt2.name = 'price[]';
						txt2.value = pushs[3];

						td4.align='center';
						td4.width='100px';
						td4.appendChild(div3);
						td4.appendChild(txt2);

						txt3.id = 'qty'+uni_id;
						txt3.type = 'text';
						txt3.name = 'qty[]';
						txt3.value = 1;
						txt3.required = 'required';
						txt3.onkeyup = () => {
							
							txt5.value = 0; txt6.value = 0;
							document.getElementById('discount-amount-label').innerHTML = "&#8358; 0.00";
							grandtotal.value = Number(grandtotal.value) - Number(discountamt.value);
							discountamt.value = 0;

							document.getElementById('grand-total-label').innerHTML = "&#8358; "+numberFormat(grandtotal.value);
							

							var err_qty;

							if(pushs[5] == 'consumable' && Number(txt3.value) > Number(pushs[6])) {
								err_qty = 1;
							} else {
								err_qty = 0;
							}

							if(err_qty == 1) {
								alert('-- Consumable Stock Item --\nPlease use lower value');
								txt3.value = 0;
								amount = 0;

								subtotal.value = (eval(subtotal.value) - eval(txt4.value)) + eval(amount);
								consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
								grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);

								var sbt_label = numberFormat(subtotal.value);
								var ctax_label = numberFormat(consumptiontax.value);
								var gt_label = numberFormat(grandtotal.value);

								document.getElementById('sub-total-label').innerHTML = "&#8358; "+sbt_label;
								document.getElementById('consumption-tax-label').innerHTML = "&#8358; "+ctax_label;
								document.getElementById('grand-total-label').innerHTML = "&#8358; "+gt_label;

								txt4.value = amount;
								div4.innerHTML = numberFormat(amount);
							} else {
								if(txt3.value !== null && txt3.value > 0) {
									amount = eval(txt3.value) * eval(txt2.value);
						
									subtotal.value = (eval(subtotal.value) - eval(txt4.value)) + eval(amount);
									consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
									grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);

									var sbt_label = numberFormat(subtotal.value);
									var ctax_label = numberFormat(consumptiontax.value);
									var gt_label = numberFormat(grandtotal.value);

									document.getElementById('sub-total-label').innerHTML = "&#8358; "+sbt_label;
									document.getElementById('consumption-tax-label').innerHTML = "&#8358; "+ctax_label;
									document.getElementById('grand-total-label').innerHTML = "&#8358; "+gt_label;

									txt4.value = amount;
									div4.innerHTML = numberFormat(amount);
								}
							}
						}

						td5.align='center';
						td5.appendChild(txt3);

						txt4.id = 'amt'+uni_id;
						txt4.type = 'hidden';
						txt4.name = 'amount[]';
						txt4.value = amount;
						txt4.required = 'required';

						div4.className = 'ft-xsml-size';
						div4.innerHTML = numberFormat(amount);

						var maxs;

						if(document.getElementById('billtype').value == 5) { maxs = "<?php echo $staff_discount; ?>"; }
						else { maxs = "<?php echo $guest_discount; ?>"; }

						txt5.id = 'perc'+uni_id;
						txt5.type = 'number';
						txt5.name = 'percentage[]';
						txt5.min = 0;
						txt5.max = maxs;
						txt5.step = ".01";
						
						if(allowdiscount == 'No') {
							txt5.value = 0;
							txt5.setAttribute('readonly','readonly');
						} else if(allowdiscount == 'Yes') {
							txt5.placeholder = maxs;
						}

						txt5.onblur = () => {
							var cal_da;
							
							if(Number(txt5.value) <= Number(maxs)) {
								cal_da = (Number(txt5.value) / 100) * txt4.value;
								cal_da =  tofixe(cal_da,2);

								if(Number(txt6.value) > 0 && txt6.value != cal_da) {
									discountamt.value = Number(discountamt.value) - Number(txt6.value);
									//grandtotal.value = Number(grandtotal.value) + Number(txt6.value);
									
									txt6.value = cal_da;

									discountamt.value = Number(discountamt.value) + Number(txt6.value);
									document.getElementById('discount-amount-label').innerHTML = "&#8358; "+numberFormat(discountamt.value);
								
									setTimeout(() => {
										grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);
										document.getElementById('grand-total-label').innerHTML = "&#8358; "+numberFormat(grandtotal.value);
									},500);
								} else {
									if(Number(cal_da) > Number(txt6.value)) {
										txt6.value = cal_da;

										discountamt.value = Number(discountamt.value) + Number(txt6.value);
										document.getElementById('discount-amount-label').innerHTML = "&#8358; "+numberFormat(discountamt.value);
									
										setTimeout(() => {
											grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);
											document.getElementById('grand-total-label').innerHTML = "&#8358; "+numberFormat(grandtotal.value);
										},500);
									}
								}
							} else {
								alert('You have exceeded maximum discount of '+maxs+'%');
								txt5.value = 0;
							}
						}

						td7.align='center';
						td7.appendChild(txt5);

						txt6.id = 'disc'+uni_id;
						txt6.type = 'text';
						txt6.name = 'discount[]';
						txt6.value = 0;
						txt6.setAttribute('readonly','readonly');

						td8.align='center';
						td8.appendChild(txt6);
						
						td6.align='center';
						td6.appendChild(txt4);
						td6.appendChild(div4);

						tr.appendChild(td1);
						tr.appendChild(td2);
						tr.appendChild(td3);
						tr.appendChild(td4);
						tr.appendChild(td5);
						tr.appendChild(td7);
						tr.appendChild(td8);
						tr.appendChild(td6);

						contr.appendChild(tr);

						subtotal.value = eval(subtotal.value) + eval(amount);
						consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
						grandtotal.value = (eval(subtotal.value) + eval(consumptiontax.value)) - Number(discountamt.value);
						//grandtotal.value = Number(grandtotal.value) - Number(discountamt.value);

						var sbt_label = numberFormat(subtotal.value);
						var ctax_label = numberFormat(consumptiontax.value);
						var gt_label = numberFormat(grandtotal.value);

						document.getElementById('sub-total-label').innerHTML = "&#8358; "+sbt_label;
						document.getElementById('consumption-tax-label').innerHTML = "&#8358; "+ctax_label;
						document.getElementById('grand-total-label').innerHTML = "&#8358; "+gt_label;

						objDisplay('ctrlbx');

						writeObjheader('pos-header-notification','Cart Notification!');
						writeObjheader('pos-message-notification','Item is added to cart');
						objDisplay('notifybox');
						autohidePopupBox('notifybox',2000);
					}

					//clearInterval(cart);
				}

			},1500);
			
		}
	}


	function changeGuest(strings) {
		if(strings == 'toroom') {
			document.getElementById('guestname').value = 'Room Guest';
			document.getElementById('guestname').required = false;
			$("#guestname").prop("readonly", true);
		} else if(strings == 'complimentary') {
			document.getElementById('guestname').placeholder = 'Guest?';
			document.getElementById('guestname').required = true;
			$("#guestname").prop("readonly", false);
		} else if(strings == 'group') {
			document.getElementById('guestname').placeholder = 'Guest Account?';
			document.getElementById('guestname').required = true;
			$("#guestname").prop("readonly", false);
		} else if(strings == 'staff') {
			document.getElementById('guestname').value = 'Staff';
			document.getElementById('guestname').required = true;
			$("#guestname").prop("readonly", false);
		}
	}


	function xchangeGuest(dpx) {
		document.getElementById('guestname').value = 'Fetching..';
		document.getElementById('guestname').required = false;
		$("#guestname").prop("readonly", true);
		
		var id = document.getElementById(dpx), val = id.options[id.selectedIndex].value, txt = id.options[id.selectedIndex].text;
		var data = val.split('-'), guest = data[0], room = data[1];
		document.getElementById('guestid').value = guest;

		id.options[id.selectedIndex].value = room;
		id.options[id.selectedIndex].text = txt;

		json_getdata(fguest,'guest_tbl',guest,'id','fname');
		function fguest(data) { document.getElementById('guestname').value = data; }

		json_getdata(lguest,'guest_tbl',guest,'id','lname');
		function lguest(data) { document.getElementById('guestname').value = document.getElementById('guestname').value+' '+data; }
	}

</script>