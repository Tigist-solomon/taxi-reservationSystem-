<?php 

include '../core/database.php';
  include '../models/Booking.php';

   $pickup = $_POST['pickup'] ?? '';
   $dropoff = $_POST['dropoff'] ?? '';

echo getPrice($pickup, $dropoff);
?>