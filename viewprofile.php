<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html"  charset="utf-8">
<title> View Profile </title>  
<link  rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
<script type="text/javascript" src="bootstrap/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
<div class="container-fluid">
<?php

require_once('connectvars.php');
require_once('appvars.php');
require_once('start_session.php');

if(isset($_SESSION['username']) ){
  
    echo '<h3 class="text-success">You are Logged in as '.$_SESSION['username'].'</h3>';
      echo '<div class="btn btn-group">';
      echo '<a class="btn btn-primary " href="index.php">Home</a>';
      echo '<a class="btn btn-primary active" href="viewprofile.php">View Profile </a>';
      echo '<a class="btn btn-primary" href="editprofile.php">Edit Profile </a>';
      echo '<a class="btn btn-primary" href="logout.php">Logout</a>';
      echo '</div>';
      
      $userid = $_SESSION['user_id'];
      $sql_select ="SELECT * FROM mismatch_user WHERE user_id='$userid'";
      $result = $conn->query($sql_select);
      if($result===false){
              
              trigger_error('Query error'.$conn->error,E_USER_ERROR);
              echo '<br>';
              
            }
      else{
        while($row=$result->fetch_array()){
          
              echo '<h4> Your Profile </h4>';
              echo '<div class="row">';
              echo '<div class="well col-md-6">';
              echo '<div class="media">';                     
              echo '<div class="media-body">';
              echo '<h4 class="media-heading"> <b>Username: </b>'.$row['username'].'<br>';
              echo '<br>';
              echo '<b>First Name: </b>'.$row['first_name'].'<br>';
              echo '<br>';
              echo '<b>Last Name: </b>'.$row['last_name'].'<br>';
              echo '<br>';
              $tmp_join_date = new DateTime($row['join_date']);
              $join_date= $tmp_join_date->format('d/m/Y H:i A');
              echo '<b>Join Date: </b>'.$join_date.'<br>';
              echo '<br>';
              echo '<b>Gender: </b>';
               
              if($row['gender']=="M"){
                echo 'Male<br>';
              }
                else{
                  echo 'Female<br>';
                }
              $temp_bday= new DateTime($row['birthdate']);
          
              $birthdate= $temp_bday->format('d/m/Y');
              
              echo '<br>';
              echo '<b>Birth Date: </b>'.$birthdate.'<br>';
              echo '<br>';
              echo '<b>City : </b>'.$row['city'].'<br>';
              echo '<br>';
              echo '<b>State : </b>'.$row['state'].'<br>';
              echo '</h4>';
              echo '</div>';
              echo '<div class="media-right">';
              echo '<img class="media-object" src="'.MM_UPLOAD_PATH.$row['picture'].'" width="150px">';
              echo '</div>';
              echo '</div>';
              echo '</div>';
              echo '</div>';

        }
      }
     
     
      if(isset($_GET['username'])){
            
            $view_profile_of_user = $_GET['username'];
            $sql_select="SELECT * FROM mismatch_user WHERE username='$view_profile_of_user'";
            $result = $conn->query($sql_select);
            if($result===false){
              
              trigger_error('Query error'.$conn->error,E_USER_ERROR);
              echo '<br>';
              
            }
           
            else{
              
              
              while($row=$result->fetch_array()){
              
              echo '<h3><b>Profile Data</b></h3>';
              echo '<div class="row">';
              echo '<div class="well col-md-6">';
              echo '<div class="media">';                     
              echo '<div class="media-body">';
              echo '<h4 class="media-heading"> <b>Username: </b>'.$row['username'].'<br>';
              echo '<br>';
              echo '<b>First Name: </b>'.$row['first_name'].'<br>';
              echo '<br>';
              echo '<b>Last Name: </b>'.$row['last_name'].'<br>';
              echo '<br>';
              $tmp_join_date = new DateTime($row['join_date']);
              $join_date= $tmp_join_date->format('d/m/Y H:i A');
              echo '<b>Join Date: </b>'.$join_date.'<br>';
              echo '<br>';
              echo '<b>Gender: </b>';
               
              if($row['gender']=="M"){
                echo 'Male<br>';
              }
                else{
                  echo 'Female<br>';
                }
              $temp_bday= new DateTime($row['birthdate']);
          
              $birthdate= $temp_bday->format('d/m/Y');
              
              echo '<br>';
              echo '<b>Birth Date: </b>'.$birthdate.'<br>';
              echo '<br>';
              echo '<b>City : </b>'.$row['city'].'<br>';
              echo '<br>';
              echo '<b>State : </b>'.$row['state'].'<br>';
              echo '</h4>';
              echo '</div>';
              echo '<div class="media-right">';
              echo '<img class="media-object" src="'.MM_UPLOAD_PATH.$row['picture'].'" width="150px">';
              echo '</div>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
              
              
              
              
            }
              echo '<div class="col-md-12">';
              echo '<a href="viewprofile.php">Go Back </a>';
              echo '</div>';
            }
         
        
      }
  
    else{
         $sql_select = 'SELECT username,picture FROM mismatch_user ORDER BY join_date DESC LIMIT 5' ;
          $result =  $conn->query($sql_select);  
        
           echo '<h3 class="text-default"> View Profiles </h3>';
              while($row=$result->fetch_array())
              {
              $user_view=$row['username'];
              echo '<table class="table">';
              echo '<tr class="col-md-2"><td class="col-md-1"><b><a href="viewprofile.php?username='.$user_view.'">'.$row['username'].'</a></b></td>';
              echo '<td class="col-md-2"><img src="'.MM_UPLOAD_PATH.$row['picture'].'" width="100px"> </td> </tr>';
              echo '</table>';
              }
         
    
      
    }
  
  
}


else{
  echo'Please login to access this page<br>';
  header("Location:index.php?error_login=true");
  exit();
}

?>
</div>
</body>
</html>