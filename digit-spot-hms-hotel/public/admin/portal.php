<?php include "../../includes/php_paths.php"; include B2WF_PATH.ROOT_FLD._DB_SERVER_; include B2WF_PATH.ROOT_FLD._DB_TABLES_; include B2WF_PATH.ROOT_FLD._FUNC_; include B2WF_PATH.ROOT_FLD._RQ_FUNC_; include B2WF_PATH.ROOT_FLD._SERVER_CUR_DATE_; include B2WF_PATH.ROOT_FLD._USRP_; include B2WF_PATH.ROOT_FLD._APPMODULES_; ?>

<?php 
	
	sessionIsChecked($_SESSION['page_sid'],'./','session_active_page');
	$userSignedIn = $_SESSION['authenticate_id'];

	include "../../includes/common_data_vars.php";
	include "../../includes/general_routine.php";
	include "night_audit_token.php";
	include "module_operation_privilege.php";

	//linking pages
	$pg_link1 = "logout".PHP_EXT;
	$pg_link2 = "ini.php";

	$_SESSION['app_service'] = $_GET['aps'];

	#----------------------------------------------------------------------------------------------------------------

	if(isset($_POST['passwordbutton']))
	{
		$fieldset1 = escape_data($_POST['fieldset1']);
		$fieldset2 = escape_data($_POST['fieldset2']);

		$insert_dataproperty = array("password"=>sha1($fieldset2));
		$insert_constrain = array("id"=>$userSignedIn);
		$data_inserted = mysqli_data_update($tbL7,$insert_dataproperty,$insert_constrain);

		if(isset($data_inserted) && $data_inserted == 2)
		{
			?> <script> window.location.href = "<?php echo $pg_link1; ?>"; </script> <?php 
		}
	}

	#----------------------------------------------------------------------------------------------------------------

	$app = str_replace('-',' ',strtoupper($_GET['aps']));

