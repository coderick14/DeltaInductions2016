var dd,mm,yy,hr,mins,name; // to get user input of deadline and event name
var x;                     // to set and clear the interval of countdown()
var deadline;              // to store deadline input as Date object

function countdown()
{
  var curTime =new Date();
  curTime=curTime.getTime();
  var diff=deadline - curTime;
  diff=Math.round(diff/1000);
  if(diff<0)               //if deadline is before the current system
  {
    window.alert('Gone are those days!!');
    clearInterval(x);
    location.reload();
  }
  var sec=diff%60;
  diff=Math.floor(diff/60);
  var mi=diff%60;
  diff=Math.floor(diff/60);
  var hh=diff%24;
  var days=Math.floor(diff/24);
  document.getElementById('Days').innerHTML=((days<10)?'0'+days:days) + '<br/>'+'Days';
  document.getElementById('Hours').innerHTML=((hh<10)?'0'+hh:hh)+'<br/>'+'Hours';
  document.getElementById('Mins').innerHTML=((mi<10)?'0'+mi:mi)+'<br/>'+'Minutes';
  document.getElementById('Secs').innerHTML=((sec<10)?'0'+sec:sec)+'<br/>'+'Seconds';

  if(!sec && !mi && !hh && !days)  //when all are 0, deadline has been reached
  {
    clearInterval(x);
    window.alert('Hey!! Your event '+name+ ' has started!!');
    location.reload();
  }
}
function startCount()
{
  dd=document.getElementById('date').value;
  mm=document.getElementById('month').value - 1; //since JS months start from 0
  yy=document.getElementById('year').value;
  hr=document.getElementById('hours').value;
  mins=document.getElementById('minutes').value;
  name=document.getElementById('event_name').value;

  validateInput();  // to validate the user input in date_validate.js

  deadline=new Date(yy,mm,dd,hr,mins);
  deadline=deadline.getTime();
  x=setInterval(countdown,1000);
}
function stopCount()
{
  clearInterval(x);
}
function resetCount()
{
  clearInterval(x);
  var arr=['Days','Hours','Minutes','Seconds'];
  var fields=document.getElementsByTagName('input');
  for(i=0;i<fields.length;i++)
    fields[i].value=fields[i].defaultValue;
  var timeLeft=document.getElementsByClassName('timer');
  for(i=0;i<timeLeft.length;i++)
      timeLeft[i].innerHTML='00'+'<br/>'+arr[i];
}
