<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_;

	include "../includes/uom.php";
	include "../includes/common_data_vars.php";
	include "../includes/notificationlist.php";
	//include "../includes/pos_common_data.php";

	
	if(isset($_GET['dataSend']) && $_GET['dataSend'] == 200)
	{
		if(isset($_GET['r']) && $_GET['r'] == 'check-for-night-audit')
		{
			$nw_htmlresult = 'NotStartingYet';

	    	$cpn_constrain = array("deletedata"=>0);
		    $cpn_data = mysqli_data_fetch('night_audit_ini_tbl','start_audit',$cpn_constrain,'noarray');

		    if(is_array($cpn_data) && count($cpn_data) > 0) {
		    	$nw_htmlresult = $cpn_data[0];
			}
		
		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-shift-users')
		{
			$nw_htmlresult = "";
			$dataid_query = escape_data($_GET['data']);

			$additionalQuery = " GROUP BY userid";
	    	$cpn_constrain = array("shiftid"=>$dataid_query,"deletedata"=>0);
		    $cpn_data = mysqli_data_fetch($tbL23,'userid',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>All</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		$staffname = idget_data($tbL7,$value['userid'],'staffname');
		    		$nw_htmlresult .='<option value="'.$value['userid'].'">'.$staffname.'</option>';
		    		$staffname = "";
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Users</option>';
			}
		
		    echo $nw_htmlresult;
		}


		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-module-category')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("moduleid"=>$dataid_query,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL10,'id,category',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Category</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['category'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Category!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-department-role')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("departmentid"=>$dataid_query,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL4,'id,role',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose Role</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['role'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-blocks-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL49,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Blocks</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-roomtype-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL52,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Room Types</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-block-floors-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("blockid"=>$dataid_query,"deletedata"=>0);
		    $cpn_data = mysqli_data_fetch($tbL50,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Floors</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-country-states-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("country_id"=>$dataid_query);
	    	$additionalQuery = " ORDER BY name ASC";
		    $cpn_data = mysqli_data_fetch($tbL65,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>State</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-season-mode-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL78,'id,legendname',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Modes</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['legendname'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-product-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	//$cpn_constrain = array("postoreid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
	    	$additionalQuery = " ORDER BY item ASC";
	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL118,'id,item',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['item'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-pos-category-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	if($dataid_query > 0) {
	    		$cpn_constrain = array("postoreid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		    	$cpn_data = mysqli_data_fetch($tbL15,'id,program_id,category',$cpn_constrain,'array');
	    	} else {
	    		$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		   		$cpn_data = mysqli_data_fetch($tbL115,'id,category',$cpn_constrain,'array');
	    	}
	    	

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		$nw_htmlresult .= '<option value="'.$value['id'].'">'.$value['category'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}


		if(isset($_GET['r']) && $_GET['r'] == 'eget-pos-category-list-x')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("postoreid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL15,'id,program_id,category',$cpn_constrain,'array');
	    	
		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		if($value['program_id'] != 2) {
	    				$nw_htmlresult .= '<option value="'.$value['id'].'">'.$value['category'].'</option>';
	    			}
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		if(isset($_GET['r']) && $_GET['r'] == 'eeget-category-list')
		{
	    	$dataid_query = escape_data($_GET['data']);
	    	$ctgid = idget_data($tbL118,$dataid_query,'categoryid');
	    	$ctgname = idget_data($tbL115,$ctgid,'category');
	    	
		    $nw_htmlresult = '<option value="'.$ctgid.'" selected>'.$ctgname.'</option>';

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-sub-category-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	if(isset($_SESSION['postoreid']) && $_SESSION['postoreid'] > 0) {
	    		$cpn_constrain = array("postoreid"=>$_SESSION['postoreid'],"categoryid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		    	$cpn_data = mysqli_data_fetch($tbL92,'id,subcategory',$cpn_constrain,'array');
	    	} else {
	    		$cpn_constrain = array("categoryid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		   		$cpn_data = mysqli_data_fetch($tbL116,'id,subcategory',$cpn_constrain,'array');
	    	}

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['subcategory'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		if(isset($_GET['r']) && $_GET['r'] == 'eeget-sub-category-list')
		{
	    	$dataid_query = escape_data($_GET['data']);
	    	$subid = idget_data($tbL118,$dataid_query,'subcategoryid');
	    	$subname = idget_data($tbL116,$subid,'subcategory');
	    	
		    $nw_htmlresult = '<option value="'.$subid.'" selected>'.$subname.'</option>';

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eeget-item-cost-list')
		{
	    	$dataid_query = escape_data($_GET['data']);

	    	$additionalQuery = " ORDER BY id DESC LIMIT 1";
	    	$cpn_constrain = array("itemid"=>$dataid_query,"deletedata"=>0);
		    $cpn_data = mysqli_data_fetch('item_cost_centre_tbl','costprice',$cpn_constrain,'noarray');

		    if(!empty($cpn_data[0]) && $cpn_data[0] != '') { $nw_htmlresult = $cpn_data[0]; }
		    else { $nw_htmlresult = 0; }

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-supplier-list')
		{
	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL114,'id,supplier_name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['supplier_name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-uom-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	//include "../includes/uom.php";
	    	$nw_htmlresult = $list_uoms;
	    	
		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-pos-products-button')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);
	    	$cur_pos_store_id = $_SESSION['postoreid'];

	    	if(isset($dataid_query)) {
	    		$additionalQuery = " AND storagetype IN('directsales','consumable')";
	    		if($dataid_query == 999) {
	    			$posproductkey = array("deletedata"=>0,"isfeature"=>"Yes","postoreid"=>$cur_pos_store_id);
	    		} elseif($dataid_query == 888) {
	    			$posproductkey = array("deletedata"=>0,"isstaff"=>"Yes","postoreid"=>$cur_pos_store_id);
	    		} else {
	    			$posproductkey = array("deletedata"=>0,"subcategoryid"=>$dataid_query,"postoreid"=>$cur_pos_store_id);
	    		}

	    		$dataproperty = "id,categoryid,subcategoryid,itemcode,item,storagetype,stockin,balance,uom,price,isstaff";
				$posproducts = mysqli_data_fetch($tbL16,$dataproperty,$posproductkey,'array');

				if(is_array($posproducts)) {
					
					$select_uom = ''; $pushItem = ''; $bill_type = "'billtype'"; $bill_acct = "'billacct'";

					foreach ($posproducts as $pskey => $psvalue) {
						
						$select_uom = get_uom($psvalue['uom']);
						$pushItem = "'".$psvalue['id']."==".$psvalue['item']."==".$psvalue['itemcode']."==".$psvalue['price']."==".$psvalue['isstaff']."==".$psvalue['storagetype']."==".$psvalue['balance']."'";
						
						if($psvalue['storagetype'] == 'consumable' && $psvalue['balance'] > 0) {
							$nw_htmlresult .= '<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-150 cs-height-120 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; '.number_format($psvalue['price'],2).'" onclick="pushItem('.$bill_type.','.$bill_acct.','.$pushItem.')">';
							$nw_htmlresult .= '<h4 class="large nobold default-text-font-bold">'.$psvalue['item'].'</h4><small class="block-element top-push-3">&#8358; '.number_format($psvalue['price'],2).'</small><small class="block-element top-push-3 ft-xxsml-size light-grey-font">per '.$select_uom.'</small></span>';
						} else {
							if($psvalue['storagetype'] == 'directsales') {
								$nw_htmlresult .= '<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-150 cs-height-120 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; '.number_format($psvalue['price'],2).'" onclick="pushItem('.$bill_type.','.$bill_acct.','.$pushItem.')">';
								$nw_htmlresult .= '<h4 class="large nobold default-text-font-bold">'.$psvalue['item'].'</h4><small class="block-element top-push-3">&#8358; '.number_format($psvalue['price'],2).'</small><small class="block-element top-push-3 ft-xxsml-size light-grey-font">per '.$select_uom.'</small></span>';
							}
						}
					}

					
					$nw_htmlresult .= '<span class="block-element new-line-space"></span>';
					
				} else {
					
					$nw_htmlresult .= '<small class="block-element top-push-50 dark-grey-font alignct">No products list</small>';
				}
	    	}

	    	echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'search-pos-products-button')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);
	    	$cur_pos_store_id = $_SESSION['postoreid'];

	    	$additionalQuery = " AND item REGEXP '{$dataid_query}?'";
    		$posproductkey = array("deletedata"=>0,"postoreid"=>$cur_pos_store_id);
    		$dataproperty = "id,categoryid,subcategoryid,itemcode,item,storagetype,stockin,balance,uom,price,isstaff";
			$posproducts = mysqli_data_fetch($tbL16,$dataproperty,$posproductkey,'array');

			if(is_array($posproducts)) {
				
				$select_uom = ''; $pushItem = ''; $bill_type = "'billtype'"; $bill_acct = "'billacct'";

				foreach ($posproducts as $pskey => $psvalue) {
					
					$select_uom = get_uom($psvalue['uom']);
					$pushItem = "'".$psvalue['id']."==".$psvalue['item']."==".$psvalue['itemcode']."==".$psvalue['price']."==".$psvalue['isstaff']."==".$psvalue['storagetype']."==".$psvalue['balance']."'";
					
					if($psvalue['storagetype'] == 'consumable' && $psvalue['balance'] > 0) {
						$nw_htmlresult .= '<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-140 cs-height-80 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; '.number_format($psvalue['price'],2).'" onclick="pushItem('.$bill_type.','.$bill_acct.','.$pushItem.')">';
						$nw_htmlresult .= '<h4 class="large nobold default-text-font-bold">'.$psvalue['item'].'</h4><small class="block-element top-push-3">&#8358; '.number_format($psvalue['price'],2).'</small><small class="block-element top-push-3 ft-xxsml-size light-grey-font">per '.$select_uom.'</small></span>';
					} else {
						if($psvalue['storagetype'] == 'directsales') {
							$nw_htmlresult .= '<span class="ln-display-box float-left right-push-7 bottom-push-7 cs-width-140 cs-height-80 noscroll blue-white-state pads10 alignct anchor" title="@ &#8358; '.number_format($psvalue['price'],2).'" onclick="pushItem('.$bill_type.','.$bill_acct.','.$pushItem.')">';
							$nw_htmlresult .= '<h4 class="large nobold default-text-font-bold">'.$psvalue['item'].'</h4><small class="block-element top-push-3">&#8358; '.number_format($psvalue['price'],2).'</small><small class="block-element top-push-3 ft-xxsml-size light-grey-font">per '.$select_uom.'</small></span>';
						}
					}
				}

				
				$nw_htmlresult .= '<span class="block-element new-line-space"></span>';
				
			} else {
				
				$nw_htmlresult .= '<small class="block-element top-push-50 dark-grey-font alignct">No related search! Try again</small>';
			}
	    	

	    	echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-biller-account')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);
	    	
	    	
	    	if(isset($dataid_query) && $dataid_query == 1) {
	    		$forGuest = "'individual'";
	    		$nw_htmlresult .= '<select name="billacct" id="billacct" required="required" onchange="changeGuest(this,'.$forGuest.')">';
	    		$nw_htmlresult .= '<option value="Instant Payment">Individual</option>';
	    		$nw_htmlresult .= '</select>';
	    	} elseif(isset($dataid_query) && $dataid_query == 2) {
	    		$forGuest = "'toroom'";
	    		$nw_htmlresult .= '<select name="billacct" id="billacct" required="required" onchange="xchangeGuest(this.id)">';
	    		$nw_htmlresult .= '<option value="" selected>Choose</option>';

	    		$dtkey = array("status"=>"CheckedIn","deletedata"=>0);
				$get_data = mysqli_data_fetch($tbL127,'customerid,roomid,booking_number',$dtkey,'array');
				
				if(is_array($get_data)) {
					$this_room_prefix = ""; $this_room_number = ""; $this_room_sufix = "";
					foreach ($get_data as $dtskey => $dtsvalue) {
						
						$isbill2room = idget_fdata($tbL130,'booking_number',$dtsvalue['booking_number'],'isbill_to_room');

						if($isbill2room == 'Yes') {
							$this_room_prefix = idget_data($tbL56,$dtsvalue['roomid'],'roomprefix');
							$this_room_number = idget_data($tbL56,$dtsvalue['roomid'],'roomnumber');
							$this_room_sufix = idget_data($tbL56,$dtsvalue['roomid'],'roomsuffix');
							$nw_htmlresult .= '<option value="'.$dtsvalue['customerid'].'-'.$dtsvalue['roomid'].'">'.$this_room_prefix.$this_room_number.$this_room_sufix.'</option>';
						}

						$isbill2room = "";
					}
				} else {
					$nw_htmlresult .= '<option value="">n/a</option>';
				}

	    		$nw_htmlresult .= '</select>';
	    	} elseif(isset($dataid_query) && $dataid_query == 3) {
	    		$forGuest = "'complimentary'";
	    		$nw_htmlresult .= '<select name="billacct" id="billacct" required="required" onchange="changeGuest(this,'.$forGuest.')">';
	    		$nw_htmlresult .= '<option value="" selected="selected">Choose</option>';
				
				$dtkey = array("deletedata"=>0,"status"=>"Active");
				$get_data = mysqli_data_fetch($tbL33,'id,name',$dtkey,'array');
				
				if(is_array($get_data)) {
					foreach ($get_data as $dtskey => $dtsvalue) {
						$nw_htmlresult .= '<option value="'.$dtsvalue['id'].'">'.$dtsvalue['name'].'</option>';
					}
				}

				$nw_htmlresult .= '</select>';
	    	} elseif(isset($dataid_query) && $dataid_query == 4) {
	    		$forGuest = "'group'";
	    		$nw_htmlresult .= '<select name="billacct" id="billacct" required="required" onchange="changeGuest(this,'.$forGuest.')">';
	    		$nw_htmlresult .= '<option value="" selected="selected">Choose</option>';
				
				$additionalQuery = " ORDER BY name ASC";
				$dtkey = array("deletedata"=>0,"status"=>"Active");
				$get_data = mysqli_data_fetch($tbL58,'id,name',$dtkey,'array');
				
				if(is_array($get_data)) {
					foreach ($get_data as $dtskey => $dtsvalue) {
						$nw_htmlresult .= '<option value="'.$dtsvalue['id'].'">'.$dtsvalue['name'].'</option>';
					}
				}

				$nw_htmlresult .= '</select>';
	    	} elseif(isset($dataid_query) && $dataid_query == 5) {
	    		$forGuest = "'staff'";
	    		$nw_htmlresult .= '<select name="billacct" id="billacct" required="required" onchange="changeGuest(this,'.$forGuest.')">';
	    		$nw_htmlresult .= '<option value="" selected="selected">Choose</option>';
				
				$dtkey = array("deletedata"=>0,"status"=>"Active","uaccess"=>"limited");
				$get_data = mysqli_data_fetch($tbL7,'id,staffname,department',$dtkey,'array');
				
				if(is_array($get_data)) {
					$department_name = '';
					foreach ($get_data as $dtskey => $dtsvalue) {
						$department_name = idget_data($tbL12,$dtsvalue['department'],'department');
						$nw_htmlresult .= '<option value="'.$dtsvalue['id'].'">'.$dtsvalue['staffname'].' ('.$department_name.')</option>';
					}
				}

				$nw_htmlresult .= '</select>';
	    	}

	    	echo $nw_htmlresult;
	    }

	    #----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['jmsg']) && $_GET['jmsg'] == 'get-room-access-service')
		{
			$dataid_query = escape_data($_GET['jstring']);
			$cur_pos_store_id = $_SESSION['postoreid'];

			$additionalQuery = " AND isbill_to_room='Yes' AND FIND_IN_SET($cur_pos_store_id,billing_services)";
			$dtkey = array("id"=>$dataid_query);
			$result = mysqli_data_checkr($tbL102,'(*)',$dtkey);

			if($result == true) {
				$nw_htmlresult = 1;
			} else {
				$nw_htmlresult = 0;
			}

			echo trim($nw_htmlresult);
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['jmsg']) && $_GET['jmsg'] == 'get-corporate-pos-package')
		{
			$dataid_query = escape_data($_GET['jstring']);
			$cur_pos_store_id = $_SESSION['postoreid'];

			$dtkey = array("cspgid"=>$dataid_query,"posid"=>$cur_pos_store_id);
			$get_data = mysqli_data_fetch($tbL61,'id',$dtkey,'noarray');

			if(isset($get_data[0]) && $get_data[0] >= 1) {
				$nw_htmlresult = 1;
			} else {
				$nw_htmlresult = 0;
			}

			echo trim($nw_htmlresult);
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['jmsg']) && $_GET['jmsg'] == 'get-cur-biller')
		{
			$dataid_query = escape_data($_GET['jstring']);
			
			$dtkey = array("order_number"=>$dataid_query);
			$get_data = mysqli_data_fetch($tbL100,'biller,billtype,booking_number',$dtkey,'noarray');

			if(isset($get_data[0]) && $get_data[0] > 0) {
				$myObj->biller = $get_data[0];
				$myObj->account = $get_data[1];
			} else {
				$myObj->biller = 0;
				$myObj->account = $get_data[1];
			}

			$myJSON = json_encode($myObj);
			echo $myJSON;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['jsonclass']) && ($_GET['jsonclass'] == 'frontdesk-list-room-type-detail' || $_GET['jsonclass'] == 'nodetail'))
		{
			$dataid_query = escape_data($_GET['jsonkey']);
			$wktr = escape_data($_GET['wkt']);
			$ctype = escape_data($_GET['ctype']);
			$checkin = escape_data($_GET['checkin']);
			$checkout = escape_data($_GET['checkout']);
			
			if(isset($ctype) && $ctype == 'corporate') {
				if(isset($_GET['cspg']) && $_GET['cspg'] >= 1) {
					
					$ischargetype = idget_data($tbL58,$_GET['cspg'],'chargetype');
					$cspg_discount = idget_data($tbL58,$_GET['cspg'],'discount');

					if($ischargetype == 'Unknown') { $gpr = "yes"; $cdiscount = 0; }
					elseif($ischargetype == 'On Discount') { $gpr = "yes"; $cdiscount = $cspg_discount; }
					elseif($ischargetype == 'Corporate Tariff') { $gpr = "no"; $cdiscount = $cspg_discount; }

					$dataproperty = "detail,adult,child,defaultprice,baseprice,extrabedprice,noofrooms,maxallow,ismandatory_minimum_deposit,issmoking,isextrabed,minimumdeposit,childfare";
					$json_constrain = array("id"=>$dataid_query);
					$room_types = mysqli_data_fetch($tbL52,$dataproperty,$json_constrain,'noarray');

					$additionalQuery = " AND roomid > 0 AND room_status_id IN(3,6)";
					$hkkey = array("room_type"=>$dataid_query);
					mysqli_data_check($tbL94,'(*)',$hkkey);
					
					if($numOfrows > 0) { $actual_room_left = $room_types[6] - $numOfrows; }
					else { $actual_room_left = $room_types[6]; }

					if($actual_room_left == 0) {
						$additionalQuery = " AND endate <= '{$checkin}'";
						$hkkey = array("room_type"=>$dataid_query,"room_status_id"=>3);
						mysqli_data_check($tbL94,'(*)',$hkkey);
						$actual_room_left = $numOfrows;
					} else {
						$actual_room_left = $actual_room_left;
					}

					$additionalQuery = "";

					$fkey = array("room_type_id"=>$dataid_query);
					$room_facilities = mysqli_data_fetch($tbL55,'amenityid',$fkey,'array');

					if(is_array($room_types)) {

						if(is_array($room_facilities)) {
							$r_facilities = '';
							foreach ($room_facilities as $fc_key => $fc_value) {
								$r_facilities .= idget_data($tbL13,$fc_value['amenityid'],'name').', ';
							}
						} else {
							$r_facilities = '';
						}

						$myObj->detail = $room_types[0];
						$myObj->adult = $room_types[1];
						$myObj->child = $room_types[2];
						$myObj->noofrooms = $actual_room_left;
						$myObj->issmoking = $room_types[9];
						$myObj->minimumdeposit = $room_types[11];
						$myObj->childfare = $room_types[12];
						$myObj->roomfacilities = $r_facilities;
					}

					if($gpr == 'yes') {

						$myObj->discount = $cdiscount;

						if(isset($wktr) && $wktr == 'No') {
							$myObj->price = $room_types[3];
							$myObj->extrabed = $room_types[5];
							$myObj->hotelseason = 0;
							$myObj->hotelseasonday = 'defaultday';
						} elseif(isset($wktr) && $wktr == 'Yes') {
							
							$weekday = date('l',strtotime($server_get_date));
							$weekday = strtolower($weekday);

							$fkey2 = array("room_type_id"=>$dataid_query,"day"=>$weekday,"ratetype"=>"adult rate","status"=>"Active");
							$this_rate_1 = mysqli_data_fetch($tbL80,'id,price,modeid',$fkey2,'noarray');
							
							$fkey3 = array("room_type_id"=>$dataid_query,"day"=>$this_day,"ratetype"=>"extrabed rate","status"=>"Active"); $this_rate_2 = mysqli_data_fetch($tbL80,'id,price',$fkey3,'noarray');

							if((isset($this_rate_1[0]) && isset($this_rate_2[0])) && ($this_rate_1[0] >= 1 && $this_rate_2[0] >= 1)) {
								$myObj->price = $this_rate_1[1];
								$myObj->extrabed = $this_rate_2[1];
								$myObj->hotelseason = $this_rate_1[2];
								$myObj->hotelseasonday = $this_day;
							} else {
								$myObj->price = $room_types[3];
								$myObj->extrabed = $room_types[5];
								$myObj->hotelseason = 0;
								$myObj->hotelseasonday = 'defaultday';
							}
						}

						$myObj->tax = $gh_get_vat;
						$myObj->servicecharge = $gh_get_service_charge;
						$myObj->consumption = $gh_get_consumption_tax;

						$myObj->rsvat = 0;
						$myObj->rsschg = 0;
						$myObj->rsctax = 0;

					} else {
				
						$weekday = date('l',strtotime($server_get_date));
						$weekday = strtolower($weekday);

						$cspg = $_GET['cspg'];
						
						$json_constrain = array("corporateid"=>$cspg,"room_type_id"=>$dataid_query,"ratetype"=>"naira","day"=>$weekday,"status"=>"Active","deletedata"=>0);
						$corporate_price = mysqli_data_fetch($tbL147,'price',$json_constrain,'noarray');

						$cpsg_flat_rate = $corporate_price[0];

						$tax_query3 = array("corporateid"=>$cspg,"room_type_id"=>$dataid_query,"taxid"=>3,"status"=>"Active","deletedata"=>0); $tax_data3 = mysqli_data_fetch($tbL82,'taxid',$tax_query3,'noarray');
						
						/*if(isset($tax_data3[0]) && $tax_data3[0] == 3) { $myObj->tax = 0; }
						else { $myObj->tax = $gh_get_vat; }*/

						if(isset($tax_data3[0]) && $tax_data3[0] == 3) {
							$myObj->tax = $gh_get_vat;
							//$rs_vat = ($gh_get_vat / 100) * $cpsg_flat_rate;
							$rs_vat = $gh_get_vat;
						} else {
							$myObj->tax = $gh_get_vat;
							$rs_vat = 0;
						}

						$tax_query2 = array("corporateid"=>$cspg,"room_type_id"=>$dataid_query,"taxid"=>2,"status"=>"Active","deletedata"=>0); $tax_data2 = mysqli_data_fetch($tbL82,'taxid',$tax_query2,'noarray');

						if(isset($tax_data2[0]) && $tax_data2[0] == 2) {
							$myObj->servicecharge = $gh_get_service_charge;
							//$rs_schg = ($gh_get_service_charge / 100) * $cpsg_flat_rate;
							$rs_schg = $gh_get_service_charge;
						} else {
							$myObj->servicecharge = $gh_get_service_charge;
							$rs_schg = 0;
						}

						$tax_query1 = array("corporateid"=>$cspg,"room_type_id"=>$dataid_query,"taxid"=>1,"status"=>"Active","deletedata"=>0); $tax_data1 = mysqli_data_fetch($tbL82,'taxid',$tax_query1,'noarray');

						if(isset($tax_data1[0]) && $tax_data1[0] == 1) {
							$myObj->consumption = $gh_get_consumption_tax;
							//$rs_ctax = ($gh_get_consumption_tax / 100) * $cpsg_flat_rate;
							$rs_ctax = $gh_get_consumption_tax;
						} else {
							$myObj->consumption = $gh_get_consumption_tax;
							$rs_ctax = 0;
						}

						$accu_taxes = 100 / (100 + $rs_vat + $rs_schg + $rs_ctax);
						$cpsg_flat_rate = $accu_taxes * $cpsg_flat_rate;
						$cpsg_flat_rate = round($cpsg_flat_rate,2);
						//$cpsg_flat_rate = $cpsg_flat_rate - ($rs_vat + $rs_schg + $rs_ctax);

						$myObj->discount = 0;
						$myObj->price = $cpsg_flat_rate;
						$myObj->rsvat = $rs_vat;
						$myObj->rsschg = $rs_schg;
						$myObj->rsctax = $rs_ctax;
						$myObj->extrabed = 0;

						$myObj->hotelseason = 0;
						$myObj->hotelseasonday = 0;
					}
				}

			} else {

				$dataproperty = "detail,adult,child,defaultprice,baseprice,extrabedprice,noofrooms,maxallow,ismandatory_minimum_deposit,issmoking,isextrabed,minimumdeposit,childfare";
				$json_constrain = array("id"=>$dataid_query);
				$room_types = mysqli_data_fetch($tbL52,$dataproperty,$json_constrain,'noarray');

				$additionalQuery = " AND roomid > 0 AND room_status_id IN(3,6)";
				$hkkey = array("room_type"=>$dataid_query);
				mysqli_data_check($tbL94,'(*)',$hkkey);
				
				if($numOfrows > 0) { $actual_room_left = $room_types[6] - $numOfrows; }
				else { $actual_room_left = $room_types[6]; }

				if($actual_room_left == 0) {
					$additionalQuery = " AND endate <= '{$checkin}'";
					$hkkey = array("room_type"=>$dataid_query,"room_status_id"=>3);
					mysqli_data_check($tbL94,'(*)',$hkkey);
					$actual_room_left = $numOfrows;
				} else {
					$actual_room_left = $actual_room_left;
				}

				$additionalQuery = "";

				$fkey = array("room_type_id"=>$dataid_query);
				$room_facilities = mysqli_data_fetch($tbL55,'amenityid',$fkey,'array');

				if(is_array($room_types)) {

					if(is_array($room_facilities)) {
						$r_facilities = '';
						foreach ($room_facilities as $fc_key => $fc_value) {
							$r_facilities .= idget_data($tbL13,$fc_value['amenityid'],'name').', ';
						}
					} else {
						$r_facilities = '';
					}

					$myObj->detail = $room_types[0];
					$myObj->adult = $room_types[1];
					$myObj->child = $room_types[2];
					$myObj->discount = 0;
					
					if(isset($wktr) && $wktr == 'No') {
						$myObj->price = $room_types[3];
						$myObj->extrabed = $room_types[5];
						$myObj->hotelseason = 0;
						$myObj->hotelseasonday = 'defaultday';
					} elseif(isset($wktr) && $wktr == 'Yes') {
						$this_day = strtolower(date("l"));
						$fkey2 = array("room_type_id"=>$dataid_query,"day"=>$this_day,"ratetype"=>"adult rate","status"=>"Active");
						$this_rate_1 = mysqli_data_fetch($tbL80,'id,price,modeid',$fkey2,'noarray');
						$fkey3 = array("room_type_id"=>$dataid_query,"day"=>$this_day,"ratetype"=>"extrabed rate","status"=>"Active");
						$this_rate_2 = mysqli_data_fetch($tbL80,'id,price',$fkey3,'noarray');

						if((isset($this_rate_1[0]) && isset($this_rate_2[0])) && ($this_rate_1[0] >= 1 && $this_rate_2[0] >= 1)) {
							$myObj->price = $this_rate_1[1];
							$myObj->extrabed = $this_rate_2[1];
							$myObj->hotelseason = $this_rate_1[2];
							$myObj->hotelseasonday = $this_day;
						} else {
							$myObj->price = $room_types[3];
							$myObj->extrabed = $room_types[5];
							$myObj->hotelseason = 0;
							$myObj->hotelseasonday = 'defaultday';
						}
					}

					$myObj->tax = $gh_get_vat;
					$myObj->servicecharge = $gh_get_service_charge;
					$myObj->consumption = $gh_get_consumption_tax;
					
					$myObj->noofrooms = $actual_room_left;
					$myObj->issmoking = $room_types[9];
					$myObj->minimumdeposit = $room_types[11];
					$myObj->childfare = $room_types[12];
					$myObj->roomfacilities = $r_facilities;
				}
			}
			
			$myJSON = json_encode($myObj);
			echo $myJSON;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-rooms')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$additionalQuery = " ORDER BY roomnumber ASC";
	    	$cpn_constrain = array("room_type_id"=>$dataid_query,"deletedata"=>0,"roomstatus"=>1);
		    $cpn_data = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$cpn_constrain,'array');
		    $additionalQuery = "";

		    if(is_array($cpn_data))
		    {
		    	$housekeeping_room_state = ''; $room_availability = '';
		    	$nw_htmlresult .='<option value="" selected></option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$hrs_query = array("roomid"=>$value['id']);
		    		$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid,room_status_id',$hrs_query,'noarray');
		    		
		    		if(isset($hrs_data[0]) && $hrs_data[0] >= 1) {
		    			$housekeeping_room_state = '['.idget_data($tbL36,$hrs_data[0],'legendname').']';
		    		} else {
		    			$housekeeping_room_state = '['.$default_housekeeping_legend.']';
		    		}

		    		/*$additionalQuery = " ORDER BY id DESC LIMIT 1";
		    		$ra_query = array("roomid"=>$value['id'],"checkout"=>0,"deletedata"=>0);
		    		$ra_data = mysqli_data_fetch($tbL97,'stateid',$ra_query,'noarray');*/
		    		//do not list rooms that are either checkedin, reserved or temp. reserve
		    		if($hrs_data[1] != 3 && $hrs_data[1] != 6 && $hrs_data[1] != 7) {
		    			$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['roomprefix'].$value['roomnumber'].' '.$housekeeping_room_state.'</option>';
		    		}
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-salutations')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL42,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected></option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-item-subcategory-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("categoryid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL116,'id,subcategory',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Select Sub-Category</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['subcategory'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-item-group-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("subcategoryid"=>$dataid_query,"deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL117,'id,groupname',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Select Item Group</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['groupname'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-occupancy-type')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL51,'id,name',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected></option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'frontdesk-search-data')
		{
			$nw_htmlresult='';
			$nw_htmlresult .= '<h4 class="large nobold"><b>&bull;</b> &nbsp; Search result</h4>';

	    	$keywords = escape_data($_GET['keywords']);
	    	$criteria = escape_data($_GET['src']);

	    	if(isset($criteria) && $criteria == 'Bookings') {
	    		
	    		$hrefLink = "";
	    		
	    		$additionalQuery = " AND booking_number REGEXP '".$keywords."'";
		    	$search_constrain = array("deletedata"=>0);
			    $search_data = mysqli_data_fetch($tbL130,'booking_number',$search_constrain,'array');

			    if(is_array($search_data)) {
			    	
			    	$reservation = "'reservations'";
			    	$salutation_id=""; $salutation=""; $guest_code=""; $guest_name=""; $hrefLink = "";
			    	$guest_id=""; $bkn="";
			    	
			    	foreach ($search_data as $sdkey => $sdvalue) {
			    		$salutation_id = idget_fdata($tbL102,'booking_number',$sdvalue['booking_number'],'salutation');
			    		$salutation = idget_data($tbL42,$salutation_id,'name');
			    		$guest_name = idget_fdata($tbL102,'booking_number',$sdvalue['booking_number'],'fname').' ';
			    		$guest_name .= idget_fdata($tbL102,'booking_number',$sdvalue['booking_number'],'lname');
			    		$guest_code = idget_fdata($tbL102,'booking_number',$sdvalue['booking_number'],'guest_code');
			    		$guest_id = idget_fdata($tbL102,'booking_number',$sdvalue['booking_number'],'id');
			    		$bkn = "'".$sdvalue['booking_number']."'";

			    		$hrefLink = "'workspace.php?logs=new-booking&booking=".$sdvalue['booking_number']."'";
			    		
			    		$nw_htmlresult .= '<span class="ln-display-box float-left right-push-15 top-push-5 bottom-push-10 anchor blue-font" onclick="crframe('.$bkn.','.$guest_id.','.$reservation.')" title="See booking detail">';
			    		$nw_htmlresult .= '<h4 class="large nobold"><b class="steel-blue-font">'.$sdvalue['booking_number'].'</b> ('.$salutation.' '.$guest_name.' - '.$guest_code.$guest_id.')</h4>';
			    		$nw_htmlresult .= '</span>';
			    	}

			    	$nw_htmlresult .= '<span class="block-element new-line-space"></span>';
			    } else {
	    			$nw_htmlresult .= '<small class="block-element dark-grey-font top-push-5 bottom-push-20">There are no related information</small>';
	    		}
	    	}

	    	elseif(isset($criteria) && $criteria == 'Guest') {
	    		
	    		$hrefLink = "";
	    		
	    		$additionalQuery = " AND (guest_code REGEXP '".$keywords."' OR fname REGEXP '".$keywords."' OR lname REGEXP '".$keywords."' OR mobile REGEXP '".$keywords."')";
		    	$search_constrain = array("deletedata"=>0);
		    	$coldataset = "id,guest_code,salutation,fname,lname,mobile,emailaddress,booking_number";
			    $search_data = mysqli_data_fetch($tbL102,$coldataset,$search_constrain,'array');

			    if(is_array($search_data)) {
			    	
			    	$reservation = "'reservations'"; $formdl="'frontdesk'"; $forpg="'guestcoupon'";
			    	$salutation_id=""; $salutation=""; $guest_code=""; $guest_name=""; $guest_id=""; $bkn="";

			    	foreach ($search_data as $sdkey => $sdvalue) {
			    		$salutation_id = $sdvalue['salutation'];
			    		$salutation = idget_data($tbL42,$salutation_id,'name');
			    		$guest_name = $sdvalue['fname'].' '.$sdvalue['lname'];
			    		$guest_code = $sdvalue['guest_code'];
			    		$guest_id = $sdvalue['id'];
			    		$bkn = "'".$sdvalue['booking_number']."'";

			    		$nw_htmlresult .= '<span class="ln-display-box float-left right-push-15 top-push-5 bottom-push-10 anchor black-font">';
			    		$nw_htmlresult .= '<h4 class="large">'.$salutation.' '.$guest_name.' - '.$guest_code.$guest_id.'</h4>';
			    		$nw_htmlresult .= '<div class="block-element top-push-5">';
			    		$nw_htmlresult .= '<input type="hidden" id="p'.$guest_id.'" value="'.$guest_id.'/'.$sdvalue['salutation'].'/'.$salutation.'/'.$sdvalue['fname'].'/'.$sdvalue['lname'].'/'.$sdvalue['mobile'].'/'.$sdvalue['emailaddress'].'">';
			    		//$nw_htmlresult .= '<a href="javascript:void(0)" class="blue-font right-push-15" onclick="addGuest2Bk('.$guest_id.')">Add to booking</a>';
			    		$nw_htmlresult .= '<a href="javascript:void(0)" class="blue-font right-push-15" onclick="crframe('.$bkn.','.$guest_id.','.$reservation.')">See booking details</a>';
			    		$nw_htmlresult .= '<a href="javascript:void(0)" class="blue-font" onclick="popmodalframe('.$formdl.','.$forpg.','.$bkn.','.$guest_id.',1000,1500)">Coupon</a>';
			    		$nw_htmlresult .= '</div>';
			    		$nw_htmlresult .= '</span>';
			    	}

			    	$nw_htmlresult .= '<span class="block-element new-line-space"></span>';
			    	$nw_htmlresult .= '<input type="button" id="addguest" value="add" class="noshow">';
			    } else {
	    			$nw_htmlresult .= '<small class="block-element dark-grey-font top-push-5 bottom-push-20">There are no related information</small>';
	    		}
	    	}

	    	elseif(isset($criteria) && $criteria == 'Rooms') {
	    		
	    		$hrefLink = "";
	    		
	    		$additionalQuery = " AND roomnumber REGEXP '".$keywords."'";
		    	$search_constrain = array("deletedata"=>0);
		    	$coldataset = "id,blockid,floorid,room_type_id,roomprefix,roomnumber,extn";
			    $search_data = mysqli_data_fetch($tbL56,$coldataset,$search_constrain,'array');

			    if(is_array($search_data)) {
			    	
			    	$block_name=""; $floor_name=""; $room_type_name=""; $room_hk_id=""; $room_stat_id=""; $hrefLink="";
			    	$bkn=""; $reservation="";
			    	
			    	foreach ($search_data as $sdkey => $sdvalue) {
			    		
			    		$block_name = idget_data($tbL49,$sdvalue['blockid'],'name');
			    		$floor_name = idget_data($tbL50,$sdvalue['floorid'],'name');
			    		$room_type_name = idget_data($tbL52,$sdvalue['room_type_id'],'name');

			    		$room_hk_id = idget_fdata($tbL94,'roomid',$sdvalue['id'],'housekeeping_stateid');
			    		$room_stat_id = idget_fdata($tbL94,'roomid',$sdvalue['id'],'room_status_id');

			    		if(isset($room_hk_id) && $room_hk_id >= 1) { $room_hk_tag = idget_data($tbL36,$room_hk_id,'legendname'); }
			    		else { $room_hk_tag = $default_housekeeping_legend; }

			    		if(isset($room_stat_id) && $room_stat_id >= 1) { $room_stat_tag = idget_data($tbL38,$room_stat_id,'legendname'); }
			    		else { $room_stat_tag = $default_room_status_legend; }

			    		if(isset($room_stat_id) && ($room_stat_id == 3 || $room_stat_id == 7)) {
			    			$additionalQuery = " AND status IN('CheckedIn','Reserved') ORDER BY id DESC LIMIT 1";
			    			$select_query = array("roomid"=>$sdvalue['id'],"deletedata"=>0);
			    			$select_data = mysqli_data_fetch($tbL127,'booking_number,customerid',$select_query,'noarray');
			    			$bkn = "'".$select_data[0]."'"; $reservation = "'reservations'";
			    			$hrefLink = ' href="javascript:void(0)" class="nobold blue-font" onclick="crframe('.$bkn.','.$select_data[1].','.$reservation.')"';
			    		} else {
			    			$hrefLink = "";
			    			$bkn = ""; $reservation = "";
			    		}
			    		
			    		$nw_htmlresult .= '<span class="ln-display-box float-left right-push-15 top-push-5 bottom-push-10 anchor black-font">';
			    		$nw_htmlresult .= '<h4 class="large nobold"><b>'.$block_name.' - '.$floor_name.'</b> ('.$room_type_name.')</h4>';
			    		$nw_htmlresult .= '<h4 class="large nobold dark-grey-font"><a'.$hrefLink.'>'.$sdvalue['roomprefix'].$sdvalue['roomnumber'].'</a> Status: <u class="black-font">'.$room_stat_tag.'</u>, Housekeeping: <u class="black-font">'.$room_hk_tag.'</u></h4>';
			    		$nw_htmlresult .= '</span>';
			    	}

			    	$nw_htmlresult .= '<span class="block-element new-line-space"></span>';

			    } else {
	    			$nw_htmlresult .= '<small class="block-element dark-grey-font top-push-5 bottom-push-20">There are no related information</small>';
	    		}
	    	}

	    	echo $nw_htmlresult;
	    }

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'formaxadultandchild')
		{
			$dataid_query = escape_data($_GET['data']);
			
			$data_query = array("id"=>$dataid_query);
			$get_data = mysqli_data_fetch($tbL52,'id,adult,child',$data_query,'noarray');

			if(isset($get_data[1]) && $get_data[1] >= 1) {
				
				$myObj->mxadult = $get_data[1];
				$myObj->mxchild = $get_data[2];
				
				$myJSON = json_encode($myObj);

				echo $myJSON;
			} 
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'foractiveguest')
		{
			$dataid_query = escape_data($_GET['data']);
			
			$data_query = array("id"=>$dataid_query);
			$guest_dataproperty = "id,guest_code,salutation,name,mobile,emailaddress,remarks,address,city,state,country,means_of_identification,identification_number,occupation,period_of_stay";
			$get_data = mysqli_data_fetch($tbL102,$guest_dataproperty,$data_query,'noarray');

			if(isset($get_data[0]) && $get_data[0] >= 1) {
				$salutation = idget_data($tbL42,$get_data[2],'name');
				$identity_type = idget_data($tbL37,$get_data[11],'name');
				
				$myObj->guestId = $get_data[0];
				$myObj->guestCode = $get_data[1];
				$myObj->guestSalutationId = $get_data[2];
				$myObj->guestSalutationName = $salutation;
				$myObj->guestName = $get_data[3];
				$myObj->guestMobile = $get_data[4];
				$myObj->guestEmail = $get_data[5];
				$myObj->guestRemark = $get_data[6];
				$myObj->guestAddress = $get_data[7];
				$myObj->guestCity = $get_data[8];
				$myObj->guestState = $get_data[9];
				$myObj->guestCountry = $get_data[10];
				$myObj->guestIdentityTypeId = $get_data[11];
				$myObj->guestIdentityTypeName = $identity_type;
				$myObj->guestIdentificationNumber = $get_data[12];
				$myObj->guestOccupation = $get_data[13];
				$myObj->guestStay = $get_data[14];
				
				$myJSON = json_encode($myObj);
				echo $myJSON;
			} 
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-inclusion')
		{
			$nw_htmlresult = '';
			$nw_htmlresult .= '<div class="block-element">';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"status"=>"Active");
		    $cpn_data = mysqli_data_fetch($tbL83,'id,name,price,posstore,posproduct',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .= '<div class="block-element bottom-push-15">';
		    		$nw_htmlresult .= '<span class="ln-display-box float-left nc-width-10">';
		    		$nw_htmlresult .= '<input type="checkbox" name="inclusions[]" value="'.$value['id'].'">';
		    		$nw_htmlresult .= '</span>';
		    		$nw_htmlresult .= '<span class="ln-display-box float-left nc-width-70">';
		    		$nw_htmlresult .= '<small>'.$value['name'].'</small>';
		    		$nw_htmlresult .= '</span>';
		    		$nw_htmlresult .= '<span class="ln-display-box float-left nc-width-20 left-pull-5">';
		    		$nw_htmlresult .= '<small>&#8358;'.write_amountF($gh_get_decimal_format,$value['price']).'</small>';
		    		$nw_htmlresult .= '</span>';
		    		$nw_htmlresult .= '<span class="block-element new-line-space">';
		    		$nw_htmlresult .= '</span>';
		    		$nw_htmlresult .= '</div>';
		    	}
			}
			else
			{
				$nw_htmlresult .= '<small class="block-element alignct dark-grey-font">No inclusions found</small>';
			}

			$nw_htmlresult .= '</div>';

		    echo $nw_htmlresult;
		}


		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-swap-room-type')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("id"=>$dataid_query,"deletedata"=>0,"roomstatus"=>1);
		    $cpn_data = mysqli_data_fetch($tbL56,'room_type_id,roomprefix,roomnumber',$cpn_constrain,'noarray');

		    $cpn_constrain_2 = array("room_type_id"=>$cpn_data[0]);
		    $cpn_data_2 = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$cpn_constrain_2,'array');

		    if(is_array($cpn_data_2))
		    {
		    	$housekeeping_room_state = ''; $room_availability = '';

		    	$room_type_name = idget_data($tbL52,$cpn_data[0],'name');
		    	$nw_htmlresult .='<h4 class="large nobold left-pull-5">+ Swap Room ('.$cpn_data[1].$cpn_data[2].')</h4>';
		    	$nw_htmlresult .='<div class="top-push-7 bottom-push-10 left-pull-5"><b>'.$room_type_name.'</b></div>';
		    	$nw_htmlresult .='<select name="swapsameroomtype" id="swapsameroomtype" required>';
		    	$nw_htmlresult .='<option value="" selected>Choose</option>';

		    	foreach ($cpn_data_2 as $key => $value) {
		    		
		    		$hrs_query = array("roomid"=>$value['id']);
		    		$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid',$hrs_query,'noarray');
		    		
		    		if(isset($hrs_data[0]) && $hrs_data[0] >= 1) {
		    			$housekeeping_room_state = '['.idget_data($tbL36,$hrs_data[0],'legendname').']';
		    		} else {
		    			$housekeeping_room_state = '['.$default_housekeeping_legend.']';
		    		}

		    		$ra_query = array("roomid"=>$value['id'],"checkout"=>0,"deletedata"=>0);
		    		$ra_data = mysqli_data_fetch($tbL97,'stateid',$ra_query,'noarray');
		    		
		    		if(!isset($ra_data[0]) || empty($ra_data[0])) {
		    			$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['roomprefix'].$value['roomnumber'].' '.$housekeeping_room_state.'</option>';
		    		}
		    	}

		    	$nw_htmlresult .='</select>';
			}
			else
			{
				$nw_htmlresult .='<select name="swapsameroomtype" id="swapsameroomtype" required>Choose</option>';
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
				$nw_htmlresult .='</select>';
			}

		    echo $nw_htmlresult;
		}


		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-up-downgrade-room-type')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);
	    	$data_receive = "'updowngrade-room'"; $data_ses = "'eget-rooms'"; $data_src = "'updowngrade-roomtype'"; $data_type="'dropbox'";

	    	$getroomtype = select_dt_fetch('status','Active',$tbL52,'id','name');

		   	$nw_htmlresult .='<h4 class="large nobold left-pull-5">+ Upgrade & Downgrade Room</h4>';
		   	$nw_htmlresult .='<div class="ln-display-box float-left nc-width-60 right-pull-10 top-push-10">';
		   	$nw_htmlresult .='<select name="updowngrade-roomtype" id="updowngrade-roomtype" onchange="getdata('.$data_receive.','.$data_ses.','.$data_src.','.$data_type.');" required>';
		   	$nw_htmlresult .='<option value="" selected>Choose Room-Type</option>';
		   	$nw_htmlresult .= $getroomtype;
		   	$nw_htmlresult .='</select>';
		   	$nw_htmlresult .='</div>';
		   	$nw_htmlresult .='<div class="ln-display-box float-left nc-width-40 left-pull-10 top-push-10">';
		   	$nw_htmlresult .='<select name="updowngrade-room" id="updowngrade-room" required>';
		   	$nw_htmlresult .='<option value="" selected>Choose Room</option>';
		   	$nw_htmlresult .='</select>';
		   	$nw_htmlresult .='</div>';
		   	$nw_htmlresult .='<div class="block-element new-line-space">';
		   	$nw_htmlresult .='</div>';

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-coupon-balance')
		{
			$dataid_query = escape_data($_GET['data']);
			$coupon_account_name = idget_fdata($tbL129,'coupon_code',$dataid_query,'guest_name');
			$coupon_balance = idget_fdata($tbL129,'coupon_code',$dataid_query,'coupon_amount');
			$expire_date = idget_fdata($tbL129,'coupon_code',$dataid_query,'expires_on');
			
			if(isset($coupon_balance) && $coupon_balance >= 1) {
				if(str_replace('-','',$expire_date) >= str_replace('-','',$server_get_date)) {
					$print_coupon_balance =  write_amountF($gh_get_decimal_format,$coupon_balance);
				} else {
					$print_coupon_balance = write_amountF($gh_get_decimal_format,0);
				}
			} else {
				$print_coupon_balance =  write_amountF($gh_get_decimal_format,0);
			}

			echo '<h4 class="large nobold">'.$coupon_account_name.'</h4><b>&#8358;'.$print_coupon_balance.'</b>';
			echo '<input type="hidden" name="couponbal" value="'.$coupon_balance.'">';
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-supplier-data')
		{
			$dataid_query = escape_data($_GET['data']);
			$supplier_name = idget_data($tbL114,$dataid_query,'supplier_name');
			$mobile = idget_data($tbL114,$dataid_query,'mobile');
			$address = idget_data($tbL114,$dataid_query,'address');
			$city = idget_data($tbL114,$dataid_query,'city');
			$country = idget_data($tbL114,$dataid_query,'country');
			$payment_term = idget_data($tbL114,$dataid_query,'paymenterm');

			$data_arry = array("sname"=>$supplier_name,"mobile"=>$mobile,"address"=>$address,"city"=>$city,"country"=>$country,"term"=>$payment_term);
			$myJSON = json_encode($data_arry);
			echo $myJSON;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-store-data')
		{
			$dataid_query = escape_data($_GET['data']);
			$store_name = idget_data($tbL123,$dataid_query,'store_name');
			$store_number = idget_data($tbL123,$dataid_query,'store_number');
			
			$data_arry = array("sname"=>$store_name,"snumber"=>$store_number);
			$myJSON = json_encode($data_arry);
			echo $myJSON;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'check-rrom-status-bf-booking')
		{
	    	$dataid_query = escape_data($_GET['data']);

    		$hrs_query = array("roomid"=>$dataid_query);
    		$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid',$hrs_query,'noarray');
    		
    		if(isset($hrs_data[0]) && $hrs_data[0] >= 1) {
    			//$get_r_state = idget_data($tbL36,$hrs_data[0],'legendname');
    			if($hrs_data[0] == 1) { $housekeeping_room_state = 1; }
    			else { $housekeeping_room_state = 0; }
    		} else {
    			$housekeeping_room_state = 0;
    		}

    		echo $housekeeping_room_state;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-newpr-item')
		{
	    	$txt_query = escape_data($_GET['data']);

	    	$additionalQuery = " AND (item REGEXP '".$txt_query."' OR itemcode REGEXP '".$txt_query."' OR detail REGEXP '".$txt_query."')";
    		$sql_query = array("deletedata"=>0);
    		$wgtdata = mysqli_data_fetch($tbL118,'id,item,buying_unit,itemcode',$sql_query,'array');
    		
    		$items = array();

    		if(is_array($wgtdata)) {
    			foreach($wgtdata as $key => $val) {
    				$eItem = array();
    				$eItem['dataid'] = $val['id'];
    				$eItem['code'] = $val['itemcode'];
    				$eItem['item'] = $val['item'];
    				$wguom = arrayget_key($uoms,$val['buying_unit']);
    				$eItem['uom'] = $wguom;
    				array_push($items, $eItem);
    				$uom = "";
    			}
    		}

    		echo json_encode($items);
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-item-last-price')
		{
	    	$txt_query = escape_data($_GET['data']);
	    	$txt2_query = escape_data($_GET['altdata']);

	    	$additionalQuery = " ORDER BY id DESC LIMIT 1";
    		$sql_query = array("supplierid"=>$txt2_query,"itemid"=>$txt_query,"deletedata"=>0);
    		$wgtdata = mysqli_data_fetch($tbL121,'unitprice',$sql_query,'noarray');
    		
    		if($wgtdata[0] > 0) { $upr = $wgtdata[0]; }
    		else { $upr = 0; }

    		echo $upr;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'get-audit-init-status')
		{
	    	$night_query = array("audit_date"=>$server_get_date);
			$isnot_init = mysqli_data_checkr($tbL136,'(*)',$night_query);

			$response = array();

			if($isnot_init == true) {
				$get_status = idget_fdata($tbL136,'audit_date',$server_get_date,'status');
				$response['success'] = 200;
				$response['status'] = $get_status;
			} else {
				$response['success'] = 0;
				$response['status'] = null;
			}

			$jsr = json_encode($response);
			echo $jsr;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'unreadnewsfeed') {
			
			$data = escape_data($_GET['data']);

			$datasets = "id,msgtype,subject";
			$additionalQuery = " ORDER BY id DESC LIMIT 1";
			$sql_query = array("receiver"=>$data,"read_status"=>0);
		    $sql_data = mysqli_data_fetch($tbL104,$datasets,$sql_query,'noarray');

		    if(is_array($sql_data)) {
		    	
		    	$receiver = idget_data($tbL7,$data,'staffname');
		    	$msg = "Hello ".$receiver.", kindly check above notification ";

		    	$id = $sql_data[0];
		    	$title = arrayget_key($notify_arry,$sql_data[1]);
		    	$msg = $sql_data[2];
		    	//$msg .= arrayget_key($notify_title,$sql_data[2]);
		    	
		    	$response['success'] = 200;
		    	$response['wgtdatatoken'] = $id;
		    	$response['wgttitle'] = "Rockview Hotels: ".$title;
		    	$response['wgtmsg'] = $msg;
		    } else {
		    	$response['success'] = 0;
		    }
			
		    $doJSON = json_encode($response);
			echo $doJSON;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['kyw']) && $_GET['kyw'] == 'get-uom-label') {
			$uomkey = escape_data($_GET['uom']);
			$label = arrayget_key($uoms,$uomkey);
			echo $label;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

		if(isset($_GET['r']) && $_GET['r'] == 'eget-department-user-list')
		{
			$nw_htmlresult='';

	    	$dataid_query = escape_data($_GET['data']);

	    	$cpn_constrain = array("deletedata"=>0,"department"=>$dataid_query);
		    $cpn_data = mysqli_data_fetch($tbL7,'id,staffname',$cpn_constrain,'array');

		    if(is_array($cpn_data))
		    {
		    	$nw_htmlresult .='<option value="" selected>Choose?</option>';

		    	foreach ($cpn_data as $key => $value) {
		    		
		    		$nw_htmlresult .='<option value="'.$value['id'].'">'.$value['staffname'].'</option>';
		    	}
			}
			else
			{
				$nw_htmlresult .='<option value="" selected>No Option!</option>';
			}

		    echo $nw_htmlresult;
		}

		#----------------------------------------------------------------------------------------------------------------------------------------------end

	}

?>