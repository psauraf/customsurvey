<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login_encuesta.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "encuestas");

if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

$query = "SELECT * FROM encuesta";
$result = $mysqli->query($query);

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona una Encuesta</title>
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

        .container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 18px;
            color: #555;
            margin: 15px 0;
        }

        .survey-link {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background-color: #3399ff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .survey-link:hover {
            background-color: #1a8cff;
        }

        .logout-link {
            color: #ff0000;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }

        /* Posicionar el mensaje de usuario logueado en la esquina superior derecha */
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
    <div class="container">
        <h1>Seleccione una Encuesta</h1>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<a href="ver_encuesta.php?id=' . $row['id'] . '" class="survey-link">Encuesta ' . $row['id'] . ': ' . $row['textoPregunta'] . '</a>';
    }
} else {
    echo '<p>No hay encuestas disponibles.</p>';
}

echo '<p><a href="logout_encuesta.php" class="logout-link">Cerrar sesión</a></p>
    </div>
</body>
</html>';

$mysqli->close();
?>
