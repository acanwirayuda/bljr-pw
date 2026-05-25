<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/auth.php';
require_login('../login.php');

$title = 'Tambah Barang';
$current = 'barang';
$base = '../';
$errors = [];

$data = [
    'kode_barang' => generate_code('BRG'),
    'nama_barang' => '',
    'kategori' => '',
    'satuan' => 'pcs',
    'harga_beli' => '',
    'harga_jual' => '',
    'stok' => '',
    'stok_minimum' => 5,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

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
            $stmt = $conn->prepare('INSERT INTO barang (kode_barang, nama_barang, kategori, satuan, harga_beli, harga_jual, stok, stok_minimum) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $hargaBeli = (float)$data['harga_beli'];
            $hargaJual = (float)$data['harga_jual'];
            $stok = (int)$data['stok'];
            $stokMinimum = (int)$data['stok_minimum'];
            $stmt->bind_param('ssssddii', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['satuan'], $hargaBeli, $hargaJual, $stok, $stokMinimum);
            $stmt->execute();
            set_flash('success', 'Data barang berhasil ditambahkan.');
            redirect('index.php');
        } catch (mysqli_sql_exception $e) {
            $errors[] = 'Gagal menyimpan. Kode barang mungkin sudah digunakan.';
        }
    }
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="card form-card">
    <div class="card-header">
        <div>
            <h3>Form Tambah Barang</h3>
            <p>Isi data barang dengan benar agar stok dan laporan penjualan akurat.</p>
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
            <input type="text" name="nama_barang" value="<?= e($data['nama_barang']) ?>" placeholder="Contoh: Beras Premium 5 Kg" required>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= e($data['kategori']) ?>" placeholder="Contoh: Beras, Minyak, Gula" required>
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
            <button class="btn btn-primary" type="submit">Simpan Barang</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
