<div class="nc-width-100 sml-rounded-button noscroll">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th width="160px" align="center">Type</th>
			<th width="120px" align="center">Room</th>
			<th width="120px" align="center">Extra Bed</th>
		</tr>

		<?php

			$additionalQuery = "";
			$dataproperty = "id,name,shortname,detail,adult,child,defaultprice,baseprice,extrabedprice";
			$constrain = array("deletedata"=>0);
			$row = "array";

			$room_types = mysqli_data_fetch($tbL52,$dataproperty,$constrain,$row);

			if(is_array($room_types))
			{
				foreach ($room_types as $rm_key => $rm_value) {
					?>
						<tr>
							<td width="160px" align="center"><?php echo $rm_value['name']; ?></td>
							<td width="120px" align="center">&#8358; <?php echo number_format($rm_value['defaultprice'],2); ?></td>
							<td width="120px" align="center">&#8358; <?php echo number_format($rm_value['extrabedprice'],2); ?></td>
						</tr>
					<?php
				}
			}

		?>

	</table>
</div>