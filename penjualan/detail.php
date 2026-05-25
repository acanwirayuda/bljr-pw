<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Detail Penjualan';
$current = 'penjualan';
$base = '../';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT p.*, a.nama AS nama_admin FROM penjualan p LEFT JOIN admins a ON p.admin_id = a.id WHERE p.id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$penjualan = $stmt->get_result()->fetch_assoc();

if (!$penjualan) {
    set_flash('danger', 'Transaksi tidak ditemukan.');
    redirect('index.php');
}

$stmt = $conn->prepare('SELECT d.*, b.kode_barang, b.nama_barang, b.satuan FROM penjualan_detail d INNER JOIN barang b ON d.barang_id = b.id WHERE d.penjualan_id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$detail = $stmt->get_result();

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card receipt">
    <div class="card-header with-actions">
        <div>
            <h3>Detail Transaksi</h3>
            <p>Kode: <strong><?= e($penjualan['kode_transaksi']) ?></strong></p>
        </div>
        <div class="actions">
            <button class="btn btn-light" onclick="window.print()">Cetak</button>
            <a class="btn btn-outline" href="index.php">Kembali</a>
        </div>
    </div>

    <div class="receipt-info">
        <div>
            <span>Tanggal</span>
            <strong><?= e(date('d/m/Y H:i', strtotime($penjualan['tanggal']))) ?></strong>
        </div>
        <div>
            <span>Pelanggan</span>
            <strong><?= e($penjualan['nama_pelanggan'] ?: 'Umum') ?></strong>
        </div>
        <div>
            <span>Admin</span>
            <strong><?= e($penjualan['nama_admin'] ?? '-') ?></strong>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $detail->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?= e($row['nama_barang']) ?></strong><br>
                            <small><?= e($row['kode_barang']) ?></small>
                        </td>
                        <td><?= rupiah($row['harga']) ?></td>
                        <td><?= e($row['jumlah']) ?> <?= e($row['satuan']) ?></td>
                        <td><?= rupiah($row['subtotal']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="summary-box">
        <div><span>Total</span><strong><?= rupiah($penjualan['total']) ?></strong></div>
        <div><span>Bayar</span><strong><?= rupiah($penjualan['bayar']) ?></strong></div>
        <div><span>Kembalian</span><strong><?= rupiah($penjualan['kembalian']) ?></strong></div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
