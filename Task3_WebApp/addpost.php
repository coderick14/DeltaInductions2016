<?php
    session_start();
    $user = $_SESSION['user'];
    if(!$user) {
      header("Location: login.php");
    }
    else {
      include('connect.php');
      if(isset($_POST['add']))  {
        $topic = htmlspecialchars($_POST['topic']);
        $content = htmlspecialchars($_POST['content']);
        $addpost = $dbcon->prepare("INSERT INTO posts (post_by,post_topic,post_content) VALUES (?,?,?)");

        if($addpost)  {
          $addpost->bind_param("sss",$user,$topic,$content);
        }
        else {
          die("Error preparing statement");
        }
        $result = $addpost->execute();
        if($result) {
          echo "Post added<br>";
        }
        else {
          die("Error inserting into database");
        }
      }
?>
    <html>
      <head>
        <title>PostIt - Add a post</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="profile.css" />
        <style>
          body {
            font-family: calibri,sans-serif;
            color: darkslategray;
            text-align: center;
            background-image: url('key.jpg');
            background-size: cover;
            background-repeat: no-repeat;
          }
          h1,h3  {
            font-weight: 400;
            color: black;
            display: inline-block;
            background-color: rgba(255,255,255,0.5);
            padding: 0.3em;
          }
          input[type=text],textarea  {
            text-align: center;
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 0.5em;
            border: 1px solid rgba(0,0,0,0.7);
            border-radius: 10px;
          }
        </style>
      </head>
      <body>
        <ul>
          <li><a href="profile.php">View profile</a></li>
          <li><a href="editprofile.php">Edit profile</a></li>
          <li><a href="addpost.php">Add post</a></li>
          <li style="float:right"><a href="logout.php">Sign Out</a></li>
        </ul>
        <h1>PostIt - Add your post, <?php echo $_SESSION['user']; ?>!</h1><br/>
        <br/>
        <form action="addpost.php" method="post">
          <h3>Topic</h3><br/>
          <input type="text" name="topic" size="50" maxlength="30" placeholder="Enter the topic for your post" required/><br/>
          <h3>Content</h3><br/>
          <textarea cols="50" rows="15" name="content" placeholder="Write your post here..." required></textarea><br/><br/>
          <input type="submit" name="add" value="Add Post"/>
        </form>
      </body>
    </html>

<?php
    mysqli_close($dbcon);
    }
?>
