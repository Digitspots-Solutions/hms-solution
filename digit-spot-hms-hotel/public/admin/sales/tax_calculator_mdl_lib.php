<?php $smdl = "sales"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: here you can calculate your tax charges using real-time calculator
 	</span>
 	<span class="ln-display-box float-right">
		<h3 class="large nobold default-text-font-bold">Tax Calculator</h3>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<div class="block-element" align="center">
	<div class="fx-width-40 alignlt">
		<form action="" method="post" autocomplete="off">
			<div class="xform bottom-push-7 alignlt">
				<small class="block-element bottom-push-5 dark-grey-font default-text-font-bold">Amount</small>
				<input type="number" name="fieldset1" id="fieldset1" placeholder="Enter amount?" class="nopads no-back-black" required="required" onkeyup="rxt()">
			</div>
			<div class="xform bottom-push-7 alignlt">
				<small class="block-element bottom-push-5 dark-grey-font default-text-font-bold">Tax (%)</small>
				<input type="number" name="fieldset2" id="fieldset2" placeholder="Enter tax percentage?" class="nopads no-back-black" required="required" onkeyup="rxt()" title="Do not include % sign in your input">
			</div>
			<div class="xform bottom-push-7 slate-blue-theme white-font alignlt">
				<small class="block-element bottom-push-5">Result:</small>
				<h1 id="rxt" class="large">0.00</h1>
			</div>
		</form>
	</div>
</div>

<script>

	function rxt() {
		var amt,tx,result,dspl;
		amt = document.getElementById('fieldset1');
		tx = document.getElementById('fieldset2');
		dspl = document.getElementById('rxt');

		if((tx && tx.value > 0) && (amt && amt.value > 0)) {
			result = (tx.value / 100) * amt.value;
			dspl.innerHTML = numberFormat(result);
		}
	}

</script>