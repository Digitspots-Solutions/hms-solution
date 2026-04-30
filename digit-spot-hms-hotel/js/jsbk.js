//js file by Webprox Technologies


function titleCase(str,obj) {
	var word = str.replace(/\b\w+/g,function(s){return s.charAt(0).toUpperCase() + s.substr(1).toLowerCase();});
	htmlpassval(word,obj);
}

function wtitleCase(char) {
	var word = char.replace(/\b\w+/g,function(s){return s.charAt(0).toUpperCase() + s.substr(1).toLowerCase();});
	return word;
}

//-----------------------------------------------------------------------------------------------end function

function winpop(win,toggle) {
	if(toggle == 1) {
		chgclass(win,'fx-position-stick zind-2 motion fscr top-pull-50 txp8-white');
		parent.document.getElementById('workspace').scrollTop = 0;
	} else if(toggle == 0) {
		chgclass(win,'noshow fx-position-rel zind-2 motion');
	}
}

//-----------------------------------------------------------------------------------------------end function

function slideInOut()
{
	var unslide,slide,innerobj,tp,bt,topsticky;

	unslide = document.getElementById('sidebar');
	slide = document.getElementById('workspace');
	innerobj = document.getElementById('text-link');
	innericon = document.getElementById('icons');

	tp = document.getElementById('text-link-tp');
	bt = document.getElementById('text-link-bt'); //text-link-bt

	topsticky = document.getElementById('top-sticky');
	

	if(unslide.lang == 's')
	{
		tp.style.display='none';
		bt.style.display='none';

		//slide.className="motion";
		unslide.style.width="3%";
		slide.style.width="97%";
		innerobj.style.width=0;
		innericon.style.width="100%";

		unslide.lang = 'h';

		chgclass('top-sticky','fx-position-stick top-layout right-layout nc-width-98 motion right-push-20');
	}
	else if(unslide.lang == 'h')
	{		
		//slide.className="motion";
		unslide.style.width="20%";
		slide.style.width="79%";
		innerobj.style.width='85%';
		innericon.style.width="15%";

		tp.style.display='block';
		bt.style.display='block';

		unslide.lang = 's';

		chgclass('top-sticky','fx-position-stick top-layout right-layout fx-width-80 motion right-push-20');
	}
}

//-----------------------------------------------------------------------------------------------end function

