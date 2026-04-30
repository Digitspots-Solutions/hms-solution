<?php
$smdl = "accounting"; $logs = escape_data($_GET['logs']);

include "../../includes/class.vars.php";
include "../../includes/class.function.php";

$update_result = '';
$post_result = '';
$htmlresult = '';

$pst_query = array("deletedata"=>0,"status"=>"Active","iscounter"=>"Yes");
$get_posstores = mysqli_data_fetch($tbL14,'id,posname,postype',$pst_query,'array');

$exp_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Expenses') AND status IN('Active') AND deletedata=0";
$exp_accts_list = mysqli_data_array('assoc',$exp_accts_sql);

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

include "booking_sql.php";
include "recreation_sql.php";
include "expenses_sql.php";

?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left top-pull-7">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here is profit and loss statement
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
	<h3 class="xlarge ft-tahoma alignct">P&L Statement</h3>
	<p>&nbsp;</p>
	<table cellpadding="5" cellspacing="0">
		<tr>
			<td colspan="13" class="alignct"><b class="ft-tahoma">YEAR <?php echo $get_year; ?></b></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5">&nbsp;</td>
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
			<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Revenue</b></td>
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
			<td class="right-pull-5 left-pull-5 ft-tahoma">Booking Sales</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_jan_row[0]['janRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_feb_row[0]['febRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_mar_row[0]['marRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_apr_row[0]['aprRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_may_row[0]['mayRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_jun_row[0]['junRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_jul_row[0]['julRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_aug_row[0]['augRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_sep_row[0]['sepRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_oct_row[0]['octRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_nov_row[0]['novRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_dec_row[0]['decRevenue'],2); ?></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma">Recreation</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_jan_row[0]['janRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_feb_row[0]['febRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_mar_row[0]['marRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_apr_row[0]['aprRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_may_row[0]['mayRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_jun_row[0]['junRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_jul_row[0]['julRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_aug_row[0]['augRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_sep_row[0]['sepRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_oct_row[0]['octRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_nov_row[0]['novRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_dec_row[0]['decRevenue'],2); ?></td>
		</tr>

		<?php
			$jan_revenue = $bk_jan_row[0]['janRevenue'] + $rcr_jan_row[0]['janRevenue'];
			$feb_revenue = $bk_feb_row[0]['janRevenue'] + $rcr_feb_row[0]['febRevenue'];
			$mar_revenue = $bk_mar_row[0]['marRevenue'] + $rcr_mar_row[0]['marRevenue'];
			$apr_revenue = $bk_apr_row[0]['aprRevenue'] + $rcr_apr_row[0]['aprRevenue'];
			$may_revenue = $bk_may_row[0]['mayRevenue'] + $rcr_may_row[0]['mayRevenue'];
			$jun_revenue = $bk_jun_row[0]['junRevenue'] + $rcr_jun_row[0]['junRevenue'];
			$jul_revenue = $bk_jul_row[0]['julRevenue'] + $rcr_jul_row[0]['julRevenue'];
			$aug_revenue = $bk_aug_row[0]['augRevenue'] + $rcr_aug_row[0]['augRevenue'];
			$sep_revenue = $bk_sep_row[0]['sepRevenue'] + $rcr_sep_row[0]['sepRevenue'];
			$oct_revenue = $bk_oct_row[0]['octRevenue'] + $rcr_oct_row[0]['octRevenue'];
			$nov_revenue = $bk_nov_row[0]['novRevenue'] + $rcr_nov_row[0]['novRevenue'];
			$dec_revenue = $bk_dec_row[0]['decRevenue'] + $rcr_dec_row[0]['decRevenue'];

			$jan_taxes = $bkt_jan_row[0]['janTaxes'] + $rcrt_jan_taxes;
			$feb_taxes = $bkt_feb_row[0]['febTaxes'] + $rcrt_feb_taxes;
			$mar_taxes = $bkt_mar_row[0]['marTaxes'] + $rcrt_mar_taxes;
			$apr_taxes = $bkt_apr_row[0]['aprTaxes'] + $rcrt_apr_taxes;
			$may_taxes = $bkt_may_row[0]['mayTaxes'] + $rcrt_may_taxes;
			$jun_taxes = $bkt_jun_row[0]['junTaxes'] + $rcrt_jun_taxes;
			$jul_taxes = $bkt_jul_row[0]['julTaxes'] + $rcrt_jul_taxes;
			$aug_taxes = $bkt_aug_row[0]['augTaxes'] + $rcrt_aug_taxes;
			$sep_taxes = $bkt_sep_row[0]['sepTaxes'] + $rcrt_sep_taxes;
			$oct_taxes = $bkt_oct_row[0]['octTaxes'] + $rcrt_oct_taxes;
			$nov_taxes = $bkt_nov_row[0]['novTaxes'] + $rcrt_nov_taxes;
			$dec_taxes = $bkt_dec_row[0]['decTaxes'] + $rcrt_dec_taxes;


			if(is_array($get_posstores)):
			foreach($get_posstores as $key => $val):

			include "pos_sql.php";
		?>

		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['posname']; ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_jan_row[0]['janRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_feb_row[0]['febRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_mar_row[0]['marRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_apr_row[0]['aprRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_may_row[0]['mayRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_jun_row[0]['junRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_jul_row[0]['julRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_aug_row[0]['augRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_sep_row[0]['sepRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_oct_row[0]['octRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_nov_row[0]['novRevenue'],2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_dec_row[0]['decRevenue'],2); ?></td>
		</tr>

		<?php

			$jan_revenue = $jan_revenue + $pos_jan_row[0]['janRevenue'];
			$feb_revenue = $feb_revenue + $pos_feb_row[0]['febRevenue'];
			$mar_revenue = $mar_revenue + $pos_mar_row[0]['marRevenue'];
			$apr_revenue = $apr_revenue + $pos_apr_row[0]['aprRevenue'];
			$may_revenue = $may_revenue + $pos_may_row[0]['mayRevenue'];
			$jun_revenue = $jun_revenue + $pos_jun_row[0]['junRevenue'];
			$jul_revenue = $jul_revenue + $pos_jul_row[0]['julRevenue'];
			$aug_revenue = $aug_revenue + $pos_aug_row[0]['augRevenue'];
			$sep_revenue = $sep_revenue + $pos_sep_row[0]['sepRevenue'];
			$oct_revenue = $oct_revenue + $pos_oct_row[0]['octRevenue'];
			$nov_revenue = $nov_revenue + $pos_nov_row[0]['novRevenue'];
			$dec_revenue = $dec_revenue + $pos_dec_row[0]['decRevenue'];

			$jan_taxes = $jan_taxes + $post_jan_row[0]['janTaxes'];
			$feb_taxes = $feb_taxes + $post_feb_row[0]['febTaxes'];
			$mar_taxes = $mar_taxes + $post_mar_row[0]['marTaxes'];
			$apr_taxes = $apr_taxes + $post_apr_row[0]['aprTaxes'];
			$may_taxes = $may_taxes + $post_may_row[0]['mayTaxes'];
			$jun_taxes = $jun_taxes + $post_jun_row[0]['junTaxes'];
			$jul_taxes = $jul_taxes + $post_jul_row[0]['julTaxes'];
			$aug_taxes = $aug_taxes + $post_aug_row[0]['augTaxes'];
			$sep_taxes = $sep_taxes + $post_sep_row[0]['sepTaxes'];
			$oct_taxes = $oct_taxes + $post_oct_row[0]['octTaxes'];
			$nov_taxes = $nov_taxes + $post_nov_row[0]['novTaxes'];
			$dec_taxes = $dec_taxes + $post_dec_row[0]['decTaxes'];

			$pos_jan_sql = ""; $pos_feb_sql = ""; $pos_mar_sql = ""; $pos_apr_sql = ""; $pos_may_sql = "";
			$pos_jun_sql = ""; $pos_jul_sql = ""; $pos_aug_sql = ""; $pos_sep_sql = ""; $pos_oct_sql = "";
			$pos_nov_sql = ""; $pos_dec_sql = "";

			endforeach;
			endif;
		?>

		<tr class="sky-blue-theme">
			<td class="right-pull-5 left-pull-5 ft-tahoma sky-blue-theme">Total</td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jan_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($feb_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mar_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($apr_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($may_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jun_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jul_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aug_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sep_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($oct_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($nov_revenue,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($dec_revenue,2); ?></b></td>
		</tr>
		<tr>
			<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Operating Expenses</b></td>
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
			<td class="right-pull-5 left-pull-5 ft-tahoma">Taxes</td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jan_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($feb_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mar_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($apr_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($may_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jun_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jul_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aug_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sep_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($oct_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($nov_taxes,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($dec_taxes,2); ?></td>
		</tr>
		<tr class="yellow-theme">
			<td class="right-pull-5 left-pull-5 ft-tahoma">Cost of Goods</td>
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

		<?php

			$jan_expenses = $jan_taxes + $exp_jan_row[0]['janExpenses'];
			$feb_expenses = $feb_taxes + $exp_feb_row[0]['febExpenses'];
			$mar_expenses = $mar_taxes + $exp_mar_row[0]['marExpenses'];
			$apr_expenses = $apr_taxes + $exp_apr_row[0]['aprExpenses'];
			$may_expenses = $may_taxes + $exp_may_row[0]['mayExpenses'];
			$jun_expenses = $jun_taxes + $exp_jun_row[0]['junExpenses'];
			$jul_expenses = $jul_taxes + $exp_jul_row[0]['julExpenses'];
			$aug_expenses = $aug_taxes + $exp_aug_row[0]['augExpenses'];
			$sep_expenses = $sep_taxes + $exp_sep_row[0]['sepExpenses'];
			$oct_expenses = $oct_taxes + $exp_oct_row[0]['octExpenses'];
			$nov_expenses = $nov_taxes + $exp_nov_row[0]['novExpenses'];
			$dec_expenses = $dec_taxes + $exp_dec_row[0]['decExpenses'];


			$jan_net_income = $jan_revenue - $exp_jan_row[0]['janExpenses'];
			$feb_net_income = $feb_revenue - $exp_feb_row[0]['febExpenses'];
			$mar_net_income = $mar_revenue - $exp_mar_row[0]['marExpenses'];
			$apr_net_income = $apr_revenue - $exp_apr_row[0]['aprExpenses'];
			$may_net_income = $may_revenue - $exp_may_row[0]['mayExpenses'];
			$jun_net_income = $jun_revenue - $exp_jun_row[0]['junExpenses'];
			$jul_net_income = $jul_revenue - $exp_jul_row[0]['julExpenses'];
			$aug_net_income = $aug_revenue - $exp_aug_row[0]['augExpenses'];
			$sep_net_income = $sep_revenue - $exp_sep_row[0]['sepExpenses'];
			$oct_net_income = $oct_revenue - $exp_oct_row[0]['octExpenses'];
			$nov_net_income = $nov_revenue - $exp_nov_row[0]['novExpenses'];
			$dec_net_income = $dec_revenue - $exp_dec_row[0]['decExpenses'];

		?>

		<tr>
			<td class="right-pull-5 left-pull-5 ft-tahoma sky-blue-theme">Total</td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jan_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($feb_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mar_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($apr_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($may_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jun_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jul_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aug_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sep_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($oct_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($nov_expenses,2); ?></b></td>
			<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($dec_expenses,2); ?></b></td>
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
			<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Net Income (P/L)</b></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jan_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($feb_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mar_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($apr_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($may_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jun_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($jul_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aug_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sep_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($oct_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($nov_net_income,2); ?></td>
			<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($dec_net_income,2); ?></td>
		</tr>
	</table>
</div>