<?php
/* file to all function library */

function escape_data($data)
{
	global $mysqli;
	
	//Address Magic Quotes.
	
	if(ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
	
	if(function_exists('mysql_real_escape_string')) {
		$data = $mysqli->real_escape_string($data);
	} else {
		$data = $mysqli->real_escape_string($data);
	}

	return $data;
}

//end of function ------------------------------------------------/

function trash_record($table,$constrain)
{
	global $mysqli;

	$response = array();
	
	if(isset($constrain) && !empty($constrain)) {
		$sql = "DELETE FROM ".$table." WHERE ".$constrain;
		mysqli_query($mysqli,$sql); 
		if(mysqli_affected_rows($mysqli) >= 1) { $response['isaffected'] = true; }
		else { $response['isaffected'] = false; }
	} else {
		$response['isaffected'] = false;
	}

	return $response;
}

//end of function ------------------------------------------------/

function createDatabasetable($tablename)
{
	global $mysqli;
	
	$response = "";

	if(isset($tablename) && !empty($tablename)) {
		$result = mysqli_query($mysqli,$tablename);
		if($result) { $response = true; }
		else { $response = false; }
	} else {
		$response = false;
	}

	return $response;
}

//end of function ------------------------------------------------/

function truncateDbtable($tablename)
{
	global $mysqli;

	if(isset($tablename) && !empty($tablename)) {
		$sql = "TRUNCATE TABLE ".$tablename;
		mysqli_query($mysqli,$sql);
	}
}

//end of function ------------------------------------------------/

function alterDbtable($sql)
{
	global $mysqli;

	$response = "";

	if(isset($sql) && !empty($sql)) {
		$result = mysqli_query($mysqli,$sql);
		if($result) { $response = true; }
		else { $response = false; }
	} else {
		$response = false;
	}

	return $response;
}

//end of function ------------------------------------------------/

function mysqli_data_exist($table,$constrain)
{
	global $mysqli;

	$response = array();
	
	$wgt = "SELECT * FROM {$table} WHERE ".$constrain;
	$result = $mysqli->query($wgt);

	if($result->num_rows > 0) {
		$response['isdata'] = true;
		$response['dbrows'] = $result->num_rows;
	} else {
		$response['isdata'] = false;
		$response['dbrows'] = 0;
	}

	return $response;
}

//end of function ------------------------------------------------/

function mysqli_data_fetch($xfunc,$sql)
{
	global $mysqli;
	
	$response = array();

	/*$result = $mysqli->query($sql);
	
	if($xfunc == 'arithmetic-fx') {
		$data = $result->fetch_assoc();
		$response['mathr'] = $data['field'];
	} else if($xfunc == 'assoc') {
		while($data = $result->fetch_assoc()) { $response[] = $data; }
		$result->free();
	}*/

	$set_query = mysqli_query($mysqli,$sql);

	if(@ mysqli_num_rows($set_query) == true) {
		
		while($data = @ mysqli_fetch_array($set_query,MYSQLI_ASSOC)) {
			$response[] = $data;
		}
	}

	return $response;
}

//end of function ------------------------------------------------/

function mysqli_data_insert($table,$dataset,$constrain)
{
	global $mysqli;
	
	$noinsert = 0;
	$response = array();

	if(isset($constrain) && !empty($constrain)) {
		$result = mysqli_data_exist($table,$constrain);
		if($result['isdata'] == true) { $noinsert = 1; }
		else { $noinsert = 0; }
	} else {
		$noinsert = 0;
	}

	if($noinsert == 0) {
		$sql = "INSERT INTO {$table} SET ".$dataset;
		$mysqli->query($sql);
		if($mysqli->affected_rows >= 1) {
			$response['isaffected'] = true;
			$response['rowid'] = $mysqli->insert_id;
		} else {
			$response['isaffected'] = false;
			$response['rowid'] = 0;
		}
	}

	return $response;
}

//end of function ------------------------------------------------/

function mysqli_data_update($table,$dataset,$constrain)
{
	global $mysqli;
	
	$response = array();

	$sql = "UPDATE {$table} SET ".$dataset." WHERE ".$constrain;
	$mysqli->query($sql);
	if($mysqli->affected_rows >= 1) {
		$response['isaffected'] = true;
	} else {
		$response['isaffected'] = false;
	}
	
	return $response;
}

//end of function ------------------------------------------------/

function mysqli_log_authen($queryset)
{
	$data = mysqli_data_fetch('assoc',$queryset);
	$response = false;
	
	$_SESSION['authen_page'] = 200;
	$_SESSION['loggedin_time'] = time();
	$_SESSION['authen_id'] = $data[0]['id'];

	if($_SESSION['authen_id'] > 0) { $response = true; }
	else { $response = false; }

	return $response;
}

//end of function ------------------------------------------------/

function sessionIsChecked($sesid,$loggedtime)
{
	$login_session_duration = 10500;
	$response = 200;

	if(((time() - $loggedtime) > $login_session_duration) || (!isset($sesid) || empty($sesid))) {
		$response = 0;
	} else {
		$response = 200;
	}

	return $response;
}

//end of function ------------------------------------------------/

function noSession($val,$format,$redirect)
{
	if($val < 200) {
		session_destroy();
		if(isset($format) && $format == '_Self') {
			?>
				<script>
					window.addEventListener('load',function() { window.location.href = "<?php echo $redirect; ?>"; },false);
				</script>
			<?php
		} else if(isset($format) && $format == '_Blank') {
			?>
				<script>
					window.addEventListener('load',function() { window.parent.location.href = "<?php echo $redirect; ?>"; },false);
				</script>
			<?php
		}
	}
}

//end of function ------------------------------------------------/

function sendSMS($apiUrl,$params,$result)
{   
	set_time_limit(1000*60*5);
		
	//$specialchar = array("'","/","\\","@","&","^","!","`");
	//$message = urlencode($wgt_message);
	//foreach($specialchar as $v){$message = str_replace($v,"",$message);}
	//sample result: 1701

	/* $api = array(); $api['apiURL'] = $apiUrl;$api['apiUsername'] = $apiUsername; $api['apiPassword'] = $apiPassword;   $tys=0; $dls=1; $url =$api['apiURL']; $param=""; $uap=$param."job=send"."&email=".$api['apiUsername']."&pass=" . urlencode($api['apiPassword'])."&flash=" .$tys."&dlr=" .$dls."&recv=". urlencode($phone)."&sender=" . urlencode($sender)."&msg=" .urlencode($message);*/

	//Send the POST request with cURL
	$ch = curl_init($apiUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	if(substr($response,0,4) == $result) {
		return 200;
	} else {
		return 0;
	}

	curl_close($ch);
}

//end of function ------------------------------------------------/

function serializePhone($number,$fprefix)
{
	if(isset($number) && !empty($number)) {
		
		if(substr($number,0,1) == 0) { $use_number = $number; }
		else { $use_number = "0".$number; }
		
		$mpref = array('080'=>'trunk_1','070'=>'trunk_2','081'=>'trunk_3','090'=>'trunk_4');
		$pref = substr($use_number,0,3);
		
		if(array_key_exists($pref,$mpref)) {
			$use_number = '234'.substr($use_number,1,10);
		} else {
			$use_number = $fprefix.$use_number;
		}

	} else {
		$use_number = 0;
	}
	
	return $use_number;
}

//end of function ------------------------------------------------/

function mailSender($logourl,$footnote,$senderlabel,$senderemail,$recipient,$mailsubject,$mailmessage)
{
	if(isset($senderemail) && isset($recipient)) {
		
		$from_name = $senderlabel;
		$from_mail = $senderemail;
		$subject = $mailsubject;
		$mailto = $recipient;
		$message = $mailmessage; //html mail setup
		
		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		if(!stristr($from_mail,'no-reply') && !stristr($from_mail,'noreply')) {
		$header .= "Reply-To: ".$from_mail."\r\n"; }
		$header .= "MIME-Version: 1.0\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		if(mail($mailto,$subject,$message,$header)) {
			$response = 200;
		} else {
			$response = 0;
		}

	} else {
		$response = 0;
	}

	return $response;
}

//end of function ------------------------------------------------/

function convertToPdf($htmlobj,$saveto) {

	// Set parameters
	$apikey = '465282e9-2a95-407b-b3a1-563ad196aafe';
	$value = $htmlobj; //can also be a url, starting with http..
												
	$postdata = http_build_query(
		array(
			'apikey' => $apikey,
			'value' => $value,
			'MarginBottom' => '30',
			'MarginTop' => '20'
		)
	);
	 
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);
	 
	$context  = stream_context_create($opts);
	 
	// Convert the HTML string to a PDF using those parameters
	$result = @ file_get_contents('http://api.html2pdfrocket.com/pdf', false, $context);
	 
	// Save to root folder in website
	file_put_contents($saveto, $result);

	return 1;
}