?>
<html>
	<head>
		<title><?php echo _CTITLE_; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<link rel="shortcut icon" href="../../theme/images/inc/favicon.png" type="images/x-icon"/>
		<link rel="stylesheet" href="../../style/csslibrary/default.css"/>
		<link rel="stylesheet" href="../../style/custom.css"/>
		<link rel="stylesheet" href="applystyle.css"/>
		<script type="text/javascript" src="../../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../js/jspath.js"></script>
		<script language="javascript" src="../../js/timer.js"></script>
		<script type="text/javascript" src="../../js/jsbk.js"></script>
		<script type="text/javascript" src="../../style/csslibrary/flexcroll.js"></script>
		
		<style>

		.nc-width-79 {
			width: 79%;
		}

		.stick-cs-left-margin {
			margin-left: 25% !important;
			margin-right: 10% !important;
		}

		#chatframe {
			height: 37px;
		}

		#cframeheader {
			border-top-right-radius: 5px;
			border-top-left-radius: 5px;
			-webkit-border-top-right-radius: 5px;
			-webkit-border-top-left-radius: 5px;
		}

		#messaging {
			height: 100% !important;
			padding: 35px 30px 10px 40px !important;
			outline: 0;
		}

		#messaging:focus {
			outline: 0;
		}

		.select-custom-state-1 {
			width: 150px !important;
			padding: 7px !important;
		}


		.menu-mini-photobox {
			width: 45px;
			height: 45px;
		}

		</style>
		
	</head>
	<body id="parent-node" class="white-theme noscroll">
		<div id="container" class="block-element">
			<div id="sidebar" class="ln-display-box float-left nc-width-20 nc-height-100 white-theme obj-dark-shadow" lang="s">
				<span id="icons" class="ln-display-box float-left nc-width-15 nc-height-100 black-theme">
					<div class="nc-height-15 top-pull-30 alignct" title="Slide In/Out">
						<b class="anchor fa-listing fa-sml-size fa-color-strike-2 nobold" onclick="slideInOut()"></b>
					</div>
					<div class="nc-height-85 alignct top-pull-3 left-pull-10">
						<span class="block-element top-push-10 nc-width-60 nc-height-5 noscroll anchor">
						</span>
					</div>
				</span>
				<span id="text-link" class="ln-display-box float-right nc-width-85 nc-height-100 motion">
					<div id="text-link-tp" class="box-border-thick-bottom nc-height-15 top-pull-15 left-pull-10 noscroll alignlt">
						<img src="<?php echo _MF_LOGO_; ?>">
					</div>
					<div id="text-link-bt" class="grey-1-theme nc-height-85 y-scroll">
						<!-- menu list -->
						<div class="block-element top-pull-50 alignct grey-font">No module selected</div>
					</div>
				</span>
			</div>
			<div id="workspace" class="ln-display-box float-right nc-width-79 nc-height-100 white-theme motion y-scroll">
				<!-- menu header -->
				<div id="top-sticky" class="fx-position-stick top-layout right-layout fx-width-80 motion right-push-15">
					<div id="menus" class="left-push-50 white-theme top-pull-7">
						<div class="ln-display-box float-right">
							<ul class="nolist">
								<li class="drop-box top-pull-5 right-pull-5 left-pull-5 white-black-state xsml-rounded-button"><b class="mbri-users"></b><b class="fa-arrow-down nobold left-push-10"></b>
									<div class="th-menu dark-black-theme white-font right-layout xsml-rounded-button minutop5 noscroll">
										<div class="pads30">
											<ul class="nolist">
												<li class="bottom-push-15 ft-sml-size">Logged in: <b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b></li>
												<li class="bottom-push-7 ft-sml-size"><a href="javascript:void(0)" class="overstate-font-one" onclick="load_inbox()">Inbox</a></li>
												<li class="bottom-push-7 ft-sml-size"><a href="javascript:void(0)" class="overstate-font-one" onclick="objDisplay('modal-box-1')">Change Password</a></li>
												<li class="bottom-push-20 ft-sml-size"><a href="javascript:void(0)" class="overstate-font-one" onclick="objDisplay('modal-box-2')">User Profile</a></li>
												<li class="ft-sml-size"><a href="<?php echo $pg_link1; ?>" class="overstate-font-one">Logout <b class="mbri-right float-right"></b></a></li>
											</ul>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="ln-display-box float-left right-push-10">
							<?php echo $moduleMenu; ?>
						</div>
						<div class="block-element new-line-space">
						</div>
					</div>
					<div id="frame-header" class="block-element white-theme top-pull-10 bottom-pull-5 left-push-25 left-pull-15 motion noscroll box-border-thick-bottom">
					</div>
					<div class="block-element new-line-space">
						<!-- clear line -->
					</div>
				</div>
				<!-- body section -->

				<?php include "dashinfo.php"; ?>
				
				<div id="frame-work" class="block-element noscroll">
					<div id="td0" class="top-pull-50">
						<div class="block-element cs-height-20">&nbsp;</div>
						<div class="right-pull-15 left-pull-15">
							<div class="box-border-thick sky-blue-theme sml-rounded-button pads30 bottom-push-20">
								<h4 class="large nobold default-text-font">Welcome</h4>
								<h1 class="xxlarge ft-tahoma"><?php echo SOFTWARE_NAME.': '.$app; ?></h1>
								<p>&nbsp;</p>
								<span class="fx-float-left fx-width-30 pads7">
									<h3 class="large nobold ft-tahoma">Total Reservations</h3>
									<h1 class="xlarge nobold default-text-font-bold"><?php echo number_format($wgt_total_reservations); ?></h1>
								</span>
								<span class="fx-float-left fx-width-30 pads7">
									<h3 class="large nobold ft-tahoma">Total Checked-In</h3>
									<h1 class="xlarge nobold default-text-font-bold"><?php echo number_format($wgt_total_checkedin); ?></h1>
								</span>
								<span class="fx-float-left fx-width-30 pads7">
									<h3 class="large nobold ft-tahoma">Total Checked-Out</h3>
									<h1 class="xlarge nobold default-text-font-bold"><?php echo number_format($wgt_total_checkedout); ?></h1>
								</span>
								<span class="block-element new-line-space">
								</span>
								<span class="fx-float-left fx-width-30 pads7">
									<h3 class="large nobold ft-tahoma">Total Vacant Rooms</h3>
									<h1 class="xlarge nobold default-text-font-bold"><?php echo number_format($wgt_vacant_rooms); ?></h1>
								</span>
								<span class="fx-float-left fx-width-30 pads7">
									<h3 class="large nobold ft-tahoma">Total Dirty Rooms</h3>
									<h1 class="xlarge nobold default-text-font-bold"><?php echo number_format($wgt_dirty_rooms); ?></h1>
								</span>
								<span class="block-element new-line-space">
								</span>
							</div>
							<div class="block-element grey-theme sml-rounded-button pads30">
								<h3 class="large nobold default-text-font">Active Logged User</h3>
								<p>&nbsp;</p>
								<?php include "loggedusers.php"; ?>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" id="curpage" value="0">
				<input type="hidden" id="noofpage" value="0">
				<div class="block-element new-line-space">
					<!-- clear line -->
				</div>
			</div>
			<div class="block-element new-line-space">
				<!-- clear line -->
			</div>
		</div>

		<div id="modal-box-1" class="fx-position-stick fscr zind-2 motion txp5-white noshow" align="center">
			<div class="block-element nc-height-10">&nbsp;</div>
			<div class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 xsml-rounded-button obj-light-shadow motion">
				<form action="" method="post" autocomplete="off">
					<div class="form-block-label alignlt">
						<h4 class="nomargin nobold default-text-font-bold">Change Password</h4><br>
					</div>
					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">User ID</small><br>
						<input type="text" name="fieldset1" id="fieldset1" placeholder="<?php echo strtolower($user_login); ?>" required="required" class="no-back-black" readonly>
					</p>
					<p class="form-block-label">
						<input autocomplete='new-password' type="text" name="fieldset2" id="fieldset2" placeholder="New Password?" required="required" class="no-back-black" onkeyup="htmlFormField('fieldset2','password')">
					</p>
					
					
					<br>
					
					<input type="submit" name="passwordbutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('modal-box'); objHidden('modal-box-1')">Cancel</a>
				</form>
			</div>
		</div>

		<div id="modal-box-2" class="fx-position-stick fscr zind-2 motion txp5-white noshow" align="center">
			<div class="block-element nc-height-10">&nbsp;</div>
			<div class="nc-width-40 white-theme top-pull-30 right-pull-30 bottom-pull-30 left-pull-30 sml-rounded-button obj-light-shadow motion">
				<form action="" method="post" autocomplete="off">
					<div class="form-block-label alignlt">
						<h4 class="nomargin nobold default-text-font-bold">User Detail</h4><br>
					</div>
					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">First & Lastname</small><br>
						<input type="text" name="fieldset3" id="fieldset3" placeholder="Enter name" value="<?php echo $admin_name; ?>" required="required" class="no-back-black">
					</p>
					
					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">Email Address</small><br>
						<input type="text" name="fieldset4" id="fieldset4" placeholder="Enter emailaddress" value="<?php echo $emailaddress; ?>" required="required" class="no-back-black">
					</p>

					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">Mobile Number</small><br>
						<input type="number" name="fieldset5" id="fieldset5" placeholder="Enter mobile number" value="<?php echo $mobile; ?>" required="required" class="no-back-black">
					</p>

					<p class="form-block-label-uline alignlt">
						<small class="grey-2-font">Gender</small><br>
						<select name="fieldset6" id="fieldset6" class="no-back-black">
							<?php if(isset($gender) && !empty($gender)) { ?><option value="<?php echo $gender ?>"><?php echo $gender ?></option><?php } else { ?><option value="">Choose<?php } ?>
							<option value="Male">Male</option><option value="Female">Female</option>
						</select>
					</p>
					
					<br>
					
					<input type="submit" name="userdetailbutton" value="Update" class="submit pads10 black-white-state rounded-button nc-width-40"> &nbsp;&nbsp; <a href="javascript:void(0)" class="steel-blue-font" onclick="objHidden('modal-box'); objHidden('modal-box-2')">Cancel</a>
				</form>
			</div>
		</div>

		<div id="pause-state" class="fx-position-flow fscr zind-1 motion noshow txp5-white" align="center">
			<div class="block-element nc-height-40">&nbsp;</div>
			<div id="pause-state-msg" class="cs-width-350 white-theme obj-shadow pads20"></div>
		</div>


		<div id="inbox-message" class="fx-position-stick zind-2 motion btscr noscroll" align="center">
			<div id="close-inbox" class="noshow" align="right">
				<span class="ln-display-box float-left left-pull-10">
					<h2 class="large nobold"><b class="fa-envelop nobold"></b> Inbox: <b class="nobold default-text-font-bold"><?php echo $admin_name; ?></b></h2>
				</span>
				<span class="ln-display-box float-right top-pull-3 right-pull-10">
					<a href="javascript:void(0)" class="light-red-font ft-sml-size" onclick="chgclass('inbox-message','fx-position-stick zind-2 motion btscr noscroll'); chgclass('msg-frame','noshow white-theme sml-rounded-button noscroll'); chgclass('close-inbox','noshow');">Close X</a>
				</span>
				<span class="ln-display-box float-right top-pull-3 right-pull-20">
					<a href="javascript:void(0)" class="blue-font ft-sml-size default-text-font-bold" onclick="load_inbox()">Return to Inbox</a>
				</span>
				<?php if(isset($allowSeeReview) && $allowSeeReview == 200): ?>
				<span class="ln-display-box float-right top-pull-3 right-pull-20">
					<a href="javascript:void(0)" class="dark-blue-font ft-sml-size default-text-font-bold" onclick="load_review()">Review Signed Request</a>
				</span>
				<?php endif; ?>
				<span class="block-element new-line-space">
				</span>
			</div>
			<div id="msg-frame" class="noshow white-theme sml-rounded-button noscroll">
			</div>
		</div>

		<?php include "changeuserdetail.php"; ?>

		<!--- modal boxes below -->

		<div id="room-rate" class="fx-position-stick btscr zind-2 motion y-scroll" align="center">
			<div id="inn-room-rate" class="noshow">
				<div class="block-element cs-height-100"></div>
				<div class="white-theme cs-width-400 pads20 xsml-rounded-button alignlt">
					<h4 class="large">Room Rate <a href="javascript:void(0)" class="blue-font ft-xxsml-size float-right" onclick="chgclass('room-rate','fx-position-stick btscr zind-2 motion y-scroll'); objHidden('inn-room-rate')">Hide x</a></h4><br>
					<?php include "administration/get_room_rate.php"; ?>
				</div>
			</div>
		</div>


		<div id="weekly-tariff" class="fx-position-stick btscr zind-2 motion y-scroll" align="center">
			<div id="inn-weekly-tariff" class="noshow">
				<div class="block-element cs-height-100"></div>
				<div class="white-theme cs-width-800 pads20 xsml-rounded-button alignlt">
					<h4 class="large">Weekly Tariff <a href="javascript:void(0)" class="blue-font ft-xxsml-size float-right" onclick="chgclass('weekly-tariff','fx-position-stick btscr zind-2 motion y-scroll'); objHidden('inn-weekly-tariff')">Hide x</a></h4><br>
					<?php include "sales/get_weekly_tariff.php"; ?>
				</div>
			</div>
		</div>


		<div id="house-status" class="fx-position-stick btscr zind-2 motion y-scroll" align="center">
			<div id="inn-house-status" class="noshow">
				<div class="block-element cs-height-100"></div>
				<div class="white-theme cs-width-400 pads20 xsml-rounded-button alignlt">
					<h4 class="large">House Status <a href="javascript:void(0)" class="blue-font ft-xxsml-size float-right" onclick="chgclass('house-status','fx-position-stick btscr zind-2 motion y-scroll'); objHidden('inn-house-status')">Hide x</a></h4><br>
					<?php include "housekeeping/get_house_status.php"; ?>
				</div>
			</div>
		</div>


		<div id="checkin-checkout" class="fx-position-stick btscr zind-2 motion y-scroll" align="center">
			<div id="inn-checkin-checkout" class="noshow">
				<div class="block-element cs-height-100"></div>
				<div class="white-theme cs-width-600 pads20 xsml-rounded-button alignlt">
					<h4 class="large">Daily Checkins & Checkouts <a href="javascript:void(0)" class="blue-font ft-xxsml-size float-right" onclick="chgclass('checkin-checkout','fx-position-stick btscr zind-2 motion y-scroll'); objHidden('inn-checkin-checkout')">Hide x</a></h4><br>
					<?php include "frontdesk/get_checkins_checkouts.php"; ?>
				</div>
			</div>
		</div>


		<div id="role-privilege" class="fx-position-stick btscr zind-2 motion y-scroll" align="center" onclick="chgclass('role-privilege','fx-position-stick btscr zind-2 motion y-scroll'); chgclass('inn-role-privilege','noshow');">
			<div id="inn-role-privilege" class="noshow">
				<div class="block-element nc-height-10"></div>
				<div id="th-usr-pr" class="white-theme cs-width-1200 nc-height-80 xsml-rounded-button noscroll">
				</div>
			</div>
		</div>


		<div id="for-booking" class="fx-position-stick btscr zind-2 motion y-scroll" align="center">
			<div id="inn-for-booking" class="noshow">
				<div class="block-element nc-height-10"></div>
				<div id="th-nbk" class="white-theme cs-width-700 xsml-rounded-button pads30 noscroll">
				</div>
			</div>
		</div>


		<div id="for-pop-wins" class="fx-position-stick btscr zind-2 motion" align="center">
			<div id="fmodal" class="noshow">
				<p class="top-pull-20 right-pull-30 bottom-pull-20 alignrt">
					<a href="javascript:void(0)" class="white-font" onclick="closemodalframe()"><b class="mbri-close fa-sml-size"></b></a>
				</p>
				<div id="fmodalwin" class="white-theme xsml-rounded-button cs-height-0 motion noscroll">
				</div>
			</div>
		</div>

		<div id="crframe" class="noshow">
			<a href="javascript:void(0)" id="wgtframe">openframe</a>
			<a href="javascript:void(0)" id="clsframe">closeframe</a>
			<a href="javascript:void(0)" id="newbkg">newbooking</a>
		</div>

		<!--- end -->

		<div id="night-audit-alert" class="noshow motion" align="center">
			<div class="fx-width-50 white-theme pads30 cs-margin-top-100 alignlt">
				<h3 class="large nobold light-red-font">Starting night audit..</h3>
				<h2 id="audit-msg" class="xlarge nobold default-text-font-bold"></h2>
				<p class="top-pull-50 alignct">
					<?php
						if(strtotime($server_get_non_auditdate) >= strtotime($server_get_date)):

							$uncompleted_night_date = date('Y-m-d',strtotime($server_get_non_auditdate.' -1 days'));
							$r_night_query = array("audit_date"=>$uncompleted_night_date,"status"=>"Started");
							$r_audit_sql = array("status"=>"Pending");
							
							mysqli_data_update($tbL136,$r_audit_sql,$r_night_query);
					?>
					
					<b class="light-red-font nobold">Error: Request terminated. You may try later</b>
					
					<?php else: ?>
					
					<span id="start" class="motion"><a id="statr" href="start_night_audit<?php echo PHP_EXT; ?>" class="top-pull-7 right-pull-30 bottom-pull-7 left-pull-30 blue-white-state rounded-button ft-xsml-size default-text-font-bold">Start</a></span>
					
					<?php endif; ?>
					<span id="xclose"><a id="skip" href="javascript: void(0)" class="dark-black-font top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size" onclick="chgclass('night-audit-alert','noshow motion')">Close x</a></span>
				</p>
			</div>
		</div>


			
	</body>
