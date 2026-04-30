<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_;


if(isset($_GET['shop']) && is_numeric($_GET['shop']))
{
	$_SESSION['postoreid'] = $_GET['shop'];

	if(isset($_SESSION['postoreid'])) { $wgt_pos_id = $_SESSION['postoreid']; }
	else { $wgt_pos_id = $_GET['shop']; }

	$postores = idget_data($tbL14,$wgt_pos_id,'posname');
	$postype = idget_data($tbL14,$wgt_pos_id,'postype');
	$storage = idget_data($tbL14,$wgt_pos_id,'store');

	$pos_response_data = array();

	$pos_response_data['success'] = 200;
	$pos_response_data['wgtposname'] = $postores;
	$pos_response_data['wgtpostype'] = $postype;
	$pos_response_data['wgtpostore'] = $storage;

	//$postorekey = array("id"=>$_SESSION['postoreid']);
	//$postores = mysqli_data_fetch($tbL14,'posname',$postorekey,'noarray');

	$json_Obj = json_encode($pos_response_data);
	echo $json_Obj;
}

?>