<?php 
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include '../models/Booking.php';

$user_id = $_SESSION['user_id'];

$bookings = getBookings($user_id);


?>