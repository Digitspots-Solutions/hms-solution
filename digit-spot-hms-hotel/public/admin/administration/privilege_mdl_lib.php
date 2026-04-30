<?php
$smdl = "administration"; $logs = escape_data($_GET['logs']); $role = escape_data($_GET['role']);

$post_result = '';
$htmlresult = '';

if(isset($role) && $role >= 1)
{
	$role_key = array("id"=>$role);
	$role_data = mysqli_get_schema_data($tbL4,'role',$role_key);
	
	?>

	<div class="block-element bottom-push-30">
	 	<span class="ln-display-box float-left">
			<a href="?logs=<?php echo $logs; ?>&role=<?php echo $role; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
			&nbsp; Note: Control user's accessibility to the platform by adding or updating this role privileges. Remove accessibility by checking the boxes and click revoke button
	 	</span>
	 	<span class="ln-display-box float-right">
			&nbsp;
		</span>
		<span class="block-element new-line-space">
			<!-- clear line -->
		</span>

		<br><h2 class="large blue-font"><b class="fa-share nobold"></b> &nbsp; <?php echo $role_data[0]; ?> Privileges</h2>
	</div>

	<?php

	if(isset($_POST['submitbutton']) && isset($_POST['arole'])) {
		
		createDatabasetable($var_tbl_9); //create a table for this post

		$insert=0; $datas="";
			
		foreach($_POST['arole'] as $privileges) {
			
			$datas = explode('-', $privileges);

			$urole_key = array("roleid"=>$_POST['roleid'],"classid"=>$datas[0],"name"=>$datas[1]);
			$data_property = array("roleid"=>$_POST['roleid'],"classid"=>$datas[0],"name"=>$datas[1]);
			$isinsert = mysqli_data_insert($tbL5,$data_property,$urole_key);

			if(isset($isinsert) && $isinsert == 2) {
				$insert += 1;
			}
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($insert) && $insert >= 1) {
			
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Add privileges to role: $role_data[0]","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New privilege was added successfully</span>';

		} else {
			$post_result .= '<span class="red-font">No privilege added</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['removebutton']) && isset($_POST['arole'])) {
		
		$delete=0; $datas=""; $roleid=""; $classid=""; $name="";
			
		foreach($_POST['arole'] as $privileges) {
			
			$datas = explode('-', $privileges);

			$roleid .= $_POST['roleid'].",";
			$classid .= $datas[0].",";
			$name .= $datas[1].",";

			//$urole_key = array("roleid"=>$_POST['roleid'],"classid"=>$datas[0],"name"=>$datas[1]);
		}

		$roleid = substr_replace($roleid,'',-1,1);
		$classid = substr_replace($classid,'',-1,1);
		$name = substr_replace($name,'',-1,1);

		$addQuery = " AND roleid IN({$roleid}) AND classid IN({$classid}) AND name NOT IN({$name})";
		$urole_key = array("deletedata"=>0);
		$isdelete = trash_record($tbL5,$urole_key);
			
		if(isset($isdelete) && $isdelete == 2) {
			$delete = 1;
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($delete) && $delete >= 1) {
			
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Remove privileges from role: {$role_data[0]}","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">Privilege was removed successfully</span>';

		} else {
			$post_result .= '<span class="red-font">No privilege added</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

	#-----------------------------------------------------------------------------------------------------------------

	?>

	<form action="" method="post" onsubmit="objDisplay('processbar')" autocomplete="off">
	<input type="hidden" name="roleid" value="<?php echo $role; ?>">
	<div class="box-border-thick sml-rounded-button bottom-push-20 noscroll white-theme">
		<div class="block-element grey-theme top-pull-15 right-pull-20 bottom-pull-15 left-pull-20">
			<input type="checkbox" id="group0" alt="app-modules" lang="u" onClick="checkallboxes('group0')"> &nbsp; <b>Application Modules</b> (Menu)
		</div>
		<div class="block-element top-pull-20 right-pull-20 bottom-pull-20 left-pull-20">
			<?php
			
			$checker="";

			foreach ($application_module as $header_menu_key => $header_menu_value)
			{
				$privilege_checker_key = array("roleid"=>$role,"classid"=>0,"name"=>$header_menu_value);
				$privilege_checker = mysqli_get_schema_data($tbL5,'id',$privilege_checker_key);

				if(isset($privilege_checker[0]) && $privilege_checker[0] >= 1) { $checker = " checked"; }
				else { $checker = ""; }

				?>
				<span class="ln-display-box float-left nc-width-30 right-push-30 bottom-push-20">
				<input type="checkbox" name="arole[]" id="app-modules<?php echo $header_menu_value; ?>" value="0-<?php echo $header_menu_value; ?>"<?php echo $checker; ?>> &nbsp; <small>Grant Access to <u><?php echo $header_menu_key; ?></u></small>
				</span>
				<?php
			}
			?>
			<span class="block-element new-line-space"></span>
			<input type="hidden" id="app-modules" value="9">
		</div>
	</div>
	
	<?php

	#------------------------------------------------------------------------------------------------------------------------------------------
		
		#application module menu list
		#not include point of sale
	
		foreach ($application_module as $list_menu_key => $list_menu_value)
		{
			//if($list_menu_value != 8)
			//{
				?>
					<div class="box-border-thick sml-rounded-button bottom-push-20 noscroll white-theme">
						<div class="block-element grey-theme top-pull-15 right-pull-20 bottom-pull-15 left-pull-20">
							<input type="checkbox" id="group888<?php echo $list_menu_value; ?>" alt="<?php echo str_replace(' ','-',strtolower($list_menu_key)); ?>" lang="u" onClick="checkallboxes('group888<?php echo $list_menu_value; ?>')"> &nbsp; <b><?php echo $list_menu_key; ?></b> (Modules)
						</div>
						<div class="block-element top-pull-20 right-pull-20 bottom-pull-20 left-pull-20">
							
							<?php
				
								$checker=""; $mm_numbr=0;

								$mmkey = array("moduleid"=>$list_menu_value,"status"=>"Active");
								$mm = mysqli_data_fetch($tbL10,'id,category',$mmkey,'array');

								if(is_array($mm))
								{
									foreach ($mm as $mm_key => $mm_value)
									{
										$mm_numbr += 1;

										$privilege_checker_key = array("roleid"=>$role,"classid"=>888,"name"=>$mm_value["id"]);
										$privilege_checker = mysqli_get_schema_data($tbL5,'id',$privilege_checker_key);

										if(isset($privilege_checker[0]) && $privilege_checker[0] >= 1) { $checker = " checked"; }
										else { $checker = ""; }

										?>
										<span class="ln-display-box float-left nc-width-30 right-push-30 bottom-push-20">
										<input type="checkbox" name="arole[]" id="<?php echo str_replace(' ','-',strtolower($list_menu_key)).$mm_numbr; ?>" value="888-<?php echo $mm_value["id"]; ?>"<?php echo $checker; ?>> &nbsp; <small>Grant Access to <u><?php echo $mm_value["category"]; ?></u></small>
										</span>
										<?php
									}

									?>
									<span class="block-element new-line-space"></span>
									<input type="hidden" id="<?php echo str_replace(' ','-',strtolower($list_menu_key)); ?>" value="<?php echo $mm_numbr; ?>">
									<?php
								}

							?>

						</div>
					</div>
				<?php
			//}
		}

		#------------------------------------------------------------------------------------------------------------------------------------------

	?>
		<div class="box-border-thick sml-rounded-button bottom-push-20 noscroll white-theme">
			<div class="block-element grey-theme top-pull-15 right-pull-20 bottom-pull-15 left-pull-20">
				<input type="checkbox" id="group999" alt="xpos" lang="u" onClick="checkallboxes('group999')"> &nbsp; <b>Point of Sales</b> (Created Stores)
			</div>
			<div class="block-element top-pull-20 right-pull-20 bottom-pull-20 left-pull-20">
				<?php
				
				$checker=""; $pst_numbr=0;

				$pstkey = array("deletedata"=>0,"status"=>"Active");
				$pst = mysqli_data_fetch($tbL14,'id,posname',$pstkey,'array');

				if(is_array($pst))
				{
					foreach ($pst as $pst_key => $pst_value)
					{
						$pst_numbr += 1;

						$privilege_checker_key = array("roleid"=>$role,"classid"=>999,"name"=>$pst_value["id"]);
						$privilege_checker = mysqli_get_schema_data($tbL5,'id',$privilege_checker_key);

						if(isset($privilege_checker[0]) && $privilege_checker[0] >= 1) { $checker = " checked"; }
						else { $checker = ""; }

						?>
						<span class="ln-display-box float-left nc-width-30 right-push-30 bottom-push-20">
						<input type="checkbox" name="arole[]" id="xpos<?php echo $pst_value["id"]; ?>" value="999-<?php echo $pst_value["id"]; ?>"<?php echo $checker; ?>> &nbsp; <small>Grant Access to <u><?php echo $pst_value["posname"]; ?></u></small>
						</span>
						<?php
					}
					?>
					<span class="block-element new-line-space"></span>
					<input type="hidden" id="xpos" value="<?php echo $pst_numbr; ?>">
					<?php
				}
				?>
			</div>
		</div>

	<?php

	#------------------------------------------------------------------------------------------------------------------------------------------

	$additionalQuery = " GROUP BY categoryid";
	$dataproperty = "id,categoryid,name";
	$constrain = "";
	$row = "array";

	$listlib = mysqli_data_fetch($tbL11,$dataproperty,$constrain,$row);

	if(is_array($listlib))
	{
		$category=""; $categoryid=""; $module_select="";

		foreach ($listlib as $lib_key => $lib_value) {
			
			$ckey = array("id"=>$lib_value["categoryid"]);
			$category_data = mysqli_get_schema_data($tbL10,'category,moduleid',$ckey);
			$category=$category_data[0];
			$categoryid=str_replace(' ','-',strtolower($category_data[0]));

			$module_select=appmodule($category_data[1]);

			?>
				<div class="box-border-thick sml-rounded-button bottom-push-20 noscroll white-theme">
					<div class="block-element grey-theme top-pull-15 right-pull-20 bottom-pull-15 left-pull-20">
						<input type="checkbox" id="group<?php echo $lib_value['categoryid']; ?>" alt="<?php echo $categoryid; ?>" lang="u" onClick="checkallboxes('group<?php echo $lib_value['categoryid']; ?>')"> &nbsp; <b><?php echo $category; ?></b> (<?php echo $module_select; ?>)
					</div>
					<div class="block-element top-pull-20 right-pull-20 bottom-pull-20 left-pull-20">
						<?php
							$additionalQuery = "";
							$categorykey = array("categoryid"=>$lib_value["categoryid"]);
							$listclib = mysqli_data_fetch($tbL11,'id,name,categoryid',$categorykey,'array');

							$r=0; $checkers="";

							foreach ($listclib as $clib_key => $clib_value) {
								
								$r += 1;

								$privilege_checker_keys = array("roleid"=>$role,"classid"=>$clib_value["categoryid"],"name"=>$clib_value["id"]);
								$privilege_checkers = mysqli_get_schema_data($tbL5,'id',$privilege_checker_keys);

								if(isset($privilege_checkers[0]) && $privilege_checkers[0] >= 1) { $checkers = " checked"; }
								else { $checkers = ""; }

								?>
									<span class="ln-display-box float-left nc-width-30 right-push-30 bottom-push-20">
										<div class="ln-display-box float-left nc-width-10 alignct">
											<input type="checkbox" name="arole[]" id="<?php echo $categoryid.$r; ?>" value="<?php echo $clib_value["categoryid"].'-'.$clib_value["id"]; ?>"<?php echo $checkers; ?>>
										</div>
										<div class="ln-display-box float-right nc-width-80">
											<small>Grant Access to <u><?php echo $clib_value["name"]; ?></u></small>
										</div>
										<div class="block-element new-line-space"></div>
									</span>
								<?php
							}
						?>
						<span class="block-element new-line-space"></span>
						<input type="hidden" id="<?php echo $categoryid; ?>" value="<?php echo $r; ?>">
					</div>
				</div>
			<?php
		}
	}

	?>

		<br><br>

		<div class="fx-position-stick btscr zind-3">
			<div class="block-element pads10 dark-black-theme alignct">
				<input type="submit" name="submitbutton" value="Add or Update Privilege" class="submit pads10 blue-white-state rounded-button nc-width-30"> <input type="submit" name="removebutton" value="Revoke Privilege" class="submit pads10 black-white-state rounded-button nc-width-25 left-push-20">
			</div>
		</div>
	</form>
	<?php
}
?>

<script>
	
function checkallboxes(str)
{
	var chk = document.getElementById(str).lang;
	var tocheck = document.getElementById(str).alt;
	var totalcount = document.getElementById(tocheck).value;
	
	var selector = document.getElementById('checkselector').value;
	var i;
	
	if(chk == 'u') { for (i = 1; i<=totalcount; i++) { document.getElementById(tocheck+i).checked = true; } document.getElementById(str).lang = 'c'; }
	else if(chk == 'c') { for (i = 1; i<=totalcount; i++) { document.getElementById(tocheck+i).checked = false; } document.getElementById(str).lang = 'u'; }
} 

</script>

<input type="hidden" id="checkselector" value="not checker">