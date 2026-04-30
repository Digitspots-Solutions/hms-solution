// js file

//var docpath = 'http://www.vis.ng/';

function slider(cbutton,show,str)
{
	var b = document.getElementById(cbutton);
	var d = document.getElementById(show);
	var w = str.split(',');
	
	
	if(b.lang == 'h')
	{
		d.style.left = 0;
		document.getElementById(w[0]).style.display = 'inline';
		document.getElementById(w[1]).style.display = 'inline';
		
		b.lang = 's';
	}
	else if(b.lang = 's')
	{
		d.style.left = '';
		document.getElementById(w[0]).style.display = 'none';
		document.getElementById(w[1]).style.display = 'none';
		
		b.lang = 'h';
	}
	
}

//-----------------------------------------------------------------------------------------------end function

function onoffObj(obj,ctrl)
{
	var flip_ctrl;

	flip_ctrl = document.getElementById(ctrl);

	if(flip_ctrl.lang == 'h')
	{
		flip_ctrl.lang = 's';
		document.getElementById(obj).style.display='block';
	}
	else if(flip_ctrl.lang == 's')
	{
		flip_ctrl.lang = 'h';
		document.getElementById(obj).style.display='none';
	}	
}

//-----------------------------------------------------------------------------------------------end function

function getdata(obj,ses,str)
{
	var xhr,file,getstring;

	if(str != '')
	{
		if(str >= 1) { getstring = str; } else { if(document.getElementById(str)) { getstring = document.getElementById(str).value; } }

		if(window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		file = phpfile+"dbquery.php?data="+getstring+"&r="+ses+"&dataSend=200";
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
			}
		}
		
		xhr.send();
	}
}

//-----------------------------------------------------------------------------------------------end function

function pagescroller(scroller,obj)
{
	//var divElem = document.getElementById(scroller);
	//var y = divElem.scrollTop;

	/*if(y >= 20) {
		document.getElementById(obj).style.background='#e8e8e8';
	} else if(y <= 5) {
		document.getElementById(obj).style.background='none';
	}*/

	var win = window.pageYOffset;

	if(win >= 50) {
		document.getElementById(obj).style.background='#000066';
		document.getElementById(obj).style.position='fixed';
		document.getElementById(obj).style.top='-10px';
		document.getElementById(obj).style.left='0px';
		document.getElementById(obj).style.right='0px';
		document.getElementById(obj).style.paddingTop='15px';
		document.getElementById(obj).style.paddingBottom='15px';
	} else if(win <= 5) {
		document.getElementById(obj).style.background='none';
		document.getElementById(obj).style.position='relative';
		document.getElementById(obj).style.paddingTop='0px';
		document.getElementById(obj).style.paddingBottom='0px';
		document.getElementById(obj).style.top='3px';
	}
}

//-----------------------------------------------------------------------------------------------end function

function ondisplayObj(obj,str)
{
	document.getElementById(obj).style.display=str;
}

//-----------------------------------------------------------------------------------------------end function

function postmail()
{
	var params,f1,f2;

	f1 = document.getElementById('newsletter-subscription').value;
   	if(document.getElementById('phone')) {
   		f2 = document.getElementById('phone').value;
   	} else {
   		f2 = '';
   	}

   	document.getElementById('newsletter-subscription').value = 'adding mail..';

	if(window.XMLHttpRequest) { var xhr = new XMLHttpRequest(); }
	else { var xhr = new ActiveXObject("Microsoft.XMLHTTP"); }

	params = 'f=mailing&formfield1='+f1+'&formfield2='+f2+'&dataSend=200';
	var file = phpfile+"post_form_data.php";
   	
    xhr.onreadystatechange=stateChanged;
   	xhr.open('POST', file, true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    function stateChanged()
    {
      if(xhr.readyState == 4)
      {
        if(xhr.status == 200)
        {
        	document.getElementById('newsletter-subscription').value = xhr.responseText;
        	setTimeout(function() { ondisplayObj('winx','none'); },1500);
        }
      }
  	}

  	xhr.send(params);
  	
}

//-----------------------------------------------------------------------------------------------end function

function sendmail(e)
{
	e.preventDefault();
	
	//select form data into variable
	var xhr,params,file,cf1,cf2,cf3,cf4,json_response,removeWhiteSpace;
	
	cf1 = document.getElementById('yourname').value;
	cf2 = document.getElementById('phone').value;
	cf3 = document.getElementById('emailaddress').value;
	cf4 = document.getElementById('note').value;
	
	
	if(cf1 !== null && cf2 !== null && cf3 !== null && cf4 !== null)
	{
		document.getElementById('messenger').style.display = 'block';
		document.getElementById('messenger').innerHTML = 'Sending message';
		document.getElementById('form-submit-1').innerHTML = 'Loading..';
		
		
		//pack the variables into concatenantion method
		params = 'f=cmail&formfield1='+cf1+'&formfield2='+cf2+'&formfield3='+cf3+'&formfield4='+cf4+'&dataSend=200';
	
		if(window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	
		file = phpfile+"post_form_data.php";
		
		xhr.onreadystatechange=stateChanged;
		xhr.open('POST', file, true);
		
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		function stateChanged()
		{
			if(xhr.readyState == 4)
			{
				if(xhr.status == 200)
				{
					json_response = xhr.responseText;
					removeWhiteSpace = json_response.replace(/\s+/g,'');
					
					if(removeWhiteSpace == 2)
					{
						document.getElementById('mailling').reset();
						document.getElementById('messenger').style.display = 'block';
						document.getElementById('messenger').innerHTML = 'Thank you! Your message has been sent to our customer service center';
						document.getElementById('form-submit-1').innerHTML = '<input type="submit" name="mailbutton" value="&nbsp;&nbsp; Send Message &nbsp;&nbsp;" class="submit pads12 rounded-button blue-white-state">';
					}
					else if(removeWhiteSpace == 1)
					{
						document.getElementById('messenger').style.display = 'block';
						document.getElementById('messenger').innerHTML = 'Unable to send message! Please try again';
						document.getElementById('form-submit-1').innerHTML = '<input type="submit" name="mailbutton" value="&nbsp;&nbsp; Send Message &nbsp;&nbsp;" class="submit pads12 rounded-button blue-white-state">';
					}
				}
				else
				{
					document.getElementById('mailling').reset();
					document.getElementById('messenger').style.display = 'block';
					document.getElementById('messenger').innerHTML = 'Error connecting server. Please try again';
					document.getElementById('form-submit-1').innerHTML = '<input type="submit" name="mailbutton" value="&nbsp;&nbsp; Send Message &nbsp;&nbsp;" class="submit pads12 rounded-button blue-white-state">';
				}
			}
		}
		
		xhr.send(params);
	}
	else
	{
		document.getElementById('messenger').style.display = 'block';
		document.getElementById('messenger').innerHTML = 'Sorry, all fields are compulsory';
	}
}

//-----------------------------------------------------------------------------------------------end function

function searchform(e)
{
	if(e.KeyCode == 13 || e.which == 13)
	{
		var f1 = document.getElementById('searchfield').value;
		window.location.href = 'search-engine?ui='+f1;
	}
}

//-----------------------------------------------------------------------------------------------end function