function changemenuclass(btn,obj,objs)
{
	var i;

	if(btn.getAttribute('data-tab') == 8) {
		document.getElementById('mdl'+obj).className = 'ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-black motion anchor drop-box';
	} else {
		document.getElementById('mdl'+obj).className = 'ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-black motion anchor';
	}

	for(i=1; i <= objs; i++) {
		if(i != obj) {
			//document.getElementById('mdl'+i).className = document.getElementById('mdl'+i).getAttribute('class');
			//document.getElementById('mdl'+i).classList.replace('sticky-menu-black','sticky-menu-state');
			if(document.getElementById('mdl'+i).getAttribute('data-tab') == 8) {
				document.getElementById('mdl'+i).className = 'ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-state motion anchor drop-box';
			} else {
				document.getElementById('mdl'+i).className = 'ln-display-box float-left top-pull-7 left-pull-15 right-pull-15 bottom-pull-7 sml-rounded-button sticky-menu-state motion anchor';
			}
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

function loadpage(pg)
{
	var page = (pg).split('-');
	var pgstart = page[0];
	var pglimit = page[1];
	var cpg = page[2];

	var pageurl = document.getElementById('pageurl').innerHTML;
	var npageurl = pageurl.replace('/','&');
	npageurl = npageurl.replace('/','&');
	npageurl = npageurl.replace('/','&');
	npageurl = npageurl.replace('/','&');
	npageurl = npageurl.replace('/','&');

	//console.log(npageurl+'&pg='+cpg+'&start='+pgstart+'&limit='+pglimit);
	if(npageurl.indexOf('spl.-guests') > -1) { npageurl = npageurl.replace('&','/'); }
	window.location.href = npageurl+'&pg='+cpg+'&start='+pgstart+'&limit='+pglimit;

}


function nextpage(pg)
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

//-----------------------------------------------------------------------------------------------end function

function csautohidePopupBox(cobj,obj)
{
	var op = 50;
	
	var stopafter = setInterval(function () { 
	
	if(document.getElementById(cobj)) {
		if(document.getElementById(cobj).value == 1) {
			clearInterval(stopafter);
			document.getElementById(obj).style.display='none';
			document.getElementById(cobj).value = 0;
		}
	}
		
	op += op * 1;
	
	}, 3000);
}

//-----------------------------------------------------------------------------------------------end function

function pageModLoader(obj,fil) {
	if(obj !='' && fil !='') {
		window.location.reload(true);
		$("#"+obj).load(filePath+fil);
	}
}

//-----------------------------------------------------------------------------------------------end function

function pageLoader(obj,fil) {
	if(obj !='' && fil !='') {
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

function formnotrequired(str)
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

function forsubmenu(container,list,xlist)
{
	var c = document.getElementById(container);

	if(c.lang == 'uncollapsed') {
		document.getElementById(list).className = 'motion';
		document.getElementById(xlist).className = 'block-element motion';
		c.lang = 'collapsed';
	} else if(c.lang == 'collapsed') {
		document.getElementById(list).className = 'csp-height-0 motion';
		document.getElementById(xlist).className = 'noshow motion';
		c.lang = 'uncollapsed';
	}
}

//-----------------------------------------------------------------------------------------------end function

function objswitch(container,list,xlist,fsclass,ssclass,xfsclass,xssclass)
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

/*function loadframe(label,numbr)
{
	var movepg, curframe = document.getElementById('curpage'), noofpage = document.getElementById('noofpage'), carousel = document.getElementById('frame-work-drag');
	movepg = eval(curframe.value) + 100;
	carousel.style.width = movepg+"%";
	carousel.style.height = "7000px";
	curframe.value = movepg;

	var newth = document.createElement('DIV');
	var newtd = document.createElement('DIV');
	var newframe = document.createElement('iframe');
	var newthHtml = "";

	newframe.id = 'frame'+numbr;
	newframe.name = 'frame'+numbr;
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '100%';
	newframe.scrolling = 'no';

	if(curframe.value == 0) {
		carousel.innerHTML = "";
		var pagescrollToken = {label:0};
		var fpg = 1;
	} else {
		var pgtoken = sessionStorage.getItem('pagetoken');
		var pagescrollToken = JSON.parse(pgtoken);
		pagescrollToken.push({label:movepg});
		var fpg = 0;
	}

	if(pagescrollToken.label !== null && pagescrollToken.label != 'undefined') {
		if(fpg == 1) { carousel.style.marginLeft = pagescrollToken.label+"%"; }
		else if(fpg == 0) { carousel.style.marginLeft = "-"+pagescrollToken.label+"%"; }
	} else {
		var param = label.toLocaleLowerCase();
		var sparam = param.split(' ');
		
		var i,urlparam,paramcount,incr;
		urlparam=''; paramcount = sparam.length; incr = 0;
		
		if(paramcount > 1) {
			for(i=0; i<paramcount; i++) {
				incr = incr + 1;
				if(incr < paramcount) { urlparam += sparam[i]+'-'; }
				else { urlparam += sparam[i]; }
			}
		} else {
			urlparam = param;
		}

		noofpage.value = eval(noofpage.value) + 1;
		var wh = 100 / (eval(noofpage.value) + 1);
		wh = wh.toFixed(1);


		newthHtml = '<span id="'+urlparam+'" class="block-element anchor">';
		newthHtml += '<div class="ln-display-box float-left right-push-7" onclick="mvPage(this.lang)" lang="'+urlparam+'">'+label+'</div>';
		newthHtml += '<div class="ln-display-box float-left" onclick="clsPage(this.lang)" lang="'+urlparam+'">x</div>';
		newthHtml += '<div class="block-element new-line-space"></div>';
		newthHtml += '</span>';

		newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		newtd.className = 'float-left';

		newth.innerHTML = newthHtml;
		document.getElementById('frame-header').appendChild(newth);
	
		newtd.appendChild(newframe);
		carousel.appendChild(newtd);
		
		var d, divs = carousel.getElementsByTagName('div');

		for(d=0; d < divs.length; d++) {
			divs[d].style.width = wh+"%";
			divs[d].style.height = "100%";
		}

		//open notification
		objDisplay('pause-state'); writeObjheader('pause-state-msg','Processing request, wait');
		newframe.src = filePath+'public/admin/workspace.php?logs='+urlparam;
		
		newframe.onload = function() {
			if(fpg == 1) { carousel.style.marginLeft = pagescrollToken.label+"%"; }
			else if(fpg == 0) { carousel.style.marginLeft = "-"+pagescrollToken.label+"%"; }
			objHidden('pause-state');
		}
	}

	var jsonString = JSON.stringify(pagescrollToken);
	sessionStorage.setItem('pagetoken',jsonString);
}*/

function openpg(str,xp) {
	var n = document.getElementById('curpage').value;
	objHidden('td'+n);
	
	if(xp == 1) {
		objDisplay('td'+str);
		document.getElementById('curpage').value = str;
	} else if(xp == 0) {
		objHidden('td'+str);
		document.getElementById('workspace').scrollTop = 0;

		var pgs = sessionStorage.getItem('Pagetoken');
		var jsonPg = JSON.parse(pgs);
		//var pg = jsonPg.indexOf(Number(str));
		
		var j,newJson = [];
		for(j=0; j < jsonPg.length; j++) { if(jsonPg[j] != eval(str)) { newJson.push(jsonPg[j]); } }
		sessionStorage.setItem('Pagetoken',JSON.stringify(newJson));
		var pgpos = (newJson.length) - 1;
		var lastpg = (newJson[pgpos]);

		objDisplay('td'+lastpg);
		document.getElementById('curpage').value = lastpg;
	}
}

function loadframe(label,numbr)
{
	var curframe = document.getElementById('curpage');
	document.getElementById('workspace').scrollTop = 0;
	//parent.document.getElementById('workspace').scrollTop = 0;

	if(sessionStorage.getItem('Pagetoken') !== null) {
		var pgs = sessionStorage.getItem('Pagetoken');
		var err,jsonPg = JSON.parse(pgs);

		for(j=0; j < jsonPg.length; j++) {
			if(jsonPg[j] == numbr) {
				err = 1;
				break;
			}
		}

		if(!err) { jsonPg.push(numbr); }

	} else {
		var jsonPg = [numbr];
	}

	jsonPgs = JSON.stringify(jsonPg);
	sessionStorage.setItem('Pagetoken',jsonPgs);

	if(document.getElementById('th'+numbr)) {
		document.getElementById('th'+numbr).className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;
	} else {
		var param = label.toLocaleLowerCase();
		var sparam = param.split(' ');
		
		var i,urlparam,paramcount,incr;
		urlparam=''; paramcount = sparam.length; incr = 0;
		
		if(paramcount > 1) {
			for(i=0; i<paramcount; i++) {
				incr = incr + 1;
				if(incr < paramcount) { urlparam += sparam[i]+'-'; }
				else { urlparam += sparam[i]; }
			}
		} else {
			urlparam = param;
		}
		
		var newth = document.createElement('DIV');
		var newtd = document.createElement('DIV');
		var newframe = document.createElement('iframe');
		
		newth.id = 'th'+numbr;
		newtd.id = 'td'+numbr;

		newtd.style.height = '50000px';

		var h = "'th"+numbr+"'";
		var b = "'td"+numbr+"'";
		var c = "'td"+curframe.value+"'";

		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;

		var removeheader = "'noshow'";

		newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1)" lang="'+numbr+'">'+label+'</span>';
		newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="openpg(this.lang,0); document.getElementById('+h+').className='+removeheader+'" lang="'+numbr+'">x</span>';
		newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

		newframe.id = 'frame'+numbr;
		newframe.name = 'frame'+numbr;
		newframe.frameBorder = 0;
		newframe.marginWidth = 0;
		newframe.marginHeight = 0;
		newframe.width = '100%';
		newframe.height = '100%';
		newframe.scrolling = 'no';


		newtd.appendChild(newframe);
		document.getElementById('frame-work').appendChild(newtd);
		document.getElementById('frame-header').appendChild(newth);

		//open notification
		objDisplay('pause-state'); writeObjheader('pause-state-msg','Processing request, wait');
		newframe.src = filePath+'public/admin/workspace.php?logs='+urlparam;
		newframe.onload = () => { objHidden('pause-state'); }
	}
}

//-----------------------------------------------------------------------------------------------end function

function mx_loadframe(label,numbr)
{
	var curframe = document.getElementById('curpage');
	document.getElementById('workspace').scrollTop = 0;

	if(sessionStorage.getItem('Pagetoken') !== null) {
		var pgs = sessionStorage.getItem('Pagetoken');
		var err,jsonPg = JSON.parse(pgs);

		for(j=0; j < jsonPg.length; j++) {
			if(jsonPg[j] == numbr) {
				err = 1;
				break;
			}
		}

		if(!err) { jsonPg.push(numbr); }

	} else {
		var jsonPg = [numbr];
	}

	jsonPgs = JSON.stringify(jsonPg);
	sessionStorage.setItem('Pagetoken',jsonPgs);

	if(document.getElementById('th'+numbr)) {
		document.getElementById('th'+numbr).className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;
	} else {
		var param = label.toLocaleLowerCase();
		var sparam = param.split(' ');
		
		var i,urlparam,paramcount,incr;
		urlparam=''; paramcount = sparam.length; incr = 0;
		
		if(paramcount > 1) {
			for(i=0; i<paramcount; i++) {
				incr = incr + 1;
				if(incr < paramcount) { urlparam += sparam[i]+'_'; }
				else { urlparam += sparam[i]; }
			}
		} else {
			urlparam = param;
		}
		
		var newth = document.createElement('DIV');
		var newtd = document.createElement('DIV');
		var newframe = document.createElement('iframe');
		
		newth.id = 'th'+numbr;
		newtd.id = 'td'+numbr;

		newtd.style.height = '50000px';

		var h = "'th"+numbr+"'";
		var b = "'td"+numbr+"'";
		var c = "'td"+curframe.value+"'";

		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;

		var removeheader = "'noshow'";

		newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1)" lang="'+numbr+'">'+label+'</span>';
		newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="openpg(this.lang,0); document.getElementById('+h+').className='+removeheader+'" lang="'+numbr+'">x</span>';
		newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

		newframe.id = 'frame'+numbr;
		newframe.name = 'frame'+numbr;
		newframe.frameBorder = 0;
		newframe.marginWidth = 0;
		newframe.marginHeight = 0;
		newframe.width = '100%';
		newframe.height = '100%';
		newframe.scrolling = 'no';


		newtd.appendChild(newframe);
		document.getElementById('frame-work').appendChild(newtd);
		document.getElementById('frame-header').appendChild(newth);

		//open notification
		objDisplay('pause-state'); writeObjheader('pause-state-msg','Processing request, wait');
		newframe.src = filePath+'public/admin/materialcontrol/workspace.php?logs='+urlparam;
		newframe.onload = () => { objHidden('pause-state'); }
	}
}

//-----------------------------------------------------------------------------------------------end function

function get_pos_shop(xpos,obj,cobj)
{
	var op = 50;
	
	var stopafter = setInterval(function () { 
	
	if(document.getElementById('pos-mode')) {
		if(document.getElementById('pos-mode').value == 1) {
		
			if(window.XMLHttpRequest) { var xhr = new XMLHttpRequest(); }
			else { var xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

			var file = phpfile+"getposhop.php?shop="+xpos+"&dataSend=200";
		  var randomNum = Math.random() * 1000000000;
		    
	    xhr.onreadystatechange=stateChanged;
	    xhr.open('GET', file+"&rand=" + randomNum, true);
	  
	    function stateChanged()
	    {
	      if(xhr.readyState == 4)
	      {
	        if(xhr.status == 200)
	        {
	        	console.log(xhr.responseText);
	        	var json = JSON.parse(xhr.responseText);
	        	
	        	if(json.success == 200) {
	        		document.getElementById(obj).innerHTML = json.wgtposname;
		        	document.getElementById('pos-mode').value = 0;
		        	
		        	if(json.wgtpostype == 'Service') { show_pos_counter(); }
		        	else if(json.wgtpostype == 'Establishment') { show_open_counter(); }
		        	else if(json.wgtpostype == 'Sales') { }
	        	}
	        }
	      }
	  	}

	  	xhr.send();
	  	clearInterval(stopafter);
		}
	}
		
	op += op * 1;
	
	},3500);
}

//-----------------------------------------------------------------------------------------------end function

function get_fx_fo_default()
{
	var stopafter = setInterval(function () { 
		
		if(document.getElementById('fo-mode') && eval(document.getElementById('fo-mode').value) == 1) {
			
				document.getElementById('fo-mode').value = 0;

				var label = 'Front Office';
				var url = "'"+filePath+"public/admin/frontdesk.php?logs="+label+"'";
				var curframe = document.getElementById('curpage');

				var newth = document.createElement('DIV');
				var newtd = document.createElement('DIV');
				var newframe = document.createElement('iframe');
				
				newth.id = 'th10000';
				newtd.id = 'td10000';

				var h = "'th10000'";
				var b = "'td10000'";
				var c = "'td"+curframe.value+"'";

				objHidden('td'+curframe.value);
				curframe.value = 10000;

				var wks = "'workspace'";
				newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
				newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1); parent.document.getElementById('+wks+').scrollTop = 0" lang="10000">'+label+'</span>';
				newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
				newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

				//newframe.id = 'frame10000';
				newframe.setAttribute('id', 'frame10000'); // assign an id
				newframe.name = 'frame10000';
				newframe.frameBorder = 0;
				newframe.marginWidth = 0;
				newframe.marginHeight = 0;
				newframe.width = '100%';
				newframe.height = '30000px';
				newframe.scrolling = 'no';


				newtd.appendChild(newframe);
				document.getElementById('frame-work').appendChild(newtd);
				document.getElementById('frame-header').appendChild(newth);

				//open notification
				objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading frontdesk dashboard, wait');
				newframe.src = filePath+'public/admin/frontdesk.php?logs='+label;
				newframe.onload = function() {
					objHidden('pause-state');
					var doc = newframe.contentDocument? newframe.contentDocument:newframe.contentWindow.document;
					var trigger_button_1 = doc.getElementById('show-room-rate');
					var trigger_button_2 = doc.getElementById('show-weekly-tariff');
					var trigger_button_3 = doc.getElementById('show-house-status');
					var trigger_button_4 = doc.getElementById('show-checkin-checkout');
					
					trigger_button_1.onclick = function() {
						chgclass('room-rate','fx-position-stick fscr zind-2 y-scroll motion txp5-black');
						objDisplay('inn-room-rate');
					}

					trigger_button_2.onclick = function() {
						chgclass('weekly-tariff','fx-position-stick fscr zind-2 y-scroll motion txp5-black');
						objDisplay('inn-weekly-tariff');
					}

					trigger_button_3.onclick = function() {
						chgclass('house-status','fx-position-stick fscr zind-2 y-scroll motion txp5-black');
						objDisplay('inn-house-status');
					}

					trigger_button_4.onclick = function() {
						chgclass('checkin-checkout','fx-position-stick fscr zind-2 y-scroll motion txp5-black');
						objDisplay('inn-checkin-checkout');
					}
				}
				
				//clearInterval(stopafter);
		}

	},3500);
}

//-----------------------------------------------------------------------------------------------end function

function get_fx_hsk_default()
{
	var stopafter = setInterval(function () { 
		if(document.getElementById('hsk-mode') && document.getElementById('hsk-mode').value == 1) {
			
			document.getElementById('hsk-mode').value = 0;

			var label = 'Housekeeping';
			var url = "'"+filePath+"public/admin/housekeeping.php?logs="+label+"'";
			var curframe = document.getElementById('curpage');

			var newth = document.createElement('DIV');
			var newtd = document.createElement('DIV');
			var newframe = document.createElement('iframe');
			
			newth.id = 'th10000';
			newtd.id = 'td10000';

			var h = "'th10000'";
			var b = "'td10000'";
			var c = "'td"+curframe.value+"'";

			objHidden('td'+curframe.value);
			curframe.value = 10000;

			var wks = "'workspace'";

			newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
			newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1); parent.document.getElementById('+wks+').scrollTop = 0" lang="10000">'+label+'</span>';
			newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
			newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

			newframe.id = 'frame10000';
			newframe.name = 'frame10000';
			newframe.frameBorder = 0;
			newframe.marginWidth = 0;
			newframe.marginHeight = 0;
			newframe.width = '100%';
			newframe.height = '30000px';
			newframe.scrolling = 'no';


			newtd.appendChild(newframe);
			document.getElementById('frame-work').appendChild(newtd);
			document.getElementById('frame-header').appendChild(newth);

			//open notification
			objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading housekeeping dashboard, wait');
			newframe.src = filePath+'public/admin/housekeeping.php?logs='+label;
			newframe.onload = function() { objHidden('pause-state'); }
			
			//clearInterval(stopafter);
		}

	},3700);
}

//-----------------------------------------------------------------------------------------------end function

function get_fx_recr_default()
{
	var stopafter = setInterval(function () { 
		if(document.getElementById('recr-mode') && document.getElementById('recr-mode').value == 1) {
			
			document.getElementById('recr-mode').value = 0;

			var label = 'Recreation';
			var url = "'"+filePath+"public/admin/recreation.php?logs="+label+"'";
			var curframe = document.getElementById('curpage');

			var newth = document.createElement('DIV');
			var newtd = document.createElement('DIV');
			var newframe = document.createElement('iframe');
			
			newth.id = 'th10000';
			newtd.id = 'td10000';

			var h = "'th10000'";
			var b = "'td10000'";
			var c = "'td"+curframe.value+"'";

			objHidden('td'+curframe.value);
			curframe.value = 10000;

			var wks = "'workspace'";

			newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
			newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1); parent.document.getElementById('+wks+').scrollTop = 0" lang="10000">'+label+'</span>';
			newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
			newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

			newframe.id = 'frame10000';
			newframe.name = 'frame10000';
			newframe.frameBorder = 0;
			newframe.marginWidth = 0;
			newframe.marginHeight = 0;
			newframe.width = '100%';
			newframe.height = '30000px';
			newframe.scrolling = 'no';


			newtd.appendChild(newframe);
			document.getElementById('frame-work').appendChild(newtd);
			document.getElementById('frame-header').appendChild(newth);

			//open notification
			objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading recreation dashboard, wait');
			newframe.src = filePath+'public/admin/recreation.php?logs='+label;
			newframe.onload = function() { objHidden('pause-state'); }
			
			//clearInterval(stopafter);
		}

	},3700);
}

//-----------------------------------------------------------------------------------------------end function

function get_fx_mtc_default()
{
	var stopafter = setInterval(function () { 
		if(document.getElementById('mtc-mode') && document.getElementById('mtc-mode').value == 1) {
		
			document.getElementById('mtc-mode').value = 0;

			var label = 'Dashboard';
			var url = "'"+filePath+"public/admin/material_control_dashboard.php?logs="+label+"'";
			var curframe = document.getElementById('curpage');

			var newth = document.createElement('DIV');
			var newtd = document.createElement('DIV');
			var newframe = document.createElement('iframe');
			
			newth.id = 'th10000';
			newtd.id = 'td10000';

			var h = "'th10000'";
			var b = "'td10000'";
			var c = "'td"+curframe.value+"'";

			objHidden('td'+curframe.value);
			curframe.value = 10000;

			var wks = "'workspace'";

			newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
			newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1); parent.document.getElementById('+wks+').scrollTop = 0" lang="10000">'+label+'</span>';
			newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
			newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

			newframe.id = 'frame10000';
			newframe.name = 'frame10000';
			newframe.frameBorder = 0;
			newframe.marginWidth = 0;
			newframe.marginHeight = 0;
			newframe.width = '100%';
			newframe.height = '30000px';
			newframe.scrolling = 'no';


			newtd.appendChild(newframe);
			document.getElementById('frame-work').appendChild(newtd);
			document.getElementById('frame-header').appendChild(newth);

			//open notification
			objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading material control dashboard, wait');
			newframe.src = filePath+'public/admin/material_control_dashboard.php?logs=materialcontroldashboard';
			newframe.onload = function() { objHidden('pause-state'); }
			
			//clearInterval(stopafter);
		}

	},3700);
}

