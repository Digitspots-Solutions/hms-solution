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
$df_month = date('m',strtotime($server_get_date));

$mm_sds = $df_year.'-'.$df_month.'-01'; $mm_eds = $df_year.'-'.$df_month.'-31';

include "revenue_sql.php";

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left top-pull-7">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here is trial balance
 	</span>
 	<span class="ln-display-box float-right cs-width-250">
 		<ul class="nolist">
 			<li class="float-right cs-width-150 top-pull-7 left-pull-30">
 				<a href="javascript:void(0)" class="top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 dark-black-white-state xsml-rounded-button ft-xsml-size ft-tahoma" onclick="window.print()" title="Print Report"><b class="mbri-print right-push-5"></b> Print</a>
 			</li>
 			<li class="float-right cs-width-100 box-border-thick xsml-rounded-button pads7">
 				&nbsp;
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
	<h3 class="xlarge ft-tahoma alignct">Trial Balance</h3>
	<p>&nbsp;</p>
	<table cellpadding="5" cellspacing="0">
		<tr>
			<td class="right-pull-5 left-pull-5 alignlt"><b class="ft-tahoma">DESCRIPTION</b></td>
			<td class="right-pull-5 left-pull-5 alignlt"><b class="ft-tahoma">ACCOUNT TYPE</b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">DEBIT</b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma">CREDIT</b></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Sales</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Revenue</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">0.00</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sales_revenue,2); ?></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Accounts Payable</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Current Liability</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">0.00</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($acct_payable,2); ?></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Accounts Receivable</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Current Assets</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($acct_receivable,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">0.00</td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Taxes</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Current Liability</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($taxes_charge,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">0.00</td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
		</tr>

		<?php
			$total_debit = $acct_receivable + $taxes_charge;
			$total_credit = $sales_revenue + $acct_payable;
		?>

		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Total</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($total_debit,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($total_credit,2); ?></b></td>
		</tr>
	</table>
</div>