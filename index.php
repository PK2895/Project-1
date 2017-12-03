<?php

require_once('connectvars.php');
require_once('appvars.php');
require_once('start_session.php');


if(!isset($_SESSION['user_id'])){

    if(isset($_POST['submit'])){


  $username = $_POST['username'];
  $password = $_POST['password'];
 // $remember = $_POST['remember'];



  $username = $conn->real_escape_string(trim($username));
  $password = $conn->real_escape_string(trim($password));
  //$remember = $conn->real_escape_string(trim($remember));
  
  $sql_select = "SELECT user_id,username FROM mismatch_user WHERE username='$username'AND "."password= SHA2('$password',224)";
  $result = $conn->query($sql_select);
  if($result === false){

    trigger_error('Please check Select SQL query'.$conn->error,E_USER_ERROR);
    echo '<br>';

  }
  else{


    if($result->num_rows==1){
          session_start();
      $row= $result->fetch_array();
      $_SESSION['user_id']= $row['user_id'];
      $_SESSION['username']= $row['username'];
      if(!empty($remember)){
      setcookie('user_id',$row['user_id'], time()+(60*60*24*30));
      setcookie('username',$row['username'],time()+(60*60*24*30));
      }
     header('Location:index.php');
     exit();

    }
    else{

      $error="failed"; 
      
    }

  }

  }
  }


?>  


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html"  charset="utf-8">
<title> Login </title>  
<link  rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
<script type="text/javascript" src="bootstrap/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
<div class="container-fluid">

<?php 
  
    
    if(isset($_SESSION['username'])){
      
      echo '<h3 class="text-success">You are Logged in as '.$_SESSION['username'].'</h3>';
      echo '<div class="btn btn-group">';
      echo '<a class="btn btn-primary active" href="index.php">Home</a>';
      echo '<a class="btn btn-primary" id="view" href="viewprofile.php">View Profile </a>';
      echo '<a class="btn btn-primary" id="edit" href="editprofile.php">Edit Profile </a>';
      echo '<a class="btn btn-primary" href="logout.php">Logout</a>';
      echo '</div>';
      
  echo '<div id="home_profiles">';
  echo '<h3 class="text-default"> User Profiles </h3>';
 

  $sql_select = 'SELECT username,picture FROM mismatch_user ORDER BY join_date DESC LIMIT 5' ;
  $result =  $conn->query($sql_select);
  if($result===false){
    
    trigger_error("Select SQL query is wrong".$conn->error,E_USER_ERROR);
    echo '<br>';
    
  }
  else{
    
    while($row=$result->fetch_array()){
      
      $user_view=$row['username'];
      echo '<table class="table">';
      echo '<tr class="col-md-2"><td class="col-md-1"><b><a href="viewprofile.php?username='.$user_view.'">'.$row['username'].'</a></b></td>';
      echo '<td class="col-md-2"><img src="'.MM_UPLOAD_PATH.$row['picture'].'" width="100px"> </td> </tr>';
      echo '</table>';
    }
    
  }
  
  

echo '</div>';

    }
    else{  
?>


<div id="login">

  <?php
  if(isset($_GET['error_login']) && $_GET['error_login']=="true"){
      
      
      echo '<span class="text-danger"> Please login to continue </span>';
      
    }
         
  ?>
  
<h3 class="text-primary"> Login </h3>
<button class="btn btn-primary" data-toggle="modal" data-target="#login_modal" > Click to Login </button> 
<div class="modal fade" id="login_modal">
    <div class="modal-dialog">
      
      <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="close"> &times; </button>
            <div class="modal-title">
            <h4>Enter username and password to Login  </h4>
            </div>
            </div>
            <div class="modal-body">
              
             
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>"> 
                  
                  
                  <?php
                        if ( isset($error) && $error="failed" ){
                        echo '<p class="text-danger"> Invalid username and password</p>';
                    }
                    ?>
                
                  <div class="form-group row">
                        <div class="col-md-12">
                            <label for="username"> Username </label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
                        </div>
                    </div>
                     <div class="form-group row">
                        <div class="col-md-12">
                            <label for="password"> Password </label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                        </div>
                    </div>
                    <div class="form-check row">
                        <div class="col-md-12">
                          <label class="form-check-label">
                           <input class="form-check-input" type="checkbox" name="remember" value="remember" > Remember me 
                          </label>
                        </div>
                    </div> 
                    <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="Login">
                    <button type="button" class="btn btn-danger " data-dismiss="modal"> Close </button>
                  </div>
                </form>
            </div>
           
      </div>
  
    </div>
</div>
</div>
<hr>
<div id="create_profile">
    <div class="text-success">
        <h3> Create Profile Here</h3>
        <a class="btn btn-success" href="create_profile.php">Click to Create Profile</a>
    </div>
</div>
<hr>
<div id="home_profiles">
  <h3 class="text-default"> User Profiles </h3>
  <?php

  $sql_select = 'SELECT username,picture FROM mismatch_user ORDER BY join_date DESC LIMIT 5' ;
  $result =  $conn->query($sql_select);
  if($result===false){
    
    trigger_error("Select SQL query is wrong".$conn->error,E_USER_ERROR);
    echo '<br>';
    
  }
  else{
    
    while($row=$result->fetch_array()){
      
      
      echo '<table class="table">';
      echo '<tr class="col-md-2"><td class="col-md-1"><b>'.$row['username'].'</b></td>';
      echo '<td class="col-md-2"><img src="'.MM_UPLOAD_PATH.$row['picture'].'" width="100px"> </td> </tr>';
      echo '</table>';
    }
    
  }
  
  
  ?>
</div>
  
<?php
      }       
?>

<script type="text/javascript">

$('#login_modal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');

})  
  
<?php if(isset($_POST['submit']) ) { ?>

$(function(){
  $("#login_modal").modal('show');
});
  
<?php } ?> 

</script>
</div>
</body>
</html>

