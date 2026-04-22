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
        <form class="logout__form" action="../Controlador/server-auth.php" method="post">
            <input type="hidden" name="accion" value="logout">
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
            echo '<h4 id="nombre-seleccionado"class="user__name-selected">' . $primerNombre . '</h4>';
            ?>
            
            
            
        </div>
        <div>
            <h3>Publicaciones</h3>
            <div id="publicaciones-container">
                <?php
                    $publicaciones = array();
                    $sql = "SELECT p.mensaje, u.usr_name, u.imagen, p.fecha_publicacion FROM publicaciones p JOIN usuario u ON p.id_usuario = u.id ORDER BY p.fecha_publicacion DESC";
                    $resultado = mysqli_query($conn, $sql);
                    if ($resultado) {
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            $publicaciones[] = $fila;
                        }
                    }
                    foreach ($publicaciones as $publicacion) {
                        $mensaje = htmlspecialchars($publicacion['mensaje']);
                        $nombre = htmlspecialchars($publicacion['usr_name']);
                        $imagen = htmlspecialchars($publicacion['imagen']);
                        $fecha = htmlspecialchars($publicacion['fecha_publicacion']);
                        echo "<div class='publicacion'>";
                        echo "<img src='../$imagen' alt='Imagen de perfil' class='user__img-publicacion'>";
                        echo "<div class='contenido-publicacion'>";
                        echo "<h4>$nombre</h4>";
                        echo "<p>$mensaje</p>";
                        echo "<span class='fecha-publicacion'>$fecha</span>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="div__publicaciones">
            <h3>Publicar</h3>
            <form action="../Controlador/server-auth.php" method="post" class="form" value="Publicar">
                <input type="hidden" name="accion" value="Publicar">
                <textarea name="contenido" placeholder="Escribe tu publicación aquí..." required class="label__input"></textarea>
                <input type="submit" value="Publicar" class="input__submit">
            </form>
        </div>
    </main>
    <footer class="footer">

    </footer>
    <script src="scripts.js"></script>
</body>
</html>
