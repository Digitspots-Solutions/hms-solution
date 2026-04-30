<?php include "../includes/php_paths.php"; include BWF_PATH.ROOT_FLD._DB_SERVER_; include BWF_PATH.ROOT_FLD._DB_TABLES_; include BWF_PATH.ROOT_FLD._FUNC_; include BWF_PATH.ROOT_FLD._RQ_FUNC_; include BWF_PATH.ROOT_FLD._SERVER_CUR_DATE_;
	
	
	if(isset($_POST['dataSend']) && $_POST['dataSend'] == 200)
	{
		
		#--------------------------------------------------------------------------------------------------------end

		if(isset($_POST['kyw']) && $_POST['kyw'] == 'idgetsql') {
			
			$response_data = array();
			$response = array();

	    	$request_data = stripslashes($_POST['sqlrequestdata']);
			$wgt_json = json_decode($request_data);

			$wsql = $wgt_json->sql;
			$queryset = $wsql;

			/*$result = $mysqli->query($queryset);
			while($data = $result->fetch_assoc()) { $response[] = $data; }
			
			$result->free();*/

			$set_query = mysqli_query($mysqli,$queryset);

			if(@ mysqli_num_rows($set_query) == true) {
				
				while($data = @ mysqli_fetch_array($set_query,MYSQLI_ASSOC)) {
					$response[] = $data;
				}
			}

		    if(is_array($response) && count($response) > 0) {
		    	$response_data['success'] = 200;
		    	$response_data['datastring'] = $response;
			} else {
				$response_data['success'] = 0;
				$response_data['datastring'] = "nil";
			}
			
			$doJSON = json_encode($response_data);
			echo $doJSON;
		}

		#--------------------------------------------------------------------------------------------------------end

		if(isset($_POST['f']) && $_POST['f'] == 'idgetvalue') {
			
			$request_data = stripslashes($_POST['postdatarequest']);
			$request_data_json = json_decode($request_data);

			$tableSrc = $request_data_json->tableSrc;
			$dataCallid = $request_data_json->dataCallid;
			$columnKey = $request_data_json->columnKey;
			$valueObj = $request_data_json->valueObj;

			$response_data = idget_fdata($tableSrc,$columnKey,$dataCallid,$valueObj);
			echo $response_data;
		}

		#-------------------------------------------------------------------------------------------------

		if(isset($_POST['f']) && $_POST['f'] == 'fileupload')
		{
			
			$encoded_data = str_replace(' ','+',$_POST['formfield1']);
			$binary_data = base64_decode($encoded_data);
	
			$img = $_POST['formfield2']."_".date('YmdHis');
			$newname="../theme/images/general/".$_POST['formfield2']."/".$img.".jpg";
			$newphoto = $img.".jpg";
			
			file_put_contents($newname, $binary_data);
			
			$data_inserted = $newphoto;

			echo $data_inserted;
		}

		#-------------------------------------------------------------------------------------------------

		if(isset($_POST['f']) && $_POST['f'] == 'userauthen') 
		{
			//createDatabasetable($var_tbl_136); //create a table for this post

			$jspost = stripslashes($_POST['fdata']);
			$post_formdata = json_decode($jspost);
			
			$username = $post_formdata->wgtuserid;
			$password = $post_formdata->wgtpwd;
			$interface = $_POST['intf'];
			$privilege = $_POST['prl'];

		
			$intf_query = array("category"=>$interface,"moduleid"=>200);
			$wgt1 = mysqli_data_fetch($tbL10,'id',$intf_query,'noarray');

			$prl_query = array("name"=>$privilege,"categoryid"=>$wgt1[0]);
			$wgt2 = mysqli_data_fetch($tbL11,'id',$prl_query,'noarray');
			
			$response = array();

			$select_query = array("username"=>$username,"password"=>sha1($password),"status"=>"Active");
			$select_data = mysqli_data_fetch($tbL7,'id,role,uaccess',$select_query,'noarray');

			if(isset($select_data[0]) && $select_data[0] >= 1) {
				if(isset($select_data[2]) && $select_data[2] == 'super admin') {
					$response['success'] = 200;
					$response['userid'] = $select_data[0];
					$response['status'] = 'Ok';
				} else {
					$accs_query = array("roleid"=>$select_data[1],"classid"=>$wgt1[0],"name"=>$wgt2[0],"deletedata"=>0);
					$rdata = mysqli_data_checkr($tbL5,'(*)',$accs_query);

					if($rdata == true) {
						$response['success'] = 200;
						$response['userid'] = $select_data[0];
						$response['status'] = 'Ok';
					} else {
						$response['success'] = 0;
						$response['userid'] = 0;
						$response['status'] = 'Sorry, you do not have access to perform this request';
					}
				}
				
			} else {
				$response['success'] = 0;
				$response['userid'] = 0;
				$response['status'] = 'Invalid username or password';
			}

			$jsr = json_encode($response);
			echo $jsr;
		}
	}

?>