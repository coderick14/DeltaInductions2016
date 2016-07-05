//function to implement AJAX live search
function srch(searchTxt) {
    if(searchTxt.length != 0) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)  {
          document.getElementById('searchresults').innerHTML = xmlhttp.responseText;
        }
      };
      xmlhttp.open("GET","search.php?searchVal="+searchTxt);
      xmlhttp.send();
    }
    else {
      document.getElementById('searchresults').innerHTML = "";
    }
}
