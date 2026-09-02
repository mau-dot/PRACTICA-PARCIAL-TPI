<?php
session_start();
$_SESSION["usuarios"]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Procesados</title>
</head>
<body>
    
    <h1>DATOS DEL USUARIO</h1>
    <ul>
        <?php foreach($_SESSION["usuarios"] as $usuario){ ?>
            <p>
                <?= $usuario["nombre"] ?> - <?= $usuario["edad"] ?>
            </p>
        <?php } ?>
    </ul>
    
</body>
</html>