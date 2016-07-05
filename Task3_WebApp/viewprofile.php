<?php
  session_start();
  $user = $_SESSION['user'];
  if(!$user)  {
    header("Location: login.php");
  }
  elseif(!isset($_GET['name']) || empty($_GET['name'])) {
    header("Location: profile.php");
  }
  else {
    include('connect.php');
    $user_viewed = htmlspecialchars($_GET['name']);
    $viewquery = $dbcon->prepare("SELECT user_mail,user_phone,user_pic,user_time FROM users WHERE user_name=?");
    $viewquery->bind_param("s",$user_viewed);
    $viewquery->execute();
    $viewquery->bind_result($mail,$phone,$pic,$reg_time);
    $viewresult = $viewquery->fetch();

    if(!$viewresult) {
      die("Error code : ".$dbcon->error);
    }
?>

<html>
  <head>
    <title>View Profile</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="profile.css" />
  </head>
  <body>
    <ul>
      <li>
        <form>
          <input type="search" name="search" onkeyup="srch(this.value)" placeholder="Search for people" />
        </form>
      </li>
      <li><a href="profile.php">View profile</a></li>
      <li><a href="editprofile.php">Edit profile</a></li>
      <li><a href="addpost.php">Add post</a></li>
      <li style="float:right"><a href="logout.php">Sign Out</a></li>
    </ul>
    <div id="searchresults"></div>
    <br /><br />
    <h1>Profile : <?php echo $user_viewed; ?></h1><br />
    <div id="details">
      <img src="<?php echo $pic; ?>" id="propic" width="200px" height="200px"/>
      <br />
      <div>
        <h2>General Info :</h2>
        <br />
        <h3>Username : <?php echo $user_viewed; ?></h3>
        <h3>E-mail : <?php echo $mail; ?></h3>
        <h3>Phone : <?php echo $phone; ?></h3>
        <h3>Joined on : <?php echo $reg_time; ?></h3>
      </div>
    </div>
    <br /><br />
    <h1>Posts by <?php echo $user_viewed; ?></h1><br />
    <?php

      $viewquery->close();
      $postquery = $dbcon->prepare("SELECT post_id,post_topic,post_content,post_time FROM posts WHERE post_by=?");
      $postquery->bind_param("s",$user_viewed);
      $postquery->execute();
      $postquery->bind_result($id,$topic,$content,$ptime);
      if($postquery->fetch() === NULL) {
            echo $user_viewed." has not made any posts yet<br><br>";
      }
      else {
        do {
         echo "<div class='myPost'><span class='postWriter'>".$user_viewed."</span> wrote a post about <span class='postTopic'>".$topic."</span> on <span class='postTime'>".$ptime."</span><br/><br/>".$content."</div>";
         echo "<br>";
         } while ($postquery->fetch());
      }

    ?>
    <script src="ajaxlivesearch.js" type="text/javascript"></script>
  </body>
</html>

<?php
  mysqli_close($dbcon);
  }
?>
