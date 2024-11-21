<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login_encuesta.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_encuesta = $_POST['id_encuesta'];
    $respuesta_seleccionada = $_POST['respuesta'];

    $conexion = new mysqli("localhost", "root", "", "encuestas");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Actualizar el conteo de votos para la respuesta seleccionada
    $sql_update_respuesta = "UPDATE respuesta SET numeroRespuestas = numeroRespuestas + 1 WHERE id = ?";
    $stmt = $conexion->prepare($sql_update_respuesta);
    $stmt->bind_param("i", $respuesta_seleccionada);
    $stmt->execute();

    // Obtener el total de respuestas para calcular los porcentajes
    $sql_total_respuestas = "SELECT SUM(numeroRespuestas) AS totalRespuestas FROM respuesta WHERE idEncuesta = ?";
    $stmt_total = $conexion->prepare($sql_total_respuestas);
    $stmt_total->bind_param("i", $id_encuesta);
    $stmt_total->execute();
    $resultado_total = $stmt_total->get_result()->fetch_assoc();
    $total_respuestas = $resultado_total['totalRespuestas'];

    // Obtener cada respuesta con su porcentaje
    $sql_porcentajes = "SELECT textoRespuesta, numeroRespuestas FROM respuesta WHERE idEncuesta = ?";
    $stmt_porcentajes = $conexion->prepare($sql_porcentajes);
    $stmt_porcentajes->bind_param("i", $id_encuesta);
    $stmt_porcentajes->execute();
    $resultado_porcentajes = $stmt_porcentajes->get_result();

    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de la encuesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .results-container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .results-container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .result-item {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
            text-align: left;
        }

        .result-item .percentage-bar {
            background-color: #3399ff;
            height: 20px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .result-item .percentage-bar span {
            color: white;
            padding-left: 5px;
            font-weight: bold;
        }

        .back-link {
            color: #3399ff;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h1>Resultados de la encuesta</h1>';

    // Mostrar cada opción de respuesta con su porcentaje y número de votos
    while ($fila = $resultado_porcentajes->fetch_assoc()) {
        $porcentaje = ($total_respuestas > 0) ? ($fila['numeroRespuestas'] / $total_respuestas) * 100 : 0;
        echo '<div class="result-item">
                <p>' . $fila['textoRespuesta'] . ' - ' . number_format($porcentaje, 2) . '% (' . $fila['numeroRespuestas'] . ' votos)</p>
                <div class="percentage-bar" style="width: ' . $porcentaje . '%;">
                    <span>' . number_format($porcentaje, 2) . '%</span>
                </div>
              </div>';
    }

echo '<p><a href="selecciona_encuesta.php" class="back-link">Volver a Encuestas</a></p>
    </div>
</body>
</html>';

    $conexion->close();
}
?>
