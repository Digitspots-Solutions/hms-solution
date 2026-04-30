//js file by WebProx Technologies

function jsText2Speech(msg) {
	
	var getmsg, speech;

	getmsg = document.getElementById(msg).innerHTML;

	speech = new SpeechSynthesisUtterance();
	speech.lang = "en-US";
	speech.text = getmsg;
	speech.volume = 1;
	speech.rate = 1;
	speech.pitch = 1;

	window.speechSynthesis.speak(speech);
}

//-----------------------------------------------------------------------------------------------end function

function jsdate() {
	
	var date = new Date;

	var day = date.getDate();
	var month = eval(date.getMonth()) + 1;
	var year = date.getFullYear();

	if(month < 10) { month = '0'+month; }
    if(day < 10) { day = '0'+day; }

    var hr = date.getHours();
    var min = date.getMinutes();
    var sec = date.getSeconds();

    if(min < 10) { min = '0'+min; }
    if(sec < 10) { sec = '0'+sec; }

    return day+'/'+month+'/'+year+' '+hr+':'+min+':'+sec;
}

//-----------------------------------------------------------------------------------------------end function

function todecimal(numbr,decimal)
{
	var aux = Math.pow(10, decimal), float_val = Math.round(numbr * aux) / aux;
	return float_val;
}

//-----------------------------------------------------------------------------------------------end function

function tofixe(numbr,decimal)
{
	if(numbr.indexOf('.') > -1) { return numbr.toFixed(decimal); }
	else { return numbr; }
}

//-----------------------------------------------------------------------------------------------end function

function convertoShortdate(isdate) {
	
    var dt = new Date(isdate),
    month = '' + (dt.getMonth() + 1),
    day = '' + dt.getDate(),
    year = dt.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [day, month, year].join('/');
}

//-----------------------------------------------------------------------------------------------end function

function pwd(obj) {
	if(document.getElementById(obj).lang == 'hide') {
		if(document.getElementById(obj).value !== '') {
			htmlFormField(obj,'text');
			document.getElementById(obj).lang = 'show';
			document.getElementById('icon').title = 'Hide';
			chgclass('c-icon','red-font motion');
		}
	} else if(document.getElementById(obj).lang == 'show') {
		htmlFormField(obj,'password');
		document.getElementById(obj).lang = 'hide';
		document.getElementById('icon').title = 'Show';
		chgclass('c-icon','black-font motion');
	}
}

//-----------------------------------------------------------------------------------------------end function

function pwdStrength(xpwd) {
	var pwds = document.getElementById(xpwd).value;
	var i, numbr = 2, uppxter = 0, xter, noofxters = pwds.length;
	
	if(noofxters >= 1) {
		
		for(i=0; i < noofxters; i++) {
			xter = pwds.substr(i,1);
			if(xter.toUpperCase() === xter) { uppxter += 1; }
		}

		if(uppxter >= 1 && noofxters >= 8) { numbr = eval(numbr) + 48; }
		if(pwds.indexOf('@') > -1 || pwds.indexOf('%') > -1 || pwds.indexOf('$') > -1) { numbr = eval(numbr) + 30; }
		if(pwds.indexOf(0) > -1 || pwds.indexOf(1) > -1 || pwds.indexOf(2) > -1 || pwds.indexOf(3) > -1 || pwds.indexOf(4) > -1 || pwds.indexOf(5) > -1 || pwds.indexOf(6) > -1 || pwds.indexOf(7) > -1 || pwds.indexOf(8) > -1 || pwds.indexOf(9) > -1) { numbr = eval(numbr) + 20; }

		if(numbr > 0 && numbr < 70) { chgclass('pwd-strength','cs-height-3 red-theme motion'); htmlFormField('sbt','button'); }
		else if(numbr >= 70 && numbr <= 100) { chgclass('pwd-strength','cs-height-3 forest-green-theme motion'); htmlFormField('sbt','submit'); }
		document.getElementById('pwd-strength').style.width = numbr+'%';

	} else {
		chgclass('pwd-strength','csp-width-0 cs-height-3 motion');
	}
}

//-----------------------------------------------------------------------------------------------end function

function loadpage(pg)
{
	var page = (pg).split('-');
	var pgstart = page[0];
	var pglimit = page[1];
	var cpg = page[2];

	var cUri,uri = window.location.href;
	
	if(sessionStorage.getItem('weburi') == null) { sessionStorage.setItem('weburi',uri); }
	setTimeout(function() { cUri = sessionStorage.getItem('weburi'); window.location = cUri+'&pg='+cpg+'&start='+pgstart+'&limit='+pglimit; },100);
}

