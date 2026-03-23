<?php

    include '../Modelo/conexion.php';

    $nombre = $_POST["name"];
    $email = $_POST["email"];
    $pwd = $_POST["password"];

    $stmt = $conn->prepare("INSERT INTO usuario(usr_name, usr_email, usr_pass) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $pwd);
    try {
        $stmt->execute();
    } catch (\Throwable $th) {
        echo "error de registro: Ya existe alguien con ese email.";
        exit();
    }
    

    $stmt->close();
    $conn->close();

    header("location: ../Vista/sesion.php");
    exit();
?>