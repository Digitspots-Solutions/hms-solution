<?php
	$smdl = "materialcontrol"; $logs = escape_data($_GET['logs']);

	$query_depts = "SELECT * FROM {$tbL12} WHERE status IN('Active') AND deletedata=0";
	$for_depts = html_db_select($query_depts,'id','department');

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

	$xtbl = $mtbL5;

?>
<div class="white-theme top-pull-7 right-pull-20 left-pull-20 bottom-push-10">
	<span class="ln-display-box float-left right-pull-30 ft-sml-size">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
	&nbsp; Note: This shows the department consumption details
	</span>
	<span class="ln-display-box float-right top-pull-5">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
	</span>
</div>

<div class="white-theme right-pull-20 bottom-pull-10 left-pull-20 box-border-thick-bottom x-scroll">
	<div class="nc-width-100">
		<form action="" method="post" autocomplete="off" id="reportform" class="nomargin nopads">
			<input type="hidden" name="reporter" value="department-consumption-reports">
			<span class="ln-display-box float-left cs-width-200 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Department</h3>
				<select name="stores" id="stores" class="nopads no-back-black">
					<option value="" selected>All</option>
					<?php echo $for_depts; ?>
				</select>
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-20">
				<h3 class="large nobold default-text-font-bold alignlt">Start Date</h3>
				<input type="text" name="startdate" id="startdate" placeholder="Start Date?" value="<?php if(isset($_POST['startdate'])) { echo $_POST['startdate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-left cs-width-120 top-pull-7 right-push-50">
				<h3 class="large nobold default-text-font-bold alignlt">End Date</h3>
				<input type="text" name="enddate" id="enddate" placeholder="End Date?" value="<?php if(isset($_POST['enddate'])) { echo $_POST['enddate']; } else { echo $server_get_date; } ?>" onfocus="textodate(this.id)" class="nopads no-back-black">
			</span>
			<span class="ln-display-box float-right top-pull-15">
				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 blue-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="jsForm('reportform')" title="Run Report"><b class="mbri-download right-push-5"></b> Run</a>
				<a href="javascript:void(0)" class="left-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
				<!--<a href="javascript:void(0)" class="left-push-10 right-push-10 top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 green-white-state xsml-rounded-button ft-xsml-size default-text-font-bold" onclick="csvExcel()" title="Csv Excel Report"><b class="mbri-share right-push-5"></b> Csv Excel</a>-->
			</span>
			<span class="block-element new-line-space">
			</span>
		</form>
	</div>
