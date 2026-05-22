<?php
include '../Modelo/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: sesion.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Usuario';
$rutaImagen = $_SESSION['usuario_imagen'] ?? null;
$usuarioId = $_SESSION['usuario_id'];
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
                <img src="../Controlador/uploads/<?php echo htmlspecialchars($rutaImagen); ?>" alt="Imagen de perfil" class="user__img">
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
        <form class="logout__form" id="logoutForm">
            <input type="hidden" name="accion" value="logout">
            <button class="logout__btn" type="submit">Cerrar sesion</button>
        </form>

        
        
        
        <div>
            <h3>Publicaciones</h3>
            <div id="publicaciones-container">
                
            </div>
        </div>
        <div class="div__publicaciones">
            <h3>Publicar</h3>
            <form method="post" class="form" value="Publicar">
                <input type="hidden" name="accion" value="Publicar">
                <textarea name="contenido" placeholder="Escribe tu publicación aquí..." required class="label__input" id="contenido"></textarea>
                <input type="submit" value="Publicar" class="input__submit" id="publicarBtn">
            </form>
        </div>
        
    </main>
    <footer class="footer">

    </footer>
    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
            // Logout handler
            const logoutForm = document.getElementById('logoutForm');
            logoutForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                try {
                    const response = await fetch('../Controlador/api.php/logout', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    if (response.ok) {
                        window.location.href = 'sesion.php';
                    } else {
                        alert(data.error || 'Error al cerrar sesión');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error en la solicitud: ' + error.message);
                }
            });

            const inputPublicar = document.getElementById('publicarBtn');
            inputPublicar.addEventListener('click', (e) => {
                e.preventDefault();
                const contenido = document.getElementById('contenido').value;
                const body = { 
                    mensaje: contenido,
                    id_usuario: <?php echo json_encode($_SESSION['usuario_id']); ?>


                 };
                fetch('../Controlador/api.php/publicar', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                })
                .then(async response => {
                    const data = await response.json();
                    if (response.ok && data.success) {
                        alert('Publicación creada con éxito');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al crear la publicación');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error en la solicitud: ' + error.message);
                });
            });
            fetch('../Controlador/api.php/publicaciones')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('publicaciones-container');
                    data.forEach(pub => {
                        const pubDiv = document.createElement('div');
                        pubDiv.classList.add('publicacion');
                        pubDiv.textContent = pub.mensaje;
                        container.appendChild(pubDiv);
                    });
                })
                .catch(error => console.error('Error al cargar publicaciones:', error));


        });
    </script>
    
</body>
</html>
