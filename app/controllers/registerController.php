<?php
session_start();

include '../models/user.php';


    //gets the  input value.
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$_SESSION['errors'] = [];


if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $_SESSION['errors']['email'] = "Invalid email format";
}


if(!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/",$email)){
    $_SESSION['errors']['email'] = "Only Gmail allowed";
}


if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/",$password)){
    $_SESSION['errors']['password'] = "Strong password required";
}


if(!empty($_SESSION['errors'])){
    header("Location: ../views/register.php");
    exit();
}


       //call model
$result=registerUser($fullname,$email,$password);


if($result == "success"){

     $_SESSION['fullname'] = $fullname;
      $_SESSION['user_id'] = mysqli_insert_id($con);
    $_SESSION['message'] = "✅ Registration successful!";
     header("Location: ../views/dashboard.php");
    exit();
} else {
    $_SESSION['message'] = $result;
     header("Location: ../views/register.php");
    exit();
}


?>