<?php
// Konfigurasi koneksi database
// Sesuaikan jika username/password MySQL di perangkat Anda berbeda.
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_sembako';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
}
