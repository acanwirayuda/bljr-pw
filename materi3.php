<?php
function hello()
{
    echo "Saya Ingin Berkerja Di UTM";
}
hello();

function tambah (int $a, int $b)
{
    $hasil = $a = $b;
    return $hasil;
}
echo "<br><br>";
echo tambah(12, 8);

?>

<form menthod = "post">
    Masukan angka 1: <input type = "number" name = "angka 1">
    Masukan angka 2: <input type = "number" name = "angka 2">
    <input type = "submit" name = "kirim" value = "kirim">
</form>

<?php
if(isset ($_POST["angka"])){
    $newangka1 = $_post["angka1"];
    $newangka1 = $_post["angka2"];
    echo tambah ($newangka1, $newangka2);
    echo "<br>";
    echo kali ($newangka1, $newangka2);
    echo bagi ($newangka1, $newangka2);
    echo kurang ($newangka1, $newangka2);
}
    