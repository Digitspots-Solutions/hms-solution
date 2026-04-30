<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/common_data_vars.php";
?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads20">
	<?php
		if(isset($_GET['thisItem']) && $_GET['thisItem'] >= 1) {
			$this_item = escape_data($_GET['thisItem']);
			$item_name = idget_data($tbL123,$this_item,'store_name');
			$item_code = idget_data($tbL123,$this_item,'store_number');

			if(isset($_GET['a']) && $_GET['a'] == 'overview') { $highlight1 = "blue-white-state"; $a1=1; }
			else { $highlight1 = "grey-theme dark-grey-font"; $a1=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'item') { $highlight2 = "blue-white-state"; $a2=2; }
			else { $highlight2 = "grey-theme dark-grey-font"; $a2=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'variation') { $highlight3 = "blue-white-state"; $a3=3; }
			else { $highlight3 = "grey-theme dark-grey-font"; $a3=0; }

			if(isset($_GET['a']) && $_GET['a'] == 'purchase') { $highlight4 = "blue-white-state"; $a4=4; }
			else { $highlight4 = "grey-theme dark-grey-font"; $a4=0; }
			

			?>
				<h2 class="large nomargin"><?php echo $item_name; ?> (<?php echo $item_code; ?>)</h2><br>
				<a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=overview" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight1; ?> right-push-5 ft-xsml-size">Overview</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=item" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight2; ?> right-push-5 ft-xsml-size">Item</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=variation" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight3; ?> right-push-5 ft-xsml-size">Stock</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=purchase" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight4; ?> right-push-5 ft-xsml-size">Purchase Statistics</a>

				<div class="block-element cs-height-50"></div>
			<?php

			if(isset($a1) && $a1 == 1) {
				
				$ctg1 = idget_data($tbL123,$this_item,'store_type');
				$ctg2 = idget_data($tbL123,$this_item,'parent_store');
				$ctg3 = idget_data($tbL123,$this_item,'department');

				$description = idget_data($tbL123,$this_item,'detail');
				$this_store_type = arrayget_key($store_type,$ctg1);
				$this_parent_store = arrayget_key($parent_store,$ctg2);
				$this_department = idget_data($tbL12,$ctg3,'department');
				
				$address = idget_data($tbL123,$this_item,'address');
				$status = idget_data($tbL123,$this_item,'status');

				$this_status_color_tag = arrayget_key($status_color_tag,$status);
					
				?>
					<span class="ln-display-box float-left nc-width-30 right-push-30">
						<small class="block-element dark-grey-font bottom-push-5">Store Type</small>
						<small class="block-element bottom-push-15"><?php echo $this_store_type; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Parent Store</small>
						<small class="block-element bottom-push-15"><?php echo $this_parent_store; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Department</small>
						<small class="block-element bottom-push-15"><?php echo $this_department; ?></small>
					</span>
					<span class="ln-display-box float-left nc-width-35 right-push-30">
						<small class="block-element dark-grey-font bottom-push-5">Description</small>
						<small class="block-element bottom-push-15"><?php echo $description; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Address</small>
						<small class="block-element bottom-push-15"><?php echo $address; ?></small>
					</span>
					<span class="ln-display-box float-left nc-width-20">
						<small class="block-element dark-grey-font bottom-push-5">Status</small>
						<small class="block-element bottom-push-15 <?php echo $this_status_color_tag; ?>"><?php echo $status; ?></small>
					</span>
					<span class="block-element new-line-space">
					</span>
				<?php
			}

			if(isset($a2) && $a2 == 2) {
				
					if(isset($_GET['linkItem']) && $_GET['linkItem'] >= 1) {
						
						createDatabasetable($var_tbl_119); //create a table for this post

						$thisdata = array("storeid"=>$this_item,"itemid"=>escape_data($_GET['linkItem']));
						$thisdata_constrain = array("itemid"=>escape_data($_GET['linkItem']));
						$islinked = mysqli_data_insert($tbL124,$thisdata,$thisdata);

						if(isset($islinked) && $islinked == 2) {
							//create a log file
							$log_message = "Recently link item to store outlet (".$item_name." - ".$item_code.")";
							$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

							$post_result = "Item has been linked to this store";
						} else {
							$post_result = "Unable to link item. Item already exist";
						}

					} else {
						$post_result = "";
					}

				?>
				<div class="block-element box-border-thick nc-width-100 nc-height-60 isscroll">
						<div class="block-element pads10 grey-1-theme bottom-push-20" align="center">
							<h4 class="large alignct">Link item to store</h4>
							<form action="" method="post">
								<div class="block-element nc-width-50 box-border-thick pads20 top-push-10">
									<span class="ln-display-box float-left nc-width-70">
										<input type="text" name="search" id="search" placeholder="Enter item name or code?">
									</span>
									<span class="ln-display-box float-right nc-width-20 top-pull-3">
										<input type="submit" name="searchbutton" value="Go &rsaquo;" class="submit top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state sml-rounded-button">
									</span>
									<span class="block-element new-line-space">
									</span>
								</div>
							</form>
							<small class="block-element top-push-5 red-font alignct"><?php echo $post_result; ?></small>
							<?php
								if(isset($_POST['searchbutton'])) {

									$keywords=" AND (item LIKE '".escape_data($_POST['search'])."%' OR itemcode LIKE '".escape_data($_POST['search'])."%')";
									$additionalQuery = $keywords; $item_query = array("deletedata"=>0);
									$get_the_item = mysqli_data_fetch($tbL118,'id,item,buying_unit',$item_query,'array');

									if(is_array($get_the_item)) {
										$ibu="";
										foreach ($get_the_item as $gi_key => $gi_value) {
											$ibu = arrayget_key($uoms,$gi_value['buying_unit']);
											?>
												<span class="ln-display-box float-left right-push-20 bottom-push-20">
													<h4 class="large nobold"><?php echo $gi_value['item']; ?></h4>
													<small class="block-element top-push-3 bottom-push-5 dark-grey-font">Base Unit: <?php echo $ibu; ?></small>
													<a href="?thisItem=<?php echo $this_item; ?>&a=item&linkItem=<?php echo $gi_value['id']; ?>" class="ft-xxsml-size blue-font">Add to this store</a>
												</span>
											<?php
										}
									} else {
										?>
											<span class="block-elemen alignct ft-sml-size">No items found for your search</span>
										<?php
									}

									?>
										<span class="block-element new-line-space"></span>
									<?php
								}
							?>
						</div>
					<?php

					#-----------------------------------------------------------------------------------------------------------------
					
					$pageurl = 'store_mgt.php?thisItem='.$this_item.'/a=item';

					//pagination controller
					if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
						$curpage = $_GET['pg'];
						$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
						$additionalQuery = " ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
					} else {
						$curpage = 0;
						$pgstart = 0; $pglimit = 25;
						$additionalQuery = " ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
					}

					$dataproperty = "id,itemid";
					$constrain = array("storeid"=>$this_item);
					$row = "array";

					$dataCollect = mysqli_data_fetch($tbL124,$dataproperty,$constrain,$row);

					if(is_array($dataCollect))
					{
						$thproperty = array("noth","item","base unit","status");
						$tcount = count($thproperty);

						$htmlresult .= '<div class="block-element pads15">';
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
						
						$num=$pgstart; $g=""; $dataid=""; $get_item_name=""; $base_unit=""; $get_base_unit=""; $get_status="";

						foreach($dataCollect as $theader => $tdata)
						{
							$num += 1;
							$g = $num / 2;

							$dataid = $tdata["id"];

							$get_item_name = idget_data($tbL118,$tdata["itemid"],'item');
							$get_base_unit = idget_data($tbL118,$tdata["itemid"],'buying_unit');
							$get_status = idget_data($tbL118,$tdata["itemid"],'status');

							$base_unit = arrayget_key($uoms,$get_base_unit);
							
							$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
									
							$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
							$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
							$htmlresult .= '<td width="350px" align="center" class="box-border-thick-right"><a href="stock_item_mgt.php?thisItem='.$tdata["itemid"].'&a=overview&bk=y&store='.$this_item.'" class="royal-blue-font" title="Item Details"><b>'.$get_item_name.'</b></a></td>';
							$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$base_unit.'</td>';
							$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$get_status.'</td>';
							$htmlresult .= '</tr>';
						}
						
						$htmlresult .= '</table>';
						$htmlresult .= '</div>';
						$htmlresult .= '</div>';
					}
					

					echo $htmlresult;

					//paginate this page

					$additionalQuery = "";
					mysqli_data_check($tbL124,'(*)',$constrain);
					$totalcount = $numOfrows;

					$paginate = data_pagenation(25,0,$totalcount);
					if(isset($paginate) && !empty($paginate)) {
						echo '<div class="block-element pads20">'.$paginate.'</div>';
					}

					//end of pagination

					?>

				</div>

				<?php
			}
		}
	?>
	<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>
</div>