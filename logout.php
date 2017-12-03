<?php

session_start();
if(isset($_SESSION['username'])){
 
    session_unset();
   if(isset($_COOKIE[session_name()])){
     setcookie(session_name(),'',time()-3600);
   }
    session_destroy();
    setcookie('username','',time()-3600);
    setcookie('user_id','',time()-3600);
    header('Location:index.php');
}   


?>