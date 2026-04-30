//js file by Ibat Informatics

function logAccount(e)
{
	e.preventDefault();
	
	//select form data into variable
	var xhr,params,file,cf1,cf2,json_response,removeWhiteSpace,htmlresult;
	
	cf1 = document.getElementById('fieldset1').value;
	cf2 = document.getElementById('fieldset2').value;
	
	
	if(cf1 !== null && cf2 !== null)
	{
		document.getElementById('messenger').style.display = 'block';
		document.getElementById('messenger').innerHTML = 'Verifying your login details';
		document.getElementById('form-submit-1').style.display = 'none';
		
		//pack the variables into concatenantion method
		params = 'formfield1='+cf1+'&formfield2='+cf2+'&dataSend=400';
	
		if(window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	
		file = phpfile+"log_account.php";
		
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
					htmlresult = removeWhiteSpace;
					
					if(htmlresult != 'undefined')
					{
						document.getElementById('lform').reset();
						document.getElementById('messenger').innerHTML = 'fetching page..';
						
						window.location = htmlresult;
					}
					else
					{
						document.getElementById('messenger').innerHTML = 'Incorrect username or password!';
						document.getElementById('form-submit-1').style.display = 'block';
					}
				}
				else
				{
					document.getElementById('lform').reset();
					
					document.getElementById('messenger').innerHTML = 'Error connecting, please try again';
					document.getElementById('form-submit-1').style.display = 'block';
				}
			}
		}
		
		xhr.send(params);
		
	}
	else
	{
		document.getElementById('messenger').style.display = 'block';
		document.getElementById('messenger').innerHTML = 'Please provide your login credentials!';
	}
}

//-----------------------------------------------------------------------------------------------end function