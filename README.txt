SISTEM INFORMASI WEB PENJUALAN TOKO SEMBAKO
================================================

Stack:
- PHP Native
- MySQL/MariaDB
- CSS custom tanpa framework eksternal

Fitur:
1. Login admin tanpa register admin.
2. Dashboard ringkasan toko.
3. CRUD data barang.
4. Pencarian barang.
5. Status stok menipis otomatis berdasarkan stok minimum.
6. Transaksi penjualan sederhana.
7. Stok barang otomatis berkurang saat transaksi disimpan.
8. Detail/cetak transaksi.
9. Laporan penjualan berdasarkan tanggal.
10. Desain simpel, modern, responsive.

Cara menjalankan di XAMPP:
1. Extract folder sistem_penjualan_sembako.
2. Pindahkan folder ke:
   Windows: C:\xampp\htdocs\
   MacOS: /Applications/XAMPP/htdocs/
3. Buka phpMyAdmin.
4. Import file database/db_sembako.sql.
5. Buka browser:
   http://localhost/sistem_penjualan_sembako/

Akun admin default:
Email    : admin@sembako.test
Password : admin123

Jika koneksi database error:
- Buka file config/koneksi.php
- Sesuaikan $host, $user, $pass, dan $db dengan pengaturan MySQL Anda.

Catatan:
- Admin tidak melakukan register dari website.
- Akun admin dibuat dari database sesuai alur umum website admin panel.
- Untuk tugas kuliah, struktur ini sudah mencakup login admin, CRUD, transaksi, dan laporan.
