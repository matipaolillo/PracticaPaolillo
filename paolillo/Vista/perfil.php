<?php
include '../Modelo/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: sesion.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Usuario';
$rutaImagen = $_SESSION['usuario_imagen'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Perfil</title>
</head>
<body>
    <main>
        <div class="div">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
            <?php if (!empty($rutaImagen)): ?>
                <img src="../<?php echo htmlspecialchars($rutaImagen); ?>" alt="Imagen de perfil" class="user__img">
            <?php else: ?>
                <p>No tienes imagen de perfil cargada.</p>
            <?php endif; ?>
            <?php if (false): ?>
<h1>¡Bienvenido!</h1>
            <?php endif; ?>
        </div>
        
        <div class="div grid__cont">
            <div>JavaScript</div>
            <div>HTML</div>
            <div>CSS</div>
            <div>php</div>
        </div>
        <form class="logout__form" action="../Controlador/logout-proceso.php" method="post">
            <button class="logout__btn" type="submit">Cerrar sesion</button>
        </form>

        <div class="div__usuarios">
            <?php
            // Obtener todos los usuarios (nombre e imagen)
            $usuarios = array();
            $sql = "SELECT usr_name, imagen FROM usuario";
            $resultado = mysqli_query($conn, $sql);
            if ($resultado) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $usuarios[] = $fila;
                }
            }
            foreach ($usuarios as $usuario) {
                $nombre = htmlspecialchars($usuario['usr_name']);
                $imagen = htmlspecialchars($usuario['imagen']);
                echo "<button class='usuario-btn' type='button' data-nombre='$nombre' data-imagen='$imagen'>$nombre</button> ";
            }
            ?>
        </div>
        <div id="usuario-seleccionado" class="div_seleccionado" >
            <?php
            $primerImagen = $usuarios[0]['imagen'] ?? '../uploads/predeterminada.webp';
            $primerNombre = $usuarios[0]['usr_name'] ?? 'Usuario';
            echo '<img id="imagen-seleccionada" src="../' . $primerImagen . '" alt="Imagen de perfil" style="max-width:150px;" class="user__img-selected">';
            echo '<h3 id="nombre-seleccionado"class="user__name-selected">' . $primerNombre . '</h3>';
            ?>
            
            
            
        </div>
    </main>
    <footer class="footer">

    </footer>
    <script src="scripts.js"></script>
</body>
</html>
