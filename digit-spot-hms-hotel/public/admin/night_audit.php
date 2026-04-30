<?php
	
	/*createDatabasetable($var_tbl_131); //create a table for this post
	createDatabasetable($var_tbl_132); //create a table for this post

	if(isset($gh_get_night_audit_calendar) && $gh_get_night_audit_calendar == 1) {
		$audit_date = $server_get_date;
	} elseif(isset($gh_get_night_audit_calendar) && $gh_get_night_audit_calendar == 2) {
		$audit_date = date('Y-m-d',strtotime('1 day'));
	}

	$log_audit_data = array("audit_date"=>$audit_date);
	mysqli_data_insert($tbL136,$log_audit_data,$log_audit_data);

	$log_audit_mdl_1 = array("audit_date"=>$audit_date,"module"=>2);
	$log_audit_mdl_2 = array("audit_date"=>$audit_date,"module"=>3);
	$log_audit_mdl_3 = array("audit_date"=>$audit_date,"module"=>4);
	$log_audit_mdl_4 = array("audit_date"=>$audit_date,"module"=>7);
	$log_audit_mdl_5 = array("audit_date"=>$audit_date,"module"=>8);

	mysqli_data_insert($tbL137,$log_audit_mdl_1,$log_audit_mdl_1);
	mysqli_data_insert($tbL137,$log_audit_mdl_2,$log_audit_mdl_2);
	mysqli_data_insert($tbL137,$log_audit_mdl_3,$log_audit_mdl_3);
	mysqli_data_insert($tbL137,$log_audit_mdl_4,$log_audit_mdl_4);
	mysqli_data_insert($tbL137,$log_audit_mdl_5,$log_audit_mdl_5);*/

	//night audit work time
	$night_audit_time = $server_get_date.' '.$gh_get_night_audit_hr.':'.$gh_get_night_audit_min;
	$night_audit_time2 = $server_get_date.' '.$gh_get_night_audit_hr.':'.$gh_get_night_audit_min.':00';
	$cur_day_time = $server_get_date.' '.$server_get_time;

	//check if night audit is already done
	$night_query = array("audit_date"=>$server_get_date,"status"=>"Pending");
	$isnot_init = mysqli_data_checkr($tbL136,'(*)',$night_query);

	$ftimestamp = $gh_get_night_audit_hr.$gh_get_night_audit_min.'00';
	$ttimestamp = str_replace(':','',$server_get_time);
	
	if($ttimestamp > $ftimestamp) {
		$hrsDiff = daytimeDiffs($night_audit_time2,$cur_day_time);
		if(($isnot_init == true) && (isset($hrsDiff[2]) && $hrsDiff[2] >= 3)) { $open_night_audit_box = "block-element"; }
		else { $open_night_audit_box = "noshow"; }
	} else {
		$open_night_audit_box = "noshow";
	}
	

?>

<div id="night-audit-alert" class="<?php echo $open_night_audit_box; ?> fx-position-flow fscr zind-1 txp8-black motion" align="center">
	<div class="fx-width-40 white-theme pads30 cs-margin-top-100 alignlt">
		<h2 class="large nobold default-text-font-bold">Start Night Audit</h2>
		<h3 class="large nobold">You have one or two night audit pending. We recommend you complete it before proceeding to business day.
		Click <u>start</u> button to get started</h3>
		<p class="top-pull-50 alignct">
			<a href="javascript: startnightAudit()" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button ft-xsml-size default-text-font-bold">Start</a>
			<a href="javascript: void(0)" class="dark-black-font top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size" onclick="chgclass('night-audit-alert','fx-position-flow fscr zind-1 txp5-black motion noshow')">Skip</a>
		</p>
	</div>
</div>

<div id="nat" class="noshow"></div>

