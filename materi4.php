<?php
function login($username, $password){
    $username = "Acan Ganteng";
    $password = "4_CN!!!";
    if ($username == $username && $password == $password) {
        return true;
    }else {
        return false;
    }
}

?>
<form method="POST">
    <label for="username"> username: </label>
    <input type="text" name="username"><br><br>
    <label for="password"> password: </label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="login">
</form>

<?php
if (isset($_post["login"])){
    $newusername = $_post["username"];
    $newpassword = $_post["password"];
    if (login($newusername, $newpassword)){
        echo "login sukses";
    } else {
        echo "login gagal";
    }
    
}