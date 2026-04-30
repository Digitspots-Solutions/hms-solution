<h4 class="large">Pending Orders <b class="float-right nobold fa-arrow-down"></b></h4><br>
<?php
	//get all pending transaction and list theme here
	$pts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Pending");
	$get_pos_pt_data = mysqli_data_fetch($tbL100,'id,order_number,bill_amount,tableid',$pts_selection_key,'array');

	if(is_array($get_pos_pt_data)) {
		
		$table_name = "";
		
		foreach ($get_pos_pt_data as $pts_key => $pts_value) {
			
			$table_name = idget_data($tbL17,$pts_value['tableid'],'tablename');

			?>
				<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" onclick="window.location.href='pos/preview_pos_order.php?new_order=<?php echo $pts_value['order_number']; ?>'">
					<span class="ln-display-box float-left right-push-7">
						<h4 class="large"><?php echo $pts_value['order_number']; ?></h4>
						<small class="block-element">&#8358; <?php echo number_format($pts_value['bill_amount'],2); ?></small>
					</span>
					<span class="ln-display-box float-right right-push-7">
						<small class="block-element bottom-push-3"><b class="fa-share nobold"></b> <?php echo $table_name; ?></small>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
			<?php
		}
	}
?>

<br>

<h4 class="large">Pending Payments <b class="float-right nobold fa-arrow-down"></b></h4><br>

<?php
	//get all pending transaction and list theme here
	$pyts_selection_key = array("posid"=>$cur_pos_store_id,"status"=>"Completed","payment"=>"Pending");
	$get_pos_pyt_data = mysqli_data_fetch($tbL100,'id,order_number,invoice_number,bill_amount,tableid,customerid,billtype,roomid',$pyts_selection_key,'array');

	if(is_array($get_pos_pyt_data)) {
		
		$table_name = ""; $get_bill_name = ""; $billto = "";
		
		foreach ($get_pos_pyt_data as $pyts_key => $pyts_value) {
			
			$billto = idget_data($tbL102,$pyts_value['customerid'],'billto');

			if($pyts_value['billtype'] == 1) { $get_bill_name = idget_data($tbL102,$pyts_value['customerid'],'name'); }
			elseif($pyts_value['billtype'] == 2) { $r_prefix = idget_data($tbL56,$pyts_value['roomid'],'roomprefix'); $r_number = idget_data($tbL56,$pyts_value['roomid'],'roomnumber'); $r_suffix = idget_data($tbL56,$pyts_value['roomid'],'roomsuffix'); $get_bill_name = $r_prefix.$r_number.$r_suffix; }
			elseif($pyts_value['billtype'] == 3) { $get_bill_name = idget_data($tbL33,$billto,'name'); }
			elseif($pyts_value['billtype'] == 4) { $get_bill_name = idget_data($tbL58,$billto,'name'); }
			elseif($pyts_value['billtype'] == 5) { $get_bill_name = idget_data($tbL7,$billto,'staffname'); }

			?>
				<div class="block-element bottom-push-5 red-white-state white-font anchor top-pull-7 right-pull-7 bottom-pull-7 left-pull-7" onclick="window.location.href='pos/preview_pos_order_pay.php?new_order=<?php echo $pyts_value['order_number']; ?>&invoice=<?php echo $pyts_value['invoice_number']; ?>'">
					<span class="ln-display-box float-left right-push-7">
						<h4 class="large"><?php echo $pyts_value['invoice_number']; ?></h4>
						<small class="block-element">&#8358; <?php echo number_format($pyts_value['bill_amount'],2); ?></small>
					</span>
					<span class="ln-display-box float-right right-push-7">
						<small class="block-element add-bold">Guest</small>
						<small><?php echo $get_bill_name; ?></small>
					</span>
					<span class="block-element new-line-space">
					</span>
				</div>
			<?php
		}
	}
?>