<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Penjualan';
$current = 'penjualan';
$base = '../';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $keyword = '%' . $q . '%';
    $stmt = $conn->prepare('SELECT * FROM penjualan WHERE kode_transaksi LIKE ? OR nama_pelanggan LIKE ? ORDER BY tanggal DESC');
    $stmt->bind_param('ss', $keyword, $keyword);
    $stmt->execute();
    $penjualan = $stmt->get_result();
} else {
    $penjualan = $conn->query('SELECT * FROM penjualan ORDER BY tanggal DESC');
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card">
    <div class="card-header with-actions">
        <div>
            <h3>Data Penjualan</h3>
            <p>Catat transaksi penjualan dan stok barang akan berkurang otomatis.</p>
        </div>
        <a class="btn btn-primary" href="tambah.php">+ Tambah Penjualan</a>
    </div>

    <form class="toolbar" method="get">
        <input type="search" name="q" placeholder="Cari kode transaksi atau nama pelanggan..." value="<?= e($q) ?>">
        <button class="btn btn-light" type="submit">Cari</button>
        <?php if ($q !== ''): ?>
            <a class="btn btn-outline" href="index.php">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($penjualan->num_rows): $no = 1; ?>
                    <?php while ($row = $penjualan->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= e($row['kode_transaksi']) ?></strong></td>
                            <td><?= e(date('d/m/Y H:i', strtotime($row['tanggal']))) ?></td>
                            <td><?= e($row['nama_pelanggan'] ?: 'Umum') ?></td>
                            <td><?= rupiah($row['total']) ?></td>
                            <td><?= rupiah($row['bayar']) ?></td>
                            <td><?= rupiah($row['kembalian']) ?></td>
                            <td class="actions">
                                <a class="btn btn-sm btn-light" href="detail.php?id=<?= (int)$row['id'] ?>">Detail</a>
                                <form action="hapus.php" method="post" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok barang akan dikembalikan.')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="empty">Data penjualan belum tersedia.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
