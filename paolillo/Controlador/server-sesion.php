<?php
    include '../Modelo/conexion.php';

    $login_email = $_POST["login_email"];
    $login_pwd = $_POST["login_password"];
    
   
    $query = "SELECT * FROM usuario WHERE usr_email = '$login_email' AND usr_pass = '$login_pwd'";
	$result = mysqli_query($conn, $query);
	$usuarios = [];
	while($row = mysqli_fetch_assoc($result)) {
		$usuarios[] = $row;
	}

    if($usuarios[0]["usr_email"] == $login_email && $usuarios[0]["usr_pass"] == $login_pwd){
        echo "login exitoso";
        header("Location: ../Vista/perfil.php");
        exit();
    }else{
        echo "login fallido";
        header("Location: ../Vista/sesion.php");
        exit();
    }

    
?>