<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
    <main>
        <h2 class="h2">Inicia sesion</h2>
        <form id="loginForm" class="form"  method="post">
            <input type="hidden" name="accion" value="login">
            
            <div class="div">
                <label class="label" for="email">
                    email
                
                </label>
                <input class="label__input" name="login_email" id="login_email" type="email" placeholder="algo@gmail.com">
            </div>
            <div class="div">
                <label class="label" for="pwd">password</label>
               
                <input class="label__input" name="login_password" id="login_password"type="password">
            </div>
            
            
            <input type="submit" class="label__input input__submit">
        </form>
        <p class="p">¿No tienes cuenta? <a href="registro.php">Click aqui</a> para crear una cuenta.</p>
    </main>
    <script>
        const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('login_email');
        const password = document.getElementById('login_password');
        const body = {
            usr_email: email.value,
            usr_pass: password.value
        };
        try{
fetch('../Controlador/api.php/login', {
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
                window.location.href = 'perfil.php';
            } else {
                alert(data.error || 'Error en el inicio de sesión');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error en la conexión con el servidor');
        });
        }catch(error){
            console.error('Error:', error);
            alert('Error en la conexión con el servidor');
        }
        

    });
    </script>
</body>
</html>
