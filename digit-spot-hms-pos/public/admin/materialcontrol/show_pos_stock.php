
<div id="tktBox" class="fx-position-stick fscr zind-2 txp5-white noscroll xfadeout motion" align="center">
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button pads30 alignlt cs-margin-top-100 y-scroll">
		<span class="float-right top-pull-3"><a href="javascript:nth_default()" class="black-font"><b class="mbri-close"></b></a></span>
		<h3 class="large nobold nunito-bold"><?php echo $title; ?></h3><br>

		<h4 class="xlarge nobold black-font alignct"><b class="fas fa-question-circle right-push-5"></b> Note that serviceable stock are not deductable. You may need to check physically</h4><br>

		<div class="x-scroll">
			<div class="cs-width-1000">
				<?php
					
					$tbl = $tbL16;
					$queryset = "storageid={$row} AND storagetype IN('consumable','serviceable') AND deletedata=0";

					$keys = array(
						"categoryid"=>"category",
						"subcategoryid"=>"subcategory",
						"item"=>"item",
						"uom"=>"uom",
						"stockout"=>"stock out",
						"balance"=>"stock bal."
					);

					$format = array(
						"grid"
					);

					$result = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $result;
				?>
			</div>
		</div>

	</div>
</div>

<?php


?>