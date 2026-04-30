<?php
	
	$posid = $ftoken;

	include "../../includes/class.vars.php";
	include "../../includes/class.function.php";

	if(function_exists('get_pos_cmd')):
	else: include "../../includes/pos_common_data.php";
	endif;

	$posname = idget_data($tbL14,$posid,'posname');

	$startdate = $_SESSION['startdate'];
	$enddate = $_SESSION['enddate'];

	$printed_by = idget_name($userSignedIn,'staffname',$tbL7);
	$printed_date = date('d-m-Y',strtotime($server_get_date)).'. '.$server_get_time;


	$queryset = "isreversed=0 AND deletedata=0 AND posid={$posid} AND status IN('Completed') AND datelogged BETWEEN '{$startdate}' AND '{$enddate}'";

	$tbl = $tbL99;

	$keys = array(
		"itemid"=>"item",
		"qty"=>"(nf)quantity",
		"price"=>"(nf)unit price (&#8358;)",
		"amount"=>"(nf)amount (&#8358;)"
	);

	$format = array(
		"grid",
		"use-base-data"
	);

	$datasheet = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);

?>

<p class="top-pull-10 alignrt">
	<input type="button" value="Print" class="anchor" onclick="window.print()">
</p>

<div id="section-to-print" align="center">
	
	<div class="cs-width-500 alignct">
		<h2 class="large nobold default-text-font-bold nomargin"><?php echo _LONG_NAME; ?></h2>
		<h3 class="large nobold nomargin">&mdash; <?php echo strtoupper($posname); ?> &mdash;</h3><br>
		<h4 class="large nobold">Printed By: <?php echo $printed_by; ?> On <?php echo $printed_date; ?></h4><br>
	</div>

	<br><br>

	<?php echo $datasheet; ?>

</div>