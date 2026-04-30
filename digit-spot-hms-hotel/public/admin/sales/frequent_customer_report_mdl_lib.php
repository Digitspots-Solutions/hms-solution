<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can get the list of corporate and individual guest reports on their frequent booking
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<div class="block-element bottom-push-30 box-border-thick-bottom bottom-pull-5">
	<form action="" method="post">
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date From</small>
			<input type="date" name="fieldset1" id="fieldset1" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Date To</small>
			<input type="date" name="fieldset2" id="fieldset2" value="<?php echo $server_get_date; ?>">
		</span>
		<span class="ln-display-box float-left nc-width-15 right-pull-5">
			<small class="block-element bottom-push-3 left-pull-3">Guest</small>
			<select name="fieldset3" id="fieldset3">
				<option value="" selected="selected">Choose</option>
				<option value="individual">Individual</option>
				<option value="corporate">Corporate</option>
			</select>
		</span>
		<span class="ln-display-box float-right nc-width-10 right-pull-5 alignct">
			<small class="block-element bottom-push-3 left-pull-3">&nbsp;</small>
			<input type="submit" name="submitbutton" id="submitbutton" value="Go &rsaquo;" class="submit blue-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 sml-rounded-button">
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>
	</form>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton'])) {
		
		$date_from = write_dateF($gh_get_date_format,$_POST['fieldset1']);
		$date_to = write_dateF($gh_get_date_format,$_POST['fieldset2']);

		$bookingtype = isset($_POST['fieldset3']) ? $_POST['fieldset3'] : "individual";

		?>
			<p class="alignrt bottom-pull-20">
				<a href="javascript:void(0)" class="black-white-state top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 rounded-button ft-sml-size" onclick="window.print()"><b class="fa-print nobold"></b> Print</a>
			</p>

			<div id="section-to-print" class="block-element">
				<h1 class="large alignct"><?php echo _LONG_NAME; ?></h1>
				<small class="block-element alignct">Frequent Customers From <?php echo $date_from.' To '.$date_to; ?></small>
				<small class="block-element top-push-3 alignct">Printed by: <b><?php echo $admin_name; ?></b></small>

				<?php
					
					#start report selection

					if($bookingtype === 'individual') {

						$additionalQuery = " AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'";
						$gs_query = array("booking_type"=>$bookingtype,"primary_guest"=>1,"deletedata"=>0);
						$guest_dataproperty = "id,booking_number,booking_type,virtual_guest_code,guest_code,salutation,fname,lname,mobile,emailaddress,city,state,country";
						$get_guest_data = mysqli_data_fetch($tbL102,$guest_dataproperty,$gs_query,'array');

						?>
							<div class="block-element top-push-20">
								<div class="block-element top-push-5 box-border-thick">
									<table cellpadding="0" cellspacing="0" class="ft-xsml-size">
										<tr>
											<th width="100px" align="center">Guest Code</th>
											<th width="250px" align="center">Name</th>
											<th width="100px" align="center">No of Visit</th>
											<th width="150px" align="center">Mobile</th>
											<th width="150px" align="center">Email</th>
											<th width="150px" align="center">City</th>
											<th width="150px" align="center">State</th>
											<th width="150px" align="center">Country</th>
										</tr>

										<?php

											if(is_array($get_guest_data)) {
												
												$salutation = ""; $status = ""; $country_name = ""; $state_name = "";
												$queryset = ""; $noofvisit = ""; $salutation_name = ""; $guest_ky = "";
								
												foreach ($get_guest_data as $key => $val) {
													
													$salutation_name = idget_data($tbL42,$val['salutation'],'name');
													$country_name = idget_data($tbL64,$val['country'],'name');
													$state_name = idget_data($tbL65,$val['state'],'name');

													$dataset = "COUNT(booking_number)";
													//$queryset = "virtual_guest_code={$val['virtual_guest_code']} AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'";
													$queryset = "booking_number='{$val['booking_number']}' AND status NOT IN('No Show','Cancelled','Reserved')";
													$noofvisit = mysqli_arithmetic_data($tbL127,$dataset,$queryset);

													$guest_ky = $salutation_name.' '.$val['fname'].' '.$val['lname'].'<br>Code: P'.$val['id'].'<br>Mobile: '.$val['mobile'].'<br><br>No of Bookings: '.$noofvisit;

													?>
														<tr>
															<td width="100px" align="left">&nbsp; <?php echo $val['guest_code'].$val['id']; ?></td>
															<td width="200px" align="center"><?php echo $salutation_name.' '.$val['fname'].' '.$val['lname']; ?></td>
															<td width="100px" align="center"><a href="javascrpt:void(0)" class="blue-font" onclick="popmodalframe('frontdesk','guestbookings','<?php echo $val['booking_number']; ?>','<?php echo $guest_ky; ?>',1000,1500)"><?php echo $noofvisit; ?></a></td>
															<td width="100px" align="center"><?php echo $val['mobile']; ?></td>
															<td width="150px" align="center"><?php echo $val['emailaddress']; ?></td>
															<td width="150px" align="center"><?php echo $val['city']; ?></td>
															<td width="150px" align="center"><?php echo $state_name; ?></td>
															<td width="120px" align="center"><?php echo $country_name; ?></td>
														</tr>
													<?php
												}
											}

										?>

									</table>
								</div>
							</div>
						<?php

					} elseif($bookingtype === 'corporate') {

						$additionalQuery = " AND reservation IN('Checking In','Checking Out') AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."' GROUP BY bill_to";
						$gs_query = array("booking_type"=>$bookingtype,"deletedata"=>0);
						$guest_dataproperty = "bill_to";
						$get_guest_data = mysqli_data_fetch($tbL130,$guest_dataproperty,$gs_query,'array');

						?>
							<div class="block-element top-push-20">
								<div class="block-element top-push-5 box-border-thick">
									<table cellpadding="0" cellspacing="0" class="ft-xsml-size">
										<tr>
											<th width="150px" align="center">Code</th>
											<th width="350px" align="center">Name</th>
											<th width="100px" align="center">No of Visit</th>
											<th width="150px" align="center">Mobile</th>
											<th width="150px" align="center">Email</th>
											<th width="150px" align="center">City</th>
											<th width="150px" align="center">State</th>
											<th width="150px" align="center">Country</th>
										</tr>

										<?php

											if(is_array($get_guest_data)) {
												
												$salutation = ""; $status = ""; $country_name = ""; $state_name = "";
												$queryset = ""; $noofvisit = ""; $cspg = ""; $cspg_name = ""; $cspg_code = "";
												$cspg_mobile = ""; $cspg_email = ""; $cspg_city = ""; $cspg_state = "";
												$cspg_country = ""; $bkgsx = "";
								
												foreach($get_guest_data as $key => $val) {
													
													//$queryset = "bill_to={$val['bill_to']}";
													//$dataset = "COUNT(id)";
													$additionalQuery = " AND datelogged BETWEEN '".$_POST['fieldset1']."' AND '".$_POST['fieldset2']."'"; $gs_query2 = array("bill_to"=>$val['bill_to'],"deletedata"=>0);
													$get_bkgs = mysqli_data_fetch($tbL130,'booking_number',$gs_query2,'array');
													$bkgs = ""; $var_bkgs = ""; foreach($get_bkgs as $ky => $vl) { $bkgs .= "'".$vl['booking_number']."',"; $var_bkgs .= $vl['booking_number'].'-'; }

													$bkgsx = substr_replace($bkgs,'',-1,1);
													//$_SESSION['bkgsx'] = $bkgsx;

													$dataset = "COUNT(booking_number)";
													$queryset = "booking_number IN({$bkgsx}) AND status NOT IN('Cancelled','Reserved')";
													$noofvisit = mysqli_arithmetic_data($tbL127,$dataset,$queryset);

													$cspg = $val['bill_to'];
													
													$cspg_name = idget_data($tbL58,$cspg,'name');
													$cspg_code = idget_data($tbL58,$cspg,'code');
													$cspg_mobile = idget_data($tbL58,$cspg,'mobile');
													$cspg_email = idget_data($tbL58,$cspg,'email');
													$cspg_city = idget_fdata($tbL59,'cspgid',$cspg,'city');
													$cspg_state = idget_fdata($tbL59,'cspgid',$cspg,'state');
													$cspg_country = idget_fdata($tbL59,'cspgid',$cspg,'country');

													$country_name = idget_data($tbL64,$cspg_country,'name');
													$state_name = idget_data($tbL65,$cspg_state,'name');

													$guest_ky = $cspg_name.'<br>Code: '.$cspg_code.'<br>Mobile: '.$cspg_mobile.'<br><br>No of Bookings: '.$noofvisit;

													?>
														<tr>
															<td width="100px" align="center">&nbsp; <?php echo $cspg_code; ?></td>
															<td width="200px" align="center"><?php echo $cspg_name; ?></td>
															<td width="100px" align="center"><a href="javascrpt:void(0)" class="blue-font" onclick="popmodalframe('frontdesk','cspgbookings','<?php echo $var_bkgs; ?>','<?php echo $guest_ky; ?>',1000,1500)"><?php echo $noofvisit; ?></a></td>
															<td width="100px" align="center"><?php echo $cspg_mobile; ?></td>
															<td width="150px" align="center"><?php echo $cspg_email; ?></td>
															<td width="150px" align="center"><?php echo $cspg_city; ?></td>
															<td width="150px" align="center"><?php echo $state_name; ?></td>
															<td width="120px" align="center"><?php echo $country_name; ?></td>
														</tr>
													<?php
												}
											}

										?>

									</table>
								</div>
							</div>
						<?php
					}
					
				?>
			</div>
		<?php
	}

?>