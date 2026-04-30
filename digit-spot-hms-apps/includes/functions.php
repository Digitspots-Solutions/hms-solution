<?php
/* file to all function library */

function escape_data($data)
{
	global $mysqli;
	
	//Address Magic Quotes.
	
	if(ini_get('magic_quotes_gpc'))
	{
		$data = stripslashes($data);
	}
	
	if(function_exists('mysql_real_escape_string'))
	{
		$data = $mysqli->real_escape_string($data);
	}
	else
	{
		$data = $mysqli->real_escape_string($data);
	}

	return $data;
}

//end of function ------------------------------------------------/

function trash_record($table,$constrain)
{
	global $mysqli;
	global $addQuery;
	
	if(is_array($constrain)) 
	{ 
		$count_array_occurence = count($constrain); $loop = 0;
		
		$contrain_selector = " WHERE ";
		
		foreach($constrain as $colkey => $coldata)
		{
			$loop += 1;
			
			if($loop < $count_array_occurence)
			{
				$contrain_selector .= $colkey."='".$coldata."' AND ";
			}
			else
			{
				$contrain_selector .= $colkey."='".$coldata."'";
			}
		}

		if(isset($addQuery) && !empty($addQuery)) { $sql = "DELETE FROM ".$table.$contrain_selector.$addQuery; }
		else { $sql = "DELETE FROM ".$table.$contrain_selector; }
		
		mysqli_query($mysqli,$sql); 
		
		if(mysqli_affected_rows($mysqli) >= 1)
		{
			$response = 2;
		}
		else
		{
			$response = 1;
		}
			
		return $response;
	}
}

//end of function ------------------------------------------------/

function createDatabasetable($tablename)
{
	global $mysqli;
	
	if(isset($tablename) && !empty($tablename))
	{
		$result = mysqli_query($mysqli,$tablename);
		
		if($result)
		{
			$tbl = 2;
		}
		else
		{
			$tbl = 1;
		}
		
		return $tbl;
	}
}

//end of function ------------------------------------------------/

function truncateDatabasetable($tablename)
{
	global $mysqli;
	
	if(isset($tablename) && !empty($tablename))
	{
		$sql = "TRUNCATE TABLE ".$tablename;
		mysqli_query($mysqli,$sql);
	}
}

//end of function ------------------------------------------------/

function mysqli_data_check($table,$dataset,$constrain)
{
	global $mysqli;
	global $numOfrows;
	global $additionalQuery;
	
	$contrain_selector="";
	
	if(isset($table) && !empty($table))
	{
		if(is_array($constrain))
		{
			$doClause = 1;
			
			$count_array_occurence = count($constrain);
			$loop=0;
				
			$contrain_selector=" WHERE ";
			
			foreach($constrain as $colkey => $coldata)
			{
				$loop += 1;
				
				if($loop < $count_array_occurence)
				{
					$contrain_selector .= $colkey."='".$coldata."' AND ";
				}
				else
				{
					$contrain_selector .= $colkey."='".$coldata."'";
				}
			}
			
			$contrain_selectors = $contrain_selector;
		}
		else
		{
			$contrain_selectors="";
		}
		
		if(isset($dataset) && !empty($dataset))
		{
			if(isset($additionalQuery) && !empty($additionalQuery)) { $aQuery = $additionalQuery; }
			else { $aQuery = ""; }
			
			if($dataset == '(*)')
			{
				$sqli = "SELECT * FROM ".$table.$contrain_selectors.$aQuery;
			}
			else
			{
				$sqli = "SELECT $dataset FROM ".$table.$contrain_selectors.$aQuery;
			}
		}
		
		$result = mysqli_query($mysqli,$sqli);
		$checkifexist = @ mysqli_num_rows($result);

		if(isset($checkifexist) && $checkifexist >= 1)
		{
			$numOfrows = $checkifexist;
			$avail=2;
		}
		else
		{
			$numOfrows = 0;
			$avail=1;
		}
		
		/*if($result)
		{
			$numOfrows = @ mysqli_num_rows($result);
			$avail=2;
		}
		else
		{
			$numOfrows = 0;
			$avail=1;
		}*/
		
		return $avail;
	}
}

