<?php
  session_start();
  $user = $_SESSION['user'];
  $pass = $_SESSION['pass'];
  if(!$user)  {
    header("Location: login.php");  //If not logged in, redirect to login page
  }
  else {
    include('connect.php');

    $mailErr=$phoneErr=$picErr=$passErr=$newpassErr="";

    if(isset($_POST['submit'])) {

      $flag = 1;
      $passUpdate = 0;

      if(!filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL)) { //validate email
        $mailErr = "Invalid email format";
        $flag = 0;
      }
      if(!preg_match("/^\d{10}$/",$_POST['phone'])) {   //validate phone number
        $phoneErr = "Phone number must have 10 digits";
        $flag = 0;
      }
      if(!empty($_POST['oldpass']) && !empty($_POST['newpass']) && !password_verify($_POST['oldpass'],$pass))  {
        $passErr = "Incorrect password";
        $flag = 0;
      }
      elseif(!empty($_POST['oldpass']) && empty($_POST['newpass'])) {
        $newpassErr = "This field cannot be left blank";
        $flag = 0;
      }
      elseif(empty($_POST['oldpass']) && !empty($_POST['newpass'])) {
        $passErr = "This field cannot be left blank";
        $flag = 0;
      }

      //validate profile pic, if the user has uploaded one
      if(!empty($_FILES['profilepic']['name'])) {
        $target_dir = "profilepictures/";
        $target_file = $target_dir.basename($_FILES['profilepic']['name']);
        //echo $target_file;
        $file_extension = pathinfo($target_file,PATHINFO_EXTENSION);
        $check = getimagesize($_FILES['profilepic']['tmp_name']);
        if(!$check) {
          $picErr = "File is not an image";
          $flag = 0;
        }
        //uncomment the following elseif block when you have a large number of users
        /*elseif(file_exists($target_file)) {
          $picErr = "File already exists. Try renaming your file";
          $flag = 0;
        }*/
        elseif($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
          $picErr = "Sorry!! Only jpg,jpeg and png formats supported";
          $flag = 0;
        }
        elseif(!is_uploaded_file($_FILES['profilepic']['tmp_name']) || $_FILES['profilepic']['error'] != 0) {
          $picErr = "Sorry!! File could not be uploaded";
          $flag = 0;
        }

        if($flag) {
          if(!move_uploaded_file($_FILES['profilepic']['tmp_name'],$target_file)) {
            $picErr = "Error uploading file";
            $flag = 0;
          }
        }
      }

      if($flag) {
        if(!empty($_POST['oldpass']) && !empty($_POST['newpass']))  {
          $newPass = password_hash($_POST['newpass'],PASSWORD_BCRYPT);
          $sqlupdate = $dbcon->prepare("UPDATE users SET user_pass=?,user_mail=?,user_phone=? WHERE user_name=?");
          $sqlupdate->bind_param("ssis",$newPass,$_POST['mail'],$_POST['phone'],$user);
          $passUpdate = 1;
        }
        else {
          $sqlupdate = $dbcon->prepare("UPDATE users SET user_mail=? ,user_phone=? WHERE user_name=?");
          $sqlupdate->bind_param("sis",$_POST['mail'],$_POST['phone'],$user);
        }
        $update_result = $sqlupdate->execute();
        if($update_result) {
          //echo "Details updated successfully";
          if($passUpdate)
            $_SESSION['pass'] = $newPass;   //will be executed only if password has been updated
        }
        else {
          die("Error code : ".$dbcon->error);
        }
        if(!empty($_FILES['profilepic']['name'])) {
          $sqlupdate = $dbcon->prepare("UPDATE users SET user_pic=? WHERE user_name=?");
          $sqlupdate->bind_param("ss",$target_file,$user);
          $update_result = $sqlupdate->execute();
          //unlink($pic);
          if(!$update_result)   {
            die("Error updating database : ".$dbcon->error);
          }
        }
      }

    }

    $sqlview = $dbcon->prepare("SELECT user_mail,user_phone,user_pic FROM users WHERE user_name=?");
    $sqlview->bind_param("s",$user);
    $sqlview->execute();
    $sqlview->bind_result($mail,$phone,$pic);
    $result = $sqlview->fetch();

    if($result == FALSE) {
      die("Error code :".$dbcon->error);
    }

?>

  <html>
    <head>
      <title>Edit Profile</title>
      <meta charset="utf-8" />
      <link rel="stylesheet" href="profile.css" />
      <style>
        .error  {
          color: red;
        }
        input[type=text], input[type=password], input[type=email] {
          width : 150px;
          padding: 0.5em;
          margin: 0.5em;
          color: white;
          text-align: center;
          background-color: rgba(0,0,0,0);
          border: 1px solid rgba(0,0,0,0);
          border-bottom: 1px solid white;
          transition: width 0.5s ease-in-out;
        }
        input[type=text]:focus, input[type=password]:focus, input[type=email]:focus {
          width : 250px;
          border-bottom: 1px solid lightblue;
        }
      </style>
    </head>
    <body>
      <ul>
        <li>
          <form>
            <input type="search" name="search" onkeyup="srch(this.value)" placeholder="Search for people"/>
          </form>
        </li>
        <li><a href="profile.php">View Profile</a></li>
        <li><a href="editprofile.php">Edit Profile</a></li>
        <li><a href="addpost.php">Add post</a></li>
        <li style="float:right"><a href="logout.php">Sign Out</a></li>
      </ul>
      <div id="searchresults"></div>
      <br /><br />
      <h1>Edit your profile, <?php echo $user; ?>!</h1><br />
      <div id="details">
        <img src="<?php echo $pic; ?>" id="propic" width="200px" height="200px"/>
        <div style="display:inline-block">
          <form method="post" action="editprofile.php" enctype="multipart/form-data">
            Username &emsp;&emsp;&emsp;&emsp;:
            <input type="text" name="uname" value="<?php echo $user; ?>" readonly/><br />
            E-mail &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:
            <input type="email" name="mail" value="<?php echo $mail; ?>" required/>
            <span class="error"><?php echo $mailErr; ?></span>
            <br />
            Phone &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:
            <input type="text" name="phone" value="<?php echo $phone; ?>" required/>
            <span class="error"><?php echo $phoneErr; ?></span>
            <br />
            Profile Picture &emsp;&emsp;:
            <input type="file" name="profilepic" />
            <span class="error"><?php echo $picErr; ?></span>
            <br /><br />
            <h3>Change your password (optional)</h3>
            Old Password&emsp;&emsp;&emsp;:
            <input type="password" name="oldpass" />
            <span class="error"><?php echo $passErr; ?></span>
            <br />
            New Password &emsp;&emsp;:
            <input type="password" name="newpass" />
            <span class="error"><?php echo $newpassErr; ?></span>
            <br /><br />
            <input type="submit" name="submit" value="Save Changes" />
          </form>
        </div>
      </div>
      <script src="ajaxlivesearch.js" type="text/javascript"></script>

    </body>
  </html>

<?php
  mysqli_close($dbcon);
  }
?>
