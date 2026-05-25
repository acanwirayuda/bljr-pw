<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare('SELECT id, nama, email, password FROM admins WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin'] = [
                'id' => $admin['id'],
                'nama' => $admin['nama'],
                'email' => $admin['email'],
            ];
            redirect('dashboard.php');
        }

        $error = 'Email atau password salah.';
    }
}

$flash = get_flash();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - SembakoMart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <section class="login-wrapper">
        <div class="login-hero">
            <div class="badge">Toko Sembako</div>
            <h1>Sistem Informasi Penjualan Toko Sembako</h1>
            <p>Kelola data barang, stok, transaksi penjualan, dan laporan toko dengan tampilan sederhana dan modern.</p>
        </div>

        <div class="login-card">
            <div class="brand login-brand">
                <div class="brand-logo">S</div>
                <div>
                    <h1>SembakoMart</h1>
                    <span>Login Admin</span>
                </div>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Email Admin</label>
                    <input type="email" name="email" placeholder="admin@sembako.test" value="<?= e($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk Dashboard</button>
            </form>

            <div class="login-note">
                <strong>Akun default:</strong><br>
                Email: admin@sembako.test<br>
                Password: admin123
            </div>
        </div>
    </section>
</body>
</html>
