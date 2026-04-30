<?php
	
	$booking_number = $ftoken;
	$ths_token = $stoken;

?>
<form action="" method="post" autocomplete="off">
	<div class="block-element">
		<fieldset>
			<legend><h2 class="large nobold default-text-font-bold nomargin">Guest History</h2></legend>
			<div class="block-element cs-height-10"></div>
			
			<?php
				
				$sql_data = "COUNT(id)";
				$sql_query = "booking_number='{$booking_number}' AND deletedata=0";
				$totalcount = mysqli_arithmetic_data($tbL132,$sql_data,$sql_query);

				//pagination controller
				$additionalQuery = ""; $curpage = ""; $pgstart = ""; $pglimit = "";
				if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
					$curpage = $_GET['pg'];
					$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
					$additionalQuery = " ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
				} else {
					$curpage = 0;
					$pgstart = 0; $pglimit = 15;
					$additionalQuery = " ORDER BY id DESC LIMIT ".$pgstart.",".$pglimit;
				}

				
				$bh_query = array("booking_number"=>$booking_number,"deletedata"=>0);
				$get_bh_data = mysqli_data_fetch($tbL132,'activities,userid,datelogged,timelogged',$bh_query,'array');
				
				if(is_array($get_bh_data)) {
					
					$counter = 0; $frontdesk_user = "";

					foreach ($get_bh_data as $bhkey => $bhvalue) {
						
						$counter += 1;

						$frontdesk_user = idget_data($tbL7,$bhvalue['userid'],'staffname');

						?>
							<div class="block-element box-border-thick-bottom bottom-pull-10 bottom-push-10">
								<span class="ln-display-box float-left cs-width-40 cs-height-40 dark-black-theme white-font top-pull-7 alignct noscroll">
									<?php echo $counter; ?>.
								</span>
								<span class="ln-display-box float-left nc-width-70 left-pull-30">
									<h3 class="large nobold nomargin"><?php echo $bhvalue['activities']; ?> <small><em>- by <?php echo $frontdesk_user; ?></em></small></h3>
								</span>
								<span class="ln-display-box float-right nc-width-20">
									<h4 class="large nobold dark-grey-font"><?php echo date("D, F jS Y",strtotime($bhvalue['datelogged'])); ?>. <?php echo $bhvalue['timelogged']; ?></h4>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
						<?php
					}
				}

			?>

		</fieldset>

		<div class="block-element cs-height-20"></div>

		<?php
			
			//pagination buttons
			$paginate = data_pagenation(15,0,$totalcount);
			if(isset($paginate) && !empty($paginate)) { echo $paginate; }
			$pageurl = 'workspacex.php?logs=modals/prefix='.$pfx.'/param='.$param.'/ftoken='.$ftoken.'/stoken='.$stoken;

		?>

		<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

	</div>
</form>