//end of function ------------------------------------------------/

function mysqli_data_checkr($table,$dataset,$constrain)
{
	global $mysqli;
	//global $numOfrows;
	global $additionalQuery;
	
	$contrain_selector="";
	
	if(isset($table) && !empty($table))
	{
		if(is_array($constrain))
		{
			$doClause = 1;
			
			$count_array_occurence = count($constrain);
			$loop=0;
				
			$contrain_selector=" WHERE ";
			
			foreach($constrain as $colkey => $coldata)
			{
				$loop += 1;
				
				if($loop < $count_array_occurence)
				{
					$contrain_selector .= $colkey."='".$coldata."' AND ";
				}
				else
				{
					$contrain_selector .= $colkey."='".$coldata."'";
				}
			}
			
			$contrain_selectors = $contrain_selector;
		}
		else
		{
			$contrain_selectors="";
		}
		
		if(isset($dataset) && !empty($dataset))
		{
			if(isset($additionalQuery) && !empty($additionalQuery)) { $aQuery = $additionalQuery; }
			else { $aQuery = ""; }
			
			if($dataset == '(*)')
			{
				$sqli = "SELECT * FROM ".$table.$contrain_selectors.$aQuery;
			}
			else
			{
				$sqli = "SELECT $dataset FROM ".$table.$contrain_selectors.$aQuery;
			}
		}
		
		$result = mysqli_query($mysqli,$sqli);
		
		if(@ mysqli_num_rows($result) >= 1)
		{
			$numOfrows = true;
		}
		else
		{
			$numOfrows = false;
		}
		
		return $numOfrows;
	}
}

//end of function ------------------------------------------------/

function mysqli_data_insert($table,$dataset,$constrain)
{
	global $mysqli;
	global $mysqli_id;
	
	$contrain_selector="";
	$param="";
	
	if(is_array($constrain))
	{
		$doClause = 1;
		
		$count_array_occurence = count($constrain);
		$loop=0;
			
		$contrain_selector=" WHERE ";
		
		foreach($constrain as $colkey => $coldata)
		{
			$loop += 1;
			
			if($loop < $count_array_occurence)
			{
				$contrain_selector .= $colkey."='".$coldata."' AND ";
			}
			else
			{
				$contrain_selector .= $colkey."='".$coldata."'";
			}
		}
		
		$contrain_selectors = $contrain_selector;
	}
	else
	{
		$doClause = 0;
		$contrain_selectors="";
	}
	
	if(is_array($dataset))
	{
		foreach($dataset as $colschema => $coldatas)
		{
			$param .= $colschema."='".$coldatas."',";
		}
		
		$params = substr_replace($param,'',-1,1);
	}
	else
	{
		$params = "";
	}
	
	$sqls = "SELECT * FROM ".$table.$contrain_selectors;
	$sqli = "INSERT INTO ".$table." SET ".$params;
	
	if(isset($doClause) && $doClause == 1)
	{
		$uchk = mysqli_query($mysqli,$sqls);
		if(@ mysqli_num_rows($uchk) >= 1)
		{
			$avail = 1;
		}
		else
		{
			mysqli_query($mysqli,$sqli);
			if(@ mysqli_affected_rows($mysqli) == 1)
			{
				$mysqli_id = mysqli_insert_id($mysqli);
				$avail = 2;
			}
		}
	}
	else
	{
		mysqli_query($mysqli,$sqli);
		if(@ mysqli_affected_rows($mysqli) == 1)
		{
			$mysqli_id = mysqli_insert_id($mysqli);
			$avail = 2;
		}
		else
		{
			$avail = 1;
		}
	}
	
	return $avail;
}

//end of function ------------------------------------------------/