//-----------------------------------------------------------------------------------------------end function

function chgclass(obj,xclass)
{
	if(document.getElementById(obj)) {
		document.getElementById(obj).className = xclass;
	}
}

//-----------------------------------------------------------------------------------------------end function

function revealpwd(obj)
{
	var pwd = document.getElementById(obj);
	if(pwd.value != '' && pwd.lang == 'h') {
		pwd.type = 'text';
		pwd.lang = 'r';
	} else if(pwd.value != '' && pwd.lang == 'r') {
		pwd.type = 'password';
		pwd.lang = 'h';
	}
}

//-----------------------------------------------------------------------------------------------end function

function textodate(obj)
{
	if(document.getElementById(obj)) {
		document.getElementById(obj).type='date';
		setTimeout(function() { document.getElementById(obj).focus(); },200);
	}
}

//-----------------------------------------------------------------------------------------------end function

function getdata(obj,ses,str,opt)
{
	var xhr,file,todata,usesid;

	if(str != '')
	{
		if(opt == 'dropbox') { document.getElementById(obj).innerHTML = '<option value="">fetching data..</option>'; }
		else if(opt == 'textnode' || opt == 'div') { document.getElementById(obj).innerHTML = '<div class="top-push-50 top-pull-50" align="center"><div class="loading"></div><br>Loading</div>'; }
		else if(opt == 'inputs') { document.getElementById(obj).value = 'fetching..'; }
		
		
		if(str >= 1) { usesid = str; } else { if(document.getElementById(str)) { usesid = document.getElementById(str).value; } else { usesid = 0; } }

		if(window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		file = phpfile+"dbquery.php?data="+usesid+"&r="+ses+"&dataSend=200";
		var randomNum = Math.random() * 1000000000;
		
		xhr.onreadystatechange=stateChanged;
		xhr.open('GET', file+"&rand=" + randomNum, true);
	
		function stateChanged()
		{
			if(xhr.readyState == 4)
			{
				if(xhr.status == 200)
				{
					//console.log(xhr.responseText);
					if(opt == 'dropbox') { document.getElementById(obj).innerHTML = xhr.responseText; }
					else if(opt == 'textnode' || opt == 'div') { document.getElementById(obj).innerHTML = xhr.responseText; }
					else if(opt == 'inputs') { document.getElementById(obj).value = xhr.responseText; }
				}
				else
				{
					if(opt == 'dropbox') { document.getElementById(obj).innerHTML = '<option value="">Error fetching data!</option>'; }
					else if(opt == 'textnode' || opt == 'div') { document.getElementById(obj).innerHTML = '<small class="block-element alignct">Error loading content!</small>'; }
					else if(opt == 'inputs') { document.getElementById(obj).value = 'Error!'; }
				}
			}
		}
		
		xhr.send();
	}
}

//-----------------------------------------------------------------------------------------------end function

function keywords_search(obj,ses,str)
{
	var xhr,file,strings;

	if(str != '')
	{
		document.getElementById(obj).innerHTML = '<div class="top-push-50 top-pull-50" align="center"><div class="loading"></div><br>Loading</div>';
		
		if(document.getElementById(str)) { strings = document.getElementById(str).value; }

		if(window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		file = phpfile+"dbquery.php?data="+strings+"&r="+ses+"&dataSend=200";
		var randomNum = Math.random() * 1000000000;
		
		xhr.onreadystatechange=stateChanged;
		xhr.open('GET', file+"&rand=" + randomNum, true);
	
		function stateChanged()
		{
			if(xhr.readyState == 4)
			{
				if(xhr.status == 200)
				{
					document.getElementById(obj).innerHTML = xhr.responseText;
				}
				else
				{
					document.getElementById(obj).innerHTML = '<small class="block-element alignct">Error fetching data!</small>';
				}
			}
		}
		
		xhr.send();
	}
}

//-----------------------------------------------------------------------------------------------end function

function return_getdata(obj,ses,str)
{
	var xhr,file,usesid,randomNum,ajaxson;

	if(str >= 1) { usesid = str; } else { usesid = 0; }

	ajaxson = '<option value="">wooooow!</option>';

	if(window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	} else {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	file = phpfile+"dbquery.php?data="+usesid+"&r="+ses+"&dataSend=200";
	randomNum = Math.random() * 1000000000;
	
	ajaxson = '<option value="">heyy!</option>';

	xhr.onreadystatechange=stateChanged;
	xhr.open('GET', file+"&rand=" + randomNum, true);

	ajaxson = '<option value="">haaa!</option>';

	function stateChanged()
	{
		if(xhr.readyState == 4)
		{
			ajaxson = '<option value="">fetching data!</option>';
			
			if(xhr.status == 200) {
				ajaxson = xhr.responseText;
			} else {
				ajaxson = '<option value="">Error fetching data!</option>';
			}
		}
	}


	
	xhr.send();

	return ajaxson;
}

//-----------------------------------------------------------------------------------------------end function

function uploadphoto(obj,curl,fupl)
{
	params = 'f=fileupload&formfield1='+curl+'&formfield2='+fupl+'&dataSend=200';
	
	if(window.XMLHttpRequest)
	{
		xhr = new XMLHttpRequest();
	}
	else
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	file = filePath1+"post_form_data.php";
	
	xhr.onreadystatechange=stateChanged;
	xhr.open('POST', file, true);
	
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	function stateChanged()
	{
		if(xhr.readyState == 4)
		{
			if(xhr.status == 200)
			{
				document.getElementById(obj).value = xhr.responseText;
				document.getElementById('fmsg').innerHTML = 'image is attached';
			}
		}
	}
	
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function resizeimage(e,w,h,todata,upl,fupl,showimage)
{
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
		
		if(upl == 'upload')
		{
			uploadphoto(todata,raw_image_data,fupl);
			document.getElementById(showimage).innerHTML = "<img src="+e.target.result+" width='100%' height='100%'>";
		}
		else
		{
			document.getElementById(todata).value = raw_image_data;
			document.getElementById(showimage).innerHTML = "<img src="+e.target.result+" width='100%' height='100%'>";
			document.getElementById('fmsg').innerHTML = 'image is ready for upload!';
		}
	  }
	  img.src=e.target.result;
	 }
	 
	fr.readAsDataURL(e.target.files[0]);
}

//-----------------------------------------------------------------------------------------------end function

function show_pos_counter()
{
	var label = document.getElementById('pos-shop-selected').innerHTML;
	var url = "'"+filePath+"public/admin/pos_counter.php?logs="+label+"'";
	var curframe = document.getElementById('curpage');

	var newth = document.createElement('DIV');
	var newtd = document.createElement('DIV');
	var newframe = document.createElement('iframe');
	
	newth.id = 'th10000';
	newtd.id = 'td10000';

	var h = "'th10000'";
	var b = "'td10000'";
	var c = "'td"+curframe.value+"'";

	objHidden('td'+curframe.value);
	curframe.value = 10000;

	newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
	newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1)" lang="10000">'+label+'</span>';
	newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
	newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

	newframe.id = 'frame10000';
	newframe.name = 'frame10000';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '10000px';
	newframe.scrolling = 'auto';


	newtd.appendChild(newframe);
	document.getElementById('frame-work').appendChild(newtd);
	document.getElementById('frame-header').appendChild(newth);

	//open notification
	objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading pos, wait');
	newframe.src = filePath+'public/admin/pos_counter.php?logs='+label;
	newframe.onload = function() { objHidden('pause-state'); }
}

//-----------------------------------------------------------------------------------------------end function

function show_open_counter() {

	var label = document.getElementById('pos-shop-selected').innerHTML;
	var url = "'"+filePath+"public/admin/pos_open_counter.php?logs="+label+"'";
	var curframe = document.getElementById('curpage');

	var newth = document.createElement('DIV');
	var newtd = document.createElement('DIV');
	var newframe = document.createElement('iframe');
	
	newth.id = 'th10000';
	newtd.id = 'td10000';

	var h = "'th10000'";
	var b = "'td10000'";
	var c = "'td"+curframe.value+"'";

	objHidden('td'+curframe.value);
	curframe.value = 10000;

	newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 steel-blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
	newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1)" lang="10000">'+label+'</span>';
	newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="window.frame10000.location='+url+'" title="Refresh"><b class="nobold fa-refresh"></b></span>';
	newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

	newframe.id = 'frame10000';
	newframe.name = 'frame10000';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '10000px';
	newframe.scrolling = 'auto';


	newtd.appendChild(newframe);
	document.getElementById('frame-work').appendChild(newtd);
	document.getElementById('frame-header').appendChild(newth);

	//open notification
	objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading pos, wait');
	newframe.src = filePath+'public/admin/pos_open_counter.php?logs='+label;
	newframe.onload = function() { objHidden('pause-state'); }
}

