<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Tambah Penjualan';
$current = 'penjualan';
$base = '../';
$errors = [];

$barangList = $conn->query('SELECT id, kode_barang, nama_barang, harga_jual, stok, satuan FROM barang ORDER BY nama_barang ASC');
$data = [
    'nama_pelanggan' => 'Umum',
    'barang_id' => '',
    'jumlah' => 1,
    'bayar' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data['nama_pelanggan'] = trim($_POST['nama_pelanggan'] ?? 'Umum');
    $data['barang_id'] = (int)($_POST['barang_id'] ?? 0);
    $data['jumlah'] = (int)($_POST['jumlah'] ?? 0);
    $data['bayar'] = trim($_POST['bayar'] ?? '');

    if ($data['barang_id'] <= 0) $errors[] = 'Barang wajib dipilih.';
    if ($data['jumlah'] <= 0) $errors[] = 'Jumlah beli minimal 1.';
    if (!is_numeric($data['bayar']) || $data['bayar'] < 0) $errors[] = 'Nominal bayar tidak valid.';

    if (!$errors) {
        $stmt = $conn->prepare('SELECT id, nama_barang, harga_jual, stok FROM barang WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $data['barang_id']);
        $stmt->execute();
        $barang = $stmt->get_result()->fetch_assoc();

        if (!$barang) {
            $errors[] = 'Barang tidak ditemukan.';
        } elseif ((int)$barang['stok'] < $data['jumlah']) {
            $errors[] = 'Stok barang tidak cukup. Stok tersedia: ' . (int)$barang['stok'];
        } else {
            $harga = (float)$barang['harga_jual'];
            $subtotal = $harga * $data['jumlah'];
            $bayar = (float)$data['bayar'];
            $kembalian = $bayar - $subtotal;

            if ($bayar < $subtotal) {
                $errors[] = 'Nominal bayar kurang dari total belanja.';
            } else {
                try {
                    $conn->begin_transaction();
                    $kode = generate_code('TRX');
                    $namaPelanggan = $data['nama_pelanggan'] !== '' ? $data['nama_pelanggan'] : 'Umum';
                    $adminId = (int)$_SESSION['admin']['id'];

                    $stmt = $conn->prepare('INSERT INTO penjualan (kode_transaksi, admin_id, nama_pelanggan, total, bayar, kembalian) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->bind_param('sisddd', $kode, $adminId, $namaPelanggan, $subtotal, $bayar, $kembalian);
                    $stmt->execute();
                    $penjualanId = $conn->insert_id;

                    $stmt = $conn->prepare('INSERT INTO penjualan_detail (penjualan_id, barang_id, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)');
                    $stmt->bind_param('iidid', $penjualanId, $data['barang_id'], $harga, $data['jumlah'], $subtotal);
                    $stmt->execute();

                    $stmt = $conn->prepare('UPDATE barang SET stok = stok - ? WHERE id = ?');
                    $stmt->bind_param('ii', $data['jumlah'], $data['barang_id']);
                    $stmt->execute();

                    $conn->commit();
                    set_flash('success', 'Transaksi penjualan berhasil disimpan.');
                    redirect('detail.php?id=' . $penjualanId);
                } catch (Throwable $e) {
                    $conn->rollback();
                    $errors[] = 'Gagal menyimpan transaksi: ' . $e->getMessage();
                }
            }
        }
    }

    $barangList = $conn->query('SELECT id, kode_barang, nama_barang, harga_jual, stok, satuan FROM barang ORDER BY nama_barang ASC');
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card form-card">
    <div class="card-header">
        <div>
            <h3>Form Transaksi Penjualan</h3>
            <p>Versi sederhana: satu transaksi untuk satu jenis barang. Cocok untuk tugas CRUD dan alur penjualan dasar.</p>
        </div>
    </div>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?= e($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="<?= e($data['nama_pelanggan']) ?>" placeholder="Umum">
        </div>
        <div class="form-group">
            <label>Pilih Barang</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php while ($row = $barangList->fetch_assoc()): ?>
                    <option value="<?= (int)$row['id'] ?>" <?= (int)$data['barang_id'] === (int)$row['id'] ? 'selected' : '' ?>>
                        <?= e($row['nama_barang']) ?> - <?= rupiah($row['harga_jual']) ?> | Stok: <?= e($row['stok']) ?> <?= e($row['satuan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Beli</label>
            <input type="number" name="jumlah" min="1" value="<?= e($data['jumlah']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nominal Bayar</label>
            <input type="number" name="bayar" min="0" step="100" value="<?= e($data['bayar']) ?>" placeholder="Contoh: 50000" required>
        </div>
        <div class="form-actions">
            <a class="btn btn-outline" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan Transaksi</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
