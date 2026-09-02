<?php
session_start();
//inicializacion de varable global de sesion de usuario
$_SESSION["clases"] ??= [];
//verificacion de metodo de envio
if($_SERVER["REQUEST_METHOD"] === "POST"){
    //validacion de campos vacios
    if(!empty($_POST["nombre"]) && !empty($_POST["edad"])){
        //guardamos en la Global array de 
        $_SESSION["clases"][] = [
            $_POST["clase"]=>["nombre" =>$_POST["nombre"], "edad" => $_POST["edad"]]
        ];
    }
}

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
        <?php foreach($_SESSION["clases"] as $clase){ 
                foreach($clase as $key => $value){
            ?>      
            <p>
                <?= $key?> : <?= $value["nombre"] ?> - <?= $value["edad"] ?>
            </p>
        <?php } 
        }        
        ?>
    </ul>
    
</body>
</html>