//-----------------------------------------------------------------------------------------------end function

function json_data(str,ses)
{
	var xhr,file,strings,randomNum,ajaxson,result;

	if(str >= 1) { strings = str; } else { strings = 0; }

	if(window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	} else {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	file = phpfile+"dbquery.php?jstring="+strings+"&jmsg="+ses+"&dataSend=200";
	randomNum = Math.random() * 1000000000;
	
	xhr.onreadystatechange=stateChanged;
	xhr.open('GET', file+"&rand=" + randomNum, true);
	
	function stateChanged()
	{
		if(xhr.readyState == 4)
		{
			if(xhr.status == 200) {
				localStorage.setItem('json',xhr.responseText);
			} else {
				localStorage.setItem('json',0);
			}
		}
	}

	xhr.send();
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

function tofixe(numbr,decimal)
{
	return numbr.toFixed(decimal);
}

//-----------------------------------------------------------------------------------------------end function

function getJson_data(obj,ses,str)
{
	var xhr,file,todata,usesid,myObj,wktr,ctype,thscspg;

	if(str != '')
	{
		wktr = document.getElementById('apply-wkly-tariff').value;
		ctype = document.getElementById('customer-type').value;
		thscspg = document.getElementById('cspg').value;
		newcheckin = document.getElementById('checkin').value;
		newcheckout = document.getElementById('checkout').value;

		document.getElementById(obj).innerHTML = '<div align="center"><div class="loading"></div></div>';
		
		if(str >= 1) { usesid = str; } else { if(document.getElementById(str)) { usesid = document.getElementById(str).value; } else { usesid = 0; } }

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		file = phpfile+"dbquery.php?jsonkey="+usesid+"&jsonclass="+ses+"&wkt="+wktr+"&ctype="+ctype+"&cspg="+thscspg+"&checkin="+newcheckin+"&checkout="+newcheckout+"&dataSend=200";
		var randomNum = Math.random() * 1000000000;
		
		xhr.onreadystatechange=stateChanged;
		xhr.open('GET', file+"&rand=" + randomNum, true);
	
		function stateChanged()
		{
			if(xhr.readyState == 4)
			{
				if(xhr.status == 200)
				{
					//console.log(xhr.responseText);
					myObj = JSON.parse(xhr.responseText); console.log(myObj);
					var nr = myObj.noofrooms;
					var r,nrobj,drobj; nrobj=''; drobj='';

					if(ses == 'frontdesk-list-room-type-detail') {
						
						nrobj += '<option value="">Choose</option>';
						
						for(r=1; r <= nr; r++) { nrobj += '<option value="'+r+'">'+r+'</option>'; }

						var cpr = myObj.price; //tofixe(myObj.price,2);

						document.getElementById('noofrooms').innerHTML = nrobj;
						document.getElementById('unitprice').value = cpr;

						document.getElementById('hotelseason').value = myObj.hotelseason;
						document.getElementById('hotelseasonday').value = myObj.hotelseasonday;

						document.getElementById('wgt-discount').value = myObj.discount;
						document.getElementById('wgt-tax').value = myObj.tax;
						document.getElementById('wgt-service-charge').value = myObj.servicecharge;
						document.getElementById('wgt-consumption').value = myObj.consumption;

						if(myObj.rsvat && myObj.rsvat > 0) {
							chgclass('w-vat','noshow');
							var tax_1 = (myObj.rsvat / 100) * cpr; tax_1 = tofixe(tax_1,2);
							document.getElementById('rsvat').value = tax_1;
							sessionStorage.setItem('phrsvat',tax_1);
						} else {
							if(sessionStorage.getItem('phrsvat') !== null && sessionStorage.getItem('phrsvat') !== undefined) {
								sessionStorage.removeItem('phrsvat');
							}
						}

						if(myObj.rsschg && myObj.rsschg > 0) {
							chgclass('w-service','noshow');
							var tax_2 = (myObj.rsschg / 100) * cpr; tax_2 = tofixe(tax_2,2);
							document.getElementById('rsschg').value = tax_2;
							sessionStorage.setItem('phrsschg',tax_2);
						} else {
							if(sessionStorage.getItem('phrsschg') !== null && sessionStorage.getItem('phrsschg') !== undefined) {
								sessionStorage.removeItem('phrsschg');
							}
						}

						if(myObj.rsctax && myObj.rsctax > 0) {
							chgclass('w-consumption','noshow');
							var tax_3 = (myObj.rsctax / 100) * cpr; tax_3 = tofixe(tax_3,2);
							document.getElementById('rsctax').value = tax_3;
							sessionStorage.setItem('phrsctax',tax_3);
						} else {
							if(sessionStorage.getItem('phrsctax') !== null && sessionStorage.getItem('phrsctax') !== undefined) {
								sessionStorage.removeItem('phrsctax');
							}
						}

						drobj += '<div class="block-element top-push-10"></div>';
						drobj += '<span class="ln-display-box float-left right-push-20"><small class="block-element dark-grey-font ft-xxsml-size">Rate Type</small><small class="block-element">Rack</small></span>';
						drobj += '<span class="ln-display-box float-left right-push-20"><small class="block-element dark-grey-font ft-xxsml-size">Room Rate</small><small class="block-element">&#8358; '+numberFormat(cpr)+'</small></span>';
						drobj += '<span class="ln-display-box float-left"><small class="block-element dark-grey-font ft-xxsml-size">Extrabed Rate</small><small class="block-element">&#8358; '+numberFormat(myObj.extrabed)+'</small></span>';
						drobj += '<span class="block-element new-line-space"></span>';
						drobj += '<div class="block-element top-push-10"></div>';
						drobj += '<span class="ln-display-box float-left right-push-30"><small class="block-element dark-grey-font ft-xxsml-size">Adult</small><small class="block-element">&nbsp; '+myObj.adult+'</small></span>';
						drobj += '<span class="ln-display-box float-left right-push-30"><small class="block-element dark-grey-font ft-xxsml-size">Child</small><small class="block-element">&nbsp; '+myObj.child+'</small></span>';
						drobj += '<span class="ln-display-box float-left right-push-30"><small class="block-element dark-grey-font ft-xxsml-size">Child Fare</small><small class="block-element">&nbsp; '+numberFormat(myObj.childfare)+'</small></span>';
						drobj += '<span class="block-element new-line-space"></span>';
						drobj += '<div class="block-element top-push-10"></div>';
						drobj += '<span class="ln-display-box float-left right-push-30"><small class="block-element dark-grey-font ft-xxsml-size">Minimum Deposit</small><small class="block-element">&nbsp; '+numberFormat(myObj.minimumdeposit)+'</small></span>';
						drobj += '<span class="ln-display-box float-left right-push-30"><small class="block-element dark-grey-font ft-xxsml-size">Allow Smoking</small><small class="block-element">&nbsp; '+myObj.issmoking+'</small></span>';
						drobj += '<span class="block-element new-line-space"></span>';
						drobj += '<div class="block-element top-push-10"></div>';
						drobj += '<div class="block-element"><small class="block-element dark-grey-font ft-xxsml-size">Room Facilities</small><small class="block-element">'+myObj.roomfacilities+'</small></div>';

						document.getElementById('room-type-detail').innerHTML = drobj;

					} else {

						document.getElementById('unitprice').value = myObj.price;
						document.getElementById('wgt-discount').value = myObj.discount;
						document.getElementById('wgt-tax').value = myObj.tax;
						document.getElementById('wgt-service-charge').value = myObj.servicecharge;
						document.getElementById('wgt-consumption').value = myObj.consumption;
					}
				}
				else
				{
					document.getElementById(obj).innerHTML = '<small class="block-element alignct">Error loading content!</small>';
				}
			}
		}
		
		xhr.send();
	}
}

//-----------------------------------------------------------------------------------------------end function

function checkallboxes(str,totchk,prefix,obj)
{
	var chk = document.getElementById(str).lang;
	var totalcount = document.getElementById(totchk).value;

	if(chk == 'u')
	{
		for (i = 1; i<=totalcount; i++)
		{
			document.getElementById(prefix+i).checked = true;
			document.getElementById(prefix+i).lang = 'c';
		}
		
		document.getElementById(str).lang = 'c';

		if(document.getElementById(obj))
		{
			document.getElementById(obj).innerHTML = 'Pos Arenas: Deselect All';
		}
	}
	else if(chk == 'c')
	{
		for (i = 1; i<=totalcount; i++)
		{
			document.getElementById(prefix+i).checked = false;
			document.getElementById(prefix+i).lang = 'u';
		}
		
		document.getElementById(str).lang = 'u';
		
		if(document.getElementById(obj))
		{
			document.getElementById(obj).innerHTML = 'Pos Arenas: Select All';
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

function load_inbox()
{
	
	var curframe = document.getElementById('msg-frame');
	var newframe = document.createElement('iframe');
	
	curframe.innerHTML = "";

	chgclass('inbox-message','fx-position-stick zind-2 motion fscr top-pull-7 right-pull-30 left-pull-30 grey-theme noscroll');
	chgclass('msg-frame','block-element nc-height-90 white-theme sml-rounded-button obj-light-shadow noscroll');
	chgclass('close-inbox','block-element bottom-push-10');

	
	newframe.id = 'frame-inbox';
	newframe.name = 'frame-inbox';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '100%';
	newframe.scrolling = 'auto';


	curframe.appendChild(newframe);
	
	//open notification
	objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading inbox, please wait..');
	newframe.src = filePath+'public/admin/inbox.php';
	newframe.onload = function() { objHidden('pause-state'); }
}

//-----------------------------------------------------------------------------------------------end function

function load_review()
{
	
	var curframe = document.getElementById('msg-frame');
	var newframe = document.createElement('iframe');
	
	curframe.innerHTML = "";

	chgclass('inbox-message','fx-position-stick zind-2 motion fscr top-pull-7 right-pull-30 left-pull-30 grey-theme noscroll');
	chgclass('msg-frame','block-element nc-height-90 white-theme sml-rounded-button obj-light-shadow noscroll');
	chgclass('close-inbox','block-element bottom-push-10');

	
	newframe.id = 'frame-signed';
	newframe.name = 'frame-signed';
	newframe.frameBorder = 0;
	newframe.marginWidth = 0;
	newframe.marginHeight = 0;
	newframe.width = '100%';
	newframe.height = '100%';
	newframe.scrolling = 'auto';


	curframe.appendChild(newframe);
	
	//open notification
	objDisplay('pause-state'); writeObjheader('pause-state-msg','Loading signed request, please wait..');
	newframe.src = filePath+'public/admin/signed.php';
	newframe.onload = function() { objHidden('pause-state'); }
}

//-----------------------------------------------------------------------------------------------end function

function hideCdiv() {
	var n = document.getElementById('curpage').value;
	objHidden('td'+n);
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

function json_getdata(jsonCall,tbl,dataid,colkey,val)
{
	var xhr,params,file,ajaxresult;

	if(tbl !== null && dataid !== null && colkey !== null && val !== null) {

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		var dataString = {
			"tableSrc": tbl,
			"dataCallid": dataid,
			"columnKey": colkey,
			"valueObj": val
		};

		JSONString = JSON.stringify(dataString);

		params = "dataSend=200&f=idgetvalue&postdatarequest="+JSONString;
		file = phpfile+"post_form_data.php";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					jsonCall(xhr.responseText);
				}
			}
		};

		xhr.open('POST', file, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}
}

//-----------------------------------------------------------------------------------------------end function

function json_callback(response) {
	return response;
}

//-----------------------------------------------------------------------------------------------end function

function wgtiframe(label,numbr,log)
{
	var curframe = document.getElementById('curpage');
	document.getElementById('workspace').scrollTop = 0;
	//console.log(curframe.value);

	if(sessionStorage.getItem('Pagetoken') !== null) {
		var pgs = sessionStorage.getItem('Pagetoken');
		var err,jsonPg = JSON.parse(pgs);

		for(j=0; j < jsonPg.length; j++) {
			if(jsonPg[j] == numbr) {
				err = 1;
				break;
			}
		}

		if(!err) { jsonPg.push(numbr); }

	} else {
		var jsonPg = [numbr];
	}

	jsonPgs = JSON.stringify(jsonPg);
	sessionStorage.setItem('Pagetoken',jsonPgs);

	if(document.getElementById('th'+numbr)) {
		document.getElementById('th'+numbr).className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		window.frame+numbr.location.reload(true);
		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;
	} else {
		urlparam = label;
		
		var newth = document.createElement('DIV');
		var newtd = document.createElement('DIV');
		var newframe = document.createElement('iframe');
		
		newth.id = 'th'+numbr;
		newtd.id = 'td'+numbr;

		newtd.style.height = '7000px';

		var h = "'th"+numbr+"'";
		var b = "'td"+numbr+"'";
		var c = "'td"+curframe.value+"'";

		//console.log(h+'/'+b+'/'+c);

		objDisplay('td'+numbr);
		objHidden('td'+curframe.value);
		curframe.value = numbr;

		var removeheader = "'noshow'";

		newth.className = 'ln-display-box float-left top-pull-7 right-pull-10 bottom-pull-7 left-pull-10 blue-theme ft-xxsml-size white-font right-push-3 bottom-push-3 sml-rounded-button noscroll';
		newth.innerHTML = '<span class="ln-display-box float-left right-push-7 anchor" onclick="openpg(this.lang,1)" lang="'+numbr+'">'+label+'</span>';
		newth.innerHTML = newth.innerHTML+'<span class="ln-display-box float-left anchor" onclick="openpg(this.lang,0); document.getElementById('+h+').className='+removeheader+'" lang="'+numbr+'">x</span>';
		newth.innerHTML = newth.innerHTML+'<span class="block-element new-line-space"></span>';

		newframe.id = 'frame'+numbr;
		newframe.name = 'frame'+numbr;
		newframe.frameBorder = 0;
		newframe.marginWidth = 0;
		newframe.marginHeight = 0;
		newframe.width = '100%';
		newframe.height = '100%';
		newframe.scrolling = 'no';


		newtd.appendChild(newframe);
		document.getElementById('frame-work').appendChild(newtd);
		document.getElementById('frame-header').appendChild(newth);

		//open notification
		objDisplay('pause-state'); writeObjheader('pause-state-msg','Processing request, wait');
		newframe.src = filePath+'public/admin/workspace.php?logs='+log+'&token='+urlparam;
		newframe.onload = function() { objHidden('pause-state'); }
	}
	
}

//-----------------------------------------------------------------------------------------------end function

function xModal(number,css,box) {
	var xbcss, modal_win = "modal-win-"+number, modal_box = "modal-box-"+number;
	if(box == 1) { objDisplay(modal_box); }
	else if(box == 0) { objHidden(modal_box); }
	chgclass(modal_win,css);
	//chgclass(modal_box,xbcss);
}

//-----------------------------------------------------------------------------------------------end function

function crframe(tkn1,tkn2,tkn3) {
		
	var cString = {
		"label":tkn1,
		"id":tkn2,
		"param":tkn3
	}

	var framesets = JSON.stringify(cString);
	console.log(framesets);
	sessionStorage.setItem('framesets',framesets);
	setTimeout(function() { parent.document.getElementById('wgtframe').click(); },300);
}

//-----------------------------------------------------------------------------------------------end function

function crframe2(tkn1,tkn2,tkn3) {
		
	var cString = {
		"label":tkn1,
		"id":tkn2,
		"param":tkn3
	}

	var framesets = JSON.stringify(cString);
	console.log(framesets);
	sessionStorage.setItem('framesets',framesets);
	setTimeout(function() { document.getElementById('wgtframe').click(); },300);
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
	parent.modalframe.location.href = filePath+'public/admin/workspacex.php?logs=modals&prefix='+pfx+'&param='+param+'&ftoken='+token1+'&stoken='+token2;
}

//-----------------------------------------------------------------------------------------------end function

function closemodalframe() {
	chgclass('for-pop-wins','fx-position-stick btscr zind-2 motion');
	chgclass('fmodal','noshow');
	chgclass('fmodalwin','white-theme xsml-rounded-button cs-height-0 motion noscroll');
	writeObjheader('fmodalwin','');
}

//-----------------------------------------------------------------------------------------------end function

function getUserAuthen(e,ui,privilege) {

	e.preventDefault();

	var xhr,url,params,ajaxresult,wgt_form_data;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	chgclass('fbutton','noshow');
	writeObjheader('fmessage','<div class="loading"></div>');

	wgt_form_data = json_get_formdata('authenform');
	sessionStorage.setItem('authen',wgt_form_data);

	params = "f=userauthen&fdata="+wgt_form_data+"&intf="+ui+"&prl="+privilege+"&dataSend=200";
	url = phpfile+"post_form_data.php";
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxresult = JSON.parse(xhr.responseText);
				if(ajaxresult.success == 200) {
					htmlFormReset('authenform');
					chgclass('ufm','noshow');
					chgclass('utask','top-push-20 right-pull-7 left-pull-7 block-element');
					htmlpassval(ajaxresult.userid,'wgtxuserid');
				} else {
					chgclass('fbutton','block-element alignct');
					writeObjheader('fmessage','<small class="block-element bottom-push-20 light-red-font">'+ajaxresult.status+'</small>');
				}
			}
		}
	}

	xhr.open('POST', url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(params);
}

//-----------------------------------------------------------------------------------------------end function

function wgtfsubmit(form) {
	document.getElementById(form).submit();
}

//-----------------------------------------------------------------------------------------------end function

function check_room_enabled(room,roomtyp) {

	var xhr,file,random_numbr,ajaxson,result;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	file = phpfile+"dbquery.php?r=check-rrom-status-bf-booking&data="+room+"&dataSend=200";
	random_numbr = Math.random() * 1000000000;
	
	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxson = xhr.responseText;
				result = ajaxson.replace(/ /g,'');

				if(result == 0) {
					
					parent.document.getElementById('workspace').scrollTop = 0;
					
					if(document.getElementById('notifybox')) {
						
						objDisplay('notifybox');
						writeObjheader('fo-header-notification','Notification!');
						writeObjheader('fo-message-notification','The room is not allowed for booking until housekeeping status is changed. Please refresh the booking');

						autohidePopupBox('notifybox',5000);

					} else if(document.getElementById('notifybox2')) {
						
						objDisplay('notifybox2');
						writeObjheader('fo-header-notification','Notification!');
						writeObjheader('fo-message-notification','The room is not allowed for booking until housekeeping status is changed. Please refresh the booking');

						autohidePopupBox('notifybox2',5000);
					}
					
					
					var row = sessionStorage.getItem('thisrow');
					dodata(row,'eget-rooms',roomtyp,'dropbox');
					
					sessionStorage.removeItem('thisrow');
				}
			}
		}
	};

	xhr.open('GET', file+"&rand=" + random_numbr, true);
	xhr.send();
}

