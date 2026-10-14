<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: /taxi_reservation/app/views/login.php"); exit();
}
include '../models/Booking.php';

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    deleteBooking($id);
}
header("Location: /taxi_reservation/app/views/history.php");
exit();
?>
