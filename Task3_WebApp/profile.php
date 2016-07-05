<?php
  session_start();
  $user = $_SESSION['user'];
  if(!$user)  {
    header("Location: login.php");
  }
  else {
    include('connect.php');
    $viewprofile = $dbcon->prepare("SELECT user_mail,user_phone,user_pic,user_time FROM users WHERE user_name=?");
    $viewprofile->bind_param("s",$user);
    $viewprofile->execute();
    $viewprofile->bind_result($mail,$phone,$pic,$reg_time);
    $result = $viewprofile->fetch();
    if($result == FALSE) {
      die("Error ocuured :".$dbcon->error);
    }

    if(isset($_POST['postId']) && !empty($_POST['postId'])) {
        $viewprofile->close();
        $delpost = $dbcon->prepare("DELETE FROM posts WHERE post_id=?");
        if($delpost)  {
          $delpost->bind_param("i",$_POST['postId']);
        }
        else {
          die("Error preparing statement");
        }
        $delresult = $delpost->execute();
        if(!$delresult)  {
          die("Error deleting post");
        }
      }

?>

  <html>
    <head>
      <title>Profile</title>
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
        <li><a href="profile.php">View Profile</a></li>
        <li><a href="editprofile.php">Edit Profile</a></li>
        <li><a href="addpost.php">Add post</a></li>
        <li style="float:right"><a href="logout.php">Sign Out</a></li>
      </ul>
      <div id="searchresults"></div>
      <br /><br />
      <form id="myForm" method="post" action="profile.php">
        <input type="hidden" name="postId" />
      </form>
      <h1>Welcome to PostIt, <?php echo $user; ?>!</h1><br />
      <div id="details">
        <img src="<?php echo $pic; ?>" id="propic" width="200px" height="200px"/>
        <br />
        <div>
          <h2>Your details :</h2>
          <br />
          <h3>Username : <?php echo $user; ?></h3>
          <h3>E-mail : <?php echo $mail; ?></h3>
          <h3>Phone : <?php echo $phone; ?></h3>
          <h3>Joined on : <?php echo $reg_time; ?></h3>
        </div>
      </div>
      <br /><br />
      <h1>Your posts</h1><br />
      <?php
      $viewprofile->close();
        //$delpost->close();
      $postquery = $dbcon->prepare("SELECT post_id,post_topic,post_content,post_time FROM posts WHERE post_by=?");
      $postquery->bind_param("s",$user);
      $postquery->execute();
      $postquery->bind_result($id,$topic,$content,$ptime);
      if($postquery->fetch() === NULL) {
            echo "You have not made any posts yet<br><br>";
      }
      else {
        do {
         echo "<div class='myPost'><span class='postWriter'>You</span> wrote a post about <span class='postTopic'>".$topic."</span> on <span class='postTime'>".$ptime."</span><br/><br/>".$content."</div>";
          echo "<button style='color:red' onclick='delPost(".$id.")'>Delete post &times;</button><br>";
         } while ($postquery->fetch());
      }

      ?>
      <script src="ajaxlivesearch.js" type="text/javascript"></script>
      <script type="text/javascript">
        function delPost(postId)  {
          var cnf = window.confirm("Are you sure you want to delete this post??");
          if(cnf) {
            document.getElementById('myForm').elements[0].value = postId;
            document.getElementById('myForm').submit();
          }
        }
      </script>
    </body>
  </html>

<?php
  }
?>
