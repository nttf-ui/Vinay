<?php
$conn = mysqli_connect("localhost","root","","otp_verification");
if(!$conn){
    echo"connection failed".mysql_connect_error() or die();
}