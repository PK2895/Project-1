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
date_default_timezone_set('Asia/Kolkata');

if(isset($_SESSION['username']) ){
  
    echo '<h3 class="text-success">You are Logged in as '.$_SESSION['username'].'</h3>';
      echo '<div class="btn btn-group">';
      echo '<a class="btn btn-primary " href="index.php">Home</a>';
      echo '<a class="btn btn-primary " href="viewprofile.php">View Profile </a>';
      echo '<a class="btn btn-primary active" href="editprofile.php">Edit Profile </a>';
      echo '<a class="btn btn-primary" href="logout.php">Logout</a>';
      echo '</div>';
  
  
  //Form submit section
  
    if(isset($_POST['submit'])){
      

  $first_name= $conn->real_escape_string(trim($_POST['firstname']));
  $last_name= $conn->real_escape_string(trim($_POST['lastname'])) ;
  $gender= $conn->real_escape_string(trim($_POST['gender']));
  $birthdate= $conn->real_escape_string(trim($_POST['birthdate']));
  $state= $conn->real_escape_string(trim($_POST['state']));
  $city= $conn->real_escape_string(trim($_POST['city']));
  $new_pic= $_FILES['new_profilepic']['name'];
  $new_pic_size=$_FILES['new_profilepic']['size'];
  $new_pic_type=$_FILES['new_profilepic']['type'];
    
    if(!empty($new_pic)){
      
      if(($new_pic_type=='image/gif' || $new_pic_type=='image/png' || $new_pic_type=='image/jpeg' || $new_pic_type=='image/pjpeg') && (($new_pic_size>0) && ($new_pic_size<=MAX_FILE_SIZE)) ){
        
        
        $target= MM_UPLOAD_PATH.$new_pic;
        if(move_uploaded_file($_FILES['new_profilepic']['tmp_name'],$target)){
          
          @unlink(MM_UPLOAD_PATH.$old_pic);
          
          
        }else{
          $error="true";
          echo 'There was an error in uploading file';
          @unlink($_FILES['new_profilepic']['tmp_name']);
        }
        
      }
      else{
        $error="true";
        echo'Please upload correct image';
      }
    }
      if(empty($error)){
        
        if(!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($state) && !empty($city)){
          
          if(!empty($new_pic)){
            
            $sql_update = "UPDATE mismatch_user SET first_name='$first_name', last_name='$last_name', gender='$gender', birthdate='$birthdate', city='$city', state='$state', picture='$new_pic' WHERE user_id=".$_SESSION['user_id']." ";
          }
          
          else{
             $sql_update = "UPDATE mismatch_user SET first_name='$first_name', last_name='$last_name', gender='$gender', birthdate='$birthdate', city='$city', state='$state' WHERE user_id=".$_SESSION['user_id']." ";
          }
            $conn->query($sql_update);
            echo 'Data updated successfully';  
          
        }
        else{
          
          echo 'Please fill up all details';
          
        }
        
      }
      
    }
      
      

  
  // Form Area
  echo '<br>';
  echo '<br>';
  echo '<div class="row-fluid">';
  echo '<div class="well col-md-3">';
  
  $username= $_SESSION['username'];
  $sql_select = "SELECT * FROM mismatch_user WHERE username='$username'";
  $result = $conn->query($sql_select);
  while($row=$result->fetch_array()){
    
    $first_name= $row['first_name'];
    $last_name= $row['last_name'];
    $gender= $row['gender'];
    $birth_date= $row['birthdate'];
    $state= $row['state'];
    $city= $row['city'];
    $old_pic= $row['picture'];
 
  }
  ?>
  
  
<form class="col col-md-12" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"> 
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="firstname" > First Name </label>
<input type="text" class="form-control " name="firstname" id="firstname" placeholder="Enter First Name Here"  value="<?php if(isset($first_name)) { echo $first_name;} ?>" required>
<?php if(isset($firstname_err)) { echo '<small class="text-danger"> '.$firstname_err.'</small>';}?>
</div>
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="lastname"> Last Name</label>
<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Last Name Here" value="<?php if(isset($last_name)) { echo $last_name;} ?>" required>
<?php if(isset($lastname_err)) { echo '<small class="text-danger"> '.$lastname_err.'</small>';}?>
</div>
</div>
 
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="gender"> Select Your Gender</label>
<select class="form-control"  name="gender" id="gender" required>
<option value="M"> Male </option>
<option value="F"> Female</option>
</select>
<?php if(isset($gender_err)) { echo '<small class="text-danger"> '.$gender_err.'</small>';}?>
</div>   
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="birthdate"> Enter Your Birthdate</label>
<input class="form-control" type="date" name="birthdate" id="birthdate" value="<?php if(isset($birth_date)) { echo $birth_date;} ?>" required>
<?php if(isset($birthdate_err)) { echo '<small class="text-danger"> '.$birthdate_err.'</small>';}?>
</div>
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="state"> State </label>
<input class="form-control" type="text" name="state" id="state" placeholder="Enter Your State Here "  value="<?php if(isset($state)) { echo $state;} ?>" required>
<?php if(isset($state_err)) { echo '<small class="text-danger"> '.$state_err.'</small>';}?>
</div>  
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="city"> City </label>
<input class="form-control" type="text" name="city" id="city" placeholder="Enter Your City Here "  value="<?php if(isset($city)) { echo $city;} ?>" required>
<?php if(isset($city_err)) { echo '<small class="text-danger"> '.$city_err.'</small>';}?>
</div>  
</div>
  
<div class="form-group">
<label for="new_picture"> Upload your picture </label>  
<input class="form-control-file" type="file" name="new_profilepic" id="new_picture"  > 
<?php if(isset($file_err)) { echo '<small class="text-danger"> '.$file_err.'</small>';}
  
  if(!empty($old_pic)){
    echo '<div class="media">';
    echo '<div class="media-right">';
    echo '<label> Current picture </label>';
    echo '<img class="media-object" src="'.MM_UPLOAD_PATH.$old_pic.'" width="100px">';
    echo '</div>';
    echo '</div>';
  }
  
  ?>

</div>
<hr class="hr_style1"> 
<div class="form-group">
<input class="btn btn-primary btn-block" type="submit" name="submit" value="Save Profile">  
</div>
  
</form>  
  <?php
  echo '</div>';
  echo '</div>';
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