</html>

<script> const sqlserver4date = "<?php echo $server_get_date; ?>"; </script>
<script src="frontdesk/booking.js?ver=1.0"></script>

<script>

const auth = "<?php echo DOMAIN_URL; ?>/login/";
const app_timer = {"timing":0}
const app_stop = 900;

setInterval(() => {
	if(app_timer.timing < app_stop) {

		if(sessionStorage.getItem('workaround') !== null) { var wka = sessionStorage.getItem('workaround'); }

		if(wka == 0) { app_timer.timing = wka; }
		app_timer.timing = Number(app_timer.timing) + 1;

		//console.log(app_timer.timing);
	} else {
		window.location.href = auth;
	}
},1000);


window.addEventListener("mousemove", (event) => {
	var e = event || window.event;
	if(e.clientX || e.clientY) { app_timer.timing = 0; }
});

window.addEventListener("keypress", (event) => {
	var e = event || window.event;
	if(e.code || e.which) { app_timer.timing = 0; }
});

var wgtframe = document.getElementById('wgtframe');
var clsframe = document.getElementById('clsframe');
var newbkg = document.getElementById('newbkg');

wgtframe.addEventListener('click',function() {
	var crframe = sessionStorage.getItem('framesets');
	var crframex = JSON.parse(crframe);
	wgtiframe(crframex.label,crframex.id,crframex.param);
},false);