//-----------------------------------------------------------------------------------------------end function

function dodata(str,sses,id,sopt) {
	var select_id = str;
	getdata(select_id,sses,id,sopt);
}

//-----------------------------------------------------------------------------------------------end function

function removeViewedPages() {
	if(sessionStorage.getItem('Pagetoken') !== null && sessionStorage.getItem('Pagetoken') != '') {
		sessionStorage.removeItem('Pagetoken');
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

function wtitleCase(char) {
	var word = char.replace(/\b\w+/g,function(s){return s.charAt(0).toUpperCase() + s.substr(1).toLowerCase();});
	return word;
}

//-----------------------------------------------------------------------------------------------end function

function showPushnotification(title,pushicon,msg,token,init) {
	var notifikasi = new Notification(title, {
		icon: filePath+pushicon,
		body: msg,
		vibrate: true,
		silent: false
	});

	new Audio(filePath+'theme/sound/beep.mp3').play();

	notifikasi.onclick = function() {
		if(init == '_self') {
			popmodalframe('','inbox2',token,'feed',1000,1500);
			/*var xframe = document.getElementById('xframe');

			chgclass('pause-page','fx-position-stick zind-1 fscr txp9-black motion noscroll');
			writeObjheader('pause-page','<div class="nc-height-40"></div><div class="loading"></div>');

			xframe.src = curl+publ+"admin/workspace"+exts+"?logs=Newsfeed&tag=Feed&token="+token;
			xframe.onload = function() {
				chgclass('pause-page','fx-position-flow btscr motion noscroll');
				writeObjheader('pause-page','');
			}*/
		} else if(init == '_blank') {
			window.open(filePath+"public/admin/workspace"+exts+"?logs=Newsfeed&tag=Feed&token="+token,"_blank");
		}

		notifikasi.close();     
	};
}

//-----------------------------------------------------------------------------------------------end function

function nwfeed(user) {
		
		var xhr,url,jsonpack,datafile,ajaxresult;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		url = phpfile+"dbquery.php?r=unreadnewsfeed&data="+user+"&dataSend=200";
		
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					//console.log(xhr.responseText);
					jsonpack = JSON.parse(xhr.responseText);
					
					if(jsonpack.success && jsonpack.success == 200) {
						/*var ifrm = document.getElementById('xframe');
						var doc = ifrm.contentDocument? ifrm.contentDocument:ifrm.contentWindow.document;
						doc.getElementById('alert').innerHTML = jsonpack.wgtunread;*/
						
						//do web notification
						if(Notification.permission === 'default') {
							Notification.requestPermission(function(permission) {
							      if(permission === "granted") {
							        window.location.reload(true);
							        showPushnotification(jsonpack.wgttitle,'theme/images/inc/favicon.png',jsonpack.wgtmsg,jsonpack.wgtdatatoken,'_self');
							      }
							});
						} else if(Notification.permission === 'granted') {
							showPushnotification(jsonpack.wgttitle,'theme/images/inc/favicon.png',jsonpack.wgtmsg,jsonpack.wgtdatatoken,'_self');
						}
						
					}
				}
			}
		}

		xhr.open('GET', url, true);
		xhr.send();
}

