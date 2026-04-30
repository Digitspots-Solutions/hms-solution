<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/pos_common_data.php";
include "module_operation_privilege.php";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	<div class="cs-height-100"></div>
	<?php

		$logs = isset($_GET['logs']);

		$qyt = array("frompos"=>$cur_pos_store_id,"tr_status"=>"Pending");
		$isTr = mysqli_data_checkr($tbL166,'(*)',$qyt);

		if($isTr == true):
			echo '<h3 class="large nobold black-font alignlt">Click the button to acknowledge outlet-to-outlet stock transfer &nbsp; <input type="button" name="openrequest" value="Open Request" onclick="opentr()"></h3>';
			echo '<p class="bottom-pull-7"></p>';
		endif;


		if(isset($_POST['acknowledgebutton']) && $_POST['acknowledgebutton'] == 'Acknowledge') {
			
			foreach($_POST['checkers'] as $id) {
				
				$frompos = idget_data($tbL166,$id,'frompos');
				$topos = idget_data($tbL166,$id,'topos');
				$queryrow = idget_data($tbL166,$id,'queryrow');
				$itemid = idget_data($tbL166,$id,'itemid');
				$qtyrequired = idget_data($tbL166,$id,'qty_required');

				$fr_balance = idget_data($tbL16,$queryrow,'balance');
				$fr_stockout = idget_data($tbL16,$queryrow,'stockout');

				$constrain = array("itemcode"=>$itemid,"postoreid"=>$topos,"storagetype"=>"consumable");
				$chkStock = mysqli_data_checkr($tbL16,'(*)',$constrain);

				if($chkStock == true) {
					
					if($fr_balance >= $qtyrequired) {
						
						$new_balance = $fr_balance - $qtyrequired;
						$new_stockout = $fr_stockout + $qtyrequired;

						$repo = mysqli_data_fetch($tbL16,'id,stockin,balance',$constrain,'noarray');

						$new_stockin_repo = $repo[1] + $qtyrequired;
						$new_balance_repo = $repo[2] + $qtyrequired;

						$dataset_repo = array("stockin"=>$new_stockin_repo,"balance"=>$new_balance_repo);
						mysqli_data_update($tbL16,$dataset_repo,$constrain);

						$query_out = array("id"=>$queryrow);
						$dataset_out = array("stockout"=>$new_stockout,"balance"=>$new_balance);
						mysqli_data_update($tbL16,$dataset_out,$query_out);

						$query_id = array("id"=>$id);
						$dataset_id = array("acknowledgeby"=>$userSignedIn,"tr_status"=>"Successful");
						mysqli_data_update($tbL166,$dataset_id,$query_id);

						unset($_GET['openrequest']);
					}

				} else {

					$storageid = idget_data($tbL14,$topos,'store');
					$categoryid = idget_data($tbL16,$queryrow,'categoryid');
					$subcategoryid = idget_data($tbL16,$queryrow,'subcategoryid');
					$item = idget_data($tbL16,$queryrow,'item');
					$uom = idget_data($tbL16,$queryrow,'uom');
					$cost = idget_data($tbL16,$queryrow,'cost');
					$price = idget_data($tbL16,$queryrow,'price');
					$isfeature = idget_data($tbL16,$queryrow,'isfeature');
					$isstaff = idget_data($tbL16,$queryrow,'isstaff');

					$balance = $qtyrequired;
					$stockin = $qtyrequired;

					$constrain = array("itemcode"=>$itemid,"postoreid"=>$topos,"storagetype"=>"consumable");
					$dataset_repo = array("storageid"=>$storageid,"storagetype"=>"consumable","postoreid"=>$topos,"categoryid"=>$categoryid,"subcategoryid"=>$subcategoryid,"itemcode"=>$itemid,"item"=>$item,"stockin"=>$stockin,"uom"=>$uom,"cost"=>$cost,"price"=>0,"balance"=>$balance,"isfeature"=>$isfeature,"isstaff"=>$isstaff);
					$result = mysqli_data_insert($tbL16,$dataset_repo,$constrain);

					if(isset($result) && $result == 2) {

						$new_balance = $fr_balance - $qtyrequired;
						$new_stockout = $fr_stockout + $qtyrequired;

						$query_out = array("id"=>$queryrow);
						$dataset_out = array("stockout"=>$new_stockout,"balance"=>$new_balance);
						mysqli_data_update($tbL16,$dataset_out,$query_out);

						$query_id = array("id"=>$id);
						$dataset_id = array("acknowledgeby"=>$userSignedIn,"tr_status"=>"Successful");
						mysqli_data_update($tbL166,$dataset_id,$query_id);

						unset($_GET['openrequest']);
					}
				}
			}
		}

		if(isset($_POST['ignorebutton']) && $_POST['ignorebutton'] == 'Ignore') {
			foreach($_POST['checkers'] as $id) {
				$queryset = array("id"=>$id);
				trash_record($tbL166,$queryset);
			}
		}

		#--get var

		if(isset($_GET['openrequest']) && $_GET['openrequest'] == 'yes') {
			$queryset = array("frompos"=>$cur_pos_store_id,"tr_status"=>"Pending");
			$sql = mysqli_data_fetch($tbL166,'id,topos,queryrow,itemid,qty_required,requestby,datelogged,timelogged',$queryset,'array');

			if(is_array($sql)) {
				?>
					<h3 class="large nobold alignlt steel-blue-font">+ Outlet Transfer Request</h3><br>

					<form action="" method="post" autocomplete="off">
						<?php if(isset($allowOutletTransfer) && $allowOutletTransfer == 200): ?>
							<p class="bottom-pull-15"><input type="submit" name="acknowledgebutton" value="Acknowledge"> &nbsp; <input type="submit" name="ignorebutton" value="Ignore"></p>
						<?php else: ?>
							<p class="light-red-font bottom-pull-15">* Require authentication privilege</p>
						<?php endif; ?>

						<table cellpadding="3" cellspacing="1">
							<tr>
								<td class="alignct"></td>
								<td class="alignct default-text-font-bold">From Outlet</td>
								<td class="alignct default-text-font-bold">Item</td>
								<td class="alignct default-text-font-bold">Qty</td>
								<td class="alignct default-text-font-bold">Requested By</td>
								<td class="alignct default-text-font-bold">Datetime</td>
							</tr>
							<?php
							
								foreach($sql as $key => $val) {
									
									$outlet = idget_data($tbL14,$val['topos'],'posname');
									$item = idget_data($tbL16,$val['queryrow'],'item');
									$initiator = idget_data($tbL7,$val['requestby'],'staffname');

									?>
										<tr>
											<td class="alignct"><input type="checkbox" name="checkers[]" value="<?php echo $val['id']; ?>"></td>
											<td class="alignct"><?php echo $outlet; ?></td>
											<td class="alignct"><?php echo $item; ?></td>
											<td class="alignct"><?php echo $val['qty_required']; ?></td>
											<td class="alignct"><?php echo $initiator; ?></td>
											<td class="alignct"><?php echo date('d-m-y',strtotime($val['datelogged'])).' '.$val['timelogged']; ?></td>
										</tr>
									<?php

									$outlet = ""; $item = ""; $initiator = "";
								}

							?>
						</table>
					</form>
				<?php
			}

		} elseif(isset($_GET['openrequest']) && $_GET['openrequest'] == 'no') {
			$queryset = array("frompos"=>$cur_pos_store_id,"tr_status"=>"Pending");
			trash_record($tbL166,$queryset);
		}

		$postorekey = array("iscounter"=>"Yes","deletedata"=>0,"status"=>"Active");
		$postores = mysqli_data_fetch($tbL14,'id,posname',$postorekey,'array');

		if(is_array($postores)) {
			foreach($postores as $pskey => $psvalue) {
				if(!empty($logs) && $logs == $psvalue['posname']) {
					
					//populate default category
					foreach($outlet_category_type as $key => $val):
						$queryset = array("postoreid"=>$cur_pos_store_id,"program_id"=>$key);
						$dataset = array("postoreid"=>$cur_pos_store_id,"program_id"=>$key,"category"=>$val,"detail"=>"For {$val} Products","isdefault"=>"Yes");
						mysqli_data_insert($tbL15,$dataset,$queryset);
					endforeach;
					
					include "pos/get_cur_counter.php";
					break;
				}
			}
		}

		

	?>
