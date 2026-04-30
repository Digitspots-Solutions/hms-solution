<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; ?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>

<div class="pads20">
	<?php
		
		include "../../includes/common_data_vars.php";
		include "../../includes/pos_common_data.php";

		if(isset($_GET['checkby']) && $_GET['checkby'] == 'room') {
			
			$guest = escape_data($_GET['customer']);
			$room = escape_data($_GET['room']);

			$guest_booking_no = idget_data($tbL102,$guest,'booking_number');
			$guest_type = idget_data($tbL102,$guest,'booking_type');
			$allowBill = idget_data($tbL102,$guest,'isbill_to_room');
			$billing_services = idget_data($tbL102,$guest,'billing_services');

			$bkr_query = array("booking_number"=>$guest_booking_no,"roomid"=>$room,"customerid"=>$guest);
			$remark = mysqli_data_fetch($tbL127,'remarks',$bkr_query,'noarray');

			$guest_bal = guestBillSummary($guest_booking_no);
			if($guest_bal <= 0) { $guest_bal = str_replace('-','',$guest_bal); }
			else { $guest_bal = 0; }

			echo '<h3 class="large nobold">Guest: <b class="light-red-font nobold default-text-font-bold nomargin">'.ucwords($guest_type).'</b></h3>';

			if(!empty($remark[0])) { echo '<h3 class="large nobold light-red-font nomargin bottom-pull-3">'.$remark[0].'</h3>'; }

			echo '<h3 class="large nobold default-text-font-bold">&#8358; '.number_format($guest_bal,2).' Bal.</h3>';
			echo '<h3 class="large nobold">Allow Bill: <u>'.$allowBill.'</u></h3>';

			if($allowBill === 'Yes') {

				$bArry = explode(',',$billing_services);

				echo '<ul>';

				foreach($bArry as $rs) {
					if(is_numeric($rs)) {
						$ths_service_name = idget_data($tbL14,$rs,'posname');
						$issub = idget_data($tbL14,$rs,'isfoodtype');
						if($issub == 'Yes') { 
							$addx="";
							for($x=1; $x <= 3; $x++) {
								$rsx = $rs.'-'.$x;
								if(in_array($rsx,$bArry)) { $addx .= $food_type[$x].','; }
							}
							$addx = substr_replace($addx,'',-1,1);
							echo '<li>'.$ths_service_name.' ('.$addx.')</li>';
						} else {
							echo '<li>'.$ths_service_name.'</li>';
						}

						$ths_service_name=""; $issub="";
					}
				}

				echo '</ul>';
			}

		} elseif(isset($_GET['checkby']) && $_GET['checkby'] == 'group') {

			$guest = escape_data($_GET['customer']);
			$room = escape_data($_GET['room']);

			$guest_bal = idget_data($tbL58,$guest,'creditlimit');

			echo '<h3 class="large nobold">Guest: <b class="light-red-font nobold default-text-font-bold">Corporate</b></h3><h3 class="large nobold default-text-font-bold">&#8358; '.number_format($guest_bal,2).' Bal.</h3>';
			echo '<h3 class="large nobold">Package:</h3>';

			$dtkey = array("cspgid"=>$guest);
			$bArry = mysqli_data_fetch($tbL61,'posid',$dtkey,'array');

			if(is_array($bArry) && count($bArry) > 0) {
				$line = 0;
				echo '<ul>';

				foreach($bArry as $ky => $rs) {
					if(is_numeric($rs['posid'])) {
						$line += 1;
						$ths_service_name = idget_data($tbL14,$rs['posid'],'posname');
						$issub = idget_data($tbL14,$rs['posid'],'isfoodtype');
						if($issub == 'Yes') { 
							$addx="";
							foreach($bArry as $k => $v) {
								for($x=1; $x <= 3; $x++) {
									$rsx = $rs['posid'].'-'.$x;
									if($rsx == $v['posid']) { $addx .= $food_type[$x].','; }
									$rsx = "";
								}
							}
							$addx = substr_replace($addx,'',-1,1);
							echo '<li>'.$ths_service_name.' ('.$addx.')</li>';
						} else {
							echo '<li>'.$ths_service_name.'</li>';
						}
					}

					$ths_service_name=""; $issub="";
				}

				echo '</ul>';
			}

		} elseif(isset($_GET['checkby']) && $_GET['checkby'] == 'complimentary') {

			$guest = escape_data($_GET['customer']);
			$room = escape_data($_GET['room']);

			//$dtkey = array("booking_type"=>"complimentary","bill_to"=>$guest,"reservation"=>"Checking In");
			//$bArry = mysqli_data_fetch($tbL130,'isbill_to_room,billing_services',$dtkey,'noarray');

			echo '<h3 class="large nobold">Guest: <b class="light-red-font nobold default-text-font-bold">Complimentary</b></h3>';
			echo '<h3 class="large nobold">Allow Bill: <u>Yes</u></h3>';
			//echo '<h3 class="large nobold">Allow Bill: <u>'.$bArry[0].'</u></h3>';

			/*if($bArry[0] === 'Yes') {

				$bArry = explode(',',$bArry[1]);

				echo '<ul>';

				foreach($bArry as $rs) {
					if(is_numeric($rs)) {
						$ths_service_name = idget_data($tbL14,$rs,'posname');
						$issub = idget_data($tbL14,$rs,'isfoodtype');
						if($issub == 'Yes') { 
							$addx="";
							for($x=1; $x <= 3; $x++) {
								$rsx = $rs.'-'.$x;
								if(in_array($rsx,$bArry)) { $addx .= $food_type[$x].','; }
							}
							$addx = substr_replace($addx,'',-1,1);
							echo '<li>'.$ths_service_name.' ('.$addx.')</li>';
						} else {
							echo '<li>'.$ths_service_name.'</li>';
						}

						$ths_service_name=""; $issub="";
					}
				}

				echo '</ul>';
			}*/
		}
	?>
</div>