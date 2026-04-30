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

$accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type NOT IN('Fixed Assets') AND status IN('Active') AND deletedata=0";
$accts_list = mysqli_data_array('assoc',$accts_sql);

$get_year = $df_year;

//include "revenue_sql.php";

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
		
		<?php
			if(is_array($accts_list) && count($accts_list) > 0):

				$val_sql=""; $val_list = "";

				$totalCredit=0; $totalDebit=0;

				foreach($accts_list as $key => $val):

					$val_sql = "SELECT coa_id AS ExAcct, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND entry_type IN('Credit') AND yyear IN({$get_year})) AS AllCredits, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND entry_type IN('Debit') AND yyear IN({$get_year})) AS AllDebits FROM coa_entry_tbl WHERE coa_id={$val['id']}";

					$val_list = mysqli_data_array('assoc',$val_sql);

					?>
						<tr>
							<td class="right-pull-5 left-pull-5 ft-tahoma alignlt"><?php echo $val['account_name']; ?></td>
							<td class="right-pull-5 left-pull-5 ft-tahoma alignlt"><?php echo $val['account_type']; ?></td>
							<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($val_list[0]['AllDebits'],2); ?></td>
							<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($val_list[0]['AllCredits'],2); ?></td>
						</tr>
					<?php

					$totalCredit = $totalCredit + $val_list[0]['AllCredits'];
					$totalDebit = $totalDebit + $val_list[0]['AllDebits'];
					
				endforeach;
			endif;
		?>
		
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
		</tr>

		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignlt">Total</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($totalDebit,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($totalCredit,2); ?></b></td>
		</tr>
	</table>
</div>