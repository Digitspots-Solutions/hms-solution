<?php $smdl = "pos"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-20">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create past pos transaction by following the onscreen instructions
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php

include "../../includes/pos_common_data.php";

$cur_pos_store_id = $_SESSION['postoreid'];

//get waiter list

$dp_key = array("mdl"=>8);
$dp_data = mysqli_data_fetch($tbL4,'id',$dp_key,'array');
if(is_array($dp_data)) {
	$housekeepers = '';
	foreach ($dp_data as $dpt_key => $dpt_value) {
		$ext_tbls = "0,".$tbL12.",".$tbL4;
		$waiters .= mt_select_fetch('role',$dpt_value['id'],$tbL7,'id','staffname,department,role',$ext_tbls,'0,department,role');
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

if(isset($_SESSION['order_date']) && !empty($_SESSION['order_date'])) {
	unset($_SESSION['order_date']);
}

?>

<div class="ln-display-box float-left nc-width-75">
	<div class="block-element box-border-thick cs-height-250 noscroll sml-rounded-button">
		<div class="block-element nc-height-15 grey-theme top-pull-10 left-pull-20"><small id="pcl-header">Featured Products</small></div>
		<div id="pos-show-products" class="block-element nc-height-85 y-scroll top-pull-15 left-pull-15">
			<?php

				$posproductkey = array("deletedata"=>0,"isfeature"=>"Yes","postoreid"=>$cur_pos_store_id);
				$dataproperty = "id,categoryid,subcategoryid,itemcode,item,stockin,uom,price,isstaff";
				$posproducts = mysqli_data_fetch($tbL16,$dataproperty,$posproductkey,'array');

				if(is_array($posproducts)) {
					
					$select_uom = ''; $pushItem = '';

					foreach ($posproducts as $pskey => $psvalue) {
						
						$select_uom = get_uom($psvalue['uom']);
						$pushItem = $psvalue['id'].'=='.$psvalue['item'].'=='.$psvalue['itemcode'].'=='.$psvalue['price'].'=='.$psvalue['isstaff'];
					
						?>
							<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-140 cs-height-80 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; <?php echo number_format($psvalue['price'],2); ?>" onclick="pushItem('billtype','billacct','<?php echo $pushItem; ?>')">
								<h4 class="large"><?php echo $psvalue['item']; ?></h4>
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

				$ctgkey = array("deletedata"=>0,"postoreid"=>$cur_pos_store_id);
				$ctgs = mysqli_data_fetch($tbL16,'categoryid',$ctgkey,'array');

				if(is_array($ctgs)) { $categoryids = ''; foreach ($ctgs as $ctgkey => $ctgvalue) { $categoryids .= $ctgvalue['categoryid'].','; } }
				else { $categoryids = ''; }

				$category_arry = explode(',', $categoryids);
				if(isset($category_arry[0]) && $category_arry[0] >= 1) { $arry_unq_category = array_unique($category_arry); $category_count = 1; }
				else { $arry_unq_category = null; $category_count = 0; }
				
			?>
		</div>
	</div>

	<p id="for-new-order" class="noshow top-pull-10">
		<?php if(isset($category_count) && $category_count >= 1) { ?><small>Click on <u>start new order</u> button to open order form. And push the item to add to cart</small> <a href="javascript:void(0)" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 dark-black-white-state rounded-button ft-xxsml-size float-right" onclick="chgclass('order-form','block-element top-push-20 box-border-thick pads15 sml-rounded-button'); chgclass('for-new-order','noshow')">Start New Order</a><?php } ?>
	</p>

	<p class="new-line-space"></p>

	<?php

		$pos_product_category = select_dt_fetch('postoreid',$cur_pos_store_id,$tbL15,'id','category');
		$shift_list = mt_select_fetch('status','Active',$tbL20,'id','shiftname,startimelabel,endtimelabel','','0,0,0');

	?>

	<form action="pos/process_past_order.php" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<div id="order-form" class="block-element">
			<h3 class="large alignct">&mdash; &nbsp; Pos Order (for past transaction) &nbsp; &mdash;</h3>
			<div class="ln-display-box float-left nc-width-20 top-pull-10 bottom-push-7">
				<a href="javascript:void(0)" class="ft-xxsml-size" onclick="chgclass('order-form','noshow'); chgclass('for-new-order','block-element top-pull-10')">Hide x</a>
			</div>
			<div class="ln-display-box float-right nc-width-80 bottom-push-7">
				<span class="ln-display-box float-left nc-width-25">
					<input type="text" name="orderdate" id="orderdate" placeholder="Transaction Date?" onclick="textodate('orderdate')" required="required">
				</span>
				<span class="ln-display-box float-left nc-width-25">
					<select name="shift" id="shift" required="required">
						<option value="" selected="selected">Shift</option>
						<?php echo $shift_list; ?>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-25">
					<select name="cashier" id="cashier" required="required">
						<option value="" selected="selected">Cashier</option>
						<?php echo $waiters; ?>
					</select>
				</span>
				<span class="ln-display-box float-left nc-width-25">
					<select name="waiters" id="waiters" required="required">
						<option value="" selected="selected">Waiter</option>
						<?php echo $waiters; ?>
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div class="block-element new-line-space">
			</div>

			<div class="block-element sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<th width="100px" align="center">Food Type</th>
						<th width="130px" align="center">Bill Type</th>
						<th width="150px" align="center">Bill To</th>
						<th width="150px" align="center">Guest Name</th>
						<th width="100px" align="center">Table</th>
						<th width="100px" align="center">Cover</th>
					</tr>
					<tr>
						<td width="100px" align="center">
							<select name="foodtype" id="foodtype" required="required">
								<option value="" selected="selected">Choose</option>
								<?php echo $list_food_type; ?>
							</select>
						</td>
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
						</td>
						<td width="100px" align="center">
							<select name="tabletype" id="tabletype" required="required">
								<option value="" selected="selected">Choose</option>
								<option value="0">Not Apply</option>
								<?php echo $list_tables; ?>
							</select>
						</td>
						<td width="100px" align="center">
							<input type="text" name="cover" id="cover" pattern="\d*" required="required">
						</td>
					</tr>
				</table>
			</div>

			<div class="block-element sml-rounded-button noscroll top-push-20 bottom-push-20">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<th width="50px" align="center" class="dark-black-theme"></th>
						<th width="200px" align="center">Item</th>
						<th width="80px" align="center">Code</th>
						<th width="100px" align="center">Price &#8358;</th>
						<th width="70px" align="center">Unit(s)</th>
						<th width="100px" align="center">Amount &#8358;</th>
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
					<small class="dark-grey-font right-push-20">Consumption Tax:</small> <small id="consumption-tax-label" class="add-bold">&#8358; 0.00</small>
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
			<input type="submit" name="submitbutton" value="Create Order" class="submit pads10 black-white-state rounded-button nc-width-30"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
		</div>
	</form>
</div>
<div class="ln-display-box float-right nc-width-20 box-border-thick sml-rounded-button pads10 obj-light-shadow">
	<h4 class="large">Quick Search</h4>
	<span class="ln-display-box float-left nc-width-80 top-push-7">
		<input type="text" name="search" id="search" placeholder="Search for item..">
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

				if(is_array($arry_unq_category))
				{
					foreach ($arry_unq_category as $ctg) {
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
													foreach ($ctgs_2 as $sctgkey => $sctgvalue) {
														
														$additionalQuery = "";
														$select_sub_ctg = idget_data($tbL92,$sctgvalue['subcategoryid'],'subcategory');
														
														$count_product_key = "subcategoryid='".$sctgvalue['subcategoryid']."'";
														$count_datasets = "COUNT(item)";
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
		$pts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Pending");
		$get_pos_pt_data = mysqli_data_fetch($tbL100,'id,order_number,bill_amount,tableid',$pts_selection_key,'array');

		if(is_array($get_pos_pt_data)) {
			
			$table_name = "";
			
			foreach ($get_pos_pt_data as $pts_key => $pts_value) {
				
				$table_name = idget_data($tbL17,$pts_value['tableid'],'tablename');

				?>
					<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" onclick="window.location.href='pos/preview_pos_order.php?new_order=<?php echo $pts_value['order_number']; ?>'">
						<span class="ln-display-box float-left right-push-7">
							<h4 class="large"><?php echo $pts_value['order_number']; ?></h4>
							<small class="block-element">&#8358; <?php echo number_format($pts_value['bill_amount'],2); ?></small>
						</span>
						<span class="ln-display-box float-right right-push-7">
							<small class="block-element bottom-push-3"><b class="fa-share nobold"></b> <?php echo $table_name; ?></small>
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
		$pyts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Completed","payment"=>"Pending");
		$get_pos_pyt_data = mysqli_data_fetch($tbL100,'id,order_number,invoice_number,bill_amount,tableid,customerid,billtype,roomid',$pyts_selection_key,'array');

		if(is_array($get_pos_pyt_data)) {
			
			$table_name = ""; $get_bill_name = ""; $billto = "";
			
			foreach ($get_pos_pyt_data as $pyts_key => $pyts_value) {
				
				$billto = idget_data($tbL102,$pyts_value['customerid'],'billto');

				if($pyts_value['billtype'] == 1) { $get_bill_name = idget_data($tbL102,$pyts_value['customerid'],'name'); }
				elseif($pyts_value['billtype'] == 2) { $r_prefix = idget_data($tbL56,$pyts_value['roomid'],'roomprefix'); $r_number = idget_data($tbL56,$pyts_value['roomid'],'roomnumber'); $r_suffix = idget_data($tbL56,$pyts_value['roomid'],'roomsuffix'); $get_bill_name = $r_prefix.$r_number.$r_suffix; }
				elseif($pyts_value['billtype'] == 3) { $get_bill_name = idget_data($tbL33,$billto,'name'); }
				elseif($pyts_value['billtype'] == 4) { $get_bill_name = idget_data($tbL58,$billto,'name'); }
				elseif($pyts_value['billtype'] == 5) { $get_bill_name = idget_data($tbL7,$billto,'staffname'); }

				?>
					<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" onclick="window.location.href='pos/preview_pos_order_pay.php?new_order=<?php echo $pyts_value['order_number']; ?>&invoice=<?php echo $pyts_value['invoice_number']; ?>'">
						<span class="ln-display-box float-left right-push-7">
							<h4 class="large"><?php echo $pyts_value['invoice_number']; ?></h4>
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


<div id="notifybox" class="noshow fx-position-stick zind-2 motion btscr" align="left">
	<div class="cs-width-400 white-theme pads20 bottom-push-30 left-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="pos-header-notification" class="large red-font"></h4>
		<small id="pos-message-notification" class="block-element top-push-10"></small>
	</div>
</div>


<script>

	function pushItem(billtyp,billacct,itemstrings) {
		
		var biller,biller,acct,pushs,err;

		if(document.getElementById(billtyp)) { biller = document.getElementById(billtyp).value; }
		else { biller = ''; }

		if(document.getElementById(billacct)) { acct = document.getElementById(billacct).value; }
		else { acct = ''; }

		pushs = itemstrings.split('==');
		err = 0;

		if(biller == '' || biller == 'undefined') {
			err = 1;
			writeObjheader('pos-header-notification','Error Notification!');
			writeObjheader('pos-message-notification','Please select bill-type and try again');
			objDisplay('notifybox');
			autohidePopupBox('notifybox',3000);
		} else if(acct == '' || acct == 'undefined') {
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
				json_data(acct,'get-corporate-pos-package');
				var stopwhile = setInterval(function () { 
					if(localStorage.getItem('json') !== null && localStorage.getItem('json') != 'undefined') {
						clearInterval(stopwhile);
						var result = localStorage.getItem('json');
						//console.log(result);
						if(result == 0) {
							err = 1;
							writeObjheader('pos-header-notification','Error Notification!');
							writeObjheader('pos-message-notification','Selected biller is not eligible to this pos package');
							objDisplay('notifybox');
							autohidePopupBox('notifybox',5000);
						} else {
							err = 0;
						}

						localStorage.removeItem('json');
					}
				}, 1000);
			} else {
				err = 0;
			}

			if(err == 0) {
				
				var uni_id = Math.random() * 10000;

				var subtotal = document.getElementById('sub-total');
				var consumptiontax = document.getElementById('consumption-tax');
				var grandtotal = document.getElementById('grand-total');
				var ctax = document.getElementById('for-ctax-charge');

				contr = document.getElementById('datasheet');
				var tr = document.createElement('tr');
				tr.id = 'tr'+uni_id;

				var td1 = document.createElement('td');
				var td2 = document.createElement('td');
				var td3 = document.createElement('td');
				var td4 = document.createElement('td');
				var td5 = document.createElement('td');
				var td6 = document.createElement('td');

				var txt1 = document.createElement('input');
				var txt2 = document.createElement('input');
				var txt3 = document.createElement('input');
				var txt4 = document.createElement('input');

				var div1 = document.createElement('div');
				var div2 = document.createElement('div');
				var div3 = document.createElement('div');
				var div4 = document.createElement('div');

				var trashicon = document.createElement('b');
				trashicon.id = 'b'+uni_id;
				trashicon.className = 'fa-trash nobold';
				trashicon.title = 'Remove item';
				trashicon.onclick = function() { 
					contr.removeChild(tr);
					
					subtotal.value = eval(subtotal.value) - eval(amount);
					consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
					grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);

					var sbt_label = numberFormat(subtotal.value)+'.00';
					var ctax_label = numberFormat(consumptiontax.value)+'.00';
					var gt_label = numberFormat(grandtotal.value)+'.00';

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

				td5.align='center';
				td5.appendChild(txt3);

				var amount = eval(pushs[3]) * 1;
				txt4.id = 'amt'+uni_id;
				txt4.type = 'hidden';
				txt4.name = 'amount[]';
				txt4.value = amount;
				txt4.required = 'required';
				
				div4.className = 'ft-xsml-size';
				div4.innerHTML = numberFormat(amount)+'.00';

				
				td6.align='center';
				td6.appendChild(txt4);
				td6.appendChild(div4);

				tr.appendChild(td1);
				tr.appendChild(td2);
				tr.appendChild(td3);
				tr.appendChild(td4);
				tr.appendChild(td5);
				tr.appendChild(td6);

				contr.appendChild(tr);

				subtotal.value = eval(subtotal.value) + eval(amount);
				consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
				grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);

				var sbt_label = numberFormat(subtotal.value);
				var ctax_label = numberFormat(consumptiontax.value);
				var gt_label = numberFormat(grandtotal.value);

				document.getElementById('sub-total-label').innerHTML = "&#8358; "+sbt_label;
				document.getElementById('consumption-tax-label').innerHTML = "&#8358; "+ctax_label;
				document.getElementById('grand-total-label').innerHTML = "&#8358; "+gt_label;

				objDisplay('ctrlbx');

				writeObjheader('pos-header-notification','Cart Notification!');
				writeObjheader('pos-message-notification','Item is added cart');
				objDisplay('notifybox');
				autohidePopupBox('notifybox',2000);
			}
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

</script>