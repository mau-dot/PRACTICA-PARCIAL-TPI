<?php
echo" \" Holaa mundo \" Luffy culero ";
const PERRA = "Perra";
echo PERRA;
echo "<br>";

$variable = 67;
echo $variable;
echo "<br><br>";
var_dump( $variable );

//variables globales


$numero = 67;

function funcion(){
    global $numero;
    var_dump($numero);
}

funcion();
echo"<br>";
//variable estatica
function contador(){
    static $contador = 0;
    $contador++;
    echo $contador;
}
contador();
contador();
contador();


//array indexado
echo "<br><br>";
$arrayindeado = ["luis", "carlos", "juan"];
var_dump($arrayindeado);
echo "<br>";//recorriendo el array
foreach($arrayindeado as $valor){
    echo "<p>$valor</p>";
}
echo "<br><br>";
//array asociativo : tiene par clave => valor
$arrayasociativo = [
    "nombre"=>"mau",
    "carnet"=>"PG23022",
    "edad"=> 22,
    "nota"=> 6.7
];
var_dump($arrayasociativo);
//recorriendo
echo "<br><br>";
//array muldidimencional
$arrayMuldimensional = [
    ["nombre"=>"mau", "edad"=>22],
    ["nombre"=> "Luis", "edad"=> 21],
    ["nombre"=> "dariel", "edad"=>22]
];
$numero = 10;
function porValor ($n){
    $n = 100;
}

porValor($numero);
echo $numero;

function porReferencia(&$n){
    $n = 100;
}
echo "<br>";
porReferencia($numero);
echo $numero;





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Datos del Estudiante <?= $arrayasociativo["nombre"]?></h2>
    <?php foreach($arrayasociativo as $clave => $valor) { ?>
            <li><?= $clave ?> - <?= $valor ?></li>
    <?php } ?>

    <h2>Array Multidimencional</h2>
    <ul>
        <?php foreach($arrayMuldimensional as $arrays){ ?>
          
            <li>Nombre :<?= $arrays["nombre"] ?> - Edad :<?= $arrays["edad"] ?></li>
            
        <?php } ?>
    </ul>

</body>
</html>