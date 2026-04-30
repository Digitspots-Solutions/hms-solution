<table cellpadding="0" cellspacing="0">
	<tr>
		<th width="50px" align="center">&nbsp;</th>
		<th width="150px" align="left"><small class="ft-xxsml-size">Room Types</small></th>
		<th width="150px" align="left"><small class="ft-xxsml-size">Room Nos.</small></th>
		<th width="50px" align="left"><small class="ft-xxsml-size">Adults</small></th>
		<th width="50px" align="left"><small class="ft-xxsml-size">Child</small></th>
		<th width="120px" align="left"><small class="ft-xxsml-size">Occupancy Type</small></th>
		<th width="100px" align="left"><small class="ft-xxsml-size">Check-In</small></th>
		<th width="100px" align="left"><small class="ft-xxsml-size">Check-Out</small></th>
		<th width="100px" align="left"><small class="ft-xxsml-size">Status</small></th>
		<th width="100px" align="left"><small class="ft-xxsml-size">Allow Bill</small></th>
		<th width="100px" align="left"><small class="ft-xxsml-size">Billing Status</small></th>
		<th width="150px" align="center"><small class="ft-xxsml-size">Action</small></th>
		<th align="left"></th>
		<th width="350px" align="left" class="box-border-thick-left">
			<small class="block-element white-font left-pull-10 top-pull-3 bottom-pull-3 box-border-thick-bottom add-bold">Guest Detail</small>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th width="135px" align="center"><small class="ft-xxsml-size">First Name</small></th>
					<th width="135px" align="center"><small class="ft-xxsml-size">Last Name</small></th>
					<th width="100px" align="center"><small class="ft-xxsml-size">Phone No.</small></th>
					<th width="30px" align="center"><small class="ft-xxsml-size">Action</small></th>
				</tr>
			</table>
		</th>
	</tr>
	
	<?php

		$gog = 0; $checker_id=0;
		$occupancy_type = select_dt_fetch('status','Active',$tbL51,'id','name');
		$getroomtype = select_dt_fetch('status','Active',$tbL52,'id','name');
		
		$room_type_id=""; $room_type=""; $block_id=""; $block_name=""; $room_prefix=""; $room_number="";
		$cur_occupancy_type=""; $gcheckin=""; $gcheckout=""; $this_guest=""; $gu_name="";
		$max_adults=""; $max_childs=""; $disabled_this_room=""; $isroomno = 0; $getroomlist = "";
		$guest_fname=""; $guest_lname=""; $guest_phone=""; $extendtrigger = ""; $isb2 = "";
		$nodateselect=""; $extendcolor = ""; $extendtitle = ""; $rsArry2 = "";

		$rm_datasets = "id,customerid,room_type_id,roomid,housekeeping_stateid,room_status_id,adult,child,isextrabed,occupancy_type,noofdays,reservation,holdtill,checkin_date,checkin_time,checkout_date,checkout_time,cancel_date,cancel_time,checkin_byuser,checkout_byuser,cancel_byuser,remarks,cancel_policy,cancel_reason,early_checkin_charges,late_checkout_charges,cancellation_charges,datelogged,timelogged,status,isdiscount";

		$rm_occupancy_query = array("booking_number"=>$booking_number,"deletedata"=>0);
		$get_occupancy_data = mysqli_data_fetch($tbL127,$rm_datasets,$rm_occupancy_query,'array');

		foreach($get_occupancy_data as $gokey => $govalue) {
			
			if($govalue['roomid'] > 0) {
				//$room_type_id = idget_fdata($tbL56,'id',$govalue['roomid'],'room_type_id');
				$isroomno = 1;
			} else {
				//$rmt_query = array("booking_number"=>$booking_number,"day"=>1,"deletedata"=>0);
				//$get_rmt_data = mysqli_data_fetch($tbL134,'room_type_id',$rmt_query,'noarray');
				//$room_type_id = $get_rmt_data[0];
				$isroomno = 0;
			}

			$room_type_id = $govalue['room_type_id'];
			include "eget_rooms.php";
			
			$room_type = idget_data($tbL52,$room_type_id,'name');
			$max_adults = idget_data($tbL52,$room_type_id,'adult');
			$max_childs = idget_data($tbL52,$room_type_id,'child');

			$block_id = idget_fdata($tbL56,'id',$govalue['roomid'],'blockid');
			$block_name = idget_data($tbL49,$block_id,'name');
			$room_prefix = idget_data($tbL56,$govalue['roomid'],'roomprefix');
			$room_number = idget_data($tbL56,$govalue['roomid'],'roomnumber');
			$cur_occupancy_type = idget_data($tbL51,$govalue['occupancy_type'],'name');

			$gcheckin = write_dateF($gh_get_date_format,$govalue['checkin_date']);
			$gcheckout = write_dateF($gh_get_date_format,$govalue['checkout_date']);

			$guest_fname = idget_data($tbL102,$govalue['customerid'],'fname');
			$guest_lname = idget_data($tbL102,$govalue['customerid'],'lname');
			$guest_phone = idget_data($tbL102,$govalue['customerid'],'mobile');
			$isb2 = idget_data($tbL102,$govalue['customerid'],'isbill_to_room');
			
			if($isb2 == 'Yes') {
				$rsArry2 = idget_data($tbL102,$govalue['customerid'],'billing_services');
				$rsArry2 = explode(',',$rsArry2);
			} else {
				$rsArry2 = "";
			}
			
			$checker_id += 1;

			if(isset($govalue['cancel_policy']) && $govalue['cancel_policy'] >= 1) {
				$disabled_this_room="disabled"; $disabled_color = "dark-grey-theme";
				$nodateselect=" readonly";
				$extendcolor = "nopads no-back-black";
				$extendtitle = "";
				$extendtrigger = "";
			} else {
				if(isset($govalue['status']) && ($govalue['status'] == 'CheckedOut' || $govalue['status'] == 'Cancelled' || $govalue['status'] == 'Swapped' || $govalue['status'] == 'Upgraded' || $govalue['status'] == 'Downgraded' || $govalue['status'] == 'No Show')) {
					$disabled_this_room="disabled"; $disabled_color = "dark-grey-theme";
					$nodateselect=" readonly";
					$extendcolor = "nopads no-back-black";
					$extendtitle = "";
					$extendtrigger = "";
				} else {
					$nodateselect="";
					$extendcolor = "nopads no-back-black";
					$extendtitle = "";
					$extendtrigger = "";

					if($govalue['status'] == 'CheckedIn') {
						$nodateselect=" readonly";
						$extendcolor = "nopads no-back-blue anchor";
						$extendtitle = "Click to extend duration of this room";
						$extendtrigger = "singleExt(this)";
					}

					$disabled_this_room=""; $disabled_color = "";
				}
			}

			$isroomcharged_query = array("booking_number"=>$booking_number,"roomid"=>$govalue['roomid'],"bill_date"=>$server_get_date,"charge"=>"yes","ischarged"=>1,"deletedata"=>0); $isroomcharged = mysqli_data_checkr($tbL134,'(*)',$isroomcharged_query);
			//$isroomcharged = mysqli_data_fetch($tbL134,'ischarged',$isroomcharged_query,'noarray');

			$isroomcredited_query = array("booking_number"=>$booking_number,"status"=>0,"deletedata"=>0);
			$isroomcredited = mysqli_data_checkr($tbL138,'(*)',$isroomcredited_query);



			?>
				<tr class="<?php echo $disabled_color; ?>">
					<td width="50px" align="center">
						<input type="radio" name="checkers" id="chk<?php echo $checker_id; ?>" value="<?php echo $govalue['id']; ?>" onclick="roomChk('chk<?php echo $checker_id; ?>'); buttonCtrl(this.lang)" lang="<?php echo $govalue['status']; ?>" title="<?php echo $room_prefix.$room_number; ?>" <?php echo $disabled_this_room; ?>>
					</td>
					<td width="150px" align="left" class="right-pull-5">
						<?php if($govalue['status'] == 'Reserved'): ?>

							<select name="roomtype<?php echo $govalue['id']; ?>" id="roomtype<?php echo $govalue['id']; ?>" class="nopads" onchange="getdata('roomnumber<?php echo $govalue['id']; ?>','eget-rooms','roomtype<?php echo $govalue['id']; ?>','dropbox')" required>
								<option value="<?php echo $room_type_id; ?>" selected="selected"><?php echo $room_type; ?></option>
								<?php echo $getroomtype; ?>
							</select>

						<?php elseif($govalue['status'] == 'CheckedIn'): ?>

						<span class="ln-display-box float-left right-pull-7 ft-xsml-size"><?php echo $room_type; ?></span>
						<span class="ln-display-box float-left"><?php if($disabled_this_room == '') { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/rack_rate_icon.png" class="anchor" onclick="rackrates(<?php echo $room_type_id; ?>)" title="Rack Rates"><?php } ?></span><span class="block-element new-line-space"></span>

						<?php else: ?>

						<span class="ln-display-box float-left right-pull-7 ft-xsml-size"><?php echo $room_type; ?></span>
						<span class="ln-display-box float-left">&nbsp;</span><span class="block-element new-line-space"></span>

						<?php endif; ?>
					</td>
					<td width="150px" align="left">
						<!--sudroom: swaproom or upgrade/downgrade-->

						<?php if($govalue['status'] == 'Reserved'): ?>

							<div class="right-pull-5"><select name="roomnumber<?php echo $govalue['id']; ?>" id="roomnumber<?php echo $govalue['id']; ?>" class="nopads" onchange="kpr('roomnumber<?php echo $govalue['id']; ?>'); check_room_enabled(this.value,'roomnumber<?php echo $govalue['id']; ?>');"><?php if($isroomno == 1) { ?><option value="<?php echo $govalue['roomid']; ?>" selected><?php echo $room_prefix.$room_number; ?></option><?php } echo $getroomlist; ?></select></div>

						<?php elseif($govalue['status'] == 'CheckedIn'): ?>

						<span class="ln-display-box float-left right-pull-7 ft-xsml-size blue-font anchor" onclick="popmodalframe('frontdesk','sudroom','<?php echo $booking_number; ?>',<?php echo $govalue['roomid']; ?>,500,500)"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</span><span class="ln-display-box float-left"><?php if($disabled_this_room == '') { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/room_type_tariff_change_icon.png" class="anchor" title="Room Type Tariff Change" onclick="popmodalframe('frontdesk','roomtypetariffchange','<?php echo $booking_number; ?>',<?php echo $govalue['roomid']; ?>,500,500)"><?php } ?></span><span class="block-element new-line-space"></span>

						<?php else: ?>

						<span class="ln-display-box float-left right-pull-7 ft-xsml-size dark-grey-font"><?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)</span><span class="ln-display-box float-left">&nbsp;</span>
						<span class="block-element new-line-space"></span>

						<?php endif; ?>
					</td>
					<td width="50px" align="left" class="right-pull-3">
						<select name="adults<?php echo $govalue['id']; ?>" class="nopads" required="required">
							<option value="<?php echo $govalue['adult']; ?>" selected><?php echo $govalue['adult']; ?></option>
							<?php
								for($a=1; $a<=count($max_adults); $a++) {
									?><option value="<?php echo $a; ?>"><?php echo $a; ?></option><?php
								}
							?>
						</select>
					</td>
					<td width="50px" align="left" class="right-pull-3">
						<select name="childs<?php echo $govalue['id']; ?>" class="nopads" required="required">
							<option value="<?php echo $govalue['child']; ?>" selected><?php echo $govalue['child']; ?></option>
							<?php
								for($c=1; $c<=count($max_childs); $c++) {
									?><option value="<?php echo $c; ?>"><?php echo $c; ?></option><?php
								}
							?>
						</select>
					</td>
					<td width="80px" align="left" class="right-pull-3">
						<select name="occupancytype<?php echo $govalue['id']; ?>" class="nopads">
							<option value="<?php echo $govalue['occupancy_type']; ?>" selected><?php echo $cur_occupancy_type; ?></option><?php echo $occupancy_type; ?>
						</select>
					</td>
					<td width="100px" align="left" class="left-pull-5">
						<input type="date" name="checkin<?php echo $govalue['id']; ?>" value="<?php echo $govalue['checkin_date']; ?>" style="font-size: 12px !important" class="nopads no-back-black"<?php echo $nodateselect; ?>>
					</td>
					<td width="100px" align="left" class="left-pull-5">
						<input type="date" name="checkout<?php echo $govalue['id']; ?>" value="<?php echo $govalue['checkout_date']; ?>" style="font-size: 12px !important" class="<?php echo $extendcolor; ?>" data-ext="<?php echo $govalue['roomid']; ?>" data-chkout="<?php echo $govalue['checkout_date']; ?>" lang="<?php echo $room_prefix.$room_number; ?> (<?php echo $block_name; ?>)" title="<?php echo $extendtitle; ?>" onclick="<?php echo $extendtrigger; ?>"<?php echo $nodateselect; ?>>
					</td>
					<td width="100px" align="left">
						<span class="ln-display-box float-left right-pull-7 ft-xsml-size"><?php echo $govalue['status']; ?></span><span class="ln-display-box float-left"><?php if(isset($govalue['status']) && ($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'Reserved')) { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/checkin_card_icon.png" class="anchor" title="Check-In Card" onclick=""> <?php if($wgt_booking_type == 'individual' && (isset($allowManualtariff) && $allowManualtariff == 200)) { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/tariff_change_icon.png" class="anchor" title="Tariff Change" onclick="popmodalframe('frontdesk','manualtariffchange','<?php echo $booking_number; ?>',<?php echo $govalue['roomid']; ?>,500,500)"> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/inclusions_icon.png" class="anchor" title="Inclusions" onclick=""><?php } } ?></span>
						<span class="block-element new-line-space"></span>
					</td>
					<td id="b2-<?php echo $govalue['id']; ?>" width="100px" align="left">
						<?php 
							$ths_service_name = "";
							
							if(is_array($rsArry2)) {
								?>
									<a data-td="b2-<?php echo $govalue['id']; ?>" data-id="<?php echo $govalue['id']; ?>" href="javascript:void(0)" class="ft-xsml-size blue-font" title="See bills allow to room" onclick="showBillto(this)">Bill to</a>

									<div id="bill<?php echo $govalue['id']; ?>" class="noshow motion" onclick="chgclass('bill<?php echo $govalue['id']; ?>','noshow motion')" style="margin-right: 300px;">
										<p class="bottom-pull-3"><a href="javascript:void(0)" class="ft-xxsml-size blue-font" title="Add bill" onclick="adB2r(<?php echo $govalue['customerid']; ?>)">+ <u>Add</u></a></p>
										<?php
											
											foreach($rsArry2 as $rs) {
												$ths_service_name = idget_data($tbL14,$rs,'posname');
												?>
													<span class="ln-display-box float-left right-push-10 bottom-push-5">
														<small class="steel-blue-font right-push-3"><?php echo $ths_service_name; ?></small> <a href="?logs=<?php echo $logs; ?>&token=<?php echo $booking_number; ?>&r=<?php echo $rs; ?>&wgtag=removebilltoroom&cid=<?php echo $govalue['customerid']; ?>" class="ft-xxsml-size black-font" title="Remove <?php echo $ths_service_name; ?>">x</a>
													</span>
												<?php
											}
										?>
										<span class="block-element new-line-space">
										</span>
									</div>
								<?php
							} else {
								?>
									<small class="block-element ft-xxsml-size dark-grey-font bottom-push-5">No bills allowed</small>
									
									<a data-td="b2-<?php echo $govalue['id']; ?>" data-id="<?php echo $govalue['id']; ?>" href="javascript:void(0)" class="ft-xsml-size blue-font" title="See bills allow to room" onclick="showBillto(this)">Allow Bill</a>

									<div id="bill<?php echo $govalue['id']; ?>" class="noshow motion" onclick="chgclass('bill<?php echo $govalue['id']; ?>','noshow motion')" style="margin-right: 300px;">
										<p class="bottom-pull-3"><a href="javascript:void(0)" class="ft-xxsml-size blue-font" title="Add bill" onclick="adB2r(<?php echo $govalue['customerid']; ?>)">+ <u>Add</u></a></p>
									</div>
								<?php
							}
						?>
					</td>
					<td width="100px" align="center">
						<?php
							if($isroomcharged == true) { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/bill_icon.png" title="Billed for The Day"><?php } else { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/not_bill_icon.png" title="Not Billed for The Day"><?php }

							if((isset($govalue['status'])) && ($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'CheckedOut') && ($isroomcredited == true)) { ?> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/credit_notification_icon.png" class="anchor" title="Credit Notification" onclick="popmodalframe('frontdesk','creditnotification','<?php echo $booking_number; ?>',<?php echo $govalue['roomid']; ?>,400,510)"><?php }

							if(isset($govalue['status']) && ($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'CheckedOut')) { ?> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/checkin_card_icon.png" class="anchor" title="Bill Status" onclick="popmodalframe('frontdesk','accountstatement','<?php echo $booking_number; ?>','<?php echo $govalue['roomid']; ?>-<?php echo $govalue['customerid']; ?>',1000,2500)"><?php }
						?>
					</td>
					<td width="150px" align="center">
						<?php if($govalue['remarks'] == NULL || $govalue['remarks'] == '') { ?><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','roomremark','<?php echo $booking_number; ?>','<?php echo $govalue['roomid']; ?>',600,400)">Remark</a><?php } else { ?><small class="ft-xxsml-size light-red-font block-element"><?php echo $govalue['remarks']; ?></small> <a href="javascript:void(0)" class="royal-blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','roomremark','<?php echo $booking_number; ?>','<?php echo $govalue['roomid']; ?>',600,400)">Update Remark</a><?php } if($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'Reserved') { ?> | <a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','applydiscount','<?php echo $booking_number; ?>','<?php echo $govalue['roomid']; ?>',500,400)" title="<?php if($govalue['isdiscount'] > 0) { ?>Discount applied<?php } else { ?>Apply<?php } ?>">Discount</a><?php } ?>
					</td>
					<td align="left"></td>
					<td width="400px" align="left">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="135px" align="center"><small class="ft-xxsml-size"><?php echo $guest_fname; ?></small></td>
								<td width="135px" align="center"><small class="ft-xxsml-size"><?php echo $guest_lname; ?></small></td>
								<td width="100px" align="center"><small class="ft-xxsml-size"><?php echo $guest_phone; ?></small></td>
								<td width="30px" align="center">
									<?php if($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'Reserved'): ?>
									<img src="<?php echo DOMAIN_URL; ?>theme/images/general/edit_record_icon.png" class="anchor" title="Edit Record" onclick="popmodalframe('frontdesk','guestdetail','<?php echo $booking_number; ?>','<?php echo $govalue['customerid']; ?>',1000,1500)">
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php

			$gog += 1;
			$getroomlist = "";
		}
		

	?>
		<tbody id="room-listing"></tbody>
</table>

<input type="hidden" name="wgtbkn" id="wgtbkn" value="<?php echo $booking_number; ?>">
<input type="hidden" id="rwcounter" value="0">
<input type="hidden" id="room-record-id" value="0">
<input type="hidden" id="room-label" value="">

<div id="notifybox2" class="noshow fx-position-stick zind-2 motion tpscr top-push-50 top-pull-50" align="right">
	<div class="cs-width-400 light-yellow-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="fo-header-notification" class="large red-font"></h4>
		<small id="fo-message-notification" class="block-element top-push-10"></small>
	</div>
</div>

<?php $iscspg = (!empty($wgt_bill_to) && $wgt_bill_to > 0) ? $wgt_bill_to : $wgt_bill_to_g; ?>

<script>

	function wgthsroom() {
		var numbr = document.getElementById('room-record-id').value;
		var label = document.getElementById('room-label').value;
		htmlpassval(numbr,'wgtcroom');
		writeObjheader('roomlabel',label);
		parent.document.getElementById('workspace').scrollTop = 0;
	}

	function roomChk(obj) {
		var numbr = document.getElementById(obj).value;
		var label = document.getElementById(obj).title;
		htmlpassval(numbr,'room-record-id');
		htmlpassval('Room No: '+label,'room-label');
	}

	function buttonCtrl(str) {
		if(str == 'CheckedIn') {
			document.getElementById('btn1').disabled = false;
			document.getElementById('btn2').disabled = true;
			document.getElementById('btn3').disabled = true;
			document.getElementById('btn4').disabled = true;
			document.getElementById('btn5').disabled = false;
		} else if(str == 'Reserved') {
			document.getElementById('btn1').disabled = false;
			document.getElementById('btn2').disabled = false;
			document.getElementById('btn3').disabled = false;
			document.getElementById('btn4').disabled = false;
			document.getElementById('btn5').disabled = true;
		} else if(str == 'Temp. Reserved') {
			document.getElementById('btn1').disabled = false;
			document.getElementById('btn2').disabled = false;
			document.getElementById('btn3').disabled = false;
			document.getElementById('btn4').disabled = false;
			document.getElementById('btn5').disabled = true;
		} else {
			document.getElementById('btn1').disabled = false;
			document.getElementById('btn2').disabled = true;
			document.getElementById('btn3').disabled = true;
			document.getElementById('btn4').disabled = true;
			document.getElementById('btn5').disabled = true;
		}
	}


	function dodata(str,sses,id,sopt) {
		var select_id = str;
		getdata(select_id,sses,id,sopt);
	}


	function maxAc(adult,child,room) {
		var xhr,file,string,random_numbr,ajaxson;

		string = document.getElementById(room).value;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		file = phpfile+"dbquery.php?data="+string+"&r=formaxadultandchild&dataSend=200";
		random_numbr = Math.random() * 1000000000;
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					ajaxson = JSON.parse(xhr.responseText);

					var a,c,opt_1='',opt_2='';
					
					for(a=1; a <= ajaxson.mxadult; a++) { opt_1 += '<option value="'+a+'">'+a+'</option>'; }
					for(c=0; c <= ajaxson.mxchild; c++) { opt_2 += '<option value="'+c+'">'+c+'</option>'; }

					document.getElementById(adult).innerHTML = opt_1;
					document.getElementById(child).innerHTML = opt_2;
				}
			}
		};

		xhr.open('GET', file+"&rand=" + random_numbr, true);
		xhr.send();
	}


	function addMrm() {

		var r,curnumbr,contr;

		curnumbr = document.getElementById('rwcounter');
		contr = document.getElementById('room-listing');
		
		var uni_id = eval(curnumbr.value) + 1; //generate new id this row
		var tr = document.createElement('tr');
		tr.id = 'tr'+uni_id;

		var td1 = document.createElement('td');
		var td2 = document.createElement('td');
		var td3 = document.createElement('td');
		var td4 = document.createElement('td');
		var td5 = document.createElement('td');
		var td6 = document.createElement('td');
		var td7 = document.createElement('td');
		var td8 = document.createElement('td');
		var td9 = document.createElement('td');
		var td10 = document.createElement('td');
		var td11 = document.createElement('td');
		var td12 = document.createElement('td');
		var td13 = document.createElement('td');
		var td14 = document.createElement('td');
		var td15 = document.createElement('td');
		var td16 = document.createElement('td');

		var txt1 = document.createElement('input');
		var txt2 = document.createElement('input');
		var txt3 = document.createElement('input');
		var txt4 = document.createElement('input');
		var txt5 = document.createElement('input');

		var dropbox1 = document.createElement('select');
		var dropbox2 = document.createElement('select');
		var dropbox3 = document.createElement('select');
		var dropbox4 = document.createElement('select');
		var dropbox5 = document.createElement('select');
		var opt1 = document.createElement('option');
		var opt2 = document.createElement('option');
		var opt3 = document.createElement('option');
		var opt4 = document.createElement('option');
		var opt5 = document.createElement('option');
		var opt6 = document.createElement('option');
		var opt7 = document.createElement('option');
		var opt8 = document.createElement('option');
		var opt9 = document.createElement('option');
		var opt10 = document.createElement('option');
		var opt11 = document.createElement('option');
		var opt12 = document.createElement('option');

		var comment1 = document.createElement('textarea');

		var trashicon = document.createElement('b');
		trashicon.id = 'b'+uni_id;
		trashicon.className = 'fa-trash nobold anchor right-push-5';
		trashicon.title = 'Remove Row';
		trashicon.onclick = function() { 
			contr.removeChild(tr);
		}

		txt1.id = "chk"+uni_id;
		txt1.name = "checkers";
		txt1.type = "radio";
		txt1.value = 0;
		txt1.lang = "newly";
		td1.align = "center";
		td1.appendChild(trashicon);
		td1.appendChild(txt1);
		txt1.onclick = function() {
			document.getElementById('btn1').disabled = false;
			document.getElementById('btn2').disabled = true;
			document.getElementById('btn3').disabled = true;
			document.getElementById('btn4').disabled = true;
			document.getElementById('btn5').disabled = true;
		}

		dropbox1.id = "select-col-1-"+uni_id;
		dropbox1.className = "nopads";
		dropbox1.name = "roomtype0";
		dropbox1.required = "required";
		dropbox1.onchange = function() { getdata('select-col-2-'+uni_id,'eget-rooms','select-col-1-'+uni_id,'dropbox'); maxAc('select-col-3-'+uni_id,'select-col-4-'+uni_id,'select-col-1-'+uni_id); };
		td2.appendChild(dropbox1);

		dropbox2.id = "select-col-2-"+uni_id;
		dropbox2.className = "nopads";
		dropbox2.name = "roomnumber0";
		dropbox2.required = "required";
		opt1.value = "";
		opt1.text = "Choose";
		dropbox2.appendChild(opt1);
		td3.appendChild(dropbox2);
		dropbox2.onchange = function() { check_room_enabled(this.value,'select-col-1-'+uni_id); sessionStorage.setItem('thisrow','select-col-2-'+uni_id); }

		dropbox3.id = "select-col-3-"+uni_id;
		dropbox3.className = "nopads";
		dropbox3.name = "adults0";
		dropbox3.required = "required";
		td4.appendChild(dropbox3);

		dropbox4.id = "select-col-4-"+uni_id;
		dropbox4.className = "nopads";
		dropbox4.name = "childs0";
		dropbox4.required = "required";
		td5.appendChild(dropbox4);

		dropbox5.id = "select-col-5-"+uni_id;
		dropbox5.className = "nopads";
		dropbox5.name = "occupancytype0";
		dropbox5.required = "required";
		td6.appendChild(dropbox5);

		txt2.id = "input-col-1-"+uni_id;
		txt2.className = "nopads";
		txt2.name = 'checkin0';
		txt2.type = 'date';
		txt2.required = "required";
		td7.appendChild(txt2);

		txt3.id = "input-col-2-"+uni_id;
		txt3.className = "nopads";
		txt3.name = 'checkout0';
		txt3.type = 'date';
		txt3.required = "required";
		td8.appendChild(txt3);

		comment1.id = "text-col-1-"+uni_id;
		comment1.name = "remarks0";
		comment1.placeholder = "Remarks (if any?)";
		comment1.className = "cs-height-30 nopads ft-xxsml-size";
		td12.appendChild(comment1);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);
		tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);
		tr.appendChild(td10);
		tr.appendChild(td11);
		tr.appendChild(td12);

		contr.appendChild(tr);
		curnumbr.value = uni_id;

		
		dodata('select-col-1-'+uni_id,'eget-roomtype-list',1,'dropbox');
		dodata('select-col-5-'+uni_id,'eget-occupancy-type',1,'dropbox');
	}
	

	function addnewbkg(booking) {
		
		var strings = {
			"bookingno":"<?php echo $booking_number; ?>",
			"guestname":"<?php echo $replica; ?>",
			"bookingtype":"<?php echo $wgt_booking_type; ?>",
			"billto":"<?php echo $iscspg; ?>",
			"billtype":"<?php echo $wgt_bill_type; ?>"
		}

		sessionStorage.setItem('pbk',JSON.stringify(strings));
		setTimeout(() => {
			parent.document.getElementById('workspace').scrollTop = 0;
			parent.document.getElementById('newbkg').click();
		},1000);

	}


	function singleExt(obj) {
		obj.blur();
		var roomnumber = obj.getAttribute('data-ext');
		var roomname = obj.getAttribute('lang');
		var checkoutdate = obj.getAttribute('data-chkout');

		writeObjheader('ext-title','Extend Guest Stay: '+roomname);
		writeObjheader('ext-note','Note that this extension will affect only '+roomname);
		htmlpassval(roomnumber,'wgtagroom');
		htmlpassval(checkoutdate,'exstartdate');

		document.getElementById('extButton').click();
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function adB2r(customer) {
		htmlpassval(customer,'wgtagcustomer');
		document.getElementById('b2r').click();
		parent.document.getElementById('workspace').scrollTop = 0;
	}


	function kpr(id) {
		sessionStorage.setItem('thisrow',id);
	}


	function showBillto(obj) {

		var id = obj.getAttribute('data-td');
		var elem = obj.getAttribute('data-id');

		var setTop = document.getElementById(id).offsetTop;
		setTop = setTop + 600;
		//console.log(setTop);

		chgclass('bill'+elem,'fx-position-rel light-yellow-theme  cs-width-250 pads7 xsml-rounded-button right-push-30 motion');
		//document.getElementById('bill'+elem).style.top = setTop;
	}

</script>