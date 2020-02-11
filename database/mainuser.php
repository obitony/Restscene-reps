<?php
Session_start();
//initializing variables 
$username   = "";
$email      = "";
$errors     = array();  

//connect to the database
$db = mysqli_connect('localhost', 'root', "", 'reps');

//REGISTER USER
  if(isset($_POST['create'])){
      //receive all input values from the form
      $username =mysqli_real_escape_string($db, $_POST['username']);
      $fname = mysqli_real_escape_string($db, $_POST['fname']);
      $sname = mysqui_real_escape_string($db, $_POST['sname']);
      $email = mysqui_real_escape_string($db, $_POST['email']);
      $phone = mysqui_real_escape_string($db, $_POST['email']);
      $country = mysqui_real_escape_string($db, $_POST['country']);
      $pwd = mysqui_real_escape_string($db, $_POST['pwd']);
      $pwd2 = mysqui_real_escape_string($db, $_POST['pwd2']);
      
// form validation: ensure that the form is correctly filled...
// by adding (array_push()) corresponding error unto $errors array

if (empty($username)){array_push($erros, "username is required");}
if (empty($fname)){array_push($erros, "Your First Name is required");}
if (empty($sname)){array_push($erros, "Your Surname is required");}
if (empty($email)) {array_push($errors, "Email is required");}
if (empty($phone)) {array_push($errors, "Phone is required");}
if (empty($country)) {array_push($errors, "Country is required");}
if (empty($pwd)) {array_push($errors, "Password is required");}
if ($pwd !=$pwd2) {array_push($errors, "The two Password do not match");}

// first check the database to make sure
// a user does not already exist with thesame username and/or email
$user_check_query = "SELECT * FROM reps WHERE username='$username' or email='$email";
$result = mysqli_query($db, $user_check_query);
$user = msqli_fetch_assoc($result);

if ($user) {// if user exists
    if ($user['username'] === $username){
        array_push($errors, "username already exists");      
    }
    if ($user['email'] === $email) {
        array_push($errors, "email already exists");
    }
}
    //Finally, regiter user if there are no errors in the form
  if (count($errors) == 0){
      $password =md5($pwd);//encrypt the password before saving in the database
      
      $query ="INSERT INTO reps (username, email, password)
      VALUES('$username', '$email', '$password)";
      mysqli_query($db, $query);
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "Your are now logged in";
      header('location: login.html');
      
  }}
?>
