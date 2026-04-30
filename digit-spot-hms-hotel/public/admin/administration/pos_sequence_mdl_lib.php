<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>
<?php //$get_posstore = select_dt_fetch('status','Active',$tbL14,'id','posname'); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: select the pos store to start or change the order sequence
 	</span>
 	<span class="ln-display-box float-right">
		
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<script>
	
	function setupos() {
		var pos = document.getElementById('fieldset1').value;
		window.location.href = '?logs=<?php echo $logs; ?>&selectpos='+pos;
	}

</script>

<?php

	$query_stores = array("status"=>"Active","deletedata"=>0);
	$for_stores = mysqli_data_fetch($tbL14,'id,posname',$query_stores,'array');

	$pos_stores = "";

	if(is_array($for_stores)) {
		foreach($for_stores as $key => $val) {
			$get_posstore .= '<option value="'.$val['id'].'">'.$val['posname'].'</option>';
		}
	} else {
		$get_posstore .= '<option value="0">No Outlets</option>';
	}
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_68); //create a table for this post

		$fieldset1 = $_POST['fieldset1'];
		$fieldset2 = $_POST['fieldset2'];
		$fieldset3 = $_POST['fieldset3'];


		$insert_dataproperty = array("posid"=>$fieldset1,"prefixtext"=>$fieldset2,"startnumber"=>$fieldset3);

		$insert_constrain = array("posid"=>$fieldset1);
		$data_inserted = mysqli_data_insert($tbL72,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Setup new pos sequence","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Pos sequence has been setup successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}


	#--------------------------------------------------------------------------------------------------------------------------------


	if(isset($_POST['updatebutton']))
	{
		$fieldset1 = $_POST['fieldset1'];
		$fieldset2 = $_POST['fieldset2'];
		$fieldset3 = $_POST['fieldset3'];


		$insert_dataproperty = array("prefixtext"=>$fieldset2);

		$insert_constrain = array("posid"=>$fieldset1);
		$data_inserted = mysqli_data_update($tbL72,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change pos sequence","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Pos sequence was updated successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

?>

<div class="block-element bottom-push-30" align="center">
	<div class="nc-width-40">
		<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
			<span class="block-element bottom-push-10 box-border-thick pads15 sml-rounded-button">
				<small class="block-element bottom-push-3 blue-font left-pull-5 alignlt"><b>OUTLET/POS</b></small>
				<select name="fieldset1" id="fieldset1" required="required" onchange="setupos()">
					<?php
						
						if(isset($_GET['selectpos']) && is_numeric($_GET['selectpos'])) {
							$selectposstore = escape_data($_GET['selectpos']);
							$posstore = idget_data($tbL14,$selectposstore,'posname');
							$htmlresult = '<option value="'.$selectposstore.'" selected="selected">'.$posstore.'</option>';
						} else {
							$selectposstore = '';
							$htmlresult = '<option value="" selected="selected">Select Pos Store</option>';
						}

						echo $htmlresult.$get_posstore;

					?>
				</select>
			</span>

			<?php

				if(isset($selectposstore) && !empty($selectposstore))
				{
					$posid = array("posid"=>$selectposstore);
					$get_pos_sqn = mysqli_data_fetch($tbL72,'id,prefixtext,startnumber',$posid,'noarray');

					if(isset($get_pos_sqn[0]) && $get_pos_sqn[0] >= 1) {
						$avail=1;
						$prefix_text = $get_pos_sqn[1];
						$nextnumber = $get_pos_sqn[2];
						$editable = 'readonly="readonly"';
						$maxlength = '';
					} else {
						$avail=0;
						$pn = explode(' ', $posstore);
						if(count($pn) >= 2) {
							$prefix_text = '';
							foreach($pn as $psn) {
								$prefix_text .= substr($psn,0,1);
							}
						} else {
							$prefix_text = substr(strtoupper($posstore),0,3);
						}

						$nextnumber = 1001;
						$editable = '';
						$maxlength = ' maxlength="4"';
					}

					

					?>
						<span class="block-element bottom-push-10 box-border-thick pads15 sml-rounded-button">
							<small class="block-element bottom-push-10 blue-font left-pull-5 alignlt"><b>POS BOOKING SEQUENCE</b></small>
							<div class="ln-display-box float-left nc-width-45">
								<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">Prefix Text</small>
								<input type="text" name="fieldset2" id="fieldset2" value="<?php echo $prefix_text; ?>" required="required">
							</div>
							<div class="ln-display-box float-right nc-width-45">
								<small class="block-element bottom-push-3 dark-grey-font left-pull-5 alignlt">Next Number</small>
								<input type="text" name="fieldset3" id="fieldset3" value="<?php echo $nextnumber; ?>" pattern="\d*" <?php echo $maxlength.$editable; ?> required="required">
							</div>
							<div class="block-element new-line-space">
							</div>
						</span>

						<br><br>

					<?php

					if(isset($avail) && $avail == 1) {
						?>
							<input type="submit" name="updatebutton" value="Update Sequence" class="submit pads10 black-white-state rounded-button nc-width-60">
						<?php
					} else {
						?>
							<input type="submit" name="submitbutton" value="Save Sequence" class="submit pads10 black-white-state rounded-button nc-width-60"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
						<?php
					}
				}

			?>
		</form>
	</div>
</div>