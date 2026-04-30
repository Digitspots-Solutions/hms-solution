<?php include "../../../includes/php_paths.php"; include B3WF_PATH.ROOT_FLD._DB_SERVER_; include B3WF_PATH.ROOT_FLD._DB_TABLES_; 
include B3WF_PATH.ROOT_FLD._FUNC_; include B3WF_PATH.ROOT_FLD._RQ_FUNC_; include B3WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B3WF_PATH.ROOT_FLD._USRP_; include B3WF_PATH.ROOT_FLD._APPMODULES_;  include B3WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B3WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../../includes/uom.php";
include "../../../includes/pos_common_data.php";
include "../module_operation_privilege.php";

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

?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
		<title>Waiter Docket</title>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<link rel="touch-startup-image" href="">
		<meta name="theme-color" content="#000000">

		<link rel="stylesheet" href="../../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../../style/custom.css"/>
		<link rel="stylesheet" href="../applystyle.css"/>
		<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../js/jspath.js"></script>
		<script type="text/javascript" src="../../../js/jsbk.js"></script>
		<script src="../../ckeditor/ckeditor.js"></script>
	</head>
	<body class="fx-position-rel nc-height-100 noscroll">
		<div class="fx-position-stick tpscr zind-2 white-theme box-border-thick-bottom pads20 obj-light-shadow">
			<span class="float-right top-pull-3"><?php echo date('M. j Y'); ?></span>
			<span class="float-left top-pull-3 right-push-20"><b class="mbri-user ft-large-size"></b></span>
			<h4 class="large nobold ft-tahoma dark-grey-font">Logged-in as</h4>
			<h3 class="large nobold ft-tahoma nomargin"><?php echo $admin_name; ?></h3>
		</div>

		<div class="cs-height-70">
		</div>

		<div class="block-element nc-height-30 box-border-dark-thick-bottom pads20 noscroll">
			<div class="block-element white-theme nc-height-15 top-push-5">
				<span class="float-right nc-width-30">
					<p class="float-right nc-width-20"><a href="javascript:void(0)" onclick="writeObjheader('pcl-header','Searched Products'); keywords_search('pos-show-products','search-pos-products-button','search')"><b class="nobold fa-search"></b></a></p>
					<p class="float-left nc-width-80"><input type="text" name="search" id="search" placeholder="Search for item.." class="nopads no-back-black" autocomplete="off"></p>
					<p class="new-line-space"></p>
				</span>
				<span class="float-left nc-width-70">
					<b id="pcl-header" class="ft-tahoma">Featured Products</b>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div id="pos-show-products" class="nc-width-100 nc-height-85 top-pull-15 y-scroll">
				<?php

					$posproductkey = array("deletedata"=>0,"isfeature"=>"Yes","postoreid"=>$cur_pos_store_id);
					$dataproperty = "id,categoryid,subcategoryid,itemcode,item,stockin,uom,price,isstaff,storagetype,balance";
					$posproducts = mysqli_data_fetch($tbL16,$dataproperty,$posproductkey,'array');

					if(is_array($posproducts)) {
						
						$select_uom = ''; $pushItem = '';

						foreach($posproducts as $pskey => $psvalue) {
							
							$select_uom = !empty($psvalue['uom']) ? get_uom($psvalue['uom']) : "one";

							if($psvalue['storagetype'] == 'consumable' && $psvalue['balance'] > 0) {
								
								$pushItem = $psvalue['id'].'=='.$psvalue['item'].'=='.$psvalue['itemcode'].'=='.$psvalue['price'].'=='.$psvalue['isstaff'].'=='.$psvalue['storagetype'].'=='.$psvalue['balance'];
							
								?>
									<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-120 cs-height-80 noscroll blue-white-state pads7 alignct anchor" title="@ &#8358; <?php echo number_format($psvalue['price'],2); ?>" onclick="pushItem('billtype','billacct','<?php echo $pushItem; ?>')">
										<h4 class="large nobold default-text-font-bold"><?php echo $psvalue['item']; ?></h4>
										<small class="block-element top-push-3">&#8358; <?php echo number_format($psvalue['price'],2); ?></small>
										<small class="block-element top-push-3 ft-xxsml-size light-grey-font">per <?php echo $select_uom; ?></small>
									</span>
								<?php

							} elseif($psvalue['storagetype'] == 'directsales') {

								$pushItem = $psvalue['id'].'=='.$psvalue['item'].'=='.$psvalue['itemcode'].'=='.$psvalue['price'].'=='.$psvalue['isstaff'].'=='.$psvalue['storagetype'].'=='.$psvalue['balance'];
							
								?>
									<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-120 cs-height-80 noscroll blue-white-state pads7 alignct anchor" title="@ &#8358; <?php echo number_format($psvalue['price'],2); ?>" onclick="pushItem('billtype','billacct','<?php echo $pushItem; ?>')">
										<h4 class="large nobold default-text-font-bold"><?php echo $psvalue['item']; ?></h4>
										<small class="block-element top-push-3">&#8358; <?php echo number_format($psvalue['price'],2); ?></small>
										<small class="block-element top-push-3 ft-xxsml-size light-grey-font">per <?php echo $select_uom; ?></small>
									</span>
								<?php
							}
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
				?>
			</div>
		</div>

		<div class="block-element nc-height-40 box-border-dark-thick-bottom pads20 noscroll">
			<form id="orderform" action="" method="post" onsubmit="createOrder(event)" autocomplete="off">
				<input type="hidden" name="billacct" value="Instant Payment">
				<input type="hidden" name="cover" value="1">
				
				<?php if(isset($checkIfistableset) && $checkIfistableset > 0): ?>
						
					<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
						<tr>
							<td width="80%" class="ft-tahoma box-noborder" align="left">
								Table Arrangement
							</td>
							<td width="20%" class="ft-tahoma box-noborder" align="right">
								<select name="tabletype" id="tabletype" class="nopads no-back-black">
									<option value="" selected="selected">Choose</option>
									<?php echo $list_tables; ?>
									<option value="0">Not Apply</option>
								</select>
							</td>
						</tr>
					</table>

				<?php endif; ?>
					
				<div class="block-element nc-height-70 top-pull-10 y-scroll">
					<table cellpadding="0" cellspacing="0" class="ft-xxsml-size">
						<tr>
							<th width="50px" align="center" class="dark-black-theme"></th>
							<th width="200px" align="center" class="ft-xxsml-size">Item</th>
							<th width="100px" align="center" class="ft-xxsml-size">Price &#8358;</th>
							<th width="70px" align="center" class="ft-xxsml-size">Unit(s)</th>
							<th width="100px" align="center" class="ft-xxsml-size">Amount &#8358;</th>
						</tr>
						<tbody id="datasheet"></tbody>
					</table>

					<p>&nbsp;</p>

					<div class="ln-display-box float-left nc-width-40">
						<textarea name="remarks" id="remarks" placeholder="Enter remarks"></textarea>
					</div>
					<div class="ln-display-box float-right nc-width-40">
						<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
							<small class="dark-grey-font right-push-20">Sub Total:</small> <small id="sub-total-label" class="default-text-font-bold">&#8358; 0.00</small>
							<input type="hidden" name="sub-total" id="sub-total" value="0">
						</span>
						<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
							<small class="dark-grey-font right-push-20">Discount:</small> <small id="discount-amount-label" class="default-text-font-bold">&#8358; 0.00</small>
							<input type="hidden" name="discount-amount" id="discount-amount" value="0">
						</span>
						<span class="noshow box-border-thick-bottom bottom-pull-10 bottom-push-7">
							<small class="dark-grey-font right-push-20">Tax:</small> <small id="consumption-tax-label" class="default-text-font-bold">&#8358; 0.00</small>
							<input type="hidden" id="for-ctax-charge" value="<?php echo $cur_pos_ctax; ?>">
							<input type="hidden" name="consumption-tax" id="consumption-tax" value="0">
						</span>
						<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
							<small class="dark-grey-font right-push-20">Grand Total:</small> <small id="grand-total-label" class="default-text-font-bold">&#8358; 0.00</small>
							<input type="hidden" name="grand-total" id="grand-total" value="0">
						</span>
					</div>
					<div class="block-element new-line-space">
					</div>
				</div>
				<div class="block-element nc-height-20 white-theme top-pull-15">
					<input type="submit" name="orderbutton" id="orderbutton" value="Create Order" class="submit pads10 black-white-state rounded-button nc-width-35 ft-xxsml-size motion"> &nbsp;&nbsp; <a href="" class="steel-blue-font">Cancel</a>
				</div>
			</form>
		</div>

		<div id="queues" class="block-element nc-height-30 pads20 y-scroll">

			<h3 class="large ft-tahoma nomargin">Pending Transactions</h3>
			<p>&nbsp;</p>

			<?php
				//get all pending transaction and list theme here
				
				$query_pendings = array("posid"=>$cur_pos_store_id,"status"=>"Pending","isreversed"=>0);
				$get_pending_tr = mysqli_data_fetch($tbL100,'order_number,bill_amount,tableid',$query_pendings,'array');

				if(is_array($get_pending_tr)) {
					
					$table_name = "";
					
					foreach($get_pending_tr as $key => $val) {

						$table_name = idget_data($tbL17,$val['tableid'],'tablename');

						?>
							<span onclick="payOrder(this)" data-order="<?php echo $val['order_number']; ?>" class="float-left nc-width-25 pads7">
								<div class="cs-width-80 cs-height-80 red-white-state rounded-element ft-tahoma add-bold ft-sml-size top-pull-20 alignct" style="margin: 0 auto">
									<?php echo $table_name; ?>
								</div>
								<p class="top-pull-7 ft-tahoma add-bold alignct">
									&#8358; <?php echo number_format($val['bill_amount'],2); ?>
								</p>
								<p class="ft-tahoma blue-font alignct">
									&#128279 <?php echo $val['order_number']; ?>
								</p>
							</span>
						<?php
					}

					?>
						<span class="block-element new-line-space">
						</span>
					<?php
				}

			?>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

		</div>


		<div id="counter-notification" class="noshow motion">
		</div>

		<div id="notifybox" class="noshow fx-position-stick zind-1 motion tpscr top-push-50 top-pull-50" align="right">
			<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-dark-thick">
				<h4 id="pos-header-notification" class="large light-red-font ft-tahoma"></h4>
				<small id="pos-message-notification" class="block-element ft-tahoma add-bold top-push-10"></small>
			</div>
		</div>


		<div id="formsubmission" class="noshow fx-position-stick zind-1 fscr txp5-black pads30 motion" align="center">
			<div id="frameBox" class="fx-width-40 cs-height-200 white-theme pads20 mini-rounded-button motion">
				<iframe src="about:blank" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="100%" height="70%" name="frameOrder" id="frameOrder"></iframe>
				<p class="top-pull-20 alignct">
					<a href="" class="blue-font ft-tahoma">Close x</a>
				</p>
			</div>
		</div>


		<script>

			const addedCarts = [];
			const errs = {"err":0};

			function pushItem(billtyp,billacct,itemstrings) {
				
				var biller,biller,acct,pushs,err;

				biller = 1;

				pushs = itemstrings.split('==');
				err = 0;

				if(err == 0) {
					
					var index = addedCarts.indexOf(pushs[0]);
				
					if(index > -1) {
						writeObjheader('pos-header-notification','Cart Notification!');
						writeObjheader('pos-message-notification','Item already added! You can change quantity');
						objDisplay('notifybox');
						autohidePopupBox('notifybox',2000);
					} else {
						addedCarts.push(pushs[0]);
						
						var uni_id = Math.random() * 10000;

						var subtotal = document.getElementById('sub-total');
						var consumptiontax = document.getElementById('consumption-tax');
						var grandtotal = document.getElementById('grand-total');
						var ctax = document.getElementById('for-ctax-charge');
						//var discountamt = document.getElementById('discount-amount');

						contr = document.getElementById('datasheet');
						var tr = document.createElement('tr');
						tr.id = 'tr'+uni_id;

						var td1 = document.createElement('td');
						var td2 = document.createElement('td');
						var td3 = document.createElement('td');
						var td4 = document.createElement('td');
						var td5 = document.createElement('td');

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
							grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);
							
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

						div3.className = 'ft-xsml-size';
						div3.innerHTML = numberFormat(pushs[3]);

						txt2.id = 'price'+uni_id;
						txt2.type = 'hidden';
						txt2.name = 'price[]';
						txt2.value = pushs[3];

						td3.align='center';
						td3.width='100px';
						td3.appendChild(div3);
						td3.appendChild(txt2);

						txt3.id = 'qty'+uni_id;
						txt3.type = 'number';
						txt3.step = 1;
						txt3.min = 1;
						txt3.name = 'qty[]';
						txt3.value = 1;
						txt3.required = 'required';
						txt3.onkeyup = () => {
							
							txt5.value = 0; txt6.value = 0;
							//document.getElementById('discount-amount-label').innerHTML = "&#8358; 0.00";
							//grandtotal.value = Number(grandtotal.value) - Number(discountamt.value);
							//discountamt.value = 0;

							grandtotal.value = Number(grandtotal.value);

							document.getElementById('grand-total-label').innerHTML = "&#8358; "+numberFormat(grandtotal.value);
							

							var err_qty;

							if(pushs[5] == 'consumable' && Number(txt3.value) > Number(pushs[6])) {
								err_qty = 1;
							} else {
								err_qty = 0;
							}

							if(err_qty == 1) {
								alert('-- Consumable Stock Item --\nPlease use lower value. Stock available: '+pushs[6]);
								txt3.value = 0;
								amount = 0;

								subtotal.value = (eval(subtotal.value) - eval(txt4.value)) + eval(amount);
								consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
								grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);

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
									grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);

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

						td4.align='center';
						td4.appendChild(txt3);

						txt4.id = 'amt'+uni_id;
						txt4.type = 'hidden';
						txt4.name = 'amount[]';
						txt4.value = amount;
						txt4.required = 'required';

						div4.className = 'ft-xsml-size';
						div4.innerHTML = numberFormat(amount);

						td5.align='center';
						td5.appendChild(txt4);
						td5.appendChild(div4);

						tr.appendChild(td1);
						tr.appendChild(td2);
						tr.appendChild(td3);
						tr.appendChild(td4);
						tr.appendChild(td5);
						
						contr.appendChild(tr);

						subtotal.value = eval(subtotal.value) + eval(amount);
						consumptiontax.value = (eval(ctax.value) / 100) * eval(subtotal.value);
						grandtotal.value = eval(subtotal.value) + eval(consumptiontax.value);
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
				}
			}


			function xclcounter() {
				chgclass('counter-notification','noshow motion');
				document.getElementById('counter-notification').innerHTML = "";
			}


			function pfr(obj) {
				if(obj.value != '' && obj.value != ' ' && obj.value !== null) {
					document.getElementById('orderbutton').value='Add to Bill';
				} else {
					document.getElementById('orderbutton').value='Create Order';
				}
			}


			function createOrder(e) {

				e.preventDefault();

				let fr, btn, info, table;

				fr = document.getElementById('orderform');
				btn = document.getElementById('orderbutton');
				info = document.getElementById('sub-total');
				table = document.getElementById('tabletype');

				if(info.value == 0 || (table.value == '' || table.value == 0)) {

					writeObjheader('pos-header-notification','Error Notification!');
					writeObjheader('pos-message-notification','A table either not selected or you have no items in your list');
					objDisplay('notifybox');
					autohidePopupBox('notifybox',5000);

				} else {

					fr.setAttribute('onsubmit','submitOrder(event)');

					btn.value = 'Confirm Order';
					btn.classList.replace('black-white-state','blue-white-state');
				}
			}


			function submitOrder(e) {

				if(e) { e.preventDefault(); }

				let framer, fr;

				framer = document.getElementById('formsubmission');
				fr = document.getElementById('orderform');

				fr.setAttribute('onsubmit','');
				fr.setAttribute('target','frameOrder');
				fr.setAttribute('action','submitorder.php');

				framer.classList.remove('noshow');

				setTimeout(() => {
					fr.submit();
				},1000);
			}


			function payOrder(obj) {

				let framer, orderno, frBx, frSp;

				framer = document.getElementById('formsubmission');
				framer.classList.remove('noshow');

				orderno = obj.getAttribute('data-order');
				frBx = document.getElementById('frameBox');
				frSp = document.getElementById('frameOrder');

				frBx.classList.replace('cs-height-200','nc-height-100');
				frSp.height = "90%";
				frSp.src = "payorder.php?orderno="+orderno;
			}

		</script>

	</body>
</html>