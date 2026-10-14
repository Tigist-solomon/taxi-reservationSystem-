<?php
session_start();
include '../models/Admin.php';

if(isset($_POST['admin_login'])){
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $result   = adminLogin($email, $password);

    if(is_array($result)){
        $_SESSION['admin_id']   = $result['id'];
        $_SESSION['admin_name'] = $result['fullname'];
        header("Location: /taxi_reservation/app/views/admindashboard.php");
        exit();
    } elseif($result === "wrong_password"){
        $_SESSION['admin_error'] = "Incorrect password.";
    } else {
        $_SESSION['admin_error'] = "Admin account not found.";
    }
    header("Location: /taxi_reservation/app/views/admin/login.php");
    exit();
}

if(isset($_GET['complete_id'])){
    if(!isset($_SESSION['admin_id'])){
        header("Location: /taxi_reservation/app/views/admin/login.php"); exit();
    }
    $id = (int)$_GET['complete_id'];
    updateBookingStatus($id, 'completed');
    $_SESSION['message'] = "✅ Booking #$id marked as completed.";
    header("Location: /taxi_reservation/app/views/admindashboard.php");
    exit();
}
?>
