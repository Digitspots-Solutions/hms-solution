function json_getdata(jsonCall,tbl,dataid,colkey,val,pack) {
	
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
		url = filePath+"public/admin/materialcontrol/cphp/post_form_data.php";

		xhr.onreadystatechange=function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					//console.log(xhr.responseText);
					var temptkn, jtemptkn, get_temptkn = sessionStorage.getItem('temptkn');
					if(sessionStorage.getItem('temptkn') !== null) { temptkn = JSON.parse(get_temptkn); }
					if(pack == 'array' && xhr.responseText !== null) { temptkn[jsonCall] = JSON.parse(xhr.responseText); }
					else if(pack == 'scalar' && xhr.responseText !== null) { temptkn[jsonCall] = xhr.responseText; }
					jtemptkn = JSON.stringify(temptkn); sessionStorage.setItem('temptkn',jtemptkn);
				}
			}
		};

		xhr.open('POST', url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(params);
	}
}


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


function searchStringInArray(str,strArray) {
    var j,s,sstring;
    for(j=0; j<strArray.length; j++) {
    	sstring = strArray[j].split(' ');
    	for(s=0; s<sstring.length; s++) { if(sstring[s].indexOf(str) == 0) { return sstring[s]; break; } }
    }
    return "nomatch";
}