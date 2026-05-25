<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Laporan Penjualan';
$current = 'laporan';
$base = '../';

$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

$stmt = $conn->prepare('SELECT p.*, a.nama AS nama_admin FROM penjualan p LEFT JOIN admins a ON p.admin_id = a.id WHERE DATE(p.tanggal) BETWEEN ? AND ? ORDER BY p.tanggal DESC');
$stmt->bind_param('ss', $dari, $sampai);
$stmt->execute();
$laporan = $stmt->get_result();

$stmtTotal = $conn->prepare('SELECT COUNT(*) AS total_transaksi, COALESCE(SUM(total), 0) AS total_pendapatan FROM penjualan WHERE DATE(tanggal) BETWEEN ? AND ?');
$stmtTotal->bind_param('ss', $dari, $sampai);
$stmtTotal->execute();
$ringkasan = $stmtTotal->get_result()->fetch_assoc();

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card">
    <div class="card-header with-actions">
        <div>
            <h3>Laporan Penjualan</h3>
            <p>Filter laporan berdasarkan rentang tanggal dan cetak jika dibutuhkan.</p>
        </div>
        <button class="btn btn-light" onclick="window.print()">Cetak Laporan</button>
    </div>

    <form class="toolbar report-filter" method="get">
        <div class="form-group inline">
            <label>Dari</label>
            <input type="date" name="dari" value="<?= e($dari) ?>">
        </div>
        <div class="form-group inline">
            <label>Sampai</label>
            <input type="date" name="sampai" value="<?= e($sampai) ?>">
        </div>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>

    <section class="stats-grid compact">
        <div class="stat-card">
            <div class="stat-icon">🧾</div>
            <div>
                <span>Total Transaksi</span>
                <h3><?= (int)$ringkasan['total_transaksi'] ?></h3>
            </div>
        </div>
        <div class="stat-card success-soft">
            <div class="stat-icon">Rp</div>
            <div>
                <span>Total Pendapatan</span>
                <h3><?= rupiah($ringkasan['total_pendapatan']) ?></h3>
            </div>
        </div>
    </section>

    <div class="table-responsive mt-16">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Admin</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($laporan->num_rows): $no = 1; ?>
                    <?php while ($row = $laporan->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= e($row['kode_transaksi']) ?></td>
                            <td><?= e(date('d/m/Y H:i', strtotime($row['tanggal']))) ?></td>
                            <td><?= e($row['nama_pelanggan'] ?: 'Umum') ?></td>
                            <td><?= e($row['nama_admin'] ?? '-') ?></td>
                            <td><?= rupiah($row['total']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="empty">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
