<?php 

include '../core/database.php';


function adminLogin($email, $password){
    global $con;

    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if(!$result){
        return "not_found";
    }

    $data = mysqli_fetch_assoc($result);

    if($data){
        if(password_verify($password, $data['password'])){
            return $data;
        } else {
            return "wrong_password";
        }
    } else {
        return "not_found";
    }
}


function getAllBookings(){
    global $con;

    $sql = "SELECT * FROM bookings ORDER BY id DESC";
    return mysqli_query($con, $sql);
}


function getActiveBookings(){
    global $con;

    $sql = "SELECT * FROM bookings WHERE status != 'completed' ORDER BY id DESC";
    return mysqli_query($con, $sql);
}


function updateBookingStatus($id, $status){
    global $con;

    $sql = "UPDATE bookings SET status = '$status' WHERE id = '$id'";
    return mysqli_query($con, $sql);
}


function addAdminLog($admin_id, $action){
    global $con;

    $sql = "INSERT INTO admin_logs (admin_id, action)
            VALUES ('$admin_id', '$action')";
    return mysqli_query($con, $sql);
}
?>