<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: specify tariff for corporate on all the room types
 	</span>
 	<span class="ln-display-box float-right">
		<h3 class="large nobold default-text-font-bold">Corporate Tariff</h3>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>


<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$roomtypes = select_dt_fetch('',0,$tbL52,'id','name');
	$corporatelist = select_dt_fetch('',0,$tbL58,'id','name');
	$season_list = select_dt_fetch('',0,$tbL78,'id','legendname');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_143); //create a table for this post
		createDatabasetable($var_tbl_78); //create a table for this post
		
		$corporateid = $_POST['corporate']; $mode = $_POST['mode'];
		$room_types = $_POST['room_types']; $corp_tariff_status = $_POST['status'];
		
		$mon_rate = $_POST['mon_rates']; $tue_rate = $_POST['tue_rates']; $wed_rate = $_POST['wed_rates'];
		$thur_rate = $_POST['thur_rates']; $fri_rate = $_POST['fri_rates']; $sat_rate = $_POST['sat_rates'];
		$sun_rate = $_POST['sun_rates'];

		$monday = $_POST['mondays']; $tuesday = $_POST['tuesdays']; $wedsday = $_POST['wedsdays'];
		$thursday = $_POST['thursdays']; $friday = $_POST['fridays']; $saturday = $_POST['saturdays'];
		$sunday = $_POST['sundays'];

		$adult_query=""; $adult_datasets=""; $insert_query="";
		$extrabed_query=""; $extrabed_datasets=""; $e_insert_query="";

		$post_tax_name = ""; $post_ratetype = ""; $get_ratetype = "";

		$loop_counter_r = 0;
		
		for($a=0; $a < count($room_types); $a++)
		{
			$loop_counter_r = $a + 1;

			$post_ratetype = "ratetype".$loop_counter_r;
			if(isset($_POST[$post_ratetype])) { $get_ratetype = $_POST[$post_ratetype]; }
			else { $get_ratetype = null; }

			#all tax inclusive
			$post_tax_name = "tax".$loop_counter_r;
			if(isset($_POST[$post_tax_name])) {
				$tax_incl_query = "";
				foreach($_POST[$post_tax_name] as $tax_incl) {
					$tax_incl_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"taxid"=>$tax_incl); mysqli_data_insert($tbL82,$tax_incl_query,$tax_incl_query);
				}
			}

			##monday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$monday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$mon_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$monday[$a],"price"=>$mon_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## tuesday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$tuesday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$tue_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$tuesday[$a],"price"=>$tue_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## wednesday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$wedsday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$wed_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$wedsday[$a],"price"=>$wed_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## thursday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$thursday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$thur_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$thursday[$a],"price"=>$thur_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## friday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$friday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$fri_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$friday[$a],"price"=>$fri_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## saturday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$saturday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$sat_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$saturday[$a],"price"=>$sat_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## sunday

			$adult_query = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"day"=>$sunday[$a]);
			$adult_data = mysqli_data_fetch($tbL147,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("modeid"=>$mode,"ratetype"=>$get_ratetype,"price"=>$sun_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_update($tbL147,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"corporateid"=>$corporateid,"modeid"=>$mode,"ratetype"=>$get_ratetype,"day"=>$sunday[$a],"price"=>$sun_rate[$a],"status"=>$corp_tariff_status[$a]);
				mysqli_data_insert($tbL147,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------
		}

		$saynotify = 1;
		$notifytype = 2;

		$post_header = "Notification";
		$post_message = "Corporate room tariff setup was successful";

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently update corporate room tariff","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
	}

	$get_corporate=""; $crp=""; $show_tax = 0; $count_r = ""; $tx = "";

?>
	
	<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
		
		<?php
			if(isset($_GET['crp']) && $_GET['crp'] >= 1) {
				if(isset($cct_isprivilege) && $cct_isprivilege >= 1) {
					?>
						<p class="bottom-pull-20 alignlt">
							<a href="" class="top-pull-5 right-pull-20 bottom-pull-5 left-pull-20 dark-black-white-state rounded-button ft-xsml-size" onclick="wgt_edit(event)">Edit Charges</a>
						</p>
					<?php
				}
			}
		?>

		<div class="block-element xform">
			<span class="ln-display-box float-left nc-width-60">
				<small class="block-element bottom-push-5 default-text-font-bold dark-grey-font">Corporate</small>
				<select name="corporate" id="corporate" class="nopads no-back-black" onchange="ths_corporate(this.value)">
					<?php if(isset($_GET['crp']) && $_GET['crp'] >= 1) { $crp = escape_data($_GET['crp']); $get_corporate = idget_data($tbL58,$crp,'name'); ?><option value="<?php echo $crp; ?>" selected="selected"><?php echo $get_corporate; ?></option><?php } else { ?><option value="" selected="selected">Choose</option><?php } ?>
					<?php echo $corporatelist; ?>
				</select>
			</span>
			<span class="ln-display-box float-right nc-width-35">
				<small class="block-element bottom-push-5 default-text-font-bold dark-grey-font">Season</small>
				<select name="mode" id="mode" class="nopads no-back-black">
					<option value="0" selected="selected">Tariff</option>
					<?php echo $season_list; ?>
				</select>
			</span>
			<span class="block-element new-line-space">
			</span>
		</div>

		<?php
			
			if(isset($crp) && is_numeric($crp)) {
				
				$query_tax = array("deletedata"=>0);
				$select_tax = mysqli_data_fetch($tbL35,'taxname',$query_tax,'array');

				?>
					<div class="block-element sml-rounded-button top-push-30 noscroll">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<th width="150px" class="box-border-thick-left box-border-thick-right" align="center">Room Types</th>
								<th width="100px" class="box-border-thick-right" align="center">Rate Types</th>
								<?php
									if(is_array($select_tax)) {
										$show_tax = 1;
										?>
											<th width="200px" class="box-border-thick-right" align="center">Tax</th>
										<?php
									}
								?>
								<th width="100px" class="box-border-thick-right" align="center">Mon &nbsp; <b class="fa-share nobold anchor" onclick="data_replica()" title="Replicate to other days"></b></th>
								<th width="100px" class="box-border-thick-right" align="center">Tue</th>
								<th width="100px" class="box-border-thick-right" align="center">Wed</th>
								<th width="100px" class="box-border-thick-right" align="center">Thur</th>
								<th width="100px" class="box-border-thick-right" align="center">Fri</th>
								<th width="100px" class="box-border-thick-right" align="center">Sat</th>
								<th width="100px" class="box-border-thick-right" align="center">Sun</th>
							</tr>
							
							<?php

								$additionalQuery = "";
								$dataproperty = "id,name,shortname,detail,adult,child,defaultprice,baseprice,extrabedprice";
								$constrain = array("deletedata"=>0);
								$row = "array";

								$room_types = mysqli_data_fetch($tbL52,$dataproperty,$constrain,$row);

								if(is_array($room_types))
								{
									$mon_adultrate=""; $tue_adultrate=""; $wed_adultrate=""; $thur_adultrate="";
									$fri_adultrate=""; $sat_adultrate=""; $sun_adultrate="";

									$status_query = ""; $get_status = ""; $write_status = ""; $status_color = "";

									$count_r = 0;
									
									foreach ($room_types as $rm_key => $rm_value) {
										
										//adult rate day price

										$count_r += 1;

										$status_query = array("corporateid"=>$crp,"room_type_id"=>$rm_value['id']);
										$get_status = mysqli_data_fetch($tbL147,'status',$status_query,'noarray');
										if(isset($get_status[0]) && !empty($get_status[0])) { $write_status = $get_status[0]; if($get_status[0] == 'Active') { $status_color = "forest-green-font"; } else { $status_color = "light-red-font"; } } else { $write_status = "InActive"; $status_color = "light-red-font"; }

										$mon_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"monday");
										$mon_adultrate_data = mysqli_data_fetch($tbL147,'price',$mon_adultrate,'noarray');

										$tue_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"tuesday");
										$tue_adultrate_data = mysqli_data_fetch($tbL147,'price',$tue_adultrate,'noarray');

										$wed_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"wednesday");
										$wed_adultrate_data = mysqli_data_fetch($tbL147,'price',$wed_adultrate,'noarray');

										$thur_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"thursday");
										$thur_adultrate_data = mysqli_data_fetch($tbL147,'price',$thur_adultrate,'noarray');

										$fri_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"friday");
										$fri_adultrate_data = mysqli_data_fetch($tbL147,'price',$fri_adultrate,'noarray');

										$sat_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"saturday");
										$sat_adultrate_data = mysqli_data_fetch($tbL147,'price',$sat_adultrate,'noarray');

										$sun_adultrate = array("corporateid"=>$crp,"modeid"=>0,"ratetype"=>"naira","room_type_id"=>$rm_value['id'],"day"=>"sunday");
										$sun_adultrate_data = mysqli_data_fetch($tbL147,'price',$sun_adultrate,'noarray');

										?>
											<tr>
												<td width="200px" class="box-border-thick-left box-border-thick-right alignct"><b><?php echo $rm_value['name']; ?></b><input type="hidden" name="room_types[]" value="<?php echo $rm_value['id']; ?>">
												<div class="top-push-5 top-pull-5 right-pull-10 left-pull-10 box-border-thick-top"><select name="status[]" class="nopads no-back-black <?php echo $status_color; ?>" required="required"><option value="<?php echo $write_status; ?>" class="<?php echo $status_color; ?>"><?php echo $write_status; ?></option><option value="Active">Active</option><option value="InActive">InActive</option></select>
												</td>
												<td width="100px" class="box-border-thick-right">
													<input type="radio" name="ratetype<?php echo $count_r; ?>" id="ratetype<?php echo $count_r; ?>1" value="naira" checked="checked"> <small class="right-push-10 default-text-font-bold">N</small>
													<input type="radio" name="ratetype<?php echo $count_r; ?>" id="ratetype<?php echo $count_r; ?>2" value="percent"> <small class="default-text-font-bold">%</small>
												</td>
												<?php
													if(isset($show_tax)) {
														?>
															<td width="200px" class="box-border-thick-right">
																<?php
																	
																	$th_taxname = ""; $tx = 0; $istax_inclusive = "";
																	$istax_query = "";
																	
																	foreach ($select_tax as $tkey => $tvalue) {
																		$tx += 1;
																		$th_taxname = arrayget_key($tax_charges,$tvalue['taxname']);

																		$istax_query = array("corporateid"=>$crp,"room_type_id"=>$rm_value['id'],"taxid"=>$tvalue['taxname']);
																		$istax_inclusive = mysqli_data_fetch($tbL82,'id',$istax_query,'noarray');
																		?>
																			<div class="block-element bottom-push-3"><input type="checkbox" name="tax<?php echo $tx; ?>[]" value="<?php echo $tvalue['taxname']; ?>"<?php if(isset($istax_inclusive[0]) && $istax_inclusive[0] >= 1) { ?> checked<?php } ?>> <?php echo $th_taxname; ?></div>
																		<?php
																	}
																?>
															</td>
														<?php
													}
												?>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="mon_rates[]" id="mon<?php echo $count_r; ?>"<?php if(isset($mon_adultrate_data[0]) && !empty($mon_adultrate_data[0])) { ?> value="<?php echo $mon_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="mondays[]" value="monday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="tue_rates[]" id="tue<?php echo $count_r; ?>"<?php if(isset($tue_adultrate_data[0]) && !empty($tue_adultrate_data[0])) { ?> value="<?php echo $tue_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="tuesdays[]" value="tuesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="wed_rates[]" id="wed<?php echo $count_r; ?>"<?php if(isset($wed_adultrate_data[0]) && !empty($wed_adultrate_data[0])) { ?> value="<?php echo $wed_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="wedsdays[]" value="wednesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="thur_rates[]" id="thur<?php echo $count_r; ?>"<?php if(isset($thur_adultrate_data[0]) && !empty($thur_adultrate_data[0])) { ?> value="<?php echo $thur_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="thursdays[]" value="thursday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="fri_rates[]" id="fri<?php echo $count_r; ?>"<?php if(isset($fri_adultrate_data[0]) && !empty($fri_adultrate_data[0])) { ?> value="<?php echo $fri_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="fridays[]" value="friday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sat_rates[]" id="sat<?php echo $count_r; ?>"<?php if(isset($sat_adultrate_data[0]) && !empty($sat_adultrate_data[0])) { ?> value="<?php echo $sat_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="saturdays[]" value="saturday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sun_rates[]" id="sun<?php echo $count_r; ?>"<?php if(isset($sun_adultrate_data[0]) && !empty($sun_adultrate_data[0])) { ?> value="<?php echo $sun_adultrate_data[0]; ?>" readonly<?php } else { ?> value="<?php echo $rm_value['defaultprice']; ?>"<?php } ?>><input type="hidden" name="sundays[]" value="sunday"></td>
											</tr>
										<?php
									}
								}

							?>

						</table>
					</div>

					<div class="block-element alignct top-push-30">
						<input type="submit" name="submitbutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</div>

				<?php
			}
		?>	
	</form>


<script>

	function ths_corporate(str) {
		window.location="?logs=<?php echo $logs; ?>&crp="+str;
	}

	function data_replica() {
		var i, totalr = "<?php echo $count_r; ?>";
		for(i=1; i <= totalr; i++) {
			if(document.getElementById('mon'+i) && document.getElementById('mon'+i).value !== null) {
				var th_day = document.getElementById('mon'+i).value;
				document.getElementById('tue'+i).value = th_day;
				document.getElementById('wed'+i).value = th_day;
				document.getElementById('thur'+i).value = th_day;
				document.getElementById('fri'+i).value = th_day;
				document.getElementById('sat'+i).value = th_day;
				document.getElementById('sun'+i).value = th_day;
			}
		}
	}

	function wgt_edit(e) {
		e.preventDefault();
		var i, totalr = "<?php echo $count_r; ?>";
		for(i=1; i <= totalr; i++) {
			//$("#mon"+i).prop("readonly", false);
			document.getElementById('mon'+i).readOnly = false;
			document.getElementById('tue'+i).readOnly = false;
			document.getElementById('wed'+i).readOnly = false;
			document.getElementById('thur'+i).readOnly = false;
			document.getElementById('fri'+i).readOnly = false;
			document.getElementById('sat'+i).readOnly = false;
			document.getElementById('sun'+i).readOnly = false;
		}
	}

</script>