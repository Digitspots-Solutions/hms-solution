<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: setup room-season tariff for room types. Ensure you have created your hotel season
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>
	
	function getseasonTariff() {
		var season = document.getElementById('fieldset1').value;
		window.location.href = '?logs=<?php echo $logs; ?>&selectseason='+season;
	}

</script>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$additionalQuery = "";
	$extable = $tbL78;
	$extcols = 'legendname';
	$extkey = 'id';
	$season_list = select_dt_fetch('',0,$tbL79,'modeid','modeid');

	if(isset($_GET['selectseason']) && is_numeric($_GET['selectseason'])) {
		$selectseason = escape_data($_GET['selectseason']);
		$season_name = idget_data($tbL78,$selectseason,'legendname');
		$htmlresult = '<option value="'.$selectseason.'" selected="selected">'.$season_name.'</option>';
	} else {
		$selectseason = '';
		$htmlresult = '<option value="" selected="selected">Choose Season</option>';
	}


	#---------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_76); //create a table for this post

		$hotelseason = $_POST['fieldset1'];
		$room_types = $_POST['room_types'];
		$adult = $_POST['adult'];
		$extrabed = $_POST['extrabed'];

		$mon_rate = $_POST['mon_rates']; $tue_rate = $_POST['tue_rates']; $wed_rate = $_POST['wed_rates']; $thur_rate = $_POST['thur_rates'];
		$fri_rate = $_POST['fri_rates']; $sat_rate = $_POST['sat_rates']; $sun_rate = $_POST['sun_rates'];

		$monday = $_POST['mondays']; $tuesday = $_POST['tuesdays']; $wedsday = $_POST['wedsdays']; $thursday = $_POST['thursdays'];
		$friday = $_POST['fridays']; $saturday = $_POST['saturdays']; $sunday = $_POST['sundays'];

		$e_mon_rate = $_POST['e_mon_rates']; $e_tue_rate = $_POST['e_tue_rates']; $e_wed_rate = $_POST['e_wed_rates']; $e_thur_rate = $_POST['e_thur_rates'];
		$e_fri_rate = $_POST['e_fri_rates']; $e_sat_rate = $_POST['e_sat_rates']; $e_sun_rate = $_POST['e_sun_rates'];

		$e_monday = $_POST['e_mondays']; $e_tuesday = $_POST['e_tuesdays']; $e_wedsday = $_POST['e_wedsdays']; $e_thursday = $_POST['e_thursdays'];
		$e_friday = $_POST['fridays']; $e_saturday = $_POST['e_saturdays']; $e_sunday = $_POST['e_sundays'];

		$adult_query=""; $adult_datasets=""; $insert_query="";
		$extrabed_query=""; $extrabed_datasets=""; $e_insert_query="";
		
		for($a=0; $a < count($adult); $a++)
		{
			##monday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$monday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$mon_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$monday[$a],"price"=>$mon_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_monday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_mon_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_monday[$a],"price"=>$e_mon_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## tuesday
			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$tuesday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$tue_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$tuesday[$a],"price"=>$tue_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_tuesday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_tue_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_tuesday[$a],"price"=>$e_tue_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## wednesday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$wedsday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$wed_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$wedsday[$a],"price"=>$wed_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_wedsday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_wed_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_wedsday[$a],"price"=>$e_wed_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## thursday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$thursday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$thur_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$thursday[$a],"price"=>$thur_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_thursday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_thur_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_thursday[$a],"price"=>$e_thur_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## friday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$friday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$fri_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$friday[$a],"price"=>$fri_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_friday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_fri_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_friday[$a],"price"=>$e_fri_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## saturday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$saturday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$sat_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$saturday[$a],"price"=>$sat_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_saturday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_sat_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_saturday[$a],"price"=>$e_sat_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## sunday

			$adult_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$sunday[$a]);
			$adult_data = mysqli_data_fetch($tbL80,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$sun_rate[$a]);
				mysqli_data_update($tbL80,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$adult[$a],"day"=>$sunday[$a],"price"=>$sun_rate[$a]);
				mysqli_data_insert($tbL80,$adult_datasets,$insert_query);
			}

			$extrabed_query = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_sunday[$a]);
			$extrabed_data = mysqli_data_fetch($tbL80,'id',$extrabed_query,'noarray');
			if(isset($extrabed_data[0]) && $extrabed_data[0] >= 1) {
				$insert_query = array("id"=>$extrabed_data[0]);
				$extrabed_datasets = array("price"=>$e_sun_rate[$a]);
				mysqli_data_update($tbL80,$extrabed_datasets,$insert_query);
			} else {
				$insert_query = "";
				$extrabed_datasets = array("modeid"=>$hotelseason,"room_type_id"=>$room_types[$a],"ratetype"=>$extrabed[$a],"day"=>$e_sunday[$a],"price"=>$e_sun_rate[$a]);
				mysqli_data_insert($tbL80,$extrabed_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------
		}
	}

