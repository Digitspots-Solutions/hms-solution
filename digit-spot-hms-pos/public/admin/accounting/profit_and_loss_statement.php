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

$df_month = date('m',strtotime($server_get_date));
$df_year = date('Y',strtotime($server_get_date));
$lf_year = $df_year - 5;

$get_aly = (isset($_GET['aly']) && !empty($_GET['aly'])) ? $_GET['aly'] : 'Detail Report';
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

$jan_sdsx = $get_year.'-01-01'; $dec_edsx = $get_year.'-12-31';

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
 	<span class="ln-display-box float-right cs-width-450">
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
 			<li class="float-right cs-width-150 box-border-thick xsml-rounded-button pads7">
 				<select name="alycombo" id="alycombo" class="nopads no-back-black" onchange="window.location.href=window.location.href+'&aly='+this.value">
 					<option value="<?php echo $get_aly; ?>" selected><?php echo $get_aly; ?></option>
					<option value="Detail Report">Detail Report</option>
					<option value="Summary Report">Summary Report</option>
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

<?php if($get_aly == 'Detail Report'): ?>

<div id="section-to-print">
	<h3 class="xlarge ft-tahoma alignct">P&L Statement (YEAR <?php echo $get_year; ?>)</h3>
	<h3 class="large nobold ft-tahoma alignct"><?php echo $get_aly; ?></h3>
	<p>&nbsp;</p>
	<div class="nc-width-100 x-scroll">
		<table cellpadding="5" cellspacing="0" class="cs-width-1500">
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

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total Revenue</td>
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

			<tr><td colspan="20" class="grey-theme"></td></tr>
			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Cost of Sales</b></td>
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

				$cos_sql = "SELECT (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-01') AS JanCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-02') AS FebCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-03') AS MarCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-04') AS AprCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-05') AS MayCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-06') AS JunCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-07') AS JulCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-08') AS AugCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-09') AS SepCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-10') AS OctCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-11') AS NovCOS, (SELECT SUM(order_net_r_amount) FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND DATE_FORMAT(delivery_date, '%Y-%m') = '{$get_year}-12') AS DecCOS FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND deletedata=0";
							
				$cos_list = mysqli_data_array('assoc',$cos_sql);

			?>

			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma">Goods/Material</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['JanCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['FebCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['MarCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['AprCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['MayCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['JunCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['JulCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['AugCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['SepCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['OctCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['NovCOS'],2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($cos_list[0]['DecCOS'],2); ?></td>
			</tr>

			<?php

				$jan_gpf = $jan_revenue - $cos_list[0]['JanCOS'];
				$feb_gpf = $feb_revenue - $cos_list[0]['FebCOS'];
				$mar_gpf = $mar_revenue - $cos_list[0]['MarCOS'];
				$apr_gpf = $apr_revenue - $cos_list[0]['AprCOS'];
				$may_gpf = $may_revenue - $cos_list[0]['MayCOS'];
				$jun_gpf = $jun_revenue - $cos_list[0]['JunCOS'];
				$jul_gpf = $jul_revenue - $cos_list[0]['JulCOS'];
				$aug_gpf = $aug_revenue - $cos_list[0]['AugCOS'];
				$sep_gpf = $sep_revenue - $cos_list[0]['SepCOS'];
				$oct_gpf = $oct_revenue - $cos_list[0]['OctCOS'];
				$nov_gpf = $nov_revenue - $cos_list[0]['NovCOS'];
				$dec_gpf = $dec_revenue - $cos_list[0]['DecCOS'];

			?>

			<tr><td colspan="20" class="grey-theme"></td></tr>
			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Gross Profit</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jan_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($feb_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mar_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($apr_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($may_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jun_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jul_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aug_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sep_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($oct_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($nov_gpf,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($dec_gpf,2); ?></b></td>
			</tr>
			

			<tr><td colspan="20"></td></tr>

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

			<?php
				if(is_array($exp_accts_list) && count($exp_accts_list) > 0):

					$exp_val_sql=""; $exp_val_list = "";

					$janGtl=0; $febGtl=0; $marGtl=0; $aprGtl=0; $mayGtl=0; $junGtl=0; $julGtl=0; $augGtl=0; $sepGtl=0;
					$octGtl=0; $novGtl=0; $decGtl=0;

					foreach($exp_accts_list as $key => $val):

						$exp_val_sql = "SELECT coa_id AS ExAcct, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('January') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS JanExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('February') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS FebExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('March') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS MarExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('April') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS AprExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('May') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS MayExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('June') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS JunExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('July') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS JulExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('August') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS AugExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('September') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS SepExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('October') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS OctExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('November') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS NovExp, (SELECT SUM(amount) FROM coa_entry_tbl WHERE coa_id=ExAcct AND mmonth IN('December') AND yyear IN({$get_year}) AND entry_type IN('Debit')) AS DecExp FROM coa_entry_tbl WHERE coa_id={$val['id']}";
						
						$exp_val_list = mysqli_data_array('assoc',$exp_val_sql);

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['JanExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['FebExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['MarExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['AprExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['MayExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['JunExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['JulExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['AugExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['SepExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['OctExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['NovExp'],2); ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val_list[0]['DecExp'],2); ?></td>
							</tr>
						<?php

						$janGtl = $janGtl + $exp_val_list[0]['JanExp'];
						$febGtl = $febGtl + $exp_val_list[0]['FebExp'];
						$marGtl = $marGtl + $exp_val_list[0]['MarExp'];
						$aprGtl = $aprGtl + $exp_val_list[0]['AprExp'];
						$mayGtl = $mayGtl + $exp_val_list[0]['MayExp'];
						$junGtl = $junGtl + $exp_val_list[0]['JunExp'];
						$julGtl = $julGtl + $exp_val_list[0]['JulExp'];
						$augGtl = $augGtl + $exp_val_list[0]['AugExp'];
						$sepGtl = $sepGtl + $exp_val_list[0]['SepExp'];
						$octGtl = $octGtl + $exp_val_list[0]['OctExp'];
						$novGtl = $novGtl + $exp_val_list[0]['NovExp'];
						$decGtl = $decGtl + $exp_val_list[0]['DecExp'];
						
					endforeach;
				endif;


				//for depreciation

				$fass_accts_sql = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Fixed Assets') AND status IN('Active') AND deletedata=0"; $fass_accts_list = mysqli_data_array('assoc',$fass_accts_sql);

				if(is_array($fass_accts_list) && count($fass_accts_list) > 0):

					$fyyear=""; $plf=""; $ypl=""; $mmdr=""; $dateObj="";
					$cass_val_sql=""; $cass_val_list = "";

					$janFA=""; $febFA=""; $marFA=""; $aprFA="";
					$mayFA=""; $junFA=""; $julFA=""; $augFA="";
					$sepFA=""; $octFA=""; $novFA=""; $decFA="";

					$janDpr=0; $febDpr=0; $marDpr=0; $aprDpr=0; $mayDpr=0; $junDpr=0; $julDpr=0; $augDpr=0;
					$sepDpr=0; $octDpr=0; $novDpr=0; $decDpr=0;

					foreach($fass_accts_list as $key => $val):

						if($val['plf'] > 0) {

							$dateObj = new DateTime($val['plf_date']);
							$fyyear = $dateObj->format('Y');
							
							$plf = $fyyear - $get_year;
							$ypl = $val['ppr'] / $plf;
							
							$janFA = ($ypl / 12) * 1;
							$febFA = ($ypl / 12) * 2;
							$marFA = ($ypl / 12) * 3;
							$aprFA = ($ypl / 12) * 4;
							$mayFA = ($ypl / 12) * 5;
							$junFA = ($ypl / 12) * 6;
							$julFA = ($ypl / 12) * 7;
							$augFA = ($ypl / 12) * 8;
							$sepFA = ($ypl / 12) * 9;
							$octFA = ($ypl / 12) * 10;
							$novFA = ($ypl / 12) * 11;
							$decFA = $ypl;

						} else {

							$janFA = 0;
							$febFA = 0;
							$marFA = 0;
							$aprFA = 0;
							$mayFA = 0;
							$junFA = 0;
							$julFA = 0;
							$augFA = 0;
							$sepFA = 0;
							$octFA = 0;
							$novFA = 0;
							$decFA = 0;
						}
						
						$janDpr = $janDpr + $janFA;
						$febDpr = $febDpr + $febFA;
						$marDpr = $marDpr + $marFA;
						$aprDpr = $aprDpr + $aprFA;
						$mayDpr = $mayDpr + $mayFA;
						$junDpr = $junDpr + $junFA;
						$julDpr = $julDpr + $julFA;
						$augDpr = $augDpr + $augFA;
						$sepDpr = $sepDpr + $sepFA;
						$octDpr = $octDpr + $octFA;
						$novDpr = $novDpr + $novFA;
						$decDpr = $decDpr + $decFA;
						
					endforeach;
				endif;
			?>

			<tr>
				<td class="sky-blue-theme right-pull-5 left-pull-5 ft-tahoma">Depreciation</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($janDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($febDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($marDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($aprDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($mayDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($junDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($julDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($augDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($sepDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($octDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($novDpr,2); ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($decDpr,2); ?></td>
			</tr>
			<tr class="grey-theme">
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

			<?php

				$jan_total_exp = $janDpr + $janGtl;
				$feb_total_exp = $febDpr + $febGtl;
				$mar_total_exp = $marDpr + $marGtl;
				$apr_total_exp = $aprDpr + $aprGtl;
				$may_total_exp = $mayDpr + $mayGtl;
				$jun_total_exp = $junDpr + $junGtl;
				$jul_total_exp = $julDpr + $julGtl;
				$aug_total_exp = $augDpr + $augGtl;
				$sep_total_exp = $sepDpr + $sepGtl;
				$oct_total_exp = $octDpr + $octGtl;
				$nov_total_exp = $novDpr + $novGtl;
				$dec_total_exp = $decDpr + $decGtl;

			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Total Expenses</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jan_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($feb_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($mar_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($apr_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($may_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jun_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($jul_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($aug_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($sep_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($oct_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($nov_total_exp,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($dec_total_exp,2); ?></b></td>
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

			<?php

				//for net income

				$jan_net_income = $jan_gpf - ($janDpr + $janGtl);
				$feb_net_income = $feb_gpf - ($febDpr + $febGtl);
				$mar_net_income = $mar_gpf - ($marDpr + $marGtl);
				$apr_net_income = $apr_gpf - ($aprDpr + $aprGtl);
				$may_net_income = $may_gpf - ($mayDpr + $mayGtl);
				$jun_net_income = $jun_gpf - ($junDpr + $junGtl);
				$jul_net_income = $jul_gpf - ($julDpr + $julGtl);
				$aug_net_income = $aug_gpf - ($augDpr + $augGtl);
				$sep_net_income = $sep_gpf - ($sepDpr + $sepGtl);
				$oct_net_income = $oct_gpf - ($octDpr + $octGtl);
				$nov_net_income = $nov_gpf - ($novDpr + $novGtl);
				$dec_net_income = $dec_gpf - ($decDpr + $decGtl);
			?>

			

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
			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Net Income (P/L)</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($jan_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($feb_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($mar_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($apr_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($may_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($jun_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($jul_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($aug_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($sep_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($oct_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($nov_net_income,2); ?></b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><b class="ft-tahoma"><?php echo number_format($dec_net_income,2); ?></b></td>
			</tr>
		</table>
	</div>
</div>

<?php elseif($get_aly == 'Summary Report'): ?>

<div id="section-to-print">
	<h3 class="xlarge ft-tahoma alignct">P&L Statement (YEAR <?php echo $get_year; ?>)</h3>
	<h3 class="large nobold ft-tahoma alignct"><?php echo $get_aly; ?></h3>
	<p>&nbsp;</p>
	<div class="nc-width-100">
		<table cellpadding="5" cellspacing="0">
			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Revenue</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
			</tr>
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma">Booking Sales</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($bk_row[0]['ttRevenue'],2); ?></td>
			</tr>
			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma">Recreation</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($rcr_row[0]['ttRevenue'],2); ?></td>
			</tr>

			<?php

			$total_outlet_revenue = 0;

			if(is_array($get_posstores)):
			foreach($get_posstores as $key => $val):

			include "pos_sql.php";

			?>

			<tr>
				<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['posname']; ?></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($pos_row[0]['ttRevenue'],2); ?></td>
			</tr>

			<?php

			$total_outlet_revenue = $total_outlet_revenue + $pos_row[0]['ttRevenue'];
			
			endforeach;
			endif;

			$grand_total_revenue_smry = $total_outlet_revenue + $bk_row[0]['ttRevenue'] + $rcr_row[0]['ttRevenue'];

			?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Total Rev.</b></td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($grand_total_revenue_smry,2); ?></b></td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Cost of Sales</b></td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>

			<?php

			$cos_sql_smry = "SELECT SUM(order_net_r_amount) AS ATSales FROM stock_item_purchase_order_tbl WHERE order_status IN('Approved') AND receipt_status IN('Received') AND deletedata=0 AND DATE_FORMAT(delivery_date, '%Y') = '{$get_year}'";
							
			$cos_smry = mysqli_data_array('assoc',$cos_sql_smry);

			$gross_profit_smry = $grand_total_revenue_smry - $cos_smry[0]['ATSales'];

			?>
			<tr class="sky-blue-theme">
				<td class="right-pull-5 left-pull-5 ft-tahoma">Goods/Material</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($cos_smry[0]['ATSales'],2); ?></b></td>
			</tr>
			<tr class="grey-theme">
				<td class="right-pull-5 left-pull-5">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>
			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Gross Profit</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($gross_profit_smry,2); ?></b></td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Operating Expenses</b></td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>

			<?php
				if(is_array($exp_accts_list) && count($exp_accts_list) > 0):

					$exp_val_sql=""; $exp_val_list = "";

					$opm_smry=0;

					foreach($exp_accts_list as $key => $val):

						$exp_val2_sql = "SELECT SUM(amount) AS totExp FROM coa_entry_tbl WHERE coa_id={$val['id']} AND yyear IN({$get_year}) AND entry_type IN('Debit')";
						
						$exp_val2_list = mysqli_data_array('assoc',$exp_val2_sql);

						?>
							<tr>
								<td class="right-pull-5 left-pull-5 ft-tahoma"><?php echo $val['account_name']; ?></td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
								<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($exp_val2_list[0]['totExp'],2); ?></td>
							</tr>
						<?php

						$opm_smry = $opm_smry + $exp_val2_list[0]['totExp'];
						
					endforeach;
				endif;


				//for depreciation

				$fass_accts_sql_smry = "SELECT * FROM coa_setup_tbl WHERE account_type IN('Fixed Assets') AND status IN('Active') AND deletedata=0"; $fass_accts_list_smry = mysqli_data_array('assoc',$fass_accts_sql_smry);

				if(is_array($fass_accts_list_smry) && count($fass_accts_list_smry) > 0):

					$fyyear=""; $plf=""; $ypl=""; $mmdr=""; $dateObj="";
					$cass_val_sql=""; $cass_val_list = "";

					$janFA=0; $febFA=0; $marFA=0; $aprFA=0;
					$mayFA=0; $junFA=0; $julFA=0; $augFA=0;
					$sepFA=0; $octFA=0; $novFA=0; $decFA=0;

					$janDpr_smry=0; $febDpr_smry=0; $marDpr_smry=0; $aprDpr_smry=0; $mayDpr_smry=0; $junDpr_smry=0; $julDpr_smry=0; $augDpr_smry=0; $sepDpr_smry=0; $octDpr_smry=0; $novDpr_smry=0; $decDpr_smry=0;

					foreach($fass_accts_list_smry as $key => $val):

						if($val['plf'] > 0) {

							$dateObj = new DateTime($val['plf_date']);
							$fyyear = $dateObj->format('Y');
							
							$plf = $fyyear - $get_year;
							$ypl = $val['ppr'] / $plf;
							
							if($df_month == '01') {
								$janFA = ($ypl / 12) * 1;
							} elseif($df_month == '02') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
							} elseif($df_month == '03') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
							} elseif($df_month == '04') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
							} elseif($df_month == '05') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
							} elseif($df_month == '06') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
							} elseif($df_month == '07') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
							} elseif($df_month == '08') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
								$augFA = ($ypl / 12) * 8;
							} elseif($df_month == '09') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
								$augFA = ($ypl / 12) * 8;
								$sepFA = ($ypl / 12) * 9;
							} elseif($df_month == '10') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
								$augFA = ($ypl / 12) * 8;
								$sepFA = ($ypl / 12) * 9;
								$octFA = ($ypl / 12) * 10;
							} elseif($df_month == '11') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
								$augFA = ($ypl / 12) * 8;
								$sepFA = ($ypl / 12) * 9;
								$octFA = ($ypl / 12) * 10;
								$novFA = ($ypl / 12) * 11;
							} elseif($df_month == '12') {
								$janFA = ($ypl / 12) * 1;
								$febFA = ($ypl / 12) * 2;
								$marFA = ($ypl / 12) * 3;
								$aprFA = ($ypl / 12) * 4;
								$mayFA = ($ypl / 12) * 5;
								$junFA = ($ypl / 12) * 6;
								$julFA = ($ypl / 12) * 7;
								$augFA = ($ypl / 12) * 8;
								$sepFA = ($ypl / 12) * 9;
								$octFA = ($ypl / 12) * 10;
								$novFA = ($ypl / 12) * 11;
								$decFA = $ypl;
							}

						} else {

							$janFA = 0;
							$febFA = 0;
							$marFA = 0;
							$aprFA = 0;
							$mayFA = 0;
							$junFA = 0;
							$julFA = 0;
							$augFA = 0;
							$sepFA = 0;
							$octFA = 0;
							$novFA = 0;
							$decFA = 0;
						}
						
						$janDpr_smry = $janDpr_smry + $janFA;
						$febDpr_smry = $febDpr_smry + $febFA;
						$marDpr_smry = $marDpr_smry + $marFA;
						$aprDpr_smry = $aprDpr_smry + $aprFA;
						$mayDpr_smry = $mayDpr_smry + $mayFA;
						$junDpr_smry = $junDpr_smry + $junFA;
						$julDpr_smry = $julDpr_smry + $julFA;
						$augDpr_smry = $augDpr_smry + $augFA;
						$sepDpr_smry = $sepDpr_smry + $sepFA;
						$octDpr_smry = $octDpr_smry + $octFA;
						$novDpr_smry = $novDpr_smry + $novFA;
						$decDpr_smry = $decDpr_smry + $decFA;
						
					endforeach;
				endif;

				$total_depreciation = $janDpr_smry + $febDpr_smry + $marDpr_smry + $aprDpr_smry + $mayDpr_smry + $junDpr_smry + $julDpr_smry + $augDpr_smry + $sepDpr_smry + $octDpr_smry + $novDpr_smry + $decDpr_smry;

			?>

			<tr>
				<td class="sky-blue-theme right-pull-5 left-pull-5 ft-tahoma">Depreciation</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct"><?php echo number_format($total_depreciation,2); ?></td>
			</tr>

			<?php $total_exps = $opm_smry + $total_depreciation; ?>

			<tr class="yellow-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Total Exp.</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($total_exps,2); ?></b></td>
			</tr>

			<tr>
				<td class="right-pull-5 left-pull-5">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct">&nbsp;</td>
			</tr>

			<?php $net_worth = $gross_profit_smry - ($opm_smry + $total_depreciation); ?>

			<tr class="sky-blue-theme">
				<td class="right-pull-5 left-pull-5"><b class="ft-tahoma">Net Income (P/L)</b></td>
				<td class="right-pull-5 left-pull-5 ft-tahoma alignct">&nbsp;</td>
				<td class="right-pull-5 left-pull-5 alignct"><b class="ft-tahoma"><?php echo number_format($net_worth,2); ?></b></td>
			</tr>

		</table>
	</div>
</div>

<?php endif; ?>