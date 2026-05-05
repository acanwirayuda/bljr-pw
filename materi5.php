<?php
include 'koneksi.php';
if (isset($_POST['kirim'])){
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $sql = "INSERT INTO username (id, username, password, nama, email) values ('$id', '$username', '$password', '$nama', '$email')";
    $query = mysqli_query($conn, $sql);

    if($query) {
        echo "data berhasil di tambahkan";
    }else {
        echo "data gagal di tambahkan";
    }
}
?>

<form method="POST">
    id: <input type="int" name="id">
    username: <input type="text" name="username">
    password: <input type="password" name="password">
    nama: <input type="text" name="nama">
    email: <input type="text" name="email">
    <input type="submit" value="kirim data" name="kirim">
</form>

//menampilkan data dalam tabel
<table border="1">
    <tr>
        <th>id</th>
        <th>username</th>
        <th>password</th>
        <th>nama</th>
        <th>email</th>
        <th>aksi</th>
</tr>
<?php
$sql = "SELECT * FROM username";
$query = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($query)){
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['username']}</td>
    <td>{$row['password']}</td>
    <td>{$row['nama']}</td>
    <td>{$row['email']}</td>
    <td><a href='materi5.php?Hapus={$row['id']}'>hapus</a> | <a href=?edit={$row['id']}>edit</a>
    </tr>";
}
//proses hapus
if (isset($_get["hapus"])){
    echo "data berhasil di hapus";
    $id = $_get['hapus'];
    $sql = "DELETE FROM username WHERE id= '$id'";
    $query =  mysqli_query($conn, $sql);
    if (query){
        echo "Data Berhasil Di Hapus";
    } else {
        echo "Data Gagal Di Hapus";
    }
    
}
?>