?>


<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
	<div class="block-element box-border-thick sml-rounded-button pads15 bottom-push-30">
		<span class="ln-display-box float-left top-pull-10 left-pull-10 nc-width-60">
			<b>Only created hotel seasons :</b>
		</span>
		<span class="ln-display-box float-right nc-width-20">
			<select name="fieldset1" id="fieldset1" required="required" onchange="getseasonTariff()">
				<?php echo $htmlresult.$season_list; ?>
			</select>
		</span>
		<span class="block-element new-line-space">
		</span>
	</div>
	<div class="block-element box-border-thick sml-rounded-button pads15 bottom-push-30 alignct">
		<?php

			if(isset($selectseason) && !empty($selectseason)) {
				?>
					<div class="block-element sml-rounded-button noscroll">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<th width="200px" class="box-border-thick-left box-border-thick-right" align="center">Room Types</th>
								<th width="100px" class="box-border-thick-right" align="center">Rate Types</th>
								<th width="100px" class="box-border-thick-right" align="center">Mon &nbsp; <b class="fa-share nobold anchor" onclick="" title="Replicate to other days"></b></th>
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
									$mon_adultrate=""; $tue_adultrate=""; $wed_adultrate=""; $thur_adultrate=""; $fri_adultrate=""; $sat_adultrate=""; $sun_adultrate="";
									$mon_extrabedrate=""; $tue_extrabedrate=""; $wed_extrabedrate=""; $thur_extrabedrate=""; $fri_extrabedrate="";
									$sat_extrabedrate=""; $sun_extrabedrate="";

									foreach ($room_types as $rm_key => $rm_value) {
										
										//adult rate day price

										$mon_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"monday");
										$mon_adultrate_data = mysqli_data_fetch($tbL80,'price',$mon_adultrate,'noarray');

										$tue_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"tuesday");
										$tue_adultrate_data = mysqli_data_fetch($tbL80,'price',$tue_adultrate,'noarray');

										$wed_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"wednesday");
										$wed_adultrate_data = mysqli_data_fetch($tbL80,'price',$wed_adultrate,'noarray');

										$thur_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"thursday");
										$thur_adultrate_data = mysqli_data_fetch($tbL80,'price',$thur_adultrate,'noarray');

										$fri_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"friday");
										$fri_adultrate_data = mysqli_data_fetch($tbL80,'price',$fri_adultrate,'noarray');

										$sat_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"saturday");
										$sat_adultrate_data = mysqli_data_fetch($tbL80,'price',$sat_adultrate,'noarray');

										$sun_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"sunday");
										$sun_adultrate_data = mysqli_data_fetch($tbL80,'price',$sun_adultrate,'noarray');

										//extrabed rate day price
										
										$mon_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"monday");
										$mon_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$mon_extrabedrate,'noarray');

										$tue_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"tuesday");
										$tue_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$tue_extrabedrate,'noarray');

										$wed_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"wednesday");
										$wed_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$wed_extrabedrate,'noarray');

										$thur_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"thursday");
										$thur_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$thur_extrabedrate,'noarray');

										$fri_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"friday");
										$fri_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$fri_extrabedrate,'noarray');

										$sat_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"saturday");
										$sat_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$sat_extrabedrate,'noarray');

										$sun_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"sunday");
										$sun_extrabedrate_data = mysqli_data_fetch($tbL80,'price',$sun_extrabedrate,'noarray');




										?>
											<tr>
												<td rowspan="2" width="200px" class="box-border-thick-left box-border-thick-right alignct"><b><?php echo $rm_value['name']; ?></b></td>
												<td width="100px" class="box-border-thick-right alignct">Adult Rate
													<input type="hidden" name="adult[]" value="adult rate">
													<input type="hidden" name="room_types[]" value="<?php echo $rm_value['id']; ?>">
												</td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="mon_rates[]" value="<?php if(isset($mon_adultrate_data[0]) && !empty($mon_adultrate_data[0])) { echo $mon_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="mondays[]" value="monday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="tue_rates[]" value="<?php if(isset($tue_adultrate_data[0]) && !empty($tue_adultrate_data[0])) { echo $tue_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="tuesdays[]" value="tuesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="wed_rates[]" value="<?php if(isset($wed_adultrate_data[0]) && !empty($wed_adultrate_data[0])) { echo $wed_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="wedsdays[]" value="wednesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="thur_rates[]" value="<?php if(isset($thur_adultrate_data[0]) && !empty($thur_adultrate_data[0])) { echo $thur_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="thursdays[]" value="thursday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="fri_rates[]" value="<?php if(isset($fri_adultrate_data[0]) && !empty($fri_adultrate_data[0])) { echo $fri_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="fridays[]" value="friday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sat_rates[]" value="<?php if(isset($sat_adultrate_data[0]) && !empty($sat_adultrate_data[0])) { echo $sat_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="saturdays[]" value="saturday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sun_rates[]" value="<?php if(isset($sun_adultrate_data[0]) && !empty($sun_adultrate_data[0])) { echo $sun_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="sundays[]" value="sunday"></td>
											</tr>
											<tr>
												<td width="100px" class="box-border-thick-right alignct">Extra Bed
													<input type="hidden" name="extrabed[]" value="extrabed rate">
												</td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_mon_rates[]" value="<?php if(isset($mon_extrabedrate_data[0]) && !empty($mon_extrabedrate_data[0])) { echo $mon_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_mondays[]" value="monday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_tue_rates[]" value="<?php if(isset($tue_extrabedrate_data[0]) && !empty($tue_extrabedrate_data[0])) { echo $tue_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_tuesdays[]" value="tuesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_wed_rates[]" value="<?php if(isset($wed_extrabedrate_data[0]) && !empty($wed_extrabedrate_data[0])) { echo $wed_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_wedsdays[]" value="wednesday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_thur_rates[]" value="<?php if(isset($thur_extrabedrate_data[0]) && !empty($thur_extrabedrate_data[0])) { echo $thur_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_thursdays[]" value="thursday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_fri_rates[]" value="<?php if(isset($fri_extrabedrate_data[0]) && !empty($fri_extrabedrate_data[0])) { echo $fri_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_fridays[]" value="friday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_sat_rates[]" value="<?php if(isset($sat_extrabedrate_data[0]) && !empty($sat_extrabedrate_data[0])) { echo $sat_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_saturdays[]" value="saturday"></td>
												<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_sun_rates[]" value="<?php if(isset($sun_extrabedrate_data[0]) && !empty($sun_extrabedrate_data[0])) { echo $sun_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_sundays[]" value="sunday"></td>
											</tr>
										<?php
									}
								}

							?>

						</table>
					</div>

					<div class="block-element alignct top-push-30">
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</div>
				<?php
			} else {
				?>
					<small class="dark-grey-font">No season selected</small>
				<?php
			}

		?>
	</div>
</form>