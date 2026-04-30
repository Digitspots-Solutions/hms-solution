<div class="pads30">
	
	<div class="alignlt"><h3 class="xlarge nobold nomargin"><a href="<?php echo $ths_page; ?>" title="Refresh" class="right-push-10"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a> Here you can create an open invoice that hit the store directly without approval process</h3></div>

	<br><br>

	<h4 class="xlarge nobold black-font alignct"><b class="fas fa-question-circle right-push-5"></b>Choose supplier, item then press enter-key upon indicating item quantity to create new record or tab-key to end</h4><br>

	<form action="" method="post" autocomplete="off" onsubmit="xframeenablescroll(); onsubmt('submitbutton','Submitting..')">
		<input type="hidden" name="uri" value="insert-open-invoice">

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
				<li class="nc-width-30 right-pull-10">
					<div class="xform">
						<span class="right-pull-5 left-pull-3">
							<label>Delivery Date</label>
							<input type="text" name="deliverydate" id="deliverydate" placeholder="Enter date here?" class="nopads no-back-black" onfocus="textodate(this.id)" required>
						</span>
					</div>
				</li>
				<li class="nc-width-30 right-pull-10">
					<div class="xform">
						<span class="right-pull-5 left-pull-3">
							<label>Delivery Note</label>
							<input type="text" name="deliverynote" id="deliverynote" placeholder="Enter here?" class="nopads no-back-black">
						</span>
					</div>
				</li>
				<li></li>
			</ul>
		</div>

		<div class="">
			<div class="">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td class="default-text-font-bold right-pull-10 left-pull-10 cs-width-200">Supplier</td>
						<td class="default-text-font-bold right-pull-10 left-pull-10 cs-width-200">Item</td>
						<td class="default-text-font-bold right-pull-10 left-pull-10">Cost Price</td>
						<td class="default-text-font-bold right-pull-10 left-pull-10">Quantity</td>
						<td class="default-text-font-bold right-pull-10 left-pull-10">Total Cost</td>
					</tr>
					<tbody id="table-form">
						<tr>
							<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200">
								<select name="supplier[]" id="supplier1" class="nopads no-back-black" onclick="wgets4(3,this.id,0)">
								</select>
							</td>
							<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200">
								<!--<select name="item[]" id="item1" class="nopads no-back-black" onclick="wgti(this.id)" onchange="wgets5(4,this.id,0)"><option value="">Choose?</option></select>-->
								<select name="item[]" id="item1" class="noshow nopads no-back-black"><option value=""></option></select>
								<a href="javascript:void(0)" class="blue-font" onclick="ossr(this.lang,1)" lang="item1-sbox"><b class="fas fa-plus-circle nobold"></b> Add item</a>
								<div id="item1-sbox" class="noshow cs-width-250 fx-position-flow zind-2 white-theme">
									<div class="grey-theme pads15">
										<input type="text" id="item1-tbox" class="nopads no-back-black" placeholder="Type to search.." onkeyup="xgetitem(this)">
									</div>
									<div id="item1-dlist" class="cs-height-200 pads15 y-scroll">
									</div>
									<p class="top-pull-15 bottom-pull-15 alignct">
										<input type="button" value="Cancel" class="rounded-button anchor" onclick="ossr(this.lang,0)" lang="item1-sbox">
									</p>
								</div>
							</td>
							<td class="nunito-semibold right-pull-10 left-pull-10">
								<input list="thecostprice1" name="unitcost[]" id="unitcost1" placeholder="Type to choose?" class="nopads no-back-black unitcost" oninput="nextTp(1)" readonly required><datalist id="thecostprice1"></datalist>
							</td>
							<td class="nunito-semibold right-pull-10 left-pull-10">
								<input type="number" min="1" step=".01" name="quantity[]" id="quantity1" placeholder="Enter quantity?" value="1" class="nopads no-back-black quantity" onkeypress="nextRow(this.id,event)" onblur="lastRow(this.id)" required>
							</td>
							<td class="nunito-semibold right-pull-10 left-pull-10">
								<input type="text" name="totalcost[]" id="totalcost1" placeholder="Auto?" class="nopads no-back-black totalcost" readonly required>
							</td>
						</tr>
					</tbody>
					<tr class="yellow-theme">
						<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200">
							&nbsp;
						</td>
						<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200 alignrt">
							Price:
						</td>
						<td class="nunito-semibold right-pull-10 left-pull-10">
							<input type="text" name="grandcost" id="grandcost" placeholder="Auto?" class="nopads no-back-black grandcost" readonly required>
						</td>
						<td class="nunito-semibold right-pull-10 left-pull-10 alignrt">
							Total Cost:
						</td>
						<td class="nunito-semibold right-pull-10 left-pull-10">
							<input type="text" name="grandtotal" id="grandtotal" placeholder="Auto?" class="nopads no-back-black grandtotal" readonly required>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="top-push-50 motion">
			<input type="hidden" name="datau" id="datau" value="0">
			<div id="sbtn" class="xfadein motion-x">
				<input type="submit" id="submitbutton" name="submitbutton" value="Confirm & Save" class="submit blue-white-state nunito-semibold rounded-button anchor letter-spacing-2 nodefault-appearance">
			</div>
		</div>

	</form>

