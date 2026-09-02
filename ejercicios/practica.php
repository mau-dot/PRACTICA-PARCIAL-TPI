<?php

session_start();

define('CARGO_ADMINISTRATIVO', 5.00); 
define('PORCENTAJE_DESCUENTO', 0.10); 


$videojuegos = [
    'lol' => [
        'nombre' => 'League of Legends',
        'categoria' => 'MOBA',
        'costo' => 25.00,
        'cupo_maximo' => 20
    ],
    'valorant' => [
        'nombre' => 'Valorant',
        'categoria' => 'Shooter',
        'costo' => 30.00,
        'cupo_maximo' => 15
    ],
    'smash' => [
        'nombre' => 'Super Smash Bros. Ultimate',
        'categoria' => 'Peleas',
        'costo' => 20.00,
        'cupo_maximo' => 16
    ],
    'fifa' => [
        'nombre' => 'EA Sports FC / FIFA',
        'categoria' => 'Deportes',
        'costo' => 15.00,
        'cupo_maximo' => 32
    ]
];


if (!isset($_SESSION['participantes'])) {
    $_SESSION['participantes'] = [];
}


$errores = [];
$comprobante = null;


function calcularCostoInscripcion(float $costoBase, string $nivelExperiencia): array {
    $descuento = 0.0;
    

    if (strtolower($nivelExperiencia) === 'principiante') {
        $descuento = $costoBase * PORCENTAJE_DESCUENTO;
    }
    
    $subtotal = $costoBase - $descuento;
    $total = $subtotal + CARGO_ADMINISTRATIVO;

    return [
        'costo_original' => $costoBase,
        'descuento' => $descuento,
        'cargo_admin' => CARGO_ADMINISTRATIVO,
        'total' => $total
    ];
}


