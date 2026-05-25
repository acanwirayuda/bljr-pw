<?php

 $nama = "abdull";
 $umur = 20;
 $tinggi = 17;
 $menikah = true;
 $hobi = ["bola", "baslet", "voli"];

 echo "nama saya $nama, umur saya $umur, tinggi saya $tinggi, setats saya $menikah, hobi saya $hobi[1]";


 echo "<br><br>============<br><br>";

 //operator
 $nilai1 = 5;
 $nilai2 = 8;
 $nilai3 = 10;
 $nilai4 = 20;
 $nilai5 = 1000;

 $hasil = $nilai1 + $nilai2 * $nilai3 + $nilai4 /$nilai5;

 echo "hasil dari penjumlahan di atas adalah $hasil";

 echo "<br><br>============<br><br>";


 //percabangan

 if ($nilai5 >= 85) {
    echo "grade A";
 }else if ($nilai1 >= 70) {
    echo "grade B";
 }else if ($nilai2 >= 50) {
    echo "grade C";
 }else if ($nilai3 >= 30) {
    echo "grade D";
 }else {

 }
?>

 