// js file

function xform(string) {
	
	var tbx="'fbox'", bxStream, bxHeader, vbxHeader;

	bxStream = '<div id="fbox-show" class="fx-position-stick btscr motion" align="center">';
	bxStream += '<div id="fbox-xshow" class="fx-width-90 xfadein motion">';
	bxStream += '</div>';
	bxStream += '</div>';

	bxHeader = '<p class="bottom-pull-20 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="jsClose('+tbx+')"><b class="mbri-close"></b></a></span>';
	bxHeader += '<h2 class="large nobold nunito-bold">'+datastring.tip+'</h2>';
	bxHeader += '<div class="fx-width-80 box-border-thick xsml-rounded-button top-push-20 bottom-push-30 pads20 noscroll">';
	bxHeader += '<div class="cs-width-250 cs-height-5 bottom-push-10 grey-theme"></div>';
	bxHeader += '<div id="fbox-content" class="pads30" align="left">';
	bxHeader += '<div class="cs-height-20 sml-rounded-button grey-theme bottom-push-10 noscroll"><div class="wiper"></div></div><div class="nc-width-90 cs-height-20 sml-rounded-button grey-theme bottom-push-30 noscroll"><div class="wiper"></div></div>';
	bxHeader += '</div>';
	bxHeader += '</div>';

	vbxHeader = '<p class="bottom-pull-20 alignrt"><a href="javascript://" class="black-font" title="Close" onclick="jsClose('+tbx+')"><b class="mbri-close"></b></a></span>';
	vbxHeader += '<h2 class="large nobold nunito-bold">'+datastring.tip+'</h2>';
	vbxHeader += '<div class="fx-width-90 box-border-thick sml-rounded-button top-push-30 bottom-push-30 pads10 noscroll">';
	vbxHeader += '<div class="cs-width-250 cs-height-5 bottom-push-10 grey-theme"></div>';
	vbxHeader += '<div id="fbox-content" class="pads20" align="left"></div>';
	vbxHeader += '</div>';
	
	writeObjheader('fbox',bxStream);

	setTimeout(function() {
		chgclass('fbox-show','fx-position-stick fscr nc-height-100 txp5-white zind-1 motion bottom-pull-50 y-scroll');
		chgclass('fbox-xshow','fx-width-65 cs-margin-top-150 xfadeout white-theme xhover-shadow sml-rounded-button box-border-thick pads30 noscroll motion');
	},500);

	setTimeout(function() {
		//xframedisablescroll();
		parent.document.getElementById('workspace').scrollTop = 0;
		
		if(string == 'htmlform') {
			writeObjheader('fbox-xshow',bxHeader);
			$('#fbox-content').load(curl+'public/admin/materialcontrol/'+datastring.element);
		} else if(string == 'nohtmlform') {
			chgclass('fbox-xshow','fx-width-80 cs-margin-top-150 xfadeout white-theme xhover-shadow sml-rounded-button pads30 noscroll motion');
			writeObjheader('fbox-xshow',vbxHeader);
		}
	},700);
}

//-----------------------------------------------------------------------------------------------end function

function jsClose(string) {
	var fx = string+"-show", sx = string+"-xshow";
	chgclass(fx,'fx-position-stick btscr motion');
	chgclass(sx,'fx-width-90 xfadein motion');

	setTimeout(function() {
		writeObjheader(string,'');
		xframeenablescroll();
	},100);
}

//-----------------------------------------------------------------------------------------------end function

function jsClean() {
	writeObjheader('fbox','');
}

//-----------------------------------------------------------------------------------------------end function

function jsLoadButton(index) {
	var button = loadbuttons[index].buttoname;
	var message = loadbuttons[index].loadmessage;
	htmlpassval(message,button);
}

//-----------------------------------------------------------------------------------------------end function

