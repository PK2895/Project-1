<?php

$DBServerLocation ="localhost";
$DBUser ="root";
$DBPwd = "";
$DBName ="mismatch";

$conn = new mysqli($DBServerLocation, $DBUser , $DBPwd, $DBName);
  if($conn->connect_error){
    
    trigger_error("Database Connection successfull".$conn->connect_error,E_USER_ERROR);
    echo '<br>';
    
  }
  else{
    echo '<br>Database connection successfull <br>';
  }
  
?>