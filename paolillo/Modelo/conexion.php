<?php
	$hostname = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'base_usuarios';
	// Establish database connection
	$conn = mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if(!$conn){
		die('Connection failed: ' . mysqli_connect_error());
	}
	function insertUsuario($conn, $nombre,$pwd,$email,$rutaImagen){
		if ($rutaImagen !== null) {
            $stmt = $conn->prepare("INSERT INTO usuario(usr_name, usr_email, usr_pass, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $pwd, $rutaImagen);
        } else {
            $stmt = $conn->prepare("INSERT INTO usuario(usr_name, usr_email, usr_pass) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $pwd);
        }

        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            if ($rutaImagen !== null && file_exists(__DIR__ . '/../' . $rutaImagen)) {
                unlink(__DIR__ . '/../' . $rutaImagen);
            }
            echo "error de registro: Ya existe alguien con ese email.";
            exit();
        }

        $stmt->close();
        $conn->close();

        header("Location: ../Vista/sesion.php");
        exit();
	}
    /*
	CREATE DATABASE IF NOT EXISTS base_usuarios;
	CREATE TABLE IF NOT EXISTS base_usuarios.usuario (
	  id INT(11) NOT NULL AUTO_INCREMENT,
	  usr_name VARCHAR(100) NOT NULL,
	  usr_email VARCHAR(100) UNIQUE NOT NULL,
	  usr_pass VARCHAR(100) NOT NULL,
	  imagen VARCHAR(100) DEFAULT NULL,
	  PRIMARY KEY (id)
	);
    CREATE TABLE IF NOT EXISTS base_usuarios.publicaciones (
    id_publicacion INT(11) NOT NULL AUTO_INCREMENT,
    mensaje TEXT NOT NULL,
    id_usuario INT(11) NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_publicacion),
    CONSTRAINT fk_usuario_publicacion 
        FOREIGN KEY (id_usuario) 
        REFERENCES base_usuarios.usuario(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
	

	SELECT * FROM usuario;
    select * from publicaciones;
	*/
?>
