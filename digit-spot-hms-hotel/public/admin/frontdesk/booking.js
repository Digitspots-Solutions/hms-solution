//for initiating frontdesk booking

const sqlroomSay = {"error":0}

function redobooking() {
	chgclass('for-booking','fx-position-stick btscr zind-2 motion y-scroll');
	chgclass('inn-for-booking','noshow');
}

function sqlroomCheck(room) {
	
	var sql = "SELECT * FROM guest_occupancy_detail_tbl WHERE roomid="+room+" AND datelogged='"+sqlserver4date+"' AND status IN('CheckedIn','Reserved')";
	
	const sqlconnect = {
		"sql":sql
	}

	var xhr,params,url,ajaxresult;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(sqlconnect);
	
	params = "kyw=idgetsql&dataSend=200&sqlrequestdata="+requestdata;
	url = phpfile+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success == 200) {
					sqlroomSay.error = Number(sqlroomSay.error) + 1;
				}
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

function confirmbooking() {
	if(sessionStorage.getItem('bookingdetails') !== 'undefined' && sessionStorage.getItem('bookingdetails') != '') {

		document.getElementById('confirmbooking').value = 'Processing..';
		document.getElementById('confirmbooking').setAttribute('onclick','');

		var wgt_booking_details = sessionStorage.getItem('bookingdetails');
		var postdatarequest = wgt_booking_details;
		var chkfor = JSON.parse(wgt_booking_details);

		var continue_err = 0;
		var guest = chkfor.guest;

		if(chkfor.bookingclass == 'Checking In') {
			
			for(var g=0; g < guest.length; g++) {
				if(guest[g].room == '' || guest[g].room == 0 || guest[g].room == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].title == '' || guest[g].title == 0 || guest[g].title == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].firstname == '' || guest[g].firstname == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].lastname == '' || guest[g].lastname == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].phone == '' || guest[g].phone == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].room && guest[g].room >= 1) {
					sqlroomCheck(guest[g].room);
				}
			}

		} else {
			for(var g=0; g < guest.length; g++) {
				if(guest[g].title == '' || guest[g].title == 0 || guest[g].title == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].firstname == '' || guest[g].firstname == ' ') {
					continue_err = 1;
					break;
				}

				if(guest[g].lastname == '' || guest[g].lastname == ' ') {
					continue_err = 1;
					break;
				}
				
				if(guest[g].room && guest[g].room >= 1) {
					sqlroomCheck(guest[g].room);
				}
			}
		}

		setTimeout(() => {

			if(continue_err == 0 && sqlroomSay.error == 0) {

				chgclass('fbutton','alignct noshow');
				writeObjheader('fmsg','<div align="center"><div class="loading"></div></div>');
			
				var xhr,params,url,ajaxresult;

				if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
				else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

				params = "kyw=newbooking&postdatarequest="+postdatarequest;
				url = filePath+"public/admin/frontdesk/postbooking.php";
				
				xhr.onreadystatechange=function() {
					if(xhr.readyState == 4) {
						if(xhr.status == 200) {
							//console.log(xhr.responseText);
							ajaxresult = JSON.parse(xhr.responseText);
							if(ajaxresult.success && ajaxresult.success == 200) {
								chgclass('for-booking','fx-position-stick btscr zind-2 motion y-scroll');
								chgclass('inn-for-booking','noshow');
								document.getElementById('workspace').scrollTop = 0;
								window.frame10000.location.href = filePath+'public/admin/frontdesk.php?logs=Front Office';
								var random_number = Math.floor(Math.random() * 1000000);
								wgtiframe(ajaxresult.param,random_number,'reservations');
							} else {
								chgclass('fbutton','alignct');
								writeObjheader('fmsg','<small class="block-element alignct top-push-7">Error: Unable to complete request. Try again</small>');
							}
						} else {
							chgclass('fbutton','alignct');
							writeObjheader('fmsg','<small class="block-element alignct top-push-7">Error: Unable to connect server. Try again</small>');
						}
					}
				};

				xhr.open('POST', url, true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.send(params);

			} else {
				document.getElementById('confirmbooking').value = 'Confirm & Continue';
				document.getElementById('confirmbooking').setAttribute('onclick','confirmbooking()');
				document.getElementById('fmsg').innerHTML = '<small class="block-element alignct top-push-7 red-font">Error: Indicate necessary information for check-in/reservation or change room number</small>';
			}

		},2000);

	} else {
		document.getElementById('confirmbooking').value = 'Confirm & Continue';
		document.getElementById('confirmbooking').setAttribute('onclick','confirmbooking()');
		document.getElementById('fmsg').innerHTML = '<small class="block-element alignct top-push-7 red-font">Error: Unable to continue</small>';
	}
}