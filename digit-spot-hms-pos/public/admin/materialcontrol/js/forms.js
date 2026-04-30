//forms generator js
//copyright 2022

var curObj = "";

if(document.getElementById('sign-in')) {
	
	curObj = "sign-in";

	formObjects.formName = "login";
	formObjects.formFx = "wgt_login(event)";
	formObjects.formButton = 1;
	formObjects.formButtonvalue = "Log In";

	formObjects.formList.push(
		{"fields":1,"design":"lineset","label":"Username","widget":[{"docElem":"text","docName":"userid","docHold":"Username","docRef":"","docEvent":"","docAttr":"required=required x-autocompletetype=given-name"}]}
	);

	formObjects.formList.push(
		{"fields":1,"design":"lineset","label":"Password","widget":[{"docElem":"password","docName":"pwd","docHold":"Password","docRef":"","docEvent":"","docAttr":"required=required autocomplete=new-password"}]}
	);
}

if(document.getElementById('f-creator')) {
	
	curObj = "f-creator";

	formObjects.formName = "item";
	formObjects.formFx = "";
	formObjects.formButton = 4;
	formObjects.formButtonvalue = "Apply";

	formObjects.formList.push(
		{"fields":1,"design":"boxset","label":"Enter Category","widget":[{"docElem":"textarea","docName":"items","docHold":"Enter Category","docRef":"","docEvent":"","docAttr":"required=required onkeyup=wordpack(this.id)"}]}
	);
}

window.onload = () => {
	if(curObj !== null && curObj != '' && curObj != 'undefined') {
		jsCreateform(curObj,formObjects);
		var cfm = setInterval(() => {
			if(document.getElementById(formObjects.formName)) {
				wgt_token('fToken');
				clearInterval(cfm);
			}
		},1000);
	}
}