<?php $smdl = "accounting"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create all journal entry by clicking <u>new entry</u> button
 	</span>
 	<span class="ln-display-box float-right">
		<a href="?logs=<?php echo $logs; ?>&c=new" class="submit pads12 sml-rounded-button blue-theme white-font">
		New Entry
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

	$acctTypes = select_dt_fetch('status','Active','coa_setup_tbl','account_type','account_type');

	$extable="coa_setup_tbl"; $extcols="account_name"; $extkey="id";
	$acctHeadVals = select_dt_fetch('deletedata',0,'coa_entry_tbl',0,'coa_id');

	$acctHeads = ""; $accttype=""; $accthead=""; $entrytype=""; $entrytype2=""; $acctid=""; $amount=""; $detail=""; $a2title="";
	$amt=""; $dtl="";

	if(isset($_GET['ah']) && !empty($_GET['ah'])) {
		$acctHeads = '<option value=""></option>';
		$acctHeads .= select_dt_fetch('account_type',$_GET['ah'],'coa_setup_tbl','id','account_name');
		$accttype = $_GET['ah'];
	}

	if(isset($_GET['ah2']) && !empty($_GET['ah2'])) {
		$acctHeads2 = '<option value=""></option>';
		$acctHeads2 .= select_dt_fetch('account_type',$_GET['ah2'],'coa_setup_tbl','id','account_name');
		$accttype2 = $_GET['ah2'];
		$entrytype = $_GET['ety'];
		$entrytype2 = ($entrytype == 'Debit') ? 'Credit' : 'Debit';
		$a2title = ($entrytype == 'Debit') ? '+ Include Credit Account' : '+ Include Debit Account';
		$amt = $_GET['amt'];
		$dtl = $_GET['dtl'];
	}

	if(isset($_GET['ext']) && !empty($_GET['ext']) && $_GET['ah'] == 'Fixed Assets') {
		$acctid = $_GET['ext'];
		$amount = idget_fdata('coa_setup_tbl','id',$_GET['ext'],'mdpr');
		$accthead = idget_fdata('coa_setup_tbl','id',$_GET['ext'],'account_name');
		$detail = "Recurrence depreciation value";
	} else {
		$acctid = $_GET['ext'];
		$accthead = idget_fdata('coa_setup_tbl','id',$_GET['ext'],'account_name');
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_coa_tbl_1); //create a table for this post
		createDatabasetable($var_coa_tbl_2); //create a table for this post

		$coa_id = escape_data($_POST['acctname']);

		$amount = escape_data(str_replace(',','',$_POST['jrAmt']));
		$entry_type = escape_data($_POST['entrytype']);
		$detail = escape_data($_POST['detail']);

		$coa_id2 = escape_data($_POST['acctname2']);

		$amount2 = escape_data(str_replace(',','',$_POST['jrAmt2']));
		$entry_type2 = escape_data($_POST['entrytype2']);
		$detail2 = escape_data($_POST['detail2']);

		$mmonth = date('F',strtotime($server_get_date));
		$yyear = date('Y',strtotime($server_get_date));

		
		$insert_dataproperty = array("coa_id"=>$coa_id,"amount"=>$amount,"entry_type"=>$entry_type,"detail"=>$detail,"mmonth"=>$mmonth,"yyear"=>$yyear,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		$insert_dataproperty2 = array("coa_id"=>$coa_id2,"amount"=>$amount2,"entry_type"=>$entry_type2,"detail"=>$detail2,"mmonth"=>$mmonth,"yyear"=>$yyear,"userid"=>$userSignedIn,"datelogged"=>$server_get_date,"timelogged"=>$server_get_time);

		
		$insert_constrain = "";
		$data_inserted = mysqli_data_insert('coa_entry_tbl',$insert_dataproperty,$insert_constrain);

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($data_inserted) && $data_inserted == 2)
		{
			mysqli_data_insert('coa_entry_tbl',$insert_dataproperty2,$insert_constrain);

			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently created account head new journal","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

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
							<h3 class="default-text-font-bold nobold nomargin">Creating New Journal</h3>
						</div>
						<div class="box-border-thick mini-rounded-button pads20 bottom-push-10">
							<span class="float-left nc-width-50 right-pull-10">
								<select name="accttype" id="accttype" onchange="getaccthead(this.value)" required="required">
									<?php if(isset($accttype) && !empty($accttype)): ?>
										<option value="<?php echo $accttype; ?>"><?php echo $accttype; ?></option>
									<?php else: ?>
										<option value="" selected="selected">Account Type</option>
									<?php endif; ?>
									<?php echo $acctTypes; ?>
								</select>
							</span>
							<span class="float-left nc-width-50 left-pull-10">
								<select name="acctname" id="acctname" onchange="getacctheadEx(this.value)" required="required">
									<?php
										
										if(isset($accthead) && !empty($accthead)) {
											?><option value="<?php echo $acctid; ?>"><?php echo $accthead; ?></option><?php
										} else {
											if(isset($acctHeads) && !empty($acctHeads)) {
												echo $acctHeads;
											} else {
												?><option value="">Account Head</option><?php
											}
										}
									?>
								</select>
							</span>
							<span class="block-element new-line-space">
							</span>
						</div>

						<span class="block-element bottom-push-10">
							<textarea name="detail" id="detail" placeholder="Briefly describe purpose or beneficiary"><?php if(isset($detail) && !empty($detail)) { echo $detail; } else { echo $dtl; } ?></textarea>
						</span>

						<span class="block-element bottom-push-10">
							<input type="text" name="jrAmt" id="jrAmt" placeholder="Enter amount e.g 50000" oninput="numberinputFormat(this.value,this.id,'no-amt')" value="<?php if(isset($amount) && !empty($amount)) { echo number_format($amount,2); } else { if(!empty($amt)) { echo number_format($amt,2); } } ?>"<?php if(isset($amount) && !empty($amount)): ?> readonly="readonly"<?php endif; ?>>
						</span>

						<span class="block-element bottom-push-10">
							<select name="entrytype" id="entrytype" onchange="entryType(this)" required="required">
								<?php if(!empty($entrytype)): ?>
									<option value="<?php echo $entrytype; ?>" selected="selected"><?php echo $entrytype; ?></option>
								<?php else: ?>
									<option value="" selected="selected">Debit or Credit</option>
								<?php endif; ?>
								<option value="Debit">Debit</option>
								<option value="Credit">Credit</option>
							</select>
						</span>
						
						<br><br>

						<div id="dr-cr" class="<?php if(empty($entrytype)): ?>noshow<?php endif; ?> bottom-push-30 motion">

							<h3 id="drcr-label" class="default-text-font-bold alignlt nomargin"><?php if(!empty($a2title)) { echo $a2title; } ?></h3>

							<p>&nbsp;</p>

							<div class="box-border-thick mini-rounded-button pads20 bottom-push-10">
								<span class="float-left nc-width-50 right-pull-10">
									<select name="accttype2" id="accttype2" onchange="getaccthead2(this.value)" required="required">
										<?php if(isset($accttype2) && !empty($accttype2)): ?>
											<option value="<?php echo $accttype2; ?>"><?php echo $accttype2; ?></option>
										<?php else: ?>
											<option value="" selected="selected">Account Type</option>
										<?php endif; ?>
										<?php echo $acctTypes; ?>
									</select>
								</span>
								<span class="float-left nc-width-50 left-pull-10">
									<select name="acctname2" id="acctname2" required="required">
										<?php
											
											if(isset($accthead2) && !empty($accthead2)) {
												?><option value="<?php echo $acctid2; ?>"><?php echo $accthead2; ?></option><?php
											} else {
												if(isset($acctHeads2) && !empty($acctHeads2)) {
													echo $acctHeads2;
												} else {
													?><option value="">Account Head</option><?php
												}
											}
										?>
									</select>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>

							<span class="block-element bottom-push-10">
								<textarea name="detail2" id="detail2" placeholder="Briefly describe purpose or beneficiary"></textarea>
							</span>

							<span class="block-element bottom-push-10">
								<input type="text" name="jrAmt2" id="jrAmt2" placeholder="Enter amount e.g 50000" oninput="numberinputFormat(this.value,this.id,'no-amt')">
							</span>

							<span class="block-element bottom-push-10">
								<select name="entrytype2" id="entrytype2" required="required">
									<?php if(!empty($entrytype2)): ?>
										<option value="<?php echo $entrytype2; ?>"><?php echo $entrytype2; ?></option>
									<?php endif; ?>
								</select>
							</span>
						</div>
						
						<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
					</form>
				</div>
			</div>
		<?php
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
			$del = mysqli_data_update('coa_entry_tbl',$usr_datasets,$usr_key);

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
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Recently deleted account head journal","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Selected info removed successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	//for search keywords
	if((isset($_POST['searchbutton'])) && (!empty($_POST['datefrom']) && !empty($_POST['dateto']))) {
		if(isset($_POST['acctheadval']) && !empty($_POST['acctheadval'])) {
			$keywords = " AND coa_id={$_POST['acctheadval']}";
		}
		$keywords .= " AND datelogged >= '{$_POST['datefrom']}' AND datelogged <= '{$_POST['dateto']}' GROUP BY coa_id";
	} else { 
		$keywords = " AND datelogged >= '{$server_get_date}' AND datelogged <= '{$server_get_date}' GROUP BY coa_id";
	}

	//pagination controller
	if(isset($_GET['pg']) && $_GET['pg'] >= 1) {
		$curpage = $_GET['pg'];
		$pgstart = $_GET['start']; $pglimit = $_GET['limit'];
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	} else {
		$curpage = 0;
		$pgstart = 0; $pglimit = 500;
		$additionalQuery = $keywords." LIMIT ".$pgstart.",".$pglimit;
	}

	//$dataproperty = "coa_id,amount,entry_type,detail,mmonth,yyear,userid,datelogged,timelogged";
	$dataproperty = "coa_id";
	$constrain = array("deletedata"=>0);
	$row = "array";

	$dataCollect = mysqli_data_fetch('coa_entry_tbl',$dataproperty,$constrain,$row);

	$htmlresult .= '<form action="" method="post" autocomplete="off" onsubmit="objDisplay('.$processbar.')">';
	$htmlresult .= '<div class="block-element bottom-push-30 top-push-30">';
	$htmlresult .= '<span class="ln-display-box float-left nc-width-70">';
	$htmlresult .= '<div class="ln-display-box float-left nc-width-30 right-pull-10">';
	$htmlresult .= '<select name="acctheadval" id="acctheadval"><option value="" selected>ACCOUNT HEAD</option>'.$acctHeadVals.'</select>';
	$htmlresult .= '</div>';
	$htmlresult .= '<div class="ln-display-box float-left nc-width-25 right-pull-5">';
	$htmlresult .= '<input type="text" name="datefrom" id="datefrom" placeholder="DATE FROM" onclick="this.type=this.lang; showPicker()" lang="date">';
	$htmlresult .= '</div>';
	$htmlresult .= '<div class="ln-display-box float-left nc-width-25 left-pull-5">';
	$htmlresult .= '<input type="text" name="dateto" id="dateto" placeholder="DATE TO" onclick="this.type=this.lang; showPicker()" lang="date">';
	$htmlresult .= '</div>';
	$htmlresult .= '<div class="ln-display-box float-left nc-width-20 alignrt">';
	$htmlresult .= '<input type="submit" name="searchbutton" id="sbtn" value=" Search " class="submit pads10 black-white-state sml-rounded-button">';
	$htmlresult .= '</div>';
	$htmlresult .= '<div class="block-element new-line-space"></div>';
	$htmlresult .= '</span>';
	$htmlresult .= '<span class="block-element new-line-space"></span>';
	$htmlresult .= '</div>';
	$htmlresult .= '</form>';

	if(is_array($dataCollect))
	{
		$thproperty = array("noth","account head","amount","cr/dr","loggedby","date");
		$tcount = count($thproperty);

		$processbar="'processbar'"; $fxobj="'sbtn'"; $fxclass="'submit pads10 black-white-state sml-rounded-button motion'";
		
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
		
		$dataid=""; $inline_category=""; $inline_product=""; $inline_table=""; $department=""; $store_name=""; $account_type=""; $account_name="";

		$constrain2=""; $dataproperty2=""; $dataCollect2="";

		foreach($dataCollect as $theader => $tdata) {
			
			$dataid = $tdata['coa_id'];

			$account_type = idget_data('coa_setup_tbl',$dataid,'account_type');
			$account_name = idget_data('coa_setup_tbl',$dataid,'account_name');

			if(!empty($_POST['datefrom']) && !empty($_POST['dateto'])) {
				$additionalQuery = " AND datelogged >= '{$_POST['datefrom']}' AND datelogged <= '{$_POST['dateto']}'";
			} else {
				$additionalQuery = " AND datelogged >= '{$server_get_date}' AND datelogged <= '{$server_get_date}'";
			}

			$constrain2 = array("coa_id"=>$dataid,"deletedata"=>0);
			$dataproperty2 = "amount,entry_type,detail,mmonth,yyear,userid,datelogged,timelogged";
			$dataCollect2 = mysqli_data_fetch('coa_entry_tbl',$dataproperty2,$constrain2,'array');
					
			$htmlresult .= '<tr>';
			$htmlresult .= '<td width="70px" class="box-border-thick-right" align="center">&nbsp;</td>';
			$htmlresult .= '<td colspan="5" align="left"><u>'.$account_type.'</u><br>'.$account_name.'</td>';
			$htmlresult .= '</tr>';

			$num=0; $g=""; $trcolor=""; $loggedby=""; $grtotal=0;

			foreach($dataCollect2 as $theader2 => $tdata2) {

				$num += 1;
				$g = $num / 2;

				$trcolor = is_int($g) ? '#F9F9F9' : '#D1E0ED';

				$loggedby = idget_data($tbL7,$tdata2['userid'],'staffname');

				$htmlresult .= '<tr bgcolor="'.$trcolor.'">';
				$htmlresult .= '<td width="30px" class="box-border-thick-right" align="center">'.$num.'</td>';
				$htmlresult .= '<td width="200px" align="left" class="box-border-thick-right">'.$tdata2['detail'].'</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right blue-font">&#8358; '.number_format($tdata2['amount'],2).'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.$tdata2['entry_type'].'</td>';
				$htmlresult .= '<td width="150px" align="center" class="box-border-thick-right">'.$loggedby.'</td>';
				$htmlresult .= '<td width="100px" align="center" class="box-border-thick-right">'.date('d/m/Y',strtotime($tdata2['datelogged'])).'</td>';
				$htmlresult .= '</tr>';

				$grtotal = $grtotal + $tdata2['amount'];
			}

			$htmlresult .= '<tr>';
			$htmlresult .= '<td colspan="2">Total</td>';
			$htmlresult .= '<td align="center" class="default-text-font-bold">&#8358; '.number_format($grtotal,2).'</td>';
			$htmlresult .= '<td colspan="3">&nbsp;</td>';
			$htmlresult .= '</tr>';

			$htmlresult .= '<tr>';
			$htmlresult .= '<td colspan="7">&nbsp;</td>';
			$htmlresult .= '</tr>';
		}
		
		$htmlresult .= '</table>';
		$htmlresult .= '</div>';
	}
	else
	{
		$htmlresult .= '<div class="top-pull-50 alignct"><small class="dark-grey-font">There are no records at the moment!</small></div>';
	}

	echo $htmlresult;

	//paginate this page

	$additionalQuery = "";
	mysqli_data_check('coa_entry_tbl','(*)','');
	$totalcount = $numOfrows;

	$paginate = data_pagenation(500,0,$totalcount);
	if(isset($paginate) && !empty($paginate)) {
		echo $paginate;
	}

	//end of pagination

?>

<div id="pageurl" class="noshow"><?php echo $pageurl; ?></div>

<script>

	let ah = "<?php echo $_GET['ah']; ?>";
	let ext = "<?php echo $_GET['ext']; ?>";

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

	function getaccthead(val) {
		if(val !== null && val != '') {
			window.location.href = window.location.href+'&ah='+val;
		}
	}

	function getaccthead2(val) {
		if(val !== null && val != '') {
			let ety = document.getElementById('entrytype').value;
			let amt = document.getElementById('jrAmt').value;
			let dtl = document.getElementById('detail').value;

			amt = amt.replace(',','');
			window.location.href = window.location.href+'&ah='+ah+'&ah2='+val+'&ety='+ety+'&amt='+amt+'&dtl='+dtl;
		}
	}

	function getacctheadEx(val) {
		if(val !== null && val != '') {
			window.location.href = window.location.href+'&ext='+val;
		}
	}

	function entryType(elem) {
		document.getElementById('dr-cr').classList.remove('noshow');
		if(elem.value == 'Credit') {
			document.getElementById('drcr-label').innerText = '+ Include Debit Account';
			document.getElementById('entrytype2').innerHTML = '<option value="Debit">Debit</option>';
		} else if(elem.value == 'Debit') {
			document.getElementById('drcr-label').innerText = '+ Include Credit Account';
			document.getElementById('entrytype2').innerHTML = '<option value="Credit">Credit</option>';
		}
	}

</script>