function returnLoadfile(tab,tabs,page) {
	
	//parent.document.getElementById('pause-page').className = 'fx-position-stick zind-1 fscr txp9-black motion noscroll';
	//parent.document.getElementById('pause-page').innerHTML = '<div class="nc-height-40"></div><div class="loading"></div>';

	sessionStorage.setItem('uri','dev-'+page);
	setTimeout(() => { parent.document.getElementById('wgetfile').click(); },100);

	for(var i = 1; i <= tabs; i++) {
		if(tab == 'tab-'+i) {
			sessionStorage.setItem('tab',i);
			chgclass('tab-'+i,'ln-display-box float-left right-pull-30 bottom-pull-10 left-pull-30 box-3border-thick-bottom anchor motion-x');
		} else {
			chgclass('tab-'+i,'ln-display-box float-left right-pull-30 bottom-pull-10 left-pull-30 anchor motion-x');
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

function wgtframe(label,dirs) {
	var xlabel = label.split('-');
	var xframe = document.getElementById('xframe');

	chgclass('pause-page','fx-position-stick zind-1 fscr txp9-black motion noscroll');
	writeObjheader('pause-page','<div class="nc-height-40"></div><div class="loading"></div>');

	var wtab = sessionStorage.getItem('tab');
	xframe.src = curl+dirs+"workspace"+exts+"?logs="+xlabel[1]+"&tag="+xlabel[0]+"&tab="+wtab;
	xframe.onload = function() {
		chgclass('pause-page','fx-position-flow btscr motion noscroll');
		writeObjheader('pause-page','');
	}
}

//-----------------------------------------------------------------------------------------------end function

function tabb(tab) {
	const tabs = 3;
	for(var i = 1; i <= tabs; i++) {
		if(tab == i) {
			chgclass('tab-'+i,'top-pull-10 right-pull-7 bottom-pull-10 left-pull-7 blue-theme white-font anchor sml-rounded-button bottom-push-3 motion');
		} else {
			chgclass('tab-'+i,'top-pull-10 right-pull-7 bottom-pull-10 left-pull-7 anchor sml-rounded-button bottom-push-3 motion');
		}
	}
}

//-----------------------------------------------------------------------------------------------end function

function tabmenu(menu,numbr,h) {
	var m = document.getElementById(menu);
	var label = "tab"+numbr, icon = "xtab"+numbr;

	if(m.lang == 'open') {
		m.lang = 'close';
		chgclass(menu,'noshow noscroll motion');
		chgclass(label,'block-element cs-height-40 motion');
		chgclass(icon,'block-element cs-height-40 motion');
	} else if(m.lang == 'close') {
		m.lang = 'open';
		chgclass(menu,'motion');
		chgclass(label,'block-element motion '+h);
		chgclass(icon,'block-element motion '+h);
	}
}

//-----------------------------------------------------------------------------------------------end function

function xslidein() {
	
	var isState = document.getElementById('x-header');

	if(isState.lang == 'out') {
		
		chgclass('left-panel','ln-display-box float-left nc-width-5 nc-height-100 white-blue-faded white-font noscroll motion');
		chgclass('right-panel','ln-display-box float-left nc-width-95 nc-height-100 white-theme noscroll motion');
		chgclass('icons','ln-display-box float-left nc-width-25 motion');
		chgclass('tlabel','noshow motion');

		chgclass('x-header','fx-position-stick zind-2 cs-width-65 top-pull-15 bottom-pull-15 motion');
		chgclass('x-icons','nc-width-100 motion');
		chgclass('x-tlabel','noshow motion');

		isState.lang = "in";

	} else if(isState.lang == 'in') {
		
		chgclass('left-panel','ln-display-box float-left nc-width-20 nc-height-100 white-blue-faded white-font motion');
		chgclass('right-panel','ln-display-box float-left nc-width-80 nc-height-100 white-theme noscroll motion');
		chgclass('icons','ln-display-box float-left nc-width-20 motion');
		chgclass('tlabel','ln-display-box float-left nc-width-80 motion');

		chgclass('x-header','fx-position-stick zind-2 cs-width-270 top-pull-15 bottom-pull-15 motion');
		chgclass('x-icons','ln-display-box float-left nc-width-20 motion');
		chgclass('x-tlabel','ln-display-box float-left nc-width-80 motion');

		isState.lang = "out";
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

function xframedisablescroll() {
	parent.document.getElementById('workspace').className = 'ln-display-box float-right nc-width-79 nc-height-100 white-theme motion noscroll';
}

function xframeenablescroll() {
	parent.document.getElementById('workspace').className = 'ln-display-box float-right nc-width-79 nc-height-100 white-theme motion y-scroll';
}

//-----------------------------------------------------------------------------------------------end function

function scrolLr(dr) {
	var scrolto,elem;
	if(document.getElementById('e-carousel')) {
		elem = document.getElementById('e-carousel');
		if(dr > 0) { scrolto = '-'+dr+'%'; }
		else { scrolto = dr; }
		elem.style.marginLeft = scrolto;
	}
}

//-----------------------------------------------------------------------------------------------end function

function ddel(id,tbl) {
	var wp, li, log, fparam, uri_param, uri_split, uri = window.location.href;
	
	uri_split = uri.split('?');
	wp = uri_split[0]; uri_param = uri_split[1];
	
	if(uri_param.indexOf('&') > -1) { fparam = uri_param.split('&'); log = fparam[0]; }
	else { log = uri_param; }

	window.location.href = wp+'?'+log+'&lit='+id+'&tbl='+tbl+'&curi=delete-record';
}

//-----------------------------------------------------------------------------------------------end function

function qrdel(query) {
	var wp, log, fparam, uri_param, uri_split, uri = window.location.href;
	
	uri_split = uri.split('?');
	wp = uri_split[0]; uri_param = uri_split[1];
	
	if(uri_param.indexOf('&') > -1) { fparam = uri_param.split('&'); log = fparam[0]; }
	else { log = uri_param; }

	window.location.href = wp+'?'+log+'&'+query;
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

const formjS = [];

const formObjects = {
	"formStyle":"standard",
	"formSend":"post",
	"formFx":"",
	"formName":"",
	"formData":"",
	"formTbl":"",
	"formToken":"",
	"formList":[],
	"formButton":0,
	"formButtonvalue":""
}

const txtmonth = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

const loadbuttons = [
	{"buttoname":"submitbutton","loadmessage":"Submitting.."},
	{"buttoname":"applybutton","loadmessage":"Applying.."},
	{"buttoname":"logbutton","loadmessage":"Connecting.."},
	{"buttoname":"updatebutton","loadmessage":"Updating.."}
];

//-----------------------------------------------------------------------------------------------end function

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