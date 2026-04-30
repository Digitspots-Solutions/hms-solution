//discount

<?php if($govalue['isdiscount'] == 0 && $disabled_this_room == '') { ?><a href="javascript:void(0)" class="blue-font ft-xxsml-size" onclick="popmodalframe('frontdesk','applydiscount','<?php echo $booking_number; ?>','<?php echo $govalue['roomid']; ?>',500,400)">Discount</a><?php } ?>


<?php if(isset($govalue['status']) && ($govalue['status'] == 'CheckedIn' || $govalue['status'] == 'CheckedOut') && ($disabled_this_room == '') && ($wgt_balance < 0)) { ?> <img src="<?php echo DOMAIN_URL; ?>theme/images/general/credit_notification_icon.png" class="anchor" onclick=""><?php } ?>

/*if(isset($allow_bill_to_room) && $allow_bill_to_room == 'Yes') {
									?><li class="bottom-push-3"><a href="" class="blue-font ft-xsml-size">Room Services</a></li><?php
								}*/


								 /*elseif(isset($govalue['status']) && $govalue['status'] == 'Reserved') { ?><img src="<?php echo DOMAIN_URL; ?>theme/images/general/tariff_change_icon.png" class="anchor" onclick=""><?php }*/


if((isset($isrebate) && $isrebate == 1) && $isrebateChk == false) {
									?>
										<span class="float-left left-push-50 ft-xsml-size">Use balance as 
											<a href="?logs=modals&prefix=frontdesk&param=<?php echo $param; ?>&ftoken=<?php echo $booking_number; ?>&stoken=<?php echo $ths_token; ?>&amt=<?php echo str_replace('-','',$total_base_balance); ?>&rebate=yes" class="light-red-font default-text-font-bold">rebate</a>
										</span>
									<?php
								} elseif((isset($isrebate) && $isrebate == 1) && $isrebateChk == true) {
									$color_code = "dark-grey-font";
									?>
										<span class="float-left left-push-50 ft-xsml-size default-text-font-bold dark-blue-font">
											* Paid as rebate
										</span>
									<?php
								}


