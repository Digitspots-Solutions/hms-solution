<?php

	$tbl = $mtbL2;

	#auto populate default categories

	if(is_array($outlet_category_type)) {
		foreach($outlet_category_type as $key => $val) {
			$pst_query = "program_id={$key}";
			$pst_field = "program_id={$key},category='{$val}'";
			mysqli_data_insert($tbl,$pst_field,$pst_query);
		}
	}

	#--------------------------------------------------------------------

	//check if data exist in expense category table
	$query_type = "deletedata=0";
	$istype = mysqli_data_exist($tbl,$query_type);

?>

<div class="pads30">
	<div class="box-border-thick xsml-rounded-button alignlt">
		<ul class="nolist">
			<li class="">
				<div class="pads30">

					<div class="block-element bottom-push-30">
					 	<span class="ln-display-box float-left">
							<a href="<?php echo $ths_page; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
							&nbsp; Note: create new item category by clicking <u>new category</u> button
					 	</span>
					 	<span class="ln-display-box float-right">
							<a href="javascript:void(0)" class="blue-font box-border-thick rounded-button top-pull-10 right-pull-20 bottom-pull-10 left-pull-20" onclick="jsForm()">New Category <b class="fa-arrow-right left-push-5"></b></a>
						</span>
						<span class="block-element new-line-space">
							<!-- clear line -->
						</span>
					</div>

					<?php
						if($istype['isdata'] == true) {

							$isSql = "SELECT * FROM {$tbl} WHERE deletedata=0";
							$wgttype = idget_data($isSql);

							$numbr = 1;

							if(is_array($wgttype) && count($wgttype)) {
								foreach($wgttype as $key => $val) {
									
									?>
										<span id="tlnk<?php echo $numbr; ?>" class="fx-display-box fx-float-left pads10 right-push-5 bottom-push-10 royal-blue-font anchor" onclick="xfa(<?php echo $numbr; ?>)" lang="off"><h3 class="large nobold nomargin"><?php echo $val['category']; ?><b class="fa-arrow-right left-push-10"></b></h3>

											<div id="dr-<?php echo $numbr; ?>" class="noshow xsml-rounded-button xfadein motion-x tLnk" align="left">
												<ul class="nolist">
													<li class="bottom-push-7 dark-black-font" onclick="openUp(<?php echo $val['id']; ?>)">Detail</li>
													<?php
														if($val['isdefault'] == 'No') {
															?>
																<li class="bottom-push-5 dark-black-font" onclick="popto(<?php echo $val['id']; ?>)">Edit</li>
															<?php
																if(isset($allowMcDelete) && $allowMcDelete == 200) {
																	?>
																		<li class="bottom-push-5 dark-black-font" onclick="ddel(<?php echo $val['id']; ?>,'<?php echo $tbl; ?>')">Delete</li>
																	<?php
																}
														}
													?>
												</ul>
											</div>
										</span>

									<?php

									$numbr += 1;
								}
							}

							?>
								<span class="block-element new-line-space"></span>
							<?php

						} else {
						
							?>
								<div class="cs-height-50"></div>
								<div class="block-element" align="center">
									<div class="light-steel-blue-theme cs-width-80 cs-height-80 rounded-element bottom-push-30 alignct noscroll">
										<span class="block-element nc-height-35"></span>
										<b class="mbri-pages ft-Lsize nobold"></b>
									</div>
									<h3 class="xlarge nobold dark-grey-font">No records found</h3>
								</div>
							<?php
						}
					?>

				</div>
			</li>
		</ul>
	</div>
</div>

<div id="fbox"></div>

