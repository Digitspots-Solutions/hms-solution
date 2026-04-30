<?php
	$booking_number = $ftoken;
	$ths_token = $stoken;

	include "post_booking_tokens.php";

	$room_query = array("booking_number"=>$booking_number,"status"=>"CheckedIn");
	$room_sql = mysqli_data_fetch($tbL127,'id,room_type_id,roomid,customerid,checkin_date,checkout_date',$room_query,'array');
?>

<h3 class="large nobold default-text-font-bold alignct">GUEST BOOKING DURATION (<?php echo $booking_number; ?>)</h3>
<p>&nbsp;</p>

<p class="alignct">Note: To reverse guest duration, select the new check-out date and check the box for the room you want to effect</p>
<p>&nbsp;</p>

<form action="" method="post">

	<input type="hidden" name="bookingnumber" value="<?php echo $booking_number; ?>">

	<fieldset>
		<legend class="ft-sml-size">CheckOut Date</legend>
		<input type="text" name="checkout" id="checkout" placeholder="Click here to choose date" class="no-back-black" onfocus="pickdate()" required>
	</fieldset>

	<p>&nbsp;</p>

	<table cellpadding="5" cellspacing="0">

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Check-in Date</td>
			<td>Check-out Date</td>
		</tr>

		<?php

			if(is_array($room_sql) && count($room_sql) > 0) {

				$room_type_id = ""; $room_type = ""; $room_prefix = ""; $room_number = "";

				foreach($room_sql as $key => $val) {

					$room_type_id = $val['room_type_id'];
					$room_type = idget_data($tbL52,$room_type_id,'name');

					$room_prefix = idget_data($tbL56,$val['roomid'],'roomprefix');
					$room_number = idget_data($tbL56,$val['roomid'],'roomnumber');


					//$bill_query = array("booking_number"=>$booking_number,"room_type_id"=>$val['room_type_id'],"roomid"=>$val['roomid'],"customerid"=>$val['customerid'],"room_status"=>"CheckedIn","deletedata"=>0);
					//$bill_sql = mysqli_data_fetch($tbL134,'id,day,weekday,bill_date',$bill_query,'array');

					?>

					<tr>
						<td><input type="checkbox" name="checkers[]" value="<?php echo $val['id'].'#'.$val['customerid'].'#'.$val['room_type_id'].'#'.$val['roomid'].'#'.$val['checkout_date'].'#'.$room_prefix.$room_number; ?>"></td>
						<td><h3 class="large nobold default-text-font-bold"><?php echo strtoupper($room_type).' &mdash; '.$room_prefix.$room_number; ?></h3></td>
						<td class="steel-blue-font"><?php echo date('d-m-Y',strtotime($val['checkin_date'])); ?></td>
						<td class="steel-blue-font"><?php echo date('d-m-Y',strtotime($val['checkout_date'])); ?></td>
					</tr>
			
					<?php
				}
			}

		?>

	</table>

	<p class="top-pull-20"><input type="submit" name="reversebooking" value="Apply" class="nc-width-100 submit top-pull-15 bottom-pull-15 blue-white-state rounded-button"></p>

</form>


<script>

	function pickdate() {
		document.getElementById('checkout').type = 'date';
		document.getElementById('checkout').showPicker();
	}

</script>