</div>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr top-push-50 top-pull-50" align="right">
	<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="pos-header-notification" class="large red-font"></h4>
		<small id="pos-message-notification" class="block-element top-push-10"></small>
	</div>
</div>


<script>
	
	const posid = "<?php echo $cur_pos_store_id; ?>";

	/*var getransfer = setInterval(() => {

		sqldatastring.sql = "SELECT * FROM outlet_transfer_tbl WHERE frompos="+posid+" AND status='Pending' LIMIT 1";
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;

			if(data.length >= 1) {
				var con = confirm("You have an outlet transfer request!\nClick OK to proceed or\nCancel to remove request");
				if(con == true) { window.location.href = window.location.href+'&openrequest=yes'; }
				else { window.location.href = window.location.href+'&openrequest=no'; }
			}
		}

	},2000);*/

	function opentr() {
		window.location.href = window.location.href+'&openrequest=yes';
	}


	sessionStorage.setItem('workaround',0);

	setInterval(() => {
		var wka;
		wka = sessionStorage.getItem('workaround');
		wka = Number(wka) + 1;
		sessionStorage.setItem('workaround',wka);
	},1000);

	window.addEventListener("mousemove", (event) => {
		var e = event || window.event;
		if(e.clientX || e.clientY) { sessionStorage.setItem('workaround',0); }
	});

	window.addEventListener("keypress", (event) => {
		var e = event || window.event;
		if(e.code || e.which) { sessionStorage.setItem('workaround',0); }
	});

</script>