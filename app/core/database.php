<?php 
   
   

   $con = mysqli_connect(  "localhost","root","","taxi_system" );


   if(!$con){
    die("connection failed:".mysqli_connect_error());
   }

?>