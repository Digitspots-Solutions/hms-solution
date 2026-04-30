<?php $smdl = "administration"; $logs = escape_data($_GET['logs']); ?>

<div class="block-element bottom-push-30">
 	<span class="ln-display-box float-left">
		<a href="?logs=<?php echo $logs; ?>" title="Refresh"><img src="<?php echo DOMAIN_URL; ?>theme/images/general/refresh.png"></a>
		&nbsp; Note: create bulk rooms by clicking <u>add room</u> button. Click the button and supply information as you proceed
 	</span>
 	<span class="ln-display-box float-right">
		<a href="javascript:void(0)" class="submit pads12 sml-rounded-button blue-theme white-font" onclick="create_newroom(); setTimeout(dodata('select-col-1-','eget-blocks-list',1,'dropbox'),1000); setTimeout(dodata('select-col-3-','eget-roomtype-list',1,'dropbox'),1500); objDisplay('ctrlbx')">
		Add Room
		</a>
	</span>
	<span class="block-element new-line-space">
		<!-- clear line -->
	</span>
</div>

<?php
	
	$update_result = '';
	$post_result = '';
	$htmlresult = '';


	if(isset($_POST['submitbutton']))
	{
		createDatabasetable($var_tbl_54); //create a table for this post

		$fieldset1 = $_POST['blocks'];
		$fieldset2 = $_POST['floors'];
		$fieldset3 = $_POST['roomtypes'];
		$fieldset4 = $_POST['prefix'];
		$fieldset5 = $_POST['room'];
		$fieldset6 = $_POST['suffix'];
		$fieldset7 = $_POST['detail'];
		$fieldset8 = $_POST['extn'];

		$isdata = 0;

		for($r=0; $r < count($fieldset5); $r++)
		{
			$room_arr = array("blockid"=>$fieldset1[$r],"floorid"=>$fieldset2[$r],"room_type_id"=>$fieldset3[$r],"roomprefix"=>$fieldset4[$r],"roomnumber"=>$fieldset5[$r],"roomsuffix"=>$fieldset6[$r],"detail"=>$fieldset7[$r],"extn"=>$fieldset8[$r]);
			$constrain = array("room_type_id"=>$fieldset3[$r],"roomnumber"=>$fieldset5[$r]);
			$data_inserted = mysqli_data_insert($tbL56,$room_arr,$constrain);

			if(isset($data_inserted) && $data_inserted == 2) { $isdata += 1; }
		}

		$post_result .= '<div class="block-element box-border-thick pads15 top-push-30 bottom-push-30">';

		if(isset($isdata) && $isdata >= 1)
		{
			//create a log file
			$log_datasets = array("userid"=>$userSignedIn,"logcategory"=>$smdl,"message"=>"Create new bulk rooms","datelogged"=>$server_get_date,"timelogged"=>$server_get_time); mysqli_data_insert($tbL8,$log_datasets,'');

			$post_result .= '<span class="red-font">New entry was saved successfully</span>';
		}

		$post_result .= '</div>';

		echo $post_result;
	}

?>

<form action="" method="post" autocomplete="off" onsubmit="objDisplay('processbar')">
	<div class="block-element sml-rounded-button noscroll">
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th width="50px" class="box-border-thick-right" align="center">&nbsp;</th>
			<th width="100px" class="box-border-thick-right" align="center">Block</th>
			<th width="100px" class="box-border-thick-right" align="center">Floor</th>
			<th width="150px" class="box-border-thick-right" align="center">Room Type</th>
			<th width="80px" class="box-border-thick-right" align="center">Prefix</th>
			<th width="100px" class="box-border-thick-right" align="center">Room No</th>
			<th width="80px" class="box-border-thick-right" align="center">Suffix</th>
			<th width="150px" class="box-border-thick-right" align="center">Description</th>
			<th width="70px" class="box-border-thick-right" align="center">Extn</th>
		</tr>
		<tbody id="datasheet"></tbody>
		</table>
	</div>
	<input type="hidden" id="rwcounter" value="0">

	<br><br>
	<div id="ctrlbx" class="noshow alignct">
		<input type="submit" name="submitbutton" value="Save" class="submit pads10 black-white-state rounded-button nc-width-20"> &nbsp;&nbsp; <a href="?logs=<?php echo $logs; ?>" class="steel-blue-font">Cancel</a>
	</div>