<script type="text/javascript">
	
	function min_diff(ftime,ttime) {
		
		//dividing the result by 1000 gives you the number of seconds. dividing that by 60 gives you the number of minutes.
		//to round to whole minutes, use Math.floor or Math.ceil:

		var diff = Math.abs(new Date(ftime) - new Date(ttime));
		var minutes = Math.floor((diff/1000)/60);
		return minutes;
	}
	

	function night_audit(nat,r) {
		var stopafter = setInterval(function() {

			var date = new Date;

			day = date.getDate();
			hr = date.getHours();
			min = date.getMinutes();
			year = date.getFullYear();
    		mth = eval(date.getMonth()) + 1;
    		if(mth < 10) { month = '0'+mth; } else { month = mth; }

    		var day_time_now = year+'-'+month+'-'+day+' '+hr+':'+min;
			var timeLeft = min_diff(nat,day_time_now);
			//console.log(timeLeft);

			if(eval(timeLeft) == eval(r)) {
				objDisplay('pause-state');
				writeObjheader('pause-state-msg','Night audit is starting in '+timeLeft+' min(s) time');
				clearInterval(stopafter);
				setTimeout(function() {
					objHidden('pause-state');
					writeObjheader('pause-state-msg','');
				},7000);
			}

		},1000);
	}


	function night_audit_start(nat,l) {
		var stopafter = setInterval(function() {

			var date = new Date;

			day = date.getDate();
			hr = date.getHours();
			min = date.getMinutes();
			year = date.getFullYear();
    		mth = eval(date.getMonth()) + 1;
    		if(mth < 10) { month = '0'+mth; } else { month = mth; }

    		var day_time_now = year+'-'+month+'-'+day+' '+hr+':'+min;
			var timeLeft = min_diff(nat,day_time_now);
			//console.log(timeLeft);
			if(eval(timeLeft) == eval(l)) {
				clearInterval(stopafter);
				chgclass('night-audit-alert','fx-position-flow fscr zind-1 txp5-black motion');
			}

		},1000);
	}

	function return2Work() {
		var stopafter = setInterval(function() {
			
			var xhr,randnum,url,ajaxresult;

			if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
			else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }
		 	
		 	randnum = Math.floor(Math.random() * 1000000);
			url = phpfile+"dbquery.php?r=get-audit-init-status&dataSend=200&rand="+randnum;

			xhr.onreadystatechange=function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						//console.log(xhr.responseText);
						ajaxresult = JSON.parse(xhr.responseText);
						if(ajaxresult.success == 200) {
							if(ajaxresult.status == 'Started') {
								var init = sessionStorage.getItem('nightaudit');
								if(init == '' || init == null || init == 'undefined') {
									objDisplay('pause-state');
									writeObjheader('pause-state-msg','Night audit has started..');
									chgclass('pause-state','fx-position-flow fscr zind-2 motion block-element black-theme');
									chgclass('pause-state-msg','cs-width-250 dark-black-theme white-font pads20');
								}
							} else if(ajaxresult.status == 'Successful') {
								clearInterval(stopafter);
								objHidden('pause-state');
								writeObjheader('pause-state-msg','');
								chgclass('pause-state','fx-position-flow fscr zind-1 motion noshow txp2-white');
								chgclass('pause-state-msg','cs-width-350 white-theme obj-shadow pads20');
							}
						}
					}
				}
			};

			xhr.open('GET', url, true);
			xhr.send();

		},3000);
	}

	function startnightAudit() {
		writeObjheader('night-audit-alert','');
		chgclass('night-audit-alert','fx-position-flow fscr zind-1 white-theme noscroll motion');
		sessionStorage.setItem('nightaudit','started');
		var newframe = document.createElement('iframe');
		newframe.id = 'naframe';
		newframe.name = 'naframe';
		newframe.frameBorder = 0;
		newframe.marginWidth = 0;
		newframe.marginHeight = 0;
		newframe.width = '100%';
		newframe.height = '100%';
		newframe.scrolling = 'auto';

		document.getElementById('night-audit-alert').appendChild(newframe);
		newframe.src = filePath+"public/admin/start_night_audit.php";
	}

</script>