//-----------------------------------------------------------------------------------------------end function

function autohidePopupBox(obj,timer)
{
	var op = 50;
	
	var stopafter = setInterval(function () { 
	
	if(op >= 50)
	{
		clearInterval(stopafter);
	}
		
	document.getElementById(obj).style.display='none';
	
	op += op * 1;
	
	}, timer);
}

function xautohidePopupBox(obj,timer,style)
{
	setTimeout(function() {
		chgclass(obj,style);
	},timer);
}

//-----------------------------------------------------------------------------------------------end function

function pageLoader(obj,fil)
{
	if(obj !='' && fil !='')
	{
		$("#"+obj).load(filePath+fil);
	}
}

//-----------------------------------------------------------------------------------------------end function

function objDisplay(obj)
{
	if(document.getElementById(obj))
	{
		document.getElementById(obj).style.display='block';
	}
}

//-----------------------------------------------------------------------------------------------end function

function objHidden(obj)
{
	if(document.getElementById(obj))
	{
		document.getElementById(obj).style.display='none';
	}
}

//-----------------------------------------------------------------------------------------------end function

function htmlpassval(str,obj)
{
	document.getElementById(obj).value = str;
}

//-----------------------------------------------------------------------------------------------end function

function htmlpassphl(str,obj) {
	document.getElementById(obj).placeholder = str;
}

//-----------------------------------------------------------------------------------------------end function

function disableBarcodeAutoSubmit()
{
  return !(window.event && window.event.keyCode == 13);
}

//-----------------------------------------------------------------------------------------------end function

function htmlFormField(obj,str)
{
	document.getElementById(obj).type = str;
}

//-----------------------------------------------------------------------------------------------end function

function formrequired(str)
{
	if(document.getElementById(str))
	{
		document.getElementById(str).required = true;
	}
}

//-----------------------------------------------------------------------------------------------end function

function formNonrequired(str)
{
	if(document.getElementById(str))
	{
		document.getElementById(str).required = false;
	}
}

//-----------------------------------------------------------------------------------------------end function

function writeObjheader(obj,text)
{
	document.getElementById(obj).innerHTML = text;
}

//-----------------------------------------------------------------------------------------------end function

function htmlFormReset(str)
{
	document.getElementById(str).reset();
}

//-----------------------------------------------------------------------------------------------end function

function unbeeper()
{
	var audio = new Audio(filePath+'admin/media/beep-02.mp3');
	audio.play();
}

//-----------------------------------------------------------------------------------------------end function

function forsubmenu(container,list,xlist,fsclass,ssclass,xfsclass,xssclass)
{
	var c = document.getElementById(container);

	if(c.lang == 'uncollapsed') {
		document.getElementById(list).className = fsclass;
		if(document.getElementById(xlist)) { document.getElementById(xlist).className = ssclass; }
		c.lang = 'collapsed';
	} else if(c.lang == 'collapsed') {
		document.getElementById(list).className = xfsclass;
		if(document.getElementById(xlist)) { document.getElementById(xlist).className = xssclass; }
		c.lang = 'uncollapsed';
	}
}

//-----------------------------------------------------------------------------------------------end function

function chgclass(obj,xclass)
{
	if(document.getElementById(obj)) {
		document.getElementById(obj).className = xclass;
	}
}

//-----------------------------------------------------------------------------------------------end function

function chgborder(tag,thickness,color,side) {
	if(document.getElementById(tag)) {
		if(side == 0) { document.getElementById(tag).style.border = thickness+'px solid '+color; }
		else if(side == 1) { document.getElementById(tag).style.borderTop = thickness+'px solid '+color; }
		else if(side == 2) { document.getElementById(tag).style.borderRight = thickness+'px solid '+color; }
		else if(side == 3) { document.getElementById(tag).style.borderBottom = thickness+'px solid '+color; }
		else if(side == 4) { document.getElementById(tag).style.borderLeft = thickness+'px solid '+color; }
	}
}

//-----------------------------------------------------------------------------------------------end function

function noapx(elem,state) {
	writeObjheader(elem,'');
	chgclass(elem,state);
}

//-----------------------------------------------------------------------------------------------end function

function textodate(obj)
{
	if(document.getElementById(obj)) {
		document.getElementById(obj).type='date';
		setTimeout(function() { document.getElementById(obj).click(); },200);
	}
}

//-----------------------------------------------------------------------------------------------end function

function textotime(obj)
{
	if(document.getElementById(obj)) {
		document.getElementById(obj).type='time';
		setTimeout(function() { document.getElementById(obj).click(); },200);
	}
}

//-----------------------------------------------------------------------------------------------end function

