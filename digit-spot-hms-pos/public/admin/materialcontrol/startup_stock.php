<div class="pads30">
	<div class="alignrt bottom-push-20" align="right">
		<span class="float-left ft-sml-size">
			Click on <u>Add</u> button to create your current stock
		</span>
		<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 sea-green-theme white-font anchor rounded-button ft-xsml-size right-push-10" onclick="create_product(this); dodata(this)">Add +</a>
	</div>

	<div class="pads15 x-scroll">
		<div class="nc-width-100">
			<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="cs-width-50" align="center">&nbsp;</td>
						<td class="cs-width-250 default-text-font-bold" align="center">Supplier</td>
						<td class="default-text-font-bold" align="center">Item</td>
						<td class="cs-width-200 default-text-font-bold" align="center">Quantity</td>
					</tr>
					<tbody id="datasheet"></tbody>
				</table>

				<div id="ctrlbx" class="xfadein top-push-50 motion" align="center">
					<div class="fx-width-30">
						<input type="submit" name="startupstockbutton" value="Save Stock" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-100">
					</div>
				</div>
			</form>
		</div>
	</div>

	<?php
		
		if(isset($_POST['startupstockbutton'])) {

			$product = $_POST['product']; $qty = $_POST['qty'];
			$supplier = $_POST['supplier'];

			$ispassed = 0;

			for($i=0; $i < count($product); $i++) {
				if(!empty($product[$i]) && $product[$i] > 0) {
					
					$item_name = idget_data($tbL118,$product[$i],'item');
					$buying_unit = idget_data($tbL118,$product[$i],'selling_unit');
					$cost = idget_fdata('item_cost_centre_tbl','itemid',$product[$i],'costprice');
					$total_cost = $qty[$i] * $cost;

					$constrain = array("itemid"=>$product[$i]);
					$product_arr = array("itemid"=>$product[$i],"uom"=>$buying_unit,"supplierid"=>$supplier[$i],"unitprice"=>$cost,"stockin"=>$qty[$i],"balance"=>$qty[$i],"total_cost"=>$total_cost,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					
					$data_inserted = mysqli_data_insert($tbL156,$product_arr,$constrain);
					if($data_inserted == 2) { $ispassed += 1; }

					$item_name = ""; $buying_unit = ""; $cost = ""; $total_cost = "";
				}
			}

			
			if(isset($ispassed) && $ispassed > 0) {

				$saynotify = 1;
				$notifytype = 2;
				
				$post_header = "Notification";
				$post_message = "Stock received successfully";
				
				$islogfile = 1;
				$logfile_msg = "Start-up stock is created by this user";
			}
		}
	?>

</div>

<script>

	const numbering = {'row':1}

	function create_product(obj) {
	
		obj.className = 'noshow';

		var contr,tr,td1,td2,td3,td4,td5,td6,td7,td8,td9,td10,select1,select2,select3,select4,select5,opt1,opt2,opt3,opt4,opt5,opt6,opt7,opt8,opt9,txt1,txt2,txt3,txt4,txt5,obj,numbr,curnumbr;
		
		contr = document.getElementById('datasheet');

		tr = document.createElement('tr');
		td1 = document.createElement('td');
		td2 = document.createElement('td');
		td3 = document.createElement('td');
		td4 = document.createElement('td');
		
		select1 = document.createElement('select');
		select2 = document.createElement('select');
		
		opt1 = document.createElement('option');
		opt2 = document.createElement('option');
		
		opt3 = document.createElement('option');
		opt4 = document.createElement('option');

		txt1 = document.createElement('input');

		var numbr = numbering.row;

		span = document.createElement('span');
		span.className = 'block-element alignct';
		span.innerHTML = numbr+'.';
		td1.appendChild(span);

		select1.name = 'supplier[]';
		select1.id = 'select-col-1-'+numbr;
		opt1.value = '';
		opt1.text = 'Choose';
		select1.appendChild(opt1);
		select1.className = 'no-back-black';
		td2.appendChild(select1);
		
		select2.id = 'select-col-2-'+numbr;
		select2.name = 'product[]';
		opt2.value = '';
		opt2.text = 'Choose';
		select2.appendChild(opt2);
		select2.className = 'no-back-black';
		td3.appendChild(select2);
		
		txt1.type = 'number';
		txt1.name = 'qty[]';
		txt1.min = 0;
		txt1.step = '0.01';
		txt1.placeholder = 'Enter here';
		txt1.className = 'no-back-black';
		td4.appendChild(txt1);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		
		contr.appendChild(tr);
		numbering.row = eval(numbering.row) + 1;
	}


	function dodata(obj) {
		var numbr;
		setTimeout(() => {
			numbr = eval(numbering.row) - 1;
			getdata('select-col-1-'+numbr,'eget-supplier-list','select-col-1-'+numbr,'dropbox');
			getdata('select-col-2-'+numbr,'eget-product-list','select-col-2-'+numbr,'dropbox');
			obj.className = 'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 sea-green-theme white-font anchor rounded-button ft-xsml-size right-push-10';
			chgclass('ctrlbx','xfadeout top-push-30 motion');
		},2000);
	}

</script>