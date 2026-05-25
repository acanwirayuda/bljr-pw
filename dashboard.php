<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
require_login('login.php');

$title = 'Dashboard';
$current = 'dashboard';
$base = '';

$totalBarang = (int)$conn->query('SELECT COUNT(*) AS total FROM barang')->fetch_assoc()['total'];
$stokMenipis = (int)$conn->query('SELECT COUNT(*) AS total FROM barang WHERE stok <= stok_minimum')->fetch_assoc()['total'];
$totalTransaksi = (int)$conn->query('SELECT COUNT(*) AS total FROM penjualan')->fetch_assoc()['total'];
$pendapatanHariIni = (float)$conn->query("SELECT COALESCE(SUM(total), 0) AS total FROM penjualan WHERE DATE(tanggal) = CURDATE()")->fetch_assoc()['total'];

$barangMenipis = $conn->query('SELECT kode_barang, nama_barang, stok, satuan, stok_minimum FROM barang WHERE stok <= stok_minimum ORDER BY stok ASC LIMIT 6');
$transaksiTerbaru = $conn->query('SELECT kode_transaksi, tanggal, nama_pelanggan, total FROM penjualan ORDER BY tanggal DESC LIMIT 6');

require_once __DIR__ . '/partials/header.php';
?>

<section class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">▦</div>
        <div>
            <span>Total Barang</span>
            <h3><?= $totalBarang ?></h3>
        </div>
    </div>
    <div class="stat-card danger-soft">
        <div class="stat-icon">!</div>
        <div>
            <span>Stok Menipis</span>
            <h3><?= $stokMenipis ?></h3>
        </div>
    </div>
    <div class="stat-card success-soft">
        <div class="stat-icon">🧾</div>
        <div>
            <span>Total Transaksi</span>
            <h3><?= $totalTransaksi ?></h3>
        </div>
    </div>
    <div class="stat-card primary-soft">
        <div class="stat-icon">Rp</div>
        <div>
            <span>Pendapatan Hari Ini</span>
            <h3><?= rupiah($pendapatanHariIni) ?></h3>
        </div>
    </div>
</section>

<section class="grid-2 mt-24">
    <div class="card">
        <div class="card-header">
            <div>
                <h3>Stok Menipis</h3>
                <p>Barang yang perlu segera ditambah.</p>
            </div>
            <a class="btn btn-light" href="barang/index.php">Lihat Barang</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($barangMenipis->num_rows): ?>
                    <?php while ($row = $barangMenipis->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['kode_barang']) ?></td>
                            <td><?= e($row['nama_barang']) ?></td>
                            <td><span class="pill danger"><?= e($row['stok']) ?> <?= e($row['satuan']) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="empty">Tidak ada stok menipis.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h3>Transaksi Terbaru</h3>
                <p>Daftar penjualan terakhir.</p>
            </div>
            <a class="btn btn-light" href="penjualan/index.php">Lihat Penjualan</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($transaksiTerbaru->num_rows): ?>
                    <?php while ($row = $transaksiTerbaru->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['kode_transaksi']) ?></td>
                            <td><?= e($row['nama_pelanggan'] ?: 'Umum') ?></td>
                            <td><?= rupiah($row['total']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="empty">Belum ada transaksi.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
