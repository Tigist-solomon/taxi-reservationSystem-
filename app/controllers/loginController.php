<?php
session_start();
include '../models/user.php';
include '../models/Admin.php';

if(isset($_POST['login'])){
    $role     = $_POST['role']     ?? '';
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    if($role === 'admin'){
        $result = adminLogin($email, $password);
        if(is_array($result)){
            $_SESSION['admin_id']   = $result['id'];
            $_SESSION['admin_name'] = $result['fullname'];
            header("Location: /taxi_reservation/app/views/admindashboard.php");
            exit();
        } elseif($result === "wrong_password"){
            $_SESSION['message'] = "Incorrect admin password.";
        } else {
            $_SESSION['message'] = "Admin account not found.";
        }
    } else {
        $result = loginUser($email, $password);
        if(is_array($result)){
            $_SESSION['user_id']  = $result['id'];
            $_SESSION['fullname'] = $result['fullname'];
            header("Location: /taxi_reservation/app/views/dashboard.php");
            exit();
        } elseif($result === "wrong_password"){
            $_SESSION['message'] = "Incorrect password.";
        } else {
            $_SESSION['message'] = "No account found with that email.";
        }
    }
    header("Location: /taxi_reservation/app/views/login.php");
    exit();
}
?>
