<?php
$_SERVER = "localhost";
$username = "root";
$password = "";
$database = "db_acan";

$conn = mysqli_connect($_SERVER, $username, $password, $database);
if (mysqli_connect_errno()){
    echo "koneksi gagal";
}else {
    echo "koneksi sukses";
}
?>