function mysqli_data_update($table,$dataset,$constrain)
{
	global $mysqli;
	global $additionalQuery;
	
	$contrain_selector="";
	$param="";
	
	if(is_array($constrain))
	{
		$doClause = 1;
		
		$count_array_occurence = count($constrain);
		$loop=0;
			
		$contrain_selector=" WHERE ";
		
		foreach($constrain as $colkey => $coldata)
		{
			$loop += 1;
			
			if($loop < $count_array_occurence)
			{
				$contrain_selector .= $colkey."='".$coldata."' AND ";
			}
			else
			{
				$contrain_selector .= $colkey."='".$coldata."'";
			}
		}
		
		$contrain_selectors = $contrain_selector;
	}
	else
	{
		$doClause = 0;
		$contrain_selectors="";
	}
	
	if(is_array($dataset))
	{
		foreach($dataset as $colschema => $coldatas)
		{
			$param .= $colschema."='".$coldatas."',";
		}
		
		$params = substr_replace($param,'',-1,1);
	}
	else
	{
		$params = "";
	}

	if(isset($additionalQuery) && !empty($additionalQuery)) { $aQuery = $additionalQuery; }
	else { $aQuery = ""; }
	
	$sqli = "UPDATE ".$table." SET ".$params.$contrain_selectors.$aQuery;
	mysqli_query($mysqli,$sqli);
	
	if(@ mysqli_affected_rows($mysqli) >= 1)
	{
		$avail = 2;
	}
	else
	{
		$avail = 1;
	}
	
	return $avail;
}

//end of function ------------------------------------------------/

function mysqli_data_fetch($table,$dataset,$query,$displaytype)
{
	global $mysqli;
	global $additionalQuery;
	
	$contrain_selector="";
	$headers=$dataset;
	$params="";
	
	
	if(isset($table) && !empty($table))
	{
		if(is_array($query))
		{
			$doClause = 1;
			
			$count_array_occurence = count($query);
			$loop=0;
		
			$contrain_selector=" WHERE ";
			
			foreach($query as $colkey => $coldata)
			{
				$loop += 1;
			
				if($loop < $count_array_occurence)
				{
					$contrain_selector .= $colkey."='".$coldata."' AND ";
				}
				else
				{
					$contrain_selector .= $colkey."='".$coldata."'";
				}
			}
			
			$contrain_selectors = $contrain_selector;
		}
		else
		{
			$doClause = 0;
			$contrain_selectors="";
		}
		
		if(isset($dataset) && !empty($dataset))
		{
			if(isset($additionalQuery) && !empty($additionalQuery)) { $aQuery = $additionalQuery; }
			else { $aQuery = ""; }
			
			if($dataset == '(*)')
			{
				$sqli = "SELECT * FROM ".$table.$contrain_selectors.$aQuery;
			}
			else
			{
				$sqli = "SELECT $dataset FROM ".$table.$contrain_selectors.$aQuery;
			}
		}
		
		$result = mysqli_query($mysqli,$sqli);
		
		if(@ mysqli_num_rows($result) >= 1)
		{
			$data_array = array();
			$var_in_array = explode(",",$dataset);
			
			if(isset($displaytype) && $displaytype == 'noarray')
			{
				$display = @ mysqli_fetch_array($result,MYSQLI_ASSOC);
				
				foreach($var_in_array as $getcol)
				{
					array_push($data_array,$display[$getcol]);
				}
			}
			elseif(isset($displaytype) && $displaytype == 'array')
			{
				$sub_data_array = array(); $key = ""; $value = "";
				
				while($display = @ mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$key = "startRow"; $value = "start";
					$sub_data_array[$key] = $value;
					
					foreach($var_in_array as $getcol)
					{
						$key = $getcol; $value = $display[$getcol];
						$sub_data_array[$key] = $value;
					}
					
					$key = "nextRow"; $value = "end";
					$sub_data_array[$key] = $value;
					
					array_push($data_array,$sub_data_array);
				}
			}
		}
		else
		{
			$data_array = "";
		}
		
		return $data_array;
	}
}

//end of function ------------------------------------------------/

function mysqli_user_privilege($id,$col,$table,$uprivilege,$ucol)
{
	global $mysqli;
	
	$upr = mysqli_query($mysqli, "SELECT * FROM $table WHERE $col = '".$id."' AND $ucol = '".$uprivilege."'");
	if(@ mysqli_num_rows($upr) == 1)
	{
		$privlg = 1;
	}
	else
	{
		$privlg = 0;
	}
	
	return $privlg;
}