clsframe.addEventListener('click',function() {
	closemodalframe();
},false);

newbkg.addEventListener('click',function() {
	openpg(10000,1);
	var strings = sessionStorage.getItem('pbk');
	var jstrings = JSON.parse(strings);
	var ifrm = document.getElementById('frame10000');
	var doc = ifrm.contentDocument? ifrm.contentDocument:ifrm.contentWindow.document;
	doc.getElementById('get-search-result').innerHTML = '';
	if(doc.getElementById('search-box').lang == 'collapsed') { doc.getElementById('qsh').click(); }
	
	if(jstrings.bookingtype == 'individual') {
		doc.getElementById('type-1').click();
		doc.getElementById('type-2').disabled='disabled';
		doc.getElementById('type-4').disabled='disabled';

		if(jstrings.billtype == 'Guest') { doc.getElementsByName('payment-by')[1].checked = true; }
		else if(jstrings.billtype == 'Group') { doc.getElementsByName('payment-by')[0].checked = true; }
	} else if(jstrings.bookingtype == 'corporate') {
		doc.getElementById('type-2').click();
		doc.getElementById('type-1').disabled='disabled';
		doc.getElementById('type-4').disabled='disabled';
		//setTimeout(() => { alert('Please select the same corporate name highlighted at the right side of the screen before choosing room-type'); },1500);
		var actname;
		actname = (jstrings.guestname).split('<br>');
		actname = actname[1].replace('(',''); actname = actname.replace(')','');
		doc.getElementById('cspg').removeAttribute('onchange');
		doc.getElementById('cspg').innerHTML = '<option value="'+jstrings.billto+'">'+actname+'</option>';
		doc.getElementById('for-cspg').value = actname;
		doc.getElementById('for-cspg').setAttribute('readonly','readonly');
		doc.getElementById('for-cspg').removeAttribute('oninput');
		doc.getElementById('for-cspg').removeAttribute('onfocus');

		if(jstrings.billtype == 'Guest') { doc.getElementsByName('payment-by2')[1].checked = true; }
		else if(jstrings.billtype == 'Corporate') { doc.getElementsByName('payment-by2')[0].checked = true; }

		sqldatastring.sql = "SELECT * FROM cspg_tbl WHERE id="+jstrings.billto;
		sqldataQuery(wgtpop,sqldatastring);

		function wgtpop(response) {
			var i, vhtml, data, ajaxresult = JSON.parse(response);
			data = ajaxresult.datastring;
			doc.getElementById('cspgcrlimit').value = data[0]['xcreditlimit'];
			doc.getElementById('cspgcrbal').value = data[0]['notifylimit'];
		}
	} else if(jstrings.bookingtype == 'complimentary') {
		doc.getElementById('type-4').click();
		doc.getElementById('type-1').disabled='disabled';
		doc.getElementById('type-2').disabled='disabled';
	}

	doc.getElementById('onbH').value = jstrings.bookingno;
	doc.getElementById('owner-addon-booking').innerHTML = '<div class="box-border-thick-red pads15 bottom-push-10 xsml-rounded-button"><h4 class="large nobold black-font bottom-pull-7">Note: This booking will be added to:</h4><h3 class="large nobold default-text-font-bold nomargin">'+jstrings.guestname+'<b class="fa-arrow-right nobold left-push-10 right-push-7"></b>'+jstrings.bookingno+'</h3></div>';
},false);


