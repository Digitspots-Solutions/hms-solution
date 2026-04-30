<?php

$smdl = "report"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

if(isset($_SESSION['postoreid'])) { $cur_pos_store_id = $_SESSION['postoreid']; }
else { $cur_pos_store_id = 0; }

if(isset($_POST['startdate']) && !empty($_POST['startdate'])) { $startdate = $_POST['startdate']; }
else { $startdate = $server_get_date; }

if(isset($_POST['endate']) && !empty($_POST['endate'])) { $endate = $_POST['endate']; }
else { $endate = $server_get_date; }

$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;
	
?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: Here you can see the hotel revenue
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-20">
	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
		<span class="ln-display-box float-left right-push-10">
			<div class="ln-display-box float-left right-push-10">
				<h3 class="large nobold default-text-font-bold">Start Date</h3>
				<input type="date" name="startdate" id="startdate" value="<?php echo $startdate; ?>" title="From date">
			</div>
			<div class="ln-display-box float-left right-push-10">
				<h3 class="large nobold default-text-font-bold">End Date</h3>
				<input type="date" name="endate" id="endate" value="<?php echo $endate; ?>" title="To date">
			</div>
		</span>
		<span class="ln-display-box float-right">
			<input type="submit" name="searchbutton" value="Run" class="submit top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
		</span>
	</form>
</div>

