<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; 
include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_;  include B2WF_PATH.ROOT_FLD._NOTIFICATION_TYPES_;
include B2WF_PATH.ROOT_FLD._PACKAGE_TYPES_; 

sessionIsChecked(PAGE_AUTHEN_SID,'./','session_active_page'); 
$userSignedIn = USER_AUTHEN_ID;

include "../../includes/uom.php";
include "../../includes/pos_common_data.php";

?>

<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
<link rel="stylesheet" href="../../style/custom.css"/>
<link rel="stylesheet" href="applystyle.css"/>
<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../../js/jspath.js"></script>
<script type="text/javascript" src="../../js/jsbk.js"></script>
<script src="../ckeditor/ckeditor.js"></script>

<div class="block-element pads30">
	<div class="cs-height-100"></div>
	<?php

		$logs = isset($_GET['logs']);

		$postorekey = array("deletedata"=>0,"status"=>"Active");
		$postores = mysqli_data_fetch($tbL14,'id,posname',$postorekey,'array');

		if(is_array($postores)) {
			foreach ($postores as $pskey => $psvalue) {
				if(!empty($logs) && $logs == $psvalue['posname']) {
					//populate default category
					foreach($outlet_category_type as $key => $val):
						$queryset = array("postoreid"=>$cur_pos_store_id,"program_id"=>$key);
						$dataset = array("postoreid"=>$cur_pos_store_id,"program_id"=>$key,"category"=>$val,"detail"=>"For {$val} Products","isdefault"=>"Yes");
						mysqli_data_insert($tbL15,$dataset,$queryset);
					endforeach;
					include "pos/get_cur_open_counter.php";
					break;
				}
			}
		}

	?>
</div>

<div id="notifybox" class="noshow fx-position-stick zind-2 motion tpscr top-push-50 top-pull-50" align="right">
	<div class="cs-width-400 white-theme pads20 top-push-50 right-push-50 sml-rounded-button alignlt box-border-thick">
		<h4 id="pos-header-notification" class="large red-font"></h4>
		<small id="pos-message-notification" class="block-element top-push-10"></small>
	</div>
</div>