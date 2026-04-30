<?php

	##send notification for guest credit-limit
	
	$additionalQuery = " WHERE (notifylimit > creditlimit) OR (notifylimit = creditlimit)";
	mysqli_data_check($tbL58,'(*)','');
	$cspg_count = $numOfrows;

	if(isset($cspg_count) && $cspg_count >= 1) {
		$get_cspg_data = mysqli_data_fetch($tbL58,'id,name,creditlimit,notifylimit,mobile,email','','array');

		if(is_array($get_cspg_data)) {
			$inbox_message = '';
			$inbox_message .= '<h4 class="large nobold">The following corporate guest accounts need attention. The credit limit has reached notification limit and is running low</h4><br>';
			foreach ($get_cspg_data as $cspgkey => $cspgvalue) {
				$inbox_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
				$inbox_message .= '<h3 class="large">'.$cspgvalue['name'].'</h3>';
				$inbox_message .= '<small class="block-element">'.$cspgvalue['mobile'].', '.$cspgvalue['email'].'</small>';
				$inbox_message .= '<div class="block-element box-border-thick-top top-push-15 top-pull-15">';
				$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
				$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Credit Balance</small>';
				$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['creditlimit'],2).'</small>';
				$inbox_message .= '</span>';
				$inbox_message .= '<span class="ln-display-box float-left">';
				$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Notify Limit</small>';
				$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['notifylimit'],2).'</small>';
				$inbox_message .= '</span>';
				$inbox_message .= '<span class="block-element new-line-space">';
				$inbox_message .= '</span>';
				$inbox_message .= '</div>';
				$inbox_message .= '</div>';
			}
		}

		//get receiver by module and role
		$additionalQuery = ""; $dp_selection_key = array("mdl"=>2);
		$get_dp_data = mysqli_data_fetch($tbL4,'id',$dp_selection_key,'array');
		if(is_array($get_dp_data)) { $roles = ''; foreach ($get_dp_data as $dp_key => $dp_value) { $roles .= $dp_value['id'].','; } $f_roles = substr_replace($roles,'',-1,1); }

		$additionalQuery = " WHERE role IN(".$f_roles.")";
		$get_receiver_data = mysqli_data_fetch($tbL7,'id','','array');

		//check for routine data and log notification to respective users
		$additionalQuery = ""; $routine_selection_key = array("title"=>1);
		$get_routine_data = mysqli_data_fetch($tbL103,'id,module,nextrun,status',$routine_selection_key,'noarray');

		$nextrun = date("Y-m-d",strtotime('8 days'));
		$lastrun = $server_get_date;

		if(isset($get_routine_data[0]) && $get_routine_data[0] >= 1) {
			if($get_routine_data[2] == $server_get_date) {

				if(is_array($get_receiver_data)) {
				foreach ($get_receiver_data as $rckey => $rcvalue) {
					$receiver_dataproperty = array("subject"=>"Corporate guest account credit limit alert","sender"=>2,"receiver"=>$rcvalue['id'],"message"=>$inbox_message,"priority"=>2,"msgtype"=>14,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL104,$receiver_dataproperty,'');
					}
				}

				$routine_dataproperty = array("lastrun"=>$lastrun,"nextrun"=>$nextrun,"status"=>0);
				$routine_query = array("title"=>1);
				mysqli_data_update($tbL103,$routine_dataproperty,$routine_query);

			}
		} else {

			createDatabasetable($var_tbl_98); //create a table for this post
			createDatabasetable($var_tbl_99); //create a table for this post

			if(is_array($get_receiver_data)) {
				foreach ($get_receiver_data as $rckey => $rcvalue) {
					$receiver_dataproperty = array("subject"=>"Corporate guest account credit limit alert","sender"=>2,"receiver"=>$rcvalue['id'],"message"=>$inbox_message,"priority"=>2,"msgtype"=>14,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL104,$receiver_dataproperty,'');
				}
			}

			$routine_dataproperty = array("module"=>2,"title"=>1,"lastrun"=>$lastrun,"nextrun"=>$nextrun,"status"=>0);
			mysqli_data_insert($tbL103,$routine_dataproperty,$routine_selection_key);
		}

	}

	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	##send notification for special/corpr guest recurring payment

	$get_day = date("d",strtotime($server_get_date));
	$get_month = date("m",strtotime($server_get_date));
	
	$inbox_message = '';
	$inbox_message .= '<h4 class="large nobold">The following corporate guest accounts recurring payment is due. Please ignore if payment have been made</h4><br>';

	$get_cspg_data = mysqli_data_fetch($tbL58,'id,name,creditlimit,mobile,email,payterm','','array');

	if(is_array($get_cspg_data)) {
		
		$payment = 0; $dbt = 0; $payterm = "";
		
		foreach ($get_cspg_data as $cspgkey => $cspgvalue) {
			
			$pay_sql = "SUM(amount)";
			$pay_query = "WHERE cspgid=".$cspgvalue['id'];
			$payment = mysqli_arithmetic_data($tbL63,$pay_sql,$pay_query);

			$payterm = idget_data($tbL41,$cspgvalue['payterm'],'name');

			if(isset($payterm) && ($payterm == 'Monthly' || $payterm == 'monthly' || $payterm == 'month'))
			{
				if($get_day == '01' || $get_day == '02' || $get_day == '03')
				{
					if(($payment < $cspgvalue['creditlimit']) || ($payment == $cspgvalue['creditlimit'])) {
						
						$dbt += 1;

						$inbox_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
						$inbox_message .= '<h3 class="large">'.$cspgvalue['name'].'</h3>';
						$inbox_message .= '<small class="block-element">'.$cspgvalue['mobile'].', '.$cspgvalue['email'].'</small>';
						$inbox_message .= '<div class="block-element box-border-thick-top top-push-15 top-pull-15">';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Recurring Payment Plan</small>';
						$inbox_message .= '<small class="block-element">'.$payterm.'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Credit Balance</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['creditlimit'],2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Payment (so far)</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($payment,2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="block-element new-line-space">';
						$inbox_message .= '</span>';
						$inbox_message .= '</div>';
						$inbox_message .= '</div>';
					}
				}
			}
			elseif(isset($payterm) && ($payterm == 'Half Yearly' || $payterm == 'half yearly'))
			{
				if(($get_day == '29' || $get_day == '30' || $get_day == '31') && ($get_month == '06' || $get_month == '12'))
				{
					if(($payment < $cspgvalue['creditlimit']) || ($payment == $cspgvalue['creditlimit'])) {
						
						$dbt += 1;

						$inbox_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
						$inbox_message .= '<h3 class="large">'.$cspgvalue['name'].'</h3>';
						$inbox_message .= '<small class="block-element">'.$cspgvalue['mobile'].', '.$cspgvalue['email'].'</small>';
						$inbox_message .= '<div class="block-element box-border-thick-top top-push-15 top-pull-15">';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Recurring Payment Plan</small>';
						$inbox_message .= '<small class="block-element">'.$payterm.'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Credit Balance</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['creditlimit'],2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Payment (so far)</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($payment,2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="block-element new-line-space">';
						$inbox_message .= '</span>';
						$inbox_message .= '</div>';
						$inbox_message .= '</div>';
					}
				}
			}
			elseif(isset($payterm) && ($payterm == 'Weekly' || $payterm == 'weekly'))
			{
				if(($get_day == '08' || $get_day == '16' || $get_day == '27'))
				{
					if(($payment < $cspgvalue['creditlimit']) || ($payment == $cspgvalue['creditlimit'])) {
						
						$dbt += 1;

						$inbox_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
						$inbox_message .= '<h3 class="large">'.$cspgvalue['name'].'</h3>';
						$inbox_message .= '<small class="block-element">'.$cspgvalue['mobile'].', '.$cspgvalue['email'].'</small>';
						$inbox_message .= '<div class="block-element box-border-thick-top top-push-15 top-pull-15">';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Recurring Payment Plan</small>';
						$inbox_message .= '<small class="block-element">'.$payterm.'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Credit Balance</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['creditlimit'],2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Payment (so far)</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($payment,2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="block-element new-line-space">';
						$inbox_message .= '</span>';
						$inbox_message .= '</div>';
						$inbox_message .= '</div>';
					}
				}
			}
			elseif(isset($payterm) && ($payterm == 'Quarterly' || $payterm == 'quarterly'))
			{
				if(($get_day == '29' || $get_day == '30' || $get_day == '31') && ($get_month == '03' || $get_month == '06' || $get_month == '09' || $get_month == '12'))
				{
					if(($payment < $cspgvalue['creditlimit']) || ($payment == $cspgvalue['creditlimit'])) {
						
						$dbt += 1;

						$inbox_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
						$inbox_message .= '<h3 class="large">'.$cspgvalue['name'].'</h3>';
						$inbox_message .= '<small class="block-element">'.$cspgvalue['mobile'].', '.$cspgvalue['email'].'</small>';
						$inbox_message .= '<div class="block-element box-border-thick-top top-push-15 top-pull-15">';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Recurring Payment Plan</small>';
						$inbox_message .= '<small class="block-element">'.$payterm.'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left right-push-30">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Credit Balance</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($cspgvalue['creditlimit'],2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="ln-display-box float-left">';
						$inbox_message .= '<small class="block-element dark-grey-font bottom-push-3">Payment (so far)</small>';
						$inbox_message .= '<small class="block-element">&#8358;'.number_format($payment,2).'</small>';
						$inbox_message .= '</span>';
						$inbox_message .= '<span class="block-element new-line-space">';
						$inbox_message .= '</span>';
						$inbox_message .= '</div>';
						$inbox_message .= '</div>';
					}
				}
			}
		}


		if(isset($dbt) && $dbt >= 1) {

			$additionalQuery = ""; $routine_selection_key = array("title"=>2,"lastrun"=>$server_get_date);
			$get_routine_data = mysqli_data_fetch($tbL103,'id',$routine_selection_key,'noarray');

			if(!isset($get_routine_data[0])) {

				createDatabasetable($var_tbl_98); //create a table for this post
				createDatabasetable($var_tbl_99); //create a table for this post

				$routine_dataproperty = array("module"=>2,"title"=>2,"lastrun"=>$server_get_date,"nextrun"=>$server_get_date,"status"=>0);
				mysqli_data_insert($tbL103,$routine_dataproperty,$routine_selection_key);

				//get receiver by module and role
				$additionalQuery = ""; $dp_selection_key = array("mdl"=>2); $get_dp_data = mysqli_data_fetch($tbL4,'id',$dp_selection_key,'array');
				if(is_array($get_dp_data)) { $roles = ''; foreach ($get_dp_data as $dp_key => $dp_value) { $roles .= $dp_value['id'].','; } $f_roles = substr_replace($roles,'',-1,1); }

				$additionalQuery = " WHERE role IN(".$f_roles.")";
				$get_receiver_data = mysqli_data_fetch($tbL7,'id','','array');

				if(is_array($get_receiver_data)) {
					foreach ($get_receiver_data as $rckey => $rcvalue) {
						$receiver_dataproperty = array("subject"=>"Corporate guest account recurring payment","sender"=>2,"receiver"=>$rcvalue['id'],"message"=>$inbox_message,"priority"=>1,"msgtype"=>4,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
						mysqli_data_insert($tbL104,$receiver_dataproperty,'');
					}
				}

			}
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	//change recreation status plan

	$stat_recreation_selection_key = array("deletedata"=>0,"status"=>1);
	$get_stat_recreation_data = mysqli_data_fetch($tbL105,'id,recreation_number,photo,salutation,firstname,lastname,startdate,enddate,plan,workflow',$stat_recreation_selection_key,'array');

	if(is_array($get_stat_recreation_data)) {

		$rcr_message = '';

		$current_day_time = str_replace('-', '', $server_get_date);
		$recreation_due_time = ""; $expired = 0; $member_photo = "";

		foreach ($get_stat_recreation_data as $rstat_key => $rstat_value) {
			$recreation_due_time = str_replace('-', '', $rstat_value['enddate']);
			if($current_day_time >= $recreation_due_time) {
				$expired += 1;
				$update_status = array("status"=>0);
				$update_status_key = array("id"=>$rstat_value['id']);
				mysqli_data_update($tbL105,$update_status,$update_status_key);

				if(isset($rstat_value['photo']) && !empty($rstat_value['photo'])) {
					$member_photo = DOMAIN_URL."theme/images/general/recreation-members/".$rstat_value['photo'];
				} else {
					$member_photo = DOMAIN_URL."theme/images/general/photo.png";
				}

				$get_salutation = idget_data($tbL42,$rstat_value['salutation'],'name');
				$get_plan = arrayget_key($recreation_duration,$rstat_value['plan']);

				$rcr_message .= '<div class="block-element box-border-thick sml-rounded-button pads20 bottom-push-20">';
				$rcr_message .= '<span class="ln-display-box float-left right-push-30">';
				$rcr_message .= '<div class="block-element cs-width-250 cs-height-250 grey-theme box-border-thick bottom-push-10 noscroll alignct">';
				$rcr_message .= '<img src="'.$member_photo.'" class="auto-wh">';
				$rcr_message .= '</div>';
				$rcr_message .= '</span>';
				$rcr_message .= '<span class="ln-display-box float-left top-pull-10">';
				$rcr_message .= '<h3 class="large">#'.$rstat_value['recreation_number'].'</h3>';
				$rcr_message .= '<small class="block-element bottom-push-20">'.$get_salutation.' '.$rstat_value['firstname'].' '.$rstat_value['lastname'].'</small>';
				$rcr_message .= '<small class="block-element dark-grey-font bottom-push-3">Membership Plan</small>';
				$rcr_message .= '<small class="block-element bottom-push-15">'.$get_plan.'</small>';
				$rcr_message .= '<small class="block-element dark-grey-font bottom-push-3">Period</small>';
				$rcr_message .= '<small class="block-element bottom-push-15">'.date("d/m/Y",strtotime($rstat_value['startdate'])).' &mdash; '.date("d/m/Y",strtotime($rstat_value['enddate'])).'</small>';
				$rcr_message .= '</span>';
				$rcr_message .= '<span class="block-element new-line-space">';
				$rcr_message .= '</span>';
				$rcr_message .= '</div>';
			}
		}

		if(isset($expired) && $expired >= 1) {
			
			//get receiver by module and role
			$additionalQuery = ""; $dp_selection_key = array("approve"=>10); $get_dp_data = mysqli_data_fetch($tbL108,'role',$dp_selection_key,'array');
			if(is_array($get_dp_data)) { $roles = ''; foreach ($get_dp_data as $dp_key => $dp_value) { $roles .= $dp_value['role'].','; } $f_roles = substr_replace($roles,'',-1,1); }

			$rcr_inbox_message = '';
			$rcr_inbox_message .= '<h4 class="large nobold">The following recreation account is expired and require renewal. Please take necessary action</h4><br>';
			$rcr_inbox_message .= $rcr_message;

			$additionalQuery = " WHERE role IN(".$f_roles.")";
			$get_receiver_data = mysqli_data_fetch($tbL7,'id','','array');

			if(is_array($get_receiver_data)) {
				foreach ($get_receiver_data as $rckey => $rcvalue) {
					$receiver_dataproperty = array("subject"=>"Recreation: (Membership account renewal)","sender"=>2,"receiver"=>$rcvalue['id'],"message"=>$rcr_inbox_message,"priority"=>3,"msgtype"=>4,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
					mysqli_data_insert($tbL104,$receiver_dataproperty,'');
				}
			}
		}
	}

	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	#for hotel season re-initialization

	$additionalQuery = "";
	$wkt_query = array("deletedata"=>0);
	$get_wkt_data = mysqli_data_fetch($tbL79,'id,modeid,startseason,endseason',$wkt_query,'array');

	if(is_array($get_wkt_data)) {
		$startseason=""; $endseason=""; $this_curdate = str_replace('-', '', $server_get_date);
		foreach ($get_wkt_data as $wkt_key => $wkt_value) {
			
			$startseason = str_replace('-', '', $wkt_value['startseason']);
			$endseason = str_replace('-', '', $wkt_value['endseason']);

			if($this_curdate >= $startseason && $this_curdate <= $endseason) {
				$wkt_sets = array("status"=>"Active");
			} else {
				$wkt_sets = array("status"=>"InActive");
			}

			$wkt_set_query_1 = array("id"=>$wkt_value['id']);
			mysqli_data_update($tbL79,$wkt_sets,$wkt_set_query_1);

			$wkt_set_query_2 = array("modeid"=>$wkt_value['modeid']);
			mysqli_data_update($tbL80,$wkt_sets,$wkt_set_query_2);
		}
	}

	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	#for temp. reserved room date validation

	/*$additionalQuery = "";
	$tmpr_query = array("tempreserved"=>"Yes","deletedata"=>0);
	$get_tmpr_data = mysqli_data_fetch($tbL96,'id,booking_number,roomid,tempdatereserved',$tmpr_query,'array');

	if(is_array($get_tmpr_data)) {
		$this_curdate = str_replace('-', '', $server_get_date); $tempdate = "";
		foreach ($get_tmpr_data as $tmpr_key => $tmpr_value) {
			
			$tempdate = str_replace('-', '', $tmpr_value['tempdatereserved']);
			
			if($this_curdate > $tempdate) {
				$tmpr_sets_1 = array("status"=>"Cancelled","cancel_policy"=>999,"cancel_reason"=>7);
				$tmpr_sets_2 = array("tempreserved"=>"No","tempdatereserved"=>"0000-00-00");
				
				$tmpr_set_query_1 = array("booking_number"=>$tmpr_value['booking_number'],"roomid"=>$tmpr_value['roomid']);
				mysqli_data_update($tbL127,$tmpr_sets_1,$tmpr_set_query_1);

				$tmpr_set_query_2 = array("id"=>$tmpr_value['id']);
				mysqli_data_update($tbL96,$tmpr_sets_2,$tmpr_set_query_2);
			}
		}
	}*/

?>