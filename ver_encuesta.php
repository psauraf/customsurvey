<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login_encuesta.php");
    exit();
}

$id_encuesta = $_GET['id'];
$conexion = new mysqli("localhost", "root", "", "encuestas");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT textoPregunta FROM encuesta WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_encuesta);
$stmt->execute();
$resultado = $stmt->get_result();
$pregunta = $resultado->fetch_assoc()["textoPregunta"];

$sql_respuestas = "SELECT id, textoRespuesta FROM respuesta WHERE idEncuesta = ?";
$stmt_respuestas = $conexion->prepare($sql_respuestas);
$stmt_respuestas->bind_param("i", $id_encuesta);
$stmt_respuestas->execute();
$resultado_respuestas = $stmt_respuestas->get_result();

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Encuesta</title>
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

        .survey-container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .survey-container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .survey-question {
            font-size: 20px;
            color: #555;
            margin-bottom: 20px;
        }

        .option {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .option input[type="radio"] {
            margin-right: 10px;
        }

        input[type="submit"] {
            background-color: #3399ff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1a8cff;
        }

        .back-link {
            color: #3399ff;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }

        .user-info {
            position: fixed;
            top: 10px;
            right: 20px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="user-info">
        Estás logado como ' . $_SESSION['login'] . ' (' . ($_SESSION['tipoUsuario'] == "admin" ? "administrador" : "votante") . ')
    </div>
    <div class="survey-container">
        <h1>Responder Encuesta</h1>
        <p class="survey-question">' . $pregunta . '</p>
        <form action="mostrar_resultados.php" method="post">';

while ($fila = $resultado_respuestas->fetch_assoc()) {
    echo '<div class="option">
            <input type="radio" name="respuesta" value="' . $fila['id'] . '" required>
            <label>' . $fila['textoRespuesta'] . '</label>
          </div>';
}

echo '      <input type="hidden" name="id_encuesta" value="' . $id_encuesta . '">
            <input type="submit" value="Enviar respuesta">
        </form>
        <a href="selecciona_encuesta.php" class="back-link">Volver a Encuestas</a>
    </div>
</body>
</html>';

$conexion->close();
?>
