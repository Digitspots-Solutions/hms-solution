<?php
$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
$get_posstores = mysqli_data_fetch($tbL14,'id,posname,postype',$pst_query,'array');

$cass_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Current Assets','Cash') AND status IN('Active') AND deletedata=0"; $cass_accts_list = mysqli_data_array('assoc',$cass_accts_sql);

$fass_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Fixed Assets') AND status IN('Active') AND deletedata=0"; $fass_accts_list = mysqli_data_array('assoc',$fass_accts_sql);

$clb_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Current Liabilities','Long Term Liabilities') AND status IN('Active') AND deletedata=0"; $clb_accts_list = mysqli_data_array('assoc',$clb_accts_sql);

$equt_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Equity-Gets Closed','Equity-Gets Closed') AND status IN('Active') AND deletedata=0"; $equt_accts_list = mysqli_data_array('assoc',$equt_accts_sql);

$df_year = date('Y',strtotime($server_get_date));
$lf_year = $df_year - 5;

$get_year = (isset($_GET['ydt']) && !empty($_GET['ydt'])) ? $_GET['ydt'] : $df_year;

$jan_sds = $get_year.'-01-01'; $jan_eds = $get_year.'-01-31';
$feb_sds = $get_year.'-02-01'; $feb_eds = $get_year.'-02-29';
$mar_sds = $get_year.'-03-01'; $mar_eds = $get_year.'-03-30';
$apr_sds = $get_year.'-04-01'; $apr_eds = $get_year.'-04-30';
$may_sds = $get_year.'-05-01'; $may_eds = $get_year.'-05-31';
$jun_sds = $get_year.'-06-01'; $jun_eds = $get_year.'-06-30';
$jul_sds = $get_year.'-07-01'; $jul_eds = $get_year.'-07-31';
$aug_sds = $get_year.'-08-01'; $aug_eds = $get_year.'-08-31';
$sep_sds = $get_year.'-09-01'; $sep_eds = $get_year.'-09-30';
$oct_sds = $get_year.'-10-01'; $oct_eds = $get_year.'-10-31';
$nov_sds = $get_year.'-11-01'; $nov_eds = $get_year.'-11-30';
$dec_sds = $get_year.'-12-01'; $dec_eds = $get_year.'-12-31';

$jan_revenue = 0; $feb_revenue = 0; $mar_revenue = 0; $apr_revenue = 0; $may_revenue = 0; $jun_revenue = 0;
$jul_revenue = 0; $aug_revenue = 0; $sep_revenue = 0; $oct_revenue = 0; $nov_revenue = 0; $dec_revenue = 0;

$jan_taxes = 0; $feb_taxes = 0; $mar_taxes = 0; $apr_taxes = 0; $may_taxes = 0; $jun_taxes = 0;
$jul_taxes = 0; $aug_taxes = 0; $sep_taxes = 0; $oct_taxes = 0; $nov_taxes = 0; $dec_taxes = 0;

include "cash_sql.php";
include "receivables_sql.php";
include "inventory_sql.php";
include "expensesjo_sql.php";

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left top-pull-7">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here is balance sheet
 	</span>
 	<span class="ln-display-box float-right cs-width-250">
 		<ul class="nolist">
 			<li class="float-right cs-width-150 top-pull-7 left-pull-30">
 				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size ft-tahoma" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
 			</li>
 			<li class="float-right cs-width-100 box-border-thick xsml-rounded-button pads7">
 				<select name="yycombo" id="yycombo" class="nopads no-back-black" onchange="window.location.href=window.location.href+'&ydt='+this.value">
 					<option value="<?php echo $get_year; ?>" selected><?php echo $get_year; ?></option>
					<?php for($y=$df_year; $y > $lf_year; $y--): ?>
						<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
					<?php endfor; ?>
				</select>
 			</li>
 			<li class="block-element new-line-space">
 			</li>
 		</ul>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>
