<?php
include "../includes/initialize_session.php";
include "../includes/config.php";

//include "frval.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if((isset($_POST['requestdata']) && isset($_POST['kyw'])) && ($_POST['kyw'] == 'idgetvalue')) {
		
		$response_data = array();

		$request_data = stripslashes($_POST['requestdata']);
		$request_data_json = json_decode($request_data);

		$tableSrc = $request_data_json->tableSrc;
		$dataCallerid = $request_data_json->dataCallid;
		$dataColumn = $request_data_json->columnKey;
		$valueObj = $request_data_json->valueObj;
		$dataPack = $request_data_json->datapack;

		$queryset = "SELECT {$valueObj} FROM {$tableSrc} WHERE {$dataColumn} = '{$dataCallerid}'";
		$isSql = idget_data($queryset);

		if(is_array($isSql) && count($isSql) > 0) {
			if($dataPack == 'array') {
				foreach($isSql as $key => $val) {
					array_push($response_data, $val[$valueObj]);
				}
				
				$doJSON = json_encode($response_data);
				echo $doJSON;

			} elseif($dataPack == 'scalar') {
				echo $isSql[0][$valueObj];
			}
		}
	}

	#---------------------------------------------------------------------------------------------------------------------------------------end

	if(isset($_POST['kyw']) && $_POST['kyw'] == 'dbvalue')
	{
		$response_data = array();

    	$request_data = stripslashes($_POST['kywdata']);
		$wgt_json = json_decode($request_data);

		$tableSrc = $wgt_json->tbl;
		$dataCallerid = $wgt_json->key;
		$dataColumn = $wgt_json->col;
		
		$queryset = "SELECT * FROM {$tableSrc} WHERE {$dataColumn} = '{$dataCallerid}'";
		$isSql = idget_data($queryset);
    	
	    if(is_array($isSql)) {
	    	$response_data['success'] = 200;
	    	$response_data['datastring'] = $isSql;
		} else {
			$response_data['success'] = 0;
			$response_data['datastring'] = "nil";
		}
		
		$doJSON = json_encode($response_data);
		echo $doJSON;
	}

	#---------------------------------------------------------------------------------------------------------------------------------------end

	if(isset($_POST['kyw']) && $_POST['kyw'] == 'idgetsql')
	{
		$response_data = array();

    	$request_data = stripslashes($_POST['sqlrequestdata']);
		$wgt_json = json_decode($request_data);

		$wsql = $wgt_json->sql;
		
		$queryset = $wsql;
		$isSql = idget_data($queryset);
    	
	    if(is_array($isSql)) {
	    	$response_data['success'] = 200;
	    	$response_data['datastring'] = $isSql;
		} else {
			$response_data['success'] = 0;
			$response_data['datastring'] = "nil";
		}
		
		$doJSON = json_encode($response_data);
		echo $doJSON;
	}

	#---------------------------------------------------------------------------------------------------------------------------------------end
}

?>