<?php $smdl = "pos"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create new pos product by clicking <u>new product</u> button. Use <u>add+</u> button to add fields rows
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Product
		</a>
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

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_14); //create a table for this post
		createDatabasetable($var_tbl_88); //create a table for this post

		$fieldset1 = $_POST['staff'];
		$fieldset2 = $_POST['feature'];
		$fieldset3 = $_POST['category'];
		$fieldset4 = $_POST['subcategory'];
		$fieldset5 = $_POST['product'];
		$fieldset6 = 0;
		$fieldset7 = 0;
		$fieldset8 = $_POST['price'];
		$fieldset9 = "";
		
		$isdata = 0; $itemcode = ''; $next_number = '';

		for($r=0; $r < count($fieldset3); $r++)
		{
			$additionalQuery=" ORDER BY id DESC LIMIT 1";
			$get_next_number = mysqli_data_fetch($tbL16,'id','','noarray');

			if(isset($get_next_number[0]) && $get_next_number[0] >= 1) { $next_number = $get_next_number[0] + 1; }
			else { $next_number = 1001; }

			$itemcode = 'PDT'.$next_number;
			$storageid = idget_data($tbL14,$cur_pos_store_id,'store');

			$product_arr = array("storageid"=>$storageid,"storagetype"=>"directsales","postoreid"=>$cur_pos_store_id,"categoryid"=>$fieldset3[$r],"subcategoryid"=>$fieldset4[$r],"itemcode"=>$itemcode,"item"=>ucwords(strtolower($fieldset5[$r])),"stockin"=>0,"uom"=>$fieldset7[$r],"price"=>str_replace(',','',$fieldset8[$r]),"balance"=>0,"detail"=>$fieldset9[$r],"isfeature"=>$fieldset2[$r],"isstaff"=>$fieldset1[$r]);
			$constrain = "";
			$data_inserted = mysqli_data_insert($tbL16,$product_arr,$constrain);

			if(isset($data_inserted) && $data_inserted == 2) {
				
				$product_hs_arr = array("postoreid"=>$cur_pos_store_id,"categoryid"=>$fieldset3[$r],"subcategoryid"=>$fieldset4[$r],"itemcode"=>$itemcode,"item"=>ucwords(strtolower($fieldset5[$r])),"stockin"=>str_replace(',','',$fieldset6[$r]),"uom"=>$fieldset7[$r],"price"=>str_replace(',','',$fieldset8[$r]),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
				mysqli_data_insert($tbL93,$product_hs_arr,'');

				$isdata += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($isdata) && $isdata >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new pos products","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-100">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Product</h3>
						</div>
						
						<div class="block-element sml-rounded-button noscroll">
							<table cellpadding="0" cellspacing="0">
							<tr>
								<th width="50px" class="box-border-thick-right" align="center">&nbsp;</th>
								<th width="70px" class="box-border-thick-right" align="center">Is Staff</th>
								<th width="100px" class="box-border-thick-right" align="center">Is Featured</th>
								<th width="100px" class="box-border-thick-right" align="center">Category</th>
								<th width="100px" class="box-border-thick-right" align="center">SubCategory</th>
								<th width="150px" class="box-border-thick-right" align="center">Product</th>
								<th width="100px" class="box-border-thick-right" align="center">Price (Per 1)</th>
								<!--<th width="60px" class="box-border-thick-right" align="center">Qty</th>
								<th width="80px" class="box-border-thick-right" align="center">Uom</th>
								<th width="100px" class="box-border-thick-right" align="center">Price (Per 1)</th>
								<th width="150px" class="box-border-thick-right" align="center">Description</th>-->
							</tr>
							<tbody id="datasheet"></tbody>
							</table>
						</div>

						<input type="hidden" id="rwcounter" value="0">
						
						

						<div class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-theme white-font anchor top-push-10 float-right rounded-button ft-xxsml-size" onclick="create_product(); objDisplay('ctrlbx'); setTimeout(dodata('select-col-3-','eget-pos-category-list-x','<?php echo $cur_pos_store_id; ?>','dropbox'),1000)">Add +</div><div class="block-element new-line-space"></div>
						<!-- setTimeout(dodata('select-col-5-','eget-uom-list',1,'dropbox'),2000) -->
						<br><br>
						
						<div id="ctrlbx" class="noshow alignct">
							<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL16,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change pos product status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		//$fieldset4 = escape_data($_POST['fieldset4']);
		//$fieldset5 = escape_data($_POST['fieldset5']);
		$fieldset6 = escape_data($_POST['fieldset6']);
		//$fieldset7 = escape_data($_POST['fieldset7']);
		//$fieldset8 = escape_data($_POST['fieldset8']);
		$fieldset9 = escape_data($_POST['fieldset9']);
		$fieldset10 = escape_data($_POST['fieldset10']);

		$fieldset11 = escape_data($_POST['fieldset11']);
	
		$insert_dataproperty = array("categoryid"=>$fieldset1,"subcategoryid"=>$fieldset2,"item"=>ucwords(strtolower($fieldset3)),"stockin"=>0,"price"=>str_replace(',','',$fieldset6),"balance"=>0,"isstaff"=>$fieldset9,"isfeature"=>$fieldset10);
		$insert_constrain = array("id"=>$fieldset11);
		$data_inserted = mysqli_data_update($tbL16,$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			$product_hs_arr = array("categoryid"=>$fieldset3[$r],"subcategoryid"=>$fieldset4[$r],"item"=>ucwords(strtolower($fieldset5[$r])),"stockin"=>str_replace(',','',$fieldset6[$r]),"uom"=>$fieldset7[$r],"price"=>str_replace(',','',$fieldset8[$r]));
			$insert_constrain = array("postoreid"=>$cur_pos_store_id,"itemcode"=>$fieldset10);
				mysqli_data_update($tbL93,$product_hs_arr,$insert_constrain);

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Edit pos product details","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL16,$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove from pos product list","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (item REGEXP '".escape_data($_POST['search'])."?' OR itemcode REGEXP '".escape_data($_POST['search'])."')";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." ORDER BY item ASC LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 100;
		$additionalQuery = $keywords." ORDER BY item ASC LIMIT ".$pgstart.",".$pglimit;
	}

	//$dataproperty = "id,categoryid,subcategoryid,itemcode,item,stockin,uom,price,stockout,balance,detail,isfeature,isstaff,status";
	$dataproperty = "id,categoryid,subcategoryid,itemcode,item,price,isfeature,isstaff,status";
	$constrain = array("deletedata"=>0,"postoreid"=>$cur_pos_store_id,"storagetype"=>"directsales");
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL16,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","category","subcategory","code","product","price (Per 1)","status","noth","enoth");
		$csvheader = array("SN","Category","Subcategory","Code","Product","Price (Per 1)");
		$data_library = array(); $data_row = array();
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<p class="alignrt top-pull-20 bottom-pull-10"><a href="javascript:void(0)" class="blue-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button white-theme obj-light-shadow right-push-10" onclick="window.print()">Print <b class="fa-print left-push-5"></b></a><a href="javascript:void(0)" class="white-font box-border-thick top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button forest-green-theme obj-light-shadow left-push-10" onclick="csvExcel()" title="Export to Excel">Export <b class="fa-share left-push-5"></b></a></p>';
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div id="section-to-print" class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<h1 class="alignct motion">'._LONG_NAME.'</h1>';
		$htmlresult .= '<h3 class="alignct nobold motion">OUTLET NON-BEVERAGE ITEMS</h3>';
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
		
		$num=$pgstart; $g=""; $dataid=""; $category=""; $subcategory=""; $applied_uom=""; $allow_edit=""; $disabled="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$additionalQuery = "";
			$category = idget_data($tbL15,$tdata['categoryid'],'category');
			$subcategory = idget_data($tbL92,$tdata['subcategoryid'],'subcategory');
			//$applied_uom = get_uom($tdata['uom']);

			if($tdata["stockout"] >= 1) { $allow_edit = 0; $disabled=' disabled="disabled"'; } else { $allow_edit = 1; $disabled=''; }
			

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$category.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$subcategory.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["itemcode"].'</td>';
			$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">'.$tdata["item"].'</td>';
			//$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$tdata["stockin"].' '.$applied_uom.'</td>';
			$htmlresult .= '<td width="180px" align="center" class="box-border-thick-right">&#8358; '.number_format($tdata["price"],2).'</td>';
			//$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$tdata["stockout"].' '.$applied_uom.'</td>';
			//$htmlresult .= '<td width="50px" align="center" class="box-border-thick-right">'.$tdata["balance"].' '.$applied_uom.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right">';
			
			if(!isset($allow_edit)) {
				$htmlresult .= '<a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="dark-grey-font">View</a>';
			} else {
				$htmlresult .= '<a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">View/Edit</a>';
			}

			$htmlresult .= '</td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"'.$disabled.'></td>';
			$htmlresult .= '</tr>';

			#----------------------------------------------------------------------------------------------------------------------------------------------------

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$fil_obj="'fieldset2'"; $select_opt="'eget-sub-category-list'"; $select_criteria="'fieldset1'"; $obj_type="'dropbox'";

				$additionalQuery = " AND postoreid={$cur_pos_store_id}";
				$pos_category = select_dt_fetch('status','Active',$tbL15,'id','category');
				//$pos_category = select_dt_fetch('status','Active',$tbL115,'id','category');

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating '.$tdata["item"].' ('.$tdata["itemcode"].')</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Category</small>';
				$htmlresult .= '<select name="fieldset1" id="fieldset1" required="required" onchange="getdata('.$fil_obj.','.$select_opt.','.$select_criteria.','.$obj_type.');">';
				$htmlresult .= '<option value="'.$tdata["categoryid"].'">'.$category.'</option>';
				$htmlresult .= $pos_category;
				$htmlresult .= '<select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Sub Category</small>';
				$htmlresult .= '<select name="fieldset2" id="fieldset2" required="required">';
				$htmlresult .= '<option value="'.$tdata["subcategoryid"].'">'.$subcategory.'</option>';
				$htmlresult .= '<select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Product Name</small>';
				$htmlresult .= '<input type="text" name="fieldset3" id="fieldset3" placeholder="Product name" value="'.$tdata["item"].'" required="required">';
				$htmlresult .= '</span>';
				/*$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Quantity</small>';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-50">';
				$htmlresult .= '<input type="text" name="fieldset4" id="fieldset4" pattern="\d*" placeholder="Quantity" value="'.$tdata["stockin"].'" required="required">';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-40">';
				$htmlresult .= '<select name="fieldset5" id="fieldset5" required="required">';
				$htmlresult .= '<option value="'.$tdata["uom"].'">'.$applied_uom.'</option>';
				$htmlresult .= $list_uoms;
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';*/
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Price (Per Unit)</small>';
				$htmlresult .= '<input type="number" name="fieldset6" id="fieldset6" step="any" placeholder="Price e.g 50" value="'.$tdata["price"].'" required="required">';
				$htmlresult .= '</span>';
				/*$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Description</small>';
				$htmlresult .= '<textarea name="fieldset7" id="fieldset7" placeholder="Enter description">'.$tdata["detail"].'</textarea>';
				$htmlresult .= '</span>';*/
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<div class="ln-display-box float-left nc-width-45">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Staff</small>';
				$htmlresult .= '<select name="fieldset9" id="fieldset9" required="required">';
				$htmlresult .= '<option value="'.$tdata["isstaff"].'">'.$tdata["isstaff"].'</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="ln-display-box float-right nc-width-45">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Is Featured</small>';
				$htmlresult .= '<select name="fieldset10" id="fieldset10" required="required">';
				$htmlresult .= '<option value="'.$tdata["isfeature"].'">'.$tdata["isfeature"].'</option>';
				$htmlresult .= '<option value="No">No</option>';
				$htmlresult .= '<option value="Yes">Yes</option>';
				$htmlresult .= '</select>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element new-line-space"></div>';
				$htmlresult .= '</span>';
				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-push-10 bottom-push-10 alignrt">';
				//$htmlresult .= '<input type="hidden" name="fieldset10" id="fieldset10" value="'.$tdata["itemcode"].'">';
				$htmlresult .= '<input type="hidden" name="fieldset11" id="fieldset11" value="'.$fieldset.'">';

				if(isset($allow_edit)) { $htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp;'; }
				$htmlresult .= '<a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';

				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}

			#----------------------------------------------------------------------------------------------------------------------------------------------------

			$data_row['sn'] = $num;
			$data_row['category'] = $category;
			$data_row['subcategory'] = $subcategory;
			$data_row['itemcode'] = $tdata['itemcode'];
			$data_row['item'] = $tdata['item'];
			$data_row['price'] = number_format($tdata["price"],2);

			array_push($data_library, $data_row);
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';

		if(isset($_SESSION['csvExcel']) || isset($_SESSION['csvHeaders'])) {
			unset($_SESSION['csvExcel']);
			unset($_SESSION['csvHeaders']);
		}

		$_SESSION['csvExcel'] = $data_library;
		$_SESSION['csvHeaders'] = $csvheader;
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
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

<script>

function csvExcel() {
	var curl = filePath;
	window.location = curl+'includes/csv_excel.php';
}

function create_product()
{
	var contr,tr,td1,td2,td3,td4,td5,td6,td7,td8,td9,td10,select1,select2,select3,select4,select5,opt1,opt2,opt3,opt4,opt5,opt6,opt7,opt8,opt9,txt1,txt2,txt3,txt4,txt5,obj,numbr,curnumbr;
	
	curnumbr = document.getElementById('rwcounter');
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
	td10 = document.createElement('td');

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

	txt1 = document.createElement('input');
	txt2 = document.createElement('input');
	txt3 = document.createElement('input');
	txt4 = document.createElement('input');
	txt5 = document.createElement('input');

	obj = document.createElement('span');

	numbr = eval(curnumbr.value) + 1; //generate new row number

	obj.id = 'span'+numbr;
	obj.className = 'block-element alignct';
	obj.innerHTML = numbr;
	td1.appendChild(obj);

	select1.id = 'select-col-1-'+numbr;
	select1.name = 'staff[]';
	select1.required = 'required';
	//opt1.value = '';
	//opt1.text = 'For Staff';
	opt2.value = 'Yes';
	opt2.text = 'Yes';
	opt3.value = 'No';
	opt3.text = 'No';
	//select1.appendChild(opt1);
	select1.appendChild(opt3);
	select1.appendChild(opt2);
	td10.appendChild(select1);

	select2.id = 'select-col-2-'+numbr;
	select2.name = 'feature[]';
	select2.required = 'required';
	//opt4.value = '';
	//opt4.text = 'Is feature';
	opt5.value = 'Yes';
	opt5.text = 'Yes';
	opt6.value = 'No';
	opt6.text = 'No';
	//select2.appendChild(opt4);
	select2.appendChild(opt6);
	select2.appendChild(opt5);
	td2.appendChild(select2);

	select3.id = 'select-col-3-'+numbr;
	select3.name = 'category[]';
	select3.required = 'required';
	opt7.value = '';
	opt7.text = 'Choose';
	select3.appendChild(opt7);
	select3.onchange = function() { getdata('select-col-4-'+numbr,'eget-sub-category-list','select-col-3-'+numbr,'dropbox'); }
	td3.appendChild(select3);

	select4.id = 'select-col-4-'+numbr;
	select4.name = 'subcategory[]';
	select4.required = 'required';
	opt8.value = '';
	opt8.text = 'Choose';
	select4.appendChild(opt8);
	td4.appendChild(select4);

	txt1.type = 'text';
	txt1.name = 'product[]';
	txt1.placeholder = 'Product name';
	txt1.required = 'required';
	td5.appendChild(txt1);

	/*txt2.type = 'text';
	txt2.name = 'qty[]';
	txt2.value = 1;
	txt2.required = 'required';
	td6.appendChild(txt2);*/

	/*select5.id = 'select-col-5-'+numbr;
	select5.name = 'uom[]';
	select5.required = 'required';
	opt9.value = '';
	opt9.text = 'Choose';
	select5.appendChild(opt9);
	td7.appendChild(select5);*/

	txt3.type = 'number';
	txt3.name = 'price[]';
	txt3.step = 'any';
	txt3.placeholder = '0.00';
	txt3.required = 'required';
	td8.appendChild(txt3);

	/*txt4.type = 'text';
	txt4.name = 'detail[]';
	txt4.placeholder = 'Description';
	td9.appendChild(txt4);*/
	
	
	tr.appendChild(td1);
	tr.appendChild(td10);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);
	tr.appendChild(td5);
	//tr.appendChild(td6);
	//tr.appendChild(td7);
	tr.appendChild(td8);
	//tr.appendChild(td9);

	contr.appendChild(tr);
	curnumbr.value = numbr;
}

function dodata(str,sses,id,sopt) {
	var curnumbr = document.getElementById('rwcounter').value;
	var select_id = str+curnumbr;

	getdata(select_id,sses,id,sopt);
}

</script>