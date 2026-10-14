<?php
session_start();
session_destroy();
header("Location: /taxi_reservation/app/views/login.php");
exit();
?>
