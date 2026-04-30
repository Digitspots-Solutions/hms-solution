<?php

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

$all_pending_orders = select_dt_fetch('datelogged',$server_get_date,$tbL99,'order_number','order_number');

?>

<div class="ln-display-box float-left nc-width-70">
	<span class="float-right top-pull-3"><a href="javascript:opencart()" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state rounded-button ft-sml-size">Add Item +</a></span>
	<h3 class="large nobold">For open sales outlet: Click <u>add item</u> button to create new order</h3><br>
	<h4 class="large alignct">NEW ORDER</h4><br>
	
	<form action="pos/process_open_order.php" method="post" onsubmit="document.getElementById('submitbutton').value='Processing..'; setTimeout(() => { document.getElementById('submitbutton').setAttribute('type','button'); },200)" autocomplete="off">
		<div class="bottom-pull-20 x-scroll">
			<div id="pos-header" class="nc-width-100 sml-rounded-button noscroll">
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
							if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
								?>
									<th width="100px" align="center">Cover</th>
								<?php
							}
						?>

						<th width="100px" align="center">Reserve from</th>
						<th width="100px" align="center">Reserve to</th>
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
							<input type="text" name="guestname" id="guestname" onkeyup="getnames(this)" required="required">
							<input type="hidden" name="guestid" id="guestid">
							<div id="name-suggest" class="noshow motion" align="left"></div>
						</td>
						

						<?php
							if(isset($checkIfisfoodset) && $checkIfisfoodset == 'Yes') {
								?>
									<td width="100px" align="center">
										<input type="text" name="cover" id="cover" pattern="\d*">
									</td>
								<?php
							}
						?>

						<td width="100px" align="center">
							<input type="text" name="rfrom" id="rfrom" placeholder="Choose?" onfocus="textodate(this.id)">
						</td>
						<td width="100px" align="center">
							<input type="text" name="rto" id="rto" placeholder="Choose?" onfocus="textodate(this.id)">
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="cs-height-30">
		</div>

		<div class="ft-xsml-size alignct bottom-push-15">
			<b class="fas fa-clock right-push-10"></b> Use tab key to move around the fields while you change the value
		</div>

		<div class="sml-rounded-button noscroll">
			<div class="bottom-pull-20 x-scroll">
				<div id="pos-items" class="nc-width-100 sml-rounded-button noscroll">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="30px" align="center"></th>
							<th width="50px" align="center"></th>
							<th align="center">Item</th>
							<th align="center">Category</th>
							<th align="center">Quantity</th>
							<th align="center">Rate</th>
							<th align="center">Amount</th>
						</tr>
						<tbody id="item-pack"></tbody>
						<tr>
							<td colspan="6" class="right-pull-15 nobordercolor" align="right">Grand Total (&#8358;)</td>
							<td width="150px" align="center"><input type="text" name="xgrandtotal" id="xgrandtotal" placeholder="0.00" readonly="readonly"><input type="hidden" name="grandtotal" id="grandtotal" value="0"><input type="hidden" name="actualgrandtotal" id="actualgrandtotal" value="0"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div id="ctrlbx" class="noshow alignct top-push-20">
			<div class="bottom-push-15">
				<h4 class="large nobold light-red-font alignct">* Please indicate only if any tax should be ignored</h4> 
				<div class="top-push-5 ft-sml-size">
					<input type="checkbox" name="revoketax1" value="VAT"> Ignore VAT &nbsp;&nbsp;
					<input type="checkbox" name="revoketax2" value="Service Charge"> Ignore Service Charge &nbsp;&nbsp;
					<input type="checkbox" name="revoketax3" value="Consumption Charge"> Ignore Consumption Charge
				</div>
			</div>

			<h4 class="large nobold black-font alignct right-pull-20 left-pull-20">Please check your order before clicking on <u>create-order</u> button</h4><br>
			<input type="submit" name="submitbutton" value="Create Order" class="submit pads10 dark-black-white-state rounded-button nc-width-30">
		</div>
	</form>
</div>
<div class="ln-display-box float-right nc-width-25 box-border-thick sml-rounded-button pads10 obj-light-shadow">
	<h4 class="large">Taxes</h4><br>
	<?php
		$tax_query = array("deletedata"=>0,"status"=>"Active","postoreid"=>$cur_pos_store_id);
		$tax_data = mysqli_data_fetch($tbL18,'id,taxname,taxcharge',$tax_query,'array');

		if(is_array($tax_data)) {
			foreach($tax_data as $key => $value) {
				?>
					<div class="block-element bottom-push-7">
						<div class="ln-display-box float-left nc-width-80">
							<h4 class="large nobold"><?php echo $value['taxname']; ?></h4>
							<h4 class="large nobold default-text-font-bold"><?php echo $value['taxcharge']; ?>%</h4>
						</div>
						<div class="ln-display-box float-left nc-width-20" align="right">
							<div id="tx<?php echo $value['id']; ?>" class="cs-width-20 cs-height-20 box-border-thick xsml-rounded-button noscroll alignct white-font top-pull-3 anchor motion" title="<?php echo $value['taxname']; ?>" lang="uncollapsed" onclick="addons(this,'<?php echo $value['taxcharge']; ?>')"><b id="chk<?php echo $value['id']; ?>" class="fa-checker nobold"></b></div>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
				<?php
			}
		}
	?>

	<br><br>
</div>
<div class="block-element new-line-space">
</div>




<script src="../../js/autosugest.js"></script>

<script>

	const pos_store_id = "<?php echo $_SESSION['postoreid']; ?>";
	const carts = {"bkt":1}

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
				if(xhr.readyState == 4)
				{
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


	function opencart() {

		var contr = document.getElementById('item-pack');
		var psttotal = document.getElementById('grandtotal');
		var pstacttotal = document.getElementById('actualgrandtotal');
		var psttotalx = document.getElementById('xgrandtotal');

		var tr = document.createElement('tr');
		
		var td1 = document.createElement('td');
		var td2 = document.createElement('td');
		var td3 = document.createElement('td');
		var td3x = document.createElement('td');
		var td4 = document.createElement('td');
		var td5 = document.createElement('td');
		var td6 = document.createElement('td');

		td1.className = "right-pull-10 left-pull-10";
		td2.className = "right-pull-10 left-pull-10 numb";
		td3.className = "right-pull-10 left-pull-10";
		td3x.className = "right-pull-10 left-pull-10";
		td4.className = "right-pull-10 left-pull-10";
		td5.className = "right-pull-10 left-pull-10";
		td6.className = "right-pull-10 left-pull-10";

		var txt1 = document.createElement('input');
		var txt2 = document.createElement('input');
		var txt3 = document.createElement('input');
		var txt4 = document.createElement('input');

		var select = document.createElement('select');
		var opt = document.createElement('option');

		var trash = document.createElement('b');
		trash.className = "fa-trash nobold";
		trash.title = "Trash";

		trash.onclick = () => {
			contr.removeChild(tr);
			if(eval(carts.bkt) > 1) { carts.bkt = eval(carts.bkt) - 1; }
			var n, renum = document.getElementsByClassName('numb');
			for(n=0; n < renum.length; n++) { var nb = eval(n)+1; renum[n].innerHTML = nb+'.'; }

			psttotal.value = eval(psttotal.value) - eval(txt4.value);
			pstacttotal.value = psttotal.value;
			psttotalx.value = numberFormat(psttotal.value);
		}

		txt1.type = "text";
		txt1.id = "item-"+carts.bkt;
		txt1.name = "item[]";
		txt1.placeholder = "Enter here";
		txt1.className = "nopads no-back-black notextborder cs-height-50";
		txt1.setAttribute('required','required');
		txt1.onkeyup = () => {
			titleCase(txt1.value,txt1.id);
		}

		//autoSuggest(txt1.id);

		select.id = "category-"+carts.bkt;
		select.name = "category[]";
		select.required = "required";
		opt.value = '';
		opt.text = 'Choose';
		select.appendChild(opt);
		

		txt2.type = "number";
		txt2.name = "qty[]";
		txt2.placeholder = "Enter here";
		txt2.className = "nopads no-back-black";
		txt2.min = 1;
		txt2.setAttribute('required','required');

		txt2.onblur = () => {
			if(eval(txt3.value) >= 1) {
				var newamt = eval(txt2.value) * eval(txt3.value);
				psttotal.value = (eval(psttotal.value) - eval(txt4.value)) + eval(newamt);
				pstacttotal.value = psttotal.value;
				psttotalx.value = numberFormat(psttotal.value);
				txt4.value = newamt;
			}

			if(txt2.value == null || txt2.value == '') {
				alert('The quantity field is required for computation');
			}
		}

		txt3.type = "number";
		txt3.name = "rate[]";
		txt3.placeholder = "Enter here";
		txt3.className = "nopads no-back-black";
		txt3.min = 1;
		txt3.step = ".01";
		txt3.setAttribute('required','required');

		txt3.onblur = () => {
			if(eval(txt2.value) >= 1 && eval(txt3.value) >= 1) {
				var newamt = eval(txt2.value) * eval(txt3.value);
				if(eval(txt4.value) > 0) { psttotal.value = (eval(psttotal.value) - eval(txt4.value)) + eval(newamt); }
				else { psttotal.value = eval(psttotal.value) + eval(newamt); }
				pstacttotal.value = psttotal.value;
				psttotalx.value = numberFormat(psttotal.value);
				txt4.value = newamt;
			} else {
				txt3.value = "";
				alert('Please input actual quantity and rate');
			}
		}

		txt4.type = "number";
		txt4.name = "amount[]";
		txt4.placeholder = "Auto?";
		txt4.className = "nopads no-back-black";
		txt4.setAttribute('readonly','readonly');
		txt4.min = 1;
		txt4.setAttribute('required','required');

		txt4.onclick = () => {
			if(eval(txt2.value) >= 1 && eval(txt3.value) >= 1) {
				var newamt = eval(txt2.value) * eval(txt3.value);
				if(eval(txt4.value) > 0) { psttotal.value = (eval(psttotal.value) - eval(txt4.value)) + eval(newamt); }
				else { psttotal.value = eval(psttotal.value) + eval(newamt); }
				pstacttotal.value = psttotal.value;
				psttotalx.value = numberFormat(psttotal.value);
				txt4.value = newamt;
			} else {
				txt3.value = ""; txt2.value = "";
				alert('Please input actual quantity and rate');
			}
		}

		td1.appendChild(trash);
		td2.innerHTML = carts.bkt+'.';
		td3.appendChild(txt1);
		td3x.appendChild(select);
		td4.appendChild(txt2);
		td5.appendChild(txt3);
		td6.appendChild(txt4);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td3x);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);

		contr.appendChild(tr);
		

		setTimeout(() => {
			//getdata('category-'+carts.bkt,'eget-pos-category-list',0,'dropbox');
			sqldatastring.sql = "SELECT * FROM pos_store_category_tbl WHERE postoreid="+pos_store_id+" AND status='Active' AND deletedata=0";
			sqldataQuery(wgtparam,sqldatastring);

			function wgtparam(response) {
				var data = JSON.parse(response);
				var i, lshtml, dl = data.datastring;
				
				lshtml = '';

				for(i=0; i<dl.length; i++) {
					lshtml += '<option value="'+dl[i].id+'">'+dl[i].category+'</option>';
				}

				select.innerHTML = lshtml;
				//writeObjheader('category-'+carts.bkt,lshtml);
			}

			objDisplay('ctrlbx');
			
			carts.bkt = eval(carts.bkt) + 1;

		},1000);

	}


	function addons(obj,val) {

		var contr = document.getElementById('item-pack');
		var psttotal = document.getElementById('grandtotal');
		var pstacttotal = document.getElementById('actualgrandtotal');
		var psttotalx = document.getElementById('xgrandtotal');

		if(eval(psttotal.value) > 0) {
			
			
			obj.lang = 'collapsed';
			obj.className = 'cs-width-20 cs-height-20 royal-blue-theme xsml-rounded-button noscroll alignct white-font top-pull-3 anchor motion';

			var id = obj.id, nid = id.replace('tx','chk');
			chgclass(nid,'fa-checker nobold fa-color-strike-17');

			var tr = document.createElement('tr');

			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			var td3 = document.createElement('td');
			
			var txt1 = document.createElement('input');
			var txt2 = document.createElement('input');

			td1.className = "right-pull-10 left-pull-10 alignlt";
			td2.className = "right-pull-15 left-pull-10 alignrt";
			td2.setAttribute('colspan',5);
			td3.className = "";

			var trash = document.createElement('b');
			trash.className = "fa-trash nobold";
			trash.title = "Trash";

			trash.onclick = () => {
				contr.removeChild(tr);
				psttotal.value = eval(psttotal.value) - eval(txt1.value);
				psttotalx.value = numberFormat(psttotal.value);

				obj.lang = 'uncollapsed';
				obj.className = 'cs-width-20 cs-height-20 box-border-thick xsml-rounded-button noscroll alignct white-font top-pull-3 anchor motion';
			
				var id = obj.id, nid = id.replace('tx','chk');
				chgclass(nid,'fa-checker nobold');
			}

			txt1.type = "text";
			txt1.name = "taxes[]";
			txt1.placeholder = "Auto?";
			txt1.setAttribute('readonly','readonly');
			txt1.min = 1;

			txt2.type = "hidden";
			txt2.name = "taxnames[]";
			txt2.value = obj.title;

			var theAddon = (val / 100) * eval(pstacttotal.value);
			txt1.value = tofixe(theAddon,1);
			psttotal.value = eval(psttotal.value) + eval(theAddon);
			psttotalx.value = numberFormat(psttotal.value);

			td1.appendChild(trash);
			td2.innerHTML = obj.title;
			td2.appendChild(txt2);
			td3.appendChild(txt1);

			tr.appendChild(td1);
			tr.appendChild(td2);
			tr.appendChild(td3);
			
			contr.appendChild(tr);
		}
	}


	function changeGuest(obj,strings) {
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

			sqldatastring.sql = "SELECT * FROM cspg_tbl WHERE id="+obj.value;
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;
				document.getElementById('guestname').value = data[0].code;
			}
			
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
		
		sqldatastring.sql = "SELECT * FROM guest_tbl WHERE id="+guest;
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;
			vhtml = data[0].fname;
			
			if(data[0].lname !== null && data[0].lname != '') {
				vhtml += ' '+data[0].lname;
			}

			document.getElementById('guestname').value = vhtml;
		}
		
	}

	function getnames(obj) {

		var guest = obj.value;
		chgclass('name-suggest','fx-position-flow zind-3 cs-width-300 cs-height-350 white-theme obj-light-shadow sml-rounded-button pads20 top-push-20 y-scroll motion');
		sqldatastring.sql = "SELECT distinct(fname), lname FROM walkin_guest_tbl WHERE fname REGEXP '^"+guest+"' OR lname REGEXP '^"+guest+"'";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '<span class="float-right"><a href="javascript:nJs()" class="dark-grey-font"><b class="mbri-close"></b></a></span>';
			vhtml += '<h4 class="xlarge nobold black-font">Go by related names</h4><br>';

			for(i=0; i<data.length; i++) {
				vhtml += '<div class="bottom-push-10 anchor" title="'+data[i].fname+' '+data[i].lname+'" onclick="jsName(this.title)">';
				vhtml += '<span class="float-right top-pull-3"><b class="fa-arrow-right"></b></span>';
				vhtml += '<h3 class="large nobold default-text-font-bold">'+data[i].fname+' '+data[i].lname+'</h3>';
				vhtml += '</div>';
			}

			document.getElementById('name-suggest').innerHTML = vhtml;
		}
	}

	function jsName(name) {
		document.getElementById('guestname').value = name;
	}

	function nJs() {
		chgclass('name-suggest','noshow motion');
	}

	window.onload = () => {
		var temptkn = {}, jtemptkn = JSON.stringify(temptkn);
		if(sessionStorage.getItem('temptkn') === null) { sessionStorage.setItem('temptkn',jtemptkn); }
		json_getdata('suggy','pos_store_product_tbl','opensales','storagetype','item','array');
		setTimeout(function() {
			var i, ths, suggyArry, g_suggy = sessionStorage.getItem('temptkn');
			if(g_suggy !== null && g_suggy !== undefined && g_suggy !='') {
				suggyArry = JSON.parse(g_suggy);
				ths = suggyArry.suggy;
				for(i=0; i < ths.length; i++) {suggestions.push(ths[i]);}
			}
		},2000);
	}

</script>