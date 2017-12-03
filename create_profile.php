<!DOCTYPE html>
<html>
<head>
<title> Create Your Profile</title>  
<meta http-equiv="content-type" content="text/html" charset="utf-8">
<style>

    #user_form{
    
    background-color: #dfeaf4;
    padding-left:30px;
    padding-right: 30px;
    box-sizing: border-box;
    
  }
  
  #legend{
    border-color: #6a6a6a;
  }
  
  .hr_style1{ 
    border: 0.3px solid;
    border-color: #afafaf;
  }
  
  
  @media only screen and (max-width:320px){
    
     h1.head{
      font-size: 24px;
    }

  }
  
  @media only screen and (min-width:321px){
    
    h1.head{
      font-size: 30px;
    }
  
  }
  
    @media only screen and (min-width:480px){
    
    h1.head{
      font-size: 36px;
     
    } 
  }
  
   @media only screen and (min-width:768px){
    
    h1.head{
      font-size: 36px;  
    }
      
    
  }
  


  
</style>
  

<link rel=stylesheet type="text/css" href="bootstrap/css/bootstrap.css">
<script type="text/javascript" src="bootstrap/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head> 
<body>
<div class="container-fluid">

<h1 class="head"> Create Your Profile Here</h1>
  
<?php
if(isset($_POST['submit'])){

  date_default_timezone_set('Asia/Kolkata');
  $join_date= date("Y/m/d H:i:s");
  $first_name= $_POST['firstname'];
  $last_name= $_POST['lastname'];
  $gender= $_POST['gender'];
  $birth_date= $_POST['birthdate'];
  $state= $_POST['state'];
  $city= $_POST['city'];
  $profile_pic_name= $_FILES['profilepic']['name'];
  $profile_pic_size=$_FILES['profilepic']['size'];
  $profile_pic_type=$_FILES['profilepic']['type'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $re_password = $_POST['repassword'];
  
  // ----------checking if any field is empty starts here ------------
  
  if(empty($first_name)){

    $firstname_err=" Please enter your First Name";
    $form_output=true;

  }

  if(empty($last_name)){

    $lastname_err=" Please enter your Last Name";
    $form_output=true;

  }

  if(empty($gender)){

    $gender_err=" Please select your gender";
    $form_output=true;

  }

  if(empty($birth_date)){

    $birthdate_err=" Please enter your Birth Date";
    $form_output=true;

  }

  if(empty($state)){

    $state_err=" Please enter your State Name";
    $form_output=true;

  }

  if(empty($city)){

    $city_err=" Please enter your City Name";
    $form_output=true;

  }

  if(empty($profile_pic_name)){

    $file_err=" Please upload your profile pic";
    $form_output=true;

  }
  
   if(empty($username)){

    $username_err=" Please enter your username";
    $form_output=true;

  }
  
   if(empty($password)){

    $password_err=" Please enter your password";
    $form_output=true;

  }
  
   if(empty($re_password)){

    $repassword_err=" Please enter your password again";
    $form_output=true;

  }// ----------checking if any field is empty ends here ------------
  

  // ----  Check if all fields are filled or not  Start Here ------
  if( (!empty($first_name)) && (!empty($last_name)) && (!empty($gender)) && (!empty($birth_date)) && (!empty($city)) && (!empty($state)) && (!empty($profile_pic_name)) && (!empty($username)) && (!empty($password)) && (!empty($re_password)) )

    {
    
    if($password==$re_password){
      
           // this will include code of two files connectvars.php and appvars.php here.
      require_once("connectvars.php");
      require_once("appvars.php");
    
    // ------check if profile pic is proper MIME type and its size is less than defined Max_FILE_SIZE  starts here-------
    
      if( (($profile_pic_type=='image/jpeg') || ($profile_pic_type=='image/pjpeg') || ($profile_pic_type=='image/png') || ($profile_pic_type=='image/gif')) && ( ($profile_pic_size >0) && ($profile_pic_size < MAX_FILE_SIZE)) )
      {

        // --------- check if file upload is successfull starts here ----------
          if($_FILES['profilepic']['error'] == 0)
          {
            
            // Set upload path for saving profile pics
            $target= MM_UPLOAD_PATH.$profile_pic_name;
            
        // move uploaded profile pic from temporary location to defined target: Images/ folder 
            move_uploaded_file($_FILES['profilepic']['tmp_name'], $target );
            

              //this will return database connection obj into $conn variable  
              $conn = new mysqli($DBServerLocation, $DBUser, $DBPwd, $DBName);

              // Database connection error checking code -- start here 
                if($conn->connect_error)
                  {

                    trigger_error('Database connection failed '.$conn->connect_error,E_USER_ERROR);
                    echo '<br>';

                  }

                else
                  {
                    echo 'Database connection successfull<br>';

                  }
                // Database connection error checking code -- ends here 

             //Trim whitespace from before and after of string using trim() function and use real_escape_string of database function which will escape special  character in string to use in SQL query
               
                $join_date= $conn->real_escape_string(trim($join_date));
                $first_name= $conn->real_escape_string(trim($first_name));
                $last_name= $conn->real_escape_string(trim($last_name));
                $state= $conn->real_escape_string(trim($state));
                $city= $conn->real_escape_string(trim($city));
                $username = $conn->real_escape_string(trim($username));
                $password = $conn->real_escape_string(trim($password));
                $re_password = $conn->real_escape_string(trim($re_password));
             
             
              $sql_select = "SELECT * FROM mismatch_user WHERE username='$username'";
              $result= $conn->query($sql_select);
              if($result->num_rows==1){
                $username_err= "This username already exist . Please try another username";
                $form_output=true;
              }
            else{
               // Insert data into database using this sql statement
              $sql_insert = "INSERT INTO mismatch_user (username,password, join_date,first_name, last_name, gender, birthdate, city, state, picture) VALUES ('$username',SHA2('$password',224),'$join_date', '$first_name', '$last_name', '$gender', '$birth_date', '$city', '$state', '$profile_pic_name')";
                
              $result = $conn->query($sql_insert);
              if( $result === false)
              {
                  trigger_error('Insert query successfull'.$conn->error,E_USER_ERROR);
                  echo '<br>';
              }
              else{
                
                $form_output=false;
                echo 'Your data saved successfully<br>';
                echo '<b> First Name: </b>'.$first_name.'<br>';
                echo '<b> Last Name: </b>'.$last_name.'<br>';
               
                //this DateTime will convert date and time into DateTime object then we are calling format method to change its format
                $tmp_Date = new DateTime($join_date);
                $formatted_join_date = $tmp_Date -> format("d/m/y g:i A");
                echo '<b> Join Date: </b>'.$formatted_join_date.'</br>';
                
                echo '<b> Gender: </b>'.$gender.'<br>';
                echo '<b> Birth Date: </b>'.$birth_date.'<br>';
                echo '<b> State: </b>'.$state.'<br>';
                echo '<b> City: </b>'.$city.'<br>';
                echo '<b> Your Profile Pic: </b><br>';
                echo '<img src="'.$target.'" class="media-object" alt="'.$profile_pic_name.'" width="102px"> ';
                echo '<br>';
                echo '<div class="dropdown">';
                echo '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Profile <span class="caret"> </span></button>';
                echo '<ul class="dropdown-menu">';
                echo '<li><a href="create_profile.php"> Create Profile </a> </li>';
                echo '<li><a href="view_profile.php"> View Profile </a> </li>';
                echo '<li><a href="edit_profile.php"> Edit Profile </a> </li>';
                echo '</ul>';
                echo '</div>';
                
                
              }
            }
             
          
            $conn->close();
          
          }// --------- check if file upload is successfull ends here ----------
        
          
        // if any error happens in uploading profile pic then this message will be shown
          else
          {
              echo 'There was some problem in uploading your profile pic <br>';
              $form_output=true;
          }
        
      //this unlink function will remove file
        @unlink($_FILES['profilepic']['tmp_name']);
            
        
      } // ------check if profile pic is proper MIME type and its size is less than defined Max_FILE_SIZE  ends here-------
  
    //if profile pic is not image or >1MB then it will show this message
    else
      {
          echo 'Please upload profile pic in jpeg, pjpeg, png or gif format and please make sure that file size should be <1MB  <br>';
          $form_output=true;
      }
      
    }
    
        else{
          
          $repassword_err = 'Please type password again. It did not match with previous one';
          $form_output=true;
          
        }
    
    
    
   } 
  // ----  Check if all fields are filled or not Ends Here ------
  
  else{
    echo 'Please fill all the fields to continue<br>';
    $form_output=true;
  }

}
  
else{
  
  $form_output=true;
  
}


if($form_output==true){            
?>

<form class="col-sm-6 col-md-3" id="user_form" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<br>
<legend id="legend"> Enter your details</legend>  
  
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
<label for="picture"> Upload your picture </label>  
<input class="form-control-file" type="file" name="profilepic" id="picture"  required> 
<?php if(isset($file_err)) { echo '<small class="text-danger"> '.$file_err.'</small>';}?>
</div>
<hr class="hr_style1"> 
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="username"> Username </label>
<input class="form-control" type="text" name="username" id="username" placeholder="Enter Username Here" required>
<?php if(isset($username_err)) { echo '<small class="text-danger"> '.$username_err.'</small>';}?>
</div>    
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="password"> Password </label>
<input class="form-control" type="password" name="password" id="password" placeholder="Enter Password Here" required>
<?php if(isset($password_err)) { echo '<small class="text-danger"> '.$password_err.'</small>';}?>
<small class="text-muted"> Password must be 6 charachter long </small>
</div>    
</div>
  
<div class="form-group row">
<div class="col-sm-12 col-md-12">
<label for="repassword"> Re-enter Password </label>
<input class="form-control" type="password" name="repassword" id="repassword" placeholder="Enter Password Again" required>
<?php if(isset($repassword_err)) { echo '<small class="text-danger"> '.$repassword_err.'</small>';}?>
</div>    
</div>
  
<div class="form-group">
<input class="btn btn-primary btn-block" type="submit" name="submit" value="Submit">  
</div>
  
</form>  
</div>
</body>
</html>
<?php
}
?>