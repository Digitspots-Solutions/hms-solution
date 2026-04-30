<?php $smdl = "material control"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Here you can create new purchase order. Follow on-screen instruction
 	</span>
 	<span class="ln-display-box float-right">
		<h3 class="large">Purchase Request</h3>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="block-element light-yellow-theme pads10 bottom-push-20 ft-xsml-size add-bold">
	&nbsp; * Select a supplier to start creating new purchase
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	createDatabasetable($var_tbl_116);

	#-----------------------------------------------------------------------------------------------------------------

		if(isset($_POST['continuebutton']) && isset($_POST['checkers'])) {
			
			foreach($_POST['checkers'] as $chkr) {
				//update the gstat as CONFIRM
				//select the QC to update the level of approval
				//include JOB ORDER FORM to re-select the right supplier
				$query = array("order_number"=>$chkr);
				$data = array("gstat"=>"Confirm");
				$data_updated = mysqli_data_update($tbL121,$data,$query);

				if(isset($data_updated) && $data_updated == 2) { 
					$adj_order_no = $chkr;
					include "tojobform.php";
					/*$wgt_Qc = idget_fdata($tbL121,'order_number',$chkr,'qc');
					$wgt_nQc = idget_fdata($tbL121,'order_number',$chkr,'numberof_approvals');

					$query2 = array("qc"=>$wgt_Qc,"approve"=>6,"deletedata"=>0);
					$sql_data_select = mysqli_data_fetch($tbL108,'role',$query2,'array');

					$startAt = 0;

					foreach($sql_data_select as $key => $val) {
						$qcCol = $tqc[$startAt];
						$data = array($qcCol=>$val['role']);
						mysqli_data_update($tbL121,$data,$query);
						$startAt++;
					}*/
				}
				
				break;
			}
		}

	#-----------------------------------------------------------------------------------------------------------------

		if(isset($_POST['deletebutton']) && isset($_POST['checkers'])) {
			foreach($_POST['checkers'] as $chkr) {
				$query = array("order_number"=>$chkr);
				trash_record($tbL121,$query);
				$query = "";
			}
		}

	#-----------------------------------------------------------------------------------------------------------------

		if(isset($_POST['orderbutton'])) {
			
			//create a table for this post
			createDatabasetable($var_tbl_116);

			$supplier = $_POST['supplier'];
			$store = $_POST['store'];
			$workflow = $_POST['workflow'];
			$deliverydate = $_POST['deliverydate'];
			$deliverynote = escape_data($_POST['remarks']);

			$xf_sql = "COUNT(role)";
			$xf_query = "qc={$workflow} AND approve=6 AND deletedata=0";
			$noofapr = mysqli_arithmetic_data($tbL108,$xf_sql,$xf_query);

			$dataid = $_POST['dataid'];
			$uom = $_POST['uom'];
			$unitcost = $_POST['unitcost'];
			$qty = $_POST['qty'];
			$amount = $_POST['amount'];

			$subtotal = str_replace(',','',$_POST['sub-total']);
			$taxes = str_replace(',','',$_POST['taxes']);
			$grandtotal = str_replace(',','',$_POST['grand-total']);

			$order_no = 'PR'.substr(mt_rand(100,999999999999),1,6);
			$isSuccess = 0; $wgtd = "";

			for($i=0; $i<count($dataid); $i++) {

				$insert_constrain = ""; //gmp_intval(gmpnumber);
				$insert_dataproperty = array("itemid"=>$dataid[$i],"uom"=>$uom[$i],"supplierid"=>$supplier,"store"=>$store,"order_number"=>$order_no,"order_date"=>$server_get_date,"delivery_date"=>$deliverydate,"delivery_note"=>$deliverynote,"unitprice"=>$unitcost[$i],"qty_ordered"=>$qty[$i],"order_total_amount"=>$subtotal,"order_tax_amount"=>$taxes,"order_net_amount"=>$grandtotal,"numberof_approvals"=>$noofapr,"qc"=>$workflow,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				$data_inserted = mysqli_data_insert($tbL121,$insert_dataproperty,$insert_constrain);

				if(isset($data_inserted) && $data_inserted == 2) {
					
					$isSuccess += 1;

					$wgtd .= '<tr>';
					$wgtd .= '<td width="30px" align="center">'.$isSuccess.'</td>';
					$wgtd .= '<td width="200px" align="center">'.idget_data($tbL118,$dataid[$i],'item').'</td>';
					$wgtd .= '<td width="100px" align="center">'.$unitcost[$i].'</td>';
					$wgtd .= '<td width="200px" align="center">'.$qty[$i].' '.$uom[$i].'</td>';
					$wgtd .= '<td width="150px" align="right">'.$amount[$i].'</td>';
					$wgtd .= '</tr>';
				}
			}

			if(isset($isSuccess) && $isSuccess > 0) {

				$wgtd .= '<tr><td colspan="5">&nbsp;</td></tr>';
				$wgtd .= '<tr><td rowspan="2">'.$deliverynote.'</td><td>&nbsp;</td></tr>';
				$wgtd .= '<tr>';
				$wgtd .= '<td align="right">&#8358;'.$subtotal.'</td>';
				$wgtd .= '<td align="right">&#8358;'.$taxes.'</td>';
				$wgtd .= '<td align="right">&#8358;'.$grandtotal.'</td>';
				$wgtd .= '</tr>';

				//pop created purchase order
				include "pr_orders.php";

				//create a log file
				$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new purchase order","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
			}
		}

	#-----------------------------------------------------------------------------------------------------------------

	$mctax_query = array("deletedata"=>0);
	$mctax = mysqli_data_fetch($tbL149,'taxcharge',$mctax_query,'array');
	$mctx = ""; if(is_array($mctax)) { foreach($mctax as $key => $val) { $mctx .= $val['taxcharge'].','; } } else { $mctx = 0; }

	$suppliers = select_dt_fetch('status','Active',$tbL114,'id','supplier_name');
	$stores = select_dt_fetch('status','Active',$tbL123,'id','store_name');

	$main_store_query = array("store_type"=>5,"parent_store"=>0);
	$main_store = mysqli_data_fetch($tbL123,'id,store_name,store_number',$main_store_query,'noarray');

	$amdl = 6;

	include "get_avail_workflow.php";
?>


<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	<div class="block-element box-border-thick sml-rounded-button pads15">
		<span class="ln-display-box float-left nc-width-20 right-push-30">
			<div class="block-element royal-blue-theme white-font pads10 bottom-push-7 ft-sml-size">
				Supplier
			</div>
			<select name="supplier" id="supplier" onchange="getSupplier()" required="required">
				<option value="" selected="selected">Select</option>
				<?php echo $suppliers; ?>
			</select>
			<div id="supplier-detail" class="noshow left-pull-7">
				<h3 id="sl-name" class="large"></h3>
				<small id="sl-other" class="block-element bottom-push-10"></small>
				<h4 class="large red-font">terms of payment</h4>
				<small id="sl-pt" class="block-element top-push-3"></small>
			</div>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-push-30">
			<div class="block-element royal-blue-theme white-font pads10 bottom-push-7 ft-sml-size">
				Store
			</div>
			<select name="store" id="store" onchange="getStore()" required="required">
				<option value="<?php echo $main_store[0]; ?>" selected="selected"><?php echo $main_store[1]; ?></option>
				<?php echo $stores; ?>
			</select>
			<div id="store-detail" class="block-element left-pull-7">
				<h3 id="st-name" class="large"><?php echo $main_store[1]; ?></h3>
				<small id="st-number" class="block-element"><?php echo $main_store[2]; ?></small>
			</div>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-push-30">
			<div class="block-element royal-blue-theme white-font pads10 bottom-push-7 ft-sml-size">
				Workflow
			</div>
			<select name="workflow" id="workflow" required="required">
				<?php echo $ths_workflow_names; ?>
			</select>
		</span>
		<span class="ln-display-box float-left nc-width-20 right-push-30">
			<div class="block-element royal-blue-theme white-font pads10 bottom-push-7 ft-sml-size">
				Delivery Date
			</div>
			<input type="text" name="deliverydate" id="deliverydate" placeholder="Delivery on?" onfocus="textodate('deliverydate')">
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
	<div id="load-notify" class="noshow add-bold">
	</div>
	<div id="order-items" class="noshow box-border-thick sml-rounded-button pads15 top-push-20">
		<a href="javascript:void(0)" class="blue-font ft-xsml-size" onclick="chgclass('item-filter','fx-position-stick fscr zind-3 txp5-white motion y-scroll'); parent.document.getElementById('workspace').scrollTop = 0">Search & Add Item</a>
		<div class="block-element sml-rounded-button noscroll top-push-5 bottom-push-15">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th width="30px" align="center"></th>
					<th width="200px" align="center">Item</th>
					<th width="70px" align="center"></th>
					<th width="100px" align="center">Unit Cost</th>
					<th width="100px" align="center">Quantity</th>
					<th width="150px" align="center">Amount</th>
				</tr>
				<tbody id="datasheet"></tbody>
			</table>
			<input type="hidden" id="rwcounter" value="0">
		</div>
		<div class="ln-display-box float-left nc-width-40">
			<textarea name="remarks" id="remarks" placeholder="Enter delivery note?" title="Delivery Note"></textarea>
		</div>
		<div class="ln-display-box float-right nc-width-40">
			<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
				<small class="dark-grey-font right-push-20">Billing Amount:</small> <small id="sub-total-label" class="add-bold">&#8358; 0.00</small>
				<input type="hidden" name="sub-total" id="sub-total" value="0">
			</span>
			<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
				<small class="dark-grey-font right-push-20">Taxes:</small> <small id="taxes-label" class="add-bold">&#8358; 0.00</small>
				<!--<input type="hidden" id="for-ctax-charge" value="<?php //echo $cur_pos_ctax; ?>">-->
				<input type="hidden" name="taxes" id="taxes" value="0">
			</span>
			<span class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-7">
				<small class="dark-grey-font right-push-20">Net Amount:</small> <small id="grand-total-label" class="add-bold">&#8358; 0.00</small>
				<input type="hidden" name="grand-total" id="grand-total" value="0">
			</span>
		</div>
		<div class="block-element new-line-space">
		</div>
	</div>

	<div class="block-element top-pull-20 alignct">
		<input type="submit" name="orderbutton" value="Create Order" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor">
	</div>
</form>

<div id="item-filter" class="noshow motion" align="center">
	<div class="cs-height-120"></div>
	<div id="wksp" class="nc-width-60 white-theme sml-rounded-button pads30 box-border-thick" align="left">
		<p class="bottom-pull-20 alignrt">
			<a href="javascript://" class="black-font" onclick="chgclass('item-filter','noshow motion')"><b class="mbri-close"></b></a>
		</p>
		<fieldset>
			<legend class="ft-sml-size default-text-font-bold">Search for item</legend>
			<form action="" method="post" class="nomargin nopads" onsubmit="searchItem(event)">
				<span class="ln-display-box float-left nc-width-80">
					<input type="text" name="wgtsearch" id="wgtsearch" placeholder="Enter here" class="nopads no-back-black top-push-7">
				</span>
				<span class="ln-display-box float-left nc-width-20 alignrt">
					<input type="submit" name="searchbutton" value="Search" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button anchor">
				</span>
				<span class="block-element new-line-space">
				</span>
			</form>
		</fieldset>
		<div id="itemizing" class="top-pull-20">
		</div>
	</div>
</div>

<?php
	if($logs == 'purchase-request') {
		$query = array("gstat"=>"Pending");
		$isresult = mysqli_data_checkr($tbL121,'(*)',$query);
		if($isresult == true) { include "ppo.php"; }
	}
?>


<script>

	function getSupplier() {
		var xhr,file,random_numbr,ajaxson,result;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		objDisplay('load-notify');
		writeObjheader('load-notify','fetching supplier details..');

		var rdata = document.getElementById('supplier').value;
		file = phpfile+"dbquery.php?r=get-supplier-data&data="+rdata+"&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					objHidden('load-notify');
					ajaxson = JSON.parse(xhr.responseText);

					objDisplay('supplier-detail'); objDisplay('order-items');
					document.getElementById('sl-name').innerHTML = ajaxson.sname;
					document.getElementById('sl-other').innerHTML = ajaxson.address+'. '+ajaxson.city+'<br>'+ajaxson.mobile;
					document.getElementById('sl-pt').innerHTML = ajaxson.term;
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}


	function getStore() {
		var xhr,file,random_numbr,ajaxson,result;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		objDisplay('load-notify');
		writeObjheader('load-notify','fetching store details..');

		var rdata = document.getElementById('store').value;
		file = phpfile+"dbquery.php?r=get-store-data&data="+rdata+"&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					objHidden('load-notify');
					ajaxson = JSON.parse(xhr.responseText);

					document.getElementById('st-name').innerHTML = ajaxson.sname;
					document.getElementById('st-number').innerHTML = ajaxson.snumber;
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}


	function searchItem(e) {

		e.preventDefault();

		var xhr,file,random_numbr,ajaxson,result,i;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		writeObjheader('itemizing','<h4 class="xlarge nobold">fetching related items..</h4>');

		var rdata = document.getElementById('wgtsearch').value;
		file = phpfile+"dbquery.php?r=get-newpr-item&data="+rdata+"&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					htmlpassval('','wgtsearch');
					sessionStorage.setItem('storeditems',xhr.responseText);
					ajaxson = JSON.parse(xhr.responseText);
					result = '';
					for(i=0; i<ajaxson.length; i++) {
						result += '<div class="bottom-push-15">';
						result += '<span class="float-right ft-sml-size"><a href="javascript://" class="blue-font" name="'+ajaxson[i].code+'" onclick="addItem(this.name)">Add +</a></span>';
						result += '<h4 class="xlarge nobold">'+ajaxson[i].item+' ('+ajaxson[i].code+')</h4>';
						result += '</div>';
					}

					writeObjheader('itemizing',result);
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}

	function addItem(item) {
		var sheet = document.getElementById('datasheet');
		
		var tr = document.createElement('tr');
		var td1 = document.createElement('td');
		var td2 = document.createElement('td');
		var td3 = document.createElement('td');
		var td4 = document.createElement('td');
		var td5 = document.createElement('td');
		var td6 = document.createElement('td');

		var b = document.createElement('b');
		var h4 = document.createElement('h4');
		var txt1 = document.createElement('input');
		var txt2 = document.createElement('input');
		var txt3 = document.createElement('input');
		var txt4 = document.createElement('input');
		var selectbox = document.createElement('select');
		var selectopt = document.createElement('option');

		b.title = 'Remove from list';
		b.className = 'fa-trash nobold';
		b.onclick = function() {
			sheet.removeChild(tr);
			doSumup();
		}
		
		var j,nos,temp_items = JSON.parse(sessionStorage.getItem('storeditems'));
		
		if(document.getElementsByName('amount[]')) {
			var doms = document.getElementsByName('amount[]');
			nos = eval(doms.length) + 1;
		} else {
			nos = 1;
		}
		
		for(j=0; j<temp_items.length; j++) {
			if(temp_items[j].code == item) {
				
				td1.className = 'alignct';
				td1.appendChild(b);

				txt1.type = 'hidden';
				txt1.name = 'dataid[]';
				txt1.value = temp_items[j].dataid;

				h4.className = 'xlarge nobold alignct';
				h4.innerHTML = temp_items[j].item;

				td2.appendChild(txt1);
				td2.appendChild(h4);

				selectbox.id = 'uom-'+nos;
				selectbox.name = 'uom[]';
				selectopt.value = temp_items[j].uom;
				selectopt.text = temp_items[j].uom;
				selectbox.appendChild(selectopt);
				td6.appendChild(selectbox);

				txt2.type = 'text';
				txt2.id = 'unitcost-'+nos;
				txt2.name = 'unitcost[]';
				txt2.placeholder = 'Enter unit cost?';
				txt2.value = 0;
				td3.appendChild(txt2);
				txt2.onblur = function() {
					txt4.value = txt2.value * txt3.value;
					setTimeout(doSumup(),500);
				}

				txt3.type = 'text';
				txt3.id = 'qty-'+nos;
				txt3.name = 'qty[]';
				txt3.placeholder = 'Enter qty?';
				txt3.value = 1;
				td4.appendChild(txt3);
				txt3.onblur = function() {
					txt4.value = txt2.value * txt3.value;
					setTimeout(doSumup(),500);
				}

				txt4.type = 'text';
				txt4.id = 'amount-'+nos;
				txt4.name = 'amount[]';
				txt4.placeholder = 'Enter subtotal?';
				txt4.value = 0;
				txt4.readOnly = true;
				td5.appendChild(txt4);

				tr.appendChild(td1);
				tr.appendChild(td2);
				tr.appendChild(td6);
				tr.appendChild(td3);
				tr.appendChild(td4);
				tr.appendChild(td5);

				sheet.appendChild(tr);
				doSumup();

				setTimeout(() => {
					dataQuery(temp_items[j].dataid,'unitcost-'+nos,'qty-'+nos,'amount-'+nos);
					//getdata('uom-'+nos,'eget-uom-list',1,'dropbox');
				},100);

				break;
			}
		}
	}

	 
	function doSumup() {
		var j,sumbill=0,amts = document.getElementsByName('amount[]');
		for(j=0; j<amts.length; j++) { sumbill = eval(sumbill) + eval(amts[j].value); }

		document.getElementById('sub-total').value = numberFormat(sumbill);
		document.getElementById('sub-total-label').innerHTML = '&#8358; '+numberFormat(sumbill);

		var t,ataxes=0,tx,taxes = "<?php echo $mctx; ?>";
		tx = taxes.split(',');
		for(t=0; t<tx.length; t++) {
			var jtx = "";
			if(tx[t] !== null && tx[t] > 0) { jtx = (eval(tx[t]) / 100) * sumbill; }
			else { jtx = 0; }

			ataxes = eval(ataxes) + jtx;
		}

		ataxes = tofixe(ataxes,2);

		document.getElementById('taxes').value = numberFormat(ataxes);
		document.getElementById('taxes-label').innerHTML = '&#8358; '+numberFormat(ataxes);

		var grandtotal = eval(sumbill) + eval(ataxes);

		document.getElementById('grand-total').value = numberFormat(grandtotal);
		document.getElementById('grand-total-label').innerHTML = '&#8358; '+numberFormat(grandtotal);
	}


	function dataQuery(val,id1,id2,id3) {
		
		var xhr,url;
		var supplier = document.getElementById('supplier').value;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		url = phpfile+"dbquery.php?data="+val+"&altdata="+supplier+"&r=get-item-last-price&dataSend=200";
		
		xhr.onreadystatechange = () => {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					document.getElementById(id1).value = xhr.responseText;
					document.getElementById(id3).value = eval(xhr.responseText) * eval(document.getElementById(id2).value);
				}
			}
		};

		xhr.open('GET', url, true);
		xhr.send();
	}

</script>