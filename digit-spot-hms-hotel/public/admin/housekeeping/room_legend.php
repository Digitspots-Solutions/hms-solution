<?php $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Here is list of room legend
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php

	$dataproperty = "id,legendname,colorcode,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL38,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		foreach($dataCollect as $theader => $tdata)
		{
			?>
				<span class="ln-display-box float-left right-push-50 bottom-push-20">
					<div class="ln-display-box float-left cs-width-20 cs-height-20" style="background:<?php echo $tdata["colorcode"]; ?>">&nbsp;</div>
					<div class="ln-display-box float-left left-push-15"><?php echo $tdata["legendname"]; ?></div>
					<div class="block-element new-line-space"></div>
				</span>
			<?php
		}
		?>
			<span class="block-element new-line-space">
			</span>
		<?php
	}
	else
	{
		?>
			<small class="block-element dark-grey-font alignct top-push-50">No room legend found..</small>
		<?php
	}

?>