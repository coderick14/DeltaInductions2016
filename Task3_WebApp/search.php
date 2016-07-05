<?php
  if(isset($_GET['searchVal']) && !empty($_GET['searchVal'])) {
    include('connect.php');
    $output = "";
    $searchq = $_GET['searchVal'];
    $searchq = preg_replace("#[^0-9a-z]#i",'',$searchq);
    $sqlquery = "SELECT user_name FROM users where user_name LIKE '%".$searchq."%'";
    $result = mysqli_query($dbcon,$sqlquery);
    if($result) {
      if(mysqli_num_rows($result)==0) {
        $output = "<div class='res'>No suggestions match your search</div>";
      }
      else {
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))  {
          $username = $row['user_name'];
          $output.="<div class='res'><a class='res' href='viewprofile.php?name=".$username."'>".$username."</a></div>";
        }
      }
    }
    else {
      die("Error searching for results");
    }
    echo $output;
  }
?>
