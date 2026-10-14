<?php 
  
mysqli_report(MYSQLI_REPORT_OFF);

  include '../core/database.php';
  function registerUser($fullname,$email,$password){

  global $con;

    
     $hashed_password = password_hash($password,PASSWORD_DEFAULT);

    
    $sql = "INSERT INTO users(fullname,email,password) 
              VALUES ('$fullname','$email','$hashed_password')";

    if(mysqli_query($con,$sql)){
        return "success";

    }else{
      if(mysqli_errno($con) == 1062){
        return "Email already exists";
      }
    else{
        return "Error: " .mysqli_error($con);
    }
  }
  }

  function loginUser($email,$password){

  global $con;

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($con,$sql);

  $data = mysqli_fetch_array($result);

  if($data){

  if(password_verify($password,$data['password'])){
     return $data;
         
       
  

  }else{
            return "wrong_password";  
        }}
  
  else{

  return "not_found";
  }
  }
  
  
?>