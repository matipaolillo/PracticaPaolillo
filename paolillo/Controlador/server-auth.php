<?php
session_start();
include '../Modelo/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Vista/sesion.php");
    exit();
}

$accion = $_POST['accion'] ?? '';
switch($accion){
    case 'registro':
        registrarUsuario($conn);
        break;
    case 'login':
        logearUsuario($conn);
        break;
    
    case 'logout':
        logoutUsuario();
        break;
    case 'Publicar':
        publicar($conn);
        break;
    
    default:
        echo "accion no valida";
        break;
}
function registrarUsuario($conn){
    $nombre = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $pwd = $_POST["password"] ?? '';
    $pwd_confirm = $_POST["password-confirm"] ?? '';
    $rutaImagen = null;

    if(empty($nombre) || empty($email) || empty($pwd) || empty($pwd_confirm)) {
        header("Location: ../Vista/registro.php?mensaje=Todos los campos son obligatorios");
        exit();
    } else if(strlen($pwd) < 8) {
        header("Location: ../Vista/registro.php?mensaje=La contraseña debe tener al menos 8 caracteres");
        exit();
    } else if($pwd !== $pwd_confirm) {
        header("Location: ../Vista/registro.php?mensaje=Las contraseñas no coinciden");
        exit();
    }

    if (isset($_FILES['imagen_usuario']) && $_FILES['imagen_usuario']['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $_FILES['imagen_usuario']['tmp_name'];
        $originalName = $_FILES['imagen_usuario']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed, true)) {
            echo "error de registro: formato de imagen no permitido.";
            exit();
        }

        $uploadDir = __DIR__ . '/../uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('img_', true) . '.' . $extension;
        $targetPath = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $targetPath)) {
            echo "error de registro: no se pudo guardar la imagen.";
            exit();
        }

        $rutaImagen = 'uploads/' . $fileName;
    }
    if ($pwd_confirm == $pwd) {
        insertUsuario($conn, $nombre,$pwd,$email,$rutaImagen);
    }else{
        header("Location: ../Vista/registro.php?mensaje=Las contraseñas no coinciden");
        exit();
    }

}


function logearUsuario($conn) {
    $login_email = $_POST["login_email"] ?? '';
    $login_pwd = $_POST["login_password"] ?? '';

    $stmt = $conn->prepare("SELECT id, usr_name, usr_email, imagen FROM usuario WHERE usr_email = ? AND usr_pass = ?");
    $stmt->bind_param("ss", $login_email, $login_pwd);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['usr_name'];
        $_SESSION['usuario_email'] = $usuario['usr_email'];
        $_SESSION['usuario_imagen'] = $usuario['imagen'];

        $stmt->close();
        $conn->close();
        header("Location: ../Vista/perfil.php");
        exit();
    }

    $stmt->close();
    $conn->close();
    header("Location: ../Vista/sesion.php");
    exit();
}

function logoutUsuario() {
    // Limpiar todas las variables de sesión
    $_SESSION = array();

    // Borrar la cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    session_destroy();
    header("Location: ../Vista/sesion.php");
    exit();
}
function publicar($conn) {
    $contenido = $_POST['contenido'] ?? '';
    $usuario_id = $_POST['usuario_id'] ?? $_SESSION['usuario_id'] ?? null;



    if (empty($contenido)) {
        header("Location: ../Vista/perfil.php?mensaje=El contenido no puede estar vacío");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO publicaciones (id_usuario, mensaje, fecha_publicacion) VALUES (?, ?, NOW())");
    $stmt->bind_param("is",$usuario_id, $contenido);
    $stmt->execute();
    $stmt->close();
    header("Location: ../Vista/perfil.php");
    exit();
}

?>