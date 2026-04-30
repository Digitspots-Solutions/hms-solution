<?php
	$smdl = "frontdesk"; $logs = escape_data($_GET['logs']);
	
	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";


	$pageurl = 'workspace.php?logs='.$logs;


	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND (booking_number REGEXP '^{$_POST['search']}' OR activities LIKE '%{$_POST['search']}%')";
	} else { 
		$keywords="";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 50;
		$additionalQuery = $keywords." ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
	}


	$sql = "SELECT * FROM {$tbL132} WHERE app_tag IN('Booking') AND deletedata=0".$additionalQuery;
	$getbkg = wgetSQL($sql);

?>
<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you see all booking histories
 	</span>
 	<span class="ln-display-box float-right">
		&nbsp;
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<form action="" method="post" autocomplete="off">
	<span class="float-right">
		<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button noshow">
	</span>
	<span class="float-right right-push-30">
		<input type="text" name="search" id="search" placeholder="Search by keywords.." onkeyup="chgclass('sbtn','submit pads10 black-white-state sml-rounded-button')">
	</span>
	<span class="block-element new-line-space">
	</span>
</form>
		
<?php

	if(is_array($getbkg)) {
		foreach($getbkg as $key => $val) {
				
			$loguser = idget_data($tbL7,$val['userid'],'staffname');

			?>
				<div class="box-border-thick sml-rounded-button pads20 bottom-push-7">
					<a href="javascript:void(0)" class="blue-font default-text-font-bold" onclick="jsxView('<?php echo $val['booking_number']; ?>')"><?php echo $val['booking_number']; ?></a> &nbsp; <?php echo $val['activities']; ?>
					<p class="top-pull-10 ft-xsml-size dark-grey-font">
						By <?php echo $loguser; ?> on <?php echo date('d/m/y',strtotime($val['datelogged'])).' '.$val['timelogged']; ?>
					</p>
				</div>
			<?php

			$loguser = "";
		}
	}


	echo '<p>&nbsp;</p>';
	
	#paginate this page

	$additionalQuery = "";
	$ukey = array("deletedata"=>0);
	mysqli_data_check($tbL132,'(*)',$ukey);
	$totalcount = $numOfrows;

	$paginate = data_pagenation(50,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>


<script>

	function jsxView(key) {
		var uId = Math.round(Math.random() * 10000) + 1;
		crframe(key,uId,'reservations');
	}

</script>