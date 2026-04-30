<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>


<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: indicate hotel booking sequence label
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>


<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_72); //create a table for this post

		$fieldset1 = str_replace(' ','',strtoupper($_POST['fieldset1']));
		$fieldset2 = $_POST['fieldset2'];

		$insert_dataproperty = array("prefixtext"=>$fieldset1);

		if(isset($fieldset2) && $fieldset2 >= 1) {
			$insert_constrain = array("id"=>$fieldset2);
			$data_inserted = mysqli_data_update($tbL76,$insert_dataproperty,$insert_constrain);
		} else {
			$insert_constrain = "";
			$data_inserted = mysqli_data_insert($tbL76,$insert_dataproperty,$insert_constrain);
		}
		

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Update hotel booking sequence","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Hotel booking sequence was updated successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#--------------------------------------------------------------------------------------------------------------------------------

	$hotel_query = '';
	$get_hotel_data = mysqli_data_fetch($tbL76,'id,prefixtext',$hotel_query,'noarray');

?>

<div class="block-element bottom-push-30" align="center">
	<div class="nc-width-40">
		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<span class="block-element bottom-push-10 box-border-thick pads15 sml-rounded-button">
				<small class="block-element bottom-push-3 blue-font left-pull-5 alignlt"><b>HOTEL BOOKING SEQUENCE</b></small>
				<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter prefix text" value="<?php if(isset($get_hotel_data[1]) && !empty($get_hotel_data[1])) { echo $get_hotel_data[1]; } ?>" required="required"><input type="hidden" name="fieldset2" id="fieldset2" value="<?php if(isset($get_hotel_data[0]) && !empty($get_hotel_data[0])) { echo $get_hotel_data[0]; } else { echo 0; } ?>">
			</span>

			<br><br>

			<input type="submit" name="submitbutton" value="Save Sequence" class="submit pads10 black-white-state rounded-button nc-width-60">
		</form>
	</div>
</div>