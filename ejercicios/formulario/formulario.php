<?php
$_SESSION = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario POST</title>
</head>
<body>
    <div>
        <form action="informacion.php" method="post" style="display: flex; flex-direction: column; margin: 0 auto; padding-left:300px ; padding-right: 300px; gap: 20px;">
            <select name="clase" id="">
                <option value="TPI">TPI</option>
                <option value="HDP">HDP</option>
                <option value="SIC">Sistemas Contables</option>
            </select>
            <input type="text" name="nombre" placeholder="Ingrese el nombre">
            <input type="number" name="edad" placeholder="Ingrese su edad">
            <div style="display: flex; flex-direction: column;">
                <label for="">Ingrese Nota 1</label>
                <input type="number" name="notas[]" id="">
                <label for="">Ingrese Nota 2</label>
                <input type="number" name="notas[]" id="">
                <label for="">Ingrese Nota 3</label>
                <input type="number" name="notas[]" id="">
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
 
</body>
</html>