//-----------------------------------------------------------------------------------------------end function

function jpson(order) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=In Purchase Request&tag=&orderno='+order;
}

//-----------------------------------------------------------------------------------------------end function

function jpson_rec(member) {
	window.location.href = filePath+'public/admin/workspace.php?logs=New Recreation Member&tag=&membership='+member;
}

//-----------------------------------------------------------------------------------------------end function

function jpson_iou(order) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=IOU Request&tag=&orderno='+order;
}

function jpson_iou2(order) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=IOU Form Request&tag=&orderno='+order;
}

//-----------------------------------------------------------------------------------------------end function

function jpvar(order) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=Stock Variation&tag=&orderno='+order;
}

//-----------------------------------------------------------------------------------------------end function

function jpdbst(requestno) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=Item Request List&tag=&irqs='+requestno;
}

//-----------------------------------------------------------------------------------------------end function

function jptr(requestno) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=Item Transfer List&tag=&trqs='+requestno;
}

//-----------------------------------------------------------------------------------------------end function

function jpbnd(requestno) {
	window.location.href = filePath+'public/admin/materialcontrol/workspace.php?logs=Bad Damage List&tag=&bnd='+requestno;
}

//-----------------------------------------------------------------------------------------------end function

function jsmr(requestno) {
	window.location.href = filePath+'public/admin/workspace.php?logs=list-manual-rebate&tag=&rbt='+requestno;
}