</div>
<div class="top-pull-30" align="left">
	<div class="x-scroll">
		<div class="nc-width-100 right-pull-20 left-pull-20">
			<div id="section-to-print">

				<?php
					
					if(isset($_POST['reporter']) && $_POST['reporter'] == 'department-consumption-reports') {

						?>
							<div class="cs-width-100 margin-auto-ct bottom-push-10 noscroll">
								<img src="<?php echo _LOGO_URL; ?>" class="auto-wh">
							</div>
							<div class="cs-width-700 margin-auto-ct alignct">
								<h2 class="large nobold default-text-font-bold"><?php echo _LONG_NAME; ?></h2>
								<h3 class="large nobold nomargin">Department Consumption for Item Request (Between <?php echo date('d/m/y',strtotime($_POST['startdate'])); ?> And <?php echo date('d/m/y',strtotime($_POST['enddate'])); ?>)</h3>
								<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
							</div>
						<?php

						$_SESSION['startdate'] = $_POST['startdate'];
						$_SESSION['enddate'] = $_POST['enddate'];

						$keywords = "";

						if(isset($_POST['stores']) && !empty($_POST['stores'])) {
							$keywords .= " AND departmentid={$_POST['stores']}";
						}

						$sql = "SELECT departmentid FROM {$mtbL22} WHERE deletedata=0".$keywords." GROUP BY departmentid";
						$datagroup = idget_data($sql);

						if(is_array($datagroup)) {
							
							$sql2 = ""; $datagroup2 = ""; $department_name = ""; $consumers = array();

							foreach($datagroup as $key => $val) {
				
								$new_consumer = array();

								$department_name = idget_name($val['departmentid'],'department',$tbL12);
								$new_consumer['departments'] = $department_name;

								$sql2 = "SELECT itemcode FROM {$mtbL22} WHERE departmentid={$val['departmentid']} AND deletedata=0 GROUP BY itemcode"; $datagroup2 = idget_data($sql2);
							
								?>
								<h3 class="large nobold default-text-font-bold"><?php echo $department_name; ?></h3><br>
								<table cellpadding="3" cellspacing="0" border="1">
									<tr>
										<td class="alignct default-text-font-bold">Item Code</td>
										<td class="alignct default-text-font-bold">Item</td>
										<td class="alignct default-text-font-bold">Quantity</td>
										<td class="alignct default-text-font-bold">Amount</td>
									</tr>

									<?php

										$sql3 = ""; $dataget = ""; $itemcode = ""; $itemname = ""; $total_consumption_amount = 0;
									
										foreach($datagroup2 as $key2 => $val2) {
									
											$itemcode = idget_name($val2['itemcode'],'itemcode',$mtbL5);
											$itemname = idget_name($val2['itemcode'],'item',$mtbL5);

											$sql3 = "SELECT SUM(stockin) AS totalQty, SUM(cost) AS totalAmt FROM {$mtbL22} WHERE itemcode={$val2['itemcode']} AND departmentid={$val['departmentid']} AND deletedata=0 AND datelogged BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'";
											$dataget = idget_data($sql3);

											if(!empty($dataget[0]['totalQty']) && $dataget[0]['totalQty'] > 0) {

												$total_consumption_amount = $total_consumption_amount + $dataget[0]['totalAmt'];

												?>
													<tr>
														<td class="alignct blue-font anchor default-text-font-bold" onclick="jsxView(<?php echo $val2['itemcode']; ?>)"><?php echo $itemcode; ?></td>
														<td class="alignct"><?php echo $itemname; ?></td>
														<td class="alignrt"><?php echo $dataget[0]['totalQty']; ?></td>
														<td class="alignrt"><?php echo number_format($dataget[0]['totalAmt'],2); ?></td>
													</tr>
												<?php
											}
										}

										$new_consumer['amount'] = $total_consumption_amount;
										array_push($consumers,$new_consumer);

									?>

									<tr>
										<td colspan="3" class="alignlt default-text-font-bold">Total</td>
										<td class="alignrt">&#8358; <?php echo number_format($total_consumption_amount,2); ?></td>
									</tr>

								</table>

								<div class="cs-height-30">
								</div>

								<div align="center">
									<div class="cs-width-400">
										<h4 class="large nobold bottom-pull-5">Summary for Departmental Consumption</h4>
										<table cellpadding="2" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Departments</td>
												<td class="alignct default-text-font-bold">Amount</td>
											</tr>
											<?php
												if(is_array($consumers) && count($consumers) > 0) {
													$grand_total = 0;
													foreach($consumers as $data) {
														$grand_total = $grand_total + $data['amount'];
														?>
															<tr>
																<td class="alignlt"><?php echo $data['departments']; ?></td>
																<td class="alignrt"><?php echo number_format($data['amount'],2); ?></td>
															</tr>
														<?php
													}
												}
											?>
											<tr>
												<td class="alignlt default-text-font-bold">Total</td>
												<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($grand_total,2); ?></td>
											</tr>
										</table>
									</div>
								</div>

								<?php
							}
						}
					}
				?>
			</div>
		</div>
	</div>
</div>


