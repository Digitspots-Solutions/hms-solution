<?php

	$guest_no = $ftoken;
	$ths_token = $stoken;
	
	$printed_by = idget_data($tbL7,$userSignedIn,'staffname');
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;

?>

<div id="section-to-print" class="block-element" align="center">
	<div class="cs-width-900">

		<h1 class="xlarge nobold default-text-font-bold alignct"><?php echo _LONG_NAME; ?></h1>
		<h4 class="large nobold alignct"><?php echo $hotel_address; ?></h4>
		<h4 class="large nobold alignct">Tel: <?php echo $hotel_fs_phonenumber; ?>, Email: <?php echo $hotel_email; ?></h4>
		<h4 class="large nobold alignct">Printed by: <?php echo $printed_by.' on '.$printed_date; ?></h4>
		
		
		<div class="block-element top-push-15 alignlt">
			<h3 class="large nobold default-text-font-bold"><?php echo $ths_token; ?></h3><br>
		</div>
		<div class="block-element top-push-10 alignlt">
			<div class="block-element top-push-3 bottom-push-15 box-border-thick sml-rounded-button noscroll">
				<table cellpadding="0" cellspacing="0" class="ft-xxsml-size default-text-font-bold">
					<tr>
						<th align="center">Booking Number</th>
						<th align="center">Rooom Number</th>
						<th align="center">Stay Duration</th>
						<th align="center">Date of Booking</th>
						<th align="center">Booking Status</th>
						<th align="center">Booking Type</th>
					</tr>
					<?php
						
						$additionalQuery = " AND status NOT IN('No Show','Cancelled','Reserved')";
						$datasets="booking_number,roomid,checkin_date,checkout_date,datelogged,status";
						$booking_query2 = array("booking_number"=>$guest_no);
						$get_booking_data2 = mysqli_data_fetch($tbL127,$datasets,$booking_query2,'array');

						$room_name = ""; $booking_type="";

						if(is_array($get_booking_data2)) {

							foreach($get_booking_data2 as $key2 => $val2) {
							
								$room_name = idget_data($tbL56,$val2['roomid'],'roomprefix');
								$room_name .= idget_data($tbL56,$val2['roomid'],'roomnumber');

								$booking_type = idget_fdata($tbL130,'booking_number',$val2['booking_number'],'booking_type');

								?>
									<tr>
										<td align="center"><?php echo $val2['booking_number']; ?></td>
										<td align="center"><?php echo $room_name; ?></td>
										<td align="center"><?php echo date('d-m-y',strtotime($val2['checkin_date'])).' - '.date('d-m-y',strtotime($val2['checkout_date'])); ?></td>
										<td align="center"><?php echo date('d-m-y',strtotime($val2['datelogged'])); ?></td>
										
										<td align="center"><?php echo $val2['status']; ?></td>
										<td align="center"><?php echo $booking_type; ?></td>
									</tr>
								<?php
							}
						}
						
					?>
				</table>
			</div>
			

		</div>
	</div>
</div>

<div class="block-element top-pull-50 alignct">
	<input type="button" value="Print" class="anchor" onclick="window.print()">
</div>