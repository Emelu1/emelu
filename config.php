<?php
$sname = "localhost";
$uname = "root";
$password = "";
$dbname = "bincom_test";

$conn = mysqli_connect($sname, $uname, $password, $dbname);

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>