//end of function ------------------------------------------------/

function mail_attachment($filepath, $mailto, $from_mail, $from_name, $replyto, $subject, $message)
{
	global $filename;
	
	$file = $filepath;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);
	
	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));

	
	$header = "From: ".$from_name." <".$from_mail.">\r\n";
	//$header .= "Reply-To: ".$replyto."\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
	$header .= "File attachment.\r\n";
	$header .= "--".$uid."\r\n";
	$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
	$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	$header .= $message."\r\n\r\n";
	$header .= "--".$uid."\r\n";
	$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
	$header .= "Content-Transfer-Encoding: base64\r\n";
	$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
	$header .= $content."\r\n\r\n";
	$header .= "--".$uid."--";
	
	$response = mail($mailto, $subject, "", $header);
	
	if($response) { return 200; }
	else { return 201; }
}

//end of function ------------------------------------------------/

function isMobile()
{
	if(isset($_SERVER["HTTP_USER_AGENT"]))
	{
		
		$user_agents = array("acs", "alav", "alca", "amoi", "audi", "aste", "avan", "benq", "bird", "blac", "blaz", "brew", "cell", "cldc", "cmd-", "dang", "doco", "eric", "hipt", "inno", "ipaq", "java", "jigs", "kddi", "keji", "leno", "lg-c", "lg-d", "lg-g", "lge-", "maui", "maxo", "midp", "mits", "mmef", "mobi", "mot-", "moto", "mwbp", "nec-", "newt", "noki", "opwv", "palm", "pana", "pant", "pdxg", "phil", "play", "pluc", "port", "prox", "qtek", "qwap", "sage", "sams", "sany", "sch-", "sec-", "send", "seri", "sgh-", "shar", "sie-", "siem", "smal", "smar", "sony", "sph-", "symb", "t-mo", "teli", "tim-", "tosh", "tsm-", "upg1", "upsi", "vk-v", "voda", "w3cs", "wap-", "wapa", "wapi", "wapp", "wapr", "webc", "winw", "winw", "xda", "xda-", "up.browser", "up.link", "windowssce", "iemobile", "mini", "mmp", "symbian", "midp", "wap", "phone", "pocket", "mobile", "pda", "psp", "midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
		foreach($user_agents as $user_string)
		{
			if(preg_match("/".$user_string."/i",$_SERVER["HTTP_USER_AGENT"]))
			//if(stristr($user_string,$_SERVER["HTTP_USER_AGENT"]))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
	}
	
	if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) 
	{
		return 1;
	}

	// If the http_accept header supports wap then it's a mobile too
	if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"]))
	{
		return 1;
	}
}
	
//end of function ------------------------------------------------/
	
function mediaQuery()
{
	global $tablet_browser;
	global $mobile_browser;
	
	$tablet_browser = 0;
	$mobile_browser = 0;
	 
	if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$tablet_browser++;
	}
	 
	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
	}
	 
	if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}
	 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');
	 
	if(in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}
	 
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		$mobile_browser++;
		//Check for tablets on opera mini alternative headers
		$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		if(preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
		  $tablet_browser++;
		}
	}
	 
	if($tablet_browser > 0 || $mobile_browser > 0) {
	   // do something for tablet/mobile devices
	   return 1;
	} else  {
	   // do something for desktop
	   return 2;
	}
}

