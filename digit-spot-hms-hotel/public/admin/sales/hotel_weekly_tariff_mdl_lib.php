<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can setup your room weekly tariff
 	</span>
 	<span class="ln-display-box float-right right-pull-5">
		<h3 class="large nobold default-text-font-bold">Weekend Fares</h3>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	#---------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_142); //create a table for this post

		$room_types = $_POST['room_types'];
		
		$mon_rate = $_POST['mon_rates']; $tue_rate = $_POST['tue_rates']; $wed_rate = $_POST['wed_rates'];
		$thur_rate = $_POST['thur_rates']; $fri_rate = $_POST['fri_rates']; $sat_rate = $_POST['sat_rates'];
		$sun_rate = $_POST['sun_rates'];

		$monday = $_POST['mondays']; $tuesday = $_POST['tuesdays']; $wedsday = $_POST['wedsdays'];
		$thursday = $_POST['thursdays']; $friday = $_POST['fridays']; $saturday = $_POST['saturdays'];
		$sunday = $_POST['sundays'];

		$adult_query=""; $adult_datasets=""; $insert_query="";
		$extrabed_query=""; $extrabed_datasets=""; $e_insert_query="";
		
		for($a=0; $a < count($room_types); $a++)
		{
			##monday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$monday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$mon_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$monday[$a],"price"=>$mon_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## tuesday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$tuesday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$tue_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$tuesday[$a],"price"=>$tue_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## wednesday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$wedsday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$wed_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$wedsday[$a],"price"=>$wed_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## thursday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$thursday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$thur_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$thursday[$a],"price"=>$thur_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## friday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$friday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$fri_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$friday[$a],"price"=>$fri_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## saturday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$saturday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$sat_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$saturday[$a],"price"=>$sat_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------

			## sunday

			$adult_query = array("room_type_id"=>$room_types[$a],"day"=>$sunday[$a]);
			$adult_data = mysqli_data_fetch($tbL146,'id',$adult_query,'noarray');
			if(isset($adult_data[0]) && $adult_data[0] >= 1) {
				$insert_query = array("id"=>$adult_data[0]);
				$adult_datasets = array("price"=>$sun_rate[$a]);
				mysqli_data_update($tbL146,$adult_datasets,$insert_query);
			} else {
				$insert_query = "";
				$adult_datasets = array("room_type_id"=>$room_types[$a],"day"=>$sunday[$a],"price"=>$sun_rate[$a]);
				mysqli_data_insert($tbL146,$adult_datasets,$insert_query);
			}

			#-------------------------------------------------------------------------------------------------------------------------------------------------------
		}

		$saynotify = 1;
		$notifytype = 2;

		$post_header = "Notification";
		$post_message = "Weekly tariff setup was successful";

		//create a log file
		$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently update room weekly tariff","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');
	}

?>


<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
		
	<div class="block-element sml-rounded-button noscroll">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th width="200px" class="box-border-thick-left box-border-thick-right" align="center">Room Types</th>
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

					$count_r = 0;
					
					foreach ($room_types as $rm_key => $rm_value) {
						
						//adult rate day price

						$count_r += 1;

						$mon_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"monday");
						$mon_adultrate_data = mysqli_data_fetch($tbL146,'price',$mon_adultrate,'noarray');

						$tue_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"tuesday");
						$tue_adultrate_data = mysqli_data_fetch($tbL146,'price',$tue_adultrate,'noarray');

						$wed_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"wednesday");
						$wed_adultrate_data = mysqli_data_fetch($tbL146,'price',$wed_adultrate,'noarray');

						$thur_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"thursday");
						$thur_adultrate_data = mysqli_data_fetch($tbL146,'price',$thur_adultrate,'noarray');

						$fri_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"friday");
						$fri_adultrate_data = mysqli_data_fetch($tbL146,'price',$fri_adultrate,'noarray');

						$sat_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"saturday");
						$sat_adultrate_data = mysqli_data_fetch($tbL146,'price',$sat_adultrate,'noarray');

						$sun_adultrate = array("room_type_id"=>$rm_value['id'],"day"=>"sunday");
						$sun_adultrate_data = mysqli_data_fetch($tbL146,'price',$sun_adultrate,'noarray');

						?>
							<tr>
								<td width="200px" class="box-border-thick-left box-border-thick-right alignct"><b><?php echo $rm_value['name']; ?></b><input type="hidden" name="room_types[]" value="<?php echo $rm_value['id']; ?>"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="mon_rates[]" id="mon<?php echo $count_r; ?>" value="<?php if(isset($mon_adultrate_data[0]) && !empty($mon_adultrate_data[0])) { echo $mon_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="mondays[]" value="monday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="tue_rates[]" id="tue<?php echo $count_r; ?>" value="<?php if(isset($tue_adultrate_data[0]) && !empty($tue_adultrate_data[0])) { echo $tue_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="tuesdays[]" value="tuesday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="wed_rates[]" id="wed<?php echo $count_r; ?>" value="<?php if(isset($wed_adultrate_data[0]) && !empty($wed_adultrate_data[0])) { echo $wed_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="wedsdays[]" value="wednesday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="thur_rates[]" id="thur<?php echo $count_r; ?>" value="<?php if(isset($thur_adultrate_data[0]) && !empty($thur_adultrate_data[0])) { echo $thur_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="thursdays[]" value="thursday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="fri_rates[]" id="fri<?php echo $count_r; ?>" value="<?php if(isset($fri_adultrate_data[0]) && !empty($fri_adultrate_data[0])) { echo $fri_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="fridays[]" value="friday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sat_rates[]" id="sat<?php echo $count_r; ?>" value="<?php if(isset($sat_adultrate_data[0]) && !empty($sat_adultrate_data[0])) { echo $sat_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="saturdays[]" value="saturday"></td>
								<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="sun_rates[]" id="sun<?php echo $count_r; ?>" value="<?php if(isset($sun_adultrate_data[0]) && !empty($sun_adultrate_data[0])) { echo $sun_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="sundays[]" value="sunday"></td>
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
				
	
</form>


<script>

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

</script>