<script>

	function jsForm() {

		datastring.process = "insert";
		datastring.tip = "Creating new item category";
		datastring.element = "html/item_category.html";
		
		xform('htmlform');
	}

	function xfa(id) {
		var li = document.getElementById('tlnk'+id);
		var menu = 'dr-'+id;

		if(li.lang == 'off') {
			li.lang = 'on';
			chgclass(menu,'fx-position-flow white-theme box-border-thick obj-light-shadow cs-width-100 top-pull-10 right-pull-20 bottom-pull-7 left-pull-20 xsml-rounded-button xfadeout motion-x tLnk');
		} else if(li.lang == 'on') {
			li.lang = 'off';
			chgclass(menu,'noshow pads15 xsml-rounded-button xfadein motion-x tLnk');
		}

		var j, oli, nof;

		oli = document.getElementsByClassName('tLnk');
		nof = oli.length;

		for(j=0; j<nof; j++) {
			var tl = j + 1;
			if('tlnk'+id !== 'tlnk'+tl) {
				document.getElementById('tlnk'+tl).lang = 'off';
				chgclass('dr-'+tl,'noshow pads15 xsml-rounded-button xfadein motion-x tLnk');
			}
		}
	}

	function popto(id) {

		wparams.tbl = "<?php echo $tbl; ?>";
		wparams.key = id;
		wparams.col = "id";

		wgtdata(wgtpop,wparams);

		function wgtpop(response) {
			var vhtml, ajaxresult = JSON.parse(response);

			vhtml = '<div id="dbox" class="fx-position-stick fscr zind-1 motion pads50 y-scroll" align="center">';
			vhtml += '<div class="cs-height-100"></div>';
			vhtml += '<div class="fx-width-40 white-theme xsml-rounded-button xhover-shadow">';
			
			vhtml += '<p class="pads20 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="jsClean()"><b class="mbri-close"></b></a></p>';
			vhtml += '<div class="right-pull-30 bottom-pull-10 left-pull-30 box-border-thick-bottom">';
			vhtml += '<h3 class="xlarge nobold nomargin nunito-bold alignlt">'+ajaxresult.datastring[0].category+'</h3>';
			vhtml += '</div>';
			vhtml += '<div class="block-element noscroll">';
			vhtml += '<div id="e-carousel" class="motion-x" style="width: 200%">';
			vhtml += '<span class="float-left nc-width-50 pads20">';
			vhtml += '<form action="" method="post" autocomplete="off" onsubmit="jsLoadButton(3)">';
			vhtml += '<input type="hidden" name="uri" value="edit-table-record">';
			vhtml += '<div class="box-border-thick-bottom pads15 alignlt">';
			vhtml += '<label>Change to:</label>';
			vhtml += '<input type="text" name="wgtcol" id="wgtcol" placeholder="Enter new name here" class="nopads no-back-black"  onkeypress="titleCase(this.value,this.id)" required>';
			vhtml += '</div>';
			vhtml += '<div class="top-pull-30 motion">';
			vhtml += '<input type="hidden" name="datau" id="datau" value="'+id+'">';
			vhtml += '<input type="hidden" name="tablecolumn" id="tablecolumn" value="category">';
			vhtml += '<input type="hidden" name="tablename" id="tablename" value="'+wparams.tbl+'">';
			vhtml += '<input type="submit" id="updatebutton" name="updatebutton" value="Save Update" class="nc-width-100 dark-black-white-state top-pull-15 bottom-pull-15 nunito-semibold rounded-button anchor ft-mini-size letter-spacing-2">';
			vhtml += '</div>';
			vhtml += '</form>';
			vhtml += '</span>';
			vhtml += '<span class="float-left nc-width-50">';
			vhtml += '&nbsp;';
			vhtml += '</span>';
			vhtml += '<span class="block-element new-line-space">';
			vhtml += '</span>';
			vhtml += '</div>';
			vhtml += '</div>';
			
			vhtml += '</div>';
			vhtml += '</div>';

			writeObjheader('fbox',vhtml);
		}
	}

	function openUp(id) {

		var xtbl = "<?php echo $mtbL2; ?> t1", xxtbl = "<?php echo $mtbL3; ?> t2";

		sqldatastring.sql = "SELECT t1.category,t2.subcategory FROM "+xtbl+", "+xxtbl+" WHERE t1.id="+id+" AND t2.categoryid="+id;
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var vhtml, ajaxresult = JSON.parse(response);
			var i,wdata = ajaxresult.datastring;

			vhtml = '<div id="dbox" class="fx-position-stick fscr zind-1 motion pads50 y-scroll" align="center">';
			vhtml += '<div class="cs-height-100"></div>';
			vhtml += '<div class="fx-width-45 white-theme xsml-rounded-button xhover-shadow">';
			vhtml += '<p class="pads20 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="jsClean()"><b class="mbri-close"></b></a></p>';
			vhtml += '<div class="right-pull-30 bottom-pull-10 left-pull-30 box-border-thick-bottom">';
			vhtml += '<h3 class="xlarge nobold nomargin nunito-bold alignlt">'+wdata[0].category+' Category List</h3>';
			vhtml += '</div>';
			vhtml += '<div class="block-element noscroll">';
			vhtml += '<div id="e-carousel" class="motion-x" style="width: 200%">';
			vhtml += '<span class="float-left nc-width-50 pads20">';
			
			vhtml += '<ul class="nolist">';

			for(i=0; i < wdata.length; i++) {
				vhtml += '<li class="ln-display-box float-left right-push-15 bottom-push-15 dark-black-font"><b class="mbri-success right-push-5"></b>'+wdata[i].subcategory+'</li>';
			}

			vhtml += '<li class="block-element new-line-space"></li>';
			vhtml += '</ul>';

			vhtml += '</span>';
			vhtml += '<span class="float-left nc-width-50">';
			vhtml += '&nbsp;';
			vhtml += '</span>';
			vhtml += '<span class="block-element new-line-space">';
			vhtml += '</span>';
			vhtml += '</div>';
			vhtml += '</div>';
			
			vhtml += '</div>';
			vhtml += '</div>';

			writeObjheader('fbox',vhtml);
		}
	}

</script>