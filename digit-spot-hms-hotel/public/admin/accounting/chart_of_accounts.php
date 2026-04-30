<?php $smdl = "accounting"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create all chart of accounts by clicking <u>new account</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Account
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

	//'Income','Inventory',

	$account_type = array('Cash','Accounts Receivable','Accounts Payable','Expenses','Fixed Assets','Accumulated Depreciation','Cost of Sales','Current Liabilities','Long Term Liabilities','Equity-Gets Closed','Equity-Retained Earnings','Current Assets');

	$list_payment_modes = select_dt_fetch('iscounter','Yes',$tbL24,'id','name');

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_coa_tbl_1); //create a table for this post
		createDatabasetable($var_coa_tbl_2); //create a table for this post

		$fieldset1 = escape_data($_POST['accttype']);
		$fieldset2 = escape_data($_POST['acctname']);

		$fieldset3 = escape_data(str_replace(',','',$_POST['ppr']));
		$fieldset4 = escape_data($_POST['plf']);
		$fieldset5 = escape_data($_POST['paymentmode']);

		
		$insert_dataproperty = array("account_type"=>$fieldset1,"account_name"=>ucwords($fieldset2),"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		if($fieldset1 === 'Fixed Assets' && !empty($fieldset3) && !empty($fieldset4)) {
			
			$insert_dataproperty['ppr'] = $fieldset3;
			$insert_dataproperty['plf'] = $fieldset4;

			$peryyr = $fieldset3 / $fieldset4;
			$permm = $peryyr / 12;

			$insert_dataproperty['mdpr'] = $permm;

			$date = date_create($server_get_date);
			date_modify($date, '+'.$fieldset4.' Years');

			$insert_dataproperty['plf_date'] = (!empty($fieldset4)) ? date_format($date,'Y-m-d') : '0000-00-00';
			//$insert_dataproperty['plf_date'] = (!empty($fieldset4)) ? $dateObj->modify('+'.$fieldset4.' Years')->format('Y-m-d') : '0000-00-00';
			//$insert_dataproperty['plf_date'] = date('Y-m-d',strtotime('+'.$fieldset4.' Years'));
		}

		if($fieldset1 === 'Cash' && !empty($fieldset5)) {

			$insert_dataproperty['pychannel'] = $fieldset5;
		}

		$insert_constrain = array("account_name"=>ucwords($fieldset2));
		$data_inserted = mysqli_data_insert('coa_setup_tbl',$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently created new chart of account","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
							<h3 class="nomargin">Creating New Account Head</h3>
						</div>
						<span class="block-element bottom-push-10">
							<select name="accttype" id="accttype" onchange="coaAddon(this)" required="required">
								<option value="" selected="selected">Account Type</option>
								<?php foreach($account_type as $var): ?>
									<option value="<?php echo $var; ?>"><?php echo $var; ?></option>
								<?php endforeach; ?>
							</select>
						</span>
						<span class="block-element bottom-push-10">
							<input type="text" name="acctname" id="acctname" placeholder="Enter Account Name e.g Motor Vehicle" required="required">
						</span>

						<div id="fa" class="noshow motion bottom-push-10">
							<span class="block-element bottom-push-10">
								<input type="text" name="ppr" id="ppr" placeholder="Enter Cost of Purchase e.g 500000" oninput="numberinputFormat(this.value,this.id,'no-ppr')">
							</span>
							<span class="block-element bottom-push-10">
								<ul class="nolist">
									<li class="ln-display-box float-left nc-width-70"><input type="text" name="plf" id="plf" placeholder="Enter Product Life Span e.g 5" pattern="\d*"></li>
									<li class="ln-display-box float-left nc-width-30"><select name="yyr" id="yyr"><option value="Years">Years</option></select></li>
									<li class="block-element new-line-space"></li>
								</ul>
							</span>
						</div>

						<div id="py" class="noshow motion bottom-push-10">
							<select name="paymentmode" id="paymentmode">
								<option value="" selected="selected">Payment Channel</option>
								<?php echo $list_payment_modes; ?>
							</select>
						</div>
						
						<br><br>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
	}

	#-----------------------------------------------------------------------------------------------------------------

	if((isset($_POST['statusbutton']) && isset($_POST['checkers'])) && (isset($_POST['cstatus']) && !empty($_POST['cstatus'])))
	{
		$data_updated=0;

		$fieldset = escape_data($_POST['cstatus']);
		$usr_datasets = array("status"=>$fieldset);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$cstat = mysqli_data_update('coa_setup_tbl',$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently changed chart of account status","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Status was changed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	$pageurl = 'workspace.php?logs='.$logs;

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['editbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);
		$fieldset2a = (isset($_POST['fieldset2a']) && !empty($_POST['fieldset2a'])) ? escape_data($_POST['fieldset2a']) : 0;
		$fieldset2b = (isset($_POST['fieldset2b']) && !empty($_POST['fieldset2b'])) ? escape_data($_POST['fieldset2b']) : 0;

		
		$date = date_create($server_get_date);
		date_modify($date, '+'.$fieldset2b.' Years');
		
		//$dateObj = new DateTime($server_get_date);
		//$noww = $dateObj->modify('+'.$fieldset2b.' Years');
		//$fieldset2c = (!empty($fieldset2b)) ? $noww->format('Y-m-d') : '0000-00-00';

		$fieldset2a = str_replace(',','',$fieldset2a);
		$fieldset2c = (!empty($fieldset2b)) ? date_format($date,'Y-m-d') : '0000-00-00';
		

		$fieldset3 = escape_data($_POST['fieldset3']);
		
		$insert_dataproperty = array("account_name"=>ucwords($fieldset2),"ppr"=>$fieldset2a,"plf"=>$fieldset2b,"plf_date"=>$fieldset2c);
		$insert_constrain = array("id"=>$fieldset3);
		$data_inserted = mysqli_data_update('coa_setup_tbl',$insert_dataproperty,$insert_constrain);

		$update_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently edited chart of account","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$update_result .= '<span class="red-font">Changes were added successfully</span>';
		}

		$update_result .= '</div>';
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['deletebutton']) && isset($_POST['checkers']))
	{
		$data_deleted=0;

		$usr_datasets = array("deletedata"=>1);
		$usr_key = "";

		foreach ($_POST['checkers'] as $fkey) {
			
			$usr_key = array("id"=>$fkey);
			$del = mysqli_data_update('coa_setup_tbl',$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently deleted chart of account","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (isset($_POST['search']) && !empty($_POST['search']))) {
		$keywords=" AND account_name LIKE '".escape_data($_POST['search'])."%' OR account_type LIKE '".escape_data($_POST['search'])."'";
	} else { 
		$keywords=" ORDER BY account_type ASC";
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

	$dataproperty = "id,account_type,account_name,ppr,plf,plf_date,mdpr,pychannel,status";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch('coa_setup_tbl',$dataproperty,$constrain,$row);

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","type","name","status","noth","enoth");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
		$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
		$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
		//$htmlresult .= '<input type="submit" name="deletebutton" value=" Delete " class="submit pads10 black-white-state rounded-button nc-width-15"> &nbsp; ';
		$htmlresult .= '<input type="submit" name="statusbutton" value=" Change Status " class="submit pads10 black-white-state rounded-button nc-width-15">';
		$htmlresult .= '&nbsp; &rsaquo; <select name="cstatus" id="cstatus" style="width: 120px"><option value="">Choose</option><option value="Active">Enable</option><option value="InActive">Disable</option></select>';
		$htmlresult .= '<span class="ln-display-box float-right nc-width-40">';
		$htmlresult .= '<div class="ln-display-box float-left nc-width-70">';
		$htmlresult .= '<input type="text" name="search" id="search" placeholder="Search by account name.." onkeyup="chgclass('.$fxobj.','.$fxclass.')">';
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
		
		$num=$pgstart; $g=""; $dataid=""; $inline_category=""; $inline_product=""; $inline_table=""; $department=""; $store_name=""; $dateObj=""; $fddate="";

		foreach($dataCollect as $theader => $tdata)
		{
			$num += 1;
			$g = $num / 2;

			$dataid = $tdata["id"];

			$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';
					
			$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">'.$num.'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["account_type"].'</td>';
			$htmlresult .= '<td width="200px" align="center" class="box-border-thick-right">'.$tdata["account_name"].'</td>';
			$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right leaf-green-font">'.$tdata["status"].'</td>';
			$htmlresult .= '<td width="70px" align="center" class="box-border-thick-right"><a href="?logs='.$logs.'&edit='.$dataid.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'&#tr" class="blue-font">Edit/View</a></td>';
			$htmlresult .= '<td width="30px" align="center"><input type="checkbox" name="checkers[]" value="'.$dataid.'"></td>';
			$htmlresult .= '</tr>';

			if((isset($_GET['edit']) && $_GET['edit'] >= 1) && ($_GET['edit'] == $dataid))
			{
				$fieldset = escape_data($_GET['edit']);

				$htmlresult .= '<tr>';
				$htmlresult .= '<td colspan="30">';
				$htmlresult .= '<div id="tr" class="block-element grey-1-theme pads30">';
				$htmlresult .= $update_result;
				$htmlresult .= '<h4 class="large blue-font">Updating Account Head</h4><br>';
				$htmlresult .= '<div class="nc-width-40">';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Type</small>';
				$htmlresult .= '<select name="fieldset1" id="fieldset1" required="required"><option value="'.$tdata["account_type"].'" selected="selected">'.$tdata["account_type"].'</option></select>';
				$htmlresult .= '</span>';
				$htmlresult .= '<span class="block-element bottom-push-10">';
				$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Name</small>';
				$htmlresult .= '<input type="text" name="fieldset2" id="fieldset2" placeholder="Enter account name" value="'.$tdata["account_name"].'">';
				$htmlresult .= '</span>';

				if($tdata["account_type"] == 'Cash') {

					$htmlresult .= '<span class="block-element bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Payment Channel</small>';
					if(!empty($tdata['pychannel'])) {
						$pychannel_name = idget_data($tbL24,$tdata['pychannel'],'name');
						$htmlresult .= '<select name="pychannel" id="pychannel" required>';
						$htmlresult .= '<option value="'.$tdata['pychannel'].'" selected>'.$pychannel_name.'</option>';
						$htmlresult .= $list_payment_modes;
						$htmlresult .= '</select>';
					} else {
						$htmlresult .= '<select name="pychannel" id="pychannel" required>';
						$htmlresult .= '<option value="'.$tdata['pychannel'].'" selected>N/A</option>';
						$htmlresult .= $list_payment_modes;
						$htmlresult .= '</select>';
					}

					$htmlresult .= '</span>';
				}

				if($tdata["account_type"] == 'Fixed Assets') {

					$dateObj = new DateTime($tdata['plf_date']);
					$fddate = $dateObj->format('d-m-Y');

					$htmlresult .= '<span class="block-element bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Cost of Purchase</small>';
					$htmlresult .= '<input type="text" name="fieldset2a" id="fieldset2a" placeholder="Enter cost of purchase" value="'.number_format($tdata["ppr"]).'">';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="block-element bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font left-pull-5">Life Span (Year)</small>';
					$htmlresult .= '<input type="number" min="1" step="1" name="fieldset2b" id="fieldset2b" placeholder="Enter product life-time" value="'.$tdata["plf"].'">';
					$htmlresult .= '</span>';

					$monthly_depr = $tdata["mdpr"];
					$yearly_depr = $monthly_depr * 12;

					$htmlresult .= '<p>&nbsp;</p>';

					$htmlresult .= '<span class="ln-display-box float-left nc-width-50 right-pull-10 bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font">Asset Price</small>';
					$htmlresult .= '<h3 class="large nobold default-text-font-bold">&#8358;'.number_format($tdata["ppr"],2).'</h3>';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="ln-display-box float-left nc-width-50 right-pull-10 bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font">Asset Life Span</small>';
					$htmlresult .= '<h3 class="large nobold default-text-font-bold">'.$fddate.' ('.$tdata["plf"].' Years)</h3>';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="block-element new-line-space">';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="ln-display-box float-left nc-width-50 right-pull-10 bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font">Yearly Depreciation</small>';
					$htmlresult .= '<h3 class="large nobold default-text-font-bold">&#8358;'.number_format($yearly_depr,2).'</h3>';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="ln-display-box float-left nc-width-50 right-pull-10 bottom-push-10">';
					$htmlresult .= '<small class="block-element bottom-push-3 dark-grey-font">Monthly Depreciation</small>';
					$htmlresult .= '<h3 class="large nobold default-text-font-bold">&#8358;'.number_format($monthly_depr,2).'</h3>';
					$htmlresult .= '</span>';

					$htmlresult .= '<span class="block-element new-line-space">';
					$htmlresult .= '</span>';
				}

				$htmlresult .= '</div>';
				$htmlresult .= '<div class="block-element top-pull-20 bottom-push-10 alignrt">';
				$htmlresult .= '<input type="hidden" name="fieldset3" id="fieldset3" value="'.$fieldset.'">';
				$htmlresult .= '<input type="submit" name="editbutton" value="Save Changes" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs='.$logs.'&pg='.$curpage.'&start='.$pgstart.'&limit='.$pglimit.'" class="steel-blue-font">Cancel</a>';
				$htmlresult .= '</div>';
				$htmlresult .= '</div>';
				$htmlresult .= '</td>';
				$htmlresult .= '</tr>';
			}
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
	mysqli_data_check('coa_setup_tbl','(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(30,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<script>

	function coaAddon(obj) {

		if(obj.value == 'Fixed Assets') { document.getElementById('fa').classList.remove('noshow'); }
		else { document.getElementById('fa').classList.add('noshow'); }

		if(obj.value == 'Cash') { document.getElementById('py').classList.remove('noshow'); }
		else { document.getElementById('py').classList.add('noshow'); }
	}

	function allowdisc(opt) {
		if(opt == 'Yes') {
			document.getElementById('guest').removeAttribute('readonly');
			document.getElementById('staff').removeAttribute('readonly');
		} else if(opt == 'No') {
			document.getElementById('guest').value = "";
			document.getElementById('staff').value = "";
			document.getElementById('guest').setAttribute('readonly','readonly');
			document.getElementById('staff').setAttribute('readonly','readonly');
		}
	}

</script>