<div id="tktBox" class="xfadein noshow motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function jsForm(fr) {
		document.getElementById(fr).submit();
	}


	function jsxView(id) {

		datastring.process = "view";
		datastring.tip = "Product / item stock general analysis";
		
		xform('nohtmlform');

		wparams.tbl = "<?php echo $xtbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		suggestions.splice(0,suggestions.length);
		arrygets.splice(0,arrygets.length);

		idget_val('<?php echo $xtbl; ?>',id,'id','item','scalar');
		
		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var stopAfter = setInterval(() => {
				if(document.getElementById('fbox-content')) {
					writeObjheader('fbox-content','<h3 class="large nobold alignct">Previewing Content</h3>');
					clearInterval(stopAfter);

					var htmlresult, ajaxresult = JSON.parse(response);
					var arry = ajaxresult.datastring, data = arry[0];

					//var item_category='', item_sub_category='', item_group='';

					setTimeout(() => { idget_val('<?php echo $mtbL2; ?>',data.categoryid,'id','category','scalar'); },1000);
					setTimeout(() => { idget_val('<?php echo $mtbL3; ?>',data.subcategoryid,'id','subcategory','scalar'); },2000);
					setTimeout(() => { idget_val('<?php echo $mtbL4; ?>',data.itemgroupid,'id','groupname','scalar'); },3000);

					
					setTimeout(() => { var fbu = {"arryname":"uoms","keys":data.buying_unit}; wgtarrykey(fbu); },500);
					if(data.selling_unit && data.selling_unit > 0) { setTimeout(() => { var fsu = {"arryname":"uoms","keys":data.selling_unit}; wgtarrykey(fsu); },1500); }

					var expiry;

					if(data.isexpire == 'No') { expiry = "Never Expire"; }
					else if(data.isexpire == 'Yes') { expiry = data.expiry_date; }

					setTimeout(() => {
					
						htmlresult = '';
						htmlresult += '<h3 class="large nobold default-text-font-bold bottom-pull-7">'+suggestions[0]+'</h3>';
						htmlresult += '<div class="sided-box bottom-push-30">';
						htmlresult += '<ul>';
						htmlresult += '<li id="tab-1" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor" onclick="changetab(this.id)">Overview</li>';
						htmlresult += '<li id="tab-2" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Supplier</li>';
						htmlresult += '<li id="tab-3" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Purchase Statistics</li>';
						htmlresult += '<li id="tab-4" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Variation</li>';
						htmlresult += '<li id="tab-5" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Movement</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-1" class="sided-box xfadeout motion-x">';
						htmlresult += '<ul>';
						htmlresult += '<li class="nc-width-35 right-pull-30 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[1]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Sub Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[2]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Group</h4>';
						htmlresult += '<h4 class="xlarge nobold">N/A</h4>';
						//htmlresult += '<h4 class="xlarge nobold">'+suggestions[3]+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-35 right-pull-30 left-pull-20 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Buying Unit</h4>';
						htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_bu+' Pieces in 1 '+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Selling Unit</h4>';
						if(arrygets[1] != 'undefined') {
							htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[1]+'</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_su+' '+arrygets[1]+' in 1 piece</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom">Formula: '+data.calc_formular+'</h4>';
						} else {
							htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">N/A</h4>';
						}
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-30 left-pull-20">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Expiry Date</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+expiry+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Minimum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.minimum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Maximum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.maximum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Cost Center Item</h4>';
						htmlresult += '<h4 class="xlarge nobold">'+data.iscost_center+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-2" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-3" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-4" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-5" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						writeObjheader('fbox-content',htmlresult);

					},4000);
				}
			},1000);
		}
	}

	function changetab(id) {
		var i;
		for(i=1; i <= 5; i++) {
			if('tab-'+i == id) {
				chgclass(id,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor');
				chgclass('in-'+id,'sided-box xfadeout motion-x');
			} else {
				chgclass('tab-'+i,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor');
				chgclass('in-tab-'+i,'noshow xfadein motion-x');
			}
		}

		if(id == 'tab-2' || id == 'tab-3' || id == 'tab-4' || id == 'tab-5') {
			writeObjheader('in-'+id,'<h4 class="large nobold dark-grey-font alignct">Looking for record..</h4>');
		}

		if(id == 'tab-2') {

		}
	}


</script>