<p>&nbsp;</p>
<div id="section-to-print">
	<h3 class="xlarge ft-tahoma alignct">Balance Sheet (YEAR <?php echo $get_year; ?>)</h3>
	<p>&nbsp;</p>
	<div class="nc-width-100 x-scroll">
		<table cellpadding="5" cellspacing="0" class="cs-width-2000">
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">JAN</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">FEB</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">MAR</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">APR</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">MAY</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">JUN</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">JUL</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">AUG</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">SEP</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">OCT</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">NOV</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">DEC</b></td>
			</tr>
			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Assets</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
		
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma"><u class="ft-tahoma">Non-Current Assets:</u></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<?php
				if(is_array($fass_accts_list) && count($fass_accts_list) > 0):

					$fyyear=""; $plf=""; $ypl=""; $mmdr=""; $dateObj="";
					$cass_val_sql=""; $cass_val_list = "";

					$janFA=""; $febFA=""; $marFA=""; $aprFA="";
					$mayFA=""; $junFA=""; $julFA=""; $augFA="";
					$sepFA=""; $octFA=""; $novFA=""; $decFA="";

					$janGtl1=0; $febGtl1=0; $marGtl1=0; $aprGtl1=0; $mayGtl1=0; $junGtl1=0; $julGtl1=0; $augGtl1=0;
					$sepGtl1=0; $octGtl1=0; $novGtl1=0; $decGtl1=0;

					foreach($fass_accts_list as $key => $val):

						if($val['plf'] > 0) {

							$dateObj = new DateTime($val['plf_date']);
							$fyyear = $dateObj->format('Y');
							//$fyyear = date('Y',strtotime($val['plf_date']));
						
							$plf = $fyyear - $get_year;
							$ypl = $val['ppr'] / $plf;
							$mmdr = $ypl / 12;

							$janFA = $val['ppr'] - (($ypl / 12) * 1);
							$febFA = $val['ppr'] - (($ypl / 12) * 2);
							$marFA = $val['ppr'] - (($ypl / 12) * 3);
							$aprFA = $val['ppr'] - (($ypl / 12) * 4);
							$mayFA = $val['ppr'] - (($ypl / 12) * 5);
							$junFA = $val['ppr'] - (($ypl / 12) * 6);
							$julFA = $val['ppr'] - (($ypl / 12) * 7);
							$augFA = $val['ppr'] - (($ypl / 12) * 8);
							$sepFA = $val['ppr'] - (($ypl / 12) * 9);
							$octFA = $val['ppr'] - (($ypl / 12) * 10);
							$novFA = $val['ppr'] - (($ypl / 12) * 11);
							$decFA = $val['ppr'] - $ypl;

							/*$janFA = $val['ppr'] - $mmdr;
							$febFA = $val['ppr'] - $mmdr;
							$marFA = $val['ppr'] - $mmdr;
							$aprFA = $val['ppr'] - $mmdr;
							$mayFA = $val['ppr'] - $mmdr;
							$junFA = $val['ppr'] - $mmdr;
							$julFA = $val['ppr'] - $mmdr;
							$augFA = $val['ppr'] - $mmdr;
							$sepFA = $val['ppr'] - $mmdr;
							$octFA = $val['ppr'] - $mmdr;
							$novFA = $val['ppr'] - $mmdr;
							$decFA = $val['ppr'] - $mmdr;*/

						} else {

							$janFA = $val['ppr'];
							$febFA = $val['ppr'];
							$marFA = $val['ppr'];
							$aprFA = $val['ppr'];
							$mayFA = $val['ppr'];
							$junFA = $val['ppr'];
							$julFA = $val['ppr'];
							$augFA = $val['ppr'];
							$sepFA = $val['ppr'];
							$octFA = $val['ppr'];
							$novFA = $val['ppr'];
							$decFA = $val['ppr'];
						}
						

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($janFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($febFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($marFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aprFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mayFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($junFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($julFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($augFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sepFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($octFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($novFA,2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($decFA,2); ?></td>
							</tr>
						<?php

						$janGtl1 = $janGtl1 + $janFA;
						$febGtl1 = $febGtl1 + $febFA;
						$marGtl1 = $marGtl1 + $marFA;
						$aprGtl1 = $aprGtl1 + $aprFA;
						$mayGtl1 = $mayGtl1 + $mayFA;
						$junGtl1 = $junGtl1 + $junFA;
						$julGtl1 = $julGtl1 + $julFA;
						$augGtl1 = $augGtl1 + $augFA;
						$sepGtl1 = $sepGtl1 + $sepFA;
						$octGtl1 = $octGtl1 + $octFA;
						$novGtl1 = $novGtl1 + $novFA;
						$decGtl1 = $decGtl1 + $decFA;
						
					endforeach;
				endif;
			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl1,2); ?></b></td>
			</tr>
			<tr class="dark-grey-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5"><u class="ft-tahoma">Current Assets:</u></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<?php
				if(is_array($cass_accts_list) && count($cass_accts_list) > 0):

					$cass_val_sql=""; $cass_val_list = "";

					$janGtl=0; $febGtl=0; $marGtl=0; $aprGtl=0; $mayGtl=0; $junGtl=0; $julGtl=0; $augGtl=0; $sepGtl=0;
					$octGtl=0; $novGtl=0; $decGtl=0;

					foreach($cass_accts_list as $key => $val):

						$cass_val_sql = "SELECT coa_id AS ExAcct, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('January') AND yyear IN({$get_year})) AS JanCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('February') AND yyear IN({$get_year})) AS FebCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('March') AND yyear IN({$get_year})) AS MarCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('April') AND yyear IN({$get_year})) AS AprCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('May') AND yyear IN({$get_year})) AS MayCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('June') AND yyear IN({$get_year})) AS JunCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('July') AND yyear IN({$get_year})) AS JulCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('August') AND yyear IN({$get_year})) AS AugCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('September') AND yyear IN({$get_year})) AS SepCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('October') AND yyear IN({$get_year})) AS OctCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('November') AND yyear IN({$get_year})) AS NovCA, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('December') AND yyear IN({$get_year})) AS DecCA FROM coa_entry_tbl WHERE coa_id={$val['id']}";
						
						$cass_val_list = mysqli_data_array('assoc',$cass_val_sql);

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['JanCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['FebCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['MarCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['AprCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['MayCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['JunCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['JulCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['AugCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['SepCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['OctCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['NovCA'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cass_val_list[0]['DecCA'],2); ?></td>
							</tr>
						<?php

						$janGtl = $janGtl + $cass_val_list[0]['JanCA'];
						$febGtl = $febGtl + $cass_val_list[0]['FebCA'];
						$marGtl = $marGtl + $cass_val_list[0]['MarCA'];
						$aprGtl = $aprGtl + $cass_val_list[0]['AprCA'];
						$mayGtl = $mayGtl + $cass_val_list[0]['MayCA'];
						$junGtl = $junGtl + $cass_val_list[0]['JunCA'];
						$julGtl = $julGtl + $cass_val_list[0]['JulCA'];
						$augGtl = $augGtl + $cass_val_list[0]['AugCA'];
						$sepGtl = $sepGtl + $cass_val_list[0]['SepCA'];
						$octGtl = $octGtl + $cass_val_list[0]['OctCA'];
						$novGtl = $novGtl + $cass_val_list[0]['NovCA'];
						$decGtl = $decGtl + $cass_val_list[0]['DecCA'];
						
					endforeach;
				endif;
			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl,2); ?></b></td>
			</tr>
			<tr class="dark-grey-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>

			<tr class="sky-deep-blue-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total Assets</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl + $janGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl + $febGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl + $marGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl + $aprGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl + $mayGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl + $junGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl + $julGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl + $augGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl + $sepGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl + $octGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl + $novGtl1,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl + $decGtl1,2); ?></b></td>
			</tr>
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			
			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Liabilities</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<?php
				if(is_array($clb_accts_list) && count($clb_accts_list) > 0):

					$clb_val_sql=""; $clb_val_list = "";

					$janGtl2=0; $febGtl2=0; $marGtl2=0; $aprGtl2=0; $mayGtl2=0; $junGtl2=0; $julGtl2=0; $augGtl2=0;
					$sepGtl2=0; $octGtl2=0; $novGtl2=0; $decGtl2=0;

					foreach($clb_accts_list as $key => $val):

						$clb_val_sql = "SELECT coa_id AS ExAcct, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('January') AND yyear IN({$get_year})) AS JanLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('February') AND yyear IN({$get_year})) AS FebLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('March') AND yyear IN({$get_year})) AS MarLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('April') AND yyear IN({$get_year})) AS AprLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('May') AND yyear IN({$get_year})) AS MayLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('June') AND yyear IN({$get_year})) AS JunLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('July') AND yyear IN({$get_year})) AS JulLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('August') AND yyear IN({$get_year})) AS AugLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('September') AND yyear IN({$get_year})) AS SepLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('October') AND yyear IN({$get_year})) AS OctLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('November') AND yyear IN({$get_year})) AS NovLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('December') AND yyear IN({$get_year})) AS DecLB FROM coa_entry_tbl WHERE coa_id={$val['id']}";
						
						$clb_val_list = mysqli_data_array('assoc',$clb_val_sql);

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['JanLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['FebLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['MarLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['AprLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['MayLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['JunLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['JulLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['AugLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['SepLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['OctLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['NovLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($clb_val_list[0]['DecLB'],2); ?></td>
							</tr>
						<?php

						$janGtl2 = $janGtl2 + $clb_val_list[0]['JanLB'];
						$febGtl2 = $febGtl2 + $clb_val_list[0]['FebLB'];
						$marGtl2 = $marGtl2 + $clb_val_list[0]['MarLB'];
						$aprGtl2 = $aprGtl2 + $clb_val_list[0]['AprLB'];
						$mayGtl2 = $mayGtl2 + $clb_val_list[0]['MayLB'];
						$junGtl2 = $junGtl2 + $clb_val_list[0]['JunLB'];
						$julGtl2 = $julGtl2 + $clb_val_list[0]['JulLB'];
						$augGtl2 = $augGtl2 + $clb_val_list[0]['AugLB'];
						$sepGtl2 = $sepGtl2 + $clb_val_list[0]['SepLB'];
						$octGtl2 = $octGtl2 + $clb_val_list[0]['OctLB'];
						$novGtl2 = $novGtl2 + $clb_val_list[0]['NovLB'];
						$decGtl2 = $decGtl2 + $clb_val_list[0]['DecLB'];
						
					endforeach;
				endif;
			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl2,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl2,2); ?></b></td>
			</tr>
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Equity</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<?php
				if(is_array($equt_accts_list) && count($equt_accts_list) > 0):

					$equt_val_sql=""; $equt_val_list = "";

					$janGtl3=0; $febGtl3=0; $marGtl3=0; $aprGtl3=0; $mayGtl3=0; $junGtl3=0; $julGtl3=0; $augGtl3=0;
					$sepGtl3=0; $octGtl3=0; $novGtl3=0; $decGtl3=0;

					foreach($equt_accts_list as $key => $val):

						$equt_val_sql = "SELECT coa_id AS ExAcct, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('January') AND yyear IN({$get_year})) AS JanLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('February') AND yyear IN({$get_year})) AS FebLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('March') AND yyear IN({$get_year})) AS MarLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('April') AND yyear IN({$get_year})) AS AprLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('May') AND yyear IN({$get_year})) AS MayLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('June') AND yyear IN({$get_year})) AS JunLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('July') AND yyear IN({$get_year})) AS JulLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('August') AND yyear IN({$get_year})) AS AugLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('September') AND yyear IN({$get_year})) AS SepLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('October') AND yyear IN({$get_year})) AS OctLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('November') AND yyear IN({$get_year})) AS NovLB, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('December') AND yyear IN({$get_year})) AS DecLB FROM coa_entry_tbl WHERE coa_id={$val['id']}";
						
						$equt_val_list = mysqli_data_array('assoc',$equt_val_sql);

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['JanLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['FebLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['MarLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['AprLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['MayLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['JunLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['JulLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['AugLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['SepLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['OctLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['NovLB'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($equt_val_list[0]['DecLB'],2); ?></td>
							</tr>
						<?php

						$janGtl3 = $janGtl3 + $equt_val_list[0]['JanLB'];
						$febGtl3 = $febGtl3 + $equt_val_list[0]['FebLB'];
						$marGtl3 = $marGtl3 + $equt_val_list[0]['MarLB'];
						$aprGtl3 = $aprGtl3 + $equt_val_list[0]['AprLB'];
						$mayGtl3 = $mayGtl3 + $equt_val_list[0]['MayLB'];
						$junGtl3 = $junGtl3 + $equt_val_list[0]['JunLB'];
						$julGtl3 = $julGtl3 + $equt_val_list[0]['JulLB'];
						$augGtl3 = $augGtl3 + $equt_val_list[0]['AugLB'];
						$sepGtl3 = $sepGtl3 + $equt_val_list[0]['SepLB'];
						$octGtl3 = $octGtl3 + $equt_val_list[0]['OctLB'];
						$novGtl3 = $novGtl3 + $equt_val_list[0]['NovLB'];
						$decGtl3 = $decGtl3 + $equt_val_list[0]['DecLB'];
						
					endforeach;
				endif;
			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl3,2); ?></b></td>
			</tr>
			<tr class="dark-grey-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<tr class="sky-deep-blue-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total Liabilities & Equity</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($janGtl2 + $janGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($febGtl2 + $febGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($marGtl2 + $marGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aprGtl2 + $aprGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mayGtl2 + $mayGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($junGtl2 + $junGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($julGtl2 + $julGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($augGtl2 + $augGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sepGtl2 + $sepGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($octGtl2 + $octGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($novGtl2 + $novGtl3,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($decGtl2 + $decGtl3,2); ?></b></td>
			</tr>
			<tr class="white-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<tr class="grey-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Net Worth (&#8358;)</b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($janGtl2 + $janGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($febGtl2 + $febGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($marGtl2 + $marGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($aprGtl2 + $aprGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($mayGtl2 + $mayGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($junGtl2 + $junGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($julGtl2 + $julGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($augGtl2 + $augGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($sepGtl2 + $sepGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($octGtl2 + $octGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($novGtl2 + $novGtl3),2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format(($janGtl + $janGtl1) - ($decGtl2 + $decGtl3),2); ?></b></td>
			</tr>
		</table>
	</div>
</div>