<div class="block-element box-border-thick pads15 sml-rounded-button">

	<?php

		if(isset($_POST['searchbutton'])) {
			
			$base_arry = array();

			$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
			$get_posstores = mysqli_data_fetch($tbL14,'id,posname',$pst_query,'array');

			$custom_date_start=""; $custom_date_end="";

			$custom_date_start=$_POST['startdate']; $custom_date_end=$_POST['endate'];
			$date_query = "(Between ".date("d/m/Y",strtotime($_POST['startdate']))." And ".date("d/m/Y",strtotime($_POST['endate'])).")";;
			$date_aQuery = " AND datelogged BETWEEN '".$custom_date_start."' AND '".$custom_date_end."'";

			?>
				<p class="top-pull-10 bottom-pull-10 right-pull-30 alignrt">
					<a href="javascript:void(0)" class="blue-font" onclick="window.print()"><b class="fa-print nobold dark-black-font"></b>&nbsp; Print</a>
				</p>
				<div id="section-to-print" class="block-element">
					<div class="block-element bottom-push-30" align="center">
						<div class="cs-width-100 bottom-push-10 noscroll">
							<img src="<?php echo _FC_LOGO_Mx; ?>" class="auto-wh">
						</div>
						<h2 class="large nobold"><?php echo _LONG_NAME; ?></h2>
						<h3 class="large nobold default-text-font-bold nomargin">Tentative Collations Report <?php echo $date_query; ?></h3>
						<h4 class="large nobold">Printed By: <?php echo $printed_by.' (On '.$printed_date.')'; ?></h4>
					</div>

					<?php

						$rn_sql = "SELECT SUM(room_amount) AS totalrevenue, SUM(tax_amount) AS vat, SUM(consumption_tax_amount) AS consumption, SUM(service_charge) AS scharge FROM {$tbL134} WHERE wkf IN(0,1,2) AND deletedata=0 AND room_status='CheckedIn' AND bill_date BETWEEN '{$startdate}' AND '{$endate}'";
						$in_dataset2 = wgetSQL($rn_sql);

						$revenue = $in_dataset2[0]['totalrevenue'];
						$tax = $in_dataset2[0]['vat'];
						$consumption = $in_dataset2[0]['consumption'];
						$servicecharge = $in_dataset2[0]['scharge'];

						$final_revenue = $revenue + $tax + $consumption + $servicecharge;

						#end 1:

						$cr_sql = "SELECT SUM(cancellation_charges) AS totalcancelrevenue FROM {$tbL127} WHERE status IN('Cancelled') AND deletedata=0 AND cancel_date BETWEEN '{$startdate}' AND '{$endate}'";
						$in_dataset3 = wgetSQL($cr_sql);

						$cancel_revenue = $in_dataset3[0]['totalcancelrevenue'];

						#end 2:

						$rb_sql = "SELECT SUM(amount) AS totalrebates FROM {$tbL163} WHERE rebate_type IN('Booking') AND deletedata=0 AND transaction_date BETWEEN '{$startdate}' AND '{$endate}'";
						$in_dataset4 = wgetSQL($rb_sql);

						$rebates = $in_dataset4[0]['totalrebates'];

						#end 3:
					?>

					<div class="block-element bottom-push-20">
						<h3 class="large nobold default-text-font-bold blue-font">Booking</h3>
						<table cellpadding="3" cellspacing="0" border="1">
							<tr>
								<td class="alignct default-text-font-bold">Particular</td>
								<td class="alignct default-text-font-bold">Gross Today</td>
								<td class="alignct default-text-font-bold">Rebates</td>
								<td class="alignct default-text-font-bold">Service Charge</td>
								<td class="alignct default-text-font-bold">Tax</td>
								<td class="alignct default-text-font-bold">Consumption</td>
								<td class="alignct default-text-font-bold">Total Less Rebate</td>
								<td class="alignct default-text-font-bold">Final Revenue</td>
							</tr>
							<tr>
								<td class="alignlt">Transcient</td>
								<td class="alignrt"><?php echo number_format($revenue,2); ?></td>
								<td class="alignrt"><?php echo number_format($rebates,2); ?></td>
								<td class="alignrt"><?php echo number_format($servicecharge,2); ?></td>
								<td class="alignrt"><?php echo number_format($tax,2); ?></td>
								<td class="alignrt"><?php echo number_format($consumption,2); ?></td>
								<td class="alignrt">0</td>
								<td class="alignrt"><?php echo number_format($final_revenue,2); ?></td>
							</tr>
							<tr>
								<td class="alignlt">Extra Revenue</td>
								<td class="alignrt"><?php echo number_format($cancel_revenue,2); ?></td>
								<td class="alignrt">0.00</td>
								<td class="alignrt">0.00</td>
								<td class="alignrt">.00</td>
								<td class="alignrt">0.00</td>
								<td class="alignrt">0.00</td>
								<td class="alignrt">0.00</td>
							</tr>
						</table>
					</div>

					<?php

						$base_arry['Booking'] = $final_revenue;
						$base_arry['Extra Revenue'] = $cancel_revenue;

						if(is_array($outlet_category_type)) {

							$add_category_to_array = array();
							
							$category_name = "";
							$category_item_selection_key = "";

							$counter_up = 0;

							foreach($outlet_category_type as $psckey => $pscvalue) {
							
								$add_category_component = array();

								$category_name = $pscvalue;

								$counter_up += 1;

								?>

								<div class="block-element bottom-push-20">
									<h3 class="large nobold default-text-font-bold blue-font"><?php echo $category_name; ?></h3>
									<div class="block-element top-push-10 sml-rounded-button noscroll">
										<table cellpadding="3" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Store</td>
												<td class="alignct default-text-font-bold">Gross Today</td>
												<td class="alignct default-text-font-bold">Rebates</td>
												<td class="alignct default-text-font-bold">Service Charge</td>
												<td class="alignct default-text-font-bold">Tax</td>
												<td class="alignct default-text-font-bold">Consumption</td>
												<td class="alignct default-text-font-bold">Total Less Rebate</td>
												<td class="alignct default-text-font-bold">Final Revenue</td>
											</tr>
											
											<?php

											if(is_array($get_posstores)) {
												
												
												$final_revenue = ""; $revenue = ""; $rebates = "";
												$tax = ""; $consumption = ""; $servicecharge = "";

												$g_final_revenue = 0; $g_revenue = 0; $g_rebates = 0;
												$g_tax = 0; $g_consumption = 0; $g_servicecharge = 0;

												$g_sql = ""; $sql = ""; $orders = "";

												foreach($get_posstores as $key => $val) {

													$g_sql = "SELECT order_number FROM {$tbL99} WHERE posid={$val['id']} AND main_category={$psckey} AND isreversed=0 AND status IN('Completed') AND datelogged BETWEEN '{$startdate}' AND '{$endate}' GROUP BY order_number";
													$datagroup = wgetSQL($g_sql);

													if(is_array($datagroup)) {

														foreach($datagroup as $gkey => $gval) {
															$orders .= "'".$gval['order_number']."',";
														}

														$orders = substr_replace($orders,'',-1,1);

														$sql = "SELECT SUM(bill_amount) AS totalamount, SUM(tax_amount) AS vat, SUM(consumption_amount) AS consumption, SUM(service_charge_amount) AS scharge FROM {$tbL100} WHERE posid={$val['id']} AND order_number IN({$orders}) AND isreversed=0 AND status IN('Completed') AND payment NOT IN('Complimentary') AND datelogged BETWEEN '{$startdate}' AND '{$endate}'";

														$in_dataset = wgetSQL($sql);

														$revenue = $in_dataset[0]['totalamount'] - ($in_dataset[0]['vat'] + $in_dataset[0]['consumption'] + $in_dataset[0]['scharge']);

														$tax = $in_dataset[0]['vat'];
														$consumption = $in_dataset[0]['consumption'];
														$servicecharge = $in_dataset[0]['scharge'];
														$final_revenue = $in_dataset[0]['totalamount'];
														$rebates = 0;

														$g_final_revenue = $g_final_revenue + $final_revenue;
														$g_revenue = $g_revenue + $revenue;
														$g_rebates = $g_rebates + $rebates;
														$g_tax = $g_tax + $tax;
														$g_consumption = $g_consumption + $consumption;
														$g_servicecharge = $g_servicecharge + $servicecharge;
													}

													?>
												
													<tr>
														<td class="alignlt"><?php echo $val['posname']; ?></td>
														<td class="alignrt"><?php echo number_format($revenue,2); ?></td>
														<td class="alignrt"><?php echo number_format($rebates,2); ?></td>
														<td class="alignrt"><?php echo number_format($servicecharge,2); ?></td>
														<td class="alignrt"><?php echo number_format($tax,2); ?></td>
														<td class="alignrt"><?php echo number_format($consumption,2); ?></td>
														<td class="alignrt">0</td>
														<td class="alignrt"><?php echo number_format($final_revenue,2); ?></td>
													</tr>

													<?php
												}

												?>
												
													<tr>
														<td class="alignlt">Total</td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_revenue,2); ?></td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_rebates,2); ?></td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_servicecharge,2); ?></td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_tax,2); ?></td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_consumption,2); ?></td>
														<td class="alignrt default-text-font-bold">&#8358; 0.00</td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($g_final_revenue,2); ?></td>
													</tr>

												<?php

												$base_arry[$category_name] = $g_final_revenue;

											}

											?>

										</table>
									</div>
								</div>
											
								<?php

							}

							?>
								<div class="block-element top-push-50 bottom-push-20" align="center">
									<h4 class="large nobold">Tentative Collations Summary </h4>
									<div class="block-element nc-width-40 top-push-10">
										<table cellpadding="3" cellspacing="0" border="1">
											<tr>
												<td class="alignct default-text-font-bold">Type</td>
												<td class="alignct default-text-font-bold">Amount</td>
											</tr>

											<?php
											
											if(is_array($base_arry)) {
												
												$total_sum = 0;

												$category_name = "";
												
												foreach($base_arry as $category => $component) {
													
													$total_sum = $total_sum + $component;
												
													?>
														<tr>
															<td class="alignlt"><?php echo $category; ?></td>
															<td class="alignrt"><?php echo number_format($component,2); ?></td>
														</tr>
													<?php
												}

												?>
													<tr class="grey-theme">
														<td class="alignlt default-text-font-bold">Total</td>
														<td class="alignrt default-text-font-bold">&#8358; <?php echo number_format($total_sum,2); ?></td>
													</tr>
												<?php
											}
											
											?>
										</table>
									</div>
								</div>
		
							<?php
						}

					?>
				</div>

			<?php
		}

	?>

</div>


<script>

</script>