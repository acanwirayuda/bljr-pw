<?php
require_once __DIR__ . '/functions.php';

function require_login(string $loginUrl = 'login.php'): void
{
    if (empty($_SESSION['admin'])) {
        set_flash('warning', 'Silakan login terlebih dahulu.');
        redirect($loginUrl);
    }
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin']);
}
