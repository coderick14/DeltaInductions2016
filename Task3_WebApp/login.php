<?php
  session_start();
  include('connect.php');
  $nameErr=$passErr=$captchaErr="";
  if(isset($_POST['signin'])) {


        $uName = htmlspecialchars($_POST['uname_ex']);
        $uPass = htmlspecialchars($_POST['upass_ex']);
        $name=$pass="";

        //prevent sql injection here
        $sqlquery = $dbcon->prepare("SELECT user_name,user_pass FROM users WHERE user_name=?");
        $sqlquery->bind_param("s",$uName);
        $sqlquery->execute();
        $sqlquery->bind_result($name,$pass);
        $result = $sqlquery->fetch();

        if($result == NULL) {
            $nameErr = "Username does not exist";
        }
        elseif($result == TRUE) {
            if(password_verify($uPass,$pass)) {
              $_SESSION['user'] = $uName;
              $_SESSION['pass'] = $pass;
              echo "<script type='text/javascript'>location.href='profile.php'</script>";
              //echo "Access granted";
            }
            else
              $passErr = "Password does not match";
        }
        else {
          die("Error extracting from database");
        }
    mysqli_close($dbcon);
  }
?>


<html>
  <head>
    <title>PostIt - Sign in</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="startpage.css"/>
  </head>
  <body>
    <header>
      <h1>PostIt</h1>
      <h3>A place to put up your thoughts</h3>
    </header>
    <br/><br/>
      <center>
        <div id="signIn" style="height:19em">
          <form action="login.php" method="post">
            <h2>Username</h2>
            <input type="text" name="uname_ex" maxlength="20" placeholder="Enter your username" required/><br/>
            <span class="error"><?php echo $nameErr; ?></span>
            <h2>Password</h2>
            <input type="password" name="upass_ex" maxlength="20" placeholder="Enter your password" required/><br/>
            <span class="error"><?php echo $passErr; ?></span>
            <br/><br/>
           <input type="submit" name="signin" value="Sign In"/>
          </form>
        </div>
        <br/>
        <h3 id="signchange">Not a registered user? Click here to sign up.</h3>
      </center>
      <script type="text/javascript">
        document.getElementById('signchange').addEventListener("click",showSignUp);
        function showSignUp() {
          location.href = "signup.php";
        }

      </script>
  </body>
</html>
