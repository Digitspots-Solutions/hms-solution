<?php
	//$booking_number = $ftoken;
	//$ths_token = $stoken;

	$wgt_cur_room_id = $ths_token;
	
	$room_prefix = idget_data($tbL56,$wgt_cur_room_id,'roomprefix');
	$room_number = idget_data($tbL56,$wgt_cur_room_id,'roomnumber');
	$block_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'blockid');
	$block_name = idget_data($tbL49,$block_id,'name');

	$room_type_id = idget_fdata($tbL56,'id',$wgt_cur_room_id,'room_type_id');
	$room_type_name = idget_data($tbL52,$room_type_id,'name');

	$cpn_constrain = array("room_type_id"=>$room_type_id,"deletedata"=>0,"roomstatus"=>1);
    $cpn_data = mysqli_data_fetch($tbL56,'id,roomprefix,roomnumber',$cpn_constrain,'array');

    $room_availability = '';

    if(is_array($cpn_data)) {
    	
    	$housekeeping_room_state = '';
    
    	foreach($cpn_data as $key => $value) {
    		
    		$hrs_query = array("roomid"=>$value['id']);
    		$hrs_data = mysqli_data_fetch($tbL94,'housekeeping_stateid,room_status_id',$hrs_query,'noarray');
    		
    		if(isset($hrs_data[0]) && $hrs_data[0] >= 1) {
    			$housekeeping_room_state = '['.idget_data($tbL36,$hrs_data[0],'legendname').']';
    		} else {
    			$housekeeping_room_state = '['.$default_housekeeping_legend.']';
    		}

    		if($hrs_data[1] != 3 && $hrs_data[1] != 6 && $hrs_data[1] != 7) {
    			$room_availability .='<option value="'.$value['id'].'">'.$value['roomprefix'].$value['roomnumber'].' '.$housekeeping_room_state.'</option>';
    		}
    	}
	} else {
		$room_availability .='<option value="" selected>No Option!</option>';
	}

	include "post_booking_tokens.php";

?>
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Swap Room</h2></legend>
			<div class="block-element cs-height-10"></div>
			<h3 class="large nobold alignct">Note: Only available rooms are applicable</h3><br>
			<h3 class="large nobold default-text-font-bold alignct"><?php echo $room_type_name; ?></h3>
			<div class="block-element top-push-10">
				<span class="ln-display-box float-left nc-width-40 right-pull-20 top-pull-10">
					<h4 class="large nobold alignct"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</h4>
				</span>
				<span class="ln-display-box float-left nc-width-20 right-pull-20 alignct top-pull-10">
					<h1 class="large nobold mbri-right"></h1>
				</span>
				<span class="ln-display-box float-left nc-width-40">
					<select name="wgtroom" id="wgtroom" required>
						<option value="" selected="selected">Swap to:</option>
						<?php echo $room_availability; ?>
					</select>
				</span>
				<span class="block-element new-line-space">
				</span>
			</div>
		</fieldset>

		<div class="block-element top-pull-50 alignct">
			<input type="hidden" name="wgtcuroom" id="wgtcuroom" value="<?php echo $room_prefix.$room_number; ?>">
			<input type="hidden" name="wgtcuroomid" id="wgtcuroomid" value="<?php echo $wgt_cur_room_id; ?>">
			<input type="hidden" name="wgtroomtype" id="wgtroomtype" value="<?php echo $room_type_id; ?>">
			<input type="hidden" name="wgtpstbk" id="wgtpstbk" value="<?php echo $booking_number; ?>">
			<input type="submit" name="swaproombutton" value="Apply" class="nc-width-80 submit top-pull-10 bottom-pull-10 blue-white-state rounded-button">
		</div>
	</div>
</form>