//end of function ------------------------------------------------/

function mysqli_log_authen($table,$queryset)
{
	global $mysqli;
	global $userSigned;

	$param="";
	
	if(is_array($queryset))
	{
		$doClause = 1;
		
		$count_array_occurence = count($queryset);
		$loop=0;
		
		$param = " WHERE ";
		
		foreach($queryset as $colschema => $coldatas)
		{
			$loop += 1;
			
			if($loop < $count_array_occurence)
			{
				$param .= $colschema."='".$coldatas."' AND ";
			}
			else
			{
				$param .= $colschema."='".$coldatas."'";
			}
		}
		
		$params = $param;
	}
	else
	{
		$doClause = 0;
	}
	
	if(isset($doClause) && $doClause == 1)
	{
		$sqli = "SELECT * FROM ".$table.$params;
		$result = mysqli_query($mysqli,$sqli);
		
		if(@ mysqli_num_rows($result) == 1)
		{
			$display = @ mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			$_SESSION['page_sid'] = "session_active_page";
			$_SESSION['data_sid'] = 200;
			$_SESSION['authenticate_id'] = $display['id'];
			$userSigned = $display['id'];
			
			$authen = 2;
		}
		else
		{
			$authen = 1;
		}
		
		return $authen;
	}
}

//end of function ------------------------------------------------/

function mysqli_get_schema_data($table,$dataset,$queryset)
{
	global $mysqli;
	
	$param="";
	
	if(is_array($queryset))
	{
		$count_array_occurence = count($queryset);
		$loop=0;
		
		$doClause = 1;
		
		$param = " WHERE ";
		
		foreach($queryset as $colschema => $coldatas)
		{
			$loop += 1;
			
			if($loop < $count_array_occurence)
			{
				$param .= $colschema."='".$coldatas."' AND ";
			}
			else
			{
				$param .= $colschema."='".$coldatas."'";
			}
		}
		
		$params = $param;
	}
	else
	{
		$doClause = 0;
	}
	
	if(isset($doClause) && $doClause == 1)
	{
		$sqli = "SELECT ".$dataset." FROM ".$table.$params;
		$result = mysqli_query($mysqli,$sqli);
		
		if(@ mysqli_num_rows($result) == 1)
		{
			$display = @ mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			$data_array = array();
				
			$var_in_array = explode(",",$dataset);
			
			foreach($var_in_array as $getcol)
			{
				array_push($data_array,ucwords($display[$getcol]));
			}
		}
		else
		{
			$data_array = "";
		}
		
		return $data_array;
	}
}

//end of function ------------------------------------------------/

function mysqli_arithmetic_data($table,$dataset,$queryset)
{
	global $mysqli;
	
	$param="";
	
	if(isset($queryset) && !empty($queryset))
	{
		$doClause = 1;
		
		$param = " WHERE ";
		
		$param .= $queryset;
		
		$params = $param;
	}
	else
	{
		$params = "";
		$doClause = 1;
	}
	
	if(isset($doClause) && $doClause == 1)
	{
		$sqli = "SELECT ".$dataset." AS 'dataresult' FROM ".$table.$params;
		$result = mysqli_query($mysqli,$sqli);
		
		if(@ mysqli_num_rows($result) >= 1)
		{
			$display = @ mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			$get_dataresult = $display['dataresult'];
		}
		else
		{
			$get_dataresult = 0;
		}
		
		return $get_dataresult;
	}
}

//end of function ------------------------------------------------/

function sessionIsChecked($var_sid,$redirect,$evals)
{	
	global $toparentLog;

	if(!isset($var_sid) || $var_sid != $evals) {
		
		$_SESSION['err'] = "session_expired_page";
		session_destroy();
		
		if($toparentLog == true): ?> <script> window.parent.location="<?php echo $redirect; ?>"; </script> <?php
		else: ?> <script> window.location="<?php echo $redirect; ?>"; </script> <?php endif;
		
		exit;
	}
}

