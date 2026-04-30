<?php
include "includes/initialize_session.php";
include "includes/config.php";


#used sessions
$userSignedIn = $_SESSION['authenticate_id'];
$appPageSid = $_SESSION['page_sid'];
$appDataSid = $_SESSION['data_sid'];

include "../../admin/program_sequence_numbers.php";
include "mc_operation_privilege.php";
include "../../../includes/uom.php";
include "../../../includes/hotel_profile_alt.php";

define ("_LONG_NAME",$hotel_name);

#create table
createDatabasetable($var_tbl_155);

#load new program numbers
$pst_query=""; $pst_field="";
foreach($app_sequencials as $name => $no) {
	$pst_query = "app_name='{$name}'";
	$pst_field = "app_name='{$name}',start_number={$no}";
	mysqli_data_insert($tbL155,$pst_field,$pst_query);
}

$outlet_category_type = array(
	1=>"Food",
	2=>"Beverage",
	3=>"Others"
);

?>

<script type="text/javascript" src="css3.0/flexcroll.js"></script>
<link rel="stylesheet" href="css3.0/default.css"/>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jspath.js"></script>
<script src="js/jsfx.js"></script>
<script src="js/index.js"></script>
<script src="js/all.js"></script>


<div class="block-element" align="center">
	<div class="block-dkt-show cs-height-100"></div>
	<?php

		$noaccess="";
		$islogfile=0; $logfile_msg="";
		$saynotify=0; $notifytype=""; $post_header=""; $post_message="";
		$postresult=""; $htmlresult="";

		include "postforms.php";
		
		$logs=""; $tag=""; $ths_page="";

		if(isset($_GET['logs']) && !empty($_GET['logs'])) {
			
			$logs = $_GET['logs']; $tag = $_GET['tag'];
			$ths_page = $_SERVER['PHP_SELF'].'?logs='.$logs;
			$wr_file = str_replace('/','',strtolower($logs));
			$wr_file = str_replace(' ','_',$wr_file);
			$wgt_file = str_replace('__','_',$wr_file);
			$openfile = $wgt_file.'.php'; $ths_file_dir = $openfile;

			if(!file_exists($ths_file_dir)) { fopen($ths_file_dir,'w'); }
			include $openfile;


			##pop notifications

			if(isset($saynotify) && $saynotify >= 1) {
			
				?>
					<div id="notifybox" class="noshow fx-position-stick zind-1 motion tpscr" align="right">
						<div class="cs-height-100"></div>
						<div class="cs-width-300 white-theme obj-light-shadow right-push-50 top-push-10 sml-rounded-button alignlt noscroll">
							<div class="ln-display-box float-left cs-width-5 cs-height-80 light-red-theme"></div>
							<div class="ln-display-box float-right cs-width-290 pads15 cs-height-80">
								<span class="float-left top-pull-3 right-pull-10"><b class="fas fa-check-circle"></b></span>
								<h4 class="large nobold red-font font-big-bold"><?php echo $post_header; ?></h4>
								<small class="block-element top-push-10"><?php echo $post_message; ?></small>
							</div>
							<div class="block-element new-line-space">
							</div>
						</div>
					</div>
				<?php
			} else {
				?>
					<div id="notifybox" class="noshow fx-position-stick zind-1 motion btscr" align="right">
					</div>
				<?php
			}
		} else {
			?>
				<div id="notifybox" class="noshow fx-position-stick zind-1 motion btscr" align="right">
				</div>
			<?php
		}

		
		##create a log file
		
		if(isset($islogfile) && $islogfile == 1) {}

	?>

</div>


<script>
	
	window.addEventListener('load',function() {
		var temptkn = {}, jtemptkn = JSON.stringify(temptkn);
		if(sessionStorage.getItem('temptkn') === null) { sessionStorage.setItem('temptkn',jtemptkn); }
		var rawhtml, tag = "<?php echo $tag; ?>", logs = "<?php echo $logs; ?>";
		
		//for pop box
		parent.document.getElementById('workspace').scrollTop = 0;
		objDisplay('notifybox'); autohidePopupBox('notifybox',7000);

	},false);

</script>