//end of function ------------------------------------------------/

function createDb($cPanelUser,$cPanelPass,$dbName) {

    $buildRequest = "/frontend/paper_lantern/sql/addb.html?db=".$dbName;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);

    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }

    fclose($openSocket);
}

function createUser($cPanelUser,$cPanelPass,$userName,$userPass) {

    $buildRequest = "/frontend/paper_lantern/sql/adduser.html?user=".$userName."&pass=".$userPass;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);

    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }

    fclose($openSocket);
}

function addUserToDb($cPanelUser,$cPanelPass,$userName,$dbName,$privileges) {

    $buildRequest = "/frontend/paper_lantern/sql/addusertodb.html?user=" . $cPanelUser . "_" . $userName . "&db=" . $cPanelUser . "_" . $dbName . "&privileges=" . $privileges;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);

    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }
    
    fclose($openSocket);
}

//end of function ------------------------------------------------/

function getMAC() {
    ob_start(); system('getmac');
    $Content = ob_get_contents();
    ob_clean(); return substr($Content, strpos($Content,'\\')-20, 17);
}

//end of function ------------------------------------------------/

function hashPassword($pass) {
	
	$passwordNowHash = "";
	
	if(!empty($pass)) {
		
		$notags = strip_tags($pass);
		$newHash = sha1($notags);

		$firstPhase = substr($newHash, 30,10);
		$secondPhase = substr($newHash, 20,10);
		$thirdPhase = substr($newHash, 10,10);
		$fourthPhase = substr($newHash, 0,10);

		$thePassword = @crypt($firstPhase, '$5$rounds=5000$usesomesillystringforsalt$');
		$thePassword .= @crypt($secondPhase, '$5$rounds=5000$usesomesillystringforsalt$');
		$thePassword .= @crypt($thirdPhase, '$5$rounds=5000$usesomesillystringforsalt$');
		$thePassword .= @crypt($fourthPhase, '$5$rounds=5000$usesomesillystringforsalt$');

		$nocharsym = str_replace('.','',$thePassword);
		$nocharsym2 = str_replace('/','',$nocharsym);
		$passwordNowHash = $nocharsym2;

		return $passwordNowHash;
	}
}

