<?php

$cur_pos_store_id = $_SESSION['postoreid'];

$waiterDocket = idget_data($tbL14,$cur_pos_store_id,'isdocket');
$checkIfisdiscountallowed = idget_data($tbL14,$cur_pos_store_id,'isdiscount');
$guest_discount = idget_data($tbL14,$cur_pos_store_id,'guest_discount');
$staff_discount = idget_data($tbL14,$cur_pos_store_id,'staff_discount');

$checkIfisfoodset = idget_data($tbL14,$cur_pos_store_id,'isfoodtype');
$checkIfiscounter = idget_data($tbL14,$cur_pos_store_id,'iscounter');
$checkIfistableset = idget_fdata($tbL17,'postoreid',$cur_pos_store_id,'id');

/*$food_type_select = array(
	1001=>"Breakfast",
	1002=>"Lunch",
	1003=>"Dinner"
);*/

$food_type = array(
	1=>"Breakfast",
	2=>"Lunch",
	3=>"Dinner",
	4=>"Service"
);

$list_food_type = "";

foreach($food_type as $ftp_key => $ftp_value) {
	$list_food_type .= '<option value="'.$ftp_key.'">'.$ftp_value.'</option>';
}

#------------------------------------------------------------------------------------------------------

$outlet_category_type = array(
	1=>"Food",
	2=>"Beverage",
	3=>"Others"
);

$list_outlet_category = "";

foreach($outlet_category_type as $ctg_key => $ctg_value) {
	$list_outlet_category .= '<option value="'.$ctg_key.'">'.$ctg_value.'</option>';
}

#------------------------------------------------------------------------------------------------------

$bill_type = array(
	1=>"Instant Payment",
	2=>"Charge Room",
	3=>"Complimentary",
	4=>"Group",
	5=>"Staff"
);

$list_bill_type = '';

foreach ($bill_type as $btp_key => $btp_value) {
	$list_bill_type .= '<option value="'.$btp_key.'">'.$btp_value.'</option>';
}

#------------------------------------------------------------------------------------------------------

//$additionalQuery="";
$list_tables = select_dt_fetch('postoreid',$cur_pos_store_id,$tbL17,'id','tablename');

#------------------------------------------------------------------------------------------------------



function get_pos_cmd($arry,$string) {
	
	$th_cmd = '';

	if(is_array($arry)) {
		
		foreach ($arry as $cmd_key => $cmd_value) {
			if($string == $cmd_key) {
				$th_cmd = $cmd_value;
				break;
			}
		}

	}
	
	return $th_cmd;
}


#------------------------------------------------------------------------------------------------------

//get pos tax

/*if(isset($cur_pos_store_id) && $cur_pos_store_id >= 1) {
	$pstx_selection_key = array("postoreid"=>$cur_pos_store_id,"status"=>"Active","deletedata"=>0);
	$get_pos_tax_data = mysqli_data_fetch($tbL18,'id,taxcharge,taxname',$pstx_selection_key,'noarray');
	
	if(isset($get_pos_tax_data[0]) && $get_pos_tax_data[0] >= 1) {
		$cur_pos_tax_charge = $get_pos_tax_data[1];
		$cur_pos_tax_name = $get_pos_tax_data[2];
	} else {
		$cur_pos_tax_charge = 0;
		$cur_pos_tax_name = "Tax";
	}
	
} else {
	$cur_pos_tax_charge = 0;
	$cur_pos_tax_name = "Tax";
}*/

$cur_pos_tax_charge = 0;

?>