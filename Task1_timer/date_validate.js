function validateInput()
{
  var dateCheck= yy>=2016 && mm>=0 && mm<=11 && dd>=1 && dd<=31;
  var timeCheck=hr>=0 && hr<=23 && mins>=0 && mins<=59;
  if(!dateCheck || !timeCheck)
  {
    window.alert('Invalid input for event deadline');
    location.reload();
  }
  if(mm==3 || mm==5 || mm==8 || mm==10) //checking for months having 30 days only
  {
    if(dd==31)
    {
      window.alert('The entered month does not have 31 days!!');
      location.reload();
    }
  }
  var leap;
  if(mm==1)
  {
    if(yy%400==0)     //if-else ladder to check for leap year
      leap=true;
    else if(yy%100==0)
      leap=false;
    else if(yy%4==0)
      leap=true;
    else
      leap=false;
    if(leap)
    {
      if(dd>=30)  //if its a leap year, date value can be upto 29
      {
        window.alert('In '+yy+' ,February has only 29 days');
        location.reload();
      }
    }
    else if(dd>=29)  //if its not a leap year, date value can be upto 28
    {
        window.alert('In '+yy+' ,February has only 28 days');
        location.reload();
    }
  }
}