</form>

<script>

function create_newroom()
{
	var contr,tr,td1,td2,td3,td4,td5,td6,td7,td8,td9,select1,select2,select3,opt1,opt2,opt3,txt1,txt2,txt3,txt4,txt5,obj,numbr,curnumbr;
	var select1Opt,select2Opt,select3Opt;

	curnumbr = document.getElementById('rwcounter');
	contr = document.getElementById('datasheet');

	tr = document.createElement('tr');
	td1 = document.createElement('td');
	td2 = document.createElement('td');
	td3 = document.createElement('td');
	td4 = document.createElement('td');
	td5 = document.createElement('td');
	td6 = document.createElement('td');
	td7 = document.createElement('td');
	td8 = document.createElement('td');
	td9 = document.createElement('td');

	select1 = document.createElement('select');
	select2 = document.createElement('select');
	select3 = document.createElement('select');
	opt1 = document.createElement('option');
	opt2 = document.createElement('option');
	opt3 = document.createElement('option');

	txt1 = document.createElement('input');
	txt2 = document.createElement('input');
	txt3 = document.createElement('input');
	txt4 = document.createElement('input');
	txt5 = document.createElement('input');

	obj = document.createElement('span');

	numbr = eval(curnumbr.value) + 1; //generate new row number

	tr.id = 'tr'+numbr;

	obj.id = 'span'+numbr;
	obj.className = 'block-element alignct';

	var trashicon = document.createElement('b');
	trashicon.id = 'b'+numbr;
	trashicon.className = 'fa-trash nobold anchor';
	trashicon.title = 'Remove Row '+numbr+':';
	trashicon.onclick = function() { 
		contr.removeChild(tr);
	}

	obj.appendChild(trashicon);
	//obj.innerHTML = obj.innerHTML + ' ' + numbr;
	td1.appendChild(obj);

	select1.id = 'select-col-1-'+numbr;
	select1.name = 'blocks[]';
	select1.required = 'required';
	opt1.value = '';
	opt1.text = 'Blocks';
	select1.appendChild(opt1);
	select1.onchange = function() { getdata('select-col-2-'+numbr,'eget-block-floors-list','select-col-1-'+numbr,'dropbox'); }
	td2.appendChild(select1);

	select2.id = 'select-col-2-'+numbr;
	select2.name = 'floors[]';
	select2.required = 'required';
	opt2.value = '';
	opt2.text = 'Floors';
	select2.appendChild(opt2);
	td3.appendChild(select2);

	select3.id = 'select-col-3-'+numbr;
	select3.name = 'roomtypes[]';
	select3.required = 'required';
	opt3.value = '';
	opt3.text = 'Room Types';
	select3.appendChild(opt3);
	td4.appendChild(select3);

	txt1.type = 'text';
	txt1.name = 'prefix[]';
	txt1.placeholder = 'Prefix';
	txt1.required = 'required';
	td5.appendChild(txt1);

	txt2.type = 'text';
	txt2.name = 'room[]';
	txt2.placeholder = 'Room number';
	txt2.required = 'required';
	td6.appendChild(txt2);

	txt3.type = 'text';
	txt3.name = 'suffix[]';
	txt3.placeholder = 'Suffix';
	td7.appendChild(txt3);
	
	txt4.type = 'text';
	txt4.name = 'detail[]';
	txt4.placeholder = 'Room description';
	td8.appendChild(txt4);

	txt5.type = 'text';
	txt5.name = 'extn[]';
	txt5.placeholder = 'Room Extension';
	txt5.value = 0;
	td9.appendChild(txt5);

	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);
	tr.appendChild(td5);
	tr.appendChild(td6);
	tr.appendChild(td7);
	tr.appendChild(td8);
	tr.appendChild(td9);

	contr.appendChild(tr);
	curnumbr.value = numbr;
}

function dodata(str,sses,id,sopt) {
	var curnumbr = document.getElementById('rwcounter').value;
	var select_id = str+curnumbr;

	getdata(select_id,sses,id,sopt);
}

</script>