//end of function ------------------------------------------------/

function sessionCloseSid($var_sid)
{
	if(isset($var_sid) && !empty($var_sid))
	{
		$var_sid=null;
		return $var_sid;
	}
}

//end of function ------------------------------------------------/

function sendSMS($phone,$sender,$message)
{   
	
	set_time_limit(1000*60*5);
		
	//$specialchar = array("'","/","\\","@","&","^","!","`");
	
	global $apiUrl;
	global $apiUsername;
	global $apiPassword;
	
	$specialchar = array("\\");
	
	foreach($specialchar as $v)
	{
		$message = str_replace($v,"",$message);
	}
		
	$api = array();
  $api['apiURL'] = $apiUrl;
  $api['apiUsername'] = $apiUsername;
  $api['apiPassword'] = $apiPassword;    
	$tys=0;
	$dls=1;
		$url =$api['apiURL'];
		$param="";
		$uap=$param
		."job=send"
		."&email=".$api['apiUsername']
		. "&pass=" . urlencode($api['apiPassword'])
		. "&flash=" .$tys
		. "&dlr=" .$dls
		. "&recv=". urlencode($phone)
		. "&sender=" . urlencode($sender)
		. "&msg=" .urlencode($message);
	
		if (function_exists('curl_init'))
		{
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); //return as a variable
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, 1); //set POST method
			curl_setopt($ch, CURLOPT_POSTFIELDS, $uap); //set the POST variables
			$response = curl_exec($ch); //run the whole process and return the response
			
			if(substr($response,0,4) == '1701')
			{
			  return 1;
			}
			
			curl_close($ch);
		}
		else
		{
			return 0;
		}

}

//end of function ------------------------------------------------/

function serializePhone($number)
{
	if(isset($number) && !empty($number))
	{
		if(substr($number,0,1) == "0") { $use_number = $number; }
		else { $use_number = "0".$number; }
		
		$mpref = array('080'=>'trunk_1','070'=>'trunk_2','081'=>'trunk_3','090'=>'trunk_4');
		  
		$pref = substr($use_number,0,3);
		
		if(array_key_exists($pref,$mpref))
		{
			$use_number = '234' . substr($use_number,1,10);
		}
		else
		{
			$use_number = 999;
		}
		
	}
	else
	{
		$use_number = 999;
	}
	
	return $use_number;
}

//end of function ------------------------------------------------/

function mailSender($logourl,$footnote,$senderlabel,$senderemail,$recipient,$mailsubject,$mailmessage)
{
	if(isset($senderemail) && isset($recipient))
	{
		$email_template="<!DOCTYPE html><html><head><meta charset='utf-8'>";
		$email_template .="<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
		$email_template .="<meta name='viewport' content='initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width'>";
		$email_template .="</head>";
		$email_template .="<body style='background-color: #e9e9e9; padding: 5% 10% 5% 10%; font-family: tahoma,verdana,arial,sans-serif; font-size: 15px'>";
		$email_template .="<div style='display: block' align='center'><a href=''><img src='".$logourl."' style='border: none; outline: none'></a></div>";
		$email_template .="<div style='background-color: #fff; display: block; margin: 20px 0 15px 0; padding: 20px;'>";
		$email_template .= $mailmessage;
		$email_template .="</div>";
		$email_template .="<div style='display: block' align='center'>Copyright &copy; ".date("Y").". ".$footnote."</div>";
		$email_template .="</body></html>";
		
		$from_name = $senderlabel;
		$from_mail = $senderemail;
		$subject = $mailsubject;
		$mailto = $recipient;
		$message = $email_template;
		
		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		if(!stristr($from_mail,'no-reply') && !stristr($from_mail,'noreply')) {
		$header .= "Reply-To: ".$from_mail."\r\n"; }
		$header .= "MIME-Version: 1.0\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$response = @ mail($mailto,$subject,$message,$header);
		
		return $response;
	}
	else
	{
		return 999;
	}
}

//end of function ------------------------------------------------/

?>