function obtenerCategoriaParticipante(int $edad, string $nivel): string {
    
    $rangoEdad = match (true) {
        $edad < 18 => 'Junior',
        $edad >= 18 && $edad <= 25 => 'Universitario Senior',
        default => 'Master',
    };

    return $rangoEdad . " - " . ucfirst($nivel);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recuperar y limpiar datos con funciones de cadena (trim, htmlspecialchars)
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $edadInput = isset($_POST['edad']) ? trim($_POST['edad']) : '';
    $correo = isset($_POST['correo']) ? trim(strtolower($_POST['correo'])) : '';
    $videojuegoKey = isset($_POST['videojuego']) ? trim($_POST['videojuego']) : '';
    $modalidad = isset($_POST['modalidad']) ? trim($_POST['modalidad']) : '';
    $nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : '';

    // 1. Validaciones
    if (empty($nombre) || strlen($nombre) < 3) {
        $errores[] = "El nombre es obligatorio y debe tener al menos 3 caracteres.";
    }

    if ($edadInput === '' || !is_numeric($edadInput) || (int)$edadInput <= 0) {
        $errores[] = "Debe ingresar una edad válida (número entero positivo).";
    } else {
        $edad = (int)$edadInput;
    }

    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Debe proporcionar un correo electrónico válido.";
    }

    if (empty($videojuegoKey) || !array_key_exists($videojuegoKey, $videojuegos)) {
        $errores[] = "El videojuego seleccionado no es válido o no está disponible.";
    }

    if (empty($modalidad)) {
        $errores[] = "Debe seleccionar una modalidad de participación.";
    }

    if (empty($nivel)) {
        $errores[] = "Debe seleccionar un nivel de experiencia.";
    }

    // 2. Si no hay errores, procesar la inscripción
    if (empty($errores)) {
        $juegoSeleccionado = $videojuegos[$videojuegoKey];
        $categoriaAsignada = obtenerCategoriaParticipante($edad, $nivel);
        $costos = calcularCostoInscripcion($juegoSeleccionado['costo'], $nivel);

        // Estructura del registro
        $nuevoRegistro = [
            'nombre' => htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'),
            'edad' => $edad,
            'correo' => htmlspecialchars($correo, ENT_QUOTES, 'UTF-8'),
            'juego' => htmlspecialchars($juegoSeleccionado['nombre'], ENT_QUOTES, 'UTF-8'),
            'modalidad' => htmlspecialchars($modalidad, ENT_QUOTES, 'UTF-8'),
            'nivel' => htmlspecialchars($nivel, ENT_QUOTES, 'UTF-8'),
            'categoria' => $categoriaAsignada,
            'costo_original' => $costos['costo_original'],
            'descuento' => $costos['descuento'],
            'cargo_admin' => $costos['cargo_admin'],
            'total' => $costos['total']
        ];

        
        $_SESSION['participantes'][] = $nuevoRegistro;

        
        $comprobante = $nuevoRegistro;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneo Universitario de Videojuegos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 20px; color: #333; }
        h1, h2, h3 { color: #1a252f; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], input[type="email"], select {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        .btn { background-color: #27ae60; color: white; border: none; padding: 12px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #219150; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .comprobante { background-color: #e8f4f8; border-left: 5px solid #2980b9; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container">
    <h1>Torneo Universitario de Videojuegos</h1>
    <p>Inscripción oficial de participantes</p>
    <hr>

    
    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            <strong>Por favor, corrija los siguientes errores:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <?php if ($comprobante): ?>
        <div class="comprobante">
            <h2>Comprobante de Inscripción</h2>
            <p><strong>Participante:</strong> <?= $comprobante['nombre'] ?> (<?= $comprobante['edad'] ?> años)</p>
            <p><strong>Correo:</strong> <?= $comprobante['correo'] ?></p>
            <p><strong>Videojuego:</strong> <?= $comprobante['juego'] ?></p>
            <p><strong>Categoría Asignada:</strong> <?= $comprobante['categoria'] ?></p>
            <p><strong>Modalidad:</strong> <?= $comprobante['modalidad'] ?></p>
            <hr>
            <p>Costo Original: $<?= number_format($comprobante['costo_original'], 2) ?></p>
            <p>Descuento Aplicado (Principiante 10%): -$<?= number_format($comprobante['descuento'], 2) ?></p>
            <p>Cargo Administrativo: $<?= number_format($comprobante['cargo_admin'], 2) ?></p>
            <h3>Total a Pagar: $<?= number_format($comprobante['total'], 2) ?></h3>
        </div>
    <?php endif; ?>

    
    <h2>Formulario de Registro</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" min="1" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="form-group">
            <label for="videojuego">Seleccione Videojuego:</label>
            <select id="videojuego" name="videojuego" required>
                <option value="">-- Seleccionar --</option>
                <!-- Uso de foreach para recorrer los videojuegos -->
                <?php foreach ($videojuegos as $clave => $juego): ?>
                    <option value="<?= $clave ?>">
                        <?= $juego['nombre'] ?> (<?= $juego['categoria'] ?>) - $<?= number_format($juego['costo'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="modalidad">Modalidad de Participación:</label>
            <select id="modalidad" name="modalidad" required>
                <option value="Presencial">Presencial</option>
                <option value="Online">Online</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nivel">Nivel de Experiencia:</label>
            <select id="nivel" name="nivel" required>
                <option value="Principiante">Principiante (10% descuento)</option>
                <option value="Intermedio">Intermedio</option>
                <option value="Avanzado">Avanzado</option>
            </select>
        </div>

        <button type="submit" class="btn">Procesar Inscripción</button>
    </form>

    <hr style="margin-top: 30px;">

    <!-- TABLA DE PARTICIPANTES REGISTRADOS -->
    <h2>Lista de Participantes Registrados</h2>
    <?php if (!empty($_SESSION['participantes'])): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Videojuego</th>
                    <th>Categoría</th>
                    <th>Modalidad</th>
                    <th>Total Pagado</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($_SESSION['participantes'] as $indice => $p): ?>
                    <tr>
                        <td><?= $indice + 1 ?></td>
                        <td><?= $p['nombre'] ?></td>
                        <td><?= $p['correo'] ?></td>
                        <td><?= $p['juego'] ?></td>
                        <td><?= $p['categoria'] ?></td>
                        <td><?= $p['modalidad'] ?></td>
                        <td>$<?= number_format($p['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay participantes registrados hasta el momento.</p>
    <?php endif; ?>

</div>

</body>
</html>