<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Edit Barang';
$current = 'barang';
$base = '../';
$errors = [];

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM barang WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    set_flash('danger', 'Data barang tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data['kode_barang'] = trim($_POST['kode_barang'] ?? '');
    $data['nama_barang'] = trim($_POST['nama_barang'] ?? '');
    $data['kategori'] = trim($_POST['kategori'] ?? '');
    $data['satuan'] = trim($_POST['satuan'] ?? '');
    $data['harga_beli'] = trim($_POST['harga_beli'] ?? '');
    $data['harga_jual'] = trim($_POST['harga_jual'] ?? '');
    $data['stok'] = trim($_POST['stok'] ?? '');
    $data['stok_minimum'] = trim($_POST['stok_minimum'] ?? '');

    if ($data['kode_barang'] === '') $errors[] = 'Kode barang wajib diisi.';
    if ($data['nama_barang'] === '') $errors[] = 'Nama barang wajib diisi.';
    if ($data['kategori'] === '') $errors[] = 'Kategori wajib diisi.';
    if ($data['satuan'] === '') $errors[] = 'Satuan wajib diisi.';
    if (!is_numeric($data['harga_beli']) || $data['harga_beli'] < 0) $errors[] = 'Harga beli tidak valid.';
    if (!is_numeric($data['harga_jual']) || $data['harga_jual'] < 0) $errors[] = 'Harga jual tidak valid.';
    if (!ctype_digit((string)$data['stok'])) $errors[] = 'Stok harus berupa angka bulat.';
    if (!ctype_digit((string)$data['stok_minimum'])) $errors[] = 'Stok minimum harus berupa angka bulat.';

    if (!$errors) {
        try {
            $stmt = $conn->prepare('UPDATE barang SET kode_barang=?, nama_barang=?, kategori=?, satuan=?, harga_beli=?, harga_jual=?, stok=?, stok_minimum=? WHERE id=?');
            $hargaBeli = (float)$data['harga_beli'];
            $hargaJual = (float)$data['harga_jual'];
            $stok = (int)$data['stok'];
            $stokMinimum = (int)$data['stok_minimum'];
            $stmt->bind_param('ssssddiii', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['satuan'], $hargaBeli, $hargaJual, $stok, $stokMinimum, $id);
            $stmt->execute();
            set_flash('success', 'Data barang berhasil diperbarui.');
            redirect('index.php');
        } catch (mysqli_sql_exception $e) {
            $errors[] = 'Gagal memperbarui. Kode barang mungkin sudah digunakan.';
        }
    }
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card form-card">
    <div class="card-header">
        <div>
            <h3>Form Edit Barang</h3>
            <p>Perbarui data barang sesuai kondisi terbaru di toko.</p>
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
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" value="<?= e($data['kode_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= e($data['nama_barang']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= e($data['kategori']) ?>" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <select name="satuan" required>
                <?php foreach (['pcs', 'kg', 'liter', 'dus', 'pack', 'botol', 'kaleng', 'sachet'] as $satuan): ?>
                    <option value="<?= e($satuan) ?>" <?= $data['satuan'] === $satuan ? 'selected' : '' ?>><?= e($satuan) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" min="0" step="100" value="<?= e($data['harga_beli']) ?>" required>
        </div>
        <div class="form-group">
            <label>Harga Jual</label>
            <input type="number" name="harga_jual" min="0" step="100" value="<?= e($data['harga_jual']) ?>" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" min="0" value="<?= e($data['stok']) ?>" required>
        </div>
        <div class="form-group">
            <label>Stok Minimum</label>
            <input type="number" name="stok_minimum" min="0" value="<?= e($data['stok_minimum']) ?>" required>
        </div>
        <div class="form-actions">
            <a class="btn btn-outline" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