function sqldataQuery(jsoncall,string)
{
	var xhr,params,url,ajaxresult;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(string);
	
	params = "kyw=idgetsql&sqlrequestdata="+requestdata;
	url = cphpf+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				//console.log(xhr.responseText);
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success && ajaxresult.success == 200) {
					jsoncall(xhr.responseText);
				}
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function json_getdata(jsonCall,tbl,dataid,colkey,val,pack)
{
	var xhr,params,url,ajaxresult;

	if(tbl !== null && dataid !== null && colkey !== null && val !== null) {

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		var dataString = {
			"tableSrc": tbl,
			"dataCallid": dataid,
			"columnKey": colkey,
			"valueObj": val,
			"datapack": pack
		};

		JSONString = JSON.stringify(dataString);

		params = "kyw=idgetvalue&requestdata="+JSONString;
		url = cphpf+"post_form_data.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					//jsonCall(xhr.responseText);
					var temptkn, jtemptkn, get_temptkn = sessionStorage.getItem('temptkn');
					temptkn = JSON.parse(get_temptkn);
					if(pack == 'array') { temptkn[jsonCall] = JSON.parse(xhr.responseText); }
					else if(pack == 'scalar') { temptkn[jsonCall] = xhr.responseText; }
					jtemptkn = JSON.stringify(temptkn); sessionStorage.setItem('temptkn',jtemptkn);
				}
			}
		};

		xhr.open('POST', url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}
}

//-----------------------------------------------------------------------------------------------end function

function idget_val(tbl,dataid,colkey,val,pack)
{
	var xhr,params,url,ajaxresult;

	if(tbl !== null && dataid !== null && colkey !== null && val !== null && pack !== null) {

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		var dataString = {
			"tableSrc": tbl,
			"dataCallid": dataid,
			"columnKey": colkey,
			"valueObj": val,
			"datapack": pack
		};

		JSONString = JSON.stringify(dataString);

		params = "kyw=idgetvalue&requestdata="+JSONString;
		url = cphpf+"post_form_data.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) { suggestions.push(xhr.responseText); }
			}
		};

		xhr.open('POST', url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}
}

//-----------------------------------------------------------------------------------------------end function

