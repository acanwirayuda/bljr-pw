<?php
<<<<<<< HEAD
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

redirect('login.php');
=======
echo "
<a href='materi1.php'>Materi 1: if dan else</a><br><br>
<a href='materi2.php'>Materi 2: looping</a><br><br>
<a href='materi3.php'>Materi 3: function</a><br><br>
<a href='materi4.php'>Materi 4: function</a><br><br>
<a href='materi5.php'>Materi 5: koneksi database</a><br><br>
";
?>
>>>>>>> 23e95c929b95dbbdeeaa9e946d7dc4659d788812
