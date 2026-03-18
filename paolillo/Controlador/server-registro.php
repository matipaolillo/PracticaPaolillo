<?php
    $nombre = $_POST["name"];
    $email = $_POST["email"];
    $pwd = $_POST["password"];

    header("location: sesion.php");
    exit();
?>