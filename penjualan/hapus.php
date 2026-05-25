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
    $conn->begin_transaction();

    $stmt = $conn->prepare('SELECT barang_id, jumlah FROM penjualan_detail WHERE penjualan_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $details = $stmt->get_result();

    while ($row = $details->fetch_assoc()) {
        $stmtUpdate = $conn->prepare('UPDATE barang SET stok = stok + ? WHERE id = ?');
        $jumlah = (int)$row['jumlah'];
        $barangId = (int)$row['barang_id'];
        $stmtUpdate->bind_param('ii', $jumlah, $barangId);
        $stmtUpdate->execute();
    }

    $stmt = $conn->prepare('DELETE FROM penjualan WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $conn->commit();
    set_flash('success', 'Transaksi berhasil dihapus dan stok barang dikembalikan.');
} catch (Throwable $e) {
    $conn->rollback();
    set_flash('danger', 'Gagal menghapus transaksi.');
}

redirect('index.php');
