<?php
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
    </main>
    <footer class="footer">

    </footer>
</body>
</html>