//end of function ------------------------------------------------/

function hashToken($pass) {
	
	$tokenNowHash = "";
	
	if(!empty($pass)) {
		
		//$encode_pass = urlencode($pass);
		$encode_pass = $pass;
		$pass_crypt = @crypt($encode_pass, '$5$rounds=5000$usesomesillystringforsalt$');

		$tokenNowHash = str_replace('.','',$pass_crypt);
		$tokenNowHash = str_replace('/','',$tokenNowHash);
		$tokenNowHash = $tokenNowHash.sha1($pass);

		return $tokenNowHash;
	}
}

//end of function ------------------------------------------------/

function gwToken() {
	$tkn = '@!'.substr(mt_rand(100,999999999999),1,10);
	return $tkn;
}

//end of function ------------------------------------------------/

function gwOpt() {
	$otp = substr(mt_rand(100,999999999999),1,6);
	return $otp;
}

//end of function ------------------------------------------------/

function vIPadrs() {
	return $_SERVER['REMOTE_ADDR'];
}

//end of function ------------------------------------------------/

function mysqli_table_columns($table) {
	
	global $mysqli;

	$sql = 'DESCRIBE '.$table;
	$data = mysqli_query($mysqli, $sql);

	$rows = array();

	while($row = mysqli_fetch_assoc($data)) {
		$rows[] = $row['Field'];
	}

	return $rows;
}

//end of function ------------------------------------------------/

?>