function startNightAudit() {

	chgclass('night-audit-alert','fx-position-stick fscr zind-1 txp5-black motion noscroll');

	var auditdonelast = "<?php echo $server_get_auditdate; ?>";
	var auditime = "<?php echo $night_audit_time; ?>";
	var jsontime = <?php echo $timeArr; ?>;
	var day, hr, min, year, mth, month, date = new Date;

	day = date.getDate();
	hr = date.getHours();
	min = date.getMinutes();
	year = date.getFullYear();
	mth = eval(date.getMonth()) + 1;

	if(mth < 10) { month = '0'+mth; } else { month = mth; }

	var systime = year+'-'+month+'-'+day+' '+hr+':'+min+':00';

	var diff = Math.abs(new Date(auditime) - new Date(systime));
	var minutes = Math.floor((diff/1000)/60);

	var nstr = new String(jsontime[3]);

	//console.log(jsontime);
	
	if(eval(jsontime[3]) > 0 && nstr.length <= 3) {
		writeObjheader('audit-msg','Night audit cannot start at the moment. Please try again later on/after '+auditime);
		chgclass('start','noshow');
		chgclass('skip','blue-font top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size');
	} else {
		if(auditdonelast !== null && auditdonelast != '') { writeObjheader('audit-msg','Night Audit Done Till: '+auditdonelast+'<br><br><b class="nobold">Click on the start button to begin night audit. No post will be allowed during this process</b>'); } else { writeObjheader('audit-msg','Click on the start button to begin night audit. No post will be allowed during this process'); }
		chgclass('start','motion');
		chgclass('skip','dark-black-font top-pull-7 right-pull-20 bottom-pull-7 left-pull-20 ft-xsml-size');

		sessionStorage.setItem('auditsys','yes');
	}
}