function wgtdata(jsoncall,string) {
	
	var xhr,url,params,ajaxresult;
	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(string);
	params = "kyw=dbvalue&kywdata="+requestdata;
	url = cphpf+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				//console.log(xhr.responseText);
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success && ajaxresult.success == 200) {
					jsoncall(xhr.responseText);
				}
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function wgtarry(jsoncall,string) {
	
	var xhr,url,params,ajaxresult;
	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(string);
	params = "qpst=array-get-list&kywdata="+requestdata;
	url = cphpf+"wgtarry.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				//console.log(xhr.responseText);
				jsoncall(xhr.responseText);
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function wgtarrykey(string) {
	
	var xhr,url,params,ajaxresult;
	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(string);
	params = "qpst=array-get-only-key-exist&kywdata="+requestdata;
	url = cphpf+"wgtarry.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				var ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success == 200) { arrygets.push(ajaxresult.dataval); }
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function numberFormat(numbr)
{
	var numb = numbr.toString().split(".");
	numb[0] = numb[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	if(numb[1] && numb[1] != '') { return numb.join("."); }
	else { return numb+'.00'; }
}

//-----------------------------------------------------------------------------------------------end function

function numberinputFormat(numbr,obj,objx)
{
	var returnum, tostring;

	returnum = numbr.replace(/,/g,'');

	if(returnum.length == 4) { tostring = returnum.replace(returnum.substr(0,1),returnum.substr(0,1)+','); }
	else if(returnum.length == 5) { tostring = returnum.replace(returnum.substr(0,2),returnum.substr(0,2)+','); }
	else if(returnum.length == 6) { tostring = returnum.replace(returnum.substr(0,3),returnum.substr(0,3)+','); }
	else if(returnum.length == 7) { tostring = returnum.replace(returnum.substr(0,1),returnum.substr(0,1)+','); tostring = tostring.replace(tostring.substr(2,3),tostring.substr(2,3)+','); }
	else if(returnum.length == 8) { tostring = returnum.replace(returnum.substr(0,2),returnum.substr(0,2)+','); tostring = tostring.replace(tostring.substr(3,3),tostring.substr(3,3)+','); }
	else { tostring = returnum; }

	if(document.getElementById(obj)) { document.getElementById(obj).value = tostring; }
	if(document.getElementById(objx)) { document.getElementById(objx).value = returnum; }
}

//-----------------------------------------------------------------------------------------------end function

function telephoneinputFormat(numbr,obj,objx)
{
	var returnum, tostring;

	returnum = numbr.replace(/ /g,'');

	if(returnum.length == 5 || returnum.length == 6 || returnum.length == 7) { tostring = returnum.replace(returnum.substr(0,4),returnum.substr(0,4)+' '); }
	else if(returnum.length == 9 || returnum.length == 10 || returnum.length == 11 || returnum.length == 12 || returnum.length == 13) { tostring = returnum.replace(returnum.substr(0,4),returnum.substr(0,4)+' '); tostring = tostring.replace(tostring.substr(5,3),tostring.substr(5,3)+' '); }
	else { tostring = returnum; }

	if(document.getElementById(obj)) { document.getElementById(obj).value = tostring; }
	if(document.getElementById(objx)) { document.getElementById(objx).value = returnum; }
}

//-----------------------------------------------------------------------------------------------end function

function json_get_formdata(form) {

	var formElements = document.getElementById(form).elements;    
	var i,jsonObj,postData={};
	
	for(i=0; i<formElements.length; i++) {
	    if(formElements[i].type != 'submit') {
	        postData[formElements[i].name]=formElements[i].value;
	    }
	}

	jsonObj = JSON.stringify(postData);

	return jsonObj;
}

//-----------------------------------------------------------------------------------------------end function

function openfilebox(obj) {
	if(document.getElementById(obj)) {
		document.getElementById(obj).click();
	}
}

//-----------------------------------------------------------------------------------------------end function

function onsubmt(button,msg) {
	document.getElementById(button).value = msg;
}

//-----------------------------------------------------------------------------------------------end function

function wgtfsubmit(form,task) {
	if(task != '') { htmlpassval(task,'ftask'); }
	setTimeout(function() { document.getElementById(form).submit(); },200);
}

//-----------------------------------------------------------------------------------------------end function

function xwgtfsubmit(form,task,obj) {
	if(task != '') { htmlpassval(task,obj); }
	setTimeout(function() { document.getElementById(form).submit(); },200);
}

//-----------------------------------------------------------------------------------------------end function

function titleCase(str,obj) {
	var word = str.replace(/\b\w+/g,function(s){return s.charAt(0).toUpperCase() + s.substr(1).toLowerCase();});
	htmlpassval(word,obj);
}

function wtitleCase(char) {
	var word = char.replace(/\b\w+/g,function(s){return s.charAt(0).toUpperCase() + s.substr(1).toLowerCase();});
	return word;
}

//-----------------------------------------------------------------------------------------------end function

function upperCase(str,obj) {
	var word = str.replace(/\b\w+/g,function(s){return s.toUpperCase();});
	htmlpassval(word,obj);
}

//-----------------------------------------------------------------------------------------------end function

function wgtask(id,task) {
	var ccurl, wurl = window.location.href;
	if(sessionStorage.getItem('adbar') == '' || sessionStorage.getItem('adbar') == null) { sessionStorage.setItem('adbar',wurl); ccurl = wurl; }
	else { ccurl = sessionStorage.getItem('adbar'); }

	window.location.href = ccurl+'&wtask='+task+'&rw='+id+'&#win';
}

//-----------------------------------------------------------------------------------------------end function

function nth_default() {
	window.location.href = window.location.href;
}

//-----------------------------------------------------------------------------------------------end function

function rhome(dirs) {
	window.location.href = curl+dirs+"workspace.php?logs=&tag=";
}

//-----------------------------------------------------------------------------------------------end function

function csvExcel() {
	window.location = curl+'includes/csv_excel.php';
}

//-----------------------------------------------------------------------------------------------end function

function mousexy_coord(event) {
	var e = event || window.event;
	var x = e.clientX;
  	var y = e.clientY;
  	//console.log(x); console.log(y);
  	sessionStorage.setItem('mx',x);
  	sessionStorage.setItem('my',y);
  	return x+','+y;
}

//-----------------------------------------------------------------------------------------------end function

function showPushnotification(title,pushicon,msg,token,init) {
	var notifikasi = new Notification(title, {
		icon: curl+pushicon,
		body: msg,
		vibrate: true
	});

	notifikasi.onclick = function() {
		if(init == '_self') {
			var xframe = document.getElementById('xframe');

			chgclass('pause-page','fx-position-stick zind-1 fscr txp9-black motion noscroll');
			writeObjheader('pause-page','<div class="nc-height-40"></div><div class="loading"></div>');

			xframe.src = curl+publ+"admin/workspace"+exts+"?logs=Newsfeed&tag=Feed&token="+token;
			xframe.onload = function() {
				chgclass('pause-page','fx-position-flow btscr motion noscroll');
				writeObjheader('pause-page','');
			}
		} else if(init == '_blank') {
			window.open(curl+publ+"/admin/workspace"+exts+"?logs=Newsfeed&tag=Feed&token="+token,"_blank");
		}

		notifikasi.close();     
	};
}

//-----------------------------------------------------------------------------------------------end function

function wgt_token(container) {

	var xhr,randnum,url,ajaxresult;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }
 	
 	randnum = Math.floor(Math.random() * 1000000) + 1;
	url = cphpf+"wgtform.php?form=xform-security-token&rand="+randnum;

	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success && ajaxresult.success == 200) {
					htmlpassval(ajaxresult.token,container);
				}
			}
		}
	};

	xhr.open('GET', url, true);
	xhr.send();
}

