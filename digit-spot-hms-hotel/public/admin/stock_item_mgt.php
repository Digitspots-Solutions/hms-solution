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
			$item_name = idget_data($tbL118,$this_item,'item');
			$item_code = idget_data($tbL118,$this_item,'itemcode');

			if(isset($_GET['a']) && $_GET['a'] == 'overview') { $highlight1 = "blue-white-state"; $a1=1; }
			else { $highlight1 = "grey-theme dark-grey-font"; $a1=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'supplier') { $highlight2 = "blue-white-state"; $a2=2; }
			else { $highlight2 = "grey-theme dark-grey-font"; $a2=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'purchase') { $highlight3 = "blue-white-state"; $a3=3; }
			else { $highlight3 = "grey-theme dark-grey-font"; $a3=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'variation') { $highlight4 = "blue-white-state"; $a4=4; }
			else { $highlight4 = "grey-theme dark-grey-font"; $a4=0; }
			
			if(isset($_GET['a']) && $_GET['a'] == 'movement') { $highlight5 = "blue-white-state"; $a5=5; }
			else { $highlight5 = "grey-theme dark-grey-font"; $a5=0; }

			?>
				<h2 class="large nomargin"><?php echo $item_name; ?> (<?php echo $item_code; ?>) <?php if((isset($_GET['bk']) && isset($_GET['store'])) && ($_GET['bk'] == 'y' && $_GET['store'] >= 1)) { $bk = "y"; $store = $_GET['store']; ?><a href="store_mgt.php?thisItem=<?php echo $_GET['store']; ?>&a=item" class="blue-font ft-xsml-size float-right">Back to store</a><?php } else { $bk = "n"; $store = 0; } ?></h2><br>
				<a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=overview&bk=<?php echo $bk; ?>&store=<?php echo $store; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight1; ?> right-push-5 ft-xsml-size">Overview</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=supplier&bk=<?php echo $bk; ?>&store=<?php echo $store; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight2; ?> right-push-5 ft-xsml-size">Supplier</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=purchase&bk=<?php echo $bk; ?>&store=<?php echo $store; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight3; ?> right-push-5 ft-xsml-size">Purchase Statistics</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=variation&bk=<?php echo $bk; ?>&store=<?php echo $store; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight4; ?> right-push-5 ft-xsml-size">Stock Variation</a><a href="?thisItem=<?php echo $_GET['thisItem']; ?>&a=movement&bk=<?php echo $bk; ?>&store=<?php echo $store; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 <?php echo $highlight5; ?> right-push-5 ft-xsml-size">Stock Movement</a>

				<div class="block-element cs-height-50"></div>
			<?php

			if(isset($a1) && $a1 == 1) {
				
					$ctg1 = idget_data($tbL118,$this_item,'categoryid');
					$ctg2 = idget_data($tbL118,$this_item,'subcategoryid');
					$ctg3 = idget_data($tbL118,$this_item,'itemgroupid');

					$uom1 = idget_data($tbL118,$this_item,'buying_unit');
					$uom2 = idget_data($tbL118,$this_item,'selling_unit');

					$description = idget_data($tbL118,$this_item,'detail');
					$category = idget_data($tbL115,$ctg1,'category');
					$sub_category = idget_data($tbL116,$ctg2,'subcategory');
					$item_group = idget_data($tbL117,$ctg3,'groupname');
					$noofpieces = idget_data($tbL118,$this_item,'noofpiece_bu');
					$isexpire = idget_data($tbL118,$this_item,'isexpire');
					$minimum_stock = idget_data($tbL118,$this_item,'minimum_stock');
					$maximum_stock = idget_data($tbL118,$this_item,'maximum_stock');
					$iscost_center = idget_data($tbL118,$this_item,'iscost_center');

					if(isset($isexpire) && $isexpire == 'Yes') {
						$expire_date = idget_data($tbL118,$this_item,'expiry_date');
					} elseif(isset($isexpire) && $isexpire == 'No') {
						$expire_date = "Never Expire";
					}
					
					$buying_unit = arrayget_key($uoms,$uom1);
					$selling_unit = arrayget_key($uoms,$uom2);

				?>
					<span class="ln-display-box float-left nc-width-30 right-push-30">
						<small class="block-element dark-grey-font bottom-push-5">Category</small>
						<small class="block-element bottom-push-15"><?php echo $category; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Sub Category</small>
						<small class="block-element bottom-push-15"><?php echo $sub_category; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Item Group</small>
						<small class="block-element bottom-push-15"><?php echo $item_group; ?></small>
					</span>
					<span class="ln-display-box float-left nc-width-35 right-push-30">
						<small class="block-element dark-grey-font bottom-push-5">Description</small>
						<small class="block-element bottom-push-15"><?php echo $description; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Buying Unit</small>
						<small class="block-element bottom-push-15"><?php echo $buying_unit; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Selling Unit</small>
						<small class="block-element bottom-push-15"><?php echo $selling_unit; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">No. of Pieces</small>
						<small class="block-element bottom-push-15"><?php echo $noofpieces; ?> (1 <?php echo $buying_unit; ?> = <?php echo $noofpieces.' '.$selling_unit; ?>)</small>
					</span>
					<span class="ln-display-box float-left nc-width-20">
						<small class="block-element dark-grey-font bottom-push-5">Expiry Date</small>
						<small class="block-element bottom-push-15"><?php echo $expire_date; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Minimum Stock</small>
						<small class="block-element bottom-push-15"><?php echo $minimum_stock; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Maximum Stock</small>
						<small class="block-element bottom-push-15"><?php echo $maximum_stock; ?></small>

						<small class="block-element dark-grey-font bottom-push-5">Is Cost Center Item</small>
						<small class="block-element bottom-push-15"><?php echo $iscost_center; ?></small>
					</span>
					<span class="block-element new-line-space">
					</span>
				<?php
			}
		}
	?>
</div>