<?php
ini_set("session.cookie_httponly",1);
ini_set("session.cookie_samesite","Lax");
session_set_cookie_params(lifetime_or_options:(60*60*24),httponly:true);
session_start();

function regenerationSessionId(){
  session_regenerate_id(true);
  $_SESSION['last_regeneration_time'] = time();
  $_SESSION['login_time'] =time();
}

function authenticated(){
  if(!isset($_SESSION["logedin"])){
    header("location:../login.php");
    exit();
  }
}
if(time()-$_SESSION['login_time'] > (60*60)){
  header("location:./pages/logout.php");
}

if(!isset($_SESSION['last_regeneration_time'])){
  regenerationSessionId();
}else{
  if(time()-$_SESSION['last_regeneration_time'] > (60*30)){
    regenerationSessionId();
  }
}
////////rebase شوفيها ضرورى