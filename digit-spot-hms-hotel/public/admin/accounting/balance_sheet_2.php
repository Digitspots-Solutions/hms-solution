<?php
$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
$get_posstores = mysqli_data_fetch($tbL14,'id,posname,postype',$pst_query,'array');

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
	<h3 class="xlarge ft-tahoma alignct">Balance Sheet</h3>
	<p>&nbsp;</p>
	<table cellpadding="5" cellspacing="0">
		<tr>
			<td colspan="13" class="alignct"><b class="ft-tahoma">YEAR <?php echo $get_year; ?></b></td>
		</tr>
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
			<td class="right-pull-5 left-pull-5 ft-tahoma">Cash</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($janCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($febCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($marCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aprCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mayCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($junCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($julCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($augCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sepCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($octCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($novCash,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($decCash,2); ?></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma">Accounts Receivable</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jan_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($feb_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mar_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($apr_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($may_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jun_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jul_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aug_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sep_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($oct_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($nov_ar,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($dec_ar,2); ?></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma">Inventory</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_jan_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_feb_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_mar_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_apr_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_may_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_jun_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_jul_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_aug_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_sep_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_oct_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_nov_row[0]['AmountIncur'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($inv_dec_row[0]['AmountIncur'],2); ?></td>
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
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma">Accounts Payable</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_jan_row[0]['janExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_feb_row[0]['febExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_mar_row[0]['marExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_apr_row[0]['aprExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_may_row[0]['mayExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_jun_row[0]['junExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_jul_row[0]['julExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_aug_row[0]['augExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_sep_row[0]['sepExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_oct_row[0]['octExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_nov_row[0]['novExpenses'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_dec_row[0]['decExpenses'],2); ?></td>
		</tr>
		
	</table>
</div>