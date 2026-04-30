
/* for form submission */

const isfor = {'formid':''}

function wgtFormSubmit(e) {
	
	e.preventDefault();
	
	var butval,wgt_form_data,tbl,jf;
	var xhr,params,url,ajaxresult;

	var thisForm = isfor.formid;
	
	//get initial button value
	butval = document.getElementById('submitbutton').value;

	//get form content
	wgt_form_data = json_get_formdata(thisForm); //console.log(wgt_form_data);
	tbl = jsonformtbl.jtbl;
	jfr = jsonformtbl.jfr;

	htmlpassval('Submitting..','submitbutton');
	htmlFormField('submitbutton','button');
	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	params = "kyw=jsonform&tbl="+tbl+"&jfr="+jfr+"&requestdata="+wgt_form_data;
	url = cphpf+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success && ajaxresult.success == 200) {
					jsClose('fbox');
					writeObjheader('notifybox',reportMsg);
					setTimeout(function() {
						writeObjheader('msgHeader','Post Notification');
						writeObjheader('msgBody','Information has been submitted successfully');
						chgclass('notifybox','fx-position-stick zind-1 motion btscr');
						objDisplay('notifybox');
					},200);
					setTimeout(function() {
						window.location.reload(true);
					},2000);
				} else {
					htmlpassval(butval,'submitbutton');
					htmlFormField('submitbutton','submit');
					writeObjheader('fmessage','Error processing request. Try again');
				}
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}