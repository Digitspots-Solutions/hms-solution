<?php
	
	//get number of active days
	$additionalQuery = " GROUP BY day";
	$day_query = array("status"=>"Active","deletedata"=>0);
	$day_select = mysqli_data_fetch($tbL146,'day',$day_query,'array');

	$days = array();
	$days_tab = '';

	if(is_array($day_select)) {
		foreach ($day_select as $day_key => $day_value) {
			array_push($days,$day_value['day']);
			$days_tab .= '<th align="center">'.ucwords($day_value['day']).'</th>';
		}
	}

	if(is_array($days) && count($days) >= 1) { $day_coulmn = count($days); }
	else { $day_coulmn = 0; }

	//get list of room type
	$additionalQuery = " GROUP BY room_type_id";
	$rmtype_query = array("status"=>"Active","deletedata"=>0);
	$rmtype_select = mysqli_data_fetch($tbL146,'room_type_id',$rmtype_query,'array');
?>

<div class="nc-width-100 sml-rounded-button noscroll">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td width="200px" align="center" class="box-border-thick-top box-border-thick-left default-text-font-bold">Room Type</td><td colspan="<?php echo $day_coulmn; ?>" align="center" class="box-border-thick-top box-border-thick-right box-border-thick-left default-text-font-bold">Tariffs</th>
		</tr>
		<tr>
			<th width="200px" align="center">&nbsp;</th>
			<?php echo $days_tab; ?>
		</tr>
		
		<?php
			
			if(is_array($rmtype_select)) {
				
				$get_th_room_name = ""; $tariff_query = "";
				
				foreach($rmtype_select as $rm_key => $rm_value) {
					$get_th_room_name = idget_data($tbL52,$rm_value['room_type_id'],'name');
					?>
						<tr>
							<td align="center"><?php echo $get_th_room_name; ?></td>
							<?php
								
								$write_tariff = "";
								
								foreach($days as $list_day_tf) {
									
									$additionalQuery = "";
									$tariff_query = array("room_type_id"=>$rm_value['room_type_id'],"day"=>$list_day_tf);
									$tariff_select = mysqli_data_fetch($tbL146,'price',$tariff_query,'noarray');

									?>
										<td align="center"><?php if(isset($tariff_select[0]) && $tariff_select[0] >= 1) { $write_tariff = write_amountF(0,$tariff_select[0]); echo $write_tariff; } ?></td>
									<?php
								}
							?>
						</tr>
					<?php
				}
			}

		?>

	</table>
</div>