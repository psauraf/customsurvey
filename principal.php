<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login_encuesta.php");
    exit();
}

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
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

        .panel-container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .panel-container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .panel-container p {
            font-size: 18px;
            color: #555;
            margin: 15px 0;
        }

        a.button-link {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #3399ff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a.button-link:hover {
            background-color: #1a8cff;
        }

        .logout {
            color: #ff0000;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h1>Bienvenido, ' . $_SESSION['login'] . '</h1>
        <p><a href="selecciona_encuesta.php" class="button-link">Votar Encuestas</a></p>';

if ($_SESSION['tipoUsuario'] == 'admin') {
    echo '<p><a href="alta_encuesta.php" class="button-link">Crear Encuestas</a></p>';
}

echo '<p><a href="logout_encuesta.php" class="button-link logout">Cerrar sesión</a></p>
    </div>
</body>
</html>';
?>
