<?php
session_start();

include '../models/Booking.php';
include '../core/database.php';



if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){
    header("Location: ../views/login.php");
    exit();
}

if(isset($_POST['book'])){ 
  

     $user_id = $_SESSION['user_id'];
     $pickup = "Mexico";
     $dropoff = $_POST['dropoff'];
     $price = $_POST['price'];
         $date = date("Y-m-d");
         $time=date("H:i:s");

$result = createBooking($user_id, $pickup, $dropoff,$date,$time);

  if($result =="assigned"){
  $_SESSION['message'] = "🚖 Taxi assigned successfully!";

  } else{

      $queue = getQueueCount($pickup,$dropoff);
      $_SESSION['message'] = "⏳ No taxi available. $queue people are waiting before you.";
  }
       
   header("Location: ../views/history.php");
 }



if(isset($_GET['complete_id'])){

    if(!isset($_SESSION['admin_id'])){
        header("Location: ../views/admin/login.php");
        exit();
    }

    $id = $_GET['complete_id'];

    completeBooking($id);

    $_SESSION['message'] = "✅ Booking marked as completed";

    header("Location: ../views/admindashboard.php");
    exit();
}




?>