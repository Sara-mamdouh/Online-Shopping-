<?php
  require_once '../config.php';

function hashpass($password){
  $pwd = $password;
  $hashedPass =password_hash($pwd ,PASSWORD_DEFAULT);
  $option =["const"=>12];
  $hashedPass =password_hash($pwd ,PASSWORD_BCRYPT ,$option);
  return $hashedPass;
}
if(isset($_POST['submit'])){
  $errors=[];
  [
    "username" => $username,
    "password" => $password,
    "email" => $email,
    "passwordC"=>$passwordc
  ]=$_POST;

  if(isset($email)&& !filter_var($email ,FILTER_VALIDATE_EMAIL)){
    $errors["email"]="email not valid";
  }
  if(isset($password)&& !(strlen($password)>7)){
    $errors["password"]="min length is 8 chars";
  } 
  if(!($password == $passwordc)){
    $errors["password"]="password not match";
  }
  // var_dump($errors);
  if(count($errors)==0){
    $originalPostCount = count($_POST);
 
    if (count(array_filter($_POST)) === $originalPostCount){
      $hashedPass = hashpass($password);
      $newUser = array(
        "username" => $username,
        "email" => $email,
        "password" =>$hashedPass
     );
     
     if(filesize("../users.json") == 0){
       // echo "is empty";
        $firstUser = array($newUser);
        $data_to_save = $firstUser;
     }else{
       // echo "is not empty";
        $old_records = json_decode(file_get_contents("../users.json"),true);
        $productsCart = array_filter($old_records, function($user)use($email){
          return $user["email"] == $email ;
        });
        // var_dump($productsCart);
  
  
        if($productsCart){
          $data_to_save = $old_records;
          $dis_message ="This email has already been used previously";
        }else{
          array_push($old_records ,$newUser);
          $data_to_save = $old_records;
        }
     }
     $encoded_data = json_encode($data_to_save, JSON_PRETTY_PRINT);
     file_put_contents("../users.json", $encoded_data, LOCK_EX);
   
    }
  }
 
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>sign-up</title>
</head>
<body>
  <div class="container">
    <div class="box form-box">
      <header>sign up</header>
      <form action="sign-up.php" method="post">
          <div class="input field">
            <label for="username">Username</label>
            <input type="text" name="username"  id="username"required>
          </div>
          <div class="input field">
            <label for="email">email</label>
            <input type="email" name="email"  id="email" required>
            <?php
              if(isset($dis_message)){
                echo "<div class='message'>
                  <p>{$dis_message}</p>
                  </div>";
              }
              ?>
          </div>
          <div class="errormassage">
            <p>
               <?php
                if(isset($errors)&& isset($errors["email"]))
                {
                    echo $errors["email"];
                }
              ?>
            </p>
          </div>
       
          <div class="input field">
            <label for="password">password</label>
            <input type="password" name="password"  id="password" required>
          </div>
          <div class="input field">
            <label for="passwordC">confirm password</label>
            <input type="password" name="passwordC"  id="passwordC" required>
          </div>
          <div class="errormassage">
            <p>
               <?php
                if(isset($errors)&& isset($errors["password"]))
                {
                    echo $errors["password"];
                }
              ?>
            </p>
          </div>
       
          <div class="field">
            <input type="submit" value="Register" class="btn" name="submit">
          </div>
          <div class="links">
            <span>already a member ?..</span><a href="../login.php">sign in</a>
          </div>

      </form>
    </div>
  </div>

</body>
</html>