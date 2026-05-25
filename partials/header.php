<?php
$base = $base ?? '';
$title = $title ?? 'Sistem Penjualan Sembako';
$current = $current ?? '';
$flash = get_flash();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= e($base) ?>assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">S</div>
            <div>
                <h1>SembakoMart</h1>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="menu">
            <a class="menu-link <?= $current === 'dashboard' ? 'active' : '' ?>" href="<?= e($base) ?>dashboard.php">
                <span>⌂</span> Dashboard
            </a>
            <a class="menu-link <?= $current === 'barang' ? 'active' : '' ?>" href="<?= e($base) ?>barang/index.php">
                <span>▦</span> Data Barang
            </a>
            <a class="menu-link <?= $current === 'penjualan' ? 'active' : '' ?>" href="<?= e($base) ?>penjualan/index.php">
                <span>🧾</span> Penjualan
            </a>
            <a class="menu-link <?= $current === 'laporan' ? 'active' : '' ?>" href="<?= e($base) ?>laporan/index.php">
                <span>▤</span> Laporan
            </a>
        </nav>

        <div class="sidebar-footer">
            <span>Login sebagai</span>
            <strong><?= e($_SESSION['admin']['nama'] ?? 'Admin') ?></strong>
            <a class="logout" href="<?= e($base) ?>logout.php">Logout</a>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <button class="hamburger" type="button" onclick="toggleSidebar()">☰</button>
            <div>
                <p class="muted">Sistem Informasi Web</p>
                <h2><?= e($title) ?></h2>
            </div>
        </header>

        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
