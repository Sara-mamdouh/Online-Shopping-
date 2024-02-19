<?php

$output = file_get_contents("users.json");
$decode = json_decode($output, true);

if(isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  require_once './config.php';
  $errorMass=[];

  $_SESSION['login_time'] =time();
  if($username === "admin" && $password === "admin"){
    $_SESSION["username"] = "admin";
    $_SESSION["logedin"] = true;
    header("location:pages/create.php");
  }else{
    for($i = 0; $i < count($decode); $i++) {
      if($decode[$i]['username'] == $username) {
        // var_dump($decode[$i]['password']);
        if(password_verify($password,$decode[$i]['password'])){
            // echo "Login successful!";
            $_SESSION['username'] = $username;
            $_SESSION["logedin"] = true;
            header("location:pages/home.php");
            break;
        }else{
          $errorMass["errMass"]="Please verify your password and userName and re-enter it";

        }

      }else {  
        $errorMass["errMass"]="Please verify your password and userName and re-enter it";
      }
    }
    // var_dump($errorMass);
  }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/style.css">
  <title>login</title>
</head>
<body>
  <div class="container">
    <div class="box form-box">
      <header>login</header>
      <form action="login.php" method="post">
          <div class="input field">
            <label for="username">Username</label>
            <input type="text" name="username"  id="username"required>
          </div>
          <div class="input field">
          <label for="password">password</label>
            <input type="password" name="password"  id="password" required>
          </div>
          <div class="input field">
          <?php
              if(isset($errorMass))
              {
                echo $errorMass["errMass"];
              }
          ?>
          </div> 
          <div class="field">
            <input type="submit" value="Login" class="btn" name="submit">
          </div>
          <div class="links">
            <span>don't have account ?..</span><a href="./pages/sign-up.php">sign up now</a>
          </div>

      </form>
    </div>
  </div>
  

</body>
</html>