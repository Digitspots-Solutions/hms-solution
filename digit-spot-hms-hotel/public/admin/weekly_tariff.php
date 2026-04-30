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

<script>
	
	function getseasonTariff() {
		var season = document.getElementById('fieldset1').value;
		window.location.href = '?selectseason='+season;
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
		$season_status = idget_fdata($tbL79,'modeid',$selectseason,'status');
		$htmlresult = '<option value="'.$selectseason.'" selected="selected">'.$season_name.' ('.$season_status.')</option>';
	} else {
		$selectseason = '';
		$htmlresult = '<option value="" selected="selected">Choose</option>';
	}


	#---------------------------------------------------------------------------------------------------------------

?>

<div class="block-element pads20">
	
		
		<span class="ln-display-box float-left top-pull-10 left-pull-10 nc-width-60">
			<b>Weekly Tariffs</b>
		</span>
		<span class="ln-display-box float-right nc-width-20">
			<select name="fieldset1" id="fieldset1" required="required" onchange="getseasonTariff()">
				<?php echo $htmlresult.$season_list; ?>
			</select>
		</span>
		<span class="block-element new-line-space">
		</span>
		
		<div class="block-element box-border-thick sml-rounded-button pads15 top-push-10 bottom-push-10 alignct">
			<?php

				if(isset($selectseason) && !empty($selectseason)) {
					?>
						<div class="block-element sml-rounded-button noscroll">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<th width="200px" class="box-border-thick-left box-border-thick-right" align="center">Room Types</th>
									<th width="100px" class="box-border-thick-right" align="center">Rate Types</th>
									<th width="100px" class="box-border-thick-right" align="center">Mon</th>
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

											$wed_adultrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"adult rate","day"=>"wednessday");
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

											$wed_extrabedrate = array("modeid"=>$selectseason,"room_type_id"=>$rm_value['id'],"ratetype"=>"extrabed rate","day"=>"wednessday");
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
													<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="wed_rates[]" value="<?php if(isset($wed_adultrate_data[0]) && !empty($wed_adultrate_data[0])) { echo $wed_adultrate_data[0]; } else { echo $rm_value['defaultprice']; } ?>"><input type="hidden" name="wedsdays[]" value="wednessday"></td>
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
													<td width="100px" class="box-border-thick-right"><input type="number" step="any" name="e_wed_rates[]" value="<?php if(isset($wed_extrabedrate_data[0]) && !empty($wed_extrabedrate_data[0])) { echo $wed_extrabedrate_data[0]; } else { echo $rm_value['extrabedprice']; } ?>"><input type="hidden" name="e_wedsdays[]" value="wednessday"></td>
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
					<?php
				} else {
					?>
						<small class="dark-grey-font">No season selected</small>
					<?php
				}

			?>
		</div>
	
</div>