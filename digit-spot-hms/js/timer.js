

function formatAMPM() {
var d = new Date(),
    minutes = d.getMinutes().toString().length == 1 ? '0'+d.getMinutes() : d.getMinutes(),
    hours = d.getHours().toString().length == 1 ? '0'+d.getHours() : d.getHours(),
    ampm = d.getHours() >= 12 ? 'PM' : 'AM',
    months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
return /*days[d.getDay()]+'. '+months[d.getMonth()]+' '+d.getDate()+', '+d.getFullYear();+' '+*/ 'LAST ACTIVE: '+hours+':'+minutes+ampm;
}



function date_time(id)
{
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
    d = date.getDate();
    day = date.getDay();
    days = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
    h = date.getHours();
    ampm = date.getHours() >= 12 ? 'PM' : 'AM';
    
    if(h<10)
    {
        h = "0"+h;
    }

    m = date.getMinutes();

    if(m<10)
    {
        m = "0"+m;
    }

    s = date.getSeconds();

    if(s<10)
    {
        s = "0"+s;
    }

    //result = '<table><tr><td>'+days[day]+'. '+months[month]+' '+d+', '+year+'.</td><td><div style="background: #ff0000; color: #fff; padding: 7px; border-radius: 25px; -webkit-border-radius: 25px; -ms-border-radius: 25px"> '+h+':'+m+':'+s+'</div></td></tr></table>';
	result = h+':'+m+':'+s+' '+ampm;
    if(document.getElementById(id)) { document.getElementById(id).innerHTML = result; }
    setTimeout('date_time("'+id+'");','1000');
    return true;
}


