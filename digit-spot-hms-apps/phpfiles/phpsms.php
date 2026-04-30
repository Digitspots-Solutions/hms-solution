<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_;


if(isset($_POST['postsms']) && $_POST['postsms'] == 'y') {

	$guest_id = $_POST['pguest'];
	$post_sender = $_POST['psubject'];
	$post_message = $_POST['pmessage'];

	$guest_number = idget_data($tbL102,$guest_id,'mobile');
	
	if(isset($guest_number) && !empty($guest_number)) {
		
		$sms_sender = $post_sender;
		$sms_message = $post_message;
		$receiver_mobile_number = serializePhone($guest_number);
		$issent = sendSMS($receiver_mobile_number,$sms_sender,$sms_message);

		if(isset($issent) && $issent == 1) {
			$response = 1;
		} else {
			$response = 0;
		}

	} else {
		$response = 0;
	}
}

?>