function foStopostbill() {
	
	var divpop = document.createElement('div');

	var pstbil = setInterval(() => {
		if(document.getElementById('fo-mode') && (sessionStorage.getItem('auditsys') == undefined || sessionStorage.getItem('auditsys') == null)) {
			
			var xhr,url;

			if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
			else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }
		 	
			url = phpfile+"dbquery.php?r=check-for-night-audit&dataSend=200";

			xhr.onreadystatechange=function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						if(xhr.responseText == 'Running') {
							if(!document.getElementById('blockpsb')) {
								divpop.setAttribute('id','blockpsb');
								divpop.className = 'fx-position-stick zind-1 right-layout bottom-layout right-push-30 txp8-white nc-width-75 nc-height-85 pads30 ft-xxlarge-size black-font default-text-font-bold';
								document.body.appendChild(divpop);
								divpop.innerHTML = 'Night audit has started. We recommend not to post any bill at this moment...';
							}
						} else {
							if(document.getElementById('blockpsb')) {
								document.body.removeChild(divpop);
							}
						}
					}
				}
			};

			xhr.open('GET', url, true);
			xhr.send();
		}
	},120000);
}

window.addEventListener('load', function() {
	date_time('ctimer');
	get_fx_fo_default(); get_fx_hsk_default();
	get_fx_recr_default(); get_fx_mtc_default();
	foStopostbill();
	setTimeout(nwfeed('<?php echo $userSignedIn; ?>'),3000);
	setInterval(function() { nwfeed('<?php echo $userSignedIn; ?>'); },120000);
},false);

</script>