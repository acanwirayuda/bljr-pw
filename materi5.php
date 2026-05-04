<?php
include 'koneksi.php';
if (isset($_POST['kirim'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $sql = "INSERT INTO username (username, password, nama, email) values ('$username', '$password', '$nama', '$email')";
    $query = mysqli_query($conn, $sql);

    if($query) {
        echo "data berhasil di tambahkan";
    }else {
        echo "data gagal di tambahkan";
    }
}
?>

<form method="POST">
    username: <input type="text" name="username">
    password: <input type="password" name="password">
    nama: <input type="text" name="nama">
    email: <input type="text" name="email">
    <input type="submit" value="kirim data" name="kirim">
</form>
