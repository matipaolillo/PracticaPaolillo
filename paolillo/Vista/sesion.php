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
        <h1>Inicia sesion</h1>
        <form class="form" action="../Controlador/proceso_sesion.php" method="post">
            
            <div class="div">
                <label class="label" for="email">
                    email
                
                </label>
                <input class="label__input" name="login_email" id="email" type="email" placeholder="algo@gmail.com">
            </div>
            <div class="div">
                <label class="label" for="pwd">password</label>
               
                <input class="label__input" name="login_password" id="pwd"type="password">
            </div>
            
            
            <input type="submit" class="label__input input__submit">
        </form>
        <p class="p">¿No tienes cuenta? <a href="/paolillo/Vista/registro.php">Click aqui</a> para crear una cuenta.</p>
    </main>
</body>
</html>