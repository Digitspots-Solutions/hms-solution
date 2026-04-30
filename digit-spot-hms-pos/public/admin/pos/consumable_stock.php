<?php $smdl = "pos"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here are the list of consumables stock. For established stocks, use <u>start-up stock</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<?php 
			/*if(isset($allowStartupStock) && $allowStartupStock == 200) {
				?>
					<a href="javascript:void(0)" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 sml-rounded-button blue-theme white-font" onclick="winpop('start-up',1)">Start-up Stock</a>
				<?php
			}*/

			if(isset($allowRemoveStock) && $allowRemoveStock == 200) {
				?>
					<a href="javascript:void(0)" class="top-pull-10 right-pull-30 bottom-pull-10 left-pull-30 rounded-button blue-white-state" onclick="document.getElementById('deletebutton').click()">Remove Stock</a>
				<?php
			}
		?>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$cur_pos_store_id = $_SESSION['postoreid'];
	$storageid = idget_data($tbL14,$cur_pos_store_id,'store');

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['startupstockbutton'])) {

		$storagetype = $_POST['storagetype'];
		$category = $_POST['category']; $subcategory = $_POST['subcategory'];
		$product = $_POST['product']; $qty = $_POST['qty'];
		$cost = $_POST['cost']; $price = $_POST['price'];

		$ispassed = 0;

		for($i=0; $i < count($product); $i++) {
			if(!empty($product[$i]) && $product[$i] > 0) {
				
				$item_name = idget_data($tbL118,$product[$i],'item');
				$selling_unit = idget_data($tbL118,$product[$i],'selling_unit');

				$constrain = array("postoreid"=>$cur_pos_store_id,"itemcode"=>$product[$i],"storagetype"=>$storagetype[$i]);
				$product_arr = array("storageid"=>$storageid,"storagetype"=>$storagetype[$i],"postoreid"=>$cur_pos_store_id,"categoryid"=>$category[$i],"subcategoryid"=>$subcategory[$i],"itemcode"=>$product[$i],"item"=>$item_name,"stockin"=>$qty[$i],"uom"=>$selling_unit,"cost"=>$cost[$i],"price"=>$price[$i],"balance"=>$qty[$i],"isfeature"=>"No","isstaff"=>"No");
				
				$data_inserted = mysqli_data_insert($tbL16,$product_arr,$constrain);
				if($data_inserted == 2) { $ispassed += 1; }

				$item_name = ""; $selling_unit = "";
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($ispassed) && $ispassed > 0) {

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently created start-up stock for pos","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Start-up stock was created successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['pricebutton']) && isset($_POST['rowid']) && $_POST['rowid'] > 0) {
		
		$cost = isset($_POST['cost']) ? $_POST['cost'] : 0;
		$price = isset($_POST['price']) ? $_POST['price'] : 0;
		$isfeature = isset($_POST['isfeature']) ? $_POST['isfeature'] : "No";

		$result = 0;

		if($cost > 0 && $cost < $price) {
			$pst_query = array("id"=>escape_data($_POST['rowid']));
			$pst_field = array("price"=>$_POST['price'],"isfeature"=>$isfeature);
			$result = mysqli_data_update($tbL16,$pst_field,$pst_query);
		} else {
			$result = 0;
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($result) && $result == 2) {
			$post_result .= '<span class="light-red-font">Item price is updated successfully</span>';
		
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently updated item price","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
		} else {
			$post_result .= '<span class="light-red-font">Unable to complete price update. Check for zero or negative value</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editsubutton']) && isset($_POST['xrowid']) && $_POST['xrowid'] > 0) {
		
		$category = escape_data($_POST['ctg']);
		$subcategoryid = escape_data($_POST['subctg']);
		$subcategory = idget_data($tbL116,$subcategoryid,'subcategory');
		$rowid = escape_data($_POST['xrowid']);
		
		$result = 0; $processed = 0;

		$subctg_query = array("postoreid"=>$cur_pos_store_id,"subcategory"=>$subcategory);
		$subctg_data = mysqli_data_checkr($tbL92,'(*)',$subctg_query);
			
		if($subctg_data == true) {
			$new_subcategoryid = mysqli_data_fetch($tbL92,'id',$subctg_query,'noarray');
			$pst_query = array("id"=>$rowid);
			$pst_field = array("subcategoryid"=>$new_subcategoryid[0]);
			$result = mysqli_data_update($tbL16,$pst_field,$pst_query);
			$processed = 1;
		} else {
			$pst_query = array("postoreid"=>$cur_pos_store_id,"subcategory"=>$subcategory);
			$pst_field = array("postoreid"=>$cur_pos_store_id,"categoryid"=>$category,"subcategory"=>ucwords($subcategory),"detail"=>"for product sales");
			$result = mysqli_data_insert($tbL92,$pst_field,$pst_query);
			$result_id = $mysqli_id;
			$processed = 1;

			if($result == 2) {
				$pst_query = array("id"=>$rowid);
				$pst_field = array("subcategoryid"=>$result_id);
				mysqli_data_update($tbL16,$pst_field,$pst_query);
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($processed) && $processed == 1) {
			$post_result .= '<span class="light-red-font">Sub-category is changed successfully</span>';
		
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently changed item sub-category from consumable stock","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
		} else {
			$post_result .= '<span class="light-red-font">Unable to complete request. Try again</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		//$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = trash_record($tbL16,$usr_key);
			//$del = mysqli_data_update($tbL16,$usr_datasets,$usr_key);

			if(isset($del) && $del == 2) {
				$data_deleted += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_deleted) && $data_deleted == 0)
		{
			$post_result .= '<span class="red-font">Unable to remove data. Try again</span>';
		}
		elseif(isset($data_deleted) && $data_deleted >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently removed item from consumable stock","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (item LIKE '".escape_data($_POST['search'])."%' OR itemcode LIKE '".escape_data($_POST['search'])."%') ORDER BY item ASC";
	} else { 
		$keywords=" ORDER BY item ASC";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 100;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	
	$dataproperty = "id,categoryid,subcategoryid,itemcode,item,uom,cost,price,stockout,balance,isfeature,isstaff,status";
	$constrain = array("deletedata"=>0,"postoreid"=>$cur_pos_store_id,"storagetype"=>"consumable");
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL16,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$consumable_outlet = idget_data($tbL14,$cur_pos_store_id,'posname');

		//$thproperty = array("noth","category","subcategory","product","price (Per 1)","stock out","avail stock","noth");
		$thproperty = array("enoth","noth","product","cost (Per 1)","price (Per 1)","stock out","avail stock","noth");
		$csvheader = array("SN","Product","Cost (Per 1)","Price (Per 1)","Sold","Balance");
		$data_library = array(); $data_row = array();
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";

		$xhtml = '';
		$xhtml .= '<h1 class="large nobold alignct">'._LONG_NAME.'</h1><br>';
		$xhtml .= '<h3 class="large nobold alignct nomargin">Available Stock Summary</h3>';
		$xhtml .= '<h3 class="large nobold alignct">('.$consumable_outlet.')</h3>';
		$xhtml .= '<table cellpadding="3" cellspacing="0">';
		$xhtml .= '<tr>';
		$xhtml .= '<td class="default-text-font-bold">&nbsp;</td>';
		$xhtml .= '<td class="default-text-font-bold">Item</td>';
		$xhtml .= '<td class="default-text-font-bold">Stock Balance</td>';
		$xhtml .= '<td class="default-text-font-bold">Cost Price</td>';
		$xhtml .= '<td class="default-text-font-bold">Sales Price</td>';
		$xhtml .= '<td class="default-text-font-bold">Total Cost</td>';
		$xhtml .= '</tr>';
		
		$htmlresult .= '<form id="stockform" action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<span class="noshow"><input type="submit" name="deletebutton" id="deletebutton" value="delete"></span>';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-60">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left left-pull-20 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-right top-pull-10 alignrt">';
		$htmlresult .= '<a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow left-push-15 right-push-10" onclick="printh()">Print <b class="fa-print left-push-5"></b></a><a href="javascript:void(0)" class="white-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button forest-green-theme obj-light-shadow left-push-10" onclick="csvExcel()" title="Export to Excel">Export <b class="fa-share left-push-5"></b></a>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$htmlresult .= '</tr>';
		
		$num=$pgstart; $g=""; $dataid=""; $category=""; $subcategory="";
		$applied_uom=""; $allow_edit=""; $disabled=""; $total_cost_price = 0; $total_sales_price = 0;

		$totalamount = 0; $total_grand_amount = 0;

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$additionalQuery = "";
			$category = idget_data($tbL15,$tdata['categoryid'],'category');
			$subcategory = idget_data($tbL92,$tdata['subcategoryid'],'subcategory');
			$applied_uom = get_uom($tdata['uom']);

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

			$total_cost_price = $total_cost_price + $tdata['cost'];
			$total_sales_price = $total_sales_price + $tdata['price'];

					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="30px" class="box-border-thick-right" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$category.'</td>';
			//$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$subcategory.' &nbsp; <a href="javascript://" class="blue-font ft-xsml-size" name="'.$category.'//'.$subcategory.'//'.$dataid.'//'.$tdata['categoryid'].'//'.$tdata['subcategoryid'].'" onclick="editSubctg(this.name)">Edit</a></td>';
			$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">'.$tdata["item"].'</td>';
			$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">&#8358; '.number_format($tdata['cost'],2).'</td>';
			$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">&#8358; '.number_format($tdata['price'],2).'</td>';
			$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$tdata['stockout'].' '.$applied_uom.'</td>';
			$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$tdata['balance'].' '.$applied_uom.'</td>';
			if(isset($allowPriceUpdate) && $allowPriceUpdate == 200) { $htmlresult .= '<td width="150px" align="center" class="box-border-thick-right"><a href="javascript://" class="blue-font" name="'.$tdata["item"].'//'.$tdata["cost"].'//'.$dataid.'" onclick="updatePrice(this.name)">Update Price</a></td>'; } else { $htmlresult .= '<td width="150px" align="center" class="box-border-thick-right" title="Price setup not applicable">?</td>'; }
			$htmlresult .= '</tr>';

			$totalamount = $tdata["cost"] * $tdata["balance"];
			$total_grand_amount = $total_grand_amount + $totalamount;

			$xhtml .= '<tr>';
			$xhtml .= '<td>'.$num.'</td>';
			$xhtml .= '<td>'.$tdata["item"].'</td>';
			$xhtml .= '<td>'.$tdata["balance"].' '.$applied_uom.'</td>';
			$xhtml .= '<td>&#8358; '.number_format($tdata["cost"],2).'</td>';
			$xhtml .= '<td>&#8358; '.number_format($tdata["price"],2).'</td>';
			$xhtml .= '<td>&#8358; '.number_format($totalamount,2).'</td>';
			$xhtml .= '</tr>';

			$data_row['sn'] = $num;
			$data_row['product'] = $tdata["item"];
			$data_row['cost'] = number_format($tdata['cost'],2);
			$data_row['price'] = number_format($tdata['price'],2);
			$data_row['sold'] = $tdata['stockout'].' '.$applied_uom;
			$data_row['balance'] = $tdata['balance'].' '.$applied_uom;

			array_push($data_library, $data_row);
		}

		$htmlresult .= '<tr bgcolor="#c1c1c1">';
		$htmlresult .= '<td colspan="2" class="default-text-font-bold box-border-thick-right" align="center">Total</td>';
		$htmlresult .= '<td width="180px" align="center" class="default-text-font-bold box-border-thick-right">&#8358; '.number_format($total_cost_price,2).'</td>';
		$htmlresult .= '<td width="180px" align="center" class="default-text-font-bold box-border-thick-right">&#8358; '.number_format($total_sales_price,2).'</td>';
		$htmlresult .= '<td colspan="4">&nbsp;</td>';
		$htmlresult .= '</tr>';
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';



		$xhtml .= '<tr>';
		$xhtml .= '<td>&nbsp;</td>';
		$xhtml .= '<td>&nbsp;</td>';
		$xhtml .= '<td>&nbsp;</td>';
		$xhtml .= '<td class="default-text-font-bold">&#8358; '.number_format($total_cost_price,2).'</td>';
		$xhtml .= '<td class="default-text-font-bold">&#8358; '.number_format($total_sales_price,2).'</td>';
		$xhtml .= '<td class="default-text-font-bold">&#8358; '.number_format($total_grand_amount,2).'</td>';
		$xhtml .= '</tr>';

		$xhtml .= '</table>';

		if(isset($_SESSION['csvExcel']) || isset($_SESSION['csvHeaders'])) {
			unset($_SESSION['csvExcel']);
			unset($_SESSION['csvHeaders']);
		}

		$_SESSION['csvExcel'] = $data_library;
		$_SESSION['csvHeaders'] = $csvheader;
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct dark-grey-font">There are no records at the moment!</div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	mysqli_data_check($tbL16,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(100,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>


<div id="start-up" class="noshow fx-position-rel zind-2 pads30 motion" align="center">
	<div class="nc-width-100 white-theme pads20 top-push-50 alignlt">
		<div class="alignrt bottom-push-20" align="right">
			<span class="float-left ft-sml-size">
				Click on <u>Add</u> button to add-up the items for your stock
			</span>
			<a href="javascript:void(0)" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 sea-green-theme white-font anchor rounded-button ft-xsml-size right-push-10" onclick="create_product(this); dodata(this); objDisplay('ctrlbx')">Add +</a> <a href="?logs=<?php echo $logs; ?>" class="blue-font ft-sml-size">Cancel</a>
		</div>

		<div class="pads15 x-scroll">
			<div class="cs-width-1200">
				<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<th width="50px" class="box-border-thick-right" align="center">&nbsp;</th>
							<th width="70px" class="box-border-thick-right" align="center">Storage Type</th>
							<th width="150px" class="box-border-thick-right" align="center">Item</th>
							<th width="100px" class="box-border-thick-right" align="center">Category</th>
							<th width="100px" class="box-border-thick-right" align="center">SubCategory</th>
							<th width="100px" class="box-border-thick-right" align="center">Qty</th>
							<th width="100px" class="box-border-thick-right" align="center">Cost Price</th>
							<th width="100px" class="box-border-thick-right" align="center">Selling Price</th>
						</tr>
						<tbody id="datasheet"></tbody>
					</table>

					<div id="ctrlbx" class="noshow top-push-30 alignct">
						<input type="submit" name="startupstockbutton" value="Save Stock" class="submit top-pull-10 bottom-pull-10 blue-white-state rounded-button nc-width-20">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="section-to-print" class="noshow motion">
	<?php echo $xhtml; ?>
</div>

<div id="pricebox" class="noshow fx-position-rel zind-2 motion" align="center">
	<div class="cs-width-400 white-theme pads20 top-push-50 sml-rounded-button alignlt box-border-thick obj-shadow">
		<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
			<h3 class="large nobold nomargin">Price Update for</h3>
			<h2 id="item-label" class="large nobold default-text-font-bold"></h2>
			<input type="hidden" name="rowid" id="rowid" value="0" required>
			<input type="number" min="1" name="price" id="price" placeholder="0.00" required>
			<input type="hidden" name="cost" id="cost" required>
			<p class="top-pull-10 ft-sml-size"><input type="checkbox" name="isfeature" value="Yes"> &nbsp; Allow Feature Product</p>
			<p class="top-pull-20 alignct">
				<input type="submit" name="pricebutton" value=" Apply " class="pads10 blue-white-state rounded-button nc-width-30 default-text-font-bold"> <a href="javascript://" class="blue-font left-push-20 ft-sml-size" onclick="winpop('pricebox',0);">Cancel x</a>
			</p>
		</form>
	</div>
</div>

<div id="subbox" class="noshow fx-position-rel zind-2 motion" align="center">
	<div class="cs-width-400 white-theme pads20 top-push-50 sml-rounded-button alignlt box-border-thick obj-shadow">
		<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
			<h2 id="subctg-label" class="large nobold default-text-font-bold"></h2><br>
			<input type="hidden" name="xrowid" id="xrowid" value="0" required>
			<select name="ctg" id="ctg" required></select><p></p>
			<select name="subctg" id="subctg" required></select>
			<p class="top-pull-20 alignct">
				<input type="submit" name="editsubutton" value=" Apply " class="pads10 blue-white-state rounded-button nc-width-30 default-text-font-bold"> <a href="javascript://" class="blue-font left-push-20 ft-sml-size" onclick="winpop('subbox',0);">Cancel x</a>
			</p>
		</form>
	</div>
</div>


<script>

	function csvExcel() {
		var curl = filePath;
		window.location = curl+'includes/csv_excel.php';
	}

	function updatePrice(record) {
		var r = record.split('//');
		htmlpassval(r[2],'rowid');
		htmlpassval(r[1],'cost');
		writeObjheader('item-label',r[0]);
		winpop('pricebox',1);
	}


	function editSubctg(record) {
		var r = record.split('//');
		htmlpassval(r[2],'xrowid');
		writeObjheader('ctg','<option value="'+r[3]+'">'+r[0]+'</option>');
		writeObjheader('subctg-label','Editing '+r[1]);

		sqldatastring.sql = "SELECT * FROM stock_subcategory_tbl WHERE categoryid=2 AND status IN('Active') AND deletedata=0";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			vhtml = '<option value="" selected>Choose?</option>';

			for(i=0; i<data.length; i++) {
				vhtml += '<option value="'+data[i].id+'">'+data[i].subcategory+'</option>';
			}

			writeObjheader('subctg',vhtml);
		}

		winpop('subbox',1);
	}


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
		td5 = document.createElement('td');
		td6 = document.createElement('td');
		td7 = document.createElement('td');
		td8 = document.createElement('td');
		td9 = document.createElement('td');

		select1 = document.createElement('select');
		select2 = document.createElement('select');
		select3 = document.createElement('select');
		select4 = document.createElement('select');
		select5 = document.createElement('select');
		
		opt1 = document.createElement('option');
		opt2 = document.createElement('option');
		
		opt3 = document.createElement('option');
		opt4 = document.createElement('option');

		opt5 = document.createElement('option');
		opt6 = document.createElement('option');
		
		opt7 = document.createElement('option');
		opt8 = document.createElement('option');

		opt9 = document.createElement('option');
		opt10 = document.createElement('option');
		
		txt1 = document.createElement('input');
		txt2 = document.createElement('input');
		txt3 = document.createElement('input');
		txt4 = document.createElement('input');
		txt5 = document.createElement('input');

		var numbr = numbering.row;

		span = document.createElement('span');
		span.className = 'block-element alignct';
		span.innerHTML = numbr;
		td1.appendChild(span);

		select1.name = 'storagetype[]';
		//select1.required = 'required';
		opt1.value = 'consumable';
		opt1.text = 'Consumable';
		opt2.value = 'serviceable';
		opt2.text = 'Serviceable';
		
		select1.appendChild(opt1);
		select1.appendChild(opt2);
		td2.appendChild(select1);
		td2.className = 'cs-width-150';

		select2.id = 'select-col-3-'+numbr;
		select2.name = 'category[]';
		//select2.required = 'required';
		opt3.value = '';
		opt3.text = 'Auto?';
		select2.appendChild(opt3);
		td3.appendChild(select2);
		
		select3.id = 'select-col-4-'+numbr;
		select3.name = 'subcategory[]';
		//select3.required = 'required';
		opt4.value = '';
		opt4.text = 'Auto?';
		select3.appendChild(opt4);
		td4.appendChild(select3);

		select4.id = 'select-col-5-'+numbr;
		select4.name = 'product[]';
		//select4.required = 'required';
		opt5.value = '';
		opt5.text = 'Choose';
		select4.appendChild(opt5);
		td5.appendChild(select4);
		select4.onchange = function() {
			getdata('select-col-3-'+numbr,'eeget-category-list','select-col-5-'+numbr,'dropbox');
			getdata('select-col-4-'+numbr,'eeget-sub-category-list','select-col-5-'+numbr,'dropbox');
			getdata('input-col-8-'+numbr,'eeget-item-cost-list','select-col-5-'+numbr,'inputs');
		}

		txt1.type = 'number';
		txt1.name = 'qty[]';
		txt1.min = 0;
		txt1.step = '0.01';
		txt1.placeholder = 'Enter here';
		//txt1.required = 'required';
		td6.appendChild(txt1);

		select5.id = 'select-col-6-'+numbr;
		select5.name = 'uom[]';
		//select5.required = 'required';
		opt6.value = '';
		opt6.text = 'Choose';
		select5.appendChild(opt6);
		td7.appendChild(select5);

		txt2.type = 'text';
		txt2.id = 'input-col-8-'+numbr;
		txt2.name = 'cost[]';
		txt2.placeholder = 'Auto?';
		txt2.setAttribute('readonly','readonly');
		td8.appendChild(txt2);
		
		txt3.type = 'number';
		txt3.name = 'price[]';
		txt3.min = 0;
		txt3.step = '0.01';
		td9.appendChild(txt3);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td5);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td6);
		//tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);

		contr.appendChild(tr);
		numbering.row = eval(numbering.row) + 1;
	}


	function dodata(obj) {
		var numbr;
		//var select_id = str+curnumbr;
		//getdata(select_id,sses,id,sopt);

		setTimeout(() => {
			numbr = eval(numbering.row) - 1;
			//getdata('select-col-3-'+numbr,'eget-pos-category-list','select-col-3-'+numbr,'dropbox');
			getdata('select-col-5-'+numbr,'eget-product-list','select-col-5-'+numbr,'dropbox');
			obj.className = 'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 sea-green-theme white-font anchor rounded-button ft-xsml-size right-push-10';
		},2000);
	}


	function printh() {
		chgclass('section-to-print','motion');
		setTimeout(() => { window.print(); },1000);
		setTimeout(() => { chgclass('section-to-print','noshow motion'); },3000);
	}

</script>