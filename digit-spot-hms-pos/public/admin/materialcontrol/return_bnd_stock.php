<?php
	
	#create table
	createDatabasetable($var_tbl_152);
	createDatabasetable($var_tbl_157);

	$tbl = $tbL156;
	$tblr = $tbL157;
	$xtbl = $mtbL5;

	#get all applcable workflows
	$itworkFlow = getjob_workflow(12);

?>

<div class="pads30" align="left">
	<div class="bottom-push-30"><a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a></div>

	<div class="fx-width-60 margin-auto-ct bottom-push-30">
		<h3 class="large nobold dark-grey-font alignct">To return any item as bad/damage, select the checkbox and click on <u>return as bad</u> button. You may need to wait for approval to complete the move</h3>
	</div>

	<div class="pads30 box-border-thick sml-rounded-button bottom-push-50" align="left">
		
		<span class="float-right left-pull-10"><a href="javascript:void(0)" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button ft-xsml-size nunito-semibold" onclick="returnBnD()">Return as bad</a></span>

		<h3 class="large nobold nunito-bold">List of items transferred as bad or damage</h3><br>
		<div class="x-scroll">
			<div class="nc-width-100">
				<?php
					
					$queryset = "deletedata=0 AND bnd=0 AND tagged_name='BnD' AND transfer_status='Transfer Completed'";

					$keys = array(
						"itemid"=>"(fx)item",
						"qty_transfer"=>"quantity",
						"uom"=>"uom",
						"datelogged"=>"(df)date modified"
					);

					$format = array(
						"grid",
						"form-ctrl",
						"allow-check-for-isbad"
					);

					$result = data_row_dpl($tbl,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $result;
				?>
			</div>
		</div>
	</div>

	<div class="pads30 box-border-thick sml-rounded-button bottom-push-50" align="left">
		<h3 class="large nobold nunito-bold">List of bad or damage items</h3><br>
		<div class="x-scroll">
			<div class="nc-width-100">
				<?php
					
					$queryset = "deletedata=0 AND bnd_status='Approved'";

					$keys = array(
						"itemid"=>"(fx)item",
						"stock"=>"quantity",
						"uom"=>"uom",
						"total_cost"=>"(nf)cost",
						"datelogged"=>"(df)date modified"
					);

					$format = array(
						"grid"
					);

					$result = data_row_dpl($tblr,$queryset,$keys,$format,$startnumbr,$extdata);
					echo $result;
				?>
			</div>
		</div>
	</div>
</div>


<div id="tktBox" class="xfadein noshow motion" align="center">
	<div class="cs-height-150"></div>
	<div id="rBox" class="fx-width-90 cs-height-500 white-theme xsml-rounded-button alignlt noscroll"></div>
</div>

<div id="fbox"></div>

<script>

	function jsxView(id) {

		datastring.process = "view";
		datastring.tip = "Product / item stock general analysis";
		
		xform('nohtmlform');

		wparams.tbl = "<?php echo $xtbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		suggestions.splice(0,suggestions.length);
		arrygets.splice(0,arrygets.length);

		idget_val('<?php echo $xtbl; ?>',id,'id','item','scalar');
		
		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var stopAfter = setInterval(() => {
				if(document.getElementById('fbox-content')) {
					writeObjheader('fbox-content','<h3 class="large nobold alignct">Previewing Content</h3>');
					clearInterval(stopAfter);

					var htmlresult, ajaxresult = JSON.parse(response);
					var arry = ajaxresult.datastring, data = arry[0];

					//var item_category='', item_sub_category='', item_group='';

					setTimeout(() => { idget_val('<?php echo $mtbL2; ?>',data.categoryid,'id','category','scalar'); },1000);
					setTimeout(() => { idget_val('<?php echo $mtbL3; ?>',data.subcategoryid,'id','subcategory','scalar'); },2000);
					setTimeout(() => { idget_val('<?php echo $mtbL4; ?>',data.itemgroupid,'id','groupname','scalar'); },3000);

					
					setTimeout(() => { var fbu = {"arryname":"uoms","keys":data.buying_unit}; wgtarrykey(fbu); },500);
					if(data.selling_unit && data.selling_unit > 0) { setTimeout(() => { var fsu = {"arryname":"uoms","keys":data.selling_unit}; wgtarrykey(fsu); },1500); }

					var expiry;

					if(data.isexpire == 'No') { expiry = "Never Expire"; }
					else if(data.isexpire == 'Yes') { expiry = data.expiry_date; }

					setTimeout(() => {
					
						htmlresult = '';
						htmlresult += '<h3 class="large nobold default-text-font-bold bottom-pull-7">'+suggestions[0]+'</h3>';
						htmlresult += '<div class="sided-box bottom-push-30">';
						htmlresult += '<ul>';
						htmlresult += '<li id="tab-1" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor" onclick="changetab(this.id)">Overview</li>';
						htmlresult += '<li id="tab-2" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Supplier</li>';
						htmlresult += '<li id="tab-3" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Purchase Statistics</li>';
						htmlresult += '<li id="tab-4" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Variation</li>';
						htmlresult += '<li id="tab-5" class="top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor" onclick="changetab(this.id)">Stock Movement</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-1" class="sided-box xfadeout motion-x">';
						htmlresult += '<ul>';
						htmlresult += '<li class="nc-width-35 right-pull-30 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[1]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Sub Category</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+suggestions[2]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Group</h4>';
						htmlresult += '<h4 class="xlarge nobold">N/A</h4>';
						//htmlresult += '<h4 class="xlarge nobold">'+suggestions[3]+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-35 right-pull-30 left-pull-20 box-border-thick-right">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Buying Unit</h4>';
						htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_bu+' Pieces in 1 '+arrygets[0]+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Selling Unit</h4>';
						if(arrygets[1] != 'undefined') {
							htmlresult += '<h4 class="xlarge nobold nomargin">'+arrygets[1]+'</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom black-font">'+data.noofpiece_su+' '+arrygets[1]+' in 1 piece</h4>';
							htmlresult += '<h4 class="large nobold bottom-pull-7 box-border-thick-bottom">Formula: '+data.calc_formular+'</h4>';
						} else {
							htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">N/A</h4>';
						}
						htmlresult += '</li>';
						htmlresult += '<li class="nc-width-30 left-pull-20">';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Expiry Date</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+expiry+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Minimum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.minimum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Maximum Stock</h4>';
						htmlresult += '<h4 class="xlarge nobold bottom-pull-7 box-border-thick-bottom">'+data.maximum_stock+'</h4>';
						htmlresult += '<h4 class="large nobold dark-grey-font nomargin">Cost Center Item</h4>';
						htmlresult += '<h4 class="xlarge nobold">'+data.iscost_center+'</h4>';
						htmlresult += '</li>';
						htmlresult += '<li></li>';
						htmlresult += '</ul>';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-2" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-3" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-4" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						htmlresult += '<div id="in-tab-5" class="noshow xfadein motion-x">';
						htmlresult += '</div>';

						writeObjheader('fbox-content',htmlresult);

					},4000);
				}
			},1000);
		}
	}

	function changetab(id) {
		var i;
		for(i=1; i <= 5; i++) {
			if('tab-'+i == id) {
				chgclass(id,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 blue-white-state anchor');
				chgclass('in-'+id,'sided-box xfadeout motion-x');
			} else {
				chgclass('tab-'+i,'top-pull-10 right-pull-20 bottom-pull-10 left-pull-20 white-grey-state anchor');
				chgclass('in-tab-'+i,'noshow xfadein motion-x');
			}
		}

		if(id == 'tab-2' || id == 'tab-3' || id == 'tab-4' || id == 'tab-5') {
			writeObjheader('in-'+id,'<h4 class="large nobold dark-grey-font alignct">Looking for record..</h4>');
		}

		if(id == 'tab-2') {

		}
	}


	function returnBnD() {
		var inp = document.createElement('input');
		inp.type = "hidden";
		inp.name = "workflow";
		inp.id = "workflow";
		inp.value = 0;

		document.getElementById('datasheet').appendChild(inp);

		var isworkflow = '<?php echo $itworkFlow; ?>';
		
		chgclass('tktBox','fx-position-stick fscr zind-2 txp8-white noscroll xfadeout motion');
		chgclass('rBox','fx-width-35 pads30 white-theme obj-light-shadow xsml-rounded-button alignlt cs-margin-top-60 noscroll');

		vhtml = '';
		vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jbtrigger(event)">';
		vhtml += '<div class="pads10 alignlt">';
		vhtml += '<label>Select your approval workflow?</label>';
		vhtml += '<select name="workflowx" id="workflowx" class="nopads no-back-black">'+isworkflow+'</select>';
		vhtml += '</div>';
		vhtml += '<div class="top-pull-30 motion">';
		vhtml += '<input type="submit" id="jobworkflowbutton" name="jobworkflowbutton" value="Accept & Apply" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
		vhtml += '<p class="top-pull-15 alignct"><a href="javascript://" class="black-font" title="Close" onclick="cancelPrSign()">Cancel x</a></p>';
		vhtml += '</div>';
		vhtml += '</form>';
		
		writeObjheader('rBox',vhtml);
		parent.document.getElementById('workspace').scrollTop = 0;
	}

	function cancelPrSign() {
		chgclass('tktBox','xfadein noshow motion');
		chgclass('rBox','fx-width-80 white-theme xsml-rounded-button alignlt cs-margin-top-100 noscroll');
		writeObjheader('rBox','');
	}

	function jbtrigger(e) {
		e.preventDefault();
		if(document.getElementById('workflow')) {
			document.getElementById('workflow').value = document.getElementById('workflowx').value;
			document.getElementById('ftask').value = 'for-bad-and-damage';
			setTimeout(() => { document.getElementById('datasheet').submit(); },1000);
		} else {
			cancelPrSign();
		}
	}

</script>