//-----------------------------------------------------------------------------------------------end function

function sqldataQuery(jsoncall,string)
{
	var xhr,params,url,ajaxresult;

	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
	else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	var requestdata = JSON.stringify(string);
	
	params = "kyw=idgetsql&dataSend=200&sqlrequestdata="+requestdata;
	url = phpfile+"post_form_data.php";
	
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

function jsUom(uom,elem) {
		var xhr,url;

		if(window.XMLHttpRequest) { xhr = new XMLHttpRequest(); }
		else { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

		url = phpfile+"dbquery.php?kyw=get-uom-label&uom="+uom+"&dataSend=200";
		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) { document.getElementById(elem).innerHTML = xhr.responseText; }
			}
		};

		xhr.open('GET', url, true);
		xhr.send();
}

//-----------------------------------------------------------------------------------------------end function

function numberinputFormat(numbr,obj,objx)
{
	var returnum, brkpoint, dec, tostring;

	if(numbr.indexOf('.') > -1) { brkpoint = numbr.split('.'); fig = brkpoint[0]; dec = '.'+brkpoint[1]; }
	else { fig = numbr; dec = ""; }

	returnum = fig.replace(/,/g,'');

	if(returnum.length == 4) { tostring = returnum.replace(returnum.substr(0,1),returnum.substr(0,1)+','); }
	else if(returnum.length == 5) { tostring = returnum.replace(returnum.substr(0,2),returnum.substr(0,2)+','); }
	else if(returnum.length == 6) { tostring = returnum.replace(returnum.substr(0,3),returnum.substr(0,3)+','); }
	else if(returnum.length == 7) { tostring = returnum.replace(returnum.substr(0,1),returnum.substr(0,1)+','); tostring = tostring.replace(tostring.substr(2,3),tostring.substr(2,3)+','); }
	else if(returnum.length == 8) { tostring = returnum.replace(returnum.substr(0,2),returnum.substr(0,2)+','); tostring = tostring.replace(tostring.substr(3,3),tostring.substr(3,3)+','); }
	else { tostring = returnum; }

	if(document.getElementById(obj)) { document.getElementById(obj).value = tostring+dec; }
	if(document.getElementById(objx)) { document.getElementById(objx).value = returnum+dec; }
}

