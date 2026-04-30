//js file by Ibat Informatics

function slideInOut()
{
	var unslide,slide,innerobj,tp,bt;

	unslide = document.getElementById('sidebar');
	slide = document.getElementById('workspace');
	innerobj = document.getElementById('text-link');
	innericon = document.getElementById('icons');

	tp = document.getElementById('text-link-tp');
	bt = document.getElementById('text-link-bt');
	

	if(unslide.lang == 's')
	{
		tp.style.display='none';
		bt.style.display='none';

		//slide.className="motion";
		unslide.style.width="3%";
		slide.style.width="95%";
		innerobj.style.width=0;
		innericon.style.width="100%";

		unslide.lang = 'h';
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
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function module(tab,page)
{
	var tabs,i;

	tabs = ['tab1','tab2','tab3','tab4','tab5'];

	document.getElementById(tab).className = "block-element box-border-dark-thick-bottom pads12 anchor black-theme grey-font";

	for(i=0; i < tabs.length; ++i)
	{
		if(tabs[i] != tab)
		{
			document.getElementById(tabs[i]).className = "block-element box-border-dark-thick-bottom pads12 overstate-background-seven anchor";
		}
	}

	window.frameworks.location.href = page;
}

//---------------------------------------------------------end----------------------------------------------------------------------//



//---------------------------------------------------------end----------------------------------------------------------------------//

function formrequired(str)
{
	if(document.getElementById(str))
	{
		document.getElementById(str).required = true;
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function formfocus(str)
{
	if(document.getElementById(str))
	{
		document.getElementById(str).focus();
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function objDisplay(obj)
{
	if(document.getElementById(obj))
	{
		document.getElementById(obj).style.display='block';
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function objHidden(obj)
{
	if(document.getElementById(obj))
	{
		document.getElementById(obj).style.display='none';
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function pagetoTop(obj)
{
	if(document.getElementById(obj))
	{
		document.getElementById(obj).scrollTop = 0;
	}
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function htmlFormReset(str)
{
	document.getElementById(str).reset();
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function htmlpassval(str,obj)
{
	document.getElementById(obj).value = str;
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function disableBarcodeAutoSubmit()
{
  return !(window.event && window.event.keyCode == 13);
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function htmlFormField(obj,str)
{
	document.getElementById(obj).type = str;
}

//---------------------------------------------------------end----------------------------------------------------------------------//

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
			}
		}
	}
	
	xhr.send(params);
}

//---------------------------------------------------------end----------------------------------------------------------------------//

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
		}
	  }
	  img.src=e.target.result;
	 }
	 
	fr.readAsDataURL(e.target.files[0]);
}

//---------------------------------------------------------end----------------------------------------------------------------------//



//---------------------------------------------------------end----------------------------------------------------------------------//

function ctimer()
{
	newdate = new Date;
	day = newdate.getDay();
    days = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
    h = newdate.getHours();
    m = newdate.getMinutes();
    ampm = h >= 12 ? 'PM' : 'AM';

    //if(h<10) { h = "0"+h; }
    if(m<10) { m = "0"+m; }

    return days[day]+'. '+h+':'+m+' '+ampm;
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function writeObjheader(obj,text)
{
	document.getElementById(obj).innerHTML = text;
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function writeObjval(obj,str)
{
	document.getElementById(obj).value = str;
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function unbeeper()
{
	var audio = new Audio('media/beep-02.mp3');
	audio.play();
}

//---------------------------------------------------------end----------------------------------------------------------------------//

function beeper(str,msesid)
{
	var audio,incomingdata,storedata,getstoredata,do_count,countbeep,stopbeep;
	audio = new Audio('media/beep-02.mp3');

	if(sessionStorage.getItem(msesid) == '' || sessionStorage.getItem(msesid) == 'undefined') {
		storedata = sessionStorage.setItem(msesid,0);
	}

	if(document.getElementById(str)) {
		incomingdata = document.getElementById(str).value;
	}
	else {
		incomingdata = 0;
	}

	getstoredata = sessionStorage.getItem(msesid);
	
	if(incomingdata > getstoredata) {
		sessionStorage.setItem(msesid,incomingdata);
		audio.play();
	}
}


//-----------------------------------------------------------------------------------------------end function

function getdata(obj,ses,str,opt)
{
	var xhr,file,todata,usesid;

	if(str != '')
	{
		if(opt == 'selectbox') { 
			document.getElementById(obj).innerHTML = '<option value="">fetching data..</option>';
		} else { 
			document.getElementById(obj).innerHTML = '<div class="loading"></div><br><h3 class="nobold black-font large">LOADING</h3>';
		}
		
		if(str >= 1) { usesid = str; } else { if(document.getElementById(str)) { usesid = document.getElementById(str).value; } }
		
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
					document.getElementById(obj).innerHTML = xhr.responseText;
					return 2;
				}
				else
				{
					if(opt == 'selectbox') { 
						document.getElementById(obj).innerHTML = '<option value="">Error fetching data!</option>';
					} else { 
						document.getElementById(obj).innerHTML = 'Error loading content!';
					}

					return 1;
				}
			}
		}
		
		xhr.send();
	}
}

//-----------------------------------------------------------------------------------------------end function

function selectall(str)
{
	var i;

	if(document.getElementById(str).lang == 'n')
	{
		document.getElementById(str).lang = 'a';

		for(i=0; i < 31; i++)
		{
			if(document.getElementById('chk-'+str+i)) {
				document.getElementById('chk-'+str+i).checked = true;
			}
		}
	}
	else if(document.getElementById(str).lang == 'a')
	{
		document.getElementById(str).lang = 'n';

		for(i=0; i < 31; i++)
		{
			if(document.getElementById('chk-'+str+i)) {
				document.getElementById('chk-'+str+i).checked = false;
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

	console.log(npageurl+'&pg='+cpg+'&start='+pgstart+'&limit='+pglimit);

	window.location.href = npageurl+'&pg='+cpg+'&start='+pgstart+'&limit='+pglimit;
}

//-----------------------------------------------------------------------------------------------end function