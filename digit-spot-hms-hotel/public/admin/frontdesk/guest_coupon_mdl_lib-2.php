<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create guest coupon by clicking <u>new coupon</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Coupon
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';

	$payment_mode = select_dt_fetch('deletedata',0,$tbL24,'id','name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_124); //create a table for this post

		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset3 = escape_data($_POST['fieldset3']);
		$fieldset4 = escape_data($_POST['fieldset4']);

		$new_coupon_code = substr(sha1(mt_rand(100,999999999999)),1,8);

		$insert_dataproperty = array("coupon_code"=>$new_coupon_code,"guest_name"=>ucwords(strtolower($fieldset1)),"guest_contact"=>$fieldset2,"coupon_amount"=>$fieldset3,"payment_mode"=>$fieldset4,"coupon_type"=>1,"expires_on"=>$coupon_expiry_default_date,"customerid"=>0,"userid"=>$userSignedIn,"coupon_status"=>"Unused","datelogged"=>$server_get_date,"timelogged"=>$server_get_time);
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert($tbL129,$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			/*$new_coupon_id = $mysqli_id;
			$new_coupon_code = "GCP".$new_coupon_id;

			$coupon_query_2 = array("id"=>$new_coupon_id);
			$coupon_data_2 = array("coupon_code"=>$new_coupon_code);
			$is_insert_2 = mysqli_data_update($tbL129,$coupon_data_2,$coupon_query_2);*/

			//create a log file
			$log_message = "Create coupon for guest: ".ucwords(strtolower($fieldset1));
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>$log_message,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_GET['c']) && $_GET['c'] == 'new')
	{

		?>
			<div class="block-element box-border-thick-bottom bottom-pull-30 bottom-push-30" align="center">
				<div class="nc-width-40">
					<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
						<div class="bottom-push-20 alignlt">
							<h3 class="nomargin">Creating New Coupon</h3>
						</div>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset1" id="fieldset1" placeholder="Enter guest name" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter contact number" pattern="\d*" maxlength="11" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<input type="number" step=".01" name="fieldset3" id="fieldset3" placeholder="Enter coupon amount" required="required">
						</span>
						<span class="block-element bottom-push-10">
							<select name="fieldset4" id="fieldset4" required="required">
								<?php echo $payment_mode; ?>
							</select>
						</span>
						
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && isset($_POST['cstatus']))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update($tbL129,$usr_datasets,$usr_key);

			if(isset($cstat) && $cstat == 2) {
				$data_updated += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_updated) && $data_updated == 0)
		{
			$post_result .= '<span class="red-font">Unable to change status. Try again</span>';
		}
		elseif(isset($data_updated) && $data_updated >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Change coupon status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update($tbL129,$usr_datasets,$usr_key);

			if(isset($del) && $del == 2) {
				$data_deleted += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_deleted) && $data_deleted == 0)
		{
			$post_result .= '<span class="red-font">Unable to remove data. Try again</span>';
		}
		elseif(isset($data_deleted) && $data_deleted >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove guest coupon","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (guest_name LIKE '%".escape_data($_POST['search'])."%' OR coupon_code LIKE '".escape_data($_POST['search'])."%')";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 30;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	$dataproperty = "id,guest_name,guest_contact,coupon_code,coupon_type,coupon_amount,payment_mode,expires_on,coupon_status,status,refunds,customerid,userid,usedby,datelogged,timelogged";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch($tbL129,$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","coupon code","guest name","guest contact","coupon type","coupon amount","expires on","coupon status","created by","created on","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="1">Enable</option><option value="0">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by keywords.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-30 alignrt">';
		$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element new-line-space"></div>';
		$htmlresult .= '</span>';
		$htmlresult .= '<span class="block-element new-line-space"></span>';
		$htmlresult .= '</div>';
		$htmlresult .= '<div class="block-element sml-rounded-button noscroll">';
		$htmlresult .= '<table cellpadding="0" cellspacing="0">';
		$htmlresult .= '<tr>';
		
		$thu=0; $uclass="";
		
		foreach($thproperty as $th)
		{
			$thu += 1;
			
			if($tcount == $thu) { $uclass=''; }
			else { $uclass='class="box-border-thick-right"'; }
			
			if($th == 'noth') { $htmlresult .= '<th width="70px" '.$uclass.' align="center">&nbsp;</th>'; }
			elseif($th == 'enoth') { $htmlresult .= '<th width="30px" '.$uclass.' align="center">&nbsp;</th>'; }
			else { $htmlresult .= '<th width="150px" '.$uclass.' align="center">'.ucwords($th).'</th>'; }
		}
		
		$htmlresult .= '</tr>';
		
		$num=$pgstart; $g=""; $dataid=""; $customerid=""; $get_coupon_type=""; $print_amount=""; $print_created_date=""; $print_expire_date="";
		$issuer_name=""; $get_status=""; $operation_status="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];
			$customerid = $tdata["customerid"];

			if(isset($customerid) && $customerid >= 1) {
				$guest_name = idget_data($tbL102,$customerid,'name');
				$guest_contact = idget_data($tbL102,$customerid,'mobile');
			} else {
				$guest_name = $tdata["guest_name"];
				$guest_contact = $tdata["guest_contact"];
			}

			$get_coupon_type = arrayget_key($coupon_type,$tdata["coupon_type"]);
			$print_amount = write_amountF($gh_get_decimal_format,$tdata["coupon_amount"]);

			$print_created_date = write_dateF($gh_get_date_format,$tdata["datelogged"]);
			$print_expire_date = write_dateF($gh_get_date_format,$tdata["expires_on"]);

			$issuer_name = idget_data($tbL7,$tdata["userid"],'staffname');

			$get_status = arrayget_key($status_tag,$tdata["status"]);

			if($tdata["coupon_status"] == "Used") {
				$operation_status = '<span class="block-element red-font">Used</span>';
			} elseif($tdata["coupon_status"] == "Unused") {
				$operation_status = '<a href="javascript:void(0)" class="block-element blue-font add-bold">[Refund]</a>';
			}


			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata["coupon_code"].' <a href="javascript:void(0)" class="black-font" onclick="printCoupon('.$dataid.')"><b class="fa-print nobold"></b></a></td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$guest_name.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$guest_contact.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$get_coupon_type.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">&#8358;'.$print_amount.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$print_expire_date.'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$get_status.$operation_status.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$issuer_name.'</td>';
			$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$print_created_date.'</td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
		$htmlresult .= '</form>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	mysqli_data_check($tbL129,'(*)',$constrain);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<div id="popup-win" class="noshow motion noscroll" align="left">
	<div class="cs-height-100"></div>
	<div id="clx" class="noshow alignrt right-pull-50">
		<a href="javascript:void(0)" class="ft-sml-size black-font" onclick="closeWin()">Close x</a>
	</div>
	<div id="frame-box" class="noshow right-push-50 bottom-push-20 left-push-50 nc-height-80 noscroll">
		<iframe frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" width="100%" height="100%" name="coupon" id="coupon"></iframe>
	</div>
</div>

<script>
	
	function printCoupon(id) {
		openWin();
		window.coupon.location.href = "coupon.php?r="+id;
	}

	function openWin() {
		chgclass('popup-win','fx-position-stick zind-2 motion fscr txp8-white y-scroll');
		objDisplay('frame-box'); objDisplay('clx');
	}

	function closeWin() {
		chgclass('popup-win','noshow motion noscroll');
		objHidden('frame-box'); objHidden('clx');
	}

</script>