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
        <h2>Registro</h2>
        <form class="form" action="../Controlador/server-registro.php" method="post">
            <div class="div">
                <label class="label" for="nom">
                    nombre
                
                </label>
                <input class="label__input" name="name" id="nom" type="text" placeholder="Juan Perez">
            </div>
            <div class="div">
                <label class="label" for="email">
                    email
                
                </label>
                <input class="label__input" name="email"id="email" type="email" placeholder="algo@gmail.com">
            </div>
            <div class="div">
                <label class="label" for="pwd">password</label>
               
                <input class="label__input" name="password" id="pwd"type="password">
            </div>
            
            
            <input type="submit" class="label__input input__submit">
        </form>
        <p class="p">¿Tienes cuenta? <a href="sesion.php">Click aqui</a> para iniciar sesion.</p>
    </main>
</body>
</html>