</div>

<script>

	const newNumbr = {'row':1,'idLabel':20};

	function wgets(id,ls,rs) {

		var datalist = document.getElementById(ls);
		
		if(datalist.value == null || datalist.value == '') { 
			
			sqldatastring.sql = "SELECT * FROM stock_category_tbl WHERE deletedata=0";
			sqldataQuery(wgtparam,sqldatastring);

			function wgtparam(response) {
				var data = JSON.parse(response);
				var i, lshtml, dl = data.datastring;
				
				lshtml = '<option value="" selected>Choose?</option>';

				for(i=0; i<dl.length; i++) {
					lshtml += '<option value="'+dl[i].id+'">'+dl[i].category+'</option>';
				}

				writeObjheader(ls,lshtml);
			}
		}
	}


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


	function wgets2(id,ls,rs) {

		var datalist = document.getElementById(ls);

		if(id == 1) {
			
			var selectbyid = datalist.value;

			if(selectbyid && selectbyid > 0) {

				sqldatastring.sql = "SELECT * FROM stock_subcategory_tbl WHERE categoryid="+selectbyid+" AND deletedata=0";
				sqldataQuery(wgtparam,sqldatastring);

				function wgtparam(response) {
					var data = JSON.parse(response);
					var i, lshtml, dl = data.datastring;
					
					lshtml = '<option value="" selected>Choose?</option>';

					for(i=0; i<dl.length; i++) {
						lshtml += '<option value="'+dl[i].id+'">'+dl[i].subcategory+'</option>';
					}

					writeObjheader(rs,lshtml);
				}
			}

		}
	}

	function wgets3(id,ls,rs) {

		var datalist = document.getElementById(ls);

		if(id == 2) {
			
			var selectbyid = datalist.value;

			if(selectbyid && selectbyid > 0) {
				var selectbyid2 = document.getElementById(rs).value;
				var itembox = "item"+newNumbr.row;

				sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE categoryid="+selectbyid2+" AND subcategoryid="+selectbyid+" AND deletedata=0";
				sqldataQuery(wgtparam,sqldatastring);

				function wgtparam(response) {
					var data = JSON.parse(response);
					var i, lshtml, dl = data.datastring;
					
					lshtml = '<option value="" selected>Choose?</option>';

					for(i=0; i<dl.length; i++) {
						lshtml += '<option value="'+dl[i].id+'">'+dl[i].item+'</option>';
					}

					writeObjheader(itembox,lshtml);
				}
			}

		}
	}

	function wgets4(id,ls,rs) {

		//var datalist = document.getElementById(ls);

		if(document.getElementById(ls).value == '' || document.getElementById(ls).value == null) {
			
			sqldatastring.sql = "SELECT * FROM supplier_tbl WHERE deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].supplier_name+'</option>';
				}

				writeObjheader(ls,vhtml);
			}
		}
	}

	function wgets5(id,ls,rs) {

		var datalist = document.getElementById(ls);

		if(id == 4) {
			
			var selectbyid = datalist.value;
			var itembox = "unitcost"+newNumbr.row;

			if(selectbyid && selectbyid > 0) {
				
				sqldatastring.sql = "SELECT * FROM item_cost_centre_tbl WHERE itemid="+selectbyid+" AND deletedata=0 ORDER BY id DESC LIMIT 1"; sqldataQuery(wgtparam,sqldatastring);

				function wgtparam(response) {
					var data = JSON.parse(response);
					var i, lshtml = '', dl = data.datastring;
					
					/*for(i=0; i<dl.length; i++) {
						lshtml += '<option value="'+dl[i].costprice+'">';
					}*/

					htmlpassval(dl[0].costprice,itembox);
					document.getElementById('quantity'+newNumbr.row).focus();
				}
			}
		}
	}

	
	function wgti(id) {

		if(document.getElementById(id).value == '' || document.getElementById(id).value == null) {
			
			sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE status='Active' AND deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<option value="" selected="selected"></option>';

				for(i=0; i<data.length; i++) {
					vhtml += '<option value="'+data[i].id+'">'+data[i].item+'</option>';
				}

				writeObjheader(id,vhtml);
				
			}
		}
	}


	function xgetitem(tag) {

		if(tag.value !== null && tag.value != '') {
			
			var sstring = tag.value;
			var boxid = tag.id, sshow = boxid.replace('-tbox','-dlist'), dbx = boxid.replace('-tbox','');

			//sqldatastring.sql = "SELECT t1.id,t1.item,t2.costprice FROM stock_item_tbl t1, item_cost_centre_tbl t2 WHERE t1.item REGEXP '^"+sstring+"' AND t1.status='Active' AND t1.deletedata=0 AND t1.id=t2.itemid";

			sqldatastring.sql = "SELECT * FROM stock_item_tbl WHERE item REGEXP '"+sstring+"' AND status='Active' AND deletedata=0";
			sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var i, vhtml, data, ajaxresult = JSON.parse(response);
				data = ajaxresult.datastring;

				vhtml = '<ul class="nolist">';

				for(i=0; i<data.length; i++) {
					vhtml += '<li class="top-pull-5 bottom-pull-5 box-border-thick-bottom">';
					vhtml += '<span class="float-right"><input type="button" value="+" class="top-pull-3 right-pull-7 bottom-pull-3 left-pull-7 rounded-button anchor" name="'+data[i].item+'/?'+data[i].id+'" lang="'+dbx+'" onclick="xpk(this)"></span>';
					vhtml += '<h3 class="large nobold">'+data[i].item+' - in '+js_uoms[Number(data[i].buying_unit) - 1]+'</h3>';
					vhtml += '</li>';
				}

				vhtml += '</ul>';

				writeObjheader(sshow,vhtml);
			}
		}
	}


	function nextTp(row) {

		var tr, j, r = row - 1;
		
		var uc = document.getElementsByClassName('unitcost');
		var tc = document.getElementsByClassName('totalcost');
		var qy = document.getElementsByClassName('quantity');

		for(j=0; j < uc.length; j++) {
			if(j == r) {
				var unitcost = uc[j].id, totalcost = tc[j].id, quantity = qy[j].id;
				var unitcostval, qtyval, totalcostval;

				totalcostval = document.getElementById(totalcost);

				if(document.getElementById(unitcost).value > 0) {
					unitcostval = document.getElementById(unitcost).value;
					qtyval = document.getElementById(quantity).value;
				} else {
					unitcostval = 0;
					qtyval = 0;
				}

				var endval = eval(unitcostval) * eval(qtyval);
				endval = todecimal(endval,2);

				totalcostval.value = endval;
				
				break;
			}
		}

		setTimeout(() => {
			var gc = document.getElementById('grandcost');
			var gt = document.getElementById('grandtotal');
		
			var grandcost = 0, grandtotal = 0;

			for(tr=0; tr < uc.length; tr++) {
				if(document.getElementById(uc[tr].id).value !='') {
					grandcost = grandcost + eval(document.getElementById(uc[tr].id).value); 
				} console.log('uc:'+document.getElementById(uc[tr].id).value);
				
				if(document.getElementById(tc[tr].id).value != '') {
					grandtotal = grandtotal + eval(document.getElementById(tc[tr].id).value);
				} console.log('tc:'+document.getElementById(tc[tr].id).value);
			}

			gc.value = numberFormat(grandcost);
			gt.value = numberFormat(grandtotal);
		},1000);
		
	}

	function nextRow(field,e) {
		
		if(e.KeyCode == 13 || e.which == 13) {
			
			e.preventDefault();

			var aElem = document.getElementById(field);
			aElem.blur();

			chgclass('sbtn','fx-width-40 fx-position-rel margin-auto-ct xfadeout motion-x');

			var vhtml, nrow, nlabel, contr = document.getElementById('table-form');
			nrow = newNumbr.row + 1; nlabel = newNumbr.idLabel + 1;

			//calculate totalprice, totalprofit for previous record
			nextTp(newNumbr.row);

			//<select name="item[]" id="item'+nrow+'" class="nopads no-back-black" onclick="wgti(this.id)" onchange="wgets5(4,this.id,0)"><option value="">Choose?</option></select>

			vhtml = '';
			vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200"><select name="supplier[]" id="supplier'+nrow+'" class="nopads no-back-black" onclick="wgets4(3,this.id,0)"></select></td>';
			
			vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10 cs-width-200">';
			vhtml += '<select name="item[]" id="item'+nrow+'" class="noshow nopads no-back-black"><option value=""></option></select>';
			vhtml += '<a href="javascript:void(0)" class="blue-font" onclick="ossr(this.lang,1)" lang="item'+nrow+'-sbox"><b class="fas fa-plus-circle nobold"></b> Add item</a>';
			vhtml += '<div id="item'+nrow+'-sbox" class="noshow cs-width-250 fx-position-flow zind-2 white-theme">';
			vhtml += '<div class="grey-theme pads15"><input type="text" id="item'+nrow+'-tbox" class="nopads no-back-black" placeholder="Type to search.." onkeyup="xgetitem(this)"></div>';
			vhtml += '<div id="item'+nrow+'-dlist" class="cs-height-200 pads15 y-scroll"></div>';
			vhtml += '<p class="top-pull-15 bottom-pull-15 alignct"><input type="button" value="Cancel" class="rounded-button anchor" onclick="ossr(this.lang,0)" lang="item'+nrow+'-sbox"></p>';
			vhtml += '</div>';
			vhtml += '</td>';

			vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input list="thecostprice'+nrow+'" name="unitcost[]" id="unitcost'+nrow+'" placeholder="Type to choose?" class="nopads no-back-black unitcost" oninput="nextTp('+nrow+')"  readonly required><datalist id="thecostprice'+nrow+'"></datalist></td>';
			vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input type="number" min="1" step=".01" name="quantity[]" id="quantity'+nrow+'" placeholder="Enter quantity?" value="1" class="nopads no-back-black quantity" onkeypress="nextRow(this.id,event)" onblur="lastRow(this.id)" required></td>';
			vhtml += '<td class="nunito-semibold right-pull-10 left-pull-10"><input type="text" name="totalcost[]" id="totalcost'+nrow+'" placeholder="Auto?" class="nopads no-back-black totalcost" readonly required></td>';
			
			newNumbr.row = nrow;
			newNumbr.idLabel = nlabel;

			var tr = document.createElement('tr');
			contr.appendChild(tr);

			setTimeout(() => {
				tr.innerHTML = vhtml;
				//wgets3(2,'subcategory','category');
				document.getElementById('supplier'+nrow).click();
			},500);
		}
	}


	function lastRow(field) {
		
		chgclass('sbtn','fx-width-40 fx-position-rel margin-auto-ct xfadeout motion-x');
		
		var aElem = document.getElementById(field);
		
		if(aElem.value != '') {
			
			var vhtml, nrow, nlabel;
			nrow = newNumbr.row + 1; nlabel = newNumbr.idLabel + 1;

			//calculate totalprice, totalprofit for previous record
			nextTp(newNumbr.row);
		}
	}


	function ossr(tag,nf) {
		var dbx = tag.replace('-sbox','');
		if(nf == 1) {
			chgclass(tag,'cs-width-350 fx-position-flow zind-2 white-theme obj-light-shadow');
			chgclass(dbx,'noshow nopads no-back-black');
		} else if(nf == 0) {
			chgclass(tag,'noshow cs-width-250 fx-position-flow zind-2 white-theme');
			if(document.getElementById(dbx).value != '') { chgclass(dbx,'nopads no-back-black'); }
			else { chgclass(dbx,'noshow nopads no-back-black'); }
		}
	}


	function xpk(tag) {
		var dbx = tag.lang;
		var val = tag.name;

		var xhtml, get_txt_id = val.split('/?');
		xhtml = '<option value="'+get_txt_id[1]+'">'+get_txt_id[0]+'</option>';

		writeObjheader(dbx,xhtml);
		chgclass(dbx,'nopads no-back-black');
		chgclass(dbx+'-sbox','noshow cs-width-250 fx-position-flow zind-2 white-theme');

		setTimeout(function() {

			sqldatastring.sql = "SELECT * FROM item_cost_centre_tbl WHERE itemid="+get_txt_id[1]+" AND deletedata=0 ORDER BY id DESC LIMIT 1"; sqldataQuery(wgtpop,sqldatastring);

			function wgtpop(response) {
				var data = JSON.parse(response);
				var dl = data.datastring;
				var fld = dbx.replace('item','unitcost');
				console.log(fld);
				htmlpassval(dl[0].costprice,fld);
			}

		},1000);
	}

</script>