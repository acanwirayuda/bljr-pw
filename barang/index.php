<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Data Barang';
$current = 'barang';
$base = '../';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $keyword = '%' . $q . '%';
    $stmt = $conn->prepare('SELECT * FROM barang WHERE kode_barang LIKE ? OR nama_barang LIKE ? OR kategori LIKE ? ORDER BY id DESC');
    $stmt->bind_param('sss', $keyword, $keyword, $keyword);
    $stmt->execute();
    $barang = $stmt->get_result();
} else {
    $barang = $conn->query('SELECT * FROM barang ORDER BY id DESC');
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card">
    <div class="card-header with-actions">
        <div>
            <h3>Kelola Barang</h3>
            <p>Tambah, ubah, cari, dan hapus data barang toko sembako.</p>
        </div>
        <a class="btn btn-primary" href="tambah.php">+ Tambah Barang</a>
    </div>

    <form class="toolbar" method="get">
        <input type="search" name="q" placeholder="Cari kode, nama, atau kategori barang..." value="<?= e($q) ?>">
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
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($barang->num_rows): $no = 1; ?>
                    <?php while ($row = $barang->fetch_assoc()): ?>
                        <?php $menipis = (int)$row['stok'] <= (int)$row['stok_minimum']; ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= e($row['kode_barang']) ?></strong></td>
                            <td><?= e($row['nama_barang']) ?></td>
                            <td><?= e($row['kategori']) ?></td>
                            <td><?= rupiah($row['harga_beli']) ?></td>
                            <td><?= rupiah($row['harga_jual']) ?></td>
                            <td><?= e($row['stok']) ?> <?= e($row['satuan']) ?></td>
                            <td>
                                <?php if ($menipis): ?>
                                    <span class="pill danger">Stok Menipis</span>
                                <?php else: ?>
                                    <span class="pill success">Aman</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a class="btn btn-sm btn-warning" href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
                                <form action="hapus.php" method="post" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="empty">Data barang belum tersedia.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