//-----------------------------------------------------------------------------------------------end function

function chkAll(id) {
	var cc = document.getElementById(id);
	var i, aa = document.getElementsByClassName('checkers');
	
	if(cc.value == 'off') {
		cc.value = 'on';
		chgclass(id,'nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button dark-grey-theme motion');
		for(i=0; i<aa.length; i++) {
			aa[i].setAttribute('checked','checked');
		}
	} else if(cc.value == 'on') {
		cc.value = 'off';
		chgclass(id,'nodefault-appearance box-border-thick cs-width-20 cs-height-20 xsml-rounded-button motion');
		for(i=0; i<aa.length; i++) {
			aa[i].removeAttribute('checked');
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

const datastring = {
	"process": "",
	"tip": "",
	"element": ""
}

const wparams = {
	"tbl":"",
	"key":"",
	"col":""
}

const arrytokens = {
	"arryname":"",
	"type":""
}

const sqldatastring = {
	"sql":""
}

const suggestions = [];

const arrygets = [];

const js_uoms = [];

js_uoms[0] = "Gram(s)";
js_uoms[1] = "Litre(s)";
js_uoms[2] = "Millimeter(s)";
js_uoms[3] = "Count(s)";
js_uoms[4] = "Centimeter(s)";
js_uoms[5] = "Milli Litres";
js_uoms[6] = "Bag(s)";
js_uoms[7] = "Block(s)";
js_uoms[8] = "Bottle(s)";
js_uoms[9] = "Bucket(s)";
js_uoms[10] = "Bundle(s)";
js_uoms[11] = "Can(s)";
js_uoms[12] = "Cup(s)";
js_uoms[13] = "Dozen";
js_uoms[14] = "Jar";
js_uoms[15] = "Kg(s)";
js_uoms[16] = "Pieces";
js_uoms[17] = "Packet(s)";
js_uoms[18] = "Ptn";
js_uoms[19] = "Ream(s)";
js_uoms[20] = "Sat";
js_uoms[21] = "Tab";
js_uoms[22] = "Tin(s)";
js_uoms[23] = "Yard(s)";
js_uoms[24] = "Gallon(s)";
js_uoms[25] = "Pairs";
js_uoms[26] = "Create(s)";
js_uoms[27] = "Length";
js_uoms[28] = "Pound(s)";
js_uoms[29] = "Roll(s)";
js_uoms[30] = "Inch(es)";
js_uoms[31] = "Set(s)";
js_uoms[32] = "Tot(s)";
js_uoms[33] = "Trip(s)";
js_uoms[34] = "Jug(s)";
js_uoms[35] = "Glass(es)";
js_uoms[36] = "Slim Jim";
js_uoms[37] = "Square Metre(s)";
js_uoms[38] = "Group";
js_uoms[39] = "Each";
js_uoms[40] = "Carton";
js_uoms[41] = "Portion";
js_uoms[42] = "Booklet(s)";
js_uoms[43] = "Drum(s)";
js_uoms[44] = "Sachet(s)";
js_uoms[45] = "Wrap(s)";
js_uoms[46] = "Sheet(s)";
js_uoms[47] = "Meter(s)";