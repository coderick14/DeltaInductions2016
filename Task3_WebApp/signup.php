<?php
  //session_start();
  include('connect.php');
  $nameErr1=$passErr1=$passErr2=$mailErr=$phoneErr=$picErr=$captchaErr="";

  if (isset($_POST['signup'])) {
    $flag = 1;
    if ($_POST['upass_new1']!=$_POST['upass_new']) {
      $passErr2 = "Passwords do not match!";
      $flag = 0;
    }
    if(!filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL)) {   //validate email
      $mailErr = "Invalid email format";
      $flag = 0;
    }
    if(!preg_match("/^\d{10}$/",$_POST['phone']))  {    //validate phone number
      $phoneErr = "Phone number must have 10 digits";
      $flag = 0;
    }

    //captcha validation

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $privatekey = "Your-private-key";
    $response = file_get_contents($url."?secret=".$privatekey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $data = json_decode($response);
    if (!(isset($data->success) && $data->success==true)) {
      $captchaErr = "Captcha Error!!Try again.";
      $flag = 0;
    }

    //validate image here

    /*check flag value. If some error has already been reported, don't execute this block of code.
    Else this will result in uploading the image to the server. So when the user removes the error
    and submits again, he will get 'file already exists' error*/
    if(!empty($_FILES['profilepic']['name']) && $flag) {
      $target_dir = "profilepictures/";
      $target_file = $target_dir.basename($_FILES["profilepic"]['name']);
      $file_extension = pathinfo($target_file,PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["profilepic"]['tmp_name']);
      if(!$check) {
        $picErr = "File is not an image";
        $flag = 0;
      }
      //uncomment the following elseif block if you havea large number of users
      /*elseif(file_exists($target_file)) {
        $picErr = "File already exists. Please rename your file and try uploading";
        $flag = 0;
      }*/
      elseif($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        $picErr = "Sorry!! Only jpg,png and jpeg images allowed";
        $flag = 0;
      }
      elseif(!is_uploaded_file($_FILES["profilepic"]['tmp_name']) || $_FILES["profilepic"]['error'] != 0) {
        $picErr = "Sorry!! Picture could not be uploaded";
        $flag = 0;
      }

      if($flag) {
        if(!move_uploaded_file($_FILES["profilepic"]['tmp_name'],$target_file)) {
          $picErr = "Sorry!! There was an error uploading the picture";
          $flag = 0;
        }
      }

    }
    elseif(empty($_FILES['profilepic']['name'])) {   //execute only if name is empty
      $picErr = "Please choose a file";
      $flag = 0;
    }


    if ($flag) {
      $uNameNew = htmlspecialchars($_POST['uname_new']);
      $uPassNew = password_hash($_POST['upass_new'],PASSWORD_BCRYPT); //using crypt_blowfish algorithm
      $email = htmlspecialchars($_POST['mail']);
      $phone = $_POST['phone'];


      $sqlinsert = $dbcon->prepare("INSERT INTO users (user_name,user_pass,user_mail,user_phone,user_pic) VALUES (?,?,?,?,?)");
      if($sqlinsert)  {
        $sqlinsert->bind_param("sssis",$uNameNew,$uPassNew,$email,$phone,$target_file);
      }
      else {
        die("Error preparing statement");
      }
      $result = $sqlinsert->execute();
      if($result) {


        echo "<script type='text/javascript'>location.href='login.php'</script>";
      }
      elseif ($dbcon->errno == 1062) {  //mysql duplicate entry error code
        $uniqueError = $dbcon->error;
        if(strpos($uniqueError,"user_name")) {
          $nameErr1 = "Username already exists";
        }
        elseif(strpos($uniqueError,"user_mail")) {
          $mailErr = "Email id already exists";
        }
        else {
          $phoneErr = "Phone number already exists";
        }
      }
      else {
        die("Error inserting into database : ".$dbcon->error);
      }
    }
    mysqli_close($dbcon);
  }
?>

<html>
  <head>
    <title>PostIt - Sign up</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="startpage.css"/>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <header>
      <h1>PostIt</h1>
      <h3>A place to put up your thoughts</h3>
    </header>
    <br/><br/>
      <center>
        <div id="signUp" style="height:44em">
          <form action="signup.php" method="post" enctype="multipart/form-data">
            <h3>Username</h3>
            <input type="text" name="uname_new" maxlength="20" placeholder="Enter your username" required/><br/>
            <span class="error"><?php echo $nameErr1; ?></span>
            <h3>Password</h3>
            <input type="password" name="upass_new" maxlength="20" placeholder="Enter your password" required/><br/>
            <span class="error"><?php echo $passErr1; ?></span>
            <h3>Re-enter password</h3>
            <input type="password" name="upass_new1" maxlength="20" placeholder="Enter your password again" required/><br/>
            <span class="error"><?php echo $passErr2; ?></span>
            <h3>E-mail</h3>
            <input type="email" name="mail" placeholder="Enter your email" required/><br />
            <span class="error"><?php echo $mailErr; ?></span>
            <h3>Phone</h3>
            <input type="text" name="phone" maxlength="10" placeholder="Enter your phone number" required/><br />
            <span class="error"><?php echo $phoneErr; ?></span>
            <h3>Upload your profile picture</h3>
            <input type="file" name="profilepic" required/><br />
            <span class="error"><?php echo $picErr; ?></span>
            <br/><br/>
            <div class="g-recaptcha" data-sitekey="Your-public-key"></div>
            <span class="error"><?php echo $captchaErr; ?></span>
            <br/>
            <input type="submit" name="signup" value="Sign Up"/>
          </form>
        </div>
        <br/>
        <h3 id="signchange">Already a registered user? Click here to sign in.</h3>
      </center>
      <script type="text/javascript">
        document.getElementById('signchange').addEventListener("click",showSignIn);
        function showSignIn() {
          location.href = "login.php";
        }

      </script>
  </body>
</html>
