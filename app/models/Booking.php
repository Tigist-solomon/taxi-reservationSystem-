<?php 
include '../core/database.php';


function getLocation(){
  global $con;
  $sql = "SELECT name FROM locations";
  $result = mysqli_query($con, $sql);
  return $result;
}


function getPrice($pickup, $dropoff){
    global $con;
    $pickup  = mysqli_real_escape_string($con, $pickup);
    $dropoff = mysqli_real_escape_string($con, $dropoff);

    $sql = "SELECT price FROM routes
              WHERE pickup_location = '$pickup'
              AND dropoff_location = '$dropoff'";

    $result = mysqli_query($con, $sql);
    $data   = mysqli_fetch_assoc($result);

    if($data){
        return $data['price'];
    } else {
        return 0;
    }
}



define('TAXI_CAPACITY', 3);


function createBooking($user_id, $pickup, $dropoff, $date, $time){
    global $con;

    $price = getPrice($pickup, $dropoff);

    $pickup_esc  = mysqli_real_escape_string($con, $pickup);
    $dropoff_esc = mysqli_real_escape_string($con, $dropoff);

    
    $driver_sql = "SELECT * FROM drivers
                   WHERE status = 'available'
                   AND location = '$dropoff_esc'
                   AND current_passengers < " . TAXI_CAPACITY . "
                   LIMIT 1";

    $driver_result = mysqli_query($con, $driver_sql);
    $driver        = $driver_result ? mysqli_fetch_assoc($driver_result) : false;

    if($driver){
        $driver_id = $driver['id'];

        $sql = "INSERT INTO bookings
                (user_id, pickup_location, dropoff_location, price, booking_date, booking_time, status, driver_id)
                VALUES
                ('$user_id','$pickup_esc','$dropoff_esc','$price','$date','$time','active','$driver_id')";

        mysqli_query($con, $sql);

     
        $new_count = $driver['current_passengers'] + 1;
        mysqli_query($con, "UPDATE drivers SET current_passengers = '$new_count' WHERE id='$driver_id'");

       
        // If the taxi is now full, mark it busy
        if($new_count >= TAXI_CAPACITY){
            mysqli_query($con, "UPDATE drivers SET status='busy' WHERE id='$driver_id'");
        }

        return "assigned";
    } else {
        $sql = "INSERT INTO bookings
                (user_id, pickup_location, dropoff_location, price, booking_date, booking_time, status)
                VALUES
                ('$user_id','$pickup_esc','$dropoff_esc','$price','$date','$time','waiting')";

        mysqli_query($con, $sql);
        return "waiting";
    }
}


function getBookings($user_id){
    global $con;
    $sql = "SELECT * FROM bookings
             WHERE user_id = '$user_id'
             ORDER BY id DESC";
    return mysqli_query($con, $sql);
}


function checkTaxiAvailability($destination){
    global $con;
    $destination = mysqli_real_escape_string($con, $destination);
    $sql = "SELECT * FROM drivers
            WHERE location = '$destination'
            AND status = 'available'
            LIMIT 1";
    return mysqli_query($con, $sql);
}


function getAvailableTaxis(){
    global $con;

    // For each destination, count how many taxis still have free seats (bookable)
    $sql = "SELECT location AS destination, COUNT(*) AS total
            FROM drivers
            WHERE status = 'available'
            AND current_passengers < " . TAXI_CAPACITY . "
            GROUP BY location
            ORDER BY location ASC";

    $result = mysqli_query($con, $sql);

    if(!$result){
        return [];
    }

    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}


function deleteBooking($id){
    global $con;
    $sql = "DELETE FROM bookings WHERE id='$id'";
    return mysqli_query($con, $sql);
}


function getQueueCount($pickup, $dropoff){
    global $con;
    $pickup  = mysqli_real_escape_string($con, $pickup);
    $dropoff = mysqli_real_escape_string($con, $dropoff);
    $sql = "SELECT COUNT(*) as total FROM bookings
             WHERE status='waiting'
             AND pickup_location ='$pickup'
             AND dropoff_location='$dropoff'";
    $result = mysqli_query($con, $sql);
    $data   = mysqli_fetch_assoc($result);
    return $data['total'];
}


function completeBooking($id){
    global $con;

    $result = mysqli_query($con, "SELECT driver_id, dropoff_location FROM bookings WHERE id='$id'");
    $row    = mysqli_fetch_assoc($result);

    $driver_id = $row['driver_id'];
    $dropoff   = mysqli_real_escape_string($con, $row['dropoff_location']);

    if($driver_id){
        // Mark every passenger riding in this taxi to this same destination as completed
        mysqli_query($con, "UPDATE bookings
                             SET status='completed'
                             WHERE driver_id='$driver_id'
                             AND dropoff_location='$dropoff'
                             AND status='active'");

        // Free the taxi and reset its passenger count
        mysqli_query($con, "UPDATE drivers
                             SET status='available', current_passengers=0
                             WHERE id='$driver_id'");
    } else {
        // No driver attached (shouldn't normally happen) — just complete this one
        mysqli_query($con, "UPDATE bookings SET status='completed' WHERE id='$id'");
    }
}
?>
