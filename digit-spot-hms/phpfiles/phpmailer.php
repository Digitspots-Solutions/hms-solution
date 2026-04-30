<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_;

require '../PHPMailer/PHPMailerAutoload.php';

$mail_server = "mail.gutemplate.com.ng";
$user_name = "info@gutemplate.com.ng";
$pass_word = "success@info@1";
$sender_name = _LONG_NAME;

if(isset($_POST['postmail']) && $_POST['postmail'] == 'y') {

	$guest_id = $_POST['pguest'];
	$post_subject = $_POST['psubject'];
	$post_message = $_POST['pmessage'];

	$guest_email = idget_data($tbL102,$guest_id,'emailaddress');
	$copy_email = "";

	if(isset($guest_email) && !empty($guest_email)) {
		
		$mail = new PHPMailer;

		$mail->isSMTP(); //Set mailer to use SMTP
		$mail->Host = $mail_server; //Specify main and backup SMTP servers
		$mail->SMTPAuth = true; //Enable SMTP authentication
		$mail->Username = $user_name; //SMTP username
		$mail->Password = $pass_word; //SMTP password
		$mail->SMTPSecure = 'ssl'; //Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465; //TCP port to connect to

		$mail->setFrom($user_name, $sender_name);
		$mail->addReplyTo($user_name, $sender_name);
		$mail->addAddress($guest_email); //Add a recipient
		//$mail->addCC($copy_email);
		//$mail->addBCC('bcc@example.com');

		$mail->isHTML(true); //Set email format to HTML

		$mail->Subject = $post_subject;
		$mail->Body = $post_message;

		if(!$mail->send()) {
			$response = 0;
		} else {
			$response = 1;
		}
	} else {
		$response = 0;
	}
}

?>