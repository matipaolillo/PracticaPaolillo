<?php


    $login_email = $_POST["login_email"];
    $login_pwd = $_POST["login_password"];
    
    if($login_email == "hola@gmail.com" && $login_pwd == "123"){
        header("location: ../Vista/perfil.php");
        exit();
    }else{
        echo "Error";
    }
?>