//-----------------------------------------------------------------------------------------------end function

function wgt_login(e) {

	e.preventDefault();
	
	var init,wgt_form_data;
	var xhr,params,url,ajaxresult;
	
	htmlFormField('logbutton','button');
	htmlpassval('Verifying..','logbutton');

	wgt_form_data = json_get_formdata('login');
	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	params = "kyw=accountlogin&requestdata="+wgt_form_data;
	url = cphpf+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				console.log(xhr.responseText);
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success && ajaxresult.success == 200) {
					chgclass('fmessage','motion');
					writeObjheader('fmessage','');
					htmlpassval('Signing you in..','logbutton');
					if(ajaxresult.wgt_accs && ajaxresult.wgt_accs == 1) { window.location = curl+'workspace/admin/'; }
					else if(ajaxresult.wgt_accs && ajaxresult.wgt_accs == 2) { sessionStorage.setItem('uapp','apu200'); window.location = curl+'workspace/'; }
					else { htmlpassval('Restarting..','logbutton'); }
				} else {
					htmlFormField('logbutton','submit');
					htmlpassval('Log In','logbutton');
					chgclass('fmessage','box-border-thick xxsml-rounded-button pads7 top-push-20 bottom-push-7 ft-xsml-size light-red-font alignct motion');
					writeObjheader('fmessage','Invalid username or password!');
				}
			}
		}
	};

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function autoSuggest(obj) {
	
	var txtarea,curPos,content,txt,lastxtPos,newtxt,txtmatch;
	
	txtarea = document.getElementById(obj);
	curPos = txtarea.selectionStart;
	content = txtarea.value;
	content = content.substr(0,curPos);
	txt = content.split(' ');
	lastxtPos = (txt.length) - 1;

	if(txt[lastxtPos] != '' && txt[lastxtPos] !== null) {
		txtmatch = searchStringInArray(txt[lastxtPos],suggestions);
		if(txtmatch != 'nomatch') {
			newtxt = content.replace(txt[lastxtPos],txtmatch);
			txtarea.value = newtxt;
			setTimeout(function() { txtarea.selectionEnd = curPos; },200);
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

function searchStringInArray(str,strArray) {
    var j,s,sstring;
    for(j=0; j<strArray.length; j++) {
    	sstring = strArray[j].split(' ');
    	for(s=0; s<sstring.length; s++) { if(sstring[s].indexOf(str) == 0) { return sstring[s]; break; } }
    }
    return "nomatch";
}

//-----------------------------------------------------------------------------------------------end function

function fSfocus(flegend,fset,typef) {
	formjS.ky = document.getElementById('field'+typef).placeholder;
	chgclass('l'+flegend,'xfadeout ft-sml-size motion isbold6');
	chgborder('fs'+fset,1,'#4169e1',0);
	htmlpassphl('','field'+typef);
}

function fSblur(flegend,fset,typef) {
	if(document.getElementById('field'+typef).value == '') {
		var wgt_phl = formjS.ky;
		chgclass('l'+flegend,'fx-position-flow bottom-hide xfadein motion');
		chgborder('fs'+fset,1,'#c1c1c1',0);
		htmlpassphl(wgt_phl,'field'+typef);
	} else {
		chgborder('fs'+fset,1,'#c1c1c1',0);
	}
}

//-----------------------------------------------------------------------------------------------end function

function popmodalframe(pfx,param,token1,token2,w,h) {

	var twidth = 'cs-width-'+w, theight = 'cs-height-'+h;

	parent.document.getElementById('for-pop-wins').className = 'fx-position-stick fscr zind-2 motion txp5-black y-scroll';
	parent.document.getElementById('fmodal').className = 'block-element';
	parent.document.getElementById('fmodalwin').className = 'white-theme xsml-rounded-button '+twidth+' '+theight+' bottom-push-30 motion noscroll';

	var newframe = document.createElement('iframe');
	newframe.setAttribute('id', 'modalframe'); // assign an id
	newframe.name = 'modalframe';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '100%';
	newframe.scrolling = 'auto';
	parent.document.getElementById('fmodalwin').appendChild(newframe);
	parent.modalframe.location.href = curl+'public/admin/workspacex.php?logs=modals&prefix='+pfx+'&param='+param+'&ftoken='+token1+'&stoken='+token2;
}

//-----------------------------------------------------------------------------------------------end function

function closemodalframe() {
	chgclass('for-pop-wins','fx-position-stick btscr zind-2 motion');
	chgclass('fmodal','noshow');
	chgclass('fmodalwin','white-theme xsml-rounded-button cs-height-0 motion noscroll');
	writeObjheader('fmodalwin','');
}

//-----------------------------------------------------------------------------------------------end function

function xfSfocus(flegend,fset,typef) {
	formjS.ky = document.getElementById('field'+typef).placeholder;
	chgclass('l'+flegend,'xfadeout ft-sml-size motion isbold6');
	htmlpassphl('','field'+typef);
}

function xfSblur(flegend,fset,typef) {
	if(document.getElementById('field'+typef).value == '') {
		var wgt_phl = formjS.ky;
		chgclass('l'+flegend,'fx-position-flow bottom-hide xfadein motion');
		htmlpassphl(wgt_phl,'field'+typef);
	}
}

//-----------------------------------------------------------------------------------------------end function

const dropbox_selectors = {};

function filtersearch(id) {
	var obj = document.getElementById(id), typed = wtitleCase(obj.value);
	var sufx = id.replace('for-','');

	chgclass('list-'+sufx,'top-push-5');
	document.getElementById(sufx).setAttribute('size','5');
	var dataSelector = document.getElementById(sufx).getElementsByTagName('option');
	var i, cap, nocap, options = dataSelector.length;

	if(obj.value !== null && obj.value != '') {
		nocap = ''; cap = '<option value=""></option>';
		for(i=0; i<options; i++) {
			if((dataSelector[i].text).indexOf(typed) > -1) {
				cap += '<option value="'+dataSelector[i].value+'">'+dataSelector[i].text+'</option>';
			} else {
				if(dataSelector[i].value !== null && dataSelector[i].value != '') {
					nocap += '<option value="'+dataSelector[i].value+'">'+dataSelector[i].text+'</option>';
				}
			}
		}
		document.getElementById(sufx).innerHTML = cap+nocap;
	}
}

function filterselect(id) {
	var obj = document.getElementById(id);
	var caption = "for-"+id, captionbox = document.getElementById(caption);

	chgclass('list-'+id,'noshow');
	obj.removeAttribute('size');

	captionbox.value = obj.options[obj.selectedIndex].text;
	//console.log(dropbox_selectors.selectedIndexes);
}

//-----------------------------------------------------------------------------------------------end function

function uploadphoto(obj,curl,fupl) {
	
	var xhr,url,params;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	params = 'kyw=imageupload&uridata='+curl+'&data='+fupl;
	url = cphpf+"upload_image.php";
	
	xhr.onreadystatechange = () => {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById(obj).value = xhr.responseText;
				document.getElementById('fmsg').innerHTML = 'image is uploaded';
			}
		}
	}
	
	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function resizeimage(e,w,h,todata,upl,fupl,showimage) {
	
	var elem = e.target.id;
	var width = w;
	var height = h;

	 var fr=new FileReader();
	 fr.onload=function(e){
	  var img=new Image();
	  img.onload=function(){
		var MAXWidthHeight= width;
	   var r=MAXWidthHeight/Math.max(this.width,this.height),
	   w=Math.round(this.width*r),
	   h=Math.round(this.height*r),
	   c=document.createElement("canvas");
	   c.width=w;c.height=h;
	   c.getContext("2d").drawImage(this,0,0,w,h);
	   
	   var raw_image_data = c.toDataURL().replace(/^data\:image\/\w+\;base64\,/, '');
		
		if(upl == 'upload') {
			uploadphoto(todata,raw_image_data,fupl);
			document.getElementById(showimage).innerHTML = "<img src="+e.target.result+" width='100%'>";
			document.getElementById('fmsg').innerHTML = 'Uploading..';
		} else {
			document.getElementById(todata).value = raw_image_data;
			document.getElementById(showimage).innerHTML = "<img src="+e.target.result+" width='100%'>";
			document.getElementById('fmsg').innerHTML = 'image is ready for upload!';
		}
	  }
	  img.src=e.target.result;
	 }
	 
	fr.readAsDataURL(e.target.files[0]);
}

//-----------------------------------------------------------------------------------------------end function

function jsCreateform(formcontainer,formobj) {
	
	var htmlForm, fElements = formobj;
	
	if(fElements.hasOwnProperty('formStyle') == true && fElements.formName != '') {
	
		htmlForm = '<form id="'+fElements.formName+'" action="" method="'+fElements.formSend+'" onsubmit="'+fElements.formFx+'" autocomplete="off">';
		htmlForm += '<input type="hidden" name="dbTbl" id="dbTbl" value="'+fElements.formTbl+'">';
		htmlForm += '<input type="hidden" name="fToken" id="fToken" value="'+fElements.formToken+'">';
		htmlForm += '<input type="hidden" name="dtProcess" id="dtProcess" value="'+fElements.formData+'">';
		
		//formStyle: standard,standard-image,datasheet
		
		if(fElements.formStyle == 'standard') {
			
			var i, objs = fElements.formList, numbr = objs.length;
			
			for(i=0; i < numbr; i++) {
				var n, wgf = objs[i].fields, boxsize = 100 / wgf;
				var widgets = objs[i].widget, ef;
				
				if(objs[i].design == 'N/A' || objs[i].design == '') {
					for(ef=0; ef < wgf; ef++) {
						htmlForm += '<input type="hidden" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" '+widgets[ef].docAttr+'>';
					}
				} else {
					if(fElements.formName == 'login') { htmlForm += '<div class="bottom-push-3">'; }
					else { htmlForm += '<div id="rowf'+i+'" class="fx-position-rel motion bottom-push-15 noscroll">'; }
					
					if(objs[i].design == 'fieldset') {
						
						htmlForm += '<fieldset id="fs1'+i+'" class="bbx">';
						htmlForm += '<legend id="l1'+i+'" class="fx-position-flow bottom-hide xfadein motion">'+objs[i].label+'</legend>';
						
						for(ef=0; ef < wgf; ef++) {
							
							htmlForm += '<div class="inline-flex-element top-pull-10 right-pull-5 bottom-pull-10 left-pull-5" style="width: '+boxsize+'%">';
							
							if(widgets[ef].docElem != 'textarea' && widgets[ef].docElem != 'datalist') { htmlForm += '<input onfocus="fSfocus(1'+i+',1'+i+',1'+i+ef+')" onblur="fSblur(1'+i+',1'+i+',1'+i+ef+')" class="nopads no-back-black" type="'+widgets[ef].docElem+'" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>'; }
							else if(widgets[ef].docElem == 'textarea') { htmlForm += '<textarea onfocus="fSfocus(1'+i+',1'+i+',1'+i+ef+')" onblur="fSblur(1'+i+',1'+i+',1'+i+ef+')" class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'></textarea>'; }
							else if(widgets[ef].docElem == 'datalist') {
								htmlForm += '<input onfocus="fSfocus(1'+i+',1'+i+',1'+i+ef+')" onblur="fSblur(1'+i+',1'+i+',1'+i+ef+')" list="datals'+i+'" class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>';
								htmlForm += '<datalist id="datals'+i+'">';
								if(widgets[ef].docRef != '') {
									var dl, refs = (widgets[ef].docRef).split(',');
									for(dl=0; dl < refs.length; dl++) { htmlForm += '<option value="'+refs[dl]+'">'; }
								}
								htmlForm += '</datalist>';
							}

							htmlForm += '</div>';
						}

						htmlForm += '</fieldset>';

					} else if(objs[i].design == 'boxset') {

						htmlForm += '<div class="xform">';
						if(objs[i].label != '') { htmlForm += '<label>'+objs[i].label+'</label>'; }

						for(ef=0; ef < wgf; ef++) {
							
							htmlForm += '<div class="inline-flex-element top-pull-10 right-pull-5 bottom-pull-10 left-pull-5" style="width: '+boxsize+'%">';
							
							if(widgets[ef].docElem != 'textarea' && widgets[ef].docElem != 'datalist' && widgets[ef].docElem != 'select' && widgets[ef].docElem != 'radio' && widgets[ef].docElem != 'checkbox') { htmlForm += '<input class="nopads no-back-black" type="'+widgets[ef].docElem+'" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>'; }
							else if(widgets[ef].docElem == 'textarea') { htmlForm += '<textarea class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'></textarea>'; }
							else if(widgets[ef].docElem == 'datalist') {
								htmlForm += '<input list="datals'+i+'" class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>';
								htmlForm += '<datalist id="datals'+i+'">';
								if(widgets[ef].docRef != '') {
									var dl, refs = (widgets[ef].docRef).split(',');
									for(dl=0; dl < refs.length; dl++) { htmlForm += '<option value="'+refs[dl]+'">'; }
								}
								htmlForm += '</datalist>';
							} else if(widgets[ef].docElem == 'select') {
								htmlForm += '<select class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>';
								htmlForm += '<option value="" selected>Choose</option>';
								if(widgets[ef].docRef != '') {
									var dl, refs = (widgets[ef].docRef).split(',');
									for(dl=0; dl < refs.length; dl++) { htmlForm += '<option value="'+refs[dl]+'">'+refs[dl]+'</option>'; }
								}
								htmlForm += '</select>';
							} else if(widgets[ef].docElem == 'radio' || widgets[ef].docElem == 'checkbox') {
								htmlForm += '<input type="'+widgets[ef].docElem+'" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" value="'+widgets[ef].docRef+'"  '+widgets[ef].docAttr+'><span class="left-push-10 top-pull-3">'+widgets[ef].docRef+'</span>';
							}

							htmlForm += '</div>';
						}

						htmlForm += '</div>';

					} else if(objs[i].design == 'lineset') {

						htmlForm += '<fieldset id="fs1'+i+'" class="nbx">';
						htmlForm += '<legend id="l1'+i+'" class="fx-position-flow bottom-hide xfadein motion">'+objs[i].label+'</legend>';
						
						for(ef=0; ef < wgf; ef++) {
							var alc="'#4169e1'", dlc="'#e1e1e1'", fn="'fn"+i+ef+"'";
							htmlForm += '<div id="fn'+i+ef+'" class="inline-flex-element box-border-thick-bottom top-pull-10 right-pull-5 bottom-pull-10 left-pull-5" style="width: '+boxsize+'%">';
							
							if(widgets[ef].docElem != 'textarea' && widgets[ef].docElem != 'datalist') { htmlForm += '<input onfocus="xfSfocus(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+alc+',3)" onblur="xfSblur(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+dlc+',3)" class="nopads no-back-black" type="'+widgets[ef].docElem+'" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>'; }
							else if(widgets[ef].docElem == 'textarea') { htmlForm += '<textarea onfocus="xfSfocus(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+alc+',3)" onblur="xfSblur(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+dlc+',3)" class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'></textarea>'; }
							else if(widgets[ef].docElem == 'datalist') {
								htmlForm += '<input onfocus="xfSfocus(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+alc+',3)" onblur="xfSblur(1'+i+',1'+i+',1'+i+ef+'); chgborder('+fn+',1,'+dlc+',3)" list="datals'+i+'" class="nopads no-back-black" name="'+widgets[ef].docName+'" id="field1'+i+ef+'" placeholder="'+widgets[ef].docHold+'" '+widgets[ef].docAttr+widgets[ef].docEvent+'>';
								htmlForm += '<datalist id="datals'+i+'">';
								if(widgets[ef].docRef != '') {
									var dl, refs = (widgets[ef].docRef).split(',');
									for(dl=0; dl < refs.length; dl++) { htmlForm += '<option value="'+refs[dl]+'">'; }
								}
								htmlForm += '</datalist>';
							}

							htmlForm += '</div>';
						}

						htmlForm += '</fieldset>';
					}

					htmlForm += '</div>';
				}
			}
		}
	

		if(fElements.formButton == 1) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="logbutton" id="logbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 blue-white-state anchor sml-rounded-button nunito-semibold">';
			htmlForm += '</div>';
		} else if(fElements.formButton == 2) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="logbutton" id="logbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 green-white-state anchor sml-rounded-button nunito-semibold">';
			htmlForm += '</div>';
		} else if(fElements.formButton == 3) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="logbutton" id="logbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 pink-white-state anchor rounded-button nunito-semibold">';
			htmlForm += '</div>';
		} else if(fElements.formButton == 4) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="submitbutton" id="submitbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 dark-black-white-state anchor sml-rounded-button nunito-semibold">';
			htmlForm += '</div>';
		} else if(fElements.formButton == 5) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="submitbutton" id="submitbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 dark-black-white-state anchor rounded-button nunito-semibold">';
			htmlForm += '</div>';
		} else if(fElements.formButton == 6) {
			htmlForm += '<div class="top-pull-20 right-pull-10 left-pull-10">';
			htmlForm += '<input type="submit" name="submitbutton" id="submitbutton" value="'+fElements.formButtonvalue+'" class="fx-width-100 top-pull-15 bottom-pull-15 orange-white-state anchor rounded-button nunito-semibold">';
			htmlForm += '</div>';
		}

		htmlForm += '</form>';

		setTimeout(() => {
			writeObjheader(formcontainer,htmlForm);
			htmlForm = null;
		},2000);
	}
}

//-----------------------------------------------------------------------------------------------end function