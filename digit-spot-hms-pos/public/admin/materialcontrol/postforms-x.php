<?php

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$params = array();
		$params['header'] = "Notification";
		
		$uri = isset($_POST['uri']) ? $_POST['uri'] : "";
		$ftask = isset($_POST['ftask']) ? $_POST['ftask'] : "";

		$pst_query = "";
		$pst_field = "";


		if($uri === 'adjust-stock') {
			
			createDatabasetable($var_tbl_159); //create a table

			$wgt_store = remove_data_injection($_POST['activestore']);
			$wgt_store_type = remove_data_injection($_POST['activestoretype']);

			if($wgt_store_type == 'Outlets') { $stockTbl = $tbL16; }
			elseif($wgt_store_type == 'Virtual Stores') { $stockTbl = $mtbL19; }

			$wgt_ids = $_POST['ids'];
			$wgt_itemids = $_POST['itemids'];
			$wgt_availqty = $_POST['availqty'];
			$wgt_availqtyin = $_POST['availqtyin'];
			$wgt_availqtyout = $_POST['availqtyout'];
			$wgt_adjustqty = $_POST['adjustqty'];
			$wgt_adjusttype = $_POST['adjusttype'];
			$wgt_adjustcal = $_POST['adjustcal'];
			$wgt_remarks = $_POST['remarks'];

			$a_fields = ""; $a_query = ""; $a2_fields = ""; $a2_query = "";
			$new_stockin = ""; $new_stockout = ""; $new_stockbal = "";

			$err = 0;
			
			for($i=0; $i < count($wgt_ids); $i++) {
				
				if(!empty($wgt_adjusttype[$i]) && !empty($wgt_adjustcal[$i]) && !empty($wgt_remarks[$i])) {
				
					if($wgt_adjustcal[$i] == 'decrement' && $wgt_adjustqty[$i] > $wgt_availqty[$i]) { $err = 1; }
					
					if($err == 0) {
						if($wgt_adjustcal[$i] == 'decrement') {
							$new_stockin =  $wgt_availqtyin[$i];
							$new_stockout =  $wgt_availqtyout[$i] + $wgt_adjustqty[$i];
							$new_stockbal =  $wgt_availqty[$i] - $wgt_adjustqty[$i];
						} elseif($wgt_adjustcal[$i] == 'increment') {
							$new_stockin =  $wgt_availqtyin[$i] + $wgt_adjustqty[$i];
							$new_stockout =  $wgt_availqtyout[$i];
							$new_stockbal =  $wgt_availqty[$i] + $wgt_adjustqty[$i];
						}

						$a_query .= "id={$wgt_ids[$i]};";

						if($wgt_store_type == 'Virtual Stores') {
							$a_fields .= "stockin='{$new_stockin}',stockout='{$new_stockout}',balance='{$new_stockbal}',delivery_date='{$server_get_date}',delivery_note='Last update with SA ({$wgt_adjustcal[$i]}) - {$wgt_adjustqty[$i]} Qty',userid={$userSignedIn};";
						} else {
							$a_fields .= "stockin='{$new_stockin}',stockout='{$new_stockout}',balance='{$new_stockbal}';";
						}

						$a2_query .= "";
						$a2_fields .= "store={$wgt_store},store_type='{$wgt_store_type}',stockid={$wgt_ids[$i]},itemid='{$wgt_itemids[$i]}',current_stock='{$wgt_availqty[$i]}',adjusted_stock='{$wgt_adjustqty[$i]}',new_stock='{$new_stockbal}',adjustment_type='{$wgt_adjusttype[$i]}',adjustment_process='{$wgt_adjustcal[$i]}',remarks='{$wgt_remarks[$i]}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
					}
				}
			}

			/*error_log($a_query,3,'w.txt');
			error_log($a_fields,3,'w.txt');
			error_log($a2_query,3,'w.txt');
			error_log($a2_fields,3,'w.txt');*/

			if((isset($a_query) && !empty($a_query)) && (isset($a_fields) && !empty($a_fields))) {

				$pst_query = $a_query;
				$pst_field = $a_fields;

				$params['body'] = "Stock adjustment was successful";
				$params['tbls'] = $stockTbl;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 2;
				
				mysqlpost('update',$params);
			}

			if(isset($a2_fields) && !empty($a2_fields)) {

				$pst_query = $a2_query;
				$pst_field = $a2_fields;

				$params['body'] = "";
				$params['tbls'] = $mtbL24;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 2;
				
				mysqlpost('insert',$params);
			}

		} elseif($uri === 'insert-item-category') {
			
			$wgt_items = $_POST['items'];
			
			$entry = "insert";

			foreach($wgt_items as $key) {
				if($key != '' && $key !== null) {
					$fpkey = remove_data_injection($key);
					$isfound = preg_match('/Food|Beverage|Others/i', $fpkey, $matches);
					
					if($isfound == 0) {
						$pst_query .= "category='{$fpkey}';";
						$pst_field .= "program_id=3,category='{$fpkey}',isdefault='No';";
					}

					$fpkey = ""; $isfound = "";
				}
			}
			
			$params['body'] = "Category is added successfully";
			$params['tbls'] = $mtbL2;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

		} elseif($uri === 'insert-item-sub-category') {
			
			$wgt_category = $_POST['category'];
			$wgt_name = $_POST['items'];

			$entry = "insert";

			foreach($wgt_name as $key) {
				if($key != '' && $key !== null) {
					$fpkey = remove_data_injection($key);
					$pst_query .= "categoryid='{$wgt_category}' AND subcategory='{$fpkey}';";
					$pst_field .= "categoryid='{$wgt_category}',subcategory='{$fpkey}';";

					$fpkey = "";
				}
			}
			
			$params['body'] = "Information is added successfully";
			$params['tbls'] = $mtbL3;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);
		
		} elseif($uri === 'insert-item-group') {
			
			$wgt_items = $_POST['items'];
			
			$entry = "insert";

			foreach($wgt_items as $key) {
				if($key != '' && $key !== null) {
					$fpkey = remove_data_injection($key);
					$pst_query .= "groupname='{$fpkey}';";
					$pst_field .= "groupname='{$fpkey}';";

					$fpkey = "";
				}
			}
			
			$params['body'] = "Group name is added successfully";
			$params['tbls'] = $mtbL4;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

		} elseif($uri === 'insert-supplier') {
			
			$wgtf1 = remove_data_injection($_POST['wgtf1']);
			$wgtf2 = remove_data_injection($_POST['wgtf2']);
			$wgtf3 = remove_data_injection($_POST['wgtf3']);
			$wgtf4 = remove_data_injection($_POST['wgtf4']);
			$wgtf5 = remove_data_injection($_POST['wgtf5']);
			$wgtf6 = remove_data_injection($_POST['wgtf6']);

			$datau = $_POST['datau'];

			if(isset($datau) && $datau > 0) {
				$entry = "update";
				$pst_query = "id={$datau}";
				$pst_field = "supplier_name='{$wgtf1}',mobile='{$wgtf2}',emailaddress='{$wgtf3}',city='{$wgtf4}',country='{$wgtf5}',sales_representative='{$wgtf6}'";
			} else {
				$entry = "insert";
				$pst_query = "supplier_name='{$wgtf1}'";
				$pst_field = "supplier_name='{$wgtf1}',mobile='{$wgtf2}',emailaddress='{$wgtf3}',city='{$wgtf4}',country='{$wgtf5}',sales_representative='{$wgtf6}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
			}
			
			$params['body'] = "Information is added successfully";
			$params['tbls'] = $mtbL1;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);
		
		} elseif($uri === 'edit-table-record') {
			
			$editedata = remove_data_injection($_POST['wgtcol']);
			$srccol = remove_data_injection($_POST['tablecolumn']);
			$srctbl = remove_data_injection($_POST['tablename']);
		
			$datau = $_POST['datau'];

			if(isset($datau) && $datau > 0) {
				$entry = "update";
				$pst_query = "id={$datau}";
				$pst_field = "$srccol='{$editedata}'";
			}
			
			$params['body'] = "Updated successfully";
			$params['tbls'] = $srctbl;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

		} elseif($uri === 'insert-store') {
			
			$wgtf1 = remove_data_injection($_POST['wgtf1']);
			$wgtf2 = remove_data_injection($_POST['wgtf2']);
			$wgtf3 = remove_data_injection($_POST['wgtf3']);
			
			$datau = $_POST['datau'];

			if(isset($datau) && $datau > 0) {
				$entry = "update";
				$pst_query = "id={$datau}";
				$pst_field = "store_name='{$wgtf1}',store_type='{$wgtf2}',department='{$wgtf3}'";
			} else {
				$entry = "insert";
				$store_name = str_replace('&amp;','/',$wgtf1);
				$pst_query = "store_name='{$store_name}'";
				$pst_field = "store_name='{$store_name}',store_type='{$wgtf2}',department='{$wgtf3}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
			}
			
			$params['body'] = "Information is added successfully";
			$params['tbls'] = $mtbL10;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			if($entry === 'insert') {
				
				$entry = "update";

				$id = $mysql_rowid;
				$store_number = "STORE".$id;
				
				$pst_query = "id={$id}";
				$pst_field = "store_number='{$store_number}'";

				$params['body'] = "";
				$params['tbls'] = $mtbL10;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);
			}

		} elseif($uri === 'insert-stock-item') {
			
			$wgtf1 = remove_data_injection($_POST['wgtf1']);
			$wgtf2 = remove_data_injection($_POST['wgtf2']);
			$wgtf3 = remove_data_injection($_POST['wgtf3']);
			$wgtf4 = remove_data_injection($_POST['wgtf4']);
			$wgtf5 = remove_data_injection($_POST['wgtf5']);
			$wgtf6 = remove_data_injection($_POST['wgtf6']);
			$wgtf7 = remove_data_injection($_POST['wgtf7']);
			$wgtf8 = remove_data_injection($_POST['wgtf8']);
			$wgtf8a = remove_data_injection($_POST['wgtf8a']);
			$wgtf9 = remove_data_injection($_POST['wgtf9']);
			$wgtf10 = remove_data_injection($_POST['wgtf10']);
			$wgtf11 = remove_data_injection($_POST['wgtf11']);
			$wgtf12 = remove_data_injection($_POST['wgtf12']);

			$indays = remove_data_injection($_POST['indays']);
			$indate = remove_data_injection($_POST['indate']);

			$err = 0; 

			if(isset($wgtf11) && $wgtf11 == "Yes") {
				if(isset($indays) && $indays >= 1) {
					$err = 0; $day = $indays." days"; $dateExpire = date("Y-m-d",strtotime($day));
				} elseif(isset($indate) && !empty($indate)) {
					$err = 0; $dateExpire = $_POST['indate'];
				} else {
					$err = 1; $dateExpire = "";
				}
			} else {
				$err = 0; $dateExpire = "";
			}

			$su = arrayget_key($uoms,$wgtf8);
			$bu = arrayget_key($uoms,$wgtf6);

			$qy_transfer = $wgtf8a * $wgtf7;
			$formula = "(SU * BU) : ".$wgtf8a." * ".$wgtf7." = ".$qy_transfer." ".$su." per ".$bu;

			$itemgroup = idget_name($wgtf2,'program_id',$tbL115);

			$datau = remove_data_injection($_POST['datau']);

			if(isset($datau) && $datau > 0) {
				
				$entry = "update";
				$pst_query = "id={$datau}";
				$pst_field = "item='{$wgtf1}',categoryid={$wgtf2},subcategoryid={$wgtf3},itemgroupid={$itemgroup},iscost_center='{$wgtf5}'";

				if(!empty($wgtf6) && $wgtf6 != '') { $pst_field .= ",buying_unit={$wgtf6}"; }
				if(!empty($wgtf7) && $wgtf7 != '') { $pst_field .= ",noofpiece_bu='{$wgtf7}'"; }
				if(!empty($wgtf8) && $wgtf8 != '') { $pst_field .= ",selling_unit={$wgtf8}"; }
				if(!empty($wgtf8a) && $wgtf8a != '') { $pst_field .= ",noofpiece_su='{$wgtf8a}'"; }
				if(!empty($wgtf9) && $wgtf9 != '') { $pst_field .= ",minimum_stock='{$wgtf9}'"; }
				if(!empty($wgtf10) && $wgtf10 != '') { $pst_field .= ",maximum_stock='{$wgtf10}'"; }
				if(!empty($wgtf11) && $wgtf11 != '') { $pst_field .= ",isexpire='{$wgtf11}'"; }
				if(!empty($dateExpire) && $dateExpire != '') { $pst_field .= ",expiry_date='{$dateExpire}'"; }

				//$pst_field = "item='{$wgtf1}',categoryid={$wgtf2},subcategoryid={$wgtf3},itemgroupid={$itemgroup},iscost_center='{$wgtf5}',buying_unit={$wgtf6},noofpiece_bu='{$wgtf7}',selling_unit={$wgtf8},noofpiece_su='{$wgtf8a}',calc_formular='{$formula}',minimum_stock='{$wgtf9}',maximum_stock='{$wgtf10}',isexpire='{$wgtf11}',expiry_date='{$dateExpire}'";

				$params['body'] = "Information is updated successfully";
				$params['tbls'] = $mtbL5;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

			} else {

				#avoid duplicate value submission
				$words = explode(' ', $wgtf1); $ws=""; foreach($words as $w) { $ws .= $w.'|'; }
				$wss = substr_replace($ws,'',-1,1);

				$findinset=""; $queryset = "SELECT item FROM {$mtbL5} WHERE deletedata=0 AND status IN('Active') AND item REGEXP '{$wss}'";
				$chkSql = mysqli_query($mysqli,$queryset); if(@mysqli_num_rows($chkSql) == true) { while($result=mysqli_fetch_array($chkSql,MYSQLI_ASSOC)) { $findinset .= $result['item'].','; } } $findin = substr_replace($findinset,',',-1,1);

				if(preg_match('/\b$wgtf1\b/', $findin)) { $err = 1; }
				elseif(strpos($findin,$wgtf1) !== false) { $err = 1; }
				elseif(stristr($findin,$wgtf1) !== false) { $err = 1; }
				else { $queryset = "SELECT item FROM {$mtbL5} WHERE deletedata=0 AND status IN('Active') AND item REGEXP '{$wgtf1}*'";
				$chkSql = mysqli_query($mysqli,$queryset); if(@mysqli_num_rows($chkSql) == true) { $err = 1; } }

			
				if($err == 0) {
					
					$entry = "insert";
					$pst_query = "item='{$wgtf1}'";
					$pst_field = "item='{$wgtf1}',categoryid={$wgtf2},subcategoryid={$wgtf3},itemgroupid={$itemgroup},iscost_center='{$wgtf5}',buying_unit={$wgtf6},noofpiece_bu='{$wgtf7}',selling_unit={$wgtf8},noofpiece_su='{$wgtf8a}',calc_formular='{$formula}',minimum_stock='{$wgtf9}',maximum_stock='{$wgtf10}',isexpire='{$wgtf11}',expiry_date='{$dateExpire}'";
					
					
					$params['body'] = "Information is added successfully";
					$params['tbls'] = $mtbL5;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost($entry,$params);

					if($entry === 'insert') {
						
						$id = $mysql_rowid;

						if(isset($id) && $id > 0) {
							
							$item_number = "ITM".$id;
							
							$pst_query = "id={$id}";
							$pst_field = "itemcode='{$item_number}'";

							$entry = "update";
							$params['body'] = "";
							$params['tbls'] = $mtbL5;
							$params['datasets'] = $pst_field;
							$params['constrains'] = $pst_query;
							$params['loop'] = 1;
							
							mysqlpost($entry,$params);

							$pst_query = "";
							$pst_field = "itemid={$id},costprice='{$wgtf12}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

							$entry = "insert";
							$params['body'] = "";
							$params['tbls'] = $mtbL18;
							$params['datasets'] = $pst_field;
							$params['constrains'] = $pst_query;
							$params['loop'] = 1;
							
							mysqlpost($entry,$params);
						}
					}
				}
			}

		} elseif($uri === 'insert-new-stock') {

			//$category = $_POST['category']; $subcategory = $_POST['subcategory'];
			$storagename = $_POST['store']; $deliverydate = $_POST['deliverydate']; $deliverynote = $_POST['deliverynote'];

			$supplier = $_POST['supplier']; $item = $_POST['item']; $quantity = $_POST['quantity'];
			$unitcost = $_POST['unitcost']; $totalcost = $_POST['totalcost'];

			//$order_number = "PR".substr(mt_rand(100,999999999999),1,6);
			$order_number = prgSequence($tbL155,'PR');
		
			for($r=0; $r < count($item); $r++) {
				
				if(!empty($supplier[$r]) && !empty($item[$r]) && !empty($unitcost[$r]) && !empty($totalcost[$r])) {
				
					$uom = idget_name($item[$r],'buying_unit',$mtbL5);
					$categoryid = idget_name($item[$r],'categoryid',$mtbL5);
					$subcategoryid = idget_name($item[$r],'subcategoryid',$mtbL5);
					
					$pst_query .= "";
					$pst_field .= "store='{$storagename}',order_number='{$order_number}',order_date='{$server_get_date}',delivery_date='{$deliverydate}',delivery_note='{$deliverynote}',supplierid={$supplier[$r]},uom={$uom},categoryid={$categoryid},subcategoryid={$subcategoryid},itemid={$item[$r]},unitprice='{$unitcost[$r]}',qty_ordered='{$quantity[$r]}',order_total_amount='{$totalcost[$r]}',order_tax_amount=0,order_net_amount='{$totalcost[$r]}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";

					$uom = ""; $categoryid = ""; $subcategoryid = "";
				}
			}

			$entry = "insert";
			$params['body'] = "({$order_number}) Purchase request created successfully. Go for approval";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

		} elseif($uri === 'update-pr') {

			$storagename = $_POST['store']; $deliverydate = $_POST['deliverydate']; $deliverynote = $_POST['deliverynote'];

			$row = $_POST['rwid']; $supplier = $_POST['supplier']; $item = $_POST['item'];
			$quantity = $_POST['quantity']; $unitcost = $_POST['unitcost']; $totalcost = $_POST['totalcost'];
			$order_number = remove_data_injection($_POST['datau']);

			$insert_query = ""; $insert_pst = "";

			for($r=0; $r < count($item); $r++) {
				
				if(!empty($supplier[$r]) && !empty($item[$r]) && !empty($unitcost[$r]) && !empty($totalcost[$r])) {
				
					$uom = idget_name($item[$r],'buying_unit',$mtbL5);
					$categoryid = idget_name($item[$r],'categoryid',$mtbL5);
					$subcategoryid = idget_name($item[$r],'subcategoryid',$mtbL5);

					if(!empty($row[$r]) && $row[$r] > 0) {
						$pst_query .= "id={$row[$r]};";
						$pst_field .= "store='{$storagename}',delivery_date='{$deliverydate}',delivery_note='{$deliverynote}',supplierid={$supplier[$r]},itemid={$item[$r]},unitprice='{$unitcost[$r]}',qty_ordered='{$quantity[$r]}',order_total_amount='{$totalcost[$r]}',order_tax_amount=0,order_net_amount='{$totalcost[$r]}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
					} else {
						$insert_query .= "";
						$insert_pst .= "order_number='{$order_number}',order_date='{$server_get_date}',delivery_date='{$deliverydate}',delivery_note='{$deliverynote}',supplierid={$supplier[$r]},uom={$uom},categoryid={$categoryid},subcategoryid={$subcategoryid},itemid={$item[$r]},unitprice='{$unitcost[$r]}',qty_ordered='{$quantity[$r]}',order_total_amount='{$totalcost[$r]}',order_tax_amount=0,order_net_amount='{$totalcost[$r]}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
					}

					$uom = ""; $categoryid = ""; $subcategoryid = "";
				}
			}

			$entry = "update";
			$params['body'] = "Purchase request updated successfully";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

			#---------------------------------------------------------

			/*$pst_field = $insert_pst;
			$pst_query = $insert_query;

			$entry = "insert";
			$params['body'] = "";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);*/

		} elseif($uri === 'insert-item-cost') {

			$item = remove_data_injection($_POST['item']);
			$costprice = remove_data_injection($_POST['costprice']);
			
			$pst_query = "";
			$pst_field = "itemid={$item},costprice='{$costprice}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$entry = "insert";
			$params['body'] = "Information is added successfully";
			$params['tbls'] = $mtbL18;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);
			$stockid = $mysql_rowid;
			
			$isItemid = $item;

			$pst_query = "";
			$pst_field = "";

			#update cost of purchase for pending order
			$isSql = "SELECT qty_ordered FROM {$mtbL8} WHERE itemid={$isItemid} AND order_status IN('Pending') AND deletedata=0";
			$wgtQy = idget_data($isSql);

			if(is_array($wgtQy) && count($wgtQy) > 0) {
				foreach($wgtQy as $key => $val) {
					$totalcost = $costprice * $val['qty_ordered'];
					$pst_query .= "itemid={$isItemid} AND order_status IN('Pending') AND receipt_status IN('Pending') AND deletedata=0;";
					$pst_field .= "unitprice='{$costprice}',order_total_amount='{$totalcost}',order_tax_amount=0,order_net_amount='{$totalcost}';";
				}

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 2;

				mysqlpost($entry,$params);
			}

			#update new cost of item in stock table
			$inStock = "SELECT * FROM {$mtbL19} WHERE itemid={$isItemid} AND deletedata=0";
			$wgtstock = idget_data($inStock);

			if(is_array($wgtstock) && count($wgtstock) > 0) {
				foreach($wgtstock as $key => $val) {
					$totalcost = $costprice * $val['balance'];
					$pst_query .= "itemid={$isItemid} AND balance > 0 AND deletedata=0;";
					$pst_field .= "unitprice='{$costprice}',total_cost='{$totalcost}';";
				}

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $mtbL19;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 2;

				mysqlpost($entry,$params);
			}

			#send notification to cost controllers
			$item_name = idget_name($isItemid,'item',$mtbL5);
			$message_title = "New Cost of Purchase for item (".$item_name.")";
			$sendmessage = "The following item (".$item_name.") cost of purchase recently changed: NGN ".$costprice;
			$users = getuser4_notification(11,0);
			
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>2,
				"msgtype"=>17,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

		} elseif($uri === 'apply-pr-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$orderno = remove_data_injection($_POST['orderno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$orderno}' AND approval_type='PR'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='PR'";
				$listuser = idget_data($getuser);
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='PR'";
				$listuser = idget_data($getuser);
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='PR'";
				$listuser = idget_data($getuser);
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='PR'";
				$listuser = idget_data($getuser);
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "Purchase Request (".$orderno.") Required Your Attention";
				$sendmessage = 'The following purchase request order number ('.$orderno.') has now required your approval to complete the transaction. Please click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpson(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>16,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "PR approval acknowledgement received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$pr_level = "SELECT job_level FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='PR'";
			$show_pr_level = idget_data($pr_level);

			$get_pr_job_level = $show_pr_level[0]['job_level'];
			
			if($get_pr_job_level == $level && $signatory == 1) { 

				$pst_query = "order_number='{$orderno}'";
				$pst_field = "order_status='Approved'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				$pst_query = "subject='{$orderno}' AND approval_type='PR'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);
			}

		} elseif($uri === 'apply-pr-iou-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$orderno = remove_data_injection($_POST['orderno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$orderno}' AND approval_type='IOU'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "IOU for PR (".$orderno.") Required Your Attention";
				$sendmessage = 'The following IOU for PR number ('.$orderno.') has now required your approval to complete the transaction. Please click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpson_iou(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>9,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "IOU approval acknowledgement received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$iou_level = "SELECT job_level FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
			$show_iou_level = idget_data($iou_level);

			$get_iou_job_level = $show_iou_level[0]['job_level'];
			
			if($get_iou_job_level == $level) { 

				$pst_query = "order_number='{$orderno}'";
				$pst_field = "pr_status='IOU Approved'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				$pst_query = "subject='{$orderno}' AND approval_type='IOU'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				#log into iou expenses
				$iou_amount = 0;
				$pst_query = "iou_no='{$orderno}'";
				$pst_field = "iou_no='{$orderno}',iou_type='GC Payment',expense_type='LPO Expenses',departmentid=0,receivedby=0,detail='Local purchase order',amount='{$iou_amount}',status='Pending',iou_date='{$server_get_date}'";

				$entry = "insert";
				$params['body'] = "";
				$params['tbls'] = $tbL158;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);
			}

		} elseif($uri === 'apply-iou-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$orderno = remove_data_injection($_POST['orderno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$orderno}' AND approval_type='IOU'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
				$listuser = idget_data($getuser);
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "IOU for PR (".$orderno.") Required Your Attention";
				$sendmessage = 'The following IOU number ('.$orderno.') has now required your approval to complete the transaction. Please click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpson_iou2(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>9,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "IOU approval acknowledgement received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);


			$iou_level = "SELECT job_level FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='IOU'";
			$show_iou_level = idget_data($iou_level);

			$get_iou_job_level = $show_iou_level[0]['job_level'];
			
			if($get_iou_job_level == $level) { 

				$pst_query = "iou_no='{$orderno}'";
				$pst_field = "status='Approved'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL161;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);
			}

		} elseif($uri === 'apply-pr-var-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$orderno = remove_data_injection($_POST['orderno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$orderno}' AND approval_type='VAR'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='VAR'";
				$listuser = idget_data($getuser);
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='VAR'";
				$listuser = idget_data($getuser);
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='VAR'";
				$listuser = idget_data($getuser);
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='VAR'";
				$listuser = idget_data($getuser);
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "Stock Variation for Purchase (".$orderno.") Required Your Attention";
				$sendmessage = 'Please approve stock variation. Click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpvar(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>19,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "Stock variation approval received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$pr_level = "SELECT job_level FROM $tbL151 WHERE subject='{$orderno}' AND approval_type='VAR'";
			$show_pr_level = idget_data($pr_level);

			$get_pr_job_level = $show_pr_level[0]['job_level'];
			
			if($get_pr_job_level == $level && $signatory == 1) { 

				$pst_query = "subject='{$orderno}' AND approval_type='VAR'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				#---------------------------------------------------------------------------------
				
				#change pr detail base on the stock variation approved

				$stock_var = "SELECT * FROM $mtbL20 WHERE order_number='{$orderno}'";
				$get_stock_var = idget_data($stock_var);

				if(is_array($get_stock_var)) {
					
					$for_upr_query = ""; $for_upr_pst = "";
					$for_itc_query = ""; $for_itc_pst = "";

					$grand_total_amount = 0;

					foreach($get_stock_var as $key => $val) {
						//$total_amount = $val['qty_required'] * $val['market_price'];
						$total_amount = $val['qty_bought'] * $val['market_price'];
						
						$for_upr_query .= "order_number='{$val['order_number']}' AND itemid='{$val['itemid']}';";
						$for_upr_pst .= "unitprice='{$val['market_price']}',qty_received='{$val['qty_bought']}',qty_diff='{$val['qty_diff']}',order_total_amount='{$total_amount}',order_net_amount='{$total_amount}',order_total_r_amount='{$val['total_amount']}',order_net_r_amount='{$val['total_amount']}',var_approval='Yes';";
						
						$grand_total_amount = $grand_total_amount + $total_amount;

						$total_amount = 0;

						///$for_itc_query .= "itemid={$val['itemid']} AND costprice='{$val['market_price']}';";
						//$for_itc_pst .= "itemid={$val['itemid']},costprice='{$val['market_price']}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
					}

					$pst_query = $for_upr_query;
					$pst_field = $for_upr_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $mtbL8;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);

					
					#get amount disbursed from iou or paymaster
					
					$amt_disbursed = idget_fname($orderno,'pr_no','amount',$tbL153);
					$amt_disbursed_pym = idget_fname($orderno,'pr_no','amount',$tbL154);
					
					if(!empty($amt_disbursed) && $amt_disbursed > 0) {

						$amt_variance = $amt_disbursed - $grand_total_amount;

						$pst_query = "pr_no='{$orderno}'";
						$pst_field = "pr_amount='{$grand_total_amount}',variance_amount='{$amt_variance}'";

						$entry = "update";
						$params['body'] = "";
						$params['tbls'] = $tbL153;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 1;
						
						mysqlpost($entry,$params);

					} elseif(!empty($amt_disbursed_pym) && $amt_disbursed_pym > 0) {

						$amt_variance = $amt_disbursed_pym - $grand_total_amount;

						$pst_query = "pr_no='{$orderno}'";
						$pst_field = "pr_amount='{$grand_total_amount}',variance_amount='{$amt_variance}'";

						$entry = "update";
						$params['body'] = "";
						$params['tbls'] = $tbL154;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 1;
						
						mysqlpost($entry,$params);

					}
				}
			}

		} elseif($uri === 'apply-stock-variance') {

			$order_number = remove_data_injection($_POST['pr']);
			$item = $_POST['item']; $qty_required = $_POST['qtyrequired']; $qty_bought = $_POST['qtybought'];
			$qty_diff = $_POST['qtydiff']; $price_request = $_POST['pricerequest']; $mkt_price = $_POST['mktprice'];
			$price_diff = $_POST['pricediff'];
			
			for($r=0; $r < count($item); $r++) {
				$total_amount = $qty_bought[$r] * $mkt_price[$r];
				$pst_query .= "order_number='{$order_number}' AND itemid={$item[$r]};";
				$pst_field .= "order_number='{$order_number}',itemid={$item[$r]},qty_required='{$qty_required[$r]}',qty_bought='{$qty_bought[$r]}',qty_diff='{$qty_diff[$r]}',price_request='{$price_request[$r]}',market_price='{$mkt_price[$r]}',price_diff='{$price_diff[$r]}',total_amount='{$total_amount}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
				$total_amount = 0;
			}

			$entry = "insert";
			$params['body'] = "Stock variation is added for verification";
			$params['tbls'] = $mtbL20;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);
			

			#send notification to cost controllers
			$message_title = "Stock Variation for Purchase Order No (".$order_number.")";
			$sendmessage = 'The variation in purchase number ('.$order_number.') requires your approval in order to update stock. Click <a href="javascript:void(0)" class="blue-font" name="'.$order_number.'" onclick="jpvar(this.name)"><u>here</u></a> to acknowledge';
			
			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(13,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>19,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$order_number}' AND approval_type='VAR'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$order_number}',user_one={$users[0]['id']},approval_type='VAR'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$order_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='VAR'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$order_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='VAR'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$order_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='VAR'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$order_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='VAR'";
				}


				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('insert',$params);


				$pst_query = "order_number='{$order_number}' AND order_status='Approved'";
				$pst_field = "var_status=1,var_approval='No'";

				$params['body'] = "";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('update',$params);

				if(isset($_GET['var'])) { unset($_GET['var']); }
			}

		} elseif($uri === 'apply-receive-stock') {

			$orderno = remove_data_injection($_POST['orderno']);

			$pr_var_data = "SELECT * FROM $mtbL8 WHERE order_number='{$orderno}' AND var_approval='Yes' AND receipt_status='Pending' AND deletedata=0"; $get_pr_var_data = idget_data($pr_var_data);

			if(is_array($get_pr_var_data)) {
				
				$invoice_number = 'LPO/INV/'.substr(mt_rand(100,999999999999),1,6);

				foreach($get_pr_var_data as $key => $val) {
						
					if($val['qty_received'] > 0) { $to_stock = $val['qty_received']; }
					else { $to_stock = $val['qty_ordered']; }

					if($val['store'] > 0) {

						#---warehouse stores

						//$storageid = idget_name($val['store'],'store',$tbL14);
						$storageid = $val['store'];
						$groupid = idget_name($val['itemid'],'itemgroupid',$mtbL5);
						$category = idget_name($val['itemid'],'categoryid',$mtbL5);
						$subcategory = idget_name($val['itemid'],'subcategoryid',$mtbL5);
						$item_name = idget_name($val['itemid'],'item',$mtbL5);

						#if the category not available in the pos category list
						
						$chk_item = "itemid='{$val['itemid']}' AND storageid={$storageid} AND deletedata=0";
						$is_item_exist = mysqli_data_exist($mtbL19,$chk_item);

						if($is_item_exist['isdata'] == true) {
						
							$sql_item = "SELECT * FROM {$mtbL19} WHERE ".$chk_item;
							$whr_item = idget_data($sql_item);

							$new_balance = $whr_item[0]['balance'] + $to_stock;
							$new_stockin = $whr_item[0]['stockin'] + $to_stock;
							$item_total_cost = $new_balance * $val['unitprice'];
							
							$entry = "update";
							$pst_query = "itemid={$val['itemid']} AND storageid={$storageid} AND deletedata=0";
							$pst_field = "uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$new_stockin}',balance='{$new_balance}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with PR - {$to_stock} Qty',userid={$userSignedIn}";

						} else {
							
							$item_total_cost = $to_stock * $val['unitprice'];

							$entry = "insert";
							$pst_query = "itemid={$val['itemid']} AND storageid={$storageid}";
							$pst_field = "storageid={$storageid},itemgroupid={$groupid},categoryid={$category},subcategoryid={$subcategory},itemid={$val['itemid']},uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$to_stock}',balance='{$to_stock}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with PR',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
						}

						
						$params['body'] = "";
						$params['tbls'] = $mtbL19;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 1;
						
						mysqlpost($entry,$params);

						$is_item_exist = ""; $is_w_item_exist = ""; $is_item_stockin = ""; $is_item_bal = "";
						$new_stockin = 0; $new_stockout = 0; $new_balance = 0; $item_total_cost = 0;
						$is_item_stockout = ""; $to_stock = "";
					}
					
					$for_pr_pst_query .= "order_number='{$val['order_number']}' AND itemid={$val['itemid']};";
					$for_pr_pst_field .= "delivery_date='{$server_get_date}',receipt_status='Received',invoice_number='{$invoice_number}';";
				}

				$pst_query = $for_pr_pst_query;
				$pst_field = $for_pr_pst_field;

				$entry = "update";
				$params['body'] = "Stock has been added to recipient store successfully";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 2;
				
				mysqlpost($entry,$params);
			}

		} elseif($uri === 'insert-open-invoice') {

			$pushtype = $_POST['pushtype'];
			$storagename = $_POST['store']; $deliverydate = $_POST['deliverydate']; $deliverynote = $_POST['deliverynote'];
			$supplier = $_POST['supplier']; $item = $_POST['item']; $quantity = $_POST['quantity'];
			$unitcost = $_POST['unitcost']; $totalcost = $_POST['totalcost'];

			$order_number = prgSequence($tbL155,'PR');
			$invoice_number = 'LPO/INV/'.substr(mt_rand(100,999999999999),1,6);
		
			for($r=0; $r < count($item); $r++) {
				
				if(!empty($supplier[$r]) && !empty($item[$r]) && !empty($unitcost[$r]) && !empty($totalcost[$r])) {
				
					$uom = idget_name($item[$r],'buying_unit',$mtbL5);
					$groupid = idget_name($item[$r],'itemgroupid',$mtbL5);
					$categoryid = idget_name($item[$r],'categoryid',$mtbL5);
					$subcategoryid = idget_name($item[$r],'subcategoryid',$mtbL5);
					
					$pst_query .= "";
					$pst_field .= "store='{$storagename}',store_type='{$pushtype}',order_number='{$order_number}',order_date='{$server_get_date}',delivery_date='{$deliverydate}',delivery_note='{$deliverynote}',invoice_number='{$invoice_number}',supplierid={$supplier[$r]},uom='{$uom}',itemgroupid={$groupid},categoryid={$categoryid},subcategoryid={$subcategoryid},itemid={$item[$r]},unitprice='{$unitcost[$r]}',qty_ordered='{$quantity[$r]}',order_total_amount='{$totalcost[$r]}',order_tax_amount=0,order_net_amount='{$totalcost[$r]}',ispr_to_manual='Yes',order_status='Approved',receipt_status='Received',gstat='Confirm',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";

					$uom = ""; $categoryid = ""; $subcategoryid = ""; $groupid = "";
				}
			}

			//error_log($pst_field,3,'w.txt');

			$entry = "insert";
			$params['body'] = "Purchase order received to store successfully";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

			#-----------------------------------------------------------------------move to store after pr table

			$orderno = $order_number;

			$pr_var_data = "SELECT * FROM $mtbL8 WHERE order_number='{$orderno}' AND ispr_to_manual='Yes' AND receipt_status='Received' AND deletedata=0"; $get_pr_var_data = idget_data($pr_var_data);

			if($pushtype == 'Virtual Stores') {

				if(is_array($get_pr_var_data)) {

					foreach($get_pr_var_data as $key => $val) {
							
						if($val['qty_received'] > 0) { $to_stock = $val['qty_received']; }
						else { $to_stock = $val['qty_ordered']; }

						if($val['store'] > 0) {

							#---warehouse stores

							$storageid = $val['store'];
							$groupid = idget_name($val['itemid'],'itemgroupid',$mtbL5);
							$category = idget_name($val['itemid'],'categoryid',$mtbL5);
							$subcategory = idget_name($val['itemid'],'subcategoryid',$mtbL5);
							$item_name = idget_name($val['itemid'],'item',$mtbL5);

							#if the category not available in the pos category list
							
							$chk_item = "itemid='{$val['itemid']}' AND storageid={$storageid} AND deletedata=0";
							$is_item_exist = mysqli_data_exist($mtbL19,$chk_item);

							if($is_item_exist['isdata'] == true) {
							
								$sql_item = "SELECT * FROM {$mtbL19} WHERE ".$chk_item;
								$whr_item = idget_data($sql_item);

								$new_balance = $whr_item[0]['balance'] + $to_stock;
								$new_stockin = $whr_item[0]['stockin'] + $to_stock;
								$item_total_cost = $new_balance * $val['unitprice'];
								
								$entry = "update";
								$pst_query = "itemid={$val['itemid']} AND storageid={$storageid} AND deletedata=0";
								$pst_field = "uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$new_stockin}',balance='{$new_balance}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with manual INV - {$to_stock} Qty',userid={$userSignedIn}";
							} else {
								
								$item_total_cost = $to_stock * $val['unitprice'];

								$entry = "insert";
								$pst_query = "itemid={$val['itemid']} AND storageid={$storageid}";
								$pst_field = "storageid={$storageid},itemgroupid={$groupid},categoryid={$category},subcategoryid={$subcategory},itemid={$val['itemid']},uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$to_stock}',balance='{$to_stock}',total_cost='{$item_total_cost}',delivery_date='{$server_get_date}',delivery_note='Last update with manual INV - {$to_stock} Qty',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
							}

							
							$params['body'] = "";
							$params['tbls'] = $mtbL19;
							$params['datasets'] = $pst_field;
							$params['constrains'] = $pst_query;
							$params['loop'] = 1;
							
							mysqlpost($entry,$params);

							$is_item_exist = ""; $is_w_item_exist = ""; $is_item_stockin = ""; $is_item_bal = "";
							$new_stockin = 0; $new_stockout = 0; $new_balance = 0; $item_total_cost = 0;
							$is_item_stockout = ""; $to_stock = "";
						}
					}
				}

			} elseif($pushtype == 'Outlets') {

				if(is_array($get_pr_var_data)) {
					
					foreach($get_pr_var_data as $key => $val) {
							
						if($val['qty_received'] > 0) { $to_stock = $val['qty_received']; }
						else { $to_stock = $val['qty_ordered']; }

						if($val['store'] == 0) {
							
							#---warehouse

							$is_item_exist = idget_fname($val['itemid'],'itemid','id',$mtbL19);

							if(!empty($is_item_exist) && $is_item_exist > 0) {
								$is_item_stockin = idget_fname($val['itemid'],'itemid','stockin',$mtbL19);
								$is_item_bal = idget_fname($val['itemid'],'itemid','balance',$mtbL19);

								$new_stockin = $is_item_stockin + $to_stock;
								$new_balance = $is_item_bal + $to_stock;
								$item_total_cost = $new_balance * $val['unitprice'];

								$entry = "update";
								$pst_query = "itemid='{$val['itemid']}'";
								$pst_field = "uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$new_balance}',stockout=0,balance='{$new_balance}',total_cost='{$item_total_cost}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
							} else {
								$entry = "insert";
								$pst_query = "itemid='{$val['itemid']}'";
								$pst_field = "itemid='{$val['itemid']}',uom={$val['uom']},supplierid={$val['supplierid']},unitprice='{$val['unitprice']}',stockin='{$to_stock}',balance='{$to_stock}',total_cost='{$val['order_net_r_amount']}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
							}

							$params['body'] = "";
							$params['tbls'] = $mtbL19;
							$params['datasets'] = $pst_field;
							$params['constrains'] = $pst_query;
							$params['loop'] = 1;
							
							mysqlpost($entry,$params);

							$is_item_exist = ""; $is_item_stockin = ""; $is_item_bal = "";
							$new_stockin = 0; $new_balance = 0; $item_total_cost = 0;
							$to_stock = "";

						} elseif($val['store'] > 0) {

							#---other stores
							
							//$storageid = idget_name($val['store'],'store',$tbL14);
							$storageid = 0;
							$groupid = idget_name($val['itemid'],'itemgroupid',$mtbL5);
							$category = idget_name($val['itemid'],'categoryid',$mtbL5);
							$subcategory = idget_name($val['itemid'],'subcategoryid',$mtbL5);
							$item_name = idget_name($val['itemid'],'item',$mtbL5);

							#if the category not available in the pos category list
							
							$category_name = idget_name($category,'category',$tbL115);
							$subcategory_name = idget_name($subcategory,'subcategory',$tbL116);

							$chk_ctg = "postoreid={$val['store']} AND category='{$category_name}'";
							$is_ctg_exist = mysqli_data_exist($tbL15,$chk_ctg);

							if($is_ctg_exist['isdata'] == false) {
								$x_pst_query = "postoreid={$val['store']} AND category='{$category_name}'";
								$x_pst_field = "postoreid={$val['store']},program_id={$groupid},category='{$category_name}'";
								$pops = mysqli_data_insert($tbL15,$x_pst_field,$x_pst_query);
								$new_ctg_id = $pops['rowid'];
							} else {
								$chk_ctg = "SELECT * FROM $tbL15 WHERE postoreid={$val['store']} AND category='{$category_name}'";
								$is_ctg_exist = idget_data($chk_ctg);
								$new_ctg_id = $is_ctg_exist[0]['id'];
							}

							$chk_ctgx = "postoreid={$val['store']} AND categoryid={$new_ctg_id} AND subcategory='{$subcategory_name}'";
							$is_ctgx_exist = mysqli_data_exist($tbL92,$chk_ctgx);

							if($is_ctgx_exist['isdata'] == false) {
								$x_pst_query = "postoreid={$val['store']} AND categoryid={$new_ctg_id} AND subcategory='{$subcategory_name}'";
								$x_pst_field = "postoreid={$val['store']},categoryid={$new_ctg_id},subcategory='{$subcategory_name}'";
								$pops = mysqli_data_insert($tbL92,$x_pst_field,$x_pst_query);
								$new_ctgx_id = $pops['rowid'];
							} else {
								$chk_ctgx = "SELECT * FROM $tbL92 WHERE postoreid={$val['store']} AND categoryid={$new_ctg_id} AND subcategory='{$subcategory_name}'";
								$is_ctgx_exist = idget_data($chk_ctgx);
								$new_ctgx_id = $is_ctgx_exist[0]['id'];
							}

							#--end here

							$chk_item = "itemcode='{$val['itemid']}' AND postoreid={$val['store']} AND storagetype='consumable' AND deletedata=0"; $is_item_exist = mysqli_data_exist($tbL16,$chk_item);

							if($is_item_exist['isdata'] == true) {
								$is_item_stockin = idget_fname($val['itemid'],'itemcode','stockin',$tbL16);
								$is_item_bal = idget_fname($val['itemid'],'itemcode','balance',$tbL16);

								$new_balance = $is_item_bal + $to_stock;
								$new_stockin = $new_balance;
								
								$entry = "update";
								$pst_query = "itemcode='{$val['itemid']}' AND postoreid={$val['store']} AND storagetype='consumable'";
								$pst_field = "uom={$val['uom']},cost='{$val['unitprice']}',stockin='{$new_stockin}',stockout=0,balance='{$new_balance}'";
							} else {
								$entry = "insert";
								$pst_query = "itemcode='{$val['itemid']}' AND postoreid={$val['store']} AND storagetype='consumable'";
								$pst_field = "storageid={$storageid},storagetype='consumable',postoreid={$val['store']},categoryid={$new_ctg_id},subcategoryid={$new_ctgx_id},itemcode='{$val['itemid']}',item='{$item_name}',uom={$val['uom']},cost='{$val['unitprice']}',price=0,stockin='{$to_stock}',stockout=0,balance='{$to_stock}',isfeature='No',isstaff='No'";
							}

							$params['body'] = "";
							$params['tbls'] = $tbL16;
							$params['datasets'] = $pst_field;
							$params['constrains'] = $pst_query;
							$params['loop'] = 1;
							
							mysqlpost($entry,$params);

							$is_item_exist = ""; $is_w_item_exist = ""; $is_item_stockin = ""; $is_item_bal = "";
							$new_stockin = 0; $new_stockout = 0; $new_balance = 0; $item_total_cost = 0;
							$is_item_stockout = ""; $to_stock = "";
						}
				
					}
				}
			}

		} elseif($uri === 'apply-item-request') { 

			$storage = remove_data_injection($_POST['storage']);
			$storage_name = idget_name($storage,'department',$tbL12);
			$request_number = remove_data_injection($_POST['requestnumber']);

			$id = $_POST['id'];
			$qtyrequired = $_POST['qtyrequired'];
			$qtytransfer = $_POST['qtytransfer'];
			
			$insert_data = 0; $balance = 0;

			for($i=0; $i < count($id); $i++) {
				
				if(!empty($qtytransfer[$i]) && $qtytransfer[$i] >= 0) {
					$balance = $qtyrequired[$i] - $qtytransfer[$i];

					$pst_field .= "qty_received='{$qtytransfer[$i]}',qty_diff='{$balance}',status='Under Approval',whr_user={$userSignedIn};";
					$pst_query .= "id={$id[$i]};";
				
					//mysqli_data_update($tbL152,$pst_field,$pst_query);
					//$pst_field=""; $pst_query="";
					//$insert_data += 1;
				}
			}

			$entry = "update";
			$params['body'] = "";
			$params['tbls'] = $tbL152;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

			$message_title = "Item Request Approval (".$request_number.")";
			$sendmessage = 'The following item request number ('.$request_number.') for '.$storage_name.' needs approval to complete disburstment. Please click <a href="javascript:void(0)" class="blue-font" name="'.$request_number.'" onclick="jpdbst(this.name)"><u>here</u></a> to acknowledge';

			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(4,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>10,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$request_number}' AND approval_type='ITEM DISBURST'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$request_number}',user_one={$users[0]['id']},approval_type='ITEM DISBURST'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='ITEM DISBURST'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='ITEM DISBURST'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='ITEM DISBURST'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$request_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='ITEM DISBURST'";
				}

				//mysqli_data_insert($tbL151,$pst_field,$pst_query);

				$entry = "insert";
				$params['body'] = "Approval for item request sent successfully";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;

				mysqlpost($entry,$params);
			}
			

		} elseif($uri === 'apply-item-request-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$requestno = remove_data_injection($_POST['requestno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$requestno}' AND approval_type='ITEM DISBURST'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='ITEM DISBURST'";
				$listuser = idget_data($getuser);
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='ITEM DISBURST'";
				$listuser = idget_data($getuser);
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='ITEM DISBURST'";
				$listuser = idget_data($getuser);
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='ITEM DISBURST'";
				$listuser = idget_data($getuser);
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "Item Request for (".$requestno.") Required Your Attention";
				$sendmessage = 'Please approve item request. Click <a href="javascript:void(0)" class="blue-font" name="'.$requestno.'" onclick="jpdbst(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>10,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "Item request approval received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$pr_level = "SELECT job_level FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='ITEM DISBURST'";
			$show_pr_level = idget_data($pr_level);

			$get_pr_job_level = $show_pr_level[0]['job_level'];
			
			if($get_pr_job_level == $level && $signatory == 1) { 

				$pst_query = "subject='{$requestno}' AND approval_type='ITEM DISBURST'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				#---------------------------------------------------------------------------------
				
				#update stock balance for requested item

				$stock_var = "SELECT * FROM $tbL152 WHERE request_number='{$requestno}'";
				$get_stock_var = idget_data($stock_var);

				if(is_array($get_stock_var)) {
					
					$for_stock_query=""; $for_stock_pst="";
					$for_ir_query=""; $for_ir_pst="";

					foreach($get_stock_var as $key => $val) {
						
						$stockout = idget_fname($val['itemid'],'itemid','stockout',$mtbL19);
						$stockbal = idget_fname($val['itemid'],'itemid','balance',$mtbL19);

						$new_stockout = $stockout + $val['qty_received'];
						$new_stockbal = $stockbal - $val['qty_received'];

						$for_stock_query .= "itemid='{$val['itemid']}';";
						$for_stock_pst .= "stockout='{$new_stockout}',balance='{$new_stockbal}';";
						
						$for_ir_query .= "itemid='{$val['itemid']}' AND request_number='{$requestno}';";
						$for_ir_pst .= "status='Ready to Disburse';";
					}

					/*$pst_query = $for_stock_query;
					$pst_field = $for_stock_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $mtbL19;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);*/


					$pst_query = $for_ir_query;
					$pst_field = $for_ir_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $tbL152;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);
				}
			}

		} elseif($uri === 'apply-item-transfer-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$requestno = remove_data_injection($_POST['requestno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$requestno}' AND approval_type='TR'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				//$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				//$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				//$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				//$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "Item Transfer for (".$requestno.") Required Your Attention";
				$sendmessage = 'Please approve item transfer. Click <a href="javascript:void(0)" class="blue-font" name="'.$requestno.'" onclick="jpdbst(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>20,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "Item transfer approval received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$pr_level = "SELECT job_level FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
			$show_pr_level = idget_data($pr_level);

			$get_pr_job_level = $show_pr_level[0]['job_level'];
			
			if($get_pr_job_level == $level && $signatory == 1) { 

				$pst_query = "subject='{$requestno}' AND approval_type='TR'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				#---------------------------------------------------------------------------------
				
				#update stock balance for requested item

				$stock_var = "SELECT * FROM $tbL156 WHERE transfer_number='{$requestno}'";
				$get_stock_var = idget_data($stock_var);

				if(is_array($get_stock_var)) {
					
					$for_stock_query=""; $for_stock_pst="";
					$for_stock_query2=""; $for_stock_pst2="";
					$fr_for_stock_query=""; $fr_for_stock_pst="";
					$for_tr_query=""; $for_tr_pst="";
					$fr_remove_query = ""; $fr_remove_data = "";

					$stockin = ""; $new_stockin = ""; $fr_stockout = ""; $fr_stockbal = "";
					$fr_new_stockout = ""; $fr_new_stockbal = ""; $new_stockbal = ""; $new_stockin = "";

					$dotransfer = false;

					foreach($get_stock_var as $key => $val) {
						
						if($val['from_posid'] > 0) {
							$os_query = "SELECT * FROM {$mtbL19} WHERE itemid='{$val['itemid']}' AND storageid={$val['from_posid']} AND deletedata=0"; $os_data = idget_data($os_query);

							if($os_data[0]['balance'] >= $val['qty_transfer']) {
								
								$fr_stockout = $os_data[0]['stockout'];
								$fr_stockbal = $os_data[0]['balance'];

								$fr_new_stockout = $fr_stockout + $val['qty_transfer'];
								$fr_new_stockbal = $fr_stockbal - $val['qty_transfer'];

								$fr_for_stock_query .= "itemid='{$val['itemid']}' AND storageid={$val['from_posid']} AND deletedata=0;";
								$fr_for_stock_pst .= "stockout='{$fr_new_stockout}',balance='{$fr_new_stockbal}',delivery_date='{$server_get_date}',delivery_note='Last update with TR - {$val['qty_transfer']} Qty',userid={$userSignedIn};";

								$dotransfer = true;

							} else {
								
								$fr_remove_query .= "id={$val['id']};";
								$fr_remove_data = "";

								$dotransfer = false;
							}
						} else {
							$dotransfer = false;
						}

						
						if($dotransfer == true) {
							if($val['to_posid'] > 0) {
								$or_query = "SELECT * FROM {$tbL16} WHERE itemcode='{$val['itemid']}' AND postoreid={$val['to_posid']} AND storagetype='consumable'"; $or_data = idget_data($or_query);

								$stockbal = $or_data[0]['balance'];
								$stockin = $or_data[0]['stockin'];
								$new_stockbal = $stockbal + $val['qty_transfer'];
								$new_stockin = $stockin + $val['qty_transfer'];

								
								$for_stock_query .= "itemcode='{$val['itemid']}' AND postoreid={$val['to_posid']} AND storagetype='consumable';";
								$for_stock_pst .= "stockin='{$new_stockin}',balance='{$new_stockbal}';";
								
							}

							$for_tr_query .= "itemid='{$val['itemid']}' AND transfer_number='{$requestno}';";
							$for_tr_pst .= "transfer_status='Transfer Completed';";
						}
					}

					if((isset($fr_for_stock_pst) && !empty($fr_for_stock_pst)) && (isset($fr_for_stock_query) && !empty($fr_for_stock_query))) {
						
						$pst_query = $fr_for_stock_query;
						$pst_field = $fr_for_stock_pst;

						$entry = "update";
						$params['body'] = "";
						$params['tbls'] = $mtbL19;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 2;
						
						mysqlpost($entry,$params);
					}

					if((isset($for_stock_pst) && !empty($for_stock_pst)) && (isset($for_stock_query) && !empty($for_stock_query))) {
					
						$pst_query = $for_stock_query;
						$pst_field = $for_stock_pst;

						$entry = "update";
						$params['body'] = "";
						$params['tbls'] = $tbL16;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 2;
						
						mysqlpost($entry,$params);
					}

					if(isset($fr_remove_query) && !empty($fr_remove_query)) {
					
						$pst_query = $fr_remove_query;
						$pst_field = "";

						$entry = "delete";
						$params['body'] = "";
						$params['tbls'] = $tbL156;
						$params['datasets'] = $pst_field;
						$params['constrains'] = $pst_query;
						$params['loop'] = 2;
						
						mysqlpost($entry,$params);
					}

					$pst_query = $for_tr_query;
					$pst_field = $for_tr_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $tbL156;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);
				}
			}

		} elseif($uri === 'apply-bnd-approval') {

			$commentpr = remove_data_injection($_POST['commentpr']).' - '.date('d-m-Y',strtotime($server_get_date)).' '.$server_get_time;
			$requestno = remove_data_injection($_POST['requestno']);
			$level = remove_data_injection($_POST['level']);
			$signatory = remove_data_injection($_POST['signatory']);
			
			$pst_query = "subject='{$requestno}' AND approval_type='BND'";

			if($level == 1) {
				$pst_field = "approval_one={$signatory},comment_one='{$commentpr}'";
				//$getuser = "SELECT user_two FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 2) {
				$pst_field = "approval_two={$signatory},comment_two='{$commentpr}'";
				//$getuser = "SELECT user_three FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 3) {
				$pst_field = "approval_three={$signatory},comment_three='{$commentpr}'";
				//$getuser = "SELECT user_four FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 4) {
				$pst_field = "approval_four={$signatory},comment_four='{$commentpr}'";
				//$getuser = "SELECT user_five FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='TR'";
				//$listuser = idget_data($getuser);
				$listuser = "";
			} elseif($level == 5) {
				$pst_field = "approval_five={$signatory},comment_five='{$commentpr}'";
				$listuser = "";
			}

			/*if((is_array($listuser) && count($listuser) > 0) && $signatory == 1) {

				$message_title = "Bad/Damage (".$requestno.") Required Your Attention";
				$sendmessage = 'Please approve bad/damage items. Click <a href="javascript:void(0)" class="blue-font" name="'.$requestno.'" onclick="jpbnd(this.name)"><u>here</u></a> to acknowledge';

				$users = $listuser;

				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>20,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);
			}*/
			

			$entry = "update";
			$params['body'] = "bad/damage approval received";
			$params['tbls'] = $tbL151;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);

			$pr_level = "SELECT job_level FROM $tbL151 WHERE subject='{$requestno}' AND approval_type='BND'";
			$show_pr_level = idget_data($pr_level);

			$get_pr_job_level = $show_pr_level[0]['job_level'];
			
			if($get_pr_job_level == $level && $signatory == 1) { 

				$pst_query = "subject='{$requestno}' AND approval_type='BND'";
				$pst_field = "approval_status='Completed'";

				$entry = "update";
				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost($entry,$params);

				#---------------------------------------------------------------------------------
				
				#update stock balance for requested item

				$stock_var = "SELECT * FROM $tbL157 WHERE bnd_number='{$requestno}'";
				$get_stock_var = idget_data($stock_var);

				if(is_array($get_stock_var)) {
					
					$for_stock_query=""; $for_stock_pst="";
					$for_tr_query=""; $for_tr_pst="";
					$for_2tr_query=""; $for_2tr_pst="";

					foreach($get_stock_var as $key => $val) {
						
						$stockout = idget_fname($val['itemid'],'itemid','stockout',$mtbL19);
						$stockbal = idget_fname($val['itemid'],'itemid','balance',$mtbL19);

						$new_stockout = $stockout + $val['stock'];
						$new_stockbal = $stockbal - $val['stock'];

						$for_tr_query .= "itemid='{$val['itemid']}' AND bnd_number='{$requestno}';";
						$for_tr_pst .= "bnd_status='Approved';";

						$for_stock_query .= "itemid='{$val['itemid']}';";
						$for_stock_pst .= "stockout='{$new_stockout}',balance='{$new_stockbal}';";

						$for_2tr_query .= "itemid='{$val['itemid']}' AND bnd=0 ORDER BY id DESC LIMIT 1;";
						$for_2tr_pst .= "bnd=1;";
					}

					
					$pst_query = $for_tr_query;
					$pst_field = $for_tr_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $tbL157;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);

					#---------------------------------------------------

					$pst_query = $for_2tr_query;
					$pst_field = $for_2tr_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $tbL156;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);

					#---------------------------------------------------

					$pst_query = $for_stock_query;
					$pst_field = $for_stock_pst;

					$entry = "update";
					$params['body'] = "";
					$params['tbls'] = $mtbL19;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 2;
					
					mysqlpost($entry,$params);
				}
			}

		} elseif($uri === 'apply-transfer-request') {

			$transfer_number = prgSequence($tbL155,'TR');
			$get_selling_uom = "";

			$frompos = $_POST['frompos']; $topos = $_POST['topos']; $transferas = $_POST['transferas'];
			$item = $_POST['item']; $qty = $_POST['qty'];
			
			for($r=0; $r < count($item); $r++) {
				$get_selling_uom = idget_name($item[$r],'selling_unit',$tbL118);
				//$pst_query .= "from_posid={$frompos} AND itemid='{$item[$r]}' AND transfer_status='Under Approval';";
				$pst_query .= "";
				$pst_field .= "transfer_number='{$transfer_number}',itemid='{$item[$r]}',from_posid={$frompos},to_posid={$topos},qty_transfer='{$qty[$r]}',uom={$get_selling_uom},tagged_name='{$transferas}',transfer_status='Under Approval',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
			}


			$entry = "insert";
			$params['body'] = "Item transfer has been sent for approval";
			$params['tbls'] = $tbL156;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);
			

			#send notification to cost controllers
			$message_title = "Approval for Item Transfer No (".$transfer_number.")";
			$sendmessage = 'The following item transfer ('.$transfer_number.') needs approval to complete the transfer. Click <a href="javascript:void(0)" class="blue-font" name="'.$transfer_number.'" onclick="jptr(this.name)"><u>here</u></a> to acknowledge';
			
			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(12,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>20,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$transfer_number}' AND approval_type='TR'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$transfer_number}',user_one={$users[0]['id']},approval_type='TR'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$transfer_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='TR'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$transfer_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='TR'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$transfer_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='TR'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$transfer_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='TR'";
				}


				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('insert',$params);
			}

		} elseif($uri === 'apply-iou-expense-request') {

			$iou_no = prgSequence($tbL155,'IOU');
		
			$transaction_type = $_POST['transactiontype']; $expenses_type = $_POST['expensetype'];
			$transaction_date = $_POST['transactiondate']; $amount = remove_data_injection($_POST['amount']); 
			$department = $_POST['department']; $user = $_POST['user']; $detail = remove_data_injection($_POST['remark']);
			
			$pst_query = "iou_no='{$iou_no}'";
			$pst_field = "iou_no='{$iou_no}',iou_type='{$transaction_type}',expense_type='{$expenses_type}',departmentid={$department},receivedby={$user},detail='{$detail}',amount='{$amount}',iou_date='{$transaction_date}',status='Under Approval',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
			
			$entry = "insert";
			$params['body'] = "";
			$params['tbls'] = $tbL158;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost($entry,$params);
			

			$pst_query = "iou_no='{$iou_no}'";
			$pst_field = "iou_no='{$iou_no}',amount='{$amount}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$params['body'] = "";
			$params['tbls'] = $tbL153;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('insert',$params);
			#--end here

			$message_title = "Approval for IOU Number (".$iou_no.")";
			$sendmessage = 'The following IOU number ('.$iou_no.') requires approval. Please click <a href="javascript:void(0)" class="blue-font" name="'.$iou_no.'" onclick="jpson_iou2(this.name)"><u>here</u></a> to acknowledge';

			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(3,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>9,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$iou_no}' AND approval_type='IOU'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$iou_no}',user_one={$users[0]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$iou_no}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$iou_no}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$iou_no}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$iou_no}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='IOU'";
				}

				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('insert',$params);
			}

		} elseif($uri === 'edit-item-request') {

			$id = $_POST['id'];
			$qtyrequired = $_POST['qtyrequired'];
			$qtytransfer = $_POST['qtytransfer'];
			
			$balance = 0;

			for($i=0; $i < count($id); $i++) {
				if(!empty($qtytransfer[$i]) && $qtytransfer[$i] > 0) {
					$balance = $qtyrequired[$i] - $qtytransfer[$i];
					$pst_field .= "qty_received='{$qtytransfer[$i]}',qty_diff='{$balance}';";
					$pst_query .= "id={$id[$i]};";						
				}
			}

			$params['body'] = "";
			$params['tbls'] = $tbL152;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost('update',$params);

		} elseif($uri === 'disburse-stock-to-store') {

			createDatabasetable($var_tbl_158); //create a table

			$request_number = remove_data_injection($_POST['requestnumber']);

			$stores = remove_data_injection($_POST['stores']);
			$pos = remove_data_injection($_POST['pos']);
			$storage = remove_data_injection($_POST['storage']);
			
			if(isset($_POST['checkers']) && !empty($_POST['checkers'])) {
				
				$inx = 0;

				foreach($_POST['checkers'] as $push_id) {

					$ir_data = "SELECT * FROM $tbL152 WHERE id={$push_id} AND deletedata=0";
					$val = idget_data($ir_data);

					
					$irt_data = "SELECT * FROM $mtbL19 WHERE itemid='{$val[0]['itemid']}' AND storageid={$stores} AND balance >= {$val[0]['qty_received']} AND deletedata=0"; $for_irt_data = idget_data($irt_data);

					if(!empty($for_irt_data[0]['balance']) && $for_irt_data[0]['balance'] >= $val[0]['qty_received']) {
						
						$new_stockout = $for_irt_data[0]['stockout'] + $val[0]['qty_received'];
						$new_stockbal = $for_irt_data[0]['balance'] - $val[0]['qty_received'];

						$pst_query = "itemid='{$val[0]['itemid']}' AND storageid={$stores}";
						$pst_field = "stockout='{$new_stockout}',balance='{$new_stockbal}',delivery_date='{$server_get_date}',delivery_note='Last update with IR - {$val[0]['qty_received']} Qty',userid={$userSignedIn}";
						mysqli_data_update($mtbL19,$pst_field,$pst_query);

						$pst_query = "id={$push_id}";
						$pst_field = "acceptance=1,status='Disbursed'";
						mysqli_data_update($tbL152,$pst_field,$pst_query);

						$get_item_name = idget_name($val[0]['itemid'],'item',$tbL118);
						$get_item_cost = $val[0]['qty_received'] * $for_irt_data[0]['unitprice'];

						$pst_query = "";
						$pst_field = "departmentid={$storage},storageid={$stores},categoryid={$for_irt_data[0]['categoryid']},subcategoryid={$for_irt_data[0]['subcategoryid']},itemcode={$val[0]['itemid']},item='{$get_item_name}',stockin='{$val[0]['qty_received']}',uom='{$val[0]['uom']}',cost='{$get_item_cost}',balance='{$val[0]['qty_received']}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
						
						mysqli_data_insert($mtbL22,$pst_field,$pst_query);

						
						$get_item_cost = ""; $get_item_stockout = ""; $get_item_stockbal = "";
						$stockin = ""; $balance = ""; $new_stock = ""; $new_balance = ""; $get_item_subctgid = "";
						$new_stockout = ""; $new_stockbal = ""; $get_item_name = ""; $get_item_ctgid = "";

						$pst_query = ""; $pst_field = "";

						$inx += 1;
					}
				}

				if(isset($inx) && $inx >= 1) {
					$saynotify = 1;
					$post_header = "Notification";
					$post_message = "Selected items were disbursed successfully";
				}
			}

		} elseif($uri === 'mc-make-item-request') {

			$cur_pos_storage = $_POST['stores'];
			$cur_pos_id = 0;
			//$cur_pos_id = idget_fname($cur_pos_storage,'store','id',$tbL14);

			$item = $_POST['item'];
			$qty = $_POST['qty'];
			$stock_type = "serviceable";

			$request_number = prgSequence($tbL155,'IR');
			$get_selling_uom = "";

			for($i=0; $i < count($item); $i++) {
				
				$get_selling_uom = idget_name($item[$i],'selling_unit',$tbL118);
				
				//$pst_query .= "storeid={$cur_pos_storage} AND itemid={$item[$i]} AND stock_type='{$stock_type}' AND status='Reviewing';";
				$pst_query .= "";
				$pst_field .= "request_number='{$request_number}',posid={$cur_pos_id},storeid={$cur_pos_storage},itemid={$item[$i]},uom={$get_selling_uom},qty_required='{$qty[$i]}',stock_type='{$stock_type}',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}';";
			}

			$entry = "insert";
			$params['body'] = "Item request submitted successfully";
			$params['tbls'] = $tbL152;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost($entry,$params);

		} elseif($uri === 'pr-approval-request') {
			
			createDatabasetable($var_tbl_150);
			$tbl = $tbL151;

			#get request PR
			$forPr = remove_data_injection($_POST['pr']);
			//$isSql = "SELECT order_number FROM {$mtbL8} WHERE order_status IN('Pending') AND gstat IN('Pending') AND deletedata=0 LIMIT 1";$wgtQy = idget_data($isSql);

			if(!empty($forPr)) {
				
				$message_title = "Purchase Request Approval for (".$forPr.")";
				$sendmessage = 'The following purchase request order number ('.$forPr.') is up for approval. Please click <a href="javascript:void(0)" class="blue-font" name="'.$forPr.'" onclick="jpson(this.name)"><u>here</u></a> to acknowledge';

				$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
				$users = getuser4_notification(6,$workflow);
				
				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>16,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);

				if(is_array($users) && count($users) > 0) {
				
					$joblevel = count($users);
					$pst_query = "subject='{$forPr}' AND approval_type='PR'";
					
					if(isset($joblevel) && $joblevel == 1) {
						$pst_field = "job_level=1,subject='{$forPr}',user_one={$users[0]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 2) {
						$pst_field = "job_level=2,subject='{$forPr}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 3) {
						$pst_field = "job_level=3,subject='{$forPr}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 4) {
						$pst_field = "job_level=4,subject='{$forPr}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 5) {
						$pst_field = "job_level=5,subject='{$forPr}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='PR'";
					}


					$params['body'] = "PR approval was sent successfully";
					$params['tbls'] = $tbl;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost('insert',$params);

					$pst_query = "order_number='{$forPr}' AND order_status='Pending' AND gstat='Pending'";
					$pst_field = "gstat='Confirm'";

					$params['body'] = "";
					$params['tbls'] = $mtbL8;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost('update',$params);
				}
			}

		} elseif($uri === 'pr-pay-request') {
			
			$orderno = remove_data_injection($_POST['pr']);

			#create an PAY record for this PR
			createDatabasetable($var_tbl_156);
			$pay_no = prgSequence($tbL155,'PAYM');

			$pst_query = "pay_no='{$pay_no}'";
			$pst_field = "pay_no='{$pay_no}',pr_no='{$orderno}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$params['body'] = "";
			$params['tbls'] = $tbL154;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('insert',$params);
			#--end here

			$message_title = "Cash for Purchase Request (".$orderno.")";
			$sendmessage = 'The following purchase request order number ('.$orderno.') is now set for fund disburstment. Please go to your portal (Accounting - Cash Purchase) to attend to this request';

			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(8,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>18,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			$pst_query = "order_number='{$orderno}'";
			$pst_field = "pr_status='Payment Inview'";

			$params['body'] = "PR is now set for fund disburstment";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('update',$params);
			
		} elseif($uri === 'pr-iou-approval') {
			
			$orderno = remove_data_injection($_POST['pr']);

			#create an IOU record for this PR
			createDatabasetable($var_tbl_154);
			$iou_no = prgSequence($tbL155,'IOU');

			$pst_query = "iou_no='{$iou_no}'";
			$pst_field = "iou_no='{$iou_no}',pr_no='{$orderno}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$params['body'] = "";
			$params['tbls'] = $tbL153;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('insert',$params);
			#--end here

			$message_title = "Approval for IOU Number (".$iou_no.")";
			$sendmessage = 'The following IOU number ('.$iou_no.') needs your authorization to complete a PR transaction. Please click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpson_iou(this.name)"><u>here</u></a> to acknowledge';

			$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
			$users = getuser4_notification(3,$workflow);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>9,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$orderno}' AND approval_type='IOU'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$orderno}',user_one={$users[0]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='IOU'";
				}


				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('insert',$params);


				$pst_query = "order_number='{$orderno}'";
				$pst_field = "pr_status='IOU'";

				$params['body'] = "PR is now transferred for IOU process";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('update',$params);
			}
			
		} elseif($uri === 'submit-food-flash-form') {

			include "../../../includes/fnf_tables.php";
			createDatabasetable($var_fnf_tbL1);
			createDatabasetable($var_fnf_tbL2);

			$trdate = $_POST['transactiondate'];

			$fb = $_POST['fb']; $nfb = $_POST['nfb']; $gfb = $_POST['gfb'];
			$bsb = $_POST['bsb']; $nbsb = $_POST['nbsb']; $gbsb = $_POST['gbsb'];
			$outlets = $_POST['outlets']; $covers = $_POST['covers']; $foods = $_POST['foods'];

			#--food

			$pst_query = ""; $pst_field = "";

			for($f=0; $f < count($fb); $f++) {
				
				if(!empty($fb[$f])) { $val = $fb[$f]; }
				else { $val = 0; }
				
				$pst_query .= "category='food' AND name='{$nfb[$f]}' AND transaction_date='{$trdate}';";
				$pst_field .= "category='food',name='{$nfb[$f]}',amount='{$val}',ngroup='{$gfb[$f]}',transaction_date='{$trdate}',datelogged='{$server_get_date}',timelogged='{$server_get_time}';";

				$val = "";
			}

			$params['body'] = "";
			$params['tbls'] = "fd_analysis_tbl";
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost('insert',$params);

			#--beverages

			$pst_query = ""; $pst_field = "";

			for($b=0; $b < count($bsb); $b++) {
				
				if(!empty($bsb[$b])) { $val = $bsb[$b]; }
				else { $val = 0; }
				
				$pst_query .= "category='beverages' AND name='{$nbsb[$b]}' AND transaction_date='{$trdate}';";
				$pst_field .= "category='beverages',name='{$nbsb[$b]}',amount='{$val}',ngroup='{$gbsb[$b]}',transaction_date='{$trdate}',datelogged='{$server_get_date}',timelogged='{$server_get_time}';";

				$val = "";
			}

			$params['body'] = "";
			$params['tbls'] = "fd_analysis_tbl";
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost('insert',$params);

			#--sales

			$pst_query = ""; $pst_field = "";

			for($s=0; $s < count($outlets); $s++) {
				
				if(!empty($covers[$s])) { $cval = $covers[$s]; }
				else { $cval = 0; }

				if(!empty($foods[$s])) { $fval = $foods[$s]; }
				else { $fval = 0; }
				
				$pst_query .= "pos={$outlets[$s]} AND transaction_date='{$trdate}';";
				$pst_field .= "pos={$outlets[$s]},cover='{$cval}',food='{$fval}',transaction_date='{$trdate}',datelogged='{$server_get_date}',timelogged='{$server_get_time}';";

				$cval = ""; $fval = "";
			}

			$params['body'] = "FF information processed successfully";
			$params['tbls'] = "outlet_food_analysis_tbl";
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 2;
			
			mysqlpost('insert',$params);

		}




		#end entry form post

		if($ftask === 'delete') {
			
			$params['body'] = "Record deleted successfully";
			$params['tbls'] = $_POST['xtbl'];
			$params['datasets'] = "";
			$params['loop'] = 2;
			$pst_query = "";
			
			foreach($_POST['checkers'] as $row) { $pst_query .= "id={$row};"; }
			$params['constrains'] = $pst_query;
			
			mysqlpost('delete',$params);

		} elseif($ftask === 'noidelete') {
			
			$params['body'] = "Record deleted successfully";
			$params['tbls'] = $_POST['xtbl'];
			$params['datasets'] = "";
			$params['loop'] = 2;
			$pst_query = "";

			$col = $_POST['xcol'];
			
			foreach($_POST['checkers'] as $row) { $pst_query .= "{$col}='{$row}';"; }
			$params['constrains'] = $pst_query;
			
			mysqlpost('delete',$params);

		} elseif($ftask === 'archive') {

			$params['body'] = "Record deleted successfully";
			$params['tbls'] = $_POST['xtbl'];
			$params['loop'] = 2;
			$pst_field = "";
			$pst_query = "";
			
			foreach($_POST['checkers'] as $row) { $pst_query .= "id={$row};"; $pst_field .= "deletedata=1;"; }
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			
			mysqlpost('update',$params);

		} elseif($ftask === 'user-status') {

			$params['body'] = "Record status changed successfully";
			$params['tbls'] = $_POST['xtbl'];
			$params['loop'] = 2;
			$pst_field = "";
			$pst_query = "";
			
			foreach($_POST['checkers'] as $row) {
				$wgtstat = idget_name($row,'status',$_POST['xtbl']);
				$pst_query .= "id={$row};";
				if($wgtstat == 1) { $pst_field .= "status=0;"; }
				elseif($wgtstat == 0) { $pst_field .= "status=1;"; }
			}

			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			
			mysqlpost('update',$params);

		} elseif($ftask === 'item-status') {

			$params['body'] = "Record status changed successfully";
			$params['tbls'] = $_POST['xtbl'];
			$params['loop'] = 2;
			$pst_field = "";
			$pst_query = "";
			
			foreach($_POST['checkers'] as $row) {
				$wgtstat = idget_name($row,'status',$_POST['xtbl']);
				$pst_query .= "id={$row};";
				if($wgtstat == 'Active') { $pst_field .= "status='InActive';"; }
				elseif($wgtstat == 'InActive') { $pst_field .= "status='Active';"; }
			}

			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			
			mysqlpost('update',$params);

		} elseif($ftask === 'pos-stock') {

			$pst_tbl = $_POST['xtbl'];
			$store_to_check = isset($_POST['checkers']) ? $_POST['checkers'] : "";
			$row = 0; $label = "";
			
			if(!empty($store_to_check)) {
				
				for($r=0; $r < count($store_to_check); $r++) {
					if($r == 0) {
						$row = $store_to_check[$r];
						$label = idget_name($row,'store_name',$mtbL10);
						
						break;
					}
				}

				//$sql_param = substr_replace($sql_param,'',-1,1); $label = substr_replace($label,'',-1,1);
				$title = "Stock for ".$label;
				include "show_pos_stock.php";
			}

		} elseif($ftask === 'for-bad-and-damage') {

			$pst_tbl = $_POST['xtbl'];
			$onlybad = isset($_POST['checkers']) ? $_POST['checkers'] : "";
			$row = 0; $label = "";
			
			if(!empty($onlybad)) {
				
				$bnd_number = prgSequence($tbL155,'BND');

				for($r=0; $r < count($onlybad); $r++) {
					$itemid = idget_name($onlybad[$r],'itemid',$tbL156);
					$uom = idget_name($onlybad[$r],'uom',$tbL156);
					$qty = idget_name($onlybad[$r],'qty_transfer',$tbL156);

					$unitprice = idget_fname($itemid,'itemid','unitprice',$mtbL19);
					$total_cost = $unitprice * $qty;

					$pst_query = "bnd_number='{$bnd_number}' AND itemid={$itemid} AND bnd_status='Pending'";
					$pst_field = "bnd_number='{$bnd_number}',itemid={$itemid},stock='{$qty}',uom={$uom},unitprice='{$unitprice}',total_cost='{$total_cost}',bnd_status='Under Approval',userid={$userSignedIn},datelogged='{$server_get_date}',timelogged='{$server_get_time}'";
					mysqli_data_insert($tbL157,$pst_field,$pst_query);

					$pst_query = "id={$onlybad[$r]}";
					$pst_field = "isbad=1";
					mysqli_data_update($tbL156,$pst_field,$pst_query);

					$itemid=""; $uom=""; $qty=""; $total_cost=0;
				}

				#send notification to cost controllers
				$message_title = "Approval for Bad/Damage Item (".$bnd_number.")";
				$sendmessage = 'The following bad or damage items ('.$bnd_number.') need approval to move stock. Click <a href="javascript:void(0)" class="blue-font" name="'.$bnd_number.'" onclick="jpbnd(this.name)"><u>here</u></a> to acknowledge';
				
				$workflow = isset($_POST['workflow']) ? $_POST['workflow'] : 0;
				$users = getuser4_notification(12,$workflow);
				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>20,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);

				if(is_array($users) && count($users) > 0) {
						
					$joblevel = count($users);
					$pst_query = "subject='{$bnd_number}' AND approval_type='BND'";
					
					if(isset($joblevel) && $joblevel == 1) {
						$pst_field = "job_level=1,subject='{$bnd_number}',user_one={$users[0]['id']},approval_type='BND'";
					} elseif(isset($joblevel) && $joblevel == 2) {
						$pst_field = "job_level=2,subject='{$bnd_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='BND'";
					} elseif(isset($joblevel) && $joblevel == 3) {
						$pst_field = "job_level=3,subject='{$bnd_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='BND'";
					} elseif(isset($joblevel) && $joblevel == 4) {
						$pst_field = "job_level=4,subject='{$bnd_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='BND'";
					} elseif(isset($joblevel) && $joblevel == 5) {
						$pst_field = "job_level=5,subject='{$bnd_number}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='BND'";
					}


					$params['body'] = "Bad/damage items sent for approval";
					$params['tbls'] = $tbL151;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost('insert',$params);
				}
			}
		}

	}


	#for return pop page or get page

	if(isset($_GET['curi']) && !empty($_GET['curi'])) {

		$params = array(); $log = array();
		$params['header'] = "Notification";
		
		$curi = $_GET['curi'];

		$entry = "";
		$pst_query = "";
		$pst_field = "";

		if($curi === 'delete-record') {
			
			$id = remove_data_injection($_GET['lit']);
			$tbl = remove_data_injection($_GET['tbl']);

			$pst_query = "id={$id}";
			
			$params['body'] = "Record deleted successfully";
			$params['tbls'] = $tbl;
			$params['datasets'] = "";
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('delete',$params);

		} elseif($curi === 'qr-delete-record') {
			
			$tbl = remove_data_injection($_GET['tbl']);

			$pst_query = $_GET['sql'];
			
			$params['body'] = "Record deleted successfully";
			$params['tbls'] = $tbl;
			$params['datasets'] = "";
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('delete',$params);

		}/* elseif($curi === 'pr-approval-request') {
			
			createDatabasetable($var_tbl_150);
			$tbl = $tbL151;

			#get all pending PR
			$isSql = "SELECT order_number FROM {$mtbL8} WHERE order_status IN('Pending') AND gstat IN('Pending') AND deletedata=0 LIMIT 1";
			$wgtQy = idget_data($isSql);

			if(!empty($wgtQy[0]['order_number'])) {
				
				$message_title = "Purchase Request Approval for (".$wgtQy[0]['order_number'].")";
				$sendmessage = 'The following purchase request order number ('.$wgtQy[0]['order_number'].') is up for approval. Please click <a href="javascript:void(0)" class="blue-font" name="'.$wgtQy[0]['order_number'].'" onclick="jpson(this.name)"><u>here</u></a> to acknowledge';

				$users = getuser4_notification(6);
				$message_params = array(
					"subject"=>$message_title,
					"sender"=>2,
					"receiver"=>$users,
					"message"=>$sendmessage,
					"priority"=>1,
					"msgtype"=>16,
					"datelogged"=>$server_get_date,
					"timelogged"=>$server_get_time
				);

				inboxmsg($message_params);

				if(is_array($users) && count($users) > 0) {
					
					$joblevel = count($users);
					$pst_query = "subject='{$wgtQy[0]['order_number']}' AND approval_type='PR'";
					
					if(isset($joblevel) && $joblevel == 1) {
						$pst_field = "job_level=1,subject='{$wgtQy[0]['order_number']}',user_one={$users[0]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 2) {
						$pst_field = "job_level=2,subject='{$wgtQy[0]['order_number']}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 3) {
						$pst_field = "job_level=3,subject='{$wgtQy[0]['order_number']}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 4) {
						$pst_field = "job_level=4,subject='{$wgtQy[0]['order_number']}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='PR'";
					} elseif(isset($joblevel) && $joblevel == 5) {
						$pst_field = "job_level=5,subject='{$wgtQy[0]['order_number']}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='PR'";
					}


					$params['body'] = "The current PR has been sent for approval successfully";
					$params['tbls'] = $tbl;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost('insert',$params);

					$pst_query = "order_number='{$wgtQy[0]['order_number']}' AND order_status='Pending' AND gstat='Pending'";
					$pst_field = "gstat='Confirm'";

					$params['body'] = "";
					$params['tbls'] = $mtbL8;
					$params['datasets'] = $pst_field;
					$params['constrains'] = $pst_query;
					$params['loop'] = 1;
					
					mysqlpost('update',$params);
				}
			}

		} elseif($curi === 'pr-pay-request') {
			
			$orderno = remove_data_injection($_GET['pr']);

			#create an PAY record for this PR
			createDatabasetable($var_tbl_156);
			$pay_no = prgSequence($tbL155,'PAYM');

			$pst_query = "pay_no='{$pay_no}'";
			$pst_field = "pay_no='{$pay_no}',pr_no='{$orderno}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$params['body'] = "";
			$params['tbls'] = $tbL154;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('insert',$params);
			#--end here

			$message_title = "Fund Disburstment for Purchase Request (".$orderno.")";
			$sendmessage = 'The following purchase request order number ('.$orderno.') is now set for fund disburstment. Please go to your portal to attend to this request';

			$users = getuser4_notification(8);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>18,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			$pst_query = "order_number='{$orderno}'";
			$pst_field = "pr_status='Payment Inview'";

			$params['body'] = "PR is now set for fund disburstment";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('update',$params);
			
		} elseif($curi === 'pr-iou-approval') {
			
			$orderno = remove_data_injection($_GET['pr']);

			#create an IOU record for this PR
			createDatabasetable($var_tbl_154);
			$iou_no = prgSequence($tbL155,'IOU');

			$pst_query = "iou_no='{$iou_no}'";
			$pst_field = "iou_no='{$iou_no}',pr_no='{$orderno}',datelogged='{$server_get_date}',timelogged='{$server_get_time}'";

			$params['body'] = "";
			$params['tbls'] = $tbL153;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('insert',$params);
			#--end here

			$message_title = "Approval for IOU number (".$iou_no.")";
			$sendmessage = 'The following IOU number ('.$iou_no.') needs your authorization to complete a PR transaction. Please click <a href="javascript:void(0)" class="blue-font" name="'.$orderno.'" onclick="jpson_iou(this.name)"><u>here</u></a> to acknowledge';

			$users = getuser4_notification(3);
			$message_params = array(
				"subject"=>$message_title,
				"sender"=>2,
				"receiver"=>$users,
				"message"=>$sendmessage,
				"priority"=>1,
				"msgtype"=>9,
				"datelogged"=>$server_get_date,
				"timelogged"=>$server_get_time
			);

			inboxmsg($message_params);

			if(is_array($users) && count($users) > 0) {
					
				$joblevel = count($users);
				$pst_query = "subject='{$orderno}' AND approval_type='IOU'";
				
				if(isset($joblevel) && $joblevel == 1) {
					$pst_field = "job_level=1,subject='{$orderno}',user_one={$users[0]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 2) {
					$pst_field = "job_level=2,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 3) {
					$pst_field = "job_level=3,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 4) {
					$pst_field = "job_level=4,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},approval_type='IOU'";
				} elseif(isset($joblevel) && $joblevel == 5) {
					$pst_field = "job_level=5,subject='{$orderno}',user_one={$users[0]['id']},user_two={$users[1]['id']},user_three={$users[2]['id']},user_four={$users[3]['id']},user_five={$users[4]['id']},approval_type='IOU'";
				}


				$params['body'] = "";
				$params['tbls'] = $tbL151;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('insert',$params);


				$pst_query = "order_number='{$orderno}'";
				$pst_field = "pr_status='IOU'";

				$params['body'] = "PR is now transferred for IOU process";
				$params['tbls'] = $mtbL8;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('update',$params);
			}
			
		}*/ elseif($curi === 'apply-no-variance') {

			$orderno = remove_data_injection($_GET['pr']);

			#--update iou fund table
			#--check if PR initially set for IOU process

			$query_po = "pr_no='{$orderno}' AND deletedata=0";
			$pr2IOU = mysqli_data_exist($tbL153,$query_po);

			if($pr2IOU['isdata'] == true) {
				
				$stock_var = "SELECT SUM(order_net_amount) AS 'totalpramt' FROM $mtbL8 WHERE order_number='{$orderno}'";
				$get_stock_var = idget_data($stock_var);

				$grand_total_amount = $get_stock_var[0]['totalpramt'];

				$pst_query = "pr_no='{$orderno}'";
				$pst_field = "pr_amount='{$grand_total_amount}'";

				$params['body'] = "";
				$params['tbls'] = $tbL153;
				$params['datasets'] = $pst_field;
				$params['constrains'] = $pst_query;
				$params['loop'] = 1;
				
				mysqlpost('update',$params);
			}

			#--end

			$pst_query = "order_number='{$orderno}' AND order_status='Approved'";
			$pst_field = "var_status=2,var_approval='Yes'";

			$params['body'] = "No stock variances applied. Stock can now be received";
			$params['tbls'] = $mtbL8;
			$params['datasets'] = $pst_field;
			$params['constrains'] = $pst_query;
			$params['loop'] = 1;
			
			mysqlpost('update',$params);
		}
	}

?>