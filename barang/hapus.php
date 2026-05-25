<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

verify_csrf();
$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $conn->prepare('DELETE FROM barang WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    set_flash('success', 'Data barang berhasil dihapus.');
} catch (mysqli_sql_exception $e) {
    set_flash('danger', 'Barang tidak bisa dihapus karena sudah pernah masuk transaksi. Gunakan edit stok/nama barang jika